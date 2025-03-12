@extends('layouts.admin')
@section('content')
<style>
   #user_task_assign{
      /* overflow-x: hidden; */
      /* overflow-y: hidden; */
      z-index: 999;
      padding-top: 0px;
      /* margin:auto; */
   }
   #user_task_assign .modal-dialog.modal-lg{
      max-width: 90% !important;
      width: 100%;
      padding: 0px;
      left: 3.5%;
   }
   #user_task_assign .modal-content {
      margin: auto;
      display: block;
      width: 100%;
      max-width: 1270px;
   
   }
   /* .col-sm-12.app_status .select2.select2-container.select2-container--default.select2-container--below.select2-container--focus {
      z-index: 9999999!important;
      display: block;
   } */
</style>
<div class="main-content-wrap sidenav-open d-flex flex-column">
   <!-- ============ Body content start ============= -->
   <div class="main-content">
                  @php
                     // $ADD_ACCESS    = false;
                     $REASSIGN_ACCESS   = false;
                     $VIEW_ACCESS   = false;
                     $DASHBOARD_ACCESS =  false;
                     $DASHBOARD_ACCESS    = Helper::can_access('Dashboard','');//passing action title and route group name
                     // $ADD_ACCESS    = Helper::can_access('Create Task','');//passing action title and route group name
                     $REASSIGN_ACCESS    = Helper::can_access('Reassign','');//passing action title and route group name
                     $VIEW_ACCESS   = Helper::can_access('View Task','');//passing action title and route group name
                     @endphp
      <div class="row">
         <div class="col-sm-11">
             <ul class="breadcrumb">
             @if($DASHBOARD_ACCESS)
             <li>
             <a href="{{ url('/home') }}">Dashboard</a>
             </li>
             <li>Task</li>
             @else
             <li>Task</li>
             @endif
             </ul>
         </div>
         <!-- ============Back Button ============= -->
         <div class="col-sm-1 back-arrow">
             <div class="text-right">
             <a href="{{ url()->previous() }}"><i class="fas fa-arrow-circle-left fa-2x"></i></a>
             </div>
         </div>
     </div>
      <div class="row">
         {{-- <div class="col-md-12"> --}}
         <div class="card text-left">
            <div class="card-body">
               <div class="row">
                  <div class="col-md-12">
                     <ul class="nav nav-tabs nav-tabs-bottom">
                        <li class="nav-item"><a href="{{url('/task')}}" class="nav-link ">All Tasks</a></li>
                        <li class="nav-item"><a href="{{url('/task/assign')}}" class="nav-link active">Assigned Tasks</a></li>
                        <li class="nav-item"><a href="{{url('/task/unassign')}}" class="nav-link">Unassigned Tasks</a></li>
                        <li class="nav-item"><a href="{{url('/task/complete')}}" class="nav-link">Completed Tasks</a></li>
                        {{-- <li class="nav-item"><a href="{{url('/task/vendor')}}" class="nav-link">Vendor Tasks</a></li> --}}
                     </ul>
                  </div>
                  @if ($message = Session::get('success'))
                     <div class="col-md-12">   
                        <div class="alert alert-success">
                           <strong>{{ $message }}</strong> 
                        </div>
                     </div>
                  @endif
                    
                  <div class="col-md-8 mt-2">
                     {{-- <h4 class="card-title mb-1"> Tasks</h4>
                     <p> List of all Task </p> --}}
                  </div>
                 
                  <div class="col-md-4 mt-2">
                     <div class="btn-group" style="float:right">
                      
                       <a href="#" class="filter0search"><i class="fa fa-filter"></i></a>   
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-4 form-group mb-3">
                     <label for="picker1"> Export </label>
                     <select class="form-control check"  id="check">
                        <option value="">-Select-</option>
                        <option value="pdf">Excel</option>   
                     </select>
                  </div>
                  <div class="col-md-5 form-group mt-4">
                        <a class="btn-link " id="exportExcel" href="javascript:;"> <i class="far fa-file-archive"></i> Download Excel</a> 
                        <p style="margin-bottom:2px;" class="load_container text-danger" id="loading"></p>
                  </div>
                     <div class="col-md-2 form-group mt-4" >
                        <label for="picker1" style="float: right;"><strong>Numbers of Rows:-</strong>  </label>
                     </div>
                     <div class="col-md-1 form-group mt-3" >
                        <select class="form-control rows"  id="rows">
                           <option value="">-Select-</option>
                           <option value="25">25</option>   
                           <option value="50">50</option> 
                           <option value="100">100</option> 
                           <option value="150">150</option> 
                           <option value="200">200</option> 
                           <option value="250">250</option> 
                           <option value="300">300</option> 
                           <option value="350">350</option> 
                           <option value="400">400</option> 
                           <option value="450">450</option> 
                           <option value="500">500</option> 
                        </select>
                     </div>
               </div>
               <div class="search-drop-field" id="search-drop">
                  <div class="row">
                     <div class="col-12">           
                         <div class="btn-group" style="float:right;font-size:24px;">   
                             <a href="#" class="filter_close text-danger"><i class="far fa-times-circle"></i></a>        
                         </div>
                     </div>
                 </div>
                  <div class="row">
                      <div class="col-md-2 form-group mb-1">
                          <label> From date </label>
                          <input class="form-control from_date commonDatePicker" type="text" placeholder="From date">
                      </div>
                      <div class="col-md-2 form-group mb-1">
                          <label> To date </label>
                          <input class="form-control to_date commonDatePicker" type="text" placeholder="To date">
                      </div>
                      <div class="col-md-2 form-group mb-1 level_selector">
                        <label>Customer</label><br>
                        <select class="form-control customer_list select" name="customer" id="customer">
                           <option value=''>-All-</option>
                            @foreach($clients as $item)
                            <option value="{{$item->id}}"> {{ ucfirst($item->company_name)}} </option>
                            @endforeach
                        </select>
                     </div>
                      <div class="col-md-2 form-group mb-1 level_selector">
                          <label>Candidate Name</label><br>
                          <select class="form-control candidate_list select " name="candidate" id="candidate">
                           <option value=''>-Select-</option>
                          </select>
                      </div>
                      {{-- <div class="col-md-2 form-group mb-1 level_selector">
                        <label>SLA Name</label><br>
                        <select class="form-control sla_list select " name="sla" id="sla">
                           <option value=''>-Select-</option>

                        </select>
                       
                    </div>  --}}
                    <div class="col-md-2 form-group mb-1 level_selector">
                        <label>User's Name</label><br>
                        <select class="form-control user_list select" name="user" id="user">
                           <option value=''>-Select-</option>
                           @foreach($users_list as $item)
                           <option value="{{$item->id}}"> {{ ucfirst($item->name)}} </option>
                           @endforeach
                        </select>
                     </div>
                     <div class="col-md-2 form-group mb-1">
                        <label>Checks</label>
                        <select class="form-control "  name="service" id="service">
                           <option value="">Select</option>
                           @foreach ($services as $service)
                                 <option value="{{ $service->id }}" >{{ $service->name }}</option> 
                           @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 form-group mb-1">
                        <label>Task's Type</label>
                        <select class="form-control" name="task_type" id="task_type" >
                            <option value="">All</option>
                            <option  value="BGV Filling" >BGV Filling</option>
                            <option  value="BGV QC">BGV QC</option>
                            <option  value="Task for Verification " >Task Verification</option>
                            <option  value="Report generation" >Report Generation</option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <button class="btn btn-danger  resetBtn" style="padding: 7px;margin: 18px 0px;"> <i class="fas fa-refresh"></i>  Reset </button>
                     </div>
                      <div class="col-md-2">
                      <button class="btn btn-info search filterBtn" style="width: 100%;padding: 7px;margin: 18px 0px;"> Filter </button>
                      </div>
                  </div>
              </div>
               <div id="taskResult">
                  @include('admin.task.assign-ajax')
                  
               </div>
            </div>
         </div>
      {{-- </div> --}}
   </div>
   </div>
