<?php include 'header.tpl'; ?>

<style>
    .page-container {
        max-width: 800px;
        margin-inline: auto;
        padding-block: 4rem;
    }

    .page-title {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 3rem;
        text-align: center;
        color: var(--color-text-main);
    }

    .prose-content {
        font-size: 1.1rem;
        line-height: 1.9;
        color: #334155;
    }

    .prose-content h2 { font-size: 1.75rem; margin-top: 2.5rem; margin-bottom: 1rem; font-weight: 700; color: var(--color-text-main); }
    .prose-content h3 { font-size: 1.5rem; margin-top: 2rem; margin-bottom: 0.75rem; font-weight: 600; color: var(--color-text-main); }
    .prose-content p { margin-bottom: 1.5rem; text-align: justify; }
    .prose-content ul { list-style-type: disc; padding-right: 1.5rem; margin-bottom: 1.5rem; }
    .prose-content li { margin-bottom: 0.5rem; }
    .prose-content a { color: var(--color-primary); text-decoration: underline; text-underline-offset: 4px; }
    .prose-content a:hover { color: var(--color-primary-hover); }
</style>

<article class="page-container">
    <div class="glass-panel" style="padding: 3rem;">
        <h1 class="page-title"><?php echo htmlspecialchars($page_title); ?></h1>

        <div class="prose-content">
            <?php echo $page_content; ?>
        </div>
    </div>
</article>

<!-- JSON-LD Schema -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Article",
    "headline": "<?php echo htmlspecialchars($page_title); ?>",
    "mainEntityOfPage": {
        "@type": "WebPage",
        "@id": "<?php echo (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>"
    },
    "publisher": {
        "@type": "Organization",
        "name": "فروشگاه مدرن",
        "logo": {
            "@type": "ImageObject",
            "url": "<?php echo (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST']; ?>/assets/logo.png"
        }
    }
}
</script>

<?php include 'footer.tpl'; ?>
