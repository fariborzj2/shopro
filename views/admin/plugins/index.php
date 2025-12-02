<div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md transition-colors duration-300">
    <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">مدیریت پلاگین‌ها</h1>
        <form action="/admin/plugins/upload" method="post" enctype="multipart/form-data" class="flex items-center gap-2 w-full sm:w-auto">
            <?php csrf_field(); ?>
            <input type="file" name="plugin_zip" accept=".zip" class="border dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-200 p-2 rounded w-full text-sm" required>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors whitespace-nowrap">آپلود پلاگین</button>
        </form>
    </div>

    <?php if (empty($plugins)): ?>
        <p class="text-gray-500 dark:text-gray-400 text-center py-8">هیچ پلاگینی نصب نشده است.</p>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse">
                <thead>
                    <tr class="bg-gray-100 dark:bg-gray-700 border-b dark:border-gray-600">
                        <th class="p-3 text-gray-700 dark:text-gray-200">نام</th>
                        <th class="p-3 text-gray-700 dark:text-gray-200">نامک (Slug)</th>
                        <th class="p-3 text-gray-700 dark:text-gray-200">نسخه</th>
                        <th class="p-3 text-gray-700 dark:text-gray-200">وضعیت</th>
                        <th class="p-3 text-gray-700 dark:text-gray-200">عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($plugins as $plugin): ?>
                        <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors">
                            <td class="p-3 font-medium text-gray-900 dark:text-gray-100"><?= htmlspecialchars($plugin['name']) ?></td>
                            <td class="p-3 text-gray-600 dark:text-gray-400"><?= htmlspecialchars($plugin['slug']) ?></td>
                            <td class="p-3 text-sm text-gray-600 dark:text-gray-400"><?= htmlspecialchars($plugin['version']) ?></td>
                            <td class="p-3">
                                <?php if ($plugin['status'] === 'active'): ?>
                                    <span class="bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 px-2 py-1 rounded text-xs border border-green-200 dark:border-green-800">فعال</span>
                                <?php else: ?>
                                    <span class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300 px-2 py-1 rounded text-xs border border-gray-200 dark:border-gray-600">غیرفعال</span>
                                <?php endif; ?>
                            </td>
                            <td class="p-3 flex gap-2">
                                <?php if ($plugin['status'] === 'inactive'): ?>
                                    <form action="/admin/plugins/activate/<?= $plugin['slug'] ?>" method="post">
                                        <?php csrf_field(); ?>
                                        <button class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300 text-sm font-medium transition-colors">فعال‌سازی</button>
                                    </form>
                                    <form action="/admin/plugins/delete/<?= $plugin['slug'] ?>" method="post" onsubmit="return confirm('آیا از حذف این پلاگین اطمینان دارید؟ تمام اطلاعات آن حذف خواهد شد.');">
                                        <?php csrf_field(); ?>
                                        <button class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 text-sm font-medium transition-colors">حذف</button>
                                    </form>
                                <?php else: ?>
                                    <form action="/admin/plugins/deactivate/<?= $plugin['slug'] ?>" method="post">
                                        <?php csrf_field(); ?>
                                        <button class="text-yellow-600 hover:text-yellow-800 dark:text-yellow-400 dark:hover:text-yellow-300 text-sm font-medium transition-colors">غیرفعال‌سازی</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
