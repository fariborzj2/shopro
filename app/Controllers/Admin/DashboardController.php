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

        // Prepare data for Chart.js
        $sales_labels = array_map(fn($item) => $item['date'] ?? 'N/A', $salesChartData);
        $sales_values = array_map(fn($item) => $item['total'] ?? 0, $salesChartData);

        $users_labels = array_map(fn($item) => "Day " . ($item['day'] ?? 'N/A'), $usersChartData);
        $users_values = array_map(fn($item) => $item['count'] ?? 0, $usersChartData);

        return view('main', 'dashboard', [
            'title' => 'داشبورد',
            'kpis' => $kpis,
            'sales_chart' => [
                'labels' => json_encode($sales_labels),
                'values' => json_encode($sales_values)
            ],
            'users_chart' => [
                'labels' => json_encode($users_labels),
                'values' => json_encode($users_values)
            ],
            'recent_orders' => $recentOrders // Pass to view
        ]);
    }
}
