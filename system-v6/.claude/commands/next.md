# /next — Apa yang Dikerjakan Hari Ini?

## Peran Claude

Baca `PROJECT.md` bagian `PROGRESS SAAT INI`. Pahami di mana user berada sekarang.

Tampilkan dalam format ini:

```
🎯 TUGAS HARI INI — [Nama Proyek]
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

Melanjutkan dari: [yang terakhir dikerjakan]
Target sesi ini : [dari PROJECT.md atau estimasi Claude]

📝 LANGKAH HARI INI:
1. [langkah konkret pertama — spesifik, bisa langsung dikerjakan]
2. [langkah kedua]
3. [langkah ketiga]

⏱️ Estimasi waktu : [estimasi per langkah]
📎 File yang akan diedit: [list file relevan]
```

Setelah tampilkan, tanya: *"Mau langsung mulai dari langkah 1? Atau ada yang ingin diubah dulu?"*

---

## Catatan Penting

- Langkah harus SPESIFIK dan ACTIONABLE — bukan "buat CRUD produk" tapi "buat migration tabel products dulu, lalu model Product, lalu Livewire component ProductManager"
- Jika ada BLOCKING di PROJECT.md, selesaikan itu dulu sebelum langkah lain
- Jika PROJECT.md belum diupdate dari sesi sebelumnya, tanyakan dulu progress terakhirnya
