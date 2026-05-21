@extends('frontend.layout.app')

@section('content')

<section class="min-h-screen bg-black text-white py-32">

    <div class="max-w-2xl mx-auto px-6">

        <div class="text-center mb-10">
            <h1 class="text-4xl font-bold mb-4">
                Pembayaran QRIS
            </h1>
            <p class="text-gray-400">
                Scan QR code di bawah untuk melakukan pembayaran
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

            {{-- Countdown Timer --}}
            <div class="bg-zinc-900 rounded-3xl p-6 mb-6">
                <div class="text-center">
                    <p class="text-gray-400 mb-3">
                        <i class="fas fa-hourglass-half mr-2"></i>Selesaikan pembayaran sebelum:
                    </p>
                    <div id="countdown" class="text-3xl font-bold text-orange-400 font-mono">
                        @if($paymentData && $paymentData['expiry_time'])
                            {{ $paymentData['expiry_time']->diffForHumans(['parts' => 2]) }}
                        @else
                            --
                        @endif
                    </div>
                    <p class="text-sm text-gray-500 mt-2">
                        Waktu tersisa untuk melakukan pembayaran
                    </p>
                </div>
            </div>

            {{-- QRIS Display --}}
            <div class="bg-zinc-900 rounded-3xl p-8 mb-6">
                <div class="flex flex-col items-center">
                    <div class="bg-white rounded-2xl p-4 mb-6 shadow-lg" id="qrisContainer">
                        @if($paymentData && $paymentData['qr_image_url'])
                            <img
                                src="{{ $paymentData['qr_image_url'] }}"
                                alt="QRIS Payment Code"
                                class="w-64 h-64 object-contain"
                                id="qrisImage"
                            >
                        @else
                            <div class="w-64 h-64 flex items-center justify-center bg-gray-100 rounded-xl">
                                <div class="text-center text-gray-400">
                                    <i class="fas fa-qrcode text-6xl mb-4"></i>
                                    <p class="text-sm">Memuat QR Code...</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <p class="text-sm text-gray-400 mb-4 text-center">
                        <i class="fas fa-info-circle mr-1"></i>
                        Scan QR code di atas menggunakan aplikasi e-wallet Anda (GoPay, OVO, DANA, dll.)
                    </p>

                    {{-- Action Buttons --}}
                    <div class="flex gap-4">
                        <button
                            type="button"
                            onclick="refreshQris()"
                            id="refreshBtn"
                            class="bg-zinc-800 hover:bg-zinc-700 border-2 border-gray-600 px-6 py-3 rounded-xl font-semibold transition"
                        >
                            <i class="fas fa-sync-alt mr-2"></i>
                            Refresh QR
                        </button>

                        <a
                            href="{{ $paymentData && $paymentData['qr_image_url'] ? $paymentData['qr_image_url'] : '#' }}"
                            download="qris-{{ $order->kode_order }}.png"
                            id="downloadBtn"
                            class="bg-orange-500 hover:bg-orange-600 px-6 py-3 rounded-xl font-semibold transition"
                        >
                            <i class="fas fa-download mr-2"></i>
                            Download QR
                        </a>
                    </div>
                </div>
            </div>

            {{-- Payment Instructions --}}
            <div class="bg-zinc-900 rounded-3xl p-6 mb-6">
                <h3 class="text-lg font-bold mb-4">
                    <i class="fas fa-info-circle text-orange-400 mr-2"></i>
                    Cara Pembayaran
                </h3>
                <ol class="space-y-3 text-sm text-gray-300">
                    <li class="flex gap-3">
                        <span class="text-orange-400 font-bold">1.</span>
                        <span>Buka aplikasi e-wallet Anda (GoPay, OVO, DANA, ShopeePay, LinkAja)</span>
                    </li>
                    <li class="flex gap-3">
                        <span class="text-orange-400 font-bold">2.</span>
                        <span>Pilih menu "Bayar" atau "Scan QR"</span>
                    </li>
                    <li class="flex gap-3">
                        <span class="text-orange-400 font-bold">3.</span>
                        <span>Scan QR code di atas</span>
                    </li>
                    <li class="flex gap-3">
                        <span class="text-orange-400 font-bold">4.</span>
                        <span>Pastikan nominal <strong class="text-orange-400">Rp {{ number_format($order->total_bayar, 0, ',', '.') }}</strong> sesuai</span>
                    </li>
                    <li class="flex gap-3">
                        <span class="text-orange-400 font-bold">5.</span>
                        <span>Konfirmasi pembayaran dan masukkan PIN Anda</span>
                    </li>
                    <li class="flex gap-3">
                        <span class="text-orange-400 font-bold">6.</span>
                        <span>Halaman ini akan otomatis terupdate setelah pembayaran berhasil</span>
                    </li>
                </ol>
            </div>

            {{-- Auto-check Status Indicator --}}
            <div class="text-center text-sm text-gray-500 mb-6">
                <i class="fas fa-spinner fa-spin mr-2" id="checkingIcon"></i>
                <span id="checkingText">Menunggu pembayaran Anda...</span>
            </div>

            {{-- Cancel Order --}}
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

