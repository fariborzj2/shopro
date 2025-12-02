# CMS Plugin Architecture Specification

## 1. Overview
این سند ساختار، استانداردها و الزامات کامل برای طراحی معماری پلاگین‌های CMS جدید شما را تعریف می‌کند. هدف ایجاد یک سیستم پایدار، ایمن، ماژولار و قابل‌گسترش است که توسعه، نصب، انتشار و اجرای پلاگین‌ها را استانداردسازی می‌کند.

---

## 2. Plugin Package Format
### **2.1 فرمت فایل نصبی پلاگین**
پلاگین‌ها باید در قالب یک فایل ZIP با ساختار مشخص بسته‌بندی شوند:

```
plugin-name-version.zip
└── plugin-name/
        plugin.json
        index.php
        install.php
        uninstall.php
        assets/
            css/
            js/
            images/
        src/
            Controllers/
            Models/
            Views/
        migrations/
        languages/
```

### **2.2 الزامات فایل plugin.json**
```json
{
  "name": "plugin-name",
  "slug": "plugin-name",
  "version": "1.0.0",
  "description": "Short plugin description.",
  "author": "Author Name",
  "website": "https://example.com",
  "requires": {
    "php": ">=8.1",
    "cms": ">=1.0.0",
    "plugins": {
        "shop-core": ">=2.0.0"
    }
  },
  "autoload": {
    "psr-4": {
      "Vendor\\PluginName\\": "src/"
    }
  },
  "events": {
    "onActivate": "Vendor\\PluginName\\Hooks::activate",
    "onDeactivate": "Vendor\\PluginName\\Hooks::deactivate",
    "onUpdate": "Vendor\\PluginName\\Hooks::update",
    "onLoad": "Vendor\\PluginName\\Hooks::boot"
  }
}
```

---

## 3. Plugin Lifecycle
### **3.1 مراحل**
- **Install:** آپلود یا نصب از مخزن → بررسی نسخه‌ها → ثبت در DB → اجرای migrationها
- **Activate:** اجرای onActivate → ثبت hookها → لود اتولودر
- **Update:** اجرای onUpdate($oldVersion, $newVersion) → مایگریشن‌های جدید
- **Load:** فراخوانی onLoad → در دسترس قرار گرفتن APIها
- **Deactivate:** حذف hookها → اجرای onDeactivate
- **Uninstall:** حذف کامل DB و فایل‌ها (اختیاری)

---

## 4. Plugin API
### **4.1 Hooks (Event System) با اولویت**
```php
class Hook {
    protected static $listeners = [];

    public static function add(string $event, callable $callback, int $priority = 10) {
        self::$listeners[$event][$priority][] = $callback;
    }

    public static function fire(string $event, ...$args) {
        if (!isset(self::$listeners[$event])) return;

        ksort(self::$listeners[$event]); // مرتب‌سازی بر اساس اولویت

        foreach (self::$listeners[$event] as $priority => $callbacks) {
            foreach ($callbacks as $callback) {
                call_user_func_array($callback, $args);
            }
        }
    }
}

// نمونه
Hook::add('on_user_login', [AuthPlugin::class, 'log'], 5);
Hook::add('on_user_login', [MailPlugin::class, 'sendEmail'], 20);
```

### **4.2 Filters**
```php
Filter::add('post_content', function($content) {
    return $content . "<footer>Powered by plugin</footer>";
});
```

### **4.3 REST API Support**
```php
API::register('GET', '/plugin/data', [PluginController::class, 'index']);
```

---

## 5. Database Migrations
```php
return new class {
    public function up() {
        DB::query("CREATE TABLE example (...)");
    }
    public function down() {
        DB::query("DROP TABLE example");
    }
};
```
جدول migrations در دیتابیس وضعیت اجرا شده‌ها را ذخیره می‌کند تا هنگام Update فقط migrations جدید اجرا شوند.

---

## 6. Security Requirements
- Namespace Isolation الزامی است. همه کلاس‌ها باید در Namespace اختصاصی پلاگین باشند.
- تمام ورودی‌ها sanitize شوند.
- DI Container دسترسی امن به سرویس‌ها بدهد.
- جلوگیری از Class Collision و Overwrite فایل‌های هسته.

---

## 7. Plugin Upload System
- آپلود ZIP در مسیر `/storage/tmp/plugins-upload/`
- بررسی ساختار ZIP و plugin.json
- بررسی نسخه CMS و پلاگین‌های پیش‌نیاز
- استخراج در مسیر موقت و سپس انتقال به `/plugins/`
- اجرای install.php با try/catch و rollback در صورت خطا

---

## 8. Plugin Validation Rules
- نام یکتا و مطابق slug
- ساختار فولدر درست و وجود فایل‌های ضروری
- JSON معتبر و نسخه سازگار با CMS
- Namespace کلاس‌ها مطابق convention

---

## 9. Plugin Autoloading
Composer-style autoloading با PSR-4 بر اساس plugin.json و Namespace اختصاصی هر پلاگین.

---

## 10. Performance Guidelines
- کش داخلی برای hookها
- کنترل ترتیب اجرای hookها با priority
- سیستم مدیریت asset با dependency
- غیرفعال‌سازی بخش‌های سنگین از پنل

---

## 11. Frontend Integration
```php
Assets::registerScript('jquery', 'path/to/jquery.js');
Assets::addScript('my-plugin-script', 'path/to/app.js', ['jquery']);
Assets::addStyle('plugin/style.css');
```

---

## 12. Admin Panel Integration
- افزودن منو و صفحات تنظیمات
- ثبت تنظیمات اختصاصی با settings.json
- ارتباط با سیستم DI برای دسترسی امن به سرویس‌ها

---

## 13. Logging
هر پلاگین فضای log خود را دارد:
```
storage/logs/plugins/plugin-name.log
```

---

## 14. Recommended Coding Standards
- PSR-12
- PascalCase برای کلاس‌ها
- استفاده از Dependency Injection
- Namespace اختصاصی بر اساس slug

---

## 15. Example Plugin Structure
```
awesome-plugin/
 ├── plugin.json
 ├── index.php
 ├── src/
 │    ├── Hooks.php
 │    ├── Controllers/
 │    └── Models/
 ├── assets/
 └── migrations/
```

---

## 16. Example index.php
```php
<?php
use Vendor\PluginName\Hooks;
Hooks::boot();
```

---

## 17. Roadmap for Future Features
- Marketplace رسمی
- Webhooks و Notifications
- UI Builder برای تنظیمات پلاگین
- Signature Verification برای امنیت بیشتر
- سیستم پیشرفته Update و Dependency Resolution

---

## 18. Hook System Summary
ویژگی‌های کلیدی:
- Priority-based execution
- Support for dependencies بین پلاگین‌ها
- Pipeline architecture برای جلوگیری از اثرات جانبی
- Log و Exception handling کامل

---

## پایان سند
