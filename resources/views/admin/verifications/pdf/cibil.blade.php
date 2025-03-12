<!DOCTYPE html>
<html>
  <head>
    <title>ID Verification  </title>
  <style>
    @page {
      /* header: page-header; */
      footer: page-footer;
    }
  body{
  font-family: Arial, Helvetica Neue, Helvetica, sans-serif; 
  color:#333;
  }
  table{width: 100%;}
  table.main{ padding: 0px; font-size: 14px; width: 100%;}
  table tr td{padding: 5px; }
  table table.appropriate-answer tr td{width:; text-align: center;}
  footer {
                position: fixed; 
                bottom: 0px; 
                left: -50px; 
                right: -50px;
                height: 80px;
                padding: 0px 30px;

                /** Extra personal styles **/
                /*background-color: #03a9f4;*/
                color: #000000;
                text-align: center;
                line-height: 20px;
            }
  </style>
  </head>
  <body>
    <htmlpagefooter name="page-footer">
        {{-- <footer>
          <p style="font-size:13px;border-top:1px solid #666;padding-top:20px;">
            <b>Confidential</b>
            <br><b>Premier Consultancy & Investigation Private Limited</b><br>
            <b>Website Link:-</b> <a href="https://www.my-bcd.com/">https://www.my-bcd.com/</a></p>
            <table cellpadding="0" cellspacing="2" width="100%" ><tr><td align="left" style=" font-size:14px;">Powered By: <img src="{{url('/').'/admin/images/pcil-logo.png'}}" width="110" style="vertical-align:bottom"> </td><td align="right">{PAGENO} of {nb}</td> </tr></table>
          </footer> --}}
        </htmlpagefooter>
  <div class="cover" >
    
    <table cellpadding="0" cellspacing="1" class="" style="border-bottom: 1px solid rgb(145, 139, 139)">
      <tr>
        <td colspan="" width="30%" style="text-align:left">
          {!! Helper::company_logo(Auth::user()->business_id) !!}
        </td>
        <td colspan="" width="70%" style="text-align:right;">
          <!-- <img style='height:30px; object-fit:contain; width:150px;' src="{{url('/').'/admin/images/Nasscom.png'}}" alt=''>
          <img style='height:45px; object-fit:contain; width:150px;' src="{{url('/').'/admin/images/NSR_logo.png'}}" alt=''>
          <img style='height:45px; object-fit:contain; width:150px;' src="{{url('/').'/admin/images/NSDL_logo.png'}}" alt=''> -->
        </td>
      </tr>
    </table>

    <table cellpadding="0" cellspacing="0" class="main" >
        <!--  -->
        <tr>
        <td colspan="4" style="padding: 0; ">
          <h3 style="text-align: center; font-weight:400;  padding: 5px; font-size:18px; color:#333; "> &nbsp; </h3>
        </td>
      </tr>
      <tr>
        <td colspan="4" style="padding: 5; text-align:center ">
          <h3 style="text-align: center; font-weight:400;  padding: 5px; font-size:18px; color:#333; "><b>ID Verification</b> </h3>
        </td>
      </tr>
      <tr>
        <td style="padding:7px; border:1px solid #666; ">Initiated Date</td>  <td style="padding:7px; border:1px solid #666; ">Completed Date </td>
        <td style="padding:7px; border:1px solid #666; ">Insufficiency Raise Date  </td><td style="padding:7px; border:1px solid #666; ">Insufficiency Cleared Date </td>
      </tr>
      <tr>
        <td style="padding:7px; border:1px solid #666; ">{{ date('d-M-Y') }}</td>
        <td style="padding:7px; border:1px solid #666; ">{{ date('d-M-Y') }}</td>
        <td style="padding:7px; border:1px solid #666; ">N/A</td>
        <td style="padding:7px; border:1px solid #666; ">N/A</td>
      </tr>
  
      <tr>
        <td colspan="4" ><br></td>
      </tr>
      <!--  -->
    </table>
    @if($master_data->is_api_verified==0)
      <table cellpadding="0" cellspacing="0" class="main" style="margin-bottom: 2%">
          <!--  -->
        <tr>
          <td colspan="2" style="padding: 5; text-align:center ">
            <h3 style="text-align: center; font-weight:400;  padding: 5px; font-size:18px; color:#333; "><b>CIBIL Verification </b></h3>
          </td>
        </tr>
        
          <tr> <td style="padding:7px; border:1px solid #666; width:30%">CIBIL Validity</td> 
          <td class='aadhar_validity' style="padding:7px; border:1px solid #666; ">Invalid <img style='width:30px; margin-bottom:-7px;' src="{{ asset('admin/images/check-cancel_50x50.png') }}" alt=""></td> 
          </tr>
          <tr> <td style="padding:7px; border:1px solid #666; ">Verification Check</td> 
          <td class='aadhar_check' style="padding:7px; border:1px solid #666; ">Completed</td> 
          </tr>
          <tr> 
              <td style="padding:7px; border:1px solid #666;">PAN Number</td> 
              <td class='aadhar_validity' style="padding:7px; border:1px solid #666; "><strong> {{$master_data->pan_number}} </strong></td> 
          </tr>
          <tr> 
              <td style="padding:7px; border:1px solid #666;">Result</td> 
              <td class='aadhar_validity' style="padding:7px; border:1px solid #666; ">
                  Cibil Verification Completed <br>
                  {{-- Phone number exist <span class="t_phone_number"></span> <br> --}}
                  {{-- CONSUMER CREDIT REPORT : <a href="{{$master_data->credit_report_link}}" target="_blank">Click here to report preview</a> <br> --}}
              </td> 
          </tr>
        <!--  -->
      </table>
    @else
      <table cellpadding="0" cellspacing="0" class="main" style="margin-bottom: 2%">
          <!--  -->
          <tr>
            <td colspan="2" style="padding: 5; text-align:center ">
              <h3 style="text-align: center; font-weight:400;  padding: 5px; font-size:18px; color:#333; "><b>CIBIL Verification </b></h3>
            </td>
          </tr>
        
          <tr> <td style="padding:7px; border:1px solid #666; width:30%">CIBIL Validity</td> 
          <td class='aadhar_validity' style="padding:7px; border:1px solid #666; ">Valid <img style='width:30px; margin-bottom:-7px;' src="{{ asset('admin/images/check-circle.png') }}" alt=""></td> 
          </tr>
          <tr> <td style="padding:7px; border:1px solid #666; ">Verification Check</td> 
          <td class='aadhar_check' style="padding:7px; border:1px solid #666; ">Completed</td> 
          </tr>
          <tr> 
              <td style="padding:7px; border:1px solid #666;">PAN Number</td> 
              <td class='aadhar_validity' style="padding:7px; border:1px solid #666; "><strong> {{$master_data->pan_number}} </strong></td> 
          </tr>
          <tr> 
              <td style="padding:7px; border:1px solid #666;">Result</td> 
              <td class='aadhar_validity' style="padding:7px; border:1px solid #666; ">
                  Cibil Verification Completed <br>
                  {{-- Phone number exist <span class="t_phone_number"></span> <br> --}}
                  {{-- CONSUMER CREDIT REPORT : <a href="{{$master_data->credit_report_link}}" target="_blank">Click here to report preview</a> <br> --}}
              </td> 
          </tr>
          <!--  -->
      </table>
    @endif
    {{-- {{$master_data->credit_report_link}} --}}
    @php
      $emp_arr = [];
      if($master_data->credit_report_link!=NULL)
      {
        $emp_arr = json_decode($master_data->credit_report_link,true);
        $addressInfo = $emp_arr['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['IDAndContactInfo']['AddressInfo'];
        $scriongInfo = $emp_arr['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['ScoreDetails'][0]['ScoringElements'];
        $retailAccount = array_key_exists('RetailAccountsSummary',$emp_arr['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']) ? $emp_arr['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['RetailAccountsSummary'] : [];
        $retailAccountdetails = array_key_exists('RetailAccountDetails',$emp_arr['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']) ? $emp_arr['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['RetailAccountDetails'] : [];
       //dd($retailAccountdetails);
      }
    @endphp

@if(count($emp_arr) > 0)
    <div class="content-wrapper">
      <div id='printdivcontent$i1'>
          <div class='row'>
              <style>
                  .reporttable {
                      font-family: Calibri;
                      border-collapse: collapse;
                      font-weight: 400;
                      font-size: 14px;
                      line-height: 1.42857143;
                      color: #333;
                      width: 100%;
                  }

                  .reporttable td,
                  .reporttable th {
                      border: 1px solid #ddd;
                      padding: 8px;
                  }

                  .reporttable th {
                      padding-top: 12px;
                      padding-bottom: 12px;
                      text-align: left;
                      background-color: #a30a36;
                  }
              </style>
              <div class='user-form-2'>
                  <div class='col-xs-12 col-md-12 col-sm-12'>
                      <div class='col-xs-12 col-md-12 col-sm-12 user-indi-form'>
                          <table style='width:100%;border-bottom: 1px solid;font-family: Calibri;font-size:18px;font-weight:400;'>
                              <tbody>
                                  <tr class='logo-space'>
                                      <td style='text-align:center;'><span style='text-align:center;font-size:20px;font-weight:bold;'>CONSUMER CREDIT
                                              REPORT V2.0</span><br />
                                      </td>
                                  </tr>
                              </tbody>
                          </table>
                          <p style='text-align:left; color:#a30a36' class='h4'> <strong>Consumer Name: {{$master_data->name}} </strong></p>
                          <p></p>
                          {{-- @foreach ($emp_arr as $key => $value) --}}
                            <table style="border-bottom: 1px solid" class='reporttable'>
                                <tbody>
                                    <tr style="background-color: coral;">
                                        <th style="background-color: #a30a36;color:#fff">Personal Information</th>
                                        <th style="background-color: #a30a36;color:#fff">Ientification</th>
                                        <th style="background-color: #a30a36;color:#fff">Contact Details</th>
                                    </tr>
                                    <tr>
                                        <td style="text-align:left;"><span style="text-align:left;"> Previous
                                                Name:</span></br>
                                            <span style="text-align:left;"> Alias
                                                Name: </span></br>
                                            <span style="text-align:left;"> DOB: 
                                            {{array_key_exists('DateOfBirth',$emp_arr['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['IDAndContactInfo']['PersonalInfo']) ? $emp_arr['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['IDAndContactInfo']['PersonalInfo']['DateOfBirth'] : ''}}
                                            </span></br>
                                            <span style="text-align:left;"> Age: 
                                              {{array_key_exists('Age',$emp_arr['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['IDAndContactInfo']['PersonalInfo']['Age']) ? $emp_arr['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['IDAndContactInfo']['PersonalInfo']['Age']['Age'] : ''}}
                                            </span></br>
                                            <span style="text-align:left;">
                                                Gender: 
                                                {{array_key_exists('Gender',$emp_arr['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['IDAndContactInfo']['PersonalInfo']) ? $emp_arr['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['IDAndContactInfo']['PersonalInfo']['Gender'] : ''}}
                                              </span></br>
                                            <span style="text-align:left;"> Total
                                                Income: </span></br>
                                            <span style="text-align:left;">
                                                Occupaton: </span></br>
                                            <span style="text-align:left;">
                                                Marital Status: </span>
                                        </td>
                                        <td style="text-align:left;">
                                            <span style="text-align:left;"> PAN: 
                                              {{array_key_exists('IdentityInfo',$emp_arr['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['IDAndContactInfo']) ? (array_key_exists('PANId',$emp_arr['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['IDAndContactInfo']['IdentityInfo']) ? $emp_arr['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['IDAndContactInfo']['IdentityInfo']['PANId'][0]['IdNumber'] : '') : ''}}
                                            </span></br>
                                            <span style="text-align:left;"> Voter ID:                                             </span></br>
                                            <span style="text-align:left;"> Passport ID:                                             </span></br>
                                            <span style="text-align:left;"> UID: </span><br>
                                            <span style="text-align:left;"> Driver's
                                                License: </span></br>
                                            <span style="text-align:left;"> Ration Card:                                             </span></br>
                                            <span style="text-align:left;"> Other:                                             </span></br>
                                        </td>
                                        <td style="text-align:left;">
                                            <span style="text-align:left;"> Mobile - 1:
                                            {{$emp_arr['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['IDAndContactInfo']['PhoneInfo'][0]['Number']}}
                                            </span>
                                            </br>
                                            <span style="text-align:left;">,<br> Mobile - 2
                                              :{{$emp_arr['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['IDAndContactInfo']['PhoneInfo'][0]['Number']}}
                                        </span></br> <span style="text-align:left;"> Email:
                                            </span></br>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <p style='text-align:left; color:#a30a36' class='h4'> <strong>Consumer Address: </strong></p>
                            
                            <table style="border-bottom: 1px solid" class='reporttable'>
                                <tbody>
                                    <tr style="background-color: #a30a36;">
                                        <th style="background-color: #a30a36;color:#fff">Seq</th>
                                        <th style="background-color: #a30a36;color:#fff">Address</th>
                                        <th style="background-color: #a30a36;color:#fff">State</th>
                                        <th style="background-color: #a30a36;color:#fff">Postal</th>
                                        <th style="background-color: #a30a36;color:#fff">Date Reported</th>
                                    </tr>
                                    @if(count($addressInfo) > 0)
                                      @foreach ($addressInfo as $key => $value)
                                        <tr>
                                          <td style='text-align:left;'>
                                            <span style='text-align:left;'> {{array_key_exists('Seq',$value) ? $value['Seq'] : ''}}</span>
                                          </td>
                                          <td style='text-align:left;'>
                                              <span style='text-align:left;'>{{array_key_exists('Address',$value) ? $value['Address'] : ''}}</span></br>
                                          </td>
                                          <td style='text-align:left;'>
                                              <span style='text-align:left;'> {{array_key_exists('State',$value) ? $value['State'] : ''}}</span></br>
                                          </td>
                                          <td style='text-align:left;'>
                                              <span style='text-align:left;'> {{array_key_exists('Postal',$value) ? $value['Postal'] : ''}}</span></br>
                                          </td>
                                          <td style='text-align:left;'>
                                              <span style='text-align:left;'> {{isset($value['ReportedDate']) ? $value['ReportedDate'] : ''}}</span></br>
                                          </td>
                                        </tr> 
                                      @endforeach    
                                    @endif    
                              </tbody>
                            </table>
                            
                            <pagebreak/>

                            <p style='text-align:left; color:#a30a36' class='h4'> <strong>Equifax Score(s): </strong></p>
                            <table class='reporttable'>
                                <tbody>
                                    <tr>
                                        <td style="text-align:left;">
                                            <span style="text-align:left;">Equifax Risk Score - Retail:
                                              @if(isset($master_data->credit_score)) 
                                                  {{$master_data->credit_score}}
                                              @endif
                                                </span></br>
                                        </td>
                                        <td style="text-align:left;">
                                            <span style="text-align:left;"> Equifax Risk Score - Microfinance:
                                              @if(isset($master_data->credit_score)) 
                                                  {{$master_data->credit_score}}
                                              @endif  
                                            </span></br>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <p style='text-align:left; color:#a30a36' class='h4'> <strong>Equifax Scoring Factors: </strong>
                            </p>

                            <table class='reporttable'>
                                <tbody> 
                                  @if(count($scriongInfo) > 0)
                                    @foreach ($scriongInfo as $key => $value)
                                        <tr>
                                          <td style='text-align:left;'>
                                              <span style='text-align:left;'>{{$value['Description']}}</span></br>
                                          </td>
                                        </tr>   
                                    @endforeach 
                                  @endif                             
                                  </tbody>
                            </table>

                            <p style='text-align:left; color:#a30a36' class='h4'> <strong>Account Summary: </strong></p>
                            <p style='text-align:left; color:#a30a36' class='h4'> <strong>RETAIL: </strong></p>

                            <table style="border-bottom: 1px solid" class='reporttable'>
                                <tbody>
                                      <tr>
                                        @if(isset($retailAccount['NoOfAccounts']))
                                          <td style="text-align:left;"><span style="text-align:left;"> No Of Accounts: {{$retailAccount['NoOfAccounts']}}</span><br>
                                        @endif    
                                        @if(isset($retailAccount['NoOfActiveAccounts']))
                                          <span style="text-align:left;"> No Of Active Accounts: {{$retailAccount['NoOfActiveAccounts']}}</span><br>
                                        @endif
                                        @if(isset($retailAccount['NoOfWriteOffs']))
                                          <span style="text-align:left;"> No Of Write Offs: {{$retailAccount['NoOfWriteOffs']}}</span><br>
                                        @endif
                                        @if(isset($retailAccount['TotalPastDue']))
                                          <span style="text-align:left;"> Single Highest Balance: {{$retailAccount['TotalPastDue']}}</span><br>
                                        @endif
                                        @if(isset($retailAccount['RecentAccount']))
                                          <span style="text-align:left;"> Recent Account: {{$retailAccount['RecentAccount']}}</span><br>
                                        @endif
                                        @if(isset($retailAccount['TotalCreditLimit']))
                                          <span style="text-align:left;"> Total Credit Limit: {{$retailAccount['TotalCreditLimit']}}</span><br>
                                        @endif
                                    </td>
                                    <td style="text-align:left;">
                                        @if(isset($retailAccount['TotalPastDue']))
                                          <span style="text-align:left;"> Total Past Due: {{$retailAccount['TotalPastDue']}}</span><br>
                                        @endif
                                        @if(isset($retailAccount['MostSevereStatusWithIn24Months']))
                                          <span style="text-align:left;"> Most Severe Status With In 24 Months: {{$retailAccount['MostSevereStatusWithIn24Months']}}</span><br>
                                        @endif  
                                        @if(isset($retailAccount['SingleHighestCredit']))
                                          <span style="text-align:left;"> Single Highest Credit: {{$retailAccount['SingleHighestCredit']}}</span><br>
                                        @endif
                                        @if(isset($retailAccount['NoOfPastDueAccounts']))
                                          <span style="text-align:left;"> No Of Past Due Accounts: {{$retailAccount['NoOfPastDueAccounts']}}</span><br>
                                        @endif
                                        @if(isset($retailAccount['TotalBalanceAmount']))
                                          <span style="text-align:left;"> Total Balance Amount: {{$retailAccount['TotalBalanceAmount']}}</span><br>
                                        @endif
                                        @if(isset($retailAccount['TotalMonthlyPaymentAmount']))
                                          <span style="text-align:left;"> Total Monthly Payment Amount: {{$retailAccount['TotalMonthlyPaymentAmount']}}</span><br>
                                        @endif
                                    </td>
                                    <td style="text-align:left;">
                                      @if(isset($retailAccount['SingleHighestSanctionAmount']))
                                        <span style="text-align:left;"> Single Highest Sanction Amount: {{$retailAccount['SingleHighestSanctionAmount']}}</span><br>
                                      @endif
                                      @if(isset($retailAccount['TotalHighCredit']))
                                        <span style="text-align:left;"> Total High Credit: {{$retailAccount['TotalHighCredit']}}</span><br>
                                      @endif
                                      @if(isset($retailAccount['AverageOpenBalance']))
                                        <span style="text-align:left;"> Average Open Balance: {{$retailAccount['AverageOpenBalance']}}</span><br>
                                      @endif
                                      @if(isset($retailAccount['NoOfZeroBalanceAccounts']))
                                        <span style="text-align:left;"> No Of Zero Balance Accounts: {{$retailAccount['NoOfZeroBalanceAccounts']}}</span><br>
                                      @endif
                                      @if(isset($retailAccount['TotalSanctionAmount']))
                                        <span style="text-align:left;"> Total Sanction Amount: {{$retailAccount['TotalSanctionAmount']}}</span><br>
                                      @endif
                                    </td>
                                </tr>
                                </tbody>
                            </table>

                            @if(isset($retailAccount->MICROFINANCE))
                              <p style='text-align:left; color:#a30a36' class='h4'> <strong>MICROFINANCE: </strong></p>
                              <table style="border-bottom: 1px solid" class='reporttable'>
                                    <tbody>
                                      <tr>
                                          <td style="text-align:left;"><span style="text-align:left;"> Number of Open Accounts: 1</span><br>
                                              <span style="text-align:left;"> Recent Account: Credit Card on 19-07-2017</span>
                                          </td>
                                          <td style="text-align:left;">
                                              <span style="text-align:left;"> Number of Past Due Accounts: 0</span><br>
                                              <span style="text-align:left;"> Installment Amount: 0.00</span>
                                          </td>
                                          <td style="text-align:left;">
                                              <span style="text-align:left;"> Total Outstanding Balance: 0.00</span><br>
                                              <span style="text-align:left;"> Total Past Due Amount: 0</span>
                                          </td>
                                      </tr>
                                  </tbody>
                              </table>
                            @endif

                            <p style='text-align:left; color:#a30a36' class='h4'> <strong>Account Details: </strong></p>
                            <p style='text-align:left; color:#a30a36' class='h4'> <strong>RETAIL: </strong></p>

                            <table style="border-bottom: 1px solid" class='reporttable'>
                              @if(count($retailAccountdetails) > 0)
                                @foreach ($retailAccountdetails as $key => $value)
                                    <tbody>
                                      <tr>
                                        <td style='text-align:left;'><span style='text-align:left;'> Acct #: {{array_key_exists('AccountNumber',$value) ? $value['AccountNumber'] : ''}}</span><br>
                                            <span style='text-align:left;'> Institution: {{array_key_exists('Institution',$value) ? $value['Institution'] : ''}}</span><br>
                                            <span style='text-align:left;'> Type: {{array_key_exists('AccountType',$value) ? $value['AccountType'] : ''}}</span><br>
                                            <span style='text-align:left;'> Ownership Type: {{array_key_exists('OwnershipType',$value) ? $value['OwnershipType'] : ''}}</span><br>
                                            <span style='text-align:left;'> Repayment Tenure: </span><br>
                                            <span style='text-align:left;'> Dispute Code: </span><br>
                                            <span style='text-align:left;'> Suit Filed Status: </span>
                                        </td>
                                        <td style='text-align:left;'>
                                            <span style='text-align:left;'> Balance: {{array_key_exists('Balance',$value) ? $value['Balance'] : ''}}</span><br>
                                            <span style='text-align:left;'> Past Due Amount: </span><br>
                                            <span style='text-align:left;'> Last Payment: </span><br>
                                            <span style='text-align:left;'> Term Frequency: </span><br>
                                            <span style='text-align:left;'> Monthly Payment Amount: </span><br>
                                            <span style='text-align:left;'> Writeoff Amount: </span><br>
                                            <span style='text-align:left;'> Asset Classification: </span>
                                        </td>
                                        <td style='text-align:left;'>
                                            <span style='text-align:left;'> Open: {{$value['Open']}}</span><br>
                                            <span style='text-align:left;'> Interest Rate: </span><br>
                                            <span style='text-align:left;'> Last Payment Date: </span><br>
                                            <span style='text-align:left;'> Sanction Amount: </span><br>
                                            <span style='text-align:left;'> Credit Limit: </span><br>
                                            <span style='text-align:left;'> Account Status: {{$value['AccountStatus']}}</span><br>
                                            <span style='text-align:left;'> Date Reported: {{$value['DateReported']}}</span>
                                        </td>
                                        <td style='text-align:left;'>
                                            <span style='text-align:left;'> Date Opened: {{array_key_exists('DateOpened',$value) ? $value['DateOpened'] : ''}}</span><br>
                                            <span style='text-align:left;'> Date Closed: </span><br>
                                            <span style='text-align:left;'> Reason: </span><br>
                                            <span style='text-align:left;'> Collateral Value: </span><br>
                                            <span style='text-align:left;'> Collateral Type: </span>
                                        </td>
                                      </tr>                               
                                  </tbody>
                                @endforeach
                              @endif  
                            </table>

                            
                            @if(isset($retailAccount->MICROFINANCE))
                              <p style='text-align:left; color:#a30a36' class='h4'> <strong>MICROFINANCE: </strong></p>
                              <table style="border-bottom: 1px solid" class='reporttable'>
                                  <tbody>
                                  </tbody>
                              </table>
                              <p style='text-align:left; color:#a30a36' class='h4'> <strong>Enquiries: </strong></p>
                              <table style="border-bottom: 1px solid" class='reporttable'>
                                  <tbody>
                                      <tr style="background-color: #a30a36;">
                                          <th style="background-color: #a30a36;color:#fff">Institution</th>
                                          <th style="background-color: #a30a36;color:#fff">Date</th>
                                          <th style="background-color: #a30a36;color:#fff">Purpose</th>
                                          <th style="background-color: #a30a36;color:#fff">Amount</th>
                                      </tr>
                                  </tbody>
                              </table>
                            @endif
                          {{-- @endforeach --}}
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>
@else
  <div class="content-wrapper">
    <div id='printdivcontent$i1'>
        <div class='row'>
            <style>
                .reporttable {
                    font-family: Calibri;
                    border-collapse: collapse;
                    font-weight: 400;
                    font-size: 14px;
                    line-height: 1.42857143;
                    color: #333;
                    width: 100%;
                }

                .reporttable td,
                .reporttable th {
                    border: 1px solid #ddd;
                    padding: 8px;
                }

                .reporttable th {
                    padding-top: 12px;
                    padding-bottom: 12px;
                    text-align: left;
                    background-color: #a30a36;
                }
            </style>
            <div class='user-form-2'>
                <div class='col-xs-12 col-md-12 col-sm-12'>
                    <div class='col-xs-12 col-md-12 col-sm-12 user-indi-form'>
                        <table style='width:100%;border-bottom: 1px solid;font-family: Calibri;font-size:18px;font-weight:400;'>
                            <tbody>
                                <tr class='logo-space'>
                                    <td style='text-align:center;'><span style='text-align:center;font-size:20px;font-weight:bold;'>CONSUMER CREDIT
                                            REPORT V2.0</span><br />
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <p style='text-align:left; color:#a30a36' class='h4'> <strong>Consumer Name: {{$master_data->name}} </strong></p>
                        <p></p>
                        {{-- @foreach ($emp_arr as $key => $value) --}}
                          <table style="border-bottom: 1px solid" class='reporttable'>
                              <tbody>
                                  <tr style="background-color: coral;">
                                      <th style="background-color: #a30a36;color:#fff">Personal Information</th>
                                      <th style="background-color: #a30a36;color:#fff">Ientification</th>
                                      <th style="background-color: #a30a36;color:#fff">Contact Details</th>
                                  </tr>
                                  <tr>
                                      <td style="text-align:left;"><span style="text-align:left;"> Previous
                                              Name:</span></br>
                                          <span style="text-align:left;"> Alias
                                              Name: </span></br>
                                          <span style="text-align:left;"> DOB: 
                                          </span></br>
                                          <span style="text-align:left;"> Age: 
                                          </span></br>
                                          <span style="text-align:left;">
                                              Gender: 
                                            </span></br>
                                          <span style="text-align:left;"> Total
                                              Income: </span></br>
                                          <span style="text-align:left;">
                                              Occupaton: </span></br>
                                          <span style="text-align:left;">
                                              Marital Status: </span>
                                      </td>
                                      <td style="text-align:left;">
                                          <span style="text-align:left;"> PAN: {{$master_data->pan_number}}
                                          </span></br>
                                          <span style="text-align:left;"> Voter ID:                                             </span></br>
                                          <span style="text-align:left;"> Passport ID:                                             </span></br>
                                          <span style="text-align:left;"> UID: </span><br>
                                          <span style="text-align:left;"> Driver's
                                              License: </span></br>
                                          <span style="text-align:left;"> Ration Card:                                             </span></br>
                                          <span style="text-align:left;"> Other:                                             </span></br>
                                      </td>
                                      <td style="text-align:left;">
                                          <span style="text-align:left;"> Mobile - 1: {{$master_data->mobile_number}}</span>
                                          </br>
                                          <span style="text-align:left;">,<br> Mobile - 2:</span></br> 
                                          <span style="text-align:left;"> Email:
                                          </span></br>
                                      </td>
                                  </tr>
                              </tbody>
                          </table>

                          <p style='text-align:left; color:#a30a36' class='h4'> <strong>Consumer Address: </strong></p>
                          
                          <table style="border-bottom: 1px solid" class='reporttable'>
                              <tbody>
                                  <tr style="background-color: #a30a36;">
                                      <th style="background-color: #a30a36;color:#fff">Seq</th>
                                      <th style="background-color: #a30a36;color:#fff">Address</th>
                                      <th style="background-color: #a30a36;color:#fff">State</th>
                                      <th style="background-color: #a30a36;color:#fff">Postal</th>
                                      <th style="background-color: #a30a36;color:#fff">Date Reported</th>
                                  </tr>
                                  
                                  {{-- <tr>
                                      <td style='text-align:left;'><span style='text-align:left;'> </span>
                                      </td>
                                      <td style='text-align:left;'>
                                          <span style='text-align:left;'></span></br>
                                      </td>
                                      <td style='text-align:left;'>
                                          <span style='text-align:left;'> </span></br>
                                      </td>
                                      <td style='text-align:left;'>
                                          <span style='text-align:left;'> </span></br>
                                      </td>
                                      <td style='text-align:left;'>
                                          <span style='text-align:left;'> </span></br>
                                      </td>
                                  </tr>  --}}
                                  
                            </tbody>
                          </table>
                          
                          <pagebreak/>

                          <p style='text-align:left; color:#a30a36' class='h4'> <strong>Equifax Score(s): </strong></p>
                          <table class='reporttable'>
                              <tbody>
                                  <tr>
                                      <td style="text-align:left;">
                                          <span style="text-align:left;">Equifax Risk Score - Retail: 725</span></br>
                                      </td>
                                      <td style="text-align:left;">
                                          <span style="text-align:left;"> Equifax Risk Score - Microfinance: 725</span></br>
                                      </td>
                                  </tr>
                              </tbody>
                          </table>

                          <p style='text-align:left; color:#a30a36' class='h4'> <strong>Equifax Scoring Factors: </strong>
                          </p>

                          <table class='reporttable'>
                              <tbody> 
                                      <tr>
                                          <td style='text-align:left;'>
                                              <span style='text-align:left;'></span></br>
                                          </td>
                                      </tr>                  
                                </tbody>
                          </table>

                          <p style='text-align:left; color:#a30a36' class='h4'> <strong>Account Summary: </strong></p>
                          <p style='text-align:left; color:#a30a36' class='h4'> <strong>RETAIL: </strong></p>

                          <table style="border-bottom: 1px solid" class='reporttable'>
                              <tbody>
                                    <tr>
                                        <td style="text-align:left;"><span style="text-align:left;"> No Of Accounts: </span><br>
                                          
                                      
                                        <span style="text-align:left;"> No Of Active Accounts: </span><br>
                                      
                                        <span style="text-align:left;"> No Of Write Offs: </span><br>
                                      
                                        <span style="text-align:left;"> Single Highest Balance: </span><br>
                                      
                                        <span style="text-align:left;"> Recent Account: </span><br>
                                      
                                        <span style="text-align:left;"> Total Credit Limit: </span><br>
                                      
                                  </td>
                                  <td style="text-align:left;">
                                      
                                        <span style="text-align:left;"> Total Past Due: </span><br>
                                      
                                      
                                        <span style="text-align:left;"> Most Severe Status With In 24 Months: </span><br>
                                        
                                        <span style="text-align:left;"> Single Highest Credit: </span><br>
                                      
                                      
                                        <span style="text-align:left;"> No Of Past Due Accounts: </span><br>
                                      
                                      
                                        <span style="text-align:left;"> Total Balance Amount: </span><br>
                                      
                                        <span style="text-align:left;"> Total Monthly Payment Amount: </span><br>
                                      
                                  </td>
                                  <td style="text-align:left;">
                                    
                                      <span style="text-align:left;"> Single Highest Sanction Amount: </span><br>
                                    
                                    
                                      <span style="text-align:left;"> Total High Credit: </span><br>
                                    
                                    
                                      <span style="text-align:left;"> Average Open Balance: </span><br>
                                    
                                    
                                      <span style="text-align:left;"> No Of Zero Balance Accounts: </span><br>
                                    
                                      <span style="text-align:left;"> Total Sanction Amount: </span><br>
                                    
                                  </td>
                              </tr>
                              </tbody>
                          </table>

                          
                            <p style='text-align:left; color:#a30a36' class='h4'> <strong>MICROFINANCE: </strong></p>
                            <table style="border-bottom: 1px solid" class='reporttable'>
                                  <tbody>
                                    <tr>
                                        <td style="text-align:left;"><span style="text-align:left;"> Number of Open Accounts: 1</span><br>
                                            <span style="text-align:left;"> Recent Account: Credit Card on 19-07-2017</span>
                                        </td>
                                        <td style="text-align:left;">
                                            <span style="text-align:left;"> Number of Past Due Accounts: 0</span><br>
                                            <span style="text-align:left;"> Installment Amount: 0.00</span>
                                        </td>
                                        <td style="text-align:left;">
                                            <span style="text-align:left;"> Total Outstanding Balance: 0.00</span><br>
                                            <span style="text-align:left;"> Total Past Due Amount: 0</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                          

                          <p style='text-align:left; color:#a30a36' class='h4'> <strong>Account Details: </strong></p>
                          <p style='text-align:left; color:#a30a36' class='h4'> <strong>RETAIL: </strong></p>

                          <table style="border-bottom: 1px solid" class='reporttable'>
                            
                                  <tbody>
                                    <tr>
                                      <td style='text-align:left;'><span style='text-align:left;'> Acct #: </span><br>
                                          <span style='text-align:left;'> Institution: </span><br>
                                          <span style='text-align:left;'> Type: </span><br>
                                          <span style='text-align:left;'> Ownership Type: </span><br>
                                          <span style='text-align:left;'> Repayment Tenure: </span><br>
                                          <span style='text-align:left;'> Dispute Code: </span><br>
                                          <span style='text-align:left;'> Suit Filed Status: </span>
                                      </td>
                                      <td style='text-align:left;'>
                                          <span style='text-align:left;'> Balance: </span><br>
                                          <span style='text-align:left;'> Past Due Amount: </span><br>
                                          <span style='text-align:left;'> Last Payment: </span><br>
                                          <span style='text-align:left;'> Term Frequency: </span><br>
                                          <span style='text-align:left;'> Monthly Payment Amount: </span><br>
                                          <span style='text-align:left;'> Writeoff Amount: </span><br>
                                          <span style='text-align:left;'> Asset Classification: </span>
                                      </td>
                                      <td style='text-align:left;'>
                                          <span style='text-align:left;'> Open: </span><br>
                                          <span style='text-align:left;'> Interest Rate: </span><br>
                                          <span style='text-align:left;'> Last Payment Date: </span><br>
                                          <span style='text-align:left;'> Sanction Amount: </span><br>
                                          <span style='text-align:left;'> Credit Limit: </span><br>
                                          <span style='text-align:left;'> Account Status: </span><br>
                                          <span style='text-align:left;'> Date Reported: </span>
                                      </td>
                                      <td style='text-align:left;'>
                                          <span style='text-align:left;'> Date Opened: </span><br>
                                          <span style='text-align:left;'> Date Closed: </span><br>
                                          <span style='text-align:left;'> Reason: </span><br>
                                          <span style='text-align:left;'> Collateral Value: </span><br>
                                          <span style='text-align:left;'> Collateral Type: </span>
                                      </td>
                                    </tr>                               
                                </tbody>
                          </table>

                          <p style='text-align:left; color:#a30a36' class='h4'> <strong>MICROFINANCE: </strong></p>
                          <table style="border-bottom: 1px solid" class='reporttable'>
                              <tbody>
                              </tbody>
                          </table>
                          <p style='text-align:left; color:#a30a36' class='h4'> <strong>Enquiries: </strong></p>
                          <table style="border-bottom: 1px solid" class='reporttable'>
                              <tbody>
                                  <tr style="background-color: #a30a36;">
                                      <th style="background-color: #a30a36;color:#fff">Institution</th>
                                      <th style="background-color: #a30a36;color:#fff">Date</th>
                                      <th style="background-color: #a30a36;color:#fff">Purpose</th>
                                      <th style="background-color: #a30a36;color:#fff">Amount</th>
                                  </tr>
                              </tbody>
                          </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>
@endif

     

    {{-- @php
      $emp_arr = [];
      if($master_data->employment_history!=NULL)
      {
        $emp_arr = json_decode($master_data->employment_history,true);
      }
    @endphp

    @if(count($emp_arr) > 0)
      <table cellpadding="0" cellspacing="0" class="main" style="margin-bottom: 2%">
        <!--  -->
      <tr>
        <td colspan="2" style="padding: 5; text-align:center ">
          <h3 style="text-align: center; font-weight:400;  padding: 5px; font-size:18px; color:#333; "><b>Employment History </b></h3>
        </td>
      </tr>
      @foreach ($emp_arr as $key => $value)
        <table cellpadding="0" cellspacing="0" class="main" >
            <tr>
                <td>
                    <tr> 
                        <td style="padding:7px; border:1px solid #666;width: 300px; ">Name </td>
                        <td class='aadhar_validity' style="padding:7px; border:1px solid #666; "><strong>{{$value['name']}}</strong></td>
                    </tr> 
                    <tr>
                        <td style="padding:7px; border:1px solid #666;">Guardian Name</td>
                        <td class='aadhar_validity' style="padding:7px; border:1px solid #666;"><strong>{{$value['guardian_name']}}</strong></td>
                    </tr>    
                    <tr>
                        <td style="padding:7px; border:1px solid #666;">Establishment Name</td>
                        <td class='aadhar_validity' style="padding:7px; border:1px solid #666;"><strong>{{$value['establishment_name']}}</strong></td>
                    </tr> 
                    <tr>
                        <td style="padding:7px; border:1px solid #666;">Member Id</td>
                        <td class='aadhar_validity' style="padding:7px; border:1px solid #666;"><strong>{{$value['member_id']}}</strong></td>
                    </tr>
                    <tr>
                        <td style="padding:7px; border:1px solid #666;">Date of Joining</td>
                        <td class='aadhar_validity' style="padding:7px; border:1px solid #666;"><strong>{{$value['date_of_joining']}}</strong></td>
                    </tr>  
                </td>
            </tr>
        </table>
      @endforeach
        
      <!--  -->
    </table>
  @endif --}}
    
  {{-- @if(count($director_arr) > 1)
    <pagebreak/>
  @endif --}}
    {{-- <table cellpadding="0" width="100%" cellspacing="0" class="main" style="margin-top:5%;">
      <!--  -->
      <tr>
        <td colspan="2" style="padding: 5px; border-left: 1px solid #333; border-bottom: 1px solid #333; background-color: #333;">
          <h3 style="text-align: left; font-weight:400;   font-size:16px;  color:#fff;  margin:0px;">Disclaimer</h3>
        </td>
      </tr>
      <tr>
        <td colspan="2" style="border-left: 1px solid #333;border-bottom: 1px solid #333;border-right: 1px solid #333; "> There is No record that is stored in myBcd and is purged out of system after One Time Report Generation.</td> 
      </tr>
      <tr>
        <td colspan="2" style="border-left: 1px solid #333;border-bottom: 1px solid #333;border-right: 1px solid #333; ">PCIL is NSR - National Skills Registry Empanelled.</td> 
      </tr>
      
      
      <!--  -->
    </table> --}}
    </div>
    {{-- <footer>
    <p style="font-size:13px;">
          <b>Confidential</b>
          <br><b>Premier Consultancy & Investigation Private Limited</b><br>
          W-77, Lane No. 10, Mandir Marg, Anupam Garden, Near Sainik Farms, New Delhi - 110068 | INDIA</p>
    </footer> --}}
  </body>
  
</html>

