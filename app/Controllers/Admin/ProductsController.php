<?php

namespace App\Controllers\Admin;

use App\Models\Product;
use App\Models\Category;
use App\Models\Setting;
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
        $settings = Setting::getAll();

        return view('main', 'products/create', [
            'title' => 'افزودن محصول جدید',
            'categories' => $categories,
            'auto_update_prices' => (bool)($settings['auto_update_prices'] ?? false),
            'dollar_exchange_rate' => (float)($settings['dollar_exchange_rate'] ?? 50000)
        ]);
    }

    /**
     * Store a new product in the database.
     */
    public function store()
    {
        $settings = Setting::getAll();
        $auto_update_prices = (bool)($settings['auto_update_prices'] ?? false);
        $dollar_exchange_rate = (float)($settings['dollar_exchange_rate'] ?? 50000);

        $data = $_POST;

        // --- Validation ---
        $errors = [];
        if (empty($data['name_fa'])) $errors[] = 'نام فارسی محصول الزامی است.';
        if (empty($data['category_id'])) $errors[] = 'انتخاب دسته بندی الزامی است.';

        $dollar_price = !empty($data['dollar_price']) ? (float)$data['dollar_price'] : null;
        if ($dollar_price !== null && $dollar_price < 0) {
             $errors[] = 'قیمت دلاری نمی‌تواند منفی باشد.';
        }

        if (empty($data['price']) && $dollar_price === null) {
            $errors[] = 'قیمت تومانی یا قیمت دلاری باید وارد شود.';
        }
        if (!empty($data['price']) && !is_numeric($data['price'])) {
            $errors[] = 'قیمت تومانی باید عدد باشد.';
        }

        if (!empty($errors)) {
            return redirect_back_with_errors($errors);
        }

        // --- Price Calculation ---
        if ($auto_update_prices && $dollar_price !== null) {
            $data['price'] = round($dollar_price * $dollar_exchange_rate);
        }
        $data['dollar_price'] = $dollar_price;


        Product::create($data);

        header('Location: /admin/products');
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
        $settings = Setting::getAll();

        return view('main', 'products/edit', [
            'title' => 'ویرایش محصول',
            'product' => $product,
            'categories' => $categories,
            'auto_update_prices' => (bool)($settings['auto_update_prices'] ?? false),
            'dollar_exchange_rate' => (float)($settings['dollar_exchange_rate'] ?? 50000)
        ]);
    }

    /**
     * Update an existing product in the database.
     *
     * @param int $id
     */
    public function update($id)
    {
        $product = Product::find($id);
        if (!$product) {
            redirect_back_with_error('Product not found.');
        }

        $settings = Setting::getAll();
        $auto_update_prices = (bool)($settings['auto_update_prices'] ?? false);
        $dollar_exchange_rate = (float)($settings['dollar_exchange_rate'] ?? 50000);

        $data = $_POST;

        // --- Validation ---
        $errors = [];
        if (empty($data['name_fa'])) $errors[] = 'نام فارسی محصول الزامی است.';
        if (empty($data['category_id'])) $errors[] = 'انتخاب دسته بندی الزامی است.';

        $dollar_price = !empty($data['dollar_price']) ? (float)$data['dollar_price'] : null;
        if ($dollar_price !== null && $dollar_price < 0) {
             $errors[] = 'قیمت دلاری نمی‌تواند منفی باشد.';
        }

        if (empty($data['price']) && $dollar_price === null) {
            $errors[] = 'قیمت تومانی یا قیمت دلاری باید وارد شود.';
        }
        if (!empty($data['price']) && !is_numeric($data['price'])) {
            $errors[] = 'قیمت تومانی باید عدد باشد.';
        }

        if (!empty($errors)) {
            return redirect_back_with_errors($errors);
        }

        // --- Price Calculation ---
        if ($auto_update_prices && $dollar_price !== null) {
            $data['price'] = round($dollar_price * $dollar_exchange_rate);
        }
        $data['dollar_price'] = $dollar_price;

        Product::update($id, $data);

        header('Location: /admin/products');
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
