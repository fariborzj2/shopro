<?php
// Define PROJECT_ROOT to allow Database class to find the config
define('PROJECT_ROOT', dirname(__DIR__));

// Include necessary files
require_once PROJECT_ROOT . '/app/Core/Database.php';
require_once PROJECT_ROOT . '/config.php';

// SQL to create the table
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
    // Get database connection
    $dbConfig = require PROJECT_ROOT . '/config.php';
    $db = \App\Core\Database::getConnection($dbConfig['database']);

    // Execute the query
    $db->exec($sql);

    echo "<h1>Success!</h1><p>The `blog_post_comments` table has been created successfully.</p>";

} catch (PDOException $e) {
    // Display error message
    echo "<h1>Error</h1><p>Error creating table: " . htmlspecialchars($e->getMessage()) . "</p>";
} catch (Exception $e) {
    echo "<h1>An unexpected error occurred</h1><p>" . htmlspecialchars($e->getMessage()) . "</p>";
} finally {
    // IMPORTANT: Delete the script itself after execution
    unlink(__FILE__);
}
