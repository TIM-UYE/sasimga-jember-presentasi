<section class="relative bg-black py-14 sm:py-20 lg:py-28 px-4 sm:px-6 overflow-hidden">

    {{-- BACKGROUND --}}
    <div class="absolute inset-0 pointer-events-none">

        <div
            class="absolute top-0 left-[-15%] sm:left-0
            w-56 h-56 sm:w-80 sm:h-80 lg:w-96 lg:h-96
            bg-orange-500/10 blur-3xl rounded-full">
        </div>

        <div
            class="absolute bottom-0 right-[-20%] sm:right-0
            w-64 h-64 sm:w-96 sm:h-96 lg:w-[32rem] lg:h-[32rem]
            bg-orange-600/10 blur-3xl rounded-full">
        </div>

    </div>


    <div class="relative max-w-7xl mx-auto">

        {{-- HEADER --}}
        <div class="text-center mb-10 sm:mb-12 lg:mb-16 reveal">

            <span
                class="inline-flex items-center gap-2 bg-orange-500/10 text-orange-400 px-3 sm:px-5 py-1.5 sm:py-2 rounded-full text-xs sm:text-sm font-semibold border border-orange-500/20">

                <i class="fas fa-camera-retro text-xs sm:text-sm"></i>

                {{ __('frontend.gallery.pre-title') }}

            </span>


            <h2 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-black text-white mt-4 sm:mt-6 leading-tight">

                {{ __('frontend.gallery.white-title') }}

                <span class="text-orange-500">
                    {{ __('frontend.gallery.orange-title') }}
                </span>

            </h2>


            <p class="text-gray-400 mt-4 sm:mt-6 max-w-3xl mx-auto leading-relaxed text-sm sm:text-base lg:text-lg">

                {{ __('frontend.gallery.description') }}

            </p>

        </div>



        {{-- GALLERY --}}
        <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-3 sm:gap-5 lg:gap-6">

            @forelse($galeris as $gallery)
                <div
                    class="group relative overflow-hidden rounded-2xl lg:rounded-[2rem]
                    h-44 sm:h-64 lg:h-80
                    border border-white/10 shadow-lg sm:shadow-xl
                    hover:shadow-orange-500/20 transition duration-500 reveal">

                    {{-- IMAGE --}}
                    <img src="{{ asset('storage/' . $gallery->image) }}" alt="{{ $gallery->title }}" loading="lazy"
                        decoding="async"
                        class="w-full h-full object-cover transition duration-700 group-hover:scale-110">


                    {{-- OVERLAY --}}
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent"></div>


                    {{-- HOVER EFFECT --}}
                    <div class="absolute inset-0 bg-orange-500/0 group-hover:bg-orange-500/10 transition duration-500">
                    </div>


                    {{-- CONTENT --}}
                    <div class="absolute bottom-0 left-0 p-3 sm:p-5 lg:p-6">

                        <h3 class="text-white font-bold text-sm sm:text-lg lg:text-xl line-clamp-1">
                            {{ $gallery->title }}
                        </h3>

                        <p class="text-gray-300 text-[11px] sm:text-sm mt-1 line-clamp-2">
                            {{ $gallery->description ?? 'Momen hangat bersama pelanggan' }}
                        </p>

                    </div>


                    {{-- ICON --}}
                    <div
                        class="absolute top-3 right-3 sm:top-5 sm:right-5
                        h-8 w-8 sm:h-10 sm:w-10
                        rounded-full bg-black/40 backdrop-blur-md
                        flex items-center justify-center border border-white/10
                        opacity-0 group-hover:opacity-100 transition duration-500">

                        <i class="fas fa-expand text-white text-xs sm:text-sm"></i>

                    </div>

                </div>

            @empty

                <div class="col-span-full text-center py-14 sm:py-20">

                    <div
                        class="inline-flex items-center justify-center w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-gray-800 mb-4 sm:mb-6">
                        <i class="fas fa-image text-2xl sm:text-3xl text-gray-600"></i>
                    </div>

                    <h3 class="text-xl sm:text-2xl font-bold text-gray-400 mb-1">
                        Belum Ada Galeri
                    </h3>

                    <p class="text-sm sm:text-base text-gray-500">
                        Galeri akan segera tersedia
                    </p>

                </div>
            @endforelse

        </div>



        {{-- VIDEO SECTION --}}
        <div class="mt-14 sm:mt-20 lg:mt-28">

            {{-- VIDEO GRID --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 sm:gap-8 reveal">

                @forelse($videos as $video)

                    <div
                        class="overflow-hidden rounded-2xl lg:rounded-[2rem] border border-white/10 shadow-xl lg:shadow-2xl shadow-orange-500/10">

                        @if ($video->video_file)
                            <video autoplay muted loop controls class="w-full h-52 sm:h-72 lg:h-[350px] object-cover">
                                <source src="{{ asset('storage/' . $video->video_file) }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        @elseif($video->video_url && $video->video_url !== '-')
                            @php
                                $isYoutube =
                                    str_contains($video->video_url, 'youtube.com') ||
                                    str_contains($video->video_url, 'youtu.be');
                                $isVimeo = str_contains($video->video_url, 'vimeo.com');
                            @endphp

                            @if ($isYoutube)
                                @php
                                    parse_str(parse_url($video->video_url, PHP_URL_QUERY), $ytParams);
                                    $ytId = $ytParams['v'] ?? '';
                                    if (str_contains($video->video_url, 'youtu.be')) {
                                        $ytId = substr(parse_url($video->video_url, PHP_URL_PATH), 1);
                                    }
                                @endphp

                                <div class="relative w-full h-52 sm:h-72 lg:h-[350px]">
                                    <iframe
                                        src="https://www.youtube.com/embed/{{ $ytId }}?autoplay=1&mute=1&loop=1&playlist={{ $ytId }}"
                                        class="w-full h-full object-cover" allow="autoplay; encrypted-media"
                                        allowfullscreen loading="lazy"></iframe>
                                </div>
                            @elseif($isVimeo)
                                @php
                                    $vimeoId = substr(parse_url($video->video_url, PHP_URL_PATH), 1);
                                @endphp

                                <div class="relative w-full h-52 sm:h-72 lg:h-[350px]">
                                    <iframe
                                        src="https://player.vimeo.com/video/{{ $vimeoId }}?autoplay=1&muted=1&loop=1"
                                        class="w-full h-full object-cover" allow="autoplay" allowfullscreen
                                        loading="lazy"></iframe>
                                </div>
                            @else
                                <video autoplay muted loop controls
                                    class="w-full h-52 sm:h-72 lg:h-[350px] object-cover">
                                    <source src="{{ $video->video_url }}" type="video/mp4">
                                </video>
                            @endif
                        @endif

                        @if ($video->title || $video->description)
                            <div
                                class="p-4 sm:p-5 bg-gradient-to-t from-black/80 to-transparent -mt-16 sm:-mt-20 relative z-10">

                                <h4 class="text-white font-bold text-base sm:text-lg">
                                    {{ $video->title }}
                                </h4>

                                @if ($video->description)
                                    <p class="text-gray-300 text-xs sm:text-sm mt-1">
                                        {{ $video->description }}
                                    </p>
                                @endif

                            </div>
                        @endif

                    </div>

                @empty

                    <div class="col-span-full text-center py-14 sm:py-16">

                        <div
                            class="inline-flex items-center justify-center w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-gray-800 mb-4 sm:mb-6">
                            <i class="fas fa-video text-2xl sm:text-3xl text-gray-600"></i>
                        </div>

                        <h3 class="text-xl sm:text-2xl font-bold text-gray-400 mb-1">
                            Belum Ada Video
                        </h3>

                        <p class="text-sm sm:text-base text-gray-500">
                            Video akan segera tersedia
                        </p>

                    </div>

                @endforelse

            </div>

        </div>

    </div>

</section>
