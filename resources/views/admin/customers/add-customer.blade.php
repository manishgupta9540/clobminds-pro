
@extends('layouts.admin')

@section('content')
<style type="text/css">
.input-group-addon {
    padding: 6px 12px;
    font-size: 14px;
    font-weight: 400;
    line-height: 1;
    color: #555;
    text-align: center;
    background-color: #eee;
    border: 1px solid #ccc;
    border-radius: 4px;
}></style>

<div class="clearfix"></div>
  
  <div class="content-wrapper">
    <div class="container-fluid">
      <div class="row">
         <div class="col-12 col-lg-12 col-xl-12">
      <h5 class="card-title"> Add customer </h5>
         <hr>
 
 <form action="{{route('/admin/customer/store')}}" id="addCustomerForm" method="post" style="padding: 10px 80px;">
  @csrf
 
  <div class="row">
  
    <div class="col-12 col-lg-4 col-xl-4">
        <div class="text-uppercase"> Customer overview </div>
      </div>
  
      <div class="col-12 col-lg-8 col-xl-8">
  <div class="card">
        <div class="card-body">

           <div class="row">
           <div class="col-md-6">
            <label for="input-1"> First Name  <span class="text-danger">*</span></label>
            <input type="text" class="form-control textName" id="input-1" name="first_name" placeholder="Enter First Name" autocomplete="off" value="{{ old('first_name') }}">
            @if ($errors->has('first_name')) <p class="help-block error">{{ $errors->first('first_name') }}</p> @endif
           </div>
           <div class="col-md-6">
            <label for="input-2"> Last Name </label>
            <input type="text" class="form-control textName" id="input-2" name="last_name" placeholder="Enter Last Name" autocomplete="off" value="{{ old('last_name') }}">
           </div>
           <div class="col-md-12">
            <label for="input-3"> Email <span class="text-danger">*</span></label>
            <input type="email" class="form-control" id="input-3" name="email" placeholder="Enter Email" autocomplete="off" value="{{ old('email') }}">
            @if ($errors->has('email')) <p class="help-block error">{{ $errors->first('email') }}</p> @endif
           </div>
           <div class="col-md-12">
            <label for="input-4"> Phone <span class="text-danger">*</span></label>
           <div class="input-group mb-3">
            <span class="input-group-addon">+1</span>
                <input type="text" id="input-4" name="phone_number" class="form-control only_no" placeholder="Enter Phone Number" autocomplete="off" value="{{ old('phone_number') }}">
               </div>
              @if ($errors->has('phone_number')) <p class="help-block error">{{ $errors->first('phone_number') }}</p> @endif
           </div> 
            <div class="col-md-12">
            <div class="form-check">
        <input class="form-check-input" type="checkbox" name="is_customer_subscribed" value="1" id="defaultCheck1" style="margin-top: 22px;">
        <label class="form-check-label" for="defaultCheck1">
        Customer agreed to receive marketing emails.
        </label>
        <p> You should ask your customers for permission before you subscribe them to your marketing emails. </p>
      </div>
           </div> 
           
            </div>
          </div>
  </div>
    </div>
  </div>
  <hr>
 <div class="row">
  
    <div class="col-12 col-lg-4 col-xl-4">
        <div class="text-uppercase"> Address </div>
        <p> The primary address of this customer </p>
      </div>
  
  
      <div class="col-12 col-lg-8 col-xl-8">
  <div class="card">
        <div class="card-body">
           <div class="row">
           <div class="col-md-6">
            <label for="input-5"> First Name  <span class="text-danger">*</span></label>
            <input type="text" class="form-control textName" id="input-5" name="address_first_name" autocomplete="off" value="{{ old('address_first_name') }}">
             @if ($errors->has('address_first_name')) <p class="help-block error">{{ $errors->first('address_first_name') }}</p> @endif
           </div>
           <div class="col-md-6">
            <label for="input-6"> last Name </label>
            <input type="text" class="form-control textName" id="input-6" name="address_last_name" autocomplete="off" value="{{ old('address_last_name') }}">
           </div>
           <div class="col-md-12">
            <label for="input-7"> Company </label>
            <input type="text" class="form-control" id="input-7" name="company_name" autocomplete="off" value="{{ old('company_name') }}">
           </div>
           <div class="col-md-12">
            <label for=""> Address <span class="text-danger">*</span></label>
            <input type="text" class="form-control addressField" name="address_line1" id="address1" placeholder="Address"> 
              @if ($errors->has('address_line1')) <p class="help-block error">{{ $errors->first('address_line1') }}</p> @endif
              <input type="hidden" class="latValue" name="addressLat" id="addressLat"> 
              <input type="hidden" class="lngValue" name="addressLng" id="addressLng">   
              <input type="hidden" name="addressfield" id="addressfield"> 
              <input type="hidden" placeholder="country" id="political">   
              <input type="hidden" name="sublocality" id="sublocality">  
              <input type="hidden" name="sublocality_level_1" id="sublocality_level_1">
              <input type="hidden" name="neighborhood" id="neighborhood"> 
              <input type="hidden" name="administrative_area_level_1" id="administrative_area_level_1">
              <input type="hidden" id="route"> 
              <input type="hidden" name="geo_country" id="country">
              <input type="hidden" name="geo_city" id="locality"> 
              <input type="hidden" name="geo_zipcode" id="postal_code">   
           </div> 
           <div class="col-md-12">
            <label for="input-8"> Apartment, suite, etc.  <span class="text-danger">*</span></label>
            <input type="text" class="form-control apartmentField" id="input-8" value="{{ old('apartment') }}" name="apartment" autocomplete="off">
            @if ($errors->has('apartment')) <p class="help-block error">{{ $errors->first('apartment') }}</p> @endif
           </div> 

           <div class="col-md-12">
            <label for="input-9"> City <span class="text-danger">*</span> </label>
            <input type="text" class="form-control" name="city" value="{{ old('city') }}">
            @if ($errors->has('city')) <p class="help-block error">{{ $errors->first('city') }}</p> @endif
           </div>            
           <div class="col-md-12">
            <label for="input-10"> Country/Region <span class="text-danger">*</span></label>
            <select class="form-control" name="country">
              <option value="">Select country</option>
              @foreach($countries as $cou)
              <option value="{{$cou->id}}">{{$cou->name}}</option>
              @endforeach
            </select>
            @if ($errors->has('country')) <p class="help-block error">{{ $errors->first('country') }}</p> @endif
           </div>

            <div class="col-md-12">
            <label for="input-10"> States <span class="text-danger">*</span></label>
            <select class="form-control" name="state">
              <option value="">Select state</option>
              @foreach($states as $sta)
              <option value="{{$sta->id}}">{{$sta->name}}</option>
              @endforeach
            </select>
            @if ($errors->has('state')) <p class="help-block error">{{ $errors->first('state') }}</p> @endif
           </div>

           <div class="col-md-12">
            <label for="input-2"> Postal code </label>
            <input type="text" class="form-control" name="zipcode" value="{{ old('zipcode') }}">
           </div>           

           <div class="col-md-12">
            <label for="input-25"> Phone <span class="text-danger">*</span></label>
                <div class="input-group mb-3">
                <span class="input-group-addon">+1</span>
                <input type="text" id="input-25" name="primary_mobile_number" class="form-control only_no" placeholder="Enter Phone Number"  value="{{ old('primary_mobile_number') }}" autocomplete="off">
              </div>
              @if ($errors->has('primary_mobile_number')) <p class="help-block error">{{ $errors->first('primary_mobile_number') }}</p> @endif
           </div>

            </div>
         </div>
  </div>
    </div>
  
 
  </div>
  
 <hr>
    <div class="row">
  
    <div class="col-12 col-lg-4 col-xl-4">
        <div class="text-uppercase"> Notes </div>
      </div>
  
  
      <div class="col-12 col-lg-8 col-xl-8">
  <div class="card">
        <div class="card-body">
           <div class="row">
            <div class="col-md-12">
           <label for="input-28"> Note   </label>
           </div>
       
           <div class="col-md-12">
            <input type="text" class="form-control" id="input-28" name="notes">
           </div>             
            </div>
         </div>
             </div>
    </div>
  
 
  </div> 
 
      <div class="form-footer" style="text-align: center;">
        <button type="submit" class="btn btn-success"><i class="fa fa-check-square-o"></i> SAVE</button>
        <a href="javaScript:;" class="btn btn-danger clrBtn"><i class="fa fa-times"></i> CANCEL</a>
      </div>
 
 </form>
        
         </div>
 
      </div><!--End Row-->

 
    
       <!--End Dashboard Content-->
      <!--start overlay-->
    <div class="overlay toggle-menu"></div>
  <!--end overlay-->
    </div>
    <!-- End container-fluid-->
    
    </div><!--End content-wrapper-->
   <!--Start Back To Top Button-->
    <a href="javaScript:void();" class="back-to-top"><i class="fa fa-angle-double-up"></i> </a>
    <!--End Back To Top Button-->

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAvFpaBTE6GipLprVwhOkgtvUaAjo1sYQU&amp;libraries=places" type="text/javascript"></script>

