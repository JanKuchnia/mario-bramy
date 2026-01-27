<?php
/**
 * ADMIN API: /admin/api/gallery-delete.php
 * DELETE - Remove portfolio image
 */

include '../../config/database.php';
include '../../config/security.php';
include '../../config/auth.php';

header('Content-Type: application/json; charset=utf-8');

// Require admin login
require_login();

try {
    // Verify CSRF token
    verify_csrf_token();
    
    // Get JSON body
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($input['image_id'])) {
        throw new Exception('Image ID required');
    }
    
    $image_id = intval($input['image_id']);
    
    // Get image info
    $sql = "SELECT filename, category_id FROM portfolio_images WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param('i', $image_id);
    $stmt->execute();
    $image_query = $stmt->get_result();
    $image = $image_query ? $image_query->fetch_assoc() : null;
    
    if (!$image) {
        throw new Exception('Image not found');
    }
    
    // Get category slug
    $category_id = $image['category_id'];
    $sql = "SELECT slug FROM portfolio_categories WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param('i', $category_id);
    $stmt->execute();
    $category_query = $stmt->get_result();
    $category = $category_query ? $category_query->fetch_assoc() : null;
    
    // Verify category exists before using slug
    if (!$category) {
        // Proceed with database deletion but log missing category
        error_log('Warning: Image ID ' . $image_id . ' references missing category ID ' . $category_id);
        $category = ['slug' => 'unknown'];
    }
    
    // Delete from database (soft delete)
    $sql = "UPDATE portfolio_images SET is_visible = 0 WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param('i', $image_id);
    
    if (!$stmt->execute()) {
        throw new Exception('Failed to delete image from database');
    }
    
    // Optionally delete physical files
    $file_path = __DIR__ . '/../../uploads/portfolio/' . $category['slug'] . '/' . $image['filename'];
    $webp_path = str_replace(['.jpg', '.png', '.jpeg'], '.webp', $file_path);
    $thumb_path = str_replace('.webp', '-thumb.webp', $webp_path);
    
    @unlink($file_path);
    @unlink($webp_path);
    @unlink($thumb_path);
    
    echo json_encode([
        'success' => true,
        'message' => 'Image deleted successfully'
    ]);
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
    error_log('Gallery Delete Error: ' . $e->getMessage());
}

?>
