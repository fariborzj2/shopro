<?php
$im = imagecreatetruecolor(120, 20);
$text_color = imagecolorallocate($im, 233, 14, 91);
$font = __DIR__ . '/public/fonts/kaman-Regular.otf';
if (!file_exists($font)) {
    die("Font not found: $font");
}
imagettftext($im, 20, 0, 10, 20, $text_color, $font, 'Testing...');
echo "GD OK";
imagedestroy($im);
