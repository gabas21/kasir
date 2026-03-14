# /init — Blueprint Proyek + Roadmap Adaptif

## Peran Claude

Sebelum satu baris kode pun ditulis, Claude wajib menampilkan **gambaran penuh proyek** terlebih dahulu.
Tujuannya: user dan AI punya peta yang sama — dan peta ini yang akan dipakai seluruh sesi ke depan, termasuk saat pindah AI.

---

## Urutan Eksekusi

### Langkah 1 — Baca & Konfirmasi PROJECT.md

Baca `PROJECT.md` secara menyeluruh. Tampilkan ringkasan:

```
📋 SAYA BACA PROJECT.MD KAMU
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

Proyek    : [nama proyek]
Jenis     : [jenis web]
Client    : [client / tujuan]
Target    : [target selesai]
Kompleksitas : [🟢 Rendah / 🟡 Sedang / 🔴 Tinggi] — [alasan singkat]

Fitur yang diminta:
  ✅ [fitur 1]
  ✅ [fitur 2]
  ✅ [dst...]

Desain:
  Palet    : [nama palet / warna]
  Nuansa   : [formal / modern / dll]
  Device   : [desktop / mobile / keduanya]
```

Tanya: *"Sudah benar semua? Ketik 'lanjut' atau koreksi jika ada yang salah."*

---

### Langkah 2 — Gambaran Lengkap Arsitektur

Setelah user konfirmasi, tampilkan **blueprint teknis** sebelum roadmap:

```
🏗️ BLUEPRINT PROYEK: [Nama Proyek]
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

ARSITEKTUR YANG AKAN DIBANGUN:
  Stack       : TALL (Tailwind + Alpine + Laravel + Livewire)
  Auth        : Laravel Breeze (Blade)
  Role        : [admin saja / admin + user / dll — sesuai PROJECT.md]

DATABASE — Tabel yang akan dibuat:
  • [tabel_1]   : [kolom-kolom kunci]
  • [tabel_2]   : [kolom-kolom kunci]
  [dst sesuai proyek]

RELASI PENTING:
  • [Model A] hasMany [Model B]
  • [Model B] belongsTo [Model A]
  [dst sesuai proyek]

HALAMAN & KOMPONEN YANG AKAN DIBUAT:
  Layouts:
    • layouts/app.blade.php       — layout utama
    • layouts/guest.blade.php     — layout login/register

  Livewire Components (halaman dinamis):
    • [NamaComponent]             — [fungsinya]
    [dst sesuai fitur di PROJECT.md]

  Blade Components (UI reusable):
    • <x-card>                    — card container umum
    • <x-button>                  — tombol dengan loading state
    • <x-modal>                   — modal konfirmasi
    [dst sesuai kebutuhan]

KEPUTUSAN ARSITEKTUR AWAL:
  • [keputusan 1]
  • [keputusan 2]
  [semua keputusan desain penting dicatat di sini]
```

---

### Langkah 3 — Roadmap Adaptif Per Fase

Berdasarkan jenis proyek di PROJECT.md, generate roadmap dengan detail per fase:

```
📅 ROADMAP: [Nama Proyek]
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

FASE 1 — [Nama Fase] (Hari [X]-[Y])
  Tujuan      : [apa yang ingin dicapai]
  Deliverable :
    □ [item konkret 1]
    □ [item konkret 2]
  File utama  : [file yang akan dibuat]
  Risiko      : [potensi masalah, atau "Rendah"]
  ✅ Selesai jika: [kriteria fase dianggap done]

FASE 2 — [Nama Fase] (Hari [X]-[Y])
  [format sama]

⚠️ FASE [N] — [Nama Fase KRITIS] (Hari [X]-[Y])
  [format sama]
  ⚠️ FASE KRITIS — Siapkan waktu ekstra. Backup ke Gemini jika stuck >1 jam.

[dst sampai fase terakhir — Deploy]

TOTAL ESTIMASI: [N] hari kerja
```

#### Referensi roadmap per jenis proyek:

| Jenis | Fase Kritis | Bottleneck |
|---|---|---|
| `kasir` | Fase 3 | Keranjang + transaksi atomik |
| `company-profile` / `instansi` | Tidak ada | Desain & konten |
| `toko-online` | Fase 3 | Keranjang + checkout + stok |
| `portal-berita` | Tidak ada | Manajemen konten |
| `absensi` | Fase 3 | Logic waktu & terlambat |
| `booking` | Fase 3 | Konflik slot & konfirmasi |
| `dashboard-admin` | Fase 3 | Query statistik + chart |
| `inventaris` | Fase 3 | Mutasi stok + audit trail |

---

### Langkah 4 — Inisialisasi HANDOFF.md

Setelah roadmap dikonfirmasi, **langsung tulis ke HANDOFF.md** sebagai snapshot awal proyek.
Ini adalah fondasi yang akan diupdate otomatis setiap fase selesai.

Format isi awal HANDOFF.md:

```markdown
# HANDOFF.md — Jembatan Antar AI
> Diupdate otomatis setiap fase selesai.
> Lampirkan file ini setiap pindah AI.

---

## 🗺️ BLUEPRINT PROYEK
*(Dibuat saat /init — bagian ini JANGAN dihapus atau diubah)*

Proyek   : [nama]
Jenis    : [jenis]
Stack    : TALL — Tailwind · Alpine · Laravel 11 · Livewire v3

### Database:
[list tabel + kolom kunci]

### Relasi:
[list relasi]

### Keputusan Arsitektur:
- [keputusan 1]
- [keputusan 2]

### Roadmap:
- Fase 1: [nama] (Hari X-Y) | [deliverable singkat]
- Fase 2: [nama] (Hari X-Y) | [deliverable singkat]
- ⚠️ Fase 3: [nama KRITIS] (Hari X-Y) | [deliverable singkat]
[dst]

---

## 📦 STATUS FASE TERAKHIR
*(Diisi otomatis setiap fase selesai — entri terbaru selalu di sini)*

Fase Selesai     : —
Tanggal          : —
Dikerjakan oleh  : —

### File Dibuat/Diubah:
*(belum ada)*

### Keputusan Arsitektur Tambahan:
*(belum ada)*

### Yang Belum Selesai + Alasan:
*(belum ada)*

### Next Step:
*(mulai Fase 1 — lihat roadmap di atas)*

---

## 📝 LOG PERUBAHAN
*(Diisi otomatis — entri terbaru di atas)*
```

---

### Langkah 5 — Tulis Roadmap ke PROJECT.md

Isi bagian `## 📅 ROADMAP` di PROJECT.md dengan roadmap yang sudah dibuat.

### Langkah 6 — Konfirmasi & Mulai

```
✅ Blueprint selesai.
✅ HANDOFF.md diinisialisasi — AI manapun kini bisa lanjutkan proyek ini.
✅ Roadmap tercatat di PROJECT.md.

Mau langsung mulai Fase 1?
Langkah pertama: [sebutkan aksi konkret pertama]
```

---

## Catatan

- Jika PROJECT.md belum lengkap → tanya info yang kurang SEBELUM generate blueprint.
- Blueprint harus SPESIFIK: nama tabel, nama komponen, nama file — sesuai konteks proyek nyata.
- Setelah /init, setiap kali fase selesai → Claude wajib jalankan prosedur update HANDOFF.md.
