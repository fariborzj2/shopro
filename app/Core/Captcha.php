<?php

namespace App\Core;

class Captcha
{
    public static function generate()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $random_num = rand(1000, 9999);
        $_SESSION['captcha'] = $random_num;

        $font = __DIR__ . '/../../public/fonts/kaman-Regular.otf';
        $image = imagecreatetruecolor(120, 40);
        $black = imagecolorallocate($image, 0, 0, 0);
        $white = imagecolorallocate($image, 255, 255, 255);

        imagefill($image, 0, 0, $white);
        imagettftext($image, 20, 0, 10, 30, $black, $font, self::toPersianNumbers($random_num));

        ob_start();
        imagepng($image);
        $image_data = ob_get_clean();
        imagedestroy($image);

        return 'data:image/png;base64,' . base64_encode($image_data);
    }

    private static function toPersianNumbers($number)
    {
        $persian_digits = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $english_digits = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        return str_replace($english_digits, $persian_digits, $number);
    }
}
