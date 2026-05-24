<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('admin_assets/img/apple-icon.png') }}" />
    <link rel="icon" type="image/png" href="{{ asset('images/logo/logo.png') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>SaSimGa Dashboard</title>

    {{-- VITE --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @include('admin.layout.style')
    <link rel="stylesheet" href="{{ asset('admin_assets/css/sidebar-custom.css') }}">
    @stack('style')

    <script defer data-site="YOUR_DOMAIN_HERE" src="https://api.nepcha.com/js/nepcha-analytics.js"></script>
</head>

<body class="m-0 font-sans text-base antialiased font-normal leading-default bg-gray-50 text-slate-500">
    @include('sweetalert::alert')

    @include('admin.layout.sidebar')

    <main id="adminMain"
        class="ease-soft-in-out xl:ml-68.5 relative h-full max-h-screen rounded-xl transition-all duration-500">
        @include('admin.layout.navbar')

        <div class="w-full px-6 py-6 mx-auto">
            @yield('content')

            @include('admin.layout.footer')
        </div>
    </main>

    @include('admin.layout.script')
    @stack('script')
</body>

</html>
