<?php

// app/routes.php

// ----------------------------------------------------------------------
// Storefront Routes
// ----------------------------------------------------------------------
$router->get('/', 'StorefrontController@home');
$router->get('/page/{slug}', 'StorefrontController@page');
$router->get('/{slug}', 'StorefrontController@category');

// Blog Routes
$router->get('/blog', 'BlogController@index');
$router->get('/blog/tags', 'BlogController@tags');
$router->get('/blog/tags/{slug}', 'BlogController@showTag');
$router->get('/blog/{slug}', 'BlogController@category');
$router->get('/blog/{category}/{slug}', 'BlogController@show');
$router->post('/blog/comments/store', 'BlogController@storeComment');


// ----------------------------------------------------------------------
// API Routes
// ----------------------------------------------------------------------
$router->get('/api/product-details/{id}', 'ApiController@productDetails');
$router->post('/api/auth/send-otp', 'AuthController@sendOtp');
$router->post('/api/auth/verify-otp', 'AuthController@verifyOtp');
$router->get('/logout', 'AuthController@logout');
$router->post('/api/payment/start', 'PaymentController@startPayment');
$router->post('/reviews/store', 'ReviewsController@store');


// Payment Gateway Callback
// ----------------------------------------------------------------------
$router->get('/payment/callback', 'PaymentController@verifyPayment');
$router->post('/payment/callback', 'PaymentController@verifyPayment');


// User Dashboard
// ----------------------------------------------------------------------
$router->get('/dashboard/orders', 'UserDashboardController@orders');
$router->get('/dashboard/orders/{id}', 'UserDashboardController@orderDetails');


// ----------------------------------------------------------------------
// Admin Panel Routes
// ----------------------------------------------------------------------

// Auth
$router->get('/admin/login', 'Admin\LoginController@index');
$router->post('/admin/login', 'Admin\LoginController@login');
$router->get('/admin/logout', 'Admin\LoginController@logout');

// Dashboard
$router->get('/admin', 'Admin\DashboardController@index');
$router->get('/admin/dashboard', 'Admin\DashboardController@index');
$router->get('/admin/dashboard/chart-data', 'Admin\DashboardController@getChartData');

// Users
$router->get('/admin/users', 'Admin\UsersController@index');
$router->get('/admin/users/create', 'Admin\UsersController@create');
$router->post('/admin/users/store', 'Admin\UsersController@store');
$router->get('/admin/users/edit/{id}', 'Admin\UsersController@edit');
$router->post('/admin/users/update/{id}', 'Admin\UsersController@update');
$router->post('/admin/users/delete/{id}', 'Admin\UsersController@delete');


// Categories
$router->get('/admin/categories', 'Admin\CategoriesController@index');
$router->get('/admin/categories/create', 'Admin\CategoriesController@create');
$router->post('/admin/categories/store', 'Admin\CategoriesController@store');
$router->get('/admin/categories/edit/{id}', 'Admin\CategoriesController@edit');
$router->post('/admin/categories/update/{id}', 'Admin\CategoriesController@update');
$router->post('/admin/categories/delete/{id}', 'Admin\CategoriesController@delete');
$router->post('/admin/categories/reorder', 'Admin\CategoriesController@reorder');


// Products
$router->get('/admin/products', 'Admin\ProductsController@index');
$router->get('/admin/products/create', 'Admin\ProductsController@create');
$router->post('/admin/products/store', 'Admin\ProductsController@store');
$router->get('/admin/products/edit/{id}', 'Admin\ProductsController@edit');
$router->post('/admin/products/update/{id}', 'Admin\ProductsController@update');
$router->post('/admin/products/delete/{id}', 'Admin\ProductsController@delete');
$router->post('/admin/products/reorder', 'Admin\ProductsController@reorder');


// Orders
$router->get('/admin/orders', 'Admin\OrdersController@index');
$router->get('/admin/orders/show/{id}', 'Admin\OrdersController@show');
$router->post('/admin/orders/update_status/{id}', 'Admin\OrdersController@updateStatus');


// Admins
$router->get('/admin/admins', 'Admin\AdminsController@index');
$router->get('/admin/admins/create', 'Admin\AdminsController@create');
$router->post('/admin/admins/store', 'Admin\AdminsController@store');
$router->get('/admin/admins/edit/{id}', 'Admin\AdminsController@edit');
$router->post('/admin/admins/update/{id}', 'Admin\AdminsController@update');
$router->post('/admin/admins/delete/{id}', 'Admin\AdminsController@delete');


// Settings
$router->get('/admin/settings', 'Admin\SettingsController@index');
$router->post('/admin/settings', 'Admin\SettingsController@update');


// Blog Categories
$router->get('/admin/blog/categories', 'Admin\BlogCategoriesController@index');
$router->get('/admin/blog/categories/create', 'Admin\BlogCategoriesController@create');
$router->post('/admin/blog/categories/store', 'Admin\BlogCategoriesController@store');
$router->get('/admin/blog/categories/edit/{id}', 'Admin\BlogCategoriesController@edit');
$router->post('/admin/blog/categories/update/{id}', 'Admin\BlogCategoriesController@update');
$router->post('/admin/blog/categories/delete/{id}', 'Admin\BlogCategoriesController@delete');


// Blog Tags
$router->get('/admin/blog/tags', 'Admin\BlogTagsController@index');
$router->get('/admin/blog/tags/create', 'Admin\BlogTagsController@create');
$router->post('/admin/blog/tags/store', 'Admin\BlogTagsController@store');
$router->get('/admin/blog/tags/edit/{id}', 'Admin\BlogTagsController@edit');
$router->post('/admin/blog/tags/update/{id}', 'Admin\BlogTagsController@update');
$router->post('/admin/blog/tags/delete/{id}', 'Admin\BlogTagsController@destroy');


