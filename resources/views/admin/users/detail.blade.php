
@extends('layouts.admin')

@section('content')

 <style>
 th {
    border-top: 0px!important;
       font-size: 12px;
    font-weight: 300!important;
   padding: 3px 15px!important;
}

button.btn.btn-light.waves-effect.waves-light.m-1 {
    padding: 10px 10px;
}

button.btn.btn-info.btn-round.waves-effect.waves-light.m-1 {
    float: right;
}

td {
    border-top: 0px!important;
   padding: 3px 15px!important;
}

p.arrow {
    font-size: 18px;
}

p.arrow a {
    color: #000;
}

label.form-check-label {
    margin-top: 0px;
}

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
}

.pac-container { z-index: 100000; }

 </style>

<div class="clearfix"></div>
<div class="content-wrapper">
<div class="container-fluid">
<div class="row">
<div class="col-12 col-lg-12 col-xl-12">
    @if(session()->has('addressAdded'))
        <div class="alert alert-success">
           <center> {{ session()->get('addressAdded') }}</center>
        </div>
    @endif
<p class="arrow"> <span> <i class="fa fa-angle-left"></i> </span> <span><a href="{{route('/admin/customers')}}"> Customers </a> </span> </p>

   <h5 class="card-title"> {{ucfirst($customer->first_name).' '.ucfirst($customer->last_name)}}</h5>
   <hr>
   <div class="row">
      <!--        <div class="col-12 col-lg-12 col-xl-12">  
         <div class="card-body" style="padding: 0px">
            <div class="card-body">
              
               <i class="icon-envelope mr-2" data-toggle="modal" data-target="#largesizemodal"><span style="font-family: sans-serif;"> Send account invite </span></i>
         
                  <div class="modal fade" id="largesizemodal" style="display: none;" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title">  Send account invite</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                          </button>
                        </div>
                        <div class="modal-body">
           <div class="card-body">
              <form>
                <div class="row">
             <div class="col-md-6">
              <label for="input-7">To </label>
            <input type="text" class="form-control form-control-rounded" id="input-7" placeholder="Enter Your Name">
             </div>
              <div class="col-md-6">
              <label for="input-7"> From </label>
            <input type="text" class="form-control form-control-rounded" id="input-7" placeholder="Enter Your Name">
             </div>          
             <div class="col-md-12">
              <label for="input-8">Subject </label>
              <input type="text" class="form-control form-control-rounded" id="input-8" placeholder="Enter Your Email Address">
             </div>
         
             <div class="col-md-12">
              <label for="input-8"> Custom message for this customer </label>
              <input type="text" class="form-control form-control-rounded" id="input-8" placeholder="Enter Your Email Address">
              <p> This template can be edited in notifications. </p>
              <p> Send bcc to: </p>
             </div>
         
             <div class="col-md-12">
               <div class="icheck-primary">
              <input type="checkbox" id="user-checkbox3" checked="">
              <label for="user-checkbox3"> mustafa53.forever52@gmail.com </label>
              </div>
             </div>
            </form>
          </div>
           </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> Cencel </button>
                          <button type="button" class="btn btn-primary"><i class="fa fa-check-square-o"></i>  Review Email</button>
                        </div>
                      </div>
                    </div>
                  </div>
                <div class="btn-group m-1" style="box-shadow: none;">
                    <button type="button" class="btn btn-outline-primary waves-effect waves-light" style="border: none;">  <span> more actions</span> </button>
                    <button type="button" class="btn btn-outline-primary dropdown-toggle waves-effect waves-light" data-toggle="dropdown" aria-expanded="false"style="border: none;    margin-left: -50px;">
                      <span class="caret"></span>
                    </button>
                    <div class="dropdown-menu" style="">
                      <a href="javaScript:void();" class="dropdown-item">Action</a>
         
                    </div>
                  </div>
         
              </div>
         </div>
         
         
            </div>  -->
      <div class="col-12 col-lg-8 col-xl-8">
         <div class="card">
            <div class="row" style="margin-top: 25px;">
               <div class="col-md-1">
                  <a href="javascript:void();" style="font-size: 40px;
                     padding: 10px;"><i aria-hidden="true" class="fa fa-telegram"></i>  </a> 
               </div>
               <div class="col-md-5">
                  <b> {{ucfirst($customer->first_name).' '.ucfirst($customer->last_name)}} </b><br>
                  @if(!empty($address))
                  <span>{{ ucfirst(Common::get_single_col('states','id',$address->state,'name')).', '.ucfirst(Common::get_single_col('states','id',$address->state,'name')).', '.ucfirst(Common::get_single_col('countries','id',$address->country,'name')) }}<br>
                  </span>
                  @endif
                  <span>Customer for {{ \Carbon\Carbon::parse($customer->created_at)->diffForHumans() }}</span>
               </div>
            </div>
            <div class="col-12 col-lg-12 col-xl-12">
               <div class="place" style="padding: 20px;">
                  <label> Customer Notes </label>
                  <input type="text" value="{{ucfirst($customer->notes)}}" class="form-control" placeholder="search product">
               </div>
            </div>
