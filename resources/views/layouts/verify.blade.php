<html lang="{{ app()->getLocale() }}">
   <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{asset('css/address-verification/style.css')}}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="{{ asset('admin/js/jquery-3.3.1.min.js') }}"></script>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    {{-- <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script> --}}

  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <title></title>
   </head>
   <body class="text-left"> 
        <header>
            <ul class="d-flex justify-content-between p-4 welcome">
                <li class="nav-item ">
                    <a href="javascript:history.go(-1)">
                        <img src="{{ asset('address-verification/img/Vector.svg')}}" class="img-fluid" />
                    </a>
                </li>
                <li class="nav-item w-25">
                    <img src="{{ asset('admin/images/logo.png')}}" class="img-fluid" />
                </li>
                <li class="nav-item">
                    <a href="#">
                        <img src="{{ asset('address-verification/img/help.svg')}}" class="img-fluid" />
                    </a>
                </li>
            </ul>
        </header>
        @yield('content')
        <footer class="pb-2 pl-5 pr-5">
            <div class="foot">
              <p class="text-center">Call us on <span style="color: #003473;font-weight: 700;">+91 9868356074</span> for your support <br>
                <b>@ 2022  <img src="{{ asset('address-verification/img/logo.png')}}" class="img-fluid" style="height: 20px;" />  All rights reserved</b>
              </p>
            </div>
        </footer>
        <script>
            // $(document).on('click', '.getData',function(){
            //     var dt = $(this).html();
            //     $('.setField').html(dt);
            // });
        </script>
    </body>
</html>