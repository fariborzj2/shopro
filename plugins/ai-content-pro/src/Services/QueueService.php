<?php

namespace AiContentPro\Services;

use AiContentPro\Core\Config;
use AiContentPro\Core\Logger;
use App\Core\Database;

class QueueService
{
    public static function push($type, $payload)
    {
        if (Config::get('queue_enabled') !== '1') {
             // If queue is disabled, we might want to run immediately or reject
             // For safety/performance, we reject async requests if queue is off.
             Logger::warning("Queue is disabled, job rejected.", ['type' => $type]);
             return false;
        }

        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("INSERT INTO ai_cp_queue (type, payload, status) VALUES (?, ?, 'pending')");
            $stmt->execute([$type, json_encode($payload, JSON_UNESCAPED_UNICODE)]);
            return true;
        } catch (\PDOException $e) {
            Logger::error("Queue Push Error", ['error' => $e->getMessage()]);
            return false;
        }
    }

    public static function process()
    {
        if (Config::get('queue_enabled') !== '1') {
            return;
        }

        $limit = (int)Config::get('queue_max_concurrent', 1);
        $db = Database::getConnection();

        // Lock and fetch pending jobs
        // Using MySQL atomic update to claim jobs
        $db->beginTransaction();

        try {
            // Select jobs that are pending or failed but have retries left
            // and haven't been touched in a while (e.g., stuck processing)
            $stmt = $db->prepare("SELECT id, type, payload, attempts FROM ai_cp_queue
                                WHERE status = 'pending'
                                OR (status = 'processing' AND last_attempt_at < (NOW() - INTERVAL 10 MINUTE))
                                OR (status = 'failed' AND attempts < ?)
                                ORDER BY created_at ASC LIMIT ? FOR UPDATE SKIP LOCKED");

            $retryLimit = (int)Config::get('queue_retry_limit', 3);
            $stmt->execute([$retryLimit, $limit]);
            $jobs = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            if (!$jobs) {
                $db->commit();
                return;
            }

            // Mark as processing
            $ids = array_column($jobs, 'id');
            $inQuery = implode(',', array_fill(0, count($ids), '?'));
            $updateStmt = $db->prepare("UPDATE ai_cp_queue SET status = 'processing', last_attempt_at = NOW(), attempts = attempts + 1 WHERE id IN ($inQuery)");
            $updateStmt->execute($ids);

            $db->commit();

            // Process each job
            foreach ($jobs as $job) {
                self::runJob($job);
            }

        } catch (\Exception $e) {
            $db->rollBack();
            Logger::error("Queue Processing Error", ['error' => $e->getMessage()]);
        }
    }

    private static function runJob($job)
    {
        $payload = json_decode($job['payload'], true);
        $success = false;
        $errorMsg = '';

        try {
            switch ($job['type']) {
                case 'content_generate':
                    $service = new ContentGenerator();
                    $service->process($payload);
                    $success = true;
                    break;
                case 'comment_reply':
                    $service = new CommentService();
                    $service->process($payload);
                    $success = true;
                    break;
                default:
                    throw new \Exception("Unknown job type: " . $job['type']);
            }
        } catch (\Throwable $e) {
            $errorMsg = $e->getMessage();
            Logger::error("Job Failed [{$job['id']}]", ['error' => $errorMsg, 'job' => $job]);
        }

        // Update status
        $db = Database::getConnection();
        $status = $success ? 'completed' : 'failed';
        // If failed, we might retry later if attempts < limit, which is handled by select query logic next time.
        // But we mark it as failed now.
        $stmt = $db->prepare("UPDATE ai_cp_queue SET status = ?, updated_at = NOW() WHERE id = ?");
        $stmt->execute([$status, $job['id']]);
    }
}
