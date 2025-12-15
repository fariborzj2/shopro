<?php
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

// Define Menu Items
$menuItems = [
    [
        'label' => 'داشبورد',
        'url' => '/dashboard',
        'icon' => 'dashboard',
        'permission' => 'dashboard'
    ],
    [
        'label' => 'مدیریت بلاگ',
        'icon' => 'blog',
        'permission' => 'blog',
        'children' => [
            [
                'label' => 'نوشته‌ها',
                'url' => '/blog/posts',
            ],
            [
                'label' => 'دسته‌بندی بلاگ',
                'url' => '/blog/categories',
            ],
            [
                'label' => 'تگ‌های مطالب',
                'url' => '/blog/tags',
            ],
            [
                'label' => 'مدیریت نظرات',
                'url' => '/blog/comments',
            ],
        ]
    ],
    [
        'label' => 'نظرات',
        'url' => '/reviews',
        'icon' => 'message',
        'permission' => 'reviews'
    ],
    [
        'label' => 'کتابخانه رسانه',
        'url' => '/media',
        'icon' => 'media',
        'permission' => 'media'
    ],
    [
        'label' => 'مدیریت صفحات',
        'url' => '/pages',
        'icon' => 'pages',
        'permission' => 'pages'
    ],
    [
        'label' => 'سوالات متداول',
        'url' => '/faq',
        'icon' => 'faq',
        'permission' => 'faq'
    ],
    [
        'label' => 'تنظیمات',
        'url' => '/settings',
        'icon' => 'settings',
        'permission' => 'settings'
    ],
];

// Apply filters to menu items to allow plugins to inject their own
$menuItems = \App\Core\Plugin\Filter::apply('admin_menu_items', $menuItems);

// Super Admin Items
$superAdminItems = [
    [
        'label' => 'مدیریت مدیران',
        'url' => '/admins',
        'icon' => 'user',
        'permission' => 'super_admin'
    ],
    [
        'label' => 'مدیریت پلاگین‌ها',
        'url' => '/plugins',
        'icon' => 'settings', // Reusing settings icon or a new 'plugin' icon if available
        'permission' => 'super_admin'
    ],
];

?>

<!-- Sidebar Overlay -->
<div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-gray-900/50 backdrop-blur-sm lg:hidden" x-transition.opacity></div>

<!-- Sidebar Component -->
<aside :class="sidebarOpen ? 'translate-x-0' : 'translate-x-full lg:translate-x-0'"
       class="fixed right-0 top-0 z-50 w-72 h-full overflow-y-auto transition-transform duration-300 ease-in-out
              bg-white dark:bg-gray-800 border-l border-gray-200 dark:border-gray-700 lg:static shadow-2xl lg:shadow-none">

    <!-- Logo Area -->
    <div class="flex items-center justify-between h-16 px-6 border-b border-gray-100 dark:border-gray-700">
        <span class="text-xl font-extrabold text-primary-600 dark:text-primary-400 tracking-tight">پنل مدیریت</span>
        <button @click="sidebarOpen = false" class="lg:hidden text-gray-500 hover:text-gray-700 dark:text-gray-400">
             <?php partial('icon', ['name' => 'close', 'class' => 'w-6 h-6']); ?>
        </button>
    </div>

    <!-- Navigation -->
    <nav class="p-4 space-y-1.5">
        <?php foreach ($menuItems as $item): ?>
            <?php if ($can($item['permission'])): ?>
                <?php if (isset($item['children'])): ?>
                    <div x-data="{ open: <?php echo (strpos($_SERVER['REQUEST_URI'], '/admin/blog') !== false) ? 'true' : 'false'; ?> }">
                        <button @click="open = !open" type="button"
                                class="group w-full flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700/50 dark:hover:text-gray-100 justify-between">
                            <div class="flex items-center">
                                <?php partial('icon', ['name' => $item['icon'], 'class' => 'ml-3 flex-shrink-0 h-5 w-5 transition-colors text-gray-400 group-hover:text-gray-500 dark:text-gray-500 dark:group-hover:text-gray-300']); ?>
                                <span><?php echo $item['label']; ?></span>
                            </div>
                            <svg :class="{ 'rotate-180': open }" class="w-4 h-4 transition-transform transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="open" class="mt-1 space-y-1 pr-8" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0">
                            <?php foreach ($item['children'] as $child): ?>
                                <?php
                                    $active = is_active($child['url']);
                                    $activeClasses = "text-primary-600 dark:text-primary-400 font-semibold";
                                    $inactiveClasses = "text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200";
                                ?>
                                <a href="<?php echo url($child['url']); ?>" class="block py-2 px-3 text-sm rounded-lg transition-colors <?php echo $active ? $activeClasses : $inactiveClasses; ?>">
                                    <?php echo $child['label']; ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php else: ?>
                    <?php
                        $active = is_active($item['url']);
                        // Modern active state: soft background, primary color text
                        $baseClasses = "group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200";
                        $activeClasses = "bg-primary-50 text-primary-700 dark:bg-primary-900/20 dark:text-primary-300 shadow-sm";
                        $inactiveClasses = "text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700/50 dark:hover:text-gray-100";
                    ?>
                    <a href="<?php echo url($item['url']); ?>" class="<?php echo $baseClasses . ' ' . ($active ? $activeClasses : $inactiveClasses); ?>">
                        <?php partial('icon', ['name' => $item['icon'], 'class' => 'ml-3 flex-shrink-0 h-5 w-5 transition-colors ' . ($active ? 'text-primary-600 dark:text-primary-400' : 'text-gray-400 group-hover:text-gray-500 dark:text-gray-500 dark:group-hover:text-gray-300')]); ?>
                        <span><?php echo $item['label']; ?></span>
                        <?php if($active): ?>
                            <!-- Active Indicator -->
                            <span class="mr-auto w-1.5 h-1.5 rounded-full bg-primary-600 dark:bg-primary-400"></span>
                        <?php endif; ?>
                    </a>
                <?php endif; ?>
            <?php endif; ?>
        <?php endforeach; ?>

        <?php if ($current_admin && Admin::isSuperAdmin($current_admin)): ?>
            <div class="pt-6 pb-2">
                <div class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">سیستم</div>
            </div>

            <?php foreach ($superAdminItems as $item): ?>
                 <?php
                    $active = is_active($item['url']);
                    $baseClasses = "group flex items-center px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200";
                    $activeClasses = "bg-purple-50 text-purple-700 dark:bg-purple-900/20 dark:text-purple-300 shadow-sm";
                    $inactiveClasses = "text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700/50 dark:hover:text-gray-100";
                ?>
                <a href="<?php echo url($item['url']); ?>" class="<?php echo $baseClasses . ' ' . ($active ? $activeClasses : $inactiveClasses); ?>">
                    <?php partial('icon', ['name' => $item['icon'], 'class' => 'ml-3 flex-shrink-0 h-5 w-5 ' . ($active ? 'text-purple-600 dark:text-purple-400' : 'text-gray-400 group-hover:text-gray-500 dark:text-gray-500 dark:group-hover:text-gray-300')]); ?>
                    <span><?php echo $item['label']; ?></span>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </nav>
</aside>
