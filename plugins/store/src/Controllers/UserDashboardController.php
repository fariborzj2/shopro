<?php

namespace Store\Controllers;

use Store\Models\Order;
use Store\Models\Transaction;
use Store\Models\UserMessage;
use Store\Models\UserLoginLog;
use App\Models\User;
use App\Core\Template;
use App\Core\Database;
use App\Models\Setting;

class UserDashboardController
{
    private $template;
    private $themeDir;

    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        // Protect all dashboard routes
        if (!isset($_SESSION['user_id'])) {
            header('Location: /'); // Redirect home if not logged in
            exit();
        }

        // Determine Theme Path
        // 1. Check Settings for active theme
        $settings = Setting::getAll();
        $theme = $settings['default_theme'] ?? 'template-1';

        // 2. Resolve Path
        // The dashboard templates are located inside the main theme directory: views/site/themes/{theme}/dashboard/
        // We set the Template engine root to the specific dashboard folder for convenience, OR keep it at theme root and prefix 'dashboard/'

        // Fix: Point to correct directory based on `template-1`.
        // Path: views/site/themes/template-1/dashboard

        // We use PROJECT_ROOT . '/views/site/themes/' . $theme . '/dashboard'
        $this->themeDir = PROJECT_ROOT . '/views/site/themes/' . $theme . '/dashboard';

        // Verify directory exists, fallback to template-1 if not
        if (!is_dir($this->themeDir)) {
             $this->themeDir = PROJECT_ROOT . '/views/site/themes/template-1/dashboard';
        }

        $this->template = new Template($this->themeDir);
    }

    private function render($view, $data = [])
    {
        // Add common data for the layout
        $data['unreadMessages'] = UserMessage::countUnread($_SESSION['user_id']);
        $data['user_name'] = $_SESSION['user_name'] ?? 'کاربر';
        $data['user_mobile'] = $_SESSION['user_mobile'] ?? '';

        // Render content view first
        $content = $this->template->render($view, $data);

        // Render layout with content injected
        // Note: layout.tpl is also in the same dashboard directory
        return $this->template->render('layout', array_merge($data, ['content' => $content]));
    }

    public function index()
    {
        // Dashboard Home
        $userId = $_SESSION['user_id'];
        $db = Database::getConnection();

        // Recent Orders
        $stmt = $db->prepare("SELECT * FROM orders WHERE user_id = :uid ORDER BY id DESC LIMIT 5");
        $stmt->execute(['uid' => $userId]);
        $recentOrders = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $lastOrder = $recentOrders[0] ?? null;

        // Active Orders Count (pending, processing, etc. - anything not completed/cancelled)
        $stmt = $db->prepare("SELECT COUNT(*) FROM orders WHERE user_id = :uid AND order_status NOT IN ('completed', 'cancelled')");
        $stmt->execute(['uid' => $userId]);
        $activeOrdersCount = $stmt->fetchColumn();

        // Recent Logs
        $recentLogs = UserLoginLog::findAllByUserId($userId, 5);

        echo $this->render('home', [
            'pageTitle' => 'پیشخوان',
            'activePage' => 'home',
            'lastOrder' => $lastOrder,
            'recentOrders' => $recentOrders,
            'activeOrdersCount' => $activeOrdersCount,
            'recentLogs' => $recentLogs
        ]);
    }

    public function orders()
    {
        $orders = Order::findAllBy('user_id', $_SESSION['user_id']);
        // Sort descending
        if (is_array($orders)) {
            usort($orders, function($a, $b) {
                // Handle both object and array
                $idA = is_object($a) ? $a->id : $a['id'];
                $idB = is_object($b) ? $b->id : $b['id'];
                return $idB - $idA;
            });
        }

        echo $this->render('orders', [
            'pageTitle' => 'سفارش‌های من',
            'activePage' => 'orders',
            'orders' => $orders,
            'breadcrumbs' => ['داشبورد', 'سفارش‌ها']
        ]);
    }

    public function orderDetails($id)
    {
        $order = Order::find($id);

        if (!$order || $order->user_id != $_SESSION['user_id']) {
            http_response_code(404);
            // Render 404 within dashboard layout or just simple message
            echo $this->render('home', ['pageTitle' => 'سفارش یافت نشد']); // Fallback
            return;
        }

        $transaction = Transaction::findBy('order_id', $id);

        echo $this->render('order_details', [
            'pageTitle' => 'جزئیات سفارش #' . $order->order_code,
            'activePage' => 'orders',
            'order' => $order,
            'transaction' => $transaction,
            'breadcrumbs' => ['داشبورد', 'سفارش‌ها', $order->order_code]
        ]);
    }

    public function messages()
    {
        $messages = UserMessage::findAllByUserId($_SESSION['user_id']);

        echo $this->render('messages', [
            'pageTitle' => 'پیام‌ها',
            'activePage' => 'messages',
            'messages' => $messages,
            'breadcrumbs' => ['داشبورد', 'پیام‌ها']
        ]);
    }

    public function viewMessage($id)
    {
         // Placeholder
    }

    public function profile()
    {
        $user = User::find($_SESSION['user_id']);
        if (is_object($user)) {
            $user = (array) $user;
        }

        echo $this->render('profile', [
            'pageTitle' => 'پروفایل و امنیت',
            'activePage' => 'profile',
            'user' => $user,
            'breadcrumbs' => ['داشبورد', 'پروفایل']
        ]);
    }

    public function updateProfile()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
             $name = $_POST['name'] ?? '';

             // Update logic (only name supported by schema currently)
             $db = Database::getConnection();
             $stmt = $db->prepare("UPDATE users SET name = :name WHERE id = :id");
             $stmt->execute(['name' => $name, 'id' => $_SESSION['user_id']]);

             $_SESSION['user_name'] = $name;

             header('Location: /dashboard/profile?success_msg=اطلاعات با موفقیت بروز شد.');
             exit;
        }
    }

    public function logs()
    {
        $logs = UserLoginLog::findAllByUserId($_SESSION['user_id']);

        echo $this->render('logs', [
            'pageTitle' => 'گزارش ورودها',
            'activePage' => 'logs',
            'logs' => $logs,
            'breadcrumbs' => ['داشبورد', 'گزارش ورودها']
        ]);
    }

    public function logoutAll()
    {
        header('Location: /dashboard/profile?success_msg=سایر نشست‌ها بسته شدند (شبیه‌سازی).');
    }
}
