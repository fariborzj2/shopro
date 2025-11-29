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

    <!-- Estedad Font Configuration -->
    <style>
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

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=typography,forms,aspect-ratio"></script>
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

    <style type="text/tailwindcss">
        @layer components {
            .container { @apply max-w-7xl mx-auto px-4 sm:px-6 lg:px-8; }
            .glass-panel { @apply bg-white/70 backdrop-blur-lg border border-white/50 shadow-xl rounded-2xl; }
            .btn { @apply inline-flex items-center justify-center px-6 py-2.5 rounded-md font-bold transition-all duration-200 transform active:scale-95; }
            .btn-primary { @apply bg-primary-600 text-white hover:bg-primary-700 shadow-lg shadow-primary-500/30; }
            .btn-secondary { @apply bg-gray-100 text-gray-700 hover:bg-gray-200; }
            .btn-danger { @apply bg-red-50 text-red-600 hover:bg-red-100; }
            .btn-ghost { @apply bg-transparent text-gray-600 hover:bg-gray-50 hover:text-primary-600; }
            .form-input { @apply block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 bg-gray-50 py-3 px-4; }
            .form-label { @apply block text-sm font-bold text-gray-700 mb-2; }
            .page-title { @apply text-3xl font-extrabold text-gray-900 mb-8 text-center; }
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
    class="bg-gray-50 text-gray-800 font-sans antialiased min-h-screen flex flex-col"
    x-data="<?php echo isset($store_data) ? "store(" . htmlspecialchars($store_data, ENT_QUOTES, 'UTF-8') . ")" : "{}"; ?>"
    x-init="init()"
>

    <!-- Header / Navbar -->
    <header class="sticky top-0 z-40 w-full backdrop-blur-md bg-white/70 border-b border-white/50 transition-colors duration-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <a href="/" class="text-2xl font-black tracking-tight text-gray-900 hover:text-primary-600 transition-colors">
                        فروشگاه مدرن
                    </a>
                </div>

                <!-- Desktop Menu -->
                <nav class="hidden lg:flex items-center space-x-6 space-x-reverse">
                    <a href="/" class="group flex items-center space-x-2 space-x-reverse px-4 py-2 rounded-md text-sm font-bold text-gray-600 hover:text-primary-600 hover:bg-primary-50 transition-all duration-200">
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-primary-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                        <span>خانه</span>
                    </a>
                    <a href="/#products" class="group flex items-center space-x-2 space-x-reverse px-4 py-2 rounded-md text-sm font-bold text-gray-600 hover:text-primary-600 hover:bg-primary-50 transition-all duration-200">
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-primary-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                        <span>محصولات</span>
                    </a>
                    <a href="/blog" class="group flex items-center space-x-2 space-x-reverse px-4 py-2 rounded-md text-sm font-bold text-gray-600 hover:text-primary-600 hover:bg-primary-50 transition-all duration-200">
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-primary-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" /></svg>
                        <span>وبلاگ</span>
                    </a>
                    <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                        <button class="group flex items-center space-x-1 space-x-reverse px-4 py-2 rounded-md text-sm font-bold text-gray-600 hover:text-primary-600 hover:bg-primary-50 transition-all duration-200">
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-primary-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <span>بیشتر</span>
                            <svg class="w-4 h-4 text-gray-400 group-hover:text-primary-500 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </button>
                        <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-2" class="absolute top-full right-0 w-48 py-2 mt-1 bg-white rounded-md shadow-xl border border-gray-100 z-50">
                            <a href="/page/about-us" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary-600">
                                <svg class="w-4 h-4 ml-2 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                                درباره ما
                            </a>
                            <a href="/page/contact-us" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary-600">
                                <svg class="w-4 h-4 ml-2 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                                تماس با ما
                            </a>
                            <a href="/page/faq" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary-600">
                                <svg class="w-4 h-4 ml-2 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                سوالات متداول
                            </a>
                        </div>
                    </div>
                </nav>

                <!-- Actions -->
                <div class="hidden lg:flex items-center gap-3">
                    <!-- Search Button (Mock) -->
                    <button class="p-2.5 rounded-md text-gray-500 hover:bg-gray-100 hover:text-primary-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    </button>

                    <!-- Theme Toggle -->
                    <button @click="toggleTheme()" class="p-2.5 rounded-md text-gray-500 hover:bg-gray-100 hover:text-primary-600 transition-colors">
                        <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                        <svg x-show="darkMode" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </button>

                    <!-- Divider -->
                    <div class="h-6 w-px bg-gray-200 mx-1"></div>

                    <template x-if="$store.auth.check()">
                        <div class="flex items-center gap-2">
                            <a href="/dashboard/orders" class="flex items-center space-x-2 space-x-reverse bg-primary-50 text-primary-700 hover:bg-primary-100 px-4 py-2.5 rounded-md text-sm font-bold transition-colors">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                <span x-text="$store.auth.user?.name || 'داشبورد'"></span>
                            </a>
                            <a href="/logout" @click.prevent="$store.auth.logout()" class="p-2.5 rounded-md text-red-500 hover:bg-red-50 transition-colors" title="خروج">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                            </a>
                        </div>
                    </template>

                    <template x-if="!$store.auth.check()">
                        <button @click.prevent="$dispatch('open-auth-modal')" class="flex items-center space-x-2 space-x-reverse bg-primary-600 text-white hover:bg-primary-700 px-5 py-2.5 rounded-md text-sm font-bold shadow-lg shadow-primary-500/30 transition-all transform hover:-translate-y-0.5">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" /></svg>
                            <span>ورود / ثبت‌نام</span>
                        </button>
                    </template>
                </div>

                <!-- Mobile Menu Button -->
                <div class="lg:hidden flex items-center gap-2">
                    <button @click="toggleTheme()" class="text-gray-400 hover:text-gray-600 p-2 rounded-full">
                         <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                         <svg x-show="darkMode" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </button>
                    <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="text-gray-500 hover:text-gray-700 focus:outline-none p-2 rounded-md">
                        <span class="sr-only">باز کردن منو</span>
                        <!-- Icon Menu -->
                        <svg x-show="!mobileMenuOpen" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <!-- Icon Close -->
                        <svg x-show="mobileMenuOpen" x-cloak class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu (Dropdown) -->
        <div x-show="mobileMenuOpen" x-cloak class="md:hidden border-t border-gray-100 bg-white/95 backdrop-blur-lg absolute w-full left-0 z-50 shadow-xl rounded-b-3xl">
            <div class="px-4 pt-4 pb-8 space-y-2">
                <a href="/" class="flex items-center px-4 py-3 rounded-2xl text-base font-bold text-gray-700 hover:text-primary-600 hover:bg-primary-50 transition-colors">
                    <svg class="w-6 h-6 ml-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                    خانه
                </a>
                <a href="/#products" class="flex items-center px-4 py-3 rounded-2xl text-base font-bold text-gray-700 hover:text-primary-600 hover:bg-primary-50 transition-colors">
                    <svg class="w-6 h-6 ml-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                    محصولات
                </a>
                <a href="/blog" class="flex items-center px-4 py-3 rounded-2xl text-base font-bold text-gray-700 hover:text-primary-600 hover:bg-primary-50 transition-colors">
                    <svg class="w-6 h-6 ml-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" /></svg>
                    وبلاگ
                </a>
                <a href="/page/about-us" class="flex items-center px-4 py-3 rounded-2xl text-base font-bold text-gray-700 hover:text-primary-600 hover:bg-primary-50 transition-colors">
                    <svg class="w-6 h-6 ml-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    درباره ما
                </a>
                <a href="/page/contact-us" class="flex items-center px-4 py-3 rounded-2xl text-base font-bold text-gray-700 hover:text-primary-600 hover:bg-primary-50 transition-colors">
                    <svg class="w-6 h-6 ml-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                    تماس با ما
                </a>

                <div class="pt-4 mt-4 border-t border-gray-100">
                    <template x-if="$store.auth.check()">
                        <div>
                            <a href="/dashboard/orders" class="flex items-center justify-center w-full text-center px-4 py-3 rounded-md text-base font-bold text-gray-700 bg-gray-50 mb-3">
                                <svg class="w-5 h-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                <span x-text="'داشبورد ' + ($store.auth.user?.name || '')"></span>
                            </a>
                            <a href="/logout" @click.prevent="$store.auth.logout()" class="flex items-center justify-center w-full text-center px-4 py-3 rounded-md text-base font-bold text-red-600 bg-red-50 hover:bg-red-100">
                                <svg class="w-5 h-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                                خروج از حساب
                            </a>
                        </div>
                    </template>
                    <template x-if="!$store.auth.check()">
                        <button @click.prevent="$dispatch('open-auth-modal'); mobileMenuOpen = false" class="flex items-center justify-center w-full bg-primary-600 text-white px-4 py-3 rounded-md text-base font-bold shadow-lg shadow-primary-500/30 hover:bg-primary-700 transition-colors">
                            <svg class="w-6 h-6 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" /></svg>
                            ورود یا ثبت‌نام
                        </button>
                    </template>
                </div>
            </div>
        </div>
    </header>