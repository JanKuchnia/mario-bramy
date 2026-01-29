<?php require_once "config/init.php"; ?>
<!doctype html>
<html lang="pl">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kontakt - Mario Bramy</title>
    <link rel="canonical" href="<?= getCanonicalUrl() ?>" />
    <meta
      name="description"
      content="Skontaktuj się z nami w sprawie nowoczesnych bram i ogrodzeń aluminiowych. Oferujemy bezpłatną wycenę. Działamy na terenie Małopolski i całej Polski."
    />
    <meta
      name="keywords"
      content="kontakt, telefon, e-mail, adres, wycena, Mario Bramy, Małopolska, Myślenice, Dobczyce, Wiśniowa"
    />
    <link rel="icon" type="image/png" href="assets/logo.jpg" />
    <link rel="stylesheet" href="assets/base.css" />
    <link
      rel="preload"
      as="style"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
      onload="this.onload=null;this.rel='stylesheet'"
    />
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" /></noscript>
    <link rel="stylesheet" href="assets/style.css" />
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
              href="<?= getShopUrl() ?>"
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
        class="contact-section pt-32 lg:pt-48 pb-24 lg:pb-40 bg-white relative overflow-hidden"
      >
        <!-- Background decorative element -->
        <div
          class="absolute top-0 right-0 w-1/3 h-full bg-[var(--light-background-color)] opacity-50 -z-10 hidden lg:block"
        ></div>

        <div class="container mx-auto px-4 lg:px-8">
          <div class="grid lg:grid-cols-2 gap-16 lg:gap-32 items-start">
            <!-- Left Column: Text & Info -->
            <div class="space-y-24 lg:space-y-32">
              <div>
                <div
                  class="inline-block px-4 py-2 bg-[var(--primary-color)] bg-opacity-10 border border-[var(--primary-color)] rounded-full mb-6"
                >
                  <span
                    class="text-[var(--light-text-color)] font-semibold text-sm"
                    >Kontakt</span
                  >
                </div>
                <h1
                  class="text-4xl sm:text-5xl lg:text-7xl font-bold text-[var(--dark-text-color)] font-[var(--font-family-heading)] mb-6 leading-tight"
                >
                  Zostańmy w <br /><span class="text-[var(--primary-color)]"
                    >Kontakcie</span
                  >
                </h1>
                <p
                  class="text-xl text-[var(--gray-text-color)] leading-loose max-w-xl mb-6"
                >
                  Masz pytania dotyczące naszych bram lub ogrodzeń? Chcesz
                  otrzymać darmową wycenę? Jesteśmy do Twojej dyspozycji.
                </p>
              </div>

              <div class="space-y-20 lg:space-y-24">
                <!-- Address Item -->
                <div class="flex items-start gap-8 group">
                  <div
                    class="w-20 h-20 bg-white shadow-xl rounded-2xl flex items-center justify-center flex-shrink-0 group-hover:bg-[var(--primary-color)] transition-all duration-300 border border-gray-100 mb-2"
                  >
                    <i
                      class="fa-solid fa-location-dot text-[var(--primary-color)] text-3xl group-hover:text-white transition-colors"
                    ></i>
                  </div>
                  <div>
                    <h4
                      class="text-xs uppercase tracking-[0.2em] text-[var(--gray-text-color)] font-bold"
                    >
                      Nasza Lokalizacja
                    </h4>
                    <p
                      class="text-2xl lg:text-3xl text-[var(--dark-text-color)] font-medium font-[var(--font-family-heading)] leading-tight"
                    >
                      Wiśniowa 782, <br />32-412 Wiśniowa
                    </p>
                  </div>
                </div>

                <!-- Phone Item -->
                <div class="flex items-start gap-8 group">
                  <div
                    class="w-20 h-20 bg-white shadow-xl rounded-2xl flex items-center justify-center flex-shrink-0 group-hover:bg-[var(--primary-color)] transition-all duration-300 border border-gray-100 mb-2"
                  >
                    <i
                      class="fa-solid fa-phone text-[var(--primary-color)] text-3xl group-hover:text-white transition-colors"
                    ></i>
                  </div>
                  <div>
                    <h4
                      class="text-xs uppercase tracking-[0.2em] text-[var(--gray-text-color)] font-bold mt-4"
                    >
                      Zadzwoń do nas
                    </h4>
                    <a
                      href="tel:+48668197170"
                      class="text-2xl lg:text-3xl text-[var(--dark-text-color)] font-bold font-[var(--font-family-heading)] hover:text-[var(--primary-color)] transition-colors block"
                    >
                      +48 668 197 170
                    </a>
                  </div>
                </div>

                <!-- Email Item -->
                <div class="flex items-start gap-8 group">
                  <div
                    class="w-20 h-20 bg-white shadow-xl rounded-2xl flex items-center justify-center flex-shrink-0 group-hover:bg-[var(--primary-color)] transition-all duration-300 border border-gray-100"
                  >
                    <i
                      class="fa-solid fa-envelope text-[var(--primary-color)] text-3xl group-hover:text-white transition-colors"
                    ></i>
                  </div>
                  <div>
                    <h4
                      class="text-xs uppercase tracking-[0.2em] text-[var(--gray-text-color)] font-bold mt-4"
                    >
                      Napisz wiadomość
                    </h4>
                    <a
                      href="mailto:mario.bramy@gmail.com"
                      class="text-2xl lg:text-3xl text-[var(--dark-text-color)] font-bold font-[var(--font-family-heading)] hover:text-[var(--primary-color)] transition-colors break-all block mt-4"
                    >
                      mario.bramy@gmail.com
                    </a>
                  </div>
                </div>
              </div>
              <!-- Social Links -->
              <div class="pt-16 border-t border-gray-100 mt-2 mb-10">
                <p
                  class="text-[var(--gray-text-color)] mb-10 font-bold text-xl uppercase tracking-wider mt-8"
                >
                  Znajdź nas również na mediach społecznościowych:
                </p>
                <a
                  href="https://www.facebook.com/p/MARIO-bramy-automatyka-61581453314458/"
                  class="inline-flex items-center gap-4 py-8 text-black font-bold text-3xl group w-full sm:w-auto mt-6"
                >
                  <i
                    class="fa-brands fa-facebook-f text-4xl group-hover:rotate-12 transition-transform duration-300"
                  ></i>
                  <span>Odwiedź nasz Facebook</span>
                </a>
              </div>
            </div>

            <!-- Right Column: Map -->
            <div class="relative lg:sticky lg:top-32 pb-12">
              <div
                class="rounded-[3rem] overflow-hidden shadow-2xl border-[8px] border-white h-96 lg:h-[1000px] transform lg:-rotate-1 hover:rotate-0 transition-transform duration-700"
              >
                <iframe
                  src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2572.824524245624!2d20.0833333!3d49.8166667!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4716161616161617%3A0x1616161616161616!2zV2nFm25pb3dhIDc4MiwgMzItNDEyIFdpxZtuaW93YSwgUG9sc2th!5e0!3m2!1spl!2spl!4v1700000000000!5m2!1spl!2spl"
                  width="100%"
                  height="100%"
                  style="border: 0"
                  allowfullscreen=""
                  loading="lazy"
                  referrerpolicy="no-referrer-when-downgrade"
                ></iframe>
              </div>
            </div>
          </div>
        </div>
      </section>
    </main>

    <footer
      id="global-footer"
      class="code-section mt-20 bg-[var(--dark-background-color)] text-[var(--light-text-color)] border-t border-[var(--dark-border-color)]"
    >
      <div class="container mx-auto px-4 lg:px-8 py-16">
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-12">
          <div class="space-y-6">
            <div class="inline-block bg-white p-4 rounded-xl shadow-md">
              <a href="index.php" class="inline-block">
                <img
                  src="assets/logo.png"
                  alt="Mario Bramy-Automatyka"
                  class="h-36"
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
                  href="index.html#oferta"
                  class="text-[var(--gray-text-color)] hover:text-[var(--primary-color)] transition-colors duration-300 inline-flex items-center gap-2 group"
                >
                  <i
                    class="fa-solid fa-chevron-right text-xs group-hover:translate-x-1 transition-transform duration-300"
                    aria-hidden="true"
                  ></i>
                  <span>Oferta</span>
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
                  href="opinie.php"
                  class="text-[var(--gray-text-color)] hover:text-[var(--primary-color)] transition-colors duration-300 inline-flex items-center gap-2 group"
                >
                  <i
                    class="fa-solid fa-chevron-right text-xs group-hover:translate-x-1 transition-transform duration-300"
                    aria-hidden="true"
                  ></i>
                  <span>Opinie klientów</span>
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
              Przykładowe Realizacje
            </h4>
            <ul class="space-y-3">
              <li>
                <a
                  href="nasze-projekty.html?category=bramy-przesuwne-aluminiowe"
                  class="text-[var(--gray-text-color)] hover:text-[var(--primary-color)] transition-colors duration-300 inline-flex items-center gap-2 group"
                >
                  <i
                    class="fa-solid fa-chevron-right text-xs group-hover:translate-x-1 transition-transform duration-300"
                    aria-hidden="true"
                  ></i>
                  <span>Bramy Przesuwne</span>
                </a>
              </li>
              <li>
                <a
                  href="nasze-projekty.html?category=bramy-dwuskrzydlowe"
                  class="text-[var(--gray-text-color)] hover:text-[var(--primary-color)] transition-colors duration-300 inline-flex items-center gap-2 group"
                >
                  <i
                    class="fa-solid fa-chevron-right text-xs group-hover:translate-x-1 transition-transform duration-300"
                    aria-hidden="true"
                  ></i>
                  <span>Bramy Dwuskrzydłowe</span>
                </a>
              </li>
              <li>
                <a
                  href="nasze-projekty.html?category=przesla-ogrodzeniowe-aluminiowe"
                  class="text-[var(--gray-text-color)] hover:text-[var(--primary-color)] transition-colors duration-300 inline-flex items-center gap-2 group"
                >
                  <i
                    class="fa-solid fa-chevron-right text-xs group-hover:translate-x-1 transition-transform duration-300"
                    aria-hidden="true"
                  ></i>
                  <span>Ogrodzenia i Przęsła</span>
                </a>
              </li>
              <li>
                <a
                  href="nasze-projekty.html?category=balustrady"
                  class="text-[var(--gray-text-color)] hover:text-[var(--primary-color)] transition-colors duration-300 inline-flex items-center gap-2 group"
                >
                  <i
                    class="fa-solid fa-chevron-right text-xs group-hover:translate-x-1 transition-transform duration-300"
                    aria-hidden="true"
                  ></i>
                  <span>Balustrady</span>
                </a>
              </li>
              <li>
                <a
                  href="nasze-projekty.html?category=automatyka"
                  class="text-[var(--gray-text-color)] hover:text-[var(--primary-color)] transition-colors duration-300 inline-flex items-center gap-2 group"
                >
                  <i
                    class="fa-solid fa-chevron-right text-xs group-hover:translate-x-1 transition-transform duration-300"
                    aria-hidden="true"
                  ></i>
                  <span>Automatyka do bram</span>
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
  </body>
</html>
