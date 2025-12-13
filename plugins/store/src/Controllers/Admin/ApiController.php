<?php

namespace Store\Controllers\Admin;

use Store\Models\Product;
use Store\Models\CustomOrderField;

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

        // Fetch custom fields for the product's category
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
                $options = [];
                if (in_array($field->type, ['select', 'radio', 'checkbox']) && !empty($field->options)) {
                    // Options are stored as newline separated value:label pairs
                    // e.g. "red:Red Color\nblue:Blue Color"
                    $lines = explode("\n", $field->options);
                    foreach ($lines as $line) {
                        $parts = explode(':', trim($line), 2);
                        if (count($parts) === 2) {
                            $options[] = [
                                'value' => trim($parts[0]),
                                'label' => trim($parts[1])
                            ];
                        }
                    }
                }

                return [
                    'id' => $field->id,
                    'name' => $field->name,
                    'label' => $field->label_fa,
                    'type' => $field->type,
                    'options' => $options,
                    'is_required' => (bool)$field->is_required,
                    'placeholder' => $field->placeholder,
                ];
            }, $customFields)
        ];

        echo json_encode($response);
    }
}
