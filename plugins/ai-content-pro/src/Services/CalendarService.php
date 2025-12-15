<?php

namespace AiContentPro\Services;

use AiContentPro\Models\AiSetting;
use AiContentPro\Models\AiCalendar;
use AiContentPro\Models\AiJob;

class CalendarService {
    private $gemini;

    public function __construct() {
        $this->gemini = new GeminiService();
    }

    public function generatePlan($period = 'month', $topic = 'General') {
        $count = ($period === 'week') ? 7 : 30;

        $systemPrompt = "You are a content strategist. Create a content calendar for the next {$count} days. " .
            "Topic/Niche: {$topic}. " .
            "Language: Persian. " .
            "Output strictly as a JSON array of objects. " .
            "Each object must have: 'title' (Persian), 'content_type' (blog, guide, faq), 'brief' (short description).";

        $userPrompt = "Generate a {$period}ly content plan.";

        $jsonStr = $this->gemini->generateContent($userPrompt, $systemPrompt);
        $jsonStr = str_replace(['```json', '```'], '', $jsonStr);
        $plan = json_decode($jsonStr, true);

        if (!is_array($plan)) {
            throw new \Exception("Invalid JSON format from AI");
        }

        $startDate = new \DateTime();
        $addedItems = [];

        foreach ($plan as $index => $item) {
            $date = clone $startDate;
            $date->modify("+{$index} days");
            $formattedDate = $date->format('Y-m-d');

            $id = AiCalendar::create($item['title'], $formattedDate, $item['content_type'] ?? 'blog');

            // Optionally create a draft job immediately?
            // Let's just store it in calendar.
            // The prompt says "Output status: Draft, Scheduled".
            // We can create a job for it if "Auto Schedule" is on, but for now just populating the calendar table is the goal.

            $addedItems[] = [
                'id' => $id,
                'title' => $item['title'],
                'date' => $formattedDate
            ];
        }

        return $addedItems;
    }
}
