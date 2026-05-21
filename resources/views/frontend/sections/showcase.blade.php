<section class="relative h-[220vh]">

    {{-- TOP FADE --}}
    <div
        class="absolute top-0 left-0 w-full h-56
        bg-gradient-to-b
        from-black
        via-black/70
        to-transparent
        z-20">
    </div>

    {{-- FIXED BACKGROUND --}}
    <div class="fixed inset-0 -z-10">

        <video autoplay muted loop playsinline preload="metadata" poster="{{ asset('images/hero/backgroundsate.png') }}"
            class="motion-video hidden md:block w-full h-full object-cover">

            <source src="{{ asset('videos/sate.mp4') }}" type="video/mp4">

        </video>

        <img src="{{ asset('images/hero/backgroundsate.png') }}" alt="Sate Simpang Tiga"
            class="block md:hidden w-full h-full object-cover">

        {{-- DARK OVERLAY --}}
        <div class="absolute inset-0 bg-black/70"></div>

        {{-- GRADIENT --}}
        <div
            class="absolute inset-0
            bg-gradient-to-b
            from-black/80
            via-black/20
            to-black">
        </div>

    </div>

    {{-- CONTENT --}}
    <div class="sticky top-0 h-screen
        flex items-center justify-center
        px-6 z-20">

        <div class="text-center max-w-5xl">

            <h2 class="text-4xl md:text-5xl lg:text-7xl
                font-bold leading-[0.95] tracking-tight">

                <span class="text-white">
                    {{ __('frontend.showcase.white') }}
                </span>
                <span
                    class="text-transparent bg-clip-text
                    bg-gradient-to-r
                    from-orange-400
                    via-orange-500
                    to-amber-500">

                    {{ __('frontend.showcase.orange') }}

                </span>

            </h2>

        </div>

    </div>

</section>
