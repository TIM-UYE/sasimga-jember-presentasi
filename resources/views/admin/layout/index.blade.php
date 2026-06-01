@extends('admin.layout.main')

@section('title', 'Layout Restoran')

@section('content')
<div class="p-4 sm:p-6 lg:p-8">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Layout Restoran</h1>
            <p class="text-slate-500 text-sm mt-1">Atur tata letak meja restoran</p>
        </div>
        <div class="flex items-center gap-3">
            @foreach($lantais as $l)
                <a href="{{ route('admin.layout.index', $l->slug) }}"
                    class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200
                    {{ $lantai->id === $l->id ? 'bg-orange-500 text-white shadow-lg shadow-orange-500/25' : 'bg-slate-100 text-slate-700 hover:bg-slate-200 hover:text-slate-900' }}">
                    {{ $l->nama }}
                </a>
            @endforeach
        </div>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="mb-4 p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-lg text-emerald-400 text-sm">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-4 bg-red-500/10 border border-red-500/20 rounded-lg text-red-400 text-sm">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        {{-- Left: Layout Canvas --}}
        <div class="xl:col-span-2 bg-white rounded-2xl border border-slate-200 p-6 shadow-sm ring-1 ring-slate-200/80">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">

    <div>
        <h2 class="text-2xl font-bold text-slate-900">
            {{ $lantai->nama }} Layout
        </h2>

        <p class="text-sm text-slate-500 mt-1">
            Atur posisi meja dengan drag & drop lalu simpan perubahan layout.
        </p>
    </div>

    <div class="flex flex-wrap items-center gap-3">

        <div class="px-4 py-2 rounded-xl bg-slate-100 border border-slate-200">
            <span class="text-xs text-slate-500">Total Meja</span>
            <p class="font-semibold text-slate-800">
                {{ $mejas->count() }}
            </p>
        </div>

        <button
            onclick="enableDragMode()"
            id="dragModeBtn"
            class="
                inline-flex items-center gap-2
                px-5 py-3
                rounded-xl
                border border-slate-200
                bg-white
                text-slate-700
                text-sm font-semibold
                shadow-sm
                hover:border-orange-400
                hover:text-orange-500
                hover:shadow-md
                transition-all duration-300">

            <span class="text-base">✋</span>
            Drag Mode
        </button>

        <button
            onclick="savePositions()"
            id="savePosBtn"
            class="
                hidden
                inline-flex items-center gap-2
                px-5 py-3
                rounded-xl
                bg-gradient-to-r
                from-orange-500
                to-orange-600
                text-white
                text-sm font-semibold
                shadow-lg shadow-orange-500/20
                hover:shadow-orange-500/40
                hover:-translate-y-0.5
                transition-all duration-300">

            <span class="text-base">💾</span>
            Simpan Posisi
        </button>

    </div>

