<!DOCTYPE html>
<html lang="en">
    <title>{{ config('app.name', 'Clobminds') }}</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" type="image/x-icon" href="{{url('/').'/admin/images/fevicon.png'}}">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-social/5.1.1/bootstrap-social.css">
  <link href="{{ asset('admin/fonts/font-awesome-all.css')}}" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> 
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
  <script type="text/javascript">
    var loaderPath = "https://f52.in/admin/assets/images/preload.gif";
   </script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
   <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.css">
</head>
<style type="text/css">
.fxt-template-layout34 {
  position: relative;
  min-height: 100vh;
  width: 100%;
  background-repeat: no-repeat;
  background-position: center top;
}
.fxt-template-layout34 .fxt-column-wrap {
  position: relative;
  min-height: 100vh;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  -ms-flex-direction: column;
  flex-direction: column;
  padding-top: 12vh;
  padding-bottom: 12vh;
}
@media only screen and (max-width: 991px) {
  .fxt-template-layout34 .fxt-column-wrap {
    padding-top: 40vh;
    padding-bottom: 10vh;
    min-height: auto;
  }
}
@media only screen and (max-width: 767px) {
  .fxt-template-layout34 .fxt-column-wrap {
    padding-top: 40vh;
    padding-bottom: 8vh;
  }
}
@media only screen and (max-width: 575px) {
  .fxt-template-layout34 .fxt-column-wrap {
    padding-top: 40vh;
    padding-bottom: 6vh;
  }
}
.fxt-template-layout34 .fxt-shape {
  position: absolute;
  right: 0;
  top: 0;
  z-index: 0;
}
.fxt-template-layout34 .fxt-animated-img {
  position: absolute;
  z-index: -1;
  top: 50%;
  right: 50px;
  -webkit-transform: translateY(-50%);
  -ms-transform: translateY(-50%);
  transform: translateY(-50%);
}
.fxt-template-layout34 .fxt-logo {
  margin-bottom: 50px;
  display: block;
  max-width: 35vw;
}
.fxt-template-layout34 .fxt-main-title {
  max-width: 280px;
  width: 100%;
  font-size: 40px;
  font-weight: 700;
  color: #000000;
  margin-bottom: 20px;
}
@media only screen and (max-width: 991px) {
  .fxt-template-layout34 .fxt-main-title {
    font-size: 36px;
  }
}
@media only screen and (max-width: 767px) {
  .fxt-template-layout34 .fxt-main-title {
    font-size: 32px;
  }
  .fxt-shape-one img{
    width:100%;
  }
}
@media only screen and (max-width: 575px) {
  .fxt-template-layout34 .fxt-main-title {
    font-size: 28px;
  }
}
.fxt-template-layout34 .fxt-switcher-description1 {
  color: #363636;
  font-size: 20px;
  max-width: 260px;
  width: 100%;
  margin-bottom: 40px;
}
.fxt-template-layout34 .fxt-switcher-description1 .fxt-switcher-text {
  display: inline-block;
  color: #4460f1;
  font-size: 18px;
  font-weight: 600;
  -webkit-transition: all 0.3s ease-in-out;
  -o-transition: all 0.3s ease-in-out;
  transition: all 0.3s ease-in-out;
}
.fxt-template-layout34 .fxt-switcher-description1 .fxt-switcher-text:hover {
  color: #0925ad;
  text-decoration: underline;
}
.fxt-template-layout34 .fxt-switcher-description2 .fxt-switcher-text {
  color: #b1b1b2;
  font-size: 14px;
  -webkit-transition: all 0.3s ease-in-out;
  -o-transition: all 0.3s ease-in-out;
  transition: all 0.3s ease-in-out;
}
.fxt-template-layout34 .fxt-switcher-description2 .fxt-switcher-text:hover {
  color: #1a34b8;
  text-decoration: underline;
}
.fxt-template-layout34 .fxt-switcher-description3 {
  text-align: center;
  font-size: 16px;
  color: #646464;
  margin-bottom: 10px;
}
.fxt-template-layout34 .fxt-switcher-description3 .fxt-switcher-text {
  color: #4460f1;
  font-size: 16px;
  font-weight: 500;
  display: inline-block;
  -webkit-transition: all 0.3s ease-in-out;
  -o-transition: all 0.3s ease-in-out;
  transition: all 0.3s ease-in-out;
}
.fxt-template-layout34 .fxt-switcher-description3 .fxt-switcher-text:hover {
  color: #0f2ab2;
  text-decoration: underline;
}
.fxt-template-layout34 .fxt-qr-code {
  display: inline-block;
  max-width: 35vw;
}
.fxt-template-layout34 .fxt-qr-code img {
  -webkit-box-shadow: 0 0 30px 0 rgba(0, 0, 0, 0.1);
  box-shadow: 0 0 30px 0 rgba(0, 0, 0, 0.1);
  background-color: #ffffff;
  padding: 20px;
  border: 1px solid #dfdfdf;
}
@media only screen and (max-width: 575px) {
  .fxt-template-layout34 .fxt-qr-code img {
    padding: 5px;
  }
}
.fxt-template-layout34 .fxt-form {
  margin-top: 10px;
}
.fxt-template-layout34 .fxt-form .fxt-label {
  color: #14133b;
  font-size: 18px;
  font-weight: 700;
  margin-bottom: 12px;
}
.fxt-template-layout34 .fxt-form .form-group {
  position: relative;
  z-index: 1;
  margin-bottom: 25px;
}
.fxt-template-layout34 .fxt-form .form-group .field-icon {
  position: absolute;
  z-index: 1;
  right: 26px;
  bottom: 24px;
  font-size: 14px;
  color: #a1a1a1;
}
.fxt-template-layout34 .fxt-form .form-group .field-icon:before {
  padding: 17px 10px;
}
.fxt-template-layout34 .fxt-form .form-control {
  border-radius: 10px;
  background-color: #ebf0f6;
  min-height: 60px;
  -webkit-box-shadow: none;
  box-shadow: none;
  border: 1px solid #ebf0f6;
  padding: 10px 20px;
  color: #111;
}
@media only screen and (max-width: 767px) {
  .fxt-template-layout34 .fxt-form .form-control {
    min-height: 50px;
  }
}
.fxt-template-layout34 .fxt-form input::-webkit-input-placeholder {
  color: #858588;
  font-size: 14px;
  font-weight: 300;
}
.fxt-template-layout34 .fxt-form input::-moz-placeholder {
  color: #858588;
  font-size: 14px;
  font-weight: 300;
}
.fxt-template-layout34 .fxt-form input:-moz-placeholder {
  color: #858588;
  font-size: 14px;
  font-weight: 300;
}
.fxt-template-layout34 .fxt-form input:-ms-input-placeholder {
  color: #858588;
  font-size: 14px;
  font-weight: 300;
}
.fxt-template-layout34 .fxt-form .fxt-checkbox-box {
  margin-bottom: 25px;
}
.fxt-template-layout34 .fxt-form .fxt-checkbox-box label {
  color: #14133b;
  margin-bottom: 0;
  font-size: 14px;
  font-weight: 500;
  position: relative;
}
.fxt-template-layout34 .fxt-form .fxt-checkbox-box label:before {
  content: "";
  position: absolute;
  width: 16px;
  height: 16px;
  top: 5px;
  left: 0;
  right: 0;
  border: 1px solid;
  border-color: #dcdcdc;
  border-radius: 3px;
  background-color: #fff;
  -webkit-transition: border 0.15s ease-in-out, color 0.15s ease-in-out;
  -o-transition: border 0.15s ease-in-out, color 0.15s ease-in-out;
  transition: border 0.15s ease-in-out, color 0.15s ease-in-out;
}
.fxt-template-layout34 .fxt-form .fxt-checkbox-box label:after {
  position: absolute;
  font-size: 10px;
  color: #555555;
}
.fxt-template-layout34 .fxt-form .fxt-checkbox-box input[type="checkbox"] {
  display: none;
}
.fxt-template-layout34 .fxt-form .fxt-checkbox-box input[type="checkbox"]:checked + label::after {
  font-family: 'Font Awesome 5 Free';
  content: "\f00c";
  font-weight: 900;
  color: #ffffff;
  left: 0;
  right: 0;
  top: 5px;
  width: 16px;
  text-align: center;
}
.fxt-template-layout34 .fxt-form .fxt-checkbox-box input[type="checkbox"]:checked + label::before {
  background-color: #4460f1;
  border-color: #4460f1;
}
.fxt-template-layout34 .fxt-form .fxt-otp-logo {
  margin-bottom: 30px;
  display: block;
}
.fxt-template-layout34 .fxt-form .fxt-otp-row {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -ms-flex-wrap: wrap;
  flex-wrap: wrap;
  margin-bottom: 20px;
}
.fxt-template-layout34 .fxt-form .fxt-otp-row .fxt-otp-col {
  min-height: 50px;
  padding: 5px;
  text-align: center;
  margin-right: 15px;
  -ms-flex-preferred-size: 0;
  flex-basis: 0;
  -webkit-box-flex: 1;
  -ms-flex-positive: 1;
  flex-grow: 1;
}
.fxt-template-layout34 .fxt-form .fxt-otp-row .fxt-otp-col:last-child {
  margin-right: 0;
}
.fxt-template-layout34 .fxt-form .fxt-otp-btn {
  margin-bottom: 20px;
}
.fxt-template-layout34 .terms-link {
  display: inline-block;
  color: #4460f1;
  -webkit-transition: all 0.3s ease-in-out;
  -o-transition: all 0.3s ease-in-out;
  transition: all 0.3s ease-in-out;
}
.fxt-template-layout34 .terms-link:hover {
  color: #1a34b8;
  text-decoration: underline;
}
.fxt-template-layout34 .fxt-btn-fill {
  font-family: 'Roboto', sans-serif;
  cursor: pointer;
  display: inline-block;
  font-size: 18px;
  font-weight: 500;
  -webkit-box-shadow: none;
  box-shadow: none;
  outline: none;
  border: 0;
  color: #fff;
  border-radius: 10px;
  background-color: #4460f1;
  padding: 12px 36px;
  width: 100%;
  -webkit-transition: all 0.3s ease-in-out;
  -o-transition: all 0.3s ease-in-out;
  transition: all 0.3s ease-in-out;
}
.fxt-template-layout34 .fxt-btn-fill:hover {
  background-color: #1a34b8;
}
.fxt-template-layout34 .fxt-btn-fill:focus {
  outline: none;
}
@media only screen and (max-width: 767px) {
  .fxt-template-layout34 .fxt-btn-fill {
    font-size: 16px;
    padding: 11px 30px;
  }
}
.fxt-template-layout34 .fxt-style-line {
  overflow: hidden;
  text-align: center;
  margin-bottom: 20px;
}
.fxt-template-layout34 .fxt-style-line span {
  text-align: center;
  font-size: 15px;
  color: #acacac;
  display: inline-block;
  position: relative;
  padding: 0 25px;
  z-index: 1;
}
.fxt-template-layout34 .fxt-style-line span:before {
  display: inline-block;
  content: "";
  height: 2px;
  width: 100%;
  background-color: #cfcfcf;
  left: 100%;
  top: 50%;
  -webkit-transform: translateY(-50%);
  -ms-transform: translateY(-50%);
  transform: translateY(-50%);
  position: absolute;
  z-index: 1;
}
.fxt-template-layout34 .fxt-style-line span:after {
  display: inline-block;
  content: "";
  height: 2px;
  width: 100%;
  background-color: #cfcfcf;
  right: 100%;
  top: 50%;
  -webkit-transform: translateY(-50%);
  -ms-transform: translateY(-50%);
  transform: translateY(-50%);
  position: absolute;
  z-index: 1;
}
.fxt-template-layout34 ul.fxt-socials {
  display: -ms-flexbox;
  display: -webkit-box;
  display: flex;
  -ms-flex-wrap: wrap;
  flex-wrap: wrap;
  -webkit-box-pack: center;
  -ms-flex-pack: center;
  justify-content: center;
  margin-right: -5px;
  margin-left: -5px;
}
.fxt-template-layout34 ul.fxt-socials li {
  padding-left: 5px;
  padding-right: 5px;
  margin-bottom: 10px;
}
.fxt-template-layout34 ul.fxt-socials li a {
  border-radius: 10px;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -webkit-box-pack: center;
  -ms-flex-pack: center;
  justify-content: center;
  font-size: 20px;
  height: 60px;
  width: 80px;
  border: 1px solid;
  border-color: #cfcfcf;
  background-color: #fefefe;
  -webkit-transition: all 0.3s ease-in-out;
  -o-transition: all 0.3s ease-in-out;
  transition: all 0.3s ease-in-out;
}
@media only screen and (max-width: 767px) {
  .fxt-template-layout34 ul.fxt-socials li a {
    font-size: 18px;
    height: 50px;
    width: 60px;
  }
}
.fxt-template-layout34 ul.fxt-socials li a:hover {
  background-color: #ffffff;
  border-color: #ffffff;
  -webkit-box-shadow: 0 0 62px 0 rgba(0, 0, 0, 0.1);
  box-shadow: 0 0 62px 0 rgba(0, 0, 0, 0.1);
}
.fxt-template-layout34 ul.fxt-socials li.fxt-google a {
  color: #CC3333;
}
.fxt-template-layout34 ul.fxt-socials li.fxt-apple a {
  color: #132133;
}
.fxt-template-layout34 ul.fxt-socials li.fxt-facebook a {
  color: #3b5998;
}
.owl-nav{
    text-align:center;
}
.owl-nav .owl-prev, .owl-nav .owl-next{
    font-size:45px !important;
    line-height: 30px !important;
    margin: 0px 10px;
}
  body {
    color: #000;
    overflow-x: hidden;
    height: 100%;
    background-color: #ffffff;
    background-repeat: no-repeat
}
/* .col-lg-6.imageblock {
        height: 100vh;    
    padding: 50px 0px;
   
} */
.col-lg-6.imageblock {
    background-color:#288FC8;
    height: auto;
    padding: 110px 0px;
}

