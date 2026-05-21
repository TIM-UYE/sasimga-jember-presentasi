{{-- TERMS & CONDITIONS FORM --}}
@php $d = $data ?? []; @endphp

{{-- HEADER SECTION --}}
<div class="mb-6 rounded-xl border border-slate-200 bg-slate-50/50 p-5">
    <h4 class="mb-4 text-sm font-bold text-slate-700"><i class="fas fa-heading text-orange-500 mr-2"></i>Header Halaman</h4>
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
        <div class="mb-4">
            <label class="mb-2 block text-sm font-semibold text-slate-700">Subtitle / Badge</label>
            <input type="text" name="subtitle" value="{{ old('subtitle', $d['subtitle'] ?? '') }}"
                class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200"
                placeholder="Terms & Service">
        </div>
        <div class="mb-4">
            <label class="mb-2 block text-sm font-semibold text-slate-700">CTA Button Route</label>
            <select name="cta_route" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200">
                <option value="">-- Pilih Route --</option>
                @foreach ($routes as $key => $label)
                    <option value="{{ $key }}" {{ old('cta_route', $d['cta_route'] ?? '') == $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="mb-4">
        <label class="mb-2 block text-sm font-semibold text-slate-700">Deskripsi</label>
        <textarea name="description" rows="3"
            class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200"
            placeholder="Deskripsi halaman">{{ old('description', $d['description'] ?? '') }}</textarea>
    </div>
</div>

{{-- ITEMS REPEATER --}}
<div class="mb-6 rounded-xl border border-slate-200 bg-slate-50/50 p-5">
    <h4 class="mb-4 text-sm font-bold text-slate-700"><i class="fas fa-list text-orange-500 mr-2"></i>Daftar Ketentuan</h4>
    <div id="termsItemsContainer">
        @php
            $items = $d['items'] ?? [];
            $oldIcons = old('items_icon', []);
            $oldTitles = old('items_title', []);
            $oldDescs = old('items_description', []);
            $hasOld = !empty($oldIcons);
            $loopItems = $hasOld ? $oldIcons : $items;
        @endphp
        @forelse ($loopItems as $i => $val)
            @php $icon = $hasOld ? $oldIcons[$i] : ($items[$i]['icon'] ?? ''); @endphp
            <div class="terms-item mb-4 rounded-xl border border-slate-200 bg-white p-4">
                <div class="flex items-start gap-4">
                    <div class="flex-1 grid grid-cols-1 gap-4 md:grid-cols-3">
                        <div>
                            <label class="mb-2 block text-xs font-semibold text-slate-600">Icon</label>
                            <select name="items_icon[]"
                                class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200">
                                <option value="">-- Pilih Icon --</option>
                                @foreach ($icons as $key => $label)
                                    <option value="{{ $key }}" {{ $icon == $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="mb-2 block text-xs font-semibold text-slate-600">Judul</label>
                            <input type="text" name="items_title[]"
                                value="{{ $hasOld ? ($oldTitles[$i] ?? '') : ($items[$i]['title'] ?? '') }}"
                                class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200"
                                placeholder="Judul ketentuan">
                        </div>
                        <div>
                            <label class="mb-2 block text-xs font-semibold text-slate-600">Deskripsi</label>
                            <input type="text" name="items_description[]"
                                value="{{ $hasOld ? ($oldDescs[$i] ?? '') : ($items[$i]['description'] ?? '') }}"
                                class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200"
                                placeholder="Deskripsi ketentuan">
                        </div>
                    </div>
                    <button type="button" onclick="this.closest('.terms-item').remove()"
                        class="btn-admin-danger mt-6 px-3 py-1.5 text-xs"><i class="fas fa-trash"></i></button>
                </div>
            </div>
        @empty
        @endforelse
    </div>
    <button type="button" onclick="addTermsItem()" class="btn-admin-secondary text-sm"><i class="fas fa-plus mr-1"></i>Tambah Ketentuan</button>
</div>

{{-- CTA SECTION --}}
<div class="mb-6 rounded-xl border border-slate-200 bg-slate-50/50 p-5">
    <h4 class="mb-4 text-sm font-bold text-slate-700"><i class="fas fa-bullhorn text-orange-500 mr-2"></i>Call to Action (CTA)</h4>
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
        <div class="mb-4">
            <label class="mb-2 block text-sm font-semibold text-slate-700">CTA Text</label>
            <input type="text" name="cta_text" value="{{ old('cta_text', $d['cta_text'] ?? '') }}"
                class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200"
                placeholder="Judul CTA">
        </div>
        <div class="mb-4">
            <label class="mb-2 block text-sm font-semibold text-slate-700">CTA Button Text</label>
            <input type="text" name="cta_button" value="{{ old('cta_button', $d['cta_button'] ?? '') }}"
                class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200"
                placeholder="Lihat Menu Kami">
        </div>
    </div>
    <div class="mb-4">
        <label class="mb-2 block text-sm font-semibold text-slate-700">CTA Description</label>
        <textarea name="cta_description" rows="2"
            class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200"
            placeholder="Deskripsi CTA">{{ old('cta_description', $d['cta_description'] ?? '') }}</textarea>
    </div>
</div>

<script>
function addTermsItem() {
    const container = document.getElementById('termsItemsContainer');
    const iconOptions = `@foreach ($icons as $key => $label)<option value="{{ $key }}">{{ $label }}</option>@endforeach`;
    container.insertAdjacentHTML('beforeend', `
        <div class="terms-item mb-4 rounded-xl border border-slate-200 bg-white p-4">
            <div class="flex items-start gap-4">
                <div class="flex-1 grid grid-cols-1 gap-4 md:grid-cols-3">
                    <div>
                        <label class="mb-2 block text-xs font-semibold text-slate-600">Icon</label>
                        <select name="items_icon[]" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200">
                            <option value="">-- Pilih Icon --</option>
                            ${iconOptions}
                        </select>
                    </div>
                    <div>
                        <label class="mb-2 block text-xs font-semibold text-slate-600">Judul</label>
                        <input type="text" name="items_title[]" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200" placeholder="Judul ketentuan">
                    </div>
                    <div>
                        <label class="mb-2 block text-xs font-semibold text-slate-600">Deskripsi</label>
                        <input type="text" name="items_description[]" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200" placeholder="Deskripsi ketentuan">
                    </div>
                </div>
                <button type="button" onclick="this.closest('.terms-item').remove()" class="btn-admin-danger mt-6 px-3 py-1.5 text-xs"><i class="fas fa-trash"></i></button>
            </div>
        </div>
    `);
}
</script>
