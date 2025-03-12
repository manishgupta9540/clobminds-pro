<?php

namespace App\Exports;
use App\User;
use Maatwebsite\Excel\Concerns\FromQuery;

use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

use App\Helpers\Helper;

class ClientProgressDataExportNtt implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents, WithMapping, WithColumnFormatting
{
    use Exportable;

    /**
    * @return \Illuminate\Support\Collection
    */

    protected $from_date;
    protected $to_date;
    protected $business_id;
    protected $service_ids;

    function __construct($from_date,$to_date,$business_id,$service_ids) {
        $this->from_date   = $from_date;
        $this->to_date   = $to_date;
        $this->business_id   = $business_id;
        $this->service_ids   = $service_ids;
    }

    public function collection()
    {
        $user = [];

        $query = DB::table('users as u')
                ->DISTINCT('js.candidate_id')
                ->select('u.*','ub.company_name','j.sla_title as sla_name','ub.department','ub.client_spokeman','j.tat','j.client_tat','j.tat_type','j.days_type','j.price_type','j.package_price','r.is_report_complete','r.status as report_status','r.report_complete_created_at as case_completed_date','r.generated_at','r.is_manual_mark','r.id as report_id','ca.access_id','u.work_order_id','u.is_report_generate','u.report_generate_created_at','r.is_manual_mark')
                ->join('user_businesses as ub','u.business_id','=','ub.business_id')
                ->join('job_items as j','j.candidate_id','=','u.id')
                ->leftJoin('candidate_accesses as ca','ca.candidate_id','=','u.id')
                //->join('job_sla_items as js','js.job_item_id','=','j.id')
                ->join('reports as r','r.candidate_id','=','u.id')
                //->join('services as s','s.id','=','js.service_id')
                //->whereIn('s.type_name',['address','employment','global_database','reference','pan','criminal','e_court'])
                ->where(['u.business_id'=>$this->business_id,'u.user_type'=>'candidate','u.is_deleted'=>0,'j.jaf_status'=>'filled']);
    
        $user = $query->orderBy('u.id','desc')->get();  

        // return collect($user);

        return $user;
    }

