@php
    $locale = app()->getLocale();
@endphp

<div
    class="relative inline-flex items-center
    rounded-full
    bg-zinc-900/90
    border border-white/10
    ring-1 ring-white/10
    p-1
    shadow-[0_0_20px_rgba(0,0,0,0.35)]">

    {{-- ID --}}
    <a
        href="{{ route('language.switch', 'id') }}"
        aria-label="Switch to Indonesian"
        class="relative z-10
        px-3 py-1.5
        text-[11px] font-semibold tracking-wide
        rounded-full
        transition-all duration-300
        {{ $locale === 'id'
            ? 'text-white bg-orange-500 shadow-[0_0_18px_rgba(235,129,50,0.45)]'
            : 'text-white/45 hover:text-orange-400 hover:[text-shadow:0_0_12px_rgba(251,146,60,0.75)]'
        }}">
        ID
    </a>

    {{-- EN --}}
    <a
        href="{{ route('language.switch', 'en') }}"
        aria-label="Switch to English"
        class="relative z-10
        px-3 py-1.5
        text-[11px] font-semibold tracking-wide
        rounded-full
        transition-all duration-300
        {{ $locale === 'en'
            ? 'text-white bg-orange-500 shadow-[0_0_18px_rgba(235,129,50,0.45)]'
            : 'text-white/45 hover:text-orange-400 hover:[text-shadow:0_0_12px_rgba(251,146,60,0.75)]'
        }}">
        EN
    </a>

</div>