@extends('admin.layout.main')

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-slate-800 tracking-tight">Dashboard
                
            </h1>
            <p class="text-slate-500 text-sm mt-1">Selamat datang kembali, <span class="font-semibold text-slate-700">{{ Auth::user()->nama }}</span>!</p>
        </div>
        <div class="flex items-center gap-2 text-xs text-slate-400">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            <span>{{ now()->isoFormat('dddd, D MMMM YYYY') }}</span>
        </div>
    </div>

    {{-- STATS CARDS -- ROW 1 --}}
    <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">

        {{-- Total Users --}}
        <div class="group relative bg-white rounded-2xl p-5 shadow-sm ring-1 ring-slate-200/80 hover:shadow-lg hover:shadow-purple-500/10 hover:ring-purple-200 transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="h-11 w-11 rounded-2xl bg-linear-to-br from-purple-400 to-purple-600 flex items-center justify-center shadow-lg shadow-purple-200/50 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/></svg>
                </div>
                <span class="text-[10px] font-medium text-purple-500 bg-purple-50 px-2 py-1 rounded-lg">+ Aktif</span>
            </div>
            <p class="text-xs font-medium text-slate-400 uppercase tracking-wider mb-0.5">Total Users</p>
            <p class="text-2xl font-bold text-slate-800">{{ \App\Models\User::where('role', 'user')->count() }}</p>
            <div class="mt-3 h-1 w-full rounded-full bg-purple-100 overflow-hidden">
                <div class="h-full w-3/4 rounded-full bg-linear-to-r from-purple-400 to-purple-600"></div>
            </div>
        </div>

        {{-- Total Menu --}}
        <div class="group relative bg-white rounded-2xl p-5 shadow-sm ring-1 ring-slate-200/80 hover:shadow-lg hover:shadow-blue-500/10 hover:ring-blue-200 transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="h-11 w-11 rounded-2xl bg-linear-to-br from-blue-400 to-blue-600 flex items-center justify-center shadow-lg shadow-blue-200/50 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                </div>
                <span class="text-[10px] font-medium text-blue-500 bg-blue-50 px-2 py-1 rounded-lg">{{ \App\Models\KategoriMenu::count() }} Kat</span>
            </div>
            <p class="text-xs font-medium text-slate-400 uppercase tracking-wider mb-0.5">Total Menu</p>
            <p class="text-2xl font-bold text-slate-800">{{ \App\Models\Menu::count() }}</p>
            <div class="mt-3 h-1 w-full rounded-full bg-blue-100 overflow-hidden">
                <div class="h-full w-full rounded-full bg-linear-to-r from-blue-400 to-blue-600"></div>
            </div>
        </div>

        {{-- Special Menu --}}
        <div class="group relative bg-white rounded-2xl p-5 shadow-sm ring-1 ring-slate-200/80 hover:shadow-lg hover:shadow-cyan-500/10 hover:ring-cyan-200 transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="h-11 w-11 rounded-2xl bg-linear-to-br from-cyan-400 to-cyan-600 flex items-center justify-center shadow-lg shadow-cyan-200/50 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"/></svg>
                </div>
                <span class="text-[10px] font-medium text-cyan-500 bg-cyan-50 px-2 py-1 rounded-lg">Special</span>
            </div>
            <p class="text-xs font-medium text-slate-400 uppercase tracking-wider mb-0.5">Menu Specials</p>
            <p class="text-2xl font-bold text-slate-800">{{ \App\Models\MenuSpecial::count() }}</p>
            <div class="mt-3 h-1 w-full rounded-full bg-cyan-100 overflow-hidden">
                <div class="h-full w-full rounded-full bg-linear-to-r from-cyan-400 to-cyan-600"></div>
            </div>
        </div>

        {{-- Orders Menunggu (Pending) --}}
        <div class="group relative bg-white rounded-2xl p-5 shadow-sm ring-1 ring-slate-200/80 hover:shadow-lg hover:shadow-amber-500/10 hover:ring-amber-200 transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="h-11 w-11 rounded-2xl bg-linear-to-br from-amber-400 to-amber-600 flex items-center justify-center shadow-lg shadow-amber-200/50 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="text-[10px] font-medium text-amber-500 bg-amber-50 px-2 py-1 rounded-lg animate-pulse">Pending</span>
            </div>
            <p class="text-xs font-medium text-slate-400 uppercase tracking-wider mb-0.5">Order Pending</p>
            <p class="text-2xl font-bold text-slate-800">{{ \App\Models\Order::where('status', \App\Models\Order::STATUS_PENDING)->count() }}</p>
            <div class="mt-3 h-1 w-full rounded-full bg-amber-100 overflow-hidden">
                <div class="h-full w-1/2 rounded-full bg-linear-to-r from-amber-400 to-amber-600"></div>
            </div>
        </div>

        {{-- Orders Hari Ini --}}
        <div class="group relative bg-white rounded-2xl p-5 shadow-sm ring-1 ring-slate-200/80 hover:shadow-lg hover:shadow-emerald-500/10 hover:ring-emerald-200 transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="h-11 w-11 rounded-2xl bg-linear-to-br from-emerald-400 to-emerald-600 flex items-center justify-center shadow-lg shadow-emerald-200/50 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                </div>
                <span class="text-[10px] font-medium text-emerald-500 bg-emerald-50 px-2 py-1 rounded-lg">Hari Ini</span>
            </div>
            <p class="text-xs font-medium text-slate-400 uppercase tracking-wider mb-0.5">Order Hari Ini</p>
            <p class="text-2xl font-bold text-slate-800">{{ \App\Models\Order::whereDate('created_at', today())->count() }}</p>
            <div class="mt-3 h-1 w-full rounded-full bg-emerald-100 overflow-hidden">
                <div class="h-full w-2/3 rounded-full bg-linear-to-r from-emerald-400 to-emerald-600"></div>
            </div>
        </div>

        {{-- Pendapatan Hari Ini (dari Order selesai) --}}
        <div class="group relative bg-white rounded-2xl p-5 shadow-sm ring-1 ring-slate-200/80 hover:shadow-lg hover:shadow-orange-500/10 hover:ring-orange-200 transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="h-11 w-11 rounded-2xl bg-linear-to-br from-orange-400 to-amber-600 flex items-center justify-center shadow-lg shadow-orange-200/50 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="text-[10px] font-medium text-orange-500 bg-orange-50 px-2 py-1 rounded-lg">Hari Ini</span>
            </div>
            <p class="text-xs font-medium text-slate-400 uppercase tracking-wider mb-0.5">Pendapatan</p>
            @php
                $todayRevenueOrders = \App\Models\Order::whereDate('created_at', today())
                    ->where('status', \App\Models\Order::STATUS_SELESAI)
                    ->sum('total_bayar');
                $todayRevenueTransaksi = \App\Models\Transaksi::whereDate('tanggal', today())->sum('total_harga');
            @endphp
            <p class="text-xl font-bold text-slate-800">Rp {{ number_format(max($todayRevenueOrders, $todayRevenueTransaksi), 0, ',', '.') }}</p>
            <div class="mt-3 h-1 w-full rounded-full bg-orange-100 overflow-hidden">
                <div class="h-full w-4/5 rounded-full bg-linear-to-r from-orange-400 to-amber-600"></div>
            </div>
        </div>

    </div>

    {{-- STATS CARDS -- ROW 2 --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-4">

        {{-- Reservasi Pending --}}
        <div class="group relative bg-white rounded-2xl p-5 shadow-sm ring-1 ring-slate-200/80 hover:shadow-lg hover:shadow-rose-500/10 hover:ring-rose-200 transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="h-11 w-11 rounded-2xl bg-linear-to-br from-rose-400 to-rose-600 flex items-center justify-center shadow-lg shadow-rose-200/50 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <span class="text-[10px] font-medium text-rose-500 bg-rose-50 px-2 py-1 rounded-lg animate-pulse">Pending</span>
            </div>
            <p class="text-xs font-medium text-slate-400 uppercase tracking-wider mb-0.5">Reservasi Pending</p>
            <p class="text-2xl font-bold text-slate-800">{{ \App\Models\Reservasi::where('status', 'pending')->count() }}</p>
            <div class="mt-3 h-1 w-full rounded-full bg-rose-100 overflow-hidden">
                <div class="h-full w-1/2 rounded-full bg-linear-to-r from-rose-400 to-rose-600"></div>
            </div>
        </div>

        {{-- Total Reservasi Hari Ini --}}
        <div class="group relative bg-white rounded-2xl p-5 shadow-sm ring-1 ring-slate-200/80 hover:shadow-lg hover:shadow-pink-500/10 hover:ring-pink-200 transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="h-11 w-11 rounded-2xl bg-linear-to-br from-pink-400 to-pink-600 flex items-center justify-center shadow-lg shadow-pink-200/50 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="text-[10px] font-medium text-pink-500 bg-pink-50 px-2 py-1 rounded-lg">Hari Ini</span>
            </div>
            <p class="text-xs font-medium text-slate-400 uppercase tracking-wider mb-0.5">Reservasi Hari Ini</p>
            <p class="text-2xl font-bold text-slate-800">{{ \App\Models\Reservasi::whereDate('tanggal_reservasi', today())->count() }}</p>
            <div class="mt-3 h-1 w-full rounded-full bg-pink-100 overflow-hidden">
                <div class="h-full w-1/2 rounded-full bg-linear-to-r from-pink-400 to-pink-600"></div>
            </div>
        </div>

        {{-- Testimoni --}}
        <div class="group relative bg-white rounded-2xl p-5 shadow-sm ring-1 ring-slate-200/80 hover:shadow-lg hover:shadow-teal-500/10 hover:ring-teal-200 transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="h-11 w-11 rounded-2xl bg-linear-to-br from-teal-400 to-teal-600 flex items-center justify-center shadow-lg shadow-teal-200/50 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                </div>
                <span class="text-[10px] font-medium text-teal-500 bg-teal-50 px-2 py-1 rounded-lg">Total</span>
            </div>
            <p class="text-xs font-medium text-slate-400 uppercase tracking-wider mb-0.5">Testimoni</p>
            <p class="text-2xl font-bold text-slate-800">{{ \App\Models\Testimoni::count() }}</p>
            <div class="mt-3 h-1 w-full rounded-full bg-teal-100 overflow-hidden">
                <div class="h-full w-3/4 rounded-full bg-linear-to-r from-teal-400 to-teal-600"></div>
            </div>
        </div>

        {{-- Galeri Foto --}}
        <div class="group relative bg-white rounded-2xl p-5 shadow-sm ring-1 ring-slate-200/80 hover:shadow-lg hover:shadow-indigo-500/10 hover:ring-indigo-200 transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="h-11 w-11 rounded-2xl bg-linear-to-br from-indigo-400 to-indigo-600 flex items-center justify-center shadow-lg shadow-indigo-200/50 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <span class="text-[10px] font-medium text-indigo-500 bg-indigo-50 px-2 py-1 rounded-lg">Foto</span>
            </div>
            <p class="text-xs font-medium text-slate-400 uppercase tracking-wider mb-0.5">Galeri</p>
            {{-- <p class="text-2xl font-bold text-slate-800">{{ \App\Models\Galeri::count() }}</p> --}}
            <div class="mt-3 h-1 w-full rounded-full bg-indigo-100 overflow-hidden">
                <div class="h-full w-3/4 rounded-full bg-linear-to-r from-indigo-400 to-indigo-600"></div>
            </div>
        </div>

        {{-- Promosi Aktif --}}
        <div class="group relative bg-white rounded-2xl p-5 shadow-sm ring-1 ring-slate-200/80 hover:shadow-lg hover:shadow-lime-500/10 hover:ring-lime-200 transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="h-11 w-11 rounded-2xl bg-linear-to-br from-lime-400 to-lime-600 flex items-center justify-center shadow-lg shadow-lime-200/50 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </div>
                <span class="text-[10px] font-medium text-lime-500 bg-lime-50 px-2 py-1 rounded-lg">Aktif</span>
            </div>
            <p class="text-xs font-medium text-slate-400 uppercase tracking-wider mb-0.5">Promosi</p>
            {{-- <p class="text-2xl font-bold text-slate-800">{{ \App\Models\Promosi::count() }}</p> --}}
            <div class="mt-3 h-1 w-full rounded-full bg-lime-100 overflow-hidden">
                <div class="h-full w-3/4 rounded-full bg-linear-to-r from-lime-400 to-lime-600"></div>
            </div>
        </div>

    </div>

    {{-- STATUS RINGKASAN ORDERS --}}
    <div class="bg-white rounded-2xl shadow-sm ring-1 ring-slate-200/80 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                <span class="text-sm font-semibold text-slate-700">Ringkasan Status Orders</span>
            </div>
            <a href="{{ route('admin.orders.index') }}" class="text-xs text-orange-500 hover:text-orange-600 font-medium hover:underline">Lihat Semua</a>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 divide-x divide-slate-100">
            @php
                $statusCounts = [
                    \App\Models\Order::STATUS_PENDING => ['label' => 'Pending', 'color' => 'bg-amber-400', 'text' => 'text-amber-600'],
                    \App\Models\Order::STATUS_DIPROSES => ['label' => 'Diproses', 'color' => 'bg-blue-400', 'text' => 'text-blue-600'],
                    \App\Models\Order::STATUS_DIMASAK => ['label' => 'Dimasak', 'color' => 'bg-orange-400', 'text' => 'text-orange-600'],
                    \App\Models\Order::STATUS_SIAP_DIAMBIL => ['label' => 'Siap Diambil', 'color' => 'bg-green-400', 'text' => 'text-green-600'],
                    \App\Models\Order::STATUS_DIANTAR => ['label' => 'Diantar', 'color' => 'bg-indigo-400', 'text' => 'text-indigo-600'],
                    \App\Models\Order::STATUS_SELESAI => ['label' => 'Selesai', 'color' => 'bg-emerald-400', 'text' => 'text-emerald-600'],
                    \App\Models\Order::STATUS_DIBATALKAN => ['label' => 'Batal', 'color' => 'bg-red-400', 'text' => 'text-red-600'],
                ];
            @endphp
            @foreach($statusCounts as $key => $info)
                @php $count = \App\Models\Order::where('status', $key)->count(); @endphp
                <div class="px-4 py-5 text-center hover:bg-slate-50/60 transition-all duration-200">
                    <span class="inline-flex items-center justify-center w-3 h-3 rounded-full {{ $info['color'] }} mb-2"></span>
                    <p class="text-lg font-bold text-slate-800">{{ $count }}</p>
                    <p class="text-[10px] font-medium text-slate-400 uppercase tracking-wider mt-0.5">{{ $info['label'] }}</p>
                </div>
            @endforeach
        </div>
    </div>

    {{-- BOTTOM ROW: Recent Activity & Quick Actions --}}
    <div class="grid lg:grid-cols-2 gap-6">

        {{-- Recent Activity --}}
        <div class="bg-white rounded-2xl shadow-sm ring-1 ring-slate-200/80 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    <span class="text-sm font-semibold text-slate-700">Aktivitas Terbaru</span>
                </div>
                <a href="{{ route('admin.orders.index') }}" class="text-xs text-orange-500 hover:text-orange-600 font-medium hover:underline">Lihat Semua</a>
            </div>
            <div class="divide-y divide-slate-100">
                @php
                    // Gabungkan aktivitas order & reservasi terbaru
                    $recentOrders = \App\Models\Order::with('items')->latest()->take(5)->get();
                    $recentReservasis = \App\Models\Reservasi::latest()->take(3)->get();
                @endphp

                {{-- Tampilkan Orders Terbaru --}}
                @forelse($recentOrders as $order)
                    @php
                        $statusColors = [
                            'pending' => 'bg-amber-400',
                            'diproses' => 'bg-blue-400',
                            'dimasak' => 'bg-orange-400',
                            'siap_diambil' => 'bg-green-400',
                            'diantar' => 'bg-indigo-400',
                            'selesai' => 'bg-emerald-400',
                            'dibatalkan' => 'bg-red-400',
                        ];
                        $deliveryIcon = $order->isDelivery() ? 'fa-motorcycle' : 'fa-store';
                        $deliveryLabel = $order->isDelivery() ? 'Delivery' : 'Pickup';
                    @endphp
                    <div class="flex items-center gap-4 px-6 py-4 hover:bg-slate-50/60 transition-all duration-200">
                        <div class="h-9 w-9 rounded-xl bg-linear-to-br from-orange-400 to-amber-600 flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
                            <i class="fas {{ $deliveryIcon }} text-xs"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-slate-800 truncate">
                                Order: {{ $order->kode_order }}
                                <span class="text-xs font-normal text-slate-400">({{ $deliveryLabel }})</span>
                            </p>
                            <p class="text-xs text-slate-400 truncate">
                                {{ $order->nama_pelanggan }} •
                                Rp {{ number_format($order->total_bayar, 0, ',', '.') }}
                            </p>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full {{ $statusColors[$order->status] ?? 'bg-slate-400' }}"></span>
                            <span class="text-xs text-slate-400">{{ $order->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-6 text-center">
                        <svg class="w-10 h-10 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                        <p class="text-sm text-slate-500">Belum ada order</p>
                    </div>
                @endforelse

                {{-- Separator jika ada reservasi --}}
                @if($recentReservasis->isNotEmpty())
                    <div class="px-6 py-2 bg-slate-50/80">
                        <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">Reservasi</p>
                    </div>
                    @foreach($recentReservasis as $reservasi)
                        @php
                            $statusColor = [
                                'pending' => 'bg-amber-400',
                                'confirmed' => 'bg-emerald-400',
                                'cancelled' => 'bg-red-400',
                                'completed' => 'bg-blue-400',
                            ][$reservasi->status] ?? 'bg-slate-400';
                        @endphp
                        <div class="flex items-center gap-4 px-6 py-4 hover:bg-slate-50/60 transition-all duration-200">
                            <div class="h-9 w-9 rounded-xl bg-linear-to-br from-sky-400 to-blue-600 flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
                                {{ strtoupper(substr($reservasi->nama, 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-slate-800 truncate">{{ $reservasi->nama }}</p>
                                <p class="text-xs text-slate-400 truncate">
                                    {{ \Carbon\Carbon::parse($reservasi->tanggal_reservasi)->isoFormat('D MMM YYYY') }} •
                                    {{ $reservasi->jumlah_orang }} orang
                                </p>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full {{ $statusColor }}"></span>
                                <span class="text-xs text-slate-400">{{ $reservasi->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        {{-- Quick Actions Menu --}}
        <div class="bg-white rounded-2xl shadow-sm ring-1 ring-slate-200/80 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-2">
                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                <span class="text-sm font-semibold text-slate-700">Aksi Cepat</span>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 gap-4">
                    <a href="{{ route('admin.orders.index') }}"
                       class="group flex flex-col items-center gap-3 p-5 rounded-2xl bg-linear-to-br from-amber-50 to-orange-50 ring-1 ring-amber-200/50 hover:ring-amber-300/60 hover:shadow-lg hover:shadow-amber-200/30 transition-all duration-300">
                        <div class="h-12 w-12 rounded-2xl bg-linear-to-br from-amber-400 to-orange-500 flex items-center justify-center shadow-md shadow-amber-200/50 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                        </div>
                        <div class="text-center">
                            <p class="text-sm font-semibold text-slate-700">Pesanan</p>
                            <p class="text-[10px] text-slate-400 mt-0.5">{{ \App\Models\Order::where('status', \App\Models\Order::STATUS_PENDING)->count() }} pending</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.reservasi.index') }}"
                       class="group flex flex-col items-center gap-3 p-5 rounded-2xl bg-linear-to-br from-sky-50 to-blue-50 ring-1 ring-sky-200/50 hover:ring-sky-300/60 hover:shadow-lg hover:shadow-sky-200/30 transition-all duration-300">
                        <div class="h-12 w-12 rounded-2xl bg-linear-to-br from-sky-400 to-blue-500 flex items-center justify-center shadow-md shadow-sky-200/50 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <div class="text-center">
                            <p class="text-sm font-semibold text-slate-700">Reservasi</p>
                            <p class="text-[10px] text-slate-400 mt-0.5">{{ \App\Models\Reservasi::where('status', 'pending')->count() }} pending</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.menu.index') }}"
                       class="group flex flex-col items-center gap-3 p-5 rounded-2xl bg-linear-to-br from-orange-50 to-amber-50 ring-1 ring-orange-200/50 hover:ring-orange-300/60 hover:shadow-lg hover:shadow-orange-200/30 transition-all duration-300">
                        <div class="h-12 w-12 rounded-2xl bg-linear-to-br from-orange-400 to-amber-500 flex items-center justify-center shadow-md shadow-orange-200/50 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                        </div>
                        <div class="text-center">
                            <p class="text-sm font-semibold text-slate-700">Kelola Menu</p>
                            <p class="text-[10px] text-slate-400 mt-0.5">{{ \App\Models\Menu::count() }} item</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.menu-specials.index') }}"
                       class="group flex flex-col items-center gap-3 p-5 rounded-2xl bg-linear-to-br from-cyan-50 to-teal-50 ring-1 ring-cyan-200/50 hover:ring-cyan-300/60 hover:shadow-lg hover:shadow-cyan-200/30 transition-all duration-300">
                        <div class="h-12 w-12 rounded-2xl bg-linear-to-br from-cyan-400 to-teal-500 flex items-center justify-center shadow-md shadow-cyan-200/50 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/></svg>
                        </div>
                        <div class="text-center">
                            <p class="text-sm font-semibold text-slate-700">Menu Specials</p>
                            <p class="text-[10px] text-slate-400 mt-0.5">{{ \App\Models\MenuSpecial::count() }} item</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.user.index') }}"
                       class="group flex flex-col items-center gap-3 p-5 rounded-2xl bg-linear-to-br from-emerald-50 to-green-50 ring-1 ring-emerald-200/50 hover:ring-emerald-300/60 hover:shadow-lg hover:shadow-emerald-200/30 transition-all duration-300">
                        <div class="h-12 w-12 rounded-2xl bg-linear-to-br from-emerald-400 to-green-500 flex items-center justify-center shadow-md shadow-emerald-200/50 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/></svg>
                        </div>
                        <div class="text-center">
                            <p class="text-sm font-semibold text-slate-700">Users</p>
                            <p class="text-[10px] text-slate-400 mt-0.5">{{ \App\Models\User::where('role', 'user')->count() }} terdaftar</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.kategori.index') }}"
                       class="group flex flex-col items-center gap-3 p-5 rounded-2xl bg-linear-to-br from-violet-50 to-purple-50 ring-1 ring-violet-200/50 hover:ring-violet-300/60 hover:shadow-lg hover:shadow-violet-200/30 transition-all duration-300">
                        <div class="h-12 w-12 rounded-2xl bg-linear-to-br from-violet-400 to-purple-500 flex items-center justify-center shadow-md shadow-violet-200/50 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        </div>
                        <div class="text-center">
                            <p class="text-sm font-semibold text-slate-700">Kategori</p>
                            <p class="text-[10px] text-slate-400 mt-0.5">{{ \App\Models\KategoriMenu::count() }} kategori</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.testimoni.index') }}"
                       class="group flex flex-col items-center gap-3 p-5 rounded-2xl bg-linear-to-br from-teal-50 to-emerald-50 ring-1 ring-teal-200/50 hover:ring-teal-300/60 hover:shadow-lg hover:shadow-teal-200/30 transition-all duration-300">
                        <div class="h-12 w-12 rounded-2xl bg-linear-to-br from-teal-400 to-emerald-500 flex items-center justify-center shadow-md shadow-teal-200/50 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        </div>
                        <div class="text-center">
                            <p class="text-sm font-semibold text-slate-700">Testimoni</p>
                            <p class="text-[10px] text-slate-400 mt-0.5">{{ \App\Models\Testimoni::count() }} ulasan</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.user.index') }}"
                       class="group flex flex-col items-center gap-3 p-5 rounded-2xl bg-linear-to-br from-rose-50 to-red-50 ring-1 ring-rose-200/50 hover:ring-rose-300/60 hover:shadow-lg hover:shadow-rose-200/30 transition-all duration-300">
                        <div class="h-12 w-12 rounded-2xl bg-linear-to-br from-rose-400 to-red-500 flex items-center justify-center shadow-md shadow-rose-200/50 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <div class="text-center">
                            <p class="text-sm font-semibold text-slate-700">Manager</p>
                            <p class="text-[10px] text-slate-400 mt-0.5">{{ \App\Models\User::where('role', 'manager')->count() }} admin</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>

    </div>

    {{-- Welcome Card --}}
    <div class="bg-white rounded-2xl shadow-sm ring-1 ring-slate-200/80 overflow-hidden">
        <div class="px-6 py-5 flex items-start gap-4">
            <div class="h-14 w-14 rounded-2xl bg-linear-to-br from-orange-400 to-amber-600 flex items-center justify-center flex-shrink-0 shadow-lg shadow-orange-200/50">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
            </div>
            <div>
                <h2 class="text-lg font-bold text-slate-800">Selamat Datang di Dashboard SaSimGa!</h2>
                <p class="text-sm text-slate-500 mt-1 leading-relaxed">
                    Anda memiliki akses penuh untuk mengelola sistem. Gunakan menu navigasi di samping untuk mengelola
                    <strong>pesanan</strong>, <strong>reservasi</strong>, <strong>menu</strong>, <strong>pengguna</strong>, dan konten lainnya.
                </p>
            </div>
        </div>
    </div>

</div>
@endsection
