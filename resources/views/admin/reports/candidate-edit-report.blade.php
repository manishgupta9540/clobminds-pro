@extends('layouts.admin')
@section('content')
<style>
    .disabled-link{
       pointer-events: none;
    }
    .disabled-link-1{
       pointer-events: none;
    }
    .sweet-alert button.cancel {
        background: #DD6B55 !important;
    }

    .remove-image
    {
        padding: 0px 3px 0px;
    }
    .filename{
      font-size: 10px;
    }
    select#service_select {
    -webkit-appearance: auto;
    appearance: auto;
}
    .image-area img{
        height: 100px;
        width: 100px;
        padding: 8px;
    }

    .image-area{
        width: 90px;
    }

    .remove-image:hover
    {
        padding: 0px 3px 0px;
    }
    
    .gallery ul{
    margin:0;
    padding:0;
    list-style-type:none;
}
.gallery ul li{
    padding:7px;
    border:2px solid #ccc;
    float:left;
    margin:10px 7px;
    background:none;
    width:auto;
    height:auto;
}
.modal-body.gallery-model {
    min-height: 400px;
    overflow:auto;
}
.gallery img{
    width:133px;
}
.modal-part1 {
    max-width: 72%!important;
   
}

#preview{
        /* overflow-x: hidden; */
        /* overflow-y: hidden; */
        z-index: 999;
        padding-top: 0px;
        /* margin:auto; */
    }
#preview .modal-dialog.modal-lg{
  max-width: 90% !important;
  width: 100%;
  padding: 0px;
  left: 3.5%;
}

#preview .modal-content {
  margin: auto;
  display: block;
  width: 100%;
  max-width: 1270px;
}
table, th, td {
  border: 1px solid black;
  border-collapse: collapse;
}
 </style>
<div class="main-content-wrap sidenav-open d-flex flex-column">
<!-- ============ Body content start ============= -->
<div class="main-content">
    <div class="row">
        <div class="col-sm-11">
            <ul class="breadcrumb">
            <li><a href="{{ url('/home') }}">Dashboard</a></li> 
            <li><a href="{{ url('/reports') }}">Reports</a></li>
            <li>Edit</li>
            </ul>
        </div>
        <!-- ============Back Button ============= -->
        <div class="col-sm-1 back-arrow">
            <div class="text-right">
            <a href="{{ url()->previous() }}"><i class="fas fa-arrow-circle-left fa-2x"></i></a>
            </div>
        </div>
    </div>
