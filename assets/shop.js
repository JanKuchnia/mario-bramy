const products = [
    {
        id: 1,
        name: "Brama Przesuwna Modern",
        category: "bramy",
        basePrice: 3500,
        image: "assets/img_98.avif",
        description: "Nowoczesna brama przesuwna o konstrukcji samonośnej. Idealna do posesji prywatnych i firmowych.",
        options: {
            width: {
                label: "Szerokość (m)",
                type: "select",
                choices: [
                    { label: "3.5m", value: 3.5, priceMod: 0 },
                    { label: "4.0m", value: 4.0, priceMod: 500 },
                    { label: "4.5m", value: 4.5, priceMod: 1000 },
                    { label: "5.0m", value: 5.0, priceMod: 1600 }
                ]
            },
            color: {
                label: "Kolor (RAL)",
                type: "select",
                choices: [
                    { label: "Antracyt (7016)", value: "7016", priceMod: 0 },
                    { label: "Czarny (9005)", value: "9005", priceMod: 0 },
                    { label: "Brąz (8017)", value: "8017", priceMod: 0 },
                    { label: "Kolor niestandardowy", value: "custom", priceMod: 300 }
                ]
            },
            automation: {
                label: "Automatyka",
                type: "select",
                choices: [
                    { label: "Bez napędu", value: "none", priceMod: 0 },
                    { label: "Napęd Standard (do 500kg)", value: "std", priceMod: 1200 },
                    { label: "Napęd Premium (Smart WiFi)", value: "smart", priceMod: 1800 }
                ]
            }
        }
    },
    {
        id: 2,
        name: "Brama Dwuskrzydłowa Classic",
        category: "bramy",
        basePrice: 2800,
        image: "assets/img_94.avif",
        description: "Klasyczna elegancja. Brama dwuskrzydłowa z wypełnieniem palisadowym.",
        options: {
            width: {
                label: "Szerokość całkowita (m)",
                type: "select",
                choices: [
                    { label: "3.0m", value: 3.0, priceMod: 0 },
                    { label: "3.5m", value: 3.5, priceMod: 400 },
                    { label: "4.0m", value: 4.0, priceMod: 800 }
                ]
            },
            automation: {
                label: "Automatyka",
                type: "select",
                choices: [
                    { label: "Bez napędu", value: "none", priceMod: 0 },
                    { label: "Zestaw siłowników Standard", value: "std", priceMod: 1500 },
                    { label: "Zestaw siłowników Premium", value: "smart", priceMod: 2200 }
                ]
            }
        }
    },
    {
        id: 3,
        name: "Wideodomofon Smart",
        category: "akcesoria",
        basePrice: 800,
        image: "assets/img_97.webp",
        description: "Wideodomofon z obsługą przez aplikację mobilną i czytnikiem linii papilarnych.",
        options: {
            screenSize: {
                label: "Wielkość ekranu",
                type: "select",
                choices: [
                    { label: "7 cali", value: 7, priceMod: 0 },
                    { label: "10 cali", value: 10, priceMod: 300 }
                ]
            },
            installation: {
                label: "Usługa montażu",
                type: "checkbox",
                price: 400
            }
        }
    },
    {
        id: 4,
        name: "Zamek Biometryczny",
        category: "akcesoria",
        basePrice: 1200,
        image: "assets/img_96.avif",
        description: "Zamek otwierany na odcisk palca, kod PIN lub aplikację.",
        options: {
            color: {
                label: "Wariant",
                type: "select",
                choices: [
                    { label: "Srebrny", value: "silver", priceMod: 0 },
                    { label: "Czarny Mat", value: "black", priceMod: 100 }
                ]
            }
        }
    }
];

// Determine if we should show 'Konfiguruj' or 'Zwiń'
let activeProductId = null;
let currentConfigs = {}; // Store config for each product: { productId: { optionKey: value } }

