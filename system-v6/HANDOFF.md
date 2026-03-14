# HANDOFF.md — Jembatan Antar AI

> Diupdate otomatis setiap fase selesai.
> **Cara pakai:** Lampirkan file ini setiap kali pindah AI (Claude → Gemini atau sebaliknya).
> AI yang menerima handoff wajib baca file ini dari atas ke bawah sebelum melakukan apapun.

---

## 🗺️ BLUEPRINT PROYEK
> ⚠️ Bagian ini JANGAN dihapus atau diubah. Ini fondasi permanen dari `/init`.

```
Proyek   : POS Cafe — Sistem Kasir Cafe Elit
Jenis    : kasir (Point of Sale)
Client   : Cafe elit — tugas akhir kuliah mahasiswa
Stack    : TALL — Tailwind · Alpine · Laravel 11 · Livewire v3
Auth     : Laravel Breeze (Blade)
Role     : kasir, admin, owner (3 role)
```

### Database — Tabel & Kolom Kunci:
```
• users                : id, name, email, password, role, timestamps
• categories           : id, name, timestamps
• products             : id, name, category_id, price, stock, image, description, timestamps
• transactions         : id, user_id, invoice_number, total_price, status, payment_method, paid_amount, change_amount, notes, timestamps
• transaction_details  : id, transaction_id, product_id, quantity, price, subtotal, timestamps
• promos               : id, name, type, value, start_date, end_date, timestamps
```

### Relasi Penting:
```
• User          hasMany   Transaction
• Category      hasMany   Product
• Product       belongsTo Category
• Transaction   hasMany   TransactionDetail
• Transaction   belongsTo User
• TransactionDetail belongsTo Product
• Transaction   belongsTo Promo (implicit via promo_code)
```

### Keputusan Arsitektur Awal:
```
• QRIS & Transfer: manual konfirmasi (ada UI placeholder QRIS & rekening)
• Keranjang: disimpan di Livewire state selama transaksi berlangsung
• Soft delete untuk produk agar data histori transaksi tetap utuh
• Premium Dark Mode dengan micro-interaction (glassmorphism, CSS keyframes, stagger animations)
• POS route terpisah dari admin (/pos vs /owner)
• Plus Jakarta Sans sebagai font utama
• Palet: Indigo #6366F1 + Violet #8B5CF6 + Amber #F59E0B
```

### Roadmap Lengkap:
```
• Fase 1: Setup + Auth + Layout (Hari 1-3)         ✅ SELESAI
• Fase 2: CRUD Produk & Kategori (Hari 4-7)        ✅ SELESAI
• Fase 3: Transaksi & Pembayaran (Hari 8-16)       ✅ SELESAI | Kasir.php terintegrasi Cash/QRIS/Transfer & Struk
• Fase 4a: Dashboard Kasir + Laporan (Hari 17-20)  ✅ SELESAI | Export PDF (dompdf) & CSV, UserManager (CRUD Role)
• Fase 4b: Dashboard Owner (Hari 21-25)            ✅ SELESAI | Omzet realtime, ApexCharts, performa kasir
• Fase 4c: Promo Integration                       ✅ SELESAI | PromoManager, potongan persentase/nominal di Kasir
• ⚠️ Fase 5: Testing + Deploy (Hari 26-28)         🔄 AKTIF  | Bug fix iterasi terakhir, persiapan shared hosting
• Fase B: PWA Bonus (Opsional)                     ⏳ | manifest, service worker, install prompt
```

---

## 📌 FASE AKTIF

```
Fase   : Fase 5 — Testing & Deploy (Persiapan Akhir)
Status : 🔄 Bersiap untuk deploy
Note   : Semua fitur utama (Kasir, Laporan, Owner, User, Promo) sudah SELESAI & diverifikasi (build sukses). Tinggal testing menyeluruh, cek edge case, dan deploy ke public html.
```

---

## 📦 STATUS FASE TERAKHIR
> Diisi otomatis setiap fase selesai.

```
Fase Selesai    : Fase 3, 4a, 4b, 4c (Transaksi, Dashboard Owner, Export Laporan, Promo, User Management)
Tanggal         : 14 Maret 2026
Dikerjakan oleh : Gemini (Antigravity v1.0)
```

