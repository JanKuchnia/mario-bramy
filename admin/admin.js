document.addEventListener('DOMContentLoaded', function() {
    
    // --- Authentication (Server-side verification) ---
    const loginForm = document.getElementById('loginForm');
    const panelContainer = document.querySelector('.panel-container');

    if (loginForm) {
        loginForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const username = document.getElementById('username')?.value || '';
            const password = document.getElementById('password')?.value || '';
            const errorMessage = document.getElementById('error-message');
            
            try {
                const response = await fetch('/admin/login.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ username, password })
                });
                
                const data = await response.json();
                if (data.success) {
                    window.location.href = 'panel.html';
                } else {
                    errorMessage.textContent = data.message || "Nieprawidłowe hasło.";
                }
            } catch (error) {
                errorMessage.textContent = "Błąd łączności";
                console.error('Login error:', error);
            }
        });
    }

    // --- Panel Logic (Server-side validation) ---
    if (panelContainer) {
        // Panel is protected by require_login() on server
        // Client-side sessionStorage is not security control
        initializePanelFeatures();
    }
    
    const logoutButton = document.getElementById('logoutButton');
    if (logoutButton) {
        logoutButton.addEventListener('click', async function() {
            try {
                await fetch('/admin/logout.php', { method: 'POST' });
                window.location.href = 'index.html';
            } catch (error) {
                console.error('Logout error:', error);
                window.location.href = 'index.html';
            }
        });
    }
    
    function initializePanelFeatures() {

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
            
            // Create elements safely without innerHTML
            const deleteBtn = document.createElement('div');
            deleteBtn.className = 'delete-btn';
            deleteBtn.title = 'Usuń zdjęcie';
            deleteBtn.setAttribute('onclick', `deletePhoto(${index})`);
            deleteBtn.innerHTML = '<i class="fa-solid fa-trash-can"></i>';
            
            const img = document.createElement('img');
            img.src = photo.src;
            img.alt = photo.name;
            
            const info = document.createElement('div');
            info.className = 'item-info';
            const catSpan = document.createElement('span');
            catSpan.className = 'text-xs text-gray-400';
            catSpan.textContent = photo.cat;
            const nameSpan = document.createElement('span');
            nameSpan.className = 'filename-badge';
            nameSpan.textContent = photo.name;
            
            info.appendChild(catSpan);
            info.appendChild(document.createElement('br'));
            info.appendChild(nameSpan);
            
            item.appendChild(deleteBtn);
            item.appendChild(img);
            item.appendChild(info);
            
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
            
            // Safely create image
            const imgDiv = document.createElement('div');
            imgDiv.className = 'product-image';
            const img = document.createElement('img');
            img.src = '../' + p.image;
            img.alt = p.name;
            imgDiv.appendChild(img);
            
            // Create info section
            const infoDiv = document.createElement('div');
            infoDiv.className = 'product-info';
            const catSpan = document.createElement('span');
            catSpan.className = 'product-cat';
            catSpan.textContent = p.category;
            const title = document.createElement('h3');
            title.className = 'product-title';
            title.textContent = p.name;
            const price = document.createElement('div');
            price.className = 'product-price';
            price.textContent = p.basePrice + ' PLN';
            
            infoDiv.appendChild(catSpan);
            infoDiv.appendChild(title);
            infoDiv.appendChild(price);
            
            // Create actions
            const actionsDiv = document.createElement('div');
            actionsDiv.className = 'product-actions';
            
            const editBtn = document.createElement('button');
            editBtn.className = 'action-btn edit-btn';
            editBtn.setAttribute('onclick', `openProductEditor(${p.id})`);
            editBtn.innerHTML = '<i class="fa-solid fa-pen"></i> Edytuj';
            
            const deleteBtn = document.createElement('button');
            deleteBtn.className = 'action-btn delete-product-btn';
            deleteBtn.setAttribute('onclick', `deleteShopProduct(${p.id})`);
            deleteBtn.innerHTML = '<i class="fa-solid fa-trash"></i> Usuń';
            
            actionsDiv.appendChild(editBtn);
            actionsDiv.appendChild(deleteBtn);
            
            card.appendChild(imgDiv);
            card.appendChild(infoDiv);
            card.appendChild(actionsDiv);
            
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

    const OPTION_KEYS = {
        'width': 'Szerokość',
        'height': 'Wysokość',
        'color': 'Kolor',
        'automation': 'Automatyka',
        'montaz': 'Montaż',
        'akcesoria': 'Akcesoria'
    };

    function createOptionHTML(key, data) {
        const div = document.createElement('div');
        div.className = 'option-block';
        div.dataset.key = key;

        // Safely create all elements without innerHTML template literals
        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.className = 'remove-block-btn';
        removeBtn.title = 'Usuń całą opcję';
        removeBtn.onclick = function() { this.closest('.option-block').remove(); };
        removeBtn.innerHTML = '<i class="fa-solid fa-times"></i>';

        const headerDiv = document.createElement('div');
        headerDiv.className = 'option-header';

        // Key selector
        const keyDiv = document.createElement('div');
        const keyLabel = document.createElement('label');
        keyLabel.className = 'text-xs';
        keyLabel.textContent = 'Rodzaj Opcji';
        const keySelect = document.createElement('select');
        keySelect.className = 'opt-key';
        
        Object.entries(OPTION_KEYS).forEach(([k, label]) => {
            const opt = document.createElement('option');
            opt.value = k;
            opt.textContent = label + ' (' + k + ')';
            if (k === key) opt.selected = true;
            keySelect.appendChild(opt);
        });
        
        if (!OPTION_KEYS[key] && key.indexOf('new_option') === -1) {
            const opt = document.createElement('option');
            opt.value = key;
            opt.textContent = key + ' (Niestandardowe)';
            opt.selected = true;
            keySelect.appendChild(opt);
        }
        
        const customOpt = document.createElement('option');
        customOpt.value = 'custom';
        customOpt.textContent = 'Inne...';
        keySelect.appendChild(customOpt);
        
        keyDiv.appendChild(keyLabel);
        keyDiv.appendChild(keySelect);

        // Type selector
        const typeDiv = document.createElement('div');
        const typeLabel = document.createElement('label');
        typeLabel.className = 'text-xs';
        typeLabel.textContent = 'Typ Pola';
        const typeSelect = document.createElement('select');
        typeSelect.className = 'opt-type';
        typeSelect.onchange = function() { toggleChoicesUI(this); };
        
        const selectOpt = document.createElement('option');
        selectOpt.value = 'select';
        selectOpt.textContent = 'Lista Rozwijana';
        if (data.type === 'select') selectOpt.selected = true;
        typeSelect.appendChild(selectOpt);
        
        const checkboxOpt = document.createElement('option');
        checkboxOpt.value = 'checkbox';
        checkboxOpt.textContent = 'Pole Wyboru (Tak/Nie)';
        if (data.type === 'checkbox') checkboxOpt.selected = true;
        typeSelect.appendChild(checkboxOpt);
        
        typeDiv.appendChild(typeLabel);
        typeDiv.appendChild(typeSelect);

        // Label input
        const labelDiv = document.createElement('div');
        const labelLabel = document.createElement('label');
        labelLabel.className = 'text-xs';
        labelLabel.textContent = 'Tytuł Wyświetlany';
        const labelInput = document.createElement('input');
        labelInput.type = 'text';
        labelInput.className = 'opt-label';
        labelInput.value = data.label || '';
        labelInput.placeholder = 'np. Wybierz Kolor';
        labelDiv.appendChild(labelLabel);
        labelDiv.appendChild(labelInput);

        headerDiv.appendChild(keyDiv);
        headerDiv.appendChild(typeDiv);
        headerDiv.appendChild(labelDiv);

        // Choices wrapper
        const choicesWrapper = document.createElement('div');
        choicesWrapper.className = 'choices-wrapper';
        if (data.type === 'checkbox') choicesWrapper.classList.add('hidden');
        
        const choicesLabel = document.createElement('label');
        choicesLabel.className = 'text-xs font-bold mb-2 block';
        choicesLabel.textContent = 'Warianty (Wybory):';
        
        const choicesContainer = document.createElement('div');
        choicesContainer.className = 'choices-container';
        
        if (data.type === 'select' && data.choices) {
            data.choices.forEach(choice => {
                choicesContainer.appendChild(createChoiceRow(choice.label, choice.value, choice.priceMod));
            });
        }
        
        const addChoiceBtn = document.createElement('button');
        addChoiceBtn.type = 'button';
        addChoiceBtn.className = 'add-choice-btn';
        addChoiceBtn.textContent = '+ Dodaj Wariant';
        addChoiceBtn.onclick = function() { addChoiceUI(this); };
        
        choicesWrapper.appendChild(choicesLabel);
        choicesWrapper.appendChild(choicesContainer);
        choicesWrapper.appendChild(addChoiceBtn);

        // Checkbox price wrapper
        const checkboxWrapper = document.createElement('div');
        checkboxWrapper.className = 'checkbox-price-wrapper';
        if (data.type !== 'checkbox') checkboxWrapper.classList.add('hidden');
        
        const priceLabel = document.createElement('label');
        priceLabel.className = 'text-xs font-bold';
        priceLabel.textContent = 'Cena za zaznaczenie (PLN):';
        
        const priceInput = document.createElement('input');
        priceInput.type = 'number';
        priceInput.className = 'opt-checkbox-price';
        priceInput.value = data.price || 0;
        
        checkboxWrapper.appendChild(priceLabel);
        checkboxWrapper.appendChild(priceInput);

        div.appendChild(removeBtn);
        div.appendChild(headerDiv);
        div.appendChild(choicesWrapper);
        div.appendChild(checkboxWrapper);
        
        return div;
    }

    function createChoiceRow(label, value, price) {
        const row = document.createElement('div');
        row.className = 'choice-row';
        
        const inputGroup = document.createElement('div');
        inputGroup.className = 'choice-input-group';
        
        const valueLabel = document.createElement('label');
        valueLabel.textContent = 'Wartość systemowa';
        const valueInput = document.createElement('input');
        valueInput.type = 'text';
        valueInput.placeholder = 'np. 4.0';
        valueInput.value = value || '';
        valueInput.className = 'choice-value';
        
        const labelLabel = document.createElement('label');
        labelLabel.textContent = 'Wyświetlana nazwa';
        const labelInput = document.createElement('input');
        labelInput.type = 'text';
        labelInput.placeholder = 'np. 4.0m';
        labelInput.value = label || '';
        labelInput.className = 'choice-label';
        
        const priceLabel = document.createElement('label');
        priceLabel.textContent = 'Cena (PLN)';
        const priceInput = document.createElement('input');
        priceInput.type = 'number';
        priceInput.value = price || 0;
        priceInput.className = 'choice-price';
        
        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.className = 'remove-choice-btn';
        removeBtn.textContent = '✕';
        removeBtn.onclick = function() { this.closest('.choice-row').remove(); };
        
        inputGroup.appendChild(valueLabel);
        inputGroup.appendChild(valueInput);
        inputGroup.appendChild(labelLabel);
        inputGroup.appendChild(labelInput);
        inputGroup.appendChild(priceLabel);
        inputGroup.appendChild(priceInput);
        inputGroup.appendChild(removeBtn);
        
        row.appendChild(inputGroup);
        return row;
    }
                <div class="choice-input-group">
                    <label>Nazwa dla klienta</label>
                    <input type="text" placeholder="np. Szerokość 4m" value="${label}" class="choice-label">
                </div>
                <button type="button" class="remove-btn" onclick="this.parentElement.remove()" title="Usuń ten wariant"><i class="fa-solid fa-trash"></i></button>
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

    // Product Image File Handler
    const prodImageFile = document.getElementById('prod-image-file');
    const prodImageInput = document.getElementById('prod-image');
    const prodImagePreview = document.getElementById('prod-image-preview');

    if (prodImageFile) {
        prodImageFile.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    prodImagePreview.src = e.target.result;
                    prodImagePreview.style.display = 'block';
                    // We set the base64 string to the input, mimicking a 'url' save for this static demo
                    prodImageInput.value = e.target.result; 
                }
                reader.readAsDataURL(file);
            }
        });
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
    } // Close initializePanelFeatures
});