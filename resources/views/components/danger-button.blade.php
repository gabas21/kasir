<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-danger text-white rounded-lg font-medium hover:opacity-90 active:scale-95 focus:outline-none transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
