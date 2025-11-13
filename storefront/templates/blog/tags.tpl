<?php partial('storefront/header', ['title' => $pageTitle]) ?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1><?= $pageTitle ?></h1>
            <div class="list-group">
                <?php foreach ($tags as $tag) : ?>
                    <a href="/blog/tags/<?= $tag['slug'] ?>" class="list-group-item list-group-item-action">
                        <?= $tag['name'] ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<?php partial('storefront/footer') ?>
