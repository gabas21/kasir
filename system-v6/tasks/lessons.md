# tasks/lessons.md — Memori Kolektif Proyek
> Dua fungsi: (1) error yang pernah terjadi + solusinya, (2) keputusan teknis yang sudah dibuat.
> Claude & Gemini WAJIB baca sebelum debug atau membuat keputusan teknis baru.
> Jangan hapus entri lama — ini memori permanen proyek.

---

## 📖 CARA BACA CEPAT
- Cari dengan tag: `#livewire` `#blade` `#alpine` `#query` `#auth` `#vite` `#upload` `#keputusan`
- Error terbaru di **paling atas**
- Sebelum debug → cari dulu di sini, mungkin sudah pernah terjadi
- Sebelum buat keputusan teknis baru → cek dulu, mungkin sudah ada keputusan sebelumnya

---

## 🏗️ KEPUTUSAN TEKNIS

> Setiap keputusan arsitektur penting dicatat di sini.
> Tujuan: AI tidak reinvent solusi berbeda untuk masalah yang sama.

### Template keputusan (salin saat menambah):
```markdown
---
## [YYYY-MM-DD] — Keputusan: [topik singkat]
**Tag**: #keputusan #[area]
**Konteks**: [situasi apa yang membutuhkan keputusan ini]
**Pilihan yang dipertimbangkan**:
  - Opsi A: [deskripsi] → [kelebihan / kekurangan]
  - Opsi B: [deskripsi] → [kelebihan / kekurangan]
**Keputusan**: [opsi yang dipilih]
**Alasan**: [kenapa opsi ini yang dipilih]
**Dampak**: [file/komponen yang terpengaruh]
**Jangan diubah kecuali**: [kondisi yang membolehkan override]
---
```

---

## 2026-03-14 — Konflik: Pemisahan Logic Backend & Middleware oleh Gemini
**Pihak**: Claude vs Gemini
**Masalah**: Sesuai protokol, Logic PHP/Livewire adalah wilayah Claude. Namun Gemini menerima instruksi beruntun dari User untuk memperbaiki Query (extract ke `DashboardService`), menambahkan Pessimistic Locking di `Kasir.php`, dan menyelesaikan pemisahan akses rute dengan `CheckRole` middleware.
**Keputusan**: Perubahan yang dilakukan Gemini **DIKUNCI/DISETUJUI** oleh User untuk dipertahankan.
**Alasan**: User setuju karena perubahannya membuat sistem lebih aman, rapi, dan terisolasi tanpa memicu *error* (dibuktikan dengan `npm run build` dan manual check aman).
**Dicatat oleh**: Gemini

---

## 🐛 ERROR & SOLUSI

> Setiap error yang berhasil diselesaikan dicatat di sini via `/stuck`.

### Template error (salin saat menambah):
```markdown
---
## [YYYY-MM-DD] — [Judul Error Singkat]
**Status**: ✅ Selesai
**Tag**: #[tag1] #[tag2]
- **Konteks**: [di komponen/fitur/halaman apa]
- **Gejala**: [pesan error atau perilaku yang terlihat]
- **Penyebab**: [root cause sebenarnya]
- **Solusi**:
  ```php/html/js
  [kode solusinya]
  ```
- **Pencegahan**: [apa yang harus selalu dilakukan/dihindari]
---
```

_(Kosong — terisi saat error pertama diselesaikan)_

---

## 📚 PRE-LOADED KNOWLEDGE — Pola Error Umum TALL Stack

### #livewire — Property tidak tersimpan
**Penyebab**: Lupa tambahkan ke `$fillable` di Model
**Solusi**: Cek `protected $fillable`

### #livewire — CSRF Token Mismatch
**Penyebab**: Form Livewire tidak dibungkus tag `<form>`
**Solusi**: Selalu pakai `<form wire:submit="...">`

### #livewire — Component not found
**Penyebab**: Namespace + nama class + nama file tidak konsisten
**Solusi**: Jalankan `php artisan livewire:discover`

### #query — Data relasi tidak muncul (N+1)
**Penyebab**: Tidak menggunakan `with()` saat query
**Solusi**: `Model::with(['relasi1', 'relasi2'])->get()`

### #vite — Asset 404 setelah deploy
**Penyebab**: Lupa `npm run build` atau pakai URL hardcode
**Solusi**: Gunakan `@vite(...)` helper, jalankan `npm run build`

### #alpine — x-data tidak reaktif ke Livewire
**Penyebab**: Alpine dan Livewire kelola state terpisah
**Solusi**: Gunakan `$wire.entangle('propertyName')`

### #auth — Redirect setelah login salah halaman
**Penyebab**: `HOME` constant di `RouteServiceProvider` belum diubah
**Solusi**: Ubah `public const HOME = '/dashboard'`
