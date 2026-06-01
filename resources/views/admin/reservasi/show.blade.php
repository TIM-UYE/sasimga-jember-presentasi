@extends('admin.layout.main')

@section('title', 'Detail Reservasi')

@section('content')
<div class="p-4 sm:p-6 lg:p-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-white">Detail Reservasi</h1>
            <p class="text-zinc-400 text-sm mt-1">Informasi lengkap reservasi</p>
        </div>
        <a href="{{ route('admin.reservasi.index') }}"
            class="px-4 py-2 bg-zinc-800 hover:bg-zinc-700 text-zinc-400 hover:text-white rounded-lg text-sm transition-all">
            ← Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Info --}}
        <div class="lg:col-span-2 space-y-4">
            {{-- Reservation Info --}}
            <div class="bg-zinc-900 rounded-2xl border border-zinc-800 p-6">
                <h2 class="text-white font-semibold mb-4">Informasi Reservasi</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs text-zinc-500">Nama</label>
                        <p class="text-white text-sm">{{ $reservasi->nama }}</p>
                    </div>
                    <div>
                        <label class="text-xs text-zinc-500">Nomor WhatsApp</label>
                        <p class="text-white text-sm">{{ $reservasi->nomor_wa }}</p>
                    </div>
                    <div>
                        <label class="text-xs text-zinc-500">Tanggal Reservasi</label>
                        <p class="text-white text-sm">{{ \Carbon\Carbon::parse($reservasi->tanggal_reservasi)->format('d M Y') }}</p>
                    </div>
                    <div>
                        <label class="text-xs text-zinc-500">Waktu Reservasi</label>
                        <p class="text-white text-sm">{{ $reservasi->waktu_reservasi }}</p>
                    </div>
                    <div>
                        <label class="text-xs text-zinc-500">Jumlah Orang</label>
                        <p class="text-white text-sm">{{ $reservasi->jumlah_orang }} orang</p>
                    </div>
                    <div>
                        <label class="text-xs text-zinc-500">Jumlah Meja</label>
                        <p class="text-white text-sm">{{ $reservasi->jumlah_meja }} meja</p>
                    </div>
                    <div>
                        <label class="text-xs text-zinc-500">Lantai</label>
                        <p class="text-white text-sm">{{ $reservasi->lantai ? $reservasi->lantai->nama : '-' }}</p>
                    </div>
                    <div>
                        <label class="text-xs text-zinc-500">Status</label>
                        <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $reservasi->status === 'confirmed' ? 'bg-emerald-500/20 text-emerald-400' : '' }}
                            {{ $reservasi->status === 'pending' ? 'bg-yellow-500/20 text-yellow-400' : '' }}
                            {{ $reservasi->status === 'cancelled' ? 'bg-red-500/20 text-red-400' : '' }}
                            {{ $reservasi->status === 'completed' ? 'bg-blue-500/20 text-blue-400' : '' }}">
                            {{ $reservasi->status_label }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Selected Tables --}}
            <div class="bg-zinc-900 rounded-2xl border border-zinc-800 p-6">
                <h2 class="text-white font-semibold mb-4">Meja yang Dipilih</h2>

                @if($selectedTables->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                        @foreach($selectedTables as $meja)
                            <div class="p-4 bg-zinc-800/50 rounded-xl border border-zinc-700/50">
                                <div class="flex items-start gap-3">
                                    @if($meja->table_image)
                                        <img src="{{ $meja->table_image_url }}" alt="{{ $meja->nama_meja }}"
                                            class="w-16 h-16 rounded-lg object-cover">
                                    @else
                                        <div class="w-16 h-16 bg-zinc-700 rounded-lg flex items-center justify-center">
                                            <span class="text-xl text-zinc-500">🪑</span>
                                        </div>
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <p class="text-white font-medium text-sm">{{ $meja->nama_meja }}</p>
                                        <p class="text-xs text-zinc-500">Kapasitas: {{ $meja->kapasitas }} orang</p>
                                        @if($meja->merge_group)
                                            <p class="text-xs text-orange-400">Grup {{ $meja->merge_group }}</p>
                                        @endif
                                        <p class="text-xs text-zinc-500">Posisi: ({{ $meja->pos_x }}, {{ $meja->pos_y }})</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4 p-4 bg-zinc-800/30 rounded-xl border border-zinc-700/50">
                        <p class="text-sm text-zinc-400">
                            Total Kapasitas: <span class="text-white font-medium">{{ $totalCapacity }} orang</span>
                            @if($selectedTables->count() > 1)
                                | Meja digabung: <span class="text-orange-400">Ya</span>
                                | Grup: <span class="text-orange-400">{{ $selectedTables->first()->merge_group ?? 'Berbeda' }}</span>
                            @else
                                | Meja digabung: <span class="text-zinc-500">Tidak</span>
                            @endif
                        </p>
                    </div>
                @else
                    <p class="text-zinc-500 text-sm">Tidak ada data meja.</p>
                @endif
            </div>

            {{-- Floor Preview --}}
            @if($reservasi->lantai && $reservasi->lantai->preview_image)
                <div class="bg-zinc-900 rounded-2xl border border-zinc-800 p-6">
                    <h2 class="text-white font-semibold mb-4">Preview Lantai</h2>
                    <img src="{{ asset('storage/' . $reservasi->lantai->preview_image) }}"
                        alt="Preview {{ $reservasi->lantai->nama }}"
                        class="w-full rounded-xl border border-zinc-700/50">
                </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="space-y-4">
            {{-- Actions --}}
            <div class="bg-zinc-900 rounded-2xl border border-zinc-800 p-6">
                <h2 class="text-white font-semibold mb-4">Aksi</h2>
                <div class="space-y-2">
                    <form action="{{ route('admin.reservasi.updateStatus', $reservasi->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="confirmed">
                        <button type="submit"
                            class="w-full py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg text-sm font-medium transition-all">
                            ✓ Konfirmasi
                        </button>
                    </form>

                    <form action="{{ route('admin.reservasi.updateStatus', $reservasi->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="completed">
                        <button type="submit"
                            class="w-full py-2.5 bg-blue-500 hover:bg-blue-600 text-white rounded-lg text-sm font-medium transition-all">
                            ✓ Selesai
                        </button>
                    </form>

                    <form action="{{ route('admin.reservasi.updateStatus', $reservasi->id) }}" method="POST"
                        onsubmit="return confirm('Yakin ingin membatalkan reservasi ini?')">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="cancelled">
                        <button type="submit"
                            class="w-full py-2.5 bg-red-500/20 hover:bg-red-500/30 text-red-400 rounded-lg text-sm font-medium transition-all">
                            ✕ Batalkan
                        </button>
                    </form>

                    <form action="{{ route('admin.reservasi.destroy', $reservasi->id) }}" method="POST"
                        onsubmit="return confirm('Yakin ingin menghapus reservasi ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="w-full py-2.5 bg-zinc-800 hover:bg-red-500/20 text-zinc-400 hover:text-red-400 rounded-lg text-sm transition-all">
                            🗑️ Hapus
                        </button>
                    </form>
                </div>
            </div>

            {{-- Timeline --}}
            <div class="bg-zinc-900 rounded-2xl border border-zinc-800 p-6">
                <h2 class="text-white font-semibold mb-4">Timeline</h2>
                <div class="space-y-3">
                    <div class="flex items-center gap-3">
                        <div class="w-2 h-2 rounded-full bg-orange-500"></div>
                        <div>
                            <p class="text-xs text-zinc-400">Dibuat</p>
                            <p class="text-sm text-white">{{ $reservasi->created_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                    @if($reservasi->updated_at)
                        <div class="flex items-center gap-3">
                            <div class="w-2 h-2 rounded-full bg-zinc-500"></div>
                            <div>
                                <p class="text-xs text-zinc-400">Terakhir diperbarui</p>
                                <p class="text-sm text-white">{{ $reservasi->updated_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
