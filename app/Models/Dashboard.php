<?php

namespace App\Models;

use App\Core\Database;
use PDO;

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

        // Sales today (Exclude cancelled orders)
        $stmt = $pdo->prepare("SELECT SUM(amount) FROM orders WHERE DATE(order_time) = ? AND payment_status = 'paid' AND order_status != 'cancelled'");
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
     * Get Report Metrics.
     */
    public static function getReports()
    {
        $pdo = Database::getConnection();
        $today = date('Y-m-d');

        // Total Users
        $stmt = $pdo->query("SELECT COUNT(id) FROM users");
        $total_users = $stmt->fetchColumn() ?: 0;

        // Total Completed Orders
        $stmt = $pdo->query("SELECT COUNT(id) FROM orders WHERE order_status = 'completed'");
        $total_completed_orders = $stmt->fetchColumn() ?: 0;

        // Total Sales Amount (Paid and not Cancelled)
        $stmt = $pdo->query("SELECT SUM(amount) FROM orders WHERE payment_status = 'paid' AND order_status != 'cancelled'");
        $total_sales = $stmt->fetchColumn() ?: 0;

        // Failed Orders Today
        $stmt = $pdo->prepare("SELECT COUNT(id) FROM orders WHERE payment_status = 'failed' AND DATE(order_time) = ?");
        $stmt->execute([$today]);
        $failed_orders_today = $stmt->fetchColumn() ?: 0;

        return [
            'total_users' => $total_users,
            'total_completed_orders' => $total_completed_orders,
            'total_sales' => $total_sales,
            'failed_orders_today' => $failed_orders_today,
        ];
    }

    /**
     * Get sales data.
     *
     * @param string $period 'week', 'month', 'year'
     * @return array
     */
    public static function getSalesChartData($period = 'week')
    {
        $pdo = Database::getConnection();
        $dateFormat = '%Y-%m-%d';
        $groupBy = 'DATE(order_time)';
        $startDate = date('Y-m-d', strtotime('-6 days'));
        $intervalStep = '1 day';
        $intervalFormat = 'Y-m-d';
        $loopCount = 6;

        if ($period === 'month') {
            // Last 30 days
            $startDate = date('Y-m-d', strtotime('-29 days'));
            $loopCount = 29;
        } elseif ($period === 'year') {
            // Last 12 months
            $startDate = date('Y-m-01', strtotime('-11 months'));
            $dateFormat = '%Y-%m';
            $groupBy = 'DATE_FORMAT(order_time, "%Y-%m")';
            $intervalStep = '1 month';
            $intervalFormat = 'Y-m';
            $loopCount = 11;
        }

        $sql = "SELECT $groupBy as date, SUM(amount) as total_sales, COUNT(id) as total_orders
                FROM orders
                WHERE order_time >= :start_date AND payment_status = 'paid'
                GROUP BY date
                ORDER BY date ASC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['start_date' => $startDate]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Re-key by date for easy lookup
        $dataMap = [];
        foreach ($results as $row) {
            $dataMap[$row['date']] = $row;
        }

        $labels = [];
        $amountData = [];
        $countData = [];

        for ($i = $loopCount; $i >= 0; $i--) {
            $date = date($intervalFormat, strtotime("-$i " . (strpos($intervalStep, 'month') !== false ? 'months' : 'days')));

            if ($period === 'year') {
                // Logic for year to match loop correctly if "today" is mid-month
                 $date = date('Y-m', strtotime("-$i months"));
            }

            // Jalali Label
            if ($period === 'year') {
                 $parts = explode('-', $date);
                 $jDate = \jdate('F Y', mktime(0, 0, 0, $parts[1], 1, $parts[0]));
            } else {
                 $jDate = \jdate('Y/m/d', strtotime($date));
            }
            $labels[] = $jDate;

            $row = $dataMap[$date] ?? null;
            $amountData[] = (float) ($row['total_sales'] ?? 0);
            $countData[] = (int) ($row['total_orders'] ?? 0);
        }

        return [
            'labels' => $labels,
            'amounts' => $amountData,
            'counts' => $countData
        ];
    }

    /**
     * Get user registration data.
     *
     * @param string $period 'week', 'month', 'year'
     * @return array
     */
    public static function getUsersChartData($period = 'week')
    {
        $pdo = Database::getConnection();
        $dateFormat = '%Y-%m-%d';
        $groupBy = 'DATE(created_at)';
        $startDate = date('Y-m-d', strtotime('-6 days'));
        $intervalStep = '1 day';
        $intervalFormat = 'Y-m-d';
        $loopCount = 6;

        if ($period === 'month') {
            // Last 30 days
            $startDate = date('Y-m-d', strtotime('-29 days'));
            $loopCount = 29;
        } elseif ($period === 'year') {
            // Last 12 months
            $startDate = date('Y-m-01', strtotime('-11 months'));
            $dateFormat = '%Y-%m';
            $groupBy = 'DATE_FORMAT(created_at, "%Y-%m")';
            $intervalStep = '1 month';
            $intervalFormat = 'Y-m';
            $loopCount = 11;
        }

        $sql = "SELECT $groupBy as date, COUNT(id) as total_users
                FROM users
                WHERE created_at >= :start_date
                GROUP BY date
                ORDER BY date ASC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['start_date' => $startDate]);
        $results = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

        $labels = [];
        $data = [];

        for ($i = $loopCount; $i >= 0; $i--) {
            $date = date($intervalFormat, strtotime("-$i " . (strpos($intervalStep, 'month') !== false ? 'months' : 'days')));

            if ($period === 'year') {
                 $date = date('Y-m', strtotime("-$i months"));
            }

            // Jalali Label
            if ($period === 'year') {
                 $parts = explode('-', $date);
                 $jDate = \jdate('F Y', mktime(0, 0, 0, $parts[1], 1, $parts[0]));
            } else {
                 $jDate = \jdate('Y/m/d', strtotime($date));
            }
            $labels[] = $jDate;

            $data[] = (int) ($results[$date] ?? 0);
        }

        // Get total users for current month
        $thisMonthStart = date('Y-m-01');
        $stmtTotal = $pdo->prepare("SELECT COUNT(id) FROM users WHERE created_at >= ?");
        $stmtTotal->execute([$thisMonthStart]);
        $totalThisMonth = $stmtTotal->fetchColumn();

        return [
            'labels' => $labels,
            'data' => $data,
            'total_this_month' => $totalThisMonth
        ];
    }

}
