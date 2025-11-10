<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Category
{
    /**
     * Get all categories from the database, including parent name.
     *
     * @return array
     */
    public static function all()
    {
        $sql = "SELECT c1.*, c2.name_fa as parent_name
                FROM categories c1
                LEFT JOIN categories c2 ON c1.parent_id = c2.id
                ORDER BY c1.position ASC, c1.id DESC";
        $stmt = Database::query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Find a category by its ID.
     *
     * @param int $id
     * @return mixed
     */
    public static function find($id)
    {
        $stmt = Database::query("SELECT * FROM categories WHERE id = :id", ['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Create a new category.
     *
     * @param array $data
     * @return bool
     */
    public static function create($data)
    {
        $sql = "INSERT INTO categories (parent_id, name_fa, name_en, status, position)
                VALUES (:parent_id, :name_fa, :name_en, :status, :position)";
        Database::query($sql, [
            'parent_id' => $data['parent_id'] ?: null,
            'name_fa' => $data['name_fa'],
            'name_en' => $data['name_en'],
            'status' => $data['status'],
            'position' => $data['position'] ?? 0
        ]);
        return true;
    }

    /**
     * Update an existing category.
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public static function update($id, $data)
    {
        $sql = "UPDATE categories
                SET parent_id = :parent_id, name_fa = :name_fa, name_en = :name_en, status = :status, position = :position
                WHERE id = :id";
        Database::query($sql, [
            'id' => $id,
            'parent_id' => $data['parent_id'] ?: null,
            'name_fa' => $data['name_fa'],
            'name_en' => $data['name_en'],
            'status' => $data['status'],
            'position' => $data['position'] ?? 0
        ]);
        return true;
    }

    /**
     * Delete a category.
     *
     * @param int $id
     * @return bool
     */
    public static function delete($id)
    {
        // Note: Depending on DB constraints, deleting a parent category might fail.
        // This simple delete assumes child categories will be handled (e.g., set parent_id to NULL).
        Database::query("DELETE FROM categories WHERE id = :id", ['id' => $id]);
        return true;
    }
}
