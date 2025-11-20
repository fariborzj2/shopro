<div :class="sidebarOpen ? 'block' : 'hidden'" @click="sidebarOpen = false" class="fixed z-20 inset-0 bg-black opacity-50 transition-opacity lg:hidden"></div>

<div :class="sidebarOpen ? 'translate-x-0 ease-out' : '-translate-x-full ease-in'" class="fixed z-30 inset-y-0 left-0 w-64 transition duration-300 transform bg-gray-900 overflow-y-auto lg:translate-x-0 lg:static lg:inset-0">
    <div class="flex items-center justify-center mt-8">
        <div class="flex items-center">
            <span class="text-white text-2xl mx-2 font-semibold">پنل مدیریت</span>
        </div>
    </div>

    <?php
    // Helper to check permissions for sidebar display
    // Using a closure or ensuring Admin is imported if not already in context.
    // Since this is a partial, we assume the global scope or manual import.
    use App\Models\Admin;

    // Fetch current admin object once
    $current_admin = null;
    if (isset($_SESSION['admin_id'])) {
        $current_admin = Admin::find($_SESSION['admin_id']);
    }

    $can = function($permission) use ($current_admin) {
        if (!$current_admin) return false;
        return Admin::hasPermission($current_admin, $permission);
    };
    ?>
    <nav class="mt-10">
        <?php if ($can('dashboard')): ?>
        <a class="flex items-center mt-4 py-2 px-6 <?php echo is_active('/dashboard') ? 'bg-gray-700 bg-opacity-25 text-gray-100' : 'text-gray-500 hover:bg-gray-700 hover:bg-opacity-25 hover:text-gray-100'; ?>" href="<?php echo url('/dashboard'); ?>">
            <span class="mx-3">داشبورد</span>
        </a>
        <?php endif; ?>

        <?php if ($can('users')): ?>
        <a class="flex items-center mt-4 py-2 px-6 <?php echo is_active('/users') ? 'bg-gray-700 bg-opacity-25 text-gray-100' : 'text-gray-500 hover:bg-gray-700 hover:bg-opacity-25 hover:text-gray-100'; ?>" href="<?php echo url('/users'); ?>">
            <span class="mx-3">کاربران</span>
        </a>
        <?php endif; ?>

        <?php if ($can('categories')): ?>
        <a class="flex items-center mt-4 py-2 px-6 <?php echo is_active('/categories') ? 'bg-gray-700 bg-opacity-25 text-gray-100' : 'text-gray-500 hover:bg-gray-700 hover:bg-opacity-25 hover:text-gray-100'; ?>" href="<?php echo url('/categories'); ?>">
            <span class="mx-3">دسته‌بندی‌ها</span>
        </a>
        <?php endif; ?>

        <?php if ($can('products')): ?>
        <a class="flex items-center mt-4 py-2 px-6 <?php echo is_active('/products') ? 'bg-gray-700 bg-opacity-25 text-gray-100' : 'text-gray-500 hover:bg-gray-700 hover:bg-opacity-25 hover:text-gray-100'; ?>" href="<?php echo url('/products'); ?>">
            <span class="mx-3">محصولات</span>
        </a>
        <?php endif; ?>

        <?php if ($can('blog')): ?>
        <a class="flex items-center mt-4 py-2 px-6 <?php echo is_active('/blog') ? 'bg-gray-700 bg-opacity-25 text-gray-100' : 'text-gray-500 hover:bg-gray-700 hover:bg-opacity-25 hover:text-gray-100'; ?>" href="<?php echo url('/admin/blog/posts'); ?>">
            <span class="mx-3">مدیریت بلاگ</span>
        </a>
        <?php endif; ?>

        <?php if ($can('media')): ?>
        <a class="flex items-center mt-4 py-2 px-6 <?php echo is_active('/media') ? 'bg-gray-700 bg-opacity-25 text-gray-100' : 'text-gray-500 hover:bg-gray-700 hover:bg-opacity-25 hover:text-gray-100'; ?>" href="<?php echo url('/media'); ?>">
            <span class="mx-3">کتابخانه رسانه</span>
        </a>
        <?php endif; ?>

        <?php if ($can('custom_fields')): ?>
        <a class="flex items-center mt-4 py-2 px-6 <?php echo is_active('/custom-fields') ? 'bg-gray-700 bg-opacity-25 text-gray-100' : 'text-gray-500 hover:bg-gray-700 hover:bg-opacity-25 hover:text-gray-100'; ?>" href="<?php echo url('/custom-fields'); ?>">
            <span class="mx-3">پارامترها</span>
        </a>
        <?php endif; ?>

        <?php if ($can('orders')): ?>
        <a class="flex items-center mt-4 py-2 px-6 <?php echo is_active('/orders') ? 'bg-gray-700 bg-opacity-25 text-gray-100' : 'text-gray-500 hover:bg-gray-700 hover:bg-opacity-25 hover:text-gray-100'; ?>" href="<?php echo url('/orders'); ?>">
            <span class="mx-3">سفارشات</span>
        </a>
        <?php endif; ?>

        <?php if ($can('pages')): ?>
        <a class="flex items-center mt-4 py-2 px-6 <?php echo is_active('/pages') ? 'bg-gray-700 bg-opacity-25 text-gray-100' : 'text-gray-500 hover:bg-gray-700 hover:bg-opacity-25 hover:text-gray-100'; ?>" href="<?php echo url('/admin/pages'); ?>">
            <span class="mx-3">مدیریت صفحات</span>
        </a>
        <?php endif; ?>

        <?php if ($can('faq')): ?>
        <a class="flex items-center mt-4 py-2 px-6 <?php echo is_active('/faq') ? 'bg-gray-700 bg-opacity-25 text-gray-100' : 'text-gray-500 hover:bg-gray-700 hover:bg-opacity-25 hover:text-gray-100'; ?>" href="<?php echo url('/admin/faq'); ?>">
            <span class="mx-3">سوالات متداول</span>
        </a>
        <?php endif; ?>

        <?php if ($can('reviews')): ?>
        <a class="flex items-center mt-4 py-2 px-6 <?php echo is_active('/reviews') ? 'bg-gray-700 bg-opacity-25 text-gray-100' : 'text-gray-500 hover:bg-gray-700 hover:bg-opacity-25 hover:text-gray-100'; ?>" href="<?php echo url('/admin/reviews'); ?>">
            <span class="mx-3">نظرات</span>
        </a>
        <?php endif; ?>

        <?php if ($can('settings')): ?>
        <a class="flex items-center mt-4 py-2 px-6 <?php echo is_active('/settings') ? 'bg-gray-700 bg-opacity-25 text-gray-100' : 'text-gray-500 hover:bg-gray-700 hover:bg-opacity-25 hover:text-gray-100'; ?>" href="<?php echo url('/settings'); ?>">
            <span class="mx-3">تنظیمات</span>
        </a>
        <?php endif; ?>

        <?php if ($current_admin && Admin::isSuperAdmin($current_admin)): ?>
        <a class="flex items-center mt-4 py-2 px-6 <?php echo is_active('/admins') ? 'bg-gray-700 bg-opacity-25 text-gray-100' : 'text-gray-500 hover:bg-gray-700 hover:bg-opacity-25 hover:text-gray-100'; ?>" href="<?php echo url('/admin/admins'); ?>">
            <span class="mx-3 text-purple-400">مدیریت مدیران</span>
        </a>
        <?php endif; ?>
    </nav>
</div>
