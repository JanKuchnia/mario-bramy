<?php
/**
 * ADMIN API: /admin/api/choices-create.php
 * POST - Add choice to option (e.g., "4.0m", "5.0m" for "Szerokość")
 */

include '../../config/database.php';
include '../../config/security.php';
include '../../config/auth.php';

header('Content-Type: application/json; charset=utf-8');

require_login();

try {
    // Verify CSRF token
    verify_csrf_token();
    
    $option_id = isset($_POST['option_id']) ? intval($_POST['option_id']) : 0;
    $label = isset($_POST['label']) ? sanitize_input($_POST['label']) : '';
    $value = isset($_POST['value']) ? sanitize_input($_POST['value']) : '';
    $price_modifier = isset($_POST['price_modifier']) ? floatval($_POST['price_modifier']) : 0;
    
    if (!$option_id || !$label) {
        throw new Exception('Missing required fields');
    }
    
    // Check option exists with prepared statement
    $sql = "SELECT id FROM product_options WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param('i', $option_id);
    $stmt->execute();
    $check = $stmt->get_result();
    
    if (!$check || $check->num_rows == 0) {
        throw new Exception('Option not found');
    }
    
    // Insert choice
    $sql = "INSERT INTO product_option_choices (option_id, label, value, price_modifier)
            VALUES (?, ?, ?, ?)";
    
    $stmt = $db->prepare($sql);
    $stmt->bind_param('issd', $option_id, $label, $value, $price_modifier);
    
    if (!$stmt->execute()) {
        throw new Exception('Failed to create choice');
    }
    
    $choice_id = $db->insert_id;
    
    echo json_encode([
        'success' => true,
        'choice_id' => $choice_id,
        'message' => 'Choice added'
    ]);
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

?>
