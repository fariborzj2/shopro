<?php

namespace App\Plugins\AiNews\Controllers;

use App\Core\Request;
use App\Core\Template;
use App\Plugins\AiNews\Models\AiSetting;
use App\Plugins\AiNews\Models\AiLog;
use App\Plugins\AiNews\Services\Crawler;
use App\Plugins\AiNews\Services\GroqService;
use App\Core\Database;
use PDO;

class AiNewsController
{
    private $template;

    public function __construct()
    {
        $this->template = new Template();
    }

    public function settings()
    {
        $settings = AiSetting::getAll();

        $data = [
            'plugin_enabled' => $settings['plugin_enabled'] ?? 0,
            'start_hour' => $settings['start_hour'] ?? 8,
            'end_hour' => $settings['end_hour'] ?? 21,
            'execution_interval' => $settings['execution_interval'] ?? 1,
            'max_posts_per_cycle' => $settings['max_posts_per_cycle'] ?? 5,
            'groq_api_key' => $settings['groq_api_key'] ?? '',
            'groq_model' => $settings['groq_model'] ?? 'llama-3.3-70b-versatile',
            'sitemap_urls' => $settings['sitemap_urls'] ?? '',
            'prompt_template' => $settings['prompt_template'] ?? '',
            'logs' => AiLog::getRecent(20)
        ];

        $viewPath = PROJECT_ROOT . '/app/Plugins/AiNews/Views/settings.php';

        ob_start();
        extract($data);
        include $viewPath;
        $content = ob_get_clean();

        include PROJECT_ROOT . '/views/layouts/main.php';
    }

    public function saveSettings()
    {
        $post = Request::all();

        AiSetting::set('plugin_enabled', isset($post['plugin_enabled']) ? 1 : 0);
        AiSetting::set('start_hour', $post['start_hour']);
        AiSetting::set('end_hour', $post['end_hour']);
        AiSetting::set('execution_interval', $post['execution_interval'] ?? 1);
        AiSetting::set('max_posts_per_cycle', $post['max_posts_per_cycle'] ?? 5);
        AiSetting::set('groq_api_key', $post['groq_api_key']);
        AiSetting::set('groq_model', $post['groq_model']);
        AiSetting::set('sitemap_urls', $post['sitemap_urls']);
        AiSetting::set('prompt_template', $post['prompt_template']);

        redirect_with_success('/admin/ai-news/settings', 'تنظیمات با موفقیت ذخیره شد.');
    }

    public function testConnection()
    {
        $groq = new GroqService();
        $result = $groq->test();

        header('Content-Type: application/json');
        echo json_encode($result);
        exit;
    }

    public function list()
    {
        $pdo = Database::getConnection();

        $stmt = $pdo->query("
            SELECT * FROM blog_posts
            WHERE status = 'draft'
            ORDER BY created_at DESC
            LIMIT 50
        ");
        $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $data = ['posts' => $posts];
        $viewPath = PROJECT_ROOT . '/app/Plugins/AiNews/Views/list.php';

        ob_start();
        extract($data);
        include $viewPath;
        $content = ob_get_clean();

        include PROJECT_ROOT . '/views/layouts/main.php';
    }

    public function fetch()
    {
        $crawler = new Crawler();
        $result = $crawler->run(true);

        if ($result['status'] === 'success') {
            redirect_with_success('/admin/ai-news/list', "ربات با موفقیت اجرا شد. {$result['created']} پست ایجاد شد.");
        } else {
            redirect_back_with_error("خطا در اجرا: " . ($result['message'] ?? $result['error']));
        }
    }

    public function approve($id)
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("UPDATE blog_posts SET status = 'published', published_at = NOW() WHERE id = :id");
        $stmt->execute(['id' => $id]);

        redirect_with_success('/admin/ai-news/list', 'پست با موفقیت منتشر شد.');
    }

    public function delete($id)
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("DELETE FROM blog_posts WHERE id = :id");
        $stmt->execute(['id' => $id]);

        redirect_with_success('/admin/ai-news/list', 'پست حذف شد.');
    }

    public function clearHistory()
    {
        $pdo = Database::getConnection();
        $pdo->exec("TRUNCATE TABLE ai_news_history");
        redirect_with_success('/admin/ai-news/settings', 'تاریخچه لینک‌های پردازش شده پاک شد.');
    }

    public function clearLogs()
    {
        $pdo = Database::getConnection();
        $pdo->exec("TRUNCATE TABLE ai_news_logs");
        redirect_with_success('/admin/ai-news/settings', 'لاگ‌های سیستم پاک شد.');
    }
}
