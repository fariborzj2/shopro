<?php

namespace AiContentPro\Services;

use AiContentPro\Core\Config;

class GeminiService
{
    private $apiKey;
    private $apiUrl;
    private $model;

    public function __construct()
    {
        $this->apiKey = Config::get('api_key');
        $this->apiUrl = Config::get('api_url', 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent');
        $this->model = Config::get('content_model', 'gemini-1.5-flash');
        $this->updateUrl();
    }

    public function setModel($model)
    {
        $this->model = $model;
        $this->updateUrl();
    }

    private function updateUrl()
    {
        // Only override if default generic URL is used or we are switching models
        // Assuming base URL pattern
        if (strpos($this->apiUrl, 'generativelanguage.googleapis.com') !== false) {
             $this->apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent";
        }
    }

    public function generate($prompt, $maxTokens = 2000, $temperature = 0.7)
    {
        if (empty($this->apiKey)) {
            throw new \Exception("API Key تنظیم نشده است.");
        }

        $payload = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt]
                    ]
                ]
            ],
            'generationConfig' => [
                'maxOutputTokens' => (int)$maxTokens,
                'temperature' => (float)$temperature
            ]
        ];

        $ch = curl_init($this->apiUrl . '?key=' . $this->apiKey);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            throw new \Exception("Gemini Connection Error: " . $error);
        }

        if ($httpCode !== 200) {
            throw new \Exception("Gemini API Error {$httpCode}: " . $response);
        }

        $data = json_decode($response, true);

        if (!isset($data['candidates'][0]['content']['parts'][0]['text'])) {
            throw new \Exception("Gemini Invalid Response: " . $response);
        }

        return $data['candidates'][0]['content']['parts'][0]['text'];
    }
}
