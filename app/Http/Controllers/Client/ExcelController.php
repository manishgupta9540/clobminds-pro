<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use App\Exports\ClientProgressDataExport;
use App\Exports\ProgressDataExportClientNtt;
use App\Exports\ClientProgressDataExportNtt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ExcelController extends Controller
{
    //

    public function progressExport(Request $request)
    {
        $business_id = Auth::user()->business_id;
       
        $today_date = date('Y-m-d');

        $start_date = date('Y-m-d',strtotime('-2 month'));

        // $file_name = 'progress-tracker-('.$start_date.' - '.$today_date.')-'.date('YmdHis').'.xlsx';

        $file_name = 'progress-tracker-'.date('YmdHis').'.xlsx';

        $file_name_client = 'progress-tracker-client-'.date('YmdHis').'.xlsx';

        $user_query = DB::table('users as u')
                    ->select('u.*')
                    ->where(['u.id'=>$business_id,'u.user_type'=>'client'])
                    ->first();

        $user_query_single = $user_query->display_id;
        // dd($user_query_single);

        $candidate_list = DB::table('users as u')
        ->DISTINCT('ri.candidate_id')
        ->select('u.id as candidate_id','ri.service_id')
        ->join('user_businesses as ub','u.business_id','=','ub.business_id')
        ->join('job_items as j','j.candidate_id','=','u.id')
        ->join('reports as r','r.candidate_id','=','u.id')
        ->join('report_items as ri','r.id','=','ri.report_id')
        ->join('services as s','s.id','=','ri.service_id')
        ->where(['u.business_id'=>$business_id,'u.user_type'=>'candidate','u.is_deleted'=>0,'j.jaf_status'=>'filled']);

            //$candidate_list=$candidate_list->whereDate('u.created_at','>=',$start_date)->whereDate('u.created_at','<=',$today_date);

        $service_ids = $candidate_list->whereNotIn('s.type_name',['address','employment','global_database','reference','pan','criminal','e_court'])->groupBy('ri.candidate_id')->orderBy('ri.service_id','asc')->pluck('ri.service_id')->all();

        $service_ids=array_unique($service_ids);

        sort($service_ids);

        // if($user_query_single == 'NTT-0000001867')
        // {
            // return Excel::download(new ProgressDataExportClientNtt($start_date,$today_date,$business_id,$candidate_id,$service_id),$file_name_client);

            return Excel::download(new ClientProgressDataExportNtt($start_date,$today_date,$business_id,$service_ids),$file_name_client);
        //}

        // $candidate_id = $candidate_list->groupBy('ri.candidate_id')->orderBy('ri.candidate_id','desc')->pluck('ri.candidate_id')->all();

        // //dd($candidate_id);
        
        // if($candidate_id!=NULL && count($candidate_id) > 0)
        // {
        //     $service_id = DB::table('report_items as ri')
        //                     ->DISTINCT('ri.service_id')
        //                     ->select('ri.service_id')
        //                     ->whereIn('ri.candidate_id',$candidate_id)
        //                     ->groupBy('ri.service_id')
        //                     ->orderBy('ri.candidate_id','desc')
        //                     ->pluck('ri.service_id')
        //                     ->all();

        //     return Excel::download(new ClientProgressDataExport($start_date,$today_date,$business_id,$candidate_id,$service_id),$file_name);
        // }
        
        return 'No Data Found';

    }
}
