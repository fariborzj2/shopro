<?php

namespace AiNews\Controllers;

use AiNews\Models\Source;
use App\Core\Request;

class SourceController extends BaseController {
    public function index() {
        $sources = Source::all();
        $this->view('sources/index', ['sources' => $sources], 'مدیریت منابع محتوا');
    }

    public function create() {
        $this->view('sources/form', ['source' => null], 'افزودن منبع جدید');
    }

    public function store() {
        $data = Request::all();
        Source::create($data);
        header('Location: /admin/ai-news/sources');
    }

    public function edit($id) {
        $source = Source::find($id);
        $this->view('sources/form', ['source' => $source], 'ویرایش منبع');
    }

    public function update($id) {
        $data = Request::all();
        Source::update($id, $data);
        header('Location: /admin/ai-news/sources');
    }

    public function delete($id) {
        Source::delete($id);
        header('Location: /admin/ai-news/sources');
    }
}
