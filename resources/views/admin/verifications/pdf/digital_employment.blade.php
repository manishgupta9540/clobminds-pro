<!DOCTYPE html>
<html>
<head>
    <title>Digital Employment Verification  </title>
<style>
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 900px;
  margin: 0px auto;
}

td, th {
  border: 1px solid #000000;
  text-align: left;
  padding: 8px;
}
tr th{
    font-size: 15px;
    font-weight: 600;
}
tr td{
    font-size: 13px;
    line-height: 1.3;
}
</style>
</head>
<body>
      
   @php
       $profile_details = $master_data->profile_details!=null ? json_decode($master_data->profile_details,true) : [];
       $uan_details = $master_data->uan_details!=null ? json_decode($master_data->uan_details,true) : [];
       $as26_details = $master_data->as26_details!=null ? json_decode($master_data->as26_details,true) : [];
   @endphp 
    
 
<table >
  <tr style="width: 100%;">
    <th style="width: 40%; color: #666; font-weight: 500; font-size: 15px;">A-CHECK GLOBAL SOLUTIONS
        PRIVATE LIMITED</th>
    <th style="width: 60%; text-align: center; background-color: #00008a; color: #fff;">Digital Employment BGC Report</th>
  </tr>
  <tr>
    <td style="font-size: 12px; color: #666; padding: 15px 30px;">No. II/25, Dr. V.S.I. Estate, Taramani–Velachery 1,
        Chennai, Tamil Nadu 600041, India</td>
    <td></td>    
  </tr>
</table>
<table style="background-color: #a8a8a8; margin-top: 3rem;">
    <tr>
        <td style="font-size: 16px; font-weight: 600;">BACKGROUND PROFILE</td>
    </tr>
