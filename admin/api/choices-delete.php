<?php
/**
 * ADMIN API: /admin/api/choices-delete.php
 * DELETE - Remove choice from option
 */

include '../../config/database.php';
include '../../config/auth.php';
include '../../config/security.php';

header('Content-Type: application/json; charset=utf-8');

require_login();

// Enforce HTTP method
if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    http_response_code(405);
    header('Allow: DELETE');
    die(json_encode(['success' => false, 'message' => 'Method Not Allowed']));
}

// Check admin authorization
if (!is_admin()) {
    http_response_code(403);
    die(json_encode(['success' => false, 'message' => 'Forbidden']));
}

try {
    // Verify CSRF token
    verify_csrf_token();
    
    $input = json_decode(file_get_contents('php://input'), true);
    $choice_id = isset($input['choice_id']) ? intval($input['choice_id']) : 0;
    
    if (!$choice_id) {
        throw new Exception('Choice ID required');
    }
    
    $sql = "DELETE FROM product_option_choices WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param('i', $choice_id);
    
    if (!$stmt->execute()) {
        throw new Exception('Failed to delete choice');
    }
    
    if ($stmt->affected_rows <= 0) {
        throw new Exception('Choice ID not found or already deleted');
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Choice deleted'
    ]);
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

?>
