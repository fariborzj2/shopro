<!DOCTYPE html>
<html lang="fa" dir="rtl" class="scroll-smooth" x-data="{
    mobileMenuOpen: false,
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
    <title><?php echo htmlspecialchars($pageTitle ?? 'فروشگاه مدرن'); ?></title>
    <meta name="csrf-token" content="<?php echo csrf_token(); ?>">
    <meta name="description" content="<?php echo htmlspecialchars($metaDescription ?? 'توضیحات پیش‌فرض سایت'); ?>">
    <?php if (isset($canonicalUrl)): ?>
        <link rel="canonical" href="<?php echo $canonicalUrl; ?>">
    <?php endif; ?>

    <!-- Estedad Font Configuration (Same as Admin) -->
    <style>
        * {
           letter-spacing: -4%; 
        }
        @font-face {
            font-family: 'Estedad';
            src: url('/fonts/estedad/Estedad-Regular.woff2') format('woff2');
            font-weight: 400;
            font-style: normal;
            font-display: swap;
        }
        @font-face {
            font-family: 'Estedad';
            src: url('/fonts/estedad/Estedad-Medium.woff2') format('woff2');
            font-weight: 500;
            font-style: normal;
            font-display: swap;
        }
        @font-face {
            font-family: 'Estedad';
            src: url('/fonts/estedad/Estedad-SemiBold.woff2') format('woff2');
            font-weight: 600;
            font-style: normal;
            font-display: swap;
        }
        @font-face {
            font-family: 'Estedad';
            src: url('/fonts/estedad/Estedad-Bold.woff2') format('woff2');
            font-weight: 700;
            font-style: normal;
            font-display: swap;
        }
        @font-face {
            font-family: 'Estedad';
            src: url('/fonts/estedad/Estedad-ExtraBold.woff2') format('woff2');
            font-weight: 800;
            font-style: normal;
            font-display: swap;
        }
    </style>

    <!-- Tailwind CSS (Admin Config) -->
    <script src="https://cdn.tailwindcss.com?plugins=typography,forms,aspect-ratio"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        // Slate for a more premium feel (Admin Style)
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
                        'glass': '0 8px 32px 0 rgba(31, 38, 135, 0.07)',
                    },
                    borderRadius: {
                        'xl': '1rem',
                        '2xl': '1.5rem',
                    }
                }
            }
        }
    </script>

    <!-- Custom Utility Classes (Admin Style) -->
    <style type="text/tailwindcss">
        @layer components {
            .container { @apply max-w-7xl mx-auto px-4 sm:px-6 lg:px-8; }
            .btn { @apply inline-flex items-center justify-center px-6 py-2.5 rounded-xl font-bold transition-all duration-200 transform active:scale-95; }
            .btn-primary { @apply bg-primary-600 text-white hover:bg-primary-700 shadow-soft hover:shadow-lg hover:shadow-primary-500/30; }
            .btn-secondary { @apply bg-white text-gray-700 border border-gray-200 hover:bg-gray-50 hover:text-gray-900 shadow-soft; }
            .btn-ghost { @apply bg-transparent text-gray-600 hover:bg-gray-100 hover:text-gray-900; }
            .card { @apply bg-white dark:bg-gray-800 rounded-xl shadow-card border border-gray-100 dark:border-gray-700; }
        }
    </style>

    <!-- SwiperJS CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <!-- Pincode CSS -->
    <link rel="stylesheet" href="/css/pincode.css">

    <!-- Alpine.js -->
    <script src="/js/error-modal.js" defer></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
    </style>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('auth', {
                user: <?php echo json_encode(isset($_SESSION['user_id']) ? ['id' => $_SESSION['user_id'], 'name' => $_SESSION['user_name'], 'mobile' => $_SESSION['user_mobile']] : null); ?>,
                check() {
                    return !!this.user;
                },
                login(userData) {
                    this.user = userData;
                },
                logout() {
                    this.user = null;
                    window.location.href = '/logout';
                }
            });
        });
    </script>

    <?php if (isset($schema_data)): ?>
        <?php foreach ($schema_data as $schema): if($schema): ?>
            <script type="application/ld+json"><?php echo json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?></script>
        <?php endif; endforeach; ?>
    <?php endif; ?>
</head>
<body
    class="bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-100 font-sans antialiased min-h-screen flex flex-col transition-colors duration-300"
    x-data="<?php echo isset($store_data) ? "store(" . htmlspecialchars($store_data, ENT_QUOTES, 'UTF-8') . ")" : "{}"; ?>"
