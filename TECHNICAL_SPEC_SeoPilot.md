
# مستندات فنی جامع پلاگین SeoPilot (نسخه Enterprise)

**نسخه:** 1.0.0 (Gold Master)
**وضعیت:** نهایی و آماده اجرا
**مخاطب:** تیم توسعه و معماری نرم‌افزار

-----

## ۰. شناسنامه و معرفی محصول (Overview)

### نام محصول: **SeoPilot (Enterprise Edition)**

**شعار:** "نصب کن و فراموش کن" (Install & Forget)

### شرح عملکرد (What it does exactly?)

**SeoPilot** یک سیستم مدیریت سئو تمام‌خودکار، هوشمند و پرسرعت است که برای CMSهای اختصاصی PHP طراحی شده است. این پلاگین جایگزین ابزارهای سنگین و قدیمی مانند Yoast می‌شود و با تمرکز بر **سرعت (Performance)**، **زبان فارسی (Persian NLP)** و **پایداری (Stability)**، وظایف زیر را به صورت خودکار انجام می‌دهد:

1.  **اتوماسیون کامل متا تگ‌ها:** تولید خودکار Title, Description, Robots, Canonical و OpenGraph در لحظه ذخیره محتوا، بدون نیاز به دخالت کاربر.
2.  **تولید اسکیما (Schema Generator):** شناسایی نوع صفحه (محصول، مقاله، ویدیو) و تزریق JSON-LD استاندارد گوگل.
3.  **آنالیز محتوای فارسی:** بررسی کیفیت متن با درک عمیق از زبان فارسی (نیم‌فاصله‌ها، اعراب، مترادف‌ها) و ارائه پیشنهادات عملی.
4.  **کش ترکیبی (Hybrid Caching):** ذخیره خروجی HTML متا در دیتابیس و اتصال به LiteSpeed Cache برای ارائه پاسخ در ۰.۰۰۰ ثانیه.
5.  **مدیریت ریدایرکت هوشمند:** شناسایی تغییر آدرس‌ها (Slug Change) و ایجاد ریدایرکت ۳۰۱ خودکار با جلوگیری از لوپ‌های خطرناک.
6.  **تزریق بدون تداخل (Ghost Injection):** اصلاح کدهای `<head>` قالب بدون شکستن استایل‌ها یا تداخل با پلاگین‌های دیگر.

### مشخصات فنی و پیش‌نیازها

  * **زبان:** PHP 8.1 یا بالاتر.
  * **دیتابیس:** MySQL 8.0+ یا MariaDB 10.6+.
  * **معماری:** Modular, Event-Driven, PSR-4 Compliant.
  * **سازگاری سرور:** LiteSpeed (Native Support), Nginx, Apache.
  * **لایه‌های کش:** پشتیبانی داخلی از Redis, Memcached, File System و Database.

-----

## ۱. اصول بنیادین معماری (Core Principles)

1.  **Zero-Conflict (عدم تداخل):** پلاگین هیچ استایل یا اسکریپتی را در محیط گلوبال بارگذاری نمی‌کند. همه کلاس‌ها و استایل‌ها Namespaced هستند.
2.  **Hybrid Caching (کش ترکیبی):** استفاده همزمان از کش اپلیکیشن (Redis/File) و کش سرور (LiteSpeed) برای حداکثر سرعت.
3.  **Safe DOM Injection (تزریق ایمن):** به جای چسباندن کد به `<head>`، ابتدا بررسی می‌کند تگ وجود دارد یا خیر، سپس اصلاح یا اضافه می‌کند.
4.  **Zero-Config for User:** کاربر نهایی بجز یک ویزارد اولیه، درگیر تنظیمات فنی نمی‌شود.

-----

## ۲. ساختار فایل و دایرکتوری (Directory Structure)

رعایت استاندارد PSR-4 برای Autoloading اجباری است.

