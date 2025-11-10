<?php

namespace App\Controllers;

use App\Models\BlogTag;

class BlogTagsController
{
    /**
     * Show a list of all tags.
     */
    public function index()
    {
        $tags = BlogTag::all();
        return view('main', 'blog/tags/index', [
            'title' => 'مدیریت برچسب‌ها',
            'tags' => $tags
        ]);
    }

    /**
     * Show the form for creating a new tag.
     */
    public function create()
    {
        return view('main', 'blog/tags/create', ['title' => 'افزودن برچسب جدید']);
    }

    /**
     * Store a new tag.
     */
    public function store()
    {
        if (empty($_POST['name']) || empty($_POST['slug'])) {
            die('Name and slug are required.');
        }
        BlogTag::create($_POST);
        header('Location: /blog/tags');
        exit();
    }

    /**
     * Show the form for editing a tag.
     * @param int $id
     */
    public function edit($id)
    {
        $tag = BlogTag::find($id);
        return view('main', 'blog/tags/edit', [
            'title' => 'ویرایش برچسب',
            'tag' => $tag
        ]);
    }

    /**
     * Update an existing tag.
     * @param int $id
     */
    public function update($id)
    {
        if (empty($_POST['name']) || empty($_POST['slug'])) {
            die('Name and slug are required.');
        }
        BlogTag::update($id, $_POST);
        header('Location: /blog/tags');
        exit();
    }

    /**
     * Delete a tag.
     * @param int $id
     */
    public function delete($id)
    {
        BlogTag::delete($id);
        header('Location: /blog/tags');
        exit();
    }
}
