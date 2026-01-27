<?php
/**
 * ADMIN API: /admin/api/options-delete.php
 * DELETE - Remove option from product
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
    $option_id = isset($input['option_id']) ? intval($input['option_id']) : 0;
    
    if (!$option_id) {
        throw new Exception('Option ID required');
    }
    
    // Start transaction for atomic deletion
    if (!$db->begin_transaction()) {
        throw new Exception('Failed to start transaction');
    }
    
    try {
        // Delete option choices with prepared statement
        $sql = "DELETE FROM product_option_choices WHERE option_id = ?";
        $stmt = $db->prepare($sql);
        if (!$stmt) {
            throw new Exception('Failed to prepare choices delete statement');
        }
        $stmt->bind_param('i', $option_id);
        if (!$stmt->execute()) {
            throw new Exception('Failed to delete choices');
        }
        
        // Delete option with prepared statement
        $sql = "DELETE FROM product_options WHERE id = ?";
        $stmt = $db->prepare($sql);
        if (!$stmt) {
            throw new Exception('Failed to prepare option delete statement');
        }
        $stmt->bind_param('i', $option_id);
        if (!$stmt->execute()) {
            throw new Exception('Failed to delete option');
        }
        
        // Commit transaction
        $db->commit();
    } catch (Exception $e) {
        $db->rollback();
        throw $e;
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Option deleted'
    ]);
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

?>
