<?php
/**
 * Mario Bramy - Strona "Wkrótce"
 */
require_once 'includes/config.php';
require_once 'includes/functions.php';

$page_title = 'Sklep wkrótce - Mario Bramy';
$page_description = 'Sklep internetowy Mario Bramy wkrótce dostępny. Nowoczesne bramy i ogrodzenia aluminiowe.';

// Specjalny extra CSS dla body flex
$extra_css = '<style>body { display: flex; flex-direction: column; min-height: 100vh; }</style>';

require_once 'includes/header.php';
?>

      <section class="flex-grow flex items-center justify-center py-20 lg:py-32 text-center">
        <div class="container mx-auto px-4 lg:px-8">
          <div class="max-w-2xl mx-auto">
            <div
              class="w-20 h-24 bg-[var(--primary-color)] rounded-3xl flex items-center justify-center mx-auto mb-8 shadow-lg"
            >
              <i
                class="fa-solid fa-shop text-3xl text-[var(--light-text-color)]"
              ></i>
            </div>
            <h1
              class="text-4xl md:text-6xl font-bold text-[var(--dark-text-color)] font-[var(--font-family-heading)] mb-6 mt-6"
            >
              Sklep wkrótce dostępny
            </h1>
            <p
              class="text-xl text-[var(--gray-text-color)] mb-10 leading-relaxed"
            >
              Pracujemy nad naszym nowym sklepem internetowym, aby ułatwić
              Państwu proces zamawiania i wyceny naszych produktów. Zapraszamy
              wkrótce!
            </p>
            <a
              href="index.php"
              class="inline-block px-8 py-4 bg-[var(--primary-color)] text-[var(--primary-button-text-color)] rounded-lg font-bold text-lg hover:bg-[var(--primary-button-hover-bg-color)] transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-105"
            >
              Powrót do strony głównej
            </a>
          </div>
        </div>
      </section>

<?php require_once 'includes/footer.php'; ?>
