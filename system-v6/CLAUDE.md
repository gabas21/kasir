# CLAUDE.md v3 — Universal TALL Stack System

> **Cara pakai:** Lampirkan file ini **+ PROJECT.md** ke Claude di setiap sesi baru.
> Ketik `/init` di awal sesi — Claude akan baca PROJECT.md dan adaptasi otomatis.
> Ini adalah "konstitusi" proyek. Baca seluruhnya sebelum merespons apapun.

---

## 🗂️ PETA FILE SISTEM

| File | Dibaca oleh | Isi |
|---|---|---|
| `CLAUDE.md` | Claude saja | Aturan coding TALL Stack + slash commands |
| `PROJECT.md` | Claude (wajib dibaca tiap sesi) | Konteks proyek — jenis, fitur, progress |
| `GEMINI.md` | Gemini saja | Aturan desain UI/UX + slash commands Gemini |
| `DESIGN-SYSTEM.md` | Claude & Gemini | Warna, font, komponen — satu-satunya sumber desain |
| `HANDOFF.md` | Claude & Gemini | Konteks saat pindah AI |
| `tasks/lessons.md` | Claude | Error & solusi yang pernah terjadi |
| `tasks/progress.md` | Claude | Progres antar sesi |

> ⚠️ Konflik soal warna/font antara file ini dan `DESIGN-SYSTEM.md` → **DESIGN-SYSTEM.md yang menang.**

---

## 🚀 MULAI SESI

**Sesi pertama proyek baru:**
1. Isi `PROJECT.md` (template tersedia — isi sekali, pakai selamanya)
2. Lampirkan `CLAUDE.md` + `PROJECT.md`
3. Ketik `/init` → Claude baca dan adaptasi otomatis

**Sesi lanjutan:**
1. Lampirkan `CLAUDE.md` + `PROJECT.md` + `DESIGN-SYSTEM.md`
2. Ketik `/next` untuk lanjut dari tempat terakhir

---

## ⚙️ STACK & IDENTITAS

- **Stack**: TALL — Tailwind CSS v3 · Alpine.js v3 · Laravel 11 · Livewire v3
- **Template Engine**: Blade
- **Build Tool**: Vite
- **Auth**: Laravel Breeze (Blade variant)
- **Role Saya**: Frontend awam — berikan penjelasan singkat sebelum kode

```bash
php artisan serve                        # Jalankan server lokal
npm run dev                              # Vite dev server (wajib bersamaan)
npm run build                            # Build produksi
php artisan migrate                      # Jalankan migrasi
php artisan optimize:clear               # Bersihkan semua cache
php artisan livewire:make NamaKomponen   # Buat komponen Livewire
php artisan make:component NamaKomponen  # Buat Blade component
```

---

## 📐 STRUKTUR FOLDER

```
resources/
  views/
    components/   ← Blade components (UI reusable: card, button, modal)
    livewire/     ← Template Livewire (halaman dengan data dinamis)
    layouts/      ← Layout utama (app.blade.php, guest.blade.php)
  css/app.css     ← @tailwind directives + @apply untuk komponen berulang
  js/app.js       ← Alpine.js & Livewire bootstrap
app/
  Livewire/       ← Class PHP Livewire
  Http/Requests/  ← FormRequest validasi input
```

---

## 📏 ATURAN CODING WAJIB

### Blade & Livewire
- `wire:model.live` → input search/filter yang butuh respons cepat
- `wire:model.lazy` → form biasa (submit setelah selesai isi)
- Selalu tambahkan `wire:loading` pada **setiap** tombol aksi
- Satu Livewire component = satu tanggung jawab
- UI yang dipakai di >1 halaman → wajib jadi Blade component `<x-nama>`

### Tailwind CSS
- **Dilarang** menulis CSS kustom jika ada utility class yang bisa dipakai
- `@apply` hanya untuk komponen berulang di `resources/css/app.css`
- Semua warna menggunakan token dari `tailwind.config.js` — **tidak boleh hardcode hex**
- Responsive selalu mobile-first: tanpa prefix → `sm:` → `md:` → `lg:`

### Alpine.js
- Hanya untuk interaksi sisi klien: dropdown, modal, toggle, accordion, tab
- Data yang perlu disimpan ke database → **Livewire**, bukan Alpine
- Sinkronisasi Alpine ↔ Livewire → gunakan `$wire.entangle('property')`

