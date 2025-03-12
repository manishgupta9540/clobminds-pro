<?php

namespace App\Http\Controllers\Vendor;

use App\Exports\VendorMultipleTask;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;
use Illuminate\Support\Facades\Validator;
use App\Models\Vendor\VendorVerificationData;
use App\Models\Vendor\VendorVerificationStatus;
use Illuminate\Support\Facades\Mail;
use App\Models\Vendor\VendorTaskAssignment;
use App\User;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;


class TaskController extends Controller
{
    //all vendor task
   public function index(Request $request)
   {
        $rows=10;
        
        $task_users = DB::table('users')->where(['business_id'=>Auth::user()->business_id,'user_type'=>'vendor_user'])->get();
        $services = DB::table('services')
        ->select('name','id')
        ->where(['business_id'=>NULL,'verification_type'=>'Manual'])
        ->whereNotIn('type_name',['e_court'])
        ->orwhere('business_id',Auth::user()->parent_id)
        ->where(['status'=>'1'])
        ->get();
        $vendor_tasks = DB::table('vendor_task_assignments')
                        ->whereIn('status',['1','2'])
                        ->where(['business_id'=>Auth::user()->business_id,'assigned_to'=>Auth::user()->id])->whereNull('reassigned_to')
                        ->orWhere(['reassigned_to'=>Auth::user()->id])
                        ->orderBy('id','DESC')->get();
        $user_name = DB::table('vendor_tasks as vt')->select('t.name','t.id')->join('tasks as t','t.id','=','vt.task_id')->where(['vt.business_id'=>Auth::user()->id])->whereIn('vt.status',['1','2'])->groupBy('name')->get();
        // dd($user_name);
        $tasks = DB::table('vendor_tasks as vt')->select('t.name','vt.*')->join('tasks as t','t.id','=','vt.task_id')->where(['vt.business_id'=>Auth::user()->id])->whereIn('vt.status',['1','2'])->orderBy('vt.id','DESC');
                        if($request->get('from_date') !=""){
                            $tasks->whereDate('vt.created_at','>=',date('Y-m-d',strtotime($request->get('from_date'))));
                        }
                        if($request->get('to_date') !=""){
                            $tasks->whereDate('vt.created_at','<=',date('Y-m-d',strtotime($request->get('to_date'))));
                        }
                        // if(is_numeric($request->get('customer_id'))){
                        //      $tasks->where('t.business_id',$request->get('customer_id'));
                        // }
                        if($request->get('candidate_id')){
                            // echo($request->get('candidate_id'));
                             $tasks->where('t.name',$request->get('candidate_id'));
                        }
                        if(is_numeric($request->get('service_id'))){
                            // echo($request->get('service_id'));
                            $tasks->where('vt.service_id',$request->get('service_id'));
                        }
                        // if(is_numeric($request->get('user_id'))){
                        //     $tasks->where('ta.user_id',$request->get('user_id'));
                        // }
                        // if($request->get('task_type')){
                        //     $tasks->where('t.description',$request->get('task_type'));
                        // }
                        // if($request->get('assign_status')){
                           
                        //     if ($request->get('assign_status')=='assigned') {
                        //         $tasks->whereNotNull('assigned_to');
                        //     }
                        //     else{
                        //         $tasks->whereNull('assigned_to');
                        //     }
                           
                        // }
                        if(is_numeric($request->get('complete_status'))){
                        // echo($request->get('complete_status'));
                            $tasks->where('vt.status',$request->get('complete_status'));
                        }
                        if ($request->get('rows')!='') {
                            $rows = $request->get('rows');
                        }
        $tasks=$tasks->paginate($rows);
    if($request->ajax())
        return view('vendor.task.ajax',compact('tasks','task_users','vendor_tasks','services','user_name'));
    else
        return view('vendor.task.index',compact('tasks','task_users','vendor_tasks','services','user_name'));

   }

