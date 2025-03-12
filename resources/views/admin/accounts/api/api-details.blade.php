@extends('layouts.admin')
@section('content')
<div class="main-content-wrap sidenav-open d-flex flex-column">
   <!-- ============ Body content start ============= -->
   <div class="main-content">

         <!-- ============Breadcrumb ============= -->
   <div class="row">
      <div class="col-sm-11">
         <ul class="breadcrumb">
         <li>
         <a href="{{ url('/home') }}">Dashboard</a>
         </li>
         <li>
            <a href="{{ url('/settings/general') }}">Accounts</a>
        </li>
         <li>
            <a href="{{ url('/api-usage') }}">Instant Verification</a>
        </li>
         <li>Details</li>
         </ul>
      </div>
      <!-- ============Back Button ============= -->
      <div class="col-sm-1 back-arrow">
         <div class="text-right">
            <a href="{{ url()->previous() }}"> <i class="fas fa-arrow-circle-left fa-2x"></i></a>
         </div>
      </div>
   </div>   
      {{-- <div class="row">
         <div class="page-header ">
            <div class=" align-items-center">
               <div class="col">
                  <h3 class="page-title">Accounts/API </h3>
               </div>
            </div>
         </div>
      </div> --}}
      <div class="row">
         
            <div class="col-md-3 content-container">
                  <!-- left-sidebar -->
                  @include('admin.accounts.left-sidebar') 
               </div>
                  <!-- start right sec -->
                  <div class="col-md-9 content-wrapper" style="background:#fff">
                     <div class="formCover" style="height: 100vh;">
                        <!-- section -->
                        <section>
                           <div class="col-sm-12 pt-2">
                              
                                 <!-- row -->
                                 <div class="row">
                                    <div class="col-md-6">
                                       <h4 class="card-title mb-1 mt-3">{{$service_d->name}}</h4>
                                       <p class="pb-border">  Usage Details </p>
                                    </div>
                                    <div class="col-md-6 mt-3 text-right">
                                       <!-- <a href="" class="mt-3 btn btn-sm btn-primary">Payment Method</a> -->
                                       <div class="btn-group" style="float:right">     
                                            <a href="#" class="filter0search"><i class="fa fa-filter"></i></a> 
                                       </div>
                                    </div>
                                 </div>
                                 <div class="row">
                                    <div class="col-md-12">
                                       <button class="btn btn-outline-info downloadDetails float-right" data-service="{{base64_encode($service_d->id)}}" title="Download"><i class="fas fa-download"></i></button>
                                    </div>
                                 </div>
                                 <div class="search-drop-field" id="search-drop">
                                    <div class="row">
                                        <div class="col-md-3 form-group mb-1">
                                            <label> From date </label>
                                            <input class="form-control from_date commonDatePicker" type="text" placeholder="From date">
                                        </div>
                                        <div class="col-md-3 form-group mb-1">
                                            <label> To date </label>
                                            <input class="form-control to_date commonDatePicker" type="text" placeholder="To date">
                                        </div>
                                        <div class="col-md-3 form-group mb-1 level_selector">
                                            <label>Duration</label><br>
                                            <select class="form-control duration" name="duration" id="duration">
                                                <option value=""> All </option>
                                                <option value="{{base64_encode('seven')}}" selected>Last 7 days</option>
                                            </select>
                                            {{-- <input class="form-control candidate_list" type="text" placeholder="name"> --}}
                                        </div>
                                       <div class="col-md-2">
                                            <button class="btn btn-info search filterBtn" style="width: 100%;padding: 7px;margin: 18px 0px;"> Filter </button>
                                       </div>
                                   </div>
                                 </div>
                                 <div class="row">
                                    <div class="col-md-12">
                                       <div id="candidatesResult">
                                          
                                          @include('admin.accounts.api.api-details_ajax')  
                                        </div>
                                    </div>
                                 </div>
                                 <!-- ./billing detail -->
                                 
                           </div>
                        </section>
                        <!-- ./section -->
                        <!--  -->
                        <!-- ./section -->
                     </div>
                  </div>
                  <!-- end right sec -->
               
        
      </div>
   </div>
</div>