<!--             <div class="entry">
               <div class="place" style="padding: 20px;">
                  <div class="col-12 col-lg-12 col-xl-12">
                     <div class="table-responsive">
                        <table class="table">
                           <tbody>
                              <tr>
                                 <th> Last Order </th>
                                 <td> Total spent to date </td>
                                 <td> Average order value </td>
                              </tr>
                              <tr>
                                 <td> Add shipping</td>
                                 <td> Add shipping</td>
                                 <td> Add shipping</td>
                              </tr>
                              <tr>
                                 <td> Add shipping</td>
                                 <th> <a href="#"> Add shipping    </a>    </th>
                                 <td> Add shipping</td>
                                 <td> Add shipping</td>
                              </tr>
                           </tbody>
                        </table>
                     </div>
                  </div>
               </div>
            </div>
            <hr>
            <div class="place" style="padding: 20px;">
               <h6> Orders placed </h6>
               <p> <a href="#">Order #1212 </a><span style="float: right;"> 7:11 am at 07:11 am </span></p>
               <p> AED 112.60 from Online Store </p>
               <span class="_21Z9T i4fQI _33uWB"> <span class="-EFlq"> </span>Unfulfilled</span>
               <div class="table-responsive">
                  <table class="table">
                     <tbody>
                        <tr>
                           <td> <img src="assets\images\products\01.png" class="product-img" alt="product img"> Top Selling Country Iphone 5 </td>
                           <td> <img src="assets\images\products\01.png" class="product-img" alt="product img"> Top Selling Country Iphone 5 </td>
                        </tr>
                     </tbody>
                  </table>
                  <p> <a href="#">View this order (3 more products)</a> </p>
               </div>
            </div> -->
         </div>

         <div class="card">
            <div class="col-12 col-lg-12 col-xl-12">
               <div class="place" style="padding: 20px;">
                  <p> Orders placed</p>
                  <center><span> <i class="fa fa-ban" style="font-size: 30px"></i> </span>
                  <div> This customer hasn’t placed any order yet </div></center>
               </div>
            </div>
         </div>
         <!--  <div class="card-body"> 
            <p> <a href="#"> Timeline </a> </p>
             <div class="form-check" style="float: right;margin-top: -40px;">
                  <input class="form-check-input" type="checkbox" value="" id="defaultCheck1" style="margin-top: 8px;">
                  <label class="form-check-label" for="defaultCheck1">
                  Show Comments 
                  </label>
                </div>
            
               <hr>
                           <div class="input-group mb-3">
                                  <input type="text" class="form-control" placeholder="some text">
                                  <div class="input-group-append">
                                    <span class="input-group-text"> Post</span>
                                  </div>
                                 </div>   
               <h5> Today</h5>
               <p> Order Confirmation email for order #1212 sent to this customer (amal_mohamed50555@yahoo.com).</p>           
               
            </div>   -->
      </div>
      <div class="col-12 col-lg-4 col-xl-4">
         <div class="card">
            <div class="card-body">
               <div class="col-12 col-lg-12 col-xl-12">
                  <p>Customer overview 
                    <span> <a href="javascript:;" class="editProfileClick" value="{{$customer->id}}" style="float: right"> Edit </a></span> </p>
                   <p> <a href="javaScript:;">{{$customer->email}}</a> </p>
                   <p> <a href="javaScript:;">{{$customer->phone_number}}</a> </p>
                  <!-- <p> No account </p> -->

                  </div>
                  <hr>
                  <p> DEFAULT ADDRESS @if(empty($address))<span style="float: right;"> <a href="#"data-toggle="modal" data-target="#addNewAddressModal"> Add</a> </span>@endif</p>
                  @if(!empty($address))
                  <p style="margin-bottom: 0px;font-size: 13px;">{{ucfirst($address->first_name.' '.$address->last_name)}}</p>
                  <p style="margin-bottom: 0px;font-size: 13px;">{{ucfirst($address->address_line1)}}</p>
                  <p style="margin-bottom: 0px;font-size: 13px;">{{ucfirst($address->apartment)}}</p>
                  <p style="margin-bottom: 0px;font-size: 13px;">{{ucfirst(Common::get_single_col('states','id',$address->state,'name')).', '.ucfirst(Common::get_single_col('countries','id',$address->country,'name'))}}</p>
                  <p style="font-size: 13px;">{{ $address->phone_number }}</p>
                  <p><a href="#"data-toggle="modal" data-target="#addNewAddressModal"> Add New Address </a> </p>
                  @else
                  <p style="font-size: 13px;">No address provided</p>
                  @endif
                 

                  </div>
               </div>
          
        
         <div class="card">
            <div class="card-body">
               <div class="col-12 col-lg-12 col-xl-12">
                  <p> Email Marketing <span style="float: right;">  @if($customer->is_customer_subscribed == 1)<a href="#" data-toggle="modal" data-target="#largesizemodal3"> Edit Status  </a>@endif </span></p>
                  @if($customer->is_customer_subscribed == 1)<span class="_21Z9T i4fQI _33uWB" style="background:#a2fda6">Subscribed</span>@else <span class="_21Z9T i4fQI _33uWB" style="background: #d7e2ee;"> Not subscribed</span>@endif
                  <p> Subscribed {{ \Carbon\Carbon::parse($customer->created_at)->diffForHumans() }} </p>

                  </div>
               </div>
            </div>
         </div>
          </div>
            </div>
         <!-- <div class="card-body" style="box-shadow: 0px 0px 1px 1px #e6e7f8">
            <p> Tag  <span style="float: right;"> <a href="#" data-toggle="modal" data-target="#largesizemodal3"> View All Tag  </a> </span></p>
            <input type="text" id="basic-input" class="form-control" placeholder="VIP, sale,">
            </div>
            
            
            <div class="card-body" style="box-shadow: 0px 0px 1px 1px #e6e7f8">
            <h5> Customer privacy </h5>
            <h6> REQUEST CUSTOMER DATA </h6>
            <p>Get a copy of this customer's data by email so you can forward it to them.<a href="#"> Learn more about requesting customer data. </a></p>
            <span class="input-group-text bootstrap-touchspin-postfix"> <a href="#">Request Customer Data </a></span>
            
            </div>
            <div class="card-body" style="box-shadow: 0px 0px 1px 1px #e6e7f8">
            <h5> RASE PERSONAL DATA </h5>
            <h6> REQUEST CUSTOMER DATA </h6>
            <p>Get a copy of this customer's data by email so you can forward it to them.<a href="#"> Learn more about requesting customer data. </a></p>
            <span class="input-group-text bootstrap-touchspin-postfix"> <a href="#">Request Customer Data </a></span>
            
            </div> -->
      </div>
      <button type="button" class="btn btn-danger btn-round waves-effect waves-light m-1 deleteUser" table_name="users" row-id="{{$customer->id}}"> Delete Customer</button> 
      <!--End Dashboard Content-->
      <!--start overlay-->
      <div class="overlay toggle-menu"></div>
      <!--end overlay-->
   </div>
   <!-- End container-fluid-->
