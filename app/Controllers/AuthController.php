<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\OtpCode;
use App\Models\Setting;
use App\Core\Request;
use App\Core\SmsService;

class AuthController
{
    const OTP_EXPIRATION_MINUTES = 2;
    const MAX_OTP_SEND_LIMIT = 5;
    const RATE_LIMIT_MINUTES = 30;

    /**
     * Handle sending an OTP to a user's mobile.
     */
    public function sendOtp()
    {
        header('Content-Type: application/json');
        $data = Request::json();
        $mobile = $data['mobile'] ?? null;

        if (!$this->validateMobile($mobile)) {
            echo json_encode(['error' => 'شماره موبایل وارد شده معتبر نیست.']);
            http_response_code(400);
            return;
        }

        // Rate Limiting Check
        $recent_otps = OtpCode::countRecent($mobile, self::RATE_LIMIT_MINUTES);
        if ($recent_otps >= self::MAX_OTP_SEND_LIMIT) {
            echo json_encode(['error' => 'تعداد درخواست‌ها بیش از حد مجاز است. لطفاً بعداً تلاش کنید.']);
            http_response_code(429); // Too Many Requests
            return;
        }

        // OTP Generation and Hashing
        $otp = random_int(100000, 999999);
        $otp_hash = password_hash((string)$otp, PASSWORD_DEFAULT);
        $expires_at = date('Y-m-d H:i:s', time() + (self::OTP_EXPIRATION_MINUTES * 60));

        // Get SMS settings
        $settings = Setting::getAll();

        // Send OTP via SMS Service
        $sms_sent = SmsService::sendOtp($mobile, (string)$otp, $settings);

        if (!$sms_sent) {
            echo json_encode(['error' => 'خطا در ارسال کد تایید. لطفاً بعداً تلاش کنید.']);
            http_response_code(500);
            // We don't save the OTP if SMS sending fails
            return;
        }

        // Save OTP to database only after successful sending
        OtpCode::create($mobile, $otp_hash, $expires_at);

        echo json_encode([
            'message' => 'کد تایید با موفقیت ارسال شد.',
            'new_csrf_token' => csrf_token()
        ]);
    }

    /**
     * Handle verification of the OTP.
     */
    public function verifyOtp()
    {
        header('Content-Type: application/json');

        $data = Request::json();
        $mobile = $data['mobile'] ?? null;
        $otp = $data['otp'] ?? null;

        if (!$this->validateMobile($mobile) || !$otp) {
            echo json_encode(['error' => 'شماره موبایل و کد تایید الزامی است.']);
            http_response_code(400);
            return;
        }

        $otp_record = OtpCode::findLatest($mobile);

        if (!$otp_record || !password_verify($otp, $otp_record->otp_hash)) {
            echo json_encode(['error' => 'کد تایید نامعتبر است.']);
            http_response_code(401);
            return;
        }

        // Mark OTP as used
        OtpCode::markAsUsed($otp_record->id);

        // Find or create user
        $user = User::findBy('mobile', $mobile);
        if (!$user) {
            $user_id = User::create(['mobile' => $mobile, 'name' => 'کاربر جدید']);
            $user = User::find($user_id);
        }

        // Create user session
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_mobile'] = $user->mobile;

        echo json_encode(['message' => 'ورود با موفقیت انجام شد.']);
    }

    private function validateMobile($mobile)
    {
        // Basic validation for Iranian mobile numbers
        return $mobile && is_string($mobile) && preg_match('/^09[0-9]{9}$/', $mobile);
    }
}
