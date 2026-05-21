<script>
        // Store current modal menu ID and special item data
        let currentModalMenuId = null;
        let currentSpecialItem = null;
        let currentSpecialMenuData = null;

        // CSRF Token
        const csrfToken = '{{ csrf_token() }}';

        // Open Special Menu Modal with AJAX
        async function openSpecialMenuModal(specialMenu) {
            currentSpecialMenuData = specialMenu;
            document.getElementById('specialMenuTitle').textContent = specialMenu.title || 'Menu Special';

            const itemsList = document.getElementById('specialItemsList');
            itemsList.innerHTML = '';

            if (specialMenu.items && specialMenu.items.length > 0) {
                specialMenu.items.forEach((item, index) => {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'special-item-btn w-full text-left bg-gray-800 hover:bg-orange-500 p-5 rounded-2xl transition-all text-white';
                    btn.innerHTML = `
                        <div class="font-bold">${item.name}</div>
                        <div class="text-sm text-gray-300 mt-1">Rp ${new Intl.NumberFormat('id-ID').format(item.price)}</div>
                    `;
                    btn.onclick = () => selectSpecialMenuItem(item);
                    itemsList.appendChild(btn);
                });

                selectSpecialMenuItem(specialMenu.items[0]);
            } else {
                itemsList.innerHTML = '<p class="text-gray-500 text-center py-4">Tidak ada item tersedia</p>';
            }

            document.getElementById('specialMenuModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        // Select special menu item
        function selectSpecialMenuItem(item) {
            currentSpecialItem = item;
            document.getElementById('specialItemName').textContent = item.name;

            const priceFormatter = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            });
            document.getElementById('specialItemPrice').textContent = priceFormatter.format(item.price);

            document.getElementById('specialItemDescription').textContent = item.description || 'Menu spesial dengan cita rasa terbaik.';
            document.getElementById('specialQtyInput').value = 1;

            const imgSrc = item.image ? `/storage/${item.image}` : '/images/menu-special/tumpeng.jpg';
            document.getElementById('specialMenuImage').src = imgSrc;
        }

        function increaseSpecialQty() {
            const input = document.getElementById('specialQtyInput');
            let value = parseInt(input.value) || 1;
            if (value < 99) input.value = value + 1;
        }

        function decreaseSpecialQty() {
            const input = document.getElementById('specialQtyInput');
            let value = parseInt(input.value) || 1;
            if (value > 1) input.value = value - 1;
        }

        async function addSpecialItemToCart(buttonElement = null) {
            if (!currentSpecialItem) return;

            const qty = parseInt(document.getElementById('specialQtyInput').value) || 1;
            const addBtn = buttonElement || document.getElementById('specialOrderBtn');

            addBtn.disabled = true;
            addBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menambahkan...';

            try {
                const response = await fetch(`/cart/add-special/${currentSpecialItem.id}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ qty: qty })
                });

                const data = await response.json();

                if (data.success) {
                    flyGiftToCart(addBtn);
                    updateCartUI(data.cart);

                    addBtn.innerHTML = '<i class="fas fa-check mr-2"></i>Berhasil!';
                    addBtn.classList.add('bg-green-500');

                    setTimeout(() => {
                        addBtn.disabled = false;
                        addBtn.innerHTML = 'Tambah ke Keranjang';
                        addBtn.classList.remove('bg-green-500');
                        closeSpecialMenuModal();
                    }, 1500);
                } else {
                    addBtn.innerHTML = '<i class="fas fa-exclamation mr-2"></i>Gagal';
                    addBtn.classList.add('bg-red-500');
                    setTimeout(() => {
                        addBtn.disabled = false;
                        addBtn.innerHTML = 'Tambah ke Keranjang';
                        addBtn.classList.remove('bg-red-500');
                    }, 1500);
                }
            } catch (error) {
                addBtn.innerHTML = '<i class="fas fa-exclamation mr-2"></i>Error';
                addBtn.classList.add('bg-red-500');
                setTimeout(() => {
                    addBtn.disabled = false;
                    addBtn.innerHTML = 'Tambah ke Keranjang';
                    addBtn.classList.remove('bg-red-500');
                }, 1500);
            }
        }

        function closeSpecialMenuModal(event) {
            if (event && event.target.id !== 'specialMenuModal') return;
            document.getElementById('specialMenuModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
            currentSpecialItem = null;
            currentSpecialMenuData = null;
        }

        // Search functionality
        document.getElementById('searchInput')?.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const menuFrames = document.querySelectorAll('.menu-frame');
            let hasResults = false;

            menuFrames.forEach(frame => {
                const menuName = frame.querySelector('h3').textContent.toLowerCase();
                const menuDesc = frame.querySelector('p').textContent.toLowerCase();

                if (menuName.includes(searchTerm) || menuDesc.includes(searchTerm)) {
                    frame.style.display = 'block';
                    hasResults = true;
                } else {
                    frame.style.display = 'none';
                }
            });

            const noResults = document.getElementById('noResults');
            if (noResults) {
                noResults.classList.toggle('hidden', hasResults || !searchTerm);
            }
        });

        // 🔥 Smooth filter by category with premium animations
        function filterMenuByCategory(categoryId) {
            // 1. Button particles effect
            const activeBtn = document.querySelector(`.kategori-btn[data-kategori-id="${categoryId}"]`);
            if (activeBtn) {
                for (let i = 0; i < 6; i++) {
                    const particle = document.createElement('div');
                    particle.className = 'filter-particle';
                    const rect = activeBtn.getBoundingClientRect();
                    particle.style.left = `${rect.left + rect.width * 0.2 + Math.random() * rect.width * 0.6}px`;
                    particle.style.top = `${rect.top + rect.height * 0.2 + Math.random() * rect.height * 0.6}px`;
                    particle.style.width = `${4 + Math.random() * 6}px`;
                    particle.style.height = particle.style.width;
                    particle.style.animationDelay = `${Math.random() * 0.15}s`;
                    document.body.appendChild(particle);
                    setTimeout(() => particle.remove(), 1000);
                }
                activeBtn.classList.add('filter-btn-pulse');
                setTimeout(() => activeBtn.classList.remove('filter-btn-pulse'), 400);
                activeBtn.classList.add('btn-ripple');
                setTimeout(() => activeBtn.classList.remove('btn-ripple'), 700);
            }

            // 2. Update button styles
            document.querySelectorAll('.kategori-btn').forEach(btn => {
                if (parseInt(btn.dataset.kategoriId) === parseInt(categoryId)) {
                    btn.classList.remove('bg-gray-800', 'text-gray-300');
                    btn.classList.add('text-white', 'bg-orange-500', 'shadow-lg', 'shadow-orange-500/25', 'cat-btn-active');
                } else {
                    btn.classList.add('bg-gray-800', 'text-gray-300');
                    btn.classList.remove('text-white', 'bg-orange-500', 'shadow-lg', 'shadow-orange-500/25', 'cat-btn-active');
                }
            });

            const menuFrames = document.querySelectorAll('.menu-frame');
            let visibleCount = 0;

            // 3. Filter frames
            menuFrames.forEach((frame, index) => {
                const menuCategoryId = parseInt(frame.getAttribute('data-kategori-id')) || 0;
                const filterCategoryId = parseInt(categoryId);
                const card = frame.querySelector('.menu-card');

                if (filterCategoryId === 0 || menuCategoryId === filterCategoryId) {
                    frame.style.display = 'block';
                    frame.classList.remove('filter-card-leaving');
                    if (card) {
                        card.classList.remove('menu-card-enter', 'menu-card-visible');
                    }
                    frame.style.opacity = '0';
                    setTimeout(() => {
                        frame.classList.remove('filter-card-entering');
                        void frame.offsetWidth;
                        frame.classList.add('filter-card-entering');
                        frame.style.opacity = '1';
                        setTimeout(() => {
                            if (card) {
                                card.style.transitionDelay = '0s';
                                card.classList.add('menu-card-enter');
                                void card.offsetWidth;
                                card.classList.add('menu-card-visible');
                            }
                        }, 200);
                    }, index * 80);
                    visibleCount++;
                } else {
                    frame.classList.remove('filter-card-entering');
                    frame.classList.add('filter-card-leaving');
                    setTimeout(() => {
                        frame.style.display = 'none';
                    }, 400);
                }
            });

            setTimeout(() => {
                menuFrames.forEach(frame => {
                    frame.classList.remove('filter-card-entering');
                });
            }, 700);

            // No Results handling
            const noResults = document.getElementById('noResults');
            if (noResults) {
                if (visibleCount === 0) {
                    noResults.classList.remove('hidden');
                    noResults.style.display = 'block';
                } else {
                    noResults.classList.add('hidden');
                    noResults.style.display = 'none';
                }
            }
        }

        function getCartIconRect() {
            const cartIcon = document.querySelector('[data-cart-icon]');
            if (!cartIcon) return null;
            return cartIcon.getBoundingClientRect();
        }

        function flyGiftToCart(startElement) {
            const cartRect = getCartIconRect();
            if (!cartRect || !startElement) return;

            const startRect = startElement.getBoundingClientRect();
            const gift = document.createElement('div');
            gift.className = 'fly-gift-icon fixed z-[9999] flex items-center justify-center rounded-2xl bg-orange-500 text-white shadow-2xl';
            gift.innerHTML = '<i class="fa-solid fa-gift"></i>';
            gift.style.cssText = `
                width: 48px;
                height: 48px;
                left: ${startRect.left + startRect.width / 2 - 24}px;
                top: ${startRect.top + startRect.height / 2 - 24}px;
                transition: transform 0.78s cubic-bezier(0.22, 1, 0.36, 1), opacity 0.78s ease;
                transform: translate(0, 0) scale(1);
                pointer-events: none;
            `;

            document.body.appendChild(gift);

            requestAnimationFrame(() => {
                const deltaX = cartRect.left + cartRect.width / 2 - (startRect.left + startRect.width / 2);
                const deltaY = cartRect.top + cartRect.height / 2 - (startRect.top + startRect.height / 2);
                gift.style.transform = `translate(${deltaX}px, ${deltaY}px) scale(0.24)`;
                gift.style.opacity = '0';
            });

            setTimeout(() => {
                gift.remove();
                const cartIcon = document.querySelector('[data-cart-icon]');
                if (cartIcon) {
                    cartIcon.classList.add('cart-hit');
                    setTimeout(() => cartIcon.classList.remove('cart-hit'), 450);
                }
            }, 820);
        }

        function updateCartUI(data) {
            const cartBadges = [
                document.getElementById('cartBadge'),
                ...document.querySelectorAll('.cart-count-badge')
            ].filter(Boolean);

            cartBadges.forEach(el => {
                el.textContent = data.count;
                if (data.count > 0) {
                    el.classList.remove('hidden');
                } else {
                    el.classList.add('hidden');
                }
            });

            if (data.count > 0) {
                document.getElementById('floatingCheckout')?.classList.remove('hidden');
                document.getElementById('floatingTotal').textContent = data.total_formatted;
            } else {
                document.getElementById('floatingCheckout')?.classList.add('hidden');
            }
        }

        async function quickAddToCart(menuId, buttonElement = null) {
            const btn = buttonElement || event.target.closest('button');
            if (!btn) return;

            const originalHTML = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

            try {
                const response = await fetch(`/cart/add/${menuId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ qty: 1 })
                });

                const data = await response.json();

                if (data.success) {
                    flyGiftToCart(btn);
                    updateCartUI(data.cart);

                    btn.innerHTML = '<i class="fas fa-check"></i>';
                    btn.classList.add('bg-green-500');

                    setTimeout(() => {
                        btn.innerHTML = originalHTML;
                        btn.disabled = false;
                        btn.classList.remove('bg-green-500');
                    }, 1500);
                } else {
                    btn.innerHTML = '<i class="fas fa-exclamation"></i>';
                    btn.classList.add('bg-red-500');
                    setTimeout(() => {
                        btn.innerHTML = originalHTML;
                        btn.disabled = false;
                        btn.classList.remove('bg-red-500');
                    }, 1500);
                }
            } catch (error) {
                btn.innerHTML = '<i class="fas fa-exclamation"></i>';
                btn.classList.add('bg-red-500');
                setTimeout(() => {
                    btn.innerHTML = originalHTML;
                    btn.disabled = false;
                    btn.classList.remove('bg-red-500');
                }, 1500);
            }
        }

        async function addSpecialToCart(itemId, buttonElement = null) {
            const btn = buttonElement || event.target.closest('button');
            if (!btn) return;

            const originalHTML = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

            try {
                const response = await fetch(`/cart/add-special/${itemId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ qty: 1 })
                });

                const data = await response.json();

                if (data.success) {
                    flyGiftToCart(btn);
                    updateCartUI(data.cart);

                    btn.innerHTML = '<i class="fas fa-check"></i>';
                    btn.classList.add('bg-green-500');

                    setTimeout(() => {
                        btn.innerHTML = originalHTML;
                        btn.disabled = false;
                        btn.classList.remove('bg-green-500');
                    }, 1500);
                } else {
                    btn.innerHTML = '<i class="fas fa-exclamation"></i>';
                    btn.classList.add('bg-red-500');
                    setTimeout(() => {
                        btn.innerHTML = originalHTML;
                        btn.disabled = false;
                        btn.classList.remove('bg-red-500');
                    }, 1500);
                }
            } catch (error) {
                btn.innerHTML = '<i class="fas fa-exclamation"></i>';
                btn.classList.add('bg-red-500');
                setTimeout(() => {
                    btn.innerHTML = originalHTML;
                    btn.disabled = false;
                    btn.classList.remove('bg-red-500');
                }, 1500);
            }
        }

        async function updateCart(url, buttonElement) {
            buttonElement.disabled = true;
            const originalHTML = buttonElement.innerHTML;
            buttonElement.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    updateCartUI(data.cart);
                    if (window.location.pathname === '/cart') {
                        location.reload();
                    }
                }
            } catch (error) {
                console.error('Cart update error:', error);
            } finally {
                buttonElement.disabled = false;
                buttonElement.innerHTML = originalHTML;
            }
        }

        function openMenuDetail(menu) {
            currentModalMenuId = menu.id;
            document.getElementById('modalQtyInput').value = 1;

            const imagePath = menu.gambar ? `{{ asset('storage/menu/') }}/${menu.gambar}` : '';
            document.getElementById('modalImage').src = imagePath || '{{ asset('images/placeholder.jpg') }}';
            document.getElementById('modalMenuName').textContent = menu.nama_menu;

            document.getElementById('modalCategory').innerHTML = menu.kategori ?
                `<span>${menu.kategori.nama_kategori}</span>` :
                '<span>Menu Pilihan</span>';

            const price = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(menu.harga);
            document.getElementById('modalPrice').textContent = price;

            // Check both is_available flag AND calculated_stock
            const calculatedStock = menu.calculated_stock !== undefined ? menu.calculated_stock : null;
            const hasStock = calculatedStock === null || calculatedStock > 0;
            const isAvailable = menu.is_available && hasStock;

            if (isAvailable) {
                document.getElementById('modalStatus').innerHTML = `
                    <div class="flex items-center gap-2">
                        <span class="inline-block w-3 h-3 bg-green-500 rounded-full animate-pulse"></span>
                        <span class="text-green-400 font-semibold">Tersedia</span>
                        ${calculatedStock !== null ? `<span class="text-gray-500 text-sm ml-2">(${calculatedStock} porsi)</span>` : ''}
                    </div>`;
            } else {
                let reason = 'Tidak Tersedia';
                if (!menu.is_available) {
                    reason = 'Menu tidak aktif';
                } else if (calculatedStock !== null && calculatedStock <= 0) {
                    reason = 'Stok Habis';
                }
                document.getElementById('modalStatus').innerHTML = `
                    <div class="flex items-center gap-2">
                        <span class="inline-block w-3 h-3 bg-red-500 rounded-full"></span>
                        <span class="text-red-400 font-semibold">${reason}</span>
                    </div>`;
            }

            document.getElementById('modalDescription').textContent = menu.deskripsi || 'Tidak ada deskripsi';

            if (menu.bahan) {
                document.getElementById('modalBahan').textContent = menu.bahan;
                document.getElementById('bahanSection').classList.remove('hidden');
            } else {
                document.getElementById('bahanSection').classList.add('hidden');
            }

            if (menu.ukuran) {
                document.getElementById('modalUkuran').textContent = menu.ukuran;
                document.getElementById('ukuranInfo').classList.remove('hidden');
            } else {
                document.getElementById('ukuranInfo').classList.add('hidden');
            }

            if (menu.durasi_persiapan) {
                document.getElementById('modalDurasi').textContent = `${menu.durasi_persiapan} menit`;
                document.getElementById('durasiInfo').classList.remove('hidden');
            } else {
                document.getElementById('durasiInfo').classList.add('hidden');
            }

            const orderBtn = document.getElementById('modalOrderBtn');
            if (!isAvailable) {
                orderBtn.disabled = true;
                orderBtn.classList.add('opacity-50', 'cursor-not-allowed');
                orderBtn.innerHTML = '<i class="fas fa-times-circle mr-2"></i>' + (reason === 'Stok Habis' ? 'Stok Tidak Mencukupi' : 'Tidak Tersedia');
            } else {
                orderBtn.disabled = false;
                orderBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                orderBtn.innerHTML = '<i class="fas fa-shopping-cart mr-2"></i>Tambah ke Keranjang';
            }

            document.getElementById('menuDetailModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function increaseModalQty() {
            const input = document.getElementById('modalQtyInput');
            let value = parseInt(input.value) || 1;
            if (value < 99) input.value = value + 1;
        }

        function decreaseModalQty() {
            const input = document.getElementById('modalQtyInput');
            let value = parseInt(input.value) || 1;
            if (value > 1) input.value = value - 1;
        }

        async function addToCartFromModal(buttonElement = null) {
            if (!currentModalMenuId) return;

            const qty = parseInt(document.getElementById('modalQtyInput').value) || 1;
            const addBtn = buttonElement || document.getElementById('modalOrderBtn');

            addBtn.disabled = true;
            addBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menambahkan...';

            try {
                const response = await fetch(`/cart/add/${currentModalMenuId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ qty: qty })
                });

                const data = await response.json();

                if (data.success) {
                    flyGiftToCart(addBtn);
                    updateCartUI(data.cart);
                    closeMenuDetail();
                } else {
                    alert(data.message || 'Gagal menambahkan ke keranjang');
                }
            } catch (error) {
                alert('Terjadi kesalahan');
            } finally {
                addBtn.disabled = false;
                addBtn.innerHTML = '<i class="fas fa-shopping-cart mr-2"></i>Tambah ke Keranjang';
            }
        }

        function closeMenuDetail(event) {
            if (event && event.target.id !== 'menuDetailModal') return;
            document.getElementById('menuDetailModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
            currentModalMenuId = null;
        }

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeMenuDetail();
            }
        });

        function updateFloatingCheckout() {
            fetch('{{ route('cart.count') }}')
                .then(response => response.json())
                .then(data => {
                    const floatingCheckout = document.getElementById('floatingCheckout');
                    const floatingTotal = document.getElementById('floatingTotal');

                    if (data.count > 0) {
                        floatingCheckout.classList.remove('hidden');
                        floatingTotal.textContent = data.total_formatted;
                    } else {
                        floatingCheckout.classList.add('hidden');
                    }
                })
                .catch(() => {
                    document.getElementById('floatingCheckout').classList.add('hidden');
                });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const firstBtn = document.querySelector('.kategori-btn');
            if (firstBtn) {
                firstBtn.classList.add('text-white', 'bg-orange-500', 'shadow-lg', 'shadow-orange-500/25');
                firstBtn.classList.remove('bg-gray-800', 'text-gray-300');
                setTimeout(() => filterMenuByCategory(0), 100);
            }
            updateFloatingCheckout();

            // Sticky handled via CSS (sticky top-*) for simplicity and Lenis compatibility
        });

        document.addEventListener('visibilitychange', function() {
            if (!document.hidden) {
                updateFloatingCheckout();
            }
        });

        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        function showMenuSection(type) {
            const regular = document.getElementById('regularMenuSection');
            const special = document.getElementById('specialMenuSection');
            const regularBtn = document.getElementById('regularBtn');
            const specialBtn = document.getElementById('specialBtn');
            const filterSection = document.getElementById('filterSection');

            if (type === 'regular') {
                regular.classList.remove('hidden');
                special.classList.add('hidden');
                regularBtn.classList.add('bg-orange-500');
                specialBtn.classList.remove('bg-orange-500');
                // Show filter for regular menu
                if (filterSection) {
                    filterSection.classList.remove('hidden');
                    filterSection.style.display = '';
                }
                // Re-trigger staggered entrance animation for regular menu
                const regularCards = regular.querySelectorAll('.menu-card');
                regularCards.forEach((card, i) => {
                    card.classList.remove('menu-card-enter', 'menu-card-visible');
                    setTimeout(() => {
                        card.classList.add('menu-card-enter');
                        void card.offsetWidth;
                        card.classList.add('menu-card-visible');
                    }, i * 60);
                });
            } else {
                regular.classList.add('hidden');
                special.classList.remove('hidden');
                specialBtn.classList.add('bg-orange-500');
                regularBtn.classList.remove('bg-orange-500');
                // Hide filter when viewing specials
                if (filterSection) {
                    filterSection.classList.add('hidden');
                    filterSection.style.display = 'none';
                }
                // Trigger staggered entrance animation for special cards + borders
                const specialFrames = special.querySelectorAll('.special-frame');
                specialFrames.forEach((frame, i) => {
                    const card = frame.querySelector('.special-card');
                    // Reset frame border
                    frame.classList.remove('special-frame-enter', 'special-frame-visible');
                    // Reset card
                    if (card) {
                        card.classList.remove('menu-card-enter', 'menu-card-visible');
                    }
                    setTimeout(() => {
                        // Animate border in
                        frame.classList.add('special-frame-enter');
                        void frame.offsetWidth;
                        frame.classList.add('special-frame-visible');
                        // Animate card after border
                        setTimeout(() => {
                            if (card) {
                                card.classList.add('menu-card-enter');
                                void card.offsetWidth;
                                card.classList.add('menu-card-visible');
                            }
                        }, 200);
                    }, i * 100);
                });
            }

            window.scrollTo({
                top: document.getElementById(type === 'regular' ? 'regularMenuSection' : 'specialMenuSection').offsetTop - 100,
                behavior: 'smooth'
            });
        }
    </script>

    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .line-clamp-1 { display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden; }
        .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #1a1a1a; }
        ::-webkit-scrollbar-thumb { background: #f97316; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #ea580c; }
    </style>
