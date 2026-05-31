<section class="relative bg-black py-14 sm:py-16 md:py-20 lg:py-24 overflow-hidden">

    {{-- HEADER --}}
    <div class="max-w-2xl mx-auto text-center mb-10 sm:mb-12 lg:mb-16 reveal px-4">

        <span
            class="inline-flex items-center gap-2 px-3 sm:px-4 py-1.5 rounded-full bg-orange-500/10 text-orange-400 text-[10px] sm:text-xs font-medium tracking-wider uppercase mb-4 sm:mb-5 ring-1 ring-orange-500/20">
            <span class="w-1.5 h-1.5 rounded-full bg-orange-400 animate-pulse"></span>
            {{ __('frontend.about-header.pre-title') }}
        </span>

        <h2 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold mb-3 sm:mb-4 tracking-tight leading-tight">
            <span class="text-white">{{ __('frontend.about-header.white-title') }}</span>
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-amber-500">
                {{ __('frontend.about-header.orange-title') }}
            </span>
        </h2>

        <p class="text-zinc-400 text-sm sm:text-base leading-relaxed">
            {{ __('frontend.about-header.sub-title') }}
        </p>

    </div>

    <div class="container-main grid grid-cols-1 md:grid-cols-2 items-center gap-8 sm:gap-10 lg:gap-16">

        {{-- KIRI: IMAGE --}}
        <div class="relative reveal-left group">

            {{-- IMAGE --}}
            <img src="{{ asset('images/about/depan.jpg') }}"
                class="w-full h-[320px] sm:h-[400px] md:h-[480px] lg:h-[550px] object-cover rounded-2xl md:rounded-3xl shadow-2xl shadow-orange-500/10">

            {{-- OVERLAY GLOW --}}
            <div
                class="absolute inset-0 rounded-2xl md:rounded-3xl bg-gradient-to-t from-black/50 via-transparent to-transparent">
            </div>

            {{-- FLOATING CARD --}}
            <div
                class="absolute bottom-4 left-4 sm:bottom-6 sm:left-6 bg-black/70 backdrop-blur-md border border-white/10 rounded-xl sm:rounded-2xl px-4 sm:px-5 py-3 sm:py-4">

                <p class="text-white text-base sm:text-lg font-semibold">
                    {{ __('frontend.about.bold-image-title') }}
                </p>

                <p class="text-zinc-400 text-xs sm:text-sm">
                    {{ __('frontend.about.medium-image-title') }}
                </p>

            </div>

        </div>

        {{-- KANAN: CONTENT --}}
        <div class="relative z-10 reveal-right delay-200">

            {{-- SMALL LABEL --}}
            <span
                class="inline-flex items-center gap-2 px-3 sm:px-4 py-1.5 rounded-full bg-orange-500/10 text-orange-400 text-[10px] sm:text-xs font-medium tracking-[0.16em] sm:tracking-[0.2em] uppercase ring-1 ring-orange-500/20 mb-4 sm:mb-6">

                <span class="w-1.5 h-1.5 rounded-full bg-orange-400 animate-pulse"></span>
                {{ __('frontend.about.pre-title') }}

            </span>

            {{-- TITLE --}}
            <h2 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold leading-tight tracking-tight">

                <span class="text-white">
                    {{ __('frontend.about.white-title') }}
                </span>

                <br>

                <span
                    class="text-transparent bg-clip-text bg-gradient-to-r from-orange-400 via-orange-500 to-amber-500">
                    {{ __('frontend.about.orange-title') }}
                </span>

            </h2>

            {{-- DESCRIPTION --}}
            <p class="text-zinc-400 mt-4 sm:mt-6 text-sm sm:text-base leading-relaxed max-w-xl">
                {{ __('frontend.about.description') }}
            </p>

            {{-- FEATURES --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 mt-7 sm:mt-10">

                <div
                    class="bg-zinc-900/70 border border-white/5 rounded-xl sm:rounded-2xl p-4 sm:p-5 hover:border-orange-500/30 transition-all">

                    <div
                        class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-orange-500/10 flex items-center justify-center mb-3 sm:mb-4">
                        <i class="fas fa-fire text-orange-400 text-sm sm:text-base"></i>
                    </div>

                    <h3 class="text-white text-sm sm:text-base font-medium mb-1.5 sm:mb-2">
                        {{ __('frontend.about.title-card1') }}
                    </h3>

                    <p class="text-zinc-400 text-xs sm:text-sm leading-relaxed">
                        {{ __('frontend.about.description-card1') }}
                    </p>

                </div>

                <div
                    class="bg-zinc-900/70 border border-white/5 rounded-xl sm:rounded-2xl p-4 sm:p-5 hover:border-orange-500/30 transition-all">

                    <div
                        class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-orange-500/10 flex items-center justify-center mb-3 sm:mb-4">
                        <i class="fas fa-utensils text-orange-400 text-sm sm:text-base"></i>
                    </div>

                    <h3 class="text-white text-sm sm:text-base font-medium mb-1.5 sm:mb-2">
                        {{ __('frontend.about.title-card2') }}
                    </h3>

                    <p class="text-zinc-400 text-xs sm:text-sm leading-relaxed">
                        {{ __('frontend.about.description-card2') }}
                    </p>

                </div>

            </div>

        </div>

    </div>

</section>
