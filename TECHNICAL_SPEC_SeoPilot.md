این نسخه جدید **بسیار عالی و بالغ‌تر** است.

**چرا نسخه شما بهتر است؟**
۱. **نگاه معمارانه (Architectural View):** نسخه قبلی بیشتر "دستورالعمل کدنویسی" بود، اما این نسخه یک "سند معماری نرم‌افزار" است.
۲. **ساختار دیتابیس مدرن:** استفاده از `data_raw JSON` در کنار ستون‌های ایندکس شده، هوشمندانه‌ترین روش برای سیستم‌های Enterprise است (ترکیب سرعت SQL و انعطاف NoSQL).
۳. **جزئیات حرفه‌ای:** اضافه کردن مواردی مثل **Pixel Width Analysis** (به جای تعداد کاراکتر) و **Security/Hardening** نشان‌دهنده بلوغ محصول است.
۴. **تست و QA:** بخشی که در نسخه من کمرنگ بود و اینجا بسیار خوب دیده شده است.

با این حال، برای اینکه این سند برای **ایجنت هوش مصنوعی (Code Generator)** کاملاً قابل فهم باشد، باید کمی "جزئیات اجرایی" (مثل نمونه کدها و پترن‌ها) که در نسخه من بود را به ساختار عالی شما تزریق کنیم.

در ادامه، **نسخه نهایی و ادغام شده** (Ultimate Version) را آماده کرده‌ام. این نسخه ساختار قدرتمند شما را حفظ کرده و جزئیات فنی دقیق را در دل آن جا داده است تا توسعه‌دهنده/AI هیچ ابهامی نداشته باشد.

-----

# مستندات فنی جامع SeoPilot – نسخه Enterprise Master

**نسخه:** 2.0.0 (Gold Release)
**وضعیت:** Production-Ready Specification
**استاندارد:** PSR-4 / SOLID / Zero-Conflict

-----

## ۰. معرفی محصول (Product Overview)

**نام:** SeoPilot Enterprise
**ماموریت:** موتور سئوی خودکار، نامرئی و فوق‌سریع برای CMSهای PHP.
**فلسفه طراحی:**

1.  **Invisible:** کاربر وجود پلاگین را حس نمی‌کند (بدون تنظیمات پیچیده).
2.  **Native:** بومی‌سازی شده برای زبان فارسی و ساختار وب‌سرورهای لایت‌اسپید.
3.  **Resilient:** پایداری در برابر خطاهای دیتابیس، کش و تغییرات قالب.

-----

## ۱. اصول معماری (Architecture Principles)

### ۱.۱. ایزولاسیون مطلق (Zero-Conflict)

  * **Namespace:** تمام کلاس‌ها تحت `SeoPilot\Enterprise` باشند.
  * **DOM Isolation:** عدم استفاده از `echo` یا `print`. تمام خروجی‌ها باید به بافر تزریق شوند.
  * **Prefixing:** جداول با `{prefix}_seopilot_` و متغیرهای JS/CSS با `sp_` نام‌گذاری شوند.

### ۱.۲. جریان داده (Data Flow)

1.  **Request:** درخواست به سرور می‌رسد.
2.  **L1 Cache (LiteSpeed):** اگر موجود بود -\> پاسخ آنی (Zero PHP).
3.  **L2 Cache (App - Redis/File):** اگر موجود بود -\> تزریق HTML آماده.
4.  **Generation:** اگر کش نبود -\> اجرا شدن `Analyzer` -\> تولید -\> ذخیره در L1 & L2.

-----

## ۲. ساختار دایرکتوری (Standardized Structure)

ساختار تفکیک شده بر اساس مسئولیت (Separation of Concerns):

```text
/plugins/seopilot/
├── src/
│    ├── Core/              # هسته اصلی (Bootstrap, Config)
│    ├── NLP/               # موتور پردازش زبان فارسی
│    ├── Cache/             # درایورهای Redis, File, LiteSpeed
│    ├── Analyzer/          # موتور تحلیل محتوا (Pixel-based)
│    ├── Schema/            # تولیدکننده‌های JSON-LD
│    ├── Injector/          # موتور تزریق ایمن (DOM)
│    ├── Redirect/          # مدیریت تغییر مسیرها
│    ├── Controllers/       # کنترلرهای ادمین
│    ├── Services/          # سرویس‌های کمکی (AutoFixer)
│    └── Database/          # مدل‌ها و ریپازیتوری‌ها
├── public/                 # استایل‌ها و اسکریپت‌های ایزوله
├── views/                  # قالب‌های ادمین (Blade/Native)
├── index.php                 
├── install.php             # اسکریپت نصب و مایگریشن
├── uninstall.php           # پاکسازی کامل
└── plugin.json           # مدیریت وابستگی‌ها
```

-----

## ۳. دیتابیس (Optimized Schema)

طراحی هیبرید (SQL + JSON) برای سرعت و انعطاف.

