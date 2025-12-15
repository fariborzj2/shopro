<?php
// app/Plugins/AiNews/Views/list.php
$title = 'لیست محتوای هوشمند';
?>
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
    <!-- Header -->
    <div class="p-6 flex flex-col sm:flex-row sm:justify-between sm:items-center border-b border-gray-100 dark:border-gray-700 gap-4">
        <div>
            <h1 class="text-xl font-bold text-gray-800 dark:text-white">پیش نویس‌های هوشمند</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">لیست مطالبی که با هوش مصنوعی ایجاد شده‌اند</p>
        </div>
        <a href="<?php echo url('ai-news/settings'); ?>" class="inline-flex items-center justify-center px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 shadow-sm transition-colors">
            <?php partial('icon', ['name' => 'settings', 'class' => 'w-5 h-5 ml-2']); ?>
            تنظیمات مدل هوشمند
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">تصویر</th>
                    <th scope="col" class="px-6 py-3">عنوان</th>
                    <th scope="col" class="px-6 py-3">تاریخ ایجاد</th>
                    <th scope="col" class="px-6 py-3 text-center">عملیات</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($data['posts'])): ?>
                    <tr class="bg-white dark:bg-gray-800">
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                            هیچ پیش‌نویسی یافت نشد.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($data['posts'] as $post): ?>
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                            <td class="px-6 py-4 w-24">
                                <?php if ($post['image_url']): ?>
                                    <img src="<?php echo htmlspecialchars($post['image_url']); ?>" alt="thumbnail" class="w-16 h-16 object-cover rounded-lg">
                                <?php else: ?>
                                    <div class="w-16 h-16 bg-gray-200 dark:bg-gray-700 rounded-lg flex items-center justify-center text-xs">بدون تصویر</div>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white max-w-md truncate">
                                <?php echo htmlspecialchars($post['title']); ?>
                            </td>
                            <td class="px-6 py-4">
                                <?php echo \jdate('Y/m/d H:i', strtotime($post['created_at'])); ?>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center items-center gap-2">
                                    <a href="/admin/blog/posts/edit/<?php echo $post['id']; ?>" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 bg-blue-50 dark:bg-blue-900/20 px-3 py-1.5 rounded-lg transition-colors text-xs">
                                        ویرایش
                                    </a>

                                    <form action="/admin/ai-news/approve/<?php echo $post['id']; ?>" method="POST" class="inline-block" onsubmit="return confirm('آیا از انتشار این پست اطمینان دارید؟');">
                                        <?php csrf_field(); ?>
                                        <button type="submit" class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 bg-green-50 dark:bg-green-900/20 px-3 py-1.5 rounded-lg transition-colors text-xs">
                                            انتشار
                                        </button>
                                    </form>

                                    <form action="/admin/ai-news/delete/<?php echo $post['id']; ?>" method="POST" class="inline-block" onsubmit="return confirm('آیا از حذف این پست اطمینان دارید؟');">
                                        <?php csrf_field(); ?>
                                        <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 bg-red-50 dark:bg-red-900/20 px-3 py-1.5 rounded-lg transition-colors text-xs">
                                            حذف
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
    
</div>
