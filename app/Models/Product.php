<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Product
{
    /**
     * Get all products from the database, including category name.
     *
     * @return array
     */
    public static function all()
    {
        $sql = "SELECT p.*, c.name_fa as category_name
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                ORDER BY p.position ASC, p.id DESC";
        $stmt = Database::query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Find a product by its ID.
     *
     * @param int $id
     * @return mixed
     */
    public static function find($id)
    {
        $stmt = Database::query("SELECT * FROM products WHERE id = :id", ['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Create a new product.
     *
     * @param array $data
     * @return bool
     */
    public static function create($data)
    {
        $sql = "INSERT INTO products (category_id, name_fa, name_en, price, old_price, status, position)
                VALUES (:category_id, :name_fa, :name_en, :price, :old_price, :status, :position)";
        Database::query($sql, [
            'category_id' => $data['category_id'],
            'name_fa' => $data['name_fa'],
            'name_en' => $data['name_en'],
            'price' => $data['price'],
            'old_price' => $data['old_price'] ?: null,
            'status' => $data['status'],
            'position' => $data['position'] ?? 0
        ]);
        return true;
    }

    /**
     * Update an existing product.
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public static function update($id, $data)
    {
        $sql = "UPDATE products
                SET category_id = :category_id, name_fa = :name_fa, name_en = :name_en, price = :price, old_price = :old_price, status = :status, position = :position
                WHERE id = :id";
        Database::query($sql, [
            'id' => $id,
            'category_id' => $data['category_id'],
            'name_fa' => $data['name_fa'],
            'name_en' => $data['name_en'],
            'price' => $data['price'],
            'old_price' => $data['old_price'] ?: null,
            'status' => $data['status'],
            'position' => $data['position'] ?? 0
        ]);
        return true;
    }

    /**
     * Delete a product.
     *
     * @param int $id
     * @return bool
     */
    public static function delete($id)
    {
        Database::query("DELETE FROM products WHERE id = :id", ['id' => $id]);
        return true;
    }
}
