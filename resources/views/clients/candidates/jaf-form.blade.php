@extends('layouts.client')
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

  .remove-image
    {
        padding: 0px 3px 0px !important;
    }

    .image-area img{
        height: 100px !important;
        width: 100px !important;
        padding: 8px !important;
    }

    .image-area{
        width: 90px !important;
    }

    .remove-image:hover
    {
        padding: 0px 3px 0px !important;
    }
    .refnum{
      text-align: right;
    }
</style>
@section('content')

<div class="main-content-wrap sidenav-open d-flex flex-column">
<!-- ============ Body content start ============= -->
<div class="main-content">
<div class="row">
   <div class="card text-left">
      <div class="card-body">
         <div class="row">
            <div class="col-md-12 text-center">
               <h3 class=" mb-1 ">BGV - Background Varification</h3>
               <p>Submit candidate BGV inputs.</p>
              
            </div>
            <div class="col-md-12">
                <?php $job_item_id = Request::segment(4); ?>
               <form class="mt-2" method="post" enctype="multipart/form-data" action="{{ url('my/candidates/jafFormSave/') }}" id="report_form">
                @csrf
                <!-- candidate info -->
                <input type="hidden" name="case_id" value="{{ $job_item_id }}" >
                <input type="hidden" name="candidate_id" value="{{ $candidate->id }}" >
                <input type="hidden" name="business_id" value="{{ $candidate->business_id }}" >
                <div class="row">
                    <div class="col-md-12">
                        <h4 class="card-title mb-1 mt-2">Profile Info</h4>
                          <div class="refnum">
                              <small class="text-muted">Ref. No.: {{ $candidate->display_id }}</small>
                          </div>
                        <p class="pb-border"></p>
                        <div class="row">
                          <div class="col-sm-4">
                              <div class="form-group">
                                <label>First name: <strong>{{ ucwords(strtolower($candidate->first_name)) }}</strong> </label>
                              </div>
                          </div>
                          <div class="col-sm-4">
                            <div class="form-group">
                            <label>Middle name: <strong>{{ $candidate->middle_name!=NULL ? ucwords(strtolower($candidate->middle_name)) : 'N/A' }}</strong></label>
                            </div>
                        </div>
                          <div class="col-sm-4">
                              <div class="form-group">
                              <label>Last name: <strong>{{ $candidate->last_name ?ucwords(strtolower($candidate->last_name)) : 'N/A' }}</strong></label>
                              </div>
                          </div>
                          <div class="col-sm-4">
                            <div class="form-group">
                            <label>Father name: <strong>{{ ucwords(strtolower($candidate->father_name)) }}</strong></label>
                            </div>
                        </div>
                        <div class="col-sm-4">
                          <div class="form-group">
                          <label>DOB: <strong>{{ date('d-m-Y',strtotime($candidate->dob)) }}</strong></label>
                          {{-- <input class="form-control dob commonDatepicker" type="text" name="dob" value="{{ date('d-m-Y',strtotime($candidate->dob)) }}" readonly> --}}
                          {{-- <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-dob"></p> --}}
                          </div>
                        </div>
                        <div class="col-sm-4">
                          <div class="form-group">
                          <label>Gender: <strong>{{ $candidate->gender }}</strong> </label>
                          {{-- <input class="form-control " type="text" name="gender" value="{{ $candidate->gender }}" readonly> --}}
                          </div>
                        </div>
                     
                      </div>

                      <div class="row">
                          <div class="col-sm-4">
                            <div class="form-group">
                            <label>Aadhar Number: <strong>{{ $candidate->aadhar_number!=NULL ? $candidate->aadhar_number : 'N/A' }}</strong> </label>
                            {{-- <input class="form-control " type="text" name="aadhar_number" value="{{ $candidate->aadhar_number }}" readonly> --}}
                            </div>
                        </div>
                          <div class="col-sm-4">
                              <div class="form-group">
                              <label>Email: <strong>{{ $candidate->email!=NULL ? $candidate->email : 'N/A' }}</strong> </label>
                              {{-- <input class="form-control " type="text" name="email" value="{{ $candidate->email }}" readonly> --}}
                              </div>
                          </div>
                          <div class="col-sm-4">
                              <div class="form-group">
                              <label>Phone: <strong>+{{$candidate->phone_code}}-{{ $candidate->phone }}</strong></label>
                              {{-- <input class="form-control number_only" type="text" name="phone" value="{{ $candidate->phone }}" readonly> --}}
                              </div>
                          </div>
                          <div class="col-sm-4">
                            <div class="form-group">
                            <label>Client emp code: <strong>{{ $candidate->client_emp_code!=NULL ? $candidate->client_emp_code : 'N/A'}}</strong>  </label>
                            {{-- <input class="form-control " type="text" name="client_emp_code" value="{{ $candidate->client_emp_code }}"> --}}
                            </div>
                        </div>
                        <div class="col-sm-4">
                          <div class="form-group">
                          <label>Entity code: <strong>{{ $candidate->entity_code!=NULL ? $candidate->entity_code : 'N/A' }}</strong></label>
                          {{-- <input class="form-control " type="text" name="entity_code" value="{{ $candidate->entity_code }}"> --}}
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
                <?php $user = Auth::user()->user_type ;
                ?>
                {{-- @if ($user == 'client') --}}
        
                @if( count($jaf_items) >0  )
                @foreach($jaf_items as $item)
                  <?php
                    $j=1;
                    $num ="";
                  ?>
                 
                  <div class="row" style="padding: 10px 0; margin-top:10px; border:1px solid #ddd;">
                     <div class="col-md-6">

                        <h3 class=" mb-2 mt-2">Verification - {{$item->service_name.' - '.$item->check_item_number}}</h3>
                        <p>Provide the inputs data</p>

                        <div class="row">
                          <div class="col-sm-10">
                              <div class="form-group">
                                <div class="form-check">
                                  <label class="form-check-label error-control">
                                    <input style="margin-top: 1px;" type="checkbox" class="form-check-input error-control check_ignore"  data-id="{{ $item->id }}" name="check_ignore-{{ $item->id }}" > Ignore
                                  </label>
                                </div>
                              </div>
                          </div>
                        </div>

                        <!-- if check type is address  -->
                        @if($item->service_id == '1')
                          <div class="row" >
                            <div class="col-sm-10">
                                <div class="form-group"> 
                                <label>Address Type <span class="text-danger">*</span></label>
                                  <select class="form-control address-type-{{$item->id}}" name="address-type-{{$item->id}}" >
                                    <option value="">- Select Type -</option>
                                    <option value="current" @if($item->address_type !=null) @if($item->address_type=='current') selected @endif @endif>Current</option>
                                    <option value="permanent" @if($item->address_type !=null) @if($item->address_type=='permanent') selected @endif @endif>Permanent</option>
                                    <option value="previous" @if($item->address_type !=null) @if($item->address_type=='previous') selected @endif @endif >Previous</option>
                                  </select>
                                  <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-address-type-{{$item->id}}"></p>
                                </div>
                            </div>
                          </div>
                        @endif
                        <!--  -->
                        <?php
                         $i=0; $form_items= Helper::get_sla_item_inputs($item->service_id); 

                         $input_item_data = $item->form_data;
                         $input_item_data_array =  json_decode($input_item_data, true); 
                        ?>
                         @if ($input_item_data_array != null)
                            @foreach($input_item_data_array as $key => $input)
                              <?php
                                    $key_val = array_keys($input);
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
                              <div class="row ">
                                  <div class="col-sm-12 {{$labelname}}">
                                        <div class="form-group">
                                        <?php $key_val = array_keys($input); $input_val = array_values($input); 
                                              
                                              $labelname = '';
                                              $university_board =  $readonly= "";
                                              $university_board_id="";
                                              $date_calss='';
                                              $input_class='error-control';
                                              if($key_val[0] =='University Name / Board Name'){ 
                                                $university_board_id = "#searchUniversity_board";
                                                $university_board = "searchUniversity_board";
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
                                          if($key_val[0]=='Date of Birth' || $key_val[0]=='DOB' || $key_val[0]=='dob' || stripos($key_val[0],'Date of Expire')!==false){ 
                                            // $dob = $candidate->dob;
                                            // if($dob !=NULL){
                                            //   $name = date('d-m-Y',strtotime($candidate->dob));
                                            // }
                                            $date_calss = 'commonDatepicker';
                                          }
                                          $country_name = Helper::get_country_list();

                                        
                                        ?>
                                        <label class="{{$labelname}}">  {{ $key_val[0]}} </label><br>
                                        <input type="hidden" name="service-input-label-{{ $item->id.'-'.$i }}" value="{{ $key_val[0]}}">
                                        @if($item->service_id==17)
                                          @if(stripos($key_val[0],'Reference Type (Personal / Professional)')!==false)
                                              <select class="form-control {{$date_calss.''.$university_board }} service-input-value-{{$item->id.'-'.$i}} {{$input_class}} check-input-{{$item->id}}" {{$readonly}} id="{{ $university_board_id }}" type="text" name="service-input-value-{{ $item->id.'-'.$i }}">
                                                <option value="">--Select--</option>
                                                <option @if(stripos($input_val[0],'personal')!==false) selected @endif value="personal">Personal</option>
                                                <option @if(stripos($input_val[0],'professional')!==false) selected @endif value="professional">Professional</option>
                                              </select>
                                          @else
                                              <input class="form-control {{ $date_calss.''.$university_board }} service-input-value-{{$item->id.'-'.$i}} {{$input_class}} check-input-{{$item->id}}" {{$readonly}} id="{{ $university_board_id }}" type="text" name="service-input-value-{{ $item->id.'-'.$i }}" value="{{ $input_val[0] }}">
                                          @endif
                                        @elseif (stripos($item->type_name,'drug_test_5')!==false || stripos($item->type_name,'drug_test_10')!==false)
                                          @if(stripos($key_val[0],'Test Name')!==false)
                                            <input class="form-control service-input-value-{{$item->id.'-'.$i}} {{$input_class}} check-input-{{$item->id}}" type="hidden" name="service-input-value-{{ $item->id.'-'.$i }}" value="{{$input_val[0]}}">
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
                                            <select class="form-control {{$date_calss.' '.$university_board }} service-input-value-{{$item->id.'-'.$i}} {{$input_class}} check-input-{{$item->id}}" name="service-input-value-{{ $item->id.'-'.$i }}" >
                                                <option value="">--Select--</option>
                                                <option @if(stripos($input_val[0],'positive')!==false) selected @endif value="positive">Positive</option>
                                                <option @if(stripos($input_val[0],'negative')!==false) selected  @endif value="negative">Negative</option>
                                            </select> 
                                          @else
                                            <input class="form-control {{$date_calss.' '.$university_board }} service-input-value-{{$item->id.'-'.$i}} {{$input_class}} check-input-{{$item->id}}" type="text" name="service-input-value-{{ $item->id.'-'.$i }}" value="{{ $input_val[0] }}" >
                                          @endif
                                        @elseif($item->service_id==15)
                                          @if ($key_val[0]=='Address Type')
                                            <select class="form-control {{$date_calss.' '.$university_board }} service-input-value-{{$item->id.'-'.$i}} {{$input_class}} check-input-{{$item->id}}" name="service-input-value-{{ $item->id.'-'.$i }}" >
                                                <option value="">--Select--</option>
                                                <option @if(stripos($input_val[0],'current')!==false) selected @endif value="current">Current</option>
                                                <option @if(stripos($input_val[0],'permanent')!==false) selected  @endif value="permanent">Permanent</option>
                                                <option @if(stripos($input_val[0],'current_permanent')!==false) selected  @endif value="current_permanent">Current + Permanent</option>
                                                <option @if(stripos($input_val[0],'previous')!==false) selected  @endif value="previous">Previous</option>
                                              </select> 
                                          @else
                                          <input class="form-control {{$date_calss.''.$university_board }} service-input-value-{{$item->id.'-'.$i}} {{$input_class}} check-input-{{$item->id}}" {{$readonly}} id="{{ $university_board_id }}" type="text" name="service-input-value-{{ $item->id.'-'.$i }}" value="{{ $input_val[0] }}" >
                                          @endif

                                          @elseif($item->type_name=='global_database')
                                          @if ($key_val[0]=='Country')
                                            <select class="form-control {{$date_calss.' '.$university_board }} service-input-value-{{$item->id.'-'.$i}} {{$input_class}} check-input-{{$item->id}}" name="service-input-value-{{ $item->id.'-'.$i }}" >
                                                <option value="">--Select--</option>
                                                  @foreach ($country_name as $country)  
                                                    <option  value="{{$country->name}}">{{$country->name}}</option>
                                                  @endforeach
                                            </select>
                                          
                                          @elseif($key_val[0]=='Criminal Records Database Checks - India')
                                            <input class="form-control {{$date_calss.''.$university_board }} service-input-value-{{$item->id.'-'.$i}} {{$input_class}}" {{$readonly}} id="{{ $university_board_id }}" type="hidden" name="service-input-value-{{ $item->id.'-'.$i }}" value="{{ $input_val[0] }}">
                                          
                                          @elseif($key_val[0]=='Civil Litigation Database Checks – India')
                                            <input class="form-control {{$date_calss.''.$university_board }} service-input-value-{{$item->id.'-'.$i}} {{$input_class}}" {{$readonly}} id="{{ $university_board_id }}" type="hidden" name="service-input-value-{{ $item->id.'-'.$i }}" value="{{ $input_val[0] }}">
                                            
                                          @elseif($key_val[0]=='Credit and Reputational Risk Database Checks – India')
                                            <input class="form-control {{$date_calss.''.$university_board }} service-input-value-{{$item->id.'-'.$i}} {{$input_class}}" {{$readonly}} id="{{ $university_board_id }}" type="hidden" name="service-input-value-{{ $item->id.'-'.$i }}" value="{{ $input_val[0] }}">    
                                          
                                          @elseif($key_val[0]=='Serious and Organized Crimes Database Checks – Global')
                                            <input class="form-control {{$date_calss.''.$university_board }} service-input-value-{{$item->id.'-'.$i}} {{$input_class}}" {{$readonly}} id="{{ $university_board_id }}" type="hidden" name="service-input-value-{{ $item->id.'-'.$i }}" value="{{ $input_val[0] }}">  
                                            
                                          @elseif($key_val[0]=='Global Regulatory Bodies')
                                            <input class="form-control {{$date_calss.''.$university_board }} service-input-value-{{$item->id.'-'.$i}} {{$input_class}}" {{$readonly}} id="{{ $university_board_id }}" type="hidden" name="service-input-value-{{ $item->id.'-'.$i }}" value="{{ $input_val[0] }}">  

                                          @elseif($key_val[0]=='Compliance Database')
                                            <input class="form-control {{$date_calss.''.$university_board }} service-input-value-{{$item->id.'-'.$i}} {{$input_class}}" {{$readonly}} id="{{ $university_board_id }}" type="hidden" name="service-input-value-{{ $item->id.'-'.$i }}" value="{{ $input_val[0] }}">  

                                          @elseif($key_val[0]=='Sanction & PEP - Global')
                                            <input class="form-control {{$date_calss.''.$university_board }} service-input-value-{{$item->id.'-'.$i}} {{$input_class}}" {{$readonly}} id="{{ $university_board_id }}" type="hidden" name="service-input-value-{{ $item->id.'-'.$i }}" value="{{ $input_val[0] }}">  

                                          @elseif($key_val[0]=='Web and Media Searches – Global')
                                            <input class="form-control {{$date_calss.''.$university_board }} service-input-value-{{$item->id.'-'.$i}} {{$input_class}}" {{$readonly}} id="{{ $university_board_id }}" type="hidden" name="service-input-value-{{ $item->id.'-'.$i }}" value="{{ $input_val[0] }}">  
                                          @else
                                            <input class="form-control {{$date_calss.''.$university_board }} service-input-value-{{$item->id.'-'.$i}} {{$input_class}} check-input-{{$item->id}}" {{$readonly}} id="{{ $university_board_id }}" type="text" name="service-input-value-{{ $item->id.'-'.$i }}" value="{{ $input_val[0] }}">
                                          @endif  
                                        @else
                                          <input class="form-control {{ $date_calss.''.$university_board }} service-input-value-{{$item->id.'-'.$i}} {{$input_class}} check-input-{{$item->id}}" {{$readonly}} id="{{ $university_board_id }}" type="text" name="service-input-value-{{ $item->id.'-'.$i }}" value="{{ $input_val[0] }}"> 
                                        @endif
                                        <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-service-input-value-{{$item->id.'-'.$i}}"></p>
                                        </div>
                                  </div>
                              </div>
                              <?php $i++; ?>
                           @endforeach 
                         @else
                            @foreach($form_items as $input)
                              @if($item->service_id==17)
                                @if($input->reference_type==NULL && !(stripos($input->label_name,'Mode of Verification')!==false || stripos($input->label_name,'Remarks')!==false))
                                  <div class="row" >
                                    <div class="col-sm-10">
                                        <div class="form-group">
                                        <label> {{ $input->label_name }} </label>
                                        <input type="hidden" name="service-input-label-{{ $item->id.'-'.$i }}" value="{{ $input->label_name }}">
                                        <input type="hidden" name="jaf_id" id="jaf_id" value="{{$item->id}}">
                                        <?php $input_type=""; $input_type = Helper::get_sla_item_input_type($input->form_input_type_id);
                                          $date_calss = ''; 
                                          // if($input_type == 'date'){
                                          //   $date_calss = 'commonDatepicker';
                                          // }
                                          $name =$lname = $father_name= $dob= "";
                                          $readonly ="";
                                          $input_class='error-control';
                                          $placeholder ="";
                                          //name
                                          if($input->label_name=='First Name' || $input->label_name=='First name' || $input->label_name=='first name'){ 
                                            $name = $candidate->first_name;
                                            $readonly ="readonly";
                                            $input_class='';
                                          }
                                          if($input->label_name=='Last Name' || $input->label_name=='Last name' || $input->label_name=='last name'){ 
                                            $name = $candidate->last_name;
                                            $readonly ="readonly";
                                            $input_class='';
                                          }
                                          //fateher name
                                          if($input->label_name=='Father Name' || $input->label_name=='father name' || $input->label_name=='Father name'){ 
                                            $name = $candidate->father_name;
                                          }
                                          //dob
                                          if($input->label_name=='Date of Birth' || $input->label_name=='DOB' || $input->label_name=='dob'){ 
                                            $dob = $candidate->dob;
                                            if($dob !=NULL){
                                              $name = date('d-m-Y',strtotime($candidate->dob));
                                            }

                                            $date_calss = 'commonDatepicker';

                                          }
                                          if(stripos($input->label_name,'Date of Expire')!==false)
                                          {
                                            $date_calss = 'commonDatepicker';
                                          }
                                          if($input->label_name=='Period of Stay' || $input->label_name=='Period of stay' || $input->label_name=='period of stay'){
                                            $placeholder ="ex- No of days ";
                                          
                                          }
                                          $university_board_name = "";
                                          if($input->label_name=='University Name / Board Name'){ 
                                            $university_board_name = "searchUniversity_board";
                                          }
                                        ?>
                                        @if(stripos($input->label_name,'Reference Type (Personal / Professional)')!==false)
                                          <select {{ $readonly }} class="form-control {{$date_calss.' '.$university_board_name }} service-input-value-{{$item->id.'-'.$i}} {{$input_class}} check-input-{{$item->id}}" type="text" name="service-input-value-{{ $item->id.'-'.$i }}" placeholder="{{ $placeholder }}">
                                              <option value="">--Select--</option>
                                              <option @if(stripos($name,'personal')!==false) selected @endif value="personal">Personal</option>
                                              <option @if(stripos($name,'professional')!==false) selected  @endif value="professional">Professional</option>
                                          </select>
                                        @else
                                          <input {{ $readonly }} class="form-control {{$date_calss.' '.$university_board_name }} service-input-value-{{$item->id.'-'.$i}} {{$input_class}} check-input-{{$item->id}}" type="text" name="service-input-value-{{ $item->id.'-'.$i }}" value="{{ $name }}" placeholder="{{ $placeholder }}">
                                        @endif
                                        <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-service-input-value-{{$item->id.'-'.$i}}"></p>
                                        </div>
                                    </div>
                                  </div>
                                @endif
                              @else
                              <?php
                                $labelname = '';
                                if($item->type_name=='global_database'){
                                  if(stripos($input->label_name,'Criminal Records Database Checks - India')!==false)
                                    {
                                      $labelname = 'd-none';
                                    }
                                    elseif(stripos($input->label_name,'Civil Litigation Database Checks – India')!==false)
                                    {
                                      $labelname = 'd-none';
                                    }
                                    elseif(stripos($input->label_name,'Credit and Reputational Risk Database Checks – India')!==false)
                                    {
                                      $labelname = 'd-none';
                                    }
                                    elseif(stripos($input->label_name,'Serious and Organized Crimes Database Checks – Global')!==false)
                                    {
                                      $labelname = 'd-none';
                                    }
                                    elseif(stripos($input->label_name,'Global Regulatory Bodies')!==false)
                                    {
                                      $labelname = 'd-none';
                                    }
                                    elseif(stripos($input->label_name,'Compliance Database')!==false)
                                    {
                                      $labelname = 'd-none';
                                    }
                                    elseif(stripos($input->label_name,'Sanction & PEP - Global')!==false)
                                    {
                                      $labelname = 'd-none';
                                    }
                                    elseif(stripos($input->label_name,'Web and Media Searches – Global')!==false)
                                    {
                                      $labelname = 'd-none';
                                    }
                                  }
                              ?>
                                <div class="row {{$labelname}}" >
                                    <div class="col-sm-10">
                                        <div class="form-group">
                                        <label> {{ $input->label_name }} </label><br>
                                        <input type="hidden" name="service-input-label-{{ $item->id.'-'.$i }}" value="{{ $input->label_name }}">
                                        <input type="hidden" name="jaf_id" id="jaf_id" value="{{$item->id}}">
                                        <?php $input_type=""; $input_type = Helper::get_sla_item_input_type($input->form_input_type_id);
                                          $date_calss = ''; 
                                          // if($input_type == 'date'){
                                          //   $date_calss = 'commonDatepicker';
                                          // }
                                          $name =$lname = $father_name= $dob= "";
                                          $readonly ="";
                                          $input_class='error-control';
                                          $placeholder = '';
                                          //name
                                          if($input->label_name=='First Name' || $input->label_name=='First name' || $input->label_name=='first name'){ 
                                            $name = $candidate->first_name;
                                            $readonly ="readonly";
                                            $input_class='';
                                          }
                                          if($input->label_name=='Candidate Name' || $input->label_name=='Candidate Name' || $input->label_name=='candidate name'){ 
                                            $name = $candidate->first_name;
                                            $readonly ="readonly";
                                            $input_class='';
                                          }
                                          if($input->label_name=='Last Name' || $input->label_name=='Last name' || $input->label_name=='last name'){ 
                                            $name = $candidate->last_name;
                                            $readonly ="readonly";
                                            $input_class='';
                                           
                                          }
                                          //fateher name
                                          if($input->label_name=='Father Name' || $input->label_name=='father name' || $input->label_name=='Father name'){ 
                                            $name = $candidate->father_name;
                                          }
                                          //dob
                                          if($input->label_name=='Date of Birth' || $input->label_name=='DOB' || $input->label_name=='dob'){ 
                                            $dob = $candidate->dob;
                                            if($dob !=NULL){
                                              $name = date('d-m-Y',strtotime($candidate->dob));
                                            }

                                            $date_calss = 'commonDatepicker';

                                          }

                                          $university_board_name = "";
                                          if($input->label_name=='University Name / Board Name'){ 
                                            $university_board_name = "searchUniversity_board";
                                          }
                                          $country_name = Helper::get_country_list();
                                        ?>
                                        @if(stripos($input->type_name,'drug_test_5')!==false || stripos($input->type_name,'drug_test_10')!==false)
                                          @if(stripos($input->label_name,'Test Name')!==false)
                                            <input class="form-control service-input-value-{{$item->id.'-'.$i}} {{$input_class}}" type="hidden" name="service-input-value-{{ $item->id.'-'.$i }}">
                                            @php
                                              $drug_test_name = Helper::drugTestName($input->service_id);
                                            @endphp
                                            @if(count($drug_test_name)>0)
                                              @foreach ($drug_test_name as $d_item)
                                                <div class="form-check form-check-inline disabled-link-1">
                                                    <input class="form-check-input test-name-{{$item->id.'-'.$i}}" type="checkbox" name="test-name-{{$item->id.'-'.$i}}[]" value="{{$d_item->test_name}}" checked readonly>
                                                    <label class="form-check-label" for="inlineCheckbox-1">{{$d_item->test_name}}</label>
                                                </div>
                                              @endforeach
                                            @endif
                                          @elseif (stripos($input->label_name,'Result')!==false)
                                            <select {{ $readonly }} class="form-control {{$date_calss.' '.$university_board_name }} service-input-value-{{$item->id.'-'.$i}} {{$input_class}} check-input-{{$item->id}}" name="service-input-value-{{ $item->id.'-'.$i }}">
                                                <option value="">--Select--</option>
                                                <option @if(stripos($name,'positive')!==false) selected @endif value="positive">Positive</option>
                                                <option @if(stripos($name,'negative')!==false) selected  @endif value="negative">Negative</option>
                                            </select>
                                          @else
                                            <input {{ $readonly }} class="form-control {{$date_calss.' '.$university_board_name }} service-input-value-{{$item->id.'-'.$i}} {{$input_class}} check-input-{{$item->id}}" type="text" name="service-input-value-{{ $item->id.'-'.$i }}" value="{{ $name }}">
                                          @endif
                                        @elseif ($input->service_id==15)
                                            @if ($input->label_name=='Address Type')
                                              <select class="form-control {{$date_calss.' '.$university_board_name }} service-input-value-{{$item->id.'-'.$i}} {{$input_class}} check-input-{{$item->id}}" name="service-input-value-{{ $item->id.'-'.$i }}" >
                                                <option value="">--Select--</option>
                                                <option @if(stripos($name,'current')!==false) selected @endif value="current">Current</option>
                                                <option @if(stripos($name,'permanent')!==false) selected  @endif value="permanent">Permanent</option>
                                                <option @if(stripos($name,'current_permanent')!==false) selected  @endif value="current_permanent">Current + Permanent</option>
                                                <option @if(stripos($input_val[0],'previous')!==false) selected  @endif value="previous">Previous</option>
                                              </select> 
                                            @else
                                              <input {{ $readonly }} class="form-control {{$date_calss.' '.$university_board_name }} service-input-value-{{$item->id.'-'.$i}} {{$input_class}}" type="text" name="service-input-value-{{ $item->id.'-'.$i }}" value="{{ $name }}"   placeholder="{{ $placeholder }}"> 
                                            @endif
                                           
                                          @elseif ($input->type_name=='global_database')
                                          @if ($input->label_name=='Country')
                                            <select class="form-control {{$date_calss.' '.$university_board_name }} service-input-value-{{$item->id.'-'.$i}} {{$input_class}} check-input-{{$item->id}}" name="service-input-value-{{ $item->id.'-'.$i }}" >
                                              <option value="">--Select--</option>
                                                @foreach ($country_name as $country)  
                                                  <option  value="{{$country->name}}">{{$country->name}}</option>
                                                @endforeach
                                            </select> 
                                            @elseif($input->label_name=='Criminal Records Database Checks - India')
                                              <input {{ $readonly }} class="form-control {{$date_calss.' '.$university_board_name }} service-input-value-{{$item->id.'-'.$i}} {{$input_class}}" type="hidden" name="service-input-value-{{ $item->id.'-'.$i }}" value="{{ $name }}"   placeholder="{{ $placeholder }}"> 
                                            
                                            @elseif($input->label_name=='Civil Litigation Database Checks – India')
                                              <input {{ $readonly }} class="form-control {{$date_calss.' '.$university_board_name }} service-input-value-{{$item->id.'-'.$i}} {{$input_class}}" type="hidden" name="service-input-value-{{ $item->id.'-'.$i }}" value="{{ $name }}"   placeholder="{{ $placeholder }}">
                                            
                                            @elseif($input->label_name=='Credit and Reputational Risk Database Checks – India')
                                              <input {{ $readonly }} class="form-control {{$date_calss.' '.$university_board_name }} service-input-value-{{$item->id.'-'.$i}} {{$input_class}}" type="hidden" name="service-input-value-{{ $item->id.'-'.$i }}" value="{{ $name }}"   placeholder="{{ $placeholder }}"> 
                                            
                                            @elseif($input->label_name=='Serious and Organized Crimes Database Checks – Global')
                                              <input {{ $readonly }} class="form-control {{$date_calss.' '.$university_board_name }} service-input-value-{{$item->id.'-'.$i}} {{$input_class}}" type="hidden" name="service-input-value-{{ $item->id.'-'.$i }}" value="{{ $name }}"   placeholder="{{ $placeholder }}"> 
                                            
                                            @elseif($input->label_name=='Global Regulatory Bodies')
                                              <input {{ $readonly }} class="form-control {{$date_calss.' '.$university_board_name }} service-input-value-{{$item->id.'-'.$i}} {{$input_class}}" type="hidden" name="service-input-value-{{ $item->id.'-'.$i }}" value="{{ $name }}"   placeholder="{{ $placeholder }}"> 
                                            
                                            @elseif($input->label_name=='Compliance Database')
                                              <input {{ $readonly }} class="form-control {{$date_calss.' '.$university_board_name }} service-input-value-{{$item->id.'-'.$i}} {{$input_class}}" type="hidden" name="service-input-value-{{ $item->id.'-'.$i }}" value="{{ $name }}"   placeholder="{{ $placeholder }}">
                                            
                                            @elseif($input->label_name=='Sanction & PEP - Global')
                                              <input {{ $readonly }} class="form-control {{$date_calss.' '.$university_board_name }} service-input-value-{{$item->id.'-'.$i}} {{$input_class}}" type="hidden" name="service-input-value-{{ $item->id.'-'.$i }}" value="{{ $name }}"   placeholder="{{ $placeholder }}">   
                                              
                                            @elseif($input->label_name=='Web and Media Searches – Global')
                                              <input {{ $readonly }} class="form-control {{$date_calss.' '.$university_board_name }} service-input-value-{{$item->id.'-'.$i}} {{$input_class}}" type="hidden" name="service-input-value-{{ $item->id.'-'.$i }}" value="{{ $name }}"   placeholder="{{ $placeholder }}">          
                                              
                                            @else
                                            <input {{ $readonly }} class="form-control {{$date_calss.' '.$university_board_name }} service-input-value-{{$item->id.'-'.$i}} {{$input_class}} check-input-{{$item->id}}" type="text" name="service-input-value-{{ $item->id.'-'.$i }}" value="{{ $name }}"   placeholder="{{ $placeholder }}"> 
                                          @endif    
                                        @else
                                          <input {{ $readonly }} class="form-control {{$date_calss.' '.$university_board_name }} service-input-value-{{$item->id.'-'.$i}} {{$input_class}} check-input-{{$item->id}}" type="text" name="service-input-value-{{ $item->id.'-'.$i }}" value="{{ $name }}">
                                        @endif
                                        <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-service-input-value-{{$item->id.'-'.$i}}"></p>
                                        </div>
                                    </div>
                                </div>
                              @endif
                             <?php $i++; ?>
                            @endforeach
                         @endif
                       <!-- insufficiency -->
                            <div class="row">
                                <div class="col-sm-10">
                                    <div class="form-group">
                                      <div class="form-check">
                                        <label class="form-check-label error-control">
                                          <input style="margin-top: 1px;" type="checkbox" class="form-check-input error-control check-input-{{$item->id}}" name="insufficiency-{{ $item->id }}"  @if($item->is_insufficiency==1) checked @endif>Mark as insufficiency
                                        </label>
                                      </div>
                                    </div>
                                </div>
                                <!--  -->
                                <div class="col-sm-10">
                                    <div class="form-group">
                                        <label>insufficiency Notes</label>
                                        <input type="text" class="form-control error-control check-input-{{$item->id}}" name="insufficiency-notes-{{ $item->id }}" @if($item->insufficiency_notes!=NULL) value="{{$item->insufficiency_notes}}" @endif>
                                    </div>
                                </div>
                                <!-- ./ -->
                                <div class="col-sm-10">
                                  <div class="form-group">
                                    <label for="label_name">Insufficiency Attachments: </label>
                                    <input type="file" name="attachments-{{$item->id}}[]" id="attachments-{{$item->id}}" multiple class="form-control attachments-{{$item->id}} error-control">
                                    <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-attachments-{{$item->id}}"></p>  
                                </div>
                              </div>
                            </div>
                       <!-- ./insufficiency -->
                     </div>
                     <!-- attachment  -->
                     
                     <div class="col-md-6">
                     @php $service_name=Helper::service_attachment_type($item->service_id); @endphp
                        <p>Attachments</p>
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
                        <a class='btn-link clickSelectFile' id="buttonToSelect-{{$item->id}}" add-id="{{$item->id}}" data-number='1' data-result='fileResult1' data-type='main' style='color: #0056b3; font-size: 16px; display:none ' href='javascript:;'><i class='fa fa-plus'></i> Add file</a>
                        <input type='file' class='fileupload' name="file-{{$item->id}}[]" id='file1-{{$item->id}}' multiple="multiple" style='display:none'/>
                        <div class="bcd_loading"></div>
                        <div class='row fileResult' id="fileResult1-{{$item->id}}" style='min-height: 20px; margin-top: 20px;'>
                        <?php $item_files = Helper::getJAFAttachFiles($item->id); //print_r($item_files); ?>
                        @foreach($item_files as $file)
                        <?php $attached_file_id=$file['attached_file_id']; $attached_files = Helper::getAttachedFileName($attached_file_id); //print_r($item_files); ?>
                            @if($file['attachment_type'] == 'main')
                            <div class="image-area">
                              @if(stripos($file['file_name'],'pdf')!==false)
                                    <img src="{{url('/').'/admin/images/icon_pdf.png'}}" alt="Preview" title="{{$file['file_name']}}">
                              @else
                              @foreach($attached_files as $afile)
                                    <!-- <img src="{{ $file['fileIcon'] }}" alt="Preview" title="{{$file['file_name']}}"> -->
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
                  
                  <!-- row close -->
                
                 @endforeach
                 @endif

                 <div class="row" style="padding: 10px 0; margin-top:10px; border:1px solid #ddd;">
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label>Digital Signature </label>
                      <input class="form-control digital_signature error-control" type="file" name="digital_signature" id="digital_signature">
                      {{-- @if ($errors->has('digital_signature'))
                        <div class="error text-danger">
                            {{ $errors->first('digital_signature') }}
                        </div>
                        @endif --}}
                        <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-digital_signature"></p> 
                    </div>
                  </div>
                  @if($candidate->digital_signature!=NULL || $candidate->digital_signature!='')
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label for="company_logo"></label>
                        <span class="btn btn-link float-right text-dark close_btn">X</span>
                        <img id="preview_ds"  src="{{url('uploads/signatures/')}}/{{$candidate->digital_signature}}" width="200" height="150"/>
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
                 </div>      
                  <button type="submit" class="btn btn-info mt-3 jaf_submit">Save</button>
               </form>
            </div>
         </div>
      </div>
   </div>
</div>

@stack('scripts')
<script type="text/javascript">
//
$(document).ready(function() {
      var curNum ='';
      var fileResult='fileResult1';
      var selectedtype='';
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
      $(document).on('change','.service_select_main',function(){ 
            selectedtype = $(this).attr('data-select');
            type = $(this).attr('data-type');
         });
        $(document).on('change','.service_select_main',function(e){        
            selectFileType(selectedtype,type);
         });
      //
      $(document).on('change','.fileupload',function(e){        
        uploadFile(curNum,fileResult,type,number);
      });

      //remove file
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
        //    type: 'POST',
        //    url: "{{ url('my/jaf/remove/file') }}",
        //    data: fd,
        //    processData: false,
        //    contentType: false,
        //    success: function(data) {
        //       console.log(data);
        //       if (data.fail == false) {
        //          //reset data
        //          $('.fileupload').val("");
        //          //append result
        //          $(current).parent('.image-area').detach();
        //       }
        //       else if(data.fail == true)
        //       {
        //          toastr.error('You are not authorise to delete this file ! This file was uploaded by an Admin');
        //       } 
        //       else {
              
        //       console.log("file error!");
              
        //       }
        //    },
        //    error: function(error) {
        //       console.log(error);
        //       // $(".preview_image").attr("src","{{asset('images/file-preview.png')}}"); 
        //    }
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
                            url: "{{ url('/my/jaf/remove/file') }}",
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

    $(document).on('submit', 'form#report_form', function (event) {
                        
        $("#overlay").fadeIn(300);　
        event.preventDefault();
        var form = $(this);
        var data = new FormData($(this)[0]);
        var url = form.attr("action");
        var btn = $(this);
        var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
        $('.error-container').html('');
        $('.form-control').removeClass('border-danger');
        $('.jaf_submit').attr('disabled',true);
        $('.error-control').attr('readonly',true);
        $('.error-control').addClass('disabled-link');
        if ($('.jaf_submit').html() !== loadingText) {
              $('.jaf_submit').html(loadingText);
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
                    $('.jaf_submit').attr('disabled',false);
                    $('.jaf_submit').html('Save'); 
                    $('.error-control').attr('readonly',false);
                    $('.error-control').removeClass('disabled-link');
                  },2000);
                  $('.error-container').html('');

                  if (data.fail && data.error_type == 'validation') {
                    //$("#overlay").fadeOut(300);
                    for (control in data.errors) {
                    // $('textarea[comments=' + control + ']').addClass('is-invalid');
                    $('.'+control).addClass('border-danger');
                    $('#error-' + control).html(data.errors[control]);
                      $('input[name='+control+']').focus();
                      $('select[name='+control+']').focus();
                      $('textarea[name='+control+']').focus();
                    }
                  } 
                  //  if (data.fail && data.error == 'yes') {
                    
                  //      $('#error-all').html(data.message);
                  //  }
                  if (data.fail == false) {
                    // $('#send_otp').modal('hide');
                    // alert(data.id);
                    if(data.success){
                        toastr.success("BGV Filled Successfully");
                        // redirect to google after 5 seconds
                        var candidate_id=data.candidate_id;
                        window.setTimeout(function() {
                          window.location="{{url('/my/')}}"+'/candidates/jaf-info/'+candidate_id;    
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
              error: function (data) {
                  
                console.log(data);

              }
        });
        return false;
                                 
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

function uploadFile(dynamicID,fileResult,type,number){

    $("#fileUploadProcess").html("<img src='{{asset('images/process-horizontal.gif')}}' >"); 
    $('.bcd_loading').css('display', 'block');
    var attached_file_type='';
      var attached_file_name='';
      var attached_select_option='';
    var fd = new FormData();

    var jaf_id=$('#jaf_id').val();

    // alert(fd);
    var ins = document.getElementById("file"+number+"-"+dynamicID).files.length;
    // alert(ins);
    for (var x = 0; x < ins; x++) {
        fd.append("files[]", document.getElementById("file"+number+"-"+dynamicID).files[x]);
    }
    if(type=="main"){
            attached_file_type = $("#service_select_main-"+dynamicID).val();
            attached_select_option =$("#service_select_main-"+dynamicID).find('option:selected').attr("data-name");
            attached_file_name=$("#attachment_name-"+dynamicID).val();
        }
    fd.append('candidate_id',"{{ base64_encode($candidate->id) }}");
    fd.append('business_id',"$candidate->business_id")
    fd.append('jaf_id',dynamicID);
    fd.append('type',type);
    fd.append('service_type',attached_file_type);
    fd.append('select_file',attached_select_option);
    fd.append('attachment_name',attached_file_name);
    fd.append('_token', '{{csrf_token()}}');
    //
    $.ajax({
          type: 'POST',
          url: "{{ url('my/jaf/upload/file') }}",
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
            //     $("#"+fileResult+"-"+dynamicID).append("<div class='image-area'><img src='"+value.filePrev+"'  alt='Preview'><a class='remove-image' data-id='"+value.file_id+"' href='javascript:;' style='display: inline;'>&#215;</a><input type='hidden' name='fileID[]' value='"+value.file_id+"'></div>");
            // });
                
            } else {
              $("#fileUploadProcess").html("");
              //alert("Please upload valid file! allowed file type, Image JPG, PNG, PDF etc. ");
              swal({
                  title: "Oh no!",
                  text: 'Please upload valid file! allowed file type, Image JPG, PNG, PDF and only 10mb Upload !.',
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

</script>  
<script>  
  function autoSave()  
  {  
    var form = $(this);
    // var data = new FormData($(this));
    // alert(data);
    var formData = document.getElementById('report_form');
    var data = new FormData(formData);
    data.append('type','formtype');
    // var url = form.attr("action");
        // if(post_title != '' && post_description != '')  
        // {  
              $.ajax({  
                  url:"{{ url('/my/candidates/jafFormSave/') }}",  
                  type:"POST",  
                  data:data,  
                  cache: false,
                  contentType: false,
                  processData: false,  
                  success:function(response)  
                  {
                    if(response.success==true  && response.status=='hold')
                    {
                        var candidate_id = response.candidate_id;
                        var hold = response.hold_by;
                        // alert(hold);
                        toastr.success("BGV On Hold by "+hold);
                        window.setTimeout(function(){
                          window.location="{{url('/my/')}}"+'/candidates/';
                        },2000);
                    }  
                    else if(response.success==true  && response.status=='filled') 
                    {  
                        // $('#post_id').val(data);
                        // toastr.success("Data save successfully"); 
                        //  setInterval(function(){  
                        //   toastr.success("Data save successfully");   
                        //  }, 10000);
                        
                        var candidate_id = response.candidate_id;
                        var filled = response.filled_by;
                        // alert(filled);
                        toastr.success("BGV is Filled by "+filled);
                        window.setTimeout(function(){
                          window.location="{{url('/my/')}}"+'/candidates/jaf-info/'+candidate_id;
                        },2000);
                            
                    }  
                        
                  },
                  error: function (response) {
                    console.log(response);
                  }  
              });  
        // }            
  }  
    setInterval(function(){   
        autoSave();   
        }, 10000);    
    </script> 

<script>
  $('#digital_signature').change(function(){
          
    $('#preview_ds').attr('src','')
    let reader = new FileReader();
    reader.onload = (e) => { 
        $('.close_btn').removeClass('d-none');
        $('#preview_ds').attr('src', e.target.result); 
    }
    reader.readAsDataURL(this.files[0]); 
     
});
$(document).on('click','.close_btn',function(){
      $('#preview_ds').removeAttr('src'); 
      $(this).addClass('d-none');
      $(this).parents().eq(2).find('#digital_signature').val("");
});
</script>
@endsection