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
                            50: '#f9fafb',
                            100: '#f3f4f6',
                            200: '#e5e7eb',
                            300: '#d1d5db',
                            400: '#9ca3af',
                            500: '#6b7280',
                            600: '#4b5563',
                            700: '#374151',
                            800: '#1f2937',
                            900: '#111827',
                        },
                        // Premium Trustworthy Navy Blue Palette
                        primary: {
                            50: '#eff4ff',
                            100: '#dbe6fe',
                            200: '#bfd3fe',
                            300: '#93bbfd',
                            400: '#609afa',
                            500: '#3b82f6', // Standard Blue for familiarity
                            600: '#2563eb', // Vibrant Action Blue
                            700: '#1d4ed8',
                            800: '#1e40af', // Deep Navy
                            900: '#1e3a8a', // Darkest Navy
                        },
                        accent: {
                            400: '#34d399', // Soft Emerald
                            500: '#10b981', // Action Green
                            600: '#059669',
                        }
                    },
                    fontFamily: {
                        sans: ['Estedad', 'ui-sans-serif', 'system-ui', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'Helvetica Neue', 'Arial', 'sans-serif'],
                    },
                    boxShadow: {
                        'soft': '0 4px 20px -2px rgba(0, 0, 0, 0.05)',
                        'card': '0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.025)',
                        'floating': '0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)',
                        'glass': '0 8px 32px 0 rgba(31, 38, 135, 0.07)',
                    },
                    borderRadius: {
                        'xl': '1rem',
                        '2xl': '1.25rem',
                        '3xl': '1.5rem',
                    },
                    backdropBlur: {
                        'xs': '2px',
                    }
                }
            }
        }
    </script>

    <style type="text/tailwindcss">
        @layer components {
            .container { @apply max-w-7xl mx-auto px-4 sm:px-6 lg:px-8; }
            .glass-panel { @apply bg-white/80 backdrop-blur-xl border border-white/40 shadow-card rounded-2xl; }

            /* Buttons */
            .btn { @apply inline-flex items-center justify-center px-6 py-3 rounded-xl font-bold transition-all duration-300 transform active:scale-95 text-sm md:text-base; }
            .btn-primary { @apply bg-primary-600 text-white hover:bg-primary-700 shadow-lg shadow-primary-600/20 hover:shadow-primary-600/30 hover:-translate-y-0.5; }
            .btn-secondary { @apply bg-white text-gray-700 border border-gray-200 hover:bg-gray-50 hover:border-gray-300 shadow-sm hover:shadow-md; }
            .btn-ghost { @apply bg-transparent text-gray-600 hover:bg-gray-50 hover:text-primary-600; }
            .btn-icon { @apply p-2.5 rounded-full text-gray-500 hover:bg-gray-100 hover:text-primary-600 transition-colors; }

            /* Forms */
            .form-input { @apply block w-full rounded-xl border-gray-200 shadow-sm focus:border-primary-500 focus:ring-primary-500 bg-gray-50/50 py-3 px-4 transition-all duration-200 hover:bg-white focus:bg-white; }
            .form-label { @apply block text-sm font-bold text-gray-700 mb-2; }

            /* Typography */
            .page-title { @apply text-3xl md:text-4xl font-black text-gray-900 mb-8 tracking-tight; }
            .section-title { @apply text-2xl md:text-3xl font-extrabold text-gray-900 mb-4; }
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
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
    <header
        class="sticky top-0 z-40 w-full transition-all duration-300"
        :class="window.scrollY > 10 ? 'bg-white/90 backdrop-blur-md shadow-sm border-b border-gray-100/50' : 'bg-transparent border-transparent'"
        x-data="{ scrolled: false }"
        @scroll.window="scrolled = (window.pageYOffset > 10)"
    >
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center gap-3">
                    <a href="/" class="group flex items-center gap-2">
                         <div class="w-10 h-10 bg-primary-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-primary-500/30 group-hover:scale-105 transition-transform duration-300">
                             <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                         </div>
                        <span class="text-2xl font-black tracking-tight text-gray-900 group-hover:text-primary-600 transition-colors">
                            فروشگاه مدرن
                        </span>
                    </a>
                </div>

                <!-- Desktop Menu -->
                <nav class="hidden lg:flex items-center space-x-2 space-x-reverse bg-white/50 backdrop-blur-sm px-4 py-2 rounded-full border border-gray-100/50 shadow-sm">
                    <a href="/" class="px-5 py-2.5 rounded-full text-sm font-bold text-gray-700 hover:text-primary-600 hover:bg-white transition-all duration-200 <?php echo ($_SERVER['REQUEST_URI'] === '/' || $_SERVER['REQUEST_URI'] === '/index.php') ? 'text-primary-600 bg-white shadow-sm' : ''; ?>">
                        خانه
                    </a>
                    <a href="/#products" class="px-5 py-2.5 rounded-full text-sm font-bold text-gray-700 hover:text-primary-600 hover:bg-white transition-all duration-200">
                        محصولات
                    </a>
                    <a href="/blog" class="px-5 py-2.5 rounded-full text-sm font-bold text-gray-700 hover:text-primary-600 hover:bg-white transition-all duration-200 <?php echo (strpos($_SERVER['REQUEST_URI'], '/blog') !== false) ? 'text-primary-600 bg-white shadow-sm' : ''; ?>">
                        وبلاگ
                    </a>
                    <div class="relative group" x-data="{ open: false }">
                        <button class="flex items-center gap-1 px-5 py-2.5 rounded-full text-sm font-bold text-gray-700 hover:text-primary-600 hover:bg-white transition-all duration-200">
                            <span>بیشتر</span>
                            <svg class="w-4 h-4 transition-transform duration-200 group-hover:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </button>
                        <div class="absolute top-full right-0 mt-2 w-56 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform origin-top-right z-50 pt-2">
                             <div class="bg-white rounded-2xl shadow-floating border border-gray-100 overflow-hidden py-1">
                                <a href="/page/about-us" class="flex items-center px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-primary-600">
                                    <span class="w-8 h-8 rounded-lg bg-blue-50 text-blue-500 flex items-center justify-center ml-3">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    </span>
                                    درباره ما
                                </a>
                                <a href="/page/contact-us" class="flex items-center px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-primary-600">
                                    <span class="w-8 h-8 rounded-lg bg-green-50 text-green-500 flex items-center justify-center ml-3">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                                    </span>
                                    تماس با ما
                                </a>
                                <a href="/page/faq" class="flex items-center px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-primary-600">
                                    <span class="w-8 h-8 rounded-lg bg-yellow-50 text-yellow-500 flex items-center justify-center ml-3">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    </span>
                                    سوالات متداول
                                </a>
                             </div>
                        </div>
                    </div>
                </nav>

                <!-- Actions -->
                <div class="hidden lg:flex items-center gap-3">
                    <!-- Search Button -->
                    <button class="btn-icon">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    </button>

                    <!-- Theme Toggle -->
                    <button @click="toggleTheme()" class="btn-icon">
                        <svg x-show="!darkMode" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                        <svg x-show="darkMode" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </button>

                    <!-- Auth State -->
                    <template x-if="$store.auth.check()">
                        <div class="relative group ml-2" x-data="{ open: false }">
                            <button class="flex items-center space-x-2 space-x-reverse pl-2 pr-1 py-1 rounded-full border border-gray-200 bg-white hover:border-primary-300 hover:shadow-md transition-all duration-200">
                                <div class="w-8 h-8 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center font-bold text-sm">
                                    <span x-text="$store.auth.user?.name.charAt(0)"></span>
                                </div>
                                <span class="text-sm font-bold text-gray-700" x-text="$store.auth.user?.name"></span>
                                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                            </button>
                             <div class="absolute top-full left-0 mt-2 w-48 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform origin-top-left z-50 pt-2">
                                <div class="bg-white rounded-2xl shadow-floating border border-gray-100 overflow-hidden py-1">
                                    <a href="/dashboard/orders" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary-600">
                                        داشبورد من
                                    </a>
                                    <a href="/logout" @click.prevent="$store.auth.logout()" class="block px-4 py-2.5 text-sm text-red-600 hover:bg-red-50">
                                        خروج از حساب
                                    </a>
                                </div>
                            </div>
                        </div>
                    </template>

                    <template x-if="!$store.auth.check()">
                        <button @click.prevent="$dispatch('open-auth-modal')" class="btn btn-primary ml-2">
                            <span>ورود / ثبت‌نام</span>
                            <svg class="w-5 h-5 mr-2 -ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" /></svg>
                        </button>
                    </template>
                </div>

                <!-- Mobile Menu Button -->
                <div class="lg:hidden flex items-center gap-2">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="text-gray-700 hover:text-primary-600 p-2 rounded-xl hover:bg-gray-100 transition-colors">
                        <span class="sr-only">باز کردن منو</span>
                        <svg x-show="!mobileMenuOpen" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg x-show="mobileMenuOpen" x-cloak class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu (Drawer style) -->
        <div
            x-show="mobileMenuOpen"
            x-cloak
            class="md:hidden fixed inset-x-0 top-[80px] bottom-0 z-50 bg-white/95 backdrop-blur-xl border-t border-gray-100 overflow-y-auto"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-4"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-4"
        >
            <div class="px-6 py-6 space-y-2">
                <a href="/" class="flex items-center px-4 py-4 rounded-2xl text-base font-bold text-gray-800 hover:text-primary-600 hover:bg-gray-50 transition-colors">
                    <svg class="w-6 h-6 ml-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                    خانه
                </a>
                <a href="/#products" class="flex items-center px-4 py-4 rounded-2xl text-base font-bold text-gray-800 hover:text-primary-600 hover:bg-gray-50 transition-colors">
                    <svg class="w-6 h-6 ml-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                    محصولات
                </a>
                <a href="/blog" class="flex items-center px-4 py-4 rounded-2xl text-base font-bold text-gray-800 hover:text-primary-600 hover:bg-gray-50 transition-colors">
                    <svg class="w-6 h-6 ml-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" /></svg>
                    وبلاگ
                </a>

                <div class="h-px bg-gray-100 my-4"></div>

                <a href="/page/about-us" class="flex items-center px-4 py-3 text-sm font-medium text-gray-600 hover:text-primary-600 hover:bg-gray-50 rounded-xl transition-colors">
                    درباره ما
                </a>
                <a href="/page/contact-us" class="flex items-center px-4 py-3 text-sm font-medium text-gray-600 hover:text-primary-600 hover:bg-gray-50 rounded-xl transition-colors">
                    تماس با ما
                </a>
                <a href="/page/faq" class="flex items-center px-4 py-3 text-sm font-medium text-gray-600 hover:text-primary-600 hover:bg-gray-50 rounded-xl transition-colors">
                    سوالات متداول
                </a>

                <div class="pt-6 mt-6 border-t border-gray-100">
                    <template x-if="$store.auth.check()">
                        <div class="space-y-3">
                            <div class="flex items-center gap-3 px-4 mb-4">
                                <div class="w-10 h-10 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center font-bold text-lg">
                                    <span x-text="$store.auth.user?.name.charAt(0)"></span>
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900" x-text="$store.auth.user?.name"></p>
                                    <p class="text-xs text-gray-500" x-text="$store.auth.user?.mobile"></p>
                                </div>
                            </div>
                            <a href="/dashboard/orders" class="flex items-center justify-center w-full px-4 py-3 rounded-xl font-bold text-primary-700 bg-primary-50">
                                داشبورد من
                            </a>
                            <a href="/logout" @click.prevent="$store.auth.logout()" class="flex items-center justify-center w-full px-4 py-3 rounded-xl font-bold text-red-600 bg-red-50 hover:bg-red-100">
                                خروج از حساب
                            </a>
                        </div>
                    </template>
                    <template x-if="!$store.auth.check()">
                        <button @click.prevent="$dispatch('open-auth-modal'); mobileMenuOpen = false" class="flex items-center justify-center w-full bg-primary-600 text-white px-4 py-4 rounded-xl text-base font-bold shadow-lg shadow-primary-500/30 hover:bg-primary-700 transition-colors">
                            ورود یا ثبت‌نام در فروشگاه
                        </button>
                    </template>
                </div>
            </div>
        </div>
    </header>
