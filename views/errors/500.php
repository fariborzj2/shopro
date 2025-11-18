<div class="container mx-auto mt-10">
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
        <strong class="font-bold">خطای سرور (500)</strong>
        <span class="block sm:inline">متاسفانه خطایی در سمت سرور رخ داده است.</span>
        <?php if (isset($message) && !empty($message)) : ?>
            <p class="mt-2 text-sm">جزئیات: <?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>
         <p class="mt-4">
            <a href="/" class="font-semibold text-indigo-600 hover:text-indigo-500">بازگشت به داشبورد &rarr;</a>
        </p>
    </div>
</div>
