<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Exports\InsuffdataExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Traits\S3ConfigTrait;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Config;
class InsuffController extends Controller
{
    public function index(Request $request)
    {
        
        $user_id = Auth::user()->id;
        $business_id = Auth::user()->business_id;
        $rows=10;
        //Insuff Raised for customer
        // $raised_insuff = DB::select("SELECT jf.*,vi.status as vs_status,vi.created_by,GROUP_CONCAT(DISTINCT jf.service_id)as services FROM jaf_form_data as jf JOIN users as u ON u.id=jf.candidate_id JOIN verification_insufficiency as vi ON vi.jaf_form_data_id = jf.id WHERE jf.is_insufficiency='1' AND u.is_deleted ='0' AND vi.status='raised' GROUP BY candidate_id");
       
        $raised_insuff=DB::table('jaf_form_data as jf')
                        ->select('jf.*',DB::raw('group_concat(DISTINCT jf.service_id) as services'),DB::raw('group_concat(DISTINCT jf.id) as jaf_id'),'u.display_id','u.email','u.phone','u.phone_code','u.phone_iso')
                        ->join('users as u','jf.candidate_id','=','u.id')
                        ->join('verification_insufficiency as v','v.jaf_form_data_id','=','jf.id')
                        ->join('job_items as j','jf.job_item_id','=','j.id')
                        ->where(['u.is_deleted' =>'0','j.jaf_status'=>'filled','u.parent_id'=>$business_id])
                        ->whereIn('v.status',['raised','removed']);
                        if(is_numeric($request->get('customer_id'))){
                            $raised_insuff->where('u.business_id',$request->get('customer_id'));
                          }
                          if(is_numeric($request->get('candidate_id'))){
                            $raised_insuff->where('u.id',$request->get('candidate_id'));
                          }
                        // if($request->get('from_date') !=""){
                        //     $raised_insuff->whereDate('u.created_at','>=',date('Y-m-d',strtotime($request->get('from_date'))));
                        //   }
                        //   if($request->get('to_date') !=""){
                        //     $raised_insuff->whereDate('u.created_at','<=',date('Y-m-d',strtotime($request->get('to_date'))));
                        //   }
                        if($request->get('email')){
                            $raised_insuff->where('u.email',$request->get('email'));
                          }
                          if($request->get('mob')){
                            $raised_insuff->where('u.phone',$request->get('mob'));
                          }
                          if($request->get('ref')){
                            $raised_insuff->where('u.display_id',$request->get('ref'));
                          }
                          if($request->get('rows')!='') {
                            $rows = $request->get('rows');
                          }

         $raised_insuff = $raised_insuff->orderBy('jf.updated_at','desc')->groupBy('jf.candidate_id')->paginate($rows);
        // dd($raised_insuff);
        //Insuff Raised for KAM
        // $kam_raised_insuff= DB::select("SELECT jf.*,GROUP_CONCAT(DISTINCT jf.service_id)as services,GROUP_CONCAT(DISTINCT jf.id)as id FROM jaf_form_data as jf JOIN users as u ON u.id=jf.candidate_id JOIN verification_insufficiency as vi ON vi.jaf_form_data_id = jf.id JOIN key_account_managers as kam ON kam.business_id=vi.coc_id WHERE u.is_deleted ='0' AND vi.status='raised' GROUP BY jf.candidate_id ORDER BY jf.candidate_id DESC");

        $kam_raised_insuff=DB::table('jaf_form_data as jf')
                            ->select('jf.*',DB::raw('group_concat(DISTINCT jf.service_id) as services'),DB::raw('group_concat(DISTINCT jf.id) as jaf_id'),'u.display_id','u.email','u.phone')
                            ->join('users as u','u.id','=','jf.candidate_id')
                            ->join('verification_insufficiency as v','v.jaf_form_data_id','=','jf.id')
                            ->join('key_account_managers as kam','kam.business_id','=','jf.business_id')
                            ->join('job_items as j','jf.job_item_id','=','j.id')
                            ->where(['u.is_deleted' =>'0','kam.user_id'=>$user_id,'j.jaf_status'=>'filled','u.parent_id'=>$business_id])
                            ->whereIn('v.status',['raised','removed']);
                            
                            if(is_numeric($request->get('customer_id'))){
                                $kam_raised_insuff->where('u.business_id',$request->get('customer_id'));
                              }
                              if(is_numeric($request->get('candidate_id'))){
                                $kam_raised_insuff->where('u.id',$request->get('candidate_id'));
                              }
                            // if($request->get('from_date') !=""){
                            //     $kam_raised_insuff->whereDate('u.created_at','>=',date('Y-m-d',strtotime($request->get('from_date'))));
                            //   }
                            //   if($request->get('to_date') !=""){
                            //     $kam_raised_insuff->whereDate('u.created_at','<=',date('Y-m-d',strtotime($request->get('to_date'))));
                            //   }
                            if($request->get('email')){
                                $kam_raised_insuff->where('u.email',$request->get('email'));
                              }
                              if($request->get('mob')){
                                $kam_raised_insuff->where('u.phone',$request->get('mob'));
                              }
                              if($request->get('ref')){
                                $kam_raised_insuff->where('u.display_id',$request->get('ref'));
                              }
                              if($request->get('rows')!='') {
                                $rows = $request->get('rows');
                              }

            $kam_raised_insuff=$kam_raised_insuff->groupBy('jf.candidate_id')->orderBy('jf.updated_at','DESC')->paginate($rows);
                            
        $kams = DB::table('key_account_managers')->where(['user_id'=>$user_id])->get();

        $customers = DB::table('users as u')
                    ->select('u.id','u.display_id','u.name','u.first_name','u.last_name','u.email','u.phone','b.company_name')
                    ->join('user_businesses as b','b.business_id','=','u.id')
                    ->where(['u.user_type'=>'client','u.parent_id'=>$business_id])
                    ->get();
        //  dd($kams);
        if($request->ajax())
            return view('admin.insuff.ajax',compact('raised_insuff','kams','kam_raised_insuff','customers'));
        else
            return view('admin.insuff.index',compact('raised_insuff','kams','kam_raised_insuff','customers'));
    }

