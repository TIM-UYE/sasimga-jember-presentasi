@extends('frontend.layout.app')

@section('content')

<section class="min-h-screen bg-black text-white py-32">

    <div class="max-w-4xl mx-auto px-6">

        <h1 class="text-4xl font-bold mb-10">
            Checkout
        </h1>

        @if(session('error'))
            <div class="mb-6 bg-red-500/20 border border-red-500/30 text-red-400 px-6 py-4 rounded-xl">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 bg-red-500/20 border border-red-500/30 text-red-400 px-6 py-4 rounded-xl">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- ORDER SUMMARY --}}
            <div class="lg:col-span-1 order-2 lg:order-1">

                <div class="bg-zinc-900 rounded-3xl p-6 sticky top-32">

                    <h2 class="text-2xl font-bold mb-6">
                        Ringkasan Pesanan
                    </h2>

                    <div class="space-y-4 mb-6">

                        @foreach($cart as $item)

                            <div class="flex justify-between items-center">

                                <div>

                                    <p class="font-semibold">
                                        {{ $item['nama'] }}
                                    </p>

                                    <p class="text-sm text-gray-400">
                                        {{ $item['qty'] }}x
                                    </p>

                                </div>

                                <p class="text-orange-400 font-semibold">
                                    Rp {{ number_format($item['harga'] * $item['qty'], 0, ',', '.') }}
                                </p>

                            </div>

                        @endforeach

                    </div>

                    <hr class="border-gray-700 my-4">

                    <div class="flex justify-between items-center mb-2">

                        <span class="text-gray-400">Subtotal</span>

                        <span class="font-semibold">
                            Rp {{ number_format($total, 0, ',', '.') }}
                        </span>

                    </div>

                    <div class="flex justify-between items-center mb-2">

                        <span class="text-gray-400">Ongkos Kirim</span>

                        <span id="ongkirStatus" class="font-semibold text-green-400">
                            Tidak ada ongkos kirim
                        </span>

                    </div>

                    <p id="ongkirNote" class="text-xs text-gray-500 mb-4">
                        *Pickup - Ambil sendiri di lokasi
                    </p>

                    <hr class="border-gray-700 my-4">

                    <div class="flex justify-between items-center">

                        <span class="text-xl font-bold">Total</span>

                        <span class="text-2xl font-bold text-orange-400">
                            Rp {{ number_format($total, 0, ',', '.') }}
                        </span>

                    </div>

                </div>

            </div>

            {{-- CHECKOUT FORM --}}
            <div class="lg:col-span-2 order-1 lg:order-2">

                <form action="{{ route('checkout.store') }}" method="POST" class="space-y-6">

                    @csrf

                    {{-- CUSTOMER INFO --}}
                    <div class="bg-zinc-900 rounded-3xl p-6">

                        <h2 class="text-2xl font-bold mb-6">
                            Informasi Pelanggan
                        </h2>

                        <div class="space-y-4">

                            <div>

                                <label class="block text-sm font-semibold mb-2">
                                    Nama Pelanggan
                                </label>

                                <input
                                    type="text"
                                    name="nama_pelanggan"
                                    value="{{ old('nama_pelanggan') }}"
                                    class="w-full bg-zinc-800 border border-gray-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-orange-500"
                                    placeholder="Masukkan nama Anda"
                                    required
                                >

                                @error('nama_pelanggan')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror

                            </div>

                            <div>

                                <label class="block text-sm font-semibold mb-2">
                                    Nomor HP / WhatsApp
                                </label>

                                <input
                                    type="text"
                                    name="nomor_hp"
                                    value="{{ old('nomor_hp') }}"
                                    class="w-full bg-zinc-800 border border-gray-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-orange-500"
                                    placeholder="08xx-xxxx-xxxx"
                                    required
                                >

                                @error('nomor_hp')
                                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                                @enderror

                            </div>

                        </div>

                    </div>

                    {{-- DELIVERY METHOD --}}
                    <div class="bg-zinc-900 rounded-3xl p-6">

                        <h2 class="text-2xl font-bold mb-6">
                            Metode Pengiriman
                        </h2>

                        <div class="grid grid-cols-2 gap-4">

                            <label class="cursor-pointer">

                                <input
                                    type="radio"
                                    name="metode_pengiriman"
                                    value="pickup"
                                    {{ old('metode_pengiriman', 'pickup') === 'pickup' ? 'checked' : '' }}
                                    class="peer sr-only"
                                >

                                <div class="border-2 border-gray-700 rounded-xl p-4 peer-checked:border-orange-500 peer-checked:bg-orange-500/10 transition">

                                    <div class="flex items-center gap-3">

                                        <i class="fas fa-store text-2xl text-gray-400 peer-checked:text-orange-500"></i>

                                        <div>

                                            <p class="font-semibold">Pickup</p>

                                            <p class="text-sm text-gray-400">Ambil sendiri di lokasi</p>

                                        </div>

                                    </div>

                                </div>

                            </label>

                            <label class="cursor-pointer">

                                <input
                                    type="radio"
                                    name="metode_pengiriman"
                                    value="delivery"
                                    {{ old('metode_pengiriman') === 'delivery' ? 'checked' : '' }}
                                    class="peer sr-only"
                                >

                                <div class="border-2 border-gray-700 rounded-xl p-4 peer-checked:border-orange-500 peer-checked:bg-orange-500/10 transition">

                                    <div class="flex items-center gap-3">

                                        <i class="fas fa-motorcycle text-2xl text-gray-400 peer-checked:text-orange-500"></i>

                                        <div>

                                            <p class="font-semibold">Delivery</p>

                                            <p class="text-sm text-gray-400">Diantar ke lokasi Anda</p>

                                        </div>

                                    </div>

                                </div>

                            </label>

                        </div>

                        @error('metode_pengiriman')
                            <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                        @enderror

                    </div>

                    {{-- DELIVERY ADDRESS --}}
                    <div id="address-section" class="bg-zinc-900 rounded-3xl p-6 hidden">

                        <h2 class="text-2xl font-bold mb-6">
                            Alamat Pengiriman
                        </h2>

                        {{-- ADDRESS METHOD FOR CASH PAYMENT --}}
                        <div id="address-method-cash" class="hidden space-y-4">
                            <div class="bg-blue-500/10 border border-blue-500/30 rounded-2xl p-4 mb-4">
                                <div class="flex items-start gap-3">
                                    <i class="fas fa-lock-alt text-blue-400 text-lg mt-1"></i>
                                    <div>
                                        <p class="text-sm text-blue-300 font-semibold">Mode Pembayaran CASH</p>
                                        <p class="text-xs text-gray-300 mt-1">
                                            Alamat akan diambil secara otomatis dari lokasi terkini Anda untuk menghindari pesanan scam.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <button type="button" onclick="getLocationForCash()"
                                class="w-full bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 py-3 rounded-xl font-semibold flex items-center justify-center gap-2">
                                <i class="fas fa-map-marker-alt"></i>
                                <span id="location-btn-cash-text">Ambil Lokasi Terkini</span>
                            </button>

                            <textarea
                                name="alamat"
                                id="alamat_field_cash"
                                rows="3"
                                class="w-full bg-zinc-800 border border-gray-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-green-500 disabled:opacity-50 disabled:cursor-not-allowed"
                                placeholder="Alamat akan otomatis terisi dari lokasi terkini Anda"
                                disabled
                            >{{ old('alamat') }}</textarea>

                            <input type="hidden" name="latitude_cash" id="latitude_cash">
                            <input type="hidden" name="longitude_cash" id="longitude_cash">

                            @error('alamat')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- ADDRESS METHOD FOR QRIS PAYMENT --}}
                        <div id="address-method-qris" class="hidden space-y-4">
                            <div class="space-y-3">
                                <label class="flex items-center cursor-pointer p-4 border-2 border-gray-700 rounded-xl hover:border-orange-500 hover:bg-orange-500/5 transition" id="qris-location-option">
                                    <input
                                        type="radio"
                                        name="alamat_method"
                                        value="location"
                                        class="w-4 h-4 text-orange-500 peer"
                                        onchange="toggleQrisAddressMethod('location')"
                                    >
                                    <span class="ml-3 flex-1">
                                        <span class="font-semibold block">Gunakan Lokasi Terkini</span>
                                        <span class="text-sm text-gray-400">Ambil alamat dari lokasi GPS Anda sekarang</span>
                                    </span>
                                    <i class="fas fa-map-marker-alt text-orange-500 text-xl"></i>
                                </label>

                                <label class="flex items-center cursor-pointer p-4 border-2 border-gray-700 rounded-xl hover:border-purple-500 hover:bg-purple-500/5 transition" id="qris-manual-option">
                                    <input
                                        type="radio"
                                        name="alamat_method"
                                        value="manual"
                                        class="w-4 h-4 text-purple-500 peer"
                                        onchange="toggleQrisAddressMethod('manual')"
                                    >
                                    <span class="ml-3 flex-1">
                                        <span class="font-semibold block">Isi Alamat Sendiri</span>
                                        <span class="text-sm text-gray-400">Masukkan alamat pengiriman secara manual</span>
                                    </span>
                                    <i class="fas fa-pen-square text-purple-500 text-xl"></i>
                                </label>
                            </div>

                            {{-- QRIS Location Input --}}
                            <div id="qris-location-section" class="hidden space-y-3">
                                <button type="button" onclick="getLocationForQris()"
                                    class="w-full bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 py-3 rounded-xl font-semibold flex items-center justify-center gap-2">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span id="location-btn-qris-text">Ambil Lokasi Terkini</span>
                                </button>

                                <textarea
                                    name="alamat_qris_location"
                                    id="alamat_field_qris"
                                    rows="3"
                                    class="w-full bg-zinc-800 border border-green-700/50 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-green-500 disabled:opacity-50 disabled:cursor-not-allowed"
                                    placeholder="Alamat akan otomatis terisi dari lokasi Anda"
                                    disabled
                                >{{ old('alamat_qris_location') }}</textarea>

                                <input type="hidden" name="latitude_qris" id="latitude_qris">
                                <input type="hidden" name="longitude_qris" id="longitude_qris">
                            </div>

                            {{-- QRIS Manual Input --}}
                            <div id="qris-manual-section" class="hidden">
                                <label class="block text-sm font-semibold mb-2">
                                    Alamat Lengkap
                                </label>

                                <textarea
                                    name="alamat_qris_manual"
                                    id="alamat_field_manual"
                                    rows="3"
                                    class="w-full bg-zinc-800 border border-purple-700/50 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-purple-500"
                                    placeholder="Masukkan alamat lengkap untuk pengiriman"
                                >{{ old('alamat_qris_manual') }}</textarea>
                            </div>

                            @error('alamat')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    {{-- PAYMENT METHOD --}}
                    <div class="bg-zinc-900 rounded-3xl p-6">

                        <h2 class="text-2xl font-bold mb-6">
                            Metode Pembayaran
                        </h2>

                        <div class="grid grid-cols-2 gap-4">

                            <label class="cursor-pointer">

                                <input
                                    type="radio"
                                    name="metode_pembayaran"
                                    value="cash"
                                    {{ old('metode_pembayaran', 'cash') === 'cash' ? 'checked' : '' }}
                                    class="peer sr-only"
                                >

                                <div class="border-2 border-gray-700 rounded-xl p-4 peer-checked:border-orange-500 peer-checked:bg-orange-500/10 transition">

                                    <div class="flex items-center gap-3">

                                        <i class="fas fa-money-bill text-2xl text-gray-400 peer-checked:text-orange-500"></i>

                                        <div>

                                            <p class="font-semibold">CASH</p>

                                            <p class="text-sm text-gray-400">Bayar tunai</p>

                                        </div>

                                    </div>

                                </div>

                            </label>

                            <label class="cursor-pointer">

                                <input
                                    type="radio"
                                    name="metode_pembayaran"
                                    value="qris"
                                    {{ old('metode_pembayaran') === 'qris' ? 'checked' : '' }}
                                    class="peer sr-only"
                                >

                                <div class="border-2 border-gray-700 rounded-xl p-4 peer-checked:border-orange-500 peer-checked:bg-orange-500/10 transition">

                                    <div class="flex items-center gap-3">

                                        <i class="fas fa-qrcode text-2xl text-gray-400 peer-checked:text-orange-500"></i>

                                        <div>

                                            <p class="font-semibold">QRIS</p>

                                            <p class="text-sm text-gray-400">Bayar via QR Code</p>

                                        </div>

                                    </div>

                                </div>

                            </label>

                        </div>

                        @error('metode_pembayaran')
                            <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                        @enderror

                    </div>

                    {{-- PAYMENT INFO --}}
                    <div id="payment-info-cash" class="bg-blue-500/10 border border-blue-500/30 rounded-3xl p-6 hidden">
                        <div class="flex items-start gap-4">
                            <i class="fas fa-info-circle text-blue-400 text-2xl mt-1"></i>
                            <div>
                                <h4 class="font-bold text-blue-400 mb-2">
                                    <i class="fas fa-money-bill mr-2"></i>Informasi Pembayaran CASH
                                </h4>
                                <p class="text-sm text-gray-300">
                                    Dengan pembayaran <strong class="text-white">CASH</strong>:
                                </p>
                                <ul class="text-sm text-gray-300 list-disc list-inside space-y-1 mt-2">
                                    <li>Anda membayar <strong class="text-orange-400">harga pesanan + ongkos kirim</strong> secara tunai kepada driver</li>
                                    <li>Pembayaran dilakukan saat pesanan tiba di lokasi Anda</li>
                                    <li>Pastikan menyiapkan uang pas untuk memudahkan transaksi</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div id="payment-info-qris" class="bg-purple-500/10 border border-purple-500/30 rounded-3xl p-6 hidden">
                        <div class="flex items-start gap-4">
                            <i class="fas fa-qrcode text-purple-400 text-2xl mt-1"></i>
                            <div>
                                <h4 class="font-bold text-purple-400 mb-2">
                                    <i class="fas fa-mobile-alt mr-2"></i>Informasi Pembayaran QRIS
                                </h4>
                                <p class="text-sm text-gray-300">
                                    Dengan pembayaran <strong class="text-white">QRIS</strong>:
                                </p>
                                <ul class="text-sm text-gray-300 list-disc list-inside space-y-1 mt-2">
                                    <li>Anda membayar <strong class="text-orange-400">harga pesanan</strong> via QRIS ke restoran</li>
                                    <li><strong class="text-blue-400">Ongkos kirim dibayar terpisah</strong> secara tunai kepada driver</li>
                                    <li>Pembayaran ongkir dilakukan saat pesanan tiba di lokasi Anda</li>
                                    <li>QRIS akan ditampilkan setelah pesanan dikonfirmasi</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    {{-- NOTES --}}
                    <div class="bg-zinc-900 rounded-3xl p-6">

                        <h2 class="text-2xl font-bold mb-6">
                            Catatan Tambahan
                        </h2>

                        <div>

                            <textarea
                                name="catatan"
                                rows="3"
                                class="w-full bg-zinc-800 border border-gray-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-orange-500"
                                placeholder="Catatan untuk pesanan Anda (opsional)"
                            >{{ old('catatan') }}</textarea>

                            @error('catatan')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror

                        </div>

                    </div>

                    {{-- SUBMIT --}}
                    <button
                        type="submit"
                        class="w-full bg-orange-500 hover:bg-orange-600 py-4 rounded-2xl font-bold text-lg"
                    >
                        <i class="fas fa-check-circle mr-2"></i>
                        Buat Pesanan
                    </button>

                    {{-- SUMMARY BUTTON --}}
                    <button
                        type="button"
                        onclick="openSummaryModal()"
                        class="w-full bg-zinc-800 hover:bg-zinc-700 border-2 border-gray-600 py-4 rounded-2xl font-bold text-lg"
                    >
                        <i class="fas fa-list-alt mr-2"></i>
                        Ringkasan Pilihan
                    </button>

                </form>

            </div>

        </div>

    </div>

