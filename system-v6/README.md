# TALL Stack AI System v3 — Panduan Lengkap

> Sistem dual-AI (Claude + Gemini) untuk membangun proyek web TALL Stack.
> Versi 3 — Universal & Adaptif untuk semua jenis proyek web.

---

## 📦 ISI PAKET INI

```
📁 tall-stack-system-v3/
├── CLAUDE.md          ← Untuk Claude — aturan coding + slash commands
├── GEMINI.md          ← Untuk Gemini — aturan desain UI/UX + slash commands
├── PROJECT.md         ← Template isi sekali per proyek ⭐ BARU
├── DESIGN-SYSTEM.md   ← Sumber warna, font, komponen (diisi saat /init)
├── HANDOFF.md         ← Jembatan saat pindah antar AI
├── tasks/
│   ├── progress.md    ← Progress antar sesi
│   └── lessons.md     ← Error & solusi yang pernah terjadi
└── .claude/commands/
    ├── init.md        ← /init — roadmap adaptif per jenis proyek ⭐ BARU
    ├── fase.md        ← /fase — status fase saat ini ⭐ BARU
    ├── next.md        ← /next — tugas hari ini ⭐ BARU
    ├── stuck.md       ← /stuck — debug dengan konteks ⭐ BARU
    ├── new-page.md    ← /new-page
    ├── new-component.md ← /new-component
    ├── fix-ui.md      ← /fix-ui
    ├── form.md        ← /form
    ├── review.md      ← /review
    ├── deploy-check.md ← /deploy-check
    └── handoff.md     ← /handoff
```

---

## 🚀 CARA PAKAI — PROYEK BARU

### Langkah 1: Copy ke Proyek
Ekstrak semua file ke root folder proyek Laravel kamu.

### Langkah 2: Isi PROJECT.md
Buka `PROJECT.md` dan isi bagian:
- Nama proyek & jenis web
- Fitur yang dibutuhkan (centang yang sesuai)
- Desain & tampilan yang diinginkan

Ini hanya dilakukan **sekali** di awal. Setelah itu, tinggal update bagian PROGRESS di akhir tiap sesi.

### Langkah 3: Mulai Sesi Claude
```
1. Buka Claude.ai
2. Lampirkan: CLAUDE.md + PROJECT.md
3. Ketik: /init
4. Claude baca PROJECT.md → kasih roadmap adaptif sesuai jenis proyekmu
5. Konfirmasi → mulai coding!
```

---

## 🔄 CARA PAKAI — SESI LANJUTAN

```
1. Lampirkan: CLAUDE.md + PROJECT.md + DESIGN-SYSTEM.md
2. Ketik: /next
3. Claude tahu persis di mana kamu berhenti → langsung kasih tugas hari ini
```

---

## 🔀 CARA PINDAH KE GEMINI (dan kembali)

```
Sesi Claude → mau pindah ke Gemini:
1. Ketik /handoff → Claude isi HANDOFF.md
2. Buka Gemini
3. Lampirkan: GEMINI.md + PROJECT.md + DESIGN-SYSTEM.md + HANDOFF.md
4. Gemini konfirmasi → lanjut

Sesi Gemini → mau balik ke Claude:
1. Ketik /handoff di Gemini
2. Buka Claude
3. Lampirkan: CLAUDE.md + PROJECT.md + DESIGN-SYSTEM.md + HANDOFF.md
4. Ketik /next → langsung lanjut
```

---

## 📋 SLASH COMMANDS LENGKAP

### Command Baru (v3)
| Command | Fungsi |
|---|---|
| `/init` | Baca PROJECT.md → buat roadmap adaptif per jenis proyek |
| `/fase` | Lihat status fase saat ini + estimasi progress |
| `/next` | Apa yang dikerjakan hari ini? (berdasarkan progress terakhir) |
| `/stuck [error]` | Debug dengan konteks proyek + catat ke lessons.md |

### Command Lama (tetap ada)
| Command | Fungsi |
|---|---|
| `/new-page [nama]` | Buat halaman Blade baru |
| `/new-component [nama]` | Buat Blade/Livewire component |
| `/fix-ui [file]` | Perbaiki tampilan UI |
| `/form [deskripsi]` | Form Livewire + validasi lengkap |
| `/review [file]` | Review kode |
| `/deploy-check` | Audit kesiapan sebelum deploy |
| `/handoff` | Siapkan pindah ke AI lain |

---

## 🗂️ JENIS PROYEK YANG DIDUKUNG

