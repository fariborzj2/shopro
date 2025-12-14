<!DOCTYPE html>
<html lang="fa" dir="rtl" x-data="{
    sidebarOpen: false,
    darkMode: localStorage.getItem('darkMode') === 'true',
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
    <title><?php echo $pageTitle ?? 'داشبورد کاربری'; ?></title>

    <!-- Fonts -->
    <link href="/fonts/estedad/style.css" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
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

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>

    <script>
        // Global AJAX setup for CSRF
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

    <style type="text/tailwindcss">
        [x-cloak] { display: none !important; }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }
        .dark ::-webkit-scrollbar-thumb {
            background: #475569;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>
<body class="bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-100 font-sans antialiased transition-colors duration-300">

    <div class="flex h-screen overflow-hidden">

        <!-- Sidebar Overlay -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-gray-900/50 backdrop-blur-sm lg:hidden" x-transition.opacity></div>

        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0' : 'translate-x-full lg:translate-x-0'"
               class="fixed right-0 top-0 z-50 w-72 h-full overflow-y-auto transition-transform duration-300 ease-in-out
                      bg-white dark:bg-gray-800 border-l border-gray-200 dark:border-gray-700 lg:static shadow-2xl lg:shadow-none flex flex-col">

            <!-- Logo Area -->
            <div class="flex items-center justify-between h-20 px-6 border-b border-gray-100 dark:border-gray-700">
                <a href="/" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 bg-primary-50 dark:bg-primary-900/30 rounded-xl flex items-center justify-center text-primary-600 dark:text-primary-400">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-lg font-bold text-gray-800 dark:text-white group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">فروشگاه من</span>
                        <span class="text-xs text-gray-500 dark:text-gray-400">پنل کاربری</span>
                    </div>
                </a>
                <button @click="sidebarOpen = false" class="lg:hidden text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- User Info (Sidebar Top) -->
            <div class="p-6 pb-2">
                <div class="flex items-center gap-4 p-4 bg-gray-50 dark:bg-gray-700/30 rounded-2xl border border-gray-100 dark:border-gray-700">
                    <div class="w-12 h-12 rounded-full bg-white dark:bg-gray-600 flex items-center justify-center text-xl font-bold text-primary-600 dark:text-primary-400 shadow-sm overflow-hidden ring-2 ring-white dark:ring-gray-800">
                        <?php if(!empty($_SESSION['user_avatar'])): ?>
                            <img src="<?php echo $_SESSION['user_avatar']; ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <?php echo mb_substr($_SESSION['user_name'] ?? 'U', 0, 1); ?>
                        <?php endif; ?>
                    </div>
                    <div class="flex flex-col min-w-0">
                        <span class="font-bold text-sm truncate text-gray-900 dark:text-gray-100"><?php echo $_SESSION['user_name'] ?? 'کاربر گرامی'; ?></span>
                        <span class="text-xs text-gray-500 dark:text-gray-400 truncate"><?php echo $_SESSION['user_mobile'] ?? ''; ?></span>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-4 space-y-1.5 py-4">
                <?php
                    $navItems = [
                        ['id' => 'home', 'url' => '/dashboard', 'label' => 'پیشخوان', 'icon' => 'M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z'],
                        ['id' => 'orders', 'url' => '/dashboard/orders', 'label' => 'سفارش‌های من', 'icon' => 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z'],
                        ['id' => 'messages', 'url' => '/dashboard/messages', 'label' => 'پیام‌ها و پشتیبانی', 'icon' => 'M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z', 'badge' => $unreadMessages ?? 0],
                        ['id' => 'profile', 'url' => '/dashboard/profile', 'label' => 'پروفایل و امنیت', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                        ['id' => 'logs', 'url' => '/dashboard/logs', 'label' => 'گزارش فعالیت‌ها', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ];
                ?>

                <?php foreach($navItems as $item): ?>
                    <?php
                        $isActive = ($activePage ?? '') === $item['id'];
                        $baseClasses = "group flex items-center px-3 py-3 text-sm font-medium rounded-xl transition-all duration-200";
                        $activeClasses = "bg-primary-50 text-primary-700 dark:bg-primary-900/20 dark:text-primary-300 shadow-sm";
                        $inactiveClasses = "text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700/50 dark:hover:text-gray-100";
                    ?>
                    <a href="<?php echo $item['url']; ?>" class="<?php echo $baseClasses . ' ' . ($isActive ? $activeClasses : $inactiveClasses); ?>">
                        <svg class="w-5 h-5 ml-3 transition-colors <?php echo $isActive ? 'text-primary-600 dark:text-primary-400' : 'text-gray-400 group-hover:text-gray-500 dark:text-gray-500 dark:group-hover:text-gray-300'; ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?php echo $item['icon']; ?>"></path>
                        </svg>
                        <span class="flex-1"><?php echo $item['label']; ?></span>
                        <?php if(isset($item['badge']) && $item['badge'] > 0): ?>
                            <span class="bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow-sm"><?php echo $item['badge']; ?></span>
                        <?php endif; ?>
                        <?php if($isActive): ?>
                            <span class="w-1.5 h-1.5 rounded-full bg-primary-600 dark:bg-primary-400 mr-2"></span>
                        <?php endif; ?>
                    </a>
                <?php endforeach; ?>
            </nav>

            <!-- Bottom Actions -->
            <div class="p-4 border-t border-gray-200 dark:border-gray-700 mt-auto">
                <a href="/logout" class="flex items-center px-4 py-3 text-sm font-medium text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/10 rounded-xl transition-all duration-200 group">
                    <svg class="w-5 h-5 ml-3 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    خروج از حساب
                </a>
            </div>
        </aside>

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col overflow-hidden relative bg-gray-50 dark:bg-gray-900">

            <!-- Mobile Header -->
            <header class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 lg:hidden flex items-center justify-between p-4 px-6 shadow-sm z-30">
                <div class="flex items-center gap-3">
                    <button @click="sidebarOpen = !sidebarOpen" class="p-2 -mr-2 rounded-lg text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    <span class="font-bold text-gray-800 dark:text-white">داشبورد</span>
                </div>
                <div class="w-8"></div> <!-- Spacer for balance -->
            </header>

            <!-- Desktop Header (optional, for breadcrumbs/theme toggle) -->
            <header class="hidden lg:flex items-center justify-between h-20 px-8 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 shadow-sm z-30">
                <div>
                    <h1 class="text-2xl font-extrabold text-gray-800 dark:text-white tracking-tight"><?php echo $pageTitle ?? 'داشبورد'; ?></h1>
                    <?php if(isset($breadcrumbs) && is_array($breadcrumbs)): ?>
                    <nav class="flex mt-1" aria-label="Breadcrumb">
                        <ol class="flex items-center space-x-2 space-x-reverse text-sm text-gray-500 dark:text-gray-400">
                            <?php foreach($breadcrumbs as $index => $crumb): ?>
                                <li>
                                    <span class="<?php echo $index === count($breadcrumbs)-1 ? 'font-medium text-gray-700 dark:text-gray-300' : ''; ?>">
                                        <?php echo $crumb; ?>
                                    </span>
                                </li>
                                <?php if($index < count($breadcrumbs)-1): ?>
                                    <li><svg class="w-3 h-3 text-gray-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg></li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ol>
                    </nav>
                    <?php endif; ?>
                </div>

                <div class="flex items-center gap-4">
                    <!-- Theme Toggle -->
                    <button @click="toggleTheme()" class="p-2.5 rounded-full text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 transition-colors">
                        <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                        <svg x-show="darkMode" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </button>

                    <!-- Notification Bell (Placeholder) -->
                    <button class="relative p-2.5 rounded-full text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        <?php if(($unreadMessages ?? 0) > 0): ?>
                            <span class="absolute top-2 left-2.5 w-2 h-2 bg-red-500 rounded-full border border-white dark:border-gray-800"></span>
                        <?php endif; ?>
                    </button>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto p-4 md:p-6 lg:p-8">

                <div class="max-w-7xl mx-auto space-y-6">
                    <!-- Alerts -->
                    <?php if (isset($_GET['success_msg'])): ?>
                    <div x-data="{ show: true }" x-show="show" x-transition.duration.300ms x-init="setTimeout(() => show = false, 5000)" class="flex items-center p-4 bg-green-50 border border-green-200 text-green-700 dark:bg-green-900/20 dark:border-green-800 dark:text-green-300 rounded-xl shadow-sm" role="alert">
                        <svg class="w-5 h-5 ml-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="font-medium"><?php echo htmlspecialchars($_GET['success_msg']); ?></span>
                        <button @click="show = false" class="mr-auto text-green-500 hover:text-green-700 dark:text-green-400 dark:hover:text-green-200"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                    </div>
                    <?php endif; ?>

                    <?php if (isset($_GET['error_msg'])): ?>
                    <div x-data="{ show: true }" x-show="show" x-transition.duration.300ms x-init="setTimeout(() => show = false, 5000)" class="flex items-center p-4 bg-red-50 border border-red-200 text-red-700 dark:bg-red-900/20 dark:border-red-800 dark:text-red-300 rounded-xl shadow-sm" role="alert">
                        <svg class="w-5 h-5 ml-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="font-medium"><?php echo htmlspecialchars($_GET['error_msg']); ?></span>
                        <button @click="show = false" class="mr-auto text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-200"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                    </div>
                    <?php endif; ?>

                    <!-- Page Content -->
                    <div class="animate-fade-in-up">
                        <?php echo $content; ?>
                    </div>
                </div>

                <!-- Simple Footer -->
                <div class="mt-8 pt-8 border-t border-gray-200 dark:border-gray-800 text-center text-sm text-gray-400 dark:text-gray-500">
                    &copy; <?php echo date('Y'); ?> تمامی حقوق محفوظ است.
                </div>
            </main>
        </div>

    </div>

</body>
</html>
