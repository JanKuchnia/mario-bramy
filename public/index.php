<?php
// TEMPORARY DEBUGGING - REMOVE AFTER FIXING
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/**
 * STRONA 1: Index.php - Strona Główna
 * Contains: Hero slideshow, Services, Why Choose Us, Portfolio Preview, Top 3 Reviews, CTA
 */

include '../config/database.php';
include '../config/constants.php';
$page_title = 'Strona główna';
$shop_href = null;
include '../config/settings.php';

// Determine shop link for CTAs (fallback to 'wkrotce')
$shop_href = get_setting('shop_target', 'wkrotce') === 'sklep' ? '/mario-bramy/public/sklep.php' : '/mario-bramy/public/wkrotce.php';
$additional_scripts = [
    '/mario-bramy/assets/js/gallery.js'
];

// Fetch top 3 reviews from database
$reviews_query = $db->query("
    SELECT id, author_name, rating, text, source, submitted_at
    FROM reviews 
    WHERE is_visible = 1 
    ORDER BY submitted_at DESC 
    LIMIT 3
");

$top_reviews = [];
if ($reviews_query && $reviews_query->num_rows > 0) {
    while ($row = $reviews_query->fetch_assoc()) {
        $top_reviews[] = $row;
    }
}

include '../includes/header.php';
?>

        <!-- Hero Section with Slideshow -->
        <section id="hero" class="relative h-screen bg-cover bg-center flex items-center justify-center text-white overflow-hidden">
            <!-- Background slideshow (CSS animations) -->
            <div class="absolute inset-0 z-0">
                <div class="hero-slide hero-slide-1 absolute inset-0 bg-cover bg-center transition-opacity duration-1000" style="background-image: url('/mario-bramy/assets/portfolio/bramy-przesuwne/1.webp'); opacity: 1;"></div>
                <div class="hero-slide hero-slide-2 absolute inset-0 bg-cover bg-center transition-opacity duration-1000" style="background-image: url('/mario-bramy/assets/portfolio/bramy-dwuskrzydlowe/1.webp'); opacity: 0;"></div>
                <div class="hero-slide hero-slide-3 absolute inset-0 bg-cover bg-center transition-opacity duration-1000" style="background-image: url('/mario-bramy/assets/portfolio/balustrady/1.webp'); opacity: 0;"></div>
                <div class="absolute inset-0 bg-black/50"></div>
            </div>
            
            <!-- Content -->
            <div class="relative z-10 text-center px-4 max-w-3xl">
                <h1 class="text-5xl md:text-6xl font-bold mb-6">Mario Bramy</h1>
                <p class="text-xl md:text-2xl mb-8 text-gray-200">Profesjonalne bramy i ogrodzenia aluminiowe na najwyższym poziomie</p>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="<?php echo htmlspecialchars($shop_href); ?>" class="bg-primary-color hover:bg-primary-button-hover-bg-color text-white font-bold py-3 px-8 rounded transition">
                        Szybka Wycena
                    </a>
                    <a href="tel:+48668197170" class="bg-white text-primary-color hover:bg-gray-100 font-bold py-3 px-8 rounded transition">
                        📞 Zadzwoń: 668 197 170
                    </a>
                </div>
            </div>
        </section>

        <!-- Services Section -->
        <section id="services" class="py-16 bg-gray-50">
            <div class="container mx-auto px-4">
                <h2 class="text-4xl font-bold text-center mb-12">Nasza Oferta</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <!-- Service 1 -->
                    <div class="bg-white p-8 rounded-lg shadow hover:shadow-lg transition text-center">
                        <img src="/mario-bramy/assets/icons/gate-sliding.png" alt="Bramy Przesuwne" class="w-16 h-16 mx-auto mb-4" loading="lazy">
                        <h3 class="text-xl font-bold mb-3">Bramy Przesuwne</h3>
                        <p class="text-gray-600 text-sm">Nowoczesne, bezpieczne i niezawodne rozwiązania do każdego garażu i posesji.</p>
                    </div>
                    
                    <!-- Service 2 -->
                    <div class="bg-white p-8 rounded-lg shadow hover:shadow-lg transition text-center">
                        <img src="/mario-bramy/assets/icons/gate-double.png" alt="Bramy Dwuskrzydłowe" class="w-16 h-16 mx-auto mb-4" loading="lazy">
                        <h3 class="text-xl font-bold mb-3">Bramy Dwuskrzydłowe</h3>
                        <p class="text-gray-600 text-sm">Eleganckie bramy dwuskrzydłowe, idealne dla wjazdów do posesji.</p>
                    </div>
                    
                    <!-- Service 3 -->
                    <div class="bg-white p-8 rounded-lg shadow hover:shadow-lg transition text-center">
                        <img src="/mario-bramy/assets/icons/automation.png" alt="Automatyka" class="w-16 h-16 mx-auto mb-4" loading="lazy">
                        <h3 class="text-xl font-bold mb-3">Automatyka</h3>
                        <p class="text-gray-600 text-sm">Inteligentne systemy automatyczne, sterowanie pilotem i aplikacją.</p>
                    </div>
                    
                    <!-- Service 4 -->
                    <div class="bg-white p-8 rounded-lg shadow hover:shadow-lg transition text-center">
                        <img src="/mario-bramy/assets/icons/railing.png" alt="Balustrady" class="w-16 h-16 mx-auto mb-4" loading="lazy">
                        <h3 class="text-xl font-bold mb-3">Balustrady</h3>
                        <p class="text-gray-600 text-sm">Trwałe balustrady aluminiowe do tarasów, balkonów i schodów.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Why Choose Us Section -->
        <section id="why-us" class="py-16">
            <div class="container mx-auto px-4">
                <h2 class="text-4xl font-bold text-center mb-12">Dlaczego nas wybrać?</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <div class="text-center">
                        <div class="text-5xl font-bold text-primary-color mb-4">15+</div>
                        <h3 class="text-xl font-bold mb-3">Lat Doświadczenia</h3>
                        <p class="text-gray-600">Sprawdzona na rynku firma z bogatym doświadczeniem i wieloma zadowolonymi klientami.</p>
                    </div>
                    
                    <div class="text-center">
                        <div class="text-5xl font-bold text-primary-color mb-4">100+</div>
                        <h3 class="text-xl font-bold mb-3">Realizacji</h3>
                        <p class="text-gray-600">Setki pomyślnie zrealizowanych projektów w całym regionie.</p>
                    </div>
                    
                    <div class="text-center">
                        <div class="text-5xl font-bold text-primary-color mb-4">10</div>
                        <h3 class="text-xl font-bold mb-3">Lat Gwarancji</h3>
                        <p class="text-gray-600">Rozszerzona gwarancja na wszystkie nasze produkty i usługi.</p>
                    </div>
                    
                    <div class="text-center">
                        <div class="text-5xl font-bold text-primary-color mb-4">24/7</div>
                        <h3 class="text-xl font-bold mb-3">Serwis</h3>
                        <p class="text-gray-600">Szybka i profesjonalna obsługa serwisowa dostępna non-stop.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Portfolio Preview Section -->
        <section id="portfolio-preview" class="py-16 bg-gray-50">
            <div class="container mx-auto px-4">
                <h2 class="text-4xl font-bold text-center mb-12">Nasze Realizacje</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-white rounded-lg overflow-hidden shadow hover:shadow-lg transition cursor-pointer portfolio-item" data-id="1">
                        <img src="/mario-bramy/assets/portfolio/bramy-przesuwne/1.webp" alt="Realizacja 1" class="w-full h-64 object-cover lazy-image" loading="lazy">
                    </div>
                    <div class="bg-white rounded-lg overflow-hidden shadow hover:shadow-lg transition cursor-pointer portfolio-item" data-id="2">
                        <img src="/mario-bramy/assets/portfolio/bramy-dwuskrzydlowe/1.webp" alt="Realizacja 2" class="w-full h-64 object-cover lazy-image" loading="lazy">
                    </div>
                    <div class="bg-white rounded-lg overflow-hidden shadow hover:shadow-lg transition cursor-pointer portfolio-item" data-id="3">
                        <img src="/mario-bramy/assets/portfolio/balustrady/1.webp" alt="Realizacja 3" class="w-full h-64 object-cover lazy-image" loading="lazy">
                    </div>
                </div>
                
                <div class="text-center">
                    <a href="/mario-bramy/public/nasze-projekty.php" class="bg-primary-color hover:bg-primary-button-hover-bg-color text-white font-bold py-3 px-8 rounded transition inline-block">
                        Wszystkie Realizacje →
                    </a>
                </div>
            </div>
        </section>

        <!-- Top 3 Reviews Section (AUTO from Database) -->
        <section id="reviews" class="py-16">
            <div class="container mx-auto px-4">
                <h2 class="text-4xl font-bold text-center mb-12">Opinie Naszych Klientów</h2>
                
                <?php if (!empty($top_reviews)): ?>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                        <?php foreach ($top_reviews as $review): ?>
                            <div class="bg-white p-8 rounded-lg shadow hover:shadow-lg transition">
                                <!-- Rating Stars -->
                                <div class="text-yellow-400 text-2xl mb-4">
                                    <?php 
                                    $rating = intval($review['rating']);
                                    echo str_repeat('★', $rating) . str_repeat('☆', 5 - $rating); 
                                    ?>
                                </div>
                                
                                <!-- Review Text -->
                                <p class="text-gray-700 italic mb-4">
                                    "<?php echo htmlspecialchars(substr($review['text'], 0, 150)); ?>..."
                                </p>
                                
                                <!-- Author -->
                                <p class="font-semibold text-gray-900 mb-2">
                                    <?php echo htmlspecialchars($review['author_name']); ?>
                                </p>
                                
                                <!-- Source Badge -->
                                <p class="text-sm text-gray-500">
                                    <?php echo ($review['source'] === 'google') ? '🔍 Google' : '✍️ Opinia'; ?>
                                </p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="text-center">
                        <a href="/mario-bramy/public/opinie.php" class="text-primary-color font-semibold hover:underline">
                            Przeczytaj wszystkie opinie →
                        </a>
                    </div>
                <?php else: ?>
                    <p class="text-center text-gray-600">Brak opinii do wyświetlenia.</p>
                <?php endif; ?>
            </div>
        </section>

        <!-- CTA Section -->
        <section id="cta" class="py-16 bg-primary-color text-white">
            <div class="container mx-auto px-4 text-center">
                <h2 class="text-4xl font-bold mb-6">Gotowy na zmianę?</h2>
                <p class="text-xl mb-8 text-gray-100">Skontaktuj się z nami dziś i uzyskaj bezpłatną wycenę</p>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="<?php echo htmlspecialchars($shop_href); ?>" class="bg-white text-primary-color hover:bg-gray-100 font-bold py-3 px-8 rounded transition">
                        Konfigurator Bramek
                    </a>
                    <a href="/mario-bramy/public/kontakt.php" class="bg-white text-primary-color hover:bg-gray-100 font-bold py-3 px-8 rounded transition">
                        Formularz Kontaktowy
                    </a>
                </div>
            </div>
        </section>

<?php
include '../includes/footer.php';
?>
