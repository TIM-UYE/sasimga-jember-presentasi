@extends('admin.layout.main')

@section('content')
@if($errors->any())
    <div class="mb-4 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
        <p class="mb-2 font-semibold"><i class="fas fa-exclamation-circle mr-2"></i>Periksa kembali data informasi:</p>
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
            <h1 class="text-2xl font-bold tracking-tight text-slate-800 md:text-3xl">Tambah Halaman Informasi</h1>
            <p class="mb-0 text-sm text-slate-500">Buat halaman informasi baru dengan form yang mudah diisi.</p>
        </div>
        <a href="{{ route('admin.information.index') }}" class="btn-admin-secondary">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>

    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/80">
        <form action="{{ route('admin.information.store') }}" method="POST" id="informationForm" enctype="multipart/form-data">
            @csrf

            {{-- BASIC INFO --}}
            <div class="mb-6 border-b border-slate-100 pb-6">
                <h3 class="mb-4 text-base font-bold text-slate-700"><i class="fas fa-info-circle text-orange-500 mr-2"></i>Informasi Dasar</h3>
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div class="mb-4">
                        <label class="mb-2 block text-sm font-semibold text-slate-700" for="slug">Tipe Halaman <span class="text-red-500">*</span></label>
                        <select name="slug" id="slug"
                            class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200"
                            required onchange="toggleFormSections()">
                            <option value="">-- Pilih Tipe Halaman --</option>
                            @foreach ($types as $key => $type)
                                <option value="{{ $key }}" {{ old('slug') == $key ? 'selected' : '' }}>
                                    <i class="fas {{ $type['icon'] }}"></i> {{ $type['label'] }}
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-slate-400">Pilih tipe halaman yang ingin dibuat</p>
                    </div>
                    <div class="mb-4">
                        <label class="mb-2 block text-sm font-semibold text-slate-700" for="title">Judul Halaman <span class="text-red-500">*</span></label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}"
                            class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200"
                            placeholder="Contoh: About Us - Sate Simpang Tiga" required>
                    </div>
                </div>
            </div>

            {{-- DYNAMIC FORM SECTIONS --}}
            <div id="formSections">
                <div class="form-type" data-type="about" style="display:none;">
                    @include('admin.information.forms.about', ['icons' => $icons])
                </div>
                <div class="form-type" data-type="faq" style="display:none;">
                    @include('admin.information.forms.faq', ['icons' => $icons, 'routes' => $routes])
                </div>
                <div class="form-type" data-type="privacy-policy" style="display:none;">
                    @include('admin.information.forms.privacy', ['icons' => $icons, 'routes' => $routes])
                </div>
                <div class="form-type" data-type="terms-conditions" style="display:none;">
                    @include('admin.information.forms.terms', ['icons' => $icons, 'routes' => $routes])
                </div>
            </div>

            {{-- SUBMIT --}}
            <div class="mt-6 flex justify-end gap-2 border-t border-slate-100 pt-5">
                <a href="{{ route('admin.information.index') }}" class="btn-admin-secondary">Batal</a>
                <button type="submit" class="btn-admin"><i class="fas fa-save mr-1"></i>Simpan Halaman</button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleFormSections() {
    const slug = document.getElementById('slug').value;
    document.querySelectorAll('.form-type').forEach(el => el.style.display = 'none');
    if (slug) {
        const target = document.querySelector(`.form-type[data-type="${slug}"]`);
        if (target) target.style.display = 'block';
    }
}
document.addEventListener('DOMContentLoaded', function() { toggleFormSections(); });
</script>
@endsection
