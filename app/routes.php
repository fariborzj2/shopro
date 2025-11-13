<?php

// app/routes.php

// ----------------------------------------------------------------------
// Storefront Routes
// ----------------------------------------------------------------------
$router->get('/', 'StorefrontController@home');
$router->get('/page/{slug}', 'StorefrontController@page');
$router->get('/category/{slug}', 'StorefrontController@category');


// ----------------------------------------------------------------------
// API Routes
// ----------------------------------------------------------------------
$router->get('/api/product-details/{id}', 'ApiController@productDetails');
$router->post('/api/auth/send-otp', 'AuthController@sendOtp');
$router->post('/api/auth/verify-otp', 'AuthController@verifyOtp');
$router->post('/api/payment/start', 'PaymentController@startPayment');


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
$router->get('/admin/login', 'LoginController@index');
$router->post('/admin/login', 'LoginController@login');
$router->get('/admin/logout', 'LoginController@logout');

// Dashboard
$router->get('/admin', 'DashboardController@index');
$router->get('/admin/dashboard', 'DashboardController@index');

// Users
$router->get('/admin/users', 'UsersController@index');
$router->get('/admin/users/create', 'UsersController@create');
$router->post('/admin/users/store', 'UsersController@store');
$router->get('/admin/users/edit/{id}', 'UsersController@edit');
$router->post('/admin/users/update/{id}', 'UsersController@update');
$router->post('/admin/users/delete/{id}', 'UsersController@delete');


// Categories
$router->get('/admin/categories', 'CategoriesController@index');
$router->get('/admin/categories/create', 'CategoriesController@create');
$router->post('/admin/categories/store', 'CategoriesController@store');
$router->get('/admin/categories/edit/{id}', 'CategoriesController@edit');
$router->post('/admin/categories/update/{id}', 'CategoriesController@update');
$router->post('/admin/categories/delete/{id}', 'CategoriesController@delete');
$router->post('/admin/categories/reorder', 'CategoriesController@reorder');


// Products
$router->get('/admin/products', 'ProductsController@index');
$router->get('/admin/products/create', 'ProductsController@create');
$router->post('/admin/products/store', 'ProductsController@store');
$router->get('/admin/products/edit/{id}', 'ProductsController@edit');
$router->post('/admin/products/update/{id}', 'ProductsController@update');
$router->post('/admin/products/delete/{id}', 'ProductsController@delete');
$router->post('/admin/products/reorder', 'ProductsController@reorder');


// Orders
$router->get('/admin/orders', 'OrdersController@index');
$router->get('/admin/orders/show/{id}', 'OrdersController@show');
$router->post('/admin/orders/update_status/{id}', 'OrdersController@updateStatus');


// Admins
$router->get('/admin/admins', 'AdminsController@index');


// Settings
$router->get('/admin/settings', 'SettingsController@index');
$router->post('/admin/settings', 'SettingsController@update');


// Blog Categories
$router->get('/admin/blog/categories', 'BlogCategoriesController@index');
$router->get('/admin/blog/categories/create', 'BlogCategoriesController@create');
$router->post('/admin/blog/categories/store', 'BlogCategoriesController@store');
$router->get('/admin/blog/categories/edit/{id}', 'BlogCategoriesController@edit');
$router->post('/admin/blog/categories/update/{id}', 'BlogCategoriesController@update');
$router->post('/admin/blog/categories/delete/{id}', 'BlogCategoriesController@delete');


// Blog Tags
$router->get('/admin/blog/tags', 'BlogTagsController@index');
$router->get('/admin/blog/tags/create', 'BlogTagsController@create');
$router->post('/admin/blog/tags/store', 'BlogTagsController@store');
$router->get('/admin/blog/tags/edit/{id}', 'BlogTagsController@edit');
$router->post('/admin/blog/tags/update/{id}', 'BlogTagsController@update');
$router->post('/admin/blog/tags/delete/{id}', 'BlogTagsController@delete');


// Blog Posts
$router->get('/admin/blog', 'BlogPostsController@index');
$router->get('/admin/blog/posts', 'BlogPostsController@index');
$router->get('/admin/blog/posts/create', 'BlogPostsController@create');
$router->post('/admin/blog/posts/store', 'BlogPostsController@store');
$router->get('/admin/blog/posts/edit/{id}', 'BlogPostsController@edit');
$router->post('/admin/blog/posts/update/{id}', 'BlogPostsController@update');
$router->post('/admin/blog/posts/delete/{id}', 'BlogPostsController@delete');

// Custom Order Fields
$router->get('/admin/custom-fields', 'CustomOrderFieldsController@index');
$router->get('/admin/custom-fields/create', 'CustomOrderFieldsController@create');
$router->post('/admin/custom-fields/store', 'CustomOrderFieldsController@store');
$router->get('/admin/custom-fields/edit/{id}', 'CustomOrderFieldsController@edit');
$router->post('/admin/custom-fields/update/{id}', 'CustomOrderFieldsController@update');
$router->post('/admin/custom-fields/delete/{id}', 'CustomOrderFieldsController@delete');

// Pages Management
$router->get('/admin/pages', 'PagesController@index');
$router->get('/admin/pages/create', 'PagesController@create');
$router->post('/admin/pages/store', 'PagesController@store');
$router->get('/admin/pages/edit/{id}', 'PagesController@edit');
$router->post('/admin/pages/update/{id}', 'PagesController@update');
$router->post('/admin/pages/delete/{id}', 'PagesController@delete');

// FAQ Management
$router->get('/admin/faq', 'FaqController@index');
$router->get('/admin/faq/create', 'FaqController@create');
$router->post('/admin/faq/store', 'FaqController@store');
$router->get('/admin/faq/edit/{id}', 'FaqController@edit');
$router->post('/admin/faq/update/{id}', 'FaqController@update');
$router->post('/admin/faq/delete/{id}', 'FaqController@delete');
