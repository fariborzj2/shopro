<?php
// --- Initialization ---
session_start();
require_once __DIR__ . '/../lib/db.php';
require_once __DIR__ . '/../lib/functions.php';

// --- Input Validation ---
$input = json_decode(file_get_contents('php://input'), true);
$mobile = $input['mobile'] ?? '';
$otp_attempt = $input['otp'] ?? '';

if (!is_valid_mobile($mobile) || !preg_match('/^[0-9]{6}$/', $otp_attempt)) {
    json_response('error', 'اطلاعات وارد شده نامعتبر است.', [], 400);
}

$pdo = get_db_connection();

// --- Fetch OTP Record ---
// Find the latest OTP record for this mobile that hasn't expired.
$stmt = $pdo->prepare(
    "SELECT * FROM otp_codes WHERE mobile = ? AND expire_at > NOW() ORDER BY id DESC LIMIT 1"
);
$stmt->execute([$mobile]);
$otp_record = $stmt->fetch();

if (!$otp_record) {
    json_response('error', 'کد تایید نامعتبر است یا منقضی شده.', [], 400);
}

// --- Brute-Force Protection ---
if ($otp_record['attempts'] >= MAX_OTP_VERIFY_ATTEMPTS) {
    // Invalidate the code
    $stmt = $pdo->prepare("DELETE FROM otp_codes WHERE id = ?");
    $stmt->execute([$otp_record['id']]);
    json_response('error', 'تعداد تلاش‌های ناموفق بیش از حد مجاز است. لطفاً کد جدید دریافت کنید.', [], 429);
}

// --- Verify OTP ---
if (!password_verify($otp_attempt, $otp_record['code_hash'])) {
    // Increment attempts
    $stmt = $pdo->prepare("UPDATE otp_codes SET attempts = attempts + 1 WHERE id = ?");
    $stmt->execute([$otp_record['id']]);
    json_response('error', 'کد تایید وارد شده صحیح نیست.', [], 400);
}

// --- Success! Find or Create User ---
$stmt = $pdo->prepare("SELECT id FROM users WHERE mobile = ?");
$stmt->execute([$mobile]);
$user = $stmt->fetch();

$message = 'شما با موفقیت وارد شدید.';

if (!$user) {
    // User does not exist, create a new one
    $stmt = $pdo->prepare("INSERT INTO users (mobile) VALUES (?)");
    $stmt->execute([$mobile]);
    $user_id = $pdo->lastInsertId();
    $message = 'ثبت‌نام شما با موفقیت انجام شد و وارد شدید.';
} else {
    $user_id = $user['id'];
}

// --- Clean Up and Create Session ---
// Delete the used OTP code
$stmt = $pdo->prepare("DELETE FROM otp_codes WHERE id = ?");
$stmt->execute([$otp_record['id']]);

// Regenerate session ID for security
session_regenerate_id(true);

// Store user ID in session
$_SESSION['user_id'] = $user_id;
$_SESSION['mobile'] = $mobile;

json_response('ok', $message);
