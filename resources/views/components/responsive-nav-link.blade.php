@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-gold text-start text-base font-medium text-gold bg-gold-muted focus:outline-none focus:text-gold-hover focus:bg-gold/10 focus:border-gold-hover transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-text-muted hover:text-text-main hover:bg-cream hover:border-border-soft focus:outline-none focus:text-text-main focus:bg-cream focus:border-border-soft transition duration-150 ease-in-out';
@endphp

<a wire:navigate {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
