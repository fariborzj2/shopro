<?php

use App\Core\Router;
use AiContentPro\Controllers\SettingsController;
use AiContentPro\Controllers\GeneratorController;
use AiContentPro\Controllers\DashboardController;
use AiContentPro\Controllers\CalendarController;
use AiContentPro\Controllers\LogController;

// Admin Routes for AI Content Pro
// Note: These will be loaded by PluginManager::loadRoutes

Router::addRoute('GET', '/admin/ai-content-pro', '\\' . DashboardController::class . '@index');
Router::addRoute('GET', '/admin/ai-content-pro/generator', '\\' . GeneratorController::class . '@index');
Router::addRoute('GET', '/admin/ai-content-pro/calendar', '\\' . CalendarController::class . '@index');
Router::addRoute('POST', '/admin/ai-content-pro/calendar/delete/{id}', '\\' . CalendarController::class . '@delete');
Router::addRoute('GET', '/admin/ai-content-pro/logs', '\\' . LogController::class . '@index');
Router::addRoute('POST', '/admin/ai-content-pro/logs/clear', '\\' . LogController::class . '@clear');
Router::addRoute('GET', '/admin/ai-content-pro/settings', '\\' . SettingsController::class . '@index');
Router::addRoute('POST', '/admin/ai-content-pro/settings/update', '\\' . SettingsController::class . '@update');

// API Routes for AJAX
Router::addRoute('POST', '/admin/api/ai/jobs/create', '\\' . GeneratorController::class . '@createJob');
Router::addRoute('POST', '/admin/api/ai/jobs/bulk', '\\' . GeneratorController::class . '@createBulk');
Router::addRoute('GET', '/admin/api/ai/jobs/status/{id}', '\\' . GeneratorController::class . '@status');
Router::addRoute('POST', '/admin/api/ai/process', '\\' . GeneratorController::class . '@processQueue'); // Trigger worker via AJAX
