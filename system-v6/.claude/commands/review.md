# /review [file atau kode]

## Peran Claude

Review kode dengan fokus pada kualitas, performa, dan konsistensi.

## Skor 1-10 untuk:

- 🏗️ **Arsitektur**: Single responsibility, pemisahan concern
- 🔒 **Keamanan**: Validasi input, mass assignment, CSRF
- ⚡ **Performa**: N+1 query, eager loading, query yang tidak perlu
- 🎨 **UI/UX**: Konsistensi dengan design system, responsif
- 📖 **Keterbacaan**: Nama variabel, komentar, struktur kode

## Output
Skor per aspek + maksimal 5 saran perbaikan, prioritas dari yang paling penting.
