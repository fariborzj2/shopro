<div class="bg-white p-6 rounded-lg shadow-md">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">مدیریت پلاگین‌ها</h1>
        <form action="/admin/plugins/upload" method="post" enctype="multipart/form-data" class="flex items-center gap-2">
            <?php csrf_field(); ?>
            <input type="file" name="plugin_zip" accept=".zip" class="border p-2 rounded" required>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">آپلود پلاگین</button>
        </form>
    </div>

    <?php if (empty($plugins)): ?>
        <p class="text-gray-500 text-center py-8">هیچ پلاگینی نصب نشده است.</p>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full text-right border-collapse">
                <thead>
                    <tr class="bg-gray-100 border-b">
                        <th class="p-3">نام</th>
                        <th class="p-3">نامک (Slug)</th>
                        <th class="p-3">نسخه</th>
                        <th class="p-3">وضعیت</th>
                        <th class="p-3">عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($plugins as $plugin): ?>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-3 font-medium"><?= htmlspecialchars($plugin['name']) ?></td>
                            <td class="p-3 text-gray-600"><?= htmlspecialchars($plugin['slug']) ?></td>
                            <td class="p-3 text-sm"><?= htmlspecialchars($plugin['version']) ?></td>
                            <td class="p-3">
                                <?php if ($plugin['status'] === 'active'): ?>
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">فعال</span>
                                <?php else: ?>
                                    <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded text-xs">غیرفعال</span>
                                <?php endif; ?>
                            </td>
                            <td class="p-3 flex gap-2">
                                <?php if ($plugin['status'] === 'inactive'): ?>
                                    <form action="/admin/plugins/activate/<?= $plugin['slug'] ?>" method="post">
                                        <?php csrf_field(); ?>
                                        <button class="text-green-600 hover:text-green-800 text-sm">فعال‌سازی</button>
                                    </form>
                                    <form action="/admin/plugins/delete/<?= $plugin['slug'] ?>" method="post" onsubmit="return confirm('آیا از حذف این پلاگین اطمینان دارید؟ تمام اطلاعات آن حذف خواهد شد.');">
                                        <?php csrf_field(); ?>
                                        <button class="text-red-600 hover:text-red-800 text-sm">حذف</button>
                                    </form>
                                <?php else: ?>
                                    <form action="/admin/plugins/deactivate/<?= $plugin['slug'] ?>" method="post">
                                        <?php csrf_field(); ?>
                                        <button class="text-yellow-600 hover:text-yellow-800 text-sm">غیرفعال‌سازی</button>
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
