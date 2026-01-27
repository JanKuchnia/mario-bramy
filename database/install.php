<?php
/**
 * Mario Bramy - Skrypt instalacyjny
 * 
 * Uruchom raz: http://localhost/mario-bramy/database/install.php
 * Po instalacji USUŃ ten plik ze względów bezpieczeństwa!
 */

// Konfiguracja
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'mario_bramy';

// Domyślne dane admina
$admin_username = 'admin';
$admin_password = 'admin123'; // ZMIEŃ PO INSTALACJI!

echo "<h1>Mario Bramy - Instalacja bazy danych</h1>";

try {
    // Połączenie bez wybranej bazy
    $pdo = new PDO("mysql:host=$db_host;charset=utf8mb4", $db_user, $db_pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    
    echo "<p>✅ Połączenie z MySQL udane</p>";
    
    // Utwórz bazę danych
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$db_name` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE `$db_name`");
    echo "<p>✅ Baza danych '$db_name' utworzona/wybrana</p>";
    
    // Tabela admin_users
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS admin_users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) UNIQUE NOT NULL,
            password_hash VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");
    echo "<p>✅ Tabela 'admin_users' utworzona</p>";
    
    // Tabela gallery_images
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS gallery_images (
            id INT AUTO_INCREMENT PRIMARY KEY,
            category VARCHAR(100) NOT NULL,
            filename VARCHAR(255) NOT NULL,
            alt_text VARCHAR(500),
            sort_order INT DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_category (category)
        )
    ");
    echo "<p>✅ Tabela 'gallery_images' utworzona</p>";
    
    // Tabela products
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS products (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            category VARCHAR(100) NOT NULL,
            base_price DECIMAL(10,2) NOT NULL,
            image_url VARCHAR(500),
            description TEXT,
            is_active BOOLEAN DEFAULT TRUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_category (category),
            INDEX idx_active (is_active)
        )
    ");
    echo "<p>✅ Tabela 'products' utworzona</p>";
    
    // Tabela product_options
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS product_options (
            id INT AUTO_INCREMENT PRIMARY KEY,
            product_id INT NOT NULL,
            option_name VARCHAR(100) NOT NULL,
            option_type ENUM('select', 'radio', 'checkbox') DEFAULT 'select',
            FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
        )
    ");
    echo "<p>✅ Tabela 'product_options' utworzona</p>";
    
    // Tabela product_option_values
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS product_option_values (
            id INT AUTO_INCREMENT PRIMARY KEY,
            option_id INT NOT NULL,
            value_label VARCHAR(100) NOT NULL,
            price_modifier DECIMAL(10,2) DEFAULT 0,
            FOREIGN KEY (option_id) REFERENCES product_options(id) ON DELETE CASCADE
        )
    ");
    echo "<p>✅ Tabela 'product_option_values' utworzona</p>";
    
    // Dodaj admina
    $password_hash = password_hash($admin_password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("
        INSERT INTO admin_users (username, password_hash) VALUES (?, ?)
        ON DUPLICATE KEY UPDATE password_hash = VALUES(password_hash)
    ");
    $stmt->execute([$admin_username, $password_hash]);
    echo "<p>✅ Użytkownik admin utworzony</p>";
    
    echo "<hr>";
    echo "<h2>🎉 Instalacja zakończona!</h2>";
    echo "<p><strong>Dane logowania:</strong></p>";
    echo "<ul>";
    echo "<li>Login: <code>$admin_username</code></li>";
    echo "<li>Hasło: <code>$admin_password</code></li>";
    echo "</ul>";
    echo "<p style='color: red;'><strong>⚠️ WAŻNE: Usuń ten plik (install.php) i zmień hasło admina!</strong></p>";
    echo "<p><a href='../admin/'>Przejdź do panelu admina →</a></p>";
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>❌ Błąd: " . $e->getMessage() . "</p>";
}
