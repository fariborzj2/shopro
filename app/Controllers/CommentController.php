<?php

namespace App\Controllers;

use App\Models\Comment;
use App\Core\Request;
use App\Core\Captcha;

class CommentController
{
    public function store()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $request = new Request();
        $data = $request->getBody();

        // Validate captcha
        if (!isset($_SESSION['captcha']) || $_SESSION['captcha'] != $data['captcha']) {
            echo json_encode(['success' => false, 'message' => 'پاسخ کپچا نادرست است، لطفاً دوباره تلاش کنید.']);
            return;
        }

        // Server-side validation
        $errors = $this->validate($data);
        if (!empty($errors)) {
            echo json_encode(['success' => false, 'errors' => $errors]);
            return;
        }

        // Sanitize data
        $sanitized_data = $this->sanitize($data);

        $comment_id = Comment::create([
            'post_id' => $sanitized_data['post_id'],
            'parent_id' => !empty($sanitized_data['parent_id']) ? $sanitized_data['parent_id'] : null,
            'name' => $sanitized_data['name'],
            'email' => !empty($sanitized_data['email']) ? $sanitized_data['email'] : null,
            'comment' => $sanitized_data['comment'],
            'status' => 'pending',
        ]);

        $comment = Comment::find($comment_id);
        echo json_encode(['success' => true, 'comment' => $comment]);
    }

    private function validate($data)
    {
        $errors = [];

        if (empty($data['name']) || strlen($data['name']) > 50) {
            $errors['name'] = 'Name is required and must be less than 50 characters.';
        }
        if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Invalid email format.';
        }
        if (empty($data['comment']) || strlen($data['comment']) > 800) {
            $errors['comment'] = 'Comment is required and must be less than 800 characters.';
        }

        return $errors;
    }

    private function sanitize($data)
    {
        $sanitized_data = [];
        foreach ($data as $key => $value) {
            $sanitized_data[$key] = htmlspecialchars(strip_tags($value), ENT_QUOTES, 'UTF-8');
        }
        return $sanitized_data;
    }
}
