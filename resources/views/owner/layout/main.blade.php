<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('admin_assets/img/apple-icon.png') }}" />
    <link rel="icon" type="image/png" href="{{ asset('admin_assets/img/logo.png') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>SaSimGa - Owner Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <link rel="stylesheet" href="{{ asset('admin_assets/css/sidebar-custom.css') }}">
    <style>
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #fb923c; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #ea580c; }
        .stat-card:hover { transform: translateY(-2px); }
    </style>
    @stack('style')
</head>
<body class="m-0 font-sans text-base antialiased font-normal leading-default bg-gray-50 text-slate-500">
    @include('sweetalert::alert')
    <nav class="fixed top-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-xl border-b border-slate-200/80 shadow-sm">
        <div class="px-6 h-16 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('owner.dashboard') }}" class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center shadow-lg shadow-orange-200/50">
                        <i class="fas fa-chart-line text-white text-sm"></i>
                    </div>
                    <div>
                        <span class="font-bold text-lg text-slate-800">SaSimGa</span>
                        <span class="text-[10px] text-orange-500 ml-2 font-semibold bg-orange-50 px-2 py-0.5 rounded-full">Owner Panel</span>
                    </div>
                </a>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ url('/') }}" class="px-3 py-2 text-xs font-medium text-slate-500 hover:text-orange-500 transition rounded-lg hover:bg-orange-50">
                    <i class="fas fa-external-link-alt mr-1"></i> Website
                </a>
                <div class="flex items-center gap-3 pl-3 border-l border-slate-200">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-semibold text-slate-700">{{ Auth::user()->nama }}</p>
                        <p class="text-[10px] text-orange-500 uppercase tracking-wider font-medium">Owner</p>
                    </div>
                    <div class="h-9 w-9 rounded-full bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center text-white text-xs font-bold shadow-md shadow-orange-200/50">
                        {{ strtoupper(substr(Auth::user()->nama, 0, 1)) }}
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-3 py-2 text-xs font-medium text-red-500 hover:text-red-600 transition rounded-lg hover:bg-red-50">
                        <i class="fas fa-sign-out-alt mr-1"></i>
                    </button>
                </form>
            </div>
        </div>
    </nav>
    <main class="pt-20 pb-8 px-6">
        <div class="w-full px-4 py-4 mx-auto max-w-7xl">
            @yield('content')
        </div>
    </main>
    @stack('script')
</body>
</html>