```text
/plugins/seopilot/
├── src/
│   ├── Core/
│   │   ├── Installer.php       # نصب جداول و تنظیمات اولیه (Migration)
│   │   ├── Kernel.php          # مدیریت هوک‌ها و بوت‌ستراپ
│   │   └── SafeInjector.php    # تزریق ایمن در HTML با DOMDocument
│   ├── Cache/
│   │   ├── CacheManager.php    # مدیریت مرکزی و انتخاب درایور
│   │   ├── Drivers/            # آداپتورهای Redis, File, Database
│   │   └── Server/             # هندلر اختصاصی LiteSpeed/LSCache
│   ├── Logic/
│   │   ├── Analyzer.php        # موتور آنالیز محتوا (بدون Regex خطرناک)
│   │   ├── PersianNLP.php      # پردازش زبان طبیعی فارسی (نرمال‌سازی)
│   │   └── AutoFixer.php       # پرکردن خودکار متای خالی هنگام ذخیره
│   ├── Database/
│   │   └── Repository.php      # کوئری‌های بهینه SQL
│   └── UI/
│       ├── AdminWizard.php     # ویزارد نصب اولیه
│       └── MetaBox.php         # متاباکس زیر پست (Preview & Analysis)
├── assets/                     # CSS/JS (Scoped & Isolated)
├── templates/                  # ویوهای پنل ادمین
└── composer.json
```

-----

## ۳. دیتابیس (Optimized Schema)

استفاده از ساختار **Flat Index** برای سرعت بالا (خواندن تمام متاهای یک صفحه با یک کوئری).

```sql
-- 1. جدول اصلی (شامل کش HTML رندر شده)
CREATE TABLE IF NOT EXISTS [prefix]_seopilot_meta (
    entity_id BIGINT UNSIGNED NOT NULL,
    entity_type VARCHAR(50) NOT NULL, -- post, product, page
    
    -- داده‌های خام (Raw Data) برای ویرایش
    focus_keyword VARCHAR(191),
    seo_title VARCHAR(255),
    seo_description VARCHAR(500),
    canonical_url VARCHAR(2048),
    robots VARCHAR(100) DEFAULT 'index,follow',
    og_image VARCHAR(2048),
    
    -- کشِ رندر شده (The Speed Secret)
    -- کل خروجی HTML متا اینجا ذخیره می‌شود تا در فرانت‌اند هیچ پردازشی انجام نشود
    compiled_head MEDIUMTEXT,
    
    -- نمرات آنالیز (برای گزارش‌گیری)
    seo_score TINYINT UNSIGNED DEFAULT 0,
    readability_score TINYINT UNSIGNED DEFAULT 0,
    
    PRIMARY KEY (entity_type, entity_id),
    INDEX idx_score (seo_score)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. جدول ریدایرکت‌های هوشمند (جلوگیری از 404)
CREATE TABLE IF NOT EXISTS [prefix]_seopilot_redirects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    source_uri VARCHAR(191) NOT NULL, -- Hashed or Limit length for Indexing
    target_uri VARCHAR(2048) NOT NULL,
    status_code SMALLINT DEFAULT 301,
    hit_count BIGINT UNSIGNED DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_source (source_uri)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. تنظیمات سراسری پلاگین
CREATE TABLE IF NOT EXISTS [prefix]_seopilot_options (
    option_name VARCHAR(64) PRIMARY KEY,
    option_value JSON NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

-----

## ۴. لایه کشینگ پیشرفته (The Hybrid Cache Layer)

### الف) کش سمت اپلیکیشن (Application Cache)

پلاگین باید به صورت خودکار بهترین درایور موجود را انتخاب کند (Adapter Pattern).

  * **Redis:** اولویت اول (اگر اکستنشن `phpredis` فعال بود).
  * **File:** اولویت دوم (ذخیره JSON در پوشه `cache/seo/`).
  * **Database:** اولویت آخر (Fallback).

### ب) کش سمت سرور (LiteSpeed Integration)

برای هماهنگی با وب‌سرورهای لایت‌اسپید جهت اعمال آنی تغییرات.

```php
// src/Cache/Server/LiteSpeed.php
class LiteSpeed {
    public static function tag(string $type, int $id) {
        if (!self::isLitespeed()) return;
        // ارسال تگ اختصاصی به هدر هنگام نمایش صفحه
        // پارامتر false یعنی تگ را اضافه کن، جایگزین نکن
        header("X-LiteSpeed-Tag: seopilot_{$type}_{$id}", false);
    }

