-- Admin Panel Schema

-- Drop tables if they exist to start fresh, in an order that respects foreign key constraints.
DROP TABLE IF EXISTS `transactions`;
DROP TABLE IF EXISTS `reviews`;
DROP TABLE IF EXISTS `orders`;
DROP TABLE IF EXISTS `blog_post_comments`;
DROP TABLE IF EXISTS `blog_post_tags`;
DROP TABLE IF EXISTS `category_custom_field`;
DROP TABLE IF EXISTS `products`;
DROP TABLE IF EXISTS `blog_posts`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `categories`;
DROP TABLE IF EXISTS `blog_categories`;
DROP TABLE IF EXISTS `admins`;
DROP TABLE IF EXISTS `blog_tags`;
DROP TABLE IF EXISTS `faq_items`;
DROP TABLE IF EXISTS `custom_order_fields`;
DROP TABLE IF EXISTS `otp_codes`;
DROP TABLE IF EXISTS `settings`;
DROP TABLE IF EXISTS `pages`;


-- Users Table
CREATE TABLE `users` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `mobile` VARCHAR(20) NOT NULL UNIQUE,
  `status` ENUM('active', 'inactive', 'banned') NOT NULL DEFAULT 'active',
  `short_note` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `order_count` INT DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Categories Table
CREATE TABLE `categories` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `parent_id` INT DEFAULT NULL,
  `position` INT DEFAULT 0,
  `name_fa` VARCHAR(255) NOT NULL,
  `name_en` VARCHAR(255),
  `slug` VARCHAR(255) NOT NULL UNIQUE,
  `image_url` VARCHAR(255),
  `thumbnail_url` VARCHAR(255),
  `status` ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
  `short_description` TEXT,
  `description` LONGTEXT,
  `meta_title` VARCHAR(255),
  `meta_description` TEXT,
  `meta_keywords` JSON,
  `published_at` TIMESTAMP NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`parent_id`) REFERENCES `categories`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Products Table
CREATE TABLE `products` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `category_id` INT NOT NULL,
  `position` INT DEFAULT 0,
  `name_fa` VARCHAR(255) NOT NULL,
  `name_en` VARCHAR(255),
  `price` DECIMAL(10, 2) NOT NULL,
  `dollar_price` DECIMAL(10, 2) DEFAULT NULL,
  `old_price` DECIMAL(10, 2),
  `status` ENUM('available', 'unavailable', 'draft') NOT NULL DEFAULT 'available',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Orders Table
CREATE TABLE `orders` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `order_code` VARCHAR(20) NOT NULL UNIQUE,
  `user_id` INT NOT NULL,
  `mobile` VARCHAR(20),
  `product_id` INT NOT NULL,
  `category_id` INT,
  `payment_status` ENUM('unpaid', 'paid', 'failed') NOT NULL DEFAULT 'unpaid',
  `order_status` ENUM('pending', 'completed', 'cancelled', 'phishing') NOT NULL DEFAULT 'pending',
  `order_time` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `amount` DECIMAL(10, 2) NOT NULL,
  `discount_used` DECIMAL(10, 2) DEFAULT 0,
  `quantity` INT NOT NULL,
  `payment_method` VARCHAR(50),
  `custom_fields_data` JSON,
  `payment_gateway_response` JSON,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`),
  FOREIGN KEY (`product_id`) REFERENCES `products`(`id`),
  FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Admins Table
CREATE TABLE `admins` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `password_hash` VARCHAR(255) NOT NULL,
  `name` VARCHAR(100),
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `role` VARCHAR(50),
  `is_super_admin` BOOLEAN NOT NULL DEFAULT FALSE,
  `permissions` JSON DEFAULT NULL,
  `status` ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `last_login` TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Blog Categories Table
CREATE TABLE `blog_categories` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `parent_id` INT DEFAULT NULL,
  `position` INT DEFAULT 0,
  `name_fa` VARCHAR(255) NOT NULL,
  `name_en` VARCHAR(255),
  `slug` VARCHAR(255) NOT NULL UNIQUE,
  `status` ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
  `meta_title` VARCHAR(255),
  `meta_description` TEXT,
  `image_url` VARCHAR(255),
  `notes` TEXT,
  FOREIGN KEY (`parent_id`) REFERENCES `blog_categories`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Blog Posts Table
CREATE TABLE `blog_posts` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `category_id` INT NOT NULL,
  `author_id` INT NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL UNIQUE,
  `content` LONGTEXT,
  `excerpt` TEXT,
  `status` ENUM('published', 'draft', 'scheduled') NOT NULL DEFAULT 'draft',
  `meta_title` VARCHAR(255),
  `meta_description` TEXT,
  `image_url` VARCHAR(255),
  `meta_keywords` JSON,
  `faq` JSON DEFAULT NULL,
  `views_count` INT DEFAULT 0,
  `is_editors_pick` BOOLEAN NOT NULL DEFAULT FALSE,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `published_at` TIMESTAMP NULL,
  FOREIGN KEY (`category_id`) REFERENCES `blog_categories`(`id`),
  FOREIGN KEY (`author_id`) REFERENCES `admins`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Blog Tags Table
CREATE TABLE `blog_tags` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(100) NOT NULL UNIQUE,
  `status` ENUM('active', 'inactive') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Blog Post Tags (Pivot Table)
CREATE TABLE `blog_post_tags` (
  `post_id` INT NOT NULL,
  `tag_id` INT NOT NULL,
  PRIMARY KEY (`post_id`, `tag_id`),
  FOREIGN KEY (`post_id`) REFERENCES `blog_posts`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`tag_id`) REFERENCES `blog_tags`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Settings Table
CREATE TABLE `settings` (
  `setting_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `setting_value` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Custom Order Fields Table
