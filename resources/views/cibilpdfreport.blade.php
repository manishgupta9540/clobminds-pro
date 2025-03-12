<!DOCTYPE html>
<html>
  <head>
    <title>Cibil Verification  </title>
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
            <img style='height:45px; object-fit:contain; width:150px;' src="{{asset('admin/images/logo.jpg')}}" alt=''>
        </td>
        <td colspan="" width="70%" style="text-align:right;">
          {{-- <img style='height:30px; object-fit:contain; width:150px;' src="{{url('/').'/admin/images/Nasscom.png'}}" alt=''>
          <img style='height:45px; object-fit:contain; width:150px;' src="{{url('/').'/admin/images/NSR_logo.png'}}" alt=''>
          <img style='height:45px; object-fit:contain; width:150px;' src="{{url('/').'/admin/images/NSDL_logo.png'}}" alt=''> --}}
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
            <td class='aadhar_validity' style="padding:7px; border:1px solid #666; "><strong> ALIDV9417H </strong></td> 
        </tr>
        <tr> 
            <td style="padding:7px; border:1px solid #666;">Result</td> 
            <td class='aadhar_validity' style="padding:7px; border:1px solid #666; ">
                Cibil Verification Completed <br>
            </td> 
        </tr>
      <!--  -->
    </table>


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
                          <p style='text-align:left; color:#a30a36' class='h4'> <strong>Consumer Name: Nitesh Rajendra Vaity </strong></p>
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
                                            <span style="text-align:left;"> PAN: ALIDV9417H
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
                                            <span style="text-align:left;"> Mobile - 1: 7972833611</span>
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
  
    </div>
    {{-- <footer>
    <p style="font-size:13px;">
          <b>Confidential</b>
          <br><b>Premier Consultancy & Investigation Private Limited</b><br>
          W-77, Lane No. 10, Mandir Marg, Anupam Garden, Near Sainik Farms, New Delhi - 110068 | INDIA</p>
    </footer> --}}
  </body>
  
</html>

