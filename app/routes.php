<?php

// app/routes.php

// ... (other routes)

// Blog
$router->get('/blog', 'BlogPostsController@index'); // Main blog route
$router->get('/blog/posts', 'BlogPostsController@index');
$router->get('/blog/posts/create', 'BlogPostsController@create');
$router->post('/blog/posts/store', 'BlogPostsController@store');
$router->get('/blog/posts/edit/{id}', 'BlogPostsController@edit');
$router->post('/blog/posts/update/{id}', 'BlogPostsController@update');

$router->get('/blog/categories', 'BlogCategoriesController@index');
// ... (blog category routes)
