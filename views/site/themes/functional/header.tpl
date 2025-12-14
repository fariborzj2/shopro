<!DOCTYPE html>
<html lang="fa" dir="rtl" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title ?? 'فروشگاه'); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($meta_description ?? ''); ?>">

    <!-- Functional UI Kit Style (via Tailwind) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            200: '#c7d2fe',
                            300: '#a5b4fc',
                            400: '#818cf8',
                            500: '#6366f1', // Indigo-500
                            600: '#4f46e5',
                            700: '#4338ca',
                            800: '#3730a3',
                            900: '#312e81',
                        },
                        slate: {
                            850: '#1e293b', // Custom dark
                        }
                    },
                    fontFamily: {
                        sans: ['Estedad', 'Inter', 'ui-sans-serif', 'system-ui', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'Helvetica Neue', 'Arial', 'sans-serif'],
                    },
                    container: {
                        center: true,
                        padding: '1rem',
                        screens: {
                            sm: '640px',
                            md: '768px',
                            lg: '1024px',
                            xl: '1280px',
                            '2xl': '1280px', // Limit max width
                        }
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="/fonts/estedad/style.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        [x-cloak] { display: none !important; }

        /* Functional UI tweaks */
        body {
            font-family: 'Estedad', 'Inter', sans-serif;
            background-color: #f8fafc; /* Slate-50 */
            color: #0f172a; /* Slate-900 */
        }
    </style>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
</head>
<body x-data="{
    mobileMenuOpen: false,
    scrolled: false,
    init() {
        window.addEventListener('scroll', () => {
            this.scrolled = window.scrollY > 20;
        });
    }
}" class="antialiased selection:bg-primary-100 selection:text-primary-900">

<!-- Navigation -->
<nav :class="scrolled ? 'bg-white/90 backdrop-blur-md shadow-sm border-b border-slate-200/50' : 'bg-transparent'"
     class="fixed top-0 w-full z-50 transition-all duration-300">
    <div class="container mx-auto px-4 h-20 flex items-center justify-between">
        <!-- Logo -->
        <a href="/" class="flex items-center gap-2 group">
            <div class="w-10 h-10 bg-primary-600 rounded-lg flex items-center justify-center text-white shadow-lg shadow-primary-500/30 group-hover:scale-105 transition-transform">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
            <span class="text-xl font-bold tracking-tight text-slate-900 group-hover:text-primary-600 transition-colors">فانکشنال شاپ</span>
        </a>

        <!-- Desktop Menu -->
        <div class="hidden md:flex items-center gap-8">
            <a href="/" class="text-sm font-medium text-slate-600 hover:text-primary-600 transition-colors">خانه</a>
            <a href="/category/all" class="text-sm font-medium text-slate-600 hover:text-primary-600 transition-colors">فروشگاه</a>
            <a href="/blog" class="text-sm font-medium text-slate-600 hover:text-primary-600 transition-colors">وبلاگ</a>
            <a href="/about" class="text-sm font-medium text-slate-600 hover:text-primary-600 transition-colors">درباره ما</a>
        </div>

        <!-- Actions -->
        <div class="hidden md:flex items-center gap-4">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="/dashboard/orders" class="text-sm font-medium text-slate-600 hover:text-primary-600">
                    پنل کاربری
                </a>
                <a href="/logout" class="text-sm font-medium text-red-500 hover:text-red-600">
                    خروج
                </a>
            <?php else: ?>
                <button @click="$dispatch('open-auth-modal')" class="text-sm font-medium text-slate-600 hover:text-primary-600">
                    ورود
                </button>
                <button @click="$dispatch('open-auth-modal')" class="bg-slate-900 hover:bg-slate-800 text-white px-5 py-2.5 rounded-lg text-sm font-medium transition-colors shadow-lg shadow-slate-900/20">
                    ثبت نام
                </button>
            <?php endif; ?>

            <!-- Cart Icon (Static for demo, can be dynamic) -->
             <button class="relative p-2 text-slate-500 hover:text-primary-600 transition-colors">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                <span class="absolute top-1 right-1 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-white"></span>
            </button>
        </div>

        <!-- Mobile Menu Button -->
        <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 text-slate-600">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                <path x-show="mobileMenuOpen" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Mobile Menu -->
    <div x-show="mobileMenuOpen" x-transition x-cloak class="md:hidden bg-white border-b border-slate-200">
        <div class="px-4 pt-2 pb-6 space-y-2">
            <a href="/" class="block px-3 py-2 text-base font-medium text-slate-700 hover:bg-slate-50 rounded-lg">خانه</a>
            <a href="/category/all" class="block px-3 py-2 text-base font-medium text-slate-700 hover:bg-slate-50 rounded-lg">فروشگاه</a>
            <a href="/blog" class="block px-3 py-2 text-base font-medium text-slate-700 hover:bg-slate-50 rounded-lg">وبلاگ</a>
            <div class="pt-4 border-t border-slate-100 flex flex-col gap-3">
                 <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="/dashboard/orders" class="w-full text-center py-2.5 text-slate-700 font-medium bg-slate-100 rounded-lg">پنل کاربری</a>
                 <?php else: ?>
                    <button @click="$dispatch('open-auth-modal')" class="w-full py-2.5 text-slate-700 font-medium bg-slate-100 rounded-lg">ورود</button>
                    <button @click="$dispatch('open-auth-modal')" class="w-full py-2.5 bg-primary-600 text-white font-medium rounded-lg">ثبت نام</button>
                 <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<!-- Main Content Wrapper -->
<main class="pt-20 min-h-screen">
