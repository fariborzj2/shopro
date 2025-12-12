<?php

namespace Store\Models;

use App\Core\Database;
use PDO;

class Order
{
    /**
     * Find an order by its ID (with joined details).
     *
     * @param int $id
     * @return mixed
     */
    public static function find($id)
    {
        $sql = "SELECT o.*,
                       p.name_fa as product_name, p.image_url as product_image,
                       u.name as user_name, u.mobile as user_mobile
                FROM orders o
                LEFT JOIN products p ON o.product_id = p.id
                LEFT JOIN users u ON o.user_id = u.id
                WHERE o.id = :id";
        $stmt = Database::query($sql, ['id' => $id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Create a new order.
     *
     * @param array $data
     * @return bool
     */
    public static function create($data)
    {
        $sql = "INSERT INTO orders (user_id, product_id, amount, quantity, payment_status, order_status, custom_fields_data)
                VALUES (:user_id, :product_id, :amount, :quantity, :payment_status, :order_status, :custom_fields_data)";

        $params = [
            'user_id' => $data['user_id'],
            'product_id' => $data['product_id'],
            'amount' => $data['amount'],
            'quantity' => $data['quantity'] ?? 1,
            'payment_status' => $data['payment_status'] ?? 'unpaid',
            'order_status' => $data['order_status'] ?? 'pending',
            'custom_fields_data' => isset($data['custom_fields_data']) ? json_encode($data['custom_fields_data']) : null
        ];

        Database::query($sql, $params);
        return true;
    }

    /**
     * Update order status.
     *
     * @param int $id
     * @param string $status
     * @return bool
     */
    public static function updateOrderStatus($id, $status)
    {
        $validStatuses = ['pending', 'completed', 'cancelled', 'phishing'];
        if (!in_array($status, $validStatuses)) {
            return false;
        }

        $sql = "UPDATE orders SET order_status = :status WHERE id = :id";
        Database::query($sql, ['id' => $id, 'status' => $status]);
        return true;
    }

    /**
     * Update payment status.
     *
     * @param int $id
     * @param string $status
     * @return bool
     */
    public static function updatePaymentStatus($id, $status)
    {
        $validStatuses = ['unpaid', 'paid', 'failed'];
        if (!in_array($status, $validStatuses)) {
            return false;
        }

        $sql = "UPDATE orders SET payment_status = :status WHERE id = :id";
        Database::query($sql, ['id' => $id, 'status' => $status]);
        return true;
    }

    /**
     * Find the last purchase in a category for a user.
     *
     * @param int $userId
     * @param int $categoryId
     * @return mixed
     */
    public static function findLastPurchaseInCategory($userId, $categoryId)
    {
        $sql = "SELECT o.*
                FROM orders o
                JOIN products p ON o.product_id = p.id
                WHERE o.user_id = :user_id
                  AND p.category_id = :category_id
                  AND o.payment_status = 'paid'
                ORDER BY o.created_at DESC
                LIMIT 1";

        $stmt = Database::query($sql, [
            'user_id' => $userId,
            'category_id' => $categoryId
        ]);

        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Find all orders with filtering.
     *
     * @param int $limit
     * @param int $offset
     * @param string $search
     * @return array
     */
    public static function paginated($limit, $offset, $search = '')
    {
        $sql = "SELECT o.*, u.name as user_name, u.mobile as user_mobile, p.name_fa as product_name
                FROM orders o
                LEFT JOIN users u ON o.user_id = u.id
                LEFT JOIN products p ON o.product_id = p.id
                WHERE 1=1";

        $params = [
            'limit' => $limit,
            'offset' => $offset
        ];

        if (!empty($search)) {
            $sql .= " AND (o.id LIKE :search OR u.name LIKE :search OR u.mobile LIKE :search)";
            $params['search'] = "%$search%";
        }

        $sql .= " ORDER BY o.id DESC LIMIT :limit OFFSET :offset";

        $pdo = Database::getConnection();
        $stmt = $pdo->prepare($sql);

        foreach ($params as $key => $value) {
            $type = is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR;
            $stmt->bindValue($key, $value, $type);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Find all orders with filtering and pagination.
     *
     * @param int $page
     * @param int $limit
     * @param array $filters
     * @return array
     */
    public static function findAll($page = 1, $limit = 15, $filters = [])
    {
        $offset = ($page - 1) * $limit;
        $params = [];
        $where = "WHERE 1=1";

        if (!empty($filters['search'])) {
            $where .= " AND (o.id LIKE :search OR u.name LIKE :search OR u.mobile LIKE :search OR o.order_code LIKE :search)";
            $params['search'] = "%" . $filters['search'] . "%";
        }

        if (!empty($filters['payment_status'])) {
            $where .= " AND o.payment_status = :payment_status";
            $params['payment_status'] = $filters['payment_status'];
        }

        if (!empty($filters['order_status'])) {
            $where .= " AND o.order_status = :order_status";
            $params['order_status'] = $filters['order_status'];
        }

        // Count query
        $countSql = "SELECT COUNT(o.id)
                     FROM orders o
                     LEFT JOIN users u ON o.user_id = u.id
                     $where";

        $stmtCount = Database::query($countSql, $params);
        $total = (int) $stmtCount->fetchColumn();

        // Data query
        $sql = "SELECT o.*, u.name as user_name, u.mobile as user_mobile, p.name_fa as product_name
                FROM orders o
                LEFT JOIN users u ON o.user_id = u.id
                LEFT JOIN products p ON o.product_id = p.id
                $where
                ORDER BY o.id DESC
                LIMIT :limit OFFSET :offset";

        // We need to bind limit and offset as integers for PDO
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue('limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue('offset', (int)$offset, PDO::PARAM_INT);

        $stmt->execute();
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            'orders' => $orders,
            'total' => $total
        ];
    }

    /**
     * Count total orders.
     *
     * @param string $search
     * @return int
     */
    public static function count($search = '')
    {
        $sql = "SELECT COUNT(o.id)
                FROM orders o
                LEFT JOIN users u ON o.user_id = u.id
                WHERE 1=1";
        $params = [];

        if (!empty($search)) {
            $sql .= " AND (o.id LIKE :search OR u.name LIKE :search OR u.mobile LIKE :search)";
            $params['search'] = "%$search%";
        }

        $stmt = Database::query($sql, $params);
        return (int) $stmt->fetchColumn();
    }
}
