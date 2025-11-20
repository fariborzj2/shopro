<header class="flex items-center justify-between px-4 py-3 bg-white/80 dark:bg-gray-800/80 backdrop-blur-md border-b border-gray-200 dark:border-gray-700 sticky top-0 z-20 shadow-sm">

    <!-- Left Side -->
    <div class="flex items-center gap-3">
        <!-- Mobile Menu Button -->
        <button @click="sidebarOpen = true" class="p-2 -mr-2 text-gray-500 rounded-lg lg:hidden hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700">
            <?php partial('icon', ['name' => 'menu', 'class' => 'w-6 h-6']); ?>
        </button>

        <!-- Search Input -->
        <div class="hidden md:block relative group">
            <span class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                <?php partial('icon', ['name' => 'search', 'class' => 'w-5 h-5 text-gray-400 group-focus-within:text-primary-500 transition-colors']); ?>
            </span>
            <input type="text"
                   class="w-64 py-2 pr-10 pl-4 text-sm text-gray-700 bg-gray-100 border-none rounded-xl focus:ring-2 focus:ring-primary-500/50 focus:bg-white dark:bg-gray-700 dark:text-gray-200 dark:focus:bg-gray-800 transition-all"
                   placeholder="جستجو در پنل...">
        </div>
    </div>

    <!-- Right Side Actions -->
    <div class="flex items-center gap-3">

        <!-- Dark Mode Toggle -->
        <button @click="toggleTheme()" class="p-2 text-gray-500 rounded-lg hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 transition-colors" aria-label="Toggle Dark Mode">
            <span x-show="!darkMode">
                <?php partial('icon', ['name' => 'moon', 'class' => 'w-5 h-5']); ?>
            </span>
            <span x-show="darkMode" style="display: none;">
                <?php partial('icon', ['name' => 'sun', 'class' => 'w-5 h-5']); ?>
            </span>
        </button>

        <!-- Notifications -->
        <button class="p-2 text-gray-500 rounded-lg hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 relative transition-colors">
             <?php partial('icon', ['name' => 'bell', 'class' => 'w-6 h-6']); ?>
             <span class="absolute top-2 right-2 h-2 w-2 rounded-full bg-red-500 ring-2 ring-white dark:ring-gray-800"></span>
        </button>

        <!-- Profile Dropdown -->
        <div x-data="{ dropdownOpen: false }" class="relative" @click.away="dropdownOpen = false">
            <button @click="dropdownOpen = !dropdownOpen" class="flex items-center gap-3 focus:outline-none p-1 pr-3 rounded-full hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors border border-transparent hover:border-gray-200 dark:hover:border-gray-600">
                <div class="text-right hidden md:block">
                    <p class="text-sm font-bold text-gray-700 dark:text-gray-200">مدیر سیستم</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Super Admin</p>
                </div>
                <div class="h-9 w-9 rounded-full bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center text-white shadow-md ring-2 ring-white dark:ring-gray-800">
                    <span class="font-bold text-sm">AD</span>
                </div>
                <?php partial('icon', ['name' => 'chevron-down', 'class' => 'w-4 h-4 text-gray-400 md:block hidden']); ?>
            </button>

            <!-- Dropdown Menu -->
            <div x-show="dropdownOpen"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 translate-y-2"
                 class="absolute left-0 mt-3 w-56 bg-white dark:bg-gray-800 rounded-xl shadow-xl ring-1 ring-black ring-opacity-5 py-1 z-50 origin-top-left divide-y divide-gray-100 dark:divide-gray-700"
                 style="display: none;">

                <div class="px-4 py-3">
                    <p class="text-xs text-gray-500 dark:text-gray-400">وارد شده با عنوان</p>
                    <p class="text-sm font-bold text-gray-900 dark:text-white truncate mt-0.5">admin</p>
                </div>

                <div class="py-1">
                    <a href="#" class="group flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                        <?php partial('icon', ['name' => 'user', 'class' => 'w-4 h-4 ml-3 text-gray-400 group-hover:text-primary-500']); ?>
                        پروفایل کاربری
                    </a>
                    <a href="<?php echo url('settings'); ?>" class="group flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                        <?php partial('icon', ['name' => 'settings', 'class' => 'w-4 h-4 ml-3 text-gray-400 group-hover:text-primary-500']); ?>
                        تنظیمات
                    </a>
                </div>

                <div class="py-1">
                    <a href="<?php echo url('logout'); ?>" class="group flex items-center px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20">
                        <?php partial('icon', ['name' => 'logout', 'class' => 'w-4 h-4 ml-3 text-red-500']); ?>
                        خروج از حساب
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>
