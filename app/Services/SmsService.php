<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class SmsService
{
    public function sendMessage($type = 'sign_up', $number = '', $otp = '', $alpha_num_uid = '', $password = '')
    {
        $apiKey = 'so5BA9OZ9we1Dm4i88CXZ1Sgw3Gp5Zcopw7Ve2t1m4g=';
        $pass = '2GFQATTJ';
        $message = '';
        $sender = urlencode('UNIPVT');
        $data = [];
        if ($type == 'send_otp') {
            $message = 'Your OTP for Mobile Verification with Unipay is : ' . $otp . ' UNIPAY DIGITAL PRIVATE LIMITED';
        } else if ($type == 'forget_pwd') {
            $message = 'Your OTP for Forgot Password Verification with Unipay is : ' . $otp . ' UNIPAY DIGITAL PRIVATE LIMITED';
        } else if ($type == 'reg_otp') {
            $message = 'Your OTP for Mobile Verification with Unipay is : ' . $otp . ' UNIPAY DIGITAL PRIVATE LIMITED';
        } else if ($type == 'reg_msg') {
            $message = 'Welcome to UniPay. Your Username : {' . $alpha_num_uid . '} And Password : {' . $password . '} Join Our Telegram Channel for All Updates and Notification about Unipay https://t.me/unipayofficial UNIPAY DIGITAL PRIVATE LIMITED';
        }

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://103.110.127.128/api/v2/SendSMS?',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_POSTFIELDS => array(
                'SenderId' => $sender, 'DataCoding' => 0,
                'Message' => $message,
                'MobileNumbers' => $number,
                'PrincipleEntityId' => '1701162399894104548',
                'TemplateId' => '1707164276251574457',
                'ApiKey' => $apiKey,
                'ClientId' => '498ca7fa-ab4e-44ad-a568-1615c0d673c4'
            ),
            CURLOPT_HTTPHEADER => array(
                'Cookie: SERVERID=webC1'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $arr = json_decode($response);
        $arr = (object) $arr;
        Log::info('SMS Response', [$arr]);

        if ($arr->ErrorCode == 0 && $arr->Data[0]->MessageId) {
            return ['status' => true, 'msg' => $arr->Data[0]->MessageErrorDescription ?? 'OTP sent successfully'];
        } else {
            return ['status' => false, 'msg' => $arr->Data[0]->MessageErrorDescription ?? 'Failed to send OTP'];
        }
    }
}
