@extends('frontend.layout.app')

@section('content')
    <section class="min-h-screen bg-black text-white py-32">
        <div class="max-w-5xl mx-auto px-6">

            <h1 class="text-4xl font-bold mb-10">
                Keranjang Saya
            </h1>

            @php $total = 0; @endphp

            @if(count($cart) > 0)

                <div class="space-y-6">

                    @foreach($cart as $key => $item)
                        @php
                            $subtotal = $item['harga'] * $item['qty'];
                            $total += $subtotal;
                        @endphp

                        <div id="cart-item-{{ $key }}" class="bg-zinc-900 rounded-3xl p-6">

                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">

                                <div class="flex items-center gap-4 flex-1">

                                    {{-- MENU IMAGE --}}
                                    <div class="w-20 h-20 rounded-xl overflow-hidden flex-shrink-0 bg-gray-800">
                                        @if(isset($item['gambar']) && $item['gambar'])
                                            <img
                                                src="{{ asset('storage/menu/' . $item['gambar']) }}"
                                                alt="{{ $item['nama'] }}"
                                                class="w-full h-full object-cover"
                                            >
                                        @else
                                            <div class="w-full h-full flex items-center justify-center">
                                                <i class="fas fa-utensils text-gray-600 text-xl"></i>
                                            </div>
                                        @endif
                                    </div>

                                    <div>
                                        <h2 class="text-xl font-semibold">
                                            {{ $item['nama'] }}
                                        </h2>

                                        <p class="text-orange-400 mt-1">
                                            Rp {{ number_format($item['harga'], 0, ',', '.') }}
                                        </p>
                                    </div>

                                </div>

                                {{-- Quantity Controls (AJAX) --}}
                                <div class="flex items-center gap-4">

                                    <div class="flex items-center gap-2">
                                        <button type="button" onclick="updateCartQty('{{ $key }}', 'decrement', this)"
                                            class="w-8 h-8 rounded-full bg-gray-700 hover:bg-gray-600 flex items-center justify-center text-white font-bold">
                                            -
                                        </button>

                                        <span id="qty-display-{{ $key }}" class="text-white font-semibold w-12 text-center">
                                            {{ $item['qty'] }}
                                        </span>

                                        <button type="button" onclick="updateCartQty('{{ $key }}', 'increment', this)"
                                            class="w-8 h-8 rounded-full bg-gray-700 hover:bg-gray-600 flex items-center justify-center text-white font-bold">
                                            +
                                        </button>
                                    </div>

                                    <div class="text-right min-w-[100px]">
                                        <p class="text-sm text-gray-400">Subtotal</p>
                                        <p id="subtotal-{{ $key }}" class="text-lg font-bold text-orange-400">
                                            Rp {{ number_format($subtotal, 0, ',', '.') }}
                                        </p>
                                    </div>

                                    <button type="button" onclick="removeCartItem('{{ $key }}', this)"
                                        class="bg-red-500 hover:bg-red-600 px-4 py-2 rounded-xl text-sm">
                                        <i class="fas fa-trash mr-2"></i>
                                        Hapus
                                    </button>

                                </div>

                            </div>

                        </div>

                    @endforeach

                </div>

                <div class="mt-10 bg-zinc-900 rounded-3xl p-6">

                    <div class="flex justify-between items-center mb-4">

                        <h2 class="text-2xl font-bold">
                            Total
                        </h2>

                        <p id="cart-total" class="text-3xl font-bold text-orange-400">
                            Rp {{ number_format($total, 0, ',', '.') }}
                        </p>

                    </div>

                    <div class="flex gap-4">
                        <a
                            href="{{ route('checkout.index') }}"
                            class="flex-1 bg-orange-500 hover:bg-orange-600 py-4 rounded-2xl font-semibold text-center"
                        >
                            Checkout Sekarang
                        </a>

                        <a
                            href="{{ route('frontend.menu') }}"
                            class="px-6 py-4 border-2 border-gray-600 hover:border-orange-500 rounded-2xl font-semibold"
                        >
                            <i class="fas fa-arrow-left mr-2"></i>
                            Lanjut Belanja
                        </a>
                    </div>

                </div>

            @else

                <div class="text-center py-20">

                    <i class="fa-solid fa-cart-shopping text-6xl text-white/20"></i>

                    <p class="mt-6 text-white/60">
                        Keranjang masih kosong
                    </p>

                    <a
                        href="{{ route('frontend.menu') }}"
                        class="inline-block mt-6 bg-orange-500 hover:bg-orange-600 px-6 py-3 rounded-xl font-semibold"
                    >
                        <i class="fas fa-utensils mr-2"></i>
                        Lihat Menu
                    </a>

                </div>

            @endif

        </div>
    </section>
@endsection

@push('scripts')
<script>
    const csrfToken = '{{ csrf_token() }}';

    async function updateCartQty(key, action, button) {
        button.disabled = true;
        const originalHTML = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

        try {
            const response = await fetch(`/cart/${action}/${key}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const data = await response.json();

            if (data.success) {
                // Update cart badge only, do not replace icon content
                document.getElementById('cartBadge').textContent = data.cart.count;
                document.querySelectorAll('.cart-count-badge').forEach(el => {
                    el.textContent = data.cart.count;
                });

                // Update floating checkout if exists
                if (document.getElementById('floatingTotal')) {
                    document.getElementById('floatingTotal').textContent = data.cart.total_formatted;
                }

                // Update total
                document.getElementById('cart-total').textContent = data.cart.total_formatted;
            }
        } catch (error) {
            console.error('Cart update error:', error);
        } finally {
            button.disabled = false;
            button.innerHTML = originalHTML;
        }
    }

    async function removeCartItem(key, button) {
        if (!confirm('Hapus item dari keranjang?')) return;

        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

        try {
            const response = await fetch(`/cart/remove/${key}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const data = await response.json();

            if (data.success) {
                document.getElementById(`cart-item-${key}`).remove();
                document.getElementById('cart-total').textContent = data.cart.total_formatted;
                document.getElementById('cartBadge').textContent = data.cart.count;
                document.querySelectorAll('.cart-count-badge').forEach(el => {
                    el.textContent = data.cart.count;
                });

                if (data.cart.count === 0) {
                    location.reload();
                }
            }
        } catch (error) {
            console.error('Remove error:', error);
        }
    }
</script>
@endpush
