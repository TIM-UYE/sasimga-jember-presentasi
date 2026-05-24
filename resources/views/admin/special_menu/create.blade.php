@extends('admin.layout.main')

@section('content')
@if($errors->any())
    <div class="mb-4 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
        <p class="mb-2 font-semibold"><i class="fas fa-exclamation-circle mr-2"></i>Periksa kembali data special:</p>
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
            <h1 class="text-2xl font-bold tracking-tight text-slate-800 md:text-3xl">Tambah Menu Special</h1>
            <p class="mt-1 text-sm text-slate-500">Tambahkan banner special dan varian menu langsung saat pembuatan.</p>
        </div>
        <a href="{{ route('admin.menu-specials.index') }}" class="btn-admin-secondary">
            <i class="fas fa-arrow-left mr-1"></i>Kembali
        </a>
    </div>

    <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200/80">
        <form action="{{ route('admin.menu-specials.store') }}" method="POST" enctype="multipart/form-data" id="specialForm">
            @csrf

            <div class="grid gap-4 md:grid-cols-2">
                <div class="space-y-4">
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700" for="title">
                            Judul Special <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            name="title"
                            id="title"
                            value="{{ old('title') }}"
                            required
                            class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 focus:border-orange-400 focus:ring-orange-200 focus:ring-2"
                            placeholder="Contoh: Special Tumpeng Premium"
                        >
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700" for="short_description">
                            Deskripsi Singkat
                        </label>
                        <textarea
                            name="short_description"
                            id="short_description"
                            rows="5"
                            class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 focus:border-orange-400 focus:ring-orange-200 focus:ring-2"
                            placeholder="Deskripsi singkat mengenai menu special."
                        >{{ old('short_description') }}</textarea>
                    </div>

                    <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-5 text-center transition hover:border-orange-400">
                        <input
                            type="file"
                            name="banner_image"
                            id="banner_image"
                            accept="image/*"
                            class="hidden"
                            onchange="previewBanner(event)"
                        >
                        <label for="banner_image" class="cursor-pointer">
                            <i class="fas fa-cloud-upload-alt mb-3 text-4xl text-slate-400"></i>
                            <p class="font-semibold text-slate-700">Unggah banner special</p>
                            <p class="text-xs text-slate-500">Format: jpeg, png, jpg, gif, webp. Maksimum 4MB.</p>
                        </label>
                    </div>

                    <div id="banner-preview" class="hidden">
                        <p class="mb-2 text-sm font-semibold text-slate-600">Preview Banner</p>
                        <img
                            id="banner-preview-img"
                            src=""
                            alt="Banner Preview"
                            class="h-64 w-full rounded-3xl object-cover ring-1 ring-slate-200"
                        >
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                        <label class="flex items-center gap-3">
                            <input
                                type="checkbox"
                                name="is_active"
                                id="is_active"
                                value="1"
                                class="h-4 w-4 rounded border-slate-300 text-orange-500 focus:ring-orange-500"
                            >
                            <span class="text-sm font-semibold text-slate-700">Aktifkan menu special</span>
                        </label>
                        <p class="mt-3 text-sm text-slate-500">Jika diaktifkan, special akan tampil di halaman menu frontend.</p>
                    </div>

                    <div class="rounded-3xl border border-slate-200 bg-orange-50 p-5">
                        <h2 class="text-lg font-semibold text-slate-800">Tips desain</h2>
                        <p class="mt-3 text-sm text-slate-500">Banner yang jelas dan deskripsi ringkas membuat special menu lebih menarik bagi pelanggan.</p>
                    </div>
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5 mt-6">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-800">Daftar Varian Menu</h2>
                        <p class="text-sm text-slate-500">Tambahkan varian menu sebelum menyimpan special.</p>
                    </div>
                    <button type="button" onclick="openItemModal()" class="btn-admin">Tambah Varian</button>
                </div>

                <div class="mt-4 overflow-x-auto">
                    <table class="w-full min-w-[900px] text-sm text-slate-600">
                        <thead class="border-b border-slate-200 bg-slate-100 text-left text-[11px] uppercase tracking-wider text-slate-500">
                            <tr>
                                <th class="px-4 py-3">Nama Varian</th>
                                <th class="px-4 py-3">Harga</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Deskripsi</th>
                                <th class="px-4 py-3">Komposisi</th>
                                <th class="px-4 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="variantTableBody" class="divide-y divide-slate-200">
                            <tr id="noVariantsRow">
                                <td colspan="6" class="py-10 text-center text-slate-500">
                                    Belum ada varian. Klik tombol Tambah Varian untuk membuat varian baru.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="variantInputs" class="hidden"></div>

            <div class="mt-6 flex flex-col gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:justify-end">
                <a href="{{ route('admin.menu-specials.index') }}" class="btn-admin-secondary">Batal</a>
                <button type="submit" class="btn-admin">Simpan Special</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL VARIAN MENU -->
