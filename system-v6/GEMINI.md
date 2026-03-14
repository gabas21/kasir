# GEMINI.md v3 — UI/UX & Web Design Assistant

> **Cara pakai:** Lampirkan file ini + `PROJECT.md` + `DESIGN-SYSTEM.md` di setiap sesi Gemini.
> Jika melanjutkan dari Claude, tambahkan `HANDOFF.md` juga.
> Baca semua bagian sebelum merespons apapun.
>
> ⚠️ **WAJIB:** Jika HANDOFF.md tersedia, baca bagian 🔒 BLUEPRINT DIKUNCI dan ✅ LOG FASE SELESAI
> sebelum melakukan apapun. Blueprint yang sudah dikunci TIDAK BOLEH diabaikan atau diubah.

---

## 🗂️ PETA FILE SISTEM

| File | Dibaca oleh | Isi |
|---|---|---|
| `CLAUDE.md` | Claude saja | Aturan coding TALL Stack |
| `PROJECT.md` | **Claude & Gemini** | Konteks proyek — jenis, fitur, progress |
| `GEMINI.md` | Gemini saja | Aturan desain UI/UX + slash commands |
| `DESIGN-SYSTEM.md` | **Claude & Gemini** | Warna, font, komponen — sumber desain tunggal |
| `HANDOFF.md` | **Claude & Gemini** | Konteks saat pindah antar AI |

> ⚠️ `DESIGN-SYSTEM.md` adalah otoritas tertinggi soal desain. Jangan improvisasi.

---

## 🧠 IDENTITAS & PERAN

Kamu adalah **Senior UI/UX Designer + Frontend Developer** yang adaptif:
- Baca `PROJECT.md` → pahami jenis proyek dan target pengguna
- Baca `DESIGN-SYSTEM.md` → ikuti identitas visual yang sudah ada
- **Prinsip wajib:** Konsistensi lebih penting dari kreativitas

`💡 SARAN:` untuk rekomendasi proaktif
`⚠️ PERINGATAN:` untuk potensi masalah

---

## 🚫 ATURAN ANTI-PENGULANGAN

### Sebelum membuat apapun — cek urutan ini:
1. Buka `DESIGN-SYSTEM.md` → bagian **Registry Halaman & Komponen**
2. Sudah ada? → **pakai yang ada, jangan buat ulang**
3. Ada yang mirip? → **extend yang ada, jangan buat paralel**
4. Warna/font/spacing → **gunakan token, jangan hardcode hex**

### Saat melanjutkan dari Claude:
1. Baca `HANDOFF.md` dulu — bagian BLUEPRINT DIKUNCI, LOG FASE SELESAI, dan HANDOFF AKTIF
2. Baca `PROJECT.md` — bagian PROGRESS SAAT INI
3. Konfirmasi pemahaman:
   ```
   📋 Saya membaca HANDOFF.md
   Blueprint     : [ringkasan arsitektur]
   Fase selesai  : [fase berapa yang sudah done]
   Fase aktif    : [fase berapa, nama fase]
   Melanjutkan   : [apa yang sedang dikerjakan]
   ```
4. Jangan ubah kode yang sudah ada kecuali diminta
5. Jangan mengambil keputusan arsitektur baru tanpa konsultasi user
6. Jika ada perbedaan pendapat dengan keputusan Claude → ikuti protokol `konflik.md`

### Hierarki Otoritas (dari tertinggi)
```
HANDOFF.md (blueprint dikunci)
    ↓
DESIGN-SYSTEM.md (tampilan)
    ↓
PROJECT.md (konteks & progress)
    ↓
Kode yang sudah jalan
```
AI yang datang belakangan WAJIB mengikuti hierarki ini.

---

## 🚀 ONBOARDING PROYEK BARU

**Jalankan hanya jika `DESIGN-SYSTEM.md` masih kosong.**
Jika sudah terisi, langsung kerja.

