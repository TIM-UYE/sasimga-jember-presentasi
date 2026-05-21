@extends('frontend.layout.app')

@section('content')

<section class="min-h-screen bg-black text-white py-32">

    <div class="max-w-2xl mx-auto px-6">

        <div class="text-center mb-10">
            <div class="w-20 h-20 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-check text-white text-4xl"></i>
            </div>
            <h1 class="text-4xl font-bold mb-4">
                Pembayaran Berhasil!
            </h1>
            <p class="text-gray-400">
                Pesanan Anda telah dikonfirmasi dan sedang diproses.
            </p>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-green-500/20 border border-green-500/30 text-green-400 px-6 py-4 rounded-xl">
                {{ session('success') }}
            </div>
        @endif

        {{-- Order Details --}}
        <div class="bg-zinc-900 rounded-3xl p-6 mb-6">
            <h2 class="text-xl font-bold mb-4">
                <i class="fas fa-receipt mr-2 text-orange-400"></i>
                Detail Pesanan
            </h2>

            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-gray-400">Order ID</span>
                    <span class="font-semibold text-orange-400">{{ $order->kode_order }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-400">Nama Pelanggan</span>
                    <span class="font-semibold">{{ $order->nama_pelanggan }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-400">Nomor HP</span>
                    <span class="font-semibold">{{ $order->nomor_hp }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-400">Metode Pengiriman</span>
                    <span class="font-semibold">
                        @if($order->isDelivery())
                            <i class="fas fa-motorcycle mr-1 text-blue-400"></i>Delivery
                        @else
                            <i class="fas fa-store mr-1 text-green-400"></i>Pickup
                        @endif
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-400">Metode Pembayaran</span>
                    <span class="font-semibold">
                        @if($order->isQRISPayment())
                            <i class="fas fa-qrcode mr-1 text-purple-400"></i>QRIS
                        @else
                            <i class="fas fa-money-bill mr-1 text-green-400"></i>Cash
                        @endif
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-400">Status Pesanan</span>
                    <span class="px-3 py-1 rounded-full text-sm font-semibold
                        @if($order->status === 'diproses')
                            bg-blue-500/20 text-blue-400
                        @elseif($order->status === 'selesai')
                            bg-green-500/20 text-green-400
                        @elseif($order->status === 'dibatalkan')
                            bg-red-500/20 text-red-400
                        @else
                            bg-yellow-500/20 text-yellow-400
                        @endif
                    ">
                        {{ \App\Models\Order::getStatusLabels()[$order->status] ?? $order->status }}
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-400">Status Pembayaran</span>
                    <span class="px-3 py-1 rounded-full text-sm font-semibold
                        @if($order->payment_status === 'paid')
                            bg-green-500/20 text-green-400
                        @else
                            bg-yellow-500/20 text-yellow-400
                        @endif
                    ">
                        {{ \App\Models\Order::getPaymentStatusLabels()[$order->payment_status] ?? $order->payment_status }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Payment Transaction Details --}}
        @if($paymentTransaction)
        <div class="bg-zinc-900 rounded-3xl p-6 mb-6">
            <h2 class="text-xl font-bold mb-4">
                <i class="fas fa-credit-card mr-2 text-orange-400"></i>
                Detail Pembayaran
            </h2>

            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-gray-400">Transaction ID</span>
                    <span class="font-semibold text-sm">{{ $paymentTransaction->transaction_id ?? '-' }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-400">Total Dibayar</span>
                    <span class="text-xl font-bold text-green-400">
                        Rp {{ number_format($paymentTransaction->gross_amount, 0, ',', '.') }}
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-400">Metode Bayar</span>
                    <span class="font-semibold capitalize">{{ $paymentTransaction->payment_type ?? '-' }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-400">Waktu Bayar</span>
                    <span class="font-semibold">
                        @if($paymentTransaction->settlement_time)
                            {{ $paymentTransaction->settlement_time->format('d M Y H:i') }}
                        @else
                            {{ $paymentTransaction->updated_at->format('d M Y H:i') }}
                        @endif
                    </span>
                </div>
            </div>
        </div>
        @endif

        {{-- Order Items --}}
        <div class="bg-zinc-900 rounded-3xl p-6 mb-6">
            <h2 class="text-xl font-bold mb-4">
                <i class="fas fa-shopping-cart mr-2 text-orange-400"></i>
                Pesanan Anda
            </h2>

            <div class="space-y-4">
                @foreach($order->items as $item)
                <div class="flex justify-between items-center">
                    <div>
                        <p class="font-semibold">{{ $item->nama_menu }}</p>
                        <p class="text-sm text-gray-400">{{ $item->qty }}x @ Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                    </div>
                    <p class="text-orange-400 font-semibold">
                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                    </p>
                </div>
                @endforeach
            </div>

            @if($order->catatan)
            <div class="mt-4 pt-4 border-t border-gray-700">
                <p class="text-sm text-gray-400">Catatan:</p>
                <p class="text-gray-300">{{ $order->catatan }}</p>
            </div>
            @endif

            <hr class="border-gray-700 my-4">

            <div class="flex justify-between items-center">
                <span class="text-xl font-bold">Total</span>
                <span class="text-2xl font-bold text-orange-400">
                    Rp {{ number_format($order->total_bayar, 0, ',', '.') }}
                </span>
            </div>
        </div>

        {{-- Delivery Address --}}
        @if($order->alamat)
        <div class="bg-zinc-900 rounded-3xl p-6 mb-6">
            <h2 class="text-xl font-bold mb-4">
                <i class="fas fa-map-marker-alt mr-2 text-orange-400"></i>
                Alamat Pengiriman
            </h2>
            <p class="text-gray-300">{{ $order->alamat }}</p>
        </div>
        @endif

        {{-- Action Buttons --}}
        <div class="flex flex-col gap-4">
            <a
                href="{{ route('frontend.menu') }}"
                class="w-full bg-orange-500 hover:bg-orange-600 py-4 rounded-2xl font-bold text-lg text-center transition"
            >
                <i class="fas fa-shopping-bag mr-2"></i>
                Pesan Lagi
            </a>
            <a
                href="{{ route('frontend.home') }}"
                class="w-full bg-zinc-800 hover:bg-zinc-700 border-2 border-gray-600 py-4 rounded-2xl font-bold text-lg text-center transition"
            >
                <i class="fas fa-home mr-2"></i>
                Kembali ke Beranda
            </a>
        </div>

    </div>

</section>

@endsection