    /**
     * set the session data
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function setSessionData(Request $request)
    {   
        //clear session data 
        Session()->forget('customer_id');
        Session()->forget('candidate_id');
        Session()->forget('to_date');
        Session()->forget('from_date');
        Session()->forget('check_id');

        Session()->forget('type');

        Session()->forget('export_service_id');
        Session()->forget('export_candidate_id');

        // Session()->forget('jaf_id');
        // Session()->forget('service_id');

        if( is_numeric($request->get('customer_id')) ){             
            session()->put('customer_id', $request->get('customer_id'));
        }
        if( is_numeric($request->get('candidate_id')) ){             
          session()->put('candidate_id', $request->get('candidate_id'));
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
        if($request->get('check_id') !=""){
          session()->put('check_id', $request->get('check_id'));
        }
        if($request->get('type') !=""){
            session()->put('type', $request->get('type'));
        }

        // if($request->get('jaf_id')!="")
        // {
        //   session()->put('jaf_id', $request->get('jaf_id'));
        // }

        // if($request->get('service_id')!="")
        // {
        //   session()->put('service_id', $request->get('service_id'));
        // }
        
        if($request->get('export_service_id'))
        {
            session()->put('export_service_id', $request->get('export_service_id'));
        }

        if($request->get('export_candidate_id'))
        {
            session()->put('export_candidate_id', $request->get('export_candidate_id'));
        }

        echo '1';
    }

    public function insuff_detail(Request $request)
    {
        $candidate_id=base64_decode($request->candidate_id);
        $service_id=base64_decode($request->service_id);
        $jaf_id=base64_decode($request->jaf_id);
        $service_name=$request->service_name;

        $user_id = Auth::user()->id;
        $business_id = Auth::user()->business_id;
        $report_id ="";
        $jaf_item = [];

        $form='';
        $service_name = '';
        $type=$request->type;
        if($type=='raised')
        {
            
            $ver_insuff=DB::table('verification_insufficiency as vi')
                            ->select('vi.*','s.name as service_name','s.verification_type','u.name as candidate_name','u.display_id')
                            ->join('users as u','u.id','=','vi.candidate_id')
                            ->join('services as s','s.id','=','vi.service_id')
                            ->where(['vi.jaf_form_data_id'=>$jaf_id,'vi.service_id'=>$service_id,'vi.status'=>'raised'])
                            ->orderBy('vi.id','desc')
                            ->first();
            if($ver_insuff->notes==NULL)
                $comments='N/A';
            else
                $comments=$ver_insuff->notes;

            if(stripos($ver_insuff->verification_type,'Manual')!==false)
            {
                $service_name = $ver_insuff->service_name.' - '.$ver_insuff->item_number;
            }
            else
            {
                $service_name = $ver_insuff->service_name;
            }
            $form.='<div class="form-group">
                        <label><strong>Candidate Name:</strong> '.$ver_insuff->candidate_name.' ('.$ver_insuff->display_id.')</label>
                    </div>';

            // $form.='<div class="form-group">
            //             <label for="label_name"> <strong>Comments:</strong> <span id="comments">'.$comments.'</span></label>
            //         </div>';
            $insuff_attach=DB::table('insufficiency_attachments')->where(['jaf_form_data_id'=>$jaf_id,'service_id'=>$service_id,'status'=>'raise'])->get();
            if(count($insuff_attach)>0)
            {
                $s3_config = S3ConfigTrait::s3Config();
                $path=url('/').'/uploads/raise-insuff/';
                $form.='<div class="form-group">
                        <label><strong>Attachments: </strong></label>
                        <div class="row">';
                foreach($insuff_attach as $insuff)
                {
                    $img='';
                    if(stripos($insuff->file_platform,'s3')!==false)
                    {
                        $filePath = 'uploads/raise-insuff/';

                        $disk = Storage::disk('s3');

                        $command = $disk->getDriver()->getAdapter()->getClient()->getCommand('GetObject', [
                            'Bucket'                     => Config::get('filesystems.disks.s3.bucket'),
                            'Key'                        => $filePath.$insuff->file_name,
                            'ResponseContentDisposition' => 'attachment;'//for download
                        ]);

                        $req = $disk->getDriver()->getAdapter()->getClient()->createPresignedRequest($command, '+10 minutes');

                        $file = (string)$req->getUri();
                    }
                    else
                    {
                        $file=$path.$insuff->file_name;
                    }

                    if(stripos($insuff->file_name, 'pdf')!==false) {
                        $img='<img src="'.url("/")."/admin/images/icon_pdf.png".'" title="'.$insuff->file_name.'" alt="preview" style="height:100px;"/>';
                    }
                    else
                    {
                        $img='<img src="'.$file.'" title="'.$insuff->file_name.'" alt="preview" style="height:100px;"/>';
                    }
                    $form.='<div class="col-4">
                            <div class="image-area" style="width:110px;">
                                <a href="'.$file.'" download>
                                    '.$img.'
                                </a>
                            </div>
                            </div>';
                } 
                $form.='</div>
                        </div>';
            }

            $insuff_by = 'N/A';
            if($ver_insuff->updated_by!=NULL)
            {
                $user=DB::table('users')->where(['id'=>$ver_insuff->updated_by])->first();
                if($user!=NULL)
                    $insuff_by= $user!=NULL ?  $user->name : '--';
            }
            else
            {
                $user=DB::table('users')->where(['id'=>$ver_insuff->created_by])->first();

                if($user!=NULL)
                    $insuff_by= $user!=NULL ?  $user->name : '--';
            }

            if($ver_insuff->updated_at!=NULL)
            {
                $insuff_date=date('d-m-Y h:i A',strtotime($ver_insuff->updated_at));
            }
            else
            {
                $insuff_date=date('d-m-Y h:i A',strtotime($ver_insuff->created_at));
            }

            // $form.='<div class="form-group">
            //             <div class="row">
            //                 <div class="col-6">
            //                     <label><strong>Raised By : </strong><span>'.$insuff_by.' </span</label>
            //                 </div>
            //                 <div class="col-6">
            //                     <label><strong>Raised Date & Time : </strong><span>'.$insuff_date.' </span</label>
            //                 </div>
            //             </div>
            //         </div>';

            $jaf_form_data=DB::table('jaf_form_data')->where(['id'=>$jaf_id,'service_id'=>$service_id,'is_insufficiency'=>1])->first();
            if($jaf_form_data!=NULL)
            {
                // if($jaf_form_data->verification_status==NULL || $jaf_form_data->verification_status=='failed')
                // {
                    $report_status = NULL;
                    $report_data = DB::table('reports')
                                ->select('status','id')            
                                ->where(['candidate_id'=>$candidate_id])
                                ->first();
                    if($report_data !=null){
                        $report_status['status'] =  $report_data->status;
                        $report_status['id'] =  $report_data->id;
                    }

                    if ($report_status==NULL || $report_status['status']=='incomplete' || $report_status['status']=='interim')
                    {
                        $form.='<div class="form-group">
                                    <a href="javascript:;" class="btn btn-warning itemMarkAsCleared" jaf-id="'. base64_encode($jaf_id).'" candidate-id="'.base64_encode($candidate_id) .'" service-id="'.base64_encode($service_id) .'" service-name="'.$service_name.'" style="display:none"> Mark as Insuff cleared </a>
                                </div>';
                    }           
                // }
            }
            
            $insuff_logs = DB::table('insufficiency_logs as i')
                            ->select('i.*','s.name as service_name','s.verification_type')
                            ->join('services as s','s.id','=','i.service_id')
                            ->where(['i.jaf_form_data_id'=>$jaf_id])
                            ->whereIn('i.status',['raised','failed'])
                            ->orderBy('i.id','desc')
                            ->get();

            if(count($insuff_logs)>0)
            {
                // $form.='<h5 class="pt-2">Insufficieny Log Details </h5>
                //         <p class="pb-border"></p>';
                // $jaf_form_data=DB::table('jaf_form_data')->where(['id'=>$jaf_id,'service_id'=>$service_id,'is_insufficiency'=>1])->first();
                // if($jaf_form_data!=NULL)
                // {
                // $form.='<div class="form-group">
                //     <a href="javascript:;" class="btn btn-warning itemMarkAsCleared" jaf-id="'. base64_encode($jaf_id).'" candidate-id="'.base64_encode($candidate_id) .'" service-id="'.base64_encode($service_id) .'" service-name="'.$service_name.'"> Mark as Insuff cleared </a>
                //     </div>';
                    
                // }

                $count = count($insuff_logs);

                $form.='<div class="insuff-data">';
                foreach($insuff_logs as $key => $insuff)
                {
                    $insuff_comment = 'N/A';
                    $form.='<div class="row">';

                    if($insuff->notes!=NULL)
                    {
                        $insuff_comment=$insuff->notes;
                    }

                    $insuff_by = 'N/A';

                    $user=DB::table('users')->where(['id'=>$insuff->created_by])->first();

                    if($user!=NULL)
                        $insuff_by= $user->name;

                    $form.='<div class="col-12">
                                <div class="form-group">
                                    <label for="label_name"> <strong>Comments:</strong> <span id="comments">'.$insuff_comment.'</span></label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label><strong>Raised By : </strong><span>'.$insuff_by.' </span</label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label><strong>Raised Date & Time : </strong><span>'.date('d-m-Y h:i A',strtotime($insuff->created_at)).' </span</label>
                                </div>
                            </div>';

                    $form.='</div>';

                    if($count!=++$key)
                    {
                        $form.='<p class="pb-border"></p>';
                    }
                }

                $form.='</div>';

            }

            $candidate = DB::table('users as u')
                            ->select('u.id','u.business_id','u.client_emp_code','u.entity_code','u.display_id','u.first_name','u.middle_name','u.last_name','u.name','u.email','u.phone','u.phone_code','u.phone_iso','u.dob','u.aadhar_number','u.father_name','u.gender','j.created_at','j.job_id','j.sla_id','j.is_all_insuff_cleared','j.insuff_cleared_by','j.jaf_status','u.digital_signature','j.is_jaf_ready_report','u.digital_signature_file_platform')  
                            ->leftjoin('job_items as j','j.candidate_id','=','u.id')
                            ->where(['u.id'=>$candidate_id]) 
                            ->first();

            $jaf_item = DB::table('jaf_form_data as jf')
                            ->select('jf.id','jf.form_data_all','jf.form_data','jf.check_item_number','jf.address_type','jf.insufficiency_notes','jf.is_insufficiency','jf.insuff_attachment','jf.is_api_checked','jf.verification_status','jf.verified_at','jf.is_data_verified','s.name as service_name','s.id as service_id','s.verification_type','jf.candidate_id','jf.is_supplementary','s.type_name')
                            ->join('services as s','s.id','=','jf.service_id')
                            ->where(['jf.id'=>$jaf_id])
                            ->orderBy('s.sort_number','asc')
                            ->orderBy('jf.check_item_number','asc')
                            ->first();

            if($jaf_item){
                $is_insuff_arr=$jaf_item->is_insufficiency;
            }
    
            $report = DB::table('reports')->where(['candidate_id'=>$candidate_id,'status'=>'completed'])->first();
            // dd($report);
            if ($report==NULL) {
                $report= '';
                $report_id='';
                $report_status='';
                $report_items=[];
                $status_list=[];
            }
            
            $job = DB::table('job_items')->where(['candidate_id'=>$candidate_id])->first();

            if($job->jaf_status=='filled')
            {
                //check report items created or not
                $report_count = DB::table('reports')->where(['candidate_id'=>$candidate_id])->count(); 
                if($report_count == 0){ 
                
                    $data = 
                        [
                            'parent_id'     =>$business_id,
                            'business_id'   =>$job->business_id,
                            'candidate_id'  =>$candidate_id,
                            'sla_id'        =>$job->sla_id,
                            'created_at'    =>date('Y-m-d H:i:s')
                        ];
                    
                    $report_id = DB::table('reports')->insertGetId($data);
                    
                    // add service items
                    $item = DB::table('jaf_form_data')->where(['candidate_id'=>$candidate_id,'service_id'=>$verify_service_id,'check_item_number'=>$verify_number_id])->first(); 
                    if($item){
                        if ($item->verification_status == 'success') {
                            $data = 
                            [
                            'report_id'     =>$report_id,
                            'service_id'    =>$item->service_id,
                            'service_item_number'=>$item->check_item_number,
                            'candidate_id'  =>$candidate_id,      
                            'jaf_data'      =>$item->form_data,
                            'jaf_id'        =>$item->id,
                            'created_at'    =>date('Y-m-d H:i:s')
                            ];
                        } else {
                            $data = 
                            [
                            'report_id'     =>$report_id,
                            'service_id'    =>$item->service_id,
                            'service_item_number'=>$item->check_item_number,
                            'candidate_id'  =>$candidate_id,      
                            'jaf_data'      =>$item->form_data,
                            'jaf_id'        =>$item->id,
                            'is_report_output' => '0',
                            'created_at'    =>date('Y-m-d H:i:s')
                            ]; 
                        }
                        
                        $report_item_id = DB::table('report_items')->insertGetId($data);
                    }
                }
            }
            
            $reports = DB::table('reports')->where(['candidate_id'=>$candidate_id])->first(); 

            if($reports)
            {
                $report_id = $reports->id;
                $report_status = $reports->status;
            
                

                // $candidate = [];
                $report_items = [];
                // $candidate =    Db::table('users as u')
                //                   ->select('u.id','u.business_id','u.first_name','u.last_name','u.name','u.email','u.phone','r.created_at')  
                //                   ->leftjoin('reports as r','r.candidate_id','=','u.id')
                //                   ->where(['u.id'=>$candidate_id]) 
                //                   ->first(); 
                
                $report_items = DB::table('report_items as ri')
                                ->select('ri.*','s.name as service_name','s.id as service_id' )  
                                ->join('services as s','s.id','=','ri.service_id')
                                ->where(['ri.report_id'=>$report_id]) 
                                ->orderBy('s.sort_number','asc')
                                ->get(); 

                $status_list = DB::table('report_status_masters')->where(['status'=>1])->get();
    
            }

              $user_service_check=DB::table('jaf_form_data as jf')
                            ->join('user_checks as u','u.checks','=','jf.service_id')
                            ->where(['jf.candidate_id'=>$candidate_id,'u.user_id'=>$user_id])
                            ->get();

              // dd($user_service_check);
              $services = DB::table('services')
              ->select('name','id')
              ->where(['status'=>'1'])
              ->whereNull('business_id')
              ->whereNotIn('type_name',['gstin'])
              ->orwhere('business_id',$business_id)
              ->get();

            $viewRender = view('admin.candidates.insuff-jaf-info',compact('candidate','jaf_item','is_insuff_arr','report','status_list','report_items','report_id','services','user_service_check'))->render();
            return response()->json(
                [
                    'form'=> $form,
                    'service_name' => $service_name,
                    'html' => $viewRender
                ]);

        }
        else if($type=='removed')
        {
            $ver_insuff=DB::table('verification_insufficiency as vi')
                        ->select('vi.*','s.name as service_name','s.verification_type','u.name as candidate_name','u.display_id')
                        ->join('users as u','u.id','=','vi.candidate_id')
                        ->join('services as s','s.id','=','vi.service_id')
                        ->where(['vi.jaf_form_data_id'=>$jaf_id,'vi.service_id'=>$service_id,'vi.status'=>'removed'])
                        ->orderBy('vi.id','desc')
                        ->first();
                
            if($ver_insuff->notes==NULL)
                $comments='N/A';
            else
                $comments=$ver_insuff->notes;

            $form.='<div class="form-group">
                        <label><strong>Candidate Name:</strong> '.$ver_insuff->candidate_name.' ('.$ver_insuff->display_id.')</label>
                    </div>';

            $form.='<div class="form-group">
                        <label for="label_name"> <strong>Comments: </strong><span id="comments">'.$comments.'</span></label>
                    </div>';
            $insuff_attach=DB::table('insufficiency_attachments')->where(['jaf_form_data_id'=>$jaf_id,'service_id'=>$service_id,'status'=>'removed'])->get();
            if(count($insuff_attach)>0)
            {
                $s3_config = S3ConfigTrait::s3Config();
                $path=url('/').'/uploads/clear-insuff/';
                $form.='<div class="form-group">
                        <label><strong>Attachments: </strong></label>
                        <div class="row">';
                foreach($insuff_attach as $insuff)
                {
                    $img='';
                    if(stripos($insuff->file_platform,'s3')!==false)
                    {
                        $filePath = 'uploads/clear-insuff/';

                        $disk = Storage::disk('s3');

                        $command = $disk->getDriver()->getAdapter()->getClient()->getCommand('GetObject', [
                            'Bucket'                     => Config::get('filesystems.disks.s3.bucket'),
                            'Key'                        => $filePath.$insuff->file_name,
                            'ResponseContentDisposition' => 'attachment;'//for download
                        ]);

                        $req = $disk->getDriver()->getAdapter()->getClient()->createPresignedRequest($command, '+10 minutes');

                        $file = (string)$req->getUri();
                    }
                    else
                    {
                        $file=$path.$insuff->file_name;
                    }
                    
                    if(stripos($insuff->file_name, 'pdf')!==false) {
                        $img='<img src="'.url("/")."/admin/images/icon_pdf.png".'" title="'.$insuff->file_name.'" alt="preview" style="height:100px;"/>';
                    }
                    else
                    {
                        $img='<img src="'.$file.'" alt="preview" title="'.$insuff->file_name.'" style="height:100px;"/>';
                    }
                    $form.='<div class="col-4">
                            <div class="image-area" style="width:110px;">
                                <a href="'.$file.'" download>
                                    '.$img.'
                                </a>
                            </div>
                            </div>';
                }
                $form.='</div>
                        </div>';
            }

            if($ver_insuff->updated_by!=NULL)
            {
                $user=DB::table('users')->where(['id'=>$ver_insuff->updated_by])->first();
                $insuff_by= $user!=NULL ?  $user->name : '--';
            }
            else
            {
                $user=DB::table('users')->where(['id'=>$ver_insuff->created_by])->first();
                $insuff_by= $user!=NULL ?  $user->name : '--';
            }

            if($ver_insuff->updated_at!=NULL)
            {
                $insuff_date=date('d-m-Y h:i A',strtotime($ver_insuff->updated_at));
            }
            else
            {
                $insuff_date=date('d-m-Y h:i A',strtotime($ver_insuff->created_at));
            }
            
            $form.='<div class="form-group">
                        <div class="row">
                            <div class="col-6">
                                <label><strong> Cleared By : </strong><span>'.$insuff_by.' </span</label>
                            </div>
                            <div class="col-6">
                                <label><strong> Cleared Date & Time : </strong><span>'.$insuff_date.' </span</label>
                            </div>
                        </div>
                    </div>';


            if(stripos($ver_insuff->verification_type,'Manual')!==false)
            {
                $service_name = $ver_insuff->service_name.' - '.$ver_insuff->item_number;
            }
            else
            {
                $service_name = $ver_insuff->service_name;
            }

            $insuff_logs = DB::table('insufficiency_logs as i')
                            ->select('i.*','s.name as service_name','s.verification_type')
                            ->join('services as s','s.id','=','i.service_id')
                            ->where(['i.jaf_form_data_id'=>$jaf_id])
                            ->whereIn('i.status',['removed'])
                            ->orderBy('i.id','desc')
                            ->get();

            if(count($insuff_logs)>0)
            {
                $form.='<h5 class="pt-2">Insufficieny Log Details</h5>
                        <p class="pb-border"></p>';

                $count = count($insuff_logs);

                $form.='<div class="insuff-data">';
                foreach($insuff_logs as $key => $insuff)
                {
                    $insuff_comment = 'N/A';
                    $form.='<div class="row">';

                    if($insuff->notes!=NULL)
                    {
                        $insuff_comment=$insuff->notes;
                    }

                    $insuff_by = 'N/A';

                    $user=DB::table('users')->where(['id'=>$insuff->created_by])->first();

                    if($user!=NULL)
                        $insuff_by= $user->name;

                    $form.='<div class="col-12">
                                <div class="form-group">
                                    <label for="label_name"> <strong>Comments:</strong> <span id="comments">'.$insuff_comment.'</span></label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label><strong>Cleared By : </strong><span>'.$insuff_by.' </span</label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label><strong>Cleared Date & Time : </strong><span>'.date('d-m-Y h:i A',strtotime($insuff->created_at)).' </span</label>
                                </div>
                            </div>';

                    $form.='</div>';

                    if($count!=++$key)
                    {
                        $form.='<p class="pb-border"></p>';
                    }
                }

                $form.='</div>';

            }

            return response()->json(
                [
                    'form'=> $form,
                    'service_name' => $service_name
                ]);
        }

    }

    /**
     * Export Excel of candidate's JAF data.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function export(Request $request) 
    {
      $from_date = $to_date= $customer_id=$business_id = $check_id = $type = "";
      $business_id = Auth::user()->business_id;

      $candidate_id=[];
        
        // dd($request->session()->get('export_candidate_id'));

        if( $request->session()->has('from_date') && $request->session()->has('to_date'))
        {  
          $from_date     =  $request->session()->get('from_date');
          $to_date       =  $request->session()->get('to_date');
        }
        else
        {
          if($request->session()->has('from_date'))
          {
            $from_date      =  $request->session()->get('from_date');
          }
        }
        //
        if($request->session()->has('customer_id'))
        {  
          $customer_id      =  $request->session()->get('customer_id');
        }
        
        if($request->session()->has('export_candidate_id'))
        {  
            $candidate_id   =  $request->session()->get('export_candidate_id');
            rsort($candidate_id);
        }

        if($request->session()->has('type'))
        {  
          $type  =  $request->session()->get('type');
        }

       
          $i=0;
        
          $verification_insufficiency=DB::table('verification_insufficiency as vs')
                            ->select('vs.*')
                            ->join('jaf_form_data as jf','jf.id','=','vs.jaf_form_data_id')
                            ->whereIn('vs.candidate_id',$candidate_id)
                            ->where(['vs.status'=>'raised','jf.is_insufficiency'=>1])
                            ->get();
        

        if(count($verification_insufficiency)>0)
        {
          if(stripos($type,'csv')!==false)
            return Excel::download(new InsuffdataExport($from_date, $to_date, $customer_id,$candidate_id, $business_id,$type), 'candidates-insuff-data.csv');
          else
            return Excel::download(new InsuffdataExport($from_date, $to_date, $customer_id,$candidate_id, $business_id,$type), 'candidates-insuff-data.xlsx');
        }
        else
        {
           return 'No Data Found';
        }
        
        
    }

}