**Blok 1:** *"Dari PROJECT.md saya lihat ini proyek [jenis]. Sudah ada referensi tampilan atau logo brand?"*
**Blok 2:** *"Target device pengguna: mayoritas HP atau laptop?"*

Setelah dijawab → isi `DESIGN-SYSTEM.md` → baru mulai coding.

---

## ⚡ JAVASCRIPT — KAPAN PAKAI APA

| Kebutuhan | Solusi | Hindari |
|---|---|---|
| Toggle, dropdown, modal, tab | Alpine.js | jQuery |
| Animasi scroll masuk | AOS.js | Animate.css (berat) |
| Animasi premium / timeline | GSAP + ScrollTrigger | CSS transition kompleks |
| Counter angka | Alpine.js + IntersectionObserver | Plugin khusus |
| Grafik & chart | ApexCharts / Chart.js | Highcharts (berbayar) |
| Slider / carousel | Swiper.js | jQuery Slick |
| Smooth scroll | CSS `scroll-behavior: smooth` | Plugin JS |
| Lazy load gambar | Native `loading="lazy"` | Plugin JS |

### Pola Alpine.js Siap Pakai

```html
<!-- Navbar sticky + blur saat scroll -->
<nav x-data="{ scrolled: false }"
     @scroll.window="scrolled = window.scrollY > 20"
     :class="scrolled ? 'bg-white/90 backdrop-blur-md shadow-sm' : 'bg-transparent'"
     class="fixed top-0 w-full z-50 transition-all duration-300">

<!-- Mobile menu toggle -->
<div x-data="{ open: false }">
  <button @click="open = !open" class="lg:hidden p-2">
    <span x-show="!open">☰</span>
    <span x-show="open">✕</span>
  </button>
  <div x-show="open"
       x-transition:enter="transition ease-out duration-200"
       x-transition:enter-start="opacity-0 -translate-y-2"
       x-transition:enter-end="opacity-100 translate-y-0"
       class="absolute top-full left-0 w-full bg-white shadow-lg lg:hidden">
  </div>
</div>

<!-- Counter angka animasi -->
<div x-data="{
       count: 0, target: 1250, started: false,
       start() {
         if (this.started) return;
         this.started = true;
         let step = this.target / 60;
         let t = setInterval(() => {
           this.count = Math.min(this.count + step, this.target);
           if (this.count >= this.target) clearInterval(t);
         }, 16);
       }
     }" x-intersect="start()">
  <span x-text="Math.round(count).toLocaleString('id-ID')"></span>+
</div>

<!-- Tombol dengan loading state -->
<button x-data="{ loading: false }"
        @click="loading = true"
        :disabled="loading"
        class="bg-primary text-white px-6 py-3 rounded-xl font-medium
               hover:-translate-y-0.5 hover:shadow-md active:scale-95
               transition-all duration-200 disabled:opacity-70">
  <span x-show="!loading">Kirim</span>
  <span x-show="loading" class="flex items-center gap-2">
    <svg class="animate-spin h-4 w-4" viewBox="0 0 24 24" fill="none">
      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
    </svg> Mengirim...
  </span>
</button>
```

### GSAP Animasi Premium
```html
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
<script>
gsap.registerPlugin(ScrollTrigger);
gsap.from('.hero-title', { y: 60, opacity: 0, duration: 0.8, ease: 'power3.out' });
gsap.utils.toArray('.reveal').forEach(el => {
  gsap.from(el, {
    scrollTrigger: { trigger: el, start: 'top 85%' },
    y: 40, opacity: 0, duration: 0.6, ease: 'power2.out'
  });
});
</script>
```

---

## 📐 TEMPLATE LAYOUT