   public function generatePdf($id,$service_id,$check_number)
   {
       // $user_id = Auth::user()->id;

       $candidate_id=base64_decode($id);
       $service_id=base64_decode($service_id);
       $check_number=base64_decode($check_number);
        //  dd($candidate_id);

       $candidate = Db::table('users as u')
        ->select('u.id','u.business_id','u.client_emp_code','u.entity_code','u.first_name','u.last_name','u.name','u.email','u.phone','u.dob','u.aadhar_number','u.father_name','u.gender','u.digital_signature')  
        ->where(['u.id'=>$candidate_id]) 
        ->first(); 
       
       $jaf_items = DB::table('jaf_form_data as jf')
       ->select('jf.id','jf.form_data_all','jf.form_data','jf.check_item_number','jf.address_type','jf.insufficiency_notes','jf.is_insufficiency','jf.is_api_checked','jf.verification_status','jf.verified_at','s.name as service_name','s.id as service_id','s.verification_type')
       ->join('services as s','s.id','=','jf.service_id')
       ->where(['jf.candidate_id'=>$candidate_id,'jf.service_id'=>$service_id,'jf.check_item_number'=>$check_number])
       ->first();
       // dd($jaf_items);
       // echo '<pre>';print_r($jaf_items);
       // die;
       // $sla_items = DB::select("SELECT sla_id, GROUP_CONCAT(DISTINCT service_id) AS alot_services FROM `job_sla_items` WHERE candidate_id = $candidate_id");
        if ($service_id==1) {
            $pdf = PDF::loadView('vendor.task.pdf.address-pdf', compact('candidate','jaf_items'));
            return $pdf->download('jaf.pdf');
            // return view('vendor.task.pdf.address-pdf', compact('candidate','jaf_items'));
        }
        else {
            $pdf = PDF::loadView('vendor.task.pdf.jaf-pdf', compact('candidate','jaf_items'));
            return $pdf->download('BGV.pdf');
        }
   
   }

