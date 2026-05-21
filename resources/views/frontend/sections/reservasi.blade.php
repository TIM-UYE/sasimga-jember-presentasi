<section id="reservasi" class="relative bg-zinc-950 py-24 overflow-hidden">

    {{-- SUCCESS POPUP --}}
    @if (session('success'))
        <div id="successPopup" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
            {{-- Overlay --}}
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeSuccessPopup()"></div>
            {{-- Modal --}}
            <div
                class="relative bg-zinc-900 border border-zinc-800 rounded-3xl shadow-2xl shadow-black/50 max-w-md w-full p-8 text-center animate-fade-in-up">
                {{-- Close button --}}
                <button onclick="closeSuccessPopup()"
                    class="absolute top-4 right-4 text-zinc-500 hover:text-white transition-colors duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                {{-- Success icon --}}
                <div
                    class="mx-auto mb-6 h-20 w-20 rounded-full bg-linear-to-br from-emerald-400 to-emerald-600 flex items-center justify-center shadow-lg shadow-emerald-500/30">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                </div>

                {{-- Text --}}
                <h3 class="text-2xl font-bold text-white mb-2">Reservasi Berhasil! 🎉</h3>
                <p class="text-zinc-400 text-sm leading-relaxed mb-2">
                    {{ session('success') }}
                </p>
                <p class="text-zinc-500 text-xs">
                    Silakan cek WhatsApp Anda untuk detail reservasi.
                </p>

                {{-- Button --}}
                <button onclick="closeSuccessPopup()"
                    class="mt-6 w-full rounded-xl bg-linear-to-r from-orange-500 to-amber-600 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-orange-500/25 hover:shadow-xl hover:shadow-orange-500/40 transition-all duration-300 hover:-translate-y-0.5 active:translate-y-0">
                    Tutup
                </button>

                {{-- Decorative dots --}}
                <div class="flex justify-center gap-1.5 mt-4">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500/50"></span>
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500/30"></span>
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500/10"></span>
                </div>
            </div>
        </div>
        <script>
            function closeSuccessPopup() {
                document.getElementById('successPopup').style.display = 'none';
            }
            // Auto close after 10 seconds
            setTimeout(() => {
                const popup = document.getElementById('successPopup');
                if (popup) {
                    popup.style.transition = 'opacity 0.5s';
                    popup.style.opacity = '0';
                    setTimeout(() => popup.style.display = 'none', 500);
                }
            }, 10000);
        </script>
    @endif

    {{-- Background Effects --}}
    <div class="absolute inset-0">
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-orange-500/10 rounded-full blur-[128px]"></div>
        <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-orange-600/5 rounded-full blur-[128px]"></div>
    </div>

    <div class="max-w-6xl mx-auto px-6 relative z-10">

        {{-- Header --}}
        <div class="max-w-2xl mx-auto text-center mb-16 reveal">
            <span
                class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-orange-500/10 text-orange-400 text-xs font-medium tracking-wider uppercase mb-5 ring-1 ring-orange-500/20">
                <span class="w-1.5 h-1.5 rounded-full bg-orange-400 animate-pulse"></span>
                {{ __('frontend.reservation.pre-title') }}
            </span>
            <h2 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-4 tracking-tight">
                <span class="text-white">{{ __('frontend.reservation.white-title') }}</span>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-amber-500">
                    {{ __('frontend.reservation.orange-title') }}
                </span>
            </h2>
            <p class="text-zinc-400 text-base leading-relaxed">
                {{ __('frontend.reservation.description') }}
            </p>
        </div>

        {{-- Main Card --}}
        <div
            class="bg-zinc-900/50 rounded-3xl overflow-hidden border border-zinc-800 shadow-2xl shadow-black/50 reveal">

            <div class="grid lg:grid-cols-3 gap-0">

                {{-- Left Panel - Restaurant Info --}}
                <div class="lg:col-span-1 p-6 border-b lg:border-b-0 lg:border-r border-zinc-800">
                    <div class="relative h-48 rounded-2xl overflow-hidden mb-6">
                        <div class="absolute inset-0 bg-black/40"></div>
                        <img src="{{ asset('images/reservasi/sate.jpg') }}" alt="Sate Simpang Tiga"
                            class="w-full h-full object-cover transition-all duration-700 hover:scale-110">
                    </div>

                    <div class="space-y-4">
                        <div>
                            <h3 class="text-white font-medium text-lg">Sate Simpang Tiga</h3>
                            <p class="text-zinc-400 text-sm">{{ __('frontend.reservation.location') }}</p>
                        </div>

                        <div class="flex flex-wrap gap-3 text-xs text-zinc-400">
                            <span class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-orange-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                11:00 - 23:00
                            </span>
                            
                            {{-- <span class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-orange-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Cozy
                            </span> --}}
                        </div>

                        {{-- Legend --}}
                        <div class="pt-4 border-t border-zinc-800">
                            <p class="text-xs text-zinc-500 mb-3">{{ __('frontend.reservation.info') }}:</p>
                            <div class="space-y-2 text-xs">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-lg bg-zinc-700 border border-zinc-600"></div>
                                    <span class="text-zinc-400">{{ __('frontend.reservation.available') }} (4) {{ __('frontend.reservation.table') }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-lg bg-orange-500 border border-orange-400"></div>
                                    <span class="text-zinc-400">{{ __('frontend.reservation.chosen') }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-lg bg-zinc-800 border border-zinc-700 opacity-50"></div>
                                    <span class="text-zinc-400">{{ __('frontend.reservation.non_available') }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- 12 Hour Notice --}}
                        <div class="bg-orange-500/10 border border-orange-500/20 rounded-xl p-3">
                            <div class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-orange-400 mt-0.5 flex-shrink-0" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="text-xs text-orange-300">
                                    {{ __('frontend.reservation.attention1') }} <strong>{{ __('frontend.reservation.attention_strong') }}</strong> {{ __('frontend.reservation.attention2') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right Panel - Form & Seat Selection --}}
                <div class="lg:col-span-2 p-6">
                    <form action="{{ route('reservasi.store') }}" method="POST" class="space-y-6">
                        @csrf

                        {{-- Personal Info --}}
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="flex items-center gap-2 text-zinc-300 text-sm font-medium mb-1.5">
                                    <svg class="w-4 h-4 text-orange-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    {{ __('frontend.reservation.name') }} <span class="text-red-400">*</span>
                                </label>
                                <input type="text" name="nama" value="{{ old('nama') }}"
                                    placeholder="{{ __('frontend.reservation.placeholder.name') }}"
                                    class="w-full px-4 py-3 bg-zinc-800/80 border border-zinc-700 rounded-xl text-white text-sm placeholder-zinc-500 focus:outline-none focus:border-orange-500/50 focus:ring-2 focus:ring-orange-500/10 transition-all duration-300 @error('nama') border-red-500 @enderror"
                                    required>
                                @error('nama')
                                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="flex items-center gap-2 text-zinc-300 text-sm font-medium mb-1.5">
                                    <svg class="w-4 h-4 text-orange-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                    {{ __('frontend.reservation.no') }} <span class="text-red-400">*</span>
                                </label>
                                <input type="tel" name="nomor_wa" value="{{ old('nomor_wa') }}"
                                    placeholder="{{ __('frontend.reservation.placeholder.no') }}"
                                    class="w-full px-4 py-3 bg-zinc-800/80 border border-zinc-700 rounded-xl text-white text-sm placeholder-zinc-500 focus:outline-none focus:border-orange-500/50 focus:ring-2 focus:ring-orange-500/10 transition-all duration-300 @error('nomor_wa') border-red-500 @enderror"
                                    required>
                                @error('nomor_wa')
                                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Date, Time & Guests --}}
                        <div class="grid md:grid-cols-3 gap-4">
                            <div>
                                <label class="flex items-center gap-2 text-zinc-300 text-sm font-medium mb-1.5">
                                    <svg class="w-4 h-4 text-orange-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    {{ __('frontend.reservation.date') }} <span class="text-red-400">*</span>
                                </label>
                                <input type="date" name="tanggal_reservasi" id="tanggal_reservasi"
                                    value="{{ old('tanggal_reservasi') }}" min="{{ date('Y-m-d') }}"
                                    class="w-full px-4 py-3 bg-zinc-800/80 border border-zinc-700 rounded-xl text-white text-sm focus:outline-none focus:border-orange-500/50 focus:ring-2 focus:ring-orange-500/10 transition-all duration-300 [color-scheme:dark] @error('tanggal_reservasi') border-red-500 @enderror"
                                    required>
                                @error('tanggal_reservasi')
                                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="flex items-center gap-2 text-zinc-300 text-sm font-medium mb-1.5">
                                    <svg class="w-4 h-4 text-orange-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ __('frontend.reservation.time') }} <span class="text-red-400">*</span>
                                </label>
                                <input type="time" name="waktu_reservasi" id="waktu_reservasi"
                                    value="{{ old('waktu_reservasi') }}"
                                    class="w-full px-4 py-3 bg-zinc-800/80 border border-zinc-700 rounded-xl text-white text-sm focus:outline-none focus:border-orange-500/50 focus:ring-2 focus:ring-orange-500/10 transition-all duration-300 [color-scheme:dark] @error('waktu_reservasi') border-red-500 @enderror"
                                    required>
                                <p id="waktuError" class="text-red-400 text-xs mt-1 hidden"></p>
                                @error('waktu_reservasi')
                                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="flex items-center gap-2 text-zinc-300 text-sm font-medium mb-1.5">
                                    <svg class="w-4 h-4 text-orange-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    {{ __('frontend.reservation.qty') }} <span class="text-red-400">*</span>
                                </label>
                                <input type="number" name="jumlah_orang" value="{{ old('jumlah_orang') }}" min="1"
                                    placeholder="{{ __('frontend.reservation.placeholder.qty') }}"
                                    class="w-full px-4 py-3 bg-zinc-800/80 border border-zinc-700 rounded-xl text-white text-sm placeholder-zinc-500 focus:outline-none focus:border-orange-500/50 focus:ring-2 focus:ring-orange-500/10 transition-all duration-300 @error('jumlah_orang') border-red-500 @enderror"
                                    required>
                                @error('jumlah_orang')
                                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Seat Selection Area --}}
                        <div class="pt-4 border-t border-zinc-800">
                            <div class="flex items-center justify-between mb-4">
                                <label class="flex items-center gap-2 text-zinc-300 text-sm font-medium">
                                    <svg class="w-4 h-4 text-orange-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                    {{ __('frontend.reservation.select_table') }} <span class="text-red-400">*</span>
                                </label>
                                <div class="text-right">
                                    <span id="selectedCount" class="text-xs text-orange-400">0 {{ __('frontend.reservation.chosen_table') }}</span>
                                    <p id="capacityMessage" class="text-xs text-zinc-400 mt-1 hidden"></p>
                                </div>
                            </div>

                            {{-- Screen/Stage indicator --}}
                            <div class="mb-6">
                                <div class="bg-gradient-to-t from-zinc-800/50 to-transparent rounded-t-lg py-2 text-center">
                                    <p class="text-xs text-zinc-500 uppercase tracking-widest">📍 {{ __('frontend.reservation.area') }}</p>
                                </div>
                            </div>

                            {{-- Tables Grid --}}
                            <div id="tablesContainer" class="relative min-h-[300px] p-4 bg-zinc-800/30 rounded-2xl border border-zinc-800">
                                <p id="fullMessage" class="hidden text-sm text-red-300 mb-4">{{ __('frontend.reservation.full') }}</p>
                                <div id="tablesGrid" class="grid grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-3 justify-items-center">
                                    <!-- Tables will be loaded here -->
                                </div>
                                <div id="loadingTables" class="hidden absolute inset-0 flex items-center justify-center bg-zinc-900/80 rounded-2xl">
                                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-orange-500"></div>
                                </div>
                            </div>

                            {{-- Selected Tables Info --}}
                            <div id="selectedTablesInfo" class="hidden mt-4 p-4 bg-zinc-800/50 rounded-xl border border-zinc-700">
                                <p class="text-sm text-zinc-300 mb-2">{{ __('frontend.reservation.reserve_table') }}:</p>
                                <div id="selectedTablesList" class="flex flex-wrap gap-2"></div>
                            </div>

                            {{-- Hidden inputs for meja_ids[] are generated by JavaScript --}}
                            <div id="meja_ids_input"></div>

                            @error('meja_ids')
                                <p class="text-red-400 text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Submit Button --}}
                        <div class="pt-2">
                            <button type="submit" id="submitBtn"
                                class="group relative w-full overflow-hidden rounded-xl bg-linear-to-r from-orange-500 to-amber-600 px-6 py-3.5 text-sm font-bold text-white shadow-lg shadow-orange-500/25 transition-all duration-300 hover:shadow-xl hover:shadow-orange-500/40 hover:-translate-y-0.5 active:translate-y-0 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:translate-y-0"
                                disabled>
                                <span class="relative z-10 flex items-center justify-center gap-2">
                                    {{ __('frontend.reservation.serve_btn') }}
                                    <svg class="w-4 h-4 transition-transform duration-300 group-hover:translate-x-1"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                </span>
                                <div
                                    class="absolute inset-0 -translate-x-full group-hover:translate-x-0 bg-linear-to-r from-orange-600 to-amber-700 transition-transform duration-500">
                                </div>
                            </button>
                        </div>

                    </form>

                    <p class="text-zinc-600 text-xs text-center mt-5">
                        {{ __('frontend.reservation.attention') }}
                    </p>

                </div>

            </div>

        </div>

    </div>

