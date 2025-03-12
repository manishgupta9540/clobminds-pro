<?php

namespace App\Exports;

use App\User;
use App\Helpers\Helper;
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
use Illuminate\Support\Facades\DB as FacadesDB;

class allChecksExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents,WithMapping
{
    use Exportable;

    /**
    * @return \Illuminate\Support\Collection
    */

 	protected $from_date;
    protected $to_date;
    // protected $customer_id;
    protected $candidate_id;
    protected $service_id;
    // protected $business_id;

    function __construct($from_date, $to_date,$candidate_id, $service_id) {
            $this->from_date        = $from_date;
            $this->to_date          = $to_date;
            // $this->customer_id      = $customer_id;
            $this->candidate_id     = $candidate_id;
            $this->service_id         = $service_id;
            // $this->business_id      = $business_id;
    }
    
    public function collection()
    {
        // dd($this->candidate_id);
        $query = DB::table('users as u')
              ->select('u.*','jf.id as jaf_id','jf.sla_id','jf.status','jf.job_id','jf.candidate_id',DB::raw('group_concat(jf.service_id) as service_id'),'ub.company_name','ub.city_name','r.id as report_id','r.status as report_status')
              ->join('user_businesses as ub','ub.business_id','=','u.business_id')
              ->join('reports as r','r.candidate_id','=','u.id')        
              ->join('jaf_form_data as jf','jf.candidate_id','=','u.id')     
              ->whereIn('u.id',$this->candidate_id)
              ->whereIn('jf.service_id',$this->service_id)
              ->groupBy("u.id");

                // if( $this->candidate_id !="" ){
                //     $query->where('u.id', $this->candidate_id);
                // }
                // if( $this->customer_id !="" ){
                //     $query->where('u.business_id', $this->customer_id);
                // }
                // both date is selected 
                if($this->from_date !="" && $this->to_date !=""){
                    $query->whereDate('u.created_at','>=',date('Y-m-d',strtotime($this->from_date)));
                    $query->whereDate('u.created_at','<=',date('Y-m-d',strtotime($this->to_date)));
                }
                else
                {
                  if($this->from_date !=""){
                    $query->whereDate('u.created_at','=',date('Y-m-d',strtotime($this->from_date)));
                  }
                }

                $query->orderBy('u.id','desc'); 
                $query->orderBy('jf.service_id','asc');

        return $user = $query->get();             

    }

