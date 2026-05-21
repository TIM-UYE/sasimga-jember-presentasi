import Lenis from 'lenis';

(function () {
    const lenis = new Lenis({
        duration: 1.8,
        lerp: 0.06,
        smoothWheel: true,
        wheelMultiplier: 0.9,
    });

    window.lenis = lenis;

    function raf(time) {
        lenis.raf(time);

        requestAnimationFrame(raf);
    }

    requestAnimationFrame(raf);
})();