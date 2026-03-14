# CONTEXT.md — Pintu Masuk Utama
> Lampirkan file ini DI SETIAP SESI — Claude maupun Gemini.
> Baca file ini PERTAMA sebelum file lain apapun.
> File ini adalah peta — memberitahu kamu harus baca apa dan dalam urutan apa.

---

## ⚡ BACA CEPAT (30 detik)

Buka PROJECT.md bagian PROGRESS SAAT INI → baca 5 baris itu → kamu sudah tahu posisi proyek.
Buka HANDOFF.md bagian FASE AKTIF → baca 3 baris itu → kamu tahu apa yang sedang dikerjakan.
Sekarang kamu siap. Sisanya baca kalau butuh detail.

---

## 📂 URUTAN BACA FILE

```
WAJIB dibaca setiap sesi (urutan ini):
  1. CONTEXT.md         ← ini — baca dulu, selalu
  2. PROJECT.md         ← bagian PROGRESS SAAT INI saja
  3. HANDOFF.md         ← bagian FASE AKTIF + HANDOFF AKTIF

Baca kalau relevan dengan tugas sesi ini:
  4. CLAUDE.md          ← aturan coding (Claude saja)
  5. GEMINI.md          ← aturan desain (Gemini saja)
  6. DESIGN-SYSTEM.md   ← kalau tugas menyentuh UI/tampilan

Baca kalau butuh detail lebih dalam:
  7. HANDOFF.md         ← bagian LOG FASE SELESAI
  8. tasks/lessons.md   ← kalau ada error atau mau cek keputusan lama
  9. tasks/progress.md  ← kalau butuh checklist lengkap
```

> ⚠️ Jangan baca semua file sekaligus. Baca sesuai urutan dan berhenti
> saat sudah cukup untuk mulai bekerja.

---

## 🗺️ PETA FILE SISTEM

| File | Isi | Dibaca oleh |
|------|-----|-------------|
| `CONTEXT.md` | Pintu masuk + urutan baca | Claude & Gemini |
| `PROJECT.md` | Identitas proyek + progress | Claude & Gemini |
| `HANDOFF.md` | Blueprint dikunci + log fase + handoff aktif | Claude & Gemini |
| `CLAUDE.md` | Aturan coding TALL Stack + slash commands | Claude saja |
| `GEMINI.md` | Aturan desain UI/UX + slash commands | Gemini saja |
| `DESIGN-SYSTEM.md` | Warna, font, komponen, registry | Claude & Gemini |
| `tasks/lessons.md` | Error + keputusan teknis yang pernah dibuat | Claude & Gemini |
| `tasks/progress.md` | Checklist progress per fase | Claude & Gemini |
| `.claude/commands/` | Detail instruksi slash commands | Claude saja |

---

## 🔒 HIERARKI OTORITAS

Kalau ada konflik antar file, ikuti urutan ini:

```
HANDOFF.md (blueprint dikunci)     ← tertinggi
    ↓
DESIGN-SYSTEM.md (tampilan)
    ↓
PROJECT.md (konteks & keputusan)
    ↓
Kode yang sudah jalan di browser
    ↓
Preferensi / opini AI              ← terendah
```

---

## 📍 ANCHOR SESI

> Diisi oleh Claude/Gemini di awal setiap sesi, setelah baca PROJECT.md dan HANDOFF.md.
> Ini "anchor" — kalau AI mulai kehilangan konteks di tengah sesi, rujuk bagian ini.

```
PROYEK    : [diisi AI — nama proyek]
FASE      : [diisi AI — fase berapa, nama fase]
DIKERJAKAN: [diisi AI — fitur/halaman apa]
TERAKHIR  : [diisi AI — file terakhir yang disentuh]
SEKARANG  : [diisi AI — apa yang akan dikerjakan sesi ini]
JANGAN    : [diisi AI — hal yang tidak boleh diubah sesi ini]
```

> Claude/Gemini wajib isi anchor ini sebelum mulai coding.
> Kalau di tengah sesi terasa "tersesat" → scroll ke sini, baca anchor, lanjut.

---

## ⚡ STRATEGI HEMAT TOKEN

> Aturan wajib untuk semua sesi — Claude & Gemini.

```
PRINSIP: Lampirkan hanya file yang relevan dengan tugas hari ini.
         Jangan dump semua file sekaligus.

Sesi biasa (coding fitur):
  → CONTEXT.md + QUICK-REF.md + PROJECT.md

Sesi dengan UI/tampilan:
  → + DESIGN-SYSTEM.md

Sesi dengan error/bug:
  → + tasks/lessons.md + file yang error

Sesi lanjut dari AI lain:
  → + HANDOFF.md

Sesi butuh detail aturan coding:
  → + CLAUDE.md (jarang perlu kalau QUICK-REF.md sudah cukup)
```

Satu sesi = satu fitur atau satu halaman.
Jangan campur banyak hal dalam satu sesi.

---

## 🤖 RULES FOR AGENT (Antigravity / Agentic AI)

> Khusus untuk yang menggunakan Google Antigravity atau tools agentic lain.

```
Mode yang direkomendasikan: Review-driven

Boleh tanpa izin:
  ✅ Membuat file baru
  ✅ Membaca file yang ada
  ✅ Menjalankan php artisan & npm (read-only)

Wajib minta izin dulu:
  ⚠️ Mengubah file yang sudah ada
  ⚠️ Menghapus file apapun
  ⚠️ Mengubah struktur database (migration)
  ⚠️ Mengubah file .env
  ⚠️ Mengubah tailwind.config.js atau vite.config.js

Dilarang keras:
  ❌ Override keputusan di HANDOFF.md tanpa konfirmasi user
  ❌ Mengubah komponen yang sudah dipakai di banyak halaman
  ❌ Push ke Git tanpa izin eksplisit
```

---

## 🚀 SHORTCUT MULAI SESI

**Proyek baru:**
```
Lampirkan: CONTEXT.md + PROJECT.md
Ketik: /init
```

**Sesi lanjutan:**
```
Lampirkan: CONTEXT.md + PROJECT.md + HANDOFF.md
Ketik: /next
```

**Lanjut dari AI lain:**
```
Lampirkan: CONTEXT.md + PROJECT.md + HANDOFF.md + DESIGN-SYSTEM.md
Ketik: /next
AI akan baca HANDOFF.md dan konfirmasi pemahaman sebelum mulai
```

**Ada error:**
```
Lampirkan: CONTEXT.md + PROJECT.md + file yang error
Ketik: /stuck [paste error]
```
