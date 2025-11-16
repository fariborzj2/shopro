<input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">

<div x-data="{ activeTab: 'main' }">
    <!-- Tabs Navigation -->
    <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-4" aria-label="Tabs">
            <a href="#" @click.prevent="activeTab = 'main'"
               :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'main', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'main' }"
               class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                اطلاعات اصلی
            </a>
            <a href="#" @click.prevent="activeTab = 'seo'"
               :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'seo', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'seo' }"
               class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                تنظیمات SEO
            </a>
        </nav>
    </div>

    <!-- Main Tab Content -->
    <div x-show="activeTab === 'main'" class="mt-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
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
                    <?php
                        $selectedId = $category->parent_id ?? null;
                        $currentCategoryId = $category->id ?? null;
                        echo build_category_tree_options($allCategories, null, 0, $selectedId, $currentCategoryId);
                    ?>
                </select>
            </div>
            <div>
                <label class="text-gray-700" for="status">وضعیت</label>
                <select class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-300 rounded-md focus:border-indigo-500 focus:ring-indigo-500 focus:ring-opacity-50" name="status" id="status">
                    <option value="active" <?php echo (isset($category->status) && $category->status === 'active') ? 'selected' : ''; ?>>فعال</option>
                    <option value="inactive" <?php echo (isset($category->status) && $category->status === 'inactive') ? 'selected' : ''; ?>>غیرفعال</option>
                </select>
            </div>
            <div>
                <label class="text-gray-700" for="position">ترتیب نمایش</label>
                <input class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-300 rounded-md focus:border-indigo-500 focus:ring-indigo-500 focus:ring-opacity-50" type="number" name="position" id="position" value="<?php echo htmlspecialchars($category->position ?? '0'); ?>">
            </div>
            <div class="sm:col-span-2">
                <label class="text-gray-700" for="short_description">توضیحات کوتاه</label>
                <textarea class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-300 rounded-md focus:border-indigo-500 focus:ring-indigo-500 focus:ring-opacity-50" name="short_description" id="short_description" rows="3"><?php echo htmlspecialchars($category->short_description ?? ''); ?></textarea>
            </div>
            <div class="sm:col-span-2">
                <label class="text-gray-700" for="description">توضیحات بلند</label>
                <textarea class="tinymce-editor" name="description" id="description"><?php echo $category->description ?? ''; ?></textarea>
            </div>
        </div>
    </div>

    <!-- SEO Tab Content -->
    <div x-show="activeTab === 'seo'" class="mt-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div>
                <label class="text-gray-700" for="meta_title">عنوان متا (Title Tag)</label>
                <input class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-300 rounded-md focus:border-indigo-500 focus:ring-indigo-500 focus:ring-opacity-50" type="text" name="meta_title" id="meta_title" value="<?php echo htmlspecialchars($category->meta_title ?? ''); ?>">
            </div>
            <div>
                <label class="text-gray-700" for="meta_keywords">کلمات کلیدی متا</label>
                <input class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-300 rounded-md focus:border-indigo-500 focus:ring-indigo-500 focus:ring-opacity-50" type="text" name="meta_keywords" id="meta_keywords" value="<?php echo htmlspecialchars($category->meta_keywords ?? ''); ?>">
                <p class="text-xs text-gray-500 mt-1">کلمات کلیدی را با کاما (,) جدا کنید.</p>
            </div>
            <div class="sm:col-span-2">
                <label class="text-gray-700" for="meta_description">توضیحات متا (Meta Description)</label>
                <textarea class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-300 rounded-md focus:border-indigo-500 focus:ring-indigo-500 focus:ring-opacity-50" name="meta_description" id="meta_description" rows="3"><?php echo htmlspecialchars($category->meta_description ?? ''); ?></textarea>
            </div>
        </div>
    </div>
</div>

<div class="mt-6 border-t pt-6">
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        tinymce.init({
            selector: '.tinymce-editor',
            plugins: 'directionality link image lists table media code',
            toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | code ltr rtl',
            language: 'fa',
            height: 300,
            relative_urls: false,
            remove_script_host: false
        });
    });
</script>
