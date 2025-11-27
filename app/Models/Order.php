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
    public static function findAll(int $page = 1, int $perPage = 15, array $filters = []): array
    {
        $offset = ($page - 1) * $perPage;
        $pdo = Database::getConnection();

        // Build Where Clause
        $whereClauses = ["1=1"];
        $params = [];

        if (!empty($filters['search'])) {
            $whereClauses[] = "(o.order_code LIKE :search_order OR u.mobile LIKE :search_mobile)";
            $params[':search_order'] = '%' . $filters['search'] . '%';
            $params[':search_mobile'] = '%' . $filters['search'] . '%';
        }

        if (!empty($filters['payment_status'])) {
            $whereClauses[] = "o.payment_status = :payment_status";
            $params[':payment_status'] = $filters['payment_status'];
        }

        if (!empty($filters['order_status'])) {
            $whereClauses[] = "o.order_status = :order_status";
            $params[':order_status'] = $filters['order_status'];
        }

        $whereSql = implode(' AND ', $whereClauses);

        // Query to get the total count of orders for pagination
        // Must join users if searching by mobile
        $totalSql = "
            SELECT COUNT(o.id)
            FROM orders o
            LEFT JOIN users u ON o.user_id = u.id
            WHERE $whereSql
        ";
        $totalStmt = $pdo->prepare($totalSql);
        $totalStmt->execute($params);
        $totalOrders = $totalStmt->fetchColumn();

        // Query to get the paginated list of orders with user names
        $sql = "
            SELECT
                o.id,
                o.order_code,
                o.amount,
                o.order_status,
                o.payment_status,
                o.order_time,
                u.name as user_name,
                u.mobile as user_mobile
            FROM
                orders o
            LEFT JOIN
                users u ON o.user_id = u.id
            WHERE
                $whereSql
            ORDER BY
                o.order_time DESC
            LIMIT :limit OFFSET :offset
        ";

        $stmt = $pdo->prepare($sql);
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
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
                user_id, product_id, category_id, amount, order_status, payment_status,
                custom_fields_data, order_code, quantity
            ) VALUES (
                :user_id, :product_id, :category_id, :amount, :order_status, :payment_status,
                :custom_fields_data, :order_code, :quantity
            )
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':user_id' => $data['user_id'],
            ':product_id' => $data['product_id'],
            ':category_id' => $data['category_id'],
            ':amount' => $data['amount'],
            ':order_status' => $data['order_status'] ?? 'pending',
            ':payment_status' => $data['payment_status'] ?? 'unpaid',
            ':custom_fields_data' => json_encode($data['custom_fields_data']),
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

    /**
     * Finds a single order by its ID, joining related product and user data.
     *
     * @param int $id The ID of the order to find.
     * @return object|false The order object, or false if not found.
     */
    public static function find(int $id)
    {
        $pdo = Database::getConnection();
        $sql = "
            SELECT
                o.*,
                p.name_fa as product_name,
                u.name as user_name,
                u.mobile as user_mobile
            FROM
                orders o
            JOIN
                products p ON o.product_id = p.id
            JOIN
                users u ON o.user_id = u.id
            WHERE
                o.id = :id
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
    public static function findAllBy(string $column, $value): array
    {
        // Whitelist to prevent SQL injection on column names
        $allowed_columns = ['user_id', 'product_id', 'order_status', 'payment_status'];
        if (!in_array($column, $allowed_columns)) {
            throw new \InvalidArgumentException("Invalid column for searching orders: $column");
        }

        $pdo = Database::getConnection();
        $sql = "
            SELECT
                o.id,
                o.order_code,
                o.amount,
                o.order_status,
                o.payment_status,
                o.order_time,
                p.name_fa AS product_name
            FROM
                orders o
            LEFT JOIN
                products p ON o.product_id = p.id
            WHERE
                o.{$column} = :value
            ORDER BY
                o.order_time DESC
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([':value' => $value]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Update the order status of an order.
     *
     * @param int $id
     * @param string $status
     * @return bool
     */
    public static function updateOrderStatus(int $id, string $status): bool
    {
        $pdo = Database::getConnection();
        $sql = "UPDATE orders SET order_status = :status WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([':status' => $status, ':id' => $id]);
    }

    /**
     * Update the payment status of an order.
     *
     * @param int $id
     * @param string $status
     * @return bool
     */
    public static function updatePaymentStatus(int $id, string $status): bool
    {
        $pdo = Database::getConnection();
        $sql = "UPDATE orders SET payment_status = :status WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([':status' => $status, ':id' => $id]);
    }

    /**
     * Finds the last product a user purchased from a specific category.
     *
     * @param int $userId The ID of the user.
     * @param int $categoryId The ID of the category.
     * @return object|false The product object if a paid order is found, otherwise false.
     */
    public static function findLastPurchaseInCategory(int $userId, int $categoryId)
    {
        $pdo = Database::getConnection();
        $sql = "
            SELECT
                p.id,
                p.name_fa
            FROM
                orders o
            JOIN
                products p ON o.product_id = p.id
            WHERE
                o.user_id = :user_id
                AND o.category_id = :category_id
                AND o.payment_status = 'paid'
            ORDER BY
                o.order_time DESC
            LIMIT 1
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':user_id' => $userId,
            ':category_id' => $categoryId
        ]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
}
