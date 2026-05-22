<section id="filterSection"
class="sticky top-16 lg:top-18 bg-black/50 backdrop-blur-sm border-y border-gray-800 px-6 py-6 z-30"
style="transition: box-shadow 0.3s ease;">

    <div class="max-w-7xl mx-auto">

        <div class="flex flex-col lg:flex-row gap-4 items-center justify-between">

            <!-- Search -->
            <div class="relative w-full lg:w-96">

                <input type="text"
                    id="searchInput"
                    placeholder="Cari menu..."
                    class="w-full bg-gray-800 border border-gray-700 rounded-full px-5 py-3 pl-12 text-white focus:outline-none focus:border-orange-500 transition-all">

                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-500"></i>

            </div>

            <!-- Category Filters -->
            <div class="flex flex-wrap gap-2 justify-center">

                <button onclick="filterMenuByCategory(0)"
                    class="px-5 py-2.5 rounded-full text-sm font-semibold kategori-btn text-white bg-orange-500 hover:bg-orange-600 transition-all shadow-lg shadow-orange-500/25"
                    data-kategori-id="0">

                    <i class="fas fa-th mr-2"></i>Semua

                </button>

                @foreach ($kategoris as $kat)

                    <button onclick="filterMenuByCategory({{ $kat->id }})"
                        class="px-5 py-2.5 rounded-full text-sm font-semibold kategori-btn bg-gray-800 text-gray-300 hover:bg-orange-500 hover:text-white transition-all"
                        data-kategori-id="{{ $kat->id }}">

                        {{ $kat->nama_kategori }}

                        <span class="ml-2 text-xs opacity-70">

                            ({{ $kat->menus_count }})

                        </span>

                    </button>

                @endforeach

            </div>

        </div>

    </div>

</section>
