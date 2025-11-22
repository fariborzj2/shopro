<input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">

<div x-data="{
    activeTab: 'main',
    imageUrl: '<?php echo isset($category->image_url) ? asset($category->image_url) : ''; ?>',
    thumbnailUrl: '<?php echo isset($category->thumbnail_url) ? asset($category->thumbnail_url) : ''; ?>',

    previewImage(event) {
        const reader = new FileReader();
        reader.onload = (e) => { this.imageUrl = e.target.result; };
        reader.readAsDataURL(event.target.files[0]);
    },
    previewThumbnail(event) {
        const reader = new FileReader();
        reader.onload = (e) => { this.thumbnailUrl = e.target.result; };
        reader.readAsDataURL(event.target.files[0]);
    },
    deleteImage(type) {
        if (!confirm('آیا از حذف این تصویر مطمئن هستید؟')) return;

        let url = `<?php echo isset($category->id) ? url('categories/delete-image/' . $category->id) : ''; ?>/${type}`;
        if (!url) return;

        fetch(url, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '<?php echo csrf_token(); ?>',
                'Content-Type': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                if (type === 'image') {
                    this.imageUrl = '';
                    document.getElementById('image_url').value = '';
                } else {
                    this.thumbnailUrl = '';
                    document.getElementById('thumbnail_url').value = '';
                }
            } else {
                alert(data.message || 'خطا در حذف تصویر');
            }
        })
        .catch(err => console.error('Error:', err));
    }
}">
    <!-- Tabs Navigation -->
    <div class="border-b border-gray-200 dark:border-gray-700 mb-6">
        <nav class="-mb-px flex space-x-6 space-x-reverse" aria-label="Tabs">
            <a href="#" @click.prevent="activeTab = 'main'"
               :class="{ 'border-primary-500 text-primary-600 dark:text-primary-400': activeTab === 'main', 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300': activeTab !== 'main' }"
               class="whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm transition-colors flex items-center gap-2">
               <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                اطلاعات اصلی
            </a>
            <a href="#" @click.prevent="activeTab = 'seo'"
               :class="{ 'border-primary-500 text-primary-600 dark:text-primary-400': activeTab === 'seo', 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300': activeTab !== 'seo' }"
               class="whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm transition-colors flex items-center gap-2">
               <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                تنظیمات SEO
            </a>
        </nav>
    </div>

    <!-- Main Tab Content -->
    <div x-show="activeTab === 'main'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Name FA -->
            <div class="col-span-1">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="name_fa">نام (فارسی) <span class="text-red-500">*</span></label>
                <input class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2.5 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all shadow-sm"
                       type="text" name="name_fa" id="name_fa" value="<?php echo htmlspecialchars($category->name_fa ?? ''); ?>" required>
            </div>

            <!-- Slug -->
            <div class="col-span-1">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="slug">اسلاگ (انگلیسی) <span class="text-red-500">*</span></label>
                <input class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2.5 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all shadow-sm " dir="ltr"
                       type="text" name="slug" id="slug" value="<?php echo htmlspecialchars($category->slug ?? ''); ?>" required>
            </div>

            <!-- Name EN -->
            <div class="col-span-1">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="name_en">نام (انگلیسی)</label>
                <input class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2.5 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all shadow-sm" dir="ltr"
                       type="text" name="name_en" id="name_en" value="<?php echo htmlspecialchars($category->name_en ?? ''); ?>">
            </div>

            <!-- Parent -->
            <div class="col-span-1">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="parent_id">دسته‌بندی والد</label>
                <div class="relative">
                    <select class="w-full appearance-none rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2.5 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all shadow-sm" name="parent_id" id="parent_id">
                        <option value="">-- بدون والد (دسته‌بندی اصلی) --</option>
                        <?php
                            $selectedId = $category->parent_id ?? null;
                            $currentCategoryId = $category->id ?? null;
                            echo build_category_tree_options($allCategories, null, 0, $selectedId, $currentCategoryId);
                        ?>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center px-3 text-gray-500">
                        <?php partial('icon', ['name' => 'chevron-down', 'class' => 'w-4 h-4']); ?>
                    </div>
                </div>
            </div>

            <!-- Status -->
            <div class="col-span-1">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="status">وضعیت انتشار</label>
                <div class="relative">
                    <select class="w-full appearance-none rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2.5 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all shadow-sm" name="status" id="status">
                        <option value="active" <?php echo (isset($category->status) && $category->status === 'active') ? 'selected' : ''; ?>>فعال</option>
                        <option value="inactive" <?php echo (isset($category->status) && $category->status === 'inactive') ? 'selected' : ''; ?>>غیرفعال</option>
                    </select>
                     <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center px-3 text-gray-500">
                        <?php partial('icon', ['name' => 'chevron-down', 'class' => 'w-4 h-4']); ?>
                    </div>
                </div>
            </div>

            <!-- Position -->
            <div class="col-span-1">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="position">ترتیب نمایش</label>
                <input class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2.5 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all shadow-sm"
                       type="number" name="position" id="position" value="<?php echo htmlspecialchars($category->position ?? '0'); ?>">
            </div>

             <!-- Published At (Persian Date Picker) -->
            <div class="col-span-1">
                 <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="published_at">تاریخ و ساعت انتشار</label>
                 <div class="relative">
                     <input type="text" id="published_at" name="published_at" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2.5 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all shadow-sm"
                            autocomplete="off" placeholder="انتخاب کنید..." />
                 </div>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="short_description">توضیحات کوتاه</label>
                <textarea class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2.5 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all shadow-sm"
                          name="short_description" id="short_description" rows="3"><?php echo htmlspecialchars($category->short_description ?? ''); ?></textarea>
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="description">توضیحات کامل</label>
                <div class="rounded-xl overflow-hidden border border-gray-300 dark:border-gray-600">
                    <textarea class="tinymce-editor" name="description" id="description"><?php echo $category->description ?? ''; ?></textarea>
                </div>
            </div>

            <!-- Image Upload -->
            <div class="col-span-1">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">تصویر اصلی</label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-xl bg-gray-50 dark:bg-gray-800/50 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors cursor-pointer group relative">
                     <label for="image_url" class="w-full h-full flex flex-col items-center justify-center cursor-pointer">
                         <!-- Placeholder -->
                        <div x-show="!imageUrl" class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400 group-hover:text-primary-500 transition-colors" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600 dark:text-gray-400 justify-center">
                                <span class="relative cursor-pointer rounded-md font-medium text-primary-600 hover:text-primary-500 focus-within:outline-none">
                                    انتخاب فایل
                                </span>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-500">PNG, JPG, WEBP تا 5MB</p>
                        </div>

                        <!-- Preview -->
                        <div x-show="imageUrl" class="relative w-full h-48" style="display: none;">
                             <img :src="imageUrl" class="w-full h-full object-contain rounded-lg" />
                             <div class="absolute inset-0 bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center rounded-lg">
                                 <span class="text-white text-sm font-medium bg-black/50 px-3 py-1 rounded-full">تغییر تصویر</span>
                             </div>
                        </div>

                        <input id="image_url" name="image_url" type="file" class="sr-only" @change="previewImage($event)">
                    </label>
                </div>
                 <button type="button" x-show="imageUrl && imageUrl.startsWith('http')" @click.prevent="deleteImage('image')" class="mt-2 text-xs text-red-500 hover:text-red-700 flex items-center gap-1" style="display: none;">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    حذف تصویر
                </button>
            </div>

            <!-- Thumbnail Upload -->
            <div class="col-span-1">
                 <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">تصویر کوچک (Thumbnail)</label>
                 <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-xl bg-gray-50 dark:bg-gray-800/50 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors cursor-pointer group relative">
                     <label for="thumbnail_url" class="w-full h-full flex flex-col items-center justify-center cursor-pointer">
                         <!-- Placeholder -->
                         <div x-show="!thumbnailUrl" class="space-y-1 text-center">
                             <svg class="mx-auto h-12 w-12 text-gray-400 group-hover:text-primary-500 transition-colors" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600 dark:text-gray-400 justify-center">
                                <span class="relative cursor-pointer rounded-md font-medium text-primary-600 hover:text-primary-500 focus-within:outline-none">
                                    انتخاب فایل
                                </span>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-500">PNG, JPG, WEBP تا 2MB</p>
                        </div>
                         <!-- Preview -->
                        <div x-show="thumbnailUrl" class="relative w-full h-48" style="display: none;">
                             <img :src="thumbnailUrl" class="w-full h-full object-contain rounded-lg" />
                              <div class="absolute inset-0 bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center rounded-lg">
                                 <span class="text-white text-sm font-medium bg-black/50 px-3 py-1 rounded-full">تغییر تصویر</span>
                             </div>
                        </div>
                        <input id="thumbnail_url" name="thumbnail_url" type="file" class="sr-only" @change="previewThumbnail($event)">
                    </label>
                </div>
                 <button type="button" x-show="thumbnailUrl && thumbnailUrl.startsWith('http')" @click.prevent="deleteImage('thumbnail')" class="mt-2 text-xs text-red-500 hover:text-red-700 flex items-center gap-1" style="display: none;">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    حذف تصویر
                </button>
            </div>

        </div>

        <!-- Custom Fields Section -->
        <div class="mt-8 border-t border-gray-200 dark:border-gray-700 pt-6">
            <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4">پارامترهای سفارشی</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <?php if (empty($customFields)): ?>
                    <p class="text-gray-500 dark:text-gray-400 col-span-full text-sm">هیچ پارامتر سفارشی تعریف نشده است. <a href="<?php echo url('custom-fields/create'); ?>" class="text-primary-600 hover:underline">ایجاد پارامتر جدید</a></p>
                <?php else: ?>
                    <?php foreach ($customFields as $field): ?>
                        <label class="flex items-center p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-200 dark:border-gray-700 cursor-pointer hover:border-primary-500 transition-colors">
                            <input type="checkbox" name="custom_fields[]" value="<?php echo $field->id; ?>"
                                   class="h-4 w-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500 bg-white dark:bg-gray-800 dark:border-gray-600"
                                   <?php echo in_array($field->id, $attachedFieldIds) ? 'checked' : ''; ?>>
                            <span class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-200"><?php echo htmlspecialchars($field->label_fa); ?></span>
                        </label>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- SEO Tab Content -->
    <div x-show="activeTab === 'seo'" style="display: none;">
         <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="col-span-1">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="meta_title">عنوان متا (Title Tag)</label>
                <input class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2.5 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all shadow-sm"
                       type="text" name="meta_title" id="meta_title" value="<?php echo htmlspecialchars($category->meta_title ?? ''); ?>">
                <p class="text-xs text-gray-500 mt-1">پیشنهاد: کمتر از 60 کاراکتر</p>
            </div>
            <div class="col-span-1">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="meta_keywords">کلمات کلیدی متا</label>
                <div x-data="tagInput({
                    initialTags: <?php echo isset($category->meta_keywords) && $category->meta_keywords ? json_encode(explode(',', $category->meta_keywords)) : '[]'; ?>,
                    fieldName: 'meta_keywords[]',
                    noPrefix: true
                })" class="w-full">
                    <div class="relative rounded-xl border border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-2 py-1.5 flex flex-wrap gap-2 focus-within:ring-2 focus-within:ring-primary-500/20 focus-within:border-primary-500 transition-all shadow-sm min-h-[46px]" @click="$refs.input.focus()">
                        <!-- Chips -->
                        <template x-for="(tag, index) in items" :key="index">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-primary-50 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400">
                                <span x-text="tag"></span>
                                <button type="button" @click.stop="removeTag(index)" class="mr-1.5 text-primary-400 hover:text-primary-600 dark:text-primary-500 dark:hover:text-primary-300 focus:outline-none">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                                <input type="hidden" :name="fieldName" :value="tag">
                            </span>
                        </template>

                        <!-- Input -->
                        <input x-ref="input" type="text" x-model="inputValue" @keydown="handleKeydown" @paste="handlePaste($event)"
                               class="flex-1 min-w-[120px] bg-transparent border-none outline-none focus:ring-0 p-1 text-sm text-gray-900 dark:text-white placeholder-gray-400"
                               placeholder="تایپ کنید و Enter بزنید...">
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-1">برای افزودن Enter یا کاما بزنید.</p>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" for="meta_description">توضیحات متا (Meta Description)</label>
                <textarea class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2.5 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all shadow-sm"
                          name="meta_description" id="meta_description" rows="3"><?php echo htmlspecialchars($category->meta_description ?? ''); ?></textarea>
                <p class="text-xs text-gray-500 mt-1">پیشنهاد: بین 150 تا 160 کاراکتر</p>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../partials/tag_input_script.php'; ?>

