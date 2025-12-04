<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-card border border-gray-100 dark:border-gray-700 p-6">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">تنظیمات SeoPilot Enterprise</h2>
        <span class="px-3 py-1 bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 rounded-full text-sm font-medium">فعال</span>
    </div>

    <form action="/admin/seopilot/settings" method="POST" class="space-y-6">
        <?php csrf_field(); ?>

        <!-- General Settings -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Separator -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">جداکننده عنوان (Title Separator)</label>
                <div class="relative">
                    <select name="separator" class="appearance-none w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2.5 focus:ring-primary-500 focus:border-primary-500 shadow-sm transition-colors">
                        <option value="|" <?= ($settings['separator'] ?? '') === '|' ? 'selected' : '' ?>>| (خط عمودی)</option>
                        <option value="-" <?= ($settings['separator'] ?? '') === '-' ? 'selected' : '' ?>>- (خط تیره)</option>
                        <option value="•" <?= ($settings['separator'] ?? '') === '•' ? 'selected' : '' ?>>• (نقطه)</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center px-3 text-gray-500">
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Site Type -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">نوع وب‌سایت (Schema)</label>
                <div class="relative">
                    <select name="site_type" class="appearance-none w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2.5 focus:ring-primary-500 focus:border-primary-500 shadow-sm transition-colors">
                        <option value="organization" <?= ($settings['site_type'] ?? '') === 'organization' ? 'selected' : '' ?>>سازمانی (Organization)</option>
                        <option value="person" <?= ($settings['site_type'] ?? '') === 'person' ? 'selected' : '' ?>>شخصی (Person)</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center px-3 text-gray-500">
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>

             <!-- Strictness -->
             <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">سخت‌گیری آنالیز (Analysis Strictness)</label>
                <div class="relative">
                    <select name="analysis_strictness" class="appearance-none w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2.5 focus:ring-primary-500 focus:border-primary-500 shadow-sm transition-colors">
                        <option value="relaxed" <?= ($settings['analysis_strictness'] ?? '') === 'relaxed' ? 'selected' : '' ?>>آسان‌گیر (Relaxed)</option>
                        <option value="normal" <?= ($settings['analysis_strictness'] ?? '') === 'normal' ? 'selected' : '' ?>>معمولی (Normal)</option>
                        <option value="strict" <?= ($settings['analysis_strictness'] ?? '') === 'strict' ? 'selected' : '' ?>>سخت‌گیر (Strict)</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center px-3 text-gray-500">
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>

        </div>

        <div class="border-t border-gray-200 dark:border-gray-700 my-6"></div>

        <!-- AI & Automation -->
        <div class="space-y-4">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">هوش مصنوعی و اتوماسیون</h3>

            <label class="flex items-center p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-200 dark:border-gray-700 cursor-pointer transition-colors hover:bg-gray-100 dark:hover:bg-gray-700">
                <input type="checkbox" name="ai_auto_meta" value="1" class="form-checkbox h-5 w-5 text-primary-600 rounded border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 focus:ring-primary-500" <?= ($settings['ai_auto_meta'] ?? false) ? 'checked' : '' ?>>
                <div class="mr-3">
                    <span class="block text-sm font-medium text-gray-900 dark:text-white">تولید خودکار متا تگ‌ها (AutoMeta)</span>
                    <span class="block text-xs text-gray-500 dark:text-gray-400 mt-0.5">در صورتی که عنوان یا توضیحات متا خالی باشد، سیستم به صورت هوشمند آن‌ها را از محتوا استخراج می‌کند.</span>
                </div>
            </label>

            <label class="flex items-center p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-200 dark:border-gray-700 cursor-pointer transition-colors hover:bg-gray-100 dark:hover:bg-gray-700">
                <input type="checkbox" name="sitemap_enabled" value="1" class="form-checkbox h-5 w-5 text-primary-600 rounded border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 focus:ring-primary-500" <?= ($settings['sitemap_enabled'] ?? false) ? 'checked' : '' ?>>
                <div class="mr-3">
                     <span class="block text-sm font-medium text-gray-900 dark:text-white">فعال‌سازی نقشه سایت XML</span>
                     <span class="block text-xs text-gray-500 dark:text-gray-400 mt-0.5">نقشه سایت به صورت خودکار در /sitemap.xml تولید می‌شود.</span>
                </div>
            </label>
        </div>

        <div class="border-t border-gray-200 dark:border-gray-700 my-6"></div>

        <div class="flex justify-end">
            <button type="submit" class="px-6 py-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-xl shadow-md hover:shadow-lg transition-all focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 font-medium text-sm">
                ذخیره تغییرات
            </button>
        </div>
    </form>
</div>
