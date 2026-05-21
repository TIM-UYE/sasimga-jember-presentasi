@extends('admin.layout.main')

@section('title', 'Prediksi Penjualan - SaSimGa')

@push('style')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.45.2/dist/apexcharts.css">
<style>
    .prediction-card {
        transition: all 0.3s ease;
    }
    .prediction-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.12);
    }
    .status-badge {
        padding: 4px 12px;
        border-radius: 50px;
        font-size: 12px;
        font-weight: 600;
    }
    .status-online {
        background: #d4edda;
        color: #155724;
    }
    .status-offline {
        background: #f8d7da;
        color: #721c24;
    }
    .status-fallback {
        background: #fff3cd;
        color: #856404;
    }
    .warning-row {
        background: #fff3cd !important;
    }
    .progress-bar-confidence {
        height: 6px;
        border-radius: 3px;
    }
    .prediksi-table {
        border-collapse: separate;
        border-spacing: 0 4px;
    }
    .prediksi-table tbody tr {
        transition: all 0.2s ease;
    }
    .prediksi-table tbody tr:hover {
        background: #fff8f0 !important;
    }
    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }
</style>
@endpush

@section('content')
<div class="flex flex-wrap -mx-3">
    <!-- Header -->
    <div class="w-full max-w-full px-3 mb-6">
        <div class="flex flex-wrap items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Prediksi Penjualan (AI)</h1>
                <p class="text-sm text-slate-500 mt-1">Sistem prediksi penjualan berbasis AI untuk perencanaan stok</p>
            </div>
            <div class="flex items-center gap-3">
                <!-- AI Status -->
                <div id="aiStatusBadge" class="status-badge {{ $aiStatus === 'AI Online' ? 'status-online' : 'status-offline' }}">
                    <i class="fas fa-{{ $aiStatus === 'AI Online' ? 'check-circle' : 'exclamation-triangle' }} mr-1"></i>
                    {{ $aiStatus }}
                </div>
                <!-- Run Prediction Button triggers modal -->
                <button onclick="openPredictionModal()"
                    style="background: linear-gradient(135deg, #f97316, #ea580c); color: white;"
                    class="group relative overflow-hidden
                        inline-flex items-center gap-3
                        px-6 py-3 rounded-2xl
                        font-semibold text-sm tracking-wide
                        shadow-lg hover:shadow-2xl
                        transition-all duration-300
                        hover:-translate-y-1 active:scale-95">
                    <div
                        class="absolute inset-0 bg-white/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    </div>
                    <div
                        class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center backdrop-blur-sm">
                        <i class="fas fa-robot text-white text-sm"></i>
                    </div>
                    <span class="relative z-10 text-white">
                        Run Prediction
                    </span>
                    <i
                        class="fas fa-arrow-right text-xs text-white/80 group-hover:translate-x-1 transition-transform duration-300">
                    </i>
                </button>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="w-full max-w-full px-3 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="prediction-card bg-white rounded-xl p-5 shadow-soft-sm border border-slate-100">
                <div class="flex items-center gap-4">
                    <div class="stat-icon bg-orange-100 text-orange-600">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-semibold uppercase tracking-wide">Total Prediksi</p>
                        <p class="text-2xl font-bold text-slate-800">{{ number_format($totalPredicted) }}</p>
                        <p class="text-xs text-slate-500">{{ $month }}/{{ $year }}</p>
                    </div>
                </div>
            </div>
            <div class="prediction-card bg-white rounded-xl p-5 shadow-soft-sm border border-slate-100">
                <div class="flex items-center gap-4">
                    <div class="stat-icon bg-blue-100 text-blue-600">
                        <i class="fas fa-percentage"></i>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-semibold uppercase tracking-wide">Rata-rata Confidence</p>
                        <p class="text-2xl font-bold text-slate-800">{{ number_format($avgConfidence, 0) }}%</p>
                        <p class="text-xs text-slate-500">Akurasi prediksi</p>
                    </div>
                </div>
            </div>
            <div class="prediction-card bg-white rounded-xl p-5 shadow-soft-sm border border-slate-100">
                <div class="flex items-center gap-4">
                    <div class="stat-icon bg-green-100 text-green-600">
                        <i class="fas fa-utensils"></i>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-semibold uppercase tracking-wide">Menu Diprediksi</p>
                        <p class="text-2xl font-bold text-slate-800">{{ $predictions->count() }}</p>
                        <p class="text-xs text-slate-500">Menu aktif</p>
                    </div>
                </div>
            </div>
            <div class="prediction-card bg-white rounded-xl p-5 shadow-soft-sm border border-slate-100">
                <div class="flex items-center gap-4">
                    <div class="stat-icon bg-{{ $warningCount > 0 ? 'red' : 'green' }}-100 text-{{ $warningCount > 0 ? 'red' : 'green' }}-600">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-semibold uppercase tracking-wide">Warning Stok</p>
                        <p class="text-2xl font-bold text-slate-800">{{ $warningCount }}</p>
                        <p class="text-xs text-slate-500">Bahan perlu dibeli</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="w-full max-w-full px-3 mb-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <!-- Top Menu Bar Chart -->
            <div class="bg-white rounded-xl p-5 shadow-soft-sm border border-slate-100">
                <div class="flex items-center justify-between mb-4">
                    <h5 class="text-sm font-bold text-slate-700">Top 10 Menu Terlaris (Prediksi)</h5>
                    <span class="text-xs text-slate-400">{{ $month }}/{{ $year }}</span>
                </div>
                <div id="topMenuChart"></div>
            </div>
            <!-- Confidence Distribution -->
            <div class="bg-white rounded-xl p-5 shadow-soft-sm border border-slate-100">
                <div class="flex items-center justify-between mb-4">
                    <h5 class="text-sm font-bold text-slate-700">Distribusi Confidence</h5>
                    <span class="text-xs text-slate-400">Per menu</span>
                </div>
                <div id="confidenceChart"></div>
            </div>
        </div>
    </div>

    <!-- Predictions Table -->
    <div class="w-full max-w-full px-3 mb-6">
        <div class="bg-white rounded-xl shadow-soft-sm border border-slate-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h5 class="text-sm font-bold text-slate-700">Hasil Prediksi Penjualan</h5>
                <div class="flex items-center gap-2">
                    <form method="GET" action="{{ route('admin.prediksi.index') }}" class="flex items-center gap-2">
                        <select name="month" class="text-sm border border-slate-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-orange-300 focus:border-orange-400">
                            @for($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>{{ DateTime::createFromFormat('!m', $m)->format('F') }}</option>
                            @endfor
                        </select>
                        <select name="year" class="text-sm border border-slate-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-orange-300 focus:border-orange-400">
                            @for($y = 2024; $y <= 2035; $y++)
                                <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                        <button type="submit" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2 rounded-lg text-sm font-medium transition">
                            <i class="fas fa-filter mr-1"></i> Tampilkan
                        </button>
                    </form>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="prediksi-table w-full">
                    <thead>
                        <tr class="text-left text-xs font-semibold text-slate-400 uppercase tracking-wide bg-slate-50">
                            <th class="px-6 py-4">Menu</th>
                            <th class="px-6 py-4 text-center">Predicted Sales</th>
                            <th class="px-6 py-4 text-center">Confidence</th>
                            <th class="px-6 py-4 text-center">AI Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($predictions as $prediction)
                            <tr class="border-b border-slate-50 hover:bg-orange-50/50 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        @if($prediction->menu && $prediction->menu->gambar)
                                            <img src="{{ asset('storage/' . $prediction->menu->gambar) }}"
                                                 alt="{{ $prediction->menu_name }}"
                                                 class="w-10 h-10 rounded-lg object-cover">
                                        @else
                                            <div class="w-10 h-10 rounded-lg bg-orange-100 flex items-center justify-center text-orange-500">
                                                <i class="fas fa-utensils"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <p class="text-sm font-semibold text-slate-700">{{ $prediction->menu_name }}</p>
                                            @if($prediction->menu)
                                                <p class="text-xs text-slate-400">Rp {{ number_format($prediction->menu->harga, 0, ',', '.') }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="text-lg font-bold text-orange-600">{{ number_format($prediction->predicted_sales) }}</span>
                                    <span class="text-xs text-slate-400"> unit</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2 justify-center">
                                        <div class="w-24 bg-slate-200 rounded-full h-2">
                                            <div class="progress-bar-confidence bg-{{ $prediction->confidence >= 80 ? 'green' : ($prediction->confidence >= 50 ? 'orange' : 'red') }}-500"
                                                 style="width: {{ $prediction->confidence }}%"></div>
                                        </div>
                                        <span class="text-xs font-semibold {{ $prediction->confidence >= 80 ? 'text-green-600' : ($prediction->confidence >= 50 ? 'text-orange-500' : 'text-red-500') }}">
                                            {{ $prediction->confidence }}%
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="status-badge {{ $prediction->ai_status === 'AI Online' ? 'status-online' : 'status-offline' }}">
                                        @if($prediction->ai_status === 'AI Online')
                                            <i class="fas fa-check-circle mr-1"></i>
                                        @else
                                            <i class="fas fa-exclamation-triangle mr-1"></i>
                                        @endif
                                        {{ $prediction->ai_status === 'AI Online' ? 'Online' : 'Offline' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center">
                                            <i class="fas fa-chart-bar text-2xl text-slate-300"></i>
                                        </div>
                                        <p class="text-slate-500 font-medium">Belum ada prediksi untuk {{ DateTime::createFromFormat('!m', $month)->format('F') }} {{ $year }}</p>
                                        <p class="text-xs text-slate-400">Klik "Run Prediction" untuk memulai prediksi</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Stock Requirements Table -->
    @if(count($stockRequirements) > 0)
    <div class="w-full max-w-full px-3 mb-6">
        <div class="bg-white rounded-xl shadow-soft-sm border border-slate-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100">
                <h5 class="text-sm font-bold text-slate-700">
                    <i class="fas fa-boxes text-orange-500 mr-2"></i>
                    Kebutuhan Bahan Baku
                </h5>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="text-left text-xs font-semibold text-slate-400 uppercase tracking-wide bg-slate-50">
                            <th class="px-6 py-4">Menu</th>
                            <th class="px-6 py-4">Bahan</th>
                            <th class="px-6 py-4 text-right">Kebutuhan</th>
                            <th class="px-6 py-4 text-right">Tersedia</th>
                            <th class="px-6 py-4 text-right">Kekurangan</th>
                            <th class="px-6 py-4 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stockRequirements as $req)
                            <tr class="border-b border-slate-50 {{ $req['status'] === 'Warning' ? 'warning-row' : '' }}">
                                <td class="px-6 py-3 text-sm text-slate-700">{{ $req['menu_name'] }}</td>
                                <td class="px-6 py-3 text-sm text-slate-600">{{ $req['bahan'] }}</td>
                                <td class="px-6 py-3 text-sm text-right font-medium">
                                    {{ number_format($req['kebutuhan'], 2) }} {{ $req['satuan'] }}
                                </td>
                                <td class="px-6 py-3 text-sm text-right">
                                    {{ number_format($req['tersedia'], 2) }} {{ $req['satuan'] }}
                                </td>
                                <td class="px-6 py-3 text-sm text-right font-bold {{ $req['kekurangan'] > 0 ? 'text-red-600' : 'text-green-600' }}">
                                    @if($req['kekurangan'] > 0)
                                        {{ number_format($req['kekurangan'], 2) }} {{ $req['satuan'] }}
                                    @else
                                        <span class="text-green-600">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-3 text-center">
                                    @if($req['status'] === 'Warning')
                                        <span class="status-badge bg-red-100 text-red-700">
                                            <i class="fas fa-exclamation-circle mr-1"></i> Warning
                                        </span>
                                    @else
                                        <span class="status-badge bg-green-100 text-green-700">
                                            <i class="fas fa-check-circle mr-1"></i> Aman
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- Restock Recommendations Table -->
    @if(count($restockRecommendations) > 0)
    <div class="w-full max-w-full px-3 mb-6">
        <div class="bg-white rounded-xl shadow-soft-sm border border-slate-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h5 class="text-sm font-bold text-slate-700">
                    <i class="fas fa-shopping-cart text-orange-500 mr-2"></i>
                    Rekomendasi Restock Bahan Baku
                </h5>
                <span class="text-xs text-slate-400 bg-slate-100 px-3 py-1 rounded-full">
                    {{ count($restockRecommendations) }} bahan perlu di-restock
                </span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="text-left text-xs font-semibold text-slate-400 uppercase tracking-wide bg-slate-50">
                            <th class="px-6 py-4">Bahan</th>
                            <th class="px-6 py-4 text-right">Total Kebutuhan</th>
                            <th class="px-6 py-4 text-right">Stok Tersedia</th>
                            <th class="px-6 py-4 text-right">Stok Minimum</th>
                            <th class="px-6 py-4 text-right">Kekurangan</th>
                            <th class="px-6 py-4 text-right text-orange-600">Rekomendasi Beli</th>
                            <th class="px-6 py-4 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($restockRecommendations as $rec)
                            @php
                                $needsRestock = $rec['rekomendasi_beli'] > 0;
                                $displayUnit = $rec['display_satuan'] ?? $rec['satuan'];
                            @endphp
                            <tr class="border-b border-slate-50 {{ $needsRestock ? 'bg-red-50/50' : '' }}">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-lg bg-{{ $needsRestock ? 'red' : 'green' }}-100 flex items-center justify-center text-{{ $needsRestock ? 'red' : 'green' }}-500">
                                            <i class="fas fa-{{ $needsRestock ? 'exclamation' : 'check' }}"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-slate-700">{{ $rec['nama_bahan'] }}</p>
                                            <p class="text-xs text-slate-400">
                                                Dibutuhkan oleh {{ count($rec['menus']) }} menu
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right text-sm font-medium">
                                    {{ number_format($rec['total_kebutuhan_display'], 2) }}
                                    <span class="text-xs text-slate-400">{{ $displayUnit }}</span>
                                </td>
                                <td class="px-6 py-4 text-right text-sm">
                                    <span class="{{ $rec['total_tersedia'] < $rec['total_kebutuhan'] ? 'text-red-600 font-semibold' : 'text-green-600' }}">
                                        {{ number_format($rec['total_tersedia'], 2) }}
                                    </span>
                                    <span class="text-xs text-slate-400">{{ $displayUnit }}</span>
                                </td>
                                <td class="px-6 py-4 text-right text-sm text-slate-500">
                                    {{ number_format($rec['stok_minimum'], 2) }}
                                    <span class="text-xs text-slate-400">{{ $displayUnit }}</span>
                                </td>
                                <td class="px-6 py-4 text-right text-sm">
                                    @if($rec['total_kekurangan'] > 0)
                                        <span class="text-red-600 font-bold">
                                            {{ number_format($rec['total_kekurangan_display'], 2) }}
                                            <span class="text-xs">{{ $displayUnit }}</span>
                                        </span>
                                    @else
                                        <span class="text-green-600">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    @if($needsRestock)
                                        <div class="inline-block bg-orange-50 border border-orange-200 rounded-lg px-3 py-2">
                                            <span class="text-lg font-bold text-orange-600">{{ number_format($rec['rekomendasi_beli_display'], 2) }}</span>
                                            <span class="text-xs text-orange-500 ml-1">{{ $displayUnit }}</span>
                                        </div>
                                    @else
                                        <span class="text-green-600 text-sm font-medium">Stok cukup</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($needsRestock)
                                        <span class="status-badge bg-red-100 text-red-700">
                                            <i class="fas fa-exclamation-circle mr-1"></i> Perlu Restock
                                        </span>
                                    @else
                                        <span class="status-badge bg-green-100 text-green-700">
                                            <i class="fas fa-check-circle mr-1"></i> Aman
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @if($needsRestock && count($rec['menus']) > 0)
                            <tr class="bg-slate-50/50">
                                <td colspan="7" class="px-6 py-2">
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($rec['menus'] as $menuRec)
                                            <span class="text-xs bg-white border border-slate-200 rounded-full px-3 py-1 text-slate-600">
                                                <i class="fas fa-utensils mr-1 text-orange-400"></i>
                                                {{ $menuRec['menu_name'] }}:
                                                <strong>{{ number_format($menuRec['predicted_sales']) }}</strong> unit
                                                ({{ number_format($menuRec['kebutuhan_display'], 2) }} {{ $menuRec['satuan_display'] ?? $displayUnit }})
                                            </span>
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

</div>

<!-- Run Prediction Modal -->
<div id="predictionModal" class="fixed inset-0 z-[9999] hidden overflow-y-auto" style="background: rgba(0,0,0,0.5); backdrop-filter: blur(4px);">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-8 relative">
            <button onclick="closePredictionModal()" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 transition">
                <i class="fas fa-times text-xl"></i>
            </button>
            <div class="text-center mb-6">
                <div class="w-16 h-16 rounded-full bg-orange-100 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-robot text-3xl text-orange-500"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-800">Jalankan Prediksi AI</h3>
                <p class="text-sm text-slate-500 mt-2">Pilih bulan dan tahun untuk prediksi penjualan</p>
            </div>
            <form action="{{ route('admin.prediksi.run') }}" method="POST">
                @csrf
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Bulan</label>
                        <select name="month" required
                                class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-orange-300 focus:border-orange-400">
                            @for($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ $m == now()->month ? 'selected' : '' }}>
                                    {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Tahun</label>
                        <select name="year" required
                                class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-orange-300 focus:border-orange-400">
                            @for($y = 2024; $y <= 2035; $y++)
                                <option value="{{ $y }}" {{ $y == now()->year ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <button type="submit"
                    style="background:#f97316 !important; color:white !important;"
                    class="w-full inline-flex items-center justify-center gap-2
                        py-3 px-6 rounded-xl shadow-md">

                    <i class="fas fa-play"></i>

                    <span>
                        Jalankan Prediksi
                    </span>
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('script')
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.45.2/dist/apexcharts.min.js"></script>
<script>
    // Top Menu Chart
    const topMenuChart = new ApexCharts(document.querySelector("#topMenuChart"), {
        chart: {
            type: 'bar',
            height: 320,
            toolbar: { show: false },
            fontFamily: 'Open Sans, sans-serif',
        },
        series: [{
            name: 'Predicted Sales',
            data: {!! json_encode($topMenus->pluck('predicted_sales')->map(fn($v) => (int) $v)) !!}
        }],
        xaxis: {
            categories: {!! json_encode($topMenus->pluck('menu_name')) !!},
            labels: {
                style: { fontSize: '10px', colors: '#64748b' },
                rotate: -45,
                maxHeight: 120,
            }
        },
        yaxis: {
            labels: {
                formatter: function(val) { return val.toLocaleString(); }
            }
        },
        colors: ['#f97316'],
        plotOptions: {
            bar: {
                borderRadius: 6,
                columnWidth: '60%',
            }
        },
        tooltip: {
            y: {
                formatter: function(val) { return val.toLocaleString() + ' unit'; }
            }
        },
        grid: {
            borderColor: '#f1f5f9',
            strokeDashArray: 4,
        }
    });
    topMenuChart.render();

    // Confidence Chart
    const confidenceLabels = {!! json_encode($predictions->take(10)->pluck('menu_name')->map(fn($n) => Str::limit($n, 20))) !!};
    const confidenceData = {!! json_encode($predictions->take(10)->pluck('confidence')->map(fn($v) => (int) $v)) !!};

    const confidenceChart = new ApexCharts(document.querySelector("#confidenceChart"), {
        chart: {
            type: 'radialBar',
            height: 320,
            fontFamily: 'Open Sans, sans-serif',
        },
        series: confidenceData,
        plotOptions: {
            radialBar: {
                track: { background: '#e2e8f0' },
                dataLabels: {
                    name: { fontSize: '10px', color: '#64748b', offsetY: 0 },
                    value: { fontSize: '14px', fontWeight: 700, color: '#1e293b', formatter: function(v) { return v + '%'; } }
                },
                hollow: { size: '50%' },
            }
        },
        labels: confidenceLabels,
        colors: ['#f97316', '#fb923c', '#fdba74', '#fed7aa', '#ffedd5', '#f97316', '#fb923c', '#fdba74', '#fed7aa', '#ffedd5'],
        stroke: { lineCap: 'round' },
    });
    confidenceChart.render();

    // Modal functions
    function openPredictionModal() {
        document.getElementById('predictionModal').classList.remove('hidden');
    }
    function closePredictionModal() {
        document.getElementById('predictionModal').classList.add('hidden');
    }

    // Close modal on backdrop click
    document.getElementById('predictionModal')?.addEventListener('click', function(e) {
        if (e.target === this) closePredictionModal();
    });

    // Check AI status periodically (every 30 seconds)
    function checkAiStatus() {
        fetch('{{ route("admin.prediksi.ai-status") }}')
            .then(r => r.json())
            .then(data => {
                const badge = document.getElementById('aiStatusBadge');
                if (data.is_online) {
                    badge.className = 'status-badge status-online';
                    badge.innerHTML = '<i class="fas fa-check-circle mr-1"></i> AI Online';
                } else {
                    badge.className = 'status-badge status-offline';
                    badge.innerHTML = '<i class="fas fa-exclamation-triangle mr-1"></i> AI Offline';
                }
            });
    }
    setInterval(checkAiStatus, 30000);
</script>
@endpush
