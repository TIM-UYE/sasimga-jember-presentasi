@extends('admin.layout.main')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-800 md:text-3xl">Riwayat Stok</h1>
            <p class="mt-1 text-sm text-slate-500">
                Menampilkan catatan keluar dan masuk stok bahan makanan.
            </p>
        </div>

        <a href="{{ route('admin.stok.index') }}" class="btn-admin-secondary">
            <i class="fas fa-arrow-left mr-1"></i>Kembali ke Stok
        </a>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
        <div class="group flex items-center justify-between rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200/80">
            <div>
                <p class="text-xs font-medium uppercase tracking-wider text-slate-400">Total Log</p>
                <p class="mt-1 text-3xl font-bold text-slate-800">{{ $logs->count() }}</p>
            </div>
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-orange-400 to-orange-600 text-white shadow-lg shadow-orange-200/50">
                <i class="fas fa-clock-rotate-left text-lg"></i>
            </div>
        </div>

        <div class="group flex items-center justify-between rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200/80">
            <div>
                <p class="text-xs font-medium uppercase tracking-wider text-slate-400">Stok Masuk</p>
                <p class="mt-1 text-3xl font-bold text-slate-800">{{ $logs->where('tipe', 'masuk')->count() }}</p>
            </div>
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-400 to-emerald-600 text-white shadow-lg shadow-emerald-200/50">
                <i class="fas fa-arrow-down text-lg"></i>
            </div>
        </div>

        <div class="group flex items-center justify-between rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200/80">
            <div>
                <p class="text-xs font-medium uppercase tracking-wider text-slate-400">Stok Keluar</p>
                <p class="mt-1 text-3xl font-bold text-slate-800">{{ $logs->where('tipe', 'keluar')->count() }}</p>
            </div>
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-rose-400 to-rose-600 text-white shadow-lg shadow-rose-200/50">
                <i class="fas fa-arrow-up text-lg"></i>
            </div>
        </div>
    </div>

    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200/80">

        <div class="flex flex-col gap-3 border-b border-slate-100 px-6 py-4 md:flex-row md:items-center md:justify-between">

            <div class="flex items-center gap-2">
                <i class="fas fa-clock-rotate-left text-slate-400"></i>

                <span class="text-sm font-semibold text-slate-700">
                    Daftar Riwayat Stok
                </span>

                <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-medium text-slate-500">
                    <i class="fas fa-database"></i>
                    {{ $logs->count() }} Total
                </span>
            </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[1100px] text-left text-sm text-slate-600">
                <thead>
                    <tr class="bg-slate-50/80">
                        <th class="px-6 py-4 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">Tanggal</th>
                        <th class="px-6 py-4 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">Nama Bahan</th>
                        <th class="px-6 py-4 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">Tipe</th>
                        <th class="px-6 py-4 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">Jumlah</th>
                        <th class="px-6 py-4 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">Stok Sebelum</th>
                        <th class="px-6 py-4 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">Stok Sesudah</th>
                        <th class="px-6 py-4 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">Keterangan</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse($logs as $log)
                        <tr class="group transition-all duration-200 hover:bg-slate-50/60">
                            <td class="px-6 py-4 align-middle">
                                <div class="text-sm font-semibold text-slate-700">
                                    {{ $log->created_at->format('d M Y') }}
                                </div>
                                <div class="text-xs text-slate-400">
                                    {{ $log->created_at->format('H:i') }}
                                </div>
                            </td>

                            <td class="px-6 py-4 align-middle">
                                <div class="text-sm font-semibold text-slate-700">
                                    {{ $log->stok->nama_bahan ?? '-' }}
                                </div>
                                <div class="text-xs text-slate-400">
                                    {{ $log->stok->satuan ?? '' }}
                                </div>
                            </td>

                            <td class="px-6 py-4 align-middle">
                                @if($log->tipe == 'masuk')
                                    <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                                        Masuk
                                    </span>
                                @else
                                    <span class="inline-flex rounded-full bg-rose-100 px-2.5 py-1 text-xs font-semibold text-rose-700">
                                        Keluar
                                    </span>
                                @endif
                            </td>

                            <td class="px-6 py-4 align-middle">
                                <span class="font-semibold text-slate-700">
                                    {{ number_format($log->jumlah, 2, ',', '.') }}
                                </span>
                                <span class="text-xs text-slate-400">
                                    {{ $log->stok->satuan ?? '' }}
                                </span>
                            </td>

                            <td class="px-6 py-4 align-middle">
                                {{ number_format($log->stok_sebelum, 2, ',', '.') }}
                            </td>

                            <td class="px-6 py-4 align-middle">
                                {{ number_format($log->stok_sesudah, 2, ',', '.') }}
                            </td>

                            <td class="px-6 py-4 align-middle">
                                <p class="max-w-[300px] truncate text-sm text-slate-500">
                                    {{ $log->keterangan ?? '-' }}
                                </p>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center">
                                <div class="mx-auto max-w-sm text-slate-500">
                                    <i class="fas fa-clock-rotate-left mb-3 text-2xl text-slate-300"></i>
                                    <p class="mb-1 font-semibold text-slate-600">Riwayat stok belum tersedia</p>
                                    <p class="mb-0 text-sm">
                                        Riwayat akan muncul ketika stok ditambahkan, diubah, atau berkurang karena pesanan.
                                    </p>
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