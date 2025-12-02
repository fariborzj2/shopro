<?php

// register_ai_news_plugin.php

define('PROJECT_ROOT', __DIR__);
require_once PROJECT_ROOT . '/app/Core/Database.php';

try {
    $db = \App\Core\Database::getConnection();

    // Check if plugin already exists
    $stmt = $db->prepare("SELECT id FROM plugins WHERE slug = ?");
    $stmt->execute(['ai-news']);

    if (!$stmt->fetch()) {
        $stmt = $db->prepare("INSERT INTO plugins (name, slug, version, status) VALUES (?, ?, ?, 'active')");
        $stmt->execute([
            'Smart Assistant (AI News)',
            'ai-news',
            '1.0.0'
        ]);
        echo "AI News plugin registered successfully.\n";
    } else {
        echo "AI News plugin already registered.\n";
    }

} catch (Exception $e) {
    echo "Error registering plugin: " . $e->getMessage() . "\n";
}
