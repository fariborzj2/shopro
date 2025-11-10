<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ title }} - پنل مدیریت</title>
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
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-800 text-white p-4">
            <h1 class="text-2xl font-bold mb-8">پنل مدیریت</h1>
            <nav>
                <ul>
                    <li>
                        <a href="/" class="sidebar-link block py-2 px-4 rounded hover:bg-gray-700 <?= is_active('/') ? 'active' : '' ?>">داشبورد</a>
                    </li>
                    <li>
                        <a href="/orders" class="sidebar-link block py-2 px-4 rounded hover:bg-gray-700 <?= is_active('/orders') ? 'active' : '' ?>">سفارشات</a>
                    </li>
                    <li>
                        <a href="/categories" class="sidebar-link block py-2 px-4 rounded hover:bg-gray-700 <?= is_active('/categories') ? 'active' : '' ?>">دسته‌بندی‌ها</a>
                    </li>
                    <li>
                        <a href="/products" class="sidebar-link block py-2 px-4 rounded hover:bg-gray-700 <?= is_active('/products') ? 'active' : '' ?>">محصولات</a>
                    </li>
                    <li>
                        <a href="/users" class="sidebar-link block py-2 px-4 rounded hover:bg-gray-700 <?= is_active('/users') ? 'active' : '' ?>">کاربران</a>
                    </li>
                    <li>
                        <div x-data="{ open: false }">
                            <button @click="open = !open" class="w-full text-right sidebar-link flex justify-between items-center py-2 px-4 rounded hover:bg-gray-700">
                                <span>وبلاگ</span>
                                <svg :class="{'rotate-180': open}" class="w-4 h-4 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            <div x-show="open" class="submenu bg-gray-700 rounded mt-1 mr-4">
                                <a href="/blog/posts" class="sidebar-link block py-2 px-3 hover:bg-gray-600 <?= is_active('/blog/posts') ? 'active' : '' ?>">نوشته‌ها</a>
                                <a href="/blog/categories" class="sidebar-link block py-2 px-3 hover:bg-gray-600 <?= is_active('/blog/categories') ? 'active' : '' ?>">دسته‌بندی‌ها</a>
                            </div>
                        </div>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-8">
            {{ content }}
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
</body>
</html>
