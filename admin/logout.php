<?php
/**
 * Wylogowanie admina
 */
require_once __DIR__ . '/../config/init.php';

// Zniszcz sesję
$_SESSION = [];
session_destroy();

// Przekieruj na stronę główną
header('Location: ../index.php');
exit;
