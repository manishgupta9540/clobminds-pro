@extends('layouts.superadmin')
@section('content')

<div class="main-content-wrap sidenav-open d-flex flex-column">
   <!-- ============ Body content start ============= -->
   <div class="main-content">
      <div class="row">
      <div class="col-md-12">

        <div class="row">
            <div class="col-sm-11">
                <ul class="breadcrumb">
                <li>
                    <a href="{{ url('/app/home') }}">Dashboard</a>
                </li>
                <li>
                    <a href="{{ url('/app/guest/order') }}">Guest Orders</a>
                </li>
                <li>Details</li>
                </ul>
            </div>
            <!-- ============Back Button ============= -->
            <div class="col-sm-1 back-arrow">
                <div class="text-right">
                <a href="{{ url()->previous() }}"><i class="fas fa-arrow-circle-left fa-2x"></i></a>
                </div>
            </div>
        </div>

         @if ($message = Session::get('success'))
               <div class="alert alert-success">
               <strong>{{ $message }}</strong> 
               </div>
         @endif
         <div class="card text-left">
            <div class="card-body">
                {{-- @include('superadmin.guest.menu') --}}
               <div class="row">
                  <div class="col-md-8">
                     <h4 class="card-title mb-1"> Guest Orders Details</h4>
                     <p> List of all Guest Orders</p>
                  </div>
                  <div class="col-md-4">
                     <div class="btn-group" style="float:right">
                        <span><a href="#" class="filter0search"><i class="fa fa-filter"></i></a></span>
                     </div>
                  </div>
               </div>
               <div class="search-drop-field pb-3" id="search-drop">
                    <div class="row">
                        <div class="col-md-2 form-group mb-1">
                            <label> From date </label>
                            <input class="form-control from_date commonDatepicker" type="text" placeholder="From date">
                        </div>
                        <div class="col-md-2 form-group mb-1">
                            <label> To date </label>
                            <input class="form-control to_date commonDatepicker" type="text" placeholder="To date">
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary search filterBtn" style="width: 100%;padding: 7px;margin: 18px 0px;"> Filter </button>
                        </div>
                    </div>
               </div>
                <div id="candidatesResult">
                   @include('superadmin.guest.order_details_ajax')
                </div>
            </div>
         </div>
      </div>
   </div>
   </div>
</div>

<div class="modal" id="order_data_modal">
    <div class="modal-dialog">
       <div class="modal-content">
          <!-- Modal Header -->
          
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

   $(document).on('click','.filterBtn', function (e){    
        $("#overlay").fadeIn(300);　
        getData(0);
        e.preventDefault();
   });

    $(document).on('change','.from_date, .to_date', function (e){    
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

    $(document).on('click','.detailBtn',function(){
        $('#g_id').val("");
        $('#service_name').text('Verification - '+"");
        var g_id = $(this).attr('data-id');
        // var notes= $(this).attr('data-notes');
        // alert(servi_name);

        $.ajax({
            type:'POST',
            url: "{{url('/app/guest/order/details/data')}}",
            data: {"_token": "{{ csrf_token() }}",'g_id':g_id},        
            success: function (response) {        
            console.log(response);
            $('.modal-content').html(response.modal);
            $('#g_id').val(g_id);
            $('#service_name').text('Verification - '+response.data.name);
            $('#order_details').html(response.form);

            
            $('.modal-footer').html(`<button type="button" class="btn btn-danger closeraisemdl" data-dismiss="modal">Close</button>`);
            
            
            $('#order_data_modal').modal({
                backdrop: 'static',
                keyboard: false
            });
            // if (response.status=='ok') {            
            
            
            // } else {

            //    alert('No data found');

            // }
            },
            error: function (xhr, textStatus, errorThrown) {
            //  alert("Error: " + errorThrown);
            }
        });

    });
    
});
    function getData(page){
        //set data
        var status     =    $(".status_list").val();                

        var from_date   =    $(".from_date").val(); 
        var to_date     =    $(".to_date").val();

          

            $('#candidatesResult').html("<div style='background-color:#ddd; min-height:450px; line-height:450px; vertical-align:middle; text-align:center'><img alt='' src='"+loaderPath+"' /></div>").fadeIn(300);

            $.ajax(
            {
                url: '?page=' + page+'&status='+status+'&from_date='+from_date+'&to_date='+to_date,
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

        var status     =    $(".status_list").val();                
      //   var check       =    $(".check option:selected").val();

        var from_date   =    $(".from_date").val(); 
        var to_date     =    $(".to_date").val();  

         
        
            $.ajax(
            {
                url: "{{ url('/') }}"+'/candidates/setData/?status='+status+'&from_date='+from_date+'&to_date='+to_date,
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
