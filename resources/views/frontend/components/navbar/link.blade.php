@props([
    'href' => '#',
    'active' => false,
    'variant' => 'default',
])

@php
    $baseClass = "
        relative inline-flex items-center
        px-4 py-2
        text-sm
        transition-all duration-300
        text-white/70
        hover:text-orange-400
        hover:[text-shadow:0_0_10px_rgba(251,146,60,0.95),0_0_24px_rgba(235,129,50,0.55)]
    ";

    $orangeClass = "
        text-orange-400
        hover:text-orange-300
        hover:[text-shadow:0_0_12px_rgba(251,146,60,1),0_0_30px_rgba(235,129,50,0.65)]
    ";

    $variantClass = $variant === 'orange' ? $orangeClass : '';
@endphp

<a href="{{ $href }}" {{ $attributes->merge([
    'class' => $baseClass . ' ' . $variantClass,
]) }}>
    {{ $slot }}
</a>
