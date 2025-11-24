<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'فروشگاه مدرن'; ?></title>

    <meta name="csrf-token" content="<?php echo csrf_token(); ?>">

    <meta name="description" content="<?php echo $metaDescription ?? 'توضیحات پیش‌فرض سایت'; ?>">
    <?php if (isset($canonicalUrl)): ?>
        <link rel="canonical" href="<?php echo $canonicalUrl; ?>">
    <?php endif; ?>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <?php if (isset($schema_data)): ?>
        <?php foreach ($schema_data as $schema): if($schema): ?>
            <script type="application/ld+json"><?php echo json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?></script>
        <?php endif; endforeach; ?>
    <?php endif; ?>

    <style>
        :root {
            /* Colors */
            --color-bg-body: #f8fafc;
            --color-bg-surface: rgba(255, 255, 255, 0.65);
            --color-bg-surface-hover: rgba(255, 255, 255, 0.85);
            --color-border: rgba(255, 255, 255, 0.4);
            --color-primary: #3b82f6;
            --color-primary-hover: #2563eb;
            --color-danger: #ef4444;
            --color-success: #10b981;
            --color-text-main: #1e293b;
            --color-text-muted: #64748b;

            /* Typography */
            --font-primary: 'Estedad', system-ui, -apple-system, sans-serif;

            /* Spacing & Radius */
            --radius-md: 0.75rem;
            --radius-lg: 1.25rem;
            --spacing-container: 2rem;

            /* Effects */
            --shadow-glass: 0 8px 32px 0 rgba(31, 38, 135, 0.07);
            --backdrop-blur: blur(16px);
            --transition-smooth: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Reset & Base */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: var(--font-primary);
            background-color: var(--color-bg-body);
            color: var(--color-text-main);
            line-height: 1.8;
            letter-spacing: -0.01em;
            overflow-x: hidden;
            background-image:
                radial-gradient(at 0% 0%, hsla(253,16%,7%,0) 0, hsla(253,16%,7%,0) 50%),
                radial-gradient(at 50% 0%, hsla(225,39%,30%,0) 0, hsla(225,39%,30%,0) 50%),
                radial-gradient(at 100% 0%, hsla(339,49%,30%,0) 0, hsla(339,49%,30%,0) 50%);
        }

        a { text-decoration: none; color: inherit; transition: var(--transition-smooth); }
        button { font-family: inherit; border: none; cursor: pointer; background: none; }
        ul { list-style: none; }

        /* Utility Classes (Replacing Tailwind) */
        .container {
            max-width: 1280px;
            margin-inline: auto;
            padding-inline: var(--spacing-container);
        }

        .glass-panel {
            background: var(--color-bg-surface);
            backdrop-filter: var(--backdrop-blur);
            -webkit-backdrop-filter: var(--backdrop-blur);
            border: 1px solid var(--color-border);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-glass);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.75rem 1.5rem;
            border-radius: var(--radius-md);
            font-weight: 600;
            transition: var(--transition-smooth);
            font-size: 0.95rem;
        }

        .btn-primary {
            background: var(--color-primary);
            color: white;
            box-shadow: 0 4px 14px 0 rgba(59, 130, 246, 0.39);
        }
        .btn-primary:hover {
            background: var(--color-primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px 0 rgba(59, 130, 246, 0.23);
        }

        .btn-danger {
            background: var(--color-danger);
            color: white;
        }
        .btn-danger:hover { filter: brightness(1.1); }

        .btn-ghost {
            background: rgba(255, 255, 255, 0.5);
            color: var(--color-text-main);
            border: 1px solid var(--color-border);
        }
        .btn-ghost:hover {
            background: var(--color-bg-surface-hover);
            transform: translateY(-2px);
        }

        /* Header Styles */
        .site-header {
            position: sticky;
            top: 1rem;
            z-index: 50;
            margin-block-end: 3rem;
        }

        .site-header__inner {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 2rem;
            border-radius: var(--radius-lg);
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: var(--backdrop-blur);
            border: 1px solid rgba(255,255,255,0.5);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.05);
        }

        .brand-logo {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--color-text-main);
            letter-spacing: -0.03em;
        }

        .header-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        /* Footer Styles (Pre-definition) */
        .site-footer {
            margin-block-start: 5rem;
            padding-block: 4rem 2rem;
            background: linear-gradient(180deg, rgba(255,255,255,0) 0%, rgba(255,255,255,0.8) 100%);
            border-top: 1px solid rgba(255,255,255,0.3);
        }

        .footer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 3rem;
            margin-block-end: 3rem;
        }

        .footer-heading {
            font-size: 1.1rem;
            font-weight: 700;
            margin-block-end: 1.5rem;
            color: var(--color-text-main);
        }

        .footer-link {
            display: block;
            color: var(--color-text-muted);
            margin-block-end: 0.8rem;
            transition: var(--transition-smooth);
        }
        .footer-link:hover {
            color: var(--color-primary);
            padding-inline-start: 0.5rem;
        }

        /* Modal Styles */
        [x-cloak] { display: none !important; }

        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(4px);
            z-index: 100;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: white;
            width: 100%;
            max-width: 400px;
            padding: 2.5rem;
            border-radius: 2rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            border: 1px solid rgba(255,255,255,0.5);
        }

        .form-input {
            width: 100%;
            padding: 0.875rem;
            border: 2px solid #e2e8f0;
            border-radius: var(--radius-md);
            font-size: 1rem;
            text-align: center;
            transition: var(--transition-smooth);
            background: #f8fafc;
        }
        .form-input:focus {
            outline: none;
            border-color: var(--color-primary);
            background: white;
        }
    </style>
</head>
<body x-data class="no-scrollbar">

    <div class="container">

        <!-- ===== Header ===== -->
        <header class="site-header">
            <div class="site-header__inner">
                <div class="brand">
                    <a href="/" class="brand-logo">فروشگاه مدرن</a>
                </div>

                <nav class="header-actions">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="/dashboard/orders" class="btn btn-ghost">
                            داشبورد
                        </a>
                        <a href="/logout" class="btn btn-danger">
                            خروج
                        </a>
                    <?php else: ?>
                        <a href="#" @click.prevent="$dispatch('open-auth-modal')" class="btn btn-primary">
                            ورود / ثبت‌نام
                        </a>
                    <?php endif; ?>
                </nav>
            </div>
        </header>

        <main>
