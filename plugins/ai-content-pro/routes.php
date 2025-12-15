<?php

use App\Core\Router;
use AiContentPro\Controllers\SettingsController;
use AiContentPro\Controllers\GeneratorController;

// Admin Routes for AI Content Pro
// Note: These will be loaded by PluginManager::loadRoutes

Router::addRoute('GET', '/admin/ai-content-pro/settings', SettingsController::class . '@index');
Router::addRoute('POST', '/admin/ai-content-pro/settings/update', SettingsController::class . '@update');

// API Routes for AJAX
Router::addRoute('POST', '/admin/api/ai/jobs/create', GeneratorController::class . '@createJob');
Router::addRoute('GET', '/admin/api/ai/jobs/status/{id}', GeneratorController::class . '@status');
Router::addRoute('POST', '/admin/api/ai/process', GeneratorController::class . '@processQueue'); // Trigger worker via AJAX
