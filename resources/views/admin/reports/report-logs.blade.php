            
@if(count($updated_report_user)>0)
    @foreach ($updated_report_user as $item)
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <label>Updated By: </label>
                    <span> {{$item->name}} </span>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label>Email: </label>
                    <span>{{$item->email}}</span>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label>Designation: </label>
                    <span> {{$item->designation}}</span>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label>Date & Time: </label>
                    <span class="text-justify">{{date('d-M-y H:i A',strtotime($item->created_at))}}</span>
                </div>
            </div>
        </div>
    @endforeach
    <p class="pb-border"></p>
@endif