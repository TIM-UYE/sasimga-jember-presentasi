@extends('admin.layout.main')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-slate-800 tracking-tight">Kelola Meja</h1>
            <p class="text-slate-500 text-sm mt-1">Tambahkan dan hapus konfigurasi meja untuk reservasi.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="rounded-3xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white rounded-3xl p-6 shadow-sm ring-1 ring-slate-200/80">
            <h2 class="text-lg font-semibold text-slate-800 mb-4">Tambah Meja Baru</h2>

            <form action="{{ route('admin.meja.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-2">Nama Meja</label>
                    <input type="text" name="nama_meja" value="{{ old('nama_meja') }}"
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 focus:border-orange-500 focus:outline-none"
                        required>
                    @error('nama_meja')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-2">Kategori</label>
                    <input type="text" name="kategori" value="{{ old('kategori', 'regular') }}"
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 focus:border-orange-500 focus:outline-none"
                        required>
                    @error('kategori')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-2">Kapasitas</label>
                        <input type="number" name="kapasitas" value="{{ old('kapasitas', 4) }}" min="1"
                            class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 focus:border-orange-500 focus:outline-none"
                            required>
                        @error('kapasitas')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-2">Posisi Row</label>
                        <input type="text" name="posisi_row" value="{{ old('posisi_row', 'A') }}"
                            class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 focus:border-orange-500 focus:outline-none"
                            required>
                        @error('posisi_row')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-2">Posisi Kolom</label>
                        <input type="number" name="posisi_col" value="{{ old('posisi_col', 1) }}" min="1"
                            class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 focus:border-orange-500 focus:outline-none"
                            required>
                        @error('posisi_col')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="flex items-center gap-3 pt-6">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                        <label for="is_active" class="text-sm text-slate-600">Aktif</label>
                    </div>
                </div>

                <button type="submit"
                    class="w-full rounded-2xl bg-linear-to-r from-orange-500 to-amber-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-orange-500/20 hover:shadow-xl transition-all duration-200">
                    Tambahkan Meja
                </button>
            </form>
        </div>

        <div class="lg:col-span-2 bg-white rounded-3xl p-6 shadow-sm ring-1 ring-slate-200/80 overflow-x-auto">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-lg font-semibold text-slate-800">Daftar Meja</h2>
                    <p class="text-sm text-slate-500">Atur meja yang tersedia untuk reservasi.</p>
                </div>
                <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-500">
                    {{ $mejas->count() }} Meja
                </span>
            </div>

            <table class="min-w-full text-sm text-left">
                <thead>
                    <tr class="border-b border-slate-200 text-xs uppercase tracking-wide text-slate-500">
                        <th class="px-4 py-3">Nama Meja</th>
                        <th class="px-4 py-3">Kategori</th>
                        <th class="px-4 py-3">Kapasitas</th>
                        <th class="px-4 py-3">Posisi</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($mejas as $meja)
                        <tr>
                            <td class="px-4 py-3 text-slate-700">{{ $meja->nama_meja }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ ucfirst($meja->kategori) }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $meja->kapasitas }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $meja->posisi_row }}{{ $meja->posisi_col }}</td>
                            <td class="px-4 py-3">
                                <span class="rounded-full px-3 py-1 text-[11px] font-semibold {{ $meja->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                                    {{ $meja->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <form action="{{ route('admin.meja.destroy', $meja->id) }}" method="POST" onsubmit="return confirm('Hapus meja {{ $meja->nama_meja }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded-full bg-red-500 px-3 py-2 text-xs font-semibold text-white hover:bg-red-600 transition">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-slate-500">Belum ada meja terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
