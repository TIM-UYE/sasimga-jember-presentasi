<!-- REGULAR MENU MODAL -->
<div id="menuDetailModal"
    class="hidden fixed inset-0 bg-black/80 backdrop-blur-sm z-50 flex items-center justify-center p-4"
    onclick="closeMenuDetail(event)">
    <div class="bg-gradient-to-b from-gray-900 to-gray-950 rounded-3xl max-w-3xl w-full max-h-[90vh] overflow-y-auto border border-gray-800 shadow-2xl"
        onclick="event.stopPropagation()">
        <!-- HEADER -->
        <div
            class="sticky top-0 bg-gray-900/95 backdrop-blur-sm border-b border-gray-800 px-6 py-4 flex items-center justify-between z-10">
            <h2 class="text-2xl font-bold text-white">
                <i class="fas fa-info-circle mr-2 text-orange-500"></i>
                Detail Menu
            </h2>
            <button onclick="closeMenuDetail()"
                class="w-11 h-11 rounded-full bg-gray-800 hover:bg-red-500/80 text-white flex items-center justify-center transition-all duration-300 hover:rotate-90">

                <i class="fas fa-times text-lg"></i>

            </button>
        </div>

        <!-- BODY -->
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- IMAGE -->
                <div>
                    <div class="rounded-2xl overflow-hidden mb-4">
                        <img id="modalImage" src="" alt="Menu" class="w-full h-72 object-cover">
                    </div>
                </div>

                <!-- CONTENT -->
                <div class="text-white">
                    <div id="modalCategory"
                        class="inline-block bg-orange-500/20 text-orange-400 px-4 py-2 rounded-full text-sm font-semibold mb-4">
                    </div>

                    <h1 id="modalMenuName" class="text-3xl font-bold text-white mb-3"></h1>

                    <p id="modalPrice" class="text-3xl font-bold text-orange-400 mb-4"></p>

                    <div id="modalStatus" class="mb-4"></div>

                    <hr class="border-gray-700 my-4">

                    <!-- DESCRIPTION -->
                    <div class="mb-4">
                        <h3 class="font-bold text-white mb-2 flex items-center">
                            <i class="fas fa-align-left mr-2 text-orange-500"></i>
                            Deskripsi
                        </h3>
                        <p id="modalDescription" class="text-gray-400 leading-relaxed"></p>
                    </div>

                    <!-- BAHAN -->
                    <div id="bahanSection" class="mb-4 hidden">
                        <h3 class="font-bold text-white mb-2 flex items-center">
                            <i class="fas fa-leaf mr-2 text-green-500"></i>
                            Bahan Utama
                        </h3>
                        <p id="modalBahan" class="text-gray-400"></p>
                    </div>

                    <!-- INFO -->
                    <div id="infoTambahanSection" class="grid grid-cols-2 gap-3 mb-4">
                        <div id="ukuranInfo" class="bg-gray-800 p-3 rounded-xl hidden">
                            <p class="text-xs text-gray-500">Ukuran</p>
                            <p id="modalUkuran" class="font-semibold text-white"></p>
                        </div>

                        <div id="durasiInfo" class="bg-gray-800 p-3 rounded-xl hidden">
                            <p class="text-xs text-gray-500">Durasi Persiapan</p>
                            <p id="modalDurasi" class="font-semibold text-white"></p>
                        </div>
                    </div>

                    <!-- QUANTITY SELECTOR -->
                    <div class="mb-6">
                        <label class="font-bold text-white mb-3 block">Quantity:</label>
                        <div class="flex items-center gap-4">
                            <button type="button" onclick="decreaseModalQty()"
                                class="w-12 h-12 rounded-full bg-gray-800 hover:bg-gray-700 text-white flex items-center justify-center text-xl font-bold transition-all">
                                -
                            </button>
                            <input type="number" id="modalQtyInput" value="1" min="1" max="99"
                                class="w-24 text-center bg-gray-800 border border-gray-700 rounded-xl py-3 text-white font-bold text-lg">
                            <button type="button" onclick="increaseModalQty()"
                                class="w-12 h-12 rounded-full bg-gray-800 hover:bg-gray-700 text-white flex items-center justify-center text-xl font-bold transition-all">
                                +
                            </button>
                        </div>
                    </div>

                    <!-- ACTION BUTTON -->
                    <button type="button" id="modalOrderBtn" onclick="addToCartFromModal(this)"
                        class="w-full bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white py-4 rounded-xl font-bold transition-all hover:scale-[1.02] flex items-center justify-center gap-2">
                        <i class="fas fa-shopping-cart"></i>
                        Tambah ke Keranjang
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
