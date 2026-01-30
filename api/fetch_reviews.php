<?php
/**
 * API Proxy for Google Places Reviews
 * Fetches reviews from Google Places API and caches them to avoid rate limits.
 */
header('Content-Type: application/json');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/../config/config.php';

// Check if configuration is set
if (GOOGLE_PLACES_API_KEY === 'YOUR_API_KEY_HERE' || GOOGLE_PLACE_ID === 'YOUR_PLACE_ID_HERE') {
    http_response_code(500);
    echo json_encode(['error' => 'API Key or Place ID not configured in config.php']);
    exit;
}

// Check cache
$cacheFile = CACHE_PATH;
$cacheTime = 24 * 3600; // 24 hours

if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < $cacheTime)) {
    // Serve from cache
    echo file_get_contents($cacheFile);
    exit;
}

// Fetch from Google API
$apiKey = GOOGLE_PLACES_API_KEY;
$placeId = GOOGLE_PLACE_ID;
$url = "https://maps.googleapis.com/maps/api/place/details/json?place_id={$placeId}&fields=reviews,rating,user_ratings_total&language=pl&key={$apiKey}";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // For local dev environments if needed
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode === 200 && $response) {
    $data = json_decode($response, true);
    
    // Validate response from Google
    if (isset($data['status']) && $data['status'] === 'OK') {
        // Save to cache
        // Create cache folder if it doesn't exist (just in case)
        $cacheDir = dirname($cacheFile);
        if (!is_dir($cacheDir)) {
            mkdir($cacheDir, 0755, true);
        }
        
        file_put_contents($cacheFile, $response);
        echo $response;
    } else {
        // Log error but echo response for debugging frontend
        echo $response;
    }
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to connect to Google API']);
}
