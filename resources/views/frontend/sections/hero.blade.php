@once
    @push('preloads')
        <link rel="preload" as="image" href="{{ asset('images/hero/backgroundsate.png') }}">
        <link rel="preload" as="image" href="{{ asset('images/hero/sate.png') }}">
    @endpush
@endonce

<section class="relative overflow-hidden bg-black min-h-screen">

    {{-- BACKGROUND --}}
    <div class="absolute inset-0">

        <img src="{{ asset('images/hero/backgroundsate.png') }}" alt="Background Sate Simpang Tiga" loading="eager"
            fetchpriority="high" decoding="async" data-critical-asset
            class="w-full h-full object-cover opacity-30 md:opacity-100">

        {{-- DARK OVERLAY --}}
        <div class="absolute inset-0 bg-black/90 md:bg-black/60"></div>

        {{-- GRADIENT --}}
        <div class="absolute inset-0 bg-gradient-to-r from-black via-black/70 to-transparent"></div>

    </div>

    {{-- CONTENT --}}
    <div class="relative z-20 container-main min-h-screen grid lg:grid-cols-2 items-center gap-10 pt-32 pb-20">

        {{-- LEFT --}}
        <div class="max-w-2xl text-center lg:text-left order-2 lg:order-1">

            {{-- LITTLE TITLE --}}
            <p class="text-gray-400 text-lg reveal delay-100">
                {{ __('frontend.hero.label') }}
            </p>

            {{-- TITLE --}}
            <h1
                class="text-3xl sm:text-4xl md:text-5xl xl:text-6xl
                font-bold leading-[1] tracking-tight reveal delay-200 notranslate">

                <span class="text-white">
                    SATE
                </span>

                <span
                    class="text-transparent bg-clip-text
                    bg-gradient-to-r
                    from-orange-400
                    via-orange-500
                    to-amber-500">

                    SIMPANG TIGA

                </span>

            </h1>

            {{-- DESCRIPTION --}}
            <p
                class="text-zinc-400 mt-5 text-sm sm:text-base md:text-lg
                leading-relaxed max-w-xl mx-auto lg:mx-0 reveal delay-300">

                {{ __('frontend.hero.description') }}

            </p>

            {{-- BUTTON --}}
            <div class="mt-8 flex flex-col sm:flex-row items-center lg:items-start gap-4 reveal delay-500">

                {{-- MENU --}}
                <a href="{{ route('frontend.menu') }}"
                    class="btn-primary inline-flex items-center justify-center w-full sm:w-auto">

                    {{ __('frontend.hero.order_button') }}

                </a>

                {{-- RESERVASI --}}
                <a href="{{ route('frontend.reservasi') }}"
                    class="btn-outline inline-flex items-center justify-center w-full sm:w-auto">

                    {{ __('frontend.hero.reservation_button') }}

                </a>

            </div>

        </div>


        {{-- RIGHT IMAGE --}}
        <div class="relative flex justify-center lg:justify-end order-1 lg:order-2">

            <img src="{{ asset('images/hero/sate.png') }}" alt="Sate Simpang Tiga" loading="eager" fetchpriority="high"
                decoding="async" data-critical-asset
                class="w-[260px] sm:w-[340px] md:w-[420px] xl:w-[520px]
    drop-shadow-[0_30px_60px_rgba(0,0,0,0.6)]
    reveal-scale animate-float">

        </div>

    </div>


    {{-- BOTTOM SHADOW --}}
    <div
        class="absolute bottom-0 left-0 w-full h-56
        bg-gradient-to-b
        from-transparent
        via-black/60
        to-black
        z-20">
    </div>

</section>
