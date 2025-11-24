<?php include 'header.tpl'; ?>

<main class="flex-grow bg-gray-50 py-16">
    <article class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="glass-panel bg-white/70 backdrop-blur-lg border border-white/50 rounded-2xl shadow-xl p-8 md:p-12">
            <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 text-center mb-10 tracking-tight">
                <?php echo htmlspecialchars($page_title); ?>
            </h1>

            <div class="prose prose-lg prose-slate max-w-none text-justify">
                <?php echo $page_content; ?>
            </div>
        </div>
    </article>
</main>

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
