<?php

namespace App\Models;

use App\Core\Database;
use PDO;
use DateTime;
use DateInterval;
use DatePeriod;

class Dashboard
{
    /**
     * Get key performance indicators (KPIs) for the dashboard.
     *
     * @return array
     */
    public static function getKpis()
    {
        $pdo = Database::getConnection();

        $today = date('Y-m-d');
        $month_start = date('Y-m-01');

        // Sales today
        $stmt = $pdo->prepare("SELECT SUM(amount) FROM orders WHERE DATE(order_time) = ?");
        $stmt->execute([$today]);
        $sales_today = $stmt->fetchColumn() ?: 0;

        // Orders today
        $stmt = $pdo->prepare("SELECT COUNT(id) FROM orders WHERE DATE(order_time) = ?");
        $stmt->execute([$today]);
        $orders_today = $stmt->fetchColumn() ?: 0;

        // New users today
        $stmt = $pdo->prepare("SELECT COUNT(id) FROM users WHERE DATE(created_at) = ?");
        $stmt->execute([$today]);
        $new_users_today = $stmt->fetchColumn() ?: 0;

        // Orders this month
        $stmt = $pdo->prepare("SELECT COUNT(id) FROM orders WHERE order_time >= ?");
        $stmt->execute([$month_start]);
        $orders_this_month = $stmt->fetchColumn() ?: 0;

        return [
            'sales_today' => number_format($sales_today),
            'orders_today' => $orders_today,
            'new_users_today' => $new_users_today,
            'orders_this_month' => $orders_this_month,
        ];
    }

    /**
     * Get sales data for the last 7 days.
     *
     * @return array
     */
    public static function getSalesChartData()
    {
        $pdo = Database::getConnection();
        $start_date = date('Y-m-d', strtotime('-6 days'));

        $sql = "SELECT DATE(order_time) as date, SUM(amount) as total_sales
                FROM orders
                WHERE order_time >= :start_date
                GROUP BY DATE(order_time)
                ORDER BY date ASC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['start_date' => $start_date]);
        $results = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

        // Create a complete list of dates for the last 7 days to fill in gaps
        $labels = [];
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $labels[] = date('m/d', strtotime($date));
            $data[] = (float) ($results[$date] ?? 0);
        }

        return ['labels' => $labels, 'data' => $data];
    }

    /**
     * Get new user registration data for the current month.
     *
     * @return array
     */
    public static function getUsersChartData()
    {
        $pdo = Database::getConnection();
        $month_start = date('Y-m-01');

        $sql = "SELECT DATE(created_at) as date, COUNT(id) as total_users
                FROM users
                WHERE created_at >= :month_start
                GROUP BY DATE(created_at)
                ORDER BY date ASC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['month_start' => $month_start]);
        $results = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

        $labels = [];
        $data = [];
        $days_in_month = date('t');
        for ($i = 1; $i <= $days_in_month; $i++) {
            $date = date('Y-m-') . str_pad($i, 2, '0', STR_PAD_LEFT);
            $labels[] = str_pad($i, 2, '0', STR_PAD_LEFT);
            $data[] = (int) ($results[$date] ?? 0);
        }

        return ['labels' => $labels, 'data' => $data];
    }

    /**
     * Get the 5 most recent orders.
     *
     * @return array
     */
    public static function getRecentOrders()
    {
        $sql = "SELECT o.id, o.order_code, o.amount, o.order_status, o.payment_status, u.name as user_name
                FROM orders o
                LEFT JOIN users u ON o.user_id = u.id
                ORDER BY o.order_time DESC
                LIMIT 5";

        $stmt = Database::query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
