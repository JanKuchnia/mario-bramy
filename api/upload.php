<?php
/**
 * API - Upload zdjęcia do galerii
 */
require_once __DIR__ . '/../config/init.php';

// Tylko POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonError('Metoda niedozwolona', 405);
}

// Wymagaj zalogowania
if (!isAdminLoggedIn()) {
    jsonError('Brak autoryzacji', 401);
}

// Sprawdź czy plik został przesłany
if (!isset($_FILES['photo']) || $_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
    $errorMessages = [
        UPLOAD_ERR_INI_SIZE => 'Plik przekracza maksymalny rozmiar dozwolony przez serwer',
        UPLOAD_ERR_FORM_SIZE => 'Plik przekracza maksymalny rozmiar formularza',
        UPLOAD_ERR_PARTIAL => 'Plik został przesłany tylko częściowo',
        UPLOAD_ERR_NO_FILE => 'Nie wybrano pliku',
        UPLOAD_ERR_NO_TMP_DIR => 'Brak folderu tymczasowego',
        UPLOAD_ERR_CANT_WRITE => 'Nie można zapisać pliku na dysku',
    ];
    $errorCode = $_FILES['photo']['error'] ?? UPLOAD_ERR_NO_FILE;
    jsonError($errorMessages[$errorCode] ?? 'Błąd przesyłania pliku');
}

$file = $_FILES['photo'];
$category = $_POST['category'] ?? '';
$altText = $_POST['alt_text'] ?? '';

// Walidacja kategorii
$allowedCategories = [
    'bramy-przesuwne-aluminiowe',
    'bramy-dwuskrzydlowe',
    'barierki',
    'przesla-ogrodzeniowe-aluminiowe',
    'products'
];

if (!in_array($category, $allowedCategories)) {
    jsonError('Nieprawidłowa kategoria');
}

// Walidacja rozszerzenia
if (!isAllowedExtension($file['name'])) {
    jsonError('Niedozwolony format pliku. Dozwolone: ' . implode(', ', ALLOWED_EXTENSIONS));
}

// Walidacja rozmiaru
if ($file['size'] > MAX_UPLOAD_SIZE) {
    jsonError('Plik jest za duży. Maksymalny rozmiar: ' . (MAX_UPLOAD_SIZE / 1024 / 1024) . 'MB');
}

// Walidacja MIME type
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mimeType = finfo_file($finfo, $file['tmp_name']);
finfo_close($finfo);

$allowedMimes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
if (!in_array($mimeType, $allowedMimes)) {
    jsonError('Plik nie jest prawidłowym obrazem');
}

// Utwórz folder kategorii jeśli nie istnieje
$categoryPath = PORTFOLIO_PATH . '/' . $category;
if (!is_dir($categoryPath)) {
    if (!mkdir($categoryPath, 0755, true)) {
        jsonError('Nie można utworzyć folderu kategorii');
    }
}

// Wygeneruj nazwę pliku (następny numer)
$nextNumber = getNextFileNumber($categoryPath);
$extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

