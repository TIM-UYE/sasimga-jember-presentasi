{{-- ABOUT FORM --}}
@php $d = $data ?? []; @endphp

{{-- HERO SECTION --}}
<div class="mb-6 rounded-xl border border-slate-200 bg-slate-50/50 p-5">
    <h4 class="mb-4 text-sm font-bold text-slate-700"><i class="fas fa-star text-orange-500 mr-2"></i>Hero Section</h4>
    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
        <div class="mb-4">
            <label class="mb-2 block text-sm font-semibold text-slate-700">Badge Label</label>
            <input type="text" name="hero_badge" value="{{ old('hero_badge', $d['hero_badge'] ?? '') }}"
                class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200"
                placeholder="Contoh: About Us">
        </div>
        <div class="mb-4">
            <label class="mb-2 block text-sm font-semibold text-slate-700">Judul Hero</label>
            <input type="text" name="hero_title" value="{{ old('hero_title', $d['hero_title'] ?? '') }}"
                class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200"
                placeholder="Contoh: Tentang">
        </div>
        <div class="mb-4">
            <label class="mb-2 block text-sm font-semibold text-slate-700">Highlight Judul</label>
            <input type="text" name="hero_title_highlight" value="{{ old('hero_title_highlight', $d['hero_title_highlight'] ?? '') }}"
                class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200"
                placeholder="Contoh: Sate Simpang Tiga">
        </div>
    </div>
    <div class="mb-4">
        <label class="mb-2 block text-sm font-semibold text-slate-700">Deskripsi Hero</label>
        <textarea name="hero_description" rows="3"
            class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200"
            placeholder="Deskripsi singkat tentang halaman ini">{{ old('hero_description', $d['hero_description'] ?? '') }}</textarea>
    </div>
</div>

{{-- CONTENT SECTION --}}
<div class="mb-6 rounded-xl border border-slate-200 bg-slate-50/50 p-5">
    <h4 class="mb-4 text-sm font-bold text-slate-700"><i class="fas fa-align-left text-orange-500 mr-2"></i>Content Section</h4>
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
        <div class="mb-4">
            <label class="mb-2 block text-sm font-semibold text-slate-700">Section Badge</label>
            <input type="text" name="section_badge" value="{{ old('section_badge', $d['section_badge'] ?? '') }}"
                class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200"
                placeholder="Contoh: Our Story">
        </div>
        <div class="mb-4">
            <label class="mb-2 block text-sm font-semibold text-slate-700">Section Title</label>
            <input type="text" name="section_title" value="{{ old('section_title', $d['section_title'] ?? '') }}"
                class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200"
                placeholder="Contoh: Cita Rasa Autentik">
        </div>
        <div class="mb-4">
            <label class="mb-2 block text-sm font-semibold text-slate-700">Title Highlight</label>
            <input type="text" name="section_title_highlight" value="{{ old('section_title_highlight', $d['section_title_highlight'] ?? '') }}"
                class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200"
                placeholder="Contoh: Sate Simpang Tiga">
        </div>
        {{-- IMAGE UPLOAD --}}
        <div class="mb-4">
            <label class="mb-2 block text-sm font-semibold text-slate-700">Gambar Utama <span class="text-red-500">*</span></label>
            <div class="rounded-xl border-2 border-dashed border-slate-300 p-5 text-center transition hover:border-orange-400">
                <input type="file" name="image_file" id="image_file" accept="image/*" class="hidden" onchange="previewImage(event)">
                <label for="image_file" class="cursor-pointer block">
                    <i class="fas fa-cloud-upload-alt mb-2 text-3xl text-slate-400"></i>
                    <p class="text-sm font-semibold text-slate-600">Klik untuk upload gambar</p>
                    <p class="text-xs text-slate-400">Format: jpeg, png, jpg, webp (maks 4MB)</p>
                </label>
            </div>
            {{-- Image preview for new upload --}}
            <div id="image-preview" class="mt-3 hidden">
                <img id="preview-img" src="" alt="Preview" class="h-40 w-full rounded-xl object-cover ring-1 ring-slate-200">
            </div>
            {{-- Existing image --}}
            @if(isset($information) && !empty($d['image']))
                @php
                    $imgSrc = str_starts_with($d['image'], 'information/')
                        ? asset('storage/' . $d['image'])
                        : asset($d['image']);
                @endphp
                <div id="current-image" class="mt-3">
                    <p class="text-xs font-semibold text-slate-500 mb-2">Gambar saat ini:</p>
                    <img src="{{ $imgSrc }}" alt="Current" class="h-40 w-full rounded-xl object-cover ring-1 ring-slate-200">
                    <input type="hidden" name="existing_image" value="{{ $d['image'] }}">
                </div>
            @else
                <div id="current-image" class="mt-3">
                    <p class="text-xs text-slate-400">Belum ada gambar. Upload gambar untuk ditampilkan.</p>
                </div>
            @endif
        </div>
    </div>
    <div class="mb-4">
        <label class="mb-2 block text-sm font-semibold text-slate-700">Deskripsi 1</label>
        <textarea name="section_description1" rows="3"
            class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200"
            placeholder="Paragraf pertama">{{ old('section_description1', $d['section_description1'] ?? '') }}</textarea>
    </div>
    <div class="mb-4">
        <label class="mb-2 block text-sm font-semibold text-slate-700">Deskripsi 2 (opsional)</label>
        <textarea name="section_description2" rows="3"
            class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200"
            placeholder="Paragraf kedua (opsional)">{{ old('section_description2', $d['section_description2'] ?? '') }}</textarea>
    </div>
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
        <div class="mb-4">
            <label class="mb-2 block text-sm font-semibold text-slate-700">Label "Sejak"</label>
            <input type="text" name="since" value="{{ old('since', $d['since'] ?? '') }}"
                class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200"
                placeholder="Sejak 1975">
        </div>
        <div class="mb-4">
            <label class="mb-2 block text-sm font-semibold text-slate-700">Tagline "Sejak"</label>
            <input type="text" name="since_tagline" value="{{ old('since_tagline', $d['since_tagline'] ?? '') }}"
                class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200"
                placeholder="Menjaga cita rasa autentik">
        </div>
    </div>
