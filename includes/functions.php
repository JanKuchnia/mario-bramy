<?php
/**
 * Mario Bramy - Funkcje pomocnicze
 */

/**
 * Sanityzacja danych wejściowych
 */
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

/**
 * Przekierowanie
 */
function redirect($url) {
    header("Location: $url");
    exit;
}

/**
 * Sprawdzenie czy admin jest zalogowany
 */
function is_logged_in() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

/**
 * Wymuszenie logowania - użyj na chronionych stronach
 */
function require_login() {
    if (!is_logged_in()) {
        redirect('index.php');
    }
}

/**
 * Generowanie tokena CSRF
 */
function generate_csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Walidacja tokena CSRF
 */
function validate_csrf_token($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Zwrócenie odpowiedzi JSON
 */
function json_response($data, $status_code = 200) {
    http_response_code($status_code);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

/**
 * Pobierz wszystkie obrazy galerii
 */
function get_gallery_images($category = null) {
    $pdo = getDB();
    
    if ($category && $category !== 'all') {
        $stmt = $pdo->prepare("SELECT * FROM gallery_images WHERE category = ? ORDER BY sort_order, created_at DESC");
        $stmt->execute([$category]);
    } else {
        $stmt = $pdo->query("SELECT * FROM gallery_images ORDER BY category, sort_order, created_at DESC");
    }
    
    return $stmt->fetchAll();
}

/**
 * Pobierz wszystkie produkty
 */
function get_products($category = null, $active_only = true) {
    $pdo = getDB();
    
    $sql = "SELECT * FROM products WHERE 1=1";
    $params = [];
    
    if ($active_only) {
        $sql .= " AND is_active = 1";
    }
    
    if ($category) {
        $sql .= " AND category = ?";
        $params[] = $category;
    }
    
    $sql .= " ORDER BY created_at DESC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    
    return $stmt->fetchAll();
}

/**
 * Pobierz opcje produktu
 */
function get_product_options($product_id) {
    $pdo = getDB();
    
    $stmt = $pdo->prepare("
        SELECT po.*, pov.id as value_id, pov.value_label, pov.price_modifier
        FROM product_options po
        LEFT JOIN product_option_values pov ON po.id = pov.option_id
        WHERE po.product_id = ?
        ORDER BY po.id, pov.id
    ");
    $stmt->execute([$product_id]);
    
    $rows = $stmt->fetchAll();
    
    // Grupowanie po opcjach
    $options = [];
    foreach ($rows as $row) {
        if (!isset($options[$row['id']])) {
            $options[$row['id']] = [
                'id' => $row['id'],
                'name' => $row['option_name'],
                'type' => $row['option_type'],
                'values' => []
            ];
        }
        if ($row['value_id']) {
            $options[$row['id']]['values'][] = [
                'id' => $row['value_id'],
                'label' => $row['value_label'],
                'price_modifier' => $row['price_modifier']
            ];
        }
    }
    
    return array_values($options);
}

/**
 * Pobierz kategorie galerii
 */
function get_gallery_categories() {
    return [
        'bramy-przesuwne-aluminiowe' => 'Bramy Przesuwne',
        'bramy-dwuskrzydlowe' => 'Bramy Dwuskrzydłowe',
        'barierki' => 'Balustrady',
        'przesla-ogrodzeniowe-aluminiowe' => 'Przęsła Ogrodzeniowe',
        'automatyka' => 'Automatyka',
        'bramy-garazowe' => 'Bramy Garażowe'
    ];
}
