<?php
/**
 * Mario Bramy - Strona opinii
 */
require_once 'includes/config.php';
require_once 'includes/functions.php';

$page_title = 'Opinie Klientów - Mario Bramy';
$page_description = 'Zobacz opinie naszych zadowolonych klientów o bramach i ogrodzeniach aluminiowych Mario Bramy.';

require_once 'includes/header.php';
?>

      <section
        class="mt-8 code-section pt-32 lg:pt-20 pb-20 lg:pb-32 bg-white relative overflow-hidden"
        id="s23x09"
      >
        <div
          class="absolute top-1/4 right-0 w-96 h-96 bg-[var(--primary-color)] rounded-full opacity-5 blur-3xl"
        ></div>
        <div
          class="absolute bottom-1/4 left-0 w-96 h-96 bg-[var(--accent-color)] rounded-full opacity-5 blur-3xl"
        ></div>
        <div class="container mx-auto px-4 lg:px-8 relative z-10">
          <div class="text-center max-w-3xl mx-auto mb-16">
            <div
              class="inline-block px-4 py-2 bg-[var(--primary-color)] bg-opacity-20 border border-[var(--primary-color)] rounded-full mb-4"
            >
              <span class="text-[var(--light-text-color)] font-semibold text-sm"
                >Opinie</span
              >
            </div>
            <h2
              class="text-3xl sm:text-4xl lg:text-5xl font-bold text-[var(--dark-text-color)] font-[var(--font-family-heading)] mb-6"
            >
              Co Mówią Nasi Klienci<br />
              <span class="text-[var(--primary-color)]">Google Reviews</span>
            </h2>
            <p class="text-lg text-[var(--gray-text-color)]">
              Poniżej znajdziesz najnowsze opinie o naszych produktach i
              usługach, pobierane bezpośrednio z Google.
            </p>
          </div>

          <div
            id="google-reviews-container"
            class="max-w-4xl mx-auto space-y-4"
          >
            <!-- Example Review Start -->
            <div
              class="bg-white border border-gray-200 p-6 rounded-xl shadow-sm"
            >
              <div class="flex items-start">
                <img
                  class="w-12 h-12 rounded-full mr-4"
                  src="https://lh3.googleusercontent.com/a/ACg8ocJ-12345ABCDE"
                  alt="Jan Kowalski"
                />
                <div class="flex-1">
                  <div class="flex items-center justify-between">
                    <div>
                      <h4
                        class="font-bold text-lg text-[var(--dark-text-color)]"
                      >
                        Jan Kowalski
                      </h4>
                      <p class="text-sm text-[var(--gray-text-color)]">
                        2 tygodnie temu
                      </p>
                    </div>
                    <i class="fab fa-google text-2xl text-gray-400"></i>
                  </div>
                  <div class="flex items-center my-2">
                    <i class="fas fa-star text-yellow-400"></i>
                    <i class="fas fa-star text-yellow-400"></i>
                    <i class="fas fa-star text-yellow-400"></i>
                    <i class="fas fa-star text-yellow-400"></i>
                    <i class="fas fa-star text-yellow-400"></i>
                  </div>
                  <p class="text-[var(--dark-text-color)] leading-relaxed">
                    Pełen profesjonalizm! Brama aluminiowa, którą zamówiłem,
                    przerosła moje oczekiwania. Solidna konstrukcja, piękny
                    wygląd i co najważniejsze - wszystko wykonane w terminie.
                    Montaż przebiegł sprawnie i czysto. Polecam!
                  </p>
                </div>
              </div>
            </div>
            <!-- Example Review End -->
          </div>

          <div class="mt-16 text-center">
            <p class="text-[var(--gray-text-color)] text-lg mb-6">
              Chcesz podzielić się swoją opinią?
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
              <a
                href="https://share.google/Zu1xRUtcSNc6JBLRI"
                target="_blank"
                class="px-8 py-4 bg-[var(--primary-color)] text-[var(--primary-button-text-color)] rounded-lg font-bold hover:bg-[var(--primary-button-hover-bg-color)] transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-105 inline-flex items-center justify-center gap-3"
              >
                <i class="fa-solid fa-star" aria-hidden="true"></i>
                <span>Napisz Opinię na Google</span>
              </a>
              <a
                href="kontakt.php"
                class="px-8 py-4 bg-transparent border-2 border-[var(--light-border-color)] text-[var(--dark-text-color)] rounded-lg font-bold hover:bg-[var(--dark-text-color)] hover:text-white transition-all duration-300 inline-flex items-center justify-center gap-3"
              >
                <i class="fa-solid fa-phone" aria-hidden="true"></i>
                <span>Skontaktuj się z nami</span>
              </a>
            </div>
          </div>
        </div>
      </section>

<?php require_once 'includes/footer.php'; ?>
