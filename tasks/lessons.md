# Catatan Pelajaran (Lessons Learned)

Lacak error dan solusi permanen di sini agar tidak terulang.

---

## 2026-03-14 — Undefined variable $currentTransaction
**Konteks:** Fitur Kasir (POS) - `kasir.blade.php` modal struk `resources/views/livewire/kasir.blade.php`
**Error:** `Undefined variable $currentTransaction`
**Penyebab:** Pada Livewire v3, ketika refactor dari properti state biasa (`public ?Transaction $currentTransaction`) menjadi `#[Computed]` method, variable di sisi Blade harus dipanggil sebagai `$this->currentTransaction` bukan `$currentTransaction` biasa. Karena variable lokal tersebut tidak lagi di-pass secara implisit ke view.
**Solusi:** Merubah semua referensi `$currentTransaction` menjadi `$this->currentTransaction` di dalam blok `kasir.blade.php` yang merender Modal Struk.
**Hindari:** Selalu ingat untuk menggunakan prefix `$this->` di Blade jika mengambil data dari `#[Computed]` properties di komponen Livewire v3.
