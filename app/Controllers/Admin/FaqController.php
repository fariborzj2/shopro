<?php

namespace App\Controllers\Admin;

use App\Models\FaqItem;

class FaqController
{
    public function index()
    {
        $faq_items = FaqItem::findAll();
        view('admin/faq/index', ['faq_items' => $faq_items]);
    }

    public function create()
    {
        view('admin/faq/create');
    }

    public function store()
    {
        $data = [
            'question' => $_POST['question'],
            'answer' => $_POST['answer'],
            'status' => $_POST['status'],
        ];
        FaqItem::create($data);
        redirect('/admin/faq');
    }

    public function edit($id)
    {
        $faq_item = FaqItem::find($id);
        view('admin/faq/edit', ['faq_item' => $faq_item]);
    }

    public function update($id)
    {
        $data = [
            'question' => $_POST['question'],
            'answer' => $_POST['answer'],
            'status' => $_POST['status'],
        ];
        FaqItem::update($id, $data);
        redirect('/admin/faq');
    }

    public function destroy($id)
    {
        FaqItem::delete($id);
        redirect('/admin/faq');
    }
}
