<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 transition-colors duration-300">
    <!-- Header -->
    <div class="p-6 flex flex-col sm:flex-row sm:justify-between sm:items-center border-b border-gray-100 dark:border-gray-700 gap-4">
        <div>
            <h1 class="text-xl font-bold text-gray-800 dark:text-white">مدیریت پلاگین‌ها</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">نصب و مدیریت افزونه‌های سیستم</p>
        </div>

        <form action="/admin/plugins/upload" method="post" enctype="multipart/form-data" class="flex flex-col sm:flex-row items-center gap-3 w-full sm:w-auto">
            <?php csrf_field(); ?>
            <div class="relative w-full sm:w-auto">
                <input type="file" name="plugin_zip" accept=".zip" class="block w-full text-sm text-gray-500 dark:text-gray-400
                    file:mr-4 file:py-2 file:px-4
                    file:rounded-lg file:border-0
                    file:text-sm file:font-semibold
                    file:bg-primary-50 file:text-primary-700
                    dark:file:bg-primary-900/20 dark:file:text-primary-400
                    hover:file:bg-primary-100 dark:hover:file:bg-primary-900/40
                    cursor-pointer focus:outline-none
                " required>
            </div>
            <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 shadow-sm transition-colors whitespace-nowrap">
                <?php partial('icon', ['name' => 'upload', 'class' => 'w-5 h-5 ml-2']); ?>
                آپلود پلاگین
            </button>
        </form>
    </div>

    <!-- Mobile Cards View -->
    <div class="block md:hidden divide-y divide-gray-100 dark:divide-gray-700">
        <?php if (empty($plugins)): ?>
            <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                هیچ پلاگینی نصب نشده است.
            </div>
        <?php else: ?>
            <?php foreach ($plugins as $plugin): ?>
                <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex items-center gap-3">
                            <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-bold shadow-sm">
                                <?= mb_substr($plugin['name'] ?? 'P', 0, 1) ?>
                            </div>
                            <div class="min-w-0">
                                <h3 class="text-sm font-bold text-gray-900 dark:text-white truncate"><?= htmlspecialchars($plugin['name']) ?></h3>
                                <div class="flex items-center gap-2 mt-0.5">
                                    <span class="text-xs text-gray-500 dark:text-gray-400 font-mono bg-gray-100 dark:bg-gray-700 px-1.5 py-0.5 rounded"><?= htmlspecialchars($plugin['version']) ?></span>
                                    <span class="text-xs text-gray-400 dark:text-gray-500 truncate"><?= htmlspecialchars($plugin['slug']) ?></span>
                                </div>
                            </div>
                        </div>

                        <?php
                            $status_style = match($plugin['status']) {
                                'active' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400',
                                'inactive' => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300',
                                default => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300'
                            };
                            $status_text = match($plugin['status']) {
                                'active' => 'فعال',
                                'inactive' => 'غیرفعال',
                                default => 'نامشخص'
                            };
                        ?>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full <?= $status_style ?>">
                            <?= $status_text ?>
                        </span>
                    </div>

                    <div class="flex justify-end items-center gap-3 pt-3 border-t border-gray-100 dark:border-gray-700/50 mt-3">
                        <?php if ($plugin['status'] === 'inactive'): ?>
                            <form action="/admin/plugins/activate/<?= $plugin['slug'] ?>" method="post">
                                <?php csrf_field(); ?>
                                <button class="flex items-center text-emerald-600 hover:text-emerald-800 dark:text-emerald-400 dark:hover:text-emerald-300 text-xs font-medium transition-colors">
                                    <?php partial('icon', ['name' => 'check', 'class' => 'w-4 h-4 ml-1']); ?>
                                    فعال‌سازی
                                </button>
                            </form>
                            <span class="text-gray-300 dark:text-gray-600">|</span>
                            <form action="/admin/plugins/delete/<?= $plugin['slug'] ?>" method="post" onsubmit="return confirm('آیا از حذف این پلاگین اطمینان دارید؟ تمام اطلاعات آن حذف خواهد شد.');">
                                <?php csrf_field(); ?>
                                <button class="flex items-center text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 text-xs font-medium transition-colors">
                                    <?php partial('icon', ['name' => 'trash', 'class' => 'w-4 h-4 ml-1']); ?>
                                    حذف
                                </button>
                            </form>
                        <?php else: ?>
                            <form action="/admin/plugins/deactivate/<?= $plugin['slug'] ?>" method="post">
                                <?php csrf_field(); ?>
                                <button class="flex items-center text-amber-600 hover:text-amber-800 dark:text-amber-400 dark:hover:text-amber-300 text-xs font-medium transition-colors">
                                    <?php partial('icon', ['name' => 'close', 'class' => 'w-4 h-4 ml-1']); ?>
                                    غیرفعال‌سازی
                                </button>
                            </form>
                        <?php endif; ?>
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
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">نام افزونه</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">نامک (Slug)</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">نسخه</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">وضعیت</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider text-center">عملیات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700 bg-white dark:bg-gray-800">
                <?php if (empty($plugins)): ?>
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400 text-sm">
                            هیچ پلاگینی نصب نشده است.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($plugins as $plugin): ?>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors group">
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white font-medium">
                                <?= htmlspecialchars($plugin['name']) ?>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 font-mono">
                                <?= htmlspecialchars($plugin['slug']) ?>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 font-mono">
                                <span class="bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded text-xs border border-gray-200 dark:border-gray-600">
                                    <?= htmlspecialchars($plugin['version']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <?php
                                    $status_style = match($plugin['status']) {
                                        'active' => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/20 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-800',
                                        'inactive' => 'bg-gray-50 text-gray-700 dark:bg-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-600',
                                        default => 'bg-gray-50 text-gray-700 dark:bg-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-600'
                                    };
                                    $status_text = match($plugin['status']) {
                                        'active' => 'فعال',
                                        'inactive' => 'غیرفعال',
                                        default => 'نامشخص'
                                    };
                                ?>
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium <?= $status_style ?>">
                                    <?= $status_text ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-center">
                                <div class="flex items-center justify-center gap-3">
                                    <?php if ($plugin['status'] === 'inactive'): ?>
                                        <form action="/admin/plugins/activate/<?= $plugin['slug'] ?>" method="post">
                                            <?php csrf_field(); ?>
                                            <button class="text-emerald-600 hover:text-emerald-900 dark:text-emerald-400 dark:hover:text-emerald-300 p-1 rounded-md hover:bg-emerald-50 dark:hover:bg-emerald-900/30 transition-colors" title="فعال‌سازی">
                                                <?php partial('icon', ['name' => 'check', 'class' => 'w-5 h-5']); ?>
                                            </button>
                                        </form>
                                        <form action="/admin/plugins/delete/<?= $plugin['slug'] ?>" method="post" onsubmit="return confirm('آیا از حذف این پلاگین اطمینان دارید؟ تمام اطلاعات آن حذف خواهد شد.');">
                                            <?php csrf_field(); ?>
                                            <button class="text-gray-400 hover:text-red-600 dark:text-gray-500 dark:hover:text-red-400 p-1 rounded-md hover:bg-red-50 dark:hover:bg-red-900/30 transition-colors" title="حذف">
                                                <?php partial('icon', ['name' => 'trash', 'class' => 'w-5 h-5']); ?>
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <form action="/admin/plugins/deactivate/<?= $plugin['slug'] ?>" method="post">
                                            <?php csrf_field(); ?>
                                            <button class="text-amber-600 hover:text-amber-900 dark:text-amber-400 dark:hover:text-amber-300 p-1 rounded-md hover:bg-amber-50 dark:hover:bg-amber-900/30 transition-colors" title="غیرفعال‌سازی">
                                                <?php partial('icon', ['name' => 'close', 'class' => 'w-5 h-5']); ?>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
