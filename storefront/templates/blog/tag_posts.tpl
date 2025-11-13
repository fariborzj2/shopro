<?php partial('storefront/header', ['title' => $pageTitle]) ?>
<div class="container">
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
            <?php partial('blog/_sidebar', ['sidebar' => $sidebar]); ?>
        </div>
    </div>
</div>
<?php partial('storefront/footer') ?>
