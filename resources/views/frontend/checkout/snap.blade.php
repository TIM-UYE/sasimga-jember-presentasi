@extends('frontend.layout.app')

@section('content')

<section class="min-h-screen bg-black text-white py-32">

    <div class="max-w-2xl mx-auto px-6">

        <div class="text-center mb-10">
            <h1 class="text-4xl font-bold mb-4">
                Pembayaran
            </h1>
            <p class="text-gray-400">
                Klik tombol di bawah untuk membuka popup pembayaran
            </p>
        </div>

        @if(session('error'))
            <div class="mb-6 bg-red-500/20 border border-red-500/30 text-red-400 px-6 py-4 rounded-xl">
                {{ session('error') }}
            </div>
        @endif

        @if($error)
            <div class="mb-6 bg-red-500/20 border border-red-500/30 text-red-400 px-6 py-4 rounded-xl">
                <p class="font-semibold mb-2">
                    <i class="fas fa-exclamation-circle mr-2"></i>Gagal Membuat Pembayaran
                </p>
                <p class="text-sm">{{ $error }}</p>
                <div class="mt-4">
                    <a href="{{ route('checkout.index') }}" class="text-orange-400 hover:text-orange-300 underline">
                        Kembali ke checkout
                    </a>
                </div>
            </div>
        @else

            {{-- Order Info --}}
            <div class="bg-zinc-900 rounded-3xl p-6 mb-6">
                <div class="flex justify-between items-center mb-4">
                    <span class="text-gray-400">Order ID</span>
                    <span class="font-semibold text-orange-400" id="orderId">{{ $order->kode_order }}</span>
                </div>
                <div class="flex justify-between items-center mb-4">
                    <span class="text-gray-400">Total Pembayaran</span>
                    <span class="text-2xl font-bold text-orange-400">
                        Rp {{ number_format($order->total_bayar, 0, ',', '.') }}
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-400">Status</span>
                    <span class="px-3 py-1 rounded-full text-sm font-semibold bg-yellow-500/20 text-yellow-400" id="paymentStatus">
                        <i class="fas fa-clock mr-1"></i>Menunggu Pembayaran
                    </span>
                </div>
            </div>

            {{-- Snap Pay Button --}}
            <div class="bg-zinc-900 rounded-3xl p-8 mb-6 text-center">
                <div class="mb-6">
                    <i class="fas fa-credit-card text-6xl text-orange-400 mb-4"></i>
                    <h2 class="text-2xl font-bold mb-2">Pilih Metode Pembayaran</h2>
                    <p class="text-gray-400">
                        Tersedia QRIS, GoPay, Bank Transfer, dan lainnya
                    </p>
                </div>

                <button
                    id="payButton"
                    onclick="payNow()"
                    class="w-full bg-orange-500 hover:bg-orange-600 py-4 rounded-2xl font-bold text-lg transition"
                >
                    <i class="fas fa-credit-card mr-2"></i>
                    Bayar Sekarang
                </button>

                <p class="text-xs text-gray-500 mt-4">
                    <i class="fas fa-lock mr-1"></i>
                    Pembayaran diproses secara aman oleh Midtrans
                </p>
            </div>

            {{-- Payment Methods Info --}}
            <div class="bg-zinc-900 rounded-3xl p-6 mb-6">
                <h3 class="text-lg font-bold mb-4">
                    <i class="fas fa-credit-card text-orange-400 mr-2"></i>
                    Metode Pembayaran Tersedia
                </h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                    <div class="bg-zinc-800 rounded-xl p-3 text-center">
                        <i class="fas fa-qrcode text-2xl text-purple-400 mb-2"></i>
                        <p class="text-xs font-semibold">QRIS</p>
                    </div>
                    <div class="bg-zinc-800 rounded-xl p-3 text-center">
                        <i class="fas fa-mobile-alt text-2xl text-green-400 mb-2"></i>
                        <p class="text-xs font-semibold">GoPay</p>
                    </div>
                    <div class="bg-zinc-800 rounded-xl p-3 text-center">
                        <i class="fas fa-university text-2xl text-blue-400 mb-2"></i>
                        <p class="text-xs font-semibold">Bank Transfer</p>
                    </div>
                    <div class="bg-zinc-800 rounded-xl p-3 text-center">
                        <i class="fas fa-credit-card text-2xl text-red-400 mb-2"></i>
                        <p class="text-xs font-semibold">Kartu Kredit</p>
                    </div>
                    <div class="bg-zinc-800 rounded-xl p-3 text-center">
                        <i class="fas fa-store text-2xl text-yellow-400 mb-2"></i>
                        <p class="text-xs font-semibold">Indomaret</p>
                    </div>
                    <div class="bg-zinc-800 rounded-xl p-3 text-center">
                        <i class="fas fa-mobile text-2xl text-teal-400 mb-2"></i>
                        <p class="text-xs font-semibold">OVO / DANA</p>
                    </div>
                </div>
            </div>

            {{-- Auto-check Status Indicator --}}
            <div class="text-center text-sm text-gray-500 mb-6">
                <i class="fas fa-spinner fa-spin mr-2" id="checkingIcon"></i>
                <span id="checkingText">Menunggu pembayaran Anda...</span>
            </div>

            {{-- Back Link --}}
            <div class="text-center">
                <a
                    href="{{ route('frontend.menu') }}"
                    class="text-gray-500 hover:text-gray-300 underline text-sm"
                >
                    <i class="fas fa-arrow-left mr-1"></i>
                    Kembali ke menu
                </a>
            </div>

        @endif

    </div>

