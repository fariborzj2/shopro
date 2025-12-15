<?php
// plugins/ai-content-pro/views/settings.php
include_once PROJECT_ROOT . '/views/layouts/header.php';
?>

<div class="container mx-auto px-4 py-8" x-data="{ activeTab: 'general' }">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-slate-800">تنظیمات هوش مصنوعی پرو</h1>
        <div class="flex gap-2">
            <a href="/admin/ai-content/logs" class="btn btn-secondary">مشاهده لاگ‌ها</a>
        </div>
    </div>

    <!-- Tabs Header -->
    <div class="flex border-b border-slate-200 mb-6 overflow-x-auto">
        <button @click="activeTab = 'general'" :class="{'border-primary text-primary': activeTab === 'general', 'border-transparent text-slate-500': activeTab !== 'general'}" class="px-4 py-2 border-b-2 font-medium whitespace-nowrap">عمومی</button>
        <button @click="activeTab = 'content'" :class="{'border-primary text-primary': activeTab === 'content', 'border-transparent text-slate-500': activeTab !== 'content'}" class="px-4 py-2 border-b-2 font-medium whitespace-nowrap">تولید محتوا</button>
        <button @click="activeTab = 'seo'" :class="{'border-primary text-primary': activeTab === 'seo', 'border-transparent text-slate-500': activeTab !== 'seo'}" class="px-4 py-2 border-b-2 font-medium whitespace-nowrap">سئو (SEO)</button>
        <button @click="activeTab = 'comments'" :class="{'border-primary text-primary': activeTab === 'comments', 'border-transparent text-slate-500': activeTab !== 'comments'}" class="px-4 py-2 border-b-2 font-medium whitespace-nowrap">نظرات خودکار</button>
        <button @click="activeTab = 'calendar'" :class="{'border-primary text-primary': activeTab === 'calendar', 'border-transparent text-slate-500': activeTab !== 'calendar'}" class="px-4 py-2 border-b-2 font-medium whitespace-nowrap">تقویم محتوا</button>
        <button @click="activeTab = 'queue'" :class="{'border-primary text-primary': activeTab === 'queue', 'border-transparent text-slate-500': activeTab !== 'queue'}" class="px-4 py-2 border-b-2 font-medium whitespace-nowrap">پردازش پس‌زمینه</button>
    </div>

    <form action="/admin/ai-content/settings" method="POST" class="bg-white rounded-2xl shadow-card p-6">
        <?php csrf_field(); ?>

        <!-- General Tab -->
        <div x-show="activeTab === 'general'" class="space-y-6">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">API Key (Gemini)</label>
                <input type="password" name="settings[api_key]" value="<?php echo htmlspecialchars($settings['api_key'] ?? ''); ?>" class="form-input w-full" dir="ltr">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">API URL</label>
                <input type="text" name="settings[api_url]" value="<?php echo htmlspecialchars($settings['api_url'] ?? ''); ?>" class="form-input w-full" dir="ltr">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">توکن امنیتی Cron</label>
                <input type="text" name="settings[cron_token]" value="<?php echo htmlspecialchars($settings['cron_token'] ?? ''); ?>" class="form-input w-full" dir="ltr" readonly>
                <p class="text-xs text-slate-500 mt-1">این توکن به صورت خودکار تولید می‌شود و برای فراخوانی API worker لازم است.</p>
            </div>
        </div>

        <!-- Content Generator Tab -->
        <div x-show="activeTab === 'content'" class="space-y-6">
            <div class="flex items-center gap-2">
                <input type="checkbox" id="content_enabled" name="settings[content_enabled]" value="1" <?php echo ($settings['content_enabled'] ?? '0') == '1' ? 'checked' : ''; ?> class="form-checkbox">
                <label for="content_enabled" class="text-sm font-medium text-slate-700">فعالسازی تولید محتوا</label>
            </div>

             <div class="flex items-center gap-2">
                <input type="checkbox" id="content_gen_faq" name="settings[content_gen_faq]" value="1" <?php echo ($settings['content_gen_faq'] ?? '0') == '1' ? 'checked' : ''; ?> class="form-checkbox">
                <label for="content_gen_faq" class="text-sm font-medium text-slate-700">تولید سوالات متداول (FAQ)</label>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">مدل هوش مصنوعی (تولید محتوا)</label>
                <select name="settings[content_model_gen]" class="form-select w-full" dir="ltr">
                    <option value="gemini-1.5-flash" <?php echo ($settings['content_model_gen'] ?? 'gemini-1.5-flash') == 'gemini-1.5-flash' ? 'selected' : ''; ?>>Gemini 1.5 Flash</option>
                    <option value="gemini-1.5-pro" <?php echo ($settings['content_model_gen'] ?? '') == 'gemini-1.5-pro' ? 'selected' : ''; ?>>Gemini 1.5 Pro</option>
                </select>
            </div>

             <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">هدینگ‌های مجاز</label>
                <input type="text" name="settings[content_allowed_headings]" value="<?php echo htmlspecialchars($settings['content_allowed_headings'] ?? 'H2,H3'); ?>" class="form-input w-full" dir="ltr">
            </div>

             <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">وضعیت پیش‌فرض مطالب</label>
                <select name="settings[content_status]" class="form-select w-full">
                    <option value="draft" <?php echo ($settings['content_status'] ?? '') == 'draft' ? 'selected' : ''; ?>>پیش‌نویس (Draft)</option>
                    <option value="scheduled" <?php echo ($settings['content_status'] ?? '') == 'scheduled' ? 'selected' : ''; ?>>زمان‌بندی شده</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">حداکثر تعداد لینک منبع در هر درخواست</label>
                <input type="number" name="settings[content_max_urls]" value="<?php echo htmlspecialchars($settings['content_max_urls'] ?? '5'); ?>" class="form-input w-full md:w-1/3">
            </div>
        </div>

        <!-- SEO Tab -->
        <div x-show="activeTab === 'seo'" class="space-y-6">
            <div class="flex items-center gap-2">
                <input type="checkbox" id="seo_enabled" name="settings[seo_enabled]" value="1" <?php echo ($settings['seo_enabled'] ?? '0') == '1' ? 'checked' : ''; ?> class="form-checkbox">
                <label for="seo_enabled" class="text-sm font-medium text-slate-700">فعالسازی ابزارهای سئو</label>
            </div>

             <div class="flex items-center gap-2">
                <input type="checkbox" id="seo_schema_enabled" name="settings[seo_schema_enabled]" value="1" <?php echo ($settings['seo_schema_enabled'] ?? '0') == '1' ? 'checked' : ''; ?> class="form-checkbox">
                <label for="seo_schema_enabled" class="text-sm font-medium text-slate-700">تولید اسکیما (Schema)</label>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">حداکثر طول عنوان متا</label>
                    <input type="number" name="settings[seo_meta_title_len]" value="<?php echo htmlspecialchars($settings['seo_meta_title_len'] ?? '60'); ?>" class="form-input w-full">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">حداکثر طول توضیحات متا</label>
                    <input type="number" name="settings[seo_meta_desc_len]" value="<?php echo htmlspecialchars($settings['seo_meta_desc_len'] ?? '160'); ?>" class="form-input w-full">
                </div>
            </div>
        </div>

        <!-- Comments Tab -->
        <div x-show="activeTab === 'comments'" class="space-y-6">
            <div class="flex items-center gap-2">
                <input type="checkbox" id="comments_enabled" name="settings[comments_enabled]" value="1" <?php echo ($settings['comments_enabled'] ?? '0') == '1' ? 'checked' : ''; ?> class="form-checkbox">
                <label for="comments_enabled" class="text-sm font-medium text-slate-700">فعالسازی پاسخ خودکار به نظرات</label>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">حداکثر تعداد پاسخ</label>
                <input type="number" name="settings[comments_max_replies]" value="<?php echo htmlspecialchars($settings['comments_max_replies'] ?? '1'); ?>" class="form-input w-full md:w-1/3">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">لحن پاسخ</label>
                <select name="settings[comments_tone]" class="form-select w-full md:w-1/3">
                    <option value="professional" <?php echo ($settings['comments_tone'] ?? '') == 'professional' ? 'selected' : ''; ?>>رسمی و حرفه‌ای</option>
                    <option value="friendly" <?php echo ($settings['comments_tone'] ?? '') == 'friendly' ? 'selected' : ''; ?>>دوستانه</option>
                    <option value="supportive" <?php echo ($settings['comments_tone'] ?? '') == 'supportive' ? 'selected' : ''; ?>>پشتیبانی و راهنما</option>
                </select>
            </div>
        </div>

        <!-- Calendar Tab -->
        <div x-show="activeTab === 'calendar'" class="space-y-6">
            <div class="flex items-center gap-2">
                <input type="checkbox" id="calendar_enabled" name="settings[calendar_enabled]" value="1" <?php echo ($settings['calendar_enabled'] ?? '0') == '1' ? 'checked' : ''; ?> class="form-checkbox">
                <label for="calendar_enabled" class="text-sm font-medium text-slate-700">فعالسازی تقویم محتوایی</label>
            </div>
        </div>

        <!-- Queue Tab -->
        <div x-show="activeTab === 'queue'" class="space-y-6">
            <div class="flex items-center gap-2">
                <input type="checkbox" id="queue_enabled" name="settings[queue_enabled]" value="1" <?php echo ($settings['queue_enabled'] ?? '0') == '1' ? 'checked' : ''; ?> class="form-checkbox">
                <label for="queue_enabled" class="text-sm font-medium text-slate-700">فعالسازی پردازش صف</label>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">تعداد پردازش همزمان</label>
                <input type="number" name="settings[queue_max_concurrent]" value="<?php echo htmlspecialchars($settings['queue_max_concurrent'] ?? '1'); ?>" class="form-input w-full md:w-1/3">
            </div>
             <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">تعداد تلاش مجدد (Retry)</label>
                <input type="number" name="settings[queue_retry_limit]" value="<?php echo htmlspecialchars($settings['queue_retry_limit'] ?? '3'); ?>" class="form-input w-full md:w-1/3">
            </div>
        </div>

        <div class="mt-8 pt-6 border-t border-slate-200">
            <button type="submit" class="btn btn-primary">ذخیره تنظیمات</button>
        </div>
    </form>
</div>

<?php include_once PROJECT_ROOT . '/views/layouts/footer.php'; ?>
