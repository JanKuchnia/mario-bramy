<?php
/**
 * Authentication Functions
 * Session management, Password hashing, Login checks
 */

// Start session if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('SESSION_TIMEOUT', 1800); // 30 minutes in seconds

/**
 * Hash password using bcrypt
 * @param string $password
 * @return string
 */
function hash_password($password) {
    return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
}

/**
 * Verify password against hash
 * @param string $password
 * @param string $hash
 * @return bool
 */
function verify_password($password, $hash) {
    return password_verify($password, $hash);
}

/**
 * Require login - redirect if not authenticated
 * @return void
 */
function require_login() {
    if (!isset($_SESSION['admin_id'])) {
        header('Location: /admin/login.php');
        exit;
    }
}

/**
 * Check session timeout
 * If last activity > SESSION_TIMEOUT, destroy session
 * @param int $timeout_minutes
 * @return bool True if session still valid
 */
function check_session_timeout($timeout_minutes = 30) {
    $timeout_seconds = $timeout_minutes * 60;
    
    if (isset($_SESSION['last_activity'])) {
        $elapsed = time() - $_SESSION['last_activity'];
        
        if ($elapsed > $timeout_seconds) {
            // Session expired
            session_destroy();
            header('Location: /admin/login.php?timeout=1');
            exit;
        }
    }
    
    return true;
}

/**
 * Create login session
 * @param int $admin_id
 * @param string $ip_address (optional)
 * @return void
 */
function create_session($admin_id, $ip_address = null) {
    $_SESSION['admin_id'] = $admin_id;
    $_SESSION['last_activity'] = time();
    
    if ($ip_address) {
        $_SESSION['login_ip'] = $ip_address;
    }
}

/**
 * Destroy login session (logout)
 * @return void
 */
function destroy_session() {
    $_SESSION = [];
    
    if (ini_get('session.use_cookies') === '1' || ini_get('session.use_cookies') === '0') {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }
    
    session_destroy();
}

/**
 * Get current admin ID
 * @return int|null
 */
function get_current_admin_id() {
    return $_SESSION['admin_id'] ?? null;
}

/**
 * Check if user is admin
 * @return bool
 */
function is_admin() {
    return isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id']);
}

/**
 * Reset session activity timer
 * @return void
 */
function reset_activity_timer() {
    $_SESSION['last_activity'] = time();
}

?>
