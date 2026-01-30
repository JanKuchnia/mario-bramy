<?php
/**
 * Strona logowania do panelu admina
 */
require_once __DIR__ . '/../config/init.php';

// Jeśli już zalogowany, przekieruj do panelu
if (isAdminLoggedIn()) {
    header('Location: panel.php');
    exit;
}

$error = '';
if (isset($_SESSION['login_error'])) {
    $error = $_SESSION['login_error'];
    unset($_SESSION['login_error']);
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Mario Bramy</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <div class="login-container">
        <img src="../assets/logo.webp" alt="Mario Bramy" class="login-logo">
        <h2>Panel Administracyjny</h2>
        
        <?php if ($error): ?>
            <p class="error-message"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        
        <form action="login.php" method="POST">
            <div class="form-group">
                <label for="password">Hasło:</label>
                <input type="password" id="password" name="password" required autofocus>
            </div>
            <button type="submit">Zaloguj się</button>
        </form>
    </div>
</body>
</html>
