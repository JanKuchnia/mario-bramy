<?php
require_once __DIR__ . '/config/init.php';

try {
    $db = getDB();
    
    // 1. Fix Schema: Add 'options' column if missing
    // We already know from describe that it is missing.
    // Also rename image_url to image OR simply use image_url.
    // Let's align with the install.php which expects 'image' and 'options'.
    // Actually, sticking to what works: UPDATE table to match install.php
    
    // Check if 'options' exists
    $stmt = $db->query("SHOW COLUMNS FROM products LIKE 'options'");
    if ($stmt->rowCount() == 0) {
        echo "Adding 'options' column...\n";
        $db->exec("ALTER TABLE products ADD COLUMN options JSON DEFAULT NULL");
    }
    
    // Check if 'image' exists, if not check 'image_url'
    $stmt = $db->query("SHOW COLUMNS FROM products LIKE 'image'");
    if ($stmt->rowCount() == 0) {
        $stmt2 = $db->query("SHOW COLUMNS FROM products LIKE 'image_url'");
        if ($stmt2->rowCount() > 0) {
            echo "Renaming 'image_url' to 'image'...\n";
            $db->exec("ALTER TABLE products CHANGE COLUMN image_url image VARCHAR(500) DEFAULT NULL");
        }
    }

    // 2. Insert Data
    // Check if empty
    $count = $db->query("SELECT COUNT(*) FROM products")->fetchColumn();
    if ($count == 0) {
        echo "Table empty. Inserting data...\n";
         $db->exec("
            INSERT INTO products (name, category, base_price, image, description, options) VALUES
            ('Brama Przesuwna Classic', 'bramy', 8500, 'assets/portfolio/bramy-przesuwne-aluminiowe/1.webp', 'Elegancka brama przesuwna z aluminium, idealna do nowoczesnych posesji.', '{\"width\":{\"label\":\"Szerokość\",\"type\":\"select\",\"choices\":[{\"label\":\"4 metry\",\"value\":4,\"priceMod\":0},{\"label\":\"5 metrów\",\"value\":5,\"priceMod\":1500},{\"label\":\"6 metrów\",\"value\":6,\"priceMod\":3000}]},\"color\":{\"label\":\"Kolor\",\"type\":\"select\",\"choices\":[{\"label\":\"Antracyt RAL 7016\",\"value\":\"antracyt\",\"priceMod\":0},{\"label\":\"Biały RAL 9016\",\"value\":\"bialy\",\"priceMod\":0},{\"label\":\"Czarny RAL 9005\",\"value\":\"czarny\",\"priceMod\":200}]},\"automation\":{\"label\":\"Automatyka\",\"type\":\"checkbox\",\"price\":2500}}'),
            ('Brama Dwuskrzydłowa Premium', 'bramy', 7200, 'assets/portfolio/bramy-dwuskrzydlowe/1.webp', 'Klasyczna brama dwuskrzydłowa wykonana z wysokiej jakości aluminium.', '{\"width\":{\"label\":\"Szerokość całkowita\",\"type\":\"select\",\"choices\":[{\"label\":\"3 metry\",\"value\":3,\"priceMod\":0},{\"label\":\"4 metry\",\"value\":4,\"priceMod\":1200}]},\"automation\":{\"label\":\"Automatyka\",\"type\":\"checkbox\",\"price\":3200}}'),
            ('Balustrada Nowoczesna', 'balustrady', 450, 'assets/portfolio/barierki/1.webp', 'Nowoczesna balustrada aluminiowa na metr bieżący.', '{\"height\":{\"label\":\"Wysokość\",\"type\":\"select\",\"choices\":[{\"label\":\"100 cm\",\"value\":100,\"priceMod\":0},{\"label\":\"120 cm\",\"value\":120,\"priceMod\":50}]}}'),
            ('Przęsło Ogrodzeniowe', 'ogrodzenia', 380, 'assets/portfolio/przesla-ogrodzeniowe-aluminiowe/1.webp', 'Przęsło ogrodzeniowe aluminiowe 2m szerokości.', '{\"color\":{\"label\":\"Kolor\",\"type\":\"select\",\"choices\":[{\"label\":\"Antracyt\",\"value\":\"antracyt\",\"priceMod\":0},{\"label\":\"Biały\",\"value\":\"bialy\",\"priceMod\":0}]}}')
        ");
        echo "Data inserted.\n";
    } else {
        echo "Data already exists.\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
