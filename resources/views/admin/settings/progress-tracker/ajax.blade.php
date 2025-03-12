<input type="hidden" name="duration_type" class="duration_type" value="{{$type}}">
<input type="hidden" name="duration_from" class="duration_from" value="{{date('d M Y',strtotime($from_date))}}">
<input type="hidden" name="duration_to" class="duration_to" value="{{date('d M Y',strtotime($to_date))}}">
<input type="hidden" name="raise_insuff" class="raise_insuff" value="{{json_encode($raise_insuff_arr)}}">
<input type="hidden" name="clear_insuff" class="clear_insuff" value="{{json_encode($clear_insuff_arr)}}">
<input type="hidden" name="jaf_assign" class="jaf_assign" value="{{json_encode($jaf_assign_arr)}}">
<input type="hidden" name="jaf_completed" class="jaf_completed" value="{{json_encode($jaf_completed_arr)}}">
<input type="hidden" name="ver_assign" class="ver_assign" value="{{json_encode($verification_assign_arr)}}">
<input type="hidden" name="ver_completed" class="ver_completed" value="{{json_encode($verification_completed_arr)}}">
<input type="hidden" name="total_hrs" class="total_hrs" value="{{json_encode($total_hrs_arr)}}">
{{-- <style>
.avg_size_chart{
  height:300px !important;
  min-height:300px !important;
  overflow-y:scroll !important;
}
</style> --}}
<div class="row pt-2">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-body">
                <div class="card-title">
                    Total Hours of an Employee Login
                </div>
                <div id="emp_login_chart" class="emp_login_chart" style="border: 1px solid #ddd; padding:5px;"></div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-6">
        <div class="card mb-4">
            <div class="card-body">
                <div class="card-title">
                    Task BGV Filling Assigned / Completed
                </div>
                <div id="jaf_task_chart" class="jaf_task_chart" style="border: 1px solid #ddd; padding:5px;"></div>
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="card mb-4">
            <div class="card-body">
                <div class="card-title">
                    Task Verification Assigned / Completed
                </div>
                <div id="verification_task_chart" class="verification_task_chart" style="border: 1px solid #ddd; padding:5px;"></div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-body">
                <div class="card-title">
                    Insufficiency
                </div>
                <div id="insuff_chart" class="insuff_chart" style="border: 1px solid #ddd; padding:5px;"></div>
            </div>
        </div>
    </div>
</div>