</section>

{{-- Success Modal --}}
<div id="successModal" class="hidden fixed inset-0 bg-black/80 z-50 flex items-center justify-center p-4">
    <div class="bg-zinc-900 rounded-3xl max-w-md w-full p-8 text-center">
        <div class="w-20 h-20 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-check text-white text-4xl"></i>
        </div>
        <h2 class="text-2xl font-bold mb-2">Pembayaran Berhasil!</h2>
        <p class="text-gray-400 mb-6">
            Pesanan Anda telah dikonfirmasi dan sedang diproses.
        </p>
        <div class="bg-zinc-800 rounded-xl p-4 mb-6">
            <div class="flex justify-between mb-2">
                <span class="text-gray-400">Order ID</span>
                <span class="font-semibold">{{ $order->kode_order }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-400">Total Dibayar</span>
                <span class="font-semibold text-green-400">Rp {{ number_format($order->total_bayar, 0, ',', '.') }}</span>
            </div>
        </div>
        <a
            href="{{ route('payment.success', $order->kode_order) }}"
            class="block w-full bg-orange-500 hover:bg-orange-600 py-4 rounded-2xl font-bold text-lg transition"
        >
            <i class="fas fa-check-circle mr-2"></i>
            Lihat Detail Pesanan
        </a>
    </div>
</div>

{{-- Midtrans Snap JS --}}
<script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}">
</script>

<script>
    const snapToken = '{{ $snapToken }}';
    const kodeOrder = '{{ $order->kode_order }}';
    const checkStatusUrl = '{{ route("payment.snap.status", $order->kode_order) }}';
    const successUrl = '{{ route("payment.success", $order->kode_order) }}';

    let checkInterval = null;

    // Open Snap payment popup
    function payNow() {
        const payBtn = document.getElementById('payButton');
        payBtn.disabled = true;
        payBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memuat...';

        window.snap.pay(snapToken, {
            onSuccess: function(result) {
                console.log('Payment success:', result);
                payBtn.innerHTML = '<i class="fas fa-check mr-2"></i>Pembayaran Berhasil';
                checkPaymentStatus();
                startStatusCheck();
            },
            onPending: function(result) {
                console.log('Payment pending:', result);
                payBtn.disabled = false;
                payBtn.innerHTML = '<i class="fas fa-credit-card mr-2"></i>Bayar Sekarang';
                document.getElementById('checkingText').textContent = 'Menunggu pembayaran Anda...';
                startStatusCheck();
            },
            onError: function(result) {
                console.error('Payment error:', result);
                payBtn.disabled = false;
                payBtn.innerHTML = '<i class="fas fa-credit-card mr-2"></i>Bayar Sekarang';
                alert('Terjadi kesalahan pembayaran: ' + (result.status_message || 'Unknown error'));
            },
            onClose: function() {
                console.log('Snap popup closed');
                payBtn.disabled = false;
                payBtn.innerHTML = '<i class="fas fa-credit-card mr-2"></i>Bayar Sekarang';
                startStatusCheck();
            }
        });
    }

    // Start checking payment status
    function startStatusCheck() {
        if (checkInterval) return;

        checkInterval = setInterval(checkPaymentStatus, 5000);
    }

    // Check payment status via AJAX
    function checkPaymentStatus() {
        fetch(checkStatusUrl)
            .then(response => response.json())
            .then(data => {
                if (data.paid) {
                    clearInterval(checkInterval);
                    checkInterval = null;
                    showSuccessModal();
                } else {
                    const checkingText = document.getElementById('checkingText');
                    const dots = checkingText.dataset.dots || '';
                    if (dots.length >= 3) {
                        checkingText.dataset.dots = '';
                        checkingText.textContent = 'Menunggu pembayaran Anda...';
                    } else {
                        checkingText.dataset.dots = dots + '.';
                        checkingText.textContent = 'Menunggu pembayaran Anda' + dots + '.';
                    }
                }
            })
            .catch(error => console.error('Check status error:', error));
    }

    // Show success modal
    function showSuccessModal() {
        document.getElementById('successModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        document.getElementById('paymentStatus').innerHTML = '<i class="fas fa-check-circle mr-1"></i>Lunas';
        document.getElementById('paymentStatus').className = 'px-3 py-1 rounded-full text-sm font-semibold bg-green-500/20 text-green-400';
    }

    // Auto open snap after page loads
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(payNow, 1000);
    });
</script>

@endsection