</table>
<table >
    <tr style="width: 100%;">
      <th style="width: 30%; color: #000000; font-weight: 600; font-size: 14px; background-color: #a8a8a8;">Name of the Candidate</th>
      <th style="width: 70%; font-size: 12px; color: #666; font-weight: 400;">{{count($profile_details)>0 && array_key_exists('name',$profile_details['pan']) ? $profile_details['pan']['name'] : null}}</th>

    </tr>
    <tr style="width: 100%;">
        <th style="width: 30%; color: #000000; font-weight: 600; font-size: 14px; background-color: #a8a8a8;">Date of Birth</th>
        <th style="width: 70%; font-size: 12px; color: #666; font-weight: 400;">{{count($profile_details)>0 && array_key_exists('dob',$profile_details['pan']) ? $profile_details['pan']['dob']['date'] : null}}</th>

      </tr>
      <tr style="width: 100%;">
        <th style="width: 30%; color: #000000; font-weight: 600; font-size: 14px; background-color: #a8a8a8;">Contact No</th>
        <th style="width: 70%; font-size: 12px; color: #666; font-weight: 400;">{{count($profile_details)>0 && array_key_exists('primary_mobile',$profile_details['contact']) ? $profile_details['contact']['primary_mobile'] : null}}</th>

      </tr>
      <tr style="width: 100%;">
        <th style="width: 30%; color: #000000; font-weight: 600; font-size: 14px; background-color: #a8a8a8;">Email ID</th>
        <th style="width: 70%; font-size: 12px; color: #666; font-weight: 400;">{{count($profile_details)>0 && array_key_exists('primary_email',$profile_details['contact']) ? $profile_details['contact']['primary_email'] : null}}</th>

      </tr>
      {{-- <tr style="width: 100%;">
        <th style="width: 30%; color: #000000; font-weight: 600; font-size: 14px; background-color: #a8a8a8;">Reference ID</th>
        <th style="width: 70%; font-size: 12px; color: #666; font-weight: 400;">test123</th>

      </tr>
      <tr style="width: 100%;">
        <th style="width: 30%; color: #000000; font-weight: 600; font-size: 14px; background-color: #a8a8a8;">Project/BU</th>
        <th style="width: 70%; font-size: 12px; color: #666; font-weight: 400;">A Check Global</th>

      </tr>
      <tr style="width: 100%;">
        <th style="width: 30%; color: #000000; font-weight: 600; font-size: 14px; background-color: #a8a8a8;">Fresher/ Experienced</th>
        <th style="width: 70%; font-size: 12px; color: #666; font-weight: 400;"></th>

      </tr>
      <tr style="width: 100%;">
        <th style="width: 30%; color: #000000; font-weight: 600; font-size: 14px; background-color: #a8a8a8;">Case Initiation Date & Time</th>
        <th style="width: 70%; font-size: 12px; color: #666; font-weight: 400;">10-01-2024 18:57:01</th>
      </tr>
      <tr style="width: 100%;">
        <th style="width: 30%; color: #000000; font-weight: 600; font-size: 14px; background-color: #a8a8a8;">Report Delivered Date & Time</th>
        <th style="width: 70%; font-size: 12px; color: #666; font-weight: 400;">10-01-2024 19:24:08</th>
      </tr> --}}
      <tr style="width: 100%; ">
        <th style="width: 30%; color: #000000; font-weight: 600; font-size: 14px; background-color: #a8a8a8; padding: 20px 10px;">Verification Status</th>
        <th style="width: 70%; color: green; font-weight: 400;">GREEN</th>
      </tr>
     </table>
     <table style="background-color: #a8a8a8; margin-top: 3rem; margin-bottom: 2rem;">
        <tr>
            <td style="font-size: 14px; font-weight: 600; text-align: center;">COLOR CODE</td>
        </tr>
    </table>
 
    <table >
        <tr style="width: 100%;">
          <th style="width: 33%; text-align: center;">DISCREPANCY - <span style="color: red;">RED</span></th>
          <th style="width: 33%; text-align: center;">CLEARED - <span style="color: green;">GREEN</span> </th>
          <th style="width: 33%; text-align: center;">UNABLE TO VERIFY/ DUAL -<span style="color: #ffa80e;"> AMBER</span> </th>

        </tr>
        
      </table>
      <table style="margin-top: 1rem;">
        <tr>
            <td style="border: none;">
                <p style="font-size: 12px;text-align:justify;">Disclaimer: This report only sets out information obtained from records searched by CROSSBOW. And is issued on “as is
                where is” basis. No opinion is provided in respect of the corporate entities or individuals who are the subject of the report here
                in. CROSSBOW abides by the FCPA (Foreign Corrupt Practices Act) & FCRA (Fair Credit Reporting Act) Policies. This report
                does not constitute recommendations as to what action should be taken. It is difficult to verify all aspects of the information
                obtained due to the nature of the research and the limitations of obtaining such information from public records. Thus while
                due care has been taken to ensure the accuracy of the compilation of information contained in this report, given the limitations
                of obtaining such information as noted above, no responsibility will be taken by CROSSBOW for the consequences of relying
                upon information contained in this report. All information supplied in this report is intended to be for the sole purpose of the
                client’s evaluation and is not intended for public dissemination.</p>
            </td> 
         </tr>
      </table>
      <table style="margin-top: 1rem;">
        <tr>
            <td style="border: none;">
                <p style="font-size: 16px; font-weight: 700; text-align: center; color: #575757; margin: 0px;">EXECUTIVE SUMMARY</p>
            </td> 
         </tr>
      </table>
      <table>
        <tr >
          <td style="width: 100%; background-color: #365794; font-weight: 500; color: #fff; border-bottom: none; font-size: 16px; letter-spacing: 1px;">
            ID Check - PAN Card Verification
          </td>
        </tr>
      </table>
      <table >
        <tr style="width: 100%;">
          <td style="background-color: #365794; font-weight: 500; color: #fff; border-bottom: none; font-size: 16px; letter-spacing: 1px; width: 5%;">S No
             </td>
          <td style="width: 35%; text-align: center; background-color: #365794; color: #fff;">PAN as per Input</td>
          <td style="width: 35%; text-align: center; background-color: #365794; color: #fff;">PAN as per Output</td>
          <td style="width: 10%; text-align: center; background-color: #365794; color: #fff;">Source</td>
          <td style="width: 10%; text-align: center; background-color: #365794; color: #fff;">Result</td>  
          
        </tr>
        <tr style="width: 100%;">
          <td style=" font-weight: 500; color: #000;  font-size: 16px; letter-spacing: 1px; width: 5%;">1</td>
          {{-- <td style="width: 35%; text-align: center;  color: #000;">&nbsp;</td>
          <td style="width: 35%; text-align: center;  color: #000;">&nbsp;</td> --}}
          <td style="width: 35%; text-align: center;  color: #000;">{{$master_data->username}}</td>
          <td style="width: 35%; text-align: center;  color: #000;">{{$master_data->username}}</td>
          <td style="width: 10%; text-align: center;  color: #000;">ITR</td>
          <td style="width: 10%; text-align: center;  color: #068006;">GREEN</td>  
          
        </tr>
      </table>

      <table style="margin-top: 2rem;">
        <tr>
          <td style="width: 100%; background-color: #365794; font-weight: 500; color: #fff; border-bottom: none; font-size: 16px; letter-spacing: 1px;">
            Address Verification
          </td>
        </tr>
      </table>
      <table >
        <tr style="width: 100%;">
          <td style="background-color: #365794; font-weight: 500; color: #fff; border-bottom: none; font-size: 16px; letter-spacing: 1px; width: 5%;">S No
             </td>
          <td style="width: 35%; text-align: center; background-color: #365794; color: #fff;">Address as per Input</td>
          <td style="width: 35%; text-align: center; background-color: #365794; color: #fff;">PAN Address as per Output</td>
          <td style="width: 10%; text-align: center; background-color: #365794; color: #fff;">Source</td>
          <td style="width: 10%; text-align: center; background-color: #365794; color: #fff;">Result</td>  
          
        </tr>
        <tr style="width: 100%;">
          <td style=" font-weight: 500; color: #000;  font-size: 16px; letter-spacing: 1px; width: 5%;">1</td>
          <td style="width: 35%; text-align: center;  color: #000;"><p style="font-size: 13px; text-align: center;">{{count($profile_details)>0 && array_key_exists('address',$profile_details['pan']) ? $profile_details['pan']['address'] : null}}</p></td>
          <td style="width: 35%; text-align: center;  color: #000;"><p style="font-size: 13px; text-align: center;">{{count($profile_details)>0 && array_key_exists('address',$profile_details['pan']) ? $profile_details['pan']['address'] : null}}</p></td>
          <td style="width: 10%; text-align: center;  color: #000;">ITR</td>
          @if(count($profile_details)>0)
            <td style="width: 10%; text-align: center;  color: #068006;">GREEN</td>  
          @else
            <td style="width: 10%; text-align: center;  color: #068006;">AMBER</td>  
          @endif
          
        </tr>
      </table>
      <table style="margin-top: 2rem;">
        <tr >
          <td style="width: 100%; background-color: #365794; font-weight: 500; color: #fff; border-bottom: none; font-size: 16px; letter-spacing: 1px;">
            Employment Verification
          </td>
        </tr>
      </table>
      <table>
        <tr style="width: 100%;">
          <td style="background-color: #365794; font-weight: 500; color: #fff; border-bottom: none; font-size: 16px; letter-spacing: 1px; width: 5%;">S No
             </td>
          <td style="width: 35%; text-align: center; background-color: #365794; color: #fff;">Employers as per Input</td>
          <td style="width: 35%; text-align: center; background-color: #365794; color: #fff;">Employers as per Output</td>
          <td style="width: 10%; text-align: center; background-color: #365794; color: #fff;">Source</td>
          <td style="width: 10%; text-align: center; background-color: #365794; color: #fff;">Result</td>  
          
        </tr>
        <tr style="width: 100%;">
          <td style=" font-weight: 500; color: #000;  font-size: 16px; letter-spacing: 1px; width: 5%;">1</td>
          <td style="width: 35%;   color: #000;"><p style="font-size: 13px;">{{$master_data->uan_number}}</p></td>
          <td style="width: 35%;   color: #000;">
          <p style="font-size: 13px;">
            {{$master_data->uan_number}}</p>
          </td>
          <td style="width: 10%; text-align: center;  color: #000;">EPFO</td>
          @if($master_data->uan_number!=null)
            <td style="width: 10%; text-align: center;  color: #068006;">GREEN</td>  
          @else
            <td style="width: 10%; text-align: center;  color: #ffa610;">AMBER</td>  
          @endif
          
        </tr>
        {{-- <tr style="width: 100%;">
          <td style=" font-weight: 500; color: #000;  font-size: 16px; letter-spacing: 1px; width: 5%;">2
             </td>
          <td style="width: 35%;   color: #000;"><p style="font-size: 13px;">VIBRANT SCREEN PVT. LTD</p></td>
          <td style="width: 35%;  color: #000;">
          <p style="font-size: 13px;">
            VIBRANT SCREEN PVT. LTD.</p>
          </td>
          <td style="width: 10%; text-align: center;  color: #000;">EPFO</td>
          <td style="width: 10%; text-align: center;  color: #068006;">GREEN</td>  
          
        </tr>
        <tr style="width: 100%;">
          <td style=" font-weight: 500; color: #000;  font-size: 16px; letter-spacing: 1px; width: 5%;">3
             </td>
          <td style="width: 35%;   color: #000;"><p style="font-size: 13px;">CALIBER POINT BUSINESS SOLUTIONS
            LIMITED</p></td>
          <td style="width: 35%;   color: #000;">
          <p style="font-size: 13px;">
            CALIBER POINT BUSINESS SOLUTIONS
            LIMITED</p>
          </td>
          <td style="width: 10%; text-align: center;  color: #000;">ITR</td>
          <td style="width: 10%; text-align: center;  color: #068006;">GREEN</td>  
          
        </tr>
        <tr style="width: 100%;">
          <td style=" font-weight: 500; color: #000;  font-size: 16px; letter-spacing: 1px; width: 5%;">4
             </td>
          <td style="width: 35%;   color: #000;"><p style="font-size: 13px;">HINDHUJA GLOBAL SOLUTIONS PRIVATE
            LIMITED</p></td>
          <td style="width: 35%;   color: #000;">
          <p style="font-size: 13px;">
            Data Not Found</p>
          </td>
          <td style="width: 10%; text-align: center;  color: #000;"></td>
          <td style="width: 10%; text-align: center;  color: #ffa610;">AMBER</td>  
          
        </tr> --}}
      </table>

      {{-- <table style="margin-top: 2rem;">
        <tr >
          <td style="width: 100%; background-color: #365794; font-weight: 500; color: #fff; border-bottom: none; font-size: 16px; letter-spacing: 1px;">
            Tenure Verification
          </td>
        </tr>
      </table>
      <table >
        <tr style="width: 100%;">
          <td style="background-color: #365794; font-weight: 500; color: #fff; border-bottom: none; font-size: 16px; letter-spacing: 1px; width: 5%;">S No
             </td>
          <td style="width: 35%; text-align: center; background-color: #365794; color: #fff;">Employers</td>
          <td style="width: 25%; text-align: center; background-color: #365794; color: #fff;">Tenure as per Input</td>
          <td style="width: 25%; text-align: center; background-color: #365794; color: #fff;">Tenure as per
            Output</td>
          <td style="width: 10%; text-align: center; background-color: #365794; color: #fff;">Source</td>  
          <td style="width: 10%; text-align: center; background-color: #365794; color: #fff;">Result</td>  
          
        </tr>
        <tr style="width: 100%;">
          <td style=" font-weight: 500; color: #000;  font-size: 16px; letter-spacing: 1px; width: 5%;">1
             </td>
          <td style="width: 35%;   color: #000;"><p style="font-size: 13px;">A-CHECK GLOBAL SOLUTIONS PRIVATE
            LIMITED</p></td>
          <td style="width: 25%;   color: #000;">
          <p style="font-size: 13px;">
            5 years, 2 months</p>
          </td>
          <td style="width: 25%;   color: #000;">
            <p style="font-size: 13px;">
              5 years, 2 months</p>
            </td>
          <td style="width: 10%; text-align: center;  color: #000;">EPFO</td>
          <td style="width: 10%; text-align: center;  color: #068006;">GREEN</td>  
          
        </tr>
        <tr style="width: 100%;">
          <td style=" font-weight: 500; color: #000;  font-size: 16px; letter-spacing: 1px; width: 5%;">2
             </td>
          <td style="width: 35%;   color: #000;"><p style="font-size: 13px;">VIBRANT SCREEN PVT. LTD</p></td>
          <td style="width: 25%;   color: #000;">
            <p style="font-size: 13px;">
              4 years, 1 month</p>
            </td>
            <td style="width: 25%;   color: #000;">
              <p style="font-size: 13px;">
                4 years, 1 month</p>
              </td>
          <td style="width: 10%; text-align: center;  color: #000;">EPFO</td>
          <td style="width: 10%; text-align: center;  color: #068006;">GREEN</td>  
          
        </tr>
        <tr style="width: 100%;">
          <td style=" font-weight: 500; color: #000;  font-size: 16px; letter-spacing: 1px; width: 5%;">3
             </td>
          <td style="width: 35%;   color: #000;"><p style="font-size: 13px;">CALIBER POINT BUSINESS SOLUTIONS
            LIMITED</p></td>
            <td style="width: 25%;   color: #000;">
              <p style="font-size: 13px;">
                0 year, 2 months</p>
              </td>
              <td style="width: 25%;   color: #000;">
                <p style="font-size: 13px;">
                  0 year, 2 months</p>
                </td>
          <td style="width: 10%; text-align: center;  color: #000;">ITR</td>
          <td style="width: 10%; text-align: center;  color: #068006;">GREEN</td>  
          
        </tr>
        <tr style="width: 100%;">
          <td style=" font-weight: 500; color: #000;  font-size: 16px; letter-spacing: 1px; width: 5%;">4
             </td>
          <td style="width: 35%;   color: #000;"><p style="font-size: 13px;">HINDHUJA GLOBAL SOLUTIONS PRIVATE
            LIMITED</p></td>
            <td style="width: 25%;   color: #000;">
              <p style="font-size: 13px;">
                2 years, 8 months</p>
              </td>
              <td style="width: 25%;   color: #000;">
                <p style="font-size: 13px;">
                  2 years, 8 months</p>
                </td>
          <td style="width: 10%; text-align: center;  color: #000;"></td>
          <td style="width: 10%; text-align: center;  color: #ffa610;">AMBER</td>  
          
        </tr>
      </table> --}}





      <pagebreak/>


