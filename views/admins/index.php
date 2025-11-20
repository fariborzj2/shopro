<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
    <!-- Header -->
    <div class="p-6 flex flex-col sm:flex-row sm:justify-between sm:items-center border-b border-gray-100 dark:border-gray-700 gap-4">
        <div>
            <h1 class="text-xl font-bold text-gray-800 dark:text-white">مدیریت مدیران</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">لیست مدیران و سطح دسترسی آن‌ها</p>
        </div>
        <a href="<?php echo url('admins/create'); ?>" class="inline-flex items-center justify-center px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 shadow-sm transition-colors">
            <?php partial('icon', ['name' => 'plus', 'class' => 'w-5 h-5 ml-2']); ?>
            افزودن مدیر جدید
        </a>
    </div>

    <!-- Mobile List View -->
    <div class="block md:hidden divide-y divide-gray-100 dark:divide-gray-700">
        <?php foreach ($admins as $admin): ?>
            <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                <div class="flex items-start justify-between mb-2">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-500 dark:text-gray-400 font-bold">
                            <?= mb_substr($admin['name'] ?? 'A', 0, 1) ?>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-gray-900 dark:text-white"><?= htmlspecialchars($admin['name']) ?></h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400"><?= htmlspecialchars($admin['username']) ?></p>
                        </div>
                    </div>
                    <?php if ($admin['status'] === 'active'): ?>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">فعال</span>
                    <?php else: ?>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">غیرفعال</span>
                    <?php endif; ?>
                </div>

                <div class="mt-2 flex flex-wrap gap-2 text-xs text-gray-500 dark:text-gray-400">
                    <span class="bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded-md"><?= htmlspecialchars($admin['role'] ?? '-') ?></span>
                    <?php if ($admin['is_super_admin']): ?>
                        <span class="bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400 px-2 py-1 rounded-md">مدیر کل</span>
                    <?php endif; ?>
                </div>

                <div class="flex justify-end items-center gap-3 mt-3 pt-3 border-t border-gray-100 dark:border-gray-700">
                    <a href="<?php echo url('/admin/admins/edit/' . $admin['id']); ?>" class="text-indigo-600 dark:text-indigo-400 font-medium text-sm">ویرایش</a>
                    <?php if (!$admin['is_super_admin'] && $admin['id'] != $_SESSION['admin_id']): ?>
                        <span class="text-gray-300 dark:text-gray-600">|</span>
                        <form action="<?php echo url('/admin/admins/delete/' . $admin['id']); ?>" method="POST" class="inline-block" onsubmit="return confirm('آیا از حذف این مدیر مطمئن هستید؟');">
                            <?php partial('csrf_field'); ?>
                            <button type="submit" class="text-red-600 dark:text-red-400 font-medium text-sm">حذف</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Desktop Table View -->
    <div class="hidden md:block overflow-x-auto">
        <table class="min-w-full text-right">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">نام و نام کاربری</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">ایمیل</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider text-center">سمت</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider text-center">وضعیت</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider text-center">نوع دسترسی</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider text-center">عملیات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700 bg-white dark:bg-gray-800">
                <?php foreach ($admins as $admin): ?>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors group">
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center text-gray-500 dark:text-gray-300 font-bold text-xs">
                                    <?= mb_substr($admin['name'] ?? 'A', 0, 1) ?>
                                </div>
                                <div>
                                    <div class="font-medium"><?= htmlspecialchars($admin['name']) ?></div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400"><?= htmlspecialchars($admin['username']) ?></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 font-mono text-left" dir="ltr">
                            <?= htmlspecialchars($admin['email']) ?>
                        </td>
                        <td class="px-6 py-4 text-sm text-center">
                            <span class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-2 py-1 rounded-md text-xs">
                                <?= htmlspecialchars($admin['role'] ?? '-') ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-center">
                            <?php if ($admin['status'] === 'active'): ?>
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 dark:bg-emerald-900/20 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-800">فعال</span>
                            <?php else: ?>
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-50 text-red-700 dark:bg-red-900/20 dark:text-red-400 border border-red-100 dark:border-red-800">غیرفعال</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-sm text-center">
                            <?php if ($admin['is_super_admin']): ?>
                                <span class="text-purple-600 dark:text-purple-400 font-bold text-xs">مدیر کل</span>
                            <?php else: ?>
                                <span class="text-gray-500 dark:text-gray-400 text-xs">محدود</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-sm text-center">
                            <div class="flex items-center justify-center gap-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="<?php echo url('/admin/admins/edit/' . $admin['id']); ?>" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 p-1 rounded-md hover:bg-indigo-50 dark:hover:bg-indigo-900/30" title="ویرایش">
                                    <?php partial('icon', ['name' => 'edit', 'class' => 'w-5 h-5']); ?>
                                </a>
                                <?php if (!$admin['is_super_admin'] && $admin['id'] != $_SESSION['admin_id']): ?>
                                    <form action="<?php echo url('/admin/admins/delete/' . $admin['id']); ?>" method="POST" class="inline-block" onsubmit="return confirm('آیا از حذف این مدیر مطمئن هستید؟');">
                                        <?php partial('csrf_field'); ?>
                                        <button type="submit" class="text-gray-400 hover:text-red-600 dark:text-gray-500 dark:hover:text-red-400 p-1 rounded-md hover:bg-red-50 dark:hover:bg-red-900/30 transition-colors" title="حذف">
                                            <?php partial('icon', ['name' => 'trash', 'class' => 'w-5 h-5']); ?>
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
