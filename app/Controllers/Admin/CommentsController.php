<?php

namespace App\Controllers\Admin;

use App\Models\Comment;
use App\Core\Request;

class CommentsController
{
    public function index()
    {
        $comments = Comment::findAll();
        view('admin/comments/index', ['comments' => $comments]);
    }

    public function edit($id)
    {
        $comment = Comment::find($id);
        view('admin/comments/edit', ['comment' => $comment]);
    }

    public function update($id)
    {
        $request = new Request();
        $data = $request->getBody();

        Comment::update($id, [
            'name' => $data['name'],
            'email' => $data['email'],
            'comment' => $data['comment'],
            'status' => $data['status'],
        ]);

        redirect('/admin/comments');
    }

    public function destroy($id)
    {
        Comment::delete($id);
        redirect('/admin/comments');
    }
}
