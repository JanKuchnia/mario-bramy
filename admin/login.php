<?php
/**
 * ADMIN: login.php
 * Login page with username + password (no email)
 * Session timeout: 30 minutes
 */

include '../config/database.php';
include '../config/constants.php';
include '../config/security.php';
include '../config/auth.php';

// If already logged in, redirect to dashboard
if (is_admin()) {
    header('Location: /mario-bramy/admin/dashboard.php');
    exit;
}

// Variables
$error_message = '';
$timeout_message = '';

// Check if timeout parameter exists
if (isset($_GET['timeout'])) {
    $timeout_message = 'Sesja wygasła. Zaloguj się ponownie.';
}

// Process login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (empty($_POST['csrf_token']) || !validate_csrf_token($_POST['csrf_token'])) {
        $error_message = 'Błąd CSRF. Odśwież stronę i spróbuj ponownie.';
    }
    // Check rate limiting
    elseif (!check_rate_limit($_SERVER['REMOTE_ADDR'], 'login', RATE_LIMIT_LOGIN_ATTEMPTS, RATE_LIMIT_LOGIN_WINDOW)) {
        $remaining = get_rate_limit_remaining($_SERVER['REMOTE_ADDR'], 'login', RATE_LIMIT_LOGIN_WINDOW);
        $minutes = ceil($remaining / 60);
        $error_message = "Zbyt wiele prób logowania. Spróbuj za $minutes minut.";
    }
    else {
        // Get credentials
        $username = isset($_POST['username']) ? sanitize_input($_POST['username']) : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        
        // Validate fields
        if (empty($username) || empty($password)) {
            $error_message = 'Nazwa użytkownika i hasło są wymagane.';
        } else {
            // Query admin user
            $sql = "SELECT id, password_hash FROM admin_users WHERE username = ? AND is_active = 1";
            $stmt = $db->prepare($sql);
            
            if (!$stmt) {
                $error_message = 'Błąd bazy danych. Spróbuj później.';
            } else {
                $stmt->bind_param('s', $username);
                $stmt->execute();
                $result = $stmt->get_result();
                $user = $result->fetch_assoc();
                
                // Verify password
                if ($user && verify_password($password, $user['password_hash'])) {
                    // Login successful
                    create_session($user['id'], $_SERVER['REMOTE_ADDR']);
                    
                    // Update last login
                    $update_sql = "UPDATE admin_users SET last_login = NOW(), last_login_ip = ? WHERE id = ?";
                    $update_stmt = $db->prepare($update_sql);
                    $update_stmt->bind_param('si', $_SERVER['REMOTE_ADDR'], $user['id']);
                    $update_stmt->execute();
                    
                    // Redirect to dashboard
                    header('Location: /mario-bramy/admin/dashboard.php');
                    exit;
                } else {
                    // Invalid credentials
                    $error_message = 'Nazwa użytkownika lub hasło nieprawidłowe.';
                }
            }
        }
    }
}

// Generate CSRF token
$csrf_token = generate_csrf_token();
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logowanie - Admin Panel - Mario Bramy</title>
    <link rel="stylesheet" href="/mario-bramy/assets/css/base.css">
    <link rel="stylesheet" href="/mario-bramy/assets/css/style.css">
    <link rel="stylesheet" href="/mario-bramy/assets/css/admin.css">
</head>
<body class="bg-gradient-to-br from-gray-900 to-gray-800">
    <div class="min-h-screen flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-md">
            <!-- Logo -->
            <div class="text-center mb-8">
                <img src="/mario-bramy/assets/images/logo-white.png" alt="Mario Bramy" height="50" class="mx-auto mb-4">
                <h1 class="text-3xl font-bold text-white">Panel Administracyjny</h1>
            </div>
            
            <!-- Login Card -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <!-- Error Messages -->
                <?php if ($timeout_message): ?>
                    <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-4">
                        <?php echo htmlspecialchars($timeout_message); ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($error_message): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-4">
                        <?php echo htmlspecialchars($error_message); ?>
                    </div>
                <?php endif; ?>
                
                <!-- Form -->
                <div class="p-8">
                    <form method="POST" action="" class="space-y-6">
                        <!-- CSRF Token -->
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
                        
                        <!-- Username -->
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Nazwa użytkownika</label>
                            <input 
                                type="text" 
                                name="username" 
                                required 
                                autofocus
                                class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-primary-color focus:ring-2 focus:ring-primary-color/50"
                                placeholder="admin">
                        </div>
                        
                        <!-- Password -->
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Hasło</label>
                            <input 
                                type="password" 
                                name="password" 
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-primary-color focus:ring-2 focus:ring-primary-color/50"
                                placeholder="••••••••">
                        </div>
                        
                        <!-- Submit Button -->
                        <button 
                            type="submit" 
                            class="w-full bg-primary-color hover:bg-primary-button-hover-bg-color text-white font-bold py-3 px-6 rounded transition">
                            Zaloguj się
                        </button>
                    </form>
                </div>
                
                <!-- Footer -->
                <div class="bg-gray-50 px-8 py-4 text-center text-sm text-gray-600">
                    <p>Session timeout: 30 minut braku aktywności</p>
                </div>
            </div>
            
            <!-- Back Link -->
            <div class="text-center mt-6">
                <a href="/mario-bramy/" class="text-white hover:text-gray-300 transition">
                    ← Powróć na stronę główną
                </a>
            </div>
        </div>
    </div>
</body>
</html>
