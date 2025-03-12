<!DOCTYPE html>
<html>
<head>
<style>
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 900px;
  margin: 0px auto;
}

td, th {
  border: 1px solid #aaaaaa;
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
<table>
    <tr style="background-color: #00a2ff;"><td style="text-align: center; font-weight: 700; color: #fff;">EPFO Records</td></tr>
</table>
<table >
@if($master_data->uan_details!=NULL)
  <tr style="width: 100%;">
    <th style="width: 10%;"><b>UAN No.</b></th>
    <th style="width: 7%;"><b>NAME</b></th>
    <th style="width: 20%;"><b>MEMBER ID</b></th>
    <th style="width: 10%;"><b>FATHER NAME</b></th>
    <th style="width: 30%;"><b>ESTABLISHMENT NAME</b></th>
    <th style="width: 8%;"><b>DATE OF JOIN</b></th>
    <th style="width: 8%;"><b> DATE OF EXIT</b></th>
  </tr>
  @php
    $employ_arr = [];
    $uan_arr = [];
    $uan_arr = json_decode($master_data->uan_details,true);
    $employ_arr = $uan_arr['employment_history']!=null && count($uan_arr['employment_history'])>0 ? $uan_arr['employment_history'] : [];
  @endphp
  @if(count($employ_arr) > 0)
    @foreach ($employ_arr as $key => $value)
        <tr>
            <td>{{$master_data->uan_number}}</td>
            <td>{{$value['name']}}</td>
            <td>{{$value['member_id']}}</td>
            <td>{{$value['guardian_name']}}</td>
            <td>{{$value['establishment_name']}}</td>
            <td>{{$value['date_of_joining']}}</td>
            <td>NA</td>
        </tr>
    @endforeach
  @endif
@endif
  {{-- <tr>
    <td>100099242913</td>
    <td>Ananta Kumar Patra</td>
    <td>PYBOM00189350000040502</td>
    <td>0 AtulChandra     Patra
        </td>
    <td>CGI INFORMATION SYSTEMS
        AND MANAGEMANT C PVT
        LTD
        </td>
    <td>27-05-
        2019
        </td>
        <td>NA</td>
  </tr>

  <tr>
    <td>100099242913</td>
    <td>Ananta Kumar Patra</td>
    <td>MHBAN00458700000032873</td>
    <td>0 AtulChandra     Patra
        </td>
    <td>DELOITTE TOUCHE
        TOHMATSU INDIA LLP</td>
    <td>01-08-
        2016
        </td>
        <td>23-05-
            2019
            </td>
  </tr>

  <tr>
    <td>100099242913</td>
    <td>Ananta Kumar Patra</td>
    <td>PYBOM00113940000256269</td>
    <td>0 AtulChandra     Patra
        </td>
    <td>WIPRO LIMITED -
        TECHNOLOGIES GROUP</td>
    <td>20-06-
        2019</td>
        <td>NA</td>
  </tr>

  <tr>
    <td>100099242913</td>
    <td>Ananta Kumar Patra</td>
    <td>WBPRB00535100000011887</td>
    <td>0 AtulChandra     Patra
        </td>
    <td>ITC INFOTECH INDIA LIMITED</td>
    <td>29-01-
        2015
        </td>
        <td>29-07-
            2016
            </td>
  </tr>
  <tr>
    <td>100099242913</td>
    <td>Ananta Kumar Patra</td>
    <td>MRNOI00487130000000959</td>
    <td>0 AtulChandra     Patra
        </td>
    <td>CHETU INDIA PVT-LTD.</td>
    <td>09-01-
        2012</td>
        <td>20-01-
            2015
            </td>
  </tr> --}}

</table>
<table style="border: none; ;">
    <tr style="width: 100%; border: none; text-align: right">
        <td style="border: none; text-align: right"><a href="https://unifiedportal-emp.epfindia.gov.in/epfo/" style="text-decoration: none; color: #00a2ff; font-size: 14px;">EPFO Login URL:https://unifiedportal-emp.epfindia.gov.in/epfo/</a></td>
      </tr> 
</table>

</body>
</html>

