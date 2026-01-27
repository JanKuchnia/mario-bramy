<?php
/**
 * ADMIN API: /admin/api/messages-list.php
 * GET - List all contact messages
 */

include '../../config/database.php';
include '../../config/auth.php';

header('Content-Type: application/json; charset=utf-8');

// Require admin login
require_login();

try {
    $sql = "SELECT id, name, email, phone, product_interest, message, status, submitted_at
            FROM contact_submissions
            ORDER BY submitted_at DESC
            LIMIT 50";
    
    $result = $db->query($sql);
    
    if (!$result) {
        throw new Exception('Database query failed');
    }
    
    $messages = [];
    while ($row = $result->fetch_assoc()) {
        $messages[] = [
            'id' => (int)$row['id'],
            'name' => htmlspecialchars($row['name']),
            'email' => htmlspecialchars($row['email']),
            'phone' => htmlspecialchars($row['phone']),
            'product_interest' => htmlspecialchars($row['product_interest'] ?? ''),
            'message' => htmlspecialchars($row['message']),
            'status' => $row['status'],
            'submitted_at' => date('d.m.Y H:i', strtotime($row['submitted_at']))
        ];
    }
    
    echo json_encode([
        'success' => true,
        'count' => count($messages),
        'messages' => $messages
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error fetching messages'
    ]);
    error_log('Messages List Error: ' . $e->getMessage());
}

?>
