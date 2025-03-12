<?php

namespace App\Http\Controllers\Admin;

use App\Exports\allChecksExport;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Admin\Task;
use App\Models\Admin\TaskAssignment;
use App\Models\Admin\VendorTaskAttachment;
use App\Models\Vendor\VendorTask;
use App\Models\Vendor\VendorTaskAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use App\Traits\EmailConfigTrait;
use Illuminate\Support\Facades\Config;


class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // dd($request);,compact()
        $user_id = Auth::user()->id;
        $business_id =Auth::user()->business_id;
        $cam=[];
        $kams = DB::table('key_account_managers')->where(['user_id'=>$user_id])->get();
        foreach ($kams as $kam) {
           $cam[]= $kam->user_id;
        }
        // dd($cam);
        //   if (Auth::user()->user_type=='customer') {
          # code...
        $rows=10;
        if (Auth::user()->user_type=='customer' || in_array($user_id,$cam)) {

            $tasks =DB::table('tasks as t')
            ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
            ->join('users as u','t.candidate_id', '=', 'u.id')
            ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.tat','ta.reassign_by','ta.created_at as created','u.display_id')
            ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id])
            ->whereIn('ta.status',['1','2','3']);
            // if(count($kams)>0)
            // {
            //     //dd($kams->pluck('business_id'));
            //     $tasks->whereIn('t.business_id',$kams->pluck('business_id'));
            // }

        }
        else{
            $tasks =DB::table('tasks as t')
                ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
                ->join('users as u', 't.candidate_id', '=', 'u.id')
                ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.tat','ta.reassign_by','ta.created_at as created','u.display_id')
                ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id])
                ->whereIn('ta.status',['1','2','3'])
                ->whereNotNull('t.assigned_to')
                ->whereRaw('IF (ta.reassign_to IS NOT NULL, ta.reassign_to='.Auth::user()->id.', ta.user_id='.Auth::user()->id.')');
               
        }
            if($request->get('from_date') !=""){
                $tasks->whereDate('t.created_at','>=',date('Y-m-d',strtotime($request->get('from_date'))));
            }
            if($request->get('to_date') !=""){
                $tasks->whereDate('t.created_at','<=',date('Y-m-d',strtotime($request->get('to_date'))));
            }
            if($request->get('ref')){
                $tasks->where('u.display_id',$request->get('ref'));
            }
            if(is_numeric($request->get('customer_id'))){
                 $tasks->where('t.business_id',$request->get('customer_id'));
            }
            if(is_numeric($request->get('candidate_id'))){
                 $tasks->where('t.candidate_id',$request->get('candidate_id'));
            }
            if(is_numeric($request->get('service_id'))){
            // echo($request->get('service_id'));
                $tasks->where('t.service_id',$request->get('service_id'));
            }
            if(is_numeric($request->get('user_id'))){
                $tasks->where('ta.user_id',$request->get('user_id'));
            }
            if($request->get('task_type')){
                $tasks->where('t.description',$request->get('task_type'));
            }
            if($request->get('assign_status')){
               
                if ($request->get('assign_status')=='assigned') {
                    $tasks->whereNotNull('t.assigned_to');
                }
                else{
                    $tasks->whereNull('t.assigned_to');
                }
               
            }
            if(is_numeric($request->get('complete_status'))){
                // echo($request->get('complete_status'));
                $tasks->where('t.is_completed',$request->get('complete_status'));
            }
            if ($request->get('search')) {
                // $searchQuery = '%' . $request->search . '%';
                $tasks->where('u.name',$request->get('search'))->orWhere('u.display_id',$request->get('search'));
              }
            if ($request->get('rows')!='') {
                $rows = $request->get('rows');
            }
            $tasks=$tasks->orderBy('ta.updated_at','DESC')->paginate($rows);
            // dd($tasks);
                // }else{
                //     $kam_task =DB::table('tasks as t') var assign_status = $("#assign_status option:selected").val();+'&complete_status='+complete_status
                //     ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
                //     ->join('users as u', 't.candidate_id', '=', 'u.id')
                //     ->join('key_account_managers as kam','kam.business_id','=','t.business_id')
                //     ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.tat','ta.reassign_by','ta.created_at as created')
                //     ->where(['u.is_deleted'=>'0','kam.user_id'=>Auth::user()->id])
                //     ->whereIn('ta.status',['1','2'])->orderBy('id','DESC');
                //     $tasks=$kam_task->paginate(10);
                // }
                // $normal_task =DB::table('tasks as t')
                // ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
                // ->join('users as u', 't.candidate_id', '=', 'u.id')
                // ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.tat','ta.reassign_by','ta.created_at as created')
                // ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id])
                // ->whereIn('ta.status',['1','2'])
                // ->whereNotNull('t.assigned_to')
                // ->whereRaw('IF (ta.reassign_to IS NOT NULL, ta.reassign_to='.Auth::user()->id.', t.assigned_to='.Auth::user()->id.')')->get();
                
                // $normal_task =DB::table('tasks as t')
                // ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
                // ->join('users as u', 't.candidate_id', '=', 'u.id')
                // ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.tat','ta.reassign_by','ta.created_at as created')
                
                // ->where(['u.is_deleted'=>'0','ta.user_id'=>Auth::user()->id])->whereNull('ta.reassign_to')
                // ->orWhere(['u.is_deleted'=>'0','ta.user_id'=>Auth::user()->id,'ta.reassign_to'=>Auth::user()->id])
                // ->whereIn('ta.status',['1','2'])->orderBy('id','DESC')->get();
                
                //  echo"<pre>";
                //     print_r($normal_task); 
                //  die;
                // $kams = DB::table('key_account_managers')->where(['user_id'=>$user_id])->get();
                // dd($kams);
                // dd($customer_task);

        $clients = DB::table('users as u')
        ->select('u.id','u.display_id','u.name','u.first_name','u.last_name','u.email','u.phone','b.company_name')
        ->join('user_businesses as b','b.business_id','=','u.id')
        ->where(['u.user_type'=>'client','u.parent_id'=>$business_id])
        ->get();
        $users_list = User::where(['business_id'=>$business_id,'user_type'=>'user','is_deleted'=>0])->get();

        $users = DB::table('users as u')
        ->join('role_masters as rm', 'rm.id', '=', 'u.role')
        ->join('role_permissions as rp', 'rp.role_id', '=', 'rm.id')
        ->select('u.name','u.id','u.role as role_id','rm.role','rp.permission_id' )
        ->where(['u.business_id'=>Auth::user()->business_id,'u.is_deleted'=>0,'u.status'=>1])
        ->get();


        // $all_users = DB::table('users')->where(['user_type'=>'user','business_id'=>Auth::user()->business_id]);
         
        $user_service = DB::table('users as u')
        ->join('role_masters as rm', 'rm.id', '=', 'u.role')
        ->join('role_permissions as rp', 'rp.role_id', '=', 'rm.id')
        ->leftJoin('user_checks as uc','uc.user_id','=','u.id')
        ->select('uc.checks','u.id' )
        ->where(['u.business_id'=>Auth::user()->business_id,'u.is_deleted'=>0])
        ->get();
        // echo"<pre>";
        // print_r($user_service);
        // die; 
        // dd($user_service);
        $action_master = DB::table('action_masters')
        ->select('*')
        ->where('route_group','=','')
        ->get();

        $services = DB::table('services')
        ->select('name','id')
        ->where('business_id',NULL)
        ->whereNotIn('type_name',['e_court'])
        ->orwhere('business_id',$business_id)
        ->where(['status'=>'1'])
        ->get();
        // dd($tasks);
        if($request->ajax())
            return view('admin.task.ajax',compact('tasks','action_master','users','clients','users_list','services'));
        else
            return view('admin.task.index',compact('tasks','action_master','users','clients','users_list','services'));
    }

     /**
     * Display a listing of the resource.assignModal
     *
     * @return \Illuminate\Http\Response
     */
    public function assignIndex(Request $request)
    {
        // dd($request);,compact()
        $user_id = Auth::user()->id;
        $business_id =Auth::user()->business_id;
        $cam=[];
        $kams = DB::table('key_account_managers')->where(['user_id'=>$user_id])->get();
        foreach ($kams as $kam) {
           $cam[]= $kam->user_id;
        }
        // dd($cam);
        //   if (Auth::user()->user_type=='customer') {
          # code...
        $rows=10;
        if (Auth::user()->user_type=='customer' || in_array($user_id,$cam)) {
        $tasks =DB::table('tasks as t')
        ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
        ->join('users as u', 't.candidate_id', '=', 'u.id')
        ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.tat','ta.reassign_by','ta.created_at as created')
        ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id])
        ->whereNotNull('t.assigned_to')
        ->whereIn('ta.status',['2'])->orderBy('t.start_date','DESC');
        }
        else{
            $tasks =DB::table('tasks as t')
                ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
                ->join('users as u', 't.candidate_id', '=', 'u.id')
                ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.tat','ta.reassign_by','ta.created_at as created')
                ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id])
                ->whereIn('ta.status',['2'])
                ->whereNotNull('t.assigned_to')
                ->whereRaw('IF (ta.reassign_to IS NOT NULL, ta.reassign_to='.Auth::user()->id.', ta.user_id='.Auth::user()->id.')')->orderBy('ta.updated_at','DESC');
        }
        if($request->get('from_date') !=""){
            $tasks->whereDate('t.created_at','>=',date('Y-m-d',strtotime($request->get('from_date'))));
            }
            if($request->get('to_date') !=""){
            $tasks->whereDate('t.created_at','<=',date('Y-m-d',strtotime($request->get('to_date'))));
            }
            if(is_numeric($request->get('customer_id'))){
            $tasks->where('t.business_id',$request->get('customer_id'));
            }
            if(is_numeric($request->get('candidate_id'))){
            $tasks->where('t.candidate_id',$request->get('candidate_id'));
            }
            if(is_numeric($request->get('service_id'))){
            // echo($request->get('service_id'));
                $tasks->where('t.service_id',$request->get('service_id'));
            }
            if($request->get('task_type')){
                $tasks->where('t.description',$request->get('task_type'));
            }
            if(is_numeric($request->get('user_id'))){
            $tasks->where('ta.user_id',$request->get('user_id')); 
            }
            if ($request->get('search')) {
                // $searchQuery = '%' . $request->search . '%';
              // echo($request->input('search'));
                $tasks->where('u.first_name',$request->get('search'))->orWhere('u.name',$request->get('search'))->orWhere('u.display_id',$request->get('search'))->orWhere('t.description',$request->get('search'));
              }
            if ($request->get('rows')!='') {
                $rows = $request->get('rows');
            }
            $tasks=$tasks->paginate($rows);
            

        // $kams = DB::table('key_account_managers')->where(['user_id'=>$user_id])->get();
        // dd($kams);
        // dd($customer_task);

        $clients = DB::table('users as u')
        ->select('u.id','u.display_id','u.name','u.first_name','u.last_name','u.email','u.phone','b.company_name')
        ->join('user_businesses as b','b.business_id','=','u.id')
        ->where(['u.user_type'=>'client','u.parent_id'=>$business_id])
        ->get();

        $users_list = User::where(['business_id'=>$business_id,'user_type'=>'user','is_deleted'=>0])->get();

        $users = DB::table('users as u')
        ->join('role_masters as rm', 'rm.id', '=', 'u.role')
        ->join('role_permissions as rp', 'rp.role_id', '=', 'rm.id')
        ->select('u.name','u.id','u.role as role_id','rm.role','rp.permission_id' )
        ->where(['u.business_id'=>Auth::user()->business_id,'u.is_deleted'=>0,'u.status'=>1])
        ->get();
        
        $action_master = DB::table('action_masters')
        ->select('*')
        ->where('route_group','=','')
        ->get();
        $services = DB::table('services')
        ->select('name','id')
        ->where('business_id',NULL)
        ->whereNotIn('type_name',['e_court'])
        ->orwhere('business_id',$business_id)
        ->where(['status'=>'1'])
        ->get();
            // dd($tasks);
        if($request->ajax())
            return view('admin.task.assign-ajax',compact('tasks','action_master','users','clients','users_list','services'));
        else
            return view('admin.task.assign-index',compact('tasks','action_master','users','clients','users_list','services'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function unassignIndex(Request $request)
    {
        // dd($request);,compact()
        $user_id = Auth::user()->id;
        $business_id =Auth::user()->business_id;
        $tasks=[];
        $cam=[];
        $kams = DB::table('key_account_managers')->where(['user_id'=>$user_id])->get();
        foreach ($kams as $kam) {
           $cam[]= $kam->user_id;
        }
        // dd($cam);
        //   if (Auth::user()->user_type=='customer') {
          # code...
        $rows=10;
        if (Auth::user()->user_type=='customer' || in_array($user_id,$cam)) {
            $tasks =DB::table('tasks as t')
            ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
            ->join('users as u', 't.candidate_id', '=', 'u.id')
            ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.tat','ta.reassign_by','ta.created_at as created')
            ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id])
            ->where('t.assigned_to',null)
            ->whereIn('ta.status',['1'])
            ->orderBy('id','DESC');
        
        if($request->get('from_date') !=""){
            $tasks->whereDate('t.created_at','>=',date('Y-m-d',strtotime($request->get('from_date'))));
            }
            if($request->get('to_date') !=""){
            $tasks->whereDate('t.created_at','<=',date('Y-m-d',strtotime($request->get('to_date'))));
            }
            if(is_numeric($request->get('customer_id'))){
            $tasks->where('t.business_id',$request->get('customer_id'));
            }
            if(is_numeric($request->get('candidate_id'))){
            $tasks->where('t.candidate_id',$request->get('candidate_id'));
            }
            if(is_numeric($request->get('service_id'))){
            // echo($request->get('service_id'));
                $tasks->where('t.service_id',$request->get('service_id'));
            }
            if($request->get('task_type')){
                $tasks->where('t.description',$request->get('task_type'));
            }
            if(is_numeric($request->get('user_id'))){
            $tasks->where('ta.user_id',$request->get('user_id'));
            }
            if ($request->get('search')) {
                // $searchQuery = '%' . $request->search . '%';
              // echo($request->input('search'));
                $tasks->where('u.first_name',$request->get('search'))->orWhere('u.name',$request->get('search'))->orWhere('u.email',$request->get('search'))->orWhere('u.display_id',$request->get('search'))->orWhere('t.description',$request->get('search'));
              }
            if($request->get('rows')!='') {
                $rows = $request->get('rows');
            }
            $tasks=$tasks->paginate($rows);
        }

        // $kams = DB::table('key_account_managers')->where(['user_id'=>$user_id])->get();
        // dd($kams);
        // dd($customer_task);

        $clients = DB::table('users as u')
        ->select('u.id','u.display_id','u.name','u.first_name','u.last_name','u.email','u.phone','b.company_name')
        ->join('user_businesses as b','b.business_id','=','u.id')
        ->where(['u.user_type'=>'client','u.parent_id'=>$business_id])
        ->get();

        $users_list = User::where(['business_id'=>$business_id,'user_type'=>'user','is_deleted'=>0])->get();

        $users = DB::table('users as u')
        ->join('role_masters as rm', 'rm.id', '=', 'u.role')
        ->join('role_permissions as rp', 'rp.role_id', '=', 'rm.id')
        ->select('u.name','u.id','u.role as role_id','rm.role','rp.permission_id' )
        ->where(['u.business_id'=>Auth::user()->business_id,'u.is_deleted'=>0,'u.status'=>1])
        ->get();
        
        $action_master = DB::table('action_masters')
        ->select('*')
        ->where('route_group','=','')
        ->get();
            // dd($tasks);
            
        $services = DB::table('services')
            ->select('name','id')
            ->where('business_id',NULL)
            ->whereNotIn('type_name',['e_court'])
            ->orwhere('business_id',$business_id)
            ->where(['status'=>'1'])
            ->get();
        if($request->ajax())
            return view('admin.task.unassign-ajax',compact('tasks','action_master','users','clients','users_list','services'));
        else
            return view('admin.task.unassign-index',compact('tasks','action_master','users','clients','users_list','services'));
    }


     /**
     * Display a listing of the resource.assignModal
     *
     * @return \Illuminate\Http\Response
     */
    public function completeIndex(Request $request)
    {
        // dd($request);,compact()
        $user_id = Auth::user()->id;
        $business_id =Auth::user()->business_id;
        $cam=[];
        $kams = DB::table('key_account_managers')->where(['user_id'=>$user_id])->get();
        foreach ($kams as $kam) {
           $cam[]= $kam->user_id;
        }
        // dd($cam);
        //   if (Auth::user()->user_type=='customer') {
          # code...
        $rows=10;
        if (Auth::user()->user_type=='customer' || in_array($user_id,$cam)) {
            $tasks =DB::table('tasks as t')
            ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
            ->join('users as u', 't.candidate_id', '=', 'u.id')
            ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.tat','ta.reassign_by','ta.created_at as created')
            ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id,'ta.status'=>'3'])
            ->orderBy('id','DESC');
       }
        else{
            $tasks =DB::table('tasks as t')
                ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
                ->join('users as u', 't.candidate_id', '=', 'u.id')
                ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.tat','ta.reassign_by','ta.created_at as created')
                ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id,'ta.status'=>'3'])
                ->whereNotNull('t.assigned_to')
                ->whereRaw('IF (ta.reassign_to IS NOT NULL, ta.reassign_to='.Auth::user()->id.', ta.user_id='.Auth::user()->id.')')->orderBy('ta.updated_at','DESC');
        }
        if($request->get('from_date') !=""){
            $tasks->whereDate('t.created_at','>=',date('Y-m-d',strtotime($request->get('from_date'))));
            }
            if($request->get('to_date') !=""){
            $tasks->whereDate('t.created_at','<=',date('Y-m-d',strtotime($request->get('to_date'))));
            }
            if(is_numeric($request->get('customer_id'))){
            $tasks->where('t.business_id',$request->get('customer_id'));
            }
            if(is_numeric($request->get('candidate_id'))){
            $tasks->where('t.candidate_id',$request->get('candidate_id'));
            }
            if(is_numeric($request->get('service_id'))){
            // echo($request->get('service_id'));
                $tasks->where('t.service_id',$request->get('service_id'));
            }
            if($request->get('task_type')){
                $tasks->where('t.description',$request->get('task_type'));
            }
            if(is_numeric($request->get('user_id'))){
            $tasks->where('ta.user_id',$request->get('user_id'));
            }
            if ($request->get('search')) {
                // $searchQuery = '%' . $request->search . '%';
              // echo($request->input('search'));
                $tasks->where('u.first_name',$request->get('search'))->orWhere('u.name',$request->get('search'))->orWhere('u.display_id',$request->get('search'))->orWhere('t.description',$request->get('search'));
              }
            if ($request->get('rows')!='') {
                $rows = $request->get('rows');
            }
            $tasks=$tasks->paginate($rows);
            

        // $kams = DB::table('key_account_managers')->where(['user_id'=>$user_id])->get();
        // dd($kams);
        // dd($customer_task);

        $clients = DB::table('users as u')
        ->select('u.id','u.display_id','u.name','u.first_name','u.last_name','u.email','u.phone','b.company_name')
        ->join('user_businesses as b','b.business_id','=','u.id')
        ->where(['u.user_type'=>'client','u.parent_id'=>$business_id])
        ->get();

        $users_list = User::where(['business_id'=>$business_id,'user_type'=>'user','is_deleted'=>0])->get();

        $users = DB::table('users as u')
        ->join('role_masters as rm', 'rm.id', '=', 'u.role')
        ->join('role_permissions as rp', 'rp.role_id', '=', 'rm.id')
        ->select('u.name','u.id','u.role as role_id','rm.role','rp.permission_id' )
        ->where('u.business_id',Auth::user()->business_id)
        ->get();
        
        $action_master = DB::table('action_masters')
        ->select('*')
        ->where('route_group','=','')
        ->get();

        $services = DB::table('services')
        ->select('name','id')
        ->where('business_id',NULL)
        ->whereNotIn('type_name',['e_court'])
        ->orwhere('business_id',$business_id)
        ->where(['status'=>'1'])
        ->get();
            // dd($tasks);
        if($request->ajax())
            return view('admin.task.complete-ajax',compact('tasks','action_master','users','clients','users_list','services'));
        else
            return view('admin.task.complete-index',compact('tasks','action_master','users','clients','users_list','services'));
    }


     /**
     * Display a listing of the resource.assignModal
     *
     * @return \Illuminate\Http\Response
     */
    public function vendorIndex(Request $request)
    {
        // dd($request);,compact()
        $user_id = Auth::user()->id;
        $business_id =Auth::user()->business_id;
        $cam=[];
        $kams = DB::table('key_account_managers')->where(['user_id'=>$user_id])->get();
        foreach ($kams as $kam) {
           $cam[]= $kam->user_id;
        }
        // dd($cam);
        //   if (Auth::user()->user_type=='customer') {
          # code...
        $rows=10;
        // if (Auth::user()->user_type=='customer' || in_array($user_id,$cam)) {
            $tasks =DB::table('tasks as t')
            ->join('vendor_tasks as ta', 'ta.task_id', '=', 't.id')
            ->join('vendor_verification_data as vvd', 'vvd.vendor_task_id', '=', 'ta.id')
            ->join('users as u', 't.candidate_id', '=', 'u.id')
            ->select('t.*','ta.status as tastatus','ta.reassigned_to','ta.reassigned_by','ta.created_at as created','ta.vendor_sla_id','vvd.file_name','vvd.zip_file','u.display_id')
            ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id])
            ->whereIn('ta.status',['1','2'])
            ->orderBy('id','DESC');
        // }
        // else{
        //     $tasks =DB::table('tasks as t')
        //         ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
        //         ->join('users as u', 't.candidate_id', '=', 'u.id')
        //         ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.tat','ta.reassign_by','ta.created_at as created')
        //         ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id,'ta.status'=>'2'])
        //         ->whereNotNull('t.assigned_to')
        //         ->whereRaw('IF (ta.reassign_to IS NOT NULL, ta.reassign_to='.Auth::user()->id.', ta.user_id='.Auth::user()->id.')')->orderBy('ta.updated_at','DESC');
        // }
        if($request->get('from_date') !=""){
            $tasks->whereDate('t.created_at','>=',date('Y-m-d',strtotime($request->get('from_date'))));
        }
        if($request->get('to_date') !=""){
            $tasks->whereDate('t.created_at','<=',date('Y-m-d',strtotime($request->get('to_date'))));
        }
        if(is_numeric($request->get('customer_id'))){
            $tasks->where('t.business_id',$request->get('customer_id'));
        }
        if(is_numeric($request->get('candidate_id'))){
            $tasks->where('t.candidate_id',$request->get('candidate_id'));
        }
        if(is_numeric($request->get('service_id'))){
        // echo($request->get('service_id'));
            $tasks->where('t.service_id',$request->get('service_id'));
        }
        if($request->get('task_type')){
            $tasks->where('t.description',$request->get('task_type'));
        }
        if(is_numeric($request->get('user_id'))){
            $tasks->where('ta.user_id',$request->get('user_id'));
        }
        if ($request->get('search')) {
            // $searchQuery = '%' . $request->search . '%';
          // echo($request->input('search'));
            $tasks->where('u.first_name',$request->get('search'))->orWhere('u.name',$request->get('search'))->orWhere('u.display_id',$request->get('search'))->orWhere('t.description',$request->get('search'));
          }
        if ($request->get('rows')!='') {
            $rows = $request->get('rows');
        }
       
        $tasks=$tasks->paginate($rows);
        // dd($tasks);
        // $kams = DB::table('key_account_managers')->where(['user_id'=>$user_id])->get();
        // dd($kams);
        // dd($customer_task);

        $clients = DB::table('users as u')
        ->select('u.id','u.display_id','u.name','u.first_name','u.last_name','u.email','u.phone','b.company_name')
        ->join('user_businesses as b','b.business_id','=','u.id')
        ->where(['u.user_type'=>'client','u.parent_id'=>$business_id])
        ->get();

        $users_list = User::where(['business_id'=>$business_id,'user_type'=>'user','is_deleted'=>0])->get();

        $users = DB::table('users as u')
        ->join('role_masters as rm', 'rm.id', '=', 'u.role')
        ->join('role_permissions as rp', 'rp.role_id', '=', 'rm.id')
        ->select('u.name','u.id','u.role as role_id','rm.role','rp.permission_id' )
        ->where('u.business_id',Auth::user()->business_id)
        ->get();
        
        $action_master = DB::table('action_masters')
        ->select('*')
        ->where('route_group','=','')
        ->get();

        $services = DB::table('services')
        ->select('name','id')
        ->where('business_id',NULL)
        ->whereNotIn('type_name',['e_court'])
        ->orwhere('business_id',$business_id)
        ->where(['status'=>'1'])
        ->get();
        
            // dd($tasks);
        if($request->ajax())
            return view('admin.task.vendor-ajax',compact('tasks','action_master','users','clients','users_list','services'));
        else
            return view('admin.task.vendor-index',compact('tasks','action_master','users','clients','users_list','services'));
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
        // Session()->forget('check_id');

        // Session()->forget('jaf_id');
        // Session()->forget('service_id');

        // if( is_numeric($request->get('customer_id')) ){             
        //     session()->put('customer_id', $request->get('customer_id'));
        // }
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
    
    public function exportChecks(Request $request) 
    {
        // dd($request->session()->get('candidate_id'));
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
            $task_id      =  $request->session()->get('task_id');
          }
          $service_id=[];
          $candidate_id=[];
          foreach ($task_id as $key => $task) {
            
            $tasks =  DB::table('tasks')->where(['id'=>$task,'description'=>'Task for Verification'])->first();

            if ($tasks) {
                        $service_id[] = $tasks->service_id;
                        $candidate_id[]=$tasks ->candidate_id;
                        // $no_of_verifications[] =$tasks->number_of_verifications;
            }
          }
             $candidate_id=array_values(array_unique($candidate_id));
             $service_id=array_values(array_unique($service_id));
            sort($service_id);
            rsort($candidate_id);
            // foreach ($candidate_id as $key => $id) {
            //   $job_sla_items=  DB::table('job_sla_items')->select('service_id','number_of_verifications')->where('candidate_id',$id)->get();
            // }

        //dd($candidate_id);
       

        return Excel::download(new allChecksExport($from_date, $to_date, $candidate_id, $service_id), 'task-all-checks-data.xlsx');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * Task Re-Assignment
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
            $this->validate($request, [
            'user' => 'required',
            ]);
            // 'roles'     => 'required'
            
            $business_id = Auth::user()->business_id;
            $user_id = Auth::user()->id;
            $task_id =$request->input('task_id');
            // $service_id =$request->input('service_id');
            // $services = explode(',', $service_id);
            DB::beginTransaction();
            try{

                //Change status of Old task
                // foreach ($services as $key => $service) {
                    //  dd($service);
                    $task_assgn = TaskAssignment::where(['business_id'=>$request->input('business_id'),'candidate_id'=>$request->input('candidate_id'),'status'=>"2",'task_id'=>$task_id])->first();
                        $task_assgn->status= '0';
                        $task_assgn->save();
                //  }
                //  foreach ($services as $key => $service) { 
                    $taskdata = [
                    
                        'business_id'=> $request->input('business_id'),
                        'candidate_id' =>$request->input('candidate_id'),   
                        'job_sla_item_id'  => $request->input('job_sla_item_id'),
                        'task_id'=> $request->input('task_id'),
                        'user_id' => $request->input('user'),
                        // 'service_id'  =>$service,
                        'reassign_to' =>$request->input('user'),
                        'reassign_by' => $user_id,
                        'status' =>'2',
                        // 'tat' =>$request->input('tat'),
                        'created_at'  => date('Y-m-d H:i:s'),
                        'updated_at'  => date('Y-m-d H:i:s')
                    ];
                    DB::table('task_assignments')->insertGetId($taskdata);

                    $user= User::where('id',$request->user)->first();
                    $email = $user->email;
                    $name  = $user->name;
                    $candidate_name = Helper::user_name($request->candidate_id);
                    $sender = DB::table('users')->where(['id'=>$business_id])->first();
                    $msg = "BGV verification Task Re-Assign to you with candidate name";
                    $data  = array('name'=>$name,'email'=>$email,'candidate_name'=>$candidate_name,'msg'=>$msg,'sender'=>$sender);
                    EmailConfigTrait::emailConfig();
                        //get Mail config data
                            //   $mail =null;
                            $mail= Config::get('mail');
                            // dd($mail['from']['address']);
                        if (count($mail)>0) {
                                Mail::send(['html'=>'mails.task-notify'], $data, function($message) use($email,$name,$mail) {
                                    $message->to($email, $name)->subject
                                    ('Clobminds Pvt Ltd - Notification for BGV Filling Task');
                                    $message->from($mail['from']['address'],$mail['from']['name']);
                                });
                        }else {
                            Mail::send(['html'=>'mails.task-notify'], $data, function($message) use($email,$name) {
                                $message->to($email, $name)->subject
                                    ('Clobminds Pvt Ltd - Notification for BGV Filling Task');
                                $message->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
                            });
                        }
                // }
              DB::commit();
              return redirect('/task')
              ->with('success', 'Task Re-assigned successfully');
            }
            catch (\Exception $e) {
                DB::rollback();
                // something went wrong
                return $e;
            } 
    }

    /**
     * Store a newly created resource in storage.
     * Task Re-Assignment
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function reportReassign(Request $request)
    {
        // dd($request);
        $rules = [
            'report_user' => 'required',
            //   'user_status' => 'required'
        
        ];
        $customMessages=[
            'report_user.required' => 'Please select a user first!',
          ];

        $validator = Validator::make($request->all(), $rules,$customMessages);
        if ($validator->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }
            // 'roles'     => 'required'
            
            $business_id = Auth::user()->business_id;
            $user_id = Auth::user()->id;
            $task_id =$request->input('report_task_id');
            // $service_id =$request->input('service_id');
            // $services = explode(',', $service_id);
            DB::beginTransaction();
            try{

                //Change status of Old task
                // foreach ($services as $key => $service) {
                    //  dd($service);
                    //  $task= Task::find($task_id);
           
                    $task_assgn = TaskAssignment::where(['business_id'=>$request->input('report_business_id'),'candidate_id'=>$request->input('report_candidate_id'),'status'=>"2",'task_id'=>$task_id])->first();
                      
                    if($task_assgn)
                    {
                    $task_assgn->status= '0';
                        $task_assgn->save();
                //  }
                //  foreach ($services as $key => $service) { 
                    $taskdata = [
                    
                        'business_id'=> $request->input('report_business_id'),
                        'candidate_id' =>$request->input('report_candidate_id'),   
                        'job_sla_item_id'  => $request->input('report_job_sla_item_id'),
                        'task_id'=> $request->input('report_task_id'),
                        'user_id' => $request->input('report_user'),
                        // 'service_id'  =>$service,
                        'reassign_to' =>$request->input('report_user'),
                        'reassign_by' => $user_id,
                        'status' =>'2',
                        // 'tat' =>$request->input('tat'),
                        'created_at'  => date('Y-m-d H:i:s'),
                        'updated_at'  => date('Y-m-d H:i:s')
                    ];
                    DB::table('task_assignments')->insertGetId($taskdata);

                    $user= User::where('id',$request->report_user)->first();
                    $email = $user->email;
                    $name  = $user->name;
                    $candidate_name = Helper::user_name($request->report_candidate_id);
                    $sender = DB::table('users')->where(['id'=>$business_id])->first();
                    $msg = "BGV verification Task Re-Assign to you with candidate name";
                    $data  = array('name'=>$name,'email'=>$email,'candidate_name'=>$candidate_name,'msg'=>$msg,'sender'=>$sender);
                    EmailConfigTrait::emailConfig();
                        //get Mail config data
                            //   $mail =null;
                            $mail= Config::get('mail');
                            // dd($mail['from']['address']);
                        if (count($mail)>0) {
                                Mail::send(['html'=>'mails.task-notify'], $data, function($message) use($email,$name,$mail) {
                                    $message->to($email, $name)->subject
                                    ('Clobminds Pvt Ltd - Notification for BGV Filling Task');
                                    $message->from($mail['from']['address'],$mail['from']['name']);
                                });
                        }else {
                            Mail::send(['html'=>'mails.task-notify'], $data, function($message) use($email,$name) {
                                $message->to($email, $name)->subject
                                    ('Clobminds Pvt Ltd - Notification for BGV Filling Task');
                                $message->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
                            });
                        }
                // }
              DB::commit();
                return response()->json([
                'success' =>true,
                'custom'  =>'yes',
                'errors'  =>[]
                ]);
            }else{
                return response()->json([
                'success' =>false,
                'custom'  =>'yes',
                'errors'  =>[]
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * 
     *
     *Task Reassign the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function taskReassign(Request $request)
    {
        // dd($request->reassign_sla_id);
        $rules = [
            'user' => 'required',
                // 'tat' => 'required'
            ];

            $validator = Validator::make($request->all(), $rules);
                
               if ($validator->fails()){
                   return response()->json([
                       'success' => false,
                       'errors' => $validator->errors()
                   ]);
               }
            // 'roles'     => 'required'
            
            $business_id = Auth::user()->business_id;
            $user_id = Auth::user()->id;
            $task_id =$request->input('tasks_id');
            $candidate_id =$request->input('candidat_id');
            $user_type = $request->reassign_user_status;
            // dd($task_id); 
            // $services = explode(',', $service_id);

            DB::beginTransaction();
            try{
                //Change status of Old task
                // foreach ($services as $key => $service) {
                //  dd($service);
                 $task_assgn = TaskAssignment::where(['business_id'=>$request->input('business'),'candidate_id'=>$candidate_id,'status'=>'2','task_id'=>$task_id,'service_id'=>$request->service, 'number_of_verifications'=>$request->no_of_verification])->first();
                    // $task_assgn->save();
                    // $task= [
                    //     $task_assgn->status= '0'
                        
                    // ];
                    if ($task_assgn) {
                        # code...
                        DB::table('task_assignments')->where(['business_id'=>$request->input('business'),'candidate_id'=>$candidate_id,'status'=>'2','task_id'=>$task_id,'service_id'=>$request->service, 'number_of_verifications'=>$request->no_of_verification])->update(['status'=>'0']);
                    }
            //  foreach ($services as $key => $service) { 
                //  $task_assgn = TaskAssignment::where(['business_id'=>$request->input('business'),'candidate_id'=>$request->input('candidate'),'status'=>"1",'task_id'=>$task_id,'service_id'=>$request->service, 'number_of_verifications'=>$request->no_of_verification])->first();
                //  dd($task_assgn);  
                //  $task_assgn->status= '0';
                //     $task_assgn->save();
                //  }
                //  foreach ($services as $key => $service) { 
                    $taskdata = [
                    
                        'business_id'=> $request->input('business'),
                        'parent_id'=>$business_id,
                        'candidate_id' =>$candidate_id,   
                        'job_sla_item_id'  =>$user_type=='vendor'? $request->reassign_sla_id :$request->input('job_sla_item'),
                        'task_id'=> $task_id,
                        'user_id' => $request->input('user'),
                        'service_id'  =>$request->service,
                        'number_of_verifications'=>$request->no_of_verification,
                        'reassign_to' =>$request->input('user'),
                        'reassign_by' => $user_id,
                        'user_type' =>$user_type,
                        'status' =>'2',
                        // 'tat' =>$request->input('tat'),
                        'created_at' =>date('Y-m-d H:i:s'),
                        'updated_at'  => date('Y-m-d H:i:s')
                        
                    ];
                    DB::table('task_assignments')->insertGetId($taskdata);
                // }

                if ($user_type=='vendor') {

                    $service_id =[];
                    $services = DB::table('services')->where('verification_type','Manual')->get();
                    foreach ($services as $service) {
                       
                        $service_id[] = $service->id; 
                    }

                    if (in_array($request->service,$service_id)) {
                        # code...
                    
                        $vendor = DB::table('vendors')->select('id')->where('user_id',$request->user)->first();
                        $vendor_sla = DB::table('vendor_sla_items')->select('sla_id')->where(['vendor_id'=>$vendor->id,'service_id'=>$request->service])->first();
                        $vendor_task_assgn = VendorTask::where(['candidate_id'=>$candidate_id,'status'=>"1",'task_id'=>$task_id,'service_id'=>$request->service, 'no_of_verification'=>$request->no_of_verification])->first();
                        // dd($vendor->id);
                        if ($vendor_task_assgn) {
                           
                            DB::table('vendor_tasks')->where(['candidate_id'=>$candidate_id,'status'=>"1",'task_id'=>$task_id,'service_id'=>$request->service, 'no_of_verification'=>$request->no_of_verification])->update(['status'=>'0']);
                    
                            $check_vendor = VendorTask::where(['candidate_id'=>$candidate_id,'status'=>"0",'task_id'=>$task_id,'service_id'=>$request->service, 'no_of_verification'=>$request->no_of_verification])->first();
                           //Data send to Vendor task for assignment
                            $vendor_task = new VendorTask;
                            $vendor_task->parent_id = Auth::user()->business_id;
                            $vendor_task->business_id =  $request->user;
                            $vendor_task->candidate_id = $candidate_id ;
                            $vendor_task->task_id = $task_id;
                            $vendor_task->service_id = $request->service;
                            $vendor_task->vendor_sla_id = $request->reassign_sla_id;
                            $vendor_task->no_of_verification = $request->no_of_verification;
                            $vendor_task->status = '1';
                            $vendor_task->assigned_to = $check_vendor->assigned_to;
                            $vendor_task->assigned_by =$check_vendor->assigned_by;
                            $vendor_task->assigned_at = $check_vendor->assigned_at;
                            $vendor_task->reassigned_to = $request->user;
                            $vendor_task->reassigned_by = Auth::user()->id;
                            $vendor_task->reassigned_at = date('Y-m-d H:i:s');
                            $vendor_task->updated_by = Auth::user()->id;
                            $vendor_task->save();

                             //Data send to Vendor task for assignment
                            //  $vendor_task = new VendorTaskAssignment;
                            //  $vendor_task->parent_id = Auth::user()->business_id;
                            //  $vendor_task->business_id =  $request->user;
                            //  $vendor_task->candidate_id = $candidate_id ;
                            //  $vendor_task->task_id = $task_id;
                            //  $vendor_task->service_id = $request->service;
                            //  $vendor_task->vendor_sla_id = $request->reassign_sla_id;
                            //  $vendor_task->no_of_verification = $request->no_of_verification;
                            //  $vendor_task->status = '1';
                            //  $vendor_task->assigned_to = $check_vendor->assigned_to;
                            //  $vendor_task->assigned_by =$check_vendor->assigned_by;
                            //  $vendor_task->assigned_at = $check_vendor->assigned_at;
                            //  $vendor_task->reassigned_to = $request->user;
                            //  $vendor_task->reassigned_by = Auth::user()->id;
                            //  $vendor_task->reassigned_at = date('Y-m-d H:i:s');
                            //  $vendor_task->updated_by = Auth::user()->id;
                            //  $vendor_task->save();
 

                        }
                    }
                    else {
                        return response()->json([
                            'success' =>false,
                            'custom'  =>'yes',
                            'errors'  =>['name'=>'This Service  cannot assign to any vendor!']
                          ]);
                    }
                }
                 // Mail send to user

                 $user= User::where('id',$request->user)->first();
                 if ($user->email) {
                     # code...
                 
                 $email = $user->email;
                 $name  = $user->name;
                 $candidate_name =  Helper::user_name($request->candidate);
                 $msg = " BGV verification Task Re-Assign to you with candidate name";
                 $sender = DB::table('users')->where(['id'=>$business_id])->first();
                 $data  = array('name'=>$name,'email'=>$email,'candidate_name'=>$candidate_name,'msg'=>$msg,'sender'=>$sender);
                 EmailConfigTrait::emailConfig();
                 //get Mail config data
                   //   $mail =null;
                   $mail= Config::get('mail');
                   // dd($mail['from']['address']);
                   if (count($mail)>0) {
                       Mail::send(['html'=>'mails.task-notify'], $data, function($message) use($email,$name,$mail) {
                           $message->to($email, $name)->subject
                           ('Clobminds Pvt Ltd - Notification for BGV verification Task');
                           $message->from($mail['from']['address'],$mail['from']['name']);
                       });
                   }else {
                        Mail::send(['html'=>'mails.task-notify'], $data, function($message) use($email,$name) {
                            $message->to($email, $name)->subject
                                ('Clobminds Pvt Ltd - Notification for BGV verification Task');
                            $message->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
                        });
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

    /**
     * 
     *
     *Task Reassign the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function bulkAssignModal(Request $request)
    {
        
        // 'roles'     => 'required'
        
        $business_id = Auth::user()->business_id;
        $user_id = Auth::user()->id;
        
        $service_id =$request->input('service_id');
        $task_time =$request->input('task_time');
        $user_type = $request->user_type;
        // dd($task_time);
        // $services = explode(',', $service_id);
        if ($user_type == 'user') {
            # code...
        
            $users = DB::table('users as u')
            ->join('role_masters as rm', 'rm.id', '=', 'u.role')
            ->join('role_permissions as rp', 'rp.role_id', '=', 'rm.id')
            ->select('u.name','u.id','u.role as role_id','rm.role','rp.permission_id' )
            ->where(['u.business_id'=>Auth::user()->business_id,'u.is_deleted'=>0,'u.status'=>1])
            ->get();
            // // 
            // $user_service = DB::table('users as u')
            // ->join('role_masters as rm', 'rm.id', '=', 'u.role')
            // ->join('role_permissions as rp', 'rp.role_id', '=', 'rm.id')
            // ->leftJoin('user_checks as uc','uc.user_id','=','u.id')
            // ->select('uc.checks','u.id' )
            // ->where(['u.business_id'=>Auth::user()->business_id,'uc.checks'=>$service_id])
            // ->get();

            // // $task =DB::table('tasks');
            
            // $user_id =[];
            // foreach($user_service as $us)
            // {
            //     $user_id[]= $us->id;


            // }
            // $created_date = Carbon::parse($request->created_time)->format('d-m-Y');
            // $completion_date = Carbon::now()->addDays($task_time)->format('d-m-Y');
            $now = Carbon::now()->format('d-m-Y');
        
            // echo"<pre>";
            // print_r($created_date);
            // die;
            // dd($user_service); 
            $action_master = DB::table('action_masters')
            ->select('*')
            ->where(['route_group'=>'','action_title'=>'BGV Filled'])
            ->first();
            $data = "<option value=''>Select User</option>";
            foreach($users as $user){
            
                
                    if ( in_array($action_master->id,json_decode($user->permission_id)) && $action_master->action_title == 'BGV Filled'  ) {
                        
                        $tasks =DB::table('tasks as t')
                        ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
                        ->join('users as u', 't.candidate_id', '=', 'u.id')
                        ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.tat','ta.reassign_by','ta.created_at as created')
                        ->where(['u.is_deleted'=>'0','description'=>'Task for Verification ','ta.status'=>'1','ta.user_id'=>$user->id])
                        ->whereDate('t.start_date','<=',$now)
                        ->count();

                        $data .=" <option value=".$user->id. ">".$user->name.' '.' ( Assigned tasks-'.$tasks.' )</option>' ;
                    }
                
            } 

            return response()->json([
                'fail'      =>false,
                'data' => $data,
                
                ]);
            
        }
        if ($user_type == 'vendor') {
            
            $vendors = DB::table('users as u')
            ->join('vendors as v', 'v.user_id','=','u.id')
            ->join('vendor_slas as vs','vs.vendor_id','=','v.id')
            ->select('u.*','vs.vendor_id as vendor_sla_id')
            ->where(['u.parent_id'=>Auth::user()->business_id,'u.user_type'=>'vendor'])
            ->groupBy('v.id')
            ->get();
            // dd($vendors);
            $data = "<option value=''>Select User</option>";
            foreach ($vendors as $vendor) {
                $data .=" <option value=".$vendor->id." data-bulk=".$vendor->vendor_sla_id. ">".$vendor->name.' </option>' ;
            }

            return response()->json([
                'fail'      =>false,
                'data' => $data,
                
            ]);
        }
            
    }

     //Get Vendor Sla 
     public function bulkVendorSla(Request $request)
     {
 
         $vendor_id = $request->vendor_sla_id;
            // dd($vendor_id);
          $vendors= DB::table('vendor_slas')->where(['vendor_id'=>$vendor_id])->get();
        //   dd($vendor_sla);
        //   $vendors = DB::table('vendor_slas as vs')
        //   ->join('vendor_sla_items as vsi', 'vsi.sla_id', '=', 'vs.id')
        //   ->select('vs.id','vs.title')
        //   ->where(['vs.business_id'=>$vendor_id,'vsi.service_id'=>$request->service_id])->get();
 
            $data = "<option value=''>Select SLA</option>";
            if (count($vendors)>0) {
                # code...
        
                foreach($vendors as $sla){
                    $data .=" <option value=".$sla->id. ">".$sla->title.'</option>' ;
                }
                return response()->json([
                    'fail'      =>false,
                    'data' => $data,
                    
                ]); 
            }
            else {
                return response()->json([
                    'fail'      =>true,
                    'custom'  =>'yes',
                    'errors'  =>['vendor_sla'=>'Please select other vendor or Create Sla of this vendor !']
                    
                ]); 
            }  
     }
     
    /**
     * 
     *
     *Task Reassign the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function assignModal(Request $request)
    {
        
            // 'roles'     => 'required'
            
            $business_id = Auth::user()->business_id;
            $user_id = Auth::user()->id;
           
            $service_id =$request->input('service_id');
            $task_time =$request->input('task_time');
            $user_type = $request->user_type;
            // dd($task_time);
            // $services = explode(',', $service_id);
            if ($user_type == 'user') {
                # code...
           
                $users = DB::table('users as u')
                ->join('role_masters as rm', 'rm.id', '=', 'u.role')
                ->join('role_permissions as rp', 'rp.role_id', '=', 'rm.id')
                ->select('u.name','u.id','u.role as role_id','rm.role','rp.permission_id' )
                ->where(['u.business_id'=>Auth::user()->business_id,'u.is_deleted'=>0])
                ->get();

                // // 
                $user_service = DB::table('users as u')
                ->join('role_masters as rm', 'rm.id', '=', 'u.role')
                ->join('role_permissions as rp', 'rp.role_id', '=', 'rm.id')
                ->leftJoin('user_checks as uc','uc.user_id','=','u.id')
                ->select('uc.checks','u.id' )
                ->where(['u.business_id'=>Auth::user()->business_id,'uc.checks'=>$service_id])
                ->get();
                //if service id 0 means anyone from the team
                if($service_id == 0){
                    $user_service = DB::table('users as u')
                    ->join('role_masters as rm', 'rm.id', '=', 'u.role')
                    ->join('role_permissions as rp', 'rp.role_id', '=', 'rm.id')
                    ->leftJoin('user_checks as uc','uc.user_id','=','u.id')
                    ->select('uc.checks','u.id' )
                    ->where(['u.business_id'=>Auth::user()->business_id])
                    ->get();
                }
                // $task =DB::table('tasks');
                
                $user_id =[];
                foreach($user_service as $us)
                {
                    $user_id[]= $us->id;
                }

                // $created_date = Carbon::parse($request->created_time)->format('d-m-Y');
                // $completion_date = Carbon::now()->addDays($task_time)->format('d-m-Y');
                $now = Carbon::now()->format('d-m-Y');
            
                // echo"<pre>";
                // print_r($created_date);
                // die;
                // dd($user_service); 
                $action_master = DB::table('action_masters')
                ->select('*')
                ->where(['route_group'=>'','action_title'=>'BGV Filled'])
                ->first();
                $data = "<option value=''>Select an User</option>";
                foreach($users as $user){
                
                    
                        if ( in_array($action_master->id, json_decode($user->permission_id)) && $action_master->action_title == 'BGV Filled' && in_array($user->id,$user_id) ) {
                            
                            $tasks =DB::table('tasks as t')
                            ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
                            ->join('users as u', 't.candidate_id', '=', 'u.id')
                            ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.tat','ta.reassign_by','ta.created_at as created')
                            ->where(['u.is_deleted'=>'0','description'=>'Task for Verification ','ta.status'=>'1','ta.user_id'=>$user->id])
                            ->whereDate('t.start_date','<=',$now)
                            ->count();

                            $data .=" <option value=".$user->id. ">".$user->name.' '.' ( Assigned tasks-'.$tasks.' )</option>' ;
                        }
                    
                } 

                return response()->json([
                    'fail'      =>false,
                    'data' => $data,
                    
                    ]);
             
            }

            //if user type vendor selected 
            if ($user_type == 'vendor') {
                
                $vendors = DB::table('users as u')
                ->join('vendors as v', 'v.user_id','=','u.id')
                ->join('vendor_slas as vs','vs.vendor_id','=','v.id')
                ->select('u.*')
                ->where(['u.parent_id'=>Auth::user()->business_id,'u.user_type'=>'vendor'])
                ->groupBy('v.id')
                ->get();
                // dd($vendors);
                $data = "<option value=''>Select User</option>";
                foreach ($vendors as $vendor) {
                    $data .=" <option value=".$vendor->id. ">".$vendor->name.' </option>' ;
                }

                return response()->json([
                    'fail'      =>false,
                    'data' => $data,
                    
                ]);
            }
            
    }


    /**
     * 
     *
     *JAF filling Task Reassign the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function fillingReassignModal(Request $request)
    {
        $task_id = $request->task_id;
        $candidate_id = $request->candidate_id;
        $action_title = '';

        $data='';
        $task =DB::table('task_assignments')->where(['candidate_id'=>$candidate_id,'status'=>'2','task_id'=>$task_id])->whereNotNull('user_id')->first();
       
        $users = DB::table('users as u')
                    ->join('role_masters as rm', 'rm.id', '=', 'u.role')
                    ->join('role_permissions as rp', 'rp.role_id', '=', 'rm.id')
                    ->select('u.name','u.id','u.role as role_id','rm.role','rp.permission_id' )
                    ->where(['u.business_id'=>Auth::user()->business_id,'u.is_deleted'=>0,'u.status'=>1])
                    ->whereNotIn('u.id',[$task->user_id])
                    ->get();

        $action_master = DB::table('action_masters')
                        ->select('*')
                        ->where('route_group','=','')
                        ->get();

        if(count($users)>0)
        {
            $task = DB::table('tasks')->select('id','description')->where('id',$task_id)->first();

            if($task!=null)
            {
                if($task->description=='BGV Filling')
                {
                    $action_title='BGV Link';
                }
                else if($task->description=='BGV QC')
                {
                    $action_title='BGV QC';
                }
                else if($task->description=='Report QC')
                {
                    $action_title='Report QC';
                }
            }
           
            foreach($users as $key => $user)
            {
                foreach($action_master as $key => $am)
                {
                    if(in_array($am->id,json_decode($user->permission_id)) && $am->action_title == $action_title)
                    {
                        $data .= '<option value='.$user->id.'>'.$user->name.'</option>';
                    }
                }
            }
        }
        

            return response()->json([
                'fail'      =>false,
                'data' => $data,
                
                  ]);
             
    }
    /**
     * 
     *
     *JAF filling Task Reassign the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function reportReassignModal(Request $request)
    {
        
            // 'roles'     => 'required'
            // dd($request);
            $business_id = Auth::user()->business_id;
            $user_id = Auth::user()->id;
           
            // $service_id =$request->input('task_id');
            // $task_time =$request->input('task_time');
            // dd($request->report_candidate_id);
            // $services = explode(',', $service_id);
            $task =DB::table('task_assignments')->where(['candidate_id'=>$request->report_candidate_id,'status'=>'2','task_id'=>$request->report_task_id])->whereNotNull('user_id')->first();
        // dd($task);
            $users = DB::table('users as u')
            ->join('role_masters as rm', 'rm.id', '=', 'u.role')
            ->join('role_permissions as rp', 'rp.role_id', '=', 'rm.id')
            ->select('u.name','u.id','u.role as role_id','rm.role','rp.permission_id' )
            ->where(['u.business_id'=>Auth::user()->business_id,'u.is_deleted'=>0])
            ->whereNotIn('u.id',[$task->user_id])
            ->get();
            // dd($users);
            // // 
            // $user_service = DB::table('users as u')
            // ->join('role_masters as rm', 'rm.id', '=', 'u.role')
            // ->join('role_permissions as rp', 'rp.role_id', '=', 'rm.id')
            // ->leftJoin('user_checks as uc','uc.user_id','=','u.id')
            // ->select('uc.checks','u.id' )
            // ->where(['u.business_id'=>Auth::user()->business_id,'uc.checks'=>$service_id])
            // ->get();

            // $task =DB::table('tasks');
            
            // $user_id =[];
            // foreach($user_service as $us)
            // {
            //     $user_id[]= $us->id;

               
               

            // }
            // $created_date = Carbon::parse($request->created_time)->format('d-m-Y');
            // $completion_date = Carbon::now()->addDays($task_time)->format('d-m-Y');
            $now = Carbon::now()->format('d-m-Y');
          
            // echo"<pre>";
            // print_r($created_date);
            // die;
            // dd($user_service); 
            $action_master = DB::table('action_masters')
            ->select('*')
            ->where(['route_group'=>'','action_title'=>'Generate Candidate Reports'])
            ->first();
            // dd($action_master);
            $data = "<option value=''>Select User</option>";
            foreach($users as $user){
               
                  
                    if ( in_array($action_master->id,json_decode($user->permission_id)) && $action_master->action_title == 'Generate Candidate Reports')  {
                        
                        // $tasks =DB::table('tasks as t')
                        // ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
                        // ->join('users as u', 't.candidate_id', '=', 'u.id')
                        // ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.tat','ta.reassign_by','ta.created_at as created')
                        // ->where(['u.is_deleted'=>'0','description'=>'Task for Verification ','ta.status'=>'1','ta.user_id'=>$user->id])
                        // ->whereDate('t.start_date','<=',$now)
                        // ->count(); '.' ( Assigned tasks-'.$tasks.' )

                        $data .=" <option value=".$user->id. ">".$user->name.'</option>' ;
                    }
                
            }

            return response()->json([
                'fail'      =>false,
                'data' => $data,
                
                  ]);
             
    }
    /**
     * 
     *
     *Task Reassign the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
    */
    public function reassignModal(Request $request)
    {
        
            // 'roles'     => 'required'
            
            $business_id = Auth::user()->business_id;
            $user_id = Auth::user()->id;
           
            $service_id =$request->input('service_id');
            $candidate_id=$request->candidate_id;
            $task_time =$request->input('task_time');
            $number_of_verifications=$request->number_of_verifications;
            $user_type=$request->user_type;

        if ($user_type == 'user') {
            # code...

            // dd($candidate_id);
            // $services = explode(',', $service_id);
            if($service_id != 0){
            $task =DB::table('task_assignments')->where(['candidate_id'=>$candidate_id,'service_id'=>$service_id,'number_of_verifications'=>$number_of_verifications])->whereIn('status',['1','2'])->whereNotNull('user_id')->first();
        
            $users = DB::table('users as u')
            ->join('role_masters as rm', 'rm.id', '=', 'u.role')
            ->join('role_permissions as rp', 'rp.role_id', '=', 'rm.id')
            ->select('u.name','u.id','u.role as role_id','rm.role','rp.permission_id' )
            ->where(['u.business_id'=>Auth::user()->business_id,'u.is_deleted'=>0])
            ->whereNotIn('u.id',[$task->user_id])
            ->get();
            }
            
            // // 
            $user_service = DB::table('users as u')
            ->join('role_masters as rm', 'rm.id', '=', 'u.role')
            ->join('role_permissions as rp', 'rp.role_id', '=', 'rm.id')
            ->leftJoin('user_checks as uc','uc.user_id','=','u.id')
            ->select('uc.checks','u.id' )
            ->where(['u.business_id'=>Auth::user()->business_id,'uc.checks'=>$service_id])
            ->get();

            //if service id = 0
            if($service_id == 0){
                $task =DB::table('task_assignments')->where(['candidate_id'=>$candidate_id,'service_id'=>$service_id,'number_of_verifications'=>$number_of_verifications])->whereIn('status',['1','2'])->whereNotNull('user_id')->first();
                $users = DB::table('users as u')
                ->join('role_masters as rm', 'rm.id', '=', 'u.role')
                ->join('role_permissions as rp', 'rp.role_id', '=', 'rm.id')
                ->select('u.name','u.id','u.role as role_id','rm.role','rp.permission_id' )
                ->where(['u.business_id'=>Auth::user()->business_id,'u.is_deleted'=>0])
                ->get();

                //
                $user_service = DB::table('users as u')
                ->join('role_masters as rm', 'rm.id', '=', 'u.role')
                ->join('role_permissions as rp', 'rp.role_id', '=', 'rm.id')
                ->leftJoin('user_checks as uc','uc.user_id','=','u.id')
                ->select('uc.checks','u.id' )
                ->where(['u.business_id'=>Auth::user()->business_id])
                ->get();

            }
            
            $user_id =[];
            foreach($user_service as $us)
            {
                $user_id[]= $us->id;

            }

            // $created_date = Carbon::parse($request->created_time)->format('d-m-Y');
            // $completion_date = Carbon::now()->addDays($task_time)->format('d-m-Y');
            $now = Carbon::now()->format('d-m-Y');
          
            // echo"<pre>";
            // print_r($created_date);
            // die;
            
            $action_master = DB::table('action_masters')
            ->select('*')
            ->where(['route_group'=>'','action_title'=>'BGV Filled'])
            ->first();
            $data = "<option value=''>Select User</option>";
            // dd($action_master); 
            foreach($users as $user){
               
                  
                    if ( in_array($action_master->id,json_decode($user->permission_id)) && $action_master->action_title == 'BGV Filled' && in_array($user->id,$user_id)   ) {
                        
                        $tasks =DB::table('tasks as t')
                        ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
                        ->join('users as u', 't.candidate_id', '=', 'u.id')
                        ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.tat','ta.reassign_by','ta.created_at as created')
                        ->where(['u.is_deleted'=>'0','description'=>'Task for Verification ','ta.status'=>'1','ta.user_id'=>$user->id])
                        ->whereDate('t.start_date','<=',$now)
                        ->count();

                        $data .=" <option value=".$user->id. ">".$user->name.' '.' ( Assigned tasks-'.$tasks.' )</option>' ;
                    }
                
            }

            return response()->json([
                'fail'      =>false,
                'data' => $data,
                
                  ]);

        }     
        if ($user_type == 'vendor') {
            $task =DB::table('task_assignments')->where(['candidate_id'=>$candidate_id,'service_id'=>$service_id,'number_of_verifications'=>$number_of_verifications])->whereIn('status',['1','2'])->whereNotNull('user_id')->first();

            $vendors = DB::table('users as u')
                ->join('vendors as v', 'v.user_id','=','u.id')
                ->join('vendor_slas as vs','vs.vendor_id','=','v.id')
                ->select('u.*')
                ->where(['u.parent_id'=>Auth::user()->business_id,'u.user_type'=>'vendor'])
                ->groupBy('v.id')
                ->whereNotIn('v.user_id',[$task->user_id])
                ->get();
            // $vendors = DB::table('users')->where(['parent_id'=>Auth::user()->business_id,'user_type'=>'vendor'])->whereNotIn('id',[$task->user_id])->get();
            $data = "<option value=''>Select Vendor</option>";
            foreach ($vendors as $vendor) {
                $data .=" <option value=".$vendor->id. ">".$vendor->name.' </option>' ;
            }

            return response()->json([
                'fail'      =>false,
                'data' => $data,
                
                ]);
        }     
             
    }
    
    // assignTaskModal
    public function assignTaskModal(Request $request)
    {
        $task_type=$request->task_type;
        
    }

    //Get Vendor Sla 
    public function vendorSla(Request $request)
    {

        $vendor_id = $request->vendor_id;
         //   dd($request->vendor_id);
        $vendors = DB::table('vendor_slas as vs')
        ->join('vendor_sla_items as vsi', 'vsi.sla_id', '=', 'vs.id')
        ->select('vs.id','vs.title')
        ->where(['vs.business_id'=>$vendor_id,'vsi.service_id'=>$request->service_id])->get();

        $data = "<option value=''>Select SLA</option>";
        if (count($vendors)>0) {
            # code...
        
            foreach($vendors as $sla){
                $data .=" <option value=".$sla->id. ">".$sla->title.'</option>' ;
            }
            return response()->json([
                'fail'      =>false,
                'data' => $data,
                
            ]); 
        }
        else {
            return response()->json([
                'fail'      =>true,
                'custom'  =>'yes',
                'errors'  =>['vendor_sla'=>'Please select other vendor or Create Sla of this vendor !']
                
            ]); 
        }  
    }

    //Get Reassign Vendor Sla 
    public function reassignVendorSla(Request $request)
    {




        $vendor_id = $request->vendor_id;
            
        $vendors = DB::table('vendor_slas as vs')
        ->join('vendor_sla_items as vsi', 'vsi.sla_id', '=', 'vs.id')
        ->select('vs.id','vs.title')
        ->where(['vs.business_id'=>$vendor_id,'vsi.service_id'=>$request->service_id])->get();
        // dd($vendors);
        
            $data = "<option value=''>Select SLA</option>";
            if (count($vendors)>0) {
                # code...
            
                foreach($vendors as $sla){
                    $data .=" <option value=".$sla->id. ">".$sla->title.'</option>' ;
                }
                return response()->json([
                    'fail'      =>false,
                    'data' => $data,
                    
                ]); 
            }
            else {
                return response()->json([
                    'fail'      =>true,
                    'custom'  =>'yes',
                    'errors'  =>['reassign_sla_id'=>'Please select other vendor or Create Sla of this vendor ! ']
                    
                ]); 
            }  
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
    */
    public function reportAssignUser(Request $request)
    {
        //   dd($request);
        $task_id =$request->get('report_task_id'); 
        $job_sla_item_id =$request->get('report_job_sla_item_id');
         $rules = [
            'report_users' => 'required',
            //   'user_status' => 'required'
        
        ];
        $customMessages=[
            'report_users.required' => 'Please select a user first!',
          ];

        $validator = Validator::make($request->all(), $rules,$customMessages);
        if ($validator->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }
        DB::beginTransaction();
        try{
            $task= Task::find($task_id);
            if($task)
            {
                $task->assigned_to = $request->report_users;
                $task->assigned_by = Auth::user()->id;
                $task->assigned_at = date('Y-m-d H:i:s');
                $task->start_date = date('Y-m-d');
                $task->updated_at = date('Y-m-d H:i:s');
                $task->save();

                $task_assgn = TaskAssignment::where(['task_id'=>$task_id])->update(['user_id'=>$request->report_users,'user_type'=>'user','updated_at'=> date('Y-m-d H:i:s'),'job_sla_item_id'=>$job_sla_item_id,'status'=>'2']);
                            
                //   $user_type =$request->user_status;
           
              DB::commit();
                return response()->json([
                'success' =>true,
                'custom'  =>'yes',
                'errors'  =>[]
                ]);
            }else{
                return response()->json([
                'success' =>false,
                'custom'  =>'yes',
                'errors'  =>[]
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
    */
    public function assignUser(Request $request)
    {
       
        // dd($request->job_sla_items_id);
        $business_id = Auth::user()->business_id;
        // dd($request);
        $rules = [
          'users' => 'required',
            //'user_status' => 'required'
          
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }
        $user_type =$request->user_status;
        DB::beginTransaction();
        try{
            $type = $request->type;
            if($type)
            {
                if($type == 'verify_task')
                {
                    $tasks_id =$request->get('verify_task_id');
                    $tasks= Task::find($tasks_id);
                    if($tasks)
                    {
                        // $new_assign = str_replace($user_id,'',$task->assigned_to);
                        // $new_assign = str_replace(array(',,', '[,',',]'), array(',', '[',']'), $new_assign);
                        
            
                        // $tasks->assigned_to = $request->users;
                        // $tasks->assigned_by = Auth::user()->id;
                        // $tasks->assigned_at = date('Y-m-d H:i:s');
                        // $tasks->start_date = date('Y-m-d');
                        // $tasks->updated_at = date('Y-m-d H:i:s');
                        // $tasks->save();
            
                        // $task_assgn = TaskAssignment::where(['task_id'=>$tasks_id])->update(['user_id'=>$request->users,'updated_at'  => date('Y-m-d H:i:s')]);
                        


                        $tasks->assigned_to = $request->users;
                        $tasks->assigned_by = Auth::user()->id;
                        $tasks->assigned_at = date('Y-m-d H:i:s');
                        $tasks->start_date = date('Y-m-d');
                        $tasks->updated_at = date('Y-m-d H:i:s');
                        $tasks->save();
            
                        $task_assgn = TaskAssignment::where(['task_id'=>$tasks_id])->update(['user_id'=>$request->users,'user_type'=>$user_type,'updated_at'=> date('Y-m-d H:i:s'),'job_sla_item_id'=>$request->job_sla_items_id,'status'=>'2']);
                       
                        if ($user_type=='vendor') {
                            $task_assgn = TaskAssignment::where(['task_id'=>$tasks_id])->update(['user_id'=>$request->users,'user_type'=>$user_type,'updated_at'=> date('Y-m-d H:i:s'),'job_sla_item_id'=>$request->vendor_sla,'status'=>'2']);

                            $vendor = DB::table('vendors')->select('id')->where('user_id',$request->users)->first();
                            $vendor_sla = DB::table('vendor_sla_items')->select('sla_id')->where(['vendor_id'=>$vendor->id,'service_id'=>$request->modal_service_id])->first();
                            $vendor_task = new VendorTask;
                            $vendor_task->parent_id = Auth::user()->business_id;
                            $vendor_task->business_id =  $request->users;
                            $vendor_task->candidate_id = $request->verify_candidate;
                            $vendor_task->task_id = $tasks_id;
                            $vendor_task->service_id = $request->modal_service_id;
                            $vendor_task->vendor_sla_id = $request->vendor_sla;
                            $vendor_task->status = '2';
                            $vendor_task->no_of_verification = $tasks->number_of_verifications;
                            $vendor_task->assigned_to = $request->users;
                            $vendor_task->assigned_by = Auth::user()->id;
                            $vendor_task->assigned_at = date('Y-m-d H:i:s');
                            $vendor_task->save();

                            $vendor_task_assign = new VendorTaskAssignment;
                            $vendor_task_assign->parent_id = Auth::user()->business_id;
                            $vendor_task_assign->business_id =  $vendor_task->business_id;
                            $vendor_task_assign->candidate_id = $request->verify_candidate;
                            $vendor_task_assign->vendor_task_id = $vendor_task->id;
                            $vendor_task_assign->service_id = $request->modal_service_id;
                            $vendor_task_assign->vendor_sla_id = $request->vendor_sla;
                            $vendor_task_assign->status = '2';
                            $vendor_task_assign->no_of_verification = $tasks->number_of_verifications;
                            $vendor_task_assign->save();
                        }
                        // $login_user = Auth::user()->business_id;
                        // Mail send to user

                            $user= User::where('id',$request->users)->first();
                            $email = $user->email;
                            $name  = $user->name;
                            $candidate_name =  Helper::user_name($request->verify_candidate);
                            $sender = DB::table('users')->where(['id'=>$business_id])->first();
                            $msg = "BGV Verification Task Assign to you with candidate name";
                            $data  = array('name'=>$name,'email'=>$email,'candidate_name'=>$candidate_name,'msg'=>$msg,'sender'=>$sender);
            
                            Mail::send(['html'=>'mails.task-notify'], $data, function($message) use($email,$name) {
                                $message->to($email, $name)->subject
                                    ('Clobminds Pvt Ltd - Notification for BGV Verification Task');
                                $message->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
                            });
                        
                        DB::commit();
                        return response()->json([
                        'success' =>true,
                        'custom'  =>'yes',
                        'errors'  =>[]
                        ]);
                    }else{
                        return response()->json([
                        'success' =>false,
                        'custom'  =>'yes',
                        'errors'  =>[]
                        ]);
                    }
                }
            }
            else{

            
                $candidate_id = $request->get('candidate_id');  
                $business_id = $request->get('business_id');  
                // $user_id =$request->get('user_id'); 
                $task_id =$request->get('task_id'); 
                $job_sla_item_id =$request->get('job_sla_item_id');
                
                $task= Task::find($task_id);
                if($task)
                {
                    // $new_assign = str_replace($user_id,'',$task->assigned_to);
                    // $new_assign = str_replace(array(',,', '[,',',]'), array(',', '[',']'), $new_assign);
                    
                    $task->assigned_to = $request->users;
                    $task->assigned_by = Auth::user()->id;
                    $task->assigned_at = date('Y-m-d H:i:s');
                    $task->start_date = date('Y-m-d');
                    $task->updated_at = date('Y-m-d H:i:s');
                    $task->save();
        
                    $task_assgn = TaskAssignment::where(['task_id'=>$task_id])->update(['user_id'=>$request->users,'user_type'=>$user_type,'updated_at'=> date('Y-m-d H:i:s'),'job_sla_item_id'=>$job_sla_item_id,'status'=>'2']);
                       
                    if ($user_type=='vendor') {
                        $task_assgn = TaskAssignment::where(['task_id'=>$task_id])->update(['user_id'=>$request->users,'user_type'=>$user_type,'updated_at'=> date('Y-m-d H:i:s'),'job_sla_item_id'=>$request->vendor_sla]);
                        $vendor = DB::table('vendors')->select('id')->where('user_id',$request->users)->first();
                        $vendor_sla = DB::table('vendor_sla_items')->select('sla_id')->where(['vendor_id'=>$vendor->id,'service_id'=>$request->modal_service_id])->first();
                        $vendor_task = new VendorTask;
                        $vendor_task->parent_id = Auth::user()->business_id;
                        $vendor_task->business_id =  $request->users;
                        $vendor_task->candidate_id = $request->verify_candidate;
                        $vendor_task->task_id = $task_id;
                        $vendor_task->service_id = $request->modal_service_id;
                        $vendor_task->vendor_sla_id = $request->vendor_sla;
                        $vendor_task->status = '2';
                        $vendor_task->no_of_verification = $task->number_of_verifications;
                        $vendor_task->assigned_to = $request->users;
                        $vendor_task->assigned_by = Auth::user()->id;
                        $vendor_task->assigned_at = date('Y-m-d H:i:s');
                        $vendor_task->save();

                        $vendor_task_assign = new VendorTaskAssignment;
                        $vendor_task_assign->parent_id = Auth::user()->business_id;
                        $vendor_task_assign->business_id =  $vendor_task->business_id;
                        $vendor_task_assign->candidate_id = $request->verify_candidate;
                        $vendor_task_assign->vendor_task_id = $vendor_task->id;
                        $vendor_task_assign->service_id = $request->modal_service_id;
                        $vendor_task_assign->vendor_sla_id = $request->vendor_sla;
                        $vendor_task_assign->status = '2';
                        $vendor_task_assign->no_of_verification = $task->number_of_verifications;
                        $vendor_task_assign->save();
                    }
                     

                    // $login_user = Auth::user()->business_id;
                    // Mail send to user

                        $user= User::where('id',$request->users)->first();
                        $email = $user->email;
                        $name  = $user->name;
                        $candidate_name =  Helper::user_name($request->verify_candidate);
                        $msg = " BGV verification Task Assign to you  with candidate name";
                        $sender = DB::table('users')->where(['id'=>$business_id])->first();
                        $data  = array('name'=>$name,'email'=>$email,'candidate_name'=>$candidate_name,'msg'=>$msg,'sender'=>$sender);
                        EmailConfigTrait::emailConfig();
                        //get Mail config data
                          //   $mail =null;
                          $mail= Config::get('mail');
                          // dd($mail['from']['address']);
                          if (count($mail)>0) {
                              Mail::send(['html'=>'mails.task-notify'], $data, function($message) use($email,$name,$mail) {
                                  $message->to($email, $name)->subject
                                  ('Clobminds Pvt Ltd - Notification for BGV verification Task');
                                  $message->from($mail['from']['address'],$mail['from']['name']);
                              });
                          }else {
                                Mail::send(['html'=>'mails.task-notify'], $data, function($message) use($email,$name) {
                                    $message->to($email, $name)->subject
                                        ('Clobminds Pvt Ltd - Notification for BGV verification Task');
                                    $message->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
                                });
                        }
                        DB::commit();
                    return response()->json([
                    'success' =>true,
                    'custom'  =>'yes',
                    'errors'  =>[]
                    ]);
                }else{
                    return response()->json([
                    'success' =>false,
                    'custom'  =>'yes',
                    'errors'  =>[]
                    ]);
                }
            }
            // dd($user_id);
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        } 
    }

     /**Bull task Assign 
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function bulkAssign(Request $request)
    {
        // dd($request);
        $from_date = $to_date=$user_id= "";
        $business_id = Auth::user()->business_id;
        $user_type= $request->bulk_user_status;
        $bulk_verify_task = $request->bulk_verify_task_id;
        
        // dd($bulk_verify_task);
        // $bulk_verify_task_id= $request->bulk_verify_task_id;
        // var_dump($business_id);
        DB::beginTransaction();
        try{
            if($user_type=='vendor'){
                $task_id=[];
                if($bulk_verify_task)
                {
                    $task_ids =  $bulk_verify_task;
                    $task_id = explode(',',$task_ids);
                    // var_dump($task_id);
                }
                if($request->bulk_users)
                {
                    $user_id=  $request->bulk_users;
                }
                // dd($user_id);
                // $user_id =$request->user_id;
                $vendor_sla_id = $request->bulk_vendor_sla;
                $vendors = DB::table('vendor_slas as vs')
                ->join('vendor_sla_items as vsi', 'vsi.sla_id', '=', 'vs.id')
                ->select('vsi.service_id')
                ->where(['vs.business_id'=>$user_id,'vsi.sla_id'=>$vendor_sla_id])->get();
                foreach ($vendors as $vendor) {
                    
                    $services_id[]= $vendor->service_id;
                }
                // dd($services_id);
                $tasks =  DB::table('tasks')->where(['assigned_to'=>null,'description'=>'Task for Verification'])->whereIn('id',$task_id)->get();
                // dd($tasks);
                if (count($tasks)>0) {
                    foreach ($tasks as $key => $task) {

                        if (in_array($task->service_id,$services_id)) {
                           
                            $tasks_id= Task::find($task->id);
                            $tasks_id->assigned_to = $user_id;
                            $tasks_id->assigned_by = Auth::user()->id;
                            $tasks_id->assigned_at = date('Y-m-d H:i:s');
                            $tasks_id->start_date = date('Y-m-d');
                            $tasks_id->updated_at = date('Y-m-d H:i:s');
                            $tasks_id->save();

                            // $task_data= DB::table('task_assignments')->where(['task_id'=>$task->id,'user_id'=>0])->first();
                            $task_assgn = TaskAssignment::where(['task_id'=>$task->id])->update(['user_id'=>$user_id,'user_type'=>$user_type,'updated_at'=> date('Y-m-d H:i:s'),'job_sla_item_id'=>$vendor_sla_id]);

                            $vendor = DB::table('vendors')->select('id')->where('user_id',$user_id)->first();
                            $vendor_sla = DB::table('vendor_sla_items')->select('sla_id')->where(['vendor_id'=>$vendor->id,'service_id'=>$task->service_id])->first();
                            $vendor_task = new VendorTask;
                            $vendor_task->parent_id = Auth::user()->business_id;
                            $vendor_task->business_id =  $user_id;
                            $vendor_task->candidate_id = $task->candidate_id;
                            $vendor_task->task_id = $task->id;
                            $vendor_task->service_id = $task->service_id;
                            $vendor_task->vendor_sla_id = $vendor_sla_id;
                            $vendor_task->status = '1';
                            $vendor_task->no_of_verification = $task->number_of_verifications;
                            $vendor_task->assigned_to = $user_id;
                            $vendor_task->assigned_by = Auth::user()->id;
                            $vendor_task->assigned_at = date('Y-m-d H:i:s');
                            $vendor_task->save();

                            $vendor_task_assign = new VendorTaskAssignment;
                            $vendor_task_assign->parent_id = Auth::user()->business_id;
                            $vendor_task_assign->business_id =  $vendor_task->business_id;
                            $vendor_task_assign->candidate_id = $task->candidate_id;
                            $vendor_task_assign->vendor_task_id = $vendor_task->id;
                            $vendor_task_assign->service_id = $task->service_id;
                            $vendor_task_assign->vendor_sla_id = $vendor_sla_id;
                            $vendor_task_assign->status = '1';
                            $vendor_task_assign->no_of_verification = $task->number_of_verifications;
                            $vendor_task_assign->save();
                        }

                    }
                    DB::commit();
                    return response()->json([
                        'fail' => false,
                        'status'=>'ok',
                        'message' => 'updated',
                        ], 200);
                    
    
                }
                else {
                    return response()->json([
                        'fail' => true,
                        'status' =>'no',
                        ], 200);
                }

                
            }
            else{
               
                if($bulk_verify_task)
                {
                    // $task_id =  $bulk_verify_task;
                    $task_ids =  $bulk_verify_task;
                    $task_id = explode(',',$task_ids);
                    // dd($task_id);
                }
                if($request->bulk_users)
                {
                    $user_id=  $request->bulk_users;
                }
                $i=0;
                $j=0;
                $service_id=[];
                $candidate_id=[];
                // foreach ($task_id as $key => $task) {
                $users = DB::table('users as u')
                ->join('role_masters as rm', 'rm.id', '=', 'u.role')
                ->join('role_permissions as rp', 'rp.role_id', '=', 'rm.id')
                ->select('u.name','u.id','u.role as role_id','rm.role','rp.permission_id' )
                ->where(['u.business_id'=>Auth::user()->business_id,'u.is_deleted'=>0])
                ->get();
                
                $tasks =  DB::table('tasks')->where('assigned_to',null)->whereIn('id',$task_id)->get();
                //   dd($tasks);
                if (count($tasks)>0) {
                    foreach ($tasks as $key => $task) {
                    
                        if (stripos($task->description,'Task for Verification')!==false && $task->assigned_to==null) {
                            // echo"<pre>";
                            // print_r($task->description);
                            // dd($task->description);
                            // dd($users);
                            
                            $user_service = DB::table('users as u')
                            ->join('role_masters as rm', 'rm.id', '=', 'u.role')
                            ->join('role_permissions as rp', 'rp.role_id', '=', 'rm.id')
                            ->leftJoin('user_checks as uc','uc.user_id','=','u.id')
                            ->select('uc.checks','u.id' )
                            ->where(['u.business_id'=>Auth::user()->business_id,'uc.checks'=>$task->service_id])
                            ->get();
                            
                            // $task =DB::table('tasks');
                            
                            $user_service_id =[];
                            foreach($user_service as $us)
                            {
                                $user_service_id[]= $us->id;
                
                            }
                            
                            $action_master = DB::table('action_masters')
                            ->select('*')
                            ->where(['route_group'=>'','action_title'=>'BGV Filled'])
                            ->first();
                            
                            foreach($users as $user){
                                if ( in_array($action_master->id,json_decode($user->permission_id)) && $action_master->action_title == 'BGV Filled' && in_array($user->id,$user_service_id) ) {
                                    
                                    
                                    if ($user_id ==$user->id ) {

                                        $verify_task= Task::find($task->id);
                                        if($verify_task)
                                        {
                                            // $new_assign = str_replace($user_id,'',$task->assigned_to);
                                            // $new_assign = str_replace(array(',,', '[,',',]'), array(',', '[',']'), $new_assign);
                                            
                                
                                            $verify_task->assigned_to = $user_id;
                                            $verify_task->assigned_by = Auth::user()->id;
                                            $verify_task->assigned_at = date('Y-m-d H:i:s');
                                            $verify_task->start_date = date('Y-m-d');
                                            $verify_task->updated_at = date('Y-m-d H:i:s');
                                            $verify_task->save();
                                
                                            $task_assgn = TaskAssignment::where(['task_id'=>$task->id])->update(['user_id'=>$user_id,'updated_at'  => date('Y-m-d H:i:s')]);
                                            $i++;
                                            // $login_user = Auth::user()->business_id;
                                            // Mail send to user
                        
                                                // $user= User::where('id',$user_id)->first();
                                                // $email = $user->email;
                                                // $name  = $user->name;
                                                // $candidate_name =  Helper::user_name($task->candidate_id);
                                                // $msg = " JAF verification Task Assign to you with candidate name";
                                                // $sender = DB::table('users')->where(['id'=>$business_id])->first();
                                                // // dd($sender);
                                                // $data  = array('name'=>$name,'email'=>$email,'candidate_name'=>$candidate_name,'msg'=>$msg,'sender'=>$sender);
                                                // EmailConfigTrait::emailConfig();
                                                // //get Mail config data
                                                //   //   $mail =null;
                                                // $mail= Config::get('mail');
                                                //   // dd($mail['from']['address']);
                                                // if (count($mail)>0) {
                                                //       Mail::send(['html'=>'mails.task-notify'], $data, function($message) use($email,$name,$mail) {
                                                //           $message->to($email, $name)->subject
                                                //           ('Clobminds Pvt Ltd - Notification for JAF verification Task');
                                                //           $message->from($mail['from']['address'],$mail['from']['name']);
                                                //       });
                                                // }else {
                                                //     Mail::send(['html'=>'mails.task-notify'], $data, function($message) use($email,$name) {
                                                //         $message->to($email, $name)->subject
                                                //             ('Clobminds Pvt Ltd - Notification for JAF verification Task');
                                                //         $message->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
                                                //     });
                                                // }
                                                // return response()->json([
                                                //     'fail' => false,
                                                //     'status'=>'ok',
                                                //     'message' => 'updated',                
                                                //     ], 200);
                                    
                                        }
                                        // else {
                                        //     return response()->json([
                                        //         'fail' => true,
                                        //         'status' =>'no',
                                        //         ], 200);
                                        // }
                                    }


                                }
                                
                            }
                    
                        } 
                    
                        elseif (stripos($task->description,'BGV Filling')!==false && $task->assigned_to==null) {
                            // dd($task->description);
                            $action_master = DB::table('action_masters')
                            ->select('*')
                            ->where(['route_group'=>'','action_title'=>'BGV Link'])
                            ->first();

                            foreach ($users as $key => $user) {
                            
                                // dd(json_decode($user->permission_id));
                                if ( in_array($action_master->id,json_decode($user->permission_id)) && $action_master->action_title == 'BGV Link') {
                                    
                                    if ($user_id ==$user->id ) {
                                        // dd($user_id);
                                        $filling_task= Task::find($task->id);
                                        
                                        if($filling_task)
                                        {
                                            // $new_assign = str_replace($user_id,'',$task->assigned_to);
                                            // $new_assign = str_replace(array(',,', '[,',',]'), array(',', '[',']'), $new_assign);
                                            
                                
                                            $filling_task->assigned_to = $user_id;
                                            $filling_task->assigned_by = Auth::user()->id;
                                            $filling_task->assigned_at = date('Y-m-d H:i:s');
                                            $filling_task->start_date = date('Y-m-d');
                                            $filling_task->updated_at = date('Y-m-d H:i:s');
                                            $filling_task->save();
                                
                                            $task_assgn = TaskAssignment::where(['task_id'=>$task->id])->update(['user_id'=>$user_id,'updated_at'  => date('Y-m-d H:i:s')]);
                                            
                                            // Mail send to user
                                            $j++;
                                            // $user= User::where('id',$user_id)->first();
                                            // $email = $user->email;
                                            // $name  = $user->name;
                                            // $candidate_name =  Helper::user_name($task->candidate_id);
                                            // $msg = " BGV Filling Task Assign to you with candidate name";
                                            // $sender = DB::table('users')->where(['id'=>$business_id])->first();
                                            // // dd($sender);
                                            // $data  = array('name'=>$name,'email'=>$email,'candidate_name'=>$candidate_name,'msg'=>$msg);
                                            // EmailConfigTrait::emailConfig();
                                            // //get Mail config data
                                            //   //   $mail =null;
                                            //   $mail= Config::get('mail');
                                            //   // dd($mail['from']['address']);
                                            // if (count($mail)>0) {
                                            //       Mail::send(['html'=>'mails.task-notify'], $data, function($message) use($email,$name,$mail) {
                                            //           $message->to($email, $name)->subject
                                            //           ('Clobminds Pvt Ltd - Notification for BGV Filling Task');
                                            //           $message->from($mail['from']['address'],$mail['from']['name']);
                                            //       });
                                            // }else {
                                            //     Mail::send(['html'=>'mails.task-notify'], $data, function($message) use($email,$name) {
                                            //         $message->to($email, $name)->subject
                                            //             ('Clobminds Pvt Ltd - Notification for BGV Filling Task');
                                            //         $message->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
                                            //     });
                                            // }


                                            
                                        }
                                        // else {
                                        //     return response()->json([
                                        //         'fail' => true,
                                        //         'status' =>'no',
                                        //         ], 200);
                                        // }

                                    }

                                }
                                
                            }


                        }
                        
                    
                    }
                    $k = $i+$j;
                    if($k>0){
                        $assign_by = Auth::user()->name;
                        $user= User::where('id',$user_id)->first();
                        $email = $user->email;
                        $name  = $user->name;
                        $candidate_name =  Helper::user_name($task->candidate_id);
                        if($k==1){
                            $msg = "task has been assigned to you";
                        }
                        else{
                            $msg = "tasks have been assigned to you";
                        }
                        $sender = DB::table('users')->where(['id'=>$business_id])->first();
                        
                        // dd($sender);
                        $data  = array('name'=>$name,'email'=>$email,'candidate_name'=>$candidate_name,'msg'=>$msg,'no_of_task'=>$k,'sender'=>$sender,'assign_by'=>$assign_by);
                        EmailConfigTrait::emailConfig();
                        //get Mail config data
                        //   $mail =null;
                        $mail= Config::get('mail');
                        // dd($mail['from']['address']);
                        if (count($mail)>0) {
                            Mail::send(['html'=>'mails.bulk_assign'], $data, function($message) use($email,$name,$mail) {
                                $message->to($email, $name)->subject
                                ('Clobminds Pvt Ltd - Notification for BGV Task');
                                $message->from($mail['from']['address'],$mail['from']['name']);
                            });
                        }else {
                            Mail::send(['html'=>'mails.bulk_assign'], $data, function($message) use($email,$name) {
                                $message->to($email, $name)->subject
                                    ('Clobminds Pvt Ltd - Notification for BGV Task');
                                $message->from(env('MAIL_USERNAME'),env('MAIL_FROM_NAME'));
                            });
                        }
                    
                        DB::commit();
                        return response()->json([
                            'fail' => false,
                            'status'=>'ok',
                            'message' => 'updated',
                            ], 200);
                    }
                    else{
                        return response()->json([
                            'fail' => false,
                            'status' =>'zero',
                            ], 200);
                    }
                            // $no_of_verifications[] =$tasks->number_of_verifications;'description'=>'Task for Verification '
                }
                else {
                    return response()->json([
                        'fail' => true,
                        'status' =>'no',
                        ], 200);
                }
                
            }
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        } 
        
        // dd($business_id);
        // if( $request->session()->has('from_date') && $request->session()->has('to_date'))
        //   {  
        //     $from_date     =  $request->session()->get('from_date');
        //     $to_date       =  $request->session()->get('to_date');
        //   }
        //   else
        //   {
        //     if($request->session()->has('from_date'))
        //     {
        //       $from_date     =  $request->session()->get('from_date');
        //     }
        //   }
        
                    // }
         
        // return response()->json([
        //     'success' =>false,
        //     'custom'  =>'yes',
        //     'errors'  =>[]
        //   ]);

    }

    // Preview of completed task
    public function taskPreview(Request $request)
    {

        $form='';
        $task_id=$request->task_id;
        // dd($task_id);
        $vendor_task = DB::table('vendor_tasks')->where(['task_id'=>$task_id])->whereIn('status',['1','2'])->first();
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
            $upload_attach=DB::table('vendor_verification_data')->where(['vendor_task_id'=>$vendor_task->id])->get();
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
    /**assignModal
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    
    }

    public function assignUserList(Request $request)
    {
        $task_id = $request->task_id;
        $candidate_id = $request->id;

        $action_title = '';

        $result='';
        $users = DB::table('users as u')
                    ->join('role_masters as rm', 'rm.id', '=', 'u.role')
                    ->join('role_permissions as rp', 'rp.role_id', '=', 'rm.id')
                    ->select('u.name','u.id','u.role as role_id','rm.role','rp.permission_id' )
                    ->where(['u.business_id'=>Auth::user()->business_id,'u.is_deleted'=>0,'u.status'=>1])
                    ->get();

        $action_master = DB::table('action_masters')
                        ->select('*')
                        ->where('route_group','=','')
                        ->get();

        

        if(count($users)>0)
        {
            $task = DB::table('tasks')->select('id','description')->where('id',$task_id)->first();

            if($task!=null)
            {
                if($task->description=='BGV Filling')
                {
                    $action_title='BGV Link';
                }
                else if($task->description=='BGV QC')
                {
                    $action_title='BGV QC';
                }
                else if($task->description=='Report QC')
                {
                    $action_title='Report QC';
                }
            }

            foreach($users as $key => $user)
            {
                foreach($action_master as $key => $am)
                {
                    if(in_array($am->id,json_decode($user->permission_id)) && $am->action_title == $action_title)
                    {
                        $result .= '<option value='.$user->id.'>'.$user->name.'</option>';
                    }
                }
            }
        }

        return response()->json([
            'status' => true,
            'result' => $result
        ]);
    }

    public function taskVerifyInfo(Request $request)
    {
        $candidate_id= base64_decode($request->verify_candidate_id);
        $verify_service_id= base64_decode($request->verify_service_id);
        $verify_number_id= base64_decode($request->verify_number_id);

        $user_id = Auth::user()->id;
        $business_id = Auth::user()->business_id;

        $report_id ="";
        $jaf_item = [];
        
          $candidate = DB::table('users as u')
          ->select('u.id','u.business_id','u.client_emp_code','u.entity_code','u.display_id','u.first_name','u.middle_name','u.last_name','u.name','u.email','u.phone','u.phone_code','u.phone_iso','u.dob','u.aadhar_number','u.father_name','u.gender','j.created_at','j.job_id','j.sla_id','j.is_all_insuff_cleared','j.insuff_cleared_by','j.jaf_status','u.digital_signature','j.is_jaf_ready_report','u.digital_signature_file_platform')  
          ->leftjoin('job_items as j','j.candidate_id','=','u.id')
          ->where(['u.id'=>$candidate_id]) 
          ->first(); 

          //get JAF data - 
          $jaf_item = DB::table('jaf_form_data as jf')
                ->select('jf.id','jf.form_data_all','jf.form_data','jf.check_item_number','jf.address_type','jf.insufficiency_notes','jf.is_insufficiency','jf.insuff_attachment','jf.is_api_checked','jf.verification_status','jf.verification_mode','jf.verified_at','jf.is_data_verified','s.name as service_name','s.id as service_id','s.verification_type','jf.candidate_id','jf.is_supplementary','s.type_name')
                ->join('services as s','s.id','=','jf.service_id')
                ->where(['jf.candidate_id'=>$candidate_id,'jf.service_id'=>$verify_service_id,'jf.check_item_number'=>$verify_number_id])
                ->orderBy('s.sort_number','asc')
                ->orderBy('jf.check_item_number','asc')
                ->first();
                // dd($jaf_item);
          if($jaf_item){
            $is_insuff_arr=$jaf_item->is_insufficiency;
          }

          // dd($is_insuff_arr);
          // dd($jaf_items);
          // $job_items=DB::table('job_items')->where(['candidate_id'=>$candidate_id])->first();
          // dFd($kams);

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

            $report_items = [];

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

         $services = DB::table('services')
                            ->select('name','id')
                            ->where(['status'=>'1'])
                            ->whereNull('business_id')
                            ->whereNotIn('type_name',['gstin'])
                            ->orwhere('business_id',$business_id)
                            ->get();

         $task_for_verify = DB::table('tasks as t')
                            ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.tat','ta.reassign_by','ta.created_at as created')
                            ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
                            ->where(['t.candidate_id'=>$candidate_id,'t.service_id'=>$verify_service_id,'t.number_of_verifications'=>$verify_number_id])
                            ->whereNotIn('ta.status', ['0'])
                            ->first();
        $viewRender = view('admin.candidates.task-jaf-info',compact('candidate','jaf_item','is_insuff_arr','report','status_list','report_items','report_id','services','user_service_check','task_for_verify'))->render();
        return response()->json(array('success' => true, 'html'=>$viewRender));


    }
}
// service base task
// SELECT u.name as username,u.email,ta.business_id,ta.user_id,ta.service_id,s.name,ta.candidate_id,ta.created_at,ta.job_sla_item_id,ta.task_id,ta.status,ta.reassign_to FROM task_assignments as ta JOIN users AS u ON u.id=ta.user_id JOIN services AS s ON ta.service_id = s.id WHERE u.user_type = 'user'  AND ta.user_id= $user_id OR ta.reassign_to=$user_id