# /form [deskripsi]

## Peran Claude

Buat form Livewire lengkap dengan validasi.

## Output yang Diharapkan

1. **Livewire Class** dengan:
   - Property untuk setiap field
   - Rules validasi lengkap (required, min, max, unique, dll)
   - Method submit dengan `wire:loading` support
   - Flash message setelah sukses

2. **Blade View** dengan:
   - `wire:model.lazy` untuk setiap input
   - Tampilan error validasi `@error`
   - Tombol dengan `wire:loading.attr="disabled"`
   - UX: disable tombol saat loading

3. **FormRequest** (jika kompleks)

## Contoh Struktur
```php
public function rules(): array {
    return [
        'nama' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $this->userId,
    ];
}
```
