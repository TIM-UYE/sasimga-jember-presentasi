<div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
    {{-- Total Review --}}
    <div class="group flex items-center justify-between rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200/80 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-blue-500/10 hover:ring-blue-200">
        <div>
            <p class="text-xs font-medium uppercase tracking-wider text-slate-400">
                <i class="fas fa-star text-yellow-500 mr-1"></i> Total Review
            </p>
            <p class="mt-1 text-3xl font-bold text-slate-800" data-stat="total_reviews">
                {{ $stats['total_reviews'] }}
            </p>
        </div>
        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-400 to-blue-600 text-white shadow-lg shadow-blue-200/50">
            <i class="fas fa-comments text-lg"></i>
        </div>
    </div>

    {{-- Rata-rata Rating --}}
    <div class="group flex items-center justify-between rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200/80 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-emerald-500/10 hover:ring-emerald-200">
        <div>
            <p class="text-xs font-medium uppercase tracking-wider text-slate-400">
                <i class="fas fa-chart-line text-emerald-500 mr-1"></i> Rata-rata Rating
            </p>
            <p class="mt-1 text-3xl font-bold text-slate-800" data-stat="average_rating">
                {{ $stats['average_rating'] }}
            </p>
        </div>
        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-400 to-emerald-600 text-white shadow-lg shadow-emerald-200/50">
            <i class="fas fa-star text-lg"></i>
        </div>
    </div>

    {{-- Review Positif --}}
    <div class="group flex items-center justify-between rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200/80 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-emerald-500/10 hover:ring-emerald-200">
        <div>
            <p class="text-xs font-medium uppercase tracking-wider text-slate-400">
                <i class="fas fa-smile text-emerald-500 mr-1"></i> Review Positif
            </p>
            <p class="mt-1 text-3xl font-bold text-slate-800">
                {{ $stats['positif'] }}
                <span class="text-sm font-normal text-slate-400">({{ $stats['positif_persen'] }}%)</span>
            </p>
        </div>
        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-green-400 to-green-600 text-white shadow-lg shadow-green-200/50">
            <i class="fas fa-thumbs-up text-lg"></i>
        </div>
    </div>

    {{-- Review Negatif --}}
    <div class="group flex items-center justify-between rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200/80 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-red-500/10 hover:ring-red-200">
        <div>
            <p class="text-xs font-medium uppercase tracking-wider text-slate-400">
                <i class="fas fa-frown text-red-500 mr-1"></i> Review Negatif
            </p>
            <p class="mt-1 text-3xl font-bold text-slate-800">
                {{ $stats['negatif'] }}
                <span class="text-sm font-normal text-slate-400">({{ $stats['negatif_persen'] }}%)</span>
            </p>
        </div>
        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-red-400 to-red-600 text-white shadow-lg shadow-red-200/50">
            <i class="fas fa-thumbs-down text-lg"></i>
        </div>
    </div>
</div>

@if($stats['place_name'])
<div class="flex items-center gap-2 text-xs text-slate-400 mt-1 px-1">
    <i class="fas fa-store"></i>
    <span>Place: <strong>{{ $stats['place_name'] }}</strong></span>
    @if($stats['total_rating'] > 0)
        <span class="ml-2">Overall Rating: <strong>{{ $stats['total_rating'] }}</strong></span>
    @endif
    @if($stats['last_scraped'])
        <span class="ml-auto"><i class="fas fa-clock mr-1"></i>Last Scraped: {{ $stats['last_scraped']->format('d M Y H:i:s') }}</span>
    @endif
</div>
@endif
