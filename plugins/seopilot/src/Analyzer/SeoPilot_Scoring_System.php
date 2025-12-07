<?php

namespace SeoPilot\Enterprise\Analyzer;

class SeoPilot_Scoring_System
{
    /**
     * Calculate Score (0-100)
     */
    public static function calculateScore(array $results): int
    {
        $score = 0;
        $maxScore = 0;

        // --- 1. Content (30 points) ---
        // Keyword in Title (5)
        $maxScore += 5;
        if ($results['content']['keyword_in_title'] ?? false) $score += 5;

        // Keyword Density (10)
        $maxScore += 10;
        $density = $results['content']['density'] ?? 0;
        if ($density >= 0.5 && $density <= 2.5) $score += 10;
        elseif ($density > 0) $score += 5;

        // Content Length (10)
        $maxScore += 10;
        $words = $results['content']['word_count'] ?? 0;
        if ($words >= 800) $score += 10;
        elseif ($words >= 300) $score += 5;

        // Keyword in Conclusion (5)
        $maxScore += 5;
        if (($results['content']['keyword_in_conclusion'] ?? false)) $score += 5;


        // --- 2. Readability (20 points) ---
        // Sentence Length (5)
        $maxScore += 5;
        if (($results['readability']['long_sentences_count'] ?? 0) == 0) $score += 5;

        // Sentence Variety (5)
        $maxScore += 5;
        if (($results['readability']['consecutive_starts_issues'] ?? 0) == 0) $score += 5;

        // Half-Space Issues (5)
        $maxScore += 5;
        if (!($results['readability']['half_space_issues'] ?? false)) $score += 5;

        // Transition Words (5)
        $maxScore += 5;
        if (($results['readability']['transition_score'] ?? false)) $score += 5;


        // --- 3. Structure (15 points) ---
        // Single H1 (3)
        $maxScore += 3;
        if (($results['structure']['h1_count'] ?? 0) === 1) $score += 3;

        // Hierarchy (3)
        $maxScore += 3;
        if (($results['structure']['hierarchy_ok'] ?? true)) $score += 3;

        // Long Paragraphs (3)
        $maxScore += 3;
        if (($results['structure']['long_paragraphs'] ?? 0) === 0) $score += 3;

        // Lists (3)
        $maxScore += 3;
        if (($results['structure']['has_list'] ?? false)) $score += 3;

        // Keyword in Subheaders (3)
        $maxScore += 3;
        if (($results['structure']['keyword_in_subheaders_percent'] ?? 0) > 0) $score += 3;


        // --- 4. Links (15 points) ---
        // Internal Links (5)
        $maxScore += 5;
        if (($results['links']['internal'] ?? 0) >= 3) $score += 5;

        // External Links (5)
        $maxScore += 5;
        if (($results['links']['external'] ?? 0) > 0) $score += 5;

        // Link Attributes (5)
        $maxScore += 5;
        if (($results['links']['unsafe_external_count'] ?? 0) == 0) $score += 5;


        // --- 5. Images (10 points) ---
        // Alt Tags (5)
        $maxScore += 5;
        if (($results['images']['no_alt'] ?? 0) === 0) $score += 5;

        // Filenames (5)
        $maxScore += 5;
        if (($results['images']['bad_filenames'] ?? 0) === 0) $score += 5;


        // --- 6. Technical (10 points) ---
        // Meta Title & Desc (5)
        $maxScore += 5;
        if (($results['technical']['meta_title_ok'] ?? false) && ($results['technical']['meta_desc_ok'] ?? false)) $score += 5;

        // URL (5)
        $maxScore += 5;
        if (($results['technical']['url_short'] ?? false) && ($results['technical']['keyword_in_url'] ?? false)) $score += 5;


        // Final Calculation
        if ($maxScore == 0) return 0;
        return (int) round(($score / $maxScore) * 100);
    }

