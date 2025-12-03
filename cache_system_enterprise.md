
-----

```markdown
# سیستم کشینگ Enterprise و High-Performance 

این مستند معماری، پیاده‌سازی و تنظیمات یک سیستم کشینگ پیشرفته را پوشش می‌دهد که دارای ویژگی‌های زیر است:
* **ضد Cache Stampede:** با استفاده از مکانیزم Locking و Retry Loop.
* **اتمیک (Atomic):** استفاده از Redis Pipeline برای عملیات همزمان.
* **هوشمند (Tag-based):** قابلیت پاکسازی گروهی کش‌ها.
* **ایمن:** جداسازی کش کاربران مهمان از کاربران لاگین شده.

---

## ۱. معماری جریان داده (Architecture Flow)

دیاگرام زیر نحوه برخورد درخواست با لایه‌های مختلف کش را نشان می‌دهد:

```

User Request
│
▼
[ 1. CDN Layer (Cloudflare/Fastly) ] ──(HIT)──\> Return Content
│
│ (MISS)
▼
[ 2. Web Server (Nginx/Apache) ]
│
▼
[ 3. Application (PHP) ]
│
├── A. Is User Guest? ───[ Check HTML Cache (Redis) ]
│
├── B. Database Query ───[ Check Query Cache (Redis) ]
│       │
│       ├── (Locked?) ──\> Wait (Retry Loop)
│       │
│       └── (Free?) ────\> Lock -\> DB Query -\> Cache -\> Release Lock
│
▼
[ 4. Database (MySQL/PostgreSQL) ]

````

---

## ۲. پیاده‌سازی کلاس Cache (هسته اصلی)

این کلاس از `Predis` استفاده می‌کند. ویژگی‌های مهم اصلاح شده در این نسخه:
1.  **Retry Loop:** اگر کلید قفل باشد، کاربر منتظر می‌ماند تا کش پر شود (جلوگیری از خطا).
2.  **Try-Catch:** تضمین می‌کند که حتی در صورت بروز خطای کد، قفل آزاد شود (جلوگیری از Deadlock).
3.  **Environment Variables:** حذف اطلاعات حساس از کد.

```php
<?php
require 'vendor/autoload.php';
use Predis\Client;

class Cache {
    protected $redis;
    protected $prefix;

    public function __construct($prefix = '') {
        $this->prefix = $prefix;
        
        // اتصال امن با استفاده از متغیرهای محیطی
        $this->redis = new Client([
            'scheme'   => 'tcp',
            'host'     => $_ENV['REDIS_HOST'] ?? '127.0.0.1',
            'port'     => $_ENV['REDIS_PORT'] ?? 6379,
            'password' => $_ENV['REDIS_PASSWORD'] ?? null,
            'database' => $_ENV['REDIS_DB'] ?? 0,
            'read_write_timeout' => 0, // جلوگیری از تایم‌اوت در عملیات سنگین
        ]);
    }

    /**
     * تولید کلید نهایی با پیشوند
     */
    private function key($key) {
        return $this->prefix . $key;
    }

    /**
     * ذخیره ساده (معمولاً توسط متد remember صدا زده می‌شود)
     */
    public function put($key, $value, $ttl = 300, $tags = []) {
        $pipeline = $this->redis->pipeline();
        $packedValue = serialize($value); // یا json_encode برای خوانایی بیشتر
        
        $pipeline->set($this->key($key), $packedValue);
        $pipeline->expire($this->key($key), $ttl);
        
        // ذخیره تگ‌ها برای پاکسازی گروهی
        foreach ($tags as $tag) {
            $pipeline->sadd($this->key("tag:$tag"), $this->key($key));
            // تگ‌ها هم باید TTL داشته باشند (اختیاری ولی توصیه شده)
            $pipeline->expire($this->key("tag:$tag"), $ttl); 
        }
        
        $pipeline->execute();
    }

    public function get($key) {
        $data = $this->redis->get($this->key($key));
        return $data ? unserialize($data) : null;
    }

    public function delete($key) {
        $this->redis->del([$this->key($key)]);
    }

    /**
     * متد حیاتی Remember
     * مدیریت Locking، Race Condition و Cache Stampede
     */
    public function remember($key, $ttl, callable $callback, $tags = []) {
        $fullKey = $this->key($key);
        
        // ۱. تلاش اولیه: آیا دیتا در کش هست؟
        $data = $this->get($key);
        if ($data !== null) {
            return $data;
        }

        // ۲. تلاش برای دریافت قفل (Lock)
        // NX: یعنی فقط اگر کلید وجود نداشت ست کن (Atomic Lock)
        // EX: انقضای قفل بعد از ۱۰ ثانیه (برای جلوگیری از Deadlock ابدی)
        $lockKey = $this->key("lock:$key");
        $isLocked = $this->redis->set($lockKey, 1, 'EX', 10, 'NX');

        if ($isLocked) {
            // ---> ما قفل را گرفتیم (Leader Process)
            try {
                // اجرای کوئری سنگین
                $data = $callback();
                
                // ذخیره در کش با استفاده از پایپ‌لاین
                $pipeline = $this->redis->pipeline();
                $pipeline->set($fullKey, serialize($data));
                $pipeline->expire($fullKey, $ttl);
                
                foreach ($tags as $tag) {
                    $pipeline->sadd($this->key("tag:$tag"), $fullKey);
                }
                
                // آزاد کردن قفل
                $pipeline->del([$lockKey]);
                $pipeline->execute();
                
                return $data;
                
            } catch (Exception $e) {
                // در صورت خطا، حتماً قفل را آزاد کن تا بقیه گیر نکنند
                $this->redis->del([$lockKey]);
                throw $e;
            }
        } else {
            // ---> قفل دست کس دیگری است (Follower Process)
            // ورود به حلقه انتظار (Retry Loop)
            $attempts = 5; // ۵ تلاش
            $wait = 200000; // ۲۰۰ میلی‌ثانیه
            
            while ($attempts > 0) {
                usleep($wait);
                $data = $this->get($key);
                if ($data !== null) {
                    return $data; // دیتا آماده شد، برگردان
                }
                $attempts--;
            }
            
            // اگر بعد از ۱ ثانیه هنوز دیتا نیامد، خودمان اجرا می‌کنیم (Fallback)
            return $callback();
        }
    }

    /**
     * پاکسازی بر اساس تگ
     */
    public function invalidateTag($tag) {
        $fullTag = $this->key("tag:$tag");
        
        // گرفتن تمام کلیدهای متصل به این تگ
        $keys = $this->redis->smembers($fullTag);
        
        if (!empty($keys)) {
            // حذف تمام کلیدها + خود تگ در یک حرکت
            $keys[] = $fullTag;
            $this->redis->del($keys);
        }
    }
}
````

-----

## ۳. نحوه استفاده (Usage Strategy)

### الف) کش کردن HTML (Full Page Cache)

**قانون:** فقط برای کاربرانی که لاگین نکرده‌اند (`Guest`).

```php
// در فایل index.php یا Router
$cache = new Cache('app_v1_');
$isGuest = !isset($_SESSION['user_id']) && !isset($_COOKIE['auth_token']);

