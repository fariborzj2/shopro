<?php

namespace App\Core;

class SmsService
{
    /**
     * Sends an OTP using the specified IPPanel API structure.
     *
     * @param string $mobile The recipient's mobile number.
     * @param string $otp The one-time password.
     * @param array $settings An associative array of SMS settings
     *                        (e.g., ['sms_api_key' => '...', 'sms_pattern_code' => '...', 'sms_sender_number' => '...']).
     * @return bool True on success, false on failure.
     */
    public static function sendOtp($mobile, $otp, array $settings)
    {
        $api_key = $settings['sms_api_key'] ?? null;
        $pattern_code = $settings['sms_pattern_code'] ?? null;
        $sender = $settings['sms_sender_number'] ?? null;

        if (!$api_key || !$pattern_code || !$sender) {
            error_log("SMS settings are incomplete.");
            return false;
        }

        $url = "https://api2.ippanel.com/api/v1/sms/pattern/normal/send";

        $data = [
            "code" => $pattern_code,
            "sender" => $sender,
            "recipient" => $mobile,
            "variable" => [
                "verification-code" => $otp
            ]
        ];

        $header = [
            "apikey: " . $api_key,
            "Content-Type: application/json"
        ];

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => $header
        ]);

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_code >= 200 && $http_code < 300) {
            error_log("SMS sent successfully to {$mobile}. Response: {$response}");
            return true;
        } else {
            error_log("Failed to send SMS to {$mobile}. HTTP Code: {$http_code}, Response: {$response}");
            return false;
        }
    }
}
