
   @if($report_rework_logs!=null)
   <div class="row">
               @foreach($report_rework_logs as $report_log)
                  <div class="col-6">
                  
                     <div class="form-group">
                        <label for="label_name"> Candidate Name: </label>
                        <span class="first_name">{{$report_log->name}}</span>
                     </div>
                  </div>
                  <div class="col-6">
                     <div class="form-group">
                        <label for="label_name"> Reference Id: </label>
                        <span class="last_name">{{$report_log->display_id}}</span>
                     </div>
                  </div>
                  <div class="col-6">
                     <div class="form-group">
                        <label for="label_name">Comment: </label>
                        <span class="phone">{{$report_log->comment}}</span>
                     </div>
                  </div>
                  <div class="col-6">
                     <div class="form-group">
                        <label for="label_name">Requested By: </label>
                        <span class="phone">{!!Helper::user_name($report_log->created_by)!!} </span>
                     </div>
                  </div>
                  <div class="col-6">
                     <div class="form-group">
                        @php 
                           $create_date = $report_log->created_at!=NULL?date("d-m-Y H:i:s",strtotime($report_log->created_at)):NULL;
                           
                        @endphp
                        <label for="label_name"> Date & Time : </label>
                        <span class="email">{{$create_date}}</span>
                     </div>
                  </div>
                  <div class="col-6">
                     <div class="form-group">
                     </div>
                  </div>
                  <div class="col-6">
                  @php $data_file_name= Helper::get_file_name($report_log->id); @endphp
                     
                  @if(count($data_file_name)>0)
                     <div class="form-group">
                        
                        @foreach($data_file_name as $dfn)
                        @php  $extArray = explode('.', $dfn->attachment_img); 
                        $ext = end($extArray);  @endphp
                        @if(stripos($ext,'pdf')!==false)
                        
                        
                           <div class="image-area" style="width:110px;">
                                    <a href="{{url('/').'/uploads/send-rework/'.$dfn->attachment_img }}" download>
                                    <img src="{{url('/').'/admin/images/icon_pdf.png' }}" alt="Preview" title="{{$dfn->attachment_img}}">
                                        <p style="font-size:15px;"><i class="fas fa-file-download" ><small>Download</small></i></p>
                                    </a>
                                </div>

                        @else
                        <img src="{{asset('/uploads/send-rework/' . $dfn->attachment_img) }}" alt="Preview" title="{{$dfn->attachment_img">
                        @endif
                        @endforeach
                      
                     </div>
                     @endif
                  </div>
                  <div class="col-md-12">
                    <p class="pb-border"></p>
                  </div>
               @endforeach
            </div>
   @endif


   