    // 
    public function map($user): array
    {
        
        // $jaf = DB::table('jaf_form_data')->where(['id'=>$user->jaf_id])->first();
        // dd($jaf);
       
        // die;
        // $array1 = json_decode($jaf->form_data, true);

        //dd($array1);
        $new_arr=[$user->company_name,$user->display_id,$user->first_name,$user->last_name,$user->father_name, date('d M Y', strtotime($user->dob)),$user->city_name];

        $key_account_manager = DB::table('key_account_managers as k')
                                ->select('u.*')
                                ->join('users as u','u.id','=','k.user_id')
                                ->where(['k.business_id'=>$user->business_id])
                                ->get();
        // Key Manager Name
        if(count($key_account_manager)>0)
        {
            $user_name = '';
            $c=0;
            $count = count($key_account_manager);
            foreach($key_account_manager as $key => $kam)
            {
                if(++$c==$count)
                    $user_name = $user_name.' '.$kam->name;
                else
                    $user_name = $user_name.' '.$kam->name.',';
            }

            $new_arr[]=$user_name;
        }
        else
        {
            $new_arr[]=NULL;
        }

        //Report Status
        if(stripos($user->report_status,'incomplete')!==false)
        {
            $new_arr[] = 'Pending';
        }
        else
        {
            $new_arr[] = ucwords($user->report_status);
        }

        $i=1;
        foreach($this->service_id as $service_id)
        {
            $no_of_verification=1;
            $max_verification=1;
            $max_verification=DB::table('job_sla_items as js')
                            ->select('js.number_of_verifications')
                            ->join('job_items as j','j.id','=','js.job_item_id')
                            ->where('j.jaf_status','filled')
                            ->where('js.service_id',$service_id)
                            ->whereIn('js.candidate_id',$this->candidate_id)
                            ->max('js.number_of_verifications');

            $jaf_form=DB::table('jaf_form_data')
            ->where(['candidate_id'=>$user->id,'service_id'=>$service_id])
            ->orderBy('check_item_number','asc')
            ->get();

            $no_of_verification=count($jaf_form);


            $service_items = DB::table('service_form_inputs')
                        ->where(['service_id'=>$service_id,'status'=>1])
                        ->whereNull('reference_type')
                        ->whereNotIn('label_name',['First Name','Last Name','Father Name','Date of Birth','Mode of Verification','Remarks'])
                        ->get();

            $services=DB::table('services')->where('id',$service_id)->first();
            
            if(stripos($services->verification_type,'Manual')!==false)
            {
                if(count($jaf_form)>0)
                {
                    if(count($jaf_form) == $max_verification)
                    {
                        foreach($jaf_form as $jaf)
                        {
                            $form_data_arr=json_decode($jaf->form_data,true);

                            foreach($form_data_arr as $key => $input)
                            {
                                $key_val = array_keys($input); $input_val = array_values($input);
                                if(!(stripos($key_val[0],'First Name')!==false || stripos($key_val[0],'Last Name')!==false || stripos($key_val[0],'Father Name')!==false || stripos($key_val[0],'Date of Birth')!==false))
                                {

                                    if(stripos($services->type_name,'drug_test_5')!==false || stripos($services->type_name,'drug_test_10')!==false)
                                    {
                                        if(stripos($key_val[0],'Test Name')!==false)
                                        {
                                            $drug_test_name = Helper::drugTestName($services->id);
                                            if(count($drug_test_name)>0)
                                            {
                                                $arr = $drug_test_name->pluck('test_name')->all();
                                                $new_arr[] = implode(', ',$arr);
                                            }
                                            else
                                            {
                                                $new_arr[]=$input_val[0];
                                            }
                                        }
                                        else
                                        {
                                            $new_arr[]=$input_val[0];
                                        }
                                    }
                                    else
                                    {
                                        if(!(stripos($key_val[0],'First Name')!==false || stripos($key_val[0],'Last Name')!==false || stripos($key_val[0],'Father Name')!==false || stripos($key_val[0],'Date of Birth')!==false))
                                        {
                                            $new_arr[]=$input_val[0];
                                        }
                                    }
                                }

                            }
                        }
                        
                    }
                    else
                    {
                        for($i=0; $i < $max_verification; $i++)
                        {
                            
                            if($i<$no_of_verification)
                            {
                                $j=$i+1;

                                $jaf_form=DB::table('jaf_form_data')
                                ->where(['candidate_id'=>$user->id,'service_id'=>$service_id,'check_item_number'=>$j])
                                ->orderBy('check_item_number','asc')
                                ->get();

                                foreach($jaf_form as $jaf)
                                {
                                    $form_data_arr=[];
                                    $form_data_arr=json_decode($jaf->form_data,true);
                                    if ($form_data_arr) {
                                        # code...
                                    
                                        foreach($form_data_arr as $key => $input)
                                        {
                                            $key_val = array_keys($input); $input_val = array_values($input);

                                            if(!(stripos($key_val[0],'First Name')!==false || stripos($key_val[0],'Last Name')!==false || stripos($key_val[0],'Father Name')!==false || stripos($key_val[0],'Date of Birth')!==false))
                                            {

                                                if(stripos($services->type_name,'drug_test_5')!==false || stripos($services->type_name,'drug_test_10')!==false)
                                                {
                                                    if(stripos($key_val[0],'Test Name')!==false)
                                                    {
                                                        $drug_test_name = Helper::drugTestName($services->id);
                                                        if(count($drug_test_name)>0)
                                                        {
                                                            $arr = $drug_test_name->pluck('test_name')->all();
                                                            $new_arr[] = implode(', ',$arr);
                                                        }
                                                        else
                                                        {
                                                            $new_arr[]=$input_val[0];
                                                        }
                                                    }
                                                    else
                                                    {
                                                        $new_arr[]=$input_val[0];
                                                    }
                                                }
                                                else
                                                {
                                                    if(!(stripos($key_val[0],'First Name')!==false || stripos($key_val[0],'Last Name')!==false || stripos($key_val[0],'Father Name')!==false || stripos($key_val[0],'Date of Birth')!==false))
                                                    {
                                                        $new_arr[]=$input_val[0];
                                                    }
                                                }
                                            }

                                        }
                                    }
                                }
                            }
                            else
                            {
                                foreach($service_items as $item)
                                {
                                    $new_arr[]=NULL;
                                }
                            }
                        }
                    }
                }
                else
                {
                    for($i=0; $i < $max_verification; $i++)
                    {
                        foreach($service_items as $item)
                        {
                            $new_arr[]=NULL;
                        }
                    }
                }
            }
            else
            {
                if(count($jaf_form)>0)
                {
                    foreach($jaf_form as $jaf)
                    {
                        $form_data_arr=json_decode($jaf->form_data,true);

                        if($service_id==2 || $service_id==3 || $service_id==4 || $service_id==7)
                        {
                            if ($form_data_arr) {
                                foreach($form_data_arr as $key => $input)
                                {
                                    $key_val = array_keys($input); $input_val = array_values($input);

                                    if(stripos($key_val[0],'Aadhar Number')!==false || stripos($key_val[0],'PAN Number')!==false || stripos($key_val[0],'Voter ID Number')!==false || stripos($key_val[0],'RC Number')!==false)
                                    {
                                        $new_arr[]=$input_val[0];
                                    }
                                }
                            }
                        }
                        else
                        {
                            foreach($form_data_arr as $key => $input)
                            {
                                $key_val = array_keys($input); $input_val = array_values($input);

                                if(!(stripos($key_val[0],'First Name')!==false || stripos($key_val[0],'Last Name')!==false || stripos($key_val[0],'Father Name')!==false || stripos($key_val[0],'Date of Birth')!==false))
                                {
                                    $new_arr[]=$input_val[0];
                                }
                            }
                        }
                    }
                }
                else
                {
                    for($i=0; $i < $max_verification; $i++)
                    {
                        if($service_id==2 || $service_id==3 || $service_id==4 || $service_id==7)
                        {
                            foreach($service_items as $item)
                            {
                                if(stripos($item->label_name,'Aadhar Number')!==false || stripos($item->label_name,'PAN Number')!==false || stripos($item->label_name,'Voter ID Number')!==false || stripos($item->label_name,'RC Number')!==false)
                                    $new_arr[]=NULL;
                            }
                        }
                        else
                        {
                            foreach($service_items as $item)
                            {
                                $new_arr[]=NULL;
                            }
                        }
                        
                    }
                }
            }
            
        }

        $new_arr[]='+'.$user->phone_code.'-'.$user->phone;
         
        $new_arr[]=$user->email;
        
        return $new_arr;
    }

