<input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">

<div x-data="{
    activeTab: 'main',
    imageUrl: '<?php echo isset($category->image_url) ? url($category->image_url) : ''; ?>',
    thumbnailUrl: '<?php echo isset($category->thumbnail_url) ? url($category->thumbnail_url) : ''; ?>',

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
                    document.getElementById('image_url').value = ''; // Clear file input
                } else {
                    this.thumbnailUrl = '';
                    document.getElementById('thumbnail_url').value = ''; // Clear file input
                }
                // You might want to show a success message here
            } else {
                alert(data.message || 'خطا در حذف تصویر');
            }
        })
        .catch(err => console.error('Error:', err));
    }
}">
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

            <!-- Image Upload -->
            <div class="sm:col-span-1">
                <label class="text-gray-700">تصویر اصلی</label>
                <div class="mt-2 flex items-center justify-center w-full">
                    <label for="image_url" class="flex flex-col items-center justify-center w-full h-48 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                        <template x-if="!imageUrl">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-8 h-8 mb-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/></svg>
                                <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">برای آپلود کلیک کنید</span></p>
                            </div>
                        </template>
                        <template x-if="imageUrl">
                            <img :src="imageUrl" class="object-cover h-full w-full rounded-lg" />
                        </template>
                        <input id="image_url" name="image_url" type="file" class="hidden" @change="previewImage($event)">
                    </label>
                </div>
                 <template x-if="imageUrl && imageUrl.startsWith('http')">
                    <button type="button" @click.prevent="deleteImage('image')" class="mt-2 text-sm text-red-600 hover:text-red-800">حذف تصویر</button>
                </template>
            </div>

            <!-- Thumbnail Upload -->
            <div class="sm:col-span-1">
                <label class="text-gray-700">تصویر کوچک (Thumbnail)</label>
                 <div class="mt-2 flex items-center justify-center w-full">
                    <label for="thumbnail_url" class="flex flex-col items-center justify-center w-full h-48 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                        <template x-if="!thumbnailUrl">
                             <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-8 h-8 mb-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/></svg>
                                <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">برای آپلود کلیک کنید</span></p>
                            </div>
                        </template>
                         <template x-if="thumbnailUrl">
                            <img :src="thumbnailUrl" class="object-cover h-full w-full rounded-lg" />
                        </template>
                        <input id="thumbnail_url" name="thumbnail_url" type="file" class="hidden" @change="previewThumbnail($event)">
                    </label>
                </div>
                 <template x-if="thumbnailUrl && thumbnailUrl.startsWith('http')">
                    <button type="button" @click.prevent="deleteImage('thumbnail')" class="mt-2 text-sm text-red-600 hover:text-red-800">حذف تصویر</button>
                </template>
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
            api_key: 'YOUR_API_KEY', // Replace with your actual TinyMCE API key
            plugins: 'directionality link image lists table media code',
            toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | code ltr rtl',
            language: 'fa',
            height: 300,
            relative_urls: false,
            remove_script_host: false,

            // Image Upload Configuration
            image_title: true,
            automatic_uploads: true,
            file_picker_types: 'image',
            images_upload_handler: (blobInfo, progress) => new Promise((resolve, reject) => {
                const xhr = new XMLHttpRequest();
                xhr.withCredentials = false;
                xhr.open('POST', '<?php echo url('api/upload-image'); ?>');

                // Set the CSRF token header
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);

                xhr.upload.onprogress = (e) => {
                    progress(e.loaded / e.total * 100);
                };

                xhr.onload = () => {
                    if (xhr.status < 200 || xhr.status >= 300) {
                        try {
                            const json = JSON.parse(xhr.responseText);
                            return reject(json.error || 'HTTP Error: ' + xhr.status);
                        } catch (e) {
                            return reject('HTTP Error: ' + xhr.status);
                        }
                    }

                    const json = JSON.parse(xhr.responseText);

                    if (!json || typeof json.location != 'string') {
                        return reject('Invalid JSON: ' + xhr.responseText);
                    }

                    resolve(json.location);
                };

                xhr.onerror = () => {
                    reject('Image upload failed due to a network error.');
                };

                const formData = new FormData();
                formData.append('file', blobInfo.blob(), blobInfo.filename());

                xhr.send(formData);
            }),

            // Simple file picker for local images
            file_picker_callback: function (cb, value, meta) {
                var input = document.createElement('input');
                input.setAttribute('type', 'file');
                input.setAttribute('accept', 'image/*');

                input.onchange = function () {
                    var file = this.files[0];
                    var reader = new FileReader();

                    reader.onload = function () {
                        var id = 'blobid' + (new Date()).getTime();
                        var blobCache = tinymce.activeEditor.editorUpload.blobCache;
                        var base64 = reader.result.split(',')[1];
                        var blobInfo = blobCache.create(id, file, base64);
                        blobCache.add(blobInfo);

                        cb(blobInfo.blobUri(), { title: file.name });
                    };
                    reader.readAsDataURL(file);
                };

                input.click();
            }
        });
    });
</script>
