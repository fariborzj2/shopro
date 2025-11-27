<?php
$faq_types = get_faq_types();
$faq_type_labels = array_column($faq_types, 'label_fa', 'key');
?>
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
    <!-- Header -->
    <div class="p-6 flex flex-col sm:flex-row sm:justify-between sm:items-center border-b border-gray-100 dark:border-gray-700 gap-4">
        <div>
            <h1 class="text-xl font-bold text-gray-800 dark:text-white">مدیریت سوالات متداول</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">سوالات و پاسخ‌های متداولی که در سایت نمایش داده می‌شوند</p>
        </div>
        <a href="<?= url('faq/create') ?>" class="inline-flex items-center justify-center px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 shadow-sm transition-colors">
            <?php partial('icon', ['name' => 'plus', 'class' => 'w-5 h-5 ml-2']); ?>
            افزودن سوال جدید
        </a>
    </div>

    <!-- Mobile List View -->
    <div class="block md:hidden divide-y divide-gray-100 dark:divide-gray-700">
        <?php if (empty($items)): ?>
            <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                هیچ سوالی یافت نشد.
            </div>
        <?php else: ?>
            <?php foreach ($items as $item): ?>
                <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                    <div class="flex justify-between items-start mb-2">
                        <div class="flex-1">
                            <h3 class="text-sm font-bold text-gray-900 dark:text-white line-clamp-2"><?= htmlspecialchars($item['question']) ?></h3>
                             <div class="flex items-center gap-2 mt-1">
                                <span class="text-xs bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 px-2 py-0.5 rounded">
                                    <?= htmlspecialchars($faq_type_labels[$item['type']] ?? 'تعیین نشده') ?>
                                </span>
                                <span class="text-xs bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 px-2 py-0.5 rounded">
                                    ترتیب: <?= htmlspecialchars($item['position']) ?>
                                </span>
                                <?php if ($item['status'] === 'active'): ?>
                                    <span class="text-xs text-emerald-600 dark:text-emerald-400 font-medium">فعال</span>
                                <?php else: ?>
                                    <span class="text-xs text-red-600 dark:text-red-400 font-medium">غیرفعال</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end items-center gap-3 pt-2 mt-2 border-t border-gray-100 dark:border-gray-700">
                        <a href="<?= url('/faq/edit/' . $item['id']) ?>" class="text-indigo-600 dark:text-indigo-400 text-sm font-medium">ویرایش</a>
                        <span class="text-gray-300 dark:text-gray-600">|</span>
                        <form action="<?= url('/faq/delete/' . $item['id']) ?>" method="POST" class="inline-block" onsubmit="return confirm('آیا از حذف این سوال مطمئن هستید؟');">
                            <?php partial('csrf_field'); ?>
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
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">سوال</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">نوع</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider text-center">ترتیب</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider text-center">وضعیت</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider text-center">عملیات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700 bg-white dark:bg-gray-800">
                 <?php if (empty($items)): ?>
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400 text-sm">
                            هیچ سوالی یافت نشد.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($items as $item): ?>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors group">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white max-w-md truncate">
                                <?= htmlspecialchars($item['question']) ?>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                <?= htmlspecialchars($faq_type_labels[$item['type']] ?? 'تعیین نشده') ?>
                            </td>
                            <td class="px-6 py-4 text-sm text-center text-gray-500 dark:text-gray-400">
                                <span class="bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded font-mono text-xs">
                                    <?= htmlspecialchars($item['position']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-center">
                                <?php if ($item['status'] === 'active'): ?>
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 dark:bg-emerald-900/20 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-800">فعال</span>
                                <?php else: ?>
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-50 text-red-700 dark:bg-red-900/20 dark:text-red-400 border border-red-100 dark:border-red-800">غیرفعال</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-sm text-center">
                                <div class="flex items-center justify-center gap-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <a href="<?= url('/faq/edit/' . $item['id']) ?>" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 p-1 rounded-md hover:bg-indigo-50 dark:hover:bg-indigo-900/30" title="ویرایش">
                                        <?php partial('icon', ['name' => 'edit', 'class' => 'w-5 h-5']); ?>
                                    </a>
                                    <form action="<?= url('/faq/delete/' . $item['id']) ?>" method="POST" class="inline-block" onsubmit="return confirm('آیا از حذف این سوال مطمئن هستید؟');">
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
