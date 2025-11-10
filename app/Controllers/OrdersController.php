<?php

namespace App\Controllers;

use App\Models\Order;

class OrdersController
{
    /**
     * Show a list of all orders.
     */
    public function index()
    {
        $orders = Order::all();

        return view('main', 'orders/index', [
            'title' => 'مدیریت سفارشات',
            'orders' => $orders
        ]);
    }

    /**
     * Show the details of a specific order.
     *
     * @param int $id
     */
    public function show($id)
    {
        $order = Order::find($id);
        if (!$order) {
            die('Order not found.');
        }

        return view('main', 'orders/show', [
            'title' => "جزئیات سفارش {$order['order_code']}",
            'order' => $order
        ]);
    }

    /**
     * Update the status of an order.
     *
     * @param int $id
     */
    public function updateStatus($id)
    {
        // Basic validation
        if (empty($_POST['status'])) {
            die('Status is required.');
        }

        Order::updateStatus($id, $_POST['status']);

        // Redirect back to the order details page
        header("Location: /orders/show/{$id}");
        exit();
    }
}
