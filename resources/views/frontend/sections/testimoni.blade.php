<section class="bg-black py-24 overflow-hidden">

    <div class="container-main">

        {{-- SECTION HEADER --}}
        <div class="max-w-2xl mx-auto text-center mb-16 reveal">

            <span
                class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-orange-500/10 text-orange-400 text-xs font-medium tracking-wider uppercase mb-5 ring-1 ring-orange-500/20">
                <span class="w-1.5 h-1.5 rounded-full bg-orange-400 animate-pulse"></span>
                {{ __('frontend.testimoni.pre-title') }}
            </span>

            <h2 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-4 tracking-tight">
                <span class="text-white">
                    {{ __('frontend.testimoni.white-title') }}

                </span>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-400 via-orange-500 to-amber-500">
                    {{ __('frontend.testimoni.orange-title') }}

                </span>
            </h2>

            <p class="text-zinc-400 text-base leading-relaxed">
                {{ __('frontend.testimoni.description') }}

            </p>
        </div>

        @php
            $hasReviews = collect($testimonis ?? [])->isNotEmpty();
        @endphp

        {{-- CARD GRID --}}
        <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-8">

            @forelse ($testimonis as $index => $testimoni)
                <div class="group relative bg-zinc-900/70 backdrop-blur-sm border border-white/5 rounded-3xl overflow-hidden hover:border-orange-500/30 transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl hover:shadow-orange-500/10 reveal"
                    style="animation-delay: {{ $index * 0.1 }}s">

                    {{-- BANNER GRADIENT --}}
                    <div class="relative h-52 overflow-hidden flex items-center justify-center bg-gradient-to-br from-orange-500/30 via-orange-600/20 to-amber-500/10">
                        <div class="absolute inset-0 opacity-10" style="background: repeating-linear-gradient(45deg, transparent, transparent 20px, rgba(255,255,255,0.03) 20px, rgba(255,255,255,0.03) 40px)"></div>

                        {{-- OVERLAY --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>

                        {{-- SOURCE & SENTIMENT --}}
                        <div class="absolute top-4 right-4 flex gap-2">
                            @if(data_get($testimoni, 'sentiment'))
                                <span class="px-3 py-1 rounded-full text-xs font-semibold backdrop-blur-sm
                                    {{ data_get($testimoni, 'sentiment') === 'positif' ? 'bg-emerald-500/90 text-white' : '' }}
                                    {{ data_get($testimoni, 'sentiment') === 'netral' ? 'bg-zinc-500/90 text-white' : '' }}
                                    {{ data_get($testimoni, 'sentiment') === 'negatif' ? 'bg-red-500/90 text-white' : '' }}">
                                    <i class="fas
                                        {{ data_get($testimoni, 'sentiment') === 'positif' ? 'fa-smile' : '' }}
                                        {{ data_get($testimoni, 'sentiment') === 'netral' ? 'fa-meh' : '' }}
                                        {{ data_get($testimoni, 'sentiment') === 'negatif' ? 'fa-frown' : '' }}
                                    mr-1"></i>
                                    {{ data_get($testimoni, 'sentiment') }}
                                </span>
                            @endif
                            <span class="px-3 py-1 rounded-full bg-orange-500/90 backdrop-blur-sm text-white text-xs font-semibold">
                                Google
                            </span>
                        </div>

                    </div>

                    {{-- CONTENT --}}
                    <div class="p-6">

                        {{-- STARS --}}
                        <div class="flex items-center gap-1 mb-4">

                            @for ($i = 0; $i < data_get($testimoni, 'rating', 5); $i++)
                                <i class="fas fa-star text-amber-400 text-sm"></i>
                            @endfor

                            <span class="text-zinc-500 text-xs ml-2">
                                {{ data_get($testimoni, 'rating', 5) }}/5
                            </span>

                        </div>

                        {{-- TESTI --}}
                        <p class="text-zinc-300 leading-relaxed mb-6 line-clamp-4">

                            “{{ data_get($testimoni, 'text', 'Belum ada testimoni.') }}”

                        </p>

                        {{-- USER WITH GOOGLE PROFILE PHOTO --}}
                        <div class="flex items-center gap-3">

                            {{-- Avatar Photo dari Google --}}
                            @php
                                $photoUrl = data_get($testimoni, 'profile_photo_url', '');
                            @endphp
                            <div class="flex-shrink-0">
                                @if($photoUrl)
                                    <img src="{{ $photoUrl }}"
                                        onerror="this.parentElement.innerHTML = '<div class=\'w-12 h-12 rounded-full bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center\'><i class=\'fas fa-user text-white\'></i></div>'"
                                        class="w-12 h-12 rounded-full object-cover ring-2 ring-orange-500/30"
                                        alt="{{ data_get($testimoni, 'author_name', 'User') }}">
                                @else
                                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center">
                                        <i class="fas fa-user text-white"></i>
                                    </div>
                                @endif
                            </div>

                            <div class="flex-1 min-w-0">
                                <h3 class="text-white font-semibold text-lg truncate">
                                    {{ data_get($testimoni, 'author_name', 'Anonymous') }}
                                </h3>
                                <p class="text-zinc-500 text-sm">
                                    {{ data_get($testimoni, 'relative_time_description', 'Baru saja') }}
                                </p>
                            </div>

                            {{-- QUOTE ICON --}}
                            <div class="flex-shrink-0 w-10 h-10 rounded-2xl bg-orange-500/10 flex items-center justify-center">
                                <i class="fas fa-quote-right text-orange-400 text-sm"></i>
                            </div>

                        </div>

                    </div>

                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <div class="mx-auto max-w-sm">
                        <i class="fab fa-google mb-4 text-5xl text-zinc-600"></i>
                        <p class="text-zinc-400 mb-2">Belum ada testimoni dari Google.</p>
                        <p class="text-zinc-500 text-sm">Testimoni akan muncul setelah Admin melakukan scraping Google Reviews.</p>
                    </div>
                </div>
            @endforelse

        </div>

        {{-- TOMBOL LIHAT SEMUA --}}
        <div class="flex justify-center mt-12">
            <a href="{{ route('frontend.testimoni.index') }}"
                class="inline-flex items-center gap-3 px-8 py-3.5 bg-white text-black rounded-2xl font-semibold text-sm tracking-wider hover:bg-orange-500 hover:text-white transition-all duration-300 hover:scale-105 active:scale-95">
                <span> {{ __('frontend.menu.more_button') }}
                </span>
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        {{-- DOT INDICATOR --}}
        <div class="flex justify-center items-center gap-3 mt-14">

            <div class="w-2.5 h-2.5 bg-zinc-700 rounded-full"></div>

            <div class="w-10 h-2 rounded-full bg-gradient-to-r from-orange-400 to-amber-500">
            </div>

            <div class="w-2.5 h-2.5 bg-zinc-700 rounded-full"></div>

        </div>

    </div>

</section>
