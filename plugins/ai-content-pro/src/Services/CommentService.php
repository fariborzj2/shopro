<?php

namespace AiContentPro\Services;

use AiContentPro\Core\Config;
use AiContentPro\Core\Logger;
use App\Core\Database;

class CommentService
{
    private $gemini;

    public function __construct()
    {
        $this->gemini = new GeminiService();
    }

    public function process($payload)
    {
        if (Config::get('comments_enabled') !== '1') {
             return;
        }

        $commentId = $payload['comment_id'];
        $commentText = $payload['comment_text'];

        // Check sentiment
        if ($this->isNegative($commentText)) {
            Logger::info("Comment skipped due to negative sentiment", ['id' => $commentId]);
            return;
        }

        $tone = Config::get('comments_tone', 'professional');
        $prompt = "به عنوان مدیر سایت، یک پاسخ {$tone} و کوتاه (حداکثر 50 کلمه) به نظر زیر بده:
        '{$commentText}'
        زبان فارسی.";

        $reply = $this->gemini->generate($prompt, 200);

        // Save reply
        $this->saveReply($commentId, $reply);
    }

    private function isNegative($text)
    {
        // Simple keyword check or AI check.
        // For fail-safety and speed, let's use a keyword list first, or ask AI if budget permits.
        // Let's ask AI efficiently.
        $prompt = "Analyze the sentiment of this Persian comment: '{$text}'.
        If it is negative, offensive, or angry, reply with 'NEGATIVE'.
        If it is positive, neutral, or a question, reply with 'SAFE'.
        Reply with only one word.";

        $result = $this->gemini->generate($prompt, 10);
        return stripos($result, 'NEGATIVE') !== false;
    }

    private function saveReply($commentId, $replyText)
    {
        // Insert into blog_post_comments
        // Assuming parent_id support or just a new comment linked to post?
        // Usually replies have parent_id.

        $db = Database::getConnection();

        // Get post_id from comment
        $stmt = $db->prepare("SELECT post_id, author_name FROM blog_post_comments WHERE id = ?");
        $stmt->execute([$commentId]);
        $original = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$original) return;

        $stmt = $db->prepare("INSERT INTO blog_post_comments (
            post_id, parent_id, author_name, email, content, status, created_at
        ) VALUES (?, ?, ?, ?, ?, 'approved', NOW())");

        $adminName = 'پشتیبانی هوشمند';
        $adminEmail = 'ai@example.com';

        $stmt->execute([
            $original['post_id'],
            $commentId,
            $adminName,
            $adminEmail,
            $replyText
        ]);

        Logger::info("Auto-replied to comment #{$commentId}");
    }
}
