<?php
/**
 * Narzędzie do masowej optymalizacji zdjęć (Konwersja na WebP)
 * Uruchom ten plik raz na serwerze, aby skonwertować istniejące galerie.
 */

// Zwiększ limity czasu i pamięci dla dużych operacji
set_time_limit(300); // 5 minut
ini_set('memory_limit', '512M');

// Włącz raportowanie błędów
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/config/init.php';

echo "<html><body style='font-family: sans-serif; padding: 20px;'>";
echo "<h1>Optymalizacja Zdjęć do WebP</h1>";

if (!extension_loaded('gd')) {
    die("<h2 style='color: red;'>Błąd: Brak biblioteki GD! Nie można konwertować zdjęć.</h2></body></html>");
}

$portfolioPath = PORTFOLIO_PATH;
$stats = [
    'scanned' => 0,
    'converted' => 0,
    'skipped' => 0,
    'deleted' => 0,
    'existing_webp' => 0,
    'errors' => 0,
    'saved_space' => 0
];

echo "<div style='background: #f5f5f5; padding: 15px; border-radius: 5px; font-family: monospace; max-height: 500px; overflow-y: auto;'>";

// Funkcja rekurencyjna do skanowania katalogów
function scanAndConvert($dir) {
    global $stats;
    
    $files = scandir($dir);
    
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') continue;
        
        $path = $dir . '/' . $file;
        
        if (is_dir($path)) {
            scanAndConvert($path);
        } else {
            $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
            
            // Przetwarzaj tylko obrazy, pomijaj już istniejące WebP
            if (in_array($ext, ['jpg', 'jpeg', 'png'])) {
                $stats['scanned']++;
            } elseif ($ext === 'webp') {
                // Licz istniejące pliki WebP
                $stats['existing_webp']++;
            }
            
            if (in_array($ext, ['jpg', 'jpeg', 'png'])) {
                // Sprawdź czy wersja WebP już istnieje
                $webpPath = $dir . '/' . pathinfo($path, PATHINFO_FILENAME) . '.webp';
                
                if (file_exists($webpPath)) {
                    // WebP już istnieje - usuń oryginalny plik
                    if (@unlink($path)) {
                        echo "Usunięto (WebP istnieje): " . str_replace(PORTFOLIO_PATH, '', $path) . "<br>";
                        $stats['deleted']++;
                    }
                    $stats['skipped']++;
                    continue;
                }
                
                echo "Konwertuję: " . str_replace(PORTFOLIO_PATH, '', $path) . "... ";
                
                if (convertToWebP($path, $webpPath, $ext)) {
                    $originalSize = filesize($path);
                    $newSize = filesize($webpPath);
                    $diff = $originalSize - $newSize;
                    $stats['saved_space'] += ($diff > 0 ? $diff : 0);
                    $stats['converted']++;
                    
                    // Usuń oryginalny plik po pomyślnej konwersji
                    if (@unlink($path)) {
                        $stats['deleted']++;
                        echo "<span style='color: green;'>OK + usunięto oryginał</span> (Oszczędność: " . formatBytes($diff) . ")<br>";
                    } else {
                        echo "<span style='color: green;'>OK</span> <span style='color: orange;'>(nie udało się usunąć oryginału)</span><br>";
                    }
                } else {
                    $stats['errors']++;
                    echo "<span style='color: red;'>BŁĄD</span><br>";
                }
                
                // Flush output buffer to show progress live
                flush();
                ob_flush();
            }
        }
    }
}

function convertToWebP($sourcePath, $targetPath, $ext) {
    try {
        $img = null;
        if ($ext === 'jpg' || $ext === 'jpeg') {
            $img = @imagecreatefromjpeg($sourcePath);
        } elseif ($ext === 'png') {
            $img = @imagecreatefrompng($sourcePath);
            if ($img) {
                imagepalettetotruecolor($img);
                imagealphablending($img, true);
                imagesavealpha($img, true);
            }
        }
        
        if (!$img) return false;
        
        // Zapisz jako WebP z jakością 80%
        $result = imagewebp($img, $targetPath, 80);
        imagedestroy($img);
        
        return $result;
    } catch (Exception $e) {
        return false;
    }
}

function formatBytes($bytes, $precision = 2) { 
    $units = ['B', 'KB', 'MB', 'GB']; 
    $bytes = max($bytes, 0); 
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
    $pow = min($pow, count($units) - 1); 
    $bytes /= pow(1024, $pow); 
    return round($bytes, $precision) . ' ' . $units[$pow]; 
}

// Uruchom
if (is_dir($portfolioPath)) {
    scanAndConvert($portfolioPath);
} else {
    echo "Folder portfolio nie istnieje: $portfolioPath";
}

echo "</div>";

echo "<h2>Podsumowanie</h2>";
echo "<ul>";
echo "<li>Przeskanowano plików JPG/PNG: <strong>{$stats['scanned']}</strong></li>";
echo "<li>Istniejące pliki WebP: <strong style='color: blue;'>{$stats['existing_webp']}</strong></li>";
echo "<li>Skonwertowano: <strong style='color: green;'>{$stats['converted']}</strong></li>";
echo "<li>Pominięto (już były): <strong>{$stats['skipped']}</strong></li>";
echo "<li>Usunięto oryginałów: <strong style='color: orange;'>{$stats['deleted']}</strong></li>";
echo "<li>Błędy: <strong style='color: red;'>{$stats['errors']}</strong></li>";
echo "<li>Zaoszczędzone miejsce: <strong>" . formatBytes($stats['saved_space']) . "</strong></li>";
echo "</ul>";

echo "<p><a href='index.php' style='display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px;'>Powrót do strony głównej</a></p>";

echo "</body></html>";
