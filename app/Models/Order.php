<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Order
{
    /**
     * Get all orders from the database, including user and product names.
     *
     * @return array
     */
    public static function all()
    {
        $sql = "SELECT o.*, u.name as user_name, p.name_fa as product_name
                FROM orders o
                LEFT JOIN users u ON o.user_id = u.id
                LEFT JOIN products p ON o.product_id = p.id
                ORDER BY o.order_time DESC";
        $stmt = Database::query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Find a single order by its ID, with all related details.
     *
     * @param int $id
     * @return mixed
     */
    public static function find($id)
    {
        $sql = "SELECT o.*, u.name as user_name, u.mobile as user_mobile, p.name_fa as product_name, c.name_fa as category_name
                FROM orders o
                LEFT JOIN users u ON o.user_id = u.id
                LEFT JOIN products p ON o.product_id = p.id
                LEFT JOIN categories c ON o.category_id = c.id
                WHERE o.id = :id";
        $stmt = Database::query($sql, ['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Update the status of an order.
     *
     * @param int $id
     * @param string $status
     * @return bool
     */
    public static function updateStatus($id, $status)
    {
        $sql = "UPDATE orders SET status = :status WHERE id = :id";
        Database::query($sql, [
            'id' => $id,
            'status' => $status
        ]);
        return true;
    }
}
