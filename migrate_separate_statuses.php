<?php

require_once __DIR__ . '/public/index.php';

use App\Core\Database;

echo "Separating order statuses in DB...\n";

try {
    $pdo = Database::getConnection();

    // 1. Add new columns
    // We use temporary names or just add them.
    // Let's add 'payment_status' first.
    // 'order_status' will be the migrated 'status'.

    $pdo->exec("ALTER TABLE orders ADD COLUMN payment_status ENUM('unpaid', 'paid', 'failed') NOT NULL DEFAULT 'unpaid' AFTER status");
    $pdo->exec("ALTER TABLE orders ADD COLUMN order_status ENUM('pending', 'completed', 'cancelled', 'phishing') NOT NULL DEFAULT 'pending' AFTER payment_status");

    echo "Columns added. Migrating data...\n";

    // 2. Migrate data
    $stmt = $pdo->query("SELECT id, status FROM orders");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $id = $row['id'];
        $oldStatus = $row['status'];

        $pStatus = 'unpaid';
        $oStatus = 'pending';

        switch ($oldStatus) {
            case 'pending': // old 'pending' -> unpaid, pending
                $pStatus = 'unpaid';
                $oStatus = 'pending';
                break;
            case 'paid':
            case 'processing': // old 'paid'/'processing' -> paid, pending
                $pStatus = 'paid';
                $oStatus = 'pending';
                break;
            case 'failed': // old 'failed' -> failed, pending
                $pStatus = 'failed';
                $oStatus = 'pending';
                break;
            case 'completed':
            case 'shipped':
            case 'delivered': // completed/shipped/delivered -> paid, completed
                $pStatus = 'paid';
                $oStatus = 'completed';
                break;
            case 'cancelled': // cancelled -> unpaid (usually), cancelled
                $pStatus = 'unpaid'; // Or failed? cancelled implies abort.
                $oStatus = 'cancelled';
                break;
            case 'phishing':
                $pStatus = 'paid'; // Phishing usually means money taken? Or just order marked as such.
                // Let's assume paid for now as that's the danger, or unpaid.
                // Safest is 'paid' if we want to flag it, or 'failed'.
                // Actually, if it's phishing, maybe payment status doesn't matter or is suspicious.
                // I'll set 'paid' for payment status if it looks like a paid order, or 'unpaid'.
                // Let's stick to 'paid' and 'phishing'.
                $pStatus = 'paid';
                $oStatus = 'phishing';
                break;
            default:
                $pStatus = 'unpaid';
                $oStatus = 'pending';
        }

        $update = $pdo->prepare("UPDATE orders SET payment_status = :p, order_status = :o WHERE id = :id");
        $update->execute([':p' => $pStatus, ':o' => $oStatus, ':id' => $id]);
    }

    echo "Data migrated. Cleaning up schema...\n";

    // 3. Drop old column
    $pdo->exec("ALTER TABLE orders DROP COLUMN status");

    echo "Successfully separated statuses.\n";

} catch (Exception $e) {
    echo "Migration failed: " . $e->getMessage() . "\n";
    exit(1);
}
