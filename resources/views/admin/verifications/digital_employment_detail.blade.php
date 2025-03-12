
<!-- id detail -->
@if($master_data->uan_details!=NULL)
    @php
        $employ_arr = [];
        $uan_arr = [];
        $uan_arr = json_decode($master_data->uan_details,true);
        $employ_arr = $uan_arr['employment_history']!=null && count($uan_arr['employment_history'])>0 ? $uan_arr['employment_history'] : [];
    @endphp
    @if(count($employ_arr)>0)
        <h3 class="text-center"><b>UAN Details </b></h3>
        <table class="table table-bordered">
            <tbody>
                <tr> <td>UAN Validity</td> <td class='phone_validity'>Valid <img style='width:30px; margin-top:3px;' src="{{ asset('admin/images/check-circle.png') }}" alt=""></td> </tr>
                <tr> <td>Verification Check</td> <td class='phone_check'>Completed</td> </tr>
                <tr> 
                    <td>Result</td> 
                    <td class="uan_result">
                        <strong>Employment History</strong>
                        <div class="row">
                            @foreach ($employ_arr as $emp)
                                <div class="col-6 col-md-4 py-1">
                                    <span> Name : <strong>{{$emp['name']}}</strong></span><br>
                                    <span> Guardian Name : <strong>{{$emp['guardian_name']}}</strong></span><br>
                                    <span> Establishment Name : <strong>{{$emp['establishment_name']}}</strong></span><br>
                                    <span> Member ID : <strong>{{$emp['member_id']}}</strong></span><br>
                                    <span> Date of Joining : <strong>{{$emp['date_of_joining']}}</strong></span><br>
                                    <span> Date of Exit : <strong>{{$emp['date_of_exit']!=null ? $emp['date_of_exit'] : ''}}</strong></span>
                                </div>
                            @endforeach
                        </div>
                    </td> 
                </tr>
            </tbody>
        </table>
    @endif
@endif
<!-- id detail -->
<!-- id detail -->
@if($master_data->as26_details!=NULL)
    @php
        $as_arr = [];
        $as_arr = json_decode($master_data->as26_details,true);
    @endphp
    <h3 class="text-center"><b>26AS Details </b></h3>
    <table class="table table-bordered">
    <tbody>
        <tr> <td>AS26 Validity</td> <td class='aadhar_validity'> Valid <img style='width:30px; margin-top:3px;' src="{{ asset('admin/images/check-circle.png') }}" alt=""></td> </tr>
        <tr> <td>Verification Check</td> <td class='aadhar_check'>Completed</td> </tr>
        <tr> <td>Result</td> 
            <td>
                AS26 Details Completed <br>
                @foreach ($as_arr as $as)
                    <div class="row">
                        <div class="col-12 py-1">
                            Assessment Year : <span><strong>{{$as['assessment_year']}}</strong></span>
                            {{-- Download Link : <span><strong><a href="{{$as['download_link']}}" target="_blank"><i class="fas fa-download"></i></a></strong></span> --}}
                            @if($as['tds_data']!=null && count($as['tds_data'])>0)
                                Deductor: <span><strong>{{$as['tds_data'][0]['name_of_deductor']}}</strong></span>
                                Amount Paid: <span><strong>{{$as['tds_data'][0]['total_amount_paid']}}</strong></span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </td> 
        </tr>
    </tbody>
    </table>
@endif
<!-- id detail -->