.logo {
    width: 180px;
    height: auto;
    margin-top: 15%;
    /* margin-left: 40vh; */
}
.col-lg-6.login {
    /*position: absolute;
    left: 50%;*/
    text-align: center;
}
.container-fluid.px-1.px-md-5.px-lg-1.px-xl-5.py-5.mx-auto {
    padding: 0px!important;
}
.card0 {
    /*box-shadow: 0px 4px 8px 0px #757575;*/
    border-radius: 0px
}
.card2.card.border-0.px-4.py-5 {
    padding-bottom: 0px!important;
}
.card1.pb-5{
    padding-bottom:0px!important;
}
.card2 {
    margin: 40px 80px;
}
/* .text-danger {
    color: #03136b!important;
}
.text-danger:hover {
    color: #03136b!important;
} */
/* .logo {
    width: 180px;
    height: auto;
    margin-top: 40px;
  
} */

img.image {
    width: 100%;
    height: auto;
}
/*.border-line {
    border-right: 1px solid #EEEEEE
}*/

.facebook {
    background-color: #3b5998;
    color: #fff;
    font-size: 18px;
    padding-top: 5px;
    border-radius: 50%;
    width: 35px;
    height: 35px;
    cursor: pointer
}

.twitter {
    background-color: #1DA1F2;
    color: #fff;
    font-size: 18px;
    padding-top: 5px;
    border-radius: 50%;
    width: 35px;
    height: 35px;
    cursor: pointer
}

