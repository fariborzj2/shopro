<?php

namespace App\Controllers\Admin;

use App\Models\Product;
use App\Models\Category;
use App\Core\Paginator;

class ProductsController
{
    const ITEMS_PER_PAGE = 15;

    /**
     * Show a paginated list of all products.
     */
    public function index()
    {
        $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $total_products = Product::count();

        $paginator = new Paginator($total_products, self::ITEMS_PER_PAGE, $current_page, '/products');

        $products = Product::paginated(self::ITEMS_PER_PAGE, $paginator->getOffset());

        return view('main', 'products/index', [
            'title' => 'مدیریت محصولات',
            'products' => $products,
            'paginator' => $paginator
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
            redirect_back_with_error('Name, price, and category are required.');
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
            redirect_back_with_error('Product not found.');
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
        $product = Product::find($id);
        if (!$product) {
            redirect_back_with_error('Product not found.');
        }
        if (empty($_POST['name_fa']) || empty($_POST['price']) || empty($_POST['category_id'])) {
            redirect_back_with_error('Name, price, and category are required.');
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

    /**
     * Delete a product.
     *
     * @param int $id
     */
    public function delete($id)
    {
        Product::delete($id);
        header('Location: /products');
        exit();
    }

    /**
     * Handle the reordering of products.
     */
    public function reorder()
    {
        header('Content-Type: application/json');

        $input = json_decode(file_get_contents('php://input'), true);
        $ids = $input['ids'] ?? [];

        if (empty($ids) || !is_array($ids)) {
            echo json_encode(['success' => false, 'message' => 'Invalid data.']);
            http_response_code(400);
            return;
        }

        if (Product::updateOrder($ids)) {
            echo json_encode(['success' => true, 'message' => 'Order updated successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update order.']);
            http_response_code(500);
        }
    }
}
