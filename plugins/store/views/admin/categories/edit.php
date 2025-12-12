<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">ویرایش دسته بندی</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            ویرایش دسته‌بندی: <?php echo htmlspecialchars($category->name_fa) ?>
        </p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
        <form action="<?php echo url('categories/update/' . $category->id); ?>" method="POST" enctype="multipart/form-data" accept-charset="UTF-8" class="p-6">

            <?php require '_form.php'; ?>

            <!-- Action Buttons -->
            <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-700 flex items-center justify-end space-x-3 space-x-reverse">
                <a href="<?php echo url('categories'); ?>" class="px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 font-medium transition-colors">
                    انصراف
                </a>
                <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 shadow-md hover:shadow-lg font-medium transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    به‌روزرسانی دسته‌بندی
                </button>
            </div>
        </form>
    </div>
</div>
