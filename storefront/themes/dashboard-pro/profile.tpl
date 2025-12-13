<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

    <!-- Profile Edit Form -->
    <div class="lg:col-span-2 card p-6">
        <h3 class="font-bold text-gray-800 mb-6 border-b border-gray-100 pb-4">ویرایش اطلاعات کاربری</h3>

        <form action="/dashboard/profile/update" method="POST" class="space-y-6">
            <?php csrf_field(); ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">نام و نام خانوادگی</label>
                    <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                </div>

                <!-- Mobile (Read Only) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">شماره موبایل</label>
                    <input type="text" value="<?php echo htmlspecialchars($user['mobile']); ?>" disabled class="w-full px-4 py-2 rounded-lg border border-gray-200 bg-gray-100 text-gray-500 cursor-not-allowed">
                    <p class="text-xs text-gray-400 mt-1">شماره موبایل قابل تغییر نیست.</p>
                </div>

                <!-- Email (Optional) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">ایمیل (اختیاری)</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                </div>

                <!-- Avatar URL (Optional) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">آدرس آواتار</label>
                    <input type="url" name="avatar_url" placeholder="https://..." value="<?php echo htmlspecialchars($user['avatar_url'] ?? ''); ?>" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                </div>
            </div>

            <div class="flex justify-end pt-4">
                <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors shadow-sm">
                    ذخیره تغییرات
                </button>
            </div>
        </form>
    </div>

    <!-- Security Settings -->
    <div class="space-y-6">
        <!-- Change Password (Not applicable for OTP, maybe delete account or active sessions) -->
        <div class="card p-6 border-t-4 border-red-500">
            <h3 class="font-bold text-gray-800 mb-4">امنیت حساب</h3>
            <p class="text-sm text-gray-600 mb-4">
                ورود شما با کد تایید (OTP) انجام می‌شود و نیازی به رمز عبور ثابت ندارید.
            </p>

            <div class="border-t border-gray-100 pt-4 mt-4">
                <h4 class="font-medium text-gray-800 text-sm mb-3">نشست‌های فعال</h4>
                <ul class="space-y-3">
                    <li class="flex items-center justify-between text-sm">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                            <span class="text-gray-600">همین دستگاه</span>
                        </div>
                        <span class="text-xs text-gray-400">آنلاین</span>
                    </li>
                    <!-- Mock Data -->
                    <!-- In real implementation, iterate over sessions -->
                </ul>

                <form action="/dashboard/security/logout-all" method="POST" class="mt-4">
                    <?php csrf_field(); ?>
                    <button type="submit" class="w-full py-2 border border-red-200 text-red-600 rounded-lg text-sm hover:bg-red-50 transition-colors">
                        خروج از سایر دستگاه‌ها
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
