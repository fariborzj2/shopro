<div class="mt-8">
    <div class="mt-4">
        <div class="p-6 bg-white rounded-md shadow-md">
            <h2 class="text-lg text-gray-700 font-semibold capitalize"><?php echo isset($field) ? 'ویرایش فیلد' : 'ایجاد فیلد جدید'; ?></h2>

            <form action="<?php echo isset($field) ? url('custom-fields/update/' . $field['id']) : url('custom-fields/store'); ?>" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-4">
                    <div>
                        <label class="text-gray-700" for="name">نام (انگلیسی و بدون فاصله)</label>
                        <input class="form-input w-full mt-2 rounded-md focus:border-indigo-600" type="text" name="name" id="name" value="<?php echo htmlspecialchars($field['name'] ?? ''); ?>" required>
                    </div>

                    <div>
                        <label class="text-gray-700" for="label_fa">برچسب (فارسی)</label>
                        <input class="form-input w-full mt-2 rounded-md focus:border-indigo-600" type="text" name="label_fa" id="label_fa" value="<?php echo htmlspecialchars($field['label_fa'] ?? ''); ?>" required>
                    </div>

                    <div>
                        <label class="text-gray-700" for="type">نوع فیلد</label>
                        <select class="form-select w-full mt-2 rounded-md focus:border-indigo-600" name="type" id="type" required>
                            <option value="text" <?php echo (isset($field) && $field['type'] === 'text') ? 'selected' : ''; ?>>متنی</option>
                            <option value="textarea" <?php echo (isset($field) && $field['type'] === 'textarea') ? 'selected' : ''; ?>>متن بلند</option>
                            <option value="select" <?php echo (isset($field) && $field['type'] === 'select') ? 'selected' : ''; ?>>انتخابی</option>
                            <option value="checkbox" <?php echo (isset($field) && $field['type'] === 'checkbox') ? 'selected' : ''; ?>>چک‌باکس</option>
                            <option value="radio" <?php echo (isset($field) && $field['type'] === 'radio') ? 'selected' : ''; ?>>رادیویی</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-gray-700" for="options">گزینه‌ها (برای نوع انتخابی، چک‌باکس و رادیویی)</label>
                        <textarea class="form-textarea w-full mt-2 rounded-md focus:border-indigo-600" name="options" id="options" rows="3"><?php echo htmlspecialchars($field['options'] ?? ''); ?></textarea>
                        <p class="text-xs text-gray-500 mt-1">هر گزینه را در یک خط جدید وارد کنید.</p>
                    </div>

                    <div class="col-span-2">
                        <label class="flex items-center">
                            <input type="checkbox" class="form-checkbox" name="is_required" value="1" <?php echo (isset($field) && $field['is_required']) ? 'checked' : ''; ?>>
                            <span class="mx-2 text-gray-700">این فیلد الزامی است</span>
                        </label>
                    </div>
                </div>

                <div class="flex justify-end mt-4">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:bg-indigo-700">ذخیره</button>
                </div>
            </form>
        </div>
    </div>
</div>
