<?php

namespace AiNews\Controllers;

use AiNews\Services\Crawler;
use AiNews\Services\Processor;

class AutomationController extends BaseController {
    public function trigger() {
        // CSRF handled globally

        $crawler = new Crawler();
        $foundCount = $crawler->run();

        $processor = new Processor();
        $processedCount = $processor->processPending();

        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'found' => $foundCount,
            'processed' => $processedCount
        ]);
    }
}
