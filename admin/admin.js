document.addEventListener('DOMContentLoaded', function() {
    
    // --- Authentication ---
    const correctPassword = "password123"; 
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
            item.innerHTML = `
                <div class="delete-btn" onclick="deletePhoto(${index})" title="Usuń zdjęcie">
                    <i class="fa-solid fa-trash-can"></i>
                </div>
                <img src="${photo.src}" alt="${photo.name}">
                <div class="item-info">
                    <span class="text-xs text-gray-400">${photo.cat}</span><br>
                    <span class="filename-badge">${photo.name}</span>
                </div>
            `;
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

    renderGallery();
});