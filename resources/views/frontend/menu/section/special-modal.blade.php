<!-- SPECIAL MENU MODAL -->
    <div id="specialMenuModal" onclick="closeSpecialMenuModal(event)"
        class="hidden fixed inset-0 bg-black/80 backdrop-blur-sm z-50 flex items-center justify-center p-4">

        <div class="bg-gradient-to-b from-gray-900 to-black rounded-3xl w-full max-w-5xl border border-gray-800 overflow-hidden">

            <!-- HEADER -->
            <div
                class="sticky top-0 z-20 bg-gray-900/95 backdrop-blur-sm border-b border-gray-800 px-6 py-4 flex items-center justify-between">

                <div>
                    <h2 class="text-2xl font-bold text-white flex items-center gap-3">
                        <i class="fas fa-star text-orange-500"></i>
                        Detail Menu Special
                    </h2>

                    <p class="text-sm text-gray-400 mt-1">
                        Pilih varian menu spesial yang tersedia
                    </p>
                </div>

                <!-- CLOSE BUTTON -->
                <button onclick="closeSpecialMenuModal()"
                    class="w-11 h-11 rounded-full bg-gray-800 hover:bg-red-500/80 text-white flex items-center justify-center transition-all hover:rotate-90">

                    <i class="fas fa-times text-lg"></i>

                </button>

            </div>

            <!-- BODY -->
            <div class="grid md:grid-cols-2">

                <!-- LEFT -->
                <div class="border-r border-gray-800 p-6">

                    <h2 id="specialMenuTitle" class="text-2xl font-bold text-white mb-6">
                        Pilih Menu Special
                    </h2>

                    <div id="specialItemsList" class="space-y-4">
                        <!-- Items will be populated by JavaScript -->
                    </div>

                </div>

                <!-- RIGHT -->
                <div class="p-8 text-white">

                    <img id="specialMenuImage" src="{{ asset('images/menu-special/tumpeng.jpg') }}"
                        class="w-full h-64 object-cover rounded-2xl mb-6">

                    <h3 id="specialItemName" class="text-3xl font-bold mb-3">
                        Tumpeng Mini
                    </h3>

                    <p id="specialItemPrice" class="text-orange-400 text-2xl font-bold mb-5">
                        Rp 350.000
                    </p>

                    <p id="specialItemDescription" class="text-gray-400 leading-relaxed mb-8">
                        Paket tumpeng lengkap dengan lauk dan garnish premium.
                    </p>

                    <div class="flex items-center gap-4 mb-6">
                        <button type="button" onclick="decreaseSpecialQty()"
                            class="w-10 h-10 rounded-full bg-gray-800 hover:bg-gray-700 text-white flex items-center justify-center font-bold">
                            -
                        </button>
                        <input type="number" id="specialQtyInput" value="1" min="1" max="99"
                            class="w-16 text-center bg-gray-800 border border-gray-700 rounded-xl py-2 text-white font-bold">
                        <button type="button" onclick="increaseSpecialQty()"
                            class="w-10 h-10 rounded-full bg-gray-800 hover:bg-gray-700 text-white flex items-center justify-center font-bold">
                            +
                        </button>
                    </div>

                    <button id="specialOrderBtn" onclick="addSpecialItemToCart(this)"
                        class="w-full bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 py-4 rounded-2xl font-bold transition-all hover:scale-[1.02]">
                        Tambah ke Keranjang
                    </button>

                </div>

            </div>

        </div>

    </div>
