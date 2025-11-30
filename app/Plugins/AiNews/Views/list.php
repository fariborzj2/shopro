<?php
// app/Plugins/AiNews/Views/list.php
$title = 'لیست محتوای هوشمند';
?>

<div class="flex flex-col gap-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">پیش‌نویس‌های هوشمند</h1>
        <div class="flex gap-2">
            <a href="/admin/ai-news/settings" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                تنظیمات
            </a>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-card overflow-hidden">
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
</div>
