<div x-data="seoPilotPanel"
     @open-seopilot.window="openModal($event.detail)"
     class="relative z-50"
     x-show="isOpen"
     style="display: none;"
     x-cloak>

    <!-- Modal Backdrop -->
    <div class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm transition-opacity"
         x-show="isOpen"
         x-transition.opacity></div>

    <!-- Modal Container -->
    <div class="fixed inset-0 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative w-full max-w-5xl transform overflow-hidden rounded-2xl bg-gray-50 text-right shadow-2xl transition-all ring-1 ring-gray-900/5 dark:bg-gray-900 dark:ring-white/10 sm:my-8"
                 @click.outside="closeModal()"
                 x-show="isOpen"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

                <!-- Header -->
                <div class="flex items-center justify-between border-b border-gray-200 bg-white px-4 py-4 sm:px-6 dark:border-gray-800 dark:bg-gray-800">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-primary-600 text-white shadow-md shadow-primary-600/20">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-gray-900 sm:text-lg dark:text-white">آنالیز محتوا</h2>
                            <p class="text-xs text-gray-500 sm:text-sm dark:text-gray-400">تحلیل هوشمند و استاندارد محتوای فارسی</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <div class="flex flex-col items-end">
                            <span class="text-xs font-medium text-gray-500">امتیاز سئو</span>
                            <div class="flex items-center gap-1">
                                <span class="text-xl font-black sm:text-3xl" :class="getScoreColor()">
                                    <span x-text="score"></span>
                                </span>
                                <span class="text-sm text-gray-400">/100</span>
                            </div>
                        </div>
                        <button @click="closeModal()" class="rounded-lg p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-500 dark:hover:bg-gray-700">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="max-h-[75vh] overflow-y-auto p-4 sm:p-6 text-right" dir="rtl">

                    <!-- Keyword Context (Editable) -->
                    <div class="mb-6 flex flex-col md:flex-row items-center justify-between rounded-xl bg-white p-4 shadow-sm border border-gray-100 dark:bg-gray-800 dark:border-gray-700 gap-4">
                        <div class="flex w-full md:w-auto items-center gap-2 flex-grow">
                            <label class="whitespace-nowrap text-sm font-medium text-gray-700 dark:text-gray-300">کلمه کلیدی کانونی:</label>
                            <input type="text" x-model="meta.focus_keyword" @input.debounce.800ms="analyze()"
                                   class="w-full md:min-w-[250px] rounded-lg border border-gray-300 px-3 py-1.5 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary-500"
                                   placeholder="کلمه کلیدی را وارد کنید...">
                        </div>
                        <div class="text-xs text-gray-400 whitespace-nowrap">
                            آخرین به‌روزرسانی: <span x-text="lastAnalyzed || '---'" class="font-mono"></span>
                        </div>
                    </div>

                    <!-- Metrics Grid -->
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 mb-8">

                        <!-- 1. Content Density -->
                        <div class="relative overflow-hidden rounded-xl bg-white p-5 shadow-sm border border-gray-100 dark:bg-gray-800 dark:border-gray-700">
                            <div class="mb-2 flex items-center justify-between">
                                <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300">چگالی کلمه کلیدی</h4>
                                <div class="h-2 w-2 rounded-full" :class="getStatusColor(analysis.content?.density, 0.5, 2.5)"></div>
                            </div>
                            <div class="flex items-baseline gap-1">
                                <span class="text-2xl font-black text-gray-900 dark:text-white" x-text="(analysis.content?.density || 0)"></span>
                                <span class="text-sm font-bold text-gray-500">%</span>
                            </div>
                            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                محدوده مجاز: <span class="font-medium text-gray-700 dark:text-gray-300">0.5% تا 2.5%</span>
                            </p>
                        </div>

                        <!-- 2. Word Count -->
                        <div class="relative overflow-hidden rounded-xl bg-white p-5 shadow-sm border border-gray-100 dark:bg-gray-800 dark:border-gray-700">
                            <div class="mb-2 flex items-center justify-between">
                                <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300">طول محتوا</h4>
                                <div class="h-2 w-2 rounded-full" :class="analysis.content?.word_count >= 800 ? 'bg-green-500' : (analysis.content?.word_count >= 300 ? 'bg-yellow-500' : 'bg-red-500')"></div>
                            </div>
                            <div class="flex items-baseline gap-1">
                                <span class="text-2xl font-black text-gray-900 dark:text-white" x-text="analysis.content?.word_count || 0"></span>
                                <span class="text-sm font-bold text-gray-500">کلمه</span>
                            </div>
                            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                حداقل پیشنهاد: <span class="font-medium text-gray-700 dark:text-gray-300">300 کلمه</span>
                            </p>
                        </div>

                        <!-- 3. Readability -->
                        <div class="relative overflow-hidden rounded-xl bg-white p-5 shadow-sm border border-gray-100 dark:bg-gray-800 dark:border-gray-700">
                            <div class="mb-2 flex items-center justify-between">
                                <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300">خوانایی</h4>
                                <div class="h-2 w-2 rounded-full" :class="!analysis.readability?.long_sentences_count && !analysis.readability?.half_space_issues ? 'bg-green-500' : 'bg-yellow-500'"></div>
                            </div>
                            <div class="flex items-baseline gap-1">
                                <span class="text-lg font-bold text-gray-900 dark:text-white" x-text="analysis.readability?.reading_time_min || 0"></span>
                                <span class="text-xs text-gray-500">دقیقه زمان مطالعه</span>
                            </div>
                            <div class="mt-2 flex flex-col gap-1">
                                 <p class="text-xs text-gray-500 dark:text-gray-400">
                                    جملات طولانی: <span class="font-medium" :class="analysis.readability?.long_sentences_count > 0 ? 'text-red-500' : 'text-green-500'" x-text="analysis.readability?.long_sentences_count || 0"></span>
                                 </p>
                                 <button x-show="analysis.readability?.half_space_issues" @click="fixText()" class="mt-1 w-full rounded bg-indigo-50 py-1 text-xs font-bold text-indigo-700 hover:bg-indigo-100">
                                    اصلاح خودکار نگارش
                                </button>
                            </div>
                        </div>

                        <!-- 4. Headings -->
                        <div class="relative overflow-hidden rounded-xl bg-white p-5 shadow-sm border border-gray-100 dark:bg-gray-800 dark:border-gray-700">
                            <div class="mb-2 flex items-center justify-between">
                                <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300">ساختار هدینگ</h4>
                                <div class="h-2 w-2 rounded-full" :class="analysis.structure?.h1_count === 1 ? 'bg-green-500' : 'bg-red-500'"></div>
                            </div>
                            <div class="text-sm text-gray-600 dark:text-gray-300">
                                <div class="flex justify-between">
                                    <span>تعداد H1:</span>
                                    <span class="font-bold" :class="analysis.structure?.h1_count === 1 ? 'text-green-600' : 'text-red-500'" x-text="analysis.structure?.h1_count"></span>
                                </div>
                                <div class="flex justify-between mt-1">
                                    <span>ترتیب:</span>
                                    <span class="font-bold" :class="analysis.structure?.hierarchy_ok ? 'text-green-600' : 'text-yellow-500'" x-text="analysis.structure?.hierarchy_ok ? 'صحیح' : 'نادرست'"></span>
                                </div>
                            </div>
                        </div>

                         <!-- 5. Links -->
                         <div class="relative overflow-hidden rounded-xl bg-white p-5 shadow-sm border border-gray-100 dark:bg-gray-800 dark:border-gray-700">
                            <div class="mb-2 flex items-center justify-between">
                                <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300">لینک‌سازی</h4>
                                <div class="h-2 w-2 rounded-full" :class="analysis.links?.internal >= 2 ? 'bg-green-500' : 'bg-yellow-500'"></div>
                            </div>
                            <div class="text-sm text-gray-600 dark:text-gray-300">
                                <div class="flex justify-between">
                                    <span>داخلی:</span>
                                    <span class="font-bold text-gray-900 dark:text-white" x-text="analysis.links?.internal || 0"></span>
                                </div>
                                <div class="flex justify-between mt-1">
                                    <span>نامعتبر (External):</span>
                                    <span class="font-bold" :class="analysis.links?.unsafe_external_count > 0 ? 'text-red-500' : 'text-green-600'" x-text="analysis.links?.unsafe_external_count || 0"></span>
                                </div>
                            </div>
                        </div>

                         <!-- 6. Images -->
                         <div class="relative overflow-hidden rounded-xl bg-white p-5 shadow-sm border border-gray-100 dark:bg-gray-800 dark:border-gray-700">
                            <div class="mb-2 flex items-center justify-between">
                                <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300">تصاویر و Alt</h4>
                                <div class="h-2 w-2 rounded-full" :class="analysis.images?.no_alt === 0 ? 'bg-green-500' : 'bg-red-500'"></div>
                            </div>
                            <div class="flex flex-col gap-2">
                                <div class="flex justify-between text-sm text-gray-600 dark:text-gray-300">
                                    <span>بدون Alt:</span>
                                    <span class="font-bold" :class="analysis.images?.no_alt > 0 ? 'text-red-500' : 'text-green-600'" x-text="analysis.images?.no_alt || 0"></span>
                                </div>
                                <button x-show="analysis.images?.no_alt > 0" @click="autoAlt()" class="w-full rounded bg-primary-50 py-1 text-xs font-bold text-primary-700 hover:bg-primary-100">
                                    اصلاح خودکار
                                </button>
                            </div>
                        </div>

                    </div>

                    <!-- LSI Keywords -->
                    <div class="rounded-xl bg-white p-6 shadow-sm border border-gray-100 dark:bg-gray-800 dark:border-gray-700" x-show="analysis.lsi && analysis.lsi.length > 0">
                         <h4 class="mb-4 text-sm font-bold text-gray-700 dark:text-gray-300">کلمات کلیدی پیشنهادی (LSI)</h4>
                         <div class="flex flex-wrap gap-2">
                             <template x-for="word in analysis.lsi" :key="word">
                                 <span class="cursor-default rounded-lg bg-gray-100 px-3 py-1.5 text-sm text-gray-700 transition-colors hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300" x-text="word"></span>
                             </template>
                         </div>
                    </div>

                    <!-- Issues List -->
                     <div class="mt-8">
                         <h3 class="mb-4 font-bold text-gray-900 dark:text-white">اقدامات پیشنهادی</h3>
                         <div class="space-y-3">
                             <template x-for="(todo, index) in todoList" :key="index">
                                <div class="flex items-start gap-3 rounded-xl p-4 border transition-all hover:shadow-md"
                                     :class="todo.type === 'critical' ? 'bg-red-50 border-red-100 text-red-900 dark:bg-red-900/10 dark:border-red-900/30 dark:text-red-200' : 'bg-amber-50 border-amber-100 text-amber-900 dark:bg-amber-900/10 dark:border-amber-900/30 dark:text-amber-200'">
                                    <div class="mt-0.5 shrink-0">
                                        <svg x-show="todo.type === 'critical'" class="h-5 w-5 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        <svg x-show="todo.type === 'warning'" class="h-5 w-5 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    </div>
                                    <span class="text-sm font-medium leading-6" x-text="todo.msg"></span>
                                </div>
                             </template>
                             <div x-show="todoList.length === 0 && score > 0" class="flex items-center gap-3 rounded-xl bg-green-50 p-4 border border-green-100 text-green-900 dark:bg-green-900/10 dark:border-green-900/30 dark:text-green-200">
                                <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <span class="font-bold">تبریک! محتوای شما کاملاً استاندارد است.</span>
                             </div>
                         </div>
                     </div>

                </div>

                <!-- Footer -->
                <div class="flex items-center justify-end border-t border-gray-200 bg-gray-50 px-4 py-4 sm:px-6 dark:border-gray-800 dark:bg-gray-800">
                    <button @click="closeModal()" class="rounded-lg border border-gray-300 bg-white px-6 py-2.5 text-sm font-bold text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600">بستن پنجره</button>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('seoPilotPanel', () => ({
        isOpen: false,
        score: 0,
        lastAnalyzed: null,
        todoList: [],

        // Entity Context
        entityType: null,
        entityId: null,

        // Data derived from Parent Form (Read-Only/Context)
        meta: {
            focus_keyword: '',
            title: '',
            description: '',
            slug: ''
        },

        // Analysis Result
        analysis: {},
        
        // Helper to update CSRF token
        updateCsrf(token) {
            if (token) {
                const meta = document.querySelector('meta[name="csrf-token"]');
                if (meta) {
                    meta.setAttribute('content', token);
                }
            }
        },

        openModal(detail) {
            this.isOpen = true;
            this.entityType = detail.type;
            this.entityId = detail.id;

            // Populate Context from Detail or Parent Form
            this.loadContext(detail);

            // Load Content from Editor (TinyMCE)
            let content = '';
            if (window.tinymce && tinymce.activeEditor) {
                content = tinymce.activeEditor.getContent();
            }

            // Trigger Analysis
            this.analyze(content);
        },

        closeModal() {
            this.isOpen = false;
        },

        // Scrape data from the parent form to provide context for analysis
        loadContext(detail) {
            // 1. Slug
            this.meta.slug = detail.slug || '';
            const slugInput = document.querySelector('input[name="slug"]');
            if (slugInput && !this.meta.slug) this.meta.slug = slugInput.value;

            // 2. Title (Support name_fa, name, title)
            this.meta.title = detail.title || '';
            const titleInput = document.querySelector('input[name="title"]') || document.querySelector('input[name="name_fa"]') || document.querySelector('input[name="name"]');
            if (titleInput && !this.meta.title) this.meta.title = titleInput.value;

            // 3. Focus Keyword (Detection Priority: meta_keywords -> seopilot_focus -> tags)
            this.meta.focus_keyword = ''; // Reset

            // Priority 1: Meta Keywords (input or textarea)
            const metaKeywordsInput = document.querySelector('input[name="meta_keywords"]') || document.querySelector('textarea[name="meta_keywords"]');
            if (metaKeywordsInput && metaKeywordsInput.value) {
                // Split by comma and take the first one
                this.meta.focus_keyword = metaKeywordsInput.value.split(',')[0].trim();
            }

            // Priority 2: Custom Field
            if (!this.meta.focus_keyword) {
                const focusInput = document.querySelector('input[name="seopilot_focus_keyword"]') || document.querySelector('input[name="focus_keyword"]');
                if (focusInput && focusInput.value) {
                    this.meta.focus_keyword = focusInput.value.trim();
                }
            }

            // Priority 3: Tags
            if (!this.meta.focus_keyword) {
                const tagsInput = document.querySelector('input[name="tags"]');
                if (tagsInput && tagsInput.value) {
                    this.meta.focus_keyword = tagsInput.value.split(',')[0].trim();
                }
            }

            // 4. Meta Description
            const metaDescInput = document.querySelector('textarea[name="meta_description"]') || document.querySelector('textarea[name="description"]');
            if (metaDescInput) {
                this.meta.description = metaDescInput.value;
            }
        },

        getScoreColor() {
            if (this.score < 50) return 'text-red-500';
            if (this.score < 80) return 'text-yellow-500';
            return 'text-green-500';
        },

        getStatusColor(value, min, max) {
            if (value === undefined || value === null) return 'bg-gray-300';
            if (value >= min && value <= max) return 'bg-green-500';
            if (value > 0) return 'bg-yellow-500'; // Close but not perfect
            return 'bg-red-500';
        },

        async analyze(contentOverride = null) {
            let content = contentOverride;
            if (content === null && window.tinymce && tinymce.activeEditor) {
                content = tinymce.activeEditor.getContent();
            }
            // Fallback for non-TinyMCE pages (e.g., categories with standard textarea)
            if (!content) {
                const descArea = document.querySelector('textarea[name="description"]') || document.querySelector('textarea[name="content"]');
                if (descArea) content = descArea.value;
            }

            try {
                const response = await fetch('/admin/seopilot/analyze', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        content: content,
                        keyword: this.meta.focus_keyword,
                        title: this.meta.title,
                        meta_title: this.meta.title,
                        meta_desc: this.meta.description,
                        slug: this.meta.slug
                    })
                });

                const res = await response.json();
                if (res.success) {
                    this.updateCsrf(res.new_csrf_token);
                    this.analysis = res.data;
                    this.score = res.score;
                    this.todoList = res.todo_list;

                    // Format Time: H:i:s
                    const now = new Date();
                    this.lastAnalyzed = now.getHours().toString().padStart(2, '0') + ':' +
                                       now.getMinutes().toString().padStart(2, '0') + ':' +
                                       now.getSeconds().toString().padStart(2, '0');
                } else {
                    if (res.error) {
                        alert('خطای آنالیز: ' + res.error);
                    }
                }
            } catch (e) {
                console.error('SeoPilot Analysis Failed', e);
            }
        },

        async autoAlt() {
            let content = '';
            if (window.tinymce && tinymce.activeEditor) {
                content = tinymce.activeEditor.getContent();
            }

            try {
                const response = await fetch('/admin/seopilot/auto-alt', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        content: content,
                        title: this.meta.title
                    })
                });

                const res = await response.json();
                if (res.success) {
                    this.updateCsrf(res.new_csrf_token);
                    if (res.count > 0) {
                        if (window.tinymce && tinymce.activeEditor) {
                            tinymce.activeEditor.setContent(res.content);
                        }
                        this.analyze(res.content);
                    } else {
                        alert('هیچ تصویری بدون Alt یافت نشد.');
                    }
                }
            } catch (e) {
                console.error('Auto Alt Failed', e);
            }
        },

        async fixText() {
            let content = '';
            if (window.tinymce && tinymce.activeEditor) {
                content = tinymce.activeEditor.getContent();
            }
            if (!content) {
                const descArea = document.querySelector('textarea[name="description"]') || document.querySelector('textarea[name="content"]');
                if (descArea) content = descArea.value;
            }

            try {
                const response = await fetch('/admin/seopilot/fix-text', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        content: content
                    })
                });

                const res = await response.json();
                if (res.success) {
                    this.updateCsrf(res.new_csrf_token);
                    if (window.tinymce && tinymce.activeEditor) {
                        tinymce.activeEditor.setContent(res.content);
                    } else {
                        const descArea = document.querySelector('textarea[name="description"]') || document.querySelector('textarea[name="content"]');
                        if (descArea) descArea.value = res.content;
                    }
                    // Re-run analysis
                    this.analyze(res.content);
                }
            } catch (e) {
                console.error('Text Fix Failed', e);
            }
        }
    }));
});
</script>
