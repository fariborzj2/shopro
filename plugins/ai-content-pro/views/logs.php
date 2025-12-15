<?php
// plugins/ai-content-pro/views/logs.php
include_once PROJECT_ROOT . '/views/layouts/header.php';
?>

<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-slate-800">لاگ‌های سیستم</h1>
        <a href="/admin/ai-content/settings" class="btn btn-secondary">بازگشت به تنظیمات</a>
    </div>

    <div class="bg-white rounded-2xl shadow-card overflow-hidden">
        <table class="w-full text-right">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-6 py-4 font-medium text-slate-600">زمان</th>
                    <th class="px-6 py-4 font-medium text-slate-600">سطح</th>
                    <th class="px-6 py-4 font-medium text-slate-600">پیام</th>
                    <th class="px-6 py-4 font-medium text-slate-600">جزئیات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                <?php foreach ($logs as $log): ?>
                <tr>
                    <td class="px-6 py-4 text-sm text-slate-600 dir-ltr text-right">
                        <?php echo $log['created_at']; ?>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            <?php
                            switch($log['level']) {
                                case 'error': echo 'bg-red-100 text-red-800'; break;
                                case 'warning': echo 'bg-yellow-100 text-yellow-800'; break;
                                default: echo 'bg-blue-100 text-blue-800';
                            }
                            ?>">
                            <?php echo $log['level']; ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-800">
                        <?php echo htmlspecialchars($log['message']); ?>
                    </td>
                     <td class="px-6 py-4 text-sm text-slate-500 font-mono text-xs dir-ltr">
                        <?php echo htmlspecialchars(substr($log['context'], 0, 100)) . (strlen($log['context']) > 100 ? '...' : ''); ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($logs)): ?>
                <tr>
                    <td colspan="4" class="px-6 py-8 text-center text-slate-500">
                        هیچ لاگی ثبت نشده است.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include_once PROJECT_ROOT . '/views/layouts/footer.php'; ?>