</div>
<!--End content-wrapper-->
<!--Start Back To Top Button-->
<a href="javaScript:void();" class="back-to-top"><i class="fa fa-angle-double-up"></i> </a>
<!--End Back To Top Button-->



<!--modal for edit user information -->

              <div class="modl">
                    <div class="modal fade" id="edit-profile-modal" tabindex="-1" role="dialog" aria-labelledby="edit-profile" aria-hidden="true">
                     <div class="modal-dialog modal-dialog-centered">
                        <form method="post" action="{{route('/admin/updateUserDetail')}}" id="editProfileForm">
                          @csrf
                        <div class="modal-content">
                           <div class="modal-header">
                              <h5 class="modal-title">   Edit customer </h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">×</span>
                              </button>
                           </div>
                           <div class="modal-body">
                              <div class="card-body">
                                    <div class="row">
                                       <div class="col-md-6">
                                          <label for="edit-input-1"> First Name <span class="text-danger">*</span></label>
                                          <input type="text" name="first_name" class="form-control form-control-rounded editFirstName" id="edit-input-1" placeholder=" ">
                                       </div>
                                       <div class="col-md-6">
                                          <label for="edit-input-2"> Last Name</label>
                                          <input type="text" name="last_name" class="form-control form-control-rounded editLastName" id="edit-input-2" placeholder=" ">
                                       </div>
                                       <div class="col-md-12">
                                          <label for="edit-input-3"> Email <span class="text-danger">*</span></label>
                                          <input type="text" class="form-control form-control-rounded editEmail" name="email" id="edit-input-3" placeholder=" ">
                                       </div>
                                        <div class="col-md-12">
                                          <label for="edit-input-4"> Phone number <span class="text-danger">*</span></label>
                                            <div class="input-group mb-3">
                                            <span class="input-group-addon">+91</span>
                                            <input type="text" id="edit-input-4" name="phone_number" class="form-control editMobile only_no">
                                          </div>
                                       </div>
                                       <input type="hidden" class="editUserId" name="user_id">
                                 
                                 </div>
                              </div>
                           </div>
                           <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">  Cancel </button>
                              <button type="submit" class="btn btn-primary">  Save </button>
                           </div>
                           </form>
                        </div>
                     </div> 
                     </div> 

              <!--modal for add new address -->
              <div class="modl">
                  <div class="modal fade" id="addNewAddressModal" style="display: none;" aria-hidden="true">
                     <div class="modal-dialog modal-dialog-centered" style="max-width: 630px!important;">

                        <form method="post" action="{{route('/admin/customer/addUserAddress')}}" id="addAddressForm">
                          @csrf
                        <div class="modal-content">
                           <div class="modal-header">
                              <h5 class="modal-title">   Add new address </h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">×</span>
                              </button>
                           </div>
                           <div class="modal-body">
                              <div class="card-body" style="padding: 0px 10px;">
                                    <div class="row">
                                       <div class="col-md-6">
                                          <label for="input-7"> First Name <span class="text-danger">*</span></label>
                                          <input type="text" class="form-control form-control-rounded" name="address_first_name" id="input-7">
                                       </div>
                                       <div class="col-md-6">
                                          <label for="input-8"> Last Name</label>
                                          <input type="text" class="form-control form-control-rounded" id="input-8" name="address_last_name">
                                       </div>
                                       <div class="col-md-12">
                                          <label for="input-8"> Company  </label>
                                          <input type="text" class="form-control form-control-rounded" id="input-8" name="company_name">
                                       </div>
                                       <div class="col-md-12">
                                          <label for="input-9"> Address <span class="text-danger">*</span></label>
                                          <input type="text" class="form-control form-control-rounded addressField" name="address_line1" id="address1" id="input-9" placeholder="">
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
                                           <input type="hidden" name="user_id" value="{{$customer->id}}">  
                                       </div>
                                       <div class="col-md-6">
                                           <label for="input-10"> Apartment, suite, etc.  <span class="text-danger">*</span></label>
                                          <input type="text" class="form-control form-control-rounded apartmentField" id="input-10" name="apartment">
                                       </div>
                                       <div class="col-md-6">
                                          <label for="input-11"> City <span class="text-danger">*</span></label>
                                          <input type="text" class="form-control form-control-rounded" name="city" id="input-11">
                                       </div>
                                       <div class="col-md-6">
                                          <label> Country/Region <span class="text-danger">*</span></label>
                                            <select class="form-control" name="country">
                                              <option value="">Select country</option>
                                              @foreach($countries as $cou)
                                              <option value="{{$cou->id}}">{{$cou->name}}</option>
                                              @endforeach
                                            </select>
                                       </div>
                                        <div class="col-md-6">
                                          <label> States <span class="text-danger">*</span></label>
                                          <select class="form-control" name="state">
                                            <option value="">Select state</option>
                                            @foreach($states as $sta)
                                            <option value="{{$sta->id}}">{{$sta->name}}</option>
                                            @endforeach
                                          </select>
                                       </div>
                                          <div class="col-md-6">
                                          <label for="input-12"> Postal code <span class="text-danger">*</span></label>
                                          <input type="text" class="form-control form-control-rounded" name="zipcode" id="input-12">
                                       </div>
                                       <div class="col-md-6">
                                          <label for="input-25"> Phone <span class="text-danger">*</span></label>
                                            <div class="input-group mb-3">
                                            <span class="input-group-addon">+1</span>
                                            <input type="text" id="input-25" name="primary_mobile_number" class="form-control only_no">
                                          </div>
                                       </div>
                                 </div>
                              </div>
                           </div>
                           <div class="modal-footer">
                              <button type="button" class="btn btn-secondary clrBtn" data-dismiss="modal">  Cancel </button>
                              <button type="submit" class="btn btn-primary">  Save </button>
                           </div>
                        </div>
                      </form>
                     </div> 
                     </div> 
                     </div> 


                    <!--modal for email subscription -->

                    <div class="modl3">
                  <div class="modal fade" id="largesizemodal3" style="display: none;" aria-hidden="true">
                     <form action="" method="post">
                      @csrf
                     <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                           <div class="modal-header">
                              <h5 class="modal-title">   Edit email marketing status </h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">×</span>
                              </button>
                           </div>
                           <div class="modal-body">
                              <div class="card-body">
                                <div class="row">
                                       <div class="col-md-12">
                                          <div class="form-check">
                                             <input class="form-check-input" type="checkbox" value="" id="defaultCheck1" style="margin-top: 8px;" checked="">
                                             <label class="form-check-label" for="defaultCheck1">
                                             Customer agreed to receive marketing emails.
                                             </label>
                                             <input type="hidden" name="user_id" value="{{$customer->id}}">
                                          </div>
                                       </div>
                                 </div>
                              </div>
                           </div>
                           <div class="modal-footer">
                              <button type="submit" class="btn btn-primary">  Save </button>
                           </div>
                        </div>
                      </form>
                     </div>
                 </div>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAvFpaBTE6GipLprVwhOkgtvUaAjo1sYQU&amp;libraries=places" type="text/javascript"></script>
