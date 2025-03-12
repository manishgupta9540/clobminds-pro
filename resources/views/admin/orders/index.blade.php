
@extends('layouts.admin')

@section('content')
 <div class="container-fluid">
<div class="clearfix"></div>
   
  <div class="content-wrapper">
    <div class="container-fluid">
      <div class="row">
         <div class="col-12 col-lg-12 col-xl-12">
            @if(session()->has('message'))
              <div class="alert alert-success">
                  {{ session()->get('message') }}
              </div>
            @endif
          <h5 class="card-title"> Orders  </h5>
          <div class="card-body" style="padding: 15px 0px 25px;">       
            <a href="javascript:void();" class="card-link"> <i class="fa fa-upload" aria-hidden="true"></i> Export </a>
            <a class="btnn btn-fixer" href="{{route('/admin/customer/add')}}"> Create Order </a>  
            {{csrf_field()}}
          </div>
         <div class="card">
        <div class="carttype">
    
              <div class="carttye">
                      <!-- <ul class="nav nav-pills" role="tablist">
                        <li class="nav-item">
                          <a class="nav-link active" data-toggle="pill" href="#">  <span class="hidden-xs"> All </span></a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" data-toggle="pill" href="#pill-2">  <span class="hidden-xs"> Unfulfilled </span></a>
                        </li>
                         <li class="nav-item">
                          <a class="nav-link" data-toggle="pill" href="#pill-2"> <span class="hidden-xs"> Unpaid </span></a>
                        </li>               
                         <li class="nav-item">
                          <a class="nav-link" data-toggle="pill" href="#pill-2"> <span class="hidden-xs"> Open </span></a>
                        </li> 
                         <li class="nav-item">
                          <a class="nav-link" data-toggle="pill" href="#pill-2"> <span class="hidden-xs"> Closed </span></a>
                        </li> 
                         <li class="nav-item">
                          <a class="nav-link" data-toggle="pill" href="#pill-2"> <span class="hidden-xs"> More views </span></a>
                        </li>           
                      </ul> -->

                      <ul class="nav nav-tabs nav-pills">
                        <li class="active">
                          <a class="nav-link active" href="javascript:void(0);" data-toggle="tab" data-list-type="all">
                            <span class="hidden-xs"> All </span> 
                          </a> <!-- data-toggle="tab" href="#home" -->
                        </li>
                        
                        <li>
                          <a class="nav-link" href="javascript:void(0);" data-toggle="tab" data-list-type="pending">
                            <span class="hidden-xs">Pending</span>
                          </a> <!-- data-toggle="tab" href="#menu3" -->
                        </li>
                        
                      </ul>
                      <input type="hidden" id="list_type">
                </div>
              
              
              <div class="row row-searcher">
                
                <div class="col-md-3">
                  <!-- <a class="nav-link" data-toggle="pill" href="#piil-2" style="padding:0px">   -->
                    <span class="hidden-xs">
                      <i class="fa fa-search search-icon"></i>
                      <input type="text" class="form-control searching-custom" id="customer_order" placeholder="Filter order">
                    </span>
                  <!-- </a> -->
                </div>

                <div class="col-md-3">
                    <div class="">                        
                      <select class="form-control" id="order_status">
                        <option value="">Status</option>
                        <option value="1">Open</option>
                        <option value="2">Cancelled</option>
                      </select>
                    </div>
                </div>

                <div class="col-md-3">
                  <div class="">                    
                    <select class="form-control" id="payment_status">
                      <option value="">Payment status</option>
                      <option value="1">Paid</option>
                      <option value="0">Pending</option>
                      <option value="2">Refunded</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="">                    
                    <select class="form-control" id="order_sort_by">
                      <option value="">Sort</option>
                      <option value="1">Leatest Update (Newest first)</option>
                      <option value="2">Leatest Update (Oldest first)</option>
                      <option value="3">Order Amount(Hight to Low)</option>
                      <option value="4">Order Amount(Low to Hight)</option>
                    </select>
                  </div>
                </div>

              </div>
              

              <div class="tab-content">
                <div id="home" class="tab-pane in active">
                  <div id="ordersResult">
                    @include('admin.orders.ajax')   
                  </div> 
                </div>
                <div id="menu1" class="tab-pane fade">
                  <h3>Menu 1</h3>
                  <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                </div>
                <div id="menu2" class="tab-pane fade">
                  <h3>Menu 2</h3>
                  <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam.</p>
                </div>
                <div id="menu3" class="tab-pane fade">
                  <h3>Menu 3</h3>
                  <p>Eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</p>
                </div>
              </div>

              <!-- <div id="ordersResult">
                  @include('admin.orders.ajax')   
              </div> -->
              <!-- <div id="pill-2" class="tab-pane fade">
                <h2>tab2</h2>
              </div>
              <div id="pill-3" class="tab-pane fade">
                <h2>tab3</h2>
              </div>
              <div id="pill-4" class="tab-pane fade">
                <h2>tab4</h2>
              </div> -->
          
               </div>
            </div>
 
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
    <a href="javaScript:void();" class="back-to-top"><i class="fa fa-angle-double-up"></i></a>
    <!--End Back To Top Button-->
    <script type="text/javascript">
      $('.nav-link').on('click', function(){
          $('#list_type').val($(this).attr("data-list-type"));
          getOrders(0);
      });
    </script>
@endsection
