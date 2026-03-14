# /new-component [nama]

## Peran Claude

Buat Blade component atau Livewire component baru.

## Urutan

1. Tanya: "Ini komponen statik (Blade) atau butuh data dinamis (Livewire)?"
2. Blade component → buat di `resources/views/components/`
3. Livewire component → buat class + view
4. Props harus fleksibel dengan default value
5. Update Registry di DESIGN-SYSTEM.md

## Contoh Props Blade Component
```blade
@props(['title' => '', 'variant' => 'primary', 'class' => ''])
```

## Contoh Livewire Component
```
php artisan livewire:make NamaKomponen
```