<div class="row">
   <div class="card text-left">
      <div class="card-body">
         <div class="row">
            <div class="col-md-6">
               <h4 class="card-title mb-1">Update Report: <b> {{ $candidate->name }} ({{Helper::user_reference_id($candidate->id)}}) </b> </h4>
               <p>Update report items- comments and supportings etc.</p>
            </div>
            <div class="col-md-6">
                <p class="text-danger" style="font-size: 12px;">Note :- Please ensure about the data verified for each check's data. because it will be count in billing items, if "Data Verified" is check marked then it will count in Billing-Invoice.</p>
            </div>
            <div class="col-md-12">
               <form class="mt-2" method="post" action="{{ url('/reports/item/update') }}" id="report_form">
                @csrf
                <!-- candidate info -->
                <input type="hidden" name="report_id" value="{{ base64_encode($report_id) }}">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group">
                                <label>Name : <strong>{{ $candidate->name }} </strong></label>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Phone : <strong>+{{$candidate->phone_code}}-{{ $candidate->phone }}</strong></label>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                <label>Email : <strong>{{ $candidate->email!=NULL ? $candidate->email : 'N/A' }}</strong> </label>
                                </div>
                            </div>
                        </div>
                        <p class="pb-border"></p>
                        @php
                            $job_item = Helper::get_job_items($candidate->id,$candidate->business_id);
                        @endphp
                        <div class="row">
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-12">
                                        <h4 class="card-title pt-2">SLA Details</h4>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                        <label>SLA Name: <strong>{{ $job_item->sla_title}} </strong></label>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                        <label>Internal TAT : <strong>{{ $job_item->tat}} @if($job_item->tat > 1) days @else day @endif </strong></label>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Client TAT : <strong>{{ $job_item->client_tat}} @if($job_item->client_tat > 1) days @else day @endif</strong></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                        <label>Price Type : <strong>{{ ucfirst($job_item->price_type.'-'.'Wise') }} </strong></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-12">
                                        <h4 class="card-title pt-2">Case Details</h4>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Case Initiated : <strong>{{date('d-M-Y h:i A',strtotime($candidate->created_at))}}</strong></label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>BGV Filled : <strong>{{date('d-M-Y h:i A',strtotime($job_item->filled_at))}}</strong></label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Report Generated : <strong>{{$report->complete_created_at!=NULL ? date('d-M-Y h:i A',strtotime($report->complete_created_at)) : date('d-M-Y h:i A',strtotime($report->generated_at))}}</strong></label>
                                        </div>
                                    </div>
                                    @if($report->is_report_complete==1)
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Report Completed : <strong>{{$report->report_complete_created_at!=NULL ? date('d-M-Y h:i A',strtotime($report->report_complete_created_at)) : '--'}}</strong></label>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="row">
                     <div class="col-md-12">
                         <hr>
                     </div>
                </div>    --}}
            
                <!-- service item -->
                @if( count($report_items) >0  )
                    @php
                        $check_no = 1;
                        $r_item_id = NULL;
                    @endphp
                @foreach($report_items as $r_key => $item)
                    <!--  -->
                  <div class="row" style="border: 1px solid #ddd; margin:10px 0; padding: 10px 0;">
                     <div class="col-md-6">
                         <div class="row">
                            @php
                                $report_check = Helper::get_report_item_services($item->candidate_id,$item->service_id);
                                $report_check_count = count($report_check);
                            @endphp
                             <div class="col-9">
                                {{-- <h3 class="mb-2 mt-2">Verification - {{$item->service_name.' - '.$item->service_item_number}} </h3> --}}
                                {{-- <h3 class="mb-2 mt-2">Verification - {{$item->service_name.' - '.$item->service_item_order}} </h3> --}}
                                    @php
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
                                    <h3 class="mb-2 mt-2">Verification - {{$item->service_name.' - '.$check_no}} </h3>
                                    <p>{{Helper::user_reference_id($candidate->id).'-'.$item->short_name.' - '.$check_no}}</p>
                             </div>
                             <div class="col-2">
                                 
                                <label> Check Order </label>
                                <select class="form-control check-order" name="check-order-{{$item->id}}">
                                    {{-- <option value="">--Select--</option> --}}
                                    @for ($c=1; $c<=$report_check_count;$c++)
                                        <option value="{{$c}}" @if($c==$item->service_item_order) selected @endif>{{$c}}</option>
                                    @endfor
                                </select>
                             </div>
                         </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <p>Provide the approval and comments (Remarks: Checked = Yes, Left Blank = -)</p>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <div class="form-check">
                                        <label class="check-inline">
                                            <input type="checkbox" data-id="{{ $item->id }}" name="verified-input-checkbox-{{ $item->id}}" class="form-check-input verified_data" @if ($item->is_data_verified=='1') checked  disabled @endif><span style="font-size: 14px;">Data Verified ?</span>
                                        </label>
                                    </div>
                                    @if($item->is_data_verified=='1')
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group text-muted">
                                                    <span>(<strong>{{Helper::user_name($item->verified_data_submitted_by)}}</strong>, <strong>{{$item->data_verified_date!=NULL ? date('d-M-Y h:i A',strtotime($item->data_verified_date)) : '--'}}</strong>)</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <!--  -->
                        <?php 
                            $input_item_data = $item->jaf_data;
                            $reference_item_data = $item->reference_form_data;
                            $input_item_data_array =  json_decode($input_item_data, true); 
                            $i=0;
                            $k=0;
                            $l=0;

                            $country_name = Helper::get_country_list();
                            // echo "<pre>"; print_r($input_item_data_array);
                            // dd($input_item_data);
                        ?>
                        @foreach($input_item_data_array as $key => $input)
                            <!-- start row -->
                            <?php 
                            //  dd($input);
                            // echo "<pre>"; print_r($input); ?>
                            <div class="row" >
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <?php $key_val = array_keys($input); 
                                        //print_r($key_val);
                                        $input_val = array_values($input);
                                        
                                        $is_executive_summary ="0";
                                        // $is_executive_summary = Helper::get_is_executive_summary($item->service_id,$key_val[0]);
                                        if(array_key_exists('is_executive_summary',$input))
                                        {
                                            $is_executive_summary = $input['is_executive_summary'];
                                        }
                                        ?>

                                        @if($item->service_id==17)
                                            @if(stripos($key_val[0],'Reference Type (Personal / Professional)')!==false)
                                                <label>  {{ $key_val[0]}} <span class="text-danger">*</span></label>
                                                <input type="hidden" name="service-input-label-{{ $item->id.'-'.$i }}" value="{{ $key_val[0]}}">
                                                <select class="form-control service-input-value-{{$item->id.'-'.$i}} reference_type error-control" name="service-input-value-{{ $item->id.'-'.$i }}" data-id="{{base64_encode($item->id)}}" data-report="{{$item->id}}">
                                                    <option value="">--Select--</option>
                                                    <option @if(stripos($input_val[0],'personal')!==false) selected @endif value="personal">Personal</option>
                                                    <option @if(stripos($input_val[0],'professional')!==false) selected @endif value="professional">Professional</option>
                                                </select>
                                            @else
                                                <label>  {{ $key_val[0]}} </label>
                                                <input type="hidden" name="service-input-label-{{ $item->id.'-'.$i }}" value="{{ $key_val[0]}}">
                                                <input class="form-control error-control" type="text" name="service-input-value-{{ $item->id.'-'.$i }}" value="{{ $input_val[0] }}">
                                            @endif
                                        @elseif(stripos($item->type_name,'drug_test_5')!==false || stripos($item->type_name,'drug_test_10')!==false)
                                            @if (stripos($key_val[0],'Test Name')!==false)
                                                <label>  {{ $key_val[0]}} </label><br>
                                                <input type="hidden" name="service-input-label-{{ $item->id.'-'.$i }}" value="{{ $key_val[0]}}">
                                                <input class="form-control error-control" type="hidden" name="service-input-value-{{ $item->id.'-'.$i }}" value="{{ $input_val[0] }}">
                                                @php
                                                    $drug_test_name = Helper::drugTestName($item->service_id);
                                                @endphp
                                                @if(count($drug_test_name)>0)
                                                    @foreach ($drug_test_name as $d_item)
                                                        <div class="form-check form-check-inline disabled-link-1">
                                                            <input class="form-check-input test-name-{{$item->id.'-'.$i}}" type="checkbox" name="test-name-{{$item->id.'-'.$i}}[]" value="{{$d_item->test_name}}" checked readonly>
                                                            <label class="form-check-label" for="inlineCheckbox-1">{{$d_item->test_name}}</label>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            @elseif(stripos($key_val[0],'Result')!==false)
                                                <label>  {{ $key_val[0]}} </label>
                                                <input type="hidden" name="service-input-label-{{ $item->id.'-'.$i }}" value="{{ $key_val[0]}}">
                                                <select class="form-control service-input-value-{{$item->id.'-'.$i}}" name="service-input-value-{{ $item->id.'-'.$i }}" >
                                                    <option value="">--Select--</option>
                                                    <option @if(stripos($input_val[0],'positive')!==false) selected @endif value="positive">Positive</option>
                                                    <option @if(stripos($input_val[0],'negative')!==false) selected  @endif value="negative">Negative</option>
                                                </select>
                                            @else
                                                <label>  {{ $key_val[0]}} </label>
                                                <input type="hidden" name="service-input-label-{{ $item->id.'-'.$i }}" value="{{ $key_val[0]}}">
                                                <input class="form-control error-control" type="text" name="service-input-value-{{ $item->id.'-'.$i }}" value="{{ $input_val[0] }}">         
                                            @endif
                                        @elseif($item->service_id==15)
                                            @if ($key_val[0]=='Address Type')
                                               <label>  {{ $key_val[0]}} </label><br>
                                               <input type="hidden" name="service-input-label-{{ $item->id.'-'.$i }}" value="{{ $key_val[0]}}">
                                               <select class="form-control service-input-value-{{$item->id.'-'.$i}} " name="service-input-value-{{ $item->id.'-'.$i }}" >
                                                     <option value="">--Select--</option>
                                                     <option @if(stripos($input_val[0],'current')!==false) selected @endif value="current">Current</option>
                                                     <option @if(stripos($input_val[0],'permanent')!==false) selected  @endif value="permanent">Permanent</option>
                                                     <option @if(stripos($input_val[0],'current_permanent')!==false) selected  @endif value="current_permanent">Current + Permanent</option>
                                                     <option @if(stripos($input_val[0],'previous')!==false) selected  @endif value="previous">Previous</option>
                                                    </select> 
                                            @else
                                               <label>  {{ $key_val[0]}} </label><br>
                                               <input type="hidden" name="service-input-label-{{ $item->id.'-'.$i }}" value="{{ $key_val[0]}}">
                                               <input class="form-control  service-input-value-{{$item->id.'-'.$i}} "  type="text" name="service-input-value-{{ $item->id.'-'.$i }}" value="{{ $input_val[0] }}" >
                                            @endif
                                        @elseif($item->type_name=='global_database')
                                            @if ($key_val[0]=='Country')
                                            <label>  {{ $key_val[0]}} </label><br>
                                            <input type="hidden" name="service-input-label-{{ $item->id.'-'.$i }}" value="{{ $key_val[0]}}">
                                                <select class="form-control service-input-value-{{$item->id.'-'.$i}} " name="service-input-value-{{ $item->id.'-'.$i }}" >
                                                    @foreach ($country_name as $country) 
                                                        <option  value="{{$country->name}}" {{ $country->name ==  $input_val[0] ? 'selected' : '' }}>{{$country->name}}</option>
                                                    @endforeach    
                                                </select> 
                                        @elseif ($key_val[0]=='Criminal Records Database Checks - India')
                                                <input type="hidden" name="service-input-label-{{ $item->id.'-'.$i }}" value="{{ $key_val[0]}}">
                                                <input class="form-control error-control" type="hidden" name="service-input-value-{{ $item->id.'-'.$i }}" id="inputhidden" value="{{ $input_val[0] }}">
                                                    <div id="accordion" role="tablist" aria-multiselectable="true">
                                                        <div class="card">
                                                            <div class="card-header" role="tab" id="headingOne">
                                                                <div class="mb-0">
                                                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne" class="collapsed">
                                                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                                                        <label> <h6> {{ $key_val[0]}} </h6></label><br> 
                                                                    </a>
                                                                </div>
                                                            </div>
                                                            <div id="collapseOne" class="collapse" role="tabpanel" aria-labelledby="headingOne" aria-expanded="false" style="">
                                                                <div class="card-block">
                                                                    <table class="table">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>Central Bureau of Investigation Most Wanted List</td>
                                                                                <td class="record">No Record</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Supreme Court of India</td>
                                                                                <td class="record">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>High Court Records</td>
                                                                                <td class="record">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Ministry of Defense</td>
                                                                                <td class="record">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>National Investigation Agency</td>
                                                                                <td class="record">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>National Crime Records Bureau</td>
                                                                                <td class="record">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Delhi Police</td>
                                                                                <td class="record">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>India Courts</td>
                                                                                <td class="record">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Ministry of Home Affairs of India</td>
                                                                                <td class="record">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>India Narcotics Control Bureau</td>
                                                                                <td class="record">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>India Wildlife Crime Control Bureau</td>
                                                                                <td class="record">No Record</td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>  
                                                    </div>
                                                @elseif ($key_val[0]=='Civil Litigation Database Checks – India')
                                                    <input type="hidden" name="service-input-label-{{ $item->id.'-'.$i }}" value="{{ $key_val[0]}}">
                                                    <input class="form-control error-control" type="hidden" name="service-input-value-{{ $item->id.'-'.$i }}" id="civilhidden" value="{{ $input_val[0] }}">
                                                    <div id="accordion" role="tablist" aria-multiselectable="true">
                                                        <div class="card">
                                                            <div class="card-header" role="tab" id="headingtwo">
                                                                <div class="mb-0">
                                                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapsetwo" aria-expanded="false" aria-controls="collapsetwo" class="collapsed">
                                                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                                                        <label> <h6> {{ $key_val[0]}} </h6></label><br> 
                                                                    </a>
                                                                </div>
                                                            </div>
                                                            <div id="collapsetwo" class="collapse" role="tabpanel" aria-labelledby="headingtwo" aria-expanded="false" style="">
                                                                <div class="card-block">
                                                                    <table class="table">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>Indian Politically Exposed Persons (PEP) Database</td>
                                                                                <td class="database">No Record</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Income Tax Department</td>
                                                                                <td class="database">No Record</td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>  
                                                    </div>
                                                @elseif ($key_val[0]=='Credit and Reputational Risk Database Checks – India')
                                                    <input type="hidden" name="service-input-label-{{ $item->id.'-'.$i }}" value="{{ $key_val[0]}}">
                                                    <input class="form-control error-control" type="hidden" name="service-input-value-{{ $item->id.'-'.$i }}" id="creadithidden" value="{{ $input_val[0] }}">
                                                    <div id="accordion" role="tablist" aria-multiselectable="true">
                                                        <div class="card">
                                                            <div class="card-header" role="tab" id="headingthree">
                                                                <div class="mb-0">
                                                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapsethree" aria-expanded="false" aria-controls="collapsethree" class="collapsed">
                                                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                                                        <label> <h6> {{ $key_val[0]}} </h6></label><br> 
                                                                    </a>
                                                                </div>
                                                            </div>
                                                            <div id="collapsethree" class="collapse" role="tabpanel" aria-labelledby="headingthree" aria-expanded="false" style="">
                                                                <div class="card-block">
                                                                    <table class="table">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>Reserve Bank of India</td>
                                                                                <td class="reputational">No Record</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Securities and Exchange Board of India</td>
                                                                                <td class="reputational">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Ministry of Corporate Affairs of India - Vanishing companies & disqualified directors</td>
                                                                                <td class="reputational">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Insurance Regulatory and Development Authority</td>
                                                                                <td class="reputational">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Competition Commission of India</td>
                                                                                <td class="reputational">No Record</td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>  
                                                    </div>
                                                @elseif ($key_val[0]=='Serious and Organized Crimes Database Checks – Global')
                                                    <input type="hidden" name="service-input-label-{{ $item->id.'-'.$i }}" value="{{ $key_val[0]}}">
                                                    <input class="form-control error-control" type="hidden" name="service-input-value-{{ $item->id.'-'.$i }}" id="inputserious" value="{{ $input_val[0] }}">
                                                    <div id="accordion" role="tablist" aria-multiselectable="true">
                                                        <div class="card">
                                                            <div class="card-header" role="tab" id="headingfour">
                                                                <div class="mb-0">
                                                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapsefour" aria-expanded="false" aria-controls="collapsefour" class="collapsed">
                                                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                                                        <label> <h6> {{ $key_val[0]}} </h6></label><br> 
                                                                    </a>
                                                                </div>
                                                            </div>
                                                            <div id="collapsefour" class="collapse" role="tabpanel" aria-labelledby="headingfour" aria-expanded="false" style="">
                                                                <div class="card-block">
                                                                    <table class="table">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>Interpol Most Wanted</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td><strong>US & Canada – Most Wanted Lists</strong></td>
                                                                                {{-- <td class="organized">No Record</td> --}}
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Drug Enforcement Administration, USA</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Federal Bureau of Investigation, USA [includes hijack suspects, most wanted & FBI seeking information]</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Most Wanted Fugitives: Texas Department of Public Safety, USA</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Immigration and Customs Enforcement, USA</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Secret Service, USA</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>United States Department of Justice (DOJ), USA</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>United States Marshals Service, USA</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Postal Inspection Service, USA</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Department of Defense, USA</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Department of State-Enforcement, USA</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Dept of State Foreign Terrorist Organizations, USA</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Dept of State Terrorist Exclusion List, USA</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Investigative Service Georgia</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Department of State, Narcotics Rewards Program, USA</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>US Bureau of International Narcotics and Law Enforcement</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Special Enforcement Units, Canada</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Royal Canadian Mounted Police, Canada</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Ontario Provincial Service, Canada</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>The Peel Regional Police, Canada</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Alberta Law Enforcement Response Teams, Canada</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Border Services Agency, Canada</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Combined Forces Special Enforcement Unit-British Columbia(CFSEU-BC), Canada</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Edmonton Police Service, Canada</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>London Canada Police Service, Canada</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Niagara Regional Police Service, Canada</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>OSFI Enforcements, Canada</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>OSFI Anti-Terrorism, Canada</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Ottawa Police Service, Canada</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Permanent Anti-Corruption Unit, Canada</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Toronto Police Service, Canada</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>York Regional Police, Canada</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td><strong>Most Wanted Lists: Europe and Central Asia</strong></td>
                                                                                {{-- <td class="organized">No Record</td> --}}
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>The Police of Saxony-Anhalt (Sachsen-Anhalt) County, Germany</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>German Federal Criminal Police Office, Germany</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Bayern Police, Germany</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Brandenburg Police, Germany</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Bremen Police, Germany</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Hamburg Police, Germany</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Mecklenburg-Vorpommern Police, Germany</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Niedersachsen Police, Germany</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Saarland Police, Germany</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Sachsen Police, Germany</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>The Netherlands Police Department, The Netherlands</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>National Terrorism List, The Netherlands</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Netherlands Police</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Public Prosecution Service, The Netherlands</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Proscribed Organizations, United Kingdom</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>The Metropolitan Police Service, United Kingdom</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Serious Fraud Office, United Kingdom</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>National Crime Squad, United Kingdom</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Crimestoppers Trust, United Kingdom</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Crown Prosecution Service, United Kingdom</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>London Police, United Kingdom</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Scottish Crime and Drug Enforcement Agency, United Kingdom/td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Serious Organized Crime Agency, United Kingdom</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>UK Border Agency, United Kingdom</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Ministry of the Interior, Russia</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Chief Military Prosecutor, Russia</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Federal Penitentiary Service, Russia</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Federal Security Service of the Russian Federation (FSB) - Terrorist List, Russia</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td><strong>Most Wanted Lists: Africa</strong></td>
                                                                                {{-- <td class="organized">No Record</td> --}}
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>South African Police Service, South Africa</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>National Prosecution Authority, South Africa</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td><strong>Most Wanted Lists: Asia Pacific</strong></td>
                                                                                {{-- <td class="organized">No Record</td> --}}
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Australian National Security, Australia</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Australian Crime Commission, Australia</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Australian Customs and Border Protection Service, Australia</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>China Ministry of Public Security</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Central Commission for Discipline Inspection-Top 100 Fugitives, China</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Hong Kong Police Force, Hong Kong</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>National Police, Indonesia</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Hokkaido Prefecture Police, Japan</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Public Security Intelligence Agency, Japan</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Fukuoka Prefecture Police, Japan</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Japanese National Police Agency, Japan</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Yamagata Prefecture Police, Japan</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Royal Malaysian Police Force, Malaysia</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>New Zealand Police, New Zealand</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Federal Investigation Agency (FIA) - Govt. of Pakistan, Pakistan</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Punjab Police, Pakistan</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>National Bureau of Investigation, Philippines</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Philippine Drug Enforcement Agency, Philippines</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Philippine National Police, Philippines</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Police Force Case Studies, Singapore</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Corrupt Practices Investigation Bureau, Singapore</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Criminal Investigation Bureau, Taiwan</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Bureau of Investigation, Taiwan</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Ministry of National Defense of Taiwan, Taiwan</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Bureau of Investigation, Ministry of Justice, Taiwan</td>
                                                                                <td class="organized">No Record</td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>  
                                                    </div>
                                                @elseif ($key_val[0]=='Global Regulatory Bodies')
                                                    <input type="hidden" name="service-input-label-{{ $item->id.'-'.$i }}" value="{{ $key_val[0]}}">
                                                    <input class="form-control error-control" type="hidden" name="service-input-value-{{ $item->id.'-'.$i }}" id="inputglobal" value="{{ $input_val[0] }}">
                                                    <div id="accordion" role="tablist" aria-multiselectable="true">
                                                        <div class="card">
                                                            <div class="card-header" role="tab" id="headingOne">
                                                                <div class="mb-0">
                                                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne" class="collapsed">
                                                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                                                        <label> <h6> {{ $key_val[0]}} </h6></label><br> 
                                                                    </a>
                                                                </div>
                                                            </div>
                                                            <div id="collapseOne" class="collapse" role="tabpanel" aria-labelledby="headingOne" aria-expanded="false" style="">
                                                                <div class="card-block">
                                                                    <table class="table">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>Bureau of Industry and Security</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>World Bank Debarred Parties</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Defense Trade Controls (DTC) Debarred Parties</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td><strong>US and Canadian Regulatory Bodies</strong></td>
                                                                                {{-- <td class="bodies">No Record</td> --}}
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>New York Stock Exchange (NYSE), USA</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Commodities and Futures Trading Commission (CFTC), USA</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Excluded Parties List System [includes General Services Administration (GSA)], USA</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Federal Deposit and Insurance Corporation (FDIC), USA</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Federal Reserve Board (FRB), USA</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Financial Crimes Enforcement Network, USA</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>HHS-Office of Inspector General (OIG), USA</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Department of Health & Human Services, USA</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>National Credit Union Association (NCUA), USA</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Consumer Financial Protection Bureau, USA</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Office Comptroller of Currency (OCC), USA</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>US Securities and Exchange Commission, USA</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>New York State Insurance Department, USA</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>US State Attorneys General</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>US Office of Thrift Supervision</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>New York Department of Financial Services, USA</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Financial Industry Regulatory Authority, USA</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Alabama Securities Commission, USA</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Alaska Division of Banking, Securities and Corporations, USA</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Arizona Corporation Commission Securities Division, USA</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Arkansas Securities Department, USA</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>BIS Department of Commerce, USA</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>California Department of Insurance, USA</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Colorado Division of Securities, USA</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Department of State Directorate of Defense Trade Controls, USA</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Financial Fraud Enforcement Task Force/ StopFraud.gov, USA</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Florida Department of Financial Services, USA</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Illinois Securities Department, USA</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Kansas Securities Commission, USA</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Maine Securities Division, USA</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Massachusetts Securities Division, USA</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Michigan Department of Insurance and Financial Services, USA</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Missouri Secretary of State Securities Division, USA</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Nebraska Department of Banking and Finance, USA</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Nevada Secretary of State Securities Division, USA</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>New Jersey Bureau of Securities, USA</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>New Jersey Department of Banking & Insurance, USA</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Ohio Department of Commerce Securities Division, USA</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Oklahoma Securities Commission, USA</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Pennsylvania Banking and Securities Commission, USA</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Pennsylvania Department General Services, USA</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Tennessee Securities Division, USA</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Texas State Securities Board, USA</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>U.S Courts, USA</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Department of Justice, USA</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Department of Labor Office of Inspector General, USA</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Federal Trade Commission, USA</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Bureau of Industry and Security (BIS)–export violations, USA</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>US Food & Drug Administration</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Alberta Securities Commission, Canada</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>British Columbia Securities Commission (BCSC), Canada</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Investment Dealers Association of Canada (IDA), Canada</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Office of Superintendents of Financial Institutions (OSFI), Canada</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Ontario Securities Commission (OSC), Canada</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>Canada Revenue Agency, Canada</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Cayman Islands Monetary Authority, Cayman Islands</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Central Bank of Bahamas, Bahamas</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Federal Court of Canada, Canada</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Financial Services Commission of Ontario, Canada</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Canadian Securities Administrators, Canada</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>New Brunswick Securities Commission, Canada</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Nova Scotia Securities Commission, Canada</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Tax Court of Canada, Canada</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td><strong>European Regulatory Bodies</strong></td>
                                                                                {{-- <td class="bodies">No Record</td> --}}
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Financial Services Authority (FSA), United Kingdom</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>

                                                                            <tr>    
                                                                                <td>Lloyds of London (Lloyds), United Kingdom</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>HM Revenue and Customs, United Kingdom</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Financial Services Authority - Final Notice, United Kingdom</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Judiciary of Scotland, United Kingdom</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Financial Conduct Authority, United Kingdom</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Her Majesty's Courts Service, United Kingdom</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Home Office, United Kingdom</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Prudential Regulation Authority - Prohibited Individuals, United Kingdom</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Companies House - Disqualified directors, United Kingdom</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Serious Fraud Office, UK</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Department of Trade and Industry, United Kingdom</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Malta Financial Services Authority, Malta</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Netherlands Courts, Netherlands</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Netherlands Financial Intelligence Unit, Netherlands</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Supreme Court of the Netherlands, Netherlands</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>The Netherlands Authority for the Financial Markets, Netherlands</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Office of the Director of Corporate Enforcement (ODCE), Ireland</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Revenue Commissioners - Irish Tax & Customs, Ireland</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>The Irish Financial Services Regulatory Authority, Ireland</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>The Financial Supervision Commission, Isle of Man</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Institute for Supervision of Insurance, Italy</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Italian Securities Commission (Consob), Italy</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Jersey Financial Securities Commission, Jersey</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Courts, Jersey</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Gibraltar Financial Services Commission, Gibraltar</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td><strong>Asia Pacific Regulatory Bodies</strong></td>
                                                                                {{-- <td class="bodies">No Record</td> --}}
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Australian Stock Exchange, Australia</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Australian Securities and Investment Commission (ASIC), Australia</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Australian Securities Exchange</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Department of Foreign Affairs and Trade, Australia</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Bangladesh Securities and Commission, Bangladesh</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Insurance Regulatory Commission, China</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Securities Association of China, China</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Supreme People's Court, China</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>CSRC (China Securities Regulatory Commission), China</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Hong Kong Securities & Futures Commission (HKSFC), Hong Kong</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Hong Kong Monetary Authority – Warnings, Hong Kong</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Independent Commission against Corruption, Hong Kong</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Securities and Futures Exchanges, Hong Kong</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Indonesian Financial Services Authority</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Ministry of Economy, Trade and Industry, Japan</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Japanese Financial Services Agency, Japan</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Financial Supervisory Service, Korea Republic</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Malaysia Securities Commission (MSC), Malaysia</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>The Companies Commission of Malaysia, Malaysia</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Bursa Malaysia</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Courts of Malaysia (Judgments list), Malaysia</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Anti-Corruption Commission, Malaysia</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>New Zealand Securities Commission (NZSC), New Zealand</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>New Zealand Serious Fraud Office, New Zealand</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Reserve Bank, New Zealand</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Securities Exchange Commission of Pakistan (SECP), Pakistan</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Philippines Securities and Exchange Commission, Philippines</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Singapore Stock Exchange, Singapore</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Supreme Court, Singapore</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Ministry of Law, Singapore</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Customs, Singapore</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Inland Revenue Authority, Singapore</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Ministry of Manpower, Singapore</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Thailand Securities and Exchange Commission, Thailand</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Anti-Money Laundering Office, Thailand</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Banking Bureau of Financial Supervisory Commission, Taiwan</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Financial Supervisory Commission, Taiwan</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Taiwan Supreme Prosecutors Office, Taiwan</td>
                                                                                <td class="bodies">No Record</td>
                                                                            </tr>
                                                                           
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>  
                                                    </div>
                                                @elseif ($key_val[0]=='Compliance Database')
                                                    <input type="hidden" name="service-input-label-{{ $item->id.'-'.$i }}" value="{{ $key_val[0]}}">
                                                    <input class="form-control error-control" type="hidden" name="service-input-value-{{ $item->id.'-'.$i }}" id="inputcompliance" value="{{ $input_val[0] }}">
                                                    <div id="accordion" role="tablist" aria-multiselectable="true">
                                                        <div class="card">
                                                            <div class="card-header" role="tab" id="headingfive">
                                                                <div class="mb-0">
                                                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapsefive" aria-expanded="false" aria-controls="collapsefive" class="collapsed">
                                                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                                                        <label> <h6> {{ $key_val[0]}} </h6></label><br> 
                                                                    </a>
                                                                </div>
                                                            </div>
                                                            <div id="collapsefive" class="collapse" role="tabpanel" aria-labelledby="headingfive" aria-expanded="false" style="">
                                                                <div class="card-block">
                                                                    <table class="table">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>Office of Foreign Assets Control (OFAC): Specially Designated Nationals & Blocked Persons and names that have been deleted from the OFAC list</td>
                                                                                <td class="compliancedata">No Record</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Monetary Authority of Singapore</td>
                                                                                <td class="compliancedata">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Hong Kong Monetary Authority</td>
                                                                                <td class="compliancedata">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Australian Department of Foreign Affairs and Trade (DFAT)</td>
                                                                                <td class="compliancedata">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>The Australian Transaction Reports and Analysis Centre, Australia</td>
                                                                                <td class="compliancedata">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>OSFI Consolidated List, Canada</td>
                                                                                <td class="compliancedata">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>United Nations International Criminal Tribunal for the Former Yugoslavia</td>
                                                                                <td class="compliancedata">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>International Criminal Tribunal for Rwanda</td>
                                                                                <td class="compliancedata">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Global Money Laundering Database</td>
                                                                                <td class="compliancedata">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Politically Exposed Persons Database</td>
                                                                                <td class="compliancedata">No Record</td>
                                                                            </tr>
                                                    
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>  
                                                    </div>
                                                 @elseif ($key_val[0]=='Sanction & PEP - Global')
                                                    <input type="hidden" name="service-input-label-{{ $item->id.'-'.$i }}" value="{{ $key_val[0]}}">
                                                    <input class="form-control error-control" type="hidden" name="service-input-value-{{ $item->id.'-'.$i }}" id="inputsanction" value="{{ $input_val[0] }}">
                                                    <div id="accordion" role="tablist" aria-multiselectable="true">
                                                        <div class="card">
                                                            <div class="card-header" role="tab" id="headingsix">
                                                                <div class="mb-0">
                                                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapsesix" aria-expanded="false" aria-controls="collapsesix" class="collapsed">
                                                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                                                        <label> <h6> {{ $key_val[0]}} </h6></label><br> 
                                                                    </a>
                                                                </div>
                                                            </div>
                                                            <div id="collapsesix" class="collapse" role="tabpanel" aria-labelledby="headingsix" aria-expanded="false" style="">
                                                                <div class="card-block">
                                                                    <table class="table">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>US Department of State - Iran and Syria Nonproliferation</td>
                                                                                <td class="pep">No Record</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>US Department of State - Iran, North Korea, and Syria Nonproliferation</td>
                                                                                <td class="pep">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>Minister of Foreign Affairs -Special Economic Measures -Syria, Canada</td>
                                                                                <td class="pep">No Record</td>
                                                                            </tr>
                                                                            <tr>    
                                                                                <td>US Iran and Syria Nonproliferation Act</td>
                                                                                <td class="pep">No Record</td>
                                                                            </tr>
                                                                            
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>  
                                                    </div>
                                                @elseif ($key_val[0]=='Web and Media Searches – Global')
                                                    <input type="hidden" name="service-input-label-{{ $item->id.'-'.$i }}" value="{{ $key_val[0]}}">
                                                    <input class="form-control error-control" type="hidden" name="service-input-value-{{ $item->id.'-'.$i }}" id="inputmedia" value="{{ $input_val[0] }}">
                                                    <div id="accordion" role="tablist" aria-multiselectable="true">
                                                        <div class="card">
                                                            <div class="card-header" role="tab" id="headingseven">
                                                                <div class="mb-0">
                                                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseseven" aria-expanded="false" aria-controls="collapseseven" class="collapsed">
                                                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                                                        <label> <h6> {{ $key_val[0]}} </h6></label><br> 
                                                                    </a>
                                                                </div>
                                                            </div>
                                                            <div id="collapseseven" class="collapse" role="tabpanel" aria-labelledby="headingseven" aria-expanded="false" style="">
                                                                <div class="card-block">
                                                                    <table class="table">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>Internet Searches</td>
                                                                                <td class="web">No Record</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Media Searches</td>
                                                                                <td class="web">No Record</td>
                                                                            </tr>
                                                                            
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>  
                                                    </div>                
                                            @else
                                                <label>  {{ $key_val[0]}} </label><br>
                                                <input type="hidden" name="service-input-label-{{ $item->id.'-'.$i }}" value="{{ $key_val[0]}}">
                                                <input class="form-control  service-input-value-{{$item->id.'-'.$i}} "  type="text" name="service-input-value-{{ $item->id.'-'.$i }}" value="{{ $input_val[0] }}" >
                                            @endif     
                                        @else
                                            <label>  {{ $key_val[0]}} </label>
                                            <input type="hidden" name="service-input-label-{{ $item->id.'-'.$i }}" value="{{ $key_val[0]}}">
                                            <input class="form-control error-control" type="text" name="service-input-value-{{ $item->id.'-'.$i }}" value="{{ $input_val[0] }}">
                                        @endif

                                        {{-- @if(stripos($key_val[0],'Reference Type (Personal / Professional)')!==false)
                                            <label>  {{ $key_val[0]}} <span class="text-danger">*</span></label>
                                            <input type="hidden" name="service-input-label-{{ $item->id.'-'.$i }}" value="{{ $key_val[0]}}">
                                            <select class="form-control service-input-value-{{$item->id.'-'.$i}} reference_type error-control" name="service-input-value-{{ $item->id.'-'.$i }}" data-id="{{base64_encode($item->id)}}" data-report="{{$item->id}}">
                                                <option value="">--Select--</option>
                                                <option @if(stripos($input_val[0],'personal')!==false) selected @endif value="personal">Personal</option>
                                                <option @if(stripos($input_val[0],'professional')!==false) selected @endif value="professional">Professional</option>
                                            </select>
                                        @else
                                            <label>  {{ $key_val[0] }} </label>
                                            <input type="hidden" name="service-input-label-{{ $item->id.'-'.$i }}" value="{{ $key_val[0]}}">
                                            <input class="form-control error-control" type="text" name="service-input-value-{{ $item->id.'-'.$i }}" value="{{ $input_val[0] }}">
                                        @endif --}}
                                        <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-service-input-value-{{ $item->id.'-'.$i }}"></p>
                                    </div>
                                </div>

                                <?php 
                                    $label = '';
                                    if($item->type_name=='global_database'){
                                        if(stripos($key_val[0],'Criminal Records Database Checks - India')!==false)
                                        {
                                        $label = 'd-none';
                                        }
                                        elseif(stripos($key_val[0],'Civil Litigation Database Checks – India')!==false)
                                        {
                                        $label = 'd-none';
                                        }
                                        elseif(stripos($key_val[0],'Credit and Reputational Risk Database Checks – India')!==false)
                                        {
                                        $label = 'd-none';
                                        }
                                        elseif(stripos($key_val[0],'Serious and Organized Crimes Database Checks – Global')!==false)
                                        {
                                        $label = 'd-none';
                                        }
                                        elseif(stripos($key_val[0],'Global Regulatory Bodies')!==false)
                                        {
                                        $label = 'd-none';
                                        }
                                        elseif(stripos($key_val[0],'Compliance Database')!==false)
                                        {
                                        $label = 'd-none';
                                        }
                                        elseif(stripos($key_val[0],'Sanction & PEP - Global')!==false)
                                        {
                                        $label = 'd-none';
                                        }
                                        elseif(stripos($key_val[0],'Web and Media Searches – Global')!==false)
                                        {
                                        $label = 'd-none';
                                        }
                                        }
                                ?>
                                <!-- Remarks -->
                                <div class="col-sm-1">
                                    <div class="form-group">
                                    <label class=""> Remarks </label>
                                        <div class="form-check">
                                        <label class="form-check-label">
                                        <input type="checkbox" id="remarks-input-checkbox-{{ $item->id.'-'.$i}}" name="remarks-input-checkbox-{{ $item->id.'-'.$i}}"  @if(in_array('remarks', $key_val)) @if($input['remarks']=='Yes') checked @endif @endif class="form-check-input" >
                                        </label>
                                        </div>
                                    </div>
                                </div>
                                <!-- Remarks Message -->
                                <div class="col-sm-5 {{$label}}">
                                    <div class="form-group">
                                    <label> Remarks Message</label>
                                        <?php
                                            $remarks_message =""; 
                                            $remarks_custom_message="";
                                            if(array_key_exists('remarks_message', $input_item_data_array[$i]))
                                            {
                                                $remarks_message =  $input_item_data_array[$i]['remarks_message'];
                                            }
                                            if(array_key_exists('remarks_custom_message', $input_item_data_array[$i]))
                                            {
                                                $remarks_custom_message =  $input_item_data_array[$i]['remarks_custom_message'];
                                                // dd($remarks_custom_message);
                                            }
                                        ?>
                                         <select class="form-control remark_msg" name="remarks-input-value-{{ $item->id.'-'.$i}}" data-item_id="{{  $item->id }}" data-id="{{ $i }}">
                                            <option value="">-Select-</option>
                                            <option value="clear" {{ $remarks_message == 'clear' ? 'selected' : '' }}>Verified Clear</option>
                                            <option value="no_record" {{ $remarks_message == 'no_record' ? 'selected' : '' }}>No Record Found</option>
                                            <option value="unable_verify" {{ $remarks_message == 'unable_verify' ? 'selected' : '' }}>Unable to Verify</option>
                                            <option value="stop" {{ $remarks_message == 'stop' ? 'selected' : '' }}>Stop</option>
                                            <option value="custom" {{ $remarks_message == 'custom' ? 'selected' : '' }}>Custom</option>
                                        </select>
                                        {{--<input type="text" name="remarks-input-value-{{ $item->id.'-'.$i}}"  class="form-control" value="{{ $remarks_message }}"> --}}
                                    </div>
                                </div>

                                @if($item->type_name=='global_database')
                                    
                                    @if(stripos($key_val[0],'Criminal Records Database Checks - India')!==false)
                                   
                                        <div class="col-sm-5">
                                            <div class="form-group">
                                                <select name="global_database" id="customdata" class="form-control inpdes remark_msg">
                                                    <option value="">-Select-</option>
                                                    <option value="Record found" @if($input_val[0]=="Record found") selected @endif>Record Found</option>
                                                    <option value="No record" @if($input_val[0]=="No record") selected @endif>>No record</option>
                                                </select>
                                                {{-- <input type="text" name="remarks-input-value-{{ $item->id.'-'.$i}}"  class="form-control" value="{{ $remarks_message }}"> --}}
                                            </div>
                                        </div>
                                        @elseif(stripos($key_val[0],'Civil Litigation Database Checks – India')!==false)
                                        <div class="col-sm-5">
                                            <div class="form-group">
                                                <select name="global_database" id="civil" class="form-control inpdes remark_msg">
                                                    <option value="">-Select-</option>
                                                    <option value="Record found" @if($input_val[0]=="Record found") selected @endif>Record Found</option>
                                                    <option value="No record" @if($input_val[0]=="No record") selected @endif>No record</option>
                                                </select>
                                                {{-- <input type="text" name="remarks-input-value-{{ $item->id.'-'.$i}}"  class="form-control" value="{{ $remarks_message }}"> --}}
                                            </div>
                                        </div> 
                                    @elseif(stripos($key_val[0],'Credit and Reputational Risk Database Checks – India')!==false)
                                        <div class="col-sm-5">
                                            <div class="form-group">
                                                <select name="global_database" id="creadit" class="form-control inpdes remark_msg">
                                                    <option value="">-Select-</option>
                                                    <option value="Record found" @if($input_val[0]=="Record found") selected @endif>Record Found</option>
                                                    <option value="No record" @if($input_val[0]=="No record") selected @endif>No record</option>
                                                </select>
                                                {{-- <input type="text" name="remarks-input-value-{{ $item->id.'-'.$i}}"  class="form-control" value="{{ $remarks_message }}"> --}}
                                            </div>
                                        </div>

                                    @elseif(stripos($key_val[0],'Serious and Organized Crimes Database Checks – Global')!==false)
                                        <div class="col-sm-5">
                                            <div class="form-group">
                                                <select name="global_database" id="serious" class="form-control inpdes remark_msg">
                                                    <option value="">-Select-</option>
                                                    <option value="Record found" @if($input_val[0]=="Record found") selected @endif>Record Found</option>
                                                    <option value="No record" @if($input_val[0]=="No record") selected @endif>No record</option>
                                                </select>
                                                {{-- <input type="text" name="remarks-input-value-{{ $item->id.'-'.$i}}"  class="form-control" value="{{ $remarks_message }}"> --}}
                                            </div>
                                        </div>

                                        @elseif(stripos($key_val[0],'Global Regulatory Bodies')!==false)
                                        <div class="col-sm-5">
                                            <div class="form-group">
                                                <select name="global_database" id="global" class="form-control inpdes remark_msg">
                                                    <option value="">-Select-</option>
                                                    <option value="Record found" @if($input_val[0]=="Record found") selected @endif>Record Found</option>
                                                    <option value="No record" @if($input_val[0]=="No record") selected @endif>No record</option>
                                                </select>
                                                {{-- <input type="text" name="remarks-input-value-{{ $item->id.'-'.$i}}"  class="form-control" value="{{ $remarks_message }}"> --}}
                                            </div>
                                        </div>

                                        @elseif(stripos($key_val[0],'Compliance Database')!==false)
                                        <div class="col-sm-5">
                                            <div class="form-group">
                                                <select name="global_database" id="compliance" class="form-control inpdes remark_msg">
                                                    <option value="">-Select-</option>
                                                    <option value="Record found" @if($input_val[0]=="Record found") selected @endif>Record Found</option>
                                                    <option value="No record" @if($input_val[0]=="No record") selected @endif>No record</option>
                                                </select>
                                                {{-- <input type="text" name="remarks-input-value-{{ $item->id.'-'.$i}}"  class="form-control" value="{{ $remarks_message }}"> --}}
                                            </div>
                                        </div>

                                        @elseif(stripos($key_val[0],'Sanction & PEP - Global')!==false)
                                        <div class="col-sm-5">
                                            <div class="form-group">
                                                <select name="global_database" id="sanction" class="form-control inpdes remark_msg">
                                                    <option value="">-Select-</option>
                                                    <option value="Record found" @if($input_val[0]=="Record found") selected @endif>Record Found</option>
                                                    <option value="No record" @if($input_val[0]=="No record") selected @endif>No record</option>
                                                </select>
                                                {{-- <input type="text" name="remarks-input-value-{{ $item->id.'-'.$i}}"  class="form-control" value="{{ $remarks_message }}"> --}}
                                            </div>
                                        </div>

                                        @elseif(stripos($key_val[0],'Web and Media Searches – Global')!==false)
                                        <div class="col-sm-5">
                                            <div class="form-group">
                                                <select name="global_database" id="media" class="form-control inpdes remark_msg">
                                                    <option value="">-Select-</option>
                                                    <option value="Record found" @if($input_val[0]=="Record found") selected @endif>Record Found</option>
                                                    <option value="No record" @if($input_val[0]=="No record") selected @endif>No record</option>
                                                </select>
                                                {{-- <input type="text" name="remarks-input-value-{{ $item->id.'-'.$i}}"  class="form-control" value="{{ $remarks_message }}"> --}}
                                            </div>
                                        </div>    
                                    @endif
                                @endif  
                            </div>
                             <!-- check output -->
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                        <label class="checkbox-inline error-control">
                                        <?php
                                            
                                            $is_report_output ="0"; 
                                            // if(array_key_exists('is_executive_summary', $input_item_data_array[$i]))
                                            // {
                                            //     $is_executive_summary =  $input_item_data_array[$i]['is_executive_summary'];
                                            // }
                                            if(array_key_exists('is_report_output', $input_item_data_array[$i]))
                                            {
                                                $is_report_output =  $input_item_data_array[$i]['is_report_output'];
                                            }
                                        ?>
                                            {{-- <input type="checkbox" name="executive-summary-{{ $item->id .'-'.$i}}" @if ($is_executive_summary)
                                                
                                                @if($is_executive_summary->is_executive_summary == '1')  checked @endif @endif > Executive Summary Output (if yes: Check Mark) --}}

                                                <input type="checkbox" name="executive-summary-{{ $item->id .'-'.$i}}"
                                                
                                                @if($is_executive_summary == '1')  checked @endif > Executive Summary Output (if yes: Check Mark)
                                        </label>
                                        </div>
                                        <div class="form-group">
                                        <label class="checkbox-inline error-control">
                                            <input type="checkbox" name="table-output-{{ $item->id.'-'.$i }}" @if($is_report_output == '1')  checked @endif > Check's Table Output (if yes: Check Mark)
                                        </label>
                                        </div>
                                    </div> 
                                    <div class="col-sm-6">
                                        <div class="form-group @if($remarks_custom_message=='') d-none @endif " id="msg-{{ $item->id.'-'.$i }}" >
                                            <input type="text" name="remarks-msg-{{ $item->id.'-'.$i}}"  class="form-control" value="{{ $remarks_custom_message }}">
                                        </div>
                                    </div>
                                </div>
                            <!-- ./check outputs -->
                            <!-- end row -->
                            <?php $i++; ?>
                        @endforeach

                            <!-- Reference Type inputs  -->
                                @if($item->service_id==17)
                                    <div class="reference_result" id="reference_result-{{$item->id}}">
                                        @php
                                            $reference_type = NULL;

                                            if($item->reference_type!=NULL)
                                            {
                                                $reference_type = $item->reference_type;
                                            }
                                            else
                                            {
                                                foreach($input_item_data_array as $input)
                                                {
                                                    $key_val = array_keys($input); $input_val = array_values($input);

                                                    if(stripos($key_val[0],'Reference Type (Personal / Professional)')!==false)
                                                    {
                                                        $reference_type = $input_val[0];
                                                    }
                                                }
                                            }
                                        @endphp
                                        @if($reference_type!=NULL || $reference_type!='')
                                            <?php 
                                                $reference_service_inputs=Helper::referenceServiceFormInputs($item->service_id,$reference_type);
                                            ?>
                                            @if($reference_item_data!=NULL)
                                                <?php 
                                                    $reference_item_data_array=json_decode($reference_item_data,true);
                                                ?>
                                                <div class="row" style="border:1px solid #ddd; padding:10px; margin-bottom:10px;"> 
                                                    <h4 class="pt-2 pb-2">{{ ucwords($reference_type) }} Details</h4>
                                                    @foreach ($reference_item_data_array as $key => $input)
                                                        <div class="col-sm-12">
                                                            <div class="form-group">
                                                            <?php
                                                                $key_val = array_keys($input); $input_val = array_values($input);
                                                            ?>
                                                            <label>  {{ $key_val[0]}}  </label>
                                                            <input type="hidden" name="reference-input-label-{{ $item->id.'-'.$l }}" value="{{ $key_val[0] }}">
                                                            <input class="form-control error-control" type="text" name="reference-input-value-{{ $item->id.'-'.$l }}" value="{{$input_val[0]}}">
                                                            </div>
                                                        </div>
                                                        <?php $l++; ?>
                                                    @endforeach
                                                </div>
                                            @else
                                                <div class="row" style="border:1px solid #ddd; padding:10px; margin-bottom:10px;"> 
                                                    <h4 class="pt-2 pb-2">{{ ucwords($reference_type) }} Details</h4>
                                                    @foreach($reference_service_inputs as $key => $input)
                                                        <div class="col-sm-12">
                                                            <div class="form-group">
                                                                <label> {{ $input->label_name }} </label>
                                                                <input type="hidden" name="reference-input-label-{{ $item->id.'-'.$k }}" value="{{ $input->label_name }}">
                                                                <input class="form-control error-control" type="text" name="reference-input-value-{{ $item->id.'-'.$k }}">
                                                            </div>
                                                        </div>
                                                        <?php $k++; ?>
                                                    @endforeach
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                @endif
                            <!--  -->
                          <!-- Additional Address  -->
                            @if ($item->service_name=="Address")
                                @php
                                    //Helper to get report_add_page_statuses Data
                                    $report_add_page =  Helper::get_report_page($candidate->business_id);
                                    $additional_data =  Helper::get_additional_address_data($candidate->id,$item->id);
                                @endphp
                                @if ($report_add_page)
                                
                                    @if ($report_add_page->status == 'enable')
                                        <div class="row">
                                            <div class="col-sm-12"> 
                                                <h4 class="card-title mb-2 mt-2">Additional Address verification  </h4>
                                            </div>   
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label> Contact Person Name</label>
                                                    <input class="form-control error-control" type="text" name="contact_person_name-{{ $item->id }}"  value="{{ $additional_data ? $additional_data->contact_person_name : ''}}"  >
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>  Contact Person Number</label>
                                                    <input class="form-control error-control" type="text" name="contact_person_no-{{ $item->id }}"  value="{{ $additional_data ? $additional_data->contact_contact_no : '' }}"  >
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Residence Status</label>
                                                    <input class="form-control error-control" type="text" name="residence_status-{{ $item->id }}"  value="{{ $additional_data ? $additional_data->residence_status : ''  }}"  >
                                                </div>
                                            </div><div class="col-sm-6">
                                                <div class="form-group">
                                                    <label> Relation with Associate</label>
                                                    <input class="form-control error-control" type="text" name="relation_with_associate-{{ $item->id }}"  value="{{  $additional_data ? $additional_data->relation_with_associate : ''  }}"  >
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>  Locality</label>
                                                    <input class="form-control error-control" type="text" name="locality-{{ $item->id }}"  value="{{  $additional_data ? $additional_data->locality : '' }}"  >
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Mode of Verification</label>
                                                    <input class="form-control error-control" type="text" name="verification_mode-{{ $item->id }}"  value="{{   $additional_data ? $additional_data->mode_of_verification : '' }}"  >
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>  Remarks</label>
                                                    <input class="form-control error-control" type="text" name="additional_remark-{{ $item->id }}"  value="{{  $additional_data ? $additional_data->remarks : '' }} "  >
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label> Verified By</label>
                                                    <input class="form-control error-control" type="text" name="Additional_verified_by-{{ $item->id }}"  value="{{   $additional_data ? $additional_data->verified_by : ''}}"  >
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label>Additional Comments <small>(If any)</small></label>
                                                    <textarea class="form-control error-control" type="text" name="additional_verification_comments-{{ $item->id }}" >@if ($additional_data){{ $additional_data->comments }} @endif</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <!--  -->
                                    @endif
                                @endif
                            @endif
                          <!--  -->
                        <!-- comment  -->
                            <div class="row">
                                <div class="col-sm-12"> 
                                    <h4 class="card-title mb-2 mt-2">Approval Inputs  </h4>
                                </div>
                                @if(stripos($item->type_name,'drug_test_5')!==false || stripos($item->type_name,'drug_test_6')!==false || stripos($item->type_name,'drug_test_7')!==false || stripos($item->type_name,'drug_test_8')!==false || stripos($item->type_name,'drug_test_9')!==false || stripos($item->type_name,'drug_test_10')!==false)   
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label> Test Date</label>
                                            <input class="form-control error-control test_date commonDatepicker" type="text" name="test_date-{{ $item->id }}" value="{{ $item->test_date!=NULL ? date('d-m-Y',strtotime($item->test_date)) : NULL }}">
                                        </div>
                                    </div>
                                @endif
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label> Verified By</label>
                                        <input class="form-control error-control" type="text" name="verified_by-{{ $item->id }}" value="{{ $item->verified_by }}">
                                    </div>
                                </div>
                            </div>
                            @php
                                $report_show = Helper::report_show($candidate->business_id,'3');
                            @endphp
                            @if ($report_show==null)
                                <div class="row">
                                
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label> Comments</label>
                                            <textarea class="form-control " type="text" name="comments-{{ $item->id }}" >{{ $item->comments?$item->comments:"The copy of confirmation is attached herewith as Annexure." }}</textarea>
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-6" style="">
                                        <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon3">Annexure Value</span>
                                        </div>
                                            <input type="text" class="form-control error-control" name="annexure_value-{{$item->id}}"  value="{{ $item->annexure_value }}" aria-describedby="basic-addon3">
                                        </div>
                                    </div>
                                
                                </div>
                            @endif
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Additional Comments</label>
                                        <textarea class="form-control error-control" type="text" name="additional-comments-{{ $item->id }}" > {{ $item->additional_comments }} </textarea>
                                    </div>
                                </div>
                            </div>
                            <!--  -->
                            <div class="row">
                            {{-- @php $dataVal=array(1,10,11,15,16,17,28);  @endphp
                                @if(in_array($item->service_id, $dataVal)) --}}
                            <div class="col-sm-12">
                                    <div class="form-group">
                                    <label>Verification Mode</label><br>
                                    <select class="form-control verification_mode" name="verification_mode-{{ $item->id }}" >
                                            <option value="">Select Verification Mode</option>
                                            <option value="Digital Verification" @if($item->verification_mode=="Digital Verification") selected @endif >Digital Verification</option>
                                            <option value="Virtual Verification" @if($item->verification_mode=="Virtual Verification") selected @endif>Virtual Verification</option>
                                            <option value="Physical Verification" @if($item->verification_mode=="Physical Verification") selected @endif>Physical Verification</option>
                                    </select>
                                    <p style="margin-bottom: 2px;" class="text-danger error-container error-verification_mode-{{ $item->id }}" id="error-verification_mode-{{ $item->id }}"></p>

                                    </div>
                                    <div class="new-tag"> </div>
                                        <input type="hidden" class="itemID" name="itemID" value="{{ $item->id }}">
                                    </div>
                                    {{-- @endif --}}
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Approval Status</label>
                                        <select class="form-control approval_status error-control" name="approval-status-{{ $item->id }}" >
                                            @foreach($status_list as $status)
                                            <option data-id="{{ $item->id }}" value="{{ $status->id}}" @if($status->id=='5' && $item->approval_status_id==null) selected @elseif($status->id == $item->approval_status_id) selected @endif> {{ $status->name}} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="new-tag">
                                        @if ($item->approval_status_id!='4')
                                        <div class='form-group '><label class='text-danger'>Add Your Insuff Notes </label>
                                            <input class='form-control' type='text' value="{{ $item->report_insufficiency_notes }}" readonly></div>
                                        @endif   
                                    </div>
                                    <input type="hidden" class="itemID" name="itemID" value="{{ $item->id }}">
                                </div>
                               
                            </div>
                            <!--  -->
                             <!-- Court inpput start -->
                             @if( $item->service_id == 15 )  
                                <div class="row mt-2">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                        <label> <b> Court </b></label>
                                        </div>
                                    </div>
                                    <!--  -->
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                        <label> <b>Court Name </b></label>
                                        </div>
                                    </div>
                                    <!--  -->
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                        <label> <b>Result</b> </label>
                                        </div>
                                    </div>
                                    <!--  -->
                                </div>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                        <p>District Court/Lower Court/Civil Court & Small Causes</p>
                                        </div>
                                    </div>
                                    <!--  -->
                                    <div class="col-sm-6" style="padding-left:0px;padding-right:0px">
                                        <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon3">District Courts of</span>
                                        </div>
                                            <input type="text" class="form-control error-control" name="district_court_name-{{$item->id}}"  value="{{ $item->district_court_name }}" aria-describedby="basic-addon3">
                                        </div>
                                    </div>
                                    <!--  -->
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <input type="text" name="district_court_result-{{$item->id}}" class="form-control error-control" value="{{ $item->district_court_result }}">
                                        </div>
                                    </div>
                                    <!--  -->
                                </div>
                                <!-- row. -->
                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                        <p>High Court</p>
                                        </div>
                                    </div>
                                    <!--  -->
                                    <div class="col-sm-6" style="padding-left:0px;padding-right:0px">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon3">High Court of Jurisdiction at</span>
                                        </div>
                                            <input type="text" class="form-control error-control" name="high_court_name-{{$item->id}}" value="{{ $item->high_court_name }}" aria-describedby="basic-addon3">
                                        </div>
                                    </div>
                                    <!--  -->
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <input type="text" name="high_court_result-{{$item->id}}" class="form-control error-control" value="{{ $item->high_court_result }}">
                                        </div>
                                    </div>
                                    <!--  -->
                                </div>
                                <!-- ./row -->
                                <!-- row. -->
                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                        <p>Supreme Court</p>
                                        </div>
                                    </div>
                                    <!--  -->
                                    <div class="col-sm-6" style="padding-left:0px;padding-right:0px">
                                        <div class="form-group" >
                                            <input type="text" name="supreme_court_name-{{$item->id}}" class="form-control error-control" value="Supreme Court of India, New Delhi" readonly>
                                        </div>
                                    </div>
                                    <!--  -->
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <input type="text" name="supreme_court_result-{{$item->id}}" class="form-control error-control" value="{{ $item->supreme_court_result }}">
                                        </div>
                                    </div>
                                    <!--  -->
                                </div>
                                <!-- ./row -->
                             @endif
                            <!-- ./ end court  -->

                            <!--  -->
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                    <label class="checkbox-inline text-danger error-control">
                                        <input type="checkbox" name="report-output-{{ $item->id }}" @if($item->is_report_output == '1')  checked @endif >  Include in Report Output (if yes: Check Mark)
                                    </label>
                                    </div>
                                </div>
                            </div>
                            <!--  -->

                     </div>
                     <!-- attachment  -->
                     <div class="col-md-6">
                     @php $service_name=Helper::service_attachment_type($item->service_id); @endphp
                        <p>Attachments: <i class="fa fa-info-circle" data-toggle="tooltip" data-original-title="Only jpeg,png,jpg,gif,pdf are accepted "></i></p>
                        <p class="text-danger" style="font-size: 12px;">Select a field for the type of file you want to upload</p>
                        <div class="col-md-4">
                        <div class="form-group">
                        <!-- <label for="name">Form Type <span class="text-danger">*</span></label> -->
                        <select name="service_type" class="form-control service_select_main" id="service_select_main-{{$item->id}}" data-type="main" data-select="{{$item->id}}">
                            <option value="">-Select-</option>
                            @foreach($service_name as $sname)
                            <option value="{{$sname->id}}" data-name="{{preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $sname->attachment_name)}}">{{$sname->attachment_name}}</option>
                            @endforeach
                        </select>
                        <input type="text" class="form-control attachment_name" name="attachment_name" id="attachment_name-{{$item->id}}" placeholder="Enter File Name" style="display:none;margin-top: 12px;">
                        <p style="margin-bottom: 2px;" class="text-danger error_container" id="other_error"></p>  
                        </div>
                        </div>
                        <button class='btn btn-sm btn-info clickReorder reorder_link' type="button" add-imageId="{{$item->id}}" data-imageType='main' style=' float:right;'><i class="fas fa-sync"></i> Re-Arrange </button>
                        <a class='btn-link clickSelectFile  error-control' id="buttonToSelect-{{$item->id}}" add-id="{{$item->id}}" data-number='1' data-result='fileResult1' data-type='main' style='color: #0056b3; font-size: 14px; display:none;' href='javascript:;'><i class='fa fa-plus'></i> Add file</a>
                        <input type='file' class='fileupload' name="file-{{$item->id}}[]" id='file1-{{$item->id}}' multiple="multiple" style='display:none'/><br>
                        <div class="fileResult1-{{$item->id}} text-center"></div>
                        <div class='row fileResult' id="fileResult1-{{$item->id}}" style='min-height: 20px; margin-top: 20px;'>
                        <?php $item_files = Helper::getReportAttachFiles($item->id,'main'); //print_r($item_files); ?>
                        <?php $i = 0; ?>
                        @foreach($item_files as $file)
                        <?php $attached_file_id=$file['attached_file_id']; $attached_files = Helper::getAttachedFileName($attached_file_id); //print_r($attached_files); ?>
                            @if($file['attachment_type'] == 'main')
                            <div class="image-area" data-id="<?=$i;?>">
                                @if(stripos($file['file_name'],'pdf')!==false)
                                    <img src="{{url('/').'/admin/images/icon_pdf.png'}}" class="fileItem" alt="Preview" id="zoom<?=$i;?>" title="{{$file['file_name']}}">
                                @else
                                @foreach($attached_files as $afile)
                                    <img src="{{ $file['fileIcon'] }}" class="fileItem" alt="Preview" id="zoom<?=$i;?>" title="{{$file['file_name']}}">
                                    @if($file['attached_file_name']==null)
                                        <span class="filename">{{$afile->attachment_name}}</span>
                                    @endif
                                    @if($file['attached_file_name']!=null)
                                    <span class="filename">{{$file['attached_file_name']}}</span>
                                    @endif
                                @endforeach
                                    @endif
                                <input type="hidden" name="zoom_id"  id="a_id" value="<?=$i;?>">
                                <a class="remove-image" data-id="{{ $file['file_id'] }}" href="javascript:;" style="display: inline;">×</a>
                                <input type="hidden" name="fileID[]" value="{{ $file['file_id'] }}">
                            </div>
                            
                            @endif
                            <?php $i++; ?>
                        @endforeach
                        </div>
                        <p class="mt-2" style="margin-bottom:1px">Add Supportings: <i class="fa fa-info-circle" data-toggle="tooltip" data-original-title="Only jpeg,png,jpg,gif,pdf are accepted "></i></p>
                        <p class="text-danger" style="font-size: 12px;">Select a field for the type of file you want to upload</p>
                        @php $service_name=Helper::service_attachment_type($item->service_id); @endphp
                        <div class="col-md-4">
                            <div class="form-group">
                            <!-- <label for="name">Form Type <span class="text-danger">*</span></label> -->
                            <select name="service_type" class="form-control service_add service_select_supp" id="service_add_supp-{{$item->id}}" data-type="supporting" data-select="{{$item->id}}">
                                <option value="">-Select-</option>
                                @foreach($service_name as $sname)
                                <option value="{{$sname->id}}" data-name="{{preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $sname->attachment_name)}}">{{$sname->attachment_name}}</option>
                                @endforeach
                            </select>
                            <input type="text" class="form-control attached_file" name="attachment_name" id="attached_file-{{$item->id}}" placeholder="Enter File Name" style="display:none; margin-top: 12px;">
                            <p style="margin-bottom: 2px;" class="text-danger error_container" id="other_error"></p>  
                            </div>
                        </div>
                        <button class='btn btn-sm btn-info clickReorder reorder_link' type="button" add-imageId="{{$item->id}}" data-imageType='supporting' style=' float:right;'><i class="fas fa-sync"></i> Re-Arrange </button>
                        <a class='btn-link clickSelectFile error-control' id="addSupporting-{{$item->id}}" add-id="{{$item->id}}" data-number='2' data-result='fileResult2' data-type='supporting' style='color: #0056b3; font-size: 14px; display:none ' href='javascript:;'><i class='fa fa-plus'></i> Add file</a>
                        <input type='file' class='fileupload' name="file-{{$item->id}}[]" id='file2-{{$item->id}}' multiple="multiple" style='display:none'/>
                        <div class="fileResult2-{{$item->id}} text-center"></div>
                        <div class='row fileResult' id="fileResult2-{{$item->id}}" style='min-height: 20px; margin-top: 20px;'>
                        <?php $item_files = Helper::getReportAttachFiles($item->id,'supporting'); //print_r($item_files); ?>
                        @foreach($item_files as $file)
                        <?php $attached_file_id=$file['attached_file_id']; $attached_files = Helper::getAttachedFileName($attached_file_id); //print_r($item_files); ?>
                            @if($file['attachment_type'] == 'supporting')
                            <div class="image-area">
                                @if(stripos($file['file_name'],'pdf')!==false)
                                    <img src="{{url('/').'/admin/images/icon_pdf.png'}}" alt="Preview" title="{{$file['file_name']}}">
                                @else
                                @foreach($attached_files as $afile)
                                    <img src="{{ $file['fileIcon'] }}" alt="Preview" title="{{$file['file_name']}}">
                                    @if($file['attached_file_name']==null)
                                    <span class="filename">{{$afile->attachment_name}}</span>
                                    @endif
                                @endforeach
                                    @if($file['attached_file_name']!=null)
                                    <span class="filename">{{$file['attached_file_name']}}</span>
                                    @endif
                                @endif
                                <a class="remove-image" data-id="{{ $file['file_id'] }}" href="javascript:;" style="display: inline;">×</a>
                                <input type="hidden" name="fileID[]" value="{{ $file['file_id'] }}">
                            </div>
                            @endif
                        @endforeach
                        </div>

                     </div>
                            
                  </div>
                  

                  <?php $r_item_id = $item->id;?>
                 @endforeach
                 @endif
                    <!-- Verifier Details -->
                    <div class="row" style="border: 1px solid #ddd; margin:10px 0; padding: 10px 0;">
                        <div class="col-md-12">
                            <h4 class="card-title mb-0" style="margin-bottom:0px;">Report Verifier Details </h4>
                            <hr style="margin-top:2px;">
                        </div>
                    
                        <div class="col-sm-4">
                            <div class="form-group">
                            <label>Verifier name </label>
                                <input class="form-control " type="text" name="verifier_name" value="{{$report->verifier_name}}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                            <label>Verifier Email </label>
                                <input class="form-control " type="text" name="verifier_email" value="{{$report->verifier_email}}" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                            <label>Verifier Designation </label>
                                <input class="form-control " type="text" name="verifier_designation" value="{{$report->verifier_designation}}" readonly>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Insuff Details -->
                    <div class="row" style="border: 1px solid #ddd; margin:10px 0; padding: 10px 0;">
                        <div class="col-md-12">
                            <h4 class="card-title mb-0" style="margin-bottom:0px;">Insuff Details </h4>
                            <hr style="margin-top:2px;">
                        </div>
                    
                        <div class="col-sm-4">
                            <div class="form-group">
                            <label>Insuff raised date </label>
                            <input type="text" name="insuff_raised_date" class="form-control insuff_raised_date commonDatepicker"  placeholder="" value="{{$report->insuff_raised_date!=NULL?date('d-m-Y', strtotime($report->insuff_raised_date)):old('insuff_raised_date') }}" autocomplete="off">
                            <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-insuff_raised_date"></p>
                        </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                            <label>Insuff cleared date</label>
                            <input type="text" name="insuff_cleared_date" class="form-control insuff_cleared_date commonDatepicker"  placeholder="" value="{{$report->insuff_cleared_date!=null?date('d-m-Y', strtotime($report->insuff_cleared_date)):old('insuff_cleared_date') }}" autocomplete="off">
                            <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-insuff_cleared_date"></p>
                        </div>
                        </div>
                       
                    </div>
                    <!-- Revised date Update -->
                    @php
                        $hide = Helper::report_custom($report->business_id);
                    @endphp
                   
                   
                        <div class="row" style="border: 1px solid #ddd; margin:10px 0; padding: 10px 0;">
                            <div class="col-md-12">
                                <h4 class="card-title mb-0" style="margin-bottom:0px;">Add Custom Date </h4>
                                <hr style="margin-top:2px;">
                            </div>
                            @if($hide=='enable')
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label>Revised Date </label>
                                        <input class="form-control commonDatepicker revised_date" type="text" name="revised_date" value="{{ $report_revised_date && date('Y-m-d',strtotime($report_revised_date))!='1970-01-01' ? date('d-m-Y',strtotime($report_revised_date)):NULL }}" autocomplete="off">
                                        <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-revised_date"></p>
                                    </div>
                                </div>
                            @endif
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Case Initiated Date </label>
                                    <input class="form-control commonDatepicker initiated_date" type="text" name="initiated_date" value="{{$report->initiated_date!=null?date('d-m-Y', strtotime($report->initiated_date)):old('insuff_cleared_date') }}" autocomplete="off">
                                    <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-initiated_date"></p>
                                </div>
                            </div>
                        
                        </div> 

                  {{-- </div> --}}
                   
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group mt-3">
                                {{-- <div class="form-check">
                                    <label class="check-inline">
                                    <input type="checkbox" name="check_green" class="form-check-input check_green" @if($report->is_manual_mark==1) checked @endif><span style="font-size: 14px;">Want to Mark the Color Code as Green Anyway</span>
                                    </label>
                                </div> --}}
                                <label>Mark the Color Code As</label>
                                <select class="form-control manual_check" name="manual_check">
                                    {{-- <option value="">None</option> --}}
                                    <option @if($report->is_manual_mark==0) selected @endif value="6">Interim</option>
                                    <option @if($report->is_manual_mark==1) selected @endif value="1">Green</option>
                                    <option @if($report->is_manual_mark==2) selected @endif value="2">Grey (Stopped)</option>
                                    <option @if($report->is_manual_mark==3) selected @endif value="3">Red</option>
                                    <option @if($report->is_manual_mark==4) selected @endif value="4">Yellow</option>
                                    <option @if($report->is_manual_mark==5) selected @endif value="5">Orange</option>
                                </select>
                            </div>
                        </div>
                    </div>
                     <div class="form-group mt-3">
                        <div class="form-check">
                            <label class="check-inline">
                               <input type="checkbox" name="report_complete" class="form-check-input report_complete" @if($report->is_report_complete==1) checked disabled @endif><span style="font-size: 16px;"><b>Mark as Report Completed</b></span>
                            </label>
                         </div>
                     </div>
                    {{-- <div class="row update-btn"> --}}
                        <div class="text-center">
                            <button type="submit" class="btn btn-success"><i class="far fa-edit"></i> Update</button>
                            <button type="button" class="btn btn-dark reportPreviewBox" data-id="{{ base64_encode($report->id) }}"><i class="fas fa-eye"></i> Preview</button>
                            <a href="{{url('/reports')}}"><button type="button" class="btn btn-info"><i class="fas fa-arrow-left"></i> Back to Report </button></a>
                        </div>
                  {{-- </div> --}}
               </form>
            </div>
         </div>
      </div>
   </div>
