<!-- // Let's Click this button automatically when this page load using javascript -->
<!-- You can hide this button -->
<button id="rzp-button1" hidden>Pay</button>  
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
var options = {
    "key": "{{$response->razorpay_id}}", // Enter the Key ID generated from the Dashboard
    "amount": "{{$response->total_price * 100}}", // Amount is in currency subunits. Default currency is INR. Hence, 50000 refers to 50000 paise
    "currency": "{{$response->currency}}",
    "name": "{{$response->name}}",
    "description": "Pay Verification",
    "image": "https://example.com/your_logo", // You can give your logo url
    "order_id": "{{$response->transaction_id}}", //This is a sample Order ID. Pass the `id` obtained in the response of Step 1
    "handler": function (response){
        // After payment successfully made response will come here
        // send this response to Controller for update the payment response
        // Create a form for send this data
        // Set the data in form
        document.getElementById('rzp_paymentid').value = response.razorpay_payment_id;
        document.getElementById('rzp_orderid').value = response.razorpay_order_id;
        document.getElementById('rzp_signature').value = response.razorpay_signature;

        // // Let's submit the form automatically
        document.getElementById('rzp-paymentresponse').click();
    },
    "prefill": {
        "name": "{{$response->name}}",
        "email": "{{$response->email}}",
        "contact": "{{$response->contactNumber}}"
    },
    "theme": {
        "color": "#528FF0"
    }
};
var rzp1 = new Razorpay(options);
window.onload = function(){
    document.getElementById('rzp-button1').click();
};

document.getElementById('rzp-button1').onclick = function(e){
    rzp1.open();
    e.preventDefault();
}
</script>

<!-- This form is hidden -->
<form action="{{url('/guest/candidates/verification/payment-complete')}}" method="POST" hidden>
    <input type="hidden" value="{{csrf_token()}}" name="_token" /> 
    <input type="hidden" value="{{base64_encode($response->candidate_id)}}" name="candidate_id">
    <input type="text" class="form-control" id="rzp_paymentid"  name="rzp_paymentid">
    <input type="text" class="form-control" id="rzp_orderid" name="rzp_orderid">
    <input type="text" class="form-control" id="rzp_signature" name="rzp_signature">
    <button type="submit" id="rzp-paymentresponse" class="btn btn-primary">Submit</button>
</form>