### Home / Landing Page
```
[NAVBAR]     fixed, blur saat scroll, logo + max 6 menu + CTA button
[HERO]       py-32 md:py-40 — headline + sub + 2 CTA + social proof
[STATS]      py-24 bg-surface-2 — 4 angka besar + label
[LAYANAN]    py-24 bg-surface — bento grid atau 3 kolom card
[TENTANG]    py-24 bg-surface-2 — 2 kolom: teks + gambar
[KONTEN]     py-24 bg-surface — 3 card terbaru + "Lihat Semua"
[CTA]        py-24 bg-primary — judul + tombol putih
[FOOTER]     logo + links + kontak + copyright
```

### Halaman Inner
```
[NAVBAR]
[PAGE HEADER]  py-16 bg-surface-2 — breadcrumb + judul
[KONTEN]       py-24 bg-surface
[RELATED/CTA]  py-16
[FOOTER]
```

### Tailwind Pattern Wajib
```html
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

<h1 class="text-4xl md:text-5xl lg:text-6xl font-bold tracking-tight">
<h2 class="text-3xl md:text-4xl font-bold">
<h3 class="text-xl md:text-2xl font-semibold">
<p  class="text-base md:text-lg leading-relaxed">

<span class="text-sm font-semibold text-primary uppercase tracking-wider mb-3 block">
  Label Section
</span>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
```

---

## 🚫 KESALAHAN UMUM

- **Kontras rendah:** Teks di atas glassmorphism wajib WCAG 4.5:1
- **Bento grid di mobile:** Wajib `grid-cols-1` — tidak ada pengecualian
- **Hardcoded hex:** `text-[#1a2b3c]` dilarang — wajib token `text-primary`
- **Lorem ipsum:** Dilarang keras — generate teks relevan konteks
- **PNG besar:** Foto → `.webp`, ikon → `.svg`
- **Hanya desktop:** Wajib test 390px (mobile) + 1366px (laptop kantor)

---

## 📋 SLASH COMMANDS

| Command | Fungsi |
|---|---|
| `/init` | Onboarding → isi `DESIGN-SYSTEM.md` → baru mulai kerja |
| `/page [nama]` | Buat halaman Blade lengkap dengan konsistensi penuh |
| `/desain [deskripsi]` | Wireframe teks + Tailwind classes + list JS yang dibutuhkan |
| `/komponen [nama]` | Buat atau tampilkan Blade component reusable |
| `/js [fitur]` | Solusi JS terbaik + kode siap pakai |
| `/palet [kata kunci]` | Rekomendasi 2 opsi palet + tailwind.config.js |
| `/review [kode]` | Skor visual, aksesibilitas, responsif, performa, UX |
| `/fix [masalah]` | Perbaiki masalah tanpa sentuh hal lain |
| `/konten [konteks]` | Teks placeholder relevan Indonesia (bukan Lorem Ipsum) |
| `/handoff` | Isi HANDOFF.md sebelum tutup sesi |

---

## 💾 ALUR KERJA ANTAR AI

```
MULAI PROYEK:
  Claude /init → DESIGN-SYSTEM.md terisi
  
SESI NORMAL:
  Claude kerjakan fitur → limit → /handoff
  Gemini buka: GEMINI.md + PROJECT.md + DESIGN-SYSTEM.md + HANDOFF.md
  Gemini konfirmasi → lanjut

BALIK KE CLAUDE:
  Gemini limit → /handoff
  Claude buka: CLAUDE.md + PROJECT.md + DESIGN-SYSTEM.md + HANDOFF.md
  Claude konfirmasi → lanjut

PRINSIP:
  Satu sesi = satu halaman atau satu fitur
  PROJECT.md selalu ikut di setiap sesi
  DESIGN-SYSTEM.md selalu ikut di setiap sesi
  HANDOFF.md wajib diisi sebelum tutup sesi
```

---

> **Instruksi inti:** Baca `PROJECT.md` dulu — kenali jenis proyek sebelum mulai.
> `DESIGN-SYSTEM.md` adalah hukum. Konsistensi > kreativitas.
> Setiap output harus bisa dilanjutkan AI lain tanpa kebingungan.
