<?php

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    /** @var PDO|null */
    private static $pdo = null;
    /** @var array|null */
    private static $config = null;

    /**
     * Set the database configuration manually.
     * Useful for testing or CLI scripts.
     * @param array $config
     */
    public static function setConfig(array $config)
    {
        self::$config = $config;
    }

    /**
     * Get the PDO database connection.
     *
     * @return PDO
     */
    public static function getConnection()
    {
        if (self::$pdo === null) {
            if (self::$config === null) {
                // Use the globally defined PROJECT_ROOT for a reliable path
                $config = require PROJECT_ROOT . '/config.php';
                $dbConfig = $config['database'];
            } else {
                $dbConfig = self::$config;
            }

            $port = $dbConfig['port'] ?? 3306;
            $dsn = "mysql:host={$dbConfig['host']};port={$port};dbname={$dbConfig['dbname']};charset={$dbConfig['charset']}";

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            try {
                self::$pdo = new PDO($dsn, $dbConfig['user'], $dbConfig['password'], $options);
            } catch (PDOException $e) {
                // In a real application, you would log this error, not die()
                error_log('Database connection failed: ' . $e->getMessage());
                die('Database connection failed. Please check the logs.');
            }
        }

        return self::$pdo;
    }

    /**
     * A simple query helper method.
     *
     * @param string $sql
     * @param array $params
     * @return \PDOStatement
     */
    public static function query($sql, $params = [])
    {
        $stmt = self::getConnection()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
}
