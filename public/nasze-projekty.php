<?php
/**
 * STRONA 2: nasze-projekty.php - Galeria z kategoriami
 * Contains: Tabbed interface (7 categories), responsive grid, modal, lazy loading
 */

include '../config/database.php';
include '../config/constants.php';

$page_title = 'Nasze Realizacje';
$additional_scripts = [
    '/mario-bramy/assets/js/gallery.js'
];

// Fetch all portfolio categories
$categories_query = $db->query("
    SELECT id, slug, name, description
    FROM portfolio_categories
    ORDER BY sort_order ASC
");

$categories = [];
if ($categories_query && $categories_query->num_rows > 0) {
    while ($row = $categories_query->fetch_assoc()) {
        $categories[] = $row;
    }
}

include '../includes/header.php';
?>

        <!-- Page Hero -->
        <section class="bg-gray-900 text-white py-12">
            <div class="container mx-auto px-4">
                <h1 class="text-4xl font-bold mb-3">Nasze Realizacje</h1>
                <p class="text-gray-300">Zobaczcie nasze najnowsze projekty i realizacje. Każda brama i ogrodzenie wykonane z najwyższą starannością.</p>
            </div>
        </section>

        <!-- Main Content -->
        <section class="py-16">
            <div class="container mx-auto px-4">
                <!-- Tabbed Interface -->
                <div class="mb-8 flex flex-wrap gap-2 border-b border-gray-200">
                    <?php foreach ($categories as $category): ?>
                        <button 
                            class="category-tab py-3 px-4 font-semibold transition hover:text-primary-color" 
                            data-category="<?php echo htmlspecialchars($category['slug']); ?>"
                            data-category-name="<?php echo htmlspecialchars($category['name']); ?>">
                            <?php echo htmlspecialchars($category['name']); ?>
                        </button>
                    <?php endforeach; ?>
                </div>
                
                <!-- Loading Indicator -->
                <div id="loading" class="text-center py-8 hidden">
                    <p class="text-gray-600">Ładowanie zdjęć...</p>
                </div>
                
                <!-- Gallery Grid -->
                <div id="gallery-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <!-- Zdjęcia załadują się via JavaScript AJAX -->
                </div>
                
                <!-- No Images Message -->
                <div id="no-images" class="text-center py-8 hidden">
                    <p class="text-gray-600">Brak zdjęć w tej kategorii.</p>
                </div>
            </div>
        </section>

        <!-- Modal for Image Zoom -->
        <div id="image-modal" class="fixed inset-0 bg-black/80 z-50 hidden flex items-center justify-center p-4">
            <div class="relative max-w-4xl w-full">
                <!-- Close Button -->
                <button id="modal-close" class="absolute -top-10 right-0 text-white text-3xl">
                    <i class="fas fa-times"></i>
                </button>
                
                <!-- Image -->
                <img id="modal-image" src="" alt="" class="w-full rounded-lg">
                
                <!-- Image Info -->
                <div class="bg-gray-900 text-white p-4 rounded-b-lg">
                    <p id="modal-caption" class="text-center"></p>
                </div>
            </div>
        </div>

<?php
include '../includes/footer.php';
?>
