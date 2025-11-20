<?php

// Simulate the Product fetching logic from the Model
function mockProductFind() {
    $obj = new stdClass();
    $obj->id = 1;
    $obj->name_fa = "Test Product";
    $obj->category_id = 5;
    return $obj;
}

// Simulate the View logic (edit.php)
function renderView($product) {
    echo "ID: " . $product['id'] . "\n";
    echo "Name: " . $product['name_fa'] . "\n";
}

// Scenario 1: Current Buggy Behavior
echo "Scenario 1: Object passed to Array-expecting View\n";
$productObject = mockProductFind();
try {
    // This should fail with "Cannot use object of type stdClass as array"
    renderView($productObject);
} catch (Error $e) {
    echo "Caught expected error: " . $e->getMessage() . "\n";
}

// Scenario 2: Proposed Fix
echo "\nScenario 2: Object cast to Array in Controller\n";
$productObject = mockProductFind();
$productArray = (array) $productObject; // The fix
try {
    renderView($productArray);
    echo "Success!\n";
} catch (Error $e) {
    echo "Failed: " . $e->getMessage() . "\n";
}
