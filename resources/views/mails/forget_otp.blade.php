<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <link rel="preconnect" href="https://fonts.gstatic.com" />
    <link rel="stylesheet" href="">
    <title>OTP Verification</title>
</head>


<body style="font-family: 'Nunito', sans-serif; margin: 0px;padding-top: 0;padding-bottom: 0;padding-top: 0;padding-bottom: 0;background-color: #f1f1f1;background-repeat: repeat;width: 100% !important;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;-webkit-font-smoothing: antialiased;">
    <table style="max-width: 600px; width: 600px; margin: 0px auto;" cellpadding="0" cellspacing="0">
        <tbody>
            <tr>
                <td style="padding-top: 50px; padding-bottom: 50px;">
                    <table width="100%" height="100%" cellpadding="0" cellspacing="0" align="left">
                        <tbody>
                            <tr>
                                <td style="padding: 0; background-color: #fff;">
                                    <table style="width: 100%;" cellpadding="0" cellspacing="0">
                                        <tbody>
                                            <tr>
                                                <td style="text-align: center; vertical-align: middle; height: 100px;">
                                                    <img src="{{ asset('frontend/img/logo.png') }}" style="height:60px;width:200px;margin-top:10px;" alt="" />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 0px 50px;">
                                                    <table style="width: 100%;" cellpadding="0" cellspacing="0">
                                                        <tr>
                                                            <td style="padding-bottom: 30px;">
                                                                <div style="display: block;">
                                                                    <h1 style="font-size: 26px; font-weight: 700; color: #000000; margin: 0; margin-bottom: 30px; text-align: center; ">OTP Verification</h1>
                                                                    <p style="font-size: 14px; font-weight: 400; line-height: 20px; color: #637b96; margin: 0; letter-spacing: 0.5px; line-height: 24px;">
                                                                        Welcome back to EzzyCare, 
                                                                    </p>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="padding-bottom: 30px;">
                                                                <p style="font-size: 14px; font-weight: 400; line-height: 20px; color: #637b96; margin: 0; letter-spacing: 0.5px; margin-bottom: 20px; line-height: 24px;">Kindly use this 6 digit code to Reset your password.</p>
                                                                <div style="text-align: center;">
                                                                    <div style="text-align: center;font-size: 20px;padding: 10px 40px;border: 2px solid #f78125;border-radius: 27px;display: inline-block;margin: 0 auto;font-weight: 600;color: #28282c;background-color: #e1e1e1;">
                                                                        <strong>
                                                                            {{ $otp_code }}
                                                                        </strong>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="padding-bottom: 30px;">
                                                                <div style="display: block; ">
                                                                    <p style="font-size: 14px;  font-weight: 400; line-height: 20px; color: #637b96;margin: 0; letter-spacing: 0.5px; line-height: 24px;">
                                                                        Best Regards,
                                                                    </p>
                                                                    <h6 style="margin:0;font-size: 15px; font-weight: 600; color: #1e2e50;">EzzyCare Team</h6>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</body>

</html>