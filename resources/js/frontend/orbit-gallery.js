function createOrbit(worldId, speed, radiusMin, radiusMax) {
    const world = document.getElementById(worldId);
    const section = document.getElementById("orbitSection");

    if (!world || !section) 
        return;
    
    const cards = Array.from(world.querySelectorAll(".orbit-card"));

    const safeZone = 180;

    const items = cards.map((card, index) => {
        const angle = (360 / cards.length) * index;

        const radius = safeZone + radiusMin + Math.random() * (radiusMax - radiusMin);

        const offsetY = (Math.random() - 0.5) * 180;

        const rotateZ = (Math.random() - 0.5) * 2;

        return {card, angle, radius, offsetY, rotateZ};
    });

    let rotation = 0;
    let rafId = null;
    let isVisible = false;
    let isTabActive = !document.hidden;
    let lastTime = null;

    /*
        Ubah ke true kalau ingin blur depth aktif.
        Untuk performa terbaik, biarkan false.
    */
    const ENABLE_DEPTH_BLUR = false;

    function render(time) {
        if (!isVisible || !isTabActive) {
            rafId = null;
            lastTime = null;
            return;
        }

        if (!lastTime) {
            lastTime = time;
        }

        const delta = Math.min(time - lastTime, 32);

        lastTime = time;

        rotation += speed * (delta / 16.67);

        items.forEach((item) => {
            const finalAngle = item.angle + rotation;

            const depth = Math.cos(finalAngle * Math.PI / 180);

            const perspectiveScale = (depth + 1) / 2;

            const dynamicScale = 0.72 + (perspectiveScale * 0.55);

            const dynamicBrightness = 0.82 + (perspectiveScale * 0.18);

            const depthBlur = ENABLE_DEPTH_BLUR
                ? (1 - perspectiveScale) * 0.8
                : 0;

            /*
                Layering:
                - depth <= 0  : gambar di belakang text
                - text        : z-index 100
                - depth > 0   : gambar di depan text
            */
            let dynamicZ;

            if (depth > 0) {
                dynamicZ = 120 + Math.floor(depth * 100);
            } else {
                dynamicZ = 20 + Math.floor((depth + 1) * 40);
            }

            item.card.style.transform = `
                translate(-50%, -50%)
                rotateY(${finalAngle}deg)
                translateZ(${item.radius +
                    (depth * 120)}px)
                translateY(${item.offsetY}px)
                rotateY(${ - finalAngle}deg)
                rotateZ(${item.rotateZ}deg)
                scale(${dynamicScale})
            `;

            if (ENABLE_DEPTH_BLUR) {
                item.card.style.filter = `brightness(${dynamicBrightness}) blur(${depthBlur}px)`;
            } else {
                item.card.style.filter = `brightness(${dynamicBrightness})`;
            }

            item.card.style.opacity = 1;
            item.card.style.zIndex = dynamicZ;
        });

        rafId = requestAnimationFrame(render);
    }

    function startOrbit() {
        if (rafId || !isVisible || !isTabActive) 
            return;
        
        lastTime = null;

        rafId = requestAnimationFrame(render);
    }

    function stopOrbit() {
        if (!rafId) 
            return;
        
        cancelAnimationFrame(rafId);

        rafId = null;
        lastTime = null;
    }

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            isVisible = entry.isIntersecting;

            if (isVisible) {
                startOrbit();
            } else {
                stopOrbit();
            }
        });
    }, {
        threshold: 0.15,
        rootMargin: "200px 0px"
    });

    observer.observe(section);

    document.addEventListener("visibilitychange", () => {
        isTabActive = !document.hidden;

        if (isTabActive) {
            startOrbit();
        } else {
            stopOrbit();
        }
    });
}

createOrbit("orbitWorld", 0.09, 370, 520);