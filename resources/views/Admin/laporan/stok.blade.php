@extends('admin.layout.main')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-800 md:text-3xl">Laporan Stok</h1>
            <p class="mt-1 text-sm text-slate-500">Riwayat keluar masuk bahan baku. Maks. 200 baris.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.laporan.stok.csv') }}"
                class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50">
                <i class="fas fa-file-csv text-green-600"></i> Download CSV
            </a>
            <a href="{{ route('admin.laporan.stok.xlsx') }}"
                class="inline-flex items-center gap-1.5 rounded-xl bg-emerald-500 px-4 py-2 text-xs font-semibold text-white hover:bg-emerald-600">
                <i class="fas fa-file-excel"></i> Download XLSX
            </a>
        </div>
    </div>

    <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200/80">
        <form method="GET" action="{{ route('admin.laporan.stok') }}" class="flex flex-wrap items-end gap-4">
            <div>
                <label class="mb-1 block text-xs font-semibold text-slate-500">Dari Tanggal</label>
                <input type="date" name="dari" value="{{ $dari }}"
                    class="rounded-xl border border-slate-200 px-4 py-2 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200">
            </div>
            <div>
                <label class="mb-1 block text-xs font-semibold text-slate-500">Sampai Tanggal</label>
                <input type="date" name="sampai" value="{{ $sampai }}"
                    class="rounded-xl border border-slate-200 px-4 py-2 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200">
            </div>
            <button type="submit"
                class="rounded-xl bg-orange-500 px-5 py-2 text-sm font-semibold text-white hover:bg-orange-600">
                <i class="fas fa-filter mr-1"></i> Filter
            </button>
            @if($dari || $sampai)
                <a href="{{ route('admin.laporan.stok') }}"
                    class="rounded-xl border border-slate-200 px-5 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50">
                    <i class="fas fa-times mr-1"></i> Reset
                </a>
            @endif
        </form>
    </div>

    <div class="overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-slate-200/80">
        <div class="flex items-center gap-2 border-b border-slate-100 px-6 py-4">
            <i class="fas fa-history text-orange-500"></i>
            <span class="font-semibold text-slate-700">Riwayat Stok</span>
            <span class="rounded-full bg-slate-100 px-2.5 py-0.5 text-xs text-slate-500">{{ $stokLogs->count() }} baris</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full min-w-max text-left text-sm text-slate-600">
                <thead>
                    <tr class="bg-slate-50/80 text-xs uppercase tracking-wider text-slate-500">
                        <th class="px-5 py-3">Bahan</th>
                        <th class="px-5 py-3">Tipe</th>
                        <th class="px-5 py-3">Jumlah</th>
                        <th class="px-5 py-3">Stok Sebelum</th>
                        <th class="px-5 py-3">Stok Sesudah</th>
                        <th class="px-5 py-3">Keterangan</th>
                        <th class="px-5 py-3">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($stokLogs as $log)
                        <tr class="hover:bg-slate-50/60">
                            <td class="px-5 py-3 font-medium text-slate-800">{{ $log->stok->nama_bahan ?? '-' }}</td>
                            <td class="px-5 py-3">
                                <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold
                                    {{ $log->tipe === 'masuk' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                    {{ ucfirst($log->tipe) }}
                                </span>
                            </td>
                            <td class="px-5 py-3">{{ number_format($log->jumlah, 0, ',', '.') }} {{ $log->stok->satuan ?? '' }}</td>
                            <td class="px-5 py-3">{{ number_format($log->stok_sebelum, 0, ',', '.') }}</td>
                            <td class="px-5 py-3">{{ number_format($log->stok_sesudah, 0, ',', '.') }}</td>
                            <td class="px-5 py-3 text-slate-500">{{ $log->keterangan ?? '-' }}</td>
                            <td class="px-5 py-3 text-slate-400">{{ $log->created_at->format('d M Y, H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center text-slate-400">
                                <i class="fas fa-inbox mb-2 text-2xl block"></i>
                                Tidak ada data riwayat stok
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection