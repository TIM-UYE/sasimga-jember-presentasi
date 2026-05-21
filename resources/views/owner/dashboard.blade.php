@extends('owner.layout.main')

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-slate-800 tracking-tight">Owner Dashboard</h1>
            <p class="text-slate-500 text-sm mt-1">Selamat datang kembali, <span class="font-semibold text-orange-600">{{ Auth::user()->nama }}</span>! Berikut ringkasan bisnis Anda.</p>
        </div>
        <div class="flex items-center gap-2 text-xs text-slate-400">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            <span>{{ now()->isoFormat('dddd, D MMMM YYYY') }}</span>
            <span class="text-slate-300">|</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>{{ now()->format('H:i') }} WIB</span>
        </div>
    </div>

    {{-- STATS CARDS ROW 1 --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 xl:grid-cols-6 gap-4">
        <div class="group relative bg-white rounded-2xl p-5 shadow-sm ring-1 ring-slate-200/80 hover:shadow-lg hover:shadow-emerald-500/10 hover:ring-emerald-200 transition-all duration-300 stat-card">
            <div class="flex items-center justify-between mb-4">
                <div class="h-11 w-11 rounded-2xl bg-linear-to-br from-emerald-400 to-emerald-600 flex items-center justify-center shadow-lg shadow-emerald-200/50 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="text-[10px] font-medium text-emerald-500 bg-emerald-50 px-2 py-1 rounded-lg">Total</span>
            </div>
            <p class="text-xs font-medium text-slate-400 uppercase tracking-wider mb-0.5">Total Revenue</p>
            <p class="text-xl font-bold text-slate-800">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
            <div class="mt-3 h-1 w-full rounded-full bg-emerald-100 overflow-hidden">
                <div class="h-full w-full rounded-full bg-linear-to-r from-emerald-400 to-emerald-600"></div>
            </div>
        </div>

        <div class="group relative bg-white rounded-2xl p-5 shadow-sm ring-1 ring-slate-200/80 hover:shadow-lg hover:shadow-blue-500/10 hover:ring-blue-200 transition-all duration-300 stat-card">
            <div class="flex items-center justify-between mb-4">
                <div class="h-11 w-11 rounded-2xl bg-linear-to-br from-blue-400 to-blue-600 flex items-center justify-center shadow-lg shadow-blue-200/50 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                </div>
                <span class="text-[10px] font-medium text-blue-500 bg-blue-50 px-2 py-1 rounded-lg">Total</span>
            </div>
            <p class="text-xs font-medium text-slate-400 uppercase tracking-wider mb-0.5">Transaksi</p>
            <p class="text-2xl font-bold text-slate-800">{{ $totalTransactions }}</p>
            <div class="mt-3 h-1 w-full rounded-full bg-blue-100 overflow-hidden">
                <div class="h-full w-3/4 rounded-full bg-linear-to-r from-blue-400 to-blue-600"></div>
            </div>
        </div>

        <div class="group relative bg-white rounded-2xl p-5 shadow-sm ring-1 ring-slate-200/80 hover:shadow-lg hover:shadow-purple-500/10 hover:ring-purple-200 transition-all duration-300 stat-card">
            <div class="flex items-center justify-between mb-4">
                <div class="h-11 w-11 rounded-2xl bg-linear-to-br from-purple-400 to-purple-600 flex items-center justify-center shadow-lg shadow-purple-200/50 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <span class="text-[10px] font-medium text-purple-500 bg-purple-50 px-2 py-1 rounded-lg">Total</span>
            </div>
            <p class="text-xs font-medium text-slate-400 uppercase tracking-wider mb-0.5">Reservasi</p>
            <p class="text-2xl font-bold text-slate-800">{{ $totalReservations }}</p>
            <div class="mt-3 h-1 w-full rounded-full bg-purple-100 overflow-hidden">
                <div class="h-full w-1/2 rounded-full bg-linear-to-r from-purple-400 to-purple-600"></div>
            </div>
        </div>

        <div class="group relative bg-white rounded-2xl p-5 shadow-sm ring-1 ring-slate-200/80 hover:shadow-lg hover:shadow-amber-500/10 hover:ring-amber-200 transition-all duration-300 stat-card">
            <div class="flex items-center justify-between mb-4">
                <div class="h-11 w-11 rounded-2xl bg-linear-to-br from-amber-400 to-amber-600 flex items-center justify-center shadow-lg shadow-amber-200/50 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <span class="text-[10px] font-medium text-amber-500 bg-amber-50 px-2 py-1 rounded-lg">Total</span>
            </div>
            <p class="text-xs font-medium text-slate-400 uppercase tracking-wider mb-0.5">Pelanggan</p>
            <p class="text-2xl font-bold text-slate-800">{{ $totalCustomers }}</p>
            <div class="mt-3 h-1 w-full rounded-full bg-amber-100 overflow-hidden">
                <div class="h-full w-2/3 rounded-full bg-linear-to-r from-amber-400 to-amber-600"></div>
            </div>
        </div>

        <div class="group relative bg-white rounded-2xl p-5 shadow-sm ring-1 ring-slate-200/80 hover:shadow-lg hover:shadow-rose-500/10 hover:ring-rose-200 transition-all duration-300 stat-card">
            <div class="flex items-center justify-between mb-4">
                <div class="h-11 w-11 rounded-2xl bg-linear-to-br from-rose-400 to-rose-600 flex items-center justify-center shadow-lg shadow-rose-200/50 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
                <span class="text-[10px] font-medium text-rose-500 bg-rose-50 px-2 py-1 rounded-lg">Hari Ini</span>
            </div>
            <p class="text-xs font-medium text-slate-400 uppercase tracking-wider mb-0.5">Transaksi Hari Ini</p>
            <p class="text-2xl font-bold text-slate-800">{{ $transactionsToday }}</p>
            <div class="mt-3 h-1 w-full rounded-full bg-rose-100 overflow-hidden">
                <div class="h-full w-1/3 rounded-full bg-linear-to-r from-rose-400 to-rose-600"></div>
            </div>
        </div>

        <div class="group relative bg-white rounded-2xl p-5 shadow-sm ring-1 ring-slate-200/80 hover:shadow-lg hover:shadow-cyan-500/10 hover:ring-cyan-200 transition-all duration-300 stat-card">
            <div class="flex items-center justify-between mb-4">
                <div class="h-11 w-11 rounded-2xl bg-linear-to-br from-cyan-400 to-cyan-600 flex items-center justify-center shadow-lg shadow-cyan-200/50 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                </div>
                <span class="text-[10px] font-medium text-cyan-500 bg-cyan-50 px-2 py-1 rounded-lg">Menu</span>
            </div>
            <p class="text-xs font-medium text-slate-400 uppercase tracking-wider mb-0.5">Total Menu</p>
            <p class="text-2xl font-bold text-slate-800">{{ $totalMenus }}</p>
            <div class="mt-3 h-1 w-full rounded-full bg-cyan-100 overflow-hidden">
                <div class="h-full w-4/5 rounded-full bg-linear-to-r from-cyan-400 to-cyan-600"></div>
            </div>
        </div>
    </div>

    {{-- REVENUE SUMMARY STRIP --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl p-5 shadow-sm ring-1 ring-slate-200/80 flex items-center gap-4">
            <div class="h-12 w-12 rounded-2xl bg-linear-to-br from-emerald-400 to-emerald-600 flex items-center justify-center shadow-lg shadow-emerald-200/50 flex-shrink-0">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Pendapatan Hari Ini</p>
                <p class="text-xl font-bold text-slate-800">Rp {{ number_format($revenueToday, 0, ',', '.') }}</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm ring-1 ring-slate-200/80 flex items-center gap-4">
            <div class="h-12 w-12 rounded-2xl bg-linear-to-br from-blue-400 to-blue-600 flex items-center justify-center shadow-lg shadow-blue-200/50 flex-shrink-0">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Pendapatan Bulan Ini</p>
                <p class="text-xl font-bold text-slate-800">Rp {{ number_format($revenueMonth, 0, ',', '.') }}</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm ring-1 ring-slate-200/80 flex items-center gap-4">
            <div class="h-12 w-12 rounded-2xl bg-linear-to-br from-purple-400 to-purple-600 flex items-center justify-center shadow-lg shadow-purple-200/50 flex-shrink-0">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <div>
                <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">Reservasi Pending</p>
                <p class="text-xl font-bold text-slate-800">{{ $pendingReservations }}</p>
            </div>
        </div>
    </div>

    {{-- CHARTS ROW 1: Daily & Weekly --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl p-6 shadow-sm ring-1 ring-slate-200/80 stat-card">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-bold text-slate-800">Penjualan Harian</h3>
                    <p class="text-xs text-slate-400">7 hari terakhir</p>
                </div>
                <div class="h-10 w-10 rounded-xl bg-linear-to-br from-emerald-400 to-emerald-600 flex items-center justify-center shadow-md shadow-emerald-200/50">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
            </div>
            <div class="chart-container">
                <canvas id="dailyChart"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm ring-1 ring-slate-200/80 stat-card">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-bold text-slate-800">Penjualan Mingguan</h3>
                    <p class="text-xs text-slate-400">4 minggu terakhir</p>
                </div>
                <div class="h-10 w-10 rounded-xl bg-linear-to-br from-blue-400 to-blue-600 flex items-center justify-center shadow-md shadow-blue-200/50">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
            </div>
            <div class="chart-container">
                <canvas id="weeklyChart"></canvas>
            </div>
        </div>
    </div>

    {{-- CHARTS ROW 2: Monthly & Yearly --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl p-6 shadow-sm ring-1 ring-slate-200/80 stat-card">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-bold text-slate-800">Penjualan Bulanan</h3>
                    <p class="text-xs text-slate-400">6 bulan terakhir</p>
                </div>
                <div class="h-10 w-10 rounded-xl bg-linear-to-br from-purple-400 to-purple-600 flex items-center justify-center shadow-md shadow-purple-200/50">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
            </div>
            <div class="chart-container">
                <canvas id="monthlyChart"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm ring-1 ring-slate-200/80 stat-card">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-bold text-slate-800">Penjualan Tahunan</h3>
                    <p class="text-xs text-slate-400">5 tahun terakhir</p>
                </div>
                <div class="h-10 w-10 rounded-xl bg-linear-to-br from-amber-400 to-amber-600 flex items-center justify-center shadow-md shadow-amber-200/50">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
            </div>
            <div class="chart-container">
                <canvas id="yearlyChart"></canvas>
            </div>
        </div>
    </div>

    {{-- REVENUE TREND FULL YEAR --}}
    <div class="bg-white rounded-2xl p-6 shadow-sm ring-1 ring-slate-200/80 stat-card">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-bold text-slate-800">Trend Pendapatan {{ now()->year }}</h3>
                <p class="text-xs text-slate-400">Perbandingan pendapatan per bulan sepanjang tahun</p>
            </div>
            <div class="h-10 w-10 rounded-xl bg-linear-to-br from-rose-400 to-rose-600 flex items-center justify-center shadow-md shadow-rose-200/50">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            </div>
        </div>
        <div class="chart-container">
            <canvas id="revenueTrendChart"></canvas>
        </div>
    </div>

    {{-- BOTTOM: Top Selling & Recent Orders --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl shadow-sm ring-1 ring-slate-200/80 overflow-hidden stat-card">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-2">
                <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                <span class="text-sm font-semibold text-slate-700">Menu Terlaris</span>
            </div>
            <div class="p-6">
                @if($topSellingMenus->count() > 0)
                    <div class="space-y-4">
                        @foreach($topSellingMenus as $index => $item)
                            <div class="flex items-center gap-4">
                                <div class="h-8 w-8 rounded-lg bg-linear-to-br from-amber-400 to-amber-600 flex items-center justify-center text-white text-xs font-bold flex-shrink-0 shadow-sm">
                                    {{ $index + 1 }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-slate-700 truncate">{{ $item->menu->nama_menu ?? 'Menu #'.$item->menu_id }}</p>
                                    <div class="flex items-center gap-3 mt-0.5">
                                        <span class="text-xs text-slate-400">{{ $item->total_qty }} terjual</span>
                                        <span class="text-xs font-medium text-emerald-600">Rp {{ number_format($item->total_revenue, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                                <div class="w-20 h-1.5 rounded-full bg-slate-100 overflow-hidden">
                                    @php
                                        $maxQty = $topSellingMenus->max('total_qty');
                                        $width = $maxQty > 0 ? ($item->total_qty / $maxQty) * 100 : 0;
                                    @endphp
                                    <div class="h-full rounded-full bg-linear-to-r from-amber-400 to-amber-600" style="width: {{ $width }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 text-slate-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                        <p class="text-sm text-slate-500">Belum ada data penjualan menu</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm ring-1 ring-slate-200/80 overflow-hidden stat-card">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    <span class="text-sm font-semibold text-slate-700">Pesanan Terbaru</span>
                </div>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($recentOrders as $order)
                    <div class="flex items-center gap-4 px-6 py-4 hover:bg-slate-50/60 transition-all duration-200">
                        <div class="h-9 w-9 rounded-xl bg-linear-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white text-sm font-bold flex-shrink-0 shadow-sm">
                            {{ strtoupper(substr($order->kode_order, -3)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-slate-800 truncate">{{ $order->nama_pelanggan }}</p>
                            <p class="text-xs text-slate-400 truncate">{{ $order->kode_order }} &bull; Rp {{ number_format($order->total_bayar, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            @php
                                $statusBadge = [
                                    'pending' => ['bg-amber-100 text-amber-700', 'Pending'],
                                    'diproses' => ['bg-blue-100 text-blue-700', 'Diproses'],
                                    'siap_diambil' => ['bg-cyan-100 text-cyan-700', 'Siap Diambil'],
                                    'diantar' => ['bg-purple-100 text-purple-700', 'Diantar'],
                                    'selesai' => ['bg-emerald-100 text-emerald-700', 'Selesai'],
                                    'dibatalkan' => ['bg-red-100 text-red-700', 'Dibatalkan'],
                                ];
                                $badge = $statusBadge[$order->status] ?? ['bg-slate-100 text-slate-700', $order->status];
                            @endphp
                            <span class="text-[10px] font-semibold px-2.5 py-1.5 rounded-lg {{ $badge[0] }}">{{ $badge[1] }}</span>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center">
                        <svg class="w-12 h-12 text-slate-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        <p class="text-sm text-slate-500">Belum ada pesanan terbaru</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- WELCOME CARD --}}
    <div class="bg-white rounded-2xl shadow-sm ring-1 ring-slate-200/80 overflow-hidden stat-card">
        <div class="px-6 py-5 flex items-start gap-4">
            <div class="h-14 w-14 rounded-2xl bg-linear-to-br from-orange-400 to-amber-600 flex items-center justify-center flex-shrink-0 shadow-lg shadow-orange-200/50">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
            </div>
            <div>
                <h2 class="text-lg font-bold text-slate-800">Selamat Datang di Owner Dashboard SaSimGa!</h2>
                <p class="text-sm text-slate-500 mt-1 leading-relaxed">
                    Pantau perkembangan bisnis Anda secara real-time. Dashboard ini menampilkan data <strong class="text-slate-700">read-only</strong> untuk memonitor penjualan, pendapatan, reservasi, dan menu terlaris.
                </p>
            </div>
        </div>
    </div>

</div>
@endsection

@push('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const gridColor = 'rgba(226, 232, 240, 0.8)';
    const textColor = '#94a3b8';

    Chart.defaults.color = textColor;
    Chart.defaults.borderColor = gridColor;

    @if($dailySales->count() > 0)
    new Chart(document.getElementById('dailyChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode($dailySales->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->isoFormat('dddd'))) !!},
            datasets: [{
                label: 'Pendapatan',
                data: {!! json_encode($dailySales->pluck('total')->map(fn($v) => (int)$v)) !!},
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.08)',
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#10b981',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7,
                borderWidth: 2.5
            }, {
                label: 'Transaksi',
                data: {!! json_encode($dailySales->pluck('count')) !!},
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.08)',
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#3b82f6',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7,
                borderWidth: 2.5,
                yAxisID: 'y1'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { intersect: false, mode: 'index' },
            plugins: {
                legend: {
                    labels: { boxWidth: 12, padding: 15, font: { size: 11, weight: '500' } }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: gridColor },
                    ticks: {
                        callback: function(value) { return 'Rp' + value.toLocaleString('id-ID'); },
                        font: { size: 10 }
                    }
                },
                y1: {
                    beginAtZero: true,
                    position: 'right',
                    grid: { display: false },
                    ticks: { font: { size: 10 } }
                },
                x: {
                    grid: { color: gridColor },
                    ticks: { font: { size: 10 } }
                }
            }
        }
    });
    @endif

    @if($weeklySales->count() > 0)
    new Chart(document.getElementById('weeklyChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($weeklySales->pluck('week')->map(fn($w) => 'Week ' . substr($w, -2))) !!},
            datasets: [{
                label: 'Pendapatan',
                data: {!! json_encode($weeklySales->pluck('total')->map(fn($v) => (int)$v)) !!},
                backgroundColor: 'rgba(59, 130, 246, 0.75)',
                borderColor: '#3b82f6',
                borderWidth: 1.5,
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: gridColor },
                    ticks: {
                        callback: function(value) { return 'Rp' + value.toLocaleString('id-ID'); },
                        font: { size: 10 }
                    }
                },
                x: {
                    grid: { color: gridColor },
                    ticks: { font: { size: 10 } }
                }
            }
        }
    });
    @endif

    @if($monthlySales->count() > 0)
    new Chart(document.getElementById('monthlyChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode($monthlySales->pluck('month')->map(fn($m) => \Carbon\Carbon::parse($m.'-01')->isoFormat('MMM YYYY'))) !!},
            datasets: [{
                label: 'Pendapatan',
                data: {!! json_encode($monthlySales->pluck('total')->map(fn($v) => (int)$v)) !!},
                borderColor: '#8b5cf6',
                backgroundColor: 'rgba(139, 92, 246, 0.08)',
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#8b5cf6',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7,
                borderWidth: 2.5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: gridColor },
                    ticks: {
                        callback: function(value) { return 'Rp' + value.toLocaleString('id-ID'); },
                        font: { size: 10 }
                    }
                },
                x: {
                    grid: { color: gridColor },
                    ticks: { font: { size: 10 } }
                }
            }
        }
    });
    @endif

    @if($yearlySales->count() > 0)
    new Chart(document.getElementById('yearlyChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($yearlySales->pluck('year')->map(fn($y) => (string)$y)) !!},
            datasets: [{
                label: 'Pendapatan',
                data: {!! json_encode($yearlySales->pluck('total')->map(fn($v) => (int)$v)) !!},
                backgroundColor: 'rgba(245, 158, 11, 0.75)',
                borderColor: '#f59e0b',
                borderWidth: 1.5,
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: gridColor },
                    ticks: {
                        callback: function(value) { return 'Rp' + value.toLocaleString('id-ID'); },
                        font: { size: 10 }
                    }
                },
                x: {
                    grid: { color: gridColor },
                    ticks: { font: { size: 10, weight: '600' } }
                }
            }
        }
    });
    @endif

    // Monthly Revenue Trend
    new Chart(document.getElementById('revenueTrendChart'), {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
            datasets: [{
                label: 'Pendapatan {{ now()->year }}',
                data: {!! json_encode(array_values($monthlyRevenueData)) !!},
                borderColor: '#f43f5e',
                backgroundColor: 'rgba(244, 63, 94, 0.08)',
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#f43f5e',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
                borderWidth: 2.5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: gridColor },
                    ticks: {
                        callback: function(value) { return 'Rp' + value.toLocaleString('id-ID'); },
                        font: { size: 10 }
                    }
                },
                x: {
                    grid: { color: gridColor },
                    ticks: { font: { size: 10, weight: '500' } }
                }
            }
        }
    });
});
</script>
@endpush
