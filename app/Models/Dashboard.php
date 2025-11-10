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

        $labels = [];
        $data = [];
        $today = new DateTime();
        $period = new DatePeriod(
            (new DateTime())->sub(new DateInterval('P6D')),
            new DateInterval('P1D'),
            (new DateTime())->add(new DateInterval('P1D'))
        );

        foreach ($period as $date) {
            $formatted_date = $date->format('Y-m-d');
            $labels[] = $date->format('m/d');

            $stmt = $pdo->prepare("SELECT SUM(amount) FROM orders WHERE DATE(order_time) = ?");
            $stmt->execute([$formatted_date]);
            $daily_total = $stmt->fetchColumn() ?: 0;
            $data[] = $daily_total;
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

        $labels = [];
        $data = [];
        $month_start = new DateTime('first day of this month');
        $month_end = new DateTime('last day of this month');

        $period = new DatePeriod($month_start, new DateInterval('P1D'), $month_end->add(new DateInterval('P1D')));

        foreach ($period as $date) {
            $formatted_date = $date->format('Y-m-d');
            $labels[] = $date->format('d');

            $stmt = $pdo->prepare("SELECT COUNT(id) FROM users WHERE DATE(created_at) = ?");
            $stmt->execute([$formatted_date]);
            $daily_count = $stmt->fetchColumn() ?: 0;
            $data[] = $daily_count;
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
        $sql = "SELECT o.id, o.order_code, o.amount, o.status, u.name as user_name
                FROM orders o
                LEFT JOIN users u ON o.user_id = u.id
                ORDER BY o.order_time DESC
                LIMIT 5";

        $stmt = Database::query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
