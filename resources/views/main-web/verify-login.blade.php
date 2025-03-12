@extends('layouts.verify')
@section('content')
    <section class="mobile-auth p-4">
        <div class="heading pt-5">
            <h2 class="font-weight-bold">Mobile Authentication</h2>
        </div>
        <div class="small-text">
            <p>Please allow us to verify your mobile number</p>
        </div>
        <form class="mt-2" method="post" id="addCandidateForm" action="{{route('candidate-login.send.otp')}}" enctype="multipart/form-data">
            @csrf
            <div class="d-flex dropdown flag pt-4 form-group">
                <button class="btn btn-success dropdown-toggle setField" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <img src="https://media.geeksforgeeks.org/wp-content/uploads/20200630132503/iflag.jpg" class="flag-img"> +91 </button>
                <input type="text" class="form-control mobilenumber" name='mobilenumber' id='mobilenumber' placeholder="Enter your mobile number">
              

                {{-- <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="position: absolute; transform: translate3d(424px, 38px, 0px); top: 0px; left: 0px; will-change: transform;">
                    <li class="dropdown-item getData">
                        <img src="https://media.geeksforgeeks.org/wp-content/uploads/20200630132503/iflag.jpg" class="flag-img"> +91
                    </li>
                    <li class="dropdown-item getData">
                        <img src="https://media.geeksforgeeks.org/wp-content/uploads/20200630132504/uflag.jpg" class="flag-img"> +1
                    </li>
                </ul> --}}
            </div>
            <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-mobilenumber"></p>
            <button class="align-items-center d-flex justify-content-center mt-4 theme-btn submit"  type='submit'> Send OTP <img src="{{ asset('address-verification/img/arrow.svg')}}" class="img-fluid mt-1 pl-3">
            </button>
        </form>
  </section>
  <script>
    $(document).ready(function(){
        $(document).on('submit', 'form#addCandidateForm', function (event) {
            event.preventDefault();
            // //clearing the error msg
            $('p.error_container').html("");

            var form = $(this);
            var data = new FormData($(this)[0]);
            var url = form.attr("action");
            var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
            $('.submit').attr('disabled',true);
            $('.close').attr('disabled',true);
            $('.form-control').attr('readonly',true);
            $('.form-control').addClass('disabled-link');
            $('.error-control').addClass('disabled-link');
            if ($('.submit').html() !== loadingText) {
                $('.submit').html(loadingText);
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
                            $('.submit').attr('disabled',false);
                            $('.close').attr('disabled',false);
                            $('.form-control').attr('readonly',false);
                            $('.form-control').removeClass('disabled-link');
                            $('.error-control').removeClass('disabled-link');
                            $('.submit').html('Send OTP');
                        },2000);
                        // console.log(response);
                        if(response.success==true  ) {  
                            if(response.success==true && response.mail=='no' ) {   
                                toastr.error("Email is not availble for this candidate");
                            }     
                            if(response.success==true && response.mail=='yes' ) {   
                                toastr.success("OTP has been created successfully");
                                //redirect to google after 5 seconds
                                console.log(response.id);
                                window.setTimeout(function() {
                                    window.location = "{{ url('/')}}"+"/candidate-login/otp?id="+response.id ;
                                }, 2000);
                            } 
                            //notify
                        
                        
                        }
                        //show the form validates error
                        if(response.success==false ) {  
                            var i=0;                            
                            for (control in response.errors) {   
                                $('#error-' + control).html(response.errors[control]);
                                
                            }
                        }
                    },
                    error: function (xhr, textStatus, errorThrown) {
                        alert("Error: " + errorThrown);
                    }
                });
                // return false;
        });
    });
</script>
@endsection