<?php
define('PROJECT_ROOT', __DIR__);
require_once PROJECT_ROOT . '/app/Core/Database.php';

$sql = "
CREATE TABLE IF NOT EXISTS `blog_post_comments` (
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
";

try {
    $db = \App\Core\Database::getConnection();
    $db->exec($sql);
    echo "Table blog_post_comments created successfully.\n";
} catch (PDOException $e) {
    echo "Error creating table: " . $e->getMessage() . "\n";
}
