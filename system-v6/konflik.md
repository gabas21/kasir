# konflik.md — Protokol Resolusi Konflik Antar AI

> Dirujuk oleh: `CLAUDE.md` (baris: "Ada konflik dengan keputusan Gemini") dan `GEMINI.md` (baris: "perbedaan pendapat dengan keputusan Claude")

---

## Apa yang Dimaksud "Konflik"?

Konflik terjadi ketika AI yang sedang aktif ingin membuat keputusan yang **bertentangan** dengan keputusan yang sudah dibuat oleh AI sebelumnya. Contoh:

- Claude ingin mengubah skema warna yang sudah ditetapkan Gemini di `DESIGN-SYSTEM.md`
- Gemini ingin mengubah struktur database yang sudah dikunci di `HANDOFF.md`
- Salah satu AI ingin menambah dependensi baru yang tidak ada di blueprint
- AI ingin mengganti pattern arsitektur (misal: dari Livewire ke Inertia)

---

## Hierarki Otoritas (Selalu Ikuti Ini)

```
1. HANDOFF.md — keputusan yang sudah dikunci TIDAK BISA diubah
2. DESIGN-SYSTEM.md — desain & token warna
3. PROJECT.md — scope & fitur yang disepakati
4. Kode yang sudah jalan & diverifikasi
5. Opini / preferensi AI saat ini
```

> AI yang datang **belakangan** WAJIB mengikuti hierarki ini.
> AI yang datang belakangan **TIDAK BOLEH override** keputusan yang sudah dikunci.

---

## Prosedur Saat Ada Konflik

### Langkah 1: Identifikasi Level Konflik

| Level | Deskripsi | Contoh |
|---|---|---|
| 🟢 Rendah | Preferensi gaya, bukan fungsional | Padding berbeda, nama variabel |
| 🟡 Sedang | Menyentuh keputusan yang bisa di-adjust | Urutan kolom, nama field baru |
| 🔴 Tinggi | Menyentuh blueprint yang sudah dikunci | Ganti stack, hapus fitur, ubah DB schema |

### Langkah 2: Tindakan Berdasarkan Level

**🟢 Konflik Rendah:**
→ Ikuti keputusan yang sudah ada. Jangan ubah tanpa alasan kuat.
→ Boleh catat saran di bagian bawah file yang relevan sebagai komentar.

**🟡 Konflik Sedang:**
→ **Tanya user** sebelum mengubah apapun.
→ Format pertanyaan:
```
⚠️ KONFLIK DITEMUKAN
File: [nama file]
Keputusan sebelumnya: [apa yang sudah ada]
Usulan saya: [apa yang ingin saya ubah]
Alasan: [mengapa lebih baik]
Apakah boleh saya ubah?
```

**🔴 Konflik Tinggi:**
→ **STOP. Jangan ubah apapun.**
→ Laporkan ke user dengan format:
```
🚨 KONFLIK ARSITEKTUR DITEMUKAN
Ini menyentuh keputusan yang sudah dikunci di HANDOFF.md.
[jelaskan konfliknya]
Saya tidak akan mengubah ini tanpa persetujuan eksplisit.
```
→ Tunggu instruksi user.

---

## Keputusan yang TIDAK BOLEH Diubah Tanpa Izin User

Berdasarkan `HANDOFF.md` proyek ini:

- Stack: TALL (Tailwind · Alpine · Laravel · Livewire) — tidak boleh diganti
- Skema database: tabel & kolom yang sudah ada di migration
- Role system: `kasir`, `admin`, `owner`
- Palet warna: Indigo #6366F1 + Violet #8B5CF6 + Amber #F59E0B (token di tailwind.config)
- Font: Plus Jakarta Sans
- Layout: POS route `/pos` terpisah dari admin `/dashboard`, `/owner`
- Payment: konfirmasi manual (bukan payment gateway)

---

## Catatan

> Prinsip utama: **Konsistensi lebih penting dari kreativitas.**
> Jika ragu, tanya. Jangan asumsi.
> Satu AI yang override AI lain tanpa izin = proyek berantakan.
