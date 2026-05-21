@extends('admin.layout.main')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-800 md:text-3xl">Tambah Stok Bahan</h1>
            <p class="mt-1 text-sm text-slate-500">Tambahkan bahan makanan yang akan digunakan dalam komposisi menu.</p>
        </div>

        <a href="{{ route('admin.stok.index') }}" class="btn-admin-secondary">
            <i class="fas fa-arrow-left mr-1"></i>Kembali
        </a>
    </div>

    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200/80">
        <div class="border-b border-slate-100 px-6 py-4">
            <div class="flex items-center gap-2">
                <i class="fas fa-boxes-stacked text-slate-400"></i>
                <span class="text-sm font-semibold text-slate-700">Form Tambah Bahan</span>
            </div>
        </div>

        <form action="{{ route('admin.stok.store') }}" method="POST" class="space-y-5 p-6">
            @csrf

            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">Nama Bahan</label>
                <input type="text" name="nama_bahan" value="{{ old('nama_bahan') }}"
                    class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-orange-400 focus:ring-4 focus:ring-orange-100"
                    placeholder="Contoh: Daging Kambing" required>

                @error('nama_bahan')
                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Jumlah Stok</label>
                    <input type="number" step="0.01" name="jumlah_stok" value="{{ old('jumlah_stok') }}"
                        class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-orange-400 focus:ring-4 focus:ring-orange-100"
                        placeholder="Contoh: 5000" required>

                    @error('jumlah_stok')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Satuan</label>
                    <select name="satuan"
                        class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-orange-400 focus:ring-4 focus:ring-orange-100"
                        required>
                        <option value="">-- Pilih Satuan --</option>
                        <option value="gram" {{ old('satuan') == 'gram' ? 'selected' : '' }}>Gram</option>
                        <option value="kg" {{ old('satuan') == 'kg' ? 'selected' : '' }}>Kg</option>
                        <option value="pcs" {{ old('satuan') == 'pcs' ? 'selected' : '' }}>Pcs</option>
                        <option value="liter" {{ old('satuan') == 'liter' ? 'selected' : '' }}>Liter</option>
                        <option value="ml" {{ old('satuan') == 'ml' ? 'selected' : '' }}>Ml</option>
                    </select>

                    @error('satuan')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Stok Minimum</label>
                    <input type="number" step="0.01" name="stok_minimum" value="{{ old('stok_minimum') }}"
                        class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-orange-400 focus:ring-4 focus:ring-orange-100"
                        placeholder="Contoh: 500" required>

                    @error('stok_minimum')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center justify-end gap-2 border-t border-slate-100 pt-5">
                <a href="{{ route('admin.stok.index') }}" class="btn-admin-secondary">Batal</a>
                <button type="submit" class="btn-admin">
                    <i class="fas fa-save mr-1"></i>Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection