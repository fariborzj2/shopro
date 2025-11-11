<?php

/**
 * Establishes a PDO database connection.
 *
 * This function reads the database configuration from the main config.php file
 * and returns a PDO object. It uses a static variable to ensure that the
 * connection is only established once per request.
 *
 * @return PDO|null The PDO database connection object or null on failure.
 */
function get_db_connection(): ?PDO
{
    // Use a static variable to maintain the connection object
    static $pdo = null;

    if ($pdo === null) {
        // Load the configuration file only once
        if (file_exists(__DIR__ . '/../config.php')) {
            require_once __DIR__ . '/../config.php';
        } else {
            // A more graceful error handling for production
            die("Error: Configuration file is missing. Please create 'config.php' from 'config.example.php'.");
        }

        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            // In a real-world scenario, you would log this error instead of dying.
            error_log('Database Connection Error: ' . $e->getMessage());
            // For the user, show a generic error message
            die('Error: Could not connect to the database.');
        }
    }

    return $pdo;
}
