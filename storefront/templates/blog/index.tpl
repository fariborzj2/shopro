<?php include __DIR__ . '/../header.tpl'; ?>

<div class="container">
    <!-- Slider Section -->
    <?php if (!empty($slider_posts)): ?>
        <div id="blog-slider" class="swiper-container mb-5">
            <div class="swiper-wrapper">
                <?php foreach ($slider_posts as $post): ?>
                    <div class="swiper-slide">
                        <a href="/blog/<?= $post->slug ?>">
                            <img src="<?= $post->featured_image ?? 'https://placehold.co/800x400' ?>" alt="<?= htmlspecialchars($post->title) ?>">
                            <div class="slider-caption"><?= htmlspecialchars($post->title) ?></div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="swiper-pagination"></div>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-lg-8">
            <h1 class="mb-4"><?= $pageTitle ?></h1>
            <?php foreach ($posts as $post) : ?>
                <div class="card mb-4">
                    <div class="card-body">
                        <h2 class="card-title"><a href="/blog/<?= $post->slug ?>"><?= htmlspecialchars($post->title) ?></a></h2>
                        <p class="card-text"><?= htmlspecialchars($post->excerpt) ?></p>
                        <a href="/blog/<?= $post->slug ?>" class="btn btn-primary">ادامه مطلب &rarr;</a>
                    </div>
                </div>
            <?php endforeach; ?>

            <!-- Pagination -->
            <?php if ($paginator->total_pages > 1) : ?>
                <nav>
                    <ul class="pagination">
                        <?php if ($paginator->hasPrev()) : ?>
                            <li class="page-item"><a class="page-link" href="<?= $paginator->getPrevUrl() ?>">قبلی</a></li>
                        <?php endif; ?>
                        <?php for ($i = 1; $i <= $paginator->total_pages; $i++) : ?>
                            <li class="page-item <?= ($i == $paginator->current_page) ? 'active' : '' ?>"><a class="page-link" href="<?= $paginator->buildUrl($i) ?>"><?= $i ?></a></li>
                        <?php endfor; ?>
                        <?php if ($paginator->hasNext()) : ?>
                            <li class="page-item"><a class="page-link" href="<?= $paginator->getNextUrl() ?>">بعدی</a></li>
                        <?php endif; ?>
                    </ul>
                </nav>
            <?php endif; ?>

        </div>
        <div class="col-lg-4">
            <?php include '_sidebar.tpl'; ?>
        </div>
    </div>

    <!-- Featured Categories Section -->
    <?php if (!empty($featured_categories)): ?>
        <hr class="my-5">
        <div class="featured-categories">
            <h2>دسته‌بندی‌های منتخب</h2>
            <?php foreach ($featured_categories as $category): ?>
                <div class="mb-4">
                    <h3><a href="/blog/category/<?= $category['slug'] ?>"><?= htmlspecialchars($category['name_fa']) ?></a></h3>
                    <ul class="list-unstyled">
                        <?php foreach ($category['posts'] as $post): ?>
                            <li><a href="/blog/<?= $post->slug ?>"><?= htmlspecialchars($post->title) ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

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
                },
            });
        }
    });
</script>

<?php include __DIR__ . '/../footer.tpl'; ?>
