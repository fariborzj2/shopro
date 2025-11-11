<?php

// Database Configuration
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'otp_login_db');
define('DB_USER', 'root');
define('DB_PASS', '');

// IPPanel SMS API Configuration
define('IPPANEL_API_KEY', 'YOUR_API_KEY');
define('IPPANEL_SENDER', '3000xxx'); // Your dedicated sender number
define('IPPANEL_PATTERN_CODE', 'katajm2twseygm7'); // The pattern code for OTP message

// OTP Settings
define('OTP_EXPIRATION_TIME_MINUTES', 2); // OTP validity duration in minutes
define('MAX_OTP_SEND_LIMIT', 5); // Max OTP requests per number in the defined time window
define('MAX_OTP_SEND_WINDOW_MINUTES', 30); // Time window for the send limit
define('MAX_OTP_VERIFY_ATTEMPTS', 5); // Max verification attempts for a single OTP
