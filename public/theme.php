<?php
$skin = $_GET['skin'] ?? '';
$allowed = ['template-1', 'template-2'];

if (in_array($skin, $allowed)) {
    // Set cookie for 30 days, available to entire domain
    setcookie('site_theme', $skin, time() + (86400 * 30), "/");
}

// Redirect back to the previous page or homepage
$redirect = $_SERVER['HTTP_REFERER'] ?? '/';
// Prevent redirect loops if referrer is theme.php (unlikely but safe)
if (strpos($redirect, 'theme.php') !== false) {
    $redirect = '/';
}

header("Location: $redirect");
exit;
