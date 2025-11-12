<?php include 'header.tpl'; ?>

<div class="max-w-3xl mx-auto">
    <h1 class="text-3xl font-extrabold text-gray-900 mb-8 text-center">پاسخ به پرسش‌های شما</h1>

    <div x-data="{ open: null }" class="space-y-4">
        <?php foreach ($faq_items as $index => $item): ?>
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <button @click="open = (open === <?= $index ?> ? null : <?= $index ?>)" class="w-full flex justify-between items-center text-right font-semibold text-lg">
                    <span><?= htmlspecialchars($item['question']) ?></span>
                    <span x-show="open !== <?= $index ?>" class="transform transition-transform duration-300">&plus;</span>
                    <span x-show="open === <?= $index ?>" class="transform transition-transform duration-300 rotate-45">&plus;</span>
                </button>
                <div x-show="open === <?= $index ?>" x-collapse.duration.500ms class="mt-4 text-gray-600 prose">
                    <p><?= nl2br(htmlspecialchars($item['answer'])) ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include 'footer.tpl'; ?>
