<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - SaSimGa</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            background: #000;
            min-height: 100vh;
        }

        .login-container {
            box-shadow: 0 35px 80px rgba(15, 23, 42, 0.35);
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center px-4 py-8">

    <div class="w-full max-w-6xl login-container rounded-[32px] overflow-hidden bg-slate-900">

        <div class="grid grid-cols-1 lg:grid-cols-2 min-h-[700px]">

            {{-- LEFT SIDE --}}
            <div
                class="relative hidden lg:block bg-cover bg-center"
                style="background-image: url('{{ asset('images/login/login.jpeg') }}');"
            >

                <div class="absolute inset-0 bg-slate-950/35"></div>

                <div class="absolute inset-0 flex flex-col items-center justify-center px-8">

                    <div class="mb-8 rounded-full bg-white/80 p-4 shadow-lg">

                        <img
                            src="{{ asset('images/logo/logo.png') }}"
                            alt="Simpang Tiga"
                            class="h-16 w-auto"
                        >

                    </div>

                    <div class="text-center text-white max-w-xs">

                        <h2 class="text-3xl font-semibold tracking-tight">
                            Selamat Datang di SaSimGa
                        </h2>

                        <p class="mt-4 text-sm text-slate-200">
                            Buat akun baru untuk mengelola menu dan pesanan dengan mudah.
                        </p>

                    </div>

                </div>

            </div>


            {{-- RIGHT SIDE --}}
            <div class="bg-white px-8 py-10 sm:px-12 sm:py-12 flex items-center justify-center">

                <div class="w-full max-w-md">

                    {{-- TITLE --}}
                    <div class="mb-10">

                        <h1 class="text-3xl font-bold text-orange-600">
                            Daftar
                        </h1>

                        <p class="mt-3 text-sm text-slate-500">
                            Isi data berikut untuk membuat akun baru.
                        </p>

                    </div>


                    {{-- ERROR --}}
                    @if ($errors->any())

                        <div class="mb-6 rounded-3xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">

                            <ul class="list-disc list-inside space-y-1">

                                @foreach ($errors->all() as $error)

                                    <li>{{ $error }}</li>

                                @endforeach

                            </ul>

                        </div>

                    @endif


                    {{-- FORM --}}
                    <form action="{{ route('register') }}" method="POST" class="space-y-6">

                        @csrf


                        {{-- NAMA --}}
                        <div>

                            <label for="nama" class="block text-sm font-medium text-slate-700">
                                Nama Lengkap
                            </label>

                            <input
                                id="nama"
                                name="nama"
                                type="text"
                                value="{{ old('nama') }}"
                                placeholder="Nama Lengkap"
                                required
                                class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-orange-400 focus:ring-4 focus:ring-orange-200 @error('nama') border-red-400 ring-red-200 @enderror"
                            />

                        </div>


                        {{-- EMAIL --}}
                        <div>

                            <label for="email" class="block text-sm font-medium text-slate-700">
                                Email
                            </label>

                            <input
                                id="email"
                                name="email"
                                type="email"
                                value="{{ old('email') }}"
                                placeholder="Email"
                                required
                                class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-orange-400 focus:ring-4 focus:ring-orange-200 @error('email') border-red-400 ring-red-200 @enderror"
                            />

                        </div>


                        {{-- PASSWORD --}}
                        <div>

                            <label for="password" class="block text-sm font-medium text-slate-700">
                                Password
                            </label>

                            <div class="relative mt-2">

                                <input
                                    id="password"
                                    name="password"
                                    type="password"
                                    placeholder="Password (min. 8 karakter)"
                                    required
                                    class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 pr-12 text-sm text-slate-900 outline-none transition focus:border-orange-400 focus:ring-4 focus:ring-orange-200 @error('password') border-red-400 ring-red-200 @enderror"
                                />

                                {{-- TOGGLE --}}
                                <button
                                    type="button"
                                    onclick="togglePassword()"
                                    class="absolute inset-y-0 right-0 flex items-center pr-4 text-slate-400 hover:text-orange-500 transition"
                                >

                                    {{-- DEFAULT = PASSWORD HIDDEN --}}
                                    <i id="eyeIconPassword" class="fa-solid fa-eye-slash"></i>

                                </button>

                            </div>

                        </div>


                        {{-- KONFIRMASI PASSWORD --}}
                        <div>

                            <label for="password_confirmation" class="block text-sm font-medium text-slate-700">
                                Konfirmasi Password
                            </label>

                            <div class="relative mt-2">

                                <input
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    type="password"
                                    placeholder="Konfirmasi Password"
                                    required
                                    class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 pr-12 text-sm text-slate-900 outline-none transition focus:border-orange-400 focus:ring-4 focus:ring-orange-200 @error('password_confirmation') border-red-400 ring-red-200 @enderror"
                                />

                                {{-- TOGGLE --}}
                                <button
                                    type="button"
                                    onclick="toggleConfirmPassword()"
                                    class="absolute inset-y-0 right-0 flex items-center pr-4 text-slate-400 hover:text-orange-500 transition"
                                >

                                    {{-- DEFAULT = PASSWORD HIDDEN --}}
                                    <i id="eyeIconConfirm" class="fa-solid fa-eye-slash"></i>

                                </button>

                            </div>

                        </div>


                        {{-- BUTTON --}}
                        <button
                            type="submit"
                            class="w-full rounded-2xl bg-orange-500 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-orange-500/20 transition hover:bg-orange-600"
                        >
                            DAFTAR
                        </button>

                    </form>


                    {{-- LOGIN --}}
                    <div class="mt-8 text-sm text-slate-500 text-center">

                        Sudah punya akun?

                        <a
                            href="{{ route('login') }}"
                            class="font-semibold text-orange-600 hover:text-orange-700"
                        >
                            Masuk sekarang
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- SCRIPT --}}
    <script>

        function togglePassword() {

            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIconPassword');

            // PASSWORD MASIH TERSEMBUNYI
            if (passwordInput.type === 'password') {

                // TAMPILKAN PASSWORD
                passwordInput.type = 'text';

                // ICON = MATA TERBUKA
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');

            } else {

                // SEMBUNYIKAN PASSWORD
                passwordInput.type = 'password';

                // ICON = MATA DISILANG
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            }
        }


        function toggleConfirmPassword() {

            const passwordInput = document.getElementById('password_confirmation');
            const eyeIcon = document.getElementById('eyeIconConfirm');

            // PASSWORD MASIH TERSEMBUNYI
            if (passwordInput.type === 'password') {

                // TAMPILKAN PASSWORD
                passwordInput.type = 'text';

                // ICON = MATA TERBUKA
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');

            } else {

                // SEMBUNYIKAN PASSWORD
                passwordInput.type = 'password';

                // ICON = MATA DISILANG
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            }
        }

    </script>

</body>

</html>
