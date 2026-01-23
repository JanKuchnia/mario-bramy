<?php
$pageTitle = "Nowoczesne Bramy i Ogrodzenia Aluminiowe";
$pageDesc = "Projekt, produkcja i montaż nowoczesnych bram i ogrodzeń aluminiowych na wymiar. Poznaj nasze realizacje w Małopolsce i całej Polsce.";
$extraStyles = '    <style>
      /* Hero Section Responsive Height */
      .hero-section-custom {
        height: auto;
        min-height: 550px; /* Reduced from 100vh to show wider image crop on mobile */
        padding-bottom: 4rem;
        padding-top: 3rem;
      }
      @media (min-width: 1024px) {
        .hero-section-custom {
          height: 100vh;
          min-height: 100vh;
          padding-bottom: 0;
        }
      }
    </style>
';

include 'includes/header.php';
?>
    <main>
      <section
        class="code-section hero-section-custom relative flex overflow-hidden bg-[var(--dark-background-color)]"
        id="s6lx8b"
      >
        <div
          class="slideshow-container absolute inset-0 z-0 after:content-[''] after:absolute after:inset-0 after:bg-black/40 after:z-[1]"
        >
          <div
            class="slide"
            style="
              background-image: url(&quot;assets/portfolio/bramy-przesuwne-aluminiowe/2.jpg&quot;);
            "
          ></div>
          <div
            class="slide"
            style="
              background-image: url(&quot;assets/portfolio/bramy-dwuskrzydlowe/4.jpg&quot;);
            "
          ></div>
          <div
            class="slide"
            style="
              background-image: url(&quot;assets/portfolio/przesla-ogrodzeniowe-aluminiowe/1.jpg&quot;);
            "
          ></div>
          <div
            class="slide"
            style="
              background-image: url(&quot;assets/portfolio/barierki/1.jpg&quot;);
            "
          ></div>
          <div
            class="slide"
            style="
              background-image: url(&quot;assets/portfolio/bramy-przesuwne-aluminiowe/4.jpeg&quot;);
            "
          ></div>
          <div
            class="slide"
            style="
              background-image: url(&quot;assets/portfolio/bramy-przesuwne-aluminiowe/5.jpeg&quot;);
            "
          ></div>
        </div>

        <div
          class="absolute top-20 right-10 w-32 h-32 bg-[var(--primary-color)] rounded-full opacity-10 blur-3xl animate-float"
          style="animation: float 8s ease-in-out infinite"
        ></div>
        <div
          class="absolute bottom-20 left-10 w-48 h-48 bg-[var(--accent-color)] rounded-full opacity-10 blur-3xl animate-float"
          style="animation: float 10s ease-in-out infinite 2s"
        ></div>

        <div class="container mx-auto px-4 lg:px-8 relative z-10 pt-4 sm:pt-0">
          <div class="grid lg:grid-cols-2 gap-2 lg:gap-12 items-center">
            <!-- Left Column: Content & CTA -->
            <div class="space-y-4 text-center lg:text-left order-1 lg:-mt-20">
              <div class="space-y-4">
                <h1
                  class="text-3xl sm:text-5xl lg:text-7xl font-bold font-[var(--font-family-heading)] leading-tight"
                  style="
                    color: #cf1928;
                    text-shadow: 3px 3px 6px rgba(0, 0, 0, 0.8);
                  "
                >
                  Nowoczesne Bramy <br />i Ogrodzenia Aluminiowe
                </h1>
                <p
                  class="text-lg lg:text-2xl text-white leading-relaxed"
                  style="text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5)"
                >
                  Produkcja i montaż nowoczesnych bram i ogrodzeń
                  aluminiowych.
                </p>
              </div>

              <div
                class="flex flex-row gap-4 sm:gap-6 items-center justify-center lg:justify-start flex-wrap w-full"
              >
                <a
                  href="javascript:void(0)"
                  class="group relative w-full sm:w-80 px-6 py-3 sm:py-4 bg-[var(--primary-color)] text-[var(--primary-button-text-color)] rounded-lg font-bold text-base sm:text-lg shadow-xl hover:shadow-[var(--primary-color)] hover:scale-105 transition-all duration-300 overflow-hidden flex items-center justify-center gap-2"
                >
                  <span class="relative z-10">Szybka Wycena</span>
                  <i
                    class="fa-solid fa-arrow-right relative z-10 group-hover:translate-x-1 transition-transform duration-300"
                    aria-hidden="true"
                  ></i>
                  <div
                    class="absolute inset-0 bg-[var(--primary-button-hover-bg-color)] transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"
                  ></div>
                </a>
                <a
                  href="tel:+48668197170"
                  class="phone-button group w-full sm:w-80 py-3 sm:py-4 px-6 bg-white border-2 border-white text-[var(--dark-text-color)] rounded-lg font-bold text-base sm:text-lg hover:bg-[var(--light-background-color)] transition-all duration-300 flex items-center justify-center gap-2 shadow-xl"
                >
                  <i class="fa-solid fa-phone-volume" aria-hidden="true"></i>
                  <div class="text-container">
                    <span class="default-text">Zadzwoń Teraz</span>
                    <span class="hover-text">668 197 170</span>
                  </div>
                </a>
              </div>
            </div>

            <!-- Right Column: Hero Buttons -->
            <div id="hero-buttons-wrapper" class="w-full flex flex-col items-center lg:items-end order-2">
              <!-- Main Menu (Level 1) -->
              <div
                id="hero-main-menu"
                class="flex flex-col gap-3 w-full items-center lg:items-end transition-all duration-300 transform"
              >
                <!-- Bramy wjazdowe i przęsła (Trigger) -->
                <button
                  onclick="toggleHeroMenu('sub')"
                  class="w-full sm:w-80 h-auto sm:h-20 py-3 px-6 bg-black/60 sm:bg-black/70 backdrop-blur-sm border border-white/20 rounded-xl text-white font-bold text-lg sm:text-xl text-center hover:bg-[#cf1928] transition-all duration-300 shadow-xl hover:scale-105 flex items-center justify-center gap-4 group"
                >
                  <img
                    src="ikony/brama_podwojna.png"
                    alt="Bramy"
                    class="w-8 h-8 object-contain group-hover:rotate-6 transition-transform"
                  />
                  <span class="sm:hidden text-shadow-sm">Bramy i Przęsła</span>
                  <span class="hidden sm:inline whitespace-nowrap text-shadow-sm">Bramy Wjazdowe i Przęsła</span>
                </button>

                <!-- Automatyka -->
                <a
                  href="nasze-projekty.php?category=automatyka"
                  class="w-full sm:w-80 h-auto sm:h-20 py-3 px-6 bg-black/60 sm:bg-black/70 backdrop-blur-sm border border-white/20 rounded-xl text-white font-bold text-lg sm:text-xl text-center hover:bg-[#cf1928] transition-all duration-300 shadow-xl hover:scale-105 flex items-center justify-center gap-4 group"
                >
                  <img
                    src="ikony/automatyka.png"
                    alt="Automatyka"
                    class="w-8 h-8 object-contain group-hover:scale-110 transition-transform"
                  />
                  <span class="whitespace-nowrap text-shadow-sm">Automatyka</span>
                </a>

                <!-- Balustrady -->
                <a
                  href="nasze-projekty.php?category=balustrady"
                  class="w-full sm:w-80 h-auto sm:h-20 py-3 px-6 bg-black/60 sm:bg-black/70 backdrop-blur-sm border border-white/20 rounded-xl text-white font-bold text-lg sm:text-xl text-center hover:bg-[#cf1928] transition-all duration-300 shadow-xl hover:scale-105 flex items-center justify-center gap-4 group"
                >
                  <img
                    src="ikony/balustrada.png"
                    alt="Balustrady"
                    class="w-8 h-8 object-contain group-hover:-translate-y-1 transition-transform"
                  />
                  <span class="whitespace-nowrap text-shadow-sm">Balustrady</span>
                </a>
              </div>

              <!-- Sub Menu (Level 2: Hidden by default) -->
              <div
                id="hero-sub-menu"
                class="hidden opacity-0 translate-y-4 flex flex-col gap-3 w-full items-center lg:items-end transition-all duration-300 transform"
              >
                <!-- Wróć (Back to Main) -->
                <button
                  onclick="toggleHeroMenu('main')"
                  class="w-full sm:w-80 h-auto sm:h-20 py-3 px-6 bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl text-white font-bold text-lg sm:text-xl text-center hover:bg-white/20 transition-all duration-300 shadow-xl flex items-center justify-center gap-4 group"
                >
                  <i
                    class="fa-solid fa-chevron-left group-hover:-translate-x-1 transition-transform text-[var(--primary-color)]"
                  ></i>
                  <span class="whitespace-nowrap text-shadow-sm">Wróć</span>
                </button>

                <!-- Bramy Wjazdowe (Trigger for Level 3) -->
                <button
                  onclick="toggleHeroMenu('sub-sub')"
                  class="w-full sm:w-80 h-auto sm:h-20 py-3 px-6 bg-black/60 sm:bg-black/70 backdrop-blur-sm border border-white/20 rounded-xl text-white font-bold text-lg sm:text-xl text-center hover:bg-[#cf1928] transition-all duration-300 shadow-xl hover:scale-105 flex items-center justify-center gap-4 group"
                >
                  <img
                    src="ikony/brama_podwojna.png"
                    alt="Bramy"
                    class="w-8 h-8 object-contain"
                  />
                  <span class="sm:hidden text-shadow-sm">Bramy Wjazdowe</span>
                  <span class="hidden sm:inline whitespace-nowrap text-shadow-sm">Bramy Wjazdowe</span>
                  <i class="fa-solid fa-chevron-right text-sm ml-2 group-hover:translate-x-1 transition-transform text-[var(--primary-color)]"></i>
                </button>

                <!-- Przęsła -->
                <a
                  href="nasze-projekty.php?category=przesla-ogrodzeniowe-aluminiowe"
                  class="w-full sm:w-80 h-auto sm:h-20 py-3 px-6 bg-black/60 sm:bg-black/70 backdrop-blur-sm border border-white/20 rounded-xl text-white font-bold text-lg sm:text-xl text-center hover:bg-[#cf1928] transition-all duration-300 shadow-xl hover:scale-105 flex items-center justify-center gap-4 group"
                >
                  <img
                    src="ikony/balustrada.png"
                    alt="Przęsła"
                    class="w-8 h-8 object-contain group-hover:-translate-y-1 transition-transform"
                  />
                  <span class="whitespace-nowrap text-shadow-sm">Przęsła</span>
                </a>
              </div>

              <!-- Sub-Sub Menu (Level 3: Hidden by default) -->
              <div
                id="hero-sub-sub-menu"
                class="hidden opacity-0 translate-y-4 flex flex-col gap-3 w-full items-center lg:items-end transition-all duration-300 transform"
              >
                <!-- Wróć (Back to Level 2) -->
                <button
                  onclick="toggleHeroMenu('back-to-sub')"
                  class="w-full sm:w-80 h-auto sm:h-20 py-3 px-6 bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl text-white font-bold text-lg sm:text-xl text-center hover:bg-white/20 transition-all duration-300 shadow-xl flex items-center justify-center gap-4 group"
                >
                  <i
                    class="fa-solid fa-chevron-left group-hover:-translate-x-1 transition-transform text-[var(--primary-color)]"
                  ></i>
                  <span class="whitespace-nowrap text-shadow-sm">Wróć</span>
                </button>

                <!-- Bramy Przesuwne -->
                <a
                  href="nasze-projekty.php?category=bramy-przesuwne-aluminiowe"
                  class="w-full sm:w-80 h-auto sm:h-20 py-3 px-6 bg-black/60 sm:bg-black/70 backdrop-blur-sm border border-white/20 rounded-xl text-white font-bold text-lg sm:text-xl text-center hover:bg-[#cf1928] transition-all duration-300 shadow-xl hover:scale-105 flex items-center justify-center gap-4 group"
                >
                  <img
                    src="ikony/brama_podwojna.png"
                    alt="Przesuwne"
                    class="w-8 h-8 object-contain"
                  />
                  <span class="whitespace-nowrap text-shadow-sm">Bramy Przesuwne</span>
                </a>

                <!-- Bramy Dwuskrzydłowe -->
                <a
                  href="nasze-projekty.php?category=bramy-dwuskrzydlowe"
                  class="w-full sm:w-80 h-auto sm:h-20 py-3 px-6 bg-black/60 sm:bg-black/70 backdrop-blur-sm border border-white/20 rounded-xl text-white font-bold text-lg sm:text-xl text-center hover:bg-[#cf1928] transition-all duration-300 shadow-xl hover:scale-105 flex items-center justify-center gap-4 group"
                >
                  <img
                    src="ikony/brama_podwojna.png"
                    alt="Dwuskrzydłowe"
                    class="w-8 h-8 object-contain"
                  />
                  <span class="whitespace-nowrap text-shadow-sm">Bramy Dwuskrzydłowe</span>
                </a>
              </div>
            </div>
          </div>
        </div>

        <div class="absolute bottom-0 left-0 right-0 leading-[0]">
          <svg
            class="w-full h-12 lg:h-24 fill-white block"
            viewBox="0 0 1200 120"
            preserveAspectRatio="none"
            style="transform: translateY(1px);"
          >
            <path d="M0,0 Q300,80 600,40 T1200,0 L1200,120 L0,120 Z"></path>
          </svg>
        </div>
      </section>
      <section
        class="code-section pb-20 lg:pb-32 lg:pt-8 bg-white relative overflow-hidden"
        id="oferta"
      >
        <div
          class="absolute top-0 right-0 w-96 h-96 bg-[var(--light-background-color)] rounded-full blur-3xl opacity-50 -translate-y-1/2 translate-x-1/2"
        ></div>
        <div
          class="absolute bottom-0 left-0 w-96 h-96 bg-[var(--medium-background-color)] rounded-full blur-3xl opacity-30 translate-y-1/2 -translate-x-1/2"
        ></div>
        <div class="container mx-auto px-4 lg:px-8 relative z-10">
          <div class="text-center max-w-3xl mx-auto mb-16">
            <div
              class="inline-block px-4 py-2 bg-[var(--primary-color)] bg-opacity-10 border border-[var(--primary-color)] rounded-full mb-4"
            >
              <span class="text-[var(--light-text-color)] font-semibold text-sm"
                >Nasze Usługi</span
              >
            </div>
            <h2
              class="text-3xl sm:text-4xl lg:text-5xl font-bold text-[var(--dark-text-color)] font-[var(--font-family-heading)] mb-6"
            >
              Nowoczesne Bramy i Ogrodzenia<br />
              <span class="text-[var(--primary-color)]">Aluminiowe</span>
            </h2>
            <p class="text-lg text-[var(--gray-text-color)]">
              Specjalizujemy się w projektowaniu, produkcji i montażu bram i
              ogrodzeń aluminiowych.
            </p>
          </div>
          <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-8">
            <div
              class="group relative bg-white rounded-2xl border-2 border-[var(--light-border-color)] p-8 hover:border-[var(--primary-color)] transition-all duration-500 hover:shadow-2xl hover:-translate-y-2"
            >
              <div
                class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-[var(--primary-color)] to-transparent opacity-5 rounded-bl-full"
              ></div>
              <div class="relative mb-6">
                <div
                  class="w-20 h-20 bg-gradient-to-br from-[var(--primary-color)] to-[var(--accent-color)] rounded-2xl flex items-center justify-center transform group-hover:rotate-6 transition-transform duration-500 shadow-lg"
                >
                  <img
                    src="ikony/brama_podwojna.png"
                    alt="Bramy"
                    class="w-10 h-10 object-contain"
                  />
                </div>
              </div>
              <h3
                class="text-2xl font-bold text-[var(--dark-text-color)] mb-4 font-[var(--font-family-heading)]"
              >
                Bramy
              </h3>
              <p class="text-[var(--gray-text-color)] mb-6 leading-relaxed">
                Produkujemy bramy aluminiowe przesuwne i dwuskrzydłowe na
                wymiar.
              </p>
              <ul class="space-y-3 mb-6">
                <li
                  class="flex items-center gap-3 text-[var(--dark-text-color)]"
                >
                  <i
                    class="fa-solid fa-check-circle text-[var(--primary-color)]"
                    aria-hidden="true"
                  ></i>
                  <span class="text-sm">Bramy przesuwne aluminiowe</span>
                </li>
                <li
                  class="flex items-center gap-3 text-[var(--dark-text-color)]"
                >
                  <i
                    class="fa-solid fa-check-circle text-[var(--primary-color)]"
                    aria-hidden="true"
                  ></i>
                  <span class="text-sm">Bramy dwuskrzydłowe aluminiowe</span>
                </li>
                <li
                  class="flex items-center gap-3 text-[var(--dark-text-color)]"
                >
                  <i
                    class="fa-solid fa-check-circle text-[var(--primary-color)]"
                    aria-hidden="true"
                  ></i>
                  <span class="text-sm">Bramy garażowe</span>
                </li>
              </ul>
              <a
                href="nasze-projekty.php"
                class="inline-flex items-center gap-2 text-[var(--primary-color)] font-semibold hover:gap-4 transition-all duration-300"
              >
                Zobacz więcej
                <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
              </a>
            </div>
            <div
              class="group relative bg-white rounded-2xl border-2 border-[var(--light-border-color)] p-8 hover:border-[var(--primary-color)] transition-all duration-500 hover:shadow-2xl hover:-translate-y-2"
            >
              <div
                class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-[var(--primary-color)] to-transparent opacity-5 rounded-bl-full"
              ></div>
              <div class="relative mb-6">
                <div
                  class="w-20 h-20 bg-gradient-to-br from-[var(--primary-color)] to-[var(--accent-color)] rounded-2xl flex items-center justify-center transform group-hover:rotate-6 transition-transform duration-500 shadow-lg"
                >
                  <img
                    src="ikony/balustrada.png"
                    alt="Przęsła"
                    class="w-10 h-10 object-contain"
                  />
                </div>
              </div>
              <h3
                class="text-2xl font-bold text-[var(--dark-text-color)] mb-4 font-[var(--font-family-heading)]"
              >
                Przęsła Aluminiowe
              </h3>
              <p class="text-[var(--gray-text-color)] mb-6 leading-relaxed">
                Nowoczesne i trwałe ogrodzenia aluminiowe, które nie wymagają
                konserwacji.
              </p>
              <ul class="space-y-3 mb-6">
                <li
                  class="flex items-center gap-3 text-[var(--dark-text-color)]"
                >
                  <i
                    class="fa-solid fa-check-circle text-[var(--primary-color)]"
                    aria-hidden="true"
                  ></i>
                  <span class="text-sm">Ogrodzenia palisadowe</span>
                </li>
                <li
                  class="flex items-center gap-3 text-[var(--dark-text-color)]"
                >
                  <i
                    class="fa-solid fa-check-circle text-[var(--primary-color)]"
                    aria-hidden="true"
                  ></i>
                  <span class="text-sm">Ogrodzenia ażurowe</span>
                </li>
                <li
                  class="flex items-center gap-3 text-[var(--dark-text-color)]"
                >
                  <i
                    class="fa-solid fa-check-circle text-[var(--primary-color)]"
                    aria-hidden="true"
                  ></i>
                  <span class="text-sm">Furtki</span>
                </li>
              </ul>
              <a
                href="nasze-projekty.php"
                class="inline-flex items-center gap-2 text-[var(--primary-color)] font-semibold hover:gap-4 transition-all duration-300"
              >
                Zobacz więcej
                <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
              </a>
            </div>
            <div
              class="group relative bg-white rounded-2xl border-2 border-[var(--light-border-color)] p-8 hover:border-[var(--primary-color)] transition-all duration-500 hover:shadow-2xl hover:-translate-y-2"
            >
              <div
                class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-[var(--primary-color)] to-transparent opacity-5 rounded-bl-full"
              ></div>
              <div class="relative mb-6">
                <div
                  class="w-20 h-20 bg-gradient-to-br from-[var(--primary-color)] to-[var(--accent-color)] rounded-2xl flex items-center justify-center transform group-hover:rotate-6 transition-transform duration-500 shadow-lg"
                >
                  <img
                    src="ikony/balustrada.png"
                    alt="Balustrady"
                    class="w-10 h-10 object-contain"
                  />
                </div>
              </div>
              <h3
                class="text-2xl font-bold text-[var(--dark-text-color)] mb-4 font-[var(--font-family-heading)]"
              >
                Balustrady
              </h3>
              <p class="text-[var(--gray-text-color)] mb-6 leading-relaxed">
                Eleganckie i bezpieczne balustrady aluminiowe do domu i firmy.
              </p>
              <ul class="space-y-3 mb-6">
                <li
                  class="flex items-center gap-3 text-[var(--dark-text-color)]"
                >
                  <i
                    class="fa-solid fa-check-circle text-[var(--primary-color)]"
                    aria-hidden="true"
                  ></i>
                  <span class="text-sm">Wypełnienie szklane</span>
                </li>
                <li
                  class="flex items-center gap-3 text-[var(--dark-text-color)]"
                >
                  <i
                    class="fa-solid fa-check-circle text-[var(--primary-color)]"
                    aria-hidden="true"
                  ></i>
                  <span class="text-sm">Wypełnienie poziome</span>
                </li>
                <li
                  class="flex items-center gap-3 text-[var(--dark-text-color)]"
                >
                  <i
                    class="fa-solid fa-check-circle text-[var(--primary-color)]"
                    aria-hidden="true"
                  ></i>
                  <span class="text-sm">Wypełnienie pionowe</span>
                </li>
              </ul>
              <a
                href="nasze-projekty.php?category=balustrady"
                class="inline-flex items-center gap-2 text-[var(--primary-color)] font-semibold hover:gap-4 transition-all duration-300"
              >
                Zobacz więcej
                <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
              </a>
            </div>
            <div
              class="group relative bg-white rounded-2xl border-2 border-[var(--light-border-color)] p-8 hover:border-[var(--primary-color)] transition-all duration-500 hover:shadow-2xl hover:-translate-y-2"
            >
              <div
                class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-[var(--primary-color)] to-transparent opacity-5 rounded-bl-full"
              ></div>
              <div class="relative mb-6">
                <div
                  class="w-20 h-20 bg-gradient-to-br from-[var(--primary-color)] to-[var(--accent-color)] rounded-2xl flex items-center justify-center transform group-hover:rotate-6 transition-transform duration-500 shadow-lg"
                >
                  <img
                    src="ikony/automatyka.png"
                    alt="Automatyka"
                    class="w-10 h-10 object-contain"
                  />
                </div>
              </div>
              <h3
                class="text-2xl font-bold text-[var(--dark-text-color)] mb-4 font-[var(--font-family-heading)]"
              >
                Automatyka
              </h3>
              <p class="text-[var(--gray-text-color)] mb-6 leading-relaxed">
                Sprzedaż i montaż napędów do bram i systemów smart home.
              </p>
              <ul class="space-y-3 mb-6">
                <li
                  class="flex items-center gap-3 text-[var(--dark-text-color)]"
                >
                  <i
                    class="fa-solid fa-check-circle text-[var(--primary-color)]"
                    aria-hidden="true"
                  ></i>
                  <span class="text-sm">Napędy do bram</span>
                </li>
                <li
                  class="flex items-center gap-3 text-[var(--dark-text-color)]"
                >
                  <i
                    class="fa-solid fa-check-circle text-[var(--primary-color)]"
                    aria-hidden="true"
                  ></i>
                  <span class="text-sm">Wideodomofony</span>
                </li>
                <li
                  class="flex items-center gap-3 text-[var(--dark-text-color)]"
                >
                  <i
                    class="fa-solid fa-check-circle text-[var(--primary-color)]"
                    aria-hidden="true"
                  ></i>
                  <span class="text-sm">Sterowanie smartfonem</span>
                </li>
              </ul>
              <a
                href="nasze-projekty.php?category=automatyka"
                class="inline-flex items-center gap-2 text-[var(--primary-color)] font-semibold hover:gap-4 transition-all duration-300"
              >
                Zobacz więcej
                <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
              </a>
            </div>
          </div>
          <div class="mt-16 text-center">
            <div
              class="inline-flex flex-col sm:flex-row gap-4 items-center bg-gradient-to-r bg-[var(--dark-background-color)] to-[var(--medium-background-color)] rounded-2xl p-8 border-2 border-[var(--primary-color)] border-opacity-20"
            >
              <div class="text-left">
                <h3
                  class="text-2xl font-bold text-[var(--light-text-color)] mb-2"
                >
                  Nie znalazłeś tego, czego szukasz?
                </h3>
                <p class="text-[var(--light-text-color)]">
                  Skontaktuj się z nami – dobierzemy rozwiązanie idealnie dla
                  Ciebie.
                </p>
              </div>
              <a
                href="kontakt.php"
                class="whitespace-nowrap px-8 py-4 bg-[var(--primary-color)] text-[var(--primary-button-text-color)] rounded-lg font-bold hover:bg-[var(--primary-button-hover-bg-color)] transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-105"
              >
                Skontaktuj się
              </a>
            </div>
          </div>
        </div>
      </section>
    </main>
    <script>
      function toggleHeroMenu(target) {
        const mainMenu = document.getElementById("hero-main-menu");
        const subMenu = document.getElementById("hero-sub-menu");
        const subSubMenu = document.getElementById("hero-sub-sub-menu");

        if (target === "sub") {
          // Main -> Sub
          mainMenu.classList.add(
            "opacity-0",
            "-translate-y-4",
            "pointer-events-none",
          );
          setTimeout(() => {
            mainMenu.classList.add("hidden");
            subMenu.classList.remove("hidden");
            setTimeout(() => {
              subMenu.classList.remove("opacity-0", "translate-y-4");
            }, 50);
          }, 300);
        } else if (target === "main") {
          // Sub -> Main
          subMenu.classList.add(
            "opacity-0",
            "translate-y-4",
            "pointer-events-none",
          );
          setTimeout(() => {
            subMenu.classList.add("hidden");
            mainMenu.classList.remove("hidden", "pointer-events-none");
            setTimeout(() => {
              mainMenu.classList.remove("opacity-0", "-translate-y-4");
            }, 50);
          }, 300);
        } else if (target === "sub-sub") {
          // Sub -> Sub-Sub
          subMenu.classList.add(
            "opacity-0",
            "-translate-y-4",
            "pointer-events-none",
          );
          setTimeout(() => {
            subMenu.classList.add("hidden");
            subSubMenu.classList.remove("hidden");
            setTimeout(() => {
              subSubMenu.classList.remove("opacity-0", "translate-y-4");
            }, 50);
          }, 300);
        } else if (target === "back-to-sub") {
          // Sub-Sub -> Sub
          subSubMenu.classList.add(
            "opacity-0",
            "translate-y-4",
            "pointer-events-none",
          );
          setTimeout(() => {
            subSubMenu.classList.add("hidden");
            subMenu.classList.remove("hidden", "pointer-events-none");
            setTimeout(() => {
              subMenu.classList.remove("opacity-0", "-translate-y-4");
            }, 50);
          }, 300);
        }
      }

      // Simple Slideshow
      let slideIndex = 0;
      showSlides();

      function showSlides() {
        let i;
        let slides = document.getElementsByClassName("slide");
        for (i = 0; i < slides.length; i++) {
          slides[i].style.opacity = "0";
        }
        slideIndex++;
        if (slideIndex > slides.length) {
          slideIndex = 1;
        }
        slides[slideIndex - 1].style.opacity = "1";
        setTimeout(showSlides, 3000); // Change image every 3 seconds
      }
    </script>
<?php include 'includes/footer.php'; ?>