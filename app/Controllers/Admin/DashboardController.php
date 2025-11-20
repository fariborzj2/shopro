<?php

namespace App\Controllers\Admin;

use App\Models\Dashboard;
use App\Models\Order; // For recent orders

class DashboardController
{
    /**
     * Show the admin dashboard with dynamic data.
     */
    public function index()
    {
        $kpis = Dashboard::getKpis();
        $salesChartData = Dashboard::getSalesChartData();
        $usersChartData = Dashboard::getUsersChartData();
        $recentOrders = Dashboard::getRecentOrders(); // Fetch recent orders

        return view('main', 'dashboard', [
            'title' => 'داشبورد',
            'kpis' => $kpis,
            'salesChartData' => $salesChartData, // Pass raw array for view logic
            'usersChartData' => $usersChartData, // Pass raw array for view logic
            'recent_orders' => $recentOrders // Pass to view
        ]);
    }
}
