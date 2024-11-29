<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;

class CustomEmailController
{
    public function sendEmail($subject, $toMail, $toName = '', $templateName = '', $templateData = '')
    {
        Log::info('templateName', ['templateName' => $templateName]);


        $user = '';
        $htmlContent = '';
        if (!empty($templateData) && !empty($templateName) && $templateName == 'register_otp') {
            $htmlContent = view('mails.register_otp', ['otp_code' => $templateData])->render();
        }
        if (!empty($templateData) && !empty($templateName) && $templateName == 'forget_otp') {
            $htmlContent = view('mails.forget_otp', ['otp_code' => $templateData])->render();
        }
        

        $url = config('app.MAIL_API');

        $postFields = [
            "from" => [
                "address" => config("mail.from.address"),
                "name" => config("mail.from.name"),
            ],
            "to" => [
                [
                    "email_address" => [
                        "address" => $toMail,
                        "name" => $toName,
                    ],
                ],
            ],
            "subject" => $subject,
            "htmlbody" => $htmlContent,
        ];

        $postJson = json_encode($postFields);

        $httpHeaders = array(
            "accept: application/json",
            "authorization: " . config("app.MAIL_AUTHORIZATION"),
            "cache-control: no-cache",
            "content-type: application/json",
        );
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $postJson,
            CURLOPT_HTTPHEADER => $httpHeaders,
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        if ($err) {
            Log::info(['cURL Error sendEmail' => $err()]);
            return false;
        } else {
            return true;
        }

    }
}