document.addEventListener('DOMContentLoaded', () => {
    // Initialize default configs
    products.forEach(p => {
        currentConfigs[p.id] = {};
        Object.keys(p.options).forEach(key => {
            const opt = p.options[key];
            if (opt.type === 'select') currentConfigs[p.id][key] = opt.choices[0].value;
            if (opt.type === 'checkbox') currentConfigs[p.id][key] = false;
        });
    });
    renderShop();
});

function renderShop() {
    const grid = document.getElementById('shop-grid');
    if (!grid) return;

    grid.innerHTML = products.map(product => {
        const isExpanded = activeProductId === product.id;
        const currentPrice = calculatePrice(product);

        return `
        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100 hover:shadow-xl transition-all duration-300 group flex flex-col h-full">
            <div class="h-64 overflow-hidden relative flex-shrink-0">
                <img src="${product.image}" alt="${product.name}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                <div class="absolute bottom-4 right-4 bg-[var(--primary-color)] text-white px-3 py-1 rounded-full text-sm font-bold shadow-md">
                    od ${product.basePrice} PLN
                </div>
            </div>
            
            <div class="p-6 flex flex-col flex-grow">
                <div class="text-xs text-[var(--primary-color)] font-bold uppercase tracking-wider mb-2">${product.category}</div>
                <h3 class="text-xl font-bold text-[var(--dark-text-color)] mb-3">${product.name}</h3>
                <p class="text-[var(--gray-text-color)] text-sm mb-6 line-clamp-2 transition-all duration-300 ${isExpanded ? 'line-clamp-none' : ''}">
                    ${product.description}
                </p>

                <!-- Configuration Section (Hidden by default) -->
                <div id="config-${product.id}" class="${isExpanded ? 'block' : 'hidden'} mt-4 space-y-4 border-t pt-4 border-gray-100 animate-fade-in">
                    ${renderOptions(product)}
                    
                    <div class="border-t pt-4 mt-4">
                         <div class="flex justify-between items-center text-lg font-bold text-[var(--dark-text-color)] mb-4">
                            <span>Suma:</span>
                            <span class="text-[var(--primary-color)] text-xl">${currentPrice} PLN</span>
                        </div>
                        <a href="kontakt.html?product=${encodeURIComponent(product.name)}&price=${currentPrice}" class="group relative flex items-center justify-center w-full py-4 bg-[var(--primary-color)] text-white text-center rounded-lg font-bold overflow-hidden shadow-lg hover:shadow-xl hover:scale-[1.02] transition-all duration-300">
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
    let html = '';
    const config = currentConfigs[product.id];

    Object.keys(product.options).forEach(key => {
        const option = product.options[key];
        html += `<div class="form-group text-left">`;
        
        if (option.type === 'select') {
            html += `<label class="block text-sm font-bold text-[var(--dark-text-color)] mb-2">${option.label}</label>`;
            html += `<select onchange="updateConfig(${product.id}, '${key}', this.value)" class="w-full p-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:border-[var(--primary-color)] bg-gray-50">`;
            option.choices.forEach(choice => {
                const selected = config[key] == choice.value ? 'selected' : '';
                const priceInfo = choice.priceMod > 0 ? ` (+${choice.priceMod} PLN)` : '';
                html += `<option value="${choice.value}" ${selected}>${choice.label}${priceInfo}</option>`;
            });
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

window.toggleConfigurator = function(productId) {
    if (activeProductId === productId) {
        activeProductId = null; // Collapse if already open
    } else {
        activeProductId = productId; // Expand new one
    }
    renderShop(); // Re-render to update UI
}

window.updateConfig = function(productId, key, value) {
    currentConfigs[productId][key] = value;
    renderShop(); // Re-render to update price and state
}

function calculatePrice(product) {
    let total = product.basePrice;
    const config = currentConfigs[product.id];
    
    Object.keys(product.options).forEach(key => {
        const option = product.options[key];
        const userValue = config[key];

        if (option.type === 'select') {
            const choice = option.choices.find(c => c.value == userValue);
            if (choice) total += choice.priceMod;
        } else if (option.type === 'checkbox') {
            if (userValue) total += option.price;
        }
    });

    return total;
}