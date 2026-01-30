<?php
/**
 * Skrypt do kompresji obrazów przez TinyPNG API
 * Kompresuje wszystkie WebP/PNG/JPG w folderze portfolio
 */

set_time_limit(300);
ini_set('memory_limit', '256M');

// TinyPNG API Key
$apiKey = '0Zyzzv1z4CYLWP96YTyfQzfpdYZNn0PD';

// Ścieżki do przeszukania
$paths = [
    'assets/portfolio',
    'assets'
];

// Rozszerzenia do kompresji
$extensions = ['webp', 'png', 'jpg', 'jpeg'];

$stats = ['compressed' => 0, 'errors' => 0, 'saved' => 0, 'skipped' => 0];

echo "=== TinyPNG Kompresja Obrazów ===\n\n";

function compressWithTinyPng($filePath, $apiKey) {
    $url = 'https://api.tinify.com/shrink';
    
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_USERPWD => 'api:' . $apiKey,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => file_get_contents($filePath),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => ['Content-Type: application/octet-stream'],
        CURLOPT_TIMEOUT => 60,
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode === 201) {
        $result = json_decode($response, true);
        return $result['output']['url'] ?? null;
    }
    
    return null;
}

function downloadFile($url, $destination) {
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT => 60,
    ]);
    
    $data = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode === 200 && $data) {
        return file_put_contents($destination, $data) !== false;
    }
    
    return false;
}

function scanDirectory($dir, $extensions) {
    $files = [];
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    
    foreach ($iterator as $file) {
        if ($file->isFile()) {
            $ext = strtolower($file->getExtension());
            if (in_array($ext, $extensions)) {
                $files[] = $file->getPathname();
            }
        }
    }
    
    return $files;
}

// Zbierz wszystkie pliki
$allFiles = [];
foreach ($paths as $path) {
    if (is_dir($path)) {
        $allFiles = array_merge($allFiles, scanDirectory($path, $extensions));
    } elseif (is_file($path)) {
        $allFiles[] = $path;
    }
}

// Dodaj logo jeśli istnieje
if (file_exists('assets/logo.webp')) {
    $allFiles[] = 'assets/logo.webp';
}
if (file_exists('assets/logo.png')) {
    $allFiles[] = 'assets/logo.png';
}

$allFiles = array_unique($allFiles);
$total = count($allFiles);

echo "Znaleziono $total plików do kompresji.\n\n";

foreach ($allFiles as $index => $filePath) {
    $num = $index + 1;
    $basename = basename($filePath);
    $origSize = filesize($filePath);
    
    // Pomiń bardzo małe pliki (< 5KB)
    if ($origSize < 5000) {
        echo "[$num/$total] SKIP (mały): $filePath\n";
        $stats['skipped']++;
        continue;
    }
    
    echo "[$num/$total] Kompresuję: $filePath (" . round($origSize/1024, 1) . "KB)... ";
    
    $outputUrl = compressWithTinyPng($filePath, $apiKey);
    
    if ($outputUrl) {
        // Pobierz skompresowany plik
        if (downloadFile($outputUrl, $filePath)) {
            clearstatcache(true, $filePath);
            $newSize = filesize($filePath);
            $saved = $origSize - $newSize;
            $percent = round(($saved / $origSize) * 100, 1);
            
            $stats['compressed']++;
            $stats['saved'] += $saved;
            
            echo "OK! " . round($newSize/1024, 1) . "KB (-$percent%)\n";
        } else {
            echo "BŁĄD pobierania\n";
            $stats['errors']++;
        }
    } else {
        echo "BŁĄD API\n";
        $stats['errors']++;
    }
    
    // Krótka pauza aby nie przekroczyć limitów API
    usleep(200000); // 0.2s
}

echo "\n=== PODSUMOWANIE ===\n";
echo "Skompresowano: {$stats['compressed']}\n";
echo "Pominięto: {$stats['skipped']}\n";
echo "Błędy: {$stats['errors']}\n";
echo "Zaoszczędzono: " . round($stats['saved']/1024, 1) . " KB\n";
