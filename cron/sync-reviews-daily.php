<?php
/**
 * CRON JOB: /cron/sync-reviews-daily.php
 * Run daily: Auto-pull reviews from Google Places API
 */

include __DIR__ . '/../config/database.php';
include __DIR__ . '/../config/constants.php';

// Security: Only allow from command line or internal requests
if (php_sapi_name() !== 'cli' && !in_array($_SERVER['REMOTE_ADDR'] ?? '', ['127.0.0.1', 'localhost'])) {
    http_response_code(403);
    exit('Access denied');
}

$log_file = __DIR__ . '/../logs/reviews-sync-' . date('Y-m-d') . '.log';
if (!is_dir(dirname($log_file))) {
    mkdir(dirname($log_file), 0755, true);
}

function log_message($msg) {
    global $log_file;
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($log_file, "[$timestamp] $msg\n", FILE_APPEND);
    echo "$msg\n";
}

try {
    log_message('=== Starting review sync ===');
    
    // Load configuration
    if (!file_exists(__DIR__ . '/../config/secrets.php')) {
        throw new Exception('secrets.php not found - configure Google API key first');
    }
    
    include __DIR__ . '/../config/secrets.php';
    
    if (!defined('GOOGLE_PLACES_API_KEY') || !defined('GOOGLE_PLACES_ID')) {
        throw new Exception('Google Places API credentials not configured');
    }
    
    // Fetch from Google Places API
    $url = "https://maps.googleapis.com/maps/api/place/details/json?place_id=" . urlencode(GOOGLE_PLACES_ID) . 
           "&key=" . urlencode(GOOGLE_PLACES_API_KEY) . "&reviews_sort=newest&language=pl";
    
    log_message("Fetching from Google Places API...");
    
    $response = file_get_contents($url);
    if ($response === false) {
        throw new Exception('Failed to fetch from Google Places API');
    }
    
    $data = json_decode($response, true);
    
    if ($data['status'] !== 'OK') {
        throw new Exception('Google API error: ' . $data['status']);
    }
    
    if (!isset($data['result']['reviews'])) {
        log_message('No reviews found in response');
        exit(0);
    }
    
    $reviews = $data['result']['reviews'];
    $inserted = 0;
    $skipped = 0;
    
    foreach ($reviews as $review) {
        // Check if already exists
        $check_sql = "SELECT id FROM reviews WHERE google_review_id = ?";
        $stmt = $db->prepare($check_sql);
        $stmt->bind_param('s', $review['time']);
        $stmt->execute();
        
        if ($stmt->get_result()->num_rows > 0) {
            $skipped++;
            continue;
        }
        
        // Insert new review
        $insert_sql = "INSERT INTO reviews (google_review_id, author_name, rating, text, source, is_visible, submitted_at) 
                       VALUES (?, ?, ?, ?, 'google', 1, FROM_UNIXTIME(?))";
        
        $stmt = $db->prepare($insert_sql);
        if (!$stmt) {
            log_message("Prepare failed: " . $db->error);
            continue;
        }
        
        $review_id = $review['time'] ?? md5($review['author_name'] . $review['rating']);
        $author = $review['author_name'] ?? 'Anonimowy';
        $rating = $review['rating'] ?? 5;
        $text = $review['text'] ?? '';
        $timestamp = $review['time'] ?? time();
        
        $stmt->bind_param('ssiss', $review_id, $author, $rating, $text, $timestamp);
        
        if ($stmt->execute()) {
            $inserted++;
            log_message("✓ Inserted review by $author (rating: $rating/5)");
        } else {
            log_message("✗ Failed to insert review: " . $db->error);
        }
    }
    
    log_message("Sync complete: $inserted inserted, $skipped skipped");
    log_message('=== Sync finished ===');
    
} catch (Exception $e) {
    log_message("ERROR: " . $e->getMessage());
    exit(1);
}

?>
