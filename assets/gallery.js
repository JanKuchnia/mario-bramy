
document.addEventListener('DOMContentLoaded', function () {

    // --- UI References ---
    const modal = document.getElementById('galleryModal');
    if (!modal) return; // Exit if not on the gallery page

    const tabButtons = document.querySelectorAll('.gallery-tab-button');
    const tabPanels = document.querySelectorAll('.tab-panel');
    const modalImage = document.getElementById('modalImage');
    const closeModal = document.querySelector('.modal-close');

    // --- FETCH & RENDER LOGIC ---

    async function loadCategoryImages(category) {
        const container = document.getElementById(`gallery-${category}`);
        if (!container) return; // Container doesn't exist for this category

        // Show loading state
        container.innerHTML = '<div class="col-span-full text-center py-12"><i class="fa-solid fa-spinner fa-spin text-4xl text-[var(--primary-color)]"></i></div>';

        try {
            // Fetch directly from API
            // Note: If category is 'all', we might want to fetch everything or handle it differently
            // For 'all', let's fetch individual categories and merge, or adjust API to support 'all'
            // Our current API supports ?category=xyz. If omitted, it returns all images.

            const apiUrl = category === 'all'
                ? 'api/gallery.php'
                : `api/gallery.php?category=${category}`;

            const response = await fetch(apiUrl);

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();

            if (!data.success) {
                throw new Error(data.error || 'Unknown error');
            }

            renderImages(container, data.images);
            container.dataset.loaded = 'true';

        } catch (error) {
            console.error("Error loading gallery:", error);
            container.innerHTML = `<div class="col-span-full text-center py-10 text-red-500">Nie udało się załadować zdjęć. Spróbuj odświeżyć stronę.</div>`;
        }
    }

    function renderImages(container, images) {
        if (!images || images.length === 0) {
            container.innerHTML = '<div class="col-span-full text-center py-10 text-gray-500">Brak zdjęć w tej kategorii.</div>';
            return;
        }

        container.innerHTML = images.map(img => `
            <div class="gallery-item">
                <img src="${img.src}" alt="${img.name}" loading="lazy" />

            </div>
        `).join('');

        // Add 'tall' class logic
        const imgElements = container.querySelectorAll('img');
        imgElements.forEach(img => {
            if (img.complete) {
                checkAspectRatio(img);
            } else {
                img.onload = function () {
                    checkAspectRatio(this);
                }
            }
        });
    }

    function checkAspectRatio(img) {
        if (img.naturalHeight > img.naturalWidth) {
            img.parentElement.classList.add('tall');
        }
    }

    // --- TAB HANDLING ---

    // Initial State Check
    const urlParams = new URLSearchParams(window.location.search);
    let initialCategory = urlParams.get('category') || 'all';

    // Set active button
    tabButtons.forEach(btn => {
        if (btn.dataset.category === initialCategory) {
            btn.classList.add('active');
        } else {
            btn.classList.remove('active');
        }
    });

    // Show correct panel
    tabPanels.forEach(panel => {
        // Hide all first
        panel.style.display = 'none';

        // If 'all', show the 'all' panel container
        if (initialCategory === 'all' && panel.id === 'gallery-all') {
            panel.style.display = 'grid';
        }
        // If specific category, show that specific panel
        else if (panel.id === `gallery-${initialCategory}`) {
            panel.style.display = 'grid';
        }
    });

    // Load initial data
    loadCategoryImages(initialCategory);

    // Click Handlers
    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            const category = button.dataset.category;

            // Handle UI State
            tabButtons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');

            tabPanels.forEach(panel => panel.style.display = 'none');

            const targetPanel = document.getElementById(`gallery-${category}`);
            if (targetPanel) {
                targetPanel.style.display = 'grid';

                // Only load if not already loaded
                if (!targetPanel.dataset.loaded) {
                    loadCategoryImages(category);
                }
            }

            // Update URL without reload
            const newUrl = new URL(window.location);
            if (category === 'all') {
                newUrl.searchParams.delete('category');
            } else {
                newUrl.searchParams.set('category', category);
            }
            window.history.pushState({}, '', newUrl);
        });
    });

    // --- MODAL LOGIC ---

    function openModal(src, alt) {
        modal.style.display = 'flex';
        modalImage.src = src;
        modalImage.alt = alt;
        document.body.style.overflow = 'hidden'; // Prevent scrolling
    }

    function closeModalFunc() {
        modal.style.display = 'none';
        document.body.style.overflow = ''; // Restore scrolling
    }

    // Event Delegation for dynamic images
    document.addEventListener('click', function (e) {
        const item = e.target.closest('.gallery-item');
        if (item) {
            const img = item.querySelector('img');
            if (img) {
                openModal(img.src, img.alt);
            }
        }
    });

    if (closeModal) closeModal.addEventListener('click', closeModalFunc);

    modal.addEventListener('click', function (e) {
        if (e.target === modal) closeModalFunc();
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && modal.style.display === 'flex') {
            closeModalFunc();
        }
    });
});