{{-- Expired Modal --}}
<div id="expiredModal" class="hidden fixed inset-0 bg-black/80 z-50 flex items-center justify-center p-4">
    <div class="bg-zinc-900 rounded-3xl max-w-md w-full p-8 text-center">
        <div class="w-20 h-20 bg-red-500 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-times text-white text-4xl"></i>
        </div>
        <h2 class="text-2xl font-bold mb-2">Pembayaran Expired</h2>
        <p class="text-gray-400 mb-6">
            Waktu pembayaran telah habis. Silakan lakukan pemesanan ulang.
        </p>
        <a
            href="{{ route('frontend.menu') }}"
            class="block w-full bg-orange-500 hover:bg-orange-600 py-4 rounded-2xl font-bold text-lg transition"
        >
            <i class="fas fa-shopping-cart mr-2"></i>
            Pesan Lagi
        </a>
    </div>
</div>

<script>
    // Configuration
    const kodeOrder = '{{ $order->kode_order }}';
    const checkStatusUrl = '{{ route("payment.qris.status", $order->kode_order) }}';
    const regenerateUrl = '{{ route("payment.qris.regenerate", $order->kode_order) }}';
    const successUrl = '{{ route("payment.success", $order->kode_order) }}';

    // State
    let checkInterval = null;
    let countdownInterval = null;
    let expiryTime = @if($paymentData && $paymentData['expiry_time'])
        new Date('{{ $paymentData["expiry_time"]->format("Y-m-d H:i:s") }}').getTime();
    @else
        null;
    @endif

    // Countdown Timer
    function updateCountdown() {
        if (!expiryTime) return;

        const now = new Date().getTime();
        const distance = expiryTime - now;

        if (distance <= 0) {
            document.getElementById('countdown').textContent = 'WAKTU HABIS';
            document.getElementById('countdown').classList.remove('text-orange-400');
            document.getElementById('countdown').classList.add('text-red-500');
            clearInterval(countdownInterval);
            showExpiredModal();
            return;
        }

        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        document.getElementById('countdown').textContent =
            String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0');
    }

    // Check Payment Status
    function checkPaymentStatus() {
        fetch(checkStatusUrl)
            .then(response => response.json())
            .then(data => {
                if (data.paid) {
                    // Payment successful!
                    clearInterval(checkInterval);
                    clearInterval(countdownInterval);
                    showSuccessModal();
                } else if (data.status === 'expired') {
                    clearInterval(checkInterval);
                    clearInterval(countdownInterval);
                    showExpiredModal();
                } else {
                    // Still pending, update checking text
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
            .catch(error => {
                console.error('Error checking payment status:', error);
            });
    }

    // Show Success Modal
    function showSuccessModal() {
        document.getElementById('successModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    // Show Expired Modal
    function showExpiredModal() {
        document.getElementById('expiredModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        document.getElementById('paymentStatus').innerHTML = '<i class="fas fa-times-circle mr-1"></i>Expired';
        document.getElementById('paymentStatus').className = 'px-3 py-1 rounded-full text-sm font-semibold bg-red-500/20 text-red-400';
    }

    // Refresh QRIS
    function refreshQris() {
        const refreshBtn = document.getElementById('refreshBtn');
        refreshBtn.disabled = true;
        refreshBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memuat...';

        fetch(regenerateUrl, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update QR image
                const qrisImage = document.getElementById('qrisImage');
                if (qrisImage) {
                    qrisImage.src = data.qr_image_url + '?t=' + new Date().getTime();
                }

                // Update download link
                const downloadBtn = document.getElementById('downloadBtn');
                if (downloadBtn) {
                    downloadBtn.href = data.qr_image_url;
                }

                // Update expiry time
                if (data.expiry_time) {
                    expiryTime = new Date(data.expiry_time.replace(' ', 'T')).getTime();
                    document.getElementById('countdown').classList.remove('text-red-500');
                    document.getElementById('countdown').classList.add('text-orange-400');
                    updateCountdown();
                }

                // Update payment status back to pending
                document.getElementById('paymentStatus').innerHTML = '<i class="fas fa-clock mr-1"></i>Menunggu Pembayaran';
                document.getElementById('paymentStatus').className = 'px-3 py-1 rounded-full text-sm font-semibold bg-yellow-500/20 text-yellow-400';
            } else {
                alert('Gagal memperbarui QR: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error refreshing QRIS:', error);
            alert('Gagal memperbarui QR. Silakan coba lagi.');
        })
        .finally(() => {
            refreshBtn.disabled = false;
            refreshBtn.innerHTML = '<i class="fas fa-sync-alt mr-2"></i>Refresh QR';
        });
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        // Start countdown
        if (expiryTime) {
            updateCountdown();
            countdownInterval = setInterval(updateCountdown, 1000);
        }

        // Start checking payment status every 5 seconds
        checkPaymentStatus();
        checkInterval = setInterval(checkPaymentStatus, 5000);

        // Close modals on escape
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                document.getElementById('successModal').classList.add('hidden');
                document.getElementById('expiredModal').classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        });
    });
</script>

@endsection
