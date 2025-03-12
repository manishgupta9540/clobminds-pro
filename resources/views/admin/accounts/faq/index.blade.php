@extends('layouts.admin')
@section('content')
<div class="main-content-wrap sidenav-open d-flex flex-column">
   <!-- ============ Body content start ============= -->
   <div class="main-content">
   @php
      // $ADD_ACCESS    = false;
      $REASSIGN_ACCESS   = false;
      $DASHBOARD_ACCESS =  false;
      $VIEW_ACCESS   = false;
      $DASHBOARD_ACCESS    = Helper::can_access('Dashboard','');//passing action title and route group name
      $REASSIGN_ACCESS    = Helper::can_access('Reassign','');//passing action title and route group name
      $VIEW_ACCESS   = Helper::can_access('View Task','');//passing action title and route group name
   @endphp
      {{-- <div class="row">
         <div class="page-header ">
            <div class=" align-items-center">
               <div class="col">
                  <h3 class="page-title">Account / Billing </h3>
               </div>
            </div>
         </div>
      </div> --}}
      <div class="row">
         <div class="col-sm-11">
             <ul class="breadcrumb">
             @if($DASHBOARD_ACCESS)
             <li>
             <a href="{{ url('/home') }}">Dashboard</a>
             </li>
             <li>
                 <a href="{{ url('/settings/general') }}">Accounts</a>
             </li>
             <li>FAQ</li>
             @else
             <li>
                 <a href="{{ url('/settings/general') }}">Accounts</a>
             </li>
             <li>FAQ</li>
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
               <div class="col-md-3 content-container">
                  <!-- left-sidebar -->
                  @include('admin.accounts.left-sidebar') 
               </div>
                  <!-- start right sec -->
                  <div class="col-md-9 content-wrapper" style="background:#fff">
                     <div class="formCover" style="height: 100vh;">
                        <!-- section -->
                        <section>
                           <div class="col-sm-12 py-2">
                              @php
                                 $ADD_ACCESS    = false;
                                 $VIEW_ACCESS   = false;
                                 $EDIT_ACCESS = false;
                                 $DELETE_ACCESS   = false;
                                 // $SLA_ACCESS   = false;
                                 $ADD_ACCESS    = Helper::can_access('Create FAQ ','');
                                 $VIEW_ACCESS   = Helper::can_access('FAQ','');
                                 $EDIT_ACCESS = Helper::can_access('Edit FAQ','');
                                 $DELETE_ACCESS = Helper::can_access('Delete FAQ','');
                                 // $SLA_ACCESS = Helper::can_access('SLA','');

                                 
                                 // $REPORT_ACCESS   = false;
                                 // $VIEW_ACCESS   = false;SLA
                                 @endphp 
                                @if ( $VIEW_ACCESS)
                                    
                                
                                 <!-- row -->
                                 <div class="row">
                                    <div class="col-md-6">
                                       <h4 class="card-title mb-1 mt-3">FAQ  </h4>
                                       {{-- <p class="pb-border"> Your billing overview/history  </p> --}}
                                    </div>
                                    <div class="col-md-6 text-right">
                                       @if ($ADD_ACCESS)
                                       <a href="{{route('/faq/create')}}" class="mt-3 btn btn-sm btn-info"><i class="fa fa-plus"></i> Create FAQ</a> 
                                       @endif
                                    </div>
                                    @if ($message = Session::get('success'))
                                       <div class="col-md-12">   
                                          <div class="alert alert-success">
                                          <strong>{{ $message }}</strong> 
                                          </div>
                                       </div>
                                    @endif
                                    <div class="col-md-12 pt-3">

                                    {{-- <table class="table table-bordered">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Question</th>
                                            <th>Answer</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                       @if(count($faq)>0)
                                       @foreach ($faq as $key=>$f)
                                        <tr>
                                            <td>{{$key+1}}</td>
                                            <td>{{ Str::limit($f->question,20) }}</td>
                                            <td>{!! Str::limit(strip_tags($f->answer),20) !!}</td>
                                            <td> 
                                               <a href="{{url('/faq/edit',['id'=>base64_encode($f->id)])}}" title="edit"><i class="far fa-edit"></i></a>
                                               <a href="{{url('faq/delete',['id'=>base64_encode($f->id)])}}" title="delete" onclick="return del()"><i class="fa fa-trash text-danger"></i></a>
                                            </td>
                                        </tr>
                                        @endforeach
                                        @else
                                          <tr class="text-center">
                                             <td colspan="4">No Data Available</td>
                                          </tr>
                                        @endif   
                                    </tbody>
                                    </table> --}}
                                    @if(count($faq)>0)
                                       @foreach ($faq as $key=>$f)
                                          <div class="faq pb-2" id="accordion">
                                             <div class="card">
                                                <div class="card-header" id="faqHeading-1">
                                                   <div class="mb-0">
                                                      <div class="float-right pt-3 pr-3">
                                                         @if ($EDIT_ACCESS)
                                                         <a href="{{url('/faq/edit',['id'=>base64_encode($f->id)])}}" style="font-size: 16px;" title="edit"><i class="far fa-edit text-info"></i></a>
                                                         @endif
                                                         @if ($DELETE_ACCESS)
                                                         <a href="{{url('faq/delete',['id'=>base64_encode($f->id)])}}" style="font-size: 16px;" title="delete" onclick="return del()"><i class="fa fa-trash text-danger"></i></a>   
                                                         @endif
                                                      </div>
                                                      <h5 class="faq-title" data-toggle="collapse" data-target="#faqCollapse-{{$key+1}}" data-aria-expanded="true" data-aria-controls="faqCollapse-1">
                                                            <span class="badge">{{$key + 1}}</span> {{ $f->question }}
                                                      </h5>
                                                   </div>
                                                </div>
                                                <div id="faqCollapse-{{$key+1}}" class="collapse" aria-labelledby="faqHeading-1" data-parent="#accordion">
                                                   <div class="card-body">
                                                      <label id="answer" class="form-control answer " style="height: auto" name="answer" placeholder="e.g. Answer">{!!$f->answer!!}</label>
                                                   </div>
                                                </div>
                                             </div>
                                          </div>
                                       @endforeach
                                    @else
                                          <p class="text-muted">No data Found</p>
                                    @endif
                                    </div>
                                 </div>
                                 <!-- ./business detail -->
                                 @else
                                     <div class="mt-5"><strong>you have not any permission to access this page</strong></div>  
                                @endif
                           </div>
                        </section>
                        <!-- ./section -->
                        <!--  -->
                        <!-- ./section -->
                     </div>
                  </div>
                  <!-- end right sec -->
               
            
         
      </div>
   </div>
