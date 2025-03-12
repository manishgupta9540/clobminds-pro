@extends('layouts.admin')
<style>
   .sticky {
   position: fixed;
    top: 8%;
    width: 100%;
    z-index: 999;
    background: #eeeeee;
    border: 1px solid #eee;
    border-radius: 3px;
  
}

.sticky li{
   color: #fff !important;
}
.col-sm-11.breadcrum1 {
    position: relative;
    top: -9px;
}
</style>
@section('content')
<div class="main-content-wrap sidenav-open d-flex flex-column">
<!-- ============ Body content start ============= -->
<div class="main-content">
    <div class="row">
        <div class="col-sm-11">
            <ul class="breadcrumb">
            <li><a href="{{ url('/home') }}">Dashboard</a></li>
            <li><a href="{{ url('/candidates') }}">Candidate</a></li>
            <li>Detail</li>
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
   <div class="card text-left">
      <div class="card-body" style="padding:0px">
         <div class="row">
            <div class="col-md-3 col-12">
               <div class="span10 offset1">
                  <div id="modalTab">
                     <div class="tab-content">
                        <div class="tab-pane active" id="about">
                           <img src="{{ asset('admin/images/profile-default-avtar.jpg') }}" name="aboutme" width="140" height="140" border="0" class="img-circle">
                           <p class="m-0 text-24"> {{ $candidate->name}} 
                              {{-- <a class="text-success mr-2" href="#"><i class="nav-icon i-Pen-2 font-weight-bold" style="font-size: 10px;"></i></a> --}}
                           </p>
                           <p class="text-muted m-0"> &nbsp; </p>
                           <ul class="nav nav-tabs profile-nav mb-4" id="profileTab" role="tablist">
                              <li class="nav-item"><a class="nav-link" id="timeline-tab" href="{{ url('/candidates/notes',['id'=> base64_encode($candidate->id)]) }}"> <i class="fa fa-tasks"> </i><br> Note</a></li>
                              <li class="nav-item"><a class="nav-link " id="timeline-tab" href="{{ url('/email/list',['id'=> base64_encode($candidate->id)])}}"> <i class="fa fa-envelope"></i><br> Email</a></li>
                              <li class="nav-item"><a class="nav-link" id="timeline-tab"> <i class="fa fa-phone-square" aria-hidden="true"></i> <br> Call </a></li>
                              <li class="nav-item"><a class="nav-link" id="timeline-tab"> <i class="fa fa-plus" aria-hidden="true"></i><br> Log </a></li>
                              <li class="nav-item"><a class="nav-link" id="timeline-tab" href="{{ url('/candidates/task',['id'=> base64_encode($candidate->id)]) }}"> <i class="fa fa-tasks" aria-hidden="true"></i><br> Task  </a></li>
                              <li class="nav-item"><a class="nav-link" id="timeline-tab"> <i class="fa fa-calendar" aria-hidden="true"></i> <br> Meet </a></li>
                              {{-- <li class="nav-item"><a class="nav-link" id="timeline-tab" href="{{url('/candidate/profile/report',['id'=> base64_encode($candidate->id)])}}"> <i class="fa fa-window-maximize" aria-hidden="true"></i> <br> Report </a></li> --}}

                              @if ($report != '')
                                <li class="nav-item"><a class="nav-link reportsPreviewBox" id="timeline-tab" data-id="{{ base64_encode($report->id) }}"  href="#"> <i class="fa fa-window-maximize" aria-hidden="true"></i> <br> Report </a></li>
                              @endif

                           </ul>
                           <ul style="list-style: none; text-align: left; padding: 0">
                              
                           </ul>
                           @include('admin.candidates.profile-info')
                        </div>
                     </div>
                  </div>
               </div>
            </div> 
            <div class="col-md-9 col-12" style="background: #f6f8fc;">
               <h4 class="card-title mb-3"> </h4>
               <ul class="nav nav-pills" id="myPillTab" role="tablist" style="border-bottom: 1px solid #cdd1d8;">
                  <li class="nav-item"><a class="nav-link " id="home-icon-pill" href="{{ url('/candidates/show',['id'=> base64_encode($candidate->id)]) }}" > Activity </a></li>
                  <li class="nav-item"><a class="nav-link" id="profile-icon-pill"  href="{{ url('/candidates/jaf-info',['id'=> base64_encode($candidate->id)]) }}" > BGV </a></li>
                  <li class="nav-item"><a class="nav-link " id="notes-icon-pill" href="{{ url('/candidates/notes',['id'=> base64_encode($candidate->id)]) }}" role="tab" > Notes </a></li>
                  <li class="nav-item"><a class="nav-link active show" id="email-icon-pill"  href="{{ url('/candidates/email/list',['id'=> base64_encode($candidate->id)]) }}" role="tab" > Emails </a></li>
                  <li class="nav-item"><a class="nav-link" id="contact-icon-pill" data-toggle="pill" href="#contactPIll" role="tab" > Call </a></li>
                  <li class="nav-item"><a class="nav-link " id="contact-icon-pill"  href="{{ url('/candidates/task',['id'=> base64_encode($candidate->id)]) }}" > Task </a></li>
                  <li class="nav-item"><a class="nav-link" id="contact-icon-pill" data-toggle="pill" href="#contactPIll" role="tab" > Meeting </a></li>
                  {{-- <li class="nav-item"><a class="nav-link" id="contact-icon-pill"  href="{{url('/candidate/profile/report',['id'=> base64_encode($candidate->id)])}}"  > Report </a></li>
                   --}}

                   @if ($report != '')
                        <li class="nav-item"><a class="nav-link reportsPreviewBox" id="contact-icon-pill" data-id="{{ base64_encode($report->id) }}"  href="#"> Report </a></li>
                    @endif

               </ul>
               <div class="tab-content" id="myPillTabContent" style="padding: 0.5rem;">
                  {{-- <div class="row" style="margin-bottom:15px">
                     <div class="col-md-2">
                        <div class="search-bar" style="padding: 10px 0px;">
                           Filter By :
                        </div>
                     </div>
                     <div class="col">
                        <button class="btn  btn-lg dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: #6a88c7!important;">  Filter Activity   </button>
                        <div class="dropdown-menu"><a class="dropdown-item" href="#">Action</a><a class="dropdown-item" href="#">Another Action</a><a class="dropdown-item" href="#">Something Else Here</a></div>
                        <button class="btn  btn-lg dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: #6a88c7!important;">  All Users </button>
                        <div class="dropdown-menu"><a class="dropdown-item" href="#">Action</a><a class="dropdown-item" href="#">Another Action</a><a class="dropdown-item" href="#">Something Else Here</a></div>
                        <button class="btn  btn-lg dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: #6a88c7!important;">  All Items </button>
                        <div class="dropdown-menu"><a class="dropdown-item" href="#">Action</a><a class="dropdown-item" href="#">Another Action</a><a class="dropdown-item" href="#">Something Else Here</a></div>
                     </div>
                     <div class="search-bar" style="padding: 10px 0px;">
                        <i class="search-icon text-muted i-Magnifi-Glass1"></i>                   
                     </div> --}}
                  </div>
                  <div class="tab-pane fade active show" id="homePIll" role="tabpanel" aria-labelledby="email-icon-pill">
                     <div class="inbox-main-sidebar-container sidebar-container" data-sidebar-container="main" style="padding:10px;">
                        <div class="inbox-main-content sidebar-content" data-sidebar-content="main">
                           <!-- SECONDARY SIDEBAR CONTAINER-->
                           <div class="inbox-secondary-sidebar-container box-shadow-1 sidebar-container" data-sidebar-container="secondary" style="background: #fff;">
                              <!-- Secondary Inbox sidebar-->
                              <div class="inbox-secondary-sidebar perfect-scrollbar rtl-ps-none ps sidebar" data-sidebar="secondary" style="z-index:0;left: 0px;">
                                 <i class="sidebar-close i-Close" data-sidebar-toggle="secondary"></i>
                                       <div class="data-card">
                                          <div class="text-right" style="margin-bottom: 10px">
                                            <a class="btn btn-success " href="#" > <i class="fa fa-plus"></i> Add New </a>              
                                          </div>
                                            <table class="table table-bordered table-hover candidatesTable ">
                                            <thead>
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th scope="col">Email ID</th>
                                                    <th scope="col">Subject</th>
                                                    <th scope="col">Content</th>
                                                    <th scope="col">Action</th>
                                                </tr>
                                            </thead>
                                            </table>
                                            </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="tab-pane fade" id="profilePIll" role="tabpanel" aria-labelledby="profile-icon-pill">
                  </div>
                  <div class="tab-pane fade" id="contactPIll" role="tabpanel" aria-labelledby="contact-icon-pill"> 
                  </div>
                  <div class="tab-pane fade" id="notesPIll" role="tabpanel" aria-labelledby="notes-icon-pill"> 
                  </div>
                  
               </div>
            </div>
            
         </div>
      </div>
   </div>
