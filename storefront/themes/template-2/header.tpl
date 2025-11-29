<!DOCTYPE html>
<html lang="fa" dir="rtl" x-data="{
    mobileMenuOpen: false,
    darkMode: localStorage.getItem('darkMode') === 'true',
    toggleTheme() {
        this.darkMode = !this.darkMode;
        localStorage.setItem('darkMode', this.darkMode);
        if (this.darkMode) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    }
}" :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle ?? 'تلگرام فمیلی | خرید ارزان پرمیوم تلگرام'); ?></title>
    <meta name="csrf-token" content="<?php echo csrf_token(); ?>">
    <meta name="description" content="<?php echo htmlspecialchars($metaDescription ?? 'خرید اشتراک پرمیوم تلگرام با بهترین قیمت و تحویل فوری'); ?>">
    <?php if (isset($canonicalUrl)): ?>
        <link rel="canonical" href="<?php echo $canonicalUrl; ?>">
    <?php endif; ?>

    <link rel="stylesheet" href="/template-2/css/fonts.css">
    <link rel="stylesheet" href="/template-2/css/icon.css">
    <link rel="stylesheet" href="/template-2/css/alert.css">
    <link rel="stylesheet" href="/template-2/css/swiper-bundle.min.css">
    <link rel="stylesheet" href="/template-2/css/grid.css?v=0.0.2">
    <link rel="stylesheet" href="/template-2/css/style.css?v=0.0.2">
    <!-- Pincode CSS for Auth Modal -->
    <link rel="stylesheet" href="/css/pincode.css">

    <!-- Tailwind CSS (with preflight disabled to prevent breaking template-2 styles) -->
    <script src="https://cdn.tailwindcss.com?plugins=typography,forms,aspect-ratio"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            corePlugins: {
                preflight: false, // Critical: Don't reset browser styles, preserving template-2 look
            },
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
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
    <script src="/js/error-modal.js" defer></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        /* Fixes for Tailwind w/o preflight in Modal */
        .auth-modal-reset *, .auth-modal-reset ::before, .auth-modal-reset ::after {
            box-sizing: border-box;
            border-width: 0;
            border-style: solid;
            border-color: #e5e7eb;
        }
    </style>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('auth', {
                user: <?php echo json_encode(isset($_SESSION['user_id']) ? ['id' => $_SESSION['user_id'], 'name' => $_SESSION['user_name'], 'mobile' => $_SESSION['user_mobile']] : null); ?>,
                check() {
                    return !!this.user;
                },
                login(userData) {
                    this.user = userData;
                },
                logout() {
                    this.user = null;
                    window.location.href = '/logout';
                }
            });
        });
    </script>

    <?php if (isset($schema_data)): ?>
        <?php foreach ($schema_data as $schema): if($schema): ?>
            <script type="application/ld+json"><?php echo json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?></script>
        <?php endif; endforeach; ?>
    <?php endif; ?>
</head>
<body x-data="<?php echo isset($store_data) ? "store(" . htmlspecialchars($store_data, ENT_QUOTES, 'UTF-8') . ")" : "{}"; ?>" x-init="init()">

    <div class="main">
        <!-- Menu -->
        <div class="fix-menu"></div>
        <div class="top-menu">
            <div class="center">
                <div class="menu-container">
                    <div class="d-flex align-center">

                        <div class="open-menu ml-15" role="button" tabindex="0" aria-label="Toggle Menu" aria-expanded="false">
                            <div></div>
                            <div></div>
                            <div></div>
                        </div>

                        <a href="/" class="logo" aria-label=""><img src="/template-2/images/logo.svg" width="" height="" alt=""></a>
                    </div>

                    <div class="menu toggle-slide-box">
                        <ul>
                            <li><a href="/">صفحه اصلی</a></li>
                            <!-- We can add category links dynamically if needed, but keeping it simple as per request -->
                            <li><a href="/#products">محصولات</a></li>
                            <li><a href="/blog">وبلاگ</a></li>
                            <li><a href="/page/about-us">درباره ما</a></li>
                            <li><a href="/page/contact-us">تماس با ما</a></li>
                        </ul>
                    </div>

                    <div class="">
                        <!-- Auth Button Logic -->
                        <template x-if="!$store.auth.check()">
                            <a href="#" @click.prevent="$dispatch('open-auth-modal')" class="btn-menu border color-text">
                                <i class="icon-login-3"></i> <span>ورود / ثبت نام</span>
                            </a>
                        </template>

                        <template x-if="$store.auth.check()">
                            <div class="d-flex align-center gap-2">
                                <a href="/dashboard/orders" class="btn-menu border color-text ml-2">
                                    <i class="icon-user-bold"></i> <span x-text="$store.auth.user.name || 'داشبورد'"></span>
                                </a>
                                <a href="/logout" @click.prevent="$store.auth.logout()" class="btn-menu border color-text" style="padding: 0 10px;" title="خروج">
                                    <i class="icon-logout"></i>
                                </a>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
