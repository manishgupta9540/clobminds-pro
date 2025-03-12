<?php

namespace App\Listeners;

use App\Events\ApiCheckByRestApi;
use App\Models\Admin\AadharCheck;
use App\Models\Admin\AadharCheckMaster;
use App\Models\Admin\JafFormData;
use App\Models\Admin\KeyAccountManager;
use App\Models\Admin\PanCheck;
use App\Models\Admin\PanCheckMaster;
use App\Models\Admin\RcCheck;
use App\Models\Admin\RcCheckMaster;
use App\Models\Admin\Task;
use App\Models\Admin\TaskAssignment;
use App\Models\Admin\VoterIdCheck;
use App\Models\Admin\VoterIdCheckMaster;
use App\VerificationInsufficiency;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Traits\EmailConfigTrait;
use Illuminate\Support\Facades\Config;


class ApiCheckByRestApiListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ApiCheckByRestApi  $event
     * @return void
     */
    public function handle(ApiCheckByRestApi $event)
    {
        
        $userDT   =  DB::table('users')->where(['id'=>$event->userID])->first();
        $super_parent_id=0;
        $parent_id=$userDT->parent_id;
        $business_id=$userDT->business_id;
        $user_id=$userDT->id;
        $user_type=$userDT->user_type;
        // dd($user_type);
        if($userDT->user_type=='user' || $userDT->user_type=='User')
        {
            $users=DB::table('users')->select('parent_id','user_type')->where('id',$business_id)->first();
            $parent_id=$users->parent_id;
            $user_type=$users->user_type;
          
        }

        if($user_type=='client')
        {
            $admin_data=DB::table('users')->where('id',$parent_id)->first();
            $super_parent_id=$admin_data->parent_id;
        }

        if (isset($event->jfd_service) && count($event->jfd_service)) {
            foreach ($event->jfd_service as $service) {
              if($service->is_insufficiency==0)
              {
                $price=20;

                // check price based on user_type
                if($user_type=='customer')
                {
                  $checkprice_db=DB::table('check_price_masters')
                  ->select('price')
                  ->where('service_id',$service->service_id)
                  ->where('business_id',$parent_id)
                  ->first();
                    if($checkprice_db!=NULL)
                    {
                      $price=$checkprice_db->price;
                    }
                }
              else if($user_type=='client')
                {
                  $data = DB::table('check_price_cocs')->where(['service_id'=>$service->service_id,'coc_id'=>$business_id])->first();
                  if($data!=NULL)
                  {
                      $price=$data->price;
                  }
                  else{
                      $data=DB::table('check_prices')->where(['service_id'=>$service->service_id,'business_id'=>$parent_id])->first();
                      if($data!=NULL)
                      {
                          $price=$data->price;
                      }
                      else{
                          $data=DB::table('check_price_masters')->where(['service_id'=>$service->service_id])->first();
                          if($data!=NULL)
                          {
                              $price=$data->price;
                          }
                      }
                  }
                }
                // dd($service->service_id);
                if($service->service_id == 2){

                  $jaf_aadhaar = DB::table('jaf_form_data')->select('*')->where(['id'=>$service->id])->first();
                  $jaf_array = json_decode($jaf_aadhaar->form_data, true);
                  // print_r($jaf_array);
                  $aaddhaar_number ="";
                  foreach($jaf_array as $input){
                      if(array_key_exists('Aadhar Number',$input)){
                        $aaddhaar_number = $input['Aadhar Number'];
                      }
                  }
                  //check first into master table
                  $master_data = DB::table('aadhar_check_masters')->select('*')->where(['aadhar_number'=>$aaddhaar_number])->first();
                  // dd($master_data);
                  if($master_data !=null){
                    //update case
                    DB::table('jaf_form_data')->where(['id'=>$service->id])
                    ->update(['is_api_checked'=>'1','is_api_verified'=>'1','verification_status'=>'success','verified_at'=>date('Y-m-d H:i:s'),'is_insufficiency'=>'0','clear_insuff_notes'=>'Auto Check aadhar cleared','is_all_insuff_cleared' => 1,'insuff_cleared_by'=>$userDT->id,'updated_at'=>date('Y-m-d H:i:s')]); 
                  
                    $check_data = [
                      'parent_id'         =>$parent_id,
                      'business_id'       =>$business_id,
                      'service_id'        =>$service->service_id,
                      'candidate_id'      =>$service->candidate_id,
                      'source_reference'  =>'SystemDB',
                      'aadhar_number'     =>$master_data->aadhar_number,
                      'age_range'         =>$master_data->age_range,
                      'gender'            =>$master_data->gender,
                      'state'             =>$master_data->state,
                      'last_digit'        =>$master_data->last_digit,
                      'is_verified'       =>'1',
                      'is_aadhar_exist'   =>'1',
                      'price'             =>$price,
                      'used_by'           =>$user_type=='candidate'?'coc':'customer',
                      'user_id'            => $user_id,
                      'created_at'        =>date('Y-m-d H:i:s')
                      ];
                      AadharCheck::create($check_data);
                      // DB::table('aadhar_checks')->insert($check_data);

                      $ver_insuff=DB::table('verification_insufficiency')->where(['jaf_form_data_id'=>$service->id,'service_id'=>$service->service_id,'status'=>'removed'])->first();

                      if($ver_insuff!=NULL)
                      {
                          $ver_insuff_data=[
                            'notes' => 'Auto check aadhar cleared',
                            'updated_by' => $userDT->id,
                            'updated_at' => date('Y-m-d H:i:s')
                          ];

                          $ver_insuff_id=   DB::table('verification_insufficiency')->where(['jaf_form_data_id'=>$service->id,'service_id'=>$service->service_id,'status'=>'removed'])->first();
                          $update_ver_insuff = VerificationInsufficiency::find($ver_insuff_id->id);
                          $update_ver_insuff->update($ver_insuff_data);

                          $ver_id=$ver_insuff->id;

                      }
                      else
                      {
                        $ver_insuff_data=[
                          'parent_id' => $user_type=='candidate'?$super_parent_id:$parent_id,
                          'business_id' => $user_type=='candidate'?$parent_id:$business_id,
                          'coc_id' => $jaf_aadhaar->business_id,
                          'candidate_id' => $service->candidate_id,
                          'service_id'  => $jaf_aadhaar->service_id,
                          'jaf_form_data_id' => $jaf_aadhaar->id,
                          'item_number' => $jaf_aadhaar->check_item_number,
                          'activity_type'=> 'jaf-save',
                          'status'=>'removed',
                          'notes' => 'Auto check aadhar cleared',
                          'created_by'   => $userDT->id,
                          'created_at'   => date('Y-m-d H:i:s'),
                        ];
                  
                        $ver_id=VerificationInsufficiency::create($ver_insuff_data);
                        $ver_id=$ver_id->id;
                        // DB::table('verification_insufficiency')->insertGetId($ver_insuff_data);
                      }

                      $insuff_log_data=[
                        'parent_id' => $user_type=='candidate'?$super_parent_id:$parent_id,
                        'business_id' => $user_type=='candidate'?$parent_id:$business_id,
                        'coc_id' => $service->business_id,
                        'candidate_id' => $service->candidate_id,
                        'service_id'  => $service->service_id,
                        'jaf_form_data_id' => $service->id,
                        'item_number' => $service->check_item_number,
                        'activity_type'=> 'jaf-save',
                        'status'=>'removed',
                        'notes' => 'Auto check aadhar cleared',
                        'created_by'   => $userDT->id,
                        'user_type'           =>$user_type=='candidate'?'coc':'customer',
                        'created_at'   => date('Y-m-d H:i:s'),
                      ];
                
                      DB::table('insufficiency_logs')->insert($insuff_log_data);
 
                          // // Old Task update
                          // $task = Task::where(['business_id'=>$service->business_id,'candidate_id'=>$service->candidate_id,'is_completed'=>0,'service_id'=>$service->service_id])->first();
                          //     if ($task) {
                          //       # code...
                              
                          //         $task->is_completed= 1;
                          //         $task->save();
                          //     }
                          //     //Change status of old task 
                          //     $task_assgn = TaskAssignment::where(['business_id'=>$service->business_id,'candidate_id'=>$service->candidate_id,'status'=>"1"])->first();
                          //     // dd($task_assgn);
                          //     if($task_assgn){
                          //     $task_assgn->status= '2';
                          //     $task_assgn->save();
                          //     }

                      $ver_insuff=DB::table('verification_insufficiency')->where(['id'=>$ver_id])->first();

                      $candidates=DB::table('users as u')
                          ->select('u.*','j.business_id as coc_id','j.id as jaf_id','v.created_at as insuff_clear_date','v.created_by as insuff_clear_by','v.item_number','v.notes','s.verification_type','s.name as service_name','v.business_id as cust_id','v.attachment','v.updated_by','v.updated_at')
                          ->join('jaf_form_data as j','u.id','=','j.candidate_id')
                          ->join('verification_insufficiency as v','v.jaf_form_data_id','=','j.id')
                          ->join('services as s','s.id','=','v.service_id')
                          ->where(['u.user_type'=>'candidate','j.id'=>$service->id,'v.status'=>'removed','v.id'=>$ver_insuff->id])
                          ->first();

                      if($candidates!=NULL)
                      {
                        // $client=DB::table('users')->where(['id'=>$candidates->coc_id])->first();
                        // $name = $client->name;
                        // $email = $client->email;
                        // $msg= "Insufficiency Cleared For Candidate";
                        // $sender = DB::table('users')->where(['id'=>$candidates->parent_id])->first();
                        // $data  = array('name'=>$name,'email'=>$email,'msg'=>$msg,'link'=>'','candidate'=>$candidates,'sender'=>$sender);
        
                        // Mail::send(['html'=>'mails.insuff-clear-notify'], $data, function($message) use($email,$name) {
                        //   $message->to($email, $name)->subject
                        //       ('Clobminds System - Insufficiency Notification');
                        //   $message->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
                        // });

                        $kams = DB::table('key_account_managers')->where(['business_id'=>$candidates->business_id])->get();
                        if(count($kams)>0)
                        {
                          foreach($kams as $kam)
                          {
                              $user_data=DB::table('users')->where(['id'=>$kam->user_id])->first();

                              $name1 = $user_data->name;
                              $email1 = $user_data->email;
                              $msg= "Insufficiency Cleared For Candidate";

                              $sender = DB::table('users')->where(['id'=>$candidates->parent_id])->first();
                              $data  = array('name'=>$name1,'email'=>$email1,'msg'=>$msg,'link'=>'','candidate'=>$candidates,'sender'=>$sender);
                              // EmailConfigTrait::emailConfig();
                              //get Mail config data
                                //   $mail =null;
                                $mail= Config::get('mail');
                                // dd($mail['from']['address']);
                                // if (count($mail)>0) {
                                //     Mail::send(['html'=>'mails.insuff-clear-notify'], $data, function($message) use($email1,$name1,$mail) {
                                //         $message->to($email1, $name1)->subject
                                //         ('Clobminds System - Insufficiency Notification ');
                                //         $message->from($mail['from']['address'],$mail['from']['name']);
                                //     });
                                //   }else {
                                      Mail::send(['html'=>'mails.insuff-clear-notify'], $data, function($message) use($email1,$name1) {
                                          $message->to($email1, $name1)->subject
                                              ('Clobminds System - Insufficiency Notification');
                                          $message->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
                                      });
                                  // }

                          }
                        }

                      }

                  }
                  else{
                    //check from live API
                    $api_check_status = false;
                    // Setup request to send json via POST
                    $data = array(
                        'id_number'    => $aaddhaar_number,
                        'async'         => true,
                    );
                    $payload = json_encode($data);
                    $apiURL = "https://kyc-api.aadhaarkyc.io/api/v1/aadhaar-validation/aadhaar-validation";
      
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt ( $ch, CURLOPT_POST, 1 );
                    $authorization = "Authorization: Bearer ".env('SUREPASS_PRODUCTION_TOKEN');
                   // $authorization = "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2MDcxNzI1MDIsIm5iZiI6MTYwNzE3MjUwMiwianRpIjoiZTA5YTc5MmEtMGQ5ZC00N2RjLTk1MTAtMzg4M2E3ODYxZDczIiwiZXhwIjoxOTIyNTMyNTAyLCJpZGVudGl0eSI6ImRldi50YWd3b3JsZEBhYWRoYWFyYXBpLmlvIiwiZnJlc2giOmZhbHNlLCJ0eXBlIjoiYWNjZXNzIiwidXNlcl9jbGFpbXMiOnsic2NvcGVzIjpbInJlYWQiXX19.0Ufgl7uOeTG7QVLvRR4VkRZMT06GsiGiK44jFa9-gdw"; // Prepare the authorisation token
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization )); // Inject the token into the header
                    curl_setopt($ch, CURLOPT_URL, $apiURL);
                    // Attach encoded JSON string to the POST fields
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
                    $resp = curl_exec ( $ch );
                    curl_close ( $ch );
                    
                    $array_data =  json_decode($resp,true);
      
                    if($array_data['success'])
                    {
                        $master_data ="";
                        //check if ID number is new then insert into DB
                        $checkIDInDB= DB::table('aadhar_check_masters')->where(['aadhar_number'=>$aaddhaar_number])->count();
                        if($checkIDInDB ==0)
                        {
                            $gender = 'Male';
                            if($array_data['data']['gender'] == 'F'){
                                $gender = 'Female';
                            }
                            $data = ['aadhar_number'    =>$array_data['data']['aadhaar_number'],
                                    'age_range'         =>$array_data['data']['age_range'],
                                    'gender'            =>$gender,
                                    'state'             =>$array_data['data']['state'],
                                    'last_digit'        =>$array_data['data']['last_digits'],
                                    'is_api_verified'       =>'1',
                                    'is_aadhar_exist'   =>'1',
                                    'created_at'        =>date('Y-m-d H:i:s')
                                    ];
                                    
                                  AadharCheckMaster::create($data);
                              // DB::table('aadhar_check_masters')->insert($data);
                        }
                                    
                            //insert into business table
                            $check_data = [
                                    'parent_id'         =>$parent_id,
                                    'business_id'       =>$business_id,
                                    'candidate_id'      =>$service->candidate_id,
                                    'service_id'        =>$service->service_id,
                                    'aadhar_number'     =>$array_data['data']['aadhaar_number'],
                                    'age_range'         =>$array_data['data']['age_range'],
                                    'gender'            =>$gender,
                                    'state'             =>$array_data['data']['state'],
                                    'last_digit'        =>$array_data['data']['last_digits'],
                                    'is_verified'       =>'1',
                                    'is_aadhar_exist'   =>'1',
                                    'price'             =>$price,
                                    'used_by'           =>$user_type=='candidate'?'coc':'customer',
                                    'user_id'            => $user_id,
                                    'created_at'        =>date('Y-m-d H:i:s')
                                    ];
                                    AadharCheck::create($check_data);

                            // DB::table('aadhar_checks')->insert($check_data);
                            
                            $master_data = DB::table('aadhar_check_masters')->select('*')->where(['aadhar_number'=>$aaddhaar_number])->first();
                            // update the status
                            $update_jfd = JafFormData::find($service->id);
                         
                            $update_jfd->update(['is_api_checked'=>'1','is_api_verified'=>'1','is_insufficiency'=>'0','verification_status'=>'success','verified_at'=>date('Y-m-d H:i:s'),'is_insufficiency'=>'0','clear_insuff_notes'=>'Auto check aadhar cleared','is_all_insuff_cleared' => 1,'insuff_cleared_by'=>$userDT->id,'updated_at'=>date('Y-m-d H:i:s')]); 
                          // DB::table('jaf_form_data')->where(['id'=>$service->id])
                          $ver_insuff=DB::table('verification_insufficiency')->where(['jaf_form_data_id'=>$service->id,'service_id'=>$service->service_id,'status'=>'removed'])->first();

                          if($ver_insuff!=NULL)
                          {
                              $ver_insuff_data=[
                                'notes' => 'Auto Check Aadhaar Cleared',
                                'updated_by' => $userDT->id,
                                'updated_at' => date('Y-m-d H:i:s')
                              ];

                            $ver_insuff_id=  DB::table('verification_insufficiency')->where(['jaf_form_data_id'=>$service->id,'service_id'=>$service->service_id,'status'=>'removed'])->first();
                            $update_ver_insuff= VerificationInsufficiency::find($ver_insuff_id->id);  
                            $update_ver_insuff->update($ver_insuff_data);

                              $ver_id=$ver_insuff->id;
                              
                          }
                          else
                          {
                            $ver_insuff_data=[
                              'parent_id' => $user_type=='candidate'?$super_parent_id:$parent_id,
                              'business_id' => $user_type=='candidate'?$parent_id:$business_id,
                              'coc_id' => $jaf_aadhaar->business_id,
                              'candidate_id' => $service->candidate_id,
                              'service_id'  => $jaf_aadhaar->service_id,
                              'jaf_form_data_id' => $jaf_aadhaar->id,
                              'item_number' => $jaf_aadhaar->check_item_number,
                              'activity_type'=> 'jaf-save',
                              'status'=>'removed',
                              'notes' => 'Auto check aadhar cleared',
                              'created_by'   => $userDT->id,
                              'created_at'   => date('Y-m-d H:i:s'),
                              'updated_at' => date('Y-m-d H:i:s')
                            ];
                      
                            $ver_id= VerificationInsufficiency::create($ver_insuff_data);
                            $ver_id=$ver_id->id;
                            // DB::table('verification_insufficiency')->insertGetId($ver_insuff_data);
                          }

                          $insuff_log_data=[
                            'parent_id' => $user_type=='candidate'?$super_parent_id:$parent_id,
                            'business_id' => $user_type=='candidate'?$parent_id:$business_id,
                            'coc_id' => $jaf_aadhaar->business_id,
                            'candidate_id' => $service->candidate_id,
                            'service_id'  => $jaf_aadhaar->service_id,
                            'jaf_form_data_id' => $jaf_aadhaar->id,
                            'item_number' => $jaf_aadhaar->check_item_number,
                            'activity_type'=> 'jaf-save',
                            'status'=>'removed',
                            'notes' => 'Auto check aadhar cleared',
                            'created_by'   => $userDT->id,
                            'user_type'           =>$user_type=='candidate'?'coc':'customer',
                            'created_at'   => date('Y-m-d H:i:s'),
                          ];
                    
                          DB::table('insufficiency_logs')->insert($insuff_log_data);
                           
                       
                          $task = Task::where(['business_id'=>$service->business_id,'candidate_id'=>$service->candidate_id,'is_completed'=>0,'description'=>'BGV Filling'])->first();
                          $task_id='';
                          if ($task) {
                            # code...
                            $task_id = $task->id;

                              $task_update = Task::find($task_id);
                              $task_update->update(['is_completed'=> 1]);
                              // $task->is_completed= 1;
                              // $task->save();
                          
                            //Change status of old task 
                            $task_assgn = TaskAssignment::where(['business_id'=>$service->business_id,'candidate_id'=>$service->candidate_id,'status'=>"1",'task_id'=>$task_id])->first();
                            // dd($task_assgn);
                            if($task_assgn)
                            {
                              $task_assign_update = TaskAssignment::find($task_assgn->id);
                                $task_assign_update->update(['status'=> '2']);
                              // $task_assgn->status= '2';
                              // $task_assgn->save();
                            }
                          }
      
                          $ver_insuff=DB::table('verification_insufficiency')->where(['id'=>$ver_id])->first();
      
                          $candidates=DB::table('users as u')
                              ->select('u.*','j.business_id as coc_id','j.id as jaf_id','v.created_at as insuff_clear_date','v.created_by as insuff_clear_by','v.item_number','v.notes','s.verification_type','s.name as service_name','v.business_id as cust_id','v.attachment','v.updated_by','v.updated_at')
                              ->join('jaf_form_data as j','u.id','=','j.candidate_id')
                              ->join('verification_insufficiency as v','v.jaf_form_data_id','=','j.id')
                              ->join('services as s','s.id','=','v.service_id')
                              ->where(['u.user_type'=>'candidate','j.id'=>$service->id,'v.status'=>'removed','v.id'=>$ver_insuff->id])
                              ->first();
                          // dd($candidates);
                          if($candidates!=NULL)
                          {
                            // $client=DB::table('users')->where(['id'=>$candidates->coc_id])->first();
                            // $name = $client->name;
                            // $email = $client->email;
                            // $msg= "Insufficiency Cleared For Candidate";
                            // $sender = DB::table('users')->where(['id'=>$candidates->parent_id])->first();
                            // $data  = array('name'=>$name,'email'=>$email,'msg'=>$msg,'link'=>'','candidate'=>$candidates,'sender'=>$sender);
            
                            // Mail::send(['html'=>'mails.insuff-clear-notify'], $data, function($message) use($email,$name) {
                            //   $message->to($email, $name)->subject
                            //       ('Clobminds System - Insufficiency Notification');
                            //   $message->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
                            // });
      
                            $kams = DB::table('key_account_managers')->where(['business_id'=>$candidates->business_id])->get();
                            if(count($kams)>0)
                            {
                              foreach($kams as $kam)
                              {
                                  $user_data=DB::table('users')->where(['id'=>$kam->user_id])->first();
      
                                  $name1 = $user_data->name;
                                  $email1 = $user_data->email;
                                  $msg= "Insufficiency Cleared For Candidate";
                                  $sender = DB::table('users')->where(['id'=>$candidates->parent_id])->first();
                                  
                                  $data  = array('name'=>$name1,'email'=>$email1,'msg'=>$msg,'link'=>'','candidate'=>$candidates,'sender'=>$sender);
      
                                  // EmailConfigTrait::emailConfig();
                                  //get Mail config data
                                    //   $mail =null;
                                    $mail= Config::get('mail');
                                    // dd($mail['from']['address']);
                                    // if (count($mail)>0) {
                                    //     Mail::send(['html'=>'mails.insuff-clear-notify'], $data, function($message) use($email1,$name1,$mail) {
                                    //         $message->to($email1, $name1)->subject
                                    //         ('Clobminds System - Insufficiency Notification ');
                                    //         $message->from($mail['from']['address'],$mail['from']['name']);
                                    //     });
                                    // }else {
                                        Mail::send(['html'=>'mails.insuff-clear-notify'], $data, function($message) use($email1,$name1) {
                                            $message->to($email1, $name1)->subject
                                                ('Clobminds System - Insufficiency Notification');
                                            $message->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
                                        });
                                    // }
      
                              }
                            }
      
                          }
                        
                    
                    }
                    // Api  Status Failed Start
                    else{ 
                        //update insuff
                       $jaf_update= JafFormData::find($service->id);
                        // DB::table('jaf_form_data')->where(['id'=>$service->id])
                        $jaf_update->update(['is_api_checked'=>'1','is_api_verified'=>'0','is_insufficiency'=>'1','verification_status'=>'failed','verified_at'=>date('Y-m-d H:i:s')]); 
              
                        $jaf_data=DB::table('jaf_form_data')->where(['id'=>$service->id])->first();
                        $insuff_log_data=[
                          'parent_id' => $user_type=='candidate'?$super_parent_id:$parent_id,
                          'business_id' => $user_type=='candidate'?$parent_id:$business_id,
                          'coc_id' => $jaf_aadhaar->business_id,
                          'candidate_id' => $service->candidate_id,
                          'service_id'  => $jaf_aadhaar->service_id,
                          'jaf_form_data_id' => $jaf_aadhaar->id,
                          'item_number' => $jaf_aadhaar->check_item_number,
                          'activity_type'=> 'jaf-save',
                          'status'=>'failed',
                          'notes' => 'Auto check aadhar failed',
                          'created_by'   => $userDT->id,
                          'user_type' =>$user_type=='candidate'?'coc':'customer',
                          'created_at'   => date('Y-m-d H:i:s'),
                        ];
                  
                        DB::table('insufficiency_logs')->insert($insuff_log_data);

                        $ver_insuff=DB::table('verification_insufficiency')->where(['jaf_form_data_id'=>$service->id,'service_id'=>$service->service_id,'status'=>'raised'])->first();

                          if($ver_insuff!=NULL)
                          {
                              $ver_insuff_data=[
                                'notes' => 'Auto check aadhar failed',
                                'updated_by' => $userDT->id,
                                'updated_at' => date('Y-m-d H:i:s')
                              ];

                              $ver_insuff_id=  DB::table('verification_insufficiency')->where(['jaf_form_data_id'=>$service->id,'service_id'=>$service->service_id,'status'=>'raised'])->first();
                              $update_ver_insuff= VerificationInsufficiency::find($ver_insuff_id->id);  
                              $update_ver_insuff->update($ver_insuff_data);
  
                              // DB::table('verification_insufficiency')->where(['jaf_form_data_id'=>$service->id,'service_id'=>$service->service_id,'status'=>'raised'])->update($ver_insuff_data);

                              $ver_id=$ver_insuff->id;
                          }
                          else
                          {
                            $ver_insuff_data=[
                              'parent_id' => $user_type=='candidate'?$super_parent_id:$parent_id,
                              'business_id' => $user_type=='candidate'?$parent_id:$business_id,
                              'coc_id' => $service->business_id,
                              'candidate_id' => $service->candidate_id,
                              'service_id'  => $service->service_id,
                              'jaf_form_data_id' => $service->id,
                              'item_number' => $service->check_item_number,
                              'activity_type'=> 'jaf-save',
                              'status'=>'raised',
                              'notes' => 'Auto check aadhar failed',
                              'created_by'   => $userDT->id,
                              'created_at'   => date('Y-m-d H:i:s'),
                            ];
                      
                            $ver_id= VerificationInsufficiency::create($ver_insuff_data);
                            $ver_id=$ver_id->id;
                            // $ver_id = DB::table('verification_insufficiency')->insertGetId($ver_insuff_data);
                          }
                        
                          // Task insuff raised and assign to  CAM

                          $task = Task::where(['business_id'=>$service->business_id,'candidate_id'=>$service->candidate_id,'is_completed'=>0,'description'=>'BGV Filling'])->first();
                          $task_id='';
                          if ($task) {
                            # code...
                            $task_id = $task->id;
                                 $task_update = Task::find($task_id);
                              $task_update->update(['is_completed'=> 1]);
                              // $task->is_completed= 1;
                              // $task->save();
                          
                          //Change status of old task 
                          $task_assgn = TaskAssignment::where(['business_id'=>$service->business_id,'candidate_id'=>$service->candidate_id,'status'=>"1",'task_id'=>$task_id])->first();
                          // dd($task_assgn);
                          if($task_assgn)
                          { 
                            $task_assign_update = TaskAssignment::find($task_assgn->id);
                              $task_assign_update->update(['status'=> '2']);
                            // $task_assgn->status= '2';
                            // $task_assgn->save();
                          }
                        }
                          // task assign start
                          $final_users = [];
                          // $j = 0;
                          $job_sla_item = DB::table('job_sla_items')->where(['candidate_id'=>$service->candidate_id,'service_id'=>$service->service_id])->first();
                          // dd($job_sla_item);
                          // foreach ($job_sla_items as $job_sla_item) {
                            if ($job_sla_item) {
                              # code...
                          
                              $kam  = KeyAccountManager::where(['business_id'=>$service->business_id,'is_primary'=>'1'])->first();
                              // dd( $kam);
                              if ($kam) {
                                # code...
                             
                                  $final_users = [];
                                  $numbers_of_items = $job_sla_item->number_of_verifications;
                                  if($numbers_of_items > 0){
                                    for ($i=1; $i <= $numbers_of_items; $i++) { 
                                      
                                      $final_users = [];
                                      $user_name='';
                                      $user_name = DB::table('users')->where('id',$service->candidate_id)->first();
                                      // dd($user); 
                                      //insert in task
                                        $data = [
                                          'name'          => $user_name->first_name.' '.$user_name->last_name,
                                          'parent_id'=> $user_name->parent_id,
                                          'business_id'   => $service->business_id, 
                                          'description'   => 'Task for Verification ',
                                          'job_id'        => NULL, 
                                          'priority'      => 'normal',
                                          'candidate_id'  => $service->candidate_id,   
                                          'service_id'    => $job_sla_item->service_id, 
                                          'number_of_verifications' => $i,
                                          'assigned_to'   => $kam->user_id,
                                          'assigned_by'   => $userDT->id,
                                          'assigned_at'   => date('Y-m-d H:i:s'),
                                          'start_date'    => date('Y-m-d'),
                                          'created_by'    => $userDT->id,
                                          'created_at'    => date('Y-m-d H:i:s'),
                                          'is_completed'  => 0,
                                          // 'started_at'    => date('Y-m-d H:i:s')
                                        ];
                                        // // dd($data);
                                        $task_id = Task::create($data); 
                                        $task_id = $task_id->id;
                                        // DB::table('tasks')->insertGetId($data); 
            
                                        $taskdata = [
                                          'parent_id'=> $user_name->parent_id,
                                          'business_id'   => $service->business_id,
                                          'candidate_id'  =>$service->candidate_id,   
                                          'job_sla_item_id'  => $job_sla_item->id,
                                          'task_id'       => $task_id,
                                         'user_id'       =>  $kam->user_id,
                                          'service_id'    =>$job_sla_item->service_id,
                                          'number_of_verifications' => $i,
                                          'status'=>'1',
                                          'created_at'    => date('Y-m-d H:i:s')  
                                        ];
                                        TaskAssignment::create($taskdata);
                                        // DB::table('task_assignments')->insertGetId($taskdata); 
                                        // DB::table('task_assignments')->insertGetId($taskdata); 
                                    }
                                  }
                                }
                            }
                                       
                    }
                      
      
                  }

                }
                elseif($service->service_id==3)
                {
                  $jaf_pan = DB::table('jaf_form_data')->select('*')->where(['id'=>$service->id])->first();
          
                  $pan_number = "";
                  // $business_id = $jaf_pan->business_id; 
                  $jaf_array = json_decode($jaf_pan->form_data, true);
                  // print_r($jaf_array);
                  foreach($jaf_array as $input){
                      if(array_key_exists('PAN Number',$input)){
                        $pan_number = $input['PAN Number'];
                      }
                  }

                  $master_data = DB::table('pan_check_masters')->select('*')->where(['pan_number'=>$pan_number])->first();
                
                  if($master_data !=null){
                      // update the status
                      $jaf_update= JafFormData::find($service->id);
                      // DB::table('jaf_form_data')->where(['id'=>$service->id])
                      $jaf_update->update(['is_api_checked'=>'1','is_api_verified'=>'1','verification_status'=>'success','verified_at'=>date('Y-m-d H:i:s'),'is_insufficiency'=>'0','clear_insuff_notes'=>'Auto Check PAN cleared','is_all_insuff_cleared' => 1,'insuff_cleared_by'=>$userDT->id,'updated_at'=>date('Y-m-d H:i:s')]); 
            
                      // DB::table('jaf_form_data')->where(['id'=>$service->id])
                      // ->update(['is_api_checked'=>'1','is_api_verified'=>'1','verification_status'=>'success','verified_at'=>date('Y-m-d H:i:s'),'is_insufficiency'=>'0','clear_insuff_notes'=>'Auto Check PAN cleared','is_all_insuff_cleared' => 1,'insuff_cleared_by'=>$userDT->id,'updated_at'=>date('Y-m-d H:i:s')]);
                      $is_updated=TRUE;

                      $data = [
                        'parent_id'         =>$parent_id,
                        'category'          =>$master_data->category,
                        'pan_number'        =>$master_data->pan_number,
                        'full_name'         =>$master_data->full_name,
                        'is_verified'       =>'1',
                        'is_pan_exist'      =>'1',
                        'business_id'       => $business_id,
                        'candidate_id'      => $service->candidate_id,
                        'service_id'        => $service->service_id,
                        'source_type'       =>'SystemDb',
                        'price'             =>$price,
                        'used_by'           =>$user_type=='candidate'?'coc':'customer',
                        'user_id'           => $user_id,
                        'created_at'=>date('Y-m-d H:i:s')
                        ];
                
                        PanCheck::create($data);
                      // DB::table('pan_checks')->insert($data);

                      $ver_insuff=DB::table('verification_insufficiency')->where(['jaf_form_data_id'=>$service->id,'service_id'=>$service->service_id,'status'=>'removed'])->first();

                      if($ver_insuff!=NULL)
                      {
                          $ver_insuff_data=[
                            'notes' => 'Auto Check PAN cleared',
                            'updated_by' => $userDT->id,
                            'updated_at' => date('Y-m-d H:i:s')
                          ];
                          $ver_insuff_id=  DB::table('verification_insufficiency')->where(['jaf_form_data_id'=>$service->id,'service_id'=>$service->service_id,'status'=>'removed'])->first();
                          $update_ver_insuff= VerificationInsufficiency::find($ver_insuff_id->id);  
                          $update_ver_insuff->update($ver_insuff_data);

                          // DB::table('verification_insufficiency')->where(['jaf_form_data_id'=>$service->id,'service_id'=>$service->service_id,'status'=>'removed'])->update($ver_insuff_data);
                
                          $ver_id=$ver_insuff->id;
                      }
                      else
                      {
                        $ver_insuff_data=[
                          'parent_id' => $user_type=='candidate'?$super_parent_id:$parent_id,
                          'business_id' => $user_type=='candidate'?$parent_id:$business_id,
                          'coc_id' => $jaf_pan->business_id,
                          'candidate_id' => $service->candidate_id,
                          'service_id'  => $jaf_pan->service_id,
                          'item_number' => $jaf_pan->check_item_number,
                          'jaf_form_data_id' => $jaf_pan->id,
                          'activity_type'=> 'jaf-save',
                          'status'=>'removed',
                          'notes' => 'Auto Check PAN cleared',
                          'created_by'   => $userDT->id,
                          'created_at'   => date('Y-m-d H:i:s'),
                        ];
                  
                        $ver_id=VerificationInsufficiency::create($ver_insuff_data);
                        $ver_id=$ver_id->id;
                        // DB::table('verification_insufficiency')->insertGetId($ver_insuff_data);
                      }

                      $insuff_log_data=[
                        'parent_id' => $user_type=='candidate'?$super_parent_id:$parent_id,
                        'business_id' => $user_type=='candidate'?$parent_id:$business_id,
                        'coc_id' => $jaf_pan->business_id,
                        'candidate_id' => $service->candidate_id,
                        'service_id'  => $jaf_pan->service_id,
                        'jaf_form_data_id' => $jaf_pan->id,
                        'item_number' => $jaf_pan->check_item_number,
                        'activity_type'=> 'jaf-save',
                        'status'=>'removed',
                        'notes' => 'Auto Check PAN cleared',
                        'created_by'   => $userDT->id,
                        'user_type'           =>$user_type=='candidate'?'coc':'customer',
                        'created_at'   => date('Y-m-d H:i:s'),
                      ];
                
                      DB::table('insufficiency_logs')->insert($insuff_log_data);
                
                      $task = Task::where(['business_id'=>$service->business_id,'candidate_id'=>$service->candidate_id,'is_completed'=>0,'service_id'=>$service->service_id])->first();
                      if ($task) {
                        # code...
                       $task_id = $task->id;
                                 $task_update = Task::find($task_id);
                              $task_update->update(['is_completed'=> 1]);
                          // $task->is_completed= 1;
                          // $task->save();
                      }
                      //Change status of old task 
                      $task_assgn = TaskAssignment::where(['business_id'=>$service->business_id,'candidate_id'=>$service->candidate_id,'status'=>"1"])->first();
                      // dd($task_assgn);
                      if($task_assgn){
                        $task_assign_update = TaskAssignment::find($task_assgn->id);
                        $task_assign_update->update(['status'=> '2']);
                      // $task_assgn->status= '2';
                      // $task_assgn->save();
                      }

                      $ver_insuff=DB::table('verification_insufficiency')->where(['id'=>$ver_id])->first();

                      $candidates=DB::table('users as u')
                          ->select('u.*','j.business_id as coc_id','j.id as jaf_id','v.created_at as insuff_clear_date','v.created_by as insuff_clear_by','v.item_number','v.notes','s.verification_type','s.name as service_name','v.business_id as cust_id','v.attachment','v.updated_by','v.updated_at')
                          ->join('jaf_form_data as j','u.id','=','j.candidate_id')
                          ->join('verification_insufficiency as v','v.jaf_form_data_id','=','j.id')
                          ->join('services as s','s.id','=','v.service_id')
                          ->where(['u.user_type'=>'candidate','j.id'=>$service->id,'v.status'=>'removed','v.id'=>$ver_insuff->id])
                          ->first();

                      if($candidates!=NULL)
                      {
                        // $client=DB::table('users')->where(['id'=>$candidates->coc_id])->first();
                        // $name = $client->name;
                        // $email = $client->email;
                        // $msg= "Insufficiency Cleared For Candidate";
                        // $sender = DB::table('users')->where(['id'=>$candidates->parent_id])->first();
                        // $data  = array('name'=>$name,'email'=>$email,'msg'=>$msg,'link'=>'','candidate'=>$candidates,'sender'=>$sender);
        
                        // Mail::send(['html'=>'mails.insuff-clear-notify'], $data, function($message) use($email,$name) {
                        //   $message->to($email, $name)->subject
                        //       ('Clobminds System - Insufficiency Notification');
                        //   $message->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
                        // });

                        $kams = DB::table('key_account_managers')->where(['business_id'=>$candidates->business_id])->get();
                        if(count($kams)>0)
                        {
                          foreach($kams as $kam)
                          {
                              $user_data=DB::table('users')->where(['id'=>$kam->user_id])->first();

                              $name1 = $user_data->name;
                              $email1 = $user_data->email;
                              $msg= "Insufficiency Cleared For Candidate";
                              $sender = DB::table('users')->where(['id'=>$candidates->parent_id])->first();
                              $data  = array('name'=>$name1,'email'=>$email1,'msg'=>$msg,'link'=>'','candidate'=>$candidates,'sender'=>$sender);
                              // EmailConfigTrait::emailConfig();
                              //get Mail config data
                                //   $mail =null;
                                $mail= Config::get('mail');
                                // dd($mail['from']['address']);
                                //  if (count($mail)>0) {
                                //     Mail::send(['html'=>'mails.insuff-clear-notify'], $data, function($message) use($email1,$name1,$mail) {
                                //         $message->to($email1, $name1)->subject
                                //         ('Clobminds System - Insufficiency Notification ');
                                //         $message->from($mail['from']['address'],$mail['from']['name']);
                                //     });
                                //   }else {
                                    Mail::send(['html'=>'mails.insuff-clear-notify'], $data, function($message) use($email1,$name1) {
                                        $message->to($email1, $name1)->subject
                                            ('Clobminds System - Insufficiency Notification');
                                        $message->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
                                    });
                                //  }

                          }
                        }

                      }
      
                  }
                  else{
                    //check from live API
                    $api_check_status = false;
                    // Setup request to send json via POST
                    $data = array(
                        'id_number'    => $pan_number,
                        'async'         => true,
                    );
                    $payload = json_encode($data);
                    $apiURL = "https://kyc-api.aadhaarkyc.io/api/v1/pan/pan";
    
                    $ch = curl_init();                
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                
                    curl_setopt ( $ch, CURLOPT_POST, 1 );
                    $authorization = "Authorization: Bearer ".env('SUREPASS_PRODUCTION_TOKEN');
                    //$authorization = "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2MDcxNzI1MDIsIm5iZiI6MTYwNzE3MjUwMiwianRpIjoiZTA5YTc5MmEtMGQ5ZC00N2RjLTk1MTAtMzg4M2E3ODYxZDczIiwiZXhwIjoxOTIyNTMyNTAyLCJpZGVudGl0eSI6ImRldi50YWd3b3JsZEBhYWRoYWFyYXBpLmlvIiwiZnJlc2giOmZhbHNlLCJ0eXBlIjoiYWNjZXNzIiwidXNlcl9jbGFpbXMiOnsic2NvcGVzIjpbInJlYWQiXX19.0Ufgl7uOeTG7QVLvRR4VkRZMT06GsiGiK44jFa9-gdw"; // Prepare the authorisation token
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization )); // Inject the token into the header
                    curl_setopt($ch, CURLOPT_URL, $apiURL);
                    // Attach encoded JSON string to the POST fields
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    
                    $resp = curl_exec ( $ch );
                    curl_close ( $ch );
                    
                    $array_data =  json_decode($resp,true);
                    // print_r($array_data); die;
                    if($array_data['success'])
                    {
                        //check if ID number is new then insert into DB
                        $checkIDInDB= DB::table('pan_check_masters')->where(['pan_number'=>$pan_number])->count();
                        if($checkIDInDB ==0)
                        {
                            $data = [
                                    'category'=>$array_data['data']['category'],
                                    'pan_number'=>$array_data['data']['pan_number'],
                                    'full_name'=>$array_data['data']['full_name'],
                                    'is_api_verified'=>'1',
                                    'is_pan_exist'=>'1',
                                    'created_at'=>date('Y-m-d H:i:s')
                                    ];
                                    PanCheckMaster::create($data);
                            // DB::table('pan_check_masters')->insert($data);

                            //store log
                            $data = [
                              'parent_id'         =>$parent_id,
                              'category'          =>$array_data['data']['category'],
                              'pan_number'        =>$array_data['data']['pan_number'],
                              'full_name'         =>$array_data['data']['full_name'],
                              'is_verified'       =>'1',
                              'is_pan_exist'      =>'1',
                              'business_id'       =>$business_id,
                              'candidate_id' => $service->candidate_id,
                              'service_id'        => $service->service_id,
                              'source_type'       =>'API',
                              'price'             =>$price,
                              'used_by'           =>$user_type=='candidate'?'coc':'customer',
                              'user_id'            => $user_id,
                              'created_at'=>date('Y-m-d H:i:s')
                              ];
                          PanCheck::create($data);
                          // DB::table('pan_checks')->insert($data);
                            
                        }
                        $jaf_update= JafFormData::find($service->id);
                        // DB::table('jaf_form_data')->where(['id'=>$service->id])
                        $jaf_update->update(['is_api_checked'=>'1','is_api_verified'=>'1','verification_status'=>'success','verified_at'=>date('Y-m-d H:i:s'),'is_insufficiency'=>'0','clear_insuff_notes'=>'Auto Check PAN Cleared','is_all_insuff_cleared' => 1,'insuff_cleared_by'=>$userDT->id,'updated_at'=>date('Y-m-d H:i:s')]); 
              
                        // update the status
                        // DB::table('jaf_form_data')->where(['id'=>$service->id])
                        // ->update(['is_api_checked'=>'1','is_api_verified'=>'1','verification_status'=>'success','verified_at'=>date('Y-m-d H:i:s'),'is_insufficiency'=>'0','clear_insuff_notes'=>'Auto Check PAN Cleared','is_all_insuff_cleared' => 1,'insuff_cleared_by'=>$userDT->id,'updated_at'=>date('Y-m-d H:i:s')]); 


                        $ver_insuff=DB::table('verification_insufficiency')->where(['jaf_form_data_id'=>$service->id,'service_id'=>$service->service_id,'status'=>'removed'])->first();

                        if($ver_insuff!=NULL)
                        {
                            $ver_insuff_data=[
                              'notes' => 'Auto Check PAN cleared',
                              'updated_by' => $userDT->id,
                              'updated_at' => date('Y-m-d H:i:s')
                            ];

                            // DB::table('verification_insufficiency')->where(['jaf_form_data_id'=>$service->id,'service_id'=>$service->service_id,'status'=>'removed'])->update($ver_insuff_data);
                            $ver_insuff_id=  DB::table('verification_insufficiency')->where(['jaf_form_data_id'=>$service->id,'service_id'=>$service->service_id,'status'=>'removed'])->first();
                          $update_ver_insuff= VerificationInsufficiency::find($ver_insuff_id->id);  
                          $update_ver_insuff->update($ver_insuff_data);
                            $ver_id=$ver_insuff->id;
                        }
                        else
                        {
                          $ver_insuff_data=[
                            'parent_id' => $user_type=='candidate'?$super_parent_id:$parent_id,
                            'business_id' => $user_type=='candidate'?$parent_id:$business_id,
                            'coc_id' => $jaf_pan->business_id,
                            'candidate_id' => $service->candidate_id,
                            'service_id'  => $jaf_pan->service_id,
                            'jaf_form_data_id' => $jaf_pan->id,
                            'item_number' => $jaf_pan->check_item_number,
                            'activity_type'=> 'jaf-save',
                            'status'=>'removed',
                            'notes' => 'Auto Check PAN cleared',
                            'created_by'   => $userDT->id,
                            'created_at'   => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s')
                          ];
                          $ver_id=VerificationInsufficiency::create($ver_insuff_data);
                          $ver_id=$ver_id->id;
                          // $ver_id=DB::table('verification_insufficiency')->insertGetId($ver_insuff_data);

                        }

                        $insuff_log_data=[
                          'parent_id' => $user_type=='candidate'?$super_parent_id:$parent_id,
                          'business_id' => $user_type=='candidate'?$parent_id:$business_id,
                          'coc_id' => $jaf_pan->business_id,
                          'candidate_id' => $service->candidate_id,
                          'service_id'  => $jaf_pan->service_id,
                          'jaf_form_data_id' => $jaf_pan->id,
                          'item_number' => $jaf_pan->check_item_number,
                          'activity_type'=> 'jaf-save',
                          'status'=>'removed',
                          'notes' => 'Auto Check PAN cleared',
                          'created_by'   => $userDT->id,
                          'user_type'           =>$user_type=='candidate'?'coc':'customer',
                          'created_at'   => date('Y-m-d H:i:s'),
                        ];
                       
                        DB::table('insufficiency_logs')->insert($insuff_log_data);
                  

                       
                        $task = Task::where(['business_id'=>$service->business_id,'candidate_id'=>$service->candidate_id,'is_completed'=>0,'description'=>'BGV Filling'])->first();
                        $task_id='';
                        if ($task) {
                          # code...
                          $task_id = $task->id;
                          //  $task_id = $task->id;
                                 $task_update = Task::find($task_id);
                              $task_update->update(['is_completed'=> 1]);
                            // $task->is_completed= 1;
                            // $task->save();
                        
                        //Change status of old task 
                        $task_assgn = TaskAssignment::where(['business_id'=>$service->business_id,'candidate_id'=>$service->candidate_id,'status'=>"1",'task_id'=>$task_id])->first();
                        // dd($task_assgn);
                        if($task_assgn)
                        { 
                          $task_assign_update = TaskAssignment::find($task_assgn->id);
                          $task_assign_update->update(['status'=> '2']);
                          // $task_assgn->status= '2';
                          // $task_assgn->save();
                        }
                      }

                        $ver_insuff=DB::table('verification_insufficiency')->where(['id'=>$ver_id])->first();

                        $candidates=DB::table('users as u')
                            ->select('u.*','j.business_id as coc_id','j.id as jaf_id','v.created_at as insuff_clear_date','v.created_by as insuff_clear_by','v.item_number','v.notes','s.verification_type','s.name as service_name','v.business_id as cust_id','v.attachment','v.updated_by','v.updated_at')
                            ->join('jaf_form_data as j','u.id','=','j.candidate_id')
                            ->join('verification_insufficiency as v','v.jaf_form_data_id','=','j.id')
                            ->join('services as s','s.id','=','v.service_id')
                            ->where(['u.user_type'=>'candidate','j.id'=>$service->id,'v.status'=>'removed','v.id'=>$ver_insuff->id])
                            ->first();

                        if($candidates!=NULL)
                        {
                          // $client=DB::table('users')->where(['id'=>$candidates->coc_id])->first();
                          // $name = $client->name;
                          // $email = $client->email;
                          // $msg= "Insufficiency Cleared For Candidate";
                          // $sender = DB::table('users')->where(['id'=>$candidates->parent_id])->first();
                          // $data  = array('name'=>$name,'email'=>$email,'msg'=>$msg,'link'=>'','candidate'=>$candidates,'sender'=>$sender);
          
                          // Mail::send(['html'=>'mails.insuff-clear-notify'], $data, function($message) use($email,$name) {
                          //   $message->to($email, $name)->subject
                          //       ('Clobminds System - Insufficiency Notification');
                          //   $message->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
                          // });

                          $kams = DB::table('key_account_managers')->where(['business_id'=>$candidates->business_id])->get();
                          if(count($kams)>0)
                          {
                            foreach($kams as $kam)
                            {
                                $user_data=DB::table('users')->where(['id'=>$kam->user_id])->first();

                                $name1 = $user_data->name;
                                $email1 = $user_data->email;
                                $msg= "Insufficiency Cleared For Candidate";
                                $sender = DB::table('users')->where(['id'=>$candidates->parent_id])->first();
                                $data  = array('name'=>$name1,'email'=>$email1,'msg'=>$msg,'link'=>'','candidate'=>$candidates,'sender'=>$sender);
                                // EmailConfigTrait::emailConfig();
                                //get Mail config data
                                  //   $mail =null;
                                  $mail= Config::get('mail');
                                  // dd($mail['from']['address']);
                                  // if (count($mail)>0) {
                                  //     Mail::send(['html'=>'mails.insuff-clear-notify'], $data, function($message) use($email1,$name1,$mail) {
                                  //         $message->to($email1, $name1)->subject
                                  //         ('Clobminds System - Insufficiency Notification ');
                                  //         $message->from($mail['from']['address'],$mail['from']['name']);
                                  //     });
                                  // }else {
                                      Mail::send(['html'=>'mails.insuff-clear-notify'], $data, function($message) use($email1,$name1) {
                                          $message->to($email1, $name1)->subject
                                              ('Clobminds System - Insufficiency Notification');
                                          $message->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
                                      });
                                  // }

                            }
                          }


                        }
    
    
                    }
                    else{
                        //update insuff
                        $jaf_update= JafFormData::find($service->id);
                        // DB::table('jaf_form_data')->where(['id'=>$service->id])
                        $jaf_update->update(['is_api_checked'=>'1','is_api_verified'=>'0','is_insufficiency'=>'1','verification_status'=>'failed','verified_at'=>date('Y-m-d H:i:s')]); 
              
                        // DB::table('jaf_form_data')->where(['id'=>$service->id])->update(['is_api_checked'=>'1','is_api_verified'=>'0','is_insufficiency'=>'1','verification_status'=>'failed','verified_at'=>date('Y-m-d H:i:s')]); 

                        $jaf_data=DB::table('jaf_form_data')->where(['id'=>$service->id])->first();

                        $insuff_log_data=[
                          'parent_id' => $user_type=='candidate'?$super_parent_id:$parent_id,
                          'business_id' => $user_type=='candidate'?$parent_id:$business_id,
                          'coc_id' => $jaf_pan->business_id,
                          'candidate_id' => $service->candidate_id,
                          'service_id'  => $jaf_pan->service_id,
                          'jaf_form_data_id' => $jaf_pan->id,
                          'item_number' => $jaf_pan->check_item_number,
                          'activity_type'=> 'jaf-save',
                          'status'=>'failed',
                          'notes' => 'Auto Check PAN failed',
                          'created_by'   => $userDT->id,
                          'user_type'=>$user_type=='candidate'?'coc':'customer',
                          'created_at'   => date('Y-m-d H:i:s'),
                        ];
                  
                        DB::table('insufficiency_logs')->insert($insuff_log_data);

                        $ver_insuff=DB::table('verification_insufficiency')->where(['jaf_form_data_id'=>$service->id,'service_id'=>$service->service_id,'status'=>'raised'])->first();

                          if($ver_insuff!=NULL)
                          {
                              $ver_insuff_data=[
                                'notes' => 'Auto check PAN failed',
                                'updated_by' => $userDT->id,
                                'updated_at' => date('Y-m-d H:i:s')
                              ];

                              $ver_insuff_id=  DB::table('verification_insufficiency')->where(['jaf_form_data_id'=>$service->id,'service_id'=>$service->service_id,'status'=>'raised'])->first();
                              $update_ver_insuff= VerificationInsufficiency::find($ver_insuff_id->id);  
                              $update_ver_insuff->update($ver_insuff_data);
                              // DB::table('verification_insufficiency')->where(['jaf_form_data_id'=>$service->id,'service_id'=>$service->service_id,'status'=>'raised'])->update($ver_insuff_data);

                              $ver_id=$ver_insuff->id;
                          }
                          else
                          {
                            $ver_insuff_data=[
                              'parent_id' => $user_type=='candidate'?$super_parent_id:$parent_id,
                              'business_id' => $user_type=='candidate'?$parent_id:$business_id,
                              'coc_id' => $service->business_id,
                              'candidate_id' => $service->candidate_id,
                              'service_id'  => $service->service_id,
                              'jaf_form_data_id' => $service->id,
                              'item_number' => $service->check_item_number,
                              'activity_type'=> 'jaf-save',
                              'status'=>'raised',
                              'notes' => 'Auto check PAN failed',
                              'created_by'   => $userDT->id,
                              'created_at'   => date('Y-m-d H:i:s'),
                            ];
                            
                            $ver_id =VerificationInsufficiency::create($ver_insuff_data);
                            //  DB::table('verification_insufficiency')->insertGetId($ver_insuff_data);
                          }
                          // Task insuff raised and assign to  CAM

                          $task = Task::where(['business_id'=>$service->business_id,'candidate_id'=>$service->candidate_id,'is_completed'=>0,'description'=>'BGV Filling'])->first();
                          $task_id='';
                          if ($task) {
                            # code...
                            $task_id = $task->id;
                           
                              $task_update = Task::find($task_id);
                              $task_update->update(['is_completed'=> 1]);
                              // $task->is_completed= 1;
                              // $task->save();
                          
                          //Change status of old task 
                          $task_assgn = TaskAssignment::where(['business_id'=>$service->business_id,'candidate_id'=>$service->candidate_id,'status'=>"1",'task_id'=>$task_id])->first();
                          // dd($task_assgn);
                          if($task_assgn)
                          { 
                            $task_assign_update = TaskAssignment::find($task_assgn->id);
                            $task_assign_update->update(['status'=> '2']);
                            // $task_assgn->status= '2';
                            // $task_assgn->save();
                          }
                        }
                          // task assign start
                          $final_users = [];
                          // $j = 0;
                          $job_sla_item = DB::table('job_sla_items')->where(['candidate_id'=>$service->candidate_id,'service_id'=>$service->service_id])->first();

                          // foreach ($job_sla_items as $job_sla_item) {
                            if ($job_sla_item) {
                              # code...
                          
                              $kam  = KeyAccountManager::where(['business_id'=>$service->business_id,'is_primary'=>'1'])->first();
                              if ($kam) {
                                # code...
                              
                                  $final_users = [];
                                  $numbers_of_items = $job_sla_item->number_of_verifications;
                                  if($numbers_of_items > 0){
                                    for ($i=1; $i <= $numbers_of_items; $i++) { 
                                      
                                      $final_users = [];
                                      $user_name='';
                                      $user_name = DB::table('users')->where('id',$service->candidate_id)->first();
                                        //insert in task
                                        $data = [
                                          'name'          =>$user_name->first_name.' '.$user_name->last_name,
                                          'parent_id'=> $user_name->parent_id,
                                          'business_id'   => $service->business_id, 
                                          'description'   => 'Task for Verification ',
                                          'job_id'        => NULL, 
                                          'priority'      => 'normal',
                                          'candidate_id'  => $service->candidate_id,   
                                          'service_id'    => 3, 
                                          'number_of_verifications' => $i,
                                          'assigned_to'   => $kam->user_id,
                                          'assigned_by'   => $userDT->id,
                                          'assigned_at'   => date('Y-m-d H:i:s'),
                                          'start_date'    => date('Y-m-d'),
                                          'created_by'    => $userDT->id,
                                          'created_at'    => date('Y-m-d H:i:s'),
                                          'is_completed'  => 0,
                                          // 'started_at'    => date('Y-m-d H:i:s')
                                        ];
                                         $task_id = Task::create($data); 
                                         $task_id = $task_id->id;
                                        // $task_id =  DB::table('tasks')->insertGetId($data); 
                                        // dd($task_id);
                                        $taskdata = [
                                          'parent_id'=> $user_name->parent_id,
                                          'business_id'   => $service->business_id,
                                          'candidate_id'  =>$service->candidate_id,   
                                          'job_sla_item_id'  => $job_sla_item->id,
                                          'task_id'       => $task_id,
                                          'user_id'       =>  $kam->user_id,
                                          'service_id'    =>$job_sla_item->service_id,
                                          'number_of_verifications' => $i,
                                          'status'=>'1',
                                          'created_at'    => date('Y-m-d H:i:s')  
                                        ];
                                         TaskAssignment::create($taskdata);
                                      
                                        // DB::table('task_assignments')->insertGetId($taskdata); 
                                    }
                                  }
                                }
                            }
                                        
                                                  
                    }

                    
                    
                  }

                }
                elseif($service->service_id==4)
                {
                  $jaf_voterid = DB::table('jaf_form_data')->select('*')->where(['id'=>$service->id])->first();

                    $voterid_number = "";
                    // $business_id = $jaf_voterid->business_id; 
                    $jaf_array = json_decode($jaf_voterid->form_data, true);
                    // print_r($jaf_array);
                    foreach($jaf_array as $input){
                        if(array_key_exists('Voter ID Number',$input)){
                          $voterid_number = $input['Voter ID Number'];
                        }
                    }

                    //check first into master table
                    $master_data = DB::table('voter_id_check_masters')->select('*')->where(['voter_id_number'=>$voterid_number])->first();
                    if($master_data !=null){
                      $data = $master_data;
                      // update the status
                      $jaf_update= JafFormData::find($service->id);
                      // DB::table('jaf_form_data')->where(['id'=>$service->id])
                      $jaf_update->update(['is_api_checked'=>'1','is_api_verified'=>'1','verification_status'=>'success','verified_at'=>date('Y-m-d H:i:s'),'is_insufficiency'=>'0','clear_insuff_notes'=>'Auto Check Voter ID cleared','is_all_insuff_cleared' => 1,'insuff_cleared_by'=>$userDT->id,'updated_at'=>date('Y-m-d H:i:s')]); 
            

                      // DB::table('jaf_form_data')->where(['id'=>$service->id])
                      // ->update(['is_api_checked'=>'1','is_api_verified'=>'1','verification_status'=>'success','verified_at'=>date('Y-m-d H:i:s'),'is_insufficiency'=>'0','clear_insuff_notes'=>'Auto Check Voter ID cleared','is_all_insuff_cleared' => 1,'insuff_cleared_by'=>$userDT->id,'updated_at'=>date('Y-m-d H:i:s')]); 
                      // return response()->json([
                      //     'fail'      =>false,
                      //     'data'      =>$master_data 
                      // ]);

                      $log_data = [
                        'parent_id'         =>$parent_id,
                        'api_client_id'     =>$master_data->api_client_id,
                        'relation_type'     =>$master_data->relation_type,
                        'voter_id_number'   =>$master_data->voter_id_number,
                        'relation_name'     =>$master_data->relation_name,
                        'full_name'         =>$master_data->full_name,
                        'gender'            =>$master_data->gender,
                        'age'               =>$master_data->age,
                        'dob'               =>$master_data->dob,
                        'house_no'          =>$master_data->house_no,
                        'area'              =>$master_data->area,
                        'state'             =>$master_data->state,
                        'is_verified'       =>'1',
                        'is_voter_id_exist' =>'1',
                        'business_id'       =>$business_id,
                        'candidate_id' => $service->candidate_id,
                        'service_id'        =>$service->service_id,
                        'source_reference'  =>'SystemDb',
                        'price'             =>$price,
                        'used_by'           =>$user_type=='candidate'?'coc':'customer',
                        'user_id'            => $user_id,
                        'created_at'        =>date('Y-m-d H:i:s')
                        ];
                        VoterIdCheck::create($log_data);
                      // DB::table('voter_id_checks')->insert($log_data);

                      $ver_insuff=DB::table('verification_insufficiency')->where(['jaf_form_data_id'=>$service->id,'service_id'=>$service->service_id,'status'=>'removed'])->first();
                      if($ver_insuff!=NULL)
                      {
                          $ver_insuff_data=[
                            'notes' => 'Auto Check Voter ID cleared',
                            'updated_by' => $userDT->id,
                            'updated_at' => date('Y-m-d H:i:s')
                          ];

                          $ver_insuff_id=  DB::table('verification_insufficiency')->where(['jaf_form_data_id'=>$service->id,'service_id'=>$service->service_id,'status'=>'removed'])->first();
                          $update_ver_insuff= VerificationInsufficiency::find($ver_insuff_id->id);  
                          $update_ver_insuff->update($ver_insuff_data);
                          // DB::table('verification_insufficiency')->where(['jaf_form_data_id'=>$service->id,'service_id'=>$service->service_id,'status'=>'removed'])->update($ver_insuff_data);

                          $ver_id=$ver_insuff->id;
                      }
                      else
                      {
                        $ver_insuff_data=[
                          'parent_id' => $user_type=='candidate'?$super_parent_id:$parent_id,
                          'business_id' => $user_type=='candidate'?$parent_id:$business_id,
                          'coc_id' => $jaf_voterid->business_id,
                          'candidate_id' => $service->candidate_id,
                          'service_id'  => $jaf_voterid->service_id,
                          'jaf_form_data_id' => $jaf_voterid->id,
                          'item_number' => $jaf_voterid->check_item_number,
                          'activity_type'=> 'jaf-save',
                          'status'=>'removed',
                          'notes' => 'Auto Check Voter ID cleared',
                          'created_by'   => $userDT->id,
                          'created_at'   => date('Y-m-d H:i:s'),
                        ];
                  
                        $ver_id=VerificationInsufficiency::create($ver_insuff_data);
                        $ver_id=$ver_id->id;
                        // DB::table('verification_insufficiency')->insertGetId($ver_insuff_data);
                      }

                      $insuff_log_data=[
                        'parent_id' => $user_type=='candidate'?$super_parent_id:$parent_id,
                        'business_id' => $user_type=='candidate'?$parent_id:$business_id,
                        'coc_id' => $jaf_voterid->business_id,
                        'candidate_id' => $service->candidate_id,
                        'service_id'  => $jaf_voterid->service_id,
                        'jaf_form_data_id' => $jaf_voterid->id,
                        'item_number' => $jaf_voterid->check_item_number,
                        'activity_type'=> 'jaf-save',
                        'status'=>'removed',
                        'notes' => 'Auto Check Voter ID cleared',
                        'created_by'   => $userDT->id,
                        'user_type'           =>$user_type=='candidate'?'coc':'customer',
                        'created_at'   => date('Y-m-d H:i:s'),
                      ];
                
                      DB::table('insufficiency_logs')->insert($insuff_log_data);


                      $task = Task::where(['business_id'=>$service->business_id,'candidate_id'=>$service->candidate_id,'is_completed'=>0,'description'=>'BGV Filling'])->first();
                      $task_id='';
                      if ($task) {
                        # code...
                        $task_id = $task->id;
                          $task_update = Task::find($task_id);
                              $task_update->update(['is_completed'=> 1]);
                          // $task->is_completed= 1;
                          // $task->save();
                      
                      //Change status of old task 
                      $task_assgn = TaskAssignment::where(['business_id'=>$service->business_id,'candidate_id'=>$service->candidate_id,'status'=>"1",'task_id'=>$task_id])->first();
                      // dd($task_assgn);
                      if($task_assgn)
                      {
                            $task_assign_update = TaskAssignment::find($task_assgn->id);
                            $task_assign_update->update(['status'=> '2']);
                        // $task_assgn->status= '2';
                        // $task_assgn->save();
                      }
                    }

                      $ver_insuff=DB::table('verification_insufficiency')->where(['id'=>$ver_id])->first();

                      $candidates=DB::table('users as u')
                          ->select('u.*','j.business_id as coc_id','j.id as jaf_id','v.created_at as insuff_clear_date','v.created_by as insuff_clear_by','v.item_number','v.notes','s.verification_type','s.name as service_name','v.business_id as cust_id','v.attachment','v.updated_by','v.updated_at')
                          ->join('jaf_form_data as j','u.id','=','j.candidate_id')
                          ->join('verification_insufficiency as v','v.jaf_form_data_id','=','j.id')
                          ->join('services as s','s.id','=','v.service_id')
                          ->where(['u.user_type'=>'candidate','j.id'=>$service->id,'v.status'=>'removed','v.id'=>$ver_insuff->id])
                          ->first();

                      if($candidates!=NULL)
                      {
                        // $client=DB::table('users')->where(['id'=>$candidates->coc_id])->first();
                        // $name = $client->name;
                        // $email = $client->email;
                        // $msg= "Insufficiency Cleared For Candidate";
                        // $sender = DB::table('users')->where(['id'=>$candidates->parent_id])->first();
                        // $data  = array('name'=>$name,'email'=>$email,'msg'=>$msg,'link'=>'','candidate'=>$candidates,'sender'=>$sender);
        
                        // Mail::send(['html'=>'mails.insuff-clear-notify'], $data, function($message) use($email,$name) {
                        //   $message->to($email, $name)->subject
                        //       ('Clobminds System - Insufficiency Notification');
                        //   $message->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
                        // });

                        $kams = DB::table('key_account_managers')->where(['business_id'=>$candidates->business_id])->get();
                        if(count($kams)>0)
                        {
                          foreach($kams as $kam)
                          {
                              $user_data=DB::table('users')->where(['id'=>$kam->user_id])->first();

                              $name1 = $user_data->name;
                              $email1 = $user_data->email;
                              $msg= "Insufficiency Cleared For Candidate";
                              $sender = DB::table('users')->where(['id'=>$candidates->parent_id])->first();
                              
                              $data  = array('name'=>$name1,'email'=>$email1,'msg'=>$msg,'link'=>'','candidate'=>$candidates,'sender'=>$sender);
                              // EmailConfigTrait::emailConfig();
                              //get Mail config data
                                //   $mail =null;
                                $mail= Config::get('mail');
                                // dd($mail['from']['address']);
                                // if (count($mail)>0) {
                                //     Mail::send(['html'=>'mails.insuff-clear-notify'], $data, function($message) use($email1,$name1,$mail) {
                                //         $message->to($email1, $name1)->subject
                                //         ('Clobminds System - Insufficiency Notification');
                                //         $message->from($mail['from']['address'],$mail['from']['name']);
                                //     });
                                //   }else {
                                    Mail::send(['html'=>'mails.insuff-clear-notify'], $data, function($message) use($email1,$name1) {
                                        $message->to($email1, $name1)->subject
                                            ('Clobminds System - Insufficiency Notification');
                                        $message->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
                                    });
                                  // }

                          }
                        }


                      }

                    }
                    else{
                      //check from live API
                      // Setup request to send json via POST
                      $data = array(
                          'id_number'    => $voterid_number,
                          'async'         => true,
                      );
                      $payload = json_encode($data);
                      $apiURL = "https://kyc-api.aadhaarkyc.io/api/v1/voter-id/voter-id";
      
                      $ch = curl_init();
                      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                
                      curl_setopt ( $ch, CURLOPT_POST, 1 );
                      $authorization = "Authorization: Bearer ".env('SUREPASS_PRODUCTION_TOKEN');
                      //$authorization = "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2MDcxNzI1MDIsIm5iZiI6MTYwNzE3MjUwMiwianRpIjoiZTA5YTc5MmEtMGQ5ZC00N2RjLTk1MTAtMzg4M2E3ODYxZDczIiwiZXhwIjoxOTIyNTMyNTAyLCJpZGVudGl0eSI6ImRldi50YWd3b3JsZEBhYWRoYWFyYXBpLmlvIiwiZnJlc2giOmZhbHNlLCJ0eXBlIjoiYWNjZXNzIiwidXNlcl9jbGFpbXMiOnsic2NvcGVzIjpbInJlYWQiXX19.0Ufgl7uOeTG7QVLvRR4VkRZMT06GsiGiK44jFa9-gdw"; // Prepare the authorisation token
                      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization )); // Inject the token into the header
                      curl_setopt($ch, CURLOPT_URL, $apiURL);
                      // Attach encoded JSON string to the POST fields
                      curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
                      $resp = curl_exec ( $ch );
                      curl_close ( $ch );
                      $array_data =  json_decode($resp,true);
                      // print_r($array_data); die;
      
                      if($array_data['success'])
                      {
                          //check if ID number is new then insert into DB
                          $checkIDInDB= DB::table('voter_id_check_masters')->where(['voter_id_number'=>$voterid_number])->count();
                          if($checkIDInDB ==0)
                          {
                              $gender = 'Male';
                              if($array_data['data']['gender'] == 'F'){
                                  $gender = 'Female';
                              }
                              //
                              $relation_type = NULL;
                              if($array_data['data']['relation_type'] == 'M'){
                                  $relation_type = 'Mother';
                              }
                              if($array_data['data']['relation_type'] == 'F'){
                                  $relation_type = 'Father';
                              }
                              if($array_data['data']['relation_type'] == 'W'){
                                  $relation_type = 'Wife';
                              }
                              if($array_data['data']['relation_type'] == 'H'){
                                  $relation_type = 'Husband';
                              }
      
                              $data = [
                                        'api_client_id'     =>$array_data['data']['client_id'],
                                        'relation_type'     =>$relation_type,
                                        'voter_id_number'   =>$array_data['data']['epic_no'],
                                        'relation_name'     =>$array_data['data']['relation_name'],
                                        'full_name'         =>$array_data['data']['name'],
                                        'gender'            =>$gender,
                                        'age'               =>$array_data['data']['age'],
                                        'dob'               =>$array_data['data']['dob'],
                                        'house_no'          =>$array_data['data']['house_no'],
                                        'area'              =>$array_data['data']['area'],
                                        'state'             =>$array_data['data']['state'],
                                        'is_api_verified'   =>'1',
                                        'is_voter_id_exist' =>'1',
                                        'created_at'        =>date('Y-m-d H:i:s')
                              ];
                              VoterIdCheckMaster::create($data);
                              // DB::table('voter_id_check_masters')->insert($data);
                              
                              $master_data = DB::table('voter_id_check_masters')->select('*')->where(['voter_id_number'=>$voterid_number])->first();

                              //store log
                                $log_data = [
                                          'parent_id'         =>$parent_id,
                                          'api_client_id'     =>$array_data['data']['client_id'],
                                          'relation_type'     =>$relation_type,
                                          'voter_id_number'   =>$array_data['data']['epic_no'],
                                          'relation_name'     =>$array_data['data']['relation_name'],
                                          'full_name'         =>$array_data['data']['name'],
                                          'gender'            =>$gender,
                                          'age'               =>$array_data['data']['age'],
                                          'dob'               =>$array_data['data']['dob'],
                                          'house_no'          =>$array_data['data']['house_no'],
                                          'area'              =>$array_data['data']['area'],
                                          'state'             =>$array_data['data']['state'],
                                          'is_verified'       =>'1',
                                          'is_voter_id_exist' =>'1',
                                          'price'             =>$price,
                                          'business_id'       =>$business_id,
                                          'candidate_id' => $service->candidate_id,
                                          'service_id'        =>$service->service_id,
                                          'source_reference'  =>'API',
                                          'used_by'           =>$user_type=='candidate'?'coc':'customer',
                                          'user_id'            => $user_id,
                                          'created_at'        =>date('Y-m-d H:i:s')
                                ];
                                VoterIdCheck::create($log_data);
                              // DB::table('voter_id_checks')->insert($log_data);
                          }
      
                          // update the status
                          // DB::table('jaf_form_data')->where(['id'=>$item_id])->update(['is_api_checked'=>'1','is_api_verified'=>'1','verification_status'=>'success','verified_at'=>date('Y-m-d H:i:s')]); 
                          DB::table('jaf_form_data')->where(['id'=>$service->id])
                          ->update(['is_api_checked'=>'1','is_api_verified'=>'1','verification_status'=>'success','verified_at'=>date('Y-m-d H:i:s'),'is_insufficiency'=>'0','clear_insuff_notes'=>'Auto Check Voter ID Cleared','is_all_insuff_cleared' => 1,'insuff_cleared_by'=>$userDT->id,'updated_at'=>date('Y-m-d H:i:s')]); 
                          $is_updated=TRUE;

                          $ver_insuff=DB::table('verification_insufficiency')->where(['jaf_form_data_id'=>$service->id,'service_id'=>$service->service_id,'status'=>'removed'])->first();

                          if($ver_insuff!=NULL)
                          {
                              $ver_insuff_data=[
                                'notes' => 'Auto Check Voter ID cleared',
                                'updated_by' => $userDT->id,
                                'updated_at' => date('Y-m-d H:i:s')
                              ];
                              $ver_insuff_id=  DB::table('verification_insufficiency')->where(['jaf_form_data_id'=>$service->id,'service_id'=>$service->service_id,'status'=>'removed'])->first();
                              $update_ver_insuff= VerificationInsufficiency::find($ver_insuff_id->id);  
                              $update_ver_insuff->update($ver_insuff_data);
                              // DB::table('verification_insufficiency')->where(['jaf_form_data_id'=>$service->id,'service_id'=>$service->service_id,'status'=>'removed'])->update($ver_insuff_data);

                              $ver_id=$ver_insuff->id;
                          }
                          else
                          {
                            $ver_insuff_data=[
                              'parent_id' => $user_type=='candidate'?$super_parent_id:$parent_id,
                              'business_id' => $user_type=='candidate'?$parent_id:$business_id,
                              'coc_id' => $jaf_voterid->business_id,
                              'candidate_id' => $service->candidate_id,
                              'service_id'  => $jaf_voterid->service_id,
                              'jaf_form_data_id' => $jaf_voterid->id,
                              'item_number' => $jaf_voterid->check_item_number,
                              'activity_type'=> 'jaf-save',
                              'status'=>'removed',
                              'notes' => 'Auto Check Voter ID cleared',
                              'created_by'   => $userDT->id,
                              'created_at'   => date('Y-m-d H:i:s'),
                              'updated_at' => date('Y-m-d H:i:s')
                            ];
                      
                            $ver_id=VerificationInsufficiency::create($ver_insuff_data);
                            $ver_id=$ver_id->id;
                            // DB::table('verification_insufficiency')->insertGetId($ver_insuff_data);
                          }

                          $insuff_log_data=[
                            'parent_id' => $user_type=='candidate'?$super_parent_id:$parent_id,
                            'business_id' => $user_type=='candidate'?$parent_id:$business_id,
                            'coc_id' => $jaf_voterid->business_id,
                            'candidate_id' => $service->candidate_id,
                            'service_id'  => $jaf_voterid->service_id,
                            'jaf_form_data_id' => $jaf_voterid->id,
                            'item_number' => $jaf_voterid->check_item_number,
                            'activity_type'=> 'jaf-save',
                            'status'=>'removed',
                            'notes' => 'Auto Check Voter ID cleared',
                            'created_by'   => $userDT->id,
                            'user_type'           =>$user_type=='candidate'?'coc':'customer',
                            'created_at'   => date('Y-m-d H:i:s'),
                          ];
                    
                          DB::table('insufficiency_logs')->insert($insuff_log_data);

                          
                          $task = Task::where(['business_id'=>$service->business_id,'candidate_id'=>$service->candidate_id,'is_completed'=>0,'description'=>'BGV Filling'])->first();
                          $task_id='';
                          if ($task) {
                            # code...
                            $task_id = $task->id;
                              $task_update = Task::find($task_id);
                              $task_update->update(['is_completed'=> 1]);
                              // $task->is_completed= 1;
                              // $task->save();
                          
                          //Change status of old task 
                          $task_assgn = TaskAssignment::where(['business_id'=>$service->business_id,'candidate_id'=>$service->candidate_id,'status'=>"1",'task_id'=>$task_id])->first();
                          // dd($task_assgn);
                          if($task_assgn)
                          {

                            $task_assign_update = TaskAssignment::find($task_assgn->id);
                            $task_assign_update->update(['status'=> '2']);
                            // $task_assgn->status= '2';
                            // $task_assgn->save();
                          }
                        }
                          $ver_insuff=DB::table('verification_insufficiency')->where(['id'=>$ver_id])->first();

                          $candidates=DB::table('users as u')
                              ->select('u.*','j.business_id as coc_id','j.id as jaf_id','v.created_at as insuff_clear_date','v.created_by as insuff_clear_by','v.item_number','v.notes','s.verification_type','s.name as service_name','v.business_id as cust_id','v.attachment','v.updated_by','v.updated_at')
                              ->join('jaf_form_data as j','u.id','=','j.candidate_id')
                              ->join('verification_insufficiency as v','v.jaf_form_data_id','=','j.id')
                              ->join('services as s','s.id','=','v.service_id')
                              ->where(['u.user_type'=>'candidate','j.id'=>$service->id,'v.status'=>'removed','v.id'=>$ver_insuff->id])
                              ->first();

                          if($candidates!=NULL)
                          {
                            // $client=DB::table('users')->where(['id'=>$candidates->coc_id])->first();
                            // $name = $client->name;
                            // $email = $client->email;
                            // $msg= "Insufficiency Cleared For Candidate";
                            // $sender = DB::table('users')->where(['id'=>$candidates->parent_id])->first();
                            // $data  = array('name'=>$name,'email'=>$email,'msg'=>$msg,'link'=>'','candidate'=>$candidates,'sender'=>$sender);
            
                            // Mail::send(['html'=>'mails.insuff-clear-notify'], $data, function($message) use($email,$name) {
                            //   $message->to($email, $name)->subject
                            //       ('Clobminds System - Insufficiency Notification');
                            //   $message->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
                            // });

                            $kams = DB::table('key_account_managers')->where(['business_id'=>$candidates->business_id])->get();
                            if(count($kams)>0)
                            {
                              foreach($kams as $kam)
                              {
                                  $user_data=DB::table('users')->where(['id'=>$kam->user_id])->first();

                                  $name1 = $user_data->name;
                                  $email1 = $user_data->email;
                                  $msg= "Insufficiency Cleared For Candidate";
                                  $sender = DB::table('users')->where(['id'=>$candidates->parent_id])->first();
                                  $data  = array('name'=>$name1,'email'=>$email1,'msg'=>$msg,'link'=>'','candidate'=>$candidates,'sender'=>$sender);
                                  // EmailConfigTrait::emailConfig();
                                  //get Mail config data
                                    //   $mail =null;
                                    $mail= Config::get('mail');
                                    // dd($mail['from']['address']);
                                    // if (count($mail)>0) {
                                    //     Mail::send(['html'=>'mails.insuff-clear-notify'], $data, function($message) use($email1,$name1,$mail) {
                                    //         $message->to($email1, $name1)->subject
                                    //         ('Clobminds System - Insufficiency Notification');
                                    //         $message->from($mail['from']['address'],$mail['from']['name']);
                                    //     });
                                    // }else {
                                        Mail::send(['html'=>'mails.insuff-clear-notify'], $data, function($message) use($email1,$name1) {
                                            $message->to($email1, $name1)->subject
                                                ('Clobminds System - Insufficiency Notification');
                                            $message->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
                                        });
                                    // }

                              }
                            }


                          }


                          
                      }else{
                          //update insuff
                          $jaf_update= JafFormData::find($service->id);
                          // DB::table('jaf_form_data')->where(['id'=>$service->id])
                          $jaf_update->update(['is_api_checked'=>'1','is_api_verified'=>'0','is_insufficiency'=>'1','verification_status'=>'failed','verified_at'=>date('Y-m-d H:i:s')]); 
                          // DB::table('jaf_form_data')->where(['id'=>$service->id])->update(['is_api_checked'=>'1','is_api_verified'=>'0','is_insufficiency'=>'1','verification_status'=>'failed','verified_at'=>date('Y-m-d H:i:s')]);    

                          $jaf_data=DB::table('jaf_form_data')->where(['id'=>$service->id])->first();

                          $insuff_log_data=[
                                'parent_id' => $user_type=='candidate'?$super_parent_id:$parent_id,
                                'business_id' => $user_type=='candidate'?$parent_id:$business_id,
                                'coc_id' => $jaf_data->business_id,
                                'candidate_id' => $service->candidate_id,
                                'service_id'  => $jaf_data->service_id,
                                'jaf_form_data_id' => $jaf_data->id,
                                'item_number' => $jaf_data->check_item_number,
                                'activity_type'=> 'jaf-save',
                                'status'=>'failed',
                                'notes' => 'Auto Check Voter ID failed',
                                'created_by'   => $userDT->id,
                                'user_type'           =>$user_type=='candidate'?'coc':'customer',
                                'created_at'   => date('Y-m-d H:i:s'),
                          ];
                        
                              DB::table('insufficiency_logs')->insert($insuff_log_data);

                              $ver_insuff=DB::table('verification_insufficiency')->where(['jaf_form_data_id'=>$service->id,'service_id'=>$service->service_id,'status'=>'raised'])->first();

                              if($ver_insuff!=NULL)
                              {
                                  $ver_insuff_data=[ 
                                    'notes' => 'Auto check Voter ID failed',
                                    'updated_by' => $userDT->id,
                                    'updated_at' => date('Y-m-d H:i:s')
                                  ];
                                  $ver_insuff_id=  DB::table('verification_insufficiency')->where(['jaf_form_data_id'=>$service->id,'service_id'=>$service->service_id,'status'=>'raised'])->first();
                                  $update_ver_insuff= VerificationInsufficiency::find($ver_insuff_id->id);  
                                  $update_ver_insuff->update($ver_insuff_data);
                                  // DB::table('verification_insufficiency')->where(['jaf_form_data_id'=>$service->id,'service_id'=>$service->service_id,'status'=>'raised'])->update($ver_insuff_data);

                                  $ver_id=$ver_insuff->id;
                              }
                              else
                              {
                                $ver_insuff_data=[
                                  'parent_id' => $user_type=='candidate'?$super_parent_id:$parent_id,
                                  'business_id' => $user_type=='candidate'?$parent_id:$business_id,
                                  'coc_id' => $service->business_id,
                                  'candidate_id' => $service->candidate_id,
                                  'service_id'  => $service->service_id,
                                  'jaf_form_data_id' => $service->id,
                                  'item_number' => $service->check_item_number,
                                  'activity_type'=> 'jaf-save',
                                  'status'=>'raised',
                                  'notes' => 'Auto check Voter ID failed',
                                  'created_by'   => $userDT->id,
                                  'created_at'   => date('Y-m-d H:i:s'),
                                ];
                          
                                $ver_id = VerificationInsufficiency::create($ver_insuff);
                                $ver_id=$ver_id->id; 
                                // DB::table('verification_insufficiency')->insertGetId($ver_insuff_data);
                              }
                               // Task insuff raised and assign to  CAM

                               $task = Task::where(['business_id'=>$service->business_id,'candidate_id'=>$service->candidate_id,'is_completed'=>0,'description'=>'BGV Filling'])->first();
                               $task_id='';
                               if ($task) {
                                 # code...
                                 $task_id = $task->id;
                                $task_update = Task::find($task_id);
                                $task_update->update(['is_completed'=> 1]);
                                  //  $task->is_completed= 1;
                                  //  $task->save();
                               
                               //Change status of old task 
                               $task_assgn = TaskAssignment::where(['business_id'=>$service->business_id,'candidate_id'=>$service->candidate_id,'status'=>"1",'task_id'=>$task_id])->first();
                               // dd($task_assgn);
                               if($task_assgn)
                               {
                                $task_assign_update = TaskAssignment::find($task_assgn->id);
                                $task_assign_update->update(['status'=> '2']);
                                //  $task_assgn->status= '2';
                                //  $task_assgn->save();
                               }
                             }
                          // task assign start
                          $final_users = [];
                          // $j = 0;
                          $job_sla_item = DB::table('job_sla_items')->where(['candidate_id'=>$service->candidate_id,'service_id'=>$service->service_id])->first();
            
                          // foreach ($job_sla_items as $job_sla_item) {
                            if ($job_sla_item) {
                              # code...
                          
                              $kam  = KeyAccountManager::where(['business_id'=>$service->business_id,'is_primary'=>'1'])->first();
                              if ($kam) {
                                # code...
                             
                                  $final_users = [];
                                  $numbers_of_items = $job_sla_item->number_of_verifications;
                                  
                                  if($numbers_of_items > 0){
                                    // dd($numbers_of_items);
                                    for ($i=1; $i <= $numbers_of_items; $i++) { 
                                      
                                      $final_users = [];
                                      $user_name='';
                                      $user_name = DB::table('users')->where('id',$service->candidate_id)->first();
                                        //insert in task
                                        $data = [
                                          'name'          => $user_name->first_name.' '.$user_name->last_name,
                                          'parent_id'=> $user_name->parent_id,
                                          'business_id'   => $service->business_id, 
                                          'description'   => 'Task for Verification ',
                                          'job_id'        => NULL, 
                                          'priority'      => 'normal',
                                          'candidate_id'  => $service->candidate_id,   
                                          'service_id'    => $job_sla_item->service_id, 
                                          'number_of_verifications' => $i,
                                          'assigned_to'   => $kam->user_id,
                                          'assigned_by'   => $userDT->id,
                                          'assigned_at'   => date('Y-m-d H:i:s'),
                                          'start_date'    => date('Y-m-d'),
                                          'created_by'    => $userDT->id,
                                          'created_at'    => date('Y-m-d H:i:s'),
                                          'is_completed'  => 0
                                          // 'started_at'    => date('Y-m-d H:i:s')
                                        ];
                                         $task_id = Task::create($data); 
                                         $task_id = $task_id->id;
                                        // $task_id =  DB::table('tasks')->insertGetId($data); 
                                        // dd($data);
                                        $taskdata = [
                                          'parent_id'=> $user_name->parent_id,
                                          'business_id'   => $service->business_id,
                                          'candidate_id'  =>$service->candidate_id,   
                                          'job_sla_item_id'  => $job_sla_item->id,
                                          'task_id'       => $task_id,
                                         'user_id'       =>  $kam->user_id,
                                          'service_id'    =>$job_sla_item->service_id,
                                          'number_of_verifications' => $i,
                                          'status'=>'1',
                                          'created_at'    => date('Y-m-d H:i:s')  
                                        ];
                                        TaskAssignment::create($taskdata);
                                       
                                        // DB::table('task_assignments')->insertGetId($taskdata); 
                                    }
                                  }
                                }
                            }
                                       
        
                      }

                      
                  
                    }
                }
                elseif($service->service_id==7)
                {
                  $jaf_rc = DB::table('jaf_form_data')->select('*')->where(['id'=>$service->id])->first();

                  $rc_number = "";
                  // $business_id = $jaf_rc->business_id; 
                  $jaf_array = json_decode($jaf_rc->form_data, true);
                  // print_r($jaf_array);
                  foreach($jaf_array as $input){
                      if(array_key_exists('RC Number',$input)){
                        $rc_number = $input['RC Number'];
                      }
                  }
                  //check first into master table
                  $master_data = DB::table('rc_check_masters')->select('*')->where(['rc_number'=>$rc_number])->first();
                  if($master_data !=null){
                      // update the status
                      $jaf_update= JafFormData::find($service->id);
                      // DB::table('jaf_form_data')->where(['id'=>$service->id])
                      $jaf_update->update(['is_api_checked'=>'1','is_api_verified'=>'1','verification_status'=>'success','verified_at'=>date('Y-m-d H:i:s'),'is_insufficiency'=>'0','clear_insuff_notes'=>'Auto Check RC cleared','is_all_insuff_cleared' => 1,'insuff_cleared_by'=>$userDT->id,'updated_at'=>date('Y-m-d H:i:s')]); 
                      // DB::table('jaf_form_data')->where(['id'=>$service->id])
                      // ->update(['is_api_checked'=>'1','is_api_verified'=>'1','verification_status'=>'success','verified_at'=>date('Y-m-d H:i:s'),'is_insufficiency'=>'0','clear_insuff_notes'=>'Auto Check RC Cleared','is_all_insuff_cleared' => 1,'insuff_cleared_by'=>$userDT->id,'updated_at'=>date('Y-m-d H:i:s')]); 
                      $data = $master_data;

                      $log_data = [
                        'parent_id'         =>$parent_id,
                        'business_id'       => $business_id,
                        'candidate_id' => $service->candidate_id,
                        'service_id'        =>$service->service_id,
                        'source_type'       => 'SystemDb',
                        'api_client_id'     =>$master_data->api_client_id,
                        'rc_number'         =>$master_data->rc_number,
                        'registration_date' =>$master_data->registration_date,
                        'owner_name'        =>$master_data->owner_name,
                        'present_address'   =>$master_data->present_address,
                        'permanent_address'    =>$master_data->permanent_address,
                        'mobile_number'        =>$master_data->mobile_number,
                        'vehicle_category'     =>$master_data->vehicle_category,
                        'vehicle_chasis_number' =>$master_data->vehicle_chasis_number,
                        'vehicle_engine_number' =>$master_data->vehicle_engine_number,
                        'maker_description'     =>$master_data->maker_description,
                        'maker_model'           =>$master_data->maker_model,
                        'body_type'             =>$master_data->body_type,
                        'fuel_type'             =>$master_data->fuel_type,
                        'color'                 =>$master_data->color,
                        'norms_type'            =>$master_data->norms_type,
                        'fit_up_to'             =>$master_data->fit_up_to,
                        'financer'              =>$master_data->financer,
                        'insurance_company'     =>$master_data->insurance_company,
                        'insurance_policy_number'=>$master_data->insurance_policy_number,
                        'insurance_upto'         =>$master_data->insurance_upto,
                        'manufacturing_date'     =>$master_data->manufacturing_date,
                        'registered_at'          =>$master_data->registered_at,
                        'latest_by'              =>$master_data->latest_by,
                        'less_info'              =>$master_data->less_info,
                        'tax_upto'               =>$master_data->tax_upto,
                        'cubic_capacity'         =>$master_data->cubic_capacity,
                        'vehicle_gross_weight'   =>$master_data->vehicle_gross_weight,
                        'no_cylinders'           =>$master_data->no_cylinders,
                        'seat_capacity'          =>$master_data->seat_capacity,
                        'sleeper_capacity'       =>$master_data->sleeper_capacity,
                        'standing_capacity'      =>$master_data->standing_capacity,
                        'wheelbase'              =>$master_data->wheelbase,
                        'unladen_weight'         =>$master_data->unladen_weight,
                        'vehicle_category_description'         =>$master_data->vehicle_category_description,
                        'pucc_number'               =>$master_data->pucc_number,
                        'pucc_upto'                 =>$master_data->pucc_upto,
                        'masked_name'           =>$master_data->masked_name,
                        'is_verified'           =>'1',
                        'is_rc_exist'           =>'1',
                        'price'             =>$price,
                        'used_by'           =>$user_type=='candidate'?'coc':'customer',
                        'user_id'            => $user_id,
                        'created_at'            =>date('Y-m-d H:i:s')
                        ];
                        RcCheck::create($log_data);
                        // DB::table('rc_checks')->insert($log_data);

                        $ver_insuff=DB::table('verification_insufficiency')->where(['jaf_form_data_id'=>$service->id,'service_id'=>$service->service_id,'status'=>'removed'])->first();

                        if($ver_insuff!=NULL)
                        {
                            $ver_insuff_data=[
                              'notes' => 'Auto Check RC Cleared',
                              'updated_by' => $userDT->id,
                              'updated_at' => date('Y-m-d H:i:s')
                            ];

                            // DB::table('verification_insufficiency')->where(['jaf_form_data_id'=>$service->id,'service_id'=>$service->service_id,'status'=>'removed'])->update($ver_insuff_data);
                             $ver_insuff_id=  DB::table('verification_insufficiency')->where(['jaf_form_data_id'=>$service->id,'service_id'=>$service->service_id,'status'=>'removed'])->first();
                                  $update_ver_insuff= VerificationInsufficiency::find($ver_insuff_id->id);  
                                  $update_ver_insuff->update($ver_insuff_data);
                            	  $ver_id=$ver_insuff->id;
                        }
                        else
                        {
                          $ver_insuff_data=[
                            'parent_id' => $user_type=='candidate'?$super_parent_id:$parent_id,
                            'business_id' => $user_type=='candidate'?$parent_id:$business_id,
                            'coc_id' => $jaf_rc->business_id,
                            'candidate_id' => $service->candidate_id,
                            'service_id'  => $jaf_rc->service_id,
                            'jaf_form_data_id' => $jaf_rc->id,
                            'item_number' => $jaf_rc->check_item_number,
                            'activity_type'=> 'jaf-save',
                            'status'=>'removed',
                            'notes' => 'Auto Check RC Cleared',
                            'created_by'   => $userDT->id,
                            'created_at'   => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s')
                          ];
                      			$ver_id = VerificationInsufficiency::create($ver_insuff);
                                $ver_id=$ver_id->id; 
                        //   $ver_id=DB::table('verification_insufficiency')->insertGetId($ver_insuff_data);
                        }

                        $insuff_log_data=[
                          'parent_id' => $user_type=='candidate'?$super_parent_id:$parent_id,
                          'business_id' => $user_type=='candidate'?$parent_id:$business_id,
                          'coc_id' => $jaf_rc->business_id,
                          'candidate_id' => $service->candidate_id,
                          'service_id'  => $jaf_rc->service_id,
                          'jaf_form_data_id' => $jaf_rc->id,
                          'item_number' => $jaf_rc->check_item_number,
                          'activity_type'=> 'jaf-save',
                          'status'=>'removed',
                          'notes' => 'Auto Check RC Cleared',
                          'created_by'   => $userDT->id,
                          'user_type'           =>$user_type=='candidate'?'coc':'customer',
                          'created_at'   => date('Y-m-d H:i:s'),
                        ];
                  
                        DB::table('insufficiency_logs')->insert($insuff_log_data);

                        $task = Task::where(['business_id'=>$service->business_id,'candidate_id'=>$service->candidate_id,'is_completed'=>0,'description'=>'BGV Filling'])->first();
                        $task_id='';
                        if ($task) {
                          # code...
                          $task_id = $task->id;
                            $task_update = Task::find($task_id);
                            $task_update->update(['is_completed'=> 1]);
                            // $task->is_completed= 1;
                            // $task->save();
                        
                        //Change status of old task 
                        $task_assgn = TaskAssignment::where(['business_id'=>$service->business_id,'candidate_id'=>$service->candidate_id,'status'=>"1",'task_id'=>$task_id])->first();
                        // dd($task_assgn);
                        if($task_assgn)
                        { 
							              $task_assign_update = TaskAssignment::find($task_assgn->id);
                            $task_assign_update->update(['status'=> '2']);
                          // $task_assgn->status= '2';
                          // $task_assgn->save();
                        }
                      }

                      $ver_insuff=DB::table('verification_insufficiency')->where(['id'=>$ver_id])->first();

                      $candidates=DB::table('users as u')
                          ->select('u.*','j.business_id as coc_id','j.id as jaf_id','v.created_at as insuff_clear_date','v.created_by as insuff_clear_by','v.item_number','v.notes','s.verification_type','s.name as service_name','v.business_id as cust_id','v.attachment','v.updated_by','v.updated_at')
                          ->join('jaf_form_data as j','u.id','=','j.candidate_id')
                          ->join('verification_insufficiency as v','v.jaf_form_data_id','=','j.id')
                          ->join('services as s','s.id','=','v.service_id')
                          ->where(['u.user_type'=>'candidate','j.id'=>$service->id,'v.status'=>'removed','v.id'=>$ver_insuff->id])
                          ->first();

                          if($candidates!=NULL)
                          {
                            // $client=DB::table('users')->where(['id'=>$candidates->coc_id])->first();
                            // $name = $client->name;
                            // $email = $client->email;
                            // $msg= "Insufficiency Cleared For Candidate";
                            // $sender = DB::table('users')->where(['id'=>$candidates->parent_id])->first();
                            // $data  = array('name'=>$name,'email'=>$email,'msg'=>$msg,'link'=>'','candidate'=>$candidates,'sender'=>$sender);
            
                            // Mail::send(['html'=>'mails.insuff-clear-notify'], $data, function($message) use($email,$name) {
                            //   $message->to($email, $name)->subject
                            //       ('Clobminds System - Insufficiency Notification');
                            //   $message->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
                            // });

                            $kams = DB::table('key_account_managers')->where(['business_id'=>$candidates->business_id])->get();
                            if(count($kams)>0)
                            {
                              foreach($kams as $kam)
                              {
                                  $user_data=DB::table('users')->where(['id'=>$kam->user_id])->first();

                                  $name1 = $user_data->name;
                                  $email1 = $user_data->email;
                                  $msg= "Insufficiency Cleared For Candidate";
                                  $sender = DB::table('users')->where(['id'=>$candidates->parent_id])->first();
                                  $data  = array('name'=>$name1,'email'=>$email1,'msg'=>$msg,'link'=>'','candidate'=>$candidates,'sender'=>$sender);
                                  // EmailConfigTrait::emailConfig();
                                  //get Mail config data
                                    //   $mail =null;
                                    $mail= Config::get('mail');
                                    // dd($mail['from']['address']);
                                    // if (count($mail)>0) {
                                    //     Mail::send(['html'=>'mails.insuff-clear-notify'], $data, function($message) use($email1,$name1,$mail) {
                                    //         $message->to($email1, $name1)->subject
                                    //         ('Clobminds System - Insufficiency Notification ');
                                    //         $message->from($mail['from']['address'],$mail['from']['name']);
                                    //     });
                                    //   }else {
                                        Mail::send(['html'=>'mails.insuff-clear-notify'], $data, function($message) use($email1,$name1) {
                                            $message->to($email1, $name1)->subject
                                                ('Clobminds System - Insufficiency Notification');
                                            $message->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
                                        });
                                    //  }

                              }
                            }


                          }
                  }
                  else{
                    //check from live API
                    // Setup request to send json via POST
                    $data = array(
                        'id_number'    => $rc_number,
                        'async'         => true,
                    );
                    $payload = json_encode($data);
                    $apiURL = "https://kyc-api.aadhaarkyc.io/api/v1/rc/rc";
    
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                
                    curl_setopt ( $ch, CURLOPT_POST, 1 );
                    $authorization = "Authorization: Bearer ".env('SUREPASS_PRODUCTION_TOKEN'); // Prepare the authorisation token
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization )); // Inject the token into the header
                    curl_setopt($ch, CURLOPT_URL, $apiURL);
                    // Attach encoded JSON string to the POST fields
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
                    $resp = curl_exec ( $ch );
                    curl_close ( $ch );
                    $array_data =  json_decode($resp,true);
                    // print_r($array_data); die;
    
                    if($array_data['success'])
                    {
                        //check if ID number is new then insert into DB
                        $checkIDInDB= DB::table('rc_check_masters')->where(['rc_number'=>$rc_number])->count();
                        if($checkIDInDB ==0)
                        {
                        
                            $data = [
                                    'api_client_id'     =>$array_data['data']['client_id'],
                                    'rc_number'         =>$array_data['data']['rc_number'],
                                    'registration_date' =>$array_data['data']['registration_date'],
                                    'owner_name'        =>$array_data['data']['owner_name'],
                                    'present_address'   =>$array_data['data']['present_address'],
                                    'permanent_address'    =>$array_data['data']['permanent_address'],
                                    'mobile_number'        =>$array_data['data']['mobile_number'],
                                    'vehicle_category'     =>$array_data['data']['vehicle_category'],
                                    'vehicle_chasis_number' =>$array_data['data']['vehicle_chasi_number'],
                                    'vehicle_engine_number' =>$array_data['data']['vehicle_engine_number'],
                                    'maker_description'     =>$array_data['data']['maker_description'],
                                    'maker_model'           =>$array_data['data']['maker_model'],
                                    'body_type'             =>$array_data['data']['body_type'],
                                    'fuel_type'             =>$array_data['data']['fuel_type'],
                                    'color'                 =>$array_data['data']['color'],
                                    'norms_type'            =>$array_data['data']['norms_type'],
                                    'fit_up_to'             =>$array_data['data']['fit_up_to'],
                                    'financer'              =>$array_data['data']['financer'],
                                    'insurance_company'     =>$array_data['data']['insurance_company'],
                                    'insurance_policy_number'=>$array_data['data']['insurance_policy_number'],
                                    'insurance_upto'         =>$array_data['data']['insurance_upto'],
                                    'manufacturing_date'     =>$array_data['data']['manufacturing_date'],
                                    'registered_at'          =>$array_data['data']['registered_at'],
                                    'latest_by'              =>$array_data['data']['latest_by'],
                                    'less_info'              =>$array_data['data']['less_info'],
                                    'tax_upto'               =>$array_data['data']['tax_upto'],
                                    'cubic_capacity'         =>$array_data['data']['cubic_capacity'],
                                    'vehicle_gross_weight'   =>$array_data['data']['vehicle_gross_weight'],
                                    'no_cylinders'           =>$array_data['data']['no_cylinders'],
                                    'seat_capacity'          =>$array_data['data']['seat_capacity'],
                                    'sleeper_capacity'       =>$array_data['data']['sleeper_capacity'],
                                    'standing_capacity'      =>$array_data['data']['standing_capacity'],
                                    'wheelbase'              =>$array_data['data']['wheelbase'],
                                    'unladen_weight'         =>$array_data['data']['unladen_weight'],
                                    'vehicle_category_description' =>$array_data['data']['vehicle_category_description'],
                                    'pucc_number'               =>$array_data['data']['pucc_number'],
                                    'pucc_upto'                 =>$array_data['data']['pucc_upto'],
                                    'masked_name'           =>$array_data['data']['masked_name'],
                                    'is_api_verified'           =>'1',
                                    'is_rc_exist'           =>'1',
                                    'created_at'            =>date('Y-m-d H:i:s')
                                    ];
									            RcCheckMaster::create($data);
                            // DB::table('rc_check_masters')->insert($data);
                            
                            $master_data = DB::table('rc_check_masters')->select('*')->where(['rc_number'=>$rc_number])->first();

                            $log_data = [
                              'parent_id'         =>$parent_id,
                              'business_id'       => $business_id,
                              'candidate_id' => $service->candidate_id,
                              'service_id'        =>$service->service_id,
                              'source_type'       => 'API',
                              'api_client_id'     =>$master_data->api_client_id,
                              'rc_number'         =>$master_data->rc_number,
                              'registration_date' =>$master_data->registration_date,
                              'owner_name'        =>$master_data->owner_name,
                              'present_address'   =>$master_data->present_address,
                              'permanent_address'    =>$master_data->permanent_address,
                              'mobile_number'        =>$master_data->mobile_number,
                              'vehicle_category'     =>$master_data->vehicle_category,
                              'vehicle_chasis_number' =>$master_data->vehicle_chasis_number,
                              'vehicle_engine_number' =>$master_data->vehicle_engine_number,
                              'maker_description'     =>$master_data->maker_description,
                              'maker_model'           =>$master_data->maker_model,
                              'body_type'             =>$master_data->body_type,
                              'fuel_type'             =>$master_data->fuel_type,
                              'color'                 =>$master_data->color,
                              'norms_type'            =>$master_data->norms_type,
                              'fit_up_to'             =>$master_data->fit_up_to,
                              'financer'              =>$master_data->financer,
                              'insurance_company'     =>$master_data->insurance_company,
                              'insurance_policy_number'=>$master_data->insurance_policy_number,
                              'insurance_upto'         =>$master_data->insurance_upto,
                              'manufacturing_date'     =>$master_data->manufacturing_date,
                              'registered_at'          =>$master_data->registered_at,
                              'latest_by'              =>$master_data->latest_by,
                              'less_info'              =>$master_data->less_info,
                              'tax_upto'               =>$master_data->tax_upto,
                              'cubic_capacity'         =>$master_data->cubic_capacity,
                              'vehicle_gross_weight'   =>$master_data->vehicle_gross_weight,
                              'no_cylinders'           =>$master_data->no_cylinders,
                              'seat_capacity'          =>$master_data->seat_capacity,
                              'sleeper_capacity'       =>$master_data->sleeper_capacity,
                              'standing_capacity'      =>$master_data->standing_capacity,
                              'wheelbase'              =>$master_data->wheelbase,
                              'unladen_weight'         =>$master_data->unladen_weight,
                              'vehicle_category_description'         =>$master_data->vehicle_category_description,
                              'pucc_number'               =>$master_data->pucc_number,
                              'pucc_upto'                 =>$master_data->pucc_upto,
                              'masked_name'           =>$master_data->masked_name,
                              'is_verified'           =>'1',
                              'is_rc_exist'           =>'1',
                              'price'             =>$price,
                              'used_by'           =>$user_type=='candidate'?'coc':'customer',
                              'user_id'            => $user_id,
                              'created_at'            =>date('Y-m-d H:i:s')
                              ];
							              RcCheck::create($log_data);
                            //   DB::table('rc_checks')->insert($log_data);
                        }
    
                        // update the status
                    $jaf_update= JafFormData::find($service->id);
                    // DB::table('jaf_form_data')->where(['id'=>$service->id])
                    $jaf_update->update(['is_api_checked'=>'1','is_api_verified'=>'1','verification_status'=>'success','verified_at'=>date('Y-m-d H:i:s'),'is_insufficiency'=>'0','clear_insuff_notes'=>'Auto Check RC cleared','is_all_insuff_cleared' => 1,'insuff_cleared_by'=>$userDT->id,'updated_at'=>date('Y-m-d H:i:s')]); 
						
                        // DB::table('jaf_form_data')->where(['id'=>$service->id])
                        //     ->update(['is_api_checked'=>'1','is_api_verified'=>'1','verification_status'=>'success','verified_at'=>date('Y-m-d H:i:s'),'is_insufficiency'=>'0','clear_insuff_notes'=>'Auto Check RC Cleared','is_all_insuff_cleared' => 1,'insuff_cleared_by'=>$userDT->id,'updated_at'=>date('Y-m-d H:i:s')]); 

                            $ver_insuff=DB::table('verification_insufficiency')->where(['jaf_form_data_id'=>$service->id,'service_id'=>$service->service_id,'status'=>'removed'])->first();

                            if($ver_insuff!=NULL)
                            {
                                $ver_insuff_data=[
                                  'notes' => 'Auto Check RC Cleared',
                                  'updated_by' => $userDT->id,
                                  'updated_at' => date('Y-m-d H:i:s')
                                ];
                      
                                $ver_insuff_id=  DB::table('verification_insufficiency')->where(['jaf_form_data_id'=>$service->id,'service_id'=>$service->service_id,'status'=>'removed'])->first();
                                $update_ver_insuff= VerificationInsufficiency::find($ver_insuff_id->id);  
                                $update_ver_insuff->update($ver_insuff_data);
                                // DB::table('verification_insufficiency')->where(['jaf_form_data_id'=>$service->id,'service_id'=>$service->service_id,'status'=>'removed'])->update($ver_insuff_data);
                      
                                $ver_id=$ver_insuff->id;
                            }
                            else
                            {
                              $ver_insuff_data=[
                                'parent_id' => $user_type=='candidate'?$super_parent_id:$parent_id,
                                'business_id' => $user_type=='candidate'?$parent_id:$business_id,
                                'coc_id' => $jaf_rc->business_id,
                                'candidate_id' => $service->candidate_id,
                                'service_id'  => $jaf_rc->service_id,
                                'jaf_form_data_id' => $jaf_rc->id,
                                'item_number' => $jaf_rc->check_item_number,
                                'activity_type'=> 'jaf-save',
                                'status'=>'removed',
                                'notes' => 'Auto Check RC Cleared',
                                'created_by'   => $userDT->id,
                                'created_at'   => date('Y-m-d H:i:s'),
                                'updated_at' => date('Y-m-d H:i:s')
                              ];
                              $ver_id = VerificationInsufficiency::create($ver_insuff);
                              $ver_id=$ver_id->id; 
                            //   $ver_id=DB::table('verification_insufficiency')->insertGetId($ver_insuff_data);
                            }

                            $insuff_log_data=[
                              'parent_id' => $user_type=='candidate'?$super_parent_id:$parent_id,
                              'business_id' => $user_type=='candidate'?$parent_id:$business_id,
                              'coc_id' => $jaf_rc->business_id,
                              'candidate_id' => $jaf_rc->candidate_id,
                              'service_id'  => $jaf_rc->service_id,
                              'jaf_form_data_id' => $jaf_rc->id,
                              'item_number' => $jaf_rc->check_item_number,
                              'activity_type'=> 'jaf-save',
                              'status'=>'removed',
                              'notes' => 'Auto Check RC Cleared',
                              'created_by'   => $userDT->id,
                              'user_type'           =>$user_type=='candidate'?'coc':'customer',
                              'created_at'   => date('Y-m-d H:i:s'),
                            ];
                      
                            DB::table('insufficiency_logs')->insert($insuff_log_data);


                            
                          $task = Task::where(['business_id'=>$service->business_id,'candidate_id'=>$service->candidate_id,'is_completed'=>0,'description'=>'BGV Filling'])->first();
                          $task_id='';
                          if ($task) {
                            # code...
                            $task_id = $task->id;
							              $task_update = Task::find($task_id);
                            $task_update->update(['is_completed'=> 1]);
                            //   $task->is_completed= 1;
                            //   $task->save();
                          
                          //Change status of old task 
                          $task_assgn = TaskAssignment::where(['business_id'=>$service->business_id,'candidate_id'=>$service->candidate_id,'status'=>"1",'task_id'=>$task_id])->first();
                          // dd($task_assgn);
                          if($task_assgn)
                          {
							                $task_assign_update = TaskAssignment::find($task_assgn->id);
                            	$task_assign_update->update(['status'=> '2']);
                              // $task_assgn->status= '2';
                              // $task_assgn->save();
                          }
                        }
                        $ver_insuff=DB::table('verification_insufficiency')->where(['id'=>$ver_id])->first();

                        $candidates=DB::table('users as u')
                            ->select('u.*','j.business_id as coc_id','j.id as jaf_id','v.created_at as insuff_clear_date','v.created_by as insuff_clear_by','v.item_number','v.notes','s.verification_type','s.name as service_name','v.business_id as cust_id','v.attachment','v.updated_by','v.updated_at')
                            ->join('jaf_form_data as j','u.id','=','j.candidate_id')
                            ->join('verification_insufficiency as v','v.jaf_form_data_id','=','j.id')
                            ->join('services as s','s.id','=','v.service_id')
                            ->where(['u.user_type'=>'candidate','j.id'=>$service->id,'v.status'=>'removed','v.id'=>$ver_insuff->id])
                            ->first();

                        if($candidates!=NULL)
                        {
                          // $client=DB::table('users')->where(['id'=>$candidates->coc_id])->first();
                          // $name = $client->name;
                          // $email = $client->email;
                          // $msg= "Insufficiency Cleared For Candidate";
                          // $sender = DB::table('users')->where(['id'=>$candidates->parent_id])->first();
                          // $data  = array('name'=>$name,'email'=>$email,'msg'=>$msg,'link'=>'','candidate'=>$candidates,'sender'=>$sender);
          
                          // Mail::send(['html'=>'mails.insuff-clear-notify'], $data, function($message) use($email,$name) {
                          //   $message->to($email, $name)->subject
                          //       ('Clobminds System - Insufficiency Notification');
                          //   $message->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
                          // });

                          $kams = DB::table('key_account_managers')->where(['business_id'=>$candidates->business_id])->get();
                          if(count($kams)>0)
                          {
                            foreach($kams as $kam)
                            {
                                $user_data=DB::table('users')->where(['id'=>$kam->user_id])->first();

                                $name1 = $user_data->name;
                                $email1 = $user_data->email;
                                $msg= "Insufficiency Cleared For Candidate";
                                $sender = DB::table('users')->where(['id'=>$candidates->parent_id])->first();
                                $data  = array('name'=>$name1,'email'=>$email1,'msg'=>$msg,'link'=>'','candidate'=>$candidates,'sender'=>$sender);
                                // EmailConfigTrait::emailConfig();
                                //get Mail config data
                                  //   $mail =null;
                                  $mail= Config::get('mail');
                                  // dd($mail['from']['address']);
                                  // if (count($mail)>0) {
                                  //     Mail::send(['html'=>'mails.insuff-clear-notify'], $data, function($message) use($email1,$name1,$mail) {
                                  //         $message->to($email1, $name1)->subject
                                  //         ('Task Notification - Insufficiency Notification ');
                                  //         $message->from($mail['from']['address'],$mail['from']['name']);
                                  //     });
                                  //   }else {
                                      Mail::send(['html'=>'mails.insuff-clear-notify'], $data, function($message) use($email1,$name1) {
                                          $message->to($email1, $name1)->subject
                                              ('Clobminds System - Insufficiency Notification');
                                          $message->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
                                      });
                                    // }

                            }
                          }

                        }


                    }else{
                        //update insuff
                        $jaf_update= JafFormData::find($service->id);
                        // DB::table('jaf_form_data')->where(['id'=>$service->id])
                        $jaf_update->update(['is_api_checked'=>'1','is_api_verified'=>'0','is_insufficiency'=>'1','verification_status'=>'failed','verified_at'=>date('Y-m-d H:i:s')]);
                                    // DB::table('jaf_form_data')->where(['id'=>$service->id])->update(['is_api_checked'=>'1','is_api_verified'=>'0','is_insufficiency'=>'1','verification_status'=>'failed','verified_at'=>date('Y-m-d H:i:s')]); 

                        $jaf_data=DB::table('jaf_form_data')->where(['id'=>$service->id])->first();
                        $insuff_log_data=[
                          'parent_id' => $user_type=='candidate'?$super_parent_id:$parent_id,
                          'business_id' => $user_type=='candidate'?$parent_id:$business_id,
                          'coc_id' => $jaf_rc->business_id,
                          'candidate_id' => $service->candidate_id,
                          'service_id'  => $jaf_rc->service_id,
                          'jaf_form_data_id' => $jaf_rc->id,
                          'item_number' => $jaf_rc->check_item_number,
                          'activity_type'=> 'jaf-save',
                          'status'=>'failed',
                          'notes' => 'Auto Check RC Failed',
                          'created_by'   => $userDT->id,
                          'user_type'           =>$user_type=='candidate'?'coc':'customer',
                          'created_at'   => date('Y-m-d H:i:s'),
                        ];
                  
                        DB::table('insufficiency_logs')->insert($insuff_log_data);

                        $ver_insuff=DB::table('verification_insufficiency')->where(['jaf_form_data_id'=>$service->id,'service_id'=>$service->service_id,'status'=>'raised'])->first();

                          if($ver_insuff!=NULL)
                          {
                              $ver_insuff_data=[
                                'notes' => 'Auto check RC failed',
                                'updated_by' => $userDT->id,
                                'updated_at' => date('Y-m-d H:i:s')
                              ];

                        $ver_insuff_id=  DB::table('verification_insufficiency')->where(['jaf_form_data_id'=>$service->id,'service_id'=>$service->service_id,'status'=>'raised'])->first();
                        $update_ver_insuff= VerificationInsufficiency::find($ver_insuff_id->id);  
                        $update_ver_insuff->update($ver_insuff_data);
                            //   DB::table('verification_insufficiency')->where(['jaf_form_data_id'=>$service->id,'service_id'=>$service->service_id,'status'=>'raised'])->update($ver_insuff_data);

                              $ver_id=$ver_insuff->id;
                          }
                          else
                          {
                            $ver_insuff_data=[
                              'parent_id' => $user_type=='candidate'?$super_parent_id:$parent_id,
                              'business_id' => $user_type=='candidate'?$parent_id:$business_id,
                              'coc_id' => $service->business_id,
                              'candidate_id' => $service->candidate_id,
                              'service_id'  => $service->service_id,
                              'jaf_form_data_id' => $service->id,
                              'item_number' => $service->check_item_number,
                              'activity_type'=> 'jaf-save',
                              'status'=>'raised',
                              'notes' => 'Auto check RC failed',
                              'created_by'   => $userDT->id,
                              'created_at'   => date('Y-m-d H:i:s'),
                            ];
                     			 $ver_id = VerificationInsufficiency::create($ver_insuff);
                                $ver_id=$ver_id->id; 
                            // $ver_id = DB::table('verification_insufficiency')->insertGetId($ver_insuff_data);
                          }

                           // Task insuff raised and assign to  CAM

                           $task = Task::where(['business_id'=>$service->business_id,'candidate_id'=>$service->candidate_id,'is_completed'=>0,'description'=>'BGV Filling'])->first();
                           $task_id='';
                           if ($task) {
                             # code...
                             $task_id = $task->id;
  						    $task_update = Task::find($task_id);
                              $task_update->update(['is_completed'=> 1]);
                            //    $task->is_completed= 1;
                            //    $task->save();
                           
                           //Change status of old task 
                           $task_assgn = TaskAssignment::where(['business_id'=>$service->business_id,'candidate_id'=>$service->candidate_id,'status'=>"1",'task_id'=>$task_id])->first();
                           // dd($task_assgn);
                           if($task_assgn)
                           {$task_assign_update = TaskAssignment::find($task_assgn->id);
                            $task_assign_update->update(['status'=> '2']);
                            //  $task_assgn->status= '2';
                            //  $task_assgn->save();
                           }
                         }
                           // task assign start
                           $final_users = [];
                           // $j = 0;
                           $job_sla_item = DB::table('job_sla_items')->where(['candidate_id'=>$service->candidate_id,'service_id'=>$service->service_id])->first();
             
                           // foreach ($job_sla_items as $job_sla_item) {
                             if ($job_sla_item) {
                               # code...
                           
                               $kam  = KeyAccountManager::where(['business_id'=>$service->business_id,'is_primary'=>'1'])->first();
                               if ($kam) {
                                 # code...
                              
                                   $final_users = [];
                                   $numbers_of_items = $job_sla_item->number_of_verifications;
                                   if($numbers_of_items > 0){
                                     for ($i=1; $i <= $numbers_of_items; $i++) { 
                                       
                                       $final_users = [];
                                       $user_name='';
                                       $user_name = DB::table('users')->where('id',$service->candidate_id)->first();
                                         //insert in task
                                         $data = [
                                           'name'          => $user_name->first_name.' '.$user_name->last_name,
                                           'parent_id'=> $user_name->parent_id,
                                           'business_id'   => $service->business_id, 
                                           'description'   => 'Task for Verification ',
                                           'job_id'        => NULL, 
                                           'priority'      => 'normal',
                                           'candidate_id'  => $service->candidate_id,   
                                           'service_id'    => $job_sla_item->service_id, 
                                           'number_of_verifications' => $i,
                                           'assigned_to'   => $kam->user_id,
                                           'assigned_by'   => $userDT->id,
                                           'assigned_at'   => date('Y-m-d H:i:s'),
                                           'start_date'    => date('Y-m-d'),
                                           'created_by'    => $userDT->id,
                                           'created_at'    => date('Y-m-d H:i:s'),
                                           'is_completed'  => 0,
                                           // 'started_at'    => date('Y-m-d H:i:s')
                                         ];
                                         // // dd($data);
                                         $task_id =  Task::create($data);
                                          $task_id=$task_id->id; 
                                          //   DB::table('tasks')->insertGetId($data); 
             
                                         $taskdata = [
                                           'parent_id'=>$user_name->parent_id,
                                           'business_id'   => $service->business_id,
                                           'candidate_id'  =>$service->candidate_id,   
                                           'job_sla_item_id'  => $job_sla_item->id,
                                           'task_id'       => $task_id,
                                          'user_id'       =>  $kam->user_id,
                                           'service_id'    =>$job_sla_item->service_id,
                                           'number_of_verifications' => $i,
                                           'status'=>'1',
                                           'created_at'    => date('Y-m-d H:i:s')  
                                         ];
                                         TaskAssignment::create($taskdata);
                                        
                                         // DB::table('task_assignments')->insertGetId($taskdata); 
                                     }
                                   }
                                 }
                             }
                                        
                    }
                  
                  
                  }

                }
                elseif($service->service_id==9)
                {
                  $jaf_dl = DB::table('jaf_form_data')->select('*')->where(['id'=>$service->id])->first();

                  $dl_number = "";
                  // $business_id = $jaf_dl->business_id; 
                  $jaf_array = json_decode($jaf_dl->form_data, true);
                  // print_r($jaf_array);
                  foreach($jaf_array as $input){
                      if(array_key_exists('DL Number',$input)){
                        $dl_number = $input['DL Number'];
                      }
                  }
                  $dl_number_input      = $dl_number;
                  $dl_raw               = preg_replace('/[^A-Za-z0-9\ ]/', '', $dl_number_input);
                  $final_dl_number      = str_replace(' ', '', $dl_raw);
                  //check first into master table
                  $master_data = DB::table('dl_check_masters')->select('*')->where(['dl_number'=>$final_dl_number])->first();
                  
                  if($master_data !=null){


                    $log_data = [
                      'parent_id'         =>$parent_id,
                      'business_id'       => $business_id,
                      'service_id'        =>$service->service_id,
                      'candidate_id' => $service->candidate_id,
                      'source_type'       =>'SystemDb',
                      'api_client_id'     =>$master_data->api_client_id,
                      'dl_number'         =>$master_data->dl_number,
                      'name'              =>$master_data->name,
                      'permanent_address' =>$master_data->permanent_address,
                      'temporary_address' =>$master_data->temporary_address,
                      'permanent_zip'     =>$master_data->permanent_zip,
                      'temporary_zip'     =>$master_data->temporary_zip,
                      'state'             =>$master_data->state,
                      'citizenship'       =>$master_data->citizenship,
                      'ola_name'          =>$master_data->ola_name,
                      'ola_code'          =>$master_data->ola_code,
                      'gender'            =>$master_data->gender,
                      'father_or_husband_name' =>$master_data->father_or_husband_name,
                      'dob'               =>$master_data->dob,
                      'doe'               =>$master_data->doe,
                      'transport_doe'     =>$master_data->transport_doe,
                      'doi'               =>$master_data->doi,
                      'is_verified'       =>'1',
                      'is_rc_exist'       =>'1',
                      'price'             =>$price,
                      'used_by'           =>$user_type=='candidate'?'coc':'customer',
                      'user_id'            => $user_id,
                      'created_at'        =>date('Y-m-d H:i:s')
                    ];
                  
                        DB::table('dl_checks')->insert($log_data);
                        // update the status
                        DB::table('jaf_form_data')->where(['id'=>$service->id])
                        ->update(['is_api_checked'=>'1','is_api_verified'=>'1','verification_status'=>'success','verified_at'=>date('Y-m-d H:i:s'),'is_insufficiency'=>'0','clear_insuff_notes'=>'Auto Check DL Cleared','is_all_insuff_cleared' => 1,'insuff_cleared_by'=>$userDT->id,'updated_at'=>date('Y-m-d H:i:s')]); 

                        $ver_insuff=DB::table('verification_insufficiency')->where(['jaf_form_data_id'=>$service->id,'service_id'=>$service->service_id,'status'=>'removed'])->first();

                        if($ver_insuff!=NULL)
                        {
                            $ver_insuff_data=[
                              'notes' => 'JAF Cleared',
                              'updated_by' => $userDT->id,
                              'updated_at' => date('Y-m-d H:i:s')
                            ];

                            DB::table('verification_insufficiency')->where(['jaf_form_data_id'=>$service->id,'service_id'=>$service->service_id,'status'=>'removed'])->update($ver_insuff_data);

                            $ver_id=$ver_insuff->id;
                        }
                        else
                        {
                          $ver_insuff_data=[
                            'parent_id' => $user_type=='candidate'?$super_parent_id:$parent_id,
                            'business_id' => $user_type=='candidate'?$parent_id:$business_id,
                            'coc_id' => $jaf_dl->business_id,
                            'candidate_id' => $service->candidate_id,
                            'service_id'  => $jaf_dl->service_id,
                            'jaf_form_data_id' => $jaf_dl->id,
                            'item_number' => $jaf_dl->check_item_number,
                            'activity_type'=> 'jaf-save',
                            'status'=>'removed',
                            'notes' => 'Auto Check DL Cleared',
                            'created_by'   => $userDT->id,
                            'created_at'   => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s')
                          ];
                
                          $ver_id=DB::table('verification_insufficiency')->insertGetId($ver_insuff_data);
                        }

                        $insuff_log_data=[
                          'parent_id' => $user_type=='candidate'?$super_parent_id:$parent_id,
                          'business_id' => $user_type=='candidate'?$parent_id:$business_id,
                          'coc_id' => $jaf_dl->business_id,
                          'candidate_id' => $service->candidate_id,
                          'service_id'  => $jaf_dl->service_id,
                          'jaf_form_data_id' => $jaf_dl->id,
                          'item_number' => $jaf_dl->check_item_number,
                          'activity_type'=> 'jaf-save',
                          'status'=>'removed',
                          'notes' => 'Auto Check DL Cleared',
                          'created_by'   => $userDT->id,
                          'user_type'           =>$user_type=='candidate'?'coc':'customer',
                          'created_at'   => date('Y-m-d H:i:s'),
                        ];
                  
                        DB::table('insufficiency_logs')->insert($insuff_log_data);

                       
                        $task = Task::where(['business_id'=>$service->business_id,'candidate_id'=>$service->candidate_id,'is_completed'=>0,'description'=>'BGV Filling'])->first();
                        $task_id='';
                        if ($task) {
                          # code...
                          $task_id = $task->id;

                            $task->is_completed= 1;
                            $task->save();
                        
                        //Change status of old task 
                        $task_assgn = TaskAssignment::where(['business_id'=>$service->business_id,'candidate_id'=>$service->candidate_id,'status'=>"1",'task_id'=>$task_id])->first();
                        // dd($task_assgn);
                        if($task_assgn)
                        {
                          $task_assgn->status= '2';
                          $task_assgn->save();
                        }
                      }

                          $ver_insuff=DB::table('verification_insufficiency')->where(['id'=>$ver_id])->first();

                          $candidates=DB::table('users as u')
                              ->select('u.*','j.business_id as coc_id','j.id as jaf_id','v.created_at as insuff_clear_date','v.created_by as insuff_clear_by','v.item_number','v.notes','s.verification_type','s.name as service_name','v.business_id as cust_id','v.attachment','v.updated_at','v.updated_by')
                              ->join('jaf_form_data as j','u.id','=','j.candidate_id')
                              ->join('verification_insufficiency as v','v.jaf_form_data_id','=','j.id')
                              ->join('services as s','s.id','=','v.service_id')
                              ->where(['u.user_type'=>'candidate','j.id'=>$service->id,'v.status'=>'removed','v.id'=>$ver_insuff->id])
                              ->first();

                            if($candidates!=NULL)
                            {
                              // $client=DB::table('users')->where(['id'=>$candidates->coc_id])->first();
                              // $name = $client->name;
                              // $email = $client->email;
                              // $msg= "Insufficiency Cleared For Candidate";
                              // $sender = DB::table('users')->where(['id'=>$candidates->parent_id])->first();
                              // $data  = array('name'=>$name,'email'=>$email,'msg'=>$msg,'link'=>'','candidate'=>$candidates,'sender'=>$sender);
              
                              // Mail::send(['html'=>'mails.insuff-clear-notify'], $data, function($message) use($email,$name) {
                              //   $message->to($email, $name)->subject
                              //       ('Clobminds System - Insufficiency Notification');
                              //   $message->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
                              // });

                              $kams = DB::table('key_account_managers')->where(['business_id'=>$candidates->business_id])->get();
                              if(count($kams)>0)
                              {
                                foreach($kams as $kam)
                                {
                                    $user_data=DB::table('users')->where(['id'=>$kam->user_id])->first();

                                    $name1 = $user_data->name;
                                    $email1 = $user_data->email;
                                    $msg= "Insufficiency Cleared For Candidate";
                                    $sender = DB::table('users')->where(['id'=>$candidates->parent_id])->first();
                                      $data  = array('name'=>$name1,'email'=>$email1,'msg'=>$msg,'link'=>'','candidate'=>$candidates,'sender'=>$sender);

                                      // EmailConfigTrait::emailConfig();
                                      //get Mail config data
                                        //   $mail =null;
                                        $mail= Config::get('mail');
                                        // dd($mail['from']['address']);
                                        // if (count($mail)>0) {
                                        //     Mail::send(['html'=>'mails.insuff-clear-notify'], $data, function($message) use($email1,$name1,$mail) {
                                        //         $message->to($email1, $name1)->subject
                                        //         ('Clobminds System - Insufficiency Notification');
                                        //         $message->from($mail['from']['address'],$mail['from']['name']);
                                        //     });
                                        //   }else {
                                            Mail::send(['html'=>'mails.insuff-clear-notify'], $data, function($message) use($email1,$name1) {
                                                $message->to($email1, $name1)->subject
                                                    ('Clobminds System - Insufficiency Notification');
                                                $message->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
                                            });
                                          // }

                                }
                              }

                            }

                  }
                  else{
                    //check from live API
                    // Setup request to send json via POST
                    $data = array(
                        'id_number'    => $dl_number,
                        'async'         => true,
                    );
                    $payload = json_encode($data);
                    $apiURL = "https://kyc-api.aadhaarkyc.io/api/v1/driving-license/driving-license";
    
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                
                    curl_setopt ( $ch, CURLOPT_POST, 1 );
                    $authorization = "Authorization: Bearer ".env('SUREPASS_PRODUCTION_TOKEN'); // Prepare the authorisation token
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization )); // Inject the token into the header
                    curl_setopt($ch, CURLOPT_URL, $apiURL);
                    // Attach encoded JSON string to the POST fields
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
                    $resp = curl_exec ( $ch );
                    curl_close ( $ch );
                    $array_data =  json_decode($resp,true);
                    // print_r($array_data); die;
    
                    if($array_data['success'])
                    {
                        //check if ID number is new then insert into DB
                        $checkIDInDB= DB::table('dl_check_masters')->where(['dl_number'=>$final_dl_number])->count();
                        if($checkIDInDB ==0)
                        {
                            $gender = 'Male';
                            if($array_data['data']['gender'] == 'F'){
                                $gender = 'Female';
                            }
    
                            $dl_number      = $array_data['data']['license_number'];
                            $dl_raw         = preg_replace('/[^A-Za-z0-9\ ]/', '', $dl_number);
                            $final_number   = str_replace(' ', '', $dl_raw);
    
                            //
                            $data = [
                                    'api_client_id'     =>$array_data['data']['client_id'],
                                    'dl_number'         =>$final_number,
                                    'name'              =>$array_data['data']['name'],
                                    'permanent_address' =>$array_data['data']['permanent_address'],
                                    'temporary_address' =>$array_data['data']['temporary_address'],
                                    'permanent_zip'     =>$array_data['data']['permanent_zip'],
                                    'temporary_zip'     =>$array_data['data']['temporary_zip'],
                                    'state'             =>$array_data['data']['state'],
                                    'citizenship'       =>$array_data['data']['citizenship'],
                                    'ola_name'          =>$array_data['data']['ola_name'],
                                    'ola_code'          =>$array_data['data']['ola_code'],
                                    'gender'            =>$gender,
                                    'father_or_husband_name' =>$array_data['data']['father_or_husband_name'],
                                    'dob'               =>$array_data['data']['dob'],
                                    'doe'               =>$array_data['data']['doe'],
                                    'transport_doe'     =>$array_data['data']['transport_doe'],
                                    'doi'               =>$array_data['data']['doi'],
                                    'is_api_verified'       =>'1',
                                    'is_rc_exist'       =>'1',
                                    'created_at'        =>date('Y-m-d H:i:s')
                                    ];
                                
                                DB::table('dl_check_masters')->insert($data);
    
                                
                            
                            $master_data = DB::table('dl_check_masters')->select('*')->where(['dl_number'=>$final_dl_number])->first();
    
                            $log_data = [
                              'parent_id'         =>$parent_id,
                              'business_id'       => $business_id,
                              'service_id'        =>$service->service_id,
                              'candidate_id' => $service->candidate_id,
                              'source_type'       =>'API',
                              'api_client_id'     =>$master_data->api_client_id,
                              'dl_number'         =>$master_data->dl_number,
                              'name'              =>$master_data->name,
                              'permanent_address' =>$master_data->permanent_address,
                              'temporary_address' =>$master_data->temporary_address,
                              'permanent_zip'     =>$master_data->permanent_zip,
                              'temporary_zip'     =>$master_data->temporary_zip,
                              'state'             =>$master_data->state,
                              'citizenship'       =>$master_data->citizenship,
                              'ola_name'          =>$master_data->ola_name,
                              'ola_code'          =>$master_data->ola_code,
                              'gender'            =>$master_data->gender,
                              'father_or_husband_name' =>$master_data->father_or_husband_name,
                              'dob'               =>$master_data->dob,
                              'doe'               =>$master_data->doe,
                              'transport_doe'     =>$master_data->transport_doe,
                              'doi'               =>$master_data->doi,
                              'is_verified'       =>'1',
                              'is_rc_exist'       =>'1',
                              'price'             =>$price,
                              'used_by'           =>$user_type=='candidate'?'coc':'customer',
                              'user_id'            => $user_id,
                              'created_at'        =>date('Y-m-d H:i:s')
                              ];
                          
                          DB::table('dl_checks')->insert($log_data);
                        }
                        // update the status
                        DB::table('jaf_form_data')->where(['id'=>$service->id])
                        ->update(['is_api_checked'=>'1','is_api_verified'=>'1','verification_status'=>'success','verified_at'=>date('Y-m-d H:i:s'),'is_insufficiency'=>'0','clear_insuff_notes'=>'Auto Check DL Cleared','is_all_insuff_cleared' => 1,'insuff_cleared_by'=>$userDT->id,'updated_at'=>date('Y-m-d H:i:s')]); 

                        $ver_insuff=DB::table('verification_insufficiency')->where(['jaf_form_data_id'=>$service->id,'service_id'=>$service->service_id,'status'=>'removed'])->first();

                          if($ver_insuff!=NULL)
                          {
                              $ver_insuff_data=[
                                'notes' => 'Auto Check Dl Cleared',
                                'updated_by' => $userDT->id,
                                'updated_at' => date('Y-m-d H:i:s')
                              ];

                              DB::table('verification_insufficiency')->where(['jaf_form_data_id'=>$service->id,'service_id'=>$service->service_id,'status'=>'removed'])->update($ver_insuff_data);

                              $ver_id=$ver_insuff->id;
                          }
                          else
                          {
                            $ver_insuff_data=[
                              'parent_id' => $user_type=='candidate'?$super_parent_id:$parent_id,
                              'business_id' => $user_type=='candidate'?$parent_id:$business_id,
                              'coc_id' => $jaf_dl->business_id,
                              'candidate_id' => $service->candidate_id,
                              'service_id'  => $jaf_dl->service_id,
                              'jaf_form_data_id' => $jaf_dl->id,
                              'item_number' => $jaf_dl->check_item_number,
                              'activity_type'=> 'jaf-save',
                              'status'=>'removed',
                              'notes' => 'Auto Check Dl Cleared',
                              'created_by'   => $userDT->id,
                              'created_at'   => date('Y-m-d H:i:s'),
                              'updated_at' => date('Y-m-d H:i:s')
                            ];
                      
                            $ver_id=DB::table('verification_insufficiency')->insertGetId($ver_insuff_data);
                          }


                          $insuff_log_data=[
                            'parent_id' => $user_type=='candidate'?$super_parent_id:$parent_id,
                            'business_id' => $user_type=='candidate'?$parent_id:$business_id,
                            'coc_id' => $jaf_dl->business_id,
                            'candidate_id' => $service->candidate_id,
                            'service_id'  => $jaf_dl->service_id,
                            'jaf_form_data_id' => $jaf_dl->id,
                            'item_number' => $jaf_dl->check_item_number,
                            'activity_type'=> 'jaf-save',
                            'status'=>'removed',
                            'notes' => 'Auto Check Dl Cleared',
                            'created_by'   => $userDT->id,
                            'user_type'           =>$user_type=='candidate'?'coc':'customer',
                            'created_at'   => date('Y-m-d H:i:s'),
                          ];
                    
                          DB::table('insufficiency_logs')->insert($insuff_log_data);
                          



                          $task = Task::where(['business_id'=>$service->business_id,'candidate_id'=>$service->candidate_id,'is_completed'=>0,'description'=>'BGV Filling'])->first();
                          $task_id='';
                          if ($task) {
                            # code...
                            $task_id = $task->id;

                              $task->is_completed= 1;
                              $task->save();
                          
                          //Change status of old task 
                          $task_assgn = TaskAssignment::where(['business_id'=>$service->business_id,'candidate_id'=>$service->candidate_id,'status'=>"1",'task_id'=>$task_id])->first();
                          // dd($task_assgn);
                          if($task_assgn)
                          {
                            $task_assgn->status= '2';
                            $task_assgn->save();
                          }
                        }
    
                        $ver_insuff=DB::table('verification_insufficiency')->where(['id'=>$ver_id])->first();
    
                        $candidates=DB::table('users as u')
                            ->select('u.*','j.business_id as coc_id','j.id as jaf_id','v.created_at as insuff_clear_date','v.created_by as insuff_clear_by','v.item_number','v.notes','s.verification_type','s.name as service_name','v.business_id as cust_id','v.attachment','v.updated_at','v.updated_by')
                            ->join('jaf_form_data as j','u.id','=','j.candidate_id')
                            ->join('verification_insufficiency as v','v.jaf_form_data_id','=','j.id')
                            ->join('services as s','s.id','=','v.service_id')
                            ->where(['u.user_type'=>'candidate','j.id'=>$service->id,'v.status'=>'removed','v.id'=>$ver_insuff->id])
                            ->first();
    
                        if($candidates!=NULL)
                        {
                          // $client=DB::table('users')->where(['id'=>$candidates->coc_id])->first();
                          // $name = $client->name;
                          // $email = $client->email;
                          // $msg= "Insufficiency Cleared For Candidate";
                          // $sender = DB::table('users')->where(['id'=>$candidates->parent_id])->first();
                          // $data  = array('name'=>$name,'email'=>$email,'msg'=>$msg,'link'=>'','candidate'=>$candidates,'sender'=>$sender);
          
                          // Mail::send(['html'=>'mails.insuff-clear-notify'], $data, function($message) use($email,$name) {
                          //   $message->to($email, $name)->subject
                          //       ('Clobminds System - Insufficiency Notification');
                          //   $message->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
                          // });
    
                          $kams = DB::table('key_account_managers')->where(['business_id'=>$candidates->business_id])->get();
                          if(count($kams)>0)
                          {
                            foreach($kams as $kam)
                            {
                                $user_data=DB::table('users')->where(['id'=>$kam->user_id])->first();
    
                                $name1 = $user_data->name;
                                $email1 = $user_data->email;
                                $msg= "Insufficiency Cleared For Candidate";
                                $sender = DB::table('users')->where(['id'=>$candidates->parent_id])->first();
                                $data  = array('name'=>$name1,'email'=>$email1,'msg'=>$msg,'link'=>'','candidate'=>$candidates,'sender'=>$sender);
                                // EmailConfigTrait::emailConfig();
                                //get Mail config data
                                  //   $mail =null;
                                  $mail= Config::get('mail');
                                  // dd($mail['from']['address']);
                                  // if (count($mail)>0) {
                                  //     Mail::send(['html'=>'mails.insuff-clear-notify'], $data, function($message) use($email1,$name1,$mail) {
                                  //         $message->to($email1, $name1)->subject
                                  //         ('Clobminds System - Insufficiency Notification');
                                  //         $message->from($mail['from']['address'],$mail['from']['name']);
                                  //     });
                                  // }else {
                                      Mail::send(['html'=>'mails.insuff-clear-notify'], $data, function($message) use($email1,$name1) {
                                          $message->to($email1, $name1)->subject
                                              ('Clobminds System - Insufficiency Notification');
                                          $message->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
                                      });
                                  // }
          
                            }
                          }
    
    
                        }
    
                    }else{
                        //update insuff
                        DB::table('jaf_form_data')->where(['id'=>$service->id])->update(['is_api_checked'=>'1','is_api_verified'=>'0','is_insufficiency'=>'1','verification_status'=>'failed','verified_at'=>date('Y-m-d H:i:s')]); 
    
                        $jaf_data=DB::table('jaf_form_data')->where(['id'=>$service->id])->first();


                        $insuff_log_data=[
                              'parent_id' => $user_type=='candidate'?$super_parent_id:$parent_id,
                              'business_id' => $user_type=='candidate'?$parent_id:$business_id,
                              'coc_id' => $jaf_dl->business_id,
                              'candidate_id' => $service->candidate_id,
                              'service_id'  => $jaf_dl->service_id,
                              'jaf_form_data_id' => $jaf_dl->id,
                              'item_number' => $jaf_dl->check_item_number,
                              'activity_type'=> 'jaf-save',
                              'status'=>'failed',
                              'notes' => 'Auto Check Dl failed',
                              'created_by'   => $userDT->id,
                              'user_type'           =>$user_type=='candidate'?'coc':'customer',
                              'created_at'   => date('Y-m-d H:i:s'),
                            ];
                      
                            DB::table('insufficiency_logs')->insert($insuff_log_data);

                            $ver_insuff=DB::table('verification_insufficiency')->where(['jaf_form_data_id'=>$service->id,'service_id'=>$service->service_id,'status'=>'raised'])->first();

                          if($ver_insuff!=NULL)
                          {
                              $ver_insuff_data=[
                                'notes' => 'Auto check DL failed',
                                'updated_by' => $userDT->id,
                                'updated_at' => date('Y-m-d H:i:s')
                              ];

                              DB::table('verification_insufficiency')->where(['jaf_form_data_id'=>$service->id,'service_id'=>$service->service_id,'status'=>'raised'])->update($ver_insuff_data);

                              $ver_id=$ver_insuff->id;
                          }
                          else
                          {
                            $ver_insuff_data=[
                              'parent_id' => $user_type=='candidate'?$super_parent_id:$parent_id,
                              'business_id' => $user_type=='candidate'?$parent_id:$business_id,
                              'coc_id' => $service->business_id,
                              'candidate_id' => $service->candidate_id,
                              'service_id'  => $service->service_id,
                              'jaf_form_data_id' => $service->id,
                              'item_number' => $service->check_item_number,
                              'activity_type'=> 'jaf-save',
                              'status'=>'raised',
                              'notes' => 'Auto check DL failed',
                              'created_by'   => $userDT->id,
                              'created_at'   => date('Y-m-d H:i:s'),
                            ];
                      
                            $ver_id = DB::table('verification_insufficiency')->insertGetId($ver_insuff_data);
                          }
                           // Task insuff raised and assign to  CAM

                           $task = Task::where(['business_id'=>$service->business_id,'candidate_id'=>$service->candidate_id,'is_completed'=>0,'description'=>'BGV Filling'])->first();
                           $task_id='';
                           if ($task) {
                             # code...
                             $task_id = $task->id;
 
                               $task->is_completed= 1;
                               $task->save();
                           
                           //Change status of old task 
                           $task_assgn = TaskAssignment::where(['business_id'=>$service->business_id,'candidate_id'=>$service->candidate_id,'status'=>"1",'task_id'=>$task_id])->first();
                           // dd($task_assgn);
                           if($task_assgn)
                           {
                             $task_assgn->status= '2';
                             $task_assgn->save();
                           }
                         }
                           // task assign start
                           $final_users = [];
                           // $j = 0;
                           $job_sla_item = DB::table('job_sla_items')->where(['candidate_id'=>$service->candidate_id,'service_id'=>$service->service_id])->first();
             
                           // foreach ($job_sla_items as $job_sla_item) {
                             if ($job_sla_item) {
                               # code...
                           
                               $kam  = KeyAccountManager::where(['business_id'=>$service->business_id,'is_primary'=>'1'])->first();
                               if ($kam) {
                                 # code...
                              
                                   $final_users = [];
                                   $numbers_of_items = $job_sla_item->number_of_verifications;
                                   if($numbers_of_items > 0){
                                     for ($i=1; $i <= $numbers_of_items; $i++) { 
                                       
                                       $final_users = [];
                                       $user_name='';
                                       $user_name = DB::table('users')->where('id',$service->candidate_id)->first();
                                         //insert in task
                                         $data = [
                                           'name'          => $user_name->first_name.' '.$user_name->last_name,
                                           'parent_id'=> $user_name->parent_id,
                                           'business_id'   => $service->business_id, 
                                           'description'   => 'Task for Verification ',
                                           'job_id'        => NULL, 
                                           'priority'      => 'normal',
                                           'candidate_id'  => $service->candidate_id,   
                                           'service_id'    => $job_sla_item->service_id, 
                                           'number_of_verifications' => $i,
                                           'assigned_to'   => $kam->user_id,
                                           'assigned_by'   => $userDT->id,
                                           'assigned_at'   => date('Y-m-d H:i:s'),
                                           'start_date'    => date('Y-m-d'),
                                           'created_by'    => $userDT->id,
                                           'created_at'    => date('Y-m-d H:i:s'),
                                           'is_completed'  => 0,
                                           // 'started_at'    => date('Y-m-d H:i:s')
                                         ];
                                         // // dd($data);
                                         $task_id =  DB::table('tasks')->insertGetId($data); 
             
                                         $taskdata = [
                                           'parent_id'=> $user_name->parent_id,
                                           'business_id'   => $service->business_id,
                                           'candidate_id'  =>$service->candidate_id,   
                                           'job_sla_item_id'  => $job_sla_item->id,
                                           'task_id'       => $task_id,
                                          'user_id'       =>  $kam->user_id,
                                           'service_id'    =>$job_sla_item->service_id,
                                           'number_of_verifications' => $i,
                                           'status'=>'1',
                                           'created_at'    => date('Y-m-d H:i:s')  
                                         ];
                                         
                                         DB::table('task_assignments')->insertGetId($taskdata); 
                                         // DB::table('task_assignments')->insertGetId($taskdata); 
                                     }
                                   }
                                 }
                             }
                                        
      
                        
                    }
    
                    
                  
                  }

                }
                elseif($service->service_id==8)
                {
                  $jaf_passport = DB::table('jaf_form_data')->select('*')->where(['id'=>$service->id])->first();

                  $file_number="";
                  $dob = "";
                  // $business_id = $jaf_passport->business_id; 
                  $jaf_array = json_decode($jaf_passport->form_data, true);
                  // print_r($jaf_array);
                  foreach($jaf_array as $input){
                      if(array_key_exists('File Number',$input)){
                        $file_number = $input['File Number'];
                      }

                      if(array_key_exists('Date of Birth',$input) ){
                        $dob = $input['Date of Birth'];
                      }
                  }

                  //check first into master table
                  $master_data = DB::table('passport_check_masters')->select('*')->where(['file_number'=>$file_number])->first();
                  if($master_data !=null){

                    $log_data = [
                      'parent_id'         =>$parent_id,
                      'business_id'       =>$business_id,
                      'candidate_id' => $service->candidate_id,
                      'service_id'        =>$service->service_id,
                      'source_type'       =>'SystemDb',
                      'api_client_id'     =>$master_data->api_client_id,
                      'passport_number'   =>$master_data->passport_number,
                      'full_name'         =>$master_data->full_name,
                      'file_number'       =>$master_data->file_number,
                      'dob'               => $master_data->dob,
                      'date_of_application'=>$master_data->date_of_application,
                      'is_verified'       =>'1',
                      'is_passport_exist' =>'1',
                      'price'             =>$price,
                      'used_by'           =>$user_type=='candidate'?'coc':'customer',
                      'user_id'            => $user_id,
                      'created_at'        =>date('Y-m-d H:i:s')
                      ];

                      DB::table('passport_checks')->insert($log_data);
                      // update the status
                      DB::table('jaf_form_data')->where(['id'=>$service->id])
                      ->update(['is_api_checked'=>'1','is_api_verified'=>'1','verification_status'=>'success','verified_at'=>date('Y-m-d H:i:s'),'is_insufficiency'=>'0','clear_insuff_notes'=>'Auto Check Passport Cleared','is_all_insuff_cleared' => 1,'insuff_cleared_by'=>$userDT->id,'updated_at'=>date('Y-m-d H:i:s')]); 

                      $ver_insuff=DB::table('verification_insufficiency')->where(['jaf_form_data_id'=>$service->id,'service_id'=>$service->service_id,'status'=>'removed'])->first();

                        if($ver_insuff!=NULL)
                        {
                            $ver_insuff_data=[
                              'notes' => 'Auto Check Passport Cleared',
                              'updated_by' => $userDT->id,
                              'updated_at' => date('Y-m-d H:i:s')
                            ];

                            DB::table('verification_insufficiency')->where(['jaf_form_data_id'=>$service->id,'service_id'=>$service->service_id,'status'=>'removed'])->update($ver_insuff_data);

                            $ver_id=$ver_insuff->id;
                        }
                        else
                        {
                          $ver_insuff_data=[
                            'parent_id' => $user_type=='candidate'?$super_parent_id:$parent_id,
                            'business_id' => $user_type=='candidate'?$parent_id:$business_id,
                            'coc_id' => $jaf_passport->business_id,
                            'candidate_id' => $service->candidate_id,
                            'service_id'  => $jaf_passport->service_id,
                            'jaf_form_data_id' => $jaf_passport->id,
                            'item_number' => $jaf_passport->check_item_number,
                            'activity_type'=> 'jaf-save',
                            'status'=>'removed',
                            'notes' => 'Auto Check Passport Cleared',
                            'created_by'   => $userDT->id,
                            'created_at'   => date('Y-m-d H:i:s'),
                          ];
                    
                          $ver_id=DB::table('verification_insufficiency')->insertGetId($ver_insuff_data);
                        }

                          $insuff_log_data=[
                            'parent_id' => $user_type=='candidate'?$super_parent_id:$parent_id,
                            'business_id' => $user_type=='candidate'?$parent_id:$business_id,
                            'coc_id' => $jaf_passport->business_id,
                            'candidate_id' => $service->candidate_id,
                            'service_id'  => $jaf_passport->service_id,
                            'jaf_form_data_id' => $jaf_passport->id,
                            'item_number' => $jaf_passport->check_item_number,
                            'activity_type'=> 'jaf-save',
                            'status'=>'removed',
                            'notes' => 'Auto Check Passport Cleared',
                            'created_by'   => $userDT->id,
                            'user_type'           =>$user_type=='candidate'?'coc':'customer',
                            'created_at'   => date('Y-m-d H:i:s'),
                          ];
                    
                          DB::table('insufficiency_logs')->insert($insuff_log_data);


                          $task = Task::where(['business_id'=>$service->business_id,'candidate_id'=>$service->candidate_id,'is_completed'=>0,'description'=>'BGV Filling'])->first();
                          $task_id='';
                          if ($task) {
                            # code...
                            $task_id = $task->id;

                              $task->is_completed= 1;
                              $task->save();
                          
                          //Change status of old task 
                          $task_assgn = TaskAssignment::where(['business_id'=>$service->business_id,'candidate_id'=>$service->candidate_id,'status'=>"1",'task_id'=>$task_id])->first();
                          // dd($task_assgn);
                          if($task_assgn)
                          {
                            $task_assgn->status= '2';
                            $task_assgn->save();
                          }
                        }
                          $ver_insuff=DB::table('verification_insufficiency')->where(['id'=>$ver_id])->first();

                          $candidates=DB::table('users as u')
                              ->select('u.*','j.business_id as coc_id','j.id as jaf_id','v.created_at as insuff_clear_date','v.created_by as insuff_clear_by','v.item_number','v.notes','s.verification_type','s.name as service_name','v.business_id as cust_id','v.attachment','v.updated_at','v.updated_by')
                              ->join('jaf_form_data as j','u.id','=','j.candidate_id')
                              ->join('verification_insufficiency as v','v.jaf_form_data_id','=','j.id')
                              ->join('services as s','s.id','=','v.service_id')
                              ->where(['u.user_type'=>'candidate','j.id'=>$service->id,'v.status'=>'removed','v.id'=>$ver_insuff->id])
                              ->first();

                          if($candidates!=NULL)
                          {
                            // $client=DB::table('users')->where(['id'=>$candidates->coc_id])->first();
                            // $name = $client->name;
                            // $email = $client->email;
                            // $msg= "Insufficiency Cleared For Candidate";
                            // $sender = DB::table('users')->where(['id'=>$candidates->parent_id])->first();
                            // $data  = array('name'=>$name,'email'=>$email,'msg'=>$msg,'link'=>'','candidate'=>$candidates,'sender'=>$sender);
            
                            // Mail::send(['html'=>'mails.insuff-clear-notify'], $data, function($message) use($email,$name) {
                            //   $message->to($email, $name)->subject
                            //       ('Clobminds System - Insufficiency Notification');
                            //   $message->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
                            // });

                            $kams = DB::table('key_account_managers')->where(['business_id'=>$candidates->business_id])->get();
                            if(count($kams)>0)
                            {
                              foreach($kams as $kam)
                              {
                                  $user_data=DB::table('users')->where(['id'=>$kam->user_id])->first();

                                  $name1 = $user_data->name;
                                  $email1 = $user_data->email;
                                  $msg= "Insufficiency Cleared For Candidate";
                                  $sender = DB::table('users')->where(['id'=>$candidates->parent_id])->first();
                                  $data  = array('name'=>$name1,'email'=>$email1,'msg'=>$msg,'link'=>'','candidate'=>$candidates,'sender'=>$sender);

                                  // EmailConfigTrait::emailConfig();
                                  //get Mail config data
                                    //   $mail =null;
                                    $mail= Config::get('mail');
                                    // dd($mail['from']['address']);
                                    // if (count($mail)>0) {
                                    //     Mail::send(['html'=>'mails.insuff-clear-notify'], $data, function($message) use($email1,$name1,$mail) {
                                    //         $message->to($email1, $name1)->subject
                                    //         ('Clobminds System - Insufficiency Notification');
                                    //         $message->from($mail['from']['address'],$mail['from']['name']);
                                    //     });
                                    // }else {
                                      Mail::send(['html'=>'mails.insuff-clear-notify'], $data, function($message) use($email1,$name1) {
                                          $message->to($email1, $name1)->subject
                                              ('Clobminds System - Insufficiency Notification');
                                          $message->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
                                      });
                                    // }

                              }
                            }

                          }
                      
                  }
                  else{
                    //check from live API
                    // Setup request to send json via POST
                    $data = array(
                        'id_number' => $file_number,
                        'dob'       => date('Y-m-d',strtotime($dob)),
                        'async'         => true,
                    );
                    $payload = json_encode($data);
                    $apiURL = "https://kyc-api.aadhaarkyc.io/api/v1/passport/passport/passport-details";
    
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                
                    curl_setopt ($ch, CURLOPT_POST, 1);
                    $authorization = "Authorization: Bearer ".env('SUREPASS_PRODUCTION_TOKEN'); // Prepare the authorisation token
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization )); // Inject the token into the header
                    curl_setopt($ch, CURLOPT_URL, $apiURL);
                    // Attach encoded JSON string to the POST fields
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
                    $resp = curl_exec ( $ch );
                    curl_close ( $ch );
                    $array_data =  json_decode($resp,true);
                    
                    if($array_data['success'])
                    {
                        //check if ID number is new then insert into DB
                        $checkIDInDB= DB::table('passport_check_masters')->where(['file_number'=>$file_number])->count();
                        if($checkIDInDB ==0)
                        {
                            
                            $data = [
                                    'api_client_id'     =>$array_data['data']['client_id'],
                                    'passport_number'   =>$array_data['data']['passport_number'],
                                    'full_name'         =>$array_data['data']['full_name'],
                                    'file_number'       =>$array_data['data']['file_number'],
                                    'date_of_application'=>$array_data['data']['date_of_application'],
                                    'is_api_verified'       =>'1',
                                    'is_passport_exist' =>'1',
                                    'created_at'        =>date('Y-m-d H:i:s')
                                    ];
    
                            DB::table('passport_check_masters')->insert($data);
                            
                            $master_data = DB::table('passport_check_masters')->select('*')->where(['file_number'=>$file_number])->first();
    
                            $log_data = [
                              'parent_id'         =>$parent_id,
                              'business_id'       =>$business_id,
                              'candidate_id' => $service->candidate_id,
                              'service_id'        =>$service->service_id,
                              'source_type'       =>'API',
                              'api_client_id'     =>$master_data->api_client_id,
                              'passport_number'   =>$master_data->passport_number,
                              'full_name'         =>$master_data->full_name,
                              'file_number'       =>$master_data->file_number,
                              'dob'               => $master_data->dob,
                              'date_of_application'=>$master_data->date_of_application,
                              'is_verified'       =>'1',
                              'is_passport_exist' =>'1',
                              'price'             =>$price,
                              'used_by'           =>$user_type=='candidate'?'coc':'customer',
                              'user_id'            => $user_id,
                              'created_at'        =>date('Y-m-d H:i:s')
                              ];
          
                          DB::table('passport_checks')->insert($log_data);
                        }
                        // update the status
                        DB::table('jaf_form_data')->where(['id'=>$service->id])
                        ->update(['is_api_checked'=>'1','is_api_verified'=>'1','verification_status'=>'success','verified_at'=>date('Y-m-d H:i:s'),'is_insufficiency'=>'0','clear_insuff_notes'=>'Auto Check Passport Cleared','is_all_insuff_cleared' => 1,'insuff_cleared_by'=>$userDT->id,'updated_at'=>date('Y-m-d H:i:s')]); 
                        $is_updated=TRUE;

                        $ver_insuff=DB::table('verification_insufficiency')->where(['jaf_form_data_id'=>$service->id,'service_id'=>$service->service_id,'status'=>'removed'])->first();

                        if($ver_insuff!=NULL)
                        {
                            $ver_insuff_data=[
                              'notes' => 'Auto Check Passport Cleared',
                              'updated_by' => $userDT->id,
                              'updated_at' => date('Y-m-d H:i:s')
                            ];

                            DB::table('verification_insufficiency')->where(['jaf_form_data_id'=>$service->id,'service_id'=>$service->service_id,'status'=>'removed'])->update($ver_insuff_data);

                            $ver_id=$ver_insuff->id;
                        }
                        else
                        {
                          $ver_insuff_data=[
                            'parent_id' => $user_type=='candidate'?$super_parent_id:$parent_id,
                            'business_id' => $user_type=='candidate'?$parent_id:$business_id,
                            'coc_id' => $jaf_passport->business_id,
                            'candidate_id' => $service->candidate_id,
                            'service_id'  => $jaf_passport->service_id,
                            'jaf_form_data_id' => $jaf_passport->id,
                            'item_number' => $jaf_passport->check_item_number,
                            'activity_type'=> 'jaf-save',
                            'status'=>'removed',
                            'notes' => 'Auto Check Passport Cleared',
                            'created_by'   => $userDT->id,
                            'created_at'   => date('Y-m-d H:i:s'),
                          ];
                    
                          $ver_id=DB::table('verification_insufficiency')->insertGetId($ver_insuff_data);
                        }

                        $insuff_log_data=[
                          'parent_id' => $user_type=='candidate'?$super_parent_id:$parent_id,
                          'business_id' => $user_type=='candidate'?$parent_id:$business_id,
                          'coc_id' => $jaf_passport->business_id,
                          'candidate_id' => $service->candidate_id,
                          'service_id'  => $jaf_passport->service_id,
                          'jaf_form_data_id' => $jaf_passport->id,
                          'item_number' => $jaf_passport->check_item_number,
                          'activity_type'=> 'jaf-save',
                          'status'=>'removed',
                          'notes' => 'Auto Check Passport Cleared',
                          'created_by'   => $userDT->id,
                          'user_type'           =>$user_type=='candidate'?'coc':'customer',
                          'created_at'   => date('Y-m-d H:i:s'),
                        ];
                  
                        DB::table('insufficiency_logs')->insert($insuff_log_data);


                        $task = Task::where(['business_id'=>$service->business_id,'candidate_id'=>$service->candidate_id,'is_completed'=>0,'description'=>'BGV Filling'])->first();
                        $task_id='';
                        if ($task) {
                          # code...
                          $task_id = $task->id;

                            $task->is_completed= 1;
                            $task->save();
                        
                        //Change status of old task 
                        $task_assgn = TaskAssignment::where(['business_id'=>$service->business_id,'candidate_id'=>$service->candidate_id,'status'=>"1",'task_id'=>$task_id])->first();
                        // dd($task_assgn);
                        if($task_assgn)
                        {
                          $task_assgn->status= '2';
                          $task_assgn->save();
                        }
                      }
    
                        $ver_insuff=DB::table('verification_insufficiency')->where(['id'=>$ver_id])->first();
    
                        $candidates=DB::table('users as u')
                            ->select('u.*','j.business_id as coc_id','j.id as jaf_id','v.created_at as insuff_clear_date','v.created_by as insuff_clear_by','v.item_number','v.notes','s.verification_type','s.name as service_name','v.business_id as cust_id','v.attachment','v.updated_at','v.updated_by')
                            ->join('jaf_form_data as j','u.id','=','j.candidate_id')
                            ->join('verification_insufficiency as v','v.jaf_form_data_id','=','j.id')
                            ->join('services as s','s.id','=','v.service_id')
                            ->where(['u.user_type'=>'candidate','j.id'=>$service->id,'v.status'=>'removed','v.id'=>$ver_insuff->id])
                            ->first();
    
                        if($candidates!=NULL)
                        {
                          // $client=DB::table('users')->where(['id'=>$candidates->coc_id])->first();
                          // $name = $client->name;
                          // $email = $client->email;
                          // $msg= "Insufficiency Cleared For Candidate";
                          // $sender = DB::table('users')->where(['id'=>$candidates->parent_id])->first();
                          // $data  = array('name'=>$name,'email'=>$email,'msg'=>$msg,'link'=>'','candidate'=>$candidates,'sender'=>$sender);
          
                          // Mail::send(['html'=>'mails.insuff-clear-notify'], $data, function($message) use($email,$name) {
                          //   $message->to($email, $name)->subject
                          //       ('Clobminds System - Insufficiency Notification');
                          //   $message->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
                          // });
    
                          $kams = DB::table('key_account_managers')->where(['business_id'=>$candidates->business_id])->get();
                          if(count($kams)>0)
                          {
                            foreach($kams as $kam)
                            {
                                $user_data=DB::table('users')->where(['id'=>$kam->user_id])->first();
    
                                $name1 = $user_data->name;
                                $email1 = $user_data->email;
                                $msg= "Insufficiency Cleared For Candidate";
                                $sender = DB::table('users')->where(['id'=>$candidates->parent_id])->first();
                                $data  = array('name'=>$name1,'email'=>$email1,'msg'=>$msg,'link'=>'','candidate'=>$candidates,'sender'=>$sender);
                                // EmailConfigTrait::emailConfig();
                                //get Mail config data
                                  //   $mail =null;
                                  $mail= Config::get('mail');
                                  // dd($mail['from']['address']);
                                  // if (count($mail)>0) {
                                  //     Mail::send(['html'=>'mails.insuff-clear-notify'], $data, function($message) use($email1,$name1,$mail) {
                                  //         $message->to($email1, $name1)->subject
                                  //         ('Clobminds System - Insufficiency Notification');
                                  //         $message->from($mail['from']['address'],$mail['from']['name']);
                                  //     });
                                  // }else {
                                    Mail::send(['html'=>'mails.insuff-clear-notify'], $data, function($message) use($email1,$name1) {
                                        $message->to($email1, $name1)->subject
                                            ('Clobminds System - Insufficiency Notification');
                                        $message->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
                                    });
                                  // }
    
                            }
                          }
    
                        }
    
                        
                    }
                    else{
                        //update insuff
                        DB::table('jaf_form_data')->where(['id'=>$service->id])->update(['is_api_checked'=>'1','is_api_verified'=>'0','is_insufficiency'=>'1','verification_status'=>'failed','verified_at'=>date('Y-m-d H:i:s')]); 
    
                        $jaf_data=DB::table('jaf_form_data')->where(['id'=>$service->id])->first();
                        $insuff_log_data=[
                          'parent_id' => $user_type=='candidate'?$super_parent_id:$parent_id,
                          'business_id' => $user_type=='candidate'?$parent_id:$business_id,
                          'coc_id' => $jaf_data->business_id,
                          'candidate_id' => $service->candidate_id,
                          'service_id'  => $jaf_data->service_id,
                          'jaf_form_data_id' => $jaf_data->id,
                          'item_number' => $jaf_data->check_item_number,
                          'activity_type'=> 'jaf-save',
                          'status'=>'failed',
                          'notes' => 'Auto Check Passport Failed',
                          'created_by'   => $userDT->id,
                          'user_type'           =>$user_type=='candidate'?'coc':'customer',
                          'created_at'   => date('Y-m-d H:i:s'),
                        ];
                  
                        DB::table('insufficiency_logs')->insert($insuff_log_data);

                        $ver_insuff=DB::table('verification_insufficiency')->where(['jaf_form_data_id'=>$service->id,'service_id'=>$service->service_id,'status'=>'raised'])->first();

                          if($ver_insuff!=NULL)
                          {
                              $ver_insuff_data=[
                                'notes' => 'Auto check Passport failed',
                                'updated_by' => $userDT->id,
                                'updated_at' => date('Y-m-d H:i:s')
                              ];

                              DB::table('verification_insufficiency')->where(['jaf_form_data_id'=>$service->id,'service_id'=>$service->service_id,'status'=>'raised'])->update($ver_insuff_data);

                              $ver_id=$ver_insuff->id;
                          }
                          else
                          {
                            $ver_insuff_data=[
                              'parent_id' => $user_type=='candidate'?$super_parent_id:$parent_id,
                              'business_id' => $user_type=='candidate'?$parent_id:$business_id,
                              'coc_id' => $service->business_id,
                              'candidate_id' => $service->candidate_id,
                              'service_id'  => $service->service_id,
                              'jaf_form_data_id' => $service->id,
                              'item_number' => $service->check_item_number,
                              'activity_type'=> 'jaf-save',
                              'status'=>'raised',
                              'notes' => 'Auto check Passport failed',
                              'created_by'   => $userDT->id,
                              'created_at'   => date('Y-m-d H:i:s'),
                            ];
                      
                            $ver_id = DB::table('verification_insufficiency')->insertGetId($ver_insuff_data);
                          }
                           // Task insuff raised and assign to  CAM

                         
                           $task = Task::where(['business_id'=>$service->business_id,'candidate_id'=>$service->candidate_id,'is_completed'=>0,'description'=>'BGV Filling'])->first();
                           $task_id='';
                           if ($task) {
                             # code...
                             $task_id = $task->id;
 
                               $task->is_completed= 1;
                               $task->save();
                           
                           //Change status of old task 
                           $task_assgn = TaskAssignment::where(['business_id'=>$service->business_id,'candidate_id'=>$service->candidate_id,'status'=>"1",'task_id'=>$task_id])->first();
                           // dd($task_assgn);
                           if($task_assgn)
                           {
                             $task_assgn->status= '2';
                             $task_assgn->save();
                           }
                         }
                           // task assign start
                           $final_users = [];
                           // $j = 0;
                           $job_sla_item = DB::table('job_sla_items')->where(['candidate_id'=>$service->candidate_id,'service_id'=>$service->service_id])->first();
             
                           // foreach ($job_sla_items as $job_sla_item) {
                             if ($job_sla_item) {
                               # code...
                           
                               $kam  = KeyAccountManager::where(['business_id'=>$service->business_id,'is_primary'=>'1'])->first();
                               if ($kam) {
                                 # code...
                              
                                   $final_users = [];
                                   $numbers_of_items = $job_sla_item->number_of_verifications;
                                   if($numbers_of_items > 0){
                                     for ($i=1; $i <= $numbers_of_items; $i++) { 
                                       
                                       $final_users = [];
                                       $user_name='';
                                       $user_name = DB::table('users')->where('id',$service->candidate_id)->first();
                                         //insert in task
                                         $data = [
                                           'name'          => $user_name->first_name.' '.$user_name->last_name,
                                           'parent_id'=> $user_name->parent_id,
                                           'business_id'   => $service->business_id, 
                                           'description'   => 'Task for Verification ',
                                           'job_id'        => NULL, 
                                           'priority'      => 'normal',
                                           'candidate_id'  => $service->candidate_id,   
                                           'service_id'    => $job_sla_item->service_id, 
                                           'number_of_verifications' => $i,
                                           'assigned_to'   => $kam->user_id,
                                           'assigned_by'   => $userDT->id,
                                           'assigned_at'   => date('Y-m-d H:i:s'),
                                           'start_date'    => date('Y-m-d'),
                                           'created_by'    => $userDT->id,
                                           'created_at'    => date('Y-m-d H:i:s'),
                                           'is_completed'  => 0,
                                           // 'started_at'    => date('Y-m-d H:i:s')
                                         ];
                                         // // dd($data);
                                         $task_id =  DB::table('tasks')->insertGetId($data); 
             
                                         $taskdata = [
                                           'parent_id'=>$user_name->parent_id,
                                           'business_id'   => $service->business_id,
                                           'candidate_id'  =>$service->candidate_id,   
                                           'job_sla_item_id'  => $job_sla_item->id,
                                           'task_id'       => $task_id,
                                          'user_id'       =>  $kam->user_id,
                                           'service_id'    =>$job_sla_item->service_id,
                                           'number_of_verifications' => $i,
                                           'status'=>'1',
                                           'created_at'    => date('Y-m-d H:i:s')  
                                         ];
                                         
                                         DB::table('task_assignments')->insertGetId($taskdata); 
                                         // DB::table('task_assignments')->insertGetId($taskdata); 
                                     }
                                   }
                                 }
                             }
                                        
                    }
                  }

                }
                elseif($service->service_id==12)
                {
                  $jaf_bank = DB::table('jaf_form_data')->select('*')->where(['id'=>$service->id])->first();

                  // $passport_file_no = $request->input('id_number');
                  $account_no="";
                  $ifsc_code = "";
                  // $business_id = $jaf_bank->business_id; 
                  $jaf_array = json_decode($jaf_bank->form_data, true);
                  // print_r($jaf_array);
                    foreach($jaf_array as $input){
                        if(array_key_exists('Account Number',$input)){
                          $account_no = $input['Account Number'];
                        }
      
                        if(array_key_exists('IFSC Code',$input) ){
                          $ifsc_code = $input['IFSC Code'];
                        }
                    }

                    $master_data = DB::table('bank_account_check_masters')->select('*')->where(['account_number'=>$account_no,'ifsc_code'=>$ifsc_code])->first();
                    if($master_data !=null){

                      $log_data = [
                        'parent_id'         =>$parent_id,
                        'business_id'       =>$business_id,
                        'service_id'        =>$service->service_id,
                        'candidate_id' => $service->candidate_id,
                        'source_type'       =>'SystemDb',
                        'api_client_id'     =>$master_data->api_client_id,
                        'account_number'    =>$master_data->account_number,
                        'full_name'         =>$master_data->full_name,
                        'ifsc_code'         =>$master_data->ifsc_code,
                        'is_verified'       =>'1',
                        'is_account_exist' =>'1',
                        'price'             =>$price,
                        'used_by'           =>$user_type=='candidate'?'coc':'customer',
                        'user_id'            => $user_id,
                        'created_at'        =>date('Y-m-d H:i:s')
                        ];

                      DB::table('bank_account_checks')->insert($log_data);

                      DB::table('jaf_form_data')->where(['id'=>$service->id])
                                              ->update(['is_api_checked'=>'1','is_api_verified'=>'1','verification_status'=>'success','verified_at'=>date('Y-m-d H:i:s'),'is_insufficiency'=>'0','clear_insuff_notes'=>'Auto Check Bank Cleared','is_all_insuff_cleared' => 1,'insuff_cleared_by'=>$userDT->id,'updated_at'=>date('Y-m-d H:i:s')]); 

                      $ver_insuff=DB::table('verification_insufficiency')->where(['jaf_form_data_id'=>$service->id,'service_id'=>$service->service_id,'status'=>'removed'])->first();

                      if($ver_insuff!=NULL)
                      {
                          $ver_insuff_data=[
                            'notes' => 'JAF Cleared',
                            'updated_by' => $userDT->id,
                            'updated_at' => date('Y-m-d H:i:s')
                          ];
                
                          DB::table('verification_insufficiency')->where(['jaf_form_data_id'=>$service->id,'service_id'=>$service->service_id,'status'=>'removed'])->update($ver_insuff_data);
                
                          $ver_id=$ver_insuff->id;
                      }
                      else
                      {
                        $ver_insuff_data=[
                          'parent_id' => $user_type=='candidate'?$super_parent_id:$parent_id,
                          'business_id' => $user_type=='candidate'?$parent_id:$business_id,
                          'coc_id' => $jaf_bank->business_id,
                          'candidate_id' => $service->candidate_id,
                          'service_id'  => $jaf_bank->service_id,
                          'jaf_form_data_id' => $jaf_bank->id,
                          'item_number' => $jaf_bank->check_item_number,
                          'activity_type'=> 'jaf-save',
                          'status'=>'removed',
                          'notes' => 'Auto Check Bank Cleared',
                          'created_by'   => $userDT->id,
                          'created_at'   => date('Y-m-d H:i:s'),
                        ];
                  
                        $ver_id=DB::table('verification_insufficiency')->insertGetId($ver_insuff_data);
                      }

                      $insuff_log_data=[
                        'parent_id' => $user_type=='candidate'?$super_parent_id:$parent_id,
                        'business_id' => $user_type=='candidate'?$parent_id:$business_id,
                        'coc_id' => $jaf_bank->business_id,
                        'candidate_id' => $service->candidate_id,
                        'service_id'  => $jaf_bank->service_id,
                        'jaf_form_data_id' => $jaf_bank->id,
                        'item_number' => $jaf_bank->check_item_number,
                        'activity_type'=> 'jaf-save',
                        'status'=>'removed',
                        'notes' => 'Auto Check Bank Cleared',
                        'created_by'   => $userDT->id,
                        'user_type'           =>$user_type=='candidate'?'coc':'customer',
                        'created_at'   => date('Y-m-d H:i:s'),
                      ];
                
                      DB::table('insufficiency_logs')->insert($insuff_log_data);


                      $task = Task::where(['business_id'=>$service->business_id,'candidate_id'=>$service->candidate_id,'is_completed'=>0,'description'=>'BGV Filling'])->first();
                      $task_id='';
                      if ($task) {
                        # code...
                        $task_id = $task->id;

                          $task->is_completed= 1;
                          $task->save();
                      
                      //Change status of old task 
                      $task_assgn = TaskAssignment::where(['business_id'=>$service->business_id,'candidate_id'=>$service->candidate_id,'status'=>"1",'task_id'=>$task_id])->first();
                      // dd($task_assgn);
                      if($task_assgn)
                      {
                        $task_assgn->status= '2';
                        $task_assgn->save();
                      }
                    }
                      $ver_insuff=DB::table('verification_insufficiency')->where(['id'=>$ver_id])->first();

                      $candidates=DB::table('users as u')
                          ->select('u.*','j.business_id as coc_id','j.id as jaf_id','v.created_at as insuff_clear_date','v.created_by as insuff_clear_by','v.item_number','v.notes','s.verification_type','s.name as service_name','v.business_id as cust_id','v.attachment','v.updated_at','v.updated_by')
                          ->join('jaf_form_data as j','u.id','=','j.candidate_id')
                          ->join('verification_insufficiency as v','v.jaf_form_data_id','=','j.id')
                          ->join('services as s','s.id','=','v.service_id')
                          ->where(['u.user_type'=>'candidate','j.id'=>$service->id,'v.status'=>'removed','v.id'=>$ver_insuff->id])
                          ->first();

                          if($candidates!=NULL)
                          {
                            // $client=DB::table('users')->where(['id'=>$candidates->coc_id])->first();
                            // $name = $client->name;
                            // $email = $client->email;
                            // $msg= "Insufficiency Cleared For Candidate";
                            // $sender = DB::table('users')->where(['id'=>$candidates->parent_id])->first();
                            // $data  = array('name'=>$name,'email'=>$email,'msg'=>$msg,'link'=>'','candidate'=>$candidates,'sender'=>$sender);
            
                            // Mail::send(['html'=>'mails.insuff-clear-notify'], $data, function($message) use($email,$name) {
                            //   $message->to($email, $name)->subject
                            //       ('Clobminds System - Insufficiency Notification');
                            //   $message->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
                            // });

                            $kams = DB::table('key_account_managers')->where(['business_id'=>$candidates->business_id])->get();
                            if(count($kams)>0)
                            {
                              foreach($kams as $kam)
                              {
                                  $user_data=DB::table('users')->where(['id'=>$kam->user_id])->first();

                                  $name1 = $user_data->name;
                                  $email1 = $user_data->email;
                                  $msg= "Insufficiency Cleared For Candidate";
                                  $sender = DB::table('users')->where(['id'=>$candidates->parent_id])->first();
                                  $data  = array('name'=>$name1,'email'=>$email1,'msg'=>$msg,'link'=>'','candidate'=>$candidates,'sender'=>$sender);
                                  $sender = DB::table('users')->where(['id'=>$candidates->parent_id])->first();
                                  // EmailConfigTrait::emailConfig();
                                  //get Mail config data
                                    //   $mail =null;
                                    $mail= Config::get('mail');
                                    // dd($mail['from']['address']);
                                    // if (count($mail)>0) {
                                    //     Mail::send(['html'=>'mails.insuff-clear-notify'], $data, function($message) use($email1,$name1,$mail) {
                                    //         $message->to($email1, $name1)->subject
                                    //         ('Clobminds System - Insufficiency Notification');
                                    //         $message->from($mail['from']['address'],$mail['from']['name']);
                                    //     });
                                    // }else {

                                      Mail::send(['html'=>'mails.insuff-clear-notify'], $data, function($message) use($email1,$name1) {
                                          $message->to($email1, $name1)->subject
                                              ('Clobminds System - Insufficiency Notification');
                                          $message->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
                                      });
                                    // }

                              }
                            }

                          }

                    }
                    else{
                      //check from live API
                      // Setup request to send json via POST
                      $data = array(
                        'id_number' => $account_no,
                        'ifsc'      => $ifsc_code,
                        'async'         => true,
                        );
                        $payload = json_encode($data);
                        $apiURL = "https://kyc-api.aadhaarkyc.io/api/v1/bank-verification/";
      
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                
                        curl_setopt ($ch, CURLOPT_POST, 1);
                        $authorization = "Authorization: Bearer ".env('SUREPASS_PRODUCTION_TOKEN'); // Prepare the authorisation token
                        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization )); // Inject the token into the header
                        curl_setopt($ch, CURLOPT_URL, $apiURL);
                        // Attach encoded JSON string to the POST fields
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
                        $resp = curl_exec ( $ch );
                        curl_close ( $ch );
                        $array_data =  json_decode($resp,true);
                        // var_dump($resp); die;
                        if($array_data['success'])
                        {
                          //check if ID number is new then insert into DB
                          $checkIDInDB= DB::table('bank_account_check_masters')->where(['account_number'=>$account_no,'ifsc_code'=>$ifsc_code])->count();
                          if($checkIDInDB ==0)
                          {
                              
                              $data = [
                                      'api_client_id'     =>$array_data['data']['client_id'],
                                      'account_number'    =>$account_no,
                                      'full_name'         =>$array_data['data']['full_name'],
                                      'ifsc_code'         =>$ifsc_code,
                                      'is_verified'       =>'1',
                                      'is_account_exist' =>'1',
                                      'created_at'        =>date('Y-m-d H:i:s')
                                      ];
      
                              DB::table('bank_account_check_masters')->insert($data);
                              
                              $master_data = DB::table('bank_account_check_masters')->select('*')->where(['account_number'=>$account_no])->first();
      
                              $log_data = [
                                'parent_id'         =>$parent_id,
                                'business_id'       =>$business_id,
                                'service_id'        =>$service->service_id,
                                'candidate_id' => $service->candidate_id,
                                'source_type'       =>'API',
                                'api_client_id'     =>$master_data->api_client_id,
                                'account_number'    =>$master_data->account_number,
                                'full_name'         =>$master_data->full_name,
                                'ifsc_code'         =>$master_data->ifsc_code,
                                'is_verified'       =>'1',
                                'is_account_exist' =>'1',
                                'price'             =>$price,
                                'used_by'           =>$user_type=='candidate'?'coc':'customer',
                                'user_id'            => $user_id,
                                'created_at'        =>date('Y-m-d H:i:s')
                                ];
            
                            DB::table('bank_account_checks')->insert($log_data);
                          }
                          // update the status
                          DB::table('jaf_form_data')->where(['id'=>$service->id])
                                              ->update(['is_api_checked'=>'1','is_api_verified'=>'1','verification_status'=>'success','verified_at'=>date('Y-m-d H:i:s'),'is_insufficiency'=>'0','clear_insuff_notes'=>'Auto Check Bank Cleared','is_all_insuff_cleared' => 1,'insuff_cleared_by'=>$userDT->id,'updated_at'=>date('Y-m-d H:i:s')]); 
                          $is_updated=TRUE;

                          $ver_insuff=DB::table('verification_insufficiency')->where(['jaf_form_data_id'=>$service->id,'service_id'=>$service->service_id,'status'=>'removed'])->first();

                          if($ver_insuff!=NULL)
                          {
                              $ver_insuff_data=[
                                'notes' => 'Auto Check Bank Cleared',
                                'updated_by' => $userDT->id,
                                'updated_at' => date('Y-m-d H:i:s')
                              ];

                              DB::table('verification_insufficiency')->where(['jaf_form_data_id'=>$service->id,'service_id'=>$service->service_id,'status'=>'removed'])->update($ver_insuff_data);

                              $ver_id=$ver_insuff->id;
                          }
                          else
                          {
                            $ver_insuff_data=[
                              'parent_id' => $user_type=='candidate'?$super_parent_id:$parent_id,
                              'business_id' => $user_type=='candidate'?$parent_id:$business_id,
                              'coc_id' => $jaf_bank->business_id,
                              'candidate_id' => $service->candidate_id,
                              'service_id'  => $jaf_bank->service_id,
                              'jaf_form_data_id' => $jaf_bank->id,
                              'item_number' => $jaf_bank->check_item_number,
                              'activity_type'=> 'jaf-save',
                              'status'=>'removed',
                              'notes' => 'Auto Check Bank Cleared',
                              'created_by'   => $userDT->id,
                              'created_at'   => date('Y-m-d H:i:s'),
                            ];

                            $ver_id=DB::table('verification_insufficiency')->insertGetId($ver_insuff_data);
                          }
                          

                          $insuff_log_data=[
                            'parent_id' => $user_type=='candidate'?$super_parent_id:$parent_id,
                            'business_id' => $user_type=='candidate'?$parent_id:$business_id,
                            'coc_id' => $jaf_bank->business_id,
                            'candidate_id' => $service->candidate_id,
                            'service_id'  => $jaf_bank->service_id,
                            'jaf_form_data_id' => $jaf_bank->id,
                            'item_number' => $jaf_bank->check_item_number,
                            'activity_type'=> 'jaf-save',
                            'status'=>'removed',
                            'notes' => 'Auto Check Bank Cleared',
                            'created_by'   => $userDT->id,
                            'user_type'           =>$user_type=='candidate'?'coc':'customer',
                            'created_at'   => date('Y-m-d H:i:s'),
                          ];
                    
                          DB::table('insufficiency_logs')->insert($insuff_log_data);
                        
                          $task = Task::where(['business_id'=>$service->business_id,'candidate_id'=>$service->candidate_id,'is_completed'=>0,'description'=>'BGV Filling'])->first();
                          $task_id='';
                          if ($task) {
                            # code...
                            $task_id = $task->id;

                              $task->is_completed= 1;
                              $task->save();
                          
                          //Change status of old task 
                          $task_assgn = TaskAssignment::where(['business_id'=>$service->business_id,'candidate_id'=>$service->candidate_id,'status'=>"1",'task_id'=>$task_id])->first();
                          // dd($task_assgn);
                          if($task_assgn)
                          {
                            $task_assgn->status= '2';
                            $task_assgn->save();
                          }
                        }
                          $ver_insuff=DB::table('verification_insufficiency')->where(['id'=>$ver_id])->first();
      
                          $candidates=DB::table('users as u')
                              ->select('u.*','j.business_id as coc_id','j.id as jaf_id','v.created_at as insuff_clear_date','v.created_by as insuff_clear_by','v.item_number','v.notes','s.verification_type','s.name as service_name','v.business_id as cust_id','v.attachment','v.updated_at','v.updated_by')
                              ->join('jaf_form_data as j','u.id','=','j.candidate_id')
                              ->join('verification_insufficiency as v','v.jaf_form_data_id','=','j.id')
                              ->join('services as s','s.id','=','v.service_id')
                              ->where(['u.user_type'=>'candidate','j.id'=>$service->id,'v.status'=>'removed','v.id'=>$ver_insuff->id])
                              ->first();
      
                          if($candidates!=NULL)
                          {
                            // $client=DB::table('users')->where(['id'=>$candidates->coc_id])->first();
                            // $name = $client->name;
                            // $email = $client->email;
                            // $msg= "Insufficiency Cleared For Candidate";
                            // $sender = DB::table('users')->where(['id'=>$candidates->parent_id])->first();
                            // $data  = array('name'=>$name,'email'=>$email,'msg'=>$msg,'link'=>'','candidate'=>$candidates,'sender'=>$sender);
            
                            // Mail::send(['html'=>'mails.insuff-clear-notify'], $data, function($message) use($email,$name) {
                            //   $message->to($email, $name)->subject
                            //       ('Clobminds System - Insufficiency Notification');
                            //   $message->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
                            // });
      
                            $kams = DB::table('key_account_managers')->where(['business_id'=>$candidates->business_id])->get();
                            if(count($kams)>0)
                            {
                              foreach($kams as $kam)
                              {
                                  $user_data=DB::table('users')->where(['id'=>$kam->user_id])->first();
      
                                  $name1 = $user_data->name;
                                  $email1 = $user_data->email;
                                  $msg= "Insufficiency Cleared For Candidate";
                                  $sender = DB::table('users')->where(['id'=>$candidates->parent_id])->first();
                                  $data  = array('name'=>$name1,'email'=>$email1,'msg'=>$msg,'link'=>'','candidate'=>$candidates,'sender'=>$sender);
                                  // EmailConfigTrait::emailConfig();
                                  //get Mail config data
                                    //   $mail =null;
                                    $mail= Config::get('mail');
                                    // dd($mail['from']['address']);
                                    // if (count($mail)>0) {
                                    //     Mail::send(['html'=>'mails.insuff-clear-notify'], $data, function($message) use($email1,$name1,$mail) {
                                    //         $message->to($email1, $name1)->subject
                                    //         ('Clobminds System - Insufficiency Notification');
                                    //         $message->from($mail['from']['address'],$mail['from']['name']);
                                    //     });
                                    // }else {
                                      Mail::send(['html'=>'mails.insuff-clear-notify'], $data, function($message) use($email1,$name1) {
                                          $message->to($email1, $name1)->subject
                                              ('Clobminds System - Insufficiency Notification');
                                          $message->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
                                      });
                                    // }
      
                              }
                            }
      
                          }
      
                        }
                        else{
                          //update insuff
      
                          DB::table('jaf_form_data')->where(['id'=>$service->id])->update(['is_api_checked'=>'1','is_api_verified'=>'0','is_insufficiency'=>'1','verification_status'=>'failed','verified_at'=>date('Y-m-d H:i:s')]); 
      
                          $jaf_data=DB::table('jaf_form_data')->where(['id'=>$service->id])->first();
                          $insuff_log_data=[
                            'parent_id' => $user_type=='candidate'?$super_parent_id:$parent_id,
                            'business_id' => $user_type=='candidate'?$parent_id:$business_id,
                            'coc_id' => $jaf_bank->business_id,
                            'candidate_id' => $service->candidate_id,
                            'service_id'  => $jaf_bank->service_id,
                            'jaf_form_data_id' => $jaf_bank->id,
                            'item_number' => $jaf_bank->check_item_number,
                            'activity_type'=> 'jaf-save',
                            'status'=>'failed',
                            'notes' => 'Auto check Bank failed',
                            'created_by'   => $userDT->id,
                            'user_type'           =>$user_type=='candidate'?'coc':'customer',
                            'created_at'   => date('Y-m-d H:i:s'),
                          ];
                    
                          DB::table('insufficiency_logs')->insert($insuff_log_data);

                          $ver_insuff=DB::table('verification_insufficiency')->where(['jaf_form_data_id'=>$service->id,'service_id'=>$service->service_id,'status'=>'raised'])->first();

                          if($ver_insuff!=NULL)
                          {
                              $ver_insuff_data=[
                                'notes' => 'Auto check bank failed',
                                'updated_by' => $userDT->id,
                                'updated_at' => date('Y-m-d H:i:s')
                              ];

                              DB::table('verification_insufficiency')->where(['jaf_form_data_id'=>$service->id,'service_id'=>$service->service_id,'status'=>'raised'])->update($ver_insuff_data);

                              $ver_id=$ver_insuff->id;
                          }
                          else
                          {
                            $ver_insuff_data=[
                              'parent_id' => $user_type=='candidate'?$super_parent_id:$parent_id,
                              'business_id' => $user_type=='candidate'?$parent_id:$business_id,
                              'coc_id' => $service->business_id,
                              'candidate_id' => $service->candidate_id,
                              'service_id'  => $service->service_id,
                              'jaf_form_data_id' => $service->id,
                              'item_number' => $service->check_item_number,
                              'activity_type'=> 'jaf-save',
                              'status'=>'raised',
                              'notes' => 'Auto check bank failed',
                              'created_by'   => $userDT->id,
                              'created_at'   => date('Y-m-d H:i:s'),
                            ];
                      
                            $ver_id = DB::table('verification_insufficiency')->insertGetId($ver_insuff_data);
                          }
                           // Task insuff raised and assign to  CAM

                           $task = Task::where(['business_id'=>$service->business_id,'candidate_id'=>$service->candidate_id,'is_completed'=>0,'description'=>'BGV Filling'])->first();
                           $task_id='';
                           if ($task) {
                             # code...
                             $task_id = $task->id;
 
                               $task->is_completed= 1;
                               $task->save();
                           
                           //Change status of old task 
                           $task_assgn = TaskAssignment::where(['business_id'=>$service->business_id,'candidate_id'=>$service->candidate_id,'status'=>"1",'task_id'=>$task_id])->first();
                           // dd($task_assgn);
                           if($task_assgn)
                           {
                             $task_assgn->status= '2';
                             $task_assgn->save();
                           }
                         }
                           // task assign start
                           $final_users = [];
                           // $j = 0;
                           $job_sla_item = DB::table('job_sla_items')->where(['candidate_id'=>$service->candidate_id,'service_id'=>$service->service_id])->first();
             
                           // foreach ($job_sla_items as $job_sla_item) {
                             if ($job_sla_item) {
                               # code...
                           
                               $kam  = KeyAccountManager::where(['business_id'=>$service->business_id,'is_primary'=>'1'])->first();
                               if ($kam) {
                                 # code...
                              
                                   $final_users = [];
                                   $numbers_of_items = $job_sla_item->number_of_verifications;
                                   if($numbers_of_items > 0){
                                     for ($i=1; $i <= $numbers_of_items; $i++) { 
                                       
                                       $final_users = [];
                                       $user_name='';
                                       $user_name = DB::table('users')->where('id',$service->candidate_id)->first();
                                         //insert in task
                                         $data = [
                                           'name'          => $user_name->first_name.' '.$user_name->last_name,
                                           'parent_id'=> $user_name->parent_id,
                                           'business_id'   => $service->business_id, 
                                           'description'   => 'Task for Verification ',
                                           'job_id'        => NULL, 
                                           'priority'      => 'normal',
                                           'candidate_id'  => $service->candidate_id,   
                                           'service_id'    => $job_sla_item->service_id, 
                                           'number_of_verifications' => $i,
                                           'assigned_to'   => $kam->user_id,
                                           'assigned_by'   => $userDT->id,
                                           'assigned_at'   => date('Y-m-d H:i:s'),
                                           'start_date'    => date('Y-m-d'),
                                           'created_by'    => $userDT->id,
                                           'created_at'    => date('Y-m-d H:i:s'),
                                           'is_completed'  => 0,
                                           // 'started_at'    => date('Y-m-d H:i:s')
                                         ];
                                         // // dd($data);
                                         $task_id =  DB::table('tasks')->insertGetId($data); 
             
                                         $taskdata = [
                                           'parent_id'=> $user_name->parent_id,
                                           'business_id'   => $service->business_id,
                                           'candidate_id'  =>$service->candidate_id,   
                                           'job_sla_item_id'  => $job_sla_item->id,
                                           'task_id'       => $task_id,
                                          'user_id'       =>  $kam->user_id,
                                           'service_id'    =>$job_sla_item->service_id,
                                           'number_of_verifications' => $i,
                                           'status'=>'1',
                                           'created_at'    => date('Y-m-d H:i:s')  
                                         ];
                                         
                                         DB::table('task_assignments')->insertGetId($taskdata); 
                                         // DB::table('task_assignments')->insertGetId($taskdata); 
                                     }
                                   }
                                 }
                             }
                          
                        }
      
                    }
                }
              } 
            }
        }
    }
}
