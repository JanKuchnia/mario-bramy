<?php
/**
 * ADMIN API: /admin/api/gallery-upload.php
 * POST - Upload new portfolio image with WebP conversion
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
    
    // Validate file upload
    if (!isset($_FILES['image'])) {
        throw new Exception('No file provided');
    }
    
    $file = validate_file_upload($_FILES['image'], ALLOWED_IMAGE_TYPES, MAX_UPLOAD_SIZE);
    if (!$file) {
        throw new Exception('Invalid file format or size');
    }
    
    // Validate category
    if (!isset($_POST['category_id'])) {
        throw new Exception('Category required');
    }
    
    $category_id = intval($_POST['category_id']);
    $alt_text = isset($_POST['alt_text']) ? sanitize_input($_POST['alt_text']) : '';
    
    // Check category exists with prepared statement
    $sql = "SELECT slug FROM portfolio_categories WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param('i', $category_id);
    $stmt->execute();
    $category_query = $stmt->get_result();
    $category = $category_query ? $category_query->fetch_assoc() : null;
    
    if (!$category) {
        throw new Exception('Category not found');
    }
    
    // Create upload directory if not exists
    $upload_dir = UPLOAD_DIR_PORTFOLIO . '/' . $category['slug'];
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    
    // Generate filename
    $original_name = pathinfo($file['name'], PATHINFO_FILENAME);
    $timestamp = time();
    $filename = sanitize_input($original_name) . '_' . $timestamp;
    
    // Save original file
    $temp_path = $file['tmp_name'];
    $original_path = $upload_dir . '/' . $filename . '.jpg';
    
    if (!move_uploaded_file($temp_path, $original_path)) {
        throw new Exception('Failed to save file');
    }
    
    // Convert to WebP
    $webp_path = $upload_dir . '/' . $filename . '.webp';
    if (!convertToWebP($original_path, $webp_path, WEBP_QUALITY)) {
        throw new Exception('WebP conversion failed');
    }
    
    // Create thumbnail
    $thumb_path = $upload_dir . '/' . $filename . '-thumb.webp';
    if (!createThumbnail($webp_path, $thumb_path, THUMBNAIL_WIDTH, THUMBNAIL_HEIGHT)) {
        // Thumbnail creation failed, but continue
    }
    
    // Insert into database
    $webp_filename = $filename . '.webp';
    $sql = "INSERT INTO portfolio_images (category_id, filename, alt_text, is_visible, uploaded_at)
            VALUES (?, ?, ?, 1, NOW())";
    
    $stmt = $db->prepare($sql);
    $stmt->bind_param('iss', $category_id, $webp_filename, $alt_text);
    
    if (!$stmt->execute()) {
        throw new Exception('Database insert failed');
    }
    
    $image_id = $db->insert_id;
    
    echo json_encode([
        'success' => true,
        'image_id' => $image_id,
        'message' => 'Image uploaded and converted to WebP',
        'filename' => $webp_filename
    ]);
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
    error_log('Gallery Upload Error: ' . $e->getMessage());
}

/**
 * Convert image to WebP format
 */
function convertToWebP($source, $destination, $quality = 80) {
    if (!extension_loaded('imagick')) {
        // Fallback to GD if ImageMagick not available
        return convertToWebPGD($source, $destination, $quality);
    }
    
    try {
        $image = new Imagick($source);
        $image->setImageFormat('webp');
        $image->setImageCompressionQuality($quality);
        return $image->writeImage($destination);
    } catch (Exception $e) {
        error_log('ImageMagick conversion failed: ' . $e->getMessage());
        return convertToWebPGD($source, $destination, $quality);
    }
}

/**
 * Convert to WebP using GD Library
 */
function convertToWebPGD($source, $destination, $quality = 80) {
    if (!extension_loaded('gd')) {
        return false;
    }
    
    try {
        $image_info = getimagesize($source);
        $mime = $image_info['mime'];
        
        // Create image resource
        switch ($mime) {
            case 'image/jpeg':
                $image = imagecreatefromjpeg($source);
                break;
            case 'image/png':
                $image = imagecreatefrompng($source);
                break;
            case 'image/webp':
                $image = imagecreatefromwebp($source);
                break;
            default:
                return false;
        }
        
        if (!$image) {
            return false;
        }
        
        // Convert to WebP
        $result = imagewebp($image, $destination, $quality);
        imagedestroy($image);
        
        return $result;
    } catch (Exception $e) {
        error_log('GD WebP conversion failed: ' . $e->getMessage());
        return false;
    }
}

/**
 * Create thumbnail image
 */
function createThumbnail($source, $destination, $width = 400, $height = 300) {
    if (!extension_loaded('gd')) {
        return false;
    }
    
    try {
        $image = imagecreatefromwebp($source);
        if (!$image) {
            return false;
        }
        
        // Create thumbnail (crop to aspect ratio)
        $thumb = imagecrop($image, [
            'x' => 0,
            'y' => 0,
            'width' => $width,
            'height' => $height
        ]);
        
        if (!$thumb) {
            $thumb = $image;
        }
        
        $result = imagewebp($thumb, $destination, 85);
        imagedestroy($image);
        imagedestroy($thumb);
        
        return $result;
    } catch (Exception $e) {
        error_log('Thumbnail creation failed: ' . $e->getMessage());
        return false;
    }
}

?>
