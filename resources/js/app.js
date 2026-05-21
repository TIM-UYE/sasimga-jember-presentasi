import './bootstrap';

import './frontend/loader';
import './frontend/lenis-scroll';
import './frontend/reveal';
import './frontend/video-parallax';
import './frontend/navbar-scroll';
import './frontend/scroll-to-top';


// =========================
// 🎬 MENU CARD ANIMATIONS
// =========================

class MenuAnimations {
    constructor() {
        this.initCardReveal();
        this.init3DTilt();
        this.initCategoryFilterAnimation();
        this.initSparkles();
    }

    // Staggered card reveal on scroll
    initCardReveal() {
        const cards = document.querySelectorAll('.menu-card');
        const specials = document.querySelectorAll('.special-card');
        const allItems = [...cards, ...specials];
        if (!allItems.length) return;

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    const all = Array.from(allItems);
                    const idx = all.indexOf(entry.target);
                    const delay = idx * 0.08;

                    entry.target.style.transitionDelay = `${delay}s`;
                    entry.target.classList.add('menu-card-visible');

                    // Sync frame border animation with the card
                    const frame = entry.target.closest('.menu-frame, .special-frame');
                    if (frame) {
                        const isSpecial = frame.classList.contains('special-frame');
                        const frameDelay = Math.min(delay, 0.4); // cap delay agar border tidak terlalu lambat
                        frame.style.transitionDelay = `${frameDelay}s`;
                        frame.classList.add(isSpecial ? 'special-frame-visible' : 'menu-frame-visible');
                    }

                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        });

        allItems.forEach(item => {
            item.classList.add('menu-card-enter');
            // Also add entrance class to parent frame for border animation
            const frame = item.closest('.menu-frame, .special-frame');
            if (frame) {
                const isSpecial = frame.classList.contains('special-frame');
                frame.classList.add(isSpecial ? 'special-frame-enter' : 'menu-frame-enter');
            }
            observer.observe(item);
        });
    }

    // 3D Tilt effect on menu cards
    init3DTilt() {
        const tiltCards = document.querySelectorAll('.menu-card-tilt');

        tiltCards.forEach(card => {
            const inner = card.querySelector('.menu-card-tilt-inner');
            if (!inner) return;

            card.addEventListener('mousemove', (e) => {
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                const centerX = rect.width / 2;
                const centerY = rect.height / 2;
                const rotateX = ((y - centerY) / centerY) * -8;
                const rotateY = ((x - centerX) / centerX) * 8;

                inner.style.transform = `
                    perspective(1000px)
                    rotateX(${rotateX}deg)
                    rotateY(${rotateY}deg)
                    translateZ(10px)
                `;
            });

            card.addEventListener('mouseleave', () => {
                if (inner) {
                    inner.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg) translateZ(0)';
                }
            });
        });
    }

    // Category filter button animation
    initCategoryFilterAnimation() {
        document.querySelectorAll('.kategori-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                // Add glow to active button
                document.querySelectorAll('.kategori-btn').forEach(b => {
                    b.classList.remove('cat-btn-active');
                });
                if (btn.dataset.kategoriId === '0' || btn.classList.contains('bg-orange-500')) {
                    btn.classList.add('cat-btn-active');
                }
            });
        });
    }

    // Sparkle effect for special menu cards
    initSparkles() {
        document.querySelectorAll('.special-card').forEach(card => {
            card.addEventListener('mouseenter', () => {
                for (let i = 0; i < 4; i++) {
                    const sparkle = document.createElement('div');
                    sparkle.className = 'sparkle-particle';
                    sparkle.style.left = `${20 + Math.random() * 60}%`;
                    sparkle.style.top = `${20 + Math.random() * 60}%`;
                    sparkle.style.animationDelay = `${Math.random() * 0.5}s`;
                    sparkle.style.width = `${4 + Math.random() * 4}px`;
                    sparkle.style.height = sparkle.style.width;
                    card.appendChild(sparkle);
                    setTimeout(() => sparkle.remove(), 2000);
                }
            });
        });
    }
}

// =========================
// 🎯 PRICE COUNTER ANIMATION
// =========================

function animatePrice(element, targetPrice) {
    const duration = 800;
    const start = performance.now();
    const startPrice = 0;

    function update(currentTime) {
        const elapsed = currentTime - start;
        const progress = Math.min(elapsed / duration, 1);
        // Ease out cubic
        const eased = 1 - Math.pow(1 - progress, 3);
        const currentPrice = Math.floor(startPrice + (targetPrice - startPrice) * eased);

        element.textContent = `Rp ${currentPrice.toLocaleString('id-ID')}`;

        if (progress < 1) {
            requestAnimationFrame(update);
        }
    }

    requestAnimationFrame(update);
}

// =========================
// 🎬 INIT ON DOM READY
// =========================

document.addEventListener('DOMContentLoaded', () => {
    // Initialize animations
    new MenuAnimations();

    // Animate price on visible
    const priceObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const priceEl = entry.target;
                const priceText = priceEl.textContent.replace(/[^0-9]/g, '');
                const price = parseInt(priceText) || 0;
                if (price > 0) {
                    animatePrice(priceEl, price);
                }
                priceObserver.unobserve(priceEl);
            }
        });
    }, { threshold: 0.5 });

    document.querySelectorAll('.menu-price').forEach(el => {
        priceObserver.observe(el);
    });

    // Section reveal animation
    const sectionObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-slide-up-blur');
                sectionObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.section-reveal').forEach(el => {
        sectionObserver.observe(el);
    });
});
