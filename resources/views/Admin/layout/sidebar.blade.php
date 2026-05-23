<aside id="sidebar"
    class="fixed top-4 bottom-4 left-4 z-50
    w-64 rounded-2xl
    h-[calc(100vh-2rem)]
    max-h-[calc(100vh-2rem)]
    bg-linear-to-b from-orange-500 via-orange-600 to-orange-700
    shadow-soft-2xl
    overflow-y-auto overflow-x-hidden overscroll-contain
    custom-scrollbar
    transition-all duration-500 ease-in-out
    transform opacity-100 scale-100 translate-x-0">

    <div class="px-6 py-6">
        <a href="" class="flex items-center text-white">
            <img src="{{ asset('images/logo/logo.png') }}" class="h-10 w-10 rounded-full mr-3 shadow-md">
            <div>
                <div class="font-bold text-lg">SaSimGa</div>
                <div class="text-xs opacity-80">
                    @if (Auth::user()->role === 'manager')
                        Manager Panel
                    @elseif(Auth::user()->role === 'admin')
                        Admin Panel
                    @else
                        Backend Panel
                    @endif
                </div>
            </div>
        </a>
    </div>

    <hr class="border-white/20 mx-4">

    <ul class="mt-4 space-y-1 px-3 pb-6">
        <li>
            <a href="{{ route('admin.dashboard') }}"
                class="group flex items-center px-4 py-3 rounded-lg text-white font-semibold bg-linear-to-r from-orange-400 to-orange-600 hover:from-orange-500 hover:to-orange-700 transition">
                <div class="flex items-center justify-center h-9 w-9 rounded-lg bg-white text-orange-600 mr-3">
                    <i class="fas fa-tachometer-alt text-sm"></i>
                </div>
                Dashboard
            </a>
        </li>

        @if (Auth::user()->role === 'manager')
        <li>
            <a href="{{ route('admin.prediksi.index') }}"
               class="group flex items-center px-4 py-3 rounded-lg text-white font-semibold bg-linear-to-r from-orange-400 to-orange-600 hover:from-orange-500 hover:to-orange-700 transition {{ request()->routeIs('admin.prediksi.*') ? 'bg-white/20' : '' }}">
                <div class="flex items-center justify-center h-9 w-9 rounded-lg bg-white text-orange-600 mr-3">
                    <i class="fas fa-robot text-sm"></i>
                </div>
                AI Prediksi Stok
            </a>
        </li>
        @endif

        @if (Auth::user()->role === 'manager')
            <li>
                <button type="button" onclick="toggleSection(this)"
                    class="w-full group flex items-center px-4 py-3 rounded-lg text-white font-semibold bg-linear-to-r from-orange-400 to-orange-600 hover:from-orange-500 hover:to-orange-700 transition">

                    <div class="flex items-center justify-center h-9 w-9 rounded-lg bg-white text-orange-600 mr-3">
                        <i class="fas fa-utensils text-sm"></i>
                    </div>

                    <span class="flex-1 text-left">
                        Manajemen Menu
                    </span>

                    <i class="fas fa-chevron-down text-xs transition-transform duration-300 mr-1"></i>

                </button>
                <div class="ml-4 mt-1 space-y-1 overflow-hidden transition-all duration-300 hidden">
                    <a href="{{ route('admin.kategori.index') }}"
                        class="flex items-center pl-12 pr-4 py-2.5 rounded-lg text-white font-semibold hover:bg-white/10 transition {{ request()->routeIs('admin.kategori.*') ? 'bg-white/20' : '' }}">
                        <i class="fas fa-tags mr-3 text-sm"></i> Kategori Menu
                    </a>
                    <a href="{{ route('admin.menu.index') }}"
                        class="flex items-center pl-12 pr-4 py-2.5 rounded-lg text-white font-semibold hover:bg-white/10 transition {{ request()->routeIs('admin.menu.*') && !request()->routeIs('admin.menu-specials.*') ? 'bg-white/20' : '' }}">
                        <i class="fas fa-book mr-3 text-sm"></i> Menu Regular
                    </a>
                    <a href="{{ route('admin.menu-specials.index') }}"
                        class="flex items-center pl-12 pr-4 py-2.5 rounded-lg text-white font-semibold hover:bg-white/10 transition {{ request()->routeIs('admin.menu-specials.*') ? 'bg-white/20' : '' }}">
                        <i class="fas fa-fire mr-3 text-sm"></i> Menu Spesial
                    </a>
                </div>
            </li>
        @endif

        @if (Auth::user()->role === 'manager')
            <li>
                <button type="button" onclick="toggleSection(this)"
                    class="w-full group flex items-center px-4 py-3 rounded-lg text-white font-semibold bg-linear-to-r from-orange-400 to-orange-600 hover:from-orange-500 hover:to-orange-700 transition">
                    <div class="flex items-center justify-center h-9 w-9 rounded-lg bg-white text-orange-600 mr-3">
                        <i class="fas fa-boxes text-sm"></i>
                    </div>
                    <span class="flex-1 text-left">Inventori</span>
                    <i class="fas fa-chevron-down text-xs transition-transform duration-300 mr-1"></i>
                </button>
                <div class="ml-4 mt-1 space-y-1 overflow-hidden transition-all duration-300 hidden">
                    <a href="{{ route('admin.stok.index') }}"
                        class="flex items-center pl-12 pr-4 py-2.5 rounded-lg text-white font-semibold hover:bg-white/10 transition {{ request()->routeIs('admin.stok.index') ? 'bg-white/20' : '' }}">
                        <i class="fas fa-boxes-stacked mr-3 text-sm"></i> Stok Bahan
                    </a>
                    <a href="{{ route('admin.stok-log.index') }}"
                        class="flex items-center pl-12 pr-4 py-2.5 rounded-lg text-white font-semibold hover:bg-white/10 transition {{ request()->routeIs('admin.stok-log.*') ? 'bg-white/20' : '' }}">
                        <i class="fas fa-clock-rotate-left mr-3 text-sm"></i> Riwayat Stok
                    </a>
                </div>
            </li>
        @endif

        <li>
            <button type="button" onclick="toggleSection(this)"
                class="w-full group flex items-center px-4 py-3 rounded-lg text-white font-semibold bg-linear-to-r from-orange-400 to-orange-600 hover:from-orange-500 hover:to-orange-700 transition">
                <div class="flex items-center justify-center h-9 w-9 rounded-lg bg-white text-orange-600 mr-3">
                    <i class="fas fa-shopping-cart text-sm"></i>
                </div>
                <span class="flex-1 text-left">Transaksi</span>
                <i class="fas fa-chevron-down text-xs transition-transform duration-300 mr-1"></i>
            </button>
            <div class="ml-4 mt-1 space-y-1 overflow-hidden transition-all duration-300 hidden">
                <a href="{{ route('admin.orders.index') }}"
                    class="flex items-center pl-12 pr-4 py-2.5 rounded-lg text-white font-semibold hover:bg-white/10 transition {{ request()->routeIs('admin.orders.*') ? 'bg-white/20' : '' }}">
                    <i class="fas fa-shopping-bag mr-3 text-sm"></i> Pesanan
                </a>
                <a href="{{ route('admin.reservasi.index') }}"
                    class="flex items-center pl-12 pr-4 py-2.5 rounded-lg text-white font-semibold hover:bg-white/10 transition {{ request()->routeIs('admin.reservasi.*') ? 'bg-white/20' : '' }}">
                    <i class="fas fa-calendar-alt mr-3 text-sm"></i> Reservasi
                </a>
                <a href="{{ route('admin.meja.index') }}"
                    class="flex items-center pl-12 pr-4 py-2.5 rounded-lg text-white font-semibold hover:bg-white/10 transition {{ request()->routeIs('admin.meja.*') ? 'bg-white/20' : '' }}">
                    <i class="fas fa-chair mr-3 text-sm"></i> Meja
                </a>
            </div>
        </li>

        @if (Auth::user()->role === 'manager')
    <li>
        <button type="button" onclick="toggleSection(this)"
            class="w-full group flex items-center px-4 py-3 rounded-lg text-white font-semibold bg-linear-to-r from-orange-400 to-orange-600 hover:from-orange-500 hover:to-orange-700 transition">
            
            <div class="flex items-center justify-center h-9 w-9 rounded-lg bg-white text-orange-600 mr-3">
                <i class="fas fa-chart-pie text-sm"></i>
            </div>
            
            <span class="flex-1 text-left">Laporan</span>
            <i class="fas fa-chevron-down text-xs transition-transform duration-300 mr-1"></i>
        </button>
        
        <div class="ml-4 mt-1 space-y-1 overflow-hidden transition-all duration-300 hidden">
            <a href="{{ route('admin.laporan.pesanan') }}"
                class="flex items-center pl-12 pr-4 py-2.5 rounded-lg text-white font-semibold hover:bg-white/10 transition {{ request()->routeIs('admin.laporan.pesanan') ? 'bg-white/20' : '' }}">
                <i class="fas fa-file-invoice-dollar mr-3 text-sm"></i> Laporan Pesanan
            </a>
            <a href="{{ route('admin.laporan.reservasi') }}"
                class="flex items-center pl-12 pr-4 py-2.5 rounded-lg text-white font-semibold hover:bg-white/10 transition {{ request()->routeIs('admin.laporan.reservasi') ? 'bg-white/20' : '' }}">
                <i class="fas fa-file-invoice mr-3 text-sm"></i> Laporan Reservasi
            </a>
            <a href="{{ route('admin.laporan.stok') }}"
                class="flex items-center pl-12 pr-4 py-2.5 rounded-lg text-white font-semibold hover:bg-white/10 transition {{ request()->routeIs('admin.laporan.stok') ? 'bg-white/20' : '' }}">
                <i class="fas fa-file-lines mr-3 text-sm"></i> Laporan Stok
            </a>
        </div>
    </li>
