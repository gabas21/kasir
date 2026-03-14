@props([
    'variant' => 'gold',   // gold | danger | ghost
    'size'    => 'md',        // sm | md | lg
    'full'    => false,
    'type'    => 'button',
    'disabled'=> false,
])

@php
$base = 'inline-flex items-center justify-center font-semibold rounded-xl transition-all duration-150 active:scale-95 select-none focus:outline-none';

$variants = [
    'primary' => 'bg-gold text-white hover:opacity-90 disabled:opacity-40 disabled:cursor-not-allowed disabled:active:scale-100',
    'danger'  => 'bg-danger/10 text-danger border border-danger/30 hover:bg-danger hover:text-white disabled:opacity-40 disabled:cursor-not-allowed disabled:active:scale-100',
    'ghost'   => 'border border-border text-text-muted hover:bg-cream hover:text-text-main disabled:opacity-40 disabled:cursor-not-allowed disabled:active:scale-100',
];

$sizes = [
    'sm' => 'px-3 py-2 text-sm gap-1.5',
    'md' => 'px-5 py-3 text-base gap-2',
    'lg' => 'px-6 py-4 text-base gap-2',
];

$classes = $base . ' ' . ($variants[$variant] ?? $variants['gold']) . ' ' . ($sizes[$size] ?? $sizes['md']);
if ($full) $classes .= ' w-full';
@endphp

<button
    type="{{ $type }}"
    {{ $disabled ? 'disabled' : '' }}
    {{ $attributes->merge(['class' => $classes]) }}
>
    {{ $slot }}
</button>
