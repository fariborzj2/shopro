<?php

// Simulate the environment with fixed logic

// Input simulation
$_POST['meta_keywords'] = ['keyword1', 'keyword2'];

echo "Simulating Controller Logic (Fixed)...\n";

// In Controller (Fixed):
$meta_keywords = $_POST['meta_keywords'] ?? null;
echo "Controller passes: " . print_r($meta_keywords, true) . "\n";

// In Model (Fixed):
if (isset($meta_keywords) && is_array($meta_keywords)) {
    $dbValue = json_encode($meta_keywords, JSON_UNESCAPED_UNICODE);
} else {
    $dbValue = $meta_keywords;
}

echo "Model saves to DB: " . $dbValue . "\n";

if ($dbValue === '["keyword1","keyword2"]') {
    echo "SUCCESS: Data is correctly encoded as JSON.\n";
} else {
    echo "FAILURE: Data is not JSON.\n";
}
