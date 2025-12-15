<?php

namespace AiContentPro\Controllers;

use AiContentPro\Models\AiJob;
use App\Core\Request;

class GeneratorController {

    public function createJob() {
        if (!verify_csrf_token()) {
             header('Content-Type: application/json');
             echo json_encode(['error' => 'Invalid CSRF Token']);
             return;
        }

        $input = Request::input(); // Assuming Request::input() or Request::json() gets JSON body
        // Or Request::all() handles JSON.
        // Let's use file_get_contents('php://input') to be safe if Request is not fully known
        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true);

        if (!$data || !isset($data['type'])) {
             header('Content-Type: application/json');
             echo json_encode(['error' => 'Invalid Payload']);
             return;
        }

        $jobId = AiJob::create($data['type'], $data['payload'] ?? []);

        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'job_id' => $jobId]);
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
        // Trigger worker manually via AJAX
         if (!verify_csrf_token()) {
             header('Content-Type: application/json');
             echo json_encode(['error' => 'Invalid CSRF Token']);
             return;
        }

        $worker = new \AiContentPro\Workers\QueueWorker();
        $results = $worker->processPendingJobs();

        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'processed' => $results]);
    }
}
