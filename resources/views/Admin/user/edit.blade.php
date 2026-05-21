@extends('admin.layout.main')

@section('content')
@if($errors->any())
    <div class="mb-4 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
        <p class="mb-2 font-semibold"><i class="fas fa-exclamation-circle mr-2"></i>Periksa kembali data user:</p>
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
            <h1 class="text-2xl font-bold tracking-tight text-slate-800 md:text-3xl">Edit User</h1>
            <p class="mt-1 text-sm text-slate-500">Perbarui data user dan akses role sesuai kebutuhan.</p>
        </div>
        <a href="{{ route('admin.user.index') }}" class="btn-admin-secondary">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>

    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/80">
        <form action="{{ route('admin.user.update', $user->user_id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div class="mb-4">
                    <label class="mb-2 block text-sm font-semibold text-slate-700" for="nama">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama" id="nama" value="{{ old('nama', $user->nama) }}"
                        class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200"
                        placeholder="Contoh: John Doe" required>
                </div>

                <div class="mb-4">
                    <label class="mb-2 block text-sm font-semibold text-slate-700" for="email">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                        class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200"
                        placeholder="Contoh: john@example.com" required>
                </div>

                <div class="mb-4">
                    <label class="mb-2 block text-sm font-semibold text-slate-700" for="password">
                        Password Baru
                    </label>
                    <input type="password" name="password" id="password"
                        class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200"
                        placeholder="Kosongkan jika tidak diubah">
                    <p class="mt-1 text-xs text-slate-500">Minimal 6 karakter.</p>
                </div>

                <div class="mb-4">
                    <label class="mb-2 block text-sm font-semibold text-slate-700" for="password_confirmation">
                        Konfirmasi Password Baru
                    </label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200"
                        placeholder="Ulangi password baru">
                </div>

                <div class="mb-4 md:col-span-2">
                    <label class="mb-2 block text-sm font-semibold text-slate-700" for="role">
                        Role <span class="text-red-500">*</span>
                    </label>
                    <select name="role" id="role" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-orange-400 focus:ring-2 focus:ring-orange-200" required>
                        <option value="manager" {{ old('role', $user->role) == 'manager' ? 'selected' : '' }}>Manager</option>
                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="owner" {{ old('role', $user->role) == 'owner' ? 'selected' : '' }}>Owner</option>
                    </select>
                    <p class="mt-2 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-xs text-slate-500">
                        <strong>Manager:</strong> Full akses backend & CRUD, <strong>Admin:</strong> Hanya transaksi & reservasi, <strong>Owner:</strong> Read-only analytics dashboard.
                    </p>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-2 border-t border-slate-100 pt-5">
                <a href="{{ route('admin.user.index') }}" class="btn-admin-secondary">Batal</a>
                <button type="submit" class="btn-admin">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
