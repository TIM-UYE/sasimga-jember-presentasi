@extends('admin.layout.main')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-800 md:text-3xl">Laporan Reservasi</h1>
            <p class="mt-1 text-sm text-slate-500">Data seluruh reservasi meja. Maks. 200 baris.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.laporan.reservasi.csv') }}"
                class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50">
                <i class="fas fa-file-csv text-green-600"></i> Download CSV
            </a>
            <a href="{{ route('admin.laporan.reservasi.xlsx') }}"
                class="inline-flex items-center gap-1.5 rounded-xl bg-emerald-500 px-4 py-2 text-xs font-semibold text-white hover:bg-emerald-600">
                <i class="fas fa-file-excel"></i> Download XLSX
            </a>
        </div>
    </div>

    <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200/80">
        <form method="GET" action="{{ route('admin.laporan.reservasi') }}" class="flex flex-wrap items-end gap-4">
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
                <a href="{{ route('admin.laporan.reservasi') }}"
                    class="rounded-xl border border-slate-200 px-5 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-50">
                    <i class="fas fa-times mr-1"></i> Reset
                </a>
            @endif
        </form>
    </div>

    <div class="overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-slate-200/80">
        <div class="flex items-center gap-2 border-b border-slate-100 px-6 py-4">
            <i class="fas fa-calendar-alt text-orange-500"></i>
            <span class="font-semibold text-slate-700">Data Reservasi</span>
            <span class="rounded-full bg-slate-100 px-2.5 py-0.5 text-xs text-slate-500">{{ $reservasis->count() }} baris</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full min-w-max text-left text-sm text-slate-600">
                <thead>
                    <tr class="bg-slate-50/80 text-xs uppercase tracking-wider text-slate-500">
                        <th class="px-5 py-3">Nama</th>
                        <th class="px-5 py-3">WhatsApp</th>
                        <th class="px-5 py-3">Tanggal Reservasi</th>
                        <th class="px-5 py-3">Waktu</th>
                        <th class="px-5 py-3">Jumlah Orang</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3">Dibuat</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($reservasis as $reservasi)
                        @php
                            $rColor = match($reservasi->status) {
                                'confirmed' => 'bg-emerald-100 text-emerald-700',
                                'completed' => 'bg-blue-100 text-blue-700',
                                'cancelled' => 'bg-rose-100 text-rose-700',
                                default     => 'bg-orange-100 text-orange-700',
                            };
                            $rLabel = match($reservasi->status) {
                                'confirmed' => 'Dikonfirmasi',
                                'completed' => 'Selesai',
                                'cancelled' => 'Dibatalkan',
                                default     => 'Pending',
                            };
                        @endphp
                        <tr class="hover:bg-slate-50/60">
                            <td class="px-5 py-3 font-medium text-slate-800">{{ $reservasi->nama }}</td>
                            <td class="px-5 py-3 text-slate-500">{{ $reservasi->nomor_wa }}</td>
                            <td class="px-5 py-3">{{ \Carbon\Carbon::parse($reservasi->tanggal_reservasi)->format('d M Y') }}</td>
                            <td class="px-5 py-3">{{ $reservasi->waktu_reservasi }}</td>
                            <td class="px-5 py-3 text-center">{{ $reservasi->jumlah_orang }} orang</td>
                            <td class="px-5 py-3">
                                <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $rColor }}">
                                    {{ $rLabel }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-slate-400">{{ $reservasi->created_at->format('d M Y, H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center text-slate-400">
                                <i class="fas fa-inbox mb-2 text-2xl block"></i>
                                Tidak ada data reservasi
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection