<?php
/**
 * Security Functions
 * CSRF tokens, Rate limiting, Input sanitization
 */

// Start session if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('CSRF_TOKEN_NAME', 'csrf_token');
define('CSRF_TOKEN_LIFETIME', 3600); // 1 hour

/**
 * Generate CSRF token
 * @return string
 */
function generate_csrf_token() {
    if (empty($_SESSION[CSRF_TOKEN_NAME])) {
        $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
        $_SESSION['csrf_token_time'] = time();
    }
    return $_SESSION[CSRF_TOKEN_NAME];
}

/**
 * Validate CSRF token
 * @param string $token
 * @return bool
 */
function validate_csrf_token($token) {
    if (empty($_SESSION[CSRF_TOKEN_NAME])) {
        return false;
    }
    
    // Check if token matches
    if (!hash_equals($_SESSION[CSRF_TOKEN_NAME], $token)) {
        return false;
    }
    
    // Check if token is still valid
    if (time() - $_SESSION['csrf_token_time'] > CSRF_TOKEN_LIFETIME) {
        unset($_SESSION[CSRF_TOKEN_NAME]);
        return false;
    }
    
    return true;
}

/**
 * Verify CSRF token from request
 * Checks POST, JSON, or header
 * Throws exception if invalid
 * @throws Exception
 */
function verify_csrf_token() {
    $token = null;
    
    // Check POST data first
    if (isset($_POST[CSRF_TOKEN_NAME])) {
        $token = $_POST[CSRF_TOKEN_NAME];
    }
    // Check JSON body
    else if (isset($_SERVER['CONTENT_TYPE']) && stripos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
        $json = json_decode(file_get_contents('php://input'), true);
        if (isset($json[CSRF_TOKEN_NAME])) {
            $token = $json[CSRF_TOKEN_NAME];
        }
    }
    // Check X-CSRF-Token header
    else if (isset($_SERVER['HTTP_X_CSRF_TOKEN'])) {
        $token = $_SERVER['HTTP_X_CSRF_TOKEN'];
    }
    
    // Validate token
    if (!$token || !validate_csrf_token($token)) {
        http_response_code(403);
        throw new Exception('Invalid or missing CSRF token');
    }
}

/**
 * Rate limiting check
 * Prevents brute force attacks
 * @param string $ip_address
 * @param string $action (login, contact, etc)
 * @param int $max_attempts
 * @param int $time_window (seconds)
 * @return bool True if allowed, false if blocked
 */
function check_rate_limit($ip_address, $action = 'default', $max_attempts = 5, $time_window = 900) {
    global $db;
    
    $ip_address = filter_var($ip_address, FILTER_VALIDATE_IP);
    if (!$ip_address) {
        return false;
    }
    
    $cache_key = "rate_limit_{$action}_{$ip_address}";
    
    // Check if limit stored in session (for XAMPP, in-memory)
    if (!isset($_SESSION[$cache_key])) {
        $_SESSION[$cache_key] = ['attempts' => 0, 'first_attempt' => time()];
    }
    
    $attempt_data = &$_SESSION[$cache_key];
    $time_passed = time() - $attempt_data['first_attempt'];
    
    // Reset if time window passed
    if ($time_passed > $time_window) {
        $attempt_data = ['attempts' => 0, 'first_attempt' => time()];
    }
    
    // Check if blocked
    if ($attempt_data['attempts'] >= $max_attempts) {
        return false;
    }
    
    // Increment attempts
    $attempt_data['attempts']++;
    
    return true;
}

/**
 * Get remaining time for rate limit
 * @param string $ip_address
 * @param string $action
 * @param int $time_window
 * @return int Seconds remaining
 */
function get_rate_limit_remaining($ip_address, $action = 'default', $time_window = 900) {
    $cache_key = "rate_limit_{$action}_{$ip_address}";
    
    if (!isset($_SESSION[$cache_key])) {
        return 0;
    }
    
    $attempt_data = $_SESSION[$cache_key];
    $time_passed = time() - $attempt_data['first_attempt'];
    $remaining = $time_window - $time_passed;
    
    return max(0, $remaining);
}

/**
 * Sanitize input
 * @param string $input
 * @return string
 */
function sanitize_input($input) {
    return htmlspecialchars(stripslashes(trim($input)), ENT_QUOTES, 'UTF-8');
}

/**
 * Validate email
 * @param string $email
 * @return bool|string False if invalid, email if valid
 */
function validate_email($email) {
    $email = sanitize_input($email);
    return filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : false;
}

/**
 * Validate URL
 * @param string $url
 * @return bool|string
 */
function validate_url($url) {
    return filter_var($url, FILTER_VALIDATE_URL) ? $url : false;
}

/**
 * Escape string for database
 * @param string $string
 * @return string
 */
function db_escape($string) {
    global $db;
    return $db->real_escape_string($string);
}

/**
 * Validate file upload
 * @param array $file $_FILES['field']
 * @param array $allowed_types
 * @param int $max_size (bytes)
 * @return array|false
 */
function validate_file_upload($file, $allowed_types = ['image/jpeg', 'image/png', 'image/webp'], $max_size = 10485760) {
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return false;
    }
    
    // Check MIME type
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    if (!in_array($mime_type, $allowed_types)) {
        return false;
    }
    
    // Check file size
    if ($file['size'] > $max_size) {
        return false;
    }
    
    return [
        'name' => sanitize_input($file['name']),
        'tmp_name' => $file['tmp_name'],
        'mime_type' => $mime_type,
        'size' => $file['size']
    ];
}

/**
 * Generate secure filename
 * @param string $filename
 * @return string
 */
function generate_secure_filename($filename) {
    // Remove special characters
    $filename = preg_replace('/[^a-zA-Z0-9._-]/', '', basename($filename));
    // Add timestamp to make unique
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    $name = pathinfo($filename, PATHINFO_FILENAME);
    return $name . '_' . time() . '.' . $ext;
}

?>
