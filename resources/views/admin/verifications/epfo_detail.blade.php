
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
@if($master_data->aadhar_details!=NULL)
    @php
        $aadhar_arr = [];
        $aadhar_arr = json_decode($master_data->aadhar_details,true);
    @endphp
    <h3 class="text-center"><b>Aadhar Details </b></h3>
    <table class="table table-bordered">
    <tbody>
        <tr> <td width="50%">Aadhar number</td> <td width="50%" class="aadhar_number">{{$aadhar_arr['aadhar_number']}}</td> </tr>
        <tr> <td>Aadhar Validity</td> <td class='aadhar_validity'> Valid <img style='width:30px; margin-top:3px;' src="{{ asset('admin/images/check-circle.png') }}" alt=""></td> </tr>
        <tr> <td>Verification Check</td> <td class='aadhar_check'>Completed</td> </tr>
        <tr> <td>Result</td> 
                <td>
                        Aadhar Verification Completed <br>
                        Aadhar number exist <span class="aadhar_number">{{$aadhar_arr['aadhar_number']}}</span> <br>
                        Age Bond: <span class="aadhar_age_bond">{{$aadhar_arr['age_range']}}</span> <br>
                        Gender: <span class="aadhar_gender">{{$aadhar_arr['gender']}}</span> <br>
                        State: <span class="aadhar_state">{{$aadhar_arr['state']}}</span><br>
                        Mobile : XXXXXXX<span class="aadhar_mobile">{{$aadhar_arr['last_digit']}}</span>
                </td> 
        </tr>
    </tbody>
    </table>
@endif
<!-- id detail -->