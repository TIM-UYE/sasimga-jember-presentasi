@extends('admin.layout.main')

@section('content')
@if($errors->any())
    <div class="mb-4 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
        <p class="mb-2 font-semibold"><i class="fas fa-exclamation-circle mr-2"></i>Periksa kembali data video:</p>
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
            <h1 class="text-2xl font-bold tracking-tight text-slate-800 md:text-3xl">Edit Video</h1>
            <p class="mb-0 text-sm text-slate-500">Perbarui file video atau informasi video.</p>
        </div>
        <a href="{{ route('admin.video.index') }}" class="btn-admin-secondary">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>
    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/80">
        <form action="{{ route('admin.video.update', $video) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <!-- Judul -->
                <div class="mb-4">
                    <label class="mb-2 block text-sm font-semibold text-slate-700" for="title">
                        Judul <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" id="title" value="{{ old('title', $video->title) }}"
                        class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200"
                        placeholder="Contoh: Suasana Restoran" required>
                </div>

                <!-- Status Aktif -->
                <div class="mb-4 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 self-end">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $video->is_active) == 1 ? 'checked' : '' }}
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
                    placeholder="Deskripsi singkat video...">{{ old('description', $video->description) }}</textarea>
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <!-- Video File -->
                <div class="mb-4">
                    <label class="mb-2 block text-sm font-semibold text-slate-700" for="video_file">
                        File Video
                    </label>
                    @if($video->video_file)
                    <div class="mb-3">
                        <div class="flex items-center gap-3 rounded-xl bg-slate-50 border border-slate-200 px-4 py-3">
                            <i class="fas fa-video text-orange-500 text-xl"></i>
                            <div>
                                <p class="text-sm font-semibold text-slate-700">Video saat ini</p>
                                <p class="text-xs text-slate-500">{{ basename($video->video_file) }}</p>
                            </div>
                            <video class="h-16 w-24 rounded-lg object-cover ring-1 ring-slate-200" controls>
                                <source src="{{ asset('storage/' . $video->video_file) }}" type="video/mp4">
                            </video>
                        </div>
                    </div>
                    @elseif($video->video_url && $video->video_url !== '-')
                    <div class="mb-3">
                        <div class="flex items-center gap-3 rounded-xl bg-slate-50 border border-slate-200 px-4 py-3">
                            <i class="fas fa-link text-blue-500 text-xl"></i>
                            <div>
                                <p class="text-sm font-semibold text-slate-700">URL Video</p>
                                <p class="text-xs text-slate-500 truncate max-w-[250px]">{{ $video->video_url }}</p>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="rounded-xl border-2 border-dashed border-slate-300 p-5 text-center transition hover:border-orange-400">
                        <input type="file" name="video_file" id="video_file" accept="video/mp4,video/mov,video/avi,video/wmv,video/flv,video/mkv,video/webm" class="hidden" onchange="previewVideoName(event)">
                        <label for="video_file" class="cursor-pointer">
                            <i class="fas fa-cloud-upload-alt mb-2 text-3xl text-slate-400"></i>
                            <p class="text-sm font-semibold text-slate-600">Klik untuk upload file video baru</p>
                            <p class="text-xs text-slate-400">Format: mp4, mov, avi, wmv, flv, mkv, webm (maks 100MB)</p>
                        </label>
                    </div>
                    <div id="video-file-name" class="mt-2 hidden">
                        <p class="text-sm text-emerald-600 font-semibold"><i class="fas fa-check-circle mr-1"></i>File terpilih: <span id="video-name-display"></span></p>
                    </div>
                </div>

                <!-- Thumbnail -->
                <div class="mb-4">
                    <label class="mb-2 block text-sm font-semibold text-slate-700" for="thumbnail">
                        Thumbnail
                    </label>
                    @if($video->thumbnail)
                        <div class="mb-3">
                            <img src="{{ asset('storage/' . $video->thumbnail) }}" alt="{{ $video->title }}" class="h-24 w-40 rounded-xl object-cover ring-1 ring-slate-200">
                        </div>
                    @endif
                    <div class="rounded-xl border-2 border-dashed border-slate-300 p-5 text-center transition hover:border-purple-400">
                        <input type="file" name="thumbnail" id="thumbnail" accept="image/*" class="hidden" onchange="previewImage(event)">
                        <label for="thumbnail" class="cursor-pointer">
                            <i class="fas fa-cloud-upload-alt mb-2 text-3xl text-slate-400"></i>
                            <p class="text-sm font-semibold text-slate-600">Klik untuk upload thumbnail baru</p>
                            <p class="text-xs text-slate-400">Format: jpeg, png, jpg, gif, webp (maks 4MB)</p>
                        </label>
                    </div>
                    <div id="image-preview" class="mt-2 hidden">
                        <img id="preview-img" src="" alt="Preview" class="h-32 w-48 rounded-xl object-cover ring-1 ring-slate-200">
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-2 border-t border-slate-100 pt-5">
                <a href="{{ route('admin.video.index') }}" class="btn-admin-secondary">Batal</a>
                <button type="submit" class="btn-admin">Update Video</button>
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

function previewVideoName(event) {
    const file = event.target.files[0];
    if (file) {
        document.getElementById('video-name-display').textContent = file.name;
        document.getElementById('video-file-name').classList.remove('hidden');
    }
}
</script>
@endsection
