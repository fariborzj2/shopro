<?php

use App\Core\Router;
use AiNews\Controllers\DashboardController;
use AiNews\Controllers\SourceController;
use AiNews\Controllers\SettingsController;
use AiNews\Controllers\AutomationController;

Router::addRoute('GET', '/admin/ai-news', '\\' . DashboardController::class . '@index');
Router::addRoute('GET', '/admin/ai-news/sources', '\\' . SourceController::class . '@index');
Router::addRoute('GET', '/admin/ai-news/sources/create', '\\' . SourceController::class . '@create');
Router::addRoute('POST', '/admin/ai-news/sources/store', '\\' . SourceController::class . '@store');
Router::addRoute('GET', '/admin/ai-news/sources/edit/{id}', '\\' . SourceController::class . '@edit');
Router::addRoute('POST', '/admin/ai-news/sources/update/{id}', '\\' . SourceController::class . '@update');
Router::addRoute('POST', '/admin/ai-news/sources/delete/{id}', '\\' . SourceController::class . '@delete');

Router::addRoute('GET', '/admin/ai-news/settings', '\\' . SettingsController::class . '@index');
Router::addRoute('POST', '/admin/ai-news/settings/update', '\\' . SettingsController::class . '@update');

// Trigger manually
Router::addRoute('POST', '/admin/ai-news/trigger', '\\' . AutomationController::class . '@trigger');
