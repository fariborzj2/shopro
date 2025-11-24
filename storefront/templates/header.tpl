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
    <title><?php echo $pageTitle ?? 'فروشگاه مدرن'; ?></title>
    <meta name="csrf-token" content="<?php echo csrf_token(); ?>">
    <meta name="description" content="<?php echo $metaDescription ?? 'توضیحات پیش‌فرض سایت'; ?>">
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
            .btn { @apply inline-flex items-center justify-center px-6 py-2.5 rounded-xl font-bold transition-all duration-200 transform active:scale-95; }
            .btn-primary { @apply bg-primary-600 text-white hover:bg-primary-700 shadow-lg shadow-primary-500/30; }
            .btn-secondary { @apply bg-gray-100 text-gray-700 hover:bg-gray-200; }
            .btn-danger { @apply bg-red-50 text-red-600 hover:bg-red-100; }
            .btn-ghost { @apply bg-transparent text-gray-600 hover:bg-gray-50 hover:text-primary-600; }
            .form-input { @apply block w-full rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 bg-gray-50 py-3 px-4; }
            .form-label { @apply block text-sm font-bold text-gray-700 mb-2; }
            .page-title { @apply text-3xl font-extrabold text-gray-900 mb-8 text-center; }
        }
    </style>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
    </style>

    <?php if (isset($schema_data)): ?>
        <?php foreach ($schema_data as $schema): if($schema): ?>
            <script type="application/ld+json"><?php echo json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?></script>
        <?php endif; endforeach; ?>
    <?php endif; ?>
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased min-h-screen flex flex-col">

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
                <nav class="hidden md:flex space-x-8 space-x-reverse">
                    <a href="/" class="text-gray-600 hover:text-primary-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">خانه</a>
                    <a href="/#products" class="text-gray-600 hover:text-primary-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">محصولات</a>
                    <a href="/blog" class="text-gray-600 hover:text-primary-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">وبلاگ</a>
                    <a href="/page/about-us" class="text-gray-600 hover:text-primary-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">درباره ما</a>
                    <a href="/page/contact-us" class="text-gray-600 hover:text-primary-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">تماس با ما</a>
                </nav>

                <!-- Auth Buttons -->
                <div class="hidden md:flex items-center gap-3">
                    <!-- Theme Toggle -->
                    <button @click="toggleTheme()" class="text-gray-400 hover:text-gray-600 p-2 rounded-full">
                        <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                        <svg x-show="darkMode" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </button>

                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="/dashboard/orders" class="text-gray-700 hover:text-primary-600 font-medium text-sm transition-colors">
                            داشبورد من
                        </a>
                        <a href="/logout" class="bg-red-50 text-red-600 hover:bg-red-100 px-4 py-2 rounded-xl text-sm font-bold transition-colors">
                            خروج
                        </a>
                    <?php else: ?>
                        <button @click.prevent="$dispatch('open-auth-modal')" class="bg-primary-600 text-white hover:bg-primary-700 px-5 py-2.5 rounded-xl text-sm font-bold shadow-md shadow-primary-500/30 transition-all transform hover:-translate-y-0.5">
                            ورود / ثبت‌نام
                        </button>
                    <?php endif; ?>
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden flex items-center gap-2">
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
        <div x-show="mobileMenuOpen" x-cloak class="md:hidden border-t border-gray-100 bg-white/95 backdrop-blur-lg">
            <div class="px-4 pt-2 pb-6 space-y-1">
                <a href="/" class="block px-3 py-3 rounded-md text-base font-medium text-gray-700 hover:text-primary-600 hover:bg-gray-50">خانه</a>
                <a href="/#products" class="block px-3 py-3 rounded-md text-base font-medium text-gray-700 hover:text-primary-600 hover:bg-gray-50">محصولات</a>
                <a href="/blog" class="block px-3 py-3 rounded-md text-base font-medium text-gray-700 hover:text-primary-600 hover:bg-gray-50">وبلاگ</a>
                <a href="/page/about-us" class="block px-3 py-3 rounded-md text-base font-medium text-gray-700 hover:text-primary-600 hover:bg-gray-50">درباره ما</a>
                <a href="/page/contact-us" class="block px-3 py-3 rounded-md text-base font-medium text-gray-700 hover:text-primary-600 hover:bg-gray-50">تماس با ما</a>

                <div class="pt-4 mt-4 border-t border-gray-100">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="/dashboard/orders" class="block w-full text-center px-4 py-3 rounded-xl text-base font-bold text-gray-700 bg-gray-50 mb-3">
                            داشبورد من
                        </a>
                        <a href="/logout" class="block w-full text-center px-4 py-3 rounded-xl text-base font-bold text-red-600 bg-red-50 hover:bg-red-100">
                            خروج از حساب
                        </a>
                    <?php else: ?>
                        <button @click.prevent="$dispatch('open-auth-modal'); mobileMenuOpen = false" class="w-full bg-primary-600 text-white px-4 py-3 rounded-xl text-base font-bold shadow-md">
                            ورود یا ثبت‌نام
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>
