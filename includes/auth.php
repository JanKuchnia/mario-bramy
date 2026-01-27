<?php
/**
 * Mario Bramy - Middleware autoryzacji admina
 * 
 * Dołącz ten plik na początku chronionych stron admina
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';

// Sprawdź czy zalogowany
if (!is_logged_in()) {
    // Dla requestów API zwróć JSON
    if (strpos($_SERVER['REQUEST_URI'], '/api/') !== false) {
        json_response(['error' => 'Unauthorized'], 401);
    }
    
    // Dla stron HTML przekieruj do logowania
    redirect('index.php');
}
