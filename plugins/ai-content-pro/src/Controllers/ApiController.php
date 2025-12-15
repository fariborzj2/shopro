<?php

namespace AiContentPro\Controllers;

use AiContentPro\Services\QueueService;
use AiContentPro\Core\Logger;
use AiContentPro\Core\Config;
use App\Core\Database;

class ApiController
{
    public function runWorker()
    {
        // 1. Security Check: Validate Cron Token
        $token = $_GET['token'] ?? null;
        $storedToken = Config::get('cron_token');

        // If no token set in config, auto-generate one to lock it down
        if (empty($storedToken)) {
            $storedToken = bin2hex(random_bytes(16));
            Config::set('cron_token', $storedToken);
        }

        if ($token !== $storedToken) {
            http_response_code(403);
            die(json_encode(['status' => 'error', 'message' => 'Invalid Token']));
        }

        // Disable time limit for worker
        set_time_limit(300); // 5 minutes

        try {
            QueueService::process();
            echo json_encode(['status' => 'success', 'message' => 'Queue processed']);
        } catch (\Throwable $e) {
            Logger::error("Worker Failed", ['error' => $e->getMessage()]);
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function queueStats()
    {
        // Admin only for stats
        if (!isset($_SESSION['admin_id'])) {
             http_response_code(403);
             die();
        }

        $db = Database::getConnection();
        $stmt = $db->query("SELECT status, COUNT(*) as count FROM ai_cp_queue GROUP BY status");
        $stats = $stmt->fetchAll(\PDO::FETCH_KEY_PAIR);

        echo json_encode($stats);
    }
}
