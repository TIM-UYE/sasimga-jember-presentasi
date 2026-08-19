@once
    @push('preloads')
        <link rel="preload" as="image" href="{{ asset('images/hero/bgsate.png') }}">
        <link rel="preload" as="image" href="{{ asset('images/hero/sate.png') }}">
        <link rel="preload" as="image" href="{{ asset('images/logo/logo.png') }}">
    @endpush
@endonce

<section id="hero" class="relative min-h-[720px] overflow-hidden bg-black
    sm:min-h-[780px] lg:min-h-screen">

    {{-- =========================================================
        BACKGROUND
    ========================================================== --}}
    <div class="absolute inset-0">

        <img src="{{ asset('images/hero/bgsate.png') }}" alt="Hidangan Sate Simpang Tiga" loading="eager"
            fetchpriority="high" decoding="async" data-critical-asset
            class="h-full w-full object-cover opacity-30 md:opacity-100">

        {{-- Dark overlay --}}
        <div class="absolute inset-0 bg-black/85 md:bg-black/60"></div>

        {{-- Gradient agar tulisan tetap terbaca --}}
        <div
            class="absolute inset-0 bg-gradient-to-b
            from-black/90 via-black/75 to-black
            lg:bg-gradient-to-r lg:from-black
            lg:via-black/75 lg:to-black/20">
        </div>

        {{-- Orange decorative glow --}}
        <div class="absolute left-1/4 top-1/3 h-72 w-72
            rounded-full bg-orange-500/5 blur-[120px]">
        </div>

    </div>


    {{-- =========================================================
        LOGO BESAR DI TENGAH ATAS
    ========================================================== --}}
    <div class="pointer-events-none absolute left-1/2 top-16 z-20
        -translate-x-1/2 md:top-20">

        <img src="{{ asset('images/logo/logo.png') }}" alt="Logo Rumah Makan Simpang Tiga"
            class="w-[125px] object-contain drop-shadow-2xl
            sm:w-[145px] md:w-[165px] lg:w-[190px] xl:w-[220px]">

    </div>


    {{-- =========================================================
        JAM OPERASIONAL
    ========================================================== --}}
    <div class="absolute right-3 top-20 z-30 sm:right-5 lg:right-7">

        {{-- Desktop --}}
        <div
            class="hidden items-center gap-3 rounded-full
            border border-white/10 bg-black/60 px-4 py-2
            text-white shadow-lg backdrop-blur-md sm:flex">

            <span class="relative flex h-2.5 w-2.5">
                <span
                    class="absolute inline-flex h-full w-full
                    animate-ping rounded-full bg-green-400 opacity-75">
                </span>

                <span class="relative inline-flex h-2.5 w-2.5
                    rounded-full bg-green-500">
                </span>
            </span>

            <i class="fas fa-clock text-sm text-orange-500"></i>

            <span class="text-xs sm:text-sm">
                Buka 11:00 — Tutup 23:00
            </span>

        </div>

        {{-- Mobile --}}
        <div
            class="flex items-center gap-2 rounded-full
            border border-white/10 bg-black/60 px-3 py-1.5
            text-white shadow-lg backdrop-blur-md sm:hidden">

            <span class="h-2 w-2 rounded-full bg-green-500"></span>

            <i class="fas fa-clock text-[10px] text-orange-500"></i>

            <span class="text-[10px]">
                11:00–23:00
            </span>

        </div>

    </div>


    {{-- =========================================================
        MOBILE DECORATIVE IMAGE
    ========================================================== --}}
    <div class="pointer-events-none absolute right-[-50px] top-[150px]
        z-10 opacity-25 lg:hidden">

        <img src="{{ asset('images/hero/sate.png') }}" alt="" aria-hidden="true"
            class="w-[190px] drop-shadow-[0_20px_45px_rgba(0,0,0,0.7)]
            sm:w-[250px]">

    </div>


    {{-- =========================================================
        HERO CONTENT
    ========================================================== --}}
    <div
        class="container-main relative z-20 grid min-h-[720px]
        items-center gap-8 pb-16 pt-52
        sm:min-h-[780px] sm:pt-60
        lg:min-h-screen lg:grid-cols-2 lg:gap-10
        lg:pb-24 lg:pt-72 xl:pt-80">

        {{-- =====================================================
            LEFT CONTENT
        ====================================================== --}}
        <div class="order-1 max-w-2xl text-left">

            {{-- Little title --}}
            <div
                class="reveal delay-100 mb-3 inline-flex items-center
                gap-2 rounded-full border border-orange-500/25
                bg-orange-500/10 px-3 py-1.5">

                <span class="h-2 w-2 rounded-full bg-orange-500"></span>

                <p
                    class="text-[10px] font-semibold uppercase
                    tracking-[0.16em] text-orange-400 sm:text-xs">

                    {{ __('frontend.hero.label') }}

                </p>

            </div>


            {{-- Main title --}}
            <h1
                class="reveal delay-200 text-[2rem] font-bold
                leading-[1.05] tracking-tight
                sm:text-[2.65rem] md:text-6xl
                lg:leading-[0.98] xl:text-7xl">

                <span class="block text-white md:inline md:mr-3">
                    SATE
                </span>

                <span
                    class="block bg-gradient-to-r from-orange-400
                    via-orange-500 to-amber-500
                    bg-clip-text text-transparent md:inline">

                    SIMPANG TIGA

                </span>

            </h1>


            {{-- Description --}}
            <p
                class="reveal delay-300 mt-4 max-w-xl
                text-sm leading-relaxed text-zinc-400
                sm:text-base md:text-lg">

                {{ __('frontend.hero.description') }}

            </p>


            {{-- =================================================
                TOMBOL UTAMA
            ================================================== --}}
            <div class="reveal delay-500 mt-6 flex flex-wrap
                items-center gap-3 sm:mt-8 sm:gap-4">

                {{-- Pesan sekarang --}}
                <a href="{{ route('frontend.menu') }}"
                    class="btn-primary inline-flex items-center
                    justify-center gap-2 px-4 py-2.5
                    text-xs transition duration-300
                    hover:-translate-y-1
                    hover:shadow-lg hover:shadow-orange-500/30
                    sm:px-6 sm:py-3 sm:text-base">

                    <i class="fas fa-utensils"></i>

                    {{ __('frontend.hero.order_button') }}

                </a>

                {{-- Reservasi sekarang --}}
                <a href="{{ route('frontend.reservasi') }}"
                    class="btn-outline inline-flex items-center
                    justify-center gap-2 px-4 py-2.5
                    text-xs transition duration-300
                    hover:-translate-y-1
                    hover:border-orange-400
                    hover:bg-orange-500/10
                    sm:px-6 sm:py-3 sm:text-base">

                    <i class="fas fa-calendar-check"></i>

                    {{ __('frontend.hero.reservation_button') }}

                </a>

            </div>


            {{-- =================================================
                NAVIGASI CEPAT HERO
            ================================================== --}}
            <div class="reveal delay-500 mt-6">

                <p
                    class="mb-3 text-[10px] font-semibold uppercase
                    tracking-[0.18em] text-zinc-500 sm:text-xs">

                    Jelajahi Simpang Tiga

                </p>

                <div class="flex flex-wrap items-center gap-2">

                    {{-- Menu spesial --}}
                    <a href="#menu-spesial" aria-label="Lihat menu spesial"
                        class="group inline-flex items-center gap-2
                        rounded-full border border-white/15
                        bg-white/[0.06] px-3.5 py-2
                        text-xs text-zinc-300 backdrop-blur-md
                        transition duration-300
                        hover:-translate-y-1
                        hover:border-orange-500
                        hover:bg-orange-500/15
                        hover:text-orange-400
                        sm:text-sm">

                        <i
                            class="fas fa-fire text-orange-500
                            transition-transform duration-300
                            group-hover:scale-110">
                        </i>

                        <span>Menu Spesial</span>

                        <i
                            class="fas fa-arrow-down text-[9px]
                            opacity-50 transition-transform duration-300
                            group-hover:translate-y-0.5">
                        </i>

                    </a>


                    {{-- Review artis --}}
                    <a href="#review-artis" aria-label="Lihat review artis"
                        class="group inline-flex items-center gap-2
                        rounded-full border border-white/15
                        bg-white/[0.06] px-3.5 py-2
                        text-xs text-zinc-300 backdrop-blur-md
                        transition duration-300
                        hover:-translate-y-1
                        hover:border-orange-500
                        hover:bg-orange-500/15
                        hover:text-orange-400
                        sm:text-sm">

                        <i
                            class="fas fa-star text-orange-500
                            transition-transform duration-300
                            group-hover:rotate-12 group-hover:scale-110">
                        </i>

                        <span>Review Artis</span>

                        <i
                            class="fas fa-arrow-down text-[9px]
                            opacity-50 transition-transform duration-300
                            group-hover:translate-y-0.5">
                        </i>

                    </a>


                    {{-- Galeri --}}
                    <a href="#galeri" aria-label="Lihat galeri Simpang Tiga"
                        class="group inline-flex items-center gap-2
                        rounded-full border border-white/15
                        bg-white/[0.06] px-3.5 py-2
                        text-xs text-zinc-300 backdrop-blur-md
                        transition duration-300
                        hover:-translate-y-1
                        hover:border-orange-500
                        hover:bg-orange-500/15
                        hover:text-orange-400
                        sm:text-sm">

                        <i
                            class="fas fa-images text-orange-500
                            transition-transform duration-300
                            group-hover:scale-110">
                        </i>

                        <span>Galeri</span>

                        <i
                            class="fas fa-arrow-down text-[9px]
                            opacity-50 transition-transform duration-300
                            group-hover:translate-y-0.5">
                        </i>

                    </a>


                    {{-- Testimoni --}}
                    <a href="#testimoni" aria-label="Lihat testimoni pelanggan"
                        class="group inline-flex items-center gap-2
                        rounded-full border border-white/15
                        bg-white/[0.06] px-3.5 py-2
                        text-xs text-zinc-300 backdrop-blur-md
                        transition duration-300
                        hover:-translate-y-1
                        hover:border-orange-500
                        hover:bg-orange-500/15
                        hover:text-orange-400
                        sm:text-sm">

                        <i
                            class="fas fa-comment-dots text-orange-500
                            transition-transform duration-300
                            group-hover:scale-110">
                        </i>

                        <span>Testimoni</span>

                        <i
                            class="fas fa-arrow-down text-[9px]
                            opacity-50 transition-transform duration-300
                            group-hover:translate-y-0.5">
                        </i>

                    </a>

                </div>

            </div>

        </div>


        {{-- =====================================================
            RIGHT IMAGE — DESKTOP
        ====================================================== --}}
        <div class="relative order-2 hidden translate-y-10
            justify-center lg:flex lg:justify-end">

            {{-- Glow --}}
            <div
                class="absolute inset-0 m-auto h-[360px] w-[360px]
                rounded-full bg-orange-500/10 blur-[100px]
                xl:h-[450px] xl:w-[450px]">
            </div>

            <img src="{{ asset('images/hero/sate.png') }}" alt="Hidangan Sate Simpang Tiga" loading="eager"
                fetchpriority="high" decoding="async" data-critical-asset
                class="reveal-scale animate-float relative
                w-[460px] max-w-[90vw]
                drop-shadow-[0_30px_60px_rgba(0,0,0,0.6)]
                xl:w-[600px]">

        </div>

    </div>


    {{-- =========================================================
        SCROLL INDICATOR
    ========================================================== --}}
    <a href="#menu-spesial" aria-label="Scroll ke bagian berikutnya"
        class="absolute bottom-7 left-1/2 z-30 hidden
        -translate-x-1/2 flex-col items-center
        gap-2 text-zinc-500 transition
        hover:text-orange-500 lg:flex">

        <span class="text-[9px] uppercase tracking-[0.22em]">
            Scroll
        </span>

        <span class="flex h-8 w-5 justify-center rounded-full
            border border-white/20 pt-1.5">

            <span class="h-1.5 w-1 animate-bounce
                rounded-full bg-orange-500">
            </span>

        </span>

    </a>


    {{-- =========================================================
        BOTTOM SHADOW
    ========================================================== --}}
    <div
        class="pointer-events-none absolute bottom-0 left-0 z-10
        h-24 w-full bg-gradient-to-b
        from-transparent via-black/60 to-black
        sm:h-36 lg:h-48">
    </div>

</section>
