<h1 class="text-3xl font-bold mb-6">ویرایش محصول: <?php echo htmlspecialchars($product['name_fa']); ?></h1>

<div class="bg-white shadow-md rounded-lg p-8">
    <form action="<?php echo url('products/update/' . $product['id']); ?>" method="POST">
        <?php partial('csrf_field'); ?>

        <?php require '_form.php'; ?>

        <div class="flex items-center justify-between mt-6">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                به‌روزرسانی
            </button>
            <a href="<?php echo url('products'); ?>" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                انصراف
            </a>
        </div>
    </form>
</div>
