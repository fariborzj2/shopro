<?php include 'header.tpl'; ?>

<main class="flex-grow bg-gray-50 dark:bg-gray-900 py-16 transition-colors duration-300">
    <article class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-4xl">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-card border border-gray-100 dark:border-gray-700 p-8 md:p-12">
            <h1 class="text-3xl md:text-4xl font-black text-gray-900 dark:text-white text-center mb-10 tracking-tight relative pb-6 after:content-[''] after:absolute after:bottom-0 after:left-1/2 after:-translate-x-1/2 after:w-24 after:h-1 after:bg-primary-500 after:rounded-full">
                <?php echo htmlspecialchars($page_title); ?>
            </h1>

            <div class="prose prose-lg prose-slate dark:prose-invert max-w-none text-justify leading-loose">
                <?php echo $page_content; ?>
            </div>
        </div>
    </article>
</main>

<?php include 'footer.tpl'; ?>