if ($isGuest && $_SERVER['REQUEST_METHOD'] === 'GET') {
    // کلید کش بر اساس URL کامل ساخته می‌شود
    $cacheKey = 'page_' . md5($_SERVER['REQUEST_URI']);
    
    echo $cache->remember($cacheKey, 600, function() {
        // شروع بافرینگ خروجی
        ob_start();
        require 'controllers/HomeController.php'; // اجرای کنترلر
        $html = ob_get_clean();
        
        // می‌توان اینجا HTML را Minify کرد
        return $html;
    }, ['pages', 'home']); // تگ‌ها برای پاکسازی آسان
    exit;
}

// برای کاربران لاگین شده، مستقیم اجرا شود
require 'controllers/HomeController.php';
```

### ب) کش کردن کوئری دیتابیس (Data Caching)

برای منوهای سایت، تنظیمات، یا لیست محصولات که تغییر کمی دارند.

```php
$topProducts = $cache->remember('top_products_list', 3600, function() use ($db) {
    return $db->query("SELECT * FROM products WHERE is_featured=1 LIMIT 10")->fetchAll();
}, ['products', 'featured']);
```

-----

## ۴. استراتژی پاکسازی (Invalidation)

| عملیات در سیستم | متد فراخوانی شده | توضیح |
| :--- | :--- | :--- |
| **ذخیره مقاله جدید** | `$cache->invalidateTag('blog_home');` | لیست مقالات در صفحه اصلی آپدیت شود. |
| **ویرایش محصول** | `$cache->delete('product_123');` | فقط کش همان محصول پاک شود. |
| **تغییر قیمت سراسری** | `$cache->invalidateTag('products');` | تمام کش‌های مربوط به محصولات پاک شوند. |
| **تغییر تنظیمات سایت** | `$cache->invalidateTag('config');` | تنظیمات بازنشانی شوند. |

-----

## ۵. چک‌لیست نهایی (Deployment Checklist)

قبل از رفتن به محیط عملیاتی (Production)، این موارد را بررسی کنید:

1.  [ ] **تنظیمات Redis:** فایل `redis.conf` را بررسی کنید.
      * `maxmemory`: حتماً محدودیت رم (مثلاً 512mb) تعیین کنید.
      * `maxmemory-policy`: روی `allkeys-lru` تنظیم کنید تا وقتی رم پر شد، قدیمی‌ترین داده‌ها حذف شوند (نه اینکه ردیس کرش کند).
2.  [ ] **متغیرهای محیطی:** فایل `.env` حاوی پسورد ردیس است و نباید در مخزن عمومی Git باشد.
3.  [ ] **مدیریت CSRF:** اگر از کش HTML استفاده می‌کنید، توکن‌های CSRF نباید در HTML کش شده باشند. آن‌ها را از طریق یک فراخوانی AJAX جداگانه دریافت کنید یا در کوکی ست کنید.
4.  [ ] **قابلیت CDN Purge:** اگر از کلودفلر استفاده می‌کنید، وقتی `$cache->invalidateTag` را صدا می‌زنید، باید API کلودفلر را هم صدا بزنید تا کش لبه (Edge) هم پاک شود.

-----

**پایان مستندات**

```
```
