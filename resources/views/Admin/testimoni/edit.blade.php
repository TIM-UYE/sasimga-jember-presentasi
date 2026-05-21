@extends('admin.layout.main')

@section('content')
@if($errors->any())
    <div class="mb-4 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
        <p class="mb-2 font-semibold"><i class="fas fa-exclamation-circle mr-2"></i>Periksa kembali data testimoni:</p>
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
                    <h1 class="text-2xl font-bold tracking-tight text-slate-800 md:text-3xl">Edit Testimoni</h1>
                    <p class="mb-0 text-sm text-slate-500">Perbarui ulasan pelanggan agar konten tetap relevan.</p>
                </div>
                <a href="{{ route('admin.testimoni.index') }}" class="btn-admin-secondary">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
    </div>
    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/80">
                <form action="{{ route('admin.testimoni.update', $testimoni) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <!-- Nama Reviewer -->
                        <div class="mb-4">
                            <label class="mb-2 block text-sm font-semibold text-slate-700" for="author_name">
                                Nama Reviewer <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="author_name" id="author_name" value="{{ old('author_name', $testimoni->author_name) }}"
                                class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200"
                                placeholder="Contoh: Ahmad Rahman" required>
                        </div>

                        <!-- Rating -->
                        <div class="mb-4">
                            <label class="mb-2 block text-sm font-semibold text-slate-700" for="rating">
                                Rating <span class="text-red-500">*</span>
                            </label>
                            <select name="rating" id="rating" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200" required>
                                <option value="">Pilih Rating</option>
                                @for($i = 5; $i >= 1; $i--)
                                    <option value="{{ $i }}" {{ old('rating', $testimoni->rating) == $i ? 'selected' : '' }}>
                                        {{ $i }} {{ str_repeat('★', $i) }}
                                    </option>
                                @endfor
                            </select>
                        </div>

                        <!-- Isi Testimoni -->
                        <div class="mb-4 md:col-span-2">
                            <label class="mb-2 block text-sm font-semibold text-slate-700" for="text">
                                Isi Testimoni <span class="text-red-500">*</span>
                            </label>
                            <textarea name="text" id="text" rows="4"
                                class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200"
                                placeholder="Masukkan isi testimoni..." required>{{ old('text', $testimoni->text) }}</textarea>
                        </div>

                        <!-- URL Foto Reviewer -->
                        <div class="mb-4 md:col-span-2">
                            <label class="mb-2 block text-sm font-semibold text-slate-700" for="profile_photo_url">
                                URL Foto Reviewer
                            </label>
                            <input type="url" name="profile_photo_url" id="profile_photo_url" value="{{ old('profile_photo_url', $testimoni->profile_photo_url) }}"
                                class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200"
                                placeholder="https://example.com/photo.jpg">
                        </div>

                        <!-- URL Profil Reviewer -->
                        <div class="mb-4">
                            <label class="mb-2 block text-sm font-semibold text-slate-700" for="author_url">
                                URL Profil Reviewer
                            </label>
                            <input type="url" name="author_url" id="author_url" value="{{ old('author_url', $testimoni->author_url) }}"
                                class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200"
                                placeholder="https://example.com/profile">
                        </div>

                        <!-- Tanggal Review -->
                        <div class="mb-4">
                            <label class="mb-2 block text-sm font-semibold text-slate-700" for="review_date">
                                Tanggal Review
                            </label>
                            <input type="date" name="review_date" id="review_date" value="{{ old('review_date', optional($testimoni->review_date)->format('Y-m-d')) }}"
                                class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200">
                        </div>

                        <!-- Bahasa -->
                        <div class="mb-4">
                            <label class="mb-2 block text-sm font-semibold text-slate-700" for="language">
                                Bahasa
                            </label>
                            <input type="text" name="language" id="language" value="{{ old('language', $testimoni->language) }}"
                                class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200"
                                placeholder="id">
                        </div>

                        <!-- Status Aktif -->
                        <div class="mb-4 md:col-span-2 flex items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
                            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $testimoni->is_active) ? 'checked' : '' }}
                                class="h-4 w-4 rounded border-slate-300 bg-white text-orange-500 focus:ring-orange-500">
                            <label for="is_active" class="text-sm text-slate-700">Aktifkan testimoni ini</label>
                        </div>
                    </div>

                    <div class="mt-6 flex gap-3 border-t border-slate-100 pt-5">
                        <a href="{{ route('admin.testimoni.index') }}" class="btn-admin-secondary">Batal</a>
                        <button type="submit" class="btn-admin">
                            <i class="fas fa-save mr-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
    </div>
</div>
@endsection
