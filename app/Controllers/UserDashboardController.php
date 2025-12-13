<?php

namespace App\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Core\Database;

class UserDashboardController
{
    protected $userId;
    protected $user;

    public function __construct()
    {
        // Auth Check
        if (!isset($_SESSION['user_id'])) {
            header('Location: /?auth_required=1');
            exit();
        }

        $this->userId = $_SESSION['user_id'];

        // Fetch fresh user data
        $this->user = $this->findUserById($this->userId);

        // Update session if user data changed (optional, but good for consistency)
        if ($this->user) {
            $_SESSION['user_name'] = $this->user->name;
            $_SESSION['user_mobile'] = $this->user->mobile;
        }
    }

    /**
     * Dashboard Overview
     */
    public function index()
    {
        // Stats
        $activeOrdersCount = $this->countActiveOrders($this->userId);
        $totalSpend = $this->calculateTotalSpend($this->userId);
        $recentOrders = $this->getRecentOrders($this->userId, 5);

        // Mock data for unread messages (until a Ticket system is implemented)
        $unreadMessages = 0;

        $this->render('home', [
            'pageTitle' => 'پیشخوان',
            'activePage' => 'home',
            'activeOrdersCount' => $activeOrdersCount,
            'totalSpend' => $totalSpend,
            'recentOrders' => $recentOrders,
            'unreadMessages' => $unreadMessages,
            'breadcrumbs' => ['داشبورد']
        ]);
    }

    /**
     * Orders List
     */
    public function orders()
    {
        $orders = $this->getAllOrders($this->userId);

        $this->render('orders', [
            'pageTitle' => 'سفارش‌های من',
            'activePage' => 'orders',
            'orders' => $orders,
            'breadcrumbs' => ['داشبورد', 'سفارش‌های من']
        ]);
    }

    /**
     * Order Details
     */
    public function orderDetails($id)
    {
        // Use a direct query with JOIN to ensure we get product details just like in the list view
        $db = Database::getConnection();
        $stmt = $db->prepare("
            SELECT o.*, p.name_fa as product_name, p.image_url as product_image
            FROM orders o
            LEFT JOIN products p ON o.product_id = p.id
            WHERE o.id = :id
            LIMIT 1
        ");
        $stmt->execute([':id' => $id]);
        $order = $stmt->fetch(\PDO::FETCH_ASSOC);

        // Security check: Order must belong to user
        if (!$order || $order['user_id'] != $this->userId) {
            header('Location: /dashboard/orders');
            exit();
        }

        // Decode Custom Fields
        $customFields = [];
        if (!empty($order['custom_fields_data'])) {
            $customFields = json_decode($order['custom_fields_data'], true) ?? [];
        }

        $this->render('order_details', [
            'pageTitle' => 'جزئیات سفارش #' . $order['order_code'],
            'activePage' => 'orders',
            'order' => $order,
            'customFields' => $customFields,
            'breadcrumbs' => ['داشبورد', 'سفارش‌های من', '#' . $order['order_code']]
        ]);
    }

    /**
     * Profile Settings
     */
    public function profile()
    {
        $this->render('profile', [
            'pageTitle' => 'پروفایل و امنیت',
            'activePage' => 'profile',
            'user' => (array)$this->user,
            'breadcrumbs' => ['داشبورد', 'پروفایل']
        ]);
    }

    /**
     * Update Profile
     */
    public function updateProfile()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');

            if (empty($name)) {
                \redirect_back_with_error('نام و نام خانوادگی نمی‌تواند خالی باشد.');
            }

            // Simple update query
            $db = Database::getConnection();
            $stmt = $db->prepare("UPDATE users SET name = :name, email = :email WHERE id = :id");
            $stmt->execute([
                ':name' => $name,
                ':email' => $email,
                ':id' => $this->userId
            ]);

            // Update session
            $_SESSION['user_name'] = $name;

            \redirect_with_success('/dashboard/profile', 'اطلاعات با موفقیت بروزرسانی شد.');
        }
    }

    /**
     * Messages / Tickets (UI Only)
     */
    public function messages()
    {
        $this->render('messages', [
            'pageTitle' => 'پیام‌ها و پشتیبانی',
            'activePage' => 'messages',
            'breadcrumbs' => ['داشبورد', 'پیام‌ها']
        ]);
    }

    /**
     * Activity Logs (UI Only)
     */
    public function logs()
    {
        $this->render('logs', [
            'pageTitle' => 'گزارش فعالیت‌ها',
            'activePage' => 'logs',
            'breadcrumbs' => ['داشبورد', 'گزارش فعالیت‌ها']
        ]);
    }

    // ------------------------------------------------------------------
    // Helpers
    // ------------------------------------------------------------------

    private function render($view, $data = [])
    {
        extract($data);

        // Path to themes
        $themePath = PROJECT_ROOT . '/storefront/themes/dashboard-pro';

        // Start Output Buffering for Content
        ob_start();
        if (file_exists("$themePath/$view.tpl")) {
            require "$themePath/$view.tpl";
        } else {
            echo "View not found: $view";
        }
        $content = ob_get_clean();

        // Render Layout with Content
        if (file_exists("$themePath/layout.tpl")) {
            require "$themePath/layout.tpl";
        } else {
            echo $content;
        }
    }

    // -- Data Fetching Methods (Direct SQL for now to be robust against missing Model methods) --

    private function findUserById($id) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(\PDO::FETCH_OBJ);
    }

    private function countActiveOrders($userId) {
        $db = Database::getConnection();
        // Active = paid but not completed or cancelled
        $stmt = $db->prepare("SELECT COUNT(*) FROM orders WHERE user_id = :uid AND payment_status = 'paid' AND order_status NOT IN ('completed', 'cancelled')");
        $stmt->execute([':uid' => $userId]);
        return $stmt->fetchColumn();
    }

    private function calculateTotalSpend($userId) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT SUM(final_amount) FROM orders WHERE user_id = :uid AND payment_status = 'paid'");
        $stmt->execute([':uid' => $userId]);
        return $stmt->fetchColumn() ?: 0;
    }

    private function getRecentOrders($userId, $limit = 5) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM orders WHERE user_id = :uid ORDER BY created_at DESC LIMIT :limit");
        $stmt->bindValue(':uid', $userId);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    private function getAllOrders($userId) {
        $db = Database::getConnection();
        // Join with products if possible to get product name (orders table has product_id)
        // Note: The orders table structure in memory says "flattened record with product_id".
        $sql = "SELECT o.*, p.name_fa as product_name
                FROM orders o
                LEFT JOIN products p ON o.product_id = p.id
                WHERE o.user_id = :uid
                ORDER BY o.created_at DESC";
        $stmt = $db->prepare($sql);
        $stmt->execute([':uid' => $userId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
