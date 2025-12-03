<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 transition-colors duration-300">
    <!-- Header & Breadcrumbs -->
    <div class="p-4 sm:p-6 border-b border-gray-100 dark:border-gray-700">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-4">
            <div>
                <h1 class="text-xl font-bold text-gray-800 dark:text-white">کتابخانه رسانه</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">مدیریت فایل‌ها و پوشه‌ها</p>
            </div>
        </div>

        <!-- Breadcrumbs -->
        <nav class="flex items-center text-sm text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-gray-900/50 p-3 rounded-lg overflow-x-auto whitespace-nowrap">
            <a href="<?= url('media') ?>" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors flex items-center">
                <?php partial('icon', ['name' => 'dashboard', 'class' => 'w-4 h-4 ml-1']); ?>
                خانه
            </a>
            <?php foreach ($breadcrumbs as $crumb): ?>
                <span class="mx-2 text-gray-400">/</span>
                <a href="<?= url('media?folder=' . urlencode($crumb['path'])) ?>" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                    <?= htmlspecialchars($crumb['name']) ?>
                </a>
            <?php endforeach; ?>
        </nav>
    </div>

    <div class="p-6">
        <?php if ($currentPath && empty($folders) && empty($files)): ?>
            <div class="text-center py-12">
                <div class="w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                    <?php partial('icon', ['name' => 'media', 'class' => 'w-10 h-10 text-gray-400']); ?>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">این پوشه خالی است</h3>
                <div class="mt-6">
                    <a href="<?= url('media?folder=' . urlencode(dirname($currentPath) === '.' ? '' : dirname($currentPath))) ?>" class="inline-flex items-center text-primary-600 hover:text-primary-700 dark:text-primary-400 font-medium">
                        بازگشت به پوشه قبل
                        <?php partial('icon', ['name' => 'chevron-down', 'class' => 'w-4 h-4 mr-1 rotate-90']); ?>
                    </a>
                </div>
            </div>
        <?php else: ?>

            <!-- Folders Section -->
            <?php if (!empty($folders)): ?>
                <h2 class="text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-4">پوشه‌ها</h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 mb-8">
                    <?php foreach ($folders as $folder): ?>
                        <a href="<?= url('media?folder=' . urlencode($folder['path'])) ?>" class="group flex flex-col items-center p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl hover:bg-primary-50 dark:hover:bg-primary-900/20 border border-transparent hover:border-primary-200 dark:hover:border-primary-800 transition-all">
                            <div class="w-12 h-12 text-yellow-500 mb-2 transition-transform group-hover:scale-110">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-full h-full">
                                    <path d="M19.5 21a3 3 0 003-3v-4.5a3 3 0 00-3-3h-15a3 3 0 00-3 3V18a3 3 0 003 3h15zM1.5 10.146V6a3 3 0 013-3h5.379a2.25 2.25 0 011.59.659l2.122 2.121c.14.141.331.22.53.22H19.5a3 3 0 013 3v1.146A4.483 4.483 0 0019.5 9h-15a4.483 4.483 0 00-3 1.146z" />
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-200 text-center truncate w-full group-hover:text-primary-700 dark:group-hover:text-primary-300">
                                <?= htmlspecialchars($folder['name']) ?>
                            </span>
                            <span class="text-xs text-gray-400 mt-1"><?= $folder['count'] ?> مورد</span>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Files Section -->
            <?php if (!empty($files)): ?>
                <h2 class="text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-4">فایل‌ها</h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    <?php foreach ($files as $file): ?>
                        <div class="group relative bg-gray-50 dark:bg-gray-700/30 rounded-xl overflow-hidden border border-gray-100 dark:border-gray-700 hover:shadow-md transition-all">
                            <!-- Thumbnail -->
                            <div class="aspect-square relative overflow-hidden bg-gray-200 dark:bg-gray-800">
                                <?php if (in_array($file['extension'], ['jpg', 'jpeg', 'png', 'webp', 'gif'])): ?>
                                    <img src="<?= asset($file['path']) ?>" alt="<?= htmlspecialchars($file['name']) ?>" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                <?php else: ?>
                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                        <span class="text-xs font-mono uppercase"><?= $file['extension'] ?></span>
                                    </div>
                                <?php endif; ?>

                                <!-- Overlay Actions -->
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2 backdrop-blur-[1px]">
                                    <a href="<?= asset($file['path']) ?>" target="_blank" class="p-2 bg-white/90 hover:bg-white rounded-full text-gray-700 shadow-sm transition-transform hover:scale-110" title="مشاهده">
                                        <?php partial('icon', ['name' => 'eye', 'class' => 'w-4 h-4']); ?>
                                    </a>
                                    <form action="<?= url('media/delete-item') ?>" method="POST" onsubmit="return confirm('آیا از حذف این فایل مطمئن هستید؟ این عملیات قابل بازگشت نیست.');">
                                        <?php csrf_field(); ?>
                                        <input type="hidden" name="path" value="<?= htmlspecialchars($file['path']) ?>">
                                        <button type="submit" class="p-2 bg-red-500 hover:bg-red-600 rounded-full text-white shadow-sm transition-transform hover:scale-110" title="حذف">
                                            <?php partial('icon', ['name' => 'trash', 'class' => 'w-4 h-4']); ?>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <!-- File Info -->
                            <div class="p-3">
                                <p class="text-xs font-medium text-gray-700 dark:text-gray-200 truncate mb-1" title="<?= htmlspecialchars($file['name']) ?>">
                                    <?= htmlspecialchars($file['name']) ?>
                                </p>
                                <div class="flex justify-between items-center text-[10px] text-gray-400">
                                    <span><?= $file['size'] ?></span>
                                    <span><?= date('Y/m/d', $file['date']) ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

        <?php endif; ?>
    </div>
</div>
