# /deploy-check

## Peran Claude

Audit kesiapan proyek sebelum upload ke hosting.

## Checklist

### Environment
- [ ] `.env` production sudah diset (APP_ENV=production, APP_DEBUG=false)
- [ ] `APP_KEY` sudah di-generate
- [ ] Database credentials production sudah benar
- [ ] `MAIL_*` sudah dikonfigurasi (jika ada fitur email)

### Optimasi
- [ ] `php artisan optimize` sudah dijalankan
- [ ] `npm run build` sudah dijalankan (bukan dev)
- [ ] `php artisan storage:link` sudah dijalankan
- [ ] Semua migration sudah dijalankan

### Keamanan
- [ ] Tidak ada `dd()` atau `dump()` yang tertinggal
- [ ] `.env` tidak ikut di-commit ke git
- [ ] File permission sudah benar (storage/ dan bootstrap/cache/ writable)

### Testing
- [ ] Semua fitur utama sudah dicoba manual
- [ ] Mobile view sudah dicek
- [ ] Error handling sudah ada (404, 500)

Output: checklist per item + apa yang perlu diperbaiki sebelum deploy.
