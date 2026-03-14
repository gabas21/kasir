# QUICK-REF.md — Referensi Cepat (Baca Ini, Bukan CLAUDE.md)
> 30 detik baca, langsung siap kerja.
> Hanya lampirkan CLAUDE.md kalau butuh detail aturan coding lengkap.

---

## STACK
TALL — Tailwind v3 · Alpine.js v3 · Laravel 11 · Livewire v3 · Blade · Vite

## PERINTAH PENTING
```bash
php artisan serve          # jalankan server (wajib bersamaan dengan npm)
npm run dev                # vite dev server
php artisan optimize:clear # bersihkan semua cache
php artisan migrate        # jalankan migrasi
```

## ATURAN TIDAK BOLEH DILANGGAR
- `wire:loading` wajib di setiap tombol aksi
- `wire:model.lazy` untuk form biasa, `.live` untuk search
- Warna pakai token tailwind.config.js — dilarang hardcode hex
- Validasi input wajib `FormRequest` — jangan `$request->all()`
- Relasi Eloquent wajib `with()` — hindari N+1
- URL wajib `route()` / `asset()` / `vite()` — jangan hardcode

## FILE MANA YANG DIBACA KAPAN
```
Setiap sesi    → CONTEXT.md + QUICK-REF.md + PROJECT.md (progress saja)
Ada UI/tampilan → + DESIGN-SYSTEM.md
Ada error      → + tasks/lessons.md
Detail aturan  → + CLAUDE.md (jarang perlu)
Pindah AI      → + HANDOFF.md penuh
```

## HIERARKI KALAU ADA KONFLIK
HANDOFF.md → DESIGN-SYSTEM.md → PROJECT.md → kode yang jalan → opini AI

## SLASH COMMANDS
```
/init        → blueprint session (proyek baru ATAU audit proyek yang sudah jalan)
/next        → lanjut dari sesi terakhir
/tambah-fitur → impact analysis sebelum fitur baru
/stuck       → debug dengan konteks
/fase-selesai → update HANDOFF otomatis (dijalankan AI sendiri)
/cek-fase    → checkpoint siap test sebelum fase ditutup
/handoff     → pindah ke Gemini
/deploy      → checklist deploy spesifik per hosting
/maintenance → mode proyek sudah live
/siap-client → checklist serah terima
/export-docs → generate draft dokumen TA
/buat-manual → draft manual user per fitur
```
