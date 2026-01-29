/**
 * Panel Administracyjny Mario Bramy
 * Wersja PHP z API backend
 */

document.addEventListener('DOMContentLoaded', function () {

    // --- UI References ---
    const uploadForm = document.getElementById('uploadForm');
    const galleryContainer = document.getElementById('gallery-container');
    const photoFileInput = document.getElementById('photoFile');
    const imagePreview = document.getElementById('imagePreview');
    const previewText = document.getElementById('previewText');
    const categorySelect = document.getElementById('category');
    const targetFilenameDisplay = document.getElementById('targetFilename');
    const productListContainer = document.getElementById('products-list');
    const productEditor = document.getElementById('product-editor');
    const productForm = document.getElementById('productForm');

    // --- Gallery Category Counts (loaded from API) ---
    let categoryCounts = {};

    // --- Shop Products (loaded from API) ---
    let shopProducts = [];

    // --- Helper Functions ---

    function showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.innerHTML = `
            <i class="fa-solid fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
            <span>${message}</span>
        `;
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 25px;
            background: ${type === 'success' ? '#10b981' : '#ef4444'};
            color: white;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.2);
            z-index: 10000;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: slideIn 0.3s ease;
        `;
        document.body.appendChild(notification);

        setTimeout(() => {
            notification.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }

    // --- Gallery Management ---

    async function loadGallery() {
        if (!galleryContainer) return;

        galleryContainer.innerHTML = '<p class="loading-text"><i class="fa-solid fa-spinner fa-spin"></i> Ładowanie...</p>';

        try {
            const response = await fetch('../api/gallery.php');
            const data = await response.json();

            if (!data.success) {
                throw new Error(data.error || 'Błąd ładowania galerii');
            }

            // Count images per category
            categoryCounts = {};
            data.images.forEach(img => {
                categoryCounts[img.category] = (categoryCounts[img.category] || 0) + 1;
            });

            renderGallery(data.images);
            updateTargetFilename();

        } catch (error) {
            galleryContainer.innerHTML = `<p class="error-text"><i class="fa-solid fa-exclamation-triangle"></i> ${error.message}</p>`;
            console.error('Gallery load error:', error);
        }
    }

    function renderGallery(images) {
        if (!galleryContainer) return;

        if (images.length === 0) {
            galleryContainer.innerHTML = '<p class="empty-text">Brak zdjęć w galerii. Dodaj pierwsze zdjęcie powyżej.</p>';
            return;
        }

        galleryContainer.innerHTML = '';
        images.forEach(photo => {
            const item = document.createElement('div');
            item.className = 'gallery-item';
            item.innerHTML = `
                <div class="delete-btn" onclick="deletePhoto('${photo.category}', '${photo.name}')" title="Usuń zdjęcie">
                    <i class="fa-solid fa-trash-can"></i>
                </div>
                <img src="../${photo.src}" alt="${photo.name}" onerror="this.src='../assets/placeholder.png'">
                <div class="item-info">
                    <span class="text-xs text-gray-400">${formatCategoryName(photo.category)}</span><br>
                    <span class="filename-badge">${photo.name}</span>
                </div>
            `;
            galleryContainer.appendChild(item);
        });
    }

    function formatCategoryName(category) {
        const names = {
            'bramy-przesuwne-aluminiowe': 'Bramy Przesuwne',
            'bramy-dwuskrzydlowe': 'Bramy Dwuskrzydłowe',
            'barierki': 'Balustrady',
            'przesla-ogrodzeniowe-aluminiowe': 'Przęsła Ogrodzeniowe'
        };
        return names[category] || category;
    }

    function updateTargetFilename() {
        if (!categorySelect || !targetFilenameDisplay) return;
        const cat = categorySelect.value;
        const nextNum = (categoryCounts[cat] || 0) + 1;
        targetFilenameDisplay.textContent = `assets/portfolio/${cat}/${nextNum}.jpg`;
    }

    if (categorySelect) {
        categorySelect.addEventListener('change', updateTargetFilename);
    }

    // File Preview
    if (photoFileInput) {
        photoFileInput.addEventListener('change', function () {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    imagePreview.src = e.target.result;
                    imagePreview.style.display = 'block';
                    previewText.style.display = 'none';
                }
                reader.readAsDataURL(file);
            }
        });
    }

    // Upload Form Submit
    if (uploadForm) {
        uploadForm.addEventListener('submit', async function (e) {
            e.preventDefault();

            const submitBtn = document.getElementById('submitBtn');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Przesyłanie...';

            const formData = new FormData(this);

            try {
                const response = await fetch('../api/upload.php', {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                if (!data.success) {
                    throw new Error(data.error || 'Błąd przesyłania');
                }

                showNotification(`Zdjęcie ${data.filename} zostało dodane!`);

                // Reset form
                uploadForm.reset();
                imagePreview.style.display = 'none';
                previewText.style.display = 'block';

                // Reload gallery
                loadGallery();

            } catch (error) {
                showNotification(error.message, 'error');
                console.error('Upload error:', error);
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        });
    }

    // Delete Photo
    window.deletePhoto = async function (category, filename) {
        if (!confirm(`Czy na pewno chcesz usunąć zdjęcie ${filename}?`)) {
            return;
        }

        try {
            const response = await fetch('../api/delete-image.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ category, filename })
            });

            const data = await response.json();

            if (!data.success) {
                throw new Error(data.error || 'Błąd usuwania');
            }

            showNotification('Zdjęcie zostało usunięte');
            loadGallery();

        } catch (error) {
            showNotification(error.message, 'error');
            console.error('Delete error:', error);
        }
    };

    // --- SHOP MANAGEMENT ---

    // --- Shop Settings ---
    const shopModeBtn = document.getElementById('shopModeBtn');

    async function loadShopSettings() {
        if (!shopModeBtn) return;

        try {
            const response = await fetch('../api/settings.php');
            const data = await response.json();

            if (data.success) {
                updateToggleUI(data.shop_active);
            }
        } catch (error) {
            console.error('Settings load error:', error);
        }
    }

    async function updateShopSettings(isActive) {
        // Optimistic UI update
        updateToggleUI(isActive);

        try {
            const response = await fetch('../api/settings.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ shop_active: isActive })
            });
            const data = await response.json();

            if (data.success) {
                showNotification(`Tryb sklepu zmieniony na: ${data.shop_active ? 'Aktywny' : 'Wkrótce'}`);
                // Ensure state is synced
                if (data.shop_active !== isActive) updateToggleUI(data.shop_active);
            } else {
                throw new Error(data.error);
            }
        } catch (error) {
            showNotification('Błąd zmiany ustawień', 'error');
            console.error(error);
            // Revert UI on error
            updateToggleUI(!isActive);
        }
    }

    function updateToggleUI(isActive) {
        if (!shopModeBtn) return;

        // Store state
        shopModeBtn.dataset.active = isActive;

        if (isActive) {
            shopModeBtn.innerHTML = '<i class="fa-solid fa-store"></i> Sklep: Aktywny';
            shopModeBtn.style.backgroundColor = '#10b981'; // Green
        } else {
            shopModeBtn.innerHTML = '<i class="fa-solid fa-clock"></i> Sklep: Wkrótce';
            shopModeBtn.style.backgroundColor = '#6c757d'; // Gray
        }
    }

    if (shopModeBtn) {
        shopModeBtn.addEventListener('click', function () {
            const current = this.dataset.active === 'true';
            updateShopSettings(!current);
        });
    }


    async function loadProducts() {
        if (!productListContainer) return;

        productListContainer.innerHTML = '<p class="loading-text"><i class="fa-solid fa-spinner fa-spin"></i> Ładowanie...</p>';

        try {
            loadShopSettings(); // Load settings when loading products
            const response = await fetch('../api/products.php');
            const data = await response.json();

            if (!data.success) {
                throw new Error(data.error || 'Błąd ładowania produktów');
            }

            shopProducts = data.products;
            renderShopAdmin();

        } catch (error) {
            productListContainer.innerHTML = `<p class="error-text"><i class="fa-solid fa-exclamation-triangle"></i> ${error.message}</p>`;
            console.error('Products load error:', error);
        }
    }

    function renderShopAdmin() {
        if (!productListContainer) return;

        if (shopProducts.length === 0) {
            productListContainer.innerHTML = '<p class="empty-text">Brak produktów. Kliknij "Dodaj Produkt" aby rozpocząć.</p>';
            return;
        }

        productListContainer.innerHTML = '';

        shopProducts.forEach(p => {
            const card = document.createElement('div');
            card.className = 'product-card';
            card.innerHTML = `
                <div class="product-image">
                    <img src="../${p.image}" alt="${p.name}" onerror="this.src='../assets/placeholder.png'">
                </div>
                <div class="product-info">
                    <span class="product-cat">${p.category}</span>
                    <h3 class="product-title">${p.name}</h3>
                    <div class="product-price">${p.base_price || p.basePrice} PLN</div>
                </div>
                <div class="product-actions">
                    <button class="action-btn edit-btn" onclick="openProductEditor(${p.id})">
                        <i class="fa-solid fa-pen"></i> Edytuj
                    </button>
                    <button class="action-btn delete-product-btn" onclick="deleteShopProduct(${p.id})">
                        <i class="fa-solid fa-trash"></i> Usuń
                    </button>
                </div>
            `;
            productListContainer.appendChild(card);
        });
    }

    // Tab Switching
    window.switchView = function (viewName) {
        const views = document.querySelectorAll('.admin-view');
        const navBtns = document.querySelectorAll('.nav-btn');

        views.forEach(v => v.style.display = 'none');
        document.getElementById(`view-${viewName}`).style.display = 'block';

        navBtns.forEach(btn => btn.classList.remove('active'));
        const activeBtn = Array.from(navBtns).find(btn =>
            btn.innerText.toLowerCase().includes(viewName === 'gallery' ? 'realizacje' : 'sklep')
        );
        if (activeBtn) activeBtn.classList.add('active');

        if (viewName === 'shop') loadProducts();
        if (viewName === 'gallery') loadGallery();
    };

    // Open Product Editor
    window.openProductEditor = function (id = null) {
        productEditor.classList.remove('hidden');
        const title = document.getElementById('editor-title');

        if (id) {
            const product = shopProducts.find(p => p.id === id);
            if (!product) return;

            title.textContent = 'Edytuj Produkt';
            document.getElementById('prod-id').value = product.id;
            document.getElementById('prod-name').value = product.name;
            document.getElementById('prod-category').value = product.category;
            document.getElementById('prod-price').value = product.base_price || product.basePrice;
            document.getElementById('prod-image').value = product.image;
            document.getElementById('prod-desc').value = product.description;

            // Show image preview
            const preview = document.getElementById('prod-image-preview');
            if (product.image) {
                preview.src = '../' + product.image;
                preview.style.display = 'block';
            }

            renderOptionsEditor(product.options);
        } else {
            title.textContent = 'Dodaj Nowy Produkt';
            productForm.reset();
            document.getElementById('prod-id').value = '';
            document.getElementById('prod-image-preview').style.display = 'none';
            renderOptionsEditor({});
        }
    };

    window.closeProductEditor = function () {
        productEditor.classList.add('hidden');
    };

    // --- Options Editor Logic ---

    const OPTION_KEYS = {
        'width': 'Szerokość',
        'height': 'Wysokość',
        'color': 'Kolor',
        'automation': 'Automatyka',
        'montaz': 'Montaż',
        'akcesoria': 'Akcesoria'
    };

    function renderOptionsEditor(options) {
        const wrapper = document.getElementById('options-wrapper');
        wrapper.innerHTML = '';

        if (!options) return;

        Object.keys(options).forEach(key => {
            const opt = options[key];
            wrapper.appendChild(createOptionHTML(key, opt));
        });
    }

    function createOptionHTML(key, data) {
        const div = document.createElement('div');
        div.className = 'option-block';
        div.dataset.key = key;

        let choicesHTML = '';
        if (data.type === 'select' && data.choices) {
            data.choices.forEach(choice => {
                choicesHTML += createChoiceRowHTML(choice.label, choice.value, choice.priceMod);
            });
        } else if (data.type === 'checkbox') {
            choicesHTML = createChoiceRowHTML(data.label, 'true', data.price || 0);
        }

        let keyOptions = '';
        Object.entries(OPTION_KEYS).forEach(([k, label]) => {
            const selected = k === key ? 'selected' : '';
            keyOptions += `<option value="${k}" ${selected}>${label} (${k})</option>`;
        });
        if (!OPTION_KEYS[key] && key.indexOf('new_option') === -1) {
            keyOptions += `<option value="${key}" selected>${key} (Niestandardowe)</option>`;
        }

        div.innerHTML = `
            <button type="button" class="remove-block-btn" onclick="this.closest('.option-block').remove()" title="Usuń całą opcję">
                <i class="fa-solid fa-times"></i>
            </button>
            <div class="option-header">
                <div>
                    <label class="text-xs">Rodzaj Opcji</label>
                    <select class="opt-key">
                        ${keyOptions}
                        <option value="custom">Inne...</option>
                    </select>
                </div>
                <div>
                    <label class="text-xs">Typ Pola</label>
                    <select class="opt-type" onchange="toggleChoicesUI(this)">
                        <option value="select" ${data.type === 'select' ? 'selected' : ''}>Lista Rozwijana</option>
                        <option value="checkbox" ${data.type === 'checkbox' ? 'selected' : ''}>Pole Wyboru (Tak/Nie)</option>
                    </select>
                </div>
                <div>
                    <label class="text-xs">Tytuł Wyświetlany</label>
                    <input type="text" class="opt-label" value="${data.label}" placeholder="np. Wybierz Kolor">
                </div>
            </div>
            
            <div class="choices-wrapper ${data.type === 'checkbox' ? 'hidden' : ''}">
                <label class="text-xs font-bold mb-2 block">Warianty (Wybory):</label>
                <div class="choices-container">
                    ${choicesHTML}
                </div>
                <button type="button" class="add-choice-btn" onclick="addChoiceUI(this)">+ Dodaj Wariant</button>
            </div>
            
            <div class="checkbox-price-wrapper ${data.type === 'checkbox' ? '' : 'hidden'}">
                <label class="text-xs font-bold">Cena za zaznaczenie (PLN):</label>
                <input type="number" class="opt-checkbox-price" value="${data.price || 0}">
            </div>
        `;
        return div;
    }

    function createChoiceRowHTML(label, value, price) {
        return `
            <div class="choice-row">
                <div class="choice-input-group">
                    <label>Wartość systemowa</label>
                    <input type="text" placeholder="np. 4.0" value="${value}" class="choice-value">
                </div>
                <div class="choice-input-group">
                    <label>Cena +/- (PLN)</label>
                    <input type="number" placeholder="np. 500" value="${price}" class="choice-price">
                </div>
                <div class="choice-input-group">
                    <label>Nazwa dla klienta</label>
                    <input type="text" placeholder="np. Szerokość 4m" value="${label}" class="choice-label">
                </div>
                <button type="button" class="remove-btn" onclick="this.parentElement.remove()" title="Usuń ten wariant">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </div>
        `;
    }

    window.addNewOptionUI = function () {
        const wrapper = document.getElementById('options-wrapper');
        const defaultData = { label: 'Nowa Opcja', type: 'select', choices: [] };
        wrapper.appendChild(createOptionHTML('new_option_' + Date.now(), defaultData));
    };

    window.addChoiceUI = function (btn) {
        const container = btn.previousElementSibling;
        const div = document.createElement('div');
        div.innerHTML = createChoiceRowHTML('', '', 0);
        container.appendChild(div.firstElementChild);
    };

    window.toggleChoicesUI = function (select) {
        const block = select.closest('.option-block');
        const choicesWrapper = block.querySelector('.choices-wrapper');
        const checkboxWrapper = block.querySelector('.checkbox-price-wrapper');

        if (select.value === 'select') {
            choicesWrapper.classList.remove('hidden');
            checkboxWrapper.classList.add('hidden');
        } else {
            choicesWrapper.classList.add('hidden');
            checkboxWrapper.classList.remove('hidden');
        }
    };

    // Product Image File Handler
    const prodImageFile = document.getElementById('prod-image-file');
    const prodImageInput = document.getElementById('prod-image');
    const prodImagePreview = document.getElementById('prod-image-preview');

    if (prodImageFile) {
        prodImageFile.addEventListener('change', function () {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    prodImagePreview.src = e.target.result;
                    prodImagePreview.style.display = 'block';
                    // For now, we'll need to upload the image separately
                    // or encode as base64 (not recommended for production)
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Save Product
    if (productForm) {
        productForm.addEventListener('submit', async function (e) {
            e.preventDefault();

            // Reconstruct Options Object
            const options = {};
            document.querySelectorAll('.option-block').forEach(block => {
                const key = block.querySelector('.opt-key').value;
                const label = block.querySelector('.opt-label').value;
                const type = block.querySelector('.opt-type').value;

                if (!key) return;

                if (type === 'select') {
                    const choices = [];
                    block.querySelectorAll('.choice-row').forEach(row => {
                        let value = row.querySelector('.choice-value').value;
                        if (!isNaN(value) && value.trim() !== '') {
                            value = parseFloat(value);
                        }
                        choices.push({
                            label: row.querySelector('.choice-label').value,
                            value: value,
                            priceMod: parseFloat(row.querySelector('.choice-price').value) || 0
                        });
                    });
                    options[key] = { label, type, choices };
                } else {
                    const price = parseFloat(block.querySelector('.opt-checkbox-price').value) || 0;
                    options[key] = { label, type, price };
                }
            });

            const id = document.getElementById('prod-id').value;
            const productData = {
                id: id ? parseInt(id) : null,
                name: document.getElementById('prod-name').value,
                category: document.getElementById('prod-category').value,
                base_price: parseInt(document.getElementById('prod-price').value),
                image: document.getElementById('prod-image').value,
                description: document.getElementById('prod-desc').value,
                options: options
            };

            try {
                const method = id ? 'PUT' : 'POST';
                const response = await fetch('../api/products.php', {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(productData)
                });

                const data = await response.json();

                if (!data.success) {
                    throw new Error(data.error || 'Błąd zapisu produktu');
                }

                showNotification(id ? 'Produkt zaktualizowany!' : 'Produkt dodany!');
                closeProductEditor();
                loadProducts();

            } catch (error) {
                showNotification(error.message, 'error');
                console.error('Product save error:', error);
            }
        });
    }

    // Delete Product
    window.deleteShopProduct = async function (id) {
        if (!confirm('Czy na pewno chcesz usunąć ten produkt?')) {
            return;
        }

        try {
            const response = await fetch('../api/products.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id })
            });

            const data = await response.json();

            if (!data.success) {
                throw new Error(data.error || 'Błąd usuwania produktu');
            }

            showNotification('Produkt został usunięty');
            loadProducts();

        } catch (error) {
            showNotification(error.message, 'error');
            console.error('Product delete error:', error);
        }
    };

    // --- Initial Load ---
    loadGallery();

    // Add CSS for notifications
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideOut {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
        .loading-text, .error-text, .empty-text {
            text-align: center;
            padding: 40px;
            color: #888;
            font-size: 1rem;
        }
        .error-text { color: #ef4444; }
        .logout-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: transparent;
            border: 1px solid #ccc;
            border-radius: 8px;
            color: inherit;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s;
        }
        .logout-btn:hover {
            background: #ef4444;
            border-color: #ef4444;
            color: white;
        }
    `;
    document.head.appendChild(style);
});