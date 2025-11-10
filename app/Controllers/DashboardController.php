<?php

namespace App\Controllers;

class DashboardController
{
    /**
     * Show the admin dashboard.
     */
    public function index()
    {
        return view('main', 'dashboard', [
            'title' => 'داشبورد'
        ]);
    }
}
