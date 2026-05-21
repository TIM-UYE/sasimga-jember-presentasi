@extends('admin.layout.main')

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-800 md:text-3xl">
                Kelola Reservasi
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                Daftar reservasi pelanggan yang masuk
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2">

            {{-- Export Excel --}}
            <a href="{{ route('admin.laporan.reservasi.xlsx') }}"
               class="inline-flex items-center rounded-xl bg-emerald-500 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-emerald-600">
                <i class="fas fa-file-excel mr-2"></i>
                Excel
            </a>

            {{-- Export CSV --}}
            <a href="{{ route('admin.laporan.reservasi.csv') }}"
               class="inline-flex items-center rounded-xl bg-sky-500 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-sky-600">
                <i class="fas fa-file-csv mr-2"></i>
                CSV
            </a>

            {{-- Refresh --}}
            <a href="{{ route('admin.reservasi.index') }}"
               class="inline-flex items-center rounded-xl bg-slate-800 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-slate-700">
                <i class="fas fa-sync-alt mr-2"></i>
                Refresh
            </a>

        </div>
    </div>

    {{-- STATS --}}
    <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">

        {{-- Pending --}}
        <div class="group relative rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200/80 transition-all duration-300 hover:shadow-lg hover:shadow-amber-500/10 hover:ring-amber-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-slate-400">Pending</p>
                    <p class="mt-1 text-3xl font-bold text-slate-800">
                        {{ $reservasis->where('status', 'pending')->count() }}
                    </p>
                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-amber-400 to-amber-600 shadow-lg shadow-amber-200/50 transition-transform duration-300 group-hover:scale-110">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Confirmed --}}
        <div class="group relative rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200/80 transition-all duration-300 hover:shadow-lg hover:shadow-emerald-500/10 hover:ring-emerald-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-slate-400">Dikonfirmasi</p>
                    <p class="mt-1 text-3xl font-bold text-slate-800">
                        {{ $reservasis->where('status', 'confirmed')->count() }}
                    </p>
                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-400 to-emerald-600 shadow-lg shadow-emerald-200/50 transition-transform duration-300 group-hover:scale-110">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Cancelled --}}
        <div class="group relative rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200/80 transition-all duration-300 hover:shadow-lg hover:shadow-red-500/10 hover:ring-red-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-slate-400">Dibatalkan</p>
                    <p class="mt-1 text-3xl font-bold text-slate-800">
                        {{ $reservasis->where('status', 'cancelled')->count() }}
                    </p>
                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-red-400 to-red-600 shadow-lg shadow-red-200/50 transition-transform duration-300 group-hover:scale-110">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Completed --}}
        <div class="group relative rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200/80 transition-all duration-300 hover:shadow-lg hover:shadow-blue-500/10 hover:ring-blue-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-slate-400">Selesai</p>
                    <p class="mt-1 text-3xl font-bold text-slate-800">
                        {{ $reservasis->where('status', 'completed')->count() }}
                    </p>
                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-400 to-blue-600 shadow-lg shadow-blue-200/50 transition-transform duration-300 group-hover:scale-110">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7"/>
                    </svg>
                </div>
            </div>
        </div>

    </div>

    {{-- TABLE --}}
    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200/80">

        <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
            <div class="flex items-center gap-2">
                <i class="fas fa-calendar-check text-slate-400"></i>

                <span class="text-sm font-semibold text-slate-700">
                    Daftar Reservasi
                </span>

                <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-medium text-slate-500">
                    <i class="fas fa-database"></i>
                    {{ $reservasis->count() }} Total
                </span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">

                <thead>
                    <tr class="bg-slate-50/80">
                        <th class="px-6 py-4 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">No</th>
                        <th class="px-6 py-4 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">Pelanggan</th>
                        <th class="px-6 py-4 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">WhatsApp</th>
                        <th class="px-6 py-4 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">Tanggal</th>
                        <th class="px-6 py-4 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">Jam</th>
                        <th class="px-6 py-4 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">Orang</th>
                        <th class="px-6 py-4 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">Status</th>
                        <th class="px-6 py-4 text-right text-[11px] font-semibold uppercase tracking-wider text-slate-500">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">

                    @forelse($reservasis as $index => $reservasi)
                    <tr class="transition-all duration-200 hover:bg-slate-50/60">

                        <td class="px-6 py-4 text-xs font-medium text-slate-400">
                            {{ $index + 1 }}
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-orange-400 to-amber-600 text-sm font-bold text-white">
                                    {{ strtoupper(substr($reservasi->nama, 0, 1)) }}
                                </div>

                                <div>
                                    <p class="font-semibold text-slate-800">
                                        {{ $reservasi->nama }}
                                    </p>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            @if($reservasi->nomor_wa)
                                <a href="https://wa.me/{{ $reservasi->formatted_wa }}"
                                   target="_blank"
                                   class="inline-flex items-center gap-1 rounded-lg bg-emerald-50 px-3 py-1.5 text-xs font-medium text-emerald-700 ring-1 ring-emerald-200/50 transition hover:bg-emerald-100">
                                    <i class="fab fa-whatsapp"></i>
                                    {{ $reservasi->nomor_wa }}
                                </a>
                            @else
                                <span class="text-xs text-slate-300">-</span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-slate-600">
                            {{ \Carbon\Carbon::parse($reservasi->tanggal_reservasi)->isoFormat('D MMM YYYY') }}
                        </td>

                        <td class="px-6 py-4 text-slate-600">
                            {{ \Carbon\Carbon::parse($reservasi->waktu_reservasi)->format('H:i') }}
                        </td>

                        <td class="px-6 py-4">
                            <span class="inline-flex items-center rounded-lg bg-slate-100 px-3 py-1.5 text-xs font-medium text-slate-700">
                                {{ $reservasi->jumlah_orang }} Orang
                            </span>
                        </td>

                        <td class="px-6 py-4">
                            @php
                                $statusConfig = [
                                    'pending' => 'bg-amber-50 text-amber-700 ring-amber-200/50',
                                    'confirmed' => 'bg-emerald-50 text-emerald-700 ring-emerald-200/50',
                                    'cancelled' => 'bg-red-50 text-red-700 ring-red-200/50',
                                    'completed' => 'bg-blue-50 text-blue-700 ring-blue-200/50',
                                ];
                            @endphp

                            <span class="inline-flex items-center rounded-lg px-3 py-1.5 text-xs font-medium ring-1 {{ $statusConfig[$reservasi->status] ?? $statusConfig['pending'] }}">
                                {{ ucfirst($reservasi->status) }}
                            </span>
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-2">

                                {{-- STATUS --}}
                                <form action="{{ route('admin.reservasi.updateStatus', $reservasi->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')

                                    <select name="status"
                                            onchange="this.form.submit()"
                                            class="rounded-lg border border-slate-200 bg-slate-50 px-2.5 py-2 text-xs text-slate-600 transition hover:border-slate-300 focus:border-orange-400 focus:outline-none focus:ring-2 focus:ring-orange-500/20">

                                        <option value="pending" {{ $reservasi->status == 'pending' ? 'selected' : '' }}>
                                            ⏳ Pending
                                        </option>

                                        <option value="confirmed" {{ $reservasi->status == 'confirmed' ? 'selected' : '' }}>
                                            ✅ Konfirmasi
                                        </option>

                                        <option value="cancelled" {{ $reservasi->status == 'cancelled' ? 'selected' : '' }}>
                                            ❌ Batalkan
                                        </option>

                                        <option value="completed" {{ $reservasi->status == 'completed' ? 'selected' : '' }}>
                                            🏁 Selesai
                                        </option>

                                    </select>
                                </form>

                                {{-- DELETE --}}
                                <form action="{{ route('admin.reservasi.destroy', $reservasi->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Yakin ingin menghapus reservasi {{ $reservasi->nama }}?')">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="rounded-lg p-2 text-slate-400 transition hover:bg-red-50 hover:text-red-500">
                                        <i class="fas fa-trash"></i>
                                    </button>

                                </form>

                            </div>
                        </td>

                    </tr>
                    @empty

                    <tr>
                        <td colspan="8" class="px-6 py-16">

                            <div class="flex flex-col items-center justify-center">
                                <div class="mb-4 flex h-20 w-20 items-center justify-center rounded-2xl bg-slate-100">
                                    <i class="fas fa-calendar-times text-3xl text-slate-300"></i>
                                </div>

                                <p class="mb-1 text-lg font-semibold text-slate-700">
                                    Belum ada reservasi
                                </p>

                                <p class="text-sm text-slate-400">
                                    Tidak ada data reservasi yang masuk.
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