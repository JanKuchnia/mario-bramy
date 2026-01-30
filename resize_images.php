<?php
/**
 * Skrypt do zmniejszania rozdzielczości konkretnych obrazów
 * Uruchom raz na serwerze aby zoptymalizować obrazy zgodnie z PageSpeed Insights
 */

set_time_limit(120);
ini_set('memory_limit', '256M');
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<html><body style='font-family: sans-serif; padding: 20px;'>";
echo "<h1>Optymalizacja rozmiaru obrazów</h1>";

if (!extension_loaded('gd')) {
    die("<h2 style='color: red;'>Błąd: Brak biblioteki GD!</h2></body></html>");
}

// Lista obrazów do przeskalowania z docelowymi wymiarami
$imagesToResize = [
    // [ścieżka, max_szerokość, jakość WebP]
    ['assets/portfolio/automatyka/2.webp', 400, 75],      // 921x922 -> 400px (wyświetlane 319x377)
    ['assets/portfolio/automatyka/1.webp', 800, 75],      // 860x861 -> 800px (wyświetlane 741x319)
    ['assets/portfolio/bramy-dwuskrzydlowe/5.webp', 400, 75], // 739x738 -> 400px (wyświetlane 354x319)
    ['assets/portfolio/bramy-przesuwne-aluminiowe/6.webp', 450, 80], // 476x475 -> 450px (wyświetlane 428x319)
];

// Konwersja logo do WebP
$logoPath = 'assets/logo.webp';
$logoWebpPath = 'assets/logo.webp';

$stats = ['resized' => 0, 'errors' => 0, 'saved' => 0];

echo "<div style='background: #f5f5f5; padding: 15px; border-radius: 5px; font-family: monospace;'>";

// Najpierw skonwertuj logo do WebP
if (file_exists($logoPath)) {
    echo "Konwertuję logo do WebP... ";
    $logoImg = @imagecreatefrompng($logoPath);
    if ($logoImg) {
        // Przeskaluj logo do 350px
        $origWidth = imagesx($logoImg);
        $origHeight = imagesy($logoImg);
        $newWidth = 350;
        $newHeight = (int)($origHeight * ($newWidth / $origWidth));
        
        $resized = imagecreatetruecolor($newWidth, $newHeight);
        imagealphablending($resized, false);
        imagesavealpha($resized, true);
        imagecopyresampled($resized, $logoImg, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);
        
        $origSize = filesize($logoPath);
        if (imagewebp($resized, $logoWebpPath, 85)) {
            $newSize = filesize($logoWebpPath);
            $saved = $origSize - $newSize;
            $stats['saved'] += $saved;
            echo "<span style='color: green;'>OK</span> ({$origWidth}x{$origHeight} -> {$newWidth}x{$newHeight}, Oszczędność: " . formatBytes($saved) . ")<br>";
            $stats['resized']++;
        } else {
            echo "<span style='color: red;'>BŁĄD zapisu</span><br>";
            $stats['errors']++;
        }
        imagedestroy($logoImg);
        imagedestroy($resized);
    } else {
        echo "<span style='color: red;'>BŁĄD ładowania</span><br>";
        $stats['errors']++;
    }
}

// Przeskaluj obrazy WebP
foreach ($imagesToResize as [$path, $maxWidth, $quality]) {
    if (!file_exists($path)) {
        echo "Pominięto (nie istnieje): $path<br>";
        continue;
    }
    
    echo "Zmniejszam: $path -> {$maxWidth}px... ";
    
    $origSize = filesize($path);
    $img = @imagecreatefromwebp($path);
    
    if (!$img) {
        echo "<span style='color: red;'>BŁĄD ładowania</span><br>";
        $stats['errors']++;
        continue;
    }
    
    $origWidth = imagesx($img);
    $origHeight = imagesy($img);
    
    // Sprawdź czy potrzebuje zmniejszenia
    if ($origWidth <= $maxWidth) {
        echo "<span style='color: blue;'>Pominięto (już mały: {$origWidth}px)</span><br>";
        imagedestroy($img);
        continue;
    }
    
    // Oblicz nowe wymiary
    $ratio = $maxWidth / $origWidth;
    $newWidth = $maxWidth;
    $newHeight = (int)($origHeight * $ratio);
    
    // Utwórz przeskalowany obraz
    $resized = imagecreatetruecolor($newWidth, $newHeight);
    imagealphablending($resized, false);
    imagesavealpha($resized, true);
    imagecopyresampled($resized, $img, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);
    
    // Zapisz z nadpisaniem
    if (imagewebp($resized, $path, $quality)) {
        clearstatcache(true, $path);
        $newSize = filesize($path);
        $saved = $origSize - $newSize;
        $stats['saved'] += $saved;
        $stats['resized']++;
        echo "<span style='color: green;'>OK</span> ({$origWidth}x{$origHeight} -> {$newWidth}x{$newHeight}, Oszczędność: " . formatBytes($saved) . ")<br>";
    } else {
        echo "<span style='color: red;'>BŁĄD zapisu</span><br>";
        $stats['errors']++;
    }
    
    imagedestroy($img);
    imagedestroy($resized);
    
    flush();
    ob_flush();
}

echo "</div>";

echo "<h2>Podsumowanie</h2>";
echo "<ul>";
echo "<li>Przeskalowano obrazów: <strong style='color: green;'>{$stats['resized']}</strong></li>";
echo "<li>Błędy: <strong style='color: red;'>{$stats['errors']}</strong></li>";
echo "<li>Zaoszczędzono: <strong>" . formatBytes($stats['saved']) . "</strong></li>";
echo "</ul>";

echo "<p style='background: #fff3cd; padding: 10px; border-radius: 5px;'>";
echo "<strong>Uwaga:</strong> Zaktualizuj odniesienia do logo w plikach PHP z <code>logo.webp</code> na <code>logo.webp</code> dla lepszej wydajności.";
echo "</p>";

echo "<p><a href='index.php' style='display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px;'>Powrót do strony głównej</a></p>";
echo "</body></html>";

function formatBytes($bytes, $precision = 2) { 
    $units = ['B', 'KB', 'MB', 'GB']; 
    $bytes = max($bytes, 0); 
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
    $pow = min($pow, count($units) - 1); 
    $bytes /= pow(1024, $pow); 
    return round($bytes, $precision) . ' ' . $units[$pow]; 
}
