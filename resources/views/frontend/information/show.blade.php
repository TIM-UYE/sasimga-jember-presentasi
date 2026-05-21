@extends('frontend.layout.app')

@section('title', $information->title)

@section('content')

@php
    $data = json_decode($information->content, true);
@endphp

@if ($information->slug === 'about')
    {{-- ABOUT PAGE --}}
    <section class="relative overflow-hidden bg-black text-white pt-40 pb-28 px-6 border-b border-white/5">
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute top-0 left-0 w-[500px] h-[500px] bg-orange-500/20 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 right-0 w-[600px] h-[600px] bg-amber-500/10 rounded-full blur-3xl"></div>
        </div>

        <div class="max-w-7xl mx-auto relative z-10 text-center">
            <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-orange-500/10 text-orange-400 text-xs font-medium tracking-[0.2em] uppercase ring-1 ring-orange-500/20 mb-6 reveal">
                <span class="w-1.5 h-1.5 rounded-full bg-orange-400 animate-pulse"></span>
                {{ $data['hero_badge'] ?? '' }}
            </span>

            <h1 class="text-4xl md:text-6xl font-bold tracking-tight leading-tight reveal delay-100">
                <span class="text-white">{{ $data['hero_title'] ?? '' }}</span>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-400 via-orange-500 to-amber-500">
                    {{ $data['hero_title_highlight'] ?? '' }}
                </span>
            </h1>

            <p class="text-zinc-400 mt-6 text-base md:text-lg leading-relaxed max-w-2xl mx-auto reveal delay-200">
                {{ $data['hero_description'] ?? '' }}
            </p>

            <div class="mt-8 flex justify-center items-center gap-3 text-sm text-zinc-500 reveal delay-300">
                <a href="{{ route('frontend.home') }}" class="hover:text-orange-400 transition">Home</a>
                <span>/</span>
                <span class="text-white">About</span>
            </div>
        </div>
    </section>

    <section class="relative bg-black text-white py-24 px-6 overflow-hidden">
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute top-20 right-0 w-[400px] h-[400px] bg-orange-500/10 rounded-full blur-3xl"></div>
        </div>

        <div class="max-w-7xl mx-auto grid lg:grid-cols-2 gap-16 items-center relative z-10">
            @php
                $imgPath = $data['image'] ?? 'images/about/depan.jpg';
                $imgUrl = str_starts_with($imgPath, 'information/') ? asset('storage/' . $imgPath) : asset($imgPath);
            @endphp
            <div class="relative reveal-left group">
                <img src="{{ $imgUrl }}"
                     class="rounded-[32px] shadow-2xl shadow-orange-500/10 w-full h-[550px] object-cover">
                <div class="absolute inset-0 rounded-[32px] bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                <div class="absolute bottom-6 left-6 bg-black/60 backdrop-blur-xl border border-white/10 rounded-2xl px-5 py-4">
                    <p class="text-white text-lg font-semibold">{{ $data['since'] ?? '' }}</p>
                    <p class="text-zinc-400 text-sm">{{ $data['since_tagline'] ?? '' }}</p>
                </div>
            </div>

            <div class="reveal-right delay-200">
                <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-orange-500/10 text-orange-400 text-xs font-medium tracking-[0.2em] uppercase ring-1 ring-orange-500/20 mb-6">
                    <span class="w-1.5 h-1.5 rounded-full bg-orange-400 animate-pulse"></span>
                    {{ $data['section_badge'] ?? '' }}
                </span>

                <h2 class="text-4xl md:text-5xl lg:text-6xl font-bold leading-tight tracking-tight">
                    <span class="text-white">{{ $data['section_title'] ?? '' }}</span>
                    <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-400 via-orange-500 to-amber-500">
                        {{ $data['section_title_highlight'] ?? '' }}
                    </span>
                </h2>

                <p class="text-zinc-400 mt-6 text-base leading-relaxed max-w-xl">
                    {{ $data['section_description1'] ?? '' }}
                </p>

                @if (!empty($data['section_description2']))
                <p class="text-zinc-500 mt-5 text-base leading-relaxed max-w-xl">
                    {{ $data['section_description2'] }}
                </p>
                @endif

                @if (!empty($data['features']))
                <div class="grid sm:grid-cols-2 gap-5 mt-10">
                    @foreach ($data['features'] as $feature)
                    <div class="bg-zinc-900/70 border border-white/5 rounded-2xl p-5 hover:border-orange-500/30 transition-all">
                        <div class="w-12 h-12 rounded-xl bg-orange-500/10 flex items-center justify-center mb-4">
                            <i class="fas {{ $feature['icon'] }} text-orange-400"></i>
                        </div>
                        <h3 class="text-white font-medium mb-2">{{ $feature['title'] }}</h3>
                        <p class="text-zinc-400 text-sm">{{ $feature['description'] }}</p>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </section>