```sql
-- 1. جدول اصلی متا و کش (Hot Data)
CREATE TABLE IF NOT EXISTS {prefix}_seopilot_meta (
    entity_id BIGINT UNSIGNED NOT NULL,
    entity_type VARCHAR(32) NOT NULL, -- post, product, category
    
    -- ستون‌های کلیدی برای جستجو و ایندکس
    focus_keyword VARCHAR(191),
    seo_score TINYINT UNSIGNED DEFAULT 0,
    
    -- داده‌های منعطف (Future Proof)
    data_raw JSON NULL, -- شامل title, desc, canonical, robots, og_...
    
    -- کشِ رندر شده (Super Fast Retrieval)
    compiled_head MEDIUMTEXT NULL, 
    
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (entity_type, entity_id),
    INDEX idx_score (seo_score)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. جدول ریدایرکت (High Performance Lookup)
CREATE TABLE IF NOT EXISTS {prefix}_seopilot_redirects (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    source_uri VARCHAR(191) NOT NULL, -- Hashed path
    target_uri VARCHAR(2048) NOT NULL,
    status_code SMALLINT DEFAULT 301,
    hit_count BIGINT UNSIGNED DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_source(source_uri)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. جدول تنظیمات سراسری
CREATE TABLE IF NOT EXISTS {prefix}_seopilot_options (
    option_name VARCHAR(64) PRIMARY KEY,
    option_value JSON NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

-----

## ۴. موتورهای منطقی (Core Engines)

### ۴.۱. موتور کش هیبرید (Hybrid Cache Layer)

پیاده‌سازی الگوی **Chain of Responsibility**:

1.  **LiteSpeed Handler:** مدیریت هدرهای `X-LiteSpeed-Tag` و `X-LiteSpeed-Purge`.
2.  **App Cache (Driver Interface):**
      * `RedisDriver`: اولویت اول (در صورت وجود اکستنشن).
      * `FileDriver`: اولویت دوم (ذخیره JSON).
      * `NullDriver`: برای محیط‌های توسعه.

### ۴.۲. موتور NLP فارسی (Persian NLP Engine)

وظایف دقیق:

  * **Normalization:** تبدیل `ي/ك` به `ی/ک`، حذف اعراب، تبدیل اعداد فارسی به انگلیسی (برای URL).
  * **Zero-Width Space:** تبدیل هوشمند نیم‌فاصله‌ها برای شمارش کلمات (مثلاً "می‌شود" = 1 کلمه).
  * **Stemming:** ریشه‌یابی کلمات (کتاب‌ها = کتاب) برای تطبیق Keyword.

### ۴.۳. موتور آنالیز (Pixel-Perfect Analyzer)

به جای شمارش کاراکتر (که دقیق نیست)، عرض پیکسل تایتل محاسبه شود.

  * **Logic:** استفاده از فونت استاندارد Arial 16px برای محاسبه عرض رشته.
  * **Target:** عرض مناسب بین 200px تا 580px.
  * **DOM Parsing:** استفاده از `DOMDocument` برای استخراج متن خالص (بدون تگ‌های HTML) جهت آنالیز.

### ۴.۴. موتور تزریق ایمن (Safe Injection Engine)

الگوریتم جلوگیری از تداخل:

1.  بافر خروجی HTML دریافت می‌شود.
2.  پارس کردن با `DOMDocument`.
3.  **Deduplication:** اگر `<title>` وجود دارد -\> مقدارش را آپدیت کن (نه اینکه یکی دیگر اضافه کنی).
4.  **Injection:** تگ‌های JSON-LD و Meta Tags را قبل از `</head>` قرار بده.
5.  بازگرداندن HTML اصلاح شده.

-----

## ۵. اتوماسیون و تعمیر خودکار (AutoMeta Engine)

این بخش در هوک `on_before_save` اجرا می‌شود.

  * **Fallback Logic:**
      * `Title` خالی؟ -\> استفاده از `Post Title`.
      * `Description` خالی؟ -\> استخراج ۱۶۰ کاراکتر اول متن خالص + NLP Clean (حذف کاراکترهای ناقص).
      * `OG Image` خالی؟ -\> اسکن متن برای اولین تگ `<img>` و برداشتن `src`.
      * `Canonical` خالی؟ -\> ساخت URL استاندارد فعلی.

-----

## ۶. رابط کاربری (UI/UX)

  * **Setup Wizard:** نصب ۴ مرحله‌ای (Type, Identity, Visibility, Finish).
  * **MetaBox:**
      * استفاده از `Alpine.js` یا `Vanilla JS` (بدون وابستگی به jQuery).
      * **Live Preview:** نمایش همزمان Snippet موبایل و دسکتاپ.
      * **Trend Chart:** نمایش نمودار کوچک تغییرات امتیاز سئو در طول زمان (Sparkline).

-----

## ۷. امنیت و پایداری (Security & Stability)

  * **Input Sanitization:** تمام ورودی‌های `$_POST` و `$_GET` باید قبل از پردازش Sanitize شوند.
  * **SQL Injection:** استفاده اجباری از Prepared Statements (PDO).
  * **XSS Protection:** اسکیپ کردن خروجی‌ها در ویوهای ادمین.
  * **Fail-Safe:** تمام فراخوانی‌های خارجی (مثل Redis) باید در بلوک `try-catch` باشند تا در صورت خطا، سایت پایین نیاید (Silent Fail).

-----

## ۸. تست و تضمین کیفیت (QA Strategy)

چک‌لیست تست‌های اجباری قبل از تحویل:

1.  [ ] **Unit Tests:** تست متدهای NLP با ورودی‌های پیچیده فارسی.
2.  [ ] **Integration Tests:** تست اتصال به Redis و ذخیره/بازیابی.
3.  [ ] **Conflict Test:** نصب همزمان با یک پلاگین که هدر را تغییر می‌دهد.
4.  [ ] **LiteSpeed Test:** بررسی هدرهای HTTP خروجی برای اطمینان از وجود تگ‌ها.
5.  [ ] **Redirect Loop Test:** تلاش برای ایجاد ریدایرکت A-\>B-\>A و اطمینان از بلاک شدن آن.

-----

**دستور نهایی به تیم توسعه:**
این سند را به عنوان **Source of Truth** در نظر بگیرید. هر خط کد باید مستقیماً با یکی از بندهای این سند توجیه شود. اولویت اول با **پایداری** و **عدم تداخل** است.
