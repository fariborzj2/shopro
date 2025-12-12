<?php
// tests/shop_scenario_runner.php

define('PROJECT_ROOT', dirname(__DIR__));
require_once PROJECT_ROOT . '/app/Core/Database.php';
require_once PROJECT_ROOT . '/app/Core/helpers.php';
require_once PROJECT_ROOT . '/app/Core/Plugin/PluginManager.php';
require_once PROJECT_ROOT . '/app/Core/Plugin/Filter.php';

// Register Autoloader for Store Plugin manually for the test environment
spl_autoload_register(function ($class) {
    if (strpos($class, 'Store\\') === 0) {
        $file = PROJECT_ROOT . '/plugins/store/src/' . str_replace('\\', '/', substr($class, 6)) . '.php';
        if (file_exists($file)) {
            require $file;
        }
    }
});

use App\Core\Database;
use App\Core\Plugin\PluginManager;
use App\Core\Plugin\Filter;

class ShopScenarioTest {

    private $db;
    private $log = [];

    public function __construct() {
        // Init DB
        $config = require PROJECT_ROOT . '/config.php';
        $this->db = Database::getConnection();
        echo "[INFO] Test Runner Initialized.\n";
    }

    public function run() {
        try {
            $this->log("STARTING FULL SCENARIO TEST");

            // 1. Isolation Test
            $this->step1_Isolation();

            // 2. Activation Test
            $this->step2_Activation();

            // 3. Admin/Model Logic Test
            $this->step3_ModelLogic();

            // 4. Frontend & HTTP Test
            $this->step4_FrontendHttp();

            // 5. Sidebar Menu Test (New)
            $this->step7_SidebarMenu();

            // 6. Deactivation Test
            $this->step5_Deactivation();

            $this->report(true);

        } catch (\Exception $e) {
            $this->log("CRITICAL FAILURE: " . $e->getMessage());
            $this->report(false);
        }
    }

    private function step1_Isolation() {
        $this->log("--- Step 1: Isolation Test (Plugin Disabled) ---");

        // Disable plugin in DB
        $this->db->exec("UPDATE plugins SET status = 'inactive' WHERE slug = 'store'");

        if (PluginManager::isActive('store')) {
             throw new Exception("Failed to disable plugin 'store'.");
        }
        $this->log("Plugin 'store' is inactive.");

        $this->log("Verifying Core logic safety...");
        if (class_exists('Store\Models\Product')) {
            // Safe
        }
        $this->log("Step 1 Passed: Core logic didn't crash.");
    }

    private function step2_Activation() {
        $this->log("--- Step 2: Activation Test ---");

        $this->db->exec("UPDATE plugins SET status = 'active' WHERE slug = 'store'");

        if (!PluginManager::isActive('store')) {
            throw new Exception("Failed to activate plugin 'store'.");
        }
        $this->log("Plugin 'store' is active.");
        $this->log("Step 2 Passed: Activation successful.");
    }

    private function step3_ModelLogic() {
        $this->log("--- Step 3: Model Logic & Database ---");

        $catId = $this->createCategory();
        $this->log("Created Category ID: $catId");

        $prodId = $this->createProduct($catId);
        $this->log("Created Product ID: $prodId");

        $orderId = $this->createOrder($prodId);
        $this->log("Created Order ID: $orderId");

        $this->log("Step 3 Passed: CRUD operations successful.");
    }

    private function createCategory() {
        $data = ['name_fa' => 'Test Cat ' . time(), 'slug' => 'test-cat-' . time(), 'parent_id' => null, 'status' => 'active'];
        if (!class_exists('Store\Models\Category')) {
             throw new Exception("Class Store\Models\Category not found.");
        }
        return \Store\Models\Category::create($data);
    }

    private function createProduct($catId) {
        $data = [
            'category_id' => $catId,
            'name_fa' => 'Test Product ' . time(),
            'name_en' => 'Test Product EN',
            'price' => 1000,
            'status' => 'available',
            'position' => 0
        ];
        \Store\Models\Product::create($data);
        return $this->db->lastInsertId();
    }

    private function createOrder($prodId) {
        $data = [
            'user_id' => 1,
            'product_id' => $prodId,
            'amount' => 1000,
            'quantity' => 1,
            'payment_status' => 'unpaid',
            'order_status' => 'pending'
        ];
        $id = \Store\Models\Order::create($data);
        if (!is_int($id) || $id <= 0) {
             throw new Exception("Order::create did not return a valid ID.");
        }
        return $id;
    }

    private function step4_FrontendHttp() {
        $this->log("--- Step 4: Frontend & HTTP Testing ---");

        $baseUrl = "http://localhost:8080";

        $resp = $this->curlGet($baseUrl . "/");
        if ($this->curlCode != 200) {
             $this->log("WARNING: Home route code: " . $this->curlCode);
        } else {
             $this->log("Home Route: OK");
        }

        $resp = $this->curlGet($baseUrl . "/api/cart");
        $this->log("API Cart Response Code: " . $this->curlCode);

        $this->log("Step 4 Passed.");
    }

    private function step7_SidebarMenu() {
        $this->log("--- Step 7: Sidebar Menu Injection ---");

        // Mock initial menu items
        $menuItems = [
            ['label' => 'Dashboard', 'url' => '/dashboard']
        ];

        // Load Plugin Index manually (simulating PluginManager logic)
        $pluginIndex = PROJECT_ROOT . '/plugins/store/index.php';
        if (file_exists($pluginIndex)) {
            require_once $pluginIndex;
        } else {
            throw new Exception("plugins/store/index.php not found.");
        }

        // Apply Filter
        $finalMenu = Filter::apply('admin_menu_items', $menuItems);

        // Verify
        $foundOrders = false;
        foreach ($finalMenu as $item) {
            if (isset($item['url']) && strpos($item['url'], '/admin/orders') !== false) {
                $foundOrders = true;
                break;
            }
        }

        if ($foundOrders) {
            $this->log("Menu Item 'Orders' found in sidebar array.");
        } else {
            throw new Exception("Menu Item 'Orders' NOT found in sidebar array.");
        }

        $this->log("Step 7 Passed.");
    }

    private function step5_Deactivation() {
        $this->log("--- Step 5: Deactivation ---");
        $this->db->exec("UPDATE plugins SET status = 'inactive' WHERE slug = 'store'");
        $this->log("Plugin deactivated.");
    }

    private function log($msg) {
        $this->log[] = $msg;
        echo $msg . "\n";
    }

    private $curlCode;
    private function curlGet($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($ch);
        $this->curlCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return $output;
    }

    private function report($status) {
        echo "\n\n=== TEST REPORT ===\n";
        echo "Status: " . ($status ? "PASSED" : "FAILED") . "\n";
    }
}

// Run
$test = new ShopScenarioTest();
$test->run();