>

    <!-- Header / Navbar (Admin Style) -->
    <header class="sticky top-0 z-40 w-full bg-white/80 dark:bg-gray-900/80 backdrop-blur-md border-b border-gray-200 dark:border-gray-800 transition-colors duration-300">
        <div class="container mx-auto">
            <div class="flex items-center justify-between h-20">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center gap-3">
                    <div class="w-10 h-10 bg-primary-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-primary-500/30">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                    </div>
                    <a href="/" class="text-xl font-black tracking-tight text-gray-900 dark:text-white hover:text-primary-600 transition-colors">
                        فروشگاه مدرن
                    </a>
                </div>

                <!-- Desktop Menu -->
                <nav class="hidden lg:flex items-center space-x-2 space-x-reverse bg-gray-100 dark:bg-gray-800 p-1.5 rounded-full">
                    <a href="/" class="px-5 py-2.5 rounded-full text-sm font-bold transition-all duration-200 <?php echo ($_SERVER['REQUEST_URI'] == '/') ? 'bg-white dark:bg-gray-700 text-primary-600 shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-200 dark:hover:bg-gray-700'; ?>">
                        خانه
                    </a>
                    <a href="/#products" class="px-5 py-2.5 rounded-full text-sm font-bold text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-200 dark:hover:bg-gray-700 transition-all duration-200">
                        محصولات
                    </a>
                    <a href="/blog" class="px-5 py-2.5 rounded-full text-sm font-bold text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-200 dark:hover:bg-gray-700 transition-all duration-200">
                        وبلاگ
                    </a>
                    <a href="/page/about-us" class="px-5 py-2.5 rounded-full text-sm font-bold text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-200 dark:hover:bg-gray-700 transition-all duration-200">
                        درباره ما
                    </a>
                </nav>

                <!-- Actions -->
                <div class="hidden lg:flex items-center gap-3">
                    <!-- Theme Toggle -->
                    <button @click="toggleTheme()" class="p-3 rounded-full text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-primary-600 transition-colors border border-transparent hover:border-gray-200 dark:hover:border-gray-700">
                        <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                        <svg x-show="darkMode" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </button>

                    <template x-if="$store.auth.check()">
                        <div class="flex items-center gap-2">
                            <a href="/dashboard/orders" class="flex items-center space-x-2 space-x-reverse bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-200 hover:border-primary-500 px-4 py-2.5 rounded-full text-sm font-bold transition-all shadow-sm">
                                <div class="w-6 h-6 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center text-xs">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                </div>
                                <span x-text="$store.auth.user?.name || 'داشبورد'"></span>
                            </a>
                            <a href="/logout" @click.prevent="$store.auth.logout()" class="p-3 rounded-xl text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 border border-transparent hover:border-red-100 dark:hover:border-red-800 transition-colors" title="خروج">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                            </a>
                        </div>
                    </template>

                    <template x-if="!$store.auth.check()">
                        <button @click.prevent="$dispatch('open-auth-modal')" class="btn btn-primary  rounded-full shadow-lg shadow-primary-500/20">
                            <svg class="w-5 h-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" /></svg>
                            <span>ورود / ثبت‌نام</span>
                        </button>
                    </template>
                </div>

                <!-- Mobile Menu Button -->
                <div class="lg:hidden flex items-center gap-2">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white p-2 rounded-full border border-gray-200 dark:border-gray-700">
                        <span class="sr-only">باز کردن منو</span>
                        <svg x-show="!mobileMenuOpen" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg x-show="mobileMenuOpen" x-cloak class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu (Dropdown) -->
        <div x-show="mobileMenuOpen" x-cloak class="md:hidden border-t border-gray-100 dark:border-gray-800 bg-white/95 dark:bg-gray-900/95 backdrop-blur-lg absolute w-full left-0 z-50 shadow-xl rounded-b-3xl">
            <div class="px-4 pt-4 pb-8 space-y-2">
                <a href="/" class="flex items-center px-4 py-3 rounded-xl text-base font-bold text-gray-700 dark:text-gray-200 hover:text-primary-600 hover:bg-primary-50 dark:hover:bg-primary-900/20 transition-colors">
                    <svg class="w-6 h-6 ml-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                    خانه
                </a>
                <a href="/#products" class="flex items-center px-4 py-3 rounded-xl text-base font-bold text-gray-700 dark:text-gray-200 hover:text-primary-600 hover:bg-primary-50 dark:hover:bg-primary-900/20 transition-colors">
                    <svg class="w-6 h-6 ml-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                    محصولات
                </a>
                <a href="/blog" class="flex items-center px-4 py-3 rounded-xl text-base font-bold text-gray-700 dark:text-gray-200 hover:text-primary-600 hover:bg-primary-50 dark:hover:bg-primary-900/20 transition-colors">
                    <svg class="w-6 h-6 ml-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" /></svg>
                    وبلاگ
                </a>

                <div class="pt-4 mt-4 border-t border-gray-100 dark:border-gray-800">
                    <div class="flex items-center justify-between px-4 mb-4">
                        <span class="text-sm font-medium text-gray-500">حالت شب</span>
                        <button @click="toggleTheme()" class="p-2 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300">
                            <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                            <svg x-show="darkMode" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </button>
                    </div>

                    <template x-if="$store.auth.check()">
                        <div>
                            <a href="/dashboard/orders" class="flex items-center justify-center w-full text-center px-4 py-3 rounded-full text-base font-bold text-gray-700 dark:text-gray-200 bg-gray-50 dark:bg-gray-800 mb-3 border border-gray-200 dark:border-gray-700">
                                <svg class="w-5 h-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                <span x-text="'داشبورد ' + ($store.auth.user?.name || '')"></span>
                            </a>
                            <a href="/logout" @click.prevent="$store.auth.logout()" class="flex items-center justify-center w-full text-center px-4 py-3 rounded-full text-base font-bold text-red-600 bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/40">
                                <svg class="w-5 h-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                                خروج از حساب
                            </a>
                        </div>
                    </template>
                    <template x-if="!$store.auth.check()">
                        <button @click.prevent="$dispatch('open-auth-modal'); mobileMenuOpen = false" class="flex items-center justify-center w-full bg-primary-600 text-white px-4 py-3 rounded-full text-base font-bold shadow-lg shadow-primary-500/30 hover:bg-primary-700 transition-colors">
                            <svg class="w-6 h-6 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" /></svg>
                            ورود یا ثبت‌نام
                        </button>
                    </template>
                </div>
            </div>
        </div>
    </header>
