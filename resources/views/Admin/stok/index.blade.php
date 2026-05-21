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
            <h1 class="text-2xl font-bold tracking-tight text-slate-800 md:text-3xl">Kelola Stok Bahan</h1>
            <p class="mt-1 text-sm text-slate-500">Kelola stok bahan makanan yang digunakan pada menu regular dan menu spesial.</p>
        </div>
        <a href="{{ route('admin.stok.create') }}" class="btn-admin">
            <i class="fas fa-plus mr-1"></i>Tambah Bahan
        </a>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
        <div class="group flex items-center justify-between rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200/80 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-orange-500/10 hover:ring-orange-200">
            <div>
                <p class="text-xs font-medium uppercase tracking-wider text-slate-400">Total Bahan</p>
                <p class="mt-1 text-3xl font-bold text-slate-800">{{ $stok->count() }}</p>
            </div>
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-orange-400 to-orange-600 text-white shadow-lg shadow-orange-200/50">
                <i class="fas fa-boxes-stacked text-lg"></i>
            </div>
        </div>

        <div class="group flex items-center justify-between rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200/80 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-emerald-500/10 hover:ring-emerald-200">
            <div>
                <p class="text-xs font-medium uppercase tracking-wider text-slate-400">Stok Aman</p>
                <p class="mt-1 text-3xl font-bold text-slate-800">
                    {{ $stok->filter(fn($item) => $item->status == 'Aman')->count() }}
                </p>
            </div>
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-400 to-emerald-600 text-white shadow-lg shadow-emerald-200/50">
                <i class="fas fa-check-circle text-lg"></i>
            </div>
        </div>

        <div class="group flex items-center justify-between rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200/80 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-rose-500/10 hover:ring-rose-200">
            <div>
                <p class="text-xs font-medium uppercase tracking-wider text-slate-400">Menipis / Habis</p>
                <p class="mt-1 text-3xl font-bold text-slate-800">
                    {{ $stok->filter(fn($item) => $item->status != 'Aman')->count() }}
                </p>
            </div>
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-rose-400 to-rose-600 text-white shadow-lg shadow-rose-200/50">
                <i class="fas fa-triangle-exclamation text-lg"></i>
            </div>
        </div>
    </div>

    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200/80">
        <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
            <div class="flex items-center gap-2">
                <i class="fas fa-boxes-stacked text-slate-400"></i>
                <span class="text-sm font-semibold text-slate-700">Daftar Stok Bahan</span>
                <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-medium text-slate-500">
                    <i class="fas fa-database"></i>{{ $stok->count() }} Total
                </span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[980px] text-left text-sm text-slate-600">
                <thead>
                    <tr class="bg-slate-50/80">
                        <th class="px-6 py-4 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">No</th>
                        <th class="px-6 py-4 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">Nama Bahan</th>
                        <th class="px-6 py-4 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">Jumlah Stok</th>
                        <th class="px-6 py-4 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">Satuan</th>
                        <th class="px-6 py-4 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">Stok Minimum</th>
                        <th class="px-6 py-4 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">Status</th>
                        <th class="px-6 py-4 text-right text-[11px] font-semibold uppercase tracking-wider text-slate-500">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse($stok as $item)
                        <tr class="group transition-all duration-200 hover:bg-slate-50/60">
                            <td class="px-6 py-4 align-middle">
                                {{ $loop->iteration }}
                            </td>

                            <td class="px-6 py-4 align-middle">
                                <h6 class="mb-0 text-sm font-semibold leading-normal text-slate-700">
                                    {{ $item->nama_bahan }}
                                </h6>
                            </td>

                            <td class="px-6 py-4 align-middle">
                                <span class="font-semibold text-slate-700">
                                    {{ number_format((float) $item->jumlah_stok, 2, ',', '.') }}
                                </span>
                            </td>

                            <td class="px-6 py-4 align-middle">
                                <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">
                                    {{ $item->satuan }}
                                </span>
                            </td>

                            <td class="px-6 py-4 align-middle">
                                {{ number_format((float) $item->stok_minimum, 2, ',', '.') }}
                            </td>

                            <td class="px-6 py-4 align-middle">
                                @if($item->status == 'Habis')
                                    <span class="inline-flex rounded-full bg-rose-100 px-2.5 py-1 text-xs font-semibold text-rose-700">
                                        Habis
                                    </span>
                                @elseif($item->status == 'Menipis')
                                    <span class="inline-flex rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-700">
                                        Menipis
                                    </span>
                                @else
                                    <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                                        Aman
                                    </span>
                                @endif
                            </td>

                            <td class="px-6 py-4 align-middle">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.stok.edit', $item) }}" class="btn-admin-secondary">
                                        Edit
                                    </a>

                                    <form action="{{ route('admin.stok.destroy', $item) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus stok bahan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn-admin-danger">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center">
                                <div class="mx-auto max-w-sm text-slate-500">
                                    <i class="fas fa-box-open mb-3 text-2xl text-slate-300"></i>
                                    <p class="mb-1 font-semibold text-slate-600">Stok bahan belum tersedia</p>
                                    <p class="mb-0 text-sm">Tambahkan bahan pertama untuk mulai mengelola stok makanan.</p>
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