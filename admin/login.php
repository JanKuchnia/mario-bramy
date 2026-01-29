<?php
/**
 * Obsługa logowania admina
 */
require_once __DIR__ . '/../config/init.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$password = $_POST['password'] ?? '';

// Weryfikacja hasła
if (password_verify($password, ADMIN_PASSWORD_HASH)) {
    // Zalogowano pomyślnie
    $_SESSION['admin_logged_in'] = true;
    $_SESSION['admin_logged_at'] = time();
    
    header('Location: panel.php');
    exit;
} else {
    // Błędne hasło
    $_SESSION['login_error'] = 'Nieprawidłowe hasło.';
    header('Location: index.php');
    exit;
}
