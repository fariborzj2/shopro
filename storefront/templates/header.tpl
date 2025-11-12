<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'فروشگاه مدرن'; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 font-sans">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- ===== Header ===== -->
        <header class="flex justify-between items-center py-6">
            <div>
                <a href="index.php" class="text-2xl font-bold text-gray-900">لوگو</a>
            </div>
            <div>
                <a href="#" class="bg-gray-800 text-white px-5 py-2 rounded-lg text-sm font-semibold hover:bg-gray-900 transition-colors">
                    ورود
                </a>
            </div>
        </header>

        <main class="py-12">
