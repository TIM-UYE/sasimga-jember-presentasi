@extends('frontend.layout.app')

@section('content')
    <!-- HERO -->
    <section class="relative overflow-hidden bg-black text-white pt-40 pb-28 px-6 border-b border-white/5">

        {{-- BACKGROUND GLOW --}}
        <div class="absolute inset-0 overflow-hidden">

            <div class="absolute top-0 left-0 w-[500px] h-[500px] bg-orange-500/20 rounded-full blur-3xl">
            </div>

            <div class="absolute bottom-0 right-0 w-[600px] h-[600px] bg-amber-500/10 rounded-full blur-3xl">
            </div>

        </div>

        <div class="max-w-7xl mx-auto relative z-10 text-center">

            {{-- BADGE --}}
            <span
                class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-orange-500/10 text-orange-400 text-xs font-medium tracking-[0.2em] uppercase ring-1 ring-orange-500/20 mb-6 reveal">

                <span class="w-1.5 h-1.5 rounded-full bg-orange-400 animate-pulse"></span>

                About Us

            </span>

            {{-- TITLE --}}
            <h1 class="text-4xl md:text-6xl font-bold tracking-tight leading-tight reveal delay-100">

                <span class="text-white">
                    Tentang
                </span>

                <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-400 via-orange-500 to-amber-500">

                    Sate Simpang Tiga

                </span>

            </h1>

            {{-- DESC --}}
            <p class="text-zinc-400 mt-6 text-base md:text-lg leading-relaxed max-w-2xl mx-auto reveal delay-200">

                Mengenal lebih dekat perjalanan dan cita rasa autentik
                yang telah menjadi bagian dari pengalaman kuliner keluarga Indonesia.

            </p>

            {{-- BREADCRUMB --}}
            <div class="mt-8 flex justify-center items-center gap-3 text-sm text-zinc-500 reveal delay-300">

                <a href="{{ route('frontend.home') }}" class="hover:text-orange-400 transition">

                    Home

                </a>

                <span>/</span>

                <span class="text-white">
                    About
                </span>

            </div>

        </div>

    </section>

    <!-- CONTENT -->
    <section class="relative bg-black text-white py-24 px-6 overflow-hidden">

        {{-- BG GLOW --}}
        <div class="absolute inset-0 overflow-hidden">

            <div class="absolute top-20 right-0 w-[400px] h-[400px] bg-orange-500/10 rounded-full blur-3xl">
            </div>

        </div>

        <div class="max-w-7xl mx-auto grid lg:grid-cols-2 gap-16 items-center relative z-10">

            <!-- IMAGE -->
            <div class="relative reveal-left group">

                {{-- IMAGE --}}
                <img src="{{ asset('images/about/depan.jpg') }}"
                    class="rounded-[32px] shadow-2xl shadow-orange-500/10 w-full h-[550px] object-cover">

                {{-- OVERLAY --}}
                <div class="absolute inset-0 rounded-[32px] bg-gradient-to-t from-black/60 via-transparent to-transparent">
                </div>

                {{-- FLOATING CARD --}}
                <div
                    class="absolute bottom-6 left-6 bg-black/60 backdrop-blur-xl border border-white/10 rounded-2xl px-5 py-4">

                    <p class="text-white text-lg font-semibold">
                        Sejak 1975
                    </p>

                    <p class="text-zinc-400 text-sm">
                        Menjaga cita rasa autentik
                    </p>

                </div>

            </div>

            <!-- CONTENT -->
            <div class="reveal-right delay-200">

                {{-- SMALL LABEL --}}
                <span
                    class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-orange-500/10 text-orange-400 text-xs font-medium tracking-[0.2em] uppercase ring-1 ring-orange-500/20 mb-6">

                    <span class="w-1.5 h-1.5 rounded-full bg-orange-400 animate-pulse"></span>

                    Our Story

                </span>

                {{-- TITLE --}}
                <h2 class="text-4xl md:text-5xl lg:text-6xl font-bold leading-tight tracking-tight">

                    <span class="text-white">
                        Cita Rasa Autentik
                    </span>

                    <br>

                    <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-orange-400 via-orange-500 to-amber-500">

                        Sate Simpang Tiga

                    </span>

                </h2>

                {{-- DESC --}}
                <p class="text-zinc-400 mt-6 text-base leading-relaxed max-w-xl">

                    Berdiri sejak tahun 1975, Sate Simpang Tiga menghadirkan
                    resep turun-temurun dengan bahan berkualitas dan cita rasa
                    autentik yang tetap terjaga dari generasi ke generasi.

                </p>

                <p class="text-zinc-500 mt-5 text-base leading-relaxed max-w-xl">

                    Kami percaya bahwa makanan bukan hanya tentang rasa,
                    tetapi juga tentang pengalaman, kehangatan,
                    dan kenangan yang tercipta di setiap hidangan.

                </p>

                {{-- FEATURE --}}
                <div class="grid sm:grid-cols-2 gap-5 mt-10">

                    {{-- CARD --}}
                    <div
                        class="bg-zinc-900/70 border border-white/5 rounded-2xl p-5 hover:border-orange-500/30 transition-all">

                        <div class="w-12 h-12 rounded-xl bg-orange-500/10 flex items-center justify-center mb-4">

                            <i class="fas fa-fire text-orange-400"></i>

                        </div>

                        <h3 class="text-white font-medium mb-2">
                            Dibakar Arang
                        </h3>

                        <p class="text-zinc-400 text-sm">
                            Menghasilkan aroma khas yang menggugah selera.
                        </p>

                    </div>

                    {{-- CARD --}}
                    <div
                        class="bg-zinc-900/70 border border-white/5 rounded-2xl p-5 hover:border-orange-500/30 transition-all">

                        <div class="w-12 h-12 rounded-xl bg-orange-500/10 flex items-center justify-center mb-4">

                            <i class="fas fa-utensils text-orange-400"></i>

                        </div>

                        <h3 class="text-white font-medium mb-2">
                            Bahan Premium
                        </h3>

                        <p class="text-zinc-400 text-sm">
                            Menggunakan bahan segar dan bumbu pilihan terbaik.
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </section>
@endsection
