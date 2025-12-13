<?php

use App\Core\Plugin\PluginManager;

// Verify Store Plugin is Active before loading its routes
if (!PluginManager::isActive('store')) {
    return;
}

// ----------------------------------------------------------------------
// Storefront Routes (Shop)
// ----------------------------------------------------------------------

// Home & Product Lists
$router->get('/', '\Store\Controllers\StorefrontController@index');
$router->get('/category/{slug}', '\Store\Controllers\StorefrontController@category');
$router->get('/product/{id}', '\Store\Controllers\StorefrontController@product');

// Search
$router->get('/search', '\Store\Controllers\StorefrontController@search');

// Cart & Checkout (API style for SPA/Alpine)
$router->get('/api/cart', '\Store\Controllers\StorefrontController@getCart');
$router->post('/api/cart/add', '\Store\Controllers\StorefrontController@addToCart');
$router->post('/api/cart/remove', '\Store\Controllers\StorefrontController@removeFromCart');
$router->post('/api/cart/update', '\Store\Controllers\StorefrontController@updateCart');

// Payment
$router->post('/api/payment/start', '\Store\Controllers\PaymentController@startPayment');
$router->get('/payment/callback', '\Store\Controllers\PaymentController@callback');
$router->get('/payment/receipt/{id}', '\Store\Controllers\PaymentController@receipt');

// Reviews
$router->post('/api/reviews/store', '\Store\Controllers\ReviewsController@store');

// Custom Fields
$router->get('/api/product-details/{id}', '\Store\Controllers\Admin\ApiController@productDetails');

// User Dashboard
$router->get('/dashboard/orders', '\Store\Controllers\UserDashboardController@orders');
$router->get('/dashboard/orders/{id}', '\Store\Controllers\UserDashboardController@orderDetails');


// ----------------------------------------------------------------------
// Admin Routes for Store
// ----------------------------------------------------------------------

// Products
$router->get('/admin/products', '\Store\Controllers\Admin\ProductsController@index');
$router->get('/admin/products/create', '\Store\Controllers\Admin\ProductsController@create');
$router->post('/admin/products/store', '\Store\Controllers\Admin\ProductsController@store');
$router->get('/admin/products/edit/{id}', '\Store\Controllers\Admin\ProductsController@edit');
$router->post('/admin/products/update/{id}', '\Store\Controllers\Admin\ProductsController@update');
$router->post('/admin/products/delete/{id}', '\Store\Controllers\Admin\ProductsController@delete');
$router->post('/admin/products/delete-image/{id}', '\Store\Controllers\Admin\ProductsController@deleteImage');
$router->post('/admin/products/reorder', '\Store\Controllers\Admin\ProductsController@reorder');

// Categories (Shop)
$router->get('/admin/categories', '\Store\Controllers\Admin\CategoriesController@index');
$router->get('/admin/categories/create', '\Store\Controllers\Admin\CategoriesController@create');
$router->post('/admin/categories/store', '\Store\Controllers\Admin\CategoriesController@store');
$router->get('/admin/categories/edit/{id}', '\Store\Controllers\Admin\CategoriesController@edit');
$router->post('/admin/categories/update/{id}', '\Store\Controllers\Admin\CategoriesController@update');
$router->post('/admin/categories/delete/{id}', '\Store\Controllers\Admin\CategoriesController@delete');
$router->post('/admin/categories/delete-image/{id}', '\Store\Controllers\Admin\CategoriesController@deleteImage');

// Orders
$router->get('/admin/orders', '\Store\Controllers\Admin\OrdersController@index');
$router->get('/admin/orders/show/{id}', '\Store\Controllers\Admin\OrdersController@show');
$router->post('/admin/orders/update-status/{id}', '\Store\Controllers\Admin\OrdersController@updateStatus');

// Reviews Management
$router->get('/admin/reviews', '\Store\Controllers\Admin\ReviewsController@index');
$router->post('/admin/reviews/delete/{id}', '\Store\Controllers\Admin\ReviewsController@delete');
$router->post('/admin/reviews/status/{id}', '\Store\Controllers\Admin\ReviewsController@updateStatus');

// Custom Order Fields
$router->get('/admin/custom-fields', '\Store\Controllers\Admin\CustomOrderFieldsController@index');
$router->get('/admin/custom-fields/create', '\Store\Controllers\Admin\CustomOrderFieldsController@create');
$router->post('/admin/custom-fields/store', '\Store\Controllers\Admin\CustomOrderFieldsController@store');
$router->get('/admin/custom-fields/edit/{id}', '\Store\Controllers\Admin\CustomOrderFieldsController@edit');
$router->post('/admin/custom-fields/update/{id}', '\Store\Controllers\Admin\CustomOrderFieldsController@update');
$router->post('/admin/custom-fields/delete/{id}', '\Store\Controllers\Admin\CustomOrderFieldsController@delete');
