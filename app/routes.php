<?php

// app/routes.php

// Define application routes.
$router->get('', 'DashboardController@index');
$router->get('/', 'DashboardController@index');

// Users
$router->get('/users', 'UsersController@index');
$router->get('/users/edit/{id}', 'UsersController@edit');

// Other sections
$router->get('/orders', 'OrdersController@index');
$router->get('/products', 'ProductsController@index');
$router->get('/blog', 'BlogController@index');