   public function uploadData(Request $request)
   {
        $business_id = Auth::user()->business_id;
            //   dd($request->vendor_task_id);
             $rules = [
                'attachment' => 'required',
                'verification_status' => 'required'
            ];

            $validator = Validator::make($request->all(), $rules);
                
            if ($validator->fails()){
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors() 
                ]);
            }

         $vendor_task = DB::table('vendor_tasks')->where(['id'=>$request->vendor_task_id,'status'=>'1'])->first();
        if ($vendor_task) {
              
            
            $new_verification_status = new VendorVerificationStatus;
            $new_verification_status->parent_id =$vendor_task->parent_id;
            $new_verification_status->business_id =$vendor_task->business_id;
            $new_verification_status->candidate_id =$vendor_task->candidate_id;
            $new_verification_status->vendor_task_id=$vendor_task->id;
            $new_verification_status->service_id=$vendor_task->service_id;
            $new_verification_status->vendor_sla_id=$vendor_task->vendor_sla_id;
            $new_verification_status->no_of_verification =$vendor_task->no_of_verification;
            $new_verification_status->remarks = $request->remark;
            $new_verification_status->status =$request->verification_status;
            $new_verification_status->created_by=Auth::user()->id;
            $new_verification_status->save();

         
            // batch attachment strat
            $attach_on_select=[];
            $allowedextension=['jpg','jpeg','png'];
            $zip_name="";
            //  $now= Carbon::parse($new_batch->created_at)->format('Ymdhis');
            if($request->hasFile('attachment') && $request->file('attachment') !="")
            {
                $filePath = public_path('/uploads/verification-file/'); 
                $files= $request->file('attachment'); 
                foreach($files as $file)
                {
                        $extension = $file->getClientOriginalExtension();
    
                        $check = in_array($extension,$allowedextension);

                        $file_size = number_format(File::size($file) / 1048576, 2);
                        
                        if(!$check)
                        {
                            return response()->json([
                            'fail' => true,
                            'errors' => ['attachment' => 'Only jpg,jpeg,png are allowed !'],
                            'error_type'=>'validation'
                            ]);                        
                        }

                        if($file_size > 10)
                        {
                            return response()->json([
                              'fail' => true,
                              'error_type'=>'validation',
                              'errors' => ['attachment' => 'The document size must be less than only 10mb Upload !'],
                            ]);                        
                        }
                }
    
                $zipname = 'verification_file-'.'-'.$vendor_task->id.'.zip';
                $zip = new \ZipArchive();      
                $zip->open(public_path().'/uploads/verification-file/'.$zipname, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
    
                foreach($files as $file)
                {
                    $file_data = $file->getClientOriginalName();
                    $tmp_data  = $vendor_task->id.'-'.$file_data; 
                    $data = $file->move($filePath, $tmp_data);       
                    $attach_on_select[]=$tmp_data;
    
                    $path=public_path()."/uploads/verification-file/".$tmp_data;
                    $zip->addFile($path, '/verification-file/'.basename($path));  
                }
    
                $zip->close();
            }

            if(count($attach_on_select)>0)
            {
                $i=0;
                foreach($attach_on_select as $item) 
                {
                    $new_verification_data= new VendorVerificationData;
                    $new_verification_data->parent_id =$vendor_task->parent_id;
                    $new_verification_data->business_id =$vendor_task->business_id;
                    $new_verification_data->candidate_id =$vendor_task->candidate_id;
                    $new_verification_data->vendor_task_id=$vendor_task->id;
                    $new_verification_data->service_id=$vendor_task->service_id;
                    $new_verification_data->vendor_sla_id=$vendor_task->vendor_sla_id;
                    $new_verification_data->vendor_verification_status_id =$new_verification_status->id;
                    $new_verification_data->no_of_verification =$vendor_task->no_of_verification;
                    $new_verification_data->file_name=$attach_on_select[$i];
                    $new_verification_data->zip_file = $zipname!=""?$zipname:NULL;
                    $new_verification_data->created_by = Auth::user()->id;
                    $new_verification_data->save();
                    $i++;
                }
            }
            $status = DB::table('vendor_verification_statuses')->select('status')->where('vendor_task_id',$request->vendor_task_id)->first();
            if ($status->status == 'done') {
                 DB::table('vendor_tasks')->where(['id'=>$request->vendor_task_id,'status'=>'1'])->update(['status'=>'2','completed_at'=>date('Y-m-d H:i:s'),'completed_by'=>Auth::user()->id]);
                 DB::table('vendor_task_assignments')->where(['vendor_task_id'=>$request->vendor_task_id,'status'=>'1'])->update(['status'=>'2']);
                 $completed_vendor_task=  DB::table('vendor_tasks')->where(['id'=>$request->vendor_task_id,'status'=>'2'])->first();
                 DB::table('task_assignments')->where(['task_id'=>$completed_vendor_task->task_id,'status'=>'1'])->update(['status'=>'2']);
                 DB::table('tasks')->where(['id'=>$completed_vendor_task->task_id,'is_completed'=>'0'])->update(['is_completed'=>'1']);

                // dd($updated_vendor_task);
                $user= User::where('id',$completed_vendor_task->parent_id)->first();
                if ($user->email) {
                    $email = $user->email;
                    $name  = $user->name;
                    $candidate_name =  Helper::user_name($completed_vendor_task->candidate_id);
                    $service_name = Helper::get_service_name($completed_vendor_task->service_id);
                    $msg = "Task has been completed of candidate";
                    $sender = DB::table('users')->where(['id'=>$business_id])->first();
                    $data  = array('name'=>$name,'email'=>$email,'candidate_name'=>$candidate_name,'msg'=>$msg,'service_name'=>$service_name,'sender'=>$sender);
        
                    Mail::send(['html'=>'mails.completed-task'], $data, function($message) use($email,$name) {
                        $message->to($email, $name)->subject
                            ('Clobminds System - Task Completed Notification');
                        $message->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
                    });
                }
            }
        }
        return response()->json([
            'success' => true,
            'errors' => []
        ]);
   }

    public function taskPreview(Request $request)
    {

        $form='';
        $task_id=$request->task_id;
        $vendor_task = DB::table('vendor_tasks')->where(['id'=>$task_id])->whereIn('status',['1','2'])->first();
        if ($vendor_task) {
            $ver_status=DB::table('vendor_verification_statuses')->where(['vendor_task_id'=>$vendor_task->id])->first();

            if($ver_status->remarks==NULL){
                $comments='N/A';
                $status = 'N/A';
             } else{
                $comments=$ver_status->remarks;
                $status = $ver_status->status=='done'?'Done' :'Unable to verify';
                
             }
            $form.='<div class="form-group">
                <label for="label_name"> <strong>Status:</strong> <span id="comments">'.$status.'</span></label>
                </div>';
            $form.='<div class="form-group">
                <label for="label_name"> <strong>Comments:</strong> <span id="comments">'.$comments.'</span></label>
                </div>';
            $upload_attach=DB::table('vendor_verification_data')->where(['vendor_task_id'=>$task_id])->get();
            if(count($upload_attach)>0)
            {
                $path=url('/').'/uploads/verification-file/';
                $form.='<div class="form-group">
                        <label><strong>Attachments: </strong></label>
                        <div class="row">';
                foreach($upload_attach as $upload)
                {
                    $img='';
                    $file=$path.$upload->file_name;
                    
                    $img='<img src="'.$file.'" alt="preview" style="height:100px;"/>';
                    
                    $form.='<div class="col-3">
                            <div class="image-area" style="width:110px;">
                                <a href="'.$file.'" download>
                                    '.$img.'
                                    <p style="font-size:15px;">'.'<i class="fas fa-file-download" >'.' '.'<small>'.'Download'.'</small>'.'</i>'.'</p>
                                </a>
                            </div>
                            </div>';
                } 
                $form.='</div>
                        </div>';
            }
            return $form;
        }
        // dd($task_id);
    }
    
    //Assign task to user
    public function assignUser(Request $request)
    {
        $business_id = Auth::user()->business_id;
     //    dd($request->users);
        $task_id = $request->vendors_task_id;
        $vendor_task =DB::table('vendor_task_assignments')->where(['vendor_task_id'=>$task_id,'status'=>'1'])->first();
        // dd($vendor_task);
        if ($vendor_task) {
            
            DB::table('vendor_task_assignments')->where(['vendor_task_id'=>$task_id,'status'=>'1'])->update(['assigned_to'=>$request->users,'assigned_by'=>Auth::user()->id,'assigned_at'=>date('Y-m-d H:i:s')]);
           


            $user= User::where('id',$request->users)->first();
                if ($user->email) {
                    $email = $user->email;
                    $name  = $user->name;
                    $candidate_name =  Helper::user_name($vendor_task->candidate_id);
                    $service_name = Helper::get_service_name($vendor_task->service_id);
                    $vendor_id =  Helper::user_name($vendor_task->business_id);
                    $msg = "Task has been assigned to you with the name of candidate";
                    $sender = DB::table('users')->where(['id'=>$business_id])->first();
                    $data  = array('name'=>$name,'email'=>$email,'candidate_name'=>$candidate_name,'msg'=>$msg,'service_name'=>$service_name,'vendor_id'=>$vendor_id,'sender'=>$sender);
        
                    Mail::send(['html'=>'mails.completed-task'], $data, function($message) use($email,$name) {
                        $message->to($email, $name)->subject
                            ('Clobminds System - Task Verification Notification');
                        $message->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
                    });
                }
            return response()->json([
                'success' => true,
                'errors' => []
            ]);
        }
    }
       //Re-Assign task to user
    public function reassignUser(Request $request)
    {
        $business_id = Auth::user()->business_id;
        $task_id = $request->reassign_task_id;
        $vendor_task =DB::table('vendor_task_assignments')->where(['vendor_task_id'=>$task_id,'status'=>'1'])->whereNotNull('assigned_to')->first();

        if ($vendor_task) {
            
            DB::table('vendor_task_assignments')->where(['vendor_task_id'=>$task_id,'status'=>'1'])->whereNotNull('assigned_to')->update(['status'=>'0']);

            $new_vendor_task_assign = new VendorTaskAssignment;
            $new_vendor_task_assign->parent_id = Auth::user()->parent_id;
            $new_vendor_task_assign->business_id =  Auth::user()->business_id;
            $new_vendor_task_assign->candidate_id = $vendor_task->candidate_id;
            $new_vendor_task_assign->vendor_task_id = $task_id;
            $new_vendor_task_assign->service_id = $vendor_task->service_id;
            $new_vendor_task_assign->vendor_sla_id = $vendor_task->vendor_sla_id;
            $new_vendor_task_assign->status = '1';
            $new_vendor_task_assign->no_of_verification = $vendor_task->no_of_verification;
            $new_vendor_task_assign->assigned_to = $vendor_task->assigned_to;
            $new_vendor_task_assign->assigned_by =$vendor_task->assigned_by;
            $new_vendor_task_assign->assigned_at = $vendor_task->assigned_at;
            $new_vendor_task_assign->reassigned_to = $request->users;
            $new_vendor_task_assign->reassigned_by = Auth::user()->id;
            $new_vendor_task_assign->reassigned_at = date('Y-m-d H:i:s');
            $new_vendor_task_assign->updated_by = Auth::user()->id;
            $new_vendor_task_assign->save();


            $user= User::where('id',$request->users)->first();
            if ($user->email) {
                $email = $user->email;
                $name  = $user->name;
                $candidate_name =  Helper::user_name($vendor_task->candidate_id);
                $service_name = Helper::get_service_name($vendor_task->service_id);
                $vendor_id =  Helper::user_name($vendor_task->business_id);
                $msg = "Task has been assigned to you with the name of candidate";
                $sender = DB::table('users')->where(['id'=>$business_id])->first();
                $data  = array('name'=>$name,'email'=>$email,'candidate_name'=>$candidate_name,'msg'=>$msg,'service_name'=>$service_name,'vendor_id'=>$vendor_id,'sender'=>$sender);
    
                Mail::send(['html'=>'mails.completed-task'], $data, function($message) use($email,$name) {
                    $message->to($email, $name)->subject
                        ('Clobminds System - Task Verification Notification');
                    $message->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
                });
            }
            return response()->json([
                'success' => true,
                'errors' => []
            ]);
        }
       
    }

     /**
     * set the session data
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function setSessionData( Request $request)
    {   
        //clear session data 
        // Session()->forget('customer_id');
        Session()->forget('task_id');
        Session()->forget('to_date');
        Session()->forget('from_date');
        Session()->forget('export_type');

        // Session()->forget('jaf_id');
        // Session()->forget('service_id');
        // dd($request->get('export_type'));
        if( ($request->get('export_type')) ){             
            session()->put('export_type', $request->get('export_type'));
        }
        if( ($request->get('task_id')) ){             
          session()->put('task_id', $request->get('task_id'));
        }
        // both date is selected 
        if($request->get('report_date') !="" && $request->get('to_date') !=""){
            session()->put('report_from_date', $request->get('report_date'));
            session()->put('report_to_date', $request->get('to_date'));
        }
        else
        {
          if($request->get('from_date') !=""){
            session()->put('from_date', $request->get('from_date'));
          }
          if($request->get('to_date') !=""){
            session()->put('to_date', $request->get('to_date'));
          }
        }
        //
        // if($request->get('check_id') !=""){
        //   session()->put('check_id', $request->get('check_id'));
        // }

        // if($request->get('jaf_id')!="")
        // {
        //   session()->put('jaf_id', $request->get('jaf_id'));
        // }

        // if($request->get('service_id')!="")
        // {
        //   session()->put('service_id', $request->get('service_id'));
        // }

        echo '1';
    }

    public function pdfExport(Request $request)
    {
        // dd($request->session()->get('export_type'));
        // var_dump($request->task_id);
        $from_date = $to_date= $customer_id=$business_id = $check_id = "";

      if( $request->session()->has('from_date') && $request->session()->has('to_date'))
        {  
          $from_date     =  $request->session()->get('from_date');
          $to_date       =  $request->session()->get('to_date');
        }
        else
        {
          if($request->session()->has('from_date'))
          {
            $from_date     =  $request->session()->get('from_date');
          }
        }
        if($request->session()->has('task_id'))
          {
            $vendor_task_id  =  $request->session()->get('task_id');
          }
          if($request->session()->has('export_type'))
          {
            $export_type  =  $request->session()->get('export_type');
          }
        // $vendor_task_id = $request->session()->get('task_id');
        // $vendor_tasks = DB::table('vendor_tasks')->whereIn('id',$vendor_task_id)->get();
        $service_id=[];
        // $candidate_id=[];
        $task_id=[];
            if ($export_type=='details') {
            
                foreach ($vendor_task_id as $key => $task) {
                
                    $tasks =  DB::table('vendor_tasks')->where(['id'=>$task,'status'=>'1'])->whereNotIn('service_id',[1])->first();
                    if ($tasks) {
                        
                        $task_id[]= $tasks->id;
                        $service_id[] = $tasks->service_id;
                        // $no_of_verifications[] =  $tasks->no_of_verification;
                    }
                }
                
                    //$candidate_id=array_values($candidate_id);
                $task_id=array_values($task_id);
                    // $no_of_verifications=array_values($no_of_verifications);
                    // dd($task_id); 
                    $service= array_unique($service_id);
                    sort($service);
                    // dd($service);
                    //rsort($candidate_id);
                    // foreach ($candidate_id as $key => $id) {
                    //   $job_sla_items=  DB::table('job_sla_items')->select('service_id','number_of_verifications')->where('candidate_id',$id)->get();
                    // }
                //dd($candidate_id);
                return Excel::download(new VendorMultipleTask($from_date,$to_date,$service,$task_id,$export_type),'task-all-checks-data.xlsx');
            }
            elseif ($export_type=='attachment') {
                $login = Auth::user()->id;
                $path=public_path('/uploads/candidate_details/'.$login.'/');
                if(File::exists($path))
                  {
                      File::cleanDirectory($path);
                  }
                   
                if (count($vendor_task_id)>0) {
                    $filePath = public_path('/uploads/candidate_details/'.$login.'/' );
                    if(!File::exists($filePath))
                    {
                        File::makeDirectory($filePath, $mode = 0777, true, true);
                    }
                    foreach ($vendor_task_id as $vendor_task) {
                        $tasks =  DB::table('vendor_tasks')->where(['id'=>$vendor_task,'status'=>'1'])->first();
                        // dd($tasks);
                        if ($tasks) {
                            $user = DB::table('users')->select('first_name')->where('id',$tasks->candidate_id)->first();
                            $service = DB::table('services')->select('name')->where('id',$tasks->service_id)->first();
                            $jaf_data_forms = DB::table('jaf_form_data')->where(['candidate_id'=>$tasks->candidate_id,'service_id'=>$tasks->service_id,'check_item_number'=>$tasks->no_of_verification])->first();

                            $zipname = '';
                        
                            $jaf_item_attachments = DB::table('jaf_item_attachments')->where(['jaf_id'=>$jaf_data_forms->id])->get();
                            // dd($jaf_item_attachments);
                            if(count($jaf_item_attachments)>0){
                                $zipname =  $user->first_name.'-'.$service->name.'-'.date('Ymdhis').'-'.$tasks->no_of_verification.'.zip';
                                $zip = new \ZipArchive();
                                $zip->open(public_path().'/uploads/candidate_details/'.$login.'/'.$zipname, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
                                foreach($jaf_item_attachments as $file)
                                {
                                    $file_data = $file->file_name;
                                    $tmp_data  = $user->first_name.'-'.$service->name.'-'.date('mdYHis').'-'.$file_data; 
                                    File::copy(public_path('/uploads/jaf-files/'.$file_data),public_path("/uploads/candidate_details/".$login.'/'.$tmp_data)); 
                                    // $data = $file_data->move($filePath, $tmp_data);
                                    $attach_on_select[]=$tmp_data; 
                                    $path=public_path()."/uploads/candidate_details/".$login.'/'.$tmp_data;
                                    $zip->addFile($path,'/candidate_details/'.basename($path));
                                }
                                $zip->close();
                            }
                        }
                    }
                    $megazipname='';
                    $files = File::files( $filePath );
                    if (count($files)>0) {
                        $megafilePath = public_path('/uploads/candidate_details/export/'.$login.'/' );
                        $megazipname ='all_export.zip';
                        $megazip = new \ZipArchive();
                        $megazip->open(public_path().'/uploads/candidate_details/export/'.$login.'/'.$megazipname, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
                        if(!File::exists($megafilePath))
                        {
                            File::makeDirectory($megafilePath, $mode = 0777, true, true);
                        }
                       
                        foreach ($files as $key => $file) {
                            // $filename=$file->getClientOriginalName()
                            $filename=$file->getFilename();
                            // dd();
                            $temp= explode('.',$file);
                            $extension = end($temp);
                            if ($extension=='zip') {
                                // dd($extension);
                                $megapath=public_path()."/uploads/candidate_details/".$login.'/'.$filename;
                                $megazip->addFile($megapath,'/export/'.basename($megapath));
                            }

                        }
                        $megazip->close();
                    } 
                    
                    // $file = public_path()."/guest/reports/zip/".$guest_master->zip_name;
                    $headers = array('Content-Type: application/zip');
                    return response()->download($megafilePath.$megazipname,$megazipname,$headers);
                }
            } 
        // dd($vendor_tasks);
        // if (count($vendor_tasks)>0) {
        //     foreach ($vendor_tasks as $vendor_task) {
        //         $user = DB::table('users')->select('first_name')->where('id',$vendor_task->candidate_id)->first();
        //         $service = DB::table('services')->select('name')->where('id',$vendor_task->service_id)->first();
        //         $jaf_data_forms = DB::table('jaf_form_data')->where(['candidate_id'=>$vendor_task->candidate_id,'service_id'=>$vendor_task->service_id,'check_item_number'=>$vendor_task->no_of_verification])->first();
                
        //         $zipname =  $user->first_name.'-'.$service->name.'-'.date('Ymdhis').'-'.$vendor_task->no_of_verification.'.zip';
        //         $zip = new \ZipArchive();      
        //         $zip->open(public_path().'/uploads/candidate_details/'.$zipname, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
        //         $jaf_item_attachments = DB::table('jaf_item_attachments')->where(['jaf_id'=>$jaf_data_forms->id])->get();
        //         if(count($jaf_item_attachments)>0){
        //             foreach($jaf_item_attachments as $file)
        //             {
        //                 $file_data = $file->getClientOriginalName();
        //                 $tmp_data  = $user->first_name.'-'.$service->name.'-'.date('mdYHis').'-'.$file_data; 
        //                 $data = $file->move($filePath, $tmp_data);
        //                 $attach_on_select[]=$tmp_data;
        //                 $path=public_path()."/uploads/candidate_details/".$tmp_data;
        //                 $zip->addFile($path,'/candidate_details/'.basename($path));  
        //             }
        //         }
                
        //     }
        // }
       
    }
}
