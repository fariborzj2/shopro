<div class="bg-white rounded-2xl shadow-card p-6">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-slate-800">تنظیمات SeoPilot Enterprise</h2>
        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-medium">فعال</span>
    </div>

    <form action="/admin/seopilot/settings/save" method="POST" class="space-y-6">
        <?php csrf_field(); ?>

        <!-- General Settings -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Separator -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">جداکننده عنوان (Title Separator)</label>
                <select name="separator" class="form-input w-full rounded-xl border-slate-300">
                    <option value="|" <?= ($settings['separator'] ?? '') === '|' ? 'selected' : '' ?>>| (خط عمودی)</option>
                    <option value="-" <?= ($settings['separator'] ?? '') === '-' ? 'selected' : '' ?>>- (خط تیره)</option>
                    <option value="•" <?= ($settings['separator'] ?? '') === '•' ? 'selected' : '' ?>>• (نقطه)</option>
                </select>
            </div>

            <!-- Site Type -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">نوع وب‌سایت (Schema)</label>
                <select name="site_type" class="form-input w-full rounded-xl border-slate-300">
                    <option value="organization" <?= ($settings['site_type'] ?? '') === 'organization' ? 'selected' : '' ?>>سازمانی (Organization)</option>
                    <option value="person" <?= ($settings['site_type'] ?? '') === 'person' ? 'selected' : '' ?>>شخصی (Person)</option>
                </select>
            </div>

        </div>

        <div class="border-t border-slate-200 my-6"></div>

        <!-- AI & Automation -->
        <div class="space-y-4">
            <h3 class="text-lg font-semibold text-slate-800">هوش مصنوعی و اتوماسیون</h3>

            <div class="flex items-center">
                <input type="checkbox" name="ai_auto_meta" id="ai_auto_meta" class="form-checkbox h-5 w-5 text-primary rounded" <?= ($settings['ai_auto_meta'] ?? false) ? 'checked' : '' ?>>
                <label for="ai_auto_meta" class="mr-3 text-slate-700">تولید خودکار متا تگ‌ها در صورت خالی بودن (AutoMeta)</label>
            </div>

            <div class="flex items-center">
                <input type="checkbox" name="sitemap_enabled" id="sitemap_enabled" class="form-checkbox h-5 w-5 text-primary rounded" <?= ($settings['sitemap_enabled'] ?? false) ? 'checked' : '' ?>>
                <label for="sitemap_enabled" class="mr-3 text-slate-700">فعال‌سازی نقشه سایت XML</label>
            </div>
        </div>

        <div class="border-t border-slate-200 my-6"></div>

        <button type="submit" class="bg-primary text-white px-6 py-2 rounded-xl hover:bg-primary-dark transition-colors">
            ذخیره تغییرات
        </button>
    </form>
</div>
