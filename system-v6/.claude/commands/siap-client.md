# /siap-client — Checklist Sebelum Diserahkan ke Client

> Jalankan SETELAH semua fase selesai, SEBELUM demo atau serah terima ke client.
> Ini berbeda dari /deploy-check (yang fokus teknis hosting).
> Ini fokus pada: "Apakah app ini layak dilihat dan dipakai orang lain?"

---

## URUTAN EKSEKUSI

### Langkah 1 — Audit Konten & Data

```
[ ] Semua data dummy / placeholder sudah dihapus
    (contoh: "Lorem ipsum", "Test User", "password123", "0812345678")
[ ] Foto/gambar placeholder sudah diganti konten asli (atau diberi fallback yang rapi)
[ ] Nama produk/menu/item bukan lagi "Test Item 1", "Produk Coba", dll
[ ] Email & nomor telepon di konten bukan data pribadi developer
[ ] Tidak ada komentar kode yang berisi informasi sensitif
```

### Langkah 2 — Audit Keamanan Dasar

```
[ ] .env tidak ikut ter-commit ke Git (cek .gitignore)
[ ] APP_DEBUG=false di environment production
[ ] APP_KEY sudah di-generate (php artisan key:generate)
[ ] Password akun demo bukan "password" atau "123456"
[ ] Akun admin default sudah diganti password yang kuat
[ ] Tidak ada route debug yang terbuka (misal: /telescope tanpa auth di production)
[ ] File upload hanya menerima tipe yang diizinkan (validasi ada)
```

### Langkah 3 — Audit Fungsional (dari sudut pandang user)

```
[ ] Alur utama dicoba dari awal sampai akhir tanpa hambatan
    (contoh kasir: pilih menu → tambah ke keranjang → bayar → struk muncul)
[ ] Semua tombol punya respons yang jelas (loading state ada)
[ ] Pesan error yang muncul ke user mudah dimengerti (bukan stack trace)
[ ] Form yang gagal validasi menampilkan pesan yang jelas
[ ] Tidak ada halaman yang menampilkan "500 Server Error" ke user
[ ] Tidak ada halaman yang kosong tanpa penjelasan
```

### Langkah 4 — Audit Tampilan

```
[ ] Dicoba di browser yang biasa dipakai client (Chrome / Firefox)
[ ] Dicoba di ukuran layar yang akan dipakai (desktop / tablet / mobile)
[ ] Tidak ada elemen yang terpotong atau overlap di layar target
[ ] Foto/gambar tidak pecah atau terlalu lambat loading
[ ] Font terbaca dengan baik (kontras cukup)
[ ] Konsistensi warna & komponen di semua halaman
```

### Langkah 5 — Audit Konten Halaman

```
[ ] Nama app / instansi / cafe sudah benar di semua halaman
[ ] Logo sudah dipasang (atau placeholder yang rapi jika belum ada)
[ ] Judul tab browser (title tag) sudah benar
[ ] Halaman 404 custom ada (atau minimal tidak menampilkan error Laravel mentah)
[ ] Footer (jika ada) berisi info yang benar
```

### Langkah 6 — Persiapan Serah Terima

```
[ ] Ada akun demo yang bisa dipakai client untuk mencoba
[ ] Password akun demo sudah dicatat dan akan diberikan ke client
[ ] Panduan singkat penggunaan sudah disiapkan (minimal 1 halaman)
[ ] Client tahu cara menghubungi developer jika ada masalah
[ ] Backup database awal sudah dibuat sebelum diserahkan
```

---

## FORMAT TAMPILAN KE USER

```
══════════════════════════════════════════════════════
  CHECKLIST SIAP CLIENT — [Nama Proyek]
══════════════════════════════════════════════════════

Mari kita pastikan proyek ini siap dilihat client.
Saya akan tanya per bagian — jawab jujur.

[tampilkan per langkah, tunggu konfirmasi sebelum lanjut]

══════════════════════════════════════════════════════
```

---

## HASIL AUDIT

Setelah semua langkah selesai, tampilkan ringkasan:

```
══════════════════════════════════════════════════════
  HASIL AUDIT SIAP CLIENT
══════════════════════════════════════════════════════

✅ Lulus    : [jumlah item]
⚠️  Perlu fix: [jumlah item]
❌ Belum     : [jumlah item]

Item yang perlu diselesaikan sebelum serah terima:
  • [item 1]
  • [item 2]

══════════════════════════════════════════════════════
[jika semua lulus]
🎉 Proyek siap diserahkan ke client!
══════════════════════════════════════════════════════
```

---

## CATATAN

- Command ini **bukan pengganti /deploy-check** — keduanya harus dijalankan.
  - `/deploy-check` = teknis hosting (server, config, npm build)
  - `/siap-client` = kelayakan untuk dipakai orang lain
- Urutan yang benar: `/siap-client` → `/deploy-check` → deploy → serah terima
