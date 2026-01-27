<?php
/**
 * API: /api/products.php
 * Returns all products with their options and choices as JSON
 * GET /api/products.php
 */

include '../config/database.php';

// Set JSON header
header('Content-Type: application/json; charset=utf-8');

try {
    // Query all products
    $sql = "SELECT id, name, base_price, description, image_url, sort_order
            FROM products 
            WHERE is_visible = 1
            ORDER BY sort_order ASC";
    
    $result = $db->query($sql);
    
    if (!$result) {
        throw new Exception('Database query failed');
    }
    
    $products = [];
    while ($product_row = $result->fetch_assoc()) {
        $product_id = (int)$product_row['id'];
        
        // Fetch options for this product
        $options_sql = "SELECT id, option_key, option_label, option_type, sort_order
                        FROM product_options
                        WHERE product_id = ?
                        ORDER BY sort_order ASC";
        
        $options_stmt = $db->prepare($options_sql);
        $options_stmt->bind_param('i', $product_id);
        $options_stmt->execute();
        $options_result = $options_stmt->get_result();
        
        $options = [];
        while ($option_row = $options_result->fetch_assoc()) {
            $option_id = (int)$option_row['id'];
            
            // Fetch choices for this option
            $choices_sql = "SELECT label, value, price_modifier, sort_order
                            FROM product_option_choices
                            WHERE option_id = ?
                            ORDER BY sort_order ASC";
            
            $choices_stmt = $db->prepare($choices_sql);
            $choices_stmt->bind_param('i', $option_id);
            $choices_stmt->execute();
            $choices_result = $choices_stmt->get_result();
            
            $choices = [];
            while ($choice_row = $choices_result->fetch_assoc()) {
                $choices[] = [
                    'label' => htmlspecialchars($choice_row['label']),
                    'value' => htmlspecialchars($choice_row['value']),
                    'price_modifier' => (float)$choice_row['price_modifier']
                ];
            }
            
            $options[] = [
                'id' => $option_id,
                'key' => htmlspecialchars($option_row['option_key']),
                'label' => htmlspecialchars($option_row['option_label']),
                'type' => htmlspecialchars($option_row['option_type']),
                'choices' => $choices
            ];
        }
        
        $products[] = [
            'id' => $product_id,
            'name' => htmlspecialchars($product_row['name']),
            'base_price' => (float)$product_row['base_price'],
            'description' => htmlspecialchars($product_row['description']),
            'image_url' => '/mario-bramy' . htmlspecialchars($product_row['image_url']),
            'options' => $options
        ];
    }
    
    // Return JSON response
    echo json_encode([
        'success' => true,
        'count' => count($products),
        'products' => $products
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error fetching products'
    ]);
    error_log('Products API Error: ' . $e->getMessage());
}

?>
