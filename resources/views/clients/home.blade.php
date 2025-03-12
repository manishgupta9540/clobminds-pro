@extends('layouts.client')
<style>
  .px-10
  {
    padding-left: 3px !important;
    padding-right: 3px !important;
  }
</style>
@section('content')

 <div class="main-content-wrap sidenav-open d-flex flex-column">
 <div class="main-content">         
      @php
        $PROGRESS_TRACKER    = false;
        $PROGRESS_TRACKER   = Helper::can_access('Progress Tracker','/my');//passing action title and route group name
      @endphp
        <div class="row">
            <div class="col-lg-10">
                <h3 class="mr-2"> Dashboard  </h3>
            </div>
            <div class="col-lg-2 pt-2">
              {{-- @if(Auth::user()->user_type=='client') --}}
                <div class="" style="float: right;">
                  {{-- <button class="btn btn-link progress_excel" type="button">
                    <i class="fas fa-file-excel"></i> Progress Tracker
                  </button><br> --}}
                  @if($PROGRESS_TRACKER)
                    <a href="{{url('/my/progress-export')}}" target="_blank" class="btn btn-link">
                      <i class="fas fa-file-excel"></i> Progress Tracker
                    </a><br>
                  @endif
                  {{-- <p class="text-danger text-center mis_load"></p> --}}
                </div>
              {{-- @endif --}}
            </div>

            {{-- <div class="col-lg-2">
                <div class="btn-group" style="float: right;">
                    <!-- <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Last 30 days
                    </button> -->

                    <!-- <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px,27px, 0px);">
                        <a class="dropdown-item" href="#">Today Only</a>
                        <a class="dropdown-item" href="#">This Week</a>
                        <a class="dropdown-item" href="#">This Month</a>
                        <a class="dropdown-item" href="#">This Year</a>
                    </div> -->
                </div>      
            </div> --}}
        </div>
        
                <div class="row">
                    <!-- 1st -->
                     
                     <div class="col-lg-4 col-sm-6 candidateCard"><div class="card card-icon-bg card-icon-bg-1 o-hidden mb-4 " style="background-color: #EFF9FB;border: 2px solid rgba(0, 0, 0, 0.06);box-shadow: 7px 5px 12px 1px rgb(0 0 0 / 6%);border-radius: 20px;">
                              <div class="card-body pd-10 ">
                                <i class="fa fa-users"></i>
                                  <div class="data-content">
                                      <div class="row">
                                        <div class="col-lg-12 top-heading-dash">
                                          <a class="text-24 line-height-2 mb-2 candidate_count" href="{{url('/my/candidates')}}">{{$candidates_count}}</a><br>
                                          <a class="mt-2 mb-0 sort_desc" href="{{url('/my/candidates')}}"> <strong>Total Cases</strong>  </a>
                                        </div>
                                      </div>
                                      <div class="row mt-20">
                                        <div class="col-lg-12 below-heading-dash">
                                          <a href="{{ url('/my/candidates/?case_wip=1') }}" class="mt-2 mb-0"><strong> WIP</strong></a>
                                          <a href="{{ url('/my/candidates/?case_wip=1') }}" class="text-18 line-height-2 jaf_customer_count counting"> {{$WIP_count}}</a>
                                        </div>
                                        <div class="col-lg-12 below-heading-dash">
                                          <a href="{{ url('/my/candidates/?insuff=1') }}" class="mt-2 mb-0"><strong> Insuff</strong></a>
                                          <a href="{{ url('/my/candidates/?insuff=1') }}" class="text-wh text-18 line-height-2 jaf_coc_count counting"> {{$count_total_insuff_case}}</a>
                                        </div>
                                        
                                        <div class="col-lg-12 below-heading-dash">
                                          <a href="{{ url('/my/candidates/?sendto=candidate&jafstatus1=pending&jafstatus2=draft') }}" class="mt-2 mb-0"><strong> Associate Pending </strong></a>
                                          <a href="{{ url('/my/candidates/?sendto=candidate&jafstatus1=pending&jafstatus2=draft') }}" class="text-wh text-18 line-height-2 mb-2 jaf_candidate_count counting">{{$candidate_bgv_pending}} </a>
                                        </div>
                                        <div class="col-lg-12 below-heading-dash">
                                          <a href="{{ url('/my/candidates/?sendto=candidate&jafstatus=filled') }}" class="mt-2 mb-0"><strong> Associate Completed </strong></a>
                                          <a href="{{ url('/my/candidates/?sendto=candidate&jafstatus=filled') }}" class="text-wh text-18 line-height-2 mb-2 jaf_candidate_count counting">{{$candidate_bgv_completed}}</a>
                                        </div>

                                      </div>
                                  </div>
                              </div>
                          </div>
                        </div>

                    <!-- 2nd box -->
                    <div class="col-lg-4 col-sm-6 jafCard"><div class="card card-icon-bg card-icon-bg-1 o-hidden mb-4" style="background-color: #EFF9FB;border: 2px solid rgba(0, 0, 0, 0.06);
    box-shadow: 7px 5px 12px 1px rgb(0 0 0 / 6%);border-radius: 20px;">
                          <div class="card-body pd-10 ">
                            <i class="fa fa-id-card"></i>
                              <div class="data-content">
                                  <div class="row">
                                    <div class="col-lg-12 top-heading-dash">
                                      <a href="{{url('/my/candidates')}}" class="text-24 line-height-2 mb-2"> {{$total_checks}} </a><br>
                                      <a href="{{url('/my/candidates')}}" class="mt-2 mb-0 sort_desc"> <strong>Total Checks</strong> </a>
                                    </div>
                                  </div>
                                  <div class="row mt-20">
                                      <div class="col-lg-12 below-heading-dash">
                                      <a href="{{url('/my/candidates')}}" class="mt-2 mb-0"><strong> Completed Checks </strong></a>
                                      <a href="{{url('/my/candidates')}}" class="text-wh text-18 line-height-2 counting"> {{$completed_checks}} </a>
                                          
                                      </div>
                                    <div class="col-lg-12 below-heading-dash">
                                      <a href="{{url('/my/candidates')}}" class="mt-2 mb-0"><strong> Pending Checks </strong></a>
                                      <a href="{{url('/my/candidates')}}" class="text-wh text-18 line-height-2 counting">  {{$incompleted_checks}} </a>
                                      
                                    </div>
                                    
                                  </div>
                                  
                              </div>
                          </div>
                      </div>
                    </div>

                    <!--  -->
                    <div class="col-lg-4 col-sm-6 checkCard"><div class="card card-icon-bg card-icon-bg-1 o-hidden mb-4" style="background-color: #EFF9FB;border: 2px solid rgba(0, 0, 0, 0.06);
