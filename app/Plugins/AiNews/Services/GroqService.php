<?php

namespace App\Plugins\AiNews\Services;

use App\Plugins\AiNews\Models\AiSetting;

class GroqService
{
    private $apiKey;
    private $endpoint;
    private $model;

    public function __construct()
    {
        $this->apiKey = AiSetting::get('groq_api_key');
        $this->endpoint = AiSetting::get('groq_endpoint', 'https://api.groq.com/openai/v1/chat/completions');
        $this->model = AiSetting::get('groq_model', 'llama3-70b-8192');
    }

    public function test()
    {
        if (!$this->apiKey) {
            return ['status' => 'error', 'message' => 'API Key is missing.'];
        }

        $payload = [
            'model' => $this->model,
            'messages' => [
                ['role' => 'user', 'content' => 'Say "OK"']
            ],
            'max_tokens' => 5
        ];

        return $this->sendRequest($payload);
    }

    public function process($data)
    {
        if (!$this->apiKey) return null;

        $promptTemplate = AiSetting::get('prompt_template', $this->getDefaultPrompt());

        $prompt = str_replace(
            ['{{title}}', '{{content}}'],
            [$data['title'], strip_tags($data['content'])],
            $promptTemplate
        );

        $payload = [
            'model' => $this->model,
            'messages' => [
                ['role' => 'system', 'content' => 'You are a professional news editor. Output strictly in valid JSON format.'],
                ['role' => 'user', 'content' => $prompt]
            ],
            'temperature' => 0.7,
            'response_format' => ['type' => 'json_object']
        ];

        $response = $this->sendRequest($payload);

        if ($response['status'] === 'success') {
            $json = $response['data'];
            $content = $json['choices'][0]['message']['content'] ?? null;

            if ($content) {
                $parsed = json_decode($content, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    return $parsed;
                }
            }
        }

        return null;
    }

    private function sendRequest($payload)
    {
        $ch = curl_init($this->endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->apiKey
        ]);
        // Ignore SSL errors for development environments or if cert bundle is missing
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            return ['status' => 'error', 'message' => "Curl Error: $error"];
        }

        if ($httpCode !== 200) {
            return ['status' => 'error', 'message' => "API Error (HTTP $httpCode): " . substr($response, 0, 100)];
        }

        return ['status' => 'success', 'data' => json_decode($response, true)];
    }

    private function getDefaultPrompt()
    {
        return <<<EOT
Analyze the following article and rewrite it for a Persian tech news blog.
Source Title: {{title}}
Source Content: {{content}}

Requirements:
1. Language: Persian (Farsi).
2. Create a catchy, click-worthy Title.
3. Write a summary Excerpt (max 300 chars).
4. Rewrite the Content to be engaging, SEO-friendly, and informative (min 500 words if possible).
5. Generate Meta Title and Meta Description.
6. Extract 5 relevant Tags.

Output Format (JSON strictly):
{
  "title": "...",
  "excerpt": "...",
  "content": "...",
  "meta_title": "...",
  "meta_description": "...",
  "tags": ["tag1", "tag2", ...]
}
EOT;
    }
}
