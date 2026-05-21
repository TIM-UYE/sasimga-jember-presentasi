(function () {
    function initReveal() {
        const elements = document.querySelectorAll(
            '.reveal, .reveal-left, .reveal-right, .reveal-scale'
        );

        if (!elements.length) return;

        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                }
            });
        }, {
            threshold: 0.15,
        });

        elements.forEach((el) => revealObserver.observe(el));
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initReveal);
    } else {
        initReveal();
    }
})();