@extends('layouts.app')

@section('content')
<style>
    .disabled-link{
      pointer-events: none;
    }
  </style>
<div class="main-content-wrap sidenav-open d-flex flex-column">
    <!-- ============ Body content start ============= -->
  
    <div class="main-content">
  
    <div class="card-body" style="padding:0px">
    
    <div class="col-sm-12 ">
      <div class="row">
      <div class="col-sm-3">
   </div>
   <div class="col-sm-6 card">
    <h4 class="modal-title" id="serv_name">{{$service_name}}-1</h4>
    <form method="post" action="{{url('/candidates/jaf-clear-insuff')}}" enctype="multipart/form-data" id="clear_insuff_form">
    @csrf
        <div class="row justify-content-center">
           <input type="hidden" name="jaf_id" id="jaf_id" value="{{$jaf_id}}">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="label_name"> Comments: <span class="text-danger">*</span></label>
                    <textarea id="comments" name="comments" class="form-control comments" placeholder=""></textarea>
                    <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-comments"></p> 
                </div>
            </div>
         </div>

         <div class="row justify-content-center">
            <div class="col-sm-6">
            <div class="form-group">
                  <label for="label_name"> Attachments: <i class="fa fa-info-circle tool" data-toggle="tooltip" data-original-title="Only jpeg,png,jpg,pdf are accepted "></i> </label>
                  <input type="file" name="attachment[]" id="attachments" multiple class="form-control attachments">
                  <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-attachments"></p>  
               </div>
            </div>
         </div>
         <div class="row justify-content-center ">
            <div class="col-sm-6 justify-content-center ">
            <div class="form-group">
            <button type="submit" class="btn btn-dark clear-submit ">Submit </button>
            </div>
            </div>
         </div>
   </div>
   <div class="col-sm-3 ">
   </div>
   </div>
   </div>
   </form>
   </div>
        
   
      
    </div>
   </div>
  
<script type="text/javascript">
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
                            //   location.reload(); 
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
        </script>
@endsection
