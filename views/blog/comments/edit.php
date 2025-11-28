<div class="max-w-4xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">مدیریت نظر</h1>
        <a href="<?php echo url('blog/comments'); ?>" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
            بازگشت به لیست
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
        <form action="<?php echo url('blog/comments/update/' . $comment['id']); ?>" method="POST" class="space-y-6">
            <?php echo csrf_field(); ?>

            <!-- Post & Author Info (Read-only) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4 bg-gray-50 dark:bg-gray-700/30 rounded-lg">
                <div>
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">نام نویسنده</label>
                    <div class="text-gray-900 dark:text-white font-medium"><?= htmlspecialchars($comment['name']) ?></div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">مطلب مرتبط</label>
                    <a href="/blog/<?= $comment['post_slug'] ?>" target="_blank" class="text-primary-600 hover:text-primary-700 dark:text-primary-400 flex items-center gap-1">
                        <?= htmlspecialchars($comment['post_title']) ?>
                        <?php partial('icon', ['name' => 'external-link', 'class' => 'w-3 h-3']); ?>
                    </a>
                </div>
                 <div>
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">تاریخ ثبت</label>
                    <div class="text-gray-900 dark:text-white font-medium"><?= \jdate('Y/m/d H:i', strtotime($comment['created_at'])) ?></div>
                </div>
            </div>

            <!-- Editable Fields -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">نام نمایشی</label>
                    <input type="text" name="name" id="name" value="<?= htmlspecialchars($comment['name']) ?>" required
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">ایمیل</label>
                    <input type="email" name="email" id="email" value="<?= htmlspecialchars($comment['email'] ?? '') ?>"
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white ltr">
                </div>

                 <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">وضعیت</label>
                    <select name="status" id="status" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        <option value="pending" <?= $comment['status'] === 'pending' ? 'selected' : '' ?>>در انتظار تایید</option>
                        <option value="approved" <?= $comment['status'] === 'approved' ? 'selected' : '' ?>>تایید شده</option>
                        <option value="rejected" <?= $comment['status'] === 'rejected' ? 'selected' : '' ?>>رد شده</option>
                    </select>
                </div>
            </div>

            <div>
                <label for="comment" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">متن نظر</label>
                <textarea name="comment" id="comment" rows="6" required
                          class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white resize-y"><?= htmlspecialchars($comment['comment']) ?></textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-700">
                <a href="<?php echo url('blog/comments'); ?>" class="px-6 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    انصراف
                </a>
                <button type="submit" class="px-6 py-2 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg shadow-sm transition-colors focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    ذخیره تغییرات
                </button>
            </div>
        </form>
    </div>
</div>
