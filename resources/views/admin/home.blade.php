@extends('layouts.admin')

@section('content')
<style>

</style>
 <div class="main-content-wrap sidenav-open d-flex flex-column">
  <div class="main-content">         
    <div class="card">
      <div class="card-body">

        <div class="row">
            @php
                $OPS_ACCESS   = false;
                $SALES_ACCESS   = false;
                $MASTER_ACCESS   = false;
                $PROGRESS_ACCESS   = false;    

                $OPS_ACCESS    = Helper::can_access('OPS Tracker','');//passing action title and route group name
                $MASTER_ACCESS    = Helper::can_access('Master Tracker','');//passing action title and route group name
                $SALES_ACCESS    = Helper::can_access('Sales Tracker','');//passing action title and route group name
                $PROGRESS_ACCESS    = Helper::can_access('Progress Tracker','');//passing action title and route group name
            @endphp
            <div class="col-lg-4">
                <h3 class="mr-2"> Dashboard</h3>
            </div>
            <div class="col-lg-2 pt-2">
              @if($OPS_ACCESS)
                <div class="" style="float: right;">
                  <button class="btn btn-link ops_excel text-decoration-none" type="button">
                    <img src="{{asset('admin/images/icon14.svg')}}"> OPS Tracker
                  </button><br>
                  
                </div>
              @endif
            </div>
            <div class="col-lg-2 pt-2">
              @if($MASTER_ACCESS)
                <div class="" style="float: right;">
                  <button class="btn btn-link mis_excel" type="button">
                    <i class="fas fa-file-excel"></i> Master Tracker
                  </button><br>
                  
                </div>
              @endif
                
            </div>
            <div class="col-lg-2 pt-2">
                @if($SALES_ACCESS)
                  <div class="" style="float: right;">
                    
                    <a href="{{url('/sales-dashboard')}}" class="btn btn-link">
                      <i class="fas fa-chart-line"></i> Sales Tracker
                    </a><br>
                    
                  </div>
                @endif
            </div>
            <div class="col-lg-2 pt-2">
              @if($PROGRESS_ACCESS)
                <div class="" style="float: right;">
                 
                  <a href="{{url('/progress-dashboard')}}" class="btn btn-link">
                    <i class="fas fa-chart-line"></i> Progress Tracker
                  </a><br>
                  
                </div>
              @endif
            </div>
        </div>
        
        <div class="row" >
            <!-- ICON BG-->

           <div class="col-lg-3 col-sm-6 customerCard">
            <div class="card card-icon-bg card-icon-bg-1 o-hidden mb-4" style="background-color: #EFF9FB;border: 2px solid rgba(0, 0, 0, 0.06);
              box-shadow: 7px 5px 12px 1px rgb(0 0 0 / 6%);border-radius: 20px;">
              <div class="card-body pd-10 ">
                <i class="fa fa-user"></i>
                <div class="data-content">
                  <div class="row">
                    <div class="col-lg-12 top-heading-dash">
                      <a class="text-24 line-height-2 mb-2" href="{{ url('/customers') }}"> {{$customers_count}} </a>
                      <br>
                      <a class="mt-2 mb-0 sort_desc" href="{{ url('/customers') }}">
                        <strong>Total Clients</strong>
                      </a>
                    </div>
                  </div>
                  <div class="row mt-20">
                    <div class="col-lg-12 below-heading-dash">
                      <a href="{{ url('/customers/?active_case=1') }}" class="mt-2 mb-0 ">
                        <strong>Active  </strong>
                      </a>
                      <a href="{{ url('/customers/?active_case=1') }}" class="text-18 line-height-2 mb-2 counting"> {{$customers_active}} </a>
                    </div>
                    <div class="col-lg-12 below-heading-dash">
                      <a href="{{ url('/customers?active_case=0') }}" class="mb-0">
                        <strong> Inactive </strong>
                      </a>
                      <a href="{{ url('/customers?active_case=0') }}" class="text-18 line-height-2 mb-2 counting"> {{$customers_inactive}} </a>
                    </div>
                  </div>
                </div>
              </div>
              
            </div>
          </div>
          <!--  -->
           <div class="col-lg-3 col-sm-6 candidateCard"><div class="card card-icon-bg card-icon-bg-1 o-hidden mb-4 " style="background-color: #EFF9FB;border: 2px solid rgba(0, 0, 0, 0.06);box-shadow: 7px 5px 12px 1px rgb(0 0 0 / 6%);border-radius: 20px;">
                    <div class="card-body pd-10 ">
                      <i class="fa fa-users"></i>
                        <div class="data-content">
                            <div class="row">
                              <div class="col-lg-12 top-heading-dash">
                                <a class="text-24 line-height-2 mb-2 candidate_count" href="{{url('/candidates')}}">{{$candidate_count}}</a><br>
                                <a class="mt-2 mb-0 sort_desc" href="{{url('/candidates')}}"> <strong>Total Cases</strong> </a>
                              </div>
                            </div>
                            <div class="row mt-20">
                              <div class="col-lg-12 below-heading-dash">
                              <a href="{{ url('/candidates/?case_wip=1') }}" class="mt-2 mb-0"><strong> WIP</strong></a>
                                <a href="{{ url('/candidates/?case_wip=1') }}" class="text-18 line-height-2  jaf_customer_count counting">{{$WIP_count}} </a>
                                
                              </div>
                              <div class="col-lg-12 below-heading-dash">
                              <a href="{{ url('/candidates/?insuff=1') }}" class="mt-2 mb-0"><strong> Insuff</strong></a>
                                <a href="{{ url('/candidates/?insuff=1') }}" class="text-wh text-18 line-height-2  jaf_coc_count counting">{{$count_total_insuff_case}} </a>
                                
                              </div>
                              
                              <div class="col-lg-12 below-heading-dash">
                              <a href="{{ url('/candidates?sendto=candidate&jafstatus1=pending&jafstatus2=draft') }}" class="mt-2 mb-0"><strong> Associate Pending </strong></a>
                                <a href="{{ url('/candidates?sendto=candidate&jafstatus1=pending&jafstatus2=draft') }}" class="text-wh text-18 line-height-2 mb-2 jaf_candidate_count counting">{{$candidate_bgv_pending}}</a>
                                
                              </div>
                              <div class="col-lg-12 below-heading-dash">
                                <a href="{{ url('/candidates/?sendto=candidate&jafstatus=filled') }}" class="mt-2 mb-0"><strong> Associate Completed </strong></a>
                                <a href="{{ url('/candidates/?sendto=candidate&jafstatus=filled') }}" class="text-wh text-18 line-height-2 mb-2 jaf_candidate_count counting">{{ $candidate_bgv_completed}}</a>
                              </div>

                            </div>
                        </div>
                    </div>
                </div>
              </div>

            <!-- BGV -->
            <div class="col-lg-3 col-sm-6 jafCard"><div class="card card-icon-bg card-icon-bg-1 o-hidden mb-4" style="background-color: #EFF9FB;border: 2px solid rgba(0, 0, 0, 0.06);
    box-shadow: 7px 5px 12px 1px rgb(0 0 0 / 6%);border-radius: 20px;">
              <div class="card-body pd-10 ">
                <i class="fa fa-id-card"></i>
                  <div class="data-content">
                      <div class="row">
                        <div class="col-lg-12 top-heading-dash">
                          <a href="{{ url('/jobs') }}" class="text-24 line-height-2 mb-2"> {{$total_checks}} </a><br>
                          <a href="{{ url('/jobs') }}" class="mt-2 mb-0 sort_desc"> <strong>Total Checks</strong> </a>
                        </div>
                      </div>
                      <div class="row mt-20">
                          <div class="col-lg-12 below-heading-dash">
                          <a href="{{ url('/jobs') }}" class="mt-2 mb-0"><strong> Completed Checks </strong></a>
                              <a href="{{ url('/jobs') }}" class="text-wh text-18 line-height-2 counting"> {{$completed_checks}} </a>
                              
                          </div>
                        <div class="col-lg-12 below-heading-dash">
                        <a href="{{ url('/jobs') }}" class="mt-2 mb-0"><strong> Pending Checks </strong></a>
                          <a href="{{ url('/jobs') }}" class="text-wh text-18 line-height-2 counting"> {{$incompleted_checks}}  </a>
                          
                        </div>
                        
                      </div>
                      
                  </div>
              </div>
          </div>
        </div>
      <!--  -->

      <div class="col-lg-3 col-sm-6 checkCard"><div class="card card-icon-bg card-icon-bg-1 o-hidden mb-4" style="background-color: #EFF9FB;border: 2px solid rgba(0, 0, 0, 0.06);
    box-shadow: 7px 5px 12px 1px rgb(0 0 0 / 6%);border-radius: 20px;">
            <div class="card-body pd-10 ">
              <i class="fa fa-book"></i>
                <div class="data-content">
                    <div class="row">
                      <div class="col-lg-12 top-heading-dash">
                        <a href="{{ url('/reports') }}" class="text-24 line-height-2 mb-2"> {{$reports}} </a><br>
                        <a href="{{ url('/reports') }}" class="mt-2 mb-0 sort_desc"> <strong>Total Reports</strong> </a>
                      </div>
                    </div>
                    <div class="row mt-20">
                      <div class="col-lg-12 below-heading-dash">
                      <a href="{{ url('/reports/?report_status1=completed&report_status2=interim') }}" class="mt-2 mb-0"><strong> Completed Report </strong></a>
                        <a href="{{ url('/reports/?report_status1=completed&report_status2=interim') }}" class="text-wh text-18 line-height-2 counting">{{$complete_report}}</a>
                       
                      </div>
                      <div class="col-lg-12 below-heading-dash">
                      <a href="{{ url('/reports/?report_status=incomplete') }}" class="mt-2 mb-0"><strong> Pending Report </strong></a>
                        <a href="{{ url('/reports/?report_status=incomplete') }}" class="text-wh text-18 line-height-2 counting"> {{$pending_report}} </a>
                        
                      </div>
                      
                    </div>
                    
                </div>
            </div>
        </div>
        </div>
        
           
    </div>
    <!-- ./row end -->
            <!-- <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-icon-bg card-icon-bg-1 o-hidden mb-4">
                    <div class="card-body text-center">
                        <i class="fa fa-user"></i>
                        <div class="content">
                            <p class="text-primary text-24 line-height-1 mb-2"> {{ $customers_count }} </p>
                            <p class="text-muted mt-2 mb-0"> Customers </p>
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-icon-bg card-icon-bg-2 o-hidden mb-4">
                    <div class="card-body text-center">
                        <i class="fa fa-check"></i>
                        <div class="content">
                            <p class="text-primary text-24 line-height-1 mb-2">  </p>
                            <p class="text-muted mt-2 mb-0"> Checks </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-icon-bg card-icon-bg-3 o-hidden mb-4">
                    <div class="card-body text-center">
                        <i class="fa fa-book"></i>
                        <div class="content"> 
                            <p class="text-primary text-24 line-height-1 mb-2">  </p>
            <p class="text-muted mt-2 mb-0"> Verifications done </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-icon-bg card-icon-bg-4 o-hidden mb-4">
                    <div class="card-body text-center">
                        <i class="fa fa-paper-plane"></i>
                        <div class="content">
                            <p class="text-primary text-24 line-height-1 mb-2"> {{$reports}} </p>
            <p class="text-muted mt-2 mb-0"> Reports sent  </p>
                        </div>
                    </div>
                </div>
            </div> -->
        </div>
        
        
       <div class="row">
            <div class="col-lg-10">
                {{-- <div class="btn-group">
                        <button class="btn  dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <h4> Most <span class="rm">Recent Checks <span> </h4>
                        </button>
                        <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 27px, 0px);"><a class="dropdown-item" href="#">Action</a><a class="dropdown-item" href="#">Another Action</a><a class="dropdown-item" href="#">Something Else Here</a></div>
                </div> --}}
            </div>

            {{-- <div class="col-lg-2">
                <div class="btn-group" style="float: right;">
                        <button class="btn dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">  Show <span class="alm"> all Checks </span>
                        </button>
                        <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 27px, 0px);"><a class="dropdown-item" href="#">Action</a><a class="dropdown-item" href="#">Another Action</a><a class="dropdown-item" href="#">Something Else Here</a></div>
                </div>       
            </div> --}}
       </div>
        
      <div class="row">

          <div class="col-lg-12 col-sm-12">
              <div class="card mb-4">
                  <div class="card-body">
                      <div class="card-title">Checks Overview </div>
                      <div class="table-responsive" style="max-height: 300px;">
                        <table class="table">
                          <thead>
                            <tr>
                              <th style="position:sticky; top:0px" scope="col"><strong>Checks</strong></th>
                              <th style="position:sticky; top:0px" scope="col"><strong>Completed</strong></th>
                              <th style="position:sticky; top:0px" scope="col"><strong>Remaining</strong></th>
                              <th style="position:sticky; top:0px" scope="col"><strong>Insuff</strong></th>
                              <th style="position:sticky; top:0px" scope="col"><strong>Call</strong></th>
                              <th style="position:sticky; top:0px" scope="col"><strong>SMS</strong></th>
                              <th style="position:sticky; top:0px" scope="col"><strong>Link</strong></th>
                            </tr>
                          </thead>
                          <tbody>
                              {{-- <tr>
                                <td><strong>Checks</strong></td>
                                <td><strong>Completed</strong></td>
                                <td><strong>Remaining</strong></td>
                              </tr> --}}
                              {{-- <tr>
                                <td><strong>Addhar</strong></td>
                                <td>20</td>
                                <td>12</td>
                              </tr>
                              <tr>
                                <td><strong>Pan</strong></td>
                                <td>20</td>
                                <td>12</td>
                              </tr>
                              <tr>
                                <td><strong>Education</strong></td>
                                <td>20</td>
                                <td>12</td>
                              </tr> --}}

                              @if (count($array_result)>0)
                                @foreach ($array_result as $result)
                                    <tr>
                                      <td><strong>{{$result['check_name']}}</strong></td>
                                      <td>
                                        <a href="{{ url('/candidates/?verify_status=success&service='.$result['check_id']) }}">{{$result['completed']}}</a>
                                      </td>
                                      <td><a href="{{ url('/candidates/?verification_status=null&service='.$result['check_id']) }}">{{$result['pending']}}</a></td>
                                      <td><a href="{{ url('/candidates/?insuffs=1&service='.$result['check_id'])}}">{{$result['insuff']}}</a></td>
                                      <td>0</td>
                                      <td>0</td>
                                      <td>0</td>
                                  
                                    </tr>
                                @endforeach   
                              @endif
                          </tbody>
                        </table>
                      </div>
                  </div>
              </div>
          </div>
          <!-- <div class="col-lg-12 col-md-12">
              <div class="card mb-4">
                  <div class="card-body">
                      <div class="card-title">Checks Overview</div>
                      <div id="echartBar" style="height: 300px;"></div>
                  </div>
              </div>
          </div> -->
          <div class="col-lg-12 col-md-12">
              <div class="card mb-4">
                  <div class="card-body">
                      <div class="card-title">Checks Overview</div>
                      <div id="empchartBar" style="height: 300px;"></div>
                  </div>
              </div>
          </div>          

      </div>
 
  </div>
