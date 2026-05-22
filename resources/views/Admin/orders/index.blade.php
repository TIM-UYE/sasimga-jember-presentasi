@extends('admin.layout.main')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-slate-800 md:text-3xl">Kelola Pesanan</h1>
        <p class="mt-1 text-sm text-slate-500">Pantau transaksi pelanggan dan status proses pesanan.</p>
    </div>

    <div class="flex items-center gap-2">

    {{-- Download Excel --}}
    <a href="{{ route('admin.laporan.orders.xlsx') }}"
    class="inline-flex items-center rounded-xl bg-emerald-500 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-emerald-600">
        <i class="fas fa-file-excel mr-2"></i>
        Excel
    </a>

    {{-- Download CSV --}}
    <a href="{{ route('admin.laporan.orders.csv') }}"
    class="inline-flex items-center rounded-xl bg-sky-500 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-sky-600">
        <i class="fas fa-file-csv mr-2"></i>
        CSV
    </a>

    {{-- Refresh --}}
    <a href="{{ route('admin.orders.index') }}"
       class="btn-admin">
        <i class="fas fa-sync-alt mr-1"></i>
        Refresh
    </a>

</div>
</div>

    {{-- FILTER SECTION --}}
    <div class="rounded-3xl bg-white/90 backdrop-blur-sm p-6 shadow-lg ring-1 ring-slate-200/70">

        <form method="GET"
            action="{{ route('admin.orders.index') }}"
            id="order-filter-form"
            class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-5">

            <!-- STATUS -->
            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">
                    Status Pesanan
                </label>

                <div class="relative">
                    <select name="status" id="filter-status"
                        class="w-full appearance-none rounded-2xl border border-slate-200 bg-white px-4 py-3 pr-11 text-sm font-medium text-slate-700 shadow-sm transition focus:border-orange-400 focus:outline-none focus:ring-4 focus:ring-orange-100">
                        <option value="all" {{ request('status') == 'all' || !request('status') ? 'selected' : '' }}>Semua Status</option>
                        @foreach($statusLabels as $value => $label)
                            <option value="{{ $value }}"
                                {{ request('status') == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>

                    <div class="pointer-events-none absolute inset-y-0 right-4 flex items-center text-slate-400">
                        <i class="fas fa-chevron-down text-xs"></i>
                    </div>
                </div>
            </div>

            <!-- PAYMENT STATUS -->
            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">
                    Status Pembayaran
                </label>

                <div class="relative">
                    <select name="payment_status" id="filter-payment-status"
                        class="w-full appearance-none rounded-2xl border border-slate-200 bg-white px-4 py-3 pr-11 text-sm font-medium text-slate-700 shadow-sm transition focus:border-orange-400 focus:outline-none focus:ring-4 focus:ring-orange-100">
                        <option value="all" {{ request('payment_status') == 'all' || !request('payment_status') ? 'selected' : '' }}>Semua Pembayaran</option>
                        @foreach($paymentStatusLabels as $value => $label)
                            <option value="{{ $value }}"
                                {{ request('payment_status') == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>

                    <div class="pointer-events-none absolute inset-y-0 right-4 flex items-center text-slate-400">
                        <i class="fas fa-chevron-down text-xs"></i>
                    </div>
                </div>
            </div>

            <!-- LOADING INDICATOR (akan muncul saat AJAX) -->
            <div class="flex items-end">
                <div id="filter-loading" class="hidden items-center gap-2 text-sm text-slate-500">
                    <i class="fas fa-spinner fa-spin text-orange-500"></i>
                    <span>Memuat data...</span>
                </div>
            </div>

            <!-- BUTTON FILTER (manual submit, fallback jika JS mati / ingin reload penuh) -->
            <div class="flex items-end md:col-span-2 xl:col-span-1">
                <button type="submit"
                    class="inline-flex w-full items-center justify-center rounded-2xl bg-gradient-to-r from-orange-500 to-orange-600 px-5 py-3 text-sm font-semibold text-white shadow-md transition hover:-translate-y-0.5 hover:from-orange-600 hover:to-orange-700 hover:shadow-lg">
                    <i class="fas fa-search mr-2"></i>
                    Filter Data
                </button>
            </div>

            <!-- RESET -->
            <div class="flex items-end">
                <a href="{{ route('admin.orders.index') }}"
                    class="inline-flex w-full items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50 hover:shadow-md">
                    <i class="fas fa-rotate-left mr-2"></i>
                    Reset Filter
                </a>
            </div>

        </form>
    </div>

    {{-- STATS CARDS --}}
    <div class="grid grid-cols-2 gap-4 md:grid-cols-4" id="stats-cards">
        <div class="group rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200/80 transition-all hover:shadow-lg hover:shadow-amber-500/10 hover:ring-amber-200">
            <p class="text-xs font-medium uppercase tracking-wider text-slate-400">Pending</p>
            <p class="mt-1 text-3xl font-bold text-slate-800" id="stat-pending">{{ \App\Models\Order::where('status', 'pending')->count() }}</p>
        </div>
        <div class="group rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200/80 transition-all hover:shadow-lg hover:shadow-blue-500/10 hover:ring-blue-200">
            <p class="text-xs font-medium uppercase tracking-wider text-slate-400">Diproses</p>
            <p class="mt-1 text-3xl font-bold text-slate-800" id="stat-diproses">{{ \App\Models\Order::where('status', 'diproses')->count() }}</p>
        </div>
        <div class="group rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200/80 transition-all hover:shadow-lg hover:shadow-emerald-500/10 hover:ring-emerald-200">
            <p class="text-xs font-medium uppercase tracking-wider text-slate-400">Siap Diambil</p>
            <p class="mt-1 text-3xl font-bold text-slate-800" id="stat-siap_diambil">{{ \App\Models\Order::where('status', 'siap_diambil')->count() }}</p>
        </div>
        <div class="group rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200/80 transition-all hover:shadow-lg hover:shadow-purple-500/10 hover:ring-purple-200">
            <p class="text-xs font-medium uppercase tracking-wider text-slate-400">Selesai</p>
            <p class="mt-1 text-3xl font-bold text-slate-800" id="stat-selesai">{{ \App\Models\Order::where('status', 'selesai')->count() }}</p>
        </div>
    </div>

    {{-- TABLE CONTAINER (di-refresh via AJAX) --}}
    <div id="orders-table-container" class="transition-opacity duration-300">
        @include('admin.orders.partials.table', compact('orders', 'statusLabels', 'paymentStatusLabels'))
    </div>

</div>

{{-- SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- ORDER FILTER & STATUS SCRIPT --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // ==================== FILTER AUTO-SUBMIT AJAX ====================

    const filterForm = document.getElementById('order-filter-form');
    const statusSelect = document.getElementById('filter-status');
    const paymentSelect = document.getElementById('filter-payment-status');
    const tableContainer = document.getElementById('orders-table-container');
    const filterLoading = document.getElementById('filter-loading');

    let filterTimeout = null;
    let isFiltering = false;

    // Fungsi untuk fetch data via AJAX
    async function fetchFilteredOrders() {
        if (isFiltering) return;
        isFiltering = true;

        // Tampilkan loading state
        if (filterLoading) filterLoading.classList.remove('hidden');
        tableContainer.classList.add('opacity-50');
        tableContainer.style.pointerEvents = 'none';

        try {
            const params = new URLSearchParams();
            const statusVal = statusSelect ? statusSelect.value : 'all';
            const paymentVal = paymentSelect ? paymentSelect.value : 'all';

            if (statusVal !== 'all') params.set('status', statusVal);
            if (paymentVal !== 'all') params.set('payment_status', paymentVal);

            const url = `{{ route('admin.orders.index') }}?${params.toString()}`;

            const response = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (data.success) {
                // Update table dengan HTML baru
                if (data.html) {
                    // Buat elemen sementara, extract hanya table content
                    tableContainer.innerHTML = data.html;
                }

                // Update stats
                if (data.stats) {
                    updateStats(data.stats);
                }
            }
        } catch (error) {
            console.error('Filter error:', error);
        } finally {
            // Sembunyikan loading state
            if (filterLoading) filterLoading.classList.add('hidden');
            tableContainer.classList.remove('opacity-50');
            tableContainer.style.pointerEvents = '';

            // Re-attach event listeners untuk pagination links
            attachPaginationListeners();

            isFiltering = false;
        }
    }

    // Fungsi update stats
    function updateStats(stats) {
        const statIds = ['stat-pending', 'stat-diproses', 'stat-siap_diambil', 'stat-selesai'];
        const statKeys = ['pending', 'diproses', 'siap_diambil', 'selesai'];

        statKeys.forEach((key, index) => {
            if (stats[key] !== undefined) {
                const el = document.getElementById(statIds[index]);
                if (el) el.textContent = stats[key];
            }
        });
    }

    // Fungsi attach pagination listeners (setelah AJAX update table)
    function attachPaginationListeners() {
        document.querySelectorAll('#orders-table-container .pagination a, #orders-table-container .pagination button').forEach(el => {
            el.addEventListener('click', function(e) {
                // Cegah navigasi default
                // Biarkan pagination bekerja normal dengan full page load
                // karena pagination perlu query parameter page
            });
        });
    }

    // Event: change pada dropdown status
    if (statusSelect) {
        statusSelect.addEventListener('change', function() {
            clearTimeout(filterTimeout);
            filterTimeout = setTimeout(fetchFilteredOrders, 300); // debounce 300ms
        });
    }

    // Event: change pada dropdown payment status
    if (paymentSelect) {
        paymentSelect.addEventListener('change', function() {
            clearTimeout(filterTimeout);
            filterTimeout = setTimeout(fetchFilteredOrders, 300);
        });
    }

    // Mencegah form submit default (kita pakai AJAX)
    if (filterForm) {
        filterForm.addEventListener('submit', function(e) {
            // Biarkan submit normal sebagai fallback
            // Tapi jika JS aktif, kita override dengan AJAX
            e.preventDefault();
            fetchFilteredOrders();
        });
    }

    // ==================== ORDER STATUS UPDATE (sama seperti sebelumnya) ====================

    const pendingRequests = new Map();

    // Handle order status button clicks
    document.addEventListener('click', function(e) {
        const button = e.target.closest('.order-status-btn');
        if (button) {
            e.preventDefault();
            if (button.disabled) return;

            const orderId = button.dataset.orderId;
            const nextStatus = button.dataset.nextStatus;
            const nextLabel = button.dataset.nextLabel;

            if (pendingRequests.has(orderId)) {
                Swal.fire({
                    title: 'Mohon Tunggu',
                    text: 'Permintaan sebelumnya masih diproses. Harap tunggu.',
                    icon: 'warning',
                    timer: 1500,
                    showConfirmButton: false
                });
                return;
            }

            Swal.fire({
                title: 'Konfirmasi Perubahan Status',
                text: `Yakin ubah status pesanan menjadi "${nextLabel}"?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3B82F6',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Ya, Ubah Status',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    updateOrderStatus(orderId, nextStatus, button);
                }
            });
        }
    });

    function updateOrderStatus(orderId, newStatus, button) {
        pendingRequests.set(orderId, true);

        button.disabled = true;
        button.classList.add('opacity-50', 'cursor-not-allowed');
        const spinner = button.querySelector('.status-spinner');
        const textSpan = button.querySelector('span');
        if (spinner) spinner.classList.remove('hidden');
        if (textSpan) textSpan.textContent = 'Memproses...';

        const safetyTimer = setTimeout(() => {
            pendingRequests.delete(orderId);
            button.disabled = false;
            button.classList.remove('opacity-50', 'cursor-not-allowed');
            if (spinner) spinner.classList.add('hidden');
            if (textSpan) textSpan.textContent = button.dataset.nextLabel;
        }, 30000);

        fetch(`/admin/orders/${orderId}/status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ status: newStatus })
        })
        .then(response => response.json().catch(() => { throw new Error('Gagal membaca response server.'); }))
        .then(data => {
            clearTimeout(safetyTimer);
            if (data.success) {
                if (data.order) updateOrderRow(orderId, data.order);
                if (data.stats) updateStats(data.stats);

                Swal.fire({
                    title: 'Berhasil!',
                    text: data.message || 'Status pesanan berhasil diubah!',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                });
            } else {
                throw new Error(data.message || 'Gagal mengubah status');
            }
        })
        .catch(error => {
            clearTimeout(safetyTimer);
            Swal.fire({
                title: 'Error!',
                text: error.message || 'Terjadi kesalahan.',
                icon: 'error'
            });
        })
        .finally(() => {
            pendingRequests.delete(orderId);
            button.disabled = false;
            button.classList.remove('opacity-50', 'cursor-not-allowed');
            if (spinner) spinner.classList.add('hidden');
            if (textSpan) textSpan.textContent = button.dataset.nextLabel;
        });
    }

    // ========== BUILD STATUS/PROGRESS HTML ==========

    function buildStatusBadge(status, statusLabel, paymentStatus, paymentStatusLabel, statusColor, statusIcon) {
        const colorMap = {
            'amber': { bg: 'bg-amber-50', text: 'text-amber-700', ring: 'ring-amber-200/50' },
            'blue': { bg: 'bg-blue-50', text: 'text-blue-700', ring: 'ring-blue-200/50' },
            'green': { bg: 'bg-green-50', text: 'text-green-700', ring: 'ring-green-200/50' },
            'indigo': { bg: 'bg-indigo-50', text: 'text-indigo-700', ring: 'ring-indigo-200/50' },
            'emerald': { bg: 'bg-emerald-50', text: 'text-emerald-700', ring: 'ring-emerald-200/50' },
            'red': { bg: 'bg-red-50', text: 'text-red-700', ring: 'ring-red-200/50' },
            'orange': { bg: 'bg-orange-50', text: 'text-orange-700', ring: 'ring-orange-200/50' },
        };
        const sc = colorMap[statusColor] || colorMap.amber;
        const paymentColor = paymentStatus === 'paid' ? 'bg-emerald-50 text-emerald-700 ring-emerald-200/50' : 'bg-red-50 text-red-700 ring-red-200/50';

        return `
            <div class="flex flex-col gap-1">
                <span class="inline-flex items-center rounded-lg px-3 py-1.5 text-xs font-medium ring-1 ${sc.bg} ${sc.text} ${sc.ring}">
                    <i class="${statusIcon} mr-1"></i>
                    ${statusLabel}
                </span>
                <span class="inline-flex items-center rounded-lg px-3 py-1.5 text-xs font-medium ring-1 ${paymentColor}">
                    ${paymentStatusLabel}
                </span>
            </div>
        `;
    }

    function buildProgressColumn(order) {
        const flowKeys = order.flow_keys;
        const currentIndex = order.current_index;
        let flowHtml = '<div class="flex items-center gap-2">';
        flowKeys.forEach(function(key, index) {
            const isCompleted = index < currentIndex;
            const isCurrent = index === currentIndex;
            let dotColor = 'bg-slate-300';
            if (isCompleted) dotColor = 'bg-green-500';
            else if (isCurrent) dotColor = 'bg-blue-500 animate-pulse';
            flowHtml += '<div class="flex items-center gap-1">';
            flowHtml += `<div class="flex h-2 w-2 items-center justify-center rounded-full ${dotColor}"></div>`;
            if (index < flowKeys.length - 1) {
                let lineColor = isCompleted ? 'bg-green-500' : 'bg-slate-300';
                flowHtml += `<div class="h-px w-4 ${lineColor}"></div>`;
            }
            flowHtml += '</div>';
        });
        flowHtml += '</div>';

        flowHtml += '<div class="mt-2">';
        if (order.status !== 'selesai' && order.status !== 'dibatalkan' && order.next_status) {
            flowHtml += `
                <button type="button"
                    class="order-status-btn inline-flex items-center gap-2 rounded-lg px-3 py-2 text-xs font-medium transition-all
                           bg-blue-50 text-blue-700 hover:bg-blue-100 hover:text-blue-800
                           disabled:opacity-50 disabled:cursor-not-allowed"
                    data-order-id="${order.id}"
                    data-current-status="${order.status}"
                    data-next-status="${order.next_status}"
                    data-next-label="${order.next_status_label}">
                    <i class="fas fa-arrow-right"></i>
                    <span>${order.next_status_label}</span>
                    <div class="status-spinner hidden">
                        <i class="fas fa-spinner fa-spin"></i>
                    </div>
                </button>
            `;
        } else if (order.status === 'selesai') {
            flowHtml += `<span class="inline-flex items-center gap-1 rounded-lg bg-emerald-50 px-3 py-2 text-xs font-medium text-emerald-700"><i class="fas fa-check-circle"></i> Selesai</span>`;
        } else if (order.status === 'dibatalkan') {
            flowHtml += `<span class="inline-flex items-center gap-1 rounded-lg bg-red-50 px-3 py-2 text-xs font-medium text-red-700"><i class="fas fa-times-circle"></i> Dibatalkan</span>`;
        } else {
            flowHtml += `<span class="inline-flex items-center gap-1 rounded-lg bg-slate-50 px-3 py-2 text-xs font-medium text-slate-500"><i class="fas fa-clock"></i> Menunggu</span>`;
        }
        flowHtml += '</div>';
        return `<div class="flex flex-col gap-2">${flowHtml}</div>`;
    }

    // Update a single order row (dari response updateStatus)
    function updateOrderRow(orderId, orderData) {
        const row = document.querySelector(`tr[data-order-id="${orderId}"]`);
        if (!row) return;

        const statusCell = row.querySelector('td:nth-child(6)');
        if (statusCell) {
            statusCell.innerHTML = buildStatusBadge(
                orderData.status, orderData.status_label,
                orderData.payment_status, orderData.payment_status_label,
                orderData.status_color, orderData.status_icon
            );
        }

        const progressCell = row.querySelector('td:nth-child(7)');
        if (progressCell) {
            progressCell.innerHTML = buildProgressColumn(orderData);
        }
    }

    // ========== AUTO POLLING (dengan filter awareness) ==========

    let isPollingActive = true;
    let pollInterval = 10000; // 10 seconds (lebih jarang, karena filter sudah realtime)
    let pollTimer = null;

    function getCurrentFilterParams() {
        const params = new URLSearchParams();
        const statusVal = statusSelect ? statusSelect.value : 'all';
        const paymentVal = paymentSelect ? paymentSelect.value : 'all';
        if (statusVal !== 'all') params.set('status', statusVal);
        if (paymentVal !== 'all') params.set('payment_status', paymentVal);
        return params.toString();
    }

    function pollOrders() {
        if (!isPollingActive || isFiltering) return;

        const params = getCurrentFilterParams();
        const url = params ? `/admin/orders/poll/data?${params}` : '/admin/orders/poll/data';

        fetch(url, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.json())
        .then(data => {
            if (!isPollingActive) return;
            if (data.success && data.stats) {
                updateStats(data.stats);
            }
        })
        .catch(() => {}); // silent fail
    }

    function startPolling() {
        if (pollTimer) clearInterval(pollTimer);
        isPollingActive = true;
        pollTimer = setInterval(pollOrders, pollInterval);
    }

    function stopPolling() {
        isPollingActive = false;
        if (pollTimer) { clearInterval(pollTimer); pollTimer = null; }
    }

    // Mulai polling
    startPolling();

    // Hentikan polling saat filter berubah (biar AJAX dulu yang jalan)
    if (statusSelect) {
        statusSelect.addEventListener('change', function() { stopPolling(); });
    }
    if (paymentSelect) {
        paymentSelect.addEventListener('change', function() { stopPolling(); });
    }

    // Resume polling setelah AJAX selesai (dengan delay)
    const originalFetch = window.fetch;
    window.fetch = function() {
        // Override untuk detect kapan fetch selesai
        return originalFetch.apply(this, arguments).then(response => {
            // Clone response agar bisa dibaca dua kali
            const clonedResponse = response.clone();
            clonedResponse.json().then(data => {
                if (data && data.success && data.html) {
                    // Filter AJAX selesai, resume polling setelah 2 detik
                    setTimeout(startPolling, 2000);
                }
            }).catch(() => {});
            return response;
        });
    };

    // Cleanup visibility
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) stopPolling();
        else startPolling();
    });

    // ========== PAGINATION dengan AJAX ==========

    // Intercept clicks on pagination links untuk AJAX
    document.addEventListener('click', function(e) {
        const paginationLink = e.target.closest('#orders-table-container .pagination a');
        if (!paginationLink) return;

        const href = paginationLink.getAttribute('href');
        if (!href || href === '#') return;

        e.preventDefault();

        // Ambil parameter page dari href
        const url = new URL(href, window.location.origin);

        // Fetch AJAX
        stopPolling();
        isFiltering = true;

        if (filterLoading) filterLoading.classList.remove('hidden');
        tableContainer.classList.add('opacity-50');
        tableContainer.style.pointerEvents = 'none';

        // Tambahkan filter params yang aktif
        const statusVal = statusSelect ? statusSelect.value : 'all';
        const paymentVal = paymentSelect ? paymentSelect.value : 'all';
        if (statusVal !== 'all') url.searchParams.set('status', statusVal);
        if (paymentVal !== 'all') url.searchParams.set('payment_status', paymentVal);

        fetch(url.toString(), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.html) {
                tableContainer.innerHTML = data.html;
                if (data.stats) updateStats(data.stats);
                // Update URL browser tanpa reload
                window.history.replaceState({}, '', url.toString());
            }
        })
        .catch(error => {
            console.error('Pagination AJAX error:', error);
            // Fallback: redirect ke URL
            window.location.href = href;
        })
        .finally(() => {
            if (filterLoading) filterLoading.classList.add('hidden');
            tableContainer.classList.remove('opacity-50');
            tableContainer.style.pointerEvents = '';
            isFiltering = false;
            setTimeout(startPolling, 2000);
        });
    });

});
</script>
@endsection
