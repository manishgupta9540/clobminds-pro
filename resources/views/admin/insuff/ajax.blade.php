<table class="table table-bordered">
   <thead class="thead-light">
      <tr >
         <th class="text-center" scope="col" style="position:sticky; top:60px"><input type="checkbox" class="showhide" name='showhide' onchange="checkAll(this)" ></th>
         <th scope="col" style="position:sticky; top:60px">Candidate Name</th>
         <th scope="col" style="position:sticky; top:60px">Contact</th>
         <th scope="col" style="position:sticky; top:60px">Checks</th>
         {{-- <th scope="col">Raised By</th>
         <th scope="col">Raised Date</th> --}}
      </tr>
   </thead>
   <tbody>
      <?php $user_type = Auth::user()->user_type ?>
      {{-- if Login user is customer --}}
      @if ($user_type == 'customer')
         @if (count($raised_insuff)>0)
            
            @foreach ($raised_insuff as $insuff)
               <tr>
                  <td class="text-center" scope="row"><input class="checks" type="checkbox" name="checks[]" value="{{ $insuff->candidate_id }}" onchange='checkChange();'></td>
                  <td>
                     <a href="{{url('/candidates/jaf-info',['id'=>base64_encode($insuff->candidate_id)])}}">{{ucwords(strtolower(Helper::user_name($insuff->candidate_id)))}}</a> <br>
                     <small class="text-muted">Customer: <b>{{Helper::company_name($insuff->business_id)}}</b></small><br>
                     <small class="text-muted">Ref. No. <b>{{$insuff->display_id }}</b></small>
                  </td>
                  <td>
                     <small class="text-muted">Phone No: <b>{{"+".$insuff->phone_code."-".str_replace(' ','',$insuff->phone) }}</b></small><br>
                     <small class="text-muted">Email : <b>{{$insuff->email }}</b></small>
                  </td> 
                  <td>
                     {!!Helper::get_raise_service_name_slot($insuff->jaf_id,$insuff->candidate_id,$insuff->services)!!}    
                  </td>
                  {{-- <td>
                     {{Helper::user_name($insuff->created_by)}} 
                  </td>
                  <td>
                     {{ date('d-m-Y',strtotime($insuff->created_at) ) }}
                  </td> --}}
               </tr>  
            @endforeach
         @else
            <tr>
               <td scope="row" colspan="4"><h3 class="text-center">No record!</h3></td>
            </tr>
         @endif
      @else
         {{-- @if (count($kams)>0)
            @foreach ($kams as $kam) --}}
               @if (count($kam_raised_insuff)>0)
                  @foreach ($kam_raised_insuff as $insuff)
                     {{-- @if ($kam->business_id == $insuff->business_id) --}}
                        <tr>
                           <td class="text-center" scope="row"><input class="checks" type="checkbox" name="checks[]" value="{{ $insuff->candidate_id }}" onchange='checkChange();'></td>
                              <td>
                                 <a href="{{url('/candidates/jaf-info',['id'=>base64_encode($insuff->candidate_id)])}}">{{Helper::user_name($insuff->candidate_id)}}</a> <br>
                                 <small class="text-muted">Customer: <b>{{Helper::company_name($insuff->business_id)}}</b></small><br>
                                 <small class="text-muted">Ref. No. <b>{{$insuff->display_id }}</b></small>
                              </td>
                              <td>
                                 <small class="text-muted">Phone No: <b>{{$insuff->phone}}</b></small><br>
                                 <small class="text-muted">Email : <b>{{$insuff->email }}</b></small>
                              </td> 
                              <td>
                                 {{-- {!!Helper::get_service_name_slot($insuff->services)!!}  --}}
                                 {!!Helper::get_raise_service_name_slot($insuff->jaf_id,$insuff->candidate_id,$insuff->services)!!}
                              </td>
                              {{-- <td>
                              {{Helper::user_name($insuff->created_by)}} 
                              </td>
                              <td>
                                 {{ date('d-m-Y',strtotime($insuff->created_at) ) }}
                              </td> --}}
                        </tr>  
                     {{-- @endif --}}
                  @endforeach
               @else
                  <tr>
                     <td scope="row" colspan="4"><h3 class="text-center">No record!</h3></td>
                  </tr>
               @endif  
            {{-- @endforeach
         @endif --}}
      @endif   
   </tbody>
