<div class="max-w-2xl mx-auto bg-white rounded-3xl shadow-soft border border-gray-100 p-8">
    <h1 class="text-xl font-bold mb-6"><?php echo $source ? 'ویرایش منبع' : 'افزودن منبع جدید'; ?></h1>

    <form action="<?php echo $source ? '/admin/ai-news/sources/update/' . $source['id'] : '/admin/ai-news/sources/store'; ?>" method="POST" class="space-y-4">
        <?php csrf_field(); ?>

        <div>
            <label class="block text-sm font-bold text-gray-700 mb-1">نام منبع</label>
            <input type="text" name="name" value="<?php echo $source['name'] ?? ''; ?>" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary-500 outline-none" placeholder="مثلاً: زومیت" required>
        </div>

        <div>
            <label class="block text-sm font-bold text-gray-700 mb-1">آدرس (URL یا RSS Feed)</label>
            <input type="url" name="url" value="<?php echo $source['url'] ?? ''; ?>" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary-500 outline-none text-left" dir="ltr" placeholder="https://example.com/rss" required>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">نوع منبع</label>
                <select name="type" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary-500 outline-none">
                    <option value="rss" <?php echo ($source['type'] ?? '') === 'rss' ? 'selected' : ''; ?>>RSS Feed</option>
                    <option value="sitemap" <?php echo ($source['type'] ?? '') === 'sitemap' ? 'selected' : ''; ?>>XML Sitemap</option>
                    <option value="html" <?php echo ($source['type'] ?? '') === 'html' ? 'selected' : ''; ?>>Direct URL (Crawl)</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">وضعیت</label>
                <select name="status" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary-500 outline-none">
                    <option value="active" <?php echo ($source['status'] ?? 'active') === 'active' ? 'selected' : ''; ?>>فعال</option>
                    <option value="inactive" <?php echo ($source['status'] ?? '') === 'inactive' ? 'selected' : ''; ?>>غیرفعال</option>
                </select>
            </div>
        </div>

        <div class="pt-4 flex gap-2">
            <button type="submit" class="flex-1 bg-primary-600 hover:bg-primary-700 text-white font-bold py-3 rounded-xl transition-all">ذخیره منبع</button>
            <a href="/admin/ai-news/sources" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold rounded-xl transition-all">انصراف</a>
        </div>
    </form>
</div>