</div>

            {{-- Preview Image --}}
            <div class="mb-4">
                @if($lantai->preview_image)
                    <img src="{{ asset('storage/' . $lantai->preview_image) }}"
                        alt="{{ $lantai->nama }}"
                        class="w-full h-48 object-cover rounded-xl border border-slate-200">
                @else
                    <div class="w-full h-48 bg-slate-100 rounded-xl flex items-center justify-center border border-slate-200">
                        <p class="text-slate-500 text-sm">Belum ada gambar preview</p>
                    </div>
                @endif
                <form action="{{ route('admin.layout.preview.update', $lantai->id) }}" method="POST" enctype="multipart/form-data" class="mt-2">
                    @csrf
                    <label class="flex items-center gap-2 text-xs text-orange-400 cursor-pointer hover:text-orange-300 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Upload Gambar Preview
                        <input type="file" name="preview_image" accept="image/*" class="hidden" onchange="this.form.submit()">
                    </label>
                </form>
            </div>

            {{-- Canvas --}}
            <div id="layoutCanvas"
                class="relative w-full bg-slate-50 rounded-xl border border-slate-200 overflow-hidden"
                style="height: 500px; background-image: radial-gradient(circle, rgba(15,23,42,0.03) 1px, transparent 1px); background-size: 20px 20px;">

                @forelse($mejas as $meja)
                    <div class="table-item absolute cursor-move rounded-lg border-2 flex flex-col items-center justify-center transition-all hover:ring-2 hover:ring-orange-500/50"
                        data-id="{{ $meja->id }}"
                        data-pos-x="{{ $meja->pos_x }}"
                        data-pos-y="{{ $meja->pos_y }}"
                        style="left: {{ $meja->pos_x }}px; top: {{ $meja->pos_y }}px; width: 80px; height: 80px;
                            background: {{ $meja->is_mergeable ? 'rgba(251, 146, 60, 0.15)' : 'rgba(99, 102, 241, 0.15)' }};
                            border-color: {{ $meja->is_mergeable ? 'rgba(251, 146, 60, 0.4)' : 'rgba(99, 102, 241, 0.4)' }};">
                        <span class="text-xs font-bold text-slate-800">{{ $meja->nomor_meja }}</span>
                        <span class="text-[10px] text-slate-500">{{ $meja->kapasitas }} org</span>
                        @if($meja->merge_group)
                            <span class="text-[8px] text-orange-400">Grup {{ $meja->merge_group }}</span>
                        @endif
                    </div>
                @empty
                    <div class="flex items-center justify-center h-full">
                            <p class="text-slate-500 text-sm">Belum ada meja. Tambahkan meja dari panel sebelah kanan.</p>
                        </div>
                    @endforelse
                </div>

                <div class="mt-4 rounded-2xl border border-slate-200 bg-slate-50 p-4">
                    <p class="text-sm text-slate-600">💡 Klik "Drag Mode" untuk mengaktifkan drag & drop, lalu seret meja ke posisi yang diinginkan.</p>
                </div>
            </div>

        {{-- Right: Controls --}}
        <div class="space-y-5">
            {{-- Add Table --}}
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm ring-1 ring-slate-200/80">
                <h3 class="text-slate-800 font-semibold mb-4">Tambah Meja Baru</h3>
                <form id="addTableForm" class="space-y-4">
                    @csrf
                    <input type="hidden" name="lantai_id" value="{{ $lantai->id }}">

                    <div>
                        <label class="text-xs text-slate-500 mb-1 block">Nama Meja</label>
                        <input type="text" name="nama_meja" placeholder="Meja 21"
                            class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 focus:border-orange-400 focus:ring-2 focus:ring-orange-200 transition-all" required>
                    </div>

                    <div>
                        <label class="text-xs text-slate-500 mb-1 block">Nomor Meja</label>
                        <input type="text" name="nomor_meja" placeholder="21"
                            class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 focus:border-orange-400 focus:ring-2 focus:ring-orange-200 transition-all" required>
                    </div>

                    <div>
                        <label class="text-xs text-slate-500 mb-1 block">Kapasitas</label>
                        <input type="number" name="kapasitas" value="4" min="1" max="20"
                            class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 focus:border-orange-400 focus:ring-2 focus:ring-orange-200 transition-all" required>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-xs text-slate-500 mb-1 block">Posisi X</label>
                            <input type="number" name="pos_x" value="100"
                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 focus:border-orange-400 focus:ring-2 focus:ring-orange-200 transition-all" required>
                        </div>
                        <div>
                            <label class="text-xs text-slate-500 mb-1 block">Posisi Y</label>
                            <input type="number" name="pos_y" value="100"
                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 focus:border-orange-400 focus:ring-2 focus:ring-orange-200 transition-all" required>
                        </div>
                    </div>

                    <div>
                        <label class="text-xs text-slate-500 mb-1 block">Kategori</label>
                        <select name="kategori"
                            class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 focus:border-orange-400 focus:ring-2 focus:ring-orange-200 transition-all">
                            <option value="regular">Regular</option>
                            <option value="vip">VIP</option>
                            <option value="booth">Booth</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="is_mergeable" id="is_mergeable" value="1"
                            class="h-4 w-4 rounded border-slate-300 text-orange-500 focus:ring-orange-500">
                        <label for="is_mergeable" class="text-xs text-slate-600">Dapat digabung</label>
                    </div>

                    <div id="mergeGroupField" class="hidden">
                        <label class="text-xs text-slate-500 mb-1 block">Grup Penggabungan</label>
                        <input type="text" name="merge_group" placeholder="A"
                            class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 focus:border-orange-400 focus:ring-2 focus:ring-orange-200 transition-all">
                    </div>

                    <button type="submit"
                        class="w-full py-2.5 bg-orange-500 hover:bg-orange-600 text-white rounded-lg text-sm font-medium transition-all">
                        Tambah Meja
                    </button>
                </form>
            </div>

            {{-- Table List --}}
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm ring-1 ring-slate-200/80">
                <h3 class="text-slate-800 font-semibold mb-4">Daftar Meja</h3>
                <div class="space-y-2 max-h-72 overflow-y-auto">
                    @forelse($mejas as $meja)
                        <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl border border-slate-200 hover:border-slate-300 transition-colors">
                            <div class="space-y-1">
                                <p class="text-sm text-slate-800 font-semibold">{{ $meja->nama_meja }}</p>
                                <p class="text-xs text-slate-500">Kapasitas: {{ $meja->kapasitas }} | Posisi: ({{ $meja->pos_x }}, {{ $meja->pos_y }})</p>
                                @if($meja->merge_group)
                                    <p class="text-xs text-orange-500">Grup {{ $meja->merge_group }}</p>
                                @endif
                            </div>
                            <div class="flex items-center gap-2">
                                <button onclick="editTable({{ $meja->id }})"
                                    class="p-2.5 bg-white hover:bg-slate-100 text-slate-700 rounded-xl border border-slate-200 transition-colors shadow-sm">
                                    ✏️
                                </button>
                                <button onclick="deleteTable({{ $meja->id }})"
                                    class="p-2.5 bg-red-50 hover:bg-red-100 text-red-600 rounded-xl border border-red-100 transition-colors shadow-sm">
                                    🗑️
                                </button>
                            </div>
                        </div>
                    @empty
                        <p class="text-slate-500 text-sm text-center py-4">Belum ada meja</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Edit Table Modal --}}
