<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class BlogTag
{
    /**
     * Get all blog tags.
     *
     * @return array
     */
    public static function all()
    {
        $stmt = Database::query("SELECT * FROM blog_tags ORDER BY name ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Find a tag by its ID.
     *
     * @param int $id
     * @return mixed
     */
    public static function find($id)
    {
        $stmt = Database::query("SELECT * FROM blog_tags WHERE id = :id", ['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Create a new tag.
     *
     * @param array $data
     * @return bool
     */
    public static function create($data)
    {
        Database::query("INSERT INTO blog_tags (name, slug, status) VALUES (:name, :slug, :status)", [
            'name' => $data['name'],
            'slug' => $data['slug'],
            'status' => $data['status']
        ]);
        return true;
    }

    /**
     * Update an existing tag.
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public static function update($id, $data)
    {
        Database::query("UPDATE blog_tags SET name = :name, slug = :slug, status = :status WHERE id = :id", [
            'id' => $id,
            'name' => $data['name'],
            'slug' => $data['slug'],
            'status' => $data['status']
        ]);
        return true;
    }

    /**
     * Delete a tag.
     *
     * @param int $id
     * @return bool
     */
    public static function delete($id)
    {
        // Also remove associations in the pivot table
        Database::query("DELETE FROM blog_post_tags WHERE tag_id = :id", ['id' => $id]);
        Database::query("DELETE FROM blog_tags WHERE id = :id", ['id' => $id]);
        return true;
    }
}
