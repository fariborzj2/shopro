<?php
// plugins/store/install.php

use App\Core\Database;

$db = Database::getConnection();

// 1. Categories
$db->exec("CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    parent_id INT NULL DEFAULT NULL,
    name_fa VARCHAR(255) NOT NULL,
    name_en VARCHAR(255) NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    short_description TEXT NULL,
    description LONGTEXT NULL,
    image_url VARCHAR(255) NULL,
    thumbnail_url VARCHAR(255) NULL,
    meta_title VARCHAR(255) NULL,
    meta_description VARCHAR(255) NULL,
    meta_keywords TEXT NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    position INT DEFAULT 0,
    published_at TIMESTAMP NULL DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

// 2. Products
$db->exec("CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NULL,
    name_fa VARCHAR(255) NOT NULL,
    name_en VARCHAR(255) NULL,
    price DECIMAL(15, 2) NOT NULL DEFAULT 0,
    dollar_price DECIMAL(10, 2) NULL,
    old_price DECIMAL(15, 2) NULL,
    status ENUM('draft', 'active', 'unavailable') DEFAULT 'draft',
    position INT DEFAULT 0,
    image_url VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

// 3. Orders
// Note: We avoid ENUMs for statuses if we want flexibility, but the code uses ENUMs.
$db->exec("CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NULL,
    amount DECIMAL(15, 2) NOT NULL,
    quantity INT DEFAULT 1,
    payment_status ENUM('unpaid', 'paid', 'failed') DEFAULT 'unpaid',
    order_status ENUM('pending', 'completed', 'cancelled', 'phishing') DEFAULT 'pending',
    custom_fields_data JSON NULL,
    payment_gateway_response JSON NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

// 4. Custom Order Fields
$db->exec("CREATE TABLE IF NOT EXISTS custom_order_fields (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    label VARCHAR(255) NOT NULL,
    type ENUM('text', 'number', 'email', 'select', 'textarea', 'checkbox', 'radio') DEFAULT 'text',
    options TEXT NULL COMMENT 'Newline separated for select/radio',
    required BOOLEAN DEFAULT FALSE,
    position INT DEFAULT 0,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

// 5. Category - Custom Field Pivot
$db->exec("CREATE TABLE IF NOT EXISTS category_custom_field (
    category_id INT NOT NULL,
    field_id INT NOT NULL,
    PRIMARY KEY (category_id, field_id),
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
    FOREIGN KEY (field_id) REFERENCES custom_order_fields(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

// 6. Reviews
$db->exec("CREATE TABLE IF NOT EXISTS reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    user_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    mobile VARCHAR(20) NULL,
    rating TINYINT UNSIGNED NOT NULL,
    comment TEXT NULL,
    admin_reply TEXT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

// 7. Transactions
$db->exec("CREATE TABLE IF NOT EXISTS transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NULL,
    user_id INT NULL,
    amount DECIMAL(15, 2) NOT NULL,
    track_id VARCHAR(255) NULL,
    status VARCHAR(50) DEFAULT 'pending',
    gateway VARCHAR(50) DEFAULT 'zibal',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE SET NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
