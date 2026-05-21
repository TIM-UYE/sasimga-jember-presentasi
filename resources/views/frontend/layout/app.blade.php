<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sate Simpang Tiga')</title>

    {{-- Load logo loading --}}
    <link rel="preload" as="image" href="{{ asset('images/logo/logo.png') }}" fetchpriority="high">

    {{-- PAGE SPECIFIC PRELOAD --}}
    @stack('preloads')

    {{-- GLOBAL ASSET --}}
    @vite(['resources/css/app.css', 'resources/css/google-translate.css', 'resources/js/app.js', 'resources/js/frontend/google-translate.js'])

    {{-- PAGE / SECTION SPECIFIC STYLE --}}
    @stack('styles')

    {{-- Font Awesome Icons --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('images/logo/logo.png') }}">

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=DM+Serif+Text:ital@0;1&family=Poppins:wght@400;500;600;700&display=swap"
        rel="stylesheet">
</head>

<body class="bg-black text-white font-sans">

    {{-- LOADER --}}
    <div id="loader" class="fixed inset-0 z-[9999] bg-black flex items-center justify-center">
        <img src="{{ asset('images/logo/logo.png') }}" alt="Loader" loading="eager" fetchpriority="high"
            decoding="async" data-critical-asset class="loader-image w-28 md:w-36">
    </div>

    {{-- HIDDEN GOOGLE TRANSLATE WIDGET --}}
    <div id="google_translate_element" class="google-translate-box notranslate" translate="no"></div>
    @include('sweetalert::alert')

    @include('frontend.sections.navbar')

    <main>
        @yield('content')
    </main>

    @include('frontend.sections.footer')

    {{-- PAGE / SECTION SPECIFIC SCRIPT --}}
    @stack('scripts')

</body>

</html>
