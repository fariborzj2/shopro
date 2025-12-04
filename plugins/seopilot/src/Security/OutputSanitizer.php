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
        return json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
    }
}
