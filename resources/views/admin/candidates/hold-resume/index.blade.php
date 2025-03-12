@if(count($candidate_hold_logs)>0)
    @foreach ($candidate_hold_logs as $item)
        @if($item->status=='hold')
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label>Action Type: </label>
                        <span>Hold</span>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Hold By: </label>
                        <span>{{Helper::user_name($item->user_id)}} ({{Helper::company_name($item->business_id)}})</span>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Comments: </label>
                        <span class="text-justify">{{$item->notes!=NULL ? $item->notes : '--'}}</span>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Date & Time: </label>
                        <span class="text-justify">{{date('d-M-y h:i A',strtotime($item->created_at))}}</span>
                    </div>
                </div>
            </div>
            <p class="pb-border"></p>
        @elseif($item->status=='removed')
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label>Action Type: </label>
                        <span>Resume</span>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Resume By: </label>
                        <span>{{Helper::user_name($item->candidate_id)}} ({{Helper::company_name($item->business_id)}})</span>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Comments: </label>
                        <span class="text-justify">{{$item->notes!=NULL ? $item->notes : '--'}}</span>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Date & Time: </label>
                        <span class="text-justify">{{date('d-M-y h:i A',strtotime($item->created_at))}}</span>
                    </div>
                </div>
            </div>
            <p class="pb-border"></p>
        @endif
    @endforeach
@endif