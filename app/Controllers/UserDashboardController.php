<?php

namespace App\Controllers;

use App\Models\Order;
use App\Models\Transaction;
use App\Core\Template;

class UserDashboardController
{
    private $template;

    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        // Protect all dashboard routes
        if (!isset($_SESSION['user_id'])) {
            header('Location: /'); // Redirect home if not logged in
            exit();
        }
        $this->template = new Template(__DIR__ . '/../../storefront/templates');
    }

    public function orders()
    {
        $orders = Order::findAllBy('user_id', $_SESSION['user_id']);

        echo $this->template->render('dashboard_orders', [
            'pageTitle' => 'تاریخچه سفارشات',
            'orders' => $orders
        ]);
    }

    public function orderDetails($id)
    {
        $order = Order::find($id);

        if (!$order || $order->user_id != $_SESSION['user_id']) {
            http_response_code(404);
            echo "سفارش یافت نشد.";
            return;
        }

        $transaction = Transaction::findBy('order_id', $id);

        echo $this->template->render('dashboard_order_details', [
            'pageTitle' => 'جزئیات سفارش #' . $order->order_code,
            'order' => $order,
            'transaction' => $transaction
        ]);
    }
}
