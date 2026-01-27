<?php
/**
 * Mario Bramy - Strona kontaktowa
 */
require_once 'includes/config.php';
require_once 'includes/functions.php';

$page_title = 'Kontakt - Mario Bramy';
$page_description = 'Skontaktuj się z nami w sprawie nowoczesnych bram i ogrodzeń aluminiowych. Oferujemy bezpłatną wycenę. Działamy na terenie Małopolski i całej Polski.';

require_once 'includes/header.php';
?>

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

<?php require_once 'includes/footer.php'; ?>
