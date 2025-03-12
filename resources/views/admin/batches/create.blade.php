@extends('layouts.admin')
@section('content')
<style>
    .disabled-link{
        pointer-events: none;
    }
</style>
<div class="main-content-wrap sidenav-open d-flex flex-column">
            <!-- ============ Body content start ============= -->
            <div class="main-content">				
                <div class="row">
                    <div class="col-sm-11">
                        <ul class="breadcrumb">
                        <li><a href="{{ url('/home') }}">Dashboard</a></li>
                        <li><a href="{{ url('/batches') }}">Batches</a></li>
                        <li>Create New</li>
                        </ul>
                    </div>
                    <!-- ============Back Button ============= -->
                    <div class="col-sm-1 back-arrow">
                        <div class="text-right">
                        <a href="{{url()->previous() }}"><i class="fas fa-arrow-circle-left fa-2x"></i></a>
                        </div>
                    </div>
                </div>
            <div class="row">
			<div class="card text-left">
               <div class="card-body" style="">
               
               <div class="col-md-8 offset-md-2">
                <form class="mt-2 " method="post"  action="{{url('/batches/store')}}" enctype="multipart/form-data" id="createBatchfrm" >
                    @csrf
                   <div class="row">
                
                    @if ($message = Session::get('error'))
                    <div class="col-md-12">   
                        <div class="alert alert-danger">
                        <strong>{{ $message }}</strong> 
                        </div>
                    </div>
                    @endif
    
                    <div class="col-md-10">
                      <h4 class="card-title mb-1" style="border-bottom:1px solid #ddd;">Add a new Batch </h4> 
                        <p class="mt-1"> Fill the required details </p>			
                    </div>
                     
                   <div class="col-md-10">	
                       
                       
                        <!-- select a customer  -->
                        <div class="form-group">
                            <label for="service">Select a Customer <span class="text-danger">*</span></label>
                            <select class="form-control customer" name="customer">
                                <option value="">-Select-</option>
                                @if( count($customers) > 0 )
                                    @foreach($customers as $item)
                                    <option value="{{ $item->id }}">{{ ucfirst($item->company_name).' '.'('.$item->name.')' }}</option>
                                    @endforeach
                                @endif
                            </select>
                            
                            <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-customer"></p>
                        </div>

                        <!-- select a SLA of customer  -->
                        <div class="form-group">
                            <label for="service">Select a SLA <span class="text-danger">*</span></label>
                            <select class="form-control slaList sla" name="sla">
                                <option value="">-Select-</option>
                            </select>
                            <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-sla"></p>
                        </div>
                        
                        <div class="form-group SLAResult">
                        
                        </div>

                        <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-services"></p>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="batch">Batch Name</label>
                                        <input type="text" name="batch" class="form-control batch" id="batch" placeholder="Enter batch name" value="{{ old('batch') }}">
                                        <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-batch"></p>  
                                    </div>
                                </div>
                            {{-- </div> --}}
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Number of Candidates <span class="text-danger">*</span></label>
                                        
                                        <input type="text" name ="no_of_candidates" id="no_of_candidates" class="no_of_candidates form-control" placeholder="Enter No. of candidates" style='display:block' value="{{ old('no_of_candidates') }}">
                                        <small class="text-muted"></small>
                                        <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-no_of_candidates"></p>
                                    </div>
                                 </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="tat">TAT</label>
                                        <input type="text" name="tat" class="form-control tat" id="tat" placeholder="Enter tat" value="{{ old('tat') }}">
                                        <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-tat"></p>  
                                    </div>
                                </div>
                            
                            <div class="col-md-12">
                          
                                <div class="form-group">
                                    <div class="file">
    
                                        <label for="input-file-now">Upload file   <i class="fa fa-info-circle" data-toggle="tooltip" data-original-title="Only .zip accepted "></i> <span class="text-danger">*</span></label>
                                        {{-- <input type="hidden" name="old_file" value="{{$form->association_logo}}">  data-default-file="{{asset('images/assoc_logo'.'/'.$form->association_logo)}}"--}}
                                        <input type="file" accept=".zip" id="input-file-now"  name="file" class="dropify error-control" />
                                        <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-file"></p> 
                                    </div>
    
                                </div>
                                {{-- <input type="file" name="file" class="form-control">
                                <br>
                                <button class="btn btn-success">Import User Data</button> --}}
                             </div>
                            
                          </div>
                           
                            
                        
                        <div class="form-group mt-2">            
                            <button type="submit" class="btn btn-info submit">Submit</button>
				        </div>	
                    </div>
                <!--  -->
                </form>
               </div>
            </div>
				
            </div><!-- Footer Start -->
            <div class="flex-grow-1"></div>
        </div>
        <script src="{{asset('js/dropify.js') }}"></script>
        <script src="{{asset('js/dropify.min.js') }}"></script>

        <script type="text/javascript">
    
            $(document).ready(function () {
           
            $('.dropify').dropify();
            
            });
    
        </script>

