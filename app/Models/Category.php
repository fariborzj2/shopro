<?php

namespace App\Models;

use App\Core\Database;
use PDO;

// Model for the product categories.
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
        return $stmt->fetchAll(PDO::FETCH_OBJ);
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
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Find a single record by a specific column and value.
     *
     * @param string $column
     * @param mixed $value
     * @return mixed|false
     */
    public static function findBy($column, $value)
    {
        // Whitelist columns to prevent SQL injection on column names
        $allowedColumns = ['id', 'slug', 'status'];
        if (!in_array($column, $allowedColumns)) {
            throw new \Exception("Invalid column name provided to findBy.");
        }

        $sql = "SELECT * FROM categories WHERE {$column} = :value LIMIT 1";
        $stmt = Database::query($sql, ['value' => $value]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Find all records by a specific column and value.
     *
     * @param string $column
     * @param mixed $value
     * @param string|null $orderBy
     * @return array
     */
    public static function findAllBy($column, $value, $orderBy = null)
    {
        // Whitelist columns to prevent SQL injection on column names
        $allowedColumns = ['status', 'parent_id', 'slug'];
        if (!in_array($column, $allowedColumns)) {
            throw new \Exception("Invalid column name provided to findAllBy.");
        }

        $sql = "SELECT * FROM categories WHERE {$column} = :value";
        if ($orderBy) {
            // Basic validation for order by to prevent injection
            if (preg_match('/^[a-zA-Z0-9_]+ (ASC|DESC)$/i', $orderBy)) {
                $sql .= " ORDER BY " . $orderBy;
            }
        }

        $stmt = Database::query($sql, ['value' => $value]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Create a new category.
     *
     * @param array $data
     * @return bool
     */
    public static function create($data)
    {
        $db = Database::getConnection();
        $sql = "INSERT INTO categories (parent_id, name_fa, name_en, status, position, slug, short_description, description, meta_title, meta_description, meta_keywords, published_at, image_url, thumbnail_url)
                VALUES (:parent_id, :name_fa, :name_en, :status, :position, :slug, :short_description, :description, :meta_title, :meta_description, :meta_keywords, :published_at, :image_url, :thumbnail_url)";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            'parent_id' => $data['parent_id'] ?: null,
            'name_fa' => $data['name_fa'],
            'name_en' => $data['name_en'] ?? null,
            'status' => $data['status'],
            'position' => $data['position'] ?? 0,
            'slug' => $data['slug'],
            'short_description' => $data['short_description'] ?? null,
            'description' => $data['description'] ?? null,
            'meta_title' => $data['meta_title'] ?? null,
            'meta_description' => $data['meta_description'] ?? null,
            'meta_keywords' => isset($data['meta_keywords']) ? (is_array($data['meta_keywords']) ? json_encode($data['meta_keywords'], JSON_UNESCAPED_UNICODE) : $data['meta_keywords']) : null,
            'published_at' => $data['published_at'] ?? null,
            'image_url' => $data['image_url'] ?? null,
            'thumbnail_url' => $data['thumbnail_url'] ?? null
        ]);
        return $db->lastInsertId();
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
        // Build the query dynamically to avoid overwriting existing images if not provided
        $fields = [
            'parent_id' => $data['parent_id'] ?: null,
            'name_fa' => $data['name_fa'],
            'name_en' => $data['name_en'],
            'status' => $data['status'],
            'position' => $data['position'] ?? 0,
            'slug' => $data['slug'],
            'short_description' => $data['short_description'] ?? null,
            'description' => $data['description'] ?? null,
            'meta_title' => $data['meta_title'] ?? null,
            'meta_description' => $data['meta_description'] ?? null,
            'meta_keywords' => isset($data['meta_keywords']) ? (is_array($data['meta_keywords']) ? json_encode($data['meta_keywords'], JSON_UNESCAPED_UNICODE) : $data['meta_keywords']) : null
        ];

        if (array_key_exists('published_at', $data)) {
            $fields['published_at'] = $data['published_at'];
        }

        // Only add image fields to the update query if they are present in the data
        if (array_key_exists('image_url', $data)) {
            $fields['image_url'] = $data['image_url'];
        }
        if (array_key_exists('thumbnail_url', $data)) {
            $fields['thumbnail_url'] = $data['thumbnail_url'];
        }

        $setClauses = [];
        foreach (array_keys($fields) as $field) {
            $setClauses[] = "{$field} = :{$field}";
        }
        $sql = "UPDATE categories SET " . implode(', ', $setClauses) . " WHERE id = :id";

        $fields['id'] = $id;

        Database::query($sql, $fields);
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

    /**
     * Recursively updates the order of categories.
     *
     * @param array $orderData The nested array of category data from the request.
     * @param int|null $parentId The ID of the parent category for the current level.
     */
    private static function processOrder(array $orderData, $parentId = null)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare('UPDATE categories SET parent_id = :parent_id, position = :position WHERE id = :id');

        foreach ($orderData as $position => $item) {
            $stmt->execute([
                ':parent_id' => $parentId,
                ':position' => $position,
                ':id' => $item['id']
            ]);

            // If the item has children, process them recursively
            if (isset($item['children']) && is_array($item['children'])) {
                self::processOrder($item['children'], $item['id']);
            }
        }
    }

    /**
     * Update the category hierarchy from a nested set of data.
     * This function is transactional.
     *
     * @param array $orderData
     * @return void
     */
    public static function updateOrder(array $orderData)
    {
        $db = Database::getConnection();
        try {
            $db->beginTransaction();
            self::processOrder($orderData);
            $db->commit();
        } catch (\Exception $e) {
            $db->rollBack();
            // Re-throw the exception to be caught by the controller
            throw $e;
        }
    }

    /**
     * Get the IDs of custom fields attached to a category.
     *
     * @param int $categoryId
     * @return array
     */
    public static function getAttachedCustomFieldIds($categoryId)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare('SELECT field_id FROM category_custom_field WHERE category_id = ?');
        $stmt->execute([$categoryId]);
        return $stmt->fetchAll(\PDO::FETCH_COLUMN, 0); // Returns a flat array of IDs
    }

    /**
     * Sync the custom fields for a category.
     * Deletes old associations and inserts new ones.
     *
     * @param int $categoryId
     * @param array $fieldIds
     * @return bool
     */
    public static function syncCustomFields($categoryId, array $fieldIds)
    {
        $db = Database::getConnection();
        $db->beginTransaction();

        try {
            // Delete old associations
            $stmtDelete = $db->prepare('DELETE FROM category_custom_field WHERE category_id = ?');
            $stmtDelete->execute([$categoryId]);

            // Insert new associations if any
            if (!empty($fieldIds)) {
                $sqlInsert = "INSERT INTO category_custom_field (category_id, field_id) VALUES ";
                $placeholders = [];
                $values = [];
                foreach ($fieldIds as $fieldId) {
                    $placeholders[] = '(?, ?)';
                    $values[] = $categoryId;
                    $values[] = (int)$fieldId;
                }
                $sqlInsert .= implode(', ', $placeholders);
                $stmtInsert = $db->prepare($sqlInsert);
                $stmtInsert->execute($values);
            }

            $db->commit();
            return true;
        } catch (\Exception $e) {
            $db->rollBack();
            // In a real app, log the error: error_log($e->getMessage());
            return false;
        }
    }
}
