<div :class="sidebarOpen ? 'block' : 'hidden'" @click="sidebarOpen = false" class="fixed z-20 inset-0 bg-black opacity-50 transition-opacity lg:hidden"></div>

<div :class="sidebarOpen ? 'translate-x-0 ease-out' : '-translate-x-full ease-in'" class="fixed z-30 inset-y-0 left-0 w-64 transition duration-300 transform bg-gray-900 overflow-y-auto lg:translate-x-0 lg:static lg:inset-0">
    <div class="flex items-center justify-center mt-8">
        <div class="flex items-center">
            <span class="text-white text-2xl mx-2 font-semibold">پنل مدیریت</span>
        </div>
    </div>

    <nav class="mt-10">
        <a class="flex items-center mt-4 py-2 px-6 <?php echo is_active('/dashboard') ? 'bg-gray-700 bg-opacity-25 text-gray-100' : 'text-gray-500 hover:bg-gray-700 hover:bg-opacity-25 hover:text-gray-100'; ?>" href="<?php echo url('dashboard'); ?>">
            <span class="mx-3">داشبورد</span>
        </a>

        <a class="flex items-center mt-4 py-2 px-6 <?php echo is_active('/users') ? 'bg-gray-700 bg-opacity-25 text-gray-100' : 'text-gray-500 hover:bg-gray-700 hover:bg-opacity-25 hover:text-gray-100'; ?>" href="<?php echo url('users'); ?>">
            <span class="mx-3">کاربران</span>
        </a>

        <a class="flex items-center mt-4 py-2 px-6 <?php echo is_active('/categories') ? 'bg-gray-700 bg-opacity-25 text-gray-100' : 'text-gray-500 hover:bg-gray-700 hover:bg-opacity-25 hover:text-gray-100'; ?>" href="<?php echo url('categories'); ?>">
            <span class="mx-3">دسته‌بندی‌ها</span>
        </a>

        <a class="flex items-center mt-4 py-2 px-6 <?php echo is_active('/products') ? 'bg-gray-700 bg-opacity-25 text-gray-100' : 'text-gray-500 hover:bg-gray-700 hover:bg-opacity-25 hover:text-gray-100'; ?>" href="<?php echo url('products'); ?>">
            <span class="mx-3">محصولات</span>
        </a>

        <a class="flex items-center mt-4 py-2 px-6 <?php echo is_active('/custom-fields') ? 'bg-gray-700 bg-opacity-25 text-gray-100' : 'text-gray-500 hover:bg-gray-700 hover:bg-opacity-25 hover:text-gray-100'; ?>" href="<?php echo url('custom-fields'); ?>">
            <span class="mx-3">پارامترها</span>
        </a>

        <a class="flex items-center mt-4 py-2 px-6 <?php echo is_active('/orders') ? 'bg-gray-700 bg-opacity-25 text-gray-100' : 'text-gray-500 hover:bg-gray-700 hover:bg-opacity-25 hover:text-gray-100'; ?>" href="<?php echo url('orders'); ?>">
            <span class="mx-3">سفارشات</span>
        </a>

        <a class="flex items-center mt-4 py-2 px-6 <?php echo is_active('/settings') ? 'bg-gray-700 bg-opacity-25 text-gray-100' : 'text-gray-500 hover:bg-gray-700 hover:bg-opacity-25 hover:text-gray-100'; ?>" href="<?php echo url('settings'); ?>">
            <span class="mx-3">تنظیمات</span>
        </a>
    </nav>
</div>