// Blog Posts
$router->get('/admin/blog', 'Admin\BlogPostsController@index');
$router->get('/admin/blog/posts', 'Admin\BlogPostsController@index');
$router->get('/admin/blog/posts/create', 'Admin\BlogPostsController@create');
$router->post('/admin/blog/posts/store', 'Admin\BlogPostsController@store');
$router->get('/admin/blog/posts/edit/{id}', 'Admin\BlogPostsController@edit');
$router->post('/admin/blog/posts/update/{id}', 'Admin\BlogPostsController@update');
$router->post('/admin/blog/posts/delete/{id}', 'Admin\BlogPostsController@delete');
$router->post('/admin/blog/posts/delete-image/{id}', 'Admin\BlogPostsController@deleteImage');
$router->get('/admin/api/tags/search', 'Admin\BlogTagsController@search');

// Blog Comments
$router->get('/admin/blog/comments', 'Admin\BlogCommentsController@index');
$router->get('/admin/blog/comments/edit/{id}', 'Admin\BlogCommentsController@edit');
$router->post('/admin/blog/comments/update/{id}', 'Admin\BlogCommentsController@update');
$router->post('/admin/blog/comments/delete/{id}', 'Admin\BlogCommentsController@destroy');
$router->post('/admin/blog/comments/status/{id}', 'Admin\BlogCommentsController@updateStatus');
$router->post('/admin/blog/comments/reply/{id}', 'Admin\BlogCommentsController@reply');

// Custom Order Fields
$router->get('/admin/custom-fields', 'Admin\CustomOrderFieldsController@index');
$router->get('/admin/custom-fields/create', 'Admin\CustomOrderFieldsController@create');
$router->post('/admin/custom-fields/store', 'Admin\CustomOrderFieldsController@store');
$router->get('/admin/custom-fields/edit/{id}', 'Admin\CustomOrderFieldsController@edit');
$router->post('/admin/custom-fields/update/{id}', 'Admin\CustomOrderFieldsController@update');
$router->post('/admin/custom-fields/delete/{id}', 'Admin\CustomOrderFieldsController@delete');

// Pages Management
$router->get('/admin/pages', 'Admin\PagesController@index');
$router->get('/admin/pages/create', 'Admin\PagesController@create');
$router->post('/admin/pages/store', 'Admin\PagesController@store');
$router->get('/admin/pages/edit/{id}', 'Admin\PagesController@edit');
$router->post('/admin/pages/update/{id}', 'Admin\PagesController@update');
$router->post('/admin/pages/delete/{id}', 'Admin\PagesController@delete');

// FAQ Management
$router->get('/admin/faq', 'Admin\FaqController@index');
$router->get('/admin/faq/create', 'Admin\FaqController@create');
$router->post('/admin/faq/store', 'Admin\FaqController@store');
$router->get('/admin/faq/edit/{id}', 'Admin\FaqController@edit');
$router->post('/admin/faq/update/{id}', 'Admin\FaqController@update');
$router->post('/admin/faq/delete/{id}', 'Admin\FaqController@delete');
$router->post('/admin/faq/reorder', 'Admin\FaqController@reorder');

// Reviews Management
$router->get('/admin/reviews', 'Admin\ReviewsController@index');
$router->get('/admin/reviews/edit/{id}', 'Admin\ReviewsController@edit');
$router->post('/admin/reviews/update/{id}', 'Admin\ReviewsController@update');
$router->post('/admin/reviews/delete/{id}', 'Admin\ReviewsController@destroy');

// API routes for admin panel (e.g., TinyMCE image upload)
$router->post('/admin/api/upload-image', 'Admin\ApiController@uploadImage');

// Media Library
$router->get('/admin/media', 'Admin\MediaController@index');
$router->post('/admin/media/delete/{id}', 'Admin\MediaController@delete');

// Plugins Management
$router->get('/admin/plugins', 'Admin\PluginsController@index');
$router->post('/admin/plugins/upload', 'Admin\PluginsController@upload');
$router->post('/admin/plugins/activate/{slug}', 'Admin\PluginsController@activate');
$router->post('/admin/plugins/deactivate/{slug}', 'Admin\PluginsController@deactivate');
$router->post('/admin/plugins/delete/{slug}', 'Admin\PluginsController@delete');

// ----------------------------------------------------------------------
// AI News Plugin Routes
// ----------------------------------------------------------------------
$router->get('/admin/ai-news/settings', '\App\Plugins\AiNews\Controllers\AiNewsController@settings');
$router->post('/admin/ai-news/settings/save', '\App\Plugins\AiNews\Controllers\AiNewsController@saveSettings');
$router->post('/admin/ai-news/test-connection', '\App\Plugins\AiNews\Controllers\AiNewsController@testConnection');
$router->get('/admin/ai-news/list', '\App\Plugins\AiNews\Controllers\AiNewsController@list');
$router->post('/admin/ai-news/fetch', '\App\Plugins\AiNews\Controllers\AiNewsController@fetch');
$router->post('/admin/ai-news/approve/{id}', '\App\Plugins\AiNews\Controllers\AiNewsController@approve');
$router->post('/admin/ai-news/delete/{id}', '\App\Plugins\AiNews\Controllers\AiNewsController@delete');
$router->post('/admin/ai-news/clear-history', '\App\Plugins\AiNews\Controllers\AiNewsController@clearHistory');
$router->post('/admin/ai-news/clear-logs', '\App\Plugins\AiNews\Controllers\AiNewsController@clearLogs');
