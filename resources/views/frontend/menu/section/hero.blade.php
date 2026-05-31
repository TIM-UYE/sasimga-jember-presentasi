<!-- HERO / HEADER -->
<section
    class="relative bg-gradient-to-br from-black via-gray-900 to-black text-white
    pt-28 sm:pt-32 lg:pt-40
    pb-14 sm:pb-18 lg:pb-24
    px-4 sm:px-6
    overflow-hidden">

    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-10 pointer-events-none">

        <div
            class="absolute top-20 left-[-15%] sm:left-10
            w-40 h-40 sm:w-56 sm:h-56 lg:w-64 lg:h-64
            bg-orange-500 rounded-full blur-3xl">
        </div>

        <div
            class="absolute bottom-16 right-[-20%] sm:right-10
            w-56 h-56 sm:w-80 sm:h-80 lg:w-96 lg:h-96
            bg-orange-600 rounded-full blur-3xl">
        </div>

    </div>

    <div class="max-w-7xl mx-auto relative z-10 text-center">

        <span
            class="inline-flex items-center px-3 sm:px-4 py-1.5 sm:py-2
            bg-orange-500/20 border border-orange-500/30 rounded-full
            text-orange-400 text-xs sm:text-sm font-semibold
            mb-4 sm:mb-6">

            <i class="fas fa-fire mr-1.5 sm:mr-2"></i>
            Hot Menu

        </span>

        <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold mb-4 sm:mb-6 leading-tight">

            Semua

            <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-orange-600">

                Menu

            </span>

        </h1>

        <p class="text-sm sm:text-base md:text-xl text-gray-400 max-w-2xl mx-auto mb-6 sm:mb-8 leading-relaxed">

            Nikmati berbagai pilihan menu terbaik dari Simpang Tiga,
            dibuat dengan bahan berkualitas dan penuh cinta

        </p>

        <div class="flex flex-wrap justify-center gap-2.5 sm:gap-4">

            {{-- HOME --}}
            <a href="{{ route('frontend.home') }}"
                class="inline-flex items-center justify-center
                border-2 border-gray-600 hover:border-orange-500
                text-white px-4 sm:px-8 py-2.5 sm:py-3
                rounded-full text-xs sm:text-base font-semibold transition-all">

                <i class="fas fa-home mr-1.5 sm:mr-2"></i>
                Home

            </a>

            {{-- MENU REGULER --}}
            <button onclick="showMenuSection('regular')" id="regularBtn"
                class="menu-switch-btn inline-flex items-center justify-center
                bg-orange-500 hover:bg-orange-600 text-white
                px-4 sm:px-8 py-2.5 sm:py-3
                rounded-full text-xs sm:text-base font-semibold
                transition-all hover:scale-105">

                <i class="fas fa-utensils mr-1.5 sm:mr-2"></i>
                Menu Reguler

            </button>

            {{-- MENU SPESIAL --}}
            <button onclick="showMenuSection('special')" id="specialBtn"
                class="menu-switch-btn inline-flex items-center justify-center
                border-2 border-gray-600 hover:border-orange-500
                text-white px-4 sm:px-8 py-2.5 sm:py-3
                rounded-full text-xs sm:text-base font-semibold transition-all">

                <i class="fas fa-star mr-1.5 sm:mr-2"></i>
                Menu Spesial

            </button>

        </div>

    </div>

</section>