<script>
    
    $(function(){

       
        $('.switch').on('change.bootstrapSwitch', function(e) {
        console.log(e.target.checked);
    });

    $('.customer').prop('selectedIndex',0);

    $(document).on('change','.customer',function(e) {
        e.preventDefault();
        $('.slaList').empty();
        $('.slaList').append("<option value=''>-Select-</option>");
        $(".SLAResult").html("");

        var customer = $('.customer option:selected').val();
        $.ajax({
        type:"POST",
        url: "{{ url('/customers/sla/getlist') }}",
        data: {"_token": "{{ csrf_token() }}",'customer_id':customer},      
        success: function (response) {
            console.log(response);
            if(response.success==true  ) {   
                $.each(response.data, function (i, item) {
                    $(".slaList").append("<option value='"+item.id+"'>" + item.title + "</option>");
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

    //on select sla item
    $(document).on('change','.slaList',function(e) {
        e.preventDefault();
        $(".SLAResult").html("");
        var sla_id = $('.slaList option:selected').val();
        $.ajax({ 
        type:"POST",
        url: "{{ url('/customer/mixSla/serviceItems') }}",
        data: {"_token": "{{ csrf_token() }}",'sla_id':sla_id},      
        success: function (response) {
            console.log(response);
            if(response.success==true  ) {   
                $.each(response.data, function (i, item) {
                    
                  if(item.checked_atatus){
                      $(".SLAResult").append("<div class='form-check form-check-inline disabled-link'><input class='form-check-input services_list' type='checkbox' checked name='services[]' value='"+item.service_id+"' id='"+item.service_id+"' data-type='' readonly><label class='form-check-label' for='"+item.service_id+"'>"+item.service_name+"</label></div>");
                  }else{
                    $(".SLAResult").append("<div class='form-check form-check-inline disabled-link'><input class='form-check-input services_list' type='checkbox' name='services[]' value='"+item.service_id+"' id='"+item.service_id+"' data-type='' readonly><label class='form-check-label' for='"+item.service_id+"'>"+item.service_name+"</label></div>");
                  }

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

});

$(document).on('submit', 'form#createBatchfrm', function (event) {
      event.preventDefault();
      //clearing the error msg
      $('p.error_container').html("");
      $('.form-control').removeClass('border-danger');
      var form = $(this);
      var data = new FormData($(this)[0]);
      var url = form.attr("action");
      var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
        $('.submit').attr('disabled',true);
        $('.form-control').attr('readonly',true);
        $('.form-control').addClass('disabled-link');
        $('.error-control').addClass('disabled-link');
        if ($('.submit').html() !== loadingText) {
              $('.submit').html(loadingText);
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
                $('.submit').attr('disabled',false);
                $('.form-control').attr('readonly',false);
                $('.form-control').removeClass('disabled-link');
                $('.error-control').removeClass('disabled-link');
                $('.submit').html('Submit');
            },2000);

               console.log(response);
               if(response.success==true) {          
                  // window.location = "{{ url('/')}}"+"/sla/?created=true";
                  toastr.success('Batch has been submitted successfully.');
                  window.setTimeout(function(){
                     window.location = "{{ url('/')}}"+"/batches/";
                  },2000);
               }
               //show the form validates error
               if(response.success==false ) {                              
                  for (control in response.errors) {  
                     $('.'+control).addClass('border-danger'); 
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
</script>
@endsection