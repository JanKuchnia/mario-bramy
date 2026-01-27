<?php
/**
 * ADMIN API: Update simple setting
 * POST: { name: 'shop_target', value: 'wkrotce' }
 */

include '../../config/database.php';
include '../../config/auth.php';
include '../../config/security.php';
include '../../config/settings.php';

header('Content-Type: application/json; charset=utf-8');

try {
    require_login();
    verify_csrf_token();

    $name = isset($_POST['name']) ? $_POST['name'] : null;
    $value = isset($_POST['value']) ? $_POST['value'] : null;

    if (!$name || !$value) {
        throw new Exception('Missing parameters');
    }

    // Only allow shop_target and only known values
    if ($name === 'shop_target') {
        $allowed = ['wkrotce', 'sklep'];
        if (!in_array($value, $allowed, true)) {
            throw new Exception('Invalid value');
        }
    }

    $ok = set_setting($name, $value);
    if (!$ok) throw new Exception('Failed to save setting');

    echo json_encode(['success' => true, 'name' => $name, 'value' => $value]);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

?>
