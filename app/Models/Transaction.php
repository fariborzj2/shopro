<?php

namespace App\Models;

use App\Core\Database;

class Transaction
{
    protected static $table = 'transactions';

    public static function create(array $data)
    {
        $db = Database::getConnection();
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        $sql = "INSERT INTO " . self::$table . " ($columns) VALUES ($placeholders)";
        $stmt = $db->prepare($sql);
        $stmt->execute(array_values($data));
        return $db->lastInsertId();
    }

    public static function update($id, array $data)
    {
        $db = Database::getConnection();
        $set_clause = implode(', ', array_map(fn($key) => "$key = ?", array_keys($data)));
        $sql = "UPDATE " . self::$table . " SET $set_clause WHERE id = ?";
        $stmt = $db->prepare($sql);
        $values = array_values($data);
        $values[] = $id;
        return $stmt->execute($values);
    }

    public static function updateByOrderId($order_id, array $data)
    {
        $db = Database::getConnection();
        $set_clause = implode(', ', array_map(fn($key) => "$key = ?", array_keys($data)));
        $sql = "UPDATE " . self::$table . " SET $set_clause WHERE order_id = ?";
        $stmt = $db->prepare($sql);
        $values = array_values($data);
        $values[] = $order_id;
        return $stmt->execute($values);
    }

    public static function findBy($column, $value)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM " . self::$table . " WHERE $column = ?");
        $stmt->execute([$value]);
        return $stmt->fetch(\PDO::FETCH_OBJ);
    }
}
