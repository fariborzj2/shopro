<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Product
{
    /**
     * Get a paginated list of products from the database.
     *
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public static function paginated($limit, $offset)
    {
        $sql = "SELECT p.*, c.name_fa as category_name
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                ORDER BY p.position ASC, p.id DESC
                LIMIT :limit OFFSET :offset";

        $pdo = Database::getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get the total count of products.
     *
     * @return int
     */
    public static function count()
    {
        $stmt = Database::query("SELECT COUNT(id) FROM products");
        return (int) $stmt->fetchColumn();
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
        $allowedColumns = ['status', 'category_id'];
        if (!in_array($column, $allowedColumns)) {
            throw new \Exception("Invalid column name provided to findAllBy.");
        }

        $sql = "SELECT * FROM products WHERE {$column} = :value";
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
     * Create a new product.
     *
     * @param array $data
     * @return bool
     */
    public static function create($data)
    {
        $sql = "INSERT INTO products (category_id, name_fa, name_en, price, dollar_price, old_price, status, position)
                VALUES (:category_id, :name_fa, :name_en, :price, :dollar_price, :old_price, :status, :position)";
        Database::query($sql, [
            'category_id' => $data['category_id'],
            'name_fa' => $data['name_fa'],
            'name_en' => $data['name_en'] ?? null,
            'price' => $data['price'],
            'dollar_price' => $data['dollar_price'] ?? null,
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
                SET category_id = :category_id, name_fa = :name_fa, name_en = :name_en, price = :price, dollar_price = :dollar_price, old_price = :old_price, status = :status, position = :position
                WHERE id = :id";
        Database::query($sql, [
            'id' => $id,
            'category_id' => $data['category_id'],
            'name_fa' => $data['name_fa'],
            'name_en' => $data['name_en'] ?? null,
            'price' => $data['price'],
            'dollar_price' => $data['dollar_price'] ?? null,
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

    /**
     * Update the display order of products.
     *
     * @param array $ids
     * @return bool
     */
    public static function updateOrder(array $ids)
    {
        if (empty($ids)) {
            return false;
        }

        $pdo = Database::getConnection();
        $case_sql = "";
        $params = [];
        foreach ($ids as $position => $id) {
            $case_sql .= "WHEN ? THEN ? ";
            $params[] = (int) $id;
            $params[] = $position;
        }

        $id_list = implode(',', array_fill(0, count($ids), '?'));

        $sql = "UPDATE products SET position = CASE id {$case_sql} END WHERE id IN ({$id_list})";

        // Add the IDs for the IN clause to the params array
        foreach ($ids as $id) {
            $params[] = (int) $id;
        }

        Database::query($sql, $params);
        return true;
    }

    /**
     * Update the Toman price of all products based on their dollar price and a new exchange rate.
     *
     * @param float $new_rate
     * @return bool
     */
    public static function updateAllTomanPrices($new_rate)
    {
        $sql = "UPDATE products SET price = dollar_price * :rate WHERE dollar_price IS NOT NULL";

        try {
            Database::query($sql, ['rate' => $new_rate]);
            return true;
        } catch (\Exception $e) {
            // Log the error in a real application
            return false;
        }
    }
}
