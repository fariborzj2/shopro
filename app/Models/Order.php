<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Order
{
    /**
     * Fetches all orders with user information, ordered by the most recent.
     * Includes pagination support.
     *
     * @param int $page The current page number for pagination.
     * @param int $perPage The number of records to show per page.
     * @return array An array containing the list of orders and total count.
     */
    public static function findAll(int $page = 1, int $perPage = 15): array
    {
        $offset = ($page - 1) * $perPage;
        $pdo = Database::getConnection();

        // Query to get the total count of orders for pagination
        $totalSql = "SELECT COUNT(id) FROM orders";
        $totalStmt = $pdo->query($totalSql);
        $totalOrders = $totalStmt->fetchColumn();

        // Query to get the paginated list of orders with user names
        $sql = "
            SELECT
                o.id,
                o.order_code,
                o.amount,
                o.status,
                o.order_time,
                u.name as user_name
            FROM
                orders o
            LEFT JOIN
                users u ON o.user_id = u.id
            ORDER BY
                o.order_time DESC
            LIMIT :limit OFFSET :offset
        ";

        $stmt = $pdo->prepare($sql);

        // Bind parameters securely to prevent SQL injection
        $stmt->bindParam(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            'orders' => $orders,
            'total' => $totalOrders,
            'page' => $page,
            'perPage' => $perPage
        ];
    }

    /**
     * Creates a new order in the database.
     *
     * @param array $data The data for the new order.
     * @return int The ID of the newly created order.
     */
    public static function create(array $data): int
    {
        $pdo = Database::getConnection();
        $sql = "
            INSERT INTO orders (
                user_id, product_id, category_id, amount, status,
                custom_fields_data, order_code, quantity
            ) VALUES (
                :user_id, :product_id, :category_id, :amount, :status,
                :custom_fields_data, :order_code, :quantity
            )
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':user_id' => $data['user_id'],
            ':product_id' => $data['product_id'],
            ':category_id' => $data['category_id'],
            ':amount' => $data['amount'],
            ':status' => $data['status'],
            ':custom_fields_data' => $data['custom_fields_data'],
            ':order_code' => $data['order_code'],
            ':quantity' => $data['quantity'] ?? 1, // Default quantity to 1 if not provided
        ]);
        return (int)$pdo->lastInsertId();
    }
        public static function update(int $id, array $data): bool
    {
        $pdo = Database::getConnection();
        $fields = [];
        foreach ($data as $key => $value) {
            $fields[] = "$key = :$key";
        }
        $sql = "UPDATE orders SET " . implode(', ', $fields) . " WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $data['id'] = $id;
        return $stmt->execute($data);
    }
}
