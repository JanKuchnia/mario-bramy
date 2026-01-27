<?php
/**
 * ADMIN API: /admin/api/gallery-list.php
 * GET - List all portfolio images
 */

include '../../config/database.php';
include '../../config/auth.php';

header('Content-Type: application/json; charset=utf-8');

// Require admin login
require_login();

try {
    $sql = "SELECT pi.id, pi.filename, pi.alt_text, pc.slug as category_slug, pi.uploaded_at
            FROM portfolio_images pi
            JOIN portfolio_categories pc ON pi.category_id = pc.id
            WHERE pi.is_visible = 1
            ORDER BY pi.uploaded_at DESC";
    
    $result = $db->query($sql);
    
    if (!$result) {
        throw new Exception('Database query failed');
    }
    
    $images = [];
    while ($row = $result->fetch_assoc()) {
        $images[] = [
            'id' => (int)$row['id'],
            'filename' => htmlspecialchars($row['filename']),
            'alt_text' => htmlspecialchars($row['alt_text']),
            'category_slug' => $row['category_slug'],
            'uploaded_at' => date('d.m.Y H:i', strtotime($row['uploaded_at']))
        ];
    }
    
    echo json_encode([
        'success' => true,
        'count' => count($images),
        'images' => $images
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error fetching images'
    ]);
    error_log('Gallery List Error: ' . $e->getMessage());
}

?>
