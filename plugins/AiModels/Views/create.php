<?php
// app/Plugins/AiModels/Views/create.php
?>
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
        <a href="/admin/ai-models" class="hover:text-primary-600 transition-colors">مدیریت مدل‌ها</a>
        <span>/</span>
        <span class="text-gray-800 dark:text-gray-200">افزودن مدل جدید</span>
    </div>

    <h1 class="text-2xl font-bold text-gray-800 dark:text-white tracking-tight">افزودن مدل هوش مصنوعی</h1>

    <?php require __DIR__ . '/_form.php'; ?>
</div>
