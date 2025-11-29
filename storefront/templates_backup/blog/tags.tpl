<?php partial('storefront/header', ['title' => $pageTitle]) ?>

<div class="container" style="padding-block: 4rem;">
    <h1 style="text-align: center; font-size: 2.5rem; font-weight: 900; margin-bottom: 3rem; color: var(--color-text-main);"><?= $pageTitle ?></h1>

    <div class="glass-panel" style="padding: 2rem;">
        <div style="display: flex; flex-wrap: wrap; gap: 1rem; justify-content: center;">
            <?php foreach ($tags as $tag) : ?>
                <a href="/blog/tags/<?= $tag['slug'] ?>" class="btn btn-ghost" style="border-radius: 2rem; padding: 0.5rem 1.5rem; border: 1px solid var(--color-border);">
                    # <?= $tag['name'] ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php partial('storefront/footer') ?>