</div>


<!-- The Modal -->
<div id="myModal" class="modal">
    
    <div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        <h5 class="modal-title">File </h5>
        
      </div>
        <div class="modal-body">
                                            
            <img class=" showImg" id="img01">
            
        </div>
    </div>
    <div id="caption"></div>
</div>

<!-- The Modal -->
<div id="myDragModal" class="modal">
    
    <div class="modal-content modal-part1">
        <div class="modal-header">
            <button type="button" class="close closeRearrangeModal" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
            <h5 class="modal-title">Files- You can re-arrange order of the files by  drag the image.</h5>
            
          </div>
        <div class="modal-body gallery-model">
            <input type="hidden" name="itemId" id="jafImageId">
            <input type="hidden" name="itemType" id="jafImageType">
            <div class="gallery">
             
          </div>
              
        </div>
    </div>
    
</div>
<!-- model end -->

<div class="modal"  id="preview">
    <div class="modal-dialog modal-lg">
       <div class="modal-content">
          <!-- Modal Header -->
          <div class="modal-header">
             <h4 class="modal-title">Report Preview</h4>
             <button type="button" class="close" style="top: 12px;!important; color: red;" data-dismiss="modal">&times;</button>
          </div>
          <!-- Modal body -->
          
             <div class="modal-body">
             <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-all"> </p> 
                <iframe 
                    src="" 
                    style="width:100%; height:600px;" 
                    frameborder="0" id="preview_pdf">
                </iframe>
             </div>
             <!-- Modal footer -->
             <div class="modal-footer">
                <button type="button" class="btn btn-danger back" data-dismiss="modal">Close</button>
             </div>
       </div>
    </div>
