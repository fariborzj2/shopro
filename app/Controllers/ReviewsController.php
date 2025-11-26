<?php

namespace App\Controllers;

use App\Models\Review;
use App\Core\Request;
use App\Core\Database;

class ReviewsController
{
    public function store()
    {
        if (!isset($_SESSION['user_id'])) {
            redirect_back_with_error('You must be logged in to submit a review.');
        }

        // Use $_POST directly for form data
        $data = $_POST;

        // Server-side validation
        $errors = $this->validate($data);
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $data;
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit();
        }

        // Sanitize data
        $sanitized_data = $this->sanitize($data);

        Review::create([
            'product_id' => $sanitized_data['product_id'],
            'user_id' => $_SESSION['user_id'],
            'name' => $_SESSION['user_name'],      // Get name from session
            'mobile' => $_SESSION['user_mobile'],  // Get mobile from session
            'rating' => $sanitized_data['rating'],
            'comment' => $sanitized_data['comment'],
            'status' => 'pending',
        ]);

        // Redirect back with a success message
        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        redirect_with_success($referer, 'نظر شما با موفقیت ثبت شد و پس از تایید نمایش داده خواهد شد.');
    }

    private function validate($data)
    {
        $errors = [];

        if (empty($data['product_id']) || !filter_var($data['product_id'], FILTER_VALIDATE_INT)) {
            $errors['product_id'] = 'Invalid product specified.';
        }
        if (empty($data['rating']) || !filter_var($data['rating'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 5]])) {
            $errors['rating'] = 'Rating must be between 1 and 5.';
        }
        if (empty($data['comment']) || strlen($data['comment']) > 500) {
            $errors['comment'] = 'Comment is required and must be less than 500 characters.';
        }

        return $errors;
    }

    private function sanitize($data)
    {
        $sanitized_data = [];
        foreach ($data as $key => $value) {
            if ($key === 'comment') {
                $sanitized_data[$key] = strip_tags($value, '<p>');
            } else {
                $sanitized_data[$key] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
            }
        }
        return $sanitized_data;
    }
}
