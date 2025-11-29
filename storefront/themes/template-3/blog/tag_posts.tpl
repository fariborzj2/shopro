<?php include __DIR__ . '/../header.tpl'; ?>

<main class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen transition-colors duration-300">
    <div class="container mx-auto px-4">
        <!-- Header -->
        <header class="text-center mb-16 max-w-2xl mx-auto">
            <span class="text-primary-600 font-bold tracking-wider text-sm uppercase mb-2 block">برچسب</span>
            <h1 class="text-3xl md:text-4xl font-black text-gray-900 dark:text-white mb-4">
                # <?= htmlspecialchars($tag->name) ?>
            </h1>
        </header>

        <!-- Blog Posts Grid -->
        <?php if (!empty($posts)) : ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($posts as $post) : ?>
                    <article class="bg-white dark:bg-gray-800 rounded-2xl shadow-card border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-lg transition-all duration-300 group flex flex-col h-full">
                        <a href="/blog/<?= $post->slug ?>" class="relative aspect-[16/10] bg-gray-200 dark:bg-gray-700 overflow-hidden block">
                            <img src="<?= $post->image_url ?? 'https://placehold.co/600x400/EEE/31343C?text=No+Image' ?>"
                                 alt="<?= htmlspecialchars($post->title) ?>"
                                 class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500">
                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors duration-300"></div>
                        </a>
                        <div class="p-6 flex-1 flex flex-col">
                            <!-- Author Info -->
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-700 overflow-hidden flex-shrink-0">
                                    <img src="https://ui-avatars.com/api/?name=<?= urlencode($post->author_name) ?>&background=random&color=fff&size=64"
                                         alt="<?= htmlspecialchars($post->author_name) ?>"
                                         class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-800 dark:text-gray-200"><?= htmlspecialchars($post->author_name) ?></p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400"><?= htmlspecialchars($post->author_role ?? 'نویسنده') ?></p>
                                </div>
                            </div>

                            <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-3 leading-snug group-hover:text-primary-600 transition-colors">
                                <a href="/blog/<?= $post->slug ?>">
                                    <?= htmlspecialchars($post->title) ?>
                                </a>
                            </h2>
                            <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed mb-4 line-clamp-3">
                                <?= htmlspecialchars($post->excerpt) ?>
                            </p>

                            <div class="mt-auto pt-4 border-t border-gray-100 dark:border-gray-700 flex items-center justify-between text-xs text-gray-400 dark:text-gray-500">
                                <div class="flex items-center gap-4">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                        <?= \jdate('d F Y', strtotime($post->published_at ?? $post->created_at ?? 'now')) ?>
                                    </span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="flex items-center gap-1" title="بازدید">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                        <?= number_format($post->views_count) ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-12 text-center border border-dashed border-gray-300 dark:border-gray-700 mb-12">
                <svg class="w-16 h-16 text-gray-300 dark:text-gray-600 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">مطلبی یافت نشد</h3>
                <p class="text-gray-500 dark:text-gray-400">برای این برچسب هنوز مطلبی منتشر نشده است.</p>
            </div>
        <?php endif; ?>

        <!-- Pagination -->
        <?php if ($paginator->total_pages > 1) : ?>
            <div class="mt-16">
                <nav aria-label="Page navigation" class="flex justify-center">
                    <ul class="flex items-center gap-2 bg-white dark:bg-gray-800 px-4 py-2 rounded-full shadow-card border border-gray-100 dark:border-gray-700">
                        <?php if ($paginator->hasPrev()) : ?>
                            <li>
                                <a class="w-10 h-10 flex items-center justify-center rounded-full text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-primary-600 dark:hover:text-primary-400 transition-colors" href="<?= $paginator->getPrevUrl() ?>">
                                    <svg class="w-5 h-5 transform scale-x-[-1]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $paginator->total_pages; $i++) : ?>
                            <li>
                                <a class="w-10 h-10 flex items-center justify-center rounded-full text-sm font-bold transition-all <?= ($i == $paginator->current_page) ? 'bg-primary-600 text-white shadow-lg shadow-primary-500/30' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700' ?>" href="<?= $paginator->buildUrl($i) ?>">
                                    <?= $i ?>
                                </a>
                            </li>
                        <?php endfor; ?>

                        <?php if ($paginator->hasNext()) : ?>
                            <li>
                                <a class="w-10 h-10 flex items-center justify-center rounded-full text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-primary-600 dark:hover:text-primary-400 transition-colors" href="<?= $paginator->getNextUrl() ?>">
                                    <svg class="w-5 h-5 transform scale-x-[-1]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php include __DIR__ . '/../footer.tpl'; ?>
