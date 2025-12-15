<?php

namespace AiContentPro\Controllers;

use AiContentPro\Models\AiJob;
use App\Core\Request;

class GeneratorController {

    public function createJob() {
        // CSRF handled globally

        $input = Request::input();
        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true);

        if (!$data || !isset($data['type'])) {
             header('Content-Type: application/json');
             echo json_encode(['error' => 'Invalid Payload']);
             return;
        }

        $jobId = AiJob::create($data['type'], $data['payload'] ?? []);

        header('Content-Type: application/json');
        // Return new CSRF token if needed for subsequent requests
        $response = ['success' => true, 'job_id' => $jobId];
        if (isset($_SESSION['csrf_token'])) {
            $response['new_csrf_token'] = $_SESSION['csrf_token'];
        }
        echo json_encode($response);
    }

    public function status($id) {
        $job = AiJob::find($id);

        header('Content-Type: application/json');
        if (!$job) {
            echo json_encode(['error' => 'Job not found']);
        } else {
            echo json_encode($job);
        }
    }

    public function processQueue() {
        // CSRF handled globally

        $worker = new \AiContentPro\Workers\QueueWorker();
        $results = $worker->processPendingJobs();

        header('Content-Type: application/json');
        $response = ['success' => true, 'processed' => $results];
        if (isset($_SESSION['csrf_token'])) {
            $response['new_csrf_token'] = $_SESSION['csrf_token'];
        }
        echo json_encode($response);
    }
}
