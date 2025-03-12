@extends('layouts.admin')
<style>
    .disabled-link {
      pointer-events: none;
    }
    .action-data
   {
      max-height: 300px;
      overflow-x: hidden;
      overflow-y: scroll;
   }
   .sweet-alert button.cancel {
        background: #DD6B55 !important;
    }
</style>
@section('content')
<div class="main-content-wrap sidenav-open d-flex flex-column">
   <!-- ============ Body content start ============= -->
   <div class="main-content"> 
                @php
                     // $ADD_ACCESS    = false;
                     $REASSIGN_ACCESS   = false;
                     $DASHBOARD_ACCESS =  false;
                     $VIEW_ACCESS   = false;
                     $DASHBOARD_ACCESS    = Helper::can_access('Dashboard','');//passing action title and route group name
                     $REASSIGN_ACCESS    = Helper::can_access('Reassign','');//passing action title and route group name
                     $VIEW_ACCESS   = Helper::can_access('View Task','');//passing action title and route group name
                @endphp 
        <div class="row">
            <div class="col-sm-11">
                <ul class="breadcrumb">
                @if($DASHBOARD_ACCESS)
                <li>
                <a href="{{ url('/home') }}">Dashboard</a>
                </li>
                <li>Candidates</li>
                @else
                <li>Candidates</li>
                @endif
                </ul>
            </div>
            <!-- ============Back Button ============= -->
            <div class="col-sm-1 back-arrow">
                <div class="text-right">
                <a href="{{url()->previous() }}"><i class="fas fa-arrow-circle-left fa-2x"></i></a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 content-wrapper" style="background:#fff">
                <div class="formCover py-2" style="height: 100vh;">
                    <section>
                        @include('admin.candidates.menu')
                    </section>
                    <div class="row">
                        <div class="card text-left">
                        <div class="card-body">  
                        
                                    <div class="row">

                                        @if ($message = Session::get('success'))
                                        <div class="col-md-12">   
                                            <div class="alert alert-success">
                                            <strong>{{ $message }}</strong> 
                                            </div> 
                                        </div>
                                        @endif 

                                        <div class="col-md-4">
                                            <h4 class="card-title mb-1"> Candidates </h4> 
                                            <p> List of all Candidates  </p>        
                                        </div>
                                        <div class="col-md-3">
                                            <span>Total Candidates: <span > {{ $tota_candidates }}</span> </span>
                                        </div>
                                        <div class="col-md-5">           
                                        <div class="btn-group" style="float:right">     
                                            <a href="#" class="filter0search"><i class="fa fa-filter"></i></a>   
                                            
                                            @php
                                            $ADD_ACCESS    = false;
                                            // $EDIT_ACCESS   = false;
                                            // $DELETE_ACCESS = false;
                                            // dd($ADD_ACCESS);
                                            $ADD_ACCESS    = Helper::can_access('Add Candidates','');
                                            // $EDIT_ACCESS   = Helper::can_access('Edit ');
                                            // $DELETE_ACCESS = Helper::can_access('Delete Category');
                                        @endphp 

                                        @if($ADD_ACCESS)
                                        <a class="btn btn-success" href="{{ url('/candidates/create')}}" > <i class="fa fa-plus"></i> Add New </a>              

                                        @endif  
                
                                            {{-- <a class="btn btn-success " href="{{ url('/candidates/create')}}" > <i class="fa fa-plus"></i> Add New </a>               --}}
                                        </div>
                                        </div>
                                    </div>
                                    <!-- search bar -->
                                    <div class="search-drop-field" id="search-drop">
                                        <div class="row">
                                            <div class="col-md-3 form-group mb-1 level_selector">
                                                <label for="picker1"> Customer </label>
                                                <select class="form-control customer_list select" name="customer" id="customer">
                                                <option>-Select-</option>
                                                @foreach($customers as $customer)
                                                    <option value="{{ $customer->id }}"> {{ $customer->company_name.' - '.$customer->name}} </option>
                                                @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3 form-group mb-1 level_selector">
                                                <label>Candidate Name</label><br>
                                                <select class="form-control candidate_list select" name="candidate" id="candidate">
                                                    <option>-Select-</option>
                                                </select>
                                                {{-- <input class="form-control candidate_list" type="text" placeholder="name"> --}}
                                            </div>
                                            <div class="col-md-2 form-group mb-1">
                                                <label> From date </label>
                                                <input class="form-control from_date commonDatePicker" type="text" placeholder="From date">
                                            </div>
                                            <div class="col-md-2 form-group mb-1">
                                                <label> To date </label>
                                                <input class="form-control to_date commonDatePicker" type="text" placeholder="To date">
                                            </div>
                                            <div class="col-md-2 form-group mb-1">
                                                <label>Phone number </label>
                                                <input class="form-control mob" type="text" placeholder="phone">
                                            </div>
                                            <div class="col-md-2 form-group mb-1">
                                                <label>Reference number </label>
                                                <input class="form-control ref" type="text" placeholder="reference number">
                                            </div>
                                            <div class="col-md-2 form-group mb-1">
                                                <label>Email id</label>
                                                <input class="form-control email" type="email" placeholder="email">
                                            </div>
                                            <div class="col-md-2 form-group mb-1">
                                                <label>BGV Send to</label>
                                                <select class="form-control "  name="remain" id="remain">
                                                    <option value="">All</option>
                                                    <option value="customer" >Customer</option>
                                                    <option value="coc" >COC</option>
                                                    <option value="candidate" >Candidate</option>
                                                </select>
                                            </div>
                                            <div class="col-md-2 form-group mb-1">
                                                <label>BGV filled</label>
                                                <select class="form-control" name="jaf_filled" id="active_case" >
                                                    <option value="">All</option>
                                                    <option  value="filled" <?=$filled=="filled"?"selected":""?>>Filled</option>
                                                    <option  value="draft" <?=$filled=="draft"?"selected":""?>>Draft</option>
                                                    <option  value="pending"  <?=$filled=="pending"?"selected":""?> >Pending</option>
                                                </select>
                                            </div>
                                            <div class="col-md-2 form-group mb-1">
                                                <label>Insuff Raised In</label>
                                                <select class="form-control" name="insuff_raised" id="insuff_raised" >
                                                    <option value="">All</option>
                                                    @foreach($array_result as $result)
                                                    <option value="{{$result['check_id']}}"> {{$result['check_name']}} ({{$result['insuf']}})</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-2 form-group mb-1">
                                                <label>Service</label>
                                                <select class="form-control" name="service_name" id="service_name" >
                                                    <option value="">All</option>
                                                    @foreach($services as $service)
                                                        <option value="{{ $service->id}}">{{ $service->name  }}</option>   
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-1">
                                                <button class="btn btn-danger resetBtn" style="padding: 7px;margin: 18px 0px;"> <i class="fas fa-refresh"></i>  Reset </button>
                                            </div>
                                            <div class="col-md-1">
                                            <button class="btn btn-info search filterBtn" style="width: 100%;padding: 7px;margin: 18px 0px;"> Filter </button>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- <div class="row">
                                        <div class="col-md-3 form-group mb-1">
                                            <label for="picker1"> Customer </label>
                                            <select class="form-control customer_list" name="customer" id="customer">
                                            <option>-Select-</option>
                                            @foreach($customers as $customer)
                                                <option value="{{ $customer->id }}"> {{ $customer->first_name.'-'.$customer->company_name}} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2 form-group mb-1">
                                            <label for="from_date"> From date </label>
                                            <input class="form-control from_date commonDatePicker" id="from_date" type="text" placeholder="From date">
                                        </div>
                                        <div class="col-md-2 form-group mb-1">
                                            <label for="to_date"> To date </label>
                                            <input class="form-control to_date commonDatePicker" id="to_date" type="text" placeholder="To date">
                                        </div>
                                        <div class="col-md-3 form-group mb-1">
                                            <label for="picker1"> Candidate </label>
                                            <select class="form-control candidate_list" name="candidate" id="candidate_list">
                                            <option value="">-Select-</option>
                                            
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <button class="btn btn-primary search filterBtn" style="width: 100%;padding: 7px;margin: 18px 0px;"> Filter </button>
                                        </div>
                                    </div> --}}
                                    <!-- export data -->
                                    <div class="row">
                                        
                                        <div class="col-md-4 form-group mb-3">
                                            <label for="picker1"> Check </label>
                                            <select class="form-control check" name="check[]" id="check" data-actions-box="true" data-selected-text-format="count>1" multiple>
                                                {{-- <option value="">-Select-</option> --}}
                                                @foreach($services as $service)
                                                    <option value="{{ $service->id}}">{{ $service->name  }}</option>   
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3 form-group mt-4">
                                            <a class="btn-link" id="exportExcel" href="javascript:;"> <i class="fa fa-file-excel-o"></i> Export Excel</a> 
                                            <p style="margin-bottom:2px;" class="load_container text-danger" id="loading"></p>
                                        </div>
                                        <div class="col-md-2 form-group mb-4" >
                                            <label for="picker1" ><strong>Numbers of Rows:-</strong>  </label>
                                        
                                            <select class="form-control rows"  id="rows">
                                            <option value="">-Select-</option>
                                            <option value="25">25</option>   
                                            <option value="50">50</option> 
                                            <option value="100">100</option> 
                                            <option value="150">150</option> 
                                            <option value="200">200</option> 
                                            <option value="250">250</option> 
                                            <option value="300">300</option> 
                                            <option value="350">350</option> 
                                            <option value="400">400</option> 
                                            <option value="450">450</option> 
                                            <option value="500">500</option> 
                                            </select>
                                        </div>
                                        <div class="col-md-3 form-group mb-3">
                                            <label for="picker1"> Priority </label>
                                            <select class="form-control check_p"  id="check_p">
                                                <option value="">-Select-</option>
                                                {{-- <option value="low">Low</option>    --}}
                                                <option value="normal">Normal</option>   
                                                <option value="high">High</option>   
                                            
                                            </select>
                                        </div>
                                        
                                    </div>
                                
                                    <!-- ./export data --> 
                                    <input type="hidden" name="active_case" id="active_case" value={{$filled}}>
                                    <input type="hidden" name="insuffs" id="insuffs" value={{$insuffs}}>
                                    <input type="hidden" name="service" id="service" value={{$service_s}}>
                                    <input type="hidden" name="sendto" id="sendto" value={{$send_to}}>
                                    <input type="hidden" name="jafstatus" id="jafstatus" value={{$jafstatus}}>
                                    <input type="hidden" name="jafstatus1" id="jafstatus1" value={{$pending}}>
                                    <input type="hidden" name="jafstatus2" id="jafstatus2" value={{$draft}}>
                                    <input type="hidden" name="verification_status" id="verification_status" value={{$verification_status}}>
                                    <input type="hidden" name="verify_status" id="verify_status" value={{$verify_status}}>
                                    <input type="hidden" name="candidates_id" id="candidates_id" value={{$candidates_id}}>


                                    <!-- data  -->
                                    <div id="candidatesResult">
                                        @include('admin.candidates.completed-report.ajax')
                                    </div> 
                                    <!--  -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
   </div>
