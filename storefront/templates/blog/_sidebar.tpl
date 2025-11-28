<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-8" x-data="{ activeTab: 'most-commented' }">
    <!-- Tabs Header -->
    <nav class="flex border-b border-gray-100 mb-6 gap-6 relative" aria-label="Sidebar Tabs">
        <a class="pb-3 font-bold text-sm cursor-pointer transition-all duration-300 relative"
           :class="{ 'text-indigo-600': activeTab === 'most-commented', 'text-gray-400 hover:text-gray-600': activeTab !== 'most-commented' }"
           @click.prevent="activeTab = 'most-commented'">
            پربحث‌ترین
            <span class="absolute bottom-[-1px] right-0 w-full h-0.5 bg-indigo-600 rounded-t-full transition-transform duration-300"
                  :class="activeTab === 'most-commented' ? 'scale-x-100' : 'scale-x-0'"></span>
        </a>
        <a class="pb-3 font-bold text-sm cursor-pointer transition-all duration-300 relative"
           :class="{ 'text-indigo-600': activeTab === 'most-viewed', 'text-gray-400 hover:text-gray-600': activeTab !== 'most-viewed' }"
           @click.prevent="activeTab = 'most-viewed'">
            پربازدیدترین
            <span class="absolute bottom-[-1px] right-0 w-full h-0.5 bg-indigo-600 rounded-t-full transition-transform duration-300"
                  :class="activeTab === 'most-viewed' ? 'scale-x-100' : 'scale-x-0'"></span>
        </a>
        <a class="pb-3 font-bold text-sm cursor-pointer transition-all duration-300 relative"
           :class="{ 'text-indigo-600': activeTab === 'editors-picks', 'text-gray-400 hover:text-gray-600': activeTab !== 'editors-picks' }"
           @click.prevent="activeTab = 'editors-picks'">
            پیشنهاد سردبیر
            <span class="absolute bottom-[-1px] right-0 w-full h-0.5 bg-indigo-600 rounded-t-full transition-transform duration-300"
                  :class="activeTab === 'editors-picks' ? 'scale-x-100' : 'scale-x-0'"></span>
        </a>
    </nav>

    <!-- Tab Contents -->
    <div class="relative min-h-[200px]">
        <!-- Most Commented -->
        <div x-show="activeTab === 'most-commented'"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="space-y-4">
            <?php if (!empty($sidebar['most_commented'])): ?>
                <?php foreach ($sidebar['most_commented'] as $index => $post): ?>
                    <a href="/blog/<?= $post->slug ?>" class="flex gap-4 group items-start">
                        <span class="flex-shrink-0 w-6 h-6 rounded-full bg-indigo-50 text-indigo-600 text-xs font-bold flex items-center justify-center mt-0.5 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                            <?= $index + 1 ?>
                        </span>
                        <h4 class="text-sm font-medium text-gray-700 leading-relaxed group-hover:text-indigo-600 transition-colors">
                            <?= htmlspecialchars($post->title) ?>
                        </h4>
                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="text-center py-8">
                    <svg class="w-10 h-10 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                    <p class="text-sm text-gray-400">بدون دیدگاه</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Most Viewed -->
        <div x-show="activeTab === 'most-viewed'"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             style="display: none;"
             class="space-y-4">
            <?php if (!empty($sidebar['most_viewed'])): ?>
                <?php foreach ($sidebar['most_viewed'] as $index => $post): ?>
                    <a href="/blog/<?= $post->slug ?>" class="flex gap-4 group items-start">
                        <span class="flex-shrink-0 w-6 h-6 rounded-full bg-gray-100 text-gray-500 text-xs font-bold flex items-center justify-center mt-0.5 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                            <?= $index + 1 ?>
                        </span>
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 leading-relaxed group-hover:text-indigo-600 transition-colors">
                                <?= htmlspecialchars($post->title) ?>
                            </h4>
                            <span class="text-xs text-gray-400 mt-1 block"><?= number_format($post->views_count ?? 0) ?> بازدید</span>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center text-sm text-gray-400 py-8">موردی یافت نشد.</p>
            <?php endif; ?>
        </div>

        <!-- Editor's Picks -->
        <div x-show="activeTab === 'editors-picks'"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             style="display: none;"
             class="space-y-4">
            <?php if (!empty($sidebar['editors_picks'])): ?>
                <?php foreach ($sidebar['editors_picks'] as $post): ?>
                    <a href="/blog/<?= $post->slug ?>" class="block group">
                        <div class="aspect-w-16 aspect-h-9 bg-gray-100 rounded-lg overflow-hidden mb-3">
                            <img src="<?= $post->image_url ?? 'https://placehold.co/400x300/EEE/31343C?text=Image' ?>"
                                 class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500"
                                 alt="<?= htmlspecialchars($post->title) ?>">
                        </div>
                        <h4 class="text-sm font-bold text-gray-800 leading-relaxed group-hover:text-indigo-600 transition-colors">
                            <?= htmlspecialchars($post->title) ?>
                        </h4>
                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center text-sm text-gray-400 py-8">موردی یافت نشد.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
