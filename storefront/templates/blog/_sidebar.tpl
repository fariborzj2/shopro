<style>
    .sidebar-widget {
        background: var(--color-bg-surface);
        backdrop-filter: blur(12px);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-lg);
        padding: 1.5rem;
        box-shadow: var(--shadow-glass);
    }

    .sidebar-tabs {
        display: flex;
        border-bottom: 1px solid var(--color-border);
        margin-bottom: 1rem;
        gap: 1rem;
    }

    .sidebar-tab {
        padding-bottom: 0.5rem;
        font-weight: 600;
        color: var(--color-text-muted);
        border-bottom: 2px solid transparent;
        transition: var(--transition-smooth);
        cursor: pointer;
        font-size: 0.95rem;
    }

    .sidebar-tab.active {
        color: var(--color-primary);
        border-bottom-color: var(--color-primary);
    }

    .sidebar-list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .sidebar-item {
        display: block;
        padding: 0.75rem;
        border-radius: var(--radius-md);
        background: rgba(255,255,255,0.4);
        transition: var(--transition-smooth);
        color: var(--color-text-main);
        font-size: 0.95rem;
        line-height: 1.5;
    }

    .sidebar-item:hover {
        background: white;
        color: var(--color-primary);
        transform: translateX(-4px); /* RTL logic handled by dir="rtl" but physically move left */
    }

    .empty-state {
        color: var(--color-text-muted);
        font-size: 0.9rem;
        text-align: center;
        padding: 1rem;
    }
</style>

<aside class="sidebar-widget" x-data="{ activeTab: 'most-commented' }">
    <nav class="sidebar-tabs" aria-label="Sidebar Tabs">
        <a class="sidebar-tab" :class="{ 'active': activeTab === 'most-commented' }" @click.prevent="activeTab = 'most-commented'" href="#">پربحث‌ترین</a>
        <a class="sidebar-tab" :class="{ 'active': activeTab === 'most-viewed' }" @click.prevent="activeTab = 'most-viewed'" href="#">پربازدیدترین</a>
        <a class="sidebar-tab" :class="{ 'active': activeTab === 'editors-picks' }" @click.prevent="activeTab = 'editors-picks'" href="#">پیشنهاد سردبیر</a>
    </nav>

    <div class="tab-content">
        <div x-show="activeTab === 'most-commented'" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
            <div class="sidebar-list">
                <?php if (!empty($sidebar['most_commented'])): ?>
                    <?php foreach ($sidebar['most_commented'] as $post): ?>
                        <a href="/blog/<?= $post->slug ?>" class="sidebar-item"><?= htmlspecialchars($post->title) ?></a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="empty-state">موردی یافت نشد.</p>
                <?php endif; ?>
            </div>
        </div>

        <div x-show="activeTab === 'most-viewed'" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
            <div class="sidebar-list">
                <?php if (!empty($sidebar['most_viewed'])): ?>
                    <?php foreach ($sidebar['most_viewed'] as $post): ?>
                        <a href="/blog/<?= $post->slug ?>" class="sidebar-item"><?= htmlspecialchars($post->title) ?></a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="empty-state">موردی یافت نشد.</p>
                <?php endif; ?>
            </div>
        </div>

        <div x-show="activeTab === 'editors-picks'" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
            <div class="sidebar-list">
                <?php if (!empty($sidebar['editors_picks'])): ?>
                    <?php foreach ($sidebar['editors_picks'] as $post): ?>
                        <a href="/blog/<?= $post->slug ?>" class="sidebar-item"><?= htmlspecialchars($post->title) ?></a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="empty-state">موردی یافت نشد.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</aside>
