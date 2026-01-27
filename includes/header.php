<?php
/**
 * Mario Bramy - Wspólny Header
 * 
 * Zmienne do ustawienia przed include:
 * - $page_title (opcjonalne)
 * - $page_description (opcjonalne)
 * - $page_keywords (opcjonalne)
 */

// Domyślne wartości
$page_title = $page_title ?? 'Mario Bramy - Nowoczesne Bramy i Ogrodzenia Aluminiowe';
$page_description = $page_description ?? 'Nowoczesne bramy i ogrodzenia aluminiowe na wymiar. Projekt, produkcja i montaż na terenie Małopolski i całej Polski.';
$page_keywords = $page_keywords ?? 'Brama, brama przesuwna, brama dwuskrzydłowa, ogrodzenia aluminiowe, automatyka do bram, Małopolska, Myślenice';
?>
<!doctype html>
<html lang="pl">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= htmlspecialchars($page_title) ?></title>
    <meta name="description" content="<?= htmlspecialchars($page_description) ?>" />
    <meta name="keywords" content="<?= htmlspecialchars($page_keywords) ?>" />
    <link rel="icon" type="image/png" href="assets/logo.jpg" />
    <link rel="stylesheet" href="assets/base.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="assets/style.css" />
    <?php if (isset($extra_css)): ?>
    <?= $extra_css ?>
    <?php endif; ?>
  </head>
  <body class="bg-white">
    <header
      id="global-header"
      class="code-section sticky top-0 z-50 bg-white border-b border-gray-200"
    >
      <div class="container mx-auto px-4 lg:px-8">
        <div class="flex items-center justify-between h-32">
          <a
            href="index.php"
            class="flex-shrink-0 transition-transform hover:scale-105 duration-300"
          >
            <img
              src="assets/logo.png"
              alt="Mario Bramy-Automatyka"
              class="h-50 py-2"
              data-logo
            />
          </a>

          <nav class="hidden lg:flex items-center space-x-8">
            <a
              href="nasze-projekty.php"
              class="text-[var(--dark-text-color)] hover:text-[var(--primary-color)] transition-colors duration-300 font-medium"
              >Przykładowe Realizacje</a
            >
            <a
              href="opinie.php"
              class="text-[var(--dark-text-color)] hover:text-[var(--primary-color)] transition-colors duration-300 font-medium"
              >Opinie klientów</a
            >
            <a
              href="sklep.php"
              class="text-[var(--dark-text-color)] hover:text-[var(--primary-color)] transition-colors duration-300 font-medium"
              >Sklep</a
            >
            <a
              href="kontakt.php"
              class="bg-[var(--primary-color)] text-[var(--primary-button-text-color)] px-6 py-3 rounded hover:bg-[var(--primary-button-hover-bg-color)] transition-all duration-300 font-semibold shadow-lg hover:shadow-xl hover:scale-105"
            >
              Kontakt
            </a>
            <a
              href="https://www.facebook.com/p/MARIO-bramy-automatyka-61581453314458/"
              class="w-10 h-10 border border-gray-300 rounded-lg flex items-center justify-center hover:bg-[var(--primary-color)] hover:border-[var(--primary-color)] transition-all duration-300 group"
            >
              <i
                class="fa-brands fa-facebook-f text-[var(--dark-text-color)] group-hover:text-white"
                aria-hidden="true"
              ></i>
            </a>
          </nav>
          <button
            id="hamburger-button"
            class="lg:hidden text-[var(--dark-text-color)] hover:text-[var(--primary-color)] transition-colors duration-300 z-50"
          >
            <i class="fa-solid fa-bars text-2xl"></i>
          </button>
        </div>
        <nav
          id="mobile-menu"
          class="hidden lg:hidden fixed inset-0 top-28 bg-[var(--dark-background-color)] bg-opacity-98 backdrop-blur-lg"
        ></nav>
      </div>
    </header>
    <main>
