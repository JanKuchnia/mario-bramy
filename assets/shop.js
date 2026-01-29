
let allProducts = [];
let activeProductId = null;
let currentConfigs = {}; // Store config for each product: { productId: { optionKey: value } }

document.addEventListener('DOMContentLoaded', () => {
    fetchProducts();
});

async function fetchProducts() {
    const grid = document.getElementById('products-grid');
    if (!grid) return;

    grid.innerHTML = '<div class="col-span-full py-12 text-center"><i class="fa-solid fa-spinner fa-spin text-4xl text-[var(--primary-color)]"></i></div>';

    try {
        const response = await fetch('api/products.php');
        const data = await response.json();

        if (data.success) {
            allProducts = data.products;

            // Initialize default configs
            allProducts.forEach(p => {
                currentConfigs[p.id] = {};
                if (p.options) {
                    Object.keys(p.options).forEach(key => {
                        const opt = p.options[key];
                        if (opt.type === 'select' && opt.choices && opt.choices.length > 0) {
                            currentConfigs[p.id][key] = opt.choices[0].value;
                        }
                        if (opt.type === 'checkbox') {
                            currentConfigs[p.id][key] = false;
                        }
                    });
                }
            });

            renderShop();
        } else {
            grid.innerHTML = '<div class="col-span-full text-center text-red-500">Błąd ładowania produktów.</div>';
        }
    } catch (e) {
        console.error(e);
        grid.innerHTML = '<div class="col-span-full text-center text-red-500">Wystąpił błąd połączenia.</div>';
    }
}

function renderShop() {
    const grid = document.getElementById('products-grid');
    if (!grid) return;

    if (allProducts.length === 0) {
        grid.innerHTML = '<div class="col-span-full text-center text-gray-500">Brak produktów.</div>';
        return;
    }

    grid.innerHTML = allProducts.map(product => {
        const isExpanded = activeProductId === product.id;
        // Use backend field names (base_price vs basePrice)
        const basePrice = parseFloat(product.base_price || product.basePrice);
        const currentPrice = calculatePrice(product, basePrice);
        const image = product.image ? product.image : 'assets/logo.png';

        return `
        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100 hover:shadow-xl transition-all duration-300 group flex flex-col h-full">
            <div class="h-64 overflow-hidden relative flex-shrink-0">
                <img src="${image}" alt="${product.name}" 
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                     onerror="this.src='assets/logo.png'; this.style.objectFit='contain'; this.style.padding='20px';"
                >
                <div class="absolute bottom-4 right-4 bg-[var(--primary-color)] text-white px-3 py-1 rounded-full text-sm font-bold shadow-md">
                    od ${formatPrice(basePrice)}
                </div>
            </div>
            
            <div class="p-6 flex flex-col flex-grow">
                <div class="text-xs text-[var(--primary-color)] font-bold uppercase tracking-wider mb-2">${formatCategory(product.category)}</div>
                <h3 class="text-xl font-bold text-[var(--dark-text-color)] mb-3">${product.name}</h3>
                <p class="text-[var(--gray-text-color)] text-sm mb-6 line-clamp-2 transition-all duration-300 ${isExpanded ? 'line-clamp-none' : ''}">
                    ${product.description || ''}
                </p>

                <!-- Configuration Section (Hidden by default) -->
                <div id="config-${product.id}" class="${isExpanded ? 'block' : 'hidden'} mt-4 space-y-4 border-t pt-4 border-gray-100 animate-fade-in">
                    ${renderOptions(product)}
                    
                    <div class="border-t pt-4 mt-4">
                         <div class="flex justify-between items-center text-lg font-bold text-[var(--dark-text-color)] mb-4">
                            <span>Suma:</span>
                            <span class="text-[var(--primary-color)] text-xl">${formatPrice(currentPrice)}</span>
                        </div>
                        <a href="kontakt.php?product=${encodeURIComponent(product.name)}&price=${currentPrice}" class="group relative flex items-center justify-center w-full py-4 bg-[var(--primary-color)] text-white text-center rounded-lg font-bold overflow-hidden shadow-lg hover:shadow-xl hover:scale-[1.02] transition-all duration-300">
                            <span class="absolute inset-0 w-full h-full bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:animate-shimmer"></span>
                            <span class="relative flex items-center gap-2">
                                <i class="fa-solid fa-envelope"></i>
                                Skontaktuj się z nami
                            </span>
                        </a>
                    </div>
                </div>

                <div class="mt-8 pt-8">
                    <button onclick="toggleConfigurator(${product.id})" class="w-full py-3 bg-white border-2 border-[var(--primary-color)] text-[var(--primary-color)] rounded-lg font-bold hover:bg-[var(--primary-color)] hover:text-white transition-all duration-300 flex items-center justify-center gap-2">
                        ${isExpanded
                ? '<i class="fa-solid fa-chevron-up"></i> Zwiń'
                : '<i class="fa-solid fa-sliders"></i> Konfiguruj'}
                    </button>
                </div>
            </div>
        </div>
    `}).join('');
}


