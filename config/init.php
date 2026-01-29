<?php
/**
 * Inicjalizacja aplikacji Mario Bramy
 */


// Załaduj konfigurację
require_once __DIR__ . '/config.php';

// Obsługa błędów
if (DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Sesja
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Połączenie z bazą danych
$pdo = null;

function getDB(): PDO {
    global $pdo;
    
    if ($pdo === null) {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            if (DEBUG_MODE) {
                die("Błąd połączenia z bazą danych: " . $e->getMessage());
            } else {
                die("Błąd połączenia z bazą danych. Skontaktuj się z administratorem.");
            }
        }
    }
    
    return $pdo;
}

// Funkcje pomocnicze

/**
 * Sprawdza czy użytkownik jest zalogowany jako admin
 */
function isAdminLoggedIn(): bool {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

/**
 * Wymusza zalogowanie admina - przekierowuje jeśli niezalogowany
 */
function requireAdmin(): void {
    if (!isAdminLoggedIn()) {
        header('Location: index.php');
        exit;
    }
}

/**
 * Zwraca odpowiedź JSON
 */
function jsonResponse(array $data, int $statusCode = 200): void {
    http_response_code($statusCode);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

/**
 * Zwraca błąd JSON
 */
function jsonError(string $message, int $statusCode = 400): void {
    jsonResponse(['success' => false, 'error' => $message], $statusCode);
}

/**
 * Zwraca sukces JSON
 */
function jsonSuccess(array $data = []): void {
    jsonResponse(array_merge(['success' => true], $data));
}

/**
 * Bezpieczne czyszczenie nazwy pliku
 */
function sanitizeFilename(string $filename): string {
    // Usuń rozszerzenie
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    $name = pathinfo($filename, PATHINFO_FILENAME);
    
    // Zamień polskie znaki
    $polish = ['ą','ć','ę','ł','ń','ó','ś','ź','ż','Ą','Ć','Ę','Ł','Ń','Ó','Ś','Ź','Ż'];
    $latin = ['a','c','e','l','n','o','s','z','z','A','C','E','L','N','O','S','Z','Z'];
    $name = str_replace($polish, $latin, $name);
    
    // Tylko alfanumeryczne i myślniki
    $name = preg_replace('/[^a-zA-Z0-9\-_]/', '-', $name);
    $name = preg_replace('/-+/', '-', $name);
    $name = trim($name, '-');
    
    return strtolower($name) . '.' . strtolower($ext);
}

/**
 * Sprawdza czy rozszerzenie pliku jest dozwolone
 */
function isAllowedExtension(string $filename): bool {
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    return in_array($ext, ALLOWED_EXTENSIONS);
}

/**
 * Pobiera następny numer pliku w katalogu
 */
function getNextFileNumber(string $directory): int {
    if (!is_dir($directory)) {
        return 1;
    }
    
    $files = glob($directory . '/*.{jpg,jpeg,png,webp,gif}', GLOB_BRACE);
    $maxNum = 0;
    
    foreach ($files as $file) {
        $name = pathinfo($file, PATHINFO_FILENAME);
        if (is_numeric($name)) {
            $maxNum = max($maxNum, (int)$name);
        }
    }
    
    return $maxNum + 1;
}

/**
 * Skanuje folder portfolio i zwraca listę zdjęć
 */
function getPortfolioImages(string $category = null): array {
    $images = [];
    $portfolioPath = PORTFOLIO_PATH;
    
    if ($category) {
        $categoryPath = $portfolioPath . '/' . $category;
        if (is_dir($categoryPath)) {
            $files = glob($categoryPath . '/*.{jpg,jpeg,png,webp,gif}', GLOB_BRACE);
            foreach ($files as $file) {
                $images[] = [
                    'src' => 'assets/portfolio/' . $category . '/' . basename($file),
                    'category' => $category,
                    'name' => basename($file)
                ];
            }
        }
    } else {
        // Wszystkie kategorie
        $categories = glob($portfolioPath . '/*', GLOB_ONLYDIR);
        foreach ($categories as $catDir) {
            $catName = basename($catDir);
            $files = glob($catDir . '/*.{jpg,jpeg,png,webp,gif}', GLOB_BRACE);
            foreach ($files as $file) {
                $images[] = [
                    'src' => 'assets/portfolio/' . $catName . '/' . basename($file),
                    'category' => $catName,
                    'name' => basename($file)
                ];
            }
        }
    }
    
    return $images;
}

/**
 * Pobiera URL sklepu w zależności od ustawień
 */
function getShopUrl(): string {
    $settingsFile = __DIR__ . '/settings.json';
    $shopActive = false;
    
    if (file_exists($settingsFile)) {
        $settings = json_decode(file_get_contents($settingsFile), true);
        $shopActive = $settings['shop_active'] ?? false;
    }
    
    return $shopActive ? 'sklep.php' : 'wkrotce.php';
}
