<div class="mt-8">
    <div class="p-6 bg-white rounded-md shadow-md">
        <h2 class="text-lg text-gray-700 font-semibold capitalize">
            <?php echo isset($field->id) ? 'ویرایش فیلد: ' . htmlspecialchars($field->label_fa) : 'ایجاد فیلد جدید'; ?>
        </h2>

        <form action="<?php echo isset($field->id) ? url('/custom-fields/update/' . $field->id) : url('/custom-fields/store'); ?>" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-4">

                <div>
                    <label class="text-gray-700" for="name">نام (انگلیسی، بدون فاصله)</label>
                    <input class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-300 rounded-md focus:border-indigo-500 focus:ring-indigo-500 focus:ring-opacity-50" type="text" name="name" id="name" value="<?php echo htmlspecialchars($field->name ?? ''); ?>" required>
                </div>

                <div>
                    <label class="text-gray-700" for="label_fa">برچسب (فارسی)</label>
                    <input class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-300 rounded-md focus:border-indigo-500 focus:ring-indigo-500 focus:ring-opacity-50" type="text" name="label_fa" id="label_fa" value="<?php echo htmlspecialchars($field->label_fa ?? ''); ?>" required>
                </div>

                <div>
                    <label class="text-gray-700" for="type">نوع فیلد</label>
                    <select class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-300 rounded-md focus:border-indigo-500 focus:ring-indigo-500 focus:ring-opacity-50" name="type" id="type" required>
                        <option value="text" <?php echo (isset($field->type) && $field->type === 'text') ? 'selected' : ''; ?>>متنی</option>
                        <option value="textarea" <?php echo (isset($field->type) && $field->type === 'textarea') ? 'selected' : ''; ?>>متن بلند</option>
                        <option value="select" <?php echo (isset($field->type) && $field->type === 'select') ? 'selected' : ''; ?>>انتخابی</option>
                        <option value="checkbox" <?php echo (isset($field->type) && $field->type === 'checkbox') ? 'selected' : ''; ?>>چک‌باکس</option>
                        <option value="radio" <?php echo (isset($field->type) && $field->type === 'radio') ? 'selected' : ''; ?>>رادیویی</option>
                        <option value="number" <?php echo (isset($field->type) && $field->type === 'number') ? 'selected' : ''; ?>>عددی</option>
                    </select>
                </div>

                <div class="sm:col-span-2 lg:col-span-3">
                    <label class="text-gray-700" for="options">گزینه‌ها (برای نوع انتخابی، چک‌باکس و رادیویی)</label>
                    <textarea class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-300 rounded-md focus:border-indigo-500 focus:ring-indigo-500 focus:ring-opacity-50" name="options" id="options" rows="4"><?php echo htmlspecialchars($field->options ?? ''); ?></textarea>
                    <p class="text-xs text-gray-500 mt-1">هر گزینه را در یک خط جدید وارد کنید. فرمت: `value:Label` (مثال: `red:قرمز`).</p>
                </div>

                <div>
                    <label class="text-gray-700" for="default_value">مقدار پیش‌فرض</label>
                    <input class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-300 rounded-md focus:border-indigo-500 focus:ring-indigo-500 focus:ring-opacity-50" type="text" name="default_value" id="default_value" value="<?php echo htmlspecialchars($field->default_value ?? ''); ?>">
                </div>

                <div>
                    <label class="text-gray-700" for="placeholder">متن جایگزین (Placeholder)</label>
                    <input class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-300 rounded-md focus:border-indigo-500 focus:ring-indigo-500 focus:ring-opacity-50" type="text" name="placeholder" id="placeholder" value="<?php echo htmlspecialchars($field->placeholder ?? ''); ?>">
                </div>

                <div>
                    <label class="text-gray-700" for="validation_rules">قوانین اعتبارسنجی</label>
                    <input class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-300 rounded-md focus:border-indigo-500 focus:ring-indigo-500 focus:ring-opacity-50" type="text" name="validation_rules" id="validation_rules" value="<?php echo htmlspecialchars($field->validation_rules ?? ''); ?>">
                    <p class="text-xs text-gray-500 mt-1">مثال: `required|min:3`</p>
                </div>

                <div class="sm:col-span-2 lg:col-span-3">
                    <label class="text-gray-700" for="help_text">متن راهنما</label>
                    <input class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-300 rounded-md focus:border-indigo-500 focus:ring-indigo-500 focus:ring-opacity-50" type="text" name="help_text" id="help_text" value="<?php echo htmlspecialchars($field->help_text ?? ''); ?>">
                </div>

                <div>
                    <label class="text-gray-700" for="position">ترتیب نمایش</label>
                    <input class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-300 rounded-md focus:border-indigo-500 focus:ring-indigo-500 focus:ring-opacity-50" type="number" name="position" id="position" value="<?php echo htmlspecialchars($field->position ?? '0'); ?>">
                </div>

                <div>
                    <label class="text-gray-700" for="status">وضعیت</label>
                    <select class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-300 rounded-md focus:border-indigo-500 focus:ring-indigo-500 focus:ring-opacity-50" name="status" id="status" required>
                        <option value="active" <?php echo (isset($field->status) && $field->status === 'active') ? 'selected' : ''; ?>>فعال</option>
                        <option value="inactive" <?php echo (isset($field->status) && $field->status === 'inactive') ? 'selected' : ''; ?>>غیرفعال</option>
                    </select>
                </div>

                <div class="flex items-center pt-6">
                    <label class="flex items-center">
                        <input type="checkbox" class="h-5 w-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500" name="is_required" value="1" <?php echo (isset($field->is_required) && $field->is_required) ? 'checked' : ''; ?>>
                        <span class="mx-2 text-gray-700">این فیلد الزامی است</span>
                    </label>
                </div>

            </div>

            <div class="flex justify-end mt-6">
                <a href="<?php echo url('/custom-fields'); ?>" class="px-4 py-2 text-gray-700 rounded-md hover:bg-gray-200 ml-4">انصراف</a>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:bg-indigo-700">
                    <?php echo isset($field->id) ? 'به‌روزرسانی فیلد' : 'ایجاد فیلد'; ?>
                </button>
            </div>
        </form>
    </div>
</div>