.linkedin {
    background-color: #2867B2;
    color: #fff;
    font-size: 18px;
    padding-top: 5px;
    border-radius: 50%;
    width: 35px;
    height: 35px;
    cursor: pointer
}

.line {
    height: 1px;
    width: 45%;
    background-color: #E0E0E0;
    margin-top: 10px
}

.or {
    width: 10%;
    font-weight: bold
}

.text-sm {
    font-size: 14px !important
}

::placeholder {
    color: #BDBDBD;
    opacity: 1;
    font-weight: 300
}

:-ms-input-placeholder {
    color: #BDBDBD;
    font-weight: 300
}

::-ms-input-placeholder {
    color: #BDBDBD;
    font-weight: 300
}

input,
textarea {
    padding: 10px 50px 10px 12px;
    border: 1px solid lightgrey;
    border-radius: 2px;
    margin-bottom: 5px;
    margin-top: 2px;
    width: 100%;
    box-sizing: border-box;
    color: #2C3E50;
    font-size: 14px;
    letter-spacing: 1px
}

input:focus,
textarea:focus {
    -moz-box-shadow: none !important;
    -webkit-box-shadow: none !important;
    box-shadow: none !important;
    border: 1px solid #304FFE;
    outline-width: 0
}

button:focus {
    -moz-box-shadow: none !important;
    -webkit-box-shadow: none !important;
    box-shadow: none !important;
    outline-width: 0
}

