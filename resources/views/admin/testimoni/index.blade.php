@extends('admin.layout.main')

@section('content')
@if(session('success'))
    <div class="mb-4 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
        <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
    </div>
@endif

<div class="space-y-6">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-800 md:text-3xl">Kelola Testimoni</h1>
            <p class="mt-1 text-sm text-slate-500">Kelola ulasan pelanggan dari Google Maps maupun input manual.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <form action="{{ route('admin.testimoni.sync') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="btn-admin">
                    <i class="fas fa-sync-alt mr-1"></i> Sync Google Maps
                </button>
            </form>
            <a href="{{ route('admin.testimoni.create') }}" class="btn-admin">
                <i class="fas fa-plus mr-1"></i> Tambah Testimoni
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">

    <!-- Total Testimoni -->
    <div class="group flex items-center justify-between rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200/80 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-blue-500/10 hover:ring-blue-200">
        <div>
            <p class="text-xs font-medium uppercase tracking-wider text-slate-400">
                Total Testimoni
            </p>
            <p class="mt-1 text-3xl font-bold text-slate-800">
                {{ $testimonis->count() }}
            </p>
        </div>

        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-400 to-blue-600 text-white shadow-lg shadow-blue-200/50">
            <i class="fas fa-comments text-lg"></i>
        </div>
    </div>

    <!-- Testimoni Aktif -->
    <div class="group flex items-center justify-between rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200/80 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-emerald-500/10 hover:ring-emerald-200">
        <div>
            <p class="text-xs font-medium uppercase tracking-wider text-slate-400">
                Testimoni Aktif
            </p>
            <p class="mt-1 text-3xl font-bold text-slate-800">
                {{ $testimonis->where('is_active', true)->count() }}
            </p>
        </div>

        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-400 to-emerald-600 text-white shadow-lg shadow-emerald-200/50">
            <i class="fas fa-check-circle text-lg"></i>
        </div>
    </div>

    <!-- Sumber Google Maps -->
    <div class="group flex items-center justify-between rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200/80 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-red-500/10 hover:ring-red-200">
        <div>
            <p class="text-xs font-medium uppercase tracking-wider text-slate-400">
                Sumber Google Maps
            </p>
            <p class="mt-1 text-3xl font-bold text-slate-800">
                {{ $testimonis->where('source', 'google_maps')->count() }}
            </p>
        </div>

        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-red-400 to-red-600 text-white shadow-lg shadow-red-200/50">
            <i class="fab fa-google text-lg"></i>
        </div>
    </div>

</div>

    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200/80">
        <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
            <div class="flex items-center gap-2">
                <i class="fas fa-comments text-slate-400"></i>
                <span class="text-sm font-semibold text-slate-700">Daftar Testimoni</span>
                <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-medium text-slate-500"><i class="fas fa-database"></i>{{ $testimonis->count() }} Data</span>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[940px] text-left text-sm text-slate-600">
                <thead>
                    <tr class="bg-slate-50/80">
                            <th class="px-6 py-4 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">Reviewer</th>
                            <th class="px-6 py-4 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">Rating</th>
                            <th class="px-6 py-4 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">Sumber</th>
                            <th class="px-6 py-4 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">Tanggal</th>
                            <th class="px-6 py-4 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">Status</th>
                            <th class="px-6 py-4 text-right text-[11px] font-semibold uppercase tracking-wider text-slate-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($testimonis as $testimoni)
                    <tr class="group transition-all duration-200 hover:bg-slate-50/60">
                        <td class="px-6 py-4 align-middle">
                            <div class="flex items-center gap-3">
                                @if($testimoni->profile_photo_url)
                                    <img src="{{ $testimoni->profile_photo_url }}" alt="{{ $testimoni->author_name }}" class="h-10 w-10 rounded-full object-cover ring-1 ring-slate-200">
                                @else
                                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-100 text-slate-500">
                                        <i class="fas fa-user"></i>
                                    </div>
                                @endif
                                <div>
                                    <h6 class="mb-0 text-sm font-semibold leading-normal text-slate-700">{{ $testimoni->author_name }}</h6>
                                    <p class="mb-0 text-xs text-slate-500">{{ Str::limit($testimoni->text, 70) }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 align-middle">
                            <span class="text-yellow-500 text-sm">{{ str_repeat('★', max(1, min(5, $testimoni->rating))) }}</span>
                            <span class="text-slate-500 text-xs">({{ $testimoni->rating }})</span>
                        </td>
                        <td class="px-6 py-4 align-middle">
                            <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $testimoni->source === 'google_maps' ? 'bg-sky-100 text-sky-700' : 'bg-slate-100 text-slate-600' }}">
                                {{ $testimoni->source === 'google_maps' ? 'Google Maps' : 'Manual' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 align-middle">
                            {{ $testimoni->review_date ? $testimoni->review_date->format('d M Y') : '-' }}
                        </td>
                        <td class="px-6 py-4 align-middle">
                            <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $testimoni->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                {{ $testimoni->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 align-middle">
                            <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.testimoni.edit', $testimoni->id) }}" class="btn-admin-secondary">Edit</a>
                            <form action="{{ route('admin.testimoni.destroy', $testimoni->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus testimoni ini?')">
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
                                    <i class="fas fa-comment-dots mb-3 text-2xl text-slate-300"></i>
                                    <p class="mb-1 font-semibold text-slate-600">Belum ada testimoni</p>
                                    <p class="mb-0 text-sm">Tambahkan testimoni manual atau sinkronkan dari Google Maps.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4">
            {{ $testimonis->links() }}
        </div>
    </div>
</div>
@endsection
