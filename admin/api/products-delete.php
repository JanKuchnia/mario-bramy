<?php
/**
 * ADMIN API: /admin/api/products-delete.php
 * DELETE - Delete product
 */

include '../../config/database.php';
include '../../config/auth.php';
include '../../config/security.php';

header('Content-Type: application/json; charset=utf-8');

require_login();

try {
    // Verify CSRF token
    verify_csrf_token();
    
    $input = json_decode(file_get_contents('php://input'), true);
    $product_id = isset($input['product_id']) ? intval($input['product_id']) : 0;
    
    if (!$product_id) {
        throw new Exception('Product ID required');
    }
    
    // Check exists with prepared statement
    $sql = "SELECT id FROM products WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param('i', $product_id);
    $stmt->execute();
    $check = $stmt->get_result();
    
    if (!$check || $check->num_rows == 0) {
        throw new Exception('Product not found');
    }
    
    // Soft delete with prepared statement
    $sql = "UPDATE products SET is_visible = 0 WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param('i', $product_id);
    
    if (!$stmt->execute()) {
        throw new Exception('Failed to delete product');
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Product deleted'
    ]);
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

?>
