<?php

// app/routes.php

// Define application routes.
$router->get('', 'DashboardController@index');
$router->get('/', 'DashboardController@index');

// Users
$router->get('/users', 'UsersController@index');
$router->get('/users/create', 'UsersController@create');
$router->post('/users/store', 'UsersController@store');
$router->get('/users/edit/{id}', 'UsersController@edit');
$router->post('/users/update/{id}', 'UsersController@update');

// Categories
$router->get('/categories', 'CategoriesController@index');
$router->get('/categories/create', 'CategoriesController@create');
$router->post('/categories/store', 'CategoriesController@store');
$router->get('/categories/edit/{id}', 'CategoriesController@edit');
$router->post('/categories/update/{id}', 'CategoriesController@update');

// Other sections (temporary placeholders)
$router->get('/orders', 'OrdersController@index');
$router->get('/products', 'ProductsController@index');
$router->get('/blog', 'BlogController@index');
