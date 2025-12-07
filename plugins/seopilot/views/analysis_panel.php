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
            <div class="relative w-full max-w-4xl transform overflow-hidden rounded-2xl bg-white text-right shadow-2xl transition-all ring-1 ring-gray-900/5 dark:bg-gray-800 dark:ring-white/10 sm:my-8"
                 @click.outside="closeModal()"
                 x-show="isOpen"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

                <!-- Header -->
                <div class="flex items-center justify-between border-b border-gray-200 px-4 py-4 sm:px-6 dark:border-gray-700">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-primary-600 text-white shadow-md shadow-primary-600/20">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-gray-900 sm:text-lg dark:text-white">آنالیز محتوا</h2>
                            <p class="text-xs text-gray-500 sm:text-sm dark:text-gray-400">تحلیل هوشمند محتوا</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <div class="flex flex-col items-end">
                            <span class="text-xs font-medium text-gray-500">امتیاز کلی</span>
                            <span class="text-xl font-black sm:text-2xl" :class="getScoreColor()">
                                <span x-text="score"></span>/100
                            </span>
                        </div>
                        <button @click="closeModal()" class="rounded-lg p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-500 dark:hover:bg-gray-700">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Content -->
                <div class="max-h-[70vh] overflow-y-auto p-4 sm:p-6 text-right" dir="rtl">

                    <!-- Top Section: Inputs -->
                    <div class="grid gap-4 md:grid-cols-2 mb-6">
                         <!-- Focus Keyword -->
                        <div class="col-span-2 md:col-span-1">
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">کلمه کلیدی کانونی</label>
                            <input type="text" x-model="meta.focus_keyword" @input.debounce.500ms="analyze()" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2.5 focus:ring-primary-500 focus:border-primary-500 shadow-sm" placeholder="مثلاً: خرید گوشی موبایل">
                        </div>
                        <!-- Meta Title (Input for Analysis Only) -->
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">عنوان سئو (جهت بررسی)</label>
                            <input type="text" x-model="meta.title" @input.debounce.500ms="analyze()" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2.5 focus:ring-primary-500 focus:border-primary-500 shadow-sm">
                        </div>
                        <!-- Meta Description (Input for Analysis Only) -->
                        <div class="col-span-2">
                             <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">توضیحات متا (جهت بررسی)</label>
                             <textarea x-model="meta.description" @input.debounce.500ms="analyze()" rows="2" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2.5 focus:ring-primary-500 focus:border-primary-500 shadow-sm"></textarea>
                        </div>
                    </div>

                    <!-- To-Do List (Prioritized) -->
                    <div class="mb-6 space-y-3" x-show="todoList && todoList.length > 0">
                        <h3 class="font-bold text-gray-900 dark:text-white">لیست مشکلات و پیشنهادات</h3>
                        <template x-for="(todo, index) in todoList" :key="index">
                            <div class="flex items-start gap-3 rounded-lg p-3 border"
                                 :class="todo.type === 'critical' ? 'bg-red-50 border-red-100 text-red-800 dark:bg-red-900/20 dark:border-red-800 dark:text-red-200' : 'bg-yellow-50 border-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:border-yellow-800 dark:text-yellow-200'">
                                <svg x-show="todo.type === 'critical'" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                <svg x-show="todo.type === 'warning'" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <span class="text-sm font-medium" x-text="todo.msg"></span>
                            </div>
                        </template>
                    </div>

                    <div x-show="todoList.length === 0 && score > 0" class="mb-6 rounded-lg bg-green-50 p-4 text-green-800 border border-green-100 dark:bg-green-900/20 dark:text-green-200 dark:border-green-800">
                        <div class="flex items-center gap-2">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <span class="font-bold">عالی! هیچ مشکل مهمی یافت نشد.</span>
                        </div>
                    </div>

                    <!-- Detailed Analysis Tabs/Sections -->
                    <div class="space-y-6" x-show="score > 0">

                        <!-- 1. Content Stats -->
                        <div class="border-t pt-4 border-gray-100 dark:border-gray-700">
                            <h4 class="mb-3 font-bold text-gray-800 dark:text-gray-200">آمار محتوا</h4>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div class="rounded bg-gray-50 p-3 dark:bg-gray-700/50">
                                    <span class="block text-gray-500 dark:text-gray-400">تعداد کلمات</span>
                                    <span class="font-bold text-gray-900 dark:text-white" x-text="analysis.content?.word_count"></span>
                                </div>
                                <div class="rounded bg-gray-50 p-3 dark:bg-gray-700/50">
                                    <span class="block text-gray-500 dark:text-gray-400">چگالی کلمه کلیدی</span>
                                    <span class="font-bold text-gray-900 dark:text-white" x-text="(analysis.content?.density || 0) + '%'"></span>
                                </div>
                                <div class="rounded bg-gray-50 p-3 dark:bg-gray-700/50">
                                    <span class="block text-gray-500 dark:text-gray-400">زمان مطالعه تقریبی</span>
                                    <span class="font-bold text-gray-900 dark:text-white" x-text="(analysis.readability?.reading_time_min || 0) + ' دقیقه'"></span>
                                </div>
                                <div class="rounded bg-gray-50 p-3 dark:bg-gray-700/50">
                                    <span class="block text-gray-500 dark:text-gray-400">تعداد هدینگ‌ها (H1)</span>
                                    <span class="font-bold text-gray-900 dark:text-white" x-text="analysis.structure?.h1_count"></span>
                                </div>
                            </div>
                        </div>

                        <!-- 2. Auto Alt Action -->
                        <div class="flex items-center justify-between rounded-lg bg-indigo-50 px-4 py-3 border border-indigo-100 dark:bg-indigo-900/20 dark:border-indigo-800" x-show="analysis.images && analysis.images.no_alt > 0">
                            <span class="text-sm font-medium text-indigo-900 dark:text-indigo-200">
                                <span class="font-bold" x-text="analysis.images.no_alt"></span> تصویر بدون Alt دارید.
                            </span>
                            <button @click="autoAlt()" class="rounded bg-indigo-600 px-3 py-1.5 text-xs text-white hover:bg-indigo-700 shadow-sm transition-colors">اصلاح خودکار</button>
                        </div>

                        <!-- 3. LSI Suggestions -->
                        <div class="border-t pt-4 border-gray-100 dark:border-gray-700" x-show="analysis.lsi && analysis.lsi.length > 0">
                            <h4 class="mb-3 font-bold text-gray-800 dark:text-gray-200">کلمات مرتبط پیشنهادی (LSI)</h4>
                            <div class="flex flex-wrap gap-2">
                                <template x-for="word in analysis.lsi" :key="word">
                                    <span class="rounded-full bg-gray-100 px-3 py-1 text-xs text-gray-700 dark:bg-gray-700 dark:text-gray-300" x-text="word"></span>
                                </template>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Footer -->
                <div class="flex flex-col items-center justify-between gap-4 border-t border-gray-200 bg-gray-50 px-4 py-4 sm:flex-row sm:px-6 dark:border-gray-700 dark:bg-gray-800/50">
                    <div class="text-xs text-gray-500">
                        آخرین آنالیز: <span x-text="lastAnalyzed || 'هرگز'"></span>
                    </div>
                    <div class="flex w-full justify-end gap-3 sm:w-auto">
                        <button @click="closeModal()" class="w-full rounded-lg border border-gray-300 bg-white px-6 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 sm:w-auto dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600">بستن</button>
                    </div>
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
        title: '',
        slug: '',

        // Meta Data (Inputs for Analysis)
        meta: {
            focus_keyword: '',
            title: '',
            description: ''
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
            this.title = detail.title;
            this.slug = detail.slug;

            this.loadInitialData();

            // Load Content from Editor (TinyMCE)
            let content = '';
            if (window.tinymce && tinymce.activeEditor) {
                content = tinymce.activeEditor.getContent();
            }

            // Initial Analyze
            this.analyze(content);
        },

        closeModal() {
            this.isOpen = false;
        },

        loadInitialData() {
            // 1. Title (for context)
            const titleInput = document.querySelector('input[name="title"]') || document.querySelector('input[name="name_fa"]');
            if (titleInput && !this.meta.title) this.meta.title = titleInput.value;

            // 2. Focus Keyword from Tags (if empty)
            if (!this.meta.focus_keyword) {
                 const chips = Array.from(document.querySelectorAll('.inline-flex.items-center span[x-text]'))
                                    .map(span => span.innerText)
                                    .filter(text => text);

                 if (chips.length > 0) {
                     this.meta.focus_keyword = chips[0];
                 }
            }

            // 3. Meta Description (Try to find existing input on page)
            const metaDescInput = document.querySelector('textarea[name="meta_description"]');
            if (metaDescInput && !this.meta.description) {
                this.meta.description = metaDescInput.value;
            }
             const metaTitleInput = document.querySelector('input[name="meta_title"]');
            if (metaTitleInput && !this.meta.title) {
                this.meta.title = metaTitleInput.value;
            }
        },

        getScoreColor() {
            if (this.score < 50) return 'text-red-500';
            if (this.score < 80) return 'text-yellow-500';
            return 'text-green-500';
        },

        async analyze(contentOverride = null) {
            let content = contentOverride;
            if (content === null && window.tinymce && tinymce.activeEditor) {
                content = tinymce.activeEditor.getContent();
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
                        title: this.title,
                        meta_title: this.meta.title,
                        meta_desc: this.meta.description,
                        slug: this.slug
                    })
                });

                const res = await response.json();
                if (res.success) {
                    this.updateCsrf(res.new_csrf_token);
                    this.analysis = res.data;
                    this.score = res.score;
                    this.todoList = res.todo_list;
                    this.lastAnalyzed = new Date().toLocaleTimeString('fa-IR');
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
                        title: this.title
                    })
                });

                const res = await response.json();
                if (res.success) {
                    this.updateCsrf(res.new_csrf_token);
                    if (res.count > 0) {
                        // Update Editor Content
                        tinymce.activeEditor.setContent(res.content);
                        alert(res.count + ' تصویر به صورت خودکار تگ Alt دریافت کردند.');
                        this.analyze();
                    } else {
                        alert('هیچ تصویری بدون Alt یافت نشد یا خطا رخ داد.');
                    }
                }
            } catch (e) {
                console.error('Auto Alt Failed', e);
            }
        }
    }));
});
</script>
