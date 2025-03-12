
@extends('layouts.admin')

@section('content')

<div class="container-fluid">
<div class="clearfix"></div>
   
  <div class="content-wrapper">
    <div class="container-fluid">
      <div class="row">
         <div class="col-12 col-lg-12 col-xl-12">
            <p><i class="fa fa-angle-left"></i> <a href="{{ url('/admin/orders') }}"> Orders</a> </p>
            <div class="top-details-list">
              <ul>
                <li> <h5 class="card-title">Order- #{{ $orders->id}}  </h5></li>
              <li>
              
              {{ \Carbon\Carbon::parse($orders->created_at)->format('d M Y H:i A') }} from Online Store</p></li>
             
              <li><span class="_21Z9T i4fQI _33uWB"> <span class="-EFlq"> </span> &nbsp; Sold</span></li>
            </ul>
            <ul>
              <li><a href="#"><i class="fa fa-print"></i> Print Invoice</a></li>
              <!-- <li><a href="#"><i class="fa fa-reply"></i> Restock</a></li> -->
              <!-- <li><a href="#"><i class="fa fa-pencil"></i> Edit</a></li> -->
              <li><div class="dropdown">
                  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-expanded="false">
                    More Actions
                  </button>
                  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    
                    <a class="dropdown-item deleteOrder" href="javaScript:;" table_name="orders" data-value="2" row-id="{{$orders->id}}"> <i class="fa fa-server"></i> Update on Server </a>
                   
                 <!--    <a class="dropdown-item" href="#">Unarchive</a>
                    <a class="dropdown-item" href="#">View Order Status page</a>
                    <a class="dropdown-item" href="#">Edit Order</a>
                    <a class="dropdown-item" href="#">Send payment Request</a> -->
                  </div>
                </div>
              </div>
            </li>
            </ul>
          <div class="row">
             <div class="col-md-8 pd-left-0">
                 <div class="card">
                     <div class="card-body orders-ui-listing orders-ui-listing1">
                        <h2><i class="fa fa-check-circle checking-ui-circle"></i> Updated on Server <span>09:00 AM</span></h2>
                        {{-- <p>bluedart tracking</p>
                        <p><a>89899988777</a></p> --}}

                        <div class="spacer-20"></div>
                        <table class="orders-details-table table">
                          <tbody>
                          <?php $sum_tot_Price = 0;$tax = 0;
                            switch ($orders->currency) 
                            {
                              case $orders->currency == 'INR':
                                  $currency = 'Rs.';
                                  break;
                            }
                            ?>
                          @foreach ($cutomerOrsers as $productdetail)
                          <tr>
                              <td>
                              <?php 
                              if($productdetail->parent_id == '')
                              {
                                $product_image = Common:: get_col_c3('product_images','product_id',$productdetail->id,'position',1,'is_active',1,'feature_image'); 
                                $title= $productdetail->title;
                              }
                              else
                              {
                                $product_image = Common:: get_col_c3('product_images','varient_id',$productdetail->id,'position',1,'is_active',1,'feature_image'); 
                                $title= $productdetail->title.' - '.$productdetail->color.' /'.$productdetail->name;
                              }
                              ?>
                              
                                
                                @if($productdetail->feature_image == NULL)
                                  <a href=""><img style="height: 70px; width: 100px;" src="{{asset('admin/assets/images/dummy-img.svg')}}" alt="Collection image" class="product-img"> </a>
                                @else
                                <img style="height: 70px; width: 100px;" src="{{asset('uploads/product-images/')}}/{{$productdetail->feature_image}}">
                                @endif
                                <span class="badge badge22">{{$productdetail->quantity}}</span>
                              </td>
                              <td class="width-table-manage">
                                <p><a href="#">{{ $title }}</a></p>
                                <p><span><?php if($productdetail->discount!=0){echo '('.$productdetail->discount.' % off )'; }?><br>
                                  SKU: {{ $productdetail->sku }}</span></p>
                              </td>
                              <td>{{ '$'.$productdetail->item_price }} x {{ $productdetail->quantity }} </td>
                              <td>{{'$'.number_format(round(($productdetail->total_amount),2),2)}}</td>
                            </tr> 
                            <?php $sum_tot_Price += $productdetail->item_price; ?>
                            <?php $tax += $productdetail->tax_amount; ?>

                             @endforeach
                           
                            
                          </tbody>
                        </table>

                        {{-- <div class="dropdown" style="float:right;">
                          <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-expanded="false">
                            More 
                          </button>
                          <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            
                            <a class="dropdown-item" href="#">Update on Server</a>
                            
                          </div>
                        </div> --}}

                    </div>
                </div>
                <!-- end 1st card -->

                <div class="card">
                     <div class="card-body orders-ui-listing orders-ui-listing1">
                        <h2><i class="fa fa-check-circle checking-ui-circle"></i> Paid</h2>
                        

                        <div class="spacer-20"></div>
                        <table class="orders-details-table">
                          <tbody>
                            <tr>
                              <td>Subtotal</td>
                              <td>{{ count($cutomerOrsers)}} items</td>
                              <td align="right">{{' $'.number_format(round(($sum_tot_Price),2),2)}}</td>
                            </tr>
                            <tr>
                              <td>Discount</td>
                              <td></td>
                              <td align="right">-{{ '$'.$orders->discount_amount}}.00</td>
                            </tr>

                            <tr>
                              <td>Tax</td>
                              <td></td>
                              <td align="right">{{ ' $'.$orders->tax_amount }}.00</td>
                            </tr>
                            <tr>
                              <td>Total</td>
                              <td></td>
                              <td align="right">{{' $'.number_format(round(($orders->total_amount + $orders->delivery_charges +  $orders->tax_amount),2),2)}}</td>

                            </tr>
                            <tr>
                              <td>Paid by customer</td>
                              <td></td>
                              <td align="right">{{' $'.number_format(round(($orders->total_amount + $orders->delivery_charges +  $orders->tax_amount),2),2)}}</td>
                            </tr>
                            
                          </tbody>
                        </table>
                    </div>
                </div>
                <!-- 2nd card end here -->
               
          </div>
            <div class="col-md-4">
              <div class="card">
                  <div class="card-body ps-relative">
                    <h6>Note</h6>
                    <a href="#" class="basic-link" data-toggle="modal" data-target="#exampleModal1">Edit</a>
                    <p><?php if($orders->custom_input_request !=''){ echo $orders->custom_input_request; }else{ echo "Not Request"; }; ?></p>
                  </div>
              </div>

              <div class="card">
                  <div class="card-body ps-relative">
                    <h6>Customer</h6>
                      <img src="{{ asset('admin/assets/images/avatars/')}} " class="user-icn">
                    <p><a href="">{{ucfirst($orders->first_name).' '.ucfirst($orders->last_name)}}</a></p>
                    <p>{{ count($cutomerOrsers)}} Order</p>
                  </div>
                  <hr class="linings">
                  <div class="card-body ps-relative">
                    <h6>Contact Information</h6>
                    <a href="#" class="basic-link" data-toggle="modal" data-target="#exampleModal2">Edit</a>
                    <p>{{ $orders->email}}</p>
                    <p>{{ $orders->phone_number}}</p>
                  </div>

                  <hr>
                  <div class="card-body ps-relative">
                    <h6>Address</h6>
                    <a href="#" class="basic-link" data-toggle="modal" data-target="#exampleModal3">Edit</a>
                    <p>{{ ucfirst($shipping_address->first_name).' '.$shipping_address->last_name }}</p>
                     <p> {{ $shipping_address->address_line1}} {{ $shipping_address->zipcode}}</p>
                     <p>{{ $shipping_address->phone_number}}</p>

                     <!-- <a href="#">View map</a> -->
                  </div>
     
              </div>

            
            </div>
          </div>
 
         </div>
 
      </div><!--End Row-->

 
          <!-- modal for notes -->

        <div class="modal fade" id="exampleModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Note</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <label>Note</label>
              <textarea class="form-control" rows="3"></textarea>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary">Save</button>
              </div>
            </div>
          </div>
        </div>

