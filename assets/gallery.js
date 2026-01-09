document.addEventListener('DOMContentLoaded', function() {
    // Data for portfolio galleries
    const portfolioData = {
        'bramy-przesuwne': [
            { src: 'https://via.placeholder.com/600x400/eeeeee/aaaaaa?text=Brama+Przesuwna+1', alt: 'Brama przesuwna aluminiowa 1' },
            { src: 'https://via.placeholder.com/600x400/eeeeee/aaaaaa?text=Brama+Przesuwna+2', alt: 'Brama przesuwna aluminiowa 2' },
            { src: 'https://via.placeholder.com/600x400/eeeeee/aaaaaa?text=Brama+Przesuwna+3', alt: 'Brama przesuwna aluminiowa 3' },
        ],
        'bramy-dwuskrzydlowe': [
            { src: 'https://via.placeholder.com/600x400/dddddd/999999?text=Brama+Dwuskrzydłowa+1', alt: 'Brama dwuskrzydłowa aluminiowa 1' },
            { src: 'https://via.placeholder.com/600x400/dddddd/999999?text=Brama+Dwuskrzydłowa+2', alt: 'Brama dwuskrzydłowa aluminiowa 2' },
        ],
        'barierki': [
            { src: 'https://via.placeholder.com/600x400/cccccc/888888?text=Barierka+1', alt: 'Barierka aluminiowa 1' },
            { src: 'https://via.placeholder.com/600x400/cccccc/888888?text=Barierka+2', alt: 'Barierka aluminiowa 2' },
            { src: 'https://via.placeholder.com/600x400/cccccc/888888?text=Barierka+3', alt: 'Barierka aluminiowa 3' },
        ],
        'przesla': [
            { src: 'https://via.placeholder.com/600x400/bbbbbb/777777?text=Przęsło+1', alt: 'Przęsło ogrodzeniowe aluminiowe 1' },
        ]
    };

    const modal = document.getElementById('galleryModal');
    if (!modal) return; // Exit if not on the gallery page

    const tabButtons = document.querySelectorAll('.gallery-tab-button');
    const tabPanels = document.querySelectorAll('.tab-panel');

    // --- RENDER GALLERIES ---
    function renderGalleries() {
        Object.keys(portfolioData).forEach(category => {
            const container = document.getElementById(`gallery-${category}`);
            if (container) {
                const images = portfolioData[category];
                container.innerHTML = images.map(img => `
                    <div class="gallery-item">
                        <img src="${img.src}" alt="${img.alt}" />
                    </div>
                `).join('');
            }
        });
    }

    // --- TAB LOGIC ---
    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            const category = button.dataset.category;

            // Update button active state
            tabButtons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');

            // Show/hide panels
            tabPanels.forEach(panel => {
                if (panel.id === `gallery-${category}`) {
                    panel.style.display = 'grid';
                } else {
                    panel.style.display = 'none';
                }
            });
        });
    });

    // --- MODAL LOGIC ---
    const modalImage = document.getElementById('modalImage');
    const closeModal = document.querySelector('.modal-close');

    function openModal(src, alt) {
        modal.style.display = 'flex';
        modalImage.src = src;
        modalImage.alt = alt;
    }

    function close() {
        modal.style.display = 'none';
    }

    document.addEventListener('click', function(e) {
        if (e.target.closest('.gallery-item img')) {
            const img = e.target.closest('.gallery-item img');
            openModal(img.src, img.alt);
        }
    });
    
    if (closeModal) {
        closeModal.addEventListener('click', close);
    }
    
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            close();
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal.style.display === 'flex') {
            close();
        }
    });

    // --- INITIALIZE ---
    renderGalleries();
});