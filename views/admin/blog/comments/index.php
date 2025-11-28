<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
    <!-- Header -->
    <div class="p-6 flex flex-col md:flex-row md:justify-between md:items-center border-b border-gray-100 dark:border-gray-700 gap-4">
        <div>
            <h1 class="text-xl font-bold text-gray-800 dark:text-white">مدیریت نظرات بلاگ</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">بررسی و مدیریت نظرات کاربران</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-3">
             <form method="GET" action="/admin/blog/comments" class="flex flex-col sm:flex-row gap-3">
                 <select name="status" onchange="this.form.submit()" class="w-full sm:w-40 py-2 pl-3 pr-8 text-sm text-gray-700 bg-gray-50 dark:bg-gray-700/50 dark:text-gray-200 border border-gray-200 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
                    <option value="all" <?= $status === 'all' || !$status ? 'selected' : '' ?>>همه وضعیت‌ها</option>
                    <option value="pending" <?= $status === 'pending' ? 'selected' : '' ?>>در انتظار تایید</option>
                    <option value="approved" <?= $status === 'approved' ? 'selected' : '' ?>>تایید شده</option>
                    <option value="rejected" <?= $status === 'rejected' ? 'selected' : '' ?>>رد شده</option>
                </select>
                <div class="relative">
                    <input type="text" name="search" value="<?= htmlspecialchars($search ?? '') ?>" placeholder="جستجو (نام، ایمیل، متن...)" class="w-full sm:w-64 pl-10 pr-4 py-2 text-sm text-gray-700 bg-gray-50 dark:bg-gray-700/50 dark:text-gray-200 border border-gray-200 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all">
                    <button type="submit" class="absolute left-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 p-1">
                        <?php partial('icon', ['name' => 'search', 'class' => 'w-4 h-4']); ?>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Mobile List View -->
    <div class="block md:hidden divide-y divide-gray-100 dark:divide-gray-700">
        <?php if (empty($comments)): ?>
            <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                هیچ نظری یافت نشد.
            </div>
        <?php else: ?>
            <?php foreach ($comments as $comment): ?>
                <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                    <div class="flex justify-between items-start mb-2">
                        <div class="flex-1">
                            <h3 class="text-sm font-bold text-gray-900 dark:text-white"><?= htmlspecialchars($comment['name']) ?></h3>
                            <span class="text-xs text-gray-500 dark:text-gray-400 mt-1 block">پست: <?= htmlspecialchars($comment['post_title']) ?></span>
                        </div>
                         <?php
                            $status_class = match($comment['status']) {
                                'approved' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400',
                                'pending' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                                'rejected' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                                default => 'bg-gray-100 text-gray-700'
                            };
                            $status_text = match($comment['status']) {
                                'approved' => 'تایید شده',
                                'pending' => 'در انتظار',
                                'rejected' => 'رد شده',
                                default => 'نامشخص'
                            };
                        ?>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full <?= $status_class ?>">
                            <?= $status_text ?>
                        </span>
                    </div>
                    <div class="mt-2 text-sm text-gray-600 dark:text-gray-300 line-clamp-2">
                        <?= htmlspecialchars($comment['comment']) ?>
                    </div>
                    <div class="flex justify-between items-center mt-3 pt-3 border-t border-gray-100 dark:border-gray-700">
                        <span class="text-xs text-gray-400"><?= \jdate('Y/m/d H:i', strtotime($comment['created_at'])) ?></span>
                        <div class="flex items-center gap-3">
                            <a href="<?php echo url('admin/blog/comments/edit/' . $comment['id']); ?>" class="text-indigo-600 dark:text-indigo-400 text-sm font-medium">مدیریت</a>
                            <span class="text-gray-300 dark:text-gray-600">|</span>
                             <form action="<?php echo url('admin/blog/comments/delete/' . $comment['id']); ?>" method="POST" class="inline-block" onsubmit="return confirm('آیا از حذف این نظر مطمئن هستید؟');">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="text-red-600 dark:text-red-400 text-sm font-medium">حذف</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Desktop Table View -->
    <div class="hidden md:block overflow-x-auto">
        <table class="min-w-full text-right">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer hover:text-gray-700 dark:hover:text-gray-200">
                         <a href="/admin/blog/comments?sort=name&dir=<?= ($sort === 'name' && $dir === 'desc') ? 'asc' : 'desc' ?><?= $search ? '&search=' . urlencode($search) : '' ?><?= $status ? '&status=' . $status : '' ?>" class="flex items-center gap-1">
                            کاربر
                            <?php if ($sort === 'name'): ?>
                                <?php partial('icon', ['name' => $dir === 'asc' ? 'chevron-up' : 'chevron-down', 'class' => 'w-3 h-3']); ?>
                            <?php endif; ?>
                        </a>
                    </th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">نظر</th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer hover:text-gray-700 dark:hover:text-gray-200">
                        <a href="/admin/blog/comments?sort=post_title&dir=<?= ($sort === 'post_title' && $dir === 'desc') ? 'asc' : 'desc' ?><?= $search ? '&search=' . urlencode($search) : '' ?><?= $status ? '&status=' . $status : '' ?>" class="flex items-center gap-1">
                            مطلب
                            <?php if ($sort === 'post_title'): ?>
                                <?php partial('icon', ['name' => $dir === 'asc' ? 'chevron-up' : 'chevron-down', 'class' => 'w-3 h-3']); ?>
                            <?php endif; ?>
                        </a>
                    </th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer hover:text-gray-700 dark:hover:text-gray-200">
                         <a href="/admin/blog/comments?sort=status&dir=<?= ($sort === 'status' && $dir === 'desc') ? 'asc' : 'desc' ?><?= $search ? '&search=' . urlencode($search) : '' ?><?= $status ? '&status=' . $status : '' ?>" class="flex items-center gap-1">
                            وضعیت
                            <?php if ($sort === 'status'): ?>
                                <?php partial('icon', ['name' => $dir === 'asc' ? 'chevron-up' : 'chevron-down', 'class' => 'w-3 h-3']); ?>
                            <?php endif; ?>
                        </a>
                    </th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer hover:text-gray-700 dark:hover:text-gray-200">
                        <a href="/admin/blog/comments?sort=created_at&dir=<?= ($sort === 'created_at' && $dir === 'desc') ? 'asc' : 'desc' ?><?= $search ? '&search=' . urlencode($search) : '' ?><?= $status ? '&status=' . $status : '' ?>" class="flex items-center gap-1">
                            تاریخ
                            <?php if ($sort === 'created_at'): ?>
                                <?php partial('icon', ['name' => $dir === 'asc' ? 'chevron-up' : 'chevron-down', 'class' => 'w-3 h-3']); ?>
                            <?php endif; ?>
                        </a>
                    </th>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider text-center">عملیات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700 bg-white dark:bg-gray-800">
                <?php if (empty($comments)): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400 text-sm">
                            هیچ نظری یافت نشد.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($comments as $comment): ?>
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors group">
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                <div class="font-bold"><?= htmlspecialchars($comment['name']) ?></div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1"><?= htmlspecialchars($comment['email'] ?? '-') ?></div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300 max-w-xs truncate" title="<?= htmlspecialchars($comment['comment']) ?>">
                                <?= htmlspecialchars($comment['comment']) ?>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 max-w-xs truncate">
                                <a href="/blog/<?= $comment['post_slug'] ?>" target="_blank" class="hover:text-primary-600">
                                    <?= htmlspecialchars($comment['post_title']) ?>
                                    <?php partial('icon', ['name' => 'external-link', 'class' => 'w-3 h-3 inline opacity-50']); ?>
                                </a>
                            </td>

                            <td class="px-6 py-4 text-sm">
                                 <?php
                                    $status_class = match($comment['status']) {
                                        'approved' => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/20 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-800',
                                        'pending' => 'bg-amber-50 text-amber-700 dark:bg-amber-900/20 dark:text-amber-400 border border-amber-100 dark:border-amber-800',
                                        'rejected' => 'bg-red-50 text-red-700 dark:bg-red-900/20 dark:text-red-400 border border-red-100 dark:border-red-800',
                                        default => 'bg-gray-50 text-gray-700'
                                    };
                                    $status_text = match($comment['status']) {
                                        'approved' => 'تایید شده',
                                        'pending' => 'در انتظار',
                                        'rejected' => 'رد شده',
                                        default => 'نامشخص'
                                    };
                                ?>
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium <?= $status_class ?>">
                                    <?= $status_text ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                <?= \jdate('Y/m/d H:i', strtotime($comment['created_at'])) ?>
                            </td>
                            <td class="px-6 py-4 text-sm text-center">
                                <div class="flex items-center justify-center gap-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <div class="flex gap-1">
                                         <?php if($comment['status'] !== 'approved'): ?>
                                            <form action="<?php echo url('admin/blog/comments/status/' . $comment['id']); ?>" method="POST" class="inline-block">
                                                <?php echo csrf_field(); ?>
                                                <input type="hidden" name="status" value="approved">
                                                <button type="submit" class="text-emerald-600 hover:text-emerald-900 dark:text-emerald-400 p-1 rounded-md hover:bg-emerald-50 dark:hover:bg-emerald-900/30" title="تایید">
                                                    <?php partial('icon', ['name' => 'check', 'class' => 'w-5 h-5']); ?>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                        <?php if($comment['status'] !== 'rejected'): ?>
                                             <form action="<?php echo url('admin/blog/comments/status/' . $comment['id']); ?>" method="POST" class="inline-block">
                                                <?php echo csrf_field(); ?>
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 p-1 rounded-md hover:bg-red-50 dark:hover:bg-red-900/30" title="رد کردن">
                                                    <?php partial('icon', ['name' => 'close', 'class' => 'w-5 h-5']); ?>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>

                                    <span class="w-px h-4 bg-gray-200 dark:bg-gray-700"></span>

                                    <a href="<?php echo url('admin/blog/comments/edit/' . $comment['id']); ?>" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 p-1 rounded-md hover:bg-indigo-50 dark:hover:bg-indigo-900/30" title="ویرایش">
                                        <?php partial('icon', ['name' => 'edit', 'class' => 'w-5 h-5']); ?>
                                    </a>
                                    <form action="<?php echo url('admin/blog/comments/delete/' . $comment['id']); ?>" method="POST" class="inline-block" onsubmit="return confirm('آیا از حذف این نظر مطمئن هستید؟');">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="text-gray-400 hover:text-red-600 dark:text-gray-500 dark:hover:text-red-400 p-1 rounded-md hover:bg-red-50 dark:hover:bg-red-900/30 transition-colors" title="حذف">
                                            <?php partial('icon', ['name' => 'trash', 'class' => 'w-5 h-5']); ?>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 rounded-b-xl">
        <?php partial('pagination', ['paginator' => $paginator]); ?>
    </div>
</div>
