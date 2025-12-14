<?php

namespace App\Controllers;

use App\Core\Template;

class SiteController
{
    public function index()
    {
        $view = new Template();
        echo $view->render('index', [
            'title' => 'Home',
            'page_title' => 'Home',
        ]);
    }
}
