<?php

// Simulate the environment
$_POST['meta_keywords'] = ['keyword1', 'keyword2'];

echo "Simulating Controller Logic...\n";

try {
    // The problematic line from CategoriesController.php
    $value = htmlspecialchars($_POST['meta_keywords'] ?? '');
    echo "Result: " . $value . "\n";
} catch (\TypeError $e) {
    echo "Caught expected error: " . $e->getMessage() . "\n";
}