<div class="modal" id="download_detail_api">
   <div class="modal-dialog">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            <h4 class="modal-title">Download API Details</h4>
            <button type="button" class="close btn-disable" data-dismiss="modal">&times;</button>
         </div>
         <!-- Modal body -->
         <form method="post" action="{{url('/api-usage/details/download')}}" id="downloaddetailapiFrm">
         @csrf
           <input type="hidden" name="service_id" id="service_id">
           <input type="hidden" name="from_date" id="from_date">
           <input type="hidden" name="to_date" id="to_date">
            <div class="modal-body">
            <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-all"> </p> 
               <div class="form-group">
                     <label for="label_name">Select Type : </label>
                     <select class="form-control type" name="type" id="type">
                        <option value="">--Select--</option>
                        <option value="excel">Excel</option>
                        <option value="pdf">PDF</option>
                     </select>
                     <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-type"></p> 
               </div>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
               <button type="submit" class="btn btn-info download_btn btn-disable">Submit </button>
               <button type="button" class="btn btn-danger btn-disable" data-dismiss="modal">Close</button>
            </div>
         </form>
      </div>
   </div>
</div>

@stack('scripts')
<script type="text/javascript">
   //
   $(document).ready(function() {

        $(".select").select2();
      $('.filter0search').click(function(){
         $('.search-drop-field').toggle();
      });

      var uriNum = location.hash;
      pageNumber = uriNum.replace("#", "");
      // alert(pageNumber);
      getData(pageNumber);

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

        $(document).on('change','.from_date, .to_date,.duration', function (e){    
            $("#overlay").fadeIn(300);　
            getData(0);
            e.preventDefault();
        });

      $(document).on('click','.filterBtn', function (e){    
        $("#overlay").fadeIn(300);　
        getData(0);
        e.preventDefault();
      });

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


      $(document).on('click','.downloadDetails',function(){
         var service_id=$(this).attr('data-service');
         var from_date   =    $(".from_date").val();
         var to_date     =    $(".to_date").val();

         $('#from_date').val(from_date);
         $('#to_date').val(to_date);
         $('#service_id').val(service_id);

         $('#download_detail_api').modal({
               backdrop: 'static',
               keyboard: false
         });
      });

      $(document).on('submit', 'form#downloaddetailapiFrm', function (event) {
         event.preventDefault();
         //clearing the error msg
         $('p.error-container').html("");
         $('.form-control').removeClass('border-danger');
         var form = $(this);
         var data = new FormData($(this)[0]);
         var url = form.attr("action");
         var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> Loading...';
         $('.btn-disable').attr('disabled',true);
         if ($('.download_btn').html() !== loadingText) {
            $('.download_btn').html(loadingText);
         }
         $.ajax({
            type: form.attr('method'),
            url: url,
            data: data,
            cache: false,
            contentType: false,
            processData: false,      
            success: function (response) 
            {
               window.setTimeout(() => {
                  $('.btn-disable').attr('disabled',false);
                  $('.download_btn').html('Submit');
               }, 2000);
               //show the form validates error
               if(response.success==false ) {                              
                  for (control in response.errors) {  
                     $('.'+control).addClass('border-danger'); 
                     $('#error-' + control).html(response.errors[control]);
                  }
               }
               if(response.success==true)
               {
                  var url = response.url;
                  var arr = url.split("/");
                  var fileName = arr[arr.length - 1];
                  var a = $("<a />");
                     a.attr('id','download_lnk_btn');
                     a.attr("download", fileName);
                     a.attr("href", url);
                     // $("body").append(a);
                     a[0].click();
                     $('#download_lnk_btn').remove();
                           
                  $('#download_detail_api').modal('hide');
               }
            },
            error: function (xhr, textStatus, errorThrown) {
                  // alert("Error: " + errorThrown);
            }
         });
         return false;
      });
   
   });
   
        function getData(page){
            //set data
            // var user_id     =    $(".customer_list").val();                
            //  var service_id     =    $(".service_list").val();                

              var from_date   =    $(".from_date").val(); 
              var to_date     =    $(".to_date").val();  

              var date=$(".duration").val();    

            $('#candidatesResult').html("<div style='background-color:#ddd; min-height:450px; line-height:450px; vertical-align:middle; text-align:center'><img alt='' src='"+loaderPath+"' /></div>").fadeIn(300);

            $.ajax(
            {
                url: '?page=' + page+'&from_date='+from_date+'&to_date='+to_date+'&date='+date,
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

        //  var user_id     =    $(".customer_list").val();                
         //   var check       =    $(".check option:selected").val();

           var from_date   =    $(".from_date").val(); 
           var to_date     =    $(".to_date").val();  
           var date= $(".duration").val();  
        //  var service_id     =    $(".service_list").val();         
               $.ajax(
               {
                  url: "{{ url('/') }}"+'/candidates/setData/?from_date='+from_date+'&to_date='+to_date+'&date='+date,
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
</script>  
@endsection
