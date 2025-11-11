<?php
// --- Initialization ---
require_once __DIR__ . '/../lib/db.php';
require_once __DIR__ . '/../lib/functions.php';

// --- Input Validation ---
$input = json_decode(file_get_contents('php://input'), true);
$mobile = $input['mobile'] ?? '';

if (!is_valid_mobile($mobile)) {
    json_response('error', 'شماره موبایل وارد شده معتبر نیست.', [], 400);
}

$pdo = get_db_connection();

// --- Rate Limiting ---
// Check how many times an OTP has been sent to this number in the last window
$limit_window_start = (new DateTime())
    ->sub(new DateInterval('PT' . MAX_OTP_SEND_WINDOW_MINUTES . 'M'))
    ->format('Y-m-d H:i:s');

$stmt = $pdo->prepare("SELECT COUNT(*) FROM otp_codes WHERE mobile = ? AND created_at > ?");
$stmt->execute([$mobile, $limit_window_start]);
$sent_count = $stmt->fetchColumn();

if ($sent_count >= MAX_OTP_SEND_LIMIT) {
    json_response('error', 'تعداد درخواست‌ها برای ارسال کد بیش از حد مجاز است. لطفاً بعداً تلاش کنید.', [], 429);
}

// --- OTP Generation and Storage ---
$otp = generate_otp();
$code_hash = password_hash($otp, PASSWORD_BCRYPT);
$expire_at = (new DateTime())
    ->add(new DateInterval('PT' . OTP_EXPIRATION_TIME_MINUTES . 'M'))
    ->format('Y-m-d H:i:s');

// Invalidate previous OTPs for this number to ensure only the latest one is valid
$stmt = $pdo->prepare("DELETE FROM otp_codes WHERE mobile = ?");
$stmt->execute([$mobile]);

// Insert the new OTP
$stmt = $pdo->prepare(
    "INSERT INTO otp_codes (mobile, code_hash, expire_at) VALUES (?, ?, ?)"
);
$stmt->execute([$mobile, $code_hash, $expire_at]);

// --- SMS Sending ---
// In a real project, you might add a job to a queue here.
// For simplicity, we send it directly.
$sms_sent = send_otp_sms($mobile, $otp);

if ($sms_sent) {
    json_response('ok', 'کد تایید با موفقیت ارسال شد.', ['timer' => OTP_EXPIRATION_TIME_MINUTES * 60]);
} else {
    // This is a server-side issue. Don't reveal too much to the client.
    // Log the error for debugging.
    error_log("SMS sending failed for mobile: " . $mobile);
    json_response('error', 'خطایی در ارسال کد رخ داد. لطفاً با پشتیبانی تماس بگیرید.', [], 500);
}
