@extends('layouts.app_old')
@section('content')

<style>
  #header{
    display:none;
}
.thanku-event h1 {
    font-size: 30px;
}
@media only screen and (max-width:767px){
.thanku-event {
   width: 100%!important;
}
.thanku-page-font{
  font-size: 25px!important;
}
.text-success {
    font-size: 20px;
}
}
</style>
  
<main id="main" class="thanks">
  <section class="thanku-page">
      <div class="thanku-beta">
        <div class="row">
          <div class="col-md-12 mt-50">
                {{-- <h2 class="logo mylogo text-center"><a class="btn-bcd" href="{{ url('/') }}">BCD</a></h2> --}}
            <div class="thanku-event">
            
              
              <h1 class="thanku-page-font">Thank You for Clear Insuff</h1><br>
              <h4 class="text-success">You did an awesome job!</h4><br>
              <h3 class="text-danger">Have a nice day!</h3>
             
              {{-- <p>Please check your email for further instructions on how to complete your account setup.</p> --}}
              {{-- <p>Having trouble ? <a href="{{ url('/contact') }}">Contact us</a></p> --}}
              {{-- <p class="home-link"><a class="btn-bcd" href="{{ url('/') }}">Continue to homepage</a></p> --}}
            </div>
          </div>
        </div>
      </div>
  </section>
</main>
<!-- End #main -->

@endsection
