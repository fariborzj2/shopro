<?php
require_once __DIR__ . '/../public/index.php';

use App\Models\Admin;
use App\Core\Database;

echo "Starting Admin Permission Tests...\n";

// Ensure DB connection
$db = Database::getConnection();

// 1. Create a Super Admin (if not exists, assuming ID 1 is super)
echo "Checking Super Admin...\n";
$superAdmin = Admin::find(1);
if (!$superAdmin) {
    echo "No admin found with ID 1. Creating one...\n";
    $id = Admin::create([
        'username' => 'superadmin_test',
        'password_hash' => password_hash('password', PASSWORD_DEFAULT),
        'email' => 'super@test.com',
        'role' => 'super',
        'status' => 'active',
        'is_super_admin' => 1,
        'permissions' => []
    ]);
    $superAdmin = Admin::find($id);
} else {
    // Ensure it is super admin
    Admin::update($superAdmin->id, ['is_super_admin' => 1]);
    $superAdmin = Admin::find(1);
}

if ($superAdmin->isSuperAdmin()) {
    echo "[PASS] Super Admin detected.\n";
} else {
    echo "[FAIL] Super Admin setup failed.\n";
}

// 2. Create a Restricted Admin
echo "Creating Restricted Admin...\n";
$uniqueSuffix = time();
$restrictedUsername = "restricted_" . $uniqueSuffix;
$restrictedId = Admin::create([
    'username' => $restrictedUsername,
    'password_hash' => password_hash('password', PASSWORD_DEFAULT),
    'email' => "restricted_{$uniqueSuffix}@test.com",
    'role' => 'support',
    'status' => 'active',
    'is_super_admin' => 0,
    'permissions' => ['orders', 'users'] // Only orders and users
]);

$restrictedAdmin = Admin::find($restrictedId);
if ($restrictedAdmin && !$restrictedAdmin->isSuperAdmin()) {
    echo "[PASS] Restricted Admin created.\n";
} else {
    echo "[FAIL] Restricted Admin creation failed.\n";
}

// 3. Verify Permissions
echo "Verifying Permissions...\n";

if ($restrictedAdmin->hasPermission('orders')) {
    echo "[PASS] Restricted Admin has 'orders' permission.\n";
} else {
    echo "[FAIL] Restricted Admin missing 'orders' permission.\n";
}

if (!$restrictedAdmin->hasPermission('settings')) {
    echo "[PASS] Restricted Admin does NOT have 'settings' permission.\n";
} else {
    echo "[FAIL] Restricted Admin incorrectly has 'settings' permission.\n";
}

// 4. Verify Super Admin has all permissions
if ($superAdmin->hasPermission('settings') && $superAdmin->hasPermission('admins')) {
    echo "[PASS] Super Admin has implicit permissions.\n";
} else {
    echo "[FAIL] Super Admin missing implicit permissions.\n";
}

// Cleanup
Admin::delete($restrictedId);
echo "Cleanup complete.\n";
echo "Tests Finished.\n";
