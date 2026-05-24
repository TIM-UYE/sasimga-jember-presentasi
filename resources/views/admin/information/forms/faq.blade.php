{{-- FAQ FORM --}}
@php $d = $data ?? []; @endphp

{{-- HEADER SECTION --}}
<div class="mb-6 rounded-xl border border-slate-200 bg-slate-50/50 p-5">
    <h4 class="mb-4 text-sm font-bold text-slate-700"><i class="fas fa-heading text-orange-500 mr-2"></i>Header FAQ</h4>
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
        <div class="mb-4">
            <label class="mb-2 block text-sm font-semibold text-slate-700">Subtitle / Badge</label>
            <input type="text" name="subtitle" value="{{ old('subtitle', $d['subtitle'] ?? '') }}"
                class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200"
                placeholder="Frequently Asked Questions">
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
            placeholder="Deskripsi halaman FAQ">{{ old('description', $d['description'] ?? '') }}</textarea>
    </div>
</div>

{{-- FAQ ITEMS REPEATER --}}
<div class="mb-6 rounded-xl border border-slate-200 bg-slate-50/50 p-5">
    <h4 class="mb-4 text-sm font-bold text-slate-700"><i class="fas fa-circle-question text-orange-500 mr-2"></i>Daftar Pertanyaan & Jawaban</h4>
    <div id="faqItemsContainer">
        @php
            $items = $d['items'] ?? [];
            $oldIcons = old('items_icon', []);
            $oldQuestions = old('items_question', []);
            $oldAnswers = old('items_answer', []);
            $hasOld = !empty($oldIcons);
            $loopItems = $hasOld ? $oldIcons : $items;
        @endphp
        @forelse ($loopItems as $i => $val)
            @php $icon = $hasOld ? $oldIcons[$i] : ($items[$i]['icon'] ?? ''); @endphp
            <div class="faq-item mb-4 rounded-xl border border-slate-200 bg-white p-4">
                <div class="flex items-start gap-4">
                    <div class="flex-1 space-y-4">
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
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
                                <label class="mb-2 block text-xs font-semibold text-slate-600">Pertanyaan</label>
                                <input type="text" name="items_question[]"
                                    value="{{ $hasOld ? ($oldQuestions[$i] ?? '') : ($items[$i]['question'] ?? '') }}"
                                    class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200"
                                    placeholder="Tulis pertanyaan">
                            </div>
                        </div>
                        <div>
                            <label class="mb-2 block text-xs font-semibold text-slate-600">Jawaban</label>
                            <textarea name="items_answer[]" rows="2"
                                class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200"
                                placeholder="Tulis jawaban">{{ $hasOld ? ($oldAnswers[$i] ?? '') : ($items[$i]['answer'] ?? '') }}</textarea>
                        </div>
                    </div>
                    <button type="button" onclick="this.closest('.faq-item').remove()"
                        class="btn-admin-danger mt-6 px-3 py-1.5 text-xs"><i class="fas fa-trash"></i></button>
                </div>
            </div>
        @empty
        @endforelse
    </div>
    <button type="button" onclick="addFaqItem()" class="btn-admin-secondary text-sm"><i class="fas fa-plus mr-1"></i>Tambah Pertanyaan</button>
</div>

{{-- CTA SECTION --}}
<div class="mb-6 rounded-xl border border-slate-200 bg-slate-50/50 p-5">
    <h4 class="mb-4 text-sm font-bold text-slate-700"><i class="fas fa-bullhorn text-orange-500 mr-2"></i>Call to Action (CTA)</h4>
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
        <div class="mb-4">
            <label class="mb-2 block text-sm font-semibold text-slate-700">CTA Text</label>
            <input type="text" name="cta_text" value="{{ old('cta_text', $d['cta_text'] ?? '') }}"
                class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200"
                placeholder="Masih punya pertanyaan?">
        </div>
        <div class="mb-4">
            <label class="mb-2 block text-sm font-semibold text-slate-700">CTA Button Text</label>
            <input type="text" name="cta_button" value="{{ old('cta_button', $d['cta_button'] ?? '') }}"
                class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200"
                placeholder="Reservasi Sekarang">
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
function addFaqItem() {
    const container = document.getElementById('faqItemsContainer');
    const iconOptions = `@foreach ($icons as $key => $label)<option value="{{ $key }}">{{ $label }}</option>@endforeach`;
    container.insertAdjacentHTML('beforeend', `
        <div class="faq-item mb-4 rounded-xl border border-slate-200 bg-white p-4">
            <div class="flex items-start gap-4">
                <div class="flex-1 space-y-4">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-xs font-semibold text-slate-600">Icon</label>
                            <select name="items_icon[]" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200">
                                <option value="">-- Pilih Icon --</option>
                                ${iconOptions}
                            </select>
                        </div>
                        <div>
                            <label class="mb-2 block text-xs font-semibold text-slate-600">Pertanyaan</label>
                            <input type="text" name="items_question[]" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200" placeholder="Tulis pertanyaan">
                        </div>
                    </div>
                    <div>
                        <label class="mb-2 block text-xs font-semibold text-slate-600">Jawaban</label>
                        <textarea name="items_answer[]" rows="2" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200" placeholder="Tulis jawaban"></textarea>
                    </div>
                </div>
                <button type="button" onclick="this.closest('.faq-item').remove()" class="btn-admin-danger mt-6 px-3 py-1.5 text-xs"><i class="fas fa-trash"></i></button>
            </div>
        </div>
    `);
}
</script>