    public function headings(): array
    {

        $columns = ['Client Name','Reference ID','First Name','Last Name','Father Name','Date of Birth','Client Location','Case Manager','Overall Case Status'];
        // $items = DB::table('service_form_inputs')->where(['service_id'=>$this->check_id])->get();
        // $i=1;
        // foreach($items as $item){
            
        //     $columns[] = $item->label_name;
        // }
        // return $columns;

        foreach($this->service_id as $service_id)
        {
            $job_sla_items=DB::table('job_sla_items as js')
                            ->select('js.*')
                            ->join('job_items as j','j.id','=','js.job_item_id')
                            ->where('j.jaf_status','filled')
                            ->where('js.service_id',$service_id)
                            ->whereIn('js.candidate_id',$this->candidate_id)
                            ->get();
            $no_of_verification=[];
            if(count($job_sla_items)>0)
            {
                $max=1;
                // $j=1;
                foreach($job_sla_items as $item) 
                {
                    $no_of_verification[]=$item->number_of_verifications;
                }

                $max=max($no_of_verification);

                // dd($max);

                // dd($items);

                for($i = 0; $i < $max; $i++)
                {
                    // $i=1;

                    $items = DB::table('service_form_inputs as si')
                                ->select('si.*','s.name as service_name','s.type_name')
                                ->join('services as s','s.id','=','si.service_id')
                                ->whereNull('si.reference_type')
                                ->where(['si.service_id'=>$service_id,'si.status'=>1])
                                ->whereNotIn('si.label_name',['First Name','Last Name','Father Name','Date of Birth','Mode of Verification','Remarks'])
                                ->get();
                    
                    if($service_id==2)
                    {
                        $columns[] = 'Aadhar Number';
                    }
                    else if($service_id==3)
                    {
                        $columns[] = 'PAN Number';
                    }
                    else if($service_id==4)
                    {
                        $columns[] = 'Voter ID Number';
                    }
                    else if($service_id==7)
                    {
                        $columns[] = 'RC Number';
                    }
                    else
                    {
                        
                        foreach($items as $key => $item){

                            $service_input=DB::table('service_form_inputs as si')
                                            ->select('si.*','s.name as service_name','s.type_name')
                                            ->join('services as s','s.id','=','si.service_id')
                                            ->where('si.id',$item->id)
                                            ->first();
                                            
                            if($item->service_id==1)
                            {
                                if($service_input->label_name=='Address')
                                {
                                    $j=$i+1;
                                    $columns[]=$service_input->service_name.' (Address - '.$j.')';
                                }
                                else
                                {
                                    $columns[]=$item->label_name;
                                }
                            }
                            else if($item->service_id==10)
                            {
                                if(stripos($service_input->label_name,'Company name')!==false)
                                {
                                    $k=$i+1;
                                    $columns[]=$service_input->service_name.' (Company name - '.$k.')';
                                }
                                else
                                {
                                    $columns[]=$item->label_name;
                                }
                            }
                            else if($item->service_id==11)
                            {
                                if(stripos($service_input->label_name,'University Name / Board Name')!==false)
                                {
                                    $l=$i+1;
                                    $columns[]=$service_input->service_name.' (University Name / Board Name - '.$l.')';
                                }
                                else
                                {
                                    $columns[]=$item->label_name;
                                }
                            }
                            else if($item->service_id==15)
                            {
                                if(stripos($service_input->label_name,'Address')!==false)
                                {
                                    $m = $i+1;
                                    $columns[]=$service_input->service_name.' ('.'Address - '.$m.')';
                                }
                                else
                                {
                                    $columns[]=$item->label_name;
                                }
                            }
                            else if($item->service_id==16)
                            {
                                if(stripos($service_input->label_name,'Address')!==false)
                                {
                                    $n = $i+1;
                                    $columns[]=$service_input->service_name.' ('.'Address - '.$n.')';
                                }
                                else
                                {
                                    $columns[]=$item->label_name;
                                }
                            }
                            else if($item->service_id==17)
                            {
                                if(stripos($service_input->label_name,'Reference Type (Personal / Professional)')!==false)
                                {
                                    $o=$i+1;
                                    $columns[]=$service_input->service_name.' (Reference Type (Personal / Professional) - '.$o.')';
                                }
                                else
                                {
                                    $columns[]=$item->label_name;
                                }
                            }
                            else if($item->service_id==21)
                            {
                                if(stripos($service_input->label_name,'Antigen')!==false)
                                {
                                    $p = $i+1;
                                    $columns[]=$service_input->service_name.' ('.'Antigen - '.$p.')';
                                }
                                else
                                {
                                    $columns[]=$item->label_name;
                                }
                            }
                            else if(stripos($item->type_name,'drug_test_5')!==false)
                            {
                                $q = $i + 1;

                                if($key == 0)
                                    $columns[]='Drug Test - 5 ('.$item->label_name.' - '.$q.')';
                                else
                                    $columns[]=$item->label_name; 
                            }
                            else if(stripos($item->type_name,'cibil_new')!==false)
                            {
                                $r = $i + 1;

                                if($key == 0)
                                    $columns[]='CIBIL ('.$item->label_name.' - '.$r.')';
                                else
                                    $columns[]=$item->label_name; 
                            }
                            else if(stripos($item->type_name,'drug_test_10')!==false)
                            {
                                $r = $i + 1;

                                if($key == 0)
                                    $columns[]='Drug Test - 10 ('.$item->label_name.' - '.$r.')';
                                else
                                    $columns[]=$item->label_name; 
                            }
                            else
                            {
                                $z = $i + 1;

                                if($key == 0)
                                    $columns[]=$service_input->service_name.' ('.$item->label_name.' - '.$z.')';
                                else
                                    $columns[]=$item->label_name;
                            }
                        }
                        
                        
                    }
                }

            }

        }

        $columns[]='Candidate Mobile Number';
        $columns[]='Candidate Email ID';

        return $columns;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange = 'A1:ZZ1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(12);
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(true);
            },
        ];
    }
}
