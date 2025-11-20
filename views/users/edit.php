<div class="max-w-2xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">ویرایش کاربر</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">ویرایش اطلاعات <?= htmlspecialchars($user['name']) ?></p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
        <form action="<?php echo url('/users/update/' . $user['id']); ?>" method="POST" class="p-6 space-y-6">
            <?php partial('csrf_field'); ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div class="col-span-1">
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">نام و نام خانوادگی <span class="text-red-500">*</span></label>
                    <input type="text" id="name" name="name"
                           class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2.5 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all shadow-sm"
                           value="<?= htmlspecialchars($user['name']) ?>" required>
                </div>

                <!-- Mobile -->
                <div class="col-span-1">
                    <label for="mobile" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">شماره موبایل <span class="text-red-500">*</span></label>
                    <input type="text" id="mobile" name="mobile" dir="ltr"
                           class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2.5 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all shadow-sm font-mono"
                           value="<?= htmlspecialchars($user['mobile']) ?>" required>
                </div>

                <!-- Status -->
                <div class="col-span-1 md:col-span-2">
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">وضعیت حساب</label>
                    <div class="relative">
                        <select id="status" name="status" class="w-full appearance-none rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2.5 pr-10 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all shadow-sm">
                            <option value="active" <?= $user['status'] === 'active' ? 'selected' : '' ?>>فعال</option>
                            <option value="inactive" <?= $user['status'] === 'inactive' ? 'selected' : '' ?>>غیرفعال</option>
                            <option value="banned" <?= $user['status'] === 'banned' ? 'selected' : '' ?>>مسدود</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center px-3 text-gray-500">
                            <?php partial('icon', ['name' => 'chevron-down', 'class' => 'w-4 h-4']); ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="pt-6 border-t border-gray-100 dark:border-gray-700 flex items-center justify-end space-x-3 space-x-reverse">
                <a href="<?php echo url('/users'); ?>" class="px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 font-medium transition-colors">
                    انصراف
                </a>
                <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 shadow-md hover:shadow-lg font-medium transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    ذخیره تغییرات
                </button>
            </div>
        </form>
    </div>
</div>
