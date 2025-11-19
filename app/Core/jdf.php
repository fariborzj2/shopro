<?php
/*	Jalali (Shamsi) to Gregorian and Gregorian to Jalali conversion functions
    Original algorithm by Khaled Al-Shamaa
    Clean PHP implementation by Saleh Souzanchi
    + jdate() function added and stabilized
*/


/**
 * Convert Jalali date to Gregorian
 */
function jalali_to_gregorian($j_y, $j_m, $j_d)
{
    $g_days_in_month = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
    $j_days_in_month = array(31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29);

    $jy = $j_y - 979;
    $jm = $j_m - 1;
    $jd = $j_d - 1;

    $j_day_no = 365 * $jy + floor($jy / 33) * 8 + floor((($jy % 33) + 3) / 4);

    for ($i = 0; $i < $jm; ++$i)
        $j_day_no += $j_days_in_month[$i];

    $j_day_no += $jd;

    $g_day_no = $j_day_no + 79;

    $gy = 1600 + 400 * floor($g_day_no / 146097);
    $g_day_no = $g_day_no % 146097;

    $leap = true;

    if ($g_day_no >= 36525) {
        $g_day_no--;
        $gy += 100 * floor($g_day_no / 36524);
        $g_day_no = $g_day_no % 36524;

        if ($g_day_no >= 365)
            $g_day_no++;
        else
            $leap = false;
    }

    $gy += 4 * floor($g_day_no / 1461);
    $g_day_no %= 1461;

    if ($g_day_no >= 366) {
        $leap = false;
        $g_day_no--;
        $gy += floor($g_day_no / 365);
        $g_day_no %= 365;
    }

    for ($i = 0; $g_day_no >= $g_days_in_month[$i] + ($i == 1 && $leap); $i++)
        $g_day_no -= $g_days_in_month[$i] + ($i == 1 && $leap);

    $gm = $i + 1;
    $gd = $g_day_no + 1;

    return array($gy, $gm, $gd);
}


/**
 * Convert Gregorian date to Jalali
 */
function gregorian_to_jalali($g_y, $g_m, $g_d)
{
    $g_days_in_month = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
    $j_days_in_month = array(31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29);

    $gy = $g_y - 1600;
    $gm = $g_m - 1;
    $gd = $g_d - 1;

    $g_day_no = 365 * $gy + floor(($gy + 3) / 4) - floor(($gy + 99) / 100) + floor(($gy + 399) / 400);

    for ($i = 0; $i < $gm; ++$i)
        $g_day_no += $g_days_in_month[$i];

    if ($gm > 1 && (($gy % 4 == 0 && $gy % 100 != 0) || ($gy % 400 == 0)))
        $g_day_no++;

    $g_day_no += $gd;

    $j_day_no = $g_day_no - 79;

    $j_np = floor($j_day_no / 12053);
    $j_day_no %= 12053;

    $jy = 979 + 33 * $j_np + 4 * floor($j_day_no / 1461);

    $j_day_no %= 1461;

    if ($j_day_no >= 366) {
        $jy += floor(($j_day_no - 1) / 365);
        $j_day_no = ($j_day_no - 1) % 365;
    }

    for ($i = 0; $i < 11 && $j_day_no >= $j_days_in_month[$i]; ++$i)
        $j_day_no -= $j_days_in_month[$i];

    $jm = $i + 1;
    $jd = $j_day_no + 1;

    return array($jy, $jm, $jd);
}


/**
 * jdate — Jalali equivalent of PHP date()
 * Supports: Y, m, d, H, i, s
 */
function jdate($format = 'Y-m-d', $timestamp = null)
{
    if ($timestamp === null)
        $timestamp = time();

    // get Gregorian date
    $gy = date('Y', $timestamp);
    $gm = date('m', $timestamp);
    $gd = date('d', $timestamp);

    // convert to Jalali
    list($jy, $jm, $jd) = gregorian_to_jalali($gy, $gm, $gd);

    // replacements
    $map = [
        'Y' => $jy,
        'm' => str_pad($jm, 2, '0', STR_PAD_LEFT),
        'd' => str_pad($jd, 2, '0', STR_PAD_LEFT),
        'H' => date('H', $timestamp),
        'i' => date('i', $timestamp),
        's' => date('s', $timestamp),
    ];

    return preg_replace_callback('/[YmdHis]/', function ($match) use ($map) {
        return $map[$match[0]];
    }, $format);
}
