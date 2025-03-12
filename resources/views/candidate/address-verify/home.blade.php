@extends('layouts.verify')
@section('content')
    <section class="welcome p-4">
        <div class="heading pt-2">
            <h2 class="font-weight-bold">Hi! {{$user->name}}</h2>
        </div>
        <div class="small-text">
            <p>Let's start with the address verification</p>
        </div>
        <div class="address mt-5">
            
                @if (count($jaf)==1)
                    @foreach ($jaf as $item)
                        @php
                            $form_data=Helper::get_jaf_data_by_jafid($item->jaf_id);
                            $input_item_data = $form_data->form_data;
                            $input_item_data_array =  json_decode($input_item_data, true); 
                            foreach($input_item_data_array as $key => $input)
                            {
                                $key_val = array_keys($input); 
                                $input_val = array_values($input);
                                if($key_val[0]=='Address')
                                {
                                    $address=$input_val[0];
                                }
                                if($key_val[0]=='City')
                                {
                                    $city=$input_val[0];
                                }
                                if($key_val[0]=='State')
                                {
                                    $state=$input_val[0];
                                }
                                if($key_val[0]=='Pin code')
                                {
                                    $pin=$input_val[0];
                                }
                            }
                            // $data= $address.','.$city.', '.$state.', '.$pin;
                            // dd($address);
                        @endphp
                    @endforeach
                @endif
           
            <p class="title">Address</p>
            <p class="add-content" id="selected_address">@if (count($jaf)==1){{ $address.','.$city.', '.$state.', '.$pin}} @endif</p>
        </div>
        <p class="mt-2 text-justify" style="font-size: 15px;">Digital Address Verification has been assigned to you, please confirm the above address for further processing?</p>
        <a class="align-items-center d-flex justify-content-center mt-4 theme-btn " href="staying-since.php"> Yes, I’m </a>
        <a class="align-items-center btn d-flex justify-content-center mt-3 no-add">No, Change my address</a>
    </section>
    @if (count($jaf)>1)
        <div class="modal" id="preview_modal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title" id="serv_name">Addresses</h4>
                        {{-- <button type="button" class="close closeraisemdl" data-dismiss="modal">&times;</button> --}}
                    </div>
                    <!-- Modal body -->
                    <form method="post" action="{{url('/home/selected/address')}}" id="address_form">
                        @csrf
                        <div class="modal-body">
                           
                            <div class="form-group">
                                @foreach ($jaf as $item)
                                    @php
                                        $form_data=Helper::get_jaf_data_by_jafid($item->jaf_id);
                                        $input_item_data = $form_data->form_data;
                                        $input_item_data_array =  json_decode($input_item_data, true); 
                                        foreach($input_item_data_array as $key => $input)
                                        {
                                            $key_val = array_keys($input); 
                                            $input_val = array_values($input);
                                            if($key_val[0]=='Address')
                                            {
                                                $address=$input_val[0];
                                            }
                                            if($key_val[0]=='City')
                                            {
                                                $city=$input_val[0];
                                            }
                                            if($key_val[0]=='State')
                                            {
                                                $state=$input_val[0];
                                            }
                                            if($key_val[0]=='Pin code')
                                            {
                                                $pin=$input_val[0];
                                            }
                                        }
                                        // dd($address);
                                    @endphp
                                    <input  type="radio" class="address" id="address-{{$item->jaf_id}}" name="address" value="{{$item->jaf_id}}" > <label for="address-{{$item->jaf_id}}"> {{$address.','.$city.', '.$state.', '.$pin}}</label><br>
                                @endforeach
                                <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-user_status"></p>

                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-info preview_submit" >Submit </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
    <script>
        var a = "{{count($jaf)}}";
        if (a>1) {
            $('#preview_modal').modal({
                backdrop: 'static',
                keyboard: false
            });
        }
        $(document).ready(function(){
            $(document).on('click','.preview_submit',function(){
         
               var jaf_id   = $("input[type='radio'][name='address']:checked").val();
                console.log(jaf_id);
                if (jaf_id!=undefined) {
                    $.ajax({
                        type: 'GET',
                        url:"{{ url('/address-verification/home/selected/address') }}",
                        data: {'jaf_id':jaf_id},
                            
                        success: function (data) {
                            // console.log(data.success);
                            $('.error-container').html('');
                            if (data.fail && data.error == '') {
                                    //console.log(data.success);
                                $('.error').html(data.message);
                            }
                            
                            if (data.fail == false ) {
                                    
                                $("#selected_address").html(data.data);
                                $('#preview_modal').modal('hide');
                                // window.setTimeout(function(){

                                // },2000);
                                    
                            }
                        } 
                    
                    });
                }else{
                    alert('Please select a address');
                }
             });
           

        });
      
    </script>
@endsection