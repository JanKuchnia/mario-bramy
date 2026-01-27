<?php
/**
 * ADMIN: dashboard.php
 * Main admin panel with 4 tabs: Statistics, Gallery, Products, Messages
 */

include '../config/database.php';
include '../config/constants.php';
include '../config/security.php';
include '../config/auth.php';

// Require admin login
require_login();
check_session_timeout(SESSION_TIMEOUT_MINUTES);
reset_activity_timer();

// Get current admin
$admin_id = get_current_admin_id();
$sql = "SELECT username FROM admin_users WHERE id = ?";
$stmt = $db->prepare($sql);

// Check if prepare() succeeded
if (!$stmt) {
    error_log('Dashboard Error: prepare() failed - ' . $db->error);
    $admin = ['username' => 'Admin'];
} else {
    $stmt->bind_param('i', $admin_id);
    if (!$stmt->execute()) {
        error_log('Dashboard Error: execute() failed - ' . $stmt->error);
        $admin = ['username' => 'Admin'];
    } else {
        $admin_query = $stmt->get_result();
        $fetched = $admin_query ? $admin_query->fetch_assoc() : null;
        $admin = $fetched !== null ? $fetched : ['username' => 'Admin'];
    }
}

// Get section from GET
$section = isset($_GET['section']) ? sanitize_input($_GET['section']) : 'dashboard';

// Get statistics
$product_count = $db->query("SELECT COUNT(*) as count FROM products WHERE is_visible = 1");
$product_stat = $product_count ? $product_count->fetch_assoc() : ['count' => 0];

$gallery_count = $db->query("SELECT COUNT(*) as count FROM portfolio_images WHERE is_visible = 1");
$gallery_stat = $gallery_count ? $gallery_count->fetch_assoc() : ['count' => 0];

$messages_count = $db->query("SELECT COUNT(*) as count FROM contact_submissions WHERE status = 'pending'");
$messages_stat = $messages_count ? $messages_count->fetch_assoc() : ['count' => 0];

// Generate CSRF token for forms
$csrf_token = generate_csrf_token();
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administracyjny - Mario Bramy</title>
    <link rel="stylesheet" href="/mario-bramy/assets/base.css">
    <link rel="stylesheet" href="/mario-bramy/assets/style.css">
    <link rel="stylesheet" href="/mario-bramy/admin/admin.css">
    <link rel="stylesheet" href="/mario-bramy/assets/fontawesome.css">
