<?php
/**
 * Konfiguracja bazy danych Mario Bramy
 * 
 * HOSTINGER - Utwórz bazę w hPanel -> Bazy danych -> MySQL
 * Wpisz poniższe wartości podczas tworzenia:
 * 
 * Nazwa bazy:      mario_bramy
 * Użytkownik:      mario_admin  
 * Hasło:           MarioBramy2024!Secure
 */

// Konfiguracja bazy danych
define('DB_HOST', 'localhost');
define('DB_NAME', 'mario_bramy');
define('DB_USER', 'mario_admin');
define('DB_PASS', 'MarioBramy2024!Secure');
define('DB_CHARSET', 'utf8mb4');

// Hasło admina (zahashowane)
// Obecne hasło: admin
define('ADMIN_PASSWORD_HASH', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

// Ścieżki
define('BASE_PATH', dirname(__DIR__));
define('ASSETS_PATH', BASE_PATH . '/assets');
define('PORTFOLIO_PATH', ASSETS_PATH . '/portfolio');
define('UPLOADS_PATH', BASE_PATH . '/uploads');

// Ustawienia
define('MAX_UPLOAD_SIZE', 10 * 1024 * 1024); // 10MB
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'webp', 'gif']);

// Tryb debug (wyłącz na produkcji!)
define('DEBUG_MODE', true);
