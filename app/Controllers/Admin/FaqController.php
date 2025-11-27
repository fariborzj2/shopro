<?php

namespace App\Controllers\Admin;

use App\Models\FaqItem;

class FaqController
{
    public function index()
    {
        $faq_items = FaqItem::findAll();
        view('main', 'faq/index', ['items' => $faq_items]);
    }

    public function create()
    {
        view('main', 'faq/create');
    }

    public function store()
    {
        $data = [
            'question' => $_POST['question'],
            'answer' => $_POST['answer'],
            'type' => $_POST['type'],
            'status' => $_POST['status'],
        ];
        FaqItem::create($data);
        redirect('/admin/faq');
    }

    public function edit($id)
    {
        $faq_item = FaqItem::find($id);
        view('main', 'faq/edit', ['item' => $faq_item]);
    }

    public function update($id)
    {
        $data = [
            'question' => $_POST['question'],
            'answer' => $_POST['answer'],
            'type' => $_POST['type'],
            'status' => $_POST['status'],
        ];
        FaqItem::update($id, $data);
        redirect('/admin/faq');
    }

    public function delete($id)
    {
        FaqItem::delete($id);
        redirect('/admin/faq');
    }
}