@if(count($uan_details)>0)
      @php
          $employ_arr = $uan_arr['employment_history']!=null && count($uan_arr['employment_history'])>0 ? $uan_arr['employment_history'] : [];
      @endphp
    @if(count($employ_arr)>0)
        <h4 style="text-align: center; margin-top: 3rem; font-family: arial, sans-serif; color: #000;">ANNEXURE – A</h4>
        <table style="margin-top: 0rem;">
        <tr >
            <td style=" border: none;"> <h6 style="color: #000; font-size: 18px; margin: 0;">Input as per Uploaded Data
            </h6></td>
            </tr>
        </table>
    
        <table style="margin-top: 0rem;">
            <tr >
                <td style=" background-color: #365794; font-weight: 500; color: #fff; border-bottom: none; font-size: 16px; letter-spacing: 1px; text-align: center;">
                    Employer Name
                </td>
                <td style=" background-color: #365794; font-weight: 500; color: #fff; border-bottom: none; font-size: 16px; letter-spacing: 1px; text-align: center;">
                    Member ID
                </td>
                <td style=" background-color: #365794; font-weight: 500; color: #fff; border-bottom: none; font-size: 16px; letter-spacing: 1px; text-align: center;">
                    FATHER NAME
                </td>
                <td style=" background-color: #365794; font-weight: 500; color: #fff; border-bottom: none; font-size: 16px; letter-spacing: 1px; text-align: center;">
                    ESTABLISHMENT NAME
                </td>
                <td colspan="2" style=" background-color: #365794; font-weight: 500; color: #fff; border-bottom: none; font-size: 16px; letter-spacing: 1px; text-align: center;">
                    Tenure
                </td>
            </tr>
            <tr style="">
                
                <td style="width: 35%; text-align: center; background-color: #365794; color: #fff;">&nbsp;</td>
                <td style="width: 35%; text-align: center; background-color: #365794; color: #fff;">&nbsp;</td>
                <td style="width: 35%; text-align: center; background-color: #365794; color: #fff;">&nbsp;</td>
                <td style="width: 35%; text-align: center; background-color: #365794; color: #fff;">&nbsp;</td>
                <td style="width: 25%; text-align: center; background-color: #365794; color: #fff;">DOJ</td>
                <td style="width: 25%; text-align: center; background-color: #365794; color: #fff;">DOE</td>
                
            </tr>
            @foreach ($employ_arr as $key => $value)
                <tr style="width: 100%;">
                    
                    <td style="width: 35%;   color: #000;"><p style="font-size: 13px; text-align: center;">{{$value['name']}}</p></td>
                    <td style="width: 35%;   color: #000;"><p style="font-size: 13px; text-align: center;">{{$value['member_id']}}</p></td>
                    <td style="width: 35%;   color: #000;"><p style="font-size: 13px; text-align: center;">{{$value['guardian_name']}}</p></td>
                    <td style="width: 35%;   color: #000;"><p style="font-size: 13px; text-align: center;">{{$value['establishment_name']}}</p></td>
                    <td style="width: 25%;   color: #000;">
                        <p style="font-size: 13px;  text-align: center;">
                            {{$value['date_of_joining']}}
                        </p>
                    </td>
                    <td style="width: 25%;   color: #000;">
                    <p style="font-size: 13px;  text-align: center;">
                        NOT_AVAILABLE
                    </p>
                    </td>
                    
                </tr>
            @endforeach
            {{-- <tr style="width: 100%;">
                
                <td style="width: 35%;   color: #000; text-align: center;"><p style="font-size: 13px;">VIBRANT SCREEN PVT. LTD</p></td>
                <td style="width: 25%;   color: #000;">
                <p style="font-size: 13px; text-align: center;">
                    03-11-2014</p>
                </td>
                <td style="width: 25%;   color: #000;">
                    <p style="font-size: 13px; text-align: center;">
                    31-10-2018</p>
                    </td>
                
            </tr>
            <tr style="width: 100%;">
                
                <td style="width: 35%;   color: #000; text-align: center;"><p style="font-size: 13px;">CALIBER POINT BUSINESS SOLUTIONS
                LIMITED</p></td>
                <td style="width: 25%;   color: #000; ">
                    <p style="font-size: 13px; text-align: center;">
                    31-10-2012</p>
                    </td>
                    <td style="width: 25%;   color: #000;">
                    <p style="font-size: 13px; text-align: center;">
                        31-12-2012</p>
                    </td>
                
            </tr>
            <tr style="width: 100%;">
                
                <td style="width: 35%;   color: #000; text-align: center;"><p style="font-size: 13px;">HINDHUJA GLOBAL SOLUTIONS PRIVATE
                LIMITED</p></td>
                <td style="width: 25%;   color: #000;">
                    <p style="font-size: 13px; text-align: center;">
                    01-02-2009</p>
                    </td>
                    <td style="width: 25%;   color: #000;">
                    <p style="font-size: 13px; text-align: center;">
                        06-09-2011</p>
                    </td>
                
            </tr> --}}
        </table>

        <table style="margin-top: 3rem;">
        <tr>
            <td style="padding: 0; border: none;">
            <h4 style=" margin: 0rem; font-family: arial, sans-serif; color: #000; font-size: 18px;">Output as per EPFO Service History</h4>
            </td>
        </tr>
        <tr >
            <td style=" border: none; width: 50%; padding: 0;"> <h6 style="color: #000; font-size: 12px; margin: 0;">Candidate: {{count($profile_details)>0 && array_key_exists('name',$profile_details['pan']) ? $profile_details['pan']['name'] : null}}
            </h6></td>
            <td style=" border: none; width: 50%; text-align: right; "> <h6 style="color: #000; font-size: 12px; margin: 0; text-align: right;">UAN Number : {{$master_data->uan_number}}
            </h6></td>
            </tr>
        </table>
    
        <table style="margin-top: 0rem;">
        <tr >
            <td style=" background-color: #365794; font-weight: 500; color: #fff; border-bottom: none; font-size: 16px; letter-spacing: 1px; text-align: center;">
                Employer Name
            </td>
            <td style=" background-color: #365794; font-weight: 500; color: #fff; border-bottom: none; font-size: 16px; letter-spacing: 1px; text-align: center;">
                Member ID
            </td>
            <td style=" background-color: #365794; font-weight: 500; color: #fff; border-bottom: none; font-size: 16px; letter-spacing: 1px; text-align: center;">
                FATHER NAME
            </td>
            <td style=" background-color: #365794; font-weight: 500; color: #fff; border-bottom: none; font-size: 16px; letter-spacing: 1px; text-align: center;">
                ESTABLISHMENT NAME
            </td>
            <td colspan="2" style=" background-color: #365794; font-weight: 500; color: #fff; border-bottom: none; font-size: 16px; letter-spacing: 1px; text-align: center;">
                Tenure
            </td>
        </tr>
        <tr style="">
            
            <td style="width: 35%; text-align: center; background-color: #365794; color: #fff;">&nbsp;</td>
            <td style="width: 35%; text-align: center; background-color: #365794; color: #fff;">&nbsp;</td>
            <td style="width: 35%; text-align: center; background-color: #365794; color: #fff;">&nbsp;</td>
            <td style="width: 35%; text-align: center; background-color: #365794; color: #fff;">&nbsp;</td>
            <td style="width: 25%; text-align: center; background-color: #365794; color: #fff;">EPFO DOJ</td>
            <td style="width: 25%; text-align: center; background-color: #365794; color: #fff;">EPFO DOE</td>
            
        </tr>
        @foreach ($employ_arr as $key => $value)
            <tr style="width: 100%;">
                
                <td style="width: 35%;   color: #000;"><p style="font-size: 13px; text-align: center;">{{$value['name']}}</p></td>
                <td style="width: 35%;   color: #000;"><p style="font-size: 13px; text-align: center;">{{$value['member_id']}}</p></td>
                <td style="width: 35%;   color: #000;"><p style="font-size: 13px; text-align: center;">{{$value['guardian_name']}}</p></td>
                <td style="width: 35%;   color: #000;"><p style="font-size: 13px; text-align: center;">{{$value['establishment_name']}}</p></td>
                <td style="width: 25%;   color: #000;">
                    <p style="font-size: 13px;  text-align: center;">
                        {{$value['date_of_joining']}}
                    </p>
                </td>
                <td style="width: 25%;   color: #000;">
                <p style="font-size: 13px;  text-align: center;">
                    NOT_AVAILABLE
                </p>
                </td>
                
            </tr>
        @endforeach
        {{-- <tr style="width: 100%;">
            
            <td style="width: 35%;   color: #000; text-align: center;"><p style="font-size: 13px;">VIBRANT SCREEN PVT. LTD</p></td>
            <td style="width: 25%;   color: #000;">
            <p style="font-size: 13px; text-align: center;">
                03-11-2014</p>
            </td>
            <td style="width: 25%;   color: #000;">
                <p style="font-size: 13px; text-align: center;">
                31-10-2018</p>
                </td>
        </tr> --}}
        </table>
    @endif
    <pagebreak/>