### File Dibuat/Diubah:
```
• resources/css/app.css (Dark mode premium + Micro-interactions keyframes)
• resources/views/layouts/app.blade.php & pos-layout.blade.php (Font Plus Jakarta Sans)
• app/Livewire/Kasir.php & kasir.blade.php (Checkout Auth::id(), Promo apllication, animasi)
• app/Livewire/OwnerDashboard.php & owner-dashboard.blade.php (Statistik kompleks, ApexCharts)
• app/Http/Controllers/ExportController.php & sales-report-pdf.blade.php (DomPDF)
• app/Livewire/UserManager.php & user-manager.blade.php (Role kasir/admin/owner, perlindungan self-delete)
• app/Livewire/PromoManager.php & promo-manager.blade.php (Tipe percentage/fixed)
• app/Models/Transaction.php & migration add_discount_fields_to_transactions_table
• routes/web.php (/owner, /users, /promos, /export)
```

### Keputusan Arsitektur Tambahan:
```
• Export PDF menggunakan barryvdh/laravel-dompdf dengan layout HTML/CSS inline khusus print.
• Export CSV menggunakan native PHP output buffer (fputcsv) ketimbang package maatwebsite/excel untuk kemudahan dependensi.
• Menambahkan micro-interactions (hover-lift, fade-in-up stagger, row-glow) di semua list & grid untuk nuansa premium.
• Transaksi menyimpan `promo_code` dan `discount_amount` secara snapshot (bukan ID) agar tidak rusak jika promo dihapus.
• Query Dashboard kompleks dipusatkan di `DashboardService.php` agar komponen Livewire tetap tipis.
• Menggunakan Middleware `CheckRole` untuk memisahkan akses secara fisik antara `/pos` (Kasir) dan panel belakang (Owner/Admin).
```

### Yang Belum Selesai + Alasan:
```
• PWA (Opsional) — belum dimulai.
• Integrasi Payment Gateway API Asli — diputuskan menggunakan Konfirmasi Manual karena ini untuk Skripsi/Demo.
```

### Next Step — Fase Berikutnya:
```
Lanjut Fase 5 (Testing & Deploy):
1. Cek .env production readiness (APP_DEBUG=false, DB config).
2. Lakukan transaksi testing E2E (sebagai kasir).
3. Review laporan & PDF apakah sudah format Rupiah yang benar.
4. Export DB SQL.
5. Setup workflow deployment.
```

---

## 📝 LOG PERUBAHAN ANTAR FASE

```
[2026-03-14] Sprint 2 / Massive Feature Drop
  • Mengganti tema ke Premium Dark Mode (app.css)
  • Menyelesaikan workflow Kasir (struk, metode pembayaran, kasir nama dinamis).
  • Membangun UI Dashboard Owner lengkap dengan ApexCharts.
  • Membuat ExportController untuk sales-report ke PDF dan CSV.
  • Membangun UserManager untuk kontrol akses Owner/Admin/Kasir.
  • Mengintegrasikan PromoManager hingga perhitungan diskon di Kasir.
  • Menambahkan Micro-Interactions comprehensive di SELURUH views.

[2026-03-14] /init dijalankan — blueprint & roadmap dibuat
  • PROJECT.md diisi lengkap
  • DESIGN-SYSTEM.md diisi: dark mode, Indigo/Violet/Amber, Plus Jakarta Sans
  • HANDOFF.md diinisialisasi
  • Posisi: Fase 3 aktif, keranjang sudah ada

[~2026-03-12] Fase 1 & 2 selesai (pre-v6)
  • Setup, Auth, Layout, CRUD Produk & Kategori
```

---

## 💬 CATATAN UNTUK AI PENERIMA HANDOFF

Saat kamu (Claude atau Gemini) membuka file ini untuk melanjutkan:

1. Baca **BLUEPRINT PROYEK** → pahami struktur dan keputusan yang sudah dibuat
2. Baca **STATUS FASE TERAKHIR** → tahu persis di mana proyek berhenti
3. Baca **LOG PERUBAHAN** → lihat perjalanan fase sebelumnya
4. Konfirmasi ke user: *"Saya sudah baca HANDOFF.md. Saat ini kita berada di Fase 5 (Testing & Deploy). Semua fitur utama selesai, animasi UI terpasang. Siap lanjut ke testing/deployment?"*
5. Jangan ubah apapun di **BLUEPRINT PROYEK** secara fundamental kecuali user meminta pivot (seperti jadi Android API sebelumnya).

> Prinsip: Proyek ini sudah dalam status hampir rampung (Pre-Deploy). Fokus pada stabilitas, keamanan, dan polesan terakhir.