</div>
<div class="modal" id="hold_resume_modal">
    <div class="modal-dialog modal-lg">
       <div class="modal-content">
          <!-- Modal Header -->
          <div class="modal-header">
             <h4 class="modal-title" id="hold_resume_title"></h4>
             <button type="button" class="close btn-disable" style="top: 12px;!important; color: red;" data-dismiss="modal"><small>×</small></button>
          </div>
          <!-- Modal body -->
          <form method="post" action="#" id="hold_resume_frm" enctype="multipart/form-data">
          @csrf
            <input type="hidden" name="candidate_id" class="candidate_id" id="candidate_id">
            <input type="hidden" name="business_id" class="business_id" id="business_id">
             <div class="modal-body">
                <div class="row">
                   <div class="col-12">
                      <div class="form-group">
                         <label for="label_name"> Candidate : </label>
                         <span class="candidate_name"></span>
                     </div>
                   </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label for="label_name"> Comments: <span class="text-danger">*</span></label>
                            <textarea id="comment" name="comments" class="form-control comments" placeholder=""></textarea>
                            <p style="margin-bottom: 2px;" class="text-danger error-container error-comments" id="error-comments"></p> 
                        </div>
                    </div>
                    <div class="col-12 pt-2">
                        <h5 class="text-muted">Hold & Resume Details:-</h5>
                        <p class="pb-border"></p>
                        <div class="action-data">
 
                        </div>
                    </div>
                </div>
             </div>
             <!-- Modal footer -->
             <div class="modal-footer">
                <button type="submit" class="btn btn-info btn-disable submit_btn">Submit </button>
                <button type="button" class="btn btn-danger btn-disable" data-dismiss="modal">Close</button>
             </div>
          </form>
       </div>
    </div>
