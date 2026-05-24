<section id="filterSection"
class="sticky top-16 bg-black/50 backdrop-blur-sm border-y border-gray-800 z-30"
style="transition: box-shadow 0.3s ease;">

    {{-- TOGGLE BUTTON (MOBILE ONLY) --}}
    <div class="md:hidden flex items-center justify-between px-4 py-2">
        <span class="text-sm font-semibold text-white/70">
            <i class="fas fa-filter mr-2 text-orange-400"></i>Filter Menu
        </span>
        <button type="button" id="filterToggleBtn"
            class="flex items-center gap-2 px-4 py-2 rounded-full bg-gray-800 text-white/70 text-sm hover:bg-gray-700 hover:text-white transition-all"
            aria-expanded="true">
            <span id="filterToggleLabel">Sembunyikan</span>
            <i id="filterToggleIcon" class="fa-solid fa-chevron-up text-xs transition-transform duration-300"></i>
        </button>
    </div>

    {{-- FILTER CONTENT (collapsible on mobile) --}}
    <div id="filterContent" class="px-6 py-3">

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

    </div>

</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleBtn = document.getElementById('filterToggleBtn');
        const filterContent = document.getElementById('filterContent');
        const toggleLabel = document.getElementById('filterToggleLabel');
        const toggleIcon = document.getElementById('filterToggleIcon');

        if (!toggleBtn || !filterContent) return;

        // Initial state: button always visible on mobile, hidden on desktop
        function checkMobile() {
            if (window.innerWidth < 768) {
                toggleBtn.style.display = 'flex';
            } else {
                toggleBtn.style.display = 'none';
                filterContent.style.display = '';
                // Reset icon rotation when going to desktop
                toggleIcon.classList.remove('rotate-180');
                toggleLabel.textContent = 'Sembunyikan';
            }
        }

        checkMobile();
        window.addEventListener('resize', checkMobile);

        toggleBtn.addEventListener('click', function() {
            const isHidden = filterContent.style.display === 'none';

            if (isHidden) {
                filterContent.style.display = '';
                toggleLabel.textContent = 'Sembunyikan';
                toggleIcon.classList.remove('rotate-180');
                toggleBtn.setAttribute('aria-expanded', 'true');
            } else {
                filterContent.style.display = 'none';
                toggleLabel.textContent = 'Tampilkan';
                toggleIcon.classList.add('rotate-180');
                toggleBtn.setAttribute('aria-expanded', 'false');
            }
        });
    });
</script>