    public function map($user): array
    {
        $company_name = NULL;
        $user_access = DB::table('users')->where('id',$user->access_id)->first();

        $user_company = '';
        if($user_access!=null && $user_access->company_name!=null){
            $user_company = $user_access->company_name;
        }

        $candidate_hold = DB::table('candidate_hold_statuses')
                            ->where(['candidate_id'=>$user->id])
                            ->whereNotNull('hold_by')
                            ->whereNull('hold_remove_by')
                            ->latest()
                            ->first();

        // $addr_count = 2;

        $emp_count = 3;

        $ref_count = 2;

        // $db_count = 1;

        // $location  = 'N/A';
        // $process = 'N/A';
        // $sub_client_name = 'N/A';

        $data = [
            $user->client_emp_code!=NULL ? $user->client_emp_code : 'N/A',
            $user->work_order_id!=NULL ? $user->work_order_id : 'N/A',
            $user->name!=NULL ? $user->name : 'N/A',
            $user->phone!=NULL ? $user->phone : 'N/A',
            $user->email!=NULL ? $user->email : 'N/A',
            $user->display_id!=NULL ? $user->display_id : 'N/A',
            $user_company!=NULL && $user_company!='' ? $user_company : 'N/A',
            $user->sla_name!=NULL ? $user->sla_name : 'N/A',
            $user->case_received_date!=NULL ? date('d-M-y',strtotime($user->case_received_date)) : 'N/A',

        ];

        // 1st level insuff raise & clear

        $first_raise_date = 'N/A';

        $first_clear_date = 'N/A';

        $latest_raise_insuff = DB::table('insufficiency_logs')->where(['candidate_id'=>$user->id])->whereIn('status',['raised','failed'])->latest()->first();

        if($latest_raise_insuff!=NULL)
        {
            $first_raise_date = date('d-M-y',strtotime($latest_raise_insuff->created_at));
        }
        
        $latest_clear_insuff = DB::table('insufficiency_logs')->where(['candidate_id'=>$user->id,'status'=>'removed'])->latest()->first();

        if($latest_clear_insuff!=NULL)
        {
            $first_clear_date = date('d-M-y',strtotime($latest_clear_insuff->created_at));
        }

        array_push($data,$first_raise_date,$first_clear_date);

        // Case Completed

        array_push($data,$user->is_report_generate==1 ? $user->report_generate_created_at : 'N/A');

        // Case Status

        $status = 'WIP';
        
        if($candidate_hold!=NULL)
        {
            $status = 'STOP';
        }
        else if(stripos($user->report_status,'completed')!==false)
        {
            $status = 'Complete';
        }

        array_push($data,$status);

        // Report Color Code

        $color_code = '';
        if($user->is_manual_mark==0)
            $color_code='N/A';
        if($user->is_manual_mark==1)
            $color_code='Green';
        elseif($user->is_manual_mark==2)
            $color_code='Stopped'; 
        elseif($user->is_manual_mark==3)
            $color_code='Red';
        elseif($user->is_manual_mark==4)
            $color_code='Yellow';
        elseif($user->is_manual_mark==5)
            $color_code='Orange';
        elseif($user->is_manual_mark==6)
            $color_code='Interim';
        else
            $color_code=Helper::get_approval_status_color_name($user->report_id);

        array_push($data,$color_code);

        // Criminal Check

        $cr_check_items = DB::table('report_items as ri')
                        ->select('ri.*')
                        ->join('services as s','s.id','=','ri.service_id')
                        ->where(['ri.candidate_id'=>$user->id,'s.type_name'=>'criminal'])
                        ->orderBy('ri.service_item_number','asc')
                        ->first();

        $cr_detail = '';

        $cr_status = null;

        if($cr_check_items !=null  && $cr_check_items->jaf_data!=NULL)
        {
            $cr_status=Helper::get_report_item_approval_status($cr_check_items->id);

            $input_item_data_array=[];
            $input_item_data_array = json_decode($cr_check_items->jaf_data,true);

            foreach($input_item_data_array as $key => $input)
            {
                $key_val = array_keys($input); 
                $input_val = array_values($input);

                $cr_detail.=$key_val[0].':'.$input_val[0];

                if($key+1!=count($input_item_data_array))
                {
                    $cr_detail.=', ';
                }
            }
        }

        array_push($data,str_replace(' ','',$cr_detail)!='' ? $cr_detail : 'N/A',$cr_status!=null ? $cr_status->name : 'N/A');

         // Database Check

         $db_check_items = DB::table('report_items as ri')
         ->select('ri.*')
         ->join('services as s','s.id','=','ri.service_id')
         ->where(['ri.candidate_id'=>$user->id,'s.type_name'=>'global_database'])
         ->orderBy('ri.service_item_number','asc')
         ->first();

        $db_detail = '';

        $db_status = null;

        if($db_check_items !=null  && $db_check_items->jaf_data!=NULL)
        {
            $db_status=Helper::get_report_item_approval_status($db_check_items->id);

            $input_item_data_array=[];
            $input_item_data_array = json_decode($db_check_items->jaf_data,true);

            foreach($input_item_data_array as $key => $input)
            {
                $key_val = array_keys($input); 
                $input_val = array_values($input);

                $db_detail.=$key_val[0].':'.$input_val[0];

                if($key+1!=count($input_item_data_array))
                {
                    $db_detail.=', ';
                }
            }
        }

        array_push($data,str_replace(' ','',$db_detail)!='' ? $db_detail : 'N/A',$db_status!=null ? $db_status->name : 'N/A');

         //addr check current
         $current_ad_check_items = DB::table('report_items as ri')
                                    ->select('ri.*')
                                    ->join('services as s','s.id','=','ri.service_id')
                                    ->join('jaf_form_data as j','j.id','=','ri.jaf_id')
                                    ->where(['ri.candidate_id'=>$user->id,'s.type_name'=>'address','j.address_type'=>'current'])
                                    ->orderBy('ri.service_item_number','asc')
                                    ->first();

        $current_detail = '';

        $cur_status = null;

        if($current_ad_check_items !=null  && $current_ad_check_items->jaf_data!=NULL)
        {
            $cur_status=Helper::get_report_item_approval_status($current_ad_check_items->id);

            $input_item_data_array=[];
            $input_item_data_array = json_decode($current_ad_check_items->jaf_data,true);

            foreach($input_item_data_array as $key => $input)
            {
                $key_val = array_keys($input); 
                $input_val = array_values($input);

                $current_detail.=$key_val[0].':'.$input_val[0];

                if($key+1!=count($input_item_data_array))
                {
                    $current_detail.=', ';
                }
            }
        }

        array_push($data,str_replace(' ','',$current_detail)!='' ? $current_detail : 'N/A',$cur_status!=null ? $cur_status->name : 'N/A');

         //addr check current
         $per_ad_check_items = DB::table('report_items as ri')
                                    ->select('ri.*')
                                    ->join('services as s','s.id','=','ri.service_id')
                                    ->join('jaf_form_data as j','j.id','=','ri.jaf_id')
                                    ->where(['ri.candidate_id'=>$user->id,'s.type_name'=>'address','j.address_type'=>'permanent'])
                                    ->orderBy('ri.service_item_number','asc')
                                    ->first();

        $per_detail = '';

        $per_status = null;

        if($per_ad_check_items !=null  && $per_ad_check_items->jaf_data!=NULL)
        {

            $per_status=Helper::get_report_item_approval_status($per_ad_check_items->id);

            $input_item_data_array=[];
            $input_item_data_array = json_decode($per_ad_check_items->jaf_data,true);

            foreach($input_item_data_array as $key => $input)
            {
                $key_val = array_keys($input); 
                $input_val = array_values($input);

                $per_detail.=$key_val[0].':'.$input_val[0];

                if($key+1!=count($input_item_data_array))
                {
                    $per_detail.=', ';
                }
            }
        }

        array_push($data,str_replace(' ','',$per_detail)!='' ? $per_detail : 'N/A',$per_status!=null ? $per_status->name : 'N/A');

        //PAN Check
        $pan_check_items = DB::table('report_items as ri')
                            ->select('ri.*')
                            ->join('services as s','s.id','=','ri.service_id')
                            ->where(['ri.candidate_id'=>$user->id,'s.type_name'=>'pan'])
                            ->orderBy('ri.service_item_number','asc')
                            ->first();

        $pan_detail = '';

        $pan_status = null;

        if($pan_check_items !=null  && $pan_check_items->jaf_data!=NULL)
        {

            $pan_status=Helper::get_report_item_approval_status($pan_check_items->id);

            $input_item_data_array=[];
            $input_item_data_array = json_decode($pan_check_items->jaf_data,true);

            foreach($input_item_data_array as $key => $input)
            {
                $key_val = array_keys($input); 
                $input_val = array_values($input);

                $pan_detail.=$key_val[0].':'.$input_val[0];

                if($key+1!=count($input_item_data_array))
                {
                    $pan_detail.=', ';
                }
            }
        }

        array_push($data,str_replace(' ','',$pan_detail)!='' ? $pan_detail : 'N/A',$per_status!=null ? $per_status->name : 'N/A');

        //Employment Check

        for($i=1;$i<=$emp_count;$i++)
        {
            $emp_check_items = DB::table('report_items as ri')
                            ->select('ri.*')
                            ->join('services as s','s.id','=','ri.service_id')
                            ->where(['ri.candidate_id'=>$user->id,'s.type_name'=>'employment','ri.service_item_number'=>$i])
                            ->orderBy('ri.service_item_number','asc')
                            ->first();
            $emp_name = '';

            $emp_status=null;

            if($emp_check_items !=null  && $emp_check_items->jaf_data!=NULL)
            {
                $emp_status=Helper::get_report_item_approval_status($emp_check_items->id);

                $input_item_data_array=[];
                $input_item_data_array = json_decode($emp_check_items->jaf_data,true);

                foreach($input_item_data_array as $key => $input)
                {

                    $key_val = array_keys($input); 
                    $input_val = array_values($input);

                    if(stripos($key_val[0],'Company name')!==false){ 
                        $emp_name .='Company name-' .' '.$input_val[0].',';
                    }
                    
                    if(stripos($key_val[0],'Employee Designation')!==false){ 
                        $emp_name .= 'Employee Designation-' .' '.$input_val[0];
                    }

                    if(stripos($key_val[0],'Employee Code')!==false){ 
                        $emp_name .= 'Employee Code-' .' '.$input_val[0].',';
                    }       
                }
            }

            array_push($data,str_replace(' ','',$emp_name)!='' ? $emp_name : 'N/A',$emp_status!=null ? $emp_status->name : 'N/A');
            
        }

        // Reference Check

        for($i=1;$i<=$ref_count;$i++)
        {
            $ref_check_items = DB::table('report_items as ri')
                                ->select('ri.*')
                                ->join('services as s','s.id','=','ri.service_id')
                                ->where(['ri.candidate_id'=>$user->id,'s.type_name'=>'reference','ri.service_item_number'=>$i])
                                ->orderBy('ri.service_item_number','asc')
                                ->first();
            $ref_detail = '';

            $ref_status = null;

            if($ref_check_items !=null  && $ref_check_items->jaf_data!=NULL)
            {
                $ref_status=Helper::get_report_item_approval_status($ref_check_items->id);

                $input_item_data_array=[];
                $input_item_data_array = json_decode($ref_check_items->jaf_data,true);

                foreach($input_item_data_array as $key => $input)
                {
                    $key_val = array_keys($input); 
                    $input_val = array_values($input);

                    $ref_detail.=$key_val[0].':'.$input_val[0];

                    if($key+1!=count($input_item_data_array))
                    {
                        $ref_detail.=', ';
                    }
                }
            }

            array_push($data,str_replace(' ','',$ref_detail)!='' ? $ref_detail : 'N/A',$ref_status!=null ? $ref_status->name : 'N/A');

        }

        if(count($this->service_ids)>0)
        {
            foreach($this->service_ids  as $service_id)
            {
                $service_check_items = DB::table('report_items as ri')
                                ->select('ri.*')
                                ->join('services as s','s.id','=','ri.service_id')
                                ->where(['ri.candidate_id'=>$user->id,'s.id'=>$service_id])
                                ->orderBy('ri.service_item_number','asc')
                                ->first();
                $service_detail = '';

                $service_status = null;

                if($service_check_items!=null && $service_check_items->jaf_data!=null)
                {
                    $service_status=Helper::get_report_item_approval_status($service_check_items->id);

                    $input_item_data_array=[];
                    $input_item_data_array = json_decode($service_check_items->jaf_data,true);

                    foreach($input_item_data_array as $key => $input)
                    {
                        $key_val = array_keys($input); 
                        $input_val = array_values($input);

                        $service_detail.=$key_val[0].':'.$input_val[0];

                        if($key+1!=count($input_item_data_array))
                        {
                            $service_detail.=', ';
                        }
                    }
                }

                array_push($data,str_replace(' ','',$service_detail)!='' ? $service_detail : 'N/A',$service_status!=null ? $service_status->name : 'N/A');
            }
        }

        return $data;
    }