</div>

@stack('scripts')
<script type="text/javascript">
 $(document).ready(function() {

    $(".manual_check").select2();

    $(".approval_status").select2();

    $(document).on('click','.clickReorder',function(){ 
        imageId     = $(this).attr('add-imageId');
        imageType = $(this).attr('data-imageType');
        $('#jafImageId').val(imageId);
        $('#jafImageType').val(imageType);
        // alert(imageType);
        $.ajax({
              type:'GET',
              url: "{{url('/report/image/rearrange')}}",
              data: {'imageId':imageId,'imageType':imageType},        
              success: function (response) {        
              console.log(response);

              $('.gallery').html(response);
              $('#myDragModal').modal({
                    backdrop: 'static',
                    keyboard: false
                });
              // if (response.status=='ok') { 
              // } else {
              //    alert('No data found');
              // }
              $("ul.reorder-photos-list").sortable({   
                tolerance: 'pointer',
                update: function( event, ui ) {
                    updateOrder();
                }
            });  
              // $('.reorder_link').html('save reordering');
              // $('.reorder_link').attr("id","saveReorder");
              // $('#reorderHelper').slideDown('slow');
              $('.image_link').attr("href","javascript:void(0);");
              $('.image_link').css("cursor","move");
              // update: function( event, ui ) {
              //   updateOrder();
              // }
             
          },
          error: function (xhr, textStatus, errorThrown) {
              alert("Error: " + errorThrown);
          }
        });
        // $('#myDragModal').modal();

    });


    $(document).on('change','#customdata',function(){ 
        var custom = $(this).val();
        $('#inputhidden').val(custom);
        var input = $(this).val();
        $('.record').html(custom);
    });

    $(document).on('change','#civil',function(){ 
            var civildata = $(this).val();
            $('#civilhidden').val(civildata);
            var input = $(this).val();
            $('.database').html(civildata);
        });

        $(document).on('change','#creadit',function(){ 
            var creaditdata = $(this).val();
            $('#creadithidden').val(creaditdata);
            var input = $(this).val();
            $('.reputational').html(creaditdata);
        });

        $(document).on('change','#serious',function(){ 
            var serioustdata = $(this).val();
            $('#inputserious').val(serioustdata);
            var input = $(this).val();
            $('.organized').html(serioustdata);
        });

        $(document).on('change','#global',function(){ 
            var serioustdata = $(this).val();
            $('#inputglobal').val(serioustdata);
            var input = $(this).val();
            $('.bodies').html(serioustdata);
        });

        $(document).on('change','#compliance',function(){ 
            var inputcompliancedata = $(this).val();
        
            $('#inputcompliance').val(inputcompliancedata);
            var input = $(this).val();
            $('.compliancedata').html(inputcompliancedata);
        });

        $(document).on('change','#sanction',function(){ 
            var inputsanctiondata = $(this).val();
            $('#inputsanction').val(inputsanctiondata);
            var input = $(this).val();
            $('.pep').html(inputsanctiondata);
        });

        $(document).on('change','#media',function(){ 
            var mediadata = $(this).val();
            $('#inputmedia').val(mediadata);
            var input = $(this).val();
            $('.web').html(mediadata);
        });



    $(document).on('click','.closeRearrangeModal',function(){ 
         window.location.reload()
            // $('#myImageModal').css("display", "none");
   });
    // Preview Report
    $(document).on('click','.reportPreviewBox',function(){
        // alert('ads');
        var report_id = $(this).attr('data-id');

        document.getElementById('preview_pdf').src="{{ url('/') }}"+"/candidate/report/preview/"+report_id;
    
        $('#preview').toggle();
    });

    $('.close').click(function(){
        $('#preview').hide();
    });
    $('.back').click(function(){
        $('#preview').hide();
    });
        //image reordering
        // $("ul.reorder-photos-list").sortable({
        //    tolerance: 'pointer' 
        //    update: function( event, ui ) {
        //         updateOrder();
        //     }
        //   });
        //
    function updateOrder() {    
      //  console.log('good going');
       
      imageIds= $('#jafImageId').val();  
      jafImageTypes=$('#jafImageType').val();
        //   console.log(imageIds);
        var item_order = new Array();
        $('ul.reorder-photos-list li').each(function() {
          // console.log('good going');
            item_order.push($(this).attr("id"));
        });
        // var order_string =item_order;
        $.ajax({
            type: "GET",
            url: "{{url('/report/image/rearrange/save')}}",
            data: { "order_number":item_order,'imageIds':imageIds,'jafImageTypes':jafImageTypes},
            cache: false,
            success: function(data){ 
              if (data.fail == false) {
                console.log(data);
                if ( data.attachment_type=='main') {
                    $("#fileResult1"+"-"+data.report_item_id).html("");
                    var count = Object.keys(data.data).length;
                    // console.log(count);
                    for(var i=0; i < count; i++)
                    {
                    
                        // $("#"+fileResult+"-"+dynamicID).prepend("<div class='image-area' data-id='"+data.data[i]['file_id']+"'><img src='"+data.data[i]['filePrev']+"'  alt='Preview' title='"+data.data[i]['file_name']+"'><a class='remove-image' href='javascript:;' data-id='"+data.data[i]['file_id']+"' style='display: inline;'>&#215;</a><input type='hidden' name='fileID[]' value='"+dynamicID+'-'+data.data[i]['file_id']+"'></div>");
                        if(data.data[i].custome_img_name==null){
                        $("#fileResult1"+"-"+data.report_item_id).append("<div class='image-area' data-id='"+data.data[i]['file_id']+"'><img src='"+data.data[i]['fileIcon']+"'  alt='Preview' title='"+data.data[i]['file_name']+"'><a class='remove-image' href='javascript:;' data-id='"+data.data[i]['file_id']+"' style='display: inline;'>&#215;</a><input type='hidden' name='fileID[]' value='"+data.report_item_id+'-'+data.data[i]['file_id']+"'><span class='filename'>"+data.data[i].image_name+"</span></div>");
                        }else{
                            $("#fileResult1"+"-"+data.report_item_id).append("<div class='image-area' data-id='"+data.data[i]['file_id']+"'><img src='"+data.data[i]['fileIcon']+"'  alt='Preview' title='"+data.data[i]['file_name']+"'><a class='remove-image' href='javascript:;' data-id='"+data.data[i]['file_id']+"' style='display: inline;'>&#215;</a><input type='hidden' name='fileID[]' value='"+data.report_item_id+'-'+data.data[i]['file_id']+"'><span class='filename'>"+data.data[i].custome_img_name+"</span></div>");
 
                        }
                    }
                }
                else
                {
                    $("#fileResult2"+"-"+data.report_item_id).html("");
                    var count = Object.keys(data.data).length;
                    // console.log(count);
                    for(var i=0; i < count; i++)
                    {
                    
                        // $("#"+fileResult+"-"+dynamicID).prepend("<div class='image-area' data-id='"+data.data[i]['file_id']+"'><img src='"+data.data[i]['filePrev']+"'  alt='Preview' title='"+data.data[i]['file_name']+"'><a class='remove-image' href='javascript:;' data-id='"+data.data[i]['file_id']+"' style='display: inline;'>&#215;</a><input type='hidden' name='fileID[]' value='"+dynamicID+'-'+data.data[i]['file_id']+"'></div>");
                        if(data.data[i].custome_img_name==null){
                            $("#fileResult2"+"-"+data.report_item_id).append("<div class='image-area' data-id='"+data.data[i]['file_id']+"'><img src='"+data.data[i]['fileIcon']+"'  alt='Preview' title='"+data.data[i]['file_name']+"'><a class='remove-image' href='javascript:;' data-id='"+data.data[i]['file_id']+"' style='display: inline;'>&#215;</a><input type='hidden' name='fileID[]' value='"+data.report_item_id+'-'+data.data[i]['file_id']+"'><span class='filename'>"+data.data[i].image_name+"</span></div>");
                        }else{
                            $("#fileResult2"+"-"+data.report_item_id).append("<div class='image-area' data-id='"+data.data[i]['file_id']+"'><img src='"+data.data[i]['fileIcon']+"'  alt='Preview' title='"+data.data[i]['file_name']+"'><a class='remove-image' href='javascript:;' data-id='"+data.data[i]['file_id']+"' style='display: inline;'>&#215;</a><input type='hidden' name='fileID[]' value='"+data.report_item_id+'-'+data.data[i]['file_id']+"'><span class='filename'>"+data.data[i].custome_img_name+"</span></div>");

                        }
                    }
                }
              }
              
            }
        });
    }
    $(document).on('change', '.remark_msg', function (e) {
        e.preventDefault();  //stop the browser from following
            var _current =$(this);
            var id=_current.val();
            var data_id = $(this).attr('data-id');
            var data_item_id = $(this).attr('data-item_id');
            // alert(data_id);
            if (id =='custom') {
                $("#msg-"+data_item_id+'-'+data_id).removeClass('d-none');
                // $(".multiple").hide();
            }
            else {
                $("#msg-"+data_item_id+'-'+data_id).addClass('d-none');
                // $(".multiple").show();addClass
            }
        
    });
   $(document).on('click','.image-area > img',function(){ 
            
               var img_src =  $(this).attr("src");
               
                $('.showImg').attr('src',img_src);
                $('#myModal').modal();
               
   });
 });
   
   $(document).on('change','.approval_status',function(e){    
       var newVal = $(this).val(); 
       var itemId = $(this).parent().next().next('.itemId').val(); 
       
       var currentEl = $(this); 
        if(newVal != '4'){
           var t= $(currentEl).parent().next('.new-tag').html("<div class='form-group '><label class='text-danger'>Add Your Insuff Notes </label><input class='form-control' type='text' name='insuf_notes-"+itemId+"' ></div>");
        }else{
            $(currentEl).parent().next('.new-tag').html("");
        }
   });

   $(document).ready(function() {
      var curNum ='';
      var fileResult='fileResult2';
      var type = 'main';
      var number = '1';
      $(document).on('click','.clickSelectFile',function(){ 
         curNum     = $(this).attr('add-id');
         fileResult = $(this).attr('data-result');
         type       = $(this).attr('data-type');
         number     = $(this).attr('data-number');
        //  alert(fileResult);
         $(this).next('input[type="file"]').trigger('click');
      });
      //
      $(document).on('change','.fileupload',function(e){        
        uploadFile(curNum,fileResult,type,number);
      });

    // $(document).on('change', '.service_select', function (event) {

    //     var service_val=$('.service_select').val();
    //     var service_name =$(this).find('option:selected').attr("data-name");
    //     // var service_name =$(this).attr('data-name');
    //     if(service_name.trim().toLowerCase()=="Other".toLowerCase()){
    //     $(".attachment_name").css("display","block");
    //     }else{
    //     $(".attachment_name").css("display","none");
    //     }
    //     if(service_val){
    //     $('#buttonToSelect').css('display','block')
    //     }
    // });
    // $(document).on('change', '.service_add', function (event) {

    //     var service_val=$('.service_add').val();
    //     var service_name =$(this).find('option:selected').attr("data-name");
    //     // var service_name =$(this).attr('data-name');
    //     if(service_name.trim().toLowerCase()=="Other".toLowerCase()){
    //     $(".attached_file").css("display","block");
    //     }else{
    //     $(".attached_file").css("display","none");
    //     }
    //     if(service_val){
    //     $('#addSupporting').css('display','block')
    //     }
    // });
        $(document).on('change','.service_select_main',function(){ 
            selectedtype = $(this).attr('data-select');
            type = $(this).attr('data-type');
         });
         //
         $(document).on('change','.service_select_main',function(e){        
            selectFileType(selectedtype,type);
         });
         $(document).on('change','.service_select_supp',function(){ 
            selectedtype = $(this).attr('data-select');
            type = $(this).attr('data-type');
         });
         //
         $(document).on('change','.service_select_supp',function(e){        
            selectSuppFileType(selectedtype,type);
         });
        //remove file
        $(document).on('click','.remove-image',function(){ 

            // var r = confirm("Are you want to remove?");
            // if (r == true) {
                
            //     $('#fileupload-'+curNum).val("");
            //             var current = $(this);
            //             var file_id = $(this).attr('data-id');
            //             //
            //             var fd = new FormData();

            //             fd.append('file_id',file_id);
            //             fd.append('_token', '{{csrf_token()}}');
            //             //
            //             $.ajax({
            //                 type: 'POST',
            //                 url: "{{ url('/reports/remove/file') }}",
            //                 data: fd,
            //                 processData: false,
            //                 contentType: false,
            //                 success: function(data) {
            //                     console.log(data);
            //                     if (data.fail == false) {
            //                     //reset data
            //                     $('.fileupload').val("");
            //                     //append result
            //                     $(current).parent('.image-area').detach();
            //                     } else {
                                
            //                     console.log("file error!");
                                
            //                     }
            //                 },
            //                 error: function(error) {
            //                     console.log(error);
            //                     // $(".preview_image").attr("src","{{asset('images/file-preview.png')}}"); 
            //                 }
            //             });
            //     return false;
                
            // }
            var current = $(this);
            var file_id = $(this).attr('data-id');
            swal({
               // icon: "warning",
               type: "warning",
               title: "Are You Want to Remove?",
               text: "",
               dangerMode: true,
               showCancelButton: true,
               confirmButtonColor: "#007358",
               confirmButtonText: "YES",
               cancelButtonText: "CANCEL",
               closeOnConfirm: false,
               closeOnCancel: false
               },
               function(e){
                  if(e==true)
                  {
                    $('#fileupload-'+curNum).val("");
                        
                        //
                        var fd = new FormData();

                        fd.append('file_id',file_id);
                        fd.append('_token', '{{csrf_token()}}');
                        //
                        $.ajax({
                            type: 'POST',
                            url: "{{ url('/reports/remove/file') }}",
                            data: fd,
                            processData: false,
                            contentType: false,
                            success: function(data) {
                                console.log(data);
                                if (data.fail == false) {
                                //reset data
                                $('.fileupload').val("");
                                //append result
                                $(current).parent('.image-area').detach();
                                } else {
                                
                                console.log("file error!");
                                
                                }
                            },
                            error: function(error) {
                                console.log(error);
                                // $(".preview_image").attr("src","{{asset('images/file-preview.png')}}"); 
                            }
                        });
                        swal.close();
                  }
                  else
                  {
                    swal.close();
                  }
               }
            );

        });

        $(document).on('change','.reference_type',function(){
                var _this=$(this);
                var id = _this.attr('data-id');
                var report_id = _this.attr('data-report');
                var type = _this.val();
                if(type!='')
                {
                    $.ajax({
                            type:'POST',
                            url: "{{route('/report/reference_form')}}",
                            data: {"_token": "{{ csrf_token() }}","id":id,"type":type},        
                            success: function (response) {        
                            // console.log(response);

                            $('#reference_result-'+report_id).html(response);
                        },
                        error: function (data) {
                            // alert("Error: " + errorThrown);
                        }
                    });
                }
                else
                {

                    swal({
                        title: "Please Select The Reference Type !!",
                        text: '',
                        type: 'warning',
                        buttons: true,
                        dangerMode: true,
                        confirmButtonColor:'#003473'
                    });

                    $('#reference_result-'+report_id).html('');

                    // _this.attr('selectedIndex', '-1');
                }
        });

        $(document).on('submit','form#report_form',function (event) {
               event.preventDefault();
               //clearing the error msg
               $('p.error-container').html("");

               var form = $(this);
               var data = new FormData($(this)[0]);
               var url = form.attr("action");
               var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
               $('.report_submit').attr('disabled',true);
               // $('.form-control').attr('readonly',true);
               // $('.form-control').addClass('disabled-link');
               $('.error-control').attr('readonly',true);
               $('.error-control').addClass('disabled-link');
               if ($('.report_submit').html() !== loadingText) {
                     $('.report_submit').html(loadingText);
               }
               $.ajax({
                  type: form.attr('method'),
                  url: url,
                  data: data,
                  cache: false,
                  contentType: false,
                  processData: false,      
                  success: function (response) {
                     window.setTimeout(function(){
                           $('.report_submit').attr('disabled',false);
                           // $('.form-control').attr('readonly',false);
                           // $('.form-control').removeClass('disabled-link');
                           $('.error-control').attr('readonly',false);
                           $('.error-control').removeClass('disabled-link');
                           $('.report_submit').html('Save');
                        },2000);
                    //  console.log(response);
                     if(response.success==true) {          
                           // var case_id = response.case_id;
                           //notify
                           toastr.success("Report Has Been Updated Successfully");
                           // redirect to google after 5 seconds
                           window.setTimeout(function() {
                              //window.location = "{{ url('/')}}"+"/reports/";
                              window.location.reload();
                           }, 2000);
                     
                     }
                     //show the form validates error
                     if(response.success==false ) {      
                            var i = 0;                        
                           for (control in response.errors) {  
                              $('#error-' + control).html(response.errors[control]);
                              if(i==0)
                              {
                                $('select[name='+control+']').focus();
                                $('input[name='+control+']').focus(); 
                                $('textarea[name='+control+']').focus();
                              }
                              i++;
                           }
                     }
                  },
                  error: function (xhr, textStatus, errorThrown) {
                     // alert("Error: " + errorThrown);
                  }
               });
               return false;
        });

        $(document).on('click','.verified_data',function (event) {
         var current_data = $(this);
         var check_id = $(this).attr('data-id');
         var status = $(this).prop('checked');
         var r =swal({
                     title: "Are you sure?",
                     text: "While confirming this status, please make sure about Verification data or attachment submitted!",
                     type: "warning",
                     dangerMode: true,
                     showCancelButton: true,
                    //  cancelButtonColor: "#DD6B55",
                     confirmButtonColor: "#007358",
                     confirmButtonText: "YES",
                     cancelButtonText: "CANCEL",
                     closeOnConfirm: false,
                     closeOnCancel: false
                     },
                     function(e){
                        //Use the "Strict Equality Comparison" to accept the user's input "false" as string)
                        // if check the checkox
                        if (status== true) {
                           if (e===false) {
                              current_data.prop('checked',false);
                              // toastr.success("New Check added  successfully");
                                 // redirect to google after 5 seconds
                                 swal.close();
                           // console.log("Do here everything you want");
                           } else {
                              current_data.prop('checked',true);
                              swal.close();
                              // swal("Oh no...");
                              // console.log("The user says: ",e);
                           }
                           
                        } // if uncheck the checkox
                        else {
                           if (e===false) {
                              current_data.prop('checked',true);
                           // swal("Ok done!","!");
                           swal.close();
                           // console.log("Do here everything you want");
                           } else {
                              current_data.prop('checked',false);
                              // swal("Oh no...");
                              swal.close();
                              // console.log("The user says: ",e);
                           }
                        }
                    
                  }
                  );
            // if (r == true){
            //    // $(this).attr('disabled','disabled');
            //    // alert('mil gyi id ?'+ check_id);
            // }
        });

        $(document).on('click','.report_complete',function (event) {
         var current_data = $(this);
         var status = $(this).prop('checked');
         var r =swal({
                     title: "Are You Sure?",
                     text: "While confirming this status, please make sure about Verification data or attachment submitted!",
                     type: "warning",
                     dangerMode: true,
                     showCancelButton: true,
                    //  cancelButtonColor: "#DD6B55",
                     confirmButtonColor: "#007358",
                     confirmButtonText: "YES",
                     cancelButtonText: "CANCEL",
                     closeOnConfirm: false,
                     closeOnCancel: false
                     },
                     function(e){
                        //Use the "Strict Equality Comparison" to accept the user's input "false" as string)
                        // if check the checkox
                        if (status== true) {
                           if (e===false) {
                              current_data.prop('checked',false);
                              // toastr.success("New Check added  successfully");
                                 // redirect to google after 5 seconds
                                 swal.close();
                           // console.log("Do here everything you want");
                           } else {
                              current_data.prop('checked',true);
                              swal.close();
                              // swal("Oh no...");
                              // console.log("The user says: ",e);
                           }
                           
                        } // if uncheck the checkox
                        else {
                           if (e===false) {
                              current_data.prop('checked',true);
                           // swal("Ok done!","!");
                           swal.close();
                           // console.log("Do here everything you want");
                           } else {
                              current_data.prop('checked',false);
                              // swal("Oh no...");
                              swal.close();
                              // console.log("The user says: ",e);
                           }
                        }
                    
                  }
                  );
            // if (r == true){
            //    // $(this).attr('disabled','disabled');
            //    // alert('mil gyi id ?'+ check_id);
            // }
        });


   });
   function selectFileType(selectedtype){
      // var serviceOptionval = document.getElementById("service_select_main-"+selectedtype)

     var serviceOptionval= $("#service_select_main-"+selectedtype).val();
     var service_name =$("#service_select_main-"+selectedtype).find('option:selected').attr("data-name");
     if(service_name=="Other"){
      $("#attachment_name-"+selectedtype).css("display","block");
      $("#buttonToSelect-"+selectedtype).css("display","none");
     }else if(serviceOptionval==""){
      $("#buttonToSelect-"+selectedtype).css("display","none");
      $("#attachment_name-"+selectedtype).css("display","none");
     }else{
      $("#attachment_name-"+selectedtype).css("display","none");
      $("#buttonToSelect-"+selectedtype).css("display","block");
     }
    $("#attachment_name-"+selectedtype).keyup(function () {
        var len =$("#attachment_name-"+selectedtype).val().length;
        if(len>0){
        $("#buttonToSelect-"+selectedtype).css("display","block");
        }else{
        $("#buttonToSelect-"+selectedtype).css("display","none");
        }
   
    });
   }
   function selectSuppFileType(selectedtype){
      var serviceOptionval= $("#service_select_supp-"+selectedtype).val();
      var service_name =$("#service_add_supp-"+selectedtype).find('option:selected').attr("data-name");
      if(service_name=="Other"){
      $("#attached_file-"+selectedtype).css("display","block");
      $("#addSupporting-"+selectedtype).css("display","none");
     }else if(serviceOptionval==""){
      $("#addSupporting-"+selectedtype).css("display","none");
      $("#attached_file-"+selectedtype).css("display","none");
     }else{
      $("#attached_file-"+selectedtype).css("display","none");
      $("#addSupporting-"+selectedtype).css("display","block");
     }
    $("#attached_file-"+selectedtype).keyup(function () {
        var len =$("#attached_file-"+selectedtype).val().length;
        if(len>0){
        $("#addSupporting-"+selectedtype).css("display","block");
        }else{
        $("#addSupporting-"+selectedtype).css("display","none");
        }
   }); 
   }


