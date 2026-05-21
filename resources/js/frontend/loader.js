(function () {
    function initLoader() {
        const loader = document.getElementById('loader');

        if (!loader) return;

        const MIN_LOADING_TIME = 900;
        const MAX_LOADING_TIME = 7000;

        const startTime = performance.now();

        let isFinished = false;

        function waitForImage(img) {
            return new Promise((resolve) => {
                function done() {
                    if (img.decode) {
                        img.decode()
                            .then(resolve)
                            .catch(resolve);
                    } else {
                        resolve();
                    }
                }

                if (img.complete && img.naturalWidth > 0) {
                    done();
                    return;
                }

                img.addEventListener('load', done, { once: true });
                img.addEventListener('error', resolve, { once: true });
            });
        }

        function hideLoader() {
            if (isFinished) return;

            isFinished = true;

            loader.style.opacity = '0';
            loader.style.visibility = 'hidden';

            setTimeout(() => {
                loader.style.display = 'none';
            }, 800);
        }

        function finishLoading() {
            const elapsed = performance.now() - startTime;

            const remainingTime = Math.max(
                0,
                MIN_LOADING_TIME - elapsed
            );

            setTimeout(hideLoader, remainingTime);
        }

        const hardFallback = setTimeout(() => {
            finishLoading();
        }, MAX_LOADING_TIME);

        const criticalImages = Array.from(
            document.querySelectorAll('[data-critical-asset]')
        );

        const criticalAssetsReady = Promise.allSettled(
            criticalImages.map(waitForImage)
        );

        criticalAssetsReady.then(() => {
            clearTimeout(hardFallback);
            finishLoading();
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initLoader);
    } else {
        initLoader();
    }
})();