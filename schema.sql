-- Admin Panel Schema

-- Drop tables if they exist to start fresh
DROP TABLE IF EXISTS `blog_post_tags`;
DROP TABLE IF EXISTS `blog_tags`;
DROP TABLE IF EXISTS `blog_posts`;
DROP TABLE IF EXISTS `blog_categories`;
DROP TABLE IF EXISTS `admins`;
DROP TABLE IF EXISTS `orders`;
DROP TABLE IF EXISTS `products`;
DROP TABLE IF EXISTS `categories`;
DROP TABLE IF EXISTS `users`;

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
  `title` VARCHAR(255) NOT NULL,
  `name_fa` VARCHAR(255),
  `name_en` VARCHAR(255),
  `image_url` VARCHAR(255),
  `status` ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
  `notes` TEXT,
  `description` TEXT,
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
  `status` ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') NOT NULL DEFAULT 'pending',
  `order_time` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `amount` DECIMAL(10, 2) NOT NULL,
  `discount_used` DECIMAL(10, 2) DEFAULT 0,
  `quantity` INT NOT NULL,
  `payment_method` VARCHAR(50),
  `delivery_address` TEXT,
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
  `featured_image` VARCHAR(255),
  `views_count` INT DEFAULT 0,
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

-- Insert some sample data for users
INSERT INTO `users` (`name`, `mobile`, `status`) VALUES
('علی رضایی', '09123456789', 'active'),
('مریم احمدی', '09129876543', 'inactive'),
('رضا حسینی', '09121112233', 'banned');
