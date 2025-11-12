<div class="container mx-auto px-4 sm:px-8 py-8">
    <h2 class="text-2xl font-semibold leading-tight"><?php echo $title; ?></h2>

    <form action="<?php echo $field ? '/custom-fields/update/' . $field['id'] : '/custom-fields/store'; ?>" method="POST" class="mt-8 bg-white p-6 rounded-lg shadow-md">
        <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Left Column -->
            <div>
                <div class="mb-4">
                    <label for="label_fa" class="block text-gray-700 text-sm font-bold mb-2">برچسب (فارسی) *</label>
                    <input type="text" id="label_fa" name="label_fa" value="<?php echo htmlspecialchars($field['label_fa'] ?? ''); ?>" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <p class="text-xs text-gray-500 mt-1">این برچسب به کاربر نمایش داده می‌شود.</p>
                </div>

                <div class="mb-4">
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">نام متغیر (انگلیسی) *</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($field['name'] ?? ''); ?>" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" style="direction: ltr;">
                    <p class="text-xs text-gray-500 mt-1">برای استفاده در سیستم. فقط حروف انگلیسی، اعداد و آندرلاین. مثال: `custom_engraving_text`</p>
                </div>

                <div class="mb-4">
                    <label for="type" class="block text-gray-700 text-sm font-bold mb-2">نوع فیلد *</label>
                    <select id="type" name="type" required class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <?php
                            $types = ['text', 'textarea', 'number', 'select', 'radio', 'checkbox', 'date', 'file', 'color', 'wysiwyg'];
                            foreach($types as $type):
                        ?>
                            <option value="<?php echo $type; ?>" <?php echo (isset($field['type']) && $field['type'] === $type) ? 'selected' : ''; ?>>
                                <?php echo ucfirst($type); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-4" id="options_container" style="display: none;">
                    <label for="options" class="block text-gray-700 text-sm font-bold mb-2">گزینه‌ها</label>
                    <textarea id="options" name="options" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" style="direction: ltr;"><?php echo htmlspecialchars($field['options'] ?? ''); ?></textarea>
                    <p class="text-xs text-gray-500 mt-1">برای فیلدهای select, radio, checkbox. هر گزینه در یک خط جدید. می‌توانید از `value:Label` استفاده کنید. مثال: `red:رنگ قرمز`</p>
                </div>

                <div class="mb-4">
                    <label for="help_text" class="block text-gray-700 text-sm font-bold mb-2">متن راهنما</label>
                    <input type="text" id="help_text" name="help_text" value="<?php echo htmlspecialchars($field['help_text'] ?? ''); ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <p class="text-xs text-gray-500 mt-1">یک توضیح کوتاه که زیر فیلد نمایش داده می‌شود.</p>
                </div>

                 <div class="mb-4">
                    <label for="placeholder" class="block text-gray-700 text-sm font-bold mb-2">Placeholder</label>
                    <input type="text" id="placeholder" name="placeholder" value="<?php echo htmlspecialchars($field['placeholder'] ?? ''); ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>

            </div>

            <!-- Right Column -->
            <div>
                 <div class="mb-4">
                    <label for="default_value" class="block text-gray-700 text-sm font-bold mb-2">مقدار پیش‌فرض</label>
                    <input type="text" id="default_value" name="default_value" value="<?php echo htmlspecialchars($field['default_value'] ?? ''); ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>

                <div class="mb-4">
                    <label for="validation_rules" class="block text-gray-700 text-sm font-bold mb-2">قوانین اعتبارسنجی</label>
                    <input type="text" id="validation_rules" name="validation_rules" value="<?php echo htmlspecialchars($field['validation_rules'] ?? ''); ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" style="direction: ltr;">
                    <p class="text-xs text-gray-500 mt-1">مثال: `required|email`, `regex:/^[0-9]+$/|min:5|max:10` (در حال حاضر نمایشی)</p>
                </div>

                <div class="mb-4">
                    <label for="position" class="block text-gray-700 text-sm font-bold mb-2">ترتیب نمایش</label>
                    <input type="number" id="position" name="position" value="<?php echo htmlspecialchars($field['position'] ?? '0'); ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">وضعیت</label>
                    <select id="status" name="status" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="active" <?php echo (isset($field['status']) && $field['status'] === 'active') ? 'selected' : ''; ?>>فعال</option>
                        <option value="inactive" <?php echo (isset($field['status']) && $field['status'] === 'inactive') ? 'selected' : ''; ?>>غیرفعال</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="is_required" value="1" <?php echo (isset($field['is_required']) && $field['is_required']) ? 'checked' : ''; ?> class="form-checkbox h-5 w-5 text-indigo-600">
                        <span class="ml-2 text-gray-700">این فیلد اجباری است</span>
                    </label>
                </div>

            </div>
        </div>

        <div class="mt-8 flex justify-end">
            <a href="/custom-fields" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline ml-4">
                انصراف
            </a>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                ذخیره
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('type');
    const optionsContainer = document.getElementById('options_container');
    const optionableTypes = ['select', 'radio', 'checkbox'];

    function toggleOptionsContainer() {
        if (optionableTypes.includes(typeSelect.value)) {
            optionsContainer.style.display = 'block';
        } else {
            optionsContainer.style.display = 'none';
        }
    }

    // Initial check
    toggleOptionsContainer();

    // Event listener
    typeSelect.addEventListener('change', toggleOptionsContainer);
});
</script>