a {
    color: inherit;
    cursor: pointer
}

.btn-blue {
    background-color:#0081C9 /*#1A237E*/;
    width: 150px;
    color: #fff;
    border-radius: 2px
}

.btn-blue:hover {
    background-color: #5BC0F8;
    color: white;
    cursor: pointer
}

.bg-blue {
    color: #fff;
    background-color: #0081C9
}

.or-seperator {
    margin: 20px 0 10px;
    text-align: center;
    border-top: 1px solid #ccc;
}

.or-seperator i {
    padding: 0 10px;
    background: #f7f7f7;
    position: relative;
    top: -11px;
    z-index: 1;
}

.btn-info {
  color: #fff;
  background-color: #003473;
  border-color: #003473; }
  .btn-info:hover {
    color: #fff;
    background-color: #00234d;
    border-color: #001d40; }
  .btn-info:focus, .btn-info.focus {
    box-shadow: 0 0 0 0.2rem rgba(0, 52, 115, 0.5); }
  .btn-info.disabled, .btn-info:disabled {
    color: #fff;
    background-color: #003473;
    border-color: #003473; }
  .btn-info:not(:disabled):not(.disabled):active, .btn-info:not(:disabled):not(.disabled).active,
  .show > .btn-info.dropdown-toggle {
    color: #fff;
    background-color: #001d40;
    border-color: #001733; }
    .btn-info:not(:disabled):not(.disabled):active:focus, .btn-info:not(:disabled):not(.disabled).active:focus,
    .show > .btn-info.dropdown-toggle:focus {
      box-shadow: 0 0 0 0.2rem rgba(0, 52, 115, 0.5); }
      .otp1 {
  display: flex;
      }
      .digit {
        margin-left: 9px;
        padding: 0px;
        height: 40px;
        }

@media screen and (max-width: 991px) {
    .col-lg-6.login {
    position: relative;
     left: 0%; 
}
    .col-lg-6.imageblock {
    background-color: /*rgb(91, 99, 254)*/ #002e62;
    height: auto;
    padding:0px;
    /*position: relative;
    left: 0%;
    bottom: 0;
    top: 0px;*/
}
    .logo {
        margin-left: 0px
    }

    .image {
        width: 300px;
        height: 220px
    }

    .border-line {
        border-right: none
    }

    .card2 {
        border-top: 1px solid #EEEEEE !important;
        margin: 0px 15px
    }
}
span.show-hide-password {
    position: relative;
    top: -48px;
    right: -93%;
    font-size: 16px;
    color: #748a9c;
    cursor: pointer;
    z-index: 1;
}

.fxt-template-layout34 .fxt-shape-one {
    position: absolute;
    left: 0 !important;
    top: 0;
    z-index: 0;
}
.content{
    position: absolute;
    bottom: 60px;
    left: 95px;
}
</style>
<body style="background-image: url('{{asset('admin/images/shap03.png')}}'); background-size: contain; background-repeat: no-repeat;">

    <section class="fxt-template-animation fxt-template-layout34" style="background-image: url('{{asset('admin/images/shap03.png')}}'); background-size: cover;
    background-repeat: no-repeat;">
        <div class="fxt-shape fxt-shape-one">
            <div class="fxt-transformX-L-50 fxt-transition-delay-1">
                <img src="{{asset('admin/images/shap02.png')}}" alt="Shape" >
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-8 position-relative">
                    <h2 class="content "><strong>Clobminds</strong><span class="sub-content d-block ml-5"> -Verify with Confidence </span></h2>
                    {{-- <div class="fxt-column-wrap justify-content-between">
                        <div class="fxt-animated-img">
                            <div class="fxt-transformX-L-50 fxt-transition-delay-10">
                                <img src="img/figure/bg34-1.png" alt="Animated Image">
                            </div>
                        </div>
                        <div class="fxt-transformX-L-50 fxt-transition-delay-3">
                            <a href="login-34.html" class="fxt-logo"><img src="img/logo-34.png" alt="Logo"></a>
                        </div>
                        <div class="fxt-transformX-L-50 fxt-transition-delay-5">
                            <div class="fxt-middle-content">
                                <h1 class="fxt-main-title">Sign In to Rechage Direct</h1>
                                <div class="fxt-switcher-description1">If you don’t have an account You can<a href="register-34.html" class="fxt-switcher-text ms-2">Sign Up</a></div>
                            </div>
                        </div>
                        <div class="fxt-transformX-L-50 fxt-transition-delay-7">
                            <div class="fxt-qr-code">
                                <img src="img/elements/qr-login-34.png" alt="QR">
                            </div>
                        </div>
                    </div> --}}
                </div>
                
                <div class="col-lg-4">
                   
                    <div class="fxt-column-wrap justify-content-center">
                        <img src="{{ asset('admin/images/logo.jpg')}}" class="w-50 mx-auto">
                        <div class="fxt-form">
                            <form method="Post" action="{{ url('/userAuthenticate') }}" id="userAuthForm">
                                @csrf 
                                <div class="form-group">
                                    <input type="email" id="email" class="form-control" name="email" placeholder="Enter a valid email address" required="required">
                                    <p class="mb-3 text-danger error_container" id="error-email"></p> 
                                </div>
                                <div class="form-group">
                                    <input id="password" type="password" class="form-control " name="password" placeholder="********" required="required">
                                    <span class="show-hide-password js-show-hide has-show-hide"><i  class="fa fa-fw fa-eye-slash "></i></span>
                                    <p style="margin-left: -15px" class="text-danger error_container" id="error-password"></p>
                                </div>
                                <div class="form-group">
                                    <div class="fxt-switcher-description2 text-right">
                                        <a href="{{url('/forgot-password')}}" class="fxt-switcher-text">Forgot Password</a>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="fxt-btn-fill login_submit ">Sign In</button>
                                    
                                </div>
                            </form>
                        </div>
                        <div class="fxt-style-line">
                            <span><img src="{{asset('admin/images/shap04.png')}}"></span>
                        </div>
                        <div class="owl-carousel owl-theme fourth-sectionTwo">
                            <div class="item">
                                <img src="{{asset('admin/images/aadhaar.png')}}" alt="" class="src">
                            </div>
    
                            <div class="item">
                                <img src="{{asset('admin/images/aadhaar-1.png')}}" alt="" class="src">
                            </div>
    
                            <div class="item">
                                <img src="{{asset('admin/images/aadhaar-2.png')}}" alt="" class="src">
                            </div>
    
                            <div class="item">
                                <img src="{{asset('admin/images/aadhaar-3.png')}}" alt="" class="src">
                            </div>
                            <div class="item">
                                <img src="{{asset('admin/images/aadhaar-6.png')}}" alt="" class="src">
                            </div>
    
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="fxt-shape">
            <div class="fxt-transformX-L-50 fxt-transition-delay-1">
                <img src="{{asset('images/shap01.png')}}" alt="Shape">
            </div>
        </div> --}}
    </section>
{{-- <div class="container-fluid">
    
        <div class="row">
            <div class="col-lg-6 imageblock">
                <div class="row px-3 justify-content-center mt-4 mb-5 border-line"><img src="{{ asset('admin/images/login-vector-img.png')}}" class="image"> </div>
            </div>
            <div class="col-lg-4">
                <div class="fxt-column-wrap justify-content-center">
                    <div class="fxt-form">
                        <form method="POST">
                            <div class="form-group">
                                <input type="email" id="email" class="form-control" name="email" placeholder="Enter Email or Mobile Number" required="required">
                            </div>
                            <div class="form-group">
                                <input id="password" type="password" class="form-control" name="password" placeholder="********" required="required">
                                <i toggle="#password" class="fa fa-fw fa-eye toggle-password field-icon"></i>
                            </div>
                            <div class="form-group">
                                <div class="fxt-switcher-description2 text-right">
                                    <a href="forgot-password-34.html" class="fxt-switcher-text">Recovery Password</a>
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="fxt-btn-fill">Sign In</button>
                            </div>
                        </form>
                    </div>
                    <div class="fxt-style-line">
                        <span>Or Continus with</span>
                    </div>
                    <ul class="fxt-socials">
                        <li class="fxt-google">
                            <a href="#" title="google"><i class="fab fa-google-plus-g"></i></a>
                        </li>
                        <li class="fxt-apple">
                            <a href="#" title="apple"><i class="fab fa-apple"></i></a>
                        </li>
                        <li class="fxt-facebook">
                            <a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-6 login">
                <div class="card1 pb-5">
                    <div class="row"> <div class="col-md-12 text-center"><a href="{{ url('/') }}"><img src="{{asset('img/logo.png')}}" class="logo text-center">
                     </a></div></div>
                    <form method="Post" action="{{ url('/userAuthenticate') }}" id="userAuthForm">
                        @csrf  
                            <div class="card2 card border-0 px-4 py-5">

                                <div class="row"> 
                                    <div class="col-md-12 text-left">
                                        <h2>Sign in </h2>
                                    </div>
                                </div>
                            
                                <div class="row px-3"> <label class="mb-1">
                                        <h6 class="mb-0 text-sm">Email Address <span class="text-danger">*</span></h6>
                                    </label> 
                                    <input class="error-control email" type="text" name="email" placeholder="Enter a valid email address"> 
                                    <p class="mb-3 text-danger error_container" id="error-email"></p> 
                                </div>
                                <div class="row px-3 mt-2"> <label class="mb-1">
                                        <h6 class="mb-0 text-sm">Password <span class="text-danger">*</span></h6>
                                    </label> 
                                    <input type="password" class="error-control password" name="password" placeholder="Enter password">
                                    <span class="show-hide-password js-show-hide has-show-hide"><i class="fa fa-eye-slash"></i></span>
                                    <p style="margin-left: -15px" class="text-danger error_container" id="error-password"></p>
                                </div>
                                <span style="" class="text-left text-danger error_container" id="wrong-credential"> </span>
                                <div class="row px-3 mb-4">
                                  
                                    <a href="{{url('/forgot-password')}}" class="ml-auto mb-0 text-sm forget">Forgot Password?</a>
                                </div>
                                <div class="row mb-3 px-3"> 
                                    <button type="submit" class="btn btn-blue text-center login_submit">LOGIN</button> 
                                </div>
                               
                            </div>
                    </form>
                   

                </div>
            </div>
            
        </div>
        
    
</div> --}}

{{-- Modal for Forget Password --}}
{{-- <div class="modal"  id="forgetpass">
    <div class="modal-dialog">
       <div class="modal-content">
          <!-- Modal Header -->
          <div class="modal-header">
             <h4 class="modal-title">Forgot Password</h4>
             <button type="button" class="close" style="top: 12px;!important; color: red; " data-dismiss="modal">&times;</button>
          </div>
          <!-- Modal body -->
          <form method="post" action="{{url('/forget/password/email')}}" id="forgetPasswordForm">
          @csrf
          
             <div class="modal-body">
             
             <div class="form-group">
                <label for="email">Email Address</label> 
                <input type="text" name="forgetemail" class="form-control" id="forgetemail" placeholder="Enter email" value="{{ old('forgetemail') }}">
                <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-forgetemail"></p>  
            </div>
            <p style="margin-bottom: 2px;font-size:12px;" class="text-success error_container" id="error-all"> </p> 
             </div>
             <!-- Modal successfooter -->
             <div class="modal-footer">
                
                <button type="submit" class="btn btn-primary forgot_submit" >Submit </button>
                <button type="button" class="btn btn-danger back" data-dismiss="modal">Close</button>
             </div>
          </form>
       </div>
    </div>
</div> --}}
{{-- End of Forget Password Model --}}

{{-- Modal for Account Verification --}}
<div class="modal fade"  id="account_ver_mdl">
    <div class="modal-dialog">
       <div class="modal-content">
          <!-- Modal Header -->
          <div class="modal-header">
             <h4 class="modal-title text-muted">Account Verification</h4>
             <button type="button" class="close btn-disable" style="top: 12px;!important; color: red; " data-dismiss="modal">&times;</button>
          </div>
          <!-- Modal body -->
          <form method="post" action="{{url('/account/mobileverify')}}" id="accountverifyfrm">
          @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label for="email" class="text-muted">Mobile Number <span class="text-danger">*</span> <i class="fa fa-info-circle" data-toggle="tooltip" data-original-title="Please Enter Your Whatsapp Number"></i></label> 
                    <input type="text" name="mobile_number" class="form-control mobile_number" id="mobile_number" autocomplete="off">
                    <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-mobile_number"></p>  
                </div>
                <p style="margin-bottom: 2px;font-size:12px;" class="text-success error-container error-all" id="error-all"> </p> 
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="submit" class="btn btn-info submit btn-disable">Submit</button>
                <button type="button" class="btn btn-danger btn-disable" data-dismiss="modal">Close</button>
            </div>
          </form>
       </div>
    </div>
</div>
<div class="modal" id="verificaion">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title" id="ser_name">Email verification!</h4>
                {{-- <button type="button" class="close closeraisemdl" data-dismiss="modal">&times;</button> --}}
            </div>
            <!-- Modal body -->
            <form method="post" action="{{url('/verfiy_otp')}}" id="verificationfrm">
                @csrf
                <input type="hidden" name="verify_email" id="verify_email">
                <div class="modal-body">
                    {{-- <div id="verify_msg"> --}}
                    <div class="form-group">
                        <div class="row justify-content-center align-items-center">
                            <div class="col-sm-12 text-center">
                                <label for="label_name"> OTP </label>
                            </div>
                        </div>
                        <div class="row justify-content-center align-items-center">
                            <div class="col-sm-6 text-center otp1">
                                <input name="otp[]" class="digit text-center otp" type="text" id="first_otp" size="1" maxlength="1" tabindex="0" >
                                <input name="otp[]" class="digit text-center otp" type="text" id="second_otp" size="1" maxlength="1" tabindex="1">
                                <input name="otp[]" class="digit text-center otp" type="text" id="third_otp" size="1" maxlength="1"  tabindex="2">
                                <input name="otp[]" class="digit text-center otp" type="text" id="fourth_otp" size="1" maxlength="1" tabindex="3">
                            </div>
                        </div>
                        <div class="row justify-content-center align-items-center">
                            <div class="col-sm-6 text-center">
                                
                                <p style="margin-bottom: 2px;" class="text-danger error-container pt-2 error-otp" id="error-otp"></p> 
                                <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-all"> </p> 
                            </div>
                        </div>
                    </div>
                    {{-- </div> --}}
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-info verificaion-submit">Submit </button>
                    <button type="button" class="btn btn-danger closeemail" data-dismiss="modal">Close</button>
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
                    <button type="button" class="btn btn-danger btn_otp" id="otp_close" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- End of Forget Password Model --}}
