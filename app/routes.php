<?php

// app/routes.php

// ----------------------------------------------------------------------
// Storefront Routes
// ----------------------------------------------------------------------
// Routes moved to plugins/store/routes.php

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
$router->post('/api/auth/send-otp', 'AuthController@sendOtp');
$router->post('/api/auth/verify-otp', 'AuthController@verifyOtp');
$router->get('/logout', 'AuthController@logout');


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
$router->post('/admin/settings/clear-cache', 'Admin\SettingsController@clearCache');


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


// API routes for admin panel (e.g., TinyMCE image upload)
$router->post('/admin/api/upload-image', 'Admin\ApiController@uploadImage');

// Media Library
$router->get('/admin/media', 'Admin\MediaController@index');
$router->post('/admin/media/delete/{id}', 'Admin\MediaController@delete');
$router->post('/admin/media/delete-item', 'Admin\MediaController@deleteItem');

// Plugins Management
$router->get('/admin/plugins', 'Admin\PluginsController@index');
$router->post('/admin/plugins/upload', 'Admin\PluginsController@upload');
$router->post('/admin/plugins/activate/{slug}', 'Admin\PluginsController@activate');
$router->post('/admin/plugins/deactivate/{slug}', 'Admin\PluginsController@deactivate');
$router->post('/admin/plugins/delete/{slug}', 'Admin\PluginsController@delete');

// SeoPilot Plugin Routes
$router->get('/admin/seopilot/settings', '\SeoPilot\Enterprise\Controllers\AdminController@index');
$router->post('/admin/seopilot/settings', '\SeoPilot\Enterprise\Controllers\AdminController@saveSettings');


// Load Active Plugin Routes
\App\Core\Plugin\PluginManager::loadRoutes($router);
