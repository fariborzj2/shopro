<?php
/*--------------------------------------------
  تابع کامل و پیشرفته jdate() برای تاریخ شمسی
  بدون خروجی ناخواسته
---------------------------------------------*/

/**
 * تبدیل تاریخ جلالی به میلادی
 */
function jalali_to_gregorian($j_y, $j_m, $j_d) {
    $g_days_in_month = [31,28,31,30,31,30,31,31,30,31,30,31];
    $j_days_in_month = [31,31,31,31,31,31,30,30,30,30,30,29];

    $jy = $j_y - 979;
    $jm = $j_m - 1;
    $jd = $j_d - 1;

    $j_day_no = 365*$jy + floor($jy/33)*8 + floor((($jy%33)+3)/4);
    for($i=0;$i<$jm;++$i) $j_day_no += $j_days_in_month[$i];
    $j_day_no += $jd;

    $g_day_no = $j_day_no + 79;

    $gy = 1600 + 400*floor($g_day_no/146097);
    $g_day_no %= 146097;

    $leap = true;
    if($g_day_no >= 36525){
        $g_day_no--;
        $gy += 100*floor($g_day_no/36524);
        $g_day_no %= 36524;
        if($g_day_no >= 365) $g_day_no++; else $leap = false;
    }

    $gy += 4*floor($g_day_no/1461);
    $g_day_no %= 1461;

    if($g_day_no >= 366){
        $leap = false;
        $g_day_no--;
        $gy += floor($g_day_no/365);
        $g_day_no %= 365;
    }

    for($i=0; $g_day_no >= $g_days_in_month[$i]+($i==1 && $leap); $i++)
        $g_day_no -= $g_days_in_month[$i]+($i==1 && $leap);

    return [$gy, $i+1, $g_day_no+1];
}

/**
 * تبدیل تاریخ میلادی به جلالی
 */
function gregorian_to_jalali($g_y, $g_m, $g_d) {
    $g_days_in_month = [31,28,31,30,31,30,31,31,30,31,30,31];
    $j_days_in_month = [31,31,31,31,31,31,30,30,30,30,30,29];

    $gy = $g_y - 1600;
    $gm = $g_m - 1;
    $gd = $g_d - 1;

    $g_day_no = 365*$gy + floor(($gy+3)/4) - floor(($gy+99)/100) + floor(($gy+399)/400);
    for($i=0;$i<$gm;++$i) $g_day_no += $g_days_in_month[$i];
    if($gm>1 && (($gy%4==0 && $gy%100!=0)||($gy%400==0))) $g_day_no++;
    $g_day_no += $gd;

    $j_day_no = $g_day_no - 79;

    $j_np = floor($j_day_no/12053);
    $j_day_no %= 12053;

    $jy = 979 + 33*$j_np + 4*floor($j_day_no/1461);
    $j_day_no %= 1461;

    if($j_day_no >= 366){
        $jy += floor(($j_day_no-1)/365);
        $j_day_no = ($j_day_no-1)%365;
    }

    for($i=0; $i<11 && $j_day_no >= $j_days_in_month[$i]; ++$i)
        $j_day_no -= $j_days_in_month[$i];

    return [$jy, $i+1, $j_day_no+1];
}

/**
 * jdate — تاریخ شمسی معادل date()
 * پیشرفته و امن بدون چاپ مستقیم
 */
function jdate($format='Y-m-d H:i:s', $timestamp=null){
    if($timestamp === null) $timestamp = time();

    $gy = date('Y', $timestamp);
    $gm = date('m', $timestamp);
    $gd = date('d', $timestamp);
    list($jy, $jm, $jd) = gregorian_to_jalali($gy, $gm, $gd);

    // نام ماه و روز
    $months = ['فروردین','اردیبهشت','خرداد','تیر','مرداد','شهریور','مهر','آبان','آذر','دی','بهمن','اسفند'];
    $shortMonths = ['فرو','ارد','خرد','تیر','مر','شه','مهر','آبا','آذر','دی','بهم','اسف'];
    $weekDays = ['Sunday'=>'یکشنبه','Monday'=>'دوشنبه','Tuesday'=>'سه‌شنبه','Wednesday'=>'چهارشنبه','Thursday'=>'پنج‌شنبه','Friday'=>'جمعه','Saturday'=>'شنبه'];
    $shortWeekDays = ['Sun'=>'ی','Mon'=>'د','Tue'=>'س','Wed'=>'چ','Thu'=>'پ','Fri'=>'ج','Sat'=>'ش'];

    $hour24 = date('H', $timestamp);
    $hour12 = date('h', $timestamp);
    $ampm = date('a', $timestamp);

    // نگاشت کامل کاراکترها
    $map = [
        'Y'=>$jy, 'y'=>substr($jy,-2),
        'm'=>str_pad($jm,2,'0',STR_PAD_LEFT), 'n'=>$jm,
        'd'=>str_pad($jd,2,'0',STR_PAD_LEFT), 'j'=>$jd,
        'H'=>$hour24, 'G'=>intval($hour24),
        'h'=>$hour12, 'g'=>intval($hour12),
        'i'=>date('i',$timestamp), 's'=>date('s',$timestamp),
        'a'=>$ampm, 'A'=>strtoupper($ampm),
        'l'=>$weekDays[date('l',$timestamp)],
        'D'=>$shortWeekDays[date('D',$timestamp)],
        'F'=>$months[$jm-1], 'M'=>$shortMonths[$jm-1],
        'w'=>date('w',$timestamp),
        'N'=>date('N',$timestamp),
        't'=>cal_days_in_month(CAL_GREGORIAN, $gm, $gy),
        'L'=>((($gy%4==0 && $gy%100!=0)||($gy%400==0))?1:0),
        'z'=>date('z',$timestamp),
        'U'=>mktime($hour24, date('i',$timestamp), date('s',$timestamp), $gm, $gd, $gy)
    ];

    // جایگزینی
    return preg_replace_callback('/[YymdnjHhisaglADFMwNtLU]/', function($m) use($map){
        return $map[$m[0]] ?? $m[0];
    }, $format);
}