    /**
     * Generate Prioritized To-Do List
     */
    public static function generateToDoList(array $results): array
    {
        $items = [];

        // --- Critical (Red) ---

        if (!($results['content']['keyword_in_title'] ?? false)) {
            $items[] = ['type' => 'critical', 'msg' => 'کلمه کلیدی در عنوان سئو (Meta Title) یافت نشد.'];
        }

        if (($results['content']['word_count'] ?? 0) < 300) {
            $items[] = ['type' => 'critical', 'msg' => 'محتوا بسیار کوتاه است (کمتر از ۳۰۰ کلمه).'];
        }

        if (($results['images']['no_alt'] ?? 0) > 0) {
            $items[] = ['type' => 'critical', 'msg' => $results['images']['no_alt'] . ' تصویر بدون متن جایگزین (Alt) دارید.'];
        }

        $density = $results['content']['density'] ?? 0;
        if ($density > 2.5) {
            $items[] = ['type' => 'critical', 'msg' => 'تکرار کلمه کلیدی بیش از حد است (Stuffing).'];
        }

        if (($results['structure']['h1_count'] ?? 0) === 0) {
            $items[] = ['type' => 'critical', 'msg' => 'صفحه فاقد تیتر اصلی (H1) است.'];
        } elseif (($results['structure']['h1_count'] ?? 0) > 1) {
             $items[] = ['type' => 'critical', 'msg' => 'بیش از یک تگ H1 در صفحه وجود دارد.'];
        }

        // --- Warning (Yellow) ---

        if ($density < 0.5 && $density > 0) {
            $items[] = ['type' => 'warning', 'msg' => 'تکرار کلمه کلیدی کم است (کمتر از ۰.۵٪).'];
        }

        if (!($results['content']['keyword_in_conclusion'] ?? false)) {
             $items[] = ['type' => 'warning', 'msg' => 'کلمه کلیدی در پاراگراف پایانی (نتیجه‌گیری) یافت نشد.'];
        }

        if (($results['links']['internal'] ?? 0) < 2) {
             $items[] = ['type' => 'warning', 'msg' => 'تعداد لینک‌های داخلی کم است.'];
        }

        if (($results['links']['unsafe_external_count'] ?? 0) > 0) {
             $items[] = ['type' => 'warning', 'msg' => 'برخی لینک‌های خارجی در تب جدید باز نمی‌شوند (target="_blank" ندارند).'];
        }

        if (($results['readability']['half_space_issues'] ?? false)) {
             $items[] = ['type' => 'warning', 'msg' => 'رعایت نیم‌فاصله در متن نیاز به اصلاح دارد (می‌شود/ها).'];
        }

        if (($results['readability']['long_sentences_count'] ?? 0) > 0) {
             $items[] = ['type' => 'warning', 'msg' => $results['readability']['long_sentences_count'] . ' جمله بسیار طولانی یافت شد.'];
        }

        if (($results['readability']['consecutive_starts_issues'] ?? 0) > 0) {
             $items[] = ['type' => 'warning', 'msg' => 'تکرار کلمه در شروع جملات متوالی دیده می‌شود.'];
        }

        if (!($results['structure']['has_list'] ?? false)) {
             $items[] = ['type' => 'warning', 'msg' => 'از لیست‌ها (شماره‌گذاری یا بولت) برای شکستن متن استفاده نشده است.'];
        }

        if (($results['images']['bad_filenames'] ?? 0) > 0) {
             $items[] = ['type' => 'warning', 'msg' => 'برخی تصاویر نام فایل نامناسب دارند (فارسی یا بی‌معنی). از انگلیسی استفاده کنید.'];
        }

        if (!($results['technical']['meta_desc_ok'] ?? false)) {
             $items[] = ['type' => 'warning', 'msg' => 'طول توضیحات متا مناسب نیست (یا کوتاه است یا خیلی بلند).'];
        }

        if (!($results['technical']['url_short'] ?? false)) {
             $items[] = ['type' => 'warning', 'msg' => 'آدرس صفحه (URL) طولانی است.'];
        }

        // --- Good (Green) ---
        // (Optional: We can list passed checks or just return issues)
        // For "To-Do List", usually we only show problems.

        return $items;
    }
}
