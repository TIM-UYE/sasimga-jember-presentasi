@if($galeris->isNotEmpty())

@once

    @push('styles')
        @vite(['resources/css/gallery-orbit.css'])
    @endpush

    @push('scripts')
        @vite(['resources/js/frontend/orbit-gallery.js'])
    @endpush

@endonce

<section id="orbitSection" class="relative min-h-screen bg-black overflow-hidden -mt-8 md:-mt-12">

    {{-- BACKGROUND GLOW --}}
    <div class="absolute inset-0 pointer-events-none z-0">

        <div
            class="absolute top-[10%] left-[5%]
            w-[500px] h-[500px]
            bg-orange-500/10 rounded-full blur-3xl">
        </div>

        <div
            class="absolute bottom-[5%] right-[5%]
            w-[400px] h-[400px]
            bg-amber-500/10 rounded-full blur-3xl">
        </div>

    </div>


    {{-- ORBIT SCENE --}}
    <div class="orbit-scene reveal">

        {{-- ORBIT WORLD --}}
        <div class="orbit-world" id="orbitWorld">

            @forelse($galeris->take(15) as $g)
                <div class="orbit-card">

                    <div class="orbit-float">

                        <div class="orbit-face">

                            <img src="{{ asset('storage/' . $g->image) }}" loading="lazy"
                                alt="{{ $g->title }}">

                        </div>

                    </div>

                </div>
            @empty
                {{-- Fallback: gunakan gambar placeholder jika tidak ada galeri --}}
                @for ($i = 0; $i < 5; $i++)
                <div class="orbit-card">

                    <div class="orbit-float">

                        <div class="orbit-face">
                            @php
                                $placeholderIndex = ($i % 5) + 1;
                            @endphp
                            <img src="{{ asset('images/gallery/gallery' . $placeholderIndex . '.jpg') }}" loading="lazy"
                                decoding="async" alt="Gallery Image">

                        </div>

                    </div>

                </div>
                @endfor
            @endforelse

        </div>


        {{-- TEXT DI DALAM ORBIT --}}
        <div class="gallery-headline">

            <div class="text-center max-w-5xl mb-16 px-6">

                <h2
                    class="text-3xl md:text-5xl lg:text-6xl
                    font-bold leading-[1.12]
                    tracking-tight">

                    <span class="headline-white inline-block text-white pb-1">
                        {{ __('frontend.orbit.white') }}
                    </span>

                    <span
                        class="headline-gradient inline-block pb-2
                        text-transparent bg-clip-text
                        bg-gradient-to-r
                        from-orange-400
                        via-orange-500
                        to-amber-500">

                        {{ __('frontend.orbit.orange') }}

                    </span>

                </h2>

            </div>

        </div>

    </div>


    {{-- BOTTOM CAPTION --}}
    <div
        class="absolute bottom-20 md:bottom-24 left-1/2 -translate-x-1/2
        z-[70]
        w-full max-w-3xl px-6
        text-center pointer-events-none
        reveal">

        <p
            class="text-white text-lg md:text-xl font-semibold leading-tight
            drop-shadow-[0_4px_20px_rgba(0,0,0,0.8)]">

            {{ __('frontend.orbit.subtitle') }}

        </p>

        <p
            class="mt-3 text-zinc-400 text-sm md:text-base leading-relaxed
            max-w-2xl mx-auto
            drop-shadow-[0_4px_18px_rgba(0,0,0,0.8)]">

            {{ __('frontend.orbit.description') }}

        </p>

    </div>


    {{-- VIGNETTE --}}
    <div class="orbit-vignette"></div>
    <br><br>

</section>
@endif
