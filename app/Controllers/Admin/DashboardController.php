<?php

namespace App\Controllers\Admin;

use App\Models\Dashboard;
use App\Core\Plugin\Filter;

class DashboardController
{
    /**
     * Show the admin dashboard with dynamic data.
     */
    public function index()
    {
        // Get dashboard widgets from plugins
        // Widgets are now responsible for fetching their own data
        $widgets = Filter::apply('dashboard_widgets', []);

        // Sort widgets by order if available
        usort($widgets, function($a, $b) {
            return ($a['order'] ?? 10) <=> ($b['order'] ?? 10);
        });

        return view('main', 'dashboard', [
            'title' => 'داشبورد',
            'widgets' => $widgets
        ]);
    }

    /**
     * AJAX Endpoint for Chart Data
     * Note: This remains here for now as a shared endpoint, but ideally plugins should provide their own endpoints.
     * We will use a filter to allow plugins to hijack this response.
     */
    public function getChartData()
    {
        header('Content-Type: application/json');
        $type = $_GET['type'] ?? 'sales';
        $period = $_GET['period'] ?? 'week';

        // Default Data (Empty or Basic)
        $data = [];

        // Allow plugins to provide chart data
        // Filter name: dashboard_chart_data
        // Arguments: $data, $type, $period
        $data = Filter::apply('dashboard_chart_data', $data, $type, $period);

        // Fallback for when the Store plugin is NOT active but the request is made (shouldn't happen if UI is clean, but for safety)
        if (empty($data) && $type === 'sales') {
             // If store plugin is gone, we shouldn't really return store data, but we must return valid JSON structure to avoid JS errors if the JS is still running
             $data = ['labels' => [], 'amounts' => [], 'counts' => []];
        }

        echo json_encode($data);
        exit;
    }
}
