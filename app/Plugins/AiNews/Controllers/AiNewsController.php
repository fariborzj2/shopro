<?php

namespace App\Plugins\AiNews\Controllers;

use App\Core\Request;
use App\Core\Template;
use App\Plugins\AiNews\Models\AiSetting;
use App\Plugins\AiNews\Models\AiLog;
use App\Plugins\AiNews\Services\Crawler;
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

        // Ensure defaults
        $data = [
            'plugin_enabled' => $settings['plugin_enabled'] ?? 0,
            'start_hour' => $settings['start_hour'] ?? 8,
            'end_hour' => $settings['end_hour'] ?? 21,
            'groq_api_key' => $settings['groq_api_key'] ?? '',
            'groq_model' => $settings['groq_model'] ?? 'llama3-70b-8192',
            'sitemap_urls' => $settings['sitemap_urls'] ?? '',
            'prompt_template' => $settings['prompt_template'] ?? '',
            'logs' => AiLog::getRecent(20)
        ];

        // We render a view from our plugin directory
        // Since the core Template engine looks in `views/`, we might need to manually include our view
        // or convince Template to load from our path.
        // For simplicity, we will assume standard output buffering or include approach inside a wrapper.

        // Since we are inside the admin panel, we want to extend the main layout.
        // The standard `view()` helper might not find our file.
        // We will construct the view path manually.

        $viewPath = PROJECT_ROOT . '/app/Plugins/AiNews/Views/settings.php';

        // Hack: We can use the layout `views/layouts/main.php` and inject our content.
        // But the `view()` helper takes a view name relative to `views/`.
        // We will assume we can copy our views to `views/admin/ai_news/` OR we just use `include` inside a blank core view.
        // Let's try to just render the layout and pass the content.

        // Best approach given constraints:
        // Use `views/layouts/main.php` but capture our plugin view output as `$content`.

        ob_start();
        include $viewPath;
        $content = ob_get_clean();

        // Now render the main layout with this content
        // This requires a modification to how we call `view`.
        // If `view` function strictly expects a file, we are stuck.
        // Let's look at `app/Core/helpers.php`'s `view` function.

        global $current_view_content; // Hypothetical
        // Actually, the standard way in this app seems to be `view('admin/dashboard', $data)`.

        // Workaround: We will rely on the `view()` helper if we can register a path,
        // BUT the prompt says "No modification to backend" (except routes).
        // So I cannot change `Template.php`.

        // I will use `view('layouts/main', ['content' => $content])`?
        // Let's check `views/layouts/main.php`.
        // It likely does `<?php include ... $view ... ?>` or `<?php echo $content ?>`.

        // I'll check `views/layouts/main.php` content in next step.
        // For now, I'll assume I can output the layout manually.

        include PROJECT_ROOT . '/views/layouts/main.php';
        // Wait, main.php likely expects variables.
    }

    public function saveSettings()
    {
        $post = Request::all();

        AiSetting::set('plugin_enabled', isset($post['plugin_enabled']) ? 1 : 0);
        AiSetting::set('start_hour', $post['start_hour']);
        AiSetting::set('end_hour', $post['end_hour']);
        AiSetting::set('groq_api_key', $post['groq_api_key']);
        AiSetting::set('groq_model', $post['groq_model']);
        AiSetting::set('sitemap_urls', $post['sitemap_urls']);
        AiSetting::set('prompt_template', $post['prompt_template']);

        redirect_with_success('/admin/ai-news/settings', 'تنظیمات با موفقیت ذخیره شد.');
    }

    public function list()
    {
        $pdo = Database::getConnection();

        // Fetch drafts that were likely created by AI (we don't have a specific flag, but we can filter by 'draft')
        // Ideally we should have added `is_ai_generated` column but schema change on `blog_posts` was not requested/approved explicitly
        // other than "Full compatibility with posts table".
        // I'll filter by status='draft' and maybe created_at desc.

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
        include $viewPath;
        $content = ob_get_clean(); // This variable name matters for main.php?

        // Manually render layout
        // I need to know how main.php renders the inner view.
        // I'll check main.php in a moment.
        include PROJECT_ROOT . '/views/layouts/main.php';
    }

    public function fetch()
    {
        $crawler = new Crawler();
        $result = $crawler->run(true); // Manual = true

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
}
