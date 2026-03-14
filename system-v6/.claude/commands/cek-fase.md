# /cek-fase — Checkpoint Sebelum Fase Dinyatakan Selesai

> Dijalankan OTOMATIS oleh Claude sebelum menjalankan prosedur `fase-selesai`.
> Tujuan: memastikan fase benar-benar siap, bukan hanya "kode sudah ditulis".

---

## PRINSIP

Fase selesai bukan berarti "kode sudah ada".
Fase selesai berarti **semua output bisa dijalankan dan ditest oleh user**.

---

## CHECKLIST PER FASE

Claude wajib tampilkan checklist ini ke user dan minta konfirmasi satu per satu.

### ✅ Checklist Universal (semua fase)

```
[ ] Server berjalan tanpa error (php artisan serve + npm run dev)
[ ] Tidak ada error merah di browser console
[ ] Semua halaman yang dibuat di fase ini bisa dibuka
[ ] Tidak ada route yang 404
[ ] Migration sudah dijalankan (php artisan migrate)
[ ] Cache sudah dibersihkan (php artisan optimize:clear)
```

### ✅ Checklist Spesifik per Konten Fase

**Jika fase ini membuat AUTH / LOGIN:**
```
[ ] Bisa register akun baru
[ ] Bisa login dengan akun yang dibuat
[ ] Bisa logout
[ ] Redirect setelah login menuju halaman yang benar
[ ] Halaman protected tidak bisa diakses tanpa login
```

**Jika fase ini membuat CRUD:**
```
[ ] Bisa tambah data baru → muncul di list
[ ] Bisa edit data yang ada → perubahan tersimpan
[ ] Bisa hapus data → data hilang dari list
[ ] Validasi bekerja (coba submit form kosong → muncul pesan error)
[ ] Flash message / notifikasi muncul setelah aksi
```

**Jika fase ini membuat TRANSAKSI / KASIR:**
```
[ ] Bisa tambah item ke keranjang
[ ] Total harga terhitung benar
[ ] Bisa proses pembayaran
[ ] Data tersimpan ke database (cek tabel langsung)
[ ] Struk / konfirmasi muncul setelah transaksi
[ ] Stok berkurang setelah transaksi (jika ada stok)
```

**Jika fase ini membuat LAPORAN / DASHBOARD:**
```
[ ] Data yang tampil sesuai dengan data di database
[ ] Filter tanggal bekerja
[ ] Angka total/summary benar (verifikasi manual)
[ ] Export berfungsi (jika ada)
```

**Jika fase ini membuat UPLOAD FILE:**
```
[ ] File berhasil diupload
[ ] File tersimpan di storage yang benar
[ ] File bisa ditampilkan kembali
[ ] Validasi tipe dan ukuran file bekerja
```

---

## FORMAT TAMPILAN KE USER

```
══════════════════════════════════════════════════════
  CHECKPOINT FASE [N] — Sebelum Dinyatakan Selesai
══════════════════════════════════════════════════════

Sebelum kita tutup fase ini, pastikan semua ini
sudah kamu coba sendiri di browser:

WAJIB (semua fase):
  [ ] Server jalan tanpa error
  [ ] Tidak ada error di browser console
  [ ] Semua halaman bisa dibuka

SPESIFIK FASE INI:
  [tampilkan checklist yang relevan]

──────────────────────────────────────────────────────
Ketik "semua oke" jika sudah dicek semuanya.
Ketik item yang bermasalah jika ada yang gagal.
══════════════════════════════════════════════════════
```

---

## JIKA ADA ITEM YANG GAGAL

Jangan tutup fase. Selesaikan dulu item yang gagal, baru jalankan checkpoint ulang.

Jika item yang gagal bukan bagian dari scope fase ini (misal: ditemukan bug di fase sebelumnya), catat di HANDOFF.md bagian "Yang Belum Selesai" dengan label `[dari fase N]` dan lanjutkan.

---

## KAPAN BOLEH SKIP

Checklist boleh disingkat (tapi tidak dihilangkan) jika:
- Fase yang selesai hanya berisi perubahan kecil / cosmetic
- User eksplisit menyatakan "skip cek, lanjut saja"

Jika skip, Claude tetap wajib catat di HANDOFF.md: `"Checkpoint fase [N] di-skip atas permintaan user."`