| Jenis | Level | Bottleneck Utama |
|---|---|---|
| Company profile / instansi | 🟢 Rendah | Desain & konten |
| Portal berita / blog | 🟡 Sedang | Manajemen konten |
| Sistem absensi | 🟡 Sedang | Logic waktu & laporan |
| Booking / reservasi | 🟡 Sedang | State slot & konflik jadwal |
| Dashboard admin | 🟡 Sedang | Query kompleks & chart |
| Kasir / POS | 🔴 Tinggi | State keranjang + transaksi atomik |
| Toko online | 🔴 Tinggi | Stok, order, payment |
| Sistem inventaris | 🔴 Tinggi | Mutasi stok & audit trail |

---

## 💡 TIPS PENTING

**Update PROJECT.md setiap akhir sesi:**
Di bagian `PROGRESS SAAT INI`, tulis apa yang sudah selesai dan apa yang sedang dikerjakan. Ini yang membuat Claude selalu tahu konteks di sesi berikutnya tanpa perlu dijelaskan ulang.

**Satu sesi = satu fitur:**
Jangan coba selesaikan banyak hal dalam satu sesi. Focus pada satu fitur sampai jalan, baru lanjut ke berikutnya.

**Stuck lebih dari 1 jam? Gunakan /stuck:**
Paste error lengkap ke `/stuck [error]`. Claude akan cek apakah ini pernah terjadi sebelumnya, analisis penyebab, dan catat solusinya.

**Fase kritis = siapkan backup:**
Untuk proyek 🔴 Tinggi, siapkan Gemini sebagai backup di fase kritis. Jika Claude kehabisan token di tengah fase kritis, Gemini bisa melanjutkan dengan konteks dari HANDOFF.md.

---

## 📁 DOKUMEN PERENCANAAN (terpisah dari sistem ini)

Jika kamu mengerjakan proyek **kasir/POS**, tersedia dokumen lengkap:
- `Perencanaan-Kasir-Restoran.docx` — dokumen teknis 8 bab (DB schema, roadmap, UI design)
- `Proposal-Sistem-Kasir-Cafe-Client.docx` — proposal non-teknis untuk client

---

## 🔄 ALUR KERJA v4 (Penting Dibaca)

### Proyek Baru → `/init` dulu, baru coding

```
1. Isi PROJECT.md
2. Ketik /init
3. Claude tampilkan:
   ✅ Konfirmasi pemahaman proyek
   ✅ Blueprint arsitektur (tabel DB, komponen, keputusan)
   ✅ Roadmap lengkap per fase dengan kriteria selesai
   ✅ HANDOFF.md diinisialisasi otomatis
4. Konfirmasi → baru mulai coding
```

### Setiap Fase Selesai → HANDOFF.md Diupdate Otomatis

```
Claude mendeteksi fase selesai
  → Tampilkan rangkuman fase ke user
  → Update HANDOFF.md (status + file + keputusan + yang belum selesai)
  → Update PROJECT.md (progress terkini)
  → Konfirmasi ke user
```

### Pindah AI → HANDOFF.md sudah siap

```
Kapanpun pindah ke Gemini atau Claude baru:
  Lampirkan HANDOFF.md → AI langsung tahu semua konteks
  Tidak perlu cerita ulang dari awal
```

---

## 📝 RIWAYAT VERSI

| Versi | Perubahan |
|---|---|
| v1 | Sistem dasar: CLAUDE.md + GEMINI.md + DESIGN-SYSTEM.md + HANDOFF.md (fokus company profile/instansi) |
| v2 | Penambahan perencanaan kasir/POS, analisis kompleksitas, timeline 30 hari |
| v3 | Universal: tambah PROJECT.md, /init adaptif, /fase, /next, /stuck. Support 8 jenis proyek web |
| v4 | Blueprint session: /init tampilkan gambaran lengkap sebelum coding. HANDOFF.md diupdate otomatis setiap fase selesai dengan 4 data kunci: roadmap, file diubah, keputusan arsitektur, yang belum selesai |
| v4 | Blueprint session: /init sekarang wajib tampilkan gambaran lengkap sebelum coding. HANDOFF.md diupdate otomatis setiap fase selesai. Tambah command /fase-selesai. |
| v5 | Tambah 4 gap kritis: protokol konflik Claude vs Gemini, template standar DESIGN-SYSTEM.md, checkpoint siap-test tiap fase (/cek-fase), checklist serah terima client (/siap-client). |
