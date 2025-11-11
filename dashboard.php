<?php
session_start();

// Protect this page: if the user is not logged in, redirect to the login page.
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>داشبورد</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <nav class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <h1 class="text-xl font-bold text-indigo-600">پنل کاربری</h1>
                    </div>
                </div>
                <div class="flex items-center">
                    <a href="logout.php" class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-md text-sm font-medium">خروج</a>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <div class="bg-white shadow-md rounded-lg p-8">
                <h2 class="text-2xl font-bold mb-4">خوش آمدید!</h2>
                <p class="text-gray-700">شما با موفقیت وارد حساب کاربری خود با شماره موبایل <span class="font-semibold text-indigo-700"><?php echo htmlspecialchars($_SESSION['mobile']); ?></span> شده‌اید.</p>
                <p class="mt-4">این یک صفحه محافظت شده است.</p>
            </div>
        </div>
    </main>

</body>
</html>
