<?php

namespace App\Http\Controllers\Client;

use App\Events\ApiCheck;
use App\Http\Controllers\Controller;
use App\Models\Admin\UserCheck;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use PHPUnit\Framework\Constraint\Count;
use App\Models\Admin\CandidateHoldStatus;
use App\Models\Admin\KeyAccountManager;
use App\Models\Admin\ImportCandidate;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
// use Image;
use Illuminate\Support\Facades\File;
use Imagick;
use App\Traits\S3ConfigTrait;
use Illuminate\Support\Facades\Storage;
use App\Helpers\Helper;
use Illuminate\Support\Facades\Config;
use App\Models\Admin\JafFormData;

use App\Models\Admin\AadharCheck;
use App\Models\Admin\PanCheck;
use App\Models\Admin\RcCheck;
use App\Models\Admin\VoterIdCheck;
use App\Models\DlCheck;
use App\Models\PassportCheck;
use App\Models\BankAccountCheck;
use App\Models\GstCheck;
use App\Models\TelecomCheck;
use App\Covid19Check;
use App\Models\UpiCheck;
use App\Models\CinCheck;
use App\Models\ECourtCheck;
use App\Models\ECourtCheckItem;
use Maatwebsite\Excel\Facades\Excel;
use App\CocNotificationMaster;
use App\Models\Admin\OtpByEmail;
use App\Models\CandidateAccess;

class CandidateController extends Controller
{
  
  //candidates
    public function index(Request $request)
    {
      // dd(unserialize(urldecode($request->get('active_case'))));
      $user_type=Auth::user()->user_type;
      $business_id = Auth::user()->business_id;
      $user_id = Auth::user()->id;
      if ($user_type=='client') {
        $query = DB::table('users as u')
        ->DISTINCT('u.id')
        ->select('u.*','j.sla_id','jsi.jaf_send_to','j.jaf_status','j.job_id','j.candidate_id','jsi.jaf_send_to','j.id as job_item_id')      
        ->join('job_items as j','j.candidate_id','=','u.id') 
        ->join('job_sla_items as jsi','jsi.candidate_id','=','j.candidate_id' )             
        ->where(['u.user_type'=>'candidate','u.business_id'=>$business_id,'is_deleted'=>'0']);
      } else {
        $query = DB::table('users as u')
        ->DISTINCT('u.id')
        ->select('u.*','j.sla_id','jsi.jaf_send_to','j.jaf_status','j.job_id','j.candidate_id','jsi.jaf_send_to','j.id as job_item_id')      
        ->join('job_items as j','j.candidate_id','=','u.id') 
        ->join('job_sla_items as jsi','jsi.candidate_id','=','j.candidate_id' )
        ->join('candidate_accesses as ca','ca.candidate_id','=','u.id')             
        ->where(['u.user_type'=>'candidate','u.business_id'=>$business_id,'is_deleted'=>'0'])
        ->where('ca.access_id',$user_id);
                        
      }
      
     
      if(is_numeric($request->get('customer_id'))){
        $query->where('u.business_id',$request->get('customer_id'));
      }
      if(is_numeric($request->get('candidate_id'))){
        $query->where('u.id',$request->get('candidate_id'));
      }
      if($request->get('email')){
        $query->where('u.email',$request->get('email'));
      }
      if($request->get('mob')){
        $query->where('u.phone',$request->get('mob'));
      }
      if($request->get('ref')){
        $query->where('u.display_id',$request->get('ref'));
      }
      if($request->get('from_date') !=""){
        $query->whereDate('u.created_at','>=',date('Y-m-d',strtotime($request->get('from_date'))));
      }
      if($request->get('to_date') !=""){
        $query->whereDate('u.created_at','<=',date('Y-m-d',strtotime($request->get('to_date'))));
      }
      if($request->get('type')){
        $query->where('u.priority',$request->get('type'));
      }
      if ($request->get('active_case')) {
        // echo $request->get('active_case');
        $query->where('j.jaf_status',$request->get('active_case'));
        // dd($query);
        // $query->whereIn('j.jaf_status',$data);
      }
      if($request->get('active_case1') || $request->get('active_case2'))
      {
        $value=$request->get('active_case1').','.$request->get('active_case2');
        $query->whereIn('j.jaf_status',explode(',',$value));
      }
      if ($request->get('sendto')&&$request->get('jafstatus')) {
        
        $query->where(['jsi.jaf_send_to'=>$request->get('sendto'),'j.jaf_status'=>$request->get('jafstatus')]);
      }
      // if ($request->get('insuff_raised')&&$request->get('insuff_status')) {
        
      //   $query->where(['jsi.jaf_send_to'=>$request->get('insuff_raised'),'j.jaf_status'=>$request->get('insuff_status')]);
      // }
      // if ($request->get('remain')&&$request->get('status')) {
        
      //   $query->where(['jsi.jaf_send_to'=>$request->get('remain'),'j.jaf_status'=>$request->get('status')]);
      // }
      if ($request->get('remain')) {
        
        $query->where(['jsi.jaf_send_to'=>$request->get('remain')]);
      }
      if ($request->get('insuff') ) {
        // echo($request->get('insuff'));
        $query->join('jaf_form_data as jfd','jfd.candidate_id','=','u.id')->where('jfd.is_insufficiency',$request->get('insuff'));

      }
      if ($request->get('insuffs') && $request->get('service')) {
       
        $query->join('jaf_form_data as jd','jd.candidate_id','=','u.id')->where(['jd.is_insufficiency'=>$request->get('insuffs'),'jd.service_id'=>$request->get('service')]);
        // dd($query);
      }
      if ($request->get('insuff_raised')&&$request->get('insuff_status')) {
       
        $query->join('jaf_form_data as jd','jd.candidate_id','=','u.id')->where(['jd.is_insufficiency'=>$request->get('insuff_status'),'jd.service_id'=>$request->get('insuff_raised')]);
        // dd($query);
      }
      if ($request->get('verification_status')&& $request->get('service')) {
      //  echo($request->get('verification_status'));
      //  if$request->get('verification_status')=='0' ? null:'success';
        $query->join('jaf_form_data as jf','jf.candidate_id','=','u.id')->where(['jf.verification_status'=>null,'jf.service_id'=>$request->get('service')]);
        
      }
      if ($request->get('verify_status')&& $request->get('service')) {
        //  echo($request->get('verification_status'));
      //  if$request->get('verification_status')=='0' ? null:'success';
          $query->join('jaf_form_data as jf','jf.candidate_id','=','u.id')->where(['jf.verification_status'=>$request->get('verify_status'),'jf.service_id'=>$request->get('service')]);
          
        }
        if ($request->get('search')) {
          // $searchQuery = '%' . $request->search . '%';
        // echo($request->input('search'));
          $query->where('u.first_name',$request->get('search'))->orWhere('u.name',$request->get('search'))->orWhere('u.email',$request->get('search'))->orWhere('u.display_id',$request->get('search'))->orWhere('u.phone',$request->get('search'))->orWhere('u.client_emp_code',$request->get('search'));
        }

        if($request->get('case_wip')!='' && $request->get('case_wip')=='1')
        {
           $query->where('u.is_report_generate',0);
        }

        if ($request->get('sendto') && ($request->get('jafstatus1') || $request->get('jafstatus2'))) {

          $value=$request->get('jafstatus1').','.$request->get('jafstatus2');

          $query->where(['jsi.jaf_send_to'=>$request->get('sendto')])
                  ->whereIn('j.jaf_status',explode(',',$value));
        }

        if($request->get('sendto') && $request->get('jafstatus'))
        {
          $value=$request->get('jafstatus');

          $query->where(['jsi.jaf_send_to'=>$request->get('sendto')])
                  ->whereIn('j.jaf_status',explode(',',$value));
        }



      $query->orderBy('u.created_at','desc');

      $items =    $query->paginate(15);
      //  dd($items);  
      $candidates = DB::table('users as u')
      ->DISTINCT('jsi.candidate_id')
      ->select('u.*','j.sla_id','jsi.jaf_send_to','j.jaf_status','j.job_id','j.candidate_id','jsi.jaf_send_to','j.id as job_item_id')      
      ->join('job_items as j','j.candidate_id','=','u.id') 
      ->join('job_sla_items as jsi','jsi.candidate_id','=','j.candidate_id' )             
      ->where(['u.user_type'=>'candidate','u.business_id'=>$business_id,'is_deleted'=>'0'])->get();
      

      $customers = DB::table('users as u')
      ->select('u.id','u.display_id','u.name','u.first_name','u.last_name','u.email','u.phone','b.company_name')
      ->join('user_businesses as b','b.business_id','=','u.id')
      ->where(['u.user_type'=>'client','u.business_id'=>$business_id])
      ->get();

      $filled = $request->get('active_case');
      $pending=$request->get('active_case1');
      $draft=$request->get('active_case2');
      // dd($filled);
      $send_to = $request->get('sendto');
      $jafstatus =$request->get('jafstatus');
      $insuff = $request->get('insuff');
      $verification_status =$request->get('verification_status');
      $verify_status =$request->get('verify_status');

      $insuffs = $request->get('insuffs');
      $service = $request->get('service');
      $case_wip = $request->get('case_wip');
      $pending_jaf=$request->get('jafstatus1');
      $draft_jaf=$request->get('jafstatus2');

      $services = DB::table('services')
        ->select('name','id')
        ->where(['status'=>'1'])
        ->get();

        $array_result = [];

        foreach ($services as $key => $value) {
            
            
            $insuf = DB::table('jaf_form_data as jf')
            ->join('users as u','u.id','=','jf.candidate_id')
            ->where(['jf.service_id'=> $value->id,'jf.business_id'=>Auth::user()->business_id,'jf.is_insufficiency'=>'1'])
            ->count();

            $array_result[] = ['check_id'=>$value->id,'check_name'=> $value->name,'insuf'=>$insuf]; 
                // 
        }

      // dd($services);
      $tota_candidates = $query->count();

      if ($request->ajax())
        return view('clients.candidates.ajax', compact('items','customers','services','tota_candidates','filled','send_to','candidates','insuff','jafstatus','insuffs','service','array_result','verification_status','verify_status','pending','draft','case_wip','pending_jaf','draft_jaf'));
      else
        return view('clients.candidates.index', compact('items','customers','services','tota_candidates','filled','send_to','candidates','insuff','jafstatus','insuffs','service','array_result','verification_status','verify_status','pending','draft','case_wip','pending_jaf','draft_jaf'));
    } 

        
    /**
     * 
     * Get Auto Complete Data
     * 
     */
    public function autocomplete(Request $request)
    {
      $business_id = Auth::user()->business_id;
      $data_result = [];

      $data = DB::table('users as u')
      ->DISTINCT('u.id')
      ->select('u.*','j.sla_id','jsi.jaf_send_to','j.jaf_status','j.job_id','j.candidate_id','jsi.jaf_send_to','j.id as job_item_id')      
      ->join('job_items as j','j.candidate_id','=','u.id') 
      ->join('job_sla_items as jsi','jsi.candidate_id','=','j.candidate_id' )             
      ->where(['u.user_type'=>'candidate','u.business_id'=>$business_id,'is_deleted'=>'0']);

      if (!is_numeric($request->search)) {
      $data = $data->select('u.id','u.phone',"u.name")
      ->where("u.name","LIKE","%{$request->search}%")
      ->get();

        foreach($data as $item){
          $data_result[] = ['id'=>$item->id,'name'=>$item->name];
        }

      }

      if (is_numeric($request->search)) {
        $data = DB::table('users as u')->select('u.id','u.phone',"u.name","u.client_emp_code")
                    ->where("u.phone","LIKE","%{$request->search}%")
                    ->get();
  
        
          foreach($data as $item){
            $data_result[] = ['id'=>$item->id,'name'=>$item->phone];
          }

      }

      // if (is_numeric($request->search)) {
      //   $data = $data->select('u.id','u.phone',"u.name",'u.client_emp_code')
      //   ->where("u.client_emp_code","LIKE","%{$request->search}%")
      //   ->get();
  
      //     foreach($data as $item){
      //       $data_result[] = ['id'=>$item->id,'name'=>$item->client_emp_code];
      //     }
  
      //   }

      return response()->json($data_result);
    }

