<nav class="relative flex flex-wrap items-center justify-between px-0 py-2 mx-6 transition-all duration-250 ease-soft-in rounded-2xl lg:flex-nowrap lg:justify-start"
    navbar-main navbar-scroll="true">

    <div class="flex items-center justify-between w-full px-4 py-2 mx-auto flex-wrap-inherit">

        {{-- LEFT --}}
        <div>

            @php
                $segment = request()->segment(2) ?? 'dashboard';
                $title = ucfirst(str_replace('-', ' ', $segment));
            @endphp

            {{-- BREADCRUMB --}}
            <ol class="flex flex-wrap pt-1 mr-12 bg-transparent rounded-lg sm:mr-16">

                <li class="text-sm leading-normal">
                    <a class="opacity-50 text-slate-500 hover:text-orange-500 transition" href="javascript:;">
                        Pages
                    </a>
                </li>

                <li class="text-sm pl-2 capitalize leading-normal text-slate-700 before:float-left before:pr-2 before:text-gray-400 before:content-['/']"
                    aria-current="page">
                    {{ $title }}
                </li>

            </ol>

            {{-- TITLE --}}
            <h6 class="mb-0 font-bold capitalize text-slate-800 text-xl">
                {{ $title }}
            </h6>

        </div>


        {{-- RIGHT --}}
        <div class="flex items-center gap-4 mt-2 sm:mt-0">




            {{-- HIDE SIDEBAR BUTTON --}}
            <button id="sidebarToggle" type="button" aria-label="Toggle Sidebar" aria-expanded="true"
                title="Toggle Sidebar"
                class="group relative flex h-11 w-11 items-center justify-center
    rounded-2xl
    border
    transition-all duration-300 ease-out
    hover:-translate-y-0.5
    active:translate-y-0
    active:scale-95
    focus:outline-none
    focus:ring-2
    focus:ring-orange-400/50">

                <span
                    class="absolute inset-0 rounded-2xl
        bg-black/15
        opacity-0
        transition-opacity duration-300
        group-hover:opacity-100">
                </span>

                <span
                    class="absolute inset-0 rounded-2xl
        bg-orange-300/30
        opacity-0 blur-xl
        transition-opacity duration-300
        group-hover:opacity-100">
                </span>

                <i
                    class="sidebar-toggle-icon fas fa-bars relative z-10 text-base
        drop-shadow-[0_2px_6px_rgba(0,0,0,0.35)]
        transition-transform duration-300
        group-hover:rotate-90">
                </i>

            </button>

        </div>

    </div>

</nav>
