<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">تقویم محتوایی هوشمند</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">برنامه‌ریزی محتوا برای رشد هدفمند سایت</p>
        </div>
        <div class="flex gap-2">
            <a href="/admin/ai-content-pro/generator?tool=calendar" class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-xl transition-all flex items-center gap-2">
                <?php partial('icon', ['name' => 'plus', 'class' => 'w-4 h-4']); ?>
                ایجاد برنامه جدید
            </a>
        </div>
    </div>

    <!-- Calendar View (List of cards) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php if (empty($items)): ?>
            <div class="col-span-full py-12 flex flex-col items-center justify-center bg-white dark:bg-gray-800 rounded-2xl border border-dashed border-gray-300 dark:border-gray-700">
                <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-full mb-4">
                     <?php partial('icon', ['name' => 'pages', 'class' => 'w-12 h-12 text-gray-400']); ?>
                </div>
                <p class="text-gray-500 dark:text-gray-400">هنوز هیچ محتوایی برنامه‌ریزی نشده است.</p>
                <a href="/admin/ai-content-pro/generator?tool=calendar" class="mt-4 text-primary-600 hover:underline">اولین برنامه را بسازید</a>
            </div>
        <?php else: ?>
            <?php foreach ($items as $item): ?>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-soft border border-gray-100 dark:border-gray-700 hover:border-primary-500 transition-all group relative">
                    <div class="flex justify-between items-start mb-4">
                         <span class="px-2 py-1 bg-primary-50 text-primary-600 dark:bg-primary-900/20 dark:text-primary-400 text-[10px] font-bold rounded uppercase tracking-wider">
                            <?php echo $item['content_type']; ?>
                        </span>
                        <form action="/admin/ai-content-pro/calendar/delete/<?php echo $item['id']; ?>" method="POST" onsubmit="return confirm('حذف شود؟')">
                            <?php csrf_field(); ?>
                            <button type="submit" class="text-gray-400 hover:text-red-600 transition-colors">
                                <?php partial('icon', ['name' => 'trash', 'class' => 'w-4 h-4']); ?>
                            </button>
                        </form>
                    </div>

                    <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-2 line-clamp-2"><?php echo htmlspecialchars($item['title']); ?></h3>

                    <div class="mt-auto pt-4 flex items-center justify-between border-t border-gray-50 dark:border-gray-700">
                        <div class="flex items-center text-sm text-gray-500 dark:text-gray-400 gap-1">
                             <?php partial('icon', ['name' => 'dashboard', 'class' => 'w-4 h-4']); ?>
                            <span><?php echo \jdate('Y/m/d', strtotime($item['due_date'])); ?></span>
                        </div>

                        <?php
                        $status_colors = [
                            'planned' => 'bg-amber-50 text-amber-600 border-amber-100',
                            'drafted' => 'bg-blue-50 text-blue-600 border-blue-100',
                            'published' => 'bg-green-50 text-green-600 border-green-100',
                        ];
                        ?>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold border <?php echo $status_colors[$item['status']] ?? 'bg-gray-50'; ?>">
                            <?php echo translate_status_fa($item['status']); ?>
                        </span>
                    </div>

                    <!-- Action Overlay -->
                    <div class="absolute inset-0 bg-white/60 dark:bg-gray-800/60 backdrop-blur-[1px] opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center rounded-2xl gap-3">
                         <button onclick="createArticleFromCalendar('<?php echo addslashes($item['title']); ?>')" class="p-3 bg-primary-600 text-white rounded-full shadow-lg hover:bg-primary-700 transition-transform hover:scale-110" title="تولید محتوا برای این موضوع">
                             <?php partial('icon', ['name' => 'ai', 'class' => 'w-6 h-6']); ?>
                         </button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<script>
function createArticleFromCalendar(topic) {
    if (confirm(`آیا می‌خواهید محتوا برای موضوع "${topic}" تولید شود؟`)) {
        // Redirect to generator with preset topic
        window.location.href = `/admin/ai-content-pro/generator?tool=article&topic=${encodeURIComponent(topic)}`;
    }
}
</script>
