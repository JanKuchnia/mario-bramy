<?php
/**
 * Mario Bramy - Konfiguracja
 * 
 * Centralna konfiguracja aplikacji:
 * - Połączenie z bazą danych (PDO)
 * - Stałe konfiguracyjne
 * - Ustawienia sesji
 */

// Włącz raportowanie błędów w trybie developerskim (wyłącz na produkcji)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Uruchom sesję
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Konfiguracja bazy danych
define('DB_HOST', 'localhost');
define('DB_NAME', 'mario_bramy');
define('DB_USER', 'root');
define('DB_PASS', ''); // XAMPP domyślnie bez hasła

// Konfiguracja strony
define('SITE_URL', 'http://localhost/mario-bramy');
define('SITE_NAME', 'Mario Bramy');

// Ścieżki
define('ROOT_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);
define('INCLUDES_PATH', __DIR__ . DIRECTORY_SEPARATOR);
define('ASSETS_PATH', ROOT_PATH . 'assets' . DIRECTORY_SEPARATOR);
define('UPLOADS_PATH', ASSETS_PATH . 'portfolio' . DIRECTORY_SEPARATOR);

// Połączenie z bazą danych (PDO)
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $e) {
    // W produkcji zaloguj błąd, nie wyświetlaj
    die("Błąd połączenia z bazą danych: " . $e->getMessage());
}

// Funkcja pomocnicza - pobieranie PDO
function getDB() {
    global $pdo;
    return $pdo;
}