</div>  

   <!-- close case Modal -->
   <div class="modal" id="close_case_modal">
    <div class="modal-dialog modal-lg">
       <div class="modal-content">
          <!-- Modal Header -->
          <div class="modal-header">
             <h4 class="modal-title" id="close_case_title"></h4>
             <button type="button" class="close btn-disable" style="top: 12px;!important; color: red;" data-dismiss="modal"><small>×</small></button>
          </div>
          <!-- Modal body -->
          <form method="post" action="#" id="close_case_frm" enctype="multipart/form-data">
             @csrf
            <input type="hidden" name="candidate_id" class="candidate_id" id="candidate_id">
            <input type="hidden" name="business_id" class="business_id" id="business_id">
             <div class="modal-body">
                <div class="row">
                   <div class="col-12">
                      <div class="form-group">
                         <label for="label_name"> Candidate : </label>
                         <span class="candidate_name"></span>
                     </div>
                   </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label for="label_name"> Comments: <span class="text-danger">*</span></label>
                            <textarea id="comment" name="comments" class="form-control comments" placeholder=""></textarea>
                            <p style="margin-bottom: 2px;" class="text-danger error-container error-comments" id="error-comments"></p> 
                        </div>
                    </div>
                    <div class="col-12 pt-2">
                    <label for="label_name"> File:</label>
                        <input type="file" name="attachment[]" multiple>
                        <!-- <h5 class="text-muted">Hold & Resume Details:-</h5>
                        <p class="pb-border"></p>
                        <div class="action-data">
 
                        </div> -->
                    </div>
                </div>
             </div>
             <!-- Modal footer -->
             <div class="modal-footer">
                <button type="submit" class="btn btn-info btn-disable submit_btn">Submit </button>
                <button type="button" class="btn btn-danger btn-disable" data-dismiss="modal">Close</button>
             </div>
          </form>
       </div>
    </div>
</div>  
<!-- Script -->
<script type="text/javascript">