</div>
@stack('scripts')
<script>
   function del()
   {
      var result=confirm("Are You Sure You Want to Delete?");
      if(result){
         return true;
      }
      else{
         return false;
      }
   }

   // $(document).ready(function() {
   //    $('.answer').summernote('disable'
   //       // placeholder: 'e.g. Answer',
   //       // height: 100
   //    );
   // });
</script>
{{-- <script type="text/javascript">
   //
   $(document).ready(function() {
   //
   $(document).on('click','#clickSelectFile',function(){ 
   
       $('#fileupload').trigger('click');
       
   });
   
   $(document).on('click','.remove-image',function(){ 
       
       $('#fileupload').val("");
       $(this).parent('.image-area').detach();
   
   });
   
   $(document).on('change','#fileupload',function(e){ 
   // alert('test');
   //show process 
   // $("").html("Uploading...");
   $("#fileUploadProcess").html("<img src='{{asset('images/process-horizontal.gif')}}' >"); 
   
   var fd = new FormData();
   var inputFile = $('#fileupload')[0].files[0];
   fd.append('file',inputFile);
   fd.append('_token', '{{csrf_token()}}');
   //
   
     $.ajax({
             type: 'POST',
             url: "{{ url('/company/upload/logo') }}",
             data: fd,
             processData: false,
             contentType: false,
             success: function(data) {
               console.log(data);
               if (data.fail == false) {
               
               //reset data
               $('#fileupload').val("");
               $("#fileUploadProcess").html("");
               //append result
               $("#fileResult").html("<div class='image-area'><img src='"+data.filePrev+"'  alt='Preview'><a class='remove-image' href='javascript:;' style='display: inline;'>&#215;</a><input type='hidden' name='fileID[]' value='"+data.file_id+"'></div>");
   
               } else {
   
                 $("#fileUploadProcess").html("");
                 alert("please upload valida file! allowed file type , Image, PDF, Doc, Xls and txt ");
                 console.log("file error!");
                 
               }
             },
             error: function(error) {
                 console.log(error);
                 // $(".preview_image").attr("src","{{asset('images/file-preview.png')}}"); 
             }
         }); 
       return false;
   
   });
   
   
   });
                     
</script>   --}}
@endsection
