<?php
// install.php

// تابع برای بررسی وجود فایل install.lock و جلوگیری از اجرای مجدد
if (file_exists('install.lock')) {
    die('نصب قبلاً انجام شده است. برای نصب مجدد، فایل install.lock را حذف کنید.');
}

// تابع برای پردازش درخواست‌های AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');
    $action = $_POST['action'];

    switch ($action) {
        case 'test_db':
            // اعتبارسنجی و تست اتصال دیتابیس
            try {
                $pdo = new PDO("mysql:host={$_POST['db_host']};dbname={$_POST['db_name']}", $_POST['db_user'], $_POST['db_pass']);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // ایجاد فایل config.php
                $config_content = "<?php\n\nreturn [\n    'database' => [\n        'host' => '{$_POST['db_host']}',\n        'port' => 3306,\n        'dbname' => '{$_POST['db_name']}',\n        'user' => '{$_POST['db_user']}',\n        'password' => '{$_POST['db_pass']}',\n        'charset' => 'utf8mb4'\n    ]\n];\n";
                file_put_contents('config.php', $config_content);

                echo json_encode(['success' => true]);
            } catch (PDOException $e) {
                echo json_encode(['success' => false, 'message' => 'خطا در اتصال به دیتابیس: ' . $e->getMessage()]);
            }
            exit;

        case 'run_migration':
            // اجرای اسکریپت SQL برای ساخت جداول
            try {
                $config = require 'config.php';
                $pdo = new PDO("mysql:host={$config['database']['host']};dbname={$config['database']['dbname']}", $config['database']['user'], $config['database']['password']);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $sql = file_get_contents('schema.sql');
                $pdo->exec($sql);

                echo json_encode(['success' => true]);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => 'خطا در ساخت جداول: ' . $e->getMessage()]);
            }
            exit;

        case 'create_admin':
            // ایجاد حساب ادمین
            try {
                $config = require 'config.php';
                $pdo = new PDO("mysql:host={$config['database']['host']};dbname={$config['database']['dbname']}", $config['database']['user'], $config['database']['password']);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $sql = "INSERT INTO admins (name, username, email, password_hash) VALUES (:name, :username, :email, :password_hash)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'name' => $_POST['name'],
                    'username' => $_POST['username'],
                    'email' => $_POST['email'],
                    'password_hash' => $password_hash
                ]);

                // ایجاد فایل install.lock
                file_put_contents('install.lock', 'installed');

                echo json_encode(['success' => true]);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => 'خطا در ایجاد حساب ادمین: ' . $e->getMessage()]);
            }
            exit;
    }
}

?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نصب پروژه</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
    <style>
        body {
            font-family: 'Vazirmatn', sans-serif;
        }
        .container {
            max-width: 600px;
            margin-top: 50px;
        }
        .progress-bar {
            transition: width 0.5s ease-in-out;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center mb-4">نصب پروژه</h1>

        <!-- Step 1: Database Configuration -->
        <div id="step-1">
            <h2>مرحله ۱: تنظیمات دیتابیس</h2>
            <form id="db-form">
                <div class="mb-3">
                    <label for="db_host" class="form-label">هاست دیتابیس</label>
                    <input type="text" class="form-control" id="db_host" name="db_host" required>
                </div>
                <div class="mb-3">
                    <label for="db_name" class="form-label">نام دیتابیس</label>
                    <input type="text" class="form-control" id="db_name" name="db_name" required>
                </div>
                <div class="mb-3">
                    <label for="db_user" class="form-label">نام کاربری دیتابیس</label>
                    <input type="text" class="form-control" id="db_user" name="db_user" required>
                </div>
                <div class="mb-3">
                    <label for="db_pass" class="form-label">رمز عبور دیتابیس</label>
                    <input type="password" class="form-control" id="db_pass" name="db_pass">
                </div>
                <button type="submit" class="btn btn-primary">تأیید و ادامه</button>
            </form>
            <div id="db-error" class="alert alert-danger mt-3 d-none"></div>
        </div>

        <!-- Step 2: Database Migration -->
        <div id="step-2" class="d-none">
            <h2>مرحله ۲: ساخت جداول</h2>
            <div class="progress">
                <div id="progress-bar" class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
            </div>
        </div>

        <!-- Step 3: Admin Account Creation -->
        <div id="step-3" class="d-none">
            <h2>مرحله ۳: ایجاد حساب ادمین</h2>
            <form id="admin-form">
                <div class="mb-3">
                    <label for="name" class="form-label">نام فارسی ادمین</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="username" class="form-label">نام کاربری انگلیسی</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">ایمیل ادمین</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">رمز عبور</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary">ایجاد ادمین</button>
            </form>
            <div id="admin-error" class="alert alert-danger mt-3 d-none"></div>
        </div>

        <!-- Step 4: Success Message -->
        <div id="step-4" class="d-none text-center">
            <h2 class="text-success">نصب با موفقیت انجام شد!</h2>
            <a href="/admin/login" class="btn btn-primary mt-3">ورود به پنل مدیریت</a>
        </div>
    </div>

    <script>
        document.getElementById('db-form').addEventListener('submit', async function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            formData.append('action', 'test_db');

            const response = await fetch('install.php', {
                method: 'POST',
                body: formData
            });
            const result = await response.json();

            if (result.success) {
                document.getElementById('step-1').classList.add('d-none');
                document.getElementById('step-2').classList.remove('d-none');
                runMigration();
            } else {
                const dbError = document.getElementById('db-error');
                dbError.textContent = result.message;
                dbError.classList.remove('d-none');
            }
        });

        async function runMigration() {
            const progressBar = document.getElementById('progress-bar');
            let width = 0;
            const interval = setInterval(() => {
                if (width >= 90) {
                    clearInterval(interval);
                } else {
                    width++;
                    progressBar.style.width = width + '%';
                    progressBar.textContent = width + '%';
                }
            }, 10);

            const formData = new FormData();
            formData.append('action', 'run_migration');

            const response = await fetch('install.php', {
                method: 'POST',
                body: formData
            });
            const result = await response.json();

            if (result.success) {
                clearInterval(interval);
                progressBar.style.width = '100%';
                progressBar.textContent = '100%';
                setTimeout(() => {
                    document.getElementById('step-2').classList.add('d-none');
                    document.getElementById('step-3').classList.remove('d-none');
                }, 500);
            } else {
                clearInterval(interval);
                alert('خطا در ساخت جداول: ' + result.message);
            }
        }

        document.getElementById('admin-form').addEventListener('submit', async function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            formData.append('action', 'create_admin');

            const response = await fetch('install.php', {
                method: 'POST',
                body: formData
            });
            const result = await response.json();

            if (result.success) {
                document.getElementById('step-3').classList.add('d-none');
                document.getElementById('step-4').classList.remove('d-none');
            } else {
                const adminError = document.getElementById('admin-error');
                adminError.textContent = result.message;
                adminError.classList.remove('d-none');
            }
        });
    </script>
</body>
</html>