$(document).ready(function(){
    $(".select").select2();
    // $(".select2").select2();
    $('.filter0search').click(function(){
            $('.search-drop-field').toggle();
    });
    // Advance Aadhar Check
    // });
    
    $('.check').selectpicker();



    $(document).on('click', '.resetBtn' ,function(){

        $("input[type=text], textarea").val("");
        //   $('.customer_list').val('');
        //    $('.candidate').val('');
        //    $('.user_list').val('');
        $('#candidate').val(null).trigger('change');
        $('#customer').val(null).trigger('change');
        // $('#user').val(null).trigger('change');
        $('#remain').val('');
        $('#active_case').val('');
        $('#insuff_raised').val('');
        $('#service_name').val('');
        $('.email').val('');
        var uriNum = location.hash;
        pageNumber = uriNum.replace("#","");
        // alert(pageNumber);
        getData(pageNumber);
    });
    //
    $(document).on('change','.from_date',function() {

        var from = $('.from_date').datepicker('getDate');
        var to_date   = $('.to_date').datepicker('getDate');

        if($('.to_date').val() !=""){
        if (from > to_date) {
        alert ("Please select appropriate date range!");
        $('.from_date').val("");
        $('.to_date').val("");
        
        }
        }

    });
    //
    $(document).on('change','.to_date',function() {

        var to_date = $('.to_date').datepicker('getDate');
        var from   = $('.from_date').datepicker('getDate');
            if($('.from_date').val() !=""){
            if (from > to_date) {
            alert ("Please select appropriate date range!");
            $('.from_date').val("");
            $('.to_date').val("");
            
            }
            }

    });
    //
    var uriNum = location.hash;
    pageNumber = uriNum.replace("#", "");
    // alert(pageNumber);
    getData(pageNumber);
    //
    $('.customer_list').on('select2:select', function (e){
        var data = e.params.data.id;
        //loader
        $("#overlay").fadeIn(300);　
        getData(0);
        setData();
        event.preventDefault();
    });

    // filterBtn
    $(document).on('change','.customer_list, .candidate_list, .from_date, .to_date, .mob,.ref,.email,#remain,#active_case,#candidates_id,#insuff_raised,.search,#rows,#service_name', function (e){    
        $("#overlay").fadeIn(300);　
        getData(0);
        e.preventDefault();
    });

    $(document).on('click','.filterBtn', function (e){    
        $("#overlay").fadeIn(300);　
        getData(0);
        e.preventDefault();
    });
 
    //
    $(document).on('change','.customer_list',function(e) {
            e.preventDefault();
            $('.candidate_list').empty();
            $('.candidate_list').append("<option value=''>-Select-</option>");
            var customer_id = $('.customer_list option:selected').val();
            $.ajax({
                type:"POST",
                url: "{{ url('/customers/candidates/getlist') }}",
                data: {"_token": "{{ csrf_token() }}",'customer_id':customer_id},      
                success: function (response) {
                    console.log(response);
                    if(response.success==true  ) {   
                        $.each(response.data, function (i, item) {
                        $(".candidate_list").append("<option value='"+item.id+"'> "+ item.name +" ("+ item.display_id+")</option>");
                        });
                    }
                    //show the form validates error
                    if(response.success==false ) {                              
                        for (control in response.errors) {   
                            $('#error-' + control).html(response.errors[control]);
                        }
                    }
                },
                error: function (xhr, textStatus, errorThrown) {
                    // alert("Error: " + errorThrown);
                }
            });
        return false;
    });
    $(document).on('click','.closeCase',function(event){
        var candidate_id = $(this).attr('data-id');
        $('.form-control').removeClass('is-invalid');
            $('.error-container').html('');
            $('.btn-disable').attr('disabled',false);

            $('#close_case_title').html('Close');
            $('#close_case_modal').modal({
                backdrop: 'static',
                keyboard: false
            });

            $.ajax({
                type:'GET',
                url: "{{route('/candidates/closecase')}}",
                data: {"_token": "{{ csrf_token() }}",'candidate_id':candidate_id},       
                success: function (data) {        
                    $("#close_case_frm")[0].reset();
                    if(data !='null')
                    { 
                        // alert(data.result.additional_charge_notes);
                        //check if primary data 
                        $('#close_case_frm').attr('action',"{{route('/candidates/closecase')}}");
                        $('#close_case_frm').find('.candidate_id').val(candidate_id);
                        $('#close_case_frm').find('.business_id').val(business_id);
                        $('#close_case_frm').find('.candidate_name').html(data.candidate_name);
                        $('#close_case_frm').find('.action-data').html(data.html);
                        $('#close_case_frm').find('.comments').html('');
                    }
                },
                error: function (xhr, textStatus, errorThrown) {
                        alert("Error: " + errorThrown);
                }
            });
    });
    $(document).on('submit','form#close_case_frm',function(event){
        $("#overlay").fadeIn(300);　
            event.preventDefault();
            var form = $(this);
            var data = new FormData($(this)[0]);
            var url = form.attr("action");
            var $btn = $(this);
            var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> Loading...';
            $('.error-container').html('');
            $('.form-control').removeClass('is-invalid');
            $('.btn-disable').attr('disabled',true);
            if ($('.submit_btn').html() !== loadingText) {
                $('.submit_btn').html(loadingText);
            }
            $.ajax({
               type: form.attr('method'),
               url: url,
               data: data,
               cache: false,
               contentType: false,
               processData: false,        
               success: function (data) {        
                     // console.log(data);
                     window.setTimeout(function(){
                        $('.btn-disable').attr('disabled',false);
                        $('.submit_btn').html('Submit');
                     },2000);
                     if (data.success==false) {
                            //$("#overlay").fadeOut(300);
                            for (control in data.errors) {
                                $('#close_case_frm').find('textarea[name='+control+']').addClass('is-invalid');
                                $('#close_case_frm').find('.error-' + control).html(data.errors[control]);
                            }
                    } 
                    
                    if (data.success) {
                        if(data.message=='Close')
                        {
                            toastr.success("Candidate has been Close Successfully");
                        }
                        if(data.message=='Closecase')
                        {
                            toastr.success("Candidate has been Close Successfully");
                        }
                        // if(data.message=='removed')
                        // {
                        //     toastr.success("Candidate has been resumed Successfully");
                        // }
                        window.setTimeout(function(){
                            location.reload();
                        },2000);
                        
                    }
                     
               },
               error: function (xhr, textStatus, errorThrown) {
                    //  alert("Error: " + errorThrown);
               }
            });

    })
    // 
    $(document).on('click', '.pagination a,.searchBtn',function(event){
        //loader
        $("#overlay").fadeIn(300);　
        $('li').removeClass('active');
        $(this).parent('li').addClass('active');
        event.preventDefault();
        var myurl = $(this).attr('href');
        var page  = $(this).attr('href').split('page=')[1];
        getData(page);
    });


    // print visits  
    // $(document).on('click','#exportExcel',function(){
        // setData();
        // var _this=$(this);
        // var check = $(".check option:selected").val();
        // var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> Under Processing...';
        // $('p.load_container').html("");
        // var candidate_arr = [];
        // var i = 0;
        
        // $('.check option:selected').each(function () {
        //     candidate_arr[i++] = $(this).val();
        // });

        // alert(candidate_id.length);

        

        // if(check!=''){
                
        //     //  
        //     _this.addClass('disabled-link');
        //     $('#loading').html(loadingText);
        //         var user_id     =    $(".customer_list").val();                
        //         var check       =    $(".check option:selected").val();
        //         var from_date   =    $(".from_date").val(); 
        //         var to_date     =    $(".to_date").val();    
        //         var candidate_id=    $(".candidate_list option:selected").val();                            

        //         $.ajax(
        //         {
                    
        //             url: "{{ url('/') }}"+'/candidates/setData/?customer_id='+user_id+'&from_date='+from_date+'&to_date='+to_date+'&check_id='+check+'&candidate_id='+candidate_id,
        //             type: "get",
        //             datatype: "html",

        //         })
        //         .done(function(data)
        //         {
        //             window.setTimeout(function(){
        //                 _this.removeClass('disabled-link');
        //                 $('#loading').html("");
        //                 // _this.html('<i class="far fa-file-archive"></i> Download Zip');
        //             },2000);
                    
        //             console.log(data);
        //             var path = "{{ route('/jaf-export')}}";
        //                 window.open(path);
        //         })
        //         .fail(function(jqXHR, ajaxOptions, thrownError)
        //         {
        //             //alert('No response from server');
        //         });
        //     //
        
        // }else{
        //     alert('Please select a check to export! ');
        //     }
    // });

    // $(document).on('click','#exportExcel',function(){
    //     var _this=$(this);
    //     var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> Under Processing...';
    //     $('p.load_container').html("");

    //     var check=0;  
    //     var select = document.getElementById("check");

    //     var export_service_id=[];

    //     var export_candidate_id=[];

    //     // var i=0;
    //     // $('.check option:selected').each(function () {
    //     //     export_service_id[i++] = $(this).val();
    //     // });

    //     // alert(export_service_id);

    //     // var j=0;
    //     // $('.priority:checked').each(function () {
    //     //     export_candidate_id[j++] = $(this).val();
    //     // });

    //     // alert(export_candidate_id);
        
    //     for(var i = 0; i < select.options.length; i++){
    //         if(select.options[i].selected){
    //             check++;
    //         }
    //     }
        
    //     if(check<=0)
    //     {
    //         alert("Please Select the Services first");
    //     }
    //     else
    //     {
    //         var candidate=document.querySelectorAll('.priority:checked').length;

    //         if(candidate<=0)
    //         {
    //             alert("Please Select the Candidate first");
    //         }
    //         else
    //         {
    //             var i=0;
    //             $('.check option:selected').each(function () {
    //                 export_service_id[i++] = $(this).val();
    //             });

    //             var j=0;
    //             $('.priority:checked').each(function () {
    //                 export_candidate_id[j++] = $(this).val();
    //             });

    //             _this.addClass('disabled-link');
    //             $('#loading').html(loadingText);
    //             var user_id     =    $(".customer_list").val();                
    //             var from_date   =    $(".from_date").val(); 
    //             var to_date     =    $(".to_date").val();  

    //             $.ajax(
    //             {
                    
    //                 url: "{{ url('/') }}"+'/candidates/setData/',
    //                 type: "get",
    //                 data: {'customer_id':user_id,'from_date':from_date,'to_date':to_date,'export_service_id':export_service_id,'export_candidate_id':export_candidate_id},
    //                 datatype: "html",

    //             })
    //             .done(function(data)
    //             {
    //                 window.setTimeout(function(){
    //                     _this.removeClass('disabled-link');
    //                     $('#loading').html("");
    //                     // _this.html('<i class="far fa-file-archive"></i> Download Zip');
    //                 },2000);
                    
    //                 // console.log(data);
    //                 var path = "{{ route('/jaf-export')}}";
    //                     window.open(path);
    //             })
    //             .fail(function(jqXHR, ajaxOptions, thrownError)
    //             {
    //                 //alert('No response from server');
    //             });

    //         }
            
    //     }


    // });

    $(document).on('click','#exportExcel',function(){

        var _this=$(this);
        var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> Under Processing...';
        $('p.load_container').html("");

        var check=0;  
        var select = document.getElementById("check");

        var export_service_id=[];

        var export_candidate_id=[];

        for(var i = 0; i < select.options.length; i++){
            if(select.options[i].selected){
                check++;
            }
        }
        
        if(check<=0)
        {
            alert("Please Select the Services first");
        }
        else
        {
            var i=0;
                $('.check option:selected').each(function () {
                    export_service_id[i++] = $(this).val();
                });

                var j=0;
                $('.priority:checked').each(function () {
                    export_candidate_id[j++] = $(this).val();
                });

                _this.addClass('disabled-link');
                $('#loading').html(loadingText);
                var user_id     =    $(".customer_list").val();                
                var from_date   =    $(".from_date").val(); 
                var to_date     =    $(".to_date").val();  

                $.ajax({
                  type:'POST',
                  url: "{{ url('/') }}"+'/jaf-export',
                  data: {"_token" : "{{ csrf_token() }}",'export_service_id':export_service_id,'export_candidate_id':export_candidate_id},        
                  success: function (response) {
                    window.setTimeout(function(){
                        _this.removeClass('disabled-link');
                        $('#loading').html("");
                        // _this.html('<i class="far fa-file-archive"></i> Download Zip');
                    },2000);
                    
                    // console.log(data);
                    // var path = "{{ route('/jaf-export')}}";
                    // window.open(path);
                    
                    if(response.success)
                        window.open(response.url);
                    else
                        $('#loading').html(response.error);
                  },
                  error: function (xhr, textStatus, errorThrown) {
                     // alert("Error: " + errorThrown);
                  }
             });
        }

    });

});

    function getData(page){
        //set data
        var user_id     =    $(".customer_list").val();                
        var check       =    $(".check option:selected").val();
        var type        =    $('#check_p').val();

        var from_date   =    $(".from_date").val(); 
        var to_date     =    $(".to_date").val();      
        var candidate_id=    $(".candidate_list option:selected").val();
        var rows = $("#rows option:selected").val();
        var mob = $('.mob').val();
        var ref = $('.ref').val();
        var email = $('.email').val();  
        var remain = $('#remain').val();   
        var status = 'pending';
        var active_case =  $('#active_case').val();  

        var insuff_raised = $('#insuff_raised').val();    

        var search = $('.search').val();
        var insuff_status = '1';   

        var insuffs = $('#insuffs').val();
        var service = $('#service').val();
        var sendto = $('#sendto').val();
        var jafstatus = $('#jafstatus').val();
        var jafstatus1 = $('#jafstatus1').val();
        var jafstatus2 = $('#jafstatus2').val();
        var insuff = $('#insuff').val();
        var verification_status = $('#verification_status').val();
        var verify_status = $('#verify_status').val();
        var candidates_id = $('#candidates_id').val();
        var service_name = $('#service_name').val();
        // var candidate_arr = [];
        // var i = 0;
        

        // $('.check option:selected').each(function () {
        //     // if($(this).val()!='')
        //     candidate_arr[i++] = $(this).val();
        // });    

            $('#candidatesResult').html("<div style='background-color:#ddd; min-height:450px; line-height:450px; vertical-align:middle; text-align:center'><img alt='' src='"+loaderPath+"' /></div>").fadeIn(300);

            $.ajax(
            {
                url: '?page=' + page+'&customer_id='+user_id+'&status='+status+'&from_date='+from_date+'&to_date='+to_date+'&candidate_id='+candidate_id+'&type='+type+'&check_id='+check+'&mob='+mob+'&ref='+ref+'&email='+email+'&remain='+remain+'&active_case='+active_case+'&insuff_raised='+insuff_raised+'&insuff_status='+insuff_status+'&search='+search+'&insuffs='+insuffs+'&service='+service+'&sendto='+sendto+'&jafstatus='+jafstatus+'&insuff='+insuff+'&verification_status='+verification_status+'&verify_status='+verify_status+'&candidate='+candidates_id+'&jafstatus1='+jafstatus1+'&jafstatus2='+jafstatus2+'&rows='+rows+'&service_name='+service_name,
                type: "get",
                datatype: "html",
            })
            .done(function(data)
            {
                $("#candidatesResult").empty().html(data);
                $("#overlay").fadeOut(300);
                //debug to check page number
                location.hash = page;
            })
            .fail(function(jqXHR, ajaxOptions, thrownError)
            {
                alert('No response from server');

            });

    }

    function setData(){

        var user_id     =    $(".customer_list").val();                
        var check       =    $(".check option:selected").val();

        var from_date   =    $(".from_date").val(); 
        var to_date     =    $(".to_date").val();    
        var candidate_id=    $(".candidate_list option:selected").val();                            
        var rows = $("#rows option:selected").val();
        var mob = $('.mob').val();
        var ref = $('.ref').val();

        var email = $('.email').val(); 

        var remain = $('#remain').val();  

        var active_case =  $('#active_case').val();   

        var insuff_raised = $('#insuff_raised').val();       

        var status = 'pending'; 

        var insuff_status = '1';

        var search = $('.search').val();

        var insuffs = $('#insuffs').val();
        var service = $('#service').val();

        var sendto = $('#sendto').val();
        var jafstatus = $('#jafstatus').val();
        var insuff = $('#insuff').val();
        var verification_status = $('#verification_status').val();
        var verify_status = $('#verify_status').val();
        var candidates_id = $('#candidates_id').val();
        var service_name = $('#service_name').val();
        var jafstatus1 = $('#jafstatus1').val();
        var jafstatus2 = $('#jafstatus2').val();
        // var candidate_arr = [];
        // var i = 0;
        

        // $('.check option:selected').each(function () {
        //     // if($(this).val()!='')
        //     candidate_arr[i++] = $(this).val();
        // });

        // alert(candidate_arr);
        
            $.ajax(
            {
                url: "{{ url('/') }}"+'/candidates/setData/?customer_id='+user_id+'&from_date='+from_date+'&to_date='+to_date+'&check_id='+check+'&candidate_id='+candidate_id+'&mob='+mob+'&ref='+ref+'&email='+email+'&remain='+remain+'&status='+status+'&active_case='+active_case+'&insuff_raised='+insuff_raised+'&insuff_status='+insuff_status+'&search='+search+'&insuffs='+insuffs+'&service='+service+'&sendto='+sendto+'&jafstatus='+jafstatus+'&insuff='+insuff+'&verification_status='+verification_status+'&verify_status='+verify_status+'&candidate='+candidates_id+'&jafstatus1='+jafstatus1+'&jafstatus2='+jafstatus2+'&rows='+rows+'&service_name='+service_name,
                type: "get",
                datatype: "html",
            })
            .done(function(data)
            {
            console.log(data);
            })
            .fail(function(jqXHR, ajaxOptions, thrownError)
            {
                //alert('No response from server');
            });

    }

    //when click on hold button
    $(document).on('click', '.hold', function (event) {
        
        var candidate_id = $(this).attr('data-candidate');
        var business_id = $(this).attr('data-business');
        // if(confirm("Are you sure want to hold this candidate ?")){
        // $.ajax({
        //     type:'GET',
        //     url: "{{route('/candidates/hold')}}",
        //     data: {'candidate_id':candidate_id,'business_id':business_id},        
        //     success: function (response) {        
        //     console.log(response);
            
        //         if (response.status=='ok') {            
                
        //             $('table.candidatesTable tr').find("[data-candidate='" + candidate_id + "']").fadeOut("slow");
                    
        //             $('table.candidatesTable tr').find("[data-cand_id='" + candidate_id + "']").fadeOut("slow");
        //             $('table.candidatesTable tr').find("[data-can_id='" + candidate_id + "']").removeClass("d-none").show();
        //             $('table.candidatesTable tr').find("[data-candidate_id='" + candidate_id + "']").removeClass("d-none").show();

        //             $('table.candidatesTable tr').find("[data-resend_m='" + candidate_id + "']").addClass('d-none').hide();        
        //             $('table.candidatesTable tr').find("[data-resend_m='" + candidate_id + "']").fadeOut("slow");

        //             $('table.candidatesTable tr').find("[data-resend='" + candidate_id + "']").addClass('d-none').hide();        
        //             $('table.candidatesTable tr').find("[data-resend='" + candidate_id + "']").fadeOut("slow");

        //         } else {
                    
        //         }
        //     },
        //     error: function (response) {
        //        console.log(response);
        //     }
        //     // error: function (xhr, textStatus, errorThrown) {
        //     //     alert("Error: " + errorThrown);
        //     // }
        // });

        // }
        // return false;
        
            $('.form-control').removeClass('is-invalid');
            $('.error-container').html('');
            $('.btn-disable').attr('disabled',false);

            $('#hold_resume_title').html('Hold');
            $('#hold_resume_modal').modal({
                backdrop: 'static',
                keyboard: false
            });
         
            $.ajax({
               type:'GET',
               url: "{{route('/candidates/hold')}}",
               data: {'candidate_id':candidate_id,'business_id':business_id},        
               success: function (data) {        
                $("#hold_resume_frm")[0].reset();
                  if(data !='null')
                  { 
                     // alert(data.result.additional_charge_notes);
                     //check if primary data 
                     $('#hold_resume_frm').attr('action',"{{route('/candidates/hold')}}");
                     $('#hold_resume_frm').find('.candidate_id').val(candidate_id);
                     $('#hold_resume_frm').find('.business_id').val(business_id);
                     $('#hold_resume_frm').find('.candidate_name').html(data.candidate_name);
                     $('#hold_resume_frm').find('.action-data').html(data.html);
                     $('#hold_resume_frm').find('.comments').html('');
                  }
               },
               error: function (xhr, textStatus, errorThrown) {
                     alert("Error: " + errorThrown);
               }
            });
        

    });

    //when click on hold button
    $(document).on('click', '.resume', function (event) {
        
        var candidate_id = $(this).attr('data-candidate_id');
        var business_id = $(this).attr('data-business_id');
        // if(confirm("Are you sure want to Resume this candidate ?")){
        // $.ajax({
        //     type:'GET',
        //     url: "{{route('/candidates/resume')}}",
        //     data: {'candidate_id':candidate_id,'business_id':business_id},        
        //     success: function (response) {        
        //     console.log(response);
            
        //         if (response.status=='ok') {            
                
        //             $('table.candidatesTable tr').find("[data-candidate_id='" + candidate_id + "']").fadeOut("slow");
                    
        //             $('table.candidatesTable tr').find("[data-can_id='" + candidate_id + "']").fadeOut("slow");
        //             $('table.candidatesTable tr').find("[data-cand_id='" + candidate_id + "']").removeClass("d-none").show();
        //             $('table.candidatesTable tr').find("[data-candidate='" + candidate_id + "']").removeClass("d-none").show();

        //             $('table.candidatesTable tr').find("[data-resend='" + candidate_id + "']").removeClass("d-none").show();
        //             $('table.candidatesTable tr').find("[data-resend='" + candidate_id + "']").fadeIn("slow");

        //             $('table.candidatesTable tr').find("[data-resend_m='" + candidate_id + "']").removeClass("d-none").show();
        //             $('table.candidatesTable tr').find("[data-resend_m='" + candidate_id + "']").fadeIn("slow");
        //         } else {
                    
        //         }
        //     },
        //     error: function (response) {
        //        console.log(response);
        //     }
        // });

        // }
        // return false;

            $('.form-control').removeClass('is-invalid');
            $('.error-container').html('');
            $('.btn-disable').attr('disabled',false);

            $('#hold_resume_title').html('Resume');
            $('#hold_resume_modal').modal({
                backdrop: 'static',
                keyboard: false
            });
         
            $.ajax({
               type:'GET',
               url: "{{route('/candidates/resume')}}",
               data: {'candidate_id':candidate_id,'business_id':business_id},        
               success: function (data) {        
                $("#hold_resume_frm")[0].reset();
                  if(data !='null')
                  { 
                     // alert(data.result.additional_charge_notes);
                     //check if primary data 
                     $('#hold_resume_frm').attr('action',"{{route('/candidates/resume')}}");
                     $('#hold_resume_frm').find('.candidate_id').val(candidate_id);
                     $('#hold_resume_frm').find('.business_id').val(business_id);
                     $('#hold_resume_frm').find('.candidate_name').html(data.candidate_name);
                     $('#hold_resume_frm').find('.action-data').html(data.html);
                     $('#hold_resume_frm').find('.comments').html('');
                  }
               },
               error: function (xhr, textStatus, errorThrown) {
                     alert("Error: " + errorThrown);
               }
            });

    });

    $(document).on('submit', 'form#hold_resume_frm', function (event) {
         $("#overlay").fadeIn(300);　
            event.preventDefault();
            var form = $(this);
            var data = new FormData($(this)[0]);
            var url = form.attr("action");
            var $btn = $(this);
            var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> Loading...';
            $('.error-container').html('');
            $('.form-control').removeClass('is-invalid');
            $('.btn-disable').attr('disabled',true);
            if ($('.submit_btn').html() !== loadingText) {
                $('.submit_btn').html(loadingText);
            }
            $.ajax({
               type: form.attr('method'),
               url: url,
               data: data,
               cache: false,
               contentType: false,
               processData: false,        
               success: function (data) {        
                     // console.log(data);
                     window.setTimeout(function(){
                        $('.btn-disable').attr('disabled',false);
                        $('.submit_btn').html('Submit');
                     },2000);
                     if (data.success==false) {
                            //$("#overlay").fadeOut(300);
                            for (control in data.errors) {
                                $('#hold_resume_frm').find('textarea[name='+control+']').addClass('is-invalid');
                                $('#hold_resume_frm').find('.error-' + control).html(data.errors[control]);
                            }
                    } 
                    
                    if (data.success) {
                        if(data.message=='Hold')
                        {
                            toastr.success("Candidate has been holded Successfully");
                        }
                        if(data.message=='removed')
                        {
                            toastr.success("Candidate has been resumed Successfully");
                        }
                        window.setTimeout(function(){
                            location.reload();
                        },2000);
                        
                    }
                     
               },
               error: function (xhr, textStatus, errorThrown) {
                    //  alert("Error: " + errorThrown);
               }
            });

        

    });

    //

    $(document).on('click', '.deleteRow', function (event) {
            var _this = $(this);
            var candidate_id = _this.attr('data-id');
            swal({
               // icon: "warning",
               type: "warning",
               title: "Are you sure want to delete?",
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
                        $.ajax({
                            type:'POST',
                            url: "{{route('/candidates/delete')}}",
                            data: {"_token": "{{ csrf_token() }}",'candidate_id':candidate_id},        
                            success: function (response) {        
                            //console.log(response);
                            
                                if (response.status=='ok') {            
                                
                                    $('table.candidatesTable tr').find("[data-id='" + candidate_id + "']").parent().parent().fadeOut("slow");

                                    // $('table.candidatesTable tbody').find("[candidate-d_id='" + candidate_id + "']").fadeOut("slow");

                                } else {
                                    
                                }
                            },
                            error: function (response) {
                                console.log(response);
                            }
                            // error: function (xhr, textStatus, errorThrown) {
                            //     alert("Error: " + errorThrown);
                            // }
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

    $(document).on('click', '.deletePermRow', function (event) {
        var _this = $(this);
        var candidate_id = _this.attr('data-id');
        // if(confirm("Are you sure want to delete?")){
        //     $.ajax({
        //         type:'GET',
        //         url: "{{route('/candidates/delete')}}",
        //         data: {'candidate_id':candidate_id},        
        //         success: function (response) {        
        //         //console.log(response);
                
        //             if (response.status=='ok') {            
                    
        //                 $('table.candidatesTable tr').find("[data-id='" + candidate_id + "']").parent().parent().fadeOut("slow");

        //                 // $('table.candidatesTable tbody').find("[candidate-d_id='" + candidate_id + "']").fadeOut("slow");

        //             } else {
                        
        //             }
        //         },
        //         error: function (response) {
        //             console.log(response);
        //         }
        //         // error: function (xhr, textStatus, errorThrown) {
        //         //     alert("Error: " + errorThrown);
        //         // }
        //     });

        // }
        // return false;

        swal({
            // icon: "warning",
            type: "warning",
            title: "Are you sure want to delete?",
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
                    $.ajax({
                        type:'POST',
                        url: "{{route('/candidates/delete/permanent')}}",
                        data: {"_token": "{{ csrf_token() }}",'candidate_id':candidate_id},        
                        success: function (response) {        
                        //console.log(response);
                        
                            if (response.status=='ok') {            
                            
                                $('table.candidatesTable tr').find("[data-id='" + candidate_id + "']").parent().parent().fadeOut("slow");

                                // $('table.candidatesTable tbody').find("[candidate-d_id='" + candidate_id + "']").fadeOut("slow");

                            } else {
                                
                            }
                        },
                        error: function (response) {
                            console.log(response);
                        }
                        // error: function (xhr, textStatus, errorThrown) {
                        //     alert("Error: " + errorThrown);
                        // }
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

    $(document).on('change','.check_p',function(){
    
        var candidate_id = [];
        var i = 0;

        var type= $('#check_p').val();

        $('.priority:checked').each(function () {
            candidate_id[i++] = $(this).val();
        });

        var count = candidate_id.length;

        if(count>0)
        {
            $.ajax({
                type:"POST",
                url: "{{ url('/candidates/updateCandidate') }}",
                data:{"_token": "{{ csrf_token() }}",'candidate_id':candidate_id,'type':type},      
                success: function (response) {
                    
                    location.reload();

                },
                error: function (xhr, textStatus, errorThrown) {
                    
                }
            });  
        }

    });

      //when click on resendmail button
    $(document).on('click', '.resendMail', function (event) {
        
        // var customer_id = $(this).attr('data-customer_id');
        var _this =$(this);
        var candidate_id=$(this).attr('data-id');
        var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> Sending...';
        _this.addClass('disabled-link');
        if (_this.html() !== loadingText) {
            _this.html(loadingText);
        }

        $.ajax({
            type:'GET',
            url: "{{route('/candidates/resend_mail')}}",
            data: {'candidate_id':candidate_id},        
            success: function (response) {        
            console.log(response);
                window.setTimeout(function(){
                    _this.removeClass('disabled-link');
                    _this.html('<i class="far fa-envelope"></i> Re-send Mail');
                },2000);
                if (response.status=='ok') {            
                    var name=response.name;
                    toastr.success("Mail Sent Succesfully to "+name);
                } 
                else {
                    toastr.error("Something Went Wrong !");
                }
            },
            error: function (response) {
               console.log(response);
            }
            // error: function (xhr, textStatus, errorThrown) {
            //     alert("Error: " + errorThrown);
            // }
        });

        // }
        return false;

    }); 

    


</script>
<!--  -->

@endsection
