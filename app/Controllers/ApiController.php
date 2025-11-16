<?php

namespace App\Controllers;

use App\Models\Product;
use App\Models\CustomOrderField;

class ApiController
{
    /**
     * Get product details along with custom fields for its category.
     *
     * @param int $id Product ID
     */
    public function productDetails($id)
    {
        header('Content-Type: application/json');

        $product = Product::find($id);

        if (!$product) {
            echo json_encode(['error' => 'Product not found']);
            http_response_code(404);
            return;
        }

        // This assumes a method exists to get custom fields by category ID.
        // We will need to implement this logic in the CustomOrderField model.
        $customFields = CustomOrderField::findByCategoryId($product->category_id);

        $response = [
            'product' => [
                'id' => $product->id,
                'name' => $product->name_fa,
                'price' => (float)$product->price,
                'imageUrl' => $product->image_url ?? 'https://placehold.co/400x400/EEE/31343C?text=No+Image',
                'description' => $product->description ?? ''
            ],
            'custom_fields' => array_map(function($field) {
                return [
                    'id' => $field->id,
                    'name' => $field->name,
                    'label' => $field->label_fa,
                    'type' => $field->type,
                    'options' => json_decode($field->options) ?? [],
                    'is_required' => (bool)$field->is_required,
                    'placeholder' => $field->placeholder,
                ];
            }, $customFields)
        ];

        echo json_encode($response);
    }
}