@endif

        @if (Auth::user()->role === 'manager')
            <li>
                <button type="button" onclick="toggleSection(this)"
                    class="w-full group flex items-center px-4 py-3 rounded-lg text-white font-semibold bg-linear-to-r from-orange-400 to-orange-600 hover:from-orange-500 hover:to-orange-700 transition">
                    <div class="flex items-center justify-center h-9 w-9 rounded-lg bg-white text-orange-600 mr-3">
                        <i class="fas fa-palette text-sm"></i>
                    </div>
                    <span class="flex-1 text-left">Konten Website</span>
                    <i class="fas fa-chevron-down text-xs transition-transform duration-300 mr-1"></i>
                </button>
                <div class="ml-4 mt-1 space-y-1 overflow-hidden transition-all duration-300 hidden">
                    <a href="{{ route('admin.galeri.index') }}"
                        class="flex items-center pl-12 pr-4 py-2.5 rounded-lg text-white font-semibold hover:bg-white/10 transition {{ request()->routeIs('admin.galeri.*') ? 'bg-white/20' : '' }}">
                        <i class="fas fa-images mr-3 text-sm"></i> Galeri
                    </a>
                    <a href="{{ route('admin.video.index') }}"
                        class="flex items-center pl-12 pr-4 py-2.5 rounded-lg text-white font-semibold hover:bg-white/10 transition {{ request()->routeIs('admin.video.*') ? 'bg-white/20' : '' }}">
                        <i class="fas fa-video mr-3 text-sm"></i> Video
                    </a>
                    <a href="{{ route('admin.information.index') }}"
                        class="flex items-center pl-12 pr-4 py-2.5 rounded-lg text-white font-semibold hover:bg-white/10 transition {{ request()->routeIs('admin.information.*') ? 'bg-white/20' : '' }}">
                        <i class="fas fa-info-circle mr-3 text-sm"></i> Informasi
                    </a>
                    <a href="{{ route('admin.google-reviews.index') }}"
                        class="flex items-center pl-12 pr-4 py-2.5 rounded-lg text-white font-semibold hover:bg-white/10 transition {{ request()->routeIs('admin.google-reviews.*') ? 'bg-white/20' : '' }}">
                        <i class="fab fa-google mr-3 text-sm"></i> Google Reviews
                    </a>
                </div>
            </li>
        @endif

        @if (Auth::user()->role === 'manager')
            <li>
                <a href="{{ route('admin.user.index') }}"
                    class="group flex items-center px-4 py-3 rounded-lg text-white font-semibold bg-linear-to-r from-orange-400 to-orange-600 hover:from-orange-500 hover:to-orange-700 transition">
                    <div class="flex items-center justify-center h-9 w-9 rounded-lg bg-white text-orange-600 mr-3">
                        <i class="fas fa-users text-sm"></i>
                    </div>
                    Kelola User
                </a>
            </li>
        @endif

        <li class="pt-4">
            <a href="{{ url('/') }}"
                class="flex items-center px-4 py-3 rounded-lg text-white font-semibold bg-linear-to-r from-orange-700 to-orange-900 hover:from-orange-800 hover:to-orange-950 transition">
                <div class="flex items-center justify-center h-9 w-9 rounded-lg bg-white text-orange-700 mr-3">
                    <i class="fas fa-external-link-alt text-sm"></i>
                </div>
                Kembali ke Website
            </a>
        </li>

        <li>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                    class="w-full group flex items-center px-4 py-3 rounded-lg text-white font-semibold bg-linear-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 transition">
                    <div class="flex items-center justify-center h-9 w-9 rounded-lg bg-white text-red-600 mr-3">
                        <i class="fas fa-sign-out-alt text-sm"></i>
                    </div>
                    Logout
                </button>
            </form>
        </li>

    </ul>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // Toggle dropdown sidebar
            window.toggleSection = function(button) {
                const dropdown = button.nextElementSibling;
                const icon = button.querySelector('.fa-chevron-down');

                // toggle current dropdown
                dropdown.classList.toggle('hidden');
                icon.classList.toggle('rotate-180');
            };

            // Auto open active dropdown
            document.querySelectorAll('#sidebar li').forEach((li) => {

                const activeChild = li.querySelector('a.bg-white\\/20');

                if (activeChild) {
                    const dropdown = li.querySelector('div.hidden, div:not(.hidden)');
                    const button = li.querySelector('button');
                    const icon = li.querySelector('.fa-chevron-down');

                    if (dropdown && button) {
                        dropdown.classList.remove('hidden');

                        if (icon) {
                            icon.classList.add('rotate-180');
                        }
                    }
                }
            });

        });
    </script>

</aside>