</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tanggalInput = document.getElementById('tanggal_reservasi');
    const waktuInput = document.getElementById('waktu_reservasi');
    const jumlahOrangInput = document.querySelector('input[name="jumlah_orang"]');
    const waktuError = document.getElementById('waktuError');
    const fullMessage = document.getElementById('fullMessage');
    const capacityMessage = document.getElementById('capacityMessage');
    const tablesGrid = document.getElementById('tablesGrid');
    const loadingTables = document.getElementById('loadingTables');
    const selectedCount = document.getElementById('selectedCount');
    const selectedTablesInfo = document.getElementById('selectedTablesInfo');
    const selectedTablesList = document.getElementById('selectedTablesList');
    const mejaIdsInput = document.getElementById('meja_ids_input');
    const submitBtn = document.getElementById('submitBtn');
    const reservationForm = document.querySelector('form[action="{{ route('reservasi.store') }}"]');

    let selectedTables = new Set();

    // Set minimum date to today
    const today = new Date().toISOString().split('T')[0];
    tanggalInput.setAttribute('min', today);

    // Load tables when date and time are selected
    function loadTables() {
        const tanggal = tanggalInput.value;
        const waktu = waktuInput.value;

        if (!tanggal || !waktu) return;

        loadingTables.classList.remove('hidden');
        tablesGrid.classList.add('opacity-50');

        fetch(`{{ route('reservasi.tables') }}?tanggal=${tanggal}&waktu=${waktu}`)
            .then(response => response.json())
            .then(data => {
                const allFull = data.all_full || (data.tables.length > 0 && data.tables.every(table => !table.is_available));
                displayFullMessage(allFull);
                renderTables(data.tables);
                loadingTables.classList.add('hidden');
                tablesGrid.classList.remove('opacity-50');
            })
            .catch(error => {
                console.error('Error loading tables:', error);
                loadingTables.classList.add('hidden');
                tablesGrid.classList.remove('opacity-50');
            });
    }

    // Render tables grid
    function renderTables(tables) {
        tablesGrid.innerHTML = '';

        const availableTableIds = tables.filter(table => table.is_available).map(table => table.id.toString());
        selectedTables = new Set([...selectedTables].filter(id => availableTableIds.includes(id)));
        updateSelectedInfo();

        if (tables.length === 0) {
            tablesGrid.innerHTML = '<p class="text-zinc-500 text-sm col-span-full text-center py-8">Belum ada meja tersedia.</p>';
            return;
        }

        tables.forEach(table => {
            const isAvailable = table.is_available;
            const isSelected = selectedTables.has(table.id.toString());

            const tableEl = document.createElement('div');
            tableEl.className = `
                relative w-16 h-16 rounded-xl border-2 cursor-pointer transition-all duration-200
                flex flex-col items-center justify-center gap-1
                ${!isAvailable ? 'bg-zinc-800/50 border-zinc-700 opacity-50 cursor-not-allowed' : ''}
                ${isSelected ? 'bg-orange-500 border-orange-400 shadow-lg shadow-orange-500/30 scale-105' : ''}
                ${isAvailable && !isSelected ? 'bg-zinc-700 border-zinc-600 hover:border-orange-400 hover:bg-zinc-600' : ''}
            `;
            tableEl.dataset.tableId = table.id;
            tableEl.dataset.tableName = table.nama_meja;
            tableEl.title = isAvailable ? table.nama_meja : 'Terpesan';

            // Table label
            const label = document.createElement('span');
            label.className = `text-xs font-bold ${isSelected ? 'text-white' : 'text-zinc-300'}`;
            label.textContent = table.posisi_row + table.posisi_col;

            // Capacity indicator
            const capacity = document.createElement('span');
            capacity.className = 'text-[10px] text-zinc-500';
            capacity.textContent = table.kapasitas + ' org';

            tableEl.appendChild(label);
            tableEl.appendChild(capacity);

            // Click handler
            if (isAvailable) {
                tableEl.addEventListener('click', () => toggleTable(table.id, table.nama_meja, tableEl));
            }

            tablesGrid.appendChild(tableEl);
        });

        autoSelectTables();
    }

    // Toggle table selection
    function toggleTable(tableId, tableName, element) {
        const tableIdStr = tableId.toString();
        const requiredTables = getRequiredTableCount();

        if (selectedTables.has(tableIdStr)) {
            selectedTables.delete(tableIdStr);
            element.classList.remove('bg-orange-500', 'border-orange-400', 'shadow-lg', 'shadow-orange-500/30', 'scale-105');
            element.classList.add('bg-zinc-700', 'border-zinc-600');
            element.querySelector('span:first-child').classList.remove('text-white');
            element.querySelector('span:first-child').classList.add('text-zinc-300');
        } else {
            if (selectedTables.size >= requiredTables) {
                return;
            }
            selectedTables.add(tableIdStr);
            element.classList.remove('bg-zinc-700', 'border-zinc-600');
            element.classList.add('bg-orange-500', 'border-orange-400', 'shadow-lg', 'shadow-orange-500/30', 'scale-105');
            element.querySelector('span:first-child').classList.remove('text-zinc-300');
            element.querySelector('span:first-child').classList.add('text-white');
        }

        updateSelectedInfo();
    }

    function getRequiredTableCount() {
        const jumlah = parseInt(jumlahOrangInput.value, 10);
        if (!jumlah || jumlah <= 0) {
            return 1;
        }

        const seatsPerTable = 4;
        return Math.ceil(jumlah / seatsPerTable);
    }

    function updateCapacityMessage() {
        const jumlah = parseInt(jumlahOrangInput.value, 10);
        if (!jumlah || jumlah <= 0) {
            capacityMessage.classList.add('hidden');
            return;
        }

        const requiredTables = getRequiredTableCount();
        capacityMessage.textContent = `Untuk ${jumlah} orang, diperlukan ${requiredTables} meja.`;
        capacityMessage.classList.remove('hidden');
        capacityMessage.classList.remove('text-red-400');
        capacityMessage.classList.add('text-zinc-400');
    }

    function validateTableSelection() {
        const jumlah = parseInt(jumlahOrangInput.value, 10);
        const requiredTables = getRequiredTableCount();
        const selectedCountValue = selectedTables.size;

        if (!jumlah || selectedCountValue === 0) {
            submitBtn.disabled = true;
            return;
        }

        if (selectedCountValue < requiredTables) {
            capacityMessage.textContent = `Pilih ${requiredTables} meja untuk ${jumlah} orang.`;
            capacityMessage.classList.remove('hidden');
            capacityMessage.classList.remove('text-zinc-400');
            capacityMessage.classList.add('text-red-400');
            submitBtn.disabled = true;
        } else {
            capacityMessage.textContent = `Untuk ${jumlah} orang, ${requiredTables} meja sudah dipilih.`;
            capacityMessage.classList.remove('hidden');
            capacityMessage.classList.remove('text-red-400');
            capacityMessage.classList.add('text-zinc-400');
            submitBtn.disabled = false;
        }
    }

    // Update selected tables info
    function normalizeSelectionToRequired() {
        const requiredTables = getRequiredTableCount();

        if (selectedTables.size > requiredTables) {
            const selectedArray = Array.from(selectedTables);
            const resized = new Set(selectedArray.slice(0, requiredTables));
            const removed = selectedArray.slice(requiredTables);

            removed.forEach(id => {
                const row = document.querySelector(`[data-table-id="${id}"]`);
                if (row) {
                    row.classList.remove('bg-orange-500', 'border-orange-400', 'shadow-lg', 'shadow-orange-500/30', 'scale-105');
                    row.classList.add('bg-zinc-700', 'border-zinc-600');
                    const label = row.querySelector('span:first-child');
                    if (label) {
                        label.classList.remove('text-white');
                        label.classList.add('text-zinc-300');
                    }
                }
            });

            selectedTables = resized;
        }
    }

    function updateSelectedInfo() {
        normalizeSelectionToRequired();

        const count = selectedTables.size;
        selectedCount.textContent = count + ' meja dipilih';

        if (count > 0) {
            selectedTablesInfo.classList.remove('hidden');

            // Build list of selected table names
            selectedTablesList.innerHTML = '';
            selectedTables.forEach(id => {
                const tableEl = document.querySelector(`[data-table-id="${id}"]`);
                if (tableEl) {
                    const badge = document.createElement('span');
                    badge.className = 'px-2 py-1 bg-orange-500/20 border border-orange-500/30 rounded-lg text-xs text-orange-300';
                    badge.textContent = tableEl.dataset.tableName;
                    selectedTablesList.appendChild(badge);
                }
            });
        } else {
            selectedTablesInfo.classList.add('hidden');
        }

        // Update hidden inputs as a real Laravel array: meja_ids[]
        mejaIdsInput.innerHTML = '';
        selectedTables.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'meja_ids[]';
            input.value = id;
            mejaIdsInput.appendChild(input);
        });

        validateTableSelection();
    }

    function autoSelectTables() {
        const requiredTables = getRequiredTableCount();
        if (selectedTables.size >= requiredTables) {
            return;
        }

        const availableTableEls = Array.from(tablesGrid.querySelectorAll('[data-table-id]')).filter(el => {
            return !el.classList.contains('cursor-not-allowed') && !selectedTables.has(el.dataset.tableId);
        });

        for (const el of availableTableEls) {
            if (selectedTables.size >= requiredTables) {
                break;
            }

            const tableId = el.dataset.tableId;
            const label = el.querySelector('span:first-child');

            selectedTables.add(tableId);
            el.classList.remove('bg-zinc-700', 'border-zinc-600');
            el.classList.add('bg-orange-500', 'border-orange-400', 'shadow-lg', 'shadow-orange-500/30', 'scale-105');
            if (label) {
                label.classList.remove('text-zinc-300');
                label.classList.add('text-white');
            }
        }

        updateSelectedInfo();
    }

    function displayFullMessage(show) {
        if (show) {
            fullMessage.classList.remove('hidden');
        } else {
            fullMessage.classList.add('hidden');
        }
    }

    function isLeadTimeValid() {
        if (!tanggalInput.value || !waktuInput.value) {
            waktuError.textContent = '';
            waktuError.classList.add('hidden');
            return true;
        }

        const selectedDateTime = new Date(`${tanggalInput.value}T${waktuInput.value}`);
        const minimumDate = new Date(Date.now() + 12 * 60 * 60 * 1000);

        if (selectedDateTime < minimumDate) {
            waktuError.textContent = 'Reservasi harus dibuat minimal 12 jam sebelum waktu acara.';
            waktuError.classList.remove('hidden');
            submitBtn.disabled = true;
            return false;
        }

        waktuError.textContent = '';
        waktuError.classList.add('hidden');
        return true;
    }

    // Event listeners
    tanggalInput.addEventListener('change', () => {
        isLeadTimeValid();
        updateCapacityMessage();
        loadTables();
    });
    waktuInput.addEventListener('change', () => {
        isLeadTimeValid();
        loadTables();
    });
    jumlahOrangInput.addEventListener('input', () => {
        updateCapacityMessage();
        updateSelectedInfo();
        autoSelectTables();
    });

    reservationForm.addEventListener('submit', function (event) {
        if (!isLeadTimeValid()) {
            event.preventDefault();
            return;
        }

        const requiredTables = getRequiredTableCount();
        if (selectedTables.size < requiredTables) {
            event.preventDefault();
            capacityMessage.textContent = `Pilih ${requiredTables} meja untuk ${jumlahOrangInput.value || 1} orang.`;
            capacityMessage.classList.remove('hidden');
            capacityMessage.classList.remove('text-zinc-400');
            capacityMessage.classList.add('text-red-400');
            return;
        }
    });

    // Load tables on page load if date and time are pre-filled
    if (tanggalInput.value && waktuInput.value) {
        isLeadTimeValid();
        updateCapacityMessage();
        loadTables();
    } else {
        updateCapacityMessage();
    }
});
</script>
