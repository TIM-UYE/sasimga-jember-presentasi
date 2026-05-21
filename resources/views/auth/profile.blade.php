<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - SaSimGa</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: #111827;
            min-height: 100vh;
        }

        .profile-card {
            box-shadow: 0 35px 80px rgba(15, 23, 42, 0.35);
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center px-4 py-8">
    <div class="w-full max-w-6xl profile-card rounded-[32px] overflow-hidden bg-slate-900">
        <div class="grid grid-cols-1 lg:grid-cols-2 min-h-[680px]">
            <div class="relative hidden lg:block bg-cover bg-center" style="background-image: url('{{ asset('images/login/login.jpeg') }}');">
                <div class="absolute inset-0 bg-slate-950/35"></div>
                <div class="absolute inset-0 flex flex-col items-center justify-center px-8 text-center text-white">
                    <div class="mb-8 rounded-full bg-white/80 p-4 shadow-lg">
                        <img src="{{ asset('images/logo/logo.png') }}" alt="Simpang Tiga" class="h-16 w-auto">
                    </div>
                    <h2 class="text-3xl font-semibold tracking-tight">Pengaturan Profil</h2>
                    <p class="mt-4 text-sm text-slate-200">Kelola foto, nama, dan kata sandi akun kamu di sini.</p>
                </div>
            </div>

            <div class="bg-white px-8 py-10 sm:px-12 sm:py-12">
                <div class="max-w-xl mx-auto space-y-8">
                    <div>
                        <h1 class="text-3xl font-bold text-slate-900">Akun Saya</h1>
                        <p class="mt-2 text-sm text-slate-500">Perbarui informasi profil dan keamanan Anda.</p>
                    </div>

                    @if(session('success'))
                        <div class="rounded-3xl border border-green-200 bg-green-50 p-4 text-sm text-green-700">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="rounded-3xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="rounded-3xl border border-slate-200 p-6 bg-slate-50">
                        <h2 class="text-lg font-semibold text-slate-900 mb-4">Informasi Profil</h2>
                        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                            @csrf
                            @method('PUT')

                            <div class="flex items-center gap-4">
                                <div class="h-20 w-20 rounded-full overflow-hidden bg-slate-200 border border-slate-300">
                                    <img src="{{ auth()->user()->profile_photo ? asset('storage/profile/' . auth()->user()->profile_photo) : asset('images/logo/logo.png') }}" alt="Foto Profil" class="h-full w-full object-cover">
                                </div>
                                <div>
                                    <label class="block text-sm text-slate-700 font-medium">Upload Foto</label>
                                    <input type="file" name="profile_photo" accept="image/*" class="mt-2 block w-full text-sm text-slate-600" />
                                </div>
                            </div>

                            <div>
                                <label for="nama" class="block text-sm font-medium text-slate-700">Nama Lengkap</label>
                                <input id="nama" name="nama" type="text" value="{{ old('nama', auth()->user()->nama) }}" required
                                    class="mt-2 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none focus:border-orange-400 focus:ring-4 focus:ring-orange-200" />
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-slate-700">Email</label>
                                <input id="email" name="email" type="email" value="{{ old('email', auth()->user()->email) }}" required
                                    class="mt-2 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none focus:border-orange-400 focus:ring-4 focus:ring-orange-200" />
                            </div>

                            <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-orange-500 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-orange-500/20 hover:bg-orange-600 transition">Simpan Profil</button>
                        </form>
                    </div>

                    <div class="rounded-3xl border border-slate-200 p-6 bg-slate-50">
                        <h2 class="text-lg font-semibold text-slate-900 mb-4">Ganti Password</h2>
                        <form action="{{ route('profile.password') }}" method="POST" class="space-y-5">
                            @csrf
                            @method('PUT')

                            <div class="relative">
                                <label for="current_password" class="block text-sm font-medium text-slate-700">Password Saat Ini</label>
                                <div class="relative mt-2">
                                    <input id="current_password" name="current_password" type="password" required
                                        class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 pr-12 text-sm text-slate-900 outline-none focus:border-orange-400 focus:ring-4 focus:ring-orange-200" />
                                    <button type="button" onclick="togglePassword('current_password', 'eyeIconCurrent')" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition">
                                        <i id="eyeIconCurrent" class="fa-solid fa-eye-slash"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="relative">
                                <label for="password" class="block text-sm font-medium text-slate-700">Password Baru</label>
                                <div class="relative mt-2">
                                    <input id="password" name="password" type="password" required
                                        class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 pr-12 text-sm text-slate-900 outline-none focus:border-orange-400 focus:ring-4 focus:ring-orange-200" />
                                    <button type="button" onclick="togglePassword('password', 'eyeIconNew')" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition">
                                        <i id="eyeIconNew" class="fa-solid fa-eye-slash"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="relative">
                                <label for="password_confirmation" class="block text-sm font-medium text-slate-700">Konfirmasi Password Baru</label>
                                <div class="relative mt-2">
                                    <input id="password_confirmation" name="password_confirmation" type="password" required
                                        class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 pr-12 text-sm text-slate-900 outline-none focus:border-orange-400 focus:ring-4 focus:ring-orange-200" />
                                    <button type="button" onclick="togglePassword('password_confirmation', 'eyeIconConfirm')" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition">
                                        <i id="eyeIconConfirm" class="fa-solid fa-eye-slash"></i>
                                    </button>
                                </div>
                            </div>

                            <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-slate-900/20 hover:bg-slate-800 transition">Ubah Password</button>
                        </form>
                    </div>

                    <div class="rounded-3xl border border-slate-200 p-6 bg-slate-50">
                        <h2 class="text-lg font-semibold text-slate-900 mb-4">Aksi Akun</h2>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="inline-flex w-full items-center justify-center rounded-2xl bg-red-500 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-red-500/20 hover:bg-red-600 transition">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        }
    </script>
</body>

</html>
