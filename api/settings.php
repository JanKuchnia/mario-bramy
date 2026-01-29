<?php
require_once __DIR__ . '/../config/init.php';

// Allow only admin to modify settings
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireAdmin();
    
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (isset($input['shop_active'])) {
        $settings = ['shop_active' => (bool)$input['shop_active']];
        file_put_contents(__DIR__ . '/../config/settings.json', json_encode($settings, JSON_PRETTY_PRINT));
        jsonSuccess(['message' => 'Ustawienia zaktualizowane', 'shop_active' => $settings['shop_active']]);
    } else {
        jsonError('Brak wymaganych danych');
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Public endpoint (or admin only? Public needs to know? No, public uses getShopUrl in PHP)
    // Admin needs to know status to show toggle
    requireAdmin();
    
    $settingsFile = __DIR__ . '/../config/settings.json';
    if (file_exists($settingsFile)) {
        $settings = json_decode(file_get_contents($settingsFile), true);
        jsonSuccess($settings);
    } else {
        jsonSuccess(['shop_active' => false]); // Default
    }
}
