<!DOCTYPE html>
<html lang="fa" dir="rtl" x-data="{
    darkMode: localStorage.getItem('darkMode') === 'true',
    sidebarOpen: false,
    toggleTheme() {
        this.darkMode = !this.darkMode;
        localStorage.setItem('darkMode', this.darkMode);
        if (this.darkMode) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    }
}" :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo csrf_token(); ?>">
    <title><?php echo isset($title) ? htmlspecialchars($title) . ' | پنل مدیریت' : 'پنل مدیریت'; ?></title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        // Switching to Slate for a more premium feel than default Gray
                        gray: {
                            50: '#f8fafc',
                            100: '#f1f5f9',
                            200: '#e2e8f0',
                            300: '#cbd5e1',
                            400: '#94a3b8',
                            500: '#64748b',
                            600: '#475569',
                            700: '#334155',
                            800: '#1e293b',
                            900: '#0f172a',
                        },
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        }
                    },
                    fontFamily: {
                        sans: ['Estedad', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    },
                    boxShadow: {
                        'soft': '0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03)',
                        'card': '0 0 0 1px rgba(0,0,0,0.03), 0 1px 3px 0 rgba(0,0,0,0.05), 0 1px 2px -1px rgba(0,0,0,0.05)',
                    },
                    borderRadius: {
                        'xl': '1rem',
                        '2xl': '1.5rem',
                    }
                }
            }
        }
    </script>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="/css/admin.css">

    <!-- Alpine.js -->
    <script src="/js/error-modal.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <!-- Custom Jalali Datepicker -->
    <link rel="stylesheet" href="/css/jalali-datepicker.css">
    <script src="/js/jalali-datepicker.js" defer></script>

    <!-- TinyMCE -->
    <script src="https://cdn.tiny.cloud/1/<?php echo \App\Models\Setting::getTinyMceApiKey(); ?>/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

     <?php \App\Core\Plugin\Assets::renderStyles(); ?>

    <script>
        // Global AJAX setup
        document.addEventListener('alpine:init', () => {
            const originalFetch = window.fetch;
            window.fetch = function(url, options) {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                if (options && options.method && options.method.toUpperCase() !== 'GET') {
                    if (!options.headers) options.headers = {};
                    if (!(options.body instanceof FormData)) {
                        if (!options.headers['Content-Type']) options.headers['Content-Type'] = 'application/json';
                    }
                    options.headers['X-CSRF-TOKEN'] = csrfToken;
                }
                return originalFetch(url, options);
            };
        });

        if (localStorage.getItem('darkMode') === 'true') {
            document.documentElement.classList.add('dark');
        }
    </script>
