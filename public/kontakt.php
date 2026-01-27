<?php
/**
 * STRONA 4: kontakt.php - Formularz kontaktowy i dane firmy
 * Displays contact info, Google Maps, contact form
 */

include '../config/database.php';
include '../config/constants.php';
include '../config/security.php';

$page_title = 'Kontakt';

// Fetch company info
$company_query = $db->query("SELECT * FROM company_info WHERE id = 1");
$company = $company_query ? $company_query->fetch_assoc() : null;

// Generate CSRF token
$csrf_token = generate_csrf_token();

// Message variables
$success_message = '';
$error_message = '';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (empty($_POST['csrf_token']) || !validate_csrf_token($_POST['csrf_token'])) {
        $error_message = 'CSRF token invalid. Please try again.';
    } 
    // Check rate limiting
    elseif (!check_rate_limit($_SERVER['REMOTE_ADDR'], 'contact', 5, 3600)) {
        $error_message = 'Zbyt wiele wiadomości z Twojego adresu IP. Spróbuj za godzinę.';
    }
    else {
        // Validate fields
        $name = isset($_POST['name']) ? sanitize_input($_POST['name']) : '';
        $email = isset($_POST['email']) ? validate_email($_POST['email']) : false;
        $phone = isset($_POST['phone']) ? sanitize_input($_POST['phone']) : '';
        $product_interest = isset($_POST['product_interest']) ? sanitize_input($_POST['product_interest']) : null;
        $estimated_price = isset($_POST['estimated_price']) ? floatval($_POST['estimated_price']) : null;
        $message = isset($_POST['message']) ? sanitize_input($_POST['message']) : '';
        
        // Validation
        if (empty($name) || strlen($name) < 3) {
            $error_message = 'Imię musi mieć co najmniej 3 znaki.';
        } elseif (!$email) {
            $error_message = 'Email jest nieprawidłowy.';
        } elseif (empty($phone) || strlen($phone) < 9) {
            $error_message = 'Numer telefonu musi mieć co najmniej 9 znaków.';
        } elseif (empty($message) || strlen($message) < 10) {
            $error_message = 'Wiadomość musi mieć co najmniej 10 znaków.';
        } else {
            // Insert into database
            $ip_address = $_SERVER['REMOTE_ADDR'];
            $product_interest = $product_interest ?: null;
            
            $sql = "INSERT INTO contact_submissions 
                    (name, email, phone, product_interest, estimated_price, message, ip_address, status, submitted_at)
                    VALUES (?, ?, ?, ?, ?, ?, ?, 'pending', NOW())";
            
            $stmt = $db->prepare($sql);
            if (!$stmt) {
                $error_message = 'Błąd bazy danych. Spróbuj ponownie.';
            } else {
                $stmt->bind_param('sssssds', $name, $email, $phone, $product_interest, $estimated_price, $message, $ip_address);
                
                if ($stmt->execute()) {
                    // Send email to admin (optional)
                    $admin_email = 'mario.bramy@gmail.com';
                    $subject = 'Nowa wiadomość z formularza kontaktowego - Mario Bramy';
                    $email_body = "
                        Nowa wiadomość ze strony Mario Bramy:
                        
                        Imię: $name
                        Email: $email
                        Telefon: $phone
                        Produkt: $product_interest
                        Szacunkowa cena: $estimated_price PLN
                        
                        Wiadomość:
                        $message
                        
                        IP: $ip_address
                        Data: " . date('Y-m-d H:i:s') . "
                    ";
                    
                    // Attempt to send email (optional - don't fail if email doesn't work)
                    @mail($admin_email, $subject, $email_body, "From: $email\r\nContent-Type: text/plain; charset=UTF-8");
                    
                    $success_message = 'Dziękujemy! Wiadomość została wysłana. Skontaktujemy się z Tobą wkrótce.';
                } else {
                    $error_message = 'Błąd przy wysyłaniu wiadomości. Spróbuj ponownie.';
                }
            }
        }
    }
}

