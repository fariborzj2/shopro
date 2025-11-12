<?php include 'header.tpl'; ?>

<div class="max-w-4xl mx-auto bg-white p-8 md:p-12 rounded-2xl shadow-sm">
    <div class="prose prose-lg max-w-none text-justify">
        <h1 class="text-3xl font-extrabold text-gray-900 mb-6 text-center"><?php echo $page_title; ?></h1>

        <div>
            <?php echo $page_content; ?>
        </div>
    </div>
</div>

<?php include 'footer.tpl'; ?>
