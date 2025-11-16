<input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">

<div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-4">
    <div>
        <label class="text-gray-700" for="name_fa">نام (فارسی)</label>
        <input class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-300 rounded-md focus:border-indigo-500 focus:ring-indigo-500 focus:ring-opacity-50" type="text" name="name_fa" id="name_fa" value="<?php echo htmlspecialchars($category->name_fa ?? ''); ?>" required>
    </div>
    <div>
        <label class="text-gray-700" for="slug">اسلاگ (انگلیسی)</label>
        <input class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-300 rounded-md focus:border-indigo-500 focus:ring-indigo-500 focus:ring-opacity-50" type="text" name="slug" id="slug" value="<?php echo htmlspecialchars($category->slug ?? ''); ?>" required>
    </div>
    <div>
        <label class="text-gray-700" for="name_en">نام (انگلیسی)</label>
        <input class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-300 rounded-md focus:border-indigo-500 focus:ring-indigo-500 focus:ring-opacity-50" type="text" name="name_en" id="name_en" value="<?php echo htmlspecialchars($category->name_en ?? ''); ?>">
    </div>
    <div>
        <label class="text-gray-700" for="parent_id">دسته‌بندی والد</label>
        <select class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-300 rounded-md focus:border-indigo-500 focus:ring-indigo-500 focus:ring-opacity-50" name="parent_id" id="parent_id">
            <option value="">بدون والد</option>
            <?php foreach ($allCategories as $cat): ?>
                <?php if (!isset($category) || $cat->id !== $category->id): ?>
                    <option value="<?php echo $cat->id; ?>" <?php echo (isset($category->parent_id) && $category->parent_id == $cat->id) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($cat->name_fa); ?>
                    </option>
                <?php endif; ?>
            <?php endforeach; ?>
        </select>
    </div>
     <div>
        <label class="text-gray-700" for="status">وضعیت</label>
        <select class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-300 rounded-md focus:border-indigo-500 focus:ring-indigo-500 focus:ring-opacity-50" name="status" id="status">
            <option value="published" <?php echo (isset($category->status) && $category->status === 'published') ? 'selected' : ''; ?>>منتشر شده</option>
            <option value="draft" <?php echo (isset($category->status) && $category->status === 'draft') ? 'selected' : ''; ?>>پیش‌نویس</option>
        </select>
    </div>
     <div>
        <label class="text-gray-700" for="position">ترتیب نمایش</label>
        <input class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-300 rounded-md focus:border-indigo-500 focus:ring-indigo-500 focus:ring-opacity-50" type="number" name="position" id="position" value="<?php echo htmlspecialchars($category->position ?? '0'); ?>">
    </div>
</div>

<div class="mt-6">
    <h3 class="text-lg font-medium text-gray-700">پارامترهای سفارشی</h3>
    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <?php if (empty($customFields)): ?>
            <p class="text-gray-500 col-span-full">هیچ پارامتر سفارشی تعریف نشده است. <a href="<?php echo url('custom-fields/create'); ?>" class="text-indigo-600 hover:underline">یکی بسازید</a>.</p>
        <?php else: ?>
            <?php foreach ($customFields as $field): ?>
                <label class="flex items-center p-3 bg-gray-50 rounded-md border border-gray-200">
                    <input type="checkbox" name="custom_fields[]" value="<?php echo $field->id; ?>"
                           class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                           <?php echo in_array($field->id, $attachedFieldIds) ? 'checked' : ''; ?>>
                    <span class="ml-2 mr-2 text-sm text-gray-700"><?php echo htmlspecialchars($field->label_fa); ?></span>
                </label>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
