<?php

use App\Core\Plugin\Filter;
use App\Models\Dashboard;

require_once __DIR__ . '/helpers.php';

// Register Admin Menu Items
Filter::add('admin_menu_items', function($items) {
    $storeItems = [
        [
            'title' => 'محصولات',
            'icon' => 'box',
            'route' => '/admin/products',
            'permission' => 'products'
        ],
        [
            'title' => 'سفارشات',
            'icon' => 'orders',
            'route' => '/admin/orders',
            'permission' => 'orders'
        ],
        [
            'title' => 'دسته‌بندی‌ها',
            'icon' => 'grid',
            'route' => '/admin/categories',
            'permission' => 'categories'
        ],
        [
            'title' => 'فیلدهای سفارشی',
            'icon' => 'list',
            'route' => '/admin/custom-fields',
            'permission' => 'products'
        ],
        [
            'title' => 'نقد و بررسی‌ها',
            'icon' => 'message-square',
            'route' => '/admin/reviews',
            'permission' => 'products'
        ]
    ];

    // Insert after Dashboard (index 0)
    array_splice($items, 1, 0, $storeItems);

    return $items;
});

// Register Dashboard Widgets
Filter::add('dashboard_widgets', function($widgets) {
    // Fetch data required for the widget
    // Note: Models are expected to be available (autoloaded by core or plugin)
    // We assume Dashboard model is core. If Store has its own models, use them.
    // However, existing Dashboard logic used App\Models\Dashboard.
    // If we want to strictly decouple, we should move the Dashboard model logic here too,
    // but for now we reuse the existing model methods.

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
