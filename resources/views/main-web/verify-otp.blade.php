@extends('layouts.verify')
@section('content')
<style>
    .disabled-link
{
    pointer-events: none;
}
</style>
<section class="mobile-auth p-4">
    <div class="heading pt-5">
      <h2 class="font-weight-bold">Mobile Authentication</h2>
    </div>
    <div class="small-text">
      <p>An OTP is generated and send to your number <br><b>+91-{{$otp->mobile}}</b>. <a href="{{route('candidate-login')}}" style="color: #1D8218;font-weight: 700;">Edit</a></p>
    </div>
    <form class="mt-2" method="post" id="addCandidateForm" action="{{route('candidate-login.otp.verify')}}" enctype="multipart/form-data">
        @csrf
        <div class="text-center">
            @php
                $resend = 'd-none disabled-link';

                $time_limit='';

                $sms_date_time = date('Y-m-d H:i:s',strtotime($otp->created_at.' +'.'3 minutes'));
                // dd($sms_date_time);
                $today_date_time = date('Y-m-d H:i:s');

                if(strtotime($today_date_time) > strtotime($sms_date_time))
                {
                    $resend = '';

                    $time_limit = 'd-none';
                }
            @endphp
            <input type="hidden" name="mobile" class="mobile" value="{{$otp->mobile}}">
            <input id="otp-first" type="number" min="0" max="9" step="1" maxlength="1" tabindex="0" aria-label="first digit" name="otp[]" class=" otp otp-input ml-1 mr-2"/>
            <input id="otp-second" type="number" min="0" max="9" step="1"  maxlength="1" tabindex="1" aria-label="second digit" name="otp[]" class=" otp otp-input mr-2"/>
            <input id="otp-third" type="number" min="0" max="9" step="1" maxlength="1"  tabindex="2" aria-label="third digit" name="otp[]" class=" otp otp-input mr-2"/>
            <input id="otp-fourth" type="number" min="0" max="9" step="1"   maxlength="1" tabindex="3" aria-label="fourth digit" name="otp[]" class=" otp otp-input mr-2"/>
            <p class="instruction p-2"> 
                <span class="time {{$time_limit}} text-dark" id="time" style="color: #304ca8;">You will receive OTP in : <span id="timer"></span></span>
                <span class="resend {{$resend}} resend_otp" style="color: #304ca8;"><a href="" style="color: #1D8218;font-weight: 700;">Resend OTP</a></span>
            </p>
            <p style="margin-bottom: 2px;" class="text-danger error-container pt-2" id="error-otp"></p> 

            <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-all"> </p> 

        </div>
        <button class="align-items-center d-flex justify-content-center mt-4 theme-btn"  type="submit"> Verify OTP <img src="{{ asset('address-verification/img/arrow.svg')}}" class="img-fluid mt-1 pl-3">
        </button>
    </form>
</section>
  <script>
    $(document).ready(function(){
        $(document).on('submit', 'form#addCandidateForm', function (event) {
            event.preventDefault();
            //clearing the error msg
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
                        if(response.success==false  ) {
                            if( response.error_type == 'validation' ){
                                                                                            
                                for (control in response.errors) {   
                                    $('#error-'+ control).html(response.errors[control]);
                                }
                                return false;
                            }
                            if (response.error_type == 'yes') {
                            
                                $('#error-all').html(response.message);
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
    });
    function OTPInput() {
        const inputs = document.querySelectorAll('.otp');
        // alert(inputs.length);
        for (let i = 0; i < inputs.length; i++) 
        { 
            inputs[i].addEventListener('keyup', function(event) 
            { 
                if (event.key==="Backspace" ) 
                { 
                    inputs[i].value='' ; 
                    if (i !==0) inputs[i - 1].focus();
                    
                } 
                else { 
                    if (i===inputs.length - 1 && inputs[i].value !=='' ) 
                    { return true; } 
                    else if (event.keyCode> 47 && event.keyCode < 58) 
                    { 
                        inputs[i].value=event.key; 
                        if (i !==inputs.length - 1) inputs[i + 1].focus(); event.preventDefault(); 
                        
                    } 
                    else if (event.keyCode> 95 && event.keyCode < 106) 
                    { 
                        inputs[i].value=event.key; 
                        if (i !==inputs.length - 1) 
                        inputs[i + 1].focus(); event.preventDefault(); 
                        
                    }
                } 
                
            }); 
            
        } 
        
    } 
    OTPInput();

        var date_future = new Date('{{$otp->created_at}}');
        date_future.setMinutes(date_future.getMinutes() + 3);

        var today_date =  new Date().toISOString().slice(0, 10);
        var today = new Date();
        var today_time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
        var date_now = today_date+' '+today_time;
		var diff = new Date(date_future) - new Date(date_now);
        // alert(diff);
        if(diff > 0)
        {
            var sec = Math.floor(diff/1000);
            var min = Math.floor(sec/60);
            var h = Math.floor(min/60);
            var d = Math.floor(h/24);
            
            h = h-(d*24);
            min = min-(d*24*60)-(h*60);
            sec = sec-(d*24*60*60)-(h*60*60)-(min*60);
        
            $('#timer').html(min + ":" + sec);
            startTimer();
        }
        else
        {
            $('.resend').removeClass('d-none');
            $('.resend').removeClass('disabled-link');
            $('.time').addClass('d-none');
        }
        


function startTimer() {
  var presentTime = $('#timer').html();
  var timeArray = presentTime.split(/[:]+/);
  var m = timeArray[0];
  var s = checkSecond((timeArray[1] - 1));
  if(s==59){m=m-1}
  if(m<0){
    $('.time').addClass('d-none');
    $('.resend').removeClass('d-none');
    $('.resend').removeClass('disabled-link');
    return
  }
  
  
  $('#timer').html(m + ":" + s);
    //   console.log(m);
  setTimeout(startTimer, 1000);
  
}

function checkSecond(sec) {
  if (sec < 10 && sec >= 0) {sec = "0" + sec}; // add zero in front of numbers < 10
  if (sec < 0) {sec = "59"};
  return sec;
}
</script>
@endsection