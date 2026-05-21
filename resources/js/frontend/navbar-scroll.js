(function () {
    function initNavbarScroll() {
        const navbar = document.getElementById('siteNavbar');

        if (!navbar) return;

        let lastScrollY = window.scrollY;
        let scrollTimer = null;
        let ticking = false;

        const hideOffset = 80;
        const scrollStopDelay = 350;

        function showNavbar() {
            navbar.classList.remove('-translate-y-full');
            navbar.classList.add('translate-y-0');
        }

        function hideNavbar() {
            navbar.classList.remove('translate-y-0');
            navbar.classList.add('-translate-y-full');
        }

        function handleNavbarScroll() {
            const currentScrollY = window.scrollY;

            if (currentScrollY <= hideOffset) {
                showNavbar();
                lastScrollY = currentScrollY;
                ticking = false;
                return;
            }

            if (currentScrollY > lastScrollY) {
                hideNavbar();
            } else {
                showNavbar();
            }

            lastScrollY = currentScrollY;
            ticking = false;
        }

        window.addEventListener('scroll', () => {
            if (!ticking) {
                requestAnimationFrame(handleNavbarScroll);
                ticking = true;
            }

            clearTimeout(scrollTimer);

            scrollTimer = setTimeout(() => {
                showNavbar();
            }, scrollStopDelay);
        }, {
            passive: true,
        });

        showNavbar();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initNavbarScroll);
    } else {
        initNavbarScroll();
    }
})();