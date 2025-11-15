<h1 class="text-3xl font-bold mb-6">افزودن دسته‌بندی جدید</h1>

<div class="bg-white shadow-md rounded-lg p-8">
    <form action="/categories/store" method="POST">
        <?php partial('csrf_field'); ?>
        <div class="mb-4">
            <label for="name_fa" class="block text-gray-700 text-sm font-bold mb-2">نام فارسی</label>
            <input type="text" id="name_fa" name="name_fa" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>

        <div class="mb-4">
            <label for="name_en" class="block text-gray-700 text-sm font-bold mb-2">نام انگلیسی</label>
            <input type="text" id="name_en" name="name_en" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>

        <div class="mb-4">
            <label for="slug" class="block text-gray-700 text-sm font-bold mb-2">اسلاگ</label>
            <input type="text" id="slug" name="slug" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>

        <div class="mb-4">
            <label for="parent_id" class="block text-gray-700 text-sm font-bold mb-2">والد</label>
            <select id="parent_id" name="parent_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <option value="">— بدون والد —</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['name_fa']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-4">
            <label for="position" class="block text-gray-700 text-sm font-bold mb-2">موقعیت</label>
            <input type="number" id="position" name="position" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="0">
        </div>

        <div class="mb-6">
            <label for="status" class="block text-gray-700 text-sm font-bold mb-2">وضعیت</label>
            <select id="status" name="status" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <option value="active" selected>فعال</option>
                <option value="inactive">غیرفعال</option>
            </select>
        </div>

        <div class="flex items-center justify-between">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                ذخیره
            </button>
            <a href="/categories" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                انصراف
            </a>
        </div>
    </form>
</div>
