@extends('admin.layout.main')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-800 md:text-3xl">Detail User</h1>
            <p class="mt-1 text-sm text-slate-500">Informasi akun dan riwayat pembaruan user.</p>
        </div>
        <a href="{{ route('admin.user.index') }}" class="btn-admin-secondary">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/80 lg:col-span-2">
            <div class="mb-6 flex items-center gap-4">
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-orange-400 to-amber-600 text-xl font-bold text-white shadow-lg shadow-orange-200/50">
                    {{ strtoupper(substr($user->nama, 0, 1)) }}
                </div>
                <div>
                    <h2 class="text-xl font-bold text-slate-800">{{ $user->nama }}</h2>
                    <p class="text-sm text-slate-500">{{ $user->email }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div class="rounded-xl border border-slate-100 bg-slate-50 p-4">
                    <p class="text-xs font-medium uppercase tracking-wider text-slate-400">Nama Lengkap</p>
                    <p class="mt-1 font-semibold text-slate-800">{{ $user->nama }}</p>
                </div>
                <div class="rounded-xl border border-slate-100 bg-slate-50 p-4">
                    <p class="text-xs font-medium uppercase tracking-wider text-slate-400">Email</p>
                    <p class="mt-1 font-semibold text-slate-800">{{ $user->email }}</p>
                </div>
                <div class="rounded-xl border border-slate-100 bg-slate-50 p-4">
                    <p class="text-xs font-medium uppercase tracking-wider text-slate-400">Tanggal Dibuat</p>
                    <p class="mt-1 font-semibold text-slate-800">{{ $user->created_at->format('d M Y H:i') }}</p>
                </div>
                <div class="rounded-xl border border-slate-100 bg-slate-50 p-4">
                    <p class="text-xs font-medium uppercase tracking-wider text-slate-400">Terakhir Diupdate</p>
                    <p class="mt-1 font-semibold text-slate-800">{{ $user->updated_at->format('d M Y H:i') }}</p>
                </div>
            </div>

            <div class="mt-6 flex gap-2 border-t border-slate-100 pt-5">
                <a href="{{ route('admin.user.edit', $user->user_id) }}" class="btn-admin">
                    <i class="fas fa-edit mr-1"></i> Edit
                </a>
                @if($user->user_id !== auth()->user()->user_id)
                    <form action="{{ route('admin.user.destroy', $user->user_id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn-admin-danger">Hapus</button>
                    </form>
                @endif
            </div>
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/80">
            <p class="mb-3 text-xs font-medium uppercase tracking-wider text-slate-400">Role Pengguna</p>
            @if($user->role == 'admin')
                <span class="inline-flex items-center gap-1.5 rounded-lg bg-red-50 px-3 py-1.5 text-xs font-medium text-red-700 ring-1 ring-red-200/50"><span class="h-1.5 w-1.5 rounded-full bg-red-400"></span>Admin</span>
            @elseif($user->role == 'kasir')
                <span class="inline-flex items-center gap-1.5 rounded-lg bg-blue-50 px-3 py-1.5 text-xs font-medium text-blue-700 ring-1 ring-blue-200/50"><span class="h-1.5 w-1.5 rounded-full bg-blue-400"></span>Kasir</span>
            @else
                <span class="inline-flex items-center gap-1.5 rounded-lg bg-purple-50 px-3 py-1.5 text-xs font-medium text-purple-700 ring-1 ring-purple-200/50"><span class="h-1.5 w-1.5 rounded-full bg-purple-400"></span>Manajer</span>
            @endif
            <p class="mt-4 text-sm text-slate-500">
                Pastikan role user sesuai tanggung jawab untuk menjaga keamanan akses sistem.
            </p>
        </div>
    </div>
</div>
@endsection
