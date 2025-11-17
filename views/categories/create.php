<div class="mt-8">
    <div class="p-6 bg-white rounded-md shadow-md">
        <h2 class="text-lg text-gray-700 font-semibold capitalize">افزودن دسته‌بندی جدید</h2>

        <form action="<?php echo url('categories/store'); ?>" method="POST" enctype="multipart/form-data">
            <?php require '_form.php'; ?>

            <div class="flex justify-end mt-6">
                <a href="<?php echo url('categories'); ?>" class="px-4 py-2 text-gray-700 rounded-md hover:bg-gray-200 ml-4">انصراف</a>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:bg-indigo-700">
                    ایجاد دسته‌بندی
                </button>
            </div>
        </form>
    </div>
</div>
