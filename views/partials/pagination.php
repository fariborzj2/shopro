<?php if ($paginator->total_pages > 1): ?>
<div class="mt-6 flex justify-between items-center text-sm font-sans">

    <!-- Previous Page Link -->
    <a href="<?= $paginator->getPrevUrl(); ?>"
       class="px-4 py-2 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 <?= !$paginator->hasPrev() ? 'text-gray-400 cursor-not-allowed' : 'text-gray-700' ?>"
       <?= !$paginator->hasPrev() ? 'aria-disabled="true"' : '' ?>>
        قبلی
    </a>

    <!-- Page Number Information -->
    <div class="text-gray-600">
        صفحه <span class="font-bold"><?= $paginator->current_page; ?></span> از <span class="font-bold"><?= $paginator->total_pages; ?></span>
    </div>

    <!-- Next Page Link -->
    <a href="<?= $paginator->getNextUrl(); ?>"
       class="px-4 py-2 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 <?= !$paginator->hasNext() ? 'text-gray-400 cursor-not-allowed' : 'text-gray-700' ?>"
       <?= !$paginator->hasNext() ? 'aria-disabled="true"' : '' ?>>
        بعدی
    </a>

</div>
<?php endif; ?>