<div id="itemModal" class="fixed inset-0 z-[9999] hidden items-center justify-center overflow-y-auto bg-black/70 p-4">
    <div class="relative w-full max-w-3xl rounded-3xl bg-slate-950 shadow-2xl ring-1 ring-slate-800">
        <div class="flex items-center justify-between border-b border-slate-800 px-6 py-4">
            <div>
                <h3 class="text-xl font-semibold text-white" id="itemModalTitle">Tambah Varian Menu</h3>
                <p class="text-sm text-slate-400">Isi detail varian spesial dengan harga, gambar, dan komposisi bahan.</p>
            </div>

            <button type="button" onclick="closeItemModal()" class="text-slate-400 transition hover:text-white">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form id="itemModalForm" class="space-y-4 px-6 py-6" onsubmit="event.preventDefault(); saveVariant();">
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-200" for="item_name">
                        Nama Varian <span class="text-orange-400">*</span>
                    </label>
                    <input
                        type="text"
                        name="item_name"
                        id="item_name"
                        required
                        autocomplete="off"
                        style="pointer-events:auto; color: #ffffff !important; caret-color: #f97316; background-color: #0f172a !important;"
                        class="w-full rounded-2xl border-2 border-slate-600 px-4 py-3 text-sm placeholder-slate-400 focus:border-orange-500 focus:ring-2 focus:ring-orange-400"
                        placeholder="Contoh: Tumpeng Mini"
                    >
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-200" for="item_price">
                        Harga <span class="text-orange-400">*</span>
                    </label>
                    <input
                        type="number"
                        name="item_price"
                        id="item_price"
                        required
                        min="0"
                        autocomplete="off"
                        style="pointer-events:auto; color: #ffffff !important; caret-color: #f97316; background-color: #0f172a !important;"
                        class="w-full rounded-2xl border-2 border-slate-600 px-4 py-3 text-sm placeholder-slate-400 focus:border-orange-500 focus:ring-2 focus:ring-orange-400"
                        placeholder="350000"
                    >
                </div>
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-200" for="item_description">Deskripsi</label>
                <textarea
                    name="item_description"
                    id="item_description"
                    rows="4"
                    style="pointer-events:auto; color: #ffffff !important; caret-color: #f97316; background-color: #0f172a !important; resize: none;"
                    class="w-full rounded-2xl border-2 border-slate-600 px-4 py-3 text-sm placeholder-slate-400 focus:border-orange-500 focus:ring-2 focus:ring-orange-400"
                    placeholder="Detail paket dan keunggulan varian."
                ></textarea>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-200" for="item_image">Gambar Varian</label>
                    <input
                        type="file"
                        name="item_image"
                        id="item_image"
                        accept="image/*"
                        class="block w-full text-sm text-slate-200"
                        onchange="previewItemImage(event)"
                    >
                    <p class="mt-2 text-xs text-slate-500">Upload gambar menu terbaik.</p>
                </div>

                <div class="flex items-center rounded-2xl border border-slate-700 bg-slate-900 px-4 py-4">
                    <label class="flex items-center gap-3 text-sm text-slate-200">
                        <input
                            type="checkbox"
                            name="item_is_available"
                            id="item_is_available"
                            value="1"
                            class="h-4 w-4 rounded border-slate-600 bg-slate-800 text-orange-500 focus:ring-orange-500"
                        >
                        <span>Status tersedia</span>
                    </label>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-700 bg-slate-900 p-4">
                <div class="mb-3 flex items-center justify-between gap-3">
                    <div>
                        <h4 class="text-sm font-semibold text-white">Komposisi Stok Bahan</h4>
                        <p class="text-xs text-slate-400">Isi bahan yang dipakai untuk 1 porsi/1 paket varian ini.</p>
                    </div>

                    <button type="button" onclick="addBahanRow()" class="rounded-xl bg-orange-500 px-3 py-2 text-xs font-semibold text-white hover:bg-orange-600">
                        + Tambah Bahan
                    </button>
                </div>

                <div id="bahan-wrapper" class="space-y-3"></div>
            </div>

            <div id="item-image-preview" class="hidden">
                <p class="mb-2 text-sm font-semibold text-slate-200">Preview Gambar</p>
                <img id="item-preview-img" src="" alt="Preview" class="h-56 w-full rounded-3xl object-cover ring-1 ring-slate-700">
            </div>

            <div class="flex flex-col gap-3 pt-4 sm:flex-row sm:justify-end">
                <button type="button" onclick="closeItemModal()" class="rounded-2xl border border-slate-700 px-6 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">
                    Batal
                </button>

                <button type="submit" class="rounded-2xl bg-orange-500 px-6 py-3 text-sm font-semibold text-white transition hover:bg-orange-600">
                    Simpan Varian
                </button>
            </div>
        </form>
    </div>
