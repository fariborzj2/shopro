<div class="mt-8">
    <div class="p-6 bg-white rounded-md shadow-md">
        <h2 class="text-lg text-gray-700 font-semibold capitalize">کتابخانه رسانه</h2>

        <?php if (empty($mediaItems)): ?>
            <p class="mt-4 text-gray-500">هیچ رسانه‌ای یافت نشد.</p>
        <?php else: ?>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 mt-4">
                <?php foreach ($mediaItems as $item): ?>
                    <div class="relative group border rounded-md overflow-hidden">
                        <img src="<?php echo asset($item->file_path); ?>" alt="Media item" class="h-32 w-full object-cover">
                        <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                            <form action="<?php echo url('media/delete/' . $item->id); ?>" method="POST" onsubmit="return confirm('آیا از حذف این تصویر مطمئن هستید؟');">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="text-white text-xs bg-red-600 hover:bg-red-700 rounded-full p-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Pagination -->
        <div class="mt-6">
            <?php if ($paginator->total_pages > 1): ?>
                <nav class="flex items-center justify-between">
                    <a href="<?php echo $paginator->getPrevUrl(); ?>" class="px-4 py-2 bg-gray-200 rounded-md <?php echo !$paginator->hasPrev() ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-300'; ?>">
                        قبلی
                    </a>
                    <span>
                        صفحه <?php echo $paginator->current_page; ?> از <?php echo $paginator->total_pages; ?>
                    </span>
                    <a href="<?php echo $paginator->getNextUrl(); ?>" class="px-4 py-2 bg-gray-200 rounded-md <?php echo !$paginator->hasNext() ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-300'; ?>">
                        بعدی
                    </a>
                </nav>
            <?php endif; ?>
        </div>
    </div>
</div>
