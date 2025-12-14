<!DOCTYPE html>
<html lang="fa" dir="rtl" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo $meta_description ?? ''; ?>">
    <meta name="keywords" content="<?php echo $meta_keywords ?? ''; ?>">
    <meta name="csrf-token" content="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
    <title><?php echo $title ?? 'فروشگاه'; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        @font-face { font-family: 'Estedad'; src: url('/fonts/Estedad-Regular.woff2'); }
        body { font-family: 'Estedad', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800" x-data="store({})">
    <header class="bg-white shadow-sm sticky top-0 z-50">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <a href="/" class="text-2xl font-bold text-primary-600">فروشگاه</a>
            <nav class="hidden md:flex gap-6">
                <a href="/" class="hover:text-primary-600">خانه</a>
                <a href="/blog" class="hover:text-primary-600">وبلاگ</a>
            </nav>
        </div>
    </header>