CREATE TABLE `custom_order_fields` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `label_fa` VARCHAR(255) NOT NULL,
  `type` ENUM('text', 'textarea', 'number', 'select', 'radio', 'checkbox', 'date', 'file', 'color', 'wysiwyg') NOT NULL,
  `options` TEXT COMMENT 'JSON encoded options for select, radio, checkbox',
  `is_required` BOOLEAN DEFAULT FALSE,
  `default_value` VARCHAR(255),
  `placeholder` VARCHAR(255),
  `validation_rules` VARCHAR(255) COMMENT 'e.g., regex:/^[0-9]+$/|min:5|max:10',
  `help_text` VARCHAR(255),
  `position` INT DEFAULT 0,
  `status` ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Reviews Table
CREATE TABLE `reviews` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `product_id` INT NOT NULL,
  `user_id` INT NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `mobile` VARCHAR(20) NOT NULL,
  `rating` TINYINT NOT NULL,
  `comment` TEXT NOT NULL,
  `admin_reply` TEXT,
  `status` ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- FAQ Items Table
CREATE TABLE `faq_items` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `question` VARCHAR(255) NOT NULL,
  `answer` TEXT NOT NULL,
  `type` VARCHAR(100) NOT NULL DEFAULT 'general_questions',
  `position` INT DEFAULT 0,
  `status` ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Transactions Table
CREATE TABLE `transactions` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `order_id` INT NOT NULL,
  `user_id` INT NOT NULL,
  `track_id` VARCHAR(255) NULL,
  `amount` DECIMAL(10, 2) NOT NULL,
  `status` ENUM('pending', 'successful', 'failed') NOT NULL DEFAULT 'pending',
  `payment_gateway` VARCHAR(50) DEFAULT 'zibal',
  `gateway_response` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- OTP Codes Table
CREATE TABLE `otp_codes` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `mobile` VARCHAR(20) NOT NULL,
  `otp_hash` VARCHAR(255) NOT NULL,
  `expires_at` TIMESTAMP NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `is_used` BOOLEAN DEFAULT FALSE,
  INDEX `idx_mobile` (`mobile`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Category Custom Fields (Pivot Table)
CREATE TABLE `category_custom_field` (
  `category_id` INT NOT NULL,
  `field_id` INT NOT NULL,
  PRIMARY KEY (`category_id`, `field_id`),
  FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`field_id`) REFERENCES `custom_order_fields`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Pages Table
CREATE TABLE `pages` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL UNIQUE,
  `content` LONGTEXT,
  `short_description` TEXT,
  `meta_title` VARCHAR(255),
  `meta_keywords` JSON,
  `meta_description` TEXT,
  `published_at` TIMESTAMP NULL,
  `status` ENUM('published', 'draft') NOT NULL DEFAULT 'draft',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Media Uploads Table
CREATE TABLE `media_uploads` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `file_path` VARCHAR(255) NOT NULL UNIQUE,
  `context` VARCHAR(100) NOT NULL,
  `uploaded_by_admin_id` INT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`uploaded_by_admin_id`) REFERENCES `admins`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Blog Post Comments Table
CREATE TABLE `blog_post_comments` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `post_id` INT NOT NULL,
  `parent_id` INT DEFAULT NULL,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255),
  `comment` TEXT NOT NULL,
  `status` ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`post_id`) REFERENCES `blog_posts`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`parent_id`) REFERENCES `blog_post_comments`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- Insert some sample data for users
INSERT INTO `users` (`name`, `mobile`, `status`) VALUES
('علی رضایی', '09123456789', 'active'),
('مریم احمدی', '09129876543', 'inactive'),
('رضا حسینی', '09121112233', 'banned');
