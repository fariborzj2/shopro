<?php include __DIR__ . '/../header.tpl'; ?>

<div class="container" style="padding-block: 3rem;">
    <!-- Category Header -->
    <header style="text-align: center; margin-bottom: 4rem;">
        <h1 style="font-size: 2.5rem; font-weight: 900; margin-bottom: 1rem; color: var(--color-text-main);">دسته‌بندی: <?= htmlspecialchars($category->name_fa) ?></h1>
        <?php if (!empty($category->notes)): ?>
            <p style="font-size: 1.1rem; color: var(--color-text-muted); max-width: 600px; margin: 0 auto;"><?= htmlspecialchars($category->notes ?? '') ?></p>
        <?php endif; ?>
    </header>

    <!-- Blog Posts Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 2rem;">
        <?php foreach ($posts as $post): ?>
            <article class="glass-panel" style="display: flex; flex-direction: column; overflow: hidden; border-radius: var(--radius-lg); transition: var(--transition-smooth);">
                <a href="/blog/<?= $post->slug ?>" style="aspect-ratio: 16/9; overflow: hidden; display: block;">
                    <img src="<?= htmlspecialchars($post->image_url ?? 'https://placehold.co/600x400/EEE/31343C?text=No+Image') ?>" alt="<?= htmlspecialchars($post->title) ?>" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                </a>
                <div style="padding: 1.5rem; display: flex; flex-direction: column; flex-grow: 1;">
                    <h2 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 0.75rem;">
                        <a href="/blog/<?= $post->slug ?>" style="color: var(--color-text-main); transition: color 0.2s;" onmouseover="this.style.color='var(--color-primary)'" onmouseout="this.style.color='var(--color-text-main)'"><?= htmlspecialchars($post->title) ?></a>
                    </h2>
                    <p style="color: #64748b; margin-bottom: 1.5rem; line-height: 1.6; flex-grow: 1;"><?= htmlspecialchars($post->excerpt) ?></p>
                    <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.85rem; color: #94a3b8; border-top: 1px solid var(--color-border); padding-top: 1rem;">
                        <span><?= htmlspecialchars($post->author_name ?? 'نویسنده ناشناس') ?></span>
                        <span><?= date('Y/m/d', strtotime($post->published_at ?? 'now')) ?></span>
                    </div>
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

<?php include __DIR__ . '/../footer.tpl'; ?>
