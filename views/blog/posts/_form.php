<?php
// Ensure $post is defined and is an object to prevent errors in Create/Edit modes
$post = $post ?? new stdClass();
if (is_array($post)) {
    $post = (object) $post;
}
?>
<div x-data="{
    activeTab: 'main',
    imageUrl: '<?php echo isset($post->image_url) ? asset($post->image_url) : ''; ?>',

    previewImage(event) {
        const reader = new FileReader();
        reader.onload = (e) => { this.imageUrl = e.target.result; };
        reader.readAsDataURL(event.target.files[0]);
    },

    deleteImage() {
        if (!this.imageUrl) return;

        if (this.imageUrl.startsWith('data:')) {
            // Just clear the local preview
            this.imageUrl = '';
            document.getElementById('image').value = '';
            return;
        }

        if (!confirm('آیا از حذف این تصویر مطمئن هستید؟')) return;

        let url = `<?php echo isset($post->id) ? url('blog/posts/delete-image/' . $post->id) : ''; ?>`;
        if (!url) return;

        fetch(url, {
            method: 'POST', // Using POST to be safe with Router configuration
            headers: {
                'X-CSRF-TOKEN': '<?php echo csrf_token(); ?>',
                'Content-Type': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                this.imageUrl = '';
                document.getElementById('image').value = '';
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
                تنظیمات سئو
            </a>
            <a href="#" @click.prevent="activeTab = 'faq'"
               :class="{ 'border-primary-500 text-primary-600 dark:text-primary-400': activeTab === 'faq', 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300': activeTab !== 'faq' }"
               class="whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm transition-colors flex items-center gap-2">
               <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                سوالات متداول
            </a>
        </nav>
    </div>

    <!-- Main Tab Content -->
    <div x-show="activeTab === 'main'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Title -->
            <div class="col-span-1">
                <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">عنوان پست <span class="text-red-500">*</span></label>
                <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($post->title ?? ''); ?>" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2.5 focus:ring-primary-500 focus:border-primary-500 shadow-sm" required>
            </div>

            <!-- Slug -->
            <div class="col-span-1">
                <label for="slug" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">اسلاگ (URL)</label>
                <input type="text" id="slug" name="slug" value="<?php echo htmlspecialchars($post->slug ?? ''); ?>" dir="ltr" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2.5 focus:ring-primary-500 focus:border-primary-500 shadow-sm">
                <p class="text-xs text-gray-500 mt-1">در صورت خالی بودن، از روی عنوان ساخته می‌شود.</p>
            </div>

            <!-- Category -->
            <div class="col-span-1">
                <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">دسته‌بندی</label>
                <div class="relative">
                    <select id="category_id" name="category_id" class="w-full appearance-none rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2.5 pr-10 focus:ring-primary-500 focus:border-primary-500 shadow-sm">
                        <option value="">انتخاب کنید...</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo $category['id']; ?>" <?php echo (isset($post->category_id) && $category['id'] == $post->category_id) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($category['name_fa']); ?>
                            </option>
                        <?php endforeach; ?>
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

            <!-- Tags Input -->
            <div class="col-span-1">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">تگ‌ها</label>
                <div x-data='tagInput({
                    initialTags: <?php echo isset($post_tags) ? json_encode($post_tags, JSON_HEX_APOS) : "[]"; ?>,
                    fieldName: "tags[]",
                    fetchUrl: "<?php echo url('api/tags/search'); ?>"
                })' class="w-full relative">
                    <div class="relative rounded-xl border border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-2 py-1.5 flex flex-wrap gap-2 focus-within:ring-2 focus-within:ring-primary-500/20 focus-within:border-primary-500 transition-all shadow-sm min-h-[46px]" @click="$refs.input.focus()">
                        <!-- Chips -->
                        <template x-for="(tag, index) in items" :key="index">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-indigo-50 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400">
                                <span x-text="typeof tag === 'object' ? tag.name : tag"></span>
                                <button type="button" @click.stop="removeTag(index)" class="mr-1.5 text-indigo-400 hover:text-indigo-600 dark:text-indigo-500 dark:hover:text-indigo-300 focus:outline-none">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                                <input type="hidden" :name="fieldName" :value="getItemValue(tag)">
                            </span>
                        </template>

                        <!-- Input -->
                        <input x-ref="input" type="text" x-model="inputValue" @keydown="handleKeydown" @paste="handlePaste($event)" @input.stop
                               class="flex-1 min-w-[120px] bg-transparent border-none outline-none focus:ring-0 p-1 text-sm text-gray-900 dark:text-white placeholder-gray-400"
                               placeholder="تگ را وارد کنید...">
                    </div>

                    <!-- Suggestions Dropdown -->
                    <div x-show="suggestions.length > 0" @click.away="suggestions = []" class="absolute z-10 w-full mt-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg max-h-60 overflow-auto">
                        <template x-for="suggestion in suggestions" :key="suggestion.id">
                            <div @click="addTag(suggestion)" class="px-4 py-2 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer text-sm text-gray-700 dark:text-gray-200 transition-colors">
                                <span x-text="suggestion.name"></span>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Status -->
            <div class="col-span-1">
                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">وضعیت انتشار</label>
                <div class="relative">
                    <select id="status" name="status" class="w-full appearance-none rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2.5 pr-10 focus:ring-primary-500 focus:border-primary-500 shadow-sm">
                        <option value="draft" <?php echo (isset($post->status) && $post->status == 'draft') ? 'selected' : ''; ?>>پیش‌نویس</option>
                        <option value="published" <?php echo (isset($post->status) && $post->status == 'published') ? 'selected' : ''; ?>>منتشر شده</option>
                        <option value="archived" <?php echo (isset($post->status) && $post->status == 'archived') ? 'selected' : ''; ?>>آرشیو شده</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center px-3 text-gray-500">
                        <?php partial('icon', ['name' => 'chevron-down', 'class' => 'w-4 h-4']); ?>
                    </div>
                </div>
            </div>

            <!-- Image Upload (Redesigned) -->
            <div class="col-span-1 md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">تصویر شاخص</label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border border-gray-300 dark:border-gray-600 border-dashed rounded-xl bg-gray-50 dark:bg-gray-800/50 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors cursor-pointer group relative">
                     <label for="image" class="w-full h-full flex flex-col items-center justify-center cursor-pointer">
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
                        <div x-show="imageUrl" class="relative w-full h-64" style="display: none;">
                             <img :src="imageUrl" class="w-full h-full object-contain rounded-lg" />
                             <div class="absolute inset-0 bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center rounded-lg">
                                 <span class="text-white text-sm font-medium bg-black/50 px-3 py-1 rounded-full">تغییر تصویر</span>
                             </div>
                        </div>

                        <input id="image" name="image" type="file" class="sr-only" @change="previewImage($event)">
                    </label>
                </div>
                 <button type="button" x-show="imageUrl" @click.prevent="deleteImage()" class="mt-2 text-xs text-red-500 hover:text-red-700 flex items-center gap-1" style="display: none;">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    حذف تصویر
                </button>
            </div>

        </div>

        <!-- Summary -->
        <div class="mb-6">
            <label for="excerpt" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">خلاصه متن</label>
            <div class="rounded-xl overflow-hidden border border border-gray-300 dark:border-gray-600">
                <textarea id="excerpt" name="excerpt" rows="3" class="tinymce-editor"><?= htmlspecialchars($post->excerpt ?? '') ?></textarea>
            </div>
        </div>

        <!-- Content -->
        <div class="mb-6">
            <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">محتوای کامل</label>
            <div class="rounded-xl overflow-hidden border border border-gray-300 dark:border-gray-600">
                <textarea id="content" name="content" class="tinymce-editor"><?= htmlspecialchars($post->content ?? '') ?></textarea>
            </div>
        </div>
    </div>

    <!-- FAQ Tab Content -->
    <div x-show="activeTab === 'faq'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
        <div class="space-y-4" x-data="{
            faqs: <?php
                echo isset($post_faq_objects) ? json_encode($post_faq_objects) : '[]';
            ?>,
            addFaq() {
                this.faqs.push({ id: null, question: '', answer: '' });
            },
            removeFaq(index) {
                this.faqs.splice(index, 1);
            }
        }">
            <template x-for="(faq, index) in faqs" :key="index">
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4 border border-gray-200 dark:border-gray-600 relative group">
                    <button type="button" @click="removeFaq(index)" class="absolute top-2 left-2 text-red-400 hover:text-red-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>

                    <div class="grid grid-cols-1 gap-4 pr-8">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">سوال</label>
                            <input type="text" x-model="faq.question" :name="`post_faqs[${index}][question]`" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm px-3 py-2" placeholder="سوال را وارد کنید..." required>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">پاسخ</label>
                            <textarea x-model="faq.answer" :name="`post_faqs[${index}][answer]`" rows="2" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm px-3 py-2" placeholder="پاسخ را وارد کنید..." required></textarea>
                        </div>
                        <input type="hidden" :name="`post_faqs[${index}][id]`" x-model="faq.id">
                    </div>
                </div>
            </template>

            <button type="button" @click="addFaq()" class="w-full py-3 border-2 border-dashed border border-gray-300 dark:border-gray-600 rounded-xl text-gray-500 dark:text-gray-400 hover:border-primary-500 hover:text-primary-500 transition-colors flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                افزودن سوال جدید
            </button>
        </div>
    </div>

    <!-- SEO Tab Content -->
    <div x-show="activeTab === 'seo'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
             <div class="col-span-1">
                <label for="meta_title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">عنوان متا (Meta Title)</label>
                <input type="text" id="meta_title" name="meta_title" value="<?php echo htmlspecialchars($post->meta_title ?? ''); ?>" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2.5 focus:ring-primary-500 focus:border-primary-500 shadow-sm">
                <p class="text-xs text-gray-500 mt-1">عنوان نمایش داده شده در نتایج جستجو (معمولا کمتر از 60 کاراکتر)</p>
            </div>

            <div class="col-span-1">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">کلمات کلیدی متا</label>
                <div x-data='tagInput({
                    initialTags: <?php
                    $tags = isset($post->meta_keywords) && $post->meta_keywords
                        ? json_decode($post->meta_keywords, true)
                        : [];
                    echo json_encode(array_map("trim", is_array($tags) ? $tags : []), JSON_UNESCAPED_UNICODE);
                    ?>,
                    fieldName: "meta_keywords[]",
                    noPrefix: true
                })' class="w-full">
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
                        <input x-ref="input" type="text" x-model="inputValue" @keydown="handleKeydown" @paste="handlePaste($event)" @input.stop
                               class="flex-1 min-w-[120px] bg-transparent border-none outline-none focus:ring-0 p-1 text-sm text-gray-900 dark:text-white placeholder-gray-400"
                               placeholder="تایپ کنید و Enter بزنید...">
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-1">برای افزودن Enter یا کاما بزنید.</p>
            </div>

             <div class="col-span-1 md:col-span-2">
                <label for="meta_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">توضیحات متا (Meta Description)</label>
                <textarea id="meta_description" name="meta_description" rows="3" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2.5 focus:ring-primary-500 focus:border-primary-500 shadow-sm"><?php echo htmlspecialchars($post->meta_description ?? ''); ?></textarea>
                <p class="text-xs text-gray-500 mt-1">توضیحات نمایش داده شده در نتایج جستجو (معمولا بین 150 تا 160 کاراکتر)</p>
            </div>
        </div>
    </div>
</div>

<?php require_once PROJECT_ROOT . '/views/partials/tag_input_script.php'; ?>

<script>
    document.addEventListener('DOMContentLoaded', function () {
         // Initialize Custom Jalali Datepicker
        const publishedAtSelector = '#published_at';
        if (document.querySelector(publishedAtSelector)) {
            let initialValue = null;
            <?php if (!empty($post->published_at)): ?>
                // Convert PHP Gregorian timestamp (seconds) to JS Date object or milliseconds
                initialValue = <?php echo strtotime($post->published_at) * 1000; ?>;
            <?php else: ?>
                // Default to current time if creating a new post
                initialValue = Date.now();
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
            content_css: [
                isDark ? 'dark' : 'default',
                '/css/tiny-custom.css'
            ],
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