</div>

{{-- modal for sales excel export --}}
<div class="modal" id="sales_modal">
  <div class="modal-dialog modal-lg">
     <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
           <h4 class="modal-title" id="name">Sales Tracker Report</h4>
           {{-- <button type="button" class="close closeraisemdl" data-dismiss="modal">&times;</button> --}}
        </div>
        <!-- Modal body -->
        <form method="post" action="{{url('/sales-tracker')}}" enctype="multipart/form-data" id="sales_data_form">
        @csrf
            @php
              $start_year = 2020;
              $end_year = date('Y');

              $diff = abs($end_year - $start_year);
            @endphp
           <div class="modal-body">
            <div class="form-group">
              <label>Duration Type : <span class="text-danger">*</span></label>
              <select class="form-control type" name="type">
                <option value="">--Select--</option>
                <option value="weekly">Weekly</option>
                <option value="monthly">Monthly</option>
                <option value="quaterly">Quaterly</option>
                <option value="yearly">Yearly</option>
              </select>
              <p style="margin-bottom: 2px;" class="text-danger error-container error-type" id="error-type"></p> 
            </div>
            <div class="type_result">

            </div>
              {{-- <div class="form-group">
                <label>Year : <span class="text-danger">*</span></label>
                <select class="form-control year" name="year">
                  <option value="">--Select--</option>
                    @for ($i=0;$i<=$diff;$i++)
                      <option value="{{date('Y',strtotime('2020-01-01'.' +'.$i.' years'))}}" @if(date('Y')==date('Y',strtotime('2020-01-01'.' +'.$i.' years'))) selected @endif>{{date('Y',strtotime('2020-01-01'.' +'.$i.' years'))}}</option>
                    @endfor
                </select>
                <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-year"></p> 
              </div>
              <div class="form-group">
                <label>Month : <span class="text-danger">*</span></label>
                <select class="form-control month" name="month">
                  <option value="">--Select--</option>
                    @for ($i=1;$i<=date('n');$i++)
                      <option value="{{date('m',strtotime(date('Y').'-'.$i.'-01'))}}">{{date('F',strtotime('1-'.$i.'-'.date('Y')))}}</option>
                    @endfor
                </select>
                <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-month"></p> 
              </div> --}}
              <div class="form-group">
                <label>Customer : </label>
                <select class="form-control customer" name="customer[]" id="customer" data-actions-box="true" data-selected-text-format="count>1" multiple>
                  {{-- <option value="">-Select-</option> --}}
                    @foreach($customers as $cust)
                        <option value="{{$cust->id}}">{{$cust->company_name.' - '.$cust->name}}</option>   
                    @endforeach
                  </select>
              </div>
              <p style="margin-bottom: 2px;" class="text-danger error-container error-all"></p>
            </div>
           <!-- Modal footer -->
           <div class="modal-footer">
              <button type="submit" class="btn btn-info sale_submit btn-disable">Submit </button>
              <button type="button" class="btn btn-danger btn-disable" data-dismiss="modal">Close</button>
           </div>
        </form>
     </div>
  </div>
