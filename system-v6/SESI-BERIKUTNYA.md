# SESI-BERIKUTNYA.md — Catatan untuk Sesi Lanjutan
> Baca file ini di awal sesi berikutnya sebelum apapun.
> Lampirkan file ini + CONTEXT.md + semua file sistem v6.

---

## 📍 POSISI SEKARANG

Kita sedang membangun **TALL Stack AI System v6** — sistem file untuk memandu
Claude & Gemini membangun proyek web dari awal sampai selesai.

Konteks penting:
- User = mahasiswa, proyek kasir cafe = tugas akhir kuliah
- Proyek kasir SUDAH BERJALAN sebagian — bukan mulai dari nol
- Hosting target = Hostinger (shared dulu, VPS kalau perlu)
- Stack = TALL (Tailwind + Alpine + Laravel + Livewire)
- Selera UI = Modern & Colorful seperti Stripe + Lemon Squeezy
- Git workflow = semua ke main, perlu checkpoint di momen kritis
- Tools yang akan dipakai = Google Antigravity (agentic AI platform)

---

## ✅ YANG SUDAH SELESAI (v6)

| File | Status |
|------|--------|
| `CONTEXT.md` | ✅ Pintu masuk + anchor + urutan baca + strategi hemat token + Rules for Agent |
| `QUICK-REF.md` | ✅ Referensi ultra-ringkas 30 detik — gantikan baca CLAUDE.md setiap sesi |
| `HANDOFF-LOG.md` | ✅ Arsip fase selesai — HANDOFF.md tetap ringkas |
| `HANDOFF.md` | ✅ Format baru dengan blueprint dikunci + log fase |
| `DESIGN-SYSTEM.md` | ✅ Template lengkap dengan registry komponen |
| `tasks/lessons.md` | ✅ Diperluas dengan section keputusan teknis |
| `tasks/progress.md` | ✅ Format visual 10 detik |
| `.claude/commands/init.md` | ✅ Blueprint session lengkap — PERLU UPDATE (#17) |
| `.claude/commands/fase-selesai.md` | ✅ Update HANDOFF otomatis setiap fase selesai |
| `.claude/commands/cek-fase.md` | ✅ Checkpoint siap test sebelum fase ditutup |
| `.claude/commands/konflik.md` | ✅ Protokol konflik Claude vs Gemini — PERLU UPDATE (#5) |
| `.claude/commands/siap-client.md` | ✅ Checklist serah terima client |

---

## 🔄 YANG BELUM DIKERJAKAN (urutan prioritas)

### Grup 3 — Kualitas UI
- [ ] **#8 Design DNA System** — tambah ke GEMINI.md
      Selera: Modern & Colorful seperti Stripe + Lemon Squeezy
      Benci: card kotak-kotak, warna flat, no animation, layout simetris, icon generik
      Wajib ada: gradient disengaja, asymmetric layout, micro-interaction,
                 empty state yang didesain, typography sebagai elemen visual
      → Edit GEMINI.md bagian UI Designer Mode, tambah section "Design DNA"

- [ ] **#9 `/analisis-referensi [link]`** — command baru
      Flow: user kirim URL → AI analisis visual → ekstrak Design DNA →
            tanya bagian mana yang disukai → simpan ke DESIGN-SYSTEM.md
      → Buat `.claude/commands/analisis-referensi.md`

### Grup 4 — Kebebasan AI & Keamanan Kode
- [ ] **#5 Advisor Channel** — update konflik.md
      Tambah jalur khusus: AI bebas kasih saran improvement tanpa harus ada bug
      Format wajib:
      ```
      💡 SARAN IMPROVEMENT
      Kondisi saat ini : [apa yang ada]
      Saran            : [usulan]
      Manfaat          : [kenapa lebih baik]
      Risiko jika skip : [atau "-"]
      Mau diimplementasi?
      ```
      → Edit `.claude/commands/konflik.md`, tambah section Advisor Channel

- [ ] **#7 `/tambah-fitur`** — command baru
      Flow: deskripsi fitur → analisis file/tabel/komponen yang terpengaruh →
            tampilkan "radius kerusakan" → konfirmasi user → baru coding
      Prinsip: Additive First — tabel baru daripada ubah tabel lama
      → Buat `.claude/commands/tambah-fitur.md`

- [ ] **#10 Git Checkpoint otomatis** — update CLAUDE.md
      Claude ingatkan commit di momen kritis:
      - Sebelum fase baru dimulai
      - Sebelum /tambah-fitur dieksekusi
      - Sebelum refactor besar
      Format reminder:
      ```
      ⚠️ GIT CHECKPOINT
      Sudah commit kondisi yang jalan sekarang?
      git add . && git commit -m "feat: [deskripsi]"
      Ketik "sudah" untuk lanjut.
      ```
      → Edit CLAUDE.md section "Prosedur Otomatis"

### Grup 5 — Deploy & Maintenance
- [ ] **#11 `/deploy` command cerdas** — buat baru (gantikan deploy-check.md)
      Flow: tanya jenis hosting → kasih checklist spesifik
      Hosting yang didukung:
        - Hostinger Shared: 12 langkah khusus termasuk jebakan umum
        - Hostinger VPS: set langkah berbeda
        - Generic VPS: panduan umum
      Sertakan: post-deploy verification (apa yang dicek setelah upload)
      Argumen teknis Hostinger untuk penguji TA sudah disiapkan
      → Buat `.claude/commands/deploy.md`

- [ ] **#12 `/maintenance`** — command baru
      Mode untuk proyek yang sudah live dan ada user nyata
      Flow: re-orientasi baca HANDOFF → tanya jenis perubahan →
            bug fix / perubahan kecil / fitur baru (→ redirect ke /tambah-fitur) →
            konservatif, selalu konfirmasi → catat semua perubahan di HANDOFF
      AI tahu proyek ini sudah live = lebih hati-hati dari biasanya
      → Buat `.claude/commands/maintenance.md`

### Grup 6 — Tugas Akhir & PWA
- [ ] **#13 `/export-docs`** — command baru
      Dokumen yang dibutuhkan kampus (sudah dikonfirmasi user):
        - ERD / database schema
        - Flowchart alur sistem
        - Laporan keputusan teknis
        - Dokumentasi fungsi
        - Manual penggunaan user
      Flow: ekstrak dari HANDOFF + PROJECT + lessons →
            generate draft per dokumen → user tinggal polish
      → Buat `.claude/commands/export-docs.md`

- [ ] **#14 `/buat-manual`** — command baru
      Draft manual user per fitur, dari sudut pandang kasir bukan developer
      Dijalankan setiap fase selesai — bukan numpuk di akhir
      Bahasa: Indonesia, non-teknis, ada screenshot placeholder
      → Buat `.claude/commands/buat-manual.md`

- [ ] **#15 PWA fase bonus** — update init.md
      Tambah "Fase Bonus — PWA" di semua template roadmap jenis proyek
      Isi: manifest.json, service worker, install prompt, icon
      Label: opsional, nilai plus untuk TA
      → Edit `.claude/commands/init.md` bagian template roadmap

### Update File yang Sudah Ada
- [ ] **#16 Dashboard Owner** — update init.md + PROJECT.md
      Detail yang sudah dikonfirmasi:
        Owner: pasif, jarang di tempat, cek dari HP + laptop
        Fitur terkonfirmasi (urutan prioritas):
          1. Omzet realtime hari ini (+ vs kemarin, jumlah transaksi)
          2. Grafik omzet harian/mingguan/bulanan (bar/line chart, toggle periode)
          3. Menu terlaris & tidak laku (dengan threshold evaluasi)
          4. Perbandingan periode (minggu ini vs lalu — omzet, transaksi, rata-rata)
          5. Manajemen shift & performa kasir (+ deteksi void berlebihan)
        Layout: mobile-first tapi bagus di desktop (responsive wajib)
        Fase: Fase 4 dipecah jadi 4a (Dashboard Kasir) + 4b (Dashboard Owner)
        Estimasi tambahan: +5 hari → total proyek ~35 hari
      → Update template roadmap `kasir` di init.md
      → Update profil kompleksitas di CLAUDE.md (kasir naik complexity)
      → Tambah jenis role baru: owner (selain kasir dan admin)

- [ ] **#17 `/init` Audit Mode** — update init.md
      PALING PENTING — user sudah punya kode yang berjalan sebagian
      Tambah deteksi di awal /init:
      ```
      Sebelum mulai, proyek ini:
      A) Baru dibuat — belum ada kode
      B) Sudah berjalan — ada kode yang sudah dibuat
      ```
      Kalau B, flow berbeda:
        1. Minta user lampirkan/sebutkan file yang sudah ada
        2. Audit kondisi: fitur selesai, setengah jalan, error yang diketahui
        3. Petakan posisi di roadmap → tandai ✅ yang sudah selesai
        4. Identifikasi gap — ada yang perlu refactor sebelum lanjut?
        5. Kasih rekomendasi (bukan langsung ubah)
        6. Tawarkan next step dari titik yang ada
      → Edit `.claude/commands/init.md`, tambah Langkah 0 sebelum semua langkah lain

### Bonus — Antigravity Adjustment
- [x] ✅ Tambah "Rules for Agent" di CONTEXT.md — SELESAI
      Mode yang direkomendasikan: Review-driven
      Agent wajib minta izin sebelum ubah file yang sudah ada
      Agent bebas buat file baru tanpa izin
      → Edit CONTEXT.md bagian paling bawah

---

## 🎯 TARGET SESI BERIKUTNYA

Kerjakan semua yang belum selesai di atas sesuai urutan grup.
Setelah semua selesai → zip sebagai `tall-stack-system-v6-complete.zip`

Urutan: #17 dulu (paling kritis) → Grup 3 → Grup 4 → Grup 5 → Grup 6 → Antigravity

---

## 🔄 STATUS KODE YANG SUDAH ADA (dari v3)

> Ini hasil konfirmasi langsung dari user — catat di PROJECT.md saat /init.

```
✅ Fase 1 SELESAI:
   - Setup Laravel + struktur folder
   - Auth / login sudah jalan
   - Layout utama sudah ada

✅ Fase 2 SELESAI:
   - CRUD menu/produk sudah ada

🔄 Fase 3 SEBAGIAN:
   - Keranjang belanja sudah ada
   - Transaksi + pembayaran BELUM ada
   - Laporan/dashboard BELUM ada

⚠️ Catatan untuk /init Audit Mode:
   Saat pertama kali /init dijalankan di v6:
   1. Langsung set posisi di Fase 3 — jangan mulai dari Fase 1
   2. Audit keranjang yang sudah ada — apakah strukturnya solid untuk lanjut?
   3. Identifikasi gap sebelum lanjut ke transaksi
   4. Fase 3 adalah KRITIS — siapkan waktu ekstra
```

---

## 📊 RINGKASAN FITUR KASIR YANG SUDAH DIKONFIRMASI

```
SISTEM KASIR — [nama cafe belum ditentukan]
Role: kasir, admin, owner (3 role)
Fase 1 : Setup + Auth + Layout
Fase 2 : CRUD Menu + Kategori
Fase 3 : ⚠️ KRITIS — Keranjang + Transaksi + Pembayaran (Cash/QRIS/Transfer)
Fase 4a: Dashboard Kasir — laporan transaksi, export Excel
Fase 4b: Dashboard Owner — realtime, grafik, shift, perbandingan periode
Fase 5 : Testing + Deploy (Hostinger)
Fase B : PWA Bonus (nilai plus TA)
Total  : ~35 hari
```
