<?php partial('storefront/header', ['title' => $pageTitle]) ?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1><?= $pageTitle ?></h1>
            <?php foreach ($posts as $post) : ?>
                <div class="card mb-4">
                    <div class="card-body">
                        <h2 class="card-title"><a href="/blog/<?= $post->slug ?>"><?= $post->title ?></a></h2>
                        <p class="card-text"><?= $post->excerpt ?></p>
                        <a href="/blog/<?= $post->slug ?>" class="btn btn-primary">Read More &rarr;</a>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php if ($paginator->total_pages > 1) : ?>
                <nav>
                    <ul class="pagination">
                        <?php if ($paginator->hasPrev()) : ?>
                            <li class="page-item"><a class="page-link" href="<?= $paginator->getPrevUrl() ?>">Previous</a></li>
                        <?php endif; ?>
                        <?php for ($i = 1; $i <= $paginator->total_pages; $i++) : ?>
                            <li class="page-item <?= ($i == $paginator->current_page) ? 'active' : '' ?>"><a class="page-link" href="<?= $paginator->buildUrl($i) ?>"><?= $i ?></a></li>
                        <?php endfor; ?>
                        <?php if ($paginator->hasNext()) : ?>
                            <li class="page-item"><a class="page-link" href="<?= $paginator->getNextUrl() ?>">Next</a></li>
                        <?php endif; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php partial('storefront/footer') ?>
