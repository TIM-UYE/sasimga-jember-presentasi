@extends('admin.layout.main')

@section('content')
@if(session('success'))
    <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
        <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
    </div>
@endif

<div class="space-y-6">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-800 md:text-3xl">Kelola User</h1>
            <p class="mt-1 text-sm text-slate-500">Atur akun admin, manager, dan owner dalam satu halaman.</p>
        </div>
        <a href="{{ route('admin.user.create') }}" class="btn-admin">
            <i class="fas fa-user-plus mr-1"></i>Tambah User
        </a>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <div class="group rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200/80 transition-all duration-300 hover:shadow-lg hover:shadow-orange-500/10 hover:ring-orange-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-slate-400">Total User</p>
                    <p class="mt-1 text-3xl font-bold text-slate-800">{{ $users->count() }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-slate-400 to-slate-600 text-white shadow-lg shadow-slate-200/50">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
        <div class="group rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200/80 transition-all duration-300 hover:shadow-lg hover:shadow-red-500/10 hover:ring-red-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-slate-400">Admin</p>
                    <p class="mt-1 text-3xl font-bold text-slate-800">{{ $users->where('role', 'admin')->count() }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-red-400 to-red-600 text-white shadow-lg shadow-red-200/50">
                    <i class="fas fa-user-shield"></i>
                </div>
            </div>
        </div>
        <div class="group rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200/80 transition-all duration-300 hover:shadow-lg hover:shadow-emerald-500/10 hover:ring-emerald-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-slate-400">Manager + Owner</p>
                    <p class="mt-1 text-3xl font-bold text-slate-800">{{ $users->whereIn('role', ['manager', 'owner'])->count() }}</p>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-400 to-emerald-600 text-white shadow-lg shadow-emerald-200/50">
                    <i class="fas fa-user-cog"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200/80">
        <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
            <div class="flex items-center gap-2">
                <i class="fas fa-list text-slate-400"></i>
                <span class="text-sm font-semibold text-slate-700">Daftar User</span>
                <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-medium text-slate-500"><i class="fas fa-database"></i>{{ $users->count() }} Total</span>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50/80">
                        <th class="px-6 py-4 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">No</th>
                        <th class="px-6 py-4 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">Nama</th>
                        <th class="px-6 py-4 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">Email</th>
                        <th class="px-6 py-4 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">Role</th>
                        <th class="px-6 py-4 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">Dibuat</th>
                        <th class="px-6 py-4 text-right text-[11px] font-semibold uppercase tracking-wider text-slate-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $index => $user)
                    <tr class="group transition-all duration-200 hover:bg-slate-50/60">
                        <td class="px-6 py-4 text-xs font-medium text-slate-400">{{ $index + 1 }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-orange-400 to-amber-600 text-sm font-bold text-white shadow-sm shadow-orange-200/50">
                                    {{ strtoupper(substr($user->nama, 0, 1)) }}
                                </div>
                                <p class="text-sm font-semibold text-slate-800">{{ $user->nama }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600">{{ $user->email }}</td>
                        <td class="px-6 py-4">
                            @if($user->role == 'manager')
                                <span class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-50 px-3 py-1.5 text-xs font-medium text-emerald-700 ring-1 ring-emerald-200/50"><span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>Manager</span>
                            @elseif($user->role == 'admin')
                                <span class="inline-flex items-center gap-1.5 rounded-lg bg-blue-50 px-3 py-1.5 text-xs font-medium text-blue-700 ring-1 ring-blue-200/50"><span class="h-1.5 w-1.5 rounded-full bg-blue-400"></span>Admin</span>
                            @elseif($user->role == 'owner')
                                <span class="inline-flex items-center gap-1.5 rounded-lg bg-purple-50 px-3 py-1.5 text-xs font-medium text-purple-700 ring-1 ring-purple-200/50"><span class="h-1.5 w-1.5 rounded-full bg-purple-400"></span>Owner</span>
                            @else
                                <span class="inline-flex items-center gap-1.5 rounded-lg bg-slate-50 px-3 py-1.5 text-xs font-medium text-slate-700 ring-1 ring-slate-200/50"><span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>{{ ucfirst($user->role) }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-500">{{ $user->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.user.show', $user->user_id) }}" class="btn-admin-secondary">Detail</a>
                                <a href="{{ route('admin.user.edit', $user->user_id) }}" class="btn-admin-secondary">Edit</a>
                                <form action="{{ route('admin.user.destroy', $user->user_id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn-admin-danger">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="mb-4 flex h-20 w-20 items-center justify-center rounded-2xl bg-slate-100">
                                        <i class="fas fa-users text-3xl text-slate-300"></i>
                                    </div>
                                    <p class="mb-1 text-lg font-semibold text-slate-700">Belum ada user</p>
                                    <p class="text-sm text-slate-400">Silakan tambah user baru untuk memulai.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
