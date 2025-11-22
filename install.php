<?php
// install.php

// Prevent re-installation if lock file exists
if (file_exists('install.lock')) {
    die('<div style="font-family: sans-serif; text-align: center; margin-top: 50px; direction: rtl;">نصب قبلاً انجام شده است. برای نصب مجدد، فایل install.lock را حذف کنید.</div>');
}

// Handle AJAX Requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');
    $action = $_POST['action'];

    try {
        switch ($action) {
            case 'test_db':
                // Test Database Connection
                $dsn = "mysql:host={$_POST['db_host']};dbname={$_POST['db_name']}";
                $pdo = new PDO($dsn, $_POST['db_user'], $_POST['db_pass']);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Create config.php
                $config_content = "<?php\n\nreturn [\n    'database' => [\n        'host' => '{$_POST['db_host']}',\n        'port' => 3306,\n        'dbname' => '{$_POST['db_name']}',\n        'user' => '{$_POST['db_user']}',\n        'password' => '{$_POST['db_pass']}',\n        'charset' => 'utf8mb4'\n    ]\n];\n";
                if (file_put_contents('config.php', $config_content) === false) {
                    throw new Exception('خطا در ایجاد فایل config.php. لطفاً دسترسی نوشتن را بررسی کنید.');
                }

                echo json_encode(['success' => true]);
                break;

            case 'run_migration':
                // Run Schema Migration
                $config = require 'config.php';
                $dsn = "mysql:host={$config['database']['host']};dbname={$config['database']['dbname']}";
                $pdo = new PDO($dsn, $config['database']['user'], $config['database']['password']);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                if (!file_exists('schema.sql')) {
                    throw new Exception('فایل schema.sql یافت نشد.');
                }

                $sql = file_get_contents('schema.sql');
                $pdo->exec($sql);

                echo json_encode(['success' => true]);
                break;

            case 'create_admin':
                // Create Super Admin
                $config = require 'config.php';
                $dsn = "mysql:host={$config['database']['host']};dbname={$config['database']['dbname']}";
                $pdo = new PDO($dsn, $config['database']['user'], $config['database']['password']);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                if ($_POST['password'] !== $_POST['confirm_password']) {
                    throw new Exception('رمز عبور و تکرار آن مطابقت ندارند.');
                }

                $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);

                // Permissions for Super Admin (Full Access)
                $permissions = json_encode(['all']);

                $sql = "INSERT INTO admins (name, username, email, password_hash, role, is_super_admin, permissions, status)
                        VALUES (:name, :username, :email, :password_hash, :role, :is_super_admin, :permissions, :status)";

                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'name' => $_POST['name'],
                    'username' => $_POST['username'],
                    'email' => $_POST['email'],
                    'password_hash' => $password_hash,
                    'role' => 'super_admin',
                    'is_super_admin' => 1,
                    'permissions' => $permissions,
                    'status' => 'active'
                ]);

                // Lock Installation
                file_put_contents('install.lock', 'installed');

                echo json_encode(['success' => true]);
                break;

            default:
                echo json_encode(['success' => false, 'message' => 'Invalid action']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نصب سیستم مدیریت</title>

    <!-- Tailwind CSS (Matches Admin Panel) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        gray: {
                            50: '#f8fafc', 100: '#f1f5f9', 200: '#e2e8f0', 300: '#cbd5e1',
                            400: '#94a3b8', 500: '#64748b', 600: '#475569', 700: '#334155',
                            800: '#1e293b', 900: '#0f172a',
                        },
                        primary: {
                            50: '#eff6ff', 100: '#dbeafe', 200: '#bfdbfe', 300: '#93c5fd',
                            400: '#60a5fa', 500: '#3b82f6', 600: '#2563eb', 700: '#1d4ed8',
                            800: '#1e40af', 900: '#1e3a8a',
                        }
                    },
                    fontFamily: {
                        sans: ['Estedad', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <!-- Font (Example, replace with local if needed) -->
    <link href="https://cdn.jsdelivr.net/gh/rastikerdar/estedad-font@v5.0.1/dist/css/style.css" rel="stylesheet">

    <style>
        body { font-family: 'Estedad', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 h-screen flex items-center justify-center overflow-hidden relative">

    <!-- Background Decorations -->
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0 pointer-events-none">
        <div class="absolute -top-[10%] -right-[10%] w-[40%] h-[40%] rounded-full bg-primary-100/50 blur-3xl"></div>
        <div class="absolute top-[20%] -left-[10%] w-[30%] h-[30%] rounded-full bg-primary-200/30 blur-3xl"></div>
        <div class="absolute -bottom-[10%] right-[20%] w-[35%] h-[35%] rounded-full bg-gray-200/50 blur-3xl"></div>
    </div>

    <div class="w-full max-w-lg mx-4 z-10" x-data="installer()">

        <!-- Header Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
            <div class="bg-gradient-to-r from-primary-600 to-primary-700 p-8 text-center text-white relative overflow-hidden">
                <div class="absolute inset-0 bg-white/5 pattern-grid-lg opacity-10"></div>
                <h1 class="text-3xl font-bold mb-2 relative z-10">نصب سیستم مدیریت</h1>
                <p class="text-primary-100 text-sm relative z-10">لطفاً مراحل زیر را با دقت طی کنید</p>

                <!-- Stepper -->
                <div class="flex justify-center items-center mt-6 relative z-10 space-x-reverse space-x-4">
                    <div class="flex flex-col items-center">
                        <div :class="step >= 1 ? 'bg-white text-primary-600' : 'bg-primary-500 text-primary-200'" class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold transition-colors duration-300">1</div>
                        <span class="text-xs mt-1 text-primary-100">دیتابیس</span>
                    </div>
                    <div class="w-12 h-0.5" :class="step >= 2 ? 'bg-white' : 'bg-primary-500'"></div>
                    <div class="flex flex-col items-center">
                        <div :class="step >= 2 ? 'bg-white text-primary-600' : 'bg-primary-500 text-primary-200'" class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold transition-colors duration-300">2</div>
                        <span class="text-xs mt-1 text-primary-100">ساختار</span>
                    </div>
                    <div class="w-12 h-0.5" :class="step >= 3 ? 'bg-white' : 'bg-primary-500'"></div>
                    <div class="flex flex-col items-center">
                        <div :class="step >= 3 ? 'bg-white text-primary-600' : 'bg-primary-500 text-primary-200'" class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold transition-colors duration-300">3</div>
                        <span class="text-xs mt-1 text-primary-100">ادمین</span>
                    </div>
                </div>
            </div>

            <div class="p-8">

                <!-- Error Alert -->
                <div x-show="error" x-transition x-cloak class="mb-6 bg-red-50 text-red-600 p-4 rounded-xl border border-red-200 flex items-start">
                    <svg class="w-5 h-5 ml-3 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span x-text="error" class="text-sm"></span>
                </div>

                <!-- Step 1: Database Config -->
                <div x-show="step === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
                    <form @submit.prevent="testDb">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">آدرس هاست (Host)</label>
                                <input type="text" x-model="db.host" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50 transition-shadow text-left" dir="ltr" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">نام دیتابیس (Database Name)</label>
                                <input type="text" x-model="db.name" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50 transition-shadow text-left" dir="ltr" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">نام کاربری (Username)</label>
                                <input type="text" x-model="db.user" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50 transition-shadow text-left" dir="ltr" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">رمز عبور (Password)</label>
                                <input type="password" x-model="db.pass" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50 transition-shadow text-left" dir="ltr">
                            </div>
                        </div>
                        <div class="mt-8">
                            <button type="submit" :disabled="loading" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-bold py-3 px-4 rounded-xl shadow-lg hover:shadow-primary-500/30 transition-all duration-200 flex justify-center items-center">
                                <span x-show="!loading">بررسی اتصال و ادامه</span>
                                <svg x-show="loading" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Step 2: Migration -->
                <div x-show="step === 2" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" class="text-center py-8">
                    <div class="mb-6">
                        <div class="w-20 h-20 bg-primary-50 text-primary-600 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800">در حال ساخت جداول دیتابیس...</h3>
                        <p class="text-gray-500 text-sm mt-2">لطفاً چند لحظه صبر کنید.</p>
                    </div>

                    <!-- Progress Bar -->
                    <div class="relative pt-1">
                        <div class="overflow-hidden h-3 mb-4 text-xs flex rounded-full bg-gray-200">
                            <div :style="`width: ${progress}%`" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-primary-500 transition-all duration-500"></div>
                        </div>
                        <div class="text-right text-sm text-gray-500" x-text="`${progress}%`"></div>
                    </div>
                </div>

                <!-- Step 3: Admin Creation -->
                <div x-show="step === 3" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
                    <form @submit.prevent="createAdmin">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">نام و نام خانوادگی</label>
                                <input type="text" x-model="admin.name" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50 transition-shadow" placeholder="مثال: مدیر سیستم" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">نام کاربری (Login Username)</label>
                                <input type="text" x-model="admin.username" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50 transition-shadow text-left" dir="ltr" placeholder="admin" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">ایمیل</label>
                                <input type="email" x-model="admin.email" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50 transition-shadow text-left" dir="ltr" placeholder="admin@example.com" required>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">رمز عبور</label>
                                    <input type="password" x-model="admin.password" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50 transition-shadow text-left" dir="ltr" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">تکرار رمز عبور</label>
                                    <input type="password" x-model="admin.confirm_password" class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50 transition-shadow text-left" dir="ltr" required>
                                </div>
                            </div>
                        </div>
                        <div class="mt-8">
                            <button type="submit" :disabled="loading" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-bold py-3 px-4 rounded-xl shadow-lg hover:shadow-primary-500/30 transition-all duration-200 flex justify-center items-center">
                                <span x-show="!loading">تکمیل نصب</span>
                                <svg x-show="loading" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Step 4: Success -->
                <div x-show="step === 4" x-cloak x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 transform scale-90" x-transition:enter-end="opacity-100 transform scale-100" class="text-center py-8">
                    <div class="w-20 h-20 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">نصب با موفقیت انجام شد!</h2>
                    <p class="text-gray-600 mb-8">هم‌اکنون می‌توانید وارد پنل مدیریت شوید.</p>
                    <a href="/admin/login" class="inline-block w-full bg-primary-600 hover:bg-primary-700 text-white font-bold py-3 px-4 rounded-xl shadow-lg hover:shadow-primary-500/30 transition-all duration-200">
                        ورود به پنل مدیریت
                    </a>
                </div>

            </div>

            <!-- Footer -->
            <div class="bg-gray-50 px-8 py-4 text-center border-t border-gray-100">
                <p class="text-xs text-gray-400">نسخه ۱.۰.۰ &copy; <?php echo date('Y'); ?></p>
            </div>
        </div>
    </div>

    <script>
        function installer() {
            return {
                step: 1,
                loading: false,
                error: null,
                progress: 0,
                db: { host: 'localhost', name: '', user: '', pass: '' },
                admin: { name: '', username: '', email: '', password: '', confirm_password: '' },

                async testDb() {
                    this.loading = true;
                    this.error = null;
                    try {
                        const formData = new FormData();
                        formData.append('action', 'test_db');
                        formData.append('db_host', this.db.host);
                        formData.append('db_name', this.db.name);
                        formData.append('db_user', this.db.user);
                        formData.append('db_pass', this.db.pass);

                        const res = await fetch('install.php', { method: 'POST', body: formData });
                        const data = await res.json();

                        if (data.success) {
                            this.step = 2;
                            this.runMigration();
                        } else {
                            this.error = data.message;
                        }
                    } catch (e) {
                        this.error = 'خطا در ارتباط با سرور';
                    } finally {
                        this.loading = false;
                    }
                },

                async runMigration() {
                    this.loading = true;
                    // Simulate progress for better UX
                    let p = 0;
                    const interval = setInterval(() => {
                        if (p < 90) { p += 5; this.progress = p; }
                    }, 200);

                    try {
                        const formData = new FormData();
                        formData.append('action', 'run_migration');
                        const res = await fetch('install.php', { method: 'POST', body: formData });
                        const data = await res.json();

                        if (data.success) {
                            clearInterval(interval);
                            this.progress = 100;
                            setTimeout(() => {
                                this.step = 3;
                                this.loading = false;
                            }, 800);
                        } else {
                            clearInterval(interval);
                            this.error = data.message;
                            this.loading = false;
                        }
                    } catch (e) {
                        clearInterval(interval);
                        this.error = 'خطا در اجرای مایگریشن';
                        this.loading = false;
                    }
                },

                async createAdmin() {
                    if (this.admin.password !== this.admin.confirm_password) {
                        this.error = 'رمز عبور و تکرار آن مطابقت ندارند.';
                        return;
                    }

                    this.loading = true;
                    this.error = null;
                    try {
                        const formData = new FormData();
                        formData.append('action', 'create_admin');
                        formData.append('name', this.admin.name);
                        formData.append('username', this.admin.username);
                        formData.append('email', this.admin.email);
                        formData.append('password', this.admin.password);
                        formData.append('confirm_password', this.admin.confirm_password);

                        const res = await fetch('install.php', { method: 'POST', body: formData });
                        const data = await res.json();

                        if (data.success) {
                            this.step = 4;
                        } else {
                            this.error = data.message;
                        }
                    } catch (e) {
                        this.error = 'خطا در ایجاد حساب کاربری';
                    } finally {
                        this.loading = false;
                    }
                }
            }
        }
    </script>
</body>
</html>
