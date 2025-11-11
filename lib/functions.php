<?php

/**
 * Generates a secure random OTP (One-Time Password).
 *
 * @param int $length The desired length of the OTP.
 * @return string The generated OTP.
 */
function generate_otp(int $length = 6): string
{
    try {
        $otp = '';
        for ($i = 0; $i < $length; $i++) {
            $otp .= random_int(0, 9);
        }
        return $otp;
    } catch (Exception $e) {
        // Fallback for environments where random_int is not available
        return (string) rand(pow(10, $length - 1), pow(10, $length) - 1);
    }
}

/**
 * Sends an OTP using the IPPanel SMS service.
 *
 * @param string $mobile The recipient's mobile number.
 * @param string $otp The OTP code to be sent.
 * @return bool True on success, false on failure.
 */
function send_otp_sms(string $mobile, string $otp): bool
{
    $url = "https://api2.ippanel.com/api/v1/sms/pattern/normal/send";
    $data = [
        "code"      => IPPANEL_PATTERN_CODE,
        "sender"    => IPPANEL_SENDER,
        "recipient" => $mobile,
        "variable"  => [
            "verification-code" => $otp
        ]
    ];
    $header = [
        "apikey: " . IPPANEL_API_KEY,
        "Content-Type: application/json"
    ];

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => $header,
        CURLOPT_TIMEOUT => 10, // 10-second timeout
    ]);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Consider a successful send if the HTTP status code is in the 2xx range
    return $http_code >= 200 && $http_code < 300;
}

/**
 * Validates a mobile number.
 * A simple regex for Iranian mobile numbers.
 *
 * @param string $mobile
 * @return bool
 */
function is_valid_mobile(string $mobile): bool
{
    return (bool) preg_match('/^09[0-9]{9}$/', $mobile);
}

/**
 * Sends a standardized JSON response and terminates the script.
 *
 * @param string $status 'ok' or 'error'.
 * @param string $message The message to be sent.
 * @param array $data Additional data to include in the response.
 * @param int $http_code The HTTP status code to set.
 */
function json_response(string $status, string $message, array $data = [], int $http_code = 200)
{
    header_remove();
    header("Content-Type: application/json");
    http_response_code($http_code);

    $response = ['status' => $status, 'message' => $message];
    if (!empty($data)) {
        $response = array_merge($response, $data);
    }

    echo json_encode($response);
    exit();
}
