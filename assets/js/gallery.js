/**
 * Gallery JavaScript
 * Handles: Tab switching, AJAX loading, Modal, Lazy loading
 */

document.addEventListener('DOMContentLoaded', function() {
    // Elements
    const categoryTabs = document.querySelectorAll('.category-tab');
    const galleryGrid = document.getElementById('gallery-grid');
    const loadingIndicator = document.getElementById('loading');
    const noImagesMessage = document.getElementById('no-images');
    const modal = document.getElementById('image-modal');
    const modalImage = document.getElementById('modal-image');
    const modalCaption = document.getElementById('modal-caption');
    const modalClose = document.getElementById('modal-close');
    
    let currentCategory = 'wszystkie';
    
    // Initialize - load first category
    if (categoryTabs.length > 0) {
        categoryTabs[0].classList.add('text-primary-color', 'border-b-2', 'border-primary-color');
        loadGallery('wszystkie');
    }
    
    // Tab click handlers
    categoryTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            // Remove active class from all tabs
            categoryTabs.forEach(t => t.classList.remove('text-primary-color', 'border-b-2', 'border-primary-color'));
            
            // Add active class to clicked tab
            this.classList.add('text-primary-color', 'border-b-2', 'border-primary-color');
            
            // Load gallery for this category
            const category = this.getAttribute('data-category');
            currentCategory = category;
            loadGallery(category);
        });
    });
    
    /**
     * Load gallery images via AJAX
     */
    function loadGallery(category) {
        // Show loading
        loadingIndicator.classList.remove('hidden');
        galleryGrid.innerHTML = '';
        noImagesMessage.classList.add('hidden');
        
        // Fetch images from API
        fetch(`/mario-bramy/api/portfolio.php?category=${encodeURIComponent(category)}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                loadingIndicator.classList.add('hidden');
                
                if (data.success && data.images.length > 0) {
                    // Render images
                    data.images.forEach(image => {
                        const imgElement = createImageElement(image);
                        galleryGrid.appendChild(imgElement);
                    });
                    
                    // Setup lazy loading after rendering
                    setupLazyLoading();
                    
                    // Add click handlers for modal
                    addModalHandlers();
                } else {
                    // No images
                    noImagesMessage.classList.remove('hidden');
                }
            })
            .catch(error => {
                console.error('Error loading gallery:', error);
                loadingIndicator.classList.add('hidden');
                noImagesMessage.classList.remove('hidden');
            });
    }
    
    /**
     * Create image element
     */
    function createImageElement(image) {
        const div = document.createElement('div');
        div.className = 'bg-white rounded-lg overflow-hidden shadow hover:shadow-lg transition cursor-pointer gallery-item';
        div.setAttribute('data-image-url', image.url);
        div.setAttribute('data-image-alt', image.alt_text);
        
        const img = document.createElement('img');
        img.className = 'w-full h-64 object-cover lazy-image';
        img.setAttribute('data-src', image.url.replace(/\.(jpg|png|webp)$/, '.webp')); // Use WebP version
        img.alt = image.alt_text;
        img.loading = 'lazy';
        img.decoding = 'async';
        
        // Fallback to original if WebP not available
        img.onerror = function() {
            this.src = image.url;
        };
        
        div.appendChild(img);
        return div;
    }
    
    /**
     * Setup lazy loading for images
     */
    function setupLazyLoading() {
        const images = document.querySelectorAll('.lazy-image');
        
        if ('IntersectionObserver' in window) {
            // Modern approach with IntersectionObserver
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        const src = img.getAttribute('data-src');
                        
                        if (src) {
                            img.src = src;
                            img.removeAttribute('data-src');
                            observer.unobserve(img);
                        }
                    }
                });
            });
            
            images.forEach(img => imageObserver.observe(img));
        } else {
            // Fallback: load all images immediately
            images.forEach(img => {
                const src = img.getAttribute('data-src');
                if (src) {
                    img.src = src;
                }
            });
        }
    }
    
    /**
     * Add click handlers for modal
     */
    function addModalHandlers() {
        const items = document.querySelectorAll('.gallery-item');
        
        items.forEach(item => {
            item.addEventListener('click', function() {
                const imageUrl = this.getAttribute('data-image-url');
                const imageAlt = this.getAttribute('data-image-alt');
                
                // Use WebP version in modal
                const webpUrl = imageUrl.replace(/\.(jpg|png|webp)$/, '.webp');
                
                modalImage.src = webpUrl;
                modalImage.alt = imageAlt;
                modalCaption.textContent = imageAlt;
                modal.classList.remove('hidden');
            });
        });
    }
    
    // Modal close handlers
    modalClose.addEventListener('click', function() {
        modal.classList.add('hidden');
    });
    
    // Close modal on backdrop click
    modal.addEventListener('click', function(event) {
        if (event.target === modal) {
            modal.classList.add('hidden');
        }
    });
    
    // Close modal on ESC key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
            modal.classList.add('hidden');
        }
    });
});
