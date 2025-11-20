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
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <!-- TinyMCE -->
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

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
    <div class="flex h-screen overflow-hidden">

        <!-- Sidebar -->
        <?php partial('sidebar'); ?>

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col overflow-hidden relative">

            <!-- Navbar -->
            <?php partial('navbar'); ?>

            <!-- Main Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 dark:bg-gray-900 p-4 md:p-6 lg:p-8">
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
                            <button @click="show = false" class="text-red-400 hover:text-red-600 p-1">
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
                            <button @click="show = false" class="text-green-400 hover:text-green-600 p-1">
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
</body>
</html>
