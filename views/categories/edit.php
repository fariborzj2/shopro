<h1 class="text-3xl font-bold mb-6">ویرایش دسته‌بندی: <?= htmlspecialchars($category['name_fa']) ?></h1>

<div class="bg-white shadow-md rounded-lg p-8">
    <form action="/categories/update/<?= $category['id'] ?>" method="POST">
        <div class="mb-4">
            <label for="name_fa" class="block text-gray-700 text-sm font-bold mb-2">نام فارسی</label>
            <input type="text" id="name_fa" name="name_fa" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?= htmlspecialchars($category['name_fa']) ?>" required>
        </div>

        <div class="mb-4">
            <label for="name_en" class="block text-gray-700 text-sm font-bold mb-2">نام انگلیسی</label>
            <input type="text" id="name_en" name="name_en" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?= htmlspecialchars($category['name_en']) ?>">
        </div>

        <div class="mb-4">
            <label for="parent_id" class="block text-gray-700 text-sm font-bold mb-2">والد</label>
            <select id="parent_id" name="parent_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <option value="">— بدون والد —</option>
                <?php foreach ($allCategories as $cat): ?>
                    <?php if ($cat['id'] === $category['id']) continue; // Cannot be its own parent ?>
                    <option value="<?= $cat['id'] ?>" <?= ($cat['id'] == $category['parent_id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['name_fa']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-4">
            <label for="position" class="block text-gray-700 text-sm font-bold mb-2">موقعیت</label>
            <input type="number" id="position" name="position" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?= htmlspecialchars($category['position']) ?>">
        </div>

        <div class="mb-6">
            <label for="status" class="block text-gray-700 text-sm font-bold mb-2">وضعیت</label>
            <select id="status" name="status" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <option value="active" <?= $category['status'] === 'active' ? 'selected' : '' ?>>فعال</option>
                <option value="inactive" <?= $category['status'] === 'inactive' ? 'selected' : '' ?>>غیرفعال</option>
            </select>
        </div>

        <div class="mt-8 border-t pt-6">
             <h3 class="text-lg font-semibold text-gray-800 mb-4">اتصال پارامترها (فیلدهای سفارشی)</h3>
             <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                <?php foreach ($customFields as $field): ?>
                    <div class="flex items-center">
                        <input
                            type="checkbox"
                            name="custom_fields[]"
                            value="<?php echo $field['id']; ?>"
                            id="field_<?php echo $field['id']; ?>"
                            class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                            <?php echo in_array($field['id'], $attachedFieldIds) ? 'checked' : ''; ?>
                        >
                        <label for="field_<?php echo $field['id']; ?>" class="ml-2 block text-sm text-gray-900">
                            <?php echo htmlspecialchars($field['label_fa']); ?> (<code><?php echo htmlspecialchars($field['name']); ?></code>)
                        </label>
                    </div>
                <?php endforeach; ?>
             </div>
             <p class="text-xs text-gray-500 mt-2">فیلدهای انتخاب شده در فرم ثبت سفارش برای محصولات این دسته‌بندی نمایش داده خواهند شد.</p>
        </div>

        <div class="flex items-center justify-between mt-8">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                به‌روزرسانی
            </button>
            <a href="/categories" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                انصراف
            </a>
        </div>
    </form>
</div>
