<?php
$pageTitle = "Przykładowe Realizacje";
$pageDesc = "Galeria realizacji nowoczesnych bram i ogrodzeń aluminiowych. Zobacz nasze projekty wykonane dla klientów w Małopolsce i całej Polsce.";
$extraScripts = '<script src="assets/gallery.js"></script>';
include 'includes/header.php';
?>
    <main>
      <section class="code-section pt-40 lg:pt-48 pb-20 lg:pb-32 bg-white mt-8">
        <div class="container mx-auto px-4 lg:px-8">
          <div class="text-center max-w-3xl mx-auto mb-12 lg:mb-16">
            <h1
              class="text-3xl sm:text-4xl lg:text-6xl font-bold text-[var(--dark-text-color)] font-[var(--font-family-heading)] mb-6"
            >
              Przykładowe Realizacje
            </h1>
            <p
              class="text-lg lg:text-xl text-[var(--gray-text-color)] leading-relaxed"
            >
              Zobacz wybrane projekty naszych klientów. Każdy projekt to dowód
              naszej pasji do aluminium.
            </p>
          </div>

          <!-- Tab buttons -->
          <div class="flex flex-wrap justify-center mb-8 gap-3 sm:gap-4 mt-8">
            <button
              class="gallery-tab-button active px-6 py-3 text-sm sm:text-base"
              data-category="all"
            >
              Wszystkie
            </button>
            <button
              class="gallery-tab-button px-6 py-3 text-sm sm:text-base"
              data-category="bramy-przesuwne-aluminiowe"
            >
              Bramy Przesuwne
            </button>
            <button
              class="gallery-tab-button px-6 py-3 text-sm sm:text-base"
              data-category="bramy-dwuskrzydlowe"
            >
              Bramy Dwuskrzydłowe
            </button>
            <button
              class="gallery-tab-button px-6 py-3 text-sm sm:text-base"
              data-category="balustrady"
            >
              Balustrady
            </button>
            <button
              class="gallery-tab-button px-6 py-3 text-sm sm:text-base"
              data-category="automatyka"
            >
              Automatyka
            </button>
            <button
              class="gallery-tab-button px-6 py-3 text-sm sm:text-base"
              data-category="bramy-garazowe"
            >
              Bramy Garażowe
            </button>
            <button
              class="gallery-tab-button px-6 py-3 text-sm sm:text-base"
              data-category="przesla-ogrodzeniowe-aluminiowe"
            >
              Przęsła Ogrodzeniowe
            </button>
          </div>

          <!-- Gallery panels -->
          <div id="gallery-all" class="gallery-grid tab-panel active">
            <!-- All images will be injected here -->
          </div>
          <div
            id="gallery-bramy-przesuwne-aluminiowe"
            class="gallery-grid tab-panel"
            style="display: none"
          >
            <!-- Images will be injected here -->
          </div>
          <div
            id="gallery-bramy-dwuskrzydlowe"
            class="gallery-grid tab-panel"
            style="display: none"
          >
            <!-- Images will be injected here -->
          </div>
          <div
            id="gallery-balustrady"
            class="gallery-grid tab-panel"
            style="display: none"
          >
            <!-- Images will be injected here -->
          </div>
          <div
            id="gallery-automatyka"
            class="gallery-grid tab-panel"
            style="display: none"
          >
            <!-- Images will be injected here -->
          </div>
          <div
            id="gallery-bramy-garazowe"
            class="gallery-grid tab-panel"
            style="display: none"
          >
            <!-- Images will be injected here -->
          </div>
          <div
            id="gallery-przesla-ogrodzeniowe-aluminiowe"
            class="gallery-grid tab-panel"
            style="display: none"
          >
            <!-- Images will be injected here -->
          </div>
        </div>
      </section>
    </main>

    <!-- Modal -->
    <div id="galleryModal" class="modal">
      <span class="modal-close">&times;</span>
      <img
        class="modal-content"
        id="modalImage"
        alt="Powiększone zdjęcie realizacji"
      />
    </div>
<?php include 'includes/footer.php'; ?>