function uploadFile(dynamicID,fileResult,type,number){
$("#fileUploadProcess").html("<img src='{{asset('images/process-horizontal.gif')}}' >");

var attached_file_type='';
var attached_file_name=''; 

var fd = new FormData();
var ins = document.getElementById("file"+number+"-"+dynamicID).files.length;
// alert(ins);
for (var x = 0; x < ins; x++) {
    fd.append("files[]", document.getElementById("file"+number+"-"+dynamicID).files[x]);
}

if(type=="supporting"){
    attached_file_type = $('#service_add_supp-'+dynamicID).val();
    attached_select_option =$("#service_add_supp-"+dynamicID).find('option:selected').attr("data-name");
    attached_file_name=$('#attached_file-'+dynamicID).val();
}
else if(type=='main')
{
    attached_file_type = $("#service_select_main-"+dynamicID).val();
    attached_select_option =$("#service_select_main-"+selectedtype).find('option:selected').attr("data-name");
    attached_file_name=$("#attachment_name-"+dynamicID).val();
}

fd.append('report_id',"{{ base64_encode($report_id) }}");
fd.append('report_item_id',dynamicID);
fd.append('type',type);
fd.append('service_type',attached_file_type);
fd.append('select_file',attached_select_option);
fd.append('attachment_name',attached_file_name);
fd.append('_token', '{{csrf_token()}}');
//
$("."+fileResult+"-"+dynamicID).html('<div class="fa-3x"><i class="fas fa-spinner fa-pulse text-info"></i></div>');
$.ajax({
      type: 'POST',
      url: "{{ url('/reports/upload/file') }}",
      data: fd,
      processData: false,
      contentType: false,
      success: function(data) {
        // console.log(data);
        if (data.fail == false) {
        //reset data
        $('.fileupload').val("");
        $("#fileUploadProcess").html("");
        $(".service_select_main").html(window.location.reload());
            $(".service_select_supp").html(window.location.reload());
        //append result
        console.log(data.data);
        
        var count = Object.keys(data.data).length;

        for(var i=0; i < count; i++)
        {
            if(data.data[i]['file_type']=='pdf')
            {
                $.each(data.data[i]['file_id'],function(key,value){
                    // $("#"+fileResult+"-"+dynamicID).prepend("<div class='image-area' data-id='"+value+"'><img src='"+data.data[i]['filePrev'][key]+"'  alt='Preview' title='"+data.data[i]['file_name'][key]+"'><a class='remove-image' href='javascript:;' data-id='"+value+"' style='display: inline;'>&#215;</a><input type='hidden' name='fileID[]' value='"+dynamicID+'-'+value+"'></div>");
                        if(data.data[i].select_file!="Other"){
                                $("#"+fileResult+"-"+dynamicID).append("<div class='image-area' data-id='"+value+"'><img src='"+data.data[i]['filePrev'][key]+"'  alt='Preview' title='"+data.data[i]['file_name'][key]+"'><a class='remove-image' href='javascript:;' data-id='"+value+"' style='display: inline;'>&#215;</a><input type='hidden' name='fileID[]' value='"+dynamicID+'-'+value+"'><span class='filename' value='"+data.data[i].select_file+"'>"+data.data[i].select_file+"</span></div>");
                            }else{
                                $("#"+fileResult+"-"+dynamicID).append("<div class='image-area' data-id='"+value+"'><img src='"+data.data[i]['filePrev'][key]+"'  alt='Preview' title='"+data.data[i]['file_name'][key]+"'><a class='remove-image' href='javascript:;' data-id='"+value+"' style='display: inline;'>&#215;</a><input type='hidden' name='fileID[]' value='"+dynamicID+'-'+value+"'><span class='filename' value='"+data.data[i].select_file+"'>"+data.data[i].customeval+"</span></div>");
                        }
                });
            }
            else
            {
                // $("#"+fileResult+"-"+dynamicID).prepend("<div class='image-area' data-id='"+data.data[i]['file_id']+"'><img src='"+data.data[i]['filePrev']+"'  alt='Preview' title='"+data.data[i]['file_name']+"'><a class='remove-image' href='javascript:;' data-id='"+data.data[i]['file_id']+"' style='display: inline;'>&#215;</a><input type='hidden' name='fileID[]' value='"+dynamicID+'-'+data.data[i]['file_id']+"'></div>");
                    if(data.data[i].select_file!="Other"){
                            $("#"+fileResult+"-"+dynamicID).append("<div class='image-area' data-id='"+data.data[i]['file_id']+"'><img src='"+data.data[i]['filePrev']+"'  alt='Preview' title='"+data.data[i]['file_name']+"'><a class='remove-image' href='javascript:;' data-id='"+data.data[i]['file_id']+"' style='display: inline;'>&#215;</a><input type='hidden' name='fileID[]' value='"+dynamicID+'-'+data.data[i]['file_id']+"'><span class='filename' value='"+data.data[i].select_file+"'>"+data.data[i].select_file+"</span></div>");
                        }else{
                            $("#"+fileResult+"-"+dynamicID).append("<div class='image-area' data-id='"+data.data[i]['file_id']+"'><img src='"+data.data[i]['filePrev']+"'  alt='Preview' title='"+data.data[i]['file_name']+"'><a class='remove-image' href='javascript:;' data-id='"+data.data[i]['file_id']+"' style='display: inline;'>&#215;</a><input type='hidden' name='fileID[]' value='"+dynamicID+'-'+data.data[i]['file_id']+"'><span class='filename' value='"+data.data[i].select_file+"'>"+data.data[i].customeval+"</span></div>");
                    }
            }
        }

        $("."+fileResult+"-"+dynamicID).html("");


        // $.each(data.data, function(key, value) {
        //     $("#"+fileResult+"-"+dynamicID).prepend("<div class='image-area' data-id='"+value.file_id+"'><img src='"+value.filePrev+"'  alt='Preview' title='"+value.file_name+"'><a class='remove-image' href='javascript:;' data-id='"+value.file_id+"' style='display: inline;'>&#215;</a><input type='hidden' name='fileID[]' value='"+dynamicID+'-'+value.file_id+"'></div>");
        // });
            
        } else {
          $("#fileUploadProcess").html("");
            //   alert("Please upload valid file! allowed file type, Image JPG, PNG, PDF etc. ");
            swal({
            title: "Oh no!",
            text: 'Please upload valid file! allowed file type, Image JPG, PNG, PDF etc.',
            type: 'error',
            buttons: true,
            dangerMode: true,
            confirmButtonColor:'#003473'
            });

          $("."+fileResult+"-"+dynamicID).html("");
          console.log("file error!");
          
        }
      },
      error: function(error) {
          console.log(error);
          // $(".preview_image").attr("src","{{asset('images/file-preview.png')}}"); 
      }
  });
  
  return false;
}

//remove file
$(document).on('change','.remark_msg',function(){ 
    var _this = $(this);
    var msg     = $(this).attr('data-item_id');
    var id     = $(this).attr('data-id');
    var value = $(this).val();
    //  alert(value);
    if(value ==''){
    $('#remarks-input-checkbox-'+msg+'-'+id).prop('checked',false);
    }
    else{
    $('#remarks-input-checkbox-'+msg+'-'+id).prop('checked',true);
    }   
    // remarks-input-checkbox-{{ $item->id.'-'.$i}}
});
</script>  
@endsection