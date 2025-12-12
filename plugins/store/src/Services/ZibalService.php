<?php

namespace Store\Services;

class ZibalService
{
    private const API_REQUEST_URL = 'https://gateway.zibal.ir/v1/request';
    private const API_VERIFY_URL = 'https://gateway.zibal.ir/v1/verify';
    private const PAYMENT_URL = 'https://gateway.zibal.ir/start/';

    private $merchant_id;

    public function __construct($merchant_id)
    {
        $this->merchant_id = $merchant_id;
    }

    /**
     * Create a payment request.
     *
     * @param int $amount Amount in Rials.
     * @param string $callback_url
     * @param int $order_id
     * @param string $description
     * @return array ['success' => bool, 'track_id' => int|null, 'payment_url' => string|null, 'message' => string]
     */
    public function request($amount, $callback_url, $order_id, $description = '')
    {
        $payload = [
            'merchant' => $this->merchant_id,
            'amount' => $amount,
            'callbackUrl' => $callback_url,
            'orderId' => $order_id,
            'description' => $description,
        ];

        $response = $this->sendRequest(self::API_REQUEST_URL, $payload);

        if (isset($response['result']) && $response['result'] == 100) {
            return [
                'success' => true,
                'track_id' => $response['trackId'],
                'payment_url' => self::PAYMENT_URL . $response['trackId'],
                'message' => 'Success',
            ];
        }

        return [
            'success' => false,
            'track_id' => null,
            'payment_url' => null,
            'message' => $response['message'] ?? 'Unknown error',
        ];
    }

    /**
     * Verify a payment.
     *
     * @param int $track_id
     * @return array ['success' => bool, 'status' => int|null, 'message' => string, 'response' => array]
     */
    public function verify($track_id)
    {
        $payload = [
            'merchant' => $this->merchant_id,
            'trackId' => $track_id,
        ];

        $response = $this->sendRequest(self::API_VERIFY_URL, $payload);

        if (isset($response['result']) && $response['result'] == 100) {
            return [
                'success' => true,
                'status' => $response['status'],
                'message' => 'Payment verified successfully.',
                'response' => $response,
            ];
        }

        return [
            'success' => false,
            'status' => $response['status'] ?? null,
            'message' => $response['message'] ?? 'Verification failed.',
            'response' => $response,
        ];
    }

    private function sendRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

        $result = curl_exec($ch);
        curl_close($ch);

        return json_decode($result, true);
    }
}
