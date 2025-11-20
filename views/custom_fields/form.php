<div class="max-w-5xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
            <?php echo isset($field->id) ? 'ویرایش پارامتر' : 'افزودن پارامتر جدید'; ?>
        </h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            <?php echo isset($field->id) ? 'ویرایش مشخصات پارامتر ' . htmlspecialchars($field->label_fa) : 'تعریف یک ویژگی جدید برای استفاده در محصولات.'; ?>
        </p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
        <form action="<?php echo isset($field->id) ? url('/admin/custom-fields/update/' . $field->id) : url('/admin/custom-fields/store'); ?>" method="POST" class="p-6">
            <?php partial('csrf_field'); ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name (English) -->
                <div class="col-span-1">
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">نام سیستمی (انگلیسی) <span class="text-red-500">*</span></label>
                    <input type="text" id="name" name="name" dir="ltr"
                           class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2.5 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all shadow-sm font-mono text-sm"
                           value="<?php echo htmlspecialchars($field->name ?? ''); ?>" required placeholder="e.g. color, size">
                    <p class="text-xs text-gray-500 mt-1">فقط حروف انگلیسی و بدون فاصله.</p>
                </div>

                <!-- Label (Persian) -->
                <div class="col-span-1">
                    <label for="label_fa" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">برچسب نمایشی (فارسی) <span class="text-red-500">*</span></label>
                    <input type="text" id="label_fa" name="label_fa"
                           class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2.5 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all shadow-sm"
                           value="<?php echo htmlspecialchars($field->label_fa ?? ''); ?>" required placeholder="مثال: رنگ محصول">
                </div>

                <!-- Type -->
                <div class="col-span-1">
                    <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">نوع فیلد <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <select id="type" name="type" class="w-full appearance-none rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2.5 pr-10 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all shadow-sm" required>
                            <option value="text" <?php echo (isset($field->type) && $field->type === 'text') ? 'selected' : ''; ?>>متنی (Text)</option>
                            <option value="textarea" <?php echo (isset($field->type) && $field->type === 'textarea') ? 'selected' : ''; ?>>متن بلند (Textarea)</option>
                            <option value="select" <?php echo (isset($field->type) && $field->type === 'select') ? 'selected' : ''; ?>>لیست کشویی (Select)</option>
                            <option value="checkbox" <?php echo (isset($field->type) && $field->type === 'checkbox') ? 'selected' : ''; ?>>چک‌باکس (Checkbox)</option>
                            <option value="radio" <?php echo (isset($field->type) && $field->type === 'radio') ? 'selected' : ''; ?>>دکمه رادیویی (Radio)</option>
                            <option value="number" <?php echo (isset($field->type) && $field->type === 'number') ? 'selected' : ''; ?>>عددی (Number)</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center px-3 text-gray-500">
                            <?php partial('icon', ['name' => 'chevron-down', 'class' => 'w-4 h-4']); ?>
                        </div>
                    </div>
                </div>

                <!-- Default Value -->
                <div class="col-span-1">
                    <label for="default_value" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">مقدار پیش‌فرض</label>
                    <input type="text" id="default_value" name="default_value"
                           class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2.5 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all shadow-sm"
                           value="<?php echo htmlspecialchars($field->default_value ?? ''); ?>">
                </div>

                <!-- Options (Textarea) -->
                <div class="col-span-1 md:col-span-2">
                    <label for="options" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">گزینه‌ها (مخصوص لیست، چک‌باکس و رادیو)</label>
                    <div class="relative">
                        <textarea id="options" name="options" rows="4"
                                  class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2.5 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all shadow-sm font-mono text-sm"
                                  placeholder="red:قرمز&#10;blue:آبی"><?php echo htmlspecialchars($field->options ?? ''); ?></textarea>
                        <div class="absolute top-2 left-2">
                            <div class="group relative flex justify-center">
                                <svg class="w-5 h-5 text-gray-400 cursor-help" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span class="absolute bottom-full mb-2 hidden group-hover:block w-64 bg-gray-900 text-white text-xs rounded p-2 z-10">
                                    فرمت: `value:Label`. هر گزینه در یک خط جدید.
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Placeholder -->
                <div class="col-span-1">
                    <label for="placeholder" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">متن راهنما داخل فیلد (Placeholder)</label>
                    <input type="text" id="placeholder" name="placeholder"
                           class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2.5 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all shadow-sm"
                           value="<?php echo htmlspecialchars($field->placeholder ?? ''); ?>">
                </div>

                <!-- Validation Rules -->
                <div class="col-span-1">
                    <label for="validation_rules" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">قوانین اعتبارسنجی</label>
                    <input type="text" id="validation_rules" name="validation_rules" dir="ltr"
                           class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2.5 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all shadow-sm font-mono text-sm"
                           value="<?php echo htmlspecialchars($field->validation_rules ?? ''); ?>" placeholder="required|min:3">
                </div>

                <!-- Help Text -->
                <div class="col-span-1 md:col-span-2">
                    <label for="help_text" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">متن توضیحات زیر فیلد</label>
                    <input type="text" id="help_text" name="help_text"
                           class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2.5 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all shadow-sm"
                           value="<?php echo htmlspecialchars($field->help_text ?? ''); ?>">
                </div>

                <!-- Position -->
                <div class="col-span-1">
                    <label for="position" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">ترتیب نمایش</label>
                    <input type="number" id="position" name="position"
                           class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2.5 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all shadow-sm"
                           value="<?php echo htmlspecialchars($field->position ?? '0'); ?>">
                </div>

                <!-- Status -->
                <div class="col-span-1">
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">وضعیت</label>
                    <div class="relative">
                        <select id="status" name="status" class="w-full appearance-none rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2.5 pr-10 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all shadow-sm" required>
                            <option value="active" <?php echo (isset($field->status) && $field->status === 'active') ? 'selected' : ''; ?>>فعال</option>
                            <option value="inactive" <?php echo (isset($field->status) && $field->status === 'inactive') ? 'selected' : ''; ?>>غیرفعال</option>
                        </select>
                         <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center px-3 text-gray-500">
                            <?php partial('icon', ['name' => 'chevron-down', 'class' => 'w-4 h-4']); ?>
                        </div>
                    </div>
                </div>

                <!-- Is Required Checkbox -->
                <div class="col-span-1 md:col-span-2 flex items-center pt-4">
                    <label class="flex items-center cursor-pointer">
                         <div class="relative">
                            <input type="checkbox" name="is_required" value="1" class="sr-only peer" <?php echo (isset($field->is_required) && $field->is_required) ? 'checked' : ''; ?>>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 dark:peer-focus:ring-primary-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-[-100%] peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:right-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary-600"></div>
                        </div>
                        <span class="mr-3 text-sm font-medium text-gray-900 dark:text-gray-300">این فیلد الزامی است</span>
                    </label>
                </div>

            </div>

            <!-- Actions -->
            <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-700 flex items-center justify-end space-x-3 space-x-reverse">
                <a href="<?php echo url('/admin/custom-fields'); ?>" class="px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 font-medium transition-colors">
                    انصراف
                </a>
                <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 shadow-md hover:shadow-lg font-medium transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    <?php echo isset($field->id) ? 'ذخیره تغییرات' : 'ایجاد پارامتر'; ?>
                </button>
            </div>
        </form>
    </div>
</div>
