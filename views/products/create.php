<h1 class="text-3xl font-bold mb-6">افزودن محصول جدید</h1>

<div class="bg-white shadow-md rounded-lg p-8">
    <form action="<?php echo url('products/store') ?>" method="POST">
        <?php partial('csrf_field'); ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="mb-4">
                <label for="name_fa" class="block text-gray-700 text-sm font-bold mb-2">نام فارسی</label>
                <input type="text" id="name_fa" name="name_fa" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
            <div class="mb-4">
                <label for="name_en" class="block text-gray-700 text-sm font-bold mb-2">نام انگلیسی</label>
                <input type="text" id="name_en" name="name_en" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="mb-4">
                <label for="category_id" class="block text-gray-700 text-sm font-bold mb-2">دسته‌بندی</label>
                <select id="category_id" name="category_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    <option value="">— انتخاب دسته‌بندی —</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['id'] ?>"><?php echo htmlspecialchars($category['name_fa']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-4">
                <label for="dollar_price" class="block text-gray-700 text-sm font-bold mb-2">قیمت دلاری ($)</label>
                <input type="number" step="0.01" id="dollar_price" name="dollar_price" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="mb-4">
                <label for="price" class="block text-gray-700 text-sm font-bold mb-2">قیمت (تومان)</label>
                <input type="number" id="price" name="price" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
            <div class="mb-4">
                <label for="old_price" class="block text-gray-700 text-sm font-bold mb-2">قیمت قبلی (تومان)</label>
                <input type="number" id="old_price" name="old_price" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="mb-4">
                <label for="position" class="block text-gray-700 text-sm font-bold mb-2">موقعیت</label>
                <input type="number" id="position" name="position" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="0">
            </div>
            <div class="mb-6">
                <label for="status" class="block text-gray-700 text-sm font-bold mb-2">وضعیت</label>
                <select id="status" name="status" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="available" selected>موجود</option>
                    <option value="unavailable">ناموجود</option>
                    <option value="draft">پیش‌نویس</option>
                </select>
            </div>
        </div>

        <div class="flex items-center justify-between mt-6">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                ذخیره
            </button>
            <a href="<?php echo url('products') ?>" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                انصراف
            </a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dollarPriceInput = document.getElementById('dollar_price');
    const tomanPriceInput = document.getElementById('price');
    const autoUpdateEnabled = <?php echo json_encode($auto_update_prices ?? false) ?>;
    const exchangeRate = <?php echo json_encode($dollar_exchange_rate ?? 50000) ?>;

    function calculatePrice() {
        if (autoUpdateEnabled && dollarPriceInput.value) {
            const dollarPrice = parseFloat(dollarPriceInput.value);
            if (!isNaN(dollarPrice)) {
                tomanPriceInput.value = Math.round(dollarPrice * exchangeRate);
            }
        }
    }

    if (autoUpdateEnabled) {
        tomanPriceInput.readOnly = true;
        dollarPriceInput.addEventListener('input', calculatePrice);
    }
});
</script>
