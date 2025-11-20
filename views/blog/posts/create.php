<div class="max-w-5xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">افزودن پست جدید</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">ایجاد مقاله یا خبر جدید در بلاگ</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
        <form action="<?php echo url('blog/posts/store'); ?>" method="POST" enctype="multipart/form-data" class="p-6">
            <?php echo csrf_field(); ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Title -->
                <div class="col-span-1">
                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">عنوان پست <span class="text-red-500">*</span></label>
                    <input type="text" id="title" name="title" class="w-full rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2.5 focus:ring-primary-500 focus:border-primary-500 shadow-sm" required>
                </div>

                <!-- Slug -->
                <div class="col-span-1">
                    <label for="slug" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">اسلاگ (URL)</label>
                    <input type="text" id="slug" name="slug" dir="ltr" class="w-full rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2.5 focus:ring-primary-500 focus:border-primary-500 shadow-sm font-mono text-sm">
                    <p class="text-xs text-gray-500 mt-1">در صورت خالی بودن، از روی عنوان ساخته می‌شود.</p>
                </div>

                <!-- Category -->
                <div class="col-span-1">
                    <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">دسته‌بندی</label>
                    <div class="relative">
                        <select id="category_id" name="category_id" class="w-full appearance-none rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2.5 pr-10 focus:ring-primary-500 focus:border-primary-500 shadow-sm">
                            <option value="">انتخاب کنید...</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center px-3 text-gray-500">
                            <?php partial('icon', ['name' => 'chevron-down', 'class' => 'w-4 h-4']); ?>
                        </div>
                    </div>
                </div>

                <!-- Status -->
                <div class="col-span-1">
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">وضعیت انتشار</label>
                    <div class="relative">
                        <select id="status" name="status" class="w-full appearance-none rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2.5 pr-10 focus:ring-primary-500 focus:border-primary-500 shadow-sm">
                            <option value="draft">پیش‌نویس</option>
                            <option value="published">منتشر شده</option>
                            <option value="archived">آرشیو شده</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center px-3 text-gray-500">
                            <?php partial('icon', ['name' => 'chevron-down', 'class' => 'w-4 h-4']); ?>
                        </div>
                    </div>
                </div>

                <!-- Image -->
                <div class="col-span-1 md:col-span-2">
                    <label for="image" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">تصویر شاخص</label>
                    <input type="file" id="image" name="image" class="w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 dark:file:bg-primary-900/20 dark:file:text-primary-400 transition-colors">
                </div>
            </div>

            <!-- Summary -->
            <div class="mb-6">
                <label for="summary" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">خلاصه متن</label>
                <textarea id="summary" name="summary" rows="3" class="w-full rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2.5 focus:ring-primary-500 focus:border-primary-500 shadow-sm"></textarea>
            </div>

            <!-- Content -->
            <div class="mb-6">
                <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">محتوای کامل</label>
                <div class="rounded-xl overflow-hidden border border-gray-300 dark:border-gray-600">
                    <textarea id="content" name="content" class="tinymce-editor"></textarea>
                </div>
            </div>

            <div class="border-t border-gray-100 dark:border-gray-700 pt-6 mt-6">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4">تنظیمات سئو</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                     <div class="mb-4">
                        <label for="meta_title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">عنوان متا (Meta Title)</label>
                        <input type="text" id="meta_title" name="meta_title" class="w-full rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2.5 focus:ring-primary-500 focus:border-primary-500 shadow-sm">
                    </div>
                     <div class="mb-4">
                        <label for="meta_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">توضیحات متا (Meta Description)</label>
                        <textarea id="meta_description" name="meta_description" rows="2" class="w-full rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2.5 focus:ring-primary-500 focus:border-primary-500 shadow-sm"></textarea>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end mt-8 pt-6 border-t border-gray-100 dark:border-gray-700 gap-4">
                <a href="<?php echo url('blog/posts'); ?>" class="px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 font-medium transition-colors">انصراف</a>
                <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 shadow-md hover:shadow-lg font-medium transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    ذخیره پست
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
                formData.append('context', 'blog_posts');
                xhr.send(formData);
            })
        });
    });
</script>
