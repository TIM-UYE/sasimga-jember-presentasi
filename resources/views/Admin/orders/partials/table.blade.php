<div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200/80">
    <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
        <div class="flex items-center gap-2">
            <i class="fas fa-list text-slate-400"></i>
            <span class="text-sm font-semibold text-slate-700">Daftar Pesanan</span>
            <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-medium text-slate-500"><i class="fas fa-database"></i>{{ $orders->total() }} Data</span>
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
                        <td colspan="9" class="px-6 py-16">
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
