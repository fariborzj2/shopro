<!-- Dashboard Header -->
<div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">داشبورد</h1>
        <p class="text-gray-500 dark:text-gray-400 mt-1 text-sm">خلاصه وضعیت سیستم</p>
    </div>
    <div class="flex gap-2">
        <!-- Date Filter Placeholder -->
        <div class="bg-white dark:bg-gray-800 text-sm text-gray-500 dark:text-gray-300 px-4 py-2 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 flex items-center">
            <?php partial('icon', ['name' => 'dashboard', 'class' => 'w-4 h-4 ml-2 text-gray-400']); ?>
            <span><?= jdate('l، j F Y') ?></span>
        </div>
    </div>
</div>

<!-- Dashboard Widgets -->
<?php if (!empty($widgets)): ?>
    <?php foreach ($widgets as $widget): ?>
        <div class="mb-6 lg:mb-8 dashboard-widget-<?= $widget['id'] ?? 'unknown' ?>">
            <?= $widget['content'] ?>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php if (empty($widgets)): ?>
    <div class="p-8 text-center text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 rounded-2xl border border-dashed border-gray-300 dark:border-gray-700">
        <p>ویجتی برای نمایش وجود ندارد.</p>
    </div>
<?php endif; ?>
