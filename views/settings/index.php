<div x-data="{ tab: 'general' }" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">

    <div class="border-b border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800">
        <nav class="flex overflow-x-auto scrollbar-hide" aria-label="Tabs">
            <?php
                $tabs = [
                    'general' => 'عمومی و هویت',
                    'store' => 'فروشگاه',
                    'security' => 'امنیت',
                    'seo' => 'سئو',
                    'blog' => 'بلاگ',
                    'email' => 'ایمیل',
                    'sms' => 'پیامک',
                    'payment' => 'درگاه پرداخت',
                    'cache' => 'کشینگ (Cache)',
                ];
                foreach($tabs as $key => $label):
            ?>
                <a href="#" @click.prevent="tab = '<?= $key ?>'"
                   :class="{ 'border-primary-500 text-primary-600 bg-white dark:bg-gray-800 dark:text-primary-400': tab === '<?= $key ?>', 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300': tab !== '<?= $key ?>' }"
                   class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition-colors">
                    <?= $label ?>
                </a>
            <?php endforeach; ?>
        </nav>
    </div>

    <form action="<?= url('settings') ?>" method="POST" class="p-6">
        <?php partial('csrf_field'); ?>

        <!-- General Settings -->
        <div x-show="tab === 'general'" class="space-y-6" style="display: none;">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="col-span-1">
                    <label for="site_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">نام سایت</label>
                    <input type="text" id="site_name" name="site_name" value="<?= htmlspecialchars($settings['site_name'] ?? '') ?>" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2.5 focus:ring-primary-500 focus:border-primary-500 shadow-sm">
                </div>
                <div class="col-span-1">
                    <label for="site_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">ایمیل رسمی</label>
                    <input type="email" id="site_email" name="site_email" dir="ltr" value="<?= htmlspecialchars($settings['site_email'] ?? '') ?>" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2.5 focus:ring-primary-500 focus:border-primary-500 shadow-sm">
                </div>
                <div class="col-span-1">
                    <label for="site_contact" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">شماره تماس</label>
                    <input type="text" id="site_contact" name="site_contact" dir="ltr" value="<?= htmlspecialchars($settings['site_contact'] ?? '') ?>" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2.5 focus:ring-primary-500 focus:border-primary-500 shadow-sm">
                </div>
                <div class="col-span-1 md:col-span-2">
                    <label for="footer_text" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">متن فوتر</label>
                    <textarea id="footer_text" name="footer_text" rows="3" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2.5 focus:ring-primary-500 focus:border-primary-500 shadow-sm"><?= htmlspecialchars($settings['footer_text'] ?? '') ?></textarea>
                </div>
                <div class="col-span-1 md:col-span-2">
                    <label for="default_theme" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">قالب پیش‌فرض سایت</label>
                    <div class="relative">
                        <select id="default_theme" name="default_theme" class="w-full appearance-none rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2.5 pr-10 focus:ring-primary-500 focus:border-primary-500 shadow-sm">
                            <option value="template-1" <?= ($settings['default_theme'] ?? 'template-1') === 'template-1' ? 'selected' : '' ?>>پیش‌فرض (قالب ۱)</option>
                            <option value="template-2" <?= ($settings['default_theme'] ?? '') === 'template-2' ? 'selected' : '' ?>>تلگرام پرمیوم (قالب ۲)</option>
                            <option value="template-3" <?= ($settings['default_theme'] ?? '') === 'template-3' ? 'selected' : '' ?>>طرح ادمین (قالب ۳)</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center px-3 text-gray-500">
                            <?php partial('icon', ['name' => 'chevron-down', 'class' => 'w-4 h-4']); ?>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">کاربران جدید این قالب را مشاهده خواهند کرد. کاربران فعلی می‌توانند قالب خود را تغییر دهند.</p>
                </div>
            </div>
        </div>

        <!-- Security Settings -->
        <div x-show="tab === 'security'" class="space-y-6" style="display: none;">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="col-span-1">
                    <label for="login_max_attempts" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">تعداد تلاش مجاز ورود</label>
                    <input type="number" id="login_max_attempts" name="login_max_attempts" value="<?= htmlspecialchars($settings['login_max_attempts'] ?? '4') ?>" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2.5 focus:ring-primary-500 focus:border-primary-500 shadow-sm">
                </div>
                <div class="col-span-1">
                    <label for="login_lockout_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">زمان محدودیت (دقیقه)</label>
                    <input type="number" id="login_lockout_time" name="login_lockout_time" value="<?= htmlspecialchars($settings['login_lockout_time'] ?? '20') ?>" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2.5 focus:ring-primary-500 focus:border-primary-500 shadow-sm">
                </div>
            </div>
        </div>

        <!-- Blog Settings -->
        <div x-show="tab === 'blog'" class="space-y-6" style="display: none;">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Blog Home -->
                <div class="col-span-1">
                    <label for="blog_index_limit" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">تعداد مطالب در صفحه اصلی وبلاگ</label>
                    <input type="number" id="blog_index_limit" name="blog_index_limit" value="<?= htmlspecialchars($settings['blog_index_limit'] ?? '10') ?>" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2.5 focus:ring-primary-500 focus:border-primary-500 shadow-sm">
                </div>

                <!-- Blog Category -->
                <div class="col-span-1">
                    <label for="blog_category_limit" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">تعداد مطالب در صفحات دسته‌بندی وبلاگ</label>
                    <input type="number" id="blog_category_limit" name="blog_category_limit" value="<?= htmlspecialchars($settings['blog_category_limit'] ?? '10') ?>" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2.5 focus:ring-primary-500 focus:border-primary-500 shadow-sm">
                </div>

                <!-- Blog Tags -->
                <div class="col-span-1">
                    <label for="blog_tag_limit" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">تعداد مطالب در صفحات برچسب‌ها</label>
                    <input type="number" id="blog_tag_limit" name="blog_tag_limit" value="<?= htmlspecialchars($settings['blog_tag_limit'] ?? '10') ?>" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2.5 focus:ring-primary-500 focus:border-primary-500 shadow-sm">
                </div>

                <!-- Related Posts -->
                <div class="col-span-1">
                    <label for="blog_related_limit" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">تعداد مطالب مشابه (Related Posts)</label>
                    <input type="number" id="blog_related_limit" name="blog_related_limit" value="<?= htmlspecialchars($settings['blog_related_limit'] ?? '5') ?>" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2.5 focus:ring-primary-500 focus:border-primary-500 shadow-sm">
                </div>

                <!-- Slider -->
                <div class="col-span-1">
                    <label for="blog_slider_limit" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">تعداد آیتم‌های اسلایدر «مطالب برتر»</label>
                    <input type="number" id="blog_slider_limit" name="blog_slider_limit" value="<?= htmlspecialchars($settings['blog_slider_limit'] ?? '5') ?>" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2.5 focus:ring-primary-500 focus:border-primary-500 shadow-sm">
                </div>

                <!-- Most Discussed -->
                <div class="col-span-1">
                    <label for="blog_discussed_limit" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">تعداد مطالب پربحث</label>
                    <input type="number" id="blog_discussed_limit" name="blog_discussed_limit" value="<?= htmlspecialchars($settings['blog_discussed_limit'] ?? '5') ?>" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2.5 focus:ring-primary-500 focus:border-primary-500 shadow-sm">
                </div>

                <!-- Most Viewed -->
                <div class="col-span-1">
                    <label for="blog_viewed_limit" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">تعداد مطالب پربازدید</label>
                    <input type="number" id="blog_viewed_limit" name="blog_viewed_limit" value="<?= htmlspecialchars($settings['blog_viewed_limit'] ?? '5') ?>" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2.5 focus:ring-primary-500 focus:border-primary-500 shadow-sm">
                </div>

                <!-- Editors Picks -->
                <div class="col-span-1">
                    <label for="blog_recommended_limit" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">تعداد مطالب پیشنهادی</label>
                    <input type="number" id="blog_recommended_limit" name="blog_recommended_limit" value="<?= htmlspecialchars($settings['blog_recommended_limit'] ?? '5') ?>" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2.5 focus:ring-primary-500 focus:border-primary-500 shadow-sm">
                </div>
            </div>
        </div>

        <!-- SEO Settings -->
        <div x-show="tab === 'seo'" class="space-y-6" style="display: none;">
             <div class="col-span-1">
                <label for="meta_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">توضیحات متا (Description)</label>
                <textarea id="meta_description" name="meta_description" rows="3" maxlength="160" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2.5 focus:ring-primary-500 focus:border-primary-500 shadow-sm"><?= htmlspecialchars($settings['meta_description'] ?? '') ?></textarea>
                <p class="text-xs text-gray-500 mt-1">حداکثر ۱۶۰ کاراکتر</p>
            </div>
            <div class="col-span-1">
                <label for="meta_keywords" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">کلمات کلیدی</label>
                <input type="text" id="meta_keywords" name="meta_keywords" value="<?= htmlspecialchars($settings['meta_keywords'] ?? '') ?>" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2.5 focus:ring-primary-500 focus:border-primary-500 shadow-sm">
                <p class="text-xs text-gray-500 mt-1">با کاما (,) جدا کنید</p>
            </div>
        </div>

        <!-- Email Settings -->
        <div x-show="tab === 'email'" class="space-y-6" style="display: none;">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="col-span-1">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">سیستم ارسال ایمیل</label>
                    <div class="relative">
                        <select name="mail_system" class="w-full appearance-none rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2.5 pr-10 focus:ring-primary-500 focus:border-primary-500 shadow-sm">
                            <option value="php" <?= ($settings['mail_system'] ?? 'php') === 'php' ? 'selected' : '' ?>>PHP Mail()</option>
                            <option value="smtp" <?= ($settings['mail_system'] ?? '') === 'smtp' ? 'selected' : '' ?>>SMTP</option>
                        </select>
                         <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center px-3 text-gray-500">
                            <?php partial('icon', ['name' => 'chevron-down', 'class' => 'w-4 h-4']); ?>
                        </div>
                    </div>
                </div>
                <div class="col-span-1">
                    <label for="smtp_host" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">هاست SMTP</label>
                    <input type="text" id="smtp_host" name="smtp_host" dir="ltr" value="<?= htmlspecialchars($settings['smtp_host'] ?? 'localhost') ?>" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2.5 focus:ring-primary-500 focus:border-primary-500 shadow-sm">
                </div>
                <div class="col-span-1">
                    <label for="smtp_port" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">پورت SMTP</label>
                    <input type="number" id="smtp_port" name="smtp_port" dir="ltr" value="<?= htmlspecialchars($settings['smtp_port'] ?? '25') ?>" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2.5 focus:ring-primary-500 focus:border-primary-500 shadow-sm">
                </div>
                <div class="col-span-1">
                    <label for="smtp_user" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">نام کاربری SMTP</label>
                    <input type="text" id="smtp_user" name="smtp_user" dir="ltr" value="<?= htmlspecialchars($settings['smtp_user'] ?? '') ?>" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2.5 focus:ring-primary-500 focus:border-primary-500 shadow-sm">
                </div>
                <div class="col-span-1">
                    <label for="smtp_pass" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">کلمه عبور SMTP</label>
                    <input type="password" id="smtp_pass" name="smtp_pass" dir="ltr" value="<?= htmlspecialchars($settings['smtp_pass'] ?? '') ?>" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2.5 focus:ring-primary-500 focus:border-primary-500 shadow-sm">
                </div>
            </div>
        </div>

        <!-- Store Settings -->
        <div x-show="tab === 'store'" class="space-y-6" style="display: none;">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="col-span-1">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">واحد پولی</label>
                    <div class="relative">
                        <select name="currency" class="w-full appearance-none rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2.5 pr-10 focus:ring-primary-500 focus:border-primary-500 shadow-sm">
                            <option value="toman" <?= ($settings['currency'] ?? 'toman') === 'toman' ? 'selected' : '' ?>>تومان</option>
                            <option value="rial" <?= ($settings['currency'] ?? '') === 'rial' ? 'selected' : '' ?>>ریال</option>
                        </select>
                         <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center px-3 text-gray-500">
                            <?php partial('icon', ['name' => 'chevron-down', 'class' => 'w-4 h-4']); ?>
                        </div>
                    </div>
                </div>

                <div class="col-span-1">
                    <label for="dollar_exchange_rate" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">نرخ دلار به تومان</label>
                    <input type="number" step="1" id="dollar_exchange_rate" name="dollar_exchange_rate" value="<?= htmlspecialchars($settings['dollar_exchange_rate'] ?? '50000') ?>" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2.5 focus:ring-primary-500 focus:border-primary-500 shadow-sm">
                </div>

                 <div class="col-span-1 md:col-span-2">
                    <label class="flex items-center p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-200 dark:border-gray-700 cursor-pointer">
                        <input type="checkbox" id="auto_update_prices" name="auto_update_prices" value="1" <?= (isset($settings['auto_update_prices']) && $settings['auto_update_prices']) ? 'checked' : '' ?> class="h-4 w-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500 bg-white dark:bg-gray-700 dark:border-gray-600">
                        <div class="mr-3">
                            <span class="block text-sm font-medium text-gray-900 dark:text-white">به‌روزرسانی خودکار قیمت‌های تومانی</span>
                            <span class="block text-xs text-gray-500 dark:text-gray-400 mt-0.5">با تغییر نرخ دلار، قیمت تومانی تمام محصولات دلاری آپدیت شود.</span>
                        </div>
                    </label>
                </div>
            </div>
        </div>

        <!-- SMS Settings -->
        <div x-show="tab === 'sms'" class="space-y-6" style="display: none;">
             <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="col-span-1 md:col-span-2">
                    <label for="sms_api_key" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">کلید API</label>
                    <input type="text" id="sms_api_key" name="sms_api_key" dir="ltr" value="<?= htmlspecialchars($settings['sms_api_key'] ?? '') ?>" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2.5 focus:ring-primary-500 focus:border-primary-500 shadow-sm font-mono text-sm">
                </div>
                <div class="col-span-1">
                    <label for="sms_pattern_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">کد پترن (Pattern Code)</label>
                    <input type="text" id="sms_pattern_code" name="sms_pattern_code" dir="ltr" value="<?= htmlspecialchars($settings['sms_pattern_code'] ?? '') ?>" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2.5 focus:ring-primary-500 focus:border-primary-500 shadow-sm">
                </div>
                <div class="col-span-1">
                    <label for="sms_sender_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">شماره فرستنده</label>
                    <input type="text" id="sms_sender_number" name="sms_sender_number" dir="ltr" value="<?= htmlspecialchars($settings['sms_sender_number'] ?? '') ?>" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2.5 focus:ring-primary-500 focus:border-primary-500 shadow-sm">
                </div>
             </div>
        </div>

        <!-- Payment Gateway Settings -->
        <div x-show="tab === 'payment'" class="space-y-6" style="display: none;">
             <div class="col-span-1">
                <label for="zibal_merchant_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">کد مرچنت زیبال (Merchant ID)</label>
                <input type="text" id="zibal_merchant_id" name="zibal_merchant_id" dir="ltr" value="<?= htmlspecialchars($settings['zibal_merchant_id'] ?? '') ?>" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2.5 focus:ring-primary-500 focus:border-primary-500 shadow-sm font-mono text-sm">
            </div>
        </div>

        <!-- Cache Settings -->
        <div x-show="tab === 'cache'" class="space-y-6" style="display: none;">
            <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-xl border border-blue-100 dark:border-blue-800 mb-6">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <?php partial('icon', ['name' => 'information-circle', 'class' => 'w-5 h-5 text-blue-600 dark:text-blue-400']); ?>
                    </div>
                    <div class="mr-3">
                        <h3 class="text-sm font-medium text-blue-800 dark:text-blue-300">راهنمای کشینگ</h3>
                        <div class="mt-2 text-sm text-blue-700 dark:text-blue-400">
                            <p>سیستم کشینگ برای بهبود سرعت سایت استفاده می‌شود. قبل از فعال‌سازی، از نصب بودن Redis روی سرور اطمینان حاصل کنید.</p>
                        </div>
                        <div class="mt-4 flex flex-wrap items-center gap-4">
                             <button type="button" onclick="document.getElementById('clearCacheForm').submit()" class="inline-flex items-center px-3 py-1.5 border border-red-200 shadow-sm text-xs font-medium rounded-lg text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                پاکسازی کامل کش (Flush All)
                            </button>

                            <?php if ($cacheDriver === 'redis'): ?>
                                <!-- Cache Size (Redis) -->
                                <div class="text-xs text-blue-800 dark:text-blue-300 bg-blue-100 dark:bg-blue-900/50 px-2 py-1.5 rounded-lg border border-blue-200 dark:border-blue-800">
                                    <span class="font-bold ml-1">حجم کش:</span>
                                    <span dir="ltr" class="font-mono"><?= $cacheStats['memory'] ?? '0 B' ?></span>
                                </div>

                                <!-- Redis Status -->
                                <div>
                                    <?php if (($cacheStats['status'] ?? '') === 'Connected'): ?>
                                        <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300 border border-green-200 dark:border-green-800">
                                            <span class="w-1.5 h-1.5 ml-1.5 bg-green-500 rounded-full animate-pulse"></span>
                                            Redis متصل
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300 border border-red-200 dark:border-red-800">
                                            <span class="w-1.5 h-1.5 ml-1.5 bg-red-500 rounded-full"></span>
                                            Redis قطع
                                        </span>
                                    <?php endif; ?>
                                </div>
                            <?php elseif ($cacheDriver === 'litespeed'): ?>
                                <!-- LiteSpeed Status -->
                                <div>
                                    <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-300 border border-purple-200 dark:border-purple-800">
                                        <span class="w-1.5 h-1.5 ml-1.5 bg-purple-500 rounded-full animate-pulse"></span>
                                        LiteSpeed فعال
                                    </span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- General Cache Settings -->
                 <div class="col-span-1 md:col-span-2">
                    <h4 class="text-base font-semibold text-gray-800 dark:text-white mb-4 pb-2 border-b border-gray-100 dark:border-gray-700">تنظیمات عمومی کش</h4>
                </div>

                <div class="col-span-1">
                    <label for="cache_driver" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">درایور کش (Driver)</label>
                    <div class="relative">
                        <select id="cache_driver" name="cache_driver" class="w-full appearance-none rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2.5 pr-10 focus:ring-primary-500 focus:border-primary-500 shadow-sm">
                            <option value="redis" <?= ($settings['cache_driver'] ?? 'redis') === 'redis' ? 'selected' : '' ?>>Redis (پیشنهادی)</option>
                            <option value="litespeed" <?= ($settings['cache_driver'] ?? '') === 'litespeed' ? 'selected' : '' ?>>LiteSpeed Cache</option>
                            <option value="disabled" <?= ($settings['cache_driver'] ?? '') === 'disabled' ? 'selected' : '' ?>>غیرفعال (Disabled)</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center px-3 text-gray-500">
                            <?php partial('icon', ['name' => 'chevron-down', 'class' => 'w-4 h-4']); ?>
                        </div>
                    </div>
                </div>

                <div class="col-span-1">
                    <label class="flex items-center p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-200 dark:border-gray-700 cursor-pointer h-full">
                        <input type="checkbox" id="cache_debug" name="cache_debug" value="1" <?= (isset($settings['cache_debug']) && $settings['cache_debug'] == '1') ? 'checked' : '' ?> class="h-4 w-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500 bg-white dark:bg-gray-700 dark:border-gray-600">
                        <div class="mr-3">
                            <span class="block text-sm font-medium text-gray-900 dark:text-white">حالت دیباگ (Debug Mode)</span>
                            <span class="block text-xs text-gray-500 dark:text-gray-400 mt-0.5">ثبت خطاها و اطلاعات اتصال در error log سرور.</span>
                        </div>
                    </label>
                </div>

                <!-- Connection Settings -->
                <div class="col-span-1 md:col-span-2 mt-4">
                    <h4 class="text-base font-semibold text-gray-800 dark:text-white mb-4 pb-2 border-b border-gray-100 dark:border-gray-700">تنظیمات اتصال Redis</h4>
                </div>

                <div class="col-span-1">
                    <label for="redis_host" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">آدرس هاست (Host)</label>
                    <input type="text" id="redis_host" name="redis_host" dir="ltr" value="<?= htmlspecialchars($settings['redis_host'] ?? '127.0.0.1') ?>" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2.5 focus:ring-primary-500 focus:border-primary-500 shadow-sm font-mono">
                </div>
                <div class="col-span-1">
                    <label for="redis_port" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">پورت (Port)</label>
                    <input type="text" id="redis_port" name="redis_port" dir="ltr" value="<?= htmlspecialchars($settings['redis_port'] ?? '6379') ?>" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2.5 focus:ring-primary-500 focus:border-primary-500 shadow-sm font-mono">
                </div>
                <div class="col-span-1">
                    <label for="redis_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">رمز عبور (Password)</label>
                    <input type="password" id="redis_password" name="redis_password" dir="ltr" value="<?= htmlspecialchars($settings['redis_password'] ?? '') ?>" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2.5 focus:ring-primary-500 focus:border-primary-500 shadow-sm font-mono">
                </div>
                <div class="col-span-1">
                    <label for="redis_db" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">شماره دیتابیس (DB Index)</label>
                    <input type="number" id="redis_db" name="redis_db" dir="ltr" value="<?= htmlspecialchars($settings['redis_db'] ?? '0') ?>" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2.5 focus:ring-primary-500 focus:border-primary-500 shadow-sm font-mono">
                </div>

                <!-- HTML Cache Settings -->
                <div class="col-span-1 md:col-span-2 mt-4">
                    <h4 class="text-base font-semibold text-gray-800 dark:text-white mb-4 pb-2 border-b border-gray-100 dark:border-gray-700">کش HTML (فول پیج)</h4>
                </div>

                <div class="col-span-1 md:col-span-2">
                    <label class="flex items-center p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-200 dark:border-gray-700 cursor-pointer">
                        <input type="checkbox" id="cache_html_enabled" name="cache_html_enabled" value="1" <?= (isset($settings['cache_html_enabled']) && $settings['cache_html_enabled'] == '1') ? 'checked' : '' ?> class="h-4 w-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500 bg-white dark:bg-gray-700 dark:border-gray-600">
                        <div class="mr-3">
                            <span class="block text-sm font-medium text-gray-900 dark:text-white">فعال‌سازی کش HTML برای مهمان‌ها</span>
                            <span class="block text-xs text-gray-500 dark:text-gray-400 mt-0.5">سرعت بارگذاری صفحات برای کاربران لاگین‌نکرده به شدت افزایش می‌یابد.</span>
                        </div>
                    </label>
                </div>

                <div class="col-span-1">
                    <label for="cache_html_ttl" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">مدت زمان کش (ثانیه)</label>
                    <input type="number" id="cache_html_ttl" name="cache_html_ttl" dir="ltr" value="<?= htmlspecialchars($settings['cache_html_ttl'] ?? '600') ?>" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2.5 focus:ring-primary-500 focus:border-primary-500 shadow-sm">
                    <p class="text-xs text-gray-500 mt-1">پیش‌فرض: ۶۰۰ ثانیه (۱۰ دقیقه)</p>
                </div>

                <div class="col-span-1 md:col-span-2">
                    <label for="cache_excluded_urls" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">آدرس‌های استثنا (Exclude URLs)</label>
                    <textarea id="cache_excluded_urls" name="cache_excluded_urls" rows="3" dir="ltr" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-2.5 focus:ring-primary-500 focus:border-primary-500 shadow-sm font-mono text-sm" placeholder="/contact&#10;/search*"><?= htmlspecialchars($settings['cache_excluded_urls'] ?? '') ?></textarea>
                    <p class="text-xs text-gray-500 mt-1">هر الگو را در یک خط جدید وارد کنید. از * برای تطبیق همه چیز استفاده کنید.</p>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-700 flex justify-end">
            <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 shadow-md hover:shadow-lg font-medium transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                ذخیره تنظیمات
            </button>
        </div>
    </form>

    <!-- Hidden Form for Clear Cache -->
    <form id="clearCacheForm" action="<?= url('settings/clear-cache') ?>" method="POST" style="display: none;">
        <?php partial('csrf_field'); ?>
    </form>
</div>
