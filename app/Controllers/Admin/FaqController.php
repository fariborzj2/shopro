<?php

namespace App\Controllers\Admin;

use App\Models\FaqItem;

class FaqController
{
    public function index()
    {
        $type = $_GET['type'] ?? null;
        $faq_items = FaqItem::findAllFiltered($type);
        view('main', 'faq/index', ['items' => $faq_items, 'selected_type' => $type]);
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
            'position' => isset($_POST['position']) ? (int)$_POST['position'] : 0,
        ];
        FaqItem::create($data);
        redirect('/faq');
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
            'position' => isset($_POST['position']) ? (int)$_POST['position'] : 0,
        ];
        FaqItem::update($id, $data);
        redirect('/faq');
    }

    public function reorder()
    {
        header('Content-Type: application/json');

        $input = json_decode(file_get_contents('php://input'), true);
        $ids = $input['ids'] ?? [];

        if (empty($ids) || !is_array($ids)) {
            echo json_encode(['success' => false, 'message' => 'Invalid data.']);
            http_response_code(400);
            return;
        }

        if (FaqItem::updateOrder($ids)) {
            echo json_encode(['success' => true, 'message' => 'Order updated successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update order.']);
            http_response_code(500);
        }
    }

    public function delete($id)
    {
        FaqItem::delete($id);
        redirect('/faq');
    }
}
