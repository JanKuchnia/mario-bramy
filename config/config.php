<?php
/**
 * Konfiguracja bazy danych Mario Bramy
 * 
 * HOSTINGER - Utwórz bazę w hPanel -> Bazy danych -> MySQL
 * Wpisz poniższe wartości podczas tworzenia:
 * 
 * Nazwa bazy:      u820515051_mario_bramy
 * Użytkownik:      u820515051_mario_admin  
 * Hasło:           MarioBramy2024!Secure
 */

// Konfiguracja bazy danych
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'mario_bramy');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Hasło admina (zahashowane)
// Obecne hasło: admin
define('ADMIN_PASSWORD_HASH', '$2y$12$JPctmAu31N1ppXzHAFsUhewS/hfAxhEivROGA1nQEueIRfWVeMEDi');

// Ścieżki
define('BASE_PATH', dirname(__DIR__));
define('ASSETS_PATH', BASE_PATH . '/assets');
define('PORTFOLIO_PATH', ASSETS_PATH . '/portfolio');
define('UPLOADS_PATH', BASE_PATH . '/uploads');

// Ustawienia
define('MAX_UPLOAD_SIZE', 10 * 1024 * 1024); // 10MB
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'webp', 'gif']);

// Google API Configuration
define('GOOGLE_PLACES_API_KEY', 'AIzaSyDsHnsWdH6qd22LrXyGqW-730yAJNo3ZkI'); // Wklej swój klucz API tutaj
define('GOOGLE_PLACE_ID', 'ChIJH59bR9kTFkcR9zM2DWgdDGo'); // ID dla MARIO bramy-automatyka
define('CACHE_PATH', BASE_PATH . '/cache/reviews.json');

// Tryb debug (wyłącz na produkcji!)
define('DEBUG_MODE', true);
