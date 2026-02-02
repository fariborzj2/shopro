<?php

namespace AiContentPro\Services;

use AiContentPro\Models\AiSetting;
use AiContentPro\Models\AiLog;

class GeminiService {

    private $apiKey;
    private $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models/';

    public function __construct() {
        $this->apiKey = AiSetting::get('gemini_api_key');
    }

    /**
     * Generate content using Gemini API
     *
     * @param string $prompt The user prompt
     * @param string $systemInstruction Optional system instruction
     * @param string|null $model Model name
     * @param float $temperature
     * @return string|null Generated text or null on failure
     */
    public function generateContent($prompt, $systemInstruction = '', $model = null, $temperature = 0.7) {
        if (empty($this->apiKey)) {
            AiLog::error("Gemini API Key is missing.");
            return null;
        }

        if (!$model) {
            $model = AiSetting::get('model_content', 'gemini-1.5-flash');
            if ($model === 'custom') {
                $model = AiSetting::get('custom_model_name', 'gemini-1.5-flash');
            }
        }

        // Strictly follow Quickstart: Key in header or query param
        $url = $this->baseUrl . $model . ':generateContent';

        $payload = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ],
            'generationConfig' => [
                'temperature' => $temperature,
            ]
        ];

        if (!empty($systemInstruction)) {
            $payload['system_instruction'] = [
                'parts' => [
                    ['text' => $systemInstruction]
                ]
            ];
        }

        $headers = [
            'Content-Type: application/json',
            'x-goog-api-key: ' . $this->apiKey
        ];

        try {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);

            if ($error) {
                AiLog::error("Gemini cURL Error: " . $error);
                return null;
            }

            if ($httpCode !== 200) {
                AiLog::error("Gemini API Error ($httpCode): " . $response, ['model' => $model]);
                return null;
            }

            $data = json_decode($response, true);

            if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                return $data['candidates'][0]['content']['parts'][0]['text'];
            } else {
                AiLog::error("Gemini Unexpected Response Structure", ['response' => $data]);
                return null;
            }

        } catch (\Exception $e) {
            AiLog::error("Gemini Exception: " . $e->getMessage());
            return null;
        }
    }
}