@endif

      
@if(count($as26_details)>0)
      <h4 style=" margin: 0rem; text-align: center; font-family: arial, sans-serif; color: #000; margin-top: 3rem; font-size: 18px;">ANNEXURE – B</h4>
      <table style="">
        <tbody><tr>
          <td style="padding: 0; border: none;">
            <h4 style=" margin: 1rem 0;  font-family: arial, sans-serif; color: #000; margin-top: 3rem; font-size: 18px;">26AS Statement</h4>
          </td>
        </tr>
        <tr>
          <td style=" border: none; width: 50%; padding: 0;"> <h6 style="color: #000; font-size: 12px; margin: 0;">Candidate: {{count($profile_details)>0 && array_key_exists('name',$profile_details['pan']) ? $profile_details['pan']['name'] : null}}
          </h6></td>
          <td style=" border: none; width: 50%; text-align: right; "> <h6 style="color: #000; font-size: 12px; margin: 0; text-align: right;">UAN Number : {{$master_data->uan_number}}
          </h6></td>
          </tr>
          </tbody></table>

          <table style="margin-top: 0rem;">
            <tr >
              <td style=" background-color: #365794; font-weight: 500; color: #fff; border-bottom: none; font-size: 16px; letter-spacing: 1px; text-align: center; width: 8%;">
                Financial
                Year
              </td>
              <td  style=" background-color: #365794; font-weight: 500; color: #fff; border-bottom: none; font-size: 16px; letter-spacing: 1px; text-align: center; width: 8%;">
                TAN
              </td>
              <td  style=" background-color: #365794; font-weight: 500; color: #fff; border-bottom: none; font-size: 16px; letter-spacing: 1px; text-align: center; width: 25%;">
                Deductor
              </td>
              <td  style=" background-color: #365794; font-weight: 500; color: #fff; border-bottom: none; font-size: 16px; letter-spacing: 1px; text-align: center; width: 10%;">
                Section
              </td>
              <td  style=" background-color: #365794; font-weight: 500; color: #fff; border-bottom: none; font-size: 16px; letter-spacing: 1px; text-align: center; width: 10%;">
                Date
              </td>
              <td  style=" background-color: #365794; font-weight: 500; color: #fff; border-bottom: none; font-size: 16px; letter-spacing: 1px; text-align: center; width: 10%;">
                Amount
              </td>
            </tr>
           @foreach ($as26_details as $as)
                @php
                    $tds_data = $as['tds_data']!=null && count($as['tds_data'])>0 ? $as['tds_data'] : [];
                @endphp
                @if(count($tds_data)>0)
                  @foreach ($tds_data as $td)
                      <tr>
                          <td style="width: 8%;"><p style="font-size: 12px; color: #000;">{{$as['assessment_year']}}</p></td>
                          <td style="width: 8%;"><p style="font-size: 12px; color: #000;">{{$td['tan_of_deductor']}}</p></td>
                          <td style="width: 25%;"><p style="font-size: 12px; color: #000;">{{$td['name_of_deductor']}}</p></td>
                          <td style="width: 10%;"><p style="font-size: 12px; color: #000;">{{$td['section']}}</p></td>
                          <td style="width: 10%;"><p style="font-size: 12px; color: #000;">{{$td['date_of_booking']}}</p></td>
                          <td style="width: 10%;"><p style="font-size: 12px; color: #000;">{{$td['total_amount_paid']}}</p></td>
                      </tr>
                  @endforeach
                @endif
           @endforeach
           {{-- <tr>
            <td style="width: 8%;"><p style="font-size: 12px; color: #000;">2024</p></td>
            <td style="width: 8%;"><p style="font-size: 12px; color: #000;">CHEA19315C</p></td>
            <td style="width: 25%;"><p style="font-size: 12px; color: #000;">A-CHECK GLOBAL SOLUTIONS PRIVATE LIMITED</p></td>
            <td style="width: 10%;"><p style="font-size: 12px; color: #000;">192</p></td>
            <td style="width: 10%;"><p style="font-size: 12px; color: #000;">30-Sep-23</p></td>
            <td style="width: 10%;"><p style="font-size: 12px; color: #000;">41,462.00</p></td>
           </tr>
           <tr>
            <td style="width: 8%;"><p style="font-size: 12px; color: #000;">2024</p></td>
            <td style="width: 8%;"><p style="font-size: 12px; color: #000;">CHEA19315C</p></td>
            <td style="width: 25%;"><p style="font-size: 12px; color: #000;">A-CHECK GLOBAL SOLUTIONS PRIVATE LIMITED</p></td>
            <td style="width: 10%;"><p style="font-size: 12px; color: #000;">192</p></td>
            <td style="width: 10%;"><p style="font-size: 12px; color: #000;">31-Aug-23</p></td>
            <td style="width: 10%;"><p style="font-size: 12px; color: #000;">40,126.00</p></td>
           </tr>
           <tr>
            <td style="width: 8%;"><p style="font-size: 12px; color: #000;">2024</p></td>
            <td style="width: 8%;"><p style="font-size: 12px; color: #000;">CHEA19315C</p></td>
            <td style="width: 25%;"><p style="font-size: 12px; color: #000;">A-CHECK GLOBAL SOLUTIONS PRIVATE LIMITED</p></td>
            <td style="width: 10%;"><p style="font-size: 12px; color: #000;">192</p></td>
            <td style="width: 10%;"><p style="font-size: 12px; color: #000;">31-Jul-23</p></td>
            <td style="width: 10%;"><p style="font-size: 12px; color: #000;">41,462.00</p></td>
           </tr>
           <tr>
            <td style="width: 8%;"><p style="font-size: 12px; color: #000;">2024</p></td>
            <td style="width: 8%;"><p style="font-size: 12px; color: #000;">CHEA19315C</p></td>
            <td style="width: 25%;"><p style="font-size: 12px; color: #000;">A-CHECK GLOBAL SOLUTIONS PRIVATE LIMITED</p></td>
            <td style="width: 10%;"><p style="font-size: 12px; color: #000;">192</p></td>
            <td style="width: 10%;"><p style="font-size: 12px; color: #000;">30-Jun-23</p></td>
            <td style="width: 10%;"><p style="font-size: 12px; color: #000;">41,462.00</p></td>
           </tr>
           <tr>
            <td style="width: 8%;"><p style="font-size: 12px; color: #000;">2024</p></td>
            <td style="width: 8%;"><p style="font-size: 12px; color: #000;">CHEA19315C</p></td>
            <td style="width: 25%;"><p style="font-size: 12px; color: #000;">A-CHECK GLOBAL SOLUTIONS PRIVATE LIMITED</p></td>
            <td style="width: 10%;"><p style="font-size: 12px; color: #000;">192</p></td>
            <td style="width: 10%;"><p style="font-size: 12px; color: #000;">31-May-23</p></td>
            <td style="width: 10%;"><p style="font-size: 12px; color: #000;">36,053.00</p></td>
           </tr>
           <tr>
            <td style="width: 8%;"><p style="font-size: 12px; color: #000;">2023</p></td>
            <td style="width: 8%;"><p style="font-size: 12px; color: #000;">CHEA19315C</p></td>
            <td style="width: 25%;"><p style="font-size: 12px; color: #000;">A-CHECK GLOBAL SOLUTIONS PRIVATE LIMITED</p></td>
            <td style="width: 10%;"><p style="font-size: 12px; color: #000;">192</p></td>
            <td style="width: 10%;"><p style="font-size: 12px; color: #000;">31-Mar-23</p></td>
            <td style="width: 10%;"><p style="font-size: 12px; color: #000;">36,053.00</p></td>
           </tr>
           <tr>
            <td style="width: 8%;"><p style="font-size: 12px; color: #000;">2023</p></td>
            <td style="width: 8%;"><p style="font-size: 12px; color: #000;">CHEA19315C</p></td>
            <td style="width: 25%;"><p style="font-size: 12px; color: #000;">A-CHECK GLOBAL SOLUTIONS PRIVATE LIMITED</p></td>
            <td style="width: 10%;"><p style="font-size: 12px; color: #000;">192</p></td>
            <td style="width: 10%;"><p style="font-size: 12px; color: #000;">31-Mar-23</p></td>
            <td style="width: 10%;"><p style="font-size: 12px; color: #000;">36,053.00</p></td>
           </tr>
           <tr>
            <td style="width: 8%;"><p style="font-size: 12px; color: #000;">2023</p></td>
            <td style="width: 8%;"><p style="font-size: 12px; color: #000;">CHEA19315C</p></td>
            <td style="width: 25%;"><p style="font-size: 12px; color: #000;">A-CHECK GLOBAL SOLUTIONS PRIVATE LIMITED</p></td>
            <td style="width: 10%;"><p style="font-size: 12px; color: #000;">192</p></td>
            <td style="width: 10%;"><p style="font-size: 12px; color: #000;">31-Mar-23</p></td>
            <td style="width: 10%;"><p style="font-size: 12px; color: #000;">36,053.00</p></td>
           </tr>
           <tr>
            <td style="width: 8%;"><p style="font-size: 12px; color: #000;">2023</p></td>
            <td style="width: 8%;"><p style="font-size: 12px; color: #000;">CHEA19315C</p></td>
            <td style="width: 25%;"><p style="font-size: 12px; color: #000;">A-CHECK GLOBAL SOLUTIONS PRIVATE LIMITED</p></td>
            <td style="width: 10%;"><p style="font-size: 12px; color: #000;">192</p></td>
            <td style="width: 10%;"><p style="font-size: 12px; color: #000;">31-Dec-22</p></td>
            <td style="width: 10%;"><p style="font-size: 12px; color: #000;">35,471.00</p></td>
           </tr>
           <tr>
            <td style="width: 8%;"><p style="font-size: 12px; color: #000;">2023</p></td>
            <td style="width: 8%;"><p style="font-size: 12px; color: #000;">CHEA19315C</p></td>
            <td style="width: 25%;"><p style="font-size: 12px; color: #000;">A-CHECK GLOBAL SOLUTIONS PRIVATE LIMITED</p></td>
            <td style="width: 10%;"><p style="font-size: 12px; color: #000;">192</p></td>
            <td style="width: 10%;"><p style="font-size: 12px; color: #000;">30-Nov-22</p></td>
            <td style="width: 10%;"><p style="font-size: 12px; color: #000;">34,852.00</p></td>
           </tr>
           <tr>
            <td style="width: 8%;"><p style="font-size: 12px; color: #000;">2023</p></td>
            <td style="width: 8%;"><p style="font-size: 12px; color: #000;">CHEA19315C</p></td>
            <td style="width: 25%;"><p style="font-size: 12px; color: #000;">A-CHECK GLOBAL SOLUTIONS PRIVATE LIMITED</p></td>
            <td style="width: 10%;"><p style="font-size: 12px; color: #000;">192</p></td>
            <td style="width: 10%;"><p style="font-size: 12px; color: #000;">31-Oct-22</p></td>
            <td style="width: 10%;"><p style="font-size: 12px; color: #000;">33,146.00</p></td>
           </tr>
           <tr>
            <td style="width: 8%;"><p style="font-size: 12px; color: #000;">2023</p></td>
            <td style="width: 8%;"><p style="font-size: 12px; color: #000;">CHEA19315C</p></td>
            <td style="width: 25%;"><p style="font-size: 12px; color: #000;">A-CHECK GLOBAL SOLUTIONS PRIVATE LIMITED</p></td>
            <td style="width: 10%;"><p style="font-size: 12px; color: #000;">192</p></td>
            <td style="width: 10%;"><p style="font-size: 12px; color: #000;">30-Sep-22</p></td>
            <td style="width: 10%;"><p style="font-size: 12px; color: #000;">12,017.00</p></td>
           </tr>
           <tr>
            <td style="width: 8%;"><p style="font-size: 12px; color: #000;">2023</p></td>
            <td style="width: 8%;"><p style="font-size: 12px; color: #000;">CHEA19315C</p></td>
            <td style="width: 25%;"><p style="font-size: 12px; color: #000;">A-CHECK GLOBAL SOLUTIONS PRIVATE LIMITED</p></td>
            <td style="width: 10%;"><p style="font-size: 12px; color: #000;">192</p></td>
            <td style="width: 10%;"><p style="font-size: 12px; color: #000;">31-Aug-22</p></td>
            <td style="width: 10%;"><p style="font-size: 12px; color: #000;">1,164.00</p></td>
           </tr>
           <tr>
            <td style="width: 8%;"><p style="font-size: 12px; color: #000;">2023</p></td>
            <td style="width: 8%;"><p style="font-size: 12px; color: #000;">CHEA19315C</p></td>
            <td style="width: 25%;"><p style="font-size: 12px; color: #000;">A-CHECK GLOBAL SOLUTIONS PRIVATE LIMITED</p></td>
            <td style="width: 10%;"><p style="font-size: 12px; color: #000;">192</p></td>
            <td style="width: 10%;"><p style="font-size: 12px; color: #000;">31-Jul-22</p></td>
            <td style="width: 10%;"><p style="font-size: 12px; color: #000;">23,259.00</p></td>
           </tr>
           <tr>
            <td style="width: 8%;"><p style="font-size: 12px; color: #000;">2023</p></td>
            <td style="width: 8%;"><p style="font-size: 12px; color: #000;">CHEA19315C</p></td>
            <td style="width: 25%;"><p style="font-size: 12px; color: #000;">A-CHECK GLOBAL SOLUTIONS PRIVATE LIMITED</p></td>
            <td style="width: 10%;"><p style="font-size: 12px; color: #000;">192</p></td>
            <td style="width: 10%;"><p style="font-size: 12px; color: #000;">30-Jun-22</p></td>
            <td style="width: 10%;"><p style="font-size: 12px; color: #000;">36,053.00</p></td>
           </tr>
           <tr>
            <td style="width: 8%;"><p style="font-size: 12px; color: #000;">2023</p></td>
            <td style="width: 8%;"><p style="font-size: 12px; color: #000;">CHEA19315C</p></td>
            <td style="width: 25%;"><p style="font-size: 12px; color: #000;">A-CHECK GLOBAL SOLUTIONS PRIVATE LIMITED</p></td>
            <td style="width: 10%;"><p style="font-size: 12px; color: #000;">192</p></td>
            <td style="width: 10%;"><p style="font-size: 12px; color: #000;">31-May-22</p></td>
            <td style="width: 10%;"><p style="font-size: 12px; color: #000;">32,776.00</p></td>
           </tr>
           <tr>
            <td style="width: 8%;"><p style="font-size: 12px; color: #000;">2023</p></td>
            <td style="width: 8%;"><p style="font-size: 12px; color: #000;">CHEA19315C</p></td>
            <td style="width: 25%;"><p style="font-size: 12px; color: #000;">A-CHECK GLOBAL SOLUTIONS PRIVATE LIMITED</p></td>
            <td style="width: 10%;"><p style="font-size: 12px; color: #000;">192</p></td>
            <td style="width: 10%;"><p style="font-size: 12px; color: #000;">30-Apr-22</p></td>
            <td style="width: 10%;"><p style="font-size: 12px; color: #000;">32,776.00</p></td>
           </tr>
           <tr>
            <td style="width: 8%;"><p style="font-size: 12px; color: #000;">2023</p></td>
            <td style="width: 8%;"><p style="font-size: 12px; color: #000;">CHEA19315C</p></td>
            <td style="width: 25%;"><p style="font-size: 12px; color: #000;">A-CHECK GLOBAL SOLUTIONS PRIVATE LIMITED</p></td>
            <td style="width: 10%;"><p style="font-size: 12px; color: #000;">192</p></td>
            <td style="width: 10%;"><p style="font-size: 12px; color: #000;">31-Mar-22</p></td>
            <td style="width: 10%;"><p style="font-size: 12px; color: #000;">32,776.00</p></td>
           </tr>
           <tr>
            <td style="width: 8%;"><p style="font-size: 12px; color: #000;">2023</p></td>
            <td style="width: 8%;"><p style="font-size: 12px; color: #000;">CHEA19315C</p></td>
            <td style="width: 25%;"><p style="font-size: 12px; color: #000;">A-CHECK GLOBAL SOLUTIONS PRIVATE LIMITED</p></td>
            <td style="width: 10%;"><p style="font-size: 12px; color: #000;">192</p></td>
            <td style="width: 10%;"><p style="font-size: 12px; color: #000;">28-Feb-22</p></td>
            <td style="width: 10%;"><p style="font-size: 12px; color: #000;">32,776.00</p></td>
           </tr>
           <tr>
            <td style="width: 8%;"><p style="font-size: 12px; color: #000;">2023</p></td>
            <td style="width: 8%;"><p style="font-size: 12px; color: #000;">CHEA19315C</p></td>
            <td style="width: 25%;"><p style="font-size: 12px; color: #000;">A-CHECK GLOBAL SOLUTIONS PRIVATE LIMITED</p></td>
            <td style="width: 10%;"><p style="font-size: 12px; color: #000;">192</p></td>
            <td style="width: 10%;"><p style="font-size: 12px; color: #000;">31-Jan-22</p></td>
            <td style="width: 10%;"><p style="font-size: 12px; color: #000;">32,776.00</p></td>
           </tr>
           <tr>
            <td style="width: 8%;"><p style="font-size: 12px; color: #000;">2023</p></td>
            <td style="width: 8%;"><p style="font-size: 12px; color: #000;">CHEA19315C</p></td>
            <td style="width: 25%;"><p style="font-size: 12px; color: #000;">A-CHECK GLOBAL SOLUTIONS PRIVATE LIMITED</p></td>
            <td style="width: 10%;"><p style="font-size: 12px; color: #000;">192</p></td>
            <td style="width: 10%;"><p style="font-size: 12px; color: #000;">31-Dec-21</p></td>
            <td style="width: 10%;"><p style="font-size: 12px; color: #000;">32,776.00</p></td>
           </tr> --}}
            
            
          </table>
        
@endif
</body>
</html>

