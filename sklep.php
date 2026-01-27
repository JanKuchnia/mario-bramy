<?php
/**
 * Mario Bramy - Sklep (dynamiczny)
 */
require_once 'includes/config.php';
require_once 'includes/functions.php';

$page_title = 'Sklep - Mario Bramy';
$page_description = 'Skonfiguruj swoją wymarzoną bramę lub ogrodzenie aluminiowe. Sprawdź cenę i zamów bezpłatną wycenę.';

// Pobierz produkty z bazy
$products = get_products();

// Przygotuj dane produktów z opcjami jako JSON dla JavaScript
$products_with_options = [];
foreach ($products as $product) {
    $product['options'] = get_product_options($product['id']);
    $products_with_options[] = $product;
}
$products_json = json_encode($products_with_options, JSON_UNESCAPED_UNICODE);

$extra_css = '
<style>
  .line-clamp-2 {
    overflow: hidden;
    display: -webkit-box;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 2;
    line-clamp: 2;
  }
  .line-clamp-none {
    -webkit-line-clamp: unset;
    line-clamp: unset;
  }
  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(-5px); }
    to { opacity: 1; transform: translateY(0); }
  }
  .animate-fade-in {
    animation: fadeIn 0.3s ease-out forwards;
  }
  @keyframes shimmer {
    100% { transform: translateX(100%); }
  }
  .animate-shimmer {
    animation: shimmer 1.5s infinite;
  }
</style>';

require_once 'includes/header.php';
?>

      <section
        class="mt-8 code-section pt-32 lg:pt-20 pb-20 lg:pb-32 bg-white mb-16"
      >
        <div class="container mx-auto px-4 lg:px-8">
          <div class="text-center max-w-3xl mx-auto mb-16">
            <h1
              class="text-3xl sm:text-4xl lg:text-5xl font-bold text-[var(--dark-text-color)] font-[var(--font-family-heading)] mb-6"
            >
              Nasz Sklep i Konfigurator
            </h1>
            <p class="text-lg text-[var(--gray-text-color)]">
              Wybierz produkt, skonfiguruj go według potrzeb i uzyskaj
              szacunkową wycenę.
            </p>
          </div>

          <div
            id="shop-grid"
            class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8"
          >
            <?php if (empty($products)): ?>
              <p class="text-center text-gray-500 col-span-full py-12">
                Brak produktów w sklepie. Dodaj je w panelu admina.
              </p>
            <?php endif; ?>
            <!-- Products will be rendered by JavaScript using the data below -->
          </div>
        </div>
      </section>

<?php 
$extra_js = '
<script>
  // Dane produktów z PHP
  window.SHOP_PRODUCTS = ' . $products_json . ';
</script>
<script src="assets/shop.js"></script>';
require_once 'includes/footer.php'; 
?>
