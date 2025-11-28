<?php include __DIR__ . '/../header.tpl'; ?>

<main class="bg-gray-50 min-h-screen pb-16" x-data="{
    searchQuery: '<?php echo $search ?? ''; ?>',
    suggestions: [],
    showSuggestions: false,
    async fetchSuggestions() {
        if (this.searchQuery.length < 2) {
            this.suggestions = [];
            this.showSuggestions = false;
            return;
        }
        try {
            const res = await fetch('/blog/search-suggestions?q=' + encodeURIComponent(this.searchQuery));
            this.suggestions = await res.json();
            this.showSuggestions = true;
        } catch (e) {
            console.error(e);
        }
    }
}">

    <!-- 1. Hero Section (Minimal) -->
    <section class="relative pt-24 pb-12 overflow-hidden bg-white">
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute top-0 right-0 w-1/3 h-full bg-gradient-to-l from-primary-50/50 to-transparent"></div>
            <div class="absolute bottom-0 left-0 w-1/4 h-2/3 bg-gradient-to-t from-gray-50 to-transparent"></div>
        </div>

        <div class="container mx-auto px-4 relative z-10 text-center">
            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 tracking-tight mb-4 font-estedad">
                <?php echo $pageTitle ?? 'وبلاگ و مقالات'; ?>
            </h1>
            <p class="text-lg text-gray-500 max-w-2xl mx-auto mb-8 font-light">
                <?php echo $page_description ?? 'جدیدترین اخبار، مقالات و آموزش‌های تخصصی را دنبال کنید.'; ?>
            </p>

            <!-- Social Icons (Minimal) -->
            <div class="flex justify-center gap-4 mb-10">
                <a href="#" class="text-gray-400 hover:text-primary-600 transition-colors"><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg></a>
                <a href="#" class="text-gray-400 hover:text-pink-600 transition-colors"><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg></a>
                <a href="#" class="text-gray-400 hover:text-blue-700 transition-colors"><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg></a>
            </div>

            <!-- 3. Smart Search -->
            <div class="max-w-xl mx-auto relative group">
                <form action="/blog" method="GET" class="relative">
                    <input type="text" name="search"
                           x-model="searchQuery"
                           @input.debounce.300ms="fetchSuggestions()"
                           @click.outside="showSuggestions = false"
                           @focus="if(searchQuery.length >= 2) showSuggestions = true"
                           placeholder="جستجو در مقالات..."
                           class="w-full h-12 pr-12 pl-4 rounded-xl bg-gray-50 border border-gray-200 focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-100 transition-all shadow-sm outline-none text-gray-700 placeholder-gray-400">
                    <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-gray-400 group-focus-within:text-primary-500 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </form>

                <!-- Auto-suggest Dropdown -->
                <div x-show="showSuggestions && suggestions.length > 0"
                     x-transition.opacity.duration.200ms
                     class="absolute top-full right-0 left-0 mt-2 bg-white rounded-xl shadow-xl border border-gray-100 z-50 overflow-hidden text-right" style="display: none;">
                    <ul>
                        <template x-for="item in suggestions" :key="item.slug">
                            <li>
                                <a :href="'/blog/' + item.slug" class="block px-4 py-3 hover:bg-gray-50 transition-colors flex items-center justify-between group">
                                    <span class="text-gray-700 group-hover:text-primary-600 transition-colors" x-text="item.title"></span>
                                    <svg class="w-4 h-4 text-gray-300 group-hover:text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                                </a>
                            </li>
                        </template>
                    </ul>
                </div>
            </div>

            <!-- 4. Floating Filter Pills (Categories) -->
            <div class="mt-8 flex flex-wrap justify-center gap-2 max-w-3xl mx-auto">
                <a href="/blog" class="px-5 py-2 rounded-full text-sm font-medium transition-all shadow-sm
                    <?php echo empty($selected_category) ? 'bg-gray-900 text-white shadow-md' : 'bg-white text-gray-600 hover:bg-gray-50 border border-gray-200'; ?>">
                    همه مطالب
                </a>
                <?php if (!empty($categories) && is_array($categories)): ?>
                    <?php foreach ($categories as $cat): ?>
                        <a href="/blog?category=<?php echo $cat->id; ?>"
                           class="px-5 py-2 rounded-full text-sm font-medium transition-all shadow-sm
                           <?php echo (isset($selected_category) && $selected_category == $cat->id) ? 'bg-primary-600 text-white shadow-md shadow-primary-200' : 'bg-white text-gray-600 hover:bg-gray-50 hover:text-primary-600 border border-gray-200'; ?>">
                            <?php echo htmlspecialchars($cat->name_fa); ?>
                            <span class="mr-1 opacity-70 text-xs"><?php echo $cat->posts_count ?? 0; ?></span>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- 6. Breadcrumbs -->
    <div class="container mx-auto px-4 py-4">
        <nav class="flex text-sm text-gray-500 font-light" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 space-x-reverse">
                <li class="inline-flex items-center">
                    <a href="/" class="hover:text-gray-900 transition-colors flex items-center">
                        <svg class="w-3 h-3 ml-1" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
                        خانه
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-gray-300 mx-1 transform rotate-180" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                        <span class="text-gray-800 font-medium">وبلاگ</span>
                    </div>
                </li>
                <?php if(!empty($selected_category)): ?>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-gray-300 mx-1 transform rotate-180" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                            <span class="text-gray-500">دسته‌بندی‌ها</span>
                        </div>
                    </li>
                <?php endif; ?>
            </ol>
        </nav>
    </div>

    <!-- Main Content Layout -->
    <div class="container mx-auto px-4 mt-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

            <!-- Left Content (Main) -->
            <div class="lg:col-span-3 space-y-16">

                <!-- 12. Education Section (If Available) -->
                <?php if (!empty($educationPosts)): ?>
                <section>
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                            <span class="w-2 h-8 bg-blue-500 rounded-full ml-3"></span>
                            آموزش‌های تخصصی
                        </h2>
                        <a href="/blog?category=<?php echo $educationPosts[0]->category_id ?? ''; ?>" class="text-blue-500 hover:text-blue-700 text-sm font-medium flex items-center">
                            مشاهده همه
                            <svg class="w-4 h-4 mr-1 transform rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </a>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <?php foreach ($educationPosts as $post): ?>
                            <!-- Card Design -->
                            <article class="group bg-white rounded-2xl shadow-soft hover:shadow-lg transition-all duration-300 border border-gray-100 overflow-hidden flex flex-col h-full">
                                <a href="/blog/<?php echo $post->slug; ?>" class="relative aspect-w-16 aspect-h-9 block overflow-hidden">
                                    <img src="<?php echo !empty($post->image_url) ? $post->image_url : 'https://placehold.co/600x400'; ?>"
                                         alt="<?php echo htmlspecialchars($post->title); ?>"
                                         loading="lazy"
                                         class="object-cover w-full h-full transform group-hover:scale-105 transition-transform duration-500">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                    <span class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-bold text-blue-600 shadow-sm">
                                        <?php echo htmlspecialchars($post->category_name ?? 'آموزش'); ?>
                                    </span>
                                </a>
                                <div class="p-5 flex-1 flex flex-col">
                                    <div class="flex items-center text-xs text-gray-400 mb-3 space-x-3 space-x-reverse">
                                        <span class="flex items-center">
                                            <svg class="w-3.5 h-3.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            <?php echo \jdate('j F Y', strtotime($post->published_at)); ?>
                                        </span>
                                        <span class="flex items-center">
                                            <svg class="w-3.5 h-3.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            <?php echo ceil(mb_strlen(strip_tags($post->excerpt))/500) . ' دقیقه مطالعه'; ?>
                                        </span>
                                    </div>
                                    <h3 class="text-lg font-bold text-gray-800 mb-2 leading-tight group-hover:text-primary-600 transition-colors">
                                        <a href="/blog/<?php echo $post->slug; ?>">
                                            <?php echo htmlspecialchars($post->title); ?>
                                        </a>
                                    </h3>
                                    <p class="text-gray-500 text-sm line-clamp-3 mb-4 flex-1">
                                        <?php echo mb_substr(strip_tags($post->excerpt), 0, 150) . '...'; ?>
                                    </p>

                                    <div class="border-t border-gray-100 pt-4 mt-auto flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div class="w-6 h-6 rounded-full bg-gray-200 overflow-hidden ml-2">
                                                 <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($post->author_name ?? 'Admin'); ?>&background=random&color=fff&size=64" alt="Author" class="w-full h-full object-cover">
                                            </div>
                                            <span class="text-xs text-gray-600 font-medium"><?php echo htmlspecialchars($post->author_name ?? 'ادمین'); ?></span>
                                        </div>
                                        <a href="/blog/<?php echo $post->slug; ?>" class="text-primary-600 hover:text-primary-700 text-sm font-medium flex items-center">
                                            ادامه مطلب
                                            <svg class="w-4 h-4 mr-1 transform rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                                        </a>
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </section>
                <?php endif; ?>

                <!-- Latest Posts Section -->
                <section>
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                            <span class="w-2 h-8 bg-primary-500 rounded-full ml-3"></span>
                            <?php echo !empty($selected_category) ? 'مطالب این دسته' : 'جدیدترین مطالب'; ?>
                        </h2>
                    </div>

                    <?php if (empty($posts)): ?>
                        <div class="bg-white rounded-2xl p-10 text-center shadow-soft">
                            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                            <h3 class="text-lg font-medium text-gray-900">مطلبی یافت نشد</h3>
                            <p class="text-gray-500 mt-2">در حال حاضر پستی برای نمایش وجود ندارد.</p>
                        </div>
                    <?php else: ?>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-x-6 gap-y-10">
                            <?php foreach ($posts as $post): ?>
                                <article class="flex flex-col group h-full">
                                    <a href="/blog/<?php echo $post->slug; ?>" class="block overflow-hidden rounded-2xl mb-4 aspect-w-16 aspect-h-10 relative shadow-sm hover:shadow-md transition-shadow">
                                        <img src="<?php echo !empty($post->image_url) ? $post->image_url : 'https://placehold.co/600x400'; ?>"
                                             alt="<?php echo htmlspecialchars($post->title); ?>"
                                             loading="lazy"
                                             class="object-cover w-full h-full transform group-hover:scale-105 transition-transform duration-700 ease-out">
                                        <span class="absolute top-4 right-4 bg-white/95 backdrop-blur px-3 py-1 rounded-full text-xs font-bold text-gray-800 shadow-sm border border-gray-100">
                                            <?php echo htmlspecialchars($post->category_name ?? 'عمومی'); ?>
                                        </span>
                                    </a>
                                    <div class="flex-1 flex flex-col">
                                        <div class="flex items-center text-xs text-gray-400 mb-2 space-x-3 space-x-reverse font-medium">
                                            <span><?php echo \jdate('j F Y', strtotime($post->published_at)); ?></span>
                                            <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                                            <span><?php echo ceil(mb_strlen(strip_tags($post->excerpt))/500) . ' دقیقه'; ?></span>
                                        </div>
                                        <h3 class="text-xl font-bold text-gray-900 mb-3 leading-snug group-hover:text-primary-600 transition-colors">
                                            <a href="/blog/<?php echo $post->slug; ?>">
                                                <?php echo htmlspecialchars($post->title); ?>
                                            </a>
                                        </h3>
                                        <p class="text-gray-500 text-sm leading-relaxed line-clamp-3 mb-4 flex-1">
                                            <?php echo mb_substr(strip_tags($post->excerpt), 0, 160) . '...'; ?>
                                        </p>
                                        <div class="flex items-center justify-between mt-auto pt-4 border-t border-gray-100 border-dashed">
                                             <div class="flex items-center">
                                                 <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($post->author_name ?? 'A'); ?>&background=random&color=fff&size=48" class="w-6 h-6 rounded-full ml-2" alt="">
                                                 <span class="text-xs font-bold text-gray-700"><?php echo htmlspecialchars($post->author_name ?? 'نویسنده'); ?></span>
                                             </div>
                                        </div>
                                    </div>
                                </article>
                            <?php endforeach; ?>
                        </div>

                        <!-- Pagination -->
                        <?php if ($paginator->total_pages > 1): ?>
                            <div class="mt-12 flex justify-center">
                                <div class="inline-flex rounded-lg shadow-sm border border-gray-200 bg-white overflow-hidden">
                                    <?php if ($paginator->hasPrev()): ?>
                                        <a href="<?php echo $paginator->getPrevUrl(); ?>" class="px-4 py-2 border-l border-gray-200 text-gray-500 hover:bg-gray-50 hover:text-gray-700 transition-colors">
                                            <span class="sr-only">Previous</span>
                                            <svg class="w-5 h-5 transform rotate-180" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                                        </a>
                                    <?php endif; ?>

                                    <span class="px-4 py-2 text-gray-700 font-medium bg-gray-50">
                                        صفحه <?php echo $paginator->current_page; ?> از <?php echo $paginator->total_pages; ?>
                                    </span>

                                    <?php if ($paginator->hasNext()): ?>
                                        <a href="<?php echo $paginator->getNextUrl(); ?>" class="px-4 py-2 border-r border-gray-200 text-gray-500 hover:bg-gray-50 hover:text-gray-700 transition-colors">
                                            <span class="sr-only">Next</span>
                                            <svg class="w-5 h-5 transform rotate-180" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                    <?php endif; ?>
                </section>
            </div>

            <!-- Right Sidebar (Sticky) -->
            <aside class="lg:col-span-1 space-y-8">

                <!-- 5. Top Authors (If available) -->
                <?php if (!empty($topAuthors)): ?>
                <div class="bg-white rounded-2xl shadow-soft p-6 border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2 border-gray-100">نویسندگان برتر</h3>
                    <div class="space-y-4">
                        <?php foreach ($topAuthors as $author): ?>
                            <div class="flex items-center group cursor-default">
                                <div class="relative">
                                    <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($author['name']); ?>&background=random&color=fff&size=80"
                                         class="w-10 h-10 rounded-full object-cover ring-2 ring-transparent group-hover:ring-primary-100 transition-all" alt="">
                                </div>
                                <div class="mr-3">
                                    <h4 class="text-sm font-bold text-gray-800 group-hover:text-primary-600 transition-colors"><?php echo htmlspecialchars($author['name']); ?></h4>
                                    <p class="text-xs text-gray-400 mt-0.5"><?php echo $author['posts_count']; ?> مقاله</p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- 11. Popular Tags -->
                <?php if (!empty($tags)): ?>
                <div class="bg-white rounded-2xl shadow-soft p-6 border border-gray-100">
                     <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2 border-gray-100">برچسب‌های داغ</h3>
                     <div class="flex flex-wrap gap-2">
                         <?php foreach ($tags as $tag): ?>
                            <?php if ($tag['posts_count'] > 0): ?>
                             <a href="/blog/tags/<?php echo $tag['slug']; ?>" class="px-3 py-1.5 bg-gray-50 text-gray-600 rounded-lg text-xs font-medium hover:bg-primary-50 hover:text-primary-600 transition-colors">
                                 #<?php echo htmlspecialchars($tag['name']); ?>
                             </a>
                            <?php endif; ?>
                         <?php endforeach; ?>
                     </div>
                </div>
                <?php endif; ?>

                <!-- Most Viewed Widget -->
                <?php if (!empty($sidebar['most_viewed'])): ?>
                <div class="bg-white rounded-2xl shadow-soft p-6 border border-gray-100 sticky top-4">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2 border-gray-100">پربازدیدترین‌ها</h3>
                    <div class="space-y-4">
                        <?php foreach ($sidebar['most_viewed'] as $index => $post): ?>
                            <a href="/blog/<?php echo $post->slug; ?>" class="flex gap-3 group">
                                <span class="flex-shrink-0 w-6 h-6 flex items-center justify-center rounded bg-gray-100 text-gray-500 font-bold text-xs group-hover:bg-primary-500 group-hover:text-white transition-colors">
                                    <?php echo $index + 1; ?>
                                </span>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-700 leading-snug group-hover:text-primary-600 transition-colors line-clamp-2">
                                        <?php echo htmlspecialchars($post->title); ?>
                                    </h4>
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

<!-- 6. JSON-LD Schemas -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Blog",
  "name": "<?php echo $pageTitle ?? 'بلاگ'; ?>",
  "description": "<?php echo $page_description ?? ''; ?>",
  "url": "<?php echo (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]" . $_SERVER['REQUEST_URI']; ?>"
}
</script>

<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [{
    "@type": "ListItem",
    "position": 1,
    "name": "خانه",
    "item": "<?php echo (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]"; ?>"
  },{
    "@type": "ListItem",
    "position": 2,
    "name": "وبلاگ",
    "item": "<?php echo (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]" . '/blog'; ?>"
  }]
}
</script>

<?php include __DIR__ . '/../footer.tpl'; ?>
