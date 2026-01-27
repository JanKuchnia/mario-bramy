<?php
/**
 * ADMIN API: /admin/api/options-create.php
 * POST - Add option to product (e.g., "Szerokość", "Kolor")
 */

include '../../config/database.php';
include '../../config/security.php';
include '../../config/auth.php';

header('Content-Type: application/json; charset=utf-8');

require_login();

try {
    // Verify CSRF token
    verify_csrf_token();
    
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    $key = isset($_POST['key']) ? sanitize_input($_POST['key']) : '';
    $label = isset($_POST['label']) ? sanitize_input($_POST['label']) : '';
    $type = isset($_POST['type']) ? sanitize_input($_POST['type']) : 'select';
    
    if (!$product_id || !$key || !$label) {
        throw new Exception('Missing required fields');
    }
    
    // Check product exists
    $sql = "SELECT id FROM products WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param('i', $product_id);
    $stmt->execute();
    $check = $stmt->get_result();
    
    if (!$check || $check->num_rows == 0) {
        throw new Exception('Product not found');
    }
    
    // Insert option
    $sql = "INSERT INTO product_options (product_id, key_name, label, option_type)
            VALUES (?, ?, ?, ?)";
    
    $stmt = $db->prepare($sql);
    $stmt->bind_param('isss', $product_id, $key, $label, $type);
    
    if (!$stmt->execute()) {
        throw new Exception('Failed to create option');
    }
    
    $option_id = $db->insert_id;
    
    echo json_encode([
        'success' => true,
        'option_id' => $option_id,
        'message' => 'Option added'
    ]);
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

?>
