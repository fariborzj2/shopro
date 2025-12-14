<?php include __DIR__ . '/../header.tpl'; ?>

<main class="w-full bg-slate-50 min-h-screen py-12">
    <div class="container mx-auto px-4">

        <!-- Breadcrumb -->
        <nav class="flex items-center text-sm text-slate-500 mb-8 overflow-x-auto whitespace-nowrap pb-2">
            <a href="/" class="hover:text-primary-600 transition-colors">خانه</a>
            <svg class="w-4 h-4 mx-2 rtl:rotate-180 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
            <a href="/blog" class="hover:text-primary-600 transition-colors">وبلاگ</a>
            <svg class="w-4 h-4 mx-2 rtl:rotate-180 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
            <span class="text-slate-900 font-medium truncate"><?= htmlspecialchars($post['title'] ?? '') ?></span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Article Content -->
            <article class="lg:col-span-8">
                <!-- Cover Image -->
                <div class="rounded-3xl overflow-hidden shadow-lg border border-slate-200 mb-8 bg-slate-200 aspect-video relative">
                     <img src="<?= htmlspecialchars($post['image_url'] ?? 'https://placehold.co/800x450') ?>" alt="<?= htmlspecialchars($post['title'] ?? '') ?>" class="w-full h-full object-cover">
                     <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                     <div class="absolute bottom-6 right-6 left-6 text-white">
                         <div class="flex flex-wrap gap-2 mb-3">
                              <?php if(!empty($categories)): foreach($categories as $cat): ?>
                                <a href="/blog/category/<?= htmlspecialchars($cat->slug ?? '') ?>" class="bg-primary-600/90 backdrop-blur hover:bg-primary-500 text-white text-xs font-bold px-3 py-1 rounded-full transition-colors">
                                    <?= htmlspecialchars($cat->name ?? '') ?>
                                </a>
                              <?php endforeach; endif; ?>
                         </div>
                         <h1 class="text-3xl md:text-4xl lg:text-5xl font-extrabold leading-tight drop-shadow-sm">
                            <?= htmlspecialchars($post['title'] ?? '') ?>
                        </h1>
                     </div>
                </div>

                <!-- Meta & Author -->
                <div class="bg-white rounded-2xl border border-slate-200 p-6 mb-8 flex flex-wrap items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                         <div class="w-12 h-12 bg-slate-100 rounded-full flex items-center justify-center text-slate-400 overflow-hidden border-2 border-white shadow-sm">
                            <?php if(!empty($post['author_avatar'])): ?>
                                <img src="<?= htmlspecialchars($post['author_avatar']) ?>" alt="Author" class="w-full h-full object-cover">
                            <?php else: ?>
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                            <?php endif; ?>
                        </div>
                        <div>
                            <p class="text-slate-900 font-bold text-sm mb-0.5"><?= htmlspecialchars($post['author_name'] ?? 'نویسنده مهمان') ?></p>
                            <p class="text-slate-500 text-xs">منتشر شده در <?= \jdate('d F Y', strtotime($post['published_at'] ?? $post['created_at'] ?? 'now')) ?></p>
                        </div>
                    </div>

                    <div class="flex items-center gap-6 text-sm text-slate-500">
                         <div class="flex items-center gap-1.5" title="بازدید">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            <span><?= number_format($post['views_count'] ?? 0) ?></span>
                        </div>
                         <div class="flex items-center gap-1.5" title="دیدگاه">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" /></svg>
                            <span><?= number_format($post['comments_count'] ?? 0) ?></span>
                        </div>
                        <button class="flex items-center gap-1.5 hover:text-red-500 transition-colors group">
                            <svg class="w-5 h-5 group-hover:fill-current" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                            <span class="hidden sm:inline">لایک</span>
                        </button>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="bg-white rounded-2xl border border-slate-200 p-8 lg:p-10 mb-8 prose prose-lg prose-slate max-w-none prose-img:rounded-xl prose-a:text-primary-600 hover:prose-a:text-primary-700 prose-headings:font-bold prose-headings:tracking-tight">
                    <?= $post['content'] ?? '' ?>
                </div>

                <!-- Tags -->
                <?php if(!empty($tags)): ?>
                <div class="bg-white rounded-2xl border border-slate-200 p-6 mb-8">
                     <h3 class="font-bold text-slate-900 mb-4 text-sm uppercase tracking-wider">برچسب‌ها</h3>
                     <div class="flex flex-wrap gap-2">
                        <?php foreach($tags as $tag): ?>
                         <a href="/blog/tag/<?= htmlspecialchars($tag['slug'] ?? '') ?>" class="text-sm bg-slate-50 hover:bg-primary-50 text-slate-600 hover:text-primary-600 px-4 py-2 rounded-lg transition-colors border border-slate-100 hover:border-primary-100">
                             #<?= htmlspecialchars($tag['name'] ?? '') ?>
                         </a>
                        <?php endforeach; ?>
                     </div>
                </div>
                <?php endif; ?>

                <!-- Comments Section (Placeholder) -->
                <div class="bg-white rounded-2xl border border-slate-200 p-8" id="comments">
                    <h3 class="text-2xl font-bold text-slate-900 mb-8">دیدگاه‌ها</h3>

                    <!-- Comment Form -->
                    <?php if(isset($_SESSION['user_id'])): ?>
                    <form action="/blog/comment" method="POST" class="mb-12 bg-slate-50 p-6 rounded-xl border border-slate-100">
                        <div class="mb-4">
                            <label class="block text-slate-700 text-sm font-bold mb-2">دیدگاه شما</label>
                            <textarea name="content" rows="4" class="w-full px-4 py-3 rounded-lg border border-slate-200 focus:outline-none focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all resize-none" placeholder="نظر خود را بنویسید..."></textarea>
                        </div>
                        <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                        <div class="text-left">
                             <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-2.5 rounded-lg font-bold shadow-lg shadow-primary-500/20 transition-all">ارسال دیدگاه</button>
                        </div>
                    </form>
                    <?php else: ?>
                    <div class="bg-primary-50 border border-primary-100 rounded-xl p-6 text-center mb-8">
                        <p class="text-primary-800 font-medium mb-3">برای ثبت دیدگاه باید وارد شوید.</p>
                        <button @click="$dispatch('open-auth-modal')" class="text-white bg-primary-600 hover:bg-primary-700 px-6 py-2 rounded-lg font-bold shadow-sm transition-colors">ورود / ثبت نام</button>
                    </div>
                    <?php endif; ?>

                    <!-- Comments List -->
                    <?php if(!empty($comments)): ?>
                    <div class="space-y-6">
                        <?php foreach($comments as $comment): ?>
                        <div class="flex gap-4">
                             <div class="w-10 h-10 bg-slate-100 rounded-full flex-shrink-0 flex items-center justify-center text-slate-400">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                            </div>
                            <div class="flex-grow">
                                <div class="bg-slate-50 rounded-2xl rounded-tr-none p-4 border border-slate-100">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="font-bold text-slate-900 text-sm"><?= htmlspecialchars($comment['author_name'] ?? 'کاربر') ?></span>
                                        <span class="text-xs text-slate-400"><?= \jdate('d F Y', strtotime($comment['created_at'])) ?></span>
                                    </div>
                                    <p class="text-slate-600 text-sm leading-relaxed"><?= htmlspecialchars($comment['content']) ?></p>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                    <p class="text-slate-500 text-center py-8">اولین نفری باشید که نظر می‌دهید.</p>
                    <?php endif; ?>
                </div>

            </article>

            <!-- Sidebar -->
            <aside class="lg:col-span-4 space-y-8">
                <!-- Related Posts -->
                <?php if(!empty($related_posts)): ?>
                <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm sticky top-24">
                     <h3 class="font-bold text-slate-900 mb-6 pb-4 border-b border-slate-100 text-lg">مطالب مرتبط</h3>
                     <div class="space-y-6">
                        <?php foreach($related_posts as $related): ?>
                        <a href="/blog/<?= htmlspecialchars($related['slug']) ?>" class="flex gap-4 group">
                             <div class="w-20 h-20 rounded-lg overflow-hidden bg-slate-100 flex-shrink-0">
                                 <img src="<?= htmlspecialchars($related['image_url'] ?? 'https://placehold.co/150') ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                             </div>
                             <div class="flex flex-col justify-center">
                                 <h4 class="font-bold text-slate-900 text-sm leading-snug group-hover:text-primary-600 transition-colors line-clamp-2 mb-1">
                                     <?= htmlspecialchars($related['title']) ?>
                                 </h4>
                                 <span class="text-xs text-slate-400"><?= \jdate('d F Y', strtotime($related['created_at'])) ?></span>
                             </div>
                        </a>
                        <?php endforeach; ?>
                     </div>
                </div>
                <?php endif; ?>
            </aside>
        </div>
    </div>
</main>

<?php include __DIR__ . '/../footer.tpl'; ?>