</div>

<div class="modal"  id="preview">
    <div class="modal-dialog modal-lg" style="max-width: 80% !important;">
       <div class="modal-content">
          <!-- Modal Header -->
          <div class="modal-header">
             <h4 class="modal-title">Report Preview</h4>
             <button type="button" class="close" style="top: 12px;!important; color: red; " data-dismiss="modal">&times;</button>
          </div>
          <!-- Modal body -->
          
             <div class="modal-body">
             <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-all"> </p> 
                <iframe 
                    src="" 
                    style="width:100%; height:620px; " 
                    frameborder="0" id="preview_pdf">
                </iframe>
             </div>
             <!-- Modal footer -->
             <div class="modal-footer">
                
               
                <button type="button" class="btn btn-danger back" data-dismiss="modal">Close</button>
             </div>
         
       </div>
    </div>
 </div>
<script>
$(document).ready(function(){
    $('.reportsPreviewBox').click(function(){
        //   alert('ass');
            var report_id = $(this).attr('data-id');

            document.getElementById('preview_pdf').src="{{ url('/') }}"+"/candidate/report/preview/"+report_id;
        
            // alert(business_id);
            $('#preview').toggle();
    });

    $('.close').click(function(){
        $('#preview').hide();
    });
    $('.back').click(function(){
        $('#preview').hide();
    });
});
window.onscroll = function() {stickyFun()};

var navbar = document.getElementById("myPillTab");
var sticky = navbar.offsetTop;

function stickyFun() {
  if (window.pageYOffset >= sticky) {
    navbar.classList.add("sticky")
  } else {
    navbar.classList.remove("sticky");
  }
}
</script>
@endsection