<script>
    // $('.forget').click(function(){
    //     // Open Model Pop up
    //     $('#forgetpass').toggle();
    
    //     $(document).on('submit', 'form#forgetPasswordForm', function (event) {
    //         event.preventDefault();
    //         //clearing the error msg
    //         $('p.error_container').html("");
    //         $('.forgot_submit').attr('disabled',true);
    //         $('.form-control').attr('readonly',true);
    //         var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
    //         var form = $(this);
    //         var data = new FormData($(this)[0]);
    //         var url = form.attr("action");
    //         if ($('.forgot_submit').html() !== loadingText) {
    //           $('.forgot_submit').html(loadingText);
    //         }
    //         $.ajax({
    //             type: 'POST',
    //             url: url,
    //             data: data,
    //             cache: false,
    //             contentType: false,
    //             processData: false,      
    //             success: function (response) {
    
    //                 console.log(response);
    //                 window.setTimeout(function(){
    //                     $('.forgot_submit').attr('disabled',false);
    //                     $('.forgot_submit').html('Submit');
    //                     $('.form-control').attr('readonly',false);
    //                 },2000);
    //                 if(response.success==true  ) {          
                    
    //                 //     //notify
    //                    var email=response.email;
    //                    toastr.success("Mail has been sent successfully");
    //                    $('#error-all').html('Link has been sent on your email address('+email+'), Check your inbox for reset password! <br> Thank you');
    //                    $('.form-control').val('');
    //                     // redirect to google after 5 seconds
    //                     // window.setTimeout(function() {
    //                     //     window.location = "{{ url('/')}}"+"/login";
    //                     // }, 2000);
    //                     // history.go(-1);
                    
    //                 }
    //                 //show the form validates error
    //                 else if(response.success==false ) {                              
    //                     for (control in response.errors) {   
    //                         $('#error-' + control).html(response.errors[control]);
    //                     }
    //                 }
    
    //             },
    //             error: function (xhr, textStatus, errorThrown) {
    //                 // alert("Error: " + errorThrown);
    //             }
    //         });
    //         return false;
    //     });
    //     //close model
    //     $('.close').click(function(){
    //         $('#forgetpass').hide();
    //         $('.form-control').val('');
    //         $('.error_container').html('');
    //     });
    //     $('.back').click(function(){
    //         $('#forgetpass').hide();
    //         $('.form-control').val('');
    //         $('.error_container').html('');
    //     });
    // });
   
    //user authenticate
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
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
                        window.location="{{url('/login')}}";
                        // window.location.href='{{ Config::get('app.admin_url')}}';
                    }
                },
            });

        });

        $(document).on('click', '.verificaion-submit', function (event) {
    
          
            var verform = $(this);
           
            var verdata = new FormData($("#verificationfrm")[0]);
            // var data = new FormData($(this)[0]);
            // var verurl = verform.attr("action");
            // alert(verdata);
            // var $btn = $(this);
            $('.btn_otp').attr('disabled',true);
            $('.otp').removeClass('border-danger');
            var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
            if($('.verificaion-submit').html()!=loadingText)
            {
                $('.verificaion-submit').html(loadingText);
            }
            $('.error-container').html('');
            $.ajax({
                type: 'POST',
                url: "{{ route('/verify_otp') }}",
                data: verdata,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    // console.log(data);
                    // $('.error-container').html('');
                    window.setTimeout(function(){
                        $('.btn_otp').attr('disabled',false);
                        $('.verificaion-submit').html('Submit');
                    },2000);
                    if (data.fail && data.error_type == 'validation') {
                                                
                            //$("#overlay").fadeOut(300);
                            for (control in data.errors) {
                                $('.' + control).addClass('border-danger');
                                $('#error-' + control).html(data.errors[control]);
                            }
                    } 
                    if (data.success==false && data.error_type == 'yes') {
                       
                        $('.error-otp').html(data.message);
                    }
                    if(data.success==true  ) {   
                        window.setTimeout(function(){
                        $('#verificaion').hide();   
                        },2000);
                        window.location = data.redirect;
                    }
                },
                error: function (data) {
                    console.log(data);
                }
                // error: function (xhr, textStatus, errorThrown) {
                //     console.log("Error: " + errorThrown);
                //     // alert("Error: " + errorThrown);

                // }
            });
            event.stopImmediatePropagation();
            return false;

        });

    $(document).on('submit', 'form#userAuthForm', function (event) {
        event.preventDefault();
        var form = $(this);
        var data = new FormData($(this)[0]);
        var url = form.attr("action");
        var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
        $(".error_container").html("");
        $('.error-control').removeClass('border-danger');
        $('.login_submit').attr('disabled',true);
        if($('.login_submit').html()!=loadingText)
        {
            $('.login_submit').html(loadingText);
        }
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
                    window.setTimeout(function(){
                        $('.login_submit').attr('disabled',false);
                        $('.login_submit').html("LOGIN");
                        },1000);
                    if( response.error_type == 'validation' ){
                                                                                    
                        for (control in response.errors) {
                            $('.'+control).addClass('border-danger');   
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
                    if( response.error_type == 'To many attempts' ){
                        console.log(response);                                                           
                        $("#wrong-credential").html("");
                        $("#wrong-credential").html("To many wrong attempts ..You are blocked for 3 hours!");
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
                        // $("#wrong-credential").html("Your Account has not been verified yet, here's a link for <span class='account_verify' style='color: #304ca8;'><a href='javascript:void(0)'>Account Verification</a></span>");
                        $("#wrong-credential").html("Your Account has not been verified yet, Check your email to verify your account !!");
                    }
                    if(response.error_type=='logged-in')
                    {
                        console.log(response);                                                           
                        // $("#loggedin_msg").html("");
                        // $("#wrong-credential").html("Your Account has not been verified yet, here's a link for <span class='account_verify' style='color: #304ca8;'><a href='javascript:void(0)'>Account Verification</a></span>");
                        $('#verify_email').val(response.email);
                        $('#loggedin_msg').html("It seems like you have logged in another browser, If you want to login here?");
                        // $("#logged_in").modal("show");
                
                        // $('#logged_in').modal({
                        //     backdrop: 'static',
                        //     keyboard: false
                        // });
                        $('#verificationfrm')[0].reset();
                        $('.otp').removeClass('border-danger');
                         $('.error-container').html('');
                        $('#verificaion').modal({
                            backdrop: 'static',
                            keyboard: false
                        });
                        // $("#wrong-credential").html("It seems like you have logged in another browser, If you want to login here?");
                    }

                    // window.setTimeout(function(){
                    //     $('.login_submit').attr('disabled',false);
                    //     $('.login_submit').html('LOGIN');
                    // },2000);
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

    $(document).on('click','.account_verify',function(){

        $('#accountverifyfrm')[0].reset();
        $('.error-container').html('');
        $('.form-control').removeClass('border-danger');
        $('#account_ver_mdl').modal({
                                backdrop: 'static',
                                keyboard: false
                            });
    });

    $(document).on('submit','form#accountverifyfrm',function(event){
        event.preventDefault();
        
        var form = $(this);
        var data = new FormData($(this)[0]);
        var url = form.attr("action");
        var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> Loading...';
        $(".error-container").html("");
        $('.form-control').removeClass('border-danger');
        $('.btn-disable').attr('disabled',true);
        if($('.submit').html()!=loadingText)
        {
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
                        $('.btn-disable').attr('disabled',false);
                        $('.submit').html('Submit');
                    },2000);

                    if (response.status) { 
                        toastr.success("OTP Sent Successfully");
                        window.setTimeout(function(){
                            window.location="{{url('/account_verification/')}}"+'/'+response.id;
                        })
                    }
                    else if(response.status==false && response.error_type=='validation')
                    {
                        for (control in response.errors) { 
                            var len = 0;
                            var error_msg='';
                            if(Array.isArray(response.errors[control]))
                            {
                                len = response.errors[control].length;
                            }
                            // console.log(len);
                            $('.'+control).addClass('border-danger');
                            if(len > 1)
                            {
                                $(response.errors[control]).each(function(key,value){
                                    if(key+1!=len)
                                    {
                                        error_msg+=value+' & ';
                                    }
                                    else
                                    {
                                        error_msg+=value;
                                    }
                                });
                            }
                            else
                            {
                                error_msg+= response.errors[control];
                            }

                            $('#error-' + control).html(error_msg); 

                        }
                    } 
                    else if(response.status==false && response.error_type=='custom')
                    {
                        $('.error-all').html(response.errors);
                    } 
                    else {
                        toastr.error("Something Went Wrong !!");
                    }
                },
                error: function (xhr, textStatus, errorThrown) {
                    // alert("Error: " + errorThrown);
                }
            });

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
</script>
<script>
//     $('.fourth-sectionTwo').owlCarousel({
//     loop:true,
//     margin:30,
//     nav:true,
//     dots:false,
//     responsive:{
//         0:{
//             items:1
//         },
//         600:{
//             items:1
//         },
//         1000:{
//             items:1
//         }
//     }
// })
$('.fourth-sectionTwo').owlCarousel({
    center: true,
    items:2,
    loop:true,
    nav:true,
    margin:10,
    responsive:{
        0:{
            items:2
        },
        600:{
            items:2
        },
        2000:{
            items:2
        }
    }
});
</script>

</body>
</html>
