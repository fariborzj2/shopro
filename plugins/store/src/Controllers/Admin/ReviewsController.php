<?php

namespace Store\Controllers\Admin;

use Store\Models\Review;
use App\Core\Request;

class ReviewsController
{
    public function index()
    {
        $reviews = Review::findAll();
        store_view('main', 'reviews/index', ['reviews' => $reviews]);
    }

    public function edit($id)
    {
        $review = Review::find($id);
        store_view('main', 'reviews/edit', ['review' => $review]);
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
