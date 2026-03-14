# /stuck [error atau deskripsi masalah] — Debug dengan Konteks

## Peran Claude

Ini adalah mode debug penuh. Jangan langsung kasih solusi — ikuti urutan ini.

---

## Urutan Debug

### Langkah 1 — Cek Lessons Learned
Baca `tasks/lessons.md`. Apakah error ini atau yang mirip pernah terjadi sebelumnya?
- Jika **YA** → tampilkan solusi yang sudah tercatat + tanya apakah sudah dicoba
- Jika **TIDAK** → lanjut ke Langkah 2

### Langkah 2 — Identifikasi Konteks
Baca `PROJECT.md` untuk pahami fase saat ini dan jenis proyek. Ini penting karena:
- Error di Fase 3 kasir bisa jadi masalah Livewire state atau DB transaction
- Error di company profile bisa jadi masalah asset path atau Blade syntax

### Langkah 3 — Diagnosa Root Cause
Tampilkan analisis singkat:
```
🔍 ANALISIS ERROR
━━━━━━━━━━━━━━━━
Error       : [nama/pesan error]
Kemungkinan : [2-3 kemungkinan penyebab]
Root cause  : [yang paling mungkin]
```

### Langkah 4 — Solusi
Berikan solusi yang:
- **Permanen** — bukan sekedar patch
- **Dengan penjelasan singkat** — kenapa ini terjadi, apa yang diperbaiki
- **Step by step** jika lebih dari 1 langkah

### Langkah 5 — Catat ke Lessons
Setelah solusi diberikan, **selalu** tambahkan entry baru ke `tasks/lessons.md`:

```markdown
## [Tanggal] — [Nama Error Singkat]
**Konteks:** [fase proyek, file yang bermasalah]
**Error:** [pesan error]
**Penyebab:** [root cause]
**Solusi:** [solusi yang berhasil]
**Hindari:** [tips agar tidak terulang]
```

---

## Pola Error Umum TALL Stack

### Livewire
- `wire:model tidak sync` → Cek apakah property sudah dideklarasikan di class, cek tipe data
- `Component not found` → Cek namespace + nama class + nama file harus konsisten
- `Livewire request 500` → Cek error di `storage/logs/laravel.log`, bukan di browser

### Alpine.js
- `x-data tidak reaktif` → Alpine hanya reaktif untuk data yang dideklarasikan di `x-data`
- Konflik Alpine + Livewire → Gunakan `$wire.entangle()` bukan `x-model` langsung

### Laravel
- `Class not found` → Jalankan `composer dump-autoload`
- `View not found` → Cek path, nama file harus lowercase dengan titik sebagai separator
- `CSRF mismatch` → Tambahkan `@csrf` di form atau cek session expire
- `Mass assignment` → Isi `$fillable` di model

### Database
- `Column not found` → Jalankan migration yang tertunda: `php artisan migrate`
- `Duplicate entry` → Cek unique constraint, tambahkan `updateOrCreate` jika diperlukan
- `N+1 query` → Tambahkan `with('relation')` di query Eloquent

### Vite/Asset
- `Asset not found` → Pastikan `npm run dev` atau `npm run build` sudah dijalankan
- `Hot reload tidak jalan` → Pastikan `php artisan serve` dan `npm run dev` berjalan bersamaan