<!-- modal for notes end here -->

<!-- modal for Contact information -->

            <div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Contact Information</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <div class="form-group">
                      <label>Email Address</label>
                      <input type="text" class="form-control" name="">
                    </div>

                    <div class="form-group">
                      <label>Phone Number</label>
                      <input type="text" class="form-control" name="">
                    </div>
                    <div class="form-group">
                      <label class="form-check-label">
                        <input class="form-check-input" type="checkbox"> Update Customer Profile
                      </label>
                    </div>
                    
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Save</button>
                  </div>
                </div>
              </div>
            </div>

<!-- modal for Contact inforamtion end here -->


<!-- modal for Contact modal3 -->

                  <div class="modal fade" id="exampleModal3" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLabel">Edit address</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">
                          <div class="form-group">

                              <div class="dropdown">
                                <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-expanded="false">
                                  Select another address 
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                  <a class="dropdown-item" href="#">Prachi Thakur<br>
                                                              F-16 Naraina vihar<br>
                                                              110028 Delhi DL<br>
                                                              India</a>
                                  <a class="dropdown-item" href="#">Prachi Thakur<br>
                                                              F-16 Naraina vihar<br>
                                                              110028 Delhi DL<br>
                                                              India</a>
                                  
                                </div>
                          </div>

                      <div class="row">
                          <div class="col-md-6">
                            <label>First Name</label>
                            <input type="text" class="form-control" name="">
                          </div>

                          <div class="col-md-6">
                            <label>Last Name</label>
                            <input type="text" class="form-control" name="">
                          </div>
                      </div>
                      <div class="row">
                          <div class="col-md-6">
                            <label>Company</label>
                            <input type="text" class="form-control" name="">
                          </div>

                          <div class="col-md-6">
                            <label>Phone Number</label>
                            <input type="text" class="form-control" name="">
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-6">
                            <label>Address</label>
                            <input type="text" class="form-control" name="">
                          </div>

                          <div class="col-md-6">
                            <label>Apartment, Suite</label>
                            <input type="text" class="form-control" name="">
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-6">
                            <label>City</label>
                            <input type="text" class="form-control" name="">
                          </div>
                          <div class="col-md-6">
                            <label>Country/ Region</label>
                            <input type="text" class="form-control" name="">
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-6">
                            <label>State</label>
                            <input type="text" class="form-control" name="">
                          </div>

                          <div class="col-md-6">
                            <label>Zip/ Postel Code</label>
                            <input type="text" class="form-control" name="">
                          </div>
                          </div>

                      </div>
                          
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                          <button type="button" class="btn btn-primary">Save</button>
                        </div>
                      </div>
                    </div>
                  </div>

<!-- modal for Contact madal3 -->



       <!--End Dashboard Content-->
      <!--start overlay-->
     <div class="overlay toggle-menu"></div>
   <!--end overlay-->
    </div>
    <!-- End container-fluid-->
    </div>
    </div><!--End content-wrapper-->
   <!--Start Back To Top Button-->
    <a href="javaScript:void();" class="back-to-top"><i class="fa fa-angle-double-up"></i> </a>
    <!--End Back To Top Button-->

<script type="text/javascript">
    $(document).on('click','.deleteOrder',function(){
  var row_id = $(this).attr("row-id");
  var data_value = $(this).attr("data-value");
  var table_name = $(this).attr("table_name");
  
  if(confirm("Are you sure?"))
  {
    $.ajax({
    url:'{{route("/admin/deleteOrder")}}',
    data:{'row_id':row_id,'table_name':table_name,'data_value':data_value,"_token": "{{ csrf_token() }}"},
    type:"post",
    success:function(response)
    {
      if(data = 1)
      {   
        location.assign('{{route("/admin/orders")}}')
      }
    }
    });
  }
});
</script>
@endsection