<?php

namespace AiContentPro\Controllers;

use AiContentPro\Models\AiJob;
use AiContentPro\Models\AiSetting;
use App\Core\Request;

class GeneratorController extends BaseController {

    public function index() {
        $settings = AiSetting::getAll();
        $this->view('generator', [
            'settings' => $settings
        ], 'ابزار تولید محتوای هوشمند');
    }

    public function createBulk() {
        $data = Request::all();

        if (empty($data['topics']) || !is_array($data['topics'])) {
             header('Content-Type: application/json');
             echo json_encode(['error' => 'Invalid Bulk Payload']);
             return;
        }

        $count = 0;
        foreach ($data['topics'] as $topic) {
            AiJob::create($data['type'] ?? 'generate_article', ['topic' => $topic]);
            $count++;
        }

        header('Content-Type: application/json');
        $response = ['success' => true, 'count' => $count];
        if (isset($_SESSION['csrf_token'])) {
            $response['new_csrf_token'] = $_SESSION['csrf_token'];
        }
        echo json_encode($response);
    }

    public function createJob() {
        // CSRF handled globally

        $data = Request::all();

        if (empty($data['type'])) {
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
