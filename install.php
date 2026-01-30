<?php
/**
 * Skrypt instalacyjny bazy danych Mario Bramy
 * 
 * Uruchom raz po utworzeniu bazy na Hostingerze:
 * https://twoja-domena.pl/install.php
 * 
 * USUŃ TEN PLIK PO INSTALACJI!
 */

require_once __DIR__ . '/config/init.php';

// Sprawdź czy już zainstalowane
$installed = false;
try {
    $db = getDB();
    $stmt = $db->query("SHOW TABLES LIKE 'products'");
    $installed = $stmt->rowCount() > 0;
} catch (Exception $e) {
    // Baza nie istnieje lub błąd połączenia
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['install'])) {
    try {
        $db = getDB();
        
        // Tabela produktów
        $db->exec("
            CREATE TABLE IF NOT EXISTS products (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                category VARCHAR(100) NOT NULL,
                base_price DECIMAL(10,2) NOT NULL DEFAULT 0,
                image VARCHAR(500) DEFAULT NULL,
                description TEXT,
                options JSON,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
        
        // Tabela galerii (opcjonalna - do śledzenia zdjęć)
        $db->exec("
            CREATE TABLE IF NOT EXISTS gallery_images (
                id INT AUTO_INCREMENT PRIMARY KEY,
                filename VARCHAR(255) NOT NULL,
                category VARCHAR(100) NOT NULL,
                alt_text VARCHAR(500),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
        
        // Przykładowe produkty
        $db->exec("
            INSERT INTO products (name, category, base_price, image, description, options) VALUES
            ('Brama Przesuwna Classic', 'bramy', 8500, 'assets/portfolio/bramy-przesuwne-aluminiowe/1.webp', 'Elegancka brama przesuwna z aluminium, idealna do nowoczesnych posesji.', '{\"width\":{\"label\":\"Szerokość\",\"type\":\"select\",\"choices\":[{\"label\":\"4 metry\",\"value\":4,\"priceMod\":0},{\"label\":\"5 metrów\",\"value\":5,\"priceMod\":1500},{\"label\":\"6 metrów\",\"value\":6,\"priceMod\":3000}]},\"color\":{\"label\":\"Kolor\",\"type\":\"select\",\"choices\":[{\"label\":\"Antracyt RAL 7016\",\"value\":\"antracyt\",\"priceMod\":0},{\"label\":\"Biały RAL 9016\",\"value\":\"bialy\",\"priceMod\":0},{\"label\":\"Czarny RAL 9005\",\"value\":\"czarny\",\"priceMod\":200}]},\"automation\":{\"label\":\"Automatyka\",\"type\":\"checkbox\",\"price\":2500}}'),
            ('Brama Dwuskrzydłowa Premium', 'bramy', 7200, 'assets/portfolio/bramy-dwuskrzydlowe/1.webp', 'Klasyczna brama dwuskrzydłowa wykonana z wysokiej jakości aluminium.', '{\"width\":{\"label\":\"Szerokość całkowita\",\"type\":\"select\",\"choices\":[{\"label\":\"3 metry\",\"value\":3,\"priceMod\":0},{\"label\":\"4 metry\",\"value\":4,\"priceMod\":1200}]},\"automation\":{\"label\":\"Automatyka\",\"type\":\"checkbox\",\"price\":3200}}'),
            ('Balustrada Nowoczesna', 'balustrady', 450, 'assets/portfolio/barierki/1.webp', 'Nowoczesna balustrada aluminiowa na metr bieżący.', '{\"height\":{\"label\":\"Wysokość\",\"type\":\"select\",\"choices\":[{\"label\":\"100 cm\",\"value\":100,\"priceMod\":0},{\"label\":\"120 cm\",\"value\":120,\"priceMod\":50}]}}'),
            ('Przęsło Ogrodzeniowe', 'ogrodzenia', 380, 'assets/portfolio/przesla-ogrodzeniowe-aluminiowe/1.webp', 'Przęsło ogrodzeniowe aluminiowe 2m szerokości.', '{\"color\":{\"label\":\"Kolor\",\"type\":\"select\",\"choices\":[{\"label\":\"Antracyt\",\"value\":\"antracyt\",\"priceMod\":0},{\"label\":\"Biały\",\"value\":\"bialy\",\"priceMod\":0}]}}')
        ");
        
        $success = true;
        $message = "Instalacja zakończona pomyślnie! Usuń plik install.php z serwera.";
        
    } catch (PDOException $e) {
        $success = false;
        $message = "Błąd instalacji: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instalacja - Mario Bramy</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { 
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .container {
            background: white;
            border-radius: 16px;
            padding: 40px;
            max-width: 500px;
            width: 100%;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        h1 { 
            color: #1a1a2e;
            margin-bottom: 10px;
        }
        p { 
            color: #666;
            margin-bottom: 20px;
            line-height: 1.6;
        }
        .info-box {
            background: #f8f9fa;
            border-left: 4px solid #007bff;
            padding: 15px;
            margin: 20px 0;
            border-radius: 0 8px 8px 0;
        }
        .info-box strong { display: block; margin-bottom: 5px; }
        .info-box code {
            background: #e9ecef;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 14px;
        }
        button {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 15px 30px;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            transition: transform 0.2s;
        }
        button:hover { transform: translateY(-2px); }
        button:disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
        }
        .success {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .error {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .warning {
            background: #fff3cd;
            border: 1px solid #ffeeba;
            color: #856404;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 Instalacja Mario Bramy</h1>
        
        <?php if ($installed): ?>
            <div class="warning">
                <strong>⚠️ Baza już zainstalowana!</strong>
                Tabele już istnieją. Usuń ten plik z serwera.
            </div>
        <?php endif; ?>
        
        <?php if (isset($success)): ?>
            <?php if ($success): ?>
                <div class="success"><?= htmlspecialchars($message) ?></div>
            <?php else: ?>
                <div class="error"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>
        <?php endif; ?>
        
        <p>Ten skrypt utworzy wymagane tabele w bazie danych i doda przykładowe produkty.</p>
        
        <div class="info-box">
            <strong>Dane do bazy Hostinger:</strong>
            Nazwa bazy: <code>mario_bramy</code><br>
            Użytkownik: <code>mario_admin</code><br>
            Hasło: <code>MarioBramy2024!Secure</code>
        </div>
        
        <div class="info-box">
            <strong>Hasło admina:</strong>
            <code>admin</code>
        </div>
        
        <form method="POST">
            <button type="submit" name="install" <?= $installed ? 'disabled' : '' ?>>
                <?= $installed ? 'Już zainstalowane' : '🚀 Zainstaluj bazę danych' ?>
            </button>
        </form>
    </div>
</body>
</html>
