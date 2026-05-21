@extends('frontend.layout.app')

@section('content')

<section class="min-h-screen bg-black text-white py-32">

    <div class="max-w-2xl mx-auto px-6 text-center">

        {{-- SUCCESS ICON --}}
        <div class="mb-8">

            <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-green-500/20 mb-6">

                <i class="fas fa-check text-5xl text-green-500"></i>

            </div>

        </div>

        {{-- TITLE --}}
        <h1 class="text-4xl font-bold mb-4">
            Pesanan Berhasil Dibuat!
        </h1>

        <p class="text-gray-400 mb-8">
            Terima kasih telah memesan. Pesanan Anda sedang diproses.
        </p>

        {{-- ORDER INFO CARD --}}
        <div class="bg-zinc-900 rounded-3xl p-8 mb-8 text-left">

            <div class="flex justify-between items-center mb-6">

                <span class="text-gray-400">Order ID</span>

                <span class="text-2xl font-bold text-orange-400">
                    {{ $order->kode_order }}
                </span>

            </div>

            <hr class="border-gray-700 my-4">

            <div class="grid grid-cols-2 gap-4 mb-4">

                <div>

                    <p class="text-sm text-gray-400">Tanggal</p>

                    <p class="font-semibold">
                        {{ $order->created_at->format('d M Y, H:i') }}
                    </p>

                </div>

                <div>

                    <p class="text-sm text-gray-400">Status</p>

                    <span class="inline-block bg-yellow-500/20 text-yellow-400 px-3 py-1 rounded-full text-sm">
                        {{ ucfirst($order->status) }}
                    </span>

                </div>

            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">

                <div>

                    <p class="text-sm text-gray-400">Metode Pengiriman</p>

                    <p class="font-semibold">
                        {{ ucfirst($order->metode_pengiriman) }}
                    </p>

                </div>

                <div>

                    <p class="text-sm text-gray-400">Metode Pembayaran</p>

                    <p class="font-semibold">
                        {{ strtoupper($order->metode_pembayaran) }}
                    </p>

                </div>

            </div>

            <hr class="border-gray-700 my-4">

            <div class="flex justify-between items-center">

                <span class="text-gray-400">Total Bayar</span>

                <span class="text-2xl font-bold text-orange-400">
                    Rp {{ number_format($order->total_bayar, 0, ',', '.') }}
                </span>

            </div>

        </div>

        {{-- ORDER ITEMS --}}
        <div class="bg-zinc-900 rounded-3xl p-6 mb-8">

            <h3 class="text-xl font-bold mb-4 text-left">
                Detail Pesanan
            </h3>

            <div class="space-y-3">

                @foreach($order->items as $item)

                    <div class="flex justify-between items-center">

                        <div class="text-left">

                            <p class="font-semibold">
                                {{ $item->nama_menu }}
                            </p>

                            <p class="text-sm text-gray-400">
                                {{ $item->qty }}x @ Rp {{ number_format($item->harga, 0, ',', '.') }}
                            </p>

                        </div>

                        <p class="text-orange-400 font-semibold">
                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                        </p>

                    </div>

                @endforeach

            </div>

        </div>

        {{-- DELIVERY INFO --}}
        @if($order->isDelivery())

            <div class="bg-blue-500/10 border border-blue-500/30 rounded-3xl p-6 mb-8 text-left">

                <div class="flex items-start gap-4">

                    <i class="fas fa-info-circle text-blue-400 text-2xl mt-1"></i>

                    <div>

                        <h4 class="font-bold text-blue-400 mb-2">
                            Informasi Pengiriman
                        </h4>

                        @if($order->isCashPayment())

                            <p class="text-sm text-gray-300 mb-2">
                                <strong class="text-white">Pembayaran CASH:</strong>
                            </p>

                            <ul class="text-sm text-gray-300 list-disc list-inside space-y-1">
                                <li>Driver akan membayar harga makanan ke restoran</li>
                                <li>Anda membayar harga makanan + ongkos kirim ke driver</li>
                            </ul>

                        @else

                            <p class="text-sm text-gray-300 mb-2">
                                <strong class="text-white">Pembayaran QRIS:</strong>
                            </p>

                            <ul class="text-sm text-gray-300 list-disc list-inside space-y-1">
                                <li>Makanan sudah dibayar via QRIS</li>
                                <li>Anda hanya membayar ongkos kirim ke driver</li>
                            </ul>

                        @endif

                    </div>

                </div>

            </div>

        @endif

        {{-- ACTIONS --}}
        <div class="flex flex-col sm:flex-row gap-4 justify-center">

            <a
                href="{{ route('frontend.menu') }}"
                class="bg-orange-500 hover:bg-orange-600 px-8 py-4 rounded-2xl font-semibold"
            >
                <i class="fas fa-utensils mr-2"></i>
                Pesan Lagi
            </a>

            <a
                href="{{ route('frontend.home') }}"
                class="border-2 border-gray-600 hover:border-orange-500 px-8 py-4 rounded-2xl font-semibold"
            >
                <i class="fas fa-home mr-2"></i>
                Kembali ke Beranda
            </a>

        </div>

    </div>

</section>

@endsection