    /**
     * set the session data
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function setSessionData( Request $request)
    {   
        //clear session data 
        Session()->forget('customer_id');
        Session()->forget('candidate_id');
        Session()->forget('to_date');
        Session()->forget('from_date');
        Session()->forget('check_id');
        Session()->forget('active_case');
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
        if($request->get('active_case') !=""){
          session()->put('active_case', $request->get('active_case'));
        }

        echo "1";
    }

    /**
     * Resend Mail of candidate's BGV form.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function resendMail(Request $request)
    {
        $parent_id = Auth::user()->parent_id;
        $candidate_id = base64_decode($request->candidate_id);

        DB::beginTransaction();
        try{
          
          $candidate=DB::table('users')->where(['id'=>$candidate_id,'user_type'=>'candidate'])->first();
          
          if($candidate!=NULL)
          {
            $email = $candidate->email;
            $name  = $candidate->first_name;

            $job_items=DB::table('job_items')->where(['candidate_id'=>$candidate_id])->first();
            $data  = array('name'=>$name,'email'=>$email,'case_id'=>base64_encode($job_items->id),'c_id'=>base64_encode($candidate_id));

            if($email!="" || $email!=NULL){
              // Mail::send(['html'=>'mails.jaf-info'], $data, function($message) use($email,$name) {
              //   $message->to($email, $name)->subject
              //     ('BGV Link - Fill your job application form');
              //   $message->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
              // });

              $randomPassword = Str::random(10);
              $hashed_random_password = Hash::make($randomPassword);
              $candidate = DB::table('users')->select('email','first_name','name','business_id')->where(['id'=>$candidate_id])->first();
              $company = DB::table('user_businesses')->select('company_name')->where(['business_id'=>$candidate->business_id])->first();
              if ($candidate) {
      
                DB::table('users')->where(['id'=>$candidate_id])->update(['password'=>$hashed_random_password,'status'=>'1']);
              }

              $email = $candidate->email;
              $name  = $candidate->first_name;
              $company_name=$company->company_name;
              $id = $candidate->business_id;

              // $data  = array('name'=>$name,'email'=>$email,'password'=>$randomPassword);

              // Mail::send(['html'=>'mails.candidate-account-info'], $data, function($message) use($email,$name) {
              //     $message->to($email, $name)->subject
              //         ('Clobminds Pvt Ltd- Your account credential');
              //     $message->from(env('MAIL_USERNAME'),'Clobminds Pvt Ltd');
              // });

              $data  = array('name'=>$name,'email'=>$email,'password'=>$randomPassword,'company_name'=>$company_name,'id'=>$id);

              Mail::send(['html'=>'mails.jaf_info_credential-candidate'], $data, function($message) use($email,$name) {
                  $message->to($email, $name)->subject
                      ('Clobminds Pvt Ltd - Your account credential');
                  $message->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
              });

              // Send Email to Customer for BGV Link to Candidate
              $notification_controls = DB::table('notification_control_configs as nc')
                                        ->select('nc.*')
                                        ->join('notification_controls as n','n.business_id','=','nc.business_id')
                                        ->where(['n.status'=>1,'nc.status'=>1,'n.business_id'=>$candidate->business_id,'n.type'=>'jaf-sent-to-candidate','nc.type'=>'jaf-sent-to-candidate'])
                                        ->get();


              if(count($notification_controls)>0)
              {
                foreach($notification_controls as $item)
                {

                  //$client = User::where('id',$id)->first();

                  $email = $item->email;
                  $name = $item->name;
                  $company_name=$company->company_name;
                  $sender = User::where('id',$parent_id)->first();

                  $msg = 'Notification for Job Application Form Verifications to Candidate ('.$candidate->name.' - '.$candidate->display_id.') Has Been Resent at '.date('d-M-y h:i A').'';
                  $data  = array('name'=>$name,'email'=>$email,'company_name'=>$company_name,'sender'=>$sender,'msg'=>$msg);

                  Mail::send(['html'=>'mails.jaf-link'], $data, function($message) use($email,$name) {
                  $message->to($email, $name)->subject
                  ('Clobminds Pvt Ltd - Your account credential');
                  $message->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
                  });

                }

              }

              DB::commit();
              return response()->json([
                'status' =>'ok',
                'name' => $candidate->name,
                'message' => 'Mail Send Successfully !'
              ]);

            }
            else
            {
              return response()->json([
                'status' =>'no',
                'message' => 'Something Went Wrong!'
              ]);
            }
          }

          return response()->json([
            'status' =>'no',
            'message' => 'Something Went Wrong !'
          ]);
        }
        catch (\Exception $e) {
          DB::rollback();
          // something went wrong
          return $e;
        }     
    }

     //create options
     public function create_option()
     {
       $business_id = Auth::user()->business_id;
         
       $items = DB::table('users')
               ->select('*')              
               ->where(['user_type'=>'candidate','business_id'=>$business_id])
               ->get();
 
          return view('clients.candidates.create-option', compact('items'));
         
     }

    //create a candidate
    public function create()
    {
      $business_id = Auth::user()->business_id;
      $parent_id = Auth::user()->parent_id;
        
      $items = DB::table('users')
              ->select('*')              
              ->where(['user_type'=>'candidate','business_id'=>$business_id])
              ->get();

      $customers = DB::table('users as u')
              ->select('u.id','u.name','u.email','u.phone','b.company_name')
              ->join('user_businesses as b','b.business_id','=','u.id')
              ->where(['user_type'=>'client','u.business_id'=>$business_id])
              ->get();

      $slas = DB::table('customer_sla as sla')
              ->select('sla.*')
              ->where(['sla.business_id'=>$business_id])
              ->get();
        $services = DB::table('services as s')
                    ->select('s.*')
                    ->join('service_form_inputs as si','s.id','=','si.service_id')
                    ->where('business_id',NULL)
                    ->where('s.status',1)
                    ->whereNotIn('type_name',['gstin'])
                    ->orwhere('business_id',$parent_id)
                    ->groupBy('si.service_id')
                    ->get();
  
        $variable=DB::table('customer_sla')->select('id','title')->where('parent_id','0')->first();

        $client_users = DB::table('users')
                        ->select('*')              
                        ->where(['user_type'=>'user','business_id'=>$business_id,'is_deleted'=>'0'])
                        ->get();

         return view('clients.candidates.create', compact('items','customers','slas','business_id','parent_id','services','variable','client_users'));
        
    }

    //store 
    public function store(Request $request)
    {        
      // dd($request->all());                                 
      $business_id = Auth::user()->business_id;
      $parent_id=Auth::user()->parent_id;
      $dob = NULL;
      $sla_id=NULL;
      $user_type = Auth::user()->user_type;
      
      $rules= [
         
          // 'sla'         => 'required',
          'last_name'  => 'nullable|regex:/^[a-zA-Z]+$/u|min:1|max:255',
          'aadhar' => 'nullable|regex:/^((?!([0-1]))[0-9]{12})$/',
          'email'       => 'nullable|email:rfc,dns',
          'phone'       => 'required|regex:/^((?!(0))[0-9\s\-\+\(\)]{10,11})$/',
          // 'services'  => 'required|array|min:1',
          // 'password' => 'required|same:confirm-password',
          'jaf'       => 'required',
          'first_name' => 'required|regex:/^[a-zA-Z]+$/u|min:1|max:255',
          'middle_name' =>  'nullable|regex:/^[a-zA-Z ]+$/u|min:1|max:255',
          'father_name' => 'nullable|regex:/^[a-zA-Z ]+$/u|min:2|max:255',
          'dob'       => 'nullable|date',
          'gender'    => 'required',
          // 'sla_type'  => 'required|in:package,variable',
          // 'days_type'  => 'required_if:sla_type,variable|in:working,calender',
          // 'sla'         => 'required_if:sla_type,package',
          // 'services'    => 'required_if:sla_type,package,variable|array|min:1',
          'sla'         => 'required',
          'services'    => 'required|array|min:1',
       ];
      
       $customMessages = [
        // 'services.required' => 'Select at least one Check or Service item.',
        // 'days_type.required_if'       => 'The days type field is required',
        // 'sla.required_if' =>  'The sla is required',
        'sla' =>  'The sla is required',
        'aadhar.regex' => 'Please Enter a 12-digit valid Aadhar Number.',
        'phone.regex'=>'Phone Number must be Valid & 10-digit Number !!' ,
        // 'services.required_if' => 'Select at least one Check or Service item.',
        'services' => 'Select at least one Check or Service item.',
      ];

       $validator = Validator::make($request->all(), $rules,$customMessages);
        
        if($validator->fails()){
           return response()->json([
               'success' => false,
               'errors' => $validator->errors()
           ]);
        }
          // Validation for Client (Rekrut) & Reference Check
          $client_rekrut = DB::table('users as u')
                      ->distinct('cs.service_id')
                      ->select('u.*','cs.service_id')
                      ->where('u.id',$business_id)
                      ->join('customer_sla as c','c.business_id','=','u.id')
                      ->join('customer_sla_items as cs','cs.sla_id','=','c.id')
                      ->where(['u.user_type'=>'client'])
                      ->where('c.id',$request->input('sla'))
                      //->where('cs.service_id',17)
                      ->groupBy('cs.service_id')
                      ->get();
          
              if($business_id!=2155 && !(count($client_rekrut)==1 && $client_rekrut->contains('service_id',17)))
              {
                  $rules=[
                    'father_name' => 'required|regex:/^[a-zA-Z ]+$/u|min:2|max:255',
                    'dob'       => 'required|date',
                  ];
    
                  $validator = Validator::make($request->all(), $rules);
            
                  if($validator->fails()){
                      return response()->json([
                          'success' => false,
                          'errors' => $validator->errors()
                      ]);
                  }
              }
              else
              {
                  if(count($client_rekrut)==1 && $client_rekrut->contains('service_id',17))
                  {
                      $rules=[
                        'last_name'  => 'required|regex:/^[a-zA-Z]+$/u|min:1|max:255',
                      ];
    
                      $validator = Validator::make($request->all(), $rules);
                
                      if($validator->fails()){
                          return response()->json([
                              'success' => false,
                              'errors' => $validator->errors()
                          ]);
                      }
                  }
              }

          if($request->input("jaf")=='candidate')
          {
            $email_rules=[
              'email' => 'required|email:rfc,dns'
            ];

            $validator = Validator::make($request->all(), $email_rules);
              
            if ($validator->fails()){
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ]);
            }
          }

          $date_of_b = NULL;
          $year_diff = 0;
          $today_date = NULL;

          if($request->has('dob') && $request->input('dob')!=NULL){
            $dob = date('Y-m-d',strtotime($request->input('dob')));

            $date_of_b=Carbon::parse($dob)->format('Y-m-d');
            $today=Carbon::now();
            $year_diff=$today->diffInYears($date_of_b);
            $today_date=Carbon::now()->format('Y-m-d');
          }
          

     
          //validation for sla type
          // if($request->sla_type=='package')
          // {
          //   $rules=[
          //      'sla'         => 'required',
          //      'services'    => 'required|array|min:1',
          //   ];
          //   $customMessages=[
          //    'services.required' => 'Select at least one Check or Service item.',
          //   ];
          //    $validator = Validator::make($request->all(), $rules,$customMessages);
            
          //    if ($validator->fails()){
          //        return response()->json([
          //            'success' => false,
          //            'errors' => $validator->errors()
          //        ]);
          //    }  
          // }
          // if($request->sla_type=='variable')
          // {
          //   //  $rules=[
          //   //    'services'    => 'required|array|min:1',
          //   //  ];
          //   //  $customMessages=[
          //   //    'services.required' => 'Select at least one Check or Service item.',
          //   //  ];
          //   //    $validator = Validator::make($request->all(), $rules,$customMessages);
              
          //   //    if ($validator->fails()){
          //   //        return response()->json([
          //   //            'success' => false,
          //   //            'errors' => $validator->errors()
          //   //        ]);
          //   //    }

          //     //  dd($request->input('services'));

          //     if( count($request->input('services')) > 0 ){
          //       foreach($request->input('services') as $item){
          //         $rules=[
          //           'service_unit-'.$item    => 'required|integer|min:1',
          //           'tat-'.$item      => 'required|integer|min:1',
          //           'incentive-'.$item      => 'required|integer|lte:tat-'.$item
          //         ];
          //         $customMessages=[
          //           'service_unit-'.$item.'.required' => 'No of Verification is required',
          //           'service_unit-'.$item.'.integer' => 'No of Verification should be numeric',
          //           'service_unit-'.$item.'.min' => 'No of Verification should be atleast 1',
          //           //  'service_unit-'.$item.'.max' => 'No of Verification should be Maximum 5',
          //           'tat-'.$item.'.required' => 'No of TAT is required',
          //           'tat-'.$item.'.integer' => 'No of TAT should be numeric',
          //           'tat-'.$item.'.min' => 'No of TAT should be atleast 1',
          //           'incentive-'.$item.'.required' => 'No of incentive TAT is required',
          //           'incentive-'.$item.'.integer' => 'No of incentive TAT should be numeric',
          //           'incentive-'.$item.'.lte' => 'No of Incentive TAT should be less than or equal to Service TAT',
          //           'penalty-'.$item.'.required' => 'No of penalty TAT is required',
          //           'penalty-'.$item.'.integer' => 'No of penalty TAT should be numeric',
          //           'penalty-'.$item.'.gte' => 'No of penalty TAT should be greater than or equal to Service TAT',
          //         ];
          //           $validator = Validator::make($request->all(), $rules,$customMessages);
                    
          //           if ($validator->fails()){
          //               return response()->json([
          //                   'success' => false,
          //                   'errors' => $validator->errors()
          //               ]);
          //           }

          //       }
          //     }


          // }

          // dd($request->sla_type);

          $sla_id=$request->input('sla');

          $customer_sla=DB::table('customer_sla')->where('id',$sla_id)->first();

          // email  validation
          $is_user_exist = DB::table('users')
          ->where(['email'=>$request->input('email')])
          ->count();

          if($is_user_exist != 0 && $request->input('email') !=""){

              return response()->json([
                'success' => false,
                'custom'=>'yes',
                'errors' => ['email'=>'This email is already exists!']
              ]);
            
          }
            // Validation for Client (Rekrut) & Reference Check

          if($business_id!=2155 && !(count($client_rekrut)==1 && $client_rekrut->contains('service_id',17)))
          {
              // user validation
              $user_already_exist = DB::table('users')
              ->where(['first_name'=>$request->input('first_name'), 'dob'=>date('Y-m-d', strtotime($request->input('dob'))), 'father_name'=>$request->input('father_name') ])
              ->count();

              if($user_already_exist > 0){
                  return response()->json([
                    'success' => false,
                    'custom'=>'yes',
                    'errors' => ['user'=>'It Seems like the user is already exist!']
                  ]);
              }
          }

        $phone = preg_replace('/\D/', '', $request->input('phone'));

          if(strlen($phone)!=10)
          {
            return response()->json([
              'success' => false,
              'custom'=>'yes',
              'errors' => ['phone'=>'Phone Number must be 10-digit Number !!']
            ]);
          }

          if($dob!=NULL && ($year_diff<18 || ($date_of_b >= $today_date)))
          {
            return response()->json([
              'success' => false,
              'custom'  => 'yes',
              'errors' =>['dob'=>'Age Must be 18 or older !']
            ]);
          }
      
      // Email Validation

      if($request->input('email')!=null || $request->input('email')!='')
      {
          $email_user = DB::table('users')->where('email',$request->input('email'))->first();

          if($email_user!=NULL)
          {
            return response()->json([
              'success' => false,
              'custom'=>'yes',
              'errors' => ['email'=>'This Email Has Already Been Exists !!']
            ]);
          }
      }

      $is_send_jaf_link = 0;
      if($request->input('is_send_jaf_link')){
        $is_send_jaf_link = '1';
      }
      
      DB::beginTransaction();
      try{
      //create user 

      $name = trim(preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $request->input('first_name').' '.$request->input('middle_name').' '.$request->input('last_name')));
      $data = 
      [
          'business_id'   =>$business_id,
          'user_type'     =>'candidate',
          'client_emp_code'=>$request->input('client_emp_code'),
          'entity_code'   =>$request->input('entity_code'),
          'parent_id'     =>Auth::user()->parent_id,
          'name'          =>$name,
          'first_name'    => ucwords(strtolower($request->input('first_name'))),
          'middle_name'   => ucwords(strtolower($request->input('middle_name'))),
          'last_name'     => ucwords(strtolower($request->input('last_name'))),
          'father_name'   => ucwords(strtolower($request->input('father_name'))),
          'aadhar_number' =>$request->input('aadhar'),
          'dob'           =>$dob,
          'case_received_date' => date('Y-m-d H:i:s'),
          'gender'        =>$request->input('gender'),
          'email'         =>$request->input('email'),
          // 'password'       => Hash::make($request->input('password')),
          'phone'         =>$phone,
          'phone_code'    => $request->primary_phone_code,
          'phone_iso'     => $request->primary_phone_iso,    
          'created_by'    =>Auth::user()->id,
          'created_at'    =>date('Y-m-d H:i:s')
      ];
    
      $user_id = User::create($data);
      $user_id = $user_id->id;

          $display_id = "";
          //check customer config
        $candidate_config = DB::table('candidate_config')
        ->where(['client_id'=>$business_id,'business_id'=>$business_id])
        ->first(); 
        //check client 
        $client_config = DB::table('user_businesses')
        ->where(['business_id'=>$business_id])
        ->first();

        $latest_user = DB::table('users')
        ->select('display_id')
        ->where(['user_type'=>'candidate','business_id'=>$business_id])
        ->orderBy('id','desc')
        ->first();
        // dd($latest_user);
        $starting_number = $user_id;

        if($candidate_config !=null){
          if($latest_user != null){
            if($latest_user->display_id !=null){
              $id_arr = explode('-',$latest_user->display_id);
              $starting_number = $id_arr[2]+1;  
            }
          }
          $starting_number = str_pad($starting_number, 10, "0", STR_PAD_LEFT);
          $display_id = $candidate_config->customer_prefix.'-'.$candidate_config->client_prefix.'-'.$starting_number;
        }else{
          $customer_company = DB::table('user_businesses')
            ->select('company_name')
            ->where(['business_id'=>$parent_id])
            ->first();
            //  dd($customer_company);
            $client_company = DB::table('user_businesses')
            ->select('company_name')
            ->where(['business_id'=>$business_id])
            ->first();
            
            $u_id = str_pad($user_id, 10, "0", STR_PAD_LEFT);
            $display_id = substr($customer_company->company_name,0,4).'-'.substr($client_company->company_name,0,4).'-'.$u_id;
        }
        //
        DB::table('users')->where(['id'=>$user_id])->update(['display_id'=>$display_id]);

        if($user_type=='client')
        {
          if($request->input('customer_user')!=null){
                CandidateAccess::create([
                  'business_id' =>  $business_id,
                  'candidate_id' => $user_id,
                  'access_id' => $request->input('customer_user'),
                  'user_type' => 'user',
                  'created_at' => date('Y-m-d H:i:s')
                ]);
          }
            
        }
        else
        {
            CandidateAccess::create([
              'business_id' =>  $business_id,
              'candidate_id' => $user_id,
              'access_id' => Auth::user()->id,
              'user_type' => 'user',
              'created_at' => date('Y-m-d H:i:s')
            ]);
        }

        
          //
          $job_data = 
          [
            'business_id'  => $business_id,
            'parent_id'    => Auth::user()->parent_id,
            'title'        => NULL,
            'sla_id'       => $sla_id,
            'total_candidates'=>1,
            'send_jaf_link_required'=>$is_send_jaf_link,
            'status'       =>0,
            'created_by'   =>Auth::user()->id,
            'created_at'   => date('Y-m-d H:i:s') 
          ];
        
          $job_id = DB::table('jobs')->insertGetId($job_data);

          // job item items      
          $data = ['business_id' =>$business_id, 
          'job_id'       =>$job_id, 
          'candidate_id' =>$user_id,
          'sla_id'       =>$sla_id,
          // 'sla_type'     => $request->sla_type,
          'sla_type'     => 'package',
          // 'days_type'    => stripos($request->sla_type,'variable')!==false ? $request->days_type : $customer_sla->days_type,
          'days_type'    => $customer_sla->days_type,
          'tat_type'     => $customer_sla->tat_type,
          'incentive'     => $customer_sla->incentive,
          'penalty'     => $customer_sla->penalty,
          'package_price'   => $customer_sla->package_price,
          'sla_title' => $customer_sla->title,
          'jaf_status'   =>'pending',
          'created_at'   =>date('Y-m-d H:i:s')
          ];
          $job_item_id = DB::table('job_items')->insertGetId($data);  

          // service items
          // if($request->sla_type=='package')
          // {

            $cust_sla_items = DB::table('customer_sla_items')->where(['sla_id'=>$request->input('sla')])->get();
            // if( count($request->input('services')) > 0 ){
            //   foreach($request->input('services') as $item){
              
              if(count($cust_sla_items)>0)
              {
                 foreach($cust_sla_items as $item)
                 {
                        $service_d=DB::table('services')->where('id',$item->service_id)->first();
                        $number_of_verifications=1;
                        $no_of_tat=1;
                        $incentive_tat=1;
                        $penalty_tat=1;
                        $price = 0;
                        $sal_item_data = DB::table('customer_sla_items')->select('number_of_verifications','tat','incentive_tat','penalty_tat','price')->where(['sla_id'=>$request->input('sla'),'service_id'=>$item->service_id])->first(); 
                        if($sal_item_data !=null){
                          $number_of_verifications= $sal_item_data->number_of_verifications;
                          $no_of_tat= $sal_item_data->tat;
                          $incentive_tat=$sal_item_data->incentive_tat;
                          $penalty_tat=$sal_item_data->penalty_tat;
                          $price= $sal_item_data->price;
                        }
                          $data = ['business_id'=> $business_id, 
                                  'job_id'      => $job_id, 
                                  'job_item_id' => $job_item_id,
                                  'candidate_id' =>$user_id,
                                  'sla_id'      => $sla_id,
                                  'jaf_send_to' => $request->input('jaf'),
                                  // 'jaf_filled_by' => Auth::user()->id,
                                  'service_id'  => $item->service_id,
                                  'number_of_verifications'=>$service_d->verification_type=='Manual' || $service_d->verification_type=='manual'?$number_of_verifications:1,
                                  'tat'=>$no_of_tat,
                                  'incentive_tat'=>$incentive_tat,
                                  'penalty_tat'=>$penalty_tat,
                                  'price'   => $price,
                                  'sla_item_id' => $item->sla_id,
                                  'created_at'  => date('Y-m-d H:i:s') 
                                ];
                      $jsi =  DB::table('job_sla_items')->insertGetId($data);  
                 }
              }
               


            //   }
            // }
          // }
          // else if($request->sla_type=='variable')
          // {
          //   if( count($request->input('services')) > 0 ){
          //     foreach($request->input('services') as $item){
          //       $number_of_verifications=1;
          //       $no_of_tat=1;
          //       $incentive_tat=1;
          //       $penalty_tat=1;
          //       $number_of_verifications=$request->input('service_unit-'.$item);
          //       $no_of_tat = $request->input('tat-'.$item);
          //       $incentive_tat = $request->input('incentive-'.$item);
          //       $penalty_tat = $request->input('penalty-'.$item);

          //       $service_d=DB::table('services')->where('id',$item)->first();

          //       $data = ['business_id'=> $business_id, 
          //               'job_id'      => $job_id, 
          //               'job_item_id' => $job_item_id,
          //               'candidate_id' =>$user_id,
          //               'sla_id'      => $sla_id,
          //               'service_id'  => $item,
          //               'jaf_send_to' => $request->input('jaf'),
          //               // 'jaf_filled_by' => Auth::user()->id,
          //               'number_of_verifications'=>$service_d->verification_type=='Manual' || $service_d->verification_type=='manual'?$number_of_verifications:1,
          //               'tat' => $no_of_tat,
          //               'incentive_tat' => $incentive_tat,
          //               'penalty_tat' => $penalty_tat,
          //               'sla_item_id' => $item,
          //               'created_at'  => date('Y-m-d H:i:s')
          //             ]; 
          //     $jsi =  DB::table('job_sla_items')->insertGetId($data);  
          //     }
          //   }
          // }
          
          
          $checks= 0;
          //  Task assignment in  task table
          if ($request->input('jaf') == 'customer') {
              
            
              //  if( count($request->input('services')) > 0 ){
              //   foreach($request->input('services') as $item){
                // $id = DB::table('user_checks')->select('*')->where(['checks'=> $item])->first(); 
                // if($id !=null){
                //   $checks= $id->user_id;
                // }
                  // $kam = KeyAccountManager::where('business_id', $request->input('customer'))->first();

                  //Get data of user of customer with 
                  // $user_permissions = DB::table('users as u')
                  // ->join('role_masters as rm', 'rm.id', '=', 'u.role')
                  // ->join('role_permissions as rp', 'rp.role_id', '=', 'rm.id')
                  // ->select('u.name','u.id','u.role as role_id','rm.role','rp.permission_id' )
                  // ->where('u.business_id',Auth::user()->business_id)
                  // ->get();
                  // // Get BGV FILLING data from Action table for matching checking permission
                  //   $action_master = DB::table('action_masters')
                  //   ->select('*')
                  //   ->where(['route_group'=>'','action_title'=>'BGV Link'])
                  //   ->first(); 
                    // dd($action_master->id);
                    // Check condition if user_permission have any data or not
                  // if (count($user_permissions)>0) {
                  
                  //   $users=[];
                  //   foreach ($user_permissions as $user_permission) {
                    
                  //     if(in_array($action_master->id,json_decode($user_permission->permission_id)))
                  //     {
                  //       $users[]= $user_permission;
                  //     }
                  //   }
                  // }
                  // $task_users=[];
                  // if(count($users)>0){
                    // foreach ($users as $task_user) {
                    //   $task_users[]= $task_user->id;
                    // }
                    // $assigned_user_id =json_encode($task_users);

                    $data = [
                      'name'   =>$request->input('first_name').' '.$request->input('last_name'),
                      'parent_id'     => Auth::user()->parent_id,
                      'business_id'=> $business_id, 
                      'description' => 'BGV Filling',
                      'job_id'      => $job_id, 
                      'priority' => 'normal',
                      'candidate_id' =>$user_id,   
                      // 'service_id'  => $item, 
                      // 'assigned_to' =>$assigned_user_id,
                      // 'assigned_by' => Auth::user()->id, 
                      // 'assigned_at' => date('Y-m-d H:i:s'),
                      // 'start_date' => date('Y-m-d'),
                      'created_by'    =>Auth::user()->id,
                      'created_at'  => date('Y-m-d H:i:s'),
                      'is_completed' => '0',
                      'status'=>'1',
                      'started_at' => date('Y-m-d H:i:s')
                    ];
                    // dd($data);
                    $task =  DB::table('tasks')->insertGetId($data); 
            
                    // // dd($task_users);
                    // if (count($task_users)>1) {
                    //   $random_user=array_rand($task_users,2);
                    // } else {
                    //   $random_user=$task_users;
                    // }
                    
                    
                        
                      //  dd($random_user);
                      // foreach ($random_user as $user)
                      // {
                      //   echo  
                      // }
                      // die;
                  
                  // foreach ($users as $user) {
                  
                


                
                $taskdata = [
                  'parent_id'     => Auth::user()->parent_id,
                  'business_id'=> $business_id,
                  'candidate_id' =>$user_id,   
                  'job_sla_item_id'  => $jsi,
                  'task_id'=> $task,
                  // 'user_id' => $user->id, 
                  // 'service_id'  => $item,
                  'created_at'  => date('Y-m-d H:i:s')
                  
                ];
                DB::table('task_assignments')->insertGetId($taskdata); 
            //   }
            // }
            // }
          
          }
          $candidate_names = trim(preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $request->input('first_name').' '.$request->input('middle_name').' '.$request->input('last_name')));
          //
          $data = 
          [     'business_id'   =>$business_id,
                'candidate_id'  =>$user_id,
                'job_id'        =>$job_id,
                'name'          =>$candidate_names,
                'first_name'    =>$request->input('first_name'),
                'middle_name'   =>$request->input('middle_name'),
                'last_name'     =>$request->input('last_name'),
                'email'         =>$request->input('email'),
                'phone'         =>$phone,
                'created_by'    =>Auth::user()->id,
                'created_at'    =>date('Y-m-d H:i:s')
          ];
      
          DB::table('candidates')->insertGetId($data);
          //
          $kams  = KeyAccountManager::where('business_id',$business_id)->get();
    
          if (count($kams)>0) {
            foreach ($kams as $kam) {

              $user= User::where('id',$kam->user_id)->first();
            
              $email = $user->email;
              $name  = $user->name;
              $candidate_name = $candidate_names;
              $displayed_id=$display_id;
              $msg = "New Candidate has been created with candidate name";
              $sender = DB::table('users')->where(['id'=>$parent_id])->first();
              $data  = array('name'=>$name,'email'=>$email,'candidate_name'=>$candidate_name,'msg'=>$msg,'sender'=>$sender,'displayed_id'=>$displayed_id);

              Mail::send(['html'=>'mails.client-candidate-notify'], $data, function($message) use($email,$name) {
                    $message->to($email, $name)->subject
                      ('Clobminds Pvt Ltd - Notification for BGV Filling Task');
                    $message->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
              });

            }
            
          }
          
          if ($request->input('jaf') == 'candidate') {
            
            // //send email to customer
            // $email = $request->input('email');
            // $name  = $request->input('first_name');
            // $data  = array('name'=>$name,'email'=>$email,'case_id'=>base64_encode($job_item_id),'c_id'=>base64_encode($user_id));
            // if($email!="" || $email!=NULL)
            // {
            //   Mail::send(['html'=>'mails.jaf-info'], $data, function($message) use($email,$name) {
            //     $message->to($email, $name)->subject
            //       ('BGV Link - Fill your job application form');
            //     $message->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
            //   });
            // }
            $randomPassword = Str::random(10);
            $hashed_random_password = Hash::make($randomPassword);
            $candidate = DB::table('users')->select('email','name','display_id','business_id','parent_id')->where(['email'=>$request->input('email')])->first();
            $company = DB::table('user_businesses')->select('company_name')->where(['business_id'=>Auth::user()->business_id])->first();
            if ($candidate) {
    
              DB::table('users')->where(['email'=>$request->input('email')])->update(['password'=>$hashed_random_password,'status'=>'1']);
            }

            $email = $request->input('email');
            $name  = $request->input('first_name');
            // $data  = array('name'=>$name,'email'=>$email,'password'=>$randomPassword);

            // Mail::send(['html'=>'mails.candidate-account-info'], $data, function($message) use($email,$name) {
            //     $message->to($email, $name)->subject
            //         ('Clobminds Pvt Ltd- Your account credential');
            //     $message->from(env('MAIL_USERNAME'),'Clobminds Pvt Ltd');
            // });
            $company_name=$company->company_name;
            $id = $candidate->business_id;
            $data  = array('name'=>$name,'email'=>$email,'password'=>$randomPassword,'company_name'=>$company_name,'id'=>$id,'candidate'=>$candidate);

            Mail::send(['html'=>'mails.jaf_info_credential-candidate'], $data, function($message) use($email,$name,$company_name) {
                $message->to($email, $name)->subject
                ($name.': Authorization by '.$company_name.' to do a Background Verification Check');
                $message->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
            });

            // Send Email to Customer for BGV Link to Candidate

            $notification_controls = DB::table('notification_control_configs as nc')
                                            ->select('nc.*')
                                            ->join('notification_controls as n','n.business_id','=','nc.business_id')
                                            ->where(['n.status'=>1,'nc.status'=>1,'n.business_id'=>$candidate->business_id,'n.type'=>'jaf-sent-to-candidate','nc.type'=>'jaf-sent-to-candidate'])
                                            ->get();


            if(count($notification_controls)>0)
            {
              foreach($notification_controls as $item)
              {

                //$client = User::where('id',$id)->first();
                // dd($candidate);
                $email = $item->email;
                $name = $item->name;
                $company_name=$company->company_name;
                $sender = User::where('id',$candidate->parent_id)->first();
                
                $msg = 'Notification for Job Application Form Verifications to Candidate ('.$candidate->name.' - '.$candidate->display_id.') Has Been Sent at '.date('d-M-y h:i A').'';
                $data  = array('name'=>$name,'email'=>$email,'company_name'=>$company_name,'sender'=>$sender,'msg'=>$msg,'candidate'=>$candidate);

                Mail::send(['html'=>'mails.jaf-link'], $data, function($message) use($email,$name,$company_name) {
                    $message->to($email, $name)->subject
                    ($name.': Authorization by '.$company_name.' to do a Background Verification Check');
                    $message->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
                });


              }


            }
          }

          DB::commit();
          return response()->json([
            'success' =>true,
            'custom'  =>'yes',
            'errors'  =>[]
          ]);
      }
      catch (\Exception $e) {
        DB::rollback();
        // something went wrong
        return $e;
      }      

    }
    public function excelImport(Request $request)
    {
      $rules= [
        
        'sla'         => 'required',
        
      ];
      $customMessages = [ 
        
        'sla.required' =>  'The sla is required',
        
      ];

      $validator = Validator::make($request->all(), $rules,$customMessages);
        
        if ($validator->fails()){
            return response()->json([
                'fail' => true,
                'errors' => $validator->errors()
            ]);
        }
        DB::beginTransaction();
        try
        {

            $file = $request->file;
            // dd($file);
            $parsed_array = Excel::toArray([], $file);
            $imported_data = array_splice($parsed_array[0], 1);
            //  print_r($request->services);
            //   die();

            //   foreach($request->services as $service){
            //     $data[] = $service;
            // }
            // $service_id =json_encode($data);

          $unique = uniqid();

          foreach ($imported_data as $value)
          {
            // $excel_dob=$value[7];
            $excel_date = $value[7]; //here is that value 41621 or 41631
            // $regex = '/^(0[1-9]|[1-2][0-9]|3[0-1])-(0[1-9]|1[0-2])-([0-9]{4})$/'; 
            if(stripos($excel_date, '-') !== false || stripos($excel_date, '/') !== false){
              $excel_dob = date('Y-m-d', strtotime($excel_date));
            } else { 
              $unix_date = ($excel_date - 25569) * 86400;
              $excel_date = 25569 + ($unix_date / 86400);
              $unix_date = ($excel_date - 25569) * 86400;
              $excel_dob=gmdate("Y-m-d", $unix_date);
            } 
            // var_dump($excel_dob);
            // die;
            
            // dd($test);
            // if($excel_dob!=''){

            //     $dummy_dob = date('Y-m-d', strtotime($excel_dob));
            // }
            // else{
            //     $dummy_dob=NULL;
            // }

            $name = trim(preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $value[2].' '.$value[3].' '.$value[4]));
              $user_data = 
                    [
                       
                      'business_id' => Auth::user()->business_id,
                      'parent_id'   =>Auth::user()->parent_id,
                      'unique_id' => $unique,
                      'sla_id'  =>  $request->sla,
                      'service_id'=> $request->services,
                      'client_emp_code'      => $value[0] ,
                      'entity_code'     => $value[1] ,
                      // 'name'  =>  $value[2].' '.$value[3].' '.$value[4],
                      'name'  =>  $name,
                      'first_name'     => $value[2] ,
                      'middle_name' => $value[3],
                      'last_name'=> $value[4],
                      'father_name' => $value[5],
                      'aadhar_number' => $value[6],
                      'dob' => $excel_dob,
                      'gender'=>$value[8],
                      'phone'=>$value[9],
                      'email' => $value[10],
                      'jaf_filling_access' => $value[11],
                      'created_at'=> date('Y-m-d H:i:s')
                    ];
                    $user_id = DB::table('import_candidates')->insertGetId($user_data);

          }
          $excel_dummy = ImportCandidate::where('unique_id',$unique)->get();
          $data ='';
          
          if (count($excel_dummy)>0) {
          
            foreach ($excel_dummy as $dummy) {
                  //check condition for first name
                      
                  $first_name = $dummy->first_name; 
                  $regex = '/^([A-Za-z ]+)$/'; 
                  if (preg_match($regex, $first_name)) {
                  $first = $first_name ;
                  } else { 
                    $first = "<span class='text-danger'>".$first_name ."</span>";
                  
                  }           

                  $middle = "";
                //check condition for first name
                if ($dummy->middle_name != '') {
                  
                  $middle_name = $dummy->middle_name; 
                  $regex = '/^([a-zA-Z ]+)$/'; 
                  if (preg_match($regex, $middle_name)) {
                  $middle = $middle_name ;
                  } else { 
                    $middle = "<span class='text-danger'>".$middle_name ."</span>";
                
                  }           
                }
              //   // Last Name Check
              $last="";
              if ($dummy->last_name != '') {
                

                $last_name = $dummy->last_name; 
                $regex = '/^([a-zA-Z ]+)$/'; 
                if (preg_match($regex, $last_name)) {
                $last = $last_name ;
                } else { 
                  $last ="<span class='text-danger'>". $last_name ."</span>";
                }           
              }

               if ($dummy->father_name !='') {
                  $father_name = $dummy->father_name; 
                    $regex = '/^([a-zA-Z ]+)$/'; 
                    if (preg_match($regex, $father_name)) {
                    $father = $father_name ;
                    } else { 
                      $father ="<span class='text-danger'>". $father_name ."</span>";
                    } 
              }
                    else{

                        $father ="<span class='text-danger exceldata' ><input type='hidden' value='".$dummy->id."'>Required</span>";
                    }
              //check Aadhar number
              $aadhar='';
              if ($dummy->aadhar_number != '') {
                
                $aadhar_number = $dummy->aadhar_number; 
                $regex = ' /^[1-9]{1}[0-9]{11}$/'; 
                if (preg_match($regex, $aadhar_number)) {
                $aadhar = $aadhar_number ;
                } else { 
                  $aadhar ="<span class='text-danger'>". $aadhar_number ."</span>";
                }  
              }   

              //Check DOB validation
              if ($dummy->dob !=NULL) {
                $dob = $dummy->dob; 
                  $regex = '/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/'; 
                  if (preg_match($regex, $dob)) {
                  $birth_date = $dob ;
                  } else { 
                    $birth_date ="<span class='text-danger'>". $dob ."</span>";
                  } 
              }
              else{
                $birth_date ="<span class='text-danger exceldata' ><input type='hidden' value='".$dummy->id."'>Required</span>";
            }
                // Check Gender
                $genders="N/A";
              if ($dummy->gender != '') {
                
                if ($dummy->gender == 'male' || $dummy->gender == 'female' || $dummy->gender == 'others' ||  $dummy->gender == 'other' || $dummy->gender == 'Male' || $dummy->gender == 'Female' || $dummy->gender == 'Others' ||  $dummy->gender == 'Other'  ) {
                  $genders =  $dummy->gender;
                } 
                else {
                  $genders = "<span class='text-danger'>". $dummy->gender ."</span>";
                }
              }

              // Check Mobile nummber 
              $mob ='';
              if ($dummy->phone != '') {
                $phone = $dummy->phone; 
                $regex = ' /^[1-9]{1}[0-9]{9}$/'; 
                if (preg_match($regex, $phone)) {
                $mob = $phone ;
                } else { 
                  $mob = "<span class='text-danger'>".$phone ."</span>";
                }  
              }

              //check condition for Email
              $email_id ='';
                   $check_email='';
                    if ($dummy->email != '') {
                        $email =strtolower($dummy->email);
                        $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/'; 
                        $user_email = DB::table('users')->select('email')->where('email',$email)->first();
                        if($user_email){
                            $check_email = $user_email->email;
                        }
                        // dd($user_email);
                        if (preg_match($regex, $email) && ($check_email!=$email)) {
                            $email_id = $email ;
                        } else { 
                            $email_id ="<span class='text-danger exceldata'> <input type='hidden' value='".$dummy->id."'>". $email ."</span>";
                        }   
                    }
              // $email = $dummy->email; 
              // $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/'; 
              // if (preg_match($regex, $email)) {
              // $email_id = $email ;
              // } else { 
              //   $email_id ="<span class='text-danger'>". $email ."</span>";
              // }           
              $jaf_send_to="";
              if ($dummy->jaf_filling_access == 'Vendor' || $dummy->jaf_filling_access == 'vendor' || $dummy->jaf_filling_access == 'candidate' || $dummy->jaf_filling_access == 'Candidate') {
              if($dummy->jaf_filling_access=='Vendor' || $dummy->jaf_filling_access == 'vendor' ){

                $jaf_send_to = 'Vendor';

              }
              elseif ($dummy->jaf_filling_access == 'candidate' || $dummy->jaf_filling_access == 'Candidate') {
                $jaf_send_to = strtolower($dummy->jaf_filling_access);
              }
              
                $jaf_send_to = strtolower($dummy->jaf_filling_access);
              }
              else {
                $jaf_send_to ="<span class='text-danger'>". $dummy->jaf_filling_access ."</span>";
              }

              $data .= '<tr><td>'.$dummy->client_emp_code.'</td><td>'.$dummy->entity_code.'</td><td>'.$first.'</td><td>'.$middle.'</td><td>'.$last.'</td><td>'.$father.'</td><td>'.$aadhar.'</td><td>'.$birth_date.'</td><td>'.$genders.'</td><td>'.$email_id .'</td><td>'.$mob.'</td><td>'.$jaf_send_to.'</td></tr>';

            }
          }
          else
          {
            $data .= '<tr><td>'.'No Data Found'.'</td></tr>';
          }

          DB::commit();
          return response()->json([
            'fail'      =>false,
            'excel' => $data,
            'unique_excel_id' =>$unique
          
          ]);
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        }       
          
    }
  
    //store Multiple Candidate
 public function storeMultiple(Request $request)
 {
  //  dd($request->all());
    // // $tat = $request->input('tats');
    // // $tats = explode(',', $tat);
      
        $unique = $request->input('unique_id');
        $customer_id =Auth::user()->business_id;
        $sla_id = $request->input('sla_id');
        // $sla_type = $request->input('sla_type');
        // $service_id = $request->input('service_id');
        // $service_unit = $request->input('service_units');
        // $tat = $request->input('tats');
        // $incentive = $request->input('incentives');
        // $penalty = $request->input('penalties');
        // $check_price = $request->input('check_prices');
        // $days_type = $request->input('days_types');
        // $price_type = $request->input('price_types');
        // $package_price = $request->package_price;
        $business_id = Auth::user()->business_id;
        // $services = explode(',', $service_id);
        // $service_unit = explode(',', $service_unit);
        // $tats = explode(',', $tat);
        // $incentives = explode(',', $incentive);
        // $penalties = explode(',', $penalty);
        // $check_prices = explode(',', $check_price);
        // $max_service_tat = max($tats);
        // dd($key);
        $customer_sla=DB::table('customer_sla')->where('id',$sla_id)->first();
        DB::beginTransaction();
        try{
          $excel_dummy = ImportCandidate::where('unique_id',$unique)->get();
          $data ='';
          if (count($excel_dummy)>0) {
            foreach ($excel_dummy as $dummy) {
                    //check condition for first name
                  
                $first_name = $dummy->first_name; 
                $regex = '/^([a-zA-Z ]+)$/'; 
                if (preg_match($regex, $first_name)) {
                  $first = $first_name ;
                } else { 
                  continue;
                }           

                $middle = "";
              //check condition for first name
              if ($dummy->middle_name != '') {
                
                $middle_name = $dummy->middle_name; 
                $regex = '/^([a-zA-Z ]+)$/'; 
                if (preg_match($regex, $middle_name)) {
                  $middle = $middle_name ;
                } else { 
                  continue;
                }           
              }
              //   // Last Name Check
              $last='';
              if ($dummy->last_name != '') {
                
                $last_name = $dummy->last_name; 
                $regex = '/^([a-zA-Z ]+)$/'; 
                if (preg_match($regex, $last_name)) {
                $last = $last_name ;
                } else { 
                  continue;
                }           
              }

              if ($dummy->father_name !='') {  
                $father_name = $dummy->father_name; 
                $regex = '/^([a-zA-Z ]+)$/'; 
                if (preg_match($regex, $father_name)) {
                  $father = $father_name ;
                } else { 
                  continue;
                } 
              }
              else{
                  continue;
              }
              //check Aadhar number
              $aadhar='';
              if ($dummy->aadhar_number != '') {
                
                  $aadhar_number = $dummy->aadhar_number; 
                  $regex = '/^[1-9]{1}[0-9]{11}$/'; 
                  if (preg_match($regex, $aadhar_number)) {
                    $aadhar = $aadhar_number ;
                  } else { 
                    continue;
                  }  
              }   

              //Check DOB validation
               if ($dummy->dob !=NULL) {
                    $dob = $dummy->dob; 
                    $regex = '/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/'; 
                  if (preg_match($regex, $dob)) {
                    $birth_date = $dob ;
                  } else { 
                    continue;
                  } 
              }
                else{

                   continue;
                }
                // Check Gender
                $genders ="N/A";
              if ($dummy->gender != '') {
                
                if ($dummy->gender == 'male' || $dummy->gender == 'female' || $dummy->gender == 'others' ||  $dummy->gender == 'other' || $dummy->gender == 'Male' || $dummy->gender == 'Female' || $dummy->gender == 'Others' ||  $dummy->gender == 'Other'  ) {
                  $genders =  $dummy->gender;
                } 
                else {
                  continue;
                }
              }

              // Check Mobile nummber 
              $mob =null;
              if ($dummy->phone != '') {
                $phone = $dummy->phone; 
                $regex = '/^[1-9]{1}[0-9]{9}$/'; 
                if (preg_match($regex, $phone)) {
                $mob = $phone ;
                } else { 
                continue;
                }  
              }

              //check condition for Email
              $email_id =null;
              $check_email='';
              if ($dummy->email !='') {
                  $email = strtolower($dummy->email); 
                  $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/'; 
                    $user_email = DB::table('users')->select('email')->where('email',$email)->first();
                    if($user_email){
                      $check_email = $user_email->email;
                  }
                  if (preg_match($regex, $email) && ($check_email!=$email)) {
                      $email_id = $email ;
                  } else { 
                      continue;
                  } 
              }
              //   $email = $dummy->email; 
              //   $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/'; 
              // if (preg_match($regex, $email)) {
              //   $email_id = $email ;
              // } else { 
              //   continue;
              // }           

              if ($dummy->jaf_filling_access == 'Vendor' || $dummy->jaf_filling_access == 'vendor' || $dummy->jaf_filling_access == 'candidate' || $dummy->jaf_filling_access == 'Candidate') {
                if($dummy->jaf_filling_access=='Vendor' || $dummy->jaf_filling_access == 'vendor' ){

                  $jaf_send_to = 'customer';
  
                }
                elseif ($dummy->jaf_filling_access == 'candidate' || $dummy->jaf_filling_access == 'Candidate') {
                  $jaf_send_to = strtolower($dummy->jaf_filling_access);
                }
              }
              else {
                continue;
              }

                
              // $is_send_jaf_link = 0;
              // if($request->input('is_send_jaf_link')){
              //   $is_send_jaf_link = '1';
              // }

              //create user 

              $name = trim(preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $first.' '.$middle.' '.$last));
              $data = 
              [
                  'business_id'   =>Auth::user()->business_id,
                  'user_type'     =>'candidate',
                  'client_emp_code'=>$request->client_emp_code,
                  'entity_code'   =>$request->entity_code,
                  'parent_id'     =>Auth::user()->parent_id,
                  'name'          =>$name,
                  'first_name'    =>$first,
                  'middle_name'   =>$middle,
                  'last_name'     =>$last,
                  'father_name'   =>$father,
                  'aadhar_number' =>$aadhar,
                  'dob'           =>$birth_date,
                  'gender'        =>$genders,
                  'email'         =>$email_id,
                  //  'password'       => Hash::make($request->input('password')),
                  'phone'         =>$mob,
                  'created_by'    =>Auth::user()->id,
                  'created_at'    =>date('Y-m-d H:i:s') 
              ];
            
              $user_id = DB::table('users')->insertGetId($data);
              // dd($user_id);
              $display_id = "";
                //check customer config
              $candidate_config = DB::table('candidate_config')
              ->where(['client_id'=>$customer_id,'business_id'=>$business_id])
              ->first();
              // dd($candidate_config);
              //check client 
              $client_config = DB::table('user_businesses')
              ->where(['business_id'=>$customer_id])
              ->first();

              $latest_user = DB::table('users')
              ->select('display_id')
              ->where(['user_type'=>'candidate','business_id'=>$business_id])
              ->orderBy('id','desc')
              ->first();
              // dd($latest_user);
              $starting_number = $user_id;

              if($candidate_config !=null){
                if($latest_user != null){
                  if($latest_user->display_id !=null){
                    $id_arr = explode('-',$latest_user->display_id);
                    $starting_number = $id_arr[2]+1;  
                  }
                }
                $starting_number = str_pad($starting_number, 10, "0", STR_PAD_LEFT);
                $display_id = $candidate_config->customer_prefix.'-'.$candidate_config->client_prefix.'-'.$starting_number;
              }else{
                $customer_company = DB::table('user_businesses')
                  ->select('company_name')
                  ->where(['business_id'=>$business_id])
                  ->first();
                
                  $client_company = DB::table('user_businesses')
                  ->select('company_name')
                  ->where(['business_id'=>$customer_id])
                  ->first();
                  
                  $u_id = str_pad($user_id, 10, "0", STR_PAD_LEFT);
                  $display_id = substr($customer_company->company_name,0,4).'-'.substr($client_company->company_name,0,4).'-'.$u_id;
              }
              //
              DB::table('users')->where(['id'=>$user_id])->update(['display_id'=>$display_id]);
              
              //
              $job_data = 
              [
                'business_id'  => $customer_id,
                'parent_id'    => Auth::user()->business_id,
                'title'        => NULL,
                'sla_id'       => $sla_id,
                'total_candidates'=>1,
                // 'send_jaf_link_required'=>$is_send_jaf_link,
                'status'       =>0,
                'created_by'   =>Auth::user()->id,
                'created_at'   => date('Y-m-d H:i:s')
              ];
              
              $job_id = DB::table('jobs')->insertGetId($job_data);

              // job item items      
              $data = ['business_id' =>$customer_id, 
              'job_id'       =>$job_id, 
              'candidate_id' =>$user_id,
              'sla_id'       =>$sla_id,
              'jaf_status'   =>'pending',
              'created_at'   =>date('Y-m-d H:i:s')
              ];
              $job_item_id = DB::table('job_items')->insertGetId($data);  

              
                  DB::table('job_items')->where(['business_id'=>$customer_id,'candidate_id'=>$user_id])->update([
                      'package_price' => $customer_sla->package_price,
                  ]);
              // service items
             
                
                    // $number_of_verifications=1;
                  // $service_d=DB::table('services')->where('id',$item)->first();
                  $number_of_verifications=1;
                  $no_of_tat=1;
                  $incentive_tat=1;
                  $penalty_tat=1;
                  $price = 0;
                    $sal_item_data = DB::table('customer_sla_items')->select('number_of_verifications','tat','incentive_tat','penalty_tat','price')->where(['sla_id'=>$sla_id])->first(); 
                    if($sal_item_data !=null){
                      $number_of_verifications= $sal_item_data->number_of_verifications;
                      $no_of_tat= $sal_item_data->tat;
                      $incentive_tat= $sal_item_data->incentive_tat;
                      $penalty_tat= $sal_item_data->penalty_tat;
                      $price= $sal_item_data->price;
                    }
                      $data = ['business_id'=>  $customer_id, 
                              'job_id'      => $job_id, 
                              'job_item_id' => $job_item_id,
                              'candidate_id' =>$user_id,
                              'sla_id'      => $sla_id,
                              // 'service_id'  => $item,
                              'jaf_send_to' => $jaf_send_to,
                              'jaf_filled_by' => Auth::user()->id,
                              'number_of_verifications'=>$number_of_verifications,
                              'tat'=>$no_of_tat,
                              'incentive_tat'=>$incentive_tat,
                              'penalty_tat'=>$penalty_tat,
                              'price'   => $price,
                              // 'sla_item_id' => $item,
                              'created_at'  => date('Y-m-d H:i:s')
                            ]; 
                    $jsi =  DB::table('job_sla_items')->insertGetId($data);  
              
              $checks= 0;
                // service  items uses in  task table
                if ($dummy->jaf_filling_access == 'customer' || $dummy->jaf_filling_access == 'Customer') {
                  //Get data of user of customer with 
                  $data = [
                    'name'   =>$first.' '.$last,
                    'parent_id'=> Auth::user()->business_id,
                    'business_id'=> $customer_id, 
                    'description' => 'BGV Filling',
                    'job_id'      => $job_id, 
                    'priority' => 'normal',
                    'candidate_id' =>$user_id,   
                    // 'service_id'  => $item, 
                    // 'assigned_to' =>$assigned_user_id,
                    // 'assigned_by' => Auth::user()->id, 
                    // 'assigned_at' => date('Y-m-d H:i:s'),
                    // 'start_date' => date('Y-m-d'),
                    'created_by'    =>Auth::user()->id,
                    'created_at'  => date('Y-m-d H:i:s'),
                    'is_completed' => 0,
                    'status'=>'1',
                    'started_at' => date('Y-m-d H:i:s'),
                    'updated_at'  => date('Y-m-d H:i:s')
                  ];
                  // dd($data);
                  $task =  DB::table('tasks')->insertGetId($data); 

                  // // dd($task_users); 
                  // if (count($task_users)>1) {
                  //   $random_user=array_rand($task_users,2);
                  // } else {
                  //   $random_user=$task_users;
                  // }
                
                
                    
                  //  dd($random_user);
                  // foreach ($random_user as $user)
                  // {
                  //   echo  
                  // }
                  // die;
              
                  // foreach ($users as $user) {
              
            


                
                  $taskdata = [
                    'parent_id'=> Auth::user()->business_id,
                    'business_id'=> $customer_id,
                    'candidate_id' =>$user_id,   
                    'job_sla_item_id'  => $jsi,
                    'task_id'=> $task,
                    // 'user_id' => $user->id,
                    // 'service_id'  => $item,
                    'created_at'  => date('Y-m-d H:i:s'),
                    'updated_at'  => date('Y-m-d H:i:s')
                    
                  ];
                  DB::table('task_assignments')->insertGetId($taskdata); 

                    if (Auth::user()->user_type == 'customer') {
                      # code...
                        $admin_email = Auth::user()->email;
                        $admin_name = Auth::user()->first_name;
                        //send email to customer
                        $email = $admin_email;
                        $name  = $admin_name;
                        $candidate_name = $first;
                        $msg = "New BGV Filling Task Created with candidate name";
                        $sender = DB::table('users')->where(['id'=>$business_id])->first();
                        $data  = array('name'=>$name,'email'=>$email,'candidate_name'=>$candidate_name,'msg'=>$msg,'sender'=>$sender);

                        Mail::send(['html'=>'mails.task-notify'], $data, function($message) use($email,$name) {
                              $message->to($email, $name)->subject
                                ('Clobminds Pvt Ltd - Notification for BGV Filling Task');
                              $message->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
                        });
                    }
                    else
                    {

                      $login_user = Auth::user()->business_id;
                      $user= User::where('id',$login_user)->first();
                      $admin_email = $user->email;
                      $admin_name = $user->first_name;
                      //send email to customer
                      $email = $admin_email;
                      $name  = $admin_name;
                      $candidate_name = $first;
                      $msg = "New BGV Filling Task Created with candidate name";
                      $sender = DB::table('users')->where(['id'=>$business_id])->first();
                      $data  = array('name'=>$name,'email'=>$email,'candidate_name'=>$candidate_name,'msg'=>$msg,'sender'=>$sender);

                      Mail::send(['html'=>'mails.task-notify'], $data, function($message) use($email,$name) {
                            $message->to($email, $name)->subject
                              ('Clobminds Pvt Ltd - Notification for BGV Filling Task');
                            $message->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
                      });
                    }

                  $kams  = KeyAccountManager::where('business_id',$customer_id)->get();

                  if (count($kams)>0) {
                    foreach ($kams as $kam) {

                      $user= User::where('id',$kam->user_id)->first();
                      
                      $email = $user->email;
                      $name  = $user->name;
                      $candidate_name = $first;
                      $msg = "New BGV Filling Task Created with candidate name";
                      $sender = DB::table('users')->where(['id'=>$business_id])->first();
                      $data  = array('name'=>$name,'email'=>$email,'candidate_name'=>$candidate_name,'msg'=>$msg,'sender'=>$sender);

                      Mail::send(['html'=>'mails.task-notify'], $data, function($message) use($email,$name) {
                            $message->to($email, $name)->subject
                              ('Clobminds Pvt Ltd - Notification for BGV Filling Task');
                            $message->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
                      });

                    }
                    
                  }

                }
                //
                $data = 
                [     'business_id'   =>$customer_id,
                      'candidate_id'  =>$user_id,
                      'job_id'        =>$job_id,
                      'name'          =>$first.' '.$middle.' '.$last,
                      'first_name'    =>$first,
                      'middle_name'   =>$middle,
                      'last_name'     =>$last,
                      'email'         =>$email_id,
                      'phone'         =>$mob,
                      'created_by'    =>Auth::user()->id,
                      'created_at'    =>date('Y-m-d H:i:s')
                ];
              
                DB::table('candidates')->insertGetId($data);

                // Mail Send to COC
                $user= User::where('id',$customer_id)->first();

                $email = $user->email;
                $name  = $user->name;
                $candidate_name = $first;
                $msg = "New Candidate Has Been Created with candidate name";
                $sender = DB::table('users')->where(['id'=>$business_id])->first();
                $data  = array('name'=>$name,'email'=>$email,'candidate_name'=>$candidate_name,'msg'=>$msg,'sender'=>$sender);

                Mail::send(['html'=>'mails.task-notify'], $data, function($message) use($email,$name) {
                      $message->to($email, $name)->subject
                        ('Clobminds Pvt Ltd - Notification for New Candidate Created');
                      $message->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
                });

                if ($jaf_send_to == 'candidate' || $jaf_send_to == 'Candidate') {
                  
                      //send email to candidate
                      $randomPassword = Str::random(10);
                      $hashed_random_password = Hash::make($randomPassword);
                      $candidate = DB::table('users')->select('email','first_name','name','business_id')->where(['id'=>$user_id])->first();
                      $company = DB::table('user_businesses')->select('company_name')->where(['business_id'=>$candidate->business_id])->first();
                      if ($candidate) {
              
                        DB::table('users')->where(['id'=>$user_id])->update(['password'=>$hashed_random_password,'status'=>'1']);
                      }

                      $email = $email_id;
                      $name  = $first;
                      $company_name=$company->company_name;
                      $id = $candidate->business_id;

                      // $data  = array('name'=>$name,'email'=>$email,'case_id'=>base64_encode($job_item_id),'c_id'=>base64_encode($user_id));

                      //   Mail::send(['html'=>'mails.jaf-info'], $data, function($message) use($email,$name) {
                      //       $message->to($email, $name)->subject
                      //           ('Clobminds Pvt Ltd - Your account credential');
                      //       $message->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
                      //   });

                      $data  = array('name'=>$name,'email'=>$email,'password'=>$randomPassword,'company_name'=>$company_name,'id'=>$id);

                      Mail::send(['html'=>'mails.jaf_info_credential-candidate'], $data, function($message) use($email,$name) {
                          $message->to($email, $name)->subject
                              ('Clobminds Pvt Ltd - Your account credential');
                          $message->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
                      });
                }
            }
          }
          DB::commit();
          return response()->json([
            'fail'      =>false,
            'error' => '',
            
          
          ]);
          // return redirect('/candidates')
          //             ->with('success', 'All candidates have been successfully created.');
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        } 
 }
    /**
     * Delete Candidate
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteCandidate(Request $request)
    {   
        $candidate_id = base64_decode($request->get('candidate_id'));  
        DB::beginTransaction();      
        try{      
          if(Auth::check()){

            $user_id = Auth::user()->id;

            $candidate = DB::table('users')->select('id','business_id')->where('id',$candidate_id)->first();

            DB::table('users')
              ->where('id', $candidate_id)
              ->update(['is_deleted' => 1,'deleted_at'=>date('Y-m-d H:i:s'),'deleted_by'=>Auth::user()->id]);

            DB::table('delete_logs')->insert([
                'business_id' => $candidate->business_id,
                'user_id' => $candidate->id,
                'created_by' => $user_id,
                'user_type' => 'candidate',
                'created_at' => date('Y-m-d H:i:s')
            ]);

              $is_deleted=TRUE;
            
              //return result 
              if($is_deleted){   
                  DB::commit();
                  return response()->json([
                  'status'=>'ok',
                  'message' => 'deleted',                
                  ], 200);
              }
              else{
                  return response()->json([
                  'status' =>'no',
                  ], 200);
              }

          }
          else{   
              return response()->json([
                  'status' =>'no',
                  ], 200);
          }
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        }
    }

    /**
     * Delete Candidate
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteCandidatePermanent(Request $request)
    {
        $candidate_id = base64_decode($request->get('candidate_id'));  
        DB::beginTransaction();      
        try{
            if(Auth::check()){

                $user_id = Auth::user()->id;

                $candidate = DB::table('users')->where('id',$candidate_id)->first();
    
                $modules = [];
    
                $tables = [];
    
                $module_all = [];

                // Check Tables

                $aadhar_check=DB::table('aadhar_checks')->where('candidate_id',$candidate_id)->get();

                if(count($aadhar_check)>0)
                {
                    $tables[]='aadhar_checks';

                    if(array_key_exists('Verification Checks',$module_all))
                    {
                        array_push($module_all['Verification Checks'],'aadhar_checks');
                    }
                    else
                    {
                        $module_all['Verification Checks']=['aadhar_checks'];
                    }

                    foreach($aadhar_check as $aadhar)
                    {
                        AadharCheck::find($aadhar->id)->delete();
                    }
                }

                $bank_check=DB::table('bank_account_checks')->where('candidate_id',$candidate_id)->get();

                if(count($bank_check)>0)
                {
                    $tables[]='bank_account_checks';

                    if(array_key_exists('Verification Checks',$module_all))
                    {
                        array_push($module_all['Verification Checks'],'bank_account_checks');
                    }
                    else
                    {
                        $module_all['Verification Checks']=['bank_account_checks'];
                    }

                    foreach($bank_check as $bank)
                    {
                        BankAccountCheck::find($bank->id)->delete();
                    }
                }

                $cin_check=DB::table('cin_checks')->where('candidate_id',$candidate_id)->get();

                if(count($cin_check)>0)
                {
                    $tables[]='cin_checks';

                    if(array_key_exists('Verification Checks',$module_all))
                    {
                        array_push($module_all['Verification Checks'],'cin_checks');
                    }
                    else
                    {
                        $module_all['Verification Checks']=['cin_checks'];
                    }

                    foreach($cin_check as $cin)
                    {
                        CinCheck::find($cin->id)->delete();
                    }
                }

                $covid19_check=DB::table('covid19_checks')->where('candidate_id',$candidate_id)->get();

                if(count($covid19_check)>0)
                {
                    $tables[]='covid19_checks';

                    if(array_key_exists('Verification Checks',$module_all))
                    {
                        array_push($module_all['Verification Checks'],'covid19_checks');
                    }
                    else
                    {
                        $module_all['Verification Checks']=['covid19_checks'];
                    }

                    foreach($covid19_check as $covid)
                    {
                        Covid19Check::find($covid->id)->delete();
                    }
                }

                $dl_check=DB::table('dl_checks')->where('candidate_id',$candidate_id)->get();

                if(count($dl_check)>0)
                {
                    $tables[]='dl_checks';

                    if(array_key_exists('Verification Checks',$module_all))
                    {
                        array_push($module_all['Verification Checks'],'dl_checks');
                    }
                    else
                    {
                        $module_all['Verification Checks']=['dl_checks'];
                    }

                    foreach($dl_check as $dl)
                    {
                        DlCheck::find($dl->id)->delete();
                    }
                }

                $ecourt_check=DB::table('e_court_checks')->where('candidate_id',$candidate_id)->get();

                if(count($ecourt_check)>0)
                {
                    $tables[]='e_court_checks';

                    if(array_key_exists('Verification Checks',$module_all))
                    {
                        array_push($module_all['Verification Checks'],'e_court_checks');
                    }
                    else
                    {
                        $module_all['Verification Checks']=['e_court_checks'];
                    }

                    foreach($ecourt_check as $ecourt)
                    {
                        ECourtCheck::find($ecourt->id)->delete();
                    }
                }

                $ecourt_check_items=DB::table('e_court_check_items')->where('candidate_id',$candidate_id)->get();

                if(count($ecourt_check_items)>0)
                {
                    $tables[]='e_court_check_items';

                    if(array_key_exists('Verification Checks',$module_all))
                    {
                        array_push($module_all['Verification Checks'],'e_court_check_items');
                    }
                    else
                    {
                        $module_all['Verification Checks']=['e_court_check_items'];
                    }

                    foreach($ecourt_check_items as $ecourt)
                    {
                        ECourtCheckItem::find($ecourt->id)->delete();
                    }
                }

                $gst_check=DB::table('gst_checks')->where('candidate_id',$candidate_id)->get();

                if(count($gst_check)>0)
                {
                    $tables[]='gst_checks';

                    if(array_key_exists('Verification Checks',$module_all))
                    {
                        array_push($module_all['Verification Checks'],'gst_checks');
                    }
                    else
                    {
                        $module_all['Verification Checks']=['gst_checks'];
                    }

                    foreach($gst_check as $gst)
                    {
                        GstCheck::find($gst->id)->delete();
                    }
                }

                $pan_check=DB::table('pan_checks')->where('candidate_id',$candidate_id)->get();

                if(count($pan_check)>0)
                {
                    $tables[]='pan_checks';

                    if(array_key_exists('Verification Checks',$module_all))
                    {
                        array_push($module_all['Verification Checks'],'pan_checks');
                    }
                    else
                    {
                        $module_all['Verification Checks']=['pan_checks'];
                    }

                    foreach($pan_check as $pan)
                    {
                        PanCheck::find($pan->id)->delete();
                    }
                }

                $passport_check=DB::table('passport_checks')->where('candidate_id',$candidate_id)->get();

                if(count($passport_check)>0)
                {
                    $tables[]='passport_checks';

                    if(array_key_exists('Verification Checks',$module_all))
                    {
                        array_push($module_all['Verification Checks'],'passport_checks');
                    }
                    else
                    {
                        $module_all['Verification Checks']=['passport_checks'];
                    }

                    foreach($passport_check as $passport)
                    {
                        PassportCheck::find($passport->id)->delete();
                    }
                }

                $rc_check=DB::table('rc_checks')->where('candidate_id',$candidate_id)->get();

                if(count($rc_check)>0)
                {
                    $tables[]='rc_checks';

                    if(array_key_exists('Verification Checks',$module_all))
                    {
                        array_push($module_all['Verification Checks'],'rc_checks');
                    }
                    else
                    {
                        $module_all['Verification Checks']=['rc_checks'];
                    }

                    foreach($rc_check as $rc)
                    {
                        RcCheck::find($rc->id)->delete();
                    }
                }

                $telecom_check=DB::table('telecom_check')->where('candidate_id',$candidate_id)->get();

                if(count($telecom_check)>0)
                {
                    $tables[]='telecom_check';

                    if(array_key_exists('Verification Checks',$module_all))
                    {
                        array_push($module_all['Verification Checks'],'telecom_check');
                    }
                    else
                    {
                        $module_all['Verification Checks']=['telecom_check'];
                    }

                    foreach($telecom_check as $telecom)
                    {
                        TelecomCheck::find($telecom->id)->delete();
                    }
                }

                $upi_check=DB::table('upi_checks')->where('candidate_id',$candidate_id)->get();

                if(count($upi_check)>0)
                {
                    $tables[]='upi_checks';

                    if(array_key_exists('Verification Checks',$module_all))
                    {
                        array_push($module_all['Verification Checks'],'upi_checks');
                    }
                    else
                    {
                        $module_all['Verification Checks']=['upi_checks'];
                    }

                    foreach($upi_check as $upi)
                    {
                        UpiCheck::find($upi->id)->delete();
                    }
                }

                $voter_id=DB::table('voter_id_checks')->where('candidate_id',$candidate_id)->get();

                if(count($voter_id)>0)
                {
                    $tables[]='voter_id_checks';

                    if(array_key_exists('Verification Checks',$module_all))
                    {
                        array_push($module_all['Verification Checks'],'voter_id_checks');
                    }
                    else
                    {
                        $module_all['Verification Checks']=['voter_id_checks'];
                    }

                    foreach($voter_id as $voter)
                    {
                        VoterIdCheck::find($voter->id)->delete();
                    }
                }

                if(count($aadhar_check)>0 || count($pan_check)>0 || count($voter_id)>0 || count($rc_check)>0 || count($dl_check)>0 || count($passport_check)>0 || count($bank_check)>0 || count($covid19_check)>0 || count($ecourt_check)>0 || count($ecourt_check_items) > 0 || count($cin_check)>0 || count($upi_check)>0 || count($gst_check)>0)
                {
                  $modules[]='Verification Checks';
                }

                // 

                $candidates=DB::table('candidates')->where('candidate_id',$candidate_id)->get();

                $modules[]='Candidate';

                if(count($candidates)>0)
                {
                    $tables[]='candidates';

                    if(array_key_exists('Candidate',$module_all))
                    {
                        array_push($module_all['Candidate'],'candidates');
                    }
                    else
                    {
                        $module_all['Candidate']=['candidates'];
                    }

                    foreach($candidates as $c)
                    {
                        Candidate::find($c->id)->delete();
                    }
                }

                $candidate_hold=DB::table('candidate_hold_statuses')->where('candidate_id',$candidate_id)->get();

                if(count($candidate_hold)>0)
                {
                    $tables[]='candidate_hold_statuses';

                    if(array_key_exists('Candidate',$module_all))
                    {
                        array_push($module_all['Candidate'],'candidate_hold_statuses');
                    }
                    else
                    {
                        $module_all['Candidate']=['candidate_hold_statuses'];
                    }

                    foreach($candidate_hold as $c)
                    {
                      CandidateHoldStatus::find($c->id)->delete();
                    }
                }

                $candidate_jaf_status = DB::table('candidate_jaf_status')->where('candidate_id',$candidate_id)->get();

                if(count($candidate_jaf_status)>0)
                {
                  DB::table('candidate_jaf_status')->where('candidate_id',$candidate_id)->delete();

                  $tables[]='candidate_jaf_status';

                  if(array_key_exists('Candidate',$module_all))
                  {
                      array_push($module_all['Candidate'],'candidate_jaf_status');
                  }
                  else
                  {
                      $module_all['Candidate']=['candidate_jaf_status'];
                  }
                }

                $coc_notify=DB::table('coc_notification_masters')->where('candidate_id',$candidate_id)->get();

                if(count($coc_notify)>0)
                {
                    $tables[]='coc_notification_masters';

                    if(array_key_exists('Candidate',$module_all))
                    {
                        array_push($module_all['Candidate'],'coc_notification_masters');
                    }
                    else
                    {
                        $module_all['Candidate']=['coc_notification_masters'];
                    }

                    foreach($coc_notify as $c)
                    {
                      CocNotificationMaster::find($c->id)->delete();
                    }
                }

                $digital_address=DB::table('digital_address_verifications')->where('candidate_id',$candidate_id)->get();

                if(count($digital_address)>0)
                {
                    $tables[]='digital_address_verifications';

                    if(array_key_exists('Candidate',$module_all))
                    {
                        array_push($module_all['Candidate'],'digital_address_verifications');
                    }
                    else
                    {
                        $module_all['Candidate']=['digital_address_verifications'];
                    }

                    foreach($digital_address as $digital)
                    {
                      DigitalAddressVerification::find($digital->id)->delete();
                    }
                }

                $report_exports = DB::table('report_exports')->where('candidate_id',$candidate_id)->get();

                if(count($report_exports)>0)
                {
                    $tables[]='report_exports';

                    if(array_key_exists('Candidate',$module_all))
                    {
                        array_push($module_all['Candidate'],'report_exports');
                    }
                    else
                    {
                        $module_all['Candidate']=['report_exports'];
                    }

                    DB::table('report_exports')->where('candidate_id',$candidate_id)->delete();
                }

                $verification_insuff=DB::table('verification_insufficiency')->where('candidate_id',$candidate_id)->get();

                if(count($verification_insuff)>0)
                {
                    $tables[]='verification_insufficiency';

                    if(array_key_exists('Candidate',$module_all))
                    {
                        array_push($module_all['Candidate'],'verification_insufficiency');
                    }
                    else
                    {
                        $module_all['Candidate']=['verification_insufficiency'];
                    }

                    foreach($verification_insuff as $insuff)
                    {
                      VerificationInsufficiency::find($insuff->id)->delete();
                    }
                }

                $address_verifications=DB::table('address_verifications')->where('candidate_id',$candidate_id)->get();

                if(count($address_verifications)>0)
                {
                    $tables[]='address_verifications';

                    if(array_key_exists('Candidate',$module_all))
                    {
                        array_push($module_all['Candidate'],'address_verifications');
                    }
                    else
                    {
                        $module_all['Candidate']=['address_verifications'];
                    }

                    foreach($address_verifications as $address)
                    {
                      AddressVerification::find($address->id)->delete();
                    }
                }

                $address_decision=DB::table('address_verification_decision_logs')->where('candidate_id',$candidate_id)->get();

                if(count($address_decision)>0)
                {
                    $tables[]='address_verification_decision_logs';

                    if(array_key_exists('Candidate',$module_all))
                    {
                        array_push($module_all['Candidate'],'address_verification_decision_logs');
                    }
                    else
                    {
                        $module_all['Candidate']=['address_verification_decision_logs'];
                    }

                    foreach($address_decision as $address)
                    {
                      AddressVerificationDecisionLog::find($address->id)->delete();
                    }
                }

                $otp_email=DB::table('otp_by_email')->where('candidate_id',$candidate_id)->get();

                if(count($otp_email)>0)
                {
                    $tables[]='otp_by_email';

                    if(array_key_exists('Candidate',$module_all))
                    {
                        array_push($module_all['Candidate'],'otp_by_email');
                    }
                    else
                    {
                        $module_all['Candidate']=['otp_by_email'];
                    }

                    foreach($otp_email as $otp)
                    {
                      OtpByEmail::find($otp->id)->delete();
                    }
                }

                $job_items=DB::table('job_items')->where('candidate_id',$candidate_id)->get();

                if(count($job_items)>0)
                {
                    $tables[]='job_items';

                    if(array_key_exists('Candidate',$module_all))
                    {
                        array_push($module_all['Candidate'],'job_items');
                    }
                    else
                    {
                        $module_all['Candidate']=['job_items'];
                    }

                    $tables[]='jobs';

                    if(array_key_exists('Candidate',$module_all))
                    {
                        array_push($module_all['Candidate'],'jobs');
                    }
                    else
                    {
                        $module_all['Candidate']=['jobs'];
                    }

                    foreach($job_items as $job)
                    {
                      JobItem::find($job->id)->delete();

                      Job::find($job->job_id)->delete();
                    }
                }

                $job_sla_items=DB::table('job_sla_items')->where('candidate_id',$candidate_id)->get();

                if(count($job_sla_items)>0)
                {
                    $tables[]='job_sla_items';

                    if(array_key_exists('Candidate',$module_all))
                    {
                        array_push($module_all['Candidate'],'job_sla_items');
                    }
                    else
                    {
                        $module_all['Candidate']=['job_sla_items'];
                    }

                    foreach($job_sla_items as $job)
                    {
                      JobSlaItem::find($job->id)->delete();
                    }
                }

                $tasks=DB::table('tasks')->where('candidate_id',$candidate_id)->get();

                if(count($tasks)>0)
                {
                    $modules[]='Task';

                    $tables[]='tasks';

                    if(array_key_exists('Task',$module_all))
                    {
                        array_push($module_all['Task'],'tasks');
                    }
                    else
                    {
                        $module_all['Task']=['tasks'];
                    }

                    foreach($tasks as $task)
                    {
                      Task::find($task->id)->delete();
                    }
                }

                $tasks=DB::table('task_assignments')->where('candidate_id',$candidate_id)->get();

                if(count($tasks)>0)
                {
                    $tables[]='task_assignments';

                    if(array_key_exists('Task',$module_all))
                    {
                        array_push($module_all['Task'],'task_assignments');
                    }
                    else
                    {
                        $module_all['Task']=['task_assignments'];
                    }

                    foreach($tasks as $task)
                    {
                      TaskAssignment::find($task->id)->delete();
                    }
                }

                $insufficiency_attach = DB::table('insufficiency_attachments')->where('candidate_id',$candidate_id)->get();

                if(count($insufficiency_attach)>0)
                {
                    $tables[]='insufficiency_attachments';

                    if(array_key_exists('Candidate',$module_all))
                    {
                        array_push($module_all['Candidate'],'insufficiency_attachments');
                    }
                    else
                    {
                        $module_all['Candidate']=['insufficiency_attachments'];
                    }

                    foreach($insufficiency_attach as $insuff)
                    {

                      if($insuff->file_platform=='web')
                      {
                          $path = public_path('/uploads/raise-insuff/');

                          if(File::exists($path.$insuff->file_name))
                          {
                            File::delete($path.$insuff->file_name);
                          }

                          $path = public_path('/uploads/clear-insuff/');

                          if(File::exists($path.$insuff->file_name))
                          {
                            File::delete($path.$insuff->file_name);
                          }
                      }

                      DB::table('insufficiency_attachments')->where('id',$insuff->id)->delete();

                    }
                }

                $jaf_files = DB::table('jaf_files')->where('candidate_id',$candidate_id)->get();

                if(count($jaf_files)>0)
                {
                    $tables[]='jaf_files';

                    if(array_key_exists('Candidate',$module_all))
                    {
                        array_push($module_all['Candidate'],'jaf_files');
                    }
                    else
                    {
                        $module_all['Candidate']=['jaf_files'];
                    }

                    foreach($jaf_files as $file)
                    {
                      $path = public_path('/uploads/jaf_details/');

                      if(File::exists($path.$file->file_name))
                      {
                        File::delete($path.$file->file_name);
                      }

                      DB::table('jaf_files')->where('id',$file->id)->delete();
                    }
                }

                $jaf_form_data=DB::table('jaf_form_data')->where('candidate_id',$candidate_id)->get();

                if(count($jaf_form_data)>0)
                {
                    $tables[]='jaf_form_data';

                    if(array_key_exists('Candidate',$module_all))
                    {
                        array_push($module_all['Candidate'],'jaf_form_data');
                    }
                    else
                    {
                        $module_all['Candidate']=['jaf_form_data'];
                    }

                    foreach($jaf_form_data as $key => $jaf)
                    {
                      $jaf_additional_charges = DB::table('jaf_additional_charge_attachments')->where('jaf_id',$jaf->id)->get();

                      if(count($jaf_additional_charges)>0)
                      {
                          if($key==0)
                          {
                            $tables[]='jaf_additional_charge_attachments';

                            if(array_key_exists('Candidate',$module_all))
                            {
                                array_push($module_all['Candidate'],'jaf_additional_charge_attachments');
                            }
                            else
                            {
                                $module_all['Candidate']=['jaf_additional_charge_attachments'];
                            }
                          }

                          foreach($jaf_additional_charges as $attach)
                          {
                            $path = public_path('/uploads/jaf/additional-charge/');

                            if(File::exists($path.$attach->file_name))
                            {
                              File::delete($path.$attach->file_name);
                            }

                            DB::table('jaf_additional_charge_attachments')->where('id',$attach->id)->delete();

                          }
                      }

                      JafFormData::find($jaf->id)->delete();
                    }
                }

                $jaf_attach=DB::table('jaf_item_attachments')->where('candidate_id',$candidate_id)->get();

                if(count($jaf_attach)>0)
                {
                    $tables[]='jaf_item_attachments';

                    if(array_key_exists('Candidate',$module_all))
                    {
                        array_push($module_all['Candidate'],'jaf_item_attachments');
                    }
                    else
                    {
                        $module_all['Candidate']=['jaf_item_attachments'];
                    }

                    foreach($jaf_attach as $attach)
                    {
                      $path = public_path('/uploads/jaf-files/');

                      if(File::exists($path.$attach->file_name))
                      {
                        File::delete($path.$attach->file_name);
                      }

                      DB::table('jaf_item_attachments')->where('id',$attach->id)->delete();
                    }
                }

                $reports=DB::table('reports')->where('candidate_id',$candidate_id)->get();

                if(count($reports)>0)
                {
                    $modules[]='Report';

                    $tables[]='reports';

                    if(array_key_exists('Report',$module_all))
                    {
                        array_push($module_all['Report'],'reports');
                    }
                    else
                    {
                        $module_all['Report']=['reports'];
                    }

                    foreach($reports as $report)
                    {
                      Report::find($report->id)->delete();
                    }
                }

                $report_items=DB::table('report_items')->where('candidate_id',$candidate_id)->get();

                if(count($report_items)>0)
                {
                    $tables[]='report_items';

                    if(array_key_exists('Report',$module_all))
                    {
                        array_push($module_all['Report'],'report_items');
                    }
                    else
                    {
                        $module_all['Report']=['report_items'];
                    }

                    foreach($report_items as $key => $report)
                    {

                      $report_attach=DB::table('report_item_attachments')->where('report_item_id',$report->id)->get();

                      if(count($report_attach)>0)
                      {
                          if($key==0)
                          {
                            $tables[]='report_item_attachments';

                            if(array_key_exists('Report',$module_all))
                            {
                                array_push($module_all['Report'],'report_item_attachments');
                            }
                            else
                            {
                                $module_all['Report']=['report_item_attachments'];
                            }
                          }

                          foreach($report_attach as $attach)
                          {
                            $path = public_path('/uploads/report-files/');

                            if(File::exists($path.$attach->file_name))
                            {
                              File::delete($path.$attach->file_name);
                            }

                            DB::table('report_item_attachments')->where('id',$attach->id)->delete();
                          }
                      }

                      ReportItem::find($report->id)->delete();
                    }
                }

                $tables[]='users';

                if(array_key_exists('Candidate',$module_all))
                {
                    array_push($module_all['Candidate'],'users');
                }
                else
                {
                    $module_all['Candidate']=['users'];
                }

                User::find($candidate_id)->delete();

                DB::table('purge_data_logs')->insert([
                    'parent_id' => $candidate->parent_id,
                    'business_id' => $candidate->business_id,
                    'user_id' => $candidate->id,
                    'display_id' => $candidate->display_id,
                    'name' => $candidate->name,
                    'modules' => json_encode($modules),
                    'tables' => json_encode($tables),
                    'module_all' => json_encode($module_all),
                    'module_type' => 'account-deleted',
                    'user_type' => 'candidate',
                    'created_by'  => $user_id,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
                
                $is_deleted=TRUE;
              
                //return result 
                if($is_deleted){   
                    DB::commit();
                    return response()->json([
                    'status'=>'ok',
                    'message' => 'deleted',                
                    ], 200);
                }
                else{
                    return response()->json([
                    'status' =>'no',
                    ], 200);
                }
            }
            else{   
                return response()->json([
                    'status' =>'no',
                    ], 200);
            }
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        }
    }
       


    public function show($id)
    {
        $candidate_id = base64_decode($id);
      	//
        // dd($candidate_id);
        //get BGV filled details
        $query = DB::table('users as u')
        ->select('u.*','j.sla_id','j.jaf_status','j.job_id','j.candidate_id','j.created_at','j.filled_at','j.filled_by','j.is_insuff','insuff_created_at')      
        ->join('job_items as j','j.candidate_id','=','u.id')        
        ->where(['u.user_type'=>'candidate','is_deleted'=>'0','u.id'=>$candidate_id ,'j.jaf_status'=>'filled'])->first();
      // dd($query);
        
      $candidate = DB::table('users as u')
        ->select('u.id','u.client_emp_code','u.entity_code','u.display_id','u.first_name','u.middle_name','u.last_name','u.name','u.email','u.phone','u.phone_code','u.phone_iso','u.created_by','u.created_at','u.dob','u.aadhar_number','u.father_name','u.gender')
        ->where(['u.id'=>$candidate_id])
        ->first();

       //get insuff details
        $insuffs = DB::table('users as u')
        ->select('u.*','j.sla_id','j.jaf_status','j.job_id','jd.service_id', 'j.candidate_id','j.created_at','j.filled_at','j.filled_by','j.insuff_created_by','insuff_created_at')      
        ->join('job_items as j','j.candidate_id','=','u.id')    
        ->join('jaf_form_data as jd','jd.candidate_id','=','u.id')    
        ->where(['u.user_type'=>'candidate','is_deleted'=>'0','u.id'=>$candidate_id ,'j.jaf_status'=>'filled','j.is_insuff'=>'1','jd.is_insufficiency'=>'1'])
        ->groupBy('jd.service_id')
        ->get();

         $sla_items = DB::select("SELECT sla_id, GROUP_CONCAT(DISTINCT service_id) AS alot_services FROM `job_sla_items` WHERE candidate_id = $candidate_id");

         $insuff_data=DB::table('insufficiency_logs as v')
                    ->select('v.*','j.jaf_status','s.verification_type')
                    ->join('jaf_form_data as jd','jd.id','=','v.jaf_form_data_id')
                    ->join('job_items as j','j.id','=','jd.job_item_id')
                    ->join('services as s','s.id','=','v.service_id')
                    ->where(['v.candidate_id'=>$candidate_id,'j.jaf_status'=>'filled'])
                    ->orderBy('v.id','desc')
                    ->get();
        // dd($sla_items);
        // $services = DB::table('services')
        // ->select('name','id')
        // ->where(['status'=>'1'])
        // ->get();

        // $array_result = [];

        // foreach ($insuffs as $key => $value) {

        //    $insuff = $value->
        //     $array_result[] = ['insuff'=>$insuff]; 
        //         // 
        // }
           // dd($insuff_count);
        $users = DB::table('users as u')->select('u.id','u.name')->get();
      
        $report = DB::table('reports')->where(['candidate_id'=>$candidate_id,'status'=>'completed'])->first();
      // dd($report);
      if ($report==NULL) {
        $report= '';
      }

      $candidate_hold_logs=DB::table('candidate_hold_status_logs')
                            ->where('candidate_id',$candidate_id)
                            ->orderBy('id','desc')
                            ->get();

        return view('clients.candidates.show',compact('candidate','users','query','report','insuffs','sla_items','insuff_data','candidate_hold_logs'));
    }

    public function notes($id)
    {
      $candidate_id = base64_decode($id);

      $candidate = DB::table('users as u')
        ->select('u.id','u.first_name','u.middle_name','u.last_name','u.name','u.email','u.phone','u.phone_code','u.phone_iso','u.created_by','u.created_at','u.client_emp_code','u.entity_code','u.display_id','u.dob','u.aadhar_number','u.father_name','u.gender')
        ->where(['u.id'=>$candidate_id])
        ->first();

      $sla_items = DB::select("SELECT sla_id, GROUP_CONCAT(DISTINCT service_id) AS alot_services FROM `job_sla_items` WHERE candidate_id = $candidate_id");


      $insuff_data=DB::table('insufficiency_logs as v')
      ->select('v.*','j.jaf_status','s.verification_type')
      ->join('jaf_form_data as jd','jd.id','=','v.jaf_form_data_id')
      ->join('job_items as j','j.id','=','jd.job_item_id')
      ->join('services as s','s.id','=','v.service_id')
      ->where(['v.candidate_id'=>$candidate_id,'j.jaf_status'=>'filled'])
      ->orderBy('v.id','desc')
      ->get();

      
      $report = DB::table('reports')->where(['candidate_id'=>$candidate_id,'status'=>'completed'])->first();
      if ($report==NULL) {
        $report= '';
      }
      return view('clients.candidates.notes',compact('candidate','sla_items','report','insuff_data'));
    }


    /**
     * Get the candidates.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getCandidatesList(Request $request)
    {
        $business_id = $request->input('customer_id');

        $candidates = DB::table('users')
                ->select('id','first_name','middle_name','last_name','phone')
                ->where(['business_id'=>$business_id,'user_type'=>'candidate'])
                ->get();
        
        return response()->json([
            'success'   =>true,
            'data'      =>$candidates 
        ]);

    }


    /**
     * Get the Mix sla's items.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getMixSlaItemList(Request $request)
    {
        $parent_id=Auth::user()->parent_id;
        $sla_id = $request->input('sla_id');
        $slaItems =[];
        $existItem=[];

        try
        {
          $parent_user = DB::table('users as u')
                        ->select('ub.company_name')
                        ->join('user_businesses as ub','u.business_id','=','ub.business_id')
                        ->where(['u.id'=>$parent_id])
                        ->first();
          $sla_items = DB::table('customer_sla_items as sla')
                  ->select('sla.id as sla_item_id','s.id as service_id','s.name as service_name','s.verification_type')
                  ->join('services as s','s.id','=','sla.service_id')
                  ->where(['sla.sla_id'=>$sla_id])
                  ->whereNotIn('s.name',['GSTIN'])
                  ->get();

          $slaItems = (array) json_decode(json_encode($sla_items),true);
          // dd($slaItems);
          foreach($slaItems as $item){
                  $existItem[]= $item['service_id'];
          }       

          $all_services = DB::table('services as s')
                          ->select('s.id as service_id','s.name as service_name','s.verification_type')
                          ->join('service_form_inputs as si','s.id','=','si.service_id')
                          ->where('s.status','1')
                          ->where('s.business_id',NULL)
                          ->whereNotIn('s.type_name',['gstin'])
                          ->orwhere('s.business_id',$parent_id)
                          ->groupBy('si.service_id')
                          ->get();
          $all_service_items = (array) json_decode(json_encode($all_services),true);

          $accumulated_list = [];

          
          // dd($all_service_items);
          foreach($all_services as $item){
              $checked_atatus = FALSE;
              if(in_array($item->service_id,$existItem)){
                  $checked_atatus = TRUE;
              }            
              $accumulated_list[] = ['checked_atatus'=>$checked_atatus,'service_id'=>$item->service_id,'service_name'=>$item->service_name];
          }
          
          return response()->json([
              'success'   =>true,
              'data'      =>$accumulated_list,
              'company_name' =>  $parent_user->company_name
          ]);
        }
        catch (\Exception $e) {
          // something went wrong
          return $e;
        }

    }

     //BGV Form show
     public function jafForm($case_id,$c_id)
     {
         $id = base64_decode($case_id);
         $user_id = Auth::user()->id;
         $candidate_id =base64_decode($c_id);

         // dd($id);
         $hold_data = DB::table('candidate_hold_statuses')       
         ->where(['candidate_id'=>$candidate_id])
         ->where('hold_remove_by','=',null)
         ->first();

         if($hold_data!=NULL)
         {
          return redirect()->route('/my/candidates'); 
         }
         $job_items=DB::table('job_items')->where('id',$id)->first();
          if($job_items!=NULL)
          {
            $status=$job_items->jaf_status;
            if($status=='filled')
            return redirect()->route('/my/candidates/jaf-fill');
          }
         
         $jaf_form_data_check = DB::table('jaf_form_data')->where('job_item_id',$id)->count();
         $job_sla_items = DB::table('job_sla_items')->where('jaf_send_to','coc')->where('candidate_id',$candidate_id)->get();
        //  dd($job_sla_items);
         $input_data = [];
         //
       if ( $jaf_form_data_check == 0) {
         
         foreach($job_sla_items as $service){
           
           // service-input-label-0-1-1
           // echo $request->input('service-input-label-0-1-1');
           // die('ok');
           $input_items = DB::table('service_form_inputs as sfi')
                       ->select('sfi.*')            
                       ->where(['sfi.service_id'=>$service->service_id,'status'=>1])
                       ->get();
           $numbers_of_items = $service->number_of_verifications;
          
           for($j=1; $j<=$numbers_of_items; $j++){
             
             $i=0;
           
             $jaf_form_data = [
                               'business_id' => $service->business_id,
                               'job_id'      => $service->job_id,
                               'job_item_id' => $service->job_item_id,
                               'service_id'  => $service->service_id,
                               'candidate_id' => $service->candidate_id,
                               'check_item_number'=>$j,
                               'sla_id'      =>$service->sla_id,
                               'created_by'   => Auth::user()->id,
                               'created_at'   => date('Y-m-d H:i:s')];
   
            $jaf_data= DB::table('jaf_form_data')->insert($jaf_form_data);
            
           }
         }
       }
         // dd($jaf_data);
         $candidate = DB::table('users as u')
         ->select('u.id','u.business_id','u.client_emp_code','u.first_name','u.dob','u.father_name','u.middle_name','u.last_name','u.gender','u.name','u.email','u.phone','u.phone_code','j.created_at','j.sla_id','u.user_type','u.digital_signature','u.aadhar_number','u.entity_code','u.display_id')  
         ->leftjoin('job_items as j','j.candidate_id','=','u.id')
         ->where(['u.id'=>$candidate_id]) 
         ->first(); 
 
         //$jaf_items= DB::select("SELECT DISTINCT jf.id, jf.*,jsi.jaf_send_to,s.name as service_name,s.id as service_id FROM jaf_form_data as jf JOIN services as s ON s.id=jf.service_id JOIN job_sla_items as jsi ON jsi.job_id=jf.job_id WHERE jf.job_item_id=$id");

         $jaf_items = JafFormData::from('jaf_form_data as jf')
                      ->Distinct('jf.id')
                      ->select('jf.id', 'jf.*','jsi.jaf_send_to','s.name as service_name','s.id as service_id','s.type_name')
                      ->join('services as s','s.id','=','jf.service_id')
                      ->join('job_sla_items as jsi','jsi.job_id','=','jf.job_id')
                      ->where('jf.job_item_id',$id)
                      ->get();
 
         // $jaf_items = Db::table('jaf_form_data as jf')
         // ->select('jf.*','jsi.jaf_send_to','s.name as service_name','s.id as service_id')  
         // ->join('services as s','s.id','=','jf.service_id')
         // ->join('job_sla_items as jsi','jsi.job_id','=','jf.job_id' )
         // ->where(['jf.job_item_id'=>$id])
         // ->groupBy('jf.id') 
         // ->get(); 
 
         // dd($jaf_items); 
         $checks = UserCheck::where('user_id',$user_id)->get();
 
         // $new_jaf_form_data = DB::table('jaf_form_data')->where('job_id',$id)->get();
         // dd($new_jaf_form_data);
         return view('clients.candidates.jaf-form',compact('candidate','jaf_items','checks'));
     }

     //BGV Form data save
    public function jafSave(Request $request)
    {
      // dd($request->all()); 
      $case_id      = base64_decode($request->input('case_id'));
      $candidate_id = $request->input('candidate_id');
      $business_id  = $request->input('business_id');

      $candidate=base64_encode($request->input('candidate_id'));

      $parent_id  = Auth::user()->parent_id;

      $user_id=Auth::user()->id;

      $admin_data=DB::table('users')->where('id',$parent_id)->first();
      $super_parent_id=$admin_data->parent_id;

        if ($request->type == 'formtype') {
          // DB::beginTransaction();
          // try{
            $hold_data = DB::table('candidate_hold_statuses')       
                        ->where(['candidate_id'=>$candidate_id])
                        ->where('hold_remove_by','=',null)
                        ->first();

            if($hold_data!=NULL){
              $users_d=DB::table('users as u')
                      ->select('u.*','ub.company_name')
                      ->join('user_businesses as ub','u.business_id','=','ub.business_id')
                      ->where(['u.id'=>$hold_data->hold_by])
                      ->first();
              $name= $users_d->name.' ('.$users_d->company_name.')';
              return response()->json([
                'success' =>true,
                'status'  =>'hold',
                'hold_by' => $name,
                'candidate_id'=>$candidate,
              ]);
            }
            else{
              $job_data=DB::table('job_items')->where(['id'=>$case_id])->first();
              if($job_data->jaf_status!='filled')
              {
                $jaf_items = DB::table('jaf_form_data')->where(['job_item_id'=>$case_id])->get();
                // dd($case_id);
                $input_data = [];
                
                foreach($jaf_items as $service){
                  
                  // service-input-label-0-1-1
                  // echo $request->input('service-input-label-0-1-1');
                  // die('ok');
                  $input_items = DB::table('service_form_inputs as sfi')
                              ->select('sfi.*')            
                              ->where(['sfi.service_id'=>$service->service_id,'status'=>1])
                              ->get();
                  // $j = 1;
                  // $job_item_data = DB::table('job_sla_items')->where(['id'=>$case_id])->first();
                  // $numbers_of_items = $job_item_data->number_of_verifications;
                  // if($numbers_of_items > 1){
                  //   $j++;
                  // }
                    $reference_type = NULL;
                    $input_data = [];
                    $i=0;
                    foreach($input_items as $input){
                      if($input->service_id==17)
                        {
                            if($input->reference_type==NULL && !(stripos($input->label_name,'Mode of Verification')!==false || stripos($input->label_name,'Remarks')!==false))
                            { 
                              $input_data[] = [
                                $request->input('service-input-label-'.$service->id.'-'.$i)=>$request->input('service-input-value-'.$service->id.'-'.$i),
                                'is_report_output'=>$input->is_report_output 
                              ];

                              if(stripos($request->input('service-input-label-'.$service->id.'-'.$i),'Reference Type (Personal / Professional)')!==false)
                              {
                                  $reference_type = $request->input('service-input-value-'.$service->id.'-'.$i);
                              }

                            }
                        }
                        else
                        {
                            $input_data[] = [
                              $request->input('service-input-label-'.$service->id.'-'.$i)=>$request->input('service-input-value-'.$service->id.'-'.$i),
                              'is_report_output'=>$input->is_report_output 
                            ];
                        }
                        $i++;
                    }
                  
                    $jaf_data = json_encode($input_data);
                    
                    //insuff
                    $is_insufficiency = 0;
                    if($request->has('insufficiency-'.$service->id)){
                      $is_insufficiency = 1;
                    }
                    // $insufficiency_notes = $request->input('insufficiency-notes-'.$service->id.'-'.$i);
      
                    $insufficiency_notes = $request->input('insufficiency-notes-'.$service->id);
      
                    $address_type = $request->input('address-type-'.$service->id);
      
                    $jaf_form_data = 
                          [
                          
                            'form_data'       => $jaf_data,
                            'form_data_all'   => json_encode($request->all()),
                            'is_insufficiency'=>$is_insufficiency,
                            'insufficiency_notes'=>$is_insufficiency==1?$insufficiency_notes:NULL,
                            'address_type'  =>$address_type,
                            'reference_type'  =>$reference_type,
                            'created_by'    => Auth::user()->id,
                            'updated_at'    => date('Y-m-d H:i:s')
                          ];
      
                    DB::table('jaf_form_data')->where(['id'=>$service->id])->update($jaf_form_data);
      
      
                    DB::table('job_items')->where(['id'=>$case_id])->update(['jaf_status'=>'draft','filled_by_type'=>'customer','filled_by'=>Auth::user()->id,'filled_at'=>date('Y-m-d H:i:s')]);
      
                    if($is_insufficiency==1)
                    {
                        $insuff_data=DB::table('verification_insufficiency')->where(['jaf_form_data_id'=>$service->id,'activity_type'=>'jaf-save','status'=>'raised'])->first();
      
                        if($insuff_data!=NULL)
                        {
                          $ver_insuff_data=[
                            'notes'=>$insufficiency_notes,
                            'created_by'   => Auth::user()->id,
                            'updated_at'=> date('Y-m-d H:i:s'),
                          ]; 
                          DB::table('verification_insufficiency')->where(['jaf_form_data_id'=>$service->id,'activity_type'=>'jaf-save','status'=>'raised'])->update($ver_insuff_data);
                        }
                        else{
                          $ver_insuff_data=
                          [
                            'parent_id'   => $super_parent_id,
                            'business_id' => $parent_id,
                            'coc_id' => $service->business_id,
                            'candidate_id' => $service->candidate_id,
                            'jaf_form_data_id'  => $service->id,
                            'service_id'  => $service->service_id,
                            'item_number' => $service->check_item_number,
                            'created_by'   => Auth::user()->id,
                            'created_at'   => date('Y-m-d H:i:s'),
                            'activity_type'=> 'jaf-save',
                            'status' => 'raised',
                            'notes' => $insufficiency_notes,
                            'updated_at' => date('Y-m-d H:i:s')
                          ];
      
                          DB::table('verification_insufficiency')->insert($ver_insuff_data);
                        }
                    }
                    else if($is_insufficiency==0)
                    {
                      $insuff_data=DB::table('verification_insufficiency')->where(['jaf_form_data_id'=>$service->id,'activity_type'=>'jaf-save','status'=>'raised'])->delete();
                    } 
                }
      
                // if ($files = $request->file('digital_signature')) 
                // {
                //   $file=$request->file('digital_signature');
                //   $file_name=date('Yhis').'-'.'sign'.'.png';
                //   $destinationPath = public_path('uploads/signature/'); 
                //   $dsext = $request->file('digital_signature')->getClientOriginalExtension();
                //   // $files->move($destinationPath, $dsImage);
                //   if($dsext=='png' || $dsext=='jpg' || $dsext=='jpeg')
                //   {
                //     $candidate=DB::table('users')->select('digital_signature')->where('id',$candidate_id)->first();
      
                //     $candidate_img=$candidate->digital_signature;
                //     // $user_img = Auth::user()->digital_signature;
                //     if($candidate_img!=NULL || $candidate_img!='')
                //     {
                //       if(File::exists(public_path().'/uploads/signatures/'.$candidate_img))
                //       {
                //         File::delete(public_path().'/uploads/signatures/'.$candidate_img);
                //       } 
                //     }
                //     $mask = Image::make($file)
                //               ->orientate() // it's better to set a tolerance for trim()
                //               ->invert(); // invert it to use as a ma
                              
                //     $new_image = Image::canvas($mask->width(), $mask->height(), '#000000')
                //                 ->mask($mask)
                //                 ->save('uploads/signatures/'.$file_name);
      
                //     DB::table('users')->where(['id'=>$candidate_id])->update([
                //       'digital_signature' => $file_name
                //     ]);
                //   }
                // }
      
                  // DB::commit();
                return response()->json([
                  'success' =>true,
                  'status'  =>'no',
                  'errors'  =>[]
                ]);
              }
              else{
                  $users_d=DB::table('users as u')
                        ->select('u.*','ub.company_name')
                        ->join('user_businesses as ub','u.business_id','=','ub.business_id')
                        ->where('u.id',$job_data->filled_by)
                        ->first();
                        $name= $users_d->name.' ('.$users_d->company_name.')';
                    return response()->json([
                      'success' =>true,
                      'status'  =>'filled',
                      'filled_by' => $name,
                      'candidate_id'=>$candidate,
                    ]);
              }
            }
          // }
          // catch (\Exception $e) {
          //   DB::rollback();
          //   // something went wrong
          //   return response()->json(['error_code'=>$e->getCode(),'message'=>$e]);
          //   return $e;
          // }    
        }
        else {
          // DB::beginTransaction();
          // try
          // {
            $job_item =DB::table('job_items')->where(['candidate_id'=>$candidate_id,'jaf_status'=>'filled'])->first();
            if($job_item!=NULL)
            {
              $users_d=DB::table('users as u')
              ->select('u.*','ub.company_name')
              ->join('user_businesses as ub','u.business_id','=','ub.business_id')
              ->where('u.id',$job_item->filled_by)
              ->first();
              $name= $users_d->name.' ('.$users_d->company_name.')';
              return response()->json([
                'success' =>true,
                'status'  =>'filled',
                'filled_by' => $name,
                'candidate_id'=>$candidate,
              ]);
            }
            else
            {
                // $this->validate($request, [
                //   'digital_signature' => 'mimes:png,jpeg,jpg,gif,svg|max:2048',
                // ]);

                $rules=[
                  'digital_signature' => 'mimes:png,jpeg,jpg,gif,svg|max:2048',
                ];

                $validator = Validator::make($request->all(), $rules);
            
                if ($validator->fails()){
                      return response()->json([
                          'fail' => true,
                          'errors' => $validator->errors(),
                          'error_type'=>'validation'
                      ]);
                }

                if ($files = $request->file('digital_signature')) 
                {
                    $s3_config = S3ConfigTrait::s3Config();
                    $digital_signature_file_platform  = 'web';
                    $file=$request->file('digital_signature');
                    $filename=$file->getclientOriginalName();
                    $file_name=date('Yhis').$filename;
                    $destinationPath = public_path('uploads/signatures/'); 
                    $dsext = $request->file('digital_signature')->getClientOriginalExtension();
                    // $files->move($destinationPath, $dsImage);
                    
                      $candidate=DB::table('users')->select('digital_signature','digital_signature_file_platform')->where('id',$candidate_id)->first();

                      $candidate_img=$candidate->digital_signature;
                      // $user_img = Auth::user()->digital_signature;
                      if($candidate_img!=NULL && $candidate_img!='')
                      {
                        if(File::exists(public_path().'/uploads/signatures/'.$candidate_img))
                        {
                          File::delete(public_path().'/uploads/signatures/'.$candidate_img);
                        } 
                      }

                      if($s3_config!=NULL)
                      {
                          $digital_signature_file_platform = 's3';

                          $path = 'uploads/signatures/';

                          if(!Storage::disk('s3')->exists($path))
                          {
                              Storage::disk('s3')->makeDirectory($path,0777, true, true);
                          }

                          Storage::disk('s3')->put($path.$file_name, file_get_contents($files));
                      }
                      else
                      {
                        $request->digital_signature->move($destinationPath,$file_name);
                      }
                      // $mask = Image::make($file)
                      //           ->orientate() // it's better to set a tolerance for trim()
                      //           ->invert(); // invert it to use as a ma
                                
                      // $new_image = Image::canvas($mask->width(), $mask->height(), '#000000')
                      //             ->mask($mask)
                      //             ->save('uploads/signatures/'.$file_name);

                      DB::table('users')->where(['id'=>$candidate_id])->update([
                        'digital_signature' => $file_name,
                        'digital_signature_file_platform' => $digital_signature_file_platform
                      ]);
                    

                  
                  
                }

                $jaf_items_data = DB::table('jaf_form_data')->where(['job_item_id'=>$case_id])->get();

                // Validation for address
                foreach($jaf_items_data as $service)
                {
                  //check ignore
                  $is_check_ignore = 0;
                  if($request->has('check_ignore-'.$service->id)){
                   $is_check_ignore = 1;
                  }

                  if($is_check_ignore==1){
                    if($service->service_id==1)
                    {
                      $input_items = DB::table('service_form_inputs as sfi')
                      ->select('sfi.*')            
                      ->where(['sfi.service_id'=>$service->service_id])
                      ->get();
                      $data=false;
                      $i=2;
                      foreach($input_items as $item)
                      {
                        if($request->input('service-input-value-'.$service->id.'-'.$i)=='' || $request->input('service-input-value-'.$service->id.'-'.$i)==NULL)
                          {
                              $data=true;
                          }
                          else
                          {
                              $data = false;
                              break;
                          }
                          $i++;
                      }
      
                      if($data)
                      {
                          $rules=[
                            'address-type-'.$service->id => 'required',
                          ];
      
                          $custom=[
                            'address-type-'.$service->id.'.required' => 'Address Type Field is required'
                          ];
              
                          $validator = Validator::make($request->all(), $rules,$custom);
                      
                          if ($validator->fails()){
                                return response()->json([
                                    'fail' => true,
                                    'errors' => $validator->errors(),
                                    'error_type'=>'validation'
                                ]);
                          }
                      }
                      
                    }
                    else
                    {
                      break;
                    }
                  }
                    
                }

                // Validation for Employment & Educational

                foreach($jaf_items_data as $service)
                {
                  // Employment
                  if($service->service_id==10)
                  {
                     $fake_employment = DB::table('fake_employment_lists')->where(DB::raw('lower(company_name)'), strtolower($request->input('service-input-value-'.$service->id.'-'.'2')))->first();

                     if($fake_employment!=NULL)
                     {
                          return response()->json([
                            'fail' => true,
                            'errors' => ['service-input-value-'.$service->id.'-'.'2'=>'System found, This company name seems fake.'],
                            'error_type'=>'validation'
                        ]);
                     }
                    
                  }
                  // Educational
                  else if($service->service_id==11)
                  {
                    $fake_educational = DB::table('fake_educational_lists')->where(DB::raw('lower(board_or_university_name)'),strtolower($request->input('service-input-value-'.$service->id.'-'.'2')))->first();
                    if($fake_educational!=NULL)
                    {
                          return response()->json([
                            'fail' => true,
                            'errors' => ['service-input-value-'.$service->id.'-'.'2'=>'System found, This university / board name seems fake.'],
                            'error_type'=>'validation'
                        ]);
                    }
                  }
                }

                foreach($jaf_items_data as $service)
                {
                    // if($service->service_id==1)
                    // {
                    //   $rules=[
                    //     'address-type-'.$service->id => 'required',
                    //   ];

                    //   $custom=[
                    //     'address-type-'.$service->id.'.required' => 'Address Type Field is required'
                    //   ];
          
                    //   $validator = Validator::make($request->all(), $rules,$custom);
                  
                    //   if ($validator->fails()){
                    //         return response()->json([
                    //             'fail' => true,
                    //             'errors' => $validator->errors(),
                    //             'error_type'=>'validation'
                    //         ]);
                    //   }
                      
                      
                    // }
                    $input_items = DB::table('service_form_inputs as sfi')
                                    ->select('sfi.*')            
                                    ->where(['sfi.service_id'=>$service->service_id,'status'=>1])
                                    ->get();

                    // if($service->form_data==NULL)
                    // {
                        // $j = 1;
                        // $job_item_data = DB::table('job_sla_items')->where(['id'=>$case_id])->first();
                        // $numbers_of_items = $job_item_data->number_of_verifications;
                        // if($numbers_of_items > 1){
                        //   $j++;
                        // }


                        $input_data = [];
                        $reference_type = NULL;
                        $i=0;
                        foreach($input_items as $input){
                          if($input->service_id==17)
                          {
                              if($input->reference_type==NULL && !(stripos($input->label_name,'Mode of Verification')!==false || stripos($input->label_name,'Remarks')!==false))
                              { 
                                $input_data[] = [
                                  $request->input('service-input-label-'.$service->id.'-'.$i)=>$request->input('service-input-value-'.$service->id.'-'.$i),
                                  'is_report_output'=>$input->is_report_output 
                                ];
  
                                if(stripos($request->input('service-input-label-'.$service->id.'-'.$i),'Reference Type (Personal / Professional)')!==false)
                                {
                                    $reference_type = $request->input('service-input-value-'.$service->id.'-'.$i);
                                }
  
                              }
                          }
                          else
                          {
                              $input_data[] = [
                                $request->input('service-input-label-'.$service->id.'-'.$i)=>$request->input('service-input-value-'.$service->id.'-'.$i),
                                'is_report_output'=>$input->is_report_output 
                              ];
                          }
                          
                            $i++;
                        }
                      
                        $jaf_data = json_encode($input_data);

                        //insuff
                        $is_insufficiency = 0;
                        if($request->has('insufficiency-'.$service->id)){
                          $is_insufficiency = 1;
                        }

                        //check ignore
                        $is_check_ignore = 0;
                        if($request->has('check_ignore-'.$service->id)){
                          $is_check_ignore = 1;
                        }

                        $insufficiency_notes = $request->input('insufficiency-notes-'.$service->id);
      
                        $address_type = $request->input('address-type-'.$service->id);

                        $jaf_form_data = 
                        [           
                          'form_data'       => $jaf_data,
                          'form_data_all'   => json_encode($request->all()),
                          'is_insufficiency'=>$is_insufficiency,
                          'insufficiency_notes'=>$is_insufficiency==1?$insufficiency_notes:NULL,
                          'address_type'  =>$address_type,
                          'reference_type'  =>$reference_type,
                          'created_by'    => Auth::user()->id,
                          'is_filled' => '1',
                          'is_check_ignore' => $is_check_ignore,
                          'check_ignore_created_by' => $is_check_ignore==1 ? Auth::user()->id : NULL,
                          'check_ignore_created_at' => $is_check_ignore==1 ? date('Y-m-d H:i:s') : NULL,
                          'updated_at'    => date('Y-m-d H:i:s')
                        ];
        
                          DB::table('jaf_form_data')->where(['id'=>$service->id])->update($jaf_form_data);

                          // if($is_insufficiency==1)
                          // {
                          //     $insuff_data=DB::table('verification_insufficiency')->where(['jaf_form_data_id'=>$service->id,'activity_type'=>'jaf-save','status'=>'raised'])->first();
          
                          //     if($insuff_data!=NULL)
                          //     {
                          //       $ver_insuff_data=[
                          //         'notes'=>$insufficiency_notes,
                          //         'created_by'   => Auth::user()->id,
                          //         'updated_at'=> date('Y-m-d H:i:s'),
                          //       ]; 
                          //       DB::table('verification_insufficiency')->where(['jaf_form_data_id'=>$service->id,'activity_type'=>'jaf-save','status'=>'raised'])->update($ver_insuff_data);
                          //     }
                          //     else{
                          //       $ver_insuff_data=[
                          //         'parent_id'   => $super_parent_id,
                          //         'business_id' => $parent_id,
                          //         'coc_id' => $service->business_id,
                          //         'candidate_id' => $service->candidate_id,
                          //         'jaf_form_data_id'  => $service->id,
                          //         'service_id'  => $service->service_id,
                          //         'item_number' => $service->check_item_number,
                          //         'created_by'   => Auth::user()->id,
                          //         'created_at'   => date('Y-m-d H:i:s'),
                          //         'activity_type'=> 'jaf-save',
                          //         'status' => 'raised',
                          //         'notes' => $insufficiency_notes,
                          //       ];
            
                          //       DB::table('verification_insufficiency')->insert($ver_insuff_data);
                          //     }
          
                          // }
                          // else if($is_insufficiency==0)
                          // {
                          //   $insuff_data=DB::table('verification_insufficiency')->where(['jaf_form_data_id'=>$service->id,'activity_type'=>'jaf-save','status'=>'raised'])->delete();
                          // }

                          // $report_items = DB::table('report_items')->where(['jaf_id'=>$service->id])->first();

                          // if($report_items!=NULL)
                          // {
                          //   if($report_items->jaf_data==NULL)
                          //   {
                          //     $report_item_data=[];
                          //     if ($item->verification_status == 'success') {
                          //       $report_item_data = 
                          //       [ 
                          //         'jaf_data'      =>$jaf_data,
                          //         'reference_type'  =>$reference_type,
                          //         'created_at'    =>date('Y-m-d H:i:s')
                          //       ];
                          //     } 
                          //     else {
                          //       $report_item_data = 
                          //       [    
                          //         'jaf_data'      =>$jaf_data,
                          //         'reference_type'  =>$reference_type,
                          //         'is_report_output' => '0',
                          //         'created_at'    =>date('Y-m-d H:i:s')
                          //       ]; 
                          //     }
                          //     DB::table('report_items')->where(['id'=>$report_items->id])->update($report_item_data);
                          //   }
                            
                          // }
                          
                    // }

                    $reference_type = NULL;
                    $k=0;
                    foreach($input_items as $input)
                    {
                      if($input->service_id==17)
                      {
                        if(stripos($request->input('service-input-label-'.$service->id.'-'.$k),'Reference Type (Personal / Professional)')!==false)
                        {
                            $reference_type = $request->input('service-input-value-'.$service->id.'-'.$k);
                        }
                      }
                      $k++;
                    }
                    
                    $is_insufficiency = 0;
                    if($request->has('insufficiency-'.$service->id)){
                      $is_insufficiency = 1;
                    }

                    $insufficiency_notes = $request->input('insufficiency-notes-'.$service->id);
          
                    $address_type = $request->input('address-type-'.$service->id);

                    if($is_insufficiency==1)  
                    {
                      
                        $s3_config=NULL;
                        $c_file_platform = 'web';
                        $attach_on_select=[];
                        $allowedextension=['jpg','jpeg','png','gif','svg','pdf'];
                        $zipname="";
                        $zip_r=mt_rand(100,500);
                        if($request->hasFile('attachments-'.$service->id) && $request->file('attachments-'.$service->id) !="")
                        {
                            $filePath = public_path('/uploads/raise-insuff/'); 
                            $files= $request->file('attachments-'.$service->id);
                            foreach($files as $file)
                            {
                                    $extension = $file->getClientOriginalExtension();
          
                                    $check = in_array($extension,$allowedextension);
                                    $file_size = number_format(File::size($file) / 1048576, 2);

                                    if(!$check)
                                    {
                                        return response()->json([
                                          'fail' => true,
                                          'errors' => ['attachments-'.$service->id => 'Only jpg,jpeg,png,pdf are allowed !'],
                                          'error_type'=>'validation'
                                        ]);                        
                                    }

                                    if($file_size > 10)
                                    {
                                        return response()->json([
                                          'fail' => true,
                                          'error_type'=>'validation',
                                          'errors' => ['attachments-'.$service->id => 'The document size must be less than only 10mb Upload !'],
                                        ]);                        
                                    }
                            }
          
                            $zipname = 'raise-insuff-'.$zip_r.'-'.date('Ymdhis').'.zip';
                            $zip = new \ZipArchive();      
                            $zip->open(public_path().'/uploads/raise-insuff/'.$zipname, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
          
                            foreach($files as $file)
                            {
                                $r=mt_rand(100,500);
                                $file_data = $file->getClientOriginalName();
                                $tmp_data  = $candidate_id.'-'.$r.'-'.date('mdYHis').'-'.$file_data; 
                                $data = $file->move($filePath, $tmp_data);       
                                $attach_on_select[]=$tmp_data;
          
                                $path=public_path()."/uploads/raise-insuff/".$tmp_data;
                                $zip->addFile($path, '/raise-insuff/'.basename($path));  
                            }
          
                            $zip->close();
                        }

                        $s3_config = S3ConfigTrait::s3Config();

                        $path=public_path().'/uploads/raise-insuff/';

                        if($s3_config!=NULL && $zipname!='')
                        {
                          if(File::exists($path.$zipname))
                          {
                              $c_file_platform = 's3';

                              $s3filePath = 'uploads/raise-insuff/';
              
                              if(!Storage::disk('s3')->exists($s3filePath))
                              {
                                  Storage::disk('s3')->makeDirectory($s3filePath,0777, true, true);
                              }
              
                              $file = Helper::createFileObject($path.$zipname);
              
                              Storage::disk('s3')->put($s3filePath.$zipname,file_get_contents($file));

                              File::delete($path.$zipname);
                          }

                        }

                        if(File::exists($path.'tmp-files/'))
                        {
                            File::cleanDirectory($path.'tmp-files/');
                        }

                        $jaf_form_data = [

                          'is_insufficiency'=>$is_insufficiency,
                          'insufficiency_notes'=>$is_insufficiency==1?$insufficiency_notes:NULL,
                          'address_type'  =>$address_type,
                          'reference_type' => $reference_type,
                          'created_by'    => Auth::user()->id,
                          'is_filled' => '1',
                          'insuff_attachment' => $zipname!=""?$zipname:NULL,
                          'insuff_attachment_file_platform' => $c_file_platform,
                          'updated_at'    => date('Y-m-d H:i:s')];
        
                        DB::table('jaf_form_data')->where(['id'=>$service->id])->update($jaf_form_data);

                        $insuff_data=DB::table('verification_insufficiency')->where(['jaf_form_data_id'=>$service->id,'activity_type'=>'jaf-save','status'=>'raised'])->first();
                          // dd($insuff_data);
                        if($insuff_data!=NULL)
                        {
                          $ver_insuff_data=
                          [
                            'notes'=>$insufficiency_notes,
                            'attachment' => $zipname!=""?$zipname:NULL,
                            'attachment_file_platform' => $c_file_platform,
                            'created_by'   => Auth::user()->id,
                            'updated_at'=> date('Y-m-d H:i:s'),
                          ]; 
                          DB::table('verification_insufficiency')->where(['jaf_form_data_id'=>$service->id,'activity_type'=>'jaf-save','status'=>'raised'])->update($ver_insuff_data);
                          $ver_id=$insuff_data->id;
                        }
                        else{
                          $ver_insuff_data=[
                            'parent_id'   => $super_parent_id,
                            'business_id' => $parent_id,
                            'coc_id' => $service->business_id,
                            'candidate_id' => $service->candidate_id,
                            'jaf_form_data_id'  => $service->id,
                            'service_id'  => $service->service_id,
                            'item_number' => $service->check_item_number,
                            'created_by'   => Auth::user()->id,
                            'created_at'   => date('Y-m-d H:i:s'),
                            'activity_type'=> 'jaf-save',
                            'status' => 'raised',
                            'notes' => $insufficiency_notes,
                            'attachment' => $zipname!=""?$zipname:NULL,
                            'attachment_file_platform' => $c_file_platform,
                          ];
                        
                            $ver_id=DB::table('verification_insufficiency')->insertGetId($ver_insuff_data);
                        }
                            $insuff_log_data=[
                              'parent_id' => $super_parent_id,
                              'business_id' => $parent_id,
                              'coc_id' => $service->business_id,
                              'candidate_id' => $candidate_id,
                              'service_id'  => $service->service_id,
                              'jaf_form_data_id' => $service->id,
                              'item_number' => $service->check_item_number,
                              'activity_type'=> 'jaf-save',
                              'status'=>'raised',
                              'notes' => $insufficiency_notes,
                              'attachment' => $zipname!=""?$zipname:NULL,
                              'attachment_file_platform' => $c_file_platform,
                              'created_by'   => Auth::user()->id,
                              'created_at'   => date('Y-m-d H:i:s'),
                            ];
                      
                            DB::table('insufficiency_logs')->insert($insuff_log_data);
                      
                            $ver_insuff=DB::table('verification_insufficiency')->where(['id' => $ver_id,'status'=>'raised'])->first();

                            $candidates=DB::table('users as u')
                                        ->select('u.*','j.business_id as coc_id','j.id as jaf_id','v.created_at as insuff_date','v.created_by as insuff_by','v.item_number','v.notes','s.verification_type','s.name as service_name','v.business_id as cust_id','v.attachment','v.updated_at','v.updated_by','j.insufficiency_notes')
                                        ->join('jaf_form_data as j','u.id','=','j.candidate_id')
                                        ->join('verification_insufficiency as v','v.jaf_form_data_id','=','j.id')
                                        ->join('services as s','s.id','=','v.service_id')
                                        ->where(['u.user_type'=>'candidate','j.id'=>$service->id,'v.status'=>'raised','v.id'=>$ver_insuff->id])
                                        ->first();
                              // dd($candidates);
                                if($candidates!=NULL)
                                {
                                  // $client=DB::table('users')->where(['id'=>$candidates->coc_id])->first();

                                  // $name = $client->name;
                                  // $email = $client->email;
                                  // $msg= "Insufficiency Raised For Candidate";
                                  // $sender = DB::table('users')->where(['id'=>$candidates->parent_id])->first();
                                  // if($candidates->attachment!=NULL)
                                  // {
                                  //   $url = url('/').'/uploads/raise-insuff/'.$zipname;

                                  //   if($s3_config!=NULL)
                                  //   {
                                  //     $filePath = 'uploads/raise-insuff/';

                                  //     $disk = Storage::disk('s3');

                                  //     $command = $disk->getDriver()->getAdapter()->getClient()->getCommand('GetObject', [
                                  //         'Bucket'                     => Config::get('filesystems.disks.s3.bucket'),
                                  //         'Key'                        => $filePath.$zipname,
                                  //         'ResponseContentDisposition' => 'attachment;'//for download
                                  //     ]);

                                  //     $req = $disk->getDriver()->getAdapter()->getClient()->createPresignedRequest($command, '+10 minutes');

                                  //     $url = (string)$req->getUri();
                                  //   }

                                  //   $data  = array('name'=>$name,'email'=>$email,'msg'=>$msg,'link'=>$url,'candidate'=>$candidates,'sender'=>$sender);
                                  // }
                                  // else
                                  //     $data  = array('name'=>$name,'email'=>$email,'msg'=>$msg,'link'=>'','candidate'=>$candidates,'sender'=>$sender);

                                  // Mail::send(['html'=>'mails.insuff-notify'], $data, function($message) use($email,$name) {
                                  //   $message->to($email, $name)->subject
                                  //       ('Clobminds Pvt Ltd- Insufficiency Notification');
                                  //   $message->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
                                  // });
                                  // Notification for Insufficiency to Agency
                                    $sender = DB::table('users')->where(['id'=>$candidates->parent_id])->first();
                                    $clientname= Helper::user_details($candidates->business_id);
                                    $companyname=Helper::company_name($candidates->parent_id);
                                    $display_id=$candidates->display_id;
                                    $client_name=$clientname->name;
                                    $admin_email=$sender->email;
                                    $admin_name=$sender->name;
                                    $name = $sender->name;
                                    $email =  $clientname->email;
                                    $msg= "Insufficiency Adddressed by ".$client_name. " following are details: ";
                                    if($candidates->attachment!=NULL)
                                    {
                                      $url = url('/').'/uploads/raise-insuff/'.$zipname;

                                      if($s3_config!=NULL)
                                      {
                                        $filePath = 'uploads/raise-insuff/';

                                        $disk = Storage::disk('s3');

                                        $command = $disk->getDriver()->getAdapter()->getClient()->getCommand('GetObject', [
                                            'Bucket'                     => Config::get('filesystems.disks.s3.bucket'),
                                            'Key'                        => $filePath.$zipname,
                                            'ResponseContentDisposition' => 'attachment;'//for download
                                        ]);

                                        $req = $disk->getDriver()->getAdapter()->getClient()->createPresignedRequest($command, '+10 minutes');

                                        $url = (string)$req->getUri();

                                      }

                                      $data  = array('name'=>$name,'email'=>$email,'msg'=>$msg,'link'=>$url,'candidate'=>$candidates,'sender'=>$sender,'companyname'=>$companyname,'case_id'=>$display_id,'admin_name'=>$admin_name,'client_name'=>$client_name);

                                    }
                                    else
                                      $data  = array('name'=>$name,'email'=>$email,'msg'=>$msg,'link'=>'','candidate'=>$candidates,'sender'=>$sender,'companyname'=>$companyname,'case_id'=>$display_id,'admin_name'=>$admin_name,'client_name'=>$client_name);

                                    Mail::send(['html'=>'mails.client-insuff-notify'], $data, function($message) use($email,$name,$companyname,$display_id,$admin_email,$admin_name,$client_name) {
                                        $message->to($admin_email, $admin_name)->subject
                                            ('Insufficiency raised for Case Ref.No: '.$display_id. ' by '.$client_name);
                                        $message->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
                                    });
                                  // Notification for Insufficiency to Client
                
                                      // $sender = DB::table('users')->where(['id'=>$candidates->parent_id])->first();
                                     
                                        $notification_controls = DB::table('notification_control_configs as nc')
                                                                    ->select('nc.*')
                                                                    ->join('notification_controls as n','n.business_id','=','nc.business_id')
                                                                    ->where(['n.status'=>1,'nc.status'=>1,'n.business_id'=>$candidates->business_id,'n.type'=>'case-insuff-raise','nc.type'=>'case-insuff-raise'])
                                                                    ->get();
                                  if(count($notification_controls)>0)
                                  {
                                    foreach($notification_controls as $item)
                                    {
                                          $name = $item->name;
                                          $email =  $item->email;
                                          $msg= "Insufficiency Adddressed by ".$name. " following are details: ";

                                          if($candidates->attachment!=NULL)
                                          {
                                            $url = url('/').'/uploads/raise-insuff/'.$zipname;

                                            if($s3_config!=NULL)
                                            {
                                              $filePath = 'uploads/raise-insuff/';

                                              $disk = Storage::disk('s3');

                                              $command = $disk->getDriver()->getAdapter()->getClient()->getCommand('GetObject', [
                                                  'Bucket'                     => Config::get('filesystems.disks.s3.bucket'),
                                                  'Key'                        => $filePath.$zipname,
                                                  'ResponseContentDisposition' => 'attachment;'//for download
                                              ]);

                                              $req = $disk->getDriver()->getAdapter()->getClient()->createPresignedRequest($command, '+10 minutes');

                                              $url = (string)$req->getUri();

                                            }

                                            $data  = array('name'=>$name,'email'=>$email,'msg'=>$msg,'link'=>$url,'candidate'=>$candidates,'sender'=>$sender,'companyname'=>$companyname,'case_id'=>$display_id);

                                          }
                                          else
                                            $data  = array('name'=>$name,'email'=>$email,'msg'=>$msg,'link'=>'','candidate'=>$candidates,'sender'=>$sender,'companyname'=>$companyname,'case_id'=>$display_id);

                                          Mail::send(['html'=>'mails.client-insuff-notify'], $data, function($message) use($email,$name,$companyname,$display_id) {
                                              $message->to($email, $name)->subject
                                                  ('Insufficiency raised for Case Ref.No: '.$display_id. ' by '.$companyname);
                                              $message->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
                                          });
                                        
                                    }

                                  }

                                  // Notification for Insufficiency to Candidate

                                  $notify_candidate = DB::table('notification_controls as n')
                                                            ->select('n.*','u.display_id','u.name')
                                                             ->join('users as u','n.business_id','=','u.business_id')
                                                            ->where(['n.status'=>1,'n.business_id'=>$candidates->business_id,'n.type'=>'case-insuff-raise','n.is_send_candidate'=>1])
                                                            ->first();
                                  // dd($notify_candidate);
                                      if($notify_candidate!=NULL && $candidates->email!=NULL)
                                      {
                                        $name = $candidates->name;
                                        $client_name= $notify_candidate->name;
                                        $email =  $candidates->email;
                                        $msg= "Insufficiency Adddressed by ".$client_name. " following are details: ";

                                        if($candidates->attachment!=NULL)
                                        {
                                          $url = url('/').'/uploads/raise-insuff/'.$zipname;

                                          if($s3_config!=NULL)
                                          {
                                            $filePath = 'uploads/raise-insuff/';
                                            $disk = Storage::disk('s3');

                                            $command = $disk->getDriver()->getAdapter()->getClient()->getCommand('GetObject', [
                                                'Bucket'                     => Config::get('filesystems.disks.s3.bucket'),
                                                'Key'                        => $filePath.$zipname,
                                                'ResponseContentDisposition' => 'attachment;'//for download
                                            ]);
                                            $req = $disk->getDriver()->getAdapter()->getClient()->createPresignedRequest($command, '+10 minutes');
                                            $url = (string)$req->getUri();
                                          }

                                          $data  = array('name'=>$name,'email'=>$email,'msg'=>$msg,'link'=>$url,'candidate'=>$candidates,'sender'=>$sender,'companyname'=>$companyname,'case_id'=>$display_id);

                                        }
                                        else{
                                          $data  = array('name'=>$name,'email'=>$email,'msg'=>$msg,'link'=>'','candidate'=>$candidates,'sender'=>$sender,'companyname'=>$companyname,'case_id'=>$display_id);

                                        }

                                        Mail::send(['html'=>'mails.client-insuff-notify'], $data, function($message) use($email,$name,$companyname,$display_id,$client_name) {
                                            $message->to($email, $name)->subject
                                                ('Insufficiency raised for Case Ref.No: '.$display_id. ' by '.$client_name);
                                            $message->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
                                        });

                                      }

                                  $kams = DB::table('key_account_managers')->where(['business_id'=>$candidates->business_id])->get();
                                  if(count($kams)>0)
                                  {
                                    foreach($kams as $kam)
                                    {
                                        $user_data=DB::table('users')->where(['id'=>$kam->user_id])->first();

                                        $name1 = $user_data->name;
                                        $email1 = $user_data->email;
                                        $msg= "Insufficiency Adddressed by".$name. ",following are details: ";
                                        $sender = DB::table('users')->where(['id'=>$candidates->parent_id])->first();

                                        if($candidates->attachment!=NULL)
                                        {
                                          $url = url('/').'/uploads/raise-insuff/'.$zipname;

                                          if($s3_config!=NULL)
                                          {
                                            $filePath = 'uploads/raise-insuff/';

                                            $disk = Storage::disk('s3');

                                            $command = $disk->getDriver()->getAdapter()->getClient()->getCommand('GetObject', [
                                                'Bucket'                     => Config::get('filesystems.disks.s3.bucket'),
                                                'Key'                        => $filePath.$zipname,
                                                'ResponseContentDisposition' => 'attachment;'//for download
                                            ]);

                                            $req = $disk->getDriver()->getAdapter()->getClient()->createPresignedRequest($command, '+10 minutes');

                                            $url = (string)$req->getUri();
                                          }

                                          $data  = array('name'=>$name1,'email'=>$email1,'msg'=>$msg,'link'=>$url,'candidate'=>$candidates,'sender'=>$sender,'companyname'=>$companyname,'case_id'=>$display_id);
                                        }
                                        else
                                          $data  = array('name'=>$name1,'email'=>$email1,'msg'=>$msg,'link'=>'','candidate'=>$candidates,'sender'=>$sender,'companyname'=>$companyname,'case_id'=>$display_id);

                                        Mail::send(['html'=>'mails.client-insuff-notify'], $data, function($message) use($email1,$name1,$companyname,$display_id) {
                                            $message->to($email1, $name1)->subject
                                                ('Insufficiency raised for Case Ref.No: '.$display_id. ' by '.$companyname);
                                            $message->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
                                        });

                                    }
                                  }
                                }

                        

                        if(count($attach_on_select)>0)
                        {
                            $file_data=DB::table('insufficiency_attachments')->where(['jaf_form_data_id'=>$service->id,'service_id'=>$service->service_id,'status'=>'raise'])->get();

                            if(count($file_data)>0)
                            {
                                $path=public_path().'/uploads/raise-insuff/';
                                foreach($file_data as $file)
                                {
                                    if(File::exists($path.$file->file_name))
                                    {
                                        File::delete($path.$file->file_name);
                                    }
                                }
      
                                DB::table('insufficiency_attachments')->where(['jaf_form_data_id'=>$service->id,'service_id'=>$service->service_id,'status'=>'raise'])->delete();
      
                            }
                            
                            $i=0;
                            $file_platform = 'web';
                            if($s3_config!=NULL)
                            {
                                $s3filePath = 'uploads/raise-insuff/';

                                if(!Storage::disk('s3')->exists($s3filePath))
                                {
                                    Storage::disk('s3')->makeDirectory($s3filePath,0777, true, true);
                                }

                                foreach($attach_on_select as $item)
                                {

                                  $file_platform = 'web';
                                  $path=public_path().'/uploads/raise-insuff/';

                                  if(File::exists($path.$attach_on_select[$i]))
                                  {
                                    $file_platform = 's3';
                                    $file = Helper::createFileObject($path.$attach_on_select[$i]);

                                    Storage::disk('s3')->put($s3filePath.$attach_on_select[$i],file_get_contents($file));

                                    File::delete($path.$attach_on_select[$i]);
                                  }
                                  
                                  $insuff_file=[
                                    'parent_id' => $super_parent_id,
                                    'business_id' => $parent_id,
                                    'coc_id' => $service->business_id,
                                    'candidate_id' => $candidate_id,
                                    'service_id'  => $service->service_id,
                                    'jaf_form_data_id' => $service->id,
                                    'item_number' => $service->check_item_number,
                                    'status'=>'raise',
                                    'file_name' => $attach_on_select[$i],
                                    'file_platform' => $file_platform,
                                    'created_by'   => Auth::user()->id,
                                    'created_at'   => date('Y-m-d H:i:s'),
                                    'updated_at' => date('Y-m-d H:i:s')
                                  ];
                            
                                  $file_id = DB::table('insufficiency_attachments')->insertGetId($insuff_file);
                      
                                  $i++;

                                  if(File::exists($path.'tmp-files/'))
                                  {
                                      File::cleanDirectory($path.'tmp-files/');
                                  }
                                }
                            }
                            else
                            {
                              foreach($attach_on_select as $item)
                              {
                                $insuff_file=[
                                  'parent_id' => $super_parent_id,
                                  'business_id' => $parent_id,
                                  'coc_id' => $service->business_id,
                                  'candidate_id' => $candidate_id,
                                  'service_id'  => $service->service_id,
                                  'jaf_form_data_id' => $service->id,
                                  'item_number' => $service->check_item_number,
                                  'status'=>'raise',
                                  'file_name' => $attach_on_select[$i],
                                  'file_platform' => $file_platform,
                                  'created_by'   => Auth::user()->id,
                                  'created_at'   => date('Y-m-d H:i:s'),
                                  'updated_at' => date('Y-m-d H:i:s')
                                ];
                          
                                $file_id = DB::table('insufficiency_attachments')->insertGetId($insuff_file);
                    
                                $i++;
                              }
                            }
                        }

                    }
                    else if($is_insufficiency==0)
                    {
                      $path=public_path().'/uploads/raise-insuff';
                      $ver_data=DB::table('verification_insufficiency')->where(['jaf_form_data_id'=>$service->id,'activity_type'=>'jaf-save','status'=>'raised'])->first();

                      if($ver_data!=NULL)
                      {
                        
                        if($ver_data->attachment!=NULL)
                        {
                            if(File::exists($path.$ver_data->attachment))
                            {
                              File::delete($path.$ver_data->attachment);
                            }
                        }
                        $insuff_data=DB::table('verification_insufficiency')->where(['jaf_form_data_id'=>$service->id,'activity_type'=>'jaf-save','status'=>'raised'])->delete();
                      }

                      $insuff_attach=DB::table('insufficiency_attachments')->where(['jaf_form_data_id'=>$service->id,'status'=>'raise'])->get();
                      if(count($insuff_attach)>0){
                        foreach($insuff_attach as $insuff)
                        {
                          if(File::exists($path.$insuff->file_name))
                          {
                            File::delete($path.$insuff->file_name);
                          }
                        }

                        DB::table('insufficiency_attachments')->where(['jaf_form_data_id'=>$service->id,'status'=>'raise'])->delete();
                      }

                      // $price=20.00;

                      // $data = DB::table('check_price_cocs')->where(['service_id'=>$service->service_id,'coc_id'=>$business_id])->first();
                      // if($data!=NULL)
                      // {
                      //     $price=$data->price;
                      // }
                      // else{
                      //     $data=DB::table('check_prices')->where(['service_id'=>$service->service_id,'business_id'=>$parent_id])->first();
                      //     if($data!=NULL)
                      //     {
                      //         $price=$data->price;
                      //     }
                      //     // else{
                      //     //     $data=DB::table('check_price_masters')->where(['service_id'=>$service->service_id])->first();
                      //     //     if($data!=NULL)
                      //     //     {
                      //     //         $price=$data->price;
                      //     //     }
                      //     // }
                      // }

                    }
                }

                $serviceId = DB::table('services')->select('id','name','type_name')->where('type_name','cin')->first();
                $serviceUpi = DB::table('services')->select('id','name','type_name')->where('type_name','upi')->first();
                $serviceUan = DB::table('services')->select('id','name','type_name')->where('type_name','uan-number')->first();
                
                DB::table('job_items')->where(['id'=>$case_id])->update(['jaf_status'=>'filled','filled_by'=>Auth::user()->id,'filled_at'=>date('Y-m-d H:i:s')]);

                  // return redirect('my/candidates/jaf-info/'.base64_encode($candidate_id))
                  //       ->with('success', 'Candidate BGV submitted.');
                  $job_item =DB::table('job_items')->where(['candidate_id'=>$candidate_id,'jaf_status'=>'filled'])->first();
                  // $j = 0;
                  if ($job_item->jaf_status == 'filled') {
                      //  $jfd_service =[];
                        $jfd_service= DB::table('jaf_form_data')->where('candidate_id',$candidate_id)->whereIn('service_id',['2','3','4','7','8','9','12',$serviceId->id,$serviceUpi->id,$serviceUan->id])->get();
                        $jfd_service=$jfd_service->toArray();
                        // want to ApiCheck event
                          event(new ApiCheck($jfd_service));
                    
                    $job_sla_items = DB::table('job_sla_items')->where('candidate_id',$candidate_id)->get();

                    foreach ($job_sla_items as $job_sla_item) {
                      //Get data of user of customer with 
                      $user_permissions = DB::table('users as u')
                      ->join('role_masters as rm', 'rm.id', '=', 'u.role')
                      ->join('role_permissions as rp', 'rp.role_id', '=', 'rm.id')
                      ->select('u.name','u.id','u.role as role_id','rm.role','rp.permission_id' )
                      ->where('u.business_id',Auth::user()->parent_id)
                      ->get();
                      // Get BGV FILLING data from Action table for matching checking permission
                      $action_master = DB::table('action_masters')
                      ->select('*')
                      ->where(['route_group'=>'','action_title'=>'BGV Filled'])
                      ->first(); 
                      // dd($action_master->id);
                      // Check condition if user_permission have any data or not
                      if(count($user_permissions)>0) {
                        $users=[];
                        foreach ($user_permissions as $user_permission) {  
                          if(in_array($action_master->id,json_decode($user_permission->permission_id)))
                          {
                            $users[]= $user_permission;
                          }
                        }
                      }
                      $final_users = [];
                        // $numbers_of_items = $job_sla_item->number_of_verifications;
                        //create task for BGV QC
                     
                        
                      // if($numbers_of_items > 0){
                          // for ($i=1; $i <= $numbers_of_items; $i++) { 
                            
                          //   $final_users = [];
                          //     //insert in task
                          //     $data = [
                          //       'name'          => $request->input('first_name').' '.$request->input('last_name'),
                          //       'parent_id'     => Auth::user()->parent_id,
                          //       'business_id'   => $business_id, 
                          //       'description'   => 'Task for Verification ',
                          //       'job_id'        => NULL, 
                          //       'priority'      => 'normal',
                          //       'candidate_id'  => $candidate_id,   
                          //       'service_id'    => $job_sla_item->service_id, 
                          //       'number_of_verifications' => $i,
                          //       'assigned_to'   => NULL,
                          //       // 'assigned_by'   => Auth::user()->id,
                          //       // 'assigned_at'   => date('Y-m-d H:i:s'),
                          //       // 'start_date'    => date('Y-m-d'),
                          //       'created_by'    => Auth::user()->id,
                          //       'created_at'    => date('Y-m-d H:i:s'),
                          //       'is_completed'  => '0',
                          //       // 'started_at'    => date('Y-m-d H:i:s')
                          //     ];
                          //     // // dd($data);
                          //     $task_id =  DB::table('tasks')->insertGetId($data); 

                          //     $taskdata = [
                          //       'parent_id'     => Auth::user()->parent_id,
                          //       'business_id'   => $business_id,
                          //       'candidate_id'  =>$candidate_id,   
                          //       'job_sla_item_id'  => $job_sla_item->id,
                          //       'task_id'       => $task_id,
                          //     //  'user_id'       =>  $task_user->id,
                          //       'service_id'    =>$job_sla_item->service_id,
                          //       'number_of_verifications' => $i,
                          //       'created_at'    => date('Y-m-d H:i:s')  
                          //     ];
                              
                          //     DB::table('task_assignments')->insertGetId($taskdata); 
                          //     // DB::table('task_assignments')->insertGetId($taskdata); 
                            
                          //     //send email to customer
                          
                          //       //If login user is normal user
                          //       $login_user = Auth::user()->parent_id;
                          //       $user= User::where('id',$login_user)->first();
                          //       $admin_email = $user->email;
                          //       $admin_name = $user->first_name;
                          //       //send email to customer
                          //       $email = $admin_email;
                          //       $name  = $admin_name;
                          //       $candidate_name = $request->input('first_name');
                          //       $msg = "New BGV verification Task Created with candidate name";
                          //       $sender = DB::table('users')->where(['id'=>$parent_id])->first();
                          //       $data  = array('name'=>$name,'email'=>$email,'candidate_name'=>$candidate_name,'msg'=>$msg,'sender'=>$sender);

                          //       Mail::send(['html'=>'mails.task-notify'], $data, function($message) use($email,$name) {
                          //             $message->to($email, $name)->subject
                          //               ('Clobminds Pvt Ltd - Notification for BGV Filling Task');
                          //             $message->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
                          //       });
                            
                          //     $kams  = KeyAccountManager::where('business_id',$business_id)->get();

                          //     if (count($kams)>0) {
                          //       foreach ($kams as $kam) {

                          //         $user= User::where('id',$kam->user_id)->first();
                                
                          //         $email = $user->email;
                          //         $name  = $user->name;
                          //         $candidate_name = $request->input('first_name');
                          //         $msg = "New BGV verification Task Created with candidate name";
                          //         $sender = DB::table('users')->where(['id'=>$parent_id])->first();
                          //         $data  = array('name'=>$name,'email'=>$email,'candidate_name'=>$candidate_name,'msg'=>$msg,'sender'=>$sender);

                          //         Mail::send(['html'=>'mails.task-notify'], $data, function($message) use($email,$name) {
                          //               $message->to($email, $name)->subject
                          //                 ('Clobminds Pvt Ltd - Notification for BGV Filling Task');
                          //               $message->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
                          //         });
                          //       }
                          //     }
                          // }
                          
                        // }   
                    }


                        $j_sla_item  = DB::table('job_sla_items')->select('id')->where('candidate_id',$candidate_id)->latest()->first();

                        $data = [
                          'name'          => $request->input('first_name').' '.$request->input('last_name'),
                          'parent_id'     =>Auth::user()->parent_id,
                          'business_id'   => $business_id, 
                          'description'   => 'BGV QC',
                          'job_id'        => NULL, 
                          'priority'      => 'normal',
                          'candidate_id'  => $candidate_id,   
                          'service_id'    => 0, 
                          'number_of_verifications' => NULL,
                          'assigned_to'   => NULL,
                          'created_by'    => Auth::user()->id,
                          'created_at'    => date('Y-m-d H:i:s'),
                          'updated_at'    => date('Y-m-d H:i:s'),
                          'is_completed'  => 0,
                          // 'started_at'    => date('Y-m-d H:i:s')
                        ];

                        
                        // // dd($data);
                        $task_id =  DB::table('tasks')->insertGetId($data); 
                        $taskdata = [
                          'parent_id'=> Auth::user()->parent_id,
                          'business_id'       => $business_id,
                          'candidate_id'      => $candidate_id,   
                          'job_sla_item_id'   => $j_sla_item->id,
                          'task_id'           => $task_id,
                          'service_id'        => NULL,
                          'number_of_verifications' => 0,
                          'created_at'        => date('Y-m-d H:i:s'),
                          'updated_at'        => date('Y-m-d H:i:s') 
                        ];

                      
                        
                        DB::table('task_assignments')->insertGetId($taskdata); 

                     //  update task 
                        $report = DB::table('reports')->where('candidate_id',$candidate_id)->first();
                        // dd($report);
                        if ($report==NULL) {
                          $report= '';
                        }
              
                        //check report items created or not
                        $report_count = DB::table('reports')->where(['candidate_id'=>$candidate_id])->count(); 
                        if($report_count == 0){
                        
                          $job = DB::table('job_items')->where(['candidate_id'=>$candidate_id])->first(); 
                      
                          $data = 
                          [
                            'parent_id'     =>Auth::user()->parent_id,
                            'business_id'   =>$job->business_id,
                            'candidate_id'  =>$candidate_id,
                            'sla_id'        =>$job->sla_id,       
                            'created_at'    =>date('Y-m-d H:i:s')
                          ];
                          $report_id = DB::table('reports')->insertGetId($data);
                          
                          
                        }
                        else
                        {
                          $report_id = $report->id;
                        }

                        // add service items
                          $jaf_items_datas = DB::table('jaf_form_data')->where(['candidate_id'=>$candidate_id])->get(); 
                  
                          foreach($jaf_items_datas as $item){

                            $reference_type = NULL;
                            $r_item = DB::table('report_items')->where('jaf_id',$item->id)->first();
                            // $l=0;
                            
                            // if($item->service_id==17)
                            // {
                            //   $input_data = $item->form_data;

                            //   $input_data_array = json_decode($input_data,true);

                            //   if($input_data_array!=NULL)
                            //   {
                            //     foreach($input_data_array as $key => $input)
                            //     {
                            //       $key_val = array_keys($input); $input_val = array_values($input);
                            //       if(stripos($key_val[0],'Reference Type (Personal / Professional)')!==false)
                            //       {
                            //          $reference_type = $input_val[0];
                            //       }
                            //     }
                            //   }
                            // }

                            if($r_item!=NULL)
                            {
                                if ($item->verification_status == 'success') {
                                  $data = 
                                  [
                                    'is_check_ignore' => $item->is_check_ignore,
                                    'check_ignore_created_by' => $item->check_ignore_created_by,
                                    'check_ignore_created_at' => $item->check_ignore_created_at,
                                    'jaf_data'      =>$item->form_data,
                                    'updated_at'    =>date('Y-m-d H:i:s')
                                  ];
                                } 
                                else {
                                  $data = 
                                  [
                                    'is_check_ignore' => $item->is_check_ignore,
                                    'check_ignore_created_by' => $item->check_ignore_created_by,
                                    'check_ignore_created_at' => $item->check_ignore_created_at,
                                    'jaf_data'      =>$item->form_data,
                                    'updated_at'    =>date('Y-m-d H:i:s')
                                  ]; 
                                }
  
                                DB::table('report_items')->where('jaf_id',$item->id)->update($data);
  
                                $report_item_id = $r_item->id;
                            }
                            else
                            {
                                if ($item->verification_status == 'success') {
                                  $data = 
                                  [
                                    'report_id'     =>$report_id,
                                    'service_id'    =>$item->service_id,
                                    'service_item_number'=>$item->check_item_number,
                                    'candidate_id'  =>$candidate_id,      
                                    'jaf_data'      =>$item->form_data,
                                    'jaf_id'        =>$item->id,
                                    // 'reference_type' =>  $reference_type,
                                    'is_check_ignore' => $item->is_check_ignore,
                                    'check_ignore_created_by' => $item->check_ignore_created_by,
                                    'check_ignore_created_at' => $item->check_ignore_created_at,
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
                                    // 'reference_type' =>  $reference_type,
                                    'is_check_ignore' => $item->is_check_ignore,
                                    'check_ignore_created_by' => $item->check_ignore_created_by,
                                    'check_ignore_created_at' => $item->check_ignore_created_at,
                                    'created_at'    =>date('Y-m-d H:i:s')
                                  ]; 
                                }
                                
                                $report_item_id = DB::table('report_items')->insertGetId($data);
                            }

                            

                            $report_item = DB::table('report_items')->where(['id'=>$report_item_id])->first();
                            $jaf_item_attachments= DB::table('jaf_item_attachments')->where(['jaf_id'=>$item->id,'is_deleted'=>'0'])->get();
                                     
                            if(count($jaf_item_attachments)>0){
                              foreach($jaf_item_attachments as $attachment){
                                // dd(public_path('/uploads/report-files/'));
                                // dd($attachment);
                                // $dir  = public_path('/uploads/jaf-files/');
                                $file_platform = 'web'; 
                                if(stripos($attachment->file_platform,'s3')!==false)
                                {
                                  $s3_config = S3ConfigTrait::s3Config();

                                  $file_platform = 's3';
                                  $jaf_path = 'uploads/jaf-files/';
                                  $report_path = 'uploads/report-files/';
                                  if(!Storage::disk('s3')->exists($report_path))
                                  {
                                      Storage::disk('s3')->makeDirectory($report_path,0777, true, true);
                                  }

                                  Storage::disk('s3')->copy($jaf_path.$attachment->file_name,$report_path.$attachment->file_name);
                                }
                                else
                                {
                                    File::copy(public_path('/uploads/jaf-files/'.$attachment->file_name),public_path('/uploads/report-files/'.$attachment->file_name)); 
                                }
                              
                                DB::table('report_item_attachments')->insert([
                                  'report_id'        => $report_id, 
                                  'report_item_id'   => $report_item_id,
                                  'jaf_item_attachment_id'=>$attachment->id,
                                  'file_name'        => $attachment->file_name,
                                  'attachment_type'  => $attachment->attachment_type,
                                  'file_platform'   => $file_platform,
                                  'img_order'       =>$attachment->img_order,
                                  'service_attachment_name'=>$attachment->service_attachment_name,
                                  'service_attachment_id'=>$attachment->service_attachment_id,
                                  'created_by'       => Auth::user()->id,
                                  'created_at'       => date('Y-m-d H:i:s'),
                                  'is_temp'          => $attachment->is_temp,
                                ]);
                              }
                            }
                            $report_item = DB::table('report_items')->where(['id'=>$report_item_id])->first();

                            if($report_item->service_id==17)
                            {
                              $input_data = $item->form_data;

                              $input_data_array = json_decode($input_data,true);

                              if($input_data_array!=NULL)
                              {
                                foreach($input_data_array as $key => $input)
                                {
                                  $key_val = array_keys($input); $input_val = array_values($input);
                                  if(stripos($key_val[0],'Reference Type (Personal / Professional)')!==false)
                                  {
                                      $reference_type = $input_val[0];
                                  }
                                }
                              }
                            }

                            DB::table('report_items')->where(['id'=>$report_item_id])->update([
                                'reference_type' => $reference_type
                            ]);
                          }

                    // Notification for BGV Filled to Client
                    $candidate = DB::table('users')->where('id',$candidate_id)->first();
                    $company = DB::table('user_businesses')->select('company_name')->where(['business_id'=>$candidate->business_id])->first();
                    $sender = DB::table('users')->where(['id'=>$candidate->parent_id])->first();

                    $notification_controls = DB::table('notification_control_configs as nc')
                                              ->select('nc.*')
                                              ->join('notification_controls as n','n.business_id','=','nc.business_id')
                                              ->where(['n.status'=>1,'nc.status'=>1,'n.business_id'=>$candidate->business_id,'n.type'=>'jaf-filled','nc.type'=>'jaf-filled'])
                                              ->get();

                    if(count($notification_controls)>0)
                    {
                      foreach($notification_controls as $item)
                      {
                          $name = $item->name;
                          $email =  $item->email;
                          $company_name = $company->company_name;
                          $msg= 'Notification for Job Application Form Has Been Filled Successfully for Candidate ('.$candidate->name.' - '.$candidate->display_id.') at '.date('d-M-y h:i A').'';

                          $data  = array('name'=>$name,'email'=>$email,'company_name'=>$company_name,'sender'=>$sender,'msg'=>$msg);

                          Mail::send(['html'=>'mails.jaf-filled'], $data, function($message) use($email,$name) {
                              $message->to($email, $name)->subject
                                  ('Clobminds Pvt Ltd - BGV Notification');
                              $message->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
                          });
                      }
                    }
                    
                  }
                

                  // DB::commit();
                  return response()->json([
                    'fail'=>false,
                    'success' => true,
                    'candidate_id' => base64_encode($candidate_id)
                  ]);
            }
          // }
          // catch (\Exception $e) {
          //     DB::rollback();
          //     // something went wrong
          //     return $e;
          // }      
        }
    }

     //BGV Form show
     public function jafInfo($id)
     {
       $user_id = Auth::user()->id;

       $parent_id = Auth::user()->parent_id;
 
         $candidate_id = base64_decode($id);
        //  dd($candidate_id);
        //  $jaf_items = [];
 
         $candidate = DB::table('users as u')
         ->select('u.id','u.business_id','u.client_emp_code','u.entity_code','u.first_name','u.middle_name','u.last_name','u.name','u.email','u.phone','u.phone_code','u.phone_iso','j.created_at','j.job_id','j.sla_id','j.is_all_insuff_cleared','j.insuff_cleared_by','u.display_id','u.dob','u.aadhar_number','u.father_name','u.gender','j.jaf_status','u.digital_signature','u.digital_signature_file_platform')  
         ->leftjoin('job_items as j','j.candidate_id','=','u.id')
         ->where(['u.id'=>$candidate_id]) 
         ->first(); 
 
         //get BGV data - 
         $jaf_items = DB::table('jaf_form_data as jf')
               ->select('jf.id','jf.form_data_all','jf.form_data','jf.check_item_number','jf.address_type','jf.insufficiency_notes','jf.is_insufficiency','jf.is_api_checked','jf.verification_status','jf.verified_at','s.name as service_name','s.id as service_id','s.verification_type','s.type_name')
               ->join('services as s','s.id','=','jf.service_id')
               ->where(['jf.candidate_id'=>$candidate_id])
               ->get();

               $sla_items = DB::select("SELECT sla_id, GROUP_CONCAT(DISTINCT service_id) AS alot_services FROM `job_sla_items` WHERE candidate_id = $candidate_id");

          $checks = UserCheck::where('user_id',$user_id)->get();

          $report = DB::table('reports')->where(['candidate_id'=>$candidate_id,'status'=>'completed'])->first();
          // dd($report);
          if ($report==NULL) {
            $report= '';
          }

            $job = DB::table('job_items')->where(['candidate_id'=>$candidate_id])->first();

            // if($job->jaf_status=='filled')
            // {
            //     //check report items created or not
            //     $report_count = DB::table('reports')->where(['candidate_id'=>$candidate_id])->count(); 
            //     if($report_count == 0){ 
                
            //       $data = 
            //         [
            //           'parent_id'     =>$parent_id,
            //           'business_id'   =>$job->business_id,
            //           'candidate_id'  =>$candidate_id,
            //           'sla_id'        =>$job->sla_id,       
            //           'created_at'    =>date('Y-m-d H:i:s')
            //         ];
                    
            //         $report_id = DB::table('reports')->insertGetId($data);
                    
            //         // add service items
            //         $jaf_item = DB::table('jaf_form_data')->where(['candidate_id'=>$candidate_id])->get(); 

            //         foreach($jaf_item as $item){
            //           if ($item->verification_status == 'success') {
            //             $data = 
            //             [
            //               'report_id'     =>$report_id,
            //               'service_id'    =>$item->service_id,
            //               'service_item_number'=>$item->check_item_number,
            //               'candidate_id'  =>$candidate_id,      
            //               'jaf_data'      =>$item->form_data,
            //               'jaf_id'        =>$item->id,
            //               'created_at'    =>date('Y-m-d H:i:s')
            //             ];
            //           } else {
            //             $data = 
            //             [
            //               'report_id'     =>$report_id,
            //               'service_id'    =>$item->service_id,
            //               'service_item_number'=>$item->check_item_number,
            //               'candidate_id'  =>$candidate_id,      
            //               'jaf_data'      =>$item->form_data,
            //               'jaf_id'        =>$item->id,
            //               'is_report_output' => '0',
            //               'created_at'    =>date('Y-m-d H:i:s')
            //             ]; 
            //           }
                      
                    
                        
            //           $report_item_id = DB::table('report_items')->insertGetId($data);
            //         }
            //     }
            // }
            
         return view('clients.candidates.jaf-info',compact('candidate','jaf_items','checks','sla_items','report'));
     }

     public function jafUpdate(Request $request)
     {
       $parent_id= Auth::user()->parent_id;
       $business_id =Auth::user()->business_id;
       $user_id = Auth::user()->id;
        $this->validate($request, [
          'digital_signature' => 'mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $candidate_id = base64_decode($request->input('candidate_id'));

        // dd($candidate_id);
        DB::beginTransaction();
        try
        {
          $jaf_items = DB::table('jaf_form_data')->where(['candidate_id'=>$candidate_id])->get();
          //dd($jaf_items);
          $input_data = [];

          foreach($jaf_items as $service)
          {
            // if($service->service_id=='1')
            // {
            //     $this->validate($request, [
            //       'address-type-'.$service->id => 'required',
            //     ],
            //     [
            //       'address-type-'.$service->id.'.required'=> 'Address Type Field is required'
            //     ]
            //   );

            // }

            if (in_array($service->id,$request->jaf_id)) 
            {
              $input_items = DB::table('service_form_inputs as sfi')
                              ->select('sfi.*')            
                              ->where(['sfi.service_id'=>$service->service_id,'status'=>1])
                              ->whereNull('sfi.reference_type')
                              ->whereNotIn('label_name',['Mode of Verification','Remarks'])
                              ->get();

                $input_data = [];
                $i=0;
                foreach($input_items as $input){
                  
                  $input_data[] = [
                                    $request->input('service-input-label-'.$service->id.'-'.$i)=>$request->input('service-input-value-'.$service->id.'-'.$i),
                                    'is_report_output'=>$input->is_report_output 
                                  ];
                    $i++;
                }
              
                $jaf_data = json_encode($input_data);
                $address_type = $request->input('address-type-'.$service->id);
                $jaf_form_data = [
                                  'form_data'         => $jaf_data,
                                  'form_data_all'     => json_encode($request->all()),
                                  // 'is_insufficiency'  => $is_insufficiency,
                                  // 'insufficiency_notes'=>$insufficiency_notes,
                                  'address_type'  => $address_type,
                                  'updated_by'   => Auth::user()->id,
                                  'updated_at'   => date('Y-m-d H:i:s')];
                 // dd($jaf_data);
                DB::table('jaf_form_data')->where(['id'=>$service->id])->update($jaf_form_data);
            }
          }

          if ($files = $request->file('digital_signature')) 
          {
              $s3_config = S3ConfigTrait::s3Config();
              $digital_signature_file_platform  = 'web';
              $file=$request->file('digital_signature');
              $filename=$file->getclientOriginalName();
              $file_name=date('Yhis').$filename;
              $destinationPath = public_path('uploads/signatures/'); 
              $dsext = $request->file('digital_signature')->getClientOriginalExtension();
              // $files->move($destinationPath, $dsImage);
              
                $candidate=DB::table('users')->select('digital_signature','digital_signature_file_platform')->where('id',$candidate_id)->first();

                $candidate_img=$candidate->digital_signature;
                // $user_img = Auth::user()->digital_signature;
                if($candidate_img!=NULL || $candidate_img!='')
                {
                  if(File::exists(public_path().'/uploads/signatures/'.$candidate_img))
                  {
                    File::delete(public_path().'/uploads/signatures/'.$candidate_img);
                  } 
                }

                if($s3_config!=NULL)
                {
                    $digital_signature_file_platform = 's3';

                    $path = 'uploads/signatures/';

                    if(!Storage::disk('s3')->exists($path))
                    {
                        Storage::disk('s3')->makeDirectory($path,0777, true, true);
                    }

                    Storage::disk('s3')->put($path.$file_name, file_get_contents($files));
                }
                else
                {
                  $request->digital_signature->move($destinationPath,$file_name);
                }
                // $mask = Image::make($file)
                //           ->orientate() // it's better to set a tolerance for trim()
                //           ->invert(); // invert it to use as a ma
                          
                // $new_image = Image::canvas($mask->width(), $mask->height(), '#000000')
                //             ->mask($mask)
                //             ->save('uploads/signatures/'.$file_name);

                DB::table('users')->where(['id'=>$candidate_id])->update([
                  'digital_signature' => $file_name,
                  'digital_signature_file_platform' => $digital_signature_file_platform
                ]);
            
          }

          DB::table('jaf_logs')->insert([
            'parent_id' => $parent_id,
            'business_id' =>  $business_id,
            'candidate_id' => $candidate_id,
            'created_by' => $user_id,
            'user_type' => 'coc',
            'activity_type' => 'jaf-update',
            'created_at' => date('Y-m-d H:i:s')
          ]);

          DB::commit();
          return redirect()
              ->route('/my/candidates/jaf-info',['case_id'=> base64_encode($candidate_id)])
              ->with('success', 'Candidate BGV updated.');
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        } 
        
     }

     // add file.
    public function uploadFile(Request $request)
    {        
      //  echo count($request->file('files'));
      //  print_r($request->file('files')); 
      //  echo $request->input('service_id');
      //  die;
      // dd($request);
        $files=[];
        $i=0;
        $extensions = array("jpg","png","jpeg","PNG","JPG","JPEG","gif","pdf");

        DB::beginTransaction();
        try{
          if($request->hasFile('files')) {

            foreach( $request->file('files') as $item){
            
              $result = array($request->file('files')[$i]->getClientOriginalExtension());

              if(in_array($result[0],$extensions))
              {                      
                  // $label_file_name  = $request->input('label_file_name');

                  $file_platform = 'web';

                  $s3_config = S3ConfigTrait::s3Config();
        
                  $attachment_file  = $request->file('files')[$i];
                  $orgi_file_name   = $attachment_file->getClientOriginalName();
                  
                  $file_size = number_format(File::size($attachment_file) / 1048576, 2);
                    
                  if($file_size > 10)
                  {
                      return response()->json([
                        'fail' => true,
                        'error_type'=>'validation',
                        'errors' => ['files'  => 'The document size must be less than only 10mb Upload !'],
                      ]);                        
                  }

                  $fileName = pathinfo($orgi_file_name,PATHINFO_FILENAME);
        
                  //$filename         = time().'-'.$fileName.'.'.$attachment_file->getClientOriginalExtension();
                  $file_name_time   = time().'-'.date('Ymdhis');
                  $filename         = $file_name_time.'.'.$attachment_file->getClientOriginalExtension();
                  $dir              = public_path('/uploads/jaf-files/');            
                  $request->file('files')[$i]->move($dir, $filename);
                    
                  $candidate_id  = NULL;
                  $jaf_id  = NULL;
                  $is_temp         = 1;
                  $type            = 'main';
                  //check if report id 
                  if($request->has('jaf_id')) {
                      $candidate_id       = base64_decode($request->input('candidate_id'));
                      $jaf_id  = $request->input('jaf_id');
                      $business_id= $request->input('business_id');
                      $is_temp         = 0;
                      //get service item id
                      //  $type            = 'supporting';
                      //  if($request->has('type')){
                      //   $type           = $request->input('type');
                      //  }
                  }
                    
                    // $rowID = DB::table('jaf_item_attachments')            
                    //           ->insertGetId([
                    //               'jaf_id'        => $jaf_id, 
                    //               'business_id'   => Auth::user()->business_id,    
                    //               'candidate_id' => $candidate_id,                 
                    //               'file_name'        => $filename,
                    //               'attachment_type'  => $type,
                    //               'updated_by'       => Auth::user()->id,
                    //               'created_at'       => date('Y-m-d H:i:s'),
                    //               'is_temp'          => $is_temp,
                    //           ]);                                
        
                    // file type 
                    
                    $extArray = explode('.', $filename);
                    $ext = end($extArray);

                    $file_id_array = [];
                    $file_name_array = [];
                    $file_url_array = [];
                    
                    if(stripos($ext,'pdf')!==false)
                    {
                      $file_platform = 'web';
                      if(File::exists($dir.$filename))
                      {
                          $pdf_file_name = $file_name_time.'-'.time();

                          $imagick = new Imagick();

                          $imagick->setResolution(300, 300);

                          $imagick->readImage($dir.$filename);

                          $imagick->setImageFormat("png");

                          $pages = $imagick->getNumberImages();

                          $imagick->writeImages($dir.$pdf_file_name.'.png', false);

                          if($pages)
                          {
                              if($pages==1)
                              {
                                if($s3_config!=NULL)
                                {
                                    $file_platform = 's3';

                                    $file_name = $pdf_file_name.'.png';

                                    $path = 'uploads/jaf-files/';

                                    if(!Storage::disk('s3')->exists($path))
                                    {
                                        Storage::disk('s3')->makeDirectory($path,0777, true, true);
                                    }

                                    $file = Helper::createFileObject($path.$file_name);

                                    Storage::disk('s3')->put($path.$file_name, file_get_contents($file));

                                }
                                $rowID = DB::table('jaf_item_attachments')
                                  ->insertGetId([
                                      'jaf_id'        => $jaf_id, 
                                      'business_id'   => Auth::user()->business_id,    
                                      'candidate_id' => $candidate_id,                       
                                      'file_name'        => $pdf_file_name.'.png',
                                      'attachment_type'  => $type,
                                      'file_platform'     => $file_platform,
                                      'created_by'       => Auth::user()->id,
                                      'service_attachment_id'=>$request->service_type,
                                      'service_attachment_name'=>$request->attachment_name,
                                      'created_at'       => date('Y-m-d H:i:s'),
                                      'is_temp'          => $is_temp,
                                  ]);
                                  $report_count = DB::table('reports')->where(['candidate_id'=>$candidate_id])->count(); 
                                  if($report_count > 0){
                                    $report_items= DB::table('report_items')->where('jaf_id',$jaf_id)->first();
                                    if($report_items){
                                        // dd($report_items);
                                        File::copy(public_path('/uploads/jaf-files/'.$pdf_file_name.'.png'),public_path('/uploads/report-files/'.$pdf_file_name.'.png'));
                                        if(stripos($file_platform,'s3')!==false)
                                        {
                                          $path = 'uploads/jaf-files/';
                                          $report_path = 'uploads/report-files/';
                                          if(!Storage::disk('s3')->exists($report_path))
                                          {
                                              Storage::disk('s3')->makeDirectory($report_path,0777, true, true);
                                          }

                                          Storage::disk('s3')->copy($path.$pdf_file_name.'.png',$report_path.$pdf_file_name.'.png');

                                        }
                                      
                                        DB::table('report_item_attachments')->insert([
                                        'report_id'        => $report_items->report_id, 
                                        'report_item_id'   => $report_items->id,
                                        'jaf_item_attachment_id'=>$rowID,
                                        'file_name'        => $pdf_file_name.'.png',
                                        'attachment_type'  => $type,
                                        'file_platform'     => $file_platform,
                                        'service_attachment_id'=>$request->service_type,
                                          'service_attachment_name'=>$request->attachment_name,
                                        'created_by'       => Auth::user()->id,
                                        'created_at'       => date('Y-m-d H:i:s'),
                                        'is_temp'          => $is_temp,
                                      ]);
                                    }
                                  }

                                  $file_url = '';
                                  if(stripos($file_platform,'s3')!==false)
                                  {
                                    $filePath = 'uploads/jaf-files/';
                                    if(File::exists(public_path('/uploads/jaf-files/'.$pdf_file_name.'.png')))
                                    {
                                        File::delete(public_path('/uploads/jaf-files/'.$pdf_file_name.'.png'));
                                    }

                                    $disk = Storage::disk('s3');

                                    $command = $disk->getDriver()->getAdapter()->getClient()->getCommand('GetObject', [
                                        'Bucket'                     => Config::get('filesystems.disks.s3.bucket'),
                                        'Key'                        => $filePath.$pdf_file_name.'.png',
                                        'ResponseContentDisposition' => 'attachment;'//for download
                                    ]);

                                    $req = $disk->getDriver()->getAdapter()->getClient()->createPresignedRequest($command, '+10 minutes');

                                    $file_url = (string)$req->getUri();
                                  }
                                  else
                                  {
                                      $file_url = url('/').'/uploads/jaf-files/'.$pdf_file_name.'.png';
                                  }

                                  $file_id_array[] = $rowID;
                                  $file_name_array[] = $pdf_file_name.'.png';
                                  $file_url_array[] = $file_url;
                              }
                              else
                              {
                                  for($i=0;$i<$pages;$i++)
                                  {
                                        $file_platform = 'web';

                                        if($s3_config!=NULL)
                                        {
                                            $file_platform = 's3';

                                            $file_name = $pdf_file_name.'-'.$i.'.png';

                                            $path = 'uploads/jaf-files/';

                                            if(!Storage::disk('s3')->exists($path))
                                            {
                                                Storage::disk('s3')->makeDirectory($path,0777, true, true);
                                            }

                                            $file = Helper::createFileObject($path.$file_name);

                                            Storage::disk('s3')->put($path.$file_name, file_get_contents($file));

                                        }
                                      $rowID = DB::table('jaf_item_attachments')            
                                      ->insertGetId([
                                        'jaf_id'        => $jaf_id, 
                                        'business_id'   => Auth::user()->business_id,    
                                        'candidate_id' => $candidate_id,                       
                                          'file_name'        => $pdf_file_name.'-'.$i.'.png',
                                          'attachment_type'  => $type,
                                          'file_platform'    => $file_platform,
                                          'created_by'       => Auth::user()->id,
                                          'service_attachment_id'=>$request->service_type,
                                          'service_attachment_name'=>$request->attachment_name,
                                          'created_at'       => date('Y-m-d H:i:s'),
                                          'is_temp'          => $is_temp,
                                      ]);
                                      $report_count = DB::table('reports')->where(['candidate_id'=>$candidate_id])->count(); 
                                      if($report_count > 0){
                                        $report_items= DB::table('report_items')->where('jaf_id',$jaf_id)->first();
                                        if($report_items){
                                            // dd($report_items);
                                            File::copy(public_path('/uploads/jaf-files/'.$pdf_file_name.'-'.$i.'.png'),public_path('/uploads/report-files/'.$pdf_file_name.'-'.$i.'.png'));
                                            if(stripos($file_platform,'s3')!==false)
                                            {
                                              $path = 'uploads/jaf-files/';
                                              $report_path = 'uploads/report-files/';
                                              if(!Storage::disk('s3')->exists($report_path))
                                              {
                                                  Storage::disk('s3')->makeDirectory($report_path,0777, true, true);
                                              }

                                              Storage::disk('s3')->copy($path.$pdf_file_name.'-'.$i.'.png',$report_path.$pdf_file_name.'-'.$i.'.png');

                                            }
                                            DB::table('report_item_attachments')->insert([
                                            'report_id'        => $report_items->report_id, 
                                            'report_item_id'   => $report_items->id,
                                            'jaf_item_attachment_id'=>$rowID,
                                            'file_name'        => $pdf_file_name.'-'.$i.'.png',
                                            'attachment_type'  => $type,
                                            'file_platform'     => $file_platform,
                                            'service_attachment_id'=>$request->service_type,
                                            'service_attachment_name'=>$request->attachment_name,
                                            'created_by'       => Auth::user()->id,
                                            'created_at'       => date('Y-m-d H:i:s'),
                                            'is_temp'          => $is_temp,
                                          ]);
                                        }
                                      }
                                      $file_url = '';
                                      if(stripos($file_platform,'s3')!==false)
                                      {
                                        $filePath = 'uploads/jaf-files/';
                                        if(File::exists(public_path('/uploads/jaf-files/'.$pdf_file_name.'-'.$i.'.png')))
                                        {
                                            File::delete(public_path('/uploads/jaf-files/'.$pdf_file_name.'-'.$i.'.png'));
                                        }

                                        $disk = Storage::disk('s3');

                                        $command = $disk->getDriver()->getAdapter()->getClient()->getCommand('GetObject', [
                                            'Bucket'                     => Config::get('filesystems.disks.s3.bucket'),
                                            'Key'                        => $filePath.$pdf_file_name.'-'.$i.'.png',
                                            'ResponseContentDisposition' => 'attachment;'//for download
                                        ]);

                                        $req = $disk->getDriver()->getAdapter()->getClient()->createPresignedRequest($command, '+10 minutes');

                                        $file_url = (string)$req->getUri();
                                      }
                                      else
                                      {
                                          $file_url = url('/').'/uploads/jaf-files/'.$pdf_file_name.'-'.$i.'.png';
                                      }
    
                                      $file_id_array[] = $rowID;
                                      $file_name_array[] = $pdf_file_name.'-'.$i.'.png';
                                      $file_url_array[] = $file_url;  
                                  }
                              }
                          }

                          File::delete($dir.$filename);

                      }
                    }
                    else
                    {
                      $file_platform = 'web';
                      if($s3_config!=NULL)
                      {
                          $file_platform = 's3';

                          $path = 'uploads/jaf-files/';

                          if(!Storage::disk('s3')->exists($path))
                          {
                              Storage::disk('s3')->makeDirectory($path,0777, true, true);
                          }

                          $file = Helper::createFileObject($path.$filename);

                          Storage::disk('s3')->put($path.$filename, file_get_contents($file));

                      }

                      $rowID = DB::table('jaf_item_attachments')            
                      ->insertGetId([
                          'jaf_id'        => $jaf_id, 
                          'business_id'   => Auth::user()->business_id,    
                          'candidate_id' => $candidate_id,                 
                          'file_name'        => $filename,
                          'attachment_type'  => $type,
                          'file_platform'    => $file_platform,
                          'created_by'       => Auth::user()->id,
                          'service_attachment_id'=>$request->service_type,
                          'service_attachment_name'=>$request->attachment_name,
                          'created_at'       => date('Y-m-d H:i:s'),
                          'is_temp'          => $is_temp,
                      ]); 
                      $report_count = DB::table('reports')->where(['candidate_id'=>$candidate_id])->count(); 
                      if($report_count > 0){
                        $report_items= DB::table('report_items')->where('jaf_id',$jaf_id)->first();
                        if($report_items){
                          File::copy(public_path('/uploads/jaf-files/'.$filename),public_path('/uploads/report-files/'.$filename)); 
                          if(stripos($file_platform,'s3')!==false)
                          {
                            $path = 'uploads/jaf-files/';
                            $report_path = 'uploads/report-files/';
                            if(!Storage::disk('s3')->exists($report_path))
                            {
                                Storage::disk('s3')->makeDirectory($report_path,0777, true, true);
                            }
                            Storage::disk('s3')->copy($path.$filename,$report_path.$filename);
                          }
                          // $attachment= DB::table('report_items')->where('jaf_id',$jaf_id)->first();
                            DB::table('report_item_attachments')->insert([
                            'report_id'        => $report_items->report_id, 
                            'report_item_id'   => $report_items->id,
                            'jaf_item_attachment_id'=>$rowID,
                            'file_name'        => $filename,
                            'attachment_type'  => $type,
                            'file_platform'    => $file_platform,
                            'created_by'       => Auth::user()->id,
                            'created_at'       => date('Y-m-d H:i:s'),
                            'service_attachment_id'=>$request->service_type,
                            'service_attachment_name'=>$request->attachment_name,
                            'is_temp'          => $is_temp,
                          ]);
                        }
                      }
                      if(stripos($file_platform,'s3')!==false)
                      {
                          if(File::exists(public_path('/uploads/jaf-files/'.$filename)))
                          {
                              File::delete(public_path('/uploads/jaf-files/'.$filename));
                          }
                      }
                    }

                    $type = url('/').'/admin/images/file.jpg';
                    
                      if($filename != NULL)
                      {
                          // if($ext == 'pdf')
                          // {
                          //   $type = url('/').'/admin/images/icon_pdf.png';
                          // } 
                          if($ext == 'doc' || $ext == 'docx')
                          {
                            $type = url('/').'/admin/images/icon_docx.png';
                          }
                          if($ext == 'xls' || $ext == 'xlsx' || $ext == 'csv')
                          {
                            $type = url('/').'/admin/images/icon_xlsx.png';
                          }
                          if($ext == 'pptx' || $ext == 'ppt')
                          {
                            $type = url('/').'/admin/images/icon_pptx.png';
                          }
                          if($ext == 'psd' || $ext == 'PSD')
                          {
                            $type = url('/').'/admin/images/icon_psd.png';
                          }
                          if($ext == 'jpg' || $ext == 'png' || $ext == 'jpeg' || $ext == 'gif' || $ext == 'JPG' || $ext == 'PNG' || $ext == 'JPEG'  || $ext == 'GIF')
                          { 
                            if(stripos($file_platform,'s3')!==false)
                            {
                                $filePath = 'uploads/jaf-files/';

                                $disk = Storage::disk('s3');

                                $command = $disk->getDriver()->getAdapter()->getClient()->getCommand('GetObject', [
                                    'Bucket'                     => Config::get('filesystems.disks.s3.bucket'),
                                    'Key'                        => $filePath.$filename,
                                    //'ResponseContentDisposition' => 'attachment;'//for download
                                ]);

                                $req = $disk->getDriver()->getAdapter()->getClient()->createPresignedRequest($command, '+10 minutes');

                                $type = (string)$req->getUri();  
                            }
                            else
                            {
                              $type = url('/').'/uploads/jaf-files/'.$filename;
                            } 
                          }
                          
                      }           
                      
                      //$files[] = ['filePrev'=> $type,'file_id'=>$rowID];

                      if($ext=='pdf')
                      {
                        $files[] = ['file_type'=>$ext,'filePrev'=> $file_url_array,'file_id'=>$file_id_array,'file_name'=>$file_name_array,'select_file'=>$request->select_file,'customeval'=>$request->attachment_name];
                      }
                      else
                      {
                        $files[] = ['file_type'=>$ext,'filePrev'=> $type,'file_id'=>$rowID,'file_name'=>$filename,'select_file'=>$request->select_file,'customeval'=>$request->attachment_name];
                      }

                      if(File::exists($dir.'tmp-files/'))
                      {
                          File::cleanDirectory($dir.'tmp-files/');
                      }

                    $i++;
            
              }
              else{
                  // Do something when it fails
                  return response()->json([
                      'fail' => true,
                      'errors' => 'File type error!'
                  ]);
              }

            }

              DB::commit();
              //send file response
              return response()->json([
                'fail' => false,
                'errors' => 'no',
                'data'=>$files
              ]);
          }
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        }
  
    }

     /**
     * remove a BGV Attachment File .
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function removeFile(Request $request)
    {        
       $id =  $request->input('file_id');

       $parent_id=Auth::user()->parent_id;

       DB::beginTransaction();
       try
       {
          // check if file is uploaded by an admin
          $jaf_file=DB::table('jaf_item_attachments')->where(['id'=>$id,'business_id'=>$parent_id])->first();

          if($jaf_file!=NULL)
          {
                return response()->json([
                  'fail' => true,
              ]);
          }

          $is_done = DB::table('jaf_item_attachments')->where('id',$id)->update(['is_deleted'=>'1','deleted_at'=>date('Y-m-d H:i:s'),'deleted_by'=>Auth::user()->id]);
          DB::table('report_item_attachments')->where('jaf_item_attachment_id',$id)->update(['is_deleted'=>'1','deleted_at'=>date('Y-m-d H:i:s'),'deleted_by'=>Auth::user()->id]);

          DB::commit();
          // Do something when it fails
          return response()->json([
              'fail' => false,
              'message' => 'File removed!'
          ]);
       }
       catch (\Exception $e) {
          DB::rollback();
          // something went wrong
          return $e;
      }
    }

    // Create Function for hold a candidate
    public function holdCandidate(Request $request)
    {
        $parent_id=Auth::user()->parent_id;
        $business=Auth::user()->business_id;
        $user_id=Auth::user()->id;
        $candidate_id = base64_decode($request->get('candidate_id'));  
        $business_id = base64_decode($request->get('business_id'));  
        // echo('abc');
        // dd($candidate_id);

        // DB::beginTransaction();
        // try
        // {
        //       $hold = new CandidateHoldStatus();
        //       $hold->business_id =$business_id;
        //       $hold->candidate_id =$candidate_id;
        //       $hold->status = '1';
        //       $hold->hold_by =Auth::user()->id;
        //       $hold->hold_at = date('Y-m-d H:i:s');
        //       $hold->save();
            
        //       $hold_data = CandidateHoldStatus::where(['candidate_id'=>$candidate_id,'business_id'=>$business_id,'hold_remove_at'=>null])->first();

        //       $hold_log_data=DB::table('candidate_hold_status_logs')->insert([
        //         'parent_id'=>$parent_id,
        //         'business_id'=> $business,
        //         'candidate_id' => $candidate_id,
        //         'user_id' => $user_id,
        //         'status' => 'hold',
        //         'created_at' => date('Y-m-d H:i:s'),
        //         'updated_at' => date('Y-m-d H:i:s')
        //       ]);
        //       if ($hold_data) {
        //         DB::commit();
        //         return response()->json([
        //           'status'=>'ok',
        //           'message' => 'Hold',
        //           ], 200);
        //       }else{
        //         return response()->json([
        //         'status' =>'no',
        //         ], 200);
        //       }
        // }
        // catch (\Exception $e) {
        //     DB::rollback();
        //     // something went wrong
        //     return $e;
        // }

        $candidate = DB::table('users')->where('id',$candidate_id)->first();

        $candidate_hold_logs = DB::table('candidate_hold_status_logs')->where('candidate_id',$candidate_id)->get();

        $viewRender = view('clients.candidates.hold-resume.index',compact('candidate_hold_logs','candidate'))->render();
        return response()->json(
                                array(
                                  'success' => true, 
                                  'candidate_name' => $candidate->name.' ('.$candidate->display_id.')',
                                  'html'=>$viewRender
                                )
                              );
      
    }

  // Update hold to resume a candidate
  // public function resumeCandidate(Request $request)
  // {
  //     $parent_id=Auth::user()->parent_id;
  //     $business=Auth::user()->business_id;
  //     $user_id=Auth::user()->id;
  //     $candidate_id = base64_decode($request->get('candidate_id'));  
  //     $business_id = base64_decode($request->get('business_id'));  
  //     // echo('abc');
  //     // dd($business_id);
  //     //  $hold = new CandidateHoldStatus();
  //     //  $hold->business_id =$business_id;
  //     //  $hold->candidate_id =$candidate_id;
  //     //  $hold->status = '1';
  //     //  $hold->hold_by =Auth::user()->id;
  //     //  $hold->hold_at = date('Y-m-d H:i:s');
  //     //  $hold->save();
  //     DB::beginTransaction();
  //     try
  //     {
  //       $hold_data = CandidateHoldStatus::where(['candidate_id'=>$candidate_id,'business_id'=>$business_id,'hold_remove_at'=>null])->update(['hold_remove_by'=>Auth::user()->id,'hold_remove_at'=>date('Y-m-d H:i:s')]);

  //       $hold_log_data=DB::table('candidate_hold_status_logs')->insert([
  //         'parent_id'=>$parent_id,
  //         'business_id'=> $business,
  //         'candidate_id' => $candidate_id,
  //         'user_id' => $user_id,
  //         'status' => 'removed',
  //         'created_at' => date('Y-m-d H:i:s'),
  //         'updated_at' => date('Y-m-d H:i:s')
  //         ]);
        
  //         if ($hold_data) {
  //           DB::commit();
  //           return response()->json([
  //             'status'=>'ok',
  //             'message' => 'removed',                
  //             ], 200);
  //         }else{
  //           return response()->json([
  //           'status' =>'no',
  //           ], 200);
  //       }
  //     }
  //     catch (\Exception $e) {
  //         DB::rollback();
  //         // something went wrong
  //         return $e;
  //     } 
  // }

}