<script type="text/javascript">

    $('#addAddressForm').on('keydown', '.only_no', function(e){-1!==$.inArray(e.keyCode,[46,8,9,27,13,110,190])||/65|67|86|88/.test(e.keyCode)&&(!0===e.ctrlKey||!0===e.metaKey)||35<=e.keyCode&&40>=e.keyCode||(e.shiftKey||48>e.keyCode||57<e.keyCode)&&(96>e.keyCode||105<e.keyCode)&&e.preventDefault()});

     $('#editProfileForm').on('keydown', '.only_no', function(e){-1!==$.inArray(e.keyCode,[46,8,9,27,13,110,190])||/65|67|86|88/.test(e.keyCode)&&(!0===e.ctrlKey||!0===e.metaKey)||35<=e.keyCode&&40>=e.keyCode||(e.shiftKey||48>e.keyCode||57<e.keyCode)&&(96>e.keyCode||105<e.keyCode)&&e.preventDefault()});

    $('.clrBtn').click(function(){
    $("#addAddressForm")[0].reset();
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

    $("#addAddressForm").validate({
    rules:{
    address_first_name:{
      required:true
    },           
    address_line1:{
      required:true
    },
    apartment:{
      required:true
    },
    city:{
      required:true
    },
    country:{
      required:true
    },
    state:{
      required:true
    },
    zipcode:{
      required:true
    },
    primary_mobile_number:{
      required:true
    }
   },
    errorPlacement: function(error, element) 
   {

    error.insertAfter(element);
   },
   messages:{
    address_first_name:{
      required:"First name can not be empty"
    }, 
    address_line1:{
      required:"Address can not be empty"
    }, 
    apartment:{
      required:"Apartment can not be empty"
    }, 
    city:{
      required:"City can not be empty"
    },  
    country:{
      required:"Country can not be empty"
    }, 
    state:{
      required:"State can not be empty"
    },
    zipcode:{
      required:"Zip code can not be empty"
    },
    primary_mobile_number:{
      required:"Phone no. can not be empty"
    } 
     
 }
});

  $(document).on('click','.deleteUser',function(){
  var row_id = $(this).attr("row-id");
  var table_name = $(this).attr("table_name");
  if(confirm("Are you sure?"))
  {
    $.ajax({
    url:'{{route("/admin/deleteAccount")}}',
    data:{'row_id':row_id,'table_name':table_name,"_token": "{{ csrf_token() }}"},
    type:"post",
    success:function(response)
    {
      if(data = 1)
      {   
        location.assign('{{route("/admin/customers")}}')
      }
    }
    });
  }
});

$(document).on('click','.editProfileClick',function(){
  var row_id = $(this).attr("value");
  $.ajax({
  url:'{{route("/admin/getUserDetail")}}',
  data:{'id':row_id,"_token": "{{ csrf_token() }}"},
  type:"post",
  success:function(response)
  {
     var obj = JSON.parse(response);
      if(obj.status == true)
      {
         $('.editUserId').val(row_id);
        $('.editFirstName').val(obj.data.first_name);
        $('.editLastName').val(obj.data.last_name);
        $('.editMobile').val(obj.data.phone_number);
        $('.editEmail').val(obj.data.email);         
      }
  }
  });
  $('#edit-profile-modal').modal();
   $("#editProfileForm").validate({
     

       rules:{
      first_name:{
        required:true
      },
       email:{
        required:true,
        remote: {
                  data:{"id":row_id,"email":$("#email" ).val(),"_token": "{{ csrf_token() }}"},
                  url: '{{route("/admin/isEmailExist")}}',
                  type: "post"
                }
      }, 
      phone_number:{
        required:true
      }
     },
      errorPlacement: function(error, element) 
     {

      error.insertAfter(element);
     },
     messages:{
      first_name:{
        required:"Please Enter first name"
      },
       email:{
        required:'Please Enter Email',
        remote:"Email Already Exist !"
      },
      phone_number:{
        required:"Please Enter contact no"
      }  
    }
    
    }); 
});
</script>
@endsection