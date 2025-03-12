<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PDF;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin\AddressVerification;
use Spatie\Browsershot\Browsershot;
use DOMDocument;

class PDFController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        ini_set('max_execution_time', '0');
        ini_set("pcre.backtrack_limit", "80000000");
        
    }

    /**
     * Export PDF file - SLA
     *
     * @return \Illuminate\Http\Response
     */
    public function PDFgenerate($id)
    {
        
        $sla_data = DB::table('customer_sla')->where(['id'=>$id])->first();
        
        $sla_service_items = DB::table('customer_sla_items as cs')
                                ->select('cs.id','cs.service_id')
                                ->where(['cs.sla_id'=>$id])
                                ->get();

        $pdf = PDF::loadView('admin.accounts.sla.pdf-jaf', compact('sla_service_items'));
  
        return $pdf->download('jaf.pdf');
    }
 
    /**
     * Export PDF file - Aadhar
     *
     * @return \Illuminate\Http\Response
     */
    public function aadharExportReport($id)
    {
        
        $data = DB::table('aadhar_check_masters')->where(['id'=>$id])->first();
        
        $pdf = PDF::loadView('admin.verifications.pdf.aadhar', compact('data') );

        // $pdf->setEncryption('1111');

        // $pdf->SetProtection(array(), '1111', '0000');
  
        return $pdf->download('aadhar-1.pdf');
    }

    /**
     * Export PDF file - Aadhar
     *
     * @return \Illuminate\Http\Response
     */
    public function advanceAadharExportReport($id)
    {
        
        $data = DB::table('aadhar_check_v2s')->where(['id'=>$id])->first();
        
        $pdf = PDF::loadView('admin.verifications.pdf.v2_aadhar_report', compact('data') );
  
        return $pdf->download('advanceAadhar-1.pdf');
    }


    /**
     * Export PDF file - PAN
     *
     * @return \Illuminate\Http\Response
     */
    public function panExportReport($id)
    {
        
        $data = DB::table('pan_check_masters')->where(['id'=>$id])->first();
        
        $pdf = PDF::loadView('admin.verifications.pdf.pan', compact('data') );
  
        return $pdf->download('pan-1.pdf');
    }

    /**
     * Export PDF file - Voter ID
     *
     * @return \Illuminate\Http\Response
     */
    public function voterIDExportReport($id)
    {
        
        $data = DB::table('voter_id_check_masters')->where(['id'=>$id])->first();
        
        $pdf = PDF::loadView('admin.verifications.pdf.voter-id', compact('data') );
  
        return $pdf->download('Clobminds  -voter-1.pdf');
    }

    /**
     * Export PDF file - RC
     *
     * @return \Illuminate\Http\Response
     */
    public function rcExportReport($id)
    {
        
        $data = DB::table('rc_check_masters')->where(['id'=>$id])->first();
        
        $pdf = PDF::loadView('admin.verifications.pdf.rc', compact('data') );
  
        return $pdf->download('Clobminds  -RC-1.pdf');
    }

    /**
     * Export PDF file - DL
     *
     * @return \Illuminate\Http\Response
     */
    public function dlExportReport($id)
    {
        
        $data = DB::table('dl_check_masters')->where(['id'=>$id])->first();

        $pdf = PDF::loadView('admin.verifications.pdf.dl', compact('data') );
  
        return $pdf->download('Clobminds  -dl.pdf');
    }

    /**
     * Export PDF file - Passport
     *
     * @return \Illuminate\Http\Response
     */
    public function passportExportReport($id)
    {
        
        $data = DB::table('passport_check_masters')->where(['id'=>$id])->first();
        
        $pdf = PDF::loadView('admin.verifications.pdf.passport', compact('data') );
  
        return $pdf->download('Clobminds  -passport.pdf');
    }

    /**
     * Export PDF file - GSTIN
     *
     * @return \Illuminate\Http\Response
     */
    public function gstinExportReport($id)
    {
        
        $data = DB::table('gst_check_masters')->where(['id'=>$id])->first();
        
        $pdf = PDF::loadView('admin.verifications.pdf.gstin', compact('data') );
  
        return $pdf->download('Clobminds  -gst.pdf');
    }

    /**
     * Export PDF file - Bank
     *
     * @return \Illuminate\Http\Response
     */
    public function bankExportReport($id)
    {
        
        $data = DB::table('bank_account_check_masters')->where(['id'=>$id])->first();
        
        $pdf = PDF::loadView('admin.verifications.pdf.bank-verification', compact('data') );
  
        return $pdf->download('Clobminds  -bank-account-verification.pdf');
    }

    /**
     * Export PDF full Report
     *
     * @return \Illuminate\Http\Response
     */
    public function exportFullReport(Request $request)
    {
        
        $report_id = $request->segment(4); 
      
        $report_type = $request->segment(5);
        $business_id = Auth::user()->business_id;
        $user_id = Auth::user()->id;
        $pdf =new  PDF;
        // echo $report_id; die('tested');
        $data = [];
        //get report items
        $report_id = base64_decode($report_id);
        $footer_list =DB::table('report_config')->where(['business_id'=> $business_id])->first();
    
        $report_items = DB::table('report_items as ri')
        ->select('ri.*','s.name as service_name','s.id as service_id','s.type_name')  
        ->join('services as s','s.id','=','ri.service_id')
        ->where(['ri.report_id'=>$report_id,'is_report_output'=>'1']) 
        ->orderBy('s.sort_number','asc')
        ->orderBy('ri.service_item_order','asc')
        ->get(); 

        $path = public_path().'/uploads/report-data/'.$user_id.'/';

        if (!File::exists($path)) {
            File::makeDirectory($path, $mode = 0777, true, true);
        }

        

        if (File::exists($path)) 
        {
            File::cleanDirectory($path); 
        }

        // get candidate_id
        $report_data = DB::table('reports')->select('candidate_id','verifier_name','verifier_email','verifier_designation','complete_updated_at','generated_at','created_at','is_manual_mark','insuff_raised_date','insuff_cleared_date','initiated_date')->where(['id'=>$report_id])->first(); 
          

        $candidate =  DB::table('users as u')
                       ->select('u.id','u.created_at as initiated_date','u.display_id','u.business_id','u.case_received_date','r.complete_updated_at','u.client_emp_code','u.entity_code','u.first_name','u.last_name','u.name','u.email','u.phone','r.id as report_id','r.created_at','r.approval_status_id','r.sla_id','cs.title as sla_name','u.gender','u.dob','r.created_by','u.parent_id','r.revised_date','u.name','r.is_manual_mark','r.status as report_status','r.is_report_complete','r.report_complete_created_at')  
                       ->leftjoin('reports as r','r.candidate_id','=','u.id')
                       ->join('customer_sla as cs','cs.id','=','r.sla_id')
                       ->where(['r.id'=>$report_id]) 
                       ->first(); 


        $jaf = DB::table('jaf_form_data')->select('form_data_all','created_at')->where(['candidate_id'=>$report_data->candidate_id])->first(); 

        $template_type = DB::table('report_add_page_statuses')->select('status')->where(['coc_id' => $candidate->business_id,'template_type'=>'3'])->first();
       
        $file_name = "Clobminds -Report-".date('d-m-Y').'-'.$report_data->candidate_id.".pdf";

        // Check for Report File Renaming

        $customer = DB::table('user_businesses')
                                ->where(['business_id'=>$candidate->business_id,'is_report_file_config'=>1])
                                ->whereNotNull('report_file_config_details')
                                ->first();
       
        if($customer!=NULL)
        {
            $file_detail = $customer->report_file_config_details;

            $file_detail_arr = json_decode($file_detail,true);

            if($file_detail_arr!=NULL && count($file_detail_arr)>0)
            {
                $file_name = '';

                asort($file_detail_arr);

                $i=0;

                $count = count($file_detail_arr);

                foreach($file_detail_arr as $key => $item)
                {
                    if(stripos($key,'reference_no')!==false)
                    {
                        $file_name.=$candidate->display_id;
                    }
                    else if(stripos($key,'emp_code')!==false)
                    {
                        if($candidate->client_emp_code!='' && $candidate->client_emp_code!=null)
                            $file_name.=$candidate->client_emp_code;
                    }
                    else if(stripos($key,'candidate_name')!==false)
                    {
                        $file_name.=$candidate->name;
                    }
                    else if(stripos($key,'status')!==false)
                    {
                        $status = '';

                        if($report_type!=null && $report_type!='')
                        {
                            if(stripos($report_type,'Interim')!==false)
                            {
                                $status = 'Interim Report';
                            }
                            if(stripos($report_type,'Supplementary')!==false)
                            {
                                $status = 'Supplementary Report';
                            }
                            else if(stripos($report_type,'Final')!==false)
                            {
                                $status = 'Final Report';
                            }
                        }
                        else
                        {
                            if(stripos($candidate->report_status,'interim')!==false)
                            {
                                $status = 'Interim Report';
                            }
                            else if(stripos($candidate->report_status,'completed')!==false)
                            {
                                $status = 'Final Report';
                            }
                        }

                        $file_name.=$status;
                    }
                    else if(stripos($key,'date')!==false)
                    {
                        $file_name.=date('d-F-Y');
                    }

                    if(++$i!=$count)
                    {
                        $file_name.=' - ';
                    }
                }

                $file_name.='.pdf';
            }
        }

        $file_name = str_replace(' ','',$file_name);

        $temp_file_name = time().'.pdf';
        
        if($template_type){
            if($template_type->status=='enable')
            {
                // dd($template_type);
                // return view('admin.candidates.pdf.report-template3', compact('data','report_items','data','jaf','candidate','report_data'));
                $pdf = PDF::loadView('admin.candidates.pdf.new-report-template3', compact('data','footer_list','report_items','jaf','candidate','report_data'),[],[
                    'title' => 'Report',
                    'margin_top' => 20,
                    'margin-header'=>20,
                    'margin_bottom' =>25,
                    'margin_footer'=>5,
                    
                ])->save($path.$temp_file_name);

                // Check the File Size is greater than 3 MB

                $file_size = number_format(File::size($path.$temp_file_name) / 1048576, 2);

                if($file_size > 3)
                {
                    $output = shell_exec('gs -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dNOPAUSE -dPDFSETTINGS=/ebook -dQUIET -dBATCH -sOutputFile='.$path.$file_name.' '.$path.$temp_file_name.'');

                    // If Command Not Execute
                    if(!File::exists($path.$file_name))
                    {
                        $pdf = PDF::loadView('admin.candidates.pdf.new-report-template3', compact('report_items','footer_list','data','jaf','candidate','report_data'),[],[
                            'title' => 'Report',
                            'margin_top' => 20,
                            'margin-header'=>20,
                            'margin_bottom' =>25,
                            'margin_footer'=>5,
                            
                        ])->save($path.$file_name);
                    }
                }
                else
                {
                    $pdf = PDF::loadView('admin.candidates.pdf.new-report-template3', compact('data','footer_list','report_items','jaf','candidate','report_data'),[],[
                        'title' => 'Report',
                        'margin_top' => 20,
                        'margin-header'=>20,
                        'margin_bottom' =>25,
                        'margin_footer'=>5,
                        
                    ])->save($path.$file_name);
                }


            }
            else{
                $pdf = PDF::loadView('admin.candidates.pdf.new-report', compact('report_items','footer_list','data','jaf','candidate','report_data'),[],[
                    'title' => 'Report',
                    'margin_top' => 20,
                    'margin-header'=>20,
                    'margin_bottom' =>25,
                    'margin_footer'=>5,
                    
                ])->save($path.$temp_file_name);

                 // Check the File Size is greater than 3 MB

                 $file_size = number_format(File::size($path.$temp_file_name) / 1048576, 2);

                if($file_size > 3)
                {
                    $output = shell_exec('gs -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dNOPAUSE -dPDFSETTINGS=/ebook -dQUIET -dBATCH -sOutputFile='.$path.$file_name.' '.$path.$temp_file_name.'');

                    // If Command Not Execute
                    if(!File::exists($path.$file_name))
                    {
                        $pdf = PDF::loadView('admin.candidates.pdf.new-report', compact('report_items','footer_list','data','jaf','candidate','report_data'),[],[
                            'title' => 'Report',
                            'margin_top' => 20,
                            'margin-header'=>20,
                            'margin_bottom' =>25,
                            'margin_footer'=>5,
                            
                        ])->save($path.$file_name);
                    }

                }
                else
                {
                    $pdf = PDF::loadView('admin.candidates.pdf.new-report', compact('report_items','footer_list','data','jaf','candidate','report_data'),[],[
                        'title' => 'Report',
                        'margin_top' => 20,
                        'margin-header'=>20,
                        'margin_bottom' =>25,
                        'margin_footer'=>5,
                        
                    ])->save($path.$file_name);
                }
            }
        }  
        else {
            
            $pdf = PDF::loadView('admin.candidates.pdf.new-report', compact('report_items','footer_list','data','jaf','candidate','report_data'),[],[
                'title' => 'Report',
                'margin_top' => 20,
                'margin-header'=>20,
                'margin_bottom' =>25,
                'margin_footer'=>5,
                
            ])->save($path.$temp_file_name);

            // $pdf = PDF::loadView('admin.candidates.pdf.new-report', compact('report_items','footer_list','data','jaf','candidate','report_data'),[],[
            //     'title' => 'Report',
            //     'margin_top' => 20,
            //     'margin-header'=>20,
            //     'margin_bottom' =>25,
            //     'margin_footer'=>5,
                
            // ]);

            // return $pdf->stream($file_name);

            // dd(1);

            $file_size = number_format(File::size($path.$temp_file_name) / 1048576, 2);

            // dd($file_size);

            if($file_size > 3)
            {
                $output = shell_exec('gs -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dNOPAUSE -dPDFSETTINGS=/ebook -dQUIET -dBATCH -sOutputFile='.$path.$file_name.' '.$path.$temp_file_name.'');

                // If Command Not Execute
                if(!File::exists($path.$file_name))
                {
                    $pdf = PDF::loadView('admin.candidates.pdf.new-report', compact('report_items','footer_list','data','jaf','candidate','report_data'),[],[
                        'title' => 'Report',
                        'margin_top' => 20,
                        'margin-header'=>20,
                        'margin_bottom' =>25,
                        'margin_footer'=>5,
                        
                    ])->save($path.$file_name);
                }
            }
            else
            {
                $pdf = PDF::loadView('admin.candidates.pdf.new-report', compact('report_items','footer_list','data','jaf','candidate','report_data'),[],[
                    'title' => 'Report',
                    'margin_top' => 20,
                    'margin-header'=>20,
                    'margin_bottom' =>25,
                    'margin_footer'=>5,
                    
                ])->save($path.$file_name);
            }
        }

        return response()->download($path.$file_name);

        //return $pdf->download($file_name);
 
    }

    public function mpdf_test() 
	{
		$data = [
			'foo' => 'bar'
        ];
      
		$pdf = PDF::loadView('admin.candidates.pdf.document', $data,[],[
            'title' => 'Another Title',
            'margin_top' => 20
          ]);
		return $pdf->stream('document.pdf');
	}

    /**
     * Export PDF file - Telecom
     *
     * @return \Illuminate\Http\Response
     */
    public function telecomExportReport($id)
    {
        
        $data = DB::table('telecom_check_master')->where(['id'=>$id])->first();
        
        $pdf = PDF::loadView('admin.verifications.pdf.telecom', compact('data') );
  
        return $pdf->download('telecom-1.pdf');
    }

    /**
     * Export PDF file - Ecourt
     *
     * @return \Illuminate\Http\Response
     */
    public function ecourtExportReport($id)
    {
        
        $master_data = DB::table('e_court_check_masters')->where(['id'=>$id])->latest()->first();
        
        $pdf = PDF::loadView('admin.verifications.pdf.e-court', compact('master_data') );
  
        return $pdf->download('Clobminds  -e-court-verification.pdf');
    }

     /**
     * Export PDF file - UPI
     *
     * @return \Illuminate\Http\Response
     */
    public function upiExportReport($id)
    {
        
        $master_data = DB::table('upi_check_masters')->where(['id'=>$id])->latest()->first();
        
        $pdf = PDF::loadView('admin.verifications.pdf.upi', compact('master_data') );
  
        return $pdf->download('Clobminds  -upi-verification.pdf');
    }

   

    /**
     * Export PDF file - CIN
     *
     * @return \Illuminate\Http\Response
     */
    public function cinExportReport($id)
    {
        
        $master_data = DB::table('cin_check_masters')->where(['id'=>$id])->latest()->first();
        //dd($master_data);
        $pdf = PDF::loadView('admin.verifications.pdf.cin', compact('master_data'));
  
        return $pdf->download('Clobminds  -cin-verification.pdf');
    }


   
    /**
     * Export PDF file - Uan
     *
     * @return \Illuminate\Http\Response
     */
    public function uanExportReport($id)
    {
        
        $master_data = DB::table('uan_check_masters')->where(['id'=>$id])->latest()->first();
    
        $pdf = PDF::loadView('admin.verifications.pdf.uan', compact('master_data'));
  
        //return view('admin.verifications.pdf.uan', compact('master_data'));
        //return $pdf->stream('Clobminds  -uan-verification.pdf');
        return $pdf->download('Clobminds-uan-verification.pdf');
    }

    /**
     * Export PDF file - Cibil
     *
     * @return \Illuminate\Http\Response
     */
    public function cibilExportReport($id)
    {
       
        $master_data = DB::table('cibil_check_masters')->where(['id'=>$id])->latest()->first();
       
        $pdf = PDF::loadView('admin.verifications.pdf.cibil', compact('master_data'));
        
        return $pdf->download('Clobminds-cibil-verification.pdf');
    }

    public function adharToUanExportReport($id)
    {
       
        $master_data = DB::table('adhar_to_uan_check_masters')->where(['id'=>$id])->latest()->first();
       
        $pdf = PDF::loadView('admin.verifications.pdf.adhartouan', compact('master_data'));
        
        return $pdf->download('Clobminds-adhatToUan-verification.pdf');
    }

    public function epfoExportReport($id)
    {
        $master_data = DB::table('epfo_check_masters')->where('id',$id)->first();

        $pdf = PDF::loadView('admin.verifications.pdf.epfo', compact('master_data'));
        
        return $pdf->download('Clobminds-epfo-verification.pdf');

        //return $pdf->stream('Clobminds-epfo-verification.pdf');
    }

    public function digitalEmploymentExportReport($id)
    {
        $master_data = DB::table('digital_employment_check_masters')->where('id',$id)->first();

        $pdf = PDF::loadView('admin.verifications.pdf.digital_employment',compact('master_data'));
        
        return $pdf->download('Clobminds-digi-emp-verification.pdf');

        //return $pdf->stream('Clobminds-digi-emp-verification.pdf');
    }

    /**
     * Export PDF full Report
     *
     * @return \Illuminate\Http\Response
     */
    public function previewReport(Request $request)
    {
       
        $user_id=Auth::user()->id;
        $business_id = Auth::user()->business_id;
        $report = $request->id; 
        // dd($report_id);
        $pdf =new PDF;
        // echo $report_id; die('tested');
        $data = [];
        //get report items
        $report_id = base64_decode($report);
        $report_data='';
        // dd($report_id);
        $footer_list =DB::table('report_config')->where(['business_id'=> $business_id])->first();
        
        $report_items = DB::table('report_items as ri')
        ->select('ri.*','s.name as service_name','s.id as service_id','s.type_name')  
        ->join('services as s','s.id','=','ri.service_id')
        ->where(['ri.report_id'=>$report_id,'is_report_output'=>'1']) 
        ->orderBy('s.sort_number','asc')
        ->orderBy('ri.service_item_order','asc')
        ->get(); 
        //dd($report_items);
        // get candidate_id
        $report_data = DB::table('reports')->select('candidate_id','verifier_name','verifier_email','verifier_designation','generated_at','complete_updated_at','created_at','is_manual_mark','insuff_raised_date','insuff_cleared_date','initiated_date')->where(['id'=>$report_id])->first(); 
        //dd($report_data);
        $candidate =  DB::table('users as u')
                       ->select('u.id','u.display_id','u.role','u.created_at as initiated_date','u.business_id','u.parent_id','u.client_emp_code','u.gender','u.entity_code','u.first_name','u.last_name','u.name','u.email','u.dob','u.phone','r.id as report_id','r.created_at','r.approval_status_id','r.sla_id','cs.title as sla_name','r.revised_date','u.name','u.case_received_date','r.is_manual_mark','r.status as report_status','r.is_report_complete','r.report_complete_created_at','r.complete_updated_at')  
                       ->leftjoin('reports as r','r.candidate_id','=','u.id')
                       ->join('customer_sla as cs','cs.id','=','r.sla_id')
                       ->where(['r.id'=>$report_id]) 
                       ->first();
        $verifier = DB::table('reports')->where(['candidate_id'=>$candidate->id])->first(); 
        $jaf = DB::table('jaf_form_data')->select('form_data_all','created_at')->where(['candidate_id'=>$report_data->candidate_id])->first(); 

        // $path=public_path().'/uploads/report-data/';
        // dd($jaf);
        $path = public_path().'/uploads/report-data/'.$user_id.'/';

        if (!File::exists($path)) {
            File::makeDirectory($path, $mode = 0777, true, true);
        }

        if (File::exists($path)) 
        {
            File::cleanDirectory($path);
        }

        $file_name = "Clobminds_BGV-Report-".date('d-m-Y').'-'.$report_data->candidate_id.".pdf";

        // Check for Report File Renaming

        $customer = DB::table('user_businesses')
                                ->where(['business_id'=>$candidate->business_id,'is_report_file_config'=>1])
                                ->whereNotNull('report_file_config_details')
                                ->first();

        if($customer!=NULL)
        {
            $file_detail = $customer->report_file_config_details;

            $file_detail_arr = json_decode($file_detail,true);

            if($file_detail_arr!=NULL && count($file_detail_arr)>0)
            {
                $file_name = '';

                asort($file_detail_arr);

                $i=0;

                $count = count($file_detail_arr);

                foreach($file_detail_arr as $key => $item)
                {
                    if(stripos($key,'reference_no')!==false)
                    {
                        $file_name.=$candidate->display_id;
                    }
                    else if(stripos($key,'emp_code')!==false)
                    {
                        if($candidate->client_emp_code!='' && $candidate->client_emp_code!=null)
                            $file_name.=$candidate->client_emp_code;
                    }
                    else if(stripos($key,'candidate_name')!==false)
                    {
                        $file_name.=$candidate->name;
                    }
                    else if(stripos($key,'status')!==false)
                    {
                        $status = '';

                        
                        if(stripos($candidate->report_status,'interim')!==false)
                        {
                            $status = 'Interim Report';
                        }
                        else if(stripos($candidate->report_status,'completed')!==false)
                        {
                            $status = 'Final Report';
                        }
                        

                        $file_name.=$status;
                    }
                    else if(stripos($key,'date')!==false)
                    {
                        $file_name.=date('d-F-Y');
                    }

                    if(++$i!=$count)
                    {
                        $file_name.=' - ';
                    }
                }

                $file_name.='.pdf';
            }
        }
        
         $template_type = DB::table('report_add_page_statuses')->select('status')->where(['coc_id' => $candidate->business_id,'template_type'=>'3'])->first();
        // dd($template_type);
         if($template_type){
            if($template_type->status=='enable')
            {
                // dd($template_type);
                // return view('admin.candidates.pdf.new-report-template3', compact('report_items','footer_list','data','jaf','candidate','report_data'));
                $pdf = PDF::loadView('admin.candidates.pdf.new-report-template3', compact('report_items','footer_list','data','jaf','candidate','report_data'),[],[
                    'title' => 'Report',
                    'margin_top' => 20,
                    'margin-header'=>20,
                    'margin_bottom' =>25,
                    'margin_footer'=>5,
                ]);
            }
            else{

                $pdf = PDF::loadView('admin.candidates.pdf.new-report', compact('report_items','footer_list','jaf','candidate','report_data'),[],[
                    'title' => 'Report',
                    'margin_top' => 20,
                    'margin-header'=>20,
                    'margin_bottom' =>25,
                    'margin_footer'=>5,
                    
                ] ); 
            }
        }  
        else {
            ini_set('max_execution_time', '0');
            // return view('admin.candidates.pdf.new-report', compact('report_items','footer_list','data','jaf','candidate','report_data'));

            $pdf = PDF::loadView('admin.candidates.pdf.new-report', compact('report_items','footer_list','data','jaf','candidate','report_data','verifier'),[],[
                'title' => 'Report',
                'margin_top' => 20,
                'margin-header'=>20,
                'margin_bottom' =>25,
                'margin_footer'=>5,
                
            ]);
        }
        // return $pdf->stream('preview_Report.pdf');
        //    return view('admin.candidates.pdf.new-report', compact('report_items','footer_list','data','jaf','candidate','report_data'));
        return $pdf->stream($file_name);

    }

    public function JAFPDFgenerate($id)
    {
        // $user_id = Auth::user()->id;
        $file_name='';
        $candidate_id=base64_decode($id);
        // dd($candidate_id);

        $candidate = Db::table('users as u')
         ->select('u.id','u.business_id','u.client_emp_code','u.entity_code','u.first_name','u.last_name','u.name','u.email','u.phone','u.dob','u.aadhar_number','u.father_name','u.gender','u.digital_signature','u.display_id')  
         ->where(['u.id'=>$candidate_id]) 
         ->first(); 
        // $sla_data = DB::table('customer_sla')->where(['id'=>$id])->first();
        
        // $sla_service_items = DB::table('customer_sla_items as cs')
        //                         ->select('cs.id','cs.service_id')
        //                         ->where(['cs.sla_id'=>$id])
        //                         ->get();

        $jaf_items = DB::table('jaf_form_data as jf')
        ->select('jf.id','jf.form_data_all','jf.form_data','jf.check_item_number','jf.address_type','jf.insufficiency_notes','jf.is_insufficiency','jf.is_api_checked','jf.verification_status','jf.verified_at','s.name as service_name','s.id as service_id','s.verification_type','s.type_name')
        ->join('services as s','s.id','=','jf.service_id')
        ->where(['jf.candidate_id'=>$candidate_id])
        ->get();
        // dd($jaf_items);
        // echo '<pre>';print_r($jaf_items);
        // die;
        // $sla_items = DB::select("SELECT sla_id, GROUP_CONCAT(DISTINCT service_id) AS alot_services FROM `job_sla_items` WHERE candidate_id = $candidate_id");

        $pdf = PDF::loadView('admin.candidates.pdf.pdf-jaf', compact('candidate','jaf_items'));

        $file_name='BGV-'.$candidate->name.'-'.$candidate->display_id.'.pdf';
  
        return $pdf->download($file_name);

        // return view('admin.candidates.pdf.pdf-jaf',compact('candidate','jaf_items'));
    }

    public function billingDetailsPDF(Request $request,$id)
    {
        $file_name='';
        $business_id=Auth::user()->business_id;

        $customers=DB::table('users as u')
                ->select('u.*','ub.company_name','ub.gst_number','ub.tin_number','ub.address_line1 as business_address','ub.city_name','ub.state_name','ub.country_name','ub.zipcode','ub.phone as business_phone','ub.website','ub.email as business_email','ub.hsn_or_sac','ub.company_short_name','ub.bank_name','ub.account_number','ub.ifsc_code')
                ->join('user_businesses as ub','ub.business_id','=','u.id')
                ->where('u.id',$business_id)
                ->first();

        $billing_id=base64_decode($id);
        $bill=DB::table('billings')->where('id',$billing_id)->first();

        $billingdata=DB::table('billings as b')
                    ->select('b.id','blit.service_id','blit.service_name','blit.service_item_number',DB::raw('COUNT(blit.service_id) as qty'),DB::raw('CAST(SUM(blit.final_total_check_price)/COUNT(blit.service_id) as  decimal(10,2)) as checkrate'),DB::raw('CAST(SUM(blit.final_total_check_price) as decimal(10,2)) as totalcheckamount'))
                    ->join('billing_items as blit','b.id','=','blit.billing_id')
                    ->where(['b.id'=>$billing_id])
                    ->groupBy('blit.service_id') 
                    ->groupBy('blit.service_item_number') 
                    ->get();
        // $items=DB::table('billing_items as bi')
        //     ->select('bi.*','s.verification_type')
        //     ->join('services as s','s.id','=','bi.service_id')
        //     ->where(['bi.billing_id'=>$billing_id])
        //     ->orderBy('bi.service_id','asc')
        //     ->get();

        $billing_detail_candidate=DB::table('billing_items as bi')
        ->DISTINCT('bi.candidate_id')
        ->select('bi.candidate_id','bi.business_id',DB::raw('sum(bi.quantity) as total_quantity'),DB::raw('sum(bi.additional_charges) as total_additional_charges'),DB::raw('sum(bi.final_total_check_price) as total_check_price'))
        ->groupBy('bi.candidate_id')
        ->where(['bi.billing_id'=>$billing_id])
        ->whereNotNull('bi.candidate_id');

        $billing_detail_candidate=$billing_detail_candidate->get();

        $billing_detail_api = DB::table('billing_items as bi')
        ->select('bi.business_id',DB::raw('sum(bi.quantity) as total_quantity'),DB::raw('sum(bi.additional_charges) as total_additional_charges'),DB::raw('sum(bi.final_total_check_price) as total_check_price'))
        ->groupBy('bi.candidate_id')
        ->where(['bi.billing_id'=>$billing_id])
        ->whereNull('bi.candidate_id');

        $billing_detail_api=$billing_detail_api->get();
        $candidate_id = DB::table('billing_items')->where('billing_id', $billing_id)->pluck('candidate_id');
        $packagedata=DB::table('job_items')->whereIn('candidate_id', $candidate_id)->get();
        //return view('admin.billing.pdf.invoice', compact('billing_detail_candidate','billing_detail_api','bill','customers','billingdata'));
        $pdf = PDF::loadView('admin.billing.pdf.invoice', compact('billing_detail_candidate','billing_detail_api','bill','customers','billingdata','packagedata') );

        $file_name=$bill->invoice_id.date('Ymdhis').'.pdf';

        // return $pdf->stream($file_name);

        return $pdf->download($file_name);

        // return view('admin.accounts.billing.pdf.invoice',compact('items','bill','customers'));
    }

    public function bulkBillingDetailsPDF(Request $request)
    {
        $from_date = $to_date= $customer_id = "";

        $bulk_bill_id=[];

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

        if($request->session()->has('bulk_bill_id'))
        {  
            $bulk_bill_id   =  $request->session()->get('bulk_bill_id');
            rsort($bulk_bill_id);
        }

        // dd($bulk_bill_id);

        $file_name='';

        $business_id=Auth::user()->business_id;

        $customers=DB::table('users as u')
                ->select('u.*','ub.company_name','ub.gst_number','ub.tin_number','ub.address_line1 as business_address','ub.city_name','ub.state_name','ub.country_name','ub.zipcode','ub.phone as business_phone','ub.website','ub.email as business_email','ub.hsn_or_sac','ub.company_short_name','ub.bank_name','ub.account_number','ub.ifsc_code')
                ->join('user_businesses as ub','ub.business_id','=','u.id')
                ->where('u.id',$business_id)
                ->first();

        $billings=DB::table('billings as b')
                    ->whereIn('b.id',$bulk_bill_id)
                    ->orderBy('b.id','desc')
                    ->get();

        $pdf = PDF::loadView('admin.billing.pdf.bulk_invoice', compact('billings','customers') );

        $file_name='bulk_invoice-'.date('Ymdhis').'.pdf';

        // return $pdf->stream($file_name);

        return $pdf->download($file_name);

        // return view('admin.billing.pdf.bulk_invoice',compact('billings','customers'));
    }

    public function billSample()
    {
        $pdf = PDF::loadView('admin.billing.pdf.sample');

        $file_name='bill_sample-'.date('Ymdhis').'.pdf';

        // return $pdf->stream($file_name);

        return $pdf->download($file_name);
    }

    public function billingPreviewPDF(Request $request,$id)
    {
        $file_name='';
        $business_id=Auth::user()->business_id;

        // $parent_id=Auth::user()->parent_id;

        $customers=DB::table('users as u')
                ->select('u.*','ub.company_name','ub.gst_number','ub.tin_number','ub.address_line1 as business_address','ub.city_name','ub.state_name','ub.country_name','ub.zipcode','ub.phone as business_phone','ub.website','ub.email as business_email','ub.hsn_or_sac','ub.company_short_name','ub.bank_name','ub.account_number','ub.ifsc_code')
                ->join('user_businesses as ub','ub.business_id','=','u.id')
                ->where('u.id',$business_id)
                ->first();

        $billing_id=base64_decode($id);

        $bill=DB::table('billings')->where('id',$billing_id)->first();
        $billingdata=DB::table('billings as b')
                    ->select('b.id','blit.service_id','blit.service_name','blit.service_item_number',DB::raw('COUNT(blit.service_id) as qty'),DB::raw('CAST(SUM(blit.final_total_check_price)/COUNT(blit.service_id) as  decimal(10,2)) as checkrate'),DB::raw('CAST(SUM(blit.final_total_check_price) as decimal(10,2)) as totalcheckamount'))
                    ->join('billing_items as blit','b.id','=','blit.billing_id')
                    ->where(['b.id'=>$billing_id])
                    ->groupBy('blit.service_id') 
                    ->groupBy('blit.service_item_number') 
                    ->get();

        $billing_detail_candidate=DB::table('billing_items as bi')
                                        ->DISTINCT('bi.candidate_id')
                                        ->select('bi.candidate_id','bi.business_id',DB::raw('sum(bi.quantity) as total_quantity'),DB::raw('sum(bi.additional_charges) as total_additional_charges'),DB::raw('sum(bi.final_total_check_price) as total_check_price'))
                                        ->groupBy('bi.candidate_id')
                                        ->where(['bi.billing_id'=>$billing_id])
                                        ->whereNotNull('bi.candidate_id');

        $billing_detail_candidate=$billing_detail_candidate->get();

        $billing_detail_api = DB::table('billing_items as bi')
                                ->select('bi.business_id',DB::raw('sum(bi.quantity) as total_quantity'),DB::raw('sum(bi.additional_charges) as total_additional_charges'),DB::raw('sum(bi.final_total_check_price) as total_check_price'))
                                ->groupBy('bi.candidate_id')
                                ->where(['bi.billing_id'=>$billing_id])
                                ->whereNull('bi.candidate_id');
        
        $billing_detail_api=$billing_detail_api->get();

        $candidate_id = DB::table('billing_items')->where('billing_id', $billing_id)->pluck('candidate_id');
        $packagedata=DB::table('job_items')->whereIn('candidate_id', $candidate_id)->get();
        // dd($packagedata);
        // $packagcount=DB::table('job_items')->select(DB::raw('count(sla_id) as slacount'))->whereIn('candidate_id', $candidate_id)->->first();
        // dd( $packagedata);
        // $val=0;
        // foreach($packagedata as $data){
        //     if($data->price_type=='check'){
        //         $val=  $val+1;
        //         if($val>1){
            
        //             $checkdata=DB::table('job_items')->whereIn('candidate_id', $candidate_id)->where(['price_type'=>'check'])->groupBy('price_type')->get();
        //             dd($checkdata);
        //         }
        //         // dd($val);
        //     }
            
        // }
      
        
       
        
        // dd($resultArray);
        // dd($packagedata);
        // $result = $packagedata->duplicates('candidate_id');
        //     $collection = collect($packagedata); //change array to collection
        //    $packagearr= $collection->toArray();
        //     if(count(array_unique($packagearr))<count($packagearr))
        //     {
        //         dd('Array has duplicates'); // Array has duplicates
        //     }
        //     else
        //     {
        //         dd($packagedata);// Array does not have duplicates
        //     }
            

        // dd($billing_detil_api);

        // $items=DB::table('billing_items as bi')
        //     ->select('bi.*','s.verification_type')
        //     ->join('services as s','s.id','=','bi.service_id')
        //     ->where(['bi.billing_id'=>$billing_id])
        //     ->orderBy('bi.service_id','asc')
        //     ->get();
       // return view('admin.billing.pdf.invoice',compact('billing_detail_candidate','billing_detail_api','bill','customers','billingdata'));
        $pdf = PDF::loadView('admin.billing.pdf.invoice', compact('billing_detail_candidate','billing_detail_api','bill','customers','billingdata','packagedata',) );

        $file_name=$bill->invoice_id.date('Ymdhis').'.pdf';

        return $pdf->stream($file_name);


    }

    public function digitalAddressAddToReport(Request $request)
    {
      $business_id = Auth::user()->business_id;

      $jaf_id = base64_decode($request->id);

      $file_platform = 'web';

      $is_temp   = 0;

      try
      {

        $folderPath = public_path('uploads/digital-address-verification');

        if(!File::exists($folderPath))
        {
            File::makeDirectory($folderPath, $mode = 0777, true, true);
        }

        if(File::exists($folderPath))
        {
          File::cleanDirectory($folderPath);
        }

        $address_verification = AddressVerification::from('address_verifications as a')
                                ->select('a.*','j.business_id')
                                ->join('jaf_form_data as j','j.id','=','a.jaf_id')
                                ->where('a.jaf_id',$jaf_id)
                                ->first();

        $verification_decision = DB::table('address_verification_decision_logs')->where('jaf_id',$jaf_id)->latest()->first();

        $report_item = DB::table('report_items')->where('jaf_id',$jaf_id)->first();

        $candidate_id = $address_verification->candidate_id;

        $file_name='address-verification-'.date('Ymdhis').'.pdf';

        $pdf = PDF::loadView('admin.candidates.pdf.address-verification-decision-template', compact('address_verification','verification_decision','business_id'),[],[
            'title' => 'MyBCD',
            'margin_top' => 20,
            'margin-header'=>20,
            'margin_bottom' =>25,
            'margin_footer'=>5,
        ])->save($folderPath.$file_name);

        $report_dir  = public_path('/uploads/report-files/');

        $fileName = 'address-verification-'.date('Ymdhis');

        $pdf_file_name = $fileName.'-'.time();
    
        $imagick = new Imagick();

        $imagick->setResolution(300, 300);

        $imagick->readImage($folderPath.$file_name);

        $imagick->setImageFormat("png");

        $pages = $imagick->getNumberImages();

        $imagick->writeImages($report_dir.$pdf_file_name.'.png', false);

        if($pages)
        {
            $s3_config = S3ConfigTrait::s3Config();

            if($pages==1)
            {
                if($s3_config!=NULL)
                {
                    $file_platform = 's3';

                    $file_name = $pdf_file_name.'.png';

                    $path = 'uploads/report-files/';

                    if(!Storage::disk('s3')->exists($path))
                    {
                        Storage::disk('s3')->makeDirectory($path,0777, true, true);
                    }

                    $file = Helper::createFileObject($path.$file_name);

                    Storage::disk('s3')->put($path.$file_name, file_get_contents($file));

                }

                $rowID = DB::table('report_item_attachments')            
                ->insertGetId([
                    'report_id'        => $report_item->report_id, 
                    'report_item_id'   => $report_item->id,                      
                    'file_name'        => $pdf_file_name.'.png',
                    'attachment_type'  => 'main',
                    'file_platform'    => $file_platform,
                    'created_by'       => Auth::user()->id,
                    'created_at'       => date('Y-m-d H:i:s'),
                    'is_temp'          => $is_temp,
                ]);

                
                if(stripos($file_platform,'s3')!==false)
                {
                  $filePath = 'uploads/report-files/';

                  if(File::exists(public_path('/uploads/report-files/'.$pdf_file_name.'.png')))
                  {
                    File::delete(public_path('/uploads/report-files/'.$pdf_file_name.'.png'));
                  }

                  
                }
                
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

                        $path = 'uploads/report-files/';

                        if(!Storage::disk('s3')->exists($path))
                        {
                            Storage::disk('s3')->makeDirectory($path,0777, true, true);
                        }

                        $file = Helper::createFileObject($path.$file_name);

                        Storage::disk('s3')->put($path.$file_name, file_get_contents($file));

                    }
                    
                    $rowID = DB::table('report_item_attachments')            
                    ->insertGetId([
                        'report_id'        => $report_item->report_id, 
                        'report_item_id'   => $report_item->id,                      
                        'file_name'        => $pdf_file_name.'-'.$i.'.png',
                        'attachment_type'  => 'main',
                        'file_platform'    => $file_platform,
                        'created_by'       => Auth::user()->id,
                        'created_at'       => date('Y-m-d H:i:s'),
                        'is_temp'          => $is_temp,
                    ]);

                    
                    if(stripos($file_platform,'s3')!==false)
                    {
                      $filePath = 'uploads/report-files/';

                      if(File::exists(public_path('/uploads/report-files/'.$pdf_file_name.'-'.$i.'.png')))
                      {
                        File::delete(public_path('/uploads/report-files/'.$pdf_file_name.'-'.$i.'.png'));
                      }

                    }
                    

                }
            }
            
            if(File::exists($report_dir.'tmp-files/'))
            {
              File::cleanDirectory($report_dir.'tmp-files/');
            }

            DB::table('address_verification_decision_logs')->where('id',$verification_decision->id)->update([
              'is_send_report' => 1
            ]);

        }

        return response()->json([
          'success'  => true
        ]);

      }
      catch (\Exception $e) {
          // something went wrong
          return $e;
      } 

    }
    public function addressVerificationReport(Request $request)
    {
        $business_id = Auth::user()->business_id;
        $jaf_id = base64_decode($request->id);

        $address_verification = AddressVerification::from('address_verifications as a')
                                ->select('a.*','j.business_id')
                                ->join('jaf_form_data as j','j.id','=','a.jaf_id')
                                ->where('a.jaf_id',$jaf_id)
                                ->first();

        $verification_decision = DB::table('address_verification_decision_logs')->where('jaf_id',$jaf_id)->latest()->first();

        $pdf = PDF::loadView('admin.candidates.pdf.address-verification-decision-template', compact('address_verification','verification_decision','business_id'),[],[
            'title' => 'MyBCD',
            'margin_top' => 20,
            'margin-header'=>20,
            'margin_bottom' =>25,
            'margin_footer'=>5,
        ]);

        $file_name='address-verification-'.date('Ymdhis').'.pdf';

        //return $pdf->download($file_name);

        return $pdf->stream($file_name);

        //return view('admin.candidates.pdf.address-verification-decision-template', compact('address_verification','verification_decision','business_id'));
    }

}