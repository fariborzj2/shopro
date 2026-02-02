<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">تنظیمات اتوماسیون محتوا</h1>
        <div class="flex items-center gap-2">
            <span class="text-xs text-gray-400">آخرین اجرا: <?php echo !empty($settings['ai_news_last_run']) ? \jdate('Y/m/d H:i', strtotime($settings['ai_news_last_run'])) : 'هرگز'; ?></span>
        </div>
    </div>

    <form action="/admin/ai-news/settings/update" method="POST" class="bg-white rounded-3xl shadow-soft border border-gray-100 p-8 space-y-6">
        <?php csrf_field(); ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">فاصله زمانی اجرا (دقیقه)</label>
                <input type="number" name="ai_news_interval" value="<?php echo $settings['ai_news_interval'] ?? '360'; ?>" class="w-full px-4 py-2 rounded-xl border border-gray-200 outline-none focus:ring-2 focus:ring-primary-500" required>
                <p class="text-[10px] text-gray-400 mt-1">پیش‌فرض ۳۶۰ دقیقه (۶ ساعت)</p>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">حداکثر تعداد انتشار در هر اجرا</label>
                <input type="number" name="ai_news_limit_per_run" value="<?php echo $settings['ai_news_limit_per_run'] ?? '5'; ?>" class="w-full px-4 py-2 rounded-xl border border-gray-200 outline-none focus:ring-2 focus:ring-primary-500" required>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">دسته‌بندی هدف برای مقالات</label>
                <select name="ai_news_target_category" class="w-full px-4 py-2 rounded-xl border border-gray-200 outline-none focus:ring-2 focus:ring-primary-500">
                    <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo $cat['id']; ?>" <?php echo ($settings['ai_news_target_category'] ?? '') == $cat['id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($cat['name_fa']); ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">مدل هوش مصنوعی</label>
                <select name="ai_news_model" class="w-full px-4 py-2 rounded-xl border border-gray-200 outline-none focus:ring-2 focus:ring-primary-500">
                    <option value="gemini-1.5-flash" <?php echo ($settings['ai_news_model'] ?? '') === 'gemini-1.5-flash' ? 'selected' : ''; ?>>Gemini 1.5 Flash (سریع و اقتصادی)</option>
                    <option value="gemini-1.5-pro" <?php echo ($settings['ai_news_model'] ?? '') === 'gemini-1.5-pro' ? 'selected' : ''; ?>>Gemini 1.5 Pro (دقیق و هوشمند)</option>
                    <option value="gemini-2.0-flash-exp" <?php echo ($settings['ai_news_model'] ?? '') === 'gemini-2.0-flash-exp' ? 'selected' : ''; ?>>Gemini 2.0 Flash</option>
                </select>
            </div>
        </div>

        <div class="p-6 bg-gray-50 rounded-2xl border border-gray-100 space-y-4">
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" name="ai_news_status" value="active" <?php echo ($settings['ai_news_status'] ?? '') === 'active' ? 'checked' : ''; ?> class="w-5 h-5 text-primary-600 rounded">
                <span class="text-sm font-bold text-gray-700">فعال‌سازی اتوماسیون (اجرا توسط Cron)</span>
            </label>

            <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" name="ai_news_auto_publish" value="1" <?php echo ($settings['ai_news_auto_publish'] ?? '') === '1' ? 'checked' : ''; ?> class="w-5 h-5 text-primary-600 rounded">
                <span class="text-sm font-bold text-gray-700">انتشار فوری (Published) به جای پیش‌نویس (Draft)</span>
            </label>
        </div>

        <div class="flex items-center justify-between pt-6 border-t border-gray-100">
            <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white font-bold px-8 py-3 rounded-xl transition-all shadow-lg shadow-primary-500/20">ذخیره تنظیمات</button>
            <button type="button" @click="triggerAutomation()" class="text-sm text-gray-500 hover:text-primary-600 flex items-center gap-2">
                <?php partial('icon', ['name' => 'dashboard', 'class' => 'w-5 h-5']); ?>
                اجرای دستی (Trigger Now)
            </button>
        </div>
    </form>
</div>

<script>
function triggerAutomation() {
    if(!confirm('آیا مایلید فرآیند کرال و تولید محتوا هم‌اکنون اجرا شود؟')) return;

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch('/admin/ai-news/trigger', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrfToken }
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            alert('فرآیند با موفقیت آغاز شد. ' + data.processed + ' مقاله در حال پردازش است.');
            window.location.reload();
        } else {
            alert('خطا: ' + data.error);
        }
    });
}
</script>
