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
            <h1 class="text-2xl font-bold tracking-tight text-slate-800 md:text-3xl">Kelola Kategori</h1>
            <p class="mt-1 text-sm text-slate-500">Atur kategori menu agar data lebih rapi dan mudah dikelola.</p>
        </div>
        <a href="{{ route('admin.kategori.create') }}" class="btn-admin">
            <i class="fas fa-plus mr-1"></i>Tambah Kategori
        </a>
    </div>
<div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

    <!-- Total Kategori -->
    <div class="group flex items-center justify-between rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200/80 transition-all duration-300 hover:shadow-lg hover:shadow-orange-500/10 hover:ring-orange-200">
        <div>
            <p class="text-xs font-medium uppercase tracking-wider text-slate-400">
                Total Kategori
            </p>
            <p class="mt-1 text-3xl font-bold text-slate-800">
                {{ $kategoris->count() }}
            </p>
        </div>

        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-orange-400 to-orange-600 text-white shadow-lg shadow-orange-200/50">
            <i class="fas fa-layer-group text-lg"></i>
        </div>
    </div>

    <!-- Kategori Aktif -->
    <div class="group flex items-center justify-between rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200/80 transition-all duration-300 hover:shadow-lg hover:shadow-emerald-500/10 hover:ring-emerald-200">
        <div>
            <p class="text-xs font-medium uppercase tracking-wider text-slate-400">
                Kategori Aktif
            </p>
            <p class="mt-1 text-3xl font-bold text-slate-800">
                {{ $kategoris->where('is_active', true)->count() }}
            </p>
        </div>

        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-400 to-emerald-600 text-white shadow-lg shadow-emerald-200/50">
            <i class="fas fa-check-circle text-lg"></i>
        </div>
    </div>

</div>

    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200/80">
        <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
            <div class="flex items-center gap-2">
                <i class="fas fa-layer-group text-slate-400"></i>
                <span class="text-sm font-semibold text-slate-700">Daftar Kategori</span>
                <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-medium text-slate-500"><i class="fas fa-database"></i>{{ $kategoris->count() }} Total</span>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[760px] text-left text-sm text-slate-600">
                <thead>
                    <tr class="bg-slate-50/80">
                            <th class="px-6 py-4 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">No</th>
                            <th class="px-6 py-4 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">Nama Kategori</th>
                            <th class="px-6 py-4 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">Deskripsi</th>
                            <th class="px-6 py-4 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">Status</th>
                            <th class="px-6 py-4 text-right text-[11px] font-semibold uppercase tracking-wider text-slate-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($kategoris as $index => $kategori)
                    <tr class="group transition-all duration-200 hover:bg-slate-50/60">
                        <td class="px-6 py-4 text-xs font-medium text-slate-400">
                            {{ $index + 1 }}
                        </td>
                        <td class="px-6 py-4">
                            <h6 class="mb-0 text-sm font-semibold leading-normal text-slate-700">{{ $kategori->nama_kategori }}</h6>
                        </td>
                        <td class="px-6 py-4">
                            <p class="mb-0 text-sm text-slate-500">{{ Str::limit($kategori->deskripsi, 60) ?: '-' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-medium ring-1 {{ $kategori->is_active ? 'bg-emerald-50 text-emerald-700 ring-emerald-200/50' : 'bg-slate-100 text-slate-600 ring-slate-200/70' }}">
                                <span class="h-1.5 w-1.5 rounded-full {{ $kategori->is_active ? 'bg-emerald-400' : 'bg-slate-400' }}"></span>
                                {{ $kategori->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.kategori.edit', $kategori) }}" class="btn-admin-secondary">
                                    Edit
                                </a>
                                <form action="{{ route('admin.kategori.destroy', $kategori) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn-admin-danger">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="mx-auto max-w-sm text-slate-500">
                                    <i class="fas fa-layer-group mb-3 text-2xl text-slate-300"></i>
                                    <p class="mb-1 font-semibold text-slate-600">Belum ada kategori</p>
                                    <p class="mb-0 text-sm">Silakan tambahkan kategori baru untuk mulai mengelompokkan menu.</p>
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
