<?php
/**
 * Database Configuration & Connection
 * Mario Bramy Project
 */

// Load secrets if available (for production/custom credentials)
if (file_exists(__DIR__ . '/secrets.php')) {
    include_once __DIR__ . '/secrets.php';
}

// Database credentials (with fallback to XAMPP defaults)
if (!defined('DB_HOST')) define('DB_HOST', defined('ENV_DB_HOST') ? ENV_DB_HOST : 'localhost');
if (!defined('DB_USER')) define('DB_USER', defined('ENV_DB_USER') ? ENV_DB_USER : 'root');
if (!defined('DB_PASSWORD')) define('DB_PASSWORD', defined('ENV_DB_PASSWORD') ? ENV_DB_PASSWORD : '');
if (!defined('DB_NAME')) define('DB_NAME', defined('ENV_DB_NAME') ? ENV_DB_NAME : 'mario_bramy');

// Create connection
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// Check connection
if ($db->connect_error) {
    error_log('Database connection failed: ' . $db->connect_error);
    die('Database connection error. Please try again later.');
}

// Set charset to UTF-8
$db->set_charset('utf8mb4');

// Enable error reporting for development
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

/**
 * Helper function: Safe query execution
 * @param string $sql SQL query
 * @param array $params Parameters for prepared statement
 * @return mysqli_result|bool
 */
function db_query($sql, $params = []) {
    global $db;
    
    if (empty($params)) {
        return $db->query($sql);
    }
    
    $stmt = $db->prepare($sql);
    if (!$stmt) {
        error_log('SQL Error: ' . $db->error);
        return false;
    }
    
    if (!empty($params)) {
        $stmt->bind_param(str_repeat('s', count($params)), ...$params);
    }
    
    $stmt->execute();
    return $stmt->get_result();
}

/**
 * Helper function: Execute statement (INSERT, UPDATE, DELETE)
 * @param string $sql SQL query
 * @param array $params Parameters
 * @return bool
 */
function db_execute($sql, $params = []) {
    global $db;
    
    $stmt = $db->prepare($sql);
    if (!$stmt) {
        error_log('SQL Error: ' . $db->error);
        return false;
    }
    
    if (!empty($params)) {
        $stmt->bind_param(str_repeat('s', count($params)), ...$params);
    }
    
    return $stmt->execute();
}

/**
 * Helper function: Get last insert ID
 * @return int
 */
function db_last_id() {
    global $db;
    return $db->insert_id;
}

/**
 * Helper function: Get last error
 * @return string
 */
function db_error() {
    global $db;
    return $db->error;
}

?>
