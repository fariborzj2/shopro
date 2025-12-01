<?php

define('PROJECT_ROOT', __DIR__);

require_once PROJECT_ROOT . '/app/Core/Database.php';
require_once PROJECT_ROOT . '/app/Plugins/AiNews/Models/AiSetting.php';

use App\Plugins\AiNews\Models\AiSetting;

// Helper to get the default prompt from the updated GroqService class
// We can't easily instantiate the class without an API key if it checks in constructor,
// but looking at the code, the constructor just reads settings.
// However, to be safe and DRY, I will copy the prompt string here or instantiate the class.
// Instantiating is better if dependencies allow.
require_once PROJECT_ROOT . '/app/Plugins/AiNews/Services/GroqService.php';

echo "Updating AI Settings...\n";

// 1. Update Model
$newModel = 'llama-3.3-70b-versatile';
AiSetting::set('groq_model', $newModel);
echo "Updated 'groq_model' to '$newModel'.\n";

// 2. Update Prompt Template
// I will extract the prompt from the class I just updated to ensure consistency.
$service = new \App\Plugins\AiNews\Services\GroqService();
// Accessing private method via Reflection or just copy-pasting.
// Reflection is cleaner.
$reflection = new ReflectionClass($service);
$method = $reflection->getMethod('getDefaultPrompt');
$method->setAccessible(true);
$newPrompt = $method->invoke($service);

AiSetting::set('prompt_template', $newPrompt);
echo "Updated 'prompt_template' to new enhanced version.\n";

echo "Done.\n";
