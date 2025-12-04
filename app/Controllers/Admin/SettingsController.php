<?php

namespace App\Controllers\Admin;

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
        $cache = \App\Core\Cache::getInstance();
        $cacheStats = $cache->getStats();
        $cacheDriver = $cache->getDriverName();

        return view('main', 'settings/index', [
            'title' => 'تنظیمات سایت',
            'settings' => $settings,
            'blog_categories' => $blog_categories,
            'cacheStats' => $cacheStats,
            'cacheDriver' => $cacheDriver
        ]);
    }

    /**
     * Clear the entire cache.
     */
    public function clearCache()
    {
        try {
            \App\Core\Cache::getInstance()->flush();
            header('Location: /admin/settings?success=cache_cleared');
        } catch (\Exception $e) {
            header('Location: /admin/settings?error=cache_failed');
        }
        exit();
    }

    /**
     * Update the settings.
     */
    public function update()
    {
        $data = $_POST;

        // --- Validation ---
        $errors = [];
        if (!isset($data['dollar_exchange_rate']) || !is_numeric($data['dollar_exchange_rate']) || $data['dollar_exchange_rate'] < 0) {
            $errors[] = 'نرخ دلار باید یک عدد معتبر و غیرمنفی باشد.';
        }

        if (!empty($errors)) {
            // In a real app, you'd use flash messages to show errors.
            // For simplicity, redirecting with a generic error.
            header('Location: /admin/settings?error=validation');
            exit();
        }

        // --- Handle checkbox ---
        // If the checkbox is not checked, it won't be in $_POST.
        // So we explicitly set it to '0' to save it in the database.
        if (!isset($data['auto_update_prices'])) {
            $data['auto_update_prices'] = '0';
        }

        // --- Auto-update product prices if enabled ---
        $settings = Setting::getAll();
        $should_update_prices = isset($data['auto_update_prices']) && $data['auto_update_prices'] === '1';
        $old_rate = $settings['dollar_exchange_rate'] ?? null;
        $new_rate = $data['dollar_exchange_rate'];

        // Only update if the rate has changed and the feature is enabled
        if ($should_update_prices && $old_rate != $new_rate) {
            \App\Models\Product::updateAllTomanPrices($new_rate);
        }

        if (Setting::updateBatch($data)) {
            header('Location: /admin/settings?success=1');
        } else {
            header('Location: /admin/settings?error=1');
        }
        exit();
    }
}
