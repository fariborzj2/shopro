<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ title }} - پنل مدیریت</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* A simple style for the active sidebar link */
        .sidebar-link.active {
            background-color: #4a5568; /* gray-700 */
            color: #ffffff; /* white */
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
                        <a href="/blog" class="sidebar-link block py-2 px-4 rounded hover:bg-gray-700 <?= is_active('/blog') ? 'active' : '' ?>">وبلاگ</a>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-8">
            {{ content }}
        </main>
    </div>
</body>
</html>
