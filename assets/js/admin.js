/**
 * Admin Panel JavaScript
 * Handles: Gallery upload, Products CRUD, Messages view, etc.
 */

document.addEventListener('DOMContentLoaded', function() {
    // Get current section from URL
    const urlParams = new URLSearchParams(window.location.search);
    const section = urlParams.get('section') || 'dashboard';
    
    // Initialize section
    if (section === 'gallery') {
        initGallerySection();
    } else if (section === 'products') {
        initProductsSection();
    } else if (section === 'messages') {
        initMessagesSection();
    }
});

/**
 * Initialize Gallery Section
 */
function initGallerySection() {
    const uploadForm = document.getElementById('gallery-upload-form');
    const imageFile = document.getElementById('image-file');
    const preview = document.getElementById('image-preview');
    const previewImg = document.getElementById('preview-img');
    const imagesList = document.getElementById('images-list');
    
    // Image preview
    if (imageFile) {
        imageFile.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    previewImg.src = event.target.result;
                    preview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        });
    }
    
    // Form submission
    if (uploadForm) {
        uploadForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('/mario-bramy/admin/api/gallery-upload.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Zdjęcie zostało przesłane!');
                    uploadForm.reset();
                    preview.classList.add('hidden');
                    loadGalleryImages();
                } else {
                    alert('Błąd: ' + (data.message || 'Nie udało się przesłać zdjęcia'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Błąd przy przesyłaniu pliku');
            });
        });
    }
    
    // Load existing images
    loadGalleryImages();
}

/**
 * Load gallery images
 */
function loadGalleryImages() {
    const imagesList = document.getElementById('images-list');
    
    fetch('/mario-bramy/admin/api/gallery-list.php')
        .then(response => response.json())
        .then(data => {
            if (data.success && data.images.length > 0) {
                imagesList.innerHTML = data.images.map(img => `
                    <div class="relative group">
                        <img src="/mario-bramy/uploads/portfolio/${img.category_slug}/${img.filename}" alt="${img.alt_text}" class="w-full h-40 object-cover rounded">
                        <button class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition flex items-center justify-center" 
                                onclick="deleteGalleryImage(${img.id})">
                            <span class="text-white font-bold">Usuń</span>
                        </button>
                    </div>
                `).join('');
            } else {
                imagesList.innerHTML = '<p class="col-span-full text-gray-600">Brak zdjęć w galerii</p>';
            }
        })
        .catch(error => console.error('Error loading images:', error));
}

/**
 * Delete gallery image
 */
function deleteGalleryImage(imageId) {
    if (confirm('Czy na pewno chcesz usunąć to zdjęcie?')) {
        fetch('/mario-bramy/admin/api/gallery-delete.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ image_id: imageId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Zdjęcie usunięte!');
                loadGalleryImages();
            } else {
                alert('Błąd: ' + (data.message || 'Nie udało się usunąć zdjęcia'));
            }
        })
        .catch(error => console.error('Error:', error));
    }
}

/**
 * Initialize Products Section
 */
function initProductsSection() {
    const addProductBtn = document.getElementById('add-product-btn');
    const productsList = document.getElementById('products-list');
    
    if (addProductBtn) {
        addProductBtn.addEventListener('click', function() {
            alert('Funkcja dodawania produktu będzie dostępna w dalszych wersjach');
        });
    }
    
    // Load products
    loadProducts();
}

/**
 * Load products
 */
function loadProducts() {
    const productsList = document.getElementById('products-list');
    
    fetch('/mario-bramy/api/products.php')
        .then(response => response.json())
        .then(data => {
            if (data.success && data.products.length > 0) {
                productsList.innerHTML = data.products.map(product => `
                    <div class="border border-gray-300 rounded p-4">
                        <h4 class="text-xl font-bold mb-2">${product.name}</h4>
                        <p class="text-gray-600 mb-4">Cena bazowa: <strong>${product.base_price.toFixed(2)} PLN</strong></p>
                        <div class="space-y-2">
                            <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded transition mr-2">
                                Edytuj
                            </button>
                            <button class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded transition">
                                Usuń
                            </button>
                        </div>
                    </div>
                `).join('');
            } else {
                productsList.innerHTML = '<p class="text-gray-600">Brak produktów</p>';
            }
        })
        .catch(error => console.error('Error loading products:', error));
}

/**
 * Initialize Messages Section
 */
function initMessagesSection() {
    const messagesList = document.getElementById('messages-list');
    
    // Load messages
    fetch('/mario-bramy/admin/api/messages-list.php')
        .then(response => response.json())
        .then(data => {
            if (data.success && data.messages.length > 0) {
                messagesList.innerHTML = data.messages.map(msg => `
                    <div class="border border-gray-300 rounded p-4">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h4 class="text-lg font-bold">${msg.name}</h4>
                                <p class="text-sm text-gray-600">${msg.email}</p>
                                <p class="text-sm text-gray-600">${msg.phone}</p>
                            </div>
                            <span class="text-xs bg-${msg.status === 'pending' ? 'yellow' : 'green'}-100 text-${msg.status === 'pending' ? 'yellow' : 'green'}-700 px-3 py-1 rounded">
                                ${msg.status}
                            </span>
                        </div>
                        <p class="text-gray-700 mb-4">${msg.message}</p>
                        <div class="text-sm text-gray-500 mb-4">
                            ${msg.submitted_at}
                        </div>
                        <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded transition">
                            Odpowiedz
                        </button>
                    </div>
                `).join('');
            } else {
                messagesList.innerHTML = '<p class="text-gray-600">Brak wiadomości</p>';
            }
        })
        .catch(error => console.error('Error loading messages:', error));
}
