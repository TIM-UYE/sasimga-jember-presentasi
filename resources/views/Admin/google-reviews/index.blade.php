@extends('admin.layout.main')

@push('style')
<style>
    .star-rating .fa-star,
    .star-rating .fa-star-half-alt,
    .star-rating .fa-star-half-stroke {
        color: #f59e0b;
    }
    .star-rating .fa-star-o,
    .star-rating .fa-star.checked {
        color: #f59e0b;
    }
    .star-rating .fa-star.text-slate-300 {
        color: #cbd5e1;
    }
    .rating-bar {
        transition: width 0.6s ease;
    }
    .sync-spinner {
        animation: spin 1s linear infinite;
    }
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    .review-card {
        transition: all 0.2s ease;
    }
    .review-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px -5px rgba(0,0,0,0.1), 0 4px 10px -5px rgba(0,0,0,0.05);
    }
    .sentiment-badge {
        font-size: 0.65rem;
        padding: 0.2rem 0.6rem;
        border-radius: 9999px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }
    .rating-chart-bar {
        height: 8px;
        border-radius: 4px;
        transition: width 0.8s ease;
    }
    .pagination .page-link {
        padding: 0.375rem 0.75rem;
        border-radius: 0.5rem;
        color: #475569;
        font-size: 0.875rem;
    }
    .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, #f97316, #ea580c);
        border-color: transparent;
        color: white;
    }
    .pagination .page-item.disabled .page-link {
        color: #94a3b8;
        pointer-events: none;
    }
</style>
@endpush