box-shadow: 7px 5px 12px 1px rgb(0 0 0 / 6%);border-radius: 20px;">
                      <div class="card-body pd-10 ">
                        <i class="fa fa-book"></i>
                          <div class="data-content">
                              <div class="row">
                                <div class="col-lg-12 top-heading-dash">
                                  <a href="{{ url('/my/reports') }}" class="text-24 line-height-2 mb-2"> {{$reports}}  </a><br>
                                  <a href="{{ url('/my/reports') }}" class="mt-2 mb-0 sort_desc"> <strong>Total Reports</strong> </a>
                                </div>
                              </div>
                              <div class="row mt-20">
                                <div class="col-lg-12 below-heading-dash">
                                <a href="{{ url('/my/reports/?report_status1=completed&report_status2=interim') }}" class="mt-2 mb-0"><strong> Completed Report </strong></a>
                                  <a href="{{ url('/my/reports/?report_status1=completed&report_status2=interim') }}" class="text-wh text-18 line-height-2 counting"> {{$complete_report}} </a>
                                 
                                </div>
                                <div class="col-lg-12 below-heading-dash">
                                <a href="{{ url('/my/reports/?report_status=incomplete') }}" class="mt-2 mb-0"><strong> Pending Report </strong></a>
                                  <a href="{{ url('/my/reports/?report_status=incomplete') }}" class="text-wh text-18 line-height-2 counting"> {{$pending_report}} </a>
                                  
                                </div>
                                
                              </div>
                              
                          </div>
                      </div>
                  </div>
              </div> 
            <!-- end box -->


        </div>
        
      <!--  -->

      <div class="row">
        <div class="col-lg-10">
            <div class="btn-group">
                    {{-- <button class="btn  dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <h4> Most <span class="rm">Recent Checks <span> </h4>
                    </button> --}}
                    <!-- <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 27px, 0px);">
                      <a class="dropdown-item" href="#">Action</a>
                      <a class="dropdown-item" href="#">Another Action</a>
                      <a class="dropdown-item" href="#">Something Else Here</a>
                    </div> -->
            </div>
        </div>

        <div class="col-lg-2">
            <!-- <div class="btn-group" style="float: right;">
                    <button class="btn dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">  Show <span class="alm"> all Checks </span>
                    </button>
                    <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 27px, 0px);"><a class="dropdown-item" href="#">Action</a><a class="dropdown-item" href="#">Another Action</a><a class="dropdown-item" href="#">Something Else Here</a></div>
            </div>        -->
        </div>
      </div>
            @if(Auth::user()->user_type=='user')
              <div class="row">

                  <div class="col-lg-12 col-sm-12">
                      <div class="card mb-4">
                          <div class="card-body">
                              <div class="card-title openCheckOverview" > Checks Overview</div>
                                <div class="table-responsive" style="max-height: 300px;">
                                  <table class="table table-bordered table-hover" style="position:relative">
                                    <thead>
                                      <tr>
                                          <th style="position:sticky; top:0px" scope="col"><strong>Checks</strong></th>
                                          <th style="position:sticky; top:0px" scope="col"><strong>Completed</strong></th>
                                          <th style="position:sticky; top:0px" scope="col"><strong>Remaining</strong></th>
                                          <th style="position:sticky; top:0px" scope="col"><strong>Insuff</strong></th>
                                          <!-- <th style="position:sticky; top:0px" scope="col"><strong>Call</strong></th>
                                          <th style="position:sticky; top:0px" scope="col"><strong>SMS</strong></th>
                                          <th style="position:sticky; top:0px" scope="col"><strong>Link</strong></th> -->
                                      </tr>
                                  </thead>
                                    <tbody style="padding-top:40px;">
                                        @if (count($array_result)>0)
                                          @foreach ($array_result as $result)
                                              <tr>
                                                <td><strong>{{$result['check_name']}}</strong></td>
                                                <td>
                                                  <a href="{{ url('/my/candidates/?verify_status=success&service='.$result['check_id']) }}">{{$result['completed']}}</a>
                                                </td>
                                                <td><a href="{{ url('/my/candidates/?verification_status=null&service='.$result['check_id']) }}">{{$result['pending']}}</a></td>
                                                <td><a href="{{ url('/my/candidates/?insuffs=1&service='.$result['check_id'])}}">{{$result['insuff']}}</a></td>
                                                <!-- <td>0</td>
                                                <td>0</td>
                                                <td>0</td> -->
                                            
                                              </tr>
                                          @endforeach   
                                        @endif
                                    </tbody>
                                  </table>
                                </div>
                          </div>
                      </div>
                  </div>
              </div>
            @else
              <div class="row">
                  <div class="col-lg-12 col-sm-12">
                      <div class="card mb-4">
                          <div class="card-body">
                              <div class="card-title openCheckOverview" > Case Overview</div>
                                <div class="table-responsive" style="max-height: 300px;">
                                  <table class="table table-bordered table-hover" style="position:relative">
                                    <thead>
                                      <tr>
                                          <th style="position:sticky; top:0px" scope="col"><strong>Company Name</strong></th>
                                          <th style="position:sticky; top:0px" scope="col"><strong>Total Case</strong></th>
                                          <th style="position:sticky; top:0px" scope="col"><strong>BGV Pending</strong></th>
                                          <th style="position:sticky; top:0px" scope="col"><strong>BGV Complete</strong></th>
                                          <th style="position:sticky; top:0px" scope="col"><strong>Report Pending</strong></th>
                                          <th style="position:sticky; top:0px" scope="col"><strong>Report Complete</strong></th>
                                          <!-- <th style="position:sticky; top:0px" scope="col"><strong>Call</strong></th>
                                          <th style="position:sticky; top:0px" scope="col"><strong>SMS</strong></th>
                                          <th style="position:sticky; top:0px" scope="col"><strong>Link</strong></th> -->
                                      </tr>
                                  </thead>
                                    <tbody style="padding-top:40px;">
                                        @if (count($client_users)>0)
                                          @foreach ($client_users as $user)
                                              <tr>
                                                <td><strong>{{$user->company_name!=null ? $user->company_name : 'N/A'}}</strong></td>
                                                <td>
                                                  <a href="#">{{count(Helper::totalCaseAccess(Auth::user()->business_id,$user->id))}}</a>
                                                </td>
                                                <td><a href="#">{{count(Helper::jafPendingAccess(Auth::user()->business_id,$user->id))}}</a></td>
                                                <td><a href="#">{{count(Helper::jafCompleteAccess(Auth::user()->business_id,$user->id))}}</a></td>
                                                <td><a href="#">{{count(Helper::reportPendingAccess(Auth::user()->business_id,$user->id))}}</a></td>
                                                <td><a href="#">{{count(Helper::reportCompleteAccess(Auth::user()->business_id,$user->id))}}</a></td>
                                                <!-- <td>0</td>
                                                <td>0</td>
                                                <td>0</td> -->
                                            
                                              </tr>
                                          @endforeach   
                                        @endif
                                    </tbody>
                                  </table>
                                </div>
                          </div>
                      </div>
                  </div>
              </div>
            @endif
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="card-title">Checks Overview</div>
                            <div id="echartBar" style="height: 300px;"></div>
                            {{-- <div id="chart_div" style="height: 300px;"></div> --}}
                        </div>
                    </div>
                </div>          
              </div>
            </div>
          
      <!--  -->
 
      </div>

  </div>

<!-- modal -->
<!-- The Modal -->
  <div class="modal" id="checksModal">
    <div class="modal-dialog">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Checks OverView</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
          
          <div class="form-group">  HE </div>

        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
        
      </div>
    </div>
  </div>
<!-- ./modal -->

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script>
$(document).ready(function(){

  // $(document).on('click','.openCheckOverview', function(){
  //   $('#checksModal').modal();
  // });
});
</script>
@endsection
