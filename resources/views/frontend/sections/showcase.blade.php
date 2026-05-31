<section class="relative h-[190vh] sm:h-[220vh] md:h-[260vh] lg:h-[300vh] bg-black">

    {{-- STICKY SHOWCASE --}}
    <div class="sticky top-0 h-[100svh] md:h-screen overflow-hidden">

        {{-- BACKGROUND VIDEO --}}
        <div class="absolute inset-0 bg-black">

            <video autoplay muted loop playsinline preload="metadata"
                poster="{{ asset('images/hero/backgroundsate.png') }}"
                class="motion-video absolute inset-0 h-full w-full object-cover scale-[1.04] md:scale-[1.02] transform-gpu">

                <source src="{{ asset('videos/sate.mp4') }}" type="video/mp4">

            </video>

            {{-- FALLBACK IMAGE --}}
            <img src="{{ asset('images/hero/backgroundsate.png') }}" alt="Sate Simpang Tiga"
                class="absolute inset-0 h-full w-full object-cover opacity-0 pointer-events-none">

            {{-- DARK OVERLAY --}}
            <div class="absolute inset-0 bg-black/75 md:bg-black/70"></div>

            {{-- GRADIENT --}}
            <div
                class="absolute inset-0
                bg-gradient-to-b
                from-black/85
                via-black/25
                md:via-black/20
                to-black">
            </div>

        </div>

        {{-- TOP FADE --}}
        <div
            class="absolute top-0 left-0 w-full h-32 sm:h-40 md:h-56
            bg-gradient-to-b
            from-black
            via-black/70
            to-transparent
            z-20">
        </div>

        {{-- CONTENT --}}
        <div class="relative z-30 h-full flex items-center justify-center px-4 sm:px-6">

            <div class="text-center max-w-[92%] sm:max-w-3xl lg:max-w-5xl">

                <h2
                    class="text-3xl sm:text-4xl md:text-5xl lg:text-7xl
                    font-bold leading-[1.05] md:leading-[0.95] tracking-tight">

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

    </div>

</section>