</head>
<body class="bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-100 font-sans antialiased transition-colors duration-300">
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:right-4 focus:z-[100] focus:px-4 focus:py-2 focus:bg-primary-600 focus:text-white focus:rounded-lg">پرش به محتوای اصلی</a>
    <div class="flex h-screen overflow-hidden">

        <!-- Sidebar -->
        <?php partial('sidebar'); ?>

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col overflow-hidden relative">

            <!-- Navbar -->
            <?php partial('navbar'); ?>

            <!-- Main Content -->
            <main id="main-content" class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 dark:bg-gray-900 p-4 md:p-6 lg:p-8">
                <div class="container mx-auto max-w-7xl">
                    <!-- Alerts/Toasts -->
                    <?php if (isset($_GET['error_msg'])): ?>
                        <div x-data="{ show: true }" x-show="show" x-transition.duration.300ms x-init="setTimeout(() => show = false, 5000)"
                             class="mb-6 flex items-center p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl shadow-sm">
                            <div class="text-red-500 p-2 bg-red-100 dark:bg-red-800 rounded-lg ml-3">
                                <?php partial('icon', ['name' => 'close', 'class' => 'w-5 h-5']); ?>
                            </div>
                            <div class="text-red-700 dark:text-red-300 flex-1">
                                <strong class="block font-bold text-sm">خطا</strong>
                                <span class="text-sm"><?= htmlspecialchars(urldecode($_GET['error_msg'])) ?></span>
                            </div>
                            <button @click="show = false" class="text-red-400 hover:text-red-600 p-1" aria-label="بستن">
                                <?php partial('icon', ['name' => 'close', 'class' => 'w-4 h-4']); ?>
                            </button>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($_GET['success_msg'])): ?>
                        <div x-data="{ show: true }" x-show="show" x-transition.duration.300ms x-init="setTimeout(() => show = false, 5000)"
                             class="mb-6 flex items-center p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl shadow-sm">
                            <div class="text-green-500 p-2 bg-green-100 dark:bg-green-800 rounded-lg ml-3">
                                <?php partial('icon', ['name' => 'check', 'class' => 'w-5 h-5']); ?>
                            </div>
                            <div class="text-green-700 dark:text-green-300 flex-1">
                                <strong class="block font-bold text-sm">موفقیت</strong>
                                <span class="text-sm"><?= htmlspecialchars(urldecode($_GET['success_msg'])) ?></span>
                            </div>
                            <button @click="show = false" class="text-green-400 hover:text-green-600 p-1" aria-label="بستن">
                                <?php partial('icon', ['name' => 'close', 'class' => 'w-4 h-4']); ?>
                            </button>
                        </div>
                    <?php endif; ?>

                    <!-- Content Injection -->
                    <div class="animate-fade-in-up">
                        <?php echo $content; ?>
                    </div>
                </div>
            </main>
        </div>

    </div>

    <?php partial('_error_modal'); ?>

     <?php \App\Core\Plugin\Assets::renderScripts(true); ?>

    <!-- Toast Notification Container -->
    <div
        x-data="{ show: false, message: '', type: 'error' }"
        @show-toast.window="show = true; message = $event.detail.message; type = $event.detail.type || 'error'; setTimeout(() => show = false, 5000)"
        x-show="show"
        role="alert"
        aria-live="assertive"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-2"
        class="fixed bottom-6 left-6 z-[60] px-6 py-3 rounded-2xl shadow-2xl flex items-center gap-3 min-w-[300px]"
        :class="type === 'success' ? 'bg-emerald-600 text-white' : (type === 'info' ? 'bg-blue-600 text-white' : 'bg-red-600 text-white')"
        style="display: none;"
    >
        <div x-show="type === 'success'">
            <?php partial('icon', ['name' => 'check', 'class' => 'w-6 h-6']); ?>
        </div>
        <div x-show="type === 'error'">
             <?php partial('icon', ['name' => 'close', 'class' => 'w-6 h-6']); ?>
        </div>
        <div x-show="type === 'info'" class="animate-spin">
             <?php partial('icon', ['name' => 'sun', 'class' => 'w-6 h-6']); ?>
        </div>
        <span x-text="message" class="font-bold text-sm"></span>
    </div>

    <!-- AI Prompt Modal -->
    <div x-data="{
        isOpen: false,
        title: '',
        placeholder: '',
        value: '',
        callback: null,
        open(title, placeholder, callback) {
            this.title = title;
            this.placeholder = placeholder;
            this.callback = callback;
            this.value = '';
            this.isOpen = true;
            this.$nextTick(() => this.$refs.input.focus());
        },
        submit() {
            if (this.value) {
                this.callback(this.value);
                this.isOpen = false;
            }
        }
    }"
    @open-ai-prompt.window="open($event.detail.title, $event.detail.placeholder, $event.detail.callback)"
    x-show="isOpen" x-cloak class="fixed inset-0 z-[70] overflow-y-auto" role="dialog" aria-modal="true" aria-labelledby="ai-prompt-title">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="isOpen = false"></div>
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl w-full max-w-md relative z-10 p-8 transform transition-all border border-gray-100 dark:border-gray-700">
                <h3 id="ai-prompt-title" class="text-xl font-extrabold text-gray-900 dark:text-white mb-4" x-text="title"></h3>
                <div class="space-y-4">
                    <input type="text" x-model="value" x-ref="input" :placeholder="placeholder" @keydown.enter="submit"
                           aria-labelledby="ai-prompt-title"
                           class="w-full px-4 py-3 rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 focus:ring-2 focus:ring-primary-500 outline-none transition-all">
                    <div class="flex justify-end gap-3">
                        <button @click="isOpen = false" title="انصراف و بستن" class="px-6 py-2.5 text-gray-500 font-bold hover:bg-gray-100 dark:hover:bg-gray-700 rounded-xl transition-all">انصراف</button>
                        <button @click="submit" title="تایید و ارسال" class="px-8 py-2.5 bg-primary-600 text-white font-extrabold rounded-xl shadow-lg shadow-primary-500/30 hover:bg-primary-700 transition-all">تایید</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- AI Suggestion Modal (for long results like replies) -->
    <div x-data="{
        isOpen: false,
        title: '',
        content: '',
        callback: null,
        open(title, content, callback) {
            this.title = title;
            this.content = content;
            this.callback = callback;
            this.isOpen = true;
        },
        confirm() {
            this.callback(this.content);
            this.isOpen = false;
        }
    }"
    @open-ai-suggestion.window="open($event.detail.title, $event.detail.content, $event.detail.callback)"
    x-show="isOpen" x-cloak class="fixed inset-0 z-[70] overflow-y-auto" role="dialog" aria-modal="true" aria-labelledby="ai-suggestion-title">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="isOpen = false"></div>
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl w-full max-w-2xl relative z-10 flex flex-col max-h-[85vh] transform transition-all border border-gray-100 dark:border-gray-700">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                    <h3 id="ai-suggestion-title" class="text-xl font-extrabold text-gray-900 dark:text-white" x-text="title"></h3>
                    <button @click="isOpen = false" class="text-gray-400 hover:text-gray-600" aria-label="بستن" title="بستن">
                         <?php partial('icon', ['name' => 'close', 'class' => 'w-6 h-6']); ?>
                    </button>
                </div>
                <div class="p-8 overflow-y-auto flex-1">
                    <textarea x-model="content" rows="10" aria-labelledby="ai-suggestion-title" class="w-full px-4 py-3 rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 focus:ring-2 focus:ring-primary-500 outline-none transition-all text-sm leading-relaxed"></textarea>
                </div>
                <div class="p-6 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-100 dark:border-gray-700 flex justify-end gap-3 rounded-b-3xl">
                    <button @click="isOpen = false" title="رد کردن این پیشنهاد" class="px-6 py-2.5 text-gray-500 font-bold hover:bg-gray-100 dark:hover:bg-gray-700 rounded-xl transition-all">رد کردن</button>
                    <button @click="confirm" title="تایید و درج متن در ویرایشگر" class="px-8 py-2.5 bg-emerald-600 text-white font-extrabold rounded-xl shadow-lg shadow-emerald-500/30 hover:bg-emerald-700 transition-all flex items-center gap-2">
                        <?php partial('icon', ['name' => 'check', 'class' => 'w-5 h-5']); ?>
                        استفاده از این متن
                    </button>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
