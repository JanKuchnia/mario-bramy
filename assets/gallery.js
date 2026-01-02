
document.addEventListener('DOMContentLoaded', function() {
    const galleryImages = document.querySelectorAll('.gallery-item img');
    const modal = document.getElementById('galleryModal');
    const modalImage = document.getElementById('modalImage');
    const closeModal = document.querySelector('.modal-close');

    if (!modal) {
        return; // No modal on this page
    }

    galleryImages.forEach(image => {
        image.addEventListener('click', function() {
            modal.style.display = 'flex';
            modalImage.src = this.src;
        });
    });

    const close = () => {
        modal.style.display = 'none';
    }

    if(closeModal) {
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
});
