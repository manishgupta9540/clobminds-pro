@extends('layouts.superadmin')
@section('title', 'Change Password | ')
<style>
    span.show-hide-password {
        position: absolute;
        top: 34px;
        right: 10px;
        font-size: 14px;
        color: #748a9c;
        cursor: pointer;
    }
</style>
@section('content')
               
   <div class="main-content-wrap sidenav-open d-flex flex-column">
   <!-- ============ Body content start ============= -->
   <div class="main-content"> 
        <div class="row">
            <div class="card text-left">
               <div class="card-body" style="height: 100vh;">
                <div class="row">
        
                    <div class="col-md-6 offset-md-3">

                        @if ($message = Session::get('success'))
                            <div class="alert alert-success">
                            <strong>{{ $message }}</strong> 
                            </div>
                        @endif
                        @if ($message = Session::get('error'))
                            <div class="alert alert-danger">
                            <strong>{{ $message }}</strong> 
                            </div>
                        @endif
                    <!-- Page Header -->
                    <div class="page-header" style="margin: 80px 0 0 0;">
                        <div class="row align-items-center">
                            <div class="col" style="padding:10px 5px 30px 5px;">
                                <h3 class="page-title" style="">Change your password</h3>                               
                            </div>                           
                        </div>
                    </div>
                    <!-- /Page Header -->

                    <form action="{{ url('/app/updatePassword') }}" method="post" id="changePasswordForm">
                        @csrf
                    <div class="form-group">
                        <label>Old password</label>
                        <input type="password" class="form-control" name="old_password">
                        <span class="show-hide-password js-show-hide has-show-hide"><i class="fa fa-eye-slash"></i></span>
                         {{-- @if ($errors->has('old_password')) <p class="text-danger">{{ $errors->first('old_password') }}</p> @endif --}}
                         <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-old_password"></p>
                    </div>
                    <div class="form-group">
                        <label>New password</label>
                        <input type="password" class="form-control" name="password">
                        <span class="show-hide-password js-show-hide has-show-hide"><i class="fa fa-eye-slash"></i></span>
                         {{-- @if ($errors->has('password')) <p class="text-danger">{{ $errors->first('password') }}</p> @endif --}}
                         <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-password"></p>

                    </div>
                    <div class="form-group">
                        <label>Confirm new password</label>
                        <input type="password" class="form-control" name="password_confirmation">
                        <span class="show-hide-password js-show-hide has-show-hide"><i class="fa fa-eye-slash"></i></span>
                         {{-- @if ($errors->has('password_confirmation')) <p class="text-danger">{{ $errors->first('password_confirmation') }}</p> @endif --}}
                         <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-password_confirmation"></p>
                    </div>
                    <div class="submit-section">
                        <button type="submit" class="btn btn-primary submit-btn">Update Password</button>
                    </div>
                </form>
           <!-- ./row -->
        </div>
    </div>
</div>
</div>
</div>
<!-- /Page Content -->
<script>
    $(document).ready(function(){
        $('.submit-btn').on('click', function() {
            var $this = $(this);
            // $('.submit-btn').attr('disabled',true);
            var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
            if ($(this).html() !== loadingText) {
            $this.data('original-text', $(this).html());
            $this.html(loadingText);
            }
            setTimeout(function() {
            $this.html($this.data('original-text'));
            // $('.submit-btn').attr('disabled',false);
            }, 5000);
        });
    
       $('#createUserBtn').click(function(e) {
            e.preventDefault();
            $("#changePasswordForm").submit();
        });
    
        $(document).on('submit', 'form#changePasswordForm', function (event) {
                event.preventDefault();
                //clearing the error msg
                $('p.error_container').html("");
                
                var form = $(this);
                var data = new FormData($(this)[0]);
                var url = form.attr("action");
                $('.submit-btn').attr('disabled',true);
                    $.ajax({
                        type: form.attr('method'),
                        url: url,
                        data: data,
                        cache: false,
                        contentType: false,
                        processData: false,      
                        success: function (response) {
                
                            console.log(response);
                            window.setTimeout(function(){
                                $('.submit-btn').attr('disabled',false);
                            },5000);
                            
                            if(response.success==true  ) {          
                            
                                //notify
                            toastr.success("Password changed successfully");
                                // redirect to google after 5 seconds
                                window.setTimeout(function() {
                                    window.location = "{{ url('/')}}"+"/login";
                                }, 2000);
                            
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

        $(document).on('click','.js-show-hide',function (e) {

            e.preventDefault();

            var _this = $(this);

            if (_this.hasClass('has-show-hide'))
            {
                _this.parent().find('input').attr('type','text');
                _this.html('<i class="fa fa-eye"></i>');
                _this.removeClass('has-show-hide');
            }
            else
            {
                _this.addClass('has-show-hide');
                _this.parent().find('input').attr('type','password');
                _this.html('<i class="fa fa-eye-slash"></i>');
            }


        });
    });
</script>             

@endsection