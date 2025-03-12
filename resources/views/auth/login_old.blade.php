
<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login | </title>
    <link rel="shortcut icon" type="image/x-icon" href="">
    <link href="https://fonts.googleapis.com/css?family=Nunito:300,400,400i,600,700,800,900" rel="stylesheet">
    <link href="{{ asset('admin/gull/dist-assets/css/themes/lite-purple.min.css')}}" rel="stylesheet" />
    <link href="{{ asset('admin/fonts/font-awesome-all.css')}}" rel="stylesheet" />
    <link href="{{ asset('admin/css/style.css')}}" rel="stylesheet" />
    <link href="http://app.Clobminds.com/admin/css/perfect-scrollbar.min.css?ver=1.0" rel="stylesheet" />
    <link href="http://app.Clobminds.com/admin/fonts/font-awesome-all.css" rel="stylesheet" />
    <link href="http://app.Clobminds.com/admin/resized/jquery.resizableColumns.css" rel="stylesheet">
    <link href="http://app.Clobminds.com/admin/css/style.css?ver=1.3" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="http://app.Clobminds.com/admin/js/jquery-3.3.1.min.js"></script>
    <script src="http://app.Clobminds.com/js/jquery-ui.min.js"></script> 
    <link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    

    <style>
        span.show-hide-password {
            position: absolute;
            top: 30px;
            right: 10px;
            font-size: 14px;
            color: #748a9c;
            cursor: pointer;
        }
    </style>
</head>
<body>
<div class="auth-layout-wrap" style="background-image: url({{asset('admin/images/photo-wide-4.jpg')}})">
    <div class="auth-content">
        <div class="card o-hidden">
            <div class="row">
             
                <div class="col-md-12">
                    <div class="p-4">
                        <div class="auth-logo text-center mb-4">
                        <a href="{{ url('/') }}" > <img src="{{ asset('admin/images/clobmind-logo.png')}}" alt=""> </a>
                        </div>
                        <h1 class="mb-3 text-18 text-center">Login your account </h1>
                    
                        <form method="Post" action="{{ url('/userAuthenticate') }}" id="userAuthForm">
                        @csrf
                        @if ($message = Session::get('success'))
                        <div class="col-md-12">   
                           <div class="alert alert-success">
                           <strong>{{ $message }}</strong> 
                           </div>
                        </div>
                        @endif
                            <div class="form-group">
                                <label for="email">Email address</label>
                                <input id="email" type="text" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" >
                                <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-email"></p>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" >
                                <span class="show-hide-password js-show-hide has-show-hide"><i class="fa fa-eye-slash"></i></span>
                                <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-password"></p>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                            </div>

                            <p style="margin-bottom: 2px;" class="text-danger error_container" id="wrong-credential"> </p>

                            <input type="submit" class="btn btn-rounded btn-info btn-block mt-3" value="Sign In">
                        </form>
                        <div class="mt-3 text-center"> 
                            <a class="text-muted" href=""> <u style="margin: 10px; color: #3f51b5;float: left;">&nbsp;</u> </a> 
                            <a class="text-muted forget" href="javascript:" > <u style="float: right;">Forgot Password?</u></a> 
                        </div>
                    </div>
                </div>
 
            </div>
        </div>
    </div>
</div>



 {{-- Modal for Forget Password --}}
 <div class="modal"  id="forgetpass">
    <div class="modal-dialog">
       <div class="modal-content">
          <!-- Modal Header -->
          <div class="modal-header">
             <h4 class="modal-title">Forget Password</h4>
             <button type="button" class="close" style="top: 12px;!important; color: red; " data-dismiss="modal">&times;</button>
          </div>
          <!-- Modal body -->
          <form method="post" action="{{url('/forget/password/email')}}" id="forgetPasswordForm">
          @csrf
             
             <div class="modal-body">
             <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-all"> </p> 
             <p style="margin-bottom: 2px;" class="text-success success1" id="success1"> </p> 
             <div class="form-group">
                <label for="forgetemail">Email</label> 
                <input type="text" name="forgetemail" class="form-control" id="forgetemail" placeholder="Enter email" value="{{ old('forgetemail') }}">
                <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-forgetemail"></p>  
            </div>
                
             </div>
             <!-- Modal successfooter -->
             <div class="modal-footer">
                
                <button type="submit" class="btn btn-primary submit1">Submit </button>
                <button type="button" class="btn btn-danger back close1" data-dismiss="modal">Close</button>
             </div>
          </form>
       </div>
    </div>
 </div>

 <div class="modal" id="logged_in">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title" id="ser_name">Warning!</h4>
                {{-- <button type="button" class="close closeraisemdl" data-dismiss="modal">&times;</button> --}}
            </div>
            <!-- Modal body -->
            <form method="post" action="{{url('/user_loggedout')}}" id="loggedinfrm">
             @csrf
                <input type="hidden" name="loggedin_email" id="loggedin_email">
                
                    <div class="modal-body">
                        <div id="loggedin_msg">

                        </div>
                    </div>
            
                <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-info logout-submit">Log out </button>
                        <button type="button" class="btn btn-danger closeraisemdl" data-dismiss="modal">Close</button>
                    </div>
            </form>
        </div>
    </div>
