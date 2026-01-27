/**
 * Shop JavaScript - Product Configurator
 * Handles: Product loading, Price calculation, Option selection, Redirect to contact
 */

document.addEventListener('DOMContentLoaded', function() {
    const loadingIndicator = document.getElementById('loading');
    const productsContainer = document.getElementById('products-container');
    
    // Load products on page load
    loadProducts();
    
    /**
     * Load products from API
     */
    function loadProducts() {
        fetch('/mario-bramy/api/products.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                loadingIndicator.classList.add('hidden');
                
                if (data.success && data.products.length > 0) {
                    // Render products
                    data.products.forEach((product, index) => {
                        const productCard = createProductCard(product, index);
                        productsContainer.appendChild(productCard);
                    });
                    
                    productsContainer.classList.remove('hidden');
                } else {
                    loadingIndicator.innerHTML = '<p class="text-gray-600">Brak produktów dostępnych.</p>';
                }
            })
            .catch(error => {
                console.error('Error loading products:', error);
                loadingIndicator.innerHTML = '<p class="text-red-600">Błąd przy ładowaniu produktów. Spróbuj później.</p>';
            });
    }
    
    /**
     * Create product card element
     */
    function createProductCard(product, index) {
        const div = document.createElement('div');
        div.className = 'mb-12 bg-white rounded-lg shadow-lg overflow-hidden product-card';
        div.id = 'product-' + product.id;
        
        let html = `
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 p-8">
                <!-- Product Image -->
                <div>
                    <img src="${product.image_url}" alt="${product.name}" class="w-full rounded-lg" loading="lazy">
                </div>
                
                <!-- Product Info -->
                <div>
                    <h2 class="text-3xl font-bold mb-3">${product.name}</h2>
                    <p class="text-gray-600 mb-6">${product.description}</p>
                    
                    <!-- Base Price -->
                    <div class="mb-6 p-4 bg-gray-50 rounded">
                        <p class="text-sm text-gray-600 mb-1">Cena bazowa</p>
                        <p class="text-3xl font-bold text-primary-color">${product.base_price.toFixed(2)} PLN</p>
                    </div>
                    
                    <!-- Configurator (Hidden by default) -->
                    <button class="configurator-toggle w-full bg-gray-200 hover:bg-gray-300 text-gray-900 font-bold py-3 px-6 rounded transition mb-6" data-product-id="${product.id}">
                        ▼ Rozwiń konfigurator
                    </button>
                    
                    <!-- Configurator Form (Hidden) -->
                    <div class="configurator-form hidden mb-6" data-product-id="${product.id}">
        `;
        
        // Add options
        if (product.options.length > 0) {
            product.options.forEach(option => {
                html += `
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">${option.label}</label>
                        <select class="w-full px-4 py-2 border border-gray-300 rounded option-select" data-product-id="${product.id}" data-option-key="${option.key}">
                            <option value="">-- Wybierz --</option>
                `;
                
                option.choices.forEach(choice => {
                    const priceText = choice.price_modifier > 0 ? ` (+${choice.price_modifier.toFixed(2)} PLN)` : '';
                    html += `<option value="${choice.value}" data-price-mod="${choice.price_modifier}">${choice.label}${priceText}</option>`;
                });
                
                html += `
                        </select>
                    </div>
                `;
            });
        }
        
        // Total Price Display
        html += `
                        <div class="p-4 bg-primary-color text-white rounded mb-4">
                            <p class="text-sm mb-1">Łączna cena</p>
                            <p class="text-2xl font-bold calculated-price" data-product-id="${product.id}">
                                ${product.base_price.toFixed(2)} PLN
                            </p>
                        </div>
                        
                        <!-- Get Quote Button -->
                        <button class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded transition get-quote-btn" 
                                data-product-id="${product.id}" 
                                data-product-name="${product.name}">
                            📋 Uzyskaj Wycenę
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        div.innerHTML = html;
        
        // Add event listeners
        const toggleBtn = div.querySelector('.configurator-toggle');
        const configuratorForm = div.querySelector('.configurator-form');
        const optionSelects = div.querySelectorAll('.option-select');
        const getQuoteBtn = div.querySelector('.get-quote-btn');
        
        toggleBtn.addEventListener('click', function() {
            configuratorForm.classList.toggle('hidden');
            this.textContent = configuratorForm.classList.contains('hidden') ? '▼ Rozwiń konfigurator' : '▲ Zwiń konfigurator';
        });
        
        optionSelects.forEach(select => {
            select.addEventListener('change', function() {
                calculateProductPrice(product.id, product.base_price, div);
            });
        });
        
        getQuoteBtn.addEventListener('click', function() {
            const productName = this.getAttribute('data-product-name');
            const totalPrice = div.querySelector('.calculated-price').textContent.replace(' PLN', '');
            
            // Collect selected options
            const options = {};
            div.querySelectorAll('.option-select').forEach(select => {
                if (select.value) {
                    const optionKey = select.getAttribute('data-option-key');
                    options[optionKey] = select.value;
                }
            });
            
            // Redirect to contact page with product and price
            const queryParams = new URLSearchParams({
                product: productName,
                price: totalPrice,
                options: JSON.stringify(options)
            });
            
            window.location.href = `/mario-bramy/public/kontakt.php?${queryParams.toString()}`;
        });
        
        return div;
    }
    
    /**
     * Calculate total price for product
     */
    function calculateProductPrice(productId, basePrice, productElement) {
        let totalPrice = basePrice;
        
        // Sum price modifiers from selected options
        const optionSelects = productElement.querySelectorAll('.option-select');
        optionSelects.forEach(select => {
            if (select.value) {
                const selectedOption = select.querySelector(`option[value="${select.value}"]`);
                if (selectedOption) {
                    const priceMod = parseFloat(selectedOption.getAttribute('data-price-mod')) || 0;
                    totalPrice += priceMod;
                }
            }
        });
        
        // Update displayed price
        const priceElement = productElement.querySelector('.calculated-price[data-product-id="' + productId + '"]');
        if (priceElement) {
            priceElement.textContent = totalPrice.toFixed(2) + ' PLN';
        }
    }
});
