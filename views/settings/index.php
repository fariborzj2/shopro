<div x-data="{ tab: 'general' }">

    <?php if (isset($_GET['success'])): ?>
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
            <p>تنظیمات با موفقیت ذخیره شد.</p>
        </div>
    <?php endif; ?>
    <?php if (isset($_GET['error'])): ?>
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
            <p>خطایی در ذخیره تنظیمات رخ داد.</p>
        </div>
    <?php endif; ?>

    <div class="mb-4 border-b border-gray-200">
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
            <a href="#" @click.prevent="tab = 'general'" :class="{ 'border-indigo-500 text-indigo-600': tab === 'general' }" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                عمومی و هویت
            </a>
            <a href="#" @click.prevent="tab = 'security'" :class="{ 'border-indigo-500 text-indigo-600': tab === 'security' }" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                امنیت
            </a>
            <a href="#" @click.prevent="tab = 'seo'" :class="{ 'border-indigo-500 text-indigo-600': tab === 'seo' }" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                سئو
            </a>
            <a href="#" @click.prevent="tab = 'email'" :class="{ 'border-indigo-500 text-indigo-600': tab === 'email' }" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                ایمیل
            </a>
            <a href="#" @click.prevent="tab = 'store'" :class="{ 'border-indigo-500 text-indigo-600': tab === 'store' }" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                فروشگاه
            </a>
            <a href="#" @click.prevent="tab = 'sms'" :class="{ 'border-indigo-500 text-indigo-600': tab === 'sms' }" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                پیامک
            </a>
            <a href="#" @click.prevent="tab = 'payment'" :class="{ 'border-indigo-500 text-indigo-600': tab === 'payment' }" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                درگاه پرداخت
            </a>
        </nav>
    </div>

    <form action="<?= url('settings') ?>" method="POST">
        <?php partial('csrf_field'); ?>
        <!-- General Settings -->
        <div x-show="tab === 'general'" class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold mb-4">تنظیمات عمومی و هویت</h2>
            <!-- Site Name -->
            <div class="mb-4">
                <label for="site_name" class="block text-gray-700 text-sm font-bold mb-2">نام سایت:</label>
                <input type="text" id="site_name" name="site_name" value="<?= htmlspecialchars($settings['site_name'] ?? '') ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
            </div>
            <!-- Official Email -->
            <div class="mb-4">
                <label for="site_email" class="block text-gray-700 text-sm font-bold mb-2">ایمیل رسمی سایت:</label>
                <input type="email" id="site_email" name="site_email" value="<?= htmlspecialchars($settings['site_email'] ?? '') ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
            </div>
            <!-- Contact Number -->
            <div class="mb-4">
                <label for="site_contact" class="block text-gray-700 text-sm font-bold mb-2">شماره تماس/پشتیبانی:</label>
                <input type="text" id="site_contact" name="site_contact" value="<?= htmlspecialchars($settings['site_contact'] ?? '') ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
            </div>
            <!-- Footer Text -->
            <div class="mb-4">
                <label for="footer_text" class="block text-gray-700 text-sm font-bold mb-2">متن فوتر:</label>
                <textarea id="footer_text" name="footer_text" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700"><?= htmlspecialchars($settings['footer_text'] ?? '') ?></textarea>
            </div>
        </div>

        <!-- Security Settings -->
        <div x-show="tab === 'security'" class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold mb-4">تنظیمات امنیت</h2>
            <div class="mb-4">
                <label for="login_max_attempts" class="block text-gray-700 text-sm font-bold mb-2">تعداد تلاش مجاز ورود:</label>
                <input type="number" id="login_max_attempts" name="login_max_attempts" value="<?= htmlspecialchars($settings['login_max_attempts'] ?? '4') ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
            </div>
            <div class="mb-4">
                <label for="login_lockout_time" class="block text-gray-700 text-sm font-bold mb-2">زمان محدودیت (دقیقه):</label>
                <input type="number" id="login_lockout_time" name="login_lockout_time" value="<?= htmlspecialchars($settings['login_lockout_time'] ?? '20') ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
            </div>
        </div>

        <!-- SEO Settings -->
        <div x-show="tab === 'seo'" class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold mb-4">تنظیمات سئو</h2>
            <div class="mb-4">
                <label for="meta_description" class="block text-gray-700 text-sm font-bold mb-2">توضیحات متا (حداکثر ۱۶۰ کاراکتر):</label>
                <textarea id="meta_description" name="meta_description" rows="3" maxlength="160" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700"><?= htmlspecialchars($settings['meta_description'] ?? '') ?></textarea>
            </div>
            <div class="mb-4">
                <label for="meta_keywords" class="block text-gray-700 text-sm font-bold mb-2">کلمات کلیدی متا (با کاما جدا شوند):</label>
                <input type="text" id="meta_keywords" name="meta_keywords" value="<?= htmlspecialchars($settings['meta_keywords'] ?? '') ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
            </div>
        </div>

        <!-- Email Settings -->
        <div x-show="tab === 'email'" class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold mb-4">تنظیمات ایمیل</h2>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">سیستم ایمیل:</label>
                <select name="mail_system" class="shadow border rounded w-full py-2 px-3">
                    <option value="php" <?= ($settings['mail_system'] ?? 'php') === 'php' ? 'selected' : '' ?>>PHP Mail()</option>
                    <option value="smtp" <?= ($settings['mail_system'] ?? '') === 'smtp' ? 'selected' : '' ?>>SMTP</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="smtp_host" class="block text-gray-700 text-sm font-bold mb-2">هاست SMTP:</label>
                <input type="text" id="smtp_host" name="smtp_host" value="<?= htmlspecialchars($settings['smtp_host'] ?? 'localhost') ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
            </div>
            <div class="mb-4">
                <label for="smtp_port" class="block text-gray-700 text-sm font-bold mb-2">پورت SMTP:</label>
                <input type="number" id="smtp_port" name="smtp_port" value="<?= htmlspecialchars($settings['smtp_port'] ?? '25') ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
            </div>
            <div class="mb-4">
                <label for="smtp_user" class="block text-gray-700 text-sm font-bold mb-2">نام کاربری SMTP:</label>
                <input type="text" id="smtp_user" name="smtp_user" value="<?= htmlspecialchars($settings['smtp_user'] ?? '') ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
            </div>
            <div class="mb-4">
                <label for="smtp_pass" class="block text-gray-700 text-sm font-bold mb-2">کلمه عبور SMTP:</label>
                <input type="password" id="smtp_pass" name="smtp_pass" value="<?= htmlspecialchars($settings['smtp_pass'] ?? '') ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
            </div>
        </div>

        <!-- Store Settings -->
        <div x-show="tab === 'store'" class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold mb-4">تنظیمات فروشگاه</h2>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">واحد پولی:</label>
                <select name="currency" class="shadow border rounded w-full py-2 px-3">
                    <option value="toman" <?= ($settings['currency'] ?? 'toman') === 'toman' ? 'selected' : '' ?>>تومان</option>
                    <option value="rial" <?= ($settings['currency'] ?? '') === 'rial' ? 'selected' : '' ?>>ریال</option>
                </select>
            </div>
            <hr class="my-6">
            <h3 class="text-lg font-semibold mb-4">قیمت‌گذاری دلاری</h3>
            <div class="mb-4">
                <label for="dollar_exchange_rate" class="block text-gray-700 text-sm font-bold mb-2">نرخ دلار به تومان:</label>
                <input type="number" step="1" id="dollar_exchange_rate" name="dollar_exchange_rate" value="<?= htmlspecialchars($settings['dollar_exchange_rate'] ?? '50000') ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
                <p class="text-xs text-gray-500 mt-1">آخرین نرخ دلار را برای محاسبه قیمت محصولات وارد کنید.</p>
            </div>
            <div class="mb-4">
                <label for="auto_update_prices" class="flex items-center">
                    <input type="checkbox" id="auto_update_prices" name="auto_update_prices" value="1" <?= (isset($settings['auto_update_prices']) && $settings['auto_update_prices']) ? 'checked' : '' ?> class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <span class="ml-2 text-gray-700">به‌روزرسانی خودکار قیمت‌های تومانی</span>
                </label>
                <p class="text-xs text-gray-500 mt-1">در صورت فعال بودن، با تغییر نرخ دلار، قیمت تومانی تمام محصولات دلاری به صورت خودکار آپدیت می‌شود.</p>
            </div>
        </div>

        <!-- SMS Settings -->
        <div x-show="tab === 'sms'" class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold mb-4">تنظیمات سرویس پیامک (OTP)</h2>
            <div class="mb-4">
                <label for="sms_api_key" class="block text-gray-700 text-sm font-bold mb-2">کلید API:</label>
                <input type="text" id="sms_api_key" name="sms_api_key" value="<?= htmlspecialchars($settings['sms_api_key'] ?? '') ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 ltr">
            </div>
            <div class="mb-4">
                <label for="sms_pattern_code" class="block text-gray-700 text-sm font-bold mb-2">کد پترن (الگو):</label>
                <input type="text" id="sms_pattern_code" name="sms_pattern_code" value="<?= htmlspecialchars($settings['sms_pattern_code'] ?? '') ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 ltr">
            </div>
            <div class="mb-4">
                <label for="sms_sender_number" class="block text-gray-700 text-sm font-bold mb-2">شماره فرستنده:</label>
                <input type="text" id="sms_sender_number" name="sms_sender_number" value="<?= htmlspecialchars($settings['sms_sender_number'] ?? '') ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 ltr">
            </div>
        </div>

        <!-- Payment Gateway Settings -->
        <div x-show="tab === 'payment'" class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold mb-4">تنظیمات درگاه پرداخت (زیبال)</h2>
            <div class="mb-4">
                <label for="zibal_merchant_id" class="block text-gray-700 text-sm font-bold mb-2">کد مرچنت (Merchant ID):</label>
                <input type="text" id="zibal_merchant_id" name="zibal_merchant_id" value="<?= htmlspecialchars($settings['zibal_merchant_id'] ?? '') ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 ltr">
            </div>
        </div>

        <!-- Submit Button -->
        <div class="mt-6">
            <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                ذخیره تغییرات
            </button>
        </div>
    </form>
</div>
