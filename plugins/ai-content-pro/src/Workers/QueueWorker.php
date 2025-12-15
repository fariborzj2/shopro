<?php

namespace AiContentPro\Workers;

use AiContentPro\Models\AiJob;
use AiContentPro\Models\AiSetting;
use AiContentPro\Models\AiLog;
use AiContentPro\Services\ContentEngine;
use AiContentPro\Services\SeoService;
use AiContentPro\Services\CommentService;
use AiContentPro\Services\CalendarService;

class QueueWorker {

    public function processPendingJobs() {
        $jobs = AiJob::getPending(5);
        $processed = 0;

        foreach ($jobs as $job) {
            $this->processJob($job);
            $processed++;
        }

        return $processed;
    }

    private function processJob($job) {
        AiJob::updateStatus($job['id'], 'processing');

        try {
            $payload = json_decode($job['payload'], true);
            $result = null;

            switch ($job['type']) {
                case 'generate_article':
                    if (AiSetting::get('enable_content_gen') !== '1') {
                        throw new \Exception("Feature 'generate_article' is disabled.");
                    }
                    $engine = new ContentEngine();
                    $result = $engine->generateArticle($payload['topic'], $payload['options'] ?? []);
                    break;

                case 'generate_meta':
                    if (AiSetting::get('enable_seo') !== '1') {
                         throw new \Exception("Feature 'generate_meta' is disabled.");
                    }
                    $seo = new SeoService();
                    $result = $seo->generateMeta($payload['content']);
                    break;

                case 'generate_reply':
                    if (AiSetting::get('enable_comments') !== '1') {
                         throw new \Exception("Feature 'generate_reply' is disabled.");
                    }
                    $comment = new CommentService();
                    $result = $comment->generateReply($payload['comment_text'], $payload['sentiment'] ?? 'positive');
                    break;

                case 'generate_calendar':
                    if (AiSetting::get('enable_calendar') !== '1') {
                         throw new \Exception("Feature 'enable_calendar' is disabled.");
                    }
                    $calendar = new CalendarService();
                    $result = $calendar->generatePlan($payload['period'] ?? 'week', $payload['topic'] ?? 'General');
                    break;

                default:
                    throw new \Exception("Unknown job type: " . $job['type']);
            }

            if ($result === null) {
                throw new \Exception("AI Provider returned null.");
            }

            // Language Validation for Articles
            if ($job['type'] === 'generate_article') {
                if (!$this->validatePersian($result)) {
                     throw new \Exception("Language validation failed: Content must be predominantly in Persian.");
                }
            }

            AiJob::updateStatus($job['id'], 'completed', $result);
            AiLog::info("Job {$job['id']} completed successfully.");

        } catch (\Exception $e) {
            $retryLimit = (int) AiSetting::get('queue_retry_limit', 3);
            $attempts = (int) $job['attempts'] + 1;

            if ($attempts < $retryLimit) {
                $this->retryJob($job['id'], $attempts, $e->getMessage());
                AiLog::info("Job {$job['id']} failed (Attempt $attempts/$retryLimit). Retrying...");
            } else {
                AiJob::updateStatus($job['id'], 'failed', null, $e->getMessage());
                AiLog::error("Job {$job['id']} failed permanently: " . $e->getMessage());
            }
        }
    }

    private function retryJob($id, $attempts, $message) {
        $db = \App\Core\Database::getConnection();
        $stmt = $db->prepare("UPDATE ai_cp_jobs SET status = 'pending', attempts = ?, error_message = ?, updated_at = NOW() WHERE id = ?");
        $stmt->execute([$attempts, $message, $id]);
    }

    private function validatePersian($text) {
        $text = strip_tags($text);
        if (empty($text)) return false;

        $length = mb_strlen($text);
        if ($length === 0) return false;

        // Count Persian/Arabic characters
        $persianCount = preg_match_all('/[\x{0600}-\x{06FF}]/u', $text);

        // Count total alphanumeric (roughly) to ignore whitespace/symbols in ratio
        // Actually, just length is fine, but spaces skew it.
        // Let's use simple ratio: PersianChars / TotalChars.
        // Persian texts often have English terms (HTML tags removed), spaces, numbers.
        // A threshold of 50% is safe for "predominantly" Persian.

        $ratio = $persianCount / $length;

        return $ratio > 0.4; // 40% allows for heavy technical terms and formatting chars
    }
}
