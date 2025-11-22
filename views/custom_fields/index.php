<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
    <!-- Header -->
    <div class="p-6 flex flex-col sm:flex-row sm:justify-between sm:items-center border-b border-gray-100 dark:border-gray-700 gap-4">
        <div>
            <h1 class="text-xl font-bold text-gray-800 dark:text-white">مدیریت پارامترهای سفارشی</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">تعریف ویژگی‌های خاص برای محصولات و سفارشات</p>
        </div>
        <a href="<?php echo url('custom-fields/create'); ?>" class="inline-flex items-center justify-center px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 shadow-sm transition-colors">
            <?php partial('icon', ['name' => 'plus', 'class' => 'w-5 h-5 ml-2']); ?>
            ایجاد پارامتر جدید
        </a>
    </div>

    <!-- Mobile List View -->
    <div class="block md:hidden divide-y divide-gray-100 dark:divide-gray-700">
        <?php if (empty($fields)): ?>
            <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                هیچ پارامتری یافت نشد.
            </div>
        <?php else: ?>
            <?php foreach ($fields as $field): ?>
                <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <h3 class="text-sm font-bold text-gray-900 dark:text-white"><?= htmlspecialchars($field->label_fa) ?></h3>
                            <span class="text-xs text-gray-500 dark:text-gray-400 font-mono block mt-0.5"><?= htmlspecialchars($field->name) ?></span>
                        </div>
                        <span class="text-xs bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 px-2 py-1 rounded-md">
                            <?= htmlspecialchars($field->type) ?>
                        </span>
                    </div>
                    <div class="flex justify-between items-center mt-2">
                        <?php if ($field->status === 'active'): ?>
                            <span class="text-xs font-medium text-emerald-600 dark:text-emerald-400 flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> فعال
                            </span>
                        <?php else: ?>
                            <span class="text-xs font-medium text-red-600 dark:text-red-400 flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> غیرفعال
                            </span>
                        <?php endif; ?>

                        <div class="flex items-center gap-3">
                            <a href="<?php echo url('/custom-fields/edit/' . $field->id); ?>" class="text-indigo-600 dark:text-indigo-400 text-sm font-medium">ویرایش</a>
                            <span class="text-gray-300 dark:text-gray-600">|</span>
                            <form action="<?php echo url('/custom-fields/delete/' . $field->id); ?>" method="POST" class="inline-block" onsubmit="return confirm('آیا از حذف این پارامتر اطمینان دارید؟');">
                                <?php partial('csrf_field'); ?>
                                <button type="submit" class="text-red-600 dark:text-red-400 text-sm font-medium">حذف</button>
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
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">نام</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">برچسب</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">نوع</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">وضعیت</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider text-center">عملیات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700 bg-white dark:bg-gray-800">
                <?php if (empty($fields)): ?>
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400 text-sm">
                            هیچ پارامتری یافت نشد.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($fields as $field): ?>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors group">
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 font-mono text-left" dir="ltr">
                                <?= htmlspecialchars($field->name) ?>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                                <?= htmlspecialchars($field->label_fa) ?>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                <span class="bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded text-xs font-mono">
                                    <?= htmlspecialchars($field->type) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <?php if ($field->status === 'active'): ?>
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 dark:bg-emerald-900/20 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-800">فعال</span>
                                <?php else: ?>
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-50 text-red-700 dark:bg-red-900/20 dark:text-red-400 border border-red-100 dark:border-red-800">غیرفعال</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-sm text-center">
                                <div class="flex items-center justify-center gap-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <a href="<?php echo url('/custom-fields/edit/' . $field->id); ?>" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 p-1 rounded-md hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition-colors" title="ویرایش">
                                        <?php partial('icon', ['name' => 'edit', 'class' => 'w-5 h-5']); ?>
                                    </a>
                                    <form action="<?php echo url('/custom-fields/delete/' . $field->id); ?>" method="POST" class="inline-block" onsubmit="return confirm('آیا از حذف این پارامتر اطمینان دارید؟');">
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
</div>
