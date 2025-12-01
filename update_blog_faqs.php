<?php

require_once __DIR__ . '/public/index.php'; // Bootstrap logic if needed, or just manual connection

use App\Core\Database;

$pdo = Database::getConnection();

echo "Starting migration...\n";

try {
    // 1. Add `faq` JSON column to `blog_posts` if it doesn't exist
    echo "Adding 'faq' column to blog_posts...\n";
    // Check if column exists first
    $stmt = $pdo->query("SHOW COLUMNS FROM blog_posts LIKE 'faq'");
    if ($stmt->rowCount() == 0) {
        $pdo->exec("ALTER TABLE blog_posts ADD COLUMN faq JSON DEFAULT NULL AFTER meta_keywords");
        echo "Column 'faq' added.\n";
    } else {
        echo "Column 'faq' already exists.\n";
    }

    // 2. Migrate existing data
    echo "Migrating existing FAQs...\n";
    // Fetch all posts that have FAQs
    $sql = "SELECT bpf.post_id, fi.question, fi.answer
            FROM blog_post_faq_items bpf
            JOIN faq_items fi ON bpf.faq_item_id = fi.id
            ORDER BY bpf.post_id, fi.position ASC, fi.id ASC";

    $stmt = $pdo->query($sql);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $faqsByPost = [];
    foreach ($rows as $row) {
        $faqsByPost[$row['post_id']][] = [
            'question' => $row['question'],
            'answer' => $row['answer']
        ];
    }

    foreach ($faqsByPost as $postId => $faqs) {
        $json = json_encode($faqs, JSON_UNESCAPED_UNICODE);
        $updateStmt = $pdo->prepare("UPDATE blog_posts SET faq = :faq WHERE id = :id");
        $updateStmt->execute(['faq' => $json, 'id' => $postId]);
        echo "Updated post ID $postId with " . count($faqs) . " FAQs.\n";
    }

    // 3. Drop pivot table
    echo "Dropping table blog_post_faq_items...\n";
    $pdo->exec("DROP TABLE IF EXISTS blog_post_faq_items");
    echo "Table blog_post_faq_items dropped.\n";

    // Note: We are NOT dropping `faq_items` as it might be used for general site FAQs.

    echo "Migration completed successfully.\n";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