include '../includes/header.php';
?>

        <!-- Page Hero -->
        <section class="bg-gray-900 text-white py-12">
            <div class="container mx-auto px-4">
                <h1 class="text-4xl font-bold mb-3">Skontaktuj się z nami</h1>
                <p class="text-gray-300">Mamy wiele sposobów, aby się z Tobą połączyć. Wybierz najwygodniejszy dla Ciebie.</p>
            </div>
        </section>

        <!-- Contact Info Cards -->
        <section class="py-16 bg-gray-50">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
                    <!-- Phone -->
                    <div class="bg-white p-8 rounded-lg shadow text-center">
                        <div class="text-4xl text-primary-color mb-4">📞</div>
                        <h3 class="text-xl font-bold mb-3">Zadzwoń</h3>
                        <a href="tel:<?php echo str_replace(' ', '', $company['phone']); ?>" class="text-primary-color font-semibold hover:underline">
                            <?php echo htmlspecialchars($company['phone']); ?>
                        </a>
                        <p class="text-gray-600 text-sm mt-3">Dostępni: Pn-Pt 08:00-17:00, Sob 09:00-13:00</p>
                    </div>
                    
                    <!-- Email -->
                    <div class="bg-white p-8 rounded-lg shadow text-center">
                        <div class="text-4xl text-primary-color mb-4">📧</div>
                        <h3 class="text-xl font-bold mb-3">Wyślij email</h3>
                        <a href="mailto:<?php echo htmlspecialchars($company['email']); ?>" class="text-primary-color font-semibold hover:underline">
                            <?php echo htmlspecialchars($company['email']); ?>
                        </a>
                        <p class="text-gray-600 text-sm mt-3">Odpowiemy w ciągu 24 godzin</p>
                    </div>
                    
                    <!-- Address -->
                    <div class="bg-white p-8 rounded-lg shadow text-center">
                        <div class="text-4xl text-primary-color mb-4">📍</div>
                        <h3 class="text-xl font-bold mb-3">Odwiedź nas</h3>
                        <p class="text-gray-700 font-semibold">
                            <?php echo htmlspecialchars($company['address']); ?>
                        </p>
                        <p class="text-gray-600 text-sm mt-3">Wizyty na umówienie</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contact Form and Map Section -->
        <section class="py-16">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                    <!-- Contact Form -->
                    <div>
                        <h2 class="text-3xl font-bold mb-8">Wyślij nam wiadomość</h2>
                        
                        <?php if ($success_message): ?>
                            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-4 rounded mb-6">
                                <?php echo htmlspecialchars($success_message); ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($error_message): ?>
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-4 rounded mb-6">
                                <?php echo htmlspecialchars($error_message); ?>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" action="" class="space-y-6">
                            <!-- CSRF Token -->
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
                            
                            <!-- Name -->
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">Imię i nazwisko *</label>
                                <input 
                                    type="text" 
                                    name="name" 
                                    required 
                                    class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-primary-color"
                                    placeholder="Twoje imię">
                            </div>
                            
                            <!-- Email -->
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">Email *</label>
                                <input 
                                    type="email" 
                                    name="email" 
                                    required 
                                    class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-primary-color"
                                    placeholder="twój@email.com">
                            </div>
                            
                            <!-- Phone -->
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">Telefon *</label>
                                <input 
                                    type="tel" 
                                    name="phone" 
                                    required 
                                    class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-primary-color"
                                    placeholder="123456789">
                            </div>
                            
                            <!-- Product Interest -->
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">Zainteresowana brama (opcjonalnie)</label>
                                <select 
                                    name="product_interest" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-primary-color">
                                    <option value="">-- Wybierz --</option>
                                    <option value="Brama Przesuwna">Brama Przesuwna</option>
                                    <option value="Brama Dwuskrzydłowa">Brama Dwuskrzydłowa</option>
                                    <option value="Automatyka">Automatyka</option>
                                    <option value="Balustrady">Balustrady</option>
                                    <option value="Inne">Inne</option>
                                </select>
                            </div>
                            
                            <!-- Estimated Price (hidden if not from sklep.php) -->
                            <input type="hidden" name="estimated_price" value="<?php echo isset($_GET['price']) ? htmlspecialchars($_GET['price']) : '0'; ?>">
                            
                            <!-- Message -->
                            <div>
                                <label class="block text-gray-700 font-semibold mb-2">Wiadomość *</label>
                                <textarea 
                                    name="message" 
                                    required 
                                    rows="6"
                                    class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-primary-color"
                                    placeholder="Twoja wiadomość..."></textarea>
                            </div>
                            
                            <!-- Submit -->
                            <button 
                                type="submit" 
                                class="w-full bg-primary-color hover:bg-primary-button-hover-bg-color text-white font-bold py-3 px-6 rounded transition">
                                Wyślij wiadomość
                            </button>
                        </form>
                    </div>
                    
                    <!-- Google Maps -->
                    <div>
                        <h2 class="text-3xl font-bold mb-8">Nasza lokalizacja</h2>
                        
                        <?php if ($company && $company['google_maps_embed_url']): ?>
                            <div class="h-96 rounded-lg overflow-hidden shadow">
                                <iframe 
                                    width="100%" 
                                    height="100%" 
                                    style="border:0;" 
                                    src="<?php echo htmlspecialchars($company['google_maps_embed_url']); ?>" 
                                    allowfullscreen="" 
                                    loading="lazy" 
                                    referrerpolicy="no-referrer-when-downgrade">
                                </iframe>
                            </div>
                        <?php else: ?>
                            <div class="bg-gray-200 h-96 rounded-lg flex items-center justify-center">
                                <p class="text-gray-600">Mapa będzie tutaj</p>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Social Links -->
                        <div class="mt-8">
                            <h3 class="text-xl font-bold mb-4">Śledź nas</h3>
                            <div class="flex gap-4">
                                <a href="https://www.facebook.com" target="_blank" rel="noopener" class="bg-blue-600 text-white p-4 rounded hover:bg-blue-700 transition">
                                    <i class="fab fa-facebook-f"></i> Facebook
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

<?php
include '../includes/footer.php';
?>
