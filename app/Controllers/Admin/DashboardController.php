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
        $kpis = Dashboard::getKpis();
        $reports = Dashboard::getReports();
        $salesChartData = Dashboard::getSalesChartData('week');
        $usersChartData = Dashboard::getUsersChartData('week');

        // Get dashboard widgets from plugins
        $widgets = Filter::apply('dashboard_widgets', []);

        return view('main', 'dashboard', [
            'title' => 'داشبورد',
            'kpis' => $kpis,
            'reports' => $reports,
            'salesChartData' => $salesChartData,
            'usersChartData' => $usersChartData,
            'widgets' => $widgets
        ]);
    }

    /**
     * AJAX Endpoint for Chart Data
     */
    public function getChartData()
    {
        header('Content-Type: application/json');
        $type = $_GET['type'] ?? 'sales';
        $period = $_GET['period'] ?? 'week';

        if ($type === 'sales') {
            echo json_encode(Dashboard::getSalesChartData($period));
        } else {
            echo json_encode(Dashboard::getUsersChartData($period));
        }
        exit;
    }
}
