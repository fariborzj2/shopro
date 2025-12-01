<?php
// app/Plugins/AiNews/Views/settings.php
$title = 'تنظیمات دستیار هوشمند';
?>

<div class="flex flex-col gap-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">تنظیمات دستیار هوشمند (AI News)</h1>
        <form action="/admin/ai-news/fetch" method="POST" onsubmit="return confirm('آیا مطمئن هستید؟ این عملیات ممکن است زمان‌بر باشد.');">
            <?php csrf_field(); ?>
            <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                اجرای دستی ربات
            </button>
        </form>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Settings Form -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-card p-6">
            <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4 border-b dark:border-gray-700 pb-2">پیکربندی</h2>

            <form action="/admin/ai-news/settings/save" method="POST" class="space-y-4">
                <?php csrf_field(); ?>

                <!-- Enable Toggle -->
                <div class="flex items-center justify-between bg-gray-50 dark:bg-gray-700/50 p-3 rounded-xl">
                    <span class="text-gray-700 dark:text-gray-200 font-medium">وضعیت پلاگین</span>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="plugin_enabled" value="1" class="sr-only peer" <?php echo $data['plugin_enabled'] ? 'checked' : ''; ?>>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 dark:peer-focus:ring-primary-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary-600"></div>
                    </label>
                </div>

                <!-- Operating Hours -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">ساعت شروع</label>
                        <select name="start_hour" class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white rounded-lg p-2.5">
                            <?php for($i=0; $i<24; $i++): ?>
                                <option value="<?php echo $i; ?>" <?php echo $data['start_hour'] == $i ? 'selected' : ''; ?>><?php echo sprintf('%02d:00', $i); ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">ساعت پایان</label>
                        <select name="end_hour" class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white rounded-lg p-2.5">
                            <?php for($i=0; $i<24; $i++): ?>
                                <option value="<?php echo $i; ?>" <?php echo $data['end_hour'] == $i ? 'selected' : ''; ?>><?php echo sprintf('%02d:00', $i); ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>

                <!-- Groq Settings -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Groq API Key</label>
                    <div class="flex gap-2">
                        <input type="password" name="groq_api_key" value="<?php echo htmlspecialchars($data['groq_api_key']); ?>" class="flex-1 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white rounded-lg p-2.5" placeholder="gsk_...">
                        <button type="button" onclick="testConnection()" class="bg-gray-500 hover:bg-gray-600 text-white px-3 rounded-lg text-sm whitespace-nowrap transition-colors">تست اتصال</button>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Groq Model</label>
                    <input type="text" name="groq_model" value="<?php echo htmlspecialchars($data['groq_model']); ?>" class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white rounded-lg p-2.5">
                </div>

                <!-- Sitemaps -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">آدرس‌های نقشه سایت (هر خط یک آدرس)</label>
                    <textarea name="sitemap_urls" rows="4" class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white rounded-lg p-2.5 text-sm dir-ltr text-left"><?php echo htmlspecialchars($data['sitemap_urls']); ?></textarea>
                </div>

                <!-- Prompt Template -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">قالب پرامپت هوش مصنوعی</label>
                    <textarea name="prompt_template" rows="6" class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white rounded-lg p-2.5 text-xs font-mono dir-ltr text-left"><?php echo htmlspecialchars($data['prompt_template']); ?></textarea>
                    <p class="text-xs text-gray-500 mt-1">متغیرهای مجاز: {{title}}, {{content}}</p>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg px-5 py-2.5 text-center transition-colors">
                        ذخیره تنظیمات
                    </button>
                </div>
            </form>
        </div>

        <!-- Logs Viewer -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-card p-6 flex flex-col h-full">
            <div class="flex justify-between items-center mb-4 border-b dark:border-gray-700 pb-2">
                <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200">آخرین فعالیت‌ها</h2>
                <div class="flex gap-2">
                    <form action="/admin/ai-news/clear-history" method="POST" onsubmit="return confirm('آیا مطمئن هستید؟ تمام تاریخچه لینک‌های پردازش شده حذف می‌شود و ممکن است لینک‌های تکراری دوباره پردازش شوند.');">
                        <?php csrf_field(); ?>
                        <button type="submit" class="text-xs text-red-500 hover:text-red-700 transition-colors" title="حذف تاریخچه لینک‌ها">
                            پاکسازی هیستوری
                        </button>
                    </form>
                    <span class="text-gray-300 dark:text-gray-600">|</span>
                    <form action="/admin/ai-news/clear-logs" method="POST" onsubmit="return confirm('آیا مطمئن هستید؟ تمام لاگ‌های سیستم حذف می‌شوند.');">
                        <?php csrf_field(); ?>
                        <button type="submit" class="text-xs text-red-500 hover:text-red-700 transition-colors" title="حذف تمام لاگ‌ها">
                            پاکسازی لاگ‌ها
                        </button>
                    </form>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto max-h-[600px] space-y-3 pr-2 custom-scrollbar">
                <?php if (empty($data['logs'])): ?>
                    <p class="text-gray-500 text-center py-8">هنوز فعالیتی ثبت نشده است.</p>
                <?php else: ?>
                    <?php foreach ($data['logs'] as $log): ?>
                        <div class="border-l-4 <?php echo $log['status'] === 'success' ? 'border-green-500' : 'border-red-500'; ?> bg-gray-50 dark:bg-gray-700/30 p-3 rounded-r-lg">
                            <div class="flex justify-between items-start mb-1">
                                <span class="text-xs font-mono text-gray-500 dark:text-gray-400" dir="ltr"><?php echo $log['run_time']; ?></span>
                                <span class="text-xs px-2 py-0.5 rounded-full <?php echo $log['status'] === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                    <?php echo $log['status']; ?>
                                </span>
                            </div>
                            <div class="text-sm text-gray-800 dark:text-gray-200 mb-1">
                                <span class="font-bold">دریافت شده:</span> <?php echo $log['fetched_count']; ?> |
                                <span class="font-bold">ایجاد شده:</span> <?php echo $log['created_count']; ?>
                            </div>
                            <?php if ($log['details']): ?>
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-2 bg-white dark:bg-gray-900 p-2 rounded border border-gray-200 dark:border-gray-700 overflow-x-auto whitespace-pre-wrap">
                                    <?php echo htmlspecialchars($log['details']); ?>
                                </div>
                            <?php endif; ?>
                            <?php if ($log['error_message']): ?>
                                <div class="text-xs text-red-500 mt-2 font-mono bg-red-50 dark:bg-red-900/10 p-2 rounded">
                                    <?php echo htmlspecialchars($log['error_message']); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
.dir-ltr { direction: ltr; }
.custom-scrollbar::-webkit-scrollbar { width: 6px; }
.custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
.custom-scrollbar::-webkit-scrollbar-thumb { background-color: rgba(156, 163, 175, 0.5); border-radius: 20px; }
</style>

<script>
function testConnection() {
    const btn = document.querySelector('button[onclick="testConnection()"]');
    const originalText = btn.innerText;
    btn.innerText = 'در حال بررسی...';
    btn.disabled = true;

    fetch('/admin/ai-news/test-connection', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert('اتصال با موفقیت برقرار شد!');
        } else {
            alert('خطا در اتصال: ' + data.message);
        }
    })
    .catch(error => {
        alert('خطای شبکه رخ داد.');
        console.error(error);
    })
    .finally(() => {
        btn.innerText = originalText;
        btn.disabled = false;
    });
}
</script>
