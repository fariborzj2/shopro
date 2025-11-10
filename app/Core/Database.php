<?php

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    /** @var PDO|null */
    private static $pdo = null;

    /**
     * Get the PDO database connection.
     *
     * @return PDO
     */
    public static function getConnection()
    {
        if (self::$pdo === null) {
            // Simple file logging for debugging
            file_put_contents(__DIR__ . '/../../db.log', "Attempting to connect...\n", FILE_APPEND);

            $config = require __DIR__ . '/../../config.php';
            $dbConfig = $config['database'];

            $dsn = "mysql:host={$dbConfig['host']};port={$dbConfig['port']};dbname={$dbConfig['dbname']};charset={$dbConfig['charset']}";

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            try {
                self::$pdo = new PDO($dsn, $dbConfig['user'], $dbConfig['password'], $options);
                file_put_contents(__DIR__ . '/../../db.log', "Connection successful.\n", FILE_APPEND);
            } catch (PDOException $e) {
                file_put_contents(__DIR__ . '/../../db.log', "Connection failed: " . $e->getMessage() . "\n", FILE_APPEND);
                // In a real application, you would log this error, not die()
                die('Database connection failed: ' . $e->getMessage());
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