</table>
<div class="row">
    <div class="col-sm-12 col-md-5">
        <div class="dataTables_info" role="status" aria-live="polite"></div>
    </div>
    <div class="col-sm-12 col-md-7">
      <div class=" paging_simple_numbers" >
          @if($user_type=='customer')            
            {!! $raised_insuff->render() !!}
          @else
            {!! $kam_raised_insuff->render() !!}
          @endif
      </div>
    </div>
 </div>
 {{-- Insuff Raised modal --}}
   <div class="modal" id="raise_modal">
      <div class="modal-dialog">
         <div class="modal-content">
         <!-- Modal Header -->
         <div class="modal-header">
         <h4 class="modal-title" id="ser_name">Raise Insuff</h4>
         {{-- <button type="button" class="close" data-dismiss="modal">&times;</button> --}}
         </div>
         <!-- Modal body --> 
            <form method="post" action="{{url('/candidates/jaf/raiseInsuff')}}" id="raise_insuff_form" enctype="multipart/form-data">
               @csrf
               <input type="hidden" name="can_id" id="can_id">
               <input type="hidden" name="ser_id" id="ser_id">
               <input type="hidden" name="jaf_id" id="jaf_id">
               <div class="modal-body">
                  <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-all"> </p> 
                  <div class="form-group">
                     <label for="label_name"> Comments </label>
                     <textarea id="comments" name="comments" class="form-control comments" placeholder=""></textarea>
                     {{-- <input type="text" id="comments" name="comments" class="form-control comments" placeholder=""/> --}}
                     <p style="margin-bottom: 2px;" class="text-danger" id="error-comments"></p> 
                  </div>
                  <div class="form-group">
                     <label for="label_name"> Attachments:  <i class="fa fa-info-circle" data-toggle="tooltip" data-original-title="Only jpeg,png,jpg,gif,pdf are accepted "></i></label>
                     <input type="file" name="attachments[]" id="attachments" multiple class="form-control attachments">
                     <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-attachments"></p>  
                  </div>
               </div>
               <!-- Modal footer -->
               <div class="modal-footer">
               <button type="submit" class="btn btn-info insuff_submit">Submit </button>
               <button type="button" class="btn btn-danger" id="raise_insuff_back" data-dismiss="modal">Close</button>
               </div>
            </form>
         </div>
      </div>
   </div>
   {{-- End of Insuff Raised Model --}}
   <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
 {{-- <script src="{{asset('js/data-table/bootstrap-table.js')}}"></script> 
<script src="{{asset('js/data-table/tableExport.js')}}"></script>
<script src="{{asset('js/data-table/data-table-active.js')}}"></script>
<script src="{{asset('js/data-table/bootstrap-table-editable.js')}}"></script>
<script src="{{asset('js/data-table/bootstrap-editable.js')}}"></script>
<script src="{{asset('js/data-table/bootstrap-table-resizable.js')}}"></script>
<script src="{{asset('js/data-table/colResizable-1.5.source.js')}}"></script>
<script src="{{asset('js/data-table/bootstrap-table-export.js')}}"></script> --}}
 <script type="text/javascript">
     $(function () {
         $('[data-toggle="tooltip"]').tooltip();
      });

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
      // end All Check
   $(document).on('click', '.raise_insuff', function (event) {
      var can_id=$(this).attr('candidate-id');
      var ser_id=$(this).attr('service-id');
      var jaf_id=$(this).attr('jaf-id');
      // var ser_name=$(this).attr('service-name');
      $('#can_id').val(can_id);
      // $('#ser_name').text('Verfication-'+ser_name);
      $('#ser_id').val(ser_id);
      $('#jaf_id').val(jaf_id);
      $('#raise_modal').modal({
         backdrop: 'static',
         keyboard: false
      });
  
         $('.insuff_submit').on('click', function() {
            $('#raise_insuff_back').prop('disabled',true);
           
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

         $('#raiseinsuffBtn').click(function(e) {
               e.preventDefault();
               $("#raise_insuff_form").submit();
         });
       
      $(document).on('submit', 'form#raise_insuff_form', function (event) {
                    
                    $("#overlay").fadeIn(300);　
                    event.preventDefault();
                    var form = $(this);
                    var data = new FormData($(this)[0]);
                    var url = form.attr("action");
                    var $btn = $(this);
        
                    $.ajax({
                        type: form.attr('method'),
                        url: url,
                        data: data,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function (data) {
                            console.log(data);
                            $('.error-container').html('');
                            if (data.fail && data.error_type == 'validation') {
                                    //$("#overlay").fadeOut(300);
                                    for (control in data.errors) {
                                    $('textarea[comments=' + control + ']').addClass('is-invalid');
                                    $('#error-' + control).html(data.errors[control]);
                                    }
                            } 
                           //  if (data.fail && data.error == 'yes') {
                                
                           //      $('#error-all').html(data.message);
                           //  }
                            if (data.fail == false) {
                                // $('#send_otp').modal('hide');
                                // alert(data.id);
                                toastr.error("Insuff is Raised");
                                 // redirect to google after 5 seconds
                                 window.setTimeout(function() {
                                 location.reload(); 
                                 }, 2000);
                                // window.location.href='{{ Config::get('app.admin_url')}}/aadharchecks/show';
                                //  location.reload(); 
                            }
                        },
                        error: function (xhr, textStatus, errorThrown) {
                            
                            alert("Error: " + errorThrown);
        
                        }
                    });
                    return false;
        
   });
}); 
</script>