    public function headings(): array
    {
        $columns = 
            [
                'Job Seeker ID',
                'Work Order ID',
                'Candidate Name',
                'Mobile No',
                'Email',
                'BGV ID',
                'Vendor Name',
                'BGV Code',
                'Case Initiated Date',
                'Latest Check Insuff Raised Date',
                'Latest Check Insuff Fulfill Date',
                'Case Completed Date',
                'Case Status',
                'Report Color Code',
                'Criminal Check',
                'Status',
                'Database Check',
                'Status',
                'Current Address',
                'Status',
                'Permanent Address',
                'Status',
                'PAN',
                'Status',
                'Employment Check - 1',
                'Status',
                'Employment Check - 2',
                'Status',
                'Employment Check - 3',
                'Status',
                'Reference Check - 1',
                'Status',
                'Reference Check - 2',
                'Status'
            ];

        $services = DB::table('services')
                    ->select('id','name')
                    ->whereIn('id',$this->service_ids)
                    ->get();

        if(count($services)>0)
        {
            foreach($services as $service)
            {
                array_push($columns,$service->name.' - 1','Status');
            }
        }

        return $columns;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) 
            {
                $cellRange = 'A1:ZZ1'; // All headers

                $event->sheet->getDelegate()->getStyle($cellRange)
                                            ->getAlignment()
                                            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                                            ->setVertical(Alignment::VERTICAL_CENTER);

                // $event->sheet->getDelegate()->getStyle('A1:AB1')->getFill()
                //                         ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                //                         ->getStartColor()
                //                         ->setRGB('F2DCDA');

                // $event->sheet->getDelegate()->getStyle('A1:AB1')
                //                             ->getFont()
                //                             ->getColor()
                //                             ->setRGB('000000');

                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(12);
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(true);

            }
        ];
    }

    public function columnFormats(): array
    {
        return [
            
        ];
    }
}
