<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo csrf_token(); ?>">
    <title><?php echo isset($title) ? htmlspecialchars($title) . ' | پنل مدیریت' : 'پنل مدیریت'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/admin.css">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

    <script>
        // Global AJAX setup to include CSRF token in headers
        document.addEventListener('alpine:init', () => {
            const originalFetch = window.fetch;
            window.fetch = function(url, options) {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                if (options && options.method && options.method.toUpperCase() !== 'GET') {
                    if (!options.headers) {
                        options.headers = {};
                    }
                    if (!(options.body instanceof FormData)) {
                         options.headers['Content-Type'] = 'application/json';
                    }
                    options.headers['X-CSRF-TOKEN'] = csrfToken;
                }
                return originalFetch(url, options);
            };
        });
    </script>
</head>
<body class="bg-gray-100 font-sans">
    <div x-data="{ sidebarOpen: false }" class="flex h-screen bg-gray-200">
        <!-- Sidebar -->
        <?php partial('sidebar'); ?>

        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Navbar -->
            <?php partial('navbar'); ?>

            <!-- Main content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-200">
                <div class="container mx-auto px-6 py-8">
                    <h3 class="text-gray-700 text-3xl font-medium"><?php echo isset($title) ? htmlspecialchars($title) : ''; ?></h3>

                    <?php if (isset($_GET['error_msg'])): ?>
                        <div class="mt-4 p-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <strong class="font-bold">خطا!</strong>
                            <span class="block sm:inline"><?= htmlspecialchars(urldecode($_GET['error_msg'])) ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($_GET['success_msg'])): ?>
                        <div class="mt-4 p-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <strong class="font-bold">موفقیت!</strong>
                            <span class="block sm:inline"><?= htmlspecialchars(urldecode($_GET['success_msg'])) ?></span>
                        </div>
                    <?php endif; ?>

                    <!-- Main content goes here -->
                    <?php echo $content; ?>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
