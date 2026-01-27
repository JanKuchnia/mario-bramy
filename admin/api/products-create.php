<?php
/**
 * ADMIN API: /admin/api/products-create.php
 * POST - Create new product
 */

include '../../config/database.php';
include '../../config/constants.php';
include '../../config/security.php';
include '../../config/auth.php';

header('Content-Type: application/json; charset=utf-8');

// Require admin login
require_login();

try {
    // Verify CSRF token
    verify_csrf_token();
    
    // Validate inputs
    $name = isset($_POST['name']) ? sanitize_input($_POST['name']) : '';
    $description = isset($_POST['description']) ? sanitize_input($_POST['description']) : '';
    $base_price = isset($_POST['base_price']) ? floatval($_POST['base_price']) : 0;
    
    if (!$name || $base_price <= 0) {
        throw new Exception('Product name and price required');
    }
    
    // Handle image upload
    $image_filename = '';
    if (isset($_FILES['image'])) {
        $file = validate_file_upload($_FILES['image'], ALLOWED_IMAGE_TYPES, MAX_UPLOAD_SIZE);
        if (!$file) {
            throw new Exception('Invalid image');
        }
        
        $upload_dir = UPLOAD_DIR_PRODUCTS;
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        
        $filename = sanitize_input(pathinfo($file['name'], PATHINFO_FILENAME)) . '_' . time();
        $original_path = $upload_dir . '/' . $filename . '.jpg';
        $webp_path = $upload_dir . '/' . $filename . '.webp';
        
        if (!move_uploaded_file($file['tmp_name'], $original_path)) {
            throw new Exception('Failed to upload image');
        }
        
        // Convert to WebP
        convertToWebP($original_path, $webp_path, WEBP_QUALITY);
        $image_filename = $filename . '.webp';
    }
    
    // Insert product
    $sql = "INSERT INTO products (name, description, base_price, image_filename, is_visible, created_at)
            VALUES (?, ?, ?, ?, 1, NOW())";
    
    $stmt = $db->prepare($sql);
    $stmt->bind_param('ssds', $name, $description, $base_price, $image_filename);
    
    if (!$stmt->execute()) {
        throw new Exception('Failed to create product');
    }
    
    $product_id = $db->insert_id;
    
    echo json_encode([
        'success' => true,
        'product_id' => $product_id,
        'message' => 'Product created successfully'
    ]);
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
    error_log('Product Create Error: ' . $e->getMessage());
}

function convertToWebP($source, $destination, $quality = 80) {
    if (!extension_loaded('imagick')) {
        return convertToWebPGD($source, $destination, $quality);
    }
    try {
        $image = new Imagick($source);
        $image->setImageFormat('webp');
        $image->setImageCompressionQuality($quality);
        $result = $image->writeImage($destination);
        $image->clear();
        $image->destroy();
        return $result;
    } catch (Exception $e) {
        return convertToWebPGD($source, $destination, $quality);
    }
}

function convertToWebPGD($source, $destination, $quality = 80) {
    if (!extension_loaded('gd')) return false;
    try {
        if (!file_exists($source) || !is_readable($source)) {
            return false;
        }
        
        $image_info = @getimagesize($source);
        if (!$image_info) return false;
        
        $mime = $image_info['mime'];
        $image = null;
        
        switch ($mime) {
            case 'image/jpeg': 
                $image = @imagecreatefromjpeg($source); 
                break;
            case 'image/png': 
                $image = @imagecreatefrompng($source); 
                break;
            case 'image/webp': 
                $image = @imagecreatefromwebp($source); 
                break;
            default: 
                return false;
        }
        
        if (!$image) return false;
        
        $result = @imagewebp($image, $destination, $quality);
        @imagedestroy($image);
        
        return $result && file_exists($destination);
    } catch (Exception $e) {
        error_log('WebP conversion error: ' . $e->getMessage());
        return false;
    }
}

?>
