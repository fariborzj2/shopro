<?php

namespace App\Controllers;

use App\Models\CustomOrderField;
use App\Core\Request;

class CustomOrderFieldsController
{
    public function index()
    {
        $fields = CustomOrderField::all();
        return view('main', 'custom_fields/index', [
            'title' => 'مدیریت پارامترها (فیلدهای سفارشی)',
            'fields' => $fields
        ]);
    }

    public function create()
    {
        return view('main', 'custom_fields/form', [
            'title' => 'ایجاد فیلد سفارشی جدید',
            'field' => null
        ]);
    }

    public function store()
    {
        $data = $this->prepareDataFromRequest();
        CustomOrderField::create($data);
        header('Location: /custom-fields');
        exit();
    }

    public function edit($id)
    {
        $field = CustomOrderField::find($id);
        if (!$field) {
            redirect_back_with_error('فیلد مورد نظر یافت نشد.');
        }
        return view('main', 'custom_fields/form', [
            'title' => 'ویرایش فیلد سفارشی',
            'field' => $field
        ]);
    }

    public function update($id)
    {
        $field = CustomOrderField::find($id);
        if (!$field) {
            redirect_back_with_error('فیلد مورد نظر یافت نشد.');
        }

        $data = $this->prepareDataFromRequest();
        CustomOrderField::update($id, $data);

        header('Location: /custom-fields');
        exit();
    }

    public function delete($id)
    {
        $field = CustomOrderField::find($id);
        if (!$field) {
            redirect_back_with_error('فیلد مورد نظر یافت نشد.');
        }

        CustomOrderField::delete($id);
        header('Location: /custom-fields');
        exit();
    }

    private function prepareDataFromRequest()
    {
        // Basic validation
        if (empty($_POST['name']) || empty($_POST['label_fa']) || empty($_POST['type'])) {
            redirect_back_with_error('نام فیلد، برچسب و نوع آن الزامی است.');
        }

        $data = [
            'name' => $_POST['name'],
            'label_fa' => $_POST['label_fa'],
            'type' => $_POST['type'],
            'options' => in_array($_POST['type'], ['select', 'radio', 'checkbox']) ? $_POST['options'] : null,
            'is_required' => isset($_POST['is_required']) ? 1 : 0,
            'default_value' => $_POST['default_value'] ?? null,
            'placeholder' => $_POST['placeholder'] ?? null,
            'validation_rules' => $_POST['validation_rules'] ?? null,
            'help_text' => $_POST['help_text'] ?? null,
            'position' => $_POST['position'] ?? 0,
            'status' => $_POST['status'] ?? 'active',
        ];

        return $data;
    }
}
