<?php

namespace AiContentPro\Services;

use AiContentPro\Core\Config;
use AiContentPro\Core\Logger;
use App\Core\Database;

class CalendarService
{
    private $gemini;

    public function __construct()
    {
        $this->gemini = new GeminiService();
    }

    public function generatePlan($topic, $days = 7, $itemsPerDay = 1)
    {
        if (Config::get('calendar_enabled') !== '1') {
             throw new \Exception("Calendar module is disabled.");
        }

        $prompt = "یک برنامه محتوایی {$days} روزه برای وبلاگ با موضوع اصلی '{$topic}' ایجاد کن.
        روزی {$itemsPerDay} پست.
        خروجی باید دقیقاً یک آرایه JSON باشد شامل اشیاء با فیلدهای:
        title (عنوان فارسی جذاب),
        type (blog, guide, news),
        summary (خلاصه کوتاه).
        هیچ متن اضافه ای ننویس.";

        $jsonStr = $this->gemini->generate($prompt, 3000);

        // Clean markdown code blocks if present
        $jsonStr = str_replace(['```json', '```'], '', $jsonStr);

        $plan = json_decode($jsonStr, true);

        if (!is_array($plan)) {
            throw new \Exception("Failed to parse AI plan.");
        }

        $this->savePlan($plan);
        return count($plan);
    }

    private function savePlan($items)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO ai_cp_calendar (title, type, scheduled_at, status, meta) VALUES (?, ?, ?, 'planned', ?)");

        $startDate = new \DateTime();

        foreach ($items as $index => $item) {
            // Schedule for consecutive days
            $date = clone $startDate;
            $date->modify('+' . floor($index) . ' days'); // Simple distribution

            $stmt->execute([
                $item['title'] ?? 'بدون عنوان',
                $item['type'] ?? 'blog',
                $date->format('Y-m-d 10:00:00'),
                json_encode(['summary' => $item['summary'] ?? ''], JSON_UNESCAPED_UNICODE)
            ]);
        }
    }
}
