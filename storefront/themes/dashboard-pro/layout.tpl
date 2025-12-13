<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'داشبورد کاربری'; ?></title>

    <!-- Fonts -->
    <link href="/fonts/estedad/style.css" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3b82f6',
                        secondary: '#64748b',
                    },
                    fontFamily: {
                        sans: ['Estedad', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>

    <!-- Custom CSS -->
    <link href="/storefront/themes/dashboard-pro/assets/css/dashboard.css" rel="stylesheet">

    <style type="text/tailwindcss">
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="dashboard-wrapper text-slate-800 antialiased" x-data="{ sidebarOpen: false }">

    <!-- Mobile Header -->
    <div class="lg:hidden flex items-center justify-between p-4 bg-white border-b border-gray-200">
        <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-md text-gray-600 hover:bg-gray-100">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
        </button>
        <span class="font-bold text-lg">داشبورد کاربری</span>
        <div class="w-10"></div> <!-- Spacer -->
    </div>

    <div class="flex h-screen overflow-hidden">

        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0' : 'translate-x-full'" class="fixed inset-y-0 right-0 z-50 w-64 bg-white border-l border-gray-200 transition-transform duration-300 ease-in-out lg:relative lg:translate-x-0">
            <div class="flex items-center justify-center h-16 border-b border-gray-200">
                <a href="/" class="text-xl font-bold text-primary">فروشگاه من</a>
            </div>

            <div class="p-4">
                <div class="flex items-center gap-3 mb-8 px-2">
                    <div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center text-xl font-bold text-gray-500 overflow-hidden">
                        <?php if(!empty($_SESSION['user_avatar'])): ?>
                            <img src="<?php echo $_SESSION['user_avatar']; ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <?php echo mb_substr($_SESSION['user_name'] ?? 'U', 0, 1); ?>
                        <?php endif; ?>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-bold text-sm"><?php echo $_SESSION['user_name'] ?? 'کاربر گرامی'; ?></span>
                        <span class="text-xs text-gray-500"><?php echo $_SESSION['user_mobile'] ?? ''; ?></span>
                    </div>
                </div>

                <nav class="space-y-1">
                    <a href="/dashboard" class="sidebar-link <?php echo ($activePage ?? '') === 'home' ? 'active' : ''; ?>">
                        <svg class="w-5 h-5 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        پیشخوان
                    </a>
                    <a href="/dashboard/orders" class="sidebar-link <?php echo ($activePage ?? '') === 'orders' ? 'active' : ''; ?>">
                        <svg class="w-5 h-5 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        سفارش‌های من
                    </a>
                    <a href="/dashboard/messages" class="sidebar-link <?php echo ($activePage ?? '') === 'messages' ? 'active' : ''; ?>">
                        <svg class="w-5 h-5 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        پیام‌ها
                        <?php if (!empty($unreadMessages)): ?>
                            <span class="mr-auto bg-red-500 text-white text-xs px-2 py-0.5 rounded-full"><?php echo $unreadMessages; ?></span>
                        <?php endif; ?>
                    </a>
                    <a href="/dashboard/profile" class="sidebar-link <?php echo ($activePage ?? '') === 'profile' ? 'active' : ''; ?>">
                        <svg class="w-5 h-5 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        پروفایل و امنیت
                    </a>
                    <a href="/dashboard/logs" class="sidebar-link <?php echo ($activePage ?? '') === 'logs' ? 'active' : ''; ?>">
                        <svg class="w-5 h-5 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        گزارش ورودها
                    </a>
                </nav>
            </div>

            <div class="absolute bottom-0 w-full p-4 border-t border-gray-200">
                <a href="/logout" class="flex items-center px-4 py-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                    <svg class="w-5 h-5 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    خروج از حساب
                </a>
            </div>
        </aside>

        <!-- Overlay -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-black bg-opacity-50 lg:hidden" x-transition.opacity></div>

        <!-- Main Content -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-slate-50 p-6 lg:p-10">
            <!-- Header for Desktop -->
            <header class="hidden lg:flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-2xl font-bold text-slate-800"><?php echo $pageTitle ?? ''; ?></h1>
                    <?php if(isset($breadcrumbs)): ?>
                    <div class="text-sm text-gray-500 mt-1">
                        <?php echo implode(' / ', $breadcrumbs); ?>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="flex items-center gap-4">
                     <!-- Notifications / Actions can go here -->
                </div>
            </header>

            <!-- Alerts -->
            <?php if (isset($_GET['success_msg'])): ?>
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline"><?php echo htmlspecialchars($_GET['success_msg']); ?></span>
            </div>
            <?php endif; ?>
            <?php if (isset($_GET['error_msg'])): ?>
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline"><?php echo htmlspecialchars($_GET['error_msg']); ?></span>
            </div>
            <?php endif; ?>

            <!-- Page Content -->
            <?php echo $content; ?>

        </main>
    </div>

</body>
</html>
