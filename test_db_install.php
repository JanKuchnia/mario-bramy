<?php
require_once __DIR__ . '/config/init.php';

try {
    $db = getDB();
    echo "Connection successful!\n";
    
    // Auto-install if needed
    $stmt = $db->query("SHOW TABLES LIKE 'products'");
    if ($stmt->rowCount() == 0) {
        echo "Installing tables...\n";
        // Run install logic directly
        $_POST['install'] = true;
        require __DIR__ . '/install.php';
        echo "Tables installed.\n";
    } else {
        echo "Tables already exist.\n";
    }

    $count = $db->query("SELECT COUNT(*) FROM products")->fetchColumn();
    echo "Products count: $count\n";

} catch (Exception $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
}
