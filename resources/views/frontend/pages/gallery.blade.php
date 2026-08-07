@extends('frontend.layout.app')

@section('title', 'Galeri - Sate Simpang Tiga')

@section('content')
    <section class="relative overflow-hidden bg-black px-4 py-24 sm:px-6 lg:px-8 lg:py-32">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(249,115,22,0.18),transparent_35%),radial-gradient(circle_at_bottom_right,rgba(251,191,36,0.16),transparent_35%)]"></div>

        <div class="relative mx-auto max-w-7xl">
            <div class="mx-auto max-w-3xl text-center">
                <span class="inline-flex items-center gap-2 rounded-full border border-orange-500/20 bg-orange-500/10 px-4 py-2 text-sm font-semibold text-orange-400">
                    <i class="fas fa-images"></i>
                    Galeri Kami
                </span>
                <h1 class="mt-6 text-4xl font-black leading-tight text-white sm:text-5xl lg:text-6xl">
                    Kenangan <span class="text-orange-500">Pelanggan</span> dalam Setiap Sudut
                </h1>
                <p class="mt-5 text-base leading-relaxed text-gray-400 sm:text-lg">
                    Jelajahi momen hangat bersama keluarga, sahabat, dan para pecinta sate di Sate Simpang Tiga.
                </p>
            </div>

            <div class="mt-14 grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                @forelse($galeris as $gallery)
                    <div class="group overflow-hidden rounded-[1.75rem] border border-white/10 bg-white/5 shadow-2xl shadow-black/30">
                        <div class="aspect-[4/3] overflow-hidden">
                            <img src="{{ $gallery->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($gallery->image) ? \Illuminate\Support\Facades\Storage::url($gallery->image) : asset('images/gallery/gallery1.jpg') }}"
                                alt="{{ $gallery->title }}"
                                loading="lazy"
                                decoding="async"
                                class="h-full w-full object-cover transition duration-700 group-hover:scale-110">
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-white">{{ $gallery->title }}</h3>
                            <p class="mt-2 text-sm leading-relaxed text-gray-400">
                                {{ $gallery->description ?? 'Momen hangat bersama pelanggan di Sate Simpang Tiga.' }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="md:col-span-2 xl:col-span-3 rounded-[1.75rem] border border-dashed border-white/15 bg-white/5 p-14 text-center">
                        <i class="fas fa-image text-4xl text-gray-600"></i>
                        <h3 class="mt-4 text-2xl font-semibold text-gray-400">Belum ada galeri yang tersedia</h3>
                        <p class="mt-2 text-gray-500">Galeri akan segera ditampilkan di halaman ini.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    @if ($videos->isNotEmpty())
        <section class="bg-zinc-950 px-4 py-20 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-7xl">
                <div class="mx-auto max-w-2xl text-center">
                    <h2 class="text-3xl font-bold text-white sm:text-4xl">Video <span class="text-orange-500">Galeri</span></h2>
                    <p class="mt-3 text-gray-400">Sorotan visual suasana restoran dan aktivitas terbaik kami.</p>
                </div>
                <div class="mt-12 grid gap-8 lg:grid-cols-2">
                    @foreach ($videos as $video)
                        <div class="overflow-hidden rounded-[1.75rem] border border-white/10 bg-white/5 shadow-2xl shadow-black/30">
                            @if ($video->video_file)
                                <video autoplay muted loop controls class="h-72 w-full object-cover sm:h-80 lg:h-[360px]">
                                    <source src="{{ asset('storage/' . $video->video_file) }}" type="video/mp4">
                                </video>
                            @elseif($video->video_url && $video->video_url !== '-')
                                @php
                                    $isYoutube = str_contains($video->video_url, 'youtube.com') || str_contains($video->video_url, 'youtu.be');
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
                                    <div class="h-72 w-full sm:h-80 lg:h-[360px]">
                                        <iframe src="https://www.youtube.com/embed/{{ $ytId }}?autoplay=1&mute=1&loop=1&playlist={{ $ytId }}"
                                            class="h-full w-full" allow="autoplay; encrypted-media" allowfullscreen loading="lazy"></iframe>
                                    </div>
                                @elseif($isVimeo)
                                    @php
                                        $vimeoId = substr(parse_url($video->video_url, PHP_URL_PATH), 1);
                                    @endphp
                                    <div class="h-72 w-full sm:h-80 lg:h-[360px]">
                                        <iframe src="https://player.vimeo.com/video/{{ $vimeoId }}?autoplay=1&muted=1&loop=1"
                                            class="h-full w-full" allow="autoplay; encrypted-media" allowfullscreen loading="lazy"></iframe>
                                    </div>
                                @endif
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
