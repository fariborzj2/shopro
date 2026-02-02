<div class="flex flex-col gap-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">منابع محتوا</h1>
        <a href="/admin/ai-news/sources/create" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-xl font-bold transition-all flex items-center gap-2">
            <?php partial('icon', ['name' => 'plus', 'class' => 'w-5 h-5']); ?>
            افزودن منبع جدید
        </a>
    </div>

    <div class="bg-white rounded-3xl shadow-soft border border-gray-100 overflow-hidden">
        <table class="w-full text-right">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4 font-bold text-gray-600">نام منبع</th>
                    <th class="px-6 py-4 font-bold text-gray-600">آدرس (URL)</th>
                    <th class="px-6 py-4 font-bold text-gray-600">نوع</th>
                    <th class="px-6 py-4 font-bold text-gray-600">آخرین کرال</th>
                    <th class="px-6 py-4 font-bold text-gray-600">وضعیت</th>
                    <th class="px-6 py-4 font-bold text-gray-600 text-center">عملیات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php foreach ($sources as $source): ?>
                <tr>
                    <td class="px-6 py-4 font-medium"><?php echo htmlspecialchars($source['name']); ?></td>
                    <td class="px-6 py-4 text-sm text-gray-500 font-mono"><?php echo htmlspecialchars($source['url']); ?></td>
                    <td class="px-6 py-4 uppercase text-xs font-bold"><?php echo $source['type']; ?></td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        <?php echo $source['last_crawled_at'] ? \jdate('Y/m/d H:i', strtotime($source['last_crawled_at'])) : '---'; ?>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded-full text-[10px] font-bold <?php echo $source['status'] === 'active' ? 'bg-green-50 text-green-600' : 'bg-gray-50 text-gray-500'; ?>">
                            <?php echo $source['status'] === 'active' ? 'فعال' : 'غیرفعال'; ?>
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center gap-2">
                            <a href="/admin/ai-news/sources/edit/<?php echo $source['id']; ?>" class="p-2 text-gray-400 hover:text-primary-600 transition-colors">
                                <?php partial('icon', ['name' => 'edit', 'class' => 'w-5 h-5']); ?>
                            </a>
                            <form action="/admin/ai-news/sources/delete/<?php echo $source['id']; ?>" method="POST" onsubmit="return confirm('آیا از حذف این منبع اطمینان دارید؟');">
                                <?php csrf_field(); ?>
                                <button type="submit" class="p-2 text-gray-400 hover:text-red-600 transition-colors">
                                    <?php partial('icon', ['name' => 'trash', 'class' => 'w-5 h-5']); ?>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($sources)): ?>
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-400">هیچ منبعی تعریف نشده است.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