    public static function purge(string $type, int $id) {
        if (!self::isLitespeed()) return;
        // دستور پاکسازی هنگام ذخیره پست
        header("X-LiteSpeed-Purge: seopilot_{$type}_{$id}");
    }
}
```

-----

## ۵. منطق هسته و هوش مصنوعی (Core Logic)

### ۵.۱. پردازشگر فارسی (`PersianNLP`)

وظیفه: درک صحیح متون فارسی.

  * **Normalization:** تبدیل `ي` به `ی`، `ك` به `ک`، و حذف اعراب.
  * **Half-Space Intelligence:** تبدیل هوشمند "می‌شود" (با نیم‌فاصله) به "می شود" (با فاصله) جهت شمارش دقیق کلمات.
  * **Stemming:** تشخیص هم‌خانواده‌ها (مثلاً تشخیص اینکه "موبایل‌ها" همان "موبایل" است).

### ۵.۲. آنالیزور ایمن (`Analyzer`)

وظیفه: امتیازدهی دقیق به محتوا.

  * **قانون حیاتی:** هرگز از Regex روی HTML خام استفاده نکنید.
  * **روش اجرا:** استفاده از `DOMDocument` برای استخراج `textContent` تمیز و سپس اعمال قوانین.
  * **قوانین نمونه:** طول تایتل (بر اساس پیکسل)، چگالی کلمه کلیدی (۰.۵٪ تا ۲.۵٪)، بررسی Alt تصاویر.

### ۵.۳. اتوماسیون (`AutoFixer`)

وظیفه: پر کردن جاهای خالی در لحظه ذخیره (هوک `on_save`).

  * اگر `meta_description` خالی است -\> ۱۵۰ کاراکتر اول متن خالص را بردار.
  * اگر `og:image` خالی است -\> اولین تصویر داخل متن را پیدا کن.
  * اگر `canonical` خالی است -\> URL استاندارد فعلی را قرار بده.

-----

## ۶. تزریق ایمن (Safe Injection Strategy)

برای جلوگیری از تداخل با قالب یا پلاگین‌های دیگر، کلاس `SafeInjector` با الگوریتم زیر عمل می‌کند:

1.  کل خروجی HTML صفحه (Buffer) را دریافت کن.
2.  با `DOMDocument` پارس کن.
3.  **Deduplication:** آیا تگ `<title>` یا `<meta name="description">` از قبل وجود دارد؟
      * **بله:** مقدار آن را آپدیت کن (جلوگیری از تایتل تکراری).
      * **خیر:** تگ جدید بساز و به `<head>` اضافه کن.
4.  خروجی اصلاح شده را برگردان.

-----

## ۷. رابط کاربری (UI) - ساده و تعاملی

### ۷.۱. ویزارد نصب (Setup Wizard)

در اولین فعال‌سازی:

1.  **نوع سایت:** (فروشگاه/بلاگ/شرکتی) -\> تنظیم خودکار Schema Type.
2.  **هویت:** (لوگو و نام سازمان) -\> تنظیمات Knowledge Graph.
3.  **موتورهای جستجو:** (ایندکس شود؟) -\> تنظیم `robots.txt`.

### ۷.۲. متاباکس (Editor Panel)

  * **Live Preview:** نمایش نحوه دیده شدن در گوگل (موبایل/دسکتاپ) با قابلیت ویرایش زنده.
  * **Score:** یک نشانگر رنگی ساده (سبز/زرد/قرمز).
  * **Analysis List:** پیام‌های فارسی و گویا (مثلاً: "عنوان خیلی طولانی است، کوتاهترش کن").

-----

## ۸. چک‌لیست توسعه (Implementation Checklist)

1.  [ ] **Setup:** ایجاد فایل `composer.json` و تعریف `SeoPilot\\` namespace.
2.  [ ] **DB Migration:** نوشتن اسکریپت ساخت جداول با شرط `IF NOT EXISTS`.
3.  [ ] **Cache Drivers:** پیاده‌سازی کلاس‌های Redis, File و LiteSpeed Handler.
4.  [ ] **NLP Engine:** کدنویسی توابع نرمال‌سازی و ریشه‌یابی فارسی.
5.  [ ] **Logic:** پیاده‌سازی `Analyzer` (بدون Regex) و `AutoFixer`.
6.  [ ] **Injection:** پیاده‌سازی `SafeInjector` برای مدیریت بافر خروجی.
7.  [ ] **UI:** طراحی متاباکس و ویزارد نصب.
8.  [ ] **Testing:** تست نهایی روی سرور لایت‌اسپید و بررسی عدم تداخل با قالب.
