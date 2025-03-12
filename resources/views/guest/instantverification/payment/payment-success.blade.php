<!DOCTYPE html>
<html lang="en">
<head>
  <title>BCD</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" type="image/x-icon" href="{{url('/').'/admin/images/BCD-favicon.png'}}">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link href="{{ asset('admin/fonts/font-awesome-all.css') }}" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="{{ asset('admin/js/jquery-3.3.1.min.js') }}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <link href="https://fonts.googleapis.com/css?family=Kaushan+Script|Source+Sans+Pro" rel="stylesheet">
  <style type="text/css">
    *{
  box-sizing:border-box;
 /* outline:1px solid ;*/
}
h1.congratulation {
    margin-top: 21px;
}
button.go-home:focus {
    border-radius: 30px;
    border: 1px solid black;
    outline: 0px auto -webkit-focus-ring-color;
}
img.check {
    margin-top: 8px;
}
.wrapper-1{
  width:100%;
  height:100vh;
  display: flex;
flex-direction: column;
}
.wrapper-2{
  padding :30px;
  text-align:center;
}
h1{
  /* font-family: 'Kaushan Script', cursive; */
  /* font-size:4em; */
  letter-spacing:3px;
  /* color:#5892FF ; */
  margin:0;
  margin-bottom:20px;
}
.wrapper-2 p {
    margin: 0;
    font-size: 27px;
    color: #002e60;
    font-family: 'Source Sans Pro', sans-serif;
    letter-spacing: 1px;
}
.go-home {
    color: #002e62;
    background: #fff;
    border: none;
    padding: 10px 50px;
    margin: 30px 0;
    border-radius: 30px;
    text-transform: capitalize;
    /* box-shadow: 0px 4px 15px #666; */
    border: 1px solid #002e62;
}
.go-home:hover{
    color: #fff;
    background: #002e62;
    border: 1px solid #fff;
    animation: fadeIn 0.7s;
}
.go-report {
    color: #28a745;
    background: #fff;
    border: none;
    padding: 10px 50px;
    margin: 30px 0;
    border-radius: 30px;
    text-transform: capitalize;
    /* box-shadow: 0px 4px 15px #666; */
    border: 1px solid #28a745;
}
.go-report:hover{
    color: #fff;
    background: #28a745;
    border: 1px solid #fff;
    animation: fadeIn 0.7s;
}

.disabled-link{
  pointer-events: none;
}
@keyframes fadeIn {
  0% {opacity:0;}
  100% {opacity:1;}
}
.footer-like{
  margin-top: -22px; 
  
  padding:6px;
  text-align:center;
}
.footer-like p {
    margin: 0;
    padding: 4px;
    color: #444;
    font-family: 'Source Sans Pro', sans-serif;
    letter-spacing: 1px;
}
.footer-like p a {
    text-decoration: none;
    color: #444;
    font-weight: 600;
}

@media (min-width:360px){
  /* h1{
    font-size:4.5em;
  } */
  .go-home{
    margin-bottom:20px;
  }
}

@media (min-width:600px){
  .content{
  max-width:1000px;
  margin:0 auto;
}
  .wrapper-1{
  height: initial;
  max-width:100%;
  margin:0 auto;
  margin-top:50px;
 
}


}
  
}

  </style>
</head>
<body>
<div class=content>
  <div class="wrapper-1">
    <div class="wrapper-2">
      <img src="{{ asset('admin/images/BCD-Logo2.png')}}">
      <h1 class="congratulation text-danger">Awesome !</h1>
      <p>Report Generated Successfully</p>
      @php
        $order_id = '';
        $guest_master_data = Helper::get_guest_instant_master_data($guest_master_id);
        if($guest_master_data!=NULL)
        {
            $order_id = $guest_master_data->order_id;
        }
      @endphp
      <p>Order ID: {{$order_id}} </p>
      <img src="{{ asset('admin/images/thank_check.png')}}" class="check">
      <p class="thanku">Thank You  </p>
      <a href="{{url('/verify/instantverification/orders')}}">
        <button class="go-home">
            <i class="fas fa-clipboard-list"></i> View Order
      </button>
    </a>
    {{-- <a class="report" href="javascript:void(0)" data-id="{{base64_encode($guest_master_data->id)}}">
        <button class="go-report">
            <i class="fab fa-whatsapp"></i> Report on Whatsapp
        </button>
    </a> --}}
    <a href="#">
        <button class="go-report">
            <i class="fab fa-whatsapp"></i> Report on Whatsapp
        </button>
    </a>
    </div>
    <div class="footer-like">
      <p>Having Trouble?
       <a href="{{url('/verify/help')}}">Contact Us</a>
      </p>
    </div>
</div>
</div>

<script>
  $(document).ready(function(){

    $(document).on('click','.report',function(){
        var _this =  $(this);
        var id = _this.attr('data-id');
        var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> Loading...';
        _this.addClass('disabled-link');
        _this.attr('disabled',true);
        $('.go-report').attr('disabled',true);
        if($('.go-report').html!=loadingText)
        {
            $('.go-report').html(loadingText);
        }
        $.ajax
        ({
                type:'POST',
                url: "{{ url('/verify/')}}"+"/instant_verification/whatsapp_report",
                data: {"_token": "{{ csrf_token() }}",'id':id},        
                success: function (response) {        
                    window.setTimeout(function(){
                        _this.removeClass('disabled-link');
                        _this.attr('disabled',false);
                        $('.go-report').attr('disabled',false);
                        $('.go-report').html('<i class="fab fa-whatsapp"></i> Report on Whatsapp');
                    },2000);

                    if (response.status) { 

                      toastr.success("Report Details Has Been Sent Successfully to your Whatsapp Number");

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

  });
  
</script>

</body>
</html>
