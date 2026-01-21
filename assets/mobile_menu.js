document.addEventListener('DOMContentLoaded', () => {
    const hamburgerButton = document.getElementById('hamburger-button');
    const mobileMenu = document.getElementById('mobile-menu');
    const globalHeader = document.getElementById('global-header');

    const menuItems = [
        { href: 'nasze-projekty.html', text: 'Nasze Projekty' },
        { href: '', text: 'Sklep' },
        { href: 'opinie.html', text: 'Opinie klientów' },
        { href: 'kontakt.html', text: 'Kontakt', isButton: true },
        { href: 'https://www.facebook.com/p/MARIO-bramy-automatyka-61581453314458/', text: 'Facebook', isIcon: true, icon: 'fa-brands fa-facebook-f' }
    ];

    if (hamburgerButton && mobileMenu && globalHeader) {
        // Create a container for the links inside the mobile menu
        const navContainer = document.createElement('nav');
        navContainer.className = 'flex flex-col items-center justify-center h-full space-y-4 pt-16 pb-8'; 

        menuItems.forEach(item => {
            const a = document.createElement('a');
            a.href = item.href;
            if (item.isIcon) {
                a.innerHTML = `<i class="${item.icon}"></i>`;
                a.setAttribute('aria-label', item.text);
            } else {
                a.textContent = item.text;
            }
            
            if (item.isButton) {
                a.className = 'bg-[var(--primary-color)] text-[var(--primary-button-text-color)] px-6 py-3 rounded hover:bg-[var(--primary-button-hover-bg-color)] transition-all duration-300 font-semibold shadow-lg text-center w-full max-w-xs text-xl';
            } else if (item.isIcon) {
                a.className = 'text-[var(--light-text-color)] hover:text-[var(--primary-color)] transition-colors duration-300 text-3xl py-4';
            } else {
                a.className = 'text-[var(--light-text-color)] hover:text-[var(--primary-color)] transition-colors duration-300 font-medium text-2xl py-2 text-center w-full max-w-xs';
            }
            // Add click listener to close menu when a link is clicked
            a.addEventListener('click', () => {
                mobileMenu.classList.remove('flex');
                mobileMenu.classList.add('hidden');
                document.body.classList.remove('mobile-menu-open');
                hamburgerButton.innerHTML = '<i class="fa-solid fa-bars text-2xl"></i>';
            });
            navContainer.appendChild(a);
        });
        
        // Append nav container to mobile menu
        mobileMenu.appendChild(navContainer);

        const toggleMobileMenu = () => {
            const isHidden = mobileMenu.classList.contains('hidden');
            if (isHidden) {
                mobileMenu.classList.remove('hidden');
                mobileMenu.classList.add('flex');
                document.body.classList.add('mobile-menu-open');
            } else {
                mobileMenu.classList.add('hidden');
                mobileMenu.classList.remove('flex');
                document.body.classList.remove('mobile-menu-open');
            }
            // Toggle hamburger icon
            hamburgerButton.innerHTML = isHidden ? '<i class="fa-solid fa-times text-2xl"></i>' : '<i class="fa-solid fa-bars text-2xl"></i>';
        };

        hamburgerButton.addEventListener('click', toggleMobileMenu);

        // Set initial icon based on menu state
        if (mobileMenu.classList.contains('hidden')) {
             hamburgerButton.innerHTML = '<i class="fa-solid fa-bars text-2xl"></i>';
        } else {
            hamburgerButton.innerHTML = '<i class="fa-solid fa-times text-2xl"></i>';
        }

        // Adjust mobile menu top position based on header height
        const adjustMobileMenuPosition = () => {
            const headerHeight = globalHeader.offsetHeight;
            mobileMenu.style.top = `${headerHeight}px`;
            mobileMenu.style.height = `calc(100vh - ${headerHeight}px)`;
        };

        // Initial adjustment
        adjustMobileMenuPosition();
        // Adjust on resize
        window.addEventListener('resize', adjustMobileMenuPosition);
    }
});