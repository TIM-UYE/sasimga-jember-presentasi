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
            <h1 class="text-2xl font-bold tracking-tight text-slate-800 md:text-3xl">Edit Menu</h1>
            <p class="mb-0 text-sm text-slate-500">Perbarui data menu agar informasi tetap akurat.</p>
        </div>
        <a href="{{ route('admin.menu.index') }}" class="btn-admin-secondary">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>

    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/80">
        <form action="{{ route('admin.menu.update', $menu) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <!-- Nama Menu -->
                <div class="mb-4">
                    <label class="mb-2 block text-sm font-semibold text-slate-700" for="nama_menu">
                        Nama Menu <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama_menu" id="nama_menu" value="{{ old('nama_menu', $menu->nama_menu) }}"
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
                            <option value="{{ $kategori->id }}" {{ old('kategori_id', $menu->kategori_id) == $kategori->id ? 'selected' : '' }}>
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
                    <input type="number" name="harga" id="harga" value="{{ old('harga', $menu->harga) }}"
                        class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200"
                        placeholder="25000" min="0" step="100" required>
                </div>

                <!-- Stok Menu (Dropdown Ada / Habis) -->
                <div class="mb-4">
                    <label class="mb-2 block text-sm font-semibold text-slate-700" for="is_available">
                        Stok Menu <span class="text-red-500">*</span>
                    </label>
                    <select name="is_available" id="is_available"
                        class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200" required>
                        <option value="1" {{ old('is_available', $menu->is_available) == 1 ? 'selected' : '' }}>Ada</option>
                        <option value="0" {{ old('is_available', $menu->is_available) == 0 ? 'selected' : '' }}>Habis</option>
                    </select>
                </div>

                <!-- Ukuran -->
                <div class="mb-4">
                    <label class="mb-2 block text-sm font-semibold text-slate-700" for="ukuran">
                        Ukuran
                    </label>
                    <input type="text" name="ukuran" id="ukuran" value="{{ old('ukuran', $menu->ukuran) }}"
                        class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200"
                        placeholder="Contoh: Kecil, Sedang, Besar">
                </div>

                <!-- Durasi Persiapan -->
                <div class="mb-4">
                    <label class="mb-2 block text-sm font-semibold text-slate-700" for="durasi_persiapan">
                        Durasi Persiapan (menit)
                    </label>
                    <input type="number" name="durasi_persiapan" id="durasi_persiapan" value="{{ old('durasi_persiapan', $menu->durasi_persiapan) }}"
                        class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200"
                        placeholder="15" min="1">
                </div>
            </div>

            <!-- Deskripsi -->
            <div class="mb-4">
                <label class="mb-2 block text-sm font-semibold text-slate-700" for="deskripsi">
                    Deskripsi
                </label>
                <textarea name="deskripsi" id="deskripsi" rows="4"
                    class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200"
                    placeholder="Deskripsi menu...">{{ old('deskripsi', $menu->deskripsi) }}</textarea>
            </div>

            <!-- Gambar -->
            <div class="mb-4">
                <label class="mb-2 block text-sm font-semibold text-slate-700" for="gambar">
                    Gambar Menu
                </label>
                <div class="rounded-xl border-2 border-dashed border-slate-300 p-5 text-center transition hover:border-orange-400">
                    <input type="file" name="gambar" id="gambar" accept="image/*" class="hidden" onchange="previewImage(event)">
                    <label for="gambar" class="cursor-pointer">
                        <i class="fas fa-cloud-upload-alt mb-2 text-3xl text-slate-400"></i>
                        <p class="text-sm font-semibold text-slate-600">Klik untuk upload gambar baru</p>
                        <p class="text-xs text-slate-400">Format: jpeg, png, jpg, gif, webp (maks 2MB)</p>
                    </label>
                </div>

                <div id="image-preview" class="mt-2 {{ $menu->gambar ? '' : 'hidden' }}">
                    <img id="preview-img" src="{{ $menu->gambar ? asset('storage/menu/' . $menu->gambar) : '' }}" alt="Preview" class="h-48 w-48 rounded-xl object-cover ring-1 ring-slate-200">
                </div>

                @if($menu->gambar)
                    <p class="mt-2 text-sm text-slate-500">Gambar saat ini: {{ $menu->gambar }}</p>
                @endif
            </div>

            <div class="mt-6 flex justify-end gap-2 border-t border-slate-100 pt-5">
                <a href="{{ route('admin.menu.index') }}" class="btn-admin-secondary">Batal</a>
                <button type="submit" class="btn-admin">Update Menu</button>
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
</script>
@endsection
