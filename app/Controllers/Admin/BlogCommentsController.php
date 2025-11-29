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

        view('main', 'blog/comments/index', [
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

        view('main', 'blog/comments/edit', ['comment' => $comment]);
    }

    public function update($id)
    {
        // Static call as Request methods are static
        $data = Request::getBody();

        // Basic validation
        if (empty($data['name']) || empty($data['comment'])) {
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
        $data = Request::getBody();

        if (isset($data['status']) && in_array($data['status'], ['approved', 'rejected', 'pending'])) {
            Comment::updateStatus($id, $data['status']);
            redirect_with_success('/blog/comments', 'وضعیت نظر تغییر کرد.');
        } else {
             redirect_back_with_error('وضعیت نامعتبر است.');
        }
    }

    public function reply($id)
    {
        $data = Request::getBody();
        $parentComment = Comment::find($id);

        if (!$parentComment) {
            redirect_back_with_error('نظر والد یافت نشد.');
            return;
        }

        if (empty($data['reply_content'])) {
            redirect_back_with_error('متن پاسخ نمی‌تواند خالی باشد.');
            return;
        }

        // Create the reply
        Comment::create([
            'post_id' => $parentComment['post_id'],
            'parent_id' => $id,
            'name' => $_SESSION['admin_name'] ?? 'مدیر سایت', // Use admin name from session
            'email' => $_SESSION['admin_email'] ?? 'admin@example.com',
            'comment' => $data['reply_content'],
            'status' => 'approved' // Admin replies are auto-approved
        ]);

        // If the parent comment was pending, approve it too (optional workflow preference)
        if ($parentComment['status'] === 'pending') {
            Comment::updateStatus($id, 'approved');
        }

        redirect_with_success('/blog/comments', 'پاسخ شما با موفقیت ثبت شد.');
    }
}