<script type="text/javascript">

  $('#addCustomerForm').on('keydown', '.only_no', function(e){-1!==$.inArray(e.keyCode,[46,8,9,27,13,110,190])||/65|67|86|88/.test(e.keyCode)&&(!0===e.ctrlKey||!0===e.metaKey)||35<=e.keyCode&&40>=e.keyCode||(e.shiftKey||48>e.keyCode||57<e.keyCode)&&(96>e.keyCode||105<e.keyCode)&&e.preventDefault()});

    $('.clrBtn').click(function(){
    $("#addCustomerForm")[0].reset();
  });
  
 function initialize_auto1() 
 {
    var options = {
    componentRestrictions: {country: "IN"}
    };
    var pickuplocation = document.getElementById('address1');
    var autocomplete = new google.maps.places.Autocomplete(pickuplocation, options);
    google.maps.event.addListener(autocomplete, 'place_changed', function () 
    {
        var place = autocomplete.getPlace();        
        if (!place.geometry) {
            window.alert("Autocomplete's returned place contains no geometry");
            return;
        }    
        var address = '';
        if (place.address_components) {
            address = [
              (place.address_components[0] && place.address_components[0].short_name || ''),
              (place.address_components[1] && place.address_components[1].short_name || ''),
              (place.address_components[2] && place.address_components[2].short_name || '')
            ].join(' ');
        }
    
      
        //Location details
        for (var i = 0; i < place.address_components.length; i++) {
            if(place.address_components[i].types[0] == 'postal_code'){              
                document.getElementById('postal_code').value = place.address_components[i].long_name;
            }

            if(place.address_components[i].types[1] == 'political'){              
                document.getElementById('political').value = place.address_components[i].long_name;
            }

            if(place.address_components[i].types[0] == 'locality'){              
                document.getElementById('locality').value = place.address_components[i].long_name;
            } 
            if(place.address_components[i].types[1] == 'sublocality'){              
                document.getElementById('sublocality').value = place.address_components[i].long_name;
            } 
            if(place.address_components[i].types[0] == 'sublocality_level_1'){              
                document.getElementById('sublocality_level_1').value = place.address_components[i].long_name;
            } 
            if(place.address_components[i].types[0] == 'administrative_area_level_1'){              
                document.getElementById('administrative_area_level_1').value = place.address_components[i].long_name;
            } 
            if(place.address_components[i].types[0] == 'neighborhood'){              
                document.getElementById('neighborhood').value = place.address_components[i].long_name;
            }
            if(place.address_components[i].types[0] == 'route'){              
                document.getElementById('route').value = place.address_components[i].long_name;
            } 
            if(place.address_components[i].types[0] == 'country'){              
                document.getElementById('country').value = place.address_components[i].long_name;
            }          
        }

        document.getElementById('addressLat').value = place.geometry.location.lat();
        document.getElementById('addressLng').value = place.geometry.location.lng();
        document.getElementById('addressfield').value = place.formatted_address;
    });
}

google.maps.event.addDomListener(window, 'load', initialize_auto1);

  $(document).on("mouseleave", ".addressField", function(e){
  var lat = $('.latValue').val();

  if(lat == '')
  {
     $(".addressField").val('');
  }
});

$(document).on("keyup", ".apartmentField", function(e){
var lat = $('.latValue').val();
if(lat == '')
{
  $(".addressField").val('');
}
});

</script>

@endsection