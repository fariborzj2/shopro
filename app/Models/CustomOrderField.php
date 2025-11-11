<?php

namespace App\Models;

use App\Core\Database;

class CustomOrderField
{
    protected static $table = 'custom_order_fields';

    public static function all()
    {
        $db = Database::getConnection();
        $stmt = $db->query('SELECT * FROM ' . self::$table . ' ORDER BY position ASC, id DESC');
        return $stmt->fetchAll();
    }

    public static function find($id)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare('SELECT * FROM ' . self::$table . ' WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

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
        $set = [];
        foreach ($data as $key => $value) {
            $set[] = "$key = ?";
        }
        $sql = "UPDATE " . self::$table . " SET " . implode(', ', $set) . " WHERE id = ?";
        $values = array_values($data);
        $values[] = $id;
        $stmt = $db->prepare($sql);
        return $stmt->execute($values);
    }

    public static function delete($id)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare('DELETE FROM ' . self::$table . ' WHERE id = ?');
        return $stmt->execute([$id]);
    }

    /**
     * Get all fields associated with a specific category.
     *
     * @param int $categoryId
     * @return array
     */
    public static function findByCategoryId($categoryId)
    {
        $db = Database::getConnection();
        $sql = "SELECT cof.* FROM custom_order_fields cof
                JOIN category_custom_field ccf ON cof.id = ccf.field_id
                WHERE ccf.category_id = ? AND cof.status = 'active'
                ORDER BY ccf.position ASC"; // Assuming a position column in pivot table
        $stmt = $db->prepare($sql);
        $stmt->execute([$categoryId]);
        return $stmt->fetchAll();
    }
}

    /**
     * Get all fields indexed by their ID.
     *
     * @return array
     */
    public static function getAllFieldsById()
    {
        $fields = self::all();
        $fieldsById = [];
        foreach ($fields as $field) {
            $fieldsById[$field['id']] = $field;
        }
        return $fieldsById;
    }
