<div x-data="{ tab: 'general' }" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">

    <div class="border-b border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800">
        <nav class="flex overflow-x-auto scrollbar-hide" aria-label="Tabs">
            <?php
                $tabs = [
                    'general' => 'عمومی و هویت',
                    'store' => 'فروشگاه',
                    'security' => 'امنیت',
                    'seo' => 'سئو',
                    'email' => 'ایمیل',
                    'sms' => 'پیامک',
                    'payment' => 'درگاه پرداخت',
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

        <!-- Submit Button -->
        <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-700 flex justify-end">
            <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 shadow-md hover:shadow-lg font-medium transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                ذخیره تنظیمات
            </button>
        </div>
    </form>
</div>
