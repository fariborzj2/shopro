<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">گزارشات فنی هوش مصنوعی</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">مانیتورینگ خطاهای API و وقایع سیستم</p>
        </div>
        <form action="/admin/ai-content-pro/logs/clear" method="POST" onsubmit="return confirm('آیا از پاکسازی تمام لاگ‌ها مطمئن هستید؟')">
            <?php echo csrf_field(); ?>
            <button type="submit" class="px-4 py-2 bg-red-50 text-red-600 hover:bg-red-100 font-bold rounded-xl transition-all flex items-center gap-2">
                <?php partial('icon', ['name' => 'trash', 'class' => 'w-4 h-4']); ?>
                پاکسازی کل گزارشات
            </button>
        </form>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-soft border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-right">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-700/50 text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider border-b border-gray-100 dark:border-gray-700">
                        <th class="px-6 py-4 font-bold">سطح</th>
                        <th class="px-6 py-4 font-bold">پیام</th>
                        <th class="px-6 py-4 font-bold">زمان ثبت</th>
                        <th class="px-6 py-4 font-bold text-center">جزئیات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    <?php if (empty($logs)): ?>
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                هیچ گزارشی یافت نشد.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($logs as $log): ?>
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/20 transition-colors">
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase <?php echo $log['level'] === 'error' ? 'bg-red-100 text-red-600' : 'bg-blue-100 text-blue-600'; ?>">
                                        <?php echo $log['level']; ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-700 dark:text-gray-300 max-w-md truncate" title="<?php echo htmlspecialchars($log['message']); ?>">
                                        <?php echo htmlspecialchars($log['message']); ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-xs text-gray-500 dark:text-gray-400">
                                    <?php echo \jdate('Y/m/d H:i:s', strtotime($log['created_at'])); ?>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <?php if (!empty($log['context']) && $log['context'] !== '[]'): ?>
                                        <button @click="$dispatch('open-ai-suggestion', { title: 'جزئیات فنی', content: '<?php echo addslashes($log['context']); ?>', callback: () => {} })"
                                                class="text-primary-600 hover:underline text-xs font-bold">
                                            مشاهده Context
                                        </button>
                                    <?php else: ?>
                                        <span class="text-gray-300 text-xs">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if ($paginator->total_pages > 1): ?>
            <div class="p-6 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 flex justify-center gap-2">
                <?php for ($i = 1; $i <= $paginator->total_pages; $i++): ?>
                    <a href="?page=<?php echo $i; ?>" class="w-8 h-8 flex items-center justify-center rounded-lg text-sm <?php echo $i == $paginator->current_page ? 'bg-primary-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-gray-700'; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
