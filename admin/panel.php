<?php
/**
 * Główny panel administracyjny
 */
require_once __DIR__ . '/../config/init.php';

// Wymagaj zalogowania
requireAdmin();
?>
<!doctype html>
<html lang="pl">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Panel Admina - Mario Bramy</title>
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
    />
    <link rel="stylesheet" href="admin.css" />
  </head>
  <body>
    <div class="panel-container">
      <header class="panel-header">
        <div class="brand">
          <img src="../assets/logo.png" alt="Logo" class="brand-logo" />
          <h1 class="brand-title">System Realizacji</h1>
        </div>
        <a href="logout.php" id="logoutButton" class="logout-btn">
          <i class="fa-solid fa-right-from-bracket"></i>
          <span>Wyloguj się</span>
        </a>
      </header>

      <nav class="admin-nav">
        <button class="nav-btn active" onclick="switchView('gallery')">
          <i class="fa-solid fa-images"></i> Realizacje
        </button>
        <button class="nav-btn" onclick="switchView('shop')">
          <i class="fa-solid fa-store"></i> Sklep
        </button>
      </nav>

      <main>
        <!-- GALLERY VIEW -->
        <div id="view-gallery" class="admin-view">
          <section class="upload-section">
            <h2>Zarządzanie Projektami</h2>
            <div class="upload-container">
              <div class="upload-card">
                <form id="uploadForm" class="admin-form" enctype="multipart/form-data">
                  <div class="form-inputs">
                    <div class="form-group">
                      <label for="category">Kategoria Projektu</label>
                      <select id="category" name="category" required>
                        <option value="bramy-przesuwne-aluminiowe">
                          Bramy Przesuwne
                        </option>
                        <option value="bramy-dwuskrzydlowe">
                          Bramy Dwuskrzydłowe
                        </option>
                        <option value="barierki">Balustrady</option>
                        <option value="przesla-ogrodzeniowe-aluminiowe">
                          Przęsła Ogrodzeniowe
                        </option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="photoFile">Zdjęcie</label>
                      <input
                        type="file"
                        id="photoFile"
                        name="photo"
                        accept="image/*"
                        required
                      />
                    </div>
                    <div class="form-group">
                      <label for="photoAlt">Opis (Alt text)</label>
                      <input
                        type="text"
                        id="photoAlt"
                        name="alt_text"
                        placeholder="np. Nowoczesna brama przesuwna..."
                        required
                      />
                    </div>
                    <div class="filename-preview">
                      Docelowa nazwa: <span id="targetFilename">--</span>
                    </div>
                  </div>

                  <div class="preview-card" id="previewCard">
                    <p id="previewText">Podgląd zdjęcia</p>
                    <img
                      id="imagePreview"
                      class="image-preview"
                      src="#"
                      alt="Preview"
                    />
                  </div>
                  <span></span>
                  <button style="margin-top: 3rem" type="submit" id="submitBtn">
                    <i class="fa-solid fa-upload"></i> Dodaj do Realizacji
                  </button>
                </form>
              </div>
            </div>
          </section>

          <section class="gallery-preview">
            <h2 style="margin-top: 4rem">Twoje Realizacje</h2>
            <div id="gallery-container" class="gallery-grid">
              <!-- Loaded dynamically via API -->
              <p class="loading-text"><i class="fa-solid fa-spinner fa-spin"></i> Ładowanie...</p>
            </div>
          </section>
        </div>

        <!-- SHOP VIEW -->
        <div id="view-shop" class="admin-view" style="display: none">
          <section class="shop-management">
            <div class="flex justify-between items-center mb-6">
              <h2>Zarządzanie Produktami</h2>
              <div class="flex items-center gap-4">
                  <button id="shopModeBtn" class="add-btn" style="background-color: #6c757d; min-width: 200px;">
                      <i class="fa-solid fa-clock"></i> Sklep: Wkrótce
                  </button>
                  <button onclick="openProductEditor()" class="add-btn">
                    <i class="fa-solid fa-plus"></i> Dodaj Produkt
                  </button>
              </div>
            </div>

            <div id="products-list" class="products-grid">
              <!-- Rendered by JS from API -->
              <p class="loading-text"><i class="fa-solid fa-spinner fa-spin"></i> Ładowanie...</p>
            </div>
          </section>

          <!-- Product Editor (Overlay/Modal style inline) -->
          <div id="product-editor" class="editor-overlay hidden">
            <div class="editor-card">
              <div class="editor-header">
                <h3 id="editor-title">Edytuj Produkt</h3>
                <button onclick="closeProductEditor()" class="close-editor">
                  <i class="fa-solid fa-times"></i>
                </button>
              </div>
              <form id="productForm" class="admin-form">
                <input type="hidden" id="prod-id" />
                <div class="form-group">
                  <label>Nazwa Produktu</label>
                  <input type="text" id="prod-name" required />
                </div>
                <div class="form-group">
                  <label>Kategoria</label>
                  <select id="prod-category">
                    <option value="bramy">Bramy</option>
                    <option value="ogrodzenia">Ogrodzenia</option>
                    <option value="balustrady">Balustrady</option>
                    <option value="automatyka">Automatyka</option>
                  </select>
                </div>
                <div class="form-group">
                  <label>Cena Bazowa (PLN)</label>
                  <input type="number" id="prod-price" required />
                </div>
                <div class="form-group">
                  <label>Zdjęcie Produktu</label>
                  <input type="file" id="prod-image-file" accept="image/*" class="mb-2" />
                  <input type="text" id="prod-image" placeholder="Lub wklej ścieżkę do zdjęcia..." style="font-size: 0.8rem; color: #666;" />
                  <img id="prod-image-preview" src="" alt="Podgląd" style="display:none; max-width: 100%; height: 150px; object-fit: cover; margin-top: 10px; border-radius: 8px; border: 1px dashed #ccc;" />
                </div>
                <div class="form-group">
                  <label>Opis</label>
                  <textarea id="prod-desc" rows="3"></textarea>
                </div>

                <!-- Options Editor Section -->
                <div class="form-group">
                  <label class="mb-2">Opcje i Konfiguracja (Wpływ na cenę)</label>
                  <div id="options-wrapper" class="space-y-4">
                    <!-- Dynamic options will be rendered here -->
                  </div>
                  <button
                    type="button"
                    onclick="addNewOptionUI()"
                    class="add-option-btn mt-3"
                  >
                    <i class="fa-solid fa-list-check"></i> Dodaj Nową Opcję
                  </button>
                </div>

                <div class="form-actions">
                  <button type="submit" class="save-btn">
                    <i class="fa-solid fa-save"></i> Zapisz
                  </button>
                  <button
                    type="button"
                    onclick="closeProductEditor()"
                    class="cancel-btn"
                  >
                    Anuluj
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </main>
    </div>
    <script src="admin.js"></script>
  </body>
</html>
