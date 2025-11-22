<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">بررسی نظر</h1>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Review Details -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-2xl p-6 border border-gray-100 dark:border-gray-700">
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-500 dark:text-gray-400 font-bold text-lg">
                        <?php echo mb_substr($review['name'], 0, 1); ?>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white"><?php echo htmlspecialchars($review['name']); ?></h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400"><?php echo htmlspecialchars($review['mobile']); ?></p>
                    </div>
                </div>
                <div class="flex items-center text-amber-400 bg-amber-50 dark:bg-amber-900/20 px-2 py-1 rounded-lg">
                    <span class="text-sm font-bold ml-1"><?php echo $review['rating']; ?></span>
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">متن نظر:</label>
                <p class="text-gray-800 dark:text-gray-200 leading-relaxed bg-gray-50 dark:bg-gray-700/30 p-4 rounded-xl border border-gray-100 dark:border-gray-700">
                    <?php echo nl2br(htmlspecialchars($review['comment'])); ?>
                </p>
            </div>

            <div class="flex items-center text-xs text-gray-400 gap-4">
                <span>تاریخ ثبت: <?php echo jdate('Y/m/d H:i', strtotime($review['created_at'])); ?></span>
            </div>
        </div>

        <!-- Admin Reply Form -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-2xl p-6 border border-gray-100 dark:border-gray-700">
            <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4">پاسخ مدیر</h3>
            <form action="<?php echo url('reviews/update/' . $review['id']); ?>" method="POST">
                <?php csrf_field(); ?>

                <div class="mb-4">
                    <textarea name="admin_reply" rows="4" class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-3 focus:ring-primary-500 focus:border-primary-500 shadow-sm transition-colors placeholder-gray-400" placeholder="پاسخ خود را اینجا بنویسید..."><?php echo htmlspecialchars($review['admin_reply'] ?? ''); ?></textarea>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="status" value="approved" class="text-green-600 focus:ring-green-500" <?php echo $review['status'] === 'approved' ? 'checked' : ''; ?>>
                            <span class="mr-2 text-sm font-medium text-gray-700 dark:text-gray-300">تایید و انتشار</span>
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="status" value="rejected" class="text-red-600 focus:ring-red-500" <?php echo $review['status'] === 'rejected' ? 'checked' : ''; ?>>
                            <span class="mr-2 text-sm font-medium text-gray-700 dark:text-gray-300">رد کردن</span>
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="status" value="pending" class="text-yellow-600 focus:ring-yellow-500" <?php echo $review['status'] === 'pending' ? 'checked' : ''; ?>>
                            <span class="mr-2 text-sm font-medium text-gray-700 dark:text-gray-300">در انتظار</span>
                        </label>
                    </div>

                    <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white font-bold py-2.5 px-6 rounded-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all shadow-lg shadow-primary-500/30">
                        ذخیره وضعیت
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Sidebar Info (Optional - e.g. Product Info) -->
    <div class="lg:col-span-1">
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-2xl p-6 border border-gray-100 dark:border-gray-700 sticky top-6">
            <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4">اطلاعات محصول</h3>
            <!-- Note: Assuming we have access to product data via review, but the find() method in model might not join product details fully unless we update it.
                 For now, let's just provide a link back. -->
            <a href="<?php echo url('products/edit/' . $review['product_id']); ?>" class="block w-full text-center py-3 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                مشاهده محصول مرتبط
            </a>

            <div class="mt-6 pt-6 border-t border-gray-100 dark:border-gray-700">
                <a href="<?php echo url('reviews'); ?>" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 text-sm flex items-center justify-center gap-1">
                    بازگشت به لیست نظرات
                    <?php partial('icon', ['name' => 'chevron-down', 'class' => 'w-4 h-4 rotate-90']); ?>
                </a>
            </div>
        </div>
    </div>
</div>
