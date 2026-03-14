# PROJECT.md — Konteks Proyek

> **Isi file ini sekali di awal proyek. Update bagian PROGRESS setiap sesi.**
> Claude akan baca file ini saat `/init` dan adaptasi semua saran sesuai jenis proyek.

---

## 🏷️ IDENTITAS PROYEK

```
Nama Proyek      : POS Cafe — Sistem Kasir Cafe Elit
Jenis Web        : kasir
Target Selesai   : Secepatnya (target ~21 hari dari 14 Maret 2026)
Tanggal Mulai    : 12 Maret 2026
Client / Tujuan  : Cafe elit — tugas akhir kuliah, 1-3 kasir + 1 owner
```

---

## ✅ FITUR YANG DIBUTUHKAN

### Fitur Umum
- [x] Login & logout (role: admin / kasir / owner)
- [x] Dashboard utama
- [ ] Manajemen pengguna (tambah, edit, hapus)
- [ ] Export data ke Excel / PDF
- [ ] Pencarian & filter data

### Fitur Spesifik
- [x] CRUD menu / produk
- [x] CRUD kategori
- [x] Keranjang belanja (POS interface)
- [ ] Pembayaran (Cash / QRIS / Transfer)
- [ ] Struk digital
- [ ] Laporan penjualan harian/mingguan/bulanan
- [ ] Dashboard owner (omzet realtime, grafik, menu terlaris)
- [ ] Manajemen shift & performa kasir
- [ ] Promo / diskon

---

## 💳 PEMBAYARAN

```
Metode bayar     : [x] Cash  [x] QRIS  [x] Transfer Bank
Integrasi payment: [x] Manual konfirmasi (tanpa API)
Struk            : [x] Digital saja
```

---

## 🎨 DESAIN & TAMPILAN

```
Palet warna      : Custom — Modern Dark Premium (inspirasi Stripe + Lemon Squeezy)
                   Primary: #6366F1 (Indigo)
                   Secondary: #8B5CF6 (Violet)
                   Accent: #F59E0B (Amber/Gold)
                   Background: #0F172A (Slate Dark)

Font             : Plus Jakarta Sans (Google Fonts)
Nuansa tampilan  : Modern & Colorful — premium, dynamic, micro-animations
Target perangkat : [x] Keduanya (mobile-first, bagus di desktop)
```

---

## 📊 DATABASE

```
Tabel utama      : users, categories, products, transactions, transaction_details, promos
Relasi penting   :
  • User         hasMany Transaction
  • Category     hasMany Product
  • Product      belongsTo Category
  • Transaction  hasMany TransactionDetail
  • Transaction  belongsTo User
  • TransactionDetail belongsTo Product
```

---

## 📈 PROGRESS SAAT INI

> **Update bagian ini setiap akhir sesi.**

```
Fase saat ini         : Fase 3 — Transaksi & Pembayaran (KRITIS ⚠️)
Yang sudah jalan      : Setup ✅ | Auth ✅ | Layout ✅ | CRUD Produk ✅ | CRUD Kategori ✅ | Keranjang ✅
Yang sedang dikerjakan: Pembayaran + Struk + Laporan
Blocking / stuck      : Tidak ada
Target sesi ini       : Selesaikan /init → lanjut Fase 3
```

---

## 📅 ROADMAP

```
Fase 1 : Setup + Auth + Layout (Hari 1-3)           ✅ SELESAI
Fase 2 : CRUD Produk & Kategori (Hari 4-7)          ✅ SELESAI
⚠️ Fase 3 : Transaksi & Pembayaran (Hari 8-16)      🔄 AKTIF — KRITIS
Fase 4a: Dashboard Kasir + Laporan (Hari 17-20)      ⏳ Menunggu
Fase 4b: Dashboard Owner (Hari 21-25)                ⏳ Menunggu
Fase 5 : Testing + Deploy (Hari 26-28)               ⏳ Menunggu
Fase B : PWA Bonus (Opsional)                         ⏳ Opsional

TOTAL ESTIMASI: ~28 hari kerja (+ bonus PWA opsional)
```

---

## 🗒️ CATATAN TAMBAHAN

```
- Mahasiswa, proyek tugas akhir kuliah
- Server target: Hostinger (shared hosting dulu)
- PHP: 8.2+
- Stack: TALL (Tailwind + Alpine + Laravel 11 + Livewire v3)
- Selera UI: Modern & Colorful seperti Stripe + Lemon Squeezy
- 3 Role: kasir (POS), admin (management), owner (dashboard monitoring)
- Git workflow: semua ke main, checkpoint di momen kritis
- Keranjang disimpan di session (bukan DB)
- QRIS: manual konfirmasi, tanpa payment gateway
```