</section>

{{-- SUMMARY MODAL --}}
<div id="summaryModal" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4" onclick="closeSummaryModal(event)">
    <div class="bg-zinc-900 rounded-3xl max-w-2xl w-full max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
        {{-- MODAL HEADER --}}
        <div class="sticky top-0 bg-zinc-900 border-b border-gray-700 px-6 py-4 flex items-center justify-between">
            <h2 class="text-2xl font-bold text-white">
                <i class="fas fa-clipboard-list mr-2 text-orange-500"></i>
                Ringkasan Pilihan
            </h2>
            <button onclick="closeSummaryModal()" class="text-gray-400 hover:text-white text-2xl">
                &times;
            </button>
        </div>

        {{-- MODAL BODY --}}
        <div class="p-6">
            {{-- Customer Info --}}
            <div class="mb-6">
                <h3 class="text-lg font-bold text-orange-500 mb-3">
                    <i class="fas fa-user mr-2"></i>Informasi Pelanggan
                </h3>
                <div class="bg-zinc-800 rounded-xl p-4 space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Nama</span>
                        <span class="font-semibold" id="summaryNama">-</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Nomor HP</span>
                        <span class="font-semibold" id="summaryHp">-</span>
                    </div>
                </div>
            </div>

            {{-- Delivery Method --}}
            <div class="mb-6">
                <h3 class="text-lg font-bold text-orange-500 mb-3">
                    <i class="fas fa-shipping-fast mr-2"></i>Metode Pengiriman
                </h3>
                <div class="bg-zinc-800 rounded-xl p-4">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400">Pengiriman</span>
                        <span class="font-semibold" id="summaryPengiriman">-</span>
                    </div>
                    <div class="flex justify-between items-center mt-2" id="summaryAlamatRow" style="display: none;">
                        <span class="text-gray-400">Alamat</span>
                        <span class="font-semibold text-right ml-4" id="summaryAlamat">-</span>
                    </div>
                </div>
            </div>

            {{-- Payment Method --}}
            <div class="mb-6">
                <h3 class="text-lg font-bold text-orange-500 mb-3">
                    <i class="fas fa-wallet mr-2"></i>Metode Pembayaran
                </h3>
                <div class="bg-zinc-800 rounded-xl p-4">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400">Pembayaran</span>
                        <span class="font-semibold" id="summaryPembayaran">-</span>
                    </div>
                </div>
            </div>

            {{-- Order Items --}}
            <div class="mb-6">
                <h3 class="text-lg font-bold text-orange-500 mb-3">
                    <i class="fas fa-shopping-cart mr-2"></i>Pesanan
                </h3>
                <div class="bg-zinc-800 rounded-xl p-4">
                    <div class="space-y-3">
                        @foreach($cart as $item)
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="font-semibold">{{ $item['nama'] }}</p>
                                    <p class="text-sm text-gray-400">{{ $item['qty'] }}x @ Rp {{ number_format($item['harga'], 0, ',', '.') }}</p>
                                </div>
                                <p class="text-orange-400 font-semibold">Rp {{ number_format($item['harga'] * $item['qty'], 0, ',', '.') }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Notes --}}
            @if(old('catatan'))
                <div class="mb-6">
                    <h3 class="text-lg font-bold text-orange-500 mb-3">
                        <i class="fas fa-sticky-note mr-2"></i>Catatan
                    </h3>
                    <div class="bg-zinc-800 rounded-xl p-4">
                        <p class="text-gray-300">{{ old('catatan') }}</p>
                    </div>
                </div>
            @endif

            {{-- Total --}}
            <div class="bg-orange-500/10 border border-orange-500/30 rounded-xl p-4">
                <div class="flex justify-between items-center">
                    <span class="text-lg font-bold">Total Bayar</span>
                    <span class="text-2xl font-bold text-orange-400">Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Update ongkir status based on delivery method
    function updateOngkirStatus() {
        const pengiriman = document.querySelector('input[name="metode_pengiriman"]:checked');
        const ongkirStatus = document.getElementById('ongkirStatus');
        const ongkirNote = document.getElementById('ongkirNote');

        if (pengiriman && pengiriman.value === 'delivery') {
            ongkirStatus.textContent = 'seusuai harga dari driver*';
            ongkirStatus.className = 'font-semibold text-blue-400';
            ongkirNote.textContent = '*Ongkos kirim ditentukan driver, bayar saat pesanan tiba di lokasi';
            ongkirNote.className = 'text-xs text-blue-400/70 mb-4';
        } else {
            ongkirStatus.textContent = 'Tidak ada ongkos kirim';
            ongkirStatus.className = 'font-semibold text-green-400';
            ongkirNote.textContent = '*Pickup - Ambil sendiri di lokasi';
            ongkirNote.className = 'text-xs text-gray-500 mb-4';
        }
    }

    // Toggle address field and ongkir status based on delivery method
    document.querySelectorAll('input[name="metode_pengiriman"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const addressSection = document.getElementById('address-section');
            if (this.value === 'delivery') {
                addressSection.classList.remove('hidden');
            } else {
                addressSection.classList.add('hidden');
            }
            updateOngkirStatus();
            updateAddressMethods();
        });
    });

    // Initial state
    if (document.querySelector('input[name="metode_pengiriman"]:checked')?.value === 'delivery') {
        document.getElementById('address-section').classList.remove('hidden');
        updateOngkirStatus();
        updateAddressMethods();
    }

    // Summary Modal Functions
    function openSummaryModal() {
        // Get form values
        const nama = document.querySelector('input[name="nama_pelanggan"]').value || '-';
        const hp = document.querySelector('input[name="nomor_hp"]').value || '-';
        const pengiriman = document.querySelector('input[name="metode_pengiriman"]:checked');
        const pembayaran = document.querySelector('input[name="metode_pembayaran"]:checked');
        const alamat = document.querySelector('textarea[name="alamat"]').value || '-';

        // Set values
        document.getElementById('summaryNama').textContent = nama;
        document.getElementById('summaryHp').textContent = hp;
        document.getElementById('summaryPengiriman').textContent = pengiriman ? (pengiriman.value === 'delivery' ? 'Delivery' : 'Pickup') : '-';
        document.getElementById('summaryPembayaran').textContent = pembayaran ? (pembayaran.value === 'cash' ? 'CASH' : 'QRIS') : '-';

        // Show/hide address
        const alamatRow = document.getElementById('summaryAlamatRow');
        if (pengiriman && pengiriman.value === 'delivery') {
            alamatRow.style.display = 'flex';
            document.getElementById('summaryAlamat').textContent = alamat;
        } else {
            alamatRow.style.display = 'none';
        }

        // Show modal
        document.getElementById('summaryModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeSummaryModal(event) {
        if (event && event.target.id !== 'summaryModal') return;
        document.getElementById('summaryModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Update payment info based on payment method
    function updatePaymentInfo() {
        const pembayaran = document.querySelector('input[name="metode_pembayaran"]:checked');
        const infoCash = document.getElementById('payment-info-cash');
        const infoQris = document.getElementById('payment-info-qris');

        // Hide both first
        infoCash.classList.add('hidden');
        infoQris.classList.add('hidden');

        if (pembayaran) {
            if (pembayaran.value === 'cash') {
                infoCash.classList.remove('hidden');
            } else if (pembayaran.value === 'qris') {
                infoQris.classList.remove('hidden');
            }
        }

        // Update address methods based on payment method
        updateAddressMethods();
    }

    // Update address methods based on payment method
    function updateAddressMethods() {
        const pengiriman = document.querySelector('input[name="metode_pengiriman"]:checked');
        const pembayaran = document.querySelector('input[name="metode_pembayaran"]:checked');
        const addressMethodCash = document.getElementById('address-method-cash');
        const addressMethodQris = document.getElementById('address-method-qris');

        // Hide both methods first
        addressMethodCash.classList.add('hidden');
        addressMethodQris.classList.add('hidden');

        // Show appropriate method if delivery is selected
        if (pengiriman && pengiriman.value === 'delivery') {
            if (pembayaran) {
                if (pembayaran.value === 'cash') {
                    addressMethodCash.classList.remove('hidden');
                } else if (pembayaran.value === 'qris') {
                    addressMethodQris.classList.remove('hidden');
                    // Set default for QRIS if not set
                    if (!document.querySelector('input[name="alamat_method"]:checked')) {
                        document.querySelector('input[name="alamat_method"][value="location"]').checked = true;
                        toggleQrisAddressMethod('location');
                    }
                }
            }
        }
    }

    // Toggle between location and manual for QRIS
    function toggleQrisAddressMethod(method) {
        const locationSection = document.getElementById('qris-location-section');
        const manualSection = document.getElementById('qris-manual-section');

        if (method === 'location') {
            locationSection.classList.remove('hidden');
            manualSection.classList.add('hidden');
        } else {
            locationSection.classList.add('hidden');
            manualSection.classList.remove('hidden');
        }
    }

    // Get location for CASH payment
    function getLocationForCash() {
        const btn = event.target.closest('button');
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span id="location-btn-cash-text">Mengambil lokasi...</span>';

        if ('geolocation' in navigator) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const lat = position.coords.latitude;
                    const lon = position.coords.longitude;

                    document.getElementById('latitude_cash').value = lat;
                    document.getElementById('longitude_cash').value = lon;

                    // Reverse geocoding to get address
                    reverseGeocode(lat, lon, function(address) {
                        document.getElementById('alamat_field_cash').value = address || `Lat: ${lat.toFixed(4)}, Lon: ${lon.toFixed(4)}`;
                        btn.disabled = false;
                        btn.innerHTML = originalText;
                    });
                },
                function(error) {
                    alert('Error mendapatkan lokasi: ' + error.message);
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                },
                {
                    enableHighAccuracy: true,
                    timeout: 5000,
                    maximumAge: 0
                }
            );
        } else {
            alert('Geolocation tidak didukung oleh browser Anda');
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    }

    // Get location for QRIS payment
    function getLocationForQris() {
        const btn = event.target.closest('button');
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span id="location-btn-qris-text">Mengambil lokasi...</span>';

        if ('geolocation' in navigator) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const lat = position.coords.latitude;
                    const lon = position.coords.longitude;

                    document.getElementById('latitude_qris').value = lat;
                    document.getElementById('longitude_qris').value = lon;

                    // Reverse geocoding to get address
                    reverseGeocode(lat, lon, function(address) {
                        document.getElementById('alamat_field_qris').value = address || `Lat: ${lat.toFixed(4)}, Lon: ${lon.toFixed(4)}`;
                        btn.disabled = false;
                        btn.innerHTML = originalText;
                    });
                },
                function(error) {
                    alert('Error mendapatkan lokasi: ' + error.message);
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                },
                {
                    enableHighAccuracy: true,
                    timeout: 5000,
                    maximumAge: 0
                }
            );
        } else {
            alert('Geolocation tidak didukung oleh browser Anda');
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    }

    // Reverse geocoding using OpenStreetMap Nominatim API
    function reverseGeocode(lat, lon, callback) {
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}`)
            .then(response => response.json())
            .then(data => {
                const address = data.address_components ?
                    `${data.address.road || ''} ${data.address.house_number || ''}, ${data.address.city || data.address.town || ''}, ${data.address.province || ''}, ${data.address.postcode || ''}`.trim() :
                    data.display_name;
                callback(address);
            })
            .catch(error => {
                console.error('Reverse geocoding error:', error);
                callback(null);
            });
    }

    // Add event listeners for payment method
    document.querySelectorAll('input[name="metode_pembayaran"]').forEach(radio => {
        radio.addEventListener('change', updatePaymentInfo);
    });

    // Initial state for payment info
    updatePaymentInfo();

    // Close on escape
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeSummaryModal();
        }
    });
</script>

@endsection
