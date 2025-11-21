<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
    <!-- Header -->
    <div class="p-6 flex flex-col sm:flex-row sm:justify-between sm:items-center border-b border-gray-100 dark:border-gray-700 gap-4">
        <div>
            <h1 class="text-xl font-bold text-gray-800 dark:text-white">مدیریت دسته‌بندی‌ها</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">ساختار درختی دسته‌بندی‌های فروشگاه</p>
        </div>
        <a href="<?php echo url('categories/create'); ?>" class="inline-flex items-center justify-center px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 shadow-sm transition-colors">
            <?php partial('icon', ['name' => 'plus', 'class' => 'w-5 h-5 ml-2']); ?>
            افزودن دسته‌بندی جدید
        </a>
    </div>

    <!-- Mobile Cards View -->
    <div class="block md:hidden divide-y divide-gray-100 dark:divide-gray-700">
        <?php if (empty($categories)): ?>
            <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                هیچ دسته‌بندی برای نمایش وجود ندارد.
            </div>
        <?php else: ?>
            <?php foreach ($categories as $category): ?>
                <div class="p-4 space-y-2 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-sm font-bold text-gray-900 dark:text-white"><?= htmlspecialchars($category->name_fa) ?></h3>
                            <span class="text-xs text-gray-500 dark:text-gray-400 font-mono block mt-0.5"><?= htmlspecialchars($category->slug) ?></span>
                        </div>
                         <?php if ($category->status === 'active'): ?>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">فعال</span>
                        <?php else: ?>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">غیرفعال</span>
                        <?php endif; ?>
                    </div>
                    <div class="flex justify-end items-center space-x-3 space-x-reverse pt-2">
                         <a href="<?php echo url('categories/edit/' . $category->id); ?>" class="text-indigo-600 dark:text-indigo-400 text-sm font-medium">
                            ویرایش
                        </a>
                        <span class="text-gray-300 dark:text-gray-600">|</span>
                        <form action="<?php echo url('categories/delete/' . $category->id); ?>" method="POST" class="inline-block" onsubmit="return confirm('آیا از حذف این دسته‌بندی مطمئن هستید؟');">
                            <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
                            <button type="submit" class="text-red-600 dark:text-red-400 text-sm font-medium">حذف</button>
                        </form>
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
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">نام دسته‌بندی</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">اسلاگ</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">والد</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">وضعیت</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider text-center">عملیات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700 bg-white dark:bg-gray-800">
                <?php if (empty($categories)): ?>
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400 text-sm">
                            هیچ دسته‌بندی یافت نشد.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($categories as $category): ?>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors group">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                                <?php echo htmlspecialchars($category->name_fa); ?>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 font-mono">
                                <?php echo htmlspecialchars($category->slug); ?>
                            </td>
                             <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                <!-- Assuming there is a way to get parent name, if not showing ID or leaving blank -->
                                <?= isset($category->parent_id) && $category->parent_id ? '#' . $category->parent_id : '-' ?>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <?php if ($category->status === 'active'): ?>
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 dark:bg-emerald-900/20 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-800">
                                        فعال
                                    </span>
                                <?php else: ?>
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-50 text-red-700 dark:bg-red-900/20 dark:text-red-400 border border-red-100 dark:border-red-800">
                                        غیرفعال
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-sm text-center">
                                <div class="flex items-center justify-center gap-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <a href="<?php echo url('categories/edit/' . $category->id); ?>" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 p-1 rounded-md hover:bg-indigo-50 dark:hover:bg-indigo-900/30" title="ویرایش">
                                        <?php partial('icon', ['name' => 'edit', 'class' => 'w-5 h-5']); ?>
                                    </a>
                                    <form action="<?php echo url('categories/delete/' . $category->id); ?>" method="POST" class="inline-block" onsubmit="return confirm('آیا از حذف این دسته‌بندی مطمئن هستید؟');">
                                        <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
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
</div>
