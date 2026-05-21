(function () {
    function initScrollToTop() {
        const button = document.getElementById('scrollToTopBtn');

        if (!button) return;

        let ticking = false;

        const showAfter = window.innerHeight * 0.8;

        function showButton() {
            button.classList.remove(
                'opacity-0',
                'translate-y-6',
                'scale-90',
                'pointer-events-none'
            );

            button.classList.add(
                'opacity-100',
                'translate-y-0',
                'scale-100',
                'pointer-events-auto'
            );
        }

        function hideButton() {
            button.classList.add(
                'opacity-0',
                'translate-y-6',
                'scale-90',
                'pointer-events-none'
            );

            button.classList.remove(
                'opacity-100',
                'translate-y-0',
                'scale-100',
                'pointer-events-auto'
            );
        }

        function handleScroll() {
            const currentScroll = window.scrollY;

            if (currentScroll > showAfter) {
                showButton();
            } else {
                hideButton();
            }

            ticking = false;
        }

        window.addEventListener('scroll', () => {
            if (ticking) return;

            ticking = true;

            requestAnimationFrame(handleScroll);
        }, {
            passive: true,
        });

        button.addEventListener('click', () => {
            if (window.lenis) {
                window.lenis.scrollTo(0, {
                    duration: 0.8,
                    easing: (t) => 1 - Math.pow(1 - t, 3),
                });

                return;
            }

            window.scrollTo({
                top: 0,
                behavior: 'smooth',
            });
        });

        hideButton();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initScrollToTop);
    } else {
        initScrollToTop();
    }
})();