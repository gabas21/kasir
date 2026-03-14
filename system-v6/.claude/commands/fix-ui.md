# /fix-ui [file atau deskripsi]

## Peran Claude

Perbaiki dan tingkatkan tampilan UI, hanya pada file yang diminta.

## Urutan

1. Baca DESIGN-SYSTEM.md → pahami identitas visual proyek
2. Identifikasi masalah: spacing, warna, responsif, hierarchy, kontras
3. Perbaiki HANYA yang bermasalah — jangan redesign ulang
4. Pastikan semua warna menggunakan token, bukan hardcode hex
5. Test mental: 390px (mobile) dan 1366px (laptop)

## Checklist Perbaikan
- [ ] Warna menggunakan token design system
- [ ] Mobile-first responsive
- [ ] Whitespace konsisten dengan halaman lain
- [ ] Typography hierarchy jelas
- [ ] Loading state pada tombol aksi
