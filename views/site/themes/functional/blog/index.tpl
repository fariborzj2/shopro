<?php include __DIR__ . '/../header.tpl'; ?>

<main class="w-full bg-slate-50 min-h-screen py-12">
    <div class="container mx-auto px-4">

        <!-- Header & Search -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-12 gap-6">
            <div class="space-y-2 text-center md:text-right">
                <h1 class="text-3xl font-bold text-slate-900">وبلاگ</h1>
                <p class="text-slate-500">آخرین اخبار، مقالات و آموزش‌های تخصصی</p>
            </div>

            <form action="/blog" method="GET" class="w-full md:w-96 relative">
                <input type="text" name="search" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
                       placeholder="جستجو در مقالات..."
                       class="w-full pl-12 pr-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 shadow-sm transition-all">
                <button type="submit" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-primary-600">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </button>
            </form>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Sidebar -->
            <aside class="hidden lg:block lg:col-span-1 space-y-8">
                <!-- Categories -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                    <h3 class="font-bold text-slate-900 mb-4 pb-4 border-b border-slate-100">دسته‌بندی‌ها</h3>
                    <ul class="space-y-3">
                        <?php if(!empty($categories)): foreach($categories as $cat): ?>
                        <li>
                            <a href="/blog/category/<?= htmlspecialchars($cat->slug ?? '') ?>" class="flex items-center justify-between group text-slate-600 hover:text-primary-600 transition-colors">
                                <span class="text-sm font-medium"><?= htmlspecialchars($cat->name ?? '') ?></span>
                                <span class="bg-slate-100 text-slate-500 text-xs py-0.5 px-2 rounded-full group-hover:bg-primary-50 group-hover:text-primary-600 transition-colors"><?= $cat->posts_count ?? 0 ?></span>
                            </a>
                        </li>
                        <?php endforeach; endif; ?>
                    </ul>
                </div>

                <!-- Tags -->
                <?php if(!empty($tags)): ?>
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                     <h3 class="font-bold text-slate-900 mb-4 pb-4 border-b border-slate-100">برچسب‌های محبوب</h3>
                     <div class="flex flex-wrap gap-2">
                        <?php foreach($tags as $tag): ?>
                         <a href="/blog/tag/<?= htmlspecialchars($tag->slug ?? '') ?>" class="text-xs bg-slate-100 hover:bg-primary-50 text-slate-600 hover:text-primary-600 px-3 py-1.5 rounded-lg transition-colors border border-transparent hover:border-primary-100">
                             #<?= htmlspecialchars($tag->name ?? '') ?>
                         </a>
                        <?php endforeach; ?>
                     </div>
                </div>
                <?php endif; ?>
            </aside>

            <!-- Posts Grid -->
            <div class="col-span-1 lg:col-span-3">
                <?php if (!empty($posts['data'])): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <?php foreach ($posts['data'] as $post): ?>
                    <article class="bg-white rounded-2xl border border-slate-200 overflow-hidden hover:shadow-xl transition-all duration-300 flex flex-col group h-full">
                        <a href="/blog/<?= htmlspecialchars($post['slug'] ?? '') ?>" class="block aspect-[16/10] overflow-hidden relative">
                             <?php if(isset($post['category_name'])): ?>
                                <span class="absolute top-4 right-4 bg-white/90 backdrop-blur text-slate-900 text-xs font-bold px-3 py-1 rounded-full shadow-sm z-10">
                                    <?= htmlspecialchars($post['category_name']) ?>
                                </span>
                            <?php endif; ?>
                            <img src="<?= htmlspecialchars($post['image_url'] ?? 'https://placehold.co/600x400') ?>" alt="<?= htmlspecialchars($post['title'] ?? '') ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        </a>
                        <div class="p-6 flex flex-col flex-grow">
                            <div class="flex items-center gap-4 text-xs text-slate-400 mb-4 border-b border-slate-50 pb-4">
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    <span><?= \jdate('d F Y', strtotime($post['created_at'] ?? 'now')) ?></span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                    <span><?= htmlspecialchars($post['author_name'] ?? 'ادمین') ?></span>
                                </div>
                            </div>

                            <h2 class="text-xl font-bold text-slate-900 mb-3 leading-snug group-hover:text-primary-600 transition-colors">
                                <a href="/blog/<?= htmlspecialchars($post['slug'] ?? '') ?>">
                                    <?= htmlspecialchars($post['title'] ?? '') ?>
                                </a>
                            </h2>
                            <p class="text-slate-500 text-sm line-clamp-3 mb-6 flex-grow leading-relaxed">
                                <?= htmlspecialchars($post['excerpt'] ?? '') ?>
                            </p>

                            <div class="mt-auto pt-4 border-t border-slate-50 flex items-center justify-between">
                                <a href="/blog/<?= htmlspecialchars($post['slug'] ?? '') ?>" class="text-primary-600 font-bold text-sm hover:underline inline-flex items-center gap-1">
                                    ادامه مطلب
                                    <svg class="w-4 h-4 rtl:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                                </a>
                                <div class="flex items-center gap-1 text-slate-400 text-xs">
                                     <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    <span><?= number_format($post['views_count'] ?? 0) ?></span>
                                </div>
                            </div>
                        </div>
                    </article>
                    <?php endforeach; ?>
                </div>

                <!-- Pagination -->
                <?php if (($posts['total_pages'] ?? 1) > 1): ?>
                <div class="mt-12 flex justify-center">
                    <nav class="flex gap-2">
                         <?php if (($posts['current_page'] ?? 1) > 1): ?>
                        <a href="?page=<?= ($posts['current_page'] ?? 1) - 1 ?><?= isset($_GET['search']) ? '&search='.htmlspecialchars($_GET['search']) : '' ?>" class="p-2 rounded-lg border border-slate-200 hover:bg-white hover:text-primary-600 hover:shadow-sm transition-all text-slate-500">
                             <svg class="w-5 h-5 rtl:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                        </a>
                        <?php endif; ?>

                        <span class="px-4 py-2 rounded-lg bg-primary-600 text-white font-bold shadow-lg shadow-primary-500/20">
                             <?= $posts['current_page'] ?? 1 ?>
                        </span>

                        <?php if (($posts['current_page'] ?? 1) < ($posts['total_pages'] ?? 1)): ?>
                        <a href="?page=<?= ($posts['current_page'] ?? 1) + 1 ?><?= isset($_GET['search']) ? '&search='.htmlspecialchars($_GET['search']) : '' ?>" class="p-2 rounded-lg border border-slate-200 hover:bg-white hover:text-primary-600 hover:shadow-sm transition-all text-slate-500">
                             <svg class="w-5 h-5 rtl:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        </a>
                        <?php endif; ?>
                    </nav>
                </div>
                <?php endif; ?>

                <?php else: ?>
                <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center">
                    <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-400">
                         <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">مطلبی یافت نشد</h3>
                    <p class="text-slate-500">لطفا با کلمات کلیدی دیگری جستجو کنید.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<?php include __DIR__ . '/../footer.tpl'; ?>
