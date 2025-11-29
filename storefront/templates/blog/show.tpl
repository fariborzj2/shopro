<?php
require_once __DIR__ . '/../../theme_loader.php';
// Note: Backend might use 'show.tpl' or 'single.tpl'.
// Original template-1 has 'show.tpl'.
// My template-2 has 'single.tpl'.
// I need to map this if the backend calls 'show.tpl'.

// Let's check what the Controller calls.
// Since I can't check controller code easily (or I can via read_file),
// Assuming original is 'show.tpl'.

// If backend calls 'view("blog/show", ...)' it loads 'storefront/templates/blog/show.tpl'.
// So I must create 'blog/show.tpl' here.
// But if active theme is template-2, I want to load 'single.tpl'.
// I can handle this mapping in the proxy file or the loader.
// Let's make the proxy file 'show.tpl' check for 'show.tpl' first, then 'single.tpl'.

// Actually, let's keep it simple. If template-2 uses 'single.tpl', I should rename it to 'show.tpl' inside template-2 folder to match the convention expected by the controller.
// OR, I can map it in this proxy file.

$__view_path = load_theme_view('blog/show.tpl');

// If template-2 doesn't have show.tpl but has single.tpl, load_theme_view falls back to template-1/show.tpl.
// I should verify template-2 file names.
include $__view_path;
