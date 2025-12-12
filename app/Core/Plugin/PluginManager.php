<?php

namespace App\Core\Plugin;

use App\Core\Database;

class PluginManager
{
    const PLUGIN_DIR = PROJECT_ROOT . '/plugins';
    const TEMP_DIR = PROJECT_ROOT . '/storage/tmp/plugins-upload';

    public static function loadActivePlugins()
    {
        // Ensure table exists (could be moved to migration)
        self::ensureTableExists();

        try {
            $db = Database::getConnection();
            $stmt = $db->query("SELECT * FROM plugins WHERE status = 'active' ORDER BY load_order ASC");
            $plugins = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            foreach ($plugins as $plugin) {
                try {
                    self::loadPlugin($plugin);
                } catch (\Throwable $e) {
                    error_log("Failed to load plugin {$plugin['slug']}: " . $e->getMessage());
                    // Automatically deactivate the broken plugin
                    self::deactivate($plugin['slug']);
                }
            }
        } catch (\Exception $e) {
            error_log("Plugin system failed to initialize: " . $e->getMessage());
        }
    }

    public static function loadRoutes($router)
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->query("SELECT * FROM plugins WHERE status = 'active' ORDER BY load_order ASC");
            $plugins = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            foreach ($plugins as $plugin) {
                $path = self::PLUGIN_DIR . '/' . $plugin['slug'];
                $routesPath = $path . '/routes.php';
                if (file_exists($routesPath)) {
                    // Include the routes file, assuming it uses $router variable
                    require $routesPath;
                }
            }
        } catch (\Throwable $e) {
            error_log("Plugin routes failed to load: " . $e->getMessage());
        }
    }

    private static function loadPlugin($plugin)
    {
        $path = self::PLUGIN_DIR . '/' . $plugin['slug'];
        $jsonPath = $path . '/plugin.json';

        if (file_exists($jsonPath)) {
            $config = json_decode(file_get_contents($jsonPath), true);

            // Register Autoloader for this plugin
            if (isset($config['autoload']['psr-4'])) {
                foreach ($config['autoload']['psr-4'] as $namespace => $src) {
                    self::registerAutoloader($namespace, $path . '/' . trim($src, '/'));
                }
            }

            // Load main file in a scoped closure (basic sandboxing)
            if (file_exists($path . '/index.php')) {
                (function($pluginFile) {
                    include_once $pluginFile;
                })($path . '/index.php');
            }

            // Trigger onLoad event
            if (isset($config['events']['onLoad']) && is_callable($config['events']['onLoad'])) {
                try {
                    call_user_func($config['events']['onLoad']);
                } catch (\Throwable $e) {
                    error_log("Plugin {$plugin['slug']} onLoad failed: " . $e->getMessage());
                    throw $e; // Rethrow so loadActivePlugins can handle it
                }
            }
        }
    }

    public static function registerAutoloader($namespace, $path)
    {
        spl_autoload_register(function ($class) use ($namespace, $path) {
            $len = strlen($namespace);
            if (strncmp($namespace, $class, $len) !== 0) {
                return;
            }
            $relative_class = substr($class, $len);
            $file = $path . '/' . str_replace('\\', '/', $relative_class) . '.php';
            if (file_exists($file)) {
                require $file;
            }
        });
    }

    public static function install($zipFile)
    {
        if (!file_exists(self::TEMP_DIR)) {
            mkdir(self::TEMP_DIR, 0755, true);
        }

        $zip = new \ZipArchive;
        if ($zip->open($zipFile) === TRUE) {
            $extractPath = self::TEMP_DIR . '/' . uniqid();
            $zip->extractTo($extractPath);
            $zip->close();

            // Find plugin.json
            $files = scandir($extractPath);
            $pluginDir = null;
            foreach($files as $f) {
                if ($f === '.' || $f === '..') continue;
                if (is_dir($extractPath . '/' . $f) && file_exists($extractPath . '/' . $f . '/plugin.json')) {
                    $pluginDir = $f;
                    break;
                }
            }

            if (!$pluginDir) {
                self::rrmdir($extractPath);
                throw new \Exception("ساختار پلاگین نامعتبر است. فایل plugin.json یافت نشد.");
            }

            $source = $extractPath . '/' . $pluginDir;
            $json = json_decode(file_get_contents($source . '/plugin.json'), true);

            if (!$json || !isset($json['slug'])) {
                self::rrmdir($extractPath);
                throw new \Exception("فایل plugin.json نامعتبر است.");
            }

            $slug = $json['slug'];

            // Security: Sanitize slug to prevent directory traversal
            if (!preg_match('/^[a-z0-9\-_]+$/i', $slug)) {
                self::rrmdir($extractPath);
                throw new \Exception("نامک (slug) پلاگین نامعتبر است. فقط حروف، اعداد، خط تیره و خط زیر مجاز هستند.");
            }

            $dest = self::PLUGIN_DIR . '/' . $slug;

            // Check if update is needed
            if (file_exists($dest)) {
                // It's an update!
                return self::update($slug, $source, $json);
            }

            if (!file_exists(self::PLUGIN_DIR)) {
                mkdir(self::PLUGIN_DIR, 0755, true);
            }

            rename($source, $dest);
            self::rrmdir($extractPath);

            // Register in DB
            $db = Database::getConnection();
            $stmt = $db->prepare("INSERT INTO plugins (name, slug, version, status) VALUES (?, ?, ?, 'inactive')");
            $stmt->execute([$json['name'], $slug, $json['version']]);

            // Run install script if exists
            if (file_exists($dest . '/install.php')) {
                try {
                    (function($installFile) {
                        include_once $installFile;
                    })($dest . '/install.php');
                } catch (\Throwable $e) {
                    error_log("Plugin install script error: " . $e->getMessage());

                    // Rollback
                    $db->prepare("DELETE FROM plugins WHERE slug = ?")->execute([$slug]);
                    self::rrmdir($dest);

                    throw new \Exception("خطا در نصب پلاگین: " . $e->getMessage());
                }
            }

            return true;
        } else {
            throw new \Exception("باز کردن فایل ZIP با مشکل مواجه شد.");
        }
    }

    public static function update($slug, $sourcePath, $newConfig)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM plugins WHERE slug = ?");
        $stmt->execute([$slug]);
        $existingPlugin = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$existingPlugin) {
             throw new \Exception("پلاگین قدیمی برای بروزرسانی یافت نشد.");
        }

        $oldVersion = $existingPlugin['version'];
        $newVersion = $newConfig['version'];

        // Replace files
        $dest = self::PLUGIN_DIR . '/' . $slug;

        // Backup mechanism could be implemented here

        // Remove old files
        self::rrmdir($dest);

        // Move new files
        rename($sourcePath, $dest);

        // Update DB
        $updateStmt = $db->prepare("UPDATE plugins SET version = ? WHERE slug = ?");
        $updateStmt->execute([$newVersion, $slug]);

        // Register autoloader for hooks
        if (isset($newConfig['autoload']['psr-4'])) {
            foreach ($newConfig['autoload']['psr-4'] as $namespace => $src) {
                self::registerAutoloader($namespace, $dest . '/' . trim($src, '/'));
            }
        }

        // Trigger onUpdate
        if (isset($newConfig['events']['onUpdate']) && is_callable($newConfig['events']['onUpdate'])) {
            try {
                call_user_func($newConfig['events']['onUpdate'], $oldVersion, $newVersion);
            } catch (\Exception $e) {
                error_log("Plugin {$slug} onUpdate failed: " . $e->getMessage());
                // Should we revert? For now, just log.
            }
        }

        return true;
    }

    public static function activate($slug)
    {
        $db = Database::getConnection();
        $plugin = $db->prepare("SELECT * FROM plugins WHERE slug = ?");
        $plugin->execute([$slug]);
        $data = $plugin->fetch(\PDO::FETCH_ASSOC);

        if (!$data) {
            throw new \Exception("پلاگین یافت نشد.");
        }

        $path = self::PLUGIN_DIR . '/' . $slug;
        $jsonPath = $path . '/plugin.json';

        if (!file_exists($jsonPath)) {
             throw new \Exception("فایل‌های پلاگین یافت نشد.");
        }

        $config = json_decode(file_get_contents($jsonPath), true);

        // Check dependencies
        if (isset($config['requires']['plugins'])) {
             foreach ($config['requires']['plugins'] as $reqSlug => $reqVer) {
                 $reqPlugin = $db->prepare("SELECT * FROM plugins WHERE slug = ? AND status = 'active'");
                 $reqPlugin->execute([$reqSlug]);
                 if (!$reqPlugin->fetch()) {
                     throw new \Exception("پلاگین پیش‌نیاز {$reqSlug} فعال نیست.");
                 }
             }
        }

        // Register autoloader temporarily for activation hooks
        if (isset($config['autoload']['psr-4'])) {
            foreach ($config['autoload']['psr-4'] as $namespace => $src) {
                self::registerAutoloader($namespace, $path . '/' . trim($src, '/'));
            }
        }

        // Trigger onActivate
        if (isset($config['events']['onActivate']) && is_callable($config['events']['onActivate'])) {
            call_user_func($config['events']['onActivate']);
        }

        $db->prepare("UPDATE plugins SET status = 'active' WHERE slug = ?")->execute([$slug]);
    }

    public static function deactivate($slug)
    {
        $db = Database::getConnection();

        $path = self::PLUGIN_DIR . '/' . $slug;
        $jsonPath = $path . '/plugin.json';

        if (file_exists($jsonPath)) {
            $config = json_decode(file_get_contents($jsonPath), true);

            // Register autoloader temporarily for deactivation hooks
            if (isset($config['autoload']['psr-4'])) {
                foreach ($config['autoload']['psr-4'] as $namespace => $src) {
                    self::registerAutoloader($namespace, $path . '/' . trim($src, '/'));
                }
            }

            // Trigger onDeactivate
            if (isset($config['events']['onDeactivate']) && is_callable($config['events']['onDeactivate'])) {
                call_user_func($config['events']['onDeactivate']);
            }
        }

        $db->prepare("UPDATE plugins SET status = 'inactive' WHERE slug = ?")->execute([$slug]);
    }

    public static function uninstall($slug)
    {
        self::deactivate($slug);

        $path = self::PLUGIN_DIR . '/' . $slug;

        // Run uninstall script if exists
        if (file_exists($path . '/uninstall.php')) {
            (function($uninstallFile) {
                include_once $uninstallFile;
            })($path . '/uninstall.php');
        }

        // Remove from DB
        $db = Database::getConnection();
        $db->prepare("DELETE FROM plugins WHERE slug = ?")->execute([$slug]);

        // Remove files
        self::rrmdir($path);
    }

    private static function ensureTableExists()
    {
        $db = Database::getConnection();
        $db->query("CREATE TABLE IF NOT EXISTS plugins (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            slug VARCHAR(255) NOT NULL UNIQUE,
            version VARCHAR(50),
            status ENUM('active', 'inactive') DEFAULT 'inactive',
            load_order INT DEFAULT 10,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
    }

    private static function rrmdir($dir) {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (is_dir($dir. DIRECTORY_SEPARATOR .$object) && !is_link($dir."/".$object))
                        self::rrmdir($dir. DIRECTORY_SEPARATOR .$object);
                    else
                        unlink($dir. DIRECTORY_SEPARATOR .$object);
                }
            }
            rmdir($dir);
        }
    }

    public static function isActive($slug)
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT COUNT(*) FROM plugins WHERE slug = ? AND status = 'active'");
            $stmt->execute([$slug]);
            return (bool) $stmt->fetchColumn();
        } catch (\Throwable $e) {
            // Table might not exist yet
            return false;
        }
    }
}
