<div x-data="{ activeTab: 'most-commented' }" class="sidebar">
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link" :class="{ 'active': activeTab === 'most-commented' }" @click.prevent="activeTab = 'most-commented'" href="#">پربحث‌ترین</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" :class="{ 'active': activeTab === 'most-viewed' }" @click.prevent="activeTab = 'most-viewed'" href="#">پربازدیدترین</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" :class="{ 'active': activeTab === 'editors-picks' }" @click.prevent="activeTab = 'editors-picks'" href="#">پیشنهاد سردبیر</a>
        </li>
    </ul>

    <div class="tab-content mt-3">
        <div x-show="activeTab === 'most-commented'">
            <ul class="list-group">
                <?php if (!empty($sidebar['most_commented'])): ?>
                    <?php foreach ($sidebar['most_commented'] as $post): ?>
                        <li class="list-group-item">
                            <a href="/blog/<?= $post->slug ?>"><?= htmlspecialchars($post->title) ?></a>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li class="list-group-item text-muted text-sm">موردی یافت نشد.</li>
                <?php endif; ?>
            </ul>
        </div>
        <div x-show="activeTab === 'most-viewed'">
            <ul class="list-group">
                <?php if (!empty($sidebar['most_viewed'])): ?>
                    <?php foreach ($sidebar['most_viewed'] as $post): ?>
                        <li class="list-group-item">
                            <a href="/blog/<?= $post->slug ?>"><?= htmlspecialchars($post->title) ?></a>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li class="list-group-item text-muted text-sm">موردی یافت نشد.</li>
                <?php endif; ?>
            </ul>
        </div>
        <div x-show="activeTab === 'editors-picks'">
            <ul class="list-group">
                <?php if (!empty($sidebar['editors_picks'])): ?>
                    <?php foreach ($sidebar['editors_picks'] as $post): ?>
                        <li class="list-group-item">
                            <a href="/blog/<?= $post->slug ?>"><?= htmlspecialchars($post->title) ?></a>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li class="list-group-item text-muted text-sm">موردی یافت نشد.</li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</div>