</div>

{{-- FEATURES REPEATER --}}
<div class="mb-6 rounded-xl border border-slate-200 bg-slate-50/50 p-5">
    <h4 class="mb-4 text-sm font-bold text-slate-700"><i class="fas fa-list text-orange-500 mr-2"></i>Fitur Unggulan</h4>
    <div id="featuresContainer">
        @php
            $features = $d['features'] ?? [];
            $oldIcons = old('features_icon', []);
            $oldTitles = old('features_title', []);
            $oldDescs = old('features_description', []);
            $hasOld = !empty($oldIcons);
            $items = $hasOld ? $oldIcons : $features;
        @endphp
        @forelse ($items as $i => $val)
            @php $icon = $hasOld ? $oldIcons[$i] : ($features[$i]['icon'] ?? ''); @endphp
            <div class="feature-item mb-4 rounded-xl border border-slate-200 bg-white p-4">
                <div class="flex items-start gap-4">
                    <div class="flex-1 grid grid-cols-1 gap-4 md:grid-cols-3">
                        <div>
                            <label class="mb-2 block text-xs font-semibold text-slate-600">Icon</label>
                            <select name="features_icon[]" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200">
                                <option value="">-- Pilih Icon --</option>
                                @foreach ($icons as $key => $label)
                                    <option value="{{ $key }}" {{ $icon == $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="mb-2 block text-xs font-semibold text-slate-600">Judul</label>
                            <input type="text" name="features_title[]" value="{{ $hasOld ? ($oldTitles[$i] ?? '') : ($features[$i]['title'] ?? '') }}"
                                class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200"
                                placeholder="Nama fitur">
                        </div>
                        <div>
                            <label class="mb-2 block text-xs font-semibold text-slate-600">Deskripsi</label>
                            <input type="text" name="features_description[]" value="{{ $hasOld ? ($oldDescs[$i] ?? '') : ($features[$i]['description'] ?? '') }}"
                                class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200"
                                placeholder="Deskripsi singkat">
                        </div>
                    </div>
                    <button type="button" onclick="this.closest('.feature-item').remove()" class="btn-admin-danger mt-6 px-3 py-1.5 text-xs"><i class="fas fa-trash"></i></button>
                </div>
            </div>
        @empty
        @endforelse
    </div>
    <button type="button" onclick="addFeature()" class="btn-admin-secondary text-sm"><i class="fas fa-plus mr-1"></i>Tambah Fitur</button>
</div>

<script>
function addFeature() {
    const container = document.getElementById('featuresContainer');
    const iconOptions = `@foreach ($icons as $key => $label)<option value="{{ $key }}">{{ $label }}</option>@endforeach`;
    container.insertAdjacentHTML('beforeend', `
        <div class="feature-item mb-4 rounded-xl border border-slate-200 bg-white p-4">
            <div class="flex items-start gap-4">
                <div class="flex-1 grid grid-cols-1 gap-4 md:grid-cols-3">
                    <div>
                        <label class="mb-2 block text-xs font-semibold text-slate-600">Icon</label>
                        <select name="features_icon[]" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200">
                            <option value="">-- Pilih Icon --</option>
                            ${iconOptions}
                        </select>
                    </div>
                    <div>
                        <label class="mb-2 block text-xs font-semibold text-slate-600">Judul</label>
                        <input type="text" name="features_title[]" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200" placeholder="Nama fitur">
                    </div>
                    <div>
                        <label class="mb-2 block text-xs font-semibold text-slate-600">Deskripsi</label>
                        <input type="text" name="features_description[]" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200" placeholder="Deskripsi singkat">
                    </div>
                </div>
                <button type="button" onclick="this.closest('.feature-item').remove()" class="btn-admin-danger mt-6 px-3 py-1.5 text-xs"><i class="fas fa-trash"></i></button>
            </div>
        </div>
    `);
}

function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('preview-img');
            preview.src = e.target.result;
            document.getElementById('image-preview').classList.remove('hidden');
            // Hide current image when new one is selected
            const current = document.getElementById('current-image');
            if (current) current.style.display = 'none';
        };
        reader.readAsDataURL(file);
    }
}
</script>
