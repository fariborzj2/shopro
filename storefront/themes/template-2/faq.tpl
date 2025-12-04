<?php include 'header.tpl'; ?>

<main class="flex-grow bg-gray-50 dark:bg-gray-900 py-16 transition-colors duration-300">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-3xl">
        <h1 class="text-3xl md:text-4xl font-black text-gray-900 dark:text-white text-center mb-12 tracking-tight">
            سوالات متداول
        </h1>

        <div x-data="{ activeTab: '<?php echo array_key_first($faq_items_grouped); ?>' }">
            <!-- Tabs -->
            <div class="border-b border-gray-200 dark:border-gray-700 mb-8 overflow-x-auto pb-1 no-scrollbar">
                <nav class="-mb-px flex space-x-2 space-x-reverse" aria-label="Tabs">
                    <?php foreach ($faq_types as $type): ?>
                        <?php if (isset($faq_items_grouped[$type['key']])): ?>
                            <button @click="activeTab = '<?php echo $type['key']; ?>'"
                                    :class="{
                                        'bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400 border-b-2 border-primary-500': activeTab === '<?php echo $type['key']; ?>',
                                        'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 border-b-2 border-transparent': activeTab !== '<?php echo $type['key']; ?>'
                                    }"
                                    class="whitespace-nowrap px-6 py-3 font-bold text-sm transition-all rounded-t-xl">
                                <?php echo $type['label_fa']; ?>
                            </button>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </nav>
            </div>

            <!-- Content -->
            <div>
                <?php foreach ($faq_items_grouped as $type => $items): ?>
                    <div x-show="activeTab === '<?php echo $type; ?>'" x-cloak>
                        <div x-data="{ openItem: null }" class="space-y-4">
                            <?php foreach ($items as $index => $item): ?>
                                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-card border border-gray-100 dark:border-gray-700 overflow-hidden transition-all duration-300 hover:shadow-lg">
                                    <button
                                        @click="openItem = (openItem === '<?php echo $item['id']; ?>' ? null : '<?php echo $item['id']; ?>')"
                                        class="w-full flex justify-between items-center p-5 text-right focus:outline-none"
                                    >
                                        <span class="text-lg font-bold text-gray-900 dark:text-white leading-snug">
                                            <?php echo htmlspecialchars($item['question']); ?>
                                        </span>
                                        <span
                                            class="flex-shrink-0 mr-4 ml-0 w-8 h-8 rounded-full bg-gray-50 dark:bg-gray-700 flex items-center justify-center transition-all duration-300"
                                            :class="openItem === '<?php echo $item['id']; ?>' ? 'bg-primary-100 dark:bg-primary-900/40 text-primary-600 dark:text-primary-400 rotate-45' : 'text-gray-400 dark:text-gray-500'"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                        </span>
                                    </button>

                                    <div
                                        x-show="openItem === '<?php echo $item['id']; ?>'"
                                        x-cloak
                                        x-transition:enter="transition ease-out duration-200"
                                        x-transition:enter-start="opacity-0 -translate-y-2"
                                        x-transition:enter-end="opacity-100 translate-y-0"
                                        x-transition:leave="transition ease-in duration-150"
                                        x-transition:leave-start="opacity-100 translate-y-0"
                                        x-transition:leave-end="opacity-0 -translate-y-2"
                                        class="px-5 pb-5 pt-0"
                                    >
                                        <div class="prose prose-sm prose-slate dark:prose-invert max-w-none text-gray-600 dark:text-gray-300 leading-relaxed border-t border-gray-100 dark:border-gray-700 pt-4">
                                            <?php echo nl2br(htmlspecialchars($item['answer'])); ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</main>

<?php include 'footer.tpl'; ?>
