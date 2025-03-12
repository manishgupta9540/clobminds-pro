<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style type="text/css">
    
        @page {
            header: page-header;
            footer: page-footer;
        }
        @font-face {
            font-family: 'Vollkorn', serif;
            font-weight: normal;
            font-style: normal;
            src: url( "{{ asset('admin/fonts/OpenSans-Regular.ttf') }}" ) format('truetype');
        }
        @font-face {
            font-family: 'Vollkorn', serif;
            font-weight: normal;
            font-style: normal;
            src: url( "{{ asset('admin/fonts/OpenSans-Regular.ttf') }}" ) format('truetype');
        }
        body {font-family: 'Vollkorn', serif;}
        table tr td {font-family: 'Vollkorn', serif; text-align: left;}
        footer {
                position: fixed; 
                bottom: -10px; 
                left: -50px; 
                right: -50px;
                height: 80px;
                padding: 0px 30px;

                /** Extra personal styles **/
                /*background-color: #03a9f4;*/
                color: #000000;
                text-align: center;
                line-height: 20px;
        } 

        .completepage
        {
            padding:25px !important;
            border:1px solid #000 !important;
        }
        .signature{
            
            padding-left: 100px;
            padding-top:100px;
        }
        .detailcss{
            padding-left:400px;
            
        }
        .mark_print{
            padding-top: 100px;
            margin-top:100px;
        }
        .signimg
        {
            width:200px !important;
            margin-left:700px;
            margin-right:-300px;
        }
    </style>
  </head>
  <body style="font-family: 'Vollkorn', serif;">
        @php
            //Helper to get report_add_page_statuses Data
         $report_add_page =  Helper::get_report_page($candidate->business_id);
        @endphp
        @if ($report_add_page)
            @if ($report_add_page->status == 'enable')
            <htmlpageheader name="page-header">
                <!--logo top table -->
                {{-- <table style="width:100%; border-bottom:1px solid #ddd;">
                    <tbody>
                        <tr>
                            <td style="padding:7px; width:50%; text-align: left;"> {!! Helper::company_logo($candidate->business_id) !!} </td>
                            <td style="padding:7px; width:50%; text-align: right;"> {{ Helper::company_name($candidate->business_id) }} </td>
                        </tr>
                    </tbody>
                </table> --}}
                <!--logo top table ends -->
            </htmlpageheader> 
            @else
                <htmlpageheader name="page-header">
                    <!--logo top table -->
                    <table style="width:100%;">
                        <tbody>
                            <tr>
                                <td style="padding:7px; width:50%; text-align: left;"> {!! Helper::company_logo($candidate->business_id) !!} </td>
                                <td style="padding:7px; width:50%; text-align: right;"> {{ Helper::report_company_name($candidate->business_id) }} </td>
                                {{-- <td style="padding:7px; width:50%; text-align: right;"> {{ Helper::company_name($candidate->business_id) }} </td> --}}
                            </tr>
                        </tbody>
                    </table>
                    <!--logo top table ends -->
                </htmlpageheader> 
            @endif
            
        @else
            <htmlpageheader name="page-header">
                <!--logo top table -->
                <table style="width:100%; ">
                    <tbody>
                        <tr>
                            <td style="padding:7px; width:50%; text-align: left; margin-top:20px"> {{ date("m/d/Y,h:i A") }} </td>
                            {{-- <td style="padding:7px; width:50%; text-align: right;"> {{ Helper::company_name($candidate->business_id) }} </td> --}}
                            {{--  <td style="padding:7px; width:50%; text-align: right;"> {{ Helper::report_company_name($candidate->business_id) }} </td>--}}
                        </tr>
                    </tbody>
                </table>
                <!--logo top table ends -->
            </htmlpageheader> 
        @endif
        <htmlpagefooter name="page-footer">
            <footer>
                <p style="font-size:13px;">
                @php $footeradd ='Vanguard Rise ,5th Cross Road, HAL Old Airport Rd, Konena Agrahara, Bengaluru, Karnataka 560017'; @endphp
                <b>Confidential</b>
                <br><b>{{$footer_list!=null? $footer_list->company_name : ''}}</b><br>
                {{$footer_list!=null ? $footer_list->company_address : $footeradd }}</p>
                <table cellpadding="0" cellspacing="2" width="100%" ><tr><td align="right">{PAGENO} of {nb}</td> </tr></table>
            </footer>
        </htmlpagefooter>
        @php 
        
         $x = 0; $z=1; $alpha = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
        
    
         $registered_company_addr=Helper::get_registered_company_addr($candidate->parent_id);
        @endphp

        @if ($report_add_page)
            @if ($report_add_page->status == 'enable')
                <div class="body_add completepage" style="background:#fff;">

                    <table style="width:100%; border:none; border-collapse: collapse; text-align: center;">
                        <tbody>
                            <tr>
                                <td style=" font-size: 15px; border:none ; text-transform: uppercase;text-align: left;" width="30%"><small>Registered Office: <br> {{$registered_company_addr}} </small></td>
                                <td style="border: none ; text-transform: uppercase;text-align: right;" width="70%">
                                    {!!Helper::company_logo($candidate->business_id)!!}</td>
                            </tr>
                        </tbody>
                    </table>
                        {{-- <div class="row">
                            <div class="col-md-3">
                                <span>Registered Office: {{$registered_company_addr}}</span>
                                <div class="col-md-6" >logo</div>
                            </div>
                            <div class="col-md-6">logo</div>
                        </div> --}}
                    
                    <table style="width:100%; border:2px solid #000; border-collapse: collapse; margin-top: 15px; text-align: center;">
                        <tbody>
                            <tr>
                                <td style="font-size:9px; padding:7px; background: #ccc; font-size: 30px; border: 1px solid #666; text-transform: uppercase;text-align: center;"><small> {{ Session::get('reportType') }} Background Report - EXECUTIVE SUMMARY </small></td>
                            </tr>
                        </tbody>
                    </table>

                    <table class="main-table" style="width:100%; border:2px solid #000; margin-top:15px; border-collapse: collapse;" autosize="1">
                        <tbody>
                            <tr>
                                <td style="padding:5px; border:1px solid #666; background:#ccc;"><strong>Client Name</strong></td>
                                <td colspan="3" style="padding:7px; border:1px solid #666;">{{ Helper::report_company_name($candidate->business_id) }}</td>
                            </tr>
                            <tr>
                                <td style="padding:5px; border:1px solid #666; background:#ccc;"><strong>Name of Candidate</strong></td>
                                <td colspan="3" style="padding:7px; border:1px solid #666;">{{ ucwords($candidate->name) }}</td>
                            </tr>
                            <tr>
                                <td style="padding:5px; border:1px solid #666; background:#ccc;"><strong>Date Of Birth</strong></td>
                                <td colspan="3" style="padding:7px; border:1px solid #666;">{{ ucwords($candidate->dob) }}</td>
                            </tr>
                            <tr>
                                <td style="padding:5px; border:1px solid #666; background:#ccc;"><strong>Package</strong></td>
                                <td colspan="3" style="padding:7px; border:1px solid #666;">{{ ucwords($candidate->sla_name) }}</td>
                            </tr>
                            <tr>
                                <td style="padding:5px; border:1px solid #666; background:#ccc;"><strong>Emp ID</strong></td>
                                <td colspan="3" style="padding:7px; border:1px solid #666;">{{ ucwords($candidate->client_emp_code) }}</td>
                            </tr>
                            <tr>
                                <td style="padding:5px; border:1px solid #666; background:#ccc;"><strong>Reference Number</strong></td>
                                <td colspan="3" style="padding:7px; border:1px solid #666;">{{ ucwords($candidate->display_id) }}</td>
                            </tr>
                            <tr>
                                <td style="padding:5px; border:1px solid #666; background:#ccc;"><strong>Case Received Date</strong></td>
                                <td colspan="3" style="padding:7px; border:1px solid #666;">@if($candidate->case_received_date) {{  date('Y-m-d',strtotime($candidate->case_received_date)) }} @endif</td>
                            </tr>
                            <tr>
                                
                                <td style="padding:7px; border:1px solid #666; background:#ccc;"><strong>Date Of Report</strong></td>
                                <td  colspan="3" style="padding:7px; border:1px solid #666;"> 
                                    @php
                                    $hide = Helper::report_custom($candidate->business_id);
                                @endphp
                                    @if($hide=='enable' && ($candidate->revised_date !="" && $candidate->revised_date != null))
                                        {{ date('d M Y', strtotime($candidate->revised_date)) }}
                                    @elseif($candidate->is_report_complete == 1 && $candidate->report_complete_created_at != null)
                                        {{ date('d M Y', strtotime($candidate->complete_updated_at)) }}
                                    @elseif($report_data->complete_updated_at !="" || $report_data->complete_updated_at != null)
                                        {{ date('d M Y', strtotime($report_data->complete_updated_at)) }}
                                    {{-- @elseif($report_data->generated_at !="" || $report_data->generated_at != null)
                                        {{ date('d M Y', strtotime($report_data->generated_at)) }} --}}
                                    @else
                                        {{ date('d M Y', strtotime($report_data->created_at)) }}
                                    @endif
                                </td>
                            </tr>
                            
                            <tr>
                                
                                <td style="padding:7px; border:1px solid #666; background:#ccc;"><strong>Report Status</strong></td>
                                @if($candidate->is_manual_mark==1)
                                    <td colspan="3" style="padding:7px; border:1px solid #666; background:green"> Green </td>
                                @elseif($candidate->is_manual_mark==2)
                                    <td  colspan="3" style="padding:7px; border:1px solid #666; background:rgb(83,83,83)"> Stopped </td>
                                @elseif($candidate->is_manual_mark==3)
                                    <td colspan="3" style="padding:7px; border:1px solid #666; background:rgb(242,27,7)"> Red </td>
                                @elseif($candidate->is_manual_mark==4)
                                    <td colspan="3" style="padding:7px; border:1px solid #666; background:rgb(242,219, 7)"> Yellow </td>
                                @elseif($candidate->is_manual_mark==5)
                                    <td colspan="3" style="padding:7px; border:1px solid #666; background:rgb(252,132,3)"> Orange </td>
                                @elseif($candidate->is_manual_mark==6)
                                    <td colspan="3" style="padding:7px; border:1px solid #666; background:rgb(78, 78, 78)"> Interim </td>
                                @else
                                    <td colspan="3" style="padding:7px; border:1px solid #666; color:{{Helper::get_approval_status_color_code($candidate->report_id)}}"> {!! Helper::get_approval_status_color_name($candidate->report_id) !!} </td>
                                @endif
                            </tr>
                        </tbody>
                    </table>

                    <table style="width:100%; border:2px solid #000; border-collapse: collapse; margin-top: 15px; vertical-align:middle">
                        <tbody>
                            <tr>
                                <td style="padding:5px; font-size:9px; vertical-align:middle;display: flex;align-items: center;text-align: center;"><img style="vertical-align:middle" src="{{ asset('admin/images/red.png') }}" />&nbsp; Major Discrepancy </td>
                                <td style="padding:5px;font-size:9px; vertical-align:middle;display: flex;align-items: center;text-align: center;"><img style="vertical-align:middle" src="{{ asset('admin/images/yellow.png') }}" />&nbsp;Minor Discrepancy</td>
                                <td style="padding:5px;font-size:9px; vertical-align:middle;display: flex;align-items: center;text-align: center;"><img style="vertical-align:middle" src="{{ asset('admin/images/orange.png') }}" />&nbsp; Inaccessible for verification/Unable to verify/Inputs required</td>
                                <td style="padding:5px;font-size:9px; vertical-align:middle;display: flex;align-items: center;text-align: center;"><img style="vertical-align:middle" src="{{ asset('admin/images/green.png') }}" />&nbsp; Clear Report</td>
                                <td style="padding:5px;font-size:9px; vertical-align:middle;display: flex;align-items: center;text-align: center;"><img style="vertical-align:middle" src="{{ asset('admin/images/blue-box.png') }}" />&nbsp; WIP</td>
                                {{-- <td style="padding:5px;font-size:9px; vertical-align:middle;display: flex;align-items: center;text-align: center;"><img style="vertical-align:middle" src="{{ asset('admin/images/green.png') }}" />&nbsp; No Record Found</td>
                                <td style="padding:5px;font-size:9px; vertical-align:middle;display: flex;align-items: center;text-align: center;"><img style="vertical-align:middle" src="{{ asset('admin/images/grey-box.png') }}" />&nbsp; Stopped </td> --}}
                            </tr>
                        </tbody>
                    </table>

                    @php
                        $service_item_arr=[];
                        if( count($report_items) > 0 ){
                            foreach($report_items as $item){
                                $service_item_arr[] = $item->service_id;
                            }   
                        }
                        $service_item_num = array_count_values($service_item_arr);
                        $i=1;
                    @endphp
                    <div class="page-break">
                            <table style="width:100%; border:2px solid #000; border-collapse: collapse; text-align: center; margin-top:15px;">
                                <tbody>
                                
                                        @if( count($report_items) > 0 )
                                            @php
                                                $check_no = 1;
                                                $r_item_id = NULL;
                                            @endphp
                                            @foreach($report_items as $r_key => $item)
                                                <?php 
                                                $approval_status_name = "";
                                                $input_item_data ="";
                                                $input_item_data_array =[];
                                            
                                                $approval_status_name   = Helper::get_approval_status_name($item->id); 
                                                $input_item_data        = $item->jaf_data;
                                                $input_item_data_array  = json_decode($input_item_data, true);
                                                $verification_status    = Helper::get_report_verification_status($item->id);
                                                $report_insufficiency_notes = $item->report_insufficiency_notes;
                                                $particular             = "";
                                                $university     ="";
                                                $br = "";
                                                $m=0;
                                                foreach($input_item_data_array as $f_item){
                                                    if( array_key_exists('is_executive_summary',$f_item) ){
                                                        if($f_item['is_executive_summary'] == '1'){
                                                            if($m > 0){
                                                                $br = "<br/>";
                                                            }
                                                            $data       = array_keys($f_item); 
                                                            $data1      = array_values($f_item); 
                                                            $particular .= $br.$data1[0]." ";

                                                            $m++;
                                                        }   
                                                    }
                                                }        
                                                $data3=[];
                                                foreach($input_item_data_array as $f_item){
                                                    if( array_key_exists('University Name / Board Name',$f_item)){
                                                        
                                                            $data2       = array_keys($f_item); 
                                                            $data3      = array_values($f_item); 
                                                            $university .= $br.$data3[0]." ";

                                                        }                                      
                                                }
                                                
                                                if($r_item_id!=NULL)
                                                {
                                                    //dd($r_item_id);
                                                    $previous = Helper::get_report_item($r_item_id);

                                                    if($previous->service_id==$item->service_id)
                                                    {
                                                        $check_no++;
                                                    }
                                                    else {
                                                        $check_no = 1;
                                                    }
                                                }
                                                ?>  
                                                <tr>
                                                    {{-- <td style="font-size: 10px; padding:7px; border:1px solid #666; background:#ccc; text-align:left;" colspan="3"><strong> {{$item->service_name.'-'. Helper::get_check_item_number($item->report_id, $item->id) }}</strong></td> --}}
                                                    {{-- <td style="font-size: 10px; padding:7px; border:1px solid #666; background:#ccc; text-align:left;" colspan="3"><strong> {{$item->service_name.'-'. $item->service_item_order }}</strong></td> --}}
                                                    <td style="font-size: 10px; padding:7px; border:1px solid #666; background:#ccc; text-align:left;" colspan="3"><strong> {{$item->service_name.'-'. $check_no }}</strong></td>
                                                </tr>
                                                <tr> 
                                                    <td style="font-size: 10px; padding:7px; border:1px solid #666; text-align:left;" width="70%" colspan="2" rowspan="2" > 
                                                        
                                                        @if ($item->service_name == "Educational")
                                                            @if ($verification_status=='4')
                                                            
                                                            Education verification check was conducted and received the Positive response from
                                                            {{$university}} University
                                                            @else
                                                                @if ($report_insufficiency_notes)
                                                                    {{$report_insufficiency_notes}}
                                                                @endif
                                                                
                                                            @endif 
                                                        @endif
                                                        @if ($item->service_name == "Employment")
                                                        Employment verification was conducted for the last 2 employments/Last 5 years'. The report was satisfactory.                                   
                                                        @endif
                                                    @if ($item->service_name == "Address")
                                                        Address Verification was conducted and there was no deviation in the report.                                   
                                                        @endif
                                                        @if ($item->service_name == "Criminal")
                                                        CIVIL & CRIMINAL proceedings verification for Original Suit/Miscellaneous Suit/Execution Petition was conducted in All India Courts/All India High Courts/Supreme Court of India.                                  
                                                        @endif
                                                        @if ($item->service_name == "Aadhar")
                                                        Identity verification was conducted through Aadhaar Card to establish the identity of the candidate and found to be genuine.
                                                
                                                        @endif 
                                                        @if (stripos($item->service_name,"PAN")!==false)
                                                        Identity verification was conducted through PAN card to establish the identity of the candidate and found to be genuine.
                                                
                                                        @endif
                                                        @if ($item->service_name == "Passport")
                                                        Identity verification was conducted through Passport to establish the identity of the candidate and found to be genuine.
                                                
                                                        @endif
                                                        @if ($item->service_name == "Database" )
                                                            Indian and Global Database verification was conducted and there is no match found against candidate credentials.                                 
                                                        @endif 
                                                        @if ($item->service_name == "Reference" )
                                                        Reference checks are conducted and Received satisfactory response.
                                                        @endif 
                                                    </td>
                                                    <td style=" font-size: 10px; padding:7px; border:1px solid #666; text-align:left;" width="30%" > 
                                                        <strong>Status</strong>  
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style=" font-size: 10px; padding:7px; border:1px solid #666; text-align:left;" width="30%" >
                                                        @if( $item->service_id == 15 || $item->service_id == 16 ) 
                                                                @if($item->approval_status_id == 4)
                                                                No Record Found
                                                                @else
                                                                <span style="color: {!! Helper::get_approval_status_color_name($candidate->report_id) !!}" > {!! Helper::get_approval_status_color_name($candidate->report_id) !!} ({{ $approval_status_name }}) </span> 
                                                                @endif
                                                        {{-- @endif  --}}
                                                        {{-- @php
                                                            dd($verification_status);
                                                        @endphp --}}
                                                        @elseif($verification_status)
                                                            @if ( $verification_status == "4")

                                                            <span style="color:  Green" > Green ({{ $approval_status_name }}) 
                                                            
                                                            </span> 

                                                            @endif

                                                            @if ( $verification_status == "2")
                                                            
                                                                
                                                            <span style="color:  Yellow" > Yellow ({{ $approval_status_name }}) 
                                                            
                                                            </span> 

                                                            @endif
                                                            @if ( $verification_status == "1")
                                                            
                                                            <span style="color:  Red" > Red ({{ $approval_status_name }}) 
                                                            
                                                            </span> 

                                                            @endif
                                                            @if ( $verification_status == "3")
                                                            
                                                            <span style="color:  Orange" > Orange ({{ $approval_status_name }}) 
                                                            
                                                            </span> 

                                                            @endif

                                                            @if ( $verification_status == "5")

                                                            <span style="color:  #114F89" > Blue ({{ $approval_status_name }}) 
                                                            
                                                            </span> 

                                                            @endif

                                                            @if ( $verification_status == "6")

                                                            <span style="color:  #701189" > Purple ({{ $approval_status_name }}) 
                                                            
                                                            </span> 

                                                            @endif

                                                            @if ( $verification_status == "7")

                                                            <span style="color:  #535353" > Grey ({{ $approval_status_name }}) 
                                                            
                                                            </span> 

                                                            @endif
                                                        @else
                                                        Verification Pending
                                                        @endif
                                                    </td>
                                                </tr>
                                                <?php $r_item_id = $item->id;?>
                                            @endforeach
                                        @endif
                                
                                {{-- @endif --}}
                                
                                </tbody>
                            </table>
                            <table style="width:100%; border:none; border-collapse: collapse; text-align: center;">
                                <tbody>
                                    <tr>
                                        <td style=" font-size: 15px; border:none ; text-align: left;" ><small>Vendor Website: </small></td>
                                        <td style="font-size: 15px; border: none ; text-align: center;" ><small>Verifier Name:{{$report_data->verifier_name}}</small> </td>
                                        <td style="font-size: 15px; border: none ; text-align: center;" ><small> Designation: {{$report_data->verifier_designation}}</small></td>

                                    </tr>
                                    <tr>
                                        <td style=" font-size: 15px; border:none ;  text-align: left;" ><small><a href="">{{ url('/')}}</a></small></td>
                                        <td style=" font-size: 15px; border: none ; text-align: center;" > <small>Email: <a href="">{{$report_data->verifier_email}}</a></small> </td>
                                        {{-- <td style="font-size: 15px; border: none ; text-transform: uppercase;text-align: left;" ><small></small> </td> --}}

                                    </tr>
                                </tbody>
                            </table>
                    </div>
                </div>
                <pagebreak />
            @endif
        @endif
        <div class="body" >
            <table style="width:100%;border:1px solid #000;height:100%;padding-bottom:90px;padding-top:10px;padding-left:20px;padding-right:20px">
                <tr>
                    <td>
                
                        <span style="padding:10px;">
                            <img style="width: 350px;margin-left:25%;" src="{{asset('admin/images/logo.jpg')}}"/>
                        </span>
                        <table class="main-table" style="width:100%; border:2px solid #000; margin-top:15px; border-collapse: collapse;" autosize="1">
                            <tbody>
                                <tr>
                                    <td style="padding:5px; border:1px solid #666; background:#ccc;"><strong>Client Name</strong></td>
                                    <td colspan="3" style="padding:7px; border:1px solid #666;">{{ Helper::report_company_name($candidate->business_id) }}</td>
                                </tr>
                                {{-- <tr>
                                    <td style="padding:5px; border:1px solid #666; background:#ccc;"><strong>Sub Client Name</strong></td>
                                    <td colspan="3" style="padding:7px; border:1px solid #666;">
                                        {{ Helper::customer_user($candidate->business_id)->company_name ?? 'N/A' }}
                                    </td>
                                </tr> --}}
                                
                                <tr>
                                    <td style="padding:5px; border:1px solid #666; background:#ccc;"><strong>Name of Candidate</strong></td>
                                    <td colspan="3" style="padding:7px; border:1px solid #666;">{{ ucwords($candidate->name) }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:5px; border:1px solid #666; background:#ccc;"><strong>Date Of Birth</strong></td>
                                    <td colspan="3" style="padding:7px; border:1px solid #666;">{{ ucwords($candidate->dob) }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:5px; border:1px solid #666; background:#ccc;"><strong>Package</strong></td>
                                    <td colspan="3" style="padding:7px; border:1px solid #666;">{{ ucwords($candidate->sla_name) }}</td>
                                </tr>
                                {{-- Check the company (Writer Business Services Pvt. Ltd) for including Emp ID --}}
                                {{-- @if($candidate->business_id==692) --}}
                                    <tr>
                                        <td style="padding:5px; border:1px solid #666; background:#ccc;"><strong>Emp ID</strong></td>
                                        <td colspan="3" style="padding:7px; border:1px solid #666;">{{ ucwords($candidate->client_emp_code) }}</td>
                                    </tr>
                                {{-- @endif --}}
                                <tr>
                                    <td style="padding:5px; border:1px solid #666; background:#ccc;"><strong>Reference Number</strong></td>
                                    <td colspan="3" style="padding:7px; border:1px solid #666;">{{ ucwords($candidate->display_id) }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:5px; border:1px solid #666; background:#ccc;"><strong>Case Received Date</strong></td>
                                    <td colspan="3" style="padding:7px; border:1px solid #666;">@if($candidate->case_received_date) {{  date('Y-m-d',strtotime($candidate->case_received_date)) }} @endif</td>
                                </tr>
                                <tr>
                                    
                                    <td style="padding:7px; border:1px solid #666; background:#ccc;"><strong>Date Of Report</strong></td>
                                    <td  colspan="3" style="padding:7px; border:1px solid #666;"> 
                                    @php
                                        $hide = Helper::report_custom($candidate->business_id);
                                    @endphp
                                        @if($hide=='enable' && ($candidate->revised_date !="" && $candidate->revised_date != null))
                                            {{ date('d M Y', strtotime($candidate->revised_date)) }}
                                        @elseif($candidate->is_report_complete == 1 && $candidate->report_complete_created_at != null)
                                            {{ date('d M Y', strtotime($candidate->complete_updated_at)) }}
                                        @elseif($report_data->complete_updated_at !="" || $report_data->complete_updated_at != null)
                                            {{ date('d M Y', strtotime($report_data->complete_updated_at)) }}
                                        {{-- @elseif($report_data->generated_at !="" || $report_data->generated_at != null)
                                            {{ date('d M Y', strtotime($report_data->generated_at)) }} --}}
                                        @else
                                            {{ date('d M Y', strtotime($report_data->created_at)) }}
                                        @endif
                                    </td>
                                </tr>
                                
                                <tr>
                                
                                    <td style="padding:7px; border:1px solid #666; background:#ccc;"><strong>Report Status</strong></td>
                                    @if($candidate->is_manual_mark==1)
                                        <td colspan="3" style="padding:7px; border:1px solid #666; background:green"> Green </td>
                                    @elseif($candidate->is_manual_mark==2)
                                        <td  colspan="3" style="padding:7px; border:1px solid #666; background:rgb(83,83,83)"> Stopped </td>
                                    @elseif($candidate->is_manual_mark==3)
                                        <td colspan="3" style="padding:7px; border:1px solid #666; background:rgb(242,27,7)"> Red </td>
                                    @elseif($candidate->is_manual_mark==4)
                                        <td colspan="3" style="padding:7px; border:1px solid #666; background:rgb(242,219, 7)"> Yellow </td>
                                    @elseif($candidate->is_manual_mark==5)
                                        <td colspan="3" style="padding:7px; border:1px solid #666; background:rgb(252,132,3)"> Orange </td>
                                    @elseif($candidate->is_manual_mark==6)
                                        <td colspan="3" style="padding:7px; border:1px solid #666; background:rgb(78, 78, 78)"> Interim </td>
                                    @else
                                        <td colspan="3" style="padding:7px; border:1px solid #666; color:{{Helper::get_approval_status_color_code($candidate->report_id)}}"> {!! Helper::get_approval_status_color_name($candidate->report_id) !!} </td>
                                    @endif
                                </tr>
                            </tbody>
                        </table>

                        <table style="width:100%; border:2px solid #000; border-collapse: collapse; margin-top: 15px; vertical-align:middle">
                            <tbody>
                                <tr>
                                    <td style="padding:5px; font-size:9px; vertical-align:middle;display: flex;align-items: center;text-align: center;"><img style="vertical-align:middle" src="{{ asset('admin/images/red.png') }}" />&nbsp; Major Discrepancy </td>
                                    <td style="padding:5px;font-size:9px; vertical-align:middle;display: flex;align-items: center;text-align: center;"><img style="vertical-align:middle" src="{{ asset('admin/images/yellow.png') }}" />&nbsp;Minor Discrepancy</td>
                                    <td style="padding:5px;font-size:9px; vertical-align:middle;display: flex;align-items: center;text-align: center;"><img style="vertical-align:middle" src="{{ asset('admin/images/orange.png') }}" />&nbsp; Inaccessible for verification/Unable to verify/Inputs required</td>
                                    <td style="padding:5px;font-size:9px; vertical-align:middle;display: flex;align-items: center;text-align: center;"><img style="vertical-align:middle" src="{{ asset('admin/images/green.png') }}" />&nbsp; Clear Report</td>
                                    <td style="padding:5px;font-size:9px; vertical-align:middle;display: flex;align-items: center;text-align: center;"><img style="vertical-align:middle" src="{{ asset('admin/images/blue-box.png') }}" />&nbsp; WIP</td>
                                    {{-- <td style="padding:5px;font-size:9px; vertical-align:middle;display: flex;align-items: center;text-align: center;"><img style="vertical-align:middle" src="{{ asset('admin/images/green.png') }}" />&nbsp; No Record Found</td>
                                    <td style="padding:5px;font-size:9px; vertical-align:middle;display: flex;align-items: center;text-align: center;"><img style="vertical-align:middle" src="{{ asset('admin/images/grey-box.png') }}" />&nbsp; Stopped</td> --}}
                                </tr>
                            </tbody>
                        </table>
                        <div class="col-md-12" >
                            <table>
                                <tr>
                                    <td class="mark_print"><img style="width: 200px;" src="{{asset('admin/images/mark.jpeg')}}"/></td>
                                    <td class="signature"><img style="" src="{{asset('admin/images/signature.jpeg')}}"/></td>
                                </tr>
                                <tr>
                                    
                                    <td colspan=2 class="detailcss">
                                        <p>Name: Jayanesh Jayan</p>
                                        <p>Designation: Manager Operations Clobminds </p>
                                    </td>
                                </tr>
                            
                            </table>
                        
                    
                        </div>
                    </td>
                </tr>
            </table>
            <pagebreak />
            <table style="width:100%;border:1px solid #000;height:100%;padding-bottom:90px;padding-top:10px;padding-left:20px;padding-right:20px">
                <tr>
                <td>
                    <span style="padding:10px;">
                        <img style="width: 350px;margin-left:25%;" src="{{asset('admin/images/logo.jpg')}}"/>
                    </span>
                </td>
                </tr>
                <tr>
                    <td>
                        <table style="width:100%; border:2px solid #000; border-collapse: collapse; margin-top: 40px; text-align: center;">
                            <tbody>
                                <tr>
                                    <td style="padding:7px; background: #ccc; font-size: 30px; border: 1px solid #666; text-transform: uppercase;text-align: center;"> {{ Session::get('reportType') }} Background Report</td>
                                </tr>
                            </tbody>
                        </table>

                        <table style="width:100%; border:4px solid #fff; border-collapse: collapse; margin-top: 15px; text-align: center;">
                            <tbody>
                                <tr>
                                    <td style="padding:7px; font-size: 30px;text-align: center;"> Executive Summary </td>
                                </tr>
                            </tbody>
                        </table>
                        <?php 
                        
                            $service_item_arr=[];
                            if( count($report_items) > 0 ){
                                foreach($report_items as $item){
                                    $service_item_arr[] = $item->service_id;
                                }   
                            }
                            $service_item_num = array_count_values($service_item_arr);
                            $i=1;
                        ?>
                        <div class="page-break">
                            <table style="width:100%; border:2px solid #000; border-collapse: collapse; text-align: center;">
                                <tbody>
                                
                                        <tr>
                                            <td style="padding:7px; border:1px solid #666; background:#ccc; text-align:center;"><strong>S.No</strong></td>
                                            <td style="padding:7px; border:1px solid #666; background:#ccc; text-align:center;"><strong>Components</strong></td>
                                            <td style="padding:7px; border:1px solid #666; background:#ccc; text-align:center;"><strong>Status</strong></td>
                                            <!-- <td style="padding:7px; border:1px solid #666; background:#ccc; text-align:center;"><strong>Page Number</strong></td> -->
                                        </tr>
                                        @if( count($report_items) > 0 )
                                            @php
                                                $check_no = 1;
                                                $r_item_id = NULL;
                                            @endphp
                                            @foreach($report_items as $r_key => $item)
                                            <?php 
                                            $approval_status_name   = Helper::get_approval_status_name($item->id); 
                                            $input_item_data        = $item->jaf_data;
                                            $input_item_data_array  = json_decode($input_item_data, true);
                                            $verification_status    = Helper::get_report_verification_status($item->id);
                                            $particular             = "";
                                            $report_item_master = Helper::get_report_item_approval_status($item->id);
                                            $br = "";
                                            $m=0;
                                            foreach($input_item_data_array as $f_item){
                                                // print_r($f_item);die;
                                                if( array_key_exists('is_executive_summary',$f_item) ){
                                                    if($f_item['is_executive_summary'] == '1'){
                                                        if($m > 0){
                                                            $br = "<br/>";
                                                        }
                                                        $data       = array_keys($f_item); 
                                                        $data1      = array_values($f_item); 
                                                        $particular .= $br.$data1[0]." ";

                                                        $m++;
                                                    }   
                                                }
                                            }
                                            
                                                if($r_item_id!=NULL)
                                                {
                                                    $previous = Helper::get_report_item($r_item_id);
                                                    if($previous->service_id==$item->service_id)
                                                    {
                                                        
                                                        $check_no++;
                                                    }
                                                    else {
                                                        $check_no = 1;
                                                    }
                                                }
                                            ?>  
                                            <tr>
                                            <td style="padding:7px; border:1px solid #666; text-align:center;"><strong> {{$r_key + 1}}</strong></td> 
                                                <td style="padding:7px; border:1px solid #666; text-align:center;"><strong> {{$item->service_name.' - '. $check_no }}</strong></td>
                                                <!-- <td style="padding:7px; border:1px solid #666; text-align:center;"><strong> {{$item->service_name.' - '. $check_no }}</strong></td> -->
                                                <!-- <td style="padding:7px; border:1px solid #666; text-align:center;"> 
                                                    
                                                    @if(stripos($item->type_name,'drug_test_5')!==false)
                                                        5 Panel
                                                    @elseif(stripos($item->type_name,'drug_test_6')!==false)
                                                        6 Panel
                                                    @elseif(stripos($item->type_name,'drug_test_7')!==false)
                                                        7 Panel
                                                    @elseif(stripos($item->type_name,'drug_test_8')!==false)
                                                        8 Panel
                                                    @elseif(stripos($item->type_name,'drug_test_9')!==false)
                                                        9 Panel
                                                    @elseif(stripos($item->type_name,'drug_test_10')!==false)
                                                        10 Panel
                                                    @else
                                                        {!! $particular !!}
                                                    @endif
                                                </td> -->
                                                <!-- <td style="padding:7px; border:1px solid #666; text-align:center;">  -->
                                                    
                                                        @if( $item->service_id == 15 || $item->service_id == 16 ) 
                                                        
                                                                @if($item->approval_status_id == 4)
                                                                {{-- @php
                                                                dd($item->approval_status_id);
                                                                @endphp --}}
                                                                <td style="padding:7px; border:1px solid #666; text-align:center;background:{{$report_item_master!=NULL?$report_item_master->color_code:green}}"> 
                                                                    <span > {{ $approval_status_name }} </span> 
                                                                {{-- <span >No Record Found</span>  --}}
                                                                </td>
                                                                @else
                                                                <td style="padding:7px; border:1px solid #666; text-align:center;background:{{$report_item_master!=NULL?$report_item_master->color_code:white}}"> 
                                                                    <span > {{ $approval_status_name }} </span> 
                                                                </td>
                                                                @endif
                                                            {{-- @endif  --}}
                                                            {{-- @php
                                                                dd($verification_status);
                                                            @endphp --}}
                                                        @elseif(stripos($item->type_name,'drug_test_5')!==false)
                                                            @if($report_item_master!=NULL)
                                                            <td style="padding:7px; border:1px solid #666; text-align:center;"> 
                                                                <span style="color: {{$report_item_master->color_code}}">{{ucwords($report_item_master->color_name)}}</span>
                                                                </td>
                                                            @endif
                                                        @elseif($verification_status)
                                                                @if ( $verification_status == "4")

                                                                <td style="padding:7px; border:1px solid #666; text-align:center; background:{{$report_item_master->color_code}}"> 
                                                                    <span style=""> {{ $approval_status_name }} 
                                                                    
                                                                    </span> 
                                                                </td>

                                                                @endif

                                                                @if ( $verification_status == "2")
                                                                
                                                                    
                                                                <td style="padding:7px; border:1px solid #666; text-align:center; background:{{$report_item_master->color_code}}"> 
                                                                    <span style=""> {{ $approval_status_name }} 
                                                                    
                                                                    </span> 
                                                                </td>

                                                                @endif
                                                                @if ( $verification_status == "1")
                                                                <td style="padding:7px; border:1px solid #666; text-align:center; background:{{$report_item_master->color_code}}"> 
                                                                    <span style=""> {{ $approval_status_name }} 
                                                                    
                                                                    </span> 
                                                                </td>

                                                                @endif
                                                                @if ( $verification_status == "3")
                                                                <td style="padding:7px; border:1px solid #666; text-align:center; background:{{$report_item_master->color_code}}"> 
                                                                    <span  >{{ $approval_status_name }}
                                                                    
                                                                    </span> 
                                                                </td>

                                                                @endif

                                                                @if ( $verification_status == "5")
                                                                <td style="padding:7px; border:1px solid #666; text-align:center; background:{{$report_item_master->color_code}}">
                                                                    <span  >{{ $approval_status_name }}
                                                                    
                                                                    </span> 
                                                                </td>
                                                                @endif

                                                                @if ( $verification_status == "6")
                                                                <td style="padding:7px; border:1px solid #666; text-align:center; background:{{$report_item_master->color_code}}">
                                                                <span  >{{ $approval_status_name }}
                                                                
                                                                </span> 
                                                                </td>

                                                                @endif
                                                                @if ( $verification_status == "7")
                                                                <td style="padding:7px; border:1px solid #666; text-align:center; background:{{$report_item_master!=null ? $report_item_master->color_code : ''}}">
                                                                <span  >{{ $approval_status_name }}
                                                                
                                                                </span> 
                                                                </td>
                                                                

                                                                @endif
                                                        @else
                                                            Verification Pending
                                                        @endif
                                                                {{-- @if( $item->service_id == 15 || $item->service_id == 16 )  
                                                                    @if($item->approval_status_id == 4)
                                                                        No Record Found
                                                                        @else
                                                                        {{ $approval_status_name }} 
                                                                    @endif
                                                                
                                                                @elseif ($verification_status=='success')
                                                                    {{ $approval_status_name }} 
                                                                @else
                                                                    Verification Pending
                                                                @endif --}}
                                                                
                                                                {{-- @endif --}}
                                                <!-- </td> -->
                                        <!-- <td style="padding:7px; border:1px solid #666; text-align:center;"><strong> {PAGENO}</strong></td> -->

                                                <!-- <td>
                                                <indexinsert usedivletters="on" links="off" collation="es_ES.utf8"
                                                collation-group="Spanish_Spain" />
                                            </td> -->
                                            </tr>
                                            <?php $r_item_id = $item->id;?>
                                            @endforeach
                                        @endif
                                
                                {{-- @endif --}}
                                
                                </tbody>
                            </table>
                        </div>
                    </td>
                </tr>
            </table>                                   
            <!-- Check item table started here... -->
            @php  $i=1;  @endphp
            @if( count($report_items) > 0 )
                @php
                    $check_no = 1;
                    $r_item_id = NULL;
                @endphp
                @foreach($report_items as $r_key => $item)
                    @php 
                        $approval_status_name = Helper::get_approval_status_name($item->id); 
                        $input_item_data = $item->jaf_data;
                        $input_item_data_array =  (array) json_decode($input_item_data, true);   
                        
                        if($r_item_id!=NULL)
                        {
                            //dd($r_item_id);
                            $previous = Helper::get_report_item($r_item_id);

                            if($previous->service_id==$item->service_id)
                            {
                                $check_no++;
                            }
                            else {
                                $check_no = 1;
                            }
                        }
                    @endphp  
                    <!--  -->
                    <pagebreak />
                    <div class="page-break">
                        <table style="width:100%;border:1px solid #000;height:100%;padding-bottom:90px;padding-top:20px;padding-left:20px;padding-right:20px">
                            <tr>
                                <td>
                                    <table style="width:100%; border:4px solid #fff; border-collapse: collapse; margin-top: 1px;" autosize="1">
                                        <tbody>
                                            <tr>
                                                <td style="padding:7px; border:1px solid #666;">
                                                    {{-- <strong>{{$item->service_name.' '.Helper::get_check_item_number($item->report_id , $item->id)}}
                                                Verification</strong> --}}
                                                {{-- <strong>{{$item->service_name.' '.$item->service_item_order}}
                                                    Verification</strong> --}}
                                                    <strong>{{$item->service_name.' - '.$check_no}} 
                                                        Verification</strong>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <table style="width:100%; border:2px solid #000; border-collapse: collapse; text-align: center;" autosize="1">
                                        <tbody>
                                            @if( $item->service_id == 15 )
                                                <tr>
                                                    <td colspan="3" style="padding:7px; border:1px solid #666; background:#ccc; text-align:center"><strong> Criminal Check- (Law Firm)</strong></td>
                                                </tr>
                                            @elseif(stripos($item->type_name,'drug_test_5')!==false)
                                                <tr>
                                                    <td colspan="2" style="padding:7px; border:1px solid #666; background:#ccc; text-align:center;"><strong>Test Components Checked</strong></td>
                                                    <td style="padding:7px; border:1px solid #666; background:#ccc; text-align:center;"><strong>Verified Data</strong></td>
                                                </tr>          
                                            @else
                                                <tr>
                                                    <td colspan="2" style="padding:7px; border:1px solid #666; background:#ccc; text-align:center;"><strong>Details as per Subjects Application Form</strong></td>
                                                    <td style="padding:7px; border:1px solid #666; background:#ccc; text-align:center;"><strong>Verified Data</strong></td>
                                                </tr>
                                            @endif
                                            <!-- dynamic items -->
                                            <!-- check if not judis and criminal -->
                                            @if( $item->service_id == 15 )
                                            <tr>
                                                <td style="padding:7px; border:1px solid #666;"><strong>Criminal Proceedings</strong></td>
                                                <td colspan="2" style="padding:7px; border:1px solid #666;">Civil Proceedings: Original Suit, Miscellaneous Suit, Execution, Arbitration Cases & Criminal Proceedings: Criminal Petition, Criminal Appeal, Session Cases, Special Session Cases, Criminal, Miscellaneous Petition, Criminal Revision Appeal</td>
                                            </tr>
                                            @endif
                                            
                                            <!-- report table item -->
                                            @if(stripos($item->type_name,'drug_test_5')!==false)
                                                <tr>
                                                    <td style="padding:7px; border:1px solid #666; background:#ccc;"><strong>  Drug Test </strong></td>
                                                    <td style="padding:7px; border:1px solid #666; text-align:center;">
                                                        @php
                                                            $drug_test_name = Helper::drugTestName($item->service_id);
                                                        @endphp
                                                        @if(count($drug_test_name)>0)
                                                            @foreach ($drug_test_name as $d_item)
                                                                <p>{{$d_item->test_name}}</p>
                                                            @endforeach
                                                        @endif
                                                    </td>
                                                    <td style="padding:7px; border:1px solid #666; text-align:center">
                                                        @foreach($input_item_data_array  as $output_item)
                                                            <?php
                                                                $key_data = array_keys($output_item);
                                                                $val_data = array_values($output_item);
                                                            ?>
                                                            @if(stripos($key_data[0],'Result')!==false)
                                                                {{$val_data[0]!=NULL && $val_data[0]!='' ? ucwords($val_data[0]) : '--' }}
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="padding:7px; border:1px solid #666; background:#ccc;"><strong>Test Date</strong></td>
                                                    <td colspan="2" style="padding:7px; border:1px solid #666;">{{ $item->test_date!=NULL ? date('d F Y',strtotime($item->test_date)) : NULL }}</td>
                                                </tr>
                                            @else
                                            
                                                @foreach($input_item_data_array  as $output_item)
                                                
                                                    @if($output_item['is_report_output']=='1') 
                                                        <tr>  <?php $key_data = array_keys($output_item); ?> 
                                                            @if($key_data[0] =='Criminal Records Database Checks - India')
                                                                @continue
                                                            @elseif($key_data[0] =='Civil Litigation Database Checks – India')
                                                                @continue
                                                            @elseif($key_data[0] =='Credit and Reputational Risk Database Checks – India')
                                                                @continue
                                                            @elseif($key_data[0] =='Serious and Organized Crimes Database Checks – Global')
                                                                @continue 
                                                            @elseif($key_data[0] =='Global Regulatory Bodies')
                                                                @continue  
                                                            @elseif($key_data[0] =='Compliance Database')
                                                                @continue
                                                            @elseif($key_data[0] =='Sanction & PEP - Global')
                                                                @continue
                                                            @elseif($key_data[0] =='Web and Media Searches – Global')
                                                                @continue               
                                                            @else
                                                                <td  style="padding:7px; border:1px solid #666; background:#ccc;"> 
                                                                    <strong> {{ $key_data[0] }} </strong>
                                                                </td>
                                                            @endif
                                                            <td  style="padding:7px; border:1px solid #666; text-align:center;">
                                                            <?php $val_data = array_values($output_item); ?> {{ $val_data[0] }}
                                                            </td>
                                                            <td style="padding:7px; border:1px solid #666; text-align:center">
                                                            <?php   //print_r($output_item); 
                                                                $verificationcheckmode=array(1,10,11,15,16,17,28);

                                                                        $remarks = "-";
                                                                        
                                                                        if( array_key_exists('remarks', $output_item)){
                                                                            if($output_item['remarks'] !="-"){
                                                                                $remarks = "Yes";
                                                                            }  
                                                                        }
                                                                        if( array_key_exists('remarks_message', $output_item)){
                                                                            if($output_item['remarks_message']!=""){
                                                                                $remarks = $output_item['remarks_message'];
                                                                                if ($remarks=='clear') {

                                                                                    $remarks = 'Verified Clear';
                                                                                }
                                                                                if ($remarks=='no_record') {

                                                                                    $remarks = 'No Record Found';
                                                                                }
                                                                                if ($remarks=='unable_verify') {

                                                                                    $remarks = 'Unable To Verify';
                                                                                }
                                                                                if ($remarks=='stop') {

                                                                                    $remarks = 'Stop';
                                                                                }
                                                                                if ($remarks=='custom') {
                                                                                    if (array_key_exists('remarks_custom_message', $output_item)) {
                                                                                        if ($output_item['remarks_custom_message']!="") {
                                                                                            $remarks = $output_item['remarks_custom_message'];
                                                                                        }
                                                                                    
                                                                                    }
                                                                                }
                                                                            }
                                                                        }    
                                                            ?>
                                                                {{ $remarks }}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            @endif
                                            
                                            <!-- dynamic items end -->

                                            <tr>
                                                <td style="padding:7px; border:1px solid #666; background:#ccc;"><strong>Verified By</strong></td>
                                                <td colspan="2" style="padding:7px; border:1px solid #666;">{{ Helper::get_report_verified_by($item->id) }}</td>
                                            </tr>
                                            @if( $item->service_id == 15 )
                                            
                                                <tr>
                                                    <td width="30%" style="padding:7px; background:#ccc;border:1px solid #666;"><strong>Court</strong></td>
                                                    <td width="50%" style="padding:7px; background:#ccc;border:1px solid #666;"><strong>Court Name</strong></td>
                                                    <td width="20%" style="padding:7px; background:#ccc;border:1px solid #666;"><strong>Result</strong></td>
                                                </tr>
                                                <tr>
                                                    <td width="30%" style="padding:7px; border:1px solid #666;">District Court/ Lower Court/ Civil Court & Small Causes</td>
                                                    <td width="50%" style="padding:7px; border:1px solid #666;">District Courts of {{ $item->district_court_name }}</td>
                                                    <td width="20%" style="padding:7px; border:1px solid #666;">{{ $item->district_court_result }}</td>
                                                </tr>
                                                <tr>
                                                    <td width="30%" style="padding:7px; border:1px solid #666;">High Court</td>
                                                    <td width="50%" style="padding:7px; border:1px solid #666;">High Court of Jurisdiction at {{ $item->high_court_name }}</td>
                                                    <td width="20%" style="padding:7px; border:1px solid #666;">{{ $item->high_court_result }}</td>
                                                </tr>
                                                <tr>
                                                    <td width="30%" style="padding:7px; border:1px solid #666;">Supreme Court</td>
                                                    <td width="50%" style="padding:7px; border:1px solid #666;">Supreme Court of India, New Delhi</td>
                                                    <td width="20%" style="padding:7px; border:1px solid #666;">{{ $item->supreme_court_result }}</td>
                                                </tr>
                                            
                                            @endif
                                            <tr>
                                                <td style="padding:7px; border:1px solid #666; background:#ccc;"><strong>Comments</strong></td>
                                                <td colspan="2" style="padding:7px; border:1px solid #666;"> <p style="background:yellow;">{{ Helper::get_report_comments($item->id).' '.$item->annexure_value.'.' }} </p></td>
                                            </tr>
                                            <tr>
                                                <td style="padding:7px; border:1px solid #666; background:#ccc;"><strong>Additional Comments</strong></td>
                                                <td colspan="2" style="padding:7px; border:1px solid #666;">{{ Helper::get_report_additional_comments($item->id) }}</td> 
                                            </tr>
                                            @php
                                                $verificationcheckmode=array(1,10,11,15,16,17,28);
                                            @endphp
                                            @if(in_array($item->service_id, $verificationcheckmode))
                                            <tr>
                                                <td style="padding:7px; border:1px solid #666; background:#ccc;"><strong>Verification Mode</strong></td>
                                                <td colspan="2" style="padding:7px; border:1px solid #666;">{{ $item->verification_mode }}</td> 
                                            </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </table>
                        <br>
                
                        @if(stripos($item->type_name,'global_database')!==false)
                            <pagebreak />
                            @foreach($input_item_data_array  as $output_item)
                                @php
                                    $key_data = array_keys($output_item);
                                    $val_data = array_values($output_item);
                                @endphp
                                @if($key_data[0]=='Criminal Records Database Checks - India') 
                                    @php $key_data = array_keys($output_item); @endphp
                                    <table style="width:100%; border:2px solid #000; border-collapse: collapse; text-align: center;" autosize="1">
                                        <thead>
                                            <tr>
                                                <th><strong>Criminal Records Database Checks - India</strong></th>
                                            </tr>
                                        </thead> 
                                        <tbody>
                                                <tr>
                                                    <td width="70%" style="padding:7px; border:1px solid #666;">Central Bureau of Investigation Most Wanted List</td>
                                                    <td width="30%" style="padding:7px; border:1px solid #666;">
                                                        {{$val_data[0]}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="70%" style="padding:7px; border:1px solid #666;">Supreme Court of India</td>
                                                    <td width="30%" style="padding:7px; border:1px solid #666;">
                                                            {{$val_data[0]}}
                                                    </td> 
                                                </tr>
                                                <tr>
                                                    <td width="70%" style="padding:7px; border:1px solid #666;">High Court Records</td>
                                                    <td width="30%" style="padding:7px; border:1px solid #666;">
                                                            {{$val_data[0]}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="70%" style="padding:7px; border:1px solid #666;">Ministry of Defense</td>
                                                    <td width="30%" style="padding:7px; border:1px solid #666;"> 
                                                            {{$val_data[0]}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="70%" style="padding:7px; border:1px solid #666;">National Investigation Agency</td>
                                                    <td width="30%" style="padding:7px; border:1px solid #666;">
                                                        {{$val_data[0]}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="70%" style="padding:7px; border:1px solid #666;">Delhi Police</td>
                                                    <td width="30%" style="padding:7px; border:1px solid #666;">
                                                            {{$val_data[0]}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="70%" style="padding:7px; border:1px solid #666;">India Courts</td>
                                                    <td width="30%" style="padding:7px; border:1px solid #666;">
                                                        {{$val_data[0]}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="70%" style="padding:7px; border:1px solid #666;">Ministry of Home Affairs of India</td>
                                                    <td width="30%" style="padding:7px; border:1px solid #666;">
                                                        {{$val_data[0]}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="70%" style="padding:7px; border:1px solid #666;">India Narcotics Control Bureau</td>
                                                    <td width="30%" style="padding:7px; border:1px solid #666;">
                                                        {{$val_data[0]}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="70%" style="padding:7px; border:1px solid #666;">India Wildlife Crime Control Bureau</td>
                                                    <td width="30%" style="padding:7px; border:1px solid #666;">
                                                            {{$val_data[0]}}
                                                    </td>
                                                </tr>
                                        </tbody>
                                    </table>
                                    <br>
                                @elseif($key_data[0]=='Civil Litigation Database Checks – India') 
                                    <table style="width:100%; border:2px solid #000; border-collapse: collapse; text-align: center;" autosize="1">
                                        <thead>
                                            <tr>
                                                <th><strong>Civil Litigation Database Checks – India</strong></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Indian Politically Exposed Persons (PEP) Database</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Income Tax Department</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                        </tbody>
                                    </table>  
                                    <br>
                                @elseif($key_data[0]=='Credit and Reputational Risk Database Checks – India')
                                    <table style="width:100%; border:2px solid #000; border-collapse: collapse; text-align: center;" autosize="1">
                                        <thead>
                                            <tr>
                                                <th><strong>Credit and Reputational Risk Database Checks – India</strong></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Reserve Bank of India</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Securities and Exchange Board of India</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Ministry of Corporate Affairs of India - Vanishing companies & disqualified directors</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Insurance Regulatory and Development Authority</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Competition Commission of India</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                        </tbody>
                                    </table> 
                                    <br>
                                @elseif($key_data[0]=='Serious and Organized Crimes Database Checks – Global') 
                                    <table style="width:100%; border:2px solid #000; border-collapse: collapse; text-align: center;" autosize="1">
                                        <thead>
                                            <tr>
                                                <th><strong>Serious and Organized Crimes Database Checks – Global</strong></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Interpol Most Wanted</td>
                                                    <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>
                                                <td width="70%" style="padding:7px; border:1px solid #666;"><strong>US & Canada – Most Wanted Lists</strong></td>
                                                
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Drug Enforcement Administration, USA</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Federal Bureau of Investigation, USA [includes hijack suspects, most wanted & FBI seeking information]</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Most Wanted Fugitives: Texas Department of Public Safety, USA</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Immigration and Customs Enforcement, USA</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Secret Service, USA</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">United States Department of Justice (DOJ), USA</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">United States Marshals Service, USA</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Postal Inspection Service, USA</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Department of Defense, USA</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Department of State-Enforcement, USA</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Dept of State Foreign Terrorist Organizations, USA</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Dept of State Terrorist Exclusion List, USA</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Investigative Service Georgia</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Department of State, Narcotics Rewards Program, USA</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">US Bureau of International Narcotics and Law Enforcement</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Special Enforcement Units, Canada</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Royal Canadian Mounted Police, Canada</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Ontario Provincial Service, Canada</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">The Peel Regional Police, Canada</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Alberta Law Enforcement Response Teams, Canada</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Border Services Agency, Canada</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Combined Forces Special Enforcement Unit-British Columbia(CFSEU-BC), Canada</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td  width="70%" style="padding:7px; border:1px solid #666;">Edmonton Police Service, Canada</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">London Canada Police Service, Canada</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Niagara Regional Police Service, Canada</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">OSFI Enforcements, Canada</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">OSFI Anti-Terrorism, Canada</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Ottawa Police Service, Canada</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Permanent Anti-Corruption Unit, Canada</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Toronto Police Service, Canada</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">York Regional Police, Canada</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;"> <strong>Most Wanted Lists: Europe and Central Asia</strong></td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;"></td>
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">The Police of Saxony-Anhalt (Sachsen-Anhalt) County, Germany</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">German Federal Criminal Police Office, Germany</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Bayern Police, Germany</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Brandenburg Police, Germany</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Bremen Police, Germany</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Hamburg Police, Germany</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Mecklenburg-Vorpommern Police, Germany</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Niedersachsen Police, Germany</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Saarland Police, Germany</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Sachsen Police, Germany</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td>The Netherlands Police Department, The Netherlands</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">National Terrorism List, The Netherlands</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Netherlands Police</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Public Prosecution Service, The Netherlands</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Proscribed Organizations, United Kingdom</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">The Metropolitan Police Service, United Kingdom</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Serious Fraud Office, United Kingdom</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">National Crime Squad, United Kingdom</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Crimestoppers Trust, United Kingdom</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Crown Prosecution Service, United Kingdom</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">London Police, United Kingdom</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Scottish Crime and Drug Enforcement Agency, United Kingdom/td>
                                                    <td width="30%" style="padding:7px; border:1px solid #666;">
                                                        {{$val_data[0]}}
                                                    </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Serious Organized Crime Agency, United Kingdom</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">UK Border Agency, United Kingdom</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;"> Ministry of the Interior, Russia</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td>
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Chief Military Prosecutor, Russia</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Federal Penitentiary Service, Russia</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Federal Security Service of the Russian Federation (FSB) - Terrorist List, Russia</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;"><strong>Most Wanted Lists: Africa</strong></td>
                                                
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">South African Police Service, South Africa</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">National Prosecution Authority, South Africa</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;"><strong>Most Wanted Lists: Asia Pacific</strong></td>
                                                
                                            </tr>
                                            <tr>    
                                                <td>Australian National Security, Australia</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Australian Crime Commission, Australia</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Australian Customs and Border Protection Service, Australia</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">China Ministry of Public Security</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td>Central Commission for Discipline Inspection-Top 100 Fugitives, China</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Hong Kong Police Force, Hong Kong</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">National Police, Indonesia</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Hokkaido Prefecture Police, Japan</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Public Security Intelligence Agency, Japan</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Fukuoka Prefecture Police, Japan</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Japanese National Police Agency, Japan</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Yamagata Prefecture Police, Japan</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Royal Malaysian Police Force, Malaysia</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">New Zealand Police, New Zealand</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Federal Investigation Agency (FIA) - Govt. of Pakistan, Pakistan</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Punjab Police, Pakistan</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">National Bureau of Investigation, Philippines</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Philippine Drug Enforcement Agency, Philippines</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Philippine National Police, Philippines</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Police Force Case Studies, Singapore</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Corrupt Practices Investigation Bureau, Singapore</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Criminal Investigation Bureau, Taiwan</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Bureau of Investigation, Taiwan</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Ministry of National Defense of Taiwan, Taiwan</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Bureau of Investigation, Ministry of Justice, Taiwan</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                        </tbody>
                                    </table>
                                    <br> 
                                @elseif($key_data[0]=='Global Regulatory Bodies')
                                    <table style="width:100%; border:2px solid #000; border-collapse: collapse; text-align: center;" autosize="1">
                                        <thead>
                                        <tr>
                                            <th><strong>Global Regulatory Bodies</strong></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Bureau of Industry and Security</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>
                                                <td width="70%" style="padding:7px; border:1px solid #666;">World Bank Debarred Parties</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Defense Trade Controls (DTC) Debarred Parties</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;"><strong>US and Canadian Regulatory Bodies</strong></td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td>
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">New York Stock Exchange (NYSE), USA</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Commodities and Futures Trading Commission (CFTC), USA</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Excluded Parties List System [includes General Services Administration (GSA)], USA</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Federal Deposit and Insurance Corporation (FDIC), USA</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Federal Reserve Board (FRB), USA</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Financial Crimes Enforcement Network, USA</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                                <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">HHS-Office of Inspector General (OIG), USA</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Department of Health & Human Services, USA</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">National Credit Union Association (NCUA), USA</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Consumer Financial Protection Bureau, USA</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Office Comptroller of Currency (OCC), USA</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">US Securities and Exchange Commission, USA</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">New York State Insurance Department, USA</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">US State Attorneys General</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">US Office of Thrift Supervision</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">New York Department of Financial Services, USA</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Financial Industry Regulatory Authority, USA</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Alabama Securities Commission, USA</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Alaska Division of Banking, Securities and Corporations, USA</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Arizona Corporation Commission Securities Division, USA</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Arkansas Securities Department, USA</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">BIS Department of Commerce, USA</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">California Department of Insurance, USA</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Colorado Division of Securities, USA</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Department of State Directorate of Defense Trade Controls, USA</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Financial Fraud Enforcement Task Force/ StopFraud.gov, USA</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Florida Department of Financial Services, USA</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Illinois Securities Department, USA</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Kansas Securities Commission, USA</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Maine Securities Division, USA</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Massachusetts Securities Division, USA</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Michigan Department of Insurance and Financial Services, USA</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Missouri Secretary of State Securities Division, USA</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Nebraska Department of Banking and Finance, USA</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Nevada Secretary of State Securities Division, USA</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">New Jersey Bureau of Securities, USA</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">New Jersey Department of Banking & Insurance, USA</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Ohio Department of Commerce Securities Division, USA</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Oklahoma Securities Commission, USA</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Pennsylvania Banking and Securities Commission, USA</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Pennsylvania Department General Services, USA</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Tennessee Securities Division, USA</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Texas State Securities Board, USA</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">U.S Courts, USA</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Department of Justice, USA</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Department of Labor Office of Inspector General, USA</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Federal Trade Commission, USA</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Bureau of Industry and Security (BIS)–export violations, USA</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">US Food & Drug Administration</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Alberta Securities Commission, Canada</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">British Columbia Securities Commission (BCSC), Canada</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Investment Dealers Association of Canada (IDA), Canada</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Office of Superintendents of Financial Institutions (OSFI), Canada</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Ontario Securities Commission (OSC), Canada</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Canada Revenue Agency, Canada</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Cayman Islands Monetary Authority, Cayman Islands</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Central Bank of Bahamas, Bahamas</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Federal Court of Canada, Canada</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Financial Services Commission of Ontario, Canada</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Canadian Securities Administrators, Canada</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">New Brunswick Securities Commission, Canada</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Nova Scotia Securities Commission, Canada</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Tax Court of Canada, Canada</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;"><strong>European Regulatory Bodies</strong></td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td>
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Financial Services Authority (FSA), United Kingdom</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>

                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Lloyds of London (Lloyds), United Kingdom</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">HM Revenue and Customs, United Kingdom</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Financial Services Authority - Final Notice, United Kingdom</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Judiciary of Scotland, United Kingdom</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Financial Conduct Authority, United Kingdom</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Her Majesty's Courts Service, United Kingdom</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Home Office, United Kingdom</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Prudential Regulation Authority - Prohibited Individuals, United Kingdom</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Companies House - Disqualified directors, United Kingdom</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Serious Fraud Office, UK</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Department of Trade and Industry, United Kingdom</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Malta Financial Services Authority, Malta</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Netherlands Courts, Netherlands</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Netherlands Financial Intelligence Unit, Netherlands</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Supreme Court of the Netherlands, Netherlands</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">The Netherlands Authority for the Financial Markets, Netherlands</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Office of the Director of Corporate Enforcement (ODCE), Ireland</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Revenue Commissioners - Irish Tax & Customs, Ireland</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">The Irish Financial Services Regulatory Authority, Ireland</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">The Financial Supervision Commission, Isle of Man</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Institute for Supervision of Insurance, Italy</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Italian Securities Commission (Consob), Italy</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Jersey Financial Securities Commission, Jersey</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Courts, Jersey</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Gibraltar Financial Services Commission, Gibraltar</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;"><strong>Asia Pacific Regulatory Bodies</strong></td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td>
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Australian Stock Exchange, Australia</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Australian Securities and Investment Commission (ASIC), Australia</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Australian Securities Exchange</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Department of Foreign Affairs and Trade, Australia</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Bangladesh Securities and Commission, Bangladesh</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Insurance Regulatory Commission, China</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Securities Association of China, China</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Supreme People's Court, China</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td>CSRC (China Securities Regulatory Commission), China</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Hong Kong Securities & Futures Commission (HKSFC), Hong Kong</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Hong Kong Monetary Authority – Warnings, Hong Kong</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Independent Commission against Corruption, Hong Kong</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Securities and Futures Exchanges, Hong Kong</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td  width="70%" style="padding:7px; border:1px solid #666;">Indonesian Financial Services Authority</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Ministry of Economy, Trade and Industry, Japan</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Japanese Financial Services Agency, Japan</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Financial Supervisory Service, Korea Republic</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Malaysia Securities Commission (MSC), Malaysia</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">The Companies Commission of Malaysia, Malaysia</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Bursa Malaysia</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Courts of Malaysia (Judgments list), Malaysia</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Anti-Corruption Commission, Malaysia</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">New Zealand Securities Commission (NZSC), New Zealand</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">New Zealand Serious Fraud Office, New Zealand</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Reserve Bank, New Zealand</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Securities Exchange Commission of Pakistan (SECP), Pakistan</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Philippines Securities and Exchange Commission, Philippines</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Singapore Stock Exchange, Singapore</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Supreme Court, Singapore</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Ministry of Law, Singapore</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Customs, Singapore</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Inland Revenue Authority, Singapore</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Ministry of Manpower, Singapore</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Thailand Securities and Exchange Commission, Thailand</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Anti-Money Laundering Office, Thailand</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Banking Bureau of Financial Supervisory Commission, Taiwan</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Financial Supervisory Commission, Taiwan</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Taiwan Supreme Prosecutors Office, Taiwan</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            
                                        </tbody>
                                    </table> 
                                    <br>
                                @elseif($key_data[0]=='Compliance Database')
                                    <table style="width:100%; border:2px solid #000; border-collapse: collapse; text-align: center;" autosize="1">
                                        <thead>
                                            <tr>
                                                <th><strong>Compliance Database</strong></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Office of Foreign Assets Control (OFAC): Specially Designated Nationals & Blocked Persons and names that have been deleted from the OFAC list</td>
                                                    <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Monetary Authority of Singapore</td>
                                                    <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td>
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Hong Kong Monetary Authority</td>
                                                    <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td>
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Australian Department of Foreign Affairs and Trade (DFAT)</td>
                                                    <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td>
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">The Australian Transaction Reports and Analysis Centre, Australia</td>
                                                    <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td>
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">OSFI Consolidated List, Canada</td>
                                                    <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td>
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">United Nations International Criminal Tribunal for the Former Yugoslavia</td>
                                                    <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td>
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">International Criminal Tribunal for Rwanda</td>
                                                    <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td>
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Global Money Laundering Database</td>
                                                    <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td>
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Politically Exposed Persons Database</td>
                                                    <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td>
                                            </tr>
                    
                                        </tbody>
                                    </table> 
                                    
                                @elseif($key_data[0]=='Sanction & PEP - Global')  
                                    <table style="width:100%; border:2px solid #000; border-collapse: collapse; text-align: center;" autosize="1">
                                        <thead>
                                            <tr>
                                                <th><strong>Sanction & PEP - Global</strong></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td width="70%" style="padding:7px; border:1px solid #666;">US Department of State - Iran and Syria Nonproliferation</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>
                                                <td width="70%" style="padding:7px; border:1px solid #666;">US Department of State - Iran, North Korea, and Syria Nonproliferation</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Minister of Foreign Affairs -Special Economic Measures -Syria, Canada</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>    
                                                <td width="70%" style="padding:7px; border:1px solid #666;">US Iran and Syria Nonproliferation Act</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            
                                        </tbody>
                                    </table> 
                                    
                                @elseif($key_data[0]=='Web and Media Searches – Global')
                                    <table style="width:100%; border:2px solid #000; border-collapse: collapse; text-align: center;" autosize="1">
                                        <thead>
                                            <tr>
                                                <th><strong>Web and Media Searches – Global</strong></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Internet Searches</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            <tr>
                                                <td width="70%" style="padding:7px; border:1px solid #666;">Media Searches</td>
                                                <td width="30%" style="padding:7px; border:1px solid #666;">
                                                    {{$val_data[0]}}
                                                </td> 
                                            </tr>
                                            
                                        </tbody>
                                    </table> 
                                    
                                @endif    
                                
                            @endforeach
                        @endif 
                        
                        @if($item->service_id == 17)
                            @if($item->reference_form_data!=NULL && $item->reference_type!=NULL)
                                <pagebreak />
                                <table style="width:100%; border:2px solid #000; border-collapse: collapse; text-align: center;" autosize="1">
                                    <tbody>
                                        <tr>
                                            <td colspan="2" style="padding:7px; border:1px solid #666; background:#ccc; text-align:center;"><strong>Checks</strong></td>
                                            <td style="padding:7px; border:1px solid #666; background:#ccc; text-align:center;"><strong>Status</strong></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="padding:7px; border:1px solid #666;text-align:center;"><strong>Reference Check</strong></td>
                                            <td style="padding:7px; border:1px solid #666;background:#CAFF33;text-align:center;"><strong>Positive Feedback</strong></td>
                                        </tr> 
                                    </tbody>
                                </table>
                                <table style="width:100%; border:2px solid #000; border-collapse: collapse; text-align: center; margin-top:5%;" autosize="1">
                                    <tbody>
                                        <tr>
                                            <td colspan="3" style="padding:7px; border:1px solid #666; background:#ccc; text-align:center;"><strong> {{ ucwords($item->reference_type) }} Reference Check</strong></td>
                                        </tr>
                                        <tr>
                                            <td style="padding:7px; border:1px solid #666;text-align:center;"><strong>Status</strong></td>
                                            <td colspan="2" style="padding:7px; border:1px solid #666;background:#CAFF33;text-align:center;"><strong>Positive Feedback</strong></td>
                                        </tr>
                                        <tr>
                                            <td style="padding:7px; border:1px solid #666; background:#ccc; text-align:center;"><strong> Verified Data</strong></td>
                                            <td style="padding:7px; border:1px solid #666; background:#ccc; text-align:center;"><strong> Details Provided by the Subject </strong></td>
                                            <td style="padding:7px; border:1px solid #666; background:#ccc; text-align:center;"><strong> Verified Data</strong></td>
                                        </tr>
                                        @if(stripos($item->reference_type,'professional')!==false)
                                            <?php 
                                                $input_data=$item->jaf_data;
                                                $reference_input_data = $item->reference_form_data;
                                                $input_data_array = json_decode($input_data,true);
                                                $reference_input_data_array = json_decode($reference_input_data,true);
                                            ?>
                                            @foreach ($input_data_array as $key => $input)
                                                <?php 
                                                    $key_val = array_keys($input); $input_val = array_values($input);

                                                    $remarks = "-";

                                                    if( array_key_exists('remarks', $input)){
                                                        if($input['remarks'] !="-"){
                                                            $remarks = "Yes";
                                                        }  
                                                    }
                                                ?>

                                                @if(!(stripos($key_val[0],'First Name')!==false || stripos($key_val[0],'Last Name')!==false || stripos($key_val[0],'Reference Type (Personal / Professional)')!==false || stripos($key_val[0],'Referee Email id')!==false))
                                                    <tr>
                                                        <td style="padding:7px; border:1px solid #666; text-align:center;"><strong> {{ $key_val[0] }}</strong></td>
                                                        <td style="padding:7px; border:1px solid #666; text-align:center;"> {{ $input_val[0] }} </td>
                                                        <td style="padding:7px; border:1px solid #666; text-align:center;"> {{ $remarks }} </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                            <tr>
                                                <td colspan="2" style="padding:7px; border:1px solid #666; background:#ccc; text-align:center;"><strong>Verified Data</strong></td>
                                                <td style="padding:7px; border:1px solid #666; background:#ccc; text-align:center;"><strong>Referee Comments</strong></td>
                                            </tr>
                                            @foreach ($reference_input_data_array as $key => $input)
                                                <?php 
                                                    $key_val = array_keys($input); $input_val = array_values($input);
                                                ?>
                                                @if(stripos($key_val[0],'Mode of Verification')!==false || stripos($key_val[0],'Remarks')!==false)
                                                    <tr>
                                                        <td style="padding:7px; border:1px solid #666;text-align:center;"><strong>{{ $key_val[0] }}</strong></td>
                                                        <td colspan="2" style="padding:7px; border:1px solid #666;text-align:center;">{{ $input_val[0] }}</td>
                                                    </tr>
                                                @else
                                                    <tr>
                                                        <td colspan="2" style="padding:7px; border:1px solid #666;text-align:center;"><strong>{{ $key_val[0] }}</strong></td>
                                                        <td style="padding:7px; border:1px solid #666;text-align:center;">{{ $input_val[0] }}</td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        @elseif (stripos($item->reference_type,'personal')!==false)
                                            <?php 
                                                $input_data=$item->jaf_data;
                                                $reference_input_data = $item->reference_form_data;
                                                $input_data_array = json_decode($input_data,true);
                                                $reference_input_data_array = json_decode($reference_input_data,true);
                                            ?>

                                            @foreach ($input_data_array as $key => $input)
                                                <?php 
                                                    $key_val = array_keys($input); $input_val = array_values($input);

                                                    $remarks = "-";

                                                    if( array_key_exists('remarks', $input)){
                                                        if($input['remarks'] !="-"){
                                                            $remarks = "Yes";
                                                        }  
                                                    }
                                                ?>
                                                @if(stripos($key_val[0],'Referee Name')!==false)
                                                    <tr>
                                                        <td style="padding:7px; border:1px solid #666; text-align:center;"><strong> {{ $key_val[0] }}</strong></td>
                                                        <td style="padding:7px; border:1px solid #666; text-align:center;"> {{ $input_val[0] }} </td>
                                                        <td style="padding:7px; border:1px solid #666; text-align:center;"> {{ $remarks }} </td>
                                                    </tr>
                                                @endif
                                            @endforeach

                                            @foreach ($reference_input_data_array as $key => $input)
                                                <?php 
                                                    $key_val = array_keys($input); $input_val = array_values($input);
                                                ?>
                                                @if(stripos($key_val[0],'Referee Relation')!==false)
                                                    <tr>
                                                        <td style="padding:7px; border:1px solid #666; text-align:center;"><strong> {{ $key_val[0] }}</strong></td>
                                                        <td style="padding:7px; border:1px solid #666; text-align:center;"> {{ $input_val[0] }} </td>
                                                        <td style="padding:7px; border:1px solid #666; text-align:center;">Yes </td>
                                                    </tr>
                                                @endif
                                            @endforeach

                                            @foreach ($input_data_array as $key => $input)
                                                <?php 
                                                    $key_val = array_keys($input); $input_val = array_values($input);

                                                    $remarks = "-";

                                                    if( array_key_exists('remarks', $input)){
                                                        if($input['remarks'] !="-"){
                                                            $remarks = "Yes";
                                                        }  
                                                    }
                                                ?>
                                                @if(stripos($key_val[0],'Referee Contact Number')!==false)
                                                    <tr>
                                                        <td style="padding:7px; border:1px solid #666; text-align:center;"><strong> {{ $key_val[0] }}</strong></td>
                                                        <td style="padding:7px; border:1px solid #666; text-align:center;"> {{ $input_val[0] }} </td>
                                                        <td style="padding:7px; border:1px solid #666; text-align:center;"> {{ $remarks }} </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                            
                                            <tr>
                                                <td colspan="2" style="padding:7px; border:1px solid #666; background:#ccc; text-align:center;"><strong>Verified Data</strong></td>
                                                <td style="padding:7px; border:1px solid #666; background:#ccc; text-align:center;"><strong>Referee Comments</strong></td>
                                            </tr>

                                            @foreach ($reference_input_data_array as $key => $input)
                                                <?php 
                                                    $key_val = array_keys($input); $input_val = array_values($input);
                                                ?>
                                                @if(stripos($key_val[0],'Mode of Verification')!==false || stripos($key_val[0],'Remarks')!==false)
                                                    <tr>
                                                        <td style="padding:7px; border:1px solid #666;text-align:center;"><strong>{{ $key_val[0] }}</strong></td>
                                                        <td colspan="2" style="padding:7px; border:1px solid #666;text-align:center;">{{ $input_val[0] }}</td>
                                                    </tr>
                                                @else
                                                    @if(!(stripos($key_val[0],'Referee Relation')!==false))
                                                        <tr>
                                                            <td colspan="2" style="padding:7px; border:1px solid #666;text-align:center;"><strong>{{ $key_val[0] }}</strong></td>
                                                            <td style="padding:7px; border:1px solid #666;text-align:center;">{{ $input_val[0] }}</td>
                                                        </tr>
                                                    @endif
                                                @endif
                                            @endforeach

                                        @endif 
                                    </tbody>
                                </table>
                            @endif
                        @endif

                        <!-- ./ check ite close here  -->

                        <!-- additional Address -->
                        @if($item->service_id == 1)
                            @if ($report_add_page)
                                @if ($report_add_page->status == 'enable')
                                <pagebreak />
                                @php
                                    $additional_data =  Helper::get_additional_address_data($candidate->id,$item->id);
                                @endphp    
                        
                                <table style="width:100%; border:2px solid #000; border-collapse: collapse; text-align: center; margin-top:15px;" autosize="1">
                                    <tbody>
                                        <tr>
                                            <td  style="padding:7px; border:1px solid #666; background:#ccc; text-align: center;  width: 50%; "><strong>Details</strong></td>
                                            <td style="padding:7px; border:1px solid #666; background:#ccc; text-align:center;  width: 50%;"><strong>Findings</strong></td>
                                        </tr>
                                        <tr>
                                            <td  style="padding:7px; border:1px solid #666; background:#ccc; "><strong>Name of the Person Contacted and
                                                Contact Number</strong></td>
                                            <td style="padding:7px; border:1px solid #666; text-align:center;"> @if ($additional_data) {{($additional_data->contact_person_name || $additional_data->contact_contact_no)  ? $additional_data->contact_person_name .','.' '. $additional_data->contact_contact_no :'N/A'}}@endif</td>
                                        </tr>
                                        <tr>
                                            <td  style="padding:7px; border:1px solid #666; background:#ccc; "><strong>Relationship with the associate</strong></td>
                                            <td style="padding:7px; border:1px solid #666; text-align:center;">@if ($additional_data){{ $additional_data->relation_with_associate ? $additional_data->relation_with_associate : 'N/A' }} @endif</td>
                                        </tr>
                                        <tr>
                                            <td  style="padding:7px; border:1px solid #666; background:#ccc;"><strong>Residence Status</strong></td>
                                            <td style="padding:7px; border:1px solid #666; text-align:center;">@if ($additional_data){{$additional_data->residence_status ? $additional_data->residence_status:'N/A' }}@endif</td>
                                        </tr>
                                        <tr>
                                            <td  style="padding:7px; border:1px solid #666; background:#ccc; "><strong>Locality</strong></td>
                                            <td style="padding:7px; border:1px solid #666; text-align:center;">@if ($additional_data){{ $additional_data->locality ? $additional_data->locality : 'N/A' }} @endif</td>
                                        </tr>
                                        <tr>
                                            <td  style="padding:7px; border:1px solid #666; background:#ccc; "><strong>Additional Comments <small>(If any)</small> </strong></td>
                                            <td style="padding:7px; border:1px solid #666; text-align:center;">@if ($additional_data){{ $additional_data->comments ? $additional_data->comments : 'N/A' }} @endif</td>
                                        </tr>
                                        <tr>
                                            <td  style="padding:7px; border:1px solid #666; background:#ccc;"><strong>Verified By</strong></td>
                                            <td style="padding:7px; border:1px solid #666; text-align:center;">@if ($additional_data){{ $additional_data->verified_by ? $additional_data->verified_by : 'N/A' }} @endif</strong></td>
                                        </tr>
                                        <tr>
                                            <td  style="padding:7px; border:1px solid #666; background:#ccc; "><strong>Mode of the Verification</strong></td>
                                            <td style="padding:7px; border:1px solid #666; text-align:center;">@if ($additional_data){{  $additional_data->mode_of_verification ? $additional_data->mode_of_verification :'N/A' }}@endif</td>
                                        </tr>
                                        <tr>
                                            <td  style="padding:7px; border:1px solid #666; background:#ccc; "><strong>Remarks </strong></td>
                                            <td style="padding:7px; border:1px solid #666; text-align:center;">@if ($additional_data){{ $additional_data->remarks ? $additional_data->remarks: 'N/A' }} @endif</td>
                                        </tr>
                                    </tbody>
                                </table>   
                                @endif
                            @endif 
                        @endif  

                        <!-- check if not judis -->
                        @if($item->service_id != 16)
                            <!-- imaages  -->
                            @php 
                                $y =1; $num = 1; $count = ""; $item_files = Helper::getReportAttachFiles($item->id,'main'); 
                                //print_r($item_files); 
                            @endphp
                            @if(count($item_files) > 0)
                                @if($item->service_id != 15)
                                    <pagebreak />
                                @endif

                                @foreach($item_files as $file)
                                    @php 
                                        $attached_file_id=$file['attached_file_id']; 
                                        $attached_files = Helper::getAttachedFileName($attached_file_id); 
                                        //print_r($attached_files); 
                                    @endphp
                                    @if($file['attachment_type'] == 'main')
                                        @foreach($attached_files as $afile)
                                            @if($num == 1)
                                                @if($file['attached_file_name']==null)
                                                    <p style="width:100%; text-align:center;"> <span class="filename" style="font-size:30px;">{{$afile->attachment_name}}</span></p>
                                                @else
                                                    <p style="width:100%; text-align:center;"><span class="filename"  style="font-size:30px;">{{$file['attached_file_name']}}</span></p>
                                                @endif
                                            @elseif($num > 1)
                                                <pagebreak />
                                                @if($file['attached_file_name']!=null)
                                                    <p style="width:100%; text-align:center;"><span class="filename"  style="font-size:30px;">{{$file['attached_file_name']}}</span></p>
                                                @else
                                                    <p style="width:100%; text-align:center;"> <span class="filename" style="font-size:30px;">{{$afile->attachment_name}}</span></p>    
                                                @endif
                                            @endif
                                            @if(stripos($file['file_name'],'pdf')!==false)
                                                {!!Helper::reportPdfToImage($item->id,$file['file_name'],Auth::user()->id)!!}
                                                @if($item->service_id==1)
                                                    <pagebreak />
                                                @endif
                                            @else
                                                <div style="border:solid 1px #333; text-align:center; margin-top:15px;">
                                                    <img src="{{ $file['fileIcon'] }}" style='width:100%; height: 100%; margin:10px 0; ' >
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                    <?php $y++; $num++; $count++;?>
                                @endforeach
                            @endif

                            @php  $snum=1; $item_file = Helper::getReportAttachFiles($item->id,'supporting'); @endphp
                            @if(count($item_file) > 0)
                                @if($item->service_id != 15)
                                    <pagebreak />
                                @endif
                                @foreach($item_file as $files)
                                    <?php $attached_file_id=$files['attached_file_id']; $attached_files = Helper::getAttachedFileName($attached_file_id); //dd($item_file); ?>
                                    <!-- supportings -->
                                    @if($files['attachment_type'] == 'supporting')
                                        @foreach($attached_files as $afile)
                                                @if($snum == 1)
                                                    @if($files['attached_file_name']==null)
                                                        <p style="width:100%; text-align:center;"><span class="filename" style="font-size:30px;">{{$afile->attachment_name}}</span></p>
                                                    @else
                                                        <p style="width:100%; text-align:center;"><span class="filename" style="font-size:30px;">{{$files['attached_file_name']}}</span></p>
                                                    @endif
                                                @elseif($snum > 1)
                                                    
                                                    <pagebreak />
                                                    @if($files['attached_file_name']!=null)
                                                        <p style="width:100%; text-align:center;"><span class="filename" style="font-size:30px;">{{$files['attached_file_name']}}</span></p>
                                                    @else
                                                        <p style="width:100%; text-align:center;"><span class="filename" style="font-size:30px;">{{$afile->attachment_name}}</span></p>
                                                    @endif
                                                @endif
                                                @if(stripos($files['file_name'],'pdf')!==false)
                                                    {!!Helper::reportPdfToImage($item->id,$files['file_name'],Auth::user()->id)!!}
                                                    @if($item->service_id==1)
                                                        <pagebreak />
                                                    @endif
                                                @else
                                                    <div style="border:solid 1px #333; text-align:center; margin-top:15px;">
                                                        <img src="{{ $files['fileIcon'] }}" style='width:100%; height: 100%; margin:10px 0; ' >
                                                    </div>
                                                @endif
                                                <?php  $snum++;?>
                                        @endforeach
                                    @endif
                                    <!-- supporting -->
                                @endforeach
                            @endif
                            <!-- images -->
                            
                        @endif
                        <!-- if not judis -->

                         <!-- if judis -->
                         @if($item->service_id == 16)
                            <!-- imaages  -->
                            <?php $y =1; $count = 1; $num = 1; $count = ""; $item_files = Helper::getReportAttachFiles($item->id,'main'); //print_r($item_files); ?>
                            @if(count($item_files) > 0)
                                <pagebreak />
                                @foreach($item_files as $file)
                                <?php $attached_file_id=$file['attached_file_id']; $attached_files = Helper::getAttachedFileName($attached_file_id); //print_r($attached_files); ?>

                                    @if($file['attachment_type'] == 'main')
                                    @foreach($attached_files as $afile)
                                        @if($num == 1)
                                        @if($file['attached_file_name']==null)
                                            <p style="width:100%; text-align:center;"><span class="filename" style="font-size:30px;">{{$afile->attachment_name}}</span></p>
                                        @else
                                            <p style="width:100%; text-align:center;"><span class="filename" style="font-size:30px;">{{$file['attached_file_name']}}</span></p>
                                        @endif
                                        @elseif($num > 1)
                                            @if( $num % 2 == 0)
                                                <pagebreak />
                                                @if($file['attached_file_name']!=null)
                                                <p style="width:100%; text-align:center;"><span class="filename" style="font-size:30px;">{{$file['attached_file_name']}}</span></p>
                                                @else
                                                <p style="width:100%; text-align:center;"><span class="filename" style="font-size:30px;">{{$afile->attachment_name}}</span></p>
                                                @endif
                                                @endif
                                        @endif
                                        @if(stripos($file['file_name'],'pdf')!==false)
                                            {!!Helper::reportPdfToImage($item->id,$file['file_name'],Auth::user()->id)!!}
                                            @if($item->service_id==1)
                                                <pagebreak />
                                            @endif
                                        @else
                                            <div style="border:solid 1px #333; text-align:center; margin-top:15px;">
                                                <img src="{{ $file['fileIcon'] }}" style='width:100%; height: 100%; margin:10px 0; ' >
                                            </div>
                                        @endif

                                        {{-- <div style="border:solid 1px #333; text-align:center; margin-top:15px;">
                                            <img src="{{ $file['filePath'] }}" style="width: 160mm; height: 85mm; margin: 0;" /> 
                                        </div>   --}}
                                    @endforeach
                                    @endif
                                    <!-- main file -->
                                    <?php $y++; $num++; $count++;?>
                                    @endforeach
                            @endif
                                        <?php  $snum=1; $item_file = Helper::getReportAttachFiles($item->id,'supporting'); ?>
                            @if(count($item_file) > 0)
                        
                                @foreach($item_file as $files)
                                <?php $attached_file_id=$files['attached_file_id']; $attached_files = Helper::getAttachedFileName($attached_file_id); //dd($item_file); ?>
                                    <!-- supportings -->
                                    @if($files['attachment_type'] == 'supporting')
                                    @foreach($attached_files as $afile)
                                            @if($snum == 1)
                                            @if($files['attached_file_name']==null)
                                                <p style="width:100%; text-align:center;"><span class="filename" style="font-size:30px;"> {{$afile->attachment_name}}</span></p>
                                            @else
                                                <p style="width:100%; text-align:center;"><span class="filename" style="font-size:30px;"> {{$file['attached_file_name']}}</span></p>
                                            @endif
                                            @elseif($snum > 1)
                                                @if( $snum % 2 == 0)
                                                <pagebreak />
                                                    @if($file['attached_file_name']!=null)
                                                        <p style="width:100%; text-align:center;"><span class="filename" style="font-size:30px;"> {{$file['attached_file_name']}}</span></p>
                                                    @else
                                                        <p style="width:100%; text-align:center;"><span class="filename" style="font-size:30px;"> {{$afile->attachment_name}}</span></p>
                                                    @endif
                                                @endif
                                            @else  
                                            
                                            @endif
                                            @if(stripos($files['file_name'],'pdf')!==false)
                                                {!!Helper::reportPdfToImage($item->id,$files['file_name'],Auth::user()->id)!!}
                                                @if($item->service_id==1)
                                                    <pagebreak />
                                                @endif
                                            @else
                                                <div style="border:solid 1px #333; text-align:center; margin-top:15px;">
                                                    <img src="{{ $files['fileIcon'] }}" style='width:100%; height: 100%; margin:10px 0; ' >
                                                </div>
                                            @endif
                                            <?php  $snum++;?>
                                            {{-- <div style="border:solid 1px #333; text-align:center; margin-top:15px;">
                                                <img src="{{ $file['filePath'] }}" style="width: 160mm; height: 85mm; margin: 0;" /> 
                                            </div>   --}}  
                                        @endforeach   
                                    @endif
                                    <!-- supporting -->
                                
                                @endforeach
                            @endif
                            <!-- images -->
                         @endif
                         <!-- ./ if judis -->

                    </div>
                    <?php  $x++;  ?>
                    <!-- row close -->
                    <?php $r_item_id = $item->id;?>
                @endforeach
            @endif
            <!-- particular block -->
            <pagebreak />
            <table style="width:100%;border:1px solid #000;height:100%;padding-bottom:90px;padding-top:20px;padding-left:20px;padding-right:20px">
                <tr>
                    <td><span style="padding:10px;">
                        <img style="width: 350px;margin-left:25%;" src="{{asset('admin/images/logo.jpg')}}"/>
                    </span></td>
                </tr>   
                <tr>
                    <td style="width:100%; text-align:center; padding:20px 0px;">
                        <p style="font-size:30px;">Restrictions & Limitations:</p>
                    </td>
                </tr>
                <tr style="text-align:justify;">
                    <td style="text-align:justify;"> 
                        <p style="font-size: 14px; text-align:justify !important;">
                            Our reports and comments are confidential in nature and are meant only for the internal use of the
                            client to make an assessment of the background of the applicant.They are not intended for publication
                            or circulation or sharing with any other person including the applicant.Also, they are not to be
                            reproduced or used for any other purpose, in whole or in part, without our prior written consent in each
                            specific instance. We request you to recognize that we are not the source of the data gathered and our
                            findings are based on the information made available to us; therefore, we cannot guarantee the
                            accuracy of the information collected. Should additional information or documentation become
                            available to us, which impacts the conclusions reached in our reports, we reserve the right to amend
                            our findings in our report accordingly.We expressly disclaim all responsibility or liability for any costs,
                            damages, losses, liabilities, expenses incurred by anyone as a result of circulation, publication,
                            reproduction or use of our reports contrary to the provisions of this paragraph. You will appreciate that
                            due to factors beyond our control, it may be possible that we are unable to get all the necessary
                            information.Because of the limitations mentioned above, the results of our work with respect to the
                            background checks should be considered only as a guide. Our reports and comments should not be
                            considered as a definitive pronouncement on the individual.
                        </p>
                        <br><br>
                    </td>
                </tr>
                <tr>
                    <td style="text-align:center;">
                        <h3 style="text-align:center;"> - End of Report -</h3>
                        <!--  -->
                    </td>
                </tr>
                <tr>
                    <td style="text-align:center;">
                        <table>
                                <tr>
                                    <td class="mark_print"><img style="width: 200px;" src="{{asset('admin/images/mark.jpeg')}}"/></td>
                                    <td class="signature"><img style="" src="{{asset('admin/images/signature.jpeg')}}"/></td>
                                </tr>
                                <tr>
                                    
                                    <td colspan=2 class="detailcss">
                                        <p>Name: Jayanesh Jayan</p>
                                        <p>Designation: Manager Operations Clobminds </p>
                                    </td>
                                </tr>
                            
                        </table>
                    </td>
                </tr>
            </table>
        </div>
  </body>
</html>