function renderOptions(product) {
    if (!product.options) return '';

    let html = '';
    const config = currentConfigs[product.id];

    Object.keys(product.options).forEach(key => {
        const option = product.options[key];
        html += `<div class="form-group text-left">`;

        if (option.type === 'select') {
            html += `<label class="block text-sm font-bold text-[var(--dark-text-color)] mb-2">${option.label}</label>`;
            html += `<select onchange="updateConfig(${product.id}, '${key}', this.value)" class="w-full p-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:border-[var(--primary-color)] bg-gray-50">`;
            if (option.choices) {
                option.choices.forEach(choice => {
                    // Check if choice is object or simple value
                    const val = choice.value !== undefined ? choice.value : choice;
                    const label = choice.label || choice;
                    const priceMod = choice.priceMod || 0;

                    const selected = config[key] == val ? 'selected' : '';
                    const priceInfo = priceMod > 0 ? ` (+${priceMod} PLN)` : '';
                    html += `<option value="${val}" ${selected}>${label}${priceInfo}</option>`;
                });
            }
            html += `</select>`;
        } else if (option.type === 'checkbox') {
            const checked = config[key] ? 'checked' : '';
            html += `
                <label class="flex items-center gap-3 cursor-pointer p-2 border border-gray-200 rounded-md hover:bg-gray-50 transition-colors">
                    <input type="checkbox" onchange="updateConfig(${product.id}, '${key}', this.checked)" ${checked} class="w-4 h-4 text-[var(--primary-color)] rounded border-gray-300 focus:ring-[var(--primary-color)]">
                    <span class="text-sm font-semibold text-[var(--dark-text-color)]">${option.label} (+${option.price} PLN)</span>
                </label>
            `;
        }
        html += `</div>`;
    });
    return html;
}

window.toggleConfigurator = function (productId) {
    if (activeProductId === productId) {
        activeProductId = null; // Collapse if already open
    } else {
        activeProductId = productId; // Expand new one
    }
    renderShop(); // Re-render to update UI
}

window.updateConfig = function (productId, key, value) {
    currentConfigs[productId][key] = value;
    renderShop(); // Re-render to update price and state
}

function calculatePrice(product, basePrice) {
    let total = basePrice;
    const config = currentConfigs[product.id];

    if (product.options) {
        Object.keys(product.options).forEach(key => {
            const option = product.options[key];
            const userValue = config[key];

            if (option.type === 'select' && option.choices) {
                const choice = option.choices.find(c => (c.value !== undefined ? c.value : c) == userValue);
                if (choice && choice.priceMod) total += parseFloat(choice.priceMod);
            } else if (option.type === 'checkbox') {
                if (userValue) total += parseFloat(option.price || 0);
            }
        });
    }

    return total;
}

function formatPrice(price) {
    return new Intl.NumberFormat('pl-PL', { style: 'currency', currency: 'PLN', maximumFractionDigits: 0 }).format(price);
}

function formatCategory(cat) {
    const map = {
        'bramy': 'Bramy',
        'ogrodzenia': 'Ogrodzenia',
        'balustrady': 'Balustrady',
        'automatyka': 'Automatyka'
    };
    return map[cat] || cat;
}