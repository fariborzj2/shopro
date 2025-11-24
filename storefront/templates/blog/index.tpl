<?php include __DIR__ . '/../header.tpl'; ?>

<style>
    /* Slider Enhancements */
    .hero-slider {
        position: relative;
        border-radius: var(--radius-lg);
        overflow: hidden;
        margin-bottom: 4rem;
        box-shadow: var(--shadow-lg);
    }
    .swiper-slide { position: relative; aspect-ratio: 21/9; }
    .swiper-slide img { width: 100%; height: 100%; object-fit: cover; }
    .slider-caption {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 2rem;
        background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
        color: white;
        font-size: 1.5rem;
        font-weight: 700;
        text-shadow: 0 2px 4px rgba(0,0,0,0.5);
    }

    /* Layout */
    .blog-layout {
        display: grid;
        grid-template-columns: 1fr 300px;
        gap: 3rem;
    }
    @media (max-width: 992px) { .blog-layout { grid-template-columns: 1fr; } }

    /* Blog Card */
    .blog-card {
        display: flex;
        flex-direction: column;
        background: white;
        border-radius: var(--radius-lg);
        overflow: hidden;
        border: 1px solid var(--color-border);
        transition: var(--transition-smooth);
        margin-bottom: 2rem;
    }
    .blog-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-glass);
    }
    .blog-card-body { padding: 2rem; }
    .blog-title { font-size: 1.5rem; font-weight: 800; margin-bottom: 1rem; line-height: 1.3; }
    .blog-excerpt { color: var(--color-text-muted); margin-bottom: 1.5rem; line-height: 1.7; }

    /* Pagination */
    .pagination { display: flex; gap: 0.5rem; justify-content: center; margin-top: 3rem; }
    .page-link {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: white;
        border: 1px solid var(--color-border);
        color: var(--color-text-main);
        font-weight: 600;
        transition: var(--transition-smooth);
    }
    .page-link:hover, .page-item.active .page-link {
        background: var(--color-primary);
        color: white;
        border-color: var(--color-primary);
    }

    /* Featured Categories */
    .featured-cats { margin-top: 4rem; padding-top: 3rem; border-top: 1px solid var(--color-border); }
    .cat-section { margin-bottom: 3rem; }
    .cat-title { font-size: 1.75rem; font-weight: 800; margin-bottom: 1.5rem; color: var(--color-text-main); }
    .cat-list { list-style: disc; padding-right: 1.5rem; }
    .cat-list li { margin-bottom: 0.5rem; }
    .cat-list a { color: var(--color-text-muted); transition: color 0.2s; }
    .cat-list a:hover { color: var(--color-primary); }
</style>

<div class="container" style="padding-block: 3rem;">

    <!-- Slider Section -->
    <?php if (!empty($slider_posts)): ?>
        <div id="blog-slider" class="swiper-container hero-slider">
            <div class="swiper-wrapper">
                <?php foreach ($slider_posts as $post): ?>
                    <div class="swiper-slide">
                        <a href="/blog/<?= $post->slug ?>">
                            <img src="<?= $post->image_url ?? 'https://placehold.co/1200x500/EEE/31343C?text=No+Image' ?>" alt="<?= htmlspecialchars($post->title) ?>">
                            <div class="slider-caption"><?= htmlspecialchars($post->title) ?></div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="swiper-pagination"></div>
        </div>
    <?php endif; ?>

    <div class="blog-layout">
        <main>
            <h1 style="font-size: 2.5rem; font-weight: 900; margin-bottom: 2rem; color: var(--color-text-main);"><?= $pageTitle ?></h1>

            <?php foreach ($posts as $post) : ?>
                <article class="blog-card glass-panel">
                    <div class="blog-card-body">
                        <h2 class="blog-title"><a href="/blog/<?= $post->slug ?>"><?= htmlspecialchars($post->title) ?></a></h2>
                        <p class="blog-excerpt"><?= htmlspecialchars($post->excerpt) ?></p>
                        <a href="/blog/<?= $post->slug ?>" class="btn btn-primary">ادامه مطلب &rarr;</a>
                    </div>
                </article>
            <?php endforeach; ?>

            <!-- Pagination -->
            <?php if ($paginator->total_pages > 1) : ?>
                <nav aria-label="Page navigation">
                    <ul class="pagination">
                        <?php if ($paginator->hasPrev()) : ?>
                            <li class="page-item"><a class="page-link" href="<?= $paginator->getPrevUrl() ?>">&larr;</a></li>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $paginator->total_pages; $i++) : ?>
                            <li class="page-item <?= ($i == $paginator->current_page) ? 'active' : '' ?>">
                                <a class="page-link" href="<?= $paginator->buildUrl($i) ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>

                        <?php if ($paginator->hasNext()) : ?>
                            <li class="page-item"><a class="page-link" href="<?= $paginator->getNextUrl() ?>">&rarr;</a></li>
                        <?php endif; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        </main>

        <aside>
            <?php include '_sidebar.tpl'; ?>
        </aside>
    </div>

    <!-- Featured Categories Section -->
    <?php if (!empty($featured_categories)): ?>
        <section class="featured-cats">
            <h2 style="text-align: center; margin-bottom: 2rem; font-weight: 800; font-size: 2rem;">دسته‌بندی‌های منتخب</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
                <?php foreach ($featured_categories as $category): ?>
                    <div class="cat-section glass-panel" style="padding: 2rem;">
                        <h3 class="cat-title">
                            <a href="/blog/category/<?= $category['slug'] ?>"><?= htmlspecialchars($category['name_fa']) ?></a>
                        </h3>
                        <ul class="cat-list">
                            <?php foreach ($category['posts'] as $post): ?>
                                <li><a href="/blog/<?= $post->slug ?>"><?= htmlspecialchars($post->title) ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>
</div>

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
            "url": "<?php echo (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST']; ?>/blog/<?= $post->slug ?>",
            "datePublished": "<?= $post->published_at ?>"
        }<?= $index < count($posts) - 1 ? ',' : '' ?>
        <?php endforeach; ?>
    ]
}
</script>

<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (document.getElementById('blog-slider')) {
            new Swiper('.swiper-container', {
                loop: true,
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
            });
        }
    });
</script>

<?php include __DIR__ . '/../footer.tpl'; ?>
