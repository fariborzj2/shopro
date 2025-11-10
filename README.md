# Database Structure

این مخزن شامل ساختار دیتابیس فروشگاه است که شامل دسته‌بندی محصولات، محصولات، کاربران، سفارشات و ادمین‌ها می‌باشد. ساختار بهینه شده و آماده گسترش است.

---

## 1. Categories (`categories`)

| Column | Type | Description |
|--------|------|-------------|
| `id` | INT PK AI | شناسه یکتا دسته‌بندی |
| `parent_id` | INT NULL | شناسه دسته‌بندی والد (برای دسته‌های تو در تو) |
| `position` | INT | ترتیب نمایش دسته‌بندی |
| `title` | VARCHAR(255) | عنوان عمومی دسته |
| `name_fa` | VARCHAR(255) | نام فارسی دسته |
| `name_en` | VARCHAR(255) | نام انگلیسی دسته |
| `image_url` | VARCHAR(512) | مسیر تصویر دسته |
| `status` | TINYINT(1) | 0: غیرفعال، 1: فعال |
| `notes` | TEXT | نکات ضروری |
| `description` | TEXT | توضیحات HTML |

---

## 2. Products (`products`)

| Column | Type | Description |
|--------|------|-------------|
| `id` | INT PK AI | شناسه یکتا محصول |
| `category_id` | INT FK (`categories.id`) | دسته‌بندی محصول |
| `position` | INT | ترتیب نمایش محصول در دسته |
| `name_fa` | VARCHAR(255) | نام فارسی محصول |
| `name_en` | VARCHAR(255) | نام انگلیسی محصول |
| `price` | DECIMAL(12,2) | قیمت فعلی محصول |
| `old_price` | DECIMAL(12,2) NULL | قیمت قبل محصول |
| `status` | TINYINT(1) | 0: غیرفعال، 1: فعال |
| `created_at` | DATETIME | تاریخ ایجاد محصول |
| `updated_at` | DATETIME | تاریخ آخرین ویرایش |

---

## 3. Users (`users`)

| Column | Type | Description |
|--------|------|-------------|
| `id` | INT PK AI | شناسه یکتا کاربر |
| `name` | VARCHAR(255) | نام کاربر |
| `mobile` | VARCHAR(20) UNIQUE | شماره موبایل |
| `status` | TINYINT(1) | 0: در حال بررسی، 1: تایید شده، 2: دزد |
| `short_note` | VARCHAR(255) NULL | توضیح کوتاه درباره کاربر |
| `created_at` | DATETIME | تاریخ ثبت‌نام |
| `order_count` | INT DEFAULT 0 | تعداد سفارشات کاربر |

---

## 4. Orders (`orders`)

| Column | Type | Description |
|--------|------|-------------|
| `id` | INT PK AI | شناسه یکتا سفارش |
| `order_code` | VARCHAR(50) UNIQUE | کد/شناسه شانس سفارش |
| `user_id` | INT FK (`users.id`) | شناسه کاربر خریدار |
| `mobile` | VARCHAR(20) | شماره موبایل خریدار |
| `product_id` | INT FK (`products.id`) | شناسه محصول خریداری شده |
| `category_id` | INT FK (`categories.id`) | دسته‌بندی محصول خریداری شده |
| `status` | TINYINT(1) | 0: لغو، 1: در حال پردازش، 2: تکمیل‌شده، 4: فیشینگ |
| `order_time` | DATETIME | زمان ثبت سفارش |
| `amount` | DECIMAL(12,2) | مبلغ سفارش |
| `discount_used` | TINYINT(1) | 0: استفاده نشده، 1: استفاده شده |
| `quantity` | INT DEFAULT 1 | تعداد محصول خریداری شده |
| `payment_method` | VARCHAR(50) NULL | روش پرداخت (اختیاری) |
| `delivery_address` | TEXT NULL | آدرس تحویل (اختیاری) |

---

## 5. Admins (`admins`)

| Column | Type | Description |
|--------|------|-------------|
| `id` | INT PK AI | شناسه یکتا ادمین |
| `username` | VARCHAR(100) UNIQUE | نام کاربری برای ورود |
| `password_hash` | VARCHAR(255) | هش رمز عبور |
| `name` | VARCHAR(255) | نام کامل ادمین |
| `email` | VARCHAR(255) UNIQUE NULL | ایمیل ادمین (اختیاری) |
| `role` | ENUM('super','manager','support') | سطح دسترسی ادمین |
| `status` | TINYINT(1) | 0: غیرفعال، 1: فعال |
| `created_at` | DATETIME | تاریخ ایجاد حساب |
| `last_login` | DATETIME NULL | آخرین ورود به سیستم |

---

### نکات مهم:

- ایندکس‌ها: `category_id`, `product_id`, `user_id` برای جستجو سریع ضروری هستند.  
- ستون‌های `status` برای دسته‌بندی، محصول، کاربر، سفارش و ادمین همگی از نوع `TINYINT(1)` هستند تا کم‌جا و خوانا باشند.  
- تاریخ‌ها (`created_at`, `updated_at`, `order_time`, `last_login`) برای گزارش‌گیری و تحلیل عملکرد حیاتی‌اند.  
- این ساختار امکان توسعه آینده را دارد:  
  - دسته‌بندی‌های تو در تو  
  - چند تصویر برای هر محصول  
  - روش‌های پرداخت متنوع  
  - تخفیف‌های چندگانه  
  - سطوح دسترسی ادمین متنوع
