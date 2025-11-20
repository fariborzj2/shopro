<div class="max-w-5xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">ویرایش صفحه</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">ویرایش محتوای صفحه: <?= htmlspecialchars($page['title']) ?></p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
        <form action="<?= url('/pages/update/' . $page['id']) ?>" method="POST" class="p-6">
             <?php partial('csrf_field'); ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Title -->
                <div class="col-span-1">
                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">عنوان صفحه <span class="text-red-500">*</span></label>
                    <input type="text" id="title" name="title" value="<?= htmlspecialchars($page['title']) ?>" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2.5 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all shadow-sm" required>
                </div>

                <!-- Slug -->
                <div class="col-span-1">
                    <label for="slug" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">اسلاگ (Slug) <span class="text-red-500">*</span></label>
                    <input type="text" id="slug" name="slug" value="<?= htmlspecialchars($page['slug']) ?>" dir="ltr" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2.5 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all shadow-sm font-mono text-sm" required>
                </div>

                 <!-- Status -->
                <div class="col-span-1">
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">وضعیت انتشار</label>
                    <div class="relative">
                        <select id="status" name="status" class="w-full appearance-none rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2.5 pr-10 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all shadow-sm">
                            <option value="draft" <?= $page['status'] === 'draft' ? 'selected' : '' ?>>پیش‌نویس</option>
                            <option value="published" <?= $page['status'] === 'published' ? 'selected' : '' ?>>منتشر شده</option>
                        </select>
                         <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center px-3 text-gray-500">
                            <?php partial('icon', ['name' => 'chevron-down', 'class' => 'w-4 h-4']); ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="mb-6">
                <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">محتوای صفحه</label>
                <div class="rounded-xl overflow-hidden border border-gray-300 dark:border-gray-600">
                    <textarea id="content" name="content" class="tinymce-editor"><?= htmlspecialchars($page['content']) ?></textarea>
                </div>
            </div>

            <!-- Actions -->
            <div class="pt-6 border-t border-gray-100 dark:border-gray-700 flex items-center justify-end space-x-3 space-x-reverse">
                <a href="<?= url('/pages') ?>" class="px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 font-medium transition-colors">
                    انصراف
                </a>
                <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 shadow-md hover:shadow-lg font-medium transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    به‌روزرسانی
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const isDark = document.documentElement.classList.contains('dark');
        tinymce.init({
            selector: '.tinymce-editor',
            plugins: 'directionality link image lists table media code',
            toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | code ltr rtl',
            language: 'fa',
            height: 400,
            relative_urls: false,
            remove_script_host: false,
             skin: isDark ? 'oxide-dark' : 'oxide',
            content_css: isDark ? 'dark' : 'default',
             // Image Upload
            image_title: true,
            automatic_uploads: true,
            file_picker_types: 'image',
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
                    if (json.csrf_token) {
                        document.querySelector('meta[name="csrf-token"]').setAttribute('content', json.csrf_token);
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
