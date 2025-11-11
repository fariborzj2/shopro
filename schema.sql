CREATE TABLE `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `mobile` VARCHAR(15) UNIQUE NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `otp_codes` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `mobile` VARCHAR(15) NOT NULL,
    `code_hash` VARCHAR(255) NOT NULL,
    `expire_at` DATETIME NOT NULL,
    `attempts` TINYINT DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    KEY `mobile_index` (`mobile`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
