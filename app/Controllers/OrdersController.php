<?php

namespace App\Controllers;

use App\Models\Order;
use App\Models\CustomOrderField;

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
            redirect_back_with_error('Order not found.');
        }

        $allCustomFields = CustomOrderField::getAllFieldsById();
        $customFieldsData = json_decode($order['custom_fields_data'] ?? '[]', true);

        return view('main', 'orders/show', [
            'title' => "جزئیات سفارش {$order['order_code']}",
            'order' => $order,
            'allCustomFields' => $allCustomFields,
            'customFieldsData' => $customFieldsData
        ]);
    }

    /**
     * Update the status of an order.
     *
     * @param int $id
     */
    public function updateStatus($id)
    {
        $order = Order::find($id);
        if (!$order) {
            redirect_back_with_error('Order not found.');
        }

        // Basic validation
        if (empty($_POST['status'])) {
            redirect_back_with_error('Status is required.');
        }

        Order::updateStatus($id, $_POST['status']);

        // Redirect back to the order details page
        header("Location: /orders/show/{$id}");
        exit();
    }
}
