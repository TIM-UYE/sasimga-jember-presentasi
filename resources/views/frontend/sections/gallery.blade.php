<section
    id="galeri"
    class="relative overflow-hidden bg-black
    px-4 py-14 scroll-mt-24
    sm:px-6 sm:py-20
    lg:py-28">

    {{-- =========================================================
        BACKGROUND
    ========================================================== --}}
    <div class="pointer-events-none absolute inset-0">

        {{-- Orange glow kiri --}}
        <div
            class="absolute left-[-15%] top-0
            h-56 w-56 rounded-full
            bg-orange-500/10 blur-3xl
            sm:left-0 sm:h-80 sm:w-80
            lg:h-96 lg:w-96">
        </div>

        {{-- Orange glow kanan --}}
        <div
            class="absolute bottom-0 right-[-20%]
            h-64 w-64 rounded-full
            bg-orange-600/10 blur-3xl
            sm:right-0 sm:h-96 sm:w-96
            lg:h-[32rem] lg:w-[32rem]">
        </div>

    </div>


    <div class="relative mx-auto max-w-7xl">

        {{-- =====================================================
            HEADER GALERI
        ====================================================== --}}
        <header
            class="reveal mb-10 text-center
            sm:mb-12 lg:mb-16">

            {{-- Badge --}}
            <span
                class="inline-flex items-center gap-2
                rounded-full border border-orange-500/20
                bg-orange-500/10
                px-3 py-1.5
                text-xs font-semibold text-orange-400
                sm:px-5 sm:py-2 sm:text-sm">

                <i class="fas fa-camera-retro text-xs sm:text-sm"></i>

                {{ __('frontend.gallery.pre-title') }}

            </span>


            {{-- Judul --}}
            <h2
                class="mt-4 text-3xl font-black
                leading-tight text-white
                sm:mt-6 sm:text-4xl
                md:text-5xl lg:text-6xl">

                {{ __('frontend.gallery.white-title') }}

                <span class="text-orange-500">
                    {{ __('frontend.gallery.orange-title') }}
                </span>

            </h2>


            {{-- Deskripsi --}}
            <p
                class="mx-auto mt-4 max-w-3xl
                text-sm leading-relaxed text-gray-400
                sm:mt-6 sm:text-base
                lg:text-lg">

                {{ __('frontend.gallery.description') }}

            </p>


            {{-- =================================================
                TOMBOL LIHAT SEMUA GALERI
                mt-8 dan sm:mt-10 membuat tombol lebih ke bawah
            ================================================== --}}
            @if (!request()->routeIs('frontend.gallery'))

                <div class="mt-8 sm:mt-10">

                    <a
                        href="{{ route('frontend.gallery') }}"
                        class="group inline-flex items-center
                        justify-center gap-2
                        rounded-full
                        border border-orange-400/20
                        bg-gradient-to-r
                        from-orange-500 to-orange-600
                        px-6 py-3
                        text-sm font-semibold text-white
                        shadow-[0_12px_35px_rgba(249,115,22,0.22)]
                        transition duration-300
                        hover:-translate-y-1
                        hover:from-orange-400
                        hover:to-amber-500
                        hover:shadow-[0_16px_45px_rgba(249,115,22,0.38)]
                        focus:outline-none
                        focus:ring-2
                        focus:ring-orange-500
                        focus:ring-offset-2
                        focus:ring-offset-black
                        sm:px-8 sm:py-3.5
                        sm:text-base">

                        <i class="fas fa-images"></i>

                        <span>
                            {{ __('frontend.gallery.gallery_button') }}
                        </span>

                        <i
                            class="fas fa-arrow-right
                            ml-1 text-[10px]
                            transition-transform duration-300
                            group-hover:translate-x-1">
                        </i>

                    </a>

                </div>

            @endif

        </header>


        {{-- =====================================================
            GALLERY GRID
        ====================================================== --}}
        <div
            class="grid grid-cols-2 gap-3
            sm:gap-5
            md:grid-cols-3
            lg:gap-6
            xl:grid-cols-4">

            @forelse ($galeris as $gallery)

                <article
                    class="group reveal relative
                    h-44 overflow-hidden
                    rounded-2xl border border-white/10
                    shadow-lg
                    transition duration-500
                    hover:-translate-y-1
                    hover:border-orange-500/30
                    hover:shadow-orange-500/20
                    sm:h-64 sm:shadow-xl
                    lg:h-80 lg:rounded-[2rem]">

                    {{-- Gambar --}}
                    <img
                        src="{{ $gallery->image &&
                            \Illuminate\Support\Facades\Storage::disk('public')->exists($gallery->image)
                                ? \Illuminate\Support\Facades\Storage::url($gallery->image)
                                : asset('images/gallery/gallery1.jpg') }}"
                        alt="{{ $gallery->title ?: 'Galeri Sate Simpang Tiga' }}"
                        loading="lazy"
                        decoding="async"
                        class="h-full w-full object-cover
                        transition duration-700
                        group-hover:scale-110">


                    {{-- Overlay gradient --}}
                    <div
                        class="absolute inset-0
                        bg-gradient-to-t
                        from-black/90 via-black/20 to-transparent">
                    </div>


                    {{-- Efek hover --}}
                    <div
                        class="absolute inset-0
                        bg-orange-500/0
                        transition duration-500
                        group-hover:bg-orange-500/10">
                    </div>


                    {{-- Informasi galeri --}}
                    <div
                        class="absolute bottom-0 left-0
                        p-3 sm:p-5 lg:p-6">

                        <h3
                            class="line-clamp-1
                            text-sm font-bold text-white
                            sm:text-lg lg:text-xl">

                            {{ $gallery->title ?: 'Sate Simpang Tiga' }}

                        </h3>

                        <p
                            class="mt-1 line-clamp-2
                            text-[11px] text-gray-300
                            sm:text-sm">

                            {{ $gallery->description ?? 'Momen hangat bersama pelanggan' }}

                        </p>

                    </div>


                    {{-- Ikon lihat gambar --}}
                    <div
                        class="absolute right-3 top-3
                        flex h-8 w-8 items-center
                        justify-center rounded-full
                        border border-white/10
                        bg-black/40
                        opacity-0 backdrop-blur-md
                        transition duration-500
                        group-hover:opacity-100
                        sm:right-5 sm:top-5
                        sm:h-10 sm:w-10">

                        <i class="fas fa-expand text-xs text-white sm:text-sm"></i>

                    </div>

                </article>

            @empty

                {{-- Empty state galeri --}}
                <div
                    class="col-span-full py-14 text-center
                    sm:py-20">

                    <div
                        class="mb-4 inline-flex
                        h-16 w-16 items-center
                        justify-center rounded-full
                        bg-gray-800
                        sm:mb-6 sm:h-20 sm:w-20">

                        <i
                            class="fas fa-image
                            text-2xl text-gray-600
                            sm:text-3xl">
                        </i>

                    </div>

                    <h3
                        class="mb-1 text-xl font-bold
                        text-gray-400
                        sm:text-2xl">

                        Belum Ada Galeri

                    </h3>

                    <p class="text-sm text-gray-500 sm:text-base">
                        Galeri akan segera tersedia
                    </p>

                </div>

            @endforelse

        </div>


        {{-- =====================================================
            VIDEO SECTION
        ====================================================== --}}
        <div class="mt-14 sm:mt-20 lg:mt-28">

            <div
                class="reveal grid grid-cols-1 gap-5
                sm:gap-8 lg:grid-cols-2">

                @forelse ($videos as $video)

                    <article
                        class="overflow-hidden rounded-2xl
                        border border-white/10
                        shadow-xl shadow-orange-500/10
                        lg:rounded-[2rem]
                        lg:shadow-2xl">

                        {{-- Video dari storage --}}
                        @if ($video->video_file)

                            <video
                                autoplay
                                muted
                                loop
                                controls
                                playsinline
                                class="h-52 w-full object-cover
                                sm:h-72 lg:h-[350px]">

                                <source
                                    src="{{ asset('storage/' . $video->video_file) }}"
                                    type="video/mp4">

                                Browser Anda tidak mendukung video.

                            </video>

                        {{-- Video dari URL --}}
                        @elseif ($video->video_url && $video->video_url !== '-')

                            @php
                                $isYoutube =
                                    str_contains($video->video_url, 'youtube.com') ||
                                    str_contains($video->video_url, 'youtu.be');

                                $isVimeo =
                                    str_contains($video->video_url, 'vimeo.com');
                            @endphp


                            {{-- YouTube --}}
                            @if ($isYoutube)

                                @php
                                    parse_str(
                                        parse_url($video->video_url, PHP_URL_QUERY),
                                        $ytParams
                                    );

                                    $ytId = $ytParams['v'] ?? '';

                                    if (str_contains($video->video_url, 'youtu.be')) {
                                        $ytId = ltrim(
                                            parse_url($video->video_url, PHP_URL_PATH),
                                            '/'
                                        );
                                    }
                                @endphp

                                <div
                                    class="relative h-52 w-full
                                    sm:h-72 lg:h-[350px]">

                                    <iframe
                                        src="https://www.youtube.com/embed/{{ $ytId }}?autoplay=1&mute=1&loop=1&playlist={{ $ytId }}"
                                        title="{{ $video->title ?: 'Video Sate Simpang Tiga' }}"
                                        class="h-full w-full"
                                        allow="autoplay; encrypted-media"
                                        allowfullscreen
                                        loading="lazy">
                                    </iframe>

                                </div>


                            {{-- Vimeo --}}
                            @elseif ($isVimeo)

                                @php
                                    $vimeoId = trim(
                                        parse_url($video->video_url, PHP_URL_PATH),
                                        '/'
                                    );
                                @endphp

                                <div
                                    class="relative h-52 w-full
                                    sm:h-72 lg:h-[350px]">

                                    <iframe
                                        src="https://player.vimeo.com/video/{{ $vimeoId }}?autoplay=1&muted=1&loop=1"
                                        title="{{ $video->title ?: 'Video Sate Simpang Tiga' }}"
                                        class="h-full w-full"
                                        allow="autoplay"
                                        allowfullscreen
                                        loading="lazy">
                                    </iframe>

                                </div>


                            {{-- Video URL biasa --}}
                            @else

                                <video
                                    autoplay
                                    muted
                                    loop
                                    controls
                                    playsinline
                                    class="h-52 w-full object-cover
                                    sm:h-72 lg:h-[350px]">

                                    <source
                                        src="{{ $video->video_url }}"
                                        type="video/mp4">

                                    Browser Anda tidak mendukung video.

                                </video>

                            @endif

                        @endif


                        {{-- Informasi video --}}
                        @if ($video->title || $video->description)

                            <div
                                class="relative z-10
                                -mt-16 bg-gradient-to-t
                                from-black/90 via-black/70 to-transparent
                                p-4 pt-12
                                sm:-mt-20 sm:p-5 sm:pt-16">

                                @if ($video->title)

                                    <h4
                                        class="text-base font-bold
                                        text-white sm:text-lg">

                                        {{ $video->title }}

                                    </h4>

                                @endif

                                @if ($video->description)

                                    <p
                                        class="mt-1 text-xs
                                        leading-relaxed text-gray-300
                                        sm:text-sm">

                                        {{ $video->description }}

                                    </p>

                                @endif

                            </div>

                        @endif

                    </article>

                @empty

                    {{-- Empty state video --}}
                    <div
                        class="col-span-full py-14 text-center
                        sm:py-16">

                        <div
                            class="mb-4 inline-flex
                            h-16 w-16 items-center
                            justify-center rounded-full
                            bg-gray-800
                            sm:mb-6 sm:h-20 sm:w-20">

                            <i
                                class="fas fa-video
                                text-2xl text-gray-600
                                sm:text-3xl">
                            </i>

                        </div>

                        <h3
                            class="mb-1 text-xl font-bold
                            text-gray-400
                            sm:text-2xl">

                            Belum Ada Video

                        </h3>

                        <p class="text-sm text-gray-500 sm:text-base">
                            Video akan segera tersedia
                        </p>

                    </div>

                @endforelse

            </div>

        </div>

    </div>

</section>
