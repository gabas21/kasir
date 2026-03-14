# /new-page [nama halaman]

## Peran Claude

Buat halaman Blade baru dengan konsistensi penuh terhadap DESIGN-SYSTEM.md dan PROJECT.md.

## Urutan

1. Baca DESIGN-SYSTEM.md → cek Registry Halaman apakah sudah ada yang mirip
2. Deklarasikan: "Halaman ini akan menggunakan komponen: X, Y, Z"
3. Buat file Blade dengan struktur:
   - Extend layout utama (`@extends('layouts.app')`)
   - Section per section dengan komentar `{{-- SECTION: nama --}}`
   - Gunakan token warna dari DESIGN-SYSTEM.md
   - Mobile-first: tanpa prefix → sm: → md: → lg:
4. Update Registry di DESIGN-SYSTEM.md setelah selesai

## Output yang Diharapkan
- File Blade lengkap
- Route yang perlu ditambahkan
- Controller method (jika diperlukan)
