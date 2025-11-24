<?php partial('storefront/header', ['title' => $pageTitle]) ?>

<div class="container" style="padding-block: 4rem;">
    <header style="text-align: center; margin-bottom: 4rem;">
        <h1 style="font-size: 2.5rem; font-weight: 900; margin-bottom: 1rem; color: var(--color-text-main);"><?= $pageTitle ?></h1>
        <p style="color: var(--color-text-muted);">مطالب مرتبط با برچسب انتخاب شده</p>
    </header>

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 2rem;">
        <?php foreach ($posts as $post) : ?>
            <article class="glass-panel" style="display: flex; flex-direction: column; overflow: hidden; border-radius: var(--radius-lg); transition: var(--transition-smooth);">
                 <div style="padding: 1.5rem; display: flex; flex-direction: column; flex-grow: 1;">
                    <h2 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 0.75rem;">
                        <a href="/blog/<?= $post->slug ?>" style="color: var(--color-text-main);"><?= $post->title ?></a>
                    </h2>
                    <p style="color: #64748b; margin-bottom: 1.5rem; line-height: 1.6; flex-grow: 1;"><?= $post->excerpt ?></p>
                    <a href="/blog/<?= $post->slug ?>" class="btn btn-ghost" style="align-self: flex-start;">ادامه مطلب &rarr;</a>
                </div>
            </article>
        <?php endforeach; ?>
    </div>

    <!-- Pagination -->
    <div style="margin-top: 4rem;">
        <?php if ($paginator->total_pages > 1) : ?>
            <nav style="display: flex; justify-content: center;">
                <ul class="pagination" style="display: flex; gap: 0.5rem; list-style: none;">
                    <?php if ($paginator->hasPrev()) : ?>
                        <li><a class="btn btn-ghost" href="<?= $paginator->getPrevUrl() ?>">&larr; قبلی</a></li>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $paginator->total_pages; $i++) : ?>
                        <li>
                            <a href="<?= $paginator->buildUrl($i) ?>" class="btn <?= ($i == $paginator->current_page) ? 'btn-primary' : 'btn-ghost' ?>" style="width: 40px; height: 40px; padding: 0; display: grid; place-items: center;">
                                <?= $i ?>
                            </a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($paginator->hasNext()) : ?>
                        <li><a class="btn btn-ghost" href="<?= $paginator->getNextUrl() ?>">بعدی &rarr;</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
</div>

<?php partial('storefront/footer') ?>
