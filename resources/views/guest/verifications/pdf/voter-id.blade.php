<!DOCTYPE html>
<html>
  <head>
    <title>ID Verification  </title>
  <style>
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
                bottom: -10px; 
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
  <div class="cover" >

  <table cellpadding="0" cellspacing="1" class="" >
     <tr>
        <td colspan="" width="30%" style="text-align:left">
          <img style='height:45px; object-fit:contain; width:150px;' src="{{url('/').'/admin/images/logo.png'}}" alt='Clobminds'>
        </td>
        <td colspan="" width="30%" style="text-align:right;">
          
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
        <td colspan="4" style="padding: 5px; text-align:center ">
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

    <table cellpadding="0" cellspacing="0" class="main" >
    <!--  -->
      <tr>
        <td colspan="2" style="padding: 5px; text-align:center ">
          <h3 style="text-align: center; font-weight:400;  padding: 5px; font-size:18px; color:#333; "><b>Voter ID Number Verification </b></h3>
        </td>
      </tr>
            
        <tr> 
        <td width="50%" style="padding:7px; border:1px solid #666; ">Voter ID Validity</td> 
         <td width="50%" class='aadhar_validity' style="padding:7px; border:1px solid #666; ">Valid <img style='width:30px; margin-top:3px;' src="{{ asset('admin/images/check-circle.png') }}" alt=""></td> 
        </tr>
        <tr> <td style="padding:7px; border:1px solid #666; ">Voter ID Number</td> 
         <td class='aadhar_validity' style="padding:7px; border:1px solid #666; "><strong> {{ $master_data->voter_id_number }} </strong></td> 
        </tr>
        <tr> <td style="padding:7px; border:1px solid #666; ">Name</td> 
         <td class='aadhar_validity' style="padding:7px; border:1px solid #666; "><strong> {{ $master_data->full_name }} </strong></td> 
        </tr>
        <tr> <td style="padding:7px; border:1px solid #666; ">Age</td> 
         <td class='aadhar_validity' style="padding:7px; border:1px solid #666; "><strong> {{ $master_data->age }} </strong></td> 
        </tr>
        <tr> <td style="padding:7px; border:1px solid #666; ">DOB</td> 
         <td class='aadhar_validity' style="padding:7px; border:1px solid #666; "><strong> {{ $master_data->dob!=NULL?date('d-m-Y',strtotime($master_data->dob)):'N/A' }} </strong></td> 
        </tr>
        <tr> <td style="padding:7px; border:1px solid #666; ">Gender</td> 
         <td class='aadhar_validity' style="padding:7px; border:1px solid #666; "><strong> {{ $master_data->gender }} </strong></td> 
        </tr>
        <tr> <td style="padding:7px; border:1px solid #666; ">Relation Type</td> 
              <td class='aadhar_validity' style="padding:7px; border:1px solid #666; "><strong> {{ $master_data->relation_type }} </strong></td> 
        </tr>
        <tr> <td style="padding:7px; border:1px solid #666; ">Relation Name</td> 
              <td class='aadhar_validity' style="padding:7px; border:1px solid #666; "><strong> {{ $master_data->relation_name }} </strong></td> 
        </tr>
        <tr> <td style="padding:7px; border:1px solid #666; ">House No.</td> 
              <td class='aadhar_validity' style="padding:7px; border:1px solid #666; "><strong> {{ $master_data->house_no }} </strong></td> 
        </tr>
        <tr> <td style="padding:7px; border:1px solid #666; ">Area</td> 
              <td class='aadhar_validity' style="padding:7px; border:1px solid #666; "><strong> {{ $master_data->area }} </strong></td> 
        </tr>
        <tr> <td style="padding:7px; border:1px solid #666; ">State</td> 
              <td class='aadhar_validity' style="padding:7px; border:1px solid #666; "><strong> {{ $master_data->state }} </strong></td> 
        </tr>

      <!--  -->
    </table>

    </div>
    <footer>
    <p style="font-size:13px;">
          <b>Confidential</b>
          <br><b>Clobminds</b><br>
    </p>
    </footer>
  </body>
  
</html>