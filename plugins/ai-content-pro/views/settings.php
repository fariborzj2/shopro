<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-soft p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">تنظیمات هوش مصنوعی (AI Content Pro)</h1>
        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">نسخه 1.2.0</span>
    </div>

    <form action="/admin/ai-content-pro/settings/update" method="POST" class="space-y-6">
        <?php csrf_field(); ?>

        <!-- API Configuration -->
        <div class="bg-gray-50 dark:bg-gray-700/50 p-6 rounded-xl border border-gray-100 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4 flex items-center">
                <span class="w-1 h-6 bg-blue-500 rounded-full ml-2"></span>
                پیکربندی API
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">کلید API جمنای (Gemini API Key)</label>
                    <input type="password" name="gemini_api_key" value="<?php echo htmlspecialchars($settings['gemini_api_key'] ?? ''); ?>"
                           class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                           placeholder="sk-...">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">مدل پیش‌فرض</label>
                    <select name="model_content" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="gemini-2.5-flash" <?php echo ($settings['model_content'] ?? '') == 'gemini-2.5-flash' ? 'selected' : ''; ?>>Gemini 2.5 Flash</option>
                        <option value="gemini-pro" <?php echo ($settings['model_content'] ?? '') == 'gemini-pro' ? 'selected' : ''; ?>>Gemini Pro</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Limits & Thresholds (Granular Config) -->
        <div class="bg-gray-50 dark:bg-gray-700/50 p-6 rounded-xl border border-gray-100 dark:border-gray-700">
             <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4 flex items-center">
                <span class="w-1 h-6 bg-purple-500 rounded-full ml-2"></span>
                محدودیت‌ها و تنظیمات دقیق
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                 <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">حداکثر توکن (Max Tokens)</label>
                    <input type="number" name="max_tokens_content" value="<?php echo htmlspecialchars($settings['max_tokens_content'] ?? '2000'); ?>" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">طول متا تایتل</label>
                    <input type="number" name="seo_title_length" value="<?php echo htmlspecialchars($settings['seo_title_length'] ?? '60'); ?>" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600">
                </div>
                 <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">طول متا توضیحات</label>
                    <input type="number" name="seo_desc_length" value="<?php echo htmlspecialchars($settings['seo_desc_length'] ?? '160'); ?>" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600">
                </div>
                 <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">حد تلاش مجدد (Retry)</label>
                    <input type="number" name="queue_retry_limit" value="<?php echo htmlspecialchars($settings['queue_retry_limit'] ?? '3'); ?>" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600">
                </div>
            </div>
        </div>

        <!-- Features Toggles -->
        <div class="bg-gray-50 dark:bg-gray-700/50 p-6 rounded-xl border border-gray-100 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4 flex items-center">
                <span class="w-1 h-6 bg-green-500 rounded-full ml-2"></span>
                ماژول‌ها
            </h2>

            <div class="space-y-4">
                <div class="p-4 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                    <label class="flex items-center justify-between cursor-pointer mb-2">
                        <span class="text-gray-700 dark:text-gray-300 font-medium">تولید محتوا (Content Engine)</span>
                        <input type="checkbox" name="enable_content_gen" value="1" <?php echo ($settings['enable_content_gen'] ?? '0') == '1' ? 'checked' : ''; ?> class="w-5 h-5 text-blue-600 rounded focus:ring-blue-500">
                    </label>

                    <!-- Sub-options for Content Gen -->
                    <div class="mr-6 space-y-2 border-r-2 border-gray-100 dark:border-gray-600 pr-4 mt-2">
                        <label class="flex items-center justify-between cursor-pointer">
                            <span class="text-sm text-gray-600 dark:text-gray-400">تولید سوالات متداول (FAQ)</span>
                            <input type="checkbox" name="enable_faq_gen" value="1" <?php echo ($settings['enable_faq_gen'] ?? '0') == '1' ? 'checked' : ''; ?> class="w-4 h-4 text-blue-500 rounded">
                        </label>
                         <label class="flex items-center justify-between cursor-pointer">
                            <span class="text-sm text-gray-600 dark:text-gray-400">پیشنهاد تصویر (Image Prompts)</span>
                            <input type="checkbox" name="enable_image_gen" value="1" <?php echo ($settings['enable_image_gen'] ?? '0') == '1' ? 'checked' : ''; ?> class="w-4 h-4 text-blue-500 rounded">
                        </label>
                        <label class="flex items-center justify-between cursor-pointer">
                            <span class="text-sm text-gray-600 dark:text-gray-400">لینک‌سازی داخلی (Internal Links)</span>
                            <input type="checkbox" name="enable_internal_links" value="1" <?php echo ($settings['enable_internal_links'] ?? '0') == '1' ? 'checked' : ''; ?> class="w-4 h-4 text-blue-500 rounded">
                        </label>
                    </div>
                </div>

                <label class="flex items-center justify-between p-4 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 cursor-pointer hover:border-blue-400 transition-colors">
                    <span class="text-gray-700 dark:text-gray-300 font-medium">سئو خودکار (Meta & Schema)</span>
                    <input type="checkbox" name="enable_seo" value="1" <?php echo ($settings['enable_seo'] ?? '0') == '1' ? 'checked' : ''; ?> class="w-5 h-5 text-blue-600 rounded focus:ring-blue-500">
                </label>

                <label class="flex items-center justify-between p-4 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 cursor-pointer hover:border-blue-400 transition-colors">
                    <span class="text-gray-700 dark:text-gray-300 font-medium">پاسخ خودکار نظرات</span>
                    <input type="checkbox" name="enable_comments" value="1" <?php echo ($settings['enable_comments'] ?? '0') == '1' ? 'checked' : ''; ?> class="w-5 h-5 text-blue-600 rounded focus:ring-blue-500">
                </label>

                <label class="flex items-center justify-between p-4 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 cursor-pointer hover:border-blue-400 transition-colors">
                    <span class="text-gray-700 dark:text-gray-300 font-medium">تقویم محتوایی (Content Calendar)</span>
                    <input type="checkbox" name="enable_calendar" value="1" <?php echo ($settings['enable_calendar'] ?? '0') == '1' ? 'checked' : ''; ?> class="w-5 h-5 text-blue-600 rounded focus:ring-blue-500">
                </label>
            </div>
        </div>

        <div class="flex justify-end pt-4">
            <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5">
                ذخیره تنظیمات
            </button>
        </div>
    </form>
</div>
