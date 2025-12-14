<?php include __DIR__ . '/../header.tpl'; ?>
<main class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6"><?php echo $post['title']; ?></h1>
    <div class="prose max-w-none">
        <?php echo $post['content']; ?>
    </div>
</main>
<?php include __DIR__ . '/../footer.tpl'; ?>