<script>
    document.addEventListener('DOMContentLoaded', function () {
         // Initialize Custom Jalali Datepicker
        const publishedAtSelector = '#published_at';
        if (document.querySelector(publishedAtSelector)) {
            let initialValue = null;
            <?php if (!empty($category->published_at)): ?>
                // Convert PHP Gregorian timestamp (seconds) to JS Date object or milliseconds
                initialValue = <?php echo strtotime($category->published_at) * 1000; ?>;
            <?php endif; ?>

            new JalaliDatepicker(publishedAtSelector, {
                initialValue: initialValue
            });
        }

        // Helper for dark mode in TinyMCE
        const isDark = document.documentElement.classList.contains('dark');
        const skin = isDark ? 'oxide-dark' : 'oxide';
        const contentCss = isDark ? 'dark' : 'default';

        tinymce.init({
            selector: '.tinymce-editor',
            plugins: 'directionality link image lists table media code',
            toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | code ltr rtl',
            language: 'fa',
            height: 350,
            relative_urls: false,
            remove_script_host: false,
            skin: skin,
            content_css: contentCss,

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
                    // Update CSRF token if rotated
                     if (json.csrf_token) {
                        document.querySelector('meta[name="csrf-token"]').setAttribute('content', json.csrf_token);
                        const inputToken = document.querySelector('input[name="csrf_token"]');
                        if (inputToken) inputToken.value = json.csrf_token;
                    }
                    resolve(json.location);
                };
                xhr.onerror = () => reject('Image upload failed due to a network error.');

                const formData = new FormData();
                formData.append('file', blobInfo.blob(), blobInfo.filename());
                formData.append('context', 'categories');
                xhr.send(formData);
            })
        });
    });
</script>
