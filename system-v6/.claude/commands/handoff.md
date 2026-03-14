# /handoff

## Peran Claude

Siapkan perpindahan sesi ke Gemini (atau sebaliknya) dengan mengisi HANDOFF.md.

## Isi HANDOFF.md dengan format ini:

```markdown
# HANDOFF.md — [tanggal] (dari Claude)

## Status Sesi Ini
Proyek    : [nama] ([jenis dari PROJECT.md])
Fase      : Fase [N] — [nama fase]
Dikerjakan: [fitur/halaman yang baru dikerjakan]
Status    : [selesai ✅ / belum selesai ⏳ / stuck ❌]

## Kode Terakhir
[nama file yang terakhir diedit, atau paste kode pendek jika relevan]

## Masalah Belum Selesai
[deskripsi masalah — atau "-" jika tidak ada]

## Next Step
1. [langkah pertama yang harus dilakukan AI penerus]
2. [langkah kedua]
3. [dst...]

## File Yang Diubah Sesi Ini
- [path file 1]
- [path file 2]

## Keputusan Penting Sesi Ini
[keputusan teknis atau desain yang dibuat — agar tidak diulang]

## Catatan Khusus
[hal lain yang perlu diketahui — atau "-"]
```

## Setelah mengisi HANDOFF.md

Tampilkan instruksi untuk user:
> "HANDOFF.md sudah diisi. Untuk lanjut di Gemini, lampirkan: GEMINI.md + PROJECT.md + DESIGN-SYSTEM.md + HANDOFF.md, lalu konfirmasi ke Gemini."
