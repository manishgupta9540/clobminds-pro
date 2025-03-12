@extends('layouts.admin')
<style>
   .disabled-link{
      pointer-events: none;
   }
   .disabled-link-1{
      pointer-events: none;
   }
   .disabled-link-2{
      pointer-events: none;
   }
   .sweet-alert p {
      text-align: left !important; 
   }

   .overflow-modal
   {
    max-height: 400px;
    overflow-x: hidden;
    overflow-y: scroll;
   }
   .modal {
    
    z-index: 9999!important;
   }
   .Datepicker
   {
      z-index:20000000 !important;
   }

   /* .tooltips {
    
    z-index: 99999!important;
   } */

   /* .modal-header .close {
    padding: 1rem;
    margin: -5rem -2rem -1rem auto;
   } */
  /* .bcd_loading{
   padding-top: 20%;
    text-align: center;
  } */

/* .swal-button--confirm {
  padding: 7px 19px;
  border-radius: 2px;
  background-color: #4962B3;
  font-size: 15px;
  border: 1px solid #3e549a;
  text-shadow: 0px -1px 0px rgba(0, 0, 0, 0.3);
}
.swal-button--cancel {
  padding: 7px 19px;
  border-radius: 2px;
  background-color: #4962B3;
  color: #cdd1d8;
  font-size: 15px;
  border: 1px solid #3e549a;
  text-shadow: 0px -1px 0px rgba(0, 0, 0, 0.3);
} */

.sticky {
   position: fixed;
    top: 8%;
    width: 100%;
    z-index: 999;
    background: #eeeeee;
    border: 1px solid #eee;
    border-radius: 3px;
  
}

.sticky li{
   color: #fff !important;
}
.col-sm-11.breadcrum1 {
    position: relative;
    top: -9px;
}

.remove-image
    {
        padding: 0px 3px 0px !important;
    }

    .image-area img{
        height: 100px !important;
        width: 100px !important;
        padding: 8px !important;
    }
    .filename{
      font-size: 10px;
    }
    select.service_select {
    -webkit-appearance: auto;
    appearance: auto;
   }
    .image-area{
        width: 90px !important;
    }

    .remove-image:hover
    {
        padding: 0px 3px 0px !important;
    }

    #myImageModal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1; /* Sit on top */
    padding-top: 100px; /* Location of the box */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.9); /* Black w/ opacity */
  }
  /* Modal Content (image) */
.image-modal-content {
  margin: auto;
  display: block;
  width: 80%;
  max-width: 700px;
}

/* Caption of Modal Image */
#caption {
  margin: auto;
  display: block;
  width: 80%;
  max-width: 700px;
  text-align: center;
  color: #ccc;
  padding: 10px 0;
  height: 150px;
}

/* Add Animation */
.image-modal-content, #caption {  
  -webkit-animation-name: zoom;
  -webkit-animation-duration: 0.6s;
  animation-name: zoom;
  animation-duration: 0.6s;
}

@-webkit-keyframes zoom {
  from {-webkit-transform:scale(0)} 
  to {-webkit-transform:scale(1)}
}

@keyframes zoom {
  from {transform:scale(0)} 
  to {transform:scale(1)}
}

/* The Close Button */
.closeImage {
  position: absolute;
  top: 60px;
  right: 20px;
  color: #f1f1f1;
  font-size: 40px;
  font-weight: bold;
  transition: 0.3s;
}

.closeImage:hover,
.closeImage:focus {
  color: #bbb;
  text-decoration: none;
  cursor: pointer;
}

