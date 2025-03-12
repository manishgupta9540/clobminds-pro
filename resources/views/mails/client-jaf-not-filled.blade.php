<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>
<body style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; background-color: #f5f8fa; color: #74787E; height: 100%; hyphens: auto; line-height: 1.4; margin: 0; -moz-hyphens: auto; -ms-word-break: break-all; width: 100% !important; -webkit-hyphens: auto; -webkit-text-size-adjust: none; word-break: break-word;">
    <style>
        @media  only screen and (max-width: 600px) {
            .inner-body {
                width: 100% !important;
            }
            .footer {
                width: 100% !important;
            }
        }
        @media  only screen and (max-width: 500px) {
            .button {
                width: 100% !important;
            }
        }
    </style>
    <table class="wrapper" width="100%" cellpadding="0" cellspacing="0" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; background-color: #f5f8fa; margin: 0; padding: 0; width: 100%; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%;">
        <tr>
            <td align="center" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box;">
                <table class="content" width="100%" cellpadding="0" cellspacing="0" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; margin: 0; padding: 0; width: 100%; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%;">
                    <tr>
                        <td class="header" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; padding: 25px 0; text-align: center;">
                            <a href="{{Config::get('app.admin_url')}}/" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #bbbfc3; font-size: 19px; font-weight: bold; text-decoration: none; text-shadow: 0 1px 0 white;">
                                {{-- <img src="{{asset('admin/images/BCD-Logo2.png')}}"> --}}
                                {!! Helper::company_logo($sender->business_id) !!}
                            </a>
                        </td>
                    </tr>
                    <!-- Email Body -->
                    <tr>
                        <td class="body" width="100%" cellpadding="0" cellspacing="0" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; background-color: #FFFFFF; border-bottom: 1px solid #EDEFF2; border-top: 1px solid #EDEFF2; margin: 0; padding: 0; width: 100%; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%;">
                            <table class="inner-body" align="center" width="570" cellpadding="0" cellspacing="0" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; background-color: #FFFFFF; margin: 0 auto; padding: 0; width: 570px; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 570px;">
                                <!-- Body content -->
                                <tr>
                                    <td class="content-cell" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; padding: 35px;">
                                        <h1 style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #2F3133; font-size: 19px; font-weight: bold; margin-top: 0; text-align: left;">Hello {{$name}},</h1>

                                        <p style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #74787E; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left;">You have received the BGV notification for those candidates whose Background Verification Form has not been filled.</p>

                                        <p style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #74787E; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left;">If you have seen already ignore it.</p>

                                        <p style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #74787E; font-size: 16px; line-height: 1.5em; margin-top: 10px; text-align: left;">Details are :-</p>

                                        <hr class="w-25">

                                        <div>
                                            @if(count($candidates)>0)
                                                <table class="action" width="100%" cellpadding="0" cellspacing="0"
                                                    style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; margin: 30px auto 0; padding: 0; text-align: center; width: 100%; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%;">
                                                    <tr>
                                                        <th
                                                            style="background-color: #eee; text-align: left; padding: 8px 12px; color: #333; font-size: 14px;">
                                                            Candidate List </th>

                                                        <th style="background-color: #eee; text-align: left; padding: 8px 12px; color: #333; font-size: 14px;">
                                                            {{date('d M Y')}}
                                                        </th>
                                                    </tr>
                                                    
                                                </table>
                                            
                                                <table class="action" width="100%" cellpadding="0" cellspacing="0"
                                                    style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; margin: 0 auto 0; padding: 0; text-align: center; width: 100%; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%;">
                                                    <tr>
                                                        <td
                                                            style="padding: 12px; border-bottom: 1px solid #ccc; color: #888; font-size: 13px; min-width: 50px; text-align: left;">
                                                            &nbsp;
                                                        </td>
                                                        <td
                                                            style="padding: 12px; border-bottom: 1px solid #ccc; color: #333; font-weight: bold; font-size: 13px; min-width:340px; text-align: left;">
                                                            Name
                                                        </td>
                                                        <td
                                                            style="padding: 12px; border-bottom: 1px solid #ccc; color: #333; font-weight: bold; font-size: 13px; min-width:340px; text-align: left;">
                                                            Reference No.
                                                        </td>
                                                    </tr>
                                                    @foreach ($candidates as $candidate)
                                                        <tr>
                                                            <td
                                                                style="padding: 12px; border-bottom: 1px solid #ccc; color: #333; font-size: 13px; min-width: 340px; text-align: left;">
                                                                {{Helper::company_name($candidate->business_id)}}
                                                            </td>
                                                            <td
                                                                style="padding: 12px; border-bottom: 1px solid #ccc; color: #333; font-weight: bold; font-size: 13px; min-width:50px; text-align: left;">
                                                                {{$candidate->name}}
                                                            </td>
                                                            <td
                                                                style="padding: 12px; border-bottom: 1px solid #ccc; color: #333; font-weight: bold; font-size: 13px; min-width:50px; text-align: left;">
                                                                {{$candidate->display_id!=NULL ? $candidate->display_id : '--'}}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    <tr>
                                                        <td
                                                            style="padding: 12px color: #333; font-size: 13px; min-width: 340px; text-align: left;">
                                                            &nbsp;
                                                        </td>
                                                        <td
                                                            style="padding: 12px color: #333; font-weight: bold; font-size: 13px; min-width:50px; text-align: left;">
                                                            &nbsp;
                                                        </td>
                                                        <td
                                                            style="padding: 12px color: #333; font-weight: bold; font-size: 13px; min-width:50px; text-align: left;">
                                                            &nbsp;
                                                        </td>
                                                    </tr>
                                                </table>
                                            @endif

                                        <p style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #74787E; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: left;">Regards, <br> {{Helper::company_name($sender->business_id)}} <br> Thank you <br>
                                            <a href="https://app.clobminds.com">https://app.clobminds.com</a>
                                        </p>
                                        <table class="subcopy" width="100%" cellpadding="0" cellspacing="0" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; border-top: 1px solid #EDEFF2; margin-top: 25px; padding-top: 25px;">
                                            <tr>
                                                <td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box;">
                                                    <p style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #db1f1f; line-height: 1.5em; margin-top: 0; text-align: left; font-size: 12px;">
                                                        Note: Please don't reply on this email, this mail is generated by system .
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box;">
                            <table class="footer" align="center" width="570" cellpadding="0" cellspacing="0" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; margin: 0 auto; padding: 0; text-align: center; width: 570px; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 570px;">
                                <tr>
                                    <td class="content-cell" align="center" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; padding: 35px;">
                                        <p style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; line-height: 1.5em; margin-top: 0; color: #AEAEAE; font-size: 12px; text-align: center;">© {{date('Y')}} {{env('MAIL_FROM_NAME')}}. All rights reserved.</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html> 