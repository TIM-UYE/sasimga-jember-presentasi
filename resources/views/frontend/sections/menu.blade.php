<section class="relative bg-black pt-24 pb-10 md:pb-14 overflow-hidden">

    {{-- Background Pattern --}}
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-20 left-10 w-64 h-64 bg-orange-500 rounded-full blur-3xl"></div>
        <div class="absolute bottom-20 right-10 w-96 h-96 bg-orange-600 rounded-full blur-3xl"></div>
    </div>

        <div class="relative z-10 container-main section-reveal">

        {{-- HEADER --}}
        <div class="max-w-2xl mx-auto text-center mb-16 reveal">
            <span
                class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-orange-500/10 text-orange-400 text-xs font-medium tracking-wider uppercase mb-5 ring-1 ring-orange-500/20">
                <span class="w-1.5 h-1.5 rounded-full bg-orange-400 animate-pulse"></span>
                {{ __('frontend.menu.pre-title') }}
            </span>
            <h2 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-4 tracking-tight">
                <span class="text-white">{{ __('frontend.menu.white-title') }}</span>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-amber-500">
                    {{ __('frontend.menu.orange-title') }}
                </span>
            </h2>
            <p class="text-zinc-400 text-base leading-relaxed">
                {{ __('frontend.menu.description') }}
            </p>
            <div class="mt-8">
                <a href="{{ route('frontend.menu') }}"
                    class="inline-flex items-center bg-orange-500 hover:bg-orange-600 text-white px-8 py-3 rounded-full font-semibold transition-all hover:scale-105">
                    <i class="fas fa-utensils mr-2"></i>{{ __('frontend.menu.menu_button') }}
                </a>
            </div>
        </div>

        {{-- CATEGORY FILTERS --}}
        <div class="flex flex-wrap gap-3 justify-center mb-10 reveal delay-200">
            <button onclick="filterMenuByCategory(0)"
                class="px-5 py-2.5 rounded-full text-sm font-semibold kategori-btn text-white bg-orange-500 hover:bg-orange-600 transition-all shadow-lg shadow-orange-500/25"
                data-kategori-id="0">
                <i class="fas fa-th mr-2"></i>Semua
            </button>

            @foreach ($kategoris as $kat)
                <button onclick="filterMenuByCategory({{ $kat->id }})"
                    class="px-5 py-2.5 rounded-full text-sm font-semibold kategori-btn bg-gray-800 text-gray-300 hover:bg-orange-500 hover:text-white transition-all"
                    data-kategori-id="{{ $kat->id }}">
                    {{ $kat->nama_kategori }}
                    <span class="ml-2 text-xs opacity-70">({{ $kat->menus_count }})</span>
                </button>
            @endforeach
        </div>

        {{-- GRID MENU --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse($menus as $menu)
                <div class="group bg-gradient-to-b from-gray-900 to-gray-950 rounded-3xl overflow-hidden shadow-xl hover:shadow-2xl hover:shadow-orange-500/10 transition-all duration-500 menu-card border border-gray-800 hover:border-orange-500/30 reveal menu-card-tilt
                {{ $loop->index >= 4 ? 'hidden md:block' : '' }}
{{ $loop->index >= 8 ? 'md:hidden extra-menu' : '' }}"
                    style="--delay: {{ $loop->index * 0.1 }}s" data-kategori-id="{{ $menu->kategori_id }}">
                    <div class="menu-card-tilt-inner">
                        <!-- IMAGE -->
                        <div class="relative overflow-hidden h-56 menu-card-shine">
                            @if ($menu->gambar)
                                <img src="{{ asset('storage/menu/' . $menu->gambar) }}" alt="{{ $menu->nama_menu }}"
                                    class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                            @else
                                <div
                                    class="w-full h-full bg-gradient-to-br from-gray-800 to-gray-900 flex items-center justify-center">
                                    <i class="fas fa-utensils text-gray-600 text-5xl"></i>
                                </div>
                            @endif
                            <!-- Overlay on hover -->
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            </div>
                            <!-- Category Badge -->
                            <div class="absolute top-4 left-4">
                                <span
                                    class="px-3 py-1 bg-black/60 backdrop-blur-sm rounded-full text-xs text-white font-semibold">
                                    {{ $menu->kategori->nama_kategori ?? 'Menu' }}
                                </span>
                            </div>
                            <!-- Availability Badge (check both is_available AND calculated stock) -->
                            <div class="absolute top-4 right-4">
                                @php
                                    $menuHasStock = $menu->is_available && $menu->calculated_stock > 0;
                                @endphp
                                @if ($menuHasStock)
                                    <span
                                        class="px-3 py-1 bg-green-500/90 backdrop-blur-sm rounded-full text-xs text-white font-semibold flex items-center gap-1">
                                        <span class="w-2 h-2 bg-white rounded-full animate-pulse"></span>
                                        {{ __('frontend.menu.available') }}
                                    </span>
                                @else
                                    <span
                                        class="px-3 py-1 bg-red-500/90 backdrop-blur-sm rounded-full text-xs text-white font-semibold">
                                        {{ __('frontend.menu.empty') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <!-- CONTENT -->
                        <div class="p-5">
                            <h3
                                class="font-bold text-xl text-white mb-2 line-clamp-1 group-hover:text-orange-400 transition-colors">
                                {{ $menu->nama_menu }}
                            </h3>
                            <p class="text-sm text-gray-400 mb-4 line-clamp-2 min-h-[40px]">
                                {{ $menu->deskripsi ?? 'Tidak ada deskripsi' }}
                            </p>
                            <!-- Price & Actions -->
                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <span class="text-2xl font-bold text-orange-400 menu-price">
                                        Rp {{ number_format($menu->harga, 0, ',', '.') }}
                                    </span>
                                </div>
                                <div class="flex gap-2">
                                    <!-- Quick Add Button -->
                                    <button type="button" onclick="quickAddToCart({{ $menu->id }}, this)"
                                        class="w-12 h-12 rounded-full bg-orange-500 hover:bg-orange-600 text-white flex items-center justify-center transition-all hover:scale-110 disabled:opacity-50 disabled:cursor-not-allowed"
                                        {{ (!$menu->is_available || $menu->calculated_stock <= 0) ? 'disabled' : '' }} title="Tambah ke keranjang">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                    <!-- Detail Button -->
                                    <button onclick='openMenuDetail(@json($menu))'
                                        class="w-12 h-12 rounded-full bg-gray-800 hover:bg-gray-700 text-white flex items-center justify-center transition-all hover:scale-110"
                                        title="Lihat detail">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-20 menu-empty-state" style="display: none;">
                    <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-gray-800 mb-6">
                        <i class="fas fa-utensils text-4xl text-gray-600"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-400 mb-2">Belum Ada Menu</h3>
                    <p class="text-gray-500">{{ __('frontend.menu.available_soon') }}</p>
                </div>
            @endforelse
        </div>
        @if ($menus->count() > 8)
            <div class="text-center mt-12 reveal">

                <a href="{{ route('frontend.menu') }}"
                    class="inline-flex items-center gap-3 px-8 py-4 rounded-full bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-semibold transition-all hover:scale-105 shadow-lg shadow-orange-500/20">

                    <span> {{ __('frontend.menu.more_button') }} </span>

                    <i class="fas fa-arrow-right"></i>

                </a>

            </div>
        @endif

    </div>

    {{-- MODAL DETAIL MENU --}}
    <div id="menuDetailModal"
        class="hidden fixed inset-0 bg-black/80 backdrop-blur-sm z-50 flex items-center justify-center p-4"
        onclick="closeMenuDetail(event)">
        <div class="bg-gradient-to-b from-gray-900 to-gray-950 rounded-3xl max-w-3xl w-full max-h-[90vh] overflow-y-auto border border-gray-800 shadow-2xl"
            onclick="event.stopPropagation()">
            {{-- HEADER --}}
            <div
                class="sticky top-0 bg-gray-900/95 backdrop-blur-sm border-b border-gray-800 px-6 py-4 flex items-center justify-between z-10">
                <h2 class="text-2xl font-bold text-white">
                    <i class="fas fa-info-circle mr-2 text-orange-500"></i>
                    Detail Menu
                </h2>
                <button onclick="closeMenuDetail()"
                    class="w-10 h-10 rounded-full bg-gray-800 hover:bg-gray-700 text-white flex items-center justify-center transition-all">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            {{-- BODY --}}
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- IMAGE --}}
                    <div>
                        <div class="rounded-2xl overflow-hidden mb-4">
                            <img id="modalImage" src="" alt="Menu" class="w-full h-72 object-cover">
                        </div>
                    </div>

                    {{-- CONTENT --}}
                    <div class="text-white">
                        <div id="modalCategory"
                            class="inline-block bg-orange-500/20 text-orange-400 px-4 py-2 rounded-full text-sm font-semibold mb-4">
                        </div>

                        <h1 id="modalMenuName" class="text-3xl font-bold text-white mb-3"></h1>

                        <p id="modalPrice" class="text-3xl font-bold text-orange-400 mb-4"></p>

                        <div id="modalStatus" class="mb-4"></div>

                        <hr class="border-gray-700 my-4">

                        {{-- DESCRIPTION --}}
                        <div class="mb-4">
                            <h3 class="font-bold text-white mb-2 flex items-center">
                                <i class="fas fa-align-left mr-2 text-orange-500"></i>
                                Deskripsi
                            </h3>
                            <p id="modalDescription" class="text-gray-400 leading-relaxed"></p>
                        </div>

                        {{-- BAHAN --}}
                        <div id="bahanSection" class="mb-4 hidden">
                            <h3 class="font-bold text-white mb-2 flex items-center">
                                <i class="fas fa-leaf mr-2 text-green-500"></i>
                                Bahan Utama
                            </h3>
                            <p id="modalBahan" class="text-gray-400"></p>
                        </div>

                        {{-- INFO --}}
                        <div id="infoTambahanSection" class="grid grid-cols-2 gap-3 mb-4">
                            <div id="ukuranInfo" class="bg-gray-800 p-3 rounded-xl hidden">
                                <p class="text-xs text-gray-500">Ukuran</p>
                                <p id="modalUkuran" class="font-semibold text-white"></p>
                            </div>

                            <div id="durasiInfo" class="bg-gray-800 p-3 rounded-xl hidden">
                                <p class="text-xs text-gray-500">Durasi Persiapan</p>
                                <p id="modalDurasi" class="font-semibold text-white"></p>
                            </div>
                        </div>

                        {{-- QUANTITY SELECTOR --}}
                        <div class="mb-6">
                            <label class="font-bold text-white mb-3 block">Quantity:</label>
                            <div class="flex items-center gap-4">
                                <button type="button" onclick="decreaseModalQty()"
                                    class="w-12 h-12 rounded-full bg-gray-800 hover:bg-gray-700 text-white flex items-center justify-center text-xl font-bold transition-all">
                                    -
                                </button>
                                <input type="number" id="modalQtyInput" value="1" min="1"
                                    max="99"
                                    class="w-24 text-center bg-gray-800 border border-gray-700 rounded-xl py-3 text-white font-bold text-lg">
                                <button type="button" onclick="increaseModalQty()"
                                    class="w-12 h-12 rounded-full bg-gray-800 hover:bg-gray-700 text-white flex items-center justify-center text-xl font-bold transition-all">
                                    +
                                </button>
                            </div>
                        </div>

                        {{-- ACTION BUTTON --}}
                        <button type="button" id="modalOrderBtn" onclick="addToCartFromModal(this)"
                            class="w-full bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white py-4 rounded-xl font-bold transition-all hover:scale-[1.02] flex items-center justify-center gap-2">
                            <i class="fas fa-shopping-cart"></i>
                            Tambah ke Keranjang
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Store current modal menu ID
        let currentModalMenuId = null;

        // 🔥 Smooth filter by category with premium animations
        function filterMenuByCategory(categoryId) {
            // 1. Button particles effect
            const activeBtn = document.querySelector(`.kategori-btn[data-kategori-id="${categoryId}"]`);
            if (activeBtn) {
                // Particle burst from button
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

                // Button morph pulse
                activeBtn.classList.add('filter-btn-pulse');
                setTimeout(() => activeBtn.classList.remove('filter-btn-pulse'), 400);

                // Button ripple
                activeBtn.classList.add('btn-ripple');
                setTimeout(() => activeBtn.classList.remove('btn-ripple'), 700);
            }

            // 2. Update button styles with smooth class
            document.querySelectorAll('.kategori-btn').forEach(btn => {
                if (parseInt(btn.dataset.kategoriId) === parseInt(categoryId)) {
                    btn.classList.remove('bg-gray-800', 'text-gray-300');
                    btn.classList.add('text-white', 'bg-orange-500', 'shadow-lg', 'shadow-orange-500/25', 'cat-btn-active');
                } else {
                    btn.classList.add('bg-gray-800', 'text-gray-300');
                    btn.classList.remove('text-white', 'bg-orange-500', 'shadow-lg', 'shadow-orange-500/25', 'cat-btn-active');
                }
            });

            const menuCards = document.querySelectorAll('.menu-card');
            let visibleCount = 0;

            // 3. Animate cards out with leave animation
            menuCards.forEach(card => {
                const menuCategoryId = parseInt(card.getAttribute('data-kategori-id'));
                const filterCategoryId = parseInt(categoryId);

                if (filterCategoryId === 0 || menuCategoryId === filterCategoryId) {
                    // Will be shown — keep visible, no leave anim
                } else {
                    // Will be hidden — animate out
                    card.classList.remove('filter-card-entering');
                    card.classList.add('filter-card-leaving');
                }
            });

            // 4. After leave animation completes, swap display and animate in
            setTimeout(() => {
                let shownCount = 0;
                menuCards.forEach((card, index) => {
                    const menuCategoryId = parseInt(card.getAttribute('data-kategori-id'));
                    const filterCategoryId = parseInt(categoryId);

                    if (filterCategoryId === 0 || menuCategoryId === filterCategoryId) {
                        if (shownCount < 8) {
                            card.style.display = 'block';
                            // Remove any previous classes that may hide the card
                            card.classList.remove('hidden', 'extra-menu', 'md:hidden');
                            // Remove any previous classes
                            card.classList.remove('filter-card-leaving', 'menu-card-enter', 'menu-card-visible');
                            // Force reflow
                            void card.offsetWidth;
                            // Add enter animation with staggered delay
                            card.style.animationDelay = `${index * 0.08}s`;
                            card.classList.add('filter-card-entering');
                            shownCount++;
                        } else {
                            card.style.display = 'none';
                            card.classList.remove('filter-card-leaving', 'filter-card-entering');
                        }
                        visibleCount++;
                    } else {
                        card.style.display = 'none';
                        card.classList.remove('filter-card-leaving', 'filter-card-entering');
                    }
                });

                // 5. Clean up entering class after animation
                setTimeout(() => {
                    menuCards.forEach(card => {
                        card.classList.remove('filter-card-entering');
                        card.style.animationDelay = '';
                    });
                }, 700);

                // Show/hide empty state
                const emptyState = document.querySelector('.menu-empty-state');
                if (emptyState) {
                    emptyState.style.display = visibleCount === 0 ? 'block' : 'none';
                    if (visibleCount === 0) {
                        emptyState.classList.add('filter-card-entering');
                        setTimeout(() => emptyState.classList.remove('filter-card-entering'), 600);
                    }
                }
            }, 420); // wait for leave animation to finish
        }

        const csrfToken = '{{ csrf_token() }}';

        function getCartIconRect() {
            const cartIcon = document.querySelector('[data-cart-icon]');
            console.log('Cart icon found:', cartIcon);
            return cartIcon ? cartIcon.getBoundingClientRect() : null;
        }

        function flyGiftToCart(startElement) {
            console.log('flyGiftToCart called with startElement:', startElement);
            if (!startElement) {
                console.log('Fly gift animation skipped: startElement missing');
                return;
            }

            let cartRect = getCartIconRect();
            // Fallback position if cart icon not found (center of screen)
            if (!cartRect) {
                cartRect = { left: window.innerWidth / 2 - 20, top: window.innerHeight / 2 - 20, width: 40, height: 40 };
                console.log('Using fallback cart position (center screen)', cartRect);
            }

            console.log('Starting fly gift animation', { startRect: startElement.getBoundingClientRect(), cartRect });

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
            const btn = buttonElement || event?.target?.closest('button');
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
                    console.log('Adding to cart success, calling flyGiftToCart with btn:', btn);
                    flyGiftToCart(btn);
                    updateCartUI(data.cart);
                    btn.innerHTML = '<i class="fas fa-check"></i>';
                    btn.classList.add('bg-green-500');
                } else {
                    btn.innerHTML = '<i class="fas fa-exclamation"></i>';
                    btn.classList.add('bg-red-500');
                }
            } catch (error) {
                btn.innerHTML = '<i class="fas fa-exclamation"></i>';
                btn.classList.add('bg-red-500');
            } finally {
                setTimeout(() => {
                    btn.innerHTML = originalHTML;
                    btn.disabled = false;
                    btn.classList.remove('bg-green-500', 'bg-red-500');
                }, 1500);
            }
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
                    console.log('Adding to cart from modal success, calling flyGiftToCart with addBtn:', addBtn);
                    flyGiftToCart(addBtn);
                    updateCartUI(data.cart);
                    closeMenuDetail();
                } else {
                    addBtn.innerHTML = '<i class="fas fa-exclamation mr-2"></i>Gagal';
                    addBtn.classList.add('bg-red-500');
                }
            } catch (error) {
                addBtn.innerHTML = '<i class="fas fa-exclamation mr-2"></i>Error';
                addBtn.classList.add('bg-red-500');
            } finally {
                setTimeout(() => {
                    addBtn.disabled = false;
                    addBtn.innerHTML = '<i class="fas fa-shopping-cart mr-2"></i>Tambah ke Keranjang';
                    addBtn.classList.remove('bg-red-500');
                }, 1500);
            }
        }

        // Open menu detail modal
        function openMenuDetail(menu) {
            currentModalMenuId = menu.id;

            // Reset quantity
            document.getElementById('modalQtyInput').value = 1;

            // Set image
            const imagePath = menu.gambar ? `{{ asset('storage/menu/') }}/${menu.gambar}` : '';
            document.getElementById('modalImage').src = imagePath || '{{ asset('images/placeholder.jpg') }}';

            // Set title
            document.getElementById('modalMenuName').textContent = menu.nama_menu;

            // Set category
            const categoryHtml = menu.kategori ? `<span>${menu.kategori.nama_kategori}</span>` :
                '<span>Menu Pilihan</span>';
            document.getElementById('modalCategory').innerHTML = categoryHtml;

            // Set price
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

            // Set status
            let statusHtml;
            if (isAvailable) {
                statusHtml = `
                    <div class="flex items-center gap-2">
                        <span class="inline-block w-3 h-3 bg-green-500 rounded-full animate-pulse"></span>
                        <span class="text-green-400 font-semibold">{{ __('frontend.menu.available') }}</span>
                        ${calculatedStock !== null ? `<span class="text-gray-500 text-sm ml-2">(${calculatedStock} porsi)</span>` : ''}
                    </div>`;
            } else {
                let reason = 'Tidak Tersedia';
                if (!menu.is_available) {
                    reason = 'Menu tidak aktif';
                } else if (calculatedStock !== null && calculatedStock <= 0) {
                    reason = 'Stok Habis';
                }
                statusHtml = `
                    <div class="flex items-center gap-2">
                        <span class="inline-block w-3 h-3 bg-red-500 rounded-full"></span>
                        <span class="text-red-400 font-semibold">${reason}</span>
                    </div>`;
            }
            document.getElementById('modalStatus').innerHTML = statusHtml;

            // Set description
            document.getElementById('modalDescription').textContent = menu.deskripsi || 'Tidak ada deskripsi';

            // Set bahan
            if (menu.bahan) {
                document.getElementById('modalBahan').textContent = menu.bahan;
                document.getElementById('bahanSection').classList.remove('hidden');
            } else {
                document.getElementById('bahanSection').classList.add('hidden');
            }

            // Set ukuran
            if (menu.ukuran) {
                document.getElementById('modalUkuran').textContent = menu.ukuran;
                document.getElementById('ukuranInfo').classList.remove('hidden');
            } else {
                document.getElementById('ukuranInfo').classList.add('hidden');
            }

            // Set durasi
            if (menu.durasi_persiapan) {
                document.getElementById('modalDurasi').textContent = `${menu.durasi_persiapan} menit`;
                document.getElementById('durasiInfo').classList.remove('hidden');
            } else {
                document.getElementById('durasiInfo').classList.add('hidden');
            }

            // Set button state
            const orderBtn = document.getElementById('modalOrderBtn');
            if (!isAvailable) {
                orderBtn.disabled = true;
                orderBtn.classList.add('opacity-50', 'cursor-not-allowed');
                if (reason === 'Stok Habis') {
                    orderBtn.innerHTML = '<i class="fas fa-times-circle mr-2"></i>Stok Tidak Mencukupi';
                } else {
                    orderBtn.innerHTML = '<i class="fas fa-times-circle mr-2"></i>Tidak Tersedia';
                }
            } else {
                orderBtn.disabled = false;
                orderBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                orderBtn.innerHTML = '<i class="fas fa-shopping-cart mr-2"></i>Tambah ke Keranjang';
            }

            // Open modal
            document.getElementById('menuDetailModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        // Modal quantity controls
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

        // Close modal
        function closeMenuDetail(event) {
            if (event && event.target.id !== 'menuDetailModal') return;
            document.getElementById('menuDetailModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
            currentModalMenuId = null;
        }

        // Close on escape
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeMenuDetail();
            }
        });

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            const firstBtn = document.querySelector('.kategori-btn');
            if (firstBtn) {
                firstBtn.classList.add('text-white', 'bg-orange-500', 'shadow-lg', 'shadow-orange-500/25');
                firstBtn.classList.remove('bg-gray-800', 'text-gray-300');
            }
        });

        function showMoreMenu() {

            const hiddenCards = document.querySelectorAll('.extra-menu');

            hiddenCards.forEach((card, index) => {

                setTimeout(() => {

                    // tampilkan card
                    card.classList.remove('hidden');

                    // reset animasi
                    card.classList.remove('active');

                    // trigger ulang animasi reveal
                    setTimeout(() => {
                        card.classList.add('active');
                    }, 50);

                }, index * 120);

            });

            // sembunyikan tombol
            const btn = document.getElementById('showMoreBtn');

            btn.style.opacity = '0';

            setTimeout(() => {
                btn.style.display = 'none';
            }, 300);
        }
    </script>

    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .line-clamp-1 {
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>

</section>
