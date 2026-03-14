@props([
    'name'      => 'Produk',
    'price'     => 0,
    'category'  => '',
    'image'     => null,
    'available' => true,
    'id'        => null,
])

<div
    @if($available)
        wire:click="addToCart({{ $id }})"
        class="relative bg-white rounded-lg border border-border
               hover:border-gold/50 hover:shadow-md
               active:scale-95 transition-all duration-150
               cursor-pointer p-3 flex flex-col gap-2 select-none"
    @else
        class="relative bg-white rounded-lg border border-border
               opacity-50 cursor-not-allowed p-3 flex flex-col gap-2 select-none"
    @endif
>
    {{-- Badge Habis --}}
    @unless($available)
        <span class="absolute top-2 right-2 inline-flex items-center px-2 py-0.5 rounded-md
                     text-xs font-medium bg-danger/10 text-danger border border-danger/30">
            Habis
        </span>
    @endunless

    {{-- Foto Produk --}}
    <div class="w-full aspect-square rounded-md bg-cream overflow-hidden flex items-center justify-center">
        @if($image)
            <img src="{{ asset($image) }}" alt="{{ $name }}"
                 class="w-full h-full object-cover">
        @else
            {{-- Placeholder icon kopi --}}
            <svg class="w-10 h-10 text-text-muted opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M18 8h1a4 4 0 010 8h-1M2 8h16v9a4 4 0 01-4 4H6a4 4 0 01-4-4V8zM6 1v3M10 1v3M14 1v3"/>
            </svg>
        @endif
    </div>

    {{-- Info Produk --}}
    <div class="flex flex-col gap-0.5">
        <p class="text-text-main font-semibold text-sm leading-tight line-clamp-2">
            {{ $name }}
        </p>
        @if($category)
            <p class="text-text-muted text-xs">{{ $category }}</p>
        @endif
        <p class="text-gold font-bold text-sm mt-1">
            Rp {{ number_format($price, 0, ',', '.') }}
        </p>
    </div>

    {{-- Ripple visual feedback --}}
    @if($available)
        <div class="absolute inset-0 rounded-lg bg-gold opacity-0 hover:opacity-5 transition-opacity duration-150 pointer-events-none"></div>
    @endif
</div>
