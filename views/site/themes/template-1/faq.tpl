<?php include 'header.tpl'; ?>
<main class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">سوالات متداول</h1>
    <div class="space-y-4">
        <?php foreach ($faqs as $faq): ?>
            <div class="border rounded-lg p-4">
                <h3 class="font-bold text-lg"><?php echo $faq['question']; ?></h3>
                <p class="mt-2 text-gray-600"><?php echo $faq['answer']; ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</main>
<?php include 'footer.tpl'; ?>
