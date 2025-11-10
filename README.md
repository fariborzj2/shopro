# Database Structure

این مخزن شامل ساختار دیتابیس فروشگاه و بلاگ است.  
تمام جداول با توضیحات، فیلدهای سئو و کلیدهای خارجی بهینه‌سازی شده‌اند.

---

## 1. Categories (`categories`)

جدول دسته‌بندی محصولات:

| Column | Type | Description |
|--------|------|-------------|
| `id` | INT PK AI | شناسه یکتا دسته‌بندی |
| `parent_id` | INT NULL | شناسه دسته‌بندی والد برای دسته‌های تو در تو |
| `position` | INT | ترتیب نمایش دسته‌بندی |
| `title` | VARCHAR(255) | عنوان عمومی دسته |
| `name_fa` | VARCHAR(255) | نام فارسی دسته |
| `name_en` | VARCHAR(255) | نام انگلیسی دسته |
| `image_url` | VARCHAR(512) | مسیر تصویر دسته |
| `status` | TINYINT(1) | 0: غیرفعال، 1: فعال |
| `notes` | TEXT | نکات ضروری داخلی |
| `description` | TEXT | توضیحات HTML دسته |

---

## 2. Products (`products`)

| Column | Type | Description |
|--------|------|-------------|
| `id` | INT PK AI | شناسه یکتا محصول |
| `category_id` | INT FK | دسته‌بندی محصول |
| `position` | INT | ترتیب نمایش محصول در دسته |
| `name_fa` | VARCHAR(255) | نام فارسی محصول |
| `name_en` | VARCHAR(255) | نام انگلیسی محصول |
| `price` | DECIMAL(12,2) | قیمت فعلی محصول |
| `old_price` | DECIMAL(12,2) | قیمت قبل محصول |
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
| `short_note` | VARCHAR(255) | توضیح کوتاه درباره کاربر |
| `created_at` | DATETIME | تاریخ ثبت‌نام |
| `order_count` | INT | تعداد سفارشات کاربر |

---

## 4. Orders (`orders`)

| Column | Type | Description |
|--------|------|-------------|
| `id` | INT PK AI | شناسه یکتا سفارش |
| `order_code` | VARCHAR(50) UNIQUE | کد/شناسه سفارش |
| `user_id` | INT FK | شناسه کاربر خریدار |
| `mobile` | VARCHAR(20) | شماره موبایل خریدار |
| `product_id` | INT FK | شناسه محصول خریداری شده |
| `category_id` | INT FK | دسته‌بندی محصول خریداری شده |
| `status` | TINYINT(1) | 0: لغو، 1: در حال پردازش، 2: تکمیل‌شده، 4: فیشینگ |
| `order_time` | DATETIME | زمان ثبت سفارش |
| `amount` | DECIMAL(12,2) | مبلغ سفارش |
| `discount_used` | TINYINT(1) | 0: استفاده نشده، 1: استفاده شده |
| `quantity` | INT | تعداد محصول خریداری شده |
| `payment_method` | VARCHAR(50) | روش پرداخت (اختیاری) |
| `delivery_address` | TEXT | آدرس تحویل (اختیاری) |

---

## 5. Admins (`admins`)

| Column | Type | Description |
|--------|------|-------------|
| `id` | INT PK AI | شناسه یکتا ادمین |
| `username` | VARCHAR(100) UNIQUE | نام کاربری ورود |
| `password_hash` | VARCHAR(255) | هش رمز عبور |
| `name` | VARCHAR(255) | نام کامل ادمین |
| `email` | VARCHAR(255) | ایمیل ادمین (اختیاری) |
| `role` | ENUM('super','manager','support') | سطح دسترسی ادمین |
| `status` | TINYINT(1) | 0: غیرفعال، 1: فعال |
| `created_at` | DATETIME | تاریخ ایجاد حساب |
| `last_login` | DATETIME | آخرین ورود |

---

## 6. Blog Categories (`blog_categories`)

| Column | Type | Description |
|--------|------|-------------|
| `id` | INT PK AI | شناسه یکتا دسته‌بندی بلاگ |
| `parent_id` | INT NULL | شناسه دسته والد برای زیر دسته‌ها |
| `position` | INT | ترتیب نمایش |
| `name_fa` | VARCHAR(255) | نام فارسی دسته |
| `name_en` | VARCHAR(255) | نام انگلیسی دسته |
| `slug` | VARCHAR(255) UNIQUE | آدرس URL بهینه برای سئو |
| `status` | TINYINT(1) | 0: غیرفعال، 1: فعال |
| `meta_title` | VARCHAR(255) | عنوان سئو |
| `meta_description` | VARCHAR(160) | توضیح متا برای سئو |
| `image_url` | VARCHAR(512) | تصویر شاخص دسته |
| `notes` | TEXT | توضیحات داخلی |

---

## 7. Blog Posts (`blog_posts`)

| Column | Type | Description |
|--------|------|-------------|
| `id` | INT PK AI | شناسه یکتا مطلب |
| `category_id` | INT FK | دسته‌بندی مطلب |
| `author_id` | INT NULL FK | نویسنده مطلب (NULL در صورت حذف کاربر) |
| `title` | VARCHAR(255) | عنوان مطلب |
| `slug` | VARCHAR(255) UNIQUE | آدرس URL مطلب برای سئو |
| `content` | TEXT | متن HTML مطلب |
| `excerpt` | TEXT | خلاصه مطلب |
| `status` | TINYINT(1) | 0: پیش‌نویس، 1: منتشر شده، 2: حذف شده |
| `meta_title` | VARCHAR(255) | عنوان سئو |
| `meta_description` | VARCHAR(160) | توضیح متا |
| `featured_image` | VARCHAR(512) | تصویر شاخص مطلب |
| `views_count` | INT | تعداد بازدید |
| `created_at` | DATETIME | تاریخ ایجاد |
| `updated_at` | DATETIME | تاریخ آخرین ویرایش |
| `published_at` | DATETIME | زمان انتشار |

---

## 8. Blog Tags (`blog_tags`)

| Column | Type | Description |
|--------|------|-------------|
| `id` | INT PK AI | شناسه یکتا تگ |
| `name` | VARCHAR(100) | نام تگ |
| `slug` | VARCHAR(100) UNIQUE | آدرس URL بهینه برای سئو |
| `status` | TINYINT(1) | 0: غیرفعال، 1: فعال |

---

## 9. Blog Post-Tag Mapping (`blog_post_tags`)

| Column | Type | Description |
|--------|------|-------------|
| `post_id` | INT FK | شناسه مطلب |
| `tag_id` | INT FK | شناسه تگ |

> این جدول امکان ارتباط چند به چند بین مطالب و تگ‌ها را فراهم می‌کند.

---

### نکات مهم:

- ستون‌های `slug`, `meta_title`, `meta_description` برای **سئو و بهینه‌سازی موتور جستجو** ضروری هستند.  
- ستون‌های `featured_image` و `image_url` برای نمایش در شبکه‌های اجتماعی و نتایج گوگل مهم هستند.  
- کلیدهای خارجی (`FK`) باعث حفظ یکپارچگی داده‌ها می‌شوند.  
- ستون‌های `status` برای مدیریت انتشار و دسترسی مفید هستند.  
- ستون‌های تاریخ (`created_at`, `updated_at`, `published_at`) برای گزارش‌گیری و تحلیل عملکرد ضروری هستند.  
- جدول `blog_post_tags` ارتباط چند به چند بین مطالب و تگ‌ها را فراهم می‌کند.
