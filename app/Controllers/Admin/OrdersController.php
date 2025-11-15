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

        // Pass the orders and pagination HTML to the view
        return view('main', 'orders/index', [
            'title' => 'مدیریت سفارشات',
            'orders' => $data['orders'],
            'pagination' => $paginator->toHtml()
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
        // Implementation for showing a single order
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
     * Remove the specified resource from storage.
     */
    public function delete($id)
    {
        // Implementation for deleting an order
    }
}
