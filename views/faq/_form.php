<?php
// Determine if we are in "create" or "edit" mode
$isEdit = isset($item) && !empty($item['id']);
$actionUrl = $isEdit ? url('faq/update/' . $item['id']) : url('faq/store');
?>

<form action="<?php echo $actionUrl; ?>" method="POST">
    <?php csrf_field(); ?>

    <div class="grid grid-cols-1 gap-6">
        <!-- Question -->
        <div>
            <label for="question" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">سوال <span class="text-red-500">*</span></label>
            <input type="text" id="question" name="question"
                   value="<?php echo htmlspecialchars($item['question'] ?? ''); ?>"
                   class="w-full rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2.5 focus:ring-primary-500 focus:border-primary-500 shadow-sm transition-colors"
                   required>
        </div>

        <!-- Answer -->
        <div>
            <label for="answer" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">پاسخ <span class="text-red-500">*</span></label>
            <textarea id="answer" name="answer" rows="5"
                      class="w-full rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2.5 focus:ring-primary-500 focus:border-primary-500 shadow-sm transition-colors"
                      required><?php echo htmlspecialchars($item['answer'] ?? ''); ?></textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Position -->
            <div>
                <label for="position" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">ترتیب نمایش</label>
                <input type="number" id="position" name="position"
                       value="<?php echo htmlspecialchars($item['position'] ?? '0'); ?>"
                       class="w-full rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2.5 focus:ring-primary-500 focus:border-primary-500 shadow-sm transition-colors">
            </div>

            <!-- Status -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">وضعیت</label>
                <div class="relative">
                    <select id="status" name="status" class="w-full appearance-none rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2.5 pr-10 focus:ring-primary-500 focus:border-primary-500 shadow-sm transition-colors">
                        <option value="active" <?php echo (isset($item['status']) && $item['status'] === 'active') ? 'selected' : ''; ?>>فعال</option>
                        <option value="inactive" <?php echo (isset($item['status']) && $item['status'] === 'inactive') ? 'selected' : ''; ?>>غیرفعال</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center px-3 text-gray-500">
                        <?php partial('icon', ['name' => 'chevron-down', 'class' => 'w-4 h-4']); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex items-center justify-end gap-4 mt-8 border-t border-gray-100 dark:border-gray-700 pt-6">
        <a href="<?php echo url('faq'); ?>" class="px-4 py-2 rounded-xl text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors font-medium">
            انصراف
        </a>
        <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white font-bold py-2.5 px-6 rounded-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all shadow-lg shadow-primary-500/30">
            <?php echo $isEdit ? 'به‌روزرسانی' : 'ذخیره'; ?>
        </button>
    </div>
</form>
