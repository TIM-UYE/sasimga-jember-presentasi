@extends('admin.layout.main')

@section('content')
@if(session('success'))
    <div class="mb-4 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
    </div>
@endif

<div class="space-y-6">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-800 md:text-3xl">Kelola Menu</h1>
            <p class="mt-1 text-sm text-slate-500">Kelola daftar menu dan status ketersediaan.</p>
        </div>
        <a href="{{ route('admin.menu.create') }}" class="btn-admin"><i class="fas fa-plus mr-1"></i>Tambah Menu</a>
    </div>

    <!-- Stats Cards (Grid disesuaikan menjadi 2 kolom) -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <!-- Total Menu -->
        <div class="group flex items-center justify-between rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200/80 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-orange-500/10 hover:ring-orange-200">
            <div>
                <p class="text-xs font-medium uppercase tracking-wider text-slate-400">Total Menu</p>
                <p class="mt-1 text-3xl font-bold text-slate-800">{{ $menus->count() }}</p>
            </div>
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-orange-400 to-orange-600 text-white shadow-lg shadow-orange-200/50">
                <i class="fas fa-utensils text-lg"></i>
            </div>
        </div>

        <!-- Menu Tersedia -->
        <div class="group flex items-center justify-between rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200/80 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-emerald-500/10 hover:ring-emerald-200">
            <div>
                <p class="text-xs font-medium uppercase tracking-wider text-slate-400">Menu Tersedia</p>
                <p class="mt-1 text-3xl font-bold text-slate-800">{{ $menus->where('is_available', true)->count() }}</p>
            </div>
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-400 to-emerald-600 text-white shadow-lg shadow-emerald-200/50">
                <i class="fas fa-check-circle text-lg"></i>
            </div>
        </div>
    </div>

    <!-- Tabel Menu -->
    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200/80">
        <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
            <div class="flex items-center gap-2">
                <i class="fas fa-utensils text-slate-400"></i>
                <span class="text-sm font-semibold text-slate-700">Daftar Menu</span>
                <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-medium text-slate-500">
                    <i class="fas fa-database"></i>{{ $menus->count() }} Total
                </span>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[700px] text-left text-sm text-slate-600">
                <thead>
                    <tr class="bg-slate-50/80">
                        <th class="px-6 py-4 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">Gambar</th>
                        <th class="px-6 py-4 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">Nama Menu</th>
                        <th class="px-6 py-4 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">Kategori</th>
                        <th class="px-6 py-4 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">Harga</th>
                        <th class="px-6 py-4 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">Status</th>
                        <th class="px-6 py-4 text-right text-[11px] font-semibold uppercase tracking-wider text-slate-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($menus as $menu)
                        <tr class="group transition-all duration-200 hover:bg-slate-50/60">
                            <td class="px-6 py-4 align-middle">
                                @if($menu->gambar)
                                    <img src="{{ asset('storage/menu/' . $menu->gambar) }}" alt="{{ $menu->nama_menu }}" class="h-16 w-16 rounded-xl object-cover ring-1 ring-slate-200">
                                @else
                                    <div class="flex h-16 w-16 items-center justify-center rounded-xl bg-slate-100">
                                        <i class="fas fa-utensils text-slate-400"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 align-middle">
                                <h6 class="mb-0 text-sm font-semibold leading-normal text-slate-700">{{ $menu->nama_menu }}</h6>
                                <p class="mb-0 text-xs text-slate-500">{{ Str::limit($menu->deskripsi, 60) }}</p>
                            </td>
                            <td class="px-6 py-4 align-middle">
                                <span class="inline-flex rounded-full bg-purple-100 px-2.5 py-1 text-xs font-semibold text-purple-700">
                                    {{ $menu->kategori->nama_kategori ?? 'Tidak ada' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 align-middle">
                                <p class="mb-0 text-sm font-semibold text-slate-700">Rp {{ number_format($menu->harga, 0, ',', '.') }}</p>
                            </td>
                            <td class="px-6 py-4 align-middle">
                                <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $menu->is_available ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                    {{ $menu->is_available ? 'Tersedia' : 'Tidak Tersedia' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 align-middle text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.menu.show', $menu->id) }}" class="btn-admin-secondary">Detail</a>
                                    <a href="{{ route('admin.menu.edit', $menu->id) }}" class="btn-admin-secondary">Edit</a>
                                    <form action="{{ route('admin.menu.destroy', $menu->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus menu ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn-admin-danger">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="mx-auto max-w-sm text-slate-500">
                                    <i class="fas fa-utensils mb-3 text-2xl text-slate-300"></i>
                                    <p class="mb-1 font-semibold text-slate-600">Menu belum tersedia</p>
                                    <p class="mb-0 text-sm">Tambahkan menu pertama untuk mulai menampilkan data restoran.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
