@extends('admin.layout.main')

@section('content')
@if(session('success'))
    <div class="mb-4 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
    </div>
@endif

<div class="space-y-6">
    <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-800 md:text-3xl">Kelola Menu Specials</h1>
            <p class="mt-1 text-sm text-slate-500">Buat, edit, dan aktifkan special menu premium untuk tampilan frontend.</p>
        </div>
        <a href="{{ route('admin.menu-specials.create') }}" class="btn-admin"><i class="fas fa-plus mr-1"></i>Tambah Menu Special</a>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
        <div class="group rounded-3xl bg-white p-5 shadow-sm ring-1 ring-slate-200/80 transition hover:-translate-y-1 hover:shadow-lg hover:shadow-orange-500/10">
            <p class="text-xs font-medium uppercase tracking-wider text-slate-400">Total Specials</p>
            <p class="mt-3 text-3xl font-bold text-slate-800">{{ $specials->count() }}</p>
        </div>
        <div class="group rounded-3xl bg-white p-5 shadow-sm ring-1 ring-slate-200/80 transition hover:-translate-y-1 hover:shadow-lg hover:shadow-emerald-500/10">
            <p class="text-xs font-medium uppercase tracking-wider text-slate-400">Aktif</p>
            <p class="mt-3 text-3xl font-bold text-slate-800">{{ $specials->where('is_active', true)->count() }}</p>
        </div>
        <div class="group rounded-3xl bg-white p-5 shadow-sm ring-1 ring-slate-200/80 transition hover:-translate-y-1 hover:shadow-lg hover:shadow-indigo-500/10">
            <p class="text-xs font-medium uppercase tracking-wider text-slate-400">Total Varian</p>
            <p class="mt-3 text-3xl font-bold text-slate-800">{{ $specials->sum('items_count') }}</p>
        </div>
    </div>

    <div class="overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-slate-200/80">
        <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
            <div class="flex items-center gap-2 text-slate-700">
                <i class="fas fa-star text-orange-500"></i>
                <span class="font-semibold">Daftar Menu Specials</span>
            </div>
            <span class="rounded-full bg-slate-100 px-3 py-1 text-sm text-slate-500">{{ $specials->count() }} items</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[920px] text-left text-sm text-slate-600">
                <thead>
                    <tr class="bg-slate-50/80 text-[11px] uppercase tracking-wider text-slate-500">
                        <th class="px-6 py-4">Banner</th>
                        <th class="px-6 py-4">Judul</th>
                        <th class="px-6 py-4">Deskripsi</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Varian</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($specials as $special)
                        <tr class="hover:bg-slate-50/80">
                            <td class="px-6 py-4 align-middle">
                                @if($special->banner_image)
                                    <img src="{{ asset('storage/' . $special->banner_image) }}" alt="{{ $special->title }}" class="h-16 w-24 rounded-2xl object-cover ring-1 ring-slate-200">
                                @else
                                    <div class="flex h-16 w-24 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                                        <i class="fas fa-image text-lg"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 align-middle">
                                <p class="font-semibold text-slate-800">{{ $special->title }}</p>
                                <p class="text-xs text-slate-500">{{ $special->slug }}</p>
                            </td>
                            <td class="px-6 py-4 align-middle text-slate-600">{{ Str::limit($special->short_description, 80) }}</td>
                            <td class="px-6 py-4 align-middle">
                                <span class="inline-flex rounded-full px-3 py-1 text-[11px] font-semibold {{ $special->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                    {{ $special->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 align-middle">
                                <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">{{ $special->items_count }}</span>
                            </td>
                            <td class="px-6 py-4 align-middle text-right">
                                <div class="flex flex-wrap justify-end gap-2">
                                    <a href="{{ route('admin.menu-specials.edit', $special) }}" class="btn-admin-secondary">Edit</a>
                                    <form action="{{ route('admin.menu-specials.destroy', $special) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus special ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn-admin-danger">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center text-slate-500">
                                <div class="mx-auto max-w-md">
                                    <i class="fas fa-star mb-3 text-3xl text-orange-300"></i>
                                    <p class="font-semibold text-slate-700">Belum ada menu specials</p>
                                    <p class="text-sm">Tambahkan special menu pertama Anda untuk menampilkan paket premium di frontend.</p>
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
