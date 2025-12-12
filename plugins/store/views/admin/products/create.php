<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">افزودن محصول جدید</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">اطلاعات محصول جدید را وارد کنید.</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
        <form action="<?php echo url('products/store'); ?>" method="POST" class="p-6">

            <?php require '_form.php'; ?>

            <!-- Action Buttons -->
            <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-700 flex items-center justify-end space-x-3 space-x-reverse">
                <a href="<?php echo url('products'); ?>" class="px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 font-medium transition-colors">
                    انصراف
                </a>
                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 shadow-md hover:shadow-lg font-medium transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    ذخیره محصول
                </button>
            </div>
        </form>
    </div>
</div>
