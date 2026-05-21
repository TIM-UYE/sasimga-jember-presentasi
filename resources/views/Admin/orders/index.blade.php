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

    <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200/80">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="grid grid-cols-1 gap-4 md:grid-cols-4">
            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-600">Cari</label>
                <input type="text" name="search" value="{{ $search }}" placeholder="Order ID / Nama / No HP" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200">
            </div>
            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-600">Status</label>
                <select name="status" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200">
                    <option value="all">Semua Status</option>
                    @foreach($statusLabels as $value => $label)
                        <option value="{{ $value }}" {{ $status == $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-600">Status Pembayaran</label>
                <select name="payment_status" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200">
                    <option value="all">Semua Pembayaran</option>
                    @foreach($paymentStatusLabels as $value => $label)
                        <option value="{{ $value }}" {{ $paymentStatus == $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="btn-admin w-full"><i class="fas fa-search mr-1"></i>Filter</button>
            </div>
        </form>
    </div>

    <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
        <div class="group rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200/80 transition-all hover:shadow-lg hover:shadow-amber-500/10 hover:ring-amber-200">
            <p class="text-xs font-medium uppercase tracking-wider text-slate-400">Pending</p>
            <p class="mt-1 text-3xl font-bold text-slate-800">{{ $orders->where('status', 'pending')->count() }}</p>
        </div>
        <div class="group rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200/80 transition-all hover:shadow-lg hover:shadow-blue-500/10 hover:ring-blue-200">
            <p class="text-xs font-medium uppercase tracking-wider text-slate-400">Diproses</p>
            <p class="mt-1 text-3xl font-bold text-slate-800">{{ $orders->where('status', 'diproses')->count() }}</p>
        </div>
        <div class="group rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200/80 transition-all hover:shadow-lg hover:shadow-emerald-500/10 hover:ring-emerald-200">
            <p class="text-xs font-medium uppercase tracking-wider text-slate-400">Siap Diambil</p>
            <p class="mt-1 text-3xl font-bold text-slate-800">{{ $orders->where('status', 'siap_diambil')->count() }}</p>
        </div>
        <div class="group rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200/80 transition-all hover:shadow-lg hover:shadow-purple-500/10 hover:ring-purple-200">
            <p class="text-xs font-medium uppercase tracking-wider text-slate-400">Selesai</p>
            <p class="mt-1 text-3xl font-bold text-slate-800">{{ $orders->where('status', 'selesai')->count() }}</p>
        </div>
    </div>

    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200/80">
        <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
            <div class="flex items-center gap-2">
                <i class="fas fa-list text-slate-400"></i>
                <span class="text-sm font-semibold text-slate-700">Daftar Pesanan</span>
                <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-medium text-slate-500"><i class="fas fa-database"></i>{{ $orders->count() }} Data</span>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50/80">
                        <th class="px-6 py-4 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">Order ID</th>
                        <th class="px-6 py-4 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">Pelanggan</th>
                        <th class="px-6 py-4 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">Pengiriman</th>
                        <th class="px-6 py-4 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">Pembayaran</th>
                        <th class="px-6 py-4 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">Total</th>
                        <th class="px-6 py-4 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">Status</th>
                        <th class="px-6 py-4 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">Progress Pesanan</th>
                        <th class="px-6 py-4 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">Tanggal</th>
                        <th class="px-6 py-4 text-right text-[11px] font-semibold uppercase tracking-wider text-slate-500">Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($orders as $order)
                        <tr class="group transition-all duration-200 hover:bg-slate-50/60" data-order-id="{{ $order->id }}">
                            <td class="px-6 py-4 font-semibold text-slate-800">{{ $order->kode_order }}</td>
                            <td class="px-6 py-4">
                                <p class="font-semibold text-slate-800">{{ $order->nama_pelanggan }}</p>
                                <p class="text-xs text-slate-500">{{ $order->nomor_hp }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1 rounded-lg px-3 py-1.5 text-xs font-medium ring-1 {{ $order->metode_pengiriman === 'delivery' ? 'bg-blue-50 text-blue-700 ring-blue-200/50' : 'bg-slate-100 text-slate-700 ring-slate-200/70' }}">
                                    <i class="fas {{ $order->metode_pengiriman === 'delivery' ? 'fa-motorcycle' : 'fa-store' }}"></i>
                                    {{ $order->metode_pengiriman === 'delivery' ? 'Delivery' : 'Pickup' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1 rounded-lg px-3 py-1.5 text-xs font-medium ring-1 {{ $order->metode_pembayaran === 'cash' ? 'bg-emerald-50 text-emerald-700 ring-emerald-200/50' : 'bg-purple-50 text-purple-700 ring-purple-200/50' }}">
                                    <i class="fas {{ $order->metode_pembayaran === 'cash' ? 'fa-money-bill' : 'fa-qrcode' }}"></i>
                                    {{ strtoupper($order->metode_pembayaran) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-semibold text-slate-800">Rp {{ number_format($order->total_bayar, 0, ',', '.') }}</td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col gap-1">
                                    <span class="inline-flex items-center rounded-lg px-3 py-1.5 text-xs font-medium ring-1
                                        @if($order->status === 'pending') bg-amber-50 text-amber-700 ring-amber-200/50
                                        @elseif($order->status === 'diproses') bg-blue-50 text-blue-700 ring-blue-200/50
                                        @elseif($order->status === 'siap_diambil') bg-green-50 text-green-700 ring-green-200/50
                                        @elseif($order->status === 'diantar') bg-indigo-50 text-indigo-700 ring-indigo-200/50
                                        @elseif($order->status === 'selesai') bg-emerald-50 text-emerald-700 ring-emerald-200/50
                                        @else bg-red-50 text-red-700 ring-red-200/50
                                        @endif">
                                        {{ $statusLabels[$order->status] ?? $order->status }}
                                    </span>
                                    <span class="inline-flex items-center rounded-lg px-3 py-1.5 text-xs font-medium ring-1 {{ $order->payment_status === 'paid' ? 'bg-emerald-50 text-emerald-700 ring-emerald-200/50' : 'bg-red-50 text-red-700 ring-red-200/50' }}">
                                        {{ $paymentStatusLabels[$order->payment_status] ?? $order->payment_status }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col gap-2">
                                    @include('admin.orders.partials.progress-column', ['order' => $order])
                                </div>
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-500">{{ $order->created_at->format('d M Y, H:i') }}</td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn-admin"><i class="fas fa-eye mr-1"></i>Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-16">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="mb-4 flex h-20 w-20 items-center justify-center rounded-2xl bg-slate-100">
                                        <i class="fas fa-inbox text-3xl text-slate-300"></i>
                                    </div>
                                    <p class="mb-1 text-lg font-semibold text-slate-700">Belum ada pesanan</p>
                                    <p class="text-sm text-slate-400">Tidak ada data pesanan untuk filter saat ini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4">
            {{ $orders->links() }}
        </div>
    </div>
</div>

{{-- SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- Order Status Management & Auto Refresh Script --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // ========== MANUAL STATUS UPDATE (via button click) ==========
    // Debounce tracker: prevent multiple rapid clicks on the same button
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

            // CEK DOUBLE CLICK: jika request untuk order ini masih pending, tolak
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

            // Show confirmation dialog
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

    // Update order status via AJAX
    function updateOrderStatus(orderId, newStatus, button) {
        // Tandai request sebagai pending (cegah double click)
        pendingRequests.set(orderId, true);

        button.disabled = true;
        button.classList.add('opacity-50', 'cursor-not-allowed');
        const spinner = button.querySelector('.status-spinner');
        const textSpan = button.querySelector('span');
        if (spinner) spinner.classList.remove('hidden');
        if (textSpan) textSpan.textContent = 'Memproses...';

        // Tambahkan timeout safety — jika response tidak kunjung datang, unlock button setelah 30 detik
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
        .then(response => {
            // Coba parse JSON, jika gagal jangan throw error mentah
            return response.json().catch(() => {
                throw new Error('Gagal membaca response server.');
            });
        })
        .then(data => {
            clearTimeout(safetyTimer);
            if (data.success) {
                if (data.order) updateOrderRow(orderId, data.order);
                if (data.stats) updateDashboardStats(data.stats);

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
            // Hapus pending request
            pendingRequests.delete(orderId);

            button.disabled = false;
            button.classList.remove('opacity-50', 'cursor-not-allowed');
            if (spinner) spinner.classList.add('hidden');
            if (textSpan) textSpan.textContent = button.dataset.nextLabel;
        });
    }

    // ========== BUILD STATUS/PROGRESS HTML (Client-side) ==========

    // Build the status badge HTML for an order
    function buildStatusBadge(status, statusLabel, paymentStatus, paymentStatusLabel, statusColor, statusIcon) {
        // Status colors mapping
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

    // Build the progress column HTML (status flow dots + action button)
    function buildProgressColumn(order) {
        const flowKeys = order.flow_keys;
        const currentIndex = order.current_index;
        const flowSteps = order.status_flow;
        let flowHtml = '';

        // Build progress dots
        flowHtml += '<div class="flex items-center gap-2">';
        flowKeys.forEach(function(key, index) {
            const isCompleted = index < currentIndex;
            const isCurrent = index === currentIndex;
            const isPending = index > currentIndex;

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

        // Build action button
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
            flowHtml += `
                <span class="inline-flex items-center gap-1 rounded-lg bg-emerald-50 px-3 py-2 text-xs font-medium text-emerald-700">
                    <i class="fas fa-check-circle"></i> Selesai
                </span>
            `;
        } else if (order.status === 'dibatalkan') {
            flowHtml += `
                <span class="inline-flex items-center gap-1 rounded-lg bg-red-50 px-3 py-2 text-xs font-medium text-red-700">
                    <i class="fas fa-times-circle"></i> Dibatalkan
                </span>
            `;
        } else {
            flowHtml += `
                <span class="inline-flex items-center gap-1 rounded-lg bg-slate-50 px-3 py-2 text-xs font-medium text-slate-500">
                    <i class="fas fa-clock"></i> Menunggu
                </span>
            `;
        }
        flowHtml += '</div>';

        return `<div class="flex flex-col gap-2">${flowHtml}</div>`;
    }

    // ========== UPDATE ORDER ROW FUNCTION ==========

    // Update a single order row with fresh data from the server
    function updateOrderRow(orderId, orderData) {
        const row = document.querySelector(`tr[data-order-id="${orderId}"]`);
        if (!row) return;

        // Update Status badge (column 6)
        const statusCell = row.querySelector('td:nth-child(6)');
        if (statusCell) {
            statusCell.innerHTML = buildStatusBadge(
                orderData.status,
                orderData.status_label,
                orderData.payment_status,
                orderData.payment_status_label,
                orderData.status_color,
                orderData.status_icon
            );
        }

        // Update Progress column (column 7)
        const progressCell = row.querySelector('td:nth-child(7)');
        if (progressCell) {
            progressCell.innerHTML = buildProgressColumn(orderData);
        }
    }

    // ========== UPDATE DASHBOARD STATS ==========

    function updateDashboardStats(stats) {
        // Map stat keys to their DOM elements
        const statElements = document.querySelectorAll('.grid.grid-cols-2.gap-4.md\\:grid-cols-4 .group');
        if (statElements.length >= 4) {
            const pendingEl = statElements[0].querySelector('p:last-child');
            const diprosesEl = statElements[1].querySelector('p:last-child');
            const siapEl = statElements[2].querySelector('p:last-child');
            const selesaiEl = statElements[3].querySelector('p:last-child');

            if (pendingEl && stats.pending !== undefined) pendingEl.textContent = stats.pending;
            if (diprosesEl && stats.diproses !== undefined) diprosesEl.textContent = stats.diproses;
            if (siapEl && stats.siap_diambil !== undefined) siapEl.textContent = stats.siap_diambil;
            if (selesaiEl && stats.selesai !== undefined) selesaiEl.textContent = stats.selesai;
        }
    }

    // ========== AUTO POLLING ENGINE ==========

    let isPollingActive = true;
    let pollInterval = 5000; // 5 seconds
    let pollTimer = null;

    // Get current filter parameters from the form
    function getFilterParams() {
        const params = new URLSearchParams();
        const searchInput = document.querySelector('input[name="search"]');
        const statusSelect = document.querySelector('select[name="status"]');
        const paymentSelect = document.querySelector('select[name="payment_status"]');

        if (searchInput && searchInput.value) params.set('search', searchInput.value);
        if (statusSelect && statusSelect.value !== 'all') params.set('status', statusSelect.value);
        if (paymentSelect && paymentSelect.value !== 'all') params.set('payment_status', paymentSelect.value);

        return params.toString();
    }

    // Poll the server for updated order data
    function pollOrders() {
        if (!isPollingActive) return;

        const params = getFilterParams();
        const url = params ? `/admin/orders/poll/data?${params}` : '/admin/orders/poll/data';

        fetch(url, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (!isPollingActive) return;

            if (data.success) {
                // Update dashboard stats
                if (data.stats) {
                    updateDashboardStats(data.stats);
                }

                // Update each order row
                if (data.orders && Array.isArray(data.orders)) {
                    // Track which order IDs are present in the response
                    const updatedIds = new Set();

                    data.orders.forEach(orderData => {
                        updatedIds.add(orderData.id);
                        updateOrderRow(orderData.id, orderData);
                    });

                    // Optional: remove rows that no longer exist in current filter
                    // (disabled by default since pagination handles this)
                }

                // Update the data count badge
                const countBadge = document.querySelector('.inline-flex.items-center.gap-1.rounded-full.bg-slate-100 span:last-child');
                if (countBadge && data.orders) {
                    // Only update if count badge is visible (not paginated pages)
                    // We'll update it silently
                }
            }
        })
        .catch(error => {
            // Silent fail for polling - don't spam console
            if (console && console.debug) {
                console.debug('Polling error (silent):', error);
            }
        });
    }

    // Start the polling interval
    function startPolling() {
        if (pollTimer) clearInterval(pollTimer);
        isPollingActive = true;
        // Initial poll right away
        pollOrders();
        // Then poll every 5 seconds
        pollTimer = setInterval(pollOrders, pollInterval);
    }

    // Stop polling
    function stopPolling() {
        isPollingActive = false;
        if (pollTimer) {
            clearInterval(pollTimer);
            pollTimer = null;
        }
    }

    // Pause polling while user is interacting (e.g. in a modal)
    function pausePollingTemporarily(durationMs) {
        if (durationMs === undefined) durationMs = 3000;
        const wasActive = isPollingActive;
        stopPolling();
        setTimeout(() => {
            if (wasActive) startPolling();
        }, durationMs);
    }

    // ========== START AUTO POLLING ==========

    // Start automatic polling when page loads
    startPolling();

    // Pause polling when SweetAlert is open (to avoid conflicts)
    document.addEventListener('click', function(e) {
        if (e.target.closest('.order-status-btn')) {
            pausePollingTemporarily(5000);
        }
    });

    // Resume polling when SweetAlert closes
    const originalSwalFire = Swal.fire;
    Swal.fire = function(options) {
        pausePollingTemporarily(8000);
        return originalSwalFire.call(this, options);
    };

    // Clean up polling when navigating away
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            stopPolling();
        } else {
            startPolling();
        }
    });

    // Add indicator that shows polling is active (small dot in header)
    const header = document.querySelector('.flex.flex-col.gap-4.md\\:flex-row');
    if (header) {
        const indicator = document.createElement('div');
        indicator.className = 'flex items-center gap-2 ml-auto';
        indicator.innerHTML = `
            <span class="relative flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
            </span>
            <span class="text-xs text-slate-400">Live</span>
        `;
        const refreshLink = header.querySelector('.btn-admin');
        if (refreshLink) {
            refreshLink.parentNode.insertBefore(indicator, refreshLink.nextSibling);
        }
    }
});
</script>
@endsection
