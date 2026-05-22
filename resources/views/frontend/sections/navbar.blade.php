@php
    $isMenuPage = request()->routeIs('frontend.menu') || request()->is('menu*');
@endphp

<nav id="siteNavbar" data-static-navbar="{{ $isMenuPage ? 'true' : 'false' }}"
    class="fixed top-0 left-0 bg-black/90 backdrop-blur-md w-full z-50 border-b border-white/5
    {{ $isMenuPage ? 'translate-y-0' : 'transition-transform duration-500 ease-out will-change-transform translate-y-0' }}">

    <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">

        {{-- LEFT : LOGO & MENU --}}
        <div class="flex items-center gap-12">

            {{-- LOGO --}}
            <a href="{{ route('frontend.home') }}" class="flex items-center gap-3 group">

                <div
                    class="h-10 w-auto overflow-hidden transition-all duration-500 group-hover:ring-orange-500/50 group-hover:bg-white/10">

                    <img src="{{ asset('images/logo/logo.png') }}" alt="SaSimGa"
                        class="h-full w-auto object-contain brightness-110 transition-all duration-500 group-hover:brightness-125 group-hover:scale-105">

                </div>

            </a>


            {{-- DESKTOP MENU --}}
            <div class="items-center gap-1 hidden md:flex">

                <x-frontend.navbar.link href="{{ route('frontend.home') }}">
                    {{ __('frontend.nav.home') }}
                </x-frontend.navbar.link>

                <x-frontend.navbar.link href="{{ route('frontend.about') }}">
                    {{ __('frontend.nav.about') }}
                </x-frontend.navbar.link>

                <x-frontend.navbar.link href="{{ route('frontend.menu') }}">
                    {{ __('frontend.nav.menu') }}
                </x-frontend.navbar.link>

                <x-frontend.navbar.link href="{{ route('frontend.reservasi') }}">
                    {{ __('frontend.nav.reservation') }}
                </x-frontend.navbar.link>

                @auth

                    @if (in_array(auth()->user()->role, ['admin', 'manager']))
                        <x-frontend.navbar.link href="{{ route('admin.dashboard') }}" variant="orange" :active="request()->routeIs('admin.dashboard')">
                            Dashboard
                        </x-frontend.navbar.link>
                    @endif
                @else
                    <x-frontend.navbar.link href="{{ route('login') }}" :active="request()->routeIs('login')">
                        Login
                    </x-frontend.navbar.link>

                @endauth

            </div>

        </div>


        {{-- RIGHT MENU --}}
        <div class="flex items-center gap-4">

            {{-- LANGUAGE TOGGLE --}}
            <div class="language-dropdown notranslate" translate="no" data-language-dropdown>
                <button type="button" class="language-dropdown__button" data-language-toggle>
                    <span class="language-dropdown__globe">
                        <i class="fa-solid fa-globe"></i>
                    </span>

                    <span class="language-dropdown__current" data-language-current>
                        ID
                    </span>

                    <i class="fa-solid fa-chevron-down language-dropdown__chevron"></i>
                </button>

                <div class="language-dropdown__menu" data-language-menu>
                    <button type="button" class="language-dropdown__item" data-lang="id" data-label="ID">
                        <span class="language-dropdown__code">ID</span>
                        <span class="language-dropdown__name">Indonesia</span>
                    </button>

                    <button type="button" class="language-dropdown__item" data-lang="en" data-label="EN">
                        <span class="language-dropdown__code">EN</span>
                        <span class="language-dropdown__name">English</span>
                    </button>

                    <button type="button" class="language-dropdown__item" data-lang="ja" data-label="JA">
                        <span class="language-dropdown__code">JA</span>
                        <span class="language-dropdown__name">Japanese</span>
                    </button>

                    <button type="button" class="language-dropdown__item" data-lang="ko" data-label="KO">
                        <span class="language-dropdown__code">KO</span>
                        <span class="language-dropdown__name">Korean</span>
                    </button>

                    <button type="button" class="language-dropdown__item" data-lang="ar" data-label="AR">
                        <span class="language-dropdown__code">AR</span>
                        <span class="language-dropdown__name">Arabic</span>
                    </button>
                </div>
            </div>

            {{-- CART --}}
            <a href="{{ route('cart.index') }}" data-cart-icon
                data-cart-count="{{ session('cart') ? count(session('cart')) : 0 }}"
                class="relative flex h-10 w-10 items-center justify-center rounded-full bg-zinc-900 border border-white/10 ring-1 ring-white/10 hover:ring-orange-500/50 hover:border-orange-500/40 transition-all duration-300 hover:scale-110 active:scale-95">

                {{-- ICON --}}
                <i class="fa-solid fa-cart-shopping text-white text-[18px]"></i>

                {{-- BADGE --}}
                <span id="cartBadge"
                    class="absolute -top-1 -right-1 bg-orange-500 text-white text-[10px] min-w-[18px] h-[18px] px-1 rounded-full flex items-center justify-center font-bold shadow-lg shadow-orange-500/40 {{ session('cart') && count(session('cart')) > 0 ? '' : 'hidden' }}">
                    {{ session('cart') ? count(session('cart')) : 0 }}
                </span>

            </a>


            {{-- PROFILE --}}
            <div class="relative">

                @auth

                    <button type="button" onclick="openProfileMenu()"
                        class="relative flex h-10 w-10 items-center justify-center rounded-full bg-zinc-900 border border-white/10 ring-1 ring-white/10 hover:ring-orange-500/50 hover:border-orange-500/40 transition-all duration-300 hover:scale-110 active:scale-95 overflow-hidden">

                        <div class="h-full w-full rounded-full overflow-hidden bg-black">

                            @if (auth()->user()->profile_photo)
                                <img src="{{ asset('storage/profile/' . auth()->user()->profile_photo) }}" alt="Profil"
                                    class="h-full w-full object-cover">
                            @else
                                <div
                                    class="flex h-full w-full items-center justify-center text-sm font-bold text-white bg-gradient-to-br from-orange-500 to-orange-700">

                                    {{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}

                                </div>
                            @endif

                        </div>

                        <span
                            class="absolute -bottom-0.5 -right-0.5 h-3 w-3 rounded-full border-2 border-black bg-green-400">
                        </span>

                    </button>

                    <span
                        class="absolute -bottom-0.5 -right-0.5 h-3 w-3 rounded-full border-2 border-black bg-green-400"></span>

                    </button>
                @else
                    <a href="{{ route('login') }}"
                        class="flex h-10 w-10 items-center justify-center rounded-full bg-zinc-900 border border-white/10 ring-1 ring-white/10 hover:ring-orange-500/50 hover:border-orange-500/40 text-white/70 hover:text-white transition-all duration-300 hover:scale-110 active:scale-95">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />

                        </svg>

                    </a>

                @endauth

            </div>

        </div>


        {{-- PROFILE POPUP --}}
        @auth

            <div id="profileMenuOverlay" class="fixed inset-0 bg-black/50 z-40 hidden" onclick="closeProfileMenu()"></div>


            <div id="profileMenuPopup"
                class="fixed top-16 right-6 z-50 hidden w-64 rounded-2xl border border-white/10 bg-zinc-900/95 backdrop-blur-xl shadow-2xl shadow-black/50">

                <div class="p-5">

                    {{-- PROFILE HEADER --}}
                    <div class="flex items-center gap-3 border-b border-white/10 pb-4 mb-4">

                        <div
                            class="h-10 w-10 rounded-full ring-2 ring-orange-500/30 overflow-hidden bg-black flex-shrink-0">

                            @if (auth()->user()->profile_photo)
                                <img src="{{ asset('storage/profile/' . auth()->user()->profile_photo) }}" alt="Profil"
                                    class="h-full w-full object-cover">
                            @else
                                <div
                                    class="flex h-full w-full items-center justify-center text-sm font-bold text-white bg-gradient-to-br from-orange-500 to-orange-700">
                                    {{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}
                                </div>
                            @endif

                        </div>

                        <div class="min-w-0">

                            <p class="text-sm font-semibold text-white truncate">
                                {{ auth()->user()->nama }}
                            </p>

                            <p class="text-xs text-white/50 truncate">
                                {{ auth()->user()->email }}
                            </p>

                        </div>

                    </div>


                    {{-- PROFILE --}}
                    <a href="{{ route('profile') }}"
                        class="flex items-center gap-3 px-3 py-2.5 text-sm text-white/70 hover:text-white rounded-xl hover:bg-white/5 transition-all duration-200">

                        <svg class="h-4 w-4 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />

                        </svg>

                        Pengaturan Profil

                    </a>


                    {{-- DASHBOARD --}}
                    <a href="{{ in_array(auth()->user()->role, ['admin', 'manager']) ? route('admin.dashboard') : route('user.dashboard') }}"
                        class="flex items-center gap-3 px-3 py-2.5 text-sm text-white/70 hover:text-white rounded-xl hover:bg-white/5 transition-all duration-200">

                        <svg class="h-4 w-4 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />

                        </svg>

                        Dashboard

                    </a>


                    {{-- LOGOUT --}}
                    <form action="{{ route('logout') }}" method="POST" class="mt-4 pt-4 border-t border-white/10">

                        @csrf

                        <button type="submit"
                            class="flex items-center justify-center gap-2 w-full rounded-xl bg-red-500/10 hover:bg-red-500/20 text-red-400 hover:text-red-300 px-4 py-2.5 text-sm font-medium transition-all duration-200">

                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />

                            </svg>

                            Logout

                        </button>

                    </form>

                </div>

            </div>


            {{-- SCRIPT --}}
            <script>
                function openProfileMenu() {

                    document.getElementById('profileMenuOverlay').classList.remove('hidden');

                    document.getElementById('profileMenuPopup').classList.remove('hidden');

                    document.getElementById('profileMenuPopup').classList.add('animate-in');
                }

                function closeProfileMenu() {

                    document.getElementById('profileMenuOverlay').classList.add('hidden');

                    document.getElementById('profileMenuPopup').classList.add('hidden');

                    document.getElementById('profileMenuPopup').classList.remove('animate-in');
                }
            </script>

        @endauth

    </div>

</nav>
