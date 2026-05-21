(function () {
    function initVideoParallax() {
        const videos = document.querySelectorAll('.motion-video');

        if (!videos.length) return;

        let ticking = false;

        window.addEventListener('scroll', () => {
            if (ticking) return;

            ticking = true;

            requestAnimationFrame(() => {
                const scrolled = window.scrollY;

                videos.forEach((video) => {
                    video.style.transform =
                        `scale(1.1) translateY(${scrolled * 0.04}px)`;
                });

                ticking = false;
            });
        }, {
            passive: true,
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initVideoParallax);
    } else {
        initVideoParallax();
    }
})();