</div>

<div class="modal" id="ops_modal">
  <div class="modal-dialog modal-lg">
     <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
           <h4 class="modal-title" id="name">OPS Tracker Report</h4>
           {{-- <button type="button" class="close closeraisemdl" data-dismiss="modal">&times;</button> --}}
        </div>
        <!-- Modal body -->
        <form method="post" action="{{url('/ops-export')}}" enctype="multipart/form-data" id="ops_data_form">
        @csrf
           <div class="modal-body">
              <div class="form-group">
                <label>Duration Type : <span class="text-danger">*</span></label>
                <select class="form-control type" name="type">
                  <option value="">--Select--</option>
                  <option value="daily">Daily</option>
                  <option value="weekly">Weekly</option>
                  <option value="monthly">Monthly</option>
                  <option value="quaterly">Quaterly</option>
                  <option value="yearly">Yearly</option>
                </select>
                <p style="margin-bottom: 2px;" class="text-danger error-container error-type" id="error-type"></p> 
              </div>
              <div class="type_result">

              </div>
              <div class="form-group">
                <label>Customer : </label>
                <select class="form-control customer" name="customer[]" id="customer" data-actions-box="true" data-selected-text-format="count>1" multiple>
                  {{-- <option value="">-Select-</option> --}}
                    @foreach($customers as $cust)
                        <option value="{{$cust->id}}">{{$cust->company_name.' - '.$cust->name}}</option>   
                    @endforeach
                  </select>
              </div>
              <div class="form-group">
                <label>User : </label>
                <select class="form-control user" name="user[]" id="user" data-actions-box="true" data-selected-text-format="count>1" multiple>
                  {{-- <option value="">-Select-</option> --}}
                    @foreach($users as $user)
                        <option value="{{$user->id}}">{{$user->name}}</option>   
                    @endforeach
                  </select>
              </div>
              <p style="margin-bottom: 2px;" class="text-danger error-container error-all"></p>
            </div>
           <!-- Modal footer -->
           <div class="modal-footer">
              <button type="submit" class="btn btn-info ops_submit btn-disable">Submit </button>
              <button type="button" class="btn btn-danger btn-disable" data-dismiss="modal">Close</button>
           </div>
        </form>
     </div>
  </div>
