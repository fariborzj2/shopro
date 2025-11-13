<?php

namespace App\Core;

class SmsService
{
    /**
     * Sends an OTP to a mobile number using a generic pattern-based SMS API.
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

        // This is a generic payload for a pattern-based SMS service like Kavenegar or IPPanel.
        // The structure may need to be adjusted for a different provider.
        $payload = [
            'pattern_code' => $pattern_code,
            'originator' => $sender,
            'recipient' => $mobile,
            'values' => [
                'code' => $otp,
            ],
        ];

        // Example for IPPanel SMS Gateway URL
        $url = 'http://rest.ippanel.com/v1/messages/patterns/send';

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: AccessKey ' . $api_key
        ]);

        $result = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_code >= 200 && $http_code < 300) {
            // Successfully sent
            error_log("SMS sent successfully to {$mobile}. Response: {$result}");
            return true;
        } else {
            // Failed to send
            error_log("Failed to send SMS to {$mobile}. HTTP Code: {$http_code}, Response: {$result}");
            return false;
        }
    }
}
