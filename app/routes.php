<?php

// app/routes.php

// ... (other routes)

// Blog
$router->get('/blog', 'BlogCategoriesController@index'); // Temp redirect
$router->get('/blog/categories', 'BlogCategoriesController@index');
$router->get('/blog/categories/create', 'BlogCategoriesController@create');
$router->post('/blog/categories/store', 'BlogCategoriesController@store');
$router->get('/blog/categories/edit/{id}', 'BlogCategoriesController@edit');
$router->post('/blog/categories/update/{id}', 'BlogCategoriesController@update');
