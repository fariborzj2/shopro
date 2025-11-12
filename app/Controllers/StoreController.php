<?php

// app/Controllers/StoreController.php

namespace App\Controllers;

use App\Models\Category;
use App\Models\Product;

class StoreController
{
    public function getStoreData()
    {
        header('Content-Type: application/json');

        try {
            // Fetch active categories and products using static methods
            $categories = Category::getActiveCategoriesForStore();
            $products = Product::getActiveProductsForStore();

            $response = [
                'categories' => $categories,
                'products' => $products,
            ];

            echo json_encode($response);

        } catch (\Exception $e) {
            // In a real app, you should log the error message.
            // error_log($e->getMessage());
            http_response_code(500);
            echo json_encode(['error' => 'Could not fetch store data.']);
        }
    }
}
