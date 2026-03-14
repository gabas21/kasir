<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 bg-white border border-border rounded-lg font-medium text-sm text-text-main hover:bg-cream focus:outline-none transition ease-in-out duration-150 disabled:opacity-25']) }}>
    {{ $slot }}
</button>