</head>
<body class="bg-gray-100">
    <!-- Header -->
    <header class="bg-gray-900 text-white shadow">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <div class="flex items-center gap-4">
                <img src="/mario-bramy/assets/images/logo-white.png" alt="Mario Bramy" height="40">
                <h1 class="text-2xl font-bold">Panel Administracyjny</h1>
            </div>
            
            <div class="flex items-center gap-6">
                <span class="text-gray-300">Zalogowany: <strong><?php echo htmlspecialchars($admin['username']); ?></strong></span>
                <a href="/mario-bramy/admin/logout.php" class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded transition">
                    Wyloguj się
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="flex min-h-screen pt-0">
        <!-- Sidebar Navigation -->
        <aside class="w-64 bg-gray-900 text-white">
            <nav class="p-6 space-y-2">
                <a href="?section=dashboard" class="block px-4 py-3 rounded <?php echo $section === 'dashboard' ? 'bg-primary-color' : 'hover:bg-gray-800'; ?> transition">
                    📊 Dashboard
                </a>
                <a href="?section=gallery" class="block px-4 py-3 rounded <?php echo $section === 'gallery' ? 'bg-primary-color' : 'hover:bg-gray-800'; ?> transition">
                    🖼️ Galeria
                </a>
                <a href="?section=products" class="block px-4 py-3 rounded <?php echo $section === 'products' ? 'bg-primary-color' : 'hover:bg-gray-800'; ?> transition">
                    🛍️ Produkty
                </a>
                <a href="?section=messages" class="block px-4 py-3 rounded <?php echo $section === 'messages' ? 'bg-primary-color' : 'hover:bg-gray-800'; ?> transition">
                    💬 Wiadomości
                </a>
            </nav>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 p-8">
            <!-- Dashboard Section -->
            <?php if ($section === 'dashboard'): ?>
                <h2 class="text-3xl font-bold mb-8">Dashboard</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
                    <!-- Stats Cards -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-gray-600 text-sm font-semibold mb-2">Produkty</h3>
                        <p class="text-4xl font-bold text-primary-color"><?php echo $product_stat['count']; ?></p>
                    </div>
                    
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-gray-600 text-sm font-semibold mb-2">Zdjęcia Galerii</h3>
                        <p class="text-4xl font-bold text-primary-color"><?php echo $gallery_stat['count']; ?></p>
                    </div>
                    
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-gray-600 text-sm font-semibold mb-2">Nowe Wiadomości</h3>
                        <p class="text-4xl font-bold text-primary-color"><?php echo $messages_stat['count']; ?></p>
                    </div>
                    
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-gray-600 text-sm font-semibold mb-2">Ostatnia Aktywność</h3>
                        <p class="text-2xl font-bold text-gray-700">
                            <?php echo date('d.m.Y H:i'); ?>
                        </p>
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-xl font-bold mb-4">Szybkie akcje</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <a href="?section=gallery" class="bg-blue-600 hover:bg-blue-700 text-white p-4 rounded transition">
                            Dodaj zdjęcie do galerii
                        </a>
                        <a href="?section=products" class="bg-green-600 hover:bg-green-700 text-white p-4 rounded transition">
                            Dodaj nowy produkt
                        </a>
                        <a href="?section=messages" class="bg-purple-600 hover:bg-purple-700 text-white p-4 rounded transition">
                            Przegląd wiadomości
                        </a>
                        <a href="/mario-bramy/" class="bg-gray-600 hover:bg-gray-700 text-white p-4 rounded transition">
                            Przejdź do strony
                        </a>
                    </div>
                </div>

            <!-- Gallery Section -->
            <?php elseif ($section === 'gallery'): ?>
                <h2 class="text-3xl font-bold mb-8">Zarządzanie Galerią</h2>
                
                <div class="bg-white rounded-lg shadow p-6 mb-8">
                    <h3 class="text-xl font-bold mb-4">Prześlij nowe zdjęcie</h3>
                    
                    <form id="gallery-upload-form" class="space-y-6">
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
                        
                        <!-- Category Select -->
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Kategoria</label>
                            <select id="category-select" name="category_id" required class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-primary-color">
                                <option value="">-- Wybierz kategorię --</option>
                                <?php
                                $categories = $db->query("SELECT id, name FROM portfolio_categories WHERE slug != 'wszystkie' ORDER BY sort_order");
                                while ($cat = $categories->fetch_assoc()):
                                ?>
                                    <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        
                        <!-- File Upload -->
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Plik (JPG, PNG, WebP - max 10MB)</label>
                            <input type="file" id="image-file" name="image" accept="image/*" required class="w-full px-4 py-2 border border-gray-300 rounded">
                        </div>
                        
                        <!-- Alt Text -->
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Opis zdjęcia (Alt text)</label>
                            <input type="text" name="alt_text" class="w-full px-4 py-2 border border-gray-300 rounded" placeholder="Opis dla SEO">
                        </div>
                        
                        <!-- Preview -->
                        <div id="image-preview" class="hidden">
                            <img id="preview-img" src="" alt="Preview" class="max-h-64 rounded">
                        </div>
                        
                        <!-- Submit -->
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded transition">
                            Prześlij zdjęcie
                        </button>
                    </form>
                </div>
                
                <!-- Existing Images -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-xl font-bold mb-4">Zdjęcia w galerii</h3>
                    <div id="images-list" class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <!-- Images will load here via JS -->
                        <p class="col-span-full text-gray-600">Ładowanie zdjęć...</p>
                    </div>
                </div>

            <!-- Products Section -->
            <?php elseif ($section === 'products'): ?>
                <h2 class="text-3xl font-bold mb-8">Zarządzanie Produktami</h2>
                
                <div class="mb-6">
                    <button id="add-product-btn" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded transition">
                        + Dodaj nowy produkt
                    </button>
                </div>
                
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-xl font-bold mb-4">Produkty</h3>
                    <div id="products-list" class="space-y-4">
                        <!-- Products will load here via JS -->
                        <p class="text-gray-600">Ładowanie produktów...</p>
                    </div>
                </div>

            <!-- Messages Section -->
            <?php elseif ($section === 'messages'): ?>
                <h2 class="text-3xl font-bold mb-8">Wiadomości Kontaktowe</h2>
                
                <div class="bg-white rounded-lg shadow p-6">
                    <div id="messages-list" class="space-y-6">
                        <!-- Messages will load here via JS -->
                        <p class="text-gray-600">Ładowanie wiadomości...</p>
                    </div>
                </div>
            <?php endif; ?>
        </main>
    </div>

    <!-- Scripts -->
    <script src="/mario-bramy/assets/js/admin.js"></script>
</body>
</html>
