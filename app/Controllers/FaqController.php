<?php

namespace App\Controllers;

use App\Models\FaqItem;
use App\Core\Request;

class FaqController
{
    public function index()
    {
        $items = FaqItem::all();
        view('faq/index', ['items' => $items]);
    }

    public function create()
    {
        view('faq/create');
    }

    public function store()
    {
        $request = new Request();
        $data = $request->all();

        if (empty($data['question']) || empty($data['answer'])) {
            redirect('/faq/create');
            return;
        }

        FaqItem::create($data);
        redirect('/faq');
    }

    public function edit($id)
    {
        $item = FaqItem::find($id);
        if (!$item) {
            redirect('/faq');
            return;
        }
        view('faq/edit', ['item' => $item]);
    }

    public function update($id)
    {
        $request = new Request();
        $data = $request->all();

        if (empty($data['question']) || empty($data['answer'])) {
            redirect('/faq/edit/' . $id);
            return;
        }

        FaqItem::update($id, $data);
        redirect('/faq');
    }

    public function delete($id)
    {
        FaqItem::delete($id);
        redirect('/faq');
    }
}