</div>
{{-- End of Forget Password Model --}}
{{-- @stack('scripts')  --}}

<script src="{{ asset('admin/js/jquery-3.3.1.min.js') }}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script type="text/javascript">
//
$(document).ready(function(){
    
    $('.forget').click(function(){

        
        // Open Model Pop up
         $('#forgetpass').toggle();


         $(document).on('submit', 'form#forgetPasswordForm', function (event) {
       event.preventDefault();
       //clearing the error msg
       $('p.error_container').html("");
    
       var form = $(this);
       var data = new FormData($(this)[0]);
       var url = form.attr("action");
       var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
        $('.submit1').attr('disabled',true);
        $('.close1').attr('disabled',true);
        $('.form-control').attr('readonly',true);
        $('.form-control').addClass('disabled-link');
        $('.error-control').addClass('disabled-link');
        if ($('.submit1').html() !== loadingText) {
            $('.submit1').html(loadingText);
        }
        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            cache: false,
            contentType: false,
            processData: false,      
            success: function (response) {
                      window.setTimeout(function(){
                        $('.submit1').attr('disabled',false);
                        $('.close1').attr('disabled',false);
                        $('.form-control').attr('readonly',false);
                        $('.form-control').removeClass('disabled-link');
                        $('.error-control').removeClass('disabled-link');
                        $('.submit1').html('Submit');
                      },2000);
                console.log(response);
                if(response.success==true) 
                { 
                
                toastr.success("Rest password link has been sent to the Candidate");
                    // redirect to google after 5 seconds
                    window.setTimeout(function() {
                        window.location = "{{ url('/')}}"+"/user_login";
                    }, 2000);
                    // history.go(-1);
                  
                }
                //show the form validates error
                else if(response.success==false ) {                              
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
        //close model
         $('.close').click(function(){
           $('#forgetpass').hide();
       });
       $('.back').click(function(){
           $('#forgetpass').hide();
       });
    });
   
    //user authenticate

    $(document).on('submit', 'form#userAuthForm', function (event) {
        event.preventDefault();
        var form = $(this);
        var data = new FormData($(this)[0]);
        var url = form.attr("action");
        $(".error_container").html("");
        
        $.ajax({
            type: form.attr('method'),
            url: url,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            success: function (response) {
                console.log(response);
                // return false;

                $('.is-invalid').removeClass('is-invalid');

                if(response.success==false  ) {
                    if( response.error_type == 'validation' ){
                                                                                    
                        for (control in response.errors) {   
                            $('#error-' + control).html(response.errors[control]);
                        }
                        return false;
                    }
                    if( response.error_type == 'wrong_email_or_password' ){
                        console.log(response);                                                           
                        $("#wrong-credential").html("");
                        $("#wrong-credential").html("Enter a valid email or password!");
                        return false;
                    }
                    if(response.error_type=='account-inactive')
                    {
                        console.log(response);                                                           
                        $("#wrong-credential").html("");
                        $("#wrong-credential").html("Your Account has been Deactivated! Please Contact to System Administrator");
                    }
                    if(response.error_type=='account-deleted')
                    {
                        console.log(response);                                                           
                        $("#wrong-credential").html("");
                        $("#wrong-credential").html("Your Account has been Deleted!");
                    }

                    if(response.error_type=='account-email')
                    {
                        console.log(response);                                                           
                        $("#wrong-credential").html("");
                        $("#wrong-credential").html("Your Account has not been verified yet,check your mail to verify email!");
                    }
                    if(response.error_type=='logged-in')
                    {
                        console.log(response);                                                           
                        // $("#loggedin_msg").html("");
                        // $("#wrong-credential").html("Your Account has not been verified yet, here's a link for <span class='account_verify' style='color: #304ca8;'><a href='javascript:void(0)'>Account Verification</a></span>");
                        $('#loggedin_email').val(response.email);
                        $('#loggedin_msg').html("It seems like you have logged in another browser, If you want to login here?");
                        // $("#logged_in").modal("show");
                
                        $('#logged_in').modal({
                            backdrop: 'static',
                            keyboard: false
                            });
                        // $("#wrong-credential").html("It seems like you have logged in another browser, If you want to login here?");
                    }
                    
                }
                if(response.success==true  ) {    
                    window.location = response.redirect;
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

    $(document).on('click','.logout-submit',function(){
        var form = $('#loggedinfrm');
        var data = new FormData(form[0]);
        var url = form.attr("action");
        // var email=$('#loggedin_email').val();
        // console.log(email);
        $.ajax({
        type: 'POST',
        url: url,
        data: data,
        cache: false,
        contentType: false,
        processData: false,
        success: function (response) {
            console.log(response);
            // return false;
            
            if(response.success==true  ) {
                // toastr.success("OTP Sent Successfully");
                window.location="{{url('/user_login')}}";
                // window.location.href='{{ Config::get('app.admin_url')}}';
            }
        },
        });

    });
});

</script>

</body>
</html>