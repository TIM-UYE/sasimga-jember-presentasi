<section id="reservasi" class="relative bg-zinc-950 py-14 sm:py-16 md:py-20 lg:py-24 overflow-hidden">

    {{-- SUCCESS POPUP --}}
    @if (session('success'))
        <div id="successPopup" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="document.getElementById('successPopup').style.display='none'"></div>
            <div class="relative bg-zinc-900 border border-zinc-800 rounded-2xl sm:rounded-3xl shadow-2xl shadow-black/50 max-w-md w-full p-5 sm:p-8 text-center animate-fade-in-up">
                <button onclick="document.getElementById('successPopup').style.display='none'" class="absolute top-4 right-4 text-zinc-500 hover:text-white"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                <div class="mx-auto mb-4 sm:mb-6 h-14 w-14 sm:h-20 sm:w-20 rounded-full bg-linear-to-br from-emerald-400 to-emerald-600 flex items-center justify-center"><svg class="w-7 h-7 sm:w-10 sm:h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg></div>
                <h3 class="text-xl sm:text-2xl font-bold text-white mb-2">Reservasi Berhasil! 🎉</h3>
                <p class="text-zinc-400 text-sm leading-relaxed mb-2">{{ session('success') }}</p>
                <p class="text-zinc-500 text-xs">Silakan cek WhatsApp Anda untuk detail reservasi.</p>
                <button onclick="document.getElementById('successPopup').style.display='none'" class="mt-6 w-full rounded-xl bg-linear-to-r from-orange-500 to-amber-600 px-6 py-3 text-sm font-bold text-white shadow-lg">Tutup</button>
            </div>
        </div>
        <script>setTimeout(()=>{const p=document.getElementById('successPopup');if(p){p.style.transition='opacity 0.5s';p.style.opacity='0';setTimeout(()=>p.style.display='none',500);}},10000);</script>
    @endif

    <div class="absolute inset-0">
        <div class="absolute top-0 left-0 sm:left-1/4 w-56 h-56 sm:w-80 sm:h-80 lg:w-96 lg:h-96 bg-orange-500/10 rounded-full blur-[96px] lg:blur-[128px]"></div>
        <div class="absolute bottom-0 right-0 sm:right-1/4 w-56 h-56 sm:w-80 sm:h-80 lg:w-96 lg:h-96 bg-orange-600/5 rounded-full blur-[96px] lg:blur-[128px]"></div>
    </div>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 relative z-10">
        <div class="max-w-2xl mx-auto text-center mb-10 sm:mb-12 lg:mb-16 reveal">
            <span class="inline-flex items-center gap-2 px-3 sm:px-4 py-1.5 rounded-full bg-orange-500/10 text-orange-400 text-[10px] sm:text-xs font-medium tracking-wider uppercase mb-4 sm:mb-5 ring-1 ring-orange-500/20">
                <span class="w-1.5 h-1.5 rounded-full bg-orange-400 animate-pulse"></span>
                {{ __('frontend.reservation.pre-title') }}
            </span>
            <h2 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold mb-3 sm:mb-4 tracking-tight leading-tight">
                <span class="text-white">{{ __('frontend.reservation.white-title') }}</span>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-amber-500">{{ __('frontend.reservation.orange-title') }}</span>
            </h2>
            <p class="text-zinc-400 text-sm sm:text-base leading-relaxed">{{ __('frontend.reservation.description') }}</p>
        </div>

        <div class="bg-zinc-900/50 rounded-2xl md:rounded-3xl overflow-hidden border border-zinc-800 shadow-xl md:shadow-2xl shadow-black/50 reveal">
            <div class="grid lg:grid-cols-3 gap-0">
                {{-- Left Panel --}}
                <div class="lg:col-span-1 p-4 sm:p-5 lg:p-6 border-b lg:border-b-0 lg:border-r border-zinc-800">
                    <div class="relative h-36 sm:h-44 lg:h-48 rounded-xl sm:rounded-2xl overflow-hidden mb-4 sm:mb-6">
                        <div class="absolute inset-0 bg-black/40"></div>
                        <img src="{{ asset('images/reservasi/sate.jpg') }}" alt="Sate Simpang Tiga" class="w-full h-full object-cover">
                    </div>
                    <div class="space-y-3 sm:space-y-4">
                        <div>
                            <h3 class="text-white font-medium text-base sm:text-lg">Sate Simpang Tiga</h3>
                            <p class="text-zinc-400 text-sm">{{ __('frontend.reservation.location') }}</p>
                        </div>
                        <div class="flex flex-wrap gap-3 text-xs text-zinc-400">
                            <span class="flex items-center gap-1.5"><svg class="w-3.5 h-3.5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>11:00 - 21:00</span>
                        </div>
                        <div class="pt-4 border-t border-zinc-800">
                            <p class="text-xs text-zinc-500 mb-3">{{ __('frontend.reservation.info') }}:</p>
                            <div class="space-y-2 text-xs">
                                <div class="flex items-center gap-2"><div class="w-5 h-5 rounded-lg bg-zinc-700 border border-zinc-600"></div><span class="text-zinc-400">Tersedia</span></div>
                                <div class="flex items-center gap-2"><div class="w-5 h-5 rounded-lg bg-orange-500 border border-orange-400"></div><span class="text-zinc-400">Dipilih</span></div>
                                <div class="flex items-center gap-2"><div class="w-5 h-5 rounded-lg bg-zinc-800 border border-zinc-700 opacity-50"></div><span class="text-zinc-400">Terpesan</span></div>
                            </div>
                        </div>
                        <div class="bg-orange-500/10 border border-orange-500/20 rounded-xl p-3">
                            <p class="text-xs text-orange-300">Reservasi minimal 2 jam sebelumnya. Maksimal reservasi pukul 19:00.</p>
                        </div>
                    </div>
                </div>

                {{-- Right Panel --}}
                <div class="lg:col-span-2 p-4 sm:p-5 lg:p-6">
                    <form action="{{ route('reservasi.store') }}" method="POST" id="reservationForm">
                        @csrf

                        {{-- Personal Info --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4">
                            <div>
                                <label class="flex items-center gap-2 text-zinc-300 text-sm font-medium mb-1.5">
                                    <svg class="w-4 h-4 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    Nama <span class="text-red-400">*</span>
                                </label>
                                <input type="text" name="nama" value="{{ old('nama') }}" placeholder="Masukkan nama Anda" class="w-full px-3.5 sm:px-4 py-2.5 sm:py-3 bg-zinc-800/80 border border-zinc-700 rounded-xl text-white text-sm placeholder-zinc-500 focus:outline-none focus:border-orange-500/50 focus:ring-2 focus:ring-orange-500/10 @error('nama') border-red-500 @enderror" required>
                                @error('nama')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="flex items-center gap-2 text-zinc-300 text-sm font-medium mb-1.5">
                                    <svg class="w-4 h-4 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                    No. WhatsApp <span class="text-red-400">*</span>
                                </label>
                                <input type="tel" name="nomor_wa" value="{{ old('nomor_wa') }}" placeholder="08123456789" class="w-full px-3.5 sm:px-4 py-2.5 sm:py-3 bg-zinc-800/80 border border-zinc-700 rounded-xl text-white text-sm placeholder-zinc-500 focus:outline-none focus:border-orange-500/50 focus:ring-2 focus:ring-orange-500/10 @error('nomor_wa') border-red-500 @enderror" required>
                                @error('nomor_wa')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        {{-- Date, Time & Guests --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 sm:gap-4 mt-4">
                            <div>
                                <label class="flex items-center gap-2 text-zinc-300 text-sm font-medium mb-1.5">
                                    <svg class="w-4 h-4 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    Tanggal <span class="text-red-400">*</span>
                                </label>
                                <input type="date" name="tanggal_reservasi" id="tanggal_reservasi" value="{{ old('tanggal_reservasi') }}" min="{{ date('Y-m-d') }}" class="w-full px-3.5 sm:px-4 py-2.5 sm:py-3 bg-zinc-800/80 border border-zinc-700 rounded-xl text-white text-sm focus:outline-none focus:border-orange-500/50 focus:ring-2 focus:ring-orange-500/10 [color-scheme:dark] @error('tanggal_reservasi') border-red-500 @enderror" required>
                                @error('tanggal_reservasi')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="flex items-center gap-2 text-zinc-300 text-sm font-medium mb-1.5">
                                    <svg class="w-4 h-4 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Jam <span class="text-red-400">*</span>
                                </label>
                                <input type="time" name="waktu_reservasi" id="waktu_reservasi" value="{{ old('waktu_reservasi') }}" min="11:00" max="19:00" class="w-full px-3.5 sm:px-4 py-2.5 sm:py-3 bg-zinc-800/80 border border-zinc-700 rounded-xl text-white text-sm focus:outline-none focus:border-orange-500/50 focus:ring-2 focus:ring-orange-500/10 [color-scheme:dark] @error('waktu_reservasi') border-red-500 @enderror" required>
                                <p id="waktuError" class="text-red-400 text-xs mt-1 hidden"></p>
                                @error('waktu_reservasi')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="flex items-center gap-2 text-zinc-300 text-sm font-medium mb-1.5">
                                    <svg class="w-4 h-4 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    Jumlah Tamu <span class="text-red-400">*</span>
                                </label>
                                <input type="number" name="jumlah_orang" id="jumlah_orang" value="{{ old('jumlah_orang') }}" min="1" placeholder="Contoh: 4" class="w-full px-3.5 sm:px-4 py-2.5 sm:py-3 bg-zinc-800/80 border border-zinc-700 rounded-xl text-white text-sm placeholder-zinc-500 focus:outline-none focus:border-orange-500/50 focus:ring-2 focus:ring-orange-500/10 @error('jumlah_orang') border-red-500 @enderror" required>
                                @error('jumlah_orang')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        {{-- Floor Selection --}}
                        <div id="floorSection" class="pt-3 sm:pt-4 mt-4 border-t border-zinc-800 hidden">
                            <label class="flex items-center gap-2 text-zinc-300 text-sm font-medium mb-3">
                                <svg class="w-4 h-4 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                Pilih Lantai <span class="text-red-400">*</span>
                            </label>
                            <input type="hidden" name="lantai_id" id="lantai_id" value="">
                            <div id="floorButtons" class="flex gap-3"></div>
                        </div>

                        {{-- Floor Preview --}}
                        <div id="floorPreview" class="hidden pt-2">
                            <div class="bg-zinc-800/30 rounded-xl p-3 border border-zinc-700/50">
                                <img id="floorPreviewImage" src="" alt="Preview Lantai" class="w-full h-48 object-cover rounded-lg">
                                <div id="floorPreviewTableImages" class="mt-3 grid grid-cols-2 gap-2 hidden"></div>
                            </div>
                        </div>

                        {{-- Table Selection --}}
                        <div id="tableSection" class="pt-3 sm:pt-4 mt-4 border-t border-zinc-800 hidden">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 sm:gap-4 mb-4">
                                <label class="flex items-center gap-2 text-zinc-300 text-sm font-medium">
                                    <svg class="w-4 h-4 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    Pilih Meja <span class="text-red-400">*</span>
                                </label>
                                <div class="text-right">
                                    <span id="selectedCount" class="text-xs text-orange-400">0 meja dipilih</span>
                                    <p id="requiredTablesInfo" class="text-xs text-zinc-500 mt-0.5"></p>
                                </div>
                            </div>

                            <div id="layoutContainer" class="relative min-h-[300px] w-full overflow-auto p-4 bg-zinc-800/30 rounded-xl border border-zinc-700/50 mb-4" style="background-image: radial-gradient(circle, rgba(255,255,255,0.03) 1px, transparent 1px); background-size: 20px 20px;">
                                <div id="loadingTables" class="hidden absolute inset-0 flex items-center justify-center bg-zinc-900/80 rounded-xl z-20">
                                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-orange-500"></div>
                                </div>
                                <p id="errorMessage" class="hidden text-sm text-red-300 mb-4"></p>
                                <p id="fullMessage" class="hidden text-sm text-red-300 mb-4">Semua meja sudah terpesan untuk waktu ini.</p>
                                <p id="mergeMessage" class="hidden text-sm text-yellow-300 mb-4">Meja yang dipilih tidak bisa digabungkan.</p>
                                <div id="tablesContainer" class="relative" style="min-height: 250px;"></div>
                            </div>

                            <div id="selectedTablesInfo" class="hidden p-3 sm:p-4 bg-zinc-800/50 rounded-xl border border-zinc-700">
                                <p class="text-sm text-zinc-300 mb-2">Meja yang dipilih:</p>
                                <div id="selectedTablesList" class="flex flex-wrap gap-2"></div>
                                <div class="mt-3">
                                    <button type="button" id="showTablePreviewBtn" class="hidden w-full sm:w-auto px-3 py-2 rounded-lg bg-zinc-800 border border-zinc-700 text-sm text-orange-300 hover:bg-zinc-700">Tampilkan Preview Meja</button>
                                </div>
                            </div>

                            <div id="meja_ids_input"></div>
                            @error('meja_ids')<p class="text-red-400 text-xs mt-2">{{ $message }}</p>@enderror
                        </div>

                        {{-- Submit --}}
                        <div class="pt-4 mt-4">
                            <button type="submit" id="submitBtn" disabled
                                class="group relative w-full overflow-hidden rounded-xl bg-linear-to-r from-orange-500 to-amber-600 px-5 sm:px-6 py-3 sm:py-3.5 text-sm font-bold text-white shadow-lg shadow-orange-500/25 transition-all duration-300 hover:shadow-xl hover:shadow-orange-500/40 hover:-translate-y-0.5 active:translate-y-0 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:translate-y-0">
                                <span class="relative z-10 flex items-center justify-center gap-2">
                                    Pesan Sekarang
                                    <svg class="w-4 h-4 transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                                </span>
                            </button>
                        </div>
                    </form>
                    <p class="text-zinc-600 text-xs text-center mt-5">{{ __('frontend.reservation.attention') }}</p>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
// Global variables
let selectedTables = new Set();
let currentFloorId = null;
let allTables = [];
let currentCombination = null;
let mergeErrorTimeout = null;

function getEl(id) { return document.getElementById(id); }

function isTimeInOp(timeStr) {
    if (!timeStr) return false;
    const [h, m] = timeStr.split(':').map(Number);
    const mins = h * 60 + m;
    return mins >= 660 && mins <= 1140; // 11:00-19:00
}

function getRequiredTables() {
    const jml = parseInt(getEl('jumlah_orang').value, 10);
    if (!jml || jml <= 0) return 1;
    return Math.ceil(jml / 4);
}

function validateTime() {
    const t = getEl('tanggal_reservasi').value;
    const w = getEl('waktu_reservasi').value;
    const err = getEl('waktuError');
    if (!t || !w) { err.classList.add('hidden'); return false; }
    if (!isTimeInOp(w)) {
        err.textContent = 'Reservasi hanya 11:00 - 19:00.';
        err.classList.remove('hidden');
        return false;
    }
    const sel = new Date(t + 'T' + w);
    const min = new Date(Date.now() + 2*60*60*1000);
    if (sel < min) {
        err.textContent = 'Minimal 2 jam sebelum waktu dipilih.';
        err.classList.remove('hidden');
        return false;
    }
    err.classList.add('hidden');
    return true;
}

// Load floors when all 3 fields filled
window.checkAndLoad = function() {
    const t = getEl('tanggal_reservasi').value;
    const w = getEl('waktu_reservasi').value;
    const j = getEl('jumlah_orang').value;
    if (!t || !w || !j) return;
    if (!validateTime() || !isTimeInOp(w)) return;

    // Reset
    selectedTables.clear();
    currentFloorId = null;
    getEl('tableSection').classList.add('hidden');
    getEl('floorPreview').classList.add('hidden');
    getEl('submitBtn').disabled = true;
    getEl('tablesContainer').innerHTML = '';

    // Load floors
    fetch('{{ route("reservasi.floors") }}')
        .then(r => r.json())
        .then(data => {
            const fb = getEl('floorButtons');
            fb.innerHTML = '';
            data.floors.forEach(floor => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'floor-btn flex-1 px-4 py-3 rounded-xl border-2 text-sm font-medium transition-all bg-zinc-800 border-zinc-700 text-zinc-400 hover:border-orange-400 hover:text-orange-400';
                btn.dataset.floorId = floor.id;
                btn.dataset.preview = floor.preview_image;
                btn.textContent = floor.nama;
                btn.onclick = function() { window.selectFloor(floor.id, floor.preview_image, this); };
                fb.appendChild(btn);
            });
            getEl('floorSection').classList.remove('hidden');
        })
        .catch(e => console.error('Floor load error:', e));
};

window.selectFloor = function(id, preview, btn) {
    currentFloorId = id;
    getEl('lantai_id').value = id;
    document.querySelectorAll('.floor-btn').forEach(b => {
        b.className = 'floor-btn flex-1 px-4 py-3 rounded-xl border-2 text-sm font-medium transition-all bg-zinc-800 border-zinc-700 text-zinc-400 hover:border-orange-400 hover:text-orange-400';
    });
    btn.className = 'floor-btn flex-1 px-4 py-3 rounded-xl border-2 text-sm font-medium transition-all bg-orange-500/20 border-orange-500 text-orange-400';

    if (preview && !preview.includes('default-layout')) {
        getEl('floorPreviewImage').src = preview;
        getEl('floorPreview').classList.remove('hidden');
    } else {
        getEl('floorPreview').classList.add('hidden');
    }

    // Load tables
    window.loadTables();
};

window.loadTables = function() {
    const t = getEl('tanggal_reservasi').value;
    const w = getEl('waktu_reservasi').value;
    const j = getEl('jumlah_orang').value;
    if (!t || !w || !currentFloorId || !j) return;

    getEl('loadingTables').classList.remove('hidden');
    getEl('tableSection').classList.remove('hidden');

    const params = new URLSearchParams({
        tanggal: t, waktu: w, lantai_id: currentFloorId, jumlah_orang: j
    });

    fetch('{{ route("reservasi.tables.byfloor") }}?' + params.toString())
        .then(r => r.json().then(d => ({ status: r.status, body: d })))
        .then(({ status, body }) => {
            getEl('loadingTables').classList.add('hidden');
            if (status === 422 && body.error) {
                getEl('errorMessage').textContent = body.error;
                getEl('errorMessage').classList.remove('hidden');
                getEl('tablesContainer').innerHTML = '';
                selectedTables.clear();
                window.updateUI();
                return;
            }
            getEl('errorMessage').classList.add('hidden');
            allTables = body.tables || [];
            const req = body.required_tables || getRequiredTables();
            getEl('requiredTablesInfo').textContent = 'Untuk ' + (body.jumlah_orang || j) + ' orang, diperlukan ' + req + ' meja.';

            const avail = allTables.filter(t => t.is_available);
            if (avail.length === 0) getEl('fullMessage').classList.remove('hidden');
            else getEl('fullMessage').classList.add('hidden');

            window.renderTables(allTables, req, body.valid_combinations || []);
        })
        .catch(e => { getEl('loadingTables').classList.add('hidden'); console.error('Table error:', e); });
};

window.renderTables = function(tables, required, validCombinations) {
    const tc = getEl('tablesContainer');
    tc.innerHTML = '';
    if (tables.length === 0) {
        tc.innerHTML = '<p class="text-zinc-500 text-sm text-center py-8">Tidak ada meja tersedia.</p>';
        return;
    }

    selectedTables.clear();
    currentCombination = null;

    // Auto-select first valid combo
    if (validCombinations.length > 0) {
        validCombinations[0].table_ids.forEach(id => selectedTables.add(id.toString()));
        currentCombination = validCombinations[0];
    }

    tables.forEach(table => {
        const avail = table.is_available;
        const sel = selectedTables.has(table.id.toString());
        const el = document.createElement('div');
        el.className = 'absolute rounded-lg border-2 flex flex-col items-center justify-center cursor-pointer transition-all duration-200' +
            (!avail ? ' bg-zinc-800/50 border-zinc-700 opacity-50 cursor-not-allowed' : '') +
            (sel ? ' bg-orange-500 border-orange-400 shadow-lg shadow-orange-500/30 scale-105 z-10' : '') +
            (avail && !sel ? ' bg-zinc-700/80 border-zinc-600 hover:border-orange-400 hover:bg-zinc-600' : '');
        el.style.left = (table.pos_x || 0) + 'px';
        el.style.top = (table.pos_y || 0) + 'px';
        el.style.width = '80px';
        el.style.height = '80px';
        el.dataset.tableId = table.id;

        // Do not set table image as button background — preview shown separately

        const nl = document.createElement('span');
        nl.className = 'text-xs font-bold ' + (sel ? 'text-white' : 'text-zinc-200');
        nl.textContent = table.nomor_meja || table.nama_meja;
        el.appendChild(nl);

        const cl = document.createElement('span');
        cl.className = 'text-[9px] ' + (sel ? 'text-orange-200' : 'text-zinc-500');
        cl.textContent = table.kapasitas + ' org';
        el.appendChild(cl);

        if (avail) {
            el.addEventListener('click', function(e) {
                try { window.toggleTable(table.id, required); } catch (err) { console.error('toggleTable error:', err); }
            });
        }
        tc.appendChild(el);
    });



    const maxY = Math.max(...tables.map(t => (t.pos_y || 0) + 80), 250);
    const maxX = Math.max(...tables.map(t => (t.pos_x || 0) + 80), 300);
    tc.style.minHeight = Math.max(maxY, 250) + 'px';
    tc.style.minWidth = Math.max(maxX, 300) + 'px';

    window.updateUI();
    window.checkSubmit();
};

window.toggleTable = function(tableId, requiredTables) {
    const idStr = tableId.toString();
    if (selectedTables.has(idStr)) {
        selectedTables.delete(idStr);
        currentCombination = null;
    } else {
        if (selectedTables.size >= requiredTables) {
            // Replace the oldest selected table so user can freely switch choices
            const oldest = selectedTables.values().next().value;
            if (oldest) {
                selectedTables.delete(oldest);
                const oldEl = document.querySelector('#tablesContainer [data-table-id="' + oldest + '"]');
                if (oldEl) {
                    oldEl.classList.remove('bg-orange-500','border-orange-400','shadow-lg','shadow-orange-500/30','scale-105','z-10');
                    oldEl.classList.add('bg-zinc-700/80','border-zinc-600');
                    const spans = oldEl.querySelectorAll('span');
                    if (spans[0]) { spans[0].classList.remove('text-white'); spans[0].classList.add('text-zinc-200'); }
                }
            }
        }
        selectedTables.add(idStr);
    }

    // Update visual
    document.querySelectorAll('#tablesContainer > div').forEach(el => {
        if (el.dataset.tableId === idStr) {
            if (selectedTables.has(idStr)) {
                el.classList.remove('bg-zinc-700/80','border-zinc-600');
                el.classList.add('bg-orange-500','border-orange-400','shadow-lg','shadow-orange-500/30','scale-105','z-10');
                const spans = el.querySelectorAll('span');
                if (spans[0]) { spans[0].classList.remove('text-zinc-200'); spans[0].classList.add('text-white'); }
            } else {
                el.classList.remove('bg-orange-500','border-orange-400','shadow-lg','shadow-orange-500/30','scale-105','z-10');
                el.classList.add('bg-zinc-700/80','border-zinc-600');
                const spans = el.querySelectorAll('span');
                if (spans[0]) { spans[0].classList.remove('text-white'); spans[0].classList.add('text-zinc-200'); }
            }
        }
    });

    // Validate combination
    const selArr = Array.from(selectedTables);
    if (selArr.length === 1) {
        const t = allTables.find(t => t.id.toString() === selArr[0]);
        if (t) currentCombination = { table_ids: [t.id], table_names: [t.nama_meja], total_capacity: t.kapasitas };
    } else if (selArr.length > 1) {
        const selData = selArr.map(id => allTables.find(t => t.id.toString() === id)).filter(Boolean);
        const groups = selData.filter(t => t && t.merge_group).map(t => t.merge_group);
        const uniq = [...new Set(groups)];
        if (uniq.length === 1 && groups.length === selArr.length) {
            currentCombination = {
                table_ids: selData.map(t => t.id),
                table_names: selData.map(t => t.nama_meja),
                total_capacity: selData.reduce((s, t) => s + t.kapasitas, 0)
            };
        } else {
            currentCombination = null;
            // Show merge error notification
            if (typeof getEl === 'function' && getEl('mergeMessage')) {
                const m = getEl('mergeMessage');
                m.textContent = 'Meja yang dipilih tidak bisa digabungkan. Silakan pilih meja lain yang sekelompok.';
                m.classList.remove('hidden');
                if (mergeErrorTimeout) clearTimeout(mergeErrorTimeout);
                mergeErrorTimeout = setTimeout(()=>{ m.classList.add('hidden'); }, 5000);
            }
        }
    }
    window.updateUI();
    window.checkSubmit();
};

window.updateUI = function() {
    const count = selectedTables.size;
    getEl('selectedCount').textContent = count + ' meja dipilih';
    if (count > 0) {
        getEl('selectedTablesInfo').classList.remove('hidden');
        getEl('selectedTablesList').innerHTML = '';
        selectedTables.forEach(id => {
            const t = allTables.find(t => t.id.toString() === id);
            if (t) {
                const b = document.createElement('span');
                b.className = 'px-2 py-1 bg-orange-500/20 border border-orange-500/30 rounded-lg text-xs text-orange-300';
                b.textContent = t.nama_meja;
                getEl('selectedTablesList').appendChild(b);
            }
        });
        // Show preview button when at least one table selected
        const previewBtn = getEl('showTablePreviewBtn');
        if (previewBtn) {
            previewBtn.classList.remove('hidden');
            previewBtn.onclick = function() { window.showSelectedTablePreview(); };
        }
    } else {
        getEl('selectedTablesInfo').classList.add('hidden');
        const previewBtn = getEl('showTablePreviewBtn'); if (previewBtn) previewBtn.classList.add('hidden');
    }

    // Hidden inputs
    getEl('meja_ids_input').innerHTML = '';
    selectedTables.forEach(id => {
        const inp = document.createElement('input');
        inp.type = 'hidden';
        inp.name = 'meja_ids[]';
        inp.value = id;
        getEl('meja_ids_input').appendChild(inp);
    });
};

window.checkSubmit = function() {
    const req = getRequiredTables();
    const jml = parseInt(getEl('jumlah_orang').value, 10);
    if (selectedTables.size >= req && jml && getEl('tanggal_reservasi').value && getEl('waktu_reservasi').value && currentFloorId && validateTime() && currentCombination) {
        getEl('submitBtn').disabled = false;
    } else {
        getEl('submitBtn').disabled = true;
    }
};

// Show table detail modal and preview table image in the floor preview area
// Note: table detail modal and related functions removed — preview only via button

window.showSelectedTablePreview = function() {
    if (selectedTables.size === 0) return;
    const fpWrap = getEl('floorPreview');
    const fpImg = getEl('floorPreviewImage');
    const fpTableContainer = getEl('floorPreviewTableImages');
    if (!fpWrap || !fpTableContainer) return;
    fpTableContainer.innerHTML = '';
    const images = [];
    selectedTables.forEach(id => {
        const t = allTables.find(x => x.id.toString() === id);
        if (t && t.table_image && !t.table_image.includes('default-table')) {
            images.push(t.table_image);
        }
    });
    if (images.length === 0) {
        const m = getEl('mergeMessage');
        if (m) {
            m.textContent = 'Tidak ada gambar untuk meja yang dipilih.';
            m.classList.remove('hidden');
            if (mergeErrorTimeout) clearTimeout(mergeErrorTimeout);
            mergeErrorTimeout = setTimeout(()=>{ m.classList.add('hidden'); }, 3000);
        }
        return;
    }

    // create image elements for all selected table images
    images.forEach(src => {
        const img = document.createElement('img');
        img.src = src;
        img.alt = 'Meja';
        img.className = 'w-full h-24 object-cover rounded-md';
        fpTableContainer.appendChild(img);
    });
    fpTableContainer.classList.remove('hidden');
    // ensure floor preview area is visible (keep floor image intact)
    fpWrap.classList.remove('hidden');
};

// Form submit
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('tanggal_reservasi').addEventListener('change', window.checkAndLoad);
    document.getElementById('waktu_reservasi').addEventListener('input', function() { validateTime(); window.checkAndLoad(); });
    document.getElementById('waktu_reservasi').addEventListener('change', function() { validateTime(); window.checkAndLoad(); });
    document.getElementById('jumlah_orang').addEventListener('input', function() {
        const req = getRequiredTables();
        getEl('requiredTablesInfo').textContent = 'Untuk ' + (getEl('jumlah_orang').value || 0) + ' orang, diperlukan ' + req + ' meja.';
        if (currentFloorId && getEl('tanggal_reservasi').value && getEl('waktu_reservasi').value) {
            if (validateTime()) window.loadTables();
        } else {
            window.checkAndLoad();
        }
    });
    document.getElementById('jumlah_orang').addEventListener('change', function() {
        if (currentFloorId && getEl('tanggal_reservasi').value && getEl('waktu_reservasi').value) {
            if (validateTime()) window.loadTables();
        } else {
            window.checkAndLoad();
        }
    });

    document.getElementById('reservationForm').addEventListener('submit', function(e) {
        const req = getRequiredTables();
        if (selectedTables.size < req) {
            e.preventDefault();
            alert('Pilih ' + req + ' meja untuk ' + (getEl('jumlah_orang').value || 1) + ' orang.');
            return;
        }
        if (!validateTime()) {
            e.preventDefault();
        }
    });

    // Init
    if (getEl('tanggal_reservasi').value && getEl('waktu_reservasi').value) {
        window.checkAndLoad();
    }
});
</script>
