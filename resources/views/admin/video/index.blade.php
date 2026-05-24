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
            <h1 class="text-2xl font-bold tracking-tight text-slate-800 md:text-3xl">Kelola Video</h1>
            <p class="mt-1 text-sm text-slate-500">Kelola video galeri restoran untuk ditampilkan di halaman utama.</p>
        </div>
        <a href="{{ route('admin.video.create') }}" class="btn-admin"><i class="fas fa-plus mr-1"></i>Tambah Video</a>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
        <div class="group flex items-center justify-between rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200/80 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-orange-500/10 hover:ring-orange-200">
            <div>
                <p class="text-xs font-medium uppercase tracking-wider text-slate-400">Total Video</p>
                <p class="mt-1 text-3xl font-bold text-slate-800">{{ $videos->count() }}</p>
            </div>
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-orange-400 to-orange-600 text-white shadow-lg shadow-orange-200/50">
                <i class="fas fa-video text-lg"></i>
            </div>
        </div>
        <div class="group flex items-center justify-between rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200/80 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-emerald-500/10 hover:ring-emerald-200">
            <div>
                <p class="text-xs font-medium uppercase tracking-wider text-slate-400">Aktif</p>
                <p class="mt-1 text-3xl font-bold text-slate-800">{{ $videos->where('is_active', true)->count() }}</p>
            </div>
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-400 to-emerald-600 text-white shadow-lg shadow-emerald-200/50">
                <i class="fas fa-check-circle text-lg"></i>
            </div>
        </div>
        <div class="group flex items-center justify-between rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200/80 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-amber-500/10 hover:ring-amber-200">
            <div>
                <p class="text-xs font-medium uppercase tracking-wider text-slate-400">Nonaktif</p>
                <p class="mt-1 text-3xl font-bold text-slate-800">{{ $videos->where('is_active', false)->count() }}</p>
            </div>
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-amber-400 to-amber-600 text-white shadow-lg shadow-amber-200/50">
                <i class="fas fa-eye-slash text-lg"></i>
            </div>
        </div>
    </div>

    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200/80">
        <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
            <div class="flex items-center gap-2">
                <i class="fas fa-video text-slate-400"></i>
                <span class="text-sm font-semibold text-slate-700">Daftar Video</span>
                <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-medium text-slate-500"><i class="fas fa-database"></i>{{ $videos->count() }} Total</span>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[980px] text-left text-sm text-slate-600">
                <thead>
                    <tr class="bg-slate-50/80">
                        <th class="px-6 py-4 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">Thumbnail</th>
                        <th class="px-6 py-4 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">Judul</th>
                        <th class="px-6 py-4 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">Preview Video</th>
                        <th class="px-6 py-4 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">Status</th>
                        <th class="px-6 py-4 text-right text-[11px] font-semibold uppercase tracking-wider text-slate-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($videos as $v)
                    <tr class="group transition-all duration-200 hover:bg-slate-50/60">
                        <td class="px-6 py-4 align-middle">
                            @if($v->thumbnail)
                                <img src="{{ asset('storage/' . $v->thumbnail) }}" alt="{{ $v->title }}" class="h-16 w-24 rounded-xl object-cover ring-1 ring-slate-200">
                            @else
                                <div class="flex h-16 w-24 items-center justify-center rounded-xl bg-slate-100">
                                    <i class="fas fa-video text-slate-400"></i>
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 align-middle">
                            <h6 class="mb-0 text-sm font-semibold leading-normal text-slate-700">{{ $v->title }}</h6>
                        </td>
                        <td class="px-6 py-4 align-middle">
                            @if($v->video_file)
                                <div class="flex items-center gap-3">
                                    <video class="h-16 w-28 rounded-xl object-cover ring-1 ring-slate-200" muted autoplay playsinline preload="metadata"
                                        onmouseover="this.play()" onmouseout="this.pause();this.currentTime=0;">
                                        <source src="{{ asset('storage/' . $v->video_file) }}" type="video/mp4">
                                    </video>
                                    <span class="text-xs text-slate-500 truncate max-w-[100px]">{{ basename($v->video_file) }}</span>
                                </div>
                            @elseif(!$v->video_file)
                                <span class="text-sm text-slate-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 align-middle">
                            <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $v->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                {{ $v->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 align-middle">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.video.edit', $v) }}" class="btn-admin-secondary">Edit</a>
                                <form action="{{ route('admin.video.destroy', $v) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus video ini?')">
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
                                <i class="fas fa-video mb-3 text-2xl text-slate-300"></i>
                                <p class="mb-1 font-semibold text-slate-600">Video belum tersedia</p>
                                <p class="mb-0 text-sm">Tambahkan video pertama untuk mulai menampilkan galeri video restoran.</p>
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
