<?php
/**
 * Settings helper - store simple key/value settings in DB
 */

// Ensure we have DB connection available
if (!isset($db)) {
    // database.php lives in ../config relative to includes and public pages
    if (file_exists(__DIR__ . '/database.php')) {
        include_once __DIR__ . '/database.php';
    }
}

/**
 * Get a setting value by name
 * @param string $name
 * @param mixed $default
 * @return mixed
 */
function get_setting($name, $default = null) {
    global $db;
    if (!isset($db)) return $default;

    $sql = "SELECT `value` FROM `settings` WHERE `name` = ? LIMIT 1";
    $stmt = $db->prepare($sql);
    if (!$stmt) return $default;
    $stmt->bind_param('s', $name);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res && $row = $res->fetch_assoc()) {
        return $row['value'];
    }
    return $default;
}

/**
 * Set a setting value (insert or update)
 * @param string $name
 * @param string $value
 * @return bool
 */
function set_setting($name, $value) {
    global $db;
    if (!isset($db)) return false;

    $sql = "INSERT INTO `settings` (`name`, `value`) VALUES (?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`), `updated_at` = CURRENT_TIMESTAMP";
    $stmt = $db->prepare($sql);
    if (!$stmt) return false;
    $stmt->bind_param('ss', $name, $value);
    return $stmt->execute();
}

?>
