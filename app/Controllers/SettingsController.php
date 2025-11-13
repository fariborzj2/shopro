<?php

namespace App\Controllers;

use App\Models\Setting;

class SettingsController
{
    /**
     * Show the settings page.
     */
    public function index()
    {
        $settings = Setting::getAll();
        $blog_categories = \App\Models\BlogCategory::all();

        return view('main', 'settings/index', [
            'title' => 'تنظیمات سایت',
            'settings' => $settings,
            'blog_categories' => $blog_categories
        ]);
    }

    /**
     * Update the settings.
     */
    public function update()
    {
        // We can add validation and sanitation here in a real application.
        $data = $_POST;

        if (Setting::updateBatch($data)) {
            // Redirect back with a success message.
            // Using a query param for simplicity; flash messages would be better.
            header('Location: /settings?success=1');
        } else {
            // Redirect back with an error message.
            header('Location: /settings?error=1');
        }
        exit();
    }
}
