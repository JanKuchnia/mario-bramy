<?php
/**
 * STRONA 6: wkrotce.php - Coming Soon
 * Static page - bez zmian
 */

include '../config/database.php';
include '../config/constants.php';

$page_title = 'Wkrótce';

include '../includes/header.php';
?>

        <!-- Coming Soon Section -->
        <section class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-900 to-gray-800 text-white">
            <div class="text-center px-4">
                <!-- Icon -->
                <div class="text-7xl mb-6">🔨</div>
                
                <!-- Heading -->
                <h1 class="text-5xl md:text-6xl font-bold mb-4">Wkrótce coś nowego!</h1>
                
                <!-- Subtitle -->
                <p class="text-xl md:text-2xl text-gray-300 mb-8 max-w-2xl mx-auto">
                    Pracujemy nad nową funkcją, którą Ci się spodoba. Zaczekaj jeszcze chwilę...
                </p>
                
                <!-- CTA Button -->
                <a href="/mario-bramy/" class="inline-block bg-primary-color hover:bg-primary-button-hover-bg-color text-white font-bold py-3 px-8 rounded transition text-lg">
                    ← Powróć na stronę główną
                </a>
            </div>
        </section>

<?php
include '../includes/footer.php';
?>
