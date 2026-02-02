<?php

namespace AiNews\Services;

use AiNews\Models\Source;
use AiNews\Models\AiNewsHistory;
use AiNews\Models\AiNewsLog;
use AiNews\Models\Setting;

class Crawler {
    public function run() {
        $sources = Source::getActive();
        $processedCount = 0;
        $limit = (int) Setting::get('ai_news_limit_per_run', 5);

        foreach ($sources as $source) {
            if ($processedCount >= $limit) break;

            AiNewsLog::info("کالینگ منبع: " . $source['name']);

            $xml = Fetcher::fetch($source['url']);
            if (!$xml) {
                AiNewsLog::error("خطا در دریافت اطلاعات از منبع: " . $source['name']);
                continue;
            }

            $parser = new FeedParser();
            $items = $parser->parse($xml, $source['type']);

            foreach ($items as $item) {
                if ($processedCount >= $limit) break;

                $url = $item['url'];
                if (AiNewsHistory::exists($url)) continue;

                // Create history record as pending
                AiNewsHistory::create([
                    'source_id' => $source['id'],
                    'original_url' => $url,
                    'status' => 'pending'
                ]);

                $processedCount++;
            }

            Source::updateLastCrawled($source['id']);
        }

        Setting::set('ai_news_last_run', date('Y-m-d H:i:s'));
        return $processedCount;
    }
}
