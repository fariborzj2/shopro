<?php
// Default values
$code = $code ?? 500;
$title = $title ?? 'خطای سرور';
$message = $message ?? 'متاسفانه مشکلی در سمت سرور رخ داده است.';
$debug_info = $debug_info ?? null;

// Determine if debug mode is on (you should define this constant in your config)
$isDebug = defined('DEBUG_MODE') && DEBUG_MODE === true;
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@400;700;900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Vazirmatn', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200">
    <div class="min-h-screen flex flex-col items-center justify-center px-4">
        <div class="max-w-2xl w-full text-center">
            <h1 class="text-8xl md:text-9xl font-black text-primary-600 dark:text-primary-500" style="color: #2563eb;"><?= htmlspecialchars($code) ?></h1>
            <h2 class="mt-4 text-3xl md:text-4xl font-bold text-gray-900 dark:text-white"><?= htmlspecialchars($title) ?></h2>
            <p class="mt-4 text-lg text-gray-600 dark:text-gray-400"><?= htmlspecialchars($message) ?></p>

            <div class="mt-10">
                <a href="/" class="px-8 py-3 text-sm font-bold text-white bg-primary-600 rounded-xl hover:bg-primary-700 transition-colors" style="background-color: #2563eb;">
                    بازگشت به صفحه اصلی
                </a>
            </div>

            <?php if ($isDebug && $debug_info): ?>
                <div class="mt-12 bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                    <h3 class="text-xl font-bold mb-4 text-red-500">اطلاعات دیباگ</h3>
                    <div class="space-y-4">
                        <div>
                            <strong class="block text-gray-700 dark:text-gray-300">پیام:</strong>
                            <p dir="ltr" class="text-left mt-1 p-2 bg-gray-50 dark:bg-gray-700 rounded-md text-sm font-mono"><?= htmlspecialchars($debug_info['message']) ?></p>
                        </div>
                        <div>
                            <strong class="block text-gray-700 dark:text-gray-300">فایل:</strong>
                            <p dir="ltr" class="text-left mt-1 p-2 bg-gray-50 dark:bg-gray-700 rounded-md text-sm font-mono"><?= htmlspecialchars($debug_info['file']) ?> (خط <?= htmlspecialchars($debug_info['line']) ?>)</p>
                        </div>
                        <div>
                            <strong class="block text-gray-700 dark:text-gray-300">Stack Trace:</strong>
                            <pre dir="ltr" class="text-left mt-1 p-3 bg-gray-50 dark:bg-gray-700 rounded-md text-xs font-mono overflow-x-auto custom-scrollbar"><?= htmlspecialchars($debug_info['trace']) ?></pre>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
