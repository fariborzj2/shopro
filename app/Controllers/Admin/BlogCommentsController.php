<?php

namespace App\Controllers\Admin;

use App\Models\Comment;
use App\Core\Request;
use App\Core\Paginator;

class BlogCommentsController
{
    public function index()
    {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $search = isset($_GET['search']) ? trim($_GET['search']) : null;
        $status = isset($_GET['status']) && $_GET['status'] !== 'all' ? $_GET['status'] : null;
        $sort = isset($_GET['sort']) ? $_GET['sort'] : 'created_at';
        $dir = isset($_GET['dir']) ? $_GET['dir'] : 'desc';

        $data = Comment::getPaginatedList($page, 10, $status, $search, $sort, $dir);

        $paginator = new Paginator($data['total'], 10, $page, '/blog/comments');

        view('admin/blog/comments/index', [
            'comments' => $data['items'],
            'paginator' => $paginator,
            'search' => $search,
            'status' => $status,
            'sort' => $sort,
            'dir' => $dir
        ]);
    }

    public function edit($id)
    {
        $comment = Comment::find($id);
        if (!$comment) {
            redirect_back_with_error('نظر مورد نظر یافت نشد.');
            return;
        }

        view('/blog/comments/edit', ['comment' => $comment]);
    }

    public function update($id)
    {
        $request = new Request();
        $data = $request->getBody();

        // Basic validation
        if (empty($data['name']) || empty($data['comment'])) {
             // In a real app, handle validation errors better (with session flashing)
            redirect_back_with_error('نام و متن نظر الزامی است.');
            return;
        }

        $validStatuses = ['pending', 'approved', 'rejected'];
        if (isset($data['status']) && !in_array($data['status'], $validStatuses)) {
            redirect_back_with_error('وضعیت انتخاب شده نامعتبر است.');
            return;
        }

        Comment::update($id, [
            'name' => $data['name'],
            'email' => $data['email'] ?? null,
            'comment' => $data['comment'],
            'status' => $data['status'],
        ]);

        redirect_with_success('/blog/comments', 'نظر با موفقیت ویرایش شد.');
    }

    public function destroy($id)
    {
        Comment::delete($id);
        redirect_with_success('/blog/comments', 'نظر با موفقیت حذف شد.');
    }

    public function updateStatus($id)
    {
        $request = new Request();
        $data = $request->getBody();

        if (isset($data['status']) && in_array($data['status'], ['approved', 'rejected', 'pending'])) {
            Comment::updateStatus($id, $data['status']);
            redirect_with_success('/blog/comments', 'وضعیت نظر تغییر کرد.');
        } else {
             redirect_back_with_error('وضعیت نامعتبر است.');
        }
    }
}
