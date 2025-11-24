<?php include 'header.tpl'; ?>

<style>
    .faq-container {
        max-width: 800px;
        margin-inline: auto;
        padding-block: 4rem;
    }

    .faq-title {
        text-align: center;
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 3rem;
        color: var(--color-text-main);
    }

    .accordion-item {
        margin-bottom: 1rem;
        border-radius: var(--radius-lg);
        background: var(--color-bg-surface);
        border: 1px solid var(--color-border);
        transition: var(--transition-smooth);
        overflow: hidden;
    }

    .accordion-item:hover {
        background: var(--color-bg-surface-hover);
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

    .accordion-header {
        width: 100%;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.25rem 1.5rem;
        font-weight: 600;
        font-size: 1.1rem;
        color: var(--color-text-main);
        text-align: right;
    }

    .accordion-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: rgba(0,0,0,0.05);
        transition: transform 0.3s ease;
        flex-shrink: 0;
        margin-right: 1rem; /* RTL: will be flipped effectively if strict RTL but safe to use margin-right visually */
    }

    .accordion-content {
        padding: 0 1.5rem 1.5rem 1.5rem;
        color: var(--color-text-muted);
        line-height: 1.7;
    }

    /* Animation classes for standard CSS transitions if x-collapse plugin is missing */
    .expand-enter-active, .expand-leave-active { transition: all 0.3s ease-out; overflow: hidden; max-height: 500px; opacity: 1; }
    .expand-enter-from, .expand-leave-to { max-height: 0; opacity: 0; padding-bottom: 0; }
</style>

<div class="faq-container">
    <h1 class="faq-title">پاسخ به پرسش‌های شما</h1>

    <div x-data="{ open: null }" class="space-y-4">
        <?php foreach ($faq_items as $index => $item): ?>
            <div class="accordion-item glass-panel">
                <button
                    @click="open = (open === <?= $index ?> ? null : <?= $index ?>)"
                    class="accordion-header"
                    aria-expanded="false"
                    :aria-expanded="open === <?= $index ?>"
                >
                    <span><?= htmlspecialchars($item['question']) ?></span>
                    <span
                        class="accordion-icon"
                        :style="open === <?= $index ?> ? 'transform: rotate(45deg); background: var(--color-primary); color: white;' : ''"
                    >
                        <svg width="12" height="12" viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="6" x2="0" y2="6"></line><line x1="6" y1="0" x2="6" y2="12"></line></svg>
                    </span>
                </button>

                <div
                    x-show="open === <?= $index ?>"
                    style="display: none;"
                    x-transition:enter="collapse-enter-active"
                    x-transition:enter-start="collapse-enter-from"
                    x-transition:enter-end="collapse-enter-to"
                    x-transition:leave="collapse-leave-active"
                    x-transition:leave-start="collapse-leave-from"
                    x-transition:leave-end="collapse-leave-to"
                    class="accordion-content prose"
                >
                    <p><?= nl2br(htmlspecialchars($item['answer'])) ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- JSON-LD Schema -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "FAQPage",
    "mainEntity": [
        <?php foreach ($faq_items as $index => $item): ?>
        {
            "@type": "Question",
            "name": "<?= htmlspecialchars($item['question']) ?>",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "<?= htmlspecialchars($item['answer']) ?>"
            }
        }<?= $index < count($faq_items) - 1 ? ',' : '' ?>
        <?php endforeach; ?>
    ]
}
</script>

<?php include 'footer.tpl'; ?>
