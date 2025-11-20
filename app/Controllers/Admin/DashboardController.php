<?php

namespace App\Controllers\Admin;

use App\Models\Dashboard;
use App\Models\Order;

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
        $recentOrders = Dashboard::getRecentOrders();

        return view('main', 'dashboard', [
            'title' => 'داشبورد',
            'kpis' => $kpis,
            'reports' => $reports,
            'salesChartData' => $salesChartData,
            'usersChartData' => $usersChartData,
            'recent_orders' => $recentOrders
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