</div>
{{-- Modal for Report generation  Reassign --}}
<div class="modal" id="report_reassign_task">
   <div class="modal-dialog">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
         <h4 class="modal-title">Task Reassign</h4>
         {{-- <button type="button" class="close" style="top: 12px;!important; color: red; " data-dismiss="modal">&times;</button> --}}
         </div>
         <!-- Modal body -->
         <form method="post" action="{{ url('/task/report/reassign') }}" id="report_reassign_form">
            @csrf
            <input type="hidden" name="report_user_id" id="report_user_id">
            <input type="hidden" name="report_business_id" id="report_business_id">
            <input type="hidden" name="report_candidate_id" id="report_candidate_id" >
            {{-- <input type="hidden" name="service_id" id="service_id"> --}}
            <input type="hidden" name="report_task_id" id="report_task_id">
            <input type="hidden" name="report_job_sla_item_id" id="report_job_sla_item_id">
            <div class="modal-body">
            <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-all"> </p> 
            {{-- <div class="form-group">
            <label for="tat">TAT</label>
            <input type="text" name="tat" class="form-control" id="tat" placeholder="Enter tat" value="{{ old('tat') }}">
            <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-tat"></p> 
            </div> --}}
            <div class="form-group">
               <label for="label_name">Re-Assign To <span class="text-danger">*</span> </label>
               {{-- <div class="col-sm-12 col-md-12 col-12"> --}}
               <div class="form-group">
                  <select class="select-option-field-7 report_user selectValue form-control" name="report_user" id="report_user" data-type="user" data-t="{{ csrf_token() }}">
                     <option value="">Select user</option>
                     {{-- @foreach ($users as $key => $user) {
                        @foreach ($action_master as $key => $am) {
                        
                           @if ( in_array($am->id,json_decode($user->permission_id)) && $am->action_title == 'BGV Link') {
                              <option value="{{$user->id}}" >{{$user->name}}</option>
                           @endif
                        @endforeach
                     @endforeach --}}
                     {{-- <option value="{{$user->id}}" >{{$user->name}}</option> --}}
                     {{-- @endforeach --}}
                  </select>
                  
                  {{-- @if ($errors->has('user'))
                     <div class="error text-danger">
                  {{ $errors->first('user') }}
                  </div>
                  @endif --}}
               </div>
                <p style="margin-bottom: 2px;" class="text-danger" id="error-report_user"></p>
            </div>
            
            </div>
            <!-- Modal successfooter -->
            <div class="modal-footer">
               <button type="button" class="btn btn-info report_reassign_submit"  id="report_reassign_submit">Submit </button>
               <button type="button" class="btn btn-danger report_back" id="report_reassign_back" data-dismiss="modal">Close</button>
            </div>
         </form>
      </div>
   </div>
</div>
{{-- End of Report generation Model --}}
{{-- Assign Task --}}
<div class="modal" id="assign_modal">
   <div class="modal-dialog">
      <div class="modal-content">
      <!-- Modal Header -->
         <div class="modal-header">
            <h4 class="modal-title" >Assign To</h4>
            {{-- <button type="button" class="close" data-dismiss="modal">&times;</button> --}}
         </div>
      <!-- Modal body -->
         <form method="post" action="{{url('/task/user/assign')}}" id="assign_form">
         @csrf
         {{-- <input type="hid den" name="user_id" id="users"> --}}
         <input type="hidden" name="business_id" id="businesss">
         <input type="hidden" name="candidate_id" id="candidates_id" >
         {{-- <input type="hidden" name="service_id" id="service_id"> --}}
         <input type="hidden" name="task_id" id="tasks">
         <input type="hidden" name="job_sla_item_id" id="job_sla_items">
         <div class="modal-body">
            <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-all"> </p> 
            <div class="form-group">
               <label for="label_name">Assign To </label>
               {{-- <div class="col-sm-12 col-md-12 col-12"> --}}
               <div class="form-group">
                  <select class="select-option-field-7 users selectValue form-control" name="users" data-type="users" data-t="{{ csrf_token() }}">
                     <option value="">Select user</option>
                     @foreach ($users as $key => $user) {
                        @foreach ($action_master as $key => $am) {
                           @if ( in_array($am->id,json_decode($user->permission_id)) && $am->action_title == 'BGV Link') {
                           <option value="{{$user->id}}" >{{$user->name}}</option>
                           @endif
                        @endforeach
                     @endforeach
                  </select>
                  <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-users"></p>
               </div>
            </div>
         </div>
         <!-- Modal footer -->
         <div class="modal-footer">
            <button type="submit" class="btn btn-info btn-submit submit">Submit </button>
            <button type="button" class="btn btn-danger back " data-dismiss="modal">Close</button>
         </div>
         </form>
      </div>
   </div>
</div>
{{-- End of Assign Task Model --}}

{{-- Assign Verify Task --}}
<div class="modal" id="verify_assign_modal">
   <div class="modal-dialog">
      <div class="modal-content">
      <!-- Modal Header -->
         <div class="modal-header">
            <h4 class="modal-title" >Assign To</h4>
            {{-- <button type="button" class="close" data-dismiss="modal">&times;</button> --}}
         </div>
      <!-- Modal body -->
         <form method="post" action="{{url('/task/user/assign')}}" id="verify_assign_form">
         @csrf
         {{-- <input type="hid den" name="user_id" id="users"> --}}
         <input type="hidden" name="business_id" id="businesss_id">
         <input type="hidden" name="verify_candidate" id="verify_candidate" >
         {{-- <input type="hidden" name="service_id" id="service_id"> --}}
         <input type="hidden" name="verify_task_id" id="verify_task_id">
         <input type="hidden" name="job_sla_items_id" id="job_sla_items_id">
         <input type="hidden" name="type" id="settype">
         <div class="modal-body">
            <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-all"> </p> 
            <div class="form-group">
               <label for="label_name">Assign To </label>
               {{-- <div class="col-sm-12 col-md-12 col-12"> --}}
               <div class="form-group">
                  <select class="select-option-field-7 users selectValue form-control" name="users" data-type="users" data-t="{{ csrf_token() }}" required>
                     
                  </select>
                  <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-users"></p>
               </div>
            </div>
         </div>
         <!-- Modal footer -->
         <div class="modal-footer">
            <button type="submit" class="btn btn-info btn-submit submit">Submit </button>
            <button type="button" class="btn btn-danger back " data-dismiss="modal">Close</button>
         </div>
         </form>
      </div>
   </div>
</div>
{{-- End of Assign Verify Task Model --}}

{{-- Modal for BGV FILLING Reassign --}}
<div class="modal" id="task">
      <div class="modal-dialog">
         <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
            <h4 class="modal-title">Task Reassign</h4>
            {{-- <button type="button" class="close" style="top: 12px;!important; color: red; " data-dismiss="modal">&times;</button> --}}
            </div>
            <!-- Modal body -->
            <form method="post" action="{{url('/task/reassign')}}" id="task">
            @csrf
               <input type="hidden" name="user_id" id="user_id">
               <input type="hidden" name="business_id" id="business_id">
               <input type="hidden" name="candidate_id" id="candidate_id" >
               {{-- <input type="hidden" name="service_id" id="service_id"> --}}
               <input type="hidden" name="task_id" id="task_id">
               <input type="hidden" name="job_sla_item_id" id="job_sla_item_id">
               <div class="modal-body">
               <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-all"> </p> 
               {{-- <div class="form-group">
               <label for="tat">TAT</label>
               <input type="text" name="tat" class="form-control" id="tat" placeholder="Enter tat" value="{{ old('tat') }}">
               <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-tat"></p> 
               </div> --}}
               <div class="form-group">
               <label for="label_name">Re-Assign To <span class="text-danger">*</span> </label>
               {{-- <div class="col-sm-12 col-md-12 col-12"> --}}
               <div class="form-group">
               <select class="select-option-field-7 user selectValue form-control" name="user" data-type="user" data-t="{{ csrf_token() }}">
               <option value="">Select user</option>
               @foreach ($users as $key => $user) {
                  @foreach ($action_master as $key => $am) {
                  
                     @if ( in_array($am->id,json_decode($user->permission_id)) && $am->action_title == 'BGV Link') {
                        <option value="{{$user->id}}" >{{$user->name}}</option>
                     @endif
                  @endforeach
               @endforeach
               {{-- <option value="{{$user->id}}" >{{$user->name}}</option> --}}
               {{-- @endforeach --}}
               </select>
               
               {{-- @if ($errors->has('user'))
               <div class="error text-danger">
               {{ $errors->first('user') }}
               </div>
               @endif --}}
               </div>
               {{-- </div> <p style="margin-bottom: 2px;" class="text-danger" id="error-assign"></p> --}}
               </div>
               
               </div>
               <!-- Modal successfooter -->
               <div class="modal-footer">
               <button type="submit" class="btn btn-info " >Submit </button>
               <button type="button" class="btn btn-danger back" data-dismiss="modal">Close</button>
               </div>
            </form>
         </div>
      </div>
</div>
{{-- End of BGV Filling Model --}}


{{-- Modal for BGV Verification Task Reassign --}}
<div class="modal" id="verify_reassign_task">
   <div class="modal-dialog">
      <div class="modal-content">
      <!-- Modal Header -->
         <div class="modal-header">
         <h4 class="modal-title">Task Reassign</h4>
         {{-- <button type="button" class="close" style="top: 12px;!important; color: red; " data-dismiss="modal">&times;</button> --}}
         </div>
         <!-- Modal body -->
         <form method="post" action="{{url('/task/verification/reassign')}}" id="verify_task_form">
         @csrf
            {{-- <input type="hidden" name="user" id="use"> --}}
            <input type="hidden" name="business" id="business">
            <input type="hidden" name="candidat_id" id="candidat_id" >
            <input type="hidden" name="service" id="services">
            <input type="hidden" name="tasks_id" id="tasks_id">
            <input type="hidden" name="job_sla_item" id="job_sla_item">
            <input type="hidden" name="no_of_verification" id="no_of_verification">
            <div class="modal-body">
            <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-all"> </p> 
            <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-name"> </p>
            <div class="form-group">
               <label>User type<span class="text-danger">*</span></label>
            </div>
            <div class="form-group">
               <input  type="radio" class="reassign_user_status" id="reassign_user_status" name="reassign_user_status" value="user" ><label for="reassign_user_status"> User</label>
               {{-- <input type="radio" class="reassign_user_status" id="reassign_vendor_status" name="reassign_user_status" value="vendor"><label for="reassign_vendor_status"> Vendor</label> --}}
               <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-reassign_user_status"></p>
           </div>
               <div class="form-group">
                  <label for="label_name">Re-Assign To <span class="text-danger">*</span> </label>
                  {{-- <div class="col-sm-12 col-md-12 col-12"> --}}
                  <div class="form-group">
                     <select class="select-option-field-7 user selectValue form-control" name="user" id="user_name" data-type="user" data-t="{{ csrf_token() }}" required>
                     
                     </select>
                  </div>
                  <p style="margin-bottom: 2px;" class="text-danger" id="error-user"></p>
               {{-- </div> --}}
               </div>
               <div class="form-group reassign_vendor_sla d-none" >
                  <label for="label_name">Vendor Sla </label>
                  {{-- <div class="col-sm-12 col-md-12 col-12"> --}}
                  <div class="form-group">
                     <select class="select-option-field-7 reassign_sla_id selectValue form-control" id="reassign_vendor_sla_id" name="reassign_sla_id" data-type="vendor_sla" data-t="{{ csrf_token() }}" >
                        
                     </select>
                     <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-reassign_sla_id"></p>
                  </div>
               </div>
            </div>
            <!-- Modal successfooter -->
            <div class="modal-footer">
            <button type="submit" class="btn btn-info verify_reassign_submit " >Submit </button>
            <button type="button" class="btn btn-danger back" id="verify_reassign_back" data-dismiss="modal">Close</button>
            </div>
         </form>
      </div>
   </div>
</div>

{{-- Modal to task verify data --}}
<div class="modal"  id="user_task_assign">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
            <h4 class="modal-title" id="user_task_serv_name"></h4>
            <button type="button " class=" close_user_task_assign " style="top: 10px;!important; color: red; font-size: 40px;font-weight: bold; transition: 0.3s; background:transparent; border:none;" data-dismiss="modal">&times;</button>
         </div>
         <!-- Modal body -->
         
            <div class="modal-body">
               <div id="user_task_assign_data">

               </div>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
               <button type="button" class="btn btn-danger close_user_task_assign" data-dismiss="modal">Close</button>
            </div>
      </div>
   </div>
</div>

<!-- Script -->


<script type="text/javascript">
      // Select all check
      function checkAll(e) {
            var checkboxes = document.getElementsByName('checks');
            
            if (e.checked) {
               for (var i = 0; i < checkboxes.length; i++) { 
               checkboxes[i].checked = true;
               }
            } else {
               for (var i = 0; i < checkboxes.length; i++) {
               checkboxes[i].checked = false;
               }
            }
      }
      function checkChange(){

            var totalCheckbox = document.querySelectorAll('input[name="checks"]').length;
            var totalChecked = document.querySelectorAll('input[name="checks"]:checked').length;

            // When total options equals to total checked option
            if(totalCheckbox == totalChecked) {
            document.getElementsByName("showhide")[0].checked=true;
            } else {
            document.getElementsByName("showhide")[0].checked=false;
            }
      }

   $(document).ready(function(){

      $("#candidate").select2();
      $("#customer").select2();
      $("#sla").select2();
      $("#user").select2();
      $('.filter0search').click(function(){
         $('.search-drop-field').toggle();
      });
     
      $('.filter_close').click(function(){
                  $('.search-drop-field').toggle();
         });
      
      $('.customer_list').on('select2:select', function (e){
        var data = e.params.data.id;
        //loader
        $("#overlay").fadeIn(300);　
        getData(0);
        setData();
        e.preventDefault();
      });

     
      $(document).on('change','.from_date',function() {

         var from = $('.from_date').datepicker('getDate');
         var to_date   = $('.to_date').datepicker('getDate');

         if($('.to_date').val() !=""){
         if (from > to_date) {
         alert ("Please select appropriate date range!");
         $('.from_date').val("");
         $('.to_date').val("");

         }
         }

      });
      //
      // 
      var uriNum = location.hash;
      pageNumber = uriNum.replace("#","");
      // alert(pageNumber);
      getData(pageNumber);

      $(document).on('change','.to_date',function() {

         var to_date = $('.to_date').datepicker('getDate');
         var from   = $('.from_date').datepicker('getDate');
         if($('.from_date').val() !=""){
            if (from > to_date) {
            alert ("Please select appropriate date range!");
            $('.from_date').val("");
            $('.to_date').val("");

            }
         }

      });

      // filterBtn
      $(document).on('change','.from_date, .to_date, .candidate_list,.sla_list,.user_list,#rows,#service,.search,#task_type', function (e){    
         $("#overlay").fadeIn(300);　
         getData(0);
         e.preventDefault();
      });

      $(document).on('click','.filterBtn', function (e){    
        $("#overlay").fadeIn(300);　
        getData(0);
        e.preventDefault();
      });

      //
      $(document).on('change','.customer_list',function(e) {
               e.preventDefault();
               $('.candidate_list').empty();
               $('.candidate_list').append("<option value=''>-All-</option>");

               $('.sla_list').empty();
               $('.sla_list').append("<option value=''>-All-</option>");
               var customer_id = $('.customer_list option:selected').val();
                var last_name ='';
               $.ajax({
               type:"POST",
               url: "{{ url('/candidates/getslalist') }}",
               data: {"_token": "{{ csrf_token() }}",'customer_id':customer_id},      
               success: function (response) {
                  console.log(response);
                  if(response.success==true  ) {   
                     $.each(response.data, function (i, item) {
                        if (item.last_name==null) {
                           last_name ='';
                        } else {
                           last_name=item.last_name;
                        }
                        $(".candidate_list").append("<option value='"+item.id+"'> "+item.id+"-" + item.first_name +' '+last_name+ "</option>");
                     });
                     $.each(response.data1,function(i,item){
                        $(".sla_list").append("<option value='"+item.id+"'> " + item.title + "</option>");
                     });
                  }
                  //show the form validates error
                  if(response.success==false ) {                              
                     for (control in response.errors) {   
                           $('#error-' + control).html(response.errors[control]);
                     }
                  }
               },
               error: function (xhr, textStatus, errorThrown) {
                  // alert("Error: " + errorThrown);
               }
         });
         return false;
      });
   
      $(document).on('click', '.pagination a,.searchBtn',function(event){
        //loader
        $("#overlay").fadeIn(300);　
        $('li').removeClass('active');
        $(this).parent('li').addClass('active');
        event.preventDefault();
        var myurl = $(this).attr('href');
        var page  = $(this).attr('href').split('page=')[1];
        getData(page);
      });


      // print visits  
      $(document).on('click','#exportExcel',function(){
         //   setData();
         //   var candidate = $(".reports option:selected").val();
         var _this=$(this);
         var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> Under Processing...';
         $('p.load_container').html("");
         var task_arr = [];
         var i = 0;
         
         $('.checks:checked').each(function () {
               task_arr[i++] = $(this).val();
         });

         
         
         if((task_arr.length)>0){
                     _this.addClass('disabled-link');
                     $('#loading').html(loadingText);
            // alert(candidate_arr);
               //
                                 
                  //  var check       =    $(".check option:selected").val();
                  var from_date   =    $(".from_date").val(); 
                  var to_date     =    $(".to_date").val();    
                  //  var candidate_id=    candidate_arr;                           

                  $.ajax(
                  {
                     url: "{{ url('/') }}"+'/task/setData/',
                     type: "get",
                     data:{'task_id':task_arr,'from_date':from_date,'to_date':to_date},
                     datatype: "html",
                  })
                  .done(function(data)
                  {
                     window.setTimeout(function(){
                                 _this.removeClass('disabled-link');
                                 $('#loading').html("");
                                 // _this.html('<i class="far fa-file-archive"></i> Download Zip');
                              },2000);
                  console.log(data);
                  var path = "{{ url('task/checks-export')}}";
                     window.open(path);
                  })
                  .fail(function(jqXHR, ajaxOptions, thrownError)
                  {
                     //alert('No response from server');
                  });
               //
         
         }else{
               alert('Please select a check to export! ');
               }
      });

      // Reassign Task to one user to another for BGV filling
      $(document).on('click','.report_reaasign',function(){
            
            var report_user_id = $(this).attr('data-user');
            var report_business_id = $(this).attr('data-business');
            var report_candidate_id = $(this).attr('data-candidate');
            var report_service_id = $(this).attr('data-service');
            var report_task_id = $(this).attr('data-task');
            var report_job_sla_item_id = $(this).attr('data-jsi');

            $('#report_user_id').val(report_user_id);
            $('#report_business_id').val(report_business_id);
            $('#report_candidate_id').val(report_candidate_id);
            $('#report_service_id').val(report_service_id);        
            $('#report_task_id').val(report_task_id);
            $('#report_job_sla_item_id').val(report_job_sla_item_id);
            // alert(business_id);

            // $('#task').html("Submit");
            $('#report_reassign_task').modal({
                  backdrop: 'static',
                  keyboard: false
               });
            // $('#task').toggle();
         

            $.ajax({
               type: 'GET',
               url:"{{ url('/task/report_reassign_modal') }}",
               data: {'report_service_id':report_service_id,'report_candidate_id':report_candidate_id,'report_task_id':report_task_id},
                  
               success: function (data) {
                     // console.log(data.success);
                  $('.error-container').html('');
                  if (data.fail && data.error == '') {
                        //console.log(data.success);
                           $('.error').html(data.message);
                  }
                  
                  if (data.fail == false ) {
                        
                        $("#report_user").html(data.data);
                     
                  }
               } 
            
            });
      });

      $(document).on('click','.report_reassign_submit',function(){
         var reportReassignFormData = new FormData($("#report_reassign_form")[0]);

         $('#report_reassign_back').prop('disabled',true);
         var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
         $('#report_reassign_submit').html("");
         $('#report_reassign_submit').html(loadingText);
         $.ajax(
               {
                  type: 'post',
                  url:"{{ url('/task/report/reassign') }}",
                  data:reportReassignFormData, 
                  processData: false,
                  contentType: false,
                  success: function (data) {
                     console.log(data.success);
                     $('.error-container').html('');
                     if ( data.success == false  ) {
                         
                        window.setTimeout(function(){
                              _this.removeClass('disabled-link');
                              $('#report_reassign_submit').html("Submit");
                              // _this.html('<i class="far fa-file-archive"></i> Download Zip');
                        },1000);
                        for (control in data.errors) {
                           $('.' + control).addClass('border-danger');
                           $('#error-' + control).html(data.errors[control]);
                        }
                           //  console.log(data.success);
                              // $('.error').html(data.message);
                           //    toastr.success("Tasks has been Already Assigned ");
                           // // redirect to google after 5 seconds
                           // window.setTimeout(function() {
                           //       window.location = "{{ url('/')}}"+"/task/";
                           // }, 2000);
                     }
                     if (data.success == true ) {

                           window.setTimeout(function(){
                              _this.removeClass('disabled-link');
                              $('#report_reassign_submit').html("Submit");
                              // _this.html('<i class="far fa-file-archive"></i> Download Zip');
                           },1000);
                        if (data.custom =='yes') {
                           toastr.success("Task has been Re-assigned successfully");
                        }
                        else{
                           toastr.success("Task has not been Re-assigned to any user,Please check the user permissions!");
                        }
                        // toastr.success("Task has been assigned successfully");
                           // redirect to google after 5 seconds
                           window.setTimeout(function() {
                                 window.location = "{{ url('/')}}"+"/task/";
                           }, 2000);
                        
                     }
                  },
                  error: function (response) {
                     console.log(response);
                  } 
                  
               });


      });

      // Assign Verification Task to one user to another for BGV filling
      //  $('.assign').click(function(){
      $(document).on('click','.assign',function(){

         var current = $(this);
         // var user_id = $(this).attr('data-user');
         var business_id = $(this).attr('data-business');
         var candidate_id = $(this).attr('data-candidate');
         var service = $(this).attr('data-service');
         var task_id = $(this).attr('data-task');
         var job_sla_item_id = $(this).attr('data-jsi');
         // var number = document.getElementById('no_of_user').value;
         // var user = $(this).attr('data-user_id');

         // $('#users').val(user_id);
         $('#businesss_id').val(business_id);
         $('#verify_candidate').val(candidate_id);
         // $('#service_id').val(service_id);        
         $('#verify_task_id').val(task_id);
         $('#job_sla_items_id').val(job_sla_item_id);
         $('#settype').val('verify_task');
         // alert(candidate_id);

         // var services = 


            $('#verify_assign_modal').toggle();


            $.ajax({
               type: 'GET',
               url:"{{ url('/task/assign_modal') }}",
               data: {'service_id':service},
                   
               success: function (data) {
                     // console.log(data.success);
                  $('.error-container').html('');
                  if (data.fail && data.error == '') {
                        //    console.log(data.success);
                           $('.error').html(data.message);
                  }
                  
                  
                  if (data.fail == false ) {
                        
                        $(".users").html(data.data);
                       
                  }
               } 
            
            });

            $('.back').click(function(){
               $('#verify_assign_modal').hide();
            });         
            $('.submit').on('click', function() {
                  var $this = $(this);
                  var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
                  if ($(this).html() !== loadingText) {
                     $this.data('original-text', $(this).html());
                     $this.html(loadingText);
                     // $this.prop('disabled',true);
                  }
                  setTimeout(function() {
                     $this.html($this.data('original-text'));
                     $this.prop('disabled',false);
                  }, 5000);
            });

            $('#verifyAssignBtn').click(function(e) {
                  e.preventDefault();
                  $("#verify_assign_form").submit();
            });

            $(document).on('submit', 'form#verify_assign_form', function (event) {
               event.preventDefault();
               //clearing the error msg
               $('p.error-container').html("");
               
            
               var form = $(this);
               var _this =$(this);
               var data = new FormData($(this)[0]);
               var url = form.attr("action");

               _this.find('.btn-submit').attr('disabled', true);

               $.ajax({
                     type: form.attr('method'),
                     url: url,
                     data: data,
                     cache: false,
                     contentType: false,
                     processData: false,      
                     success: function (response) {
            
                        console.log(response);
                        if(response.success==true) {          
                           // _this.find('.btn-submit').attr('disabled', false);
                           //notify
                           toastr.success("Task Assignment Successfully");
                           // redirect to google after 5 seconds
                           window.setTimeout(function() {
                                 window.location = "{{ url('/')}}"+"/task/";
                           }, 2000);
                           
                        }
                        //show the form validates error
                        if(response.success==false ) { 
                           // _this.find('.btn-submit').attr('disabled', false);                             
                           for (control in response.errors) {   
                                 $('#error-' + control).html(response.errors[control]);
                           }
                        }
                     },
                     error: function (xhr, textStatus, errorThrown) {
                        // alert("Error: " + errorThrown);
                     }
               });
               return false;
            });
      });

      //Assign user for task
      $(document).on('click','.assign_user',function(){


         var current = $(this);
         // var user_id = $(this).attr('data-user');
         var business_id = $(this).attr('data-business');
         var candidate_id = $(this).attr('data-candidate');
         // var service_id = $(this).attr('data-service');
         var task_id = $(this).attr('data-task');
         var job_sla_item_id = $(this).attr('data-jsi');
         // var number = document.getElementById('no_of_user').value;
         // var user = $(this).attr('data-user_id');

         // $('#users').val(user_id);
         $('#businesss').val(business_id);
         $('#candidates_id').val(candidate_id);
         // $('#service_id').val(service_id);        
         $('#tasks').val(task_id);
         $('#job_sla_items').val(job_sla_item_id);
         // alert(candidate_id);



         $('#assign_modal').toggle();

        


         $('.back').click(function(){
            $('#assign_modal').hide();
         });         
         $('.submit').on('click', function() {
            var $this = $(this);
            var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
            if ($(this).html() !== loadingText) {
               $this.data('original-text', $(this).html());
               $this.html(loadingText);
               // $this.prop('disabled',true);
            }
            setTimeout(function() {
               $this.html($this.data('original-text'));
               $this.prop('disabled',false);
            }, 5000);
         });

         $('#assignBtn').click(function(e) {
               e.preventDefault();
               $("#assign_form").submit();
         });

         $(document).on('submit', 'form#assign_form', function (event) {
            event.preventDefault();
            //clearing the error msg
            $('p.error-container').html("");
            

            var form = $(this);
            var _this =$(this);
            var data = new FormData($(this)[0]);
            var url = form.attr("action");

            _this.find('.btn-submit').attr('disabled', true);

            $.ajax
            ({
                  type: form.attr('method'),
                  url: url,
                  data: data,
                  cache: false,
                  contentType: false,
                  processData: false,      
                  success: function (response) {

                     console.log(response);
                     if(response.success==true) {          
                        // _this.find('.btn-submit').attr('disabled', false);
                        //notify
                        toastr.success("Task Assignment Successfully");
                        // redirect to google after 5 seconds
                        window.setTimeout(function() {
                              window.location = "{{ url('/')}}"+"/task/";
                        }, 2000);
                        
                     }
                     //show the form validates error
                     if(response.success==false ) { 
                        // _this.find('.btn-submit').attr('disabled', false);                             
                        for (control in response.errors) {   
                              $('#error-' + control).html(response.errors[control]);
                        }
                     }
                  },
                  error: function (xhr, textStatus, errorThrown) {
                     // alert("Error: " + errorThrown);
                  }
            });
            return false;
         });
      });

      // Reassign Task to one user to another for BGV filling
      $(document).on('click','.reaasign',function(){
            var user_id = $(this).attr('data-user');
            var business_id = $(this).attr('data-business');
            var candidate_id = $(this).attr('data-candidate');
            var service_id = $(this).attr('data-service');
            var task_id = $(this).attr('data-task');
            var job_sla_item_id = $(this).attr('data-jsi');

            $('#user_id').val(user_id);
            $('#business_id').val(business_id);
            $('#candidate_id').val(candidate_id);
            $('#service_id').val(service_id);        
            $('#task_id').val(task_id);
            $('#job_sla_item_id').val(job_sla_item_id);
            // alert(business_id);
            $('#task').toggle();
      });

      $('.close').click(function(){
         $('#task').hide();
      });
      $('.back').click(function(){
         $('#task').hide();
      });

      // Reassign Task to one user to another for BGV  Verification
      $(document).on('click','.verify_reaasign',function(){
            
               var user_id = $(this).attr('data-user_id');
               var business_id = $(this).attr('data-business_id');
               var candidate_id = $(this).attr('data-candidate_id');
               var service_id = $(this).attr('data-service_id');
               var task_id = $(this).attr('data-task_id');
               var job_sla_item_id = $(this).attr('data-jsi_id');
               var no_of_verification = $(this).attr('data-no_of_verification');
               // alert(service_id);
               $('#use').val(user_id);
               $('#business').val(business_id);
               $('#candidat_id').val(candidate_id);
               $('#services').val(service_id);        
               $('#tasks_id').val(task_id);
               $('#job_sla_item').val(job_sla_item_id);
               $('#no_of_verification').val(no_of_verification);
               // alert(business_id);
               $('#verify_reassign_task').toggle();
               $('#verify_reassign_back').click(function(){
                  $('#verify_reassign_task').hide();
               });
               
      });
      
      $(document).on('change','.reassign_user_status',function(){
         var reassign_user_status = $("input[name=reassign_user_status]:checked").val();
         var reassign_service = $('#services').val();
         var reassign_candidate_id = $('#candidat_id').val();
         var reassign_no_of_verification = $('#no_of_verification').val();
         // alert(reassign_user_status);
            if (reassign_user_status == 'vendor') {
                  $('.reassign_vendor_sla').removeClass("d-none");
            }
            else{
                  $('.reassign_vendor_sla').addClass("d-none");
            }
            $.ajax({
               type: 'GET',
               url:"{{ url('/task/reassign_modal') }}",
               data: {'service_id':reassign_service,'candidate_id':reassign_candidate_id,'number_of_verifications':reassign_no_of_verification,'user_type':reassign_user_status},
                  
               success: function (data) {
                     // console.log(data.success);
                  $('.error-container').html('');
                  if (data.fail && data.error == '') {
                        //    console.log(data.success);
                           $('.error').html(data.message);
                  }
                  
                  
                  if (data.fail == false ) {
                        
                        $("#user_name").html(data.data);
                     
                  }
               } 
            
            });



            //Sla List
            $('.user').on('change',function(){
               var assign_service =$('#services').val();
               var user_status = $("input[name=reassign_user_status]:checked").val();
               // $('#settype').val('verify_task');
               // e.preventDefault();
               // $('.users').empty();
               // $('.users').append("<option value=''>-All-</option>");
               var vendor_sla_id =$('#user_name option:selected').val();
               // alert (vendor_id);
               if (user_status == 'vendor') {
                  // $('.vendor_sla_add').removeClass("d-none");
                     $.ajax({
                        type: 'GET',
                        url:"{{ url('/task/reassign_vendor_sla') }}",
                        data: {'service_id':assign_service,'user_type':user_status,'vendor_id':vendor_sla_id},
                           
                        success: function (data) {
                              // console.log(data.success);
                           $('.error-container').html('');
                           if (data.fail && data.error == '') {
                                 //    console.log(data.success);
                                    $('.error').html(data.message);
                           }
                           if (data.fail == true) {
                              for (control in data.errors) {   
                                    $('#error-' + control).html(data.errors[control]);
                              }
                           }
                           
                           if (data.fail == false ) {
                                 
                                 $("#reassign_vendor_sla_id").html(data.data);
                              
                           }
                        } 
            
                     });
               }
               
               // else{
               //    $('.vendor_sla_add').addClass("d-none");
               // }

            });
               //verify reassign task submit
               $('.verify_reassign_submit').on('click', function() {
                  $('#verify_reassign_back').prop('disabled',true);
                  var $this = $(this);
                  var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
                  if ($(this).html() !== loadingText) {
                     $this.data('original-text', $(this).html());
                     $this.html(loadingText);
                  }
                  setTimeout(function() {
                     $this.html($this.data('original-text'));
                  }, 5000);
               });
            
               $('#verifyTaskBtn').click(function(e) {
                     e.preventDefault();
                     $("#verify_task_form").submit();
               });
            
               $(document).on('submit', 'form#verify_task_form', function (event) {
                  event.preventDefault();
                  //clearing the error msg
                  $('p.error_container').html("");
               
                  var form = $(this);
                  var data = new FormData($(this)[0]);
                  var url = form.attr("action");
            
                  $.ajax({
                        type: form.attr('method'),
                        url: url,
                        data: data,
                        cache: false,
                        contentType: false,
                        processData: false,      
                        success: function (response) {
               
                           console.log(response);
                           if(response.success==true  ) {          
                              
                              //notify
                              toastr.success("Task has been Reassigned successfully");
                              // redirect to google after 5 seconds
                              window.setTimeout(function() {
                                    window.location = "{{ url('/')}}"+"/task/";
                              }, 2000);
                              
                           }
                           //show the form validates error
                           if(response.success==false ) {                              
                              for (control in response.errors) {   
                                    $('#error-' + control).html(response.errors[control]);
                              }
                           }
                        },
                        error: function (response) {
                           console.log(response);
                        }
                        // error: function (xhr, textStatus, errorThrown) {
                        //    // alert("Error: " + errorThrown);
                        // }
                  });
                  event.stopImmediatePropagation();
                  return false;
               }); 

      });

      $('.close').click(function(){
         $('#user_name').val('');
         $('#verify_reassign_task').hide();
      });
      $('.back').click(function(){
         $('#user_name').val('');
         $('#verify_reassign_task').hide();
      });

      $(document).on('click','.task_verify',function(){
                  var verify_candidate_id=$(this).attr('data-task_verify_can_id');
                  var verify_service_id=$(this).attr('data-task_verify_service_id');
                  var verify_number_id=$(this).attr('data-task_verify_nov_id');

                  // console.log(task_id);data-task_verify_service_id
                  // data-task_verify_nov_id
                  // alert('abc');
                  $.ajax({
                     type:'GET',
                     url: "{{url('/task/verify/info')}}",
                     data: {'verify_candidate_id':verify_candidate_id,'verify_service_id':verify_service_id,'verify_number_id':verify_number_id},        
                     success: function (response) {        
                     //console.log(response);

                     $('#user_task_assign_data').html(response.html);
                     $('#user_task_assign').modal({
                           backdrop: 'static',
                           keyboard: false
                        });
                     // if (response.status=='ok') {            
                        
                        
                     // } else {

                     //    alert('No data found');

                     // }
                  },
                  error: function (xhr, textStatus, errorThrown) {
                     alert("Error: " + errorThrown);
                  }
               });
      });
   });

   function getData(page){
      //set data
      var user_id     =    $(".customer_list").val();                
      // var check       =    $(".check option:selected").val();
      var sla_id   =     $(".sla_list option:selected").val();
      var cus_user_id   =     $(".user_list option:selected").val();
   
      var from_date   =    $(".from_date").val(); 
      var to_date     =    $(".to_date").val();  
      var search = $('.search').val();    
      var candidate_id=    $(".candidate_list option:selected").val();
      var rows = $("#rows option:selected").val();
      var service_id = $("#service option:selected").val();
      var task_type = $("#task_type option:selected").val();
      //   var mob = $('.mob').val();
      //   var ref = $('.ref').val();
      //   var email = $('.email').val();
      //   var report_status=$('.report_status').val();               
   
         $('#taskResult').html("<div style='background-color:#ddd; min-height:450px; line-height:450px; vertical-align:middle; text-align:center'><img alt='' src='"+loaderPath+"' /></div>").fadeIn(300);
   
         $.ajax(
         {
               url: '?page=' + page+'&customer_id='+user_id+'&search='+search+'&from_date='+from_date+'&to_date='+to_date+'&candidate_id='+candidate_id+'&sla_id='+sla_id+'&user_id='+cus_user_id+'&rows='+rows+'&service_id='+service_id+'&task_type='+task_type,
               type: "get",
               datatype: "html",
         })
         .done(function(data)
         {
               $("#taskResult").empty().html(data);
               $("#overlay").fadeOut(300);
               //debug to check page number
               location.hash = page;
         })
         .fail(function(jqXHR, ajaxOptions, thrownError)
         {
               alert('No response from server');
         });

         return false;
   
   }

   function setData(){
   
      var user_id     =    $(".customer_list").val();                
      // var check       =    $(".check option:selected").val();
   
      var from_date   =    $(".from_date").val(); 
      var to_date     =    $(".to_date").val();  
      var search = $('.search').val();  
      var candidate_id=    $(".candidate_list option:selected").val(); 
      var rows = $("#rows option:selected").val();
      var service_id = $("#service option:selected").val();
      var task_type = $("#task_type option:selected").val();                           
      // var mob = $('.mob').val();
      // var ref = $('.ref').val();
      // var email = $('.email').val();

      var sla_id   =     $(".sla_list option:selected").val();

      var cus_user_id   =     $(".user_list option:selected").val();

      // var report_status=$('.report_status').val();
            $.ajax(
            {
               url: "{{ url('/') }}"+'/candidates/setData/?customer_id='+user_id+'&from_date='+from_date+'&to_date='+to_date+'&candidate_id='+candidate_id+'&sla_id='+sla_id+'&user_id='+cus_user_id+'&rows='+rows+'&service_id='+service_id+'&search='+search+'&task_type='+task_type,
               type: "get",
               datatype: "html",
            })
            .done(function(data)
            {
               console.log(data);
            })
            .fail(function(jqXHR, ajaxOptions, thrownError)
            {
               //alert('No response from server');
            });

            return false;
   
   }
  
</script>
@endsection

