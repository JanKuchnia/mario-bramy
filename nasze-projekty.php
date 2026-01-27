<?php
/**
 * Mario Bramy - Galeria realizacji (dynamiczna)
 */
require_once 'includes/config.php';
require_once 'includes/functions.php';

$page_title = 'Przykładowe Realizacje - Mario Bramy';
$page_description = 'Galeria realizacji nowoczesnych bram i ogrodzeń aluminiowych. Zobacz przykładowe realizacje wykonane dla klientów w Małopolsce i całej Polsce.';

// Pobierz obrazy z bazy
$gallery_images = get_gallery_images();
$categories = get_gallery_categories();

// Grupuj obrazy po kategorii
$images_by_category = [];
foreach ($gallery_images as $img) {
    $images_by_category[$img['category']][] = $img;
}

// Aktywna kategoria z URL
$active_category = isset($_GET['category']) ? sanitize_input($_GET['category']) : 'all';

require_once 'includes/header.php';
?>

      <section class="code-section pt-40 lg:pt-48 pb-20 lg:pb-32 bg-white mt-8">
        <div class="container mx-auto px-4 lg:px-8">
          <div class="text-center max-w-3xl mx-auto mb-12 lg:mb-16">
            <h1
              class="text-3xl sm:text-4xl lg:text-6xl font-bold text-[var(--dark-text-color)] font-[var(--font-family-heading)] mb-6"
            >
              Przykładowe Realizacje
            </h1>
          </div>

          <!-- Tab buttons -->
          <div class="flex flex-wrap justify-center mb-8 gap-3 sm:gap-4 mt-8">
            <button
              class="gallery-tab-button <?= $active_category === 'all' ? 'active' : '' ?> px-6 py-3 text-sm sm:text-base"
              data-category="all"
            >
              Wszystkie
            </button>
            <?php foreach ($categories as $key => $label): ?>
            <button
              class="gallery-tab-button <?= $active_category === $key ? 'active' : '' ?> px-6 py-3 text-sm sm:text-base"
              data-category="<?= htmlspecialchars($key) ?>"
            >
              <?= htmlspecialchars($label) ?>
            </button>
            <?php endforeach; ?>
          </div>

          <!-- Gallery panels -->
          <div id="gallery-all" class="gallery-grid tab-panel <?= $active_category === 'all' ? 'active' : '' ?>" <?= $active_category !== 'all' ? 'style="display: none"' : '' ?>>
            <?php if (empty($gallery_images)): ?>
              <p class="text-center text-gray-500 col-span-full py-12">Brak zdjęć w galerii. Dodaj je w panelu admina.</p>
            <?php else: ?>
              <?php foreach ($gallery_images as $img): ?>
              <div class="gallery-item" data-category="<?= htmlspecialchars($img['category']) ?>">
                <img 
                  src="assets/portfolio/<?= htmlspecialchars($img['category']) ?>/<?= htmlspecialchars($img['filename']) ?>" 
                  alt="<?= htmlspecialchars($img['alt_text'] ?? 'Realizacja Mario Bramy') ?>"
                  class="gallery-image cursor-pointer"
                  loading="lazy"
                />
              </div>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
          
          <?php foreach ($categories as $key => $label): ?>
          <div
            id="gallery-<?= htmlspecialchars($key) ?>"
            class="gallery-grid tab-panel <?= $active_category === $key ? 'active' : '' ?>"
            <?= $active_category !== $key ? 'style="display: none"' : '' ?>
          >
            <?php if (empty($images_by_category[$key])): ?>
              <p class="text-center text-gray-500 col-span-full py-12">Brak zdjęć w tej kategorii.</p>
            <?php else: ?>
              <?php foreach ($images_by_category[$key] as $img): ?>
              <div class="gallery-item">
                <img 
                  src="assets/portfolio/<?= htmlspecialchars($img['category']) ?>/<?= htmlspecialchars($img['filename']) ?>" 
                  alt="<?= htmlspecialchars($img['alt_text'] ?? 'Realizacja Mario Bramy') ?>"
                  class="gallery-image cursor-pointer"
                  loading="lazy"
                />
              </div>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
          <?php endforeach; ?>
        </div>
      </section>

    <!-- Modal -->
    <div id="galleryModal" class="modal">
      <span class="modal-close">&times;</span>
      <img
        class="modal-content"
        id="modalImage"
        alt="Powiększone zdjęcie realizacji"
      />
    </div>

<?php 
$extra_js = '<script src="assets/gallery.js"></script>';
require_once 'includes/footer.php'; 
?>
