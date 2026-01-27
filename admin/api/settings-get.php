<?php
/**
 * ADMIN API: Get settings (JSON)
 * GET /admin/api/settings-get.php?name=shop_target
 */

include '../../config/database.php';
include '../../config/auth.php';
include '../../config/security.php';
include '../../config/settings.php';

header('Content-Type: application/json; charset=utf-8');

try {
    require_login();

    $name = isset($_GET['name']) ? $_GET['name'] : null;
    if (!$name) {
        throw new Exception('Missing name');
    }

    $value = get_setting($name, null);

    echo json_encode(['success' => true, 'name' => $name, 'value' => $value]);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

?>
