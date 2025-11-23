<?php
// Determine if we are in "create" or "edit" mode based on $category existence
$isEdit = isset($category) && !empty($category['id']);
$actionUrl = $isEdit ? url('blog/categories/update/' . $category['id']) : url('blog/categories/store');
?>

<form action="<?php echo $actionUrl; ?>" method="POST">
    <?php csrf_field(); ?>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Name FA -->
        <div class="mb-4">
            <label for="name_fa" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">نام فارسی <span class="text-red-500">*</span></label>
            <input type="text" id="name_fa" name="name_fa" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2.5 focus:ring-primary-500 focus:border-primary-500 shadow-sm" value="<?php echo htmlspecialchars($category['name_fa'] ?? ''); ?>" required>
        </div>

        <!-- Name EN -->
        <div class="mb-4">
            <label for="name_en" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">نام انگلیسی</label>
            <input type="text" id="name_en" name="name_en" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2.5 focus:ring-primary-500 focus:border-primary-500 shadow-sm" value="<?php echo htmlspecialchars($category['name_en'] ?? ''); ?>">
        </div>

        <!-- Slug -->
        <div class="mb-4 col-span-1 md:col-span-2">
            <label for="slug" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">اسلاگ (Slug) <span class="text-red-500">*</span></label>
            <input type="text" id="slug" name="slug" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2.5 focus:ring-primary-500 focus:border-primary-500 shadow-sm" value="<?php echo htmlspecialchars($category['slug'] ?? ''); ?>" required dir="ltr">
        </div>

        <!-- Parent Category -->
        <div class="mb-4">
            <label for="parent_id" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">والد</label>
            <div class="relative">
                <select id="parent_id" name="parent_id" class="w-full appearance-none rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2.5 pr-10 focus:ring-primary-500 focus:border-primary-500 shadow-sm">
                    <option value="">— بدون والد —</option>
                    <?php
                    $cats = $allCategories ?? $categories ?? [];
                    foreach ($cats as $cat): ?>
                        <?php
                        // Prevent selecting self as parent
                        if ($isEdit && $cat['id'] === $category['id']) continue;
                        $selected = (isset($category['parent_id']) && $cat['id'] == $category['parent_id']) ? 'selected' : '';
                        ?>
                        <option value="<?php echo $cat['id']; ?>" <?php echo $selected; ?>><?php echo htmlspecialchars($cat['name_fa']); ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center px-3 text-gray-500">
                    <?php partial('icon', ['name' => 'chevron-down', 'class' => 'w-4 h-4']); ?>
                </div>
            </div>
        </div>

        <!-- Position -->
        <div class="mb-4">
            <label for="position" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">موقعیت</label>
            <input type="number" id="position" name="position" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2.5 focus:ring-primary-500 focus:border-primary-500 shadow-sm" value="<?php echo htmlspecialchars($category['position'] ?? '0'); ?>">
        </div>

        <!-- Status -->
        <div class="mb-6">
            <label for="status" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">وضعیت</label>
            <div class="relative">
                <select id="status" name="status" class="w-full appearance-none rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2.5 pr-10 focus:ring-primary-500 focus:border-primary-500 shadow-sm">
                    <option value="active" <?php echo (isset($category['status']) && $category['status'] === 'active') ? 'selected' : ''; ?>>فعال</option>
                    <option value="inactive" <?php echo (isset($category['status']) && $category['status'] === 'inactive') ? 'selected' : ''; ?>>غیرفعال</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center px-3 text-gray-500">
                    <?php partial('icon', ['name' => 'chevron-down', 'class' => 'w-4 h-4']); ?>
                </div>
            </div>
        </div>

        <!-- Description -->
        <div class="mb-4 col-span-1 md:col-span-2">
            <label for="notes" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">توضیحات</label>
            <div class="rounded-xl overflow-hidden border border-gray-300 dark:border-gray-600">
                <textarea id="notes" name="notes" class="tinymce-editor"><?php echo htmlspecialchars($category['notes'] ?? ''); ?></textarea>
            </div>
        </div>
    </div>

    <?php $tinyMceContext = 'blog_categories'; ?>
    <?php require_once PROJECT_ROOT . '/views/partials/tinymce_config.php'; ?>

    <div class="flex items-center justify-end mt-8 pt-6 border-t border-gray-100 dark:border-gray-700 gap-4">
        <a href="/blog/categories" class="px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 font-medium transition-colors">
            انصراف
        </a>
        <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 shadow-md hover:shadow-lg font-medium transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
            <?php echo $isEdit ? 'به‌روزرسانی' : 'ذخیره'; ?>
        </button>
    </div>
</form>
