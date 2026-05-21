@extends('admin.layout.main')

@section('content')
@if($errors->any())
    <div class="mb-4 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
        <p class="mb-2 font-semibold"><i class="fas fa-exclamation-circle mr-2"></i>Periksa kembali input berikut:</p>
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="space-y-6">
    <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-slate-800 md:text-3xl">Tambah Kategori Menu</h1>
                    <p class="mb-0 text-sm text-slate-500">Lengkapi data kategori agar manajemen menu lebih rapi.</p>
                </div>
                <a href="{{ route('admin.kategori.index') }}" class="btn-admin-secondary">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
    </div>
    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/80">
                <form action="{{ route('admin.kategori.store') }}" method="POST">
                    @csrf

                    <div class="mb-5">
                        <label class="mb-2 block text-sm font-semibold text-slate-700" for="nama_kategori">
                            Nama Kategori <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama_kategori" id="nama_kategori" value="{{ old('nama_kategori') }}"
                            class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200"
                            placeholder="Contoh: Sate Kambing, Minuman, dll" required>
                    </div>

                    <div class="mb-5">
                        <label class="mb-2 block text-sm font-semibold text-slate-700" for="deskripsi">
                            Deskripsi
                        </label>
                        <textarea name="deskripsi" id="deskripsi" rows="3"
                            class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200"
                            placeholder="Deskripsi kategori...">{{ old('deskripsi') }}</textarea>
                    </div>

                    <div class="mb-5">
                        <label class="mb-2 block text-sm font-semibold text-slate-700" for="ikon">
                            Ikon (Font Awesome class)
                        </label>
                        <input type="text" name="ikon" id="ikon" value="{{ old('ikon') }}"
                            class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200"
                            placeholder="Contoh: fa-utensils, fa-burger, fa-coffee">
                        <p class="mt-1.5 text-xs text-slate-500">Gunakan class Font Awesome sesuai icon yang diinginkan.</p>
                    </div>

                    <div class="mb-4 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
                        <label class="flex items-center">
                            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                class="h-4 w-4 rounded border-slate-300 text-orange-500 focus:ring-orange-500">
                            <span class="ml-2 text-sm text-slate-700">Kategori aktif dan dapat dipilih saat membuat menu</span>
                        </label>
                    </div>

                    <div class="mt-6 flex justify-end gap-2 border-t border-slate-100 pt-5">
                        <a href="{{ route('admin.kategori.index') }}" class="btn-admin-secondary">Batal</a>
                        <button type="submit" class="btn-admin">Simpan Kategori</button>
                    </div>
                </form>
    </div>
</div>
@endsection
