<?php include __DIR__ . '/../header.tpl'; ?>

<main class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen transition-colors duration-300">
    <div class="container mx-auto px-4">

        <!-- Page Title & Filters -->
        <div class="flex flex-col md:flex-row items-center justify-between mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-black text-gray-900 dark:text-white flex items-center gap-3">
                    <?= htmlspecialchars($pageTitle) ?>
                </h1>
                <span class="text-gray-500 dark:text-gray-400 mt-1 block">به‌روزترین اخبار و اطلاعات در زمینه تکنولوژی</span>
            </div>
        </div>

        <!-- Hero Section (Slider) -->
        <?php if (!empty($slider_posts)): ?>
            <div class="relative w-full mb-12 group" x-data="heroSlider">
                <div class="swiper hero-slider h-[300px] md:h-[450px] lg:h-[500px]">
                    <div class="swiper-wrapper">
                        <?php foreach ($slider_posts as $post): ?>
                            <div class="swiper-slide rounded-3xl overflow-hidden shadow-card border border-gray-100 dark:border-gray-700 relative">
                                <a href="/blog/<?= $post->category_slug ?>/<?= $post->id ?>-<?= $post->slug ?>" class="block w-full h-full">
                                    <img src="<?= $post->image_url ?? 'https://placehold.co/1200x500/EEE/31343C?text=No+Image' ?>"
                                         alt="<?= htmlspecialchars($post->title) ?>"
                                         class="w-full h-full object-cover transition-transform duration-700 hover:scale-105">
                                    <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/40 to-transparent flex items-end">
                                        <div class="p-8 md:p-12 w-full max-w-4xl">
                                            <div class="flex items-center gap-3 text-white/80 text-sm mb-3">
                                                <span class="bg-primary-600 px-3 py-1 rounded-full text-white text-xs font-bold shadow-sm">ویژه</span>
                                                <span class="flex items-center gap-1">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                    <?= \jdate('d F Y', strtotime($post->published_at ?? $post->created_at ?? 'now')) ?>
                                                </span>
                                            </div>
                                            <h2 class="text-xl md:text-2xl lg:text-3xl font-black text-white leading-tight mb-2 drop-shadow-lg">
                                                <?= htmlspecialchars($post->title) ?>
                                            </h2>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Navigation & Pagination -->
                <div class="flex items-center justify-between mt-6 px-2">
                    <div class="swiper-pagination-custom flex items-center justify-start gap-1 relative !w-auto !static"></div>
                    <div class="flex gap-3 items-center">
                        <div class="swiper-button-prev-custom w-12 h-12 flex items-center justify-center rounded-full bg-white dark:bg-gray-800 text-gray-700 dark:text-white shadow-soft hover:bg-primary-600 hover:text-white hover:scale-105 transition-all duration-300 cursor-pointer group border border-gray-100 dark:border-gray-700" role="button" aria-label="قبلی">
                            <svg class="w-6 h-6 transform rtl:rotate-180 group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </div>
                        <div class="swiper-button-next-custom w-12 h-12 flex items-center justify-center rounded-full bg-white dark:bg-gray-800 text-gray-700 dark:text-white shadow-soft hover:bg-primary-600 hover:text-white hover:scale-105 transition-all duration-300 cursor-pointer group border border-gray-100 dark:border-gray-700" role="button" aria-label="بعدی">
                            <svg class="w-6 h-6 transform rtl:rotate-180 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <style>
                .swiper-pagination-custom .swiper-pagination-bullet {
                    width: 10px;
                    height: 10px;
                    background-color: #cbd5e1;
                    opacity: 1;
                    transition: all 0.3s ease;
                    border-radius: 9999px;
                }
                .dark .swiper-pagination-custom .swiper-pagination-bullet {
                    background-color: #4b5563;
                }
                .swiper-pagination-custom .swiper-pagination-bullet-active {
                    background-color: #2563eb;
                    width: 30px;
                }
            </style>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

            <!-- Main Content -->
            <div class="lg:col-span-8">

                <!-- Posts Grid -->
                <?php if (!empty($posts)) : ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
                        <?php foreach ($posts as $post) : ?>
                            <article class="bg-white dark:bg-gray-800 rounded-2xl shadow-card border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-lg transition-all duration-300 group flex flex-col h-full">
                                <a href="/blog/<?= $post->category_slug ?>/<?= $post->id ?>-<?= $post->slug ?>" class="relative aspect-[16/10] bg-gray-200 dark:bg-gray-700 overflow-hidden block">
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
                                        <a href="/blog/<?= $post->category_slug ?>/<?= $post->id ?>-<?= $post->slug ?>">
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
                        <p class="text-gray-500 dark:text-gray-400">محتوایی برای نمایش وجود ندارد.</p>
                    </div>
                <?php endif; ?>

                <!-- Pagination -->
                <?php if ($paginator->total_pages > 1) : ?>
                    <nav aria-label="Page navigation" class="flex justify-center mb-12">
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
                <?php endif; ?>

                <!-- Featured Categories Section -->
                <?php if (!empty($featured_categories)): ?>
                    <section class="mt-16 pt-12 border-t border-gray-200 dark:border-gray-700">
                        <div class="text-center mb-10">
                            <span class="text-primary-600 font-bold tracking-wider text-sm uppercase mb-2 block">کشف کنید</span>
                            <h2 class="text-3xl font-black text-gray-900 dark:text-white">دسته‌بندی‌های منتخب</h2>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <?php foreach ($featured_categories as $category): ?>
                                <div class="bg-white dark:bg-gray-800 rounded-2xl p-8 border border-gray-100 dark:border-gray-700 shadow-card hover:shadow-lg transition-all">
                                    <div class="flex items-center justify-between mb-6">
                                        <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                            <span class="w-1.5 h-6 bg-primary-500 rounded-full"></span>
                                            <a href="/blog/category/<?= $category['slug'] ?>" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                                                <?= htmlspecialchars($category['name_fa']) ?>
                                            </a>
                                        </h3>
                                        <a href="/blog/category/<?= $category['slug'] ?>" class="text-sm font-medium text-primary-500 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300">مشاهده همه</a>
                                    </div>
                                    <ul class="space-y-4">
                                        <?php foreach ($category['posts'] as $cat_post): ?>
                                            <li class="group">
                                                <a href="/blog/<?= $cat_post->slug ?>" class="flex items-start gap-3">
                                                    <svg class="w-5 h-5 text-gray-300 dark:text-gray-600 mt-0.5 group-hover:text-primary-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                                    <span class="text-gray-600 dark:text-gray-400 group-hover:text-primary-700 dark:group-hover:text-primary-300 transition-colors leading-relaxed">
                                                        <?= htmlspecialchars($cat_post->title) ?>
                                                    </span>
                                                </a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </section>
                <?php endif; ?>

            </div>

            <!-- Sidebar -->
            <aside class="lg:col-span-4 space-y-8">
                <?php include __DIR__ . '/_sidebar.tpl'; ?>

                <!-- Extra Sidebar Widget -->
                <div class="bg-primary-600 rounded-2xl p-6 text-white text-center shadow-lg shadow-primary-500/30">
                    <h3 class="text-xl font-bold mb-3">عضویت در خبرنامه</h3>
                    <p class="text-primary-100 text-sm mb-6 leading-relaxed">برای اطلاع از جدیدترین اخبار و مقالات آموزشی در خبرنامه ما عضو شوید.</p>
                    <form class="space-y-3" onsubmit="event.preventDefault(); alert('این ویژگی به زودی فعال می‌شود.');">
                        <input type="email" placeholder="آدرس ایمیل شما" class="w-full rounded-xl border-none bg-white/20 placeholder-white/60 text-white focus:ring-2 focus:ring-white/50 py-3 px-4 text-center backdrop-blur-sm">
                        <button class="w-full bg-white text-primary-600 font-bold py-3 rounded-xl hover:bg-primary-50 transition-colors shadow-soft">عضویت</button>
                    </form>
                </div>
            </aside>

        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('heroSlider', () => ({
            init() {
                this.$nextTick(() => {
                    new Swiper('.hero-slider', {
                        loop: true,
                        speed: 500,
                        spaceBetween: 24, // Gap between slides
                        slidesPerView: 1, // Default (mobile)
                        autoplay: {
                            delay: 5000,
                            disableOnInteraction: false,
                        },
                        breakpoints: {
                            // Tablet
                            640: {
                                slidesPerView: 2,
                                spaceBetween: 20,
                            },
                            // Desktop
                            1024: {
                                slidesPerView: 3,
                                spaceBetween: 24,
                            }
                        },
                        pagination: {
                            el: '.swiper-pagination-custom',
                            clickable: true,
                            dynamicBullets: true,
                        },
                        navigation: {
                            nextEl: '.swiper-button-next-custom',
                            prevEl: '.swiper-button-prev-custom',
                        },
                    });
                });
            }
        }));
    });
</script>

<!-- JSON-LD Schema -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Blog",
    "name": "وبلاگ فروشگاه مدرن",
    "description": "جدیدترین مقالات و اخبار تکنولوژی",
    "blogPost": [
        <?php foreach ($posts as $index => $post) : ?>
        {
            "@type": "BlogPosting",
            "headline": "<?= htmlspecialchars($post->title) ?>",
            "alternativeHeadline": "<?= htmlspecialchars($post->excerpt) ?>",
            "image": "<?= $post->image_url ?? '' ?>",
            "url": "<?php echo (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST']; ?>/blog/<?= $post->category_slug ?>/<?= $post->id ?>-<?= $post->slug ?>",
            "datePublished": "<?= $post->published_at ?>"
        }<?= $index < count($posts) - 1 ? ',' : '' ?>
        <?php endforeach; ?>
    ]
}
</script>

<?php include __DIR__ . '/../footer.tpl'; ?>
