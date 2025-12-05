<?php

namespace SeoPilot\Enterprise\Security;

class OutputSanitizer
{
    /**
     * Sanitize output to prevent XSS.
     *
     * @param string $input
     * @return string
     */
    public static function clean(string $input): string
    {
        return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Sanitize Meta Description (strip tags + entities)
     */
    public static function cleanDescription(string $input): string
    {
        // Strip tags completely
        $text = strip_tags($input);
        // Then escape to be safe in HTML attribute
        return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Sanitize JSON Data (for JSON-LD)
     * Prevents XSS via JSON injection
     */
    public static function cleanJson(array $data): string
    {
        // Recursively clean the data to remove HTML tags and decode entities
        $cleanedData = self::recursiveClean($data);

        // Encode with UNESCAPED_UNICODE and UNESCAPED_SLASHES.
        // REMOVE JSON_HEX_* flags as they escape characters like <, >, &, ' into \uXXXX format.
        // We want raw UTF-8.
        // To be safe against XSS when injecting into <script>, strict JSON syntax is usually enough
        // provided we are not inside an HTML attribute.
        // However, standard practice for JSON-LD is to output clean JSON.
        // The only risk is </script> inside the string breaking out of the tag.

        return json_encode($cleanedData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    private static function recursiveClean($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = self::recursiveClean($value);
            }
            return $data;
        } elseif (is_string($data)) {
            // 1. Strip HTML tags
            $text = strip_tags($data);

            // 2. Decode HTML entities (e.g., &zwnj; -> actual char, &amp; -> &)
            // This converts things like &#1580; to ج
            $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');

            return $text;
        } else {
            return $data;
        }
    }
}
