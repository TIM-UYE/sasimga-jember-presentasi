@extends('admin.layout.main')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <div>
            <a href="{{ route('admin.orders.index') }}" class="text-sm text-slate-500 transition hover:text-slate-700">
                <i class="fas fa-arrow-left mr-1"></i>Kembali ke daftar pesanan
            </a>
            <h1 class="mt-2 text-2xl font-bold tracking-tight text-slate-800 md:text-3xl">Detail Pesanan {{ $order->kode_order }}</h1>
            <p class="mt-1 text-sm text-slate-500">Ringkasan detail pesanan, status, dan aksi lanjutan.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-2">
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/80">
                <h6 class="mb-4 font-bold text-slate-700"><i class="fas fa-user mr-2 text-blue-500"></i>Informasi Pelanggan</h6>
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div><p class="text-sm text-slate-500">Nama</p><p class="font-semibold text-slate-800">{{ $order->nama_pelanggan }}</p></div>
                    <div><p class="text-sm text-slate-500">Nomor HP</p><p class="font-semibold text-slate-800">{{ $order->nomor_hp }}</p></div>
                    @if($order->alamat)
                        <div class="md:col-span-2"><p class="text-sm text-slate-500">Alamat</p><p class="font-semibold text-slate-800">{{ $order->alamat }}</p></div>
                    @endif
                    @if($order->catatan)
                        <div class="md:col-span-2"><p class="text-sm text-slate-500">Catatan</p><p class="font-semibold text-slate-800">{{ $order->catatan }}</p></div>
                    @endif
                </div>
            </div>

            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/80">
                <h6 class="mb-4 font-bold text-slate-700"><i class="fas fa-list mr-2 text-emerald-500"></i>Detail Pesanan</h6>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-slate-200">
                                <th class="py-2 text-left font-semibold text-slate-600">Menu</th>
                                <th class="py-2 text-center font-semibold text-slate-600">Qty</th>
                                <th class="py-2 text-right font-semibold text-slate-600">Harga</th>
                                <th class="py-2 text-right font-semibold text-slate-600">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                                <tr class="border-b border-slate-100">
                                    <td class="py-3 text-slate-700">{{ $item->nama_menu }}</td>
                                    <td class="py-3 text-center text-slate-600">{{ $item->qty }}</td>
                                    <td class="py-3 text-right text-slate-600">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                    <td class="py-3 text-right font-semibold text-slate-800">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="pt-4 text-right font-bold text-slate-600">Subtotal</td>
                                <td class="pt-4 text-right font-bold text-slate-800">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="pt-2 text-right font-bold text-slate-600">Total Bayar</td>
                                <td class="pt-2 text-right text-xl font-bold text-orange-500">Rp {{ number_format($order->total_bayar, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <div class="space-y-6 lg:col-span-1">
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/80">
                <h6 class="mb-4 font-bold text-slate-700"><i class="fas fa-info-circle mr-2 text-purple-500"></i>Status Pesanan</h6>
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-slate-500">Status</p>
                        <span class="inline-flex items-center rounded-lg px-3 py-1.5 text-sm font-medium ring-1
                            @if($order->status === 'pending') bg-amber-50 text-amber-700 ring-amber-200/50
                            @elseif($order->status === 'diproses') bg-blue-50 text-blue-700 ring-blue-200/50
                            @elseif($order->status === 'siap_diambil') bg-green-50 text-green-700 ring-green-200/50
                            @elseif($order->status === 'diantar') bg-indigo-50 text-indigo-700 ring-indigo-200/50
                            @elseif($order->status === 'selesai') bg-emerald-50 text-emerald-700 ring-emerald-200/50
                            @else bg-red-50 text-red-700 ring-red-200/50 @endif">
                            {{ $statusLabels[$order->status] ?? $order->status }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500">Status Pembayaran</p>
                        <span class="inline-flex items-center rounded-lg px-3 py-1.5 text-sm font-medium ring-1 {{ $order->payment_status === 'paid' ? 'bg-emerald-50 text-emerald-700 ring-emerald-200/50' : 'bg-red-50 text-red-700 ring-red-200/50' }}">
                            {{ $paymentStatusLabels[$order->payment_status] ?? $order->payment_status }}
                        </span>
                    </div>
                    <div><p class="text-sm text-slate-500">Metode Pengiriman</p><p class="font-semibold text-slate-800">{{ $deliveryMethodLabels[$order->metode_pengiriman] ?? $order->metode_pengiriman }}</p></div>
                    <div><p class="text-sm text-slate-500">Metode Pembayaran</p><p class="font-semibold text-slate-800">{{ $paymentMethodLabels[$order->metode_pembayaran] ?? $order->metode_pembayaran }}</p></div>
                    <div><p class="text-sm text-slate-500">Dibuat</p><p class="font-semibold text-slate-800">{{ $order->created_at->format('d M Y, H:i') }}</p></div>
                    <div><p class="text-sm text-slate-500">Terakhir Diupdate</p><p class="font-semibold text-slate-800">{{ $order->updated_at->format('d M Y, H:i') }}</p></div>
                </div>
            </div>

            @if($order->isActive())
                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/80">
                    <h6 class="mb-4 font-bold text-slate-700"><i class="fas fa-edit mr-2 text-orange-500"></i>Update Status</h6>
                    <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="mb-4">
                            <label class="mb-2 block text-sm font-semibold text-slate-600">Status Pesanan</label>
                            <select name="status" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200">
                                @foreach($statusLabels as $value => $label)
                                    <option value="{{ $value }}" {{ $order->status == $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn-admin w-full"><i class="fas fa-save mr-1"></i>Update Status</button>
                    </form>
                </div>

                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/80">
                    <h6 class="mb-4 font-bold text-slate-700"><i class="fas fa-credit-card mr-2 text-emerald-500"></i>Update Pembayaran</h6>
                    <form action="{{ route('admin.orders.updatePaymentStatus', $order->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="mb-4">
                            <label class="mb-2 block text-sm font-semibold text-slate-600">Status Pembayaran</label>
                            <select name="payment_status" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200">
                                @foreach($paymentStatusLabels as $value => $label)
                                    <option value="{{ $value }}" {{ $order->payment_status == $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn-admin w-full"><i class="fas fa-save mr-1"></i>Update Pembayaran</button>
                    </form>
                </div>
            @endif

            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/80">
                <h6 class="mb-4 font-bold text-slate-700"><i class="fas fa-cog mr-2 text-slate-500"></i>Aksi Lainnya</h6>
                <div class="space-y-3">
                    @if($order->isActive())
                        <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-admin-danger w-full"><i class="fas fa-times mr-1"></i>Batalkan Pesanan</button>
                        </form>
                    @endif
                    <a href="https://wa.me/{{ $order->nomor_hp }}" target="_blank" class="btn-admin w-full bg-gradient-to-r from-emerald-500 to-emerald-600"><i class="fab fa-whatsapp mr-1"></i>Hubungi Pelanggan</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
