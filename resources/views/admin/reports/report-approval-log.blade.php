@if(count($report_approval_logs)>0)
    @foreach ($report_approval_logs as $item)
        @php
            $user = Helper::user_details($item->created_by);
        @endphp
        @if($item->status=='1')
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label>Action Type: </label>
                        <span>Send</span>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Send By: </label>
                        <span>
                            @if(stripos($user->user_type,'user')!==false)
                                {{Helper::user_name($user->id)}} ({{Helper::company_name($user->business_id)}})
                            @else
                                {{Helper::user_name($user->id)}} ({{Helper::company_name($user->id)}})
                            @endif
                        </span>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Date & Time: </label>
                        <span class="text-justify">{{$item->created_at!=NULL ? date('d-M-y h:i A',strtotime($item->created_at)) : '--'}}</span>
                    </div>
                </div>
            </div>
            <p class="pb-border"></p>
        @elseif($item->status=='2')
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label>Action Type: </label>
                        <span>Reject</span>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Reject By: </label>
                        <span>
                            @if(stripos($user->user_type,'user')!==false)
                                {{Helper::user_name($user->id)}} ({{Helper::company_name($user->business_id)}})
                            @else
                                {{Helper::user_name($user->id)}} ({{Helper::company_name($user->id)}})
                            @endif
                        </span>
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
                        <span class="text-justify">{{$item->created_at!=NULL ? date('d-M-y h:i A',strtotime($item->created_at)) : '--'}}</span>
                    </div>
                </div>
            </div>
            <p class="pb-border"></p>
        @elseif($item->status=='3')
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label>Action Type: </label>
                        <span>Approve</span>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Approve By: </label>
                        <span>
                            @if(stripos($user->user_type,'user')!==false)
                                {{Helper::user_name($user->id)}} ({{Helper::company_name($user->business_id)}})
                            @else
                                {{Helper::user_name($user->id)}} ({{Helper::company_name($user->id)}})
                            @endif
                        </span>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Date & Time: </label>
                        <span class="text-justify">{{$item->created_at!=NULL ? date('d-M-y h:i A',strtotime($item->created_at)) : '--'}}</span>
                    </div>
                </div>
            </div>
            <p class="pb-border"></p>
        @endif
    @endforeach
@endif