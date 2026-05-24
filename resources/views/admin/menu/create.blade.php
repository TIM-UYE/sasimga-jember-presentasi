@extends('admin.layout.main')

@section('content')
@if($errors->any())
    <div class="mb-4 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
        <p class="mb-2 font-semibold"><i class="fas fa-exclamation-circle mr-2"></i>Periksa kembali data menu:</p>
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="space-y-6">
    <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-800 md:text-3xl">Tambah Menu Baru</h1>
            <p class="mb-0 text-sm text-slate-500">Isi informasi utama menu agar siap ditampilkan dan dipesan.</p>
        </div>
        <a href="{{ route('admin.menu.index') }}" class="btn-admin-secondary">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>

    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/80">
        <form action="{{ route('admin.menu.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <!-- Nama Menu -->
                <div class="mb-4">
                    <label class="mb-2 block text-sm font-semibold text-slate-700" for="nama_menu">
                        Nama Menu <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama_menu" id="nama_menu" value="{{ old('nama_menu') }}"
                        class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200"
                        placeholder="Contoh: Sate Kambing Special" required>
                </div>

                <!-- Kategori -->
                <div class="mb-4">
                    <label class="mb-2 block text-sm font-semibold text-slate-700" for="kategori_id">
                        Kategori
                    </label>
                    <select name="kategori_id" id="kategori_id"
                        class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200">
                        <option value="">Pilih Kategori</option>
                        @foreach($kategoris as $kategori)
                            <option value="{{ $kategori->id }}" {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                {{ $kategori->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Harga -->
                <div class="mb-4">
                    <label class="mb-2 block text-sm font-semibold text-slate-700" for="harga">
                        Harga (Rp) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="harga" id="harga" value="{{ old('harga') }}"
                        class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200"
                        placeholder="25000" min="0" step="100" required>
                </div>

                <!-- Stok (Auto-calculated from ingredients) -->
                <div class="mb-4">
                    <label class="mb-2 block text-sm font-semibold text-slate-700" for="stok">
                        Stok Menu
                    </label>
                    <div class="flex items-center gap-2 rounded-xl border border-orange-200 bg-orange-50 px-4 py-2.5 text-sm text-orange-700">
                        <i class="fas fa-calculator"></i>
                        <span>Stok akan dihitung otomatis dari bahan baku yang ditambahkan di bawah</span>
                    </div>
                    <input type="hidden" name="stok" value="0">
                </div>

                <!-- Ukuran -->
                <div class="mb-4">
                    <label class="mb-2 block text-sm font-semibold text-slate-700" for="ukuran">
                        Ukuran
                    </label>
                    <input type="text" name="ukuran" id="ukuran" value="{{ old('ukuran') }}"
                        class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200"
                        placeholder="Contoh: Kecil, Sedang, Besar">
                </div>

                <!-- Durasi Persiapan -->
                <div class="mb-4">
                    <label class="mb-2 block text-sm font-semibold text-slate-700" for="durasi_persiapan">
                        Durasi Persiapan (menit)
                    </label>
                    <input type="number" name="durasi_persiapan" id="durasi_persiapan" value="{{ old('durasi_persiapan', 15) }}"
                        class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200"
                        placeholder="15" min="1">
                </div>

                <!-- Status Tersedia -->
                <div class="mb-4 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_available" id="is_available" value="1" {{ old('is_available', true) == true ? 'checked' : '' }}
                            class="h-4 w-4 rounded border-slate-300 text-orange-500 focus:ring-orange-500">
                        <span class="ml-2 text-sm text-slate-700">Menu tersedia untuk pemesanan</span>
                    </label>
                </div>
            </div>

            <!-- Deskripsi -->
            <div class="mb-4">
                <label class="mb-2 block text-sm font-semibold text-slate-700" for="deskripsi">
                    Deskripsi
                </label>
                <textarea name="deskripsi" id="deskripsi" rows="4"
                    class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200"
                    placeholder="Deskripsi menu...">{{ old('deskripsi') }}</textarea>
            </div>

            <!-- Bahan -->
            <div class="mb-4">
                <label class="mb-2 block text-sm font-semibold text-slate-700" for="bahan">
                    Bahan Utama
                </label>
                <textarea name="bahan" id="bahan" rows="2"
                    class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200"
                    placeholder="Contoh: Daging kambing, Bumbu kacang, Bawang merah...">{{ old('bahan') }}</textarea>
            </div>

            <!-- Komposisi Stok Bahan -->
            <div class="mb-4 rounded-2xl border border-orange-100 bg-orange-50/40 p-4">
                <div class="mb-3 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700">
                            Komposisi Stok Bahan
                        </label>
                        <p class="mt-1 text-xs text-slate-500">
                            Pilih bahan dan isi jumlah kebutuhan untuk 1 porsi menu.
                        </p>
                    </div>

                    <button type="button" onclick="addBahanRow()" class="btn-admin-secondary">
                        <i class="fas fa-plus mr-1"></i>Tambah Bahan
                    </button>
                </div>

                <div id="bahan-wrapper" class="space-y-3">
                    <div class="grid grid-cols-1 gap-3 rounded-xl bg-white p-3 ring-1 ring-slate-200 md:grid-cols-12">
                        <div class="md:col-span-4">
                            <label class="mb-1 block text-xs font-semibold text-slate-500">Bahan</label>
                            <select name="bahan_stok_id[]"
                                class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200">
                                <option value="">Pilih Bahan</option>
                                @foreach($stoks as $stok)
                                    <option value="{{ $stok->id }}" data-satuan="{{ $stok->satuan }}">
                                        {{ $stok->nama_bahan }} ({{ number_format($stok->jumlah_stok, 0, ',', '.') }} {{ $stok->satuan }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="md:col-span-3">
                            <label class="mb-1 block text-xs font-semibold text-slate-500">Jumlah / Porsi</label>
                            <input type="number" step="0.01" name="jumlah_dibutuhkan[]"
                                class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200 quantity-input"
                                placeholder="Contoh: 150">
                        </div>

                        <div class="md:col-span-3">
                            <label class="mb-1 block text-xs font-semibold text-slate-500">Satuan</label>
                            <input type="text" name="satuan_bahan[]" readonly
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-500 satuan-input"
                                placeholder="Otomatis" value="gram">
                        </div>

                        <div class="flex items-end md:col-span-2">
                            <button type="button" onclick="removeBahanRow(this)"
                                class="w-full rounded-xl bg-rose-100 px-4 py-2.5 text-sm font-semibold text-rose-700 hover:bg-rose-200">
                                Hapus
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gambar -->
            <div class="mb-4">
                <label class="mb-2 block text-sm font-semibold text-slate-700" for="gambar">
                    Gambar Menu
                </label>
                <div class="rounded-xl border-2 border-dashed border-slate-300 p-5 text-center transition hover:border-purple-400">
                    <input type="file" name="gambar" id="gambar" accept="image/*" class="hidden" onchange="previewImage(event)">
                    <label for="gambar" class="cursor-pointer">
                        <i class="fas fa-cloud-upload-alt mb-2 text-3xl text-slate-400"></i>
                        <p class="text-sm font-semibold text-slate-600">Klik untuk upload gambar</p>
                        <p class="text-xs text-slate-400">Format: jpeg, png, jpg, gif, webp (maks 2MB)</p>
                    </label>
                </div>
                <div id="image-preview" class="mt-2 hidden">
                    <img id="preview-img" src="" alt="Preview" class="h-48 w-48 rounded-xl object-cover ring-1 ring-slate-200">
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-2 border-t border-slate-100 pt-5">
                <a href="{{ route('admin.menu.index') }}" class="btn-admin-secondary">Batal</a>
                <button type="submit" class="btn-admin">Simpan Menu</button>
            </div>
        </form>
    </div>
</div>

<script>
function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview-img').src = e.target.result;
            document.getElementById('image-preview').classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }
}

function addBahanRow() {
    const wrapper = document.getElementById('bahan-wrapper');

    const row = document.createElement('div');
    row.className = 'grid grid-cols-1 gap-3 rounded-xl bg-white p-3 ring-1 ring-slate-200 md:grid-cols-12';

    row.innerHTML = `
        <div class="md:col-span-4">
            <label class="mb-1 block text-xs font-semibold text-slate-500">Bahan</label>
            <select name="bahan_stok_id[]" onchange="autoFillSatuan(this)"
                class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200">
                <option value="">Pilih Bahan</option>
                @foreach($stoks as $stok)
                    <option value="{{ $stok->id }}" data-satuan="{{ $stok->satuan }}">
                        {{ $stok->nama_bahan }} ({{ number_format($stok->jumlah_stok, 0, ',', '.') }} {{ $stok->satuan }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="md:col-span-3">
            <label class="mb-1 block text-xs font-semibold text-slate-500">Jumlah / Porsi</label>
            <input type="number" step="0.01" name="jumlah_dibutuhkan[]"
                class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200 quantity-input"
                placeholder="Contoh: 150">
        </div>

        <div class="md:col-span-3">
            <label class="mb-1 block text-xs font-semibold text-slate-500">Satuan</label>
            <input type="text" name="satuan_bahan[]" readonly
                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-500 satuan-input"
                placeholder="Otomatis" value="gram">
        </div>

        <div class="flex items-end md:col-span-2">
            <button type="button" onclick="removeBahanRow(this)"
                class="w-full rounded-xl bg-rose-100 px-4 py-2.5 text-sm font-semibold text-rose-700 hover:bg-rose-200">
                Hapus
            </button>
        </div>
    `;

    wrapper.appendChild(row);
}

function removeBahanRow(button) {
    const wrapper = document.getElementById('bahan-wrapper');

    if (wrapper.children.length > 1) {
        button.closest('.grid').remove();
    } else {
        const row = button.closest('.grid');
        const select = row.querySelector('select');
        if (select) select.value = '';
        const inputs = row.querySelectorAll('input');
        inputs.forEach(inp => { if (inp.type !== 'hidden') inp.value = ''; });
    }
}

function autoFillSatuan(selectEl) {
    const row = selectEl.closest('.grid');
    const satuanInput = row.querySelector('.satuan-input');
    const selectedOption = selectEl.options[selectEl.selectedIndex];
    const satuan = selectedOption ? selectedOption.getAttribute('data-satuan') : 'gram';
    if (satuanInput) {
        satuanInput.value = satuan || 'gram';
    }
}

// Initialize auto-fill for existing rows
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('#bahan-wrapper select[name="bahan_stok_id[]"]').forEach(function(sel) {
        sel.addEventListener('change', function() { autoFillSatuan(this); });
        // Trigger on load if a value is selected
        if (sel.value) autoFillSatuan(sel);
    });
});
@endsection
