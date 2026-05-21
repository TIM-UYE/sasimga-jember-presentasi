<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SaSimGa - User Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    @yield('extra-styles')
</head>

<body class="bg-gray-100">
    <div class="min-h-screen flex flex-col">
        <!-- Navbar -->
        <nav class="bg-purple-700 text-white shadow-lg">
            <div class="container mx-auto px-4">
                <div class="flex justify-between items-center py-4">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-store text-2xl"></i>
                        <span class="text-xl font-bold">SaSimGa</span>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm">Halo, {{ Auth::user()->nama }}</span>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="bg-purple-800 hover:bg-purple-900 px-4 py-2 rounded-lg text-sm transition">
                                <i class="fas fa-sign-out-alt mr-1"></i> Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="flex flex-1">
            <!-- Sidebar -->
            <aside class="w-64 bg-white shadow-md">
                <div class="p-4">
                    <ul class="space-y-2">
                        <li>
                            <a href="{{ route('user.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-purple-100 text-gray-700 hover:text-purple-700">
                                <i class="fas fa-home w-5"></i>
                                <span>Beranda</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-purple-100 text-gray-700 hover:text-purple-700">
                                <i class="fas fa-utensils w-5"></i>
                                <span>Menu</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-purple-100 text-gray-700 hover:text-purple-700">
                                <i class="fas fa-shopping-cart w-5"></i>
                                <span>Pesanan</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-purple-100 text-gray-700 hover:text-purple-700">
                                <i class="fas fa-history w-5"></i>
                                <span>Riwayat</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </aside>

            <!-- Content -->
            <main class="flex-1 p-6">
                @yield('content')
            </main>
        </div>
    </div>

    @yield('scripts')
</body>

</html>
