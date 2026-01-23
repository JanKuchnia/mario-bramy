<?php
$pageTitle = "Kontakt";
$pageDesc = "Skontaktuj się z nami w sprawie nowoczesnych bram i ogrodzeń aluminiowych. Oferujemy bezpłatną wycenę. Działamy na terenie Małopolski i całej Polski.";
include 'includes/header.php';
?>
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
                    class="text-[var(--primary-color)] font-semibold text-sm uppercase tracking-widest"
                    >Kontakt</span
                  >
                </div>
                <h1
                  class="text-4xl lg:text-6xl font-bold text-[var(--dark-text-color)] font-[var(--font-family-heading)] mb-8 leading-tight"
                >
                  Zacznijmy Twój <br />
                  <span class="text-[var(--primary-color)]">Projekt</span>
                </h1>
                <p
                  class="text-xl text-[var(--gray-text-color)] leading-relaxed max-w-lg"
                >
                  Masz pytania dotyczące naszych produktów? Chcesz otrzymać
                  bezpłatną wycenę? Jesteśmy do Twojej dyspozycji.
                </p>
              </div>

              <div class="space-y-12">
                <div class="flex items-start gap-8 group">
                  <div
                    class="w-16 h-16 bg-white border-2 border-[var(--primary-color)] rounded-2xl flex items-center justify-center flex-shrink-0 group-hover:bg-[var(--primary-color)] transition-all duration-300 shadow-lg group-hover:shadow-[var(--primary-color)] group-hover:shadow-opacity-30"
                  >
                    <i
                      class="fa-solid fa-phone text-2xl text-[var(--primary-color)] group-hover:text-white transition-colors duration-300"
                    ></i>
                  </div>
                  <div>
                    <h3
                      class="text-sm font-bold text-[var(--gray-text-color)] uppercase tracking-widest mb-2"
                    >
                      Zadzwoń do nas
                    </h3>
                    <a
                      href="tel:+48668197170"
                      class="text-2xl font-bold text-[var(--dark-text-color)] hover:text-[var(--primary-color)] transition-colors"
                      >+48 668 197 170</a
                    >
                    <p class="text-sm text-[var(--gray-text-color)] mt-1">
                      Pon - Pt: 8:00 - 18:00
                    </p>
                  </div>
                </div>

                <div class="flex items-start gap-8 group">
                  <div
                    class="w-16 h-16 bg-white border-2 border-[var(--primary-color)] rounded-2xl flex items-center justify-center flex-shrink-0 group-hover:bg-[var(--primary-color)] transition-all duration-300 shadow-lg group-hover:shadow-[var(--primary-color)] group-hover:shadow-opacity-30"
                  >
                    <i
                      class="fa-solid fa-envelope text-2xl text-[var(--primary-color)] group-hover:text-white transition-colors duration-300"
                    ></i>
                  </div>
                  <div>
                    <h3
                      class="text-sm font-bold text-[var(--gray-text-color)] uppercase tracking-widest mb-2"
                    >
                      Napisz e-mail
                    </h3>
                    <a
                      href="mailto:mario.bramy@gmail.com"
                      class="text-2xl font-bold text-[var(--dark-text-color)] hover:text-[var(--primary-color)] transition-colors"
                      >mario.bramy@gmail.com</a
                    >
                    <p class="text-sm text-[var(--gray-text-color)] mt-1">
                      Odpowiadamy w ciągu 24h
                    </p>
                  </div>
                </div>

                <div class="flex items-start gap-8 group">
                  <div
                    class="w-16 h-16 bg-white border-2 border-[var(--primary-color)] rounded-2xl flex items-center justify-center flex-shrink-0 group-hover:bg-[var(--primary-color)] transition-all duration-300 shadow-lg group-hover:shadow-[var(--primary-color)] group-hover:shadow-opacity-30"
                  >
                    <i
                      class="fa-solid fa-location-dot text-2xl text-[var(--primary-color)] group-hover:text-white transition-colors duration-300"
                    ></i>
                  </div>
                  <div>
                    <h3
                      class="text-sm font-bold text-[var(--gray-text-color)] uppercase tracking-widest mb-2"
                    >
                      Nasza lokalizacja
                    </h3>
                    <p class="text-2xl font-bold text-[var(--dark-text-color)]">
                      Wiśniowa 782
                    </p>
                    <p class="text-lg text-[var(--gray-text-color)]">
                      32-412 Wiśniowa, Małopolska
                    </p>
                  </div>
                </div>
              </div>
            </div>

            <!-- Right Column: Form -->
            <div class="relative lg:mt-12">
              <div
                class="absolute -inset-4 bg-[var(--primary-color)] bg-opacity-5 rounded-[2rem] -z-10 blur-2xl"
              ></div>
              <div
                class="bg-white p-8 lg:p-12 rounded-3xl shadow-2xl border border-gray-100"
              >
                <form action="#" class="space-y-8">
                  <div class="grid sm:grid-cols-2 gap-8">
                    <div class="space-y-2">
                      <label
                        for="name"
                        class="text-sm font-bold text-[var(--dark-text-color)] uppercase tracking-wider"
                        >Imię i Nazwisko</label
                      >
                      <input
                        type="text"
                        id="name"
                        class="w-full px-6 py-4 bg-gray-50 border-2 border-transparent focus:border-[var(--primary-color)] focus:bg-white rounded-xl transition-all outline-none"
                        placeholder="np. Jan Kowalski"
                        required
                      />
                    </div>
                    <div class="space-y-2">
                      <label
                        for="email"
                        class="text-sm font-bold text-[var(--dark-text-color)] uppercase tracking-wider"
                        >Adres E-mail</label
                      >
                      <input
                        type="email"
                        id="email"
                        class="w-full px-6 py-4 bg-gray-50 border-2 border-transparent focus:border-[var(--primary-color)] focus:bg-white rounded-xl transition-all outline-none"
                        placeholder="jan@kowalski.pl"
                        required
                      />
                    </div>
                  </div>

                  <div class="space-y-2">
                    <label
                      for="phone"
                      class="text-sm font-bold text-[var(--dark-text-color)] uppercase tracking-wider"
                      >Numer Telefonu</label
                    >
                    <input
                      type="tel"
                      id="phone"
                      class="w-full px-6 py-4 bg-gray-50 border-2 border-transparent focus:border-[var(--primary-color)] focus:bg-white rounded-xl transition-all outline-none"
                      placeholder="+48 000 000 000"
                    />
                  </div>

                  <div class="space-y-2">
                    <label
                      for="subject"
                      class="text-sm font-bold text-[var(--dark-text-color)] uppercase tracking-wider"
                      >Temat Wiadomości</label
                    >
                    <select
                      id="subject"
                      class="w-full px-6 py-4 bg-gray-50 border-2 border-transparent focus:border-[var(--primary-color)] focus:bg-white rounded-xl transition-all outline-none appearance-none"
                    >
                      <option value="quote">Bezpłatna wycena</option>
                      <option value="service">Serwis i montaż</option>
                      <option value="product">Pytanie o produkt</option>
                      <option value="other">Inny temat</option>
                    </select>
                  </div>

                  <div class="space-y-2">
                    <label
                      for="message"
                      class="text-sm font-bold text-[var(--dark-text-color)] uppercase tracking-wider"
                      >Twoja Wiadomość</label
                    >
                    <textarea
                      id="message"
                      rows="5"
                      class="w-full px-6 py-4 bg-gray-50 border-2 border-transparent focus:border-[var(--primary-color)] focus:bg-white rounded-xl transition-all outline-none resize-none"
                      placeholder="Opisz nam swój projekt..."
                      required
                    ></textarea>
                  </div>

                  <button
                    type="submit"
                    class="group relative w-full py-5 bg-[var(--primary-color)] text-white rounded-xl font-bold text-lg shadow-xl hover:shadow-[var(--primary-color)] hover:shadow-opacity-30 transition-all duration-300 overflow-hidden"
                  >
                    <span class="relative z-10 flex items-center justify-center gap-3">
                      Wyślij Wiadomość
                      <i class="fa-solid fa-paper-plane group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform"></i>
                    </span>
                    <div
                      class="absolute inset-0 bg-[var(--primary-button-hover-bg-color)] transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"
                    ></div>
                  </button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </section>
    </main>
<?php include 'includes/footer.php'; ?>