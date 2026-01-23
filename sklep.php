<?php
$pageTitle = "Sklep i Konfigurator";
$pageDesc = "Wybierz produkt, skonfiguruj go według potrzeb i uzyskaj szacunkową wycenę.";
$extraScripts = '<script src="assets/shop.js"></script>';
$extraStyles = '
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

      /* Simple Fade In Animation */
      @keyframes fadeIn {
        from {
          opacity: 0;
          transform: translateY(-5px);
        }
        to {
          opacity: 1;
          transform: translateY(0);
        }
      }
      .animate-fade-in {
        animation: fadeIn 0.3s ease-out forwards;
      }

      @keyframes shimmer {
        100% {
          transform: translateX(100%);
        }
      }
      .animate-shimmer {
        animation: shimmer 1.5s infinite;
      }
    </style>
';
include 'includes/header.php';
?>
    <main>
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
            <!-- Products will be loaded here by JavaScript -->
          </div>
        </div>
      </section>
    </main>
<?php include 'includes/footer.php'; ?>