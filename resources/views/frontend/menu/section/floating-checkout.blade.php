 <!-- FLOATING CHECKOUT BUTTON -->
    <div id="floatingCheckout" class="hidden fixed bottom-6 right-6 z-40">
        <a href="{{ route('checkout.index') }}"
            class="flex items-center gap-4 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white px-6 py-4 rounded-2xl shadow-2xl shadow-orange-500/30 transition-all duration-300 hover:scale-105">
            <div class="text-right">
                <p class="text-xs opacity-80">Total</p>
                <p id="floatingTotal" class="text-lg font-bold">Rp 0</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="font-semibold">Checkout</span>
                <i class="fas fa-arrow-right"></i>
            </div>
        </a>
    </div>