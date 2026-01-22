document.addEventListener('DOMContentLoaded', function() {
    // Data for portfolio galleries
    const portfolioData = {
        'bramy-przesuwne-aluminiowe': [
            { src: 'assets/portfolio/bramy-przesuwne-aluminiowe/1.jpg', alt: 'Brama przesuwna aluminiowa 1' },
            { src: 'assets/portfolio/bramy-przesuwne-aluminiowe/2.jpg', alt: 'Brama przesuwna aluminiowa 2' },
            { src: 'assets/portfolio/bramy-przesuwne-aluminiowe/3.jpg', alt: 'Brama przesuwna aluminiowa 3' },
        ],
        'bramy-dwuskrzydlowe': [
            { src: 'assets/portfolio/bramy-dwuskrzydlowe/1.jpg', alt: 'Brama dwuskrzydłowa aluminiowa 1' },
            { src: 'assets/portfolio/bramy-dwuskrzydlowe/2.jpg', alt: 'Brama dwuskrzydłowa aluminiowa 2' },
            { src: 'assets/portfolio/bramy-dwuskrzydlowe/3.jpg', alt: 'Brama dwuskrzydłowa aluminiowa 3' },
            { src: 'assets/portfolio/bramy-dwuskrzydlowe/4.jpg', alt: 'Brama dwuskrzydłowa aluminiowa 4' },
        ],
        'balustrady': [
            { src: 'assets/portfolio/barierki/1.jpg', alt: 'Balustrada aluminiowa 1' },
            { src: 'assets/portfolio/barierki/2.jpg', alt: 'Balustrada aluminiowa 2' },
            { src: 'assets/portfolio/barierki/3.jpg', alt: 'Balustrada aluminiowa 3' },
            { src: 'assets/portfolio/barierki/4.jpg', alt: 'Balustrada aluminiowa 4' },
            { src: 'assets/portfolio/barierki/5.jpg', alt: 'Balustrada aluminiowa 5' },
        ],
        'automatyka': [],
        'bramy-garazowe': [],
        'przesla-ogrodzeniowe-aluminiowe': [
            { src: 'assets/portfolio/przesla-ogrodzeniowe-aluminiowe/1.jpg', alt: 'Przęsło ogrodzeniowe aluminiowe 1' },
            { src: 'assets/portfolio/przesla-ogrodzeniowe-aluminiowe/2.jpg', alt: 'Przęsło ogrodzeniowe aluminiowe 2' },
        ]
    };

    // Combine all categories into an 'all' category for the "Wszystko" tab
    portfolioData.all = Object.keys(portfolioData).reduce((acc, category) => {
        return acc.concat(portfolioData[category]);
    }, []);

    const modal = document.getElementById('galleryModal');
    if (!modal) return; // Exit if not on the gallery page

    const tabButtons = document.querySelectorAll('.gallery-tab-button');
    const tabPanels = document.querySelectorAll('.tab-panel');

    // --- RENDER IMAGES FOR A SPECIFIC CATEGORY ---
    function renderCategoryImages(category) {
        const container = document.getElementById(`gallery-${category}`);
        if (container) {
            const images = portfolioData[category];
            container.innerHTML = images.map(img => `
                <div class="gallery-item">
                    <img src="${img.src}" alt="${img.alt}" loading="lazy" />
                </div>
            `).join('');

            // Add 'tall' class to portrait images after they load
            const imgElements = container.querySelectorAll('img');
            imgElements.forEach(img => {
                img.onload = function() {
                    if (this.naturalHeight > this.naturalWidth) {
                        this.parentElement.classList.add('tall');
                    }
                }
            });
        }
    }

    // --- INITIAL RENDER GALLERIES ---
    function renderGalleries() {
        // Initial render for all categories (placeholder, won't be used after dynamic loading)
        // This function will be refactored to only render the active category initially
    }

    // --- INITIALIZE ---
    // Find the initially active category
    const initialActiveButton = document.querySelector('.gallery-tab-button.active');
    
    // Check for query parameter
    const urlParams = new URLSearchParams(window.location.search);
    const categoryParam = urlParams.get('category');
    
    let initialCategory = 'all'; // Default
    
    if (categoryParam && portfolioData[categoryParam]) {
        initialCategory = categoryParam;
        
        // Update active button state
        if (initialActiveButton) {
             initialActiveButton.classList.remove('active');
        }
        const newActiveButton = document.querySelector(`.gallery-tab-button[data-category="${categoryParam}"]`);
        if (newActiveButton) {
            newActiveButton.classList.add('active');
        }
    } else if (initialActiveButton) {
        initialCategory = initialActiveButton.dataset.category;
    }

    // Render only the initially active category
    renderCategoryImages(initialCategory);

    // Set initial display state for panels
    tabPanels.forEach(panel => {
        if (panel.id === `gallery-${initialCategory}`) {
            panel.style.display = 'grid';
        } else {
            panel.style.display = 'none';
        }
    });

    // --- TAB LOGIC ---
    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            const category = button.dataset.category;

            // Update button active state
            tabButtons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');

            // Render images for the selected category
            renderCategoryImages(category);

            // Show/hide panels
            tabPanels.forEach(panel => {
                if (category === 'all') {
                    // Display all panels if 'all' category is selected
                    panel.style.display = 'grid';
                } else {
                    // Otherwise, display only the panel corresponding to the selected category
                    if (panel.id === `gallery-${category}`) {
                        panel.style.display = 'grid';
                    } else {
                        panel.style.display = 'none';
                    }
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