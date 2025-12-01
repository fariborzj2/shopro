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
        $this->model = AiSetting::get('groq_model', 'llama-3.3-70b-versatile');
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

        $systemPrompt = "You are a specialized Persian AI Assistant, acting as a Senior Content Writer and SEO Expert. " .
            "Your goal is to generate professional, comprehensive, and engaging content in Persian (Farsi). " .
            "You strictly adhere to JSON output format and HTML content structure. " .
            "Do not include conversational fillers. Output ONLY valid JSON.";

        $payload = [
            'model' => $this->model,
            'messages' => [
                ['role' => 'system', 'content' => $systemPrompt],
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
Act as a professional content writer and SEO specialist. Rewrite the following news into a comprehensive, engaging blog post in Persian (Farsi).

Source Title: {{title}}
Source Content: {{content}}

Requirements:
1. **Role**: You are an expert writer. Do not mention you are an AI. Tone should be professional, informative, and engaging.
2. **Structure**:
   - **Title**: Catchy, click-worthy, optimized for SEO.
   - **Excerpt**: A compelling summary (max 300 chars).
   - **Content**:
     - Minimum 600 words.
     - Use HTML formatting (<h2>, <h3>, <p>, <ul>, <li>, <strong>).
     - Break text into readable paragraphs.
     - Include a detailed analysis, context, or elaboration on the news.
   - **FAQ**: Create a dedicated FAQ section with 3-5 relevant questions and answers based on the text.
   - **SEO**:
     - Generate a Meta Title (max 60 chars).
     - Generate a Meta Description (max 160 chars).
     - Extract 5-7 relevant Tags (single words or short phrases).

3. **Output Format**: Strictly Valid JSON. No markdown code blocks (```json).

JSON Schema:
{
  "title": "String",
  "excerpt": "String",
  "content": "String (HTML formatted)",
  "meta_title": "String",
  "meta_description": "String",
  "tags": ["String", "String", ...],
  "faq": [
    {"question": "String", "answer": "String"},
    {"question": "String", "answer": "String"}
  ]
}
EOT;
    }
}