</div>

<div class="modal" id="progress_modal">
  <div class="modal-dialog modal-lg">
     <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
           <h4 class="modal-title" id="name">Progress Tracker Report</h4>
           {{-- <button type="button" class="close closeraisemdl" data-dismiss="modal">&times;</button> --}}
        </div>
        <!-- Modal body -->
        <form method="post" action="{{url('/progress-export')}}" enctype="multipart/form-data" id="progress_data_form">
        @csrf
            @php
              $start_year = 2020;
              $end_year = date('Y');

              $diff = abs($end_year - $start_year);
            @endphp
           <div class="modal-body">
            <div class="form-group">
              <label>Month : <span class="text-danger">*</span></label>
              <select class="form-control p_month" name="p_month[]" data-actions-box="true" data-live-search="true" data-live-search-normalize="true" data-live-search-placeholder="Select the Month" data-selected-text-format="count>1" multiple>
                {{-- <option value="">--Select--</option> --}}
                  @for ($i=1;$i<=date('n');$i++)
                    <option value="{{date('m',strtotime(date('Y').'-'.$i.'-01'))}}">{{date('F',strtotime('1-'.$i.'-'.date('Y')))}}</option>
                  @endfor
              </select>
              <p style="margin-bottom: 2px;" class="text-danger error-container error-p_month" id="error-p_month"></p> 
            </div>
              <div class="form-group">
                <label>Year : <span class="text-danger">*</span></label>
                <select class="form-control p_year" name="year">
                  <option value="">--Select--</option>
                    @for ($i=0;$i<=$diff;$i++)
                      <option value="{{date('Y',strtotime('2020-01-01'.' +'.$i.' years'))}}" @if(date('Y')==date('Y',strtotime('2020-01-01'.' +'.$i.' years'))) selected @endif>{{date('Y',strtotime('2020-01-01'.' +'.$i.' years'))}}</option>
                    @endfor
                </select>
                <p style="margin-bottom: 2px;" class="text-danger error-container error-year" id="error-year"></p> 
              </div>

              <div class="form-group">
                <label>Report Type : <span class="text-danger">*</span></label><br>
                  <div class="form-check form-check-inline">
                      <input class="form-check-input report_type" type="checkbox" name="report_type[]" value="wip" checked>
                      <label class="form-check-label" for="report_type">WIP</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input report_type" type="checkbox" name="report_type[]" value="close" checked>
                    <label class="form-check-label" for="report_type">Close</label>
                  </div>
                  <p style="margin-bottom: 2px;" class="text-danger error-container error-report_type" id="error-report_type"></p> 
              </div>
              <div class="form-group">
                <label>Customer : </label>
                <select class="form-control customer" name="customer[]" id="customer" data-actions-box="true" data-selected-text-format="count>1" multiple>
                  {{-- <option value="">-Select-</option> --}}
                    @foreach($customers as $cust)
                        <option value="{{$cust->id}}">{{$cust->company_name.' - '.$cust->name}}</option>   
                    @endforeach
                  </select>
              </div>
              <p style="margin-bottom: 2px;" class="text-danger error-container error-all"></p>
            </div>
           <!-- Modal footer -->
           <div class="modal-footer">
              <button type="submit" class="btn btn-info progress_submit btn-disable">Submit </button>
              <button type="button" class="btn btn-danger btn-disable" data-dismiss="modal">Close</button>
           </div>
        </form>
     </div>
  </div>