### Laravel Backend
- Validasi input → wajib `FormRequest`, jangan `$request->all()`
- Relasi Eloquent → wajib `with()` (eager loading), hindari N+1
- URL/path → wajib `asset()`, `route()`, `vite()` — jangan hardcode

---

## 🧠 TIGA MODE AI

### 🎨 UI Designer Mode
Aktif saat: `/new-page`, `/fix-ui`
- Baca `DESIGN-SYSTEM.md` dan `PROJECT.md` → ikuti identitas proyek
- Tawarkan pilihan estetik jika ada keputusan selera
- Setelah selesai, update Registry di `DESIGN-SYSTEM.md`

### 🔧 Debug Mode
Aktif saat: `/stuck`, `/debug`
- Baca `tasks/lessons.md` → apakah error ini pernah terjadi?
- Cari root cause, bukan patch. Solusi harus permanen
- Catat pelajaran baru ke `tasks/lessons.md`

### 🧠 Advisor Mode
Aktif: **Sepanjang waktu, tanpa diminta**
- Ada yang bisa lebih baik? → `💡 SARAN: [masalah] → [rekomendasi]`
- Ada pola berbahaya? → `⚠️ PERINGATAN:`

---

## 📋 SLASH COMMANDS

| Command | Fungsi |
|---|---|
| `/init` | ⭐ Blueprint session — gambaran lengkap proyek sebelum coding dimulai |
| `/fase` | Tampilkan fase saat ini + progress keseluruhan |
| `/next` | Apa yang harus dikerjakan hari ini? |
| `/new-page [nama]` | Buat halaman Blade baru |
| `/new-component [nama]` | Buat Blade component atau Livewire baru |
| `/fix-ui [file]` | Perbaiki & tingkatkan tampilan |
| `/form [deskripsi]` | Buat form Livewire + validasi lengkap |
| `/stuck [error]` | Debug dengan konteks proyek — cek lessons.md dulu |
| `/review [file]` | Review kode + saran peningkatan |
| `/deploy-check` | Audit kesiapan sebelum upload hosting |
| `/handoff` | Siapkan pindah ke Gemini — isi HANDOFF.md |

> Detail instruksi tiap command ada di `.claude/commands/`

| `/siap-client` | Checklist kelayakan sebelum diserahkan ke client |

### ⚙️ Prosedur Otomatis (tanpa perlu diketik)

| Kondisi | Yang Claude lakukan otomatis |
|---|---|
| Fase dinyatakan selesai | 1. Jalankan `cek-fase` (checkpoint test) → 2. Jalankan `fase-selesai` (update HANDOFF.md) |
| Ada konflik dengan keputusan Gemini | Ikuti protokol di `konflik.md` — tanya user jika tidak ada aturan jelas |
| Sesi mulai panjang | Tawarkan `/handoff` sebelum konteks habis |

> ⚠️ **Hierarki otoritas (dari tertinggi):**
> `HANDOFF.md (blueprint)` → `DESIGN-SYSTEM.md` → `PROJECT.md` → kode yang sudah jalan
> AI yang datang belakangan WAJIB ikut, bukan override.

---

## 💾 MANAJEMEN SESI

- **Satu sesi = satu fitur atau satu halaman** — jangan campur banyak hal
- Stuck >1 jam → `/stuck [paste error]`
- Sesi mulai panjang → `/handoff` untuk pindah ke Gemini
- Sesi baru lanjut → lampirkan `PROJECT.md` + `DESIGN-SYSTEM.md` → ketik `/next`
- Lampirkan **hanya file yang relevan** — jangan dump seluruh codebase

---

## 📊 PROFIL KOMPLEKSITAS (Referensi Cepat)

| Jenis Proyek | Level | Bottleneck |
|---|---|---|
| Company profile / instansi | 🟢 Rendah | Desain & konten |
| Portal berita / blog | 🟡 Sedang | Manajemen konten |
| Sistem absensi | 🟡 Sedang | Logic waktu & laporan |
| Booking / reservasi | 🟡 Sedang | State slot & konflik jadwal |
| Dashboard admin | 🟡 Sedang | Query kompleks & chart |
| Kasir / POS | 🔴 Tinggi | State keranjang + transaksi atomik |
| Toko online | 🔴 Tinggi | Stok, order, payment gateway |
| Sistem inventaris | 🔴 Tinggi | Mutasi stok & audit trail |

---

## 📝 CATATAN PELAJARAN
> Diisi otomatis via `/stuck` atau `/debug`. File lengkap: `tasks/lessons.md`
