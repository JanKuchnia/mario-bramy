<?php
/**
 * Application Constants
 */

// Base URL logic
$is_localhost = ($_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['HTTP_HOST'] === '127.0.0.1');
$url_prefix = $is_localhost ? '/mario-bramy' : '';

define('BASE_URL', 'http' . (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . $url_prefix);
define('BASE_PATH', __DIR__ . '/..');

// Upload directories
define('UPLOAD_DIR_PORTFOLIO', BASE_PATH . '/uploads/portfolio');
define('UPLOAD_DIR_PRODUCTS', BASE_PATH . '/uploads/products');
define('UPLOAD_URL_PORTFOLIO', $url_prefix . '/uploads/portfolio');
define('UPLOAD_URL_PRODUCTS', $url_prefix . '/uploads/products');

// Log directory
define('LOG_DIR', BASE_PATH . '/logs');

// File upload limits
define('MAX_UPLOAD_SIZE', 10485760); // 10MB
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/webp']);

// Session timeout
define('SESSION_TIMEOUT_MINUTES', 30);

// Rate limiting
define('RATE_LIMIT_LOGIN_ATTEMPTS', 5);
define('RATE_LIMIT_LOGIN_WINDOW', 900); // 15 minutes
define('RATE_LIMIT_CONTACT_ATTEMPTS', 5);
define('RATE_LIMIT_CONTACT_WINDOW', 3600); // 1 hour

// Image quality for WebP conversion
define('WEBP_QUALITY', 80);
define('THUMBNAIL_WIDTH', 400);
define('THUMBNAIL_HEIGHT', 300);

// Google Places API
define('GOOGLE_PLACES_API_ENDPOINT', 'https://maps.googleapis.com/maps/api/place/details/json');

?>
