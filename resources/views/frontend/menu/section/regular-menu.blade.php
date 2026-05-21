<!-- REGULAR MENU SECTION -->
    <section id="regularMenuSection" class="relative bg-gradient-to-br from-black via-gray-900 to-black px-6 pb-32 section-reveal overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10 pointer-events-none">
            <div class="absolute top-20 left-10 w-64 h-64 bg-orange-500 rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 right-10 w-96 h-96 bg-orange-600 rounded-full blur-3xl"></div>
        </div>
        <div class="max-w-7xl mx-auto mt-16 relative z-10">

            @if ($menus->isEmpty())
                <div class="text-center py-20">
                    <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-gray-800 mb-6">
                        <i class="fas fa-utensils text-4xl text-gray-600"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-400 mb-2">Belum Ada Menu</h3>
                    <p class="text-gray-500">Menu akan segera tersedia</p>
                </div>
            @endif

            <div class="grid gap-8 sm:grid-cols-2 xl:grid-cols-3">
                @foreach ($menus as $menu)
                    <div class="menu-frame" data-kategori-id="{{ $menu->kategori_id ?? 0 }}">
                        <div class="menu-frame-inner">
                            <div class="group w-full h-full overflow-hidden rounded-lg bg-gradient-to-b from-gray-800 to-gray-950 shadow-xl hover:shadow-2xl hover:shadow-orange-500/10 transition-all duration-500 menu-card border border-white/5 hover:border-orange-500/30 menu-card-tilt flex flex-col"
                                >
                                <div class="menu-card-tilt-inner flex flex-col flex-1 min-h-0">
                                    <!-- IMAGE -->
                                    <div class="relative menu-card-shine overflow-hidden" style="flex: 1.6;">
                                        @if ($menu->gambar)
                                            <img src="{{ asset('storage/menu/' . $menu->gambar) }}" alt="{{ $menu->nama_menu }}"
                                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                        @else
                                            <div
                                                class="w-full h-full bg-gradient-to-br from-gray-800 to-gray-900 flex items-center justify-center">
                                                <i class="fas fa-utensils text-gray-500 text-3xl"></i>
                                            </div>
                                        @endif

                                        <!-- Overlay on hover -->
                                        <div
                                            class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        </div>

                                        <!-- Category Badge -->
                                        <div class="absolute top-3 left-3 z-10">
                                            <span
                                                class="px-3 py-1 bg-black/60 backdrop-blur-sm rounded-full text-xs text-white font-semibold">
                                                {{ $menu->kategori->nama_kategori ?? 'Menu' }}
                                            </span>
                                        </div>

                                        <!-- Availability Badge (check both is_available AND calculated stock) -->
                                        <div class="absolute top-3 right-3 z-10">
                                            @php
                                                $menuHasStock = $menu->is_available && $menu->calculated_stock > 0;
                                            @endphp
                                            @if ($menuHasStock)
                                                <span
                                                    class="px-3 py-1 bg-green-500/90 backdrop-blur-sm rounded-full text-xs text-white font-semibold flex items-center gap-1.5">
                                                    <span class="w-2 h-2 bg-white rounded-full animate-pulse"></span>
                                                    Tersedia
                                                </span>
                                            @else
                                                <span
                                                    class="px-3 py-1 bg-red-500/90 backdrop-blur-sm rounded-full text-xs text-white font-semibold">
                                                    Habis
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- CONTENT (45% of height) -->
                                    <div class="p-5 flex flex-col justify-center" style="flex: 1;">
                                        <h3
                                            class="font-bold text-base text-white mb-1.5 line-clamp-1 group-hover:text-orange-400 transition-colors">
                                            {{ $menu->nama_menu }}
                                        </h3>

                                        <p class="text-xs text-gray-400 mb-3 line-clamp-2 leading-relaxed">
                                            {{ $menu->deskripsi ?? 'Tidak ada deskripsi' }}
                                        </p>

                                        <!-- Price & Actions -->
                                        <div class="flex items-center justify-between gap-1">
                                            <span class="text-base font-bold text-orange-400 menu-price">
                                                Rp {{ number_format($menu->harga, 0, ',', '.') }}
                                            </span>

                                            <div class="flex gap-2">
                                                <button type="button" onclick="quickAddToCart({{ $menu->id }}, this)"
                                                    class="w-9 h-9 rounded-full bg-orange-500 hover:bg-orange-600 text-white flex items-center justify-center transition-all hover:scale-110 disabled:opacity-50 disabled:cursor-not-allowed text-xs"
                                                    {{ (!$menu->is_available || $menu->calculated_stock <= 0) ? 'disabled' : '' }}>
                                                    <i class="fas fa-plus"></i>
                                                </button>

                                                <button onclick='openMenuDetail(@json($menu))'
                                                    class="w-9 h-9 rounded-full bg-gray-700 hover:bg-gray-600 text-white flex items-center justify-center transition-all hover:scale-110 text-xs">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- No Results Message -->
            <div id="noResults" class="hidden text-center py-20">
                <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-gray-800 mb-6">
                    <i class="fas fa-search text-4xl text-gray-600"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-400 mb-2">Menu Tidak Ditemukan</h3>
                <p class="text-gray-500">Coba dengan kata kunci lain</p>
            </div>
        </div>
    </section>
