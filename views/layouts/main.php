<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="<?= csrf_token() ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> - پنل مدیریت</title>
    <link href="https://cdn.jsdelivr.net/npm/kamadatepicker/dist/kamadatepicker.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .sidebar-link.active {
            background-color: #4a5568;
            color: #ffffff;
        }
        .submenu {
            display: none;
        }
    </style>
</head>
<body class="bg-gray-100 font-sans">
    <div x-data="{ sidebarOpen: false }" class="flex h-screen bg-gray-200">
        <!-- Sidebar -->
        <aside
            class="fixed inset-y-0 right-0 z-30 w-64 bg-gray-800 text-white transform transition-transform duration-300 ease-in-out md:relative md:translate-x-0"
            :class="{'translate-x-0': sidebarOpen, 'translate-x-full': !sidebarOpen}">
            <div class="p-4">
                <h1 class="text-2xl font-bold mb-8">پنل مدیریت</h1>
                <nav>
                    <ul>
                        <li>
                            <a href="<?= url('/') ?>" class="sidebar-link block py-2 px-4 rounded hover:bg-gray-700 <?= is_active('/') ? 'active' : '' ?>">داشبورد</a>
                        </li>
                        <li>
                            <a href="<?= url('/orders') ?>" class="sidebar-link block py-2 px-4 rounded hover:bg-gray-700 <?= is_active('/orders') ? 'active' : '' ?>">سفارشات</a>
                        </li>
                        <li>
                            <a href="<?= url('/categories') ?>" class="sidebar-link block py-2 px-4 rounded hover:bg-gray-700 <?= is_active('/categories') ? 'active' : '' ?>">دسته‌بندی‌ها</a>
                        </li>
                        <li>
                            <a href="<?= url('/products') ?>" class="sidebar-link block py-2 px-4 rounded hover:bg-gray-700 <?= is_active('/products') ? 'active' : '' ?>">محصولات</a>
                        </li>
                        <li>
                            <a href="<?= url('/users') ?>" class="sidebar-link block py-2 px-4 rounded hover:bg-gray-700 <?= is_active('/users') ? 'active' : '' ?>">کاربران</a>
                        </li>
                        <li>
                            <div x-data="{ open: false }">
                                <button @click="open = !open" class="w-full text-right sidebar-link flex justify-between items-center py-2 px-4 rounded hover:bg-gray-700">
                                    <span>وبلاگ</span>
                                    <svg :class="{'rotate-180': open}" class="w-4 h-4 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </button>
                                <div x-show="open" class="submenu bg-gray-700 rounded mt-1 mr-4">
                                    <a href="<?= url('/blog/posts') ?>" class="sidebar-link block py-2 px-3 hover:bg-gray-600 <?= is_active('/blog/posts') ? 'active' : '' ?>">نوشته‌ها</a>
                                    <a href="<?= url('/blog/categories') ?>" class="sidebar-link block py-2 px-3 hover:bg-gray-600 <?= is_active('/blog/categories') ? 'active' : '' ?>">دسته‌بندی‌ها</a>
                                    <a href="<?= url('/blog/tags') ?>" class="sidebar-link block py-2 px-3 hover:bg-gray-600 <?= is_active('/blog/tags') ? 'active' : '' ?>">برچسب‌ها</a>
                                </div>
                            </div>
                        </li>
                        <li>
                            <a href="<?= url('/admins') ?>" class="sidebar-link block py-2 px-4 rounded hover:bg-gray-700 <?= is_active('/admins') ? 'active' : '' ?>">مدیران</a>
                        </li>
                        <li>
                            <a href="<?= url('/custom-fields') ?>" class="sidebar-link block py-2 px-4 rounded hover:bg-gray-700 <?= is_active('/custom-fields') ? 'active' : '' ?>">پارامترهای سفارش</a>
                        </li>
                        <li>
                            <a href="<?= url('/settings') ?>" class="sidebar-link block py-2 px-4 rounded hover:bg-gray-700 <?= is_active('/settings') ? 'active' : '' ?>">تنظیمات</a>
                        </li>
                        <li>
                            <a href="<?= url('/pages') ?>" class="sidebar-link block py-2 px-4 rounded hover:bg-gray-700 <?= is_active('/pages') ? 'active' : '' ?>">مدیریت صفحات</a>
                        </li>
                        <li>
                            <a href="<?= url('/faq') ?>" class="sidebar-link block py-2 px-4 rounded hover:bg-gray-700 <?= is_active('/faq') ? 'active' : '' ?>">سوالات متداول</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <header class="bg-white shadow-md p-4 flex justify-between items-center">
                 <!-- Mobile Menu Button -->
                 <button @click.stop="sidebarOpen = !sidebarOpen" class="md:hidden text-gray-500 hover:text-gray-600 focus:outline-none focus:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                <h1 class="text-2xl font-semibold"><?= $title ?></h1>
                <div class="flex items-center">
                    <span class="hidden md:block text-gray-600 mr-4">خوش آمدید، <?= htmlspecialchars($_SESSION['admin_name'] ?? 'کاربر') ?></span>
                    <a href="<?= url('/logout') ?>" class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">
                        خروج
                    </a>
                </div>
            </header>
            <main class="flex-1 p-8 overflow-y-auto">
                <?php partial('error_message'); ?>
                <?= $content ?>
            </main>
        </div>
        <!-- Overlay -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-20 bg-black opacity-50 md:hidden" style="display: none;"></div>
    </div>
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/kamadatepicker/dist/kamadatepicker.min.js"></script>
</body>
</html>