</div>


  </div>
</div>


<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script>
  $(document).ready(function(){

      $('.customer').selectpicker({
        'liveSearch' : true,
        'liveSearchNormalize' : true,
        'liveSearchPlaceholder' : 'Select the Customer'
      });

      $('.user').selectpicker({
        'liveSearch' : true,
        'liveSearchNormalize' : true,
        'liveSearchPlaceholder' : 'Select the User'
      });

      $('.p_month').selectpicker();

      $(document).on('click','.ops_excel',function(){
                var _this=$(this);
                // // var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> Under Processing...';

                // _this.addClass('disabled-link');
                // // $('.mis_load').html(loadingText);
                // // var user_id     =    $(".customer_list").val();                
                // // var from_date   =    $(".from_date").val(); 
                // // var to_date     =    $(".to_date").val();  

                // $.ajax(
                // {
                    
                //     url: "{{ url('/') }}"+'/candidates/setData/',
                //     type: "get",
                //     data: {},
                //     datatype: "html",

                // })
                // .done(function(data)
                // {
                //     window.setTimeout(function(){
                //         _this.removeClass('disabled-link');
                //         // $('.mis_load').html("");
                //         // _this.html('<i class="far fa-file-archive"></i> Download Zip');
                //     },2000);
                    
                //     console.log(data);
                //     var path = "{{ route('/ops-export')}}";
                //     window.open(path);
                // })
                // .fail(function(jqXHR, ajaxOptions, thrownError)
                // {
                //     //alert('No response from server');
                // });

              $('#ops_data_form')[0].reset();
              $('.form-control').removeClass('border-danger');
              $('p.error-container').html("");
              $('.customer').selectpicker('refresh');
              $('.user').selectpicker('refresh');
              $('.type_result').html('');
              $('#ops_modal').modal({
                backdrop: 'static',
                keyboard: false
              });

      });

      $(document).on('submit', 'form#ops_data_form', function (event) {
         event.preventDefault();
         //clearing the error msg
         $('p.error-container').html("");
         $('.form-control').removeClass('border-danger');
         var form = $(this);
         var data = new FormData($(this)[0]);
         var url = form.attr("action");
         var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
         $('.btn-disable').attr('disabled',true);
         if($('.ops_submit').html()!== loadingText)
         {
            $('.ops_submit').html(loadingText);
         }
         $.ajax({
            type: form.attr('method'),
            url: url,
            data: data,
            cache: false,
            contentType: false,
            processData: false,      
            success: function (response) {
                  window.setTimeout(function(){
                    $('.btn-disable').attr('disabled',false);
                    $('.ops_submit').html('Submit');
                  },2000);
                  // console.log(response);
                  //show the form validates error
                  if(response.success==false ) {                              
                     for (control in response.errors) {  
                        $('.'+control).addClass('border-danger'); 
                        $('.error-' + control).html(response.errors[control]);
                     }
                  }
                  if(response.success==true)
                  {
                     window.open(response.url);
                     $('#ops_modal').modal('hide');
                  }
                  else if(response.success==false)
                  {
                     $('.error-all').html(response.message);
                  }
                  else
                  {
                     $('.error-all').html('Something Went Wrong !!');
                  }
            },
            error: function (xhr, textStatus, errorThrown) {
                  // alert("Error: " + errorThrown);
            }
         });
         return false;
      });

      $(document).on('click','.mis_excel',function(){
          var _this=$(this);
          // var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> Under Processing...';

                _this.addClass('disabled-link');
                // $('.mis_load').html(loadingText);
                // var user_id     =    $(".customer_list").val();                
                // var from_date   =    $(".from_date").val(); 
                // var to_date     =    $(".to_date").val();  

                $.ajax(
                {
                    
                    url: "{{ url('/') }}"+'/candidates/setData/',
                    type: "get",
                    data: {},
                    datatype: "html",

                })
                .done(function(data)
                {
                    window.setTimeout(function(){
                        _this.removeClass('disabled-link');
                        // $('.mis_load').html("");
                        // _this.html('<i class="far fa-file-archive"></i> Download Zip');
                    },2000);
                    
                    console.log(data);
                    var path = "{{ route('/mis-export')}}";
                    window.open(path);
                })
                .fail(function(jqXHR, ajaxOptions, thrownError)
                {
                    //alert('No response from server');
                });

      });

      $(document).on('click','.sales_excel',function(){
          var _this=$(this);
          // var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> Under Processing...';

                // _this.addClass('disabled-link');
                // $('.mis_load').html(loadingText);
                // var user_id     =    $(".customer_list").val();                
                // var from_date   =    $(".from_date").val(); 
                // var to_date     =    $(".to_date").val();  

                // $.ajax(
                // {
                    
                //     url: "{{ url('/') }}"+'/candidates/setData/',
                //     type: "get",
                //     data: {},
                //     datatype: "html",

                // })
                // .done(function(data)
                // {
                //     window.setTimeout(function(){
                //         _this.removeClass('disabled-link');
                //         // $('.mis_load').html("");
                //         // _this.html('<i class="far fa-file-archive"></i> Download Zip');
                //     },2000);
                    
                //     console.log(data);
                //     var path = "{{ route('/sales-tracker')}}";
                //     window.open(path);
                // })
                // .fail(function(jqXHR, ajaxOptions, thrownError)
                // {
                //     //alert('No response from server');
                // });
              $('#sales_data_form')[0].reset();
              $('.form-control').removeClass('border-danger');
              $('p.error-container').html("");
              $('.customer').selectpicker('refresh');
              $('.type_result').html('');
              $('#sales_modal').modal({
                backdrop: 'static',
                keyboard: false
              });

      });

      $(document).on('click','.progress_excel',function(){
          var _this=$(this);
          // var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> Under Processing...';

                // _this.addClass('disabled-link');
                // $('.mis_load').html(loadingText);
                // var user_id     =    $(".customer_list").val();                
                // var from_date   =    $(".from_date").val(); 
                // var to_date     =    $(".to_date").val();  

                // $.ajax(
                // {
                    
                //     url: "{{ url('/') }}"+'/candidates/setData/',
                //     type: "get",
                //     data: {},
                //     datatype: "html",

                // })
                // .done(function(data)
                // {
                //     window.setTimeout(function(){
                //         _this.removeClass('disabled-link');
                //         // $('.mis_load').html("");
                //         // _this.html('<i class="far fa-file-archive"></i> Download Zip');
                //     },2000);
                    
                //     console.log(data);
                //     var path = "{{ route('/sales-tracker')}}";
                //     window.open(path);
                // })
                // .fail(function(jqXHR, ajaxOptions, thrownError)
                // {
                //     //alert('No response from server');
                // });
              $('#progress_data_form')[0].reset();
              $('.form-control').removeClass('border-danger');
              $('p.error-container').html("");
              $('.p_month').selectpicker('refresh');
              $('.customer').selectpicker('refresh');
              $('#progress_modal').modal({
                backdrop: 'static',
                keyboard: false
              });

      });

      $(document).on('submit', 'form#sales_data_form', function (event) {
         event.preventDefault();
         //clearing the error msg
         $('p.error-container').html("");
         $('.form-control').removeClass('border-danger');
         var form = $(this);
         var data = new FormData($(this)[0]);
         var url = form.attr("action");
         var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
         $('.btn-disable').attr('disabled',true);
         if($('.sale_submit').html()!== loadingText)
         {
            $('.sale_submit').html(loadingText);
         }
         $.ajax({
            type: form.attr('method'),
            url: url,
            data: data,
            cache: false,
            contentType: false,
            processData: false,      
            success: function (response) {
                  window.setTimeout(function(){
                    $('.btn-disable').attr('disabled',false);
                    $('.sale_submit').html('Submit');
                  },2000);
                  // console.log(response);
                  //show the form validates error
                  if(response.success==false ) {                              
                     for (control in response.errors) {  
                        $('.'+control).addClass('border-danger'); 
                        $('.error-' + control).html(response.errors[control]);
                     }
                  }
                  if(response.success==true)
                  {
                     window.open(response.url);
                      // var path = "{{url('/sales-dashboard')}}";
                      // window.open(path);
                     $('#sales_modal').modal('hide');
                  }
                  else if(response.success==false)
                  {
                     $('.error-all').html(response.message);
                  }
                  else
                  {
                     $('.error-all').html('Something Went Wrong !!');
                  }
            },
            error: function (xhr, textStatus, errorThrown) {
                  // alert("Error: " + errorThrown);
            }
         });
         return false;
      });

      $(document).on('submit', 'form#progress_data_form', function (event) {
         event.preventDefault();
         //clearing the error msg
         $('p.error-container').html("");
         $('.form-control').removeClass('border-danger');
         var form = $(this);
         var data = new FormData($(this)[0]);
         var url = form.attr("action");
         var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
         $('.btn-disable').attr('disabled',true);
         if($('.progress_submit').html()!== loadingText)
         {
            $('.progress_submit').html(loadingText);
         }
         $.ajax({
            type: form.attr('method'),
            url: url,
            data: data,
            cache: false,
            contentType: false,
            processData: false,      
            success: function (response) {
                  window.setTimeout(function(){
                    $('.btn-disable').attr('disabled',false);
                    $('.progress_submit').html('Submit');
                  },2000);
                  // console.log(response);
                  //show the form validates error
                  if(response.success==false ) {                              
                     for (control in response.errors) {  
                        $('.'+control).addClass('border-danger'); 
                        $('.error-' + control).html(response.errors[control]);
                     }
                  }
                  if(response.success==true)
                  {
                     window.open(response.url);
                     $('#progress_modal').modal('hide');
                  }
                  else if(response.success==false)
                  {
                     $('.error-all').html(response.message);
                  }
                  else
                  {
                     $('.error-all').html('Something Went Wrong !!');
                  }
            },
            error: function (xhr, textStatus, errorThrown) {
                  // alert("Error: " + errorThrown);
            }
         });
         return false;
      });

      $(document).on('change','.type',function(){
          var _this = $(this);

          $('.type_result').html('');
          if(_this.val()!='')
          {
              var type = _this.val();

              if(type.toLowerCase()=='monthly'.toLowerCase())
              {
                  $('.type_result').html(`<div class="row">
                                            <div class="col-sm-6">
                                              <div class="form-group">
                                                <label>Month : <span class="text-danger">*</span></label>
                                                <select class="form-control month" name="month">
                                                  <option value="">--Select--</option>
                                                    @for ($i=1;$i<=date('n');$i++)
                                                      <option value="{{date('m',strtotime(date('Y').'-'.$i.'-01'))}}">{{date('F',strtotime('1-'.$i.'-'.date('Y')))}}</option>
                                                    @endfor
                                                </select>
                                                <p style="margin-bottom: 2px;" class="text-danger error-container error-month" id="error-month"></p> 
                                              </div>
                                            </div>
                                            <div class="col-sm-6">
                                            <div class="form-group">
                                              @php
                                                $start_year = 2020;
                                                $end_year = date('Y');

                                                $diff = abs($end_year - $start_year);
                                              @endphp
                                              <label>Year : <span class="text-danger">*</span></label>
                                              <select class="form-control year" name="year">
                                                <option value="">--Select--</option>
                                                  @for ($i=0;$i<=$diff;$i++)
                                                    <option value="{{date('Y',strtotime('2020-01-01'.' +'.$i.' years'))}}" @if(date('Y')==date('Y',strtotime('2020-01-01'.' +'.$i.' years'))) selected @endif>{{date('Y',strtotime('2020-01-01'.' +'.$i.' years'))}}</option>
                                                  @endfor
                                              </select>
                                              <p style="margin-bottom: 2px;" class="text-danger error-container error-year" id="error-year"></p> 
                                            </div>
                                            </div>
                                            </div>`);
              }
              else if(type.toLowerCase()=='quaterly'.toLowerCase())
              {
                $('.type_result').html(`<div class="row">
                                        <div class="col-sm-6">
                                        <div class="form-group">
                                          <label>Quater : <span class="text-danger">*</span></label>
                                          <select class="form-control quater" name="quater">
                                            <option value="">--Select--</option>
                                             <option value="q1">April - June</option>
                                             <option value="q2">July - September</option>
                                             <option value="q3">October - December</option>
                                             <option value="q4">January - March</option>
                                          </select>
                                          <p style="margin-bottom: 2px;" class="text-danger error-container error-quater" id="error-quater"></p> 
                                        </div>
                                        </div>
                                        <div class="col-sm-6">
                                        <div class="form-group">
                                          @php
                                            $start_year = 2020;
                                            $end_year = date('Y');

                                            $diff = abs($end_year - $start_year);
                                          @endphp
                                          <label>Year : <span class="text-danger">*</span></label>
                                          <select class="form-control year" name="year">
                                            <option value="">--Select--</option>
                                              @for ($i=0;$i<=$diff;$i++)
                                                <option value="{{date('Y',strtotime('2020-01-01'.' +'.$i.' years'))}}" @if(date('Y')==date('Y',strtotime('2020-01-01'.' +'.$i.' years'))) selected @endif>{{date('Y',strtotime('2020-01-01'.' +'.$i.' years'))}}</option>
                                              @endfor
                                          </select>
                                          <p style="margin-bottom: 2px;" class="text-danger error-container error-year" id="error-year"></p> 
                                        </div>
                                        </div>
                                        </div>`);
              }
              else if(type.toLowerCase()=='yearly'.toLowerCase())
              {
                $('.type_result').html(`<div class="form-group">
                                          @php
                                            $start_year = 2020;
                                            $end_year = date('Y');
                                            $diff = abs($end_year - $start_year);
                                          @endphp
                                          <label>Year : <span class="text-danger">*</span></label>
                                          <select class="form-control year" name="year">
                                            <option value="">--Select--</option>
                                              @for ($i=0;$i<=$diff;$i++)
                                                <option value="{{date('Y',strtotime('2020-01-01'.' +'.$i.' years'))}}" @if(date('Y')==date('Y',strtotime('2020-01-01'.' +'.$i.' years'))) selected @endif>{{date('Y',strtotime('2020-01-01'.' +'.$i.' years'))}}</option>
                                              @endfor
                                          </select>
                                          <p style="margin-bottom: 2px;" class="text-danger error-container error-year" id="error-year"></p> 
                                        </div>`);
              }
          }
      });

      $(document).on('change','.year',function(){
        var _this = $(this);
        var year = new Date().getFullYear();
        if(_this.val()!='')
        {
            if(_this.val()==year)
            {
                $('.month').html(` <option value="">--Select--</option>
                    @for ($i=1;$i<=date('n');$i++)
                      <option value="{{date('m',strtotime(date('Y').'-'.$i.'-01'))}}">{{date('F',strtotime('1-'.$i.'-'.date('Y')))}}</option>
                    @endfor`);
            }
            else
            {
                $('.month').html(`
                    <option value="">--Select--</option>
                    @for ($i=1;$i<=12;$i++)
                      <option value="{{date('m',strtotime(date('Y').'-'.$i.'-01'))}}">{{date('F',strtotime('1-'.$i.'-'.date('Y')))}}</option>
                    @endfor
                `);
            }
        }
        // else
        // {
        //     alert('Select the specific year');

        //     $('.year option[value="2021"]').attr('selected', 'selected').change();

        //     $('.month').html(` <option value="">--Select--</option>
        //             @for ($i=1;$i<=date('n');$i++)
        //               <option value="{{date('m',strtotime(date('Y').'-'.$i.'-01'))}}">{{date('F',strtotime('1-'.$i.'-'.date('Y')))}}</option>
        //             @endfor`);
        // }
      });

      $(document).on('change','.p_year',function(){
        var _this = $(this);
        var year = new Date().getFullYear();
        if(_this.val()!='')
        {
            if(_this.val()==year)
            {
              $('.p_month').selectpicker('destroy');
              $(".p_month").empty();

                $('.p_month').html(`
                    @for ($i=1;$i<=date('n');$i++)
                      <option value="{{date('m',strtotime(date('Y').'-'.$i.'-01'))}}">{{date('F',strtotime('1-'.$i.'-'.date('Y')))}}</option>
                    @endfor`);

                $('.p_month').selectpicker();
            }
            else
            {
                $('.p_month').selectpicker('destroy');
                $(".p_month").empty();

                $('.p_month').html(`
                    @for ($i=1;$i<=12;$i++)
                      <option value="{{date('m',strtotime(date('Y').'-'.$i.'-01'))}}">{{date('F',strtotime('1-'.$i.'-'.date('Y')))}}</option>
                    @endfor
                `);

                $('.p_month').selectpicker();
            }
        }
        // else
        // {
        //     alert('Select the specific year');

        //     $('.year option[value="2021"]').attr('selected', 'selected').change();

        //     $('.month').html(` <option value="">--Select--</option>
        //             @for ($i=1;$i<=date('n');$i++)
        //               <option value="{{date('m',strtotime(date('Y').'-'.$i.'-01'))}}">{{date('F',strtotime('1-'.$i.'-'.date('Y')))}}</option>
        //             @endfor`);
        // }
      });
  });
</script>

@endsection
