<?php

use App\Core\Plugin\Filter;
use App\Core\Hook;
use App\Models\Dashboard;

require_once __DIR__ . '/helpers.php';

// Register Frontend Hooks
Hook::add('home_products', function() {
    include __DIR__ . '/views/partials/home_products_widget.php';
});

// Register Admin Menu Items
Filter::add('admin_menu_items', function($items) {
    $storeItems = [
        [
            'label' => 'محصولات',
            'icon' => 'box',
            'url' => '/products',
            'permission' => 'products'
        ],
        [
            'label' => 'سفارشات',
            'icon' => 'orders',
            'url' => '/orders',
            'permission' => 'orders'
        ],
        [
            'label' => 'دسته‌بندی‌ها',
            'icon' => 'grid',
            'url' => '/categories',
            'permission' => 'categories'
        ],
        [
            'label' => 'فیلدهای سفارشی',
            'icon' => 'list',
            'url' => '/custom-fields',
            'permission' => 'products'
        ],
        [
            'label' => 'نظرات فروشگاه',
            'url' => '/reviews',
            'icon' => 'message',
            'permission' => 'reviews'
        ],
    ];

    // Insert after Dashboard (index 0)
    array_splice($items, 1, 0, $storeItems);

    return $items;
});

// Register Dashboard Widgets
Filter::add('dashboard_widgets', function($widgets) {
    // Fetch data required for the widget
    $kpis = Dashboard::getKpis();
    $reports = Dashboard::getReports();
    $salesChartData = Dashboard::getSalesChartData('week');
    $usersChartData = Dashboard::getUsersChartData('week');

    // Load the view content
    ob_start();
    // Make variables available to the view
    extract([
        'kpis' => $kpis,
        'reports' => $reports,
        'salesChartData' => $salesChartData,
        'usersChartData' => $usersChartData
    ]);

    include __DIR__ . '/views/dashboard_widget.php';
    $content = ob_get_clean();

    $widgets[] = [
        'id' => 'store_stats',
        'title' => 'آمار فروشگاه',
        'content' => $content,
        'order' => 10
    ];

    return $widgets;
});

// Handle AJAX Chart Data
Filter::add('dashboard_chart_data', function($data, $type, $period) {
    // Only handle types we know about
    if ($type === 'sales') {
        return Dashboard::getSalesChartData($period);
    } elseif ($type === 'users') {
        return Dashboard::getUsersChartData($period);
    }
    return $data;
});
