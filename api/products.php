<?php
/**
 * API - CRUD produktów
 */
require_once __DIR__ . '/../config/init.php';

$method = $_SERVER['REQUEST_METHOD'];

// GET - pobierz produkty
if ($method === 'GET') {
    try {
        $db = getDB();
        
        $id = $_GET['id'] ?? null;
        
        if ($id) {
            // Pojedynczy produkt
            $stmt = $db->prepare("SELECT * FROM products WHERE id = ?");
            $stmt->execute([$id]);
            $product = $stmt->fetch();
            
            if (!$product) {
                jsonError('Produkt nie znaleziony', 404);
            }
            
            // Parsuj options z JSON
            $product['options'] = json_decode($product['options'], true) ?? [];
            
            jsonSuccess(['product' => $product]);
        } else {
            // Wszystkie produkty
            $stmt = $db->query("SELECT * FROM products ORDER BY id DESC");
            $products = $stmt->fetchAll();
            
            // Parsuj options dla każdego produktu
            foreach ($products as &$product) {
                $product['options'] = json_decode($product['options'], true) ?? [];
            }
            
            jsonSuccess(['products' => $products]);
        }
    } catch (PDOException $e) {
        if (DEBUG_MODE) {
            jsonError('Błąd bazy danych: ' . $e->getMessage(), 500);
        }
        jsonError('Błąd pobierania produktów', 500);
    }
}

// Poniższe operacje wymagają autoryzacji
if (!isAdminLoggedIn()) {
    jsonError('Brak autoryzacji', 401);
}

// Pobierz dane z JSON body
$input = json_decode(file_get_contents('php://input'), true);
if (!$input && $method !== 'DELETE') {
    $input = $_POST;
}

// POST - dodaj nowy produkt
if ($method === 'POST') {
    $name = $input['name'] ?? '';
    $category = $input['category'] ?? '';
    $basePrice = (float)($input['base_price'] ?? $input['basePrice'] ?? 0);
    $image = $input['image'] ?? '';
    $description = $input['description'] ?? '';
    $options = $input['options'] ?? [];
    
    if (empty($name) || empty($category)) {
        jsonError('Nazwa i kategoria są wymagane');
    }
    
    try {
        $db = getDB();
        $stmt = $db->prepare("
            INSERT INTO products (name, category, base_price, image, description, options)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $name,
            $category,
            $basePrice,
            $image,
            $description,
            json_encode($options, JSON_UNESCAPED_UNICODE)
        ]);
        
        $newId = $db->lastInsertId();
        
        jsonSuccess([
            'message' => 'Produkt został dodany',
            'id' => $newId
        ]);
    } catch (PDOException $e) {
        if (DEBUG_MODE) {
            jsonError('Błąd bazy danych: ' . $e->getMessage(), 500);
        }
        jsonError('Błąd dodawania produktu', 500);
    }
}

// PUT - aktualizuj produkt
if ($method === 'PUT') {
    $id = $input['id'] ?? null;
    
    if (!$id) {
        jsonError('Brak ID produktu');
    }
    
    $name = $input['name'] ?? '';
    $category = $input['category'] ?? '';
    $basePrice = (float)($input['base_price'] ?? $input['basePrice'] ?? 0);
    $image = $input['image'] ?? '';
    $description = $input['description'] ?? '';
    $options = $input['options'] ?? [];
    
    if (empty($name) || empty($category)) {
        jsonError('Nazwa i kategoria są wymagane');
    }
    
    try {
        $db = getDB();
        $stmt = $db->prepare("
            UPDATE products 
            SET name = ?, category = ?, base_price = ?, image = ?, description = ?, options = ?
            WHERE id = ?
        ");
        $stmt->execute([
            $name,
            $category,
            $basePrice,
            $image,
            $description,
            json_encode($options, JSON_UNESCAPED_UNICODE),
            $id
        ]);
        
        if ($stmt->rowCount() === 0) {
            jsonError('Produkt nie znaleziony', 404);
        }
        
        jsonSuccess([
            'message' => 'Produkt został zaktualizowany',
            'id' => $id
        ]);
    } catch (PDOException $e) {
        if (DEBUG_MODE) {
            jsonError('Błąd bazy danych: ' . $e->getMessage(), 500);
        }
        jsonError('Błąd aktualizacji produktu', 500);
    }
}

// DELETE - usuń produkt
if ($method === 'DELETE') {
    $input = json_decode(file_get_contents('php://input'), true);
    $id = $input['id'] ?? $_GET['id'] ?? null;
    
    if (!$id) {
        jsonError('Brak ID produktu');
    }
    
    try {
        $db = getDB();
        $stmt = $db->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$id]);
        
        if ($stmt->rowCount() === 0) {
            jsonError('Produkt nie znaleziony', 404);
        }
        
        jsonSuccess([
            'message' => 'Produkt został usunięty',
            'id' => $id
        ]);
    } catch (PDOException $e) {
        if (DEBUG_MODE) {
            jsonError('Błąd bazy danych: ' . $e->getMessage(), 500);
        }
        jsonError('Błąd usuwania produktu', 500);
    }
}

// Nieobsługiwana metoda
jsonError('Metoda niedozwolona', 405);
