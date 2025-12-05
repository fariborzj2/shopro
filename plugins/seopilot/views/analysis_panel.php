<div x-data="seoPilotPanel"
     @open-seopilot.window="openModal($event.detail)"
     class="relative z-50"
     x-show="isOpen"
     style="display: none;"
     x-cloak>

    <!-- Modal Backdrop -->
    <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm transition-opacity"
         x-show="isOpen"
         x-transition.opacity></div>

    <!-- Modal Container -->
    <div class="fixed inset-0 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative w-full max-w-4xl rounded-2xl bg-white shadow-2xl ring-1 ring-slate-900/5 dark:bg-slate-800 dark:ring-white/10"
                 @click.outside="closeModal()"
                 x-show="isOpen"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95">

                <!-- Header -->
                <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4 dark:border-slate-700">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-600 text-white shadow-md shadow-indigo-600/20">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-slate-900 dark:text-white">SeoPilot Analysis</h2>
                            <p class="text-sm text-slate-500 dark:text-slate-400">تحلیل هوشمند محتوا و سئو</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <div class="flex flex-col items-end">
                            <span class="text-xs font-medium text-slate-500">امتیاز کلی</span>
                            <span class="text-2xl font-black" :class="getScoreColor()">
                                <span x-text="score"></span>/100
                            </span>
                        </div>
                        <button @click="closeModal()" class="rounded-lg p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-500 dark:hover:bg-slate-700">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Tabs -->
                <div class="border-b border-slate-200 bg-slate-50 px-6 dark:border-slate-700 dark:bg-slate-800/50">
                    <nav class="-mb-px flex gap-6" aria-label="Tabs">
                        <template x-for="tab in tabs" :key="tab.id">
                            <button
                                @click="currentTab = tab.id"
                                :class="currentTab === tab.id ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-300'"
                                class="whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium transition-colors"
                                x-text="tab.label">
                            </button>
                        </template>
                    </nav>
                </div>

                <!-- Content -->
                <div class="p-6">

                    <!-- Tab: Preview (SERP) -->
                    <div x-show="currentTab === 'preview'" class="space-y-6">

                        <!-- Toggle Device -->
                        <div class="flex justify-end">
                            <div class="inline-flex rounded-lg bg-slate-100 p-1 dark:bg-slate-700">
                                <button @click="device = 'mobile'" :class="device === 'mobile' ? 'bg-white text-slate-900 shadow dark:bg-slate-600 dark:text-white' : 'text-slate-500 dark:text-slate-400'" class="rounded px-3 py-1.5 text-sm font-medium transition-all">Mobile</button>
                                <button @click="device = 'desktop'" :class="device === 'desktop' ? 'bg-white text-slate-900 shadow dark:bg-slate-600 dark:text-white' : 'text-slate-500 dark:text-slate-400'" class="rounded px-3 py-1.5 text-sm font-medium transition-all">Desktop</button>
                            </div>
                        </div>

                        <!-- Google Preview Card -->
                        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800">
                            <!-- Breadcrumb -->
                            <div class="mb-1 flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400">
                                <span class="flex h-5 w-5 items-center justify-center rounded-full bg-slate-100 text-xs dark:bg-slate-700">W</span>
                                <div class="flex flex-col">
                                    <span class="text-xs text-slate-900 dark:text-white">Your Site Name</span>
                                    <span class="text-[10px]" x-text="'https://example.com/' + (slug || 'your-slug')"></span>
                                </div>
                                <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" /></svg>
                            </div>

                            <!-- Title -->
                            <h3 class="mb-1 text-xl text-[#1a0dab] hover:underline dark:text-[#8ab4f8]" x-text="meta.title || title || 'عنوان صفحه خود را وارد کنید'"></h3>

                            <!-- Description -->
                            <p class="text-sm leading-6 text-[#4d5156] dark:text-[#bdc1c6]" x-text="meta.description || 'توضیحات متا هنوز وارد نشده است. گوگل ممکن است بخشی از متن صفحه را در اینجا نمایش دهد.'"></p>
                        </div>

                        <!-- Editor Fields -->
                        <div class="grid gap-6 md:grid-cols-2">
                            <!-- Focus Keyword & Suggestions -->
                            <div class="col-span-2">
                                <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">کلمه کلیدی کانونی</label>
                                <div class="relative flex gap-2">
                                    <div class="relative w-full">
                                        <input type="text" x-model="meta.focus_keyword" @input.debounce.500ms="analyze()" class="w-full rounded-lg border-slate-300 bg-slate-50 focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white" placeholder="مثلاً: خرید گوشی موبایل">
                                        <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                                            <span class="text-xs text-slate-400" x-text="meta.focus_keyword.length > 0 ? 'ذخیره شد' : ''"></span>
                                        </div>
                                    </div>
                                    <button @click="suggestKeywords()" class="whitespace-nowrap rounded-lg bg-slate-200 px-4 py-2 text-sm text-slate-700 hover:bg-slate-300 dark:bg-slate-700 dark:text-slate-300">
                                        💡 پیشنهاد
                                    </button>
                                </div>
                                <!-- Suggestions Dropdown -->
                                <div x-show="suggestions.length > 0" class="mt-2 flex flex-wrap gap-2">
                                    <template x-for="s in suggestions" :key="s">
                                        <button @click="meta.focus_keyword = s; suggestions = []; analyze()" class="rounded-full bg-indigo-50 px-3 py-1 text-xs text-indigo-700 hover:bg-indigo-100 dark:bg-indigo-900/30 dark:text-indigo-300" x-text="s"></button>
                                    </template>
                                </div>
                            </div>

                            <!-- SEO Title -->
                            <div class="col-span-2">
                                <label class="mb-2 flex justify-between text-sm font-medium text-slate-700 dark:text-slate-300">
                                    <span>عنوان سئو (SEO Title)</span>
                                    <span class="text-xs" :class="getTitleColor()">
                                        <span x-text="pixelWidth(meta.title)"></span>px / 580px
                                    </span>
                                </label>
                                <input type="text" x-model="meta.title" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white">
                                <!-- Progress Bar -->
                                <div class="mt-2 h-1.5 w-full overflow-hidden rounded-full bg-slate-100 dark:bg-slate-700">
                                    <div class="h-full transition-all duration-300"
                                         :class="getTitleBarColor()"
                                         :style="`width: ${Math.min((pixelWidth(meta.title) / 600) * 100, 100)}%`"></div>
                                </div>
                            </div>

                            <!-- Meta Description -->
                            <div class="col-span-2">
                                <label class="mb-2 flex justify-between text-sm font-medium text-slate-700 dark:text-slate-300">
                                    <span>توضیحات متا (Meta Description)</span>
                                    <div class="flex items-center gap-2">
                                        <button @click="magicFix()" class="flex items-center gap-1 rounded bg-indigo-50 px-2 py-0.5 text-xs font-medium text-indigo-600 hover:bg-indigo-100 dark:bg-indigo-900/30 dark:text-indigo-400">
                                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                                            Magic Fix
                                        </button>
                                        <span class="text-xs text-slate-500" x-text="meta.description.length + '/160'"></span>
                                    </div>
                                </label>
                                <textarea x-model="meta.description" rows="3" class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Tab: Analysis -->
                    <div x-show="currentTab === 'analysis'" class="space-y-4">
                        <div class="rounded-lg border border-slate-200 bg-white dark:border-slate-700 dark:bg-slate-800">
                             <!-- Action: Auto Alt -->
                             <div class="flex items-center justify-between border-b border-slate-100 p-4 dark:border-slate-700" x-show="analysis.structure && analysis.structure.images_no_alt > 0">
                                <span class="text-sm font-medium text-slate-900 dark:text-white">
                                    <span class="text-red-500 font-bold" x-text="analysis.structure.images_no_alt"></span> تصویر بدون Alt یافت شد!
                                </span>
                                <button @click="autoAlt()" class="text-xs bg-indigo-600 text-white px-3 py-1.5 rounded hover:bg-indigo-700">اصلاح خودکار (Auto Alt)</button>
                             </div>

                            <!-- Items -->
                            <template x-for="(item, index) in analysisItems" :key="index">
                                <div class="flex items-start gap-3 border-b border-slate-100 p-4 last:border-0 dark:border-slate-700">
                                    <div class="mt-0.5 flex-shrink-0">
                                        <div class="flex h-5 w-5 items-center justify-center rounded-full"
                                             :class="item.passed ? 'bg-green-100 text-green-600 dark:bg-green-900/30' : 'bg-red-100 text-red-600 dark:bg-red-900/30'">
                                            <svg x-show="item.passed" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                            <svg x-show="!item.passed" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-medium text-slate-900 dark:text-white" x-text="item.label"></h4>
                                        <p class="text-xs text-slate-500 dark:text-slate-400" x-text="item.desc"></p>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Tab: Social -->
                    <div x-show="currentTab === 'social'" class="space-y-6">
                         <!-- Facebook/OG -->
                         <div class="rounded-xl border border-slate-200 p-4 dark:border-slate-700">
                            <h4 class="mb-3 text-sm font-bold text-slate-700 dark:text-slate-300">Facebook / OpenGraph</h4>
                            <div class="flex gap-4">
                                <!-- Preview -->
                                <div class="w-1/2 overflow-hidden rounded-lg border border-slate-200 bg-slate-50 dark:border-slate-700 dark:bg-slate-900">
                                    <div class="aspect-video w-full bg-slate-200 bg-cover bg-center dark:bg-slate-800" :style="`background-image: url('${meta.og_image || 'https://placehold.co/600x315'}')`"></div>
                                    <div class="p-3">
                                        <div class="truncate text-xs uppercase text-slate-500">EXAMPLE.COM</div>
                                        <div class="truncate text-sm font-bold text-slate-900 dark:text-white" x-text="meta.title"></div>
                                        <div class="line-clamp-1 text-xs text-slate-600 dark:text-slate-400" x-text="meta.description"></div>
                                    </div>
                                </div>
                                <!-- Inputs -->
                                <div class="w-1/2 space-y-3">
                                    <div>
                                        <label class="block text-xs font-medium text-slate-700 dark:text-slate-300">تصویر شاخص شبکه اجتماعی</label>
                                        <input type="text" x-model="meta.og_image" placeholder="URL تصویر..." class="mt-1 w-full rounded-md border-slate-300 text-xs dark:border-slate-600 dark:bg-slate-700">
                                    </div>
                                </div>
                            </div>
                         </div>
                    </div>

                    <!-- Tab: Schema -->
                    <div x-show="currentTab === 'schema'" class="space-y-6">
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">نوع محتوا (Schema Type)</label>
                            <select x-model="meta.schema_type" class="w-full rounded-lg border-slate-300 bg-slate-50 focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white">
                                <option value="Article">مقاله (Article)</option>
                                <option value="NewsArticle">خبر (NewsArticle)</option>
                                <option value="Product">محصول (Product)</option>
                                <option value="VideoObject">ویدیو (Video)</option>
                            </select>
                        </div>

                        <div class="rounded-md bg-yellow-50 p-4 dark:bg-yellow-900/20">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="mr-3">
                                    <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">نکته مهم</h3>
                                    <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
                                        <p>SeoPilot به صورت خودکار اسکیمای استاندارد را تولید می‌کند. تنظیمات اینجا فقط نوع کلی را تغییر می‌دهد.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Footer -->
                <div class="flex items-center justify-between border-t border-slate-200 bg-slate-50 px-6 py-4 dark:border-slate-700 dark:bg-slate-800/50">
                    <div class="text-xs text-slate-500">
                        آخرین آنالیز: <span x-text="lastAnalyzed || 'هرگز'"></span>
                    </div>
                    <div class="flex gap-3">
                        <button @click="closeModal()" class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 dark:border-slate-600 dark:bg-slate-700 dark:text-white dark:hover:bg-slate-600">انصراف</button>
                        <button @click="save()" class="flex items-center gap-2 rounded-lg bg-indigo-600 px-6 py-2 text-sm font-bold text-white shadow-lg shadow-indigo-600/20 hover:bg-indigo-700">
                            <span x-show="!isSaving">ذخیره تغییرات</span>
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
        currentTab: 'preview',
        device: 'mobile',
        score: 0,
        isSaving: false,
        lastAnalyzed: null,
        suggestions: [],

        // Entity Context
        entityType: null,
        entityId: null,
        title: '', // Original Post Title
        slug: '',

        // Meta Data (Editable)
        meta: {
            title: '',
            description: '',
            focus_keyword: '',
            canonical: '',
            robots: [],
            og_image: '',
            schema_type: 'Article'
        },

        // Analysis Result
        analysis: {},

        tabs: [
            { id: 'preview', label: 'پیش‌نمایش گوگل' },
            { id: 'analysis', label: 'تحلیل محتوا' },
            { id: 'social', label: 'شبکه‌های اجتماعی' },
            { id: 'schema', label: 'اسکیما (Schema)' }
        ],

        openModal(detail) {
            this.isOpen = true;
            this.entityType = detail.type;
            this.entityId = detail.id;
            this.title = detail.title;
            this.slug = detail.slug;

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

        // Pixel Width Calculator (Arial 16px)
        pixelWidth(text) {
            if (!text) return 0;
            const canvas = document.createElement('canvas');
            const context = canvas.getContext('2d');
            context.font = '16px Arial'; // Google standard approximation
            return Math.floor(context.measureText(text).width);
        },

        getTitleColor() {
            const w = this.pixelWidth(this.meta.title);
            if (w === 0) return 'text-slate-400';
            if (w < 200) return 'text-orange-500'; // Too short
            if (w > 580) return 'text-red-500';    // Too long
            return 'text-green-500'; // Perfect
        },

        getTitleBarColor() {
            const w = this.pixelWidth(this.meta.title);
            if (w < 200) return 'bg-orange-500';
            if (w > 580) return 'bg-red-500';
            return 'bg-green-500';
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
                        title: this.meta.title || this.title
                    })
                });

                const res = await response.json();
                if (res.success) {
                    this.analysis = res.data;
                    this.score = res.score;
                    this.lastAnalyzed = new Date().toLocaleTimeString('fa-IR');
                }
            } catch (e) {
                console.error('SeoPilot Analysis Failed', e);
            }
        },

        async magicFix() {
            let content = '';
            if (window.tinymce && tinymce.activeEditor) {
                content = tinymce.activeEditor.getContent();
            }

            try {
                const response = await fetch('/admin/seopilot/magic-fix', {
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
                    this.meta.description = res.suggestion.description;
                    if (!this.meta.focus_keyword && res.suggestion.keyword) {
                        this.meta.focus_keyword = res.suggestion.keyword;
                    }
                    this.analyze();
                }
            } catch (e) {
                console.error('Magic Fix Failed', e);
            }
        },

        async suggestKeywords() {
             if (!this.meta.focus_keyword || this.meta.focus_keyword.length < 2) {
                 alert('لطفاً ابتدا چند حرف وارد کنید');
                 return;
             }

             try {
                const response = await fetch('/admin/seopilot/suggest', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        query: this.meta.focus_keyword
                    })
                });
                const res = await response.json();
                if (res.success && res.suggestions) {
                    this.suggestions = res.suggestions;
                }
             } catch(e) { console.error(e); }
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
                if (res.success && res.count > 0) {
                    // Update Editor Content
                    tinymce.activeEditor.setContent(res.content);
                    alert(res.count + ' تصویر به صورت خودکار تگ Alt دریافت کردند.');
                    this.analyze();
                } else {
                    alert('هیچ تصویری بدون Alt یافت نشد یا خطا رخ داد.');
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
                    if (window.showToast) window.showToast('SEO settings saved successfully', 'success');
                    else alert('تنظیمات سئو با موفقیت ذخیره شد');
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
