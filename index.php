<?php
session_start();

// If the user is already logged in, redirect to the dashboard.
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ورود | ثبت‌نام با کد تایید</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Simple toast notification style */
        #toast {
            visibility: hidden;
            min-width: 250px;
            margin-left: -125px;
            background-color: #333;
            color: #fff;
            text-align: center;
            border-radius: 2px;
            padding: 16px;
            position: fixed;
            z-index: 1;
            left: 50%;
            bottom: 30px;
            transition: visibility 0.5s, opacity 0.5s linear;
            opacity: 0;
        }
        #toast.show {
            visibility: visible;
            opacity: 1;
        }
        #toast.error { background-color: #dc2626; }
        #toast.success { background-color: #16a34a; }
    </style>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

    <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold text-center mb-6">ورود یا ثبت‌نام</h1>

        <!-- Mobile Form -->
        <form id="mobile-form" class="space-y-6">
            <div>
                <label for="mobile" class="block text-sm font-medium text-gray-700">شماره موبایل</label>
                <input type="tel" id="mobile" name="mobile" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="09123456789" required>
            </div>
            <button type="submit" id="send-code-btn" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                ارسال کد تایید
            </button>
        </form>

        <!-- OTP Form (Hidden by default) -->
        <form id="otp-form" class="space-y-6 hidden">
            <p class="text-center text-sm text-gray-600">کد تایید به شماره <span id="display-mobile" class="font-bold"></span> ارسال شد.</p>
            <div>
                <label for="otp" class="block text-sm font-medium text-gray-700">کد تایید</label>
                <input type="text" id="otp" name="otp" inputmode="numeric" pattern="[0-9]*" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-center tracking-[1em]" required>
            </div>
            <button type="submit" id="verify-btn" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                ورود
            </button>
            <div id="resend-timer" class="text-center text-sm text-gray-500"></div>
        </form>
    </div>

    <!-- Toast Notification -->
    <div id="toast"></div>

    <script>
        const mobileForm = document.getElementById('mobile-form');
        const otpForm = document.getElementById('otp-form');
        const mobileInput = document.getElementById('mobile');
        const otpInput = document.getElementById('otp');
        const displayMobile = document.getElementById('display-mobile');
        const resendTimer = document.getElementById('resend-timer');
        const toast = document.getElementById('toast');
        let timerInterval;

        // --- Event Listeners ---
        mobileForm.addEventListener('submit', handleSendCode);
        otpForm.addEventListener('submit', handleVerifyCode);

        // --- Handlers ---
        async function handleSendCode(e) {
            e.preventDefault();
            const mobile = mobileInput.value;
            if (!/^^09[0-9]{9}$/.test(mobile)) {
                showToast('شماره موبایل نامعتبر است.', 'error');
                return;
            }

            setButtonLoading(e.submitter, true);

            try {
                const response = await fetch('auth/send-code.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ mobile: mobile })
                });

                const result = await response.json();

                if (result.status === 'ok') {
                    showToast(result.message, 'success');
                    displayMobile.textContent = mobile;
                    mobileForm.classList.add('hidden');
                    otpForm.classList.remove('hidden');
                    otpInput.focus();
                    startResendTimer(result.timer || 60);
                } else {
                    showToast(result.message, 'error');
                }
            } catch (error) {
                showToast('خطایی در ارتباط با سرور رخ داد.', 'error');
            } finally {
                setButtonLoading(e.submitter, false);
            }
        }

        async function handleVerifyCode(e) {
            e.preventDefault();
            const mobile = mobileInput.value;
            const otp = otpInput.value;

            setButtonLoading(e.submitter, true);

            try {
                const response = await fetch('auth/verify.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ mobile: mobile, otp: otp })
                });
                const result = await response.json();

                if (result.status === 'ok') {
                    showToast(result.message, 'success');
                    window.location.href = 'dashboard.php';
                } else {
                    showToast(result.message, 'error');
                }
            } catch (error) {
                showToast('خطایی در ارتباط با سرور رخ داد.', 'error');
            } finally {
                setButtonLoading(e.submitter, false);
            }
        }

        // --- UI Helpers ---
        function startResendTimer(seconds) {
            clearInterval(timerInterval);
            let timeLeft = seconds;
            resendTimer.innerHTML = `ارسال مجدد کد تا <span class="font-bold">${timeLeft}</span> ثانیه دیگر`;

            timerInterval = setInterval(() => {
                timeLeft--;
                if (timeLeft > 0) {
                    resendTimer.innerHTML = `ارسال مجدد کد تا <span class="font-bold">${timeLeft}</span> ثانیه دیگر`;
                } else {
                    clearInterval(timerInterval);
                    resendTimer.innerHTML = '<a href="#" onclick="resendCode(event)" class="text-indigo-600 hover:underline">ارسال مجدد کد</a>';
                }
            }, 1000);
        }

        function resendCode(e) {
            e.preventDefault();
            // Go back to mobile form to re-trigger the send-code process
            otpForm.classList.add('hidden');
            mobileForm.classList.remove('hidden');
            mobileInput.focus();
            clearInterval(timerInterval);
            resendTimer.innerHTML = '';
        }

        function setButtonLoading(button, isLoading) {
            if (isLoading) {
                button.disabled = true;
                button.innerHTML = `
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    درحال پردازش...
                `;
            } else {
                button.disabled = false;
                button.innerHTML = button.id === 'send-code-btn' ? 'ارسال کد تایید' : 'ورود';
            }
        }

        let toastTimeout;
        function showToast(message, type = 'success') {
            clearTimeout(toastTimeout);
            toast.className = 'show ' + type;
            toast.textContent = message;
            toastTimeout = setTimeout(() => {
                toast.className = toast.className.replace('show', '');
            }, 3000);
        }

    </script>
</body>
</html>
