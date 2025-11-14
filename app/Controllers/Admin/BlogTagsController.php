<?php

namespace App\Controllers\Admin;

use App\Models\BlogTag;
use App\Core\Database;

class BlogTagsController
{
    public function index()
    {
        $tags = BlogTag::findAll();
        view('admin/blog/tags/index', ['tags' => $tags]);
    }

    public function create()
    {
        view('admin/blog/tags/create');
    }

    public function store()
    {
        $data = [
            'name' => $_POST['name'],
            'slug' => $_POST['slug'],
            'status' => $_POST['status'],
        ];
        BlogTag::create($data);
        redirect('/admin/blog/tags');
    }

    public function edit($id)
    {
        $tag = BlogTag::find($id);
        view('admin/blog/tags/edit', ['tag' => $tag]);
    }

    public function update($id)
    {
        $data = [
            'name' => $_POST['name'],
            'slug' => $_POST['slug'],
            'status' => $_POST['status'],
        ];
        BlogTag::update($id, $data);
        redirect('/admin/blog/tags');
    }

    public function destroy($id)
    {
        BlogTag::delete($id);
        redirect('/admin/blog/tags');
    }
}
