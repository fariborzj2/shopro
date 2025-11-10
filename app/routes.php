<?php

// app/routes.php

// Auth
$router->get('/login', 'LoginController@index');
$router->post('/login', 'LoginController@login');
$router->get('/logout', 'LoginController@logout');

// Dashboard
$router->get('/', 'DashboardController@index');
$router->get('/dashboard', 'DashboardController@index');

// Users
$router->get('/users', 'UsersController@index');
$router->get('/users/create', 'UsersController@create');
$router->post('/users/store', 'UsersController@store');
$router->get('/users/edit/{id}', 'UsersController@edit');
$router->post('/users/update/{id}', 'UsersController@update');
$router->get('/users/delete/{id}', 'UsersController@delete');


// Categories
$router->get('/categories', 'CategoriesController@index');
$router->get('/categories/create', 'CategoriesController@create');
$router->post('/categories/store', 'CategoriesController@store');
$router->get('/categories/edit/{id}', 'CategoriesController@edit');
$router->post('/categories/update/{id}', 'CategoriesController@update');
$router->get('/categories/delete/{id}', 'CategoriesController@delete');


// Products
$router->get('/products', 'ProductsController@index');
$router->get('/products/create', 'ProductsController@create');
$router->post('/products/store', 'ProductsController@store');
$router->get('/products/edit/{id}', 'ProductsController@edit');
$router->post('/products/update/{id}', 'ProductsController@update');
$router->get('/products/delete/{id}', 'ProductsController@delete');


// Orders
$router->get('/orders', 'OrdersController@index');
$router->get('/orders/show/{id}', 'OrdersController@show');
$router->post('/orders/update_status/{id}', 'OrdersController@updateStatus');


// Admins
$router->get('/admins', 'AdminsController@index');


// Blog Categories
$router->get('/blog/categories', 'BlogCategoriesController@index');
$router->get('/blog/categories/create', 'BlogCategoriesController@create');
$router->post('/blog/categories/store', 'BlogCategoriesController@store');
$router->get('/blog/categories/edit/{id}', 'BlogCategoriesController@edit');
$router->post('/blog/categories/update/{id}', 'BlogCategoriesController@update');
$router->get('/blog/categories/delete/{id}', 'BlogCategoriesController@delete');


// Blog Tags
$router->get('/blog/tags', 'BlogTagsController@index');
$router->get('/blog/tags/create', 'BlogTagsController@create');
$router->post('/blog/tags/store', 'BlogTagsController@store');
$router->get('/blog/tags/edit/{id}', 'BlogTagsController@edit');
$router->post('/blog/tags/update/{id}', 'BlogTagsController@update');
$router->get('/blog/tags/delete/{id}', 'BlogTagsController@delete');


// Blog Posts
$router->get('/blog', 'BlogPostsController@index');
$router->get('/blog/posts', 'BlogPostsController@index');
$router->get('/blog/posts/create', 'BlogPostsController@create');
$router->post('/blog/posts/store', 'BlogPostsController@store');
$router->get('/blog/posts/edit/{id}', 'BlogPostsController@edit');
$router->post('/blog/posts/update/{id}', 'BlogPostsController@update');
$router->get('/blog/posts/delete/{id}', 'BlogPostsController@delete');
