<?php
/**
 * API - Pobieranie galerii zdjęć
 */
require_once __DIR__ . '/../config/init.php';

// Tylko GET
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    jsonError('Metoda niedozwolona', 405);
}

$category = $_GET['category'] ?? null;

// Pobierz zdjęcia z folderu
$images = getPortfolioImages($category);

// Sortuj według nazwy pliku (malejąco - najnowsze najpierw)
usort($images, function($a, $b) {
    // Próbuj sortować numerycznie
    $numA = (int) pathinfo($a['name'], PATHINFO_FILENAME);
    $numB = (int) pathinfo($b['name'], PATHINFO_FILENAME);
    return $numB - $numA;
});

jsonSuccess([
    'images' => $images,
    'count' => count($images)
]);
