<?php

namespace SeoPilot\Enterprise\Analyzer;

use SeoPilot\Enterprise\NLP\SeoPilot_Persian_Normalizer;

class SeoPilot_Analyzer_Core
{
    public static function analyze(string $content, string $keyword, string $title, string $metaTitle = '', string $metaDesc = '', string $slug = ''): array
    {
        $normalizedKeyword = SeoPilot_Persian_Normalizer::normalize($keyword);
        $text = strip_tags($content);
        $normalizedText = SeoPilot_Persian_Normalizer::normalize($text);

        $textForDensity = SeoPilot_Persian_Normalizer::removeStopWords($text);

        $dom = new \DOMDocument();
        $domContent = trim($content);
        if (empty($domContent)) $domContent = '<div></div>';
        libxml_use_internal_errors(true);
        @$dom->loadHTML('<?xml encoding="utf-8" ?>' . $domContent, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        $contentMetrics = self::analyzeContent($dom, $text, $normalizedText, $textForDensity, $normalizedKeyword, $title);
        $readabilityMetrics = self::analyzeReadability($text);
        $structureMetrics = self::analyzeStructure($dom, $normalizedKeyword, $title);
        $linkMetrics = self::analyzeLinks($dom);
        $imageMetrics = self::analyzeImages($dom, $normalizedKeyword);
        $technicalMetrics = self::analyzeTechnical($metaTitle, $metaDesc, $normalizedKeyword, $slug);

        return [
            'content' => $contentMetrics,
            'readability' => $readabilityMetrics,
            'structure' => $structureMetrics,
            'links' => $linkMetrics,
            'images' => $imageMetrics,
            'technical' => $technicalMetrics,
            'lsi' => self::getLsiSuggestions($keyword)
        ];
    }

    private static function analyzeContent(\DOMDocument $dom, $text, $normalizedText, $textForDensity, $keyword, $title)
    {
        $wordCount = SeoPilot_Persian_Normalizer::wordCount($text);

        $density = 0;
        $count = 0;
        if ($wordCount > 0 && !empty($keyword)) {
            $cleanWordCount = SeoPilot_Persian_Normalizer::wordCount($textForDensity);
            $count = substr_count($normalizedText, $keyword);

            if ($cleanWordCount > 0) {
                 $density = round(($count / $cleanWordCount) * 100, 2);
            }
        }

        // Keyword in First 100 words
        $first100 = implode(' ', array_slice(explode(' ', $normalizedText), 0, 100));
        $inFirst100 = !empty($keyword) && strpos($first100, $keyword) !== false;

        // Keyword in Conclusion (Last Paragraph)
        $paragraphs = $dom->getElementsByTagName('p');
        $inConclusion = false;
        if ($paragraphs->length > 0) {
            $lastP = $paragraphs->item($paragraphs->length - 1)->textContent;
            $normLastP = SeoPilot_Persian_Normalizer::normalize($lastP);
            $inConclusion = !empty($keyword) && strpos($normLastP, $keyword) !== false;
        }

        // Keyword in Title
        $normalizedTitle = SeoPilot_Persian_Normalizer::normalize($title);
        $inTitle = !empty($keyword) && strpos($normalizedTitle, $keyword) !== false;

        return [
            'word_count' => $wordCount,
            'density' => $density,
            'keyword_count' => $count,
            'keyword_in_first_100' => $inFirst100,
            'keyword_in_conclusion' => $inConclusion,
            'keyword_in_title' => $inTitle
        ];
    }

    private static function analyzeReadability($text)
    {
        $sentences = preg_split('/[.?!؟]+/', $text, -1, PREG_SPLIT_NO_EMPTY);
        if (!$sentences) $sentences = [];

        $longSentences = 0;
        $totalSentences = count($sentences);
        $transitionCount = 0;

        $transitions = ['بنابراین', 'علاوه بر این', 'در نتیجه', 'همچنین', 'از سوی دیگر', 'با این حال', 'به عبارت دیگر', 'پس', 'سپس'];

        $sentenceStarts = [];
        $consecutiveStarts = 0;

        foreach ($sentences as $s) {
            $s = trim($s);
            if (empty($s)) continue;

            if (SeoPilot_Persian_Normalizer::wordCount($s) > 25) {
                $longSentences++;
            }

            foreach ($transitions as $t) {
                if (strpos($s, $t) !== false) {
                    $transitionCount++;
                    break;
                }
            }

            $words = explode(' ', $s);
            $firstWord = $words[0] ?? '';
            if (!empty($firstWord)) {
                $sentenceStarts[] = $firstWord;
            }
        }

        for ($i = 0; $i < count($sentenceStarts) - 2; $i++) {
            if ($sentenceStarts[$i] === $sentenceStarts[$i+1] && $sentenceStarts[$i+1] === $sentenceStarts[$i+2]) {
                $consecutiveStarts++;
            }
        }

        $halfSpaceIssues = SeoPilot_Persian_Normalizer::hasHalfSpaceIssues($text);
        $transitionScore = ($totalSentences > 0) ? ($transitionCount / $totalSentences) > 0.3 : false;

        $readingTime = ceil(SeoPilot_Persian_Normalizer::wordCount($text) / 200);

        return [
            'long_sentences_count' => $longSentences,
            'total_sentences' => $totalSentences,
            'half_space_issues' => $halfSpaceIssues,
            'transition_score' => $transitionScore,
            'reading_time_min' => $readingTime,
            'consecutive_starts_issues' => $consecutiveStarts
        ];
    }

    private static function analyzeStructure(\DOMDocument $dom, $keyword, $title = '')
    {
        $h1 = $dom->getElementsByTagName('h1');
        $h2 = $dom->getElementsByTagName('h2');
        $h3 = $dom->getElementsByTagName('h3');

        $titleH1 = !empty($title) ? 1 : 0;
        $editorH1 = $h1->length;
        $h1Count = $titleH1 + $editorH1;

        $hierarchyOk = !($h3->length > 0 && $h2->length == 0);

        $keywordInSub = 0;
        $totalSub = $h2->length + $h3->length;

        foreach ($h2 as $node) {
            if (!empty($keyword) && strpos(SeoPilot_Persian_Normalizer::normalize($node->textContent), $keyword) !== false) $keywordInSub++;
        }
        foreach ($h3 as $node) {
            if (!empty($keyword) && strpos(SeoPilot_Persian_Normalizer::normalize($node->textContent), $keyword) !== false) $keywordInSub++;
        }

        $keywordInSubPercent = ($totalSub > 0) ? ($keywordInSub / $totalSub) * 100 : 0;

        $paragraphs = $dom->getElementsByTagName('p');
        $longParagraphs = 0;
        foreach ($paragraphs as $p) {
            if (SeoPilot_Persian_Normalizer::wordCount($p->textContent) > 150) {
                $longParagraphs++;
            }
        }

        $hasList = ($dom->getElementsByTagName('ul')->length > 0 || $dom->getElementsByTagName('ol')->length > 0);

        return [
            'h1_count' => $h1Count,
            'hierarchy_ok' => $hierarchyOk,
            'keyword_in_subheaders_percent' => $keywordInSubPercent,
            'long_paragraphs' => $longParagraphs,
            'has_list' => $hasList
        ];
    }

    private static function analyzeLinks(\DOMDocument $dom)
    {
        $internal = 0;
        $external = 0;
        $unsafeExternal = 0;
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';

        foreach ($dom->getElementsByTagName('a') as $link) {
            $href = $link->getAttribute('href');
            if (empty($href) || strpos($href, '#') === 0) continue;

            if (strpos($href, $host) !== false || strpos($href, '/') === 0) {
                $internal++;
            } else {
                $external++;
                $target = $link->getAttribute('target');
                if ($target !== '_blank') $unsafeExternal++;
            }
        }

        return [
            'internal' => $internal,
            'external' => $external,
            'unsafe_external_count' => $unsafeExternal
        ];
    }

    private static function analyzeImages(\DOMDocument $dom, $keyword)
    {
        $imgs = $dom->getElementsByTagName('img');
        $noAlt = 0;
        $keywordInAlt = false;
        $badFilenames = 0;

        foreach ($imgs as $img) {
            $alt = $img->getAttribute('alt');
            $src = $img->getAttribute('src');

            if (empty($alt)) {
                $noAlt++;
            } else {
                if (!empty($keyword) && strpos(SeoPilot_Persian_Normalizer::normalize($alt), $keyword) !== false) {
                    $keywordInAlt = true;
                }
            }

            $filename = basename($src);
            if (preg_match('/[\x{0600}-\x{06FF}]/u', $filename)) {
                $badFilenames++;
            }
            if (preg_match('/^(img|image|dsc|pic)[-_]?\d+/i', $filename)) {
                $badFilenames++;
            }
        }

        return [
            'count' => $imgs->length,
            'no_alt' => $noAlt,
            'keyword_in_alt' => $keywordInAlt,
            'bad_filenames' => $badFilenames
        ];
    }

    private static function analyzeTechnical($title, $desc, $keyword, $slug)
    {
        $titleLen = mb_strlen($title);
        $titleOk = $titleLen > 0 && $titleLen < 60;

        $descLen = mb_strlen($desc);
        $descOk = $descLen >= 120 && $descLen <= 160;

        $nTitle = SeoPilot_Persian_Normalizer::normalize($title);
        $nDesc = SeoPilot_Persian_Normalizer::normalize($desc);
        $nSlug = SeoPilot_Persian_Normalizer::normalize($slug);

        $keywordInTitleStart = !empty($keyword) && mb_strpos($nTitle, $keyword) === 0;
        $keywordInDesc = !empty($keyword) && strpos($nDesc, $keyword) !== false;

        $urlShort = mb_strlen($slug) < 75;
        $keywordInUrl = !empty($keyword) && strpos(urldecode($nSlug), $keyword) !== false;

        return [
            'meta_title_ok' => $titleOk,
            'meta_desc_ok' => $descOk,
            'keyword_at_start_title' => $keywordInTitleStart,
            'keyword_in_desc' => $keywordInDesc,
            'url_short' => $urlShort,
            'keyword_in_url' => $keywordInUrl
        ];
    }

    private static function getLsiSuggestions($keyword)
    {
        if (empty($keyword) || mb_strlen($keyword) < 2) {
            return [];
        }

        // Fetch suggestions from Google
        // URL: http://suggestqueries.google.com/complete/search?client=chrome&q={keyword}&hl=fa
        $url = "http://suggestqueries.google.com/complete/search?client=chrome&q=" . urlencode($keyword) . "&hl=fa";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Set User-Agent to avoid blocking
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36");
        curl_setopt($ch, CURLOPT_TIMEOUT, 2); // Fast timeout to not block UI

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200 && $response) {
            // Response format: ["keyword", ["suggestion1", "suggestion2", ...], ...]
            $data = json_decode($response, true);
            if (isset($data[1]) && is_array($data[1])) {
                // Filter out the exact keyword match if present
                $suggestions = array_filter($data[1], function($s) use ($keyword) {
                    return $s !== $keyword;
                });
                return array_slice($suggestions, 0, 8); // Return top 8
            }
        }

        // Fallback if API fails
        return [
            $keyword . ' چیست',
            'خرید ' . $keyword,
            'بهترین ' . $keyword,
            'قیمت ' . $keyword
        ];
    }
}
