<?php

namespace App\Controllers;

use App\Models\Review;
use App\Core\Request;
use App\Core\Database;

class ReviewsController
{
    public function store()
    {
        header('Content-Type: application/json');

        if (!isset($_SESSION['user_id'])) {
            http_response_code(401); // Unauthorized
            echo json_encode(['status' => 'error', 'message' => 'برای ثبت نظر باید وارد شوید.']);
            return;
        }

        $data = Request::json();

        // Server-side validation
        $errors = $this->validate($data);
        if (!empty($errors)) {
            http_response_code(422); // Unprocessable Entity
            echo json_encode(['status' => 'error', 'message' => 'اطلاعات وارد شده نامعتبر است.', 'errors' => $errors]);
            return;
        }

        // Sanitize data
        $sanitized_data = $this->sanitize($data);

        $new_review_id = Review::create([
            'product_id' => $sanitized_data['product_id'],
            'user_id' => $_SESSION['user_id'],
            'name' => $_SESSION['user_name'],
            'mobile' => $_SESSION['user_mobile'],
            'rating' => $sanitized_data['rating'],
            'comment' => $sanitized_data['comment'],
            'status' => 'pending', // Reviews are pending approval by default
        ]);

        // Fetch the newly created review to return it in the response
        $new_review = Review::find($new_review_id);
        // We need to add the jdate manually as the model doesn't handle it.
        $new_review['jdate'] = jdate('j F Y', strtotime($new_review['created_at']));


        http_response_code(201); // Created
        echo json_encode([
            'status' => 'success',
            'message' => 'نظر شما با موفقیت ثبت شد و پس از تایید نمایش داده خواهد شد.',
            'review' => $new_review
        ]);
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
