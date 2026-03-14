<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex w-full justify-center items-center px-4 py-2 bg-gold text-white rounded-lg font-medium hover:opacity-90 transition active:scale-95 focus:outline-none']) }}>
    {{ $slot }}
</button>
