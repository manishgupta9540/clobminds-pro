<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Helpers\Helper;
use Illuminate\Support\Facades\Mail;

class DailyNotificationControl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dailynotification:control';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends a Mail Notication to COC & its Candidate for BGV Not Filled & Insufficiency Raised';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Notification for BGV Not Filled

        $clients = User::from('users as u')
                        ->select('u.*')
                        ->join('notification_controls as n','u.id','=','n.business_id')
                        ->where('u.user_type','client')
                        ->where(['u.status'=>1,'n.type'=>'jaf-not-filled','n.status'=>1])
                        ->get();

        if(count($clients)>0)
        {
            foreach($clients as $client)
            {
                $parent_id = $client->parent_id;

                // Send Email to Customer for BGV Not Filled
                $notification_controls = DB::table('notification_control_configs as nc')
                                        ->select('nc.*')
                                        ->join('notification_controls as n','n.business_id','=','nc.business_id')
                                        ->where(['n.status'=>1,'nc.status'=>1,'n.business_id'=>$client->business_id,'n.type'=>'jaf-not-filled','nc.type'=>'jaf-not-filled'])
                                        ->get();

                if(count($notification_controls)>0)
                {

                    $candidate_jaf = User::from('users as u')
                                        ->select('u.*')
                                        ->join('job_items as j','j.candidate_id','=','u.id')
                                        ->where(['u.user_type'=>'candidate','u.is_deleted'=>0,'u.business_id'=>$client->id])
                                        ->where('j.jaf_status','<>','filled')
                                        ->get();

                    if(count($candidate_jaf)>0)
                    {
                        foreach($notification_controls as $item)
                        {
                            $email = $item->email;
                            $name = $item->name;
                            $sender = User::from('users')->where(['id'=>$parent_id])->first();

                            $data = array('name'=>$name,'email'=>$email,'candidates'=>$candidate_jaf,'sender'=>$sender);

                            Mail::send(['html'=>'mails.client-jaf-not-filled'], $data, function($message) use($email,$name) {
                                $message->to($email, $name)->subject
                                    (env('MAIL_FROM_NAME'));
                                $message->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
                            });
                        }
                        
                    }

                    $candidate_jaf_link = User::from('users as u')
                                                ->Distinct('ji.candidate_id')
                                                ->select('u.*')
                                                ->join('job_items as j','j.candidate_id','=','u.id')
                                                ->join('job_sla_items as ji','j.id','=','ji.job_item_id')
                                                ->where(['u.user_type'=>'candidate','u.is_deleted'=>0,'u.business_id'=>$client->id])
                                                ->whereNotNull('u.email')
                                                ->where('j.jaf_status','<>','filled')
                                                ->where('ji.jaf_send_to','candidate')
                                                ->groupBy('ji.candidate_id')
                                                ->get();
                    
                    if(count($candidate_jaf_link)>0)
                    {
                        foreach($candidate_jaf_link as $candidate)
                        {
                            $email = $candidate->email;
                            
                            $name = $candidate->first_name;

                            $msg = 'Kindly fill the Job Application Form that we have already been sent to you.';                  

                            $sender = User::from('users')->where(['id'=>$candidate->business_id])->first();

                            $candidate_data = User::from('users')->where(['id'=>$candidate->id])->first();

                            $data = array('name'=>$name,'email'=>$email,'sender'=>$sender,'msg'=>$msg,'candidate'=>$candidate_data);

                            Mail::send(['html'=>'mails.candidate-jaf-not-filled'], $data, function($message) use($email,$name) {
                                $message->to($email, $name)->subject
                                    (env('MAIL_FROM_NAME'));
                                $message->from(env('MAIL_USERNAME'), env('MAIL_FROM_NAME'));
                            });


                        }
                    }

                }

            }

            $this->info('Notification for BGV Not Filled Created Successfully at '.date('Y-m-d h:i A'));
        }

        // Notification for Insufficiency

        $clients = User::from('users as u')
                        ->select('u.*')
                        ->join('notification_controls as n','u.id','=','n.business_id')
                        ->where('u.user_type','client')
                        ->where(['u.status'=>1,'n.type'=>'case-insuff','n.status'=>1])
                        ->get();

        if(count($clients)>0)
        {
            foreach($clients as $client)
            {

                // Send Email to Customer for Insufficiency
                $notification_controls = DB::table('notification_control_configs as nc')
                                        ->select('nc.*')
                                        ->join('notification_controls as n','n.business_id','=','nc.business_id')
                                        ->where(['n.status'=>1,'nc.status'=>1,'n.business_id'=>$client->business_id,'n.type'=>'case-insuff','nc.type'=>'case-insuff'])
                                        ->get();

                if(count($notification_controls)>0)
                {
                    
                    $parent_id = $client->parent_id;

                    $candidate_jaf = User::from('users as u')
                                        ->select('u.*',DB::raw('group_concat(DISTINCT jf.id) as jaf_id'))
                                        ->join('job_items as j','j.candidate_id','=','u.id')
                                        ->join('jaf_form_data as jf','jf.job_item_id','=','j.id')
                                        ->where(['u.user_type'=>'candidate','u.is_deleted'=>0,'u.business_id'=>$client->id,'jf.is_insufficiency'=>0])
                                        ->where('j.jaf_status','=','filled')
                                        ->whereDate('u.created_at','>=',date('Y-m-d',strtotime('19-04-2022')))
                                        ->groupBy('jf.candidate_id')
                                        ->get();

                    if(count($candidate_jaf)>0)
                    {
                        // $email = $client->email;
                        // $name = $client->first_name;

                        foreach($notification_controls as $item)
                        {
                            $email = $item->email;
                            $name = $item->name;
                            $sender = User::from('users')->where(['id'=>$parent_id])->first();

                            $data = array('name'=>$name,'email'=>$email,'candidates'=>$candidate_jaf,'sender'=>$sender);
    
                            Mail::send(['html'=>'mails.client-case-insuff'], $data, function($message) use($email,$name) {
                                $message->to($email, $name)->subject
                                    ('Insufficiency Notification');
                                $message->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
                            });
                        }
                        

                        foreach($candidate_jaf as $candidate)
                        {
                            $jaf_id  = [];

                            $jaf_id=explode(',',$candidate->jaf_id);

                            $email = $candidate->email;
                            
                            $name = $candidate->first_name;

                            $msg = 'Insufficiency has been Raised in your BGV Form';                  

                            $sender = User::from('users')->where(['id'=>$candidate->business_id])->first();

                            $candidate_data = User::from('users')->where(['id'=>$candidate->id])->first();

                            $data = array('name'=>$name,'email'=>$email,'sender'=>$sender,'msg'=>$msg,'candidate'=>$candidate_data,'jaf_id'=>$jaf_id);

                            Mail::send(['html'=>'mails.candidate-case-insuff'], $data, function($message) use($email,$name) {
                                $message->to($email, $name)->subject
                                    ('Insufficiency Notification');
                                $message->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
                            });


                        }


                    }

                }
                
            }

            $this->info('Notification for Insufficiency Created Successfully at '.date('Y-m-d h:i A'));
        }

        return 0;
    }
}