</div>

@php
    $stokOptions = ($stoks ?? collect())->map(function ($stok) {
        return [
            'id' => $stok->id,
            'nama_bahan' => $stok->nama_bahan,
            'satuan' => $stok->satuan,
            'jumlah_stok' => $stok->jumlah_stok,
        ];
    })->values();
@endphp

<script>
const specialItems = [];
let editingVariantIndex = null;
const STOK_OPTIONS = @json($stokOptions);

function openItemModal(index = null) {
    editingVariantIndex = index;

    const item = index !== null ? specialItems[index] : null;

    document.getElementById('itemModalTitle').textContent = item ? 'Edit Varian Menu' : 'Tambah Varian Menu';
    document.getElementById('item_name').value = item ? item.name : '';
    document.getElementById('item_price').value = item ? item.price : '';
    document.getElementById('item_description').value = item ? item.description : '';
    document.getElementById('item_is_available').checked = item ? item.is_available : true;
    document.getElementById('item_image').value = '';

    fillBahanRows(item ? (item.komposisi || []) : []);

    const preview = document.getElementById('item-image-preview');
    const previewImg = document.getElementById('item-preview-img');

    if (item && item.imageUrl) {
        previewImg.src = item.imageUrl;
        preview.classList.remove('hidden');
    } else {
        preview.classList.add('hidden');
        previewImg.src = '';
    }

    const modal = document.getElementById('itemModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';

    setTimeout(function() {
        document.getElementById('item_name').focus();
    }, 200);
}

function closeItemModal() {
    const modal = document.getElementById('itemModal');

    modal.classList.remove('flex');
    modal.classList.add('hidden');

    document.body.style.overflow = 'auto';
}

function addBahanRow(stokId = '', jumlah = '') {
    const wrapper = document.getElementById('bahan-wrapper');

    const row = document.createElement('div');
    row.className = 'grid gap-3 md:grid-cols-[1fr_160px_40px] bahan-row';

    let options = '<option value="">Pilih bahan</option>';

    STOK_OPTIONS.forEach(function(stok) {
        const selected = String(stok.id) === String(stokId) ? 'selected' : '';
        options += `<option value="${stok.id}" ${selected}>${stok.nama_bahan} (${stok.satuan})</option>`;
    });

    row.innerHTML = `
        <select name="stok_id[]" class="rounded-2xl border-2 border-slate-600 bg-slate-950 px-4 py-3 text-sm text-white focus:border-orange-500 focus:ring-2 focus:ring-orange-400">
            ${options}
        </select>

        <input type="number" step="0.01" min="0.01" name="jumlah_dibutuhkan[]" value="${jumlah}" placeholder="Jumlah"
            class="rounded-2xl border-2 border-slate-600 bg-slate-950 px-4 py-3 text-sm text-white placeholder-slate-400 focus:border-orange-500 focus:ring-2 focus:ring-orange-400">

        <button type="button" onclick="this.closest('.bahan-row').remove()" class="rounded-xl bg-red-500 text-white hover:bg-red-600">
            ×
        </button>
    `;

    wrapper.appendChild(row);
}

function resetBahanRows() {
    document.getElementById('bahan-wrapper').innerHTML = '';
}

function fillBahanRows(komposisi = []) {
    resetBahanRows();

    if (!komposisi || komposisi.length === 0) {
        addBahanRow();
        return;
    }

    komposisi.forEach(function(row) {
        addBahanRow(row.stok_id, row.jumlah_dibutuhkan);
    });
}

function collectBahanRows() {
    const komposisi = [];

    document.querySelectorAll('#bahan-wrapper .bahan-row').forEach(function(row) {
        const stokSelect = row.querySelector('select[name="stok_id[]"]');
        const jumlahInput = row.querySelector('input[name="jumlah_dibutuhkan[]"]');

        if (stokSelect && jumlahInput && stokSelect.value && jumlahInput.value) {
            const selectedOption = stokSelect.options[stokSelect.selectedIndex];

            komposisi.push({
                stok_id: stokSelect.value,
                jumlah_dibutuhkan: jumlahInput.value,
                label: selectedOption ? selectedOption.textContent : ''
            });
        }
    });

    return komposisi;
}

function saveVariant() {
    const name = document.getElementById('item_name').value.trim();
    const price = Number(document.getElementById('item_price').value);
    const description = document.getElementById('item_description').value.trim();
    const isAvailable = document.getElementById('item_is_available').checked;
    const imageInput = document.getElementById('item_image');
    const imageFile = imageInput.files[0];
    const imageUrl = imageFile ? URL.createObjectURL(imageFile) : null;
    const komposisi = collectBahanRows();

    if (!name || Number.isNaN(price)) {
        alert('Nama dan harga wajib diisi');
        return;
    }

    if (editingVariantIndex !== null) {
        const current = specialItems[editingVariantIndex];

        specialItems[editingVariantIndex] = {
            name,
            price,
            description,
            is_available: isAvailable,
            image: imageFile || current?.image || null,
            imageUrl: imageUrl || current?.imageUrl || null,
            komposisi,
        };
    } else {
        specialItems.push({
            name,
            price,
            description,
            is_available: isAvailable,
            image: imageFile || null,
            imageUrl,
            komposisi,
        });
    }

    renderVariantList();
    closeItemModal();
}

function deleteVariant(index) {
    specialItems.splice(index, 1);
    renderVariantList();
}

function renderVariantList() {
    const tableBody = document.getElementById('variantTableBody');

    tableBody.innerHTML = '';

    if (specialItems.length === 0) {
        tableBody.innerHTML = `
            <tr>
                <td colspan="6" class="py-10 text-center text-slate-500">
                    Belum ada varian menu
                </td>
            </tr>
        `;

        return;
    }

    specialItems.forEach(function(item, index) {
        const row = document.createElement('tr');
        row.className = 'border-b border-slate-200';

        let komposisiHtml = '-';

        if (item.komposisi && item.komposisi.length > 0) {
            komposisiHtml = item.komposisi.map(function(bahan) {
                return `<div class="text-xs">${bahan.label}: ${bahan.jumlah_dibutuhkan}</div>`;
            }).join('');
        }

        row.innerHTML = `
            <td class="px-4 py-4 font-semibold text-slate-800">${item.name}</td>

            <td class="px-4 py-4 text-slate-700">Rp ${item.price.toLocaleString('id-ID')}</td>

            <td class="px-4 py-4">
                ${item.is_available
                    ? '<span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">Tersedia</span>'
                    : '<span class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">Tidak Tersedia</span>'
                }
            </td>

            <td class="px-4 py-4 text-slate-600">${item.description || '-'}</td>

            <td class="px-4 py-4 text-slate-600">${komposisiHtml}</td>

            <td class="px-4 py-4">
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="openItemModal(${index})" class="rounded-xl bg-slate-800 px-4 py-2 text-xs font-semibold text-white">
                        Edit
                    </button>

                    <button type="button" onclick="deleteVariant(${index})" class="rounded-xl bg-red-500 px-4 py-2 text-xs font-semibold text-white">
                        Hapus
                    </button>
                </div>
            </td>
        `;

        tableBody.appendChild(row);
    });
}

function previewItemImage(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('item-image-preview');
    const previewImg = document.getElementById('item-preview-img');

    if (!file) {
        preview.classList.add('hidden');
        previewImg.src = '';
        return;
    }

    const reader = new FileReader();

    reader.onload = function(e) {
        previewImg.src = e.target.result;
        preview.classList.remove('hidden');
    };

    reader.readAsDataURL(file);
}

function previewBanner(event) {
    const file = event.target.files[0];

    if (!file) {
        return;
    }

    const reader = new FileReader();

    reader.onload = function(e) {
        const preview = document.getElementById('banner-preview');
        const image = document.getElementById('banner-preview-img');

        image.src = e.target.result;
        preview.classList.remove('hidden');
    };

    reader.readAsDataURL(file);
}

document.getElementById('itemModal').addEventListener('click', function(e) {
    if (e.target.id === 'itemModal') {
        closeItemModal();
    }
});

document.getElementById('specialForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const form = this;
    const formData = new FormData();

    formData.append('_token', document.querySelector('input[name="_token"]').value);
    formData.append('title', document.getElementById('title').value);
    formData.append('short_description', document.getElementById('short_description').value);

    const isActiveCheckbox = document.getElementById('is_active');

    if (isActiveCheckbox.checked) {
        formData.append('is_active', '1');
    }

    const bannerInput = document.getElementById('banner_image');

    if (bannerInput.files.length > 0) {
        formData.append('banner_image', bannerInput.files[0]);
    }

    specialItems.forEach(function(item, index) {
        formData.append(`items[${index}][name]`, item.name);
        formData.append(`items[${index}][price]`, String(item.price));
        formData.append(`items[${index}][description]`, item.description || '');
        formData.append(`items[${index}][is_available]`, item.is_available ? '1' : '0');

        if (item.image && item.image instanceof File) {
            formData.append(`items[${index}][image]`, item.image);
        }

        if (item.komposisi && item.komposisi.length > 0) {
            item.komposisi.forEach(function(bahan, bahanIndex) {
                formData.append(`items[${index}][stok_id][${bahanIndex}]`, bahan.stok_id);
                formData.append(`items[${index}][jumlah_dibutuhkan][${bahanIndex}]`, bahan.jumlah_dibutuhkan);
            });
        }
    });

    fetch(form.action, {
        method: 'POST',
        headers: {
            'Accept': 'application/json, text/html',
        },
        body: formData,
    })
    .then(function(response) {
        if (response.redirected) {
            window.location.href = response.url;
            return;
        }

        if (response.ok) {
            window.location.href = '{{ route("admin.menu-specials.index") }}';
            return;
        }

        if (response.status === 422) {
            return response.json().then(function(data) {
                var errorMessages = [];
                var errors = data.errors || {};

                for (var key in errors) {
                    if (errors.hasOwnProperty(key)) {
                        errorMessages.push(errors[key][0]);
                    }
                }

                alert('Validation Error:\n' + errorMessages.join('\n'));
            });
        }

        throw new Error('Server error (HTTP ' + response.status + ')');
    })
    .catch(function(error) {
        alert('Terjadi kesalahan. Mengirim ulang form secara normal...');
        form.submit();
    });
});
</script>
@endsection