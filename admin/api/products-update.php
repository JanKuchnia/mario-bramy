<?php
/**
 * ADMIN API: /admin/api/products-update.php
 * POST - Update product details
 */

include '../../config/database.php';
include '../../config/constants.php';
include '../../config/security.php';
include '../../config/auth.php';

header('Content-Type: application/json; charset=utf-8');

require_login();

try {
    // Verify CSRF token
    verify_csrf_token();
    
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    $name = isset($_POST['name']) ? sanitize_input($_POST['name']) : '';
    $description = isset($_POST['description']) ? sanitize_input($_POST['description']) : '';
    $base_price = isset($_POST['base_price']) ? floatval($_POST['base_price']) : 0;
    
    if (!$product_id || !$name || $base_price <= 0) {
        throw new Exception('Invalid product data');
    }
    
    // Check if product exists
    $sql = "SELECT id FROM products WHERE id = ?";
    $stmt_check = $db->prepare($sql);
    $stmt_check->bind_param('i', $product_id);
    $stmt_check->execute();
    $check = $stmt_check->get_result();
    
    if (!$check || $check->num_rows == 0) {
        throw new Exception('Product not found');
    }
    
    // Update product
    $sql = "UPDATE products SET name = ?, description = ?, base_price = ? WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param('ssdi', $name, $description, $base_price, $product_id);
    
    if (!$stmt->execute()) {
        throw new Exception('Update failed');
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Product updated successfully'
    ]);
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

?>
