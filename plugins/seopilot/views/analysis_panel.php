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
                <div class="max-h-[70vh] overflow-y-auto p-4 sm:p-6">

                    <!-- Focus Keyword Input for Analysis -->
                    <div class="mb-6">
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">کلمه کلیدی کانونی</label>
                        <div class="relative w-full">
                            <input type="text" x-model="meta.focus_keyword" @input.debounce.500ms="analyze()" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2.5 focus:ring-primary-500 focus:border-primary-500 shadow-sm" placeholder="مثلاً: خرید گوشی موبایل">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <span class="text-xs text-gray-400" x-text="meta.focus_keyword.length > 0 ? 'ذخیره شد' : ''"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Analysis Results -->
                    <div class="space-y-4">
                        <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800">
                             <!-- Action: Auto Alt -->
                             <div class="flex items-center justify-between border-b border-gray-100 p-4 dark:border-gray-700" x-show="analysis.structure && analysis.structure.images_no_alt > 0">
                                <span class="text-sm font-medium text-gray-900 dark:text-white">
                                    <span class="text-red-500 font-bold" x-text="analysis.structure ? analysis.structure.images_no_alt : 0"></span> تصویر بدون Alt یافت شد!
                                </span>
                                <button @click="autoAlt()" class="rounded bg-primary-600 px-3 py-1.5 text-xs text-white hover:bg-primary-700">اصلاح خودکار (Auto Alt)</button>
                             </div>

                            <!-- Items -->
                            <template x-for="(item, index) in analysisItems" :key="index">
                                <div class="flex items-start gap-3 border-b border-gray-100 p-4 last:border-0 dark:border-gray-700">
                                    <div class="mt-0.5 flex-shrink-0">
                                        <div class="flex h-5 w-5 items-center justify-center rounded-full"
                                             :class="item.passed ? 'bg-green-100 text-green-600 dark:bg-green-900/30' : 'bg-red-100 text-red-600 dark:bg-red-900/30'">
                                            <svg x-show="item.passed" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                            <svg x-show="!item.passed" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900 dark:text-white" x-text="item.label"></h4>
                                        <p class="text-xs text-gray-500 dark:text-gray-400" x-text="item.desc"></p>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="flex flex-col items-center justify-between gap-4 border-t border-gray-200 bg-gray-50 px-4 py-4 sm:flex-row sm:px-6 dark:border-gray-700 dark:bg-gray-800/50">
                    <div class="text-xs text-gray-500">
                        آخرین آنالیز: <span x-text="lastAnalyzed || 'هرگز'"></span>
                    </div>
                    <div class="flex w-full gap-3 sm:w-auto">
                        <button @click="closeModal()" class="w-1/2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 sm:w-auto dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600">انصراف</button>
                        <button @click="save()" class="flex w-1/2 items-center justify-center gap-2 rounded-lg bg-primary-600 px-6 py-2 text-sm font-bold text-white shadow-lg shadow-primary-600/20 hover:bg-primary-700 sm:w-auto">
                            <span x-show="!isSaving">ذخیره</span>
                            <span x-show="isSaving">در حال ذخیره...</span>
                        </button>
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
        isSaving: false,
        lastAnalyzed: null,

        // Entity Context
        entityType: null,
        entityId: null,
        title: '',
        slug: '',

        // Meta Data (Analysis only needs Focus Keyword)
        meta: {
            focus_keyword: ''
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
            if (titleInput) this.title = titleInput.value;

            // 2. Focus Keyword from Tags (if empty)
            if (!this.meta.focus_keyword) {
                 const chips = Array.from(document.querySelectorAll('.inline-flex.items-center span[x-text]'))
                                    .map(span => span.innerText)
                                    .filter(text => text);

                 if (chips.length > 0) {
                     this.meta.focus_keyword = chips[0];
                 }
            }
        },

        getScoreColor() {
            if (this.score < 50) return 'text-red-500';
            if (this.score < 80) return 'text-yellow-500';
            return 'text-green-500';
        },

        get analysisItems() {
            const items = [];
            const a = this.analysis;

            if (!a || !a.keyword_stats) return [];

            // Keyword Density
            const density = a.keyword_stats.density;
            items.push({
                label: 'چگالی کلمه کلیدی',
                desc: `چگالی فعلی ${density}% است. (استاندارد: 0.5 تا 2.5 درصد)`,
                passed: density >= 0.5 && density <= 2.5
            });

            // Word Count
            items.push({
                label: 'طول محتوا',
                desc: `${a.word_count} کلمه. (حداقل 300 کلمه توصیه می‌شود)`,
                passed: a.word_count > 300
            });

            // Images
            items.push({
                label: 'متن جایگزین تصاویر (Alt)',
                desc: `${a.structure.images_no_alt} تصویر بدون Alt هستند.`,
                passed: a.structure.images_no_alt === 0
            });

            // Internal Links
            items.push({
                label: 'لینک‌سازی داخلی',
                desc: `${a.links.internal} لینک داخلی پیدا شد.`,
                passed: a.links.internal > 0
            });

            return items;
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
                        title: this.title
                    })
                });

                const res = await response.json();
                if (res.success) {
                    this.updateCsrf(res.new_csrf_token);
                    this.analysis = res.data;
                    this.score = res.score;
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
        },

        async save() {
            this.isSaving = true;
            try {
                const response = await fetch('/admin/seopilot/save', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        entity_id: this.entityId,
                        entity_type: this.entityType,
                        meta: this.meta,
                        score: this.score
                    })
                });

                const res = await response.json();
                if (res.success) {
                    this.updateCsrf(res.new_csrf_token);
                    if (window.showToast) window.showToast('تنظیمات آنالیز با موفقیت ذخیره شد', 'success');
                    else alert('تنظیمات آنالیز با موفقیت ذخیره شد');
                    this.closeModal();
                }
            } catch (e) {
                alert('خطا در ذخیره سازی');
            } finally {
                this.isSaving = false;
            }
        }
    }));
});
</script>
