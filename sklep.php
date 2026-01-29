<?php require_once "config/init.php"; ?>
<!doctype html>
<html lang="pl">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sklep - Mario Bramy</title>
    <link rel="canonical" href="<?= getCanonicalUrl() ?>" />
    <meta
      name="description"
      content="Sklep internetowy Mario Bramy. Sprawdź naszą ofertę bram, ogrodzeń i automatyki. Zamów online lub telefonicznie."
    />
    <link rel="icon" type="image/png" href="assets/logo.jpg" />
    <link rel="stylesheet" href="assets/base.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
    />
    <link rel="stylesheet" href="assets/style.css" />
  </head>
  <body class="bg-gray-50 font-[var(--font-family-body)]">
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
              href="<?= getShopUrl() ?>"
              class="text-[var(--primary-color)] font-bold transition-colors duration-300"
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
              aria-label="Facebook"
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
            id="products-grid"
            class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8"
          >
            <!-- Products will be loaded here by JavaScript -->
             <div class="col-span-full py-12 text-center">
                    <i class="fa-solid fa-spinner fa-spin text-4xl text-[var(--primary-color)]"></i>
             </div>
          </div>
        </div>
      </section>
    </main>

    <footer
      id="global-footer"
      class="code-section mt-auto bg-[var(--dark-background-color)] text-[var(--light-text-color)] border-t border-[var(--dark-border-color)]"
    >
      <div class="container mx-auto px-4 lg:px-8 py-16">
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-12">
          <div class="space-y-6">
            <div class="inline-block bg-white p-4 rounded-xl shadow-md">
              <a href="index.php" class="inline-block">
                <img
                  src="assets/logo.png"
                  alt="Mario Bramy-Automatyka"
                  class="h-24"
                  data-logo
                />
              </a>
            </div>
            <p class="text-[var(--gray-text-color)] leading-relaxed">
              Nowoczesne bramy i ogrodzenia aluminiowe. Produkcja, montaż i
              serwis.
            </p>
            <div class="flex gap-4">
              <a
                href="https://www.facebook.com/p/MARIO-bramy-automatyka-61581453314458/"
                aria-label="Facebook"
                class="w-10 h-10 bg-[var(--dark-background-color)] border border-[var(--dark-border-color)] rounded-lg flex items-center justify-center hover:bg-[var(--primary-color)] hover:border-[var(--primary-color)] transition-all duration-300 group"
              >
                <i
                  class="fa-brands fa-facebook-f text-[var(--light-text-color)] group-hover:text-white"
                  aria-hidden="true"
                ></i>
              </a>
            </div>
          </div>

          <div>
            <h4
              class="text-[var(--light-text-color)] font-bold text-lg mb-6 font-[var(--font-family-heading)]"
            >
              Szybkie Linki
            </h4>
            <ul class="space-y-3">
              <li>
                <a
                  href="index.php"
                  class="text-[var(--gray-text-color)] hover:text-[var(--primary-color)] transition-colors duration-300 inline-flex items-center gap-2 group"
                >
                  <i
                    class="fa-solid fa-chevron-right text-xs group-hover:translate-x-1 transition-transform duration-300"
                    aria-hidden="true"
                  ></i>
                  <span>Strona główna</span>
                </a>
              </li>
              <li>
                <a
                  href="nasze-projekty.php"
                  class="text-[var(--gray-text-color)] hover:text-[var(--primary-color)] transition-colors duration-300 inline-flex items-center gap-2 group"
                >
                  <i
                    class="fa-solid fa-chevron-right text-xs group-hover:translate-x-1 transition-transform duration-300"
                    aria-hidden="true"
                  ></i>
                  <span>Przykładowe Realizacje</span>
                </a>
              </li>
              <li>
                <a
                  href="<?= getShopUrl() ?>"
                  class="text-[var(--gray-text-color)] hover:text-[var(--primary-color)] transition-colors duration-300 inline-flex items-center gap-2 group"
                >
                  <i
                    class="fa-solid fa-chevron-right text-xs group-hover:translate-x-1 transition-transform duration-300"
                    aria-hidden="true"
                  ></i>
                  <span>Sklep</span>
                </a>
              </li>
              <li>
                <a
                  href="kontakt.php"
                  class="text-[var(--gray-text-color)] hover:text-[var(--primary-color)] transition-colors duration-300 inline-flex items-center gap-2 group"
                >
                  <i
                    class="fa-solid fa-chevron-right text-xs group-hover:translate-x-1 transition-transform duration-300"
                    aria-hidden="true"
                  ></i>
                  <span>Kontakt</span>
                </a>
              </li>
            </ul>
          </div>

          <div>
            <h4
              class="text-[var(--light-text-color)] font-bold text-lg mb-6 font-[var(--font-family-heading)]"
            >
              Kontakt
            </h4>
            <ul class="space-y-6">
              <li class="flex items-start gap-4">
                <div
                  class="w-10 h-10 bg-[var(--primary-color)] bg-opacity-20 rounded-lg flex items-center justify-center flex-shrink-0"
                >
                  <i
                    class="fa-solid fa-location-dot text-[var(--light-text-color)]"
                    aria-hidden="true"
                  ></i>
                </div>
                <div>
                  <div
                    class="text-[var(--gray-text-color)] text-xs uppercase tracking-wider mb-1"
                  >
                    Adres
                  </div>
                  <div
                    class="text-[var(--light-text-color)] font-medium leading-relaxed"
                  >
                    Wiśniowa 782<br />
                    32-412 Wiśniowa
                  </div>
                </div>
              </li>
              <li class="flex items-start gap-4">
                <div
                  class="w-10 h-10 bg-[var(--primary-color)] bg-opacity-20 rounded-lg flex items-center justify-center flex-shrink-0"
                >
                  <i
                    class="fa-solid fa-phone text-[var(--light-text-color)]"
                    aria-hidden="true"
                  ></i>
                </div>
                <div>
                  <div
                    class="text-[var(--gray-text-color)] text-xs uppercase tracking-wider mb-1"
                  >
                    Telefon
                  </div>
                  <a
                    href="tel:+48668197170"
                    class="text-[var(--light-text-color)] font-medium hover:text-[var(--primary-color)] transition-colors duration-300"
                    >+48 668 197 170</a
                  >
                </div>
              </li>
              <li class="flex items-start gap-3">
                <div
                  class="w-10 h-10 bg-[var(--primary-color)] bg-opacity-20 rounded-lg flex items-center justify-center flex-shrink-0"
                >
                  <i
                    class="fa-solid fa-envelope text-[var(--light-text-color)]"
                    aria-hidden="true"
                  ></i>
                </div>
                <div>
                  <div
                    class="text-[var(--gray-text-color)] text-xs uppercase tracking-wider mb-1"
                  >
                    Email
                  </div>
                  <a
                    href="mailto:mario.bramy@gmail.com"
                    class="text-[var(--light-text-color)] font-medium hover:text-[var(--primary-color)] transition-colors duration-300"
                    >mario.bramy@gmail.com</a
                  >
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>

      <div class="border-t border-[var(--dark-border-color)]">
        <div class="container mx-auto px-4 lg:px-8 py-6">
          <div
            class="flex flex-col md:flex-row justify-between items-center gap-4"
          >
            <p
              class="text-[var(--gray-text-color)] text-sm text-center md:text-left"
            >
              © 2025 Mario Bramy-Automatyka. Wszelkie prawa zastrzeżone.
            </p>
            <div class="flex gap-6">
              <a
                href="#"
                class="text-[var(--gray-text-color)] hover:text-[var(--primary-color)] transition-colors duration-300 text-sm"
                >Polityka Prywatności</a
              >
              <a
                href="#"
                class="text-[var(--gray-text-color)] hover:text-[var(--primary-color)] transition-colors duration-300 text-sm"
                >Regulamin</a
              >
            </div>
          </div>
        </div>
      </div>
    </footer>
    <script src="assets/mobile_menu.js"></script>
    <script src="assets/shop.js"></script>
  </body>
</html>
