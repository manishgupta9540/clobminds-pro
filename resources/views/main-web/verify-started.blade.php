<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link rel="stylesheet" href="css/address-verification/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <title></title>
</head>
<body class="vh-100">
    <div class="d-flex align-items-center justify-content-center h-75">
        <img src="{{ asset('address-verification/img/logo.png')}}" class="img-fluid p-5" />
    </div>
    <div class="bottom-section">
        <p class="para text-center d-block">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam bibendum </p>
        <a class="theme-btn d-flex align-items-center justify-content-center" href="{{route('term-and-condition')}}">
            Get started  <img src="{{ asset('address-verification/img/arrow.svg')}}" class="img-fluid mt-1 pl-3">
        </a>
    </div>
</body>
</html>