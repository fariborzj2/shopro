<?php include 'header.tpl'; ?>
<main class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6"><?php echo $page->title; ?></h1>
    <div class="prose max-w-none">
        <?php echo $page->content; ?>
    </div>
</main>
<?php include 'footer.tpl'; ?>
