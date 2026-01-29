
document.addEventListener('DOMContentLoaded', function () {
    const productsGrid = document.getElementById('products-grid');
    const categoryFilters = document.querySelectorAll('.shop-filter-btn');

    if (!productsGrid) return; // Exit if not on shop page

    // --- FETCH PRODUCTS ---
    async function fetchProducts() {
        productsGrid.innerHTML = '<div class="col-span-full text-center py-12"><i class="fa-solid fa-spinner fa-spin text-4xl text-[var(--primary-color)]"></i></div>';

        try {
            const response = await fetch('api/products.php');
            if (!response.ok) throw new Error('Network error');

            const data = await response.json();

            if (!data.success) {
                throw new Error(data.error || 'Failed to load products');
            }

            // Store products globally for filtering
            window.allProducts = data.products;

            renderProducts(window.allProducts);

        } catch (error) {
            console.error('Error:', error);
            productsGrid.innerHTML = '<div class="col-span-full text-center py-12 text-red-500">Nie udało się załadować produktów. Spróbuj ponownie później.</div>';
        }
    }

    // --- RENDER PRODUCTS ---
    function renderProducts(products) {
        if (!products || products.length === 0) {
            productsGrid.innerHTML = '<div class="col-span-full text-center py-12 text-gray-500">Brak dostępnych produktów w tej kategorii.</div>';
            return;
        }

        productsGrid.innerHTML = products.map(product => `
            <div class="product-card bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 border border-gray-100 group">
                <div class="relative overflow-hidden h-64 bg-gray-100 items-center justify-center flex">
                     <img 
                        src="${product.image ? product.image : 'assets/logo.png'}" 
                        alt="${product.name}" 
                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                        onerror="this.src='assets/logo.png'; this.classList.add('p-8'); this.classList.remove('object-cover'); this.classList.add('object-contain');"
                    >
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-300 flex items-center justify-center opacity-0 group-hover:opacity-100">
                        <button onclick="openProductModal(${product.id})" class="bg-white text-[var(--primary-color)] px-6 py-2 rounded-full font-bold shadow-lg transform translate-y-4 group-hover:translate-y-0 transition-all duration-300 hover:bg-[var(--primary-color)] hover:text-white">
                            Szczegóły
                        </button>
                    </div>
                </div>
                <div class="p-6">
                    <div class="text-xs font-bold text-[var(--primary-color)] uppercase tracking-wider mb-2">${formatCategory(product.category)}</div>
                    <h3 class="text-xl font-bold text-[var(--dark-text-color)] mb-3 line-clamp-2 min-h-[3.5rem]">${product.name}</h3>
                    <div class="flex justify-between items-center mt-4">
                        <div class="text-2xl font-bold text-[var(--dark-text-color)]">${formatPrice(product.base_price || product.basePrice)}</div>
                        <button onclick="openProductModal(${product.id})" class="w-10 h-10 rounded-full bg-gray-100 text-[var(--dark-text-color)] flex items-center justify-center hover:bg-[var(--primary-color)] hover:text-white transition-colors duration-300">
                            <i class="fa-solid fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        `).join('');
    }

    // --- FILTERS ---
    categoryFilters.forEach(btn => {
        btn.addEventListener('click', () => {
            // UI Update
            categoryFilters.forEach(b => b.classList.remove('active', 'bg-[var(--primary-color)]', 'text-white'));
            categoryFilters.forEach(b => b.classList.add('bg-white', 'text-[var(--dark-text-color)]'));

            btn.classList.remove('bg-white', 'text-[var(--dark-text-color)]');
            btn.classList.add('active', 'bg-[var(--primary-color)]', 'text-white');

            const category = btn.dataset.category;

            if (category === 'all') {
                renderProducts(window.allProducts);
            } else {
                const filtered = window.allProducts.filter(p => p.category === category);
                renderProducts(filtered);
            }
        });
    });

    // --- HELPER FUNCTIONS ---
    function formatPrice(price) {
        return new Intl.NumberFormat('pl-PL', { style: 'currency', currency: 'PLN' }).format(price);
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

    // --- INITIAL LOAD ---
    fetchProducts();
});

// --- PRODUCT MODAL LOGIC (Global Scope) ---
function openProductModal(id) {
    const product = window.allProducts.find(p => p.id === id);
    if (!product) return;

    // Create modal HTML dynamically
    const modalHtml = `
        <div id="product-modal-backdrop" class="fixed inset-0 bg-black bg-opacity-80 z-[60] flex items-center justify-center p-4 backdrop-blur-sm animate-fade-in" onclick="closeProductModal(event)">
            <div class="bg-white rounded-2xl max-w-5xl w-full max-h-[90vh] overflow-y-auto shadow-2xl flex flex-col lg:flex-row relative animate-scale-in" onclick="event.stopPropagation()">
                <button onclick="closeProductModal()" class="absolute top-4 right-4 z-10 w-10 h-10 bg-white rounded-full shadow-md flex items-center justify-center text-gray-500 hover:text-red-500 transition-colors">
                    <i class="fa-solid fa-times text-xl"></i>
                </button>
                
                <div class="lg:w-1/2 relative bg-gray-100 min-h-[300px] lg:min-h-full">
                     <img 
                        src="${product.image ? product.image : 'assets/logo.png'}" 
                        alt="${product.name}" 
                        class="w-full h-full object-cover absolute inset-0"
                        onerror="this.src='assets/logo.png'; this.style.objectFit='contain'; this.style.padding='40px';"
                    >
                </div>
                
                <div class="lg:w-1/2 p-8 lg:p-12 flex flex-col">
                    <div class="text-sm font-bold text-[var(--primary-color)] uppercase tracking-wider mb-2">${product.category}</div>
                    <h2 class="text-3xl lg:text-4xl font-bold text-[var(--dark-text-color)] mb-4 font-[var(--font-family-heading)]">${product.name}</h2>
                    
                    <div class="text-3xl font-bold text-[var(--primary-color)] mb-6">
                        ${product.base_price || product.basePrice} PLN <span class="text-base text-gray-400 font-normal">/ cena podstawowa</span>
                    </div>
                    
                    <div class="prose text-gray-600 mb-8 leading-relaxed">
                        ${product.description || 'Brak opisu produktu.'}
                    </div>
                    
                    ${renderProductOptions(product.options)}
                    
                    <div class="mt-auto pt-8 border-t border-gray-100">
                        <div class="flex flex-col sm:flex-row gap-4">
                            <a href="tel:+48668197170" class="flex-1 bg-[var(--primary-color)] text-white py-4 rounded-xl font-bold text-center hover:bg-opacity-90 transition-all shadow-lg hover:shadow-xl flex items-center justify-center gap-3">
                                <i class="fa-solid fa-phone"></i> Zamów Telefonicznie
                            </a>
                            <a href="kontakt.php?product=${encodeURIComponent(product.name)}" class="flex-1 bg-white border-2 border-[var(--primary-color)] text-[var(--primary-color)] py-4 rounded-xl font-bold text-center hover:bg-gray-50 transition-all flex items-center justify-center gap-3">
                                <i class="fa-solid fa-envelope"></i> Zapytaj o Produkt
                            </a>
                        </div>
                        <p class="text-xs text-center text-gray-400 mt-4">Podana cena jest ceną netto. Koszt montażu wyceniany indywidualnie.</p>
                    </div>
                </div>
            </div>
        </div>
    `;

    // Append to body
    const div = document.createElement('div');
    div.innerHTML = modalHtml;
    document.body.appendChild(div.firstElementChild);
    document.body.style.overflow = 'hidden';
}

function closeProductModal(e) {
    if (e && e.target.id !== 'product-modal-backdrop' && !e.target.closest('button')) return;

    const modal = document.getElementById('product-modal-backdrop');
    if (modal) {
        modal.classList.add('opacity-0');
        setTimeout(() => {
            modal.remove();
            document.body.style.overflow = '';
        }, 300);
    }
}

function renderProductOptions(options) {
    if (!options || Object.keys(options).length === 0) return '';

    let html = '<div class="space-y-4 mb-8 bg-gray-50 p-6 rounded-xl border border-gray-100">';
    html += '<h4 class="font-bold text-gray-800 mb-2">Dostępne konfiguracje:</h4>';

    for (const [key, opt] of Object.entries(options)) {
        html += `<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-gray-200 last:border-0 pb-3 last:pb-0">
            <span class="text-gray-600 font-medium">${opt.label}:</span>
            <span class="text-gray-800 text-sm">
                ${opt.type === 'checkbox' ? 'Opcjonalnie (+ ' + opt.price + ' PLN)' : 'Wybór wariantów'}
            </span>
        </div>`;
    }

    html += '</div>';
    return html;
}

// Add CSS for animations
const style = document.createElement('style');
style.textContent = `
    @keyframes fade-in { from { opacity: 0; } to { opacity: 1; } }
    @keyframes scale-in { from {transform: scale(0.95); opacity: 0;} to {transform: scale(1); opacity: 1;} }
    .animate-fade-in { animation: fade-in 0.3s ease-out; }
    .animate-scale-in { animation: scale-in 0.3s ease-out; }
`;
document.head.appendChild(style);