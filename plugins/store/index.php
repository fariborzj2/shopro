<?php

use App\Core\Plugin\Filter;

// Register Menu Items
Filter::add('admin_menu_items', function($items) {
    // Add Shop related items
    $shopItems = [
        [
            'label' => 'سفارشات',
            'url' => '/orders',
            'icon' => 'orders',
            'permission' => 'orders'
        ],
        [
            'label' => 'محصولات',
            'url' => '/products',
            'icon' => 'products',
            'permission' => 'products'
        ],
        [
            'label' => 'دسته‌بندی‌ها',
            'url' => '/categories',
            'icon' => 'categories',
            'permission' => 'categories'
        ],
        [
            'label' => 'نظرات',
            'url' => '/reviews',
            'icon' => 'message',
            'permission' => 'reviews'
        ],
        [
            'label' => 'پارامترها',
            'url' => '/custom-fields',
            'icon' => 'settings',
            'permission' => 'custom_fields'
        ]
    ];

    // Insert after 'dashboard' if possible, or just append
    // For simplicity, let's inject them at a specific position or just merge
    // Given the structure, array_splice or just array_merge is fine.
    // Let's put them after Dashboard (index 0).

    // Note: The structure of $items is indexed array.
    // Dashboard is usually at 0.

    array_splice($items, 1, 0, $shopItems);

    return $items;
});
