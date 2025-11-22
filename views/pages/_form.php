<?php
// Determine if we are in "create" or "edit" mode
$isEdit = isset($page) && !empty($page['id']);
$actionUrl = $isEdit ? url('pages/update/' . $page['id']) : url('pages/store');
?>

<?php require_once __DIR__ . '/../partials/tag_input_script.php'; ?>

<form action="<?php echo $actionUrl; ?>" method="POST">
    <?php csrf_field(); ?>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- Title -->
        <div class="col-span-1">
            <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">عنوان صفحه <span class="text-red-500">*</span></label>
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($page['title'] ?? ''); ?>" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2.5 focus:ring-primary-500 focus:border-primary-500 shadow-sm transition-colors" required>
        </div>

        <!-- Slug -->
        <div class="col-span-1">
            <label for="slug" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">اسلاگ (URL) <span class="text-red-500">*</span></label>
            <input type="text" id="slug" name="slug" value="<?php echo htmlspecialchars($page['slug'] ?? ''); ?>" dir="ltr" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2.5 focus:ring-primary-500 focus:border-primary-500 shadow-sm font-mono text-sm transition-colors" required>
        </div>

        <!-- Status -->
        <div class="col-span-1">
            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">وضعیت انتشار</label>
            <div class="relative">
                <select id="status" name="status" class="w-full appearance-none rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2.5 pr-10 focus:ring-primary-500 focus:border-primary-500 shadow-sm transition-colors">
                    <option value="draft" <?php echo (isset($page['status']) && $page['status'] == 'draft') ? 'selected' : ''; ?>>پیش‌نویس</option>
                    <option value="published" <?php echo (isset($page['status']) && $page['status'] == 'published') ? 'selected' : ''; ?>>منتشر شده</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center px-3 text-gray-500">
                    <?php partial('icon', ['name' => 'chevron-down', 'class' => 'w-4 h-4']); ?>
                </div>
            </div>
        </div>

        <!-- Published At (Persian Date Picker) -->
        <div class="col-span-1">
             <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="published_at">تاریخ و ساعت انتشار</label>
             <div class="relative">
                 <input type="text" id="published_at" name="published_at" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2.5 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all shadow-sm"
                        autocomplete="off" placeholder="انتخاب کنید..." />
             </div>
        </div>
    </div>

    <!-- Short Description -->
    <div class="mb-6">
        <label for="short_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">توضیحات کوتاه</label>
        <textarea id="short_description" name="short_description" rows="3" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2.5 focus:ring-primary-500 focus:border-primary-500 shadow-sm transition-colors"><?php echo htmlspecialchars($page['short_description'] ?? ''); ?></textarea>
    </div>

    <!-- Content -->
    <div class="mb-6">
        <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">محتوای کامل</label>
        <div class="rounded-xl overflow-hidden border border border-gray-300 dark:border-gray-600">
            <textarea id="content" name="content" class="tinymce-editor"><?php echo htmlspecialchars($page['content'] ?? ''); ?></textarea>
        </div>
    </div>

    <!-- SEO Section -->
    <div class="border-t border-gray-200 dark:border-gray-700 pt-6 mt-8">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">تنظیمات سئو</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Meta Title -->
            <div class="col-span-1">
                <label for="meta_title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">عنوان متا (Meta Title)</label>
                <input type="text" id="meta_title" name="meta_title" value="<?php echo htmlspecialchars($page['meta_title'] ?? ''); ?>" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2.5 focus:ring-primary-500 focus:border-primary-500 shadow-sm transition-colors">
            </div>

            <!-- Meta Keywords (Tag Input) -->
            <div class="col-span-1">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">کلمات کلیدی متا (Meta Keywords)</label>
                <div x-data="tagInput({
                    initialTags: <?php echo isset($page['meta_keywords']) && $page['meta_keywords'] ? json_encode(explode(',', $page['meta_keywords'])) : '[]'; ?>,
                    fieldName: 'meta_keywords[]',
                    noPrefix: true
                })" class="w-full">
                    <div class="relative rounded-xl border border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-2 py-1.5 flex flex-wrap gap-2 focus-within:ring-2 focus-within:ring-primary-500/20 focus-within:border-primary-500 transition-all shadow-sm min-h-[46px]" @click="$refs.input.focus()">
                        <!-- Chips -->
                        <template x-for="(tag, index) in items" :key="index">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-primary-50 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400">
                                <span x-text="tag"></span>
                                <button type="button" @click.stop="removeTag(index)" class="ml-1.5 text-primary-400 hover:text-primary-600 dark:text-primary-500 dark:hover:text-primary-300 focus:outline-none">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                                <input type="hidden" :name="fieldName" :value="tag">
                            </span>
                        </template>

                        <!-- Input -->
                        <input x-ref="input" type="text" x-model="inputValue" @keydown="handleKeydown" @keydown.backspace="handleKeydown"
                               class="flex-1 min-w-[120px] bg-transparent border-none outline-none focus:ring-0 p-1 text-sm text-gray-900 dark:text-white placeholder-gray-400"
                               placeholder="تایپ کنید و Enter بزنید...">
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-1">برای افزودن Enter یا کاما بزنید.</p>
            </div>

            <!-- Meta Description -->
            <div class="col-span-1 md:col-span-2">
                <label for="meta_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">توضیحات متا (Meta Description)</label>
                <textarea id="meta_description" name="meta_description" rows="3" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2.5 focus:ring-primary-500 focus:border-primary-500 shadow-sm transition-colors"><?php echo htmlspecialchars($page['meta_description'] ?? ''); ?></textarea>
            </div>
        </div>
    </div>

    <div class="flex items-center justify-end gap-4 mt-8 border-t border-gray-100 dark:border-gray-700 pt-6">
        <a href="<?php echo url('pages'); ?>" class="px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 font-medium transition-colors">
            انصراف
        </a>
        <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 shadow-md hover:shadow-lg font-medium transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
            <?php echo $isEdit ? 'به‌روزرسانی' : 'ذخیره'; ?>
        </button>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function () {
         // Initialize Custom Jalali Datepicker
        const publishedAtSelector = '#published_at';
        if (document.querySelector(publishedAtSelector)) {
            let initialValue = null;
            <?php if (!empty($page['published_at'])): ?>
                // Convert PHP Gregorian timestamp (seconds) to JS Date object or milliseconds
                initialValue = <?php echo strtotime($page['published_at']) * 1000; ?>;
            <?php endif; ?>

            new JalaliDatepicker(publishedAtSelector, {
                initialValue: initialValue
            });
        }

        const isDark = document.documentElement.classList.contains('dark');
        tinymce.init({
            selector: '.tinymce-editor',
            plugins: 'directionality link image lists table media code',
            toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | code ltr rtl',
            language: 'fa',
            height: 500,
            relative_urls: false,
            remove_script_host: false,
            directionality: 'rtl',
            skin: isDark ? 'oxide-dark' : 'oxide',
            content_css: isDark ? 'dark' : 'default',
             images_upload_handler: (blobInfo, progress) => new Promise((resolve, reject) => {
                const xhr = new XMLHttpRequest();
                xhr.withCredentials = false;
                xhr.open('POST', '<?php echo url('api/upload-image'); ?>');
                xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

                xhr.upload.onprogress = (e) => {
                    progress(e.loaded / e.total * 100);
                };

                xhr.onload = () => {
                    if (xhr.status < 200 || xhr.status >= 300) {
                        return reject('HTTP Error: ' + xhr.status);
                    }
                    const json = JSON.parse(xhr.responseText);
                    if (!json || typeof json.location != 'string') {
                        return reject('Invalid JSON: ' + xhr.responseText);
                    }
                    resolve(json.location);
                };
                xhr.onerror = () => reject('Image upload failed due to a network error.');
                const formData = new FormData();
                formData.append('file', blobInfo.blob(), blobInfo.filename());
                formData.append('context', 'pages');
                xhr.send(formData);
            })
        });
    });
</script>
