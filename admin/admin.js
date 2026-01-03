
document.addEventListener('DOMContentLoaded', function() {
    
    // --- Authentication ---
    const correctPassword = "password123"; // This should be replaced with a secure backend solution
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
    // Protect the panel page
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

    // Simulate loading existing photos
    const existingPhotos = [
        { src: '../assets/img_95.avif', alt: 'A modern, sleek automated gate operator' },
        { src: '../assets/img_96.avif', alt: 'Finger pressing modern digital door lock' },
        { src: '../assets/img_97.webp', alt: 'Intercom in apartment' },
        { src: '../assets/img_98.avif', alt: 'A motorized sliding gate made of black metal' }
    ];

    function renderGallery() {
        if (!galleryContainer) return;
        galleryContainer.innerHTML = '';
        existingPhotos.forEach(photo => {
            const galleryItem = document.createElement('div');
            galleryItem.className = 'gallery-item';
            galleryItem.innerHTML = `<img src="${photo.src}" alt="${photo.alt}">`;
            galleryContainer.appendChild(galleryItem);
        });
    }
    
    if (uploadForm) {
        uploadForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const altText = document.getElementById('photoAlt').value;
            
            if (photoFileInput.files && photoFileInput.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const newPhoto = { src: e.target.result, alt: altText };
                    existingPhotos.push(newPhoto);
                    renderGallery();
                    uploadForm.reset();
                    alert("Zdjęcie dodane (symulacja). Odśwież stronę galerii, aby zobaczyć zmiany.");
                }
                reader.readAsDataURL(photoFileInput.files[0]);
            }
        });
    }

    // Initial render
    renderGallery();
});
