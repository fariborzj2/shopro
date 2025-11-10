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
            } catch (PDOException $e) {
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
