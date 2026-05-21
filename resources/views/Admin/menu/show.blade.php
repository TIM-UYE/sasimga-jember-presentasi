@extends('admin.layout.main')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-slate-800 md:text-3xl">Detail Menu</h1>
                    <p class="mb-0 text-sm text-slate-500">Informasi lengkap menu untuk pengecekan cepat.</p>
                </div>
                <a href="{{ route('admin.menu.index') }}" class="btn-admin-secondary">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
    </div>

    @php $stockCalc = $menu->getStockCalculationDetails(); @endphp

    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/80">
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <!-- Gambar Menu -->
                    <div>
                        @if($menu->gambar)
                            <img src="{{ asset('storage/menu/' . $menu->gambar) }}" alt="{{ $menu->nama_menu }}"
                                class="h-64 w-full rounded-xl object-cover shadow-lg">
                        @else
                            <div class="flex h-64 w-full items-center justify-center rounded-xl bg-slate-100">
                                <i class="fas fa-utensils text-6xl text-slate-400"></i>
                            </div>
                        @endif
                    </div>

                    <!-- Detail Menu -->
                    <div>
                        <h2 class="mb-2 text-2xl font-bold text-slate-800">{{ $menu->nama_menu }}</h2>

                        <div class="mb-4 flex items-center gap-2">
                            <span class="rounded-full bg-purple-100 px-3 py-1 text-sm font-semibold text-purple-700">
                                {{ $menu->kategori->nama_kategori ?? 'Tidak ada kategori' }}
                            </span>
                            <span class="rounded-full px-3 py-1 text-sm font-semibold {{ $menu->is_available ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                {{ $menu->is_available ? 'Tersedia' : 'Tidak Tersedia' }}
                            </span>
                        </div>

                        <div class="space-y-3 rounded-xl border border-slate-100 bg-slate-50 p-4">
                            <div class="flex justify-between">
                                <span class="text-slate-500">Harga:</span>
                                <span class="text-lg font-semibold text-slate-700">Rp {{ number_format($menu->harga, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Stok Otomatis:</span>
                                <span class="font-semibold {{ $stockCalc['stock'] > 0 ? 'text-emerald-600' : 'text-red-600' }}">
                                    {{ $stockCalc['stock'] }} porsi
                                    @if($stockCalc['stock'] > 0)
                                        <i class="fas fa-check-circle text-emerald-500 ml-1"></i>
                                    @else
                                        <i class="fas fa-times-circle text-red-500 ml-1"></i>
                                    @endif
                                </span>
                            </div>
                            @if($menu->ukuran)
                            <div class="flex justify-between">
                                <span class="text-slate-500">Ukuran:</span>
                                <span class="font-semibold text-slate-700">{{ $menu->ukuran }}</span>
                            </div>
                            @endif
                            @if($menu->durasi_persiapan)
                            <div class="flex justify-between">
                                <span class="text-slate-500">Durasi Persiapan:</span>
                                <span class="font-semibold text-slate-700">{{ $menu->durasi_persiapan }} menit</span>
                            </div>
                            @endif
                        </div>

                        @if($menu->deskripsi)
                        <div class="mt-4">
                            <h4 class="mb-2 font-semibold text-slate-700">Deskripsi</h4>
                            <p class="text-sm text-slate-600">{{ $menu->deskripsi }}</p>
                        </div>
                        @endif

                        @if($menu->bahan)
                        <div class="mt-4">
                            <h4 class="mb-2 font-semibold text-slate-700">Bahan Utama</h4>
                            <p class="text-sm text-slate-600">{{ $menu->bahan }}</p>
                        </div>
                        @endif

                        <div class="mt-6 flex gap-2 border-t border-slate-100 pt-4">
                            <a href="{{ route('admin.menu.edit', $menu) }}" class="btn-admin">
                                <i class="fas fa-edit mr-1"></i> Edit
                            </a>
                            <form action="{{ route('admin.menu.destroy', $menu) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus menu ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn-admin-danger">
                                    <i class="fas fa-trash mr-1"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
    </div>

    <!-- Stock Calculation Details -->
    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/80">
        <div class="mb-4 flex items-center gap-2 border-b border-slate-100 pb-4">
            <i class="fas fa-calculator text-orange-500"></i>
            <h3 class="text-lg font-semibold text-slate-800">Detail Perhitungan Stok Otomatis</h3>
        </div>

        @if($stockCalc['details']->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead>
                        <tr class="bg-slate-50/80">
                            <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-slate-500">Bahan Baku</th>
                            <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-slate-500">Stok Tersedia</th>
                            <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-slate-500">Kebutuhan / Porsi</th>
                            <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-slate-500">Cukup Untuk</th>
                            <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-slate-500">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($stockCalc['details'] as $detail)
                            <tr>
                                <td class="px-4 py-3 font-medium text-slate-700">{{ $detail['bahan'] }}</td>
                                <td class="px-4 py-3">{{ $detail['stok_formatted'] ?? number_format($detail['stok_tersedia'], 0, ',', '.') . ' ' . $detail['satuan_stok'] }}</td>
                                <td class="px-4 py-3">{{ $detail['kebutuhan_formatted'] ?? number_format($detail['kebutuhan_per_poris'], 2, ',', '.') . ' ' . $detail['satuan_kebutuhan'] }}</td>
                                <td class="px-4 py-3">
                                    <span class="font-semibold {{ $detail['cukup_untuk'] <= 0 ? 'text-red-600' : ($detail['cukup_untuk'] < 5 ? 'text-orange-600' : 'text-emerald-600') }}">
                                        {{ $detail['cukup_untuk'] }} porsi
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-semibold
                                        {{ $detail['status'] === 'Habis' ? 'bg-red-100 text-red-700' : ($detail['status'] === 'Menipis' ? 'bg-orange-100 text-orange-700' : 'bg-emerald-100 text-emerald-700') }}">
                                        {{ $detail['status'] }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-slate-50 font-semibold">
                            <td colspan="3" class="px-4 py-3 text-right text-slate-700">Stok Menu Otomatis (bahan paling terbatas):</td>
                            <td class="px-4 py-3">
                                <span class="text-lg {{ $stockCalc['stock'] > 0 ? 'text-emerald-600' : 'text-red-600' }}">
                                    {{ $stockCalc['stock'] }} porsi
                                </span>
                            </td>
                            <td class="px-4 py-3"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @else
            <div class="rounded-xl border border-orange-100 bg-orange-50 p-4 text-center text-sm text-orange-700">
                <i class="fas fa-info-circle mr-1"></i>
                Menu ini belum memiliki komposisi bahan baku.
                <a href="{{ route('admin.menu.edit', $menu->id) }}" class="font-semibold underline">Atur komposisi bahan</a>
                untuk mengaktifkan perhitungan stok otomatis.
            </div>
        @endif
    </div>
</div>
@endsection
