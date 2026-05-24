@extends('admin.layout.main')

@section('content')
@if($errors->any())
    <div class="mb-4 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
        <p class="mb-2 font-semibold"><i class="fas fa-exclamation-circle mr-2"></i>Periksa kembali data galeri:</p>
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
            <h1 class="text-2xl font-bold tracking-tight text-slate-800 md:text-3xl">Edit Galeri</h1>
            <p class="mb-0 text-sm text-slate-500">Perbarui informasi galeri agar tetap sesuai.</p>
        </div>
        <a href="{{ route('admin.galeri.index') }}" class="btn-admin-secondary">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>
    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/80">
        <form action="{{ route('admin.galeri.update', $galeri) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <!-- Judul -->
                <div class="mb-4">
                    <label class="mb-2 block text-sm font-semibold text-slate-700" for="title">
                        Judul <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" id="title" value="{{ old('title', $galeri->title) }}"
                        class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200"
                        placeholder="Contoh: Family Dinner" required>
                </div>

                <!-- Status Aktif -->
                <div class="mb-4 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $galeri->is_active) == 1 ? 'checked' : '' }}
                            class="h-4 w-4 rounded border-slate-300 text-orange-500 focus:ring-orange-500">
                        <span class="ml-2 text-sm text-slate-700">Tampilkan di halaman utama</span>
                    </label>
                </div>
            </div>

            <!-- Deskripsi -->
            <div class="mb-4">
                <label class="mb-2 block text-sm font-semibold text-slate-700" for="description">
                    Deskripsi
                </label>
                <textarea name="description" id="description" rows="3"
                    class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200"
                    placeholder="Deskripsi singkat galeri...">{{ old('description', $galeri->description) }}</textarea>
            </div>

            <!-- Gambar -->
            <div class="mb-4">
                <label class="mb-2 block text-sm font-semibold text-slate-700" for="image">
                    Gambar
                </label>
                @if($galeri->image)
                    <div class="mb-3">
                        <img src="{{ asset('storage/' . $galeri->image) }}" alt="{{ $galeri->title }}" class="h-32 w-48 rounded-xl object-cover ring-1 ring-slate-200">
                    </div>
                @endif
                <div class="rounded-xl border-2 border-dashed border-slate-300 p-5 text-center transition hover:border-purple-400">
                    <input type="file" name="image" id="image" accept="image/*" class="hidden" onchange="previewImage(event)">
                    <label for="image" class="cursor-pointer">
                        <i class="fas fa-cloud-upload-alt mb-2 text-3xl text-slate-400"></i>
                        <p class="text-sm font-semibold text-slate-600">Klik untuk upload gambar baru</p>
                        <p class="text-xs text-slate-400">Format: jpeg, png, jpg, gif, webp (maks 4MB)</p>
                    </label>
                </div>
                <div id="image-preview" class="mt-2 hidden">
                    <img id="preview-img" src="" alt="Preview" class="h-48 w-48 rounded-xl object-cover ring-1 ring-slate-200">
                </div>
                @if($galeri->image)
                <p class="mt-2 text-sm text-slate-500">Gambar saat ini: {{ $galeri->image }}</p>
                @endif
            </div>

            <div class="mt-6 flex justify-end gap-2 border-t border-slate-100 pt-5">
                <a href="{{ route('admin.galeri.index') }}" class="btn-admin-secondary">Batal</a>
                <button type="submit" class="btn-admin">Update Galeri</button>
            </div>
        </form>
    </div>
</div>

<script>
function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview-img').src = e.target.result;
            document.getElementById('image-preview').classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }
}
</script>
@endsection
