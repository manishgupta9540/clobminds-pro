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

class ProgressDataExportClientNtt implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents, WithMapping, WithColumnFormatting
{
    use Exportable;

    /**
    * @return \Illuminate\Support\Collection
    */

 	protected $from_date;
    protected $to_date;
    protected $business_id;

    function __construct($from_date,$to_date,$business_id,$candidate_id,$service_id) {
        $this->from_date   = $from_date;
        $this->to_date   = $to_date;
        $this->business_id   = $business_id;
        $this->candidate_id = $candidate_id;
        $this->service_id = $service_id;
    }

    public function collection()
    {
        $query = DB::table('users as u')
                ->DISTINCT('js.candidate_id')
                ->select('u.*','ub.company_name','j.sla_title as sla_name','ub.department','ub.client_spokeman','j.tat','j.client_tat','j.tat_type','j.days_type','j.price_type','j.package_price','r.is_report_complete','r.status as report_status','r.report_complete_created_at as case_completed_date','ca.access_id','r.generated_at','r.is_manual_mark','r.id as report_id')
                ->join('user_businesses as ub','u.business_id','=','ub.business_id')
                ->join('job_items as j','j.candidate_id','=','u.id')
                ->leftJoin('candidate_accesses as ca','ca.candidate_id','=','u.id')
                ->join('job_sla_items as js','js.job_item_id','=','j.id')
                ->join('reports as r','r.candidate_id','=','u.id')
                ->join('services as s','s.id','=','js.service_id')
                ->whereIn('s.type_name',['address','employment','educational','database','global_database','judicial','drug_test_5','e_court','reference','identity_verification','criminal'])
                ->where(['u.business_id'=>$this->business_id,'u.user_type'=>'candidate','u.is_deleted'=>0,'j.jaf_status'=>'filled']);
    
        $user = $query->orderBy('u.id','desc')->get();  

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

        $addr_count = 1;
        $current_addr_count = 2;
        $digital_count = 2;
        $emp_count = 4;
        $edu_count = 4;
        $ref_count = 1;
        $drg_count = 3;
        $uan_count = 2; 
        $db_count = 10;
        $all_columns = 23;
        $case_flex_columns = 30;
        $data_source = 'N/A';
        $location  = 'N/A';
        $process = 'N/A';
        $sub_client_name = 'N/A';
        // $candidate_date = date('Y-m-d',strtotime($user->created_at));
       
        $data = [
                    $user->display_id!=NULL ? $user->display_id : 'N/A',
                    $data_source = 'N/A',
                    $user->client_emp_code!=NULL ? $user->client_emp_code : 'N/A',
                    $user->company_name!=NULL ? $user->company_name : 'N/A',
                    $user_company!=NULL ? $user_company : 'N/A',
                    $user->name!=NULL ? $user->name : 'N/A',
                    $user->phone!=NULL ? $user->phone : 'N/A',
                    $user->email!=NULL ? $user->email : 'N/A',
                    $location  = 'N/A',
                    $user->sla_name!=NULL ? $user->sla_name : 'N/A',
                    $user->case_received_date!=NULL ? date('d-M-y',strtotime($user->case_received_date)) : 'N/A',

                ];
               
            array_push($data);

         // 1st level & 2nd insuff raise & clear

         $first_raise_date = 'N/A';

         $first_clear_date = 'N/A';
 
         $raise_insuff_all = DB::table('insufficiency_logs')->where(['candidate_id'=>$user->id])->whereIn('status',['raised','failed'])->orderBy('created_at','desc')->take(2)->get();
        
         $clear_insuff_all = DB::table('insufficiency_logs')->where(['candidate_id'=>$user->id,'status'=>'removed'])->orderBy('created_at','desc')->take(2)->get();
        
         if(count($raise_insuff_all) > 0)
         {
             $latest_raise_insuff = DB::table('insufficiency_logs')->where(['candidate_id'=>$user->id])->whereIn('status',['raised','failed'])->latest()->first();
 
             if($latest_raise_insuff!=NULL)
             {
                 $first_raise_date = date('d-M-y',strtotime($latest_raise_insuff->created_at));
             }
         }
 
         if(count($clear_insuff_all) > 0)
         {
             $latest_clear_insuff = DB::table('insufficiency_logs')->where(['candidate_id'=>$user->id,'status'=>'removed'])->latest()->first();
 
             if($latest_clear_insuff!=NULL)
             {
                 $first_clear_date = date('d-M-y',strtotime($latest_clear_insuff->created_at));
             }
 
         }
 
         array_push($data,$first_raise_date,$first_clear_date);


        // Address Check permanent
        $addr_report_items = DB::table('jaf_form_data as ri')
                        ->select('ri.*')
                        ->join('services as s','s.id','=','ri.service_id')
                        ->where(['ri.candidate_id'=>$user->id,'s.type_name'=>'address','ri.address_type'=>'permanent'])
                        ->orderBy('ri.check_item_number','asc')
                        ->first();
        // dd($addr_report_items);
        $permanent_addr_detail = 'N/A';

        $remain = 0;
        
        
        if($addr_report_items !=null  && $addr_report_items->form_data!=NULL)
        {
            $input_item_data_array=[];
            $input_item_data_array = json_decode($addr_report_items->form_data,true);
    
            if(count($input_item_data_array)>0)
            {
                $permanent_addr_detail = '';

                foreach($input_item_data_array as $key => $input)
                {
                    $key_val = array_keys($input); 
                    $input_val = array_values($input);
                    
                    if(stripos($key_val[0],'Address')!==false){ 
                        //dd($input_val[0]);
                        $permanent_addr_detail .= $input_val[0];
                    }

                    if(stripos($key_val[0],'State')!==false){ 
                        $permanent_addr_detail .= ', '.$input_val[0];
                    }

                    if(stripos($key_val[0],'Pin code')!==false){ 
                        $permanent_addr_detail .= ', '.$input_val[0];
                    }

                    if(stripos($key_val[0],'City')!==false){ 
                        $permanent_addr_detail.= ', '.$input_val[0];
                    }
                }
            }
        }
    
        array_push($data,str_replace(' ','',$permanent_addr_detail)!='' ? $permanent_addr_detail : 'N/A');


         //addr echeck  current
        $current_report_items = DB::table('jaf_form_data as ri')
                    ->select('ri.*')
                    ->join('services as s','s.id','=','ri.service_id')
                    ->where(['ri.candidate_id'=>$user->id,'s.type_name'=>'address','ri.address_type'=>'current'])
                    ->orderBy('ri.check_item_number','asc')
                    ->get();
        // dd($current_report_items);

          $curent_addr_detail = 'N/A';
  
          if(count($current_report_items) > 0)
          {
              $remain = 0;
  
              $j = 0;
  
              foreach($current_report_items as $item)
              {
                  $curent_addr_detail = 'N/A'; 
  
                  if($item->form_data!=NULL)
                  {
                      $input_item_data_arrayc=[];
                      $input_item_data_arrayc = json_decode($item->form_data,true);
                   
                      if(count($input_item_data_arrayc)>0)
                      {
                          $curent_addr_detail = '';

                          foreach($input_item_data_arrayc as $key => $input)
                          {
                              $key_val = array_keys($input); 
                              $input_val = array_values($input);
                             
                              if(stripos($key_val[0],'Address')!==false){ 
                                  $curent_addr_detail .= $input_val[0].',';
                              }
  
                              if(stripos($key_val[0],'State')!==false){ 
                                  $curent_addr_detail .= ' '.$input_val[0];
                              }
  
                              if(stripos($key_val[0],'Pin code')!==false){ 
                                  $curent_addr_detail .= ' '.$input_val[0].',';
                              }
  
                              if(stripos($key_val[0],'City')!==false){ 
                                  $curent_addr_detail .= ' '.$input_val[0].',';
                              }
                          }
                      }
                  }
                //   dd($curent_addr_detail);
                  array_push($data,str_replace(' ','',$curent_addr_detail)!='' ? $curent_addr_detail : 'N/A');
  
                  $j++;   
              }

            $remain = $current_addr_count - $j;

            if($remain > 0)
            {
                $curent_addr_detail = 'N/A';

                for($i=1;$i<=$remain;$i++)
                {
                    array_push($data,$curent_addr_detail);
                }
            }
          }
          else
          {
              for($i=1;$i<=$current_addr_count;$i++)
              {
                  array_push($data,$curent_addr_detail);
              }
          }  

          $digital_address = 'N/A';

          for($i=1;$i<=$digital_count;$i++)
          {
              array_push($data,$digital_address);
          }
  
         array_push($data);


        // Employment Check
        $emp_report_items = DB::table('jaf_form_data as ri')
                            ->select('ri.*')
                            ->join('services as s','s.id','=','ri.service_id')
                            ->where(['ri.candidate_id'=>$user->id,'s.type_name'=>'employment'])
                            ->orderBy('ri.check_item_number','asc')
                            ->get();
          
        $emp_name = 'N/A';

        if(count($emp_report_items) > 0)
        {
            $remain = 0;
            $j = 0;

            foreach($emp_report_items as $item)
            {
                $emp_name = 'N/A';

                if($item->form_data!=NULL)
                {
                    $input_item_data_array=[];
                    $input_item_data_array = json_decode($item->form_data,true);
                   
                    if(count($input_item_data_array)>0)
                    {
                        $emp_name = '';
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
                }
                array_push($data,$emp_name!='' ? $emp_name : $user->name);

                $j++;
            }

            $remain = $emp_count - $j;

            if($remain > 0)
            {
                $emp_name = 'N/A';

                for($i=1;$i<=$remain;$i++)
                {
                    array_push($data,$emp_name);
                }
            }
        }
        else
        {
            for($i=1;$i<=$emp_count;$i++)
            {
                array_push($data,$emp_name);
            }
        }


            //Uan Check
        $uan_report_items = DB::table('jaf_form_data as ri')
                            ->select('ri.*')
                            ->join('services as s','s.id','=','ri.service_id')
                            ->where(['ri.candidate_id'=>$user->id,'s.type_name'=>'uan-number'])
                            ->orderBy('ri.check_item_number','asc')
                            ->get();
        //  dd($uan_report_items);
        $uan_name = 'N/A';

        if(count($uan_report_items) > 0)
        {
            $remain = 0;

            $j = 0;

            foreach($uan_report_items as $item)
            {
                $uan_name = 'N/A';

                if($item->form_data!=NULL)
                {
                    $input_item_data_array=[];
                    $input_item_data_array = json_decode($item->form_data,true);
                
                    if(count($input_item_data_array)>0)
                    {
                        $uan_name = '';
                        foreach($input_item_data_array as $key => $input)
                        {
                            $key_val = array_keys($input); 
                            $input_val = array_values($input);

                            if(stripos($key_val[0],'First Name')!==false){ 
                                $uan_name .='First Name-' .' '.$input_val[0].',';
                            }

                            if(stripos($key_val[0],'Last Name')!==false){ 
                                $uan_name .='Last Name-' .' '.$input_val[0].',';
                            }

                            if(stripos($key_val[0],'UAN Number')!==false){ 
                                $uan_name .='UAN Number-' .' '.$input_val[0].' ';
                            }
                        }
                    }
                }

                array_push($data,$uan_name!='' ? $uan_name : $user->name);

                $j++;
                
            }

            $remain = $uan_count - $j;
            
            if($remain > 0)
            {
                $uan_name = 'N/A';

                for($i=1;$i<=$remain;$i++)
                {
                    array_push($data,$uan_name);
                }
            }
        }
        else
        {
            for($i=1;$i<=$uan_count;$i++)
            {
                array_push($data,$uan_name);
            }
        }

         // Education Check
        $edu_report_items = DB::table('jaf_form_data as ri')
                        ->select('ri.*')
                        ->join('services as s','s.id','=','ri.service_id')
                        ->where(['ri.candidate_id'=>$user->id,'s.type_name'=>'educational'])
                        ->orderBy('ri.check_item_number','asc')
                        ->get();
       
        $edu_university = 'N/A';

        if(count($edu_report_items) > 0)
        {
            $remain = 0;

            $j = 0;

            foreach($edu_report_items as $item)
            {
                $edu_university = 'N/A';

                if($item->form_data!=NULL)
                {
                    $input_item_data_array=[];
                    $input_item_data_array = json_decode($item->form_data,true);
                   
                    if(count($input_item_data_array)>0)
                    {
                         $edu_university = '';
                        foreach($input_item_data_array as $key => $input)
                        {
                            $key_val = array_keys($input); 
                            $input_val = array_values($input);

                            if(stripos($key_val[0],'University Name / Board Name')!==false){ 
                                $edu_university .='University Name / Board Name-' .' '.$input_val[0];
                            }
                        }
                    }
                }

                array_push($data,$edu_university!='' ? $edu_university : 'N/A');

                $j++;
                
            }

            $remain = $edu_count - $j;

            if($remain > 0)
            {
                $edu_university = 'N/A';

                for($i=1;$i<=$remain;$i++)
                {
                    array_push($data,$edu_university);
                }
            }
        }
        else
        {
            for($i=1;$i<=$edu_count;$i++)
            {
                array_push($data,$edu_university);
            }
        }

         // Database Check
        $db_report_items = DB::table('jaf_form_data as ri')
                        ->select('ri.*')
                        ->join('services as s','s.id','=','ri.service_id')
                        ->where(['ri.candidate_id'=>$user->id,'s.type_name'=>'global_database'])
                        ->orderBy('ri.check_item_number','asc')
                        ->get();
        //    dd($db_report_items);
        $candidate_name = 'N/A';

        if(count($db_report_items) > 0)
        {
            $remain = 0;

            $j = 0;

            foreach($db_report_items as $item)
            {
                $candidate_name = '';

                if($item->form_data!=NULL)
                {
                    $input_item_data_array=[];
                    $input_item_data_array = json_decode($item->form_data,true);
                   
                    if(count($input_item_data_array)>0)
                    {
                         $candidate_name = '';
                        foreach($input_item_data_array as $key => $input)
                        {
                            $key_val = array_keys($input); 
                            $input_val = array_values($input);

                            if(stripos($key_val[0],'Candidate Name')!==false){ 
                                $candidate_name .='Candidate Name-' .' '.$input_val[0].',';
                            }

                            if(stripos($key_val[0],'Father Name')!==false){ 
                                $candidate_name .='Father Name-' .' '.$input_val[0].',';
                            }

                            if(stripos($key_val[0],'Date of Birth')!==false){ 
                                $candidate_name .='Date of Birth-' .' '.$input_val[0].',';
                            }

                            if(stripos($key_val[0],'Country')!==false){ 
                                $candidate_name .='Country-' .' '.$input_val[0];
                            }
                        }
                    }
                }
                // dd($candidate_name);
                array_push($data,$candidate_name!='' ? $candidate_name : 'N/A');

                $j++;
                
            }

            $remain = $db_count - $j;

            if($remain > 0)
            {
                $candidate_name = 'N/A';

                for($i=1;$i<=$remain;$i++)
                {
                    array_push($data,$candidate_name);
                }
            }
        }
        else
        {
            for($i=1;$i<=$db_count;$i++)
            {
                array_push($data,$candidate_name);
            }
        }

        // Reference Check
        $ref_report_items = DB::table('jaf_form_data as ri')
                        ->select('ri.*')
                        ->join('services as s','s.id','=','ri.service_id')
                        ->where(['ri.candidate_id'=>$user->id,'s.type_name'=>'reference'])
                        ->orderBy('ri.check_item_number','asc')
                        ->first();

            $ref_name = 'N/A';
            $remain = 0;
 
        if($ref_report_items !=NULL && $ref_report_items->form_data!=NULL)
        {
            $input_item_data_array=[];
            $input_item_data_array = json_decode($item->form_data,true);

            if(count($input_item_data_array)>0)
            {
                $ref_name = '';
                foreach($input_item_data_array as $key => $input)
                {
                    $key_val = array_keys($input); 
                    $input_val = array_values($input);

                    if(stripos($key_val[0],'Referee Name')!==false){ 
                        $ref_name .='Referee Name-' .' '.$input_val[0].',';  
                    }

                    if(stripos($key_val[0],'Referee Contact Number')!==false){ 
                        $ref_name .='Referee Contact Number-' .' '.$input_val[0];
                        
                    }
                }
            }
        }
        // dd($input_item_data_array);
        array_push($data,$ref_name!='' ? $ref_name : 'N/A');


         // Drug Test 5
         $drugtestf_report_items = DB::table('jaf_form_data as ri')
                                    ->select('ri.*')
                                    ->join('services as s','s.id','=','ri.service_id')
                                    ->where(['ri.candidate_id'=>$user->id,'s.type_name'=>'drug_test_5'])
                                    ->orderBy('ri.check_item_number','asc')
                                    ->get();
 
         $drg_name = 'N/A';
 
         if(count($drugtestf_report_items) > 0)
         {
             $remain = 0;
 
             $j = 0;
 
             foreach($drugtestf_report_items as $item)
             {
                 $drg_name = '';
 
                 if($item->form_data!=NULL)
                 {
                     $input_item_data_array=[];
                     $input_item_data_array = json_decode($item->form_data,true);
 
                     if(count($input_item_data_array)>0)
                     {
                         $drg_name = '';
                         foreach($input_item_data_array as $key => $input)
                         {
                             $key_val = array_keys($input); 
                             $input_val = array_values($input);
 
                             if(stripos($key_val[0],'First Name')!==false){ 
                                 $drg_name .='First Name-' .' '.$input_val[0].',';  
                             }
 
                             if(stripos($key_val[0],'Contact Number')!==false){ 
                                $drg_name .='Contact Number-' .' '.$input_val[0].',';  
                             }
                         }
                     }
                 }
 
                 array_push($data,$drg_name!='' ? $drg_name : 'N/A');
 
                 $j++;
                 
             }
 
             $remain = $drg_count - $j;
 
             if($remain > 0)
             {
                 $drg_name = 'N/A';
 
                 for($i=1;$i<=$remain;$i++)
                 {
                     array_push($data,$drg_name);
                 }
             }
         }
         else
         {
             for($i=1;$i<=$drg_count;$i++)
             {
                 array_push($data,$drg_name);
             }
         }

         //all vlue append 
        $pcc_detail = 'N/A';

        for($i=1;$i<=$all_columns;$i++)
        {
            array_push($data,$pcc_detail);
        }

        array_push($data);

        //delevery date
        $generated_date = '';
        if($user!=null && $user->generated_at!=null){
            $generated_date = $user->generated_at;
        }

        array_push($data,$generated_date!='' ? $generated_date : 'N/A');
        
        //case status 
        $color_name = '';

        if($user->is_manual_mark==0)
        {
            $color_name = 'N/A';
        }
        else if($user->is_manual_mark==1)
        {
            $color_name = 'Green';
        }
        else if($user->is_manual_mark==2)
        {
            $color_name = 'Stopped';
        }
        else if($user->is_manual_mark==3)
        {
            $color_name = 'Red';
        }
        else if($user->is_manual_mark==4)
        {
            $color_name = 'Yellow';
        }
        else if($user->is_manual_mark==5)
        {
            $color_name = 'Orange';
        }
        else if($user->is_manual_mark==6)
        {
            $color_name = 'Interim';
        }
        else
        {
            $color_name = Helper::get_approval_status_color_name($user->report_id);
        }
        
        array_push($data,$color_name!=''?$color_name:'N/A');

        //case_flex_columns
        $case_flex = 'N/A';

        for($i=1;$i<=$case_flex_columns;$i++)
        {
            array_push($data,$case_flex);
        }

        array_push($data);

        // foreach($this->service_id as $service_id)
        // {
        //     $no_of_verification=1;
        //     $max_verification=1;

        //     $max_verification=DB::table('report_items as ri')
        //                     ->select('ri.service_item_number')
        //                     ->where('ri.service_id',$service_id)
        //                     ->whereIn('ri.candidate_id',$this->candidate_id)
        //                     ->max('ri.service_item_number');
        //     //dd($max_verification);
        //     $report_items=DB::table('report_items')
        //                 ->where(['candidate_id'=>$user->id,'service_id'=>$service_id])
        //                 ->orderBy('service_item_number','asc')
        //                 ->get();

        //     $no_of_verification=count($report_items);

        //     $services=DB::table('services')->where('id',$service_id)->first();

        //     // Check Label Name

        //     if(stripos($services->type_name,'educational')!==false)
        //     {
        //         array_push($data,'Education');
        //     }
        //     else
        //     {
        //         array_push($data,ucwords($services->name));
        //     }

        //     // Details & Remarks

        //     if(count($report_items)>0)
        //     {
        //         if($no_of_verification == $max_verification)
        //         {
        //             foreach($report_items as $item)
        //             {
        //                 $details = '';

        //                 $remarks = 'WIP';

        //                 if($item->is_data_verified==1)
        //                     $remarks = 'Completed';

        //                 $jaf_data = $item->jaf_data;

        //                 if($jaf_data!=NULL)
        //                 {
        //                     $jaf_data_arr = [];

        //                     $jaf_data_arr=json_decode($jaf_data,true);

        //                     foreach($jaf_data_arr as $k => $input)
        //                     {
        //                         $key_val = array_keys($input); $input_val = array_values($input);

        //                         if(stripos($services->type_name,'address')!==false)
        //                         {
        //                             if(stripos($key_val[0],'Address')!==false || stripos($key_val[0],'Pincode')!==false)
        //                             {
        //                                 $details.=$key_val[0].': '.$input_val[0].', ';
        //                             }  
        //                         }
        //                         elseif(stripos($services->type_name,'aadhar')!==false)
        //                         {
        //                             if(stripos($key_val[0],'Aadhar Number')!==false)
        //                             {
        //                                 $details.=$key_val[0].': '.$input_val[0].', ';
        //                             }  
        //                         }
        //                         elseif(stripos($services->type_name,'pan')!==false)
        //                         {
        //                             if(stripos($key_val[0],'PAN Number')!==false)
        //                             {
        //                                 $details.=$key_val[0].': '.$input_val[0].', ';
        //                             }  
        //                         }
        //                         elseif(stripos($services->type_name,'voter_id')!==false)
        //                         {
        //                             if(stripos($key_val[0],'Voter ID Number')!==false)
        //                             {
        //                                 $details.=$key_val[0].': '.$input_val[0].', ';
        //                             }  
        //                         }
        //                         elseif(stripos($services->type_name,'rc')!==false)
        //                         {
        //                             if(stripos($key_val[0],'RC Number')!==false)
        //                             {
        //                                 $details.=$key_val[0].': '.$input_val[0].', ';
        //                             }  
        //                         }
        //                         elseif(stripos($services->type_name,'passport')!==false)
        //                         {
        //                             if(stripos($key_val[0],'File Number')!==false || stripos($key_val[0],'Date of Birth')!==false)
        //                             {
        //                                 $details.=$key_val[0].': '.$input_val[0].', ';
        //                             }  
        //                         }
        //                         elseif(stripos($services->type_name,'driving_license')!==false)
        //                         {
        //                             if(stripos($key_val[0],'DL Number')!==false)
        //                             {
        //                                 $details.=$key_val[0].': '.$input_val[0].', ';
        //                             }  
        //                         }
        //                         elseif(stripos($services->type_name,'employment')!==false)
        //                         {
        //                             if(stripos($key_val[0],'Company name')!==false)
        //                             {
        //                                 $details.=$input_val[0];
        //                             }  
        //                         }
        //                         elseif(stripos($services->type_name,'educational')!==false)
        //                         {
        //                             if(stripos($key_val[0],'University Name / Board Name')!==false)
        //                             {
        //                                 $details.=$input_val[0];
        //                             }  
        //                         }
        //                         elseif(stripos($services->type_name,'bank_verification')!==false)
        //                         {
        //                             if(stripos($key_val[0],'Account Number')!==false || stripos($key_val[0],'IFSC Code')!==false)
        //                             {
        //                                 $details.=$key_val[0].': '.$input_val[0].', ';
        //                             }  
        //                         }
        //                         elseif(stripos($services->type_name,'criminal')!==false || stripos($services->type_name,'judicial')!==false)
        //                         {
        //                             if(stripos($key_val[0],'Address')!==false || stripos($key_val[0],'Address Type')!==false)
        //                             {
        //                                 $details.=$key_val[0].': '.$input_val[0].', ';
        //                             }  
        //                         }
        //                         elseif(stripos($services->type_name,'reference')!==false)
        //                         {
        //                             if(stripos($key_val[0],'Referee Name')!==false || stripos($key_val[0],'Referee Designation')!==false || stripos($key_val[0],'Referee Company')!==false)
        //                             {
        //                                 $details.=$key_val[0].': '.$input_val[0].', ';
        //                             }
        //                         }
        //                         elseif(stripos($services->type_name,'database')!==false)
        //                         {
        //                             if(stripos($key_val[0],'Address')!==false || stripos($key_val[0],'Type')!==false)
        //                             {
        //                                 $details.=$key_val[0].': '.$input_val[0].', ';
        //                             }  
        //                         }
        //                         elseif(stripos($services->type_name,'telecom')!==false)
        //                         {
        //                             if(stripos($key_val[0],'Telephone Number')!==false || stripos($key_val[0],'Addesss')!==false)
        //                             {
        //                                 $details.=$key_val[0].': '.$input_val[0].', ';
        //                             }  
        //                         }
        //                         elseif(stripos($services->type_name,'covid_19')!==false)
        //                         {
                                    
        //                             $details.=$key_val[0].': '.$input_val[0].', ';
                                      
        //                         }
        //                         elseif(stripos($services->type_name,'covid_19_certificate')!==false)
        //                         {
        //                             if(stripos($key_val[0],'Mobile Number')!==false || stripos($key_val[0],'Reference ID')!==false)
        //                                 $details.=$key_val[0].': '.$input_val[0].', ';
                                      
        //                         }
        //                         elseif(stripos($services->type_name,'e_court')!==false || stripos($services->type_name,'upi')!==false || stripos($services->type_name,'cin')!==false)
        //                         {
                                    
        //                             $details.=$key_val[0].': '.$input_val[0].', ';
                                      
        //                         }
        //                         elseif(stripos($services->type_name,'drug_test_5')!==false)
        //                         {
        //                             if(stripos($key_val[0],'Test Name')!==false)
        //                             {
        //                                 $drug_test_name = Helper::drugTestName($services->id);
        //                                 if(count($drug_test_name)>0)
        //                                 {
        //                                     $arr = $drug_test_name->pluck('test_name')->all();
        //                                     $value = implode(', ',$arr);
        //                                     $details.=$key_val[0].': ('.$value.'), ';
        //                                 }
        //                                 else
        //                                     $details.=$key_val[0].': '.$input_val[0].', ';
        //                             }
        //                             else
        //                                 $details.=$key_val[0].': '.$input_val[0].', ';
        //                         }
        //                         elseif(stripos($services->type_name,'cibil_new')!==false)
        //                         {
        //                             $details.=$key_val[0].': '.$input_val[0].', ';
        //                         }
        //                         else
        //                         {
        //                             if($k==0)
        //                             {
        //                                 $details.=$key_val[0].': '.$input_val[0];
        //                             }
        //                         }
        //                     }
        //                 }

        //                 array_push($data,$details!=''?$details:'N/A',$remarks);
        //             }
        //         }
        //         else
        //         {
        //             for($i=0; $i < $max_verification; $i++)
        //             {
        //                 if($i<$no_of_verification)
        //                 {
        //                     $j=$i+1;

        //                     $report_items=DB::table('report_items')
        //                                     ->where(['candidate_id'=>$user->id,'service_id'=>$service_id,'service_item_number'=>$j])
        //                                     ->orderBy('service_item_number','asc')
        //                                     ->get();

        //                     foreach($report_items as $item)
        //                     {
        //                         $details = '';

        //                         $remarks = 'WIP';

        //                         if($item->is_data_verified==1)
        //                             $remarks = 'Completed';

        //                         $jaf_data = $item->jaf_data;

        //                         if($jaf_data!=NULL)
        //                         {
        //                             $jaf_data_arr = [];

        //                             $jaf_data_arr=json_decode($jaf_data,true);

        //                             foreach($jaf_data_arr as $k => $input)
        //                             {
        //                                 $key_val = array_keys($input); $input_val = array_values($input);

        //                                 if(stripos($services->type_name,'address')!==false)
        //                                 {
        //                                     if(stripos($key_val[0],'Address')!==false || stripos($key_val[0],'Pincode')!==false)
        //                                     {
        //                                         $details.=$key_val[0].': '.$input_val[0].', ';
        //                                     }  
        //                                 }
        //                                 elseif(stripos($services->type_name,'aadhar')!==false)
        //                                 {
        //                                     if(stripos($key_val[0],'Aadhar Number')!==false)
        //                                     {
        //                                         $details.=$key_val[0].': '.$input_val[0].', ';
        //                                     }  
        //                                 }
        //                                 elseif(stripos($services->type_name,'pan')!==false)
        //                                 {
        //                                     if(stripos($key_val[0],'PAN Number')!==false)
        //                                     {
        //                                         $details.=$key_val[0].': '.$input_val[0].', ';
        //                                     }  
        //                                 }
        //                                 elseif(stripos($services->type_name,'voter_id')!==false)
        //                                 {
        //                                     if(stripos($key_val[0],'Voter ID Number')!==false)
        //                                     {
        //                                         $details.=$key_val[0].': '.$input_val[0].', ';
        //                                     }  
        //                                 }
        //                                 elseif(stripos($services->type_name,'rc')!==false)
        //                                 {
        //                                     if(stripos($key_val[0],'RC Number')!==false)
        //                                     {
        //                                         $details.=$key_val[0].': '.$input_val[0].', ';
        //                                     }  
        //                                 }
        //                                 elseif(stripos($services->type_name,'passport')!==false)
        //                                 {
        //                                     if(stripos($key_val[0],'File Number')!==false || stripos($key_val[0],'Date of Birth')!==false)
        //                                     {
        //                                         $details.=$key_val[0].': '.$input_val[0].', ';
        //                                     }  
        //                                 }
        //                                 elseif(stripos($services->type_name,'driving_license')!==false)
        //                                 {
        //                                     if(stripos($key_val[0],'DL Number')!==false)
        //                                     {
        //                                         $details.=$key_val[0].': '.$input_val[0].', ';
        //                                     }  
        //                                 }
        //                                 elseif(stripos($services->type_name,'employment')!==false)
        //                                 {
        //                                     if(stripos($key_val[0],'Company name')!==false)
        //                                     {
        //                                         $details.=$input_val[0];
        //                                     }  
        //                                 }
        //                                 elseif(stripos($services->type_name,'educational')!==false)
        //                                 {
        //                                     if(stripos($key_val[0],'University Name / Board Name')!==false)
        //                                     {
        //                                         $details.=$input_val[0];
        //                                     }  
        //                                 }
        //                                 elseif(stripos($services->type_name,'bank_verification')!==false)
        //                                 {
        //                                     if(stripos($key_val[0],'Account Number')!==false || stripos($key_val[0],'IFSC Code')!==false)
        //                                     {
        //                                         $details.=$key_val[0].': '.$input_val[0].', ';
        //                                     }  
        //                                 }
        //                                 elseif(stripos($services->type_name,'criminal')!==false || stripos($services->type_name,'judicial')!==false)
        //                                 {
        //                                     if(stripos($key_val[0],'Address')!==false || stripos($key_val[0],'Address Type')!==false)
        //                                     {
        //                                         $details.=$key_val[0].': '.$input_val[0].', ';
        //                                     }  
        //                                 }
        //                                 elseif(stripos($services->type_name,'reference')!==false)
        //                                 {
        //                                     if(stripos($key_val[0],'Referee Name')!==false || stripos($key_val[0],'Referee Designation')!==false || stripos($key_val[0],'Referee Company')!==false)
        //                                     {
        //                                         $details.=$key_val[0].': '.$input_val[0].', ';
        //                                     }
        //                                 }
        //                                 elseif(stripos($services->type_name,'database')!==false)
        //                                 {
        //                                     if(stripos($key_val[0],'Address')!==false || stripos($key_val[0],'Type')!==false)
        //                                     {
        //                                         $details.=$key_val[0].': '.$input_val[0].', ';
        //                                     }  
        //                                 }
        //                                 elseif(stripos($services->type_name,'telecom')!==false)
        //                                 {
        //                                     if(stripos($key_val[0],'Telephone Number')!==false || stripos($key_val[0],'Addesss')!==false)
        //                                     {
        //                                         $details.=$key_val[0].': '.$input_val[0].', ';
        //                                     }  
        //                                 }
        //                                 elseif(stripos($services->type_name,'covid_19')!==false)
        //                                 {
                                            
        //                                     $details.=$key_val[0].': '.$input_val[0].', ';
                                            
        //                                 }
        //                                 elseif(stripos($services->type_name,'covid_19_certificate')!==false)
        //                                 {
        //                                     if(stripos($key_val[0],'Mobile Number')!==false || stripos($key_val[0],'Reference ID')!==false)
        //                                         $details.=$key_val[0].': '.$input_val[0].', ';
                                            
        //                                 }
        //                                 elseif(stripos($services->type_name,'e_court')!==false || stripos($services->type_name,'upi')!==false || stripos($services->type_name,'cin')!==false)
        //                                 {
                                            
        //                                     $details.=$key_val[0].': '.$input_val[0].', ';
                                            
        //                                 }
        //                                 elseif(stripos($services->type_name,'drug_test_5')!==false)
        //                                 {
        //                                     if(stripos($key_val[0],'Test Name')!==false)
        //                                     {
        //                                         $drug_test_name = Helper::drugTestName($services->id);
        //                                         if(count($drug_test_name)>0)
        //                                         {
        //                                             $arr = $drug_test_name->pluck('test_name')->all();
        //                                             $value = implode(', ',$arr);
        //                                             $details.=$key_val[0].': ('.$value.'), ';
        //                                         }
        //                                         else
        //                                             $details.=$key_val[0].': '.$input_val[0].', ';
        //                                     }
        //                                     else
        //                                         $details.=$key_val[0].': '.$input_val[0].', ';
        //                                 }
        //                                 elseif(stripos($services->type_name,'cibil_new')!==false)
        //                                 {
        //                                     $details.=$key_val[0].': '.$input_val[0].', ';
        //                                 }
        //                                 else
        //                                 {
        //                                     if($k==0)
        //                                     {
        //                                         $details.=$key_val[0].': '.$input_val[0];
        //                                     }
        //                                 }
        //                             }
        //                         }

        //                         array_push($data,$details!=''?$details:'N/A',$remarks);
        //                     }
        //                 }
        //                 else
        //                 {
        //                     array_push($data,'N/A','N/A');
        //                 }
        //             }
        //         }
        //     }
        //     else
        //     {
        //         for($i=0; $i < $max_verification; $i++)
        //         {
        //             array_push($data,'N/A','N/A');
        //         }
                
        //     }

        // }

        // dd($data);
        return $data;

    }

    public function headings(): array
    {
        $columns = 
            [
                'ARS Number',
                'Data Source',
                'Job Seeker ID',
                'Client Name',
                'Sub Client Name',
                'Candidate Name',
                'Mobile No',
                'Email',	
                'Location',	
                'Process',
                'Case Received Date',
                'Latest Check Insuff Raised Date',	
                'Latest Check Insuff Fulfill Date',
                'Permanent Address Verification-1',
                'Current Address Verification-1',
                'Current Address Verification-2',
                'Digital Address Verification-1',
                'Digital Address Verification-2',
                'Current Employment Verification-1',
                'Previous Employment Verification-1',
                'Previous Employment Verification-2',
                'Previous Employment Verification-3',
                'Employment Verification via UAN-1',
                'Employment Verification via UAN-2',
                'Education Verification W-1',
                'Education Verification W-2',
                'Education Verification Written-1',
                'Education Verification Written-2',
                'India Reputational Risk Database Check-1',
                'India Credit Default Database Check-1',
                'FACIS Level 3 Database Verification-1',
                'India Database Check Level 1-1',
                'India Database Check Level 1-2',
                'India Court Record Database Check-1',
                'India Court Record Database Check-2',
                'OIG Database Check-1',
                'Global Regulatory Compliance and Debarment Database Verification-1',
                'Global Regulatory Compliance and Debarment Database Verification-2',
                'Professional Reference Check-1',
                'Drug Test 5 Panel-1',
                'Drug Test 10 Panel-1',	
                'Drug Test 12 Panel-1',
                'Gap Check-1',
                'National Identity Check-1',
                'Social Media Check-1',
                'SSN Verification-1',
                'CV Validation-1',
                'India Credit History-1',
                'India National Sex Offender-1',
                'National Sex Offender Search-1',
                'Latest Report Severity',
                'Latest Report Sent Date',
                'Final Report Sent Date',
                'Final Report Severity',
                'Latest Interim Report Sent Date',
                'Latest Interim Report Severity',
                'Latest Additional Report Sent Date',
                'Latest Additional Report Severity',
                'Open Insuff Comments',
                'Insuff Comments Open+Close',
                'Go Ahead Date',
                'Reopen_date',
                'Closure Comments (Non-Green Checks)',
                'VS Emp Checks (Non-Green only)',
                'VS Edu Checks (Non-Green only)',
                'Delivery Date',
                'Case Status',
                'case_flex_field1',
                'case_flex_field2',
                'case_flex_field3',
                'case_flex_field4',
                'case_flex_field5',
                'case_flex_field6',
                'case_flex_field7',
                'case_flex_field8',
                'case_flex_field9',
                'case_flex_field10',
                'case_flex_field11',
                'case_flex_field12',
                'case_flex_field13',
                'case_flex_field14',
                'case_flex_field15',
                'case_flex_field16',
                'case_flex_field17',
                'case_flex_field18',
                'case_flex_field19',
                'case_flex_field20',
                'case_flex_field21',
                'case_flex_field22',
                'case_flex_field23',
                'case_flex_field24',
                'case_flex_field25',
                'case_flex_field26',
                'case_flex_field27',
                'case_flex_field28',
                'case_flex_field29',
                'case_flex_field30',
            ];

        return $columns;
        
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) 
            {
                $cellRange = 'A1:ZZ1'; // All headers

                $query = DB::table('users as u')
                        ->DISTINCT('js.candidate_id')
                        ->select('u.*','ub.company_name','j.sla_title as sla_name','ub.department','ub.client_spokeman','j.tat','j.client_tat','j.tat_type','j.days_type','j.price_type','j.package_price','r.is_report_complete','r.status as report_status','r.report_complete_created_at as case_completed_date')
                        ->join('user_businesses as ub','u.business_id','=','ub.business_id')
                        ->join('job_items as j','j.candidate_id','=','u.id')
                        ->join('job_sla_items as js','js.job_item_id','=','j.id')
                        ->join('reports as r','r.candidate_id','=','u.id')
                        ->join('services as s','s.id','=','js.service_id')
                        ->whereIn('s.type_name',['address','employment','educational','database','global_database','judicial','drug_test_5','e_court','reference','identity_verification','criminal'])
                        ->where(['u.id'=>$this->candidate_id]);
    
                $user = $query->orderBy('u.id','desc')->get();  

                $q_count = count($user);

                $event->sheet->getDelegate()->getStyle('A1:CS1')
                                            ->getAlignment()
                                            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                                            ->setVertical(Alignment::VERTICAL_CENTER);

                $event->sheet->getDelegate()->getStyle('A1:CS1')->getFill()
                                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                                        ->getStartColor()
                                        ->setRGB('F2DCDA');

                $event->sheet->getDelegate()->getStyle('A1:CS1')
                                            ->getFont()
                                            ->getColor()
                                            ->setRGB('000000');

                $event->sheet->getDelegate()->getRowDimension('1')->setRowHeight(30);

                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(12);
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(true);

                $event->sheet->getDelegate()->getStyle('AG2:AG'.($q_count + 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $event->sheet->getDelegate()->getStyle('AJ2:AJ'.($q_count + 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $event->sheet->getDelegate()->getStyle('AM2:AM'.($q_count + 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $event->sheet->getDelegate()->getStyle('AP2:AP'.($q_count + 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $event->sheet->getDelegate()->getStyle('AS2:AS'.($q_count + 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

                $event->sheet->getDelegate()->getStyle('AW2:AW'.($q_count + 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $event->sheet->getDelegate()->getStyle('AZ2:AZ'.($q_count + 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $event->sheet->getDelegate()->getStyle('BC2:BC'.($q_count + 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $event->sheet->getDelegate()->getStyle('BF2:BF'.($q_count + 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $event->sheet->getDelegate()->getStyle('BI2:BI'.($q_count + 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

                $event->sheet->getDelegate()->getStyle('BM2:BM'.($q_count + 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $event->sheet->getDelegate()->getStyle('BQ2:BQ'.($q_count + 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $event->sheet->getDelegate()->getStyle('BU2:BU'.($q_count + 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $event->sheet->getDelegate()->getStyle('BY2:BY'.($q_count + 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $event->sheet->getDelegate()->getStyle('CC2:CC'.($q_count + 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

                $event->sheet->getDelegate()->getStyle('CH2:CH'.($q_count + 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $event->sheet->getDelegate()->getStyle('CL2:CL'.($q_count + 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $event->sheet->getDelegate()->getStyle('CP2:CP'.($q_count + 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                $event->sheet->getDelegate()->getStyle('CT2:CT'.($q_count + 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
                
            },
        ];

        
    }

    public function columnFormats(): array
    {
        return [
            
        ];
    }

    public function workingDays($start_date,$tatwDays,$inc_tatwDays)
    {
        // using + weekdays excludes weekends
        $arr=[];

        $tat_new_date = date('d-F-Y', strtotime("{$start_date} +{$tatwDays} weekdays"));

        $inc_tat_new_date = date('d-F-Y', strtotime("{$start_date} +{$inc_tatwDays} weekdays"));

        $arr=['tat_date'=>$tat_new_date,'inc_tat_date'=>$inc_tat_new_date];

        return $arr;
        
    }

    
    public function calenderDays($start_date,$holidays,$tatwDays,$inc_tatwDays)
    {
        // using + weekdays excludes weekends
        // $arr=[];
        $tat_new_date = date('Y-m-d', strtotime("{$start_date} +{$tatwDays} weekdays"));

        $inc_tat_new_date = date('Y-m-d', strtotime("{$start_date} +{$inc_tatwDays} weekdays"));

        foreach($holidays as $holiday)
        {
        
            $holiday_ts = strtotime($holiday->date);

            // if holiday falls between start date and new date, then account for it
            if ($holiday_ts >= strtotime($start_date) && $holiday_ts <= strtotime($tat_new_date)) {

                // check if the holiday falls on a working day
                $h = date('w', $holiday_ts);
                    if ($h != 0 && $h != 6 ) {
                    // holiday falls on a working day, add an extra working day
                    $tat_new_date = date('Y-m-d', strtotime("{$tat_new_date} + 1 weekdays"));
                }
            }

            // if holiday falls between start date and new date, then account for it
            if ($holiday_ts >= strtotime($start_date) && $holiday_ts <= strtotime($inc_tat_new_date)) {

                // check if the holiday falls on a working day
                $h = date('w', $holiday_ts);
                    if ($h != 0 && $h != 6 ) {
                    // holiday falls on a working day, add an extra working day
                    $inc_tat_new_date = date('Y-m-d', strtotime("{$inc_tat_new_date} + 1 weekdays"));
                }
            }
        }

        return array('tat_date'=>$tat_new_date,'inc_tat_date'=>$inc_tat_new_date);
    }
    
    public function round_up ( $value, $precision ) 
    { 
        $pow = pow ( 10, $precision ); 
        return ( ceil ( $pow * $value ) + ceil ( $pow * $value - ceil ( $pow * $value ) ) ) / $pow; 
    }
}