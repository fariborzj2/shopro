<?php

namespace App\Controllers\Admin;

use App\Models\Page;
use App\Core\Request;

class PagesController
{
    public function index()
    {
        $pages = Page::all();
        view('main', 'pages/index', [
            'title' => 'مدیریت صفحات',
            'pages' => $pages
        ]);
    }

    public function create()
    {
        view('main', 'pages/create', [
            'title' => 'ایجاد صفحه جدید'
        ]);
    }

    public function store()
    {
        $request = new Request();
        $data = $request->all();

        // Basic validation
        if (empty($data['title']) || empty($data['slug'])) {
            // Handle error, maybe redirect back with message
            redirect('/pages/create');
            return;
        }

        Page::create($data);
        redirect('/pages');
    }

    public function edit($id)
    {
        $page = Page::find($id);
        if (!$page) {
            // Handle not found
            redirect('/pages');
            return;
        }
        view('main', 'pages/edit', [
            'title' => 'ویرایش صفحه',
            'page' => $page
        ]);
    }

    public function update($id)
    {
        $request = new Request();
        $data = $request->all();

        // Basic validation
        if (empty($data['title']) || empty($data['slug'])) {
            // Handle error
            redirect('/pages/edit/' . $id);
            return;
        }

        Page::update($id, $data);
        redirect('/pages');
    }

    public function delete($id)
    {
        Page::delete($id);
        redirect('/pages');
    }
}
