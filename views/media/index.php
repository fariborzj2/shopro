<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
    <!-- Header -->
    <div class="p-6 flex flex-col sm:flex-row sm:justify-between sm:items-center border-b border-gray-100 dark:border-gray-700 gap-4">
        <div>
            <h1 class="text-xl font-bold text-gray-800 dark:text-white">کتابخانه رسانه</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">مدیریت تصاویر و فایل‌های آپلود شده</p>
        </div>
        <!-- Could add upload button here if implemented -->
    </div>

    <div class="p-6">
        <?php if (empty($mediaItems)): ?>
            <div class="text-center py-12">
                <div class="w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                    <?php partial('icon', ['name' => 'media', 'class' => 'w-10 h-10 text-gray-400']); ?>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">هیچ رسانه‌ای یافت نشد</h3>
                <p class="text-gray-500 dark:text-gray-400 mt-1">هنوز هیچ فایلی آپلود نشده است.</p>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-8 gap-4">
                <?php foreach ($mediaItems as $item): ?>
                    <div class="group relative aspect-square bg-gray-100 dark:bg-gray-700 rounded-xl overflow-hidden border border-gray-200 dark:border-gray-600">
                        <img src="<?php echo asset($item->file_path); ?>" alt="Media item" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">

                        <!-- Overlay Actions -->
                        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                            <a href="<?php echo asset($item->file_path); ?>" target="_blank" class="p-2 bg-white/90 hover:bg-white rounded-full text-gray-700 shadow-sm transition-colors" title="مشاهده">
                                <?php partial('icon', ['name' => 'eye', 'class' => 'w-4 h-4']); ?>
                            </a>
                            <form action="<?php echo url('media/delete/' . $item->id); ?>" method="POST" onsubmit="return confirm('آیا از حذف این تصویر مطمئن هستید؟');">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="p-2 bg-red-500 hover:bg-red-600 rounded-full text-white shadow-sm transition-colors" title="حذف">
                                    <?php partial('icon', ['name' => 'trash', 'class' => 'w-4 h-4']); ?>
                                </button>
                            </form>
                        </div>

                        <!-- Info Badge -->
                        <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-3 pt-8 opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">
                            <p class="text-xs text-white truncate" dir="ltr"><?= basename($item->file_path) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Pagination -->
        <div class="mt-8 border-t border-gray-100 dark:border-gray-700 pt-6">
             <?php partial('pagination', ['paginator' => $paginator]); ?>
        </div>
    </div>
</div>
