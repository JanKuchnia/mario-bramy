<?php
/**
 * API: /api/portfolio.php
 * Returns portfolio images for specific category as JSON
 * GET /api/portfolio.php?category=bramy-przesuwne
 */

include '../config/database.php';

// Set JSON header
header('Content-Type: application/json; charset=utf-8');

// Get category from query parameter
$category_slug = isset($_GET['category']) ? sanitize_input($_GET['category']) : 'all';

try {
    // Build query
    if ($category_slug === 'all') {
        // Get all images
        $sql = "SELECT 
                    pi.id, 
                    pi.filename, 
                    pi.alt_text, 
                    pc.slug as category_slug,
                    pc.name as category_name
                FROM portfolio_images pi
                JOIN portfolio_categories pc ON pi.category_id = pc.id
                WHERE pi.is_visible = 1
                ORDER BY pi.sort_order ASC";
        
        $result = $db->query($sql);
    } else {
        // Get images for specific category
        $sql = "SELECT 
                    pi.id, 
                    pi.filename, 
                    pi.alt_text,
                    pc.slug as category_slug,
                    pc.name as category_name
                FROM portfolio_images pi
                JOIN portfolio_categories pc ON pi.category_id = pc.id
                WHERE pc.slug = ? AND pi.is_visible = 1
                ORDER BY pi.sort_order ASC";
        
        $stmt = $db->prepare($sql);
        $stmt->bind_param('s', $category_slug);
        $stmt->execute();
        $result = $stmt->get_result();
    }
    
    if (!$result) {
        throw new Exception('Database query failed');
    }
    
    // Fetch all images
    $images = [];
    while ($row = $result->fetch_assoc()) {
        $images[] = [
            'id' => (int)$row['id'],
            'filename' => $row['filename'],
            'alt_text' => htmlspecialchars($row['alt_text']),
            'category_slug' => $row['category_slug'],
            'category_name' => htmlspecialchars($row['category_name']),
            'url' => '/mario-bramy/uploads/portfolio/' . $row['category_slug'] . '/' . $row['filename']
        ];
    }
    
    // Return JSON response
    echo json_encode([
        'success' => true,
        'category' => $category_slug,
        'count' => count($images),
        'images' => $images
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error fetching portfolio images'
    ]);
    error_log('Portfolio API Error: ' . $e->getMessage());
}

?>
