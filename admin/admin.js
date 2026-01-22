document.addEventListener('DOMContentLoaded', function() {
    
    // --- Authentication ---
    const correctPassword = "password123"; 
    const loginForm = document.getElementById('loginForm');
    const panelContainer = document.querySelector('.panel-container');

    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const password = document.getElementById('password').value;
            const errorMessage = document.getElementById('error-message');
            if (password === correctPassword) {
                sessionStorage.setItem('isAdminAuthenticated', 'true');
                window.location.href = 'panel.html';
            } else {
                errorMessage.textContent = "Nieprawidłowe hasło.";
            }
        });
    }

    // --- Panel Logic ---
    if (panelContainer && sessionStorage.getItem('isAdminAuthenticated') !== 'true') {
        window.location.href = 'index.html';
    }

    const logoutButton = document.getElementById('logoutButton');
    if (logoutButton) {
        logoutButton.addEventListener('click', function() {
            sessionStorage.removeItem('isAdminAuthenticated');
            window.location.href = 'index.html';
        });
    }

    // --- Gallery Management ---
    const uploadForm = document.getElementById('uploadForm');
    const galleryContainer = document.getElementById('gallery-container');
    const photoFileInput = document.getElementById('photoFile');
    const imagePreview = document.getElementById('imagePreview');
    const previewText = document.getElementById('previewText');
    const categorySelect = document.getElementById('category');
    const targetFilenameDisplay = document.getElementById('targetFilename');

    // Simulate directory counts (fetched from logic or API in real app)
    const categoryCounts = {
        'bramy-przesuwne-aluminiowe': 3,
        'bramy-dwuskrzydlowe': 4,
        'barierki': 5,
        'przesla-ogrodzeniowe-aluminiowe': 2
    };

    function updateTargetFilename() {
        if (!categorySelect || !targetFilenameDisplay) return;
        const cat = categorySelect.value;
        const nextNum = (categoryCounts[cat] || 0) + 1;
        targetFilenameDisplay.textContent = `assets/portfolio/${cat}/${nextNum}.jpg`;
    }

    if (categorySelect) {
        categorySelect.addEventListener('change', updateTargetFilename);
        updateTargetFilename();
    }

    // File Preview
    if (photoFileInput) {
        photoFileInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.style.display = 'block';
                    previewText.style.display = 'none';
                }
                reader.readAsDataURL(file);
            }
        });
    }

    // Simulate photos data
    let photos = [
        { src: '../assets/portfolio/bramy-przesuwne-aluminiowe/1.jpg', cat: 'bramy-przesuwne-aluminiowe', name: '1.jpg' },
        { src: '../assets/portfolio/bramy-dwuskrzydlowe/1.jpg', cat: 'bramy-dwuskrzydlowe', name: '1.jpg' },
        { src: '../assets/portfolio/barierki/1.jpg', cat: 'barierki', name: '1.jpg' },
        { src: '../assets/portfolio/przesla-ogrodzeniowe-aluminiowe/1.jpg', cat: 'przesla-ogrodzeniowe-aluminiowe', name: '1.jpg' }
    ];

    function renderGallery() {
        if (!galleryContainer) return;
        galleryContainer.innerHTML = '';
        photos.forEach((photo, index) => {
            const item = document.createElement('div');
            item.className = 'gallery-item';
            item.innerHTML = `
                <div class="delete-btn" onclick="deletePhoto(${index})" title="Usuń zdjęcie">
                    <i class="fa-solid fa-trash-can"></i>
                </div>
                <img src="${photo.src}" alt="${photo.name}">
                <div class="item-info">
                    <span class="text-xs text-gray-400">${photo.cat}</span><br>
                    <span class="filename-badge">${photo.name}</span>
                </div>
            `;
            galleryContainer.appendChild(item);
        });
    }

    window.deletePhoto = function(index) {
        if (confirm("Czy na pewno chcesz trwale usunąć to zdjęcie z realizacji?")) {
            const removed = photos.splice(index, 1)[0];
            renderGallery();
            // W docelowej wersji tutaj nastąpiłoby wywołanie API do usunięcia pliku z serwera
        }
    }
    
    if (uploadForm) {
        uploadForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const cat = categorySelect.value;
            const targetName = targetFilenameDisplay.textContent.split('/').pop();
            
            if (photoFileInput.files && photoFileInput.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const newPhoto = { 
                        src: e.target.result, 
                        cat: cat, 
                        name: targetName 
                    };
                    photos.unshift(newPhoto); // Add to top
                    categoryCounts[cat]++; // Increment count
                    renderGallery();
                    updateTargetFilename();
                    uploadForm.reset();
                    imagePreview.style.display = 'none';
                    previewText.style.display = 'block';
                    alert(`Sukces! Zdjęcie zostało przygotowane do zapisu jako: ${targetName} w folderze ${cat}. 

(Uwaga: W wersji statycznej plik nie jest fizycznie zapisywany na dysku serwera).`);
                }
                reader.readAsDataURL(photoFileInput.files[0]);
            }
        });
    }

    // --- SHOP MANAGEMENT ---
    
    // Initialize shop products from the global variable (loaded from shop.js) or empty array
    let shopProducts = (typeof products !== 'undefined') ? JSON.parse(JSON.stringify(products)) : [];
    
    // UI References
    const productListContainer = document.getElementById('products-list');
    const productEditor = document.getElementById('product-editor');
    const productForm = document.getElementById('productForm');

    // Tab Switching
    window.switchView = function(viewName) {
        const views = document.querySelectorAll('.admin-view');
        const navBtns = document.querySelectorAll('.nav-btn');
        
        views.forEach(v => v.style.display = 'none');
        document.getElementById(`view-${viewName}`).style.display = 'block';
        
        navBtns.forEach(btn => btn.classList.remove('active'));
        // Find button that called this (approximated logic)
        const activeBtn = Array.from(navBtns).find(btn => btn.innerText.toLowerCase().includes(viewName === 'gallery' ? 'realizacje' : 'sklep'));
        if (activeBtn) activeBtn.classList.add('active');
        
        if (viewName === 'shop') renderShopAdmin();
    }

    // Render Shop List
    function renderShopAdmin() {
        if (!productListContainer) return;
        productListContainer.innerHTML = '';
        
        shopProducts.forEach(p => {
            const card = document.createElement('div');
            card.className = 'product-card';
            card.innerHTML = `
                <div class="product-image">
                    <img src="../${p.image}" alt="${p.name}">
                </div>
                <div class="product-info">
                    <span class="product-cat">${p.category}</span>
                    <h3 class="product-title">${p.name}</h3>
                    <div class="product-price">${p.basePrice} PLN</div>
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

    // Open Editor (Add or Edit)
    window.openProductEditor = function(id = null) {
        productEditor.classList.remove('hidden');
        const title = document.getElementById('editor-title');
        
        if (id) {
            // Edit Mode
            const product = shopProducts.find(p => p.id === id);
            if (!product) return;
            
            title.textContent = 'Edytuj Produkt';
            document.getElementById('prod-id').value = product.id;
            document.getElementById('prod-name').value = product.name;
            document.getElementById('prod-category').value = product.category;
            document.getElementById('prod-price').value = product.basePrice;
            document.getElementById('prod-image').value = product.image;
            document.getElementById('prod-desc').value = product.description;
            renderOptionsEditor(product.options);
        } else {
            // Add Mode
            title.textContent = 'Dodaj Nowy Produkt';
            productForm.reset();
            document.getElementById('prod-id').value = '';
            renderOptionsEditor({});
        }
    }

    window.closeProductEditor = function() {
        productEditor.classList.add('hidden');
    }

    // --- Options Editor Logic ---

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
        div.dataset.key = key; // Store original key if editing

        let choicesHTML = '';
        if (data.type === 'select' && data.choices) {
            data.choices.forEach(choice => {
                choicesHTML += createChoiceRowHTML(choice.label, choice.value, choice.priceMod);
            });
        } else if (data.type === 'checkbox') {
             // For checkboxes, we just treat the 'price' field as a single 'priceMod' choice for simplicity in UI,
             // but visually distinct. Actually, let's stick to the Select structure but allow Type change.
             // If checkbox, we might just use one row for the price.
             choicesHTML = createChoiceRowHTML(data.label, 'true', data.price || 0); 
        }

        div.innerHTML = `
            <div class="option-header">
                <div style="flex-grow:1">
                    <label class="text-xs">ID Opcji (np. width)</label>
                    <input type="text" class="opt-key" value="${key}" placeholder="id_opcji">
                </div>
                <div style="flex-grow:2">
                    <label class="text-xs">Etykieta (np. Szerokość)</label>
                    <input type="text" class="opt-label" value="${data.label}" placeholder="Nazwa wyświetlana">
                </div>
                <div>
                    <label class="text-xs">Typ</label>
                    <select class="opt-type" onchange="toggleChoicesUI(this)">
                        <option value="select" ${data.type === 'select' ? 'selected' : ''}>Lista (Select)</option>
                        <option value="checkbox" ${data.type === 'checkbox' ? 'selected' : ''}>Pojedyncza (Checkbox)</option>
                    </select>
                </div>
                <button type="button" class="remove-btn" onclick="this.closest('.option-block').remove()">
                    <i class="fa-solid fa-times"></i>
                </button>
            </div>
            
            <div class="choices-wrapper ${data.type === 'checkbox' ? 'hidden' : ''}">
                <label class="text-xs font-bold">Warianty / Wybory:</label>
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
                <input type="text" placeholder="Nazwa (np. 4m)" value="${label}" class="choice-label">
                <input type="text" placeholder="Wartość" value="${value}" class="choice-value">
                <input type="number" placeholder="Cena +/-" value="${price}" class="choice-price">
                <button type="button" class="remove-btn" onclick="this.parentElement.remove()"><i class="fa-solid fa-trash"></i></button>
            </div>
        `;
    }

    window.addNewOptionUI = function() {
        const wrapper = document.getElementById('options-wrapper');
        const defaultData = { label: 'Nowa Opcja', type: 'select', choices: [] };
        wrapper.appendChild(createOptionHTML('new_option_' + Date.now(), defaultData));
    }

    window.addChoiceUI = function(btn) {
        const container = btn.previousElementSibling;
        const div = document.createElement('div');
        div.innerHTML = createChoiceRowHTML('', '', 0);
        container.appendChild(div.firstElementChild);
    }

    window.toggleChoicesUI = function(select) {
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
    }

    // Save Product
    if (productForm) {
        productForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Reconstruct Options Object
            const options = {};
            document.querySelectorAll('.option-block').forEach(block => {
                const key = block.querySelector('.opt-key').value;
                const label = block.querySelector('.opt-label').value;
                const type = block.querySelector('.opt-type').value;
                
                if (!key) return; // Skip empty keys

                if (type === 'select') {
                    const choices = [];
                    block.querySelectorAll('.choice-row').forEach(row => {
                        choices.push({
                            label: row.querySelector('.choice-label').value,
                            value: row.querySelector('.choice-value').value, // Keep as string or cast? logic depends on usage. 
                            // Shop.js often uses numbers for dimensions. Let's try to parse if number.
                            priceMod: parseFloat(row.querySelector('.choice-price').value) || 0
                        });
                    });
                    
                    // Auto-cast values
                    choices.forEach(c => {
                        if (!isNaN(c.value) && c.value.trim() !== '') c.value = parseFloat(c.value);
                    });

                    options[key] = { label, type, choices };
                } else {
                    const price = parseFloat(block.querySelector('.opt-checkbox-price').value) || 0;
                    options[key] = { label, type, price };
                }
            });

            const id = document.getElementById('prod-id').value;
            const newProduct = {
                id: id ? parseInt(id) : (shopProducts.length > 0 ? Math.max(...shopProducts.map(p => p.id)) + 1 : 1),
                name: document.getElementById('prod-name').value,
                category: document.getElementById('prod-category').value,
                basePrice: parseInt(document.getElementById('prod-price').value),
                image: document.getElementById('prod-image').value,
                description: document.getElementById('prod-desc').value,
                options: options
            };

            if (id) {
                const index = shopProducts.findIndex(p => p.id == id);
                shopProducts[index] = newProduct;
            } else {
                shopProducts.push(newProduct);
            }

            renderShopAdmin();
            closeProductEditor();
        });
    }

    // Delete Product
    window.deleteShopProduct = function(id) {
        if (confirm('Czy na pewno chcesz usunąć ten produkt?')) {
            shopProducts = shopProducts.filter(p => p.id !== id);
            renderShopAdmin();
        }
    }

    // Apply Changes (Generate Code)
    window.applyShopChanges = function() {
        const jsContent = `const products = ${JSON.stringify(shopProducts, null, 4)};`;
        
        console.log(jsContent);
        alert("Zmiany zostały przygotowane!\n\n1. Otwórz konsolę (F12 -> Console).\n2. Skopiuj wygenerowany kod `const products = [...]`.\n3. Wklej go do pliku `assets/shop.js` zastępując starą zawartość.\n\nTo jedyny sposób na zapisanie zmian w tej wersji statycznej.");
    }

    // Initial Renders
    renderGallery();
});