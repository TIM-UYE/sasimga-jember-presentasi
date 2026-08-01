@once
    @push('preloads')
        <link rel="preload" as="image" href="{{ asset('images/hero/backgroundsate.png') }}">
        <link rel="preload" as="image" href="{{ asset('images/hero/sate.png') }}">
    @endpush
@endonce

<section class="relative overflow-hidden bg-black min-h-[720px] sm:min-h-[780px] lg:min-h-[100vh] xl:min-h-[110vh]">

    {{-- BACKGROUND --}}
    <div class="absolute inset-0">

        <img src="{{ asset('images/hero/bgsate.png') }}" alt="Background Sate Simpang Tiga" loading="eager"
            fetchpriority="high" decoding="async" data-critical-asset
            class="w-full h-full object-cover opacity-25 md:opacity-100">

        {{-- DARK OVERLAY --}}
        <div class="absolute inset-0 bg-black/90 md:bg-black/60"></div>

        {{-- GRADIENT --}}
        <div
            class="absolute inset-0 bg-gradient-to-b lg:bg-gradient-to-r from-black via-black/80 lg:via-black/70 to-transparent">
        </div>

    </div>

{{-- LOGO WATERMARK --}}
<div
    class="absolute top-14 md:top-20 left-1/2 -translate-x-1/2
    pointer-events-none z-10">

    <img
        src="{{ asset('images/logo/logo.png') }}"
        alt="Logo Simpang Tiga"
        class="
            w-[120px]
            md:w-[100px]
            xl:w-[220px]
            object-contain
        ">
</div>

    {{-- MOBILE DECORATIVE IMAGE --}}
    <div class="pointer-events-none absolute right-[-48px] top-[105px] z-10 opacity-25 blur-[0.2px] lg:hidden">
        <img src="{{ asset('images/hero/sate.png') }}" alt="" aria-hidden="true"
            class="w-[185px] sm:w-[240px] drop-shadow-[0_20px_45px_rgba(0,0,0,0.7)]">
    </div>


{{-- CONTENT --}}
<div
    class="relative z-20 container-main min-h-[640px] sm:min-h-[700px] lg:min-h-[90vh]
    grid lg:grid-cols-2 items-center gap-5 lg:gap-10
    pt-48 sm:pt-56 lg:pt-72 xl:pt-80 pb-14 sm:pb-16 lg:pb-24
    translate-y-0">


    {{-- LEFT --}}
    <div class="max-w-2xl text-left lg:text-left order-1 lg:order-1">

        {{-- LITTLE TITLE --}}
        <p class="text-gray-400 text-xs sm:text-sm md:text-lg reveal delay-100">
            {{ __('frontend.hero.label') }}
        </p>

        {{-- TITLE --}}
        <h1
            class="mt-2 text-[2rem] sm:text-[2.65rem] md:text-6xl xl:text-7xl
            font-bold leading-[1.02] md:leading-[0.95]
            tracking-tight reveal delay-200 notranslate
            md:whitespace-nowrap">

            <span class="block md:inline text-white md:mr-3">
                SATE
            </span>

            <span
                class="block md:inline text-transparent bg-clip-text
                bg-gradient-to-r
                from-orange-400
                via-orange-500
                to-amber-500">

                SIMPANG TIGA

            </span>

        </h1>

        {{-- DESCRIPTION --}}
        <p
            class="text-zinc-400 mt-3 sm:mt-4 text-sm sm:text-base md:text-lg
            leading-relaxed max-w-full sm:max-w-xl lg:max-w-xl reveal delay-300">

            {{ __('frontend.hero.description') }}

        </p>

        {{-- BUTTON --}}
        <div class="mt-5 sm:mt-8 flex flex-row flex-wrap items-center gap-2.5 sm:gap-4 reveal delay-500">

            <a href="{{ route('frontend.menu') }}"
                class="btn-primary inline-flex items-center justify-center text-xs sm:text-base px-4 sm:px-6 py-2.5 sm:py-3">

                {{ __('frontend.hero.order_button') }}

            </a>

            <a href="{{ route('frontend.reservasi') }}"
                class="btn-outline inline-flex items-center justify-center text-xs sm:text-base px-4 sm:px-6 py-2.5 sm:py-3">

                {{ __('frontend.hero.reservation_button') }}

            </a>

        </div>

    </div>

    {{-- RIGHT IMAGE DESKTOP --}}
    <div class="relative hidden lg:flex justify-center lg:justify-end order-2 translate-y-10">

        <img src="{{ asset('images/hero/sate.png') }}"
            alt="Sate Simpang Tiga"
            loading="eager"
            fetchpriority="high"
            decoding="async"
            data-critical-asset
            class="w-[460px] xl:w-[600px] max-w-[90vw]
            drop-shadow-[0_30px_60px_rgba(0,0,0,0.6)]
            reveal-scale animate-float">

    </div>

</div>


    {{-- BOTTOM SHADOW --}}
    <div
        class="absolute bottom-0 left-0 w-full h-24 sm:h-36 lg:h-56
        bg-gradient-to-b
        from-transparent
        via-black/60
        to-black
        z-20">
    </div>

</section>
