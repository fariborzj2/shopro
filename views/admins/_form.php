<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Basic Info -->
    <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800">
            <h3 class="text-lg font-bold text-gray-800 dark:text-white flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                اطلاعات حساب کاربری
            </h3>
        </div>

        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="col-span-1">
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">نام و نام خانوادگی</label>
                <input type="text" id="name" name="name"
                       class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2.5 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all shadow-sm"
                       value="<?php echo htmlspecialchars($admin['name'] ?? ''); ?>">
            </div>

            <div class="col-span-1">
                <label for="username" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">نام کاربری <span class="text-red-500">*</span></label>
                <input type="text" id="username" name="username" dir="ltr"
                       class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2.5 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all shadow-sm font-mono"
                       value="<?php echo htmlspecialchars($admin['username'] ?? ''); ?>" required>
            </div>

            <div class="col-span-1">
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">ایمیل <span class="text-red-500">*</span></label>
                <input type="email" id="email" name="email" dir="ltr"
                       class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2.5 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all shadow-sm font-mono"
                       value="<?php echo htmlspecialchars($admin['email'] ?? ''); ?>" required>
            </div>

            <div class="col-span-1">
                <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">سمت (Role) <span class="text-red-500">*</span></label>
                <input type="text" id="role" name="role"
                       class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2.5 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all shadow-sm"
                       value="<?php echo htmlspecialchars($admin['role'] ?? ''); ?>" required placeholder="مثلاً: پشتیبانی">
            </div>

            <div class="col-span-1">
                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">وضعیت حساب</label>
                <div class="relative">
                    <select id="status" name="status" class="w-full appearance-none rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2.5 pr-10 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all shadow-sm">
                        <option value="active" <?php echo (isset($admin['status']) && $admin['status'] === 'active') ? 'selected' : ''; ?>>فعال</option>
                        <option value="inactive" <?php echo (isset($admin['status']) && $admin['status'] === 'inactive') ? 'selected' : ''; ?>>غیرفعال</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center px-3 text-gray-500">
                        <?php partial('icon', ['name' => 'chevron-down', 'class' => 'w-4 h-4']); ?>
                    </div>
                </div>
            </div>

            <div class="col-span-1">
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    <?php echo isset($admin) ? 'رمز عبور جدید' : 'رمز عبور <span class="text-red-500">*</span>'; ?>
                </label>
                <input type="password" id="password" name="password" dir="ltr"
                       class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2.5 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all shadow-sm"
                       <?php echo isset($admin) ? '' : 'required'; ?> placeholder="<?php echo isset($admin) ? 'خالی بگذارید تا تغییر نکند' : ''; ?>">
            </div>
        </div>
    </div>

    <!-- Permissions -->
    <div class="lg:col-span-1 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden h-fit">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800">
            <h3 class="text-lg font-bold text-gray-800 dark:text-white flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                سطح دسترسی
            </h3>
        </div>

        <div class="p-6">
            <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">بخش‌های مجاز برای این مدیر را انتخاب کنید.</p>

            <?php
                $current_permissions = [];
                if (isset($admin) && !empty($admin['permissions'])) {
                    $current_permissions = json_decode($admin['permissions'], true) ?? [];
                }
            ?>

            <div class="space-y-3 max-h-[400px] overflow-y-auto custom-scrollbar pr-1">
                <?php foreach ($permissions_list as $key => $label): ?>
                    <label class="flex items-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-100 dark:border-gray-700 cursor-pointer hover:border-primary-500 dark:hover:border-primary-500 transition-colors">
                        <input type="checkbox" name="permissions[]" value="<?php echo $key; ?>"
                               class="h-4 w-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500 bg-white dark:bg-gray-800 dark:border-gray-600"
                               <?php echo in_array($key, $current_permissions) ? 'checked' : ''; ?>>
                        <span class="ml-3 text-sm text-gray-700 dark:text-gray-200"><?php echo $label; ?></span>
                    </label>
                <?php endforeach; ?>
            </div>

            <?php if (isset($admin) && ($admin['is_super_admin'] ?? false)): ?>
                <div class="mt-6 p-4 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-xl flex items-start gap-3">
                    <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <div>
                        <p class="text-sm font-bold text-amber-800 dark:text-amber-400">مدیر کل (Super Admin)</p>
                        <p class="text-xs text-amber-700 dark:text-amber-500 mt-1">این کاربر دسترسی کامل به تمام بخش‌ها دارد و تنظیمات بالا برای او نادیده گرفته می‌شود.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
