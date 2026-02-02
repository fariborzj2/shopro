<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">داشبورد اتوماسیون محتوا</h1>
        <div class="flex gap-2">
            <a href="/admin/ai-news/sources" class="px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm font-bold hover:bg-gray-50 transition-all">مدیریت منابع</a>
            <a href="/admin/ai-news/settings" class="px-4 py-2 bg-primary-600 text-white rounded-xl text-sm font-bold hover:bg-primary-700 transition-all">تنظیمات</a>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-3xl shadow-soft border border-gray-100">
            <p class="text-xs font-bold text-gray-400 mb-1 uppercase">منابع فعال</p>
            <h3 class="text-3xl font-black text-gray-800"><?php echo $sourcesCount; ?></h3>
        </div>
        <div class="bg-white p-6 rounded-3xl shadow-soft border border-gray-100">
            <p class="text-xs font-bold text-gray-400 mb-1 uppercase">مقالات منتشر شده</p>
            <h3 class="text-3xl font-black text-green-600"><?php echo $stats['processed'] ?? 0; ?></h3>
        </div>
        <div class="bg-white p-6 rounded-3xl shadow-soft border border-gray-100">
            <p class="text-xs font-bold text-gray-400 mb-1 uppercase">در صف انتظار</p>
            <h3 class="text-3xl font-black text-amber-500"><?php echo $stats['pending'] ?? 0; ?></h3>
        </div>
        <div class="bg-white p-6 rounded-3xl shadow-soft border border-gray-100">
            <p class="text-xs font-bold text-gray-400 mb-1 uppercase">خطاها</p>
            <h3 class="text-3xl font-black text-red-500"><?php echo $stats['failed'] ?? 0; ?></h3>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Logs -->
        <div class="lg:col-span-2 bg-white rounded-3xl shadow-soft border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-50 flex justify-between items-center">
                <h3 class="font-bold text-gray-800">آخرین فعالیت‌ها</h3>
                <span class="text-[10px] text-gray-400">نمایش ۱۰ مورد اخیر</span>
            </div>
            <div class="divide-y divide-gray-50 max-h-[400px] overflow-y-auto">
                <?php foreach ($logs as $log): ?>
                <div class="px-6 py-4 flex gap-4">
                    <div class="mt-1">
                        <?php if ($log['level'] === 'error'): ?>
                            <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                        <?php elseif ($log['level'] === 'warning'): ?>
                            <div class="w-2 h-2 bg-amber-500 rounded-full"></div>
                        <?php else: ?>
                            <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                        <?php endif; ?>
                    </div>
                    <div>
                        <p class="text-sm text-gray-700 leading-relaxed"><?php echo htmlspecialchars($log['message']); ?></p>
                        <span class="text-[10px] text-gray-400 mt-1 block"><?php echo \jdate('Y/m/d H:i:s', strtotime($log['created_at'])); ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php if (empty($logs)): ?>
                <div class="px-6 py-12 text-center text-gray-400">هیچ فعالیتی ثبت نشده است.</div>
                <?php endif; ?>
            </div>
        </div>

        <!-- System Info -->
        <div class="bg-white rounded-3xl shadow-soft border border-gray-100 p-6 space-y-4">
             <h3 class="font-bold text-gray-800 mb-4">وضعیت سیستم</h3>
             <div class="space-y-3">
                 <div class="flex justify-between items-center py-2 border-b border-gray-50">
                     <span class="text-sm text-gray-500">وضعیت اتوماسیون</span>
                     <span class="text-sm font-bold <?php echo ($settings['ai_news_status'] ?? '') === 'active' ? 'text-green-600' : 'text-red-500'; ?>">
                        <?php echo ($settings['ai_news_status'] ?? '') === 'active' ? 'فعال (در حال اجرا)' : 'غیرفعال'; ?>
                     </span>
                 </div>
                 <div class="flex justify-between items-center py-2 border-b border-gray-50">
                     <span class="text-sm text-gray-500">مدل پیش‌فرض</span>
                     <span class="text-sm font-bold text-gray-700"><?php echo $settings['ai_news_model'] ?? '---'; ?></span>
                 </div>
                 <div class="flex justify-between items-center py-2 border-b border-gray-50">
                     <span class="text-sm text-gray-500">فواصل زمانی</span>
                     <span class="text-sm font-bold text-gray-700"><?php echo $settings['ai_news_interval'] ?? '360'; ?> دقیقه</span>
                 </div>
             </div>

             <div class="pt-4">
                 <div class="p-4 bg-primary-50 rounded-2xl border border-primary-100">
                     <p class="text-xs text-primary-700 leading-relaxed">
                         سیستم اتوماسیون اخبار به صورت دوره‌ای منابع تعریف شده را کرال کرده و بهترین مطالب را پس از ترجمه و بازنویسی توسط هوش مصنوعی، در وبلاگ شما منتشر می‌کند.
                     </p>
                 </div>
             </div>
        </div>
    </div>
</div>
