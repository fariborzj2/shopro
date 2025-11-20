<?php
// views/partials/pagination.php
// Expects $paginator object

if (!$paginator || $paginator->total_pages <= 1) return;

$current = $paginator->current_page;
$total = $paginator->total_pages;
$range = 2; // Number of pages to show around current page

?>
<div class="flex items-center justify-between">
    <div class="flex-1 flex justify-between sm:hidden">
        <?php if ($paginator->getPrevUrl()): ?>
            <a href="<?= $paginator->getPrevUrl() ?>" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600">
                قبلی
            </a>
        <?php else: ?>
            <span class="relative inline-flex items-center px-4 py-2 border border-gray-200 text-sm font-medium rounded-md text-gray-300 bg-gray-50 dark:bg-gray-800 dark:text-gray-600 dark:border-gray-700 cursor-not-allowed">
                قبلی
            </span>
        <?php endif; ?>

        <?php if ($paginator->getNextUrl()): ?>
            <a href="<?= $paginator->getNextUrl() ?>" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600">
                بعدی
            </a>
        <?php else: ?>
            <span class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-200 text-sm font-medium rounded-md text-gray-300 bg-gray-50 dark:bg-gray-800 dark:text-gray-600 dark:border-gray-700 cursor-not-allowed">
                بعدی
            </span>
        <?php endif; ?>
    </div>

    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
        <div>
            <p class="text-sm text-gray-700 dark:text-gray-400">
                نمایش
                <span class="font-medium"><?= ($current - 1) * $paginator->items_per_page + 1 ?></span>
                تا
                <span class="font-medium"><?= min($current * $paginator->items_per_page, $paginator->total_items) ?></span>
                از
                <span class="font-medium"><?= $paginator->total_items ?></span>
                نتیجه
            </p>
        </div>
        <div>
            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px space-x-reverse" aria-label="Pagination">
                <!-- Previous Page -->
                <?php if ($paginator->getPrevUrl()): ?>
                    <a href="<?= $paginator->getPrevUrl() ?>" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                        <span class="sr-only">قبلی</span>
                        <!-- Chevron Right for RTL -->
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                <?php else: ?>
                     <span class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-gray-50 text-sm font-medium text-gray-300 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-600 cursor-not-allowed">
                        <span class="sr-only">قبلی</span>
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </span>
                <?php endif; ?>

                <!-- Page Numbers -->
                <?php for ($i = 1; $i <= $total; $i++): ?>
                    <?php if ($i == 1 || $i == $total || ($i >= $current - $range && $i <= $current + $range)): ?>
                        <?php if ($i == $current): ?>
                            <span aria-current="page" class="z-10 bg-indigo-50 border-indigo-500 text-indigo-600 relative inline-flex items-center px-4 py-2 border text-sm font-medium dark:bg-indigo-900/20 dark:border-indigo-500 dark:text-indigo-300">
                                <?= $i ?>
                            </span>
                        <?php else: ?>
                            <a href="<?= $paginator->buildUrl($i) ?>" class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-600">
                                <?= $i ?>
                            </a>
                        <?php endif; ?>
                    <?php elseif ($i == $current - $range - 1 || $i == $current + $range + 1): ?>
                        <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-400">
                            ...
                        </span>
                    <?php endif; ?>
                <?php endfor; ?>

                <!-- Next Page -->
                <?php if ($paginator->getNextUrl()): ?>
                    <a href="<?= $paginator->getNextUrl() ?>" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                        <span class="sr-only">بعدی</span>
                        <!-- Chevron Left for RTL -->
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </a>
                <?php else: ?>
                    <span class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-gray-50 text-sm font-medium text-gray-300 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-600 cursor-not-allowed">
                        <span class="sr-only">بعدی</span>
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </span>
                <?php endif; ?>
            </nav>
        </div>
    </div>
</div>
