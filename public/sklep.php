<?php
/**
 * STRONA 5: sklep.php - Konfigurator produktów
 * Dynamic pricing, product selection, redirect to contact
 */

include '../config/database.php';
include '../config/constants.php';

$page_title = 'Konfigurator i Wyceny';
$additional_scripts = [
    '/mario-bramy/assets/js/shop.js'
];

include '../includes/header.php';
?>

        <!-- Page Hero -->
        <section class="bg-gray-900 text-white py-12">
            <div class="container mx-auto px-4">
                <h1 class="text-4xl font-bold mb-3">Konfigurator Bramek</h1>
                <p class="text-gray-300">Skonfiguruj swoją idealną bramę i otrzymaj natychmiastową wycenę.</p>
            </div>
        </section>

        <!-- Main Content -->
        <section class="py-16">
            <div class="container mx-auto px-4">
                <!-- Loading Indicator -->
                <div id="loading" class="text-center py-8">
                    <p class="text-gray-600">Ładowanie konfiguratora...</p>
                </div>
                
                <!-- Products Container (will be populated by JS) -->
                <div id="products-container" class="hidden">
                    <!-- Produkty załadują się via JavaScript AJAX -->
                </div>
            </div>
        </section>

        <!-- Info Section -->
        <section class="py-16 bg-gray-50">
            <div class="container mx-auto px-4">
                <h2 class="text-3xl font-bold text-center mb-12">Jak działa nasz konfigurator?</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="text-center">
                        <div class="text-5xl font-bold text-primary-color mb-4">1</div>
                        <h3 class="text-xl font-bold mb-3">Wybierz Bramę</h3>
                        <p class="text-gray-600">Zaznacz interesującą Cię bramę z naszej oferty.</p>
                    </div>
                    
                    <div class="text-center">
                        <div class="text-5xl font-bold text-primary-color mb-4">2</div>
                        <h3 class="text-xl font-bold mb-3">Dostosuj Parametry</h3>
                        <p class="text-gray-600">Konfiguruj wymiary, kolory, automatykę i inne opcje.</p>
                    </div>
                    
                    <div class="text-center">
                        <div class="text-5xl font-bold text-primary-color mb-4">3</div>
                        <h3 class="text-xl font-bold mb-3">Uzyskaj Wycenę</h3>
                        <p class="text-gray-600">Otrzymaj aktualne ceny i skontaktuj się z nami.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-16 bg-primary-color text-white">
            <div class="container mx-auto px-4 text-center">
                <h2 class="text-4xl font-bold mb-6">Nie wiesz, którą bramę wybrać?</h2>
                <p class="text-xl mb-8 text-gray-100">Skontaktuj się z naszymi specjalistami. Chętnie Ci pomogą!</p>
                
                <a href="/mario-bramy/public/kontakt.php" class="bg-white text-primary-color hover:bg-gray-100 font-bold py-3 px-8 rounded transition inline-block">
                    Skontaktuj się z nami
                </a>
            </div>
        </section>

<?php
include '../includes/footer.php';
?>
