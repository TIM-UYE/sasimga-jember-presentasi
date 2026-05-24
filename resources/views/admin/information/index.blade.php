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
            <h1 class="text-2xl font-bold tracking-tight text-slate-800 md:text-3xl">Kelola Informasi</h1>
            <p class="mt-1 text-sm text-slate-500">Atur halaman informasi seperti FAQ, About, Privacy Policy, Terms & Conditions.</p>
        </div>
        <a href="{{ route('admin.information.create') }}" class="btn-admin"><i class="fas fa-plus mr-1"></i>Tambah Halaman</a>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-4">
        @php
            $typeCounts = $information->countBy(function($item) { return $item->slug; });
            $types = \App\Services\InformationService::getTypes();
        @endphp
        @foreach ($types as $key => $type)
        <div class="group flex items-center justify-between rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200/80 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-orange-500/10 hover:ring-orange-200">
            <div>
                <p class="text-xs font-medium uppercase tracking-wider text-slate-400">{{ $type['label'] }}</p>
                <p class="mt-1 text-3xl font-bold text-slate-800">{{ $information->where('slug', $key)->count() }}</p>
            </div>
            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-orange-400 to-orange-600 text-white shadow-lg shadow-orange-200/50">
                <i class="fas {{ $type['icon'] }} text-lg"></i>
            </div>
        </div>
        @endforeach
    </div>

    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200/80">
        <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
            <div class="flex items-center gap-2">
                <i class="fas fa-info-circle text-slate-400"></i>
                <span class="text-sm font-semibold text-slate-700">Daftar Halaman Informasi</span>
                <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-medium text-slate-500"><i class="fas fa-database"></i>{{ $information->count() }} Total</span>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[980px] text-left text-sm text-slate-600">
                <thead>
                    <tr class="bg-slate-50/80">
                        <th class="px-6 py-4 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">Tipe</th>
                        <th class="px-6 py-4 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">Judul</th>
                        <th class="px-6 py-4 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">Dibuat</th>
                        <th class="px-6 py-4 text-right text-[11px] font-semibold uppercase tracking-wider text-slate-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($information as $info)
                    @php
                        $typeLabel = $types[$info->slug]['label'] ?? ucfirst($info->slug);
                        $typeIcon = $types[$info->slug]['icon'] ?? 'fa-file';
                    @endphp
                    <tr class="group transition-all duration-200 hover:bg-slate-50/60">
                        <td class="px-6 py-4 align-middle">
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-orange-50 px-3 py-1 text-xs font-semibold text-orange-600">
                                <i class="fas {{ $typeIcon }}"></i>
                                {{ $typeLabel }}
                            </span>
                        </td>
                        <td class="px-6 py-4 align-middle">
                            <h6 class="mb-0 text-sm font-semibold leading-normal text-slate-700">{{ $info->title }}</h6>
                        </td>
                        <td class="px-6 py-4 align-middle">
                            <p class="mb-0 text-sm text-slate-500">{{ $info->created_at->format('d M Y') }}</p>
                        </td>
                        <td class="px-6 py-4 align-middle">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('frontend.information.show', $info) }}" target="_blank" class="btn-admin-secondary text-xs"><i class="fas fa-external-link-alt mr-1"></i>Lihat</a>
                                <a href="{{ route('admin.information.edit', $info) }}" class="btn-admin-secondary text-xs"><i class="fas fa-edit mr-1"></i>Edit</a>
                                <form action="{{ route('admin.information.destroy', $info) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus halaman {{ $typeLabel }} ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn-admin-danger text-xs"><i class="fas fa-trash mr-1"></i>Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-16 text-center">
                            <div class="mx-auto max-w-sm text-slate-500">
                                <i class="fas fa-info-circle mb-3 text-2xl text-slate-300"></i>
                                <p class="mb-1 font-semibold text-slate-600">Informasi belum tersedia</p>
                                <p class="mb-0 text-sm">Tambahkan halaman informasi baru untuk mulai mengelola konten website.</p>
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