<div id="editTableModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-slate-950/30 backdrop-blur-sm" onclick="closeEditModal()"></div>
    <div class="relative flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl border border-slate-200 max-w-lg w-full p-6 shadow-sm ring-1 ring-slate-200/80">
            <h3 class="text-slate-800 font-semibold mb-4">Edit Meja</h3>
            <form id="editTableForm" method="POST" enctype="multipart/form-data" class="space-y-3">
                @csrf
                @method('POST')
                <input type="hidden" name="_method" value="POST">

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-xs text-slate-500 mb-1 block">Nama Meja</label>
                        <input type="text" name="nama_meja" id="edit_nama_meja"
                            class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 focus:border-orange-400 focus:ring-2 focus:ring-orange-200" required>
                    </div>
                    <div>
                        <label class="text-xs text-slate-500 mb-1 block">Nomor Meja</label>
                        <input type="text" name="nomor_meja" id="edit_nomor_meja"
                            class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 focus:border-orange-400 focus:ring-2 focus:ring-orange-200" required>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-xs text-slate-500 mb-1 block">Kapasitas</label>
                        <input type="number" name="kapasitas" id="edit_kapasitas" min="1" max="20"
                            class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 focus:border-orange-400 focus:ring-2 focus:ring-orange-200" required>
                    </div>
                    <div>
                        <label class="text-xs text-slate-500 mb-1 block">Kategori</label>
                        <select name="kategori" id="edit_kategori"
                            class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 focus:border-orange-400 focus:ring-2 focus:ring-orange-200">
                            <option value="regular">Regular</option>
                            <option value="vip">VIP</option>
                            <option value="booth">Booth</option>
                        </select>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="is_mergeable" id="edit_is_mergeable" value="1"
                            class="h-4 w-4 rounded border-slate-300 text-orange-500 focus:ring-orange-500">
                        <label for="edit_is_mergeable" class="text-xs text-slate-600">Dapat digabung</label>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="is_active" id="edit_is_active" value="1"
                            class="h-4 w-4 rounded border-slate-300 text-orange-500 focus:ring-orange-500">
                        <label for="edit_is_active" class="text-xs text-slate-600">Aktif</label>
                    </div>
                </div>

                <div id="editMergeGroupField">
                    <label class="text-xs text-slate-500 mb-1 block">Grup Penggabungan</label>
                    <input type="text" name="merge_group" id="edit_merge_group"
                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-800 focus:border-orange-400 focus:ring-2 focus:ring-orange-200">
                </div>

                <div>
                    <label class="text-xs text-slate-500 mb-1 block">Foto Meja</label>
                    <input type="file" name="table_image" accept="image/*"
                            class="w-full text-sm text-slate-500 bg-white border border-slate-200 rounded-xl px-4 py-2.5 transition-all file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-orange-500/10 file:text-orange-400 hover:file:bg-orange-500/20">
                    <div class="mt-4 flex flex-col gap-2 sm:flex-row">
                        <button type="submit"
                            class="flex-1 py-2.5 bg-orange-500 hover:bg-orange-600 text-white rounded-lg text-sm font-medium transition-all">
                            Simpan Perubahan
                        </button>
                        <button type="button" onclick="closeEditModal()"
                            class="w-full sm:w-auto px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-sm transition-all">
                            Batal
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    let dragMode = false;
    let dragItem = null;
    let dragOffset = { x: 0, y: 0 };
    let positionsChanged = [];

    // Toggle merge group field
    document.getElementById('is_mergeable')?.addEventListener('change', function() {
        document.getElementById('mergeGroupField').classList.toggle('hidden', !this.checked);
    });

    document.getElementById('edit_is_mergeable')?.addEventListener('change', function() {
        document.getElementById('editMergeGroupField').classList.toggle('hidden', !this.checked);
    });

    // Add table
    document.getElementById('addTableForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        const formData = new FormData(form);

        fetch('{{ route("admin.layout.table.store") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Gagal menambah meja: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(err => {
            alert('Terjadi kesalahan saat menambah meja.');
            console.error(err);
        });
    });

    // Drag Mode
    function enableDragMode() {
        dragMode = !dragMode;
        const btn = document.getElementById('dragModeBtn');
        const saveBtn = document.getElementById('savePosBtn');

        if (dragMode) {
            btn.textContent = '🔒 Lock Mode';
            btn.classList.remove('bg-slate-100', 'text-slate-700');
            btn.classList.add('bg-orange-500', 'text-white');
            saveBtn.classList.remove('hidden');
            document.querySelectorAll('.table-item').forEach(el => {
                el.style.cursor = 'grab';
                el.addEventListener('mousedown', startDrag);
                el.addEventListener('touchstart', startDragTouch, { passive: true });
            });
        } else {
            btn.textContent = '✋ Drag Mode';
            btn.classList.remove('bg-orange-500', 'text-white');
            btn.classList.add('bg-slate-100', 'text-slate-700');
            saveBtn.classList.add('hidden');
            document.querySelectorAll('.table-item').forEach(el => {
                el.style.cursor = 'move';
                el.removeEventListener('mousedown', startDrag);
                el.removeEventListener('touchstart', startDragTouch);
            });
        }
    }

    function startDrag(e) {
        if (!dragMode) return;
        e.preventDefault();
        dragItem = e.currentTarget;
        const rect = dragItem.getBoundingClientRect();
        dragOffset = {
            x: e.clientX - rect.left,
            y: e.clientY - rect.top
        };
        dragItem.style.cursor = 'grabbing';
        dragItem.style.zIndex = '100';
        dragItem.style.transition = 'none';

        document.addEventListener('mousemove', onDrag);
        document.addEventListener('mouseup', stopDrag);
    }

    function startDragTouch(e) {
        if (!dragMode) return;
        const touch = e.touches[0];
        dragItem = e.currentTarget;
        const rect = dragItem.getBoundingClientRect();
        dragOffset = {
            x: touch.clientX - rect.left,
            y: touch.clientY - rect.top
        };
        dragItem.style.zIndex = '100';
        dragItem.style.transition = 'none';

        document.addEventListener('touchmove', onDragTouch, { passive: false });
        document.addEventListener('touchend', stopDragTouch);
    }

    function onDrag(e) {
        if (!dragItem) return;
        const canvas = document.getElementById('layoutCanvas');
        const canvasRect = canvas.getBoundingClientRect();
        let x = e.clientX - canvasRect.left - dragOffset.x;
        let y = e.clientY - canvasRect.top - dragOffset.y;
        x = Math.max(0, Math.min(x, canvasRect.width - 80));
        y = Math.max(0, Math.min(y, canvasRect.height - 80));
        dragItem.style.left = x + 'px';
        dragItem.style.top = y + 'px';

        // Track change
        const id = dragItem.dataset.id;
        const existing = positionsChanged.find(p => p.id == id);
        if (existing) {
            existing.pos_x = Math.round(x);
            existing.pos_y = Math.round(y);
        } else {
            positionsChanged.push({ id: parseInt(id), pos_x: Math.round(x), pos_y: Math.round(y) });
        }
    }

    function onDragTouch(e) {
        e.preventDefault();
        if (!dragItem) return;
        const touch = e.touches[0];
        const canvas = document.getElementById('layoutCanvas');
        const canvasRect = canvas.getBoundingClientRect();
        let x = touch.clientX - canvasRect.left - dragOffset.x;
        let y = touch.clientY - canvasRect.top - dragOffset.y;
        x = Math.max(0, Math.min(x, canvasRect.width - 80));
        y = Math.max(0, Math.min(y, canvasRect.height - 80));
        dragItem.style.left = x + 'px';
        dragItem.style.top = y + 'px';

        const id = dragItem.dataset.id;
        const existing = positionsChanged.find(p => p.id == id);
        if (existing) {
            existing.pos_x = Math.round(x);
            existing.pos_y = Math.round(y);
        } else {
            positionsChanged.push({ id: parseInt(id), pos_x: Math.round(x), pos_y: Math.round(y) });
        }
    }

    function stopDrag() {
        if (!dragItem) return;
        dragItem.style.cursor = 'grab';
        dragItem.style.zIndex = '';
        dragItem.style.transition = '';
        dragItem = null;
        document.removeEventListener('mousemove', onDrag);
        document.removeEventListener('mouseup', stopDrag);
    }

    function stopDragTouch() {
        if (!dragItem) return;
        dragItem.style.zIndex = '';
        dragItem.style.transition = '';
        dragItem = null;
        document.removeEventListener('touchmove', onDragTouch);
        document.removeEventListener('touchend', stopDragTouch);
    }

    function savePositions() {
        if (positionsChanged.length === 0) {
            alert('Tidak ada perubahan posisi.');
            return;
        }

        fetch('{{ route("admin.layout.bulk-positions") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ positions: positionsChanged })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                positionsChanged = [];
                alert('Posisi meja berhasil disimpan!');
            } else {
                alert('Gagal menyimpan posisi.');
            }
        })
        .catch(err => {
            alert('Terjadi kesalahan.');
            console.error(err);
        });
    }

    // Edit table
    function editTable(id) {
        fetch(`/admin/layout/table/${id}`, {
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.table) {
                document.getElementById('edit_nama_meja').value = data.table.nama_meja || '';
                document.getElementById('edit_nomor_meja').value = data.table.nomor_meja || '';
                document.getElementById('edit_kapasitas').value = data.table.kapasitas || 4;
                document.getElementById('edit_kategori').value = data.table.kategori || 'regular';
                document.getElementById('edit_is_mergeable').checked = data.table.is_mergeable || false;
                document.getElementById('edit_is_active').checked = data.table.is_active !== false;
                document.getElementById('edit_merge_group').value = data.table.merge_group || '';
                document.getElementById('editTableModal').classList.remove('hidden');

                // Update form action
                const form = document.getElementById('editTableForm');
                form.action = `{{ url('admin/layout/table') }}/${id}`;

                document.getElementById('editMergeGroupField').classList.toggle('hidden', !data.table.is_mergeable);
            }
        })
        .catch(err => {
            alert('Gagal memuat data meja.');
            console.error(err);
        });
    }

    function closeEditModal() {
        document.getElementById('editTableModal').classList.add('hidden');
    }

    // Delete table
    function deleteTable(id) {
        if (!confirm('Yakin ingin menghapus meja ini?')) return;

        fetch(`{{ url('admin/layout/table') }}/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Gagal menghapus meja.');
            }
        })
        .catch(err => {
            alert('Terjadi kesalahan.');
            console.error(err);
        });
    }
</script>
@endpush

