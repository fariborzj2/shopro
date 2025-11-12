<?php

require_once __DIR__ . '/../app/Core/Template.php';
require_once __DIR__ . '/../app/Core/Database.php';
require_once __DIR__ . '/../app/Models/Category.php';
require_once __DIR__ . '/../app/Models/Product.php';


use App\Core\Template;
use App\Models\Category;
use App\Models\Product;

$template = new Template();

// Fetch data from models
$categories = Category::getActiveCategoriesForStore();
$products = Product::getActiveProductsForStore();

// Pass data to the template via JavaScript
$data = [
    'categories' => $categories,
    'products' => $products
];

$template->assign('title', 'صفحه اصلی - فروشگاه مدرن');
$template->assign('store_data', json_encode($data));

echo $template->render('index.tpl');
