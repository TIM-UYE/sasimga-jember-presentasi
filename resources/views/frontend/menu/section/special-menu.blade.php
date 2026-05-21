<!-- SPECIAL MENU SECTION -->
    <section id="specialMenuSection" class="relative bg-gradient-to-br from-black via-gray-900 to-black px-6 pb-32 hidden overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10 pointer-events-none">
            <div class="absolute top-20 left-10 w-64 h-64 bg-orange-500 rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 right-10 w-96 h-96 bg-orange-600 rounded-full blur-3xl"></div>
        </div>
        <div class="max-w-7xl mx-auto mt-16 relative z-10">

            <!-- SPECIAL GRID -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">

                @forelse($specials as $special)
                    <div class="special-frame">
                        <div class="special-frame-inner">
                            <div
                                class="group w-full h-full overflow-hidden rounded-lg cursor-pointer bg-gradient-to-b from-gray-800 to-gray-950 border border-white/5 hover:border-orange-500/40 transition-all duration-500 menu-card-tilt special-card-premium special-card flex flex-col"
                                onclick="openSpecialMenuModal({{ json_encode($special) }})"
                                style="--delay: {{ $loop->index * 0.08 }}s">
                                <div class="menu-card-tilt-inner flex flex-col flex-1 min-h-0 relative overflow-hidden">
                                    <!-- IMAGE -->
                                    <div class="relative overflow-hidden" style="flex: 1.6;">
                                        @if($special->banner_image)
                                            <img src="{{ asset('storage/' . $special->banner_image) }}"
                                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                        @else
                                            <img src="{{ asset('images/menu-special/tumpeng.jpg') }}"
                                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                        @endif

                                        <!-- OVERLAY -->
                                        <div class="absolute inset-0 bg-gradient-to-t from-black via-black/60 to-transparent"></div>

                                        <!-- SPARKLE OVERLAY -->
                                        <div class="special-pulse-ring"></div>

                                        <!-- BADGE -->
                                        <div class="absolute top-4 left-4 z-10">
                                            <span class="px-4 py-1.5 rounded-full bg-orange-500 text-white text-xs font-bold shadow-lg shadow-orange-500/30">
                                                PRE ORDER
                                            </span>
                                        </div>
                                    </div>

                                    <!-- CONTENT -->
                                    <div class="p-6 flex flex-col justify-center" style="flex: 1;">
                                        <h3 class="font-bold text-lg text-white mb-2 line-clamp-1">
                                            {{ $special->title }}
                                        </h3>

                                        <p class="text-sm text-gray-300 mb-4 line-clamp-2 leading-relaxed">
                                            {{ $special->short_description ?? 'Menu spesial untuk acara istimewa Anda.' }}
                                        </p>

                                        <div class="inline-flex items-center gap-3 text-orange-400 font-semibold group-hover:gap-4 transition-all text-sm">
                                            <span>Lihat Detail</span>
                                            <i class="fas fa-arrow-right" style="font-size: 10px;"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-2 text-center py-20">
                        <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-gray-800 mb-6">
                            <i class="fas fa-star text-4xl text-gray-600"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-400 mb-2">Belum Ada Menu Special</h3>
                        <p class="text-gray-500">Menu special akan segera tersedia</p>
                    </div>
                @endforelse

            </div>

        </div>
    </section>
