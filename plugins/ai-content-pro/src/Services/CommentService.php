<?php

namespace AiContentPro\Services;

use AiContentPro\Models\AiSetting;

class CommentService {
    private $gemini;

    public function __construct() {
        $this->gemini = new GeminiService();
    }

    public function generateReply($commentText, $sentiment = 'positive') {
        $systemPrompt = "You are a helpful customer support agent. " .
            "Write a polite, professional, and short reply to the following user comment in Persian. " .
            "The reply should acknowledge their feedback.";

        if ($sentiment === 'positive') {
            $systemPrompt .= " The comment is positive, thank them warmly.";
        } else {
            $systemPrompt .= " The comment is neutral/inquiry, provide a helpful response.";
        }

        return $this->gemini->generateContent($commentText, $systemPrompt);
    }
}
