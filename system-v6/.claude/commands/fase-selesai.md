# /fase-selesai — Update HANDOFF.md Otomatis (Fase Selesai)

> Command ini dijalankan OTOMATIS oleh Claude ketika sebuah fase dinyatakan selesai.
> User tidak perlu mengetik ini — Claude menjalankannya sendiri sebelum menutup sesi fase tersebut.

---

## KAPAN DIJALANKAN

Claude wajib menjalankan prosedur ini ketika:
1. Semua output di fase tersebut sudah bisa dijalankan/ditest
2. User menyatakan "fase ini selesai", "lanjut ke fase berikutnya", atau sejenisnya
3. Di akhir sesi yang menyelesaikan milestone terakhir sebuah fase

---

## URUTAN EKSEKUSI

### Langkah 1 — Rangkum Fase yang Baru Selesai

Sebelum update file, tampilkan dulu rangkuman ke user:

```
══════════════════════════════════════════
  FASE [N] SELESAI — [Nama Fase]
══════════════════════════════════════════

✅ Yang berhasil diselesaikan:
  • [item 1]
  • [item 2]
  • [item 3]

📁 File yang dibuat/diubah:
  • [path file 1]
  • [path file 2]

🏗️ Keputusan arsitektur yang dibuat di fase ini:
  • [keputusan 1 — misal: "Pakai softDelete untuk produk"]
  • [keputusan 2 — atau "-" jika tidak ada]

🚧 Yang belum selesai / sengaja ditunda:
  • [item — dan alasannya]
  • [atau "-" jika semua selesai]

⚠️ Catatan penting untuk fase berikutnya:
  • [hal yang harus diingat — atau "-"]

══════════════════════════════════════════
```

---

### Langkah 2 — Update HANDOFF.md

Buka HANDOFF.md dan lakukan dua hal:

**A. Update tabel ROADMAP RINGKASAN** — ubah status fase ini dari `⏳` atau `🔄` menjadi `✅ Selesai`.

**B. Tambahkan entry baru di bagian `## ✅ LOG FASE SELESAI`** dengan format:

```markdown
---
### FASE [N] — [Nama Fase] ✅
**Selesai**: [tanggal]
**Dikerjakan oleh**: [Claude / Gemini]

#### Yang Diselesaikan
- [item 1]
- [item 2]
- [item 3]

#### File yang Dibuat/Diubah
- `app/Livewire/[nama].php` — [deskripsi singkat]
- `resources/views/livewire/[nama].blade.php` — [deskripsi]
- `database/migrations/[nama].php` — [deskripsi]

#### Keputusan Arsitektur
- [keputusan — misal: "Transaksi pakai DB::transaction() wrap penuh"]
- [atau "-"]

#### Yang Belum Selesai & Alasan
- [item] — [alasan: ditunda ke fase berikutnya / butuh input client / dll]
- [atau "-"]

#### Catatan untuk Fase Berikutnya
- [hal penting — atau "-"]
---
```

**C. Update bagian `## 📌 FASE AKTIF`** — ganti ke fase berikutnya:

```markdown
## 📌 FASE AKTIF

**Fase** : [N+1] — [nama fase berikutnya]
**Mulai** : [tanggal hari ini]
**Status**: 🔄 Sedang berjalan
```

---

### Langkah 3 — Update PROJECT.md

Di bagian `## 📈 PROGRESS SAAT INI`, update:
```
Fase saat ini         : Fase [N+1] — [nama]
Yang sudah jalan      : [rangkuman semua fase yang sudah selesai]
Yang sedang dikerjakan: [item pertama dari fase baru]
Blocking / stuck      : -
Target sesi ini       : [output pertama fase baru]
```

---

### Langkah 3b — Arsipkan Log Lama ke HANDOFF-LOG.md

Setelah menambah entry baru di HANDOFF.md:
- Cek apakah ada entry fase lama (lebih dari 2 fase ke belakang)
- Kalau ada → PINDAHKAN ke HANDOFF-LOG.md
- Tujuan: HANDOFF.md tetap ringkas, tidak membengkak seiring waktu

---

### Langkah 4 — Konfirmasi ke User

```
📝 HANDOFF.md diupdate ✅
📝 PROJECT.md diupdate ✅

Fase [N] sudah terkunci di log.
AI manapun yang membaca HANDOFF.md di sesi berikutnya
akan tahu persis apa yang sudah selesai dan apa yang belum.

──────────────────────────────────────
Fase berikutnya: FASE [N+1] — [nama]
Mulai dari     : [item pertama]
──────────────────────────────────────

Lanjut sekarang atau mulai sesi baru?
```

---

## ATURAN PENTING

- **Jangan skip** prosedur ini meski sesinya pendek atau fasenya "mudah".
- **Jangan singkat** di bagian "Yang Belum Selesai" — ini yang paling kritis untuk AI berikutnya.
- **Keputusan arsitektur wajib dicatat** — sekecil apapun, karena mempengaruhi fase lain.
- Jika fase selesai tapi ada bug kecil yang belum fix → tetap catat di "Yang Belum Selesai", jangan anggap selesai penuh.
