<?php if (isset($_GET['error_msg'])): ?>
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
        <p><?= htmlspecialchars(urldecode($_GET['error_msg'])) ?></p>
    </div>
<?php endif; ?>
