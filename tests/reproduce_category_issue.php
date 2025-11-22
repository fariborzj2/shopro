<?php

// Simulate environment
define('PROJECT_ROOT', __DIR__ . '/..');
require_once PROJECT_ROOT . '/config.php';
require_once PROJECT_ROOT . '/app/Core/Database.php';
require_once PROJECT_ROOT . '/app/Models/Category.php';

use App\Core\Database;
use App\Models\Category;

// 1. Create a dummy category
$data = [
    'parent_id' => null,
    'name_fa' => 'Test Category ' . time(),
    'name_en' => 'Test Category En',
    'slug' => 'test-category-' . time(),
    'status' => 'active',
    'position' => 0,
    'short_description' => 'Short desc',
    'description' => 'Long desc',
    'meta_title' => 'Meta Title',
    'meta_description' => 'Meta Desc',
    'meta_keywords' => 'tag1,tag2,tag3',
    'published_at' => date('Y-m-d H:i:s'),
    'image_url' => null,
    'thumbnail_url' => null
];

echo "Creating category...\n";
$id = Category::create($data);
echo "Created with ID: $id\n";

// 2. Retrieve it
echo "Retrieving category...\n";
$category = Category::find($id);

// 3. Check structure
echo "Structure check:\n";
if (is_object($category)) {
    echo "Is Object: Yes\n";
    echo "Name FA: " . $category->name_fa . "\n";
    echo "Meta Keywords: " . $category->meta_keywords . "\n";
} else {
    echo "Is Object: No (Type: " . gettype($category) . ")\n";
}

// 4. Simulate View Rendering Logic for Keywords
$viewLogicKeywords = isset($category->meta_keywords) && $category->meta_keywords ? json_encode(array_map('trim', explode(',', htmlspecialchars_decode($category->meta_keywords)))) : '[]';
echo "View Keywords JSON: " . $viewLogicKeywords . "\n";

// 5. Clean up
// Category::delete($id);
