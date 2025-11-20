<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
    <!-- Header -->
    <div class="p-6 flex flex-col sm:flex-row sm:justify-between sm:items-center border-b border-gray-100 dark:border-gray-700 gap-4">
        <div>
            <h1 class="text-xl font-bold text-gray-800 dark:text-white">مدیریت کاربران</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">لیست مشتریان و کاربران ثبت‌نام شده</p>
        </div>
        <a href="<?php echo url('/users/create') ?>" class="inline-flex items-center justify-center px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 shadow-sm transition-colors">
            <?php partial('icon', ['name' => 'plus', 'class' => 'w-5 h-5 ml-2']); ?>
            افزودن کاربر جدید
        </a>
    </div>

    <!-- Mobile Cards View -->
    <div class="block md:hidden divide-y divide-gray-100 dark:divide-gray-700">
        <?php if (empty($users)): ?>
            <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                هیچ کاربری یافت نشد.
            </div>
        <?php else: ?>
            <?php foreach ($users as $user): ?>
                <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                    <div class="flex items-start gap-3 mb-3">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-primary-100 dark:bg-primary-900 flex items-center justify-center text-primary-600 dark:text-primary-400 font-bold shadow-sm">
                            <?= mb_substr($user['name'] ?? 'N', 0, 1) ?>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-sm font-bold text-gray-900 dark:text-white truncate"><?= htmlspecialchars($user['name']) ?></h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 font-mono truncate"><?= htmlspecialchars($user['mobile']) ?></p>
                        </div>
                        <?php
                             $status_style = match($user['status']) {
                                'active' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400',
                                'inactive' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                                'banned' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                                default => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300'
                             };
                             $status_text = match($user['status']) {
                                 'active' => 'فعال',
                                 'inactive' => 'غیرفعال',
                                 'banned' => 'مسدود',
                                 default => 'نامشخص'
                             };
                        ?>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full <?= $status_style ?>">
                            <?= $status_text ?>
                        </span>
                    </div>

                    <div class="flex justify-between items-center text-xs text-gray-500 dark:text-gray-400 pt-2 border-t border-gray-100 dark:border-gray-700/50 mt-2">
                         <span>عضویت: <?= \jdate('Y/m/d', strtotime($user['created_at'])) ?></span>

                         <div class="flex items-center gap-3">
                             <a href="<?php echo url('/users/edit/' . $user['id']); ?>" class="text-indigo-600 dark:text-indigo-400 font-medium">ویرایش</a>
                             <span class="text-gray-300 dark:text-gray-600">|</span>
                             <form action="<?php echo url('/users/delete/' . $user['id']); ?>" method="POST" class="inline-block" onsubmit="return confirm('آیا از حذف این کاربر مطمئن هستید؟');">
                                <?php partial('csrf_field'); ?>
                                <button type="submit" class="text-red-600 dark:text-red-400 font-medium">حذف</button>
                            </form>
                         </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Desktop Table View -->
    <div class="hidden md:block overflow-x-auto">
        <table class="min-w-full text-right">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">نام کاربر</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">موبایل</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">تاریخ عضویت</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">وضعیت</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider text-center">عملیات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700 bg-white dark:bg-gray-800">
                <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400 text-sm">
                            هیچ کاربری یافت نشد.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($users as $user): ?>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors group">
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                <div class="flex items-center gap-3">
                                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-xs font-bold shadow-sm">
                                        <?= mb_substr($user['name'] ?? 'N', 0, 1) ?>
                                    </div>
                                    <span class="font-medium"><?= htmlspecialchars($user['name']) ?></span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 font-mono">
                                <?= htmlspecialchars($user['mobile']) ?>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                <?= \jdate('Y/m/d', strtotime($user['created_at'])) ?>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                 <?php
                                     $status_style = match($user['status']) {
                                        'active' => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/20 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-800',
                                        'inactive' => 'bg-amber-50 text-amber-700 dark:bg-amber-900/20 dark:text-amber-400 border border-amber-100 dark:border-amber-800',
                                        'banned' => 'bg-red-50 text-red-700 dark:bg-red-900/20 dark:text-red-400 border border-red-100 dark:border-red-800',
                                        default => 'bg-gray-50 text-gray-700 dark:bg-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-600'
                                     };
                                     $status_text = match($user['status']) {
                                         'active' => 'فعال',
                                         'inactive' => 'غیرفعال',
                                         'banned' => 'مسدود',
                                         default => 'نامشخص'
                                     };
                                ?>
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium <?= $status_style ?>">
                                    <?= $status_text ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-center">
                                <div class="flex items-center justify-center gap-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <a href="<?php echo url('/users/edit/' . $user['id']); ?>" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 p-1 rounded-md hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition-colors" title="ویرایش">
                                        <?php partial('icon', ['name' => 'edit', 'class' => 'w-5 h-5']); ?>
                                    </a>
                                    <form action="<?php echo url('/users/delete/' . $user['id']); ?>" method="POST" class="inline-block" onsubmit="return confirm('آیا از حذف این کاربر مطمئن هستید؟');">
                                        <?php partial('csrf_field'); ?>
                                        <button type="submit" class="text-gray-400 hover:text-red-600 dark:text-gray-500 dark:hover:text-red-400 p-1 rounded-md hover:bg-red-50 dark:hover:bg-red-900/30 transition-colors" title="حذف">
                                            <?php partial('icon', ['name' => 'trash', 'class' => 'w-5 h-5']); ?>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 rounded-b-xl">
        <!-- Assuming $paginator is available, though not explicitly in original file but good practice -->
        <?php if(isset($paginator)) partial('pagination', ['paginator' => $paginator]); ?>
    </div>
</div>