/* 100% Image Width on Smaller Screens */
@media only screen and (max-width: 700px){
  .image-modal-content {
    width: 100%;
  }
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
.address_data{
   max-height: 300px;
    overflow-x: hidden;
    overflow-y: scroll;
}

</style>
@section('content')
@php
   use App\Traits\S3ConfigTrait;
@endphp
<div class="main-content-wrap sidenav-open d-flex flex-column">
<!-- ============ Body content start ============= -->
<div class="main-content">
   <div class="row">
      <div class="col-sm-11 breadcrum1">
          <ul class="breadcrumb">
          <li><a href="{{ url('/home') }}">Dashboard </a></li>
          <li><a href="{{ url('/candidates') }}">Candidate</a></li>
          <li>BGV QC</li>
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
      <div class="card-body" style="padding:0px">
         <div class="row">
            <div class="col-md-3 col-12">
               <div class="span10 offset1">
                  <div id="modalTab">
                     <div class="tab-content">
                        <div class="tab-pane active" id="about">
                           <img src="{{ asset('admin/images/profile-default-avtar.jpg') }}" name="aboutme" width="140" height="140" border="0" class="img-circle">
                           <p class="m-0 text-24"> {{ $candidate->name}} 
                              {{-- <a class="text-success mr-2" href="#"><i class="nav-icon i-Pen-2 font-weight-bold" style="font-size: 10px;"></i></a> --}}
                           </p>
                           <p class="text-muted m-0"> &nbsp; </p>
                           @include('admin.candidates.profile-info')
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="col-md-9 col-12" style="background: #f6f8fc;">
               <h4 class="card-title mb-3"> </h4>
               <ul class="nav nav-pills" id="myPillTab" role="tablist" style="border-bottom: 1px solid #cdd1d8;">
                  <li class="nav-item"><a class="nav-link active show" id="profile-icon-pill" data-toggle="pill" href="#profilePIll" > BGV for QC </a></li>
               </ul> 
               <div class="tab-content" id="myPillTabContent" style="background: #fff;">
                  
                  <div class="tab-pane fade active show" id="homePIll" role="tabpanel" aria-labelledby="home-icon-pill">

                        <div class="inbox-main-content sidebar-content" data-sidebar-content="main">
                       
                        @if($candidate->jaf_status=='filled')
                           <div class="col-md-12">
                              <form class="mt-2" method="post" enctype="multipart/form-data" action="{{ url('/candidates/jafQCUpdate') }}" id="jaf_form">
                                 @csrf
                                 <!-- candidate info  -->
                                 <input type="hidden" name="candidate_id" value="{{ Request::segment(3) }}">
                                 <input type="hidden" name="report_id" value="{{ base64_encode($report_id) }}">
                              
                                 <div class="row">
                                   @if ($message = Session::get('success'))
                                    <div class="col-md-12">
                                       <div class="alert alert-success">
                                          <strong>{{ $message }}</strong> 
                                       </div>
                                    </div>
                                    @endif
                                    <?php 
                                       
                                          $file_arr   = [];
                                          $file_arr   = Helper::get_jaf_attachFile($candidate->id);
                                          $url        = '';
                                          $filename   = NULL;
                                          $file_platform = NULL; 

                                          if(count($file_arr)>0)
                                          {
                                             $filename = $file_arr['file_name'];
                                             $file_platform = $file_arr['file_platform'];
                                             // $filename = Helper::get_jaf_attachFile($candidate->id);
                                             $extension = pathinfo($filename, PATHINFO_EXTENSION);
                                             //   dd($extension);
                                             if(stripos($file_platform,'s3')!==false)
                                             {
                                                $filePath = 'uploads/jaf_details/';
                                                $s3_config = S3ConfigTrait::s3Config();
                                                $disk = \Storage::disk('s3');

                                                $command = $disk->getDriver()->getAdapter()->getClient()->getCommand('GetObject', [
                                                      'Bucket'                     => \Config::get('filesystems.disks.s3.bucket'),
                                                      'Key'                        => $filePath.$filename,
                                                      'ResponseContentDisposition' => 'attachment;'//for download
                                                ]);

                                                $req = $disk->getDriver()->getAdapter()->getClient()->createPresignedRequest($command, '+10 minutes');
                                                $url = $req->getUri();
                                             }
                                             else {
                                                $url = url('/').'/uploads/jaf_details/'.$filename;
                                             }
                                          }
                                    ?>
                                    <div class="col-12">
                                       <div class="row">
                                          <div class="col-sm-9">
                                             <h4 class="card-title mb-3 mt-2">Candidate: <b> {{ $candidate->name }} ({{Helper::user_reference_id($candidate->id)}}) </h4>
                                          </div>
                                          <div class="col-sm-3" style="float:right">
                                             @if ( $filename)
                                                @if ($extension=='zip')
                                                <a class="btn btn-link" href="{{$url}}" title="download" style="float:right">Candidate's JAF<i class="fas fa-download"></i></a>
                                                @endif
                                                @if ($extension=='pdf' || $extension=='xlsx' || $extension=='csv' || $extension=='docs' || $extension=='docx')
                                                   <a class="btn btn-link" href="{{$url}}" title="download"   target="_blank" style="float:right">Candidate's JAF<i class="fas fa-download"></i></a>
                                                @endif
                                                @if ( $extension=='png' || $extension=='jpeg' || $extension=='jpg')
                                                   <a class="btn btn-link" href="{{$url}}"  download  target="_blank" style="float:right">Candidate's JAF<i class="fas fa-download"></i></a>
                                                @endif
                                                {{-- <a class="btn btn-link" href="{{url('/').'/uploads/jaf_details/'.$filename}}" title="download">Candidate's JAF <i class="fas fa-download"></i></a> --}}
                                             @endif
                                          </div>
                                       </div>
                                    </div>

                                 </div>
                                    

                                    <div class="row">
                                    
                                    <!-- start JAF QC done -->
                                    <div class="col-sm-3">
                                    <?php  ?>
                                    @if($job->is_qc_done== 0)
                                        @php
                                            $disable_attr = '';
                                            $disable_link_cls = '';
                                            $jaf_report_check = '';
                                        @endphp  

                                    @else       @php
                                                $disable_attr = 'disabled';
                                                $disable_link_cls = 'disabled-link';
                                                $jaf_report_check = 'checked';
                                                $dc_done_time = date('d-M-Y h:i A', strtotime($job->qc_done_at));

                                            @endphp
                                    @endif

                                    <!--  -->
                                    
                                    <div class="form-check  pt-2" >
                                       <label class="check-inline {{$disable_link_cls}}" style="font-size: 14px;">
                                       <input type="checkbox" class="jaf-ready-report" id="jaf-ready-report" name="jaf-qc-done" class="form-check-input" {{$jaf_report_check}} {{$disable_attr}}> Mark as QC Done
                                       </label>
                                    </div>
                                    
                                    </div>
                                    <!-- end JAF QC done -->
                                    <div class="col-sm-6">
                                       @if($job->is_qc_done== 1)
                                       @php $qc_by = Helper::get_user_fullname($job->qc_done_by); @endphp
                                          <div class="form-check  pt-2"> at {{$dc_done_time}}, By {{ $qc_by }}  </div>
                                       @endif    
                                    </div>
                                    <!--  -->
                                    </div>
                                      
                                 </div>
                                
                                 
                                 <?php $user = Auth::user()->user_type; ?>
                               
                                    <!-- service item -->
                                    @if( count($jaf_items) > 0  )
                                          <?php $report_status = Helper::get_report_status($candidate->id);?>
                                          @foreach($jaf_items as $item)
                                             <?php
                                                //get SLA item count
                                                $i=0;
                                                $k=0;
                                                $l=0;
                                                $num ="";
                                                // $report_supplementry_status = Helper::get_supplementry_report_status($candidate->id,$item->service_id,$item->check_item_number,$item->is_supplementary);   
                                             ?>
                                             <input type="hidden" value="{{ $item->id }}" name="jaf_id[]">
                                             <div class="row" style="padding: 10px 0; margin-top:10px; border:1px solid #ddd;">
                                                   {{-- @foreach ($errors->all() as $error)
                                                      <li class="text-danger">{{ $error }}</li>
                                                   @endforeach --}}

                                                <div class="col-md-6">
                                                    <?php
                                                         $readonly='';
                                                         $disabled_link='';
                                                         if($report_status!=NULL && ($report_status['status']=='completed' || $report_status=='interim') && $item->is_supplementary=='0')
                                                         {
                                                            $readonly="readonly"; 
                                                            $disabled_link="disabled-link";
                                                         }
                                                      ?>
                                                   <h3 class=" mb-2 mt-2">Verification - {{ $item->service_name }} {{stripos($item->verification_type,'Manual')!==false ? ' - '.$item->check_item_number : ''}}</h3>
                                                   <div class="row">
                                                     
                                                      <div class="col-sm-4">
                                                         <div class="form-group">
                                                            @if($disabled_link=='')
                                                               <button class="btn btn-sm btn-outline-info addChargesBtn {{$disabled_link}}" type="button" data-id="{{base64_encode($item->id)}}"> Additional Charge</button>
                                                            @endif
                                                         </div>
                                                      </div>
                                                      {{-- <div class="col-sm-4">
                                                         <div class="form-group">
                                                            <div class="form-check">
                                                               <label class="check-inline {{$disabled_link}}">
                                                                  <input type="checkbox" data-id="{{ $item->id }}" name="verified-input-checkbox-{{ $item->id}}" class="form-check-input verified_data" @if ($item->is_data_verified=='1') checked  disabled @endif>Data Verified ?
                                                               </label>
                                                            </div>
                                                         </div>
                                                      </div> --}}
                                                     
                                                   </div>

                                                   <div class="row">
                                                      <div class="col-sm-6">
                                                          <div class="form-group">
                                                            <div class="form-check">
                                                              <label class="form-check-label error-control">
                                                                <input style="margin-top: 1px;" type="checkbox" class="form-check-input error-control check_ignore"  data-id="{{ $item->id }}" name="check_ignore-{{ $item->id }}" > Ignore
                                                              </label>
                                                            </div>
                                                          </div>
                                                      </div>

                                                      <div class="col-sm-6">
                                                         <div class="form-group">
                                                           <div class="form-check">
                                                            <label class="form-check-label error-control">
                                                               <input style="margin-top: 1px;" type="checkbox" class="form-check-input error-control" name="api_hits_counter" disabled
                                                                     value="on" {{ $item->api_hits_counter == 1 ? 'checked disabled' : '' }} > Api Hits

                                                           </label>
                                                           </div>
                                                         </div>
                                                       </div>
                                                    </div>
                                                    
                                                      <!-- if check type is address  -->
                                                      @if($item->service_id == '1')
                                                         @php
                                                            $digital_data = Helper::get_digital_data($item->id);
                                                            $address_ver = Helper::addressVerificationData($item->id);
                                                         @endphp
                                                         <div class="row">
                                                            <div class="col-sm-4">
                                                               <div class="form-group">
                                                                 <!--  <div class="form-check">
                                                                     <label class="check-inline @if($digital_data!=NULL) disabled-link @endif {{$disabled_link}}">
                                                                        <input type="checkbox" data-digital_id="{{ $item->id }}" name="digital-verification-checkbox-{{ $item->id}}" class="form-check-input digital_verification" @if ($digital_data) checked disabled @endif>Digital Verification
                                                                     </label>
                                                                  </div> -->
                                                               </div>
                                                            </div>
                                                             @if($digital_data!=NULL && ($address_ver==NULL || $address_ver->status==0))  
                                                               <div class="col-sm-3">
                                                                        <a href="{{url('/address-verification-form',['id'=>base64_encode($item->id)])}}" class="btn btn-sm btn-outline-success form-link" data-toggle="tooltip" target="_blank">Form Link </a>
                                                               </div>
                                                            @endif
                                                            <div class="col-sm-4">
                                                               @if($address_ver!=NULL && $digital_data!=NULL && $digital_data->status=='1')
                                                                  <div class="form-group">
                                                                    {{-- <button class="btn btn-outline-success address-verification-data" type="button" data-id="{{base64_encode($item->id)}}"> Verification Data </button>}}

                                                                     {{-- <a href="{{url('/candidates/digital_address_verification',['id'=>base64_encode($item->id)])}}" class="btn btn-outline-success" data-id="{{base64_encode($item->id)}}"> Verification Data</a> --}}

                                                                      <a href="{{url('/candidates/digital_address_verification',['id'=>base64_encode($item->id)])}}" class="btn btn-sm btn-outline-success" data-id="{{base64_encode($item->id)}}">Digital Verification Data</a>
                                                                  </div>
                                                               @endif
                                                            </div>
                                                         </div>
                                                         <div class="row" >
                                                            <div class="col-sm-12">
                                                                  <div class="form-group">
                                                                  <label>Address Type <span class="text-danger">*</span></label>
                                                                     <select class="form-control {{$disabled_link}} address-type-{{$item->id}}" name="address-type-{{$item->id}}" {{$readonly}} >
                                                                        <option value="">- Select Type -</option>
                                                                        <option value="current" @if($item->address_type !=null) @if($item->address_type=='current') selected @endif @endif > Current </option>
                                                                        <option value="permanent" @if($item->address_type !=null) @if($item->address_type=='permanent') selected @endif @endif >Permanent</option>
                                                                        <option value="previous" @if($item->address_type !=null) @if($item->address_type=='previous') selected @endif @endif >Previous</option>
                                                                     </select>
                                                                     {{-- @if ($errors->has('address-type-'.$item->id))
                                                                        <div class="error pt-2 text-danger">
                                                                           {{ $errors->first('address-type-'.$item->id) }}
                                                                        </div>
                                                                     @endif --}}
                                                                     <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-address-type-{{$item->id}}"></p>
                                                                  </div>
                                                            </div>
                                                         </div>
                                                      @endif
                                                      <!--  -->

                                                      <!--  -->
                                                      <?php 
                                                            $report_data = Helper::get_report_data($item->id,$item->service_id,$item->check_item_number);
                                                            $input_item_data = $report_data->jaf_data;
                                                            // dd($input_item_data);
                                                            $reference_item_data = $report_data->reference_form_data;
                                                            $input_item_data_array=[];
                                                            if($input_item_data != null){
                                                               $input_item_data_array =  json_decode($input_item_data, true); 

                                                      ?>
                                                         @foreach($input_item_data_array as $key => $input)
                                                            
                                                         <?php 
                                                            $key_val = array_keys($input); $input_val = array_values($input); 
                                                                  $labelname = '';
                                                            if($item->type_name=='global_database'){
                                                               if(stripos($key_val[0],'Criminal Records Database Checks - India')!==false)
                                                               {
                                                                  $labelname = 'd-none';
                                                               }
                                                               elseif(stripos($key_val[0],'Civil Litigation Database Checks – India')!==false)
                                                               {
                                                                  $labelname = 'd-none';
                                                               }
                                                               elseif(stripos($key_val[0],'Credit and Reputational Risk Database Checks – India')!==false)
                                                               {
                                                                  $labelname = 'd-none';
                                                               }
                                                               elseif(stripos($key_val[0],'Serious and Organized Crimes Database Checks – Global')!==false)
                                                               {
                                                                  $labelname = 'd-none';
                                                               }
                                                               elseif(stripos($key_val[0],'Global Regulatory Bodies')!==false)
                                                               {
                                                                  $labelname = 'd-none';
                                                               }
                                                               elseif(stripos($key_val[0],'Compliance Database')!==false)
                                                               {
                                                                  $labelname = 'd-none';
                                                               }
                                                               elseif(stripos($key_val[0],'Sanction & PEP - Global')!==false)
                                                               {
                                                                  $labelname = 'd-none';
                                                               }
                                                               elseif(stripos($key_val[0],'Web and Media Searches – Global')!==false)
                                                               {
                                                                  $labelname = 'd-none';
                                                               }
                                                               
                                                            } 
                                                         ?>
                                                            <div class="row {{$labelname}}">
                                                               <div class="col-sm-12">
                                                                     <div class="form-group">
                                                                     <?php $key_val = array_keys($input); $input_val = array_values($input); 

                                                                           $university_board =  $readonly= "";
                                                                           $university_board_id="";
                                                                           $date_calss='';
                                                                           $input_class='error-control';
                                                                           if($key_val[0] =='University Name / Board Name'){ 
                                                                              $university_board_id = "#searchUniversity_board";
                                                                              $university_board = "searchUniversity_board";
                                                                           }
                                                                           if($report_status!=NULL && ($report_status['status']=='completed' || $report_status=='interim') && $item->is_supplementary=='0')
                                                                           {
                                                                              $readonly="readonly";
                                                                           }
                                                                        //name
                                                                        if($key_val[0]=='First Name' || $key_val[0]=='First name' || $key_val[0]=='first name'){ 
                                                                           $name = $candidate->first_name;
                                                                           $readonly ="readonly";
                                                                           $input_class='';
                                                                        }
                                                                        if($key_val[0]=='Candidate Name' || $key_val[0]=='Candidate Name' || $key_val[0]=='candidate name'){ 
                                                                           $name = $candidate->first_name;
                                                                           $readonly ="readonly";
                                                                           $input_class='';
                                                                        }
                                                                        if($key_val[0]=='Last Name' || $key_val[0]=='Last name' || $key_val[0]=='last name'){ 
                                                                           $name = $candidate->last_name;
                                                                           $readonly ="readonly";
                                                                           $input_class='';
                                                                        }
                                                                        if($key_val[0]=='Date of Birth' || $key_val[0]=='DOB' || $key_val[0]=='dob'){ 
                                                                           // $dob = $candidate->dob;
                                                                           // if($dob !=NULL){
                                                                           //   $name = date('d-m-Y',strtotime($candidate->dob));
                                                                           // }
                                                                           $date_calss = 'commonDatepicker';
                                                                        }
                                                                        if(stripos($key_val[0],'Date of Expire')!==false)
                                                                        {
                                                                           $date_calss = 'commonDatepicker';
                                                                        }

                                                                        if(stripos($key_val[0],'Email Address')!==false)
                                                                        {
                                                                           $name = $candidate->email;
                                                                           $readonly ="readonly";
                                                                           $input_class='';
                                                                        }

                                                                       

                                                                        $country_name = Helper::get_country_list();
                                                                     
                                                                     ?>
                                                                        @if($item->service_id==17)
                                                                           @if(stripos($key_val[0],'Reference Type (Personal / Professional)')!==false)
                                                                              <label>  {{ $key_val[0]}} <span class="text-danger">*</span></label>
                                                                              <input type="hidden" name="service-input-label-{{ $item->id.'-'.$i }}" value="{{ $key_val[0]}}">
                                                                                 <select class="form-control {{$date_calss.''.$university_board }} service-input-value-{{$item->id.'-'.$i}} {{$input_class}} reference_type {{$disabled_link}} check-input-{{$item->id}}" {{$readonly}} id="{{ $university_board_id }}" name="service-input-value-{{ $item->id.'-'.$i }}" data-id="{{base64_encode($report_data->id)}}" data-jaf="{{$item->id}}">
                                                                                    <option value="">--Select--</option>
                                                                                    <option @if(stripos($input_val[0],'personal')!==false) selected @endif value="personal">Personal</option>
                                                                                    <option @if(stripos($input_val[0],'professional')!==false) selected @endif value="professional">Professional</option>
                                                                                 </select>
                                                                           @else
                                                                                 <label>  {{ $key_val[0]}} </label>
                                                                                 <input type="hidden" name="service-input-label-{{ $item->id.'-'.$i }}" value="{{ $key_val[0]}}">
                                                                                 <input class="form-control {{ $university_board }} {{$input_class}} {{$disabled_link}} {{$date_calss}} check-input-{{$item->id}}" {{$readonly}} id="{{ $university_board_id }}" type="text" name="service-input-value-{{ $item->id.'-'.$i }}" value="{{ $input_val[0] }}">
                                                                           @endif
                                                                        @elseif($item->service_id==15)
                                                                           @if ($key_val[0]=='Address Type')
                                                                              <label>  {{ $key_val[0]}} </label><br>
                                                                              <input type="hidden" name="service-input-label-{{ $item->id.'-'.$i }}" value="{{ $key_val[0]}}">
                                                                             <select class="form-control {{$date_calss.' '.$university_board }} service-input-value-{{$item->id.'-'.$i}} {{$input_class}} check-input-{{$item->id}}" name="service-input-value-{{ $item->id.'-'.$i }}" >
                                                                                 <option value="">--Select--</option>
                                                                                 <option @if(stripos($input_val[0],'current')!==false) selected @endif value="current">Current</option>
                                                                                 <option @if(stripos($input_val[0],'permanent')!==false) selected  @endif value="permanent">Permanent</option>
                                                                                 <option @if(stripos($input_val[0],'current_permanent')!==false) selected  @endif value="current_permanent">Current + Permanent</option>
                                                                                 <option @if(stripos($input_val[0],'previous')!==false) selected  @endif value="previous">Previous</option>
                                                                             </select> 
                                                                           @else
                                                                              <label>  {{ $key_val[0]}} </label><br>
                                                                              <input type="hidden" name="service-input-label-{{ $item->id.'-'.$i }}" value="{{ $key_val[0]}}">
                                                                              <input class="form-control {{$date_calss.''.$university_board }} service-input-value-{{$item->id.'-'.$i}} {{$input_class}} check-input-{{$item->id}}" {{$readonly}} id="{{ $university_board_id }}" type="text" name="service-input-value-{{ $item->id.'-'.$i }}" value="{{ $input_val[0] }}" >
                                                                           @endif

                                                                           @elseif($item->type_name=='global_database')
                                                                              @if ($key_val[0]=='Country')
                                                                                 <label>  {{ $key_val[0]}} </label><br>
                                                                                 <input type="hidden" name="service-input-label-{{ $item->id.'-'.$i }}" value="{{ $key_val[0]}}">
                                                                                 <select class="form-control {{$date_calss.' '.$university_board }} service-input-value-{{$item->id.'-'.$i}} {{$input_class}} check-input-{{$item->id}}" name="service-input-value-{{ $item->id.'-'.$i }}" >
                                                                                       @foreach ($country_name as $country) 
                                                                                          <option  value="{{$country->name}}" {{ $country->name ==  $input_val[0] ? 'selected' : '' }}>{{$country->name}}</option>
                                                                                       @endforeach
                                                                                 </select>
                                                                              @elseif ($key_val[0]=='Criminal Records Database Checks - India')
                                                                                 <label class="{{$labelname}}">  {{ $key_val[0]}} </label><br>
                                                                                 <input type="hidden" name="service-input-label-{{ $item->id.'-'.$i }}" value="{{ $key_val[0]}}">
                                                                                 <input class="form-control {{$date_calss.''.$university_board }} service-input-value-{{$item->id.'-'.$i}} {{$input_class}}" {{$readonly}} id="{{ $university_board_id }}" type="hidden" name="service-input-value-{{ $item->id.'-'.$i }}" value="{{ $input_val[0] }}">

                                                                              @elseif ($key_val[0]=='Civil Litigation Database Checks – India')
                                                                                 <label class="{{$labelname}}">  {{ $key_val[0]}} </label><br>
                                                                                 <input type="hidden" name="service-input-label-{{ $item->id.'-'.$i }}" value="{{ $key_val[0]}}">
                                                                                 <input class="form-control {{$date_calss.''.$university_board }} service-input-value-{{$item->id.'-'.$i}} {{$input_class}}" {{$readonly}} id="{{ $university_board_id }}" type="hidden" name="service-input-value-{{ $item->id.'-'.$i }}" value="{{ $input_val[0] }}">

                                                                              @elseif ($key_val[0]=='Credit and Reputational Risk Database Checks – India')
                                                                                 <label class="{{$labelname}}">  {{ $key_val[0]}} </label><br>
                                                                                 <input type="hidden" name="service-input-label-{{ $item->id.'-'.$i }}" value="{{ $key_val[0]}}">
                                                                                 <input class="form-control {{$date_calss.''.$university_board }} service-input-value-{{$item->id.'-'.$i}} {{$input_class}}" {{$readonly}} id="{{ $university_board_id }}" type="hidden" name="service-input-value-{{ $item->id.'-'.$i }}" value="{{ $input_val[0] }}">

                                                                              @elseif ($key_val[0]=='Serious and Organized Crimes Database Checks – Global')
                                                                                 <label class="{{$labelname}}">  {{ $key_val[0]}} </label><br>
                                                                                 <input type="hidden" name="service-input-label-{{ $item->id.'-'.$i }}" value="{{ $key_val[0]}}">
                                                                                 <input class="form-control {{$date_calss.''.$university_board }} service-input-value-{{$item->id.'-'.$i}} {{$input_class}}" {{$readonly}} id="{{ $university_board_id }}" type="hidden" name="service-input-value-{{ $item->id.'-'.$i }}" value="{{ $input_val[0] }}">

                                                                              @elseif ($key_val[0]=='Global Regulatory Bodies')
                                                                                 <label class="{{$labelname}}">  {{ $key_val[0]}} </label><br>
                                                                                 <input type="hidden" name="service-input-label-{{ $item->id.'-'.$i }}" value="{{ $key_val[0]}}">
                                                                                 <input class="form-control {{$date_calss.''.$university_board }} service-input-value-{{$item->id.'-'.$i}} {{$input_class}}" {{$readonly}} id="{{ $university_board_id }}" type="hidden" name="service-input-value-{{ $item->id.'-'.$i }}" value="{{ $input_val[0] }}">

                                                                              @elseif ($key_val[0]=='Compliance Database')
                                                                                 <label class="{{$labelname}}">  {{ $key_val[0]}} </label><br>
                                                                                 <input type="hidden" name="service-input-label-{{ $item->id.'-'.$i }}" value="{{ $key_val[0]}}">
                                                                                 <input class="form-control {{$date_calss.''.$university_board }} service-input-value-{{$item->id.'-'.$i}} {{$input_class}}" {{$readonly}} id="{{ $university_board_id }}" type="hidden" name="service-input-value-{{ $item->id.'-'.$i }}" value="{{ $input_val[0] }}">

                                                                              @elseif ($key_val[0]=='Web and Media Searches – Global')
                                                                                 <label class="{{$labelname}}">  {{ $key_val[0]}} </label><br>
                                                                                 <input type="hidden" name="service-input-label-{{ $item->id.'-'.$i }}" value="{{ $key_val[0]}}">
                                                                                 <input class="form-control {{$date_calss.''.$university_board }} service-input-value-{{$item->id.'-'.$i}} {{$input_class}}" {{$readonly}} id="{{ $university_board_id }}" type="hidden" name="service-input-value-{{ $item->id.'-'.$i }}" value="{{ $input_val[0] }}">   

                                                                              @elseif ($key_val[0]=='Sanction & PEP - Global')
                                                                                 <label class="{{$labelname}}">  {{ $key_val[0]}} </label><br>
                                                                                 <input type="hidden" name="service-input-label-{{ $item->id.'-'.$i }}" value="{{ $key_val[0]}}">
                                                                                 <input class="form-control {{$date_calss.''.$university_board }} service-input-value-{{$item->id.'-'.$i}} {{$input_class}}" {{$readonly}} id="{{ $university_board_id }}" type="hidden" name="service-input-value-{{ $item->id.'-'.$i }}" value="{{ $input_val[0] }}">      

                                                                              @else
                                                                                 <label>  {{ $key_val[0]}} </label><br>
                                                                                 <input type="hidden" name="service-input-label-{{ $item->id.'-'.$i }}" value="{{ $key_val[0]}}">
                                                                                 <input class="form-control {{$date_calss.''.$university_board }} service-input-value-{{$item->id.'-'.$i}} {{$input_class}} check-input-{{$item->id}}" {{$readonly}} id="{{ $university_board_id }}" type="text" name="service-input-value-{{ $item->id.'-'.$i }}" value="{{ $input_val[0] }}" >
                                                                              @endif   
                                                                        @elseif(stripos($item->type_name,'drug_test_5')!==false || stripos($item->type_name,'drug_test_6')!==false || stripos($item->type_name,'drug_test_7')!==false || stripos($item->type_name,'drug_test_8')!==false || stripos($item->type_name,'drug_test_9')!==false || stripos($item->type_name,'drug_test_10')!==false)
                                                                           @if(stripos($key_val[0],'Test Name')!==false)
                                                                              <label>  {{ $key_val[0]}} </label><br>
                                                                              <input type="hidden" name="service-input-label-{{ $item->id.'-'.$i }}" value="{{ $key_val[0]}}">
                                                                              <input class="form-control" type="hidden" name="service-input-value-{{ $item->id.'-'.$i }}" value="{{ $input_val[0] }}">
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
                                                                              <select class="form-control {{$date_calss.' '.$university_board }} service-input-value-{{$item->id.'-'.$i}} {{$input_class}} {{$disabled_link}} check-input-{{$item->id}}" name="service-input-value-{{ $item->id.'-'.$i }}" {{$readonly}}>
                                                                                 <option value="">--Select--</option>
                                                                                 <option @if(stripos($input_val[0],'positive')!==false) selected @endif value="positive">Positive</option>
                                                                                 <option @if(stripos($input_val[0],'negative')!==false) selected  @endif value="negative">Negative</option>
                                                                              </select> 
                                                                           @else
                                                                              <label>  {{ $key_val[0]}} </label>
                                                                              <input type="hidden" name="service-input-label-{{ $item->id.'-'.$i }}" value="{{ $key_val[0]}}">
                                                                              <input class="form-control {{ $university_board }} {{$input_class}} {{$disabled_link}} {{$date_calss}} check-input-{{$item->id}}" {{$readonly}} id="{{ $university_board_id }}" type="text" name="service-input-value-{{ $item->id.'-'.$i }}" value="{{ $input_val[0] }}">  
                                                                           @endif

                                                                        @else
                                                                           <label>  {{ $key_val[0]}} </label>
                                                                           <input type="hidden" name="service-input-label-{{ $item->id.'-'.$i }}" value="{{ $key_val[0]}}">
                                                                           <input class="form-control {{ $university_board }} {{$input_class}} {{$disabled_link}} {{$date_calss}} check-input-{{$item->id}}" {{$readonly}} id="{{ $university_board_id }}" type="text" name="service-input-value-{{ $item->id.'-'.$i }}" value="{{ $input_val[0] }}">  
                                                                        @endif
                                                                        <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-service-input-value-{{$item->id.'-'.$i}}"></p>
                                                                     </div>
                                                               </div>
                                                               <!-- Remarks -->
                                                               
                                                               {{-- <div class="col-sm-8">
                                                                  <div class="form-group">
                                                               
                                                                     <div class="form-check">
                                                                     <label class="check-inline {{$disabled_link}} error-control">
                                                                        <input type="checkbox" name="remarks-input-checkbox-{{ $item->id.'-'.$i}}"  @if(in_array('remarks', $key_val)) @if($input['remarks']=='Yes') checked @endif @endif class="form-check-input" {{$readonly}}>Remarks
                                                                     </label>
                                                                     </div>
                                                                  </div>
                                                               </div> --}}
                                                               <!--  -->
                                                               {{-- <div class="col-sm-5">
                                                                  <div class="form-group">
                                                                  <label> Remarks Message</label>
                                                                     <?php
                                                                        // $remarks_message =""; 
                                                                        // if(array_key_exists('remarks_message', $input_item_data_array[$i]))
                                                                        // {
                                                                        //       $remarks_message =  $input_item_data_array[$i]['remarks_message'];
                                                                        // }
                                                                     ?>
                                                                     <input type="text" name="remarks-input-value-{{ $item->id.'-'.$i}}"  class="form-control" value="{{ $remarks_message }}">
                                                                  </div>
                                                               </div> --}}
                                                               
                                                               <!-- check output -->
                                                               
                                                                  {{-- <div class="col-sm-10">
                                                                     <div class="form-group">
                                                                     <label class="checkbox-inline">
                                                                     <?php
                                                                        $is_executive_summary ="0";
                                                                        $is_executive_summary = Helper::get_is_executive_summary($item->service_id,$key_val[0]);
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
                                                                        <input type="checkbox" name="executive-summary-{{ $item->id .'-'.$i}}" @if ($is_executive_summary)
                                                                              
                                                                           @if($is_executive_summary->is_executive_summary == '1')  checked @endif @endif > Executive Summary Output (if yes: Check Mark)
                                                                     </label>
                                                                     </div>
                                                                     <div class="form-group">
                                                                     <label class="checkbox-inline">
                                                                        <input type="checkbox" name="table-output-{{ $item->id.'-'.$i }}" @if($is_report_output == '1')  checked @endif > Check's Table Output (if yes: Check Mark)
                                                                     </label>
                                                                     </div>
                                                                  </div> --}}
                                                                  {{-- <div class="col-sm-6">
                                                                     
                                                                  </div> --}}
                                                            
                                                               <!-- ./check outputs -->
                                                               <!-- end row -->
                                                            </div>
                                                            <?php $i++; ?>
                                                         @endforeach
                                                         
                                                         @if($report_data->service_id==17)
                                                            <div class="reference_result" id="reference_result-{{$item->id}}">
                                                               @php
                                                                  $reference_type = NULL;

                                                                  if($report_data->reference_type!=NULL)
                                                                  {
                                                                     $reference_type = $report_data->reference_type;
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
                                                                     $reference_service_inputs=Helper::referenceServiceFormInputs($report_data->service_id,$reference_type);
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
                                                                                    if($report_status!=NULL && ($report_status['status']=='completed' || $report_status=='interim') && $item->is_supplementary=='0')
                                                                                    {
                                                                                       $readonly="readonly";
                                                                                    }
                                                                                 ?>
                                                                                 <label>  {{ $key_val[0]}}  </label>
                                                                                 <input type="hidden" name="reference-input-label-{{ $item->id.'-'.$l }}" value="{{ $key_val[0] }}">
                                                                                 <input class="form-control error-control check-input-{{$item->id}}" {{$readonly}} type="text" name="reference-input-value-{{ $item->id.'-'.$l }}" value="{{$input_val[0]}}">
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
                                                                                 <?php
                                                                                    if($report_status!=NULL && ($report_status['status']=='completed' || $report_status=='interim'))
                                                                                    {
                                                                                       $readonly="readonly";
                                                                                    }
                                                                                 ?>
                                                                                 <label> {{ $input->label_name }} </label>
                                                                                 <input type="hidden" name="reference-input-label-{{ $item->id.'-'.$k }}" value="{{ $input->label_name }}">
                                                                                 <input class="form-control error-control check-input-{{$item->id}}" {{$readonly}} type="text" name="reference-input-value-{{ $item->id.'-'.$k }}">
                                                                              </div>
                                                                           </div>
                                                                           <?php $k++; ?>
                                                                        @endforeach
                                                                     </div>
                                                                  @endif
                                                               @endif
                                                            </div>
                                                         @endif
                                                      <?php } ?>
                                                      <!--   -->
                                                      <!-- comment  -->
                                                   
                                                  <div class="row" style="border:1px solid #ddd; padding:10px; margin-bottom:10px;">  
                                                      <div class="row">
                                                         <div class="col-sm-12"> 
                                                            <h4 class="card-title mb-2 mt-2">Approval Inputs  </h4>
                                                         </div>   
                                                         <div class="col-sm-12">
                                                            <div class="form-group">
                                                               <label> Verified By</label>
                                                               <input class="form-control error-control check-input-{{$item->id}}" type="text" name="verified_by-{{ $item->id }}" value="{{ $report_data->verified_by }}" {{$readonly}}>
                                                            </div>
                                                         </div>
                                                      </div>
                                                      <div class="row">
                                                         <div class="col-sm-12">
                                                            <div class="form-group">
                                                               <label> Comments</label>
                                                               <textarea class="form-control error-control check-input-{{$item->id}}" type="text" name="comments-{{ $item->id }}" {{$readonly}}>{{ $report_data->comments }}</textarea>
                                                            </div>
                                                         </div>
                                                         <div class="col-sm-8" style="">
                                                            <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                               <span class="input-group-text" id="basic-addon3">Annexure Value</span>
                                                            </div>
                                                               <input type="text" class="form-control error-control check-input-{{$item->id}}" name="annexure_value-{{$item->id}}"  value="{{ $report_data->annexure_value }}" aria-describedby="basic-addon3" {{$readonly}}>
                                                            </div>
                                                         </div>
                                                      </div>
                                                      <div class="row">
                                                         <div class="col-sm-12">
                                                            <div class="form-group">
                                                               <label>Additional Comments</label>
                                                               <textarea class="form-control error-control check-input-{{$item->id}}" type="text" name="additional-comments-{{ $item->id }}" {{$readonly}}>{{ $report_data->additional_comments }}</textarea>
                                                            </div>
                                                         </div>
                                                         <!--  -->
                                                         <div class="col-sm-12">
                                                            <div class="form-group">
                                                               <label>Approval Status</label><br>
                                                               <select class="form-control approval_status @if($disabled_link=='') app_status @endif error-control {{$disabled_link}} check-input-{{$item->id}}" name="approval-status-{{ $item->id }}" {{$readonly}}>
                                                                     @foreach($status_list as $status)
                                                                     <option data-id="{{ $item->id }}" value="{{ $status->id}}"   @if($status->id == $report_data->approval_status_id) selected @endif > {{ $status->name}} </option>
                                                                     @endforeach
                                                               </select>
                                                            </div>
                                                            <div class="new-tag"> </div>
                                                               <input type="hidden" class="itemID" name="itemID" value="{{ $item->id }}">
                                                            </div>
                                                            {{-- @php $dataVal=array(1,10,11,15,16,17,28);  @endphp
                                                            @if(in_array($item->service_id, $dataVal)) --}}
                                                            <div class="col-sm-12">
                                                            <div class="form-group">
                                                               <label>Verification Mode</label><br>
                                                               <select class="form-control verification_mode check-input-{{$item->id}}" name="verification_mode-{{ $item->id }}" >
                                                                     <option value="">Select Verification Mode</option>
                                                                     <option value="Digital Verification" @if($item->verification_mode=="Digital Verification") selected  @endif>Digital Verification</option>
                                                                     <option value="Virtual Verification"  @if($item->verification_mode=="Virtual Verification") selected  @endif >Virtual Verification</option>
                                                                     <option value="Physical Verification" @if($item->verification_mode=="Physical Verification") selected @endif>Physical Verification</option>
                                                               </select>
                                                               <p style="margin-bottom: 2px;" class="text-danger error-container error-verification_mode-{{$item->id}}" id="error-verification_mode-{{ $item->id }}"></p>

                                                            </div>
                                                            <div class="new-tag"> </div>
                                                               <input type="hidden" class="itemID" name="itemID" value="{{ $item->id }}">
                                                            </div>
                                                            {{-- @endif --}}
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
                                                                  <input type="text" class="form-control error-control" name="district_court_name-{{$item->id}}"  value="{{ $report_data->district_court_name }}" aria-describedby="basic-addon3" {{$readonly}}>
                                                               </div>
                                                            </div>
                                                            <!--  -->
                                                            <div class="col-sm-3">
                                                               <div class="form-group">
                                                                  <input type="text" name="district_court_result-{{$item->id}}" class="form-control error-control" value="{{ $report_data->district_court_result }}" {{$readonly}}>
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
                                                                  <input type="text" class="form-control error-control" name="high_court_name-{{$item->id}}" value="{{ $report_data->high_court_name }}" aria-describedby="basic-addon3" {{$readonly}}>
                                                               </div>
                                                            </div>
                                                            <!--  -->
                                                            <div class="col-sm-3">
                                                               <div class="form-group">
                                                                  <input type="text" name="high_court_result-{{$item->id}}" class="form-control error-control" value="{{ $report_data->high_court_result }}" {{$readonly}}>
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
                                                                  <input type="text" name="supreme_court_result-{{$item->id}}" class="form-control error-control" value="{{ $report_data->supreme_court_result }}" {{$readonly}}>
                                                               </div>
                                                            </div>
                                                            <!--  -->
                                                      </div>
                                                      <!-- ./row -->
                                                   @endif
                                                   <!-- ./ end court  -->
                                                   <!-- insufficiency -->
                                                   @if($item->is_insufficiency==1)
                                                      <div class="row">
                                                         <div class="col-sm-12" style="border:1px solid #ddd; padding:10px; margin-bottom:10px;">
                                                            <p>Insufficiency Status</p>
                                                            <div class="col-sm-12">
                                                                  <div class="form-group">
                                                                  <div class="form-check">
                                                                     <label class="form-check-label">
                                                                        <input style="margin-top: 1px;" type="checkbox" class="form-check-input"  @if($item->is_insufficiency == 1) checked @endif name="insufficiency-{{ $item->id }}" disabled>Mark as insufficiency
                                                                     </label>
                                                                  </div>
                                                                  </div>
                                                            </div>
                                                            <!--  -->
                                                            <div class="col-sm-12">
                                                                  <div class="form-group">
                                                                     <label>insufficiency Notes</label>
                                                                     <input type="text" class="form-control error-control" name="insufficiency-notes-{{$item->id }}" value="{{ $item->insufficiency_notes}}" readonly>
                                                                  </div>
                                                            </div>
                                                            @if($item->insuff_attachment!=NULL)
                                                               <div class="col-sm-12">
                                                                  <div class="form-group">
                                                                     <label>Insufficieny Attachment : </label>
                                                                     <a class="btn btn-link" href="{{url('/').'/uploads/raise-insuff/'.$item->insuff_attachment}}" title="download"><i class="fas fa-download"></i></a>
                                                                  </div>
                                                               </div>
                                                            @endif
                                                            <!-- ./ -->
                                                         </div>
                                                      </div>
                                                   @endif
                                                   
                                                   <!-- Autocheck Insuff -->
                                                   @if($item->verification_type =='Auto')
                                                      <div class="row">
                                                         <!--  -->
                                                         <div class="col-sm-12" style="border:1px solid red; padding:10px; margin-bottom:10px;">
                                                               <div class="form-group">
                                                                  <label>Auto Check API Status: 
                                                                     @if ($item->api_hits_counter == '0')
                                                                        Without api checked
                                                                     @else
                                                                        @if($item->is_api_checked == '0')
                                                                           {{ $item->verification_status }}
                                                                        @elseif( $item->verification_status == 'success')
                                                                           {{ $item->verification_status }}
                                                                           <div class="form-group">
                                                                              <span class="text-success" style="font-size: 18px;">Insuff Cleared <i class="fa fa-check-circle" aria-hidden="true"></i>
                                                                           </span>
                                                                           </div>
                                                                        @else
                                                                           {{ $item->verification_status }}
                                                                        @endif
                                                                     @endif
                                                                  
                                                                  </label>
                                                                  
                                                               </div>
                                                         </div>
                                                         <!-- ./ -->
                                                      </div>
                                                   @else
                                                      <!-- if manual  -->
                                                         @if( $item->verification_status == 'success')
                                                            <div class="form-group">
                                                               <span class="text-success" style="font-size: 18px;">Insuff Cleared <i class="fa fa-check-circle" aria-hidden="true"></i>
                                                            </span>
                                                            </div>
                                                         @else
                                                            {{ $item->verification_status }}
                                                         @endif
                                                      <!-- ./ if manual end -->

                                                   @endif
                                                   <!-- auto check insusff -->
                                                   <!-- clear insuff -->
                                                   {{-- @if( $item->verification_status == null) --}}
                                                   {{-- @if($item->is_insufficiency==1) --}}
                                                   @if(($item->verification_status==NULL || $item->verification_status=='failed') && $item->form_data!=NULL )
                                                      {{-- @if($item->is_insufficiency==0 && $item->verfication_type=='manual') --}}
                                                      <?php $report_status = Helper::get_report_status($item->candidate_id); ?>
                                                         @if ($report_status==NULL || $report_status['status']=='incomplete' || $report_status['status']=='interim'|| $item->is_supplementary=='1')
                                                            <div class="row">
                                                               <div class="col-sm-6">
                                                                  <div class="form-group">
                                                                  <a href="javascript:;" class=" btn btn-warning itemMarkAsCleared error-control" jaf-id="{{ base64_encode($item->id) }}" candidate-id="{{ base64_encode($candidate->id) }}" service-id="{{ base64_encode($item->service_id) }}" service-name="{{$item->service_name}}"> Mark as Insuff cleared </a>
                                                                  </div>
                                                               </div>
                                                               {{-- @endif --}}
                                                               @if($item->is_insufficiency!=1)
                                                               <div class="col-sm-6">
                                                                  <div class="form-group">
                                                                     <a href="javascript:;" class=" btn btn-danger raise_insuff error-control" jaf-id="{{ base64_encode($item->id) }}" candidate-id="{{ base64_encode($candidate->id) }}" service-id="{{ base64_encode($item->service_id) }}" service-name="{{$item->service_name}}" > Raise Insuff</a>
                                                                  </div>
                                                               </div>
                                                               @endif
                                                            </div>
                                                         @endif
                                                   @endif
                                                   {{-- @endif --}}
                                                   <!-- clear insuff -->
                                                   <!-- ./insufficiency -->
                                                   
                                                </div>
                                                <!-- attachment  -->
                                                <div class="col-md-6">
                                                   @php $service_name=Helper::service_attachment_type($item->service_id); @endphp
                                                   <p>Attachments <i class="fa fa-info-circle tooltips" data-toggle="tooltip" data-original-title="Only jpeg,png,jpg,pdf are accepted "></i> </p>
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
                                                   @if($candidate->is_all_insuff_cleared ==0 && ($report_status==NULL || $report_status['status']=='incomplete'|| $item->is_supplementary=='1'))
                                                   <button class='btn btn-sm btn-info clickReorder reorder_link' type="button" add-imageId="{{$item->id}}" data-imageType='main' style=' float:right; '><i class="fas fa-sync"></i> Re-Arrange </button>   
                                                   <a class='btn-link clickSelectFile error-control' id="buttonToSelect-{{$item->id}}" add-id="{{$item->id}}" data-number='1' data-result='fileResult1' data-type='main' style='color: #0056b3; font-size: 16px; display:none' href='javascript:;'><i class='fa fa-plus'></i> Add file  </a>
                                                      <input type='file' class='fileupload' name="file-{{$item->id}}[]" id='file1-{{$item->id}}' accept=".jpg,.jpeg,.png,.pdf" multiple="multiple" style='display:none'/>
                                                   @endif
                                                   <div class="bcd_loading  "  ></div>
                                                   <div class='row fileResult' id="fileResult1-{{$item->id}}" style='min-height: 20px; margin-top: 20px;'>
                                                      <?php $item_files = Helper::getJAFAttachFiles($item->id); //print_r($item_files); ?>
                                                      @foreach($item_files as $file) 
                                                      <?php $attached_file_id=$file['attached_file_id']; 
                                                      $attached_files = Helper::getAttachedFileName($attached_file_id); 
                                                      //dd($attached_files); 
                                                      //print_r($item_files); ?>
                                                         @if($file['attachment_type'] == 'main')
                                                         <div class="image-area">
                                                            @if(stripos($file['file_name'],'pdf')!==false)
                                                               <img src="{{url('/').'/admin/images/icon_pdf.png'}}" alt="Preview" title="{{$file['file_name']}}">
                                                               @if($file['attached_file_name']!=null)
                                                               <span class="filename">{{$file['attached_file_name']}}</span>
                                                               @endif
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
                                                            @if($candidate->is_all_insuff_cleared ==0 && ($report_status==NULL || $report_status['status']=='incomplete' || $item->is_supplementary=='1') )
                                                               <a class="remove-image" data-id="{{ $file['file_id'] }}" href="javascript:;" style="display: inline;">×</a>
                                                            @endif
                                                            <input type="hidden" name="fileID[]" value="{{ $file['file_id'] }}">
                                                         </div>
                                                         @endif
                                                      @endforeach
                                                   </div>
                                                   <p class="mt-2" style="margin-bottom:1px">Add Supportings: <i class="fa fa-info-circle" data-toggle="tooltip" data-original-title="Only jpeg,png,jpg,pdf are accepted "></i></p>
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
                                                   @if($candidate->is_all_insuff_cleared ==0 && ($report_status==NULL || $report_status['status']=='incomplete'|| $item->is_supplementary=='1'))
                                                      <button class='btn btn-sm btn-info clickReorder reorder_link' type="button" add-imageId="{{$item->id}}" data-imageType='supporting' style=' float:right; '><i class="fas fa-sync"></i> Re-Arrange </button>    
                                                      <a class='btn-link clickSelectFile error-control' id="addSupporting-{{$item->id}}" add-id="{{$item->id}}" data-number='2' data-result='fileResult2' data-type='supporting' style='color: #0056b3; font-size: 16px; display:none;' href='javascript:;'><i class='fa fa-plus'></i> Add file</a>
                                                      <input type='file' class='fileupload' name="file-{{$item->id}}[]" id='file2-{{$item->id}}' multiple="multiple" style='display:none'/>
                                                   @endif
                                                   <div class="fileResult2-{{$item->id}} text-center"></div>
                                                   <div class='row fileResult' id="fileResult2-{{$item->id}}" style='min-height: 20px; margin-top: 20px;'>
                                                       <?php $item_files = Helper::getJAFAttachFiles($item->id); //print_r($item_files); ?>
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
                                                               @if($candidate->is_all_insuff_cleared ==0 && ($report_status==NULL || $report_status['status']=='incomplete' || $item->is_supplementary=='1') )
                                                                  <a class="remove-image" data-id="{{ $file['file_id'] }}" href="javascript:;" style="display: inline;">×</a>
                                                               @endif
                                                               <input type="hidden" name="fileID[]" value="{{ $file['file_id'] }}">
                                                           </div>
                                                           @endif
                                                       @endforeach
                                                   </div>
                                                </div>
                                                   <!-- items loop closed -->
                                             </div>
                                             
                                          @endforeach

                                          <div class="row" style="padding: 10px 0; margin-top:10px; border:1px solid #ddd;">
                                             <div class="col-sm-6">
                                               <div class="form-group">
                                                 <label>Digital Signature </label>
                                                <div class="custom-file error-control @if($report_status!=NULL && ($report_status['status']=='completed'  || $report_status['status']=='interim')) disabled-link @endif">
                                                   <input type="file" name="digital_signature" class="custom-file-input digital_signature" id="digital_signature" @if($report_status!=NULL && ($report_status['status']=='completed' || $report_status['status']=='interim')) disabled @endif>
                                                   <label class="custom-file-label" id="digital_label" for="digital_signature">{{$candidate->digital_signature!=NULL || $candidate->digital_signature!='' ? \Str::limit($candidate->digital_signature,30,'...') : 'Choose File...'}}</label>
                                                </div>
                                                 {{-- <input class="form-control" type="file" name="digital_signature" id="digital_signature" placeholder=""> --}}
                                                 {{-- @if ($errors->has('digital_signature'))
                                                   <div class="error pt-2 text-danger">
                                                       {{ $errors->first('digital_signature') }}
                                                   </div>
                                                   @endif --}}
                                                   <p style="margin-bottom: 2px;" class="text-danger error-container error-digital_signature" id="error-digital_signature"></p>
                                               </div>
                                             </div>
                                             @if($candidate->digital_signature!=NULL || $candidate->digital_signature!='')
                                                @php
                                                   $digital_url = '';
                                                   if(stripos($candidate->digital_signature_file_platform,'s3')!==false)
                                                   {
                                                      $filePath = 'uploads/signatures/';
                                                      $s3_config = S3ConfigTrait::s3Config();
                                                      $disk = \Storage::disk('s3');

                                                      $command = $disk->getDriver()->getAdapter()->getClient()->getCommand('GetObject', [
                                                            'Bucket'                     => \Config::get('filesystems.disks.s3.bucket'),
                                                            'Key'                        => $filePath.$candidate->digital_signature,
                                                            'ResponseContentDisposition' => 'attachment;'//for download
                                                      ]);

                                                      $req = $disk->getDriver()->getAdapter()->getClient()->createPresignedRequest($command, '+10 minutes');

                                                      $digital_url = $req->getUri();
                                                   }
                                                   else
                                                   {
                                                      $digital_url = url('uploads/signatures/'.$candidate->digital_signature);
                                                   }
                                                @endphp
                                               <div class="col-sm-6">
                                                 <div class="form-group">
                                                   <label for="company_logo"></label>
                                                   @if($report_status!=NULL && ($report_status['status']=='completed'  || $report_status['status']=='interim')) 
                                                      <span class="btn btn-link float-right text-dark close_btn">X</span>
                                                   @endif
                                                   <img id="preview_ds" src="{{$digital_url}}" width="200" height="150"/>
                                                 </div>
                                               </div>
                                             @else
                                               <div class="col-sm-6">
                                                 <div class="form-group">
                                                   <label for="company_logo"></label>
                                                   @if($report_status!=NULL && ($report_status['status']=='completed'  || $report_status['status']=='interim')) 
                                                   <span class="d-none btn btn-link float-right text-dark close_btn">X</span>
                                                   @endif
                                                   <img id="preview_ds" width="200" height="150"/>
                                                 </div>
                                               </div>
                                             @endif
                                          </div>
                                          {{-- @if ($report_status==NULL || $report_status['status']=='incomplete') --}}
                                             <div class="row mt-3">
                                                <div class="col-12">
                                                   <p class="text-danger">Note :- Please Make Sure About the Data Verified For Each Check Items..</p>
                                                </div>
                                                <div class="col-md-6">
                                                   {{-- <input class="btn btn-success jaf_info_submit" type="submit" value="Update" name="update"> --}}
                                                   <button class="btn btn-success jaf_info_submit" type="submit">Update</button>
                                                </div>  
                                                <div class="col-md-3">
                                                   <input class="btn btn-success add_check" type="button" value="Add New Check" name="add_check"  data-candidate_id="{{ $candidate->id }}">
                                                </div>  
                                             </div> 
                                          {{-- @endif --}}
                                    @else
                                    <div class="col-sm-12"> JAF data is not Completed! </div>
                                    @endif
                                
                                 
                                    
                                       {{-- If Normal User Logged In --}}
                                      <!-- now removed -->
                                          <!--<div class="row" style="padding: 10px 0; margin-top:10px; border:1px solid #ddd;">
                                             <div class="col-sm-6">
                                             <div class="form-group">
                                                <label>Digital Signature </label>
                                                <div class="custom-file error-control @if($report_status['status']=='completed'  || $report_status['status']=='interim') disabled-link @endif">
                                                   <input type="file" name="digital_signature" class="custom-file-input digital_signature" id="digital_signature" @if($report_status['status']=='completed' || $report_status['status']=='interim') disabled @endif>
                                                   <label class="custom-file-label" id="digital_label" for="digital_signature">{{$candidate->digital_signature!=NULL || $candidate->digital_signature!='' ? \Str::limit($candidate->digital_signature,30,'...') : 'Choose File...'}}</label>
                                                </div>
                                                {{-- <input class="form-control" type="file" name="digital_signature" id="digital_signature"> --}}
                                                {{-- @if ($errors->has('digital_signature')) --}}
                                                   <div class="error text-danger">
                                                      {{-- {{ $errors->first('digital_signature') }} --}}
                                                   </div>
                                                   {{-- @endif --}}
                                             </div>
                                             </div>
                                             @if($candidate->digital_signature!=NULL || $candidate->digital_signature!='')
                                                {{-- @php
                                                   $digital_url = '';
                                                   if(stripos($candidate->digital_signature_file_platform,'s3')!==false)
                                                   {
                                                      $filePath = 'uploads/signatures/';

                                                      $s3_config = S3ConfigTrait::s3Config();

                                                      $disk = \Storage::disk('s3');

                                                      $command = $disk->getDriver()->getAdapter()->getClient()->getCommand('GetObject', [
                                                            'Bucket'                     => \Config::get('filesystems.disks.s3.bucket'),
                                                            'Key'                        => $filePath.$candidate->digital_signature,
                                                            'ResponseContentDisposition' => 'attachment;'//for download
                                                      ]);

                                                      $req = $disk->getDriver()->getAdapter()->getClient()->createPresignedRequest($command, '+10 minutes');

                                                      $digital_url = $req->getUri();
                                                   }
                                                   else
                                                   {
                                                      $digital_url = url('uploads/signatures/'.$candidate->digital_signature);
                                                   }
                                                @endphp --}}
                                             <div class="col-sm-6">
                                                <div class="form-group">
                                                   <label for="company_logo"></label>
                                                   <span class="btn btn-link float-right text-dark close_btn">X</span>
                                                   <img id="preview_ds"  src="{{$digital_url}}" width="200" height="150"/>
                                                </div>
                                             </div>
                                             @else
                                             <div class="col-sm-6">
                                                <div class="form-group">
                                                   <label for="company_logo"></label>
                                                   <span class="d-none btn btn-link float-right text-dark close_btn">X</span>
                                                   <img id="preview_ds" width="200" height="150"/>
                                                </div>
                                             </div>
                                             @endif
                                          </div>-->
                                          {{-- @if ($report_status==NULL || $report_status['status']=='incomplete') --}}
                                           
                                          {{-- @endif --}}
                                       @else
                                          <div class="col-sm-12"> JAF data is not Completed! </div>
                                       @endif
                                    {{-- @endif  --}}
                                          <!--  -->  
                                    {{-- @endif  --}}
                                    
                              </form>
                           </div>
                       
                     </div>
                  </div>
                 
               </div>
            </div>
            
         </div>
      </div>
   </div>
</div>

<!--  -->
<div class="modal" id="raise_modal">
   <div class="modal-dialog">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            <h4 class="modal-title" id="ser_name"></h4>
            {{-- <button type="button" class="close closeraisemdl" data-dismiss="modal">&times;</button> --}}
         </div>
         <!-- Modal body -->
         <form method="post" action="{{url('/candidates/jaf/raiseInsuff')}}" enctype="multipart/form-data" id="raise_insuff_form">
         @csrf
           <input type="hidden" name="can_id" id="can_id">
           <input type="hidden" name="ser_id" id="ser_id">
           <input type="hidden" name="jaf_id" id="jaf_id">
            <div class="modal-body">
            <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-all"> </p> 
               <div class="form-group">
                     <label for="label_name"> Comments: <span class="text-danger">*</span></label>
                     <textarea id="comments" name="comments" class="form-control comments" placeholder=""></textarea>
                     {{-- <input type="text" id="comments" name="comments" class="form-control comments" placeholder=""/> --}}
                     <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-comments"></p> 
               </div>
               <div class="form-group">
                  <label for="label_name"> Attachments: <i class="fa fa-info-circle tool" data-toggle="tooltip" data-original-title="Only jpeg,png,jpg,pdf are accepted "></i> </label>
                  <input type="file" name="attachments[]" id="attachments" multiple class="form-control attachments">
                  <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-attachments"></p>  
               </div>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
               <button type="submit" class="btn btn-info raise_submit">Submit </button>
               <button type="button" class="btn btn-danger closeraisemdl closeinsuffraise" data-dismiss="modal">Close</button>
            </div>
         </form>
      </div>
   </div>
</div>
{{-- model to add new checks --}}
<div class="modal" id="new_check_modal">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            <h4 class="modal-title" id="name">Add New Checks</h4>
            {{-- <button type="button" class="close closeraisemdl" data-dismiss="modal">&times;</button> --}}
         </div>
         <!-- Modal body -->
         <form method="post" action="{{ url('/candidates/new_check_save') }}" enctype="multipart/form-data" id="new_check_form">
          @csrf
           <input type="hidden" name="assign_candidate_id" id="assign_candidate_id">
            <div class="modal-body overflow-modal">
               <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-all"> </p> 
               
                  <div class="form-group">
                        <label for="label_name"> Check Name <span class="text-danger">*</span></label>
                        <select class="select-option-field-7 check_name selectValue form-control" name="check_name" >
                           <option value="">-Select Check Name-</option>
                           @foreach ($newcheck_services as $service) 
                                 <option value="{{$service->id}}" >{{$service->name}}</option>
                           @endforeach
                        </select>
                        <p style="margin-bottom: 2px;" class="text-danger error-container error-check_name" id="error-check_name"></p> 
                  </div>
                  
                  <div class="address_assign">

                  </div>
                  <div class="assignModal">

                  </div>
                  <div class="new_ref_data">

                  </div>

                  <div class="assignForm">

                  </div>

                  <div class="row">
                     <div class="col-sm-4">
                         <div class="form-group">
                         <label>TAT <span class="text-danger">*</span></label>
                         <input class="form-control tat" type="text" name="tat" value="1">
                         <p style="margin-bottom: 2px;" class="text-danger error-container error-tat" id="error-tat"></p>
                         </div>
                     </div>
                     <div class="col-sm-4">
                         <div class="form-group">
                         <label>Incentive TAT <span class="text-danger">*</span></label>
                         <input class="form-control incentive_tat" type="text" name="incentive_tat" value="1">
                         <p style="margin-bottom: 2px;" class="text-danger error-container error-incentive_tat" id="error-incentive_tat"></p>
                         </div>
                     </div>
                     <div class="col-sm-4">
                       <div class="form-group">
                           <label>Penalty TAT <span class="text-danger">*</span></label>
                           <input class="form-control penalty_tat" type="text" name="penalty_tat" value="1">
                           <p style="margin-bottom: 2px;" class="text-danger error-container error-penalty_tat" id="error-penalty_tat"></p>
                       </div>
                     </div>
                     <div class="col-sm-4">
                        <div class="form-group">
                            <label>Price <span class="text-danger">*</span></label>
                            <input class="form-control price" type="text" name="price" value="0">
                            <p style="margin-bottom: 2px;" class="text-danger error-container error-price" id="error-price"></p>
                        </div>
                      </div>
                  </div>   
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
               <button type="submit" class="btn btn-info new_check_submit btn-disable">Submit </button>
               <button type="button" class="btn btn-danger  new_check_close btn-disable" data-dismiss="modal">Close</button>
            </div>
         </form>
      </div>
   </div>
</div>

<div class="modal" id="clear_modal">
   <div class="modal-dialog">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            <h4 class="modal-title" id="serv_name"></h4>
            {{-- <button type="button" class="close" data-dismiss="modal">&times;</button> --}}
         </div>
         <!-- Modal body -->
         <form method="post" action="{{url('/candidates/jaf/clearCheckInsuff')}}" id="clear_insuff_form">
         @csrf
           <input type="hidden" name="cand_id" id="cand_id">
           <input type="hidden" name="serv_id" id="serv_id">
           <input type="hidden" name="jaf_f_id" id="jaf_f_id">
            <div class="modal-body">
            <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-all"> </p> 
               <div class="form-group">
                     <label for="label_name"> Comments </label>
                     <textarea id="comment" name="comment" class="form-control comment" placeholder=""></textarea>
                     {{-- <input type="text" id="comments" name="comments" class="form-control comments" placeholder=""/> --}}
                     <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-comment"></p> 
               </div>
               <div class="form-group">
                  <label for="label_name"> Attachments: <i class="fa fa-info-circle tool" data-toggle="tooltip" data-original-title="Only jpeg,png,jpg,pdf are accepted "></i></label>
                  <input type="file" name="attachment[]" id="attachment" multiple class="form-control attachment">
                  <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-attachment"></p>  
               </div>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
               <button type="submit" class="btn btn-info clear-submit">Submit </button>
               <button type="button" class="btn btn-danger closeraisemdl closeinsuffclear" data-dismiss="modal">Close</button>
            </div>
         </form>
      </div>
   </div>
</div>

<div class="modal"  id="preview">
   <div class="modal-dialog modal-lg" style="max-width: 80% !important;">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            <h4 class="modal-title">Report Preview</h4>
            <button type="button" class="close" style="top: 12px;!important; color: red; " data-dismiss="modal">&times;</button>
         </div>
         <!-- Modal body -->
         
            <div class="modal-body">
            <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-all"> </p> 
               <iframe 
                   src="" 
                   style="width:100%; height:620px; " 
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

<div class="modal" id="edit_total_check_price">
   <div class="modal-dialog">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            <h4 class="modal-title">Additional Charge</h4>
            <button type="button" class="close btn-disable" style="top: 12px;!important; color: red;" data-dismiss="modal"><small>×</small></button>
         </div>
         <!-- Modal body -->
         <form method="post" action="{{ url('/candidates/additionalCharges') }}" id="total_check_price_update" enctype="multipart/form-data">
         @csrf
           <input type="hidden" name="id" id="id">
            <div class="modal-body">
               <div class="form-group">
                  <label for="label_name">Check Name: </label>
                  <span class="add_c_service_name" id="add_c_service_name"></span>
               </div>
               <div class="form-group">
                   <label for="label_name">Amount: <small>(in <i class="fas fa-rupee-sign"></i>)</small> <span class="text-danger">*</span></label>
                   <input type="text" id="amount" name="amount" class="form-control amount" placeholder="Enter Amount"/>
                   <p style="margin-bottom: 2px;" class="text-danger error-container error-amount" id="error-amount"></p> 
               </div>
               <div class="form-group">
                   <label for="label_name"> Comments <span class="text-danger">*</span></label>
                   <textarea id="comment" name="comments" class="form-control comments" placeholder=""></textarea>
                   {{-- <input type="text" id="comments" name="comments" class="form-control comments" placeholder=""/> --}}
                   <p style="margin-bottom: 2px;" class="text-danger error-container error-comments" id="error-comments"></p> 
               </div>
               <div class="attach_data">
               </div>
               <div class="form-group">
                   <label for="label_name"> Attachments: <i class="fa fa-info-circle tooltips" data-toggle="tooltip" data-original-title="Only jpeg,png,jpg,pdf are accepted "></i></label>
                   <input type="file" name="attachment[]" id="attachment" multiple class="form-control attachment">
                   <p style="margin-bottom: 2px;" class="text-danger error-container error-attachment" id="error-attachment"></p>  
               </div>
               <div class="form-group pt-2">
                  <div class="form-check">
                     <label class="check-inline">
                        <input type="checkbox" name="additional-check" class="form-check-input additional-check"> Want to Include in Billing
                     </label>
                  </div>
               </div>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
               <button type="submit" class="btn btn-info add_submit btn-disable">Submit </button>
               <button type="button" class="btn btn-danger btn-disable" data-dismiss="modal">Close</button>
            </div>
         </form>
      </div>
   </div>
</div>



<div class="modal show" id="myImageModal">
   <div class="modal-dialog modal-lg">
     <div class="modal-content">
 
       <!-- Modal Header -->
       <div class="modal-header">
         <h4 class="modal-title">File-</h4>
         <button type="button" class="close" data-dismiss="modal">×</button>
       </div>
 
       <!-- Modal body -->
       <div class="modal-body">
       <img class="image-modal-content" id="img01">
      <div id="caption"></div>
       </div>
 
     </div>
   </div>
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

 <div class="modal" id="addr_ver_mdl">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            <h4 class="modal-title" id="serv_name">Address Verification</h4>
            {{-- <button type="button" class="close" data-dismiss="modal">&times;</button> --}}
         </div>
         <!-- Modal body -->
            <div class="modal-body">
               <div class="row">
                  <div class="col-12">
                     <div class="form-group">
                        <label for="label_name"> Candidate Name: </label>
                        <span class="candidate_name"></span>
                     </div>
                  </div>
                  <div class="col-12">
                     <div class="form-group">
                        <label for="label_name"> Check Name: </label>
                        <span class="check_name"></span>
                     </div>
                  </div>
                  <div class="col-12">
                     <div class="form-group">
                        <label for="label_name"> Submitted At: </label>
                        <span class="submit_at"></span>
                     </div>
                  </div>
                  <div class="col-12 pt-2">
                     <h5 class="text-muted">Verification Details:-</h5>
                     <p class="pb-border"></p>
                  </div>
               </div>
               <div class="address_data">
                  <div class="row">
                     <div class="col-6">
                        <div class="form-group">
                           <label for="label_name"> First Name: </label>
                           <span class="first_name"></span>
                        </div>
                     </div>
                     <div class="col-6">
                        <div class="form-group">
                           <label for="label_name"> Last Name: </label>
                           <span class="last_name"></span>
                        </div>
                     </div>
                     <div class="col-6">
                        <div class="form-group">
                           <label for="label_name"> Mobile Number: </label>
                           <span class="phone"></span>
                        </div>
                     </div>
                     <div class="col-6">
                        <div class="form-group">
                           <label for="label_name"> Email: </label>
                           <span class="email"></span>
                        </div>
                     </div>
                     <div class="col-6">
                        <div class="form-group">
                           <label for="label_name">Street Address: </label>
                           <span class="address_1"></span>
                        </div>
                     </div>
                     <div class="col-6">
                        <div class="form-group">
                           <label for="label_name"> Apartment/House/Building: </label>
                           <span class="address_2"></span>
                        </div>
                     </div>
                     <div class="col-6">
                        <div class="form-group">
                           <label for="label_name"> Pincode: </label>
                           <span class="pincode"></span>
                        </div>
                     </div>
                     <div class="col-6">
                        <div class="form-group">
                           <label for="label_name"> Country: </label>
                           <span class="country"></span>
                        </div>
                     </div>
                     <div class="col-6">
                        <div class="form-group">
                           <label for="label_name"> State: </label>
                           <span class="state"></span>
                        </div>
                     </div>
                     <div class="col-6">
                        <div class="form-group">
                           <label for="label_name"> City/Town/District: </label>
                           <span class="city"></span>
                        </div>
                     </div>
                     <div class="col-6">
                        <div class="form-group">
                           <label for="label_name"> Landmark: </label>
                           <span class="landmark"></span>
                        </div>
                     </div>
                     <div class="col-6">
                        <div class="form-group">
                           <label for="label_name"> Address Type: </label>
                           <span class="address_type"></span>
                        </div>
                     </div>
                     <div class="col-6">
                        <div class="form-group">
                           <label for="label_name"> Ownership: </label>
                           <span class="ownership_type"></span>
                        </div>
                     </div>
                     <div class="col-6">
                        <div class="form-group">
                           <label for="label_name"> Period of Stay From: </label>
                           <span class="period_stay_from"></span>
                        </div>
                     </div>
                     <div class="col-6">
                        <div class="form-group">
                           <label for="label_name"> Period of Stay To: </label>
                           <span class="period_stay_to"></span>
                        </div>
                     </div>
                     {{-- <div class="col-6">
                        <div class="form-group">
                           <label for="label_name"> Landmark: </label>
                           <span class="landmark"></span>
                        </div>
                     </div>
                     <div class="col-6">
                        <div class="form-group">
                           <label for="label_name"> Nature of Residence: </label>
                           <span class="nature_of_residence"></span>
                        </div>
                     </div>
                     <div class="col-6">
                        <div class="form-group">
                           <label for="label_name">Verifier Name: </label>
                           <span class="verifier_name"></span>
                        </div>
                     </div>
                     <div class="col-6">
                        <div class="form-group">
                           <label for="label_name">Relation with Verifier: </label>
                           <span class="relation_with_verifier"></span>
                        </div>
                     </div>
                     <div class="col-6">
                        <div class="form-group">
                           <label for="label_name">Period Stay From: </label>
                           <span class="period_stay_from"></span>
                        </div>
                     </div>
                     <div class="col-6">
                        <div class="form-group">
                           <label for="label_name">Period Stay To: </label>
                           <span class="period_stay_from"></span>
                        </div>
                     </div> --}}
                  </div>
                  <div class="add_attach_data">

                  </div>
               </div>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
               <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
      </div>
   </div>
</div>
<!--  -->

<!-- Script -->
<script type="text/javascript">

$(document).ready(function(){

    $(".app_status").select2();

   $(document).on('click','.clickReorder',function(){ 
        imageId     = $(this).attr('add-imageId');
        imageType = $(this).attr('data-imageType');
        $('#jafImageId').val(imageId);
        $('#jafImageType').val(imageType);

        // alert(imageType);
        $.ajax({
              type:'GET',
              url: "{{url('/candidates/jaf/rearrange')}}",
               data: {'imageId':imageId,'imageType':imageType},        
              success: function (response) {        
              console.log(response);

              $('.gallery').html(response);
              $('#myDragModal').modal({
                    backdrop: 'static',
                    keyboard: false
                });
              
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
   $(document).on('click','.closeRearrangeModal',function(){ 
         window.location.reload()
            // $('#myImageModal').css("display", "none");
   });

    function updateOrder() {    
      //  console.log('good going');
       
      imageIds= $('#jafImageId').val(); 
      jafImageTypes=$('#jafImageType').val(); 
        var item_order = new Array();
        $('ul.reorder-photos-list li').each(function() {
          // console.log('good going');
            item_order.push($(this).attr("id"));
        });
        // var order_string =item_order;
        $.ajax({
            type: "GET",
            url: "{{url('/candidates/jaf/rearrange/save')}}",
            data: { "order_number":item_order,'imageIds':imageIds,'jafImageTypes':jafImageTypes },
            cache: false,
            success: function(data){ 
              if (data.fail == false) {
               if ( data.attachment_type=='main') {
                  $("#fileResult1"+"-"+data.jaf_id).html("");
                  var count = Object.keys(data.data).length;
                  // console.log(count);
                  for(var i=0; i < count; i++)
                  {
                  
                        // $("#"+fileResult+"-"+dynamicID).prepend("<div class='image-area' data-id='"+data.data[i]['file_id']+"'><img src='"+data.data[i]['filePrev']+"'  alt='Preview' title='"+data.data[i]['file_name']+"'><a class='remove-image' href='javascript:;' data-id='"+data.data[i]['file_id']+"' style='display: inline;'>&#215;</a><input type='hidden' name='fileID[]' value='"+dynamicID+'-'+data.data[i]['file_id']+"'></div>");
                        if(data.data[i].custome_img_name==null){
                           $("#fileResult1"+"-"+data.jaf_id).append("<div class='image-area' data-id='"+data.data[i]['file_id']+"'><img src='"+data.data[i]['fileIcon']+"'  alt='Preview' title='"+data.data[i]['file_name']+"'><a class='remove-image' href='javascript:;' data-id='"+data.data[i]['file_id']+"' style='display: inline;'>&#215;</a><input type='hidden' name='fileID[]' value='"+data.jaf_id+'-'+data.data[i]['file_id']+"'><span class='filename'>"+data.data[i].image_name+"</span></div>");
                        }else{
                            $("#fileResult1"+"-"+data.jaf_id).append("<div class='image-area' data-id='"+data.data[i]['file_id']+"'><img src='"+data.data[i]['fileIcon']+"'  alt='Preview' title='"+data.data[i]['file_name']+"'><a class='remove-image' href='javascript:;' data-id='"+data.data[i]['file_id']+"' style='display: inline;'>&#215;</a><input type='hidden' name='fileID[]' value='"+data.jaf_id+'-'+data.data[i]['file_id']+"'><span class='filename'>"+data.data[i].custome_img_name+"</span></div>");
                          }
                  }
               }
               else
               {
                  $("#fileResult2"+"-"+data.jaf_id).html("");
                  var count = Object.keys(data.data).length;
                  // console.log(count);
                  for(var i=0; i < count; i++)
                  {
                  
                        // $("#"+fileResult+"-"+dynamicID).prepend("<div class='image-area' data-id='"+data.data[i]['file_id']+"'><img src='"+data.data[i]['filePrev']+"'  alt='Preview' title='"+data.data[i]['file_name']+"'><a class='remove-image' href='javascript:;' data-id='"+data.data[i]['file_id']+"' style='display: inline;'>&#215;</a><input type='hidden' name='fileID[]' value='"+dynamicID+'-'+data.data[i]['file_id']+"'></div>");
                        if(data.data[i].custome_img_name==null){
                           $("#fileResult2"+"-"+data.jaf_id).append("<div class='image-area' data-id='"+data.data[i]['file_id']+"'><img src='"+data.data[i]['fileIcon']+"'  alt='Preview' title='"+data.data[i]['file_name']+"'><a class='remove-image' href='javascript:;' data-id='"+data.data[i]['file_id']+"' style='display: inline;'>&#215;</a><input type='hidden' name='fileID[]' value='"+data.jaf_id+'-'+data.data[i]['file_id']+"'><span class='filename'>"+data.data[i].image_name+"</span></div>");
                        }else{
                           $("#fileResult2"+"-"+data.jaf_id).append("<div class='image-area' data-id='"+data.data[i]['file_id']+"'><img src='"+data.data[i]['fileIcon']+"'  alt='Preview' title='"+data.data[i]['file_name']+"'><a class='remove-image' href='javascript:;' data-id='"+data.data[i]['file_id']+"' style='display: inline;'>&#215;</a><input type='hidden' name='fileID[]' value='"+data.jaf_id+'-'+data.data[i]['file_id']+"'><span class='filename'>"+data.data[i].custome_img_name+"</span></div>");

                        }
                  }
               }
              }
              
            }
        });
    }

         $('.tool').tooltip();

         $(".Datepicker").datepicker({
               changeMonth: true,
               changeYear: true,
               firstDay: 1,
               autoclose:true,
               todayHighlight: true,
               format: 'dd-mm-yyyy',
               container : "#new_check_modal .modal-body"
          });
          $(document).on('click','.image-area > img',function(){ 
            
            var img_src =  $(this).attr("src");
            
             $('.image-modal-content').attr('src',img_src);
             $('#myImageModal').modal();
            
          });
          $(document).on('click','.closeImage',function(){ 
            $('#myImageModal').modal('hide');
            // $('#myImageModal').css("display", "none");
          });
         //Add Additional charges
         $('.addChargesBtn').click(function(){
            var id=$(this).attr('data-id');
            $('.form-control').removeClass('is-invalid');
            $('.error-container').html('');
            $('.btn-disable').attr('disabled',false);
            $('#edit_total_check_price').modal({
                backdrop: 'static',
                keyboard: false
            });
            $.ajax({
                type: 'GET',
                url: "{{ url('/candidates/additionalCharges')}}",
                data: {'id':id},        
                success: function (data) {
                    console.log(data);
                    $("#total_check_price_update")[0].reset();
                    if(data !='null')
                    { 
                        // alert(data.result.additional_charge_notes);
                        //check if primary data 
                        $('#id').val(id);
                        // $('.base_check_price').html('<i class="fas fa-rupee-sign"></i> '+data.result.total_check_price);
                        // $('.total_check_price').html('<i class="fas fa-rupee-sign"></i> '+data.result.final_total_check_price);
                        $('.amount').val(data.result.additional_charges);
                        $('.comments').text(data.result.additional_charge_notes);
                        $('.attach_data').html(data.form);

                        var type = data.result.service_type;
                        if(type.toLowerCase()=='Manual'.toLowerCase())
                           $('.add_c_service_name').html(data.result.service_name+' - '+data.result.check_item_number);
                        else
                           $('.add_c_service_name').html(data.result.service_name);

                        if(data.result.is_charge_allowed==1)
                        {
                           $('.additional-check').attr('checked',true);
                        }
                        else
                        {
                           $('.additional-check').attr('checked',false);
                        }
                    }
                },
                error: function (xhr, textStatus, errorThrown) {
                    // alert("Error: " + errorThrown);
                }
            });
        });
        $(document).on('submit', 'form#total_check_price_update', function (event) {
        
            $("#overlay").fadeIn(300);　
            event.preventDefault();
            var form = $(this);
            var data = new FormData($(this)[0]);
            var url = form.attr("action");
            var $btn = $(this);
            var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
            $('.error-container').html('');
            $('.form-control').removeClass('is-invalid');
            $('.btn-disable').attr('disabled',true);
            if ($('.add_submit').html() !== loadingText) {
                  $('.add_submit').html(loadingText);
            }
            $.ajax({
                  type: form.attr('method'),
                  url: url,
                  data: data,
                  cache: false,
                  contentType: false,
                  processData: false,
                  success: function (data) {
                     window.setTimeout(function(){
                        $('.btn-disable').attr('disabled',false);
                        $('.add_submit').html('Submit');
                     },2000);
                     if (data.fail && data.error_type == 'validation') {
                              //$("#overlay").fadeOut(300);
                              for (control in data.errors) {
                                 $('input[name='+control+']').addClass('is-invalid');
                                 $('textarea[name='+control+']').addClass('is-invalid');
                                 $('.error-' + control).html(data.errors[control]);
                              }
                     } 
                     if (data.fail && data.error == 'yes') {
                        
                        $('#error-all').html(data.message);
                     }
                     if (data.fail == false) {
                        toastr.success("Record Updated Successfully");
                        window.setTimeout(function(){
                              location.reload();
                        },2000);
                        
                     }
                  },
                  error: function (data) {
                     
                     console.log(data);

                  }
            });
            event.stopImmediatePropagation();
            return false;

        });
         // add New check 
      $(document).on('click', '.add_check', function (event) {
           
            // alert(jaf_id);
            $('#assign_candidate_id').val("");
          
            var assign_candidate_id =$(this).attr('data-candidate_id');
           
            $('#assign_candidate_id').val(assign_candidate_id);
           
               // $('#new_check_form')[0].reset();

               $('.form-control').removeClass('border-danger');
               $('.error-container').html('');
               
               $('#new_check_modal').modal({
                  backdrop: 'static',
                  keyboard: false
               });
           
         
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
                     confirmButtonColor: "#DD6B55",
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

                              $.ajax({
                                    type:'POST',
                                    url: "{{ url('/')}}"+"/candidates/jaf/data-verified",
                                    data: {"_token": "{{ csrf_token() }}",'id':check_id},        
                                    success: function (response) {  
                                       if(response.success==true)      
                                          current_data.prop('checked',true);
                                       else
                                       {
                                          toastr.error("Before Verifying the Data, Please Clear the Insufficiency First !!");
                                          current_data.prop('checked',false);
                                       }
                                    },
                                    error: function (xhr, textStatus, errorThrown) {
                                          // alert("Error: " + errorThrown);
                                    }
                                 });
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
                              
                              $('#jaf-ready-report').prop('checked',false);
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

      $(document).on('click','.digital_verification',function (event) {
         var current_data = $(this);
         var check_id = $(this).attr('data-digital_id');
         var status = $(this).prop('checked');
         var r =swal({
                     // icon: "warning",
                     type: "warning",
                     title: "Are you sure?",
                     text: "How it will be work?\n 1. Address Verification Link will send on candidates email.\n 2. In case candidate does not have email then Text SMS will send with APP link.\n 3. Candidate will login and submit the require data.!",
                     
                     // buttons: ["Cancel", "Yes"],
                     dangerMode: true,
                     showCancelButton: true,
                     confirmButtonColor: "#DD6B55",
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
                    
         });
                     // alert('mil gyi id ?');
          
      });

      // Add Html assign user and Check requered fields on change
      $(document).on('change','.check_name',function(event){

         var current = $(this);
         var service_id = $(this).val();

         var tat = 1;

         var check_candidate_id =$('#assign_candidate_id').val();
         $('.form-control').removeClass('border-danger');
         $('.error-container').html('');
         // alert(service_id);
            if (service_id == '1') {
               $(".address_assign").append("<div class='row'><div class='col-sm-12'><div class='form-group'><label>Address Type <span class='text-danger'>*</span></label> <select class='form-control address-type-"+service_id+"' name='address-type-"+service_id+"' ><option value=''>- Select Type -</option><option value='current'>Current</option><option value='permanent'>Permanent</option></select> <p style='margin-bottom: 2px;' class='text-danger error-container' id='error-address-type-"+service_id+"'></p></div></div></div>");
            }else{
               $(".address_assign").html('');
            }
            $(".assignModal").html('');
            $('.new_ref_data').html('');
            
         $.ajax({
               type: 'GET',
               url:"{{ url('/candidates/new_service/assign_modal') }}",
               data: {'service_id':service_id,'candidate_id':check_candidate_id},
                   
               success: function (data) {
                     // console.log(data.success);
                  $('.error-container').html('');
                  if (data.fail && data.error == '') {
                     //    console.log(data.success);
                     $('.error').html(data.message);
                  }
                  
                  
                  if (data.fail == false ) {
                        
                     $(".assignModal").html(data.data);

                     $(".assignForm").html(data.assign_form);

                     if(data.service.name.toLowerCase()=='Address'.toLowerCase())
                     {
                        tat=7;
                     }
                     else if(data.service.name.toLowerCase()=='Employment'.toLowerCase())
                     {
                        tat=5;
                     }
                     else if(data.service.name.toLowerCase()=='Educational'.toLowerCase())
                     {
                        tat=7;
                     }
                     else if(data.service.name.toLowerCase()=='Criminal'.toLowerCase())
                     {
                        tat=3;
                     }
                     else if(data.service.name.toLowerCase()=='Judicial'.toLowerCase())
                     {
                        tat=2;
                     }
                     else if(data.service.name.toLowerCase()=='Reference'.toLowerCase())
                     {
                        tat=2;
                     }
                     else if(data.service.name.toLowerCase()=='Covid-19 Certificate'.toLowerCase())
                     {
                        tat=5;
                     }

                     $('.tat').val(tat);
                     $('.incentive_tat').val(1);
                     $('.penalty_tat').val(tat);
                       
                  }
               } 
            
         });
      });
      //Submit new check modal
      $(document).on('submit', 'form#new_check_form', function (event) {
               $("#overlay").fadeIn(300);　
               event.preventDefault();
               var form = $(this);
               var data = new FormData($(this)[0]);
               var url = form.attr("action");
               var $btn = $(this);
               var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
               $('.error-container').html('');
               $('.form-control').removeClass('border-danger');
               $('.new_check_submit').attr('disabled',true);
               $('.btn-disable').attr('disabled',true);
               if ($('.new_check_submit').html() !== loadingText) {
                     $('.new_check_submit').html(loadingText);
               }
               $.ajax({
                  type: form.attr('method'),
                  url: url,
                  data: data,
                  cache: false,
                  contentType: false,
                  processData: false,
                  success: function (data) {
                        console.log(data);
                        $('.error-container').html('');
                        window.setTimeout(function(){
                           $('.new_check_submit').html('Submit');
                           $('.btn-disable').attr('disabled',false);
                        },2000);
                        if (data.fail && data.error_type == 'validation') {
                              //$("#overlay").fadeOut(300);
                              for (control in data.errors) {
                              // $('textarea[comment=' + control + ']').addClass('is-invalid');
                              $('.'+control).addClass('border-danger');
                              $('.error-'+control).html(data.errors[control]);
                              }
                        } 
                     //  if (data.fail && data.error == 'yes') {
                           
                     //      $('#error-all').html(data.message);
                     //  }
                        if(data.fail && data.status=='no')
                        {
                           // toastr.error("Insufficiency Failed");
                           // redirect to google after 5 seconds
                           window.setTimeout(function() {
                           location.reload(); 
                           }, 2000);
                        }
                        if (data.fail == false) {
                           // $('#send_otp').modal('hide');
                           // alert(data.id);
                           // if(data.success){
                              // toastr.success("Mail is Sent Successfully");
                              toastr.success("New Check added  successfully");
                              // redirect to google after 5 seconds
                              window.setTimeout(function() {
                              location.reload(); 
                              }, 2000);
                              // window.location.href='{{ Config::get('app.admin_url')}}/aadharchecks/show';
                              //  location.reload();
                           // }
                           // else
                           // {
                           //    toastr.error("Something Went Wrong!!");
                           // } 
                        }
                        if(data.fail && data.status=='error'){
                           toastr.error("Something Went Wrong!!");
                        }
                  },
                  error: function (data) {
                        
                     console.log(data);

                  }
                  // error: function (xhr, textStatus, errorThrown) {
                        
                  //       alert("Error: " + errorThrown);

                  // }
               });
               return false;
      });
         // Preview box
         $('.reportsPreviewBox').click(function(){
               //   alert('ass');
                  var report_id = $(this).attr('data-id');

                  document.getElementById('preview_pdf').src="{{ url('/') }}"+"/candidate/report/preview/"+report_id;
               
                  // alert(business_id);
                  $('#preview').toggle();
         });

         $('.close').click(function(){
            $('#preview').hide();
         });
         $('.back').click(function(){
            $('#preview').hide();
         });

          var curNum ='';
          var fileResult='fileResult1';
          var type = 'main';
          var number = '1';
          $(document).on('click','.clickSelectFile',function(){ 
            curNum     = $(this).attr('add-id');
            fileResult = $(this).attr('data-result');
            type = $(this).attr('data-type');
            number = $(this).attr('data-number');
            //  alert(fileResult);
            $(this).next('input[type="file"]').trigger('click');
          });
          //
          $(document).on('change','.fileupload',function(e){        
            uploadFile(curNum,fileResult,type,number);
          });
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

         $(document).on('change', '.check_ignore', function (event) {
            var _this = $(this);
            var id    = $(this).attr('data-id');

            if (this.checked) {
              $('.check-input-'+id).addClass('disabled-link-2');  
            }
            else
            {
              $('.check-input-'+id).removeClass('disabled-link-2');
            }

         });

         // $(document).on('change', '.service_select', function (event) {

         //    var service_val=$('.service_select').val();
         //    var service_name =$(this).find('option:selected').attr("data-name");
         //    // var service_name =$(this).attr('data-name');
         //    if(service_name.trim().toLowerCase()=="Other".toLowerCase()){
         //       $(".attachment_name").css("display","block");
         //    }else{
         //       $(".attachment_name").css("display","none");
         //    }
         //    if(service_val){
         //       $('#buttonToSelect').css('display','block');
         //    }
         // });
         //  //remove file
         //  $(document).on('change', '.service_add', function (event) {

         //    var service_val=$('.service_add').val();
         //    var service_name =$(this).find('option:selected').attr("data-name");
         //    // var service_name =$(this).attr('data-name');
         //    if(service_name.trim().toLowerCase()=="Other".toLowerCase()){
         //    $(".attached_file").css("display","block");
         //    }else{
         //    $(".attached_file").css("display","none");
         //    }
         //    if(service_val){
         //    $('#addSupporting').css('display','block')
         //    }
         // });
         $(document).on('click','.remove-image',function(){ 

            // var r = confirm("Are you want to remove?");
            // if (r == true) {
            // $('#fileupload-'+curNum).val("");
            // var current = $(this);
            // var file_id = $(this).attr('data-id');
            // //
            // var fd = new FormData();

            // fd.append('file_id',file_id);
            // fd.append('_token', '{{csrf_token()}}');
            // //
            // $.ajax({
            //       type: 'POST',
            //       url: "{{ url('/jaf/remove/file') }}",
            //       data: fd,
            //       processData: false,
            //       contentType: false,
            //       success: function(data) {
            //          console.log(data);
            //          if (data.fail == false) {
            //          //reset data
            //          $('.fileupload').val("");
            //          //append result
            //          $(current).parent('.image-area').detach();
            //          } else {
                     
            //          console.log("file error!");
                     
            //          }
            //       },
            //       error: function(error) {
            //          console.log(error);
            //          // $(".preview_image").attr("src","{{asset('images/file-preview.png')}}"); 
            //       }
            // });

            // return false;

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
                  confirmButtonColor: "#DD6B55",
                  confirmButtonText: "YES",
                  cancelButtonText: "CANCEL",
                  closeOnConfirm: false,
                  closeOnCancel: false
                  },
                  function(e){
                     if(e==true)
                     {
                        var fd = new FormData();

                        fd.append('file_id',file_id);
                        fd.append('_token', '{{csrf_token()}}');

                        $.ajax({
                              type: 'POST',
                              url: "{{ url('/jaf/remove/file') }}",
                              data: fd,
                              processData: false,
                              contentType: false,
                              success: function(data) {
                                 // console.log(data);
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
   

         $(document).on('click', '.raise_insuff', function (event) {
            $('#can_id').val("");
            $('#ser_name').text('Verification - '+"");
            $('#ser_id').val("");
            $('#jaf_id').val("");
            var can_id=$(this).attr('candidate-id');
            var ser_id=$(this).attr('service-id');
            var jaf_id=$(this).attr('jaf-id');
            var ser_name=$(this).attr('service-name');
            $('#can_id').val(can_id);
            $('#ser_name').text('Verification - '+ser_name);
            $('#ser_id').val(ser_id);
            $('#jaf_id').val(jaf_id);

            // alert(jaf_id);

            $.ajax(
            {
               url: "{{ url('/') }}"+'/candidates/setData/?jaf_id='+jaf_id+'&candidate_id='+can_id+'&service_id='+ser_id,
               type: "get",
               datatype: "html",
            })
            .done(function(data)
            {
               console.log(data);
               $('#raise_modal').modal({
                  backdrop: 'static',
                  keyboard: false
               });
               
            })
            .fail(function(jqXHR, ajaxOptions, thrownError)
            {
               //alert('No response from server');
            });
            
         });

         $(document).on('click','.closeraisemdl',function(event){
            $("#comments").val("");
            $("#comment").val("");
            $("#attachments").val("");
            $("#attachment").val("");
            $('.error-container').html('');
            $('.form-control').removeClass('border-danger');

            // $.ajax(
            // {
            //    url: "{{ url('/') }}"+'/candidates/sessionForget',
            //    type: "get",
            //    datatype: "html",
            // })
            // .done(function(data)
            // {
            //    console.log(data);
            // })
            // .fail(function(jqXHR, ajaxOptions, thrownError)
            // {
            //    //alert('No response from server');
            // });

         });

         $(document).on('submit', 'form#raise_insuff_form', function (event) {
                        
               $("#overlay").fadeIn(300);　
               event.preventDefault();
               var form = $(this);
               var data = new FormData($(this)[0]);
               var url = form.attr("action");
               var btn = $(this);
               var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
               $('.error-container').html('');
               $('.form-control').removeClass('border-danger');
               $('.raise_submit').attr('disabled',true);
               $('.closeinsuffraise').attr('disabled',true);
               if ($('.raise_submit').html() !== loadingText) {
                     $('.raise_submit').html(loadingText);
               }
               $.ajax({
                     type: form.attr('method'),
                     url: url,
                     data: data,
                     cache: false,
                     contentType: false,
                     processData: false,
                     success: function (data) {
                        console.log(data);
                        window.setTimeout(function(){
                           $('.raise_submit').attr('disabled',false);
                           $('.closeinsuffraise').attr('disabled',false);
                           $('.raise_submit').html('Submit');
                        },2000);
                        $('.error-container').html('');
                        if (data.fail && data.error_type == 'validation') {
                                 //$("#overlay").fadeOut(300);
                                 for (control in data.errors) {
                                 // $('textarea[comments=' + control + ']').addClass('is-invalid');
                                 $('.'+control).addClass('border-danger');
                                 $('#error-' + control).html(data.errors[control]);
                                 }
                        } 
                        //  if (data.fail && data.error == 'yes') {
                           
                        //      $('#error-all').html(data.message);
                        //  }
                        if (data.fail == false) {
                           // $('#send_otp').modal('hide');
                           // alert(data.id);
                           if(data.success){
                           toastr.success("Mail is Sent Successfully");
                           toastr.error("Insuff is Raised");
                              // redirect to google after 5 seconds
                              window.setTimeout(function() {
                              location.reload(); 
                              }, 2000);
                           // window.location.href='{{ Config::get('app.admin_url')}}/aadharchecks/show';
                           //  location.reload();
                           }
                           else
                           {
                              toastr.error("Something Went Wrong!!");
                           } 
                        }
                     },
                     error: function (xhr, textStatus, errorThrown) {
                        
                        alert("Error: " + errorThrown);

                     }
               });
               return false;
            
         });


         $(document).on('click', '.itemMarkAsCleared', function (event) {
         
            var candidate_id = $(this).attr('candidate-id');
            var jaf_id       = $(this).attr('jaf-id');
            var service_id   = $(this).attr('service-id');
            var servi_name  = $(this).attr('service-name');

            // alert(servi_name);

            $('#serv_name').text('Verification - '+servi_name);
            $('#serv_id').val(service_id);
            $('#jaf_f_id').val(jaf_id);
            $('#cand_id').val(candidate_id);
            $('#clear_modal').modal({
               backdrop: 'static',
               keyboard: false
            });
            // if(confirm("Are you sure want clear insuff staus?")){
            // $.ajax({
            //    type:'GET',
            //    url: "{{route('/candidates/jaf/clearCheckInsuff')}}",
            //    data: { 'candidate_id':candidate_id,'jaf_item_id':jaf_id,'service_id':service_id},        
            //    success: function (response) {        
            //    console.log(response);
               
            //       if (response.status=='ok') {            
                     
            //          toastr.success("Insuff is Cleared successfully");
            //             // redirect to google after 5 seconds
            //             window.setTimeout(function() {
            //             location.reload(); 
            //             }, 2000);
                  
            //       } else {

            //          toastr.success("Check Insuff Status");
            //             // redirect to google after 5 seconds
            //             window.setTimeout(function() {
            //             location.reload(); 
            //             }, 2000);
            //       }
            //    },
            //    error: function (xhr, textStatus, errorThrown) {
            //       alert("Error: " + errorThrown);
            //    }
            // });

            // }
            // return false;
         });

         $(document).on('submit', 'form#clear_insuff_form', function (event) {
               $("#overlay").fadeIn(300);　
               event.preventDefault();
               var form = $(this);
               var data = new FormData($(this)[0]);
               var url = form.attr("action");
               var $btn = $(this);
               var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
               $('.error-container').html('');
               $('.form-control').removeClass('border-danger');
               $('.clear-submit').attr('disabled',true);
               $('.closeinsuffclear').attr('disabled',true);
               if ($('.clear-submit').html() !== loadingText) {
                     $('.clear-submit').html(loadingText);
               }
               $.ajax({
                  type: form.attr('method'),
                  url: url,
                  data: data,
                  cache: false,
                  contentType: false,
                  processData: false,
                  success: function (data) {
                        console.log(data);
                        $('.error-container').html('');
                        window.setTimeout(function(){
                           $('.clear-submit').attr('disabled',false);
                           $('.closeinsuffclear').attr('disabled',false);
                           $('.clear-submit').html('Submit');
                        },2000);
                        if (data.fail && data.error_type == 'validation') {
                              //$("#overlay").fadeOut(300);
                              for (control in data.errors) {
                              // $('textarea[comment=' + control + ']').addClass('is-invalid');
                              $('.'+control).addClass('border-danger');
                              $('#error-' + control).html(data.errors[control]);
                              }
                        } 
                     //  if (data.fail && data.error == 'yes') {
                           
                     //      $('#error-all').html(data.message);
                     //  }
                        if(data.fail && data.status=='no')
                        {
                           toastr.error("Insufficiency Failed");
                           // redirect to google after 5 seconds
                           window.setTimeout(function() {
                           location.reload(); 
                           }, 2000);
                        }
                        if (data.fail == false) {
                           // $('#send_otp').modal('hide');
                           // alert(data.id);
                           // if(data.success){
                              // toastr.success("Mail is Sent Successfully");
                              toastr.success("Insuff is Cleared successfully");
                              // redirect to google after 5 seconds
                              window.setTimeout(function() {
                              location.reload(); 
                              }, 2000);
                              // window.location.href='{{ Config::get('app.admin_url')}}/aadharchecks/show';
                              //  location.reload();
                           // }
                           // else
                           // {
                           //    toastr.error("Something Went Wrong!!");
                           // } 
                        }
                        if(data.fail && data.status=='error'){
                           toastr.error("Something Went Wrong!!");
                        }
                  },
                  error: function (data) {
                        
                     console.log(data);

                  }
                  // error: function (xhr, textStatus, errorThrown) {
                        
                  //       alert("Error: " + errorThrown);

                  // }
               });
               return false;
         });

         // clear all 
         $(document).on('click', '.all_insuff_clear_btn', function (event) {
         
            var candidate_id = $(this).attr('data-id');
               // if(confirm("Are you sure want clear insuff status?")){
               //    $.ajax({
               //       type:'GET',
               //       url: "{{route('/candidates/jaf/clearAllChecksInsuff')}}",
               //       data: {'candidate_id':candidate_id},        
               //       success: function (response) {        
               //       console.log(response);
                     
               //             if (response.status=='ok') {      
               //                toastr.success("Report is Generated");
               //                if(response.check=='yes')      
               //                   toastr.success("All the insufficiencies have already been cleared");
               //                else
               //                   toastr.success("All Insuff is Cleared successfully");
               //                // redirect to google after 5 seconds
               //                window.setTimeout(function() {
               //                   window.location="{{url('/')}}"+"/candidates";
               //                }, 2000);

               //             }
               //             else if(response.status=='no')
               //             {
               //                toastr.error("Report is not generated due to insufficiencies..");
               //             } 
               //             else if(response.status=='error'){
               //                toastr.error("Something Went Wrong!!");
               //             }
               //       },
               //       error: function (xhr, textStatus, errorThrown) {
               //             alert("Error: " + errorThrown);
               //       }
               //    });
               //    return false;
               // }

               swal({
                  // icon: "warning",
                  type: "warning",
                  title: "Are You Sure Want to Clear Insuff Status?",
                  text: "",
                  dangerMode: true,
                  showCancelButton: true,
                  confirmButtonColor: "#DD6B55",
                  confirmButtonText: "YES",
                  cancelButtonText: "CANCEL",
                  closeOnConfirm: false,
                  closeOnCancel: false
                  },
                  function(e){
                     if(e==true)
                     {
                        $.ajax({
                           type:'GET',
                           url: "{{route('/candidates/jaf/clearAllChecksInsuff')}}",
                           data: {'candidate_id':candidate_id},        
                           success: function (response) {        
                           // console.log(response);
                           
                                 if (response.status=='ok') {      
                                    toastr.success("Report is Generated");
                                    if(response.check=='yes')      
                                       toastr.success("All the Insufficiencies has Already Been Cleared");
                                    else
                                       toastr.success("All Insuff is Cleared successfully");
                                    // redirect to google after 5 seconds
                                    window.setTimeout(function() {
                                       window.location="{{url('/')}}"+"/candidates";
                                    }, 2000);

                                 }
                                 else if(response.status=='no')
                                 {
                                    toastr.error("Report is Not Generated Due to Insufficiencies..");
                                 } 
                                 else if(response.status=='error'){
                                    toastr.error("Something Went Wrong!!");
                                 }
                           },
                           error: function (xhr, textStatus, errorThrown) {
                                 // alert("Error: " + errorThrown);
                                 console.log(errorThrown);
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
            var jaf_id = _this.attr('data-jaf');
            var type = _this.val();
            if(type!='')
            {
               $.ajax({
                     type:'POST',
                     url: "{{route('/jaf/reference_form')}}",
                     data: {"_token": "{{ csrf_token() }}","id":id,"type":type},        
                     success: function (response) {        
                     // console.log(response);

                     $('#reference_result-'+jaf_id).html(response);
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

               $('#reference_result-'+jaf_id).html('');

               // _this.attr('selectedIndex', '-1');
            }
         });

         $(document).on('submit','form#jaf_form',function (event) {
               event.preventDefault();
               //clearing the error msg
               $('p.error-container').html("");

               var form = $(this);
               var data = new FormData($(this)[0]);
               var url = form.attr("action");
               var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
               $('.jaf_info_submit').attr('disabled',true);
               // $('.form-control').attr('readonly',true);
               // $('.form-control').addClass('disabled-link');
               $('.error-control').attr('readonly',true);
               $('.error-control').addClass('disabled-link');
               if ($('.jaf_info_submit').html() !== loadingText) {
                     $('.jaf_info_submit').html(loadingText);
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
                           $('.jaf_info_submit').attr('disabled',false);
                           // $('.form-control').attr('readonly',false);
                           // $('.form-control').removeClass('disabled-link');
                           $('.error-control').attr('readonly',false);
                           $('.error-control').removeClass('disabled-link');
                           $('.jaf_info_submit').html('Update');
                        },2000);
                     console.log(response);
                     if(response.success==true) {          
                           // var case_id = response.case_id;
                           //notify
                           toastr.success("Candidate BGV QC Updated Successfully");
                           // redirect to after 3 seconds
                           window.setTimeout(function() {
                              // window.location = "{{ url('/')}}"+"/candidates/jaf-info/"+case_id;
                              window.location.reload();
                           }, 3000);
                     
                     }
                     //show the form validates error
                     if(response.success==false ) {                              
                           for (control in response.errors) {   
                              $('#error-'+control).html(response.errors[control]);
                           }
                     }
                  },
                  error: function (xhr, textStatus, errorThrown) {
                     // alert("Error: " + errorThrown);
                  }
               });
               return false;
         });

         $(document).on('change','.new_ref_type',function(event){
            var _this=$(this);
            var id = _this.attr('data-id');
            var type = _this.val();
            
            if(type!='')
            {
               // alert(type);
               $.ajax({
                     type:'POST',
                     url: "{{route('/candidates/new_service/reference_form')}}",
                     data: {"_token": "{{ csrf_token() }}","id":id,"type":type},        
                     success: function (response) {        
                     // console.log(response);

                     $('.new_ref_data').html(response);
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
               $('.new_ref_data').html('');
            }


         });

         $(document).on('click','.jaf-ready-report',function(event){
            var current_data = $(this);
            var status = $(this).prop('checked');
            var ver_check_length = $('.verified_data:checked').length;
         
            var r =swal({
                        title: "Are you sure?",
                        text: "While confirming BGV QC, please review once about BGV data & attachments submitted!",
                        type: "warning",
                        dangerMode: true,
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "YES",
                        cancelButtonText: "CANCEL",
                        closeOnConfirm: false,
                        closeOnCancel: false
                        },
                        function(e){
                           //Use the "Strict Equality Comparison" to accept the user's input "false" as string)
                           // if check the checkox
                         
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
                              
                     
                     }
                     );
         });

          //Add Additional charges
         $('.address-verification-data').click(function(){
            var _this = $(this);
            var id = _this.attr('data-id');
            $('#addr_ver_mdl').modal({
                backdrop: 'static',
                keyboard: false
            });
            $.ajax({
                type: 'POST',
                url: "{{ url('/candidates/address_verification_data')}}",
                data: {'id':id,'_token': '{{csrf_token()}}'},        
                success: function (data) {
                    if(data !='null' &&  data.result!=null)
                    { 
                        var candidate_name = data.user!=null ? data.user.name+' ('+data.user.display_id+')' : 'N/A';
                        var check_name = data.result.service_name+' - '+data.result.check_item_number;
                        var submit_at = data.submitted_at;
                        var first_name = data.result.first_name!=null ? data.result.first_name : 'N/A';
                        var last_name = data.result.last_name!=null ? data.result.last_name : 'N/A';
                        var email =  data.result.email!=null ? data.result.email : 'N/A';
                        var phone = data.result.phone!=null ? data.result.phone : 'N/A';
                        var address_1 = data.result.address_line1!=null ? data.result.address_line1 : 'N/A';
                        var address_2 = data.result.address_line2!=null ? data.result.address_line2 : 'N/A';
                        var pincode = data.result.zipcode!=null ? data.result.zipcode : 'N/A';
                        var country = data.result.country_name!=null ? data.result.country_name : 'N/A';
                        var state = data.result.state_name!=null ? data.result.state_name : 'N/A';
                        var city = data.result.city_name!=null ? data.result.city_name : 'N/A';
                        var address_type = data.result.address_type!=null ? data.result.address_type : 'N/A';
                        var ownership_type = data.result.ownership_type!=null ? data.result.ownership_type : 'N/A';
                        var landmark = data.result.landmark!=null ? data.result.landmark : 'N/A';
                        // var nature_of_residence = data.result.nature_of_residence!=null ? data.result.nature_of_residence : 'N/A';
                        // var verifier_name = data.result.verifier_name!=null ? data.result.verifier_name : 'N/A';
                        // var relation_with_verifier = data.result.relation_with_verifier!=null ? data.result.relation_with_verifier : 'N/A';
                        var period_stay_from = data.result.period_stay_from!=null && data.result.period_stay_from!='' ? data.result.period_stay_from : 'N/A';
                        var period_stay_to = data.result.period_stay_to!=null && data.result.period_stay_to!='' ? data.result.period_stay_to : 'N/A';

                        $('span.candidate_name').html(candidate_name);
                        $('span.check_name').html(check_name);
                        $('span.submit_at').html(submit_at);

                        $('span.first_name').html(first_name);
                        $('span.last_name').html(last_name);
                        $('span.email').html(email);
                        $('span.phone').html(phone);
                        $('span.address_1').html(address_1);
                        $('span.address_2').html(address_2);
                        $('span.pincode').html(pincode);
                        $('span.country').html(country);
                        $('span.state').html(state);
                        $('span.city').html(city);
                        $('span.address_type').html(address_type);
                        $('span.ownership_type').html(ownership_type);
                        $('span.landmark').html(landmark);
                        // $('span.nature_of_residence').html(nature_of_residence);
                        // $('span.verifier_name').html(verifier_name);
                        // $('span.relation_with_verifier').html(relation_with_verifier);
                        $('span.period_stay_from').html(period_stay_from);
                        $('span.period_stay_to').html(period_stay_to);

                        $('div.add_attach_data').html(data.form);
                    }
                },
                error: function (xhr, textStatus, errorThrown) {
                    // alert("Error: " + errorThrown);
                }
            });
         });

   });

   $('#digital_signature').change(function(){
         var file = this.files[0].name;
          $('#preview_ds').attr('src','');
          let reader = new FileReader();
          reader.onload = (e) => { 
              $('.close_btn').removeClass('d-none');
              $('#preview_ds').attr('src', e.target.result); 
          }
          reader.readAsDataURL(this.files[0]);
          $('#digital_label').html(file);
           
   });

   $(document).on('click','.close_btn',function(){
         $('#preview_ds').removeAttr('src'); 
         $(this).addClass('d-none');
         $('#digital_label').html('Choose File...');
         $(this).parents().eq(2).find('#digital_signature').val("");
   });
   function selectSuppFileType(selectedtype){
      var serviceOptionval= $("#service_add_supp-"+selectedtype).val();
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
      // alert(serviceOptionval);
   }
   function uploadFile(dynamicID,fileResult,type,number){
     
      $("#fileUploadProcess").html("<img src='{{asset('images/process-horizontal.gif')}}'  >"); 
      $('.bcd_loading').css('display', 'block');
      var attached_file_type='';
        var attached_file_name=''; 
        var attached_select_option='';
      // die;
      var fd = new FormData();

      var jaf_id=$('#jaf_id').val();

      // alert(fd);
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
         attached_select_option =$("#service_select_main-"+dynamicID).find('option:selected').attr("data-name");
         attached_file_name=$("#attachment_name-"+dynamicID).val();
        }
      fd.append('candidate_id',"{{ base64_encode($candidate->id) }}");
      fd.append('business_id',"$candidate->business_id");
      fd.append('jaf_id',dynamicID);
      fd.append('type',type);
      fd.append('service_type',attached_file_type);
      fd.append('select_file',attached_select_option);
      fd.append('attachment_name',attached_file_name);
      fd.append('_token', '{{csrf_token()}}');
      //
      $.ajax({
            type: 'POST',
            url: "{{ url('/jaf/upload/file') }}",
            data: fd,
            processData: false,
            contentType: false,
            success: function(data) {
               window.setTimeout(function(){
                    $('.bcd_loading').css('display', 'none');
                      },2000);
            console.log(data);
            if (data.fail == false) {
            //reset data
            $('.fileupload').val("");
            $("#fileUploadProcess").html("");
            $(".service_select_main").html(window.location.reload());
            $(".service_select_supp").html(window.location.reload());
            //append result

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

            // $.each(data.data, function(key, value) {
            //       $("#"+fileResult+"-"+dynamicID).append("<div class='image-area' ><img src='"+value.filePrev+"'  alt='Preview'><a class='remove-image' data-id='"+value.file_id+"' href='javascript:;' style='display: inline;'>&#215;</a><input type='hidden' name='fileID[]' value='"+value.file_id+"'></div>");
            // });
                  
            } else {
               $("#fileUploadProcess").html("");
               // alert("Please upload valid file! allowed file type, Image JPG, PNG etc. ");
               swal({
                  title: "Oh no!",
                  text: 'Please upload valid file! allowed file type, Image JPG, PNG, PDF etc.',
                  type: 'error',
                  buttons: true,
                  dangerMode: true,
                  confirmButtonColor:'#003473'
               });
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

window.onscroll = function() {stickyFun()};

var navbar = document.getElementById("myPillTab");
var sticky = navbar.offsetTop;

function stickyFun() {
  if (window.pageYOffset >= sticky) {
    navbar.classList.add("sticky")
  } else {
    navbar.classList.remove("sticky");
  }
}

</script>

@endsection