// Sprawdź czy mamy bibliotekę GD
if (extension_loaded('gd')) {
    // Konwertuj do WebP
    $targetExtension = 'webp';
    $targetFilename = $nextNumber . '.' . $targetExtension;
    $targetPath = $categoryPath . '/' . $targetFilename;

    // Przetwórz obraz
    $sourceImage = null;
    switch ($mimeType) {
        case 'image/jpeg':
            $sourceImage = imagecreatefromjpeg($file['tmp_name']);
            break;
        case 'image/png':
            $sourceImage = imagecreatefrompng($file['tmp_name']);
            imagepalettetotruecolor($sourceImage); // Ensure true color for palette PNGs
            imagealphablending($sourceImage, true);
            break;
        case 'image/webp':
            $sourceImage = imagecreatefromwebp($file['tmp_name']);
            break;
        case 'image/gif':
            $sourceImage = imagecreatefromgif($file['tmp_name']);
            imagepalettetotruecolor($sourceImage);
            break;
    }

    if (!$sourceImage) {
        jsonError('Nie można przetworzyć obrazu');
    }

    // Opcjonalnie: zmniejsz rozmiar jeśli za duży
    $maxWidth = 1920;
    $maxHeight = 1080;
    $width = imagesx($sourceImage);
    $height = imagesy($sourceImage);

    if ($width > $maxWidth || $height > $maxHeight) {
        $ratio = min($maxWidth / $width, $maxHeight / $height);
        $newWidth = (int)($width * $ratio);
        $newHeight = (int)($height * $ratio);
        
        $resizedImage = imagecreatetruecolor($newWidth, $newHeight);
        
        // Zachowaj przezroczystość przy zmianie rozmiaru
        imagealphablending($resizedImage, false);
        imagesavealpha($resizedImage, true);
        $transparent = imagecolorallocatealpha($resizedImage, 255, 255, 255, 127);
        imagefilledrectangle($resizedImage, 0, 0, $newWidth, $newHeight, $transparent);
        
        imagecopyresampled($resizedImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        imagedestroy($sourceImage);
        $sourceImage = $resizedImage;
    }

    // Zapisz jako WebP
    imagealphablending($sourceImage, false);
    imagesavealpha($sourceImage, true);

    if (!imagewebp($sourceImage, $targetPath, 80)) {
        imagedestroy($sourceImage);
        jsonError('Nie można zapisać obrazu');
    }
    imagedestroy($sourceImage);
    
    // Kompresja przez TinyPNG API (opcjonalnie)
    $tinypngApiKey = defined('TINYPNG_API_KEY') ? TINYPNG_API_KEY : '';
    if ($tinypngApiKey && function_exists('curl_init')) {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => 'https://api.tinify.com/shrink',
            CURLOPT_USERPWD => 'api:' . $tinypngApiKey,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => file_get_contents($targetPath),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ['Content-Type: application/octet-stream'],
            CURLOPT_TIMEOUT => 30,
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);
        
        if ($httpCode === 201) {
            $result = json_decode($response, true);
            if (isset($result['output']['url'])) {
                // Pobierz skompresowany plik z walidacją
                $chDownload = curl_init();
                curl_setopt_array($chDownload, [
                    CURLOPT_URL => $result['output']['url'],
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_TIMEOUT => 30,
                ]);
                $compressedData = curl_exec($chDownload);
                $downloadHttpCode = curl_getinfo($chDownload, CURLINFO_HTTP_CODE);
                $downloadError = curl_error($chDownload);
                $downloadErrno = curl_errno($chDownload);
                curl_close($chDownload);
                
                // Tylko zapisz jeśli pobieranie się powiodło
                if ($compressedData !== false && $downloadHttpCode === 200 && $downloadErrno === 0 && strlen($compressedData) > 0) {
                    file_put_contents($targetPath, $compressedData);
                } else {
                    // Log błędu (oryginalny plik WebP pozostaje)
                    if (DEBUG_MODE) {
                        error_log("TinyPNG download failed: HTTP $downloadHttpCode, Error: $downloadError");
                    }
                }
            }
        } else {
            // Log błędu kompresji
            if (DEBUG_MODE) {
                error_log("TinyPNG compression failed: HTTP $httpCode, Error: $curlError");
            }
        }
        // Jeśli TinyPNG zawiedzie, oryginalny plik WebP pozostaje
    }

} else {
    // BRAK GD - Zapisz oryginał bez konwersji
    $targetExtension = $extension;
    $targetFilename = $nextNumber . '.' . $targetExtension;
    $targetPath = $categoryPath . '/' . $targetFilename;

    if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
        jsonError('Nie można zapisać pliku (Brak GD, błąd przenoszenia)');
    }
}

// Opcjonalnie: zapisz do bazy danych
try {
    $db = getDB();
    $stmt = $db->prepare("INSERT INTO gallery_images (filename, category, alt_text) VALUES (?, ?, ?)");
    $stmt->execute([$targetFilename, $category, $altText]);
} catch (Exception $e) {
    // Ignoruj błąd bazy - plik został zapisany
    if (DEBUG_MODE) {
        error_log("Gallery DB error: " . $e->getMessage());
    }
}

jsonSuccess([
    'message' => 'Zdjęcie zostało dodane pomyślnie',
    'filename' => $targetFilename,
    'path' => 'assets/portfolio/' . $category . '/' . $targetFilename,
    'category' => $category
]);
