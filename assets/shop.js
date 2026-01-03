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

document.addEventListener('DOMContentLoaded', () => {
    renderShop();
    setupModal();
});

function renderShop() {
    const grid = document.getElementById('shop-grid');
    if (!grid) return;

    grid.innerHTML = products.map(product => `
        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100 hover:shadow-xl transition-all duration-300 group">
            <div class="h-64 overflow-hidden relative">
                <img src="${product.image}" alt="${product.name}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                <div class="absolute bottom-4 right-4 bg-[var(--primary-color)] text-white px-3 py-1 rounded-full text-sm font-bold">
                    od ${product.basePrice} PLN
                </div>
            </div>
            <div class="p-6">
                <div class="text-xs text-[var(--primary-color)] font-bold uppercase tracking-wider mb-2">${product.category}</div>
                <h3 class="text-xl font-bold text-[var(--dark-text-color)] mb-3">${product.name}</h3>
                <p class="text-[var(--gray-text-color)] text-sm mb-6 line-clamp-2">${product.description}</p>
                <button onclick="openConfigurator(${product.id})" class="w-full py-3 bg-transparent border-2 border-[var(--primary-color)] text-[var(--primary-color)] rounded-lg font-bold hover:bg-[var(--primary-color)] hover:text-white transition-all duration-300 flex items-center justify-center gap-2">
                    <i class="fa-solid fa-sliders"></i> Konfiguruj
                </button>
            </div>
        </div>
    `).join('');
}

let currentProduct = null;
let currentConfig = {};

window.openConfigurator = function(productId) {
    currentProduct = products.find(p => p.id === productId);
    if (!currentProduct) return;

    // Reset config
    currentConfig = {};
    Object.keys(currentProduct.options).forEach(key => {
        const opt = currentProduct.options[key];
        if (opt.type === 'select') currentConfig[key] = opt.choices[0].value;
        if (opt.type === 'checkbox') currentConfig[key] = false;
    });

    renderModalContent();
    document.getElementById('shopModal').style.display = 'flex';
    document.body.style.overflow = 'hidden'; // Prevent background scrolling
}

function renderModalContent() {
    const container = document.getElementById('modal-body-content');
    
    let html = `
        <div class="grid md:grid-cols-2 gap-8">
            <div>
                <img src="${currentProduct.image}" class="w-full h-64 md:h-full object-cover rounded-lg shadow-md" alt="${currentProduct.name}">
            </div>
            <div class="space-y-6">
                <div>
                    <h2 class="text-2xl font-bold text-[var(--dark-text-color)] mb-2">${currentProduct.name}</h2>
                    <p class="text-[var(--gray-text-color)]">${currentProduct.description}</p>
                </div>
                
                <div class="space-y-4 bg-gray-50 p-4 rounded-lg">
    `;

    // Generate Options
    Object.keys(currentProduct.options).forEach(key => {
        const option = currentProduct.options[key];
        html += `<div class="form-group">`;
        
        if (option.type === 'select') {
            html += `<label class="block text-sm font-bold text-[var(--dark-text-color)] mb-2">${option.label}</label>`;
            html += `<select onchange="updateConfig('${key}', this.value)" class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:border-[var(--primary-color)]">`;
            option.choices.forEach(choice => {
                const selected = currentConfig[key] === choice.value ? 'selected' : '';
                const priceInfo = choice.priceMod > 0 ? ` (+${choice.priceMod} PLN)` : '';
                html += `<option value="${choice.value}" ${selected}>${choice.label}${priceInfo}</option>`;
            });
            html += `</select>`;
        } else if (option.type === 'checkbox') {
            const checked = currentConfig[key] ? 'checked' : '';
            html += `
                <label class="flex items-center gap-3 cursor-pointer p-3 border border-gray-300 rounded-md hover:bg-white transition-colors">
                    <input type="checkbox" onchange="updateConfig('${key}', this.checked)" ${checked} class="w-5 h-5 text-[var(--primary-color)]">
                    <span class="text-sm font-bold text-[var(--dark-text-color)]">${option.label} (+${option.price} PLN)</span>
                </label>
            `;
        }
        html += `</div>`;
    });

    const totalPrice = calculatePrice();

    html += `
                </div>
                
                <div class="border-t pt-4 flex flex-col gap-4">
                    <div class="flex justify-between items-center text-xl font-bold text-[var(--dark-text-color)]">
                        <span>Szacowany koszt:</span>
                        <span class="text-[var(--primary-color)]">${totalPrice} PLN</span>
                    </div>
                    <a href="kontakt.html?product=${encodeURIComponent(currentProduct.name)}&price=${totalPrice}" class="w-full py-4 bg-[var(--primary-color)] text-white text-center rounded-lg font-bold hover:bg-[var(--accent-color)] transition-all shadow-lg hover:shadow-xl">
                        Zapytaj o ofertę
                    </a>
                </div>
            </div>
        </div>
    `;

    container.innerHTML = html;
}

window.updateConfig = function(key, value) {
    currentConfig[key] = value;
    renderModalContent(); // Re-render to update price
}

function calculatePrice() {
    let total = currentProduct.basePrice;
    
    Object.keys(currentProduct.options).forEach(key => {
        const option = currentProduct.options[key];
        const userValue = currentConfig[key];

        if (option.type === 'select') {
            const choice = option.choices.find(c => c.value == userValue); // weak equality for number/string matching
            if (choice) total += choice.priceMod;
        } else if (option.type === 'checkbox') {
            if (userValue) total += option.price;
        }
    });

    return total;
}

function setupModal() {
    const modal = document.getElementById('shopModal');
    const closeBtn = document.getElementById('closeShopModal');
    
    if (closeBtn) {
        closeBtn.onclick = () => {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        };
    }

    window.onclick = (event) => {
        if (event.target == modal) {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    }
}