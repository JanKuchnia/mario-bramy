<?php
/**
 * API - Usuwanie zdjęcia z galerii
 */
require_once __DIR__ . '/../config/init.php';

// Tylko POST (lub DELETE)
if ($_SERVER['REQUEST_METHOD'] !== 'POST' && $_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    jsonError('Metoda niedozwolona', 405);
}

// Wymagaj zalogowania
if (!isAdminLoggedIn()) {
    jsonError('Brak autoryzacji', 401);
}

// Pobierz dane z JSON body lub POST
$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    $input = $_POST;
}

$category = $input['category'] ?? '';
$filename = $input['filename'] ?? '';

// Walidacja
if (empty($category) || empty($filename)) {
    jsonError('Brak wymaganych parametrów: category i filename');
}

// Walidacja kategorii (zapobieganie path traversal)
$allowedCategories = [
    'bramy-przesuwne-aluminiowe',
    'bramy-dwuskrzydlowe',
    'barierki',
    'przesla-ogrodzeniowe-aluminiowe'
];

if (!in_array($category, $allowedCategories)) {
    jsonError('Nieprawidłowa kategoria');
}

// Walidacja nazwy pliku (tylko alfanumeryczne, kropka i myślnik)
if (!preg_match('/^[a-zA-Z0-9\.\-_]+$/', $filename)) {
    jsonError('Nieprawidłowa nazwa pliku');
}

// Zbuduj ścieżkę do pliku
$filePath = PORTFOLIO_PATH . '/' . $category . '/' . $filename;

// Sprawdź czy plik istnieje
if (!file_exists($filePath)) {
    jsonError('Plik nie istnieje');
}

// Sprawdź czy to rzeczywiście plik w folderze portfolio (dodatkowe zabezpieczenie)
$realPath = realpath($filePath);
$portfolioRealPath = realpath(PORTFOLIO_PATH);

if (strpos($realPath, $portfolioRealPath) !== 0) {
    jsonError('Niedozwolona ścieżka');
}

// Usuń plik
if (!unlink($filePath)) {
    jsonError('Nie można usunąć pliku');
}

// Usuń z bazy danych (jeśli istnieje)
try {
    $db = getDB();
    $stmt = $db->prepare("DELETE FROM gallery_images WHERE category = ? AND filename = ?");
    $stmt->execute([$category, $filename]);
} catch (Exception $e) {
    // Ignoruj błąd bazy - plik został usunięty
    if (DEBUG_MODE) {
        error_log("Gallery delete DB error: " . $e->getMessage());
    }
}

jsonSuccess([
    'message' => 'Zdjęcie zostało usunięte',
    'filename' => $filename,
    'category' => $category
]);
