<?php include 'header.tpl'; ?>

<main class="flex-grow bg-gray-50 py-16">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 text-center mb-12 tracking-tight">
            سوالات متداول
        </h1>

        <div x-data="{ activeTab: '<?php echo array_key_first($faq_items_grouped); ?>' }">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-4" aria-label="Tabs">
                    <?php foreach ($faq_types as $type): ?>
                        <?php if (isset($faq_items_grouped[$type['key']])): ?>
                            <button @click="activeTab = '<?php echo $type['key']; ?>'"
                                    :class="{
                                        'border-primary-500 text-primary-600': activeTab === '<?php echo $type['key']; ?>',
                                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== '<?php echo $type['key']; ?>'
                                    }"
                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                                <?php echo $type['label_fa']; ?>
                            </button>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </nav>
            </div>

            <div class="mt-8">
                <?php foreach ($faq_items_grouped as $type => $items): ?>
                    <div x-show="activeTab === '<?php echo $type; ?>'" x-cloak>
                        <div x-data="{ openItem: null }" class="space-y-4">
                            <?php foreach ($items as $index => $item): ?>
                                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden transition-all duration-300 hover:shadow-md">
                                    <button
                                        @click="openItem = (openItem === '<?php echo $item['id']; ?>' ? null : '<?php echo $item['id']; ?>')"
                                        class="w-full flex justify-between items-center p-5 text-right focus:outline-none"
                                    >
                                        <span class="text-lg font-bold text-gray-900 leading-snug">
                                            <?php echo htmlspecialchars($item['question']); ?>
                                        </span>
                                        <span
                                            class="flex-shrink-0 mr-4 ml-0 w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center transition-all duration-300"
                                            :class="openItem === '<?php echo $item['id']; ?>' ? 'bg-primary-100 text-primary-600 rotate-45' : 'text-gray-400'"
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
                                        <div class="prose prose-sm prose-slate max-w-none text-gray-600 leading-relaxed border-t border-gray-50 pt-4">
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