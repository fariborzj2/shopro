<?php

namespace App\Controllers\Admin;

use App\Models\Review;
use App\Core\Request;

class ReviewsController
{
    public function index()
    {
        $reviews = Review::findAll();
        view('main', 'reviews/index', ['reviews' => $reviews]);
    }

    public function edit($id)
    {
        $review = Review::find($id);
        view('main', 'reviews/edit', ['review' => $review]);
    }

    public function update($id)
    {
        $request = new Request();
        $data = $request->getBody();

        Review::update($id, [
            'status' => $data['status'],
            'admin_reply' => $data['admin_reply'],
        ]);

        redirect('/admin/reviews');
    }

    public function destroy($id)
    {
        Review::delete($id);
        redirect('/admin/reviews');
    }
}
