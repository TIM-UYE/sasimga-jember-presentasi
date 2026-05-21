<!-- HERO / HEADER -->
<section class="relative bg-gradient-to-br from-black via-gray-900 to-black text-white pt-40 pb-24 px-6 overflow-hidden">

    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-10">

        <div class="absolute top-20 left-10 w-64 h-64 bg-orange-500 rounded-full blur-3xl"></div>

        <div class="absolute bottom-20 right-10 w-96 h-96 bg-orange-600 rounded-full blur-3xl"></div>

    </div>

    <div class="max-w-7xl mx-auto relative z-10 text-center">

        <span
            class="inline-block px-4 py-2 bg-orange-500/20 border border-orange-500/30 rounded-full text-orange-400 text-sm font-semibold mb-6">

            <i class="fas fa-fire mr-2"></i>Hot Menu

        </span>

        <h1 class="text-5xl md:text-6xl font-bold mb-6">

            Semua

            <span
                class="text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-orange-600">

                Menu

            </span>

        </h1>

        <p class="text-xl text-gray-400 max-w-2xl mx-auto mb-8">

            Nikmati berbagai pilihan menu terbaik dari Simpang Tiga,
            dibuat dengan bahan berkualitas dan penuh cinta

        </p>

        <div class="flex flex-wrap justify-center gap-4">

            {{-- HOME --}}
            <a href="{{ route('frontend.home') }}"
                class="border-2 border-gray-600 hover:border-orange-500 text-white px-8 py-3 rounded-full font-semibold transition-all">

                <i class="fas fa-home mr-2"></i>Home

            </a>

            {{-- MENU REGULER --}}
            <button onclick="showMenuSection('regular')" id="regularBtn"
                class="menu-switch-btn bg-orange-500 hover:bg-orange-600 text-white px-8 py-3 rounded-full font-semibold transition-all hover:scale-105">

                <i class="fas fa-utensils mr-2"></i>Menu Reguler

            </button>

            {{-- MENU SPESIAL --}}
            <button onclick="showMenuSection('special')" id="specialBtn"
                class="menu-switch-btn border-2 border-gray-600 hover:border-orange-500 text-white px-8 py-3 rounded-full font-semibold transition-all">

                <i class="fas fa-star mr-2"></i>Menu Spesial

            </button>

        </div>

    </div>

</section>