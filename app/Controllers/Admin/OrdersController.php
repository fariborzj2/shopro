<?php

namespace App\Controllers\Admin;

use App\Models\Order;
use App\Core\Paginator;

class OrdersController
{
    /**
     * Display a listing of the orders.
     */
    public function index()
    {
        // Get the current page from the query string, default to 1 if not set
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = 15; // Number of items per page

        // Fetch paginated orders from the model
        $data = Order::findAll($page, $perPage);

        // Create a Paginator instance to generate pagination links
        $paginator = new Paginator($data['total'], $perPage, $page, '/admin/orders?page=(:num)');

        // Pass the orders and paginator object to the view
        return view('main', 'orders/index', [
            'title' => 'مدیریت سفارشات',
            'orders' => $data['orders'],
            'paginator' => $paginator
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * Note: Admin might not need to create orders manually, but stub is here.
     */
    public function create()
    {
        // Implementation for creating an order
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
        // Implementation for storing a new order
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $order = Order::find((int)$id);
        if (!$order) {
            // Or handle not found exception
            return http_response_code(404);
        }

        return view('main', 'orders/show', [
            'title' => 'جزئیات سفارش #' . $order->order_code,
            'order' => $order
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Implementation for editing an order
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id)
    {
        // Implementation for updating an order
    }

    /**
     * Update the order status and payment status.
     */
    public function updateStatus($id)
    {
        $order_status = $_POST['order_status'] ?? null;
        $payment_status = $_POST['payment_status'] ?? null;

        if ($order_status) {
            $validOrderStatuses = ['pending', 'completed', 'cancelled', 'phishing'];
            if (in_array($order_status, $validOrderStatuses)) {
                 Order::updateOrderStatus($id, $order_status);
            }
        }

        if ($payment_status) {
            $validPaymentStatuses = ['unpaid', 'paid', 'failed'];
             if (in_array($payment_status, $validPaymentStatuses)) {
                 Order::updatePaymentStatus($id, $payment_status);
            }
        }

        redirect_with_success(url('orders/show/' . $id), 'وضعیت سفارش با موفقیت تغییر کرد.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($id)
    {
        // Implementation for deleting an order
    }
}
