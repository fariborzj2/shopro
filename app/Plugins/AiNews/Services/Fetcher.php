<?php

namespace App\Plugins\AiNews\Services;

class Fetcher
{
    private $maxRetries = 2;
    private $retryDelay = 1; // seconds

    public function fetch($url)
    {
        $attempts = 0;
        $content = null;
        $error = null;
        $statusCode = 0;

        while ($attempts <= $this->maxRetries) {
            $attempts++;

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);

            // Browser Headers
            curl_setopt($ch, CURLOPT_USERAGENT, $this->getRandomUserAgent());
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,*/*;q=0.8',
                'Accept-Language: en-US,en;q=0.9',
                'Cache-Control: no-cache',
                'Connection: keep-alive',
                'Upgrade-Insecure-Requests: 1'
            ]);

            $content = curl_exec($ch);
            $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);

            // Success
            if ($content !== false && $statusCode >= 200 && $statusCode < 300) {
                return ['success' => true, 'content' => $content, 'status' => $statusCode];
            }

            // Permanent Errors (404, 403, 410) - Do not retry
            if (in_array($statusCode, [403, 404, 410])) {
                return ['success' => false, 'error' => "HTTP $statusCode", 'status' => $statusCode, 'retry' => false];
            }

            // Retry logic for 429, 5xx, or network errors
            if ($attempts <= $this->maxRetries) {
                sleep($this->retryDelay * $attempts); // Exponential backoff
            }
        }

        return ['success' => false, 'error' => $error ?: "HTTP $statusCode", 'status' => $statusCode, 'retry' => true];
    }

    private function getRandomUserAgent()
    {
        $agents = [
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
            'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:109.0) Gecko/20100101 Firefox/121.0'
        ];
        return $agents[array_rand($agents)];
    }
}
