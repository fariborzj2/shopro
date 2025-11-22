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

        // Handle Meta Keywords (array to string)
        if (isset($data['meta_keywords']) && is_array($data['meta_keywords'])) {
            $data['meta_keywords'] = implode(',', $data['meta_keywords']);
        }

        // Handle published_at (Timestamp)
        if (!empty($data['published_at'])) {
            $timestamp = (int)$data['published_at'];
            $data['published_at'] = date('Y-m-d H:i:s', $timestamp);
        // Handle published_at (Timestamp)
        if (!empty($data['published_at'])) {
            $timestamp = (int)$data['published_at'];
            $data['published_at'] = date('Y-m-d H:i:s', $timestamp);
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

        // Handle Meta Keywords (array to string)
        if (isset($data['meta_keywords']) && is_array($data['meta_keywords'])) {
            $data['meta_keywords'] = implode(',', $data['meta_keywords']);
        }

        // Handle Jalali Date Conversion for published_at
        if (!empty($data['published_at'])) {
            $jalaliDate = $data['published_at'];
            $parts = preg_split('/[\/\-\s:]/', $jalaliDate);
            if (count($parts) >= 5) {
                 $jy = (int)$parts[0];
                 $jm = (int)$parts[1];
                 $jd = (int)$parts[2];
                 $h = (int)$parts[3];
                 $m = (int)$parts[4];
                 $s = isset($parts[5]) ? (int)$parts[5] : 0;

                 $gregorian = jalali_to_gregorian($jy, $jm, $jd);
                 $data['published_at'] = sprintf('%04d-%02d-%02d %02d:%02d:%02d', $gregorian[0], $gregorian[1], $gregorian[2], $h, $m, $s);
            }
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
