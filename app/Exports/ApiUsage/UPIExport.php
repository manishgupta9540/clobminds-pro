<?php

namespace App\Exports\ApiUsage;

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
use App\Helpers\Helper;

class UPIExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents, WithMapping
{
    use Exportable;

    /**
    * @return \Illuminate\Support\Collection
    */

    protected $business_id;
    protected $from_date;
    protected $to_date;

    function __construct($business_id,$from_date, $to_date) {
        $this->business_id  = $business_id;
        $this->from_date        = $from_date;
        $this->to_date          = $to_date;
    }
    
    public function collection()
    {
        $users=DB::table('users')->where(['id'=>$this->business_id])->first();
        if($users->user_type=='customer')
        {
            $data=DB::table('upi_checks as a')
                    ->select('s.name as service_name','a.name','a.upi_id','a.user_id','a.created_at','a.price')
                    ->join('services as s','s.id','=','a.service_id')
                    ->where(['a.source_type'=>'API','a.user_type'=>'customer','a.business_id'=>$this->business_id])
                    ->orderBy('a.id','desc');
                    if($this->from_date !=""){
                        $data->whereDate('a.created_at','>=',date('Y-m-d',strtotime($this->from_date)));
                    }
                        if($this->to_date !=""){
                        $data->whereDate('a.created_at','<=',date('Y-m-d',strtotime($this->to_date)));
                    }
            $data=$data->paginate(10);
        }
        else if($users->user_type=='client')
        {
            $data=DB::table('upi_checks as a')
                        ->select('s.name as service_name','a.name','a.upi_id','a.user_id','a.created_at','a.price')
                        ->join('services as s','s.id','=','a.service_id')
                        ->where(['a.source_type'=>'API','a.user_type'=>'coc','a.business_id'=>$this->business_id])
                        ->orderBy('a.id','desc');
                        if($this->from_date !=""){
                            $data->whereDate('a.created_at','>=',date('Y-m-d',strtotime($this->from_date)));
                        }
                            if($this->to_date !=""){
                            $data->whereDate('a.created_at','<=',date('Y-m-d',strtotime($this->to_date)));
                        }
                $data=$data->paginate(10);
        }
        return $data;             

    }

    // 
    public function map($data): array
    {
        $new_arr=[$data->upi_id,ucfirst($data->name),Helper::user_name($data->user_id),date('d-F-Y h:i A',strtotime($data->created_at)),'₹ '.$data->price];
        // $data = Arr::flatten(json_decode($jaf->form_data,true));
        return $new_arr;
    }

    public function headings(): array
    {
        return [
            'UPI ID',
            'Name',
            'Used By',
            'Date & Time',
            'Price',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange = 'A1:W1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(12);
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle('A2:W5000')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);;
            },
        ];
    }
}
