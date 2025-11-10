<?php

namespace App\Controllers;

use App\Models\Product;
use App\Models\Category;

class ProductsController
{
    /**
     * Show a list of all products.
     */
    public function index()
    {
        $products = Product::all();
        return view('main', 'products/index', [
            'title' => 'مدیریت محصولات',
            'products' => $products
        ]);
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $categories = Category::all();
        return view('main', 'products/create', [
            'title' => 'افزودن محصول جدید',
            'categories' => $categories
        ]);
    }

    /**
     * Store a new product in the database.
     */
    public function store()
    {
        // Basic validation
        if (empty($_POST['name_fa']) || empty($_POST['price']) || empty($_POST['category_id'])) {
            die('Name, price, and category are required.');
        }

        Product::create([
            'category_id' => $_POST['category_id'],
            'name_fa' => $_POST['name_fa'],
            'name_en' => $_POST['name_en'],
            'price' => $_POST['price'],
            'old_price' => $_POST['old_price'],
            'status' => $_POST['status'],
            'position' => $_POST['position']
        ]);

        header('Location: /products');
        exit();
    }

    /**
     * Show the form for editing a specific product.
     *
     * @param int $id
     */
    public function edit($id)
    {
        $product = Product::find($id);
        if (!$product) {
            die('Product not found.');
        }

        $categories = Category::all();

        return view('main', 'products/edit', [
            'title' => 'ویرایش محصول',
            'product' => $product,
            'categories' => $categories
        ]);
    }

    /**
     * Update an existing product in the database.
     *
     * @param int $id
     */
    public function update($id)
    {
        // Basic validation
        if (empty($_POST['name_fa']) || empty($_POST['price']) || empty($_POST['category_id'])) {
            die('Name, price, and category are required.');
        }

        Product::update($id, [
            'category_id' => $_POST['category_id'],
            'name_fa' => $_POST['name_fa'],
            'name_en' => $_POST['name_en'],
            'price' => $_POST['price'],
            'old_price' => $_POST['old_price'],
            'status' => $_POST['status'],
            'position' => $_POST['position']
        ]);

        header('Location: /products');
        exit();
    }
}