@elseif ($information->slug === 'faq')
    {{-- FAQ PAGE --}}
    <section class="relative bg-black text-white min-h-screen py-28 px-6 overflow-hidden">
        <div class="absolute inset-0">
            <div class="absolute top-0 left-0 w-[30rem] h-[30rem] bg-orange-500/10 blur-3xl rounded-full"></div>
            <div class="absolute bottom-0 right-0 w-[35rem] h-[35rem] bg-orange-600/10 blur-3xl rounded-full"></div>
        </div>

        <div class="relative max-w-5xl mx-auto">
            <div class="text-center mb-16">
                <span class="inline-flex items-center gap-2 bg-orange-500/10 border border-orange-500/20 text-orange-400 px-5 py-2 rounded-full text-sm font-semibold">
                    <i class="fas fa-circle-question"></i>
                    {{ $data['subtitle'] ?? '' }}
                </span>

                <h1 class="text-5xl md:text-6xl font-black mt-6 leading-tight">
                    FAQ
                    <span class="text-orange-500">Sate Simpangtiga</span>
                </h1>

                <p class="text-gray-400 mt-6 max-w-2xl mx-auto text-lg leading-relaxed">
                    {{ $data['description'] ?? '' }}
                </p>
            </div>

            <div class="space-y-6">
                @foreach ($data['items'] ?? [] as $item)
                <div class="group bg-zinc-900/80 backdrop-blur-xl rounded-[2rem] p-7 border border-white/10 hover:border-orange-500/30 transition duration-300">
                    <div class="flex items-start gap-4">
                        <div class="h-12 w-12 rounded-2xl bg-orange-500/10 flex items-center justify-center flex-shrink-0">
                            <i class="fas {{ $item['icon'] }} text-orange-400"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold mb-3 text-white">{{ $item['question'] }}</h2>
                            <p class="text-gray-400 leading-relaxed">{{ $item['answer'] }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            @if (!empty($data['cta_text']))
            <div class="mt-16 bg-gradient-to-r from-orange-500 to-orange-600 rounded-[2rem] p-10 text-center shadow-2xl shadow-orange-500/20">
                <h3 class="text-3xl font-black text-white mb-4">{{ $data['cta_text'] }}</h3>
                <p class="text-white/80 max-w-2xl mx-auto leading-relaxed mb-8">{{ $data['cta_description'] ?? '' }}</p>
                <a href="{{ route($data['cta_route'] ?? 'frontend.reservasi') }}"
                   class="inline-flex items-center gap-3 bg-white text-orange-600 px-8 py-4 rounded-full font-bold hover:scale-105 transition duration-300 shadow-xl">
                    <i class="fas fa-calendar-check"></i>
                    {{ $data['cta_button'] ?? '' }}
                </a>
            </div>
            @endif
        </div>
    </section>
@elseif ($information->slug === 'privacy-policy')
    {{-- PRIVACY POLICY PAGE --}}
    <section class="relative bg-black text-white min-h-screen py-28 px-6 overflow-hidden">
        <div class="absolute inset-0">
            <div class="absolute top-0 left-0 w-[30rem] h-[30rem] bg-orange-500/10 blur-3xl rounded-full"></div>
            <div class="absolute bottom-0 right-0 w-[35rem] h-[35rem] bg-orange-600/10 blur-3xl rounded-full"></div>
        </div>

        <div class="relative max-w-5xl mx-auto">
            <div class="text-center mb-16">
                <span class="inline-flex items-center gap-2 bg-orange-500/10 border border-orange-500/20 text-orange-400 px-5 py-2 rounded-full text-sm font-semibold">
                    <i class="fas fa-shield-halved"></i>
                    {{ $data['subtitle'] ?? '' }}
                </span>

                <h1 class="text-5xl md:text-6xl font-black mt-6 leading-tight">
                    Privacy
                    <span class="text-orange-500">Policy</span>
                </h1>

                <p class="text-gray-400 mt-6 max-w-3xl mx-auto text-lg leading-relaxed">
                    {{ $data['description'] ?? '' }}
                </p>
            </div>

            <div class="space-y-8">
                @foreach ($data['items'] ?? [] as $item)
                <div class="group bg-zinc-900/80 backdrop-blur-xl rounded-[2rem] p-8 border border-white/10 hover:border-orange-500/30 transition duration-300">
                    <div class="flex items-start gap-5">
                        <div class="h-14 w-14 rounded-2xl bg-orange-500/10 flex items-center justify-center flex-shrink-0">
                            <i class="fas {{ $item['icon'] }} text-orange-400 text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-white mb-4">{{ $item['title'] }}</h2>
                            <p class="text-gray-400 leading-relaxed text-lg">{{ $item['description'] }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            @if (!empty($data['cta_text']))
            <div class="mt-16 bg-gradient-to-r from-orange-500 to-orange-600 rounded-[2rem] p-10 text-center shadow-2xl shadow-orange-500/20">
                <h3 class="text-3xl font-black text-white mb-4">{{ $data['cta_text'] }}</h3>
                <p class="text-white/80 max-w-3xl mx-auto leading-relaxed mb-8 text-lg">{{ $data['cta_description'] ?? '' }}</p>
                <a href="{{ route($data['cta_route'] ?? 'frontend.reservasi') }}"
                   class="inline-flex items-center gap-3 bg-white text-orange-600 px-8 py-4 rounded-full font-bold hover:scale-105 transition duration-300 shadow-xl">
                    <i class="fas fa-calendar-check"></i>
                    {{ $data['cta_button'] ?? '' }}
                </a>
            </div>
            @endif
        </div>
    </section>
@elseif ($information->slug === 'terms-conditions')
    {{-- TERMS & CONDITIONS PAGE --}}
    <section class="relative bg-black text-white min-h-screen py-28 px-6 overflow-hidden">
        <div class="absolute inset-0">
            <div class="absolute top-0 left-0 w-[28rem] h-[28rem] bg-orange-500/10 blur-3xl rounded-full"></div>
            <div class="absolute bottom-0 right-0 w-[35rem] h-[35rem] bg-orange-600/10 blur-3xl rounded-full"></div>
        </div>

        <div class="relative max-w-5xl mx-auto">
            <div class="text-center mb-16">
                <span class="inline-flex items-center gap-2 bg-orange-500/10 border border-orange-500/20 text-orange-400 px-5 py-2 rounded-full text-sm font-semibold">
                    <i class="fas fa-file-signature"></i>
                    {{ $data['subtitle'] ?? '' }}
                </span>

                <h1 class="text-5xl md:text-6xl font-black mt-6 leading-tight">
                    Terms &
                    <span class="text-orange-500">Conditions</span>
                </h1>

                <p class="text-gray-400 mt-6 max-w-3xl mx-auto text-lg leading-relaxed">
                    {{ $data['description'] ?? '' }}
                </p>
            </div>

            <div class="space-y-8">
                @foreach ($data['items'] ?? [] as $item)
                <div class="group bg-zinc-900/80 backdrop-blur-xl rounded-[2rem] p-8 border border-white/10 hover:border-orange-500/30 transition duration-300">
                    <div class="flex items-start gap-5">
                        <div class="h-14 w-14 rounded-2xl bg-orange-500/10 flex items-center justify-center flex-shrink-0">
                            <i class="fas {{ $item['icon'] }} text-orange-400 text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-white mb-4">{{ $item['title'] }}</h2>
                            <p class="text-gray-400 leading-relaxed text-lg">{{ $item['description'] }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            @if (!empty($data['cta_text']))
            <div class="mt-16 bg-gradient-to-r from-orange-500 to-orange-600 rounded-[2rem] p-10 text-center shadow-2xl shadow-orange-500/20">
                <h3 class="text-3xl font-black text-white mb-4">{{ $data['cta_text'] }}</h3>
                <p class="text-white/80 max-w-3xl mx-auto leading-relaxed mb-8 text-lg">{{ $data['cta_description'] ?? '' }}</p>
                <a href="{{ route($data['cta_route'] ?? 'frontend.menu') }}"
                   class="inline-flex items-center gap-3 bg-white text-orange-600 px-8 py-4 rounded-full font-bold hover:scale-105 transition duration-300 shadow-xl">
                    <i class="fas fa-utensils"></i>
                    {{ $data['cta_button'] ?? '' }}
                </a>
            </div>
            @endif
        </div>
    </section>
@endif

@endsection
