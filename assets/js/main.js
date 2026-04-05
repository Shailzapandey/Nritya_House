document.addEventListener('DOMContentLoaded', () => {
    // 1. Account Dropdown Logic
    const accountBtn = document.getElementById('account-btn');
    const accountMenu = document.getElementById('account-menu');

    if (accountBtn && accountMenu) {
        accountBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            accountMenu.classList.toggle('active');
        });

        document.addEventListener('click', () => {
            accountMenu.classList.remove('active');
        });

        accountMenu.addEventListener('click', (e) => {
            e.stopPropagation();
        });
    }

    // 2. Unified Mobile Drawer Logic
    const mobileBtn = document.getElementById('mobile-menu-btn');
    const navMenu = document.getElementById('nav-menu');

    if (mobileBtn && navMenu) {
        mobileBtn.addEventListener('click', () => {
            navMenu.classList.toggle('mobile-active');

            // Toggle hamburger icon (bars <-> X)
            const icon = mobileBtn.querySelector('i');
            if (icon.classList.contains('fa-bars')) {
                icon.classList.replace('fa-bars', 'fa-xmark');
            } else {
                icon.classList.replace('fa-xmark', 'fa-bars');
            }
        });
    }
});