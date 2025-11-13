<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class BlogCategory
{
    /**
     * Get all blog categories from the database, including parent name.
     *
     * @return array
     */
    public static function all()
    {
        $sql = "SELECT bc1.*, bc2.name_fa as parent_name
                FROM blog_categories bc1
                LEFT JOIN blog_categories bc2 ON bc1.parent_id = bc2.id
                ORDER BY bc1.position ASC, bc1.id DESC";
        $stmt = Database::query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Find a blog category by its ID.
     *
     * @param int $id
     * @return mixed
     */
    public static function find($id)
    {
        $stmt = Database::query("SELECT * FROM blog_categories WHERE id = :id", ['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Create a new blog category.
     *
     * @param array $data
     * @return bool
     */
    public static function create($data)
    {
        $sql = "INSERT INTO blog_categories (parent_id, name_fa, name_en, slug, status, position)
                VALUES (:parent_id, :name_fa, :name_en, :slug, :status, :position)";
        Database::query($sql, [
            'parent_id' => $data['parent_id'] ?: null,
            'name_fa' => $data['name_fa'],
            'name_en' => $data['name_en'],
            'slug' => $data['slug'],
            'status' => $data['status'],
            'position' => $data['position'] ?? 0
        ]);
        return true;
    }

    /**
     * Update an existing blog category.
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public static function update($id, $data)
    {
        $sql = "UPDATE blog_categories
                SET parent_id = :parent_id, name_fa = :name_fa, name_en = :name_en, slug = :slug, status = :status, position = :position
                WHERE id = :id";
        Database::query($sql, [
            'id' => $id,
            'parent_id' => $data['parent_id'] ?: null,
            'name_fa' => $data['name_fa'],
            'name_en' => $data['name_en'],
            'slug' => $data['slug'],
            'status' => $data['status'],
            'position' => $data['position'] ?? 0
        ]);
        return true;
    }

    /**
     * Delete a blog category.
     *
     * @param int $id
     * @return bool
     */
    public static function delete($id)
    {
        Database::query("DELETE FROM blog_categories WHERE id = :id", ['id' => $id]);
        return true;
    }

    public static function findBy($column, $value)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM blog_categories WHERE $column = ?");
        $stmt->execute([$value]);
        return $stmt->fetch(\PDO::FETCH_OBJ);
    }
}