@section('content')
<div class="space-y-6">
    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="mb-4 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
        </div>
    @endif

    {{-- Header --}}
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-800 md:text-3xl">
                <i class="fab fa-google mr-2 text-red-500"></i>Google Reviews
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                Kelola dan pantau ulasan Google Maps secara otomatis.
                @if($stats['last_scraped'])
                    Terakhir scrape: <strong>{{ $stats['last_scraped']->format('d M Y H:i:s') }}</strong>
                @else
                    <strong>Belum pernah di-scrape</strong>
                @endif
            </p>
        </div>
        <div class="flex flex-wrap gap-2">
            {{-- Tombol Scrape All: Ambil SEMUA rating & review --}}
            <button onclick="triggerScrapeAll()" id="scrapeAllBtn"
                class="group inline-flex items-center rounded-xl bg-linear-to-r from-orange-400 to-orange-600 px-5 py-2.5 text-sm font-bold text-orange-600 shadow-md shadow-orange-200/50 transition-all hover:from-orange-500 hover:to-orange-700 hover:shadow-lg disabled:opacity-60 disabled:cursor-not-allowed">

                <div class="flex items-center justify-center h-8 w-8 rounded-lg bg-white text-orange-600 mr-2">
                    <i id="scrapeAllIcon" class="fas fa-database text-xs"></i>
                </div>

                <span id="scrapeAllText">Scraping (Ambil Semua Rating)</span>
            </button>

            {{-- Tombol Update Data: Hanya review TERBARU --}}
            <button onclick="triggerUpdateData()" id="updateDataBtn"
                class="group inline-flex items-center rounded-xl bg-linear-to-r from-orange-400 to-orange-600 px-5 py-2.5 text-sm font-bold text-orange-600 shadow-md shadow-orange-200/50 transition-all hover:from-orange-500 hover:to-orange-700 hover:shadow-lg disabled:opacity-60 disabled:cursor-not-allowed">

                <div class="flex items-center justify-center h-8 w-8 rounded-lg bg-white text-orange-600 mr-2">
                    <i id="updateDataIcon" class="fas fa-cloud-upload-alt text-xs"></i>
                </div>

                <span id="updateDataText">Update Data (Update Review Terbaru Saja)</span>
            </button>

            <button onclick="refreshStats()"
                class="group inline-flex items-center rounded-xl bg-linear-to-r from-orange-400 to-orange-600 px-5 py-2.5 text-sm font-bold text-orange-600 shadow-md shadow-orange-200/50 transition-all hover:from-orange-500 hover:to-orange-700 hover:shadow-lg">

                <div class="flex items-center justify-center h-8 w-8 rounded-lg bg-white text-orange-600 mr-2">
                    <i class="fas fa-refresh text-xs"></i>
                </div>

                Refresh Data Review
            </button>
        </div>
    </div>

    {{-- Sync Progress --}}
    <div id="syncProgress" class="hidden rounded-xl border border-orange-200 bg-orange-50 px-5 py-4">
        <div class="flex items-center gap-3">
            <i class="fas fa-spinner fa-spin text-orange-500 text-lg"></i>
            <div>
                <p class="font-semibold text-orange-700 text-sm">Sedang menyinkronkan data...</p>
                <p id="syncProgressText" class="text-xs text-orange-600 mt-0.5">Memulai scraping Google Maps</p>
            </div>
        </div>
        <div class="mt-3 h-2 w-full rounded-full bg-orange-200 overflow-hidden">
            <div id="syncProgressBar" class="h-full rounded-full bg-gradient-to-r from-orange-400 to-orange-600 transition-all duration-500" style="width: 0%"></div>
        </div>
    </div>

    {{-- Sync Result --}}
    <div id="syncResult" class="hidden"></div>

    {{-- Stats Cards --}}
    <div id="statsContainer">
        @include('admin.google-reviews.partials.stats-cards', ['stats' => $stats, 'ratingDistribution' => $ratingDistribution])
    </div>

    {{-- Recent Reviews & Rating Distribution --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        {{-- Recent Reviews --}}
        <div class="lg:col-span-2 overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200/80">
            <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
                <div class="flex items-center gap-2">
                    <i class="fas fa-clock text-slate-400"></i>
                    <span class="text-sm font-semibold text-slate-700">Review Terbaru</span>
                </div>
                <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-500">
                    <i class="fas fa-star text-yellow-500 mr-1"></i> {{ $stats['average_rating'] }} avg
                </span>
            </div>
            <div id="recentReviewsContainer" class="divide-y divide-slate-100">
                @forelse($recentReviews as $review)
                    <div class="review-card px-6 py-4">
                        <div class="flex items-start gap-3">
                            @if($review->profile_photo)
                                <img src="{{ $review->profile_photo }}" alt="{{ $review->author_name }}" class="h-10 w-10 rounded-full object-cover ring-1 ring-slate-200">
                            @else
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-orange-100 to-orange-200 text-orange-500">
                                    <i class="fas fa-user"></i>
                                </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-2">
                                    <h6 class="text-sm font-semibold text-slate-700 truncate">{{ $review->author_name }}</h6>
                                    <span class="text-xs text-slate-400 whitespace-nowrap">{{ $review->review_date ? $review->review_date->format('d M Y') : '-' }}</span>
                                </div>
                                <div class="flex items-center gap-2 mt-0.5">
                                    <div class="star-rating text-xs">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $review->rating)
                                                <i class="fas fa-star text-yellow-500"></i>
                                            @elseif($i - 0.5 <= $review->rating)
                                                <i class="fas fa-star-half-alt text-yellow-500"></i>
                                            @else
                                                <i class="far fa-star text-slate-300"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    @if($review->sentiment)
                                        <span class="sentiment-badge
                                            {{ $review->sentiment === 'positif' ? 'bg-emerald-100 text-emerald-700' : '' }}
                                            {{ $review->sentiment === 'negatif' ? 'bg-red-100 text-red-700' : '' }}
                                            {{ $review->sentiment === 'netral' ? 'bg-slate-100 text-slate-600' : '' }}">
                                            <i class="fas
                                                {{ $review->sentiment === 'positif' ? 'fa-smile' : '' }}
                                                {{ $review->sentiment === 'negatif' ? 'fa-frown' : '' }}
                                                {{ $review->sentiment === 'netral' ? 'fa-meh' : '' }}
                                            mr-1"></i>
                                            {{ $review->sentiment }}
                                        </span>
                                    @endif
                                </div>
                                <p class="mt-1 text-sm text-slate-500 line-clamp-2">{{ $review->review_text ?: '(Tidak ada teks review)' }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-16 text-center">
                        <div class="mx-auto max-w-sm text-slate-500">
                            <i class="fab fa-google mb-3 text-4xl text-slate-300"></i>
                            <p class="mb-1 font-semibold text-slate-600">Belum ada review</p>
                            <p class="text-sm">Klik tombol "Sync Sekarang" untuk mengambil review dari Google Maps.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Rating Distribution --}}
        <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200/80">
            <div class="border-b border-slate-100 px-6 py-4">
                <div class="flex items-center gap-2">
                    <i class="fas fa-chart-bar text-slate-400"></i>
                    <span class="text-sm font-semibold text-slate-700">Distribusi Rating</span>
                </div>
            </div>
            <div class="p-6">
                {{-- Rating Circle --}}
                <div class="text-center mb-6">
                    <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-gradient-to-br from-orange-400 to-orange-600 text-white shadow-lg shadow-orange-200/50">
                        <div>
                            <div class="text-3xl font-bold leading-none">{{ $stats['average_rating'] }}</div>
                            <div class="text-xs mt-1 opacity-80">dari 5</div>
                        </div>
                    </div>
                    <div class="mt-3 star-rating text-lg flex justify-center gap-1">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= round($stats['average_rating']))
                                <i class="fas fa-star text-yellow-500"></i>
                            @else
                                <i class="far fa-star text-slate-300"></i>
                            @endif
                        @endfor
                    </div>
                    <p class="mt-2 text-sm text-slate-500">
                        <strong>{{ $stats['total_reviews'] }}</strong> total review
                    </p>
                </div>

                {{-- Rating Bars --}}
                @foreach([5,4,3,2,1] as $star)
                    @php
                        $count = $stats["rating_{$star}"] ?? 0;
                        $pct = $stats['total_reviews'] > 0 ? ($count / $stats['total_reviews']) * 100 : 0;
                    @endphp
                    <div class="flex items-center gap-3 mb-2">
                        <span class="text-sm font-medium text-slate-600 w-12">{{ $star }} ★</span>
                        <div class="flex-1 h-2 bg-slate-100 rounded-full overflow-hidden">
                            <div class="rating-chart-bar bg-gradient-to-r from-yellow-400 to-yellow-500"
                                 style="width: {{ $pct }}%"></div>
                        </div>
                        <span class="text-xs text-slate-400 w-8 text-right">{{ $count }}</span>
                    </div>
                @endforeach

                {{-- Sentiment Summary --}}
                <div class="mt-6 pt-4 border-t border-slate-100">
                    <h6 class="text-xs font-semibold uppercase tracking-wider text-slate-500 mb-3">Sentimen</h6>
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="flex items-center gap-1.5 text-sm text-slate-600">
                                <i class="fas fa-smile text-emerald-500"></i> Positif
                            </span>
                            <span class="text-sm font-semibold text-slate-700">{{ $stats['positif'] }} ({{ $stats['positif_persen'] }}%)</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="flex items-center gap-1.5 text-sm text-slate-600">
                                <i class="fas fa-meh text-slate-400"></i> Netral
                            </span>
                            <span class="text-sm font-semibold text-slate-700">{{ $stats['netral'] }} ({{ $stats['netral_persen'] }}%)</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="flex items-center gap-1.5 text-sm text-slate-600">
                                <i class="fas fa-frown text-red-500"></i> Negatif
                            </span>
                            <span class="text-sm font-semibold text-slate-700">{{ $stats['negatif'] }} ({{ $stats['negatif_persen'] }}%)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Review List with Filters --}}
    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200/80">
        <div class="flex flex-col gap-4 border-b border-slate-100 px-6 py-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-2">
                <i class="fas fa-list text-slate-400"></i>
                <span class="text-sm font-semibold text-slate-700">Daftar Review</span>
                <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-medium text-slate-500">
                    <i class="fas fa-database"></i>{{ $stats['total_reviews'] }} Data
                </span>
            </div>
            <div class="flex flex-wrap gap-2">
                {{-- Filter Sentiment --}}
                <select id="filterSentiment" onchange="applyFilters()"
                        class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-medium text-slate-600 focus:border-orange-400 focus:outline-none focus:ring-1 focus:ring-orange-400">
                    <option value="">Semua Sentimen</option>
                    <option value="positif">Positif</option>
                    <option value="netral">Netral</option>
                    <option value="negatif">Negatif</option>
                </select>

                {{-- Filter Rating --}}
                <select id="filterRating" onchange="applyFilters()"
                        class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-medium text-slate-600 focus:border-orange-400 focus:outline-none focus:ring-1 focus:ring-orange-400">
                    <option value="">Semua Rating</option>
                    <option value="5">★★★★★ (5)</option>
                    <option value="4">★★★★☆ (4)</option>
                    <option value="3">★★★☆☆ (3)</option>
                    <option value="2">★★☆☆☆ (2)</option>
                    <option value="1">★☆☆☆☆ (1)</option>
                </select>

                {{-- Search --}}
                <input type="text" id="filterSearch" placeholder="Cari review..."
                       onkeydown="if(event.key === 'Enter') applyFilters()"
                       class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-medium text-slate-600 placeholder:text-slate-400 focus:border-orange-400 focus:outline-none focus:ring-1 focus:ring-orange-400">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[940px] text-left text-sm text-slate-600">
                <thead>
                    <tr class="bg-slate-50/80">
                        <th class="px-6 py-4 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">Reviewer</th>
                        <th class="px-6 py-4 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">Rating</th>
                        <th class="px-6 py-4 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">Sentimen</th>
                        <th class="px-6 py-4 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">Tanggal</th>
                        <th class="px-6 py-4 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">Scraped</th>
                        <th class="px-6 py-4 text-right text-[11px] font-semibold uppercase tracking-wider text-slate-500">Aksi</th>
                    </tr>
                </thead>
                <tbody id="reviewsTableBody" class="divide-y divide-slate-100">
                    @forelse($reviews as $review)
                        <tr class="group transition-all duration-200 hover:bg-slate-50/60">
                            <td class="px-6 py-4 align-middle">
                                <div class="flex items-center gap-3">
                                    @if($review->profile_photo)
                                        <img src="{{ $review->profile_photo }}" alt="{{ $review->author_name }}" class="h-9 w-9 rounded-full object-cover ring-1 ring-slate-200">
                                    @else
                                        <div class="flex h-9 w-9 items-center justify-center rounded-full bg-orange-100 text-orange-500">
                                            <i class="fas fa-user text-xs"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <h6 class="text-sm font-semibold text-slate-700">{{ $review->author_name }}</h6>
                                        <p class="text-xs text-slate-500 max-w-xs truncate">{{ $review->review_text ?: '(Tidak ada teks)' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 align-middle">
                                <div class="flex items-center gap-1">
                                    <span class="text-yellow-500 text-sm">{{ str_repeat('★', max(1, min(5, (int)$review->rating))) }}</span>
                                    <span class="text-slate-500 text-xs">({{ $review->rating }})</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 align-middle">
                                @if($review->sentiment)
                                    <span class="sentiment-badge
                                        {{ $review->sentiment === 'positif' ? 'bg-emerald-100 text-emerald-700' : '' }}
                                        {{ $review->sentiment === 'negatif' ? 'bg-red-100 text-red-700' : '' }}
                                        {{ $review->sentiment === 'netral' ? 'bg-slate-100 text-slate-600' : '' }}">
                                        {{ $review->sentiment }}
                                    </span>
                                @else
                                    <span class="text-xs text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 align-middle text-xs text-slate-500">
                                {{ $review->review_date ? $review->review_date->format('d M Y') : '-' }}
                            </td>
                            <td class="px-6 py-4 align-middle text-xs text-slate-500">
                                {{ $review->scraped_at ? $review->scraped_at->format('d M Y H:i') : '-' }}
                            </td>
                            <td class="px-6 py-4 align-middle">
                                <div class="flex items-center justify-end gap-2">
                                    <form action="{{ route('admin.google-reviews.destroy', $review->id) }}" method="POST"
                                          class="inline-block" onsubmit="return confirm('Yakin ingin menghapus review ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="inline-flex items-center rounded-lg bg-red-50 px-2.5 py-1.5 text-xs font-semibold text-red-600 transition-all hover:bg-red-100">
                                            <i class="fas fa-trash mr-1"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="mx-auto max-w-sm text-slate-500">
                                    <i class="fab fa-google mb-3 text-3xl text-slate-300"></i>
                                    <p class="mb-1 font-semibold text-slate-600">Belum ada data review</p>
                                    <p class="text-sm">Klik "Sync Sekarang" untuk mengambil review dari Google Maps.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($reviews->hasPages())
            <div class="border-t border-slate-100 px-6 py-4">
                <div class="flex items-center justify-between">
                    <p class="text-xs text-slate-500">
                        Menampilkan {{ $reviews->firstItem() }}-{{ $reviews->lastItem() }} dari {{ $reviews->total() }} review
                    </p>
                    <div class="pagination">
                        {{ $reviews->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('script')
<script>
    let isSyncing = false;

    /**
     * Utility: Show sync progress bar
     */
    function showProgress(message) {
        const progress = document.getElementById('syncProgress');
        const progressText = document.getElementById('syncProgressText');
        const progressBar = document.getElementById('syncProgressBar');
        progress.classList.remove('hidden');
        progressText.textContent = message;
        progressBar.style.width = '10%';
    }

    /**
     * Utility: Show sync result message
     */
    function showSyncResult(type, message) {
        const result = document.getElementById('syncResult');
        result.className = 'mb-4 rounded-xl border px-4 py-3 text-sm ' +
            (type === 'success'
                ? 'border-green-200 bg-green-50 text-green-700'
                : 'border-red-200 bg-red-50 text-red-700');
        result.innerHTML = '<i class="fas fa-' + (type === 'success' ? 'check-circle' : 'exclamation-circle') + ' mr-2"></i>' + message;
        result.classList.remove('hidden');
        setTimeout(() => { result.classList.add('hidden'); }, 10000);
    }

    /**
     * Utility: Reset specific button state
     */
    function resetButton(btnId, iconId, textId, originalText, iconClass) {
        document.getElementById(btnId).disabled = false;
        document.getElementById(iconId).className = iconClass;
        document.getElementById(textId).textContent = originalText;
    }

    /**
     * Utility: Set button to loading state
     */
    function setButtonLoading(btnId, iconId, textId, loadingText) {
        document.getElementById(btnId).disabled = true;
        document.getElementById(iconId).className = 'fas fa-spinner sync-spinner mr-2';
        document.getElementById(textId).textContent = loadingText;
    }

    /**
     * ================================================================
     *  1. SCRAPE ALL - Ambil SEMUA rating & review dari Google Maps
     * ================================================================
     */
    function triggerScrapeAll() {
        if (isSyncing) return;
        isSyncing = true;

        const result = document.getElementById('syncResult');
        result.classList.add('hidden');

        setButtonLoading('scrapeAllBtn', 'scrapeAllIcon', 'scrapeAllText', 'Scraping semua rating...');
        showProgress('Memulai scraping semua rating dari Google Maps...');
        document.getElementById('syncProgressBar').style.width = '20%';

        fetch('{{ route('admin.google-reviews.scrape-all') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('syncProgressBar').style.width = '100%';
            setTimeout(() => {
                document.getElementById('syncProgress').classList.add('hidden');
                if (data.success) {
                    showSyncResult('success', data.message || 'Scraping semua rating berhasil!');
                } else {
                    showSyncResult('error', data.message || 'Scraping semua rating gagal.');
                }
                resetButton('scrapeAllBtn', 'scrapeAllIcon', 'scrapeAllText', 'Scraping (Ambil Semua Rating)', 'fas fa-database mr-2');
                refreshReviews();
                isSyncing = false;
            }, 500);
        })
        .catch(error => {
            document.getElementById('syncProgress').classList.add('hidden');
            showSyncResult('error', 'Kesalahan: ' + error.message);
            resetButton('scrapeAllBtn', 'scrapeAllIcon', 'scrapeAllText', 'Scraping (Ambil Semua Rating)', 'fas fa-database mr-2');
            isSyncing = false;
        });
    }

    /**
     * ================================================================
     *  2. UPDATE DATA - Hanya ambil review TERBARU yang belum discrape
     * ================================================================
     */
    function triggerUpdateData() {
        if (isSyncing) return;
        isSyncing = true;

        const result = document.getElementById('syncResult');
        result.classList.add('hidden');

        setButtonLoading('updateDataBtn', 'updateDataIcon', 'updateDataText', 'Update data terbaru...');
        showProgress('Mencari review terbaru yang belum discrape...');
        document.getElementById('syncProgressBar').style.width = '20%';

        fetch('{{ route('admin.google-reviews.update-data') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('syncProgressBar').style.width = '100%';
            setTimeout(() => {
                document.getElementById('syncProgress').classList.add('hidden');
                if (data.success) {
                    showSyncResult('success', data.message || 'Update data berhasil!');
                } else {
                    showSyncResult('error', data.message || 'Update data gagal.');
                }
                resetButton('updateDataBtn', 'updateDataIcon', 'updateDataText', 'Update Data Scraping (Review Terbaru)', 'fas fa-cloud-upload-alt mr-2');
                refreshReviews();
                isSyncing = false;
            }, 500);
        })
        .catch(error => {
            document.getElementById('syncProgress').classList.add('hidden');
            showSyncResult('error', 'Kesalahan: ' + error.message);
            resetButton('updateDataBtn', 'updateDataIcon', 'updateDataText', 'Update Data Scraping (Review Terbaru)', 'fas fa-cloud-upload-alt mr-2');
            isSyncing = false;
        });
    }

    /**
     * Refresh stats via AJAX
     */
    function refreshStats() {
        fetch('{{ route('admin.google-reviews.api.stats') }}', {
            headers: { 'Accept': 'application/json' }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const container = document.getElementById('statsContainer');
                // Re-render stats cards dynamically
                const stats = data.stats;
                const dist = data.rating_distribution;

                // Update summary cards
                document.querySelectorAll('[data-stat]').forEach(el => {
                    const key = el.dataset.stat;
                    if (stats[key] !== undefined) {
                        el.textContent = stats[key];
                    }
                });

                // We'll just reload the page for full refresh after sync
                location.reload();
            }
        })
        .catch(() => {});
    }

    /**
     * Refresh reviews list
     */
    function refreshReviews() {
        location.reload();
    }

    /**
     * Apply filters to review list
     */
    function applyFilters() {
        const sentiment = document.getElementById('filterSentiment').value;
        const rating = document.getElementById('filterRating').value;
        const search = document.getElementById('filterSearch').value;

        let url = new URL(window.location.href);
        if (sentiment) url.searchParams.set('sentiment', sentiment);
        else url.searchParams.delete('sentiment');
        if (rating) url.searchParams.set('rating', rating);
        else url.searchParams.delete('rating');
        if (search) url.searchParams.set('search', search);
        else url.searchParams.delete('search');
        url.searchParams.set('page', '1');

        window.location.href = url.toString();
    }

    /**
     * Auto-refresh every 60 seconds (just stats, not full page)
     */
    setInterval(() => {
        if (!isSyncing) {
            fetch('{{ route('admin.google-reviews.api.sync-status') }}', {
                headers: { 'Accept': 'application/json' }
            }).catch(() => {});
        }
    }, 60000);